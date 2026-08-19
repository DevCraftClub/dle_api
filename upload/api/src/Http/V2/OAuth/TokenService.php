<<<<<<< New base: Update README.md
<?php

declare(strict_types=1);

namespace DleApi\Http\V2\OAuth;

use DevCraft\Core\Application;
use DevCraft\Modules\DleApi\Models\ApiKey;
use DevCraft\Modules\DleApi\Models\OauthAccessToken;
use DevCraft\Modules\DleApi\Models\OauthAuthCode;
use DevCraft\Modules\DleApi\Models\OauthClient;
use DevCraft\Modules\DleApi\Models\OauthRefreshToken;
use DevCraft\Modules\DleApi\Repositories\ApiKeyRepository;
use DevCraft\Modules\DleApi\Repositories\OauthAccessTokenRepository;
use DevCraft\Modules\DleApi\Repositories\OauthAuthCodeRepository;
use DevCraft\Modules\DleApi\Repositories\OauthClientRepository;
use DevCraft\Modules\DleApi\Repositories\OauthRefreshTokenRepository;
use DevCraft\Modules\DleApi\Services\DleApiConfig;

/**
 * Выдача и проверка AuthToken через Cycle-модели DleApi.
 */
final class TokenService {

	/**
	 * @return array<string, mixed>
	 */
	public function issueByApiKey(string $apiKey, string $scope = ''): array {
		/** @var ApiKeyRepository $repo */
		$repo = $this->repo(ApiKey::class);
		$key  = $repo->findActiveByApi($apiKey);
		if($key === null) {
			return ['error' => 'invalid_client', 'message' => __('Неверный или неактивный API-ключ')];
		}
		if(DleApiConfig::isDemoMode()) {
			return DleApiConfig::demoAuthorizedResponse();
		}

		return $this->persistTokensForKey($key, $key->user_id, $scope);
	}

	/**
	 * @return array<string, mixed>
	 */
	public function issueByAuth(string $username, string $password, string $scope = ''): array {
		$userId = (new DleUserPasswordVerifier())->verify($username, $password);
		if($userId === null) {
			return ['error' => 'invalid_grant', 'message' => __('Неверный логин или пароль')];
		}
		$key = $this->resolveApiKeyForUser($userId);
		if($key === null) {
			return ['error' => 'access_denied', 'message' => __('Нет API-ключа пользователя и гостевого ключа')];
		}
		if(DleApiConfig::isDemoMode()) {
			return DleApiConfig::demoAuthorizedResponse();
		}

		return $this->persistTokensForKey($key, $userId, $scope);
	}

	/**
	 * @return array<string, mixed>
	 */
	public function issueByOauthClient(string $clientId, string $clientSecret, string $scope = ''): array {
		$client = $this->findActiveClient($clientId);
		if($client === null || !password_verify($clientSecret, $client->client_secret)) {
			return ['error' => 'invalid_client', 'message' => __('Неверный client_id или client_secret')];
		}
		if(!$this->clientAllowsGrant($client, 'client_credentials')) {
			return ['error' => 'unauthorized_client', 'message' => __('Клиент не разрешает grant_type=client_credentials')];
		}
		$key = $this->findActiveApiKey($client->api_key_id);
		if($key === null) {
			return ['error' => 'invalid_client', 'message' => __('API-ключ клиента неактивен')];
		}
		if(DleApiConfig::isDemoMode()) {
			return DleApiConfig::demoAuthorizedResponse();
		}

		return $this->persistTokens($client, $key, $key->user_id, $scope);
	}

	/**
	 * @return array<string, mixed>
	 */
	public function issueClientCredentials(string $clientId, string $clientSecret, string $scope = ''): array {
		return $this->issueByOauthClient($clientId, $clientSecret, $scope);
	}

