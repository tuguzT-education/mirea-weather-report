<?php

function isValidLatitude(float $latitude): bool {
	return -90.0 <= $latitude && $latitude < 90.0;
}

function isValidLongitude(float $longitude): bool {
	return -180.0 < $longitude && $longitude <= 180.0;
}

function redirect(string $path): void {
	if (!headers_sent()) {
		header("Location: http://{$_SERVER['HTTP_HOST']}/{$path}");
	}
	exit();
}

function login(string $name, string $surname, string $email): void {
	session_start();
	$_SESSION['name'] = $name;
	$_SESSION['surname'] = $surname;
	$_SESSION['email'] = $email;
}

function loggedIn() : bool {
	return isset($_SESSION['name'], $_SESSION['surname'], $_SESSION['email']);
}

function logout(): void {
	session_start();
	session_destroy();
	redirect('login.php');
}
