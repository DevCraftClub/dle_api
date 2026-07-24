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
