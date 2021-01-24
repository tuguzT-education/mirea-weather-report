<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/InputText.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/defines/functions.php';

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

try {
	if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
		throw new Exception('Внутренняя ошибка!', -1);
	}

	unset($_SESSION['data_weather']);

	if (isset($_POST['weather_choose_location_list']) && isset($_SESSION['data_locations'])) {
		$name = $_POST['weather_choose_location_list_name'];
		$_SESSION['data_weather_name'] = $name;

		foreach ($_SESSION['data_locations'] as $row) {
			if (strcmp($name, $row['name']) === 0) {
				$latitude = $row['latitude'];
				$_SESSION['data_weather_latitude'] = $latitude;

				$longitude = $row['longitude'];
				$_SESSION['data_weather_longitude'] = $longitude;

				getFromOpenWeatherMapAPI($latitude, $longitude);
				break;
			}
		}
	} elseif (isset($_POST['weather_location_point'])) {
		$latitude = $_POST['weather_latitude'];
		if (!isValidLatitude($latitude)) {
			inputError('Широта должна лежать в интервале от -90° включительно ' .
				'до 90° невключительно!', 'weather_latitude_input', 'weather.php#location_coordinates');
		}
		$_SESSION['data_weather_latitude'] = $latitude;

		$longitude = $_POST['weather_longitude'];
		if (!isValidLongitude($longitude)) {
			inputError('Долгота должна лежать в интервале от -180° невключительно ' .
				'до 180° включительно!', 'weather_longitude_input', 'weather.php#location_coordinates');
		}
		$_SESSION['data_weather_longitude'] = $longitude;

		getFromOpenWeatherMapAPI($latitude, $longitude);
	} elseif (isset($_POST['weather_location_address'])) {
		$name = $_POST['weather_location_selected_address'];
		$_SESSION['data_weather_name'] = $name;
		$data_geocoding = unserialize($_SESSION['data_geocoding'])->items;
		foreach ($data_geocoding as $item) {
			if (strcmp($item->title, $name) === 0) {
				$latitude = $item->position->lat;
				$_SESSION['data_weather_latitude'] = $latitude;

				$longitude = $item->position->lng;
				$_SESSION['data_weather_longitude'] = $longitude;

				getFromOpenWeatherMapAPI($latitude, $longitude);
				break;
			}
		}
	} else {
		throw new Exception('Внутренняя ошибка!', -1);
	}
} catch (Exception $exception) {
	$_SESSION['error'] = $exception->getCode() === -1
		? $exception->getMessage()
		: "Внутренняя ошибка №{$exception->getCode()}: \"{$exception->getMessage()}\"";
} finally {
	redirect('weather.php');
}
