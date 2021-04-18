<?php
include("../../_configs/system.ini");
include($docRoot."_modules/system/system.php");
// $docRoot, $htmlRoot, $template, $auth_enabled //
//   File Manager   //
include($docRoot."_modules/file_manager/file_manager.php");
?>
<?php
//								F U N C S								//
// Vpn_ppp settings //
function GetPppSettings () {
	$name_pass = explode(" ", exec("sudo cat /etc/ppp/chap-secrets | grep l2tp"));
	$name = $name_pass[0];
	$pass = $name_pass[2];
	$mtu = exec("sudo cat /etc/ppp/options.xl2tpd | grep mtu | awk '{print $2}'");
	$server = exec("sudo cat /etc/xl2tpd/xl2tpd.conf | grep lns | awk '{print $3}'");

	return (['name' => $name, 'pass' => $pass, 'mtu' => $mtu, 'server' => $server]);
}
//								P O S T								//
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	# Загрузка файла #
	if ($_FILES) {
		// var_dump($_FILES);
		// // $file_manager->LoadFile('file', '/tmp/client.conf');
		// move_uploaded_file($_FILES['file']['tmp_name'], '/tmp/client.conf');
		// echo '<hr>' . file_get_contents('/tmp/client.conf');

		$dir = '/etc/openvpn/';
		if (is_dir($dir)) {
			move_uploaded_file($_FILES['file']['tmp_name'], '/tmp/client.conf');
			exec("sudo mv /tmp/client.conf {$dir}client/client.conf");
			exec('sudo systemctl enable openvpn-client@client');
			exec('sudo systemctl start openvpn-client@client');
			echo 'Ok';
		} else {
			echo 'Error: No dir ' . $dir;
		}
		exit;
	}

	$post_action=$_POST['action'];
	$post_data=$_POST['data'];

	// Save PPP settings //
	if ($post_action == 'save_ppp') {
		$save_data = $system->FromJson($post_data);

		// /etc/ppp/chap-secrets
		$file = '/etc/ppp/chap-secrets';
		$file_data = $save_data['name'] . ' l2tp ' . $save_data['pass'];

		if (!is_file($file)) exec('sudo touch ' . $file);
		exec('sudo chmod 777 ' . $file);
		$file_manager->WriteToFile($file,$file_data);
		exec('sudo chmod 600 ' . $file);

		// /etc/ppp/options.xl2tpd
		$file = '/etc/ppp/options.xl2tpd';
		$file_data = 'unit 0' . "\n" .
			'remotename l2tp' . "\n" .
			'ipparam cyber-light' . "\n" .
			'mru ' . $save_data['mtu'] . "\n" .
			'mtu ' . $save_data['mtu'] . "\n" .
			'nodeflate' . "\n" .
			'persist' . "\n" .
			'maxfail 0' . "\n" .
			'nopcomp' . "\n" .
			'noaccomp' . "\n" .
			'noauth' . "\n" .
			'name ' . $save_data['name'] . "\n";

		if (!is_file($file)) exec('sudo touch ' . $file);
		exec('sudo chmod 777 ' . $file);
		$file_manager->WriteToFile($file,$file_data);
		exec('sudo chmod 644 ' . $file);

		// /etc/xl2tpd/xl2tpd.conf
		$file = '/etc/xl2tpd/xl2tpd.conf';
		$file_data = '[global]' . "\n" .
			'access control = yes' . "\n" .
			'[lac cyber-light]' . "\n" .
			'lns = ' . $save_data['server'] . "\n" .
			'redial = yes' . "\n" .
			'redial timeout = 10' . "\n" .
			'require chap = yes' . "\n" .
			'require authentication = yes' . "\n" .
			'ppp debug = yes' . "\n" .
			'pppoptfile = /etc/ppp/options.xl2tpd' . "\n" .
			'require pap = no' . "\n" .
			'autodial = yes' . "\n" .

			'name = ' . $save_data['name'] . "\n";

		if (!is_file($file)) exec('sudo touch ' . $file);
		exec('sudo chmod 777 ' . $file);
		$file_manager->WriteToFile($file,$file_data);
		exec('sudo chmod 644 ' . $file);

		// Save Check //
		$ppp_setts = GetPppSettings();
		if ($save_data['name'] == $ppp_setts['name'] &&
			$save_data['pass'] == $ppp_setts['pass'] &&
			$save_data['mtu'] == $ppp_setts['mtu'] &&
			$save_data['server'] == $ppp_setts['server']) {
				$restart = exec('sudo service xl2tpd restart && echo OK || echo Error');
				echo 'Save OK. Restart ' . $restart;
			}
		else
			echo 'Save Error';
	}
	exit();
}
?>
<?php
//								H T M L								//
$title.=" - Настройка Мой.Киберлайт";
$system->InitJava();
include($docRoot.$template."header.php");
?>


