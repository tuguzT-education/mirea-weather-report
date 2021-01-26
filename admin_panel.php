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

if (loggedIn() && isAdmin()) {
	include_once 'scripts/get_users.php';
?>
<div class="dialog_background" id="remove_user">
	<div class="dialog">
		<h3>Удалить пользователя</h3>
		<form action="/scripts/admin_remove_user.php" method="post">
			<label class="center_parent padding_1">
				<select name="remove_user_email">
					<?php
					if (isset($_SESSION['data_users'])) {
						foreach ($_SESSION['data_users'] as $row) {
						?>
						<option value="<?= $row['email'] ?>">
							<?= "{$row['name']} {$row['surname']}, {$row['email']}" ?>
						</option>
						<?php
						}
					}
					?>
				</select>
			</label>
			<button type="submit" name="remove_user">
				<span class="fa fa-trash margin_0p5_right"></span>
				<span>Удалить</span>
			</button>
		</form>
		<a class="button border margin_1_top" href="/admin_panel.php">
			<span class="fa fa-close margin_0p5_right"></span>
			<span>Отмена</span>
		</a>
	</div>
</div>
<div class="dialog_background" id="make_admin">
	<div class="dialog">
		<h3>Выдать права администратора</h3>
		<form action="/scripts/make_admin.php" method="post">
			<label class="center_parent padding_1">
				<select name="make_admin_email">
				<?php
				if (isset($_SESSION['data_users'])) {
					foreach ($_SESSION['data_users'] as $row) {
					?>
					<option value="<?= $row['email'] ?>">
						<?= "{$row['name']} {$row['surname']}, {$row['email']}" ?>
					</option>
					<?php
					}
				}
				?>
				</select>
			</label>
			<button type="submit" name="make_admin">
				<span class="fa fa-lock margin_0p5_right"></span>
				<span>Выдать</span>
			</button>
		</form>
		<a class="button border margin_1_top" href="/admin_panel.php">
			<span class="fa fa-close margin_0p5_right"></span>
			<span>Отмена</span>
		</a>
	</div>
</div>
<?php
}
?>
<main>
	<?php
	if (loggedIn() && isAdmin()) {
	?>
	<div class="tabs margin_1_vert">
		<input type="radio" name="admin_panel_tab" id="admin_panel_tab_1" checked hidden aria-hidden="true">
		<input type="radio" name="admin_panel_tab" id="admin_panel_tab_2" hidden aria-hidden="true">
		<ul hidden aria-hidden="true">
			<li><label for="admin_panel_tab_1">Пользователи</label></li>
			<li><label for="admin_panel_tab_2">Запросы на администрирование</label></li>
		</ul>
		<div class="padding_1p275 panel">
			<section>
				<div class="horizontal_scroll">
					<?php
					if (isset($_SESSION['error'])) {
					?>
					<div class="center_parent text_center error">
						<p><?= htmlentities($_SESSION['error']); unset($_SESSION['error']); ?></p>
					</div>
					<?php
					} elseif (isset($_SESSION['data_users'])) {
					?>
					<div class="margin_1_bottom flex">
						<a class="button border margin_1_right" href="#remove_user">
							<span class="fa fa-trash margin_0p5_right"></span>
							<span>Удалить пользователя</span>
						</a>
						<a class="button border margin_1_right" href="#make_admin">
							<span class="fa fa-lock margin_0p5_right"></span>
							<span>Выдать права администратора</span>
						</a>
					</div>
					<div class="border">
						<table class="full_width">
							<caption>Пользователи (не включая Вас: <?= count($_SESSION['data_users']) ?>)</caption>
							<tr>
								<th>Имя</th>
								<th>Фамилия</th>
								<th>Email</th>
								<th>Роль</th>
							</tr>
							<?php
							foreach ($_SESSION['data_users'] as $row) {
							?>
							<tr>
								<td><?= $row['name'] ?></td>
								<td><?= $row['surname'] ?></td>
								<td><a href="mailto:<?= $row['email'] ?>"><?= $row['email'] ?></a></td>
								<td><?= $row['role'] ?></td>
							</tr>
							<?php
							}
							?>
						</table>
					</div>
					<?php
					}
					?>
				</div>
			</section>
			<section>
				<?php
				include_once 'scripts/get_admin_requests.php';
				if (isset($_SESSION['error'])) {
				?>
				<div class="center_parent text_center error">
					<p><?= htmlentities($_SESSION['error']); unset($_SESSION['error']); ?></p>
				</div>
				<?php
				} elseif (isset($_SESSION['data_admin_requests'])) {
					foreach ($_SESSION['data_admin_requests'] as $row) {
					?>
					<div class="border padding_1 margin_1 horizontal_scroll">
						<div class="margin_1_bottom">
							<h3>Имя и фамилия: <?= "{$row['name']} {$row['surname']}" ?><br>
								<span>Email: <a href="mailto:<?= $row['email'] ?>"><?= $row['email'] ?></a></span>
							</h3>
							<p><?= $row['text'] ?></p>
						</div>
						<div class="flex">
							<form action="/scripts/accept_admin.php" method="post">
								<button class="margin_1_right"
										type="submit" name="accept_admin"
										value="<?= $row['email'] ?>">
									<span class="fa fa-check margin_0p5_right"></span>
									<span>Принять</span>
								</button>
							</form>
							<form action="/scripts/decline_admin.php" method="post">
								<button type="submit" name="decline_admin"
										value="<?= $row['email'] ?>">
									<span class="fa fa-close margin_0p5_right"></span>
									<span>Отклонить</span>
								</button>
							</form>
						</div>
					</div>
					<?php
					}
				}
				?>
			</section>
		</div>
	</div>
	<?php
	} else {
	?>
	<div class="center_parent text_center">
		<h4 class="error">Доступ запрещен!</h4>
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
