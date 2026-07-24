<?php

declare(strict_types=1);

namespace DleApi\Sdk\Presenter;

/**
 * Презентер новости (post).
 */
final class PostPresenter extends AbstractTablePresenter {
	public function table(): string {
		return 'post';
	}

	public function withTitle(string $title): static {
		return $this->with('title', $title);
	}

	public function withShortStory(string $text): static {
		return $this->with('short_story', $text);
	}

	public function withFullStory(string $text): static {
		return $this->with('full_story', $text);
	}

	/**
	 * @param int|string|list<int|string> $category
	 */
	public function withCategory(int|string|array $category): static {
		if(is_array($category)) {
			$category = implode(',', array_map(static fn($v) => (string) $v, $category));
		}

		return $this->with('category', (string) $category);
	}

	/**
	 * Массив имя→значение → строка DLE `name|val||name2|val2` (с экранированием).
	 *
	 * @param array<string, scalar|null> $fields
	 */
	public function withXfields(array $fields): static {
		return $this->with('xfields', \DleApi\Xfield\XfieldValueEncoder::encode($fields));
	}

	/** @param mixed $images TableBuilder|array|list */
	public function withImages(mixed $images): static {
		return $this->withChild('images', $images);
	}

	/** @param mixed $files TableBuilder|array|list */
	public function withFiles(mixed $files): static {
		return $this->withChild('files', $files);
	}

	/** @param mixed $comments TableBuilder|array|list */
	public function withComments(mixed $comments): static {
		return $this->withChild('comments', $comments);
	}

	/** @param mixed $poll TableBuilder|array|list */
	public function withPoll(mixed $poll): static {
		return $this->withChild('poll', $poll);
	}
}
