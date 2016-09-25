<?php
/*
** Скрипт возвращает последние записи в гостевой книге
*/

// Читаем данные, переданные в POST
$rawPost = file_get_contents('php://input');

// Заголовки ответа
header('Content-type: text/plain; charset=utf-8');
header('Cache-Control: no-store, no-cache');
header('Expires: ' . date('r'));

// Если данные были переданы...
if ($rawPost) {
	// Разбор пакета JSON
	$record = json_decode($rawPost);
    try
    {
        // Открытие БД
        $db_path='d:/gbook.db';

        $db = new PDO("sqlite:$db_path");

        // Подготовка данных
        $postname = htmlspecialchars($record->postname);
        $message = htmlspecialchars($record->message);


        $date = time();

        // Запрос
        $query=$db->prepare("INSERT INTO gbook (postname, message, date) VALUES (?, ?, ?)");

        $query->bindParam(1, $postname);
        $query->bindParam(2, $message);
        $query->bindParam(3, $date);

        $query->execute();

    }catch(PDOException $e){
        echo $e->getMessage();
    }
	// Возврат результата
	echo json_encode(
		array
		(
			'result'          => 'OK',
			'lastInsertRowId' => $db->lastInsertRowID()
		)
	);
} else {
	// Данные не переданы
	echo json_encode(
		array
		(
			'result' => 'No data'
		)
	);
}
