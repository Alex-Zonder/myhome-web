<?php $theme = 'white'; if (isset($template_theme)) $theme = $template_theme; ?>
<html>

<!--			З А Г О Л О В К И			-->
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Cache-Control" content="no-cache"/>

	<meta name="description" content="<?php echo $description; ?>">
	<title><?php echo($title) ?></title>

	<link rel="stylesheet" type="text/css" href="<?php echo $htmlRoot.$template; ?>main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $htmlRoot.$template; ?>myhome_buts.css" />
	<script type="text/javascript" src="<?php echo $htmlRoot.$template; ?>main.js"></script>

	<link rel="shortcut icon" href="<?php echo $htmlRoot; ?>_images/favicon.gif" type="image/x-gif">
	<link rel="apple-touch-icon" href="<?php echo $htmlRoot; ?>_images/iphone_icon.png">
</head>


<body>




<div id="bodyholder">
<!--___________В Ы Е З Ж А Ю Щ Е Е    М Е Н Ю___________-->
<div id="bodymenu">
	<?php include($docRoot."_templates/menu.php"); ?>
</div>

<div id="bodymain">
<!--	___________Ш А П К А___________-->
<div id="bodyhead" align="center" >
		<div id="butOpenMenu" onclick="butOpenMenu()">Меню</div>
		<div id="cyberLightName"></div>
		<a href="http://cyber-light.ru/" target="_blank"><div id="cyberLightLogo"></div></a>
	</div>
<!--	___________Т Е Л О___________-->
	<div class="bodybody" id="bodybody">


		<!--	___________ Body_Holder ___________ -->
		<div id="Body_Holder" style="width:100%"></div>
		<div id="Body_Block">
		<iframe name="body_resized" id="body_resized" style="width:calc(100%);height:0;border:0;"></iframe>
		<script>
		body_resized.onresize = function () {
			if (typeof OnResize === "function") OnResize(document.getElementById('body_resized').clientWidth);
			//else alert(document.getElementById('body_resized').clientWidth+"\n Add OnResize(){}");
		}
		</script>

<center>



<?php
//					A U T H O R I Z A T I O N					//
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
}
?>







<div class="body" id="body">


<?php
//					H E L L O   F O R   A U T H O R I Z E D					//
//if ($auth_enabled && $user!='') echo "<hr><center>".$user."</center><hr>";
?>
