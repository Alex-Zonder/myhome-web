<?php
function JsStrToArr ($data) {
	$data = str_replace("\n", '', $data);
	$data = str_replace("'", '', $data);
	$data = str_replace('];', '', $data);
	$data_arr = explode("],", $data);

	$data_arr_arr = array();
	for ($x=0; $x < count($data_arr); $x++) {
		$data_arr[$x] = str_replace('[', '', $data_arr[$x]);
		$data_arr[$x] = str_replace(']', '', $data_arr[$x]);

		$data_arr_arr[$x] = explode(",", $data_arr[$x]);
	}

	return $data_arr_arr;
}


function ConvertSetts () {
	global $docRoot, $tmp_dir, $archive_dir, $file_manager, $system, $myhome_setts, $myhome_setts_tmp;

	$web_confs_path = $docRoot . '_configs/';
	$web_confs_path_v2 = $docRoot . '_configs/v2/';

	$myhome_setts_tmp = [
		"system" => [
			"pach_to_myhome"=>""
		],
		"main" => [
			"system_name"=>"Кибер.Дом",
			"view_console"=>false,
			"first_group"=>0
		],
		"myhome"=>[
			"host"=>"",
			"port"=>"1001",
			"commands_timeout"=>"60",
			"connection_timeout"=>"2000",
			"waiting_timeout"=>"600000"
		],
		"groups"=>array(),
		"devices"=>array()
	];


	//     System     //
	$myhome_setts_tmp["system"]["pach_to_myhome"] = explode("\n", $file_manager->ReadFile($web_confs_path_v2 . 'main.ini'))[0];
	$myhome_setts_tmp["system"]["pach_to_myhome"] = explode("'", $myhome_setts_tmp["system"]["pach_to_myhome"])[1] . "myhome";


	//     Main     //
	$user_ini_file = $file_manager->ReadFile($web_confs_path_v2 . 'user.ini');

	$system_name = explode("\n", $user_ini_file)[0];
	$myhome_setts_tmp["main"]["system_name"] = explode("'", $system_name)[1];

	$first_group = explode("\n", $user_ini_file)[1];
	$first_group = explode("=", $first_group)[1];
	$first_group = str_replace(';', '', $first_group);
	$myhome_setts_tmp["main"]["first_group"] = (int)$first_group;

	$view_console = explode("\n", $user_ini_file)[2];
	$view_console = explode("=", $view_console)[1];
	$view_console = str_replace(';', '', $view_console);
	if ((int)$view_console == 1) $myhome_setts_tmp["main"]["view_console"] = true;
	else $myhome_setts_tmp["main"]["view_console"] = false;


	//     Groups     //
	$groups_tmp = shell_exec("cat " . $web_confs_path_v2 . 'user.ini | grep "groups="');
	$groups_tmp = str_replace("var groups=[", '', $groups_tmp);
	$groups_tmp_arr = JsStrToArr($groups_tmp);
	for ($x=0; $x < count($groups_tmp_arr); $x++) {
		$myhome_setts_tmp["groups"][$x]['group_id'] = (int)$groups_tmp_arr[$x][0];
		$myhome_setts_tmp["groups"][$x]["enabled"] = true;
		$myhome_setts_tmp["groups"][$x]["rights"] = "";
		$myhome_setts_tmp["groups"][$x]["name"] = $groups_tmp_arr[$x][1];

		for ($y=2; $y<count($groups_tmp_arr[$x]); $y++)
			$myhome_setts_tmp["groups"][$x]["devs_id"][] = (int)$groups_tmp_arr[$x][$y];
	}


	//     Devices     //
	$devices_tmp = shell_exec("cat " . $web_confs_path_v2 . 'user.ini | grep "devices="');
	$devices_tmp = str_replace("var devices=[", '', $devices_tmp);
	$devices_tmp_arr = JsStrToArr($devices_tmp);
	for ($x=0; $x < count($devices_tmp_arr); $x++) {
		$myhome_setts_tmp["devices"][$x]["dev_id"] = (int)$devices_tmp_arr[$x][0];
		$myhome_setts_tmp["devices"][$x]["enabled"] = true;
		$myhome_setts_tmp["devices"][$x]["rights"] = "";
		$myhome_setts_tmp["devices"][$x]["name"] = $devices_tmp_arr[$x][1];

		// RM-4 //
		if ($devices_tmp_arr[$x][2] == "RM4") {
			$myhome_setts_tmp["devices"][$x]["type"] = "RM-4";

			$rm_4_tmp = shell_exec("cat " . $web_confs_path_v2 . 'user.ini | grep "RM4="');
			$rm_4_tmp = str_replace("var RM4=[", '', $rm_4_tmp);
			$rm_4_tmp_arr = JsStrToArr($rm_4_tmp);
			for ($y=0; $y < count($rm_4_tmp_arr); $y++) {
				if ((int)$rm_4_tmp_arr[$y][0] == (int)$devices_tmp_arr[$x][3]) {
					$myhome_setts_tmp["devices"][$x]["values"] = [
						"address" => (int)$rm_4_tmp_arr[$y][1],
						"input_view" => (int)$rm_4_tmp_arr[$y][7],
					];
					for ($c=0; $c<4; $c++)
						$myhome_setts_tmp["devices"][$x]["values"]["rel_" . (string)($c + 1)] = $rm_4_tmp_arr[$y][$c + 3];
					for ($c=0; $c<4; $c++)
						$myhome_setts_tmp["devices"][$x]["values"]["inp_" . (string)($c + 1)] = $rm_4_tmp_arr[$y][$c + 8];
				}
			}
		}
		// I-16 //
		else if ($devices_tmp_arr[$x][2] == "I16") {
			$myhome_setts_tmp["devices"][$x]["type"] = "I-16";

			$i_16_tmp = shell_exec("cat " . $web_confs_path_v2 . 'user.ini | grep "I16="');
			$i_16_tmp = str_replace("var I16=[", '', $i_16_tmp);
			$i_16_tmp_arr = JsStrToArr($i_16_tmp);
			for ($y=0; $y < count($i_16_tmp_arr); $y++) {
				if ((int)$i_16_tmp_arr[$y][0] == (int)$devices_tmp_arr[$x][3]) {
					$myhome_setts_tmp["devices"][$x]["values"] = [
						"address" => (int)$i_16_tmp_arr[$y][2],
						"inputs_view" => 16,
						"temp_view" => (int)$i_16_tmp_arr[$y][20],
						"a2d_view" => (int)$i_16_tmp_arr[$y][25],
					];
					for ($c=0; $c<16; $c++)
						$myhome_setts_tmp["devices"][$x]["values"]["inp_" . (string)($c + 1)] = $i_16_tmp_arr[$y][$c + 3];

					if ($myhome_setts_tmp["devices"][$x]["values"]["temp_view"] == 1)
						$myhome_setts_tmp["devices"][$x]["values"]["temp_view"] = 4;
					for ($c=0; $c<4; $c++)
						$myhome_setts_tmp["devices"][$x]["values"]["temp_" . (string)($c + 1)] = $i_16_tmp_arr[$y][$c + 21];

					if ($myhome_setts_tmp["devices"][$x]["values"]["a2d_view"] == 1)
						$myhome_setts_tmp["devices"][$x]["values"]["a2d_view"] = 4;
					for ($c=0; $c<4; $c++)
						$myhome_setts_tmp["devices"][$x]["values"]["a2d_" . (string)($c + 1)] = "АЦП_" . (string)($c + 1);
				}
			}
		}
		// IP_CAM //
		else if ($devices_tmp_arr[$x][2] == "CAM") {
			$myhome_setts_tmp["devices"][$x]["type"] = "IP_CAM";

			$ip_cam = shell_exec("cat " . $web_confs_path_v2 . 'user.ini | grep "cameras="');
			$ip_cam = str_replace("var cameras=[", '', $ip_cam);
			$ip_cam_arr = JsStrToArr($ip_cam);
			for ($y=0; $y < count($ip_cam_arr); $y++) {
				if ((int)$ip_cam_arr[$y][0] == (int)$devices_tmp_arr[$x][3]) {
					$myhome_setts_tmp["devices"][$x]["values"] = [
						"host" => $ip_cam_arr[$y][2],
						"port" => explode("/", $ip_cam_arr[$y][3])[0],
						"link" => explode("/", $ip_cam_arr[$y][3])[1]
					];
				}
			}
		}
	}

	// Write Setts //
	//$file_manager->WriteToFile($web_confs_path . "myhome.v2.json", str_replace('\\', '', $system->ToJson($myhome_setts_tmp)));
	$file_manager->WriteToFile($web_confs_path . "myhome.json", str_replace('\\', '', $system->ToJson($myhome_setts_tmp)));

	// Erase myhome_auto //
	$file_manager->WriteToFile($web_confs_path . "myhome_auto.json", '');
}
?>
