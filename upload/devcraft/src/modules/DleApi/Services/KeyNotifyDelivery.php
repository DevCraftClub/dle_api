<?php

declare(strict_types=1);

namespace DevCraft\Modules\DleApi\Services;

use DevCraft\Core\Application;
use DevCraft\Builders\QueryBuilder;
use DevCraft\Core\Support\DleDataService;
use DLEPlugins;
use Throwable;

/**
 * Email (шаблоны _email) и PM (settings) для заявок на ключ.
 */
final class KeyNotifyDelivery {

	public const REQUEST_TEMPLATE_NAME  = 'dleapi_key_request';
	public const DECISION_TEMPLATE_NAME = 'dleapi_key_decision';

	public static function defaultRequestSubject(): string {
		return __('Заявка на API-ключ');
	}

	public static function defaultApproveSubject(): string {
		return __('API-ключ одобрен');
	}

	public static function defaultDenySubject(): string {
		return __('API-ключ отклонён');
	}

	public static function defaultRequestEmailTemplate(): string {
		return <<<'HTML'
{%username%},<br><br>
Это письмо отправлено с сайта <a href="{%site_url%}">{%site_url%}</a>.<br><br>
Поступила новая заявка на API-ключ.<br>
Пользователь (ID): {%user_id%}<br>
Уровень доступа: {%level%}<br>
Номер заявки: #{%request_id%}<br><br>
<a href="{%request_url%}">{%request_url%}</a>
HTML;
	}

	public static function defaultDecisionEmailTemplate(): string {
		return <<<'HTML'
{%username%},<br><br>
Это письмо отправлено с сайта <a href="{%site_url%}">{%site_url%}</a>.<br><br>
Статус вашей заявки на API-ключ: {%subject%}.<br>
Пользователь (ID): {%user_id%}<br>
Ключ: {%api_key%}
HTML;
	}

	/**
	 * @param array<string, mixed> $cfg
	 * @return array<string, mixed>
	 */
	public static function applyEditorDefaults(array $cfg): array {
		$cfg['email_request_subject'] = (string) ($cfg['email_request_subject'] ?? self::defaultRequestSubject());
		$cfg['email_approve_subject'] = (string) ($cfg['email_approve_subject'] ?? self::defaultApproveSubject());
		$cfg['email_deny_subject']    = (string) ($cfg['email_deny_subject'] ?? self::defaultDenySubject());
		$cfg['email_request_body']    = self::decodeHtmlBody((string) ($cfg['email_request_body'] ?? self::defaultRequestEmailTemplate()));
		$cfg['email_decision_body']   = self::decodeHtmlBody((string) ($cfg['email_decision_body'] ?? self::defaultDecisionEmailTemplate()));

		return $cfg;
	}

	/**
	 * Подтягивает текущие тела email-шаблонов из таблицы DLE в конфиг редактора.
	 *
	 * @param array<string, mixed> $cfg
	 * @return array<string, mixed>
	 */
	public static function loadEditorConfig(array $cfg): array {
		global $db;

		$cfg = self::applyEditorDefaults($cfg);

		if(!is_object($db)) {
			return $cfg;
		}

		$request = QueryBuilder::create('email')
			->withColumns(['template'])
			->withConditionsItem('name', self::REQUEST_TEMPLATE_NAME)
			->withLimit(1)
			->first();
		$decision = QueryBuilder::create('email')
			->withColumns(['template'])
			->withConditionsItem('name', self::DECISION_TEMPLATE_NAME)
			->withLimit(1)
			->first();

		if(isset($request['template']) && $request['template'] !== '') {
			$cfg['email_request_body'] = self::decodeHtmlBody((string) $request['template']);
		}
		if(isset($decision['template']) && $decision['template'] !== '') {
			$cfg['email_decision_body'] = self::decodeHtmlBody((string) $decision['template']);
		}

		return $cfg;
	}

	/**
	 * Синхронизирует шаблоны email-уведомлений в таблице DLE `_email`.
	 *
	 * @param array<string, mixed> $cfg
	 */
	public static function syncEmailTemplates(array $cfg): void {
		global $db;

		if(!is_object($db)) {
			return;
		}

		$cfg = self::applyEditorDefaults($cfg);

		self::upsertEmailTemplate(
			self::REQUEST_TEMPLATE_NAME,
			(string) $cfg['email_request_body'],
			true,
		);
		self::upsertEmailTemplate(
			self::DECISION_TEMPLATE_NAME,
			(string) $cfg['email_decision_body'],
			true,
		);
	}

