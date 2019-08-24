<?php
//			Check Get/Post request			//
function CheckRequest () {
	$type = "";
	$action = "";
	$data = "";

	// POST //
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$type = "post";
		if (isset($_POST['action'])) $action = $_POST['action'];
		if (isset($_POST['data'])) $data = $_POST['data'];
	}
	// GET //
	else if ($_SERVER['QUERY_STRING'] != '') {
		$type = "get";
		if (isset($_GET['action'])) $action = $_GET['action'];
		if (isset($_GET['data'])) $data = $_GET['data'];
		else $data = $_SERVER['QUERY_STRING'];
	}

	return [
		'type' => $type,
		'action' => $action,
		'data' => $data
	];
}


// GET //
/*function CyberCurl ($url, $timeout) {
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
	$body = curl_exec($ch);
	$retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);

	$ans=array('body'=>$body,'retcode'=>$retcode);
	return $ans;
}*/
// POST //
function SendPost ($url, $data, $timeout) {
	if( $curl = curl_init() ) {
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_ENCODING, "");  
		curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
		curl_setopt($curl, CURLOPT_POST, true);

		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);

		$body = curl_exec($curl);
		$retcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);

		return array(
			'body' => $body,
			'retcode' => $retcode
			);
	}
}
?>