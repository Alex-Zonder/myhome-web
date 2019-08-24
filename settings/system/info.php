<?php header('Access-Control-Allow-Origin: *'); ?>
<?php
include("../../_configs/system.ini");
include($docRoot."_modules/system/system.php");
// $docRoot, $htmlRoot, $template, $auth_enabled //
//     System Monitor     //
include($docRoot."_modules/system_monitor/system_monitor.php");
?>
<?php
//								R E Q U E S T								//
$request = CheckRequest();
if ($request['type'] != '') {
	// Cpu Load //
	if ($request['action']=='cpu'){
		echo $system->ToJson($system_monitor->CpuLoad());
		exit;
	}
	// Network Load //
	if ($request['action']=="network") {
		echo $system->ToJson($system_monitor->NetLoad());
		exit();
	}

	// System Info //
	if ($request['action']=="sys_info") {
		echo $system->ToJson($system_monitor->SysInfo());
		exit();
	}
	// System Disks //
	if ($request['action']=="disks") {
		echo $system->ToJson($system_monitor->Hdds());
		exit();
	}
	// Cpu_Type //
	if ($request['action']=="cpu_type") {
		$cpu = $system_monitor->CpuType();
		if (!$cpu) $cpu = 'Не определен';
		echo $system->ToJson($cpu);
		exit();
	}
	// Ram //
	if ($request['action']=='ram'){
		echo $system->ToJson($system_monitor->Ram());
		exit;
	}
	// System Ips //
	if ($request['action']=="system_ips") {
		$ipScan=$system_monitor->Ips();
		echo $system->ToJson($ipScan);
		exit();
	}
	// Client Ip //
	if ($request['action']=="client_ip") {
		// Get User Ip //
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		echo $system->ToJson($ip);
		exit();
	}
}
?>
<?php
//     System Monitor     //
$system_monitor->InitJava();
//								H T M L								//
$title.=" - Инфо";
$system->InitJava();
include($docRoot.$template."header.php");
?>
<center>



<div class="info_block">
<div class="info_block_name">OS & Up Time & Averages</div>
<div class="info_block_info">
	<center><div id="sys_info"></div></center>
</div></div>



<div class="info_block" style="width:calc(50% - 3px);float:left;">
<div class="info_block_name">Процессор</div>
<div class="info_block_info">
	<center><div id="cpu">
		Нагрузка: --.- %
		<br>Свободно: --.- %
	</div></center>
</div></div>

<div class="info_block" style="width:calc(50% - 3px);float:left;">
<div class="info_block_name">Сеть</div>
<div class="info_block_info">
	<center><div id="network">
		Вход: -,-- Мб
		<br>Выход: -,-- Мб
	</div></center>
</div></div>

<div style="clear:both;"></div>



<div class="info_block">
<div class="info_block_name">Информация о дисках</div>
<div class="info_block_info">
	<center><div id="disks"></div></center>
</div></div>



<div class="info_block">
<div class="info_block_name">Процессор</div>
	<center><div id="cpu_type"></div></center>
</div>



<div class="info_block">
<div class="info_block_name">Память</div>
<div class="info_block_info">
	<center><div id="ram"></div></center>
</div></div>



<div class="info_block">
<div class="info_block_name">IP Адреса</div>
<div class="info_block_info">
	<center><div id="system_ips"></div></center>
</div></div>



<div class="info_block">
<div class="info_block_name">Клиент</div>
	<center>
		<script>
			document.write('UserAgent: '+navigator.userAgent +
				"<hr>Screen: " + screen.width + "*" + screen.height);
		</script>
	</center>
	<center><hr><div id="client_ip"></div></center>
</div>



</center>
<?php
//								Footer								//
include($docRoot.$template."footer.php");
?>



<script>
// Send Requests //
SendPostAsync ('cpu');
SendPostAsync ('network');

SendPostAsync ('sys_info');
SendPostAsync ('disks');
SendPostAsync ('cpu_type');
SendPostAsync ('ram');
SendPostAsync ('system_ips');
SendPostAsync ('client_ip');


// Post Returned //
function PostReturned(servAnswer,send_action,send_data,url) {
	DrawSystemInfo(servAnswer, send_action);

	if (send_action == 'cpu')
		setTimeout('SendPostAsync (\'cpu\')', 500);
	if (send_action == 'network')
		setTimeout('SendPostAsync (\'network\')', 500);
}
</script>
