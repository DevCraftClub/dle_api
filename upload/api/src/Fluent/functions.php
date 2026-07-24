<?php

declare(strict_types=1);

namespace DleApi\Fluent;

/**
 * In-process fluent API (TableBuilder / TableQuery).
 */
function prepare(string $table): TableBuilder {
	return new TableBuilder($table);
}

function query(string $table): TableQuery {
	return TableQuery::of($table);
}
