<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/defines/functions.php';

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

try {
	if ($_SERVER['REQUEST_METHOD'] !== 'POST' && !isset($_POST['weather_check_location'])) {
		throw new Exception('Внутренняя ошибка!');
	}

	$address = trim($_POST['weather_check_location_address']);
	if (empty($address)) {
		inputError('Адрес не может быть пустым!',
			'weather_check_address_input', 'weather.php#location_address');
	}
	$data_geocoding = getFromHereAPI($address);
	if (empty($data_geocoding->items)) {
		inputError('Нет вариантов по данному адресу!',
			'weather_check_address_input', 'weather.php#location_address');
	}
} catch (Exception $exception) {
	$_SESSION['error'] = $exception->getMessage();
} finally {
	redirect('weather.php#checked_location');
}
