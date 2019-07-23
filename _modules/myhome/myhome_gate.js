var myhome_port = myhome_setts['myhome']['port'] || "1001";
var myhome_host = myhome_setts['myhome']['host'] || "http://"+window.location.hostname+":" + myhome_port + "/";
var myhome_commands_timeout = myhome_setts['myhome']['commands_timeout'] || 0;
var myhome_connection_timeout = myhome_setts['myhome']['connection_timeout'] || 2000;
var myhome_waiting_timeout = myhome_setts['myhome']['waiting_timeout'] || 600000;




//                   Check Answer                   //
function MyhomeCheckAnswer (command,myhome_answer,len) {
	var ans_right=true;
	for (var x=1; x<=len; x++) {
		if (command[x]!=myhome_answer[x]) {
			ans_right=false;
			break;
		}
	}
	return ans_right;
}
//                   Send Command                   //
var myhome_xml =  new XMLHttpRequest();
var myhome_xml_timer;
function MyhomeSendCommand (command,return_func,check_len,tryes) {
	var check_len = check_len || 2;
	var tryes = tryes || 0;
	//     Aborting     //
	if (myhome_xml.readyState != 0) {myhome_xml.abort();clearTimeout(myhome_xml_timer);}
	if (myhome_wait_enabled) StopMyhomeWait();

	//     Return     //
	myhome_xml.onreadystatechange = function() {
		if (myhome_xml.readyState == 4) {
			var status = myhome_xml.status;
			var answer = myhome_xml.responseText;
			var error = false;

			// Timeout //
			if (myhome_xml_timer!=0) {
				clearTimeout(myhome_xml_timer);
			}
			else {
				answer = "TimeOut";
				error = true;
			}

			// Network Error //
			if (answer == "") {
				status = -1;
				answer = "NetError: " + myhome_host;
				error = true;
			}

			//   Console   //
			if (typeof ConsoleWrite !== 'undefined') ConsoleWrite(command + " -> " + answer);

			// Check Answer OK //
			if (MyhomeCheckAnswer (command,answer, check_len) || error || tryes > 1) {
				//   Return   //
				var myhome_answer = {'command':command,'status':status,'answer':answer};
				if (typeof return_func !== 'undefined') return_func(command,myhome_answer);
				else alert(FromJson(myhome_answer));

				// Enable Wait //
				if (myhome_wait_was_enabled && commands.length == 0 && status > -1) EnableMyhomeWait();
			}
			// Check Answer ERROR //
			else MyhomeSendCommand (command,return_func,check_len,(tryes+1));
		}
	};

	//     Send     //
	myhome_xml.open("POST", myhome_host, true);
	myhome_xml.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	myhome_xml.send(command);
	myhome_xml_timer = setTimeout(function () {myhome_xml_timer=0; myhome_xml.abort();}, myhome_connection_timeout);
}




//                   Send Commands                   //
var commands = [];
var commands_sent = 0;
var AddCommand = function (comm) {
	commands.push(comm);
}
var AddCommandNoDouble = function (comm) {
	var command_is = false;
	for (var x=0; x<commands.length && !command_is; x++) {
		if (commands[x] == comm)
			command_is = true;
	}
	if (!command_is) AddCommand (comm);
}
var ClearCommands = function () {
	commands = [];
	commands_sent = 0;
}
function SendCommands (command,myhome_answer) {
	// If Returned //
	if (command) MyhomeReturned(command,myhome_answer);
	// Send Command //
	if (commands_sent<commands.length) {
		// Whith timeout //
		if (myhome_commands_timeout > 0) setTimeout(function (command,func_to_return) {
			MyhomeSendCommand(command,func_to_return);
		}, myhome_commands_timeout, commands[commands_sent],SendCommands);
		// No timeout //
		else MyhomeSendCommand(commands[commands_sent],SendCommands);
		commands_sent++;
	}
	// End Send Command //
	else {
		ClearCommands();
		if (typeof CommandsSent !== 'undefined') CommandsSent();
	}
}




//                   Send Wait                   //
var myhome_xml_wait =  new XMLHttpRequest();
var myhome_xml_wait_timer;
var myhome_wait_enabled = false;
var myhome_wait_was_enabled = false;
function MyhomeSendWait() {
	//     Aborting     //
	if (myhome_xml.readyState != 0) {myhome_xml.abort();clearTimeout(myhome_xml_timer);}
	if (myhome_xml_wait.readyState != 0) {myhome_xml_wait.abort();clearTimeout(myhome_xml_wait_timer);}

	//     Return     //
	myhome_xml_wait.onreadystatechange = function() {
		if (myhome_xml_wait.readyState == 4) {
			clearTimeout(myhome_xml_wait_timer);
			var myhome_answer = "";

			if(myhome_xml_wait.status == 200) {
				myhome_answer = myhome_xml_wait.responseText;
			}
			else {
				if (myhome_wait_enabled) myhome_answer = "Timeout.Restart.";
				else  myhome_answer = "StopWait.";
			}

			myhome_answer = {'command':'wait','status':myhome_xml_wait.status,'answer':myhome_answer};

			//   Console   //
			if (typeof ConsoleWrite !== 'undefined') ConsoleWrite("wait -> " + myhome_answer['answer']);

			//   Return   //
			if (typeof MyhomeReturned !== 'undefined') MyhomeReturned('wait',myhome_answer);
			else alert(myhome_answer);

			if (myhome_wait_enabled) MyhomeSendWait();
		}
	};

	//     Send     //
	myhome_xml_wait.open("POST", myhome_host, true);
	myhome_xml_wait.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	myhome_xml_wait.send();
	myhome_xml_wait_timer = setTimeout("myhome_xml_wait.abort();clearTimeout(myhome_xml_wait_timer);SendMyhomeWait();", myhome_waiting_timeout);
}
var EnableMyhomeWait = function () {
	myhome_wait_enabled = true;
	MyhomeSendWait();
}
var StopMyhomeWait = function () {
	myhome_wait_was_enabled = myhome_wait_enabled;
	myhome_wait_enabled = false;
	if (myhome_xml_wait.readyState != 0) {myhome_xml_wait.abort();clearTimeout(myhome_xml_wait_timer);}
}
var DisableMyhomeWait = function () {
	myhome_wait_enabled = false;
	StopMyhomeWait();
}