	/**
	 * @param list<int> $userIds
	 * @param array<string, string> $vars
	 */
	public function notifyRequest(array $userIds, array $vars): void {
		$vars = $this->enrichVars($vars);
		$cfg = self::applyEditorDefaults(DleApiConfig::all());
		try {
			if(!empty($cfg['notify_request_email'])) {
				$vars['{%subject%}'] = (string) ($cfg['email_request_subject'] ?? self::defaultRequestSubject());
				$this->sendEmailTemplate('dleapi_key_request', $userIds, $vars);
			}
			if(!empty($cfg['notify_request_pm'])) {
				$this->sendPm(
					$userIds,
					(string) ($cfg['pm_request_subject'] ?? __('Заявка на API-ключ')),
					(string) ($cfg['pm_request_body'] ?? ''),
					$vars,
				);
			}
		} catch(Throwable $e) {
			// Уведомление не должно ронять создание заявки / ключа.
		}
	}

	/**
	 * @param array<string, string> $vars
	 */
	public function notifyDecision(int $userId, bool $approved, array $vars): void {
		$vars = $this->enrichVars($vars);
		$cfg = self::applyEditorDefaults(DleApiConfig::all());
		try {
			if(!empty($cfg['notify_decision_email'])) {
				$vars['{%subject%}'] = $approved
					? (string) ($cfg['email_approve_subject'] ?? self::defaultApproveSubject())
					: (string) ($cfg['email_deny_subject'] ?? self::defaultDenySubject());
				$this->sendEmailTemplate('dleapi_key_decision', [$userId], $vars);
			}
			if(!empty($cfg['notify_decision_pm'])) {
				$subj = $approved
					? (string) ($cfg['pm_approve_subject'] ?? __('API-ключ одобрен'))
					: (string) ($cfg['pm_deny_subject'] ?? __('API-ключ отклонён'));
				$body = $approved
					? (string) ($cfg['pm_approve_body'] ?? '')
					: (string) ($cfg['pm_deny_body'] ?? '');
				$this->sendPm([$userId], $subj, $body, $vars);
			}
		} catch(Throwable $e) {
			// см. notifyRequest
		}
	}

	/**
	 * @param list<int> $userIds
	 * @param array<string, string> $vars
	 */
	private function sendEmailTemplate(string $name, array $userIds, array $vars): void {
		global $db, $config;

		if(!is_object($db)) {
			return;
		}

		$row = QueryBuilder::create('email')
			->withConditionsItem('name', $name)
			->withLimit(1)
			->first();
		if(empty($row['template'])) {
			return;
		}
		if(!class_exists('dle_mail', false)) {
			require_once DLEPlugins::Check(ENGINE_DIR . '/classes/mail.class.php');
		}
		$site = rtrim((string) ($config['http_home_url'] ?? '/'), '/');
		$vars = [
			'{%site_url%}' => $site,
			'{%api_key%}'  => (string) ($vars['{%api_key%}'] ?? ''),
		] + $vars;

		$mail = new \dle_mail($config, !empty($row['use_html']));
		$body = $this->apply($row['template'], $vars);
		$subj = $this->apply((string) ($vars['{%subject%}'] ?? __('DLE API')), $vars);
		foreach($userIds as $uid) {
			$u = DleDataService::user(id: (int) $uid);
			if(empty($u['email'])) {
				continue;
			}
			$mail->send((string) $u['email'], $subj, $this->apply($body, [
				'{%username%}' => (string) ($u['name'] ?? ''),
			] + $vars));
		}
	}

