var Blank_Dev = function () {
	//-------------------------------- M Y H O M E --------------------------------//
	this.ChangeInput = function (i_num,state) {
		if (this.inputs[i_num]) {
			if (state == "1")
				this.inputs[i_num].className = "IO_Enabled";
			else
				this.inputs[i_num].className = "IO_Disabled";
		}
	};
	this.ChangeOutput = function (o_num,state) {
		if (this.outputs[o_num]) {
			if (state == "1")
				this.outputs[o_num].className = "IO_Input_Enabled";
			else
				this.outputs[o_num].className = "IO_Input_Disabled";
		}
	};




	//-------------------------------- S E T T I N G S --------------------------------//
	//          I N P U T S          //
	this.DrawInput = function (name, arr_name, arr_value){
		var arr_name = arr_name || false;
		var input_id = this.holder_id + '_' + name + '_' + arr_name;
		var port_num = name.split("_")[1];

		if (arr_name) {
			var input_var = "devices[" + this.id + "].settings['" + arr_name + "']['" + name + "']";
			var input_val = this.settings[arr_name][name] || arr_value;
		}
		else {
			var input_var = "devices[" + this.id + "].settings['" + name + "']";
			var input_val = this.settings[name] || arr_value;
		}

		return Draw_Input (input_id, input_var, input_val, "return");
	};
	this.DrawCheckbox = function (name, arr_name, arr_value){
		var arr_name = arr_name || false;
		var input_id = this.holder_id + '_' + name + '_' + arr_name;
		if (arr_name) {
			var input_var = "devices[" + this.id + "].settings['" + arr_name + "']['" + name + "']";
			var input_val = this.settings[arr_name][name] || arr_value;
		}
		else {
			var input_var = "devices[" + this.id + "].settings['" + name + "']";
			var input_val = this.settings[name];
		}

		return Draw_Checkbox (input_id, input_var, input_val, "return");
	}

	//          S E L E C T S          //
	this.Add_Select_Rights = function () {
		var select_id = this.holder_id + '_select_rights';
		var html = '<select id="' + select_id + '"' +
			' onchange="devices[' + this.id + '].settings[\'rights\'] = document.getElementById(\'' + select_id + '\').value;">' +
				'<option value="">Все</option>' +
				'<option value="admin">Администраторы</option>' +
			'</select>';
		return html;
	}

	this.DrawSettsHolder = function () {
		var html = '<center><table border="0">';
		html += '<tr><td width="50%">Имя</td><td>' + this.DrawInput('name') + '</td></tr>';
		html += '<tr><td>Включено</td><td align="center">' + this.DrawCheckbox('enabled') + '</td></tr>';
		html += '<tr><td>Доступ</td><td>' + this.Add_Select_Rights() + '</td></tr>';
		html += '</table></center>';

		this.holder_div.innerHTML += html;
	};
	this.CreateSetts = function () {
		this.DrawSettsHolder();
		if (typeof this.CreateSetts_Dev === 'function')
			this.CreateSetts_Dev();

		document.getElementById(this.holder_id + '_select_rights').value = this.settings['rights'];
	};

	this.Remove = function () {
		if (confirm("Удалить устройство: " + this.settings['name'] + "?")) {
			// Remove from Groups //
			for (var x=0; x<groups.length; x++) {
				if (typeof groups[x].RemoveGroupDevice === 'function')
					groups[x].RemoveGroupDevice(this.settings['dev_id']);
			}

			// Remove from Devices //
			this.holder_div.parentNode.removeChild(this.holder_div);

			// Remove var //
			//devices.splice(this.id,1);
			devices[this.id] = '';
		}
	}




	//-------------------------------- D E V I C E   S E T T I N G S --------------------------------//
	this.Create_Device_Setts_Head = function () {
		// Change Device Name //
		Device_Name.innerHTML = '<div style="position:relative;">' + this.settings['name'] +
			'<span style="position:absolute;right:5px;">' + this.settings['type'] + '</span></div>';

		// Create Div (Head) //
		this.holder_div.innerHTML = '';
		this.head_div = document.createElement('div');
		this.holder_div.appendChild(this.head_div);

		// Filling Head //
		var html = '<table>' +
				'<tr><td width="50%">Имя</td><td align="center">' + this.settings['name'] + '</td></tr>' +
				'<tr><td width="50%">Тип</td><td align="center">' + this.settings['type_name'] + '</td></tr>' +
			'</table>';
		this.head_div.innerHTML = html;
	}
};











