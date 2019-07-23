<?php
include("../../_configs/system.ini");
include($docRoot."_modules/system/system.php");
// $docRoot, $htmlRoot, $template, $auth_enabled //
//     System Monitor     //
include($docRoot."_modules/system_monitor/system_monitor.php");
?>
<?php
//								F U N C T I O N S								//
function ScanIps ($net, $range, $time) {
	if (is_file('/tmp/ips.txt')) shell_exec('rm /tmp/ips.txt');
	if (is_file('/tmp/ping_pids.txt')) shell_exec('rm /tmp/ping_pids.txt');

	// Scaning.. //
	$rangeStart=explode('-', $range)[0];
	$rangeEnd=explode('-', $range)[1];
	for ($x=$rangeStart; $x<=$rangeEnd; $x++) {
		exec('ping -c1 -W'.$time.' '.$net.'.'.$x.' >> /tmp/ips.txt 2>/dev/null &');
	}
	sleep($time);

	$ips=explode("\n", shell_exec('cat /tmp/ips.txt | grep time='));
	if ($ips[count($ips)-1] == '') unset($ips[count($ips)-1]);

	//shell_exec('rm /tmp/ips.txt');
	//shell_exec('rm /tmp/ping_pids.txt');
	return $ips;
}
function ScanPorts ($ips, $ports) {
	$ports_opened=[];
	if (count($ips) > 0 && $ports != ''){
		$port=explode(',', $ports);
		for ($p=0; $p<count($port); $p++) {
			shell_exec('echo "Port Port Port:'.$port[$p].'" >> /tmp/ports.txt');
			for ($x=0; $x<count($ips); $x++) {
				// Scan ports //
				if ($ports!=''){
					$comm="$(myhome -sl 500; echo 'quit') | telnet ".$ips[$x]['ip']." ".$port[$p]." 2>/dev/null >>/tmp/ports.txt & echo $! >> /tmp/pids.txt";
					exec($comm);
				}
			}
			sleep(1);
			// Kill Pids //
			shell_exec("pids=`cat /tmp/pids.txt | tr '\n' ' '` ".'&& sudo kill -KILL $pids');
			shell_exec('rm /tmp/pids.txt');
		}
		// Echo ports //
		$ports_opened=explode("\n", shell_exec('cat /tmp/ports.txt | grep "Connected\|Port" | awk \'{print $3}\''));
		if ($ports_opened[count($ports_opened)-1] == '') unset($ports_opened[count($ports_opened)-1]);
		shell_exec('rm /tmp/ports.txt');
	}
	return $ports_opened;
}
//								P O S T								//
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$post_action=$_POST['action'];
	$post_data=$_POST['data'];

	if ($post_action == 'scan_ips') {
		$data = $system->FromJson($post_data);
		// Scaning IP Range //
		$ips_online = ScanIps ($data['net'], $data['range'], $data['time']);
		// Parsing from ping string //
		$ips = [];
		for ($x=0; $x<count($ips_online); $x++) {
			$ip_arr = explode(" ", $ips_online[$x]);
			$ips[$x]['ip'] = explode(":", $ip_arr[3])[0];
			$ips[$x]['time'] = explode("=", $ip_arr[6])[1];
		}

		// Scan ports //
		//$ports = $data['ports'];
		//$ports_opened=ScanPorts($ips, $ports);

		// Return //
		echo $system->ToJson(['ips'=>$ips,'ports'=>[]]);
	}
	else if ($post_action == 'scan_ports') {
		$data = $system->FromJson($post_data);
		// Scan ports //
		$ports_opened=ScanPorts($data['ips'], $data['ports']);
		// Return //
		echo $system->ToJson(['ips'=>$data['ips'], 'ports'=>$ports_opened]);
	}
	exit;
}
?>
<?php
//								G E T								//
if ($_GET['net']!=''){
	$net=$_GET['net'];
	$range=$_GET['range'];
	$ports=$_GET['ports'];
	$time=(int)$_GET['time'];

	// Scaning IP Range //
	$ips_online = ScanIps ($net, $range, $time);

	echo $system->ToJson(['ips'=>$ips_online,'ports'=>$ports_opened]);
	exit;
}
?>
<?php
//								H T M L								//
$title.=" - Настройки";
//$template='_templates/1/';
$system->InitJava();
include($docRoot.$template."header.php");
?>


<center>


<div class="info_block">
<div class="info_block_name">Доступные сети</div>
	<center>
	<?php
		$ipScan=$system_monitor->Ips();
		echo nl2br($ipScan);
	?>
	</center>
