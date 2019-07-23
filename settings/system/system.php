<?php
//   System   //
include("../../_configs/system.ini");
include($docRoot."_modules/system/system.php");
// $docRoot, $htmlRoot, $template, $auth_enabled //
//   File Manager   //
include($docRoot."_modules/file_manager/file_manager.php");
?>
<?php
//								P O S T								//
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$post_action=$_POST['action'];
	$post_data=$_POST['data'];

	if ($post_action=="SaveSettings") {
		$file_manager->WriteToFile($docRoot."_configs/system.ini",$post_data);

		echo $file_manager->OpenFile($docRoot."_configs/system.ini",'');
		exit();
	}
}
?>
<?php
//								H T M L								//
$title.=" - Настройки Системы";
$system->InitJava();
include($docRoot.$template."header.php");
?>






<div class="info_block">
	<div class="info_block_name">Система</div>
	<center>
		<table width="100%">
			<tr>
				<td width="50%">Имя системы:</td>
				<td>
					<input type="text" id="Input_System_Name">
				</td>
			</tr>
			<tr>
				<td>Титл:</td>
				<td>
					<input type="text" id="Input_System_Title" size="18">
				</td>
			</tr>
			<tr>
				<td>Описание:</td>
				<td>
					<input type="text" id="Input_System_Description" size="18">
				</td>
			</tr>
		</table>
	</center>
</div>

<div class="info_block">
	<div class="info_block_name">Стиль</div>
	<center>
		<table width="100%">
			<tr>
				<td width="50%">Шатблон:</td>
				<td>
					<select id="Input_System_Template">
						<option value="_templates/cyber_0/">Шаблон 0</option>
						<option value="_templates/cyber_1/">Шаблон 1</option>
						<option value="_templates/cyber_2/">Шаблон 2</option>
					</select>
				</td>
			</tr>
			<tr>
				<td width="50%">Тема шатблона:</td>
				<td>
					<!--<input type="text" id="Input_System_Template_Theme" size="18">-->
					<select id="Input_System_Template_Theme">
						<option value="white">Светлая</option>
						<option value="dark">Темная</option>
					</select>
				</td>
			</tr>
		</table>
	</center>
</div>

<div class="info_block">
	<div class="info_block_name">Дополнительно</div>
	<center>
		<table width="100%">
			<tr>
				<td width="50%">Корневой каталог:</td>
				<td>
					<input type="text" id="Input_System_Doc_Root" size="18">
				</td>
			</tr>
			<tr>
				<td width="50%">Префикс куки:</td>
				<td>
					<input type="text" id="Input_Cookies_Prefix" size="18">
				</td>
			</tr>
		</table>

		<table width="100%">
			<tr>
				<td width="50%">Авторизация:</td>
				<td>
					<input type="text" id="Input_System_Auth_Enabled" size="18">
				</td>
			</tr>
			<tr>
				<td width="50%">Сетевой интерфейс:</td>
				<td>
					<input type="text" id="Input_System_Ifaces" size="18">
				</td>
			</tr>
		</table>
	</center>
</div>
<hr><center><input type="button" value="Сохранить" onclick="SaveSettings();" /></center><hr>




<?php
//								Footer								//
include($docRoot.$template."footer.php");
?>
<script>
//   Body Footer   //
body_footer_opened = true;
body_footer_height = 32;

var html = '<center><input type="button" value="Сохранить" style="margin-top:6px;" onclick="SaveSettings()"></center>';
document.getElementById("Body_Footer_Content").innerHTML = html;
</script>



<?php
echo "<script>";
	echo 'var site_name = "' . $siteName . '";';
	echo 'var site_title = "' . $title . '";';
	echo 'var site_description = "' . $description . '";';

	echo 'var site_html_root = "' . $htmlRoot . '";';
	echo 'var site_template = "' . $template . '";';
	echo 'var site_template_theme = "' . $template_theme . '";';

	echo 'var site_auth_enabled = "' . $auth_enabled . '";';
	echo 'var site_system_ifaces = FromJson(\'' . $system->ToJson($system_ifaces) . '\');';
echo "</script>";
?>

<script>
Input_System_Name.value = site_name;
Input_System_Title.value = site_title.split(" - ")[0];
Input_System_Description.value = site_description;

Input_System_Doc_Root.value = site_html_root;
Input_System_Template.value = site_template;
Input_System_Template_Theme.value = site_template_theme;

Input_Cookies_Prefix.value = cookie_prefix;

Input_System_Auth_Enabled.value = site_auth_enabled;
Input_System_Ifaces.value = ToJson(site_system_ifaces);

function SaveSettings () {
	if (confirm("Сохранить настройки?")) {
		var html = "<\?php\n";

		html += "$siteName='" + Input_System_Name.value + "';\n";
		html += "$title='" + Input_System_Title.value + "';\n";
		html += "$description='" + Input_System_Description.value + "';\n";

		html += "$htmlRoot='" + Input_System_Doc_Root.value + "';\n";
		html += "$docRoot=$_SERVER['DOCUMENT_ROOT'].$htmlRoot;\n";
		html += "$template='" + Input_System_Template.value + "';\n";
		html += "$template_theme='" + Input_System_Template_Theme.value + "';\n";

		html += "$coocie_prefix='" + Input_Cookies_Prefix.value + "';\n";

		html += "$auth_enabled='" + Input_System_Auth_Enabled.value + "';\n";
		html += "$system_ifaces=" + Input_System_Ifaces.value + ";\n";

		html += "?>\n";

		//alert(html);
		SendPost ("SaveSettings",html);
	}
}
function PostReturned (servAnswer,send_action,send_data) {
	//alert(servAnswer);
	if (servAnswer == send_data) alert ("Настройки сохранены");
	else alert ("Ошибка сохранения");
}
</script>
