<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/InputText.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/defines/functions.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/defines/apis.php';

use WeatherReport\Database;

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

try {
	if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
		throw new Exception('Внутренняя ошибка!', -1);
	}

	if (isset($_POST['add_location_point'])) {
		$name = trim($_POST['add_location_name']);
		if (empty($name)) {
			inputError('Название не может быть пустым!',
				'name_input', 'account.php#add_location');
		}
		foreach ($_SESSION['data_locations'] as $row) {
			if (strcmp($row['name'], $name) === 0) {
				inputError('Местоположение с таким названием уже существует!',
					'name_input', 'account.php#add_location');
			}
		}

		$latitude = $_POST['add_location_latitude'];
		if (!isValidLatitude($latitude)) {
			inputError('Широта должна лежать в интервале от -90° включительно ' .
				'до 90° невключительно!', 'latitude_input', 'account.php#add_location');
		}

		$longitude = $_POST['add_location_longitude'];
		if (!isValidLongitude($longitude)) {
			inputError('Долгота должна лежать в интервале от -180° невключительно ' .
				'до 180° включительно!', 'longitude_input', 'account.php#add_location');
		}

		$database = Database::connect();
		$database->setDatabase('userdata');

		$query = 'INSERT INTO `locations` VALUES(?, ?, ?, ?)';
		$database->query($query, $name, $_SESSION['email'], $latitude, $longitude);
	} elseif (isset($_POST['add_location_address'])) {
		$name = $_POST['add_location_selected_address'];
		foreach ($_SESSION['data_locations'] as $row) {
			if (strcmp($row['name'], $name) === 0) {
				throw new Exception('Местоположение с таким названием уже существует!', -1);
			}
		}
		$data_geocoding = unserialize($_SESSION['data_geocoding'])->items;
		foreach ($data_geocoding as $item) {
			if (strcmp($item->title, $name) === 0) {
				$latitude = $item->position->lat;
				$longitude = $item->position->lng;

				$database = Database::connect();
				$database->setDatabase('userdata');

				$query = 'INSERT INTO `locations` VALUES(?, ?, CAST(? AS DECIMAL(10, 8)), CAST(? AS DECIMAL(11, 8)))';
				$database->query($query, $name, $_SESSION['email'], $latitude, $longitude);
				break;
			}
		}
	} else {
		throw new Exception('Внутренняя ошибка!', -1);
	}
} catch (Exception $exception) {
	switch ($exception->getCode()) {
		case -1:
			$_SESSION['error'] = $exception->getMessage();
			break;
		case 1062:
			if (isset($_POST['add_location_point'])) {
				inputError('Местоположение с таким названием уже существует!',
					'name_input', 'account.php#add_location');
			} else {
				$_SESSION['error'] = 'Местоположение с таким названием уже существует!';
			}
			break;
		default:
			$_SESSION['error'] = "Внутренняя ошибка №{$exception->getCode()}: \"{$exception->getMessage()}\"";
	}
} finally {
	$_SESSION['add_location_tab'] = 1;
	redirect('account.php');
}
