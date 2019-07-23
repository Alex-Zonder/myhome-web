<?php
$pidof='pidof';
if (exec('uname')=='Darwin') $pidof='/usr/local/bin/pidof';
$myhome_pid=shell_exec($pidof.' myhome');
if (!$myhome_pid) {
	?>
	<div class="info_block">
		<div class="info_block_name">Внимание</div>
		<div class="info_block_info"><center>
			<?php
				echo '<font color="red">Мой дом не запущен.</font>';
			?>
		</center></div>
	</div>
	<?php
}
?>
