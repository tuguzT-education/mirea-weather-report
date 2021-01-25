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
	include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/get_users.php';
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
		if (isset($_SESSION['error'])) {
		?>
		<div class="center_parent text_center error">
			<p><?= htmlentities($_SESSION['error']); unset($_SESSION['error']); ?></p>
		</div>
		<?php
		} elseif (isset($_SESSION['data_users'])) {
		?>
		<div class="margin_1_vert flex">
			<a class="button border margin_1_right" href="/admin_panel.php#remove_user">
				<span class="fa fa-trash margin_0p5_right"></span>
				<span>Удалить пользователя</span>
			</a>
			<a class="button border margin_1_right" href="/admin_panel.php#make_admin">
				<span class="fa fa-lock margin_0p5_right"></span>
				<span>Выдать права администратора</span>
			</a>
		</div>
		<div class="margin_1_vert horizontal_scroll">
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
		<?php
		}
		?>
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
