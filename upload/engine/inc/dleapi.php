<?php
/*
=====================================================
 DLE-API - неофициальный API
-----------------------------------------------------
 http://devcraft.club/
-----------------------------------------------------
 Copyright (c) 2019 Maxim Harder
=====================================================
 File: /engine/inc/dleapi.php
=====================================================
*/

if( !defined( 'DATALIFEENGINE' ) OR !defined( 'LOGGED_IN' ) ) {
	header( "HTTP/1.1 403 Forbidden" );
	header ( 'Location: ../../' );
	die( "Hacking attempt!" );
}

$version = [
	'name' => 'DLE-API',
	'descr' => 'Неофициальное API',
	'version' => '1.0',
	'changelog' => [
		'1.0' => [
			'ервая стандартная версия'
		]
	],
	'id' => 'dleapi',
];

$subtitle = $version['descr'];
if ($_GET['action'] === 'add') $subtitle .= ': добавление ключа';
else if ($_GET['action'] === 'settings') $subtitle .= ': настройка';
else if ($_GET['action'] === 'edit') $subtitle .= ': обновление ключа';

echoheader( "<i class=\"fa fa-id-card-o position-left\"></i><span class=\"text-semibold\">{$version['name']} (v{$version['version']})</span>", $subtitle );
function showRow($title = "", $description = "", $field = "", $class = "") {


	echo "<tr>
        <td class=\"col-xs-6 col-sm-6 col-md-5\"><h6 class=\"media-heading text-semibold\">{$title}</h6><span class=\"text-muted text-size-small hidden-xs\">{$description}</span></td>
        <td class=\"col-xs-6 col-sm-6 col-md-7\">{$field}</td>
        </tr>";
}

/**
 * @param $options
 * @param $name
 * @param $selected
 *
 * @return string
 */
function makeDropDown($options, $name, $selected) {
	$output = "<select class=\"uniform\" name=\"$name\">\r\n";
	foreach ( $options as $value => $description ) {
		$output .= "<option value=\"$value\"";
		if( $selected == $value ) {
			$output .= " selected ";
		}
		$output .= ">$description</option>\n";
	}
	$output .= "</select>";
	return $output;
}

/**
 * @param $name
 * @param $selected
 *
 * @return string
 */
function makeCheckBox($name, $selected) {

	$selected = $selected ? "checked" : "";

	return "<input class=\"switch\" type=\"checkbox\" name=\"{$name}\" value=\"1\" {$selected}>";

}

/**
 * @return array
 */
function getUsers() {
	global $db;

	$db->query('SELECT * FROM '. USERPREFIX . "_users WHERE restricted = '0'");
	$user_ar = array();

	while($build = $db->get_array()) {
		$user_ar[$build['user_id']] = $build['name'];
	}

	$db->free();
	return $user_ar;
}

/**
 * @return array
 */
function getTables() {
	global $db;

	$db->query('SHOW tables');
	$tables = array();

	while($build = $db->get_array()) {
		$name = str_replace(array(PREFIX . '_', USERPREFIX . '_'), '', $build[0]);
		if (in_array($name, array('api_keys', 'api_scope'))) continue;
		$tables[] = $name;
	}

	$db->free();
	return $tables;
}

/**
 * @param int 	 $algorithm
 * @param        $user_id
 * @param        $salt
 * @param        $key_length
 * @param        $separator
 * @param        $block_length
 * @param int    $count
 *
 * @return string
 *
 * @link https://www.php.net/manual/en/function.hash-hmac.php#109260
 */
function pbkdf2($algorithm = 2, $user_id, $salt, $key_length, $separator, $block_length, $count = 1024) {
	$algos = [];
	foreach (hash_algos() as $id => $algo) {
		$algos[$id] = $algo;
	}
	if(empty($algorithm)) $algorithm = 2;
	if(empty($salt)) $salt = 'localhost';
	if(empty($key_length)) $key_length = 20;
	if(empty($separator)) $separator = '-';
	if(empty($block_length)) $separator = 4;
	$algorithm = strtolower($algos[$algorithm]);
	if(!in_array($algorithm, hash_algos(), true))
		die('PBKDF2 ERROR: Invalid hash algorithm.');
	if($count <= 0 || $key_length <= 0)
		die('PBKDF2 ERROR: Invalid parameters.');
	if(empty($user_id))
		die('You have to be logged in to generate a key!');

	$hash_length = strlen(hash($algorithm, "", true));
	$block_count = ceil($key_length / $hash_length);
	$output = '';
	for($i = 1; $i <= $block_count; $i++) {
		// $i encoded as 4 bytes, big endian.
		$last = $salt . pack("N", $i);
		// first iteration
		$last = $xorsum = hash_hmac($algorithm, $last, $user_id, true);
		// perform the other $count - 1 iterations
		for ($j = 1; $j < $count; $j++) {
			$xorsum ^= ($last = hash_hmac($algorithm, $last, $user_id, true));
		}
		$output .= $xorsum;
	}
	$output = bin2hex($output);

	$api = [];
	$block_length = floor($key_length/$block_length);
	for ($i = 0; $i < $key_length; $i += 4) {
		$tempK = '';
		for ($k = 0; $k < $block_length; $k++) {
			$tempK .= $output[($i+$k)];
		}
		$api[] = $tempK;
	}

	return implode($separator, $api);
}

