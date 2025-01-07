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

if (!defined('DATALIFEENGINE') or !defined('LOGGED_IN')) {
	header("HTTP/1.1 403 Forbidden");
	header('Location: ../../');
	die("Hacking attempt!");
}

global $db, $config, $dle_login_hash, $_TIME;

$version = [
	'name'      => 'DLE-API',
	'descr'     => 'Неофициальное API',
	'version'   => '173.0.37',
	'changelog' => [
		'173.0.37' => [
			'[FIX] Исправлена мелкие проблемы с маршутизацией',
		],
		'173.0.36' => [
			'[FIX] Исправлена заявленная ошибка для запросов POST & PUT',
			'[UPDATE] Обновлено до версии DLE 17.3',
			'[UPDATE] Обновлено до версии PHP 8.3 (Она же является минимальной)',
			'[UPDATE] Переструктурирована сама логика работы с CRUD запросами',
		],
		'160.0.35' => [
			'[FIX] Исправлена <a href="https://github.com/DevCraftClub/dle_api/issues/11" target="_blank">заявленная ошибка</a>',
			'[FIX] Исправлены функции проверки доступа для других пользователей и гостевых ключей',
		],
		'160.0.34' => [
			'[FIX] Исправлена <a href="https://github.com/DevCraftClub/dle_api/issues/10" target="_blank">заявленная ошибка</a>',
		],
		'160.0.33' => [
			'[FIX] Исправлена <a href="https://github.com/DevCraftClub/dle_api/issues/7" target="_blank">заявленная ошибка</a>',
			'[FIX] Исправлено подключение встроенного DLE API',
		],
		'0.3.2' => [
			'[FIX] Дозалил пропуски',
		],
		'0.3.1' => [
			'[FIX] Исправлена <a href="https://skripters.biz/forum/threads/dle-api.50709/post-499483" target="_blank">заявленная ошибка</a>',
			'[FIX] Исправлена <a href="https://skripters.biz/forum/threads/dle-api.50709/post-499640" target="_blank">заявленная ошибка</a>',
		],
		'0.3.0' => [
			'Оптимизация кода под PHP 8.2',
			'Минимально поддерживаемая версия DLE - 16.0',
			'Изменён список алгоритмов на hash_hmac_algos'
		],
		'0.2.2' => [
			'[FIX] Поправлены файлы рутинга, которые забыл заменить',
			'[FIX] Поправлена функция сохранения кеша, из-за чего выдавало ответ null',
		],
		'0.2.1' => [
			'Убрана процедура проверки атрибута в таблице'
		],
		'0.2.0' => [
			'Оптимизирован код',
			'Генерация ключа привязана к времени',
			'Добавлена возможность добавлять ключи независимо от пользователя => для этого нужно выбрать в меню пользователя "Гость / Неавторизованный"',
			'Добавлена возможность генерировать свои рутеры на основе базы данных => читайте документацию',
			'Обновлены данные для таблиц базы данных',
			'Обновлена документация API',
			'Минимальные требования к серверу поднял до минимально требуемой версии от DLE-News -> 7.4, возможно не будет работать на версиях ниже 15.х'
		],
		'0.1.4' => [
			'Безопасный вывод данных (IP, пароли и хэш суммы)',
			'Добавлена функция вывода только принадлежащих API-ключу записей',
			'Добавлена функция массовых действий (удаление, (де-)активация, снятие / добавление ограничение, снятие / удаление администраторских ограничений)'
		],
		'0.1.3' => [
			'Для удаления новостей была использована функция движка',
			'Для удаления комментариев была использована функция движка',
			'Для удаления новостей была использована функция движка',
			'Изменил нумерование версий',
			'composer настроил под версию PHP 5.6'
		],
		'0.0.2' => [
			'Были исправлены несколько багов, спасибо @jyarali',
			'Исправлена проверка ключей в базе данных',
			'Добавлена проверка по длине значения для типа "string"',
			'Массивы с ячейками таблиц были обновлены до значений DLE 14.1 (на ранние версии DLE это никак не влияет)',
			'Для пользователей был использован штатный API класс самой DLE, чтобы авторизовать и регистрировать пользователей',
			'Для авторизации пользователей нужно при помощи метода POST указать следующий путь URL: api/v1/users/auth. В заголовке обязательно должны быть значения имя пользователя и его пароль в незакодированном виде.',
			'Для регистрации пользователя нужно при помощи метода POST указать следующий путь URL: api/v1/users/register. В заголовке нужно указать имя пользователя, пароль, электронную почту и ID группы пользователей.',
			'При регистрации и авторизации возвращается массив данных об этом пользователе'
		],
		'0.0.1' => [
			'Первая стандартная версия'
		],
	],
	'id'        => 'dleapi',
];

