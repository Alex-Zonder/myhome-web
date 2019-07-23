function DrawSystemInfo (data,action) {
	var parsed=FromJson(data);

	if (action=='sys_info') {
		data="Система: "+parsed['system_OS']+"<br>UpTime: "+parsed['uptime']+"";
		data+="<br>Пользователи: "+parsed['users']+"<br>Averages: "+parsed['averages'];
	}

	if (action=='cpu') {
		data="<div style=\"width:100%;background:teal;text-align:left;\">"+
			"<hr color='orange' style=\"width:"+parsed['load']+"%;margin: 9px 0 8px 0;height:3px;\">"+
			"</div>";
		data+="Нагрузка: "+parsed['load']+" %<br>Свободно: "+parsed['free']+" %";
	}
	else if (action=='network') {
		data="Интерфейс: "+parsed['iface']+"<br>Вход: "+parsed['in']+" Мб"+"<br>Выход: "+parsed['out']+" Мб";
	}

	document.getElementById(action).innerHTML=data;
}