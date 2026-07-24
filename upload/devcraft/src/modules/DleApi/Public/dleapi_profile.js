<<<<<<< New base: Update README.md
/**
 * Публичный UI профиля: статус, запрос и модерация API-ключа.
 * Требует DevCraftPublic (dc_public.js). Строки через __() / DevCraftI18n.
 */
(function (global) {
	'use strict';

	function t(phrase, params) {
		if (typeof global.__ === 'function') {
			return global.__(phrase, params || {});
		}
		var text = String(phrase);
		Object.keys(params || {}).forEach(function (key) {
			text = text.split('{' + key + '}').join(String(params[key]));
		});
		return text;
	}

	function ajax() {
		return global.DevCraftPublic && global.DevCraftPublic.Ajax;
	}

	function fmtDate(value) {
		if (!value) {
			return '—';
		}
		var date = new Date(value);
		return isNaN(date.getTime()) ? String(value) : date.toLocaleString('ru-RU');
	}

	function mask(value) {
		var text = String(value || '');
		if (text.length <= 8) {
			return text ? text : '—';
		}
		return text.slice(0, 4) + '••••' + text.slice(-4);
	}

	function boot() {
		var box = document.getElementById('dleapi-profile-key');
		var status = document.getElementById('dleapi-profile-status');
		var meta = document.getElementById('dleapi-profile-meta');
		var keyInput = document.getElementById('dleapi-profile-key-value');
		var validFrom = document.getElementById('dleapi-profile-valid-from');
		var validTo = document.getElementById('dleapi-profile-valid-to');
		var level = document.getElementById('dleapi-profile-level');
		var notice = document.getElementById('dleapi-profile-notice');
		var requestBtn = document.getElementById('dleapi-profile-request');
		var newBtn = document.getElementById('dleapi-profile-request-new');
		var approveBtn = document.getElementById('dleapi-profile-approve');
		var denyBtn = document.getElementById('dleapi-profile-deny');
		var copyBtn = document.getElementById('dleapi-profile-copy');
		var icon = document.getElementById('dleapi-profile-icon');
		if (!box) {
			return;
		}

		var client = ajax();
		if (!client || !client.post) {
			if (status) {
				status.textContent = t('DevCraftPublic недоступен');
			}
			return;
		}

		if (box.dataset.userHash && !global.dle_login_hash) {
			global.dle_login_hash = box.dataset.userHash;
		}

		var profileUserId = parseInt(box.dataset.profileUserId || '0', 10) || 0;

		function setText(node, text) {
			if (node) {
				node.textContent = text;
			}
		}

		function setButtons(state) {
			if (requestBtn) requestBtn.hidden = !state.request;
			if (newBtn) newBtn.hidden = !state.renew;
			if (approveBtn) approveBtn.hidden = !state.approve;
			if (denyBtn) denyBtn.hidden = !state.deny;
		}

		function render(data) {
			data = data || {};

			if (!data.profile_allow_generate && !data.profile_show_field && !data.viewer_is_admin) {
				box.hidden = true;
				return;
			}

			box.hidden = false;
			if (meta) {
				meta.classList.add('d-none');
			}
			if (notice) {
				notice.textContent = '';
			}

			var currentStatus = data.status || 'idle';
			if (currentStatus === 'approved' && data.key) {
				if (meta) {
					meta.classList.remove('d-none');
				}
				if (keyInput) {
					keyInput.value = data.key.key || '';
				}
				setText(validFrom, fmtDate(data.key.valid_from));
				setText(validTo, data.key.valid_to ? fmtDate(data.key.valid_to) : t('Бессрочно'));
				setText(level, data.access_level && data.access_level.name ? data.access_level.name : '—');
				setText(status, t('API-ключ активен'));
				setButtons({ request: false, renew: !!data.profile_allow_generate && data.mode === 'self', approve: false, deny: false });
				if (icon) {
					icon.innerHTML = '&lt;API /&gt;';
				}
				return;
			}

			if (currentStatus === 'pending') {
				setText(status, t('Заявка отправлена'));
				setText(notice, data.mode === 'moderation'
					? t('Ожидает решения администратора.')
					: t('Пожалуйста, подождите, заявка ещё рассматривается.'));
				setButtons({
					request: false,
					renew: false,
					approve: data.mode === 'moderation',
					deny: data.mode === 'moderation'
				});
				if (icon) {
					icon.innerHTML = '◌';
				}
				return;
			}

			if (currentStatus === 'denied') {
				setText(status, t('Заявка не одобрена'));
				setText(notice, t('Повторно отправить заявку можно в любой момент.'));
				setButtons({ request: !!data.profile_allow_generate && data.mode === 'self', renew: false, approve: false, deny: false });
				if (icon) {
					icon.innerHTML = '✕';
				}
				return;
			}

			setText(status, data.mode === 'moderation' ? t('Пользователь ещё не запросил ключ') : t('Ключ ещё не создан'));
			setText(notice, data.profile_allow_generate
				? t('Нажмите кнопку, чтобы отправить заявку или получить ключ.')
				: t('Ключ выдаёт администратор.'));
			setButtons({ request: !!data.profile_allow_generate && data.mode === 'self', renew: false, approve: false, deny: false });
			if (icon) {
				icon.innerHTML = '&lt;/&gt;';
			}
		}

		function loadStatus() {
			return client.post('dleapi', 'profile_key', {
				action: 'status',
				profile_user_id: profileUserId
			}).then(function (res) {
				if (!res || !res.success) {
					setText(status, (res && res.error && res.error.message) || t('Ошибка'));
					return;
				}
				render(res.data || {});
			}).catch(function () {
				setText(status, t('Сеть недоступна'));
			});
		}

		function withButton(btn, callback) {
			if (!btn) {
				return;
			}
			btn.disabled = true;
			callback().finally(function () {
				btn.disabled = false;
			});
		}

		if (requestBtn) {
			requestBtn.addEventListener('click', function () {
				withButton(requestBtn, function () {
					return client.post('dleapi', 'profile_key', {
						action: 'request',
						profile_user_id: profileUserId
					}).then(function (res) {
						if (!res || !res.success) {
							setText(status, (res && res.error && res.error.message) || t('Ошибка'));
							return;
						}
						return loadStatus();
					}).catch(function () {
						setText(status, t('Сеть недоступна'));
					});
				});
			});
		}

		if (newBtn) {
			newBtn.addEventListener('click', function () {
				if (requestBtn) {
					requestBtn.click();
				}
			});
		}

		if (approveBtn) {
			approveBtn.addEventListener('click', function () {
				withButton(approveBtn, function () {
					return client.post('dleapi', 'profile_key', {
						action: 'moderate',
						profile_user_id: profileUserId,
						approve: 1
					}).then(function (res) {
						if (!res || !res.success) {
							setText(status, (res && res.error && res.error.message) || t('Ошибка'));
							return;
						}
						render(res.data || {});
					}).catch(function () {
						setText(status, t('Сеть недоступна'));
					});
				});
			});
		}

		if (denyBtn) {
			denyBtn.addEventListener('click', function () {
				withButton(denyBtn, function () {
					return client.post('dleapi', 'profile_key', {
						action: 'moderate',
						profile_user_id: profileUserId,
						approve: 0
					}).then(function (res) {
						if (!res || !res.success) {
							setText(status, (res && res.error && res.error.message) || t('Ошибка'));
							return;
						}
						render(res.data || {});
					}).catch(function () {
						setText(status, t('Сеть недоступна'));
					});
				});
			});
		}

		if (copyBtn && keyInput) {
			copyBtn.addEventListener('click', function () {
				var text = String(keyInput.value || '');
				if (!text) {
					return;
				}
				if (navigator.clipboard && navigator.clipboard.writeText) {
					navigator.clipboard.writeText(text).then(function () {
						setText(notice, t('Ключ скопирован в буфер обмена.'));
					});
					return;
				}
				keyInput.focus();
				keyInput.select();
				setText(notice, t('Выделите ключ и нажмите Ctrl+C.'));
			});
		}

		loadStatus();
	}

	function start() {
		var tries = 0;
		function attempt() {
			if (ajax() && typeof ajax().post === 'function') {
				boot();
				return;
			}
			tries += 1;
			if (tries < 40) {
				setTimeout(attempt, 50);
				return;
			}
			boot();
		}
		document.addEventListener('dc:public:ready', attempt, { once: true });
		attempt();
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', start);
	} else {
		start();
	}
})(window);
|||||||
=======
/**
 * Публичный UI профиля: статус и запрос API-ключа.
 */
