<?php

declare(strict_types=1);

namespace DleApi\Fluent;

/**
 * Логические связи таблиц DLE (без MySQL FK) + nested create.
 *
 * Ребро: child.column → parent.column.
 */
final class RelationMap {
	public const KIND_ONE         = 'one';
	public const KIND_CSV         = 'csv';
	public const KIND_CSV_OR_ALL  = 'csv_or_all';
	public const KIND_NAME        = 'name';
	public const KIND_EMAIL       = 'email';
	public const KIND_ENCODED     = 'encoded';
	public const KIND_POLY        = 'poly';
	public const KIND_OPTIONAL0   = 'optional0';
	public const KIND_MIXED       = 'mixed';
	public const KIND_COPY        = 'copy';

	/**
	 * @var list<array{from: string, column: string, to: string, toColumn: string, kind: string}>
	 */
	private const EDGES = [
		// post
		['from' => 'post', 'column' => 'category', 'to' => 'category', 'toColumn' => 'id', 'kind' => self::KIND_CSV],
		['from' => 'post', 'column' => 'autor', 'to' => 'users', 'toColumn' => 'name', 'kind' => self::KIND_NAME],
		['from' => 'post_extras', 'column' => 'news_id', 'to' => 'post', 'toColumn' => 'id', 'kind' => self::KIND_ONE],
		['from' => 'post_extras', 'column' => 'user_id', 'to' => 'users', 'toColumn' => 'user_id', 'kind' => self::KIND_ONE],
		['from' => 'post_extras', 'column' => 'editor', 'to' => 'users', 'toColumn' => 'name', 'kind' => self::KIND_NAME],
		['from' => 'post_extras', 'column' => 'related_ids', 'to' => 'post', 'toColumn' => 'id', 'kind' => self::KIND_CSV],
		['from' => 'post_extras', 'column' => 'access', 'to' => 'usergroups', 'toColumn' => 'id', 'kind' => self::KIND_ENCODED],
		['from' => 'post_extras_cats', 'column' => 'news_id', 'to' => 'post', 'toColumn' => 'id', 'kind' => self::KIND_ONE],
		['from' => 'post_extras_cats', 'column' => 'cat_id', 'to' => 'category', 'toColumn' => 'id', 'kind' => self::KIND_ONE],
		['from' => 'post_pass', 'column' => 'news_id', 'to' => 'post', 'toColumn' => 'id', 'kind' => self::KIND_ONE],
		['from' => 'post_log', 'column' => 'news_id', 'to' => 'post', 'toColumn' => 'id', 'kind' => self::KIND_ONE],
		['from' => 'post_log', 'column' => 'move_cat', 'to' => 'category', 'toColumn' => 'id', 'kind' => self::KIND_CSV],
		['from' => 'files', 'column' => 'news_id', 'to' => 'post', 'toColumn' => 'id', 'kind' => self::KIND_ONE],
		['from' => 'files', 'column' => 'author', 'to' => 'users', 'toColumn' => 'name', 'kind' => self::KIND_NAME],
		['from' => 'files', 'column' => 'driver', 'to' => 'storage', 'toColumn' => 'id', 'kind' => self::KIND_ONE],
		['from' => 'images', 'column' => 'news_id', 'to' => 'post', 'toColumn' => 'id', 'kind' => self::KIND_ONE],
		['from' => 'images', 'column' => 'author', 'to' => 'users', 'toColumn' => 'name', 'kind' => self::KIND_NAME],
		['from' => 'poll', 'column' => 'news_id', 'to' => 'post', 'toColumn' => 'id', 'kind' => self::KIND_ONE],
		['from' => 'poll_log', 'column' => 'news_id', 'to' => 'post', 'toColumn' => 'id', 'kind' => self::KIND_ONE],
		['from' => 'poll_log', 'column' => 'member', 'to' => 'users', 'toColumn' => 'user_id', 'kind' => self::KIND_ONE],
		['from' => 'tags', 'column' => 'news_id', 'to' => 'post', 'toColumn' => 'id', 'kind' => self::KIND_ONE],
		['from' => 'views', 'column' => 'news_id', 'to' => 'post', 'toColumn' => 'id', 'kind' => self::KIND_ONE],
		['from' => 'read_log', 'column' => 'news_id', 'to' => 'post', 'toColumn' => 'id', 'kind' => self::KIND_ONE],
		['from' => 'logs', 'column' => 'news_id', 'to' => 'post', 'toColumn' => 'id', 'kind' => self::KIND_ONE],
		['from' => 'logs', 'column' => 'member', 'to' => 'users', 'toColumn' => 'name', 'kind' => self::KIND_NAME],
		['from' => 'xfsearch', 'column' => 'news_id', 'to' => 'post', 'toColumn' => 'id', 'kind' => self::KIND_ONE],
		['from' => 'subscribe', 'column' => 'news_id', 'to' => 'post', 'toColumn' => 'id', 'kind' => self::KIND_ONE],
		['from' => 'subscribe', 'column' => 'user_id', 'to' => 'users', 'toColumn' => 'user_id', 'kind' => self::KIND_ONE],
		// comments
		['from' => 'comments', 'column' => 'post_id', 'to' => 'post', 'toColumn' => 'id', 'kind' => self::KIND_ONE],
		['from' => 'comments', 'column' => 'user_id', 'to' => 'users', 'toColumn' => 'user_id', 'kind' => self::KIND_OPTIONAL0],
		['from' => 'comments', 'column' => 'autor', 'to' => 'users', 'toColumn' => 'name', 'kind' => self::KIND_NAME],
		['from' => 'comments', 'column' => 'parent', 'to' => 'comments', 'toColumn' => 'id', 'kind' => self::KIND_OPTIONAL0],
		['from' => 'comments_files', 'column' => 'c_id', 'to' => 'comments', 'toColumn' => 'id', 'kind' => self::KIND_OPTIONAL0],
		['from' => 'comments_files', 'column' => 'author', 'to' => 'users', 'toColumn' => 'name', 'kind' => self::KIND_NAME],
		['from' => 'comments_files', 'column' => 'driver', 'to' => 'storage', 'toColumn' => 'id', 'kind' => self::KIND_ONE],
		['from' => 'comment_rating_log', 'column' => 'c_id', 'to' => 'comments', 'toColumn' => 'id', 'kind' => self::KIND_ONE],
		['from' => 'comment_rating_log', 'column' => 'member', 'to' => 'users', 'toColumn' => 'name', 'kind' => self::KIND_NAME],
		// complaint
		['from' => 'complaint', 'column' => 'p_id', 'to' => 'conversations_messages', 'toColumn' => 'id', 'kind' => self::KIND_OPTIONAL0],
		['from' => 'complaint', 'column' => 'c_id', 'to' => 'comments', 'toColumn' => 'id', 'kind' => self::KIND_OPTIONAL0],
		['from' => 'complaint', 'column' => 'n_id', 'to' => 'post', 'toColumn' => 'id', 'kind' => self::KIND_OPTIONAL0],
		['from' => 'complaint', 'column' => 'from', 'to' => 'users', 'toColumn' => 'name', 'kind' => self::KIND_NAME],
		// conversations
		['from' => 'conversations', 'column' => 'sender_id', 'to' => 'users', 'toColumn' => 'user_id', 'kind' => self::KIND_ONE],
		['from' => 'conversations', 'column' => 'recipient_id', 'to' => 'users', 'toColumn' => 'user_id', 'kind' => self::KIND_ONE],
		['from' => 'conversation_users', 'column' => 'conversation_id', 'to' => 'conversations', 'toColumn' => 'id', 'kind' => self::KIND_ONE],
		['from' => 'conversation_users', 'column' => 'user_id', 'to' => 'users', 'toColumn' => 'user_id', 'kind' => self::KIND_ONE],
		['from' => 'conversation_reads', 'column' => 'conversation_id', 'to' => 'conversations', 'toColumn' => 'id', 'kind' => self::KIND_ONE],
		['from' => 'conversation_reads', 'column' => 'user_id', 'to' => 'users', 'toColumn' => 'user_id', 'kind' => self::KIND_ONE],
		['from' => 'conversations_messages', 'column' => 'conversation_id', 'to' => 'conversations', 'toColumn' => 'id', 'kind' => self::KIND_ONE],
		['from' => 'conversations_messages', 'column' => 'sender_id', 'to' => 'users', 'toColumn' => 'user_id', 'kind' => self::KIND_ONE],
		// users
		['from' => 'users', 'column' => 'user_group', 'to' => 'usergroups', 'toColumn' => 'id', 'kind' => self::KIND_ONE],
		['from' => 'users', 'column' => 'cat_add', 'to' => 'category', 'toColumn' => 'id', 'kind' => self::KIND_CSV_OR_ALL],
		['from' => 'users', 'column' => 'cat_allow_addnews', 'to' => 'category', 'toColumn' => 'id', 'kind' => self::KIND_CSV_OR_ALL],
		['from' => 'banned', 'column' => 'users_id', 'to' => 'users', 'toColumn' => 'user_id', 'kind' => self::KIND_OPTIONAL0],
		['from' => 'ignore_list', 'column' => 'user', 'to' => 'users', 'toColumn' => 'user_id', 'kind' => self::KIND_ONE],
		['from' => 'ignore_list', 'column' => 'user_from', 'to' => 'users', 'toColumn' => 'name', 'kind' => self::KIND_NAME],
		['from' => 'social_login', 'column' => 'uid', 'to' => 'users', 'toColumn' => 'user_id', 'kind' => self::KIND_OPTIONAL0],
		['from' => 'twofactor', 'column' => 'user_id', 'to' => 'users', 'toColumn' => 'user_id', 'kind' => self::KIND_ONE],
		['from' => 'notice', 'column' => 'user_id', 'to' => 'users', 'toColumn' => 'user_id', 'kind' => self::KIND_ONE],
		['from' => 'mail_log', 'column' => 'user_id', 'to' => 'users', 'toColumn' => 'user_id', 'kind' => self::KIND_ONE],
		['from' => 'users_delete', 'column' => 'user_id', 'to' => 'users', 'toColumn' => 'user_id', 'kind' => self::KIND_ONE],
		['from' => 'usergroups', 'column' => 'rid', 'to' => 'usergroups', 'toColumn' => 'id', 'kind' => self::KIND_ONE],
		['from' => 'usergroups', 'column' => 'allow_cats', 'to' => 'category', 'toColumn' => 'id', 'kind' => self::KIND_CSV_OR_ALL],
		['from' => 'usergroups', 'column' => 'not_allow_cats', 'to' => 'category', 'toColumn' => 'id', 'kind' => self::KIND_CSV],
		['from' => 'usergroups', 'column' => 'cat_add', 'to' => 'category', 'toColumn' => 'id', 'kind' => self::KIND_CSV_OR_ALL],
		['from' => 'usergroups', 'column' => 'cat_allow_addnews', 'to' => 'category', 'toColumn' => 'id', 'kind' => self::KIND_CSV_OR_ALL],
		['from' => 'usergroups', 'column' => 'force_reg_group', 'to' => 'usergroups', 'toColumn' => 'id', 'kind' => self::KIND_ONE],
		['from' => 'usergroups', 'column' => 'force_news_group', 'to' => 'usergroups', 'toColumn' => 'id', 'kind' => self::KIND_ONE],
		['from' => 'usergroups', 'column' => 'force_comments_group', 'to' => 'usergroups', 'toColumn' => 'id', 'kind' => self::KIND_ONE],
		['from' => 'usergroups', 'column' => 'force_rating_group', 'to' => 'usergroups', 'toColumn' => 'id', 'kind' => self::KIND_ONE],
		['from' => 'usergroups', 'column' => 'force_comments_rating_group', 'to' => 'usergroups', 'toColumn' => 'id', 'kind' => self::KIND_ONE],
		// banners / category
		['from' => 'banners', 'column' => 'category', 'to' => 'category', 'toColumn' => 'id', 'kind' => self::KIND_CSV],
		['from' => 'banners', 'column' => 'grouplevel', 'to' => 'usergroups', 'toColumn' => 'id', 'kind' => self::KIND_CSV_OR_ALL],
		['from' => 'banners', 'column' => 'rubric', 'to' => 'banners_rubrics', 'toColumn' => 'id', 'kind' => self::KIND_ONE],
		['from' => 'banners_logs', 'column' => 'bid', 'to' => 'banners', 'toColumn' => 'id', 'kind' => self::KIND_ONE],
		['from' => 'banners_rubrics', 'column' => 'parentid', 'to' => 'banners_rubrics', 'toColumn' => 'id', 'kind' => self::KIND_OPTIONAL0],
		['from' => 'category', 'column' => 'parentid', 'to' => 'category', 'toColumn' => 'id', 'kind' => self::KIND_OPTIONAL0],
		// vote / rss
		['from' => 'vote', 'column' => 'category', 'to' => 'category', 'toColumn' => 'id', 'kind' => self::KIND_CSV_OR_ALL],
		['from' => 'vote', 'column' => 'grouplevel', 'to' => 'usergroups', 'toColumn' => 'id', 'kind' => self::KIND_CSV_OR_ALL],
		['from' => 'vote_result', 'column' => 'vote_id', 'to' => 'vote', 'toColumn' => 'id', 'kind' => self::KIND_ONE],
		['from' => 'vote_result', 'column' => 'name', 'to' => 'users', 'toColumn' => 'name', 'kind' => self::KIND_NAME],
		['from' => 'rss', 'column' => 'category', 'to' => 'category', 'toColumn' => 'id', 'kind' => self::KIND_ONE],
		['from' => 'rssinform', 'column' => 'category', 'to' => 'category', 'toColumn' => 'id', 'kind' => self::KIND_CSV],
		// plugins / static / downloads / newsletter
		['from' => 'plugins_files', 'column' => 'plugin_id', 'to' => 'plugins', 'toColumn' => 'id', 'kind' => self::KIND_ONE],
		['from' => 'plugins_logs', 'column' => 'plugin_id', 'to' => 'plugins', 'toColumn' => 'id', 'kind' => self::KIND_ONE],
		['from' => 'static', 'column' => 'grouplevel', 'to' => 'usergroups', 'toColumn' => 'id', 'kind' => self::KIND_CSV_OR_ALL],
		['from' => 'static_files', 'column' => 'static_id', 'to' => 'static', 'toColumn' => 'id', 'kind' => self::KIND_OPTIONAL0],
		['from' => 'static_files', 'column' => 'author', 'to' => 'users', 'toColumn' => 'name', 'kind' => self::KIND_NAME],
		['from' => 'static_files', 'column' => 'driver', 'to' => 'storage', 'toColumn' => 'id', 'kind' => self::KIND_ONE],
		['from' => 'downloads_log', 'column' => 'user_id', 'to' => 'users', 'toColumn' => 'user_id', 'kind' => self::KIND_OPTIONAL0],
		['from' => 'downloads_log', 'column' => 'file_id', 'to' => 'files', 'toColumn' => 'id', 'kind' => self::KIND_POLY],
		['from' => 'newsletter_template_items', 'column' => 'category_id', 'to' => 'newsletter_template_categories', 'toColumn' => 'id', 'kind' => self::KIND_ONE],
		['from' => 'newsletter_template_items', 'column' => 'created_by', 'to' => 'users', 'toColumn' => 'user_id', 'kind' => self::KIND_ONE],
		['from' => 'newsletter_template_categories', 'column' => 'created_by', 'to' => 'users', 'toColumn' => 'user_id', 'kind' => self::KIND_ONE],
	];

