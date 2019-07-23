<?php $theme = 'white'; if (isset($template_theme)) $theme = $template_theme; ?>
<html>

<!--			З А Г О Л О В К И			-->
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Cache-Control" content="no-cache"/>

	<meta name="description" content="<?php echo $description; ?>">
	<title><?php echo($title) ?></title>

	<link rel="stylesheet" type="text/css" href="<?php echo $htmlRoot.$template; ?>css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $htmlRoot.$template; ?>css/<?php echo $theme; ?>.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $htmlRoot.$template; ?>css/top_menu.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $htmlRoot.$template; ?>css/myhome_buts.css" />

	<script type="text/javascript" src="<?php echo $htmlRoot.$template; ?>main.js"></script>

	<link rel="shortcut icon" href="<?php echo $htmlRoot; ?>_images/favicon.gif" type="image/x-gif">
	<link rel="apple-touch-icon" href="<?php echo $htmlRoot; ?>_images/iphone_icon.png">
</head>


<body>




<!--			Ш А П К А			-->
<div class="head"><div class="head_holder">
	<div style="float: left; width: 54px; cursor: pointer;box-shadow: inset 0px -1px 6px #111111;" onclick="MenuOnClick()">
		<img src="<?php echo $htmlRoot; ?>_images/icons/menu.png" width="100%">
	</div>
	<div class="syte_name">
		<center><a href="<?php echo $htmlRoot; ?>"><?php echo $siteName; ?></a></center>
	</div>
	<div style="float: left; width: 54px;">
		<center>
			<a href="http://cyber-light.ru/" target="_blank">
				<img src="<?php echo $htmlRoot; ?>_images/cyberlight_logo.gif" width="80%" height="85%" style="padding: 4px 5px 4px 6px; background: #eaeaea; opacity: 0.9; box-shadow: inset 0px -1px 6px black;">
			</a>
		</center>
	</div>
	<div style="clear: both"></div>
</div></div>


<div class="site" id="site">




<?php
//					A U T H O R I Z A T I O N					//
include($docRoot."_templates/auth.php");
?>




<!--			М Е Н Ю			-->
<div class="menu_holder" id="menu_holder"><div class="menu" id="menu" style="">
	<?php include($docRoot."_templates/menu.php"); ?>
</div></div>
<!--			М Е Н Ю			-->




<div class="body" id="body">
<div id="Body_Holder" style="width:100%"></div>
<div id="Body_Block">
<iframe name="body_resized" id="body_resized" style="width:calc(100%);height:0;border:0;"></iframe>
<script>
body_resized.onresize = function () {
	if (typeof OnResize === "function") OnResize(document.getElementById('body_resized').clientWidth);
	//else alert(document.getElementById('body_resized').clientWidth+"\n Add OnResize(){}");
}
</script>




<?php
//					H E L L O   F O R   A U T H O R I Z E D					//
//if ($auth_enabled && $user!='') echo "<hr><center>".$user."</center><hr>";
?>
<!--____________________________   Оповещения   ____________________________-->
<?php
//					A L E R T S					//
include($docRoot."_templates/alerts.php");
?>