	/**
	 * @return array<string, mixed>
	 */
	public function issuePassword(
		string $clientId,
		string $clientSecret,
		string $username,
		string $password,
		string $scope = '',
	): array {
		if($clientId === '' && $clientSecret === '') {
			return $this->issueByAuth($username, $password, $scope);
		}
		if($clientId === '' || $clientSecret === '') {
			return ['error' => 'invalid_client', 'message' => __('Нужны оба: client_id и client_secret')];
		}
		$client = $this->findActiveClient($clientId);
		if($client === null || !password_verify($clientSecret, $client->client_secret)) {
			return ['error' => 'invalid_client', 'message' => __('Неверный client_id или client_secret')];
		}
		if(!$this->clientAllowsGrant($client, 'password')) {
			return ['error' => 'unauthorized_client', 'message' => __('Клиент не разрешает grant_type=password')];
		}
		$key = $this->findActiveApiKey($client->api_key_id);
		if($key === null) {
			return ['error' => 'invalid_client', 'message' => __('API-ключ клиента неактивен')];
		}
		$userId = (new DleUserPasswordVerifier())->verify($username, $password);
		if($userId === null) {
			return ['error' => 'invalid_grant', 'message' => __('Неверный логин или пароль')];
		}
		if(DleApiConfig::isDemoMode()) {
			return DleApiConfig::demoAuthorizedResponse();
		}

		return $this->persistTokens($client, $key, $userId, $scope);
	}

	/**
	 * @return array<string, mixed>
	 */
	public function issueAuthorizationCode(
		string $clientId,
		string $clientSecret,
		string $code,
		string $redirectUri,
		string $codeVerifier = '',
	): array {
		$client = $this->findActiveClient($clientId);
		if($client === null || !password_verify($clientSecret, $client->client_secret)) {
			return ['error' => 'invalid_client', 'message' => __('Неверный client')];
		}
		if(!$this->clientAllowsGrant($client, 'authorization_code')) {
			return ['error' => 'unauthorized_client', 'message' => __('Клиент не разрешает grant_type=authorization_code')];
		}
		/** @var OauthAuthCodeRepository $codes */
		$codes = $this->repo(OauthAuthCode::class);
		$row   = $codes->findValidByCode($code);
		if($row === null || $row->client_id !== $clientId) {
			return ['error' => 'invalid_grant', 'message' => __('Код недействителен')];
		}
		if($redirectUri !== '' && (string) ($row->redirect_uri ?? '') !== $redirectUri) {
			return ['error' => 'invalid_grant', 'message' => __('redirect_uri не совпадает')];
		}
		$challenge = (string) ($row->code_challenge ?? '');
		if($challenge !== '') {
			$method = (string) ($row->code_challenge_method ?? 'S256');
			$calc   = $method === 'plain'
				? $codeVerifier
				: rtrim(strtr(base64_encode(hash('sha256', $codeVerifier, true)), '+/', '-_'), '=');
			if(!hash_equals($challenge, $calc)) {
				return ['error' => 'invalid_grant', 'message' => __('Ошибка проверки PKCE')];
			}
		}
		if(DleApiConfig::isDemoMode()) {
			return DleApiConfig::demoAuthorizedResponse();
		}
		$codes->revokeEntity($row);
		$key = $this->findActiveApiKey($client->api_key_id);
		if($key === null) {
			return ['error' => 'invalid_client', 'message' => __('API-ключ неактивен')];
		}

		return $this->persistTokens($client, $key, $row->user_id, (string) ($row->scopes ?? ''));
	}

	/**
	 * @return array<string, mixed>
	 */
	public function refresh(string $refreshToken): array {
		/** @var OauthRefreshTokenRepository $refreshRepo */
		$refreshRepo = $this->repo(OauthRefreshToken::class);
		$row         = $refreshRepo->findValidByTokenId($refreshToken);
		if($row === null) {
			return ['error' => 'invalid_grant', 'message' => __('Refresh token недействителен')];
		}
		/** @var OauthAccessTokenRepository $accessRepo */
		$accessRepo = $this->repo(OauthAccessToken::class);
		$access     = $accessRepo->findByTokenId($row->access_token_id);
		if($access === null) {
			return ['error' => 'invalid_grant', 'message' => __('Связанный access token не найден')];
		}
		$client = $this->findActiveClient($access->client_id);
		$key    = $this->findActiveApiKey($access->api_key_id);
		if($client === null || $key === null) {
			return ['error' => 'invalid_client', 'message' => __('Клиент или ключ неактивен')];
		}
		if(!$this->clientAllowsGrant($client, 'refresh_token')) {
			return ['error' => 'unauthorized_client', 'message' => __('Клиент не разрешает grant_type=refresh_token')];
		}
		if(DleApiConfig::isDemoMode()) {
			return DleApiConfig::demoAuthorizedResponse();
		}
		$refreshRepo->revokeEntity($row);
		$accessRepo->revokeEntity($access);

		return $this->persistTokens($client, $key, $access->user_id, (string) ($access->scopes ?? ''));
	}

