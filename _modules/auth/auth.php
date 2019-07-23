<?php
//____________________EXIT_________________________
if (isset($_GET['action']) && $_GET['action']=='exit') {
	//   Getting $htmlRoot   //
	$folders=explode('/', $_SERVER['SCRIPT_NAME']);
	$htmlRoot='/';
	for ($x=1; $x<count($folders)-3; $x++) $htmlRoot.=$folders[$x]."/";
	$docRoot=$_SERVER['DOCUMENT_ROOT'].$htmlRoot;

	include($docRoot.'_configs/system.ini');
	include($docRoot.'_configs/auth.ini');
	KillCook ();
}


include($docRoot.'_configs/auth.ini');
$user='';
$userPass='';
$userGroup='';
$users=[];
function Auth ($user,$pass) {
	global $users, $users_base;
	$users=array_merge($users,$users_base);

	for ($x=0; $x<count($users); $x++){
		if ($users[$x]['name']==$user && $users[$x]['password']==$pass) {
			$GLOBALS['user']=$users[$x]['name'];
			$GLOBALS['userPass']=$users[$x]['password'];
			$GLOBALS['userGroup']=$users[$x]['group'];
		}
	}
	//MakeLog ($user);
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name'])){
	Auth ($_POST['name'],md5($_POST['pass']));
	SetCook ($GLOBALS['user'],$GLOBALS['userPass']);
}
else if(isset($_COOKIE[$coocie_prefix.'user'])) {
	Auth ($_COOKIE[$coocie_prefix.'user'],$_COOKIE[$coocie_prefix.'pass']);
}


//____________________COOCIE_________________________
function SetCook ($name,$pass) {
	global $coocie_prefix;
	$y2k = time() + (86400 * 60);	// 86400 = 1 day
	setcookie($coocie_prefix.'user', $name, $y2k, "/");
	setcookie($coocie_prefix.'pass', $pass, $y2k, "/");

	global $htmlRoot;
	header( 'Location: '.$_POST['url'] || $htmlRoot );
}
function KillCook () {
	global $coocie_prefix;
	setcookie ($coocie_prefix.'user', null, time()-1, "/");
	unset($_COOKIE[$coocie_prefix.'user']);
	setcookie ($coocie_prefix.'pass', null, time()-1, "/");
	unset($_COOKIE[$coocie_prefix.'pass']);

	global $htmlRoot;
	header( 'Location: '.$htmlRoot );
}
?>
