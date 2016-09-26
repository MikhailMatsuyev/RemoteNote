# RemoteNote

Клиент-серверное приложение - записная книжка.

С помощью интерфейса пользователь может делать любое количество текстовых записей. 
Новая запись создаётся нажатием на кнопку добавить, а весь введенный текст сохраняется автоматически по мере набора.
Предыдущие записи должны быть доступны в виде списка и могут быть отредактированы непосредственно или по щелчку.

Каждая запись имеет название и текст любой длины. Кроме того, следует предусмотреть автоматическое сохранение даты создания.

Данные должны сохраняться сразу после ввода, автоматически.
При загрузке страницы все имеющиеся записи должны быть восстановлены. Приложение должно восстановить предыдущее состояние. Состояние необходимо хранить на стороне клиента.

Требования и ограничения:
PHP 5.6

Формат данных для клиент-серверного обмена JSON
Имплементация на чистом PHP, JS. Архитектура MVC. 
Приложение должно запускаться на встроенном веб-сервере php
