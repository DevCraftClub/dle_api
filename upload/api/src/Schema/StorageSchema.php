<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `storage` (DLE install.php).
 */
#[OA\Schema(schema: 'Storage')]
final class StorageSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (storage.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'name',
		type: 'integer',
		description: 'Имя (storage.name)',
	)]
	public int $name = 0;
	#[OA\Property(
		property: 'type',
		type: 'integer',
		description: 'Колонка storage.type',
	)]
	public int $type = 0;
	#[OA\Property(
		property: 'accesstype',
		type: 'string',
		description: 'Колонка storage.accesstype',
	)]
	public string $accesstype = '';
	#[OA\Property(
		property: 'connect_url',
		type: 'string',
		description: 'Колонка storage.connect_url',
	)]
	public string $connect_url = '';
	#[OA\Property(
		property: 'connect_port',
		type: 'integer',
		description: 'Колонка storage.connect_port',
	)]
	public int $connect_port = 0;
	#[OA\Property(
		property: 'username',
		type: 'string',
		description: 'Колонка storage.username',
	)]
	public string $username = '';
	#[OA\Property(
		property: 'password',
		type: 'string',
		description: 'Хеш пароля (storage.password)',
	)]
	public string $password = '';
	#[OA\Property(
		property: 'path',
		type: 'string',
		description: 'Колонка storage.path',
	)]
	public string $path = '';
	#[OA\Property(
		property: 'http_url',
		type: 'string',
		description: 'Колонка storage.http_url',
	)]
	public string $http_url = '';
	#[OA\Property(
		property: 'client_key',
		type: 'string',
		description: 'Колонка storage.client_key',
	)]
	public string $client_key = '';
	#[OA\Property(
		property: 'secret_key',
		type: 'string',
		description: 'Колонка storage.secret_key',
	)]
	public string $secret_key = '';
	#[OA\Property(
		property: 'bucket',
		type: 'string',
		description: 'Колонка storage.bucket',
	)]
	public string $bucket = '';
	#[OA\Property(
		property: 'region',
		type: 'string',
		description: 'Колонка storage.region',
	)]
	public string $region = '';
	#[OA\Property(
		property: 'default_storage',
		type: 'integer',
		description: 'Колонка storage.default_storage',
	)]
	public int $default_storage = 0;
	#[OA\Property(
		property: 'enabled',
		type: 'integer',
		description: 'Колонка storage.enabled',
	)]
	public int $enabled = 1;
	#[OA\Property(
		property: 'posi',
		type: 'integer',
		description: 'Колонка storage.posi',
	)]
	public int $posi = 1;

	public function table(): string {
		return 'storage';
	}

	protected function columnList(): array {
		return [
			'id',
			'name',
			'type',
			'accesstype',
			'connect_url',
			'connect_port',
			'username',
			'password',
			'path',
			'http_url',
			'client_key',
			'secret_key',
			'bucket',
			'region',
			'default_storage',
			'enabled',
			'posi',
		];
	}

	protected function defaultMap(): array {
		return [
			'name' => 0,
			'type' => 0,
			'accesstype' => '',
			'connect_url' => '',
			'connect_port' => 0,
			'username' => '',
			'password' => '',
			'path' => '',
			'http_url' => '',
			'client_key' => '',
			'secret_key' => '',
			'bucket' => '',
			'region' => '',
			'default_storage' => 0,
			'enabled' => 1,
			'posi' => 1,
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}
}
