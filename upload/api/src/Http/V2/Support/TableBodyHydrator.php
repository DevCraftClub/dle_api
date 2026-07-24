<?php

declare(strict_types=1);

namespace DleApi\Http\V2\Support;

use DleApi\Fluent\RelationMap;
use DleApi\Fluent\TableBuilder;
use DleApi\Schema\SchemaRegistry;
use function DleApi\Fluent\prepare;

/**
 * Собирает TableBuilder из JSON-тела (колонки + nested child tables).
 */
final class TableBodyHydrator {

	/**
	 * @param array<string, mixed> $body
	 */
	public function hydrate(string $table, array $body): TableBuilder {
		$builder  = prepare($table);
		$columns  = SchemaRegistry::get($table)->columns();
		$children = RelationMap::childrenOf($table);
		$attrs    = [];

		foreach($body as $key => $value) {
			if(in_array($key, $children, true) && is_array($value)) {
				$isList = $value === [] || array_keys($value) === range(0, count($value) - 1);
				if($isList) {
					$list = [];
					foreach($value as $item) {
						$list[] = is_array($item)
							? $this->hydrate($key, $item)
							: prepare($key);
					}
					$builder->with($key, $list);
				} else {
					$builder->with($key, $this->hydrate($key, $value));
				}
				continue;
			}
			if(in_array($key, $columns, true)) {
				if(is_array($value) && array_is_list($value)) {
					$value = implode(',', array_map(static fn($v) => (string) $v, $value));
				}
				$attrs[$key] = $value;
			}
		}
		if(isset($body['attributes']) && is_array($body['attributes'])) {
			$attrs = array_merge($attrs, $body['attributes']);
		}
		if($attrs !== []) {
			$builder->withAttributes($attrs);
		}

		return $builder;
	}

}
