<?php
if (isset($GLOBALS["system_OS"])) $system_OS = $GLOBALS["system_OS"];
else $system_OS=exec("uname");

class SystemMonitor {
	//			I N I T			//
	function InitJava () {
		global $htmlRoot;
		?>
		<script src="<?php echo $htmlRoot; ?>_modules/system_monitor/system_monitor.js"></script>
		<?php
	}


	function Ips () {
		global $system_OS;
		//   Freebsd  / Darwin  //
		if ($GLOBALS['system_OS']=="FreeBSD" || $GLOBALS['system_OS']=="Darwin")
			$ipScan=shell_exec('ifconfig | grep "inet " | grep -v "127.0."');
		//   Linux   //
		else
			$ipScan=shell_exec('ip addr show | grep "inet " | grep -v "127.0."');
		return $ipScan;
	}

	function Uptime () {
		global $system_OS;
		$uptime=shell_exec("uptime | awk -F 'up' '{ print $2 }' | awk -F 'user' '{ print $1 }' | awk -F ',' '{ for (i = 1; i < NF; i++) print ".'$i'." }'");
		$uptime=str_replace("\n", "", $uptime);
		$users=exec("uptime | awk -F 'user' '{ print $1 }' | awk -F ".'","'." '{ print ".'$NF'." }'");
		$averages=exec("uptime | awk -F 'averages:' '{ print $2 }'");

		return ['system_OS'=>$system_OS,'uptime'=>$uptime,'users'=>$users,'averages'=>$averages];
	}

	function Ram () {
		global $system_OS;
		//   Linux   //
		if ($system_OS == "Linux") {
			$total=exec("free -h | grep Mem | awk '{print $2}'");
			$used=exec("free -h | grep Mem | awk '{print $3}'");
			$free=exec("free -h | grep Mem | awk '{print $4}'");
			return ['total'=>$total/1024/1024,'used'=>$used/1024/1024,'free'=>$free/1024/1024];
		}
		//   Freebsd   //
		else if ($system_OS == "FreeBSD") {
			$phys=exec("/sbin/sysctl hw | grep 'hw.phys' | awk '{print $2}'");
			$user=exec("/sbin/sysctl hw | grep 'hw.user' | awk '{print $2}'");
			$real=exec("/sbin/sysctl hw | grep 'hw.real' | awk '{print $2}'");
			return ['phys'=>$phys/1024/1024,"user"=>$user/1024/1024,"real"=>$real/1024/1024];
		}
		//   Mac OS   //
		else if ($system_OS == "Darwin") {
			$exec_command="sysctl hw | egrep 'hw.(memsize)' | awk '{print $2}'";
			$memsize=exec($exec_command);

			return ['memsize'=>$memsize/1024/1024];
		}
	}

	/*function Hdd () {
		global $system_OS, $system_ifaces;
		$grep='/dev/ro\|/dev/sd\|/dev/ad\|/dev/disk';
		$du=shell_exec('df -m | grep \''.$grep.'\'');
		$disks=explode("\n", $du);

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
		}
	}*/

	function CpuLoad () {
		//   FreeBSD   //
		if ($GLOBALS['system_OS']=="FreeBSD") {
			$upTime=shell_exec('top -d2 | grep "CPU:"');
			$loads=preg_split('/ /', $upTime, -1, PREG_SPLIT_NO_EMPTY);
			$load=strval(doubleval($loads[1])+doubleval($loads[3])+doubleval($loads[5])+doubleval($loads[7]));
			$free=doubleval($loads[9]);
		}
		//   Darwin   //
		else if ($GLOBALS['system_OS']=="Darwin") {
			$upTime=shell_exec('top -l2 | grep "CPU" | grep "usage" | tail -n1');
			$loads=preg_split('/ /', $upTime, -1, PREG_SPLIT_NO_EMPTY);
			$load=strval(doubleval($loads[2])+doubleval($loads[4]));
			$free=doubleval($loads[6]);
		}
		//   Linux   //
		else {
			$upTime=shell_exec('top -bn2 | grep \'%Cpu(s):\' | tail -n1');
			$loads=preg_split('/ /', $upTime, -1, PREG_SPLIT_NO_EMPTY);
			$load=strval(doubleval($loads[1])+doubleval($loads[3])+doubleval($loads[5])+doubleval($loads[9]));
			$free=doubleval($loads[7]);
		}
		return ['load'=>$load,'free'=>$free];
	}

	function NetLoad () {
		global $system_OS, $system_ifaces;
		//   FreeBSD   //
		if ($GLOBALS['system_OS']=="FreeBSD") {
			$comm="netstat -I ".$system_ifaces[0]." -w1 -q1 | tail -n1 | awk '{ print $4\" \"$7 }'";
			$res=shell_exec($comm);
			$nLoad=explode(" ",$res);

			$bites_in=number_format((intval($nLoad[0])/1024/1024)*8,2,',','');
			$biyes_out=number_format((intval($nLoad[1])/1024/1024)*8,2,',','');
		}
		//   Darwin   //
		else if ($GLOBALS['system_OS']=="Darwin") {
			$comm="netstat -I ".$system_ifaces[0]." -b | tail -n1 | awk '{ print $7\" \"$10 }'";
			$res=shell_exec($comm);
			$R1=explode(" ", $res)[0];
			$T1=explode(" ", $res)[1];
			sleep(1);

			$comm="netstat -I ".$system_ifaces[0]." -b | tail -n1 | awk '{ print $7\" \"$10 }'";
			$res=shell_exec($comm);
			$R2=explode(" ", $res)[0];
			$T2=explode(" ", $res)[1];

			$bites_in=number_format((intval($R2-$R1)/1024/1024)*8,2,',','');
			$biyes_out=number_format((intval($T2-$T1)/1024/1024)*8,2,',','');
		}
		//   Linux   //
		else {
			$R1=exec("cat /sys/class/net/".$system_ifaces[0]."/statistics/rx_bytes");
			$T1=exec("cat /sys/class/net/".$system_ifaces[0]."/statistics/tx_bytes");

			sleep(1);
			$R2=exec("cat /sys/class/net/".$system_ifaces[0]."/statistics/rx_bytes");
			$T2=exec("cat /sys/class/net/".$system_ifaces[0]."/statistics/tx_bytes");

			$bites_in=number_format((intval($R2-$R1)/1024/1024)*8,2,',','');
			$biyes_out=number_format((intval($T2-$T1)/1024/1024)*8,2,',','');
		}
		return ['iface'=>$system_ifaces[0],'in'=>$bites_in,'out'=>$biyes_out];
	}






	//_______________________ Drawing _______________________//
	function DrawSystemBlocks () {
		?>
		<div class="info_block">
		<div class="info_block_name">OS & Up Time & Averages</div>
		<div class="info_block_info">
			<center><div id="sys_info">
			</div></center>
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


		<?php
	}

}
$system_monitor=new SystemMonitor;
?>
