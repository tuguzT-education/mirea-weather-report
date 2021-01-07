<?php

require_once 'defines/functions.php';

session_start();

$fullName = loggedIn() ? "{$_SESSION['name']} {$_SESSION['surname']}" : 'Гость';

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
	<link rel='stylesheet' href='styles/main.css' type='text/css'>
	<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
	<title><?= $fullName ?> | Weather Report</title>
</head>
<body>
<header>
	<h3 class='float_left margin_1_right'>Weather Report</h3>
	<a href='weather.php' class='button float_left padding_1p275'>
		<span class='fa fa-map margin_0p5_right'></span>Карта/Погода
	</a>
	<?php
	if (loggedIn()) {
	?>
	<nav role='navigation' class='float_right'>
		<ul class='no_style menu margin_0 padding_0'>
			<li>
				<a href='#' class='button padding_1p275'>
					<span class='fa fa-user-circle margin_0p5_right'></span>
					<?= $fullName ?>
					<span class='fa fa-angle-down margin_0p5_left'></span>
				</a>
				<ul class='no_style dropdown'>
					<li>
						<a href='account.php' class='padding_1p275'>
							<span class='fa fa-home margin_0p5_right'></span>Личный кабинет
						</a>
					</li>
					<li>
						<a href='logout.php' class='padding_1p275'>
							<span class='fa fa-sign-out margin_0p5_right'></span>Выйти
						</a>
					</li>
				</ul>
			</li>
		</ul>
	</nav>
	<?php
	}
	?>
</header>
<main>
	<?php
	if (loggedIn()) {
	?>
	<!-- todo add content -->
	<?php
	} else {
	?>
	<div class='center_parent text_center'>
		<p class='error'>Вы вошли как <b>гость</b>, поэтому функции обычного пользователя вам недоступны!<br>
			<span class='good'>
				<a href='login.php'>Войти</a> либо <a href='register.php'>зарегистрироваться</a>
			</span>
		</p>
	</div>
	<?php
	}
	?>
</main>
<footer>
	<p>Автор сайта: <b>Тугушев Тимур</b><br>
		Адрес электронной почты:
		<b><a href='mailto:timurka.tugushev@gmail.com'>timurka.tugushev@gmail.com</a></b></p>
</footer>
</body>
</html>
