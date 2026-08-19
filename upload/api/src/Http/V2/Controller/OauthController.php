<<<<<<< New base: Update README.md
<?php

declare(strict_types=1);

namespace DleApi\Http\V2\Controller;

use DleApi\Http\V2\JsonResponder;
use DleApi\Http\V2\OAuth\TokenService;
use DevCraft\Modules\DleApi\Services\DleApiConfig;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * OAuth token / revoke / authorize / discovery / userinfo.
 */
final class OauthController {

	public function __construct(
		private readonly TokenService $tokens = new TokenService(),
	) {
	}

	public function token(Request $request, Response $_response): Response {
		$params = array_merge($request->getQueryParams(), (array) $request->getParsedBody());
		$cred   = (string) ($params['credential_type'] ?? '');
		$grant  = (string) ($params['grant_type'] ?? '');

		if($cred !== '') {
			$result = match ($cred) {
				'api_key' => $this->tokens->issueByApiKey(
					(string) ($params['api_key'] ?? ''),
					(string) ($params['scope'] ?? ''),
				),
				'auth' => $this->tokens->issueByAuth(
					(string) ($params['username'] ?? ''),
					(string) ($params['password'] ?? ''),
					(string) ($params['scope'] ?? ''),
				),
				'oauth_client' => $this->tokens->issueByOauthClient(
					(string) ($params['client_id'] ?? ''),
					(string) ($params['client_secret'] ?? ''),
					(string) ($params['scope'] ?? ''),
				),
				default => ['error' => 'unsupported_credential_type', 'message' => __('Неподдерживаемый credential_type')],
			};
		} else {
			$result = match ($grant) {
				'client_credentials' => $this->tokens->issueByOauthClient(
					(string) ($params['client_id'] ?? ''),
					(string) ($params['client_secret'] ?? ''),
					(string) ($params['scope'] ?? ''),
				),
				'authorization_code' => $this->tokens->issueAuthorizationCode(
					(string) ($params['client_id'] ?? ''),
					(string) ($params['client_secret'] ?? ''),
					(string) ($params['code'] ?? ''),
					(string) ($params['redirect_uri'] ?? ''),
					(string) ($params['code_verifier'] ?? ''),
				),
				'refresh_token' => $this->tokens->refresh((string) ($params['refresh_token'] ?? '')),
				'password' => $this->tokens->issuePassword(
					(string) ($params['client_id'] ?? ''),
					(string) ($params['client_secret'] ?? ''),
					(string) ($params['username'] ?? ''),
					(string) ($params['password'] ?? ''),
					(string) ($params['scope'] ?? ''),
				),
				default => ['error' => 'unsupported_grant_type', 'message' => __('Укажите credential_type или grant_type')],
			};
		}

		$status = isset($result['error']) ? 400 : 200;

		return JsonResponder::ok($result, $status);
	}

	public function revoke(Request $request, Response $_response): Response {
		$body = (array) $request->getParsedBody();
		$this->tokens->revoke((string) ($body['token'] ?? ''));

		return JsonResponder::ok(['revoked' => true]);
	}

	public function authorize(Request $request, Response $response): Response {
		$q         = array_merge($request->getQueryParams(), (array) $request->getParsedBody());
		$clientId  = (string) ($q['client_id'] ?? '');
		$redirect  = (string) ($q['redirect_uri'] ?? '');
		$state     = (string) ($q['state'] ?? '');
		$challenge = (string) ($q['code_challenge'] ?? '');
		$method    = (string) ($q['code_challenge_method'] ?? 'S256');

		global $is_logged, $member_id, $config;
		$userId = 0;
		if(!empty($is_logged) && is_array($member_id ?? null)) {
			$userId = (int) ($member_id['user_id'] ?? 0);
		}

		if($clientId === '' || $redirect === '') {
			return JsonResponder::error('invalid_request', __('Нужны client_id и redirect_uri'), 400);
		}

		$authErr = $this->tokens->validateAuthorizeRequest($clientId, $redirect);
		if($authErr !== null) {
			return JsonResponder::error($authErr['error'], $authErr['message'], 400);
		}

		if($userId < 1) {
			$return = (string) $request->getUri();
			if(session_status() === PHP_SESSION_ACTIVE || @session_start()) {
				$_SESSION['dleapi_oauth_return'] = $return;
			}
			$login = rtrim((string) ($config['http_home_url'] ?? '/'), '/') . '/index.php?do=login';

			return $response->withHeader('Location', $login)->withStatus(302);
		}

		$sep = str_contains($redirect, '?') ? '&' : '?';
		if(DleApiConfig::isDemoMode()) {
			$loc = $redirect . $sep . http_build_query(array_filter([
				'error'             => 'demo_mode',
				'error_description' => __('Авторизация пройдена, но сброшена в демо-режиме'),
				'authorized'        => '1',
				'demo_mode'         => '1',
				'state'             => $state ?: null,
			]));

			return $response->withHeader('Location', $loc)->withStatus(302);
		}

		$code = $this->tokens->createAuthCode($clientId, $userId, $redirect, (string) ($q['scope'] ?? ''), $challenge, $method);
		$loc  = $redirect . $sep . http_build_query(array_filter(['code' => $code, 'state' => $state ?: null]));

		return $response->withHeader('Location', $loc)->withStatus(302);
	}

