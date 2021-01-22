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
	<div class="panel full_width padding_1p275 margin_2_vert">
		<h3 class="margin_0">Интерактивная погодная карта</h3>
		<div id="map" class="margin_1_top" tabindex="1"></div>
		<div class="margin_1_top">
			<label for="map_panel_layer_select" class="margin_1_right">Выберите отображаемый слой</label>
			<select id="map_panel_layer_select">
				<option hidden>Слой не выбран...</option>
				<option>Температура</option>
			</select>
		</div>
		<script type="module" src="/js/map.js" async></script>
	</div>
</main>
<?php
footerHTML();
?>
</body>
</html>