$dleapi = array();
if (file_exists(ENGINE_DIR . '/data/' . $version['id'] . '.json'))
	$dleapi = json_decode(file_get_contents(ENGINE_DIR . '/data/' . $version['id'] . '.json'), true);

foreach ($dleapi as $name => $value) {
	$dleapi[$name] = htmlspecialchars(strip_tags(stripslashes(trim(urldecode($value)))));
}

$subtitle = ['' => $version['descr']];
if ($_GET['action'] === 'add') $subtitle = ['?mod=dleapi' => $version['descr'], '' => 'Добавление ключа'];
else if ($_GET['action'] === 'settings') $subtitle = ['?mod=dleapi' => $version['descr'], '' => 'Настройка'];
else if ($_GET['action'] === 'edit') $subtitle = ['?mod=dleapi' => $version['descr'], '' => 'Обновление ключа'];
else if ($_GET['action'] === 'changelog') $subtitle = ['?mod=dleapi' => $version['descr'], '' => 'Изменения в всериях'];

echoheader("<i class=\"fa fa-id-card-o position-left\"></i><span class=\"text-semibold\">{$version['name']} (v{$version['version']})</span>", $subtitle);

/**
 * Функция создания заголовка
 *
 * @param string|null $title
 * @param string|null $description
 * @param string|null $field
 * @param string|null $class
 * @return void
 */
function showRow(?string $title = null, ?string $description = null, ?string $field = null, ?string $class = null) : void {

	echo "<tr>
        <td class=\"col-xs-6 col-sm-6 col-md-5\"><h6 class=\"media-heading text-semibold\">{$title}</h6><span class=\"text-muted text-size-small hidden-xs\">{$description}</span></td>
        <td class=\"col-xs-6 col-sm-6 col-md-7\">{$field}</td>
        </tr>";
}

/**
 * Функция выпадающего меню
 *
 * @param array $options
 * @param string $name
 * @param string|null $selected
 *
 * @return string
 */
function makeDropDown(array $options, string $name, ?string $selected = null) : string {
	$output = "<select class=\"uniform\" name=\"$name\">\r\n";
	foreach ($options as $value => $description) {
		$output .= "<option value=\"$value\"";
		if ($selected == $value) {
			$output .= " selected ";
		}
		$output .= ">$description</option>\n";
	}
	$output .= "</select>";
	return $output;
}

/**
 * Функция создания чекбокса / флажка
 *
 * @param string $name
 * @param string|null $selected
 *
 * @return string
 */
function makeCheckBox(string $name, ?string $selected = null) : string {

	$selected = $selected ? "checked" : "";

	return "<input class=\"switch\" type=\"checkbox\" name=\"{$name}\" value=\"1\" {$selected}>";

}

/**
 * Функция вывода всех пользователей в массиве в виде 1 => "Пользователь"
 *
 * @return array
 */
function getUsers() : array {
	global $db;

	$db->query('SELECT * FROM ' . USERPREFIX . "_users WHERE restricted = '0'");
	$user_ar = array(
		0 => 'Гость / Неавторизованный'
	);

	while ($build = $db->get_array()) {
		$user_ar[$build['user_id']] = $build['name'];
	}

	$db->free();
	return $user_ar;
}

