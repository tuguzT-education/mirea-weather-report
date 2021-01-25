<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/defines/functions.php';

use WeatherReport\Database;

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

try {
	if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['accept_admin'])) {
		throw new Exception('Ошибка: нет POST-запроса!', -1);
	}
	$email = $_POST['accept_admin'];

	$database = Database::connect();
	$database->setDatabase('userdata');

	$query = 'UPDATE `general` SET `roleID` = 1 WHERE `email` = ?';
	$database->query($query, $email);

	$query = 'DELETE FROM `admin_requests` WHERE `email` = ?';
	$database->query($query, $email);
} catch (Exception $exception) {
	$_SESSION['error'] = $exception->getCode() === -1
		? $exception->getMessage()
		: "Внутренняя ошибка №{$exception->getCode()}: \"{$exception->getMessage()}\"";
} finally {
	redirect('admin_panel.php');
}
