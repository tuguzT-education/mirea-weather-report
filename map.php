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
		<div id="map" class="margin_1_top"></div>
		<script type="module" src="/js/map.js"></script>
	</div>
</main>
<?php
footerHTML();
?>
</body>
</html>
