<?php
include("../../_configs/system.ini");
include($docRoot."_modules/system/system.php");
// $docRoot, $htmlRoot, $template, $auth_enabled //
//   Myhome   //
include($docRoot."_modules/myhome/myhome.php");
//   File Manager   //
include($docRoot."_modules/file_manager/file_manager.php");
?>
<?php
//								P O S T								//
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$post_action=$_POST['action'];
	$post_data=$_POST['data'];

	$path_daemon_setts = $myhome_setts["system"]["pach_to_myhome"] . "/conf/myhome/myhome.auto";
	$path_setts = $docRoot . "_configs/myhome_auto.json";

	if ($post_action == 'get_setts') {
		$myhome_daemon_setts = $file_manager->OpenFile($path_daemon_setts,'');
		$myhome_setts = $system->FromJson($file_manager->OpenFile($path_setts,''));
		echo $system->ToJson(["myhome_daemon_setts"=>$myhome_daemon_setts,"myhome_setts"=>$myhome_setts]);
	}
	else if ($post_action == 'save_setts') {
		$myhome_daemon_setts = $system->FromJson($post_data)['myhome_daemon_setts'];
		$myhome_setts = $system->FromJson($post_data)['myhome_setts'];
		$file_manager->WriteToFile($path_daemon_setts,$myhome_daemon_setts);
		$file_manager->WriteToFile($path_setts,$system->ToJson($myhome_setts));

		$myhome_daemon_setts = $file_manager->OpenFile($path_daemon_setts,'');
		$myhome_setts = $system->FromJson($file_manager->OpenFile($path_setts,''));
		echo $system->ToJson(["myhome_daemon_setts"=>$myhome_daemon_setts,"myhome_setts"=>$myhome_setts]);

		// Restart myhome //
		shell_exec('sudo /etc/init.d/myhome.d start >/dev/null 2>&1 &');
	}
	exit();
}
?>
<?php
//								H T M L								//
$title.=" - Настройка автоматизации";
$system->InitJava();
include($docRoot.$template."header.php");
?>


<div id="Div_Auto_Holder" style="font-size: 13px; padding: 5px; -webkit-user-select: text;"></div>


<?php
//								Footer								//
include($docRoot.$template."footer.php");
?>

<script>
// ----------------- Body Footer ----------------- //
body_footer_opened = true;
body_footer_height = 32;

var html = '<span style="position:absolute;left:5px;"><input type="button" value="Добавить условие" style="margin-top:6px;" onclick="AddNewAuto()" /></span>';
html += '<span style="position:absolute;right:5px;"><input type="button" value="Сохранить" style="margin-top:6px;" onclick="SaveAutoSettings()"></span>';
document.getElementById("Body_Footer_Content").innerHTML = html;
</script>



<script>
var autos_holder = document.getElementById("Div_Auto_Holder");
// ----------------- Object ----------------- //
var auto_settings = [];
this.GetSettingArrId = function (id) {
	var arr_id = -1;
	for (var x=0; x < auto_settings.length; x++) {
		if (auto_settings[x].id == id)
			arr_id = x;
	}
	return arr_id;
}
function Auto_Setting (arr_id, settings) {
	this.id = arr_id;
	if (settings) this.settings = settings;
	else {
		this.settings = {
			'name':'Условие ' + (arr_id + 1),
			'enabled':true,
			'values': {'signal':'#01R1N;','return':'$01R1A;$01R1S;','ans_or_script':''}
		};
	}

	// Create Setting Main Window (Info Block) //
	this.set_div_holder = document.createElement('div');
	this.set_div_holder.className = "info_block";
	this.set_div_holder.id = "auto_sett_div_" + arr_id;
	autos_holder.appendChild(this.set_div_holder);

	// Create Setting //
	var set_div_name = document.createElement('div');
	set_div_name.className = "info_block_name";
	this.set_div_holder.appendChild(set_div_name);
	set_div_name.innerHTML = '<div style="position:relative;">' + this.settings['name'] +
		'<span style="position:absolute;right:5px;" onclick="RemoveSetting(' + this.id + ')">Удалить</span></div>';

	// HTML main setts //
	var html = '<center><table>';
	html += '<tr><td width="50%">Имя</td><td align="right">' +
		Draw_Input (this.set_div_holder.id + '_name', 'auto_settings[GetSettingArrId(' + this.id + ')].settings[\'name\']', this.settings['name'], "return"); +
		'</td></tr>';
	html += '<tr><td>Включено</td><td align="center">' +
		Draw_Checkbox (this.set_div_holder.id + '_enabled', 'auto_settings[GetSettingArrId(' + this.id + ')].settings[\'enabled\']', this.settings['enabled'], "return"); +
		'</td></tr>';
	html += '</table>';

	// HTML myhome auto setts //
	html += '<hr><table>';
	html += '<tr><td width="50%">Сигнал</td><td align="right">' +
		Draw_Input (this.set_div_holder.id + '_signal', 'auto_settings[GetSettingArrId(' + this.id + ')].settings[\'values\'][\'signal\']', this.settings['values']['signal'], "return"); +
		'</td></tr>';
	html += '<tr><td>Выполнить</td><td align="right">' +
		'<select id="' + this.set_div_holder.id + '_ans_or_script"' +
			'onchange="auto_settings[GetSettingArrId(' + this.id + ')].settings[\'values\'][\'ans_or_script\']=' + this.set_div_holder.id + '_ans_or_script.value"' +
		'>' +
			'<option value="false">Авто-ответ</option>' +
			'<option value="true">Запустить скрипт</option>' +
		'</select>'
		'</td></tr>';
	html += '<tr><td>Ответ/Скрипт</td><td align="right">' +
		Draw_Input (this.set_div_holder.id + '_return', 'auto_settings[GetSettingArrId(' + this.id + ')].settings[\'values\'][\'return\']', this.settings['values']['return'], "return"); +
		'</td></tr>';
	html += '</table></center>';

	this.set_div_holder.innerHTML += html;
	if (this.settings['values']['ans_or_script'] == 'true' || this.settings['values']['ans_or_script'] == '1')
		document.getElementById(this.set_div_holder.id + '_ans_or_script').value = 'true';

	this.Remove = function (arr_id) {
		if (confirm('Удалить: ' + this.settings['name'] + '?')) {
			this.set_div_holder.parentNode.removeChild(this.set_div_holder);
			auto_settings.splice(arr_id,1);
		}
	}
}
function RemoveSetting (id) {
	for (var x=0; x < auto_settings.length; x++) {
		if (auto_settings[x].id == id) auto_settings[x].Remove(x);
	}
}

