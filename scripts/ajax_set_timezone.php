<?php

session_start();

if (isset($_POST['timezone'])) {
	$_SESSION['timezone'] = $_POST['timezone'];
}