</div>


<div class="info_block">
<div class="info_block_name">Диапазон сканирования</div>
	<center>
	<table width="100%" border="0">
		<tr>
			<td width="50%">Сеть</td>
			<td><input type="text" id="net" value="192.168.0"></td>
		</tr>
		<tr>
			<td>Диапазон</td>
			<td><input type="text" id="range" value="1-254"></td>
		</tr>
		<tr>
			<td>Таймаут (сек)</td>
			<td><input type="text" id="time" value="1"></td>
		</tr>
		<tr>
			<td>Порты</td>
			<td><input type="text" id="ports" value="80,554"></td>
		</tr>
	</table>
	<hr style="margin:0;">
	<input type="button" value="Сканировать" onclick="Scan();">
	</center>
</div>
<script>function OnEnter() {Scan();}</script>


<div class="info_block" id="scaning_block" style="display: none; -webkit-user-select: text;">
<div class="info_block_name">Сканирование IP</div>
	<center>
	<div id="scaning"></div>
	</center>
</div>




</center>
<?php
//								Footer								//
include($docRoot.$template."footer.php");
?>

<script>
function Scan () {
	var net = document.getElementById('net').value;
	var range = document.getElementById('range').value;
	var time = parseInt(document.getElementById('time').value);
	var ports = document.getElementById('ports').value;

	document.getElementById('scaning_block').style.display='block';
	document.getElementById('scaning').innerHTML='Сканируем: '+net+'.'+range;
	if (ports!='') document.getElementById('scaning').innerHTML+=' <font color="green">:'+ports+'</font>';

	var query = {'net':net,'range':range,'time':time,'ports':ports};
	//alert(ToJson(query));
	SendPost ('scan_ips',ToJson(query),true);
}
var ips = [];
function PostReturned (servAnswer,send_action,send_data) {
	console.log(servAnswer);
	var ip_data = FromJson(servAnswer);

	if (send_action == 'scan_ips') {
		var ports = document.getElementById('ports').value;
		ports_html = '';
		if (ports != '') ports_html = 'Сканирование..';

		ips = [];
		for (x=0; x<ip_data['ips'].length; x++) {
			ips.push({'ip':ip_data['ips'][x]['ip'],'time':ip_data['ips'][x]['time'],'ports':[ports_html]});
		}

		var query = {'ips':ip_data['ips'],'ports':ports};
		if (ports != '' && ip_data['ips'].length > 0) SendPost ('scan_ports',ToJson(query),true);
	}

	else if (send_action == 'scan_ports') {
		var port = 0;

		ips = [];
		for (x=0; x<ip_data['ips'].length; x++) {
			ips.push({'ip':ip_data['ips'][x]['ip'],'time':ip_data['ips'][x]['time'],'ports':[]});
		}

		for (x=0; x<ip_data['ports'].length; x++) {
			if (ip_data['ports'][x][0] == 'P') {
				port = ip_data['ports'][x].split(':')[1];
			}
			else {
				for (y=0; y<ips.length; y++) {
					if (ips[y]['ip'] == ip_data['ports'][x].substr(0, ip_data['ports'][x].length-1)) {
						ips[y]['ports'].push(port);
						break;
					}
				}
			}
		}
	}

	// Reaturn //
	var html = '<table><th width="40%">Адрес</th><th width="20%">Отклик</th><th>Порты</th>';
	for (x=0; x<ips.length; x++) {
		html += '<tr><td>';
		html += ips[x]['ip'] + '</td><td align="center">' + ips[x]['time'] + ' ms</td><td>';
		for (y=0; y<ips[x]['ports'].length; y++) {
			html += DrawPort(ips[x]['ip'], ips[x]['ports'][y]);
			if (y<ips[x]['ports'].length-1) html += ", ";
		}
		html += '</td></tr>';
	}
	html += '</table>';

	html += '<hr style="margin:0;">Всего адресов: ' + ips.length;
	var return_div = document.getElementById('scaning');
	return_div.innerHTML = html;
}
function DrawPort (ip, port) {
	var html = '';
	if (port == "80") {
		html += '<a href="http://' + ip + '/" target="_blank"><font color="green">' + port + '</font></a>';
	}
	else if (port == "554") {
		html += '<a href="scan_stream.php?ip=' + ip + '"><font color="green">' + port + '</font></a>';
	}
	else {
		html += '<font color="blue">' + port + '</font>';
	}
	return html;
}
</script>