var Blank_Group = function () {
	//-------------------------------- M Y H O M E --------------------------------//
	//-------------------------------- S E T T I N G S --------------------------------//
	//          I N P U T S          //
	this.DrawInput = function (name, arr_name){
		var arr_name = arr_name || false;
		var input_id = this.holder_id + '_' + name;
		if (arr_name) {
			var input_var = "groups[" + this.id + "].settings['" + arr_name + "']['" + name + "']";
			var input_val = this.settings[arr_name][name];
		}
		else {
			var input_var = "groups[" + this.id + "].settings['" + name + "']";
			var input_val = this.settings[name];
		}

		return Draw_Input (input_id, input_var, input_val, "return");
	};
	this.DrawCheckbox = function (name, arr_name){
		var arr_name = arr_name || false;
		var input_id = this.holder_id + '_' + name;
		if (arr_name) {
			var input_var = "groups[" + this.id + "].settings['" + arr_name + "']['" + name + "']";
			var input_val = this.settings[arr_name][name];
		}
		else {
			var input_var = "groups[" + this.id + "].settings['" + name + "']";
			var input_val = this.settings[name];
		}

		return Draw_Checkbox (input_id, input_var, input_val, "return");
	};

	//          S E L E C T S          //
	this.Add_Select_Rights = function () {
		var select_id = this.holder_id + '_select_rights';
		var html = '<select id="' + select_id + '"' +
			' onchange="groups[' + this.id + '].settings[\'rights\'] = document.getElementById(\'' + select_id + '\').value;">' +
				'<option value="">Все</option>' +
				'<option value="admin">Администраторы</option>' +
			'</select>';
		return html;
	}

	this.DrawSettsHolder = function () {
		var html = '<center><table border="0">';
		html += '<tr><td width="50%">Имя</td><td width="150" align="right">' + this.DrawInput('name') + '</td></tr>';
		html += '<tr><td>Включено</td><td align="center">' + this.DrawCheckbox('enabled') + '</td></tr>';
		html += '<tr><td>Доступ</td><td align="right">' + this.Add_Select_Rights() + '</td></tr>';
		html += '</table></center>';

		this.holder_div_mn_setts.innerHTML = html;

		document.getElementById(this.holder_id + '_select_rights').value = this.settings['rights'];
	};
	this.CreateSetts = function () {
		this.DrawSettsHolder();
		if (typeof this.CreateGroupDevices === 'function')
			this.CreateGroupDevices();
	};

	this.Remove = function () {
		if (confirm("Удалить группу: " + this.settings['name'] + "?")) {
			// Remove var //
			//groups.splice(this.id,1);
			groups[this.id] = '';

			// Remove from Groups Div //
			this.holder_div.parentNode.removeChild(this.holder_div);
		}
	}
}
var Group = function (id,holder_id,settings) {
	Blank_Group.apply(this);

	this.id = id;
	this.holder_id = holder_id;
	this.holder_div = document.getElementById(this.holder_id);
	this.settings = FromJson(ToJson(settings));

	this.holder_div_mn_setts = document.createElement('div');
	this.holder_div.appendChild(this.holder_div_mn_setts);
	this.holder_div_add_dev_menu = document.createElement('div');
	this.holder_div.appendChild(this.holder_div_add_dev_menu);
	this.holder_div_devices = document.createElement('div');
	this.holder_div.appendChild(this.holder_div_devices);

	// Create group (in proto) //
	this.CreateGroupSettsDiv = function() {
		this.CreateSetts();
	}
	// Filling group //
	this.devices = [];
	this.CreateGroupDevices = function () {
		this.holder_div_devices.innerHTML += '<hr>';
		// Create devices //
		this.holder_div_devices.id = this.holder_id + '_devices';

		for (var x=0; x<this.settings["devs_id"].length; x++) {
			//this.CreateGroupOneDevice(x);
			this.devices.push(new Group_Device(x,this));
		}

		// Create add device button //
		var html = '<hr><center><table><tr>' +
			'<td width="50%">Добавить устройство</td>' +
			'<td align="right">' + Add_Select_To_Class (this) + '</td>' +
			'</tr></table>'
		html += '</center>';
		this.holder_div_add_dev_menu.innerHTML += html;
	}

	// Create one device in group //
	/*this.CreateGroupOneDevice = function (x) {
		var dev_id = this.settings["devs_id"][x];
		var dev_arr_id = FindDeviceById(dev_id);

		// Adding Div //
		var new_div = document.createElement('div');
		new_div.id = this.holder_id + '_dev_' + String(dev_id) + '_' + String(x);
		this.holder_div_devices.appendChild(new_div);
		//new_div.style.padding = "2px";

		// Filling Div (vars) //
		var remove_but = '<span style="float:right;" onclick="groups[' + this.id + '].RemoveGroupDeviceByBut(' + dev_id + ',' + x + ');">Удалить</span>';
		// Filling Div (html) //
		var html = '<center>';
		html += '<table><tr>' +
			'<td width="30%">' + devices[dev_arr_id].settings["name"] + '</td>' +
			'<td width="40%" align="center">' + devices[dev_arr_id].settings["type_name"] + '</td>' +
			'<td>' + dev_id + remove_but + '</td>' + //  + dev_id
			'</tr></table>';
		html += '</center>';
		new_div.innerHTML = html;
	}*/
	// Create new device in group //
	this.CreateGroupNewDevice = function () {
		var select_id = this.holder_id + '_select';
		var dev_id = document.getElementById(select_id).value;

		if (dev_id != "") {
			// Add to Var //
			this.settings["devs_id"].push(parseInt(dev_id));
			// Add to Html //
			//this.CreateGroupOneDevice(this.settings["devs_id"].length - 1);
			this.devices.push(new Group_Device(this.settings["devs_id"].length - 1,this));
			// Clear Select //
			document.getElementById(select_id).value = '';
			this.SelectClicked();
		}
		else alert("Выберите устройство!");
	}
	// Remove device in group //
	this.RemoveOneGroupDevice = function (dev_id, x, dev_arr_id_in_group) {
		// Remove Div //
		//div_to_remove.parentNode.removeChild(div_to_remove);
		this.devices[x].RemoveDevice(x);
		this.devices.splice(x,1);
		// Remove Var //
		this.settings["devs_id"].splice(x,1);
	}
	this.RemoveGroupDevice = function (dev_id) {
		var dev_arr_id_in_group = 0;
		for (var x=0; x<this.settings["devs_id"].length; x++) {
			if (this.settings["devs_id"][x] == dev_id){
				this.RemoveOneGroupDevice (dev_id, x, dev_arr_id_in_group);
				x--;
			}
			dev_arr_id_in_group++;
		}
	}
	this.RemoveGroupDeviceByBut = function (dev_id, dev_arr_id_in_group) {
		var dev_arr_id = FindDeviceById(dev_id);
		if (confirm('Удалить: ' + devices[dev_arr_id].settings["name"] + ' из группы: ' + this.settings["name"] + '?')) {
			this.RemoveOneGroupDevice (dev_id, dev_arr_id_in_group);
		}
	}
}

