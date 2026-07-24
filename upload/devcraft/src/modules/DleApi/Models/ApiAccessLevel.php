<?php

declare(strict_types=1);

namespace DevCraft\Modules\DleApi\Models;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use DevCraft\Core\Abstracts\AbstractEntity;
use DevCraft\Modules\DleApi\Repositories\ApiAccessLevelRepository;

/**
 * Уровень доступа API (`{prefix}_api_access_levels`).
 */
#[Entity(role: 'api_access_level', repository: ApiAccessLevelRepository::class, table: 'api_access_levels')]
class ApiAccessLevel extends AbstractEntity {

	#[Column(type: 'string')]
	public string $name = '';

	#[Column(type: 'boolean', default: true)]
	public bool $active = true;

	#[Column(type: 'integer', default: 0)]
	public int $sort = 0;

	#[Column(type: 'boolean', default: false)]
	public bool $premoderate = false;

	#[Column(type: 'boolean', default: true)]
	public bool $own_only = true;

	/** Полный доступ ко всем таблицам (бывший is_admin). */
	#[Column(type: 'boolean', default: false)]
	public bool $cheater = false;

	#[Column(type: 'boolean', default: true)]
	public bool $mask_ip = true;

	#[Column(type: 'boolean', default: true)]
	public bool $mask_passwords = true;

	#[Column(type: 'boolean', default: true)]
	public bool $mask_personal = true;

	public function __construct() {
		$this->createdAt = new \DateTimeImmutable();
	}

	public function getColumnVal(string $name): mixed {
		return match ($name) {
			'id'             => $this->id(),
			'name'           => $this->name,
			'active'         => $this->active,
			'sort'           => $this->sort,
			'premoderate'    => $this->premoderate,
			'own_only'       => $this->own_only,
			'cheater'        => $this->cheater,
			'mask_ip'        => $this->mask_ip,
			'mask_passwords' => $this->mask_passwords,
			'mask_personal'  => $this->mask_personal,
			default          => null,
		};
	}

}
