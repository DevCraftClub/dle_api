<?php

declare(strict_types=1);

namespace DevCraft\Modules\DleApi\Pages;

use DevCraft\Core\Application;
use DevCraft\Core\Abstracts\AbstractPage;
use DevCraft\Modules\DleApi\Models\ApiAccessLevel;
use DevCraft\Modules\DleApi\Models\ApiKeyRequest;
use DevCraft\Modules\DleApi\Repositories\ApiAccessLevelRepository;
use DevCraft\Modules\DleApi\Repositories\ApiKeyRequestRepository;
use DevCraft\Core\Support\DleDataService;

/**
 * Заявки пользователей на API-ключ.
 */
final class KeyRequestsPage extends AbstractPage {

	public function handle(): array {
		$this->addBreadcrumb(__('Заявки на ключ'));

		/** @var ApiKeyRequestRepository $repo */
		$repo = Application::instance()->database()->repository(ApiKeyRequest::class);
		/** @var ApiAccessLevelRepository $levels */
		$levels = Application::instance()->database()->repository(ApiAccessLevel::class);

		$levelNames = [];
		foreach($levels->all() as $lvl) {
			$levelNames[$lvl->id()] = $lvl->name;
		}

		$userNames = [];
		foreach(DleDataService::users() as $row) {
			$id = (int) ($row['user_id'] ?? 0);
			if($id < 1) {
				continue;
			}
			$name = trim((string) ($row['name'] ?? ''));
			$userNames[$id] = $name !== '' ? $name : ('#' . $id);
		}

		$rows = [];
		foreach($repo->all() as $req) {
			$userId = $req->user_id;
			$rows[] = [
				'id'               => $req->id(),
				'user_id'          => $userId,
				'user_label'       => $userId < 1 ? __('гость') : ($userNames[$userId] ?? ('#' . $userId)),
				'access_level_id'  => $req->access_level_id,
				'level_name'       => $levelNames[$req->access_level_id] ?? ('#' . $req->access_level_id),
				'status'           => $req->status,
				'message'          => $req->message,
				'decided_by'       => $req->decided_by,
				'created'          => $req->createdAt()->format('Y-m-d H:i'),
			];
		}

		return [
			'view' => 'dleapi/key_requests.twig',
			'data' => [
				'page_title' => __('Заявки на ключ'),
				'requests'   => $rows,
			],
		];
	}

}
