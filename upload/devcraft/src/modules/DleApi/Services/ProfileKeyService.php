<?php

declare(strict_types=1);

namespace DevCraft\Modules\DleApi\Services;

use DevCraft\Core\Application;
use DevCraft\Modules\DleApi\Models\ApiKey;
use DevCraft\Modules\DleApi\Models\ApiKeyRequest;
use DevCraft\Modules\DleApi\Repositories\ApiKeyRepository;
use DevCraft\Modules\DleApi\Repositories\ApiKeyRequestRepository;

/**
 * Генерация / заявка API-ключа для профиля пользователя.
 */
final class ProfileKeyService {

	public function __construct(
		private readonly AccessLevelResolver $levels = new AccessLevelResolver(),
		private readonly KeyNotifyDelivery $notify = new KeyNotifyDelivery(),
		private readonly ApiKeyGenerator $generator = new ApiKeyGenerator(),
	) {
	}

	/**
	 * @return array{ok: bool, status?: string, key?: string, message?: string, request_id?: int}
	 */
	public function requestOrGenerate(int $userId): array {
		$cfg = DleApiConfig::all();
		if(empty($cfg['profile_allow_generate'])) {
			return ['ok' => false, 'message' => __('Генерация ключа запрещена')];
		}
		$level = $this->levels->forUserId($userId);
		if($level === null) {
			return ['ok' => false, 'message' => __('Не задан уровень доступа по умолчанию')];
		}

		/** @var ApiKeyRequestRepository $reqRepo */
		$reqRepo = Application::instance()->database()->repository(ApiKeyRequest::class);
		$pending = $reqRepo->findPendingByUser($userId);
		if($pending !== null) {
			return ['ok' => false, 'message' => __('Заявка уже ожидает рассмотрения'), 'request_id' => $pending->id()];
		}

		if($level->premoderate) {
			$req = $reqRepo->create($userId, $level->id());
			$recipients = $this->recipientUserIds($cfg);
			$this->notify->notifyRequest($recipients, [
				'{%user_id%}'   => (string) $userId,
				'{%level%}'     => $level->name,
				'{%request_id%}' => (string) $req->id(),
				'{%subject%}'   => __('Заявка на API-ключ'),
			]);

			return ['ok' => true, 'status' => 'pending', 'request_id' => $req->id(), 'message' => __('Заявка отправлена на модерацию')];
		}

		$key = $this->generateForUser($userId, $level->id());

		return ['ok' => true, 'status' => 'created', 'key' => $key->api];
	}

	public function generateForUser(int $userId, int $accessLevelId): ApiKey {
		if($accessLevelId < 1) {
			$level = $this->levels->forUserId($userId);
			$accessLevelId = $level?->id() ?? 0;
		}
		$level = $accessLevelId > 0 ? $this->levels->findActive($accessLevelId) : null;

		/** @var ApiKeyRepository $keys */
		$keys = Application::instance()->database()->repository(ApiKey::class);
		$api  = $this->generator->generate();
		$key  = $keys->create([
			'api'              => $api,
			'user_id'          => $userId,
			'active'           => true,
			'is_admin'         => $level?->cheater ?? false,
			'own_only'         => $level ? ($level->own_only && !$level->cheater) : true,
			'access_level_id'  => $accessLevelId,
			'creator'          => $userId,
		]);
		$this->writeXfield($userId, $api);

		return $key;
	}

	public function writeXfield(int $userId, string $apiKey): void {
		$cfg   = DleApiConfig::all();
		$field = (string) ($cfg['profile_xfield'] ?? '');
		if($field === '') {
			return;
		}
		global $db;
		$row = $db->super_query('SELECT xfields FROM ' . USERPREFIX . '_users WHERE user_id=' . (int) $userId);
		$xf  = (string) ($row['xfields'] ?? '');
		$parts = $xf !== '' ? explode('||', $xf) : [];
		$found = false;
		foreach($parts as $i => $p) {
			if(str_starts_with($p, $field . '|')) {
				$parts[$i] = $field . '|' . $apiKey;
				$found     = true;
				break;
			}
		}
		if(!$found) {
			$parts[] = $field . '|' . $apiKey;
		}
		$new = implode('||', array_filter($parts, static fn(string $p): bool => $p !== ''));
		$db->query('UPDATE ' . USERPREFIX . "_users SET xfields='" . $db->safesql($new) . "' WHERE user_id=" . (int) $userId);
	}

	/**
	 * @param array<string, mixed> $cfg
	 * @return list<int>
	 */
	private function recipientUserIds(array $cfg): array {
		global $db;
		$ids = [];
		foreach((array) ($cfg['notify_user_ids'] ?? []) as $id) {
			$ids[] = (int) $id;
		}
		foreach((array) ($cfg['notify_group_ids'] ?? []) as $gid) {
			$gid = (int) $gid;
			if($gid < 1) {
				continue;
			}
			$db->query('SELECT user_id FROM ' . USERPREFIX . '_users WHERE user_group=' . $gid);
			while($r = $db->get_row()) {
				$ids[] = (int) $r['user_id'];
			}
		}
		$ids = array_values(array_unique(array_filter($ids)));

		return $ids !== [] ? $ids : [1];
	}

}
