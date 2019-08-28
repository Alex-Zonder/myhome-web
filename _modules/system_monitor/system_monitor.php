<?php
if (isset($GLOBALS["system_OS"])) $system_OS = $GLOBALS["system_OS"];
else $system_OS=exec("uname");

class SystemMonitor {
	//			I N I T		J S			//
	function InitJava () {
		global $htmlRoot;
		?>
		<script src="<?php echo $htmlRoot; ?>_modules/system_monitor/system_monitor.js"></script>
		<?php
	}


	//			Os Version			//
	function OsVersion () {
		global $system_OS;

		// FreeBSD //
		if ($system_OS == "FreeBSD") {
			$command = "uname -a | awk '{ print $3 }'";
			$os_ver_shell = explode('-RELEASE', exec($command));
			$os_name = $system_OS;
			$os_ver = $os_ver_shell[0];
			$os_release = $os_ver_shell[1];
			$os_full = $os_name . ' ' . $os_ver . $os_release;
		}
		// Linux //
		else if ($system_OS == "Linux") {
			$command = 'lsb_release -a 2>/dev/null | grep "ID\|Release" | awk \'{ print $NF }\'';
			$os_ver_shell = explode("\n", shell_exec($command));
			$os_name = $os_ver_shell[0];
			$os_ver = $os_ver_shell[1];
			$os_full = $os_name . " " . $os_ver;
		}
		// Darwin //
		else {
			$os_full = $system_OS;
		}

		// Return //
		return ['os_name' => $os_name, 'os_ver' => $os_ver, 'os_full' => $os_full];
	}


	//			System Info			//
	function SysInfo () {
		global $system_OS;
		$sys_name = exec("uname -a | awk '{ print $2 }'");

		$uptime=shell_exec("uptime | awk -F 'up' '{ print $2 }' | awk -F 'user' '{ print $1 }' | awk -F ',' '{ for (i = 1; i < NF; i++) print ".'$i'." }'");
		$uptime=str_replace("\n", "", $uptime);

		$users=exec("uptime | awk -F 'user' '{ print $1 }' | awk -F ".'","'." '{ print ".'$NF'." }'");
		$averages=exec("uptime | awk -F 'average' '{ print $2 }' | awk '{ print $2\" \"$3\" \"$4 }'");
		$date = exec('date');

		// Return //
		return [
			'system_OS' => $this->OsVersion()['os_full'],
			'uptime' => $uptime,
			'system_name' => $sys_name,
			'users' => $users,
			'averages' => $averages,
			'date' => $date
		];
	}



	//			R A M			//
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

		// Return //
			return ['memsize'=>$memsize/1024/1024];
		}
	}



	//			H D D			//
	function Hdds () {
		global $system_OS, $system_ifaces;
		$grep='/dev/ro\|/dev/sd\|/dev/ad\|/dev/disk';
		$du=shell_exec('df -m | grep \''.$grep.'\'');
		$disks=explode("\n", $du);

		$disk = array();
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

			$disk[$x] = [
				'name' => $data[0],
				'size' => $vol,
				'used' => $zanyato,
				'used_per' => $data[4],
				'free' => $free
			];
		}

		// Return //
		return $disk;
	}



	//			C P U   T Y P E			//
	function CpuType () {
		global $system_OS;

		//   Freebsd  //
		if ($system_OS == "FreeBSD")
			$cpu=shell_exec('/sbin/sysctl hw.model hw.ncpu 2>&1');

		//   Linux   //
		else if ($system_OS == "Linux"){
			$os_name = $this->OsVersion()['os_name'];
			// Raspbian //
			if ($os_name == "Raspbian") {
				$cpu=exec('cat /sys/devices/system/cpu/cpu0/cpufreq/scaling_cur_freq') . 'Hz';
			}
			// Ubuntu //
			else if ($os_name == "Ubuntu") {
				$cpu=shell_exec('lshw | grep -i cpu | grep Hz 2>&1');
			}
		}

		//   Darwin  //
		else if ($system_OS == "Darwin")
			$cpu=shell_exec('sysctl hw.cpufrequency hw.ncpu 2>&1');

		// Return //
		return $cpu;
	}
	//			C P U	L O A D			//
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

		// Return //
		return ['load'=>$load,'free'=>$free];
	}



	//			S Y S T E M   I P S			//
	function Ips () {
		global $system_OS;
		//   Freebsd  //
		if ($system_OS == "FreeBSD")
			$ipScan=shell_exec('/sbin/ifconfig | grep "inet " | grep -v "127.0."');
		//   Darwin  //
		else if ($system_OS == "Darwin")
			$ipScan=shell_exec('ifconfig | grep "inet " | grep -v "127.0."');
		//   Linux   //
		else
			$ipScan=shell_exec('ip addr show | grep "inet " | grep -v "127.0."');

		// Sorting //
		$ipScan = explode("\n", $ipScan);
		$ips = [];
		for($x=0; $x<count($ipScan); $x++) {
			if ($ipScan[$x] != '') {
				$ip_string = explode("inet ", $ipScan[$x])[1];
				$ips[$x]['string'] = $ip_string;

				$ip_arr = explode(" ", $ip_string);
				$ips[$x]['ip'] = $ip_arr[0];

				$netmask_arr = explode("netmask ", $ip_string);
				$netmask_arr = explode(" ", $netmask_arr[1])[0];
				$ips[$x]['netmask'] = $netmask_arr;
			}
		}
		// Return //
		return $ips;
	}


	//			N E T   L O A D			//
	function NetLoad () {
		global $system_OS, $system_ifaces;
		//   FreeBSD   //
		if ($GLOBALS['system_OS']=="FreeBSD") {
			$comm="/usr/bin/netstat -I ".$system_ifaces[0]." -w1 -q1 | tail -n1 | awk '{ print $4\" \"$7 }'";
			$res=exec($comm);
			$nLoad=explode(" ",$res);

			if (isset($nLoad[1])) {
				$bites_in=number_format((intval($nLoad[0])/1024/1024)*8,2,',','');
				$biyes_out=number_format((intval($nLoad[1])/1024/1024)*8,2,',','');
			}
			else {
				sleep(1);
				$bites_in = -1;
				$biyes_out = -1;
			}
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

		// Return //
		return ['iface'=>$system_ifaces[0],'in'=>$bites_in,'out'=>$biyes_out];
	}
}
$system_monitor = new SystemMonitor();
?>
