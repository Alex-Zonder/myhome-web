function AddTouchMove (div) {
	div = document.getElementById(div);
	if (userOs=="iOS"){
		div.addEventListener('touchstart', function(event) {TouchStarted(event);}, false);
		//document.body.addEventListener('touchmove', function(event) {TouchMove(event);}, false);
		document.body.addEventListener('touchend', function(event) {TouchEnded(event);}, false);
	}
	else if (userOs=="Android") {
		div.addEventListener('touchstart', function(event) {TouchStartedA(event);}, false);
		//document.body.addEventListener('touchmove', function(event) {TouchMoveA(event);}, false);
		document.body.addEventListener('touchend', function(event) {TouchEndedA(event);}, false);
	}
	/*else if (userOs=="PC") {
		div.onmousedown = function(e) {TouchStarted(e);}
		//document.body.onmousemove = function(e) {TouchMove (e);}
		document.body.onmouseup = function(e) {TouchEnded(e);}
	}*/
}

function Unsigned (val) {
	if (val<0) val = val * -1;
	return val;
}

var offset = 40;
var touched = false;
var x_started = 0;
var y_started = 0;
var x_moved = 0;
var y_moved = 0;
var y_scrolled = 0;

// IOS //
function TouchStarted (event) {
	touched = true;
	x_moved = 0;
	y_moved = 0;
	x_started = event.pageX;
	y_started = event.pageY;
	y_scrolled = document.body.scrollTop;
}
function TouchMove (e) {
	if (touched == true) {
		x_moved = e.pageX - x_started;
		y_moved = e.pageY - y_started;
		y_scrolled = document.body.scrollTop - y_scrolled;
		y_moved = y_moved - y_scrolled;
	}
}
function TouchEnded (e) {
	if (touched == true) {
		TouchMove (e);
		//alert(Unsigned(x_moved) + ':' + Unsigned(y_moved));
		if ((Unsigned(x_moved) / 1.3) > Unsigned(y_moved) && Unsigned(x_moved) > offset) {
			if (typeof TouchMoved === "function") TouchMoved (x_moved);
			else alert('TouchMoved: ' + x_moved);
		}
	}
	touched = false;
}

// Android //
function TouchStartedA (event) {
	touched = true;
	x_moved = 0;
	y_moved = 0;
	x_started = event.touches[0].pageX;
	y_started = event.touches[0].pageY;
	y_scrolled = document.body.scrollTop;
}
function TouchMoveA (e) {
	if (touched == true) {
		x_moved = e.changedTouches[0].pageX - x_started;
		y_moved = e.changedTouches[0].pageY - y_started;
		y_scrolled = document.body.scrollTop - y_scrolled;
		y_moved = y_moved - y_scrolled;
	}
}
function TouchEndedA (e) {
	if (touched == true) {
		TouchMoveA (e);
		//alert(Unsigned(x_moved) + ':' + Unsigned(y_moved));
		if ((Unsigned(x_moved) / 1.3) > Unsigned(y_moved) && Unsigned(x_moved) > offset) {
			if (typeof TouchMoved === "function") TouchMoved (x_moved);
			else alert('TouchMoved: ' + x_moved);
		}
	}
	touched = false;
}
