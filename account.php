<?php

require_once 'classes/InputText.php';
require_once 'defines/patterns.php';
require_once 'defines/functions.php';
require_once 'defines/templates.php';

use WeatherReport\InputText;

session_start();

$fullName = loggedIn() ? "{$_SESSION['name']} {$_SESSION['surname']}" : 'Гость';

if (loggedIn()) {
	if (!isset($_SESSION['name_input'])) {
		$name_input = new InputText(
			InputText\Type::TEXT(), 'add_location_name', 'Название',
			'Введите название добавляемого местоположения', 250
		);
		$_SESSION['name_input'] = serialize($name_input);
	}
	if (!isset($_SESSION['latitude_input'])) {
		$latitude_input = new InputText(
			InputText\Type::TEXT(), 'add_location_latitude', 'Широта',
			'Введите широту добавляемого местоположения', 10,
			NUMBER_REGEX_HTML
		);
		$_SESSION['latitude_input'] = serialize($latitude_input);
	}
	if (!isset($_SESSION['longitude_input'])) {
		$longitude_input = new InputText(
			InputText\Type::TEXT(), 'add_location_longitude', 'Долгота',
			'Введите долготу добавляемого местоположения', 11,
			NUMBER_REGEX_HTML
		);
		$_SESSION['longitude_input'] = serialize($longitude_input);
	}

	if (!isset($_SESSION['error'])) {
		include 'scripts/get_locations.php';
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
<div class='dialog_background' id='add_location'>
	<div class='dialog'>
		<h3>Добавить местоположение</h3>
		<div class='tabs'>
			<input type='radio' name='tab' id='tab_1' hidden aria-hidden='true' checked>
			<input type='radio' name='tab' id='tab_2' hidden aria-hidden='true'>
			<ul hidden aria-hidden='true'>
				<li><label for='tab_1'>По координатам</label></li>
				<li><label for='tab_2'>По адресу</label></li>
			</ul>
			<div class='margin_1_bottom'>
				<section>
					<form action='/scripts/add_location.php' method='post'>
						<?php
						function showInput(string $id): void {
							$input = unserialize($_SESSION[$id]);
							$input->show();
							$input->setErrorMessage('');
							$_SESSION[$id] = serialize($input);
						}

						showInput('name_input');
						showInput('latitude_input');
						showInput('longitude_input');
						?>
						<button type='submit' class='margin_2_top' name='add_location'>
							<span class='fa fa-plus margin_0p5_right'></span>
							<span>Добавить</span>
						</button>
					</form>
				</section>
				<section>
					<h3>BRUH MOMENT</h3>
				</section>
			</div>
		</div>
		<a class='button border' href='/account.php'>
			<span class='fa fa-close margin_0p5_right'></span>
			<span>Отмена</span>
		</a>
	</div>
</div>
<div class='dialog_background' id='remove_location'>
	<div class='dialog'>
		<h3>Удалить местоположение</h3>
		<form action='/scripts/remove_location.php' method='post'>
			<label class='center_parent'>
				<select name='remove_location_name'>
				<?php
				if (isset($_SESSION['locations'])) {
					foreach ($_SESSION['locations'] as $row) {
						?><option><?= $row['name'] ?></option><?php
					}
				}
				?>
				</select>
			</label>
			<button type='submit' class='margin_0p5_bottom' name='remove_location'
				<?php if (empty($_SESSION['locations'])) echo ' disabled'; ?>>
				<span class='fa fa-trash margin_0p5_right'></span>
				<span>Удалить</span>
			</button>
		</form>
		<a class='button border' href='/account.php'>
			<span class='fa fa-close margin_0p5_right'></span>
			<span>Отмена</span>
		</a>
	</div>
</div>
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
			<?php
			if (!empty($_SESSION['locations'])) {
			?>
			<a class='button border margin_0p5_bottom' href='#remove_location'>
				<span class='fa fa-trash margin_0p5_right'></span>
				<span>Удалить местоположение</span>
			</a>
			<?php
			}
			?>
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
				<a href='/login.php'>Войти</a> либо <a href='/register.php'>зарегистрироваться</a>
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
