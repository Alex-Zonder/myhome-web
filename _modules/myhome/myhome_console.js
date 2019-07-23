var SendConsoleCommand = function () {
	MyhomeSendCommand(Console_Command.value,MyhomeReturned);
}
var CreateConsole = function (holder_div_id) {
	var console_holder = document.getElementById("Body_Footer_Content");
	console_holder.style.overflow = "hidden";

	// Return Holder //
	var return_holder = document.createElement('div');
	return_holder.id = "Return_Holder";
	console_holder.appendChild(return_holder);
	return_holder.innerHTML = "<div id='Console_Return' style='display:inline-block;'></div>";

	// Command Holder //
	var command_holder = document.createElement('div');
	command_holder.id = "Command_Holder";
	console_holder.appendChild(command_holder);
	command_holder.innerHTML = '<div id="Command_Input"><input type="text" id="Console_Command" value="$01Z;" onkeyup="keyboard.CheckEnter(SendConsoleCommand)"></div>';
	command_holder.innerHTML += '<div id="Command_Send" onclick="SendConsoleCommand();">Отправить</div>';
}
var ConsoleWrite = function (data) {
	var cons = document.getElementById("Console_Return");
	var ret = GetTime() + " " + data;
	cons.innerHTML = ret + "<br>" + cons.innerHTML;
}
