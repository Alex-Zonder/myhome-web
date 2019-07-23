//			J S O N			//
function ToJson (data) {return JSON.stringify(data);}
function FromJson (data) {return JSON.parse(data);}


//			X M L			//
xml_http = new XMLHttpRequest();
function SendPost (action, data, sync, url){
	var sync = sync || false;
	var url = url || "";
	send_action=action;
	send_data=data;

	xml_http.open("POST", url, sync);
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

				if (typeof PostReturned !== 'undefined') PostReturned(servAnswer,send_action,send_data);
				else alert(servAnswer);
			}
			else alert ("Ошибка соединения!");
		}
	}

	//var dataXml='action='+action+'&data='+data;
	var dataXml='action='+action+'&data='+encodeURIComponent(data);
	xml_http.send(dataXml);
}
