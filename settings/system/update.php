<?php
include("../../_configs/system.ini");
include($docRoot."_modules/system/system.php");
// $docRoot, $htmlRoot, $template, $auth_enabled //
//   Myhome   //
include($docRoot."_modules/myhome/myhome.php");
?>
<?php
//								F U N C T I O N S								//
function GetVersions_Installed () {
	global $myhome_setts, $docRoot;

	$web_installed = shell_exec('cat ' . $docRoot . 'versions.txt | head -n1');
	$myhome_installed = shell_exec("/bin/myhome -v | awk -F ':' '{print $2}'");
	$scripts_installed = shell_exec('cat ' . $myhome_setts['system']['pach_to_myhome'] . '/scripts/versions.txt | head -n1');

	return ([
		'web_installed'=>$web_installed,
		'myhome_installed'=>$myhome_installed,
		'scripts_installed'=>$scripts_installed
	]);
}
$source = "ftp://pub.cyber-light.ru/linux/";
$source_web = "ftp://pub.cyber-light.ru/myhome/";
function GetVersions_Latest () {
	global $source, $source_web;

	$web_latest = shell_exec('curl ' . $source_web . 'web/php/versions.txt');
	$myhome_latest = shell_exec('curl ' . $source . 'source/versions.txt');
	$scripts_latest = shell_exec('curl ' . $source . 'scripts/versions.txt');

	return ([
		'source'=>$source,
		'web_latest'=>$web_latest,
		'myhome_latest'=>$myhome_latest,
		'scripts_latest'=>$scripts_latest,
	]);
}

//								P O S T								//
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$post_action=$_POST['action'];
	$post_data=$_POST['data'];
	// Get versions //
	if ($post_action == 'get_versions_installed') {
		echo $system->ToJson(GetVersions_Installed());
	}
	if ($post_action == 'get_versions_latest') {
		echo $system->ToJson(GetVersions_Latest());
	}

	// Update Web //
	if ($post_action == 'update_web') {
		if (is_file($myhome_setts['system']['pach_to_myhome'] . '/../install_myhome_php.sh')){
			shell_exec('sudo rm ' . $myhome_setts['system']['pach_to_myhome'] . '/../install_myhome_php.sh');
		}
		$command = 'cd ' . $myhome_setts['system']['pach_to_myhome'] . '/../';
		$command .= ' && sudo wget "' . $source_web . 'install_myhome_php.sh"';
		$command .= ' && sudo chmod +x install_myhome_php.sh';

		$command .= ' && sudo bash install_myhome_php.sh update';
		echo shell_exec($command);
	}

	// Update Myhome //
	if ($post_action == 'update_myhome' || $post_action == 'update_scripts') {
		if (is_file($myhome_setts['system']['pach_to_myhome'] . '/../myhome_install.sh')){
			shell_exec('sudo rm ' . $myhome_setts['system']['pach_to_myhome'] . '/../myhome_install.sh');
		}
		$command = 'cd ' . $myhome_setts['system']['pach_to_myhome'] . '/../';
		$command .= ' && sudo wget "' . $source . 'myhome_install.sh"';
		$command .= ' && sudo chmod +x myhome_install.sh';

		if ($post_action == 'update_myhome') $command .= ' && sudo bash myhome_install.sh update';
		else if ($post_action == 'update_scripts') $command .= ' && sudo bash myhome_install.sh update_scripts';
		echo shell_exec($command);
	}

	exit();
}
?>
<?php
//								H T M L								//
$title.=" - Обновление программы";
$system->InitJava();
include($docRoot.$template."header.php");
?>





<div class="info_block">
	<div class="info_block_name">Веб интерфейс</div>
	<center>
		<table>
			<tr>
				<td width="50%">Установлено</td>
				<td><div id="web_installed"></div></td>
			</tr>
			<tr>
				<td>Доступно</td>
				<td><div id="web_latest"></div></td>
			</tr>
		</table>
		<hr><input type="button" value="Обновить веб интерфейс" onclick="Update('web')" />
	</center>
</div>

<div class="info_block">
	<div class="info_block_name">Служба "Мой Дом"</div>
	<center>
		<table>
			<tr>
				<td width="50%">Установлено</td>
				<td><div id="myhome_installed"></div></td>
			</tr>
			<tr>
				<td>Доступно</td>
				<td><div id="myhome_latest"></div></td>
			</tr>
		</table>
		<hr><input type="button" value='Обновить службу "Мой дом"' onclick="Update('myhome')" />
	</center>
</div>

<div class="info_block">
	<div class="info_block_name">Скрипты</div>
	<center>
		<table>
			<tr>
				<td width="50%">Установлено</td>
				<td><div id="scripts_installed"></div></td>
			</tr>
			<tr>
				<td>Доступно</td>
				<td><div id="scripts_latest"></div></td>
			</tr>
		</table>
		<hr><input type="button" value="Обновить скрипты" onclick="Update('scripts')" />
	</center>
</div>






<?php
//								Footer								//
include($docRoot.$template."footer.php");
?>

<script>
document.addEventListener("DOMContentLoaded", function() {
	setTimeout(function () {
		SendPost ('get_versions_installed','');
		setTimeout(function () { SendPost ('get_versions_latest',''); }, 100);
	}, 10);
});

var Update = function (name) {
	var sys_name = '';
	if (name == 'web') sys_name = 'Веб - интерфейс';
	else if (name == 'myhome') sys_name = 'Службу "Мой дом"';
	else if (name == 'scripts') sys_name = 'Скрипты';

	if (confirm('Обновить ' + sys_name + '?'))
		SendPost ('update_' + name,'');
}

function PostReturned (serv_answer,send_action,send_data) {
	console.log(serv_answer);
	if (send_action == 'get_versions_installed') {
		var vers = FromJson(serv_answer);
		document.getElementById("web_installed").innerHTML = vers['web_installed'];
		document.getElementById("myhome_installed").innerHTML = vers['myhome_installed'];
		document.getElementById("scripts_installed").innerHTML = vers['scripts_installed'];
	}
	else if (send_action == 'get_versions_latest') {
		var vers = FromJson(serv_answer);
		document.getElementById("web_latest").innerHTML = n2br(vers['web_latest'].split("\n")[0]);
		document.getElementById("myhome_latest").innerHTML = n2br(vers['myhome_latest'].split("\n")[0]);
		document.getElementById("scripts_latest").innerHTML = n2br(vers['scripts_latest'].split("\n")[0]);
	}

	else if (send_action == 'update_web') {
		alert(serv_answer);
	}
	else if (send_action == 'update_myhome' || send_action == 'update_scripts') {
		alert(serv_answer);
	}
}
</script>
