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

	//echo $post_data;
	if ($post_action=="SaveSettings") {
		$file_manager->WriteToFile($docRoot."_configs/myhome.json",$post_data);

		echo $file_manager->OpenFile($docRoot."_configs/myhome.json",'');
	}
	exit();
}
?>
<?php
//								H T M L								//
//   System   //
$title.=" - Основные настройки";
$system->InitJava();
include($docRoot.$template."header.php");
//   Myhome   //
$myhome->InitJava();
?>

<script>
</script>


<!--             TOP MENU             -->
<div class="block_top_menu">
	<div class="block_top_menu_holder">
		<div id="Top_But_0" class="block_top_menu_but_pushed" onclick="But_Top_Menu(0)">Основные</div>
		<div id="Top_But_1" class="block_top_menu_but" onclick="But_Top_Menu(1)">Устройства</div>
		<div id="Top_But_2" class="block_top_menu_but" onclick="But_Top_Menu(2)">Группы</div>
	</div>
</div>

<!--             BLOCKS             -->
<div id="Settings_Div" style="width:100%;overflow:hidden;">
	<div id="Settings_Holder" style="width:calc(100% * 3);">

		<!--             M A I N             -->
		<div style="width:calc((100%) / 3);float:left;" id="Div_Setts_0">
			<div class="info_block">
			<div class="info_block_name">Основные настройки</div>
			<center>
				<table style="min-width:50%;">
					<tr>
						<td width="50%">Имя системы</td><td>
							<script>Draw_Input ("System_name", "myhome_setts['main']['system_name']");</script>
						</td>
					</tr><tr>
						<script>
						function Add_Select_Main_Group () {
							// Fill Select onmousedown //
							this.Select_Main_Group_Clicked = function () {
								var html = '';
								for (var x=0; x<groups.length; x++) {
									if (groups[x] != "")
										html += '<option value="' + x + '"' +
											'>' + groups[x].settings["name"] + '</option>';
								}
								document.getElementById('input_main_group').innerHTML = html;
								document.getElementById('input_main_group').value = myhome_setts['main']['first_group'];
							}

							var html = "";
							html += '<select id="input_main_group"' +
								' onmousedown="Select_Main_Group_Clicked();"' +
								' onchange="myhome_setts[\'main\'][\'first_group\'] = document.getElementById(\'input_main_group\').value;"' +
								'>';
									for (var x=0; x<myhome_setts['groups'].length; x++) {
										if (myhome_setts['groups'][x] != "")
											html += '<option value="' + x + '"' +
												'>' + myhome_setts["groups"][x]["name"] + '</option>';
									}
							html += '</select>';
							document.write(html);
						}
						</script>
						<td>Основная группа</td><td>
							<script>Add_Select_Main_Group ();</script>
						</td>
					</tr><tr>
						<td>Консоль</td><td align="center">
							<script>Draw_Checkbox ("View_Console", "myhome_setts['main']['view_console']");</script>
						</td>
					</tr>
				</table>
			</center>
			</div>
			<div class="info_block">
			<div class="info_block_name">Мой дом</div>
			<center>
				<table style="min-width:50%;">
					<tr>
						<td width="50%">Путь</td><td width="150" align="right">
							<script>Draw_Input ("pach_to_myhome", "myhome_setts['system']['pach_to_myhome']");</script>
						</td>
					</tr><tr>
						<td>Хост</td><td align="right">
							<script>Draw_Input ("host", "myhome_setts['myhome']['host']");</script>
						</td>
					</tr><tr>
						<td>Порт</td><td align="right">
							<script>Draw_Input ("port", "myhome_setts['myhome']['port']");</script>
						</td>
					</tr><tr>
						<td>commands_timeout (ms)</td><td align="right">
							<script>Draw_Input ("commands_timeout", "myhome_setts['myhome']['commands_timeout']");</script>
						</td>
					</tr><tr>
						<td>connection_timeout (ms)</td><td align="right">
							<script>Draw_Input ("connection_timeout", "myhome_setts['myhome']['connection_timeout']");</script>
						</td>
					</tr><tr>
						<td>waiting_timeout (ms)</td><td align="right">
							<script>Draw_Input ("waiting_timeout", "myhome_setts['myhome']['waiting_timeout']");</script>
						</td>
					</tr>
				</table>
			</center>
			</div>
		</div>

		<!--             D E V I C E S             -->
		<script>
		function Add_New_Device (input_id) {
			var type = document.getElementById(input_id).value;
			myhome_setts['devices'].push({});
			var new_dev_arr_id = myhome_setts['devices'].length - 1;

			var dev_id = "Device_Div_" + new_dev_arr_id;

			// find free device id //
			var new_dev_id = 0;
			var founded = false;
			while (founded == false){
				var id_is = false;
				for (var x=0; x < devices.length && id_is == false; x++) {
					if (devices[x] && devices[x].settings['dev_id'] == new_dev_id)
						id_is = true;
				}
				if (id_is == false) founded = true;
				else new_dev_id++;
			}

			myhome_setts['devices'][new_dev_arr_id] = {"dev_id":new_dev_id,"enabled":true,"rights":"","name":"","type":type,"values":{}};

			devices.push([]);
			if (type == 'RM-4') {
				myhome_setts['devices'][new_dev_arr_id]['name'] = 'Релейный модуль';
				myhome_setts['devices'][new_dev_arr_id]['values'] = {'address':'0'};
				Draw_Dev_Holder(new_dev_arr_id, dev_id);
				devices[new_dev_arr_id] = new RM_4 (new_dev_arr_id,dev_id,myhome_setts['devices'][new_dev_arr_id]);
			}
			if (type == 'I-16') {
				myhome_setts['devices'][new_dev_arr_id]['name'] = 'Модуль расширения';
				myhome_setts['devices'][new_dev_arr_id]['values'] = {'address':'0'};
				Draw_Dev_Holder(new_dev_arr_id, dev_id);
				devices[new_dev_arr_id] = new I_16 (new_dev_arr_id,dev_id,myhome_setts['devices'][new_dev_arr_id]);
			}
			if (type == 'IP_CAM') {
				myhome_setts['devices'][new_dev_arr_id]['name'] = 'Камера';
				myhome_setts['devices'][new_dev_arr_id]['values'] = {'host':'localhost','port':'8080','link':'cam1.jpg'};
				Draw_Dev_Holder(new_dev_arr_id, dev_id);
				devices[new_dev_arr_id] = new IP_CAM (new_dev_arr_id,dev_id,myhome_setts['devices'][new_dev_arr_id]);
			}

			devices[new_dev_arr_id].CreateSetts ();

			document.getElementById(input_id).value = '';
			//Body_Block.scrollTop = Settings_Div.clientHeight;
			document.body.scrollTop = Settings_Div.clientHeight;
		}
		function Add_Select_New_Device (id) {
			var html = "";
			html += '<select id="input_new_device_' + id + '" style="width:200px;"' +
				//' onmousedown="Select_Main_Group_Clicked();"' +
				' onchange="Add_New_Device(\'input_new_device_' + id + '\');"' +
				'>' +
					'<option value="">Выберите устройство</option>' +
					'<option value="RM-4">Релейный модуль</option>' +
					'<option value="I-16">Модуль расширения</option>' +
					'<option value="IP_CAM">Камера</option>' +
				'</select>';
			document.write(html);
		}
		</script>
		<div style="width:calc((100%) / 3);float:left;" id="Div_Setts_1">
			<hr><center><script>Add_Select_New_Device('top');</script></center><hr>
			<div id="Div_Setts_Devs"></div>
			<hr><center><script>Add_Select_New_Device('bottom');</script></center><hr>
		</div>

		<!--             G R O U P S             -->
		<div style="width:calc((100%) / 3);float:left;" id="Div_Setts_2">
			<hr><center><input type="button" style="width:200px;" onclick="DrawNewGroup(true);" value="Добавить группу"></center><hr>
			<div id="Div_Setts_Groups"></div>
			<hr><center><input type="button" style="width:200px;" onclick="DrawNewGroup();" value="Добавить группу"></center><hr>
		</div>

	</div>
