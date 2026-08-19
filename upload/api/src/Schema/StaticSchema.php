<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `static`.
 */
#[OA\Schema(schema: 'Static')]
final class StaticSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (static.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'name',
		type: 'string',
		description: 'Имя (static.name)',
	)]
	public string $name = '';
	#[OA\Property(
		property: 'descr',
		type: 'string',
		description: 'Описание (static.descr)',
	)]
	public string $descr = '';
	#[OA\Property(
		property: 'template',
		type: 'string',
		description: 'Колонка static.template',
	)]
	public string $template = '';
	#[OA\Property(
		property: 'allow_br',
		type: 'integer',
		description: 'Колонка static.allow_br',
	)]
	public int $allow_br = 0;
	#[OA\Property(
		property: 'allow_template',
		type: 'integer',
		description: 'Колонка static.allow_template',
	)]
	public int $allow_template = 0;
	#[OA\Property(
		property: 'grouplevel',
		type: 'string',
		description: 'CSV id или all (таблица static.grouplevel)',
	)]
	public string $grouplevel = 'all';
	#[OA\Property(
		property: 'tpl',
		type: 'string',
		description: 'Колонка static.tpl',
	)]
	public string $tpl = '';
	#[OA\Property(
		property: 'metadescr',
		type: 'string',
		description: 'Колонка static.metadescr',
	)]
	public string $metadescr = '';
	#[OA\Property(
		property: 'metakeys',
		type: 'string',
		description: 'Колонка static.metakeys',
	)]
	public string $metakeys = '';
	#[OA\Property(
		property: 'views',
		type: 'integer',
		description: 'Колонка static.views',
	)]
	public int $views = 0;
	#[OA\Property(
		property: 'template_folder',
		type: 'string',
		description: 'Колонка static.template_folder',
	)]
	public string $template_folder = '';
	#[OA\Property(
		property: 'date',
		type: 'integer',
		description: 'Дата/время (static.date)',
	)]
	public int $date = 0;
	#[OA\Property(
		property: 'metatitle',
		type: 'string',
		description: 'Колонка static.metatitle',
	)]
	public string $metatitle = '';
	#[OA\Property(
		property: 'allow_count',
		type: 'integer',
		description: 'Колонка static.allow_count',
	)]
	public int $allow_count = 1;
	#[OA\Property(
		property: 'sitemap',
		type: 'integer',
		description: 'Колонка static.sitemap',
	)]
	public int $sitemap = 1;
	#[OA\Property(
		property: 'disable_index',
		type: 'integer',
		description: 'Колонка static.disable_index',
	)]
	public int $disable_index = 0;
	#[OA\Property(
		property: 'disable_search',
		type: 'integer',
		description: 'Колонка static.disable_search',
	)]
	public int $disable_search = 0;
	#[OA\Property(
		property: 'password',
		type: 'string',
		description: 'Хеш пароля (static.password)',
	)]
	public string $password = '';

	public function table(): string {
		return 'static';
	}

	protected function columnList(): array {
		return [
			'id',
			'name',
			'descr',
			'template',
			'allow_br',
			'allow_template',
			'grouplevel',
			'tpl',
			'metadescr',
			'metakeys',
			'views',
			'template_folder',
			'date',
			'metatitle',
			'allow_count',
			'sitemap',
			'disable_index',
			'disable_search',
			'password',
		];
	}

	protected function defaultMap(): array {
		return [
			'name' => '',
			'descr' => '',
			'template' => '',
			'allow_br' => 0,
			'allow_template' => 0,
			'grouplevel' => 'all',
			'tpl' => '',
			'metadescr' => '',
			'metakeys' => '',
			'views' => 0,
			'template_folder' => '',
			'date' => 0,
			'metatitle' => '',
			'allow_count' => 1,
			'sitemap' => 1,
			'disable_index' => 0,
			'disable_search' => 0,
			'password' => '',
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}

	public function withName(string $name): static {
		return $this->with('name', $name);
	}

	public function withDescr(string $descr): static {
		return $this->with('descr', $descr);
	}

	public function withTemplate(string $template): static {
		return $this->with('template', $template);
	}

	public function withPassword(string $password): static {
		return $this->with('password', $password);
	}

	public function withMetatitle(string $metatitle): static {
		return $this->with('metatitle', $metatitle);
	}
}
