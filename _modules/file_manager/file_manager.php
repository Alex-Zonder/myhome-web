<?php
class FileManager {
	//			I N I T			//
	function InitJava (){
		?><script src="<?php echo $GLOBALS['htmlRoot']; ?>_modules/file_manager/file_manager.js"></script><?php
	}



	//			D I R E C T O R Y			//
	function ScanDir ($dir) {
		if (is_dir($dir)) {
			$result = scandir($dir);

			$files=array();$dirs=array();
			for ($x=0; $x<count($result); $x++) {
				// if dir //
				if (is_dir($dir.'/'.$result[$x])) $dirs[]=$result[$x];
				// if file //
				else {
					$files[]=['name'=>$result[$x],'size'=>filesize($dir.'/'.$result[$x])];
				}
			}

			$result=['files'=>$files,'dirs'=>$dirs];
			return $result;
		}
		else return "Error: Not dir.";
	}

	function MakeDir ($pach,$rights) {
		return mkdir($pach, $rights);
	}

	function RemoveDir ($pach) {
		rmdir($pach);
	}
	function RemoveDirR ($pach) {
		shell_exec("rm -r " . $pach);
	}





	//			F I L E			//
	function OpenFile ($file_name,$options) {
		if (is_file($file_name))
			return shell_exec("cat ".$file_name.$options);
		else
			return "Error. No file: " . $file_name;
	}
	function ReadFile ($file_name) {
		if (is_file($file_name))
			return file_get_contents($file_name, FILE_USE_INCLUDE_PATH);
		else
			return "Error. No file: " . $file_name;
	}
	function WriteToFile ($file,$data) {
		file_put_contents($file,$data);
	}
	function RemoveFile ($pach) {
		unlink($pach);
	}



	//			P A R S E   F I L E   N A M E			//
	function ParseFileName ($file) {
		$file_type=explode('.', $file);

		$file_name='';
		if (count($file_type)>1) {
			for ($x=0; $x<(count($file_type)-1); $x++) {
				$file_name.=$file_type[$x];
				if ($x<(count($file_type)-2)) $file_name.='.';
			}
		}
		else $file_name.=$file_type[0].'.';

		$file_type=$file_type[count($file_type)-1];

		return ['name'=>$file_name, 'type'=>$file_type];
	}



	//			L O A D   F I L E  F R O M   H T M L			//
	function LoadFile ($name, $to) {
		if(isset($_FILES[$name])) {
			$errors = array();
			$file_name = $_FILES[$name]['name'];
			$file_size = $_FILES[$name]['size'];
			$file_tmp = $_FILES[$name]['tmp_name'];
			//$file_type = $_FILES[$name]['type'];
			//$file_ext = strtolower(end(explode('.',$_FILES[$name]['name'])));
			$file_ext = strtolower(explode('.',$file_name)[count(explode('.',$file_name))-1]);

			/*$expensions= array("jpeg","jpg","png");
			if(in_array($file_ext,$expensions)=== false){
			   $errors[]="extension not allowed, please choose a JPEG or PNG file.";
			}*/

			if ($file_size > 2097152) {
				$errors[]='File size must be excately 2 MB';
			}

			if(empty($errors)==true){
				// Remove if file is //
				if (is_file($to.$file_name)) {
					$this->RemoveFile ($to.$file_name);
				}
				// Move File //
				move_uploaded_file($file_tmp, $to.$file_name);
				chmod($to.$file_name, 0777);
				return [
					'name' => $file_name,
					'path' => $to . $file_name,
					'ext' => $file_ext,
					'size' => $file_size,
					'tmp' => $file_tmp
				];
			}
			else{
				return $errors;
			}

		}
		else {
			return 'File is not set';
		}
	}
}  //   End Class   //

$file_manager = new FileManager;
?>
