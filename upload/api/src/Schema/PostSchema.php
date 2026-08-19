<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `post`.
 */
#[OA\Schema(schema: 'Post')]
final class PostSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (post.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'autor',
		type: 'string',
		description: 'Автор (имя пользователя) (post.autor)',
	)]
	public string $autor = '';
	#[OA\Property(
		property: 'date',
		type: 'string',
		description: 'Дата/время (post.date)',
	)]
	public string $date = '2000-01-01 00:00:00';
	#[OA\Property(
		property: 'short_story',
		type: 'string',
		description: 'Краткое описание (post.short_story)',
	)]
	public string $short_story = '';
	#[OA\Property(
		property: 'full_story',
		type: 'string',
		description: 'Полный текст (post.full_story)',
	)]
	public string $full_story = '';
	#[OA\Property(
		property: 'xfields',
		type: 'string',
		description: 'Доп. поля (xfields) (post.xfields)',
	)]
	public string $xfields = '';
	#[OA\Property(
		property: 'title',
		type: 'string',
		description: 'Заголовок (post.title)',
	)]
	public string $title = '';
	#[OA\Property(
		property: 'descr',
		type: 'string',
		description: 'Описание (post.descr)',
	)]
	public string $descr = '';
	#[OA\Property(
		property: 'keywords',
		type: 'string',
		description: 'Ключевые слова (post.keywords)',
	)]
	public string $keywords = '';
	#[OA\Property(
		property: 'category',
		type: 'string',
		description: 'CSV id категорий (virtual FK csv → category.id)',
		x: ['dle-ref' => 'category.id', 'dle-kind' => 'csv'],
	)]
	public string $category = '';
	#[OA\Property(
		property: 'alt_name',
		type: 'string',
		description: 'ЧПУ-имя (post.alt_name)',
	)]
	public string $alt_name = '';
	#[OA\Property(
		property: 'comm_num',
		type: 'integer',
		description: 'Колонка post.comm_num',
	)]
	public int $comm_num = 0;
	#[OA\Property(
		property: 'allow_comm',
		type: 'integer',
		description: 'Колонка post.allow_comm',
	)]
	public int $allow_comm = 1;
	#[OA\Property(
		property: 'allow_main',
		type: 'integer',
		description: 'Колонка post.allow_main',
	)]
	public int $allow_main = 1;
	#[OA\Property(
		property: 'approve',
		type: 'integer',
		description: 'Одобрено (0/1) (post.approve)',
	)]
	public int $approve = 0;
	#[OA\Property(
		property: 'fixed',
		type: 'integer',
		description: 'Колонка post.fixed',
	)]
	public int $fixed = 0;
	#[OA\Property(
		property: 'allow_br',
		type: 'integer',
		description: 'Колонка post.allow_br',
	)]
	public int $allow_br = 1;
	#[OA\Property(
		property: 'symbol',
		type: 'string',
		description: 'Колонка post.symbol',
	)]
	public string $symbol = '';
	#[OA\Property(
		property: 'tags',
		type: 'string',
		description: 'Колонка post.tags',
	)]
	public string $tags = '';
	#[OA\Property(
		property: 'metatitle',
		type: 'string',
		description: 'Колонка post.metatitle',
	)]
	public string $metatitle = '';

	public function table(): string {
		return 'post';
	}

	protected function columnList(): array {
		return [
			'id',
			'autor',
			'date',
			'short_story',
			'full_story',
			'xfields',
			'title',
			'descr',
			'keywords',
			'category',
			'alt_name',
			'comm_num',
			'allow_comm',
			'allow_main',
			'approve',
			'fixed',
			'allow_br',
			'symbol',
			'tags',
			'metatitle',
		];
	}

	protected function defaultMap(): array {
		return [
			'autor' => '',
			'date' => '2000-01-01 00:00:00',
			'short_story' => '',
			'full_story' => '',
			'xfields' => '',
			'title' => '',
			'descr' => '',
			'keywords' => '',
			'category' => '',
			'alt_name' => '',
			'comm_num' => 0,
			'allow_comm' => 1,
			'allow_main' => 1,
			'approve' => 0,
			'fixed' => 0,
			'allow_br' => 1,
			'symbol' => '',
			'tags' => '',
			'metatitle' => '',
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}

	/** @var array<string, mixed> */
	private array $xfieldMap = [];

	public function withTitle(string $title): static {
		return $this->with('title', $title);
	}

	public function withShortStory(string $text): static {
		return $this->with('short_story', $text);
	}

	public function withFullStory(string $text): static {
		return $this->with('full_story', $text);
	}

	public function withAutor(string $autor): static {
		return $this->with('autor', $autor);
	}

	/**
	 * @param int|string|list<int|string> $category
	 */
	public function withCategory(int|string|array $category): static {
		[$csv, $ids] = self::normalizeCategoryIds($category);
		$this->with('category', $csv);
		$this->replaceExtrasCats($ids);

		return $this;
	}

	/**
	 * @param int|string|list<int|string> $category
	 */
	public function withExtrasCats(int|string|array $category): static {
		return $this->withCategory($category);
	}

	public function withExtras(
		#[\JetBrains\PhpStorm\ExpectedValues(values: [
			'news_read', 'allow_rate', 'rating', 'vote_num', 'votes', 'view_edit', 'disable_index',
			'related_ids', 'access', 'editdate', 'editor', 'reason', 'user_id', 'disable_search',
			'need_pass', 'allow_rss', 'allow_rss_dzen', 'edited_now', 'allowed_country', 'not_allowed_country',
		])]
		string $attrName,
		mixed $value,
	): static {
		$this->extrasEntity()->with($attrName, $value);

		return $this;
	}

	public function withExtrasEntity(PostExtrasSchema $entity): static {
		$this->childEntities['post_extras'] = [$entity];

		return $this;
	}

	public function withPass(
		#[\JetBrains\PhpStorm\ExpectedValues(values: ['password'])]
		string $attrName,
		mixed $value,
	): static {
		$this->passEntity()->with($attrName, $value);

		return $this;
	}

	public function withPassEntity(PostPassSchema $entity): static {
		$this->childEntities['post_pass'] = [$entity];

		return $this;
	}

	public function withXfield(string $fieldName, mixed $value): static {
		$this->xfieldMap[$fieldName] = $value;

		return $this;
	}

	/**
	 * @param array<string, mixed> $fields
	 */
	public function withXfields(array $fields): static {
		$this->xfieldMap = array_merge($this->xfieldMap, $fields);

		return $this;
	}

	public function withImagesEntity(ImagesSchema $entity): static {
		return $this->attachChildEntity('images', $entity);
	}

	public function withFilesEntity(FilesSchema $entity): static {
		return $this->attachChildEntity('files', $entity);
	}

	public function withCommentsEntity(CommentsSchema $entity): static {
		return $this->attachChildEntity('comments', $entity);
	}

	public function withPollEntity(PollSchema $entity): static {
		return $this->attachChildEntity('poll', $entity);
	}

	public function create(): static {
		$this->flushXfields();

		return parent::create();
	}

	public function save(): static {
		$this->flushXfields();

		return parent::save();
	}

	private function flushXfields(): void {
		if($this->xfieldMap === []) {
			return;
		}
		$this->with('xfields', \DleApi\Xfield\XfieldValueEncoder::encode($this->xfieldMap));
	}

	private function extrasEntity(): PostExtrasSchema {
		if(!isset($this->childEntities['post_extras'][0]) || !$this->childEntities['post_extras'][0] instanceof PostExtrasSchema) {
			$this->childEntities['post_extras'] = [new PostExtrasSchema()];
		}

		return $this->childEntities['post_extras'][0];
	}

	private function passEntity(): PostPassSchema {
		if(!isset($this->childEntities['post_pass'][0]) || !$this->childEntities['post_pass'][0] instanceof PostPassSchema) {
			$this->childEntities['post_pass'] = [new PostPassSchema()];
		}

		return $this->childEntities['post_pass'][0];
	}

	/**
	 * @param list<int> $ids
	 */
	private function replaceExtrasCats(array $ids): void {
		$this->childEntities['post_extras_cats'] = [];
		foreach($ids as $id) {
			if($id < 1) {
				continue;
			}
			$cat = new PostExtrasCatsSchema();
			$cat->with('cat_id', $id);
			$this->attachChildEntity('post_extras_cats', $cat);
		}
	}

	/**
	 * @param int|string|list<int|string> $category
	 * @return array{0: string, 1: list<int>}
	 */
	private static function normalizeCategoryIds(int|string|array $category): array {
		if(is_array($category)) {
			$ids = array_values(array_filter(array_map(static fn($v) => (int) $v, $category), static fn(int $v) => $v > 0));

			return [implode(',', $ids), $ids];
		}
		$csv = (string) $category;
		$ids = array_values(array_filter(array_map(static fn($v) => (int) $v, explode(',', $csv)), static fn(int $v) => $v > 0));

		return [$csv, $ids];
	}
}
