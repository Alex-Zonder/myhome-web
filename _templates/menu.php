<!--			М Е Н Ю			-->
<nobr>


	<div class="menu_but" id="<?php echo $htmlRoot; ?>index" onclick="document.location='<?php echo $htmlRoot; ?>'">
		<img src="<?php echo $htmlRoot; ?>_images/icons/home.png" height='22'style='margin: 3px 4px -3px 2px;'> Мой дом</div>

	<!-- DVR in DOCUMENT_ROOT / dvr / -->
	<?php if (is_dir($_SERVER['DOCUMENT_ROOT'] . '/dvr')) { ?>
	<div class="menu_but" id="" onclick="document.location='/dvr/'">
		<img src="<?php echo $htmlRoot; ?>_images/icons/video.png" height='22'style='margin: 3px 4px -3px 2px;'> Камеры</div>
	<?php } ?>
	<!-- DVR in DOCUMENT_ROOT -->
	<?php if (is_file($_SERVER['DOCUMENT_ROOT'] . '/dvr.conf')) { ?>
	<div class="menu_but" id="" onclick="document.location='/'">
		<img src="<?php echo $htmlRoot; ?>_images/icons/video.png" height='22'style='margin: 3px 4px -3px 2px;'> Камеры</div>
	<?php } ?>

	<?php if ($userGroup=="admin" || !$auth_enabled) { ?>
		<div class="menu_but" id="<?php echo $htmlRoot; ?>settings" onclick="document.location='<?php echo $htmlRoot; ?>settings/'">
			<img src="<?php echo $htmlRoot; ?>_images/icons/settings-1.png" height='20'style='margin: 3px 4px -3px 4px;'> Настройки</div>
		<div class="menu_but" id="<?php echo $htmlRoot; ?>history" onclick="document.location='<?php echo $htmlRoot; ?>history/'">
			<img src="<?php echo $htmlRoot; ?>_images/icons/folder.png" height='22'style='margin: 3px 5px -3px 4px;'> История</div>
	<?php } ?>

	<?php if ($auth_enabled) { ?>
		<div class="menu_but" id="#" onclick="document.location='<?php echo $htmlRoot; ?>_modules/auth/auth.php?action=exit'">
			<img src="<?php echo $htmlRoot; ?>_images/icons/exit.png" height='22'style='margin: 3px 5px -3px 4px;'> Выход</div>
	<?php } ?>


</nobr>






<script>
//alert(document.location.href);
var url=document.location.href.split('//');
url=url[1].split('?');
url=url[0].split('/');
//alert(url[2]);
var page=url[2];
if (page=='') page='index';
else if (page=='user') page=url[4]+'/'+url[5];
document.getElementById("<?php echo $htmlRoot; ?>"+page).style.color='green';
document.getElementById("<?php echo $htmlRoot; ?>"+page).style.textShadow='3px 3px 3px rgba(0, 0, 0, 0.7)';
document.getElementById("<?php echo $htmlRoot; ?>"+page).style.padding='9px 1px 11px 4px';
</script>
<!--			М Е Н Ю			-->
