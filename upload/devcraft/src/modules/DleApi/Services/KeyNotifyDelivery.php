<?php

declare(strict_types=1);

namespace DevCraft\Modules\DleApi\Services;

use DLEPlugins;

/**
 * Email (шаблоны _email) и PM (settings) для заявок на ключ.
 */
final class KeyNotifyDelivery {

	/**
	 * @param list<int> $userIds
	 * @param array<string, string> $vars
	 */
	public function notifyRequest(array $userIds, array $vars): void {
		$cfg = DleApiConfig::all();
		if(!empty($cfg['notify_request_email'])) {
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
	}

	/**
	 * @param array<string, string> $vars
	 */
	public function notifyDecision(int $userId, bool $approved, array $vars): void {
		$cfg = DleApiConfig::all();
		if(!empty($cfg['notify_decision_email'])) {
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
	}

	/**
	 * @param list<int> $userIds
	 * @param array<string, string> $vars
	 */
	private function sendEmailTemplate(string $name, array $userIds, array $vars): void {
		global $db, $config;

		$row = $db->super_query("SELECT * FROM " . PREFIX . "_email WHERE name='" . $db->safesql($name) . "' LIMIT 1");
		if(empty($row['template'])) {
			return;
		}
		if(!class_exists('dle_mail', false)) {
			require_once DLEPlugins::Check(ENGINE_DIR . '/classes/mail.class.php');
		}
		$mail = new \dle_mail($config, !empty($row['use_html']));
		$body = $this->apply($row['template'], $vars);
		$subj = $this->apply((string) ($vars['{%subject%}'] ?? __('DLE API')), $vars);
		foreach($userIds as $uid) {
			$u = $db->super_query('SELECT email, name FROM ' . USERPREFIX . '_users WHERE user_id=' . (int) $uid);
			if(empty($u['email'])) {
				continue;
			}
			$mail->send((string) $u['email'], $subj, $this->apply($body, [
				'{%username%}' => (string) ($u['name'] ?? ''),
			] + $vars));
		}
	}

	/**
	 * @param list<int> $userIds
	 * @param array<string, string> $vars
	 */
	private function sendPm(array $userIds, string $subject, string $body, array $vars): void {
		global $db;
		if(!function_exists('send_pm_to_user') && class_exists('dle_api', false) === false) {
			// fallback через api.class если доступен
		}
		require_once DLEPlugins::Check(ENGINE_DIR . '/api/api.class.php');
		$api = new \dle_api();
		$subj = $this->apply($subject, $vars);
		$text = $this->apply($body, $vars);
		foreach($userIds as $uid) {
			if($uid < 1) {
				continue;
			}
			$api->send_pm_to_user($uid, $subj, $text, 1);
		}
	}

	/**
	 * @param array<string, string> $vars
	 */
	private function apply(string $tpl, array $vars): string {
		return str_replace(array_keys($vars), array_values($vars), $tpl);
	}

}
