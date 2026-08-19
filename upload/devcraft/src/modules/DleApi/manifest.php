<?php

declare(strict_types=1);

use DevCraft\Types\AdminLink;
use DevCraft\Modules\DleApi\Pages\KeysPage;
use DevCraft\Modules\DleApi\Pages\OauthPage;
use DevCraft\Modules\DleApi\Pages\SettingsPage;
use DevCraft\Modules\DleApi\Pages\DashboardPage;
use DevCraft\Modules\DleApi\Pages\ChangelogPage;
use DevCraft\Modules\DleApi\Pages\AccessLevelsPage;
use DevCraft\Modules\DleApi\Pages\AccessSyncPage;
use DevCraft\Modules\DleApi\Pages\KeyRequestsPage;
use DevCraft\Modules\DleApi\Ajax\SettingsHandler;
use DevCraft\Modules\DleApi\Ajax\CreateKeyHandler;
use DevCraft\Modules\DleApi\Ajax\UpdateKeyHandler;
use DevCraft\Modules\DleApi\Ajax\GetKeyHandler;
use DevCraft\Modules\DleApi\Ajax\DeleteKeyHandler;
use DevCraft\Modules\DleApi\Ajax\ToggleKeyHandler;
use DevCraft\Modules\DleApi\Ajax\CreateOauthClientHandler;
use DevCraft\Modules\DleApi\Ajax\UpdateOauthClientHandler;
use DevCraft\Modules\DleApi\Ajax\GetOauthClientHandler;
use DevCraft\Modules\DleApi\Ajax\RegenerateOauthClientSecretHandler;
use DevCraft\Modules\DleApi\Ajax\DeleteOauthClientHandler;
use DevCraft\Modules\DleApi\Ajax\SaveAccessLevelHandler;
use DevCraft\Modules\DleApi\Ajax\GetAccessLevelHandler;
use DevCraft\Modules\DleApi\Ajax\DeleteAccessLevelHandler;
use DevCraft\Modules\DleApi\Ajax\SaveAccessSyncHandler;
use DevCraft\Modules\DleApi\Ajax\DecideKeyRequestHandler;
use DevCraft\Modules\DleApi\Ajax\PublicProfileKeyHandler;

/**
 * Манифест модуля DLE API.
 *
 * @return array<string, mixed>
 */
return [
	'mod'               => 'dleapi',
	'code'              => 'dleapi',
	'composer_required' => [
		['name' => 'league/oauth2-server', 'minVersion' => '^9.0', 'hardRequired' => true],
	],
	'meta'              => [
		'name'        => 'DLE API',
		'version'     => '200.1.0',
		'description' => __('Неофициальное REST/SDK API для DLE: ключи, OAuth2 Bearer, /api/v2'),
		'icon'        => 'mif-embed2',
		'docsLink'    => 'https://readme.devcraft.club/dev/dle_api/install/',
		'siteLink'    => 'https://devcraft.club/downloads/dle-api.20/',
		'siteId'      => 20,
	],
	'menu'              => [
		AdminLink::page(__('Главная'), 'dashboard', DashboardPage::class, 'mif-home', 'dleapi'),
		AdminLink::page(__('API-ключи'), 'keys', KeysPage::class, 'mif-key', 'dleapi'),
		AdminLink::page(__('Уровни доступа'), 'access', AccessLevelsPage::class, 'mif-security', 'dleapi'),
		AdminLink::page(__('Синхронизация с группами'), 'access_sync', AccessSyncPage::class, 'mif-users', 'dleapi'),
		AdminLink::page(__('Заявки на ключ'), 'key_requests', KeyRequestsPage::class, 'mif-mail', 'dleapi'),
		AdminLink::page(__('OAuth-клиенты'), 'oauth', OauthPage::class, 'mif-lock', 'dleapi'),
		AdminLink::page(__('Настройки'), 'settings', SettingsPage::class, 'mif-cog', 'dleapi'),
		AdminLink::page(__('Журнал изменений'), 'changelog', ChangelogPage::class, 'mif-library', 'dleapi'),
	],
	'ajax'              => [
		'controller' => 'admin',
		'methods'    => [
			'settings'             => SettingsHandler::class,
			'create_key'           => CreateKeyHandler::class,
			'update_key'           => UpdateKeyHandler::class,
			'get_key'              => GetKeyHandler::class,
			'delete_key'           => DeleteKeyHandler::class,
			'toggle_key'           => ToggleKeyHandler::class,
			'create_oauth_client'  => CreateOauthClientHandler::class,
			'update_oauth_client'  => UpdateOauthClientHandler::class,
			'get_oauth_client'     => GetOauthClientHandler::class,
			'regenerate_oauth_client_secret' => RegenerateOauthClientSecretHandler::class,
			'delete_oauth_client'  => DeleteOauthClientHandler::class,
			'save_access_level'    => SaveAccessLevelHandler::class,
			'get_access_level'     => GetAccessLevelHandler::class,
			'delete_access_level'  => DeleteAccessLevelHandler::class,
			'save_access_sync'     => SaveAccessSyncHandler::class,
			'decide_key_request'   => DecideKeyRequestHandler::class,
		],
		'public'     => [
			'profile_key' => [
				'handler'     => PublicProfileKeyHandler::class,
				'allow_guest' => false,
			],
		],
	],
	'changelog'         => require DLEPlugins::Check(__DIR__ . '/changelog.data.php'),
	'assets'            => [
		'js' => ['dleapi.js'],
	],
];
