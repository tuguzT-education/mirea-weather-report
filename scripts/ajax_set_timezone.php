<?php

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['timezone'])) {
	$_SESSION['timezone'] = $_POST['timezone'];
}