	/**
	 * ПС через Cycle (без dle_api / хрупкого global $db).
	 *
	 * @param list<int> $userIds
	 * @param array<string, string> $vars
	 */
	private function sendPm(array $userIds, string $subject, string $body, array $vars): void {
		$prefix = defined('USERPREFIX') ? USERPREFIX : 'dle';
		$gw     = Application::instance()->database();

		$senderRows = $gw->query(
			'SELECT user_id FROM ' . $prefix . '_users WHERE user_id = :id',
			['id' => 1],
		)->fetchAll();
		$senderId = (int) ($senderRows[0]['user_id'] ?? 0);
		if($senderId < 1) {
			return;
		}

		$subj = $this->apply($subject, $vars);
		$text = $this->apply($body, $vars);
		$now  = time();

		foreach($userIds as $uid) {
			$uid = (int) $uid;
			if($uid < 1) {
				continue;
			}
			$exists = $gw->query(
				'SELECT user_id FROM ' . $prefix . '_users WHERE user_id = :id',
				['id' => $uid],
			)->fetchAll();
			if($exists === []) {
				continue;
			}

			$gw->query(
				'INSERT INTO ' . $prefix . '_conversations (subject, created_at, updated_at, sender_id, recipient_id)
				 VALUES (:subj, :now1, :now2, :sender, :recipient)',
				[
					'subj'      => $subj,
					'now1'      => $now,
					'now2'      => $now,
					'sender'    => $senderId,
					'recipient' => $uid,
				],
			);
			$cidRows = $gw->query('SELECT LAST_INSERT_ID() AS id')->fetchAll();
			$conversationId = (int) ($cidRows[0]['id'] ?? 0);
			if($conversationId < 1) {
				continue;
			}

			$gw->query(
				'INSERT INTO ' . $prefix . '_conversation_users (user_id, conversation_id)
				 VALUES (:uid, :cid) ON DUPLICATE KEY UPDATE user_id = VALUES(user_id)',
				['uid' => $uid, 'cid' => $conversationId],
			);
			$gw->query(
				'INSERT INTO ' . $prefix . '_conversations_messages (conversation_id, sender_id, content, created_at)
				 VALUES (:cid, :sender, :content, :now)',
				[
					'cid'     => $conversationId,
					'sender'  => $senderId,
					'content' => $text,
					'now'     => $now,
				],
			);
			$gw->query(
				'UPDATE ' . $prefix . '_users SET pm_unread = pm_unread + 1, pm_all = pm_all + 1 WHERE user_id = :uid',
				['uid' => $uid],
			);
		}
	}

	/**
	 * @param array<string, string> $vars
	 */
	private function apply(string $tpl, array $vars): string {
		return str_replace(array_keys($vars), array_values($vars), $tpl);
	}

	/**
	 * @param array<string, string> $vars
	 * @return array<string, string>
	 */
	private function enrichVars(array $vars): array {
		if(($vars['{%username%}'] ?? '') !== '') {
			return $vars;
		}

		$userId = (int) ($vars['{%user_id%}'] ?? 0);
		if($userId < 1) {
			return $vars;
		}

		$table = (defined('USERPREFIX') ? USERPREFIX : 'dle') . '_users';
		$row   = Application::instance()->database()->query(
			'SELECT name FROM ' . $table . ' WHERE user_id = :id',
			['id' => $userId],
		)->fetchAll();
		$name = trim((string) ($row[0]['name'] ?? ''));
		if($name !== '') {
			$vars['{%username%}'] = $name;
		}

		return $vars;
	}

	private static function upsertEmailTemplate(string $name, string $template, bool $useHtml): void {
		global $db;

		if(!is_object($db)) {
			return;
		}

		$nameSql     = $db->safesql($name);
		$templateSql = $db->safesql($template);
		$exists      = QueryBuilder::create('email')
			->withColumns(['id'])
			->withConditionsItem('name', $name)
			->withLimit(1)
			->first();
		$useHtmlSql  = $useHtml ? '1' : '0';

		if(!empty($exists['id'])) {
			$db->query(
				"UPDATE " . PREFIX . "_email SET template='{$templateSql}', use_html='{$useHtmlSql}' WHERE id='" . (int) $exists['id'] . "'"
			);
			return;
		}

		$db->query(
			"INSERT INTO " . PREFIX . "_email (name, template, use_html) VALUES ('{$nameSql}', '{$templateSql}', '{$useHtmlSql}')"
		);
	}

	/**
	 * Декодирует HTML-сущности для отображения в ACE.
	 */
	private static function decodeHtmlBody(string $value): string {
		return htmlspecialchars_decode($value, ENT_QUOTES|ENT_HTML5);
	}

}
