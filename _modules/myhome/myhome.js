// ____________________________   I N I T   ____________________________ //
var devices = [];
var CreateDevice = function (dev_id, group_id, count_in_group) {
	id = FoundDeviceId(dev_id);
	// Founded && Enabled //
	if (id >= 0 && myhome_setts['devices'][id]['enabled']) {
		// Rights //
		if (myhome_setts['devices'][id]['rights'] == "" || myhome_setts['devices'][id]['rights'] == user["group"]) {
			//holder_div = document.getElementById("Group_Div_" + group_id);
			holder_div = moved_myhome.objects[group_id].holder_div;

			var newDev = document.createElement('div');
			newDev.id = group_id + "Device_Div_" + String(id) + String(count_in_group);
			newDev.className = "info_block";
			newDev.style.textAlign = "center";
			newDev.innerHTML = "<div class='info_block_name'>" + myhome_setts['devices'][id]['name'] + "</div>";
			holder_div.appendChild(newDev);

			devices[group_id].push([]);
			switch (myhome_setts['devices'][id]['type']) {
				case "RM-4":
					devices[group_id][count_in_group] = new RM_4 (group_id+"_"+count_in_group,newDev.id,myhome_setts['devices'][id]);
					//devices[group_id][count_in_group].__proto__ = new Blank_Dev();
					devices[group_id][count_in_group].CreateMain ();
					break;
				case "I-16":
					devices[group_id][count_in_group] = new I_16 (group_id+"_"+count_in_group,newDev.id,myhome_setts['devices'][id]);
					//devices[group_id][count_in_group].__proto__ = new Blank_Dev();
					devices[group_id][count_in_group].CreateMain ();
					break;
				case "IP_CAM":
					devices[group_id][count_in_group] = new IP_CAM (group_id+"_"+count_in_group,newDev.id,myhome_setts['devices'][id]);
					//devices[group_id][count_in_group].__proto__ = new Blank_Dev();
					devices[group_id][count_in_group].CreateMain ();
					break;
			}
		}
	}
}
var CreateGroup = function (id) {
	devices.push([]);
	// Rights //
	if (myhome_setts['groups'][id]['rights'] == "" || myhome_setts['groups'][id]['rights'] == user["group"]) {
		moved_myhome.Add_Object(myhome_setts['groups'][id]['name']);
		// Create Devs //
		for (var x=0; x < myhome_setts['groups'][id]['devs_id'].length; x++) {
			CreateDevice(myhome_setts['groups'][id]['devs_id'][x],id,x);
		}
	}
}
var GreateGroups = function () {
	for (var x=0; myhome_setts['groups'][x]; x++) {
		if (myhome_setts['groups'][x]['enabled']) CreateGroup(x);
	}
}


var moved_myhome = [];
var Init_Myhome = function () {
	moved_myhome = new Moved_Divs ('moved_myhome',{
		'holders':{'menu':'block_top_menu_holder','divs':'Groups_Holder'},
		'buts':{'but':'block_top_menu_but','pushed':'block_top_menu_but_pushed'}
	});

	GreateGroups();
	setTimeout("ViewGroup(myhome_setts['main']['first_group'])", 1);
	body_footer_opened = myhome_setts['main']['view_console'] || false;

	setTimeout("SendCommands();", 20);
}







// ____________________________   M Y H O M E   C O M M A N D S   ____________________________ //
function CommandsSent(){
	EnableMyhomeWait();
}
function MakeCommands (returned) {
	var commands = returned.split(";");
	for (var x=0; commands[x]; x++) MakeCommand(commands[x]);
}
function MakeCommand (returned) {
	if (returned[0] == "#") {
		var address = returned[1] + returned[2];

		// By Dvices //
		for (var g_count=0; g_count<devices.length; g_count++) {
			for (var d_count=0; d_count<devices[g_count].length; d_count++) {
				if (devices[g_count][d_count].adress == address && typeof devices[g_count][d_count].MakeCommand == 'function') {
					devices[g_count][d_count].MakeCommand (returned);
				}
			}
		}
	}
}
//   Myhome Make Answer   //
function MyhomeReturned(command,myhome_answer){
	MakeCommands (myhome_answer["answer"]);
}
