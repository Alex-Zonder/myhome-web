function FileManager () {
	// Send File //
	this.SendFile = function (input_id) {
		var file = document.getElementById(input_id).files[0];
	
		// File is //
		if (file) {
			var formData = new FormData();
			formData.append('file', file, file.name);
		
			// On return //
			var req = new XMLHttpRequest();
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					//alert (req.responseText);
					var servAnswer = req.responseText;
					if (typeof PostReturned !== 'undefined') PostReturned(servAnswer,'send_file_post',file.name);
					else alert(servAnswer);
				}
			}
		
			// Send //
			req.open("POST", '', true);
			req.send(formData);
		}
		else alert('Файл не выбран!');
	};
}
var file_manager = new FileManager ();