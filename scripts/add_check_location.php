<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/defines/functions.php';

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

try {
	if ($_SERVER['REQUEST_METHOD'] !== 'POST' && !isset($_POST['check_location'])) {
		throw new Exception('Внутренняя ошибка!');
	}

	$address = trim($_POST['check_location_address']);
	if (empty($address)) {
		$_SESSION['add_location_tab'] = 2;
		inputError('Адрес не может быть пустым!',
			'check_address_input', 'account.php#add_location');
	}
	$data_geocoding = getFromHereAPI($address);
	if (empty($data_geocoding->items)) {
		$_SESSION['add_location_tab'] = 2;
		inputError('Нет вариантов по данному адресу!',
			'check_address_input', 'account.php#add_location');
	}
} catch (Exception $exception) {
	$_SESSION['error'] = $exception->getMessage();
} finally {
	redirect('account.php#add_checked_location');
}
