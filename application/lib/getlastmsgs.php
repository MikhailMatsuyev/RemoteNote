<?php

require_once('gbookrecord.class.php');

define('MAX_RECORDS', 10);

$db_path='d:/gbook.db';
$db = new PDO("sqlite:$db_path");

$res     = $db->query('SELECT * FROM gbook ORDER BY date DESC');
$lastMod = $db->query('SELECT MAX(date) AS max_date FROM gbook');

$lastMod->setFetchMode(PDO::FETCH_ASSOC);//установим режим выборки
foreach ($lastMod->fetch() as $key){
    $lastMod=$key;
}

$records     = array();
$recordCount = 0;

while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
    $records[] = new GBookRecord(
		$row['id'],
		$row['postname'],
		$row['message'],
		$row['date']
	);
	$recordCount++;
	if ($recordCount >= MAX_RECORDS) {
		break;
	}
}

// Передаем заголовки и JSON пакет данных
header('Content-type: text/plain; charset=utf-8');
header('Cache-Control: no-store, no-cache');
header('Expires: ' . date('r'));
header('Last-Modified: ' . date('r', $lastMod));
echo json_encode($records);

