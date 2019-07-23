var IP_CAM = function (id,holder_id,settings) {
	Blank_Dev.apply(this, arguments);

	this.id = id;
	this.holder_id = holder_id;
	this.holder_div = document.getElementById(this.holder_id);
	this.settings = FromJson(ToJson(settings));
	this.settings["type_name"] = "Камера";


	//-------------------------------- M Y H O M E --------------------------------//
	this.CreateMain = function (){
		if (this.settings['values']['host'] == "" || this.settings['values']['host'] == "localhost")
			this.settings['values']['host']=window.location.hostname;

		//    ---      D R A W     ---     //
		var address = "http://" +
			this.settings['values']['host'] +
			":" + this.settings['values']['port'] +
			"/" + this.settings['values']['link'];

		var html = '<img src="' + address + "?" + GetTime() + '" width="100%">';
		this.holder_div.innerHTML += html;
	};




	//-------------------------------- S E T T I N G S --------------------------------//
	this.CreateSetts_Dev = function () {
		var html = "<hr><center>";

		// Адрес //
		html += '<table>' +
				'<tr>' +
					'<td width="50%">Сервер</td>' +
					'<td align="right">' + this.DrawInput('host','values') + '</td>' +
				'</tr>' +
				'<tr>' +
					'<td width="50%">Порт</td>' +
					'<td align="right">' + this.DrawInput('port','values') + '</td>' +
				'</tr>' +
				'<tr>' +
					'<td width="50%">Ссылка</td>' +
					'<td align="right">' + this.DrawInput('link','values') + '</td>' +
				'</tr>' +
			'</table>'

		html += "</center>"
		//html += ToJson(this.settings['values']);
		this.holder_div.innerHTML += html;
	};




	//-------------------------------- D E V I C E   S E T T I N G S --------------------------------//
	this.Create_Device_Setts_Dev = function () {
		this.Create_Device_Setts_Head();

		this.setting_div = document.createElement('div');
		this.holder_div.appendChild(this.setting_div);

		var html = '<hr><table>' +
			'<tr><td align="center"><div style="padding:10px;"><b>Настройки устройства недоступны</b></div></td></tr>' +
			'</table>';

		this.setting_div.innerHTML = html;
	}
};
