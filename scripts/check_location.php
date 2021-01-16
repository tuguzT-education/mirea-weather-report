<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/InputText.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/defines/functions.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/defines/apis.php';

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

try {
	if ($_SERVER['REQUEST_METHOD'] !== 'POST' && !isset($_POST['check_location'])) {
		throw new Exception('Внутренняя ошибка!');
	}

	$address = trim($_POST['check_location_address']);
	if (empty($address)) {
		inputError('Адрес не может быть пустым!', 'address_input');
	}
	$url = 'https://geocode.search.hereapi.com/v1/geocode?q=' .
		urlencode($address) . '&apiKey=' . HERE_REST_API_KEY;

	$curl = curl_init();
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_URL, $url);
	$result = curl_exec($curl);

	$error = curl_error($curl);
	curl_close($curl);
	if ($result === false) {
		throw new Exception("Внутренняя ошибка: \"{$error}\"");
	}
	$data_geocoding = json_decode($result);
	$_SESSION['data_geocoding'] = serialize($data_geocoding);
	if (empty($data_geocoding->items)) {
		inputError('Нет вариантов по данному адресу!', 'address_input');
	}
} catch (Exception $exception) {
	$_SESSION['error'] = $exception->getMessage();
} finally {
	redirect('account.php#add_checked_location');
}
