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
//								F U N C T I O N S								//
$tmp_dir = $docRoot . 'tmp/';
$archive_dir = $tmp_dir . 'cyber_confs/';
$myhome_user = explode('/', $myhome_setts['system']['pach_to_myhome'])[2];

// Check Dirs //
function CheckDirs ($tmp_dir, $archive_dir) {
//	global $tmp_dir, $archive_dir;
	if (!is_dir($tmp_dir)) shell_exec("sudo mkdir -p " . $tmp_dir);
	if (!is_dir($archive_dir)) shell_exec("sudo mkdir -p " . $archive_dir);
	shell_exec("sudo chmod -R 777 " . $tmp_dir);
	shell_exec("sudo chmod -R 777 " . $archive_dir);
}
CheckDirs ($tmp_dir, $archive_dir);

// Make archive //
function MakeArchive () {
	global $docRoot, $tmp_dir, $archive_dir, $file_manager, $myhome_setts, $myhome_user;

	$file_name = 'cyber_myhome_conf.tar';
	$file_path = $tmp_dir . $file_name;

	// Make tmp folder //
	if (!is_dir($tmp_dir))
		$file_manager->MakeDir($tmp_dir,0777);

	// Remove file //
	if (is_file($file_path))
		exec('sudo rm '.$file_path);

	// Make archive folder //
	if (is_dir($archive_dir))
		shell_exec('sudo rm -r '. $archive_dir);
	$file_manager->MakeDir($archive_dir,0777);


	// Web //
	shell_exec('cp -r ' . $docRoot . '_configs/ ' . $archive_dir . 'web/');
	// Myhome //
	shell_exec('cp -r ' . $myhome_setts['system']['pach_to_myhome'] . '/conf/ ' . $archive_dir . 'myhome/');

	// System //
	$archive_dir_etc = $archive_dir . 'etc';
	$file_manager->MakeDir($archive_dir_etc,0777);
	// System -> Myhome //
	shell_exec('sudo cp /etc/myhome.conf ' . $archive_dir_etc);
	// System -> Crontab //
	shell_exec('sudo crontab -l > ' . $archive_dir_etc . '/cron.sudo.txt');
	shell_exec('sudo -u ' . $myhome_user . ' crontab -l > ' . $archive_dir_etc . '/cron.user.txt');
	// System -> Vpn //
	$file_manager->MakeDir($archive_dir_etc . '/xl2tpd',0777);
	shell_exec('sudo cp /etc/xl2tpd/xl2tpd.conf ' . $archive_dir_etc . '/xl2tpd/');
	shell_exec('sudo cp /etc/ppp/options.xl2tpd ' . $archive_dir_etc . '/xl2tpd/');
	shell_exec('sudo cp /etc/ppp/chap-secrets ' . $archive_dir_etc . '/xl2tpd/');
	// System -> OpenVpn //
	shell_exec('sudo cp -r /etc/openvpn/ ' . $archive_dir_etc . '/openvpn/');
	// System -> Mail //
	shell_exec('sudo cp /etc/msmtprc ' . $archive_dir_etc);
	// System -> FFserver //
	shell_exec('sudo cp /etc/ffserver.conf ' . $archive_dir_etc);
	// System -> rc.local //
	shell_exec('sudo cp /etc/rc.local ' . $archive_dir_etc);


	// Make Archive //
	shell_exec('cd ' . $tmp_dir . ' && sudo tar -cvf '.$file_path.' cyber_confs 2>&1');
}