$dleapi = array();
if (file_exists(ENGINE_DIR . '/data/' . $version['id'] . '.json'))
	$dleapi = json_decode(file_get_contents(ENGINE_DIR . '/data/' . $version['id'] . '.json'), true);

foreach ($dleapi as $name => $value) {
	$dleapi[$name] = htmlspecialchars( strip_tags( stripslashes( trim( urldecode ($value)))));
}

switch ($_GET['action']) {

	case 'keygenerate':
		if ($_GET) {
			ob_end_clean();

			$key = $_GET['save_con'];
			$key['user'] = (int)$key['user'];

			echo pbkdf2($dleapi['algo'], $key['user'], $dleapi['secret'], $dleapi['length'],
				$dleapi['trennen'], $dleapi['block']);
		}
		return false;

		break;

	case 'create':
		if ($_POST) {
			ob_end_clean();

			$key    = $_POST['save_con'];
			$tables = $_POST['tables'];

			$key['is_admin'] = $key['is_admin'] ? (bool)$key['is_admin'] : 0;
			$key['active']   = $key['active'] ? (bool)$key['active'] : 0;
			$key['user']     = (int)$key['user'];

			try {
				$key_api = $db->super_query('SELECT api FROM ' . PREFIX . "_api_keys WHERE api = '{$key['api']}' or user_id = {$key['user']}");
				if (count($key_api) === 0) {

					$db->query('INSERT INTO '.PREFIX."_api_keys (api, is_admin, creator, active, user_id) VALUES ('{$key['api']}', {$key['is_admin']}, {$_COOKIE['dle_user_id']}, {$key['active']}, {$key['user']})");
					$apiKey = $db->insert_id();

					foreach ($tables as $table => $data) {
						$data['read']   = $data['read'] ? (bool)$data['read'] : 0;
						$data['write']  = $data['write'] ? (bool)$data['write'] : 0;
						$data['delete'] = $data['delete'] ? (bool)$data['delete'] : 0;
						$db->query('INSERT INTO '.PREFIX."_api_scope (`table`, `read`, `write`, `delete`, `key_id`) VALUES ('{$table}', {$data['read']}, {$data['write']}, {$data['delete']}, {$apiKey})");
					}

					echo 'Ключ создан';
				} else {
					echo 'Этому пользователю уже был присвоен ключ доступа!';
				}
			} catch (Exception $e) {
				echo  $e->getMessage();
			}
		}
		return false;

		break;

	case 'save':
		if ($_POST) {
			ob_end_clean();

			$this_id = (int)$_GET['id'];

			$key    = $_POST['save_con'];
			$tables = $_POST['tables'];

			$key['is_admin'] = $key['is_admin'] ? (bool)$key['is_admin'] : 0;
			$key['active']   = $key['active'] ? (bool)$key['active'] : 0;
			$key['user']     = (int)$key['user'];

			try {

				$db->query('UPDATE '.PREFIX."_api_keys SET api = '{$key['api']}', is_admin = {$key['is_admin']}, active = {$key['active']}, user_id = {$key['user']} WHERE id = {$this_id}");

				foreach ($tables as $table => $data) {
					$data['read']   = $data['read'] ? (bool)$data['read'] : 0;
					$data['write']  = $data['write'] ? (bool)$data['write'] : 0;
					$data['delete'] = $data['delete'] ? (bool)$data['delete'] : 0;

					$tab = $db->super_query("SELECT count(*) as count FROM " . PREFIX . "_api_scope WHERE key_id = {$this_id} AND `table` = '{$table}'");

					if ($tab['count'] > 0) {
						$db->query("UPDATE " . PREFIX . "_api_scope SET `read`= {$data['read']}, `write`= {$data['write']}, `delete`= {$data['delete']} WHERE key_id = {$this_id} AND `table` = '{$table}'");
					} else {
						$db->query('INSERT INTO '.PREFIX."_api_scope (`table`, `read`, `write`, `delete`, `key_id`) VALUES ('{$table}', {$data['read']}, {$data['write']}, {$data['delete']}, {$this_id})");
					}
				}

					echo 'Ключ сохранён!';
			} catch (Exception $e) {
				echo  $e->getMessage();
			}
		}
		return false;

		break;

	case 'delete':
		if ($_POST) {
			ob_end_clean();

			$this_id = (int)$_GET['id'];


			try {

				$db->query('DELETE FROM '.PREFIX."_api_keys WHERE id = {$this_id}");

				echo 'Ключ удалён!';
			} catch (Exception $e) {
				echo  $e->getMessage();
			}
		}
		return false;

		break;

	case 'mass_delete':
		var_dump($_POST);
		var_dump($_GET);
		if ($_POST) {
			ob_end_clean();

			var_dump($_POST);


			try {


			} catch (Exception $e) {
				echo  $e->getMessage();
			}
		}
		return false;

		break;

	case 'savesettings':
		if ($_POST) {
			ob_end_clean();
			$confdata = array();
			foreach ($_POST['save'] as $name => $value) {
				$confdata[$name] = htmlspecialchars( strip_tags( stripslashes( trim( urlencode ($value)))));
			}

			file_put_contents(ENGINE_DIR . '/data/' . $version['id'] . '.json', json_encode($confdata));

			echo 'Данные сохранены';
		}
		return false;

		break;

	case 'settings':

		echo <<<HTML
			<form action="?mod={$version['id']}&action=settings" method="post" name="optionsbar" id="optionsbar">
			<div class="panel panel-default">
				<div class="panel-heading">
					{$version['name']}: настройка
			    </div>
				<div class="table-responsive">
				    <table class="table table-xs table-hover">
						<thead>
							<tr>
								<th>Название</th>
				        		<th>Поле</th>
							</tr>
						</thead>
						<tbody>
HTML;

							$dleapi['secret'] = $dleapi['secret'] ?: $config['http_home_url'];
							showRow('Алгоритм шифрования', 'Выбираем алгоритм шифрования. По умолчанию: md5',
								makeDropDown(hash_algos(), 'save[algo]', $dleapi['algo']));
							showRow('Длина блока', 'Задаём длину блока, по которой будет генерироваться автоматический API ключ.', '<input type="number" class="form-control" name="save[block]" value="' . $dleapi['block'] . '">');
							showRow('Длина ключа', 'Задаём длину ключа, по которой будет генерироваться автоматический API ключ. Разделитель не учитывается. Если будет не хватка символов, то будут генерироваться случайные символы, пока не заполнят длину ключа. Или же набор символов будет урезан. <br><b>Важно:</b> Деление общей длины и длины блока должно быть без остатка. Скрипт будет сам подставлять нужное значение.', '<input type="number" class="form-control" name="save[length]" value="' . $dleapi['length'] . '">');
							showRow('Разделитель блока', 'Задаём разделитель блока, который будет делить блоки. Пример: <b>-</b>',	'<input type="text" max="1" class="form-control" name="save[trennen]" value="' . $dleapi['trennen'] . '">');
							showRow('Секретный ключ', 'Секретный ключ для генерации ключа. Пример: <b>' .
								$config['http_home_url'] . '</b>',
								'<input type="text" class="form-control" name="save[secret]" value="' .
								$dleapi['secret'] . '">');

		echo <<<HTML

					 	</tbody>
					</table>
				</div>
				<div class="panel-footer">
				
					<a href="?mod={$version['id']}" class="btn bg-blue btn-sm btn-raised position-left" 
					role="button">Главная</a>
					<div class="pull-right">
						<a href="#" class="btn bg-teal btn-sm btn-raised position-left btn-save" 
					role="button">Сохранить</a>
					</div>
				</div>

				<script>
					$(() => {
					 	$('.btn-save').on('click', function() {
					 		$.ajax({
					 			url: '{$config['http_home_url']}{$config['admin_path']}?mod={$version['id']}&action=savesettings',
					 			method: 'POST',
					 			data: $('#optionsbar').serializeArray(),
					 			success: function(data) {
					 				$("#dlepopup").remove();
					 				$("body").append("<div id='dlepopup' title='Информация' style='display:none'>" + 
					 				data +
					 				"</div>");
					 				$('#dlepopup').dialog({
										autoOpen: true,
										width: 600,
										resizable: false,
										
									});
					 			}
					 		})
					 	});
					});
				</script>
HTML;

		break;

	case 'add':

		echo <<<HTML
			<form action="?mod={$version['id']}" method="post" name="optionsbar" id="optionsbar">
			<div class="panel panel-default">
				<div class="panel-heading">
					{$version['name']}: добавление ключа
			    </div>
				<div class="table-responsive">
				    <table class="table table-xs table-hover">
						<thead>
							<tr>
								<th>Название</th>
				        		<th>Поле</th>
							</tr>
						</thead>
						<tbody>
HTML;
							showRow( 'Ключ', 'Уникальный ключ доступа. Генерация ключа происходит при помощи алгоритма, ID пользователя и секретного ключа.',	'<input type="text" class="form-control" name="save_con[api]" value=""><br><input type="button" class="btn bg-teal-400 btn-sm btn-raised" id="genKey" value="Создать ключ">', 'white-line');
							showRow( 'Пользователь', 'Выбор пользователя для ключа', makeDropDown(getUsers(), 'save_con[user]', ''));
							showRow( 'Полный доступ', 'Данная опция будет игнорировать прочие полномочия и даст полный доступ ко всем таблицам', makeCheckBox('save_con[is_admin]', ''));
							showRow( 'Активен?', 'Данная опция включает этот ключ', makeCheckBox('save_con[active]','1'));
		echo <<<HTML
					 	</tbody>
					</table>
				</div>
				<div class="table-responsive">
				    <table class="table table-xs table-hover">
						<thead>
							<tr>
								<th>Название</th>
				        		<th>Чтение <input class="icheck" type="checkbox" data-type="readAll" 
				        		title="Включить все"></th>
				        		<th>Запись <input class="icheck" type="checkbox" data-type="writeAll" 
				        		title="Включить все"></th>
				        		<th>Удаление <input class="icheck" type="checkbox" data-type="deleteAll" 
				        		title="Включить все"></th>
							</tr>
						</thead>
						<tbody>
HTML;
							foreach (getTables() as $table ) {
								echo <<<HTML
									<tr>
        								<td class="col-xs-6 col-sm-6 col-md-6">
        									<h6 class="media-heading text-semibold">Таблица: {$table}</h6>
        								</td>
										<td class="col-xs-2 col-sm-2 col-md-2">
											<input class="icheck" type="checkbox" data-type="read" 
											name="tables[{$table}][read]" 
											value="1">
										</td>
										<td class="col-xs-2 col-sm-2 col-md-2">
											<input class="icheck" type="checkbox" data-type="write" 
											name="tables[{$table}][write]" 
											value="1">
										</td>
										<td class="col-xs-2 col-sm-2 col-md-2">
											<input class="icheck" type="checkbox" data-type="delete" 
											name="tables[{$table}][delete]" 
											value="1">
										</td>
									</tr>
HTML;
							}
		echo <<<HTML
					 	</tbody>
					</table>
				</div>
				<div class="panel-footer">
				
					<a href="?mod={$version['id']}" class="btn bg-blue btn-sm btn-raised position-left" 
					role="button">Главная</a>
					<div class="pull-right">
						<a href="#" class="btn bg-teal btn-sm btn-raised position-left btn-save" 
					role="button">Сохранить</a>
					</div>
				</div>

				<script>
					$(() => {
					 	$('.icheck[data-type="readAll"]').on('click', function() {
							$(document).find('[data-type="read"]').each(function(check) {
								let status = $(this).prop('checked');
								if (status)  $(this).prop('checked', false);
								else $(this).prop('checked', true);
							});
														
							$.uniform.update();
					 	});
					 	$('.icheck[data-type="writeAll"]').on('click', function() {
							$(document).find('[data-type="write"]').each(function(check) {
								let status = $(this).prop('checked');
								if (status) $(this).prop('checked', false);
								else $(this).prop('checked', true);
							});
							
							$.uniform.update();
					 	});
					 	$('.icheck[data-type="deleteAll"]').on('click', function() {
							$(document).find('[data-type="delete"]').each(function(check) {
								let status = $(this).prop('checked');
								if (status) $(this).prop('checked', false);
								else $(this).prop('checked', true);
							});
							
							$.uniform.update();
					 	});
					 	
					 	$('.btn-save').on('click', function() {
					 		$.ajax({
					 			url: '{$config['http_home_url']}{$config['admin_path']}?mod={$version['id']}&action=create',
					 			method: 'POST',
					 			data: $('#optionsbar').serializeArray(),
					 			success: function(data) {
					 				$("#dlepopup").remove();
					 				$("body").append("<div id='dlepopup' title='Информация' style='display:none'>" + 
					 				data +
					 				"</div>");
					 				$('#dlepopup').dialog({
										autoOpen: true,
										width: 600,
										resizable: false,
										
									});
					 			}
					 		})
					 	});
					 	
					 	$('#genKey').on('click', function() {
					 	    $('[name="save_con[api]"]').val('').html('');
					 		$.ajax({
					 			url: '{$config['http_home_url']}{$config['admin_path']}?mod={$version['id']}&action=keygenerate',
					 			method: 'GET',
					 			data: $('#optionsbar').serializeArray(),
					 			success: function(data) {
					 				$("#dlepopup").remove();
					 				$("body").append("<div id='dlepopup' title='Информация' style='display:none'>" + 
					 				data +
					 				"</div>");
					 				$('#dlepopup').dialog({
										autoOpen: true,
										width: 600,
										resizable: false,
									});
					 				$('[name="save_con[api]"]').val(data).html(data);
					 			}
					 		})
					 	});
					 	
					 	//
					 	// $(document).find('[data-type="read"]').each(function(check) {
						// 		let status = $(this).prop('checked');
						// 		if (status)  $(this).prop('checked', false);
						// 		else $(this).prop('checked', true);
						// 	});
					});
				
</script>
HTML;


		break;

	case 'edit':

		$this_id = (int)$_GET['id'];

		$api_key = $db->super_query('SELECT * FROM '. PREFIX . "_api_keys WHERE id = {$this_id}");

		echo <<<HTML
			<form action="?mod={$version['id']}&action=edit&id={$this_id}" method="post" name="optionsbar" 
			id="optionsbar">
			<div class="panel panel-default">
				<div class="panel-heading">
					{$version['name']}: обновление ключа
			    </div>
				<div class="table-responsive">
				    <table class="table table-xs table-hover">
						<thead>
							<tr>
								<th>Название</th>
				        		<th>Поле</th>
							</tr>
						</thead>
						<tbody>
HTML;
							showRow( 'Ключ', 'Уникальный ключ доступа. Генерация ключа происходит при помощи алгоритма, ID пользователя и секретного ключа.',	'<input type="text" class="form-control" name="save_con[api]" value="'. $api_key['api'] .'"><br><input type="button" class="btn bg-teal-400 btn-sm btn-raised" id="genKey" value="Создать ключ">', 'white-line');
							showRow( 'Пользователь', 'Выбор пользователя для ключа', makeDropDown(getUsers(), 'save_con[user]', $api_key['user_id']));
							showRow( 'Полный доступ', 'Данная опция будет игнорировать прочие полномочия и даст полный доступ ко всем таблицам', makeCheckBox('save_con[is_admin]', $api_key['is_admin'] ));
							showRow( 'Активен?', 'Данная опция включает этот ключ', makeCheckBox('save_con[active]',
								$api_key['active']));
		echo <<<HTML
					 	</tbody>
					</table>
				</div>
				<div class="table-responsive">
				    <table class="table table-xs table-hover">
						<thead>
							<tr>
								<th>Название</th>
				        		<th>Чтение <input class="icheck" type="checkbox" data-type="readAll" 
				        		title="Включить все"></th>
				        		<th>Запись <input class="icheck" type="checkbox" data-type="writeAll" 
				        		title="Включить все"></th>
				        		<th>Удаление <input class="icheck" type="checkbox" data-type="deleteAll" 
				        		title="Включить все"></th>
							</tr>
						</thead>
						<tbody>
HTML;

							foreach (getTables() as $tables) {
								$scope = $db->super_query('SELECT * FROM '. PREFIX . "_api_scope WHERE key_id = {$this_id} and `table` = '{$tables}'");
								$scope['read'] = $scope['read'] ? 1 : 0;
								$scope['write'] = $scope['write'] ? 1 : 0;
								$scope['delete'] = $scope['delete'] ? 1 : 0;
								$read = $scope['read'] ? ' checked': '';
								$write = $scope['write'] ? ' checked': '';
								$delete = $scope['delete'] ? ' checked': '';
								echo <<<HTML
									<tr>
        								<td class="col-xs-6 col-sm-6 col-md-6">
        									<h6 class="media-heading text-semibold">Таблица: {$tables}</h6>
        								</td>
										<td class="col-xs-2 col-sm-2 col-md-2">
											<input class="icheck" type="checkbox" data-type="read" 
											name="tables[{$tables}][read]" 
											value="1"{$read}>
										</td>
										<td class="col-xs-2 col-sm-2 col-md-2">
											<input class="icheck" type="checkbox" data-type="write" 
											name="tables[{$tables}][write]" 
											value="1"{$write}>
										</td>
										<td class="col-xs-2 col-sm-2 col-md-2">
											<input class="icheck" type="checkbox" data-type="delete" 
											name="tables[{$tables}][delete]" 
											value="1"{$delete}>
										</td>
									</tr>
HTML;
							}
		echo <<<HTML
					 	</tbody>
					</table>
				</div>
				<div class="panel-footer">
				
					<a href="?mod={$version['id']}" class="btn bg-blue btn-sm btn-raised position-left" 
					role="button">Главная</a>
					<div class="pull-right">
						<a href="#" class="btn bg-teal btn-sm btn-raised position-left btn-save" 
					role="button">Сохранить</a>
					</div>
				</div>

				<script>
					$(() => {
					 	$('.icheck[data-type="readAll"]').on('click', function() {
							$(document).find('[data-type="read"]').each(function(check) {
								let status = $(this).prop('checked');
								if (status)  $(this).prop('checked', false);
								else $(this).prop('checked', true);
							});
														
							$.uniform.update();
					 	});
					 	$('.icheck[data-type="writeAll"]').on('click', function() {
							$(document).find('[data-type="write"]').each(function(check) {
								let status = $(this).prop('checked');
								if (status) $(this).prop('checked', false);
								else $(this).prop('checked', true);
							});
							
							$.uniform.update();
					 	});
					 	$('.icheck[data-type="deleteAll"]').on('click', function() {
							$(document).find('[data-type="delete"]').each(function(check) {
								let status = $(this).prop('checked');
								if (status) $(this).prop('checked', false);
								else $(this).prop('checked', true);
							});
							
							$.uniform.update();
					 	});
					 	
					 	$('.btn-save').on('click', function() {
					 		$.ajax({
					 			url: '{$config['http_home_url']}{$config['admin_path']}?mod={$version['id']}&action=save&id={$_GET['id']}',
					 			method: 'POST',
					 			data: $('#optionsbar').serializeArray(),
					 			success: function(data) {
					 				$("#dlepopup").remove();
					 				$("body").append("<div id='dlepopup' title='Информация' style='display:none'>" + 
					 				data +
					 				"</div>");
					 				$('#dlepopup').dialog({
										autoOpen: true,
										width: 600,
										resizable: false,
									});
					 			}
					 		})
					 	});
					 	
					 	$('#genKey').on('click', function() {
					 	    $('[name="save_con[api]"]').val('').html('');
					 		$.ajax({
					 			url: '{$config['http_home_url']}{$config['admin_path']}?mod={$version['id']}&action=keygenerate',
					 			method: 'GET',
					 			data: $('#optionsbar').serializeArray(),
					 			success: function(data) {
					 				$("#dlepopup").remove();
					 				$("body").append("<div id='dlepopup' title='Информация' style='display:none'>" + 
					 				data +
					 				"</div>");
					 				$('#dlepopup').dialog({
										autoOpen: true,
										width: 600,
										resizable: false,
									});
					 				$('[name="save_con[api]"]').val(data).html(data);
					 			}
					 		})
					 	});
					 	
					 	//
					 	// $(document).find('[data-type="read"]').each(function(check) {
						// 		let status = $(this).prop('checked');
						// 		if (status)  $(this).prop('checked', false);
						// 		else $(this).prop('checked', true);
						// 	});
					});
				
</script>
HTML;


		break;

	default:


		$start_from = (int)$_REQUEST['start_from'];
		$api_per_page = 50;

		if( $start_from < 0 ) $start_from = 0;
		echo <<<HTML
<form action="?mod={$version['id']}" method="get" name="navi" id="navi">
<input type="hidden" name="mod" value="{$version['id']}">
<input type="hidden" name="start_from" id="start_from" value="{$start_from}">
</form>
<form action="?mod={$version['id']}" method="post" name="optionsbar" id="optionsbar">
<input type="hidden" name="mod" value="{$version['id']}">
<input type="hidden" name="user_hash" value="{$dle_login_hash}">
<input type="hidden" name="start_from" id="start_from" value="{$start_from}">
<div class="panel panel-default">
	<div class="panel-heading">
		{$version['name']}
  	</div>
HTML;

		$i = $start_from+$api_per_page;


		$result_count = $db->super_query('SELECT COUNT(*) as count FROM '. PREFIX . "_api_keys {$where}");
		$all_count_apis = $result_count['count'];


		// pagination

		$npp_nav = "";

		if( $all_count_apis > $api_per_page ) {

			if( $start_from > 0 ) {
				$previous = $start_from - $api_per_page;
				$npp_nav .= "<li><a onclick=\"javascript:search_submit($previous); return(false);\" href=\"#\" title=\"{$lang['edit_prev']}\">&lt;&lt;</a></li>";
			}

			$enpages_count = @ceil( $all_count_apis / $api_per_page );
			$enpages_start_from = 0;
			$enpages = "";

			if( $enpages_count <= 10 ) {

				for($j = 1; $j <= $enpages_count; $j ++) {

					if( $enpages_start_from != $start_from ) {

						$enpages .= "<li><a onclick=\"javascript:search_submit($enpages_start_from); return(false);\" href=\"#\">$j</a></li>";

					} else {

						$enpages .= "<li class=\"active\"><span>$j</span></li>";
					}

					$enpages_start_from += $api_per_page;
				}

				$npp_nav .= $enpages;

			} else {

				$start = 1;
				$end = 10;

				if( $start_from > 0 ) {

					if( ($start_from / $api_per_page) > 4 ) {

						$start = @ceil( $start_from / $api_per_page ) - 3;
						$end = $start + 9;

						if( $end > $enpages_count ) {
							$start = $enpages_count - 10;
							$end = $enpages_count - 1;
						}

						$enpages_start_from = ($start - 1) * $api_per_page;

					}

				}

				if( $start > 2 ) {

					$enpages .= "<li><a onclick=\"javascript:search_submit(0); return(false);\" href=\"#\">1</a></li> <li><span>...</span></li>";

				}

				for($j = $start; $j <= $end; $j ++) {

					if( $enpages_start_from != $start_from ) {

						$enpages .= "<li><a onclick=\"javascript:search_submit($enpages_start_from); return(false);\" href=\"#\">$j</a></li>";

					} else {

						$enpages .= "<li class=\"active\"><span>$j</span></li>";
					}

					$enpages_start_from += $api_per_page;
				}

				$enpages_start_from = ($enpages_count - 1) * $api_per_page;
				$enpages .= "<li><span>...</span></li><li><a onclick=\"javascript:search_submit($enpages_start_from); return(false);\" href=\"#\">$enpages_count</a></li>";

				$npp_nav .= $enpages;

			}

			if( $all_count_apis > $i ) {
				$how_next = $all_count_apis - $i;
				if( $how_next > $api_per_page ) {
					$how_next = $api_per_page;
				}
				$npp_nav .= "<li><a onclick=\"javascript:search_submit($i); return(false);\" href=\"#\" title=\"{$lang['edit_next']}\">&gt;&gt;</a></li>";
			}

			$npp_nav = "<ul class=\"pagination pagination-sm\">".$npp_nav."</ul>";

		}

		// pagination

		$i = 0;

		if ( $all_count_apis ) {

			$entries = "";

			$db->query("SELECT * FROM " . PREFIX . "_api_keys api, ". USERPREFIX . "_users users WHERE api.user_id = users.user_id ORDER BY api.id DESC LIMIT {$start_from},{$api_per_page}");

			while($row = $db->get_row()) {

				$menu_link = <<<HTML
        <div class="btn-group">
          <a href="#" class="dropdown-toggle nocolor" data-toggle="dropdown" aria-expanded="true"><i class="fa fa-bars"></i><span class="caret"></span></a>
          <ul class="dropdown-menu text-left dropdown-menu-right">
            <li><a uid="{$row['id']}" href="?mod={$version['id']}&action=edit&id={$row['id']}" class="editlink"><i class="fa 
            fa-pencil-square-o position-left"></i>{$lang['word_ledit']}</a></li>
			<li class="divider"></li>
            <li><a data-id="{$row['id']}" class="btn-delete" href="#"><i class="fa fa-trash-o 
            position-left text-danger"></i>{$lang['word_ldel']}</a></li>
          </ul>
        </div>
HTML;

				$status = $row['active'] ? 'активен' : 'неактивен';

				$entries .= "<tr id='api_{$row['id']}'>
        <td style=\"word-break: break-all;\"><div id=\"content_{$row['id']}\">{$row['id']}</div></td>
        <td style=\"word-break: break-all;\"><div id=\"key_{$row['id']}\">{$row['api']}</div></td>
        <td style=\"word-break: break-all;\"><div id=\"date_{$row['id']}\">{$row['created']}</div></td>
        <td style=\"word-break: break-all;\"><div id=\"user_{$row['id']}\">{$row['name']}</div></td>
        <td style=\"word-break: break-all;\"><div id=\"status_{$row['id']}\">{$status}</div></td>
        <td align=\"center\">{$menu_link}</td>
        <td><input name=\"selected_tags[]\" value=\"{$row['id']}\" type=\"checkbox\" class=\"icheck\"></td>
        </tr>";

			}

			$db->free();

			echo <<<HTML
<div class="table-responsive">
    <table class="table table-xs table-hover">
      <thead>
      <tr>
        <th>#</th>
        <th>Ключ</th>
        <th>Дата</th>
        <th>Пользователь</th>
        <th>Статус</th>
        <th style="width: 70px">&nbsp;</th>
        <th style="width: 40px"><input class="icheck" type="checkbox" name="master_box" title="{$lang['edit_selall']}" onclick="javascript:ckeck_uncheck_all()"></th>
      </tr>
      </thead>
	  <tbody>
		{$entries}
	  </tbody>
	</table>
</div>
<div class="panel-footer">
	<div class="pull-right">
	<a href="?mod={$version['id']}&action=add" class="btn bg-teal btn-sm btn-raised position-left" role="button">Новый ключ</a>
	<a href="?mod={$version['id']}&action=settings" class="btn bg-blue btn-sm btn-raised position-left" 
	role="button">Настройки</a>
	<select class="uniform position-left" name="action" data-dropdown-align-right="auto">
		<option value="">{$lang['edit_selact']}</option>
		<option value="mass_deactivate">Деактивировать</option>
		<option value="mass_set_admin">Дать полный доступ</option>
		<option value="mass_remove_admin">Снять полный доступ</option>
		<option value="mass_delete">{$lang['edit_seldel']}</option>
	</select>
	<input class="btn bg-brown-600 btn-sm btn-raised" type="submit" value="{$lang['b_start']}">
	</div>
</div>
HTML;


		}  else {

			echo <<<HTML
<div class="panel-body">
<table width="100%">
    <tr>
        <td style="height:50px;"><div align="center">{$lang['links_not_found']}</div></td>
    </tr>
</table>
</div>
<div class="panel-footer">
	<a href="?mod={$version['id']}&action=add" class="btn bg-teal btn-sm btn-raised position-left" role="button">Новый ключ</a>
	<a href="?mod={$version['id']}&action=settings" class="btn bg-blue btn-sm btn-raised position-left" 
	role="button">Настройки</a>
	</div>
HTML;

		}

		echo <<<HTML
</div>
<div class="mb-20">{$npp_nav}</div>
</form>

<script>  
<!--

	$(function() {
		$('.table').find('tr > td:last-child').find('input[type=checkbox]').on('change', function() {
			if($(this).is(':checked')) {
				$(this).parents('tr').addClass('warning');
			}
			else {
				$(this).parents('tr').removeClass('warning');
			}
		});
	});
	
	function ckeck_uncheck_all() {
	    const frm = document.optionsbar;
	    for (let i=0;i<frm.elements.length;i++) {
	        const elmnt = frm.elements[i];
	        if (elmnt.type==='checkbox') {
	            if(frm.master_box.checked === true){ elmnt.checked=false; $(elmnt).parents('tr').removeClass('warning'); }
	            else{ elmnt.checked=true; $(elmnt).parents('tr').addClass('warning');}
	        }
	    }
	    frm.master_box.checked=frm.master_box.checked!==true;
		
		$(frm.master_box).parents('tr').removeClass('warning');
		
		$.uniform.update();
	
	}
	
	function search_submit(prm){
      document.navi.start_from.value=prm;
      document.navi.submit();
      return false;
    }
    
    
					 	
					 	$('.btn-delete').on('click', function() {
					 		$.ajax({
					 			url: '{$config['http_home_url']}{$config['admin_path']}?mod={$version['id']}&action=delete&id=' + $(this).data('id'),
					 			method: 'POST',
					 			data: $('#optionsbar').serializeArray(),
					 			success: function(data) {
					 				$("#dlepopup").remove();
					 				$("body").append("<div id='dlepopup' title='Информация' style='display:none'>" + 
					 				data +
					 				"</div>");
					 				$('#dlepopup').dialog({
										autoOpen: true,
										width: 600,
										resizable: false,
									});
					 				$('#api_' +  $(this).data('id')).remove();
					 			}
					 		})
					 	});
	
	
//-->
</script>
HTML;

		break;
}


echofooter();
?>