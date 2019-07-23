function Moved_Divs_Resized (id) {setTimeout(id + '.Change_Div()',0);}
function Moved_Divs (id, settings) {
	this.id = id;
	this.settings = settings;
	this.obj_opened = 0;

	// Menu holder //
	this.menu_holder_div = document.getElementById(this.settings['holders']['menu']);

	// Objects holder //
	this.divs_holder_div = document.getElementById(this.settings['holders']['divs']);
	this.divs_holder_div.style.overflow = 'hidden';
	this.divs_holder_div.style.width = '100%';

	// Width holder & Resize //
	this.divs_width_div = document.createElement('div');
	this.divs_holder_div.appendChild(this.divs_width_div);
	this.divs_width_div.innerHTML = '<iframe name="body_resized_' + this.id + '" style="width:100%;height:0;border:0;"></iframe>';
	window['body_resized_' + this.id].onresize = Moved_Divs_Resized.bind(null,this.id);

	// Objects slider //
	this.divs_slider_div = document.createElement('div');
	this.divs_slider_div.id = this.divs_holder_div.id + 'slider';
	this.divs_holder_div.appendChild(this.divs_slider_div);


	this.objects = [];
	this.Add_Object = function (name, obj_html) {
		var obj_arr_id = this.objects.length;
		this.objects.push({});


		// Add Button //
		this.objects[obj_arr_id].button_div = document.createElement('div');
		this.objects[obj_arr_id].button_div.style.display = 'inline-block';
		this.menu_holder_div.appendChild(this.objects[obj_arr_id].button_div);

		var onclick = this.id + '.Change_Div_By_But(' + obj_arr_id + ')';
		var but_id = this.id + '_but_' + obj_arr_id;
		var html = '<div class="' + this.settings['buts']['but'] + '" id="' + but_id + '" onclick="' + onclick + '">' + name + '</div>'
		this.objects[obj_arr_id].button_div.innerHTML = html;


		// Add Object Holder //
		this.objects[obj_arr_id].holder_div = document.createElement('div');
		this.objects[obj_arr_id].holder_div.id = this.id + '_obj_div_' + obj_arr_id;
		this.objects[obj_arr_id].holder_div.style.float = 'left';
		this.divs_slider_div.appendChild(this.objects[obj_arr_id].holder_div);

		if (obj_html != null) this.objects[obj_arr_id].holder_div.innerHTML = obj_html;

		this.Resize();
	}
	this.Change_Div = function (div_id) {
		if (div_id != null && (div_id >= 0 && div_id < this.objects.length))
			this.obj_opened = div_id;

		if (document.getElementById(this.id + '_but_' + this.obj_opened)) {
			this.Resize();

			// Move Objects //
			this.divs_slider_div.style.marginLeft = '-' + this.divs_holder_div.clientWidth * this.obj_opened + 'px';

			// Change Buts in Top_Menu //
			for (var x=0; x < this.objects.length; x++) {
				document.getElementById(this.id + '_but_' + x).className = this.settings['buts']['but'];
				document.getElementById(this.id + '_obj_div_' + x).style.height = "1px";
			}
			document.getElementById(this.id + '_but_' + this.obj_opened).className = this.settings['buts']['pushed'];
			document.getElementById(this.id + '_obj_div_' + this.obj_opened).style.height = "auto";
		}
	}
	this.Change_Div_By_But = function (div_id) {
		if (typeof Moved_Divs_Changed === 'function') Moved_Divs_Changed(div_id);
		Transition (this.divs_slider_div.id,300);
		this.Change_Div(div_id);
	}
	this.Resize = function () {
		var wdth = this.divs_holder_div.clientWidth;
		var obj_len = this.objects.length;
		this.divs_slider_div.style.width = wdth * obj_len;
		for (var x=0; x < obj_len; x++) {
			this.objects[x].holder_div.style.width = wdth;
		}
	}
	setTimeout(this.id + '.Change_Div()',1);
}
