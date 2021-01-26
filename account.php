<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/InputText.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/TextArea.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/defines/patterns.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/defines/functions.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/defines/templates.php';

use WeatherReport\InputText;
use WeatherReport\TextArea;

session_start();

$fullName = loggedIn() ? "{$_SESSION['name']} {$_SESSION['surname']}" : 'Гость';

if (loggedIn()) {
	update();
	if (!isset($_SESSION['error'])) {
		require 'scripts/get_locations.php';
	}
}

?>
<!DOCTYPE html>
<html lang="ru">
<?php
headHTML($fullName);
?>
<body>
<?php
userHeaderHTML();

if (loggedIn()) {
	if (!isset($_SESSION['name_input'])) {
		$input = new InputText(
			InputText\Type::TEXT(), 'add_location_name', 'Название',
			'Введите название добавляемого местоположения', 250
		);
		$_SESSION['name_input'] = serialize($input);
	}
	if (!isset($_SESSION['latitude_input'])) {
		$input = new InputText(
			InputText\Type::TEXT(), 'add_location_latitude', 'Широта',
			'Введите широту добавляемого местоположения', 10,
			NUMBER_REGEX_HTML
		);
		$_SESSION['latitude_input'] = serialize($input);
	}
	if (!isset($_SESSION['longitude_input'])) {
		$input = new InputText(
			InputText\Type::TEXT(), 'add_location_longitude', 'Долгота',
			'Введите долготу добавляемого местоположения', 11,
			NUMBER_REGEX_HTML
		);
		$_SESSION['longitude_input'] = serialize($input);
	}

	if (!isset($_SESSION['check_address_input'])) {
		$input = new InputText(
			InputText\Type::TEXT(), 'check_location_address', 'Адрес',
			'Введите адрес местоположение', -1
		);
		$_SESSION['check_address_input'] = serialize($input);
	}
?>
<div class="dialog_background" id="add_location">
	<div class="dialog">
		<h3>Добавить местоположение</h3>
		<div class="tabs">
			<?php
			$add_location_tab_id = 1;
			if (isset($_SESSION['add_location_tab'])) {
				$add_location_tab_id = $_SESSION['add_location_tab'];
				$_SESSION['add_location_tab'] = 1;
			}
			?>
			<input type="radio" name="add_location_tab" id="add_location_tab_1"
				   hidden aria-hidden="true" <?php if ($add_location_tab_id === 1) echo 'checked'; ?>>
			<input type="radio" name="add_location_tab" id="add_location_tab_2"
				   hidden aria-hidden="true" <?php if ($add_location_tab_id === 2) echo 'checked'; ?>>
			<ul hidden aria-hidden="true">
				<li><label for="add_location_tab_1">По координатам</label></li>
				<li><label for="add_location_tab_2">По адресу</label></li>
			</ul>
			<div class="margin_1_bottom padding_1p275">
				<section>
					<form action="/scripts/add_location.php" method="post">
						<div class="center_parent">
							<?php
							showInput('name_input');
							?>
						</div>
						<div class="center_parent">
							<?php
							showInput('latitude_input');
							?>
						</div>
						<div class="center_parent">
							<?php
							showInput('longitude_input');
							?>
						</div>
						<button type="submit" class="margin_2_top" name="add_location_point">
							<span class="fa fa-plus margin_0p5_right"></span>
							<span>Добавить</span>
						</button>
					</form>
				</section>
				<section>
					<form action="/scripts/add_check_location.php" method="post">
						<div class="center_parent">
							<?php
							showInput('check_address_input');
							?>
						</div>
						<button type="submit" class="margin_2_top" name="check_location">
							<span class="fa fa-check margin_0p5_right"></span>
							<span>Проверить адрес</span>
						</button>
					</form>
				</section>
			</div>
		</div>
		<a class="button border" href="/account.php">
			<span class="fa fa-close margin_0p5_right"></span>
			<span>Отмена</span>
		</a>
	</div>
</div>
<div class="dialog_background" id="remove_location">
	<div class="dialog">
		<h3>Удалить местоположение</h3>
		<form action="/scripts/remove_location.php" method="post">
			<label class="center_parent padding_1">
				<select name="remove_location_name">
				<?php
				if (isset($_SESSION['data_locations'])) {
					foreach ($_SESSION['data_locations'] as $row) {
						?><option><?= $row['name'] ?></option><?php
					}
				}
				?>
				</select>
			</label>
			<button type="submit" name="remove_location"
				<?php if (empty($_SESSION['data_locations'])) echo ' disabled'; ?>>
				<span class="fa fa-trash margin_0p5_right"></span>
				<span>Удалить</span>
			</button>
		</form>
		<a class="button border margin_1_top" href="/account.php">
			<span class="fa fa-close margin_0p5_right"></span>
			<span>Отмена</span>
		</a>
	</div>
</div>
<div class="dialog_background" id="add_checked_location">
	<div class="dialog">
		<h3>Выберите местоположение</h3>
		<form action="/scripts/add_location.php" method="post">
			<?php
			if (isset($_SESSION['data_geocoding'])) {
				$data_geocoding = unserialize($_SESSION['data_geocoding'])->items;
				if (!empty($data_geocoding)) {
					?>
			<div class="center_parent padding_1">
				<label>
					<select name="add_location_selected_address">
						<?php
						foreach ($data_geocoding as $item) {
							?><option><?= $item->title ?></option><?php
						}
						?>
					</select>
				</label>
			</div>
			<?php
				}
			}
			?>
			<button type="submit" class="margin_1_top" name="add_location_address">
				<span class="fa fa-plus margin_0p5_right"></span>
				<span>Добавить</span>
			</button>
		</form>
		<a class="button border margin_1_top" href="/account.php">
			<span class="fa fa-close margin_0p5_right"></span>
			<span>Отмена</span>
		</a>
	</div>
</div>
<?php
if (loggedIn() && !isAdmin()) {
?>
<div class="dialog_background" id="admin_request">
	<div class="dialog">
		<h3>Отправить запрос на администрирование</h3>
		<form action="/scripts/add_admin_request.php" method="post">
			<div class="margin_2_bottom">
				<?php
				$textArea = new TextArea('admin_request_text',
					'Введите сообщение',
					'Введите сообщение длиной не более 2000 символов', 2000);
				$textArea->show();
				?>
			</div>
			<button type="submit" name="admin_request">
				<span class="fa fa-envelope margin_0p5_right"></span>
				<span>Отправить</span>
			</button>
		</form>
		<a class="button border margin_1_top" href="/account.php">
			<span class="fa fa-close margin_0p5_right"></span>
			<span>Отмена</span>
		</a>
	</div>
</div>
<?php
}
?>
<main class="flex">
	<div style="flex: 1">
		<div class="panel padding_1p275 margin_1_vert">
			<h3 class="margin_0p5_vert">Данные пользователя</h3>
			<span>Имя пользователя: <b><?= $_SESSION['name'] ?></b></span><br>
			<span>Фамилия пользователя: <b><?= $_SESSION['surname'] ?></b></span><br>
			<span>Email пользователя: <a href="mailto:<?= $_SESSION['email'] ?>"><?= $_SESSION['email'] ?></a></span><br>
			<?php
			if (isAdmin()) {
			?>
			<p><b>Вы являетесь администратором!</b></p>
			<?php
			}
			?>
			<a class="button border block margin_2_top" href="#add_location">
				<span class="fa fa-plus margin_0p5_right"></span>
				<span>Добавить местоположение</span>
			</a>
			<?php
			if (!empty($_SESSION['data_locations'])) {
			?>
			<a class="button border block margin_1_top" href="#remove_location">
				<span class="fa fa-trash margin_0p5_right"></span>
				<span>Удалить местоположение</span>
			</a>
			<?php
			}
			if (loggedIn() && !isAdmin()) {
			?>
			<a class="button border margin_2_top" href="#admin_request">
				<span class="fa fa-envelope margin_0p5_right"></span>
				<span>Запрос на администрирование</span>
			</a>
			<?php
			}
			?>
		</div>
	</div>
	<div class="padding_1" style="flex: 2">
		<?php
		if (isset($_SESSION['error'])) {
		?>
		<div class="center_parent text_center error">
			<p><?= htmlentities($_SESSION['error']); unset($_SESSION['error']); ?></p>
		</div>
		<?php
		} elseif (isset($_SESSION['data_locations'])) {
		?>
		<table class="full_width">
			<caption>Сохраненные местоположения</caption>
			<tr>
				<th>Название</th>
				<th>Широта</th>
				<th>Долгота</th>
			</tr>
			<?php
			foreach ($_SESSION['data_locations'] as $row) {
			?>
			<tr>
				<td><?= $row['name'] ?></td>
				<td><?= (float) $row['latitude'] ?></td>
				<td><?= (float) $row['longitude'] ?></td>
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
	<div class="center_parent padding_1p275 text_center">
		<p class="error">Вы вошли как <b>гость</b>, поэтому функции обычного пользователя вам недоступны!<br>
			<a href="/login.php">Войти</a> либо <a href="/register.php">зарегистрироваться</a>
		</p>
	</div>
</main>
<?php
}

footerHTML();
?>
</body>
</html>
