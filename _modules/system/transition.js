var Transition = function (div,time_ms) {
	var tr_div = document.getElementById(div);
	if (tr_div.style.transition == "") {
		tr_div.style.transition = time_ms/1000 + "s";
		setTimeout (function () {
			var tr_div = document.getElementById(div);
			tr_div.style.transition = "";
		}, time_ms, div);
	}
}
