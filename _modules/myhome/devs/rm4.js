var RM_4 = function (id,holder_id,settings) {
	Blank_Dev.apply(this, arguments);

	this.id = id;
	this.holder_id = holder_id;
	this.holder_div = document.getElementById(this.holder_id);
	this.settings = FromJson(ToJson(settings));
	this.settings["type_name"] = "Релейный модуль";

	this.adress = String(this.settings["values"]['address']);
	if (parseInt(this.adress)<10) this.adress = "0" + parseInt(this.adress);


	this.outputs = [0];
	this.inputs = [0];


	//-------------------------------- M Y H O M E --------------------------------//
	this.CreateMain = function (){
		//    ---      D R A W     ---     //
		var html = '';
		// Reley //
		for (var x=0; x<4; x++) {
			var name = this.settings["values"]["rel_" + (x + 1)];
			var io_num = x + 1;
			var command = '$' + (this.adress) + 'R' + io_num + 'A;$' + (this.adress) + 'R' + io_num + 'S;';

			html += '<div class="IO_Holder"><div class="IO_Content_Holder"><div class="IO_Holder_Name">' +
				name +
				'</div>' +
				'<div class="IO_Holder_IO" onclick="MyhomeSendCommand(\'' + command + '\',MyhomeReturned);">' +
				'<div id="' + this.id + this.adress + io_num + '"></div>' +
				'</div>' +
				'</div></div>';

		}
		// Input //
		if (this.settings["values"]["input_view"]) {
			html += "<hr>";
			for (var x=1; x<=4; x++) {
				var name = this.settings["values"]["inp_" + (x)];
				var io_num = x;
				html += '<div class="IO_Holder"><div class="IO_Content_Holder"><div class="IO_Holder_Name">' +
					name +
					'</div>' +
					'<div class="IO_Holder_IO">' +
					'<div id="I' + this.id + this.adress + io_num + '"></div>' +
					'</div>' +
					'</div></div>';
			}
		}
		this.holder_div.innerHTML += html;


		//      ---    Init Vars     ---     //
		// Reley //
		for (var x=1; x<5; x++) {
			var io_num = x;
			this.outputs[x] = document.getElementById(this.id + this.adress + io_num);
		}
		// Input //
		for (var x=1; x<5; x++) {
			var io_num = x;
			this.inputs[x] = document.getElementById('I' + this.id + this.adress + io_num);
		}


		//     ---     Add Command    ---      //
		AddCommandNoDouble("$" + this.adress + "Z;");
	};



	this.MakeCommand = function (command) {
		if (command[3] == "Z" && command[4] == "R") {
			for (var x=1; x<5; x++) {
				var io_num = x;
				this.ChangeOutput (io_num,command[x + 7]);
			}
			for (var x=1; x<5; x++) {
				var io_num = x;
				this.ChangeInput (io_num,command[io_num + 12]);
			}
		}
		if (command[3] == "R") {
			var o_num = command[4];
			var state = command[5];
			this.ChangeOutput (o_num,state);
		}
	};




	//-------------------------------- S E T T I N G S --------------------------------//
	this.CreateSetts_Dev = function () {
		var html = "<hr><center>";

		// Адрес //
		html += '<table>' +
				'<tr>' +
					'<td width="50%">Адрес</td>' +
					'<td align="right">' + this.DrawInput('address','values') + '</td>' +
				'</tr>' +
			'</table>'

		// Реле //
		html += '<table>';
		for (var x=1; x<5; x++) {
			if (!this.settings['values']['rel_' + x])
				this.settings['values']['rel_' + x] = 'Реле ' + x;
			html += '<tr>' +
					'<td width="50%">Реле ' + x + '</td>' +
					'<td align="right">' + this.DrawInput('rel_' + x,'values') + '</td>' +
				'</tr>';
		}
		html += '</table>';

		// Входы //
		html += '<table>' +
				'<tr>' +
					'<td width="50%">Отображать входы</td>' +
					'<td align="center">' + this.DrawCheckbox('input_view','values') + '</td>' +
				'</tr>' +
			'</table>'

		html += '<table>';
		for (var x=1; x<5; x++) {
			if (!this.settings['values']['inp_' + x])
				this.settings['values']['inp_' + x] = 'Вход ' + x;
			html += '<tr>' +
					'<td width="50%">Вход ' + x + '</td>' +
					'<td align="right">' + this.DrawInput('inp_' + x,'values') + '</td>' +
				'</tr>';
		}
		html += '</table>';

		html += "</center>"
		//html += ToJson(this.settings['values']);
		this.holder_div.innerHTML += html;
	};




	//-------------------------------- D E V I C E   S E T T I N G S --------------------------------//
	this.Create_Device_Setts_Inputs = function () {
		var inputs_div = document.createElement('div');
		this.moved_devices_settings.objects[0].holder_div.appendChild(inputs_div);

		var html = '<center><input type="button" style="margin-top:10px;" value="Сохранить насатройки входов" onclick="devices[0].SaveSettingsInput()" /></center>';
		for (var x=1; x < 5; x++) {
			this.settings['input_' + x] = {'id':x,'enabled':true,'rel_num':'1','inversion':false,'but_switch':'0','action':'0','ignore':'0','ignore_by':'0'};
			this.settings['input_' + (x + 4)] = {'id':x+4,'enabled':false,'rel_num':'1','inversion':false,'but_switch':'0','action':'0','ignore':'0','ignore_by':'0'};
			html += '<hr>Вход ' + x + '<table><tr>' +
					'<th width="50%">Тип</th>' +
					'<th width="25%">Действ.1</th>' +
					'<th width="25%">Действ.2</th>' +
				'</tr><tr>' +
					'<td>Включено</td>' +
					'<td align="center">' + this.DrawCheckbox('enabled','input_' + x) + '</td>' +
					'<td align="center">' + this.DrawCheckbox('enabled','input_' + (x + 4)) + '</td>' +
				'</tr><tr>' +
					'<td>Кнопка/Выключатель</td>' +
					'<td align="center">' +
						'<select id="' + this.holder_id + '_but_switch_' + 'input_' + x + '" onchange="devices[' + this.id + '].settings[\'input_' + x + '\'][\'but_switch\'] = this.value">' +
							'<option value="0">Кнопка</option>' +
							'<option value="1">Выключатель</option>' +
						'</select>' +
					'</td>' +
					'<td align="center">' +
						'<select id="' + this.holder_id + '_but_switch_' + 'input_' + (x + 4) + '" onchange="devices[' + this.id + '].settings[\'input_' + (x + 4) + '\'][\'but_switch\'] = this.value">' +
							'<option value="0">Кнопка</option>' +
							'<option value="1">Выключатель</option>' +
						'</select>' +
					'</td>' +
				'</tr><tr>' +
					'<td>Инверсия</td>' +
					'<td align="center">' + this.DrawCheckbox('inversion','input_' + x) + '</td>' +
					'<td align="center">' + this.DrawCheckbox('inversion','input_' + (x + 4)) + '</td>' +
				'</tr><tr>' +
					'<td>Номер реле</td>' +
					'<td align="center">' +
						'<select id="' + this.holder_id + '_rel_num_' + 'input_' + x + '" onchange="devices[' + this.id + '].settings[\'input_' + x + '\'][\'rel_num\'] = this.value">' +
							'<option value="0">Не выбрано</option>' +
							'<option value="1">1</option>' +
							'<option value="2">2</option>' +
							'<option value="3">3</option>' +
							'<option value="4">4</option>' +
						'</select>' +
					'</td>' +
					'<td align="center">' +
						'<select id="' + this.holder_id + '_rel_num_' + 'input_' + (x + 4) + '" onchange="devices[' + this.id + '].settings[\'input_' + (x + 4) + '\'][\'rel_num\'] = this.value">' +
							'<option value="0">Не выбрано</option>' +
							'<option value="1">1</option>' +
							'<option value="2">2</option>' +
							'<option value="3">3</option>' +
							'<option value="4">4</option>' +
						'</select>' +
					'</td>' +
				'</tr><tr>' +
					'<td>Действие</td>' +
					'<td align="center">' +
						'<select id="' + this.holder_id + '_action_' + 'input_' + x + '" onchange="devices[' + this.id + '].settings[\'input_' + x + '\'][\'action\'] = this.value">' +
							'<option value="0">Оба</option>' +
							'<option value="1">Только включение</option>' +
							'<option value="2">Только выключение</option>' +
						'</select>' +
					'</td>' +
					'<td align="center">' +
						'<select id="' + this.holder_id + '_action_' + 'input_' + (x + 4) + '" onchange="devices[' + this.id + '].settings[\'input_' + (x + 4) + '\'][\'action\'] = this.value">' +
							'<option value="0">Оба</option>' +
							'<option value="1">Только включение</option>' +
							'<option value="2">Только выключение</option>' +
						'</select>' +
					'</td>' +
				'</tr><tr>' +
					'<td>Игнорировать</td>' +
					'<td align="center">' +
						'<select id="' + this.holder_id + '_ignore_' + 'input_' + x + '" onchange="devices[' + this.id + '].settings[\'input_' + x + '\'][\'ignore\'] = this.value">' +
							'<option value="0">Отключено</option>' +
							'<option value="1">По входу 1</option>' +
							'<option value="2">По входу 2</option>' +
							'<option value="3">По входу 3</option>' +
							'<option value="4">По входу 4</option>' +
						'</select>' +
					'</td>' +
					'<td align="center">' +
						'<select id="' + this.holder_id + '_ignore_' + 'input_' + (x + 4) + '" onchange="devices[' + this.id + '].settings[\'input_' + (x + 4) + '\'][\'ignore\'] = this.value">' +
							'<option value="0">Отключено</option>' +
							'<option value="1">По входу 1</option>' +
							'<option value="2">По входу 2</option>' +
							'<option value="3">По входу 3</option>' +
							'<option value="4">По входу 4</option>' +
						'</select>' +
					'</td>' +
				'</tr><tr>' +
					'<td>Игнорировать при</td>' +
					'<td align="center">' +
						'<select id="' + this.holder_id + '_ignore_by_' + 'input_' + x + '" onchange="devices[' + this.id + '].settings[\'input_' + x + '\'][\'ignore_by\'] = this.value">' +
							'<option value="0">Если замкнут</option>' +
							'<option value="1">Если разомкнут</option>' +
						'</select>' +
					'</td>' +
					'<td align="center">' +
						'<select id="' + this.holder_id + '_ignore_by_' + 'input_' + (x + 4) + '" onchange="devices[' + this.id + '].settings[\'input_' + (x + 4) + '\'][\'ignore_by\'] = this.value">' +
							'<option value="0">Если замкнут</option>' +
							'<option value="1">Если разомкнут</option>' +
						'</select>' +
					'</td>' +
				'</tr></table>';

			AddCommand('$' + this.adress + 'rB' + x + ';');
			AddCommand('$' + this.adress + 'rB' + (x + 4) + ';');
		}

		html += '<hr><center><input type="button" style="margin-bottom:10px;" value="Сохранить насатройки входов" onclick="devices[0].SaveSettingsInput()" /></center>';
		//html += "<hr>$" + this.adress + "rB1;";
		inputs_div.innerHTML = html;
	}
	this.Create_Device_Setts_Termo = function () {
		var termo_div = document.createElement('div');
		this.moved_devices_settings.objects[1].holder_div.appendChild(termo_div);

		var html = '<center><input type="button" style="margin-top:10px;" value="Сохранить насатройки термореле" onclick="devices[0].SaveSettingsTermo()" /></center>';
		for (var x=0; x < 4; x++) {
			this.settings['termo_' + x] = {'id':x,'enabled':false,'termo_num':'0','t_min':'00','t_max':'00','rel_num':'1','inversion':false};
			html += '<hr>Условие ' + (x + 1) + '<table><tr>' +
					'<td>Включено</td>' +
					'<td align="center">' + this.DrawCheckbox('enabled','termo_' + x) + '</td>' +
				'</tr><tr>' +
					'<td>Номер термометра</td>' +
					'<td align="center">' +
						'<select id="' + this.holder_id + '_termo_num_' + 'termo_' + x + '" onchange="devices[' + this.id + '].settings[\'termo_' + x + '\'][\'termo_num\'] = this.value">' +
							'<option value="0">0</option>' +
							'<option value="1">1</option>' +
							'<option value="2">2</option>' +
							'<option value="3">3</option>' +
						'</select>' +
					'</td>' +
				'</tr><tr>' +
					'<td>Мин темп</td>' +
					'<td align="center">' + this.DrawInput('t_min','termo_' + x) + '</td>' +
				'</tr><tr>' +
					'<td>Макс темп</td>' +
					'<td align="center">' + this.DrawInput('t_max','termo_' + x) + '</td>' +
				'</tr><tr>' +
					'<td>Номер реле</td>' +
					'<td align="center">' +
					'<select id="' + this.holder_id + '_rel_num_' + 'termo_' + x + '" onchange="devices[' + this.id + '].settings[\'termo_' + x + '\'][\'rel_num\'] = this.value">' +
							'<option value="0">Не выбрано</option>' +
							'<option value="1">1</option>' +
							'<option value="2">2</option>' +
							'<option value="3">3</option>' +
							'<option value="4">4</option>' +
						'</select>' +
					'</td>' +
				'</tr><tr>' +
					'<td>Инверсия</td>' +
					'<td align="center">' + this.DrawCheckbox('inversion','termo_' + x) + '</td>' +
				'</tr></table>';

				AddCommand('$' + this.adress + 'rT' + x + ';');
		}

		html += '<hr><center><input type="button" style="margin-bottom:10px;" value="Сохранить насатройки термореле" onclick="devices[0].SaveSettingsTermo()" /></center>';
		//html += "<hr>$" + this.adress + "rT0;";
		termo_div.innerHTML = html;
	}
	this.Create_Device_Setts_Dev = function () {
		AddCommand('$' + this.adress + 'V;');

		this.Create_Device_Setts_Head();

		this.setting_div = document.createElement('div');
		this.holder_div.appendChild(this.setting_div);

		// Adress & Version //
		var html = '<hr><table>' +
			'<tr><td width="50%">Адрес</td><td align="center">' + this.adress + '</td></tr>' +
			'<tr><td width="50%">Версия ПО</td><td align="center"><div id="Device_Version"></div></td></tr>' +
			'</table>';
		// Holders //
		html += '<nobr><div class="block_top_menu" id="RM_4_Buts_Holder"></div></nobr>' +
			'<div id="RM_4_Settings_Holder"></div>';
		this.setting_div.innerHTML = html;

		// Add Moved_Divs //
		this.moved_devices_settings = new Moved_Divs ('devices[' + this.id + '].moved_devices_settings',{
			'holders':{'menu':'RM_4_Buts_Holder','divs':'RM_4_Settings_Holder'},
			'buts':{'but':'block_top_menu_but','pushed':'block_top_menu_but_pushed'}
		});
		this.moved_devices_settings.Add_Object("Входы");
		this.moved_devices_settings.Add_Object("Термореле");

		this.Create_Device_Setts_Inputs();
		this.Create_Device_Setts_Termo();

		SendCommands();
	}
	// --------------------- Make Answer --------------------- //
	this.MakeCommandSettings = function (data) {
		// Device Version //
		if (data[3] == 'V') Device_Version.innerHTML = data;
		// Device Settings //
		else if (data[3] == 'r') {
			//   Inputs   //
			if (data[4] == 'B') {
				action_num = data[5];
				// First action //
				if (action_num < 5) {
					// Enabled (data[6]) //
					if (data[6] == '1') {
						document.getElementById(this.holder_id + '_enabled_input_' + action_num).checked = false;
						this.settings['input_' + action_num]['enabled'] = false;
					}
					else {
						document.getElementById(this.holder_id + '_enabled_input_' + action_num).checked = true;
						this.settings['input_' + action_num]['enabled'] = true;
					}
				}
				// Seccond action //
				else {
					// Enabled (data[6]) //
					if (data[6] == '1') {
						document.getElementById(this.holder_id + '_enabled_input_' + action_num).checked = true;
						this.settings['input_' + action_num]['enabled'] = true;
					}
					else {
						document.getElementById(this.holder_id + '_enabled_input_' + action_num).checked = false;
						this.settings['input_' + action_num]['enabled'] = false;
					}
				}
				// Rel Num (data[7]) //
				document.getElementById(this.holder_id + '_rel_num_input_' + action_num).value = data[7];
				this.settings['input_' + action_num]['rel_num'] = data[7];
				// Action (data[8]) //
				document.getElementById(this.holder_id + '_action_input_' + action_num).value = data[8];
				this.settings['input_' + action_num]['action'] = data[8];
				// But/Key (data[9]) //
				document.getElementById(this.holder_id + '_but_switch_input_' + action_num).value = data[9];
				this.settings['input_' + action_num]['but_switch'] = data[9];
				// Inversion (data[10]) //
				if (data[10] == '1') {
					document.getElementById(this.holder_id + '_inversion_input_' + action_num).checked = true;
					this.settings['input_' + action_num]['inversion'] = true;
				}
				else {
					document.getElementById(this.holder_id + '_inversion_input_' + action_num).checked = false;
					this.settings['input_' + action_num]['inversion'] = false;
				}
				// Ignore (data[11]) //
				document.getElementById(this.holder_id + '_ignore_input_' + action_num).value = data[11];
				this.settings['input_' + action_num]['ignore'] = data[11];
				// Ignore By (data[12]) //
				document.getElementById(this.holder_id + '_ignore_by_input_' + action_num).value = data[12];
				this.settings['input_' + action_num]['ignore_by'] = data[12];
			}
			//   Termo   //
			else if (data[4] == 'T') {
				action_num = data[5];
				// Enabled (data[6]) //
				if (data[6] == '1') {
					document.getElementById(this.holder_id + '_enabled_termo_' + action_num).checked = true;
					this.settings['termo_' + action_num]['enabled'] = true;
				}
				else {
					document.getElementById(this.holder_id + '_enabled_termo_' + action_num).checked = false;
					this.settings['termo_' + action_num]['enabled'] = false;
				}
				// Temp Num (data[7]) //
				document.getElementById(this.holder_id + '_termo_num_termo_' + action_num).value = data[7];
				this.settings['termo_' + action_num]['termo_num'] = data[7];
				// Min temp (data[8-9]) //
				var min_temp = data[8] + data[9];
				document.getElementById(this.holder_id + '_t_min_termo_' + action_num).value = min_temp;
				this.settings['termo_' + action_num]['t_min'] = min_temp;
				// Max temp (data[10-11]) //
				var max_temp = data[10] + data[11];
				document.getElementById(this.holder_id + '_t_max_termo_' + action_num).value = max_temp;
				this.settings['termo_' + action_num]['t_max'] = max_temp;
				// Rel Num (data[12]) //
				document.getElementById(this.holder_id + '_rel_num_termo_' + action_num).value = data[12];
				this.settings['termo_' + action_num]['rel_num'] = data[12];
				// Inversion (data[13]) //
				if (data[13] == '1') {
					document.getElementById(this.holder_id + '_inversion_termo_' + action_num).checked = true;
					this.settings['termo_' + action_num]['inversion'] = true;
				}
				else {
					document.getElementById(this.holder_id + '_inversion_termo_' + action_num).checked = false;
					this.settings['termo_' + action_num]['inversion'] = false;
				}
			}
		}
	}
	// --------------------- Save Settings --------------------- //
	this.SaveSettingsInput = function () {
		inputs_html = '';
		for (var x=1; devices[0].settings['input_' + x]; x++) {
			//inputs_html += ToJson(devices[0].settings['input_' + x]);
			var enabled = this.settings['input_' + x]['enabled'];
			if (x < 5) enabled = !enabled;
			if (enabled == true) enabled='1';
			else enabled = '0';
			var inversion = this.settings['input_' + x]['inversion'];
			if (inversion == true) inversion='1';
			else inversion = '0';
			// Rel Num (data[7]) //
			// Action (data[8]) //
			// But/Key (data[9]) //
			// Inversion (data[10]) //
			// Ignore (data[11]) //
			// Ignore By (data[12]) //
			var command = '$' +
				this.adress + 'wB' + x +
				enabled +
				this.settings['input_' + x]['rel_num'] +
				this.settings['input_' + x]['action'] +
				this.settings['input_' + x]['but_switch'] +
				inversion +
				this.settings['input_' + x]['ignore'] +
				this.settings['input_' + x]['ignore_by'] +
				';';
			inputs_html += command;
			AddCommand(command);
		}
		//alert(inputs_html);
		if (confirm("Сохранить настройки входов?")) SendCommands();
		else ClearCommands();
	}
	this.SaveSettingsTermo = function () {
		inputs_html = '';
		for (var x=0; this.settings['termo_' + x]; x++) {
			//inputs_html += ToJson(devices[0].settings['termo_' + x]);
			var enabled = this.settings['termo_' + x]['enabled'];
			if (enabled == true) enabled='1';
			else enabled = '0';
			var inversion = this.settings['termo_' + x]['inversion'];
			if (inversion == true) inversion='1';
			else inversion = '0';
			var command = '$' +
				this.adress + 'wT' + x +
				enabled +
				this.settings['termo_' + x]['termo_num'] +
				this.settings['termo_' + x]['t_min'] +
				this.settings['termo_' + x]['t_max'] +
				this.settings['termo_' + x]['rel_num'] +
				inversion +
				';';
			inputs_html += command;
			AddCommand(command);
		}
		//alert(inputs_html);
		if (confirm("Сохранить настройки термореле?")) SendCommands();
		else ClearCommands();
	}
};