	public function revoke(string $token): void {
		/** @var OauthAccessTokenRepository $accessRepo */
		$accessRepo = $this->repo(OauthAccessToken::class);
		$accessRepo->revokeByAccessOrTokenId($token);
		/** @var OauthRefreshTokenRepository $refreshRepo */
		$refreshRepo = $this->repo(OauthRefreshToken::class);
		$refreshRepo->revokeByTokenId($token);
	}

	/**
	 * Только AuthToken (не сырой API-ключ).
	 *
	 * @return array{auth_via: 'access_token', token: array<string, mixed>, key: array<string, mixed>}|null
	 */
	public function validateAccessToken(string $bearer): ?array {
		/** @var OauthAccessTokenRepository $accessRepo */
		$accessRepo = $this->repo(OauthAccessToken::class);
		$row        = $accessRepo->findValidByAccessToken($bearer);
		if($row === null) {
			return null;
		}
		$key = $this->findActiveApiKey($row->api_key_id);
		if($key === null) {
			return null;
		}

		return [
			'auth_via' => 'access_token',
			'token'    => [
				'token_id'     => $row->token_id,
				'access_token' => $row->access_token,
				'client_id'    => $row->client_id,
				'user_id'      => $row->user_id,
				'api_key_id'   => $row->api_key_id,
				'scopes'       => $row->scopes,
			],
			'key' => ApiKeyRepository::toAuthArray($key),
		];
	}

	public function resolveApiKeyForUser(int $userId): ?ApiKey {
		/** @var ApiKeyRepository $repo */
		$repo = $this->repo(ApiKey::class);
		$key  = $repo->findActiveByUserId($userId);
		if($key !== null) {
			return $key;
		}

		return $repo->findActiveGuest();
	}

	/**
	 * Проверка client_id / redirect_uri / grant authorization_code до выдачи code.
	 *
	 * @return array{error: string, message: string}|null null — OK
	 */
	public function validateAuthorizeRequest(string $clientId, string $redirectUri): ?array {
		$client = $this->findActiveClient($clientId);
		if($client === null) {
			return ['error' => 'invalid_client', 'message' => __('Неизвестный или неактивный client_id')];
		}
		if(!$this->clientAllowsGrant($client, 'authorization_code')) {
			return ['error' => 'unauthorized_client', 'message' => __('Клиент не разрешает grant_type=authorization_code')];
		}
		$registered = (string) ($client->redirect_uri ?? '');
		if($registered === '') {
			return ['error' => 'invalid_request', 'message' => __('У клиента не задан redirect_uri')];
		}
		if($registered !== $redirectUri) {
			return ['error' => 'invalid_request', 'message' => __('redirect_uri не совпадает с зарегистрированным у клиента')];
		}

		return null;
	}

	public function createAuthCode(
		string $clientId,
		int $userId,
		string $redirectUri,
		string $scopes = '',
		string $codeChallenge = '',
		string $codeChallengeMethod = 'S256',
	): string {
		$code = bin2hex(random_bytes(32));
		/** @var OauthAuthCodeRepository $codes */
		$codes = $this->repo(OauthAuthCode::class);
		$codes->createCode(
			$code,
			$clientId,
			$userId,
			$scopes,
			$redirectUri,
			$codeChallenge,
			$codeChallengeMethod,
			new \DateTimeImmutable('+' . 600 . ' seconds'),
		);

		return $code;
	}

	/**
	 * @return array{access_token: string, token_type: string, expires_in: int, refresh_token: string}
	 */
	private function persistTokens(OauthClient $client, ApiKey $key, int $userId, string $scopes): array {
		return $this->persistTokensForKey($key, $userId, $scopes, $client->client_id);
	}

