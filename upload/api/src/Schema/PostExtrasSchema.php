<<<<<<< New base: Update README.md
<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `post_extras`.
 */
#[OA\Schema(schema: 'PostExtras')]
final class PostExtrasSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'eid',
		type: 'integer',
		description: 'Колонка post_extras.eid',
	)]
	public int $eid = 0;
	#[OA\Property(
		property: 'news_id',
		type: 'integer',
		description: 'ID новости (post_extras.news_id)',
	)]
	public int $news_id = 0;
	#[OA\Property(
		property: 'news_read',
		type: 'integer',
		description: 'Колонка post_extras.news_read',
	)]
	public int $news_read = 0;
	#[OA\Property(
		property: 'allow_rate',
		type: 'integer',
		description: 'Колонка post_extras.allow_rate',
	)]
	public int $allow_rate = 1;
	#[OA\Property(
		property: 'rating',
		type: 'integer',
		description: 'Колонка post_extras.rating',
	)]
	public int $rating = 0;
	#[OA\Property(
		property: 'vote_num',
		type: 'integer',
		description: 'Колонка post_extras.vote_num',
	)]
	public int $vote_num = 0;
	#[OA\Property(
		property: 'votes',
		type: 'integer',
		description: 'Колонка post_extras.votes',
	)]
	public int $votes = 0;
	#[OA\Property(
		property: 'view_edit',
		type: 'integer',
		description: 'Колонка post_extras.view_edit',
	)]
	public int $view_edit = 0;
	#[OA\Property(
		property: 'disable_index',
		type: 'integer',
		description: 'Колонка post_extras.disable_index',
	)]
	public int $disable_index = 0;
	#[OA\Property(
		property: 'related_ids',
		type: 'string',
		description: 'Колонка post_extras.related_ids',
	)]
	public string $related_ids = '';
	#[OA\Property(
		property: 'access',
		type: 'string',
		description: 'CSV id или all (таблица post_extras.access)',
	)]
	public string $access = '';
	#[OA\Property(
		property: 'editdate',
		type: 'integer',
		description: 'Колонка post_extras.editdate',
	)]
	public int $editdate = 0;
	#[OA\Property(
		property: 'editor',
		type: 'string',
		description: 'Колонка post_extras.editor',
	)]
	public string $editor = '';
	#[OA\Property(
		property: 'reason',
		type: 'string',
		description: 'Колонка post_extras.reason',
	)]
	public string $reason = '';
	#[OA\Property(
		property: 'user_id',
		type: 'integer',
		description: 'ID пользователя (post_extras.user_id)',
	)]
	public int $user_id = 0;
	#[OA\Property(
		property: 'disable_search',
		type: 'integer',
		description: 'Колонка post_extras.disable_search',
	)]
	public int $disable_search = 0;
	#[OA\Property(
		property: 'need_pass',
		type: 'integer',
		description: 'Колонка post_extras.need_pass',
	)]
	public int $need_pass = 0;
	#[OA\Property(
		property: 'allow_rss',
		type: 'integer',
		description: 'Колонка post_extras.allow_rss',
	)]
	public int $allow_rss = 1;
	#[OA\Property(
		property: 'allow_rss_dzen',
		type: 'integer',
		description: 'Колонка post_extras.allow_rss_dzen',
	)]
	public int $allow_rss_dzen = 1;
	#[OA\Property(
		property: 'edited_now',
		type: 'string',
		description: 'Колонка post_extras.edited_now',
	)]
	public string $edited_now = '';
	#[OA\Property(
		property: 'allowed_country',
		type: 'string',
		description: 'Колонка post_extras.allowed_country',
	)]
	public string $allowed_country = '';
	#[OA\Property(
		property: 'not_allowed_country',
		type: 'string',
		description: 'Колонка post_extras.not_allowed_country',
	)]
	public string $not_allowed_country = '';

	public function table(): string {
		return 'post_extras';
	}

	protected function columnList(): array {
		return [
			'eid',
			'news_id',
			'news_read',
			'allow_rate',
			'rating',
			'vote_num',
			'votes',
			'view_edit',
			'disable_index',
			'related_ids',
			'access',
			'editdate',
			'editor',
			'reason',
			'user_id',
			'disable_search',
			'need_pass',
			'allow_rss',
			'allow_rss_dzen',
			'edited_now',
			'allowed_country',
			'not_allowed_country',
		];
	}

	protected function defaultMap(): array {
		return [
			'news_id' => 0,
			'news_read' => 0,
			'allow_rate' => 1,
			'rating' => 0,
			'vote_num' => 0,
			'votes' => 0,
			'view_edit' => 0,
			'disable_index' => 0,
			'related_ids' => '',
			'access' => '',
			'editdate' => 0,
			'editor' => '',
			'reason' => '',
			'user_id' => 0,
			'disable_search' => 0,
			'need_pass' => 0,
			'allow_rss' => 1,
			'allow_rss_dzen' => 1,
			'edited_now' => '',
			'allowed_country' => '',
			'not_allowed_country' => '',
		];
	}

	public function primaryKey(): string|array {
		return 'eid';
	}
}
|||||||
=======
<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `post_extras`.
 */
