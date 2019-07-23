var userOs="";
function CheckUserAgent () {
	if (navigator.userAgent.match(/iPhone|iPad|iPod/i)) userOs="iOS";
	else if (navigator.userAgent.match(/Android/i)) userOs="Android";
	else if (navigator.userAgent.match(/Windows Phone/i)) userOs="Windows Phone";
	else  userOs="PC";
	
	if (userOs=="iOS" || userOs=="Android" || userOs=="Windows Phone") {
		var viewPortTag=document.createElement('meta');
		viewPortTag.id="viewport";
		viewPortTag.name = "viewport";
		viewPortTag.content = "width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0";
		document.getElementsByTagName('head')[0].appendChild(viewPortTag);
		
		//userOs="mobile";
	}
}
CheckUserAgent ();



function goFullscreen(id) {
	    var element = document.body; 
	
	    var isInFullScreen = (document.fullscreenElement && document.fullscreenElement !== null) ||
	        (document.webkitFullscreenElement && document.webkitFullscreenElement !== null) ||
	        (document.mozFullScreenElement && document.mozFullScreenElement !== null) ||
	        (document.msFullscreenElement && document.msFullscreenElement !== null);
	
	    var docElm = document.documentElement;
	    if (!isInFullScreen) {
	        if (element.requestFullscreen) {
	            element.requestFullscreen();
	        } else if (element.mozRequestFullScreen) {
	            element.mozRequestFullScreen();
	        } else if (element.webkitRequestFullScreen) {
	           element.webkitRequestFullScreen();
	        } else if (element.msRequestFullscreen) {
	            element.msRequestFullscreen();
	        }
	    } else {
	        if (document.exitFullscreen) {
	            document.exitFullscreen();
	        } else if (document.webkitExitFullscreen) {
	            document.webkitExitFullscreen();
	        } else if (document.mozCancelFullScreen) {
	            document.mozCancelFullScreen();
	        } else if (document.msExitFullscreen) {
	            document.msExitFullscreen();
	        }
	    }

	window.scrollTo(0,1);
}
//if (userOs=="iOS") goFullscreen();
