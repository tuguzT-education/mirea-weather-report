<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/InputText.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/defines/apis.php';

use WeatherReport\Database;

function curlExecute(string $url) {
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_URL, $url);
	$result = curl_exec($curl);

	$error = curl_error($curl);
	curl_close($curl);
	if ($result === false) {
		throw new Exception("Внутренняя ошибка: \"{$error}\"");
	}
	return $result;
}

function getFromOpenWeatherMapAPI(string $latitude, string $longitude) {
	$url = "https://api.openweathermap.org/data/2.5/onecall?" .
		"exclude=alerts,minutely&lat={$latitude}&lon={$longitude}&lang=ru&" .
		"units=metric&appid=" . OPEN_WEATHER_MAP_API_KEY;
	$result = curlExecute($url);
	$data_weather = json_decode($result);
	$_SESSION['data_weather'] = serialize($data_weather);
	return $data_weather;
}

function getFromHereAPI(string $address) {
	$url = 'https://geocode.search.hereapi.com/v1/geocode?q=' .
		urlencode($address) . '&lang=ru-RU&apiKey=' . HERE_REST_API_KEY;
	$result = curlExecute($url);
	$data_geocoding = json_decode($result);
	$_SESSION['data_geocoding'] = serialize($data_geocoding);
	return $data_geocoding;
}

function isValidLatitude(float $latitude): bool {
	return -90.0 <= $latitude && $latitude < 90.0;
}

function isValidLongitude(float $longitude): bool {
	return -180.0 < $longitude && $longitude <= 180.0;
}

function degreeToDirection(float $degrees): string {
	$directions = array(
		'С', 'ССЗ', 'СЗ', 'ЗСЗ', 'З', 'ЗЮЗ', 'ЮЗ', 'ЮЮЗ',
		'Ю', 'ЮЮВ', 'ЮВ', 'ВЮВ', 'В', 'ВСВ', 'СВ', 'ССВ', 'С'
	);
	return $directions[round($degrees / 22.5)];
}

function redirect(string $path): void {
	if (!headers_sent()) {
		header("Location: http://{$_SERVER['HTTP_HOST']}/{$path}");
	}
	exit();
}

function login(string $name, string $surname, string $email, int $roleID): void {
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	$_SESSION = [
		'name' => $name,
		'surname' => $surname,
		'email' => $email,
		'roleID' => $roleID,
	];
}

function update(): void {
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	try {
		$database = Database::connect();
		$database->setDatabase('userdata');

		$query = 'SELECT `name`, `surname`, `email`, `roleID` FROM `general` WHERE `email` = ?';
		$result = $database->query($query, $_SESSION['email'])->fetch_assoc();

		$_SESSION = [
			'name' => $result['name'],
			'surname' => $result['surname'],
			'email' => $result['email'],
			'roleID' => $result['roleID'],
		];
	} catch (Exception $exception) {
		logout();
	}
}

function isAdmin(): bool {
	return isset($_SESSION['roleID']) && $_SESSION['roleID'] !== 0;
}

function loggedIn() : bool {
	return isset($_SESSION['name'], $_SESSION['surname'], $_SESSION['email']);
}

function logout(): void {
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	session_destroy();
	redirect('login.php');
}

function inputError(string $errorMessage, string $id, string $redirectPath) {
	$input = unserialize($_SESSION[$id]);
	$input->setErrorMessage($errorMessage);
	$_SESSION[$id] = serialize($input);
	redirect($redirectPath);
}

function showInput(string $id): void {
	$input = unserialize($_SESSION[$id]);
	$input->show();
	$input->setErrorMessage('');
	$_SESSION[$id] = serialize($input);
}
