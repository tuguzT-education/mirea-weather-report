<?php

require_once 'defines/functions.php';

session_start();

?>
<!DOCTYPE html>
<html lang='ru'>
<head>
	<meta charset='UTF-8'>
	<meta name='viewport' content='width=device-width, initial-scale=1'>
	<meta name='description' content='Прогноз погоды из любой точки земного шара'>
	<meta name='author' content='Тугушев Тимур'>
	<meta name='color-scheme' content='dark light'>
	<meta name='keywords' content='weather, погода, прогноз погоды'>
	<link href='styles/main.css' rel='stylesheet' type='text/css'>
	<title><?php echo loggedIn() ? "{$_SESSION['name']} {$_SESSION['surname']}" : 'Гость'; ?> | Weather Report</title>
</head>
<body>
<header>
	<h2>Weather Report</h2>
</header>
<main>
	<?php
	if (loggedIn()) {
	?>
	<!-- todo add content -->
	<?php
	} else {
	?>
	<h4 class='center_parent text_center error'>
		Вы вошли как гость, поэтому функции обычного пользователя вам недоступны!
	</h4>
	<?php
	}
	?>
</main>
<footer>
	<p>Автор сайта: <strong>Тугушев Тимур</strong><br>
		Адрес электронной почты:
		<strong><a href='mailto:timurka.tugushev@gmail.com'>timurka.tugushev@gmail.com</a></strong></p>
</footer>
</body>
</html>
