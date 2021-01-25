<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/defines/functions.php';

use WeatherReport\Database;

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

try {
	if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['admin_request'])) {
		throw new Exception('Ошибка: нет POST-запроса!', -1);
	}
	$text = $_POST['admin_request_text'];

	$database = Database::connect();
	$database->setDatabase('userdata');

	$database->query('
		CREATE TABLE IF NOT EXISTS `admin_requests` (
			`email` VARCHAR(320) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
			`text` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
			FOREIGN KEY (`email`) REFERENCES `general`(`email`),
			UNIQUE (`email`)
		) ENGINE=MyISAM;'
	);

	$query = 'INSERT INTO `admin_requests` VALUES(?, ?)';
	$database->query($query, $_SESSION['email'], $text);
} catch (Exception $exception) {
	switch ($exception->getCode()) {
		case -1:
			$_SESSION['error'] = $exception->getMessage();
			break;
		case 1062:
			$_SESSION['error'] = 'Вы уже выполняли запрос на администрирование! ' .
				'Ожидайте ответа от администратора!';
			break;
		default:
			$_SESSION['error'] = "Внутренняя ошибка №{$exception->getCode()}: \"{$exception->getMessage()}\"";
	}
} finally {
	redirect('account.php');
}
