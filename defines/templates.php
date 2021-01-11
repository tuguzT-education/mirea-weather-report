<?php

function headHTML(string $title): void {
?>
<head>
	<meta charset='UTF-8'>
	<meta name='viewport' content='width=device-width, initial-scale=1'>
	<meta name='description' content='Прогноз погоды из любой точки земного шара'>
	<meta name='author' content='Тугушев Тимур'>
	<meta name='color-scheme' content='dark light'>
	<meta name='keywords' content='weather, погода, прогноз погоды'>
	<link rel='icon' href='/images/favicon.svg' type='image/svg+xml'>
	<link rel='mask-icon' href='/images/favicon_safari.svg'>
	<link rel='stylesheet' href='/styles/main.css' type='text/css'>
	<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
	<title><?= $title ?> | Weather Report</title>
</head>
<?php
}

function footerHTML(): void {
?>
<footer>
	<p>Автор сайта: <b>Тугушев Тимур</b><br>
		Адрес электронной почты:
		<a href='mailto:timurka.tugushev@gmail.com'>timurka.tugushev@gmail.com</a></p>
</footer>
<?php
}

function simpleHeaderHTML(): void {
?>
<header>
	<h3 class='favicon'>Weather Report</h3>
</header>
<?php
}

function userHeaderHTML(): void {
?>
<header>
	<h3 class='float_left margin_1_right favicon'>Weather Report</h3>
	<nav role='navigation' class='float_right flex'>
		<a href='/map.php' class='button padding_1p275'>
			<span class='fa fa-map margin_0p5_right'></span>Карта
		</a>
		<a href='/weather.php' class='button padding_1p275'>
			<span class='fa fa-cloud margin_0p5_right'></span>Погода
		</a>
		<?php
		if (loggedIn()) {
		?>
			<ul class='no_style menu margin_0 padding_0'>
				<li>
					<button class='button padding_1p275'>
						<span class='fa fa-user-circle margin_0p5_right'></span>
						<span><?= "{$_SESSION['name']} {$_SESSION['surname']}" ?></span>
						<span class='fa fa-angle-down margin_0p5_left'></span>
					</button>
					<ul class='no_style dropdown'>
						<li>
							<a href='/account.php' class='button padding_1p275'>
								<span class='fa fa-home margin_0p5_right'></span>Личный кабинет
							</a>
						</li>
						<li>
							<a href='/scripts/logout.php' class='button padding_1p275'>
								<span class='fa fa-sign-out margin_0p5_right'></span>Выйти
							</a>
						</li>
					</ul>
				</li>
			</ul>
		<?php
		} else {
		?>
			<a href='/account.php' class='button padding_1p275'>
				<span class='fa fa-user-circle margin_0p5_right'></span>Личный кабинет
			</a>
		<?php
		}
		?>
	</nav>
</header>
<?php
}
