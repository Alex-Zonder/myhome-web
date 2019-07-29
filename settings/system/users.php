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

		// Get Setts //
		if ($post_action == 'get_users_setts') {
			echo $system->ToJson($users_base);
			exit();
		}

		// Save Setts //
		if ($post_action == 'save_users_setts') {
			$users_base_new = $system->FromJson($post_data);
			$return = '';

			$errors=[];
			// While for new users //
			for ($x=0; $x<count($users_base_new); $x++) {
				// New password //
				if (isset($users_base_new[$x]['_new_pass_1'])) {
					// Not equal //
					if (md5($users_base_new[$x]['password']) != $users_base[$x]['password']) {
						$errors[] = 'Не верно введен пароль';
						$return = 'Не верно введен пароль';
					}
					// No errors & update password to mdt5 //
					else {
						$users_base_new[$x]['password'] = md5($users_base_new[$x]['_new_pass_1']);
					}
				}
			}

			// Save //
			if (count($errors) == 0) {
				// Users to string //
				$users_base_new_str = '[';
				// While for new users //
				for ($x=0; $x<count($users_base_new); $x++) {
					// If password null //
					if ($users_base_new[$x]['password'] == '')
						$users_base_new[$x]['password'] = $users_base[$x]['password'];

					$users_base_new_str .= '[' .
						'"name" => "' . $users_base_new[$x]['name'] . '",' .
						'"password" => "' . $users_base_new[$x]['password'] . '",' .
						'"group" => "' . $users_base_new[$x]['group'] . '",' .
						']';

					if ($x < count($users_base_new)-1)
						$users_base_new_str .= ',';
				}
				$users_base_new_str .= ']';

				$data_to_file = '<?php $users_base=' . $users_base_new_str . '; ?>';
				$file_manager->WriteToFile($docRoot."/_configs/auth.ini", $data_to_file);
				$return = 'Сохранено';
			}

			echo $return;
			exit();
		}
	}
?>
<?php
	//								H T M L								//
	$title.=" - Настройки (Пользователи)";
	$system->InitJava();
	include($docRoot.$template."header.php");
?>


<?php
	//								Footer								//
	include($docRoot.$template."footer.php");
?>
<script>
	//   Body Footer   //
	body_footer_opened = true;
	body_footer_height = 32;

	document.getElementById("Body_Footer_Content").innerHTML = '<center><input type="button" value="Сохранить" style="margin-top:6px;" onclick="SaveSettings()"></center>';
</script>



<script>
//     F U C T I O N S     //
var users = [];
function DrawUser (arr_id) {
	// Erase password //
	users[arr_id]["password"] = '';

	// Create Html //
	var html = '<div class="info_block"><div class="info_block_name">' + users[arr_id]["name"] + '</div><center>';
	html += '<table><tr>' +
			'<td width="50%">Имя</td><td>' +
				'<input type="text" id="" onkeyup="users[' + arr_id + '][\'name\']=InputChanged(this);" value="' + users[arr_id]['name'] + '" />' +
			'</td>' +
		'</tr><tr>' +
			'<td width="50%">Группа</td><td>' +
				'<input type="text" id="" onkeyup="users[' + arr_id + '][\'group\']=InputChanged(this);" value="' + users[arr_id]['group'] + '" />' +
			'</td>' +
		'</tr></table>' +

		'Смена пароля<table><tr>' +
			'<td width="50%">Старый пароль</td><td>' +
				'<input type="text" id="" onkeyup="users[' + arr_id + '][\'password\']=InputChanged(this);" value="" />' +
			'</td>' +
		'</tr><tr>' +
			'<td width="50%">Новый пароль</td><td>' +
				'<input type="text" id="' + arr_id + '_new_pass_1" onkeyup="users[' + arr_id + '][\'_new_pass_1\']=InputChanged(this);" value="" />' +
			'</td>' +
		'</tr><tr>' +
			'<td width="50%">Подтверждение пароля</td><td>' +
				'<input type="text" id="' + arr_id + '_new_pass_2" onkeyup="users[' + arr_id + '][\'_new_pass_2\']=InputChanged(this);" value="" />' +
			'</td>' +
		'</tr></table>';
	html += '</center></div>';

	// Return //
	Body_Block.innerHTML += html;
}

function InputChanged (obj) {
	obj.style.color = 'red';
	return obj.value;
}




//     R U N     //
// Get settings //
document.addEventListener("DOMContentLoaded", function() {
	setTimeout(function () {
		SendPost ('get_users_setts','');
	}, 10);
});

//     P O S T     //
// Save Settings //
function SaveSettings() {
	//alert (ToJson(users));
	// Pass length & equals //
	errors = [];
	for (var x=0; x<users.length; x++) {
		// If is first pass //
		if (typeof users[x]['_new_pass_1'] != 'undefined' && users[x]['_new_pass_1'].length > 0) {
			// Pass length //
			if (users[x]['_new_pass_1'].length < 4) {
				errors.push(users[x]['name'] + ': Длинна пароля не может быть менее 4 символов');
				break;
			}
			// Pass_1 equals Pass_2 //
			if (users[x]['_new_pass_1'] != users[x]['_new_pass_2']) {
				errors.push(users[x]['name'] + ': Новые пароли не совпадают');
				break;
			}
		}
	}

	// Send if no errors //
	if (errors.length < 1 && confirm("Сохранить настройки?")) {
		SendPost ('save_users_setts',ToJson(users));
	}
	else if (errors.length > 0) alert ('Ошибка: ' + errors[0]);
}

// Post Returned //
function PostReturned (serv_answer,send_action,send_data) {
	// Get settings //
	if (send_action == 'get_users_setts') {
		users = FromJson(serv_answer);
		for (var x=0; x<users.length; x++) {
			DrawUser(x);
		}
	}
	// Save settings //
	else if (send_action == 'save_users_setts') {
		alert (serv_answer);
	}
}
</script>
