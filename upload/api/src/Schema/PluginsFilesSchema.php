<<<<<<< New base: Update README.md
<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `plugins_files`.
 */
#[OA\Schema(schema: 'PluginsFiles')]
final class PluginsFilesSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (plugins_files.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'plugin_id',
		type: 'integer',
		description: 'Колонка plugins_files.plugin_id',
	)]
	public int $plugin_id = 0;
	#[OA\Property(
		property: 'file',
		type: 'string',
		description: 'Колонка plugins_files.file',
	)]
	public string $file = '';
	#[OA\Property(
		property: 'action',
		type: 'string',
		description: 'Колонка plugins_files.action',
	)]
	public string $action = '';
	#[OA\Property(
		property: 'searchcode',
		type: 'string',
		description: 'Колонка plugins_files.searchcode',
	)]
	public string $searchcode = '';
	#[OA\Property(
		property: 'replacecode',
		type: 'string',
		description: 'Колонка plugins_files.replacecode',
	)]
	public string $replacecode = '';
	#[OA\Property(
		property: 'active',
		type: 'integer',
		description: 'Колонка plugins_files.active',
	)]
	public int $active = 0;
	#[OA\Property(
		property: 'searchcount',
		type: 'integer',
		description: 'Колонка plugins_files.searchcount',
	)]
	public int $searchcount = 0;
	#[OA\Property(
		property: 'replacecount',
		type: 'integer',
		description: 'Колонка plugins_files.replacecount',
	)]
	public int $replacecount = 0;
	#[OA\Property(
		property: 'filedisable',
		type: 'integer',
		description: 'Колонка plugins_files.filedisable',
	)]
	public int $filedisable = 1;
	#[OA\Property(
		property: 'filedleversion',
		type: 'string',
		description: 'Колонка plugins_files.filedleversion',
	)]
	public string $filedleversion = '';
	#[OA\Property(
		property: 'fileversioncompare',
		type: 'string',
		description: 'Колонка plugins_files.fileversioncompare',
	)]
	public string $fileversioncompare = '';

	public function table(): string {
		return 'plugins_files';
	}

	protected function columnList(): array {
		return [
			'id',
			'plugin_id',
			'file',
			'action',
			'searchcode',
			'replacecode',
			'active',
			'searchcount',
			'replacecount',
			'filedisable',
			'filedleversion',
			'fileversioncompare',
		];
	}

	protected function defaultMap(): array {
		return [
			'plugin_id' => 0,
			'file' => '',
			'action' => '',
			'searchcode' => '',
			'replacecode' => '',
			'active' => 0,
			'searchcount' => 0,
			'replacecount' => 0,
			'filedisable' => 1,
			'filedleversion' => '',
			'fileversioncompare' => '',
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
 * Схема таблицы `plugins_files`.
 */
#[OA\Schema(schema: 'PluginsFiles')]
final class PluginsFilesSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (plugins_files.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'plugin_id',
		type: 'integer',
		description: 'Колонка plugins_files.plugin_id',
	)]
	public int $plugin_id = 0;
	#[OA\Property(
		property: 'file',
		type: 'string',
		description: 'Колонка plugins_files.file',
	)]
	public string $file = '';
	#[OA\Property(
		property: 'action',
		type: 'string',
		description: 'Колонка plugins_files.action',
	)]
	public string $action = '';
	#[OA\Property(
		property: 'searchcode',
		type: 'string',
		description: 'Колонка plugins_files.searchcode',
	)]
	public string $searchcode = '';
	#[OA\Property(
		property: 'replacecode',
		type: 'string',
		description: 'Колонка plugins_files.replacecode',
	)]
	public string $replacecode = '';
	#[OA\Property(
		property: 'active',
		type: 'integer',
		description: 'Колонка plugins_files.active',
	)]
	public int $active = 0;
	#[OA\Property(
		property: 'searchcount',
		type: 'integer',
		description: 'Колонка plugins_files.searchcount',
	)]
	public int $searchcount = 0;
	#[OA\Property(
		property: 'replacecount',
		type: 'integer',
		description: 'Колонка plugins_files.replacecount',
	)]
	public int $replacecount = 0;
	#[OA\Property(
		property: 'filedisable',
		type: 'integer',
		description: 'Колонка plugins_files.filedisable',
	)]
	public int $filedisable = 1;
	#[OA\Property(
		property: 'filedleversion',
		type: 'string',
		description: 'Колонка plugins_files.filedleversion',
	)]
	public string $filedleversion = '';
	#[OA\Property(
		property: 'fileversioncompare',
		type: 'string',
		description: 'Колонка plugins_files.fileversioncompare',
	)]
	public string $fileversioncompare = '';

	public function table(): string {
		return 'plugins_files';
	}

	protected function columnList(): array {
		return [
			'id',
			'plugin_id',
			'file',
			'action',
			'searchcode',
			'replacecode',
			'active',
			'searchcount',
			'replacecount',
			'filedisable',
			'filedleversion',
			'fileversioncompare',
		];
	}

	protected function defaultMap(): array {
		return [
			'plugin_id' => 0,
			'file' => '',
			'action' => '',
			'searchcode' => '',
			'replacecode' => '',
			'active' => 0,
			'searchcount' => 0,
			'replacecount' => 0,
			'filedisable' => 1,
			'filedleversion' => '',
			'fileversioncompare' => '',
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}
}
>>>>>>> Current commit: Начало обновления до api v2
