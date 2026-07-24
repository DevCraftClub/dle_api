<?php

declare(strict_types=1);

namespace DevCraft\Modules\DleApi\Services;

use DevCraft\Core\Support\DataManager;

/**
 * Генерация API-ключей по настройкам модуля.
 */
final class ApiKeyGenerator {

	/**
	 * Генерирует значение ключа.
	 */
	public function generate(): string {
		$cfg    = DataManager::getConfig('dleapi', null, 'dleapi');
		$algo   = (string) ($cfg['algo'] ?? 'sha256');
		$secret = (string) ($cfg['secret'] ?? '');
		$length = max(16, (int) ($cfg['length'] ?? 32));

		if($secret === '') {
			$secret = bin2hex(random_bytes(16));
		}

		if(!in_array($algo, hash_hmac_algos(), true)) {
			$algo = 'sha256';
		}

		$raw = hash_hmac($algo, (string) microtime(true) . random_bytes(8), $secret);

		return substr($raw, 0, $length);
	}

}