/**
 * Функция вывода всех таблиц в виде массива
 *
 * @return array
 */
function getTables() : array {
	global $db;

	$db->query('SHOW tables');
	$tables = array();

	while ($build = $db->get_array()) {
		$name = str_replace(array(PREFIX . '_', USERPREFIX . '_'), '', $build[0]);
		if (in_array($name, array('api_keys', 'api_scope'))) continue;
		$tables[] = $name;
	}

	$db->free();
	return $tables;
}

/**
 * @link https://www.php.net/manual/en/function.hash-hmac.php#109260
 *
 * @param int $algorithm
 * @param int $user_id
 * @param string $salt
 * @param int $key_length
 * @param string $separator
 * @param int $block_length
 * @param int $count
 *
 * @return string
 */
function pbkdf2(int $algorithm = 2, int $user_id, string $salt, int $key_length, string $separator, int $block_length, int $count = 1024) : string {
	global $_TIME, $dleapi;
	$algos = [];
	foreach (hash_hmac_algos() as $id => $algo) {
		$algos[$id] = $algo;
	}
	if (empty($salt)) $salt = 'localhost';
	if (empty($key_length)) $key_length = 20;
	if (empty($separator)) $separator = '-';
	if (empty($block_length)) $separator = 4;
	$algo = strtolower($algos[$algorithm]);
	if (!in_array($algo, $algos, true))
		die('PBKDF2 ERROR: Invalid hash algorithm.');
	if ($count <= 0 || $key_length <= 0)
		die('PBKDF2 ERROR: Invalid parameters.');
	if ($user_id !== 0 && empty($user_id))
		die('You have to be logged in to generate a key!');
	if (count($dleapi) === 0) die('Нужно сначала настроить плагин, затем создавать ключ!');

	$hash_length = strlen(hash($algo, "", true));
	$block_count = ceil($key_length / $hash_length);
	$output      = '';
	$salt        = $_TIME . $salt;
	for ($i = 1; $i <= $block_count; $i++) {
		// $i encoded as 4 bytes, big endian.
		$last = $salt . pack("N", $i);
		// first iteration
		$last = $xorsum = hash_hmac($algo, $last, $user_id, true);
		// perform the other $count - 1 iterations
		for ($j = 1; $j < $count; $j++) {
			$xorsum ^= ($last = hash_hmac($algo, $last, $user_id, true));
		}
		$output .= $xorsum;
	}
	$output = bin2hex($output);

	$api          = [];
	$block_length = floor($key_length / $block_length);
	for ($i = 0; $i < $key_length; $i += 4) {
		$tempK = '';
		for ($k = 0; $k < $block_length; $k++) {
			$tempK .= $output[($i + $k)];
		}
		$api[] = $tempK;
	}

	return implode($separator, $api);
}

$action = (empty($action)) ? $_GET['action'] : $action;

