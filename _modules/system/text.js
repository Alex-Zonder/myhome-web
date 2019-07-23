var HighLightText = function (data,to_highlight,color) {
	var to_highlight_arr = to_highlight.split(',');
	for (var x=0; x<to_highlight_arr.length; x++) {
		data = data.split(to_highlight_arr[x]).join("<font color='" + color + "'>" + to_highlight_arr[x] + "</font>");
	}
	return data;
}
function n2br (data) {return data.replace(/\n/g, "<br />");}
function rsort (data) {
	var new_data = "";
	strings = data.split('\n');
	var str_counter = strings.length-1;
	if (!strings[str_counter].length) str_counter--;
	for (var x=str_counter; x>=0; x--) {
		new_data += strings[x] + '\n';
	}
	return new_data;
}
function Grep (data,grep) {
	var new_data = "";
	strings = data.split('\n');
	for (var x=0; x < strings.length; x++) {
		if (strings[x].indexOf(grep) + 1) {
			new_data += strings[x] + '\n';
		}
	}
	return new_data;
}





function FillDivTextByLines (data,div_id) {
	var div_text = document.getElementById(div_id);
	//div_text.innerHTML = n2br(data);
	div_text.innerHTML = '';
	var strings = data.split("\n");
	for (var x=0; x<strings.length; x++) {
		if (strings[x] != '') {
			var div_string = document.createElement('div');
			div_string.innerHTML = "<font color='gray'>" + (x + 1) + ":</font> " + strings[x];

			/*var html = "<div style='float:left;width:40px;'>" + x + "</div>";
			html += "<div style='float:left;width:calc(100% - 50px);'>" + strings[x]; + "</div>";
			html += "<div style='clear:both;'></div>";
			div_string.innerHTML = html;*/

			div_text.appendChild(div_string);
		}
	}
}
