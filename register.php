<?php

require_once 'classes/Database.php';
require_once 'classes/InputText.php';
require_once 'defines/patterns.php';
require_once 'defines/functions.php';
require_once 'defines/templates.php';

use WeatherReport\Database;
use WeatherReport\InputText;

$name_input = new InputText(
        InputText\Type::TEXT(), 'name',
        'Имя', 'Введите имя, которое не должно содержать цифр',
        100, NO_DIGIT_REGEX_HTML
);
$surname_input = new InputText(
        InputText\Type::TEXT(), 'surname',
        'Фамилия', 'Введите фамилию, которая не должна содержать цифр',
        100, NO_DIGIT_REGEX_HTML
);
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
$confirm_password_input = new InputText(
        InputText\Type::PASSWORD(), 'confirm_password',
        'Подтвердите пароль', 'Введите тот же пароль, что и в поле ввода пароля',
        -1, PASSWORD_REGEX_HTML
);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $error = false;

    $name = trim($_POST['name']);
    if ($name === '') {
        $error = true;
        $name_input->setErrorMessage('Имя не должно быть пустым!');
    } else {
        $name_input->setValue($name);
    }

	$surname = trim($_POST['surname']);
	if ($surname === '') {
		$error = true;
		$surname_input->setErrorMessage('Фамилия не должна быть пустой!');
	} else {
		$surname_input->setValue($surname);
	}

	$email = $_POST['email'];
	$email_input->setValue($email);

	$password = $_POST['password'];
	$password_input->setValue($password);

	$confirm_password = $_POST['confirm_password'];
	if (strcmp($password, $confirm_password) !== 0) {
		$error = true;
	    $confirm_password_input->setErrorMessage('Пароли не совпадают друг с другом!');
    } else {
		$confirm_password_input->setValue($confirm_password);
	}

	if (!$error) try {
		$database = Database::connect();
		$database->setDatabase('userdata');

		$database->query('
            CREATE TABLE IF NOT EXISTS `general` (
                `name` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                `surname` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                `email` VARCHAR(320) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
                `password` VARCHAR(96) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
                PRIMARY KEY (`email`)
            ) ENGINE=MyISAM;'
		);

		$password = password_hash($password, PASSWORD_ARGON2I);
		$query = 'INSERT INTO `general` VALUES(?, ?, ?, ?);';
		$database->query($query, $name, $surname, $email, $password);

		login($name, $surname, $email);
		redirect('account.php');
	} catch (Exception $exception) {
		$error_message = "Внутренняя ошибка №{$exception->getCode()}: \"{$exception->getMessage()}\"";
	    if ($exception->getCode() === 1062) {
	        $error_message = 'Пользователь с данным email уже существует!';
        }
	}
}

?>
<!DOCTYPE html>
<html lang='ru'>
<?php
headHTML('Регистрация');
?>
<body>
<?php
simpleHeaderHTML();
?>
<main class='center_parent'>
	<div class='center_parent panel input_form'>
		<ul class='no_style'>
			<li class='text_center'>
				<h2>Регистрация</h2>
			</li>
			<li>
				<form method='post'>
					<div class='center_parent'>
						<?php
						$name_input->show();
						?>
					</div>
					<div class='center_parent'>
						<?php
						$surname_input->show();
						?>
					</div>
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
					<div class='center_parent'>
						<?php
						$confirm_password_input->show();
						?>
					</div>
					<?php
					if (isset($error_message)) {
						?>
					<div class='center_parent text_center error'>
						<p><?= htmlentities($error_message) ?></p>
					</div>
					<?php
					}
					?>
					<div class='center_parent'>
						<button type='submit' id='register' name='register'>
							<span class='fa fa-user-plus margin_0p5_right'></span>Зарегистрироваться
						</button>
					</div>
				</form>
			</li>
			<li class='text_center'>
				<p>Уже есть аккаунт? <a href='/login.php'>Войти</a></p>
			</li>
		</ul>
	</div>
</main>
</body>
</html>
