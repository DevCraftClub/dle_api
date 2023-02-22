<?php
if (!defined('DATALIFEENGINE')) {
	header("HTTP/1.1 403 Forbidden");
	header('Location: ../../');
	die("Hacking attempt!");
}

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$api_name     = 'usergroups';
$possibleData = array(
	array(
		'name'     => 'id',
		'type'     => 'integer',
		'required' => true,
		'post'     => false,
		'length'   => 6
	),
	array(
		'name'     => 'group_name',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 50
	),
	array(
		'name'     => 'allow_cats',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 0
	),
	array(
		'name'     => 'allow_adds',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'cat_add',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 0
	),
	array(
		'name'     => 'allow_admin',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'allow_addc',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'allow_editc',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'allow_delc',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'edit_allc',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'del_allc',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'moderation',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'allow_all_edit',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'allow_edit',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'allow_pm',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'max_pm',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 6
	),
	array(
		'name'     => 'max_foto',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 10
	),
	array(
		'name'     => 'allow_files',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'allow_hide',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'allow_short',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'time_limit',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'rid',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 6
	),
	array(
		'name'     => 'allow_fixed',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'allow_feed',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'allow_search',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'allow_poll',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'allow_main',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'captcha',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'icon',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 200
	),
	array(
		'name'     => 'allow_modc',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'allow_rating',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'allow_offline',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'allow_image_upload',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'allow_file_upload',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'allow_signature',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'allow_url',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'news_sec_code',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'allow_image',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'max_signature',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 6
	),
	array(
		'name'     => 'max_info',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 6
	),
	array(
		'name'     => 'admin_addnews',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'admin_editnews',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'admin_comments',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'admin_categories',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'admin_editusers',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'admin_wordfilter',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'admin_xfields',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'admin_userfields',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'admin_static',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'admin_editvote',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'admin_newsletter',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'admin_blockip',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'admin_banners',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'admin_rss',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'admin_iptools',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'admin_rssinform',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'admin_googlemap',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'allow_html',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'group_prefix',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 0
	),
	array(
		'name'     => 'group_suffix',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 0
	),
	array(
		'name'     => 'allow_subscribe',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'allow_image_size',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'cat_allow_addnews',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 0
	),
	array(
		'name'     => 'flood_news',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 6
	),
	array(
		'name'     => 'max_day_news',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 6
	),
	array(
		'name'     => 'force_leech',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'edit_limit',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 6
	),
	array(
		'name'     => 'captcha_pm',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'max_pm_day',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 6
	),
	array(
		'name'     => 'max_mail_day',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 6
	),
	array(
		'name'     => 'admin_tagscloud',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'allow_vote',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'admin_complaint',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'news_question',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'comments_question',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'max_comment_day',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 6
	),
	array(
		'name'     => 'max_images',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 6
	),
	array(
		'name'     => 'max_files',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 6
	),
	array(
		'name'     => 'disable_news_captcha',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 6
	),
	array(
		'name'     => 'disable_comments_captcha',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 6
	),
	array(
		'name'     => 'pm_question',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'captcha_feedback',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'feedback_question',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'files_type',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 255
	),
	array(
		'name'     => 'max_file_size',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 9
	),
	array(
		'name'     => 'files_max_speed',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 6
	),
	array(
		'name'     => 'spamfilter',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'allow_comments_rating',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'max_edit_days',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'spampmfilter',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'force_reg',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'force_reg_days',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 9
	),
	array(
		'name'     => 'force_reg_group',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 6
	),
	array(
		'name'     => 'force_news',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'force_news_count',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 9
	),
	array(
		'name'     => 'force_news_group',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 6
	),
	array(
		'name'     => 'force_comments',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'force_comments_count',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 9
	),
	array(
		'name'     => 'force_comments_group',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 6
	),
	array(
		'name'     => 'force_rating',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'force_rating_count',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 9
	),
	array(
		'name'     => 'force_rating_group',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 6
	),
	array(
		'name'     => 'not_allow_cats',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 0
	),
	array(
		'name'     => 'allow_up_image',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'allow_up_watermark',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'allow_up_thumb',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'up_count_image',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 6
	),
	array(
		'name'     => 'up_image_side',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 20
	),
	array(
		'name'     => 'up_image_size',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 9
	),
	array(
		'name'     => 'up_thumb_size',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 20
	),
	array(
		'name'     => 'allow_mail_files',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'max_mail_files',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 6
	),
	array(
		'name'     => 'max_mail_allfiles',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 9
	),
	array(
		'name'     => 'mail_files_type',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 100
	),
	array(
		'name'     => 'video_comments',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'media_comments',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'min_image_side',
		'type'     => 'string',
		'required' => true,
		'post'     => true,
		'length'   => 20
	),
	array(
		'name'     => 'allow_public_file_upload',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'force_comments_rating',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 1
	),
	array(
		'name'     => 'force_comments_rating_count',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 9
	),
	array(
		'name'     => 'force_comments_rating_group',
		'type'     => 'integer',
		'required' => true,
		'post'     => true,
		'length'   => 6
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

$app->group('/' . $api_name, function () use ($connect, $api_name, $possibleData) {
	$header = array();
	$access = array(
		'full'       => false,
		'can_read'   => false,
		'can_write'  => false,
		'can_delete' => false,
	);

	$this->get('[/]', function (Request $request, Response $response, array $args) use ($possibleData, $api_name, $connect, $header, $access) {
		foreach ($request->getHeaders() as $name => $value) {
			$name          = strtolower(str_replace('HTTP_', '', $name));
			$header[$name] = $value[0];
		}

		$params = [];
		foreach ($request->getQueryParams() as $name => $value) $params[$name] = $value;

		$api_key  = $params['x-api-key'] ?? $header['x_api_key'];
		$order_by = $params['orderby'] ?? $header['orderby'];
		$sort     = $params['sort'] ?? $header['sort'];
		$limit    = $params['limit'] ?? $header['limit'];

		$checkAccess = checkAPI($api_key, $api_name);
		if (isset($checkAccess['error'])) return $response->withStatus(400)->getBody()->write(json_encode(array('error' => $checkAccess['error']), JSON_UNESCAPED_UNICODE));

		$access['full']     = $checkAccess['admin'];
		$access['can_read'] = $checkAccess['read'];
		$access['own_only'] = $checkAccess['own'];

		if ($access['full'] || $access['can_read']) {
			$orderBy = $order_by ?: 'id';
			$sort    = $sort ?: 'DESC';
			$limit   = $limit ? 'LIMIT ' . (int) $limit : '';

			$possibleParams = '';

			foreach ($header as $data => $value) {
				$keyData = array_search($data, array_column($possibleData, 'name'));
				if ($keyData !== false) {
					$postData = $possibleData[$keyData];
					if (strlen($possibleParams) === 0) $possibleParams .= " WHERE {$data}" . getComparer($header[$data], $postData['type']);
					else $possibleParams .= " AND {$data}" . getComparer($header[$data], $postData['type']);
				}
			}

			$sql = 'SELECT * FROM ' . PREFIX . "_{$api_name} {$possibleParams} ORDER by {$orderBy} {$sort} {$limit}";

			$getData = new CacheSystem($api_name, $sql);
			if (empty($getData->get())) {
				$data = $connect->query($sql);
				$getData->setData($data);
				$data = $getData->create();
			} else {
				$data = $getData->get();
			}

			$response->withStatus(200)->getBody()->write($data);

		} else {

			$response->withStatus(400)->getBody()->write(json_encode(array('error' => 'У вас нет прав на просмотр данных!'), JSON_UNESCAPED_UNICODE));

		}

		return $response->withHeader('Content-type', 'application/json; charset=UTF-8');
	});

	$this->post('[/]', function (Request $request, Response $response, array $args) use ($possibleData, $api_name, $connect, $header, $access) {
		foreach ($request->getHeaders() as $name => $value) {
			$name          = strtolower(str_replace('HTTP_', '', $name));
			$header[$name] = $value[0];
		}

		$body = array();
		foreach ($request->getParsedBody() as $name => $value) $body[$name] = $value;

		$params = [];
		foreach ($request->getQueryParams() as $name => $value) $params[$name] = $value;

		$api_key = $params['x-api-key'] ?? $header['x_api_key'];

		if (empty($body))
			return $response->withStatus(400)->getBody()->write(json_encode(array('error' => 'Требуемая информация пуста: Заполните POST-форму и попробуйте снова!'), JSON_UNESCAPED_UNICODE));

		$checkAccess = checkAPI($api_key, $api_name);
		if (isset($checkAccess['error'])) return $response->withStatus(400)->getBody()->write(json_encode(array('error' => $checkAccess['error']), JSON_UNESCAPED_UNICODE));

		$access['full']      = $checkAccess['admin'];
		$access['can_write'] = $checkAccess['write'];

		if ($access['full'] || $access['can_write']) {

			$names  = array();
			$values = array();

			foreach ($body as $name => $value) {
				$keyNum = array_search($name, array_column($possibleData, 'name'));

				if ($keyNum !== false) {
					$keyData = $possibleData[$keyNum];

					if ($keyData['post'] === false) continue;

					if ($keyData['required'] && empty($value))
						return $response->withStatus(400)->getBody()->write(json_encode(array('error' => "Требуемая информация отсутствует: {$name}!"), JSON_UNESCAPED_UNICODE));

					$names[]  = $name;
					$values[] = defType(checkLength($value, $keyData['length']), $keyData['type']);

				}
			}

			$names  = implode(', ', $names);
			$values = implode(', ', $values);

			$sql = "INSERT INTO " . PREFIX . "_{$api_name} ({$names}) VALUES ({$values})";
			$connect->query($sql);

			// Почему я не люблю MySQL? Потому что нельзя вернуть данные сразу после добавления в базу данных!
			// All Heil PostgreSQL! `INSERT INTO xxx (yyy) VALUES (zzz) RETURNING *`! Вот так просто!
			// Но нет, в MySQL нужно строить такой костыль!!!
			$lastID = $connect->lastInsertId();
			$sql    = "SELECT * FROM " . PREFIX . "_{$api_name} WHERE id = :id";
			$data   = $connect->row($sql, array('id' => $lastID));

			$cache = new CacheSystem($api_name, $sql);
			$cache->clear($api_name);
			$cache->setData($data);

			$response->withStatus(200)->getBody()->write($data);

		} else {

			$response->withStatus(400)->getBody()->write(json_encode(array('error' => 'У вас нет прав на добавление новых данных!'), JSON_UNESCAPED_UNICODE));

		}

		return $response->withHeader('Content-type', 'application/json; charset=UTF-8');
	});

	$this->put('/{id:[0-9]+}[/]', function (Request $request, Response $response, array $args) use ($possibleData, $api_name, $connect, $header, $access) {
		foreach ($request->getHeaders() as $name => $value) {
			$name          = strtolower(str_replace('HTTP_', '', $name));
			$header[$name] = $value[0];
		}

		$body = array();
		foreach ($request->getParsedBody() as $name => $value) $body[$name] = $value;

		$params = [];
		foreach ($request->getQueryParams() as $name => $value) $params[$name] = $value;

		$api_key = $params['x-api-key'] ?? $header['x_api_key'];
		if (empty($body))
			return $response->withStatus(400)->getBody()->write(json_encode(array('error' => 'Требуемая информация пуста: Заполните POST-форму и попробуйте снова!'), JSON_UNESCAPED_UNICODE));

		$checkAccess = checkAPI($api_key, $api_name);
		if (isset($checkAccess['error'])) return $response->withStatus(400)->getBody()->write(json_encode(array('error' => $checkAccess['error']), JSON_UNESCAPED_UNICODE));

		$access['full']      = $checkAccess['admin'];
		$access['can_write'] = $checkAccess['write'];

		if ($access['full'] || $access['can_write']) {

			$id = $args['id'];
			if (!(int) $id)
				return $response->withStatus(400)->getBody()->write(json_encode(array('error' => 'Требуемая информация отсутствует: ID!'), JSON_UNESCAPED_UNICODE));
			$values = array();

			foreach ($body as $name => $value) {
				if (defType($value) !== null && in_array($name, $possibleData)) {
					$keyNum = array_search($name, array_column($possibleData, 'name'));

					if ($keyNum !== false) {
						$keyData = $possibleData[$keyNum];

						$values[] = "{$name} = " . defType(checkLength($value, $keyData['length']), $keyData['type']);

					}
				}
			}
			$values = implode(', ', $values);

			$sql = 'UPDATE ' . PREFIX . "_{$api_name} SET {$values} WHERE id = :id";
			$connect->query($sql, array('id' => $id));

			$sql  = 'SELECT * FROM ' . PREFIX . "_{$api_name} WHERE id = :id";
			$data = $connect->row($sql, array('id' => $id));

			$cache = new CacheSystem($api_name, $sql);
			$cache->clear($api_name);
			$cache->setData($data);

			$response->withStatus(200)->getBody()->write($data);

		} else {

			$response->withStatus(400)->getBody()->write(json_encode(array('error' => 'У вас нет прав на изменение данных!'), JSON_UNESCAPED_UNICODE));

		}

		return $response->withHeader('Content-type', 'application/json; charset=UTF-8');
	});
	$this->delete('/{id:[0-9]+}[/]', function (Request $request, Response $response, array $args) use ($possibleData, $api_name, $connect, $header, $access) {
		foreach ($request->getHeaders() as $name => $value) {
			$name          = strtolower(str_replace('HTTP_', '', $name));
			$header[$name] = $value[0];
		}

		$params = [];
		foreach ($request->getQueryParams() as $name => $value) $params[$name] = $value;

		$api_key = $params['x-api-key'] ?? $header['x_api_key'];

		$checkAccess = checkAPI($api_key, $api_name);
		if (isset($checkAccess['error'])) return $response->withStatus(400)->getBody()->write(json_encode(array('error' => $checkAccess['error']), JSON_UNESCAPED_UNICODE));

		$access['full']       = $checkAccess['admin'];
		$access['can_delete'] = $checkAccess['delete'];

		if ($access['full'] || $access['can_delete']) {

			$id = $args['id'];
			if (!(int) $id)
				return $response->withStatus(400)->getBody()->write(json_encode(array('error' => 'Требуемая информация отсутствует: ID!'), JSON_UNESCAPED_UNICODE));

			$sql = 'DELETE FROM ' . PREFIX . "_{$api_name} WHERE id = {$id}";
			$connect->query($sql);

			$cache = new CacheSystem($api_name, $sql);
			$cache->clear($api_name);

			$response->withStatus(200)->getBody()->write(json_encode(array('success' => 'Данные успешно удалены!'), JSON_UNESCAPED_UNICODE));

		} else {

			$response->withStatus(400)->getBody()->write(json_encode(array('error' => 'У вас нет прав на удаление данных!'), JSON_UNESCAPED_UNICODE));

		}

		return $response->withHeader('Content-type', 'application/json; charset=UTF-8');
	});

	// Own routing Add
});