function AddNewAuto () {
	// Find last id //
	var new_auto_id = 0;
	var founded = false;
	while (founded == false){
		var id_is = false;
		for (var x=0; x < auto_settings.length && id_is == false; x++) {
			if (auto_settings[x] && auto_settings[x].id == new_auto_id)
				id_is = true;
		}
		if (id_is == false) founded = true;
		else new_auto_id++;
	}

	auto_settings.push(new Auto_Setting(new_auto_id));
	Body_Block.scrollTop = autos_holder.clientHeight;
}




// ----------------- Send Post ----------------- //
SendPost ('get_setts','');
function PostReturned (servAnswer,send_action,send_data) {
	// Init auto setts //
	if (send_action == 'get_setts') {
		DrawAutos(servAnswer);
	}
	// Save auto setts //
	else if (send_action == 'save_setts') {
		if (ToJson(FromJson(servAnswer)) == ToJson(FromJson(send_data))) alert('Настройки сохранены');
		else alert('Ошибка сохранения<hr>' + servAnswer);
	}
}
function DrawAutos (data) {
	var autos_holder = document.getElementById("Div_Auto_Holder");
	var autos_count = 0;

	var auto_setts, auto_setts_daemon;
	auto_setts = FromJson(data)["myhome_setts"];
	auto_setts_daemon = FromJson(data)["myhome_daemon_setts"];
	if (auto_setts != null) {
		for (var x=0; x<auto_setts.length; x++) {
			auto_settings.push(new Auto_Setting(x, auto_setts[x]));
			autos_count++;
		}
	}
	else if (auto_setts_daemon != null) {
		auto_setts_daemon = auto_setts_daemon.split("\n");
		if (auto_setts_daemon[0].split(" ")[0] != "Error.") {
			for (var x=0; x<auto_setts_daemon.length; x++) {
				if (auto_setts_daemon[x] != '') {
					// If Json is //
					if (auto_setts != null && auto_setts[x]) {
						var setts = auto_setts[x];
					}
					else {
						var setts_daemon = auto_setts_daemon[x].split(" ");
						var setts = {
							'name':'Условие ' + (x + 1),
							'enabled':true,
							'values':{'signal':setts_daemon[0],'return':setts_daemon[1],'ans_or_script':setts_daemon[2] || ''}
						};
					}
					auto_settings.push(new Auto_Setting(x, setts));
					autos_count++;
				}
			}
		}
	}
	else auto_setts_daemon = [];

	if (autos_count == 0)
		autos_holder.innerHTML = "<center><b>Автоматизации не настроены</b></center>";
}
function SaveAutoSettings () {
	var new_auto_setts = [];
	var new_auto_setts_daemon = '';
	for (var x=0; x < auto_settings.length; x++) {
		new_auto_setts.push(auto_settings[x].settings);
		if (auto_settings[x].settings['enabled']) {
			if (auto_settings[x].settings['values']['ans_or_script'] == '1' || auto_settings[x].settings['values']['ans_or_script'] == 'true')
				new_auto_setts_daemon += auto_settings[x].settings['values']['signal'] +
					" " + auto_settings[x].settings['values']['return'] +
					" 1\n";
			else {
				new_auto_setts_daemon += auto_settings[x].settings['values']['signal'] +
					" " + auto_settings[x].settings['values']['return'] + "\n";
			}
		}
	}
	if (new_auto_setts_daemon == '') new_auto_setts_daemon = null;

	// Send Setts //
	var new_myhome_all_auto_setts = {"myhome_daemon_setts":new_auto_setts_daemon,"myhome_setts":new_auto_setts};
	//alert(ToJson(new_myhome_all_auto_setts));
	if (confirm('Сохранить настройки?')) {
		SendPost ('save_setts',ToJson(new_myhome_all_auto_setts));
	}
}
</script>
