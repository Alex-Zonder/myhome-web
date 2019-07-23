<?php $theme = 'white'; if (isset($template_theme)) $theme = $template_theme; ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="description" content="<?php echo $description; ?>">
		<title><?php echo($title) ?></title>

		<link rel="stylesheet" type="text/css" href="<?php echo $htmlRoot.$template; ?>css/<?php echo $theme; ?>.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo $htmlRoot.$template; ?>css/main.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo $htmlRoot.$template; ?>css/myhome_buts.css" />

		<script src="<?php echo $htmlRoot.$template; ?>js/main_menu.js"></script>
		<script src="<?php echo $htmlRoot.$template; ?>js/body_footer.js"></script>

		<link rel="shortcut icon" href="<?php echo $htmlRoot; ?>_images/favicon.gif" type="image/x-gif">
		<link rel="apple-touch-icon" href="<?php echo $htmlRoot; ?>_images/iphone_icon.png">
	</head>
	<body>


		<div id="Head"><?php echo explode(" - ",$title)[1]; ?></div>

		<!--     Menu     -->
		<?php if ($auth_enabled && $user!='') { ?>
			<div id="Menu_Holder">
				<div id="Menu_Body_Separator"></div>
				<!--<div id="Head">Меню</div>-->
				<?php include($docRoot."_templates/menu.php") ?>
			</div>

			<script type="text/javascript">
				//   Enable Separator Move   //
				Menu_Body_Separator.onmousedown = function(e) {Enable_Change_Menu_Width (e);}
				document.body.addEventListener("mousemove", function(e) {Change_Menu_Width (e);});
				document.body.addEventListener("mouseup", function(e) {Disable_Change_Menu_Width (e);});
			</script>

			<script type="text/javascript">
				//   Add Keyboard Events   //
				function OnEscape() {Button_Main_Menu ();}
			</script>
		<?php } ?>

		<!--     Body     -->
		<div id="Body_Holder">
			<div id="Body_Block">
				<!--     OnResize()     -->
				<iframe name="body_resized" id="body_resized" style="width:calc(100%);height:0;border:0;"></iframe>
				<script>
				body_resized.onresize = function () {
					if (typeof OnResize === "function")
						OnResize(document.getElementById('body_resized').clientWidth);
					if (typeof Resize_Body_Footer === "function")
						Resize_Body_Footer(document.getElementById('body_resized').clientWidth);
				}
				</script>




				<?php
				//					A U T H O R I Z A T I O N					//
				include($docRoot."_templates/auth.php");
				?>


				<?php
				//					A L E R T S					//
				include($docRoot."_templates/alerts.php");
				?>