	public function discovery(Request $_request, Response $_response): Response {
		$base = $this->apiBaseUrl();

		return JsonResponder::ok([
			'issuer'                                => $base,
			'authorization_endpoint'                => $base . '/oauth/authorize',
			'token_endpoint'                        => $base . '/oauth/token',
			'revocation_endpoint'                   => $base . '/oauth/revoke',
			'userinfo_endpoint'                     => $base . '/oauth/userinfo',
			'response_types_supported'              => ['code'],
			'grant_types_supported'                 => [
				'authorization_code',
				'refresh_token',
				'client_credentials',
				'password',
			],
			'code_challenge_methods_supported'      => ['S256', 'plain'],
			'token_endpoint_auth_methods_supported' => ['client_secret_post', 'client_secret_basic'],
			'credential_types_supported'            => ['api_key', 'auth', 'oauth_client'],
		]);
	}

	public function userinfo(Request $request, Response $_response): Response {
		return (new MeController())->me($request, $_response);
	}

	private function apiBaseUrl(): string {
		global $config;
		$host = rtrim((string) ($config['http_home_url'] ?? ''), '/');

		return $host . '/api/v2';
	}

}
|||||||
=======
<?php

declare(strict_types=1);

namespace DleApi\Http\V2\Controller;

use DleApi\Http\V2\JsonResponder;
use DleApi\Http\V2\OAuth\TokenService;
use DevCraft\Modules\DleApi\Services\DleApiConfig;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * OAuth token / revoke / authorize / discovery / userinfo.
 */
final class OauthController {

	public function __construct(
		private readonly TokenService $tokens = new TokenService(),
	) {
	}

	public function token(Request $request, Response $_response): Response {
		$params = array_merge($request->getQueryParams(), (array) $request->getParsedBody());
		$cred   = (string) ($params['credential_type'] ?? '');
		$grant  = (string) ($params['grant_type'] ?? '');

		if($cred !== '') {
			$result = match ($cred) {
				'api_key' => $this->tokens->issueByApiKey(
					(string) ($params['api_key'] ?? ''),
					(string) ($params['scope'] ?? ''),
				),
				'auth' => $this->tokens->issueByAuth(
					(string) ($params['username'] ?? ''),
					(string) ($params['password'] ?? ''),
					(string) ($params['scope'] ?? ''),
				),
				'oauth_client' => $this->tokens->issueByOauthClient(
					(string) ($params['client_id'] ?? ''),
					(string) ($params['client_secret'] ?? ''),
					(string) ($params['scope'] ?? ''),
				),
				default => ['error' => 'unsupported_credential_type', 'message' => __('Неподдерживаемый credential_type')],
			};
		} else {
			$result = match ($grant) {
				'client_credentials' => $this->tokens->issueByOauthClient(
					(string) ($params['client_id'] ?? ''),
					(string) ($params['client_secret'] ?? ''),
					(string) ($params['scope'] ?? ''),
				),
				'authorization_code' => $this->tokens->issueAuthorizationCode(
					(string) ($params['client_id'] ?? ''),
					(string) ($params['client_secret'] ?? ''),
					(string) ($params['code'] ?? ''),
					(string) ($params['redirect_uri'] ?? ''),
					(string) ($params['code_verifier'] ?? ''),
				),
				'refresh_token' => $this->tokens->refresh((string) ($params['refresh_token'] ?? '')),
				'password' => $this->tokens->issuePassword(
					(string) ($params['client_id'] ?? ''),
					(string) ($params['client_secret'] ?? ''),
					(string) ($params['username'] ?? ''),
					(string) ($params['password'] ?? ''),
					(string) ($params['scope'] ?? ''),
				),
				default => ['error' => 'unsupported_grant_type', 'message' => __('Укажите credential_type или grant_type')],
			};
		}

		$status = isset($result['error']) ? 400 : 200;

		return JsonResponder::ok($result, $status);
	}

