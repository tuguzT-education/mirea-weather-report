<?php

require_once 'classes/Database.php';
require_once 'defines/functions.php';
require_once 'defines/templates.php';

use WeatherReport\Database;

session_start();

$fullName = loggedIn() ? "{$_SESSION['name']} {$_SESSION['surname']}" : 'Гость';

?>
<!DOCTYPE html>
<html lang='ru'>
<?php
headHTML($fullName);
?>
<body>
<?php
userHeaderHTML();
?>
<main>
	<?php
	if (loggedIn()) {
	?>
		<!-- todo content -->
		<?php
		try {
			$database = Database::connect();
			$database->setDatabase('userdata');

			$database->query('
				CREATE TABLE IF NOT EXISTS `locations` (
					`name` VARCHAR(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
					`email` VARCHAR(320) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
					`latitude` DECIMAL(10, 8) NOT NULL,
					`longitude` DECIMAL(11, 8) NOT NULL,
					FOREIGN KEY (`email`) REFERENCES `general`(`email`)
				) ENGINE=MyISAM;'
			);

			$query = 'SELECT `name`, `latitude`, `longitude` FROM `locations` WHERE email = ?';
			$result = $database->query($query, $_SESSION['email']);
			while ($row = $result->fetch_assoc()) {
				//
			}
		} catch (Exception $exception) {
			$error_message = "Внутренняя ошибка №{$exception->getCode()}: \"{$exception->getMessage()}\"";
			?>
		<div class='center_parent text_center error'>
			<p><?= htmlentities($error_message) ?></p>
		</div>
	<?php
		}
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
<?php
footerHTML();
?>
</body>
</html>
