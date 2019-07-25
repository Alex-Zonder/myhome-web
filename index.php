<?php
//   System   //
include("_configs/system.ini");
include($docRoot."_modules/system/system.php");
// $docRoot, $htmlRoot, $template, $auth_enabled //
//   Myhome   //
include($docRoot."_modules/myhome/myhome.php");
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
//   System   //
$title.=" - Управление";
$system->InitJava();
include($docRoot.$template."header.php");
//   Myhome   //
$myhome->InitJava();
?>
<center>


<!--             TOP MENU             -->
<div class="block_top_menu">
	<nobr><div class="block_top_menu_holder" id="block_top_menu_holder"></div></nobr>
</div>


<!--             Groups_Holder             -->
<div id="Groups_Div" style="width: 100%; overflow: hidden;">
	<div id="Groups_Holder" style="width: 100%; overflow: hidden;"></div>
</div>


</center>
<?php
//								Footer								//
include($docRoot.$template."footer.php");
?>

<!--             Console             -->
<script>
CreateConsole("Body_Footer_Content");
</script>



<!--____________________________   S C R I P T   I N I T   ____________________________-->
<script>
function Moved_Divs_Changed (div_id) {
	document.body.scrollTop = 0;
	setTimeout(function(){document.body.scrollTop = 0;},350);
}
// Swipes //
AddTouchMove("Groups_Div");
function TouchMoved(x){
	if (x<0) moved_myhome.Change_Div_By_But(moved_myhome.obj_opened+1);
	else moved_myhome.Change_Div_By_But(moved_myhome.obj_opened-1);
}
//   Add Keyboard Events   //
function OnLeftArrow() {if (document.activeElement.id == '') moved_myhome.Change_Div_By_But(moved_myhome.obj_opened-1);}
function OnRightArrow() {if (document.activeElement.id == '') moved_myhome.Change_Div_By_But(moved_myhome.obj_opened+1);}


//   Init Myhome   //
document.addEventListener("DOMContentLoaded", Init_Myhome);
</script>
