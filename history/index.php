<?php
include("../_configs/system.ini");
include($docRoot."_modules/system/system.php");
// $docRoot, $htmlRoot, $template, $auth_enabled //
//   Myhome   //
include($docRoot."_modules/myhome/myhome.php");
//   File Manager   //
include($docRoot."_modules/file_manager/file_manager.php");
?>
<?php
//								P O S T								//
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$post_action=$_POST['action'];
	$post_data=$_POST['data'];

	$file_data = $file_manager->OpenFile ($post_data,'');
	echo $file_data;

	exit();
}
?>
<?php
//								H T M L								//
$title.=" - История событий";
$system->InitJava();
include($docRoot.$template."header.php");
//   Myhome   //
$myhome->InitJava();
//								H T M L								//
?>



<div id="Div_Text" style="font-size: 13px; padding: 5px; -webkit-user-select: text;"></div>



<?php
//								Footer								//
include($docRoot.$template."footer.php");
?>

<!--								S C R I P T								-->
<script>
	//   Init   //
	var path_to_file = myhome_setts['system']['pach_to_myhome'] + '/logs/myhome/' + GetDate() + '.txt';
	var file_data = '';
	SendPost ('get_file',path_to_file);
	function PostReturned (servAnswer,send_action,send_data) {
		file_data = servAnswer;
		file_data = rsort(file_data);
		file_data = HighLightText(file_data,'Error,ERROR,error','#990033'); // red
		file_data = HighLightText(file_data,'USART,UART,POST','#cc8800'); // orange
		file_data = HighLightText(file_data,'AUTO BY:,ACT:','#0066ff'); // blue
		file_data = HighLightText(file_data,'TCP CONNECTION:,TCP CLOSE:,IP:','#2eb82e'); // green
		FillDivTextByLines(file_data,'Div_Text');
	}


	//   Body Footer   //
	body_footer_opened = true;
	body_footer_height = 32;

	var console_holder = document.getElementById("Body_Footer_Content");
	console_holder.style.overflow = "hidden";

	// Command Holder //
	var command_holder = document.createElement('div');
	command_holder.id = "Command_Holder";
	console_holder.appendChild(command_holder);
	command_holder.innerHTML = '<div id="Command_Input"><input type="text" id="Console_Command" value="$01Z;" onkeyup="keyboard.CheckEnter(Grep_By_But)"></div>';
	command_holder.innerHTML += '<div id="Command_Send" onclick="Grep_By_But();">Фильтр</div>';



	function Grep_By_But(){
		data = file_data;
		grep = document.getElementById('Console_Command').value;
		if (grep != '') {
			new_data = Grep(data,grep);
			new_data = HighLightText(new_data,grep,'teal');
		}
		else {
			new_data = data;
		}
		FillDivTextByLines(new_data,'Div_Text');
	}
</script>
