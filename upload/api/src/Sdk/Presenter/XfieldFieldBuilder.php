<?php

declare(strict_types=1);

namespace DleApi\Sdk\Presenter;

use DleApi\Xfield\XfieldDefinitionNormalizer;
use DleApi\Xfield\XfieldStore;
use DleApi\Xfield\XfieldTypeSpec;
use DleApi\Xfield\XfieldValidationException;
use DleApi\Xfield\XfieldValueEncoder;

/**
 * Fluent-билдер одного доп. поля каталога.
 */
final class XfieldFieldBuilder {
	private string $scope = 'post';

	private ?string $assertType = null;

	/** @var array<string, mixed> */
	private array $attrs = [];

	public function __construct(
		private string $name,
		string $description = '',
	) {
		$this->attrs['name'] = $name;
		if($description !== '') {
			$this->attrs['description'] = $description;
		}
	}

	public function scope(string $scope): self {
		$this->scope = $scope;

		return $this;
	}

	public function asText(): self { return $this->as('text'); }

	public function asTextarea(): self { return $this->as('textarea'); }

	public function asHtmljs(): self { return $this->as('htmljs'); }

	public function asSelect(): self { return $this->as('select'); }

	public function asImage(): self { return $this->as('image'); }

	public function asImagegalery(): self { return $this->as('imagegalery'); }

	public function asVideo(): self { return $this->as('video'); }

	public function asAudio(): self { return $this->as('audio'); }

	public function asFile(): self { return $this->as('file'); }

	public function asYesorno(): self { return $this->as('yesorno'); }

	public function asDatetime(): self { return $this->as('datetime'); }

	private function as(string $type): self {
		$this->assertType     = $type;
		$this->attrs['type']  = $type;

		return $this;
	}

	public function withGroup(string $group): self {
		$this->attrs['group'] = $group;

		return $this;
	}

	public function withHint(string $hint): self {
		$this->attrs['hint'] = $hint;

		return $this;
	}

	public function withCategory(string|array $category): self {
		$this->attrs['category'] = is_array($category) ? implode(',', $category) : $category;

		return $this;
	}

	public function withDefaultValue(mixed $value): self {
		$this->attrs['default'] = $value;

		return $this;
	}

	public function withNotRequired(bool $v = true): self {
		$this->attrs['not_required'] = (int) $v;

		return $this;
	}

	public function withAllowInNews(bool $v = true): self {
		$this->attrs['allow_in_news'] = (int) $v;

		return $this;
	}

	public function withMin(int|string $min): self {
		$this->attrs['min'] = $min;

		return $this;
	}

	public function withMax(int|string $max): self {
		$this->attrs['max'] = $max;

		return $this;
	}

	public function withSafeMode(bool $v = true): self {
		$this->attrs['safe_mode'] = (int) $v;

		return $this;
	}

	public function withUseEditor(bool $v = true): self {
		$this->attrs['use_editor'] = (int) $v;

		return $this;
	}

	public function withUseAsLinks(bool $v = true): self {
		$this->attrs['use_as_links'] = (int) $v;

		return $this;
	}

	public function withAllowMulti(bool $v = true): self {
		$this->attrs['allow_multi'] = (int) $v;

		return $this;
	}

	public function withImageSizes(string $sizes): self {
		$this->attrs['image_sizes'] = $sizes;

		return $this;
	}

	public function withMakeThumb(bool $v = true): self {
		$this->attrs['make_thumb'] = (int) $v;

		return $this;
	}

	public function withMakeWatermark(bool $v = true): self {
		$this->attrs['make_watermark'] = (int) $v;

		return $this;
	}

	public function withFilesExt(string $ext): self {
		$this->attrs['files_ext'] = $ext;

		return $this;
	}

	public function withFileMaxSize(int|string $size): self {
		$this->attrs['file_max_size'] = $size;

		return $this;
	}

	public function withDateFormat(int|string $format): self {
		$this->attrs['date_format'] = $format;

		return $this;
	}

	public function withCondition(int|string $condition): self {
		$this->attrs['condition'] = $condition;

		return $this;
	}

	public function withAllowAddUsergroups(string|array $groups): self {
		$this->attrs['allow_add_usergroups'] = is_array($groups) ? implode(',', $groups) : $groups;

		return $this;
	}

	public function withAllowViewUsergroups(string|array $groups): self {
		$this->attrs['allow_view_usergroups'] = is_array($groups) ? implode(',', $groups) : $groups;

		return $this;
	}

	/**
	 * @return array<string, mixed>
	 */
	public function get(): array {
		$store = new XfieldStore($this->scope);
		$field = $store->getField($this->name);
		if($field === null) {
			throw new XfieldValidationException('Поле не найдено', ['name' => 'Поле не найдено']);
		}
		$type = (string) ($field['type'] ?? '');
		if($this->assertType !== null) {
			if($type !== $this->assertType) {
				throw new XfieldValidationException(
					'Тип поля не совпадает',
					['type' => "Ожидался {$this->assertType}, в каталоге «{$type}»"],
				);
			}

			return XfieldTypeSpec::project($field, $this->assertType, $this->scope);
		}

		return $field;
	}

	public function create(): self {
		$store = new XfieldStore($this->scope);
		$norm  = new XfieldDefinitionNormalizer($store);
		$def   = $norm->normalize($this->attrs, requireUniqueName: true);
		$store->upsertField((string) $def['name'], $def);

		return $this;
	}

	public function modify(): self {
		$store = new XfieldStore($this->scope);
		$cur   = $store->getField($this->name);
		if($cur === null) {
			throw new XfieldValidationException('Поле не найдено', ['name' => 'Поле не найдено']);
		}
		$merged = array_merge($cur, $this->attrs, ['name' => $this->name]);
		$norm   = new XfieldDefinitionNormalizer($store);
		$def    = $norm->normalize($merged, requireUniqueName: false);
		$store->upsertField($this->name, $def);

		return $this;
	}

	public function delete(): self {
		$store = new XfieldStore($this->scope);
		if($store->getField($this->name) === null) {
			throw new XfieldValidationException('Поле не найдено', ['name' => 'Поле не найдено']);
		}
		$store->deleteField($this->name);

		return $this;
	}

	/**
	 * Строка фрагмента для post.xfields / users.xfields.
	 */
	public function forPost(mixed $value): string {
		$type = $this->assertType;
		if($type === null) {
			$store = new XfieldStore($this->scope);
			$field = $store->getField($this->name);
			if($field === null) {
				throw new XfieldValidationException(
					'Тип поля неизвестен: укажите as*() или создайте поле в каталоге',
					['type' => 'Нужен as*() или существующее поле'],
				);
			}
			$type = (string) ($field['type'] ?? 'text');
			if($this->assertType !== null && $type !== $this->assertType) {
				throw new XfieldValidationException(
					'Тип поля не совпадает',
					['type' => "Ожидался {$this->assertType}, в каталоге «{$type}»"],
				);
			}
		}

		return XfieldValueEncoder::encodePair(
			$this->name,
			XfieldValueEncoder::prepareValue($type, $value),
		);
	}
}
