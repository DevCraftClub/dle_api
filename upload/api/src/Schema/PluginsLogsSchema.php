<<<<<<< New base: Update README.md
<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `plugins_logs`.
 */
#[OA\Schema(schema: 'PluginsLogs')]
final class PluginsLogsSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (plugins_logs.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'plugin_id',
		type: 'integer',
		description: 'Колонка plugins_logs.plugin_id',
	)]
	public int $plugin_id = 0;
	#[OA\Property(
		property: 'area',
		type: 'string',
		description: 'Колонка plugins_logs.area',
	)]
	public string $area = '';
	#[OA\Property(
		property: 'error',
		type: 'string',
		description: 'Колонка plugins_logs.error',
	)]
	public string $error = '';
	#[OA\Property(
		property: 'type',
		type: 'string',
		description: 'Колонка plugins_logs.type',
	)]
	public string $type = '';
	#[OA\Property(
		property: 'action_id',
		type: 'integer',
		description: 'Колонка plugins_logs.action_id',
	)]
	public int $action_id = 0;

	public function table(): string {
		return 'plugins_logs';
	}

	protected function columnList(): array {
		return [
			'id',
			'plugin_id',
			'area',
			'error',
			'type',
			'action_id',
		];
	}

	protected function defaultMap(): array {
		return [
			'plugin_id' => 0,
			'area' => '',
			'error' => '',
			'type' => '',
			'action_id' => 0,
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
 * Схема таблицы `plugins_logs` (DLE install.php).
 */
#[OA\Schema(schema: 'PluginsLogs')]
final class PluginsLogsSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (plugins_logs.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'plugin_id',
		type: 'integer',
		description: 'Колонка plugins_logs.plugin_id',
	)]
	public int $plugin_id = 0;
	#[OA\Property(
		property: 'area',
		type: 'string',
		description: 'Колонка plugins_logs.area',
	)]
	public string $area = '';
	#[OA\Property(
		property: 'error',
		type: 'string',
		description: 'Колонка plugins_logs.error',
	)]
	public string $error = '';
	#[OA\Property(
		property: 'type',
		type: 'string',
		description: 'Колонка plugins_logs.type',
	)]
	public string $type = '';
	#[OA\Property(
		property: 'action_id',
		type: 'integer',
		description: 'Колонка plugins_logs.action_id',
	)]
	public int $action_id = 0;

	public function table(): string {
		return 'plugins_logs';
	}

	protected function columnList(): array {
		return [
			'id',
			'plugin_id',
			'area',
			'error',
			'type',
			'action_id',
		];
	}

	protected function defaultMap(): array {
		return [
			'plugin_id' => 0,
			'area' => '',
			'error' => '',
			'type' => '',
			'action_id' => 0,
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}
}
>>>>>>> Current commit: Начало обновления до api v2
