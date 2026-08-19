<<<<<<< New base: Update README.md
<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `comments`.
 */
#[OA\Schema(schema: 'Comments')]
final class CommentsSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (comments.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'post_id',
		type: 'integer',
		description: 'ID новости (comments.post_id)',
	)]
	public int $post_id = 0;
	#[OA\Property(
		property: 'user_id',
		type: 'integer',
		description: 'ID пользователя (comments.user_id)',
	)]
	public int $user_id = 0;
	#[OA\Property(
		property: 'date',
		type: 'string',
		description: 'Дата/время (comments.date)',
	)]
	public string $date = '2000-01-01 00:00:00';
	#[OA\Property(
		property: 'autor',
		type: 'string',
		description: 'Автор (имя пользователя) (comments.autor)',
	)]
	public string $autor = '';
	#[OA\Property(
		property: 'email',
		type: 'string',
		description: 'E-mail (comments.email)',
	)]
	public string $email = '';
	#[OA\Property(
		property: 'text',
		type: 'string',
		description: 'Колонка comments.text',
	)]
	public string $text = '';
	#[OA\Property(
		property: 'ip',
		type: 'string',
		description: 'IP-адрес (comments.ip)',
	)]
	public string $ip = '';
	#[OA\Property(
		property: 'is_register',
		type: 'integer',
		description: 'Колонка comments.is_register',
	)]
	public int $is_register = 0;
	#[OA\Property(
		property: 'approve',
		type: 'integer',
		description: 'Одобрено (0/1) (comments.approve)',
	)]
	public int $approve = 1;
	#[OA\Property(
		property: 'rating',
		type: 'integer',
		description: 'Колонка comments.rating',
	)]
	public int $rating = 0;
	#[OA\Property(
		property: 'vote_num',
		type: 'integer',
		description: 'Колонка comments.vote_num',
	)]
	public int $vote_num = 0;
	#[OA\Property(
		property: 'parent',
		type: 'integer',
		description: 'ID родителя (comments.parent)',
	)]
	public int $parent = 0;

	public function table(): string {
		return 'comments';
	}

	protected function columnList(): array {
		return [
			'id',
			'post_id',
			'user_id',
			'date',
			'autor',
			'email',
			'text',
			'ip',
			'is_register',
			'approve',
			'rating',
			'vote_num',
			'parent',
		];
	}

	protected function defaultMap(): array {
		return [
			'post_id' => 0,
			'user_id' => 0,
			'date' => '2000-01-01 00:00:00',
			'autor' => '',
			'email' => '',
			'text' => '',
			'ip' => '',
			'is_register' => 0,
			'approve' => 1,
			'rating' => 0,
			'vote_num' => 0,
			'parent' => 0,
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}

	public function withPostId(int $postId): static {
		return $this->with('post_id', $postId);
	}

	public function withUserId(int $userId): static {
		return $this->with('user_id', $userId);
	}

	public function withAutor(string $autor): static {
		return $this->with('autor', $autor);
	}

	public function withText(string $text): static {
		return $this->with('text', $text);
	}

	public function withParent(int $parentId): static {
		return $this->with('parent', $parentId);
	}

	public function withFilesEntity(CommentsFilesSchema $entity): static {
		return $this->attachChildEntity('comments_files', $entity);
	}

	public function withRatingEntity(CommentRatingLogSchema $entity): static {
		return $this->attachChildEntity('comment_rating_log', $entity);
	}
}
|||||||
=======
<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `comments`.
 */
#[OA\Schema(schema: 'Comments')]
final class CommentsSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (comments.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'post_id',
		type: 'integer',
		description: 'ID новости (comments.post_id)',
	)]
	public int $post_id = 0;
	#[OA\Property(
		property: 'user_id',
		type: 'integer',
		description: 'ID пользователя (comments.user_id)',
	)]
	public int $user_id = 0;
	#[OA\Property(
		property: 'date',
		type: 'string',
		description: 'Дата/время (comments.date)',
	)]
	public string $date = '2000-01-01 00:00:00';
	#[OA\Property(
		property: 'autor',
		type: 'string',
		description: 'Автор (имя пользователя) (comments.autor)',
	)]
	public string $autor = '';
	#[OA\Property(
		property: 'email',
		type: 'string',
		description: 'E-mail (comments.email)',
	)]
	public string $email = '';
	#[OA\Property(
		property: 'text',
		type: 'string',
		description: 'Колонка comments.text',
	)]
	public string $text = '';
	#[OA\Property(
		property: 'ip',
		type: 'string',
		description: 'IP-адрес (comments.ip)',
	)]
	public string $ip = '';
	#[OA\Property(
		property: 'is_register',
		type: 'integer',
		description: 'Колонка comments.is_register',
	)]
	public int $is_register = 0;
	#[OA\Property(
		property: 'approve',
		type: 'integer',
		description: 'Одобрено (0/1) (comments.approve)',
	)]
	public int $approve = 1;
	#[OA\Property(
		property: 'rating',
		type: 'integer',
		description: 'Колонка comments.rating',
	)]
	public int $rating = 0;
	#[OA\Property(
		property: 'vote_num',
		type: 'integer',
		description: 'Колонка comments.vote_num',
	)]
	public int $vote_num = 0;
	#[OA\Property(
		property: 'parent',
		type: 'integer',
		description: 'ID родителя (comments.parent)',
	)]
	public int $parent = 0;

	public function table(): string {
		return 'comments';
	}

	protected function columnList(): array {
		return [
			'id',
			'post_id',
			'user_id',
			'date',
			'autor',
			'email',
			'text',
			'ip',
			'is_register',
			'approve',
			'rating',
			'vote_num',
			'parent',
		];
	}

	protected function defaultMap(): array {
		return [
			'post_id' => 0,
			'user_id' => 0,
			'date' => '2000-01-01 00:00:00',
			'autor' => '',
			'email' => '',
			'text' => '',
			'ip' => '',
			'is_register' => 0,
			'approve' => 1,
			'rating' => 0,
			'vote_num' => 0,
			'parent' => 0,
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}

	public function withPostId(int $postId): static {
		return $this->with('post_id', $postId);
	}

	public function withUserId(int $userId): static {
		return $this->with('user_id', $userId);
	}

	public function withAutor(string $autor): static {
		return $this->with('autor', $autor);
	}

	public function withText(string $text): static {
		return $this->with('text', $text);
	}

	public function withParent(int $parentId): static {
		return $this->with('parent', $parentId);
	}

	public function withFilesEntity(CommentsFilesSchema $entity): static {
		return $this->attachChildEntity('comments_files', $entity);
	}

	public function withRatingEntity(CommentRatingLogSchema $entity): static {
		return $this->attachChildEntity('comment_rating_log', $entity);
	}
}
>>>>>>> Current commit: Начало обновления до api v2