	/**
	 * @return array{access_token: string, token_type: string, expires_in: int, refresh_token: string}
	 */
	private function persistTokensForKey(ApiKey $key, int $userId, string $scopes, string $clientId = ''): array {
		$ttl         = $this->tokenTtl();
		$tokenId     = bin2hex(random_bytes(16));
		$accessToken = bin2hex(random_bytes(32));
		$refreshId   = bin2hex(random_bytes(16));
		/** @var OauthAccessTokenRepository $accessRepo */
		$accessRepo = $this->repo(OauthAccessToken::class);
		$accessRepo->createToken(
			$tokenId,
			$accessToken,
			$clientId,
			$userId,
			$key->id(),
			$scopes,
			new \DateTimeImmutable('+' . $ttl . ' seconds'),
		);
		/** @var OauthRefreshTokenRepository $refreshRepo */
		$refreshRepo = $this->repo(OauthRefreshToken::class);
		$refreshRepo->createToken(
			$refreshId,
			$tokenId,
			new \DateTimeImmutable('+' . (86400 * 30) . ' seconds'),
		);

		return [
			'access_token'  => $accessToken,
			'token_type'    => 'Bearer',
			'expires_in'    => $ttl,
			'refresh_token' => $refreshId,
		];
	}

	private function clientAllowsGrant(OauthClient $client, string $grant): bool {
		$raw = $client->grant_types;
		if($raw === '') {
			return false;
		}

		return in_array($grant, array_map('trim', explode(',', $raw)), true);
	}

	private function findActiveClient(string $clientId): ?OauthClient {
		/** @var OauthClientRepository $repo */
		$repo = $this->repo(OauthClient::class);

		return $repo->findActiveByClientId($clientId);
	}

	private function findActiveApiKey(int $id): ?ApiKey {
		/** @var ApiKeyRepository $repo */
		$repo = $this->repo(ApiKey::class);

		return $repo->findActive($id);
	}

	/**
	 * @template T of object
	 * @param class-string<T> $entity
	 * @return object
	 */
	private function repo(string $entity): object {
		return Application::instance()->database()->repository($entity);
	}

	private function tokenTtl(): int {
		$cfg = DleApiConfig::all();
		if(isset($cfg['token_ttl'])) {
			return max(60, (int) $cfg['token_ttl']);
		}

		return 3600;
	}

}
|||||||
=======
<?php

declare(strict_types=1);

namespace DleApi\Http\V2\OAuth;

use DevCraft\Core\Application;
use DevCraft\Modules\DleApi\Models\ApiKey;
use DevCraft\Modules\DleApi\Models\OauthAccessToken;
use DevCraft\Modules\DleApi\Models\OauthAuthCode;
use DevCraft\Modules\DleApi\Models\OauthClient;
use DevCraft\Modules\DleApi\Models\OauthRefreshToken;
use DevCraft\Modules\DleApi\Repositories\ApiKeyRepository;
use DevCraft\Modules\DleApi\Repositories\OauthAccessTokenRepository;
use DevCraft\Modules\DleApi\Repositories\OauthAuthCodeRepository;
use DevCraft\Modules\DleApi\Repositories\OauthClientRepository;
use DevCraft\Modules\DleApi\Repositories\OauthRefreshTokenRepository;
use DevCraft\Modules\DleApi\Services\DleApiConfig;

/**
 * Выдача и проверка AuthToken через Cycle-модели DleApi.
 */
final class TokenService {

	/**
	 * @return array<string, mixed>
	 */
	public function issueByApiKey(string $apiKey, string $scope = ''): array {
		/** @var ApiKeyRepository $repo */
		$repo = $this->repo(ApiKey::class);
		$key  = $repo->findActiveByApi($apiKey);
		if($key === null) {
			return ['error' => 'invalid_client', 'message' => __('Неверный или неактивный API-ключ')];
		}
		if(DleApiConfig::isDemoMode()) {
			return DleApiConfig::demoAuthorizedResponse();
		}

		return $this->persistTokensForKey($key, $key->user_id, $scope);
	}

