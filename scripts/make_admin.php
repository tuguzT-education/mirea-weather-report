<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/defines/functions.php';

use WeatherReport\Database;

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

try {
	if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['make_admin'])) {
		throw new Exception('Ошибка: нет POST-запроса!', -1);
	}
	$email = $_POST['make_admin_email'];
	foreach ($_SESSION['data_users'] as $row) {
		if (strcmp($row['email'], $email) === 0) {
			if ($row['roleID'] == 1) {
				throw new Exception('Данный пользователь уже является администратором!', -1);
			}
			break;
		}
	}

	$database = Database::connect();
	$database->setDatabase('userdata');

	$query = 'UPDATE `general` SET `roleID` = 1 WHERE `email` = ?';
	$database->query($query, $email);
} catch (Exception $exception) {
	$_SESSION['error'] = $exception->getCode() === -1
		? $exception->getMessage()
		: "Внутренняя ошибка №{$exception->getCode()}: \"{$exception->getMessage()}\"";
} finally {
	redirect('admin_panel.php');
}