#[OA\Schema(schema: 'PostExtras')]
final class PostExtrasSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'eid',
		type: 'integer',
		description: 'Колонка post_extras.eid',
	)]
	public int $eid = 0;
	#[OA\Property(
		property: 'news_id',
		type: 'integer',
		description: 'ID новости (post_extras.news_id)',
	)]
	public int $news_id = 0;
	#[OA\Property(
		property: 'news_read',
		type: 'integer',
		description: 'Колонка post_extras.news_read',
	)]
	public int $news_read = 0;
	#[OA\Property(
		property: 'allow_rate',
		type: 'integer',
		description: 'Колонка post_extras.allow_rate',
	)]
	public int $allow_rate = 1;
	#[OA\Property(
		property: 'rating',
		type: 'integer',
		description: 'Колонка post_extras.rating',
	)]
	public int $rating = 0;
	#[OA\Property(
		property: 'vote_num',
		type: 'integer',
		description: 'Колонка post_extras.vote_num',
	)]
	public int $vote_num = 0;
	#[OA\Property(
		property: 'votes',
		type: 'integer',
		description: 'Колонка post_extras.votes',
	)]
	public int $votes = 0;
	#[OA\Property(
		property: 'view_edit',
		type: 'integer',
		description: 'Колонка post_extras.view_edit',
	)]
	public int $view_edit = 0;
	#[OA\Property(
		property: 'disable_index',
		type: 'integer',
		description: 'Колонка post_extras.disable_index',
	)]
	public int $disable_index = 0;
	#[OA\Property(
		property: 'related_ids',
		type: 'string',
		description: 'Колонка post_extras.related_ids',
	)]
	public string $related_ids = '';
	#[OA\Property(
		property: 'access',
		type: 'string',
		description: 'CSV id или all (таблица post_extras.access)',
	)]
	public string $access = '';
	#[OA\Property(
		property: 'editdate',
		type: 'integer',
		description: 'Колонка post_extras.editdate',
	)]
	public int $editdate = 0;
	#[OA\Property(
		property: 'editor',
		type: 'string',
		description: 'Колонка post_extras.editor',
	)]
	public string $editor = '';
	#[OA\Property(
		property: 'reason',
		type: 'string',
		description: 'Колонка post_extras.reason',
	)]
	public string $reason = '';
	#[OA\Property(
		property: 'user_id',
		type: 'integer',
		description: 'ID пользователя (post_extras.user_id)',
	)]
	public int $user_id = 0;
	#[OA\Property(
		property: 'disable_search',
		type: 'integer',
		description: 'Колонка post_extras.disable_search',
	)]
	public int $disable_search = 0;
	#[OA\Property(
		property: 'need_pass',
		type: 'integer',
		description: 'Колонка post_extras.need_pass',
	)]
	public int $need_pass = 0;
	#[OA\Property(
		property: 'allow_rss',
		type: 'integer',
		description: 'Колонка post_extras.allow_rss',
	)]
	public int $allow_rss = 1;
	#[OA\Property(
		property: 'allow_rss_dzen',
		type: 'integer',
		description: 'Колонка post_extras.allow_rss_dzen',
	)]
	public int $allow_rss_dzen = 1;
	#[OA\Property(
		property: 'edited_now',
		type: 'string',
		description: 'Колонка post_extras.edited_now',
	)]
	public string $edited_now = '';
	#[OA\Property(
		property: 'allowed_country',
		type: 'string',
		description: 'Колонка post_extras.allowed_country',
	)]
	public string $allowed_country = '';
	#[OA\Property(
		property: 'not_allowed_country',
		type: 'string',
		description: 'Колонка post_extras.not_allowed_country',
	)]
	public string $not_allowed_country = '';

	public function table(): string {
		return 'post_extras';
	}

	protected function columnList(): array {
		return [
			'eid',
			'news_id',
			'news_read',
			'allow_rate',
			'rating',
			'vote_num',
			'votes',
			'view_edit',
			'disable_index',
			'related_ids',
			'access',
			'editdate',
			'editor',
			'reason',
			'user_id',
			'disable_search',
			'need_pass',
			'allow_rss',
			'allow_rss_dzen',
			'edited_now',
			'allowed_country',
			'not_allowed_country',
		];
	}

	protected function defaultMap(): array {
		return [
			'news_id' => 0,
			'news_read' => 0,
			'allow_rate' => 1,
			'rating' => 0,
			'vote_num' => 0,
			'votes' => 0,
			'view_edit' => 0,
			'disable_index' => 0,
			'related_ids' => '',
			'access' => '',
			'editdate' => 0,
			'editor' => '',
			'reason' => '',
			'user_id' => 0,
			'disable_search' => 0,
			'need_pass' => 0,
			'allow_rss' => 1,
			'allow_rss_dzen' => 1,
			'edited_now' => '',
			'allowed_country' => '',
			'not_allowed_country' => '',
		];
	}

	public function primaryKey(): string|array {
		return 'eid';
	}
}
>>>>>>> Current commit: Начало обновления до api v2