(function (global) {
	'use strict';

	function ready(fn) {
		if (document.readyState !== 'loading') {
			fn();
		} else {
			document.addEventListener('DOMContentLoaded', fn);
		}
	}

	ready(function () {
		var box = document.getElementById('dleapi-profile-key');
		var status = document.getElementById('dleapi-profile-status');
		var btn = document.getElementById('dleapi-profile-request');
		if (!box || !global.DevCraftPublic || !global.DevCraftPublic.post) {
			if (status) {
				status.textContent = 'DevCraftPublic недоступен';
			}
			return;
		}

		function setStatus(text) {
			if (status) {
				status.textContent = text;
			}
		}

		global.DevCraftPublic.post('dleapi', 'profile_key', { action: 'status' }).then(function (res) {
			if (!res || !res.success) {
				setStatus((res && res.error && res.error.message) || 'Ошибка');
				return;
			}
			var d = res.data || {};
			if (!d.profile_allow_generate && !d.profile_show_field) {
				box.hidden = true;
				return;
			}
			if (d.has_key_in_xfield) {
				setStatus('Ключ записан в выбранное поле профиля (xfield).');
			} else if (d.profile_allow_generate) {
				setStatus('Ключ ещё не создан. Нажмите кнопку, чтобы запросить.');
			} else {
				setStatus('Ключ выдаёт администратор.');
			}
			if (btn && !d.profile_allow_generate) {
				btn.hidden = true;
			}
		}).catch(function () {
			setStatus('Сеть недоступна');
		});

		if (btn) {
			btn.addEventListener('click', function () {
				btn.disabled = true;
				global.DevCraftPublic.post('dleapi', 'profile_key', { action: 'request' }).then(function (res) {
					btn.disabled = false;
					if (!res || !res.success) {
						setStatus((res && res.error && res.error.message) || 'Ошибка');
						return;
					}
					var d = res.data || {};
					if (d.status === 'pending') {
						setStatus(d.message || 'Заявка отправлена на модерацию');
					} else if (d.key) {
						setStatus('Ключ создан. Значение записано в xfield профиля.');
					} else {
						setStatus(d.message || 'Готово');
					}
				}).catch(function () {
					btn.disabled = false;
					setStatus('Сеть недоступна');
				});
			});
		}
	});
})(window);
>>>>>>> Current commit: Начало обновления до api v2
