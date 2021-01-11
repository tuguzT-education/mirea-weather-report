<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/defines/functions.php';

use WeatherReport\Database;

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

try {
	$database = Database::connect();
	$database->setDatabase('userdata');
} catch (Exception $exception) {

} finally {
	redirect('account.php');
}