</div>


<?php
//								Footer								//
include($docRoot.$template."footer.php");
?>
<script>
//   Body Footer   //
body_footer_opened = true;
body_footer_height = 32;

document.getElementById("Body_Footer_Content").innerHTML = '<center><input type="button" value="Сохранить" style="margin-top:6px;" onclick="SaveSettings()"></center>';
</script>







<!-- -------------------------------- Change Menu & Blocks -------------------------------- -->
<script>
var now_opened=0;
var Cange_Top_Menu = function (id) {
	if (document.getElementById("Top_But_"+id)) {
		now_opened = id;
		holder_width = document.getElementById("Settings_Div").clientWidth;

		document.getElementById("Settings_Holder").style.width = holder_width * 3;

		// Change Buts in Top_Menu //
		for (var x=0; document.getElementById("Top_But_"+x); x++)
			document.getElementById("Top_But_"+x).className = "block_top_menu_but";
		document.getElementById("Top_But_"+id).className = "block_top_menu_but_pushed";

		// Change Blocks //
		var margin = holder_width * id;
		document.getElementById("Settings_Holder").style.marginLeft = "-"+margin+"px";

		// Change Height of Blocks //
		for (var x=0; document.getElementById("Div_Setts_"+x); x++)
			document.getElementById("Div_Setts_"+x).style.height = "10px";
		document.getElementById("Div_Setts_"+id).style.height = "auto";
	}
}
var But_Top_Menu = function (id) {
	if (document.getElementById("Top_But_"+id)) {
		document.body.scrollTop = 0;
		Transition ("Settings_Holder",300);
		Cange_Top_Menu(id);
		setTimeout(function(){Transition ("Settings_Holder",300);document.body.scrollTop = 0;},350);
	}
}
window.addEventListener("DOMContentLoaded", function() {
	Cange_Top_Menu(now_opened);
});
var OnResize = function(width) {
	Cange_Top_Menu(now_opened);
}
// Swipes //
AddTouchMove("Body_Block");
function TouchMoved(x){
	if (x<0) But_Top_Menu(now_opened+1);
	else But_Top_Menu(now_opened-1);
}
//   Add Keyboard Events   //
//function OnLeftArrow() {But_Top_Menu(now_opened-1);}
//function OnRightArrow() {But_Top_Menu(now_opened+1);}
function OnLeftArrow() {if (document.activeElement.id == '') But_Top_Menu(now_opened-1);}
function OnRightArrow() {if (document.activeElement.id == '') But_Top_Menu(now_opened+1);}
</script>