	/**
	 * @return array<string, mixed>
	 */
	public function issueByAuth(string $username, string $password, string $scope = ''): array {
		$userId = (new DleUserPasswordVerifier())->verify($username, $password);
		if($userId === null) {
			return ['error' => 'invalid_grant', 'message' => __('Неверный логин или пароль')];
		}
		$key = $this->resolveApiKeyForUser($userId);
		if($key === null) {
			return ['error' => 'access_denied', 'message' => __('Нет API-ключа пользователя и гостевого ключа')];
		}
		if(DleApiConfig::isDemoMode()) {
			return DleApiConfig::demoAuthorizedResponse();
		}

		return $this->persistTokensForKey($key, $userId, $scope);
	}

	/**
	 * @return array<string, mixed>
	 */
	public function issueByOauthClient(string $clientId, string $clientSecret, string $scope = ''): array {
		$client = $this->findActiveClient($clientId);
		if($client === null || !password_verify($clientSecret, $client->client_secret)) {
			return ['error' => 'invalid_client', 'message' => __('Неверный client_id или client_secret')];
		}
		if(!$this->clientAllowsGrant($client, 'client_credentials')) {
			return ['error' => 'unauthorized_client', 'message' => __('Клиент не разрешает grant_type=client_credentials')];
		}
		$key = $this->findActiveApiKey($client->api_key_id);
		if($key === null) {
			return ['error' => 'invalid_client', 'message' => __('API-ключ клиента неактивен')];
		}
		if(DleApiConfig::isDemoMode()) {
			return DleApiConfig::demoAuthorizedResponse();
		}

		return $this->persistTokens($client, $key, $key->user_id, $scope);
	}

	/**
	 * @return array<string, mixed>
	 */
	public function issueClientCredentials(string $clientId, string $clientSecret, string $scope = ''): array {
		return $this->issueByOauthClient($clientId, $clientSecret, $scope);
	}

	/**
	 * @return array<string, mixed>
	 */
	public function issuePassword(
		string $clientId,
		string $clientSecret,
		string $username,
		string $password,
		string $scope = '',
	): array {
		if($clientId === '' && $clientSecret === '') {
			return $this->issueByAuth($username, $password, $scope);
		}
		if($clientId === '' || $clientSecret === '') {
			return ['error' => 'invalid_client', 'message' => __('Нужны оба: client_id и client_secret')];
		}
		$client = $this->findActiveClient($clientId);
		if($client === null || !password_verify($clientSecret, $client->client_secret)) {
			return ['error' => 'invalid_client', 'message' => __('Неверный client_id или client_secret')];
		}
		if(!$this->clientAllowsGrant($client, 'password')) {
			return ['error' => 'unauthorized_client', 'message' => __('Клиент не разрешает grant_type=password')];
		}
		$key = $this->findActiveApiKey($client->api_key_id);
		if($key === null) {
			return ['error' => 'invalid_client', 'message' => __('API-ключ клиента неактивен')];
		}
		$userId = (new DleUserPasswordVerifier())->verify($username, $password);
		if($userId === null) {
			return ['error' => 'invalid_grant', 'message' => __('Неверный логин или пароль')];
		}
		if(DleApiConfig::isDemoMode()) {
			return DleApiConfig::demoAuthorizedResponse();
		}

		return $this->persistTokens($client, $key, $userId, $scope);
	}

	/**
	 * @return array<string, mixed>
	 */
	public function issueAuthorizationCode(
		string $clientId,
		string $clientSecret,
		string $code,
		string $redirectUri,
		string $codeVerifier = '',
	): array {
		$client = $this->findActiveClient($clientId);
		if($client === null || !password_verify($clientSecret, $client->client_secret)) {
			return ['error' => 'invalid_client', 'message' => __('Неверный client')];
		}
		if(!$this->clientAllowsGrant($client, 'authorization_code')) {
			return ['error' => 'unauthorized_client', 'message' => __('Клиент не разрешает grant_type=authorization_code')];
		}
		/** @var OauthAuthCodeRepository $codes */
		$codes = $this->repo(OauthAuthCode::class);
		$row   = $codes->findValidByCode($code);
		if($row === null || $row->client_id !== $clientId) {
			return ['error' => 'invalid_grant', 'message' => __('Код недействителен')];
		}
		if($redirectUri !== '' && (string) ($row->redirect_uri ?? '') !== $redirectUri) {
			return ['error' => 'invalid_grant', 'message' => __('redirect_uri не совпадает')];
		}
		$challenge = (string) ($row->code_challenge ?? '');
		if($challenge !== '') {
			$method = (string) ($row->code_challenge_method ?? 'S256');
			$calc   = $method === 'plain'
				? $codeVerifier
				: rtrim(strtr(base64_encode(hash('sha256', $codeVerifier, true)), '+/', '-_'), '=');
			if(!hash_equals($challenge, $calc)) {
				return ['error' => 'invalid_grant', 'message' => __('Ошибка проверки PKCE')];
			}
		}
		if(DleApiConfig::isDemoMode()) {
			return DleApiConfig::demoAuthorizedResponse();
		}
		$codes->revokeEntity($row);
		$key = $this->findActiveApiKey($client->api_key_id);
		if($key === null) {
			return ['error' => 'invalid_client', 'message' => __('API-ключ неактивен')];
		}

		return $this->persistTokens($client, $key, $row->user_id, (string) ($row->scopes ?? ''));
	}

