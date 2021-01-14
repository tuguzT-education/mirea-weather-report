<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/InputText.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/defines/functions.php';

use WeatherReport\Database;

function inputError(string $errorMessage, string $id) {
	$input = unserialize($_SESSION[$id]);
	$input->setErrorMessage($errorMessage);
	$_SESSION[$id] = serialize($input);
	redirect('account.php#add_location');
}

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

try {
	if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['add_location'])) {
		throw new Exception('Ошибка: нет POST-запроса!', -1);
	}

	$add_location_name = trim($_POST['add_location_name']);
	if (empty($add_location_name)) {
		inputError('Название не может быть пустым!', 'name_input');
	}

	$latitude = $_POST['add_location_latitude'];
	if (!isValidLatitude($latitude)) {
		inputError('Широта должна лежать в интервале от -90° включительно ' .
			'до 90° невключительно!', 'latitude_input');
	}

	$longitude = $_POST['add_location_longitude'];
	if (!isValidLongitude($longitude)) {
		inputError('Долгота должна лежать в интервале от -180° невключительно ' .
			'до 180° включительно!', 'longitude_input');
	}

	$database = Database::connect();
	$database->setDatabase('userdata');

	$query = 'INSERT INTO `locations` VALUES(?, ?, ?, ?)';
	$database->query(
		$query,
		$add_location_name, $_SESSION['email'],
		$latitude, $longitude
	);
} catch (Exception $exception) {
	switch ($exception->getCode()) {
		case -1:
			$_SESSION['error'] = $exception->getMessage();
			break;
		case 1062:
			inputError('Местоположение с таким названием уже существует!', 'name_input');
			break;
		default:
			$_SESSION['error'] = "Внутренняя ошибка №{$exception->getCode()}: \"{$exception->getMessage()}\"";
	}
} finally {
	redirect('account.php');
}
