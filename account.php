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
	<link href='styles/main.css' rel='stylesheet' type='text/css'>
	<title><?php echo $fullName; ?> | Weather Report</title>
</head>
<body>
<header>
	<h3 class='float_left margin_1_right'>Weather Report</h3>
    <a href='weather.php' class='button float_left margin_05_vert'>Карта/Погода</a>
    <?php
    if (loggedIn()) {
    ?>
    <nav role='navigation' class='float_right'>
        <ul class='no_style menu margin_05_vert'>
            <li><a href='#' class='button'><?php echo $fullName; ?></a>
                <ul class='no_style dropdown'>
                    <li><a href='account.php'>Личный кабинет</a></li>
                    <li><a href='logout.php'>Выйти</a></li>
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
	<h4 class='center_parent text_center error'>
		Вы вошли как гость, поэтому функции обычного пользователя вам недоступны!
	</h4>
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