	public function revoke(Request $request, Response $_response): Response {
		$body = (array) $request->getParsedBody();
		$this->tokens->revoke((string) ($body['token'] ?? ''));

		return JsonResponder::ok(['revoked' => true]);
	}

	public function authorize(Request $request, Response $response): Response {
		$q         = array_merge($request->getQueryParams(), (array) $request->getParsedBody());
		$clientId  = (string) ($q['client_id'] ?? '');
		$redirect  = (string) ($q['redirect_uri'] ?? '');
		$state     = (string) ($q['state'] ?? '');
		$challenge = (string) ($q['code_challenge'] ?? '');
		$method    = (string) ($q['code_challenge_method'] ?? 'S256');

		global $is_logged, $member_id, $config;
		$userId = 0;
		if(!empty($is_logged) && is_array($member_id ?? null)) {
			$userId = (int) ($member_id['user_id'] ?? 0);
		}

		if($clientId === '' || $redirect === '') {
			return JsonResponder::error('invalid_request', __('Нужны client_id и redirect_uri'), 400);
		}

		$authErr = $this->tokens->validateAuthorizeRequest($clientId, $redirect);
		if($authErr !== null) {
			return JsonResponder::error($authErr['error'], $authErr['message'], 400);
		}

		if($userId < 1) {
			$return = (string) $request->getUri();
			if(session_status() === PHP_SESSION_ACTIVE || @session_start()) {
				$_SESSION['dleapi_oauth_return'] = $return;
			}
			$login = rtrim((string) ($config['http_home_url'] ?? '/'), '/') . '/index.php?do=login';

			return $response->withHeader('Location', $login)->withStatus(302);
		}

		$sep = str_contains($redirect, '?') ? '&' : '?';
		if(DleApiConfig::isDemoMode()) {
			$loc = $redirect . $sep . http_build_query(array_filter([
				'error'             => 'demo_mode',
				'error_description' => __('Авторизация пройдена, но сброшена в демо-режиме'),
				'authorized'        => '1',
				'demo_mode'         => '1',
				'state'             => $state ?: null,
			]));

			return $response->withHeader('Location', $loc)->withStatus(302);
		}

		$code = $this->tokens->createAuthCode($clientId, $userId, $redirect, (string) ($q['scope'] ?? ''), $challenge, $method);
		$loc  = $redirect . $sep . http_build_query(array_filter(['code' => $code, 'state' => $state ?: null]));

		return $response->withHeader('Location', $loc)->withStatus(302);
	}

	public function discovery(Request $_request, Response $_response): Response {
		$base = $this->apiBaseUrl();

		return JsonResponder::ok([
			'issuer'                                => $base,
			'authorization_endpoint'                => $base . '/oauth/authorize',
			'token_endpoint'                        => $base . '/oauth/token',
			'revocation_endpoint'                   => $base . '/oauth/revoke',
			'userinfo_endpoint'                     => $base . '/oauth/userinfo',
			'response_types_supported'              => ['code'],
			'grant_types_supported'                 => [
				'authorization_code',
				'refresh_token',
				'client_credentials',
				'password',
			],
			'code_challenge_methods_supported'      => ['S256', 'plain'],
			'token_endpoint_auth_methods_supported' => ['client_secret_post', 'client_secret_basic'],
			'credential_types_supported'            => ['api_key', 'auth', 'oauth_client'],
		]);
	}

	public function userinfo(Request $request, Response $_response): Response {
		return (new MeController())->me($request, $_response);
	}

	private function apiBaseUrl(): string {
		global $config;
		$host = rtrim((string) ($config['http_home_url'] ?? ''), '/');

		return $host . '/api/v2';
	}

}
>>>>>>> Current commit: Начало обновления до api v2
