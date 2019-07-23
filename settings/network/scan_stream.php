<?php
include("../../_configs/system.ini");
include($docRoot."_modules/system/system.php");
// $docRoot, $htmlRoot, $template, $auth_enabled //
?>
<?php
//								H T M L								//
$title.=" - Настройки";
//$template='_templates/1/';
$system->InitJava();
include($docRoot.$template."header.php");
?>

<?php
$ip='192.168.';
$port='554';
$name='admin';
$pass='';
$channel='1';
$subtype='0';
if ($_GET['ip']!='') $ip=$_GET['ip'];
if ($_GET['port']!='') $port=$_GET['port'];
if ($_GET['name']!='') $name=$_GET['name'];
if ($_GET['pass']!='') $pass=$_GET['pass'];
if ($_GET['channel']!='') $channel=$_GET['channel'];
if ($_GET['subtype']!='') $subtype=$_GET['subtype'];
?>
<center>




<div class="info_block">
<div class="info_block_name">Сканер потоков</div>
<div class="info_block_info" style="-webkit-user-select: text;">
	<center>
	<form style="margin-bottom: 0px;">
		<table border="0" width="100%">
		<tr><td width="50%">IP:</td><td><input type="text" name="ip" value=<?php echo '"'.$ip.'"';?>></td></tr>
		<tr><td>Порт:</td><td><input type="text" name="port" value=<?php echo '"'.$port.'"';?>></td></tr>
		<tr><td>Имя:</td><td><input type="text" name="name" value=<?php echo '"'.$name.'"';?>></td></tr>
		<tr><td>Пароль:</td><td><input type="text" name="pass" value=<?php echo '"'.$pass.'"';?>></td></tr>
		<tr><td>Канал:</td><td><input type="text" name="channel" value=<?php echo '"'.$channel.'"';?>></td></tr>
		<tr><td>Поток:</td><td><input type="text" name="subtype" value=<?php echo '"'.$subtype.'"';?>></td></tr>
		</table>
		<hr><input type="submit" value="Сканировать" style="margin: 3px 0px 3px 0px;">
	</form>
	</center>
</div></div>


<?php
function TestStreamToJpg ($stream) {
	$testCamComm='sudo ffmpeg -i \''.$stream.'\' -ss 00:00:00.010 -f image2 -vframes 1 -y '.$docRoot.'out.jpg 2>&1';
	$testCam=shell_exec($testCamComm);
	echo '<img src="'.$docRoot.'out.jpg" width="90%">';
}

function TestStream ($stream) {
	$streamsDetected=0;
	echo 'Testing: '.$stream.' ';
	$testCamComm='ffmpeg -i \''.$stream.'\' 2>&1';
	$testCam=shell_exec($testCamComm);
	$testStrings=explode("\n", $testCam);

	for ($x=0; $x<count($testStrings); $x++) {
		$words=explode(' ', $testStrings[$x]);
		if ($words[4]=='Stream') {
			echo '<br>'.$testStrings[$x];
			$streamsDetected++;
		}
	}

	if ($streamsDetected>0) {
		echo '<br><font color="green">OK</font><hr>';
		TestStreamToJpg ($stream);
	} else {
		echo '<font color="red">ERROR</font><hr>';
	}
	return $streamsDetected;
}

if ($_GET['port']!=''){
	?>
	<div class="info_block">
	<div class="info_block_name">Сканируем</div>
	<div class="info_block_info" style="-webkit-user-select: text;">
		<center>
		<?php
		//   Testing by PING //
		$ping=shell_exec('ping -c1 -W1 '.$_GET['ip'].' 2>/dev/null | grep time=');

		if ($ping=='') {
			echo 'Ping: <font color="red">Error !!!</font><hr>';
		}

		else {
			echo 'Ping: '.$ping.' <font color="green">OK</font><hr>';

			//   Scan streams //
			$ok=0;
			$ok=TestStream ('rtsp://'.$_GET['ip'].':'.$_GET['port'].'/live'.$subtype.'.264');
			if ($ok==0) $ok=TestStream ('rtsp://'.$_GET['name'].':'.$_GET['pass'].'@'.$_GET['ip'].':'.$_GET['port'].'/live'.$subtype.'.264?user='.$_GET['name'].'&passwd='.$_GET['pass']);

			if ($ok==0) $ok=TestStream ('rtsp://'.$_GET['ip'].':'.$_GET['port'].'/user='.$_GET['name'].'&password='.$_GET['pass'].'&channel='.$channel.'&stream='.$subtype.'.sdp');
			if ($ok==0) $ok=TestStream ('rtsp://'.$_GET['name'].':'.$_GET['pass'].'@'.$_GET['ip'].':'.$_GET['port'].'/cam/realmonitor?channel='.$channel.'&subtype='.$subtype.' '.$_GET['name'].' '.$_GET['pass']);

			if ($ok==0) $ok=TestStream ('rtsp://'.$_GET['name'].':'.$_GET['pass'].'@'.$_GET['ip'].':'.$_GET['port'].'/video/play'.$subtype.'.sdp');
		}
		?>
		</center>
	</div></div>
	<?php
}
?>



</center>
<?php
//								Footer								//
include($docRoot.$template."footer.php");
?>