<script>
// Stop scroll (not works) //
/*HTMLElement.prototype.stopScroll = function(){
	this.scroll({top:this.scrollTop+1});
}*/
</script>




<!-- -------------------------------- D R A W I N G -------------------------------- -->
<script>
//        Main        //
var DrawMain = function () {
	// Fill Main Data //
	var input_system_name=document.getElementById("System_name");
	var input_main_group=document.getElementById("Main_Group");
	var input_view_console=document.getElementById("View_Console");

	input_system_name.value=myhome_setts['main']['system_name'];
	document.getElementById('input_main_group').value=myhome_setts['main']['first_group'];
	input_view_console.checked=myhome_setts['main']['view_console'];

	// Fill Myhome Data //
	var pach_to_myhome=document.getElementById("pach_to_myhome");
	var host=document.getElementById("host");
	var port=document.getElementById("port");

	pach_to_myhome.value=myhome_setts['system']['pach_to_myhome'];
	host.value=myhome_setts['myhome']['host'];
	port.value=myhome_setts['myhome']['port'];

	var commands_timeout=document.getElementById("commands_timeout");
	var connection_timeout=document.getElementById("connection_timeout");
	var waiting_timeout=document.getElementById("waiting_timeout");

	commands_timeout.value=myhome_setts['myhome']['commands_timeout'];
	connection_timeout.value=myhome_setts['myhome']['connection_timeout'];
	waiting_timeout.value=myhome_setts['myhome']['waiting_timeout'];
}


