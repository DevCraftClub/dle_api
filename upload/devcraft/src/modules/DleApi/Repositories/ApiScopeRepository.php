<?php

declare(strict_types=1);

namespace DevCraft\Modules\DleApi\Repositories;

use DevCraft\Modules\DleApi\Models\ApiScope;
use DevCraft\Core\Abstracts\AbstractRepository;

/**
 * Репозиторий scope API-ключей.
 */
final class ApiScopeRepository extends AbstractRepository {

	/**
	 * @return array<string, array{read: bool, write: bool, edit: bool, delete: bool}>
	 */
	public function mapForKey(int $keyId): array {
		if($keyId < 1) {
			return [];
		}

		/** @var list<ApiScope> $rows */
		$rows = $this->select()->where('key_id', $keyId)->fetchAll();
		$map  = [];

		foreach($rows as $row) {
			$table = (string) ($row->scope_table ?? '');
			if($table === '') {
				continue;
			}
			$map[$table] = [
				'read'   => $row->can_read,
				'write'  => $row->can_write,
				'edit'   => $row->can_edit || $row->can_write,
				'delete' => $row->can_delete,
			];
		}

		return $map;
	}

	/**
	 * @param 'read'|'write'|'edit'|'delete' $action
	 */
	public function allows(int $keyId, string $table, string $action): bool {
		if($keyId < 1 || $table === '') {
			return false;
		}
		$map = $this->mapForKey($keyId);
		if(!isset($map[$table])) {
			return false;
		}

		return !empty($map[$table][$action]);
	}

	/**
	 * @param array<string, array{read?: bool|int, write?: bool|int, edit?: bool|int, delete?: bool|int}> $tables
	 */
	public function replaceForKey(int $keyId, array $tables): void {
		if($keyId < 1) {
			return;
		}

		/** @var list<ApiScope> $existing */
		$existing = $this->select()->where('key_id', $keyId)->fetchAll();
		foreach($existing as $row) {
			$this->deleteEntity($row);
		}

		foreach($tables as $table => $flags) {
			$table  = (string) $table;
			$read   = !empty($flags['read']);
			$write  = !empty($flags['write']);
			$edit   = !empty($flags['edit']);
			$delete = !empty($flags['delete']);
			if($table === '' || (!$read && !$write && !$edit && !$delete)) {
				continue;
			}
			$entity              = new ApiScope();
			$entity->key_id      = $keyId;
			$entity->scope_table = $table;
			$entity->can_read    = $read;
			$entity->can_write   = $write;
			$entity->can_edit    = $edit;
			$entity->can_delete  = $delete;
			$this->saveEntity($entity);
		}
	}

}
