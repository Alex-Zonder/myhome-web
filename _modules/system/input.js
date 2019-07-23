var Input_Changed = function (name) {
	var input_value = document.getElementById(name).value;
	document.getElementById(name).style.color = "red";
	return input_value;
}
var Draw_Input = function (name, var_name, var_val, div_obj) {
	var input_value = window[var_name] || var_val || '';
	var input_html = '<input type="text" id="' + name + '" onkeyup="' + var_name + '=Input_Changed(\'' + name + '\');" value="' + input_value + '" />';

	// Return //
	if (div_obj && div_obj=="return") return input_html;
	else if (div_obj) document.getElementById(div_obj).innerHTML += input_html;
	else document.write(input_html);
}



var Checkbox_Changed = function (name) {
	var input_value = document.getElementById(name).checked;
	return input_value;
}
var Draw_Checkbox = function (name, var_name, var_val, div_obj) {
	var input_value = window[var_name] || var_val || false;

	var input_checked = '';
	if (input_value) input_checked = 'checked';
	var input_html = '<input type="checkbox" id="' + name + '" onchange="' + var_name + '=Checkbox_Changed(\'' + name + '\');" ' + input_checked + ' />';

	// Return //
	if (div_obj && div_obj=="return") return input_html;
	else if (div_obj) document.getElementById(div_obj).innerHTML += input_html;
	else document.write(input_html);
}
