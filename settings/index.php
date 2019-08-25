<?php
include("../_configs/system.ini");
include($docRoot."_modules/system/system.php");
// $docRoot, $htmlRoot, $template, $auth_enabled //
?>
<?php
//								P O S T								//
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$post_action=$_POST['action'];
	$post_data=$_POST['data'];
}
?>
<?php
//								H T M L								//
$title.=" - Настройки";
$system->InitJava();
include($docRoot.$template."header.php");
?>





<div class="info_block">
	<div class="info_block_name">Мой дом</div>
	<center>
		<div class="butBig"><a href="myhome/main.php">Основные настройки</a></div>
		<hr style="margin:-8 0 -8 0;"><div class="butBig"><a href="myhome/auto.php">Автоматизация</a></div>
		<div class="butBig"><a href="myhome/devices.php">Настройка устройств</a></div>
		<hr style="margin:-8 0 -8 0;"><div class="butBig"><a href="myhome/terminal.php">Терминал - Умный дом</a></div>
	</center>
</div>




<div class="info_block">
	<div class="info_block_name">Сеть</div>
	<center>
		<div class="butBig"><a href="network/scan_ip.php">Сканер сети</a></div>
		<div class="butBig"><a href="network/scan_stream.php">Сканер потоков камер</a></div>
	</center>
</div>




<div class="info_block">
	<div class="info_block_name">Интернет</div>
	<center>
		<div class="butBig"><a href="internet/vpn.php">VPN - Мой.Киберлайт</a></div>
		<div class="butBig"><a href="internet/mail.php">Нстройка почты</a></div>
	</center>
</div>




<div class="info_block">
	<div class="info_block_name">Дополнительно</div>
	<center>
		<div class="butBig"><a href="system/system.php">Настройки системы</a></div>
		<div class="butBig"><a href="system/users.php">Пользователи</a></div>
		<hr style="margin:-8 0 -8 0;"><div class="butBig"><a href="system/backup.php">Бэкап настроек</a></div>
		<div class="butBig"><a href="system/update.php">Обновление программы</a></div>
		<hr style="margin:-8 0 -8 0;"><div class="butBig"><a href="system/info.php">О системе</a></div>
	</center>
</div>





<?php
//								Footer								//
include($docRoot.$template."footer.php");
?>
