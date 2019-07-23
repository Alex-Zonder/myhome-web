function GetTime () {
	var date = new Date();
	var hours = date.getHours();
	var minutes = date.getMinutes();
	var seconds = date.getSeconds();
	if (hours.toString().length<2) hours = "0" + hours.toString();
	if (minutes.toString().length<2) minutes = "0" + minutes.toString();
	if (seconds.toString().length<2) seconds = "0" + seconds.toString();
	var timeNow= hours +':'+ minutes +':'+ seconds;
	return timeNow;
}

function GetDate () {
	var date = new Date();

	var year = date.getFullYear();
	var month = date.getMonth() + 1;
	var day = date.getDate();
	if (month.toString().length<2) month = "0" + month.toString();
	if (day.toString().length<2) day = "0" + day.toString();

	var date_now = year +'-'+ month +'-'+ day;
	return date_now;
}
