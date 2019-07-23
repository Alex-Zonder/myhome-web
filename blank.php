<?php
//								P H P - I N F O								//
if (isset($_GET["action"]) && $_GET["action"]=='phpinfo') {phpinfo();exit();}
?>
<?php 
include("_configs/system.ini");
include($docRoot."_modules/system/system.php");
// $docRoot, $htmlRoot, $template, $auth_enabled //
?>
<?php
//								P O S T								//
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$post_action=$_POST['action'];
	$post_data=$_POST['data'];
}
?>
<?php
//								H T M L								//
$title.=" - Файлы";
//$template='_templates/1/';
$system->InitJava();
include($docRoot.$template."header.php");
?>
<center>



<!--____________________________   I N F O   ____________________________-->
<div class="info_block">
	<div class="info_block_name">Инфо</div>
	<center>
		<b>Сеть</b><br>
		<?php
			echo nl2br(shell_exec('ifconfig | grep "10.\|192." | grep inet'));
			echo "<hr>htmlRoot: ".$htmlRoot."<br>DocRoot: ".$docRoot."<hr>";
		?>
		<script>document.write("Ваше устойство: "+userOs);</script>
	</center>
</div>



<!--____________________________   P H P - I N F O   ____________________________-->
<div class="info_block">
	<div class="info_block_name">Дополнительно</div>
	<center>
		<a href="?action=phpinfo">PHP Info</a>
	</center>
</div>


</center>
<?php
//								Footer								//
include($docRoot.$template."footer.php");
?>

