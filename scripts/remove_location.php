<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/defines/functions.php';

use WeatherReport\Database;

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

try {
	if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['remove_location'])) {
		throw new Exception('Ошибка: нет POST-запроса!', -1);
	}
	$database = Database::connect();
	$database->setDatabase('userdata');

	$query = 'DELETE FROM `locations` WHERE email = ? AND name = ?';
	$remove_location_name = $_POST['remove_location_name'];
	$database->query($query, $_SESSION['email'], $remove_location_name);
} catch (Exception $exception) {
	$_SESSION['error'] = $exception->getCode() === -1
		? $exception->getMessage()
		: "Внутренняя ошибка №{$exception->getCode()}: \"{$exception->getMessage()}\"";
} finally {
	redirect('account.php');
}