	/**
	 * @return list<array{from: string, column: string, to: string, toColumn: string, kind: string}>
	 */
	public static function edges(): array {
		return self::EDGES;
	}

	/**
	 * Виртуальный FK: ребро from.column → to.toColumn.
	 *
	 * @return array{from: string, column: string, to: string, toColumn: string, kind: string}|null
	 */
	public static function edge(string $from, string $column): ?array {
		foreach(self::EDGES as $edge) {
			if($edge['from'] === $from && $edge['column'] === $column) {
				return $edge;
			}
		}

		return null;
	}

	/**
	 * Исходящие виртуальные FK таблицы (колонки → другие таблицы).
	 *
	 * @return list<array{from: string, column: string, to: string, toColumn: string, kind: string}>
	 */
	public static function edgesFrom(string $from): array {
		$out = [];
		foreach(self::EDGES as $edge) {
			if($edge['from'] === $from) {
				$out[] = $edge;
			}
		}

		return $out;
	}

	/**
	 * FK-колонка дочерней таблицы на родителя (для nested create).
	 * Self-ref: предпочитает parentid / parent.
	 */
	public static function nestedFk(string $parentTable, string $childTable): ?string {
		$candidates = [];
		foreach(self::EDGES as $edge) {
			if($edge['from'] !== $childTable || $edge['to'] !== $parentTable) {
				continue;
			}
			if(!in_array($edge['kind'], [self::KIND_ONE, self::KIND_OPTIONAL0], true)) {
				continue;
			}
			$candidates[] = $edge;
		}
		if($candidates === []) {
			return null;
		}
		if($parentTable === $childTable) {
			foreach($candidates as $edge) {
				if(in_array($edge['column'], ['parentid', 'parent'], true)) {
					return $edge['column'];
				}
			}
		}
		// для post←comments: post_id; post←images: news_id
		return $candidates[0]['column'];
	}

	/**
	 * @return list<string> имена дочерних таблиц, которые можно вложить в parent
	 */
	public static function childrenOf(string $parentTable): array {
		$out = [];
		foreach(self::EDGES as $edge) {
			if($edge['to'] !== $parentTable) {
				continue;
			}
			if(!in_array($edge['kind'], [self::KIND_ONE, self::KIND_OPTIONAL0], true)) {
				continue;
			}
			$out[$edge['from']] = true;
		}

		return array_keys($out);
	}
}
