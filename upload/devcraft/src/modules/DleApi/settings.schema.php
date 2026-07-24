<?php

declare(strict_types=1);

use DevCraft\Core\Enums\FormLayout;
use DevCraft\Form\FormSchemaBuilder;
use DevCraft\Modules\DleApi\Services\DleApiConfig;

$demoLocked = DleApiConfig::isDemoMode();
$textXfields = [];
$xfPath = (defined('ENGINE_DIR') ? ENGINE_DIR : '') . '/data/userxfields.json';
if(is_file($xfPath)) {
	$raw = json_decode((string) file_get_contents($xfPath), true);
	foreach(($raw['fields'] ?? []) as $name => $def) {
		if(($def['type'] ?? '') === 'text') {
			$textXfields[(string) $name] = (string) $name;
		}
	}
}

$levelOptions = [0 => '—'];
try {
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
		->select('profile_xfield', __('Поле xfield для API-ключа'))
			->description(__('Только type=text. Создайте поле вручную: allow_change=0, registration=0, safe_mode=1, private=1.'))
			->options(['' => '—'] + $textXfields)
			->default('')
		->checkbox('profile_show_field', __('Показывать поле на странице пользователя'))
			->description(__('Если выключено — блок появится только после генерации ключа администратором.'))
			->default(true)
		->checkbox('profile_allow_generate', __('Разрешить пользователям генерировать ключ'))
			->description(__('Если выключено — ни поле, ни кнопка запроса не показываются.'))
			->default(false)
	->section(__('Уведомления о заявках'))
		->checkbox('notify_request_email', __('Уведомлять по почте при заявке на ключ'))
			->default(false)
		->checkbox('notify_request_pm', __('ЛС на сайте при заявке на ключ'))
			->default(false)
		->text('notify_group_ids', __('ID групп DLE (через запятую)'))
			->description(__('Получатели уведомлений о заявках.'))
			->default('1')
		->text('notify_user_ids', __('ID пользователей (через запятую)'))
			->default('')
		->text('pm_request_subject', __('PM: тема заявки'))
			->default(__('Заявка на API-ключ'))
		->textarea('pm_request_body', __('PM: текст заявки'))
			->default('')
	->section(__('Уведомления о решении'))
		->checkbox('notify_decision_email', __('Уведомлять по почте об одобрении / отказе'))
			->default(true)
		->checkbox('notify_decision_pm', __('ЛС об одобрении / отказе'))
			->default(true)
		->text('pm_approve_subject', __('PM: тема одобрения'))
			->default(__('API-ключ одобрен'))
		->textarea('pm_approve_body', __('PM: текст одобрения'))
			->default('')
		->text('pm_deny_subject', __('PM: тема отказа'))
			->default(__('API-ключ отклонён'))
		->textarea('pm_deny_body', __('PM: текст отказа'))
			->default('')
	->build();
