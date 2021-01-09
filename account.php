<?php

require_once 'classes/Database.php';
require_once 'defines/functions.php';
require_once 'defines/templates.php';

use WeatherReport\Database;

session_start();

$fullName = loggedIn() ? "{$_SESSION['name']} {$_SESSION['surname']}" : 'Гость';

if (loggedIn()) {
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

		$query = 'SELECT `name`, `latitude`, `longitude` FROM `locations` WHERE `email` = ?';
		$result = $database->query($query, $_SESSION['email']);
		if ($result->num_rows === 0) {
			throw new Exception('Список местоположений пуст!', -1);
		}

		while ($row = $result->fetch_assoc()) {
			$data[] = $row;
		}
	} catch (Exception $exception) {
		if ($exception->getCode() === -1) {
			$error_message = $exception->getMessage();
		} else {
			$error_message = "Внутренняя ошибка №{$exception->getCode()}: \"{$exception->getMessage()}\"";
		}
	}
}

?>
<!DOCTYPE html>
<html lang='ru'>
<?php
headHTML($fullName);
?>
<body>
<?php
userHeaderHTML();

if (loggedIn()) {
?>
<main class='flex'>
	<div style='flex: 1'>
		<div class='panel padding_1 margin_1_vert'>
			<h3 class='margin_0p5_vert'>Данные пользователя</h3>
			<span>Имя пользователя: <b><?= $_SESSION['name'] ?></b></span><br>
			<span>Фамилия пользователя: <b><?= $_SESSION['surname'] ?></b></span><br>
			<span>Email пользователя: <a href='mailto:<?= $_SESSION['email'] ?>'><?= $_SESSION['email'] ?></a></span>
		</div>
		<div>
			<button class='block margin_0p5_bottom'>
				<span class='fa fa-plus margin_0p5_right'></span>Добавить местоположение</button>
			<button>
				<span class='fa fa-trash margin_0p5_right'></span>Удалить местоположение</button>
		</div>
	</div>
	<div class='padding_1' style='flex: 2'>
		<?php
		if (isset($error_message)) {
		?>
		<div class='center_parent text_center error'>
			<p><?= htmlentities($error_message) ?></p>
		</div>
		<?php
		} elseif (isset($data)) {
		?>
		<table class='full_width'>
			<caption>Сохраненные местоположения</caption>
			<tr>
				<th>Название</th>
				<th>Широта</th>
				<th>Долгота</th>
			</tr>
			<?php
			foreach ($data as $row) {
			?>
			<tr>
				<td><?= $row['name'] ?></td>
				<td><?= $row['latitude'] ?></td>
				<td><?= $row['longitude'] ?></td>
			</tr>
			<?php
			}
			?>
		</table>
		<?php
		}
		?>
	</div>
<?php
} else {
?>
<main>
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
