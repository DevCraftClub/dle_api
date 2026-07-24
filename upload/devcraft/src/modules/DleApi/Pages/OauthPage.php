<?php

declare(strict_types=1);

namespace DevCraft\Modules\DleApi\Pages;

use DevCraft\Core\Application;
use DevCraft\Core\Abstracts\AbstractPage;
use DevCraft\Modules\DleApi\Models\ApiKey;
use DevCraft\Modules\DleApi\Models\OauthClient;
use DevCraft\Modules\DleApi\Repositories\ApiKeyRepository;
use DevCraft\Modules\DleApi\Repositories\OauthClientRepository;

/**
 * Страница OAuth-клиентов.
 */
final class OauthPage extends AbstractPage {

	public function handle(): array {
		$this->addBreadcrumb(__('OAuth-клиенты'));

		/** @var OauthClientRepository $clients */
		$clients = Application::instance()->database()->repository(OauthClient::class);
		/** @var ApiKeyRepository $keys */
		$keys = Application::instance()->database()->repository(ApiKey::class);

		global $config;
		$base = rtrim((string) ($config['http_home_url'] ?? ''), '/') . '/api/v2';

		return [
			'view' => 'dleapi/oauth.twig',
			'data' => [
				'page_title' => __('OAuth-клиенты'),
				'clients'    => $clients->all(),
				'api_keys'   => $keys->all(),
				'endpoints'  => [
					'discovery'  => $base . '/.well-known/oauth-authorization-server',
					'authorize'  => $base . '/oauth/authorize',
					'token'      => $base . '/oauth/token',
					'revoke'     => $base . '/oauth/revoke',
					'userinfo'   => $base . '/oauth/userinfo',
					'me'         => $base . '/me',
				],
			],
		];
	}

}
