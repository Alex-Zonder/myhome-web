<?php
include("../../_configs/system.ini");
include($docRoot."_modules/system/system.php");
// $docRoot, $htmlRoot, $template, $auth_enabled //
//   File Manager   //
include($docRoot."_modules/file_manager/file_manager.php");
//   Myhome   //
include($docRoot."_modules/myhome/myhome.php");
?>
<?php
//								P O S T								//
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$post_action=$_POST['action'];
	$post_data=$_POST['data'];

	// Get setts //
	if ($post_action == "get_mail_setts") {
		//`cat /etc/msmtprc | grep "user" | grep -v "#" | awk '{print $2}'`
		$mail_file_path = "/etc/msmtprc";
		$mail_file_data = $file_manager->ReadFile($mail_file_path);
		echo $mail_file_data;
	}

	// Save setts //
	if ($post_action == "save_mail_setts") {
		$mail_file_path = "/etc/msmtprc";
		//$file_manager->WriteToFile($mail_file_path,$post_data);
		shell_exec('sudo sh -c "echo -n \'' . $post_data . '\' > ' . $mail_file_path . '"');
		$mail_file_data = $file_manager->ReadFile($mail_file_path);
		echo $mail_file_data;
	}

	// Test setts (Send mail) //
	if ($post_action == "test_mail_setts") {
		//bash "$DIR"myhome/scripts/mail.sh
		//cat /tmp/msmtp.log | tail -n1
		shell_exec('bash ' . $myhome_setts["system"]["pach_to_myhome"] . '/scripts/mail.sh "' . $post_data . '"');
		echo "Test: " . $myhome_setts["system"]["pach_to_myhome"];
	}

	exit();
}
?>
<?php
//								H T M L								//
$title.=" - Настройка Почты";
$system->InitJava();
include($docRoot.$template."header.php");
?>


<div class="info_block">
	<div class="info_block_name">Настройка Почты</div>
	<center>
		<table>
			<tr>
				<td width="50%">Почта</td>
				<td><input type="text" id="Mail_User" /></td>
			</tr>
			<tr>
				<td>Пароль</td>
				<td><input type="text" id="Mail_Pass" /></td>
			</tr>
			<tr>
				<td>История</td>
				<td><input type="text" id="Mail_Log" /></td>
			</tr>
		</table>
	</center>
</div>


<div class="info_block">
	<div class="info_block_name">Тест Почты</div>
	<center>
		<table>
			<tr>
				<td align="center">Введите текст</td>
			</tr>
			<tr>
				<td><textarea rows="4" style="width:100%;" id="Mail_Test_Text">Привет от Ккбер.Дом!</textarea></td>
			</tr>
			<tr>
				<td align="center"><input type="button" value="Тест" onclick="TestMailSetts()" /></td>
			</tr>
		</table>
	</center>
</div>


<?php
//								Footer								//
include($docRoot.$template."footer.php");
?>
<script>
//   Body Footer   //
body_footer_opened = true;
body_footer_height = 32;

var html = '<center><input type="button" value="Сохранить" style="margin-top:6px;" onclick="SaveMailSetts()"></center>';
document.getElementById("Body_Footer_Content").innerHTML = html;
</script>




<script>
//function Init () {}
SendPost ('get_mail_setts','');
function PostReturned (servAnswer,send_action,send_data) {
	//alert(servAnswer);
	if (send_action == 'get_mail_setts') {
		if (servAnswer[0] != "E") {
			var mail = Grep (servAnswer,'user').split(" ")[1];
			Mail_User.value = mail;
			var password = Grep (servAnswer,'password').split(" ")[1];
			Mail_Pass.value = password;
			var logfile = Grep (servAnswer,'logfile').split(" ")[1];
			Mail_Log.value = logfile;
		}
	}
	else if (send_action == 'save_mail_setts') {
		if (servAnswer == send_data)
			alert("Настройки сохранены");
		else
			alert("Ошибка сохранения!");
	}
	else if (send_action == 'test_mail_setts') {
		alert(servAnswer);
	}
}


function SaveMailSetts () {
	var mail = Mail_User.value;
	var server = mail.split("@")[1];
	var password = Mail_Pass.value;
	var logfile = Mail_Log.value;

	var file_data = 'defaults' + "\n";
	file_data += 'logfile ' + Mail_Log.value + "\n";
	file_data += 'tls on' + "\n";
	file_data += 'tls_starttls on' + "\n";
	file_data += 'tls_certcheck off' + "\n";
	file_data += 'auth on' + "\n";
	file_data += 'port 587' + "\n";

	file_data += 'account default' + "\n";
	file_data += 'host smtp.' + server + "\n";
	file_data += 'from ' + mail + "\n";
	file_data += 'user ' + mail + "\n";
	file_data += 'password ' + password + "\n";

	//alert (file_data);
	SendPost ('save_mail_setts',file_data);
}


function TestMailSetts () {
	alert (Mail_Test_Text.value);
	SendPost ('test_mail_setts',Mail_Test_Text.value);
}
</script>
