<?php

$db_path='d:/gbook.db';
$db = new SQLite3($db_path);

$db->query(
    "CREATE TABLE gbook
		(
			id INTEGER PRIMARY KEY, -- Код записи
			postname TEXT,          -- Автор записи 
			message TEXT,           -- Текст сообщения
			date NUMERIC            -- Дата и время создания записи
		)"
);

$db->query(
	"CREATE INDEX gbook_date ON gbook(date ASC)"
);
?>