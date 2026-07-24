<?php

declare(strict_types=1);

namespace DevCraft\Modules\DleApi\Repositories;

use DevCraft\Core\Abstracts\AbstractRepository;
use DevCraft\Modules\DleApi\Models\ApiAccessLevelScope;

/**
 * Scopes уровня доступа.
 */
final class ApiAccessLevelScopeRepository extends AbstractRepository {

	/**
	 * @return list<ApiAccessLevelScope>
	 */
	public function forLevel(int $levelId): array {
		/** @var list<ApiAccessLevelScope> $rows */
		$rows = $this->select()->where('access_level_id', $levelId)->fetchAll();

		return $rows;
	}

	public function allows(int $levelId, string $table, string $action): bool {
		/** @var ApiAccessLevelScope|null $row */
		$row = $this->select()
			->where('access_level_id', $levelId)
			->where('table', $table)
			->fetchOne();
		if($row === null) {
			return false;
		}

		return match ($action) {
			'read'   => $row->can_read,
			'write'  => $row->can_write,
			'edit'   => $row->can_edit || $row->can_write,
			'delete' => $row->can_delete,
			default  => false,
		};
	}

	/**
	 * @param array<string, array{read?: bool, write?: bool, edit?: bool, delete?: bool}> $tables
	 */
	public function replaceForLevel(int $levelId, array $tables): void {
		foreach($this->forLevel($levelId) as $old) {
			$this->deleteEntity($old);
		}
		foreach($tables as $table => $flags) {
			$e                  = new ApiAccessLevelScope();
			$e->access_level_id = $levelId;
			$e->scope_table     = (string) $table;
			$e->can_read        = !empty($flags['read']);
			$e->can_write       = !empty($flags['write']);
			$e->can_edit        = !empty($flags['edit']);
			$e->can_delete      = !empty($flags['delete']);
			$this->saveEntity($e);
		}
	}

}
