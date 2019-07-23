<?php
function ViewAuth () {
	?>
	<center>
	<div class="info_block" style="width: calc(100% - 2px);">
		<div class="info_block_name">Авторизация</div>
		<div class="info_block_info">
			<center>
			<form method="post">
			<input type="hidden" name="url" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
			<table border="0" width="100%">
				<tr><td align="right" width="50%">Имя</td><td><input type="text" name="name" value="guest" style="text-align:center;"></td></tr>
				<tr><td align="right" width="50%">Пароль</td><td><input type="password" name="pass" value="" style="text-align:center;"></td></tr>
			</table>
			<input type="submit" value="Вход">
			</form>
			</center>
		</div>
	</div>
	</center>
	<?php
}

if ($auth_enabled && $user=='') {
	ViewAuth();
	include($docRoot.$template.'footer.php');
	die();
} else {
	echo "<script>var user = {\"name\":\"" . $GLOBALS['user'] . "\", \"group\":\"" . $GLOBALS['userGroup'] . "\"};</script>";
}
?>
