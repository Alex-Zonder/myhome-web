var FoundDeviceId = function (dev_id) {
	var arr_id = -1;
	for (var x=0; arr_id == -1 && x < myhome_setts['devices'].length; x++)
		if (myhome_setts['devices'][x] && dev_id == myhome_setts['devices'][x]['dev_id'])
			arr_id = x;
	return arr_id;
}

var FindDeviceById = function (dev_id) {
	var arr_id = -1;
	for (var x=0; arr_id == -1 && x < devices.length; x++)
		if (devices[x] && dev_id == devices[x].settings['dev_id'])
			arr_id = x;
	return arr_id;
}