<?php
// Vars XL2TPD //
echo '<script>';
// Vpn_ppp settings //
$ppp_setts = GetPppSettings();
echo "var vpn_ppp = {'name':'" . $ppp_setts['name'] .
	"','pass':'" . $ppp_setts['pass'] .
	"','mtu':'" . $ppp_setts['mtu'] .
	"','server':'" . $ppp_setts['server'] . "'};";

// Vpn_ppp online //
$ppp = substr(explode(" ", exec("ifconfig | grep 'ppp' | grep 'mtu'"))[0], 0, -1);
$ppp_ip = exec("ifconfig " . $ppp . " | grep inet");
echo 'var ppp="' . $ppp . '";';
echo 'var ppp_ip="' . $ppp_ip . '";';
echo '</script>';
?>



<div class="info_block">
	<div class="info_block_name">Соединение</div>
	<center>
		<div id="vpn_connect">
			<?php echo $ppp . ":" . $name . ":" . $pass . ":" . $mtu . ":" . $server; ?>
		</div>
	</center>
</div>

<div class="info_block">
	<div class="info_block_name">Протокол L2TP</div>
	<center>
		<table>
			<tr>
				<td width="50%">Имя</td>
				<td><input type="text" id="ppp_name" /></td>
			</tr>
			<tr>
				<td>Пароль</td>
				<td><input type="text" id="ppp_pass" /></td>
			</tr>
		</table><table>
			<tr>
				<td width="50%">Сервер</td>
				<td><input type="text" id="ppp_server" /></td>
			</tr>
			<tr>
				<td>MTU</td>
				<td><input type="text" id="ppp_mtu" /></td>
			</tr>
		</table>
	</center>
</div>

<div class="info_block">
	<div class="info_block_name">Протокол OpenVPN</div>
	<center>
		<form method="post" enctype="multipart/form-data">
			<div>
				<?php echo exec('ifconfig | grep tun') != ''
					? '<font color="#00e600"><b>Соединение установлено</b></font>'
					: '<font color="red"><b>Соединение отсутствует</b></font>';
				?>
			</div>
			<hr>
			<div>
				<input type="file" id="file" name="file" multiple style="display: none;" onchange="onFileChange(this.files)">
				<button type="button" class="btn btn-success" onclick="document.getElementById('file').click()">Загрузить файл (.ovpn)</button>
			</div>
		</form>
	</center>
</div>

<div class="info_block">
	<div class="info_block_name">Совет</div>
	<center>
		Если Вы испытываете трудности с соединением с сервером или при
		работе с камерами через сервер, попробуйте понизить MTU.
		<hr>
		Это может быть связано с настройками Вашего провайдера или роутера.
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

document.getElementById("Body_Footer_Content").innerHTML = '<center><input type="button" value="Сохранить" style="margin-top:6px;" onclick="SaveSettings()"></center>';
</script>



<script>
function Init () {
	if (vpn_ppp['server'] == '' || vpn_ppp['server'] == '=') vpn_ppp['server'] = 'vpn.cyber-light.ru';
	if (vpn_ppp['mtu'] == '') vpn_ppp['mtu'] = '1400';
	ppp_name.value = vpn_ppp['name'];
	ppp_pass.value = vpn_ppp['pass'];
	ppp_server.value = vpn_ppp['server'];
	ppp_mtu.value = vpn_ppp['mtu'];

	if (ppp != '')
		vpn_connect.innerHTML = '<font color="#00e600"><b>Соединение установлено</b></font><hr>' + ppp + ":" + ppp_ip;
	else
		vpn_connect.innerHTML = '<font color="red"><b>Соединение отсутствует</b></font>';
}
Init();
function PostReturned (servAnswer,send_action,send_data) {
	alert(servAnswer);
}
function SaveSettings () {
	if (confirm("Сохранить настройки?")) {
		var data = {'name':ppp_name.value,'pass':ppp_pass.value,'server':ppp_server.value,'mtu':ppp_mtu.value};
		SendPost('save_ppp',ToJson(data));
	}
}
</script>


<script>
// Загрузка файла
function onFileChange(e) {// alert('aaa' + e[0].name); console.log(e);
	var files = e;

	if (!files.length)
		return;

	sendFile(files[0]);
}
function sendFile(file) {// alert('bbb');
	var data = new FormData();
	data.append('file', file);
	// data.append('phoneId', this.phone.id);
	fetch('', {
		method: 'POST',
		body: data
	})
	.then(response => response.text())
	.then(text => {
		// if (text.match('^ok')) {
		// 	// let imageName = text.split('ok:')[1];
		// 	// this.phone.image = imageName;
		// }
		// else {
			alert(text);
		// }
	});
}
</script>
