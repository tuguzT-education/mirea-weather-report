<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/InputText.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/defines/functions.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/defines/templates.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/defines/patterns.php';

use WeatherReport\InputText;

session_start();

?>
<!DOCTYPE html>
<html lang="ru">
<?php
headHTML('Погода');
?>
<body>
<?php
userHeaderHTML();

if (loggedIn()) {
?>
<div class="dialog_background" id="locations_list">
	<div class="dialog">
		<div class="center_parent text_center">
			<h3>Выберите местоположение из списка</h3>
		</div>
		<?php
		if (isset($_SESSION['data_locations']) && !empty($_SESSION['data_locations'])) {
		?>
		<form action="/scripts/get_weather_data.php" method="post">
			<label class="center_parent padding_1">
				<select name="weather_choose_location_list_name">
					<?php
					foreach ($_SESSION['data_locations'] as $row) {
						?><option><?= $row['name'] ?></option><?php
					}
					?>
				</select>
			</label>
			<button type="submit" name="weather_choose_location_list">
				<span class="fa fa-check margin_0p5_right"></span>
				<span>Выбрать</span>
			</button>
		</form>
		<?php
		} else {
		?>
		<div class="center_parent text_center">
			<p class="error">Список местоположений пуст!</p>
		</div>
		<?php
		}
		?>
		<a class="button border margin_1_top" href="/weather.php">
			<span class="fa fa-close margin_0p5_right"></span>
			<span>Отмена</span>
		</a>
	</div>
</div>
<?php
}
?>
<div class="dialog_background" id="location_coordinates">
	<div class="dialog">
		<div class="center_parent text_center">
			<h3>Задайте ширину и долготу местоположения</h3>
		</div>
		<?php
		if (!isset($_SESSION['weather_latitude_input'])) {
			$input = new InputText(
				InputText\Type::TEXT(), 'weather_latitude', 'Широта',
				'Введите широту добавляемого местоположения', 10,
				NUMBER_REGEX_HTML
			);
			$_SESSION['weather_latitude_input'] = serialize($input);
		}
		if (!isset($_SESSION['weather_longitude_input'])) {
			$input = new InputText(
				InputText\Type::TEXT(), 'weather_longitude', 'Долгота',
				'Введите долготу добавляемого местоположения', 11,
				NUMBER_REGEX_HTML
			);
			$_SESSION['weather_longitude_input'] = serialize($input);
		}
		?>
		<form action="/scripts/get_weather_data.php" method="post">
			<div class="center_parent">
				<?php
				showInput('weather_latitude_input');
				?>
			</div>
			<div class="center_parent">
				<?php
				showInput('weather_longitude_input');
				?>
			</div>
			<button type="submit" class="margin_2_top" name="weather_location_point">
				<span class="fa fa-check margin_0p5_right"></span>
				<span>Выбрать</span>
			</button>
		</form>
		<a class="button border margin_1_top" href="/weather.php">
			<span class="fa fa-close margin_0p5_right"></span>
			<span>Отмена</span>
		</a>
	</div>
</div>
<div class="dialog_background" id="location_address">
	<div class="dialog">
		<div class="center_parent text_center">
			<h3>Введите адрес местоположения</h3>
		</div>
		<?php
		if (!isset($_SESSION['weather_check_address_input'])) {
			$input = new InputText(
				InputText\Type::TEXT(), 'weather_check_location_address', 'Адрес',
				'Введите адрес местоположение', -1
			);
			$_SESSION['weather_check_address_input'] = serialize($input);
		}
		?>
		<form action="/scripts/weather_check_location.php" method="post">
			<?php
			showInput('weather_check_address_input');
			?>
			<button type="submit" class="margin_2_top" name="weather_check_location">
				<span class="fa fa-check margin_0p5_right"></span>
				<span>Проверить адрес</span>
			</button>
		</form>
		<a class="button border margin_1_top" href="/weather.php">
			<span class="fa fa-close margin_0p5_right"></span>
			<span>Отмена</span>
		</a>
	</div>
