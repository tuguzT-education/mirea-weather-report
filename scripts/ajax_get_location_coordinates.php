<?php

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['location_name'])) {
	$name = $_POST['location_name'];
	foreach ($_SESSION['data_locations'] as $row) {
		if (strcmp($row['name'], $name) === 0) {
			echo json_encode([
				'latitude' => $row['latitude'],
				'longitude' => $row['longitude']
			]);
			exit();
		}
	}
}
