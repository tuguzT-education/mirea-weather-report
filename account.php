<?php

require_once 'defines/functions.php';
require_once 'defines/templates.php';

session_start();

$fullName = loggedIn() ? "{$_SESSION['name']} {$_SESSION['surname']}" : 'Гость';

include 'get_locations.php';

?>
<!DOCTYPE html>
<html lang='ru'>
<?php
headHTML($fullName);
?>
<body>
<div class='dialog_background' id='add_location'>
	<div class='dialog'>
		<h3>Добавить местоположение</h3>
		<form action='add_location.php' method='post'>
			<!-- todo -->
			<button type='submit' class='margin_0p5_bottom'>
				<span class='fa fa-plus margin_0p5_right'></span>
				<span>Добавить</span>
			</button>
		</form>
		<a class='button border' href='account.php'>
			<span class='fa fa-close margin_0p5_right'></span>
			<span>Отмена</span>
		</a>
	</div>
</div>
<div class='dialog_background' id='remove_location'>
	<div class='dialog'>
		<h3>Удалить местоположение</h3>
		<form action='remove_location.php' method='post'>
			<!-- todo -->
			<button type='submit' class='margin_0p5_bottom'>
				<span class='fa fa-trash margin_0p5_right'></span>
				<span>Удалить</span>
			</button>
		</form>
		<a class='button border' href='account.php'>
			<span class='fa fa-close margin_0p5_right'></span>
			<span>Отмена</span>
		</a>
	</div>
</div>
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
			<a class='button border margin_0p5_bottom' href='#add_location'>
				<span class='fa fa-plus margin_0p5_right'></span>
				<span>Добавить местоположение</span>
			</a>
			<a class='button border margin_0p5_bottom' href='#remove_location'>
				<span class='fa fa-trash margin_0p5_right'></span>
				<span>Удалить местоположение</span>
			</a>
		</div>
	</div>
	<div class='padding_1' style='flex: 2'>
		<?php
		if (isset($_SESSION['error'])) {
		?>
		<div class='center_parent text_center error'>
			<p><?= htmlentities($_SESSION['error']); unset($_SESSION['error']); ?></p>
		</div>
		<?php
		} elseif (isset($_SESSION['locations'])) {
		?>
		<table class='full_width'>
			<caption>Сохраненные местоположения</caption>
			<tr>
				<th>Название</th>
				<th>Широта</th>
				<th>Долгота</th>
			</tr>
			<?php
			foreach ($_SESSION['locations'] as $row) {
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
</main>
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
</main>
<?php
}
footerHTML();
?>
</body>
</html>
