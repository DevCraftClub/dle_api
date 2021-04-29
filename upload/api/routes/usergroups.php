<?php
if( !defined( 'DATALIFEENGINE' ) ) {
    header( "HTTP/1.1 403 Forbidden" );
    header ( 'Location: ../../' );
    die( "Hacking attempt!" );
}

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$api_name = 'usergroups';
$possibleData = array(
	array(
		'name' => 'id',
		'type' => 'integer',
		'required' => false,
		'post' => false,
		'length' => 0
	),
	array(
		'name' => 'group_name',
		'type' => 'string',
		'required' => false,
		'post' => true,
		'length' => 50
	),
	array(
		'name' => 'allow_cats',
		'type' => 'string',
		'required' => true,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'allow_adds',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'cat_add',
		'type' => 'string',
		'required' => true,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'allow_admin',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'allow_addc',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'allow_editc',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'allow_delc',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'edit_allc',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'del_allc',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'moderation',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'allow_all_edit',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'allow_edit',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'allow_pm',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'max_pm',
		'type' => 'integer',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'max_foto',
		'type' => 'string',
		'required' => false,
		'post' => true,
		'length' => 10
	),
	array(
		'name' => 'allow_files',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'allow_hide',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'allow_short',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'time_limit',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'rid',
		'type' => 'integer',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'allow_fixed',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'allow_feed',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'allow_search',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'allow_poll',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'allow_main',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'captcha',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'icon',
		'type' => 'string',
		'required' => false,
		'post' => true,
		'length' => 200
	),
	array(
		'name' => 'allow_modc',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'allow_rating',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'allow_offline',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'allow_image_upload',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'allow_file_upload',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'allow_signature',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'allow_url',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'news_sec_code',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'allow_image',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'max_signature',
		'type' => 'integer',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'max_info',
		'type' => 'integer',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'admin_addnews',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'admin_editnews',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'admin_comments',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'admin_categories',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'admin_editusers',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'admin_wordfilter',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'admin_xfields',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'admin_userfields',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'admin_static',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'admin_editvote',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'admin_newsletter',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'admin_blockip',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'admin_banners',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'admin_rss',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'admin_iptools',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'admin_rssinform',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'admin_googlemap',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'allow_html',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'group_prefix',
		'type' => 'string',
		'required' => true,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'group_sufix',
		'type' => 'string',
		'required' => true,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'allow_subscribe',
		'type' => 'integer',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'allow_image_size',
		'type' => 'integer',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'cat_allow_addnews',
		'type' => 'string',
		'required' => true,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'flood_news',
		'type' => 'integer',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'max_day_news',
		'type' => 'integer',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'force_leech',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'edit_limit',
		'type' => 'integer',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'captcha_pm',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'max_pm_day',
		'type' => 'integer',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'max_mail_day',
		'type' => 'integer',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'admin_tagscloud',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'allow_vote',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'admin_complaint',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'news_question',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'comments_question',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'max_comment_day',
		'type' => 'integer',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'max_images',
		'type' => 'integer',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'max_files',
		'type' => 'integer',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'disable_news_captcha',
		'type' => 'integer',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'disable_comments_captcha',
		'type' => 'integer',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'pm_question',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'captcha_feedback',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'feedback_question',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'files_type',
		'type' => 'string',
		'required' => false,
		'post' => true,
		'length' => 255
	),
	array(
		'name' => 'max_file_size',
		'type' => 'integer',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'file_max_speed',
		'type' => 'integer',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'spamfilter',
		'type' => 'integer',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'allow_comments_rating',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'max_edit_days',
		'type' => 'integer',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'spampmfilter',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'force_reg',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'force_reg_days',
		'type' => 'integer',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'force_reg_group',
		'type' => 'integer',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'force_news',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'force_news_count',
		'type' => 'integer',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'force_news_group',
		'type' => 'integer',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'force_comments',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'force_comments_count',
		'type' => 'integer',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'force_comments_group',
		'type' => 'integer',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'force_rating',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'force_rating_count',
		'type' => 'integer',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'force_rating_group',
		'type' => 'integer',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'not_allow_cats',
		'type' => 'string',
		'required' => true,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'allow_up_image',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'allow_up_watermark',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'allow_up_thumb',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'up_count_image',
		'type' => 'integer',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'up_image_side',
		'type' => 'string',
		'required' => false,
		'post' => true,
		'length' => 20
	),
	array(
		'name' => 'up_image_size',
		'type' => 'integer',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'up_thumb_size',
		'type' => 'integer',
		'required' => false,
		'post' => true,
		'length' => 20
	),
	array(
		'name' => 'allow_mail_files',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'max_mail_files',
		'type' => 'integer',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'max_mail_allfiles',
		'type' => 'integer',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'mail_file_type',
		'type' => 'string',
		'required' => false,
		'post' => true,
		'length' => 100
	),
	array(
		'name' => 'video_comments',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'media_comments',
		'type' => 'boolean',
		'required' => false,
		'post' => true,
		'length' => 0
	),
	array(
		'name' => 'min_image_side',
		'type' => 'integer',
		'required' => false,
		'post' => true,
		'length' => 20
	),
);

