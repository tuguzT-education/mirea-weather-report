<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/defines/functions.php';

use WeatherReport\Database;

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

if (loggedIn() && isAdmin()) try {
	$_SESSION['data_users'] = array();

	$database = Database::connect();
	$database->setDatabase('userdata');

	$query = 'SELECT `name`, `surname`, `email`, `roleID` FROM `general`';
	$result = $database->query($query);
	if ($result->num_rows === 0) {
		throw new Exception('Список пользователей пуст!', -1);
	}

	$query = 'SELECT `name` FROM `roles` WHERE id = ?';
	while ($row = $result->fetch_assoc()) {
		if (strcmp($row['email'], $_SESSION['email']) !== 0) {
			$temp = $database->query($query, (int)$row['roleID']);
			$name = $temp->fetch_assoc()['name'];
			$row['role'] = $name;
			$_SESSION['data_users'][] = $row;
		}
	}
	if (empty($_SESSION['data_users'])) {
		throw new Exception('Список других пользователей пуст!', -1);
	}
} catch (Exception $exception) {
	$_SESSION['error'] = $exception->getCode() === -1
		? $exception->getMessage()
		: "Внутренняя ошибка №{$exception->getCode()}: \"{$exception->getMessage()}\"";
}
