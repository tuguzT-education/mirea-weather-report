<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/defines/functions.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/defines/templates.php';

session_start();

?>
<!DOCTYPE html>
<html lang="ru">
<?php
headMapHTML('Карта');
?>
<body>
<?php
userHeaderHTML();
?>
<main>
	<div id="map_popup" class="panel padding_0p5">
		<a href="#" id="map_popup_close" class="fa fa-close"></a>
		<div id="map_popup_content"></div>
	</div>
	<div class="panel full_width padding_1p275 margin_2_vert">
		<div class="flex full_width">
			<h3 class="margin_0">Интерактивная погодная карта</h3>
			<?php
			if (loggedIn() && isset($_SESSION['data_locations']) && !empty($_SESSION['data_locations'])) {
			?>
			<div style="margin-left: auto">
				<label for="map_location_selector">Местоположение</label>
				<select id="map_location_selector" class="fixed_width">
					<?php
					if (isset($_SESSION['data_locations'])) {
						foreach ($_SESSION['data_locations'] as $row) {
							?><option><?= $row['name'] ?></option><?php
						}
					}
					?>
				</select>
			</div>
			<?php
			}
			?>
		</div>
		<div id="map" class="margin_1_top"></div>
		<div class="margin_1_top" id="map_layer_select">
			<label for="map_layer_selector" class="margin_1_right">Выберите отображаемый слой</label>
			<select id="map_layer_selector">
				<option>Без слоя</option>
			</select>
		</div>
		<script type="module" src="/js/map.js"></script>
	</div>
</main>
<?php
footerHTML();
?>
</body>
</html>
