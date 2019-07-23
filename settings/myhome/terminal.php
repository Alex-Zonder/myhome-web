<?php
//   System   //
include("../../_configs/system.ini");
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
$title.=" - Терминал";
$system->InitJava();
include($docRoot.$template."header.php");
//   Myhome   //
$myhome->InitJava();
?>




<!--____________________________   I N F O   ____________________________-->
<div id="returned" style="padding:5px;"></div>


<?php
//								Footer								//
include($docRoot.$template."footer.php");
?>


<!--             Console             -->
<script>
//   Body Footer   //
body_footer_opened = true;
body_footer_height = 32;
CreateConsole("Body_Footer_Content");
</script>



<!--____________________________   S C R I P T   ____________________________-->
<script>
EnableMyhomeWait(); /* Listening Myhome */

//   Myhome Send Command   //
var SendCommand = function () {
	MyhomeSendCommand(Input_Command.value,MyhomeReturned);
}

//   Myhome Make Answer   //
function MyhomeReturned(command,myhome_answer){
	if (myhome_answer['answer']) ans = myhome_answer['answer'];
	else ans = ToJson(myhome_answer);
	//document.getElementById("returned").innerHTML += GetTime() + ": " + command + "->" + ans + "\n";
	document.getElementById("returned").innerHTML = GetTime() + ": " + command + "->" + ans + "<br>" + document.getElementById("returned").innerHTML;
	/*setTimeout(function () {
		document.getElementById("returned").scrollTop = document.getElementById("returned").clientHeight;
	}, 1);*/
}
</script>