switch ($action) {

	case 'keygenerate':
		if ($_GET) {
			ob_end_clean();

			$key         = $_GET['save_con'];
			$key['user'] = (int) $key['user'];

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

			$key['is_admin'] = $key['is_admin'] ? (bool) $key['is_admin'] : 0;
			$key['active']   = $key['active'] ? (bool) $key['active'] : 0;
			$key['own_only'] = $key['own_only'] ? (bool) $key['own_only'] : 0;
			$key['user']     = (int) $key['user'];

			try {
				$key_api = $db->super_query('SELECT api FROM ' . PREFIX . "_api_keys WHERE (api = '{$key['api']}' or user_id = {$key['user']}) and user_id <> 0");
				try {
					if (is_null($key_api) || count($key_api) === 0) {

						$db->query('INSERT INTO ' . PREFIX .
							"_api_keys (api, is_admin, creator, active, user_id, own_only) VALUES ('{$key['api']}', {$key['is_admin']}, {$_COOKIE['dle_user_id']}, {$key['active']}, {$key['user']}, {$key['own_only']})");
						$apiKey = $db->insert_id();

						foreach ($tables as $table => $data) {
							$data['read']   = $data['read'] ? (bool) $data['read'] : 0;
							$data['write']  = $data['write'] ? (bool) $data['write'] : 0;
							$data['delete'] = $data['delete'] ? (bool) $data['delete'] : 0;
							$db->query('INSERT INTO ' . PREFIX . "_api_scope (`table`, `read`, `write`, `delete`, `key_id`) VALUES ('{$table}', {$data['read']}, {$data['write']}, {$data['delete']}, {$apiKey})");
						}

						echo 'Ключ создан';
					} else {
						echo 'Этому пользователю уже был присвоен ключ доступа!';
					}
				} catch (Exception $e) {
					echo $e->getMessage();
				}
			} catch (Exception $e) {
				msg('error', 'Всё плохо', $e->getMessage(), ['?mod=dleapi' => 'К списку']);
			}
		}
		return false;

		break;

	case 'save':
		if ($_POST) {
			ob_end_clean();

			$this_id = (int) $_GET['id'];

			$key    = $_POST['save_con'];
			$tables = $_POST['tables'];

			$key['is_admin'] = $key['is_admin'] ? (bool) $key['is_admin'] : 0;
			$key['active']   = $key['active'] ? (bool) $key['active'] : 0;
			$key['own_only'] = $key['own_only'] ? (bool) $key['own_only'] : 0;
			$key['user']     = (int) $key['user'];

			try {

				$db->query('UPDATE ' . PREFIX . "_api_keys SET api = '{$key['api']}', is_admin = {$key['is_admin']}, active = {$key['active']}, user_id = {$key['user']}, own_only = {$key['own_only']} WHERE id = {$this_id}");

				foreach ($tables as $table => $data) {
					$data['read']   = $data['read'] ? (bool) $data['read'] : 0;
					$data['write']  = $data['write'] ? (bool) $data['write'] : 0;
					$data['delete'] = $data['delete'] ? (bool) $data['delete'] : 0;

					$tab = $db->super_query("SELECT count(*) as count FROM " . PREFIX . "_api_scope WHERE key_id = {$this_id} AND `table` = '{$table}'");

					if ($tab['count'] > 0) {
						$db->query("UPDATE " . PREFIX . "_api_scope SET `read`= {$data['read']}, `write`= {$data['write']}, `delete`= {$data['delete']} WHERE key_id = {$this_id} AND `table` = '{$table}'");
					} else {
						$db->query('INSERT INTO ' . PREFIX . "_api_scope (`table`, `read`, `write`, `delete`, `key_id`) VALUES ('{$table}', {$data['read']}, {$data['write']}, {$data['delete']}, {$this_id})");
					}
				}

				echo 'Ключ сохранён!';
			} catch (Exception $e) {
				echo $e->getMessage();
			}
		}
		return false;

		break;

	case 'delete':
		if ($_POST) {
			ob_end_clean();

			$this_id = (int) $_GET['id'];


			try {

				$db->query('DELETE FROM ' . PREFIX . "_api_keys WHERE id = {$this_id}");

				echo 'Ключ удалён!';
			} catch (Exception $e) {
				echo $e->getMessage();
			}
		}
		return false;

		break;

	case 'mass_delete':

		if ($_POST) {
			ob_end_clean();

			try {

				foreach ($_POST['selected_keys'] as $key) $db->query('DELETE FROM ' . PREFIX . "_api_keys WHERE id = {$key}");
				msg('success', 'Всё прошло успешно', 'Ключи были удалены!', ['?mod=dleapi' => 'К списку']);

			} catch (Exception $e) {
				msg('error', 'Всё плохо', $e->getMessage(), ['?mod=dleapi' => 'К списку']);
			}
		}
		return false;

		break;

	case 'mass_deactivate':

		if ($_POST) {
			ob_end_clean();

			try {

				foreach ($_POST['selected_keys'] as $key) $db->query('UPDATE ' . PREFIX . "_api_keys SET active = 0 WHERE id = {$key}");
				msg('success', 'Всё прошло успешно', 'Ключи были деактивированы', ['?mod=dleapi' => 'К списку']);

			} catch (Exception $e) {
				msg('error', 'Всё плохо', $e->getMessage(), ['?mod=dleapi' => 'К списку']);
			}
		}
		return false;

		break;

	case 'mass_activate':

		if ($_POST) {
			ob_end_clean();

			try {

				foreach ($_POST['selected_keys'] as $key) $db->query('UPDATE ' . PREFIX . "_api_keys SET active = 1 WHERE id = {$key}");
				msg('success', 'Всё прошло успешно', 'Ключи были активированы', ['?mod=dleapi' => 'К списку']);

			} catch (Exception $e) {
				msg('error', 'Всё плохо', $e->getMessage(), ['?mod=dleapi' => 'К списку']);
			}
		}
		return false;

		break;

	case 'mass_set_admin':

		if ($_POST) {
			ob_end_clean();

			try {

				foreach ($_POST['selected_keys'] as $key) $db->query('UPDATE ' . PREFIX . "_api_keys SET is_admin = 1 WHERE id = {$key}");
				msg('success', 'Всё прошло успешно', 'Доступ был выдан', ['?mod=dleapi' => 'К списку']);

			} catch (Exception $e) {
				msg('error', 'Всё плохо', $e->getMessage(), ['?mod=dleapi' => 'К списку']);
			}
		}
		return false;

		break;

	case 'mass_remove_admin':

		if ($_POST) {
			ob_end_clean();

			try {

				foreach ($_POST['selected_keys'] as $key) $db->query('UPDATE ' . PREFIX . "_api_keys SET is_admin = 0 WHERE id = {$key}");
				msg('success', 'Всё прошло успешно', 'Доступ был снят', ['?mod=dleapi' => 'К списку']);

			} catch (Exception $e) {
				msg('error', 'Всё плохо', $e->getMessage(), ['?mod=dleapi' => 'К списку']);
			}
		}
		return false;

		break;

	case 'mass_set_own':

		if ($_POST) {
			ob_end_clean();

			try {

				foreach ($_POST['selected_keys'] as $key)
					$db->query('UPDATE ' . PREFIX . "_api_keys SET own_only = 1 WHERE id = {$key}");
				msg('success', 'Всё прошло успешно', 'Ограничение на свои записи было установлено', [
					'?mod=dleapi' => 'К списку'
				]);

			} catch (Exception $e) {
				msg('error', 'Всё плохо', $e->getMessage(), ['?mod=dleapi' => 'К списку']);
			}
		}
		return false;

		break;

	case 'mass_remove_own':

		if ($_POST) {
			ob_end_clean();

			try {

				foreach ($_POST['selected_keys'] as $key)
					$db->query('UPDATE ' . PREFIX . "_api_keys SET own_only = 0 WHERE id = {$key}");
				msg('success', 'Всё прошло успешно', 'Ограничение на свои записи было снято', [
					'?mod=dleapi' => 'К списку'
				]);

			} catch (Exception $e) {
				msg('error', 'Всё плохо', $e->getMessage(), ['?mod=dleapi' => 'К списку']);
			}
		}
		return false;

		break;

	case 'savesettings':
		if ($_POST) {
			ob_end_clean();
			$confdata = array();
			foreach ($_POST['save'] as $name => $value) {
				$confdata[$name] = htmlspecialchars(strip_tags(stripslashes(trim(urlencode($value)))));
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
		showRow('Безопасный вывод информации', 'Вместо паролей, IP-адресов и хэшсум будет выводить заглушилки',
			makeCheckBox('save[secure]', $dleapi['secure']));
		showRow('Длина блока', 'Задаём длину блока, по которой будет генерироваться автоматический API ключ.',
			'<input type="number" class="form-control" name="save[block]" value="' . $dleapi['block'] . '">');
		showRow('Длина ключа',
			'Задаём длину ключа, по которой будет генерироваться автоматический API ключ. Разделитель не учитывается. Если будет не хватка символов, то будут генерироваться случайные символы, пока не заполнят длину ключа. Или же набор символов будет урезан. <br><b>Важно:</b> Деление общей длины и длины блока должно быть без остатка. Скрипт будет сам подставлять нужное значение.',
			'<input type="number" class="form-control" name="save[length]" value="' . $dleapi['length'] . '">');
		showRow('Разделитель блока', 'Задаём разделитель блока, который будет делить блоки. Пример: <b>-</b>',
			'<input type="text" max="1" class="form-control" name="save[trennen]" value="' . $dleapi['trennen'] .
			'">');
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
		showRow('Ключ',
			'Уникальный ключ доступа. Генерация ключа происходит при помощи алгоритма, ID пользователя и секретного ключа.',
			'<input type="text" class="form-control" name="save_con[api]" value=""><br><input type="button" class="btn bg-teal-400 btn-sm btn-raised" id="genKey" value="Создать ключ">',
			'white-line');
		showRow('Пользователь', 'Выбор пользователя для ключа', makeDropDown(getUsers(), 'save_con[user]', ''));
		showRow('Полный доступ',
			'Данная опция будет игнорировать прочие полномочия и даст полный доступ ко всем таблицам',
			makeCheckBox('save_con[is_admin]', ''));
		showRow('Только своё?', 'Данная опция будет выводить только те данные, что связаны с API пользователя.',
			makeCheckBox('save_con[own_only]', ''));
		showRow('Активен?', 'Данная опция включает этот ключ', makeCheckBox('save_con[active]', '1'));
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
		foreach (getTables() as $table) {
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

		$this_id = (int) $_GET['id'];

		$api_key = $db->super_query('SELECT * FROM ' . PREFIX . "_api_keys WHERE id = {$this_id}");

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
		showRow('Ключ',
			'Уникальный ключ доступа. Генерация ключа происходит при помощи алгоритма, ID пользователя и секретного ключа.',
			'<input type="text" class="form-control" name="save_con[api]" value="' . $api_key['api'] .
			'"><br><input type="button" class="btn bg-teal-400 btn-sm btn-raised" id="genKey" value="Создать ключ">',
			'white-line');
		showRow('Пользователь', 'Выбор пользователя для ключа',
			makeDropDown(getUsers(), 'save_con[user]', $api_key['user_id']));
		showRow('Полный доступ',
			'Данная опция будет игнорировать прочие полномочия и даст полный доступ ко всем таблицам',
			makeCheckBox('save_con[is_admin]', $api_key['is_admin']));
		showRow('Только своё?', 'Данная опция будет выводить только те данные, что связаны с API пользователя.',
			makeCheckBox('save_con[own_only]', $api_key['own_only']));
		showRow('Активен?', 'Данная опция включает этот ключ', makeCheckBox('save_con[active]',
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
			$scope           = $db->super_query('SELECT * FROM ' . PREFIX . "_api_scope WHERE key_id = {$this_id} and `table` = '{$tables}'");
			$scope['read']   = $scope['read'] ? 1 : 0;
			$scope['write']  = $scope['write'] ? 1 : 0;
			$scope['delete'] = $scope['delete'] ? 1 : 0;
			$read            = $scope['read'] ? ' checked' : '';
			$write           = $scope['write'] ? ' checked' : '';
			$delete          = $scope['delete'] ? ' checked' : '';
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

	case 'changelog':


		echo <<<HTML
		<div class="panel panel-default">
			<div class="panel-heading">
				{$version['name']}: История изменений
			</div>
			<div class="panel-body">
				<div class="accordion" id="accordion">
HTML;

		foreach ($version['changelog'] as $id => $c) {
			$vId  = str_replace('.', '', $id);
			$cont = <<<HTML
					<div class="accordion-group">
						<div class="accordion-heading">
							<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" 
							href="#collapse{$vId}" aria-expanded="false"><b>Версия</b>: {$id}</a>
						</div>
						<div id="collapse{$vId}" class="accordion-body collapse" aria-expanded="false" >
							<div class="accordion-inner mt-20" >
								<ul class="list-circle">
HTML;
			foreach ($c as $i => $l) $cont .= "<li>{$l}</li>";
			$cont .= <<<HTML
      						
								</ul>				
							</div>
						</div>
					</div>
HTML;
			echo $cont;
		}
		echo <<<HTML
		</div></div><div class="panel-footer">
	<a href="?mod={$version['id']}" class="btn bg-green btn-sm btn-raised position-left" role="button">Главная</a>
	<a href="?mod={$version['id']}&action=add" class="btn bg-teal btn-sm btn-raised position-left" role="button">Новый ключ</a>
	<a href="?mod={$version['id']}&action=settings" class="btn bg-blue btn-sm btn-raised position-left" 
	role="button">Настройки</a>
	</div></div>
HTML;

		break;

	default:


		$start_from   = (int) $_REQUEST['start_from'];
		$api_per_page = 50;

		if ($start_from < 0) $start_from = 0;
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

		$i = $start_from + $api_per_page;


		$result_count   = $db->super_query('SELECT COUNT(*) as count FROM ' . PREFIX . "_api_keys {$where}");
		$all_count_apis = $result_count['count'];


		// pagination

		$npp_nav = "";

		if ($all_count_apis > $api_per_page) {

			if ($start_from > 0) {
				$previous = $start_from - $api_per_page;
				$npp_nav  .= "<li><a onclick=\"javascript:search_submit($previous); return(false);\" href=\"#\" title=\"{$lang['edit_prev']}\">&lt;&lt;</a></li>";
			}

			$enpages_count      = @ceil($all_count_apis / $api_per_page);
			$enpages_start_from = 0;
			$enpages            = "";

			if ($enpages_count <= 10) {

				for ($j = 1; $j <= $enpages_count; $j++) {

					if ($enpages_start_from != $start_from) {

						$enpages .= "<li><a onclick=\"javascript:search_submit($enpages_start_from); return(false);\" href=\"#\">$j</a></li>";

					} else {

						$enpages .= "<li class=\"active\"><span>$j</span></li>";
					}

					$enpages_start_from += $api_per_page;
				}

				$npp_nav .= $enpages;

			} else {

				$start = 1;
				$end   = 10;

				if ($start_from > 0) {

					if (($start_from / $api_per_page) > 4) {

						$start = @ceil($start_from / $api_per_page) - 3;
						$end   = $start + 9;

						if ($end > $enpages_count) {
							$start = $enpages_count - 10;
							$end   = $enpages_count - 1;
						}

						$enpages_start_from = ($start - 1) * $api_per_page;

					}

				}

				if ($start > 2) {

					$enpages .= "<li><a onclick=\"javascript:search_submit(0); return(false);\" href=\"#\">1</a></li> <li><span>...</span></li>";

				}

				for ($j = $start; $j <= $end; $j++) {

					if ($enpages_start_from != $start_from) {

						$enpages .= "<li><a onclick=\"javascript:search_submit($enpages_start_from); return(false);\" href=\"#\">$j</a></li>";

					} else {

						$enpages .= "<li class=\"active\"><span>$j</span></li>";
					}

					$enpages_start_from += $api_per_page;
				}

				$enpages_start_from = ($enpages_count - 1) * $api_per_page;
				$enpages            .= "<li><span>...</span></li><li><a onclick=\"javascript:search_submit($enpages_start_from); return(false);\" href=\"#\">$enpages_count</a></li>";

				$npp_nav .= $enpages;

			}

			if ($all_count_apis > $i) {
				$how_next = $all_count_apis - $i;
				if ($how_next > $api_per_page) {
					$how_next = $api_per_page;
				}
				$npp_nav .= "<li><a onclick=\"javascript:search_submit($i); return(false);\" href=\"#\" title=\"{$lang['edit_next']}\">&gt;&gt;</a></li>";
			}

			$npp_nav = "<ul class=\"pagination pagination-sm\">" . $npp_nav . "</ul>";

		}

		// pagination

		$i = 0;

		if ($all_count_apis) {

			$entries = "";

			$users = getUsers();

			$db->query("SELECT * FROM " . PREFIX . "_api_keys api ORDER BY api.id DESC LIMIT {$start_from},{$api_per_page}");

			while ($row = $db->get_row()) {
				$user_name = $users[$row['user_id']];

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
				$own    = $row['own_only'] ? '<i class="fa fa-check-circle text-success text-success"></i>'
					: '<i class="fa fa-times-circle text-danger"></i>';
				$admin  = $row['is_admin'] ? '<i class="fa fa-check-circle text-success"></i>'
					: '<i class="fa fa-times-circle text-danger"></i>';

				$entries .= "<tr id='api_{$row['id']}'>
        <td style=\"word-break: break-all;\"><div id=\"content_{$row['id']}\">{$row['id']}</div></td>
        <td style=\"word-break: break-all;\"><div id=\"key_{$row['id']}\">{$row['api']}</div></td>
        <td style=\"word-break: break-all;\"><div id=\"date_{$row['id']}\">{$row['created']}</div></td>
        <td style=\"word-break: break-all;\"><div id=\"user_{$row['id']}\">{$user_name}</div></td>
        <td style=\"word-break: break-all;\"><div id=\"status_{$row['id']}\">{$status}</div></td>
        <td style=\"word-break: break-all;\"><div id=\"own_{$row['id']}\" style='text-align:center'>{$own}</div></td>
        <td style=\"word-break: break-all;\"><div id=\"admin{$row['id']}\" style='text-align:center'>{$admin}</div></td>
        <td align=\"center\">{$menu_link}</td>
        <td><input name=\"selected_keys[]\" value=\"{$row['id']}\" type=\"checkbox\" class=\"icheck\"></td>
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
        <th style='text-align:center'>Только своё</th>
        <th style='text-align:center'>Полный доступ</th>
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
	<a href="?mod={$version['id']}&action=settings" class="btn bg-blue btn-sm btn-raised position-left" role="button">Настройки</a>
	<a href="?mod={$version['id']}&action=changelog" class="btn bg-green btn-sm btn-raised position-left" role="button">История версиий</a>
	<select class="uniform position-left" name="action" data-dropdown-align-right="auto">
		<option value="">{$lang['edit_selact']}</option>
		<option value="mass_deactivate">Деактивировать</option>
		<option value="mass_activate">Активировать</option>
		<option value="mass_set_admin">Дать полный доступ</option>
		<option value="mass_remove_admin">Снять полный доступ</option>
		<option value="mass_set_own">Ограничить только своими данными</option>
		<option value="mass_remove_own">Снять ограничение на 'только свои данные'</option>
		<option value="mass_delete">{$lang['edit_seldel']}</option>
	</select>
	<input class="btn bg-brown-600 btn-sm btn-raised" type="submit" value="{$lang['b_start']}">
	</div>
</div>
HTML;


		} else {

			echo <<<HTML
<div class="panel-body">
<table width="100%">
    <tr>
        <td style="height:50px;"><div align="center">Ещё не было создано ни одного API ключа</div></td>
    </tr>
</table>
</div>
<div class="panel-footer">
	<a href="?mod={$version['id']}&action=add" class="btn bg-teal btn-sm btn-raised position-left" role="button">Новый ключ</a>
	<a href="?mod={$version['id']}&action=settings" class="btn bg-blue btn-sm btn-raised position-left" 
	role="button">Настройки</a>
	<a href="?mod={$version['id']}&action=changelog" class="btn bg-green btn-sm btn-raised position-left" role="button">История версиий</a>
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