	/**
	 * @return array<string, mixed>
	 */
	public function refresh(string $refreshToken): array {
		/** @var OauthRefreshTokenRepository $refreshRepo */
		$refreshRepo = $this->repo(OauthRefreshToken::class);
		$row         = $refreshRepo->findValidByTokenId($refreshToken);
		if($row === null) {
			return ['error' => 'invalid_grant', 'message' => __('Refresh token недействителен')];
		}
		/** @var OauthAccessTokenRepository $accessRepo */
		$accessRepo = $this->repo(OauthAccessToken::class);
		$access     = $accessRepo->findByTokenId($row->access_token_id);
		if($access === null) {
			return ['error' => 'invalid_grant', 'message' => __('Связанный access token не найден')];
		}
		$client = $this->findActiveClient($access->client_id);
		$key    = $this->findActiveApiKey($access->api_key_id);
		if($client === null || $key === null) {
			return ['error' => 'invalid_client', 'message' => __('Клиент или ключ неактивен')];
		}
		if(!$this->clientAllowsGrant($client, 'refresh_token')) {
			return ['error' => 'unauthorized_client', 'message' => __('Клиент не разрешает grant_type=refresh_token')];
		}
		if(DleApiConfig::isDemoMode()) {
			return DleApiConfig::demoAuthorizedResponse();
		}
		$refreshRepo->revokeEntity($row);
		$accessRepo->revokeEntity($access);

		return $this->persistTokens($client, $key, $access->user_id, (string) ($access->scopes ?? ''));
	}

	public function revoke(string $token): void {
		/** @var OauthAccessTokenRepository $accessRepo */
		$accessRepo = $this->repo(OauthAccessToken::class);
		$accessRepo->revokeByAccessOrTokenId($token);
		/** @var OauthRefreshTokenRepository $refreshRepo */
		$refreshRepo = $this->repo(OauthRefreshToken::class);
		$refreshRepo->revokeByTokenId($token);
	}

	/**
	 * Только AuthToken (не сырой API-ключ).
	 *
	 * @return array{auth_via: 'access_token', token: array<string, mixed>, key: array<string, mixed>}|null
	 */
	public function validateAccessToken(string $bearer): ?array {
		/** @var OauthAccessTokenRepository $accessRepo */
		$accessRepo = $this->repo(OauthAccessToken::class);
		$row        = $accessRepo->findValidByAccessToken($bearer);
		if($row === null) {
			return null;
		}
		$key = $this->findActiveApiKey($row->api_key_id);
		if($key === null) {
			return null;
		}

		return [
			'auth_via' => 'access_token',
			'token'    => [
				'token_id'     => $row->token_id,
				'access_token' => $row->access_token,
				'client_id'    => $row->client_id,
				'user_id'      => $row->user_id,
				'api_key_id'   => $row->api_key_id,
				'scopes'       => $row->scopes,
			],
			'key' => ApiKeyRepository::toAuthArray($key),
		];
	}

	public function resolveApiKeyForUser(int $userId): ?ApiKey {
		/** @var ApiKeyRepository $repo */
		$repo = $this->repo(ApiKey::class);
		$key  = $repo->findActiveByUserId($userId);
		if($key !== null) {
			return $key;
		}

		return $repo->findActiveGuest();
	}

