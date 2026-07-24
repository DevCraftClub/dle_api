<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `plugins` (DLE install.php).
 */
#[OA\Schema(schema: 'Plugins')]
final class PluginsSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (plugins.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'name',
		type: 'string',
		description: 'Имя (plugins.name)',
	)]
	public string $name = '';
	#[OA\Property(
		property: 'description',
		type: 'string',
		description: 'Колонка plugins.description',
	)]
	public string $description = '';
	#[OA\Property(
		property: 'icon',
		type: 'string',
		description: 'Колонка plugins.icon',
	)]
	public string $icon = '';
	#[OA\Property(
		property: 'version',
		type: 'string',
		description: 'Колонка plugins.version',
	)]
	public string $version = '';
	#[OA\Property(
		property: 'dleversion',
		type: 'string',
		description: 'Колонка plugins.dleversion',
	)]
	public string $dleversion = '';
	#[OA\Property(
		property: 'versioncompare',
		type: 'string',
		description: 'Колонка plugins.versioncompare',
	)]
	public string $versioncompare = '';
	#[OA\Property(
		property: 'active',
		type: 'integer',
		description: 'Колонка plugins.active',
	)]
	public int $active = 0;
	#[OA\Property(
		property: 'mysqlinstall',
		type: 'string',
		description: 'Колонка plugins.mysqlinstall',
	)]
	public string $mysqlinstall = '';
	#[OA\Property(
		property: 'mysqlupgrade',
		type: 'string',
		description: 'Колонка plugins.mysqlupgrade',
	)]
	public string $mysqlupgrade = '';
	#[OA\Property(
		property: 'mysqlenable',
		type: 'string',
		description: 'Колонка plugins.mysqlenable',
	)]
	public string $mysqlenable = '';
	#[OA\Property(
		property: 'mysqldisable',
		type: 'string',
		description: 'Колонка plugins.mysqldisable',
	)]
	public string $mysqldisable = '';
	#[OA\Property(
		property: 'mysqldelete',
		type: 'string',
		description: 'Колонка plugins.mysqldelete',
	)]
	public string $mysqldelete = '';
	#[OA\Property(
		property: 'filedelete',
		type: 'integer',
		description: 'Колонка plugins.filedelete',
	)]
	public int $filedelete = 0;
	#[OA\Property(
		property: 'filelist',
		type: 'string',
		description: 'Колонка plugins.filelist',
	)]
	public string $filelist = '';
	#[OA\Property(
		property: 'upgradeurl',
		type: 'string',
		description: 'Колонка plugins.upgradeurl',
	)]
	public string $upgradeurl = '';
	#[OA\Property(
		property: 'needplugin',
		type: 'string',
		description: 'Колонка plugins.needplugin',
	)]
	public string $needplugin = '';
	#[OA\Property(
		property: 'phpinstall',
		type: 'string',
		description: 'Колонка plugins.phpinstall',
	)]
	public string $phpinstall = '';
	#[OA\Property(
		property: 'phpupgrade',
		type: 'string',
		description: 'Колонка plugins.phpupgrade',
	)]
	public string $phpupgrade = '';
	#[OA\Property(
		property: 'phpenable',
		type: 'string',
		description: 'Колонка plugins.phpenable',
	)]
	public string $phpenable = '';
	#[OA\Property(
		property: 'phpdisable',
		type: 'string',
		description: 'Колонка plugins.phpdisable',
	)]
	public string $phpdisable = '';
	#[OA\Property(
		property: 'phpdelete',
		type: 'string',
		description: 'Колонка plugins.phpdelete',
	)]
	public string $phpdelete = '';
	#[OA\Property(
		property: 'notice',
		type: 'string',
		description: 'Колонка plugins.notice',
	)]
	public string $notice = '';
	#[OA\Property(
		property: 'mnotice',
		type: 'integer',
		description: 'Колонка plugins.mnotice',
	)]
	public int $mnotice = 0;
	#[OA\Property(
		property: 'posi',
		type: 'integer',
		description: 'Колонка plugins.posi',
	)]
	public int $posi = 1;

	public function table(): string {
		return 'plugins';
	}

	protected function columnList(): array {
		return [
			'id',
			'name',
			'description',
			'icon',
			'version',
			'dleversion',
			'versioncompare',
			'active',
			'mysqlinstall',
			'mysqlupgrade',
			'mysqlenable',
			'mysqldisable',
			'mysqldelete',
			'filedelete',
			'filelist',
			'upgradeurl',
			'needplugin',
			'phpinstall',
			'phpupgrade',
			'phpenable',
			'phpdisable',
			'phpdelete',
			'notice',
			'mnotice',
			'posi',
		];
	}

	protected function defaultMap(): array {
		return [
			'name' => '',
			'description' => '',
			'icon' => '',
			'version' => '',
			'dleversion' => '',
			'versioncompare' => '',
			'active' => 0,
			'mysqlinstall' => '',
			'mysqlupgrade' => '',
			'mysqlenable' => '',
			'mysqldisable' => '',
			'mysqldelete' => '',
			'filedelete' => 0,
			'filelist' => '',
			'upgradeurl' => '',
			'needplugin' => '',
			'phpinstall' => '',
			'phpupgrade' => '',
			'phpenable' => '',
			'phpdisable' => '',
			'phpdelete' => '',
			'notice' => '',
			'mnotice' => 0,
			'posi' => 1,
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}

	public function withName(string $name): static {
		return $this->with('name', $name);
	}

	public function withDescription(string $description): static {
		return $this->with('description', $description);
	}

	public function withVersion(string $version): static {
		return $this->with('version', $version);
	}

	public function withFilesEntity(PluginsFilesSchema $entity): static {
		return $this->attachChildEntity('plugins_files', $entity);
	}
}
