function Keyboard () {
	//					Keyboard events					//
	document.onkeydown = function(evt) {
		evt = evt || window.event;
		//alert('Down:'+evt.keyCode);
	};
	document.onkeyup = function(evt) {
		evt = evt || window.event;
		if (evt.keyCode == 27) if (typeof OnEscape === "function") OnEscape();
		if (evt.keyCode == 13) if (typeof OnEnter === "function") OnEnter();

		if (evt.keyCode == 37) if (typeof OnLeftArrow === "function") OnLeftArrow();
		if (evt.keyCode == 39) if (typeof OnRightArrow === "function") OnRightArrow();
		//alert('Up:'+evt.keyCode);
	};

	//			For onkeyup/onkeydown/... in input			//
	this.CheckEnter=function (func_to_run,data_to_run) {
		if (window.event.keyCode==13) func_to_run(data_to_run);
	}
}
var keyboard=new Keyboard();
