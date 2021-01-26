<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/InputText.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/defines/patterns.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/defines/functions.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/defines/templates.php';

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

		$database->query('
            CREATE TABLE IF NOT EXISTS `general` (
                `name` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                `surname` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                `email` VARCHAR(320) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
                `password` VARCHAR(60) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
                `roleID` TINYINT NOT NULL DEFAULT 0,
                PRIMARY KEY (`email`)
            ) ENGINE=MyISAM;'
		);

		$query = 'SELECT name, surname, email, password, roleID FROM general WHERE email=? LIMIT 1';
		$result = $database->query($query, $email);

		$row = $result->fetch_assoc();
		if ($row === null) {
			throw new Exception(
			        'Ошибка при авторизации: пользователя с данным email не существует!', -1
            );
		}
		$email_input->setValue($email);

		if (password_verify($password, $row['password'])) {
			login($row['name'], $row['surname'], $email, $row['roleID']);
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
<html lang="ru">
<?php
headHTML('Вход');
?>
<body>
<?php
simpleHeaderHTML();
?>
<main class="center_parent">
	<div class="center_parent panel input_form padding_1p275">
		<ul class="no_style margin_0">
			<li class="text_center">
				<h2>Вход</h2>
			</li>
			<li>
				<form method="post">
					<div class="center_parent margin_1_vert">
						<?php
						$email_input->show();
						?>
					</div>
					<div class="center_parent margin_1_vert">
                        <?php
                        $password_input->show();
                        ?>
					</div>
					<?php
					if (isset($error_message)) {
					?>
					<div class="center_parent text_center error">
						<p><?= htmlentities($error_message) ?></p>
					</div>
					<?php
                    }
                    ?>
					<div class="center_parent margin_2_top">
						<button type="submit" id="login" name="login">
							<span class="fa fa-sign-in margin_0p5_right"></span>
							<span>Войти</span>
						</button>
					</div>
				</form>
			</li>
			<li class="text_center">
				<p>Нет аккаунта? <a href="/register.php">Зарегистрироваться</a></p>
				<p>Также можно <a href="/account.php">войти как гость</a></p>
			</li>
		</ul>
	</div>
</main>
</body>
</html>
