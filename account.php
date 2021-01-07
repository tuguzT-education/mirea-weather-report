<?php

require_once 'defines/functions.php';
require_once 'defines/templates.php';

session_start();

$fullName = loggedIn() ? "{$_SESSION['name']} {$_SESSION['surname']}" : 'Гость';

?>
<!DOCTYPE html>
<html lang='ru'>
<?php
headHTML($fullName);
?>
<body>
<?php
userHeaderHTML();
?>
<main>
	<?php
	if (loggedIn()) {
	?>
	<!-- todo add content -->
	<?php
	} else {
	?>
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
