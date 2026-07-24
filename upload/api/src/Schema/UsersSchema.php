<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `users` (DLE install.php).
 */
#[OA\Schema(schema: 'Users')]
final class UsersSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'email',
		type: 'string',
		description: 'E-mail (users.email)',
	)]
	public string $email = '';
	#[OA\Property(
		property: 'password',
		type: 'string',
		description: 'Хеш пароля (users.password)',
	)]
	public string $password = '';
	#[OA\Property(
		property: 'name',
		type: 'string',
		description: 'Имя (users.name)',
	)]
	public string $name = '';
	#[OA\Property(
		property: 'user_id',
		type: 'integer',
		description: 'ID пользователя (users.user_id)',
	)]
	public int $user_id = 0;
	#[OA\Property(
		property: 'news_num',
		type: 'integer',
		description: 'Колонка users.news_num',
	)]
	public int $news_num = 0;
	#[OA\Property(
		property: 'comm_num',
		type: 'integer',
		description: 'Колонка users.comm_num',
	)]
	public int $comm_num = 0;
	#[OA\Property(
		property: 'user_group',
		type: 'integer',
		description: 'ID группы (users.user_group)',
	)]
	public int $user_group = 4;
	#[OA\Property(
		property: 'lastdate',
		type: 'string',
		description: 'Колонка users.lastdate',
	)]
	public string $lastdate = '';
	#[OA\Property(
		property: 'reg_date',
		type: 'string',
		description: 'Колонка users.reg_date',
	)]
	public string $reg_date = '';
	#[OA\Property(
		property: 'banned',
		type: 'string',
		description: 'Колонка users.banned',
	)]
	public string $banned = '';
	#[OA\Property(
		property: 'allow_mail',
		type: 'integer',
		description: 'Колонка users.allow_mail',
	)]
	public int $allow_mail = 1;
	#[OA\Property(
		property: 'info',
		type: 'string',
		description: 'Колонка users.info',
	)]
	public string $info = '';
	#[OA\Property(
		property: 'signature',
		type: 'string',
		description: 'Колонка users.signature',
	)]
	public string $signature = '';
	#[OA\Property(
		property: 'foto',
		type: 'string',
		description: 'Колонка users.foto',
	)]
	public string $foto = '';
	#[OA\Property(
		property: 'fullname',
		type: 'string',
		description: 'Колонка users.fullname',
	)]
	public string $fullname = '';
	#[OA\Property(
		property: 'land',
		type: 'string',
		description: 'Колонка users.land',
	)]
	public string $land = '';
	#[OA\Property(
		property: 'favorites',
		type: 'string',
		description: 'Колонка users.favorites',
	)]
	public string $favorites = '';
	#[OA\Property(
		property: 'pm_all',
		type: 'integer',
		description: 'Колонка users.pm_all',
	)]
	public int $pm_all = 0;
	#[OA\Property(
		property: 'pm_unread',
		type: 'integer',
		description: 'Колонка users.pm_unread',
	)]
	public int $pm_unread = 0;
	#[OA\Property(
		property: 'time_limit',
		type: 'string',
		description: 'Колонка users.time_limit',
	)]
	public string $time_limit = '';
	#[OA\Property(
		property: 'xfields',
		type: 'string',
		description: 'Доп. поля (xfields) (users.xfields)',
	)]
	public string $xfields = '';
	#[OA\Property(
		property: 'allowed_ip',
		type: 'string',
		description: 'Колонка users.allowed_ip',
	)]
	public string $allowed_ip = '';
	#[OA\Property(
		property: 'hash',
		type: 'string',
		description: 'Колонка users.hash',
	)]
	public string $hash = '';
	#[OA\Property(
		property: 'logged_ip',
		type: 'string',
		description: 'Колонка users.logged_ip',
	)]
	public string $logged_ip = '';
	#[OA\Property(
		property: 'restricted',
		type: 'integer',
		description: 'Колонка users.restricted',
	)]
	public int $restricted = 0;
	#[OA\Property(
		property: 'restricted_days',
		type: 'integer',
		description: 'Колонка users.restricted_days',
	)]
	public int $restricted_days = 0;
	#[OA\Property(
		property: 'restricted_date',
		type: 'string',
		description: 'Колонка users.restricted_date',
	)]
	public string $restricted_date = '';
	#[OA\Property(
		property: 'timezone',
		type: 'string',
		description: 'Колонка users.timezone',
	)]
	public string $timezone = '';
	#[OA\Property(
		property: 'news_subscribe',
		type: 'integer',
		description: 'Колонка users.news_subscribe',
	)]
	public int $news_subscribe = 0;
	#[OA\Property(
		property: 'comments_reply_subscribe',
		type: 'integer',
		description: 'Колонка users.comments_reply_subscribe',
	)]
	public int $comments_reply_subscribe = 0;
	#[OA\Property(
		property: 'twofactor_auth',
		type: 'integer',
		description: 'Колонка users.twofactor_auth',
	)]
	public int $twofactor_auth = 0;
	#[OA\Property(
		property: 'cat_add',
		type: 'string',
		description: 'Колонка users.cat_add',
	)]
	public string $cat_add = '';
	#[OA\Property(
		property: 'cat_allow_addnews',
		type: 'string',
		description: 'Колонка users.cat_allow_addnews',
	)]
	public string $cat_allow_addnews = '';
	#[OA\Property(
		property: 'twofactor_secret',
		type: 'string',
		description: 'Колонка users.twofactor_secret',
	)]
	public string $twofactor_secret = '';

	public function table(): string {
		return 'users';
	}

	protected function columnList(): array {
		return [
			'email',
			'password',
			'name',
			'user_id',
			'news_num',
			'comm_num',
			'user_group',
			'lastdate',
			'reg_date',
			'banned',
			'allow_mail',
			'info',
			'signature',
			'foto',
			'fullname',
			'land',
			'favorites',
			'pm_all',
			'pm_unread',
			'time_limit',
			'xfields',
			'allowed_ip',
			'hash',
			'logged_ip',
			'restricted',
			'restricted_days',
			'restricted_date',
			'timezone',
			'news_subscribe',
			'comments_reply_subscribe',
			'twofactor_auth',
			'cat_add',
			'cat_allow_addnews',
			'twofactor_secret',
		];
	}

	protected function defaultMap(): array {
		return [
			'email' => '',
			'password' => '',
			'name' => '',
			'news_num' => 0,
			'comm_num' => 0,
			'user_group' => 4,
			'lastdate' => '',
			'reg_date' => '',
			'banned' => '',
			'allow_mail' => 1,
			'info' => '',
			'signature' => '',
			'foto' => '',
			'fullname' => '',
			'land' => '',
			'favorites' => '',
			'pm_all' => 0,
			'pm_unread' => 0,
			'time_limit' => '',
			'xfields' => '',
			'allowed_ip' => '',
			'hash' => '',
			'logged_ip' => '',
			'restricted' => 0,
			'restricted_days' => 0,
			'restricted_date' => '',
			'timezone' => '',
			'news_subscribe' => 0,
			'comments_reply_subscribe' => 0,
			'twofactor_auth' => 0,
			'cat_add' => '',
			'cat_allow_addnews' => '',
			'twofactor_secret' => '',
		];
	}

	public function primaryKey(): string|array {
		return 'user_id';
	}

	public function withName(string $name): static {
		return $this->with('name', $name);
	}

	public function withEmail(string $email): static {
		return $this->with('email', $email);
	}

	public function withPassword(string $password): static {
		return $this->with('password', $password);
	}

	public function withUsergroup(int $userGroup): static {
		return $this->with('user_group', $userGroup);
	}
}
