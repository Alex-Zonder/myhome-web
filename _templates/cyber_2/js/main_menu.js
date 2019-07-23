/*									O N R E S I Z E									*/
var site_width = 0;
var last_width = 0;
function Resize () {
	site_width = document.body.clientWidth;

	if (site_width != last_width) {
		last_width = site_width;
		/*			Close			*/
		if (site_width<=560 && menu_is_opened) {
			Menu_Close ();
		}
		/*			Open			*/
		else if (site_width>560 && !menu_is_opened) {
			Menu_Open ();
		}
	}
}
window.addEventListener('resize', function() {Resize ();});
window.addEventListener('DOMContentLoaded', function() {Resize ();});




var menu_width = parseInt(getCookie("menu_width")) || 150;
var menu_is_opened = false;
function Menu_Open () {
	menu_holder = document.getElementById("Menu_Holder");
	body_holder = document.getElementById("Body_Holder");

	menu_holder.style.width = menu_width + 'px';
	menu_holder.style.marginLeft = '0px';
	if (site_width>560) {
		body_holder.style.width = 'calc(100% - ' + menu_width + 'px)';
		body_holder.style.marginLeft = menu_width + 'px';
	}

	menu_is_opened = true;
}
function Menu_Close () {
	menu_holder = document.getElementById("Menu_Holder");
	body_holder = document.getElementById("Body_Holder");

	menu_holder.style.marginLeft = '-' + menu_width + 'px';
	body_holder.style.width = 'calc(100%)';
	body_holder.style.marginLeft = '0px';

	menu_is_opened = false;
}
function Button_Main_Menu() {
	Transition ("Menu_Holder",200);
	Transition ("Body_Holder",200);

	if (!menu_is_opened) {
		Menu_Open();
	}
	else {
		Menu_Close();
	}
}




var menu_width_change_enabled = false;
function Change_Menu_Width (e) {
	if (menu_width_change_enabled == true) {
		menu_width = e.clientX;
		Menu_Holder.style.width = menu_width + 'px';
		if (site_width>560) {
			Body_Holder.style.width = 'calc(100% - ' + menu_width + 'px)';
			Body_Holder.style.marginLeft = menu_width + 'px';
		}
		setCookie("menu_width", menu_width, {'path':'/','expires':60*60*24*60});
	}
}
function Enable_Change_Menu_Width () {
	menu_width_change_enabled = true;
}
function Disable_Change_Menu_Width () {
	menu_width_change_enabled = false;
}
