<?php
//								Start Class (Myhome)								//
class Myhome {

	//			I N I T			//
	function InitJava () {
		global $docRoot,$htmlRoot,$myhome_setts_json;

		include($docRoot . "_modules/myhome/myhome_console.php");


		?>
		<!-- Load Setts To Js -->
		<script>
			var myhome_setts_json = <?php echo "'".$myhome_setts_json."'"; ?>;
			var myhome_setts = FromJson(myhome_setts_json);
		</script>

		<!-- Load Objects -->
		<script src="<?php echo $htmlRoot; ?>_modules/myhome/myhome.js"></script>
		<script src="<?php echo $htmlRoot; ?>_modules/myhome/myhome_console.js"></script>

		<script src="<?php echo $htmlRoot; ?>_modules/myhome/myhome_gate.js"></script>
		<script src="<?php echo $htmlRoot; ?>_modules/myhome/myhome_manager.js"></script>

		<script src="<?php echo $htmlRoot; ?>_modules/myhome/devs/_proto.js"></script>
		<script src="<?php echo $htmlRoot; ?>_modules/myhome/devs/i16.js"></script>
		<script src="<?php echo $htmlRoot; ?>_modules/myhome/devs/rm4.js"></script>
		<script src="<?php echo $htmlRoot; ?>_modules/myhome/devs/cam.js"></script>
		<?php
	}
}
$myhome_setts_json = shell_exec("cat ".$docRoot."_configs/myhome.json | tr -d \"\n\" | tr -d \"\t\"");
$myhome_setts = $system->FromJson($myhome_setts_json);
$myhome=new Myhome;
//								End Class (Myhome)								//

?>
