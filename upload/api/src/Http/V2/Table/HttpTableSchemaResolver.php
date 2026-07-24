<?php

declare(strict_types=1);

namespace DleApi\Http\V2\Table;

use DleApi\Schema\IntrospectedTableSchema;
use DleApi\Schema\SchemaRegistry;
use DleApi\Schema\TableSchemaInterface;

/**
 * Резолвер схемы только для HTTP /table/{name} (не патчит SchemaRegistry::get).
 */
final class HttpTableSchemaResolver {
	public function resolve(string $name): ?TableSchemaInterface {
		$name = trim($name);
		if($name === '') {
			return null;
		}
		if(IntrospectedTableSchema::isDenied($name)) {
			return null;
		}
		try {
			return SchemaRegistry::make($name);
		} catch(\Throwable) {
			return IntrospectedTableSchema::tryFromPhysical($name);
		}
	}
}
