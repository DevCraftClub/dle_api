<<<<<<< New base: Update README.md
<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `social_login`.
 */
#[OA\Schema(schema: 'SocialLogin')]
final class SocialLoginSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (social_login.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'sid',
		type: 'string',
		description: 'Колонка social_login.sid',
	)]
	public string $sid = '';
	#[OA\Property(
		property: 'uid',
		type: 'integer',
		description: 'Колонка social_login.uid',
	)]
	public int $uid = 0;
	#[OA\Property(
		property: 'password',
		type: 'string',
		description: 'Хеш пароля (social_login.password)',
	)]
	public string $password = '';
	#[OA\Property(
		property: 'provider',
		type: 'string',
		description: 'Колонка social_login.provider',
	)]
	public string $provider = '';
	#[OA\Property(
		property: 'wait',
		type: 'integer',
		description: 'Колонка social_login.wait',
	)]
	public int $wait = 0;
	#[OA\Property(
		property: 'waitlogin',
		type: 'integer',
		description: 'Колонка social_login.waitlogin',
	)]
	public int $waitlogin = 0;

	public function table(): string {
		return 'social_login';
	}

	protected function columnList(): array {
		return [
			'id',
			'sid',
			'uid',
			'password',
			'provider',
			'wait',
			'waitlogin',
		];
	}

	protected function defaultMap(): array {
		return [
			'sid' => '',
			'uid' => 0,
			'password' => '',
			'provider' => '',
			'wait' => 0,
			'waitlogin' => 0,
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}
}
|||||||
=======
<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `social_login` (DLE install.php).
 */
#[OA\Schema(schema: 'SocialLogin')]
final class SocialLoginSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (social_login.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'sid',
		type: 'string',
		description: 'Колонка social_login.sid',
	)]
	public string $sid = '';
	#[OA\Property(
		property: 'uid',
		type: 'integer',
		description: 'Колонка social_login.uid',
	)]
	public int $uid = 0;
	#[OA\Property(
		property: 'password',
		type: 'string',
		description: 'Хеш пароля (social_login.password)',
	)]
	public string $password = '';
	#[OA\Property(
		property: 'provider',
		type: 'string',
		description: 'Колонка social_login.provider',
	)]
	public string $provider = '';
	#[OA\Property(
		property: 'wait',
		type: 'integer',
		description: 'Колонка social_login.wait',
	)]
	public int $wait = 0;
	#[OA\Property(
		property: 'waitlogin',
		type: 'integer',
		description: 'Колонка social_login.waitlogin',
	)]
	public int $waitlogin = 0;

	public function table(): string {
		return 'social_login';
	}

	protected function columnList(): array {
		return [
			'id',
			'sid',
			'uid',
			'password',
			'provider',
			'wait',
			'waitlogin',
		];
	}

	protected function defaultMap(): array {
		return [
			'sid' => '',
			'uid' => 0,
			'password' => '',
			'provider' => '',
			'wait' => 0,
			'waitlogin' => 0,
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}
}
>>>>>>> Current commit: Начало обновления до api v2