// Add device to group //
function Group_Device (group_arr_id, o_this) {
	this.id = group_arr_id;
	this.dev_id = o_this.settings["devs_id"][this.id];
	this.dev_arr_id = FindDeviceById(this.dev_id);
	// Adding Div //
	this.device_div = document.createElement('div');
	this.device_div.id = o_this.holder_id + '_dev_' + String(this.dev_id) + '_' + String(this.id);
	o_this.holder_div_devices.appendChild(this.device_div);
	// Filling Div (vars) //
	var remove_but = '<span style="float:right;" onclick="groups[' + o_this.id + '].RemoveGroupDeviceByBut(' + this.dev_id + ',' + this.id + ');">Удалить</span>';
	// Filling Div (html) //
	var html = '<center>';
	html += '<table><tr>' +
		'<td width="30%">' + devices[this.dev_arr_id].settings["name"] + '</td>' +
		'<td width="40%" align="center">' + devices[this.dev_arr_id].settings["type_name"] + '</td>' +
		'<td>' + remove_but + '</td>' + //  + this.dev_id
		'</tr></table>';
	html += '</center>';
	this.device_div.innerHTML = html;

	this.RemoveDevice = function (group_arr_id) {
		//if (this.id == group_arr_id)
			this.device_div.parentNode.removeChild(this.device_div);
	}
}


// Add Select To Groups //
function Add_Select_To_Class (c_this) {
	// Fill Select onmousedown //
	c_this.SelectClicked = function () {
		var select_id = c_this.holder_id + '_select';
		var dev_id = document.getElementById(select_id).value;

		if (dev_id == "") {
			var html = '<option value="">Выберите устройство</option>';
			for (var x=0; x<devices.length; x++) {
				if (devices[x] != "")
					html += '<option value="' + devices[x].settings["dev_id"] + '" onchange="groups[' + this.id + '].CreateGroupNewDevice(' + devices[x].settings["dev_id"] + ');">' +
						devices[x].settings["name"] +
						' (' + devices[x].settings["type"] + ')</option>';
			}
			document.getElementById(select_id).innerHTML = html;
		}
	}

	var select_id = c_this.holder_id + '_select';
	var html = "";
	html += '<select id="' + select_id + '"' +
		' onmousedown="groups[' + c_this.id + '].SelectClicked();"' +
		' onchange="groups[' + c_this.id + '].CreateGroupNewDevice();">' +
			'<option value="">Выберите устройство</option>' +
		'</select>';
	return html;
}
