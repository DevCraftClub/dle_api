<?php

declare(strict_types=1);

use DevCraft\Types\AdminLink;
use DevCraft\Types\ModuleManifest;
use DevCraft\Builders\ComposerTypeBuilder;
use DevCraft\Builders\ModuleAssetsBuilder;
use DevCraft\Builders\ModuleManifestBuilder;
use DevCraft\Builders\ModuleAjaxConfigBuilder;
use DevCraft\Builders\ModuleSiteAssetsBuilder;
use DevCraft\Modules\DleApi\DleApiIdentity;
use DevCraft\Modules\DleApi\Pages\KeysPage;
use DevCraft\Modules\DleApi\Pages\OauthPage;
use DevCraft\Modules\DleApi\Pages\SettingsPage;
use DevCraft\Modules\DleApi\Ajax\GetKeyHandler;
use DevCraft\Modules\DleApi\Pages\DashboardPage;
use DevCraft\Modules\DleApi\Pages\ChangelogPage;
use DevCraft\Modules\DleApi\Pages\AccessSyncPage;
use DevCraft\Modules\DleApi\Ajax\SettingsHandler;
use DevCraft\Modules\DleApi\Pages\KeyRequestsPage;
use DevCraft\Modules\DleApi\Ajax\CreateKeyHandler;
use DevCraft\Modules\DleApi\Ajax\UpdateKeyHandler;
use DevCraft\Modules\DleApi\Ajax\DeleteKeyHandler;
use DevCraft\Modules\DleApi\Ajax\ToggleKeyHandler;
use DevCraft\Modules\DleApi\Pages\AccessLevelsPage;
use DevCraft\Modules\DleApi\Ajax\GetOauthClientHandler;
use DevCraft\Modules\DleApi\Ajax\GetAccessLevelHandler;
use DevCraft\Modules\DleApi\Ajax\SaveAccessSyncHandler;
use DevCraft\Modules\DleApi\Ajax\SaveAccessLevelHandler;
use DevCraft\Modules\DleApi\Ajax\DecideKeyRequestHandler;
use DevCraft\Modules\DleApi\Ajax\PublicProfileKeyHandler;
use DevCraft\Modules\DleApi\Ajax\CreateOauthClientHandler;
use DevCraft\Modules\DleApi\Ajax\UpdateOauthClientHandler;
use DevCraft\Modules\DleApi\Ajax\DeleteOauthClientHandler;
use DevCraft\Modules\DleApi\Ajax\DeleteAccessLevelHandler;
use DevCraft\Modules\DleApi\Ajax\RegenerateOauthClientSecretHandler;

/**
 * Манифест модуля DLE API (fluent ModuleManifestBuilder).
 *
 * @package    DevCraft
 * @since      200.1.0
 * @subpackage Modules.DleApi
 *
 * @return ModuleManifest
 */
return ModuleManifestBuilder::create()
	->mod(DleApiIdentity::mod())
	->code(DleApiIdentity::code())
	->crowdinName('dle-api')
	->crowdinStatId('16830581-921125')
	->name('DLE API')
	->version('200.1.1')
	->description(__('Неофициальное REST API для DLE: ключи, OAuth2 Bearer, /api/v2'))
	->icon('mif-embed2')
	->docsLink('https://readme.devcraft.club/latest/dev/dle_api/install/')
	->siteLink('https://devcraft.club/downloads/dle-api.20/')
	->siteId(20)
	->menu([
		AdminLink::page(__('Главная'), 'dashboard', DashboardPage::class, 'mif-home', DleApiIdentity::mod()),
		AdminLink::page(__('API-ключи'), 'keys', KeysPage::class, 'mif-key', DleApiIdentity::mod()),
		AdminLink::page(__('Уровни доступа'), 'access', AccessLevelsPage::class, 'mif-security', DleApiIdentity::mod()),
		AdminLink::page(__('Синхронизация с группами'), 'access_sync', AccessSyncPage::class, 'mif-users', DleApiIdentity::mod()),
		AdminLink::page(__('Заявки на ключ'), 'key_requests', KeyRequestsPage::class, 'mif-mail', DleApiIdentity::mod()),
		AdminLink::page(__('OAuth-клиенты'), 'oauth', OauthPage::class, 'mif-lock', DleApiIdentity::mod()),
		AdminLink::page(__('Настройки'), 'settings', SettingsPage::class, 'mif-cog', DleApiIdentity::mod()),
		AdminLink::page(__('Журнал изменений'), 'changelog', ChangelogPage::class, 'mif-library', DleApiIdentity::mod()),
	])
	->ajax(
		ModuleAjaxConfigBuilder::create('admin')
			->methods([
				'settings'                       => SettingsHandler::class,
				'create_key'                     => CreateKeyHandler::class,
				'update_key'                     => UpdateKeyHandler::class,
				'get_key'                        => GetKeyHandler::class,
				'delete_key'                     => DeleteKeyHandler::class,
				'toggle_key'                     => ToggleKeyHandler::class,
				'create_oauth_client'            => CreateOauthClientHandler::class,
				'update_oauth_client'            => UpdateOauthClientHandler::class,
				'get_oauth_client'               => GetOauthClientHandler::class,
				'regenerate_oauth_client_secret' => RegenerateOauthClientSecretHandler::class,
				'delete_oauth_client'            => DeleteOauthClientHandler::class,
				'save_access_level'              => SaveAccessLevelHandler::class,
				'get_access_level'               => GetAccessLevelHandler::class,
				'delete_access_level'            => DeleteAccessLevelHandler::class,
				'save_access_sync'               => SaveAccessSyncHandler::class,
				'decide_key_request'             => DecideKeyRequestHandler::class,
			])
			->publicMethod('profile_key', PublicProfileKeyHandler::class)
	)
	->composerRequired([
		ComposerTypeBuilder::create('league/oauth2-server')->minVersion('^9.0')->hardRequired()->build(),
	])
	->changelog(require DLEPlugins::Check(__DIR__ . '/changelog.data.php'))
	->assets(ModuleAssetsBuilder::create()->js('dleapi.js'))
	->siteAssets(
		ModuleSiteAssetsBuilder::create()
			->css('dleapi_profile.css', available: ['userinfo'])
			->js(
				'dleapi_profile.js',
				dependsOn: ['devcraft/src/templates/core/assets/js/dc_public.js'],
				available: ['userinfo'],
			)
	)
	->build(__DIR__);
