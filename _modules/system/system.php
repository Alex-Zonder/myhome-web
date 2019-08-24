<?php
//			V A R S			//
$system_OS=exec('uname');

//			A U T H   M O D U L E			//
if ($auth_enabled=="yes" || $auth_enabled=="1" || $auth_enabled==true) {
	$auth_enabled=true;
	include($docRoot.'_modules/auth/auth.php');
}
else $auth_enabled=false;


//			GET/POST (curl)   M O D U L E			//
include($docRoot.'_modules/system/get_post.php');


//								Start Class (System)								//
class System {
	//			I N I T			//
	function InitJava () {
		global $htmlRoot,$docRoot,$coocie_prefix;
		?>
		<script src="<?php echo $htmlRoot; ?>_modules/system/useragent.js"></script>
		<script src="<?php echo $htmlRoot; ?>_modules/system/get_post.js"></script>
		<script src="<?php echo $htmlRoot; ?>_modules/system/keyboard.js"></script>
		<script src="<?php echo $htmlRoot; ?>_modules/system/offset.js"></script>
		<script src="<?php echo $htmlRoot; ?>_modules/system/window_active.js"></script>
		<script src="<?php echo $htmlRoot; ?>_modules/system/date.js"></script>
		<script src="<?php echo $htmlRoot; ?>_modules/system/touch.js"></script>
		<!--<script><?php /*echo shell_exec('cat '.$docRoot.'_modules/system/touch.js 2>&1');*/ ?></script>-->
		<script src="<?php echo $htmlRoot; ?>_modules/system/transition.js"></script>
		<script src="<?php echo $htmlRoot; ?>_modules/system/cookies.js"></script>
		<script src="<?php echo $htmlRoot; ?>_modules/system/text.js"></script>
		<script src="<?php echo $htmlRoot; ?>_modules/system/input.js"></script>
		<script src="<?php echo $htmlRoot; ?>_modules/system/moved_divs.js"></script>
		<?php
		//			C O O K I E S			//
		echo '<script>var cookie_prefix="' . $coocie_prefix . '";</script>';
	}


	//			J S O N			//
	function ToJson ($json) {return json_encode($json, JSON_UNESCAPED_UNICODE);}
	function FromJson ($json) {return json_decode($json, true);}


	//			A U T H   M O D U L E			//
	function InitAuthoriztion () {
		include($docRoot.'_modules/auth/auth.php');
	}
}
$system=new System;
//								End Class (System)								//
?>