// Restore archive //
function RestoreArchive ($file_path) {
	global $docRoot, $tmp_dir, $archive_dir, $file_manager, $myhome_setts, $myhome_user, $system;
	$restored = array();

	// Remove archive folder //
	if (is_dir($archive_dir))
		shell_exec('sudo rm -r '. $archive_dir);

	// Un Archive //
	shell_exec('tar -xf '.$file_path.' -C '.$tmp_dir.' 2>&1');

	// Restore Web //
	if (is_dir($archive_dir . 'web')) {
		$web_confs_path = $docRoot . '_configs/';

		//$file_manager->RemoveDirR($web_confs_path);
		shell_exec('sudo rm -r ' . $web_confs_path . '*');
		shell_exec('sudo cp -r ' . $archive_dir . 'web/* ' . $web_confs_path);
		shell_exec('sudo chmod -R 777 ' . $web_confs_path);

		$restored[] = 'web';
	}
	// Restore Web v.2 //
	if (is_dir($archive_dir . 'cgi')) {
		$web_confs_path_v2 = $docRoot . '_configs/v2/';

		if (is_dir($web_confs_path_v2))
			shell_exec('sudo rm -r ' . $web_confs_path_v2);
		shell_exec('sudo cp -r ' . $archive_dir . 'cgi/ ' . $web_confs_path_v2);
		shell_exec('sudo chmod -R 777 ' . $web_confs_path_v2);

		include("backup_versions_conv.php");
		ConvertSetts ();

		$restored[] = 'cgi';
	}

	// Restore Myhome //
	if (is_dir($archive_dir . 'myhome')) {
		$myhome_confs_path = $myhome_setts['system']['pach_to_myhome'] . '/conf/';

		shell_exec('sudo rm -r ' . $myhome_setts['system']['pach_to_myhome'] . '/conf/');
		shell_exec('sudo cp -r ' . $archive_dir . 'myhome/ ' . $myhome_setts['system']['pach_to_myhome'] . '/conf/');
		shell_exec('sudo chmod -R 777 ' . $myhome_setts['system']['pach_to_myhome'] . '/conf/');

		// Restart myhome //
		//shell_exec('sudo /etc/init.d/myhome.d start >/dev/null 2>&1 &');
		// Restart ffserver.sh #
		//shell_exec('sudo -u ' . $myhome_user . ' bash "$DIR"myhome/scripts/video/streaming/ffserver.sh --stop');
		//shell_exec('sudo -u ' . $myhome_user . ' bash "$DIR"myhome/scripts/video/streaming/ffserver.sh >/dev/null 2>&1 &');
		$restored[] = 'myhome';
	}

	// Restore etc //
	if (is_dir($archive_dir . 'etc') || is_dir($archive_dir . 'system')) {
		if (is_dir($archive_dir . 'etc')) {
			$arc_dir_etc = $archive_dir . 'etc/';

			$vpn_xl2tpd_file = $arc_dir_etc . "xl2tpd/xl2tpd.conf";
			$vpn_options_file = $arc_dir_etc . "xl2tpd/options.xl2tpd";
			$vpn_secrets_file = $arc_dir_etc . "xl2tpd/chap-secrets";

			$cron_sudo_file = $arc_dir_etc . "cron.sudo.txt";
			$cron_user_file = $arc_dir_etc . "cron.user.txt";
		}
		// v.2 //
		else {
			$arc_dir_etc = $archive_dir . 'system/';

			$vpn_xl2tpd_file = $arc_dir_etc . "xl2tpd.conf";
			$vpn_options_file = $arc_dir_etc . "options.xl2tpd";
			$vpn_secrets_file = $arc_dir_etc . "chap-secrets";

			$cron_sudo_file = $arc_dir_etc . "sudo.cron.tab";
			$cron_user_file = $arc_dir_etc . "user.cron.tab";
		}

		$rest_etc = array();

		// VPN (l2tp) //
		if (is_file($vpn_xl2tpd_file)) {
			shell_exec('sudo rm /etc/xl2tpd/xl2tpd.conf');
			shell_exec('sudo rm /etc/ppp/options.xl2tpd');
			shell_exec('sudo rm /etc/ppp/chap-secrets');
			shell_exec('sudo cp ' . $vpn_xl2tpd_file . ' /etc/xl2tpd/xl2tpd.conf');
			shell_exec('sudo cp ' . $vpn_options_file . ' /etc/ppp/options.xl2tpd');
			shell_exec('sudo cp ' . $vpn_secrets_file . ' /etc/ppp/chap-secrets');

			// Restart vpn //
			//shell_exec('sudo /etc/init.d/xl2tpd restart >/dev/null 2>&1 &');
			$rest_etc[] = 'vpn';
		}
		// VPN (OpenVpn) //
		if (is_dir($arc_dir_etc . "openvpn")) {
			// Restart open_vpn //
			$rest_etc[] = 'open_vpn';
		}

		// Mail //
		if (is_file($arc_dir_etc . "msmtprc")) {
			shell_exec('sudo rm /etc/msmtprc');
			shell_exec('sudo cp ' . $arc_dir_etc . 'msmtprc /etc/msmtprc');
			$rest_etc[] = 'mail';
		}

		// Crontab //
		if (is_file($cron_sudo_file)) {
			shell_exec('sudo crontab ' . $cron_sudo_file);
			$rest_etc[] = 'cron_sudo';
		}
		if (is_file($cron_user_file)) {
			shell_exec('sudo -u ' . $myhome_user . ' crontab ' . $cron_user_file);
			$rest_etc[] = 'cron_user';
		}

		$restored[] = ["etc"=>$rest_etc];
	}

	// Change Myhome Dir //
	//$web_confs_path = $docRoot . '_configs/';
	$myhome_setts_json_tmp = shell_exec("cat ".$docRoot."_configs/myhome.json | tr -d \"\n\" | tr -d \"\t\" 2>&1");
	$myhome_setts_tmp = $system->FromJson($myhome_setts_json_tmp);
	$myhome_setts_tmp['system']['pach_to_myhome'] = $myhome_setts['system']['pach_to_myhome'];
	$file_manager->WriteToFile($docRoot . '_configs/myhome.json', $system->ToJson($myhome_setts_tmp));

	// Read Dir & Return //
	return [
		'archive'=>$file_manager->ScanDir ($archive_dir),
		'restored'=>$restored
	];
}