// possibleData
// $possibleData[] = array(
//                  'name' => 'DBColumn name',
//                  'type' => "Type of value",  // integer, string, boolean, double
//                  'required' => true/false,   // Обязательное поле?
//                  'post' => true/false,       // Разрешить использовать при добавлении или редактуре?
//                  'length' => 0,				// Указывается ограничение для типа string. Содержимое будет обрезаться при нарушении макс. значения
// );
// possibleData Add

$app->group('/' . $api_name, function ( ) use ( $connect, $api_name, $possibleData ) {
	$header = array();
	$access = array(
		'full' => false,
		'can_read' => false,
		'can_write' => false,
		'can_delete' => false,
	);

	$this->get('[/]', function (Request $request, Response $response, Array $args) use ($possibleData, $api_name, $connect, $header, $access ) {
		foreach ( $request->getHeaders() as $name => $value ) {
			$name = strtolower(str_replace('HTTP_', '', $name));
			$header[$name] = $value[0];
		}

		$checkAccess = checkAPI($header['x_api_key'], $api_name);
		if (isset($checkAccess['error'])) return $response->withStatus(400)->getBody()->write(json_encode(array('error' => $checkAccess['error'])));

		$access['full'] = $checkAccess['admin'];
		$access['can_read'] = $checkAccess['read'];
		$access['own_only'] = $checkAccess['own'];

		if ($access['full'] || $access['can_read']) {
			$orderBy = $header['orderby'] ?: 'id';
			$sort = $header['sort'] ?: 'DESC';
			$limit = $header['limit'] ? 'LIMIT '.(int)$header['limit'] : '';

			$possibleParams = '';

			foreach ( $header as $data => $value) {
				$keyData = array_search($data, array_column($possibleData, 'name'));
				if ($keyData !== false) {
					$postData = $possibleData[$keyData];
					if ( strlen( $possibleParams ) === 0 ) $possibleParams .= " WHERE {$data}" . getComparer( $header[$data], $postData['type'] );
					else $possibleParams .= " AND {$data}" . getComparer( $header[$data], $postData['type'] );
				}
			}

			$sql = 'SELECT * FROM '. PREFIX . "_{$api_name} {$possibleParams} ORDER by {$orderBy} {$sort} {$limit}";

			$getData = new CacheSystem($api_name, $sql);
			if (empty($getData->get())) {
				$data = $connect->query($sql);
				$getData->setData($data);
				$data = $getData->create();
			} else {
				$data = $getData->get();
			}

			$response->withStatus( 200 )->getBody()->write( $data );

		} else {

			$response->withStatus(400)->getBody()->write(json_encode(array('error' => 'У вас нет прав на просмотр данных!')));

		}

		return $response->withHeader('Content-type', 'application/json; charset=UTF-8');
	});

	$this->post('[/]', function (Request $request, Response $response, Array $args) use ($possibleData, $api_name, $connect, $header, $access ) {
		foreach ( $request->getHeaders() as $name => $value ) {
			$name = strtolower(str_replace('HTTP_', '', $name));
			$header[$name] = $value[0];
		}

		$body = array();
		foreach( $request->getParsedBody() as $name => $value ) $body[$name] = $value;


		if (empty($body))
			return $response->withStatus(400)->getBody()->write(json_encode(array('error' => 'Требуемая информация пуста: Заполните POST-форму и попробуйте снова!')));

		$checkAccess = checkAPI($header['x_api_key'], $api_name);
		if (isset($checkAccess['error'])) return $response->withStatus(400)->getBody()->write(json_encode(array('error' => $checkAccess['error'])));

		$access['full'] = $checkAccess['admin'];
		$access['can_write'] = $checkAccess['write'];

		if ($access['full'] || $access['can_write']) {

			$names = array();
			$values = array();

			foreach ( $body as $name => $value ) {
				$keyNum = array_search($name, array_column($possibleData, 'name'));

				if ($keyNum !== false) {
					$keyData = $possibleData[$keyNum];

					if ( $keyData['post'] === false) continue;

					if ( $keyData['required'] && empty($value))
						return $response->withStatus(400)->getBody()->write(json_encode(array('error' => "Требуемая информация отсутствует: {$name}!")));

					$names[] = $name;
					$values[] = defType(checkLength($value, $keyData['length']), $keyData['type']);

				}
			}

			$names = implode(', ', $names);
			$values = implode(', ', $values);

			$sql = "INSERT INTO " . PREFIX . "_{$api_name} ({$names}) VALUES ({$values})";
			$connect->query( $sql );

			// Почему я не люблю MySQL? Потому что нельзя вернуть данные сразу после добавления в базу данных!
			// All Heil PostgreSQL! `INSERT INTO xxx (yyy) VALUES (zzz) RETURNING *`! Вот так просто!
			// Но нет, в MySQL нужно строить такой костыль!!!
			$lastID = $connect->lastInsertId();
			$sql = "SELECT * FROM " . PREFIX . "_{$api_name} WHERE id = :id";
			$data = $connect->row($sql, array('id' => $lastID));

			$cache = new CacheSystem($api_name, $sql);
			$cache->clear($api_name);
			$cache->setData(json_encode($data));

			$response->withStatus( 200 )->getBody()->write( json_encode( $data ) );

		} else {

			$response->withStatus(400)->getBody()->write(json_encode(array('error' => 'У вас нет прав на добавление новых данных!')));

		}

		return $response->withHeader('Content-type', 'application/json; charset=UTF-8');
	});

	$this->put('/{id:[0-9]+}[/]', function (Request $request, Response $response, Array $args) use ($possibleData, $api_name, $connect, $header, $access ) {
		foreach ( $request->getHeaders() as $name => $value ) {
			$name = strtolower(str_replace('HTTP_', '', $name));
			$header[$name] = $value[0];
		}

		$body = array();
		foreach( $request->getParsedBody() as $name => $value ) $body[$name] = $value;

		if (empty($body))
			return $response->withStatus(400)->getBody()->write(json_encode(array('error' => 'Требуемая информация пуста: Заполните POST-форму и попробуйте снова!')));

		$checkAccess = checkAPI($header['x_api_key'], $api_name);
		if (isset($checkAccess['error'])) return $response->withStatus(400)->getBody()->write(json_encode(array('error' => $checkAccess['error'])));

		$access['full'] = $checkAccess['admin'];
		$access['can_write'] = $checkAccess['write'];

		if ($access['full'] || $access['can_write']) {

			$id = $args['id'];
			if (!(int)$id)
				return $response->withStatus(400)->getBody()->write(json_encode(array('error' => 'Требуемая информация отсутствует: ID!')));
			$values = array();

			foreach ( $body as $name => $value ) {
				if ( defType($value) !== null && in_array($name, $possibleData)) {
					$keyNum = array_search($name, array_column($possibleData, 'name'));

					if ($keyNum !== false) {
						$keyData = $possibleData[$keyNum];

						$values[] ="{$name} = " . defType(checkLength($value, $keyData['length']), $keyData['type']);

					}
				}
			}
			$values = implode(', ', $values);

			$sql = 'UPDATE '. PREFIX . "_{$api_name} SET {$values} WHERE id = :id";
			$connect->query( $sql, array('id' => $id) );

			$sql = 'SELECT * FROM '. PREFIX . "_{$api_name} WHERE id = :id";
			$data = $connect->row($sql, array('id' => $id));

			$cache = new CacheSystem($api_name, $sql);
			$cache->clear($api_name);
			$cache->setData(json_encode($data));

			$response->withStatus( 200 )->getBody()->write( json_encode( $data ) );

		} else {

			$response->withStatus(400)->getBody()->write(json_encode(array('error' => 'У вас нет прав на изменение данных!')));

		}

		return $response->withHeader('Content-type', 'application/json; charset=UTF-8');
	});
	$this->delete('/{id:[0-9]+}[/]', function (Request $request, Response $response, Array $args) use ($possibleData, $api_name, $connect, $header, $access ) {
		foreach ( $request->getHeaders() as $name => $value ) {
			$name = strtolower(str_replace('HTTP_', '', $name));
			$header[$name] = $value[0];
		}

		$checkAccess = checkAPI($header['x_api_key'], $api_name);
		if (isset($checkAccess['error'])) return $response->withStatus(400)->getBody()->write(json_encode(array('error' => $checkAccess['error'])));

		$access['full'] = $checkAccess['admin'];
		$access['can_delete'] = $checkAccess['delete'];

		if ($access['full'] || $access['can_delete']) {

			$id = $args['id'];
			if (!(int)$id)
				return $response->withStatus(400)->getBody()->write(json_encode(array('error' => 'Требуемая информация отсутствует: ID!')));

			$sql = 'DELETE FROM '. PREFIX . "_{$api_name} WHERE id = {$id}";
			$connect->query( $sql );

			$cache = new CacheSystem($api_name, $sql);
			$cache->clear($api_name);

			$response->withStatus( 200 )->getBody()->write( json_encode( array('success' => 'Данные успешно удалены!') ) );

		} else {

			$response->withStatus(400)->getBody()->write(json_encode(array('error' => 'У вас нет прав на удаление данных!')));

		}

		return $response->withHeader('Content-type', 'application/json; charset=UTF-8');
	});

	// Own routing Add
});