</div>
<div class="dialog_background" id="checked_location">
	<div class="dialog">
		<div class="center_parent text_center">
			<h3>Выберите местоположение</h3>
		</div>
		<form action="/scripts/get_weather_data.php" method="post">
			<?php
			if (isset($_SESSION['data_geocoding'])) {
				$data_geocoding = unserialize($_SESSION['data_geocoding'])->items;
				if (!empty($data_geocoding)) {
					?>
					<div class="center_parent padding_1">
						<label>
							<select name="weather_location_selected_address">
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
			<button type="submit" class="margin_1_top" name="weather_location_address">
				<span class="fa fa-check margin_0p5_right"></span>
				<span>Выбрать</span>
			</button>
		</form>
		<a class="button border margin_1_top" href="/weather.php">
			<span class="fa fa-close margin_0p5_right"></span>
			<span>Отмена</span>
		</a>
	</div>
</div>
<main>
	<div class="panel padding_1p275 margin_2_vert center_vertically_parent">
		<h3>Получить прогноз погоды</h3>
		<div class="flex">
			<?php
			if (loggedIn()) {
			?>
			<a class="button border block margin_1_right" href="#locations_list">
				<span class="fa fa-list margin_0p5_right"></span>
				<span>Из списка местоположений</span>
			</a>
			<?php
			}
			?>
			<a class="button border block margin_1_right" href="#location_coordinates">
				<span class="fa fa-globe margin_0p5_right"></span>
				<span>По координатам</span>
			</a>
			<a class="button border block" href="#location_address">
				<span class="fa fa-map-marker margin_0p5_right"></span>
				<span>По адресу</span>
			</a>
		</div>
	</div>
	<div>
		<?php
		if (isset($_SESSION['error'])) {
		?>
		<div class="center_parent text_center error">
			<p><?= htmlentities($_SESSION['error']); unset($_SESSION['error']); ?></p>
		</div>
		<?php
		} elseif (isset($_SESSION['data_weather'])) {
			$data = unserialize($_SESSION['data_weather']);
		?>
		<div class="panel margin_1_bottom padding_1p275">
			<h3 class="margin_0p5_vert">Данные о выбранном местоположении</h3>
			<?php
			if (isset($_SESSION['data_weather_name'])) {
				?><p>Название: <b><?= $_SESSION['data_weather_name'] ?></b></p><?php
			}
			?>
			<p>Широта: <b><?= (float) $_SESSION['data_weather_latitude'] ?></b></p>
			<p>Долгота: <b><?= (float) $_SESSION['data_weather_longitude'] ?></b></p>
			<?php
			if (isset($data->cod) && $data->cod !== 200) {
				?><span class="error"><?= $data->message ?></span><?php
			} else {
				// Set user's timezone name
				$timezone = 'UTC';
				if (isset($_SESSION['timezone'])) try {
					$dt = new DateTime('now', new DateTimeZone($_SESSION['timezone']));
					$timezone = 'GMT' . $dt->format('P');
				} catch (Exception $exception) {}

				function getTimeFromTimezone($dt): string {
					$result = gmdate('d-m-Y H:i', $dt);
					if (isset($_SESSION['timezone'])) try {
						$dt = new DateTime('@' . $dt);
						$dt->setTimezone(new DateTimeZone($_SESSION['timezone']));
						$result = $dt->format('d-m-Y H:i');
					} catch (Exception $exception) {}
					return $result;
				}
			?>
			<div class="tabs">
				<input type="radio" name="add_location_tab" id="add_location_tab_1"
					   hidden aria-hidden="true" checked>
				<input type="radio" name="add_location_tab" id="add_location_tab_2"
					   hidden aria-hidden="true">
				<input type="radio" name="add_location_tab" id="add_location_tab_3"
					   hidden aria-hidden="true">
				<ul hidden aria-hidden="true">
					<li><label class="full_height" for="add_location_tab_1">Сейчас</label></li>
					<li><label class="full_height" for="add_location_tab_2">Каждый час в течение 2-х дней</label></li>
					<li><label class="full_height" for="add_location_tab_3">Каждый день в течение недели</label></li>
				</ul>
				<div class="horizontal_scroll">
					<section>
						<table class="full_width">
							<tr>
								<th>Время, <?= $timezone ?></th>
								<th>Температура, °C</th>
								<th>Давление, мм. рт. ст.</th>
								<th>Влажность, %</th>
								<th>Облачность, %</th>
								<th>Скорость ветра, м/с</th>
								<th>Направление ветра</th>
								<th>Описание</th>
							</tr>
							<?php
							$data->current->pressure = round($data->current->pressure * 0.75006375541921);
							?>
							<tr>
								<td><?= getTimeFromTimezone($data->current->dt) ?></td>
								<td><?= $data->current->temp ?></td>
								<td><?= $data->current->pressure ?></td>
								<td><?= $data->current->humidity ?></td>
								<td><?= $data->current->clouds ?></td>
								<td><?= $data->current->wind_speed ?></td>
								<td><?= degreeToDirection($data->current->wind_deg) ?></td>
								<td><?= $data->current->weather[0]->description ?></td>
							</tr>
						</table>
					</section>
					<section>
						<table class="full_width">
							<tr>
								<th>Время, <?= $timezone ?></th>
								<th>Температура, °C</th>
								<th>Давление, мм. рт. ст.</th>
								<th>Влажность, %</th>
								<th>Облачность, %</th>
								<th>Скорость ветра, м/с</th>
								<th>Направление ветра</th>
								<th>Описание</th>
							</tr>
							<?php
							foreach ($data->hourly as $row) {
								$row->pressure = round($row->pressure * 0.75006375541921);
							?>
							<tr>
								<td><?= getTimeFromTimezone($row->dt) ?></td>
								<td><?= $row->temp ?></td>
								<td><?= $row->pressure ?></td>
								<td><?= $row->humidity ?></td>
								<td><?= $row->clouds ?></td>
								<td><?= $row->wind_speed ?></td>
								<td><?= degreeToDirection($row->wind_deg) ?></td>
								<td><?= $row->weather[0]->description ?></td>
							</tr>
							<?php
							}
							?>
						</table>
					</section>
					<section>
						<table class="full_width">
							<tr>
								<th>Время, <?= $timezone ?></th>
								<th>Температура, °C</th>
								<th>Давление, мм. рт. ст.</th>
								<th>Влажность, %</th>
								<th>Облачность, %</th>
								<th>Скорость ветра, м/с</th>
								<th>Направление ветра</th>
								<th>Описание</th>
							</tr>
							<?php
							foreach ($data->daily as $row) {
								$row->pressure = round($row->pressure * 0.75006375541921);
							?>
							<tr>
								<td><?= getTimeFromTimezone($row->dt) ?></td>
								<td><?= "от {$row->temp->min} до {$row->temp->max}" ?></td>
								<td><?= $row->pressure ?></td>
								<td><?= $row->humidity ?></td>
								<td><?= $row->clouds ?></td>
								<td><?= $row->wind_speed ?></td>
								<td><?= degreeToDirection($row->wind_deg) ?></td>
								<td><?= $row->weather[0]->description ?></td>
							</tr>
							<?php
							}
							?>
						</table>
					</section>
				</div>
			</div>
		</div>
		<?php
			}
		} else {
		?>
		<div class="center_parent">
			<p class="error">Местоположение не было выбрано!</p>
		</div>
		<?php
		}
		?>
	</div>
</main>
<?php
footerHTML();
?>
</body>
</html>
