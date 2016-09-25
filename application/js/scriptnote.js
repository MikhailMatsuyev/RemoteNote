// Частота проверок блокнота (mSec)
var checkInterval = 2000;
// Таймер проверки
var checkTimer;

// Дата и время последнего изменения блокнота,
// по умолчанию - дата в прошлом
var lastUpdate = new Date("01/01/1900");

// Функция возвращает дату и время последней модифиации блокнота
function getLastModified()
{
    // Выполним запрос HEAD к скрипту блокнота...
    var req = new XMLHttpRequest();
    req.open('HEAD', '/application/lib/getlastmsgs.php', false);
    req.send(null);
    // Создадим объект Date на основе ответа Last-Modified
    return new Date(req.getResponseHeader("Last-Modified"));
}

// Создание элемента с текcтом
function createElement(tag, text)
{
    var element = document.createElement(tag);
    var elementText = document.createTextNode(text);

    element.appendChild(elementText);
    return element;
}

// Функция проверяет изменения в блокноте
function checkUpdates()
{
    var lastModified = getLastModified();

    if (lastUpdate < lastModified){
        // Запрос новых данных из блокнота
        var req = new XMLHttpRequest();
        req.onreadystatechange = function()
        {

            if (req.readyState != 4) return;

            var records = req.responseText;

            var records = JSON.parse(req.responseText);

            // Элемент для отображения
            var divResult = document.getElementById("divResult");
            // Удаление старых записей
            while (divResult.hasChildNodes()) divResult.removeChild(divResult.lastChild);
            // Отображение записей блокнота

            for (var i = 0; i < records.length; i++)
            {
                // Элемент для размещения записи
                var divRecord = document.createElement("div");
                divRecord.className = "divRecord";
                // Ссылка на автора
                var spDate = createElement("span", "Запись создана: "+records[i].date);
                var aAuthor = createElement("h4", records[i].postname);
                var spId = createElement("span", "  Номер записи: "+records[i].id);
                var spId = createElement("a", "  Номер записи: "+records[i].id);
                spId.setAttribute("href", "edit/"+records[i].id);

                spId.setAttribute("title", "Редактировать");
                //spId.setAttribute("onclick", "return clickMe()");
                spId.addEventListener( 'click',(function (e) { // действие при клике
                    //e.preventDefault(); // Отменяем переход по ссылке
                    //alert (this.href);
                    localStorage.setItem('locStorTxtName', "");
                    localStorage.setItem('locStorTxtMessage', "");
                }))

                divRecord.appendChild(spDate);
                divRecord.appendChild(spId);
                divRecord.appendChild(aAuthor);

                // Текст сообщения
                var pMessage = createElement("p", records[i].message);
                divRecord.appendChild(pMessage);
                divResult.appendChild(divRecord);
                // Время  последнего отображения
                lastUpdate = lastModified;
            }
        };
        req.open("GET", "/application/lib/getlastmsgs.php", true);
        req.send(null);

    }
    // Таймер на следующую проверку
    checkTimer = window.setTimeout("checkUpdates()", checkInterval);
}


///////////////////////////////////////////////////////////////////////////////////////////////////

// Класс записи
function Record(postname, message)
{
    this.postname = postname;
    this.message = message;
}

// Добавление новой записи
function addRecord() {
    // Элементы управления

    var txtName = document.getElementById("txtName");
    var txtMessage = document.getElementById("txtMessage");

    // Проверка заполнения элементов
    if (txtName.value == "" || txtMessage.value == "") {
        alert("Необходимо заполнить все поля...");
        return;
    }

    // Создание объекта записи
    var record = new Record(txtName.value, txtMessage.value);
    // Сериализация в JSON
    var jsonData = JSON.stringify(record);

    // Передача данных
    var req = new XMLHttpRequest();
    req.onreadystatechange = function () {
        if (req.readyState != 4) return;
        // Завершение передачи... Сброс таймера и показ сообщения
        window.clearTimeout(checkTimer);
        checkUpdates();
    };

    req.open("POST", "/application/lib/addrecord.php", true);
    req.setRequestHeader("Content-Type", "text/plain");
    req.send(jsonData);
}
        // Загрузка страницы
        window.onload = function()
        {
            checkUpdates();
            if(localStorage.getItem("locStorTxtMessage")!=""){
                getTxtNameFromLocStor();
                getTxtMessageFromLocStor();
            }
        }


function getTxtNameFromLocStor()
{
    document.getElementById('txtName').value=localStorage.getItem("locStorTxtName");
}

function getTxtMessageFromLocStor()
{
    document.getElementById('txtMessage').innerHTML=localStorage.getItem("locStorTxtMessage");
}

function setTxtNameToLocStor(v)
{
    localStorage.setItem('locStorTxtName', v);
}

function setTxtMessageToLocStor(v)
{
    localStorage.setItem('locStorTxtMessage', v);
}


