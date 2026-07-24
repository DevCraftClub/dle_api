<?php

declare(strict_types=1);

namespace DevCraft\Modules\DleApi\Repositories;

use DevCraft\Core\Abstracts\AbstractRepository;
use DevCraft\Modules\DleApi\Models\ApiKeyRequest;

/**
 * Заявки на API-ключ.
 */
final class ApiKeyRequestRepository extends AbstractRepository {

	/**
	 * @return list<ApiKeyRequest>
	 */
	public function allPending(): array {
		/** @var list<ApiKeyRequest> $rows */
		$rows = $this->select()->where('status', 'pending')->orderBy('id', 'DESC')->fetchAll();

		return $rows;
	}

	/**
	 * @return list<ApiKeyRequest>
	 */
	public function all(): array {
		/** @var list<ApiKeyRequest> $rows */
		$rows = $this->select()->orderBy('id', 'DESC')->fetchAll();

		return $rows;
	}

	public function find(int $id): ?ApiKeyRequest {
		/** @var ApiKeyRequest|null $e */
		$e = $this->findByPK($id);

		return $e;
	}

	public function findPendingByUser(int $userId): ?ApiKeyRequest {
		/** @var ApiKeyRequest|null $e */
		$e = $this->select()
			->where('user_id', $userId)
			->where('status', 'pending')
			->orderBy('id', 'DESC')
			->fetchOne();

		return $e;
	}

	public function create(int $userId, int $accessLevelId, string $message = ''): ApiKeyRequest {
		$e                   = new ApiKeyRequest();
		$e->user_id          = $userId;
		$e->access_level_id  = $accessLevelId;
		$e->status           = 'pending';
		$e->message          = $message !== '' ? $message : null;
		/** @var ApiKeyRequest $saved */
		$saved = $this->saveEntity($e);

		return $saved;
	}

	public function decide(ApiKeyRequest $req, string $status, int $adminId): ApiKeyRequest {
		$req->status     = $status;
		$req->decided_by = $adminId;
		$req->decided_at = new \DateTimeImmutable();
		/** @var ApiKeyRequest $saved */
		$saved = $this->saveEntity($req);

		return $saved;
	}

}
