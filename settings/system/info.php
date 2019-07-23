<?php header('Access-Control-Allow-Origin: *'); ?>
<?php
include("../../_configs/system.ini");
include($docRoot."_modules/system/system.php");
// $docRoot, $htmlRoot, $template, $auth_enabled //
//     System Monitor     //
include($docRoot."_modules/system_monitor/system_monitor.php");
?>


<?php
if ($_GET['action']=="sys_info" || $_GET['action']=="uptime") {
	echo $system->ToJson($system_monitor->Uptime());
	exit();
}
if ($_GET['action']=='cpu'){
	echo $system->ToJson($system_monitor->CpuLoad());
	exit;
}
if ($_GET['action']=='ram'){
	echo $system->ToJson($system_monitor->Ram());
	exit;
}
if ($_GET['action']=="network") {
	echo $system->ToJson($system_monitor->NetLoad());
	exit();
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

<style type="text/css" media="screen">
	td{text-align: center;}
</style>
<center>


<?php $system_monitor->DrawSystemBlocks(); ?>




<div class="info_block">
<div class="info_block_name">Информация о дисках</div>
<div class="info_block_info">
	<center>
	<?php
	$grep='/dev/ro\|/dev/sd\|/dev/ad\|/dev/disk';
	$du=shell_exec('df -m | grep \''.$grep.'\'');
	$disks=explode("\n", $du);
	?>
	<table border="1" width="90%">
		<tr>
			<th>Диск</th>
			<th>Объем</th>
			<th>Занято</th>
			<th>Свободно</th>
			<th>%</th>
		</tr>
		<?php
		for ($x=0; $x<count($disks)-1; $x++) {
			$data=preg_split('/ /', $disks[$x], -1, PREG_SPLIT_NO_EMPTY);
			$vol=$data[1];
			if ($vol>=1000000) $vol=round(($vol/1000000),2).' Tb';
			else if ($vol>=1000) $vol=round(($vol/1000),2).' Gb';
			else $vol=$vol.' Mb';
			$zanyato=$data[2];
			if ($zanyato>=1000000) $zanyato=round(($zanyato/1000000),2).' Tb';
			else if ($zanyato>=1000) $zanyato=round(($zanyato/1000),2).' Gb';
			else $zanyato=$zanyato.' Mb';
			$free=$data[3];
			if ($free>=1000000) $free=round(($free/1000000),2).' Tb';
			else if ($free>=1000) $free=round(($free/1000),2).' Gb';
			else $free=$free.' Mb';
			?>
			<tr>
				<td><?php echo $data[0]; ?></td>
				<td><?php echo $vol; ?></td>
				<td><?php echo $zanyato; ?></td>
				<td><?php echo $free; ?></td>
				<td><?php echo $data[4]; ?></td>
			</tr>
			<?php
		}
		?>
	</table>

	</center>
</div></div>




<div class="info_block">
<div class="info_block_name">IP Адреса</div>
<div class="info_block_info">
	<center>
		<?php
		$ipScan=$system_monitor->Ips();
		echo nl2br($ipScan);
		?>
	</center>
</div></div>


<div class="info_block">
<div class="info_block_name">Процессор</div>
	<center>
	<?php
		if ($GLOBALS['system_OS']=="FreeBSD") $gpu=nl2br(shell_exec('sysctl hw.cpufrequency hw.model hw.ncpu 2>&1'));
		else if ($GLOBALS['system_OS']=="Darwin") $gpu=nl2br(shell_exec('sysctl hw.cpufrequency hw.ncpu 2>&1'));
		else $gpu=nl2br(shell_exec('lshw | grep -i cpu | grep Hz 2>&1'));

		if (!$gpu) echo 'Не определен';
		else echo $gpu;
	?>
	</center>
</div>


<div class="info_block">
<div class="info_block_name">Память</div>
<div class="info_block_info">
	<center><div id="ram">
	</div></center>
</div></div>


<div class="info_block">
<div class="info_block_name">Клиент</div>
	<center>
		<script>
			document.write('UserAgent: '+navigator.userAgent +
				"<hr>Screen: " + screen.width + "*" + screen.height);
		</script>
	</center>
</div>

</center>
<?php
//								Footer								//
include($docRoot.$template."footer.php");
?>



<script>
function XhrSend (query, id) {
	var xhr = new XMLHttpRequest();
	xhr.open('GET', '?'+query, true);
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4 && xhr.status == 200) {
			//document.getElementById(id).innerHTML=xhr.responseText;
			DrawSystemInfo(xhr.responseText,id);

			if (id=='cpu') GetCpuLoad();
			if (id=='network') GetNetLoad();
		}
	}
	xhr.send();
}

function GetSysInfo () {
	XhrSend ('action=sys_info','sys_info');
}
GetSysInfo();

function GetRamInfo () {
	XhrSend ('action=ram','ram');
}
GetRamInfo();

function GetCpuLoad () {
	XhrSend ('action=cpu','cpu');
}
GetCpuLoad();

function GetNetLoad () {
	XhrSend ('action=network','network');
}
GetNetLoad();
</script>
