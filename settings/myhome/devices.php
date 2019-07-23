<?php
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
$title.=" - Настройка устройств";
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


<!--             Device_Holder             -->
<div class="info_block">
	<div class="info_block_name" id="Device_Name">Устройство</div>
	<center>
		<div id="Device_Holder"><div style="padding:10px;">Выберите устройство</div></div>
	</center>
</div>


</center>
<?php
//								Footer								//
include($docRoot.$template."footer.php");
?>

<!--             Console             -->
<script>
CreateConsole("Body_Footer_Content");
//body_footer_opened = true;
</script>





<script>
// --------------------- INIT --------------------- //
var CreateDevice = function (id) {
	var newBut = document.createElement('div');
	newBut.id = "Device_But_" + id;
	newBut.className = "block_top_menu_but";
	newBut.onclick = function () {ViewDeviceByBut(id);};
	newBut.innerHTML = myhome_setts['devices'][id]['name'];
	document.getElementById("block_top_menu_holder").appendChild(newBut);
}
var GreateDevs = function () {
	for (var x=0; x < myhome_setts['devices'].length; x++) {
		if (myhome_setts['devices'][x]['enabled']) CreateDevice(x);
	}
}
var Init = function () {
	GreateDevs();
}
Init();
</script>




<script>
// --------------------- Open Device --------------------- //
var devices = [];
var ViewDevice = function (id) {
	ClearCommands();

	// Change Buts in Top_Menu //
	for (var x=0; document.getElementById("Device_But_"+x); x++)
		document.getElementById("Device_But_"+x).className = "block_top_menu_but";
	document.getElementById("Device_But_"+id).className = "block_top_menu_but_pushed";

	// Create Device //
	devices = [];
	if (myhome_setts['devices'][id]['type'] == 'RM-4')
		devices.push(new RM_4 (0,'Device_Holder',myhome_setts['devices'][id]));
	else if (myhome_setts['devices'][id]['type'] == 'I-16')
		devices[0] =  new I_16 (0,'Device_Holder',myhome_setts['devices'][id]);
	if (myhome_setts['devices'][id]['type'] == 'IP_CAM')
		devices[0] =  new IP_CAM (0,'Device_Holder',myhome_setts['devices'][id]);

	devices[0].Create_Device_Setts_Dev();
}
var ViewDeviceByBut = function (id) {
	if (document.getElementById("Device_But_"+id))
		ViewDevice(id);
}
</script>




<script>
// --------------------- Make Answer --------------------- //
EnableMyhomeWait();
function MyhomeReturned(command,myhome_answer){
	//ConsoleWrite(command + " -> " + myhome_answer["answer"]);

	//if (Device_Version) Device_Version.innerHTML = myhome_answer["answer"];
	MakeCommands (myhome_answer["answer"]);
}
var MakeCommands = function (returned) {
	var commands = returned.split(";");
	for (var x=0; commands[x]; x++) MakeCommand(commands[x]);
}
var MakeCommand = function (returned) {
	if (returned[0] == "#") {
		var address = returned[1] + returned[2];
		if (devices[0] && devices[0].adress == address && typeof devices[0].MakeCommandSettings == 'function') {
			devices[0].MakeCommandSettings (returned);
		}
	}
}
</script>