//        Devices        //
var devices = [];
var Draw_Dev_Holder = function (x, id) {
	var div_groups_setts = document.getElementById("Div_Setts_Devs");
	var new_dev = document.createElement('div');
	new_dev.id = id;
	new_dev.className = "info_block";
	div_groups_setts.appendChild(new_dev);

	var remove_but = '<div' +
		' style="padding:3px;cursor:pointer;width:20px;height:15px;"' +
		' onclick="devices[' + x + '].Remove()">' +
		'<img src="/myhome/_images/icons/x.png" width="15" height="15"></div>';
	var html = '<div class="info_block_name" style="position:relative;">' +
		myhome_setts['devices'][x]['name'] +
		'<span style="position:absolute;right:35; padding-top:2px;font-size:12px;">' +
			myhome_setts['devices'][x]['type'] + ' ' +
		"</span>" +
		'<span style="position:absolute;right:5;">' +
			remove_but +
		"</span>" +
		'</div>';
	new_dev.innerHTML=html;
}
var DrawDevs = function () {
	for (var x=0; myhome_setts['devices'][x]; x++) {
		var dev_id = "Device_Div_" + x
		Draw_Dev_Holder(x, dev_id);
		devices.push([]);

		switch (myhome_setts['devices'][x]['type']) {
			case "RM-4":
				devices[x] = new RM_4 (x,dev_id,myhome_setts['devices'][x]);
				//devices[x].__proto__ = new Blank_Dev();
				devices[x].CreateSetts ();
				break;
			case "I-16":
				devices[x] = new I_16 (x,dev_id,myhome_setts['devices'][x]);
				//devices[x].__proto__ = new Blank_Dev();
				devices[x].CreateSetts ();
				break;
			case "IP_CAM":
				devices[x] = new IP_CAM (x,dev_id,myhome_setts['devices'][x]);
				//devices[x].__proto__ = new Blank_Dev();
				devices[x].CreateSetts ();
				break;
			/*default:
				var html = ToJson(myhome_setts['devices'][x]);
				new_dev.innerHTML += html;
				break;*/
		}
	}
}




//        Groups        //
var groups = [];
var Draw_Group_Holder = function (x, id) {
	var div_groups_setts = document.getElementById("Div_Setts_Groups");
	var new_group = document.createElement('div');
	new_group.id = id;
	new_group.className = "info_block";
	div_groups_setts.appendChild(new_group);

	var remove_but = '<div' +
		' style="padding:3px;cursor:pointer;width:20px;height:15px;"' +
		' onclick="groups[' + x + '].Remove()">' +
		'<img src="/myhome/_images/icons/x.png" width="15" height="15"></div>';
	var html = '<div class="info_block_name" style="position:relative;">' +
		groups[x].settings['name'] +
		'<span style="position:absolute;right:5;">' +
			remove_but +
		"</span>" +
		'</div>';

	new_group.innerHTML=html;
}
var DrawGroups = function () {
	for (var x=0; myhome_setts['groups'][x]; x++) {
		groups.push([]);
		var group_id = "Group_Div_" + x;
		groups[x].settings = myhome_setts['groups'][x];
		Draw_Group_Holder(x, group_id);
		groups[x] = new Group (x,group_id,myhome_setts['groups'][x]);
		groups[x].CreateGroupSettsDiv ();
	}
}
var DrawNewGroup = function () {
	groups.push([]);
	var x = groups.length - 1;
	groups[x].settings = {"name":"Группа","enabled":true,"rights":"","devs_id":[]};
	var group_id = "Group_Div_" + x;
	Draw_Group_Holder(x, group_id);
	groups[x] = new Group (x,group_id,groups[x].settings);
	groups[x].CreateGroupSettsDiv ();

	//Transition ("Settings_Holder",300); //alert(Settings_Div.clientHeight);
	//Body_Block.scrollTop = Settings_Div.clientHeight;
	document.body.scrollTop = Settings_Div.clientHeight;
}

//        I N I T        //
var InitMyhomeSetts = function () {
	DrawMain();
	DrawDevs();
	DrawGroups();
}
InitMyhomeSetts();
</script>







<!-- -------------------------------- Save Settings -------------------------------- -->
<script>
function SaveSettings () {
	// Fill Devices //
	myhome_setts['devices'] = [];
	for (var x=0; x<devices.length; x++) {
		if (devices[x] != '')
			myhome_setts['devices'].push(devices[x].settings);
	}

	// Fill Groups //
	myhome_setts['groups'] = [];
	for (var x=0; x<groups.length; x++) {
		if (groups[x] != '')
			myhome_setts['groups'].push(groups[x].settings);
	}

	// Sending.. //
	//alert('Save:\n' + ToJson(myhome_setts['groups']));
	if (confirm("Сохранить настройки?") == true)
		SendPost ("SaveSettings",ToJson(myhome_setts));
}
function PostReturned (servAnswer,send_action,send_data) {
	//alert(servAnswer);
	if (servAnswer == send_data) alert ("Настройки сохранены");
	else alert ("Ошибка сохранения");
}
</script>
