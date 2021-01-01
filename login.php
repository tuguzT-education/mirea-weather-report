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
	<title>Вход | Weather Report</title>
</head>
<body>
<header>
	<h2>Weather Report</h2>
</header>
<main class='center_parent'>
	<div class='center_parent'>
		<ul class='no_style'>
			<li class='text_center'>
				<h3>Авторизация</h3>
			</li>
			<li>
				<form method='post'>
					<div class='center_parent'>
						<div class='input'>
							<input id='email' type='email' required placeholder=' '>
							<label for='email'>Email</label>
						</div>
					</div>
					<div class='center_parent'>
						<div class='input'>
							<input id='password' type='password' required placeholder=' '>
							<label for='password'>Пароль</label>
						</div>
					</div>
					<div class='center_parent'>
						<button type='submit'>Войти</button>
					</div>
				</form>
			</li>
		</ul>
	</div>
</main>
</body>
</html>
