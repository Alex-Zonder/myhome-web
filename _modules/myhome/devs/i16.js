var I_16 = function (id,holder_id,settings) {
	Blank_Dev.apply(this, arguments);

	this.id = id;
	this.holder_id = holder_id;
	this.holder_div = document.getElementById(this.holder_id);
	this.settings = FromJson(ToJson(settings));
	this.settings["type_name"] = "Модуль расширения";

	this.adress = String(this.settings["values"]['address']);
	if (parseInt(this.adress)<10) this.adress = "0" + parseInt(this.adress);


	this.inputs = [0];
	this.outputs = [0];
	this.temps = [0];
	this.analogs = [0];

	//-------------------------------- M Y H O M E --------------------------------//
	this.CreateMain = function (){
		//     ---     Add Command    ---      //
		AddCommandNoDouble("$" + this.adress + "Z;");


		//    ---      D R A W     ---     //
		var html = '';

		// Inputs //
		for (var x=1; x<=this.settings["values"]["inputs_view"]; x++) {
			var name = this.settings["values"]["inp_" + (x)];
			var io_num = x;
			if (parseInt(io_num) < 10) io_num = "0" + parseInt(io_num);
			var command = '$' + this.adress + 'O' + io_num + 'A;';

			html += '<div class="IO_Holder"><div class="IO_Content_Holder"><div class="IO_Holder_Name">' +
				name +
				'</div>' +
				'<div class="IO_Holder_IO" onclick="MyhomeSendCommand(\'' + command + '\',MyhomeReturned);">' +
				'<div id="' + this.id + this.adress + x + '"></div>' +
				'</div>' +
				'</div></div>';
		}

		// Temps //
		if (this.settings["values"]["temp_view"]>0) {
			html += "<hr>";
			for (var x=1; x<=this.settings["values"]["temp_view"]; x++) {
				var name = this.settings["values"]["temp_" + (x)];
				var io_num = x + 10;
				var command = "$" + this.adress + "T" + io_num + ";";

				html += '<div class="IO_Holder"><div class="IO_Content_Holder"><div class="IO_Holder_Name">' +
					name +
					'</div>' +
					'<div class="IO_Holder_IO" onclick="MyhomeSendCommand(\'' + command + '\',MyhomeReturned)">' +
					'<div  id="T' + this.id + this.adress + io_num + '"></div>' +
					'</div>' +
					'</div></div>';

				AddCommandNoDouble(command);
			}
		}

		// A2D //
		if (this.settings["values"]["a2d_view"]>0) {
			html += "<hr>";
			for (var x=1; x<=this.settings["values"]["a2d_view"]; x++) {
				var name = this.settings["values"]["a2d_" + (x)];
				var io_num = x + 12;
				var command = "$" + this.adress + "A" + io_num + ";";

				html += '<div class="IO_Holder"><div class="IO_Content_Holder"><div class="IO_Holder_Name">' +
					name +
					'</div>' +
					'<div class="IO_Holder_IO" onclick="MyhomeSendCommand(\'' + command + '\',MyhomeReturned)">' +
					'<div  id="A' + this.id + this.adress + io_num + '"></div>' +
					'</div>' +
					'</div></div>';

				AddCommandNoDouble(command);
			}
		}

		this.holder_div.innerHTML += html;


		//      ---    Init Vars     ---     //
		// Reley //
		/*for (var x=1; x<5; x++) {
			var io_num = x;
			this.outputs[x] = document.getElementById(this.id + this.adress + io_num);
		}*/
		// Input //
		for (var x=1; x<=this.settings["values"]["inputs_view"]; x++) {
			var io_num = x;
			this.inputs[x] = document.getElementById(this.id + this.adress + io_num);
			this.outputs[x] = document.getElementById(this.id + this.adress + io_num);
		}
		// Temp //
		for (var x=1; x<=this.settings["values"]["temp_view"]; x++) {
			var io_num = x + 10;
			this.temps[x] = document.getElementById('T' + this.id + this.adress + io_num);
		}
		// Analog //
		for (var x=1; x<=this.settings["values"]["a2d_view"]; x++) {
			var io_num = x + 12;
			this.analogs[x] = document.getElementById('A' + this.id + this.adress + io_num);
		}
	};





	this.MakeCommand = function (command) {
		if (command[3] == "Z" && command[5] == "I") {
			for (var x=1; x<=16; x++) {
				var io_num = x;
				if (x <= 10 && command[x + 9] == '1')
					this.ChangeOutput (io_num,command[x + 21]);
				else
					this.ChangeInput (io_num,command[x + 21]);
			}
		}
		if (command[3] == "I") {
			var o_num = parseInt(command[4] + command[5]);
			var state = command[7];
			this.ChangeInput (o_num,state);
		}
		if (command[3] == "O") {
			var o_num = parseInt(command[4] + command[5]);
			var state = command[7];
			this.ChangeOutput (o_num,state);
		}
		if (command[3] == "T" && command[4] == "1") {
			var io_num = command[4] + command[5];
			var value = '';
			for (var x=7; x < command.length; x++) value += command[x];
			this.temps[parseInt(io_num)-10].innerHTML = value;
		}
		if (command[3] == "A" && command[4] == "1") {
			var io_num = command[4] + command[5];
			var value = '';
			for (var x=7; x < command.length; x++) value += command[x];
			//WriteIO("A" + address + io_num, value);
			this.analogs[parseInt(io_num)-12].innerHTML = value;
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

		// Порты //
		if (!this.settings['values']['inputs_view'])
			this.settings['values']['inputs_view'] = 16;
		html += '<table>' +
				'<tr>' +
					'<td width="50%">Отображать порты</td>' +
					//'<td align="right">' + this.DrawInput('inputs_view','values') + '</td>' +
					'<td align="right">' + this.AddOption('inputs_view',16) + '</td>' +
				'</tr>' +
			'</table>'

		html += '<table>';
		for (var x=1; x<17; x++) {
			if (!this.settings['values']['inp_' + x])
				this.settings['values']['inp_' + x] = 'Порт ' + x;
			html += '<tr>' +
					'<td width="50%">Порт ' + x + '</td>' +
					'<td align="right">' + this.DrawInput('inp_' + x,'values') + '</td>' +
				'</tr>';
		}
		html += '</table>';

		// Градусники //
		if (!this.settings['values']['temp_view'])
			this.settings['values']['temp_view'] = 4;
		html += '<table>' +
				'<tr>' +
					'<td width="50%">Отображать градусники</td>' +
					//'<td align="right">' + this.DrawInput('temp_view','values') + '</td>' +
					'<td align="right">' + this.AddOption('temp_view',4) + '</td>' +
				'</tr>' +
			'</table>'

		html += '<table>';
		for (var x=1; x<5; x++) {
			if (!this.settings['values']['temp_' + x])
				this.settings['values']['temp_' + x] = 'Градусник ' +  x;
			html += '<tr>' +
					'<td width="50%">Порт ' + (x + 10) + '</td>' +
					'<td align="right">' + this.DrawInput('temp_' + x,'values') + '</td>' +
				'</tr>';
		}
		html += '</table>';

		// А Ц П //
		if (!this.settings['values']['a2d_view'])
			this.settings['values']['a2d_view'] = 4;
		html += '<table>' +
				'<tr>' +
					'<td width="50%">Отображать аналоговые показатели</td>' +
					//'<td align="right">' + this.DrawInput('a2d_view','values') + '</td>' +
					'<td align="right">' + this.AddOption('a2d_view',4) + '</td>' +
				'</tr>' +
			'</table>'

		html += '<table>';
		for (var x=1; x<5; x++) {
			if (!this.settings['values']['a2d_' + x])
				this.settings['values']['a2d_' + x] = 'Аналог ' +  x;
			html += '<tr>' +
					'<td width="50%">Порт ' + (x + 12) + '</td>' +
					'<td align="right">' + this.DrawInput('a2d_' + x,'values') + '</td>' +
				'</tr>';
		}
		html += '</table>';

		this.holder_div.innerHTML += html;

		document.getElementById(this.holder_id + '_select_inputs_view').value = this.settings['values']['inputs_view'];
		document.getElementById(this.holder_id + '_select_temp_view').value = this.settings['values']['temp_view'];
		document.getElementById(this.holder_id + '_select_a2d_view').value = this.settings['values']['a2d_view'];
	};


	this.AddOption = function (id, max_val) {
		var option = '';
		for (var x=0; x < max_val; x++){
			if (x % 2 == 1) x++;
			option += '<option value="' + x + '">' + x + '</option>';
		}
		var html = '<select id="' + this.holder_id + '_select_' + id + '"' +
				' onchange="devices[' + this.id + '].settings[\'values\'][\'' + id + '\'] = ' + this.holder_id + '_select_' + id + '.value;"' +
				'>' +
					option +
			'</select>';
		return html;
	}




	//-------------------------------- D E V I C E   S E T T I N G S --------------------------------//
	this.Create_Device_Setts_Inputs = function () {
		var inputs_div = document.createElement('div');
		this.moved_devices_settings.objects[0].holder_div.appendChild(inputs_div);

		var html = '<center><input type="button" style="margin-top:10px;" value="Сохранить насатройки портов" onclick="devices[0].SaveSettingsPorts()" /></center>';
		for (var x=1; x < 17; x++) {
			this.settings['port_' + x] = {
				'inp_or_out':'0',
				'port_but_or_sw':'0',
				'port_inversion':false,
				'port_to_out':'0',
				'port_action':'0',

				'out_ignore':'0',
				'out_ignore_by':'0',
				'out_return_to':'0',
				'out_return_timer':'0',

				'a2d_min':'0',
				'a2d_max':'0',
				'a2d_pwm':false,
			};

			// Head //
			html += '<hr><b>Порт ' + x;
			if (x < 11) html += ' (Вход / Выход)';
			else if (x < 13) html += ' (Вход / Градусник)';
			else if (x < 15) html += ' (Вход / Градусник / АЦП)';
			else html += ' (Вход / АЦП)';
			html += '</b>';
			// Inputs or output //
			if (x < 11) {
				html += '<table><tr>' +
						'<td width="50%">Вход / Выход</td>' +
						//'<td align="center">' + this.DrawInput('inp_or_out','port_' + x) + '</td>' +
						'<td align="center">' +
							'<select id="' + this.holder_id + '_inp_or_out_' + 'port_' + x + '" onchange="devices[' + this.id + '].settings[\'port_' + x + '\'][\'inp_or_out\'] = this.value;">' +
								'<option value="0">Вход</option>' +
								'<option value="1">Выход</option>' +
							'</select>' +
						'</td>' +
					'</tr></table>';
			}
			// A2D settings //
			if (x > 12) {
				html += '<table><tr>' +
						'<th colspan="2">Настройки АЦП</th>' +
					'</tr><tr>' +
						'<td width="50%">Уровень перехода (мин)</td>' +
						'<td align="center">' + this.DrawInput('a2d_min','port_' + x) + '</td>' +
					'</tr><tr>' +
						'<td width="50%">Уровень перехода (макс)</td>' +
						'<td align="center">' + this.DrawInput('a2d_max','port_' + x) + '</td>' +
					'</tr></table>';
			}
			// Inputs settings //
			html += '<table><tr>' +
					'<th colspan="2">Настройки входа</th>' +
				'</tr><tr>' +
					'<td width="50%">Кнопка / Выключатель</td>' +
					//'<td align="center">' + this.DrawInput('port_but_or_sw','port_' + x) + '</td>' +
					'<td align="center">' +
						'<select id="' + this.holder_id + '_port_but_or_sw_' + 'port_' + x + '" onchange="devices[' + this.id + '].settings[\'port_' + x + '\'][\'port_but_or_sw\'] = this.value;">' +
							'<option value="0">Кнопка</option>' +
							'<option value="1">Выключатель</option>' +
						'</select>' +
					'</td>' +
				'</tr><tr>' +
					'<td width="50%">Инверсия</td>' +
					'<td align="center">' + this.DrawCheckbox('port_inversion','port_' + x) + '</td>' +
				'</tr><tr>' +
					'<td width="50%">Привязка к выходу</td>' +
					//'<td align="center">' + this.DrawInput('port_to_out','port_' + x) + '</td>' +
					'<td align="center">' +
						'<select id="' + this.holder_id + '_port_to_out_' + 'port_' + x + '" onchange="devices[' + this.id + '].settings[\'port_' + x + '\'][\'port_to_out\'] = this.value;">';
							html += '<option value="0">Отсутствует</option>';
							for (var i = 1; i < 11; i++) {
								if (i != x) html += '<option value="' + i + '">' + i + '</option>';
							}
						html += '</select>' +
					'</td>' +
				'</tr><tr>' +
					'<td width="50%">Действие</td>' +
					//'<td align="center">' + this.DrawInput('port_action','port_' + x) + '</td>';
					'<td align="center">' +
						'<select id="' + this.holder_id + '_port_action_' + 'port_' + x + '" onchange="devices[' + this.id + '].settings[\'port_' + x + '\'][\'port_action\'] = this.value;">' +
							'<option value="0">Оба</option>' +
							'<option value="1">Только включение</option>' +
							'<option value="2">Только выключение</option>' +
						'</select>' +
					'</td>';
			if (x > 12) {
				html += '</tr><tr>' +
					'<td width="50%">ШИМ (выходы: 2,4,5,8,9,10)</td>' +
					'<td align="center">' + this.DrawCheckbox('a2d_pwm','port_' + x) + '</td>';
			}
			html += '</tr></table>';
			// Outpus settings //
			if (x < 11) {
				html += '<table><tr>' +
						'<th colspan="2">Настройки выхода</th>' +
					'</tr><tr>' +
						'<td width="50%">Игнорировать (по порту)</td>' +
						//'<td align="center">' + this.DrawInput('out_ignore','port_' + x) + '</td>' +
						'<td align="center">' +
							'<select id="' + this.holder_id + '_out_ignore_' + 'port_' + x + '" onchange="devices[' + this.id + '].settings[\'port_' + x + '\'][\'out_ignore\'] = this.value;">';
								html += '<option value="0">Выключено</option>';
								for (var i = 1; i < 17; i++) {
									//if (i != x)
									html += '<option value="' + i + '">' + i + '</option>';
								}
							html += '</select>' +
						'</td>' +
					'</tr><tr>' +
						'<td width="50%">Игнорировать (статус порта)</td>' +
						//'<td align="center">' + this.DrawInput('out_ignore_by','port_' + x) + '</td>' +
						'<td align="center">' +
							'<select id="' + this.holder_id + '_out_ignore_by_' + 'port_' + x + '" onchange="devices[' + this.id + '].settings[\'port_' + x + '\'][\'out_ignore_by\'] = this.value;">' +
								'<option value="0">Замкнут / Выключен</option>' +
								'<option value="1">Разомкнут / Включен</option>' +
							'</select>' +
						'</td>' +
					'</tr><tr>' +
						'<td width="50%">Возврат (в состояние)</td>' +
						//'<td align="center">' + this.DrawInput('out_return_to','port_' + x) + '</td>' +
						'<td align="center">' +
							'<select id="' + this.holder_id + '_out_return_to_' + 'port_' + x + '" onchange="devices[' + this.id + '].settings[\'port_' + x + '\'][\'out_return_to\'] = this.value;">' +
								'<option value="0">Отключено</option>' +
								'<option value="1">Включен</option>' +
								'<option value="2">Выключен</option>' +
							'</select>' +
						'</td>' +
					'</tr><tr>' +
						'<td width="50%">Возврат (через)</td>' +
						'<td align="center">' + this.DrawInput('out_return_timer','port_' + x) + '</td>' +
					'</tr></table>';
			}

			var port_num = x;
			if (port_num < 10) port_num = "0" + port_num;
			AddCommand('$' + this.adress + 'P' + port_num + ';');
		}

		html += '<hr><center><input type="button" style="margin-bottom:10px;" value="Сохранить насатройки портов" onclick="devices[0].SaveSettingsPorts()" /></center>';
		//html += "<hr>$" + this.adress + "P01;";
		inputs_div.innerHTML = html;
	}
	this.Create_Device_Setts_Termo = function () {
		var termo_div = document.createElement('div');
		this.moved_devices_settings.objects[1].holder_div.appendChild(termo_div);

		var html = '<center><input type="button" style="margin-top:10px;" value="Сохранить насатройки термореле" onclick="devices[0].SaveSettingsTermos()" /></center>';
		for (var x=1; x < 5; x++) {
			this.settings['termo_' + x] = {
				'port':'0',
				'send_temp':'0',

				'out_port':'0',
				'out_inversion':false,
				'out_action':'0',

				'temp_min':'0',
				'temp_max':'0',
				'temp_pwm':false,
			};

			html += '<hr>Условие ' + x + '<table><tr>' +
					'<td width="50%">Термометр</td>' +
					//'<td align="center">' + this.DrawInput('port','termo_' + x) + '</td>' +
					'<td align="center">' +
						'<select id="' + this.holder_id + '_port_' + 'termo_' + x + '" onchange="devices[' + this.id + '].settings[\'termo_' + x + '\'][\'port\'] = this.value;">' +
							'<option value="00">Отключено</option>' +
							'<option value="11">Порт 11</option>' +
							'<option value="12">Порт 12</option>' +
							'<option value="13">Порт 13</option>' +
							'<option value="14">Порт 14</option>' +
						'</select>' +
					'</td>' +
				'</tr><tr>' +
					'<td width="50%">Отправлять Т каждые (1 - 999) * 2 сек.</td>' +
					'<td align="center">' + this.DrawInput('send_temp','termo_' + x) + '</td>' +
				'</tr><tr>' +
					'<td width="50%">Температура (мин)</td>' +
					'<td align="center">' + this.DrawInput('temp_min','termo_' + x) + '</td>' +
				'</tr><tr>' +
					'<td width="50%">Температура (макс)</td>' +
					'<td align="center">' + this.DrawInput('temp_max','termo_' + x) + '</td>' +
				'</tr><tr>' +
					'<td width="50%">Привязка к выходу</td>' +
					//'<td align="center">' + this.DrawInput('out_port','termo_' + x) + '</td>' +
					'<td align="center">' +
						'<select id="' + this.holder_id + '_out_port_' + 'termo_' + x + '" onchange="devices[' + this.id + '].settings[\'termo_' + x + '\'][\'out_port\'] = this.value;">';
							html += '<option value="0">Отсутствует</option>';
							for (var i = 1; i < 11; i++) {
								html += '<option value="' + i + '">' + i + '</option>';
							}
						html += '</select>' +
					'</td>' +
				'</tr><tr>' +
					'<td width="50%">Инверсия</td>' +
					'<td align="center">' + this.DrawCheckbox('out_inversion','termo_' + x) + '</td>' +
				'</tr><tr>' +
					'<td width="50%">Действие</td>' +
					//'<td align="center">' + this.DrawInput('out_action','termo_' + x) + '</td>' +
					'<td align="center">' +
						'<select id="' + this.holder_id + '_out_action_' + 'termo_' + x + '" onchange="devices[' + this.id + '].settings[\'termo_' + x + '\'][\'out_action\'] = this.value;">' +
							'<option value="0">Оба</option>' +
							'<option value="1">Только включение</option>' +
							'<option value="2">Только выключение</option>' +
						'</select>' +
					'</td>' +
				'</tr><tr>' +
					'<td width="50%">ШИМ (выходы: 2,4,5,8,9,10)</td>' +
					'<td align="center">' + this.DrawCheckbox('temp_pwm','termo_' + x) + '</td>' +
			'</tr></table>';

			AddCommand('$' + this.adress + 'TS' + x + ';');
		}

		html += '<hr><center><input type="button" style="margin-bottom:10px;" value="Сохранить насатройки термореле" onclick="devices[0].SaveSettingsTermos()" /></center>';
		//html += "<hr>$" + this.adress + "TS1;";
		termo_div.innerHTML = html;
	}

	this.Create_Device_Setts_Commands = function () {
		var commands_div = document.createElement('div');
		this.moved_devices_settings.objects[2].holder_div.appendChild(commands_div);

		var html = '<center><input type="button" style="margin-top:10px;" value="Сохранить насатройки команд" onclick="devices[0].SaveSettingsCommands()" /></center>';
		for (var x=1; x < 17; x++) {
			this.settings['command_' + x] = {
				'send_if_enabled':'',
				'send_if_disabled':'',

				'enable_input':'',
				'disable_input':'',
			};

			var port_inp_out_text = ' (Вход / Выход)';
			if (x > 10) port_inp_out_text = ' (Вход)';
			var port_inp_short_text = 'Замыкание / Включение';
			if (x > 10) port_inp_short_text = 'Замыкание';
			var port_inp_free_text = 'Размыкание / Выключение';
			if (x > 10) port_inp_free_text = 'Размыкание';
			html += '<hr>Порт ' + x + port_inp_out_text + '<table><tr>' +
					'<th colspan="2">Отправка</th>' +
				'</tr><tr>' +
					'<td width="50%">' + port_inp_short_text + '</td>' +
					'<td align="center">' + this.DrawInput('send_if_enabled','command_' + x) + '</td>' +
				'</tr><tr>' +
					'<td width="50%">' + port_inp_free_text + '</td>' +
					'<td align="center">' + this.DrawInput('send_if_disabled','command_' + x) + '</td>' +
			'</tr></table>';
			if (x < 11) html += '<table><tr>' +
					'<th colspan="2">Прием</th>' +
				'</tr><tr>' +
					'<td width="50%">Включать выход</td>' +
					'<td align="center">' + this.DrawInput('enable_input','command_' + x) + '</td>' +
				'</tr><tr>' +
					'<td width="50%">Выключать выход</td>' +
					'<td align="center">' + this.DrawInput('disable_input','command_' + x) + '</td>' +
			'</tr></table>';

			var first_com = ((x - 1) * 2) + 1;
			AddCommand('$' + this.adress + 'C' + IntToBytes(first_com, 2) + 'R;');
			AddCommand('$' + this.adress + 'C' + IntToBytes((first_com + 1), 2) + 'R;');
			if (x < 11) {
				AddCommand('$' + this.adress + 'C' + IntToBytes((first_com + 32), 2) + 'R;');
				AddCommand('$' + this.adress + 'C' + IntToBytes((first_com + 33), 2) + 'R;');
			}
		}

		html += '<hr><center><input type="button" style="margin-bottom:10px;" value="Сохранить насатройки команд" onclick="devices[0].SaveSettingsCommands()" /></center>';
		//html += "<hr>$" + this.adress + "C01R;";
		commands_div.innerHTML = html;
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
		html += '<hr><nobr><div class="block_top_menu" id="I_16_Buts_Holder"></div></nobr>' +
			'<div id="I_16_Settings_Holder"></div>';
		this.setting_div.innerHTML = html;

		// Add Moved_Divs //
		this.moved_devices_settings = new Moved_Divs ('devices[' + this.id + '].moved_devices_settings',{
			'holders':{'menu':'I_16_Buts_Holder','divs':'I_16_Settings_Holder'},
			'buts':{'but':'block_top_menu_but','pushed':'block_top_menu_but_pushed'}
		});
		this.moved_devices_settings.Add_Object("Порты");
		this.moved_devices_settings.Add_Object("Термореле");
		this.moved_devices_settings.Add_Object("Команды");

		this.Create_Device_Setts_Inputs();
		this.Create_Device_Setts_Termo();
		this.Create_Device_Setts_Commands();

		SendCommands();
	}

	// --------------------- Make Answer --------------------- //
	this.MakeCommandSettings = function (data) {
		// Device Version //
		if (data[3] == 'V') Device_Version.innerHTML = data;
		// Device Ports Settings //
		else if (data[3] == 'P') {
			var bytes_correct = 0;
			var port_num = parseInt(data[4] + data[5]);
			if (port_num < 11) {
				// inp_or_out (data[7]) //
				document.getElementById(this.holder_id + '_inp_or_out_port_' + port_num).value = data[7];
				this.settings['port_' + port_num]['inp_or_out'] = data[7];

				// out_ignore (data[15-16]) //
				var ignore_port = parseInt(data[15] + data[16]);
				document.getElementById(this.holder_id + '_out_ignore_port_' + port_num).value = ignore_port;
				this.settings['port_' + port_num]['out_ignore'] = ignore_port;
				// out_ignore_by (data[17]) //
				document.getElementById(this.holder_id + '_out_ignore_by_port_' + port_num).value = data[17];
				this.settings['port_' + port_num]['out_ignore_by'] = data[17];
				// out_return_to (data[19]) //
				document.getElementById(this.holder_id + '_out_return_to_port_' + port_num).value = data[19];
				this.settings['port_' + port_num]['out_return_to'] = data[19];
				// out_return_timer (data[20-23]) //
				var return_timer = parseInt(data[20]+data[21]+data[22]+data[23]);
				document.getElementById(this.holder_id + '_out_return_timer_port_' + port_num).value = return_timer;
				this.settings['port_' + port_num]['out_return_timer'] = return_timer;
			}
			else if (port_num < 13) bytes_correct = -2;
			// A2D //
			if (port_num > 12) {
				// min - max (data[7] - data[14]) //
				var min = parseInt(data[7] + data[8] + data[9] + data[10]);
				var max = parseInt(data[11] + data[12] + data[13] + data[14]);
				document.getElementById(this.holder_id + '_a2d_min_port_' + port_num).value = min;
				this.settings['port_' + port_num]['a2d_min'] = min;
				document.getElementById(this.holder_id + '_a2d_max_port_' + port_num).value = max;
				this.settings['port_' + port_num]['a2d_max'] = max;
				// a2d_pwm (data[15]) //
				var a2d_pwm = data[15];
				if (a2d_pwm == '1') a2d_pwm = true;
				else a2d_pwm = false;
				document.getElementById(this.holder_id + '_a2d_pwm_port_' + port_num).checked = a2d_pwm;
				this.settings['port_' + port_num]['a2d_pwm'] = a2d_pwm;

				bytes_correct = 17-9;
			}

			// port_to_out (data[9 - 10]) //
			var to_out = parseInt(data[9+bytes_correct] + data[10+bytes_correct]);
			document.getElementById(this.holder_id + '_port_to_out_port_' + port_num).value = to_out;
			this.settings['port_' + port_num]['port_to_out'] = to_out;
			// port_inversion (data[11]) //
			var inversion = data[11+bytes_correct];
			if (inversion == '1') inversion = true;
			else inversion = false;
			document.getElementById(this.holder_id + '_port_inversion_port_' + port_num).checked = inversion;
			this.settings['port_' + port_num]['port_inversion'] = inversion;
			// port_but_or_sw (data[12]) //
			document.getElementById(this.holder_id + '_port_but_or_sw_port_' + port_num).value = data[12+bytes_correct];
			this.settings['port_' + port_num]['port_but_or_sw'] = data[12+bytes_correct];
			// port_action (data[13]) //
			document.getElementById(this.holder_id + '_port_action_port_' + port_num).value = data[13+bytes_correct];
			this.settings['port_' + port_num]['port_action'] = data[13+bytes_correct];
		}
		// Device Termos Settings //
		else if (data[3] == 'T' && data[4] == 'S') {
			// setting_num (data[5]) //
			var setting_num = data[5];
			// port_num (data[7-8]) //
			var port_num = data[7] + data[8];
			document.getElementById(this.holder_id + '_port_termo_' + setting_num).value = port_num;
			this.settings['termo_' + setting_num]['port'] = port_num;
			// send_temp (data[10-12]) //
			var send_temp = parseInt(data[10] + data[11] + data[12]);
			document.getElementById(this.holder_id + '_send_temp_termo_' + setting_num).value = send_temp;
			this.settings['termo_' + setting_num]['send_temp'] = send_temp;
			// t_min (data[14-15]) //
			var t_min = data[14] + data[15];
			document.getElementById(this.holder_id + '_temp_min_termo_' + setting_num).value = t_min;
			this.settings['termo_' + setting_num]['temp_min'] = t_min;
			// t_max (data[16-17]) //
			var t_max = data[16] + data[17];
			document.getElementById(this.holder_id + '_temp_max_termo_' + setting_num).value = t_max;
			this.settings['termo_' + setting_num]['temp_max'] = t_max;

			// out_port (data[19-20]) //
			var out_port = parseInt(data[19] + data[20]);
			document.getElementById(this.holder_id + '_out_port_termo_' + setting_num).value = out_port;
			this.settings['termo_' + setting_num]['out_port'] = out_port;
			// out_inversion (data[21]) //
			var out_inversion = data[21];
			if (out_inversion == '1') out_inversion = true;
			else out_inversion = false;
			document.getElementById(this.holder_id + '_out_inversion_termo_' + setting_num).checked = out_inversion;
			this.settings['termo_' + setting_num]['out_inversion'] = out_inversion;
			// out_action (data[22]) //
			document.getElementById(this.holder_id + '_out_action_termo_' + setting_num).value = data[22];
			this.settings['termo_' + setting_num]['out_action'] = data[22];
			// pwm (data[23]) //
			var temp_pwm = data[23];
			if (temp_pwm == '1') temp_pwm = true;
			else temp_pwm = false;
			document.getElementById(this.holder_id + '_temp_pwm_termo_' + setting_num).checked = temp_pwm;
			this.settings['termo_' + setting_num]['temp_pwm'] = temp_pwm;
		}
		// Device Commands Settings //
		else if (data[3] == 'C') {
			var command_num = parseInt(data[4] + data[5]);
			var setts_name = '';
			var setting_num = 0;
			if (command_num < 33) {
				if (command_num%2) {
					setts_name = 'send_if_enabled';
					setting_num = (command_num + 1) / 2;
				}
				else {
					setts_name = 'send_if_disabled';
					setting_num = (command_num) / 2;
				}
			}
			else {
				if (command_num%2) {
					setts_name = 'enable_input';
					setting_num = (command_num - 31) / 2;
				}
				else {
					setts_name = 'disable_input';
					setting_num = (command_num - 32) / 2;
				}
			}
			//console.log(command_num + ' - ' + setting_num + ' - ' + setts_name);
			var command_command = '';
			for (var x=6; x < data.length; x++) command_command += data[x];
			command_command = command_command.split(':').join(';');
			document.getElementById(this.holder_id + '_' + setts_name + '_command_' + setting_num).value = command_command;
			this.settings['command_' + setting_num][setts_name] = command_command;
		}
	}
	// --------------------- Save Settings --------------------- //
	// Save Settings Ports //
	this.SaveSettingsPorts = function () {
		var inputs_html = '';
		for (var x=1; this.settings['port_' + x]; x++) {
			//inputs_html += '\n' + x + ToJson(devices[0].settings['port_' + x]);
			var input_num = x;
			if (input_num < 10) input_num = '0' + input_num;
			// input setts //
			var input_to_out = this.settings['port_' + x]['port_to_out'];
			if (input_to_out < 10) input_to_out = '0' + input_to_out;
			var port_inversion = this.settings['port_' + x]['port_inversion'];
			if (port_inversion == true) port_inversion = '1';
			else port_inversion = '0';
			var input_setts = input_to_out + port_inversion +
				this.settings['port_' + x]['port_but_or_sw'] +
				this.settings['port_' + x]['port_action'];
			// output setts //
			var ignore_port = this.settings['port_' + x]['out_ignore'];
			if (ignore_port < 10) ignore_port = '0' + ignore_port;
			var return_timer = IntToBytes(this.settings['port_' + x]['out_return_timer'], 4);
			// A2D //
			var min = IntToBytes(this.settings['port_' + x]['a2d_min'], 4);
			var max = IntToBytes(this.settings['port_' + x]['a2d_max'], 4);
			var a2d_pwm = this.settings['port_' + x]['a2d_pwm'];
			if (a2d_pwm == true) a2d_pwm = '1';
			else a2d_pwm = '0';

			var command = '$' + this.adress + 'P' + input_num + 'W:';
			// ports 1 - 10 //
			if (x < 11) command +=
				this.settings['port_' + x]['inp_or_out'] + ':' +
				input_setts + ':' +
				ignore_port + this.settings['port_' + x]['out_ignore_by'] + ':' +
				this.settings['port_' + x]['out_return_to'] + return_timer;
			// ports 11 - 12 //
			else if (x < 13) command +=
				input_setts;
			// ports 15 - 16 //
			else command +=
				min + max + a2d_pwm + ":" +
				input_setts;
			command += ';';

			inputs_html += '\n' + command;
			AddCommand(command);
		}
		//alert(inputs_html);
		if (confirm("Сохранить настройки портов?")) SendCommands();
		else ClearCommands();
	}
	// Save Settings Termos //
	this.SaveSettingsTermos = function () {
		var inputs_html = '';
		for (var x=1; this.settings['termo_' + x]; x++) {
			//inputs_html += '\n' + x + ToJson(devices[0].settings['termo_' + x]);
			var setting_num = x;
			var port_num = IntToBytes(this.settings['termo_' + setting_num]['port'], 2);
			var send_temp = IntToBytes(this.settings['termo_' + setting_num]['send_temp'], 3);
			var t_min = IntToBytes(this.settings['termo_' + setting_num]['temp_min'], 2);
			var t_max = IntToBytes(this.settings['termo_' + setting_num]['temp_max'], 2);

			var out_port = IntToBytes(this.settings['termo_' + setting_num]['out_port'], 2);
			var out_inversion = this.settings['termo_' + setting_num]['out_inversion'];
			if (out_inversion == true) out_inversion = '1';
			else out_inversion = '0';
			var out_action = this.settings['termo_' + setting_num]['out_action'];

			var temp_pwm = this.settings['termo_' + setting_num]['temp_pwm'];
			if (temp_pwm == true) temp_pwm = '1';
			else temp_pwm = '0';

			var command = '$' + this.adress + 'TW' + setting_num + ':';
			command += port_num + ':';
			command += send_temp + ':';
			command += t_min + t_max + ':';
			command += out_port + out_inversion + out_action + temp_pwm;
			command += ';';

			inputs_html += '\n' + command;
			AddCommand(command);
		}
		//if (confirm("Сохранить настройки термореле?\n" + inputs_html)) SendCommands();
		if (confirm("Сохранить настройки термореле?")) SendCommands();
		else ClearCommands();
	}
	// Save Settings Commands //
	this.SaveSettingsCommands = function () {
		var inputs_html = '';
		for (var x=1; this.settings['command_' + x]; x++) {
			inputs_html += '\n' + x + ToJson(devices[0].settings['command_' + x]);

			var first_com = ((x - 1) * 2) + 1;
			AddCommand('$' + this.adress + 'C' + IntToBytes(first_com, 2) + 'W' +
				this.settings['command_' + x]['send_if_enabled'].split(';').join(':') +
				';');
			AddCommand('$' + this.adress + 'C' + IntToBytes((first_com + 1), 2) + 'W' +
				this.settings['command_' + x]['send_if_disabled'].split(';').join(':') +
				';');
			if (x < 11) {
				AddCommand('$' + this.adress + 'C' + IntToBytes((first_com + 32), 2) + 'W' +
					this.settings['command_' + x]['enable_input'].split(';').join(':') +
					';');
				AddCommand('$' + this.adress + 'C' + IntToBytes((first_com + 33), 2) + 'W' +
					this.settings['command_' + x]['disable_input'].split(';').join(':') +
					';');
			}
		}
		//if (confirm("Сохранить настройки команд?\n" + inputs_html)) SendCommands();
		if (confirm("Сохранить настройки команд?")) SendCommands();
		else ClearCommands();
	}
};
function IntToBytes (value, len) {
	value_len = String(value).length;
	for (var x=0; x < (len - value_len); x++) value = '0' + value;
	return value;
}
