<?php
//   System   //
include("../../_configs/system.ini");
include($docRoot."_modules/system/system.php");
// $docRoot, $htmlRoot, $template, $auth_enabled //
//   File Manager   //
include($docRoot."_modules/file_manager/file_manager.php");
?>
<?php
//								P O S T								//
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$post_action=$_POST['action'];
	$post_data=$_POST['data'];
	
	// Get Setts //
	if ($post_action == 'get_users_setts') {
		echo $system->ToJson($users_base);
	}
	
	exit();
}
?>
<?php
//								H T M L								//
$title.=" - Настройки";
//$template='_templates/1/';
$system->InitJava();
include($docRoot.$template."header.php");
?>






<div class="info_block">
	<div class="info_block_name">Пользователи</div>
	<center>
		<div id="Users_Setts_Div"></div>
	</center>
</div>





<?php
//								Footer								//
include($docRoot.$template."footer.php");
?>

<script>
document.addEventListener("DOMContentLoaded", function() {
	setTimeout(function () {
		SendPost ('get_users_setts','');
	}, 10);
});

function PostReturned (serv_answer,send_action,send_data) {
	var users = FromJson(serv_answer);
	for (var x=0; x<users.length; x++) {
		var html = users[x]["name"];
		html += "<br>" + ToJson(users[x]) + "<hr>";
		Users_Setts_Div.innerHTML += html;
	}
}
</script>