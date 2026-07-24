<?php

declare(strict_types=1);

namespace DevCraft\Modules\DleApi\Repositories;

use DevCraft\Core\Abstracts\AbstractRepository;
use DevCraft\Modules\DleApi\Models\OauthClient;

/**
 * Репозиторий OAuth-клиентов.
 */
final class OauthClientRepository extends AbstractRepository {

	/**
	 * @return list<OauthClient>
	 */
	public function all(): array {
		/** @var list<OauthClient> $rows */
		$rows = $this->select()->load('apiKey')->orderBy('id', 'DESC')->fetchAll();

		return $rows;
	}

	/**
	 * @param array<string, mixed> $data
	 */
	public function create(array $data): OauthClient {
		$entity                = new OauthClient();
		$entity->client_id     = (string) $data['client_id'];
		$entity->client_secret = (string) $data['client_secret'];
		$entity->name          = (string) ($data['name'] ?? '');
		$entity->redirect_uri  = isset($data['redirect_uri']) && (string) $data['redirect_uri'] !== ''
			? (string) $data['redirect_uri']
			: null;
		$entity->grant_types   = (string) ($data['grant_types'] ?? 'authorization_code,refresh_token,client_credentials,password');
		$entity->api_key_id    = (int) $data['api_key_id'];
		$entity->active        = array_key_exists('active', $data) ? !empty($data['active']) : true;

		/** @var OauthClient $saved */
		$saved = $this->saveEntity($entity);

		return $saved;
	}

	public function find(int $id): ?OauthClient {
		/** @var OauthClient|null $entity */
		$entity = $this->findByPK($id);

		return $entity;
	}

	public function findActiveByClientId(string $clientId): ?OauthClient {
		if($clientId === '') {
			return null;
		}
		/** @var OauthClient|null $entity */
		$entity = $this->select()
			->where('client_id', $clientId)
			->where('active', true)
			->fetchOne();

		return $entity;
	}

	/**
	 * Обновляет редактируемые поля клиента (без client_id / client_secret).
	 *
	 * @param array{
	 *     name?: string,
	 *     redirect_uri?: string|null,
	 *     api_key_id?: int,
	 *     active?: bool,
	 *     grant_types?: string
	 * } $data
	 */
	public function update(OauthClient $entity, array $data): OauthClient {
		if(array_key_exists('name', $data)) {
			$entity->name = (string) $data['name'];
		}
		if(array_key_exists('redirect_uri', $data)) {
			$uri = trim((string) ($data['redirect_uri'] ?? ''));
			$entity->redirect_uri = $uri !== '' ? $uri : null;
		}
		if(array_key_exists('api_key_id', $data)) {
			$entity->api_key_id = (int) $data['api_key_id'];
		}
		if(array_key_exists('active', $data)) {
			$entity->active = !empty($data['active']);
		}
		if(array_key_exists('grant_types', $data)) {
			$entity->grant_types = (string) $data['grant_types'];
		}

		/** @var OauthClient $saved */
		$saved = $this->saveEntity($entity);

		return $saved;
	}

	/**
	 * Сохраняет новый хеш client_secret.
	 */
	public function rotateSecret(OauthClient $entity, string $hashedSecret): OauthClient {
		$entity->client_secret = $hashedSecret;

		/** @var OauthClient $saved */
		$saved = $this->saveEntity($entity);

		return $saved;
	}

	public function delete(int $id): void {
		$entity = $this->find($id);

		if($entity !== null) {
			$this->deleteEntity($entity);
		}
	}

}
