<?php

declare(strict_types=1);

namespace DleApi\Http\V2;

/**
 * Разбор query/headers в фильтры для TableQuery.
 */
final class FilterBag {
	private const META = ['limit', 'offset', 'orderby', 'sort'];

	/**
	 * @param array<string, mixed> $query
	 * @param array<string, string> $legacyHeaders lowercase header → value (только для /post/ BC)
	 * @return array{
	 *     where: array<string, string>,
	 *     xf: array<string, string>,
	 *     limit: int,
	 *     offset: int,
	 *     orderby: string,
	 *     sort: string
	 * }
	 */
	public static function parse(array $query, array $legacyHeaders = []): array {
		$limit   = (int) ($query['limit'] ?? 20);
		$offset  = (int) ($query['offset'] ?? 0);
		$orderby = (string) ($query['orderby'] ?? '');
		$sort    = (string) ($query['sort'] ?? 'DESC');

		$where = [];
		$xf    = [];

		foreach($legacyHeaders as $key => $value) {
			if($value === '' || in_array($key, self::META, true)) {
				continue;
			}
			if($key === 'xf' && is_string($value)) {
				continue;
			}
			if(preg_match('/^[a-z0-9_]+$/i', $key)) {
				$where[$key] = $value;
			}
		}

		foreach($query as $key => $value) {
			$lkey = strtolower((string) $key);
			if(in_array($lkey, self::META, true)) {
				continue;
			}
			if($lkey === 'xf' && is_array($value)) {
				foreach($value as $xfName => $xfVal) {
					if(is_scalar($xfVal) || $xfVal === null) {
						$xf[(string) $xfName] = (string) $xfVal;
					}
				}
				continue;
			}
			if(!is_scalar($value) && $value !== null) {
				continue;
			}
			if(!preg_match('/^[a-z0-9_]+$/i', (string) $key)) {
				continue;
			}
			$where[(string) $key] = (string) $value;
		}

		return [
			'where'   => $where,
			'xf'      => $xf,
			'limit'   => $limit,
			'offset'  => $offset,
			'orderby' => $orderby,
			'sort'    => $sort,
		];
	}

	/**
	 * Применяет разобранные фильтры к TableQuery.
	 *
	 * @param array{
	 *     where: array<string, string>,
	 *     xf: array<string, string>,
	 *     limit: int,
	 *     offset: int,
	 *     orderby: string,
	 *     sort: string
	 * } $parsed
	 */
	public static function apply(\DleApi\Fluent\TableQuery $query, array $parsed): \DleApi\Fluent\TableQuery {
		foreach($parsed['where'] as $col => $val) {
			$query->where($col, $val);
		}
		foreach($parsed['xf'] as $name => $val) {
			$query->whereXfield($name, $val);
		}
		if($parsed['orderby'] !== '') {
			$query->orderBy($parsed['orderby'], $parsed['sort']);
		} else {
			$query->orderBy('', $parsed['sort']);
		}

		return $query->limit($parsed['limit'])->offset($parsed['offset']);
	}
}
