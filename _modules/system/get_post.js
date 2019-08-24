//			J S O N			//
function ToJson (data) {return JSON.stringify(data);}
function FromJson (data) {return JSON.parse(data);}


//			X M L			//
xml_http = new XMLHttpRequest();
function SendPost (action,data,url){
	var url = url || "";
	send_action=action;
	send_data=data;

	xml_http.open("POST", url, false);
	xml_http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

	xml_http.onreadystatechange = function() {
		if (xml_http.readyState==4) {
			if (xml_http.status==200){
				var servAnswer = xml_http.responseText;

				//   Remove Enters   //
				strLen=servAnswer.length;
				var startByte=0;
				while (servAnswer[startByte]=="\n" && startByte<strLen) startByte++;
				servAnswer = servAnswer.substr(startByte,strLen-startByte);

				if (typeof PostReturned !== 'undefined') PostReturned(servAnswer,send_action,send_data,url);
				else alert(servAnswer);
			}
			else alert ("Ошибка соединения!");
		}
	}

	//var dataXml='action='+action+'&data='+data;
	var dataXml='action='+action+'&data='+encodeURIComponent(data);
	xml_http.send(dataXml);
}


xml_async = {};
xml_counter = 0;
function SendPostAsync (action,data,url){
	var url = url || "";
	send_action=action;
	send_data=data;

	// Open //
	xml_async[xml_counter] = new XMLHttpRequest();
	xml_async[xml_counter].open("POST", url, true);
	xml_async[xml_counter].setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xml_async[xml_counter].send_action = send_action;
	xml_async[xml_counter].send_data = send_data;
	xml_async[xml_counter].url = url;

	// Send //
	var dataXml='action='+action+'&data='+encodeURIComponent(data);
	xml_async[xml_counter].send(dataXml);

	// Return //
	xml_async[xml_counter].onreadystatechange = function() {
		if (this.readyState==4) {
			if (this.status==200){
				var servAnswer = this.responseText;

				//   Remove Enters   //
				strLen=servAnswer.length;
				var startByte=0;
				while (servAnswer[startByte]=="\n" && startByte<strLen) startByte++;
				servAnswer = servAnswer.substr(startByte,strLen-startByte);

				if (typeof PostReturned !== 'undefined') PostReturned(servAnswer,this.send_action,this.send_data,this.url);
				else alert(servAnswer);
			}
			else if (this.status != 0) alert ("Ошибка соединения: " + this.status);
		}
	};

	xml_counter++;
}
