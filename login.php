<?php

require_once 'classes/Database.php';
require_once 'classes/InputText.php';
require_once 'defines/patterns.php';
require_once 'defines/functions.php';

use WeatherReport\Database;
use WeatherReport\InputText;

$email_input = new InputText(
        InputText\Type::EMAIL(), 'email',
        'Email', 'Введите ваш адрес электронной почты', 320
);
$password_input = new InputText(
        InputText\Type::PASSWORD(), 'password',
        'Пароль', 'Введите ваш пароль: он должен содержать от 8 символов, ' .
        'в нем должны присутствовать хотя бы одна латинская прописная буква, ' .
        'одна заглавная прописная буква, одно число и один специальный (небуквенный) символ',
        -1, PASSWORD_REGEX_HTML
);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
	$email = $_POST['email'];
	$email_input->setValue($email);

	$password = $_POST['password'];
	$password_input->setValue($password);

	try {
		$database = Database::connect();
		$database->setDatabase('userdata');

		$query = 'SELECT name, surname, email, password FROM general WHERE email=? LIMIT 1';
		$result = $database->query($query, $email);

		$row = $result->fetch_assoc();
		if ($row === null) {
			throw new Exception(
			        'Ошибка при авторизации: пользователя с данным email не существует!', -1
            );
		}
		$email_input->setValue($email);

		if (password_verify($password, $row['password'])) {
			login($row['name'], $row['surname'], $email);
			redirect('account.php');
		} else {
			throw new Exception(
			        'Ошибка при авторизации: пароль указан неверно!', -1
            );
		}
	} catch (Exception $exception) {
	    if ($exception->getCode() !== -1) {
			$error_message = "Внутренняя ошибка №{$exception->getCode()}: \"{$exception->getMessage()}\"";
		} else {
	        $error_message = $exception->getMessage();
        }
    }
}

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
	<title>Вход | Weather Report</title>
</head>
<body>
<header>
	<h3>Weather Report</h3>
</header>
<main class='center_parent'>
	<div class='center_parent panel input_form'>
		<ul class='no_style'>
			<li class='text_center'>
				<h3>Авторизация</h3>
			</li>
			<li>
				<form method='post'>
					<div class='center_parent'>
                        <?php
                        $email_input->show();
                        ?>
					</div>
					<div class='center_parent'>
                        <?php
                        $password_input->show();
                        ?>
					</div>
                    <?php
                    if (isset($error_message)) {
                    ?>
                        <div class='center_parent text_center error'>
                            <p><?php echo htmlentities($error_message); ?></p>
                        </div>
                    <?php
                    }
                    ?>
					<div class='center_parent'>
						<button type='submit' id='login' name='login'>Войти</button>
					</div>
				</form>
			</li>
            <li class='text_center'>
                <p>Нет аккаунта? <a href='register.php'>Зарегистрироваться</a></p>
            </li>
		</ul>
	</div>
</main>
</body>
</html>
