<?php

declare(strict_types=1);

use DevCraft\Core\Enums\FormLayout;
use DevCraft\Form\FormSchemaBuilder;
use DevCraft\Modules\DleApi\Services\DleApiConfig;
use DevCraft\Modules\DleApi\Services\KeyNotifyDelivery;

$demoLocked = DleApiConfig::isDemoMode();
$requestPmPlaceholders = ['{%user_id%}', '{%level%}', '{%request_id%}', '{%request_url%}', '{%subject%}'];
$decisionPmPlaceholders = ['{%user_id%}', '{%api_key%}', '{%subject%}'];
$denyPmPlaceholders = ['{%user_id%}', '{%subject%}'];
$requestEmailPlaceholders = ['{%site_url%}', '{%user_id%}', '{%level%}', '{%request_id%}', '{%request_url%}', '{%subject%}', '{%username%}'];
$decisionEmailPlaceholders = ['{%site_url%}', '{%user_id%}', '{%api_key%}', '{%subject%}', '{%username%}'];
$placeholdersMetro = static function(array $placeholders, string $title): array {
	return [
		'attrs' => [
			'dleapi-placeholders' => json_encode($placeholders, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
			'dleapi-placeholder-title' => $title,
		],
	];
};

$levelOptions = [0 => '—'];
try {
	/** @var \DevCraft\Modules\DleApi\Repositories\ApiAccessLevelRepository $repo */
	$repo = \DevCraft\Core\Application::instance()->database()->repository(
		\DevCraft\Modules\DleApi\Models\ApiAccessLevel::class,
	);
	foreach($repo->all() as $lvl) {
		$levelOptions[$lvl->id()] = $lvl->name;
	}
} catch(\Throwable) {
}

return FormSchemaBuilder::create('dleapi')
	->layout(FormLayout::TABS)
	->section(__('Безопасность'))
		->select('algo', __('Алгоритм HMAC'))
			->description(__('Алгоритм для генерации API-ключей.'))
			->options([
				'sha256' => 'sha256',
				'sha512' => 'sha512',
				'sha1'   => 'sha1',
			])
			->default('sha256')
		->text('secret', __('Секрет'))
			->description(__('Секретная строка для HMAC. Оставьте пустым для автогенерации при сохранении.'))
			->default('')
		->number('length', __('Длина ключа'))
			->description(__('Длина генерируемого API-ключа.'))
			->default(32)
		->checkbox('secure', __('Маскировать чувствительные поля'))
			->description(
				$demoLocked
					? __('Задано DEMO_MODE в .env — маскирование принудительно включено.')
					: __('Скрывать пароли, IP и хеши в ответах API (CRUD).'),
			)
			->default(true)
	->section(__('OAuth2'))
		->number('token_ttl', __('TTL access token (сек)'))
			->description(__('Время жизни access token в секундах.'))
			->default(3600)
	->section(__('Уровни доступа'))
		->select('default_access_level_id', __('Уровень доступа по умолчанию'))
			->description(__('Используется при самогенерации ключа и если группа DLE не привязана на странице синхронизации.'))
			->options($levelOptions)
			->default(0)
	->section(__('Профиль пользователя'))
		->checkbox('profile_show_field', __('Показывать блок API-ключа в профиле'))
			->description(__('Если выключено — блок виден только при включённой самогенерации / заявке. Ключ берётся из таблицы api_keys.'))
			->default(true)
		->checkbox('profile_allow_generate', __('Разрешить пользователям генерировать ключ'))
			->description(__('Если выключено — кнопка запроса не показывается; ключ выдаёт администратор.'))
			->default(false)
	->section(__('Уведомления о заявках'))
		->checkbox('notify_request_email', __('Уведомлять по почте при заявке на ключ'))
			->default(false)
		->checkbox('notify_request_pm', __('ЛС на сайте при заявке на ключ'))
			->default(false)
		->multi('notify_group_ids', __('Группы DLE'))
			->description(__('Получатели уведомлений о заявках.'))
			->options([])
			->default(['1'])
		->multi('notify_user_ids', __('Пользователи'))
			->description(__('Дополнительные получатели уведомлений о заявках (по user_id).'))
			->options([])
			->default([])
		->text('pm_request_subject', __('PM: тема заявки'))
			->description(__('Тема личного сообщения для администраторов о новой заявке на API-ключ.'))
			->metro($placeholdersMetro($requestPmPlaceholders, __('Плейсхолдеры заявки (PM)')))
			->default(__('Заявка на API-ключ'))
		->textarea('pm_request_body', __('PM: текст заявки'))
			->description(__('Текст личного сообщения администраторам о новой заявке на API-ключ.'))
			->metro([
				'class' => 'ajaxwysiwygeditor dc-dleapi-editor',
				'role'  => false,
			] + $placeholdersMetro($requestPmPlaceholders, __('Плейсхолдеры заявки (PM)')))
			->default(__('Поступила новая заявка на API-ключ.' . PHP_EOL . 'Пользователь: {%user_id%}' . PHP_EOL . 'Уровень доступа: {%level%}' . PHP_EOL . 'Номер заявки: {%request_id%}' . PHP_EOL . 'Открыть заявку: {%request_url%}'))
	->section(__('Уведомления о решении'))
		->checkbox('notify_decision_email', __('Уведомлять по почте об одобрении / отказе'))
			->default(true)
		->checkbox('notify_decision_pm', __('ЛС об одобрении / отказе'))
			->default(true)
		->text('pm_approve_subject', __('PM: тема одобрения'))
			->description(__('Тема личного сообщения пользователю при одобрении API-ключа.'))
			->metro($placeholdersMetro($decisionPmPlaceholders, __('Плейсхолдеры одобрения (PM)')))
			->default(__('API-ключ одобрен'))
		->textarea('pm_approve_body', __('PM: текст одобрения'))
			->description(__('Текст личного сообщения пользователю при одобрении API-ключа.'))
			->metro([
				'class' => 'ajaxwysiwygeditor dc-dleapi-editor',
				'role'  => false,
			] + $placeholdersMetro($decisionPmPlaceholders, __('Плейсхолдеры одобрения (PM)')))
			->default(__('Ваш API-ключ одобрен.' . PHP_EOL . 'Ключ: {%api_key%}'))
		->text('pm_deny_subject', __('PM: тема отказа'))
			->description(__('Тема личного сообщения пользователю при отказе в API-ключе.'))
			->metro($placeholdersMetro($denyPmPlaceholders, __('Плейсхолдеры отказа (PM)')))
			->default(__('API-ключ отклонён'))
		->textarea('pm_deny_body', __('PM: текст отказа'))
			->description(__('Текст личного сообщения пользователю при отказе в API-ключе.'))
			->metro([
				'class' => 'ajaxwysiwygeditor dc-dleapi-editor',
				'role'  => false,
			] + $placeholdersMetro($denyPmPlaceholders, __('Плейсхолдеры отказа (PM)')))
			->default(__('Ваша заявка на API-ключ отклонена.'))
	->section(__('Email-шаблоны'))
		->text('email_request_subject', __('Email: тема заявки'))
			->description(__('Тема письма администраторам о новой заявке на API-ключ.'))
			->metro($placeholdersMetro($requestEmailPlaceholders, __('Плейсхолдеры заявки (email)')))
			->default(KeyNotifyDelivery::defaultRequestSubject())
		->textarea('email_request_body', __('Email: тело заявки'))
			->description(__('HTML или текст письма администраторам о новой заявке на API-ключ.'))
			->metro([
				'class' => 'ajaxwysiwygeditor dc-dleapi-editor',
				'role'  => false,
			] + $placeholdersMetro($requestEmailPlaceholders, __('Плейсхолдеры заявки (email)')))
			->default(KeyNotifyDelivery::defaultRequestEmailTemplate())
		->text('email_approve_subject', __('Email: тема одобрения'))
			->description(__('Тема письма пользователю при одобрении API-ключа.'))
			->metro($placeholdersMetro($decisionEmailPlaceholders, __('Плейсхолдеры решения (email)')))
			->default(KeyNotifyDelivery::defaultApproveSubject())
		->text('email_deny_subject', __('Email: тема отказа'))
			->description(__('Тема письма пользователю при отказе в API-ключе.'))
			->metro($placeholdersMetro($decisionEmailPlaceholders, __('Плейсхолдеры решения (email)')))
			->default(KeyNotifyDelivery::defaultDenySubject())
		->textarea('email_decision_body', __('Email: тело решения'))
			->description(__('HTML или текст письма пользователю при одобрении либо отказе в API-ключе.'))
			->metro([
				'class' => 'ajaxwysiwygeditor dc-dleapi-editor',
				'role'  => false,
			] + $placeholdersMetro($decisionEmailPlaceholders, __('Плейсхолдеры решения (email)')))
			->default(KeyNotifyDelivery::defaultDecisionEmailTemplate())
	->build();
