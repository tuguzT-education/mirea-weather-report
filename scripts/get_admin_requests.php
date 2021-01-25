<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/defines/functions.php';

use WeatherReport\Database;

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

if (loggedIn() && isAdmin()) try {
	$_SESSION['data_admin_requests'] = array();

	$database = Database::connect();
	$database->setDatabase('userdata');

	$query = 'SELECT * FROM `admin_requests`';
	$result = $database->query($query);
	if ($result->num_rows === 0) {
		throw new Exception('Список запросов пуст!', -1);
	}

	$query = 'SELECT `name`, `surname` FROM `general` WHERE `email` = ?';
	while ($row = $result->fetch_assoc()) {
		$temp = $database->query($query, $row['email']);
		$temp = $temp->fetch_assoc();
		$row['name'] = $temp['name'];
		$row['surname'] = $temp['surname'];
		$_SESSION['data_admin_requests'][] = $row;
	}
} catch (Exception $exception) {
	$_SESSION['error'] = $exception->getCode() === -1
		? $exception->getMessage()
		: "Внутренняя ошибка №{$exception->getCode()}: \"{$exception->getMessage()}\"";
}