	/**
	 * Проверка client_id / redirect_uri / grant authorization_code до выдачи code.
	 *
	 * @return array{error: string, message: string}|null null — OK
	 */
	public function validateAuthorizeRequest(string $clientId, string $redirectUri): ?array {
		$client = $this->findActiveClient($clientId);
		if($client === null) {
			return ['error' => 'invalid_client', 'message' => __('Неизвестный или неактивный client_id')];
		}
		if(!$this->clientAllowsGrant($client, 'authorization_code')) {
			return ['error' => 'unauthorized_client', 'message' => __('Клиент не разрешает grant_type=authorization_code')];
		}
		$registered = (string) ($client->redirect_uri ?? '');
		if($registered === '') {
			return ['error' => 'invalid_request', 'message' => __('У клиента не задан redirect_uri')];
		}
		if($registered !== $redirectUri) {
			return ['error' => 'invalid_request', 'message' => __('redirect_uri не совпадает с зарегистрированным у клиента')];
		}

		return null;
	}

	public function createAuthCode(
		string $clientId,
		int $userId,
		string $redirectUri,
		string $scopes = '',
		string $codeChallenge = '',
		string $codeChallengeMethod = 'S256',
	): string {
		$code = bin2hex(random_bytes(32));
		/** @var OauthAuthCodeRepository $codes */
		$codes = $this->repo(OauthAuthCode::class);
		$codes->createCode(
			$code,
			$clientId,
			$userId,
			$scopes,
			$redirectUri,
			$codeChallenge,
			$codeChallengeMethod,
			new \DateTimeImmutable('+' . 600 . ' seconds'),
		);

		return $code;
	}

	/**
	 * @return array{access_token: string, token_type: string, expires_in: int, refresh_token: string}
	 */
	private function persistTokens(OauthClient $client, ApiKey $key, int $userId, string $scopes): array {
		return $this->persistTokensForKey($key, $userId, $scopes, $client->client_id);
	}

	/**
	 * @return array{access_token: string, token_type: string, expires_in: int, refresh_token: string}
	 */
	private function persistTokensForKey(ApiKey $key, int $userId, string $scopes, string $clientId = ''): array {
		$ttl         = $this->tokenTtl();
		$tokenId     = bin2hex(random_bytes(16));
		$accessToken = bin2hex(random_bytes(32));
		$refreshId   = bin2hex(random_bytes(16));
		/** @var OauthAccessTokenRepository $accessRepo */
		$accessRepo = $this->repo(OauthAccessToken::class);
		$accessRepo->createToken(
			$tokenId,
			$accessToken,
			$clientId,
			$userId,
			$key->id(),
			$scopes,
			new \DateTimeImmutable('+' . $ttl . ' seconds'),
		);
		/** @var OauthRefreshTokenRepository $refreshRepo */
		$refreshRepo = $this->repo(OauthRefreshToken::class);
		$refreshRepo->createToken(
			$refreshId,
			$tokenId,
			new \DateTimeImmutable('+' . (86400 * 30) . ' seconds'),
		);

		return [
			'access_token'  => $accessToken,
			'token_type'    => 'Bearer',
			'expires_in'    => $ttl,
			'refresh_token' => $refreshId,
		];
	}

	private function clientAllowsGrant(OauthClient $client, string $grant): bool {
		$raw = $client->grant_types;
		if($raw === '') {
			return false;
		}

		return in_array($grant, array_map('trim', explode(',', $raw)), true);
	}

	private function findActiveClient(string $clientId): ?OauthClient {
		/** @var OauthClientRepository $repo */
		$repo = $this->repo(OauthClient::class);

		return $repo->findActiveByClientId($clientId);
	}

	private function findActiveApiKey(int $id): ?ApiKey {
		/** @var ApiKeyRepository $repo */
		$repo = $this->repo(ApiKey::class);

		return $repo->findActive($id);
	}

	/**
	 * @template T of object
	 * @param class-string<T> $entity
	 * @return object
	 */
	private function repo(string $entity): object {
		return Application::instance()->database()->repository($entity);
	}

	private function tokenTtl(): int {
		$cfg = DleApiConfig::all();
		if(isset($cfg['token_ttl'])) {
			return max(60, (int) $cfg['token_ttl']);
		}

		return 3600;
	}

}
>>>>>>> Current commit: Начало обновления до api v2