//								P O S T								//
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	// Post - File //
	if (isset($_FILES['file'])) {
		// Load file //
		$file_loaded = $file_manager->LoadFile('file',$tmp_dir);
		// File is //
		if ($file_loaded['name'] != '') {
			// Type is tar //
			if ($file_loaded['ext'] == 'tar') {
				// Restore archive //
				echo $system->ToJson(RestoreArchive($file_loaded['path']));
				//echo "ok";
			}
			// Type is not tar //
			else {
				// Remove loaded file //
				$file_manager->RemoveFile($file_loaded['pach']);
				echo "error: type must be 'tar'";
			}
		}
		// No file //
		else {
			echo "error: no file";
		}
		exit();
	}

	// Post - Action //
	else if (isset($_POST['action'])) {
		$post_action=$_POST['action'];
		$post_data=$_POST['data'];

		// Make archive //
		if ($post_action == "make_archive") {
			MakeArchive ();
			echo "ok";
		}

		exit();
	}
}
?>
<?php
//								H T M L								//
$title.=" - Бэкап настроек";
$system->InitJava();
include($docRoot.$template."header.php");
//   File Manager   //
$file_manager->InitJava();
?>


<div class="info_block">
	<div class="info_block_name">Загрузка настроек</div>
	<center>
		<div id="File_Ready" style="">подготовка..</div>
	</center>
</div>

<div class="info_block">
	<div class="info_block_name">Восстановление настроек</div>
	<center>
		<table><tr>
			<td width="50%"><input type="file" id="file-select" name="file" style="width:100%;"></td>
			<td align="right"><input type="button" value="Загрузить" onclick="SendFile();" /></td>
		</tr></table>
	</center>
</div>

<div class="info_block" id="Archive_Restored_Holder" style="display: none;">
	<div class="info_block_name">Восстановлено</div>
	<center>
		<div id="Archive_Restored" style="">восстановление..</div>
	</center>
</div>

<?php
//								Footer								//
include($docRoot.$template."footer.php");
?>



<?php echo '<script>var htmlRoot = ' . $htmlRoot . ';</script>'; ?>
<script>
// Make archive //
document.addEventListener("DOMContentLoaded", function() {
	setTimeout(function() {SendPost ('make_archive','');}, 100);
});

// Send File //
function SendFile () {
	if (document.getElementById('file-select').files[0]) {
		Archive_Restored_Holder.style.display = 'block';
		Archive_Restored.innerHTML = "восстановление..";
	}
	file_manager.SendFile('file-select');
}

// Archive Restored //
function ArchiveRestored (data) {
	if (data[0] == '{') {
		data = FromJson(data);
		var html = "Содержание архива:<br>" + ToJson(data['archive']);
		html += "<hr>Восстановлено:<br>" + ToJson(data['restored']);
		Archive_Restored.innerHTML = html;
	}
	else Archive_Restored.innerHTML = data;
}

// Post returned //
function PostReturned (servAnswer,send_action,send_data) {
	// Make archive //
	if (send_action == 'make_archive') {
		// Archive ready ok //
		if (servAnswer == 'ok') {
			var button = '<input type="button" value="Скачать" onclick="window.location.href = \'' +
				htmlRoot + 'tmp/cyber_myhome_conf.tar\';" />';
			File_Ready.innerHTML = button;
		}
	}
	// Send File (Archive restored) //
	else if (send_action == 'send_file_post') {
		ArchiveRestored (servAnswer);
	}
	else alert(send_action);
}
</script>
