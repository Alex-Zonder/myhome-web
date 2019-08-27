function DrawSystemInfo (data, action) {
	var parsed = FromJson(data);

	// System info //
	if (action=='sys_info') {
		data = '<table>';
		data += '<tr><td width="50%">Имя</td><td align="right">' + parsed['system_name'] + '</td></tr>';
		data += '<tr><td width="50%">Система</td><td align="right">' + parsed['system_OS'] + '</td></tr>';
		data += '<tr><td width="50%">Время</td><td align="right">' + parsed['date'] + '</td></tr>';
		data += '<tr><td width="50%">UpTime</td><td align="right">' + parsed['uptime'] + '</td></tr>';
		data += '<tr><td width="50%">Пользователи</td><td align="right">' + parsed['users'] + '</td></tr>';
		data += '<tr><td width="50%">Averages</td><td align="right">' + parsed['averages'] + '</td></tr>';
		data += '</table>';
	}

	// Cpu load //
	if (action=='cpu') {
		data="<div style=\"width:100%;background:teal;text-align:left;\">"+
			"<hr color='orange' style=\"width:"+parsed['load']+"%;margin: 9px 0 8px 0;height:3px;\">"+
			"</div>";
		data+="Нагрузка: "+parsed['load']+" %<br>Свободно: "+parsed['free']+" %";
	}

	// Network load //
	if (action=='network') {
		data = "Интерфейс: "+parsed['iface']+"<br>Вход: "+parsed['in']+" Мб"+"<br>Выход: "+parsed['out']+" Мб";
	}

	// System Disks //
	if (action=='disks') {
		data = '<table><tr>' +
			'<th>Диск</th>' +
			'<th>Объем</th>' +
			'<th>Занято</th>' +
			'<th>Свободно</th>' +
			'<th>%</th>' +
			'<tr>';
		for (x=0; x<parsed.length; x++) {
			data += '<tr><td align="center">' + parsed[x]['name'] + '</td>' +
				'<td align="center">' + parsed[x]['size'] + '</td>' +
				'<td align="center">' + parsed[x]['used'] + '</td>' +
				'<td align="center">' + parsed[x]['free'] + '</td>' +
				'<td align="center">' + parsed[x]['used_per'] + '</td>' +
				'</tr>';
		}
		data += '</table>';
	}


	// System Ips //
	if (action=='system_ips') {
		var data = '<table><tr><th width="50%">IP - адрес</th><th>Маска подсети</th>';
		for (x=0; x<parsed.length; x++) {
			data += '<tr><td width="50%">' + parsed[x]['ip'] + '</td><td align="right">' + parsed[x]['netmask'] + '</td></tr>';
		}
		data += '</table>';
	}


	if (document.getElementById(action) != null)
		document.getElementById(action).innerHTML=data;
}
