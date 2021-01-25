<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/defines/functions.php';

use WeatherReport\Database;

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

try {
	if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['remove_user'])) {
		throw new Exception('Ошибка: нет POST-запроса!', -1);
	}
	$email = $_POST['remove_user_email'];
	foreach ($_SESSION['data_users'] as $row) {
		if (strcmp($row['email'], $email) === 0) {
			if ($row['roleID'] == 1) {
				throw new Exception('Невозможно удалить пользователя с ролью администратора!', -1);
			}
			break;
		}
	}

	$database = Database::connect();
	$database->setDatabase('userdata');

	$query = 'DELETE FROM `locations` WHERE email = ?';
	$database->query($query, $email);

	$query = 'DELETE FROM `general` WHERE email = ?';
	$database->query($query, $email);
} catch (Exception $exception) {
	$_SESSION['error'] = $exception->getCode() === -1
		? $exception->getMessage()
		: "Внутренняя ошибка №{$exception->getCode()}: \"{$exception->getMessage()}\"";
} finally {
	redirect('admin_panel.php');
}
