<?php

	if( !defined( 'DATALIFEENGINE' ) ) {
		header( "HTTP/1.1 403 Forbidden" );
		header ( 'Location: ../../' );
		die( "Hacking attempt!" );
	}

	use Slim\App;
	$app = new \Slim\App($config);
	$container = $app->getContainer();
	$container['logger'] = function($c) {
		$logger = new \Monolog\Logger('dle-api');
		$file_handler = new \Monolog\Handler\StreamHandler(API_DIR . "/logs/app.log");
		$logger->pushHandler($file_handler);
		return $logger;
	};


	$app->group('/v1', function (App $app) use ($connect) {
		include_once (DLEPlugins::Check(API_DIR . '/routes/admin_logs.php'));
		include_once (DLEPlugins::Check(API_DIR . '/routes/admin_sections.php'));
		include_once (DLEPlugins::Check(API_DIR . '/routes/banned.php'));
		include_once (DLEPlugins::Check(API_DIR . '/routes/banners.php'));
		include_once (DLEPlugins::Check(API_DIR . '/routes/banners_logs.php'));
		include_once (DLEPlugins::Check(API_DIR . '/routes/banners_rubrics.php'));
		include_once (DLEPlugins::Check(API_DIR . '/routes/category.php'));
		include_once (DLEPlugins::Check(API_DIR . '/routes/comment_rating_log.php'));
		include_once (DLEPlugins::Check(API_DIR . '/routes/comments.php'));
		include_once (DLEPlugins::Check(API_DIR . '/routes/comments_files.php'));
		include_once (DLEPlugins::Check(API_DIR . '/routes/complaints.php'));
		include_once (DLEPlugins::Check(API_DIR . '/routes/email.php'));
		include_once (DLEPlugins::Check(API_DIR . '/routes/files.php'));
		include_once (DLEPlugins::Check(API_DIR . '/routes/flood.php'));
		include_once (DLEPlugins::Check(API_DIR . '/routes/ignore_list.php'));
		include_once (DLEPlugins::Check(API_DIR . '/routes/images.php'));
		include_once (DLEPlugins::Check(API_DIR . '/routes/links.php'));
		include_once (DLEPlugins::Check(API_DIR . '/routes/login_log.php'));
		include_once (DLEPlugins::Check(API_DIR . '/routes/logs.php'));
		include_once (DLEPlugins::Check(API_DIR . '/routes/lostdb.php'));
		include_once (DLEPlugins::Check(API_DIR . '/routes/mail_log.php'));
		include_once (DLEPlugins::Check(API_DIR . '/routes/metatags.php'));
		include_once (DLEPlugins::Check(API_DIR . '/routes/notice.php'));
		include_once (DLEPlugins::Check(API_DIR . '/routes/plugins.php'));
		include_once (DLEPlugins::Check(API_DIR . '/routes/plugins_files.php'));
		include_once (DLEPlugins::Check(API_DIR . '/routes/plugins_logs.php'));
		include_once (DLEPlugins::Check(API_DIR . '/routes/pm.php'));
		include_once (DLEPlugins::Check(API_DIR . '/routes/poll.php'));
		include_once (DLEPlugins::Check(API_DIR . '/routes/poll_log.php'));
		include_once (DLEPlugins::Check(API_DIR . '/routes/post.php'));
		include_once (DLEPlugins::Check(API_DIR . '/routes/post_extras.php'));
		include_once (DLEPlugins::Check(API_DIR . '/routes/post_extras_cats.php'));
		include_once (DLEPlugins::Check(API_DIR . '/routes/post_log.php'));
		include_once (DLEPlugins::Check(API_DIR . '/routes/post_pass.php'));
		include_once (DLEPlugins::Check(API_DIR . '/routes/question.php'));
		include_once (DLEPlugins::Check(API_DIR . '/routes/read_log.php'));
		include_once (DLEPlugins::Check(API_DIR . '/routes/redirects.php'));
		include_once (DLEPlugins::Check(API_DIR . '/routes/rss.php'));
		include_once (DLEPlugins::Check(API_DIR . '/routes/rssinform.php'));
		include_once (DLEPlugins::Check(API_DIR . '/routes/sendlog.php'));
		include_once (DLEPlugins::Check(API_DIR . '/routes/social_login.php'));
		include_once (DLEPlugins::Check(API_DIR . '/routes/spam_log.php'));
		include_once (DLEPlugins::Check(API_DIR . '/routes/static.php'));
		include_once (DLEPlugins::Check(API_DIR . '/routes/static_files.php'));
		include_once (DLEPlugins::Check(API_DIR . '/routes/subscribe.php'));
		include_once (DLEPlugins::Check(API_DIR . '/routes/tags.php'));
		include_once (DLEPlugins::Check(API_DIR . '/routes/twofactor.php'));
		include_once (DLEPlugins::Check(API_DIR . '/routes/usergroups.php'));
		include_once (DLEPlugins::Check(API_DIR . '/routes/users.php'));
		include_once (DLEPlugins::Check(API_DIR . '/routes/views.php'));
		include_once (DLEPlugins::Check(API_DIR . '/routes/vote.php'));
		include_once (DLEPlugins::Check(API_DIR . '/routes/vote_result.php'));
		include_once (DLEPlugins::Check(API_DIR . '/routes/xfsearch.php'));

		// Own path add
	});

	$app->run();
