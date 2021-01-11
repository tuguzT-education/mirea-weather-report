<?php

require_once 'classes/Database.php';
require_once 'defines/functions.php';

use WeatherReport\Database;

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

if (loggedIn() && !isset($_SESSION['locations'])) try {
	$database = Database::connect();
	$database->setDatabase('userdata');

	$database->query('
		CREATE TABLE IF NOT EXISTS `locations` (
			`name` VARCHAR(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
			`email` VARCHAR(320) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
			`latitude` DECIMAL(10, 8) NOT NULL,
			`longitude` DECIMAL(11, 8) NOT NULL,
			FOREIGN KEY (`email`) REFERENCES `general`(`email`)
		) ENGINE=MyISAM;'
	);

	$query = 'SELECT `name`, `latitude`, `longitude` FROM `locations` WHERE `email` = ?';
	$result = $database->query($query, $_SESSION['email']);
	if ($result->num_rows === 0) {
		throw new Exception('Список местоположений пуст!', -1);
	}

	while ($row = $result->fetch_assoc()) {
		$_SESSION['locations'][] = $row;
	}
} catch (Exception $exception) {
	$_SESSION['error'] = $exception->getCode() === -1
		? $exception->getMessage()
		: "Внутренняя ошибка №{$exception->getCode()}: \"{$exception->getMessage()}\"";
}
