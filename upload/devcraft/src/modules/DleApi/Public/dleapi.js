/**
 * DLE API admin assets (подключается после devcraft.js).
 */
(function (global) {
	'use strict';

	if (!global.DevCraft) {
		console.error('[DLE API] Сначала должен быть загружен DevCraft core.');
		return;
	}

	var busy = false;

	function t(phrase, params) {
		return global.DevCraft.__(phrase, params || {});
	}

	function yesNo(v) {
		return v ? t('Да') : t('Нет');
	}

	function attrLabel(el, name, fallback) {
		if (!el) {
			return t(fallback);
		}
		var v = el.getAttribute(name);
		return v != null && v !== '' ? v : t(fallback);
	}

	function setPanelCaption(titleEl, text) {
		if (!titleEl) {
			return;
		}
		var cap = titleEl.querySelector('.caption');
		if (cap) {
			cap.textContent = text;
			return;
		}
		titleEl.textContent = text;
	}

	function formToggleButton(box) {
		if (!box || !box.id) {
			return null;
		}
		return document.querySelector('[data-dleapi-form-toggle="' + box.id + '"]');
	}

	function syncFormToggleLabel(btn, box) {
		if (!btn || !box) {
			return;
		}
		var open = !box.classList.contains('d-none');
		btn.textContent = attrLabel(
			btn,
			open ? 'data-label-hide' : 'data-label-show',
			open ? 'Скрыть форму' : 'Показать форму'
		);
	}

	function setFormBoxOpen(box, open) {
		if (!box) {
			return;
		}
		if (open) {
			box.classList.remove('d-none');
		} else {
			box.classList.add('d-none');
		}
		syncFormToggleLabel(formToggleButton(box), box);
	}

	function bindFormToggles() {
		document.querySelectorAll('[data-dleapi-form-toggle]').forEach(function (btn) {
			var box = document.getElementById(btn.getAttribute('data-dleapi-form-toggle') || '');
			if (!box) {
				return;
			}
			syncFormToggleLabel(btn, box);
			btn.addEventListener('click', function () {
				setFormBoxOpen(box, box.classList.contains('d-none'));
			});
		});
	}

	function parsePlaceholderList(field) {
		if (!field || !field.dataset) {
			return [];
		}
		try {
			var parsed = JSON.parse(field.dataset.dleapiPlaceholders || '[]');
			return Array.isArray(parsed) ? parsed : [];
		} catch (e) {
			return [];
		}
	}

	function placeholderHost(field) {
		return field.closest('.dc-field-widget') || field.parentNode;
	}

	function insertPlaceholderList(field, wrap) {
		var host = placeholderHost(field);
		var feedback = host && host.querySelector ? host.querySelector(':scope > .invalid_feedback') : null;
		if (feedback) {
			feedback.insertAdjacentElement('beforebegin', wrap);
			return;
		}
		if (host && host !== field) {
			host.appendChild(wrap);
			return;
		}
		field.insertAdjacentElement('afterend', wrap);
	}

	function renderPlaceholderLists() {
		document.querySelectorAll('[data-dleapi-placeholders]').forEach(function (field) {
			var placeholders = parsePlaceholderList(field);
			if (!placeholders.length || field.dataset.dleapiPlaceholdersReady === '1') {
				return;
			}
			field.dataset.dleapiPlaceholdersReady = '1';

			var wrap = document.createElement('div');
			wrap.className = 'dleapi-placeholder-list mt-2';

			var title = document.createElement('div');
			title.className = 'text-small fg-gray mb-1';
			title.textContent = field.dataset.dleapiPlaceholderTitle || t('Доступные плейсхолдеры');
			wrap.appendChild(title);

			placeholders.forEach(function (placeholder) {
				var btn = document.createElement('button');
				btn.type = 'button';
				btn.className = 'button secondary mini outline rounded mr-1 mb-1';
				btn.setAttribute('data-dleapi-placeholder-value', placeholder);
				btn.setAttribute('data-dleapi-placeholder-target', field.id || '');
				btn.textContent = placeholder;
				wrap.appendChild(btn);
			});

			insertPlaceholderList(field, wrap);
		});
	}

	function editorForField(field) {
		if (!field || !field.id || typeof window.tinymce === 'undefined') {
			return null;
		}
		return window.tinymce.get(field.id) || null;
	}

	function insertPlaceholder(field, text) {
		if (!field || !text) {
			return;
		}

		var editor = editorForField(field);
		if (editor && !editor.hidden) {
			editor.focus();
			editor.insertContent(text);
			editor.save();
			return;
		}

		var start = typeof field.selectionStart === 'number' ? field.selectionStart : field.value.length;
		var end = typeof field.selectionEnd === 'number' ? field.selectionEnd : field.value.length;
		var value = String(field.value || '');
		field.value = value.slice(0, start) + text + value.slice(end);
		field.focus();
		if (typeof field.setSelectionRange === 'function') {
			field.setSelectionRange(start + text.length, start + text.length);
		}
		field.dispatchEvent(new Event('input', { bubbles: true }));
		field.dispatchEvent(new Event('change', { bubbles: true }));
	}

	function bindPlaceholderClicks() {
		if (document.body.dataset.dleapiPlaceholderBound === '1') {
			return;
		}
		document.body.dataset.dleapiPlaceholderBound = '1';

		document.addEventListener('click', function (e) {
			var btn = e.target.closest('[data-dleapi-placeholder-value]');
			if (!btn) {
				return;
			}
			e.preventDefault();
			var target = document.getElementById(btn.getAttribute('data-dleapi-placeholder-target') || '');
			insertPlaceholder(target, btn.getAttribute('data-dleapi-placeholder-value') || '');
		});
	}

	function metroLib() {
		if (window.DevCraft && DevCraft.Metro && typeof DevCraft.Metro.lib === 'function') {
			return DevCraft.Metro.lib();
		}
		return window.Metro || null;
	}

	function openActivity(text) {
		var lib = metroLib();
		if (!lib || !lib.activity || typeof lib.activity.open !== 'function') {
			return null;
		}
		return lib.activity.open({
			type: 'cycle',
			text: text,
			overlayClickClose: false
		});
	}

	function closeActivity(activity) {
		var lib = metroLib();
		if (!activity || !lib || !lib.activity || typeof lib.activity.close !== 'function') {
			return;
		}
		lib.activity.close(activity);
	}

	function activityText(method) {
		switch (method) {
			case 'create_key':
				return t('Создание ключа…');
			case 'update_key':
				return t('Сохранение ключа…');
			case 'get_key':
				return t('Загрузка прав…');
			case 'delete_key':
				return t('Удаление ключа…');
			case 'toggle_key':
				return t('Обновление ключа…');
			case 'create_oauth_client':
				return t('Создание клиента…');
			case 'update_oauth_client':
				return t('Сохранение клиента…');
			case 'get_oauth_client':
				return t('Загрузка клиента…');
			case 'regenerate_oauth_client_secret':
				return t('Пересоздание секрета…');
			case 'delete_oauth_client':
				return t('Удаление клиента…');
			case 'save_access_level':
				return t('Сохранение уровня…');
			case 'get_access_level':
				return t('Загрузка уровня…');
			case 'delete_access_level':
				return t('Удаление уровня…');
			case 'save_access_sync':
				return t('Сохранение синхронизации…');
			case 'decide_key_request':
				return t('Обработка заявки…');
			default:
				return t('Выполнение…');
		}
	}

	function keysTbody() {
		return document.getElementById('dleapi-keys-tbody');
	}

	function ensureEmptyRow() {
		var tbody = keysTbody();
		if (!tbody) {
			return;
		}
		if (tbody.querySelector('tr[data-key-id]')) {
			return;
		}
		if (tbody.querySelector('.dleapi-keys-empty')) {
			return;
		}
		var tr = document.createElement('tr');
		tr.className = 'dleapi-keys-empty';
		tr.innerHTML = '<td colspan="6">' + t('Ключей пока нет') + '</td>';
		tbody.appendChild(tr);
	}

	function removeEmptyRow() {
		var tbody = keysTbody();
		if (!tbody) {
			return;
		}
		var empty = tbody.querySelector('.dleapi-keys-empty');
		if (empty) {
			empty.remove();
		}
	}

	function collectScopes() {
		var tables = {};
		document.querySelectorAll('#dleapi-scopes-table [data-scope-flag]').forEach(function (el) {
			var table = el.getAttribute('data-table');
			var flag = el.getAttribute('data-scope-flag');
			if (!table || !flag) {
				return;
			}
			if (!tables[table]) {
				tables[table] = { read: 0, write: 0, edit: 0, delete: 0 };
			}
			if (el.checked) {
				tables[table][flag] = 1;
			}
		});
		return tables;
	}

	function clearScopes() {
		document.querySelectorAll('#dleapi-scopes-table [data-scope-flag]').forEach(function (el) {
			el.checked = false;
		});
		document.querySelectorAll('[data-dleapi-scope-all]').forEach(function (el) {
			el.checked = false;
		});
	}

	function applyScopes(map) {
		clearScopes();
		map = map || {};
		Object.keys(map).forEach(function (table) {
			var flags = map[table] || {};
			['read', 'write', 'edit', 'delete'].forEach(function (flag) {
				var el = document.querySelector(
					'#dleapi-scopes-table [data-scope-flag="' + flag + '"][data-table="' + table + '"]'
				);
				if (el) {
					el.checked = !!(flags[flag]);
				}
			});
		});
	}

	function setEditMode(on, data) {
		var form = document.getElementById('dleapi-create-key-form');
		var title = document.getElementById('dleapi-key-form-title');
		var submit = document.getElementById('dleapi-key-submit');
		var cancel = document.getElementById('dleapi-key-cancel-edit');
		var idInput = document.getElementById('dleapi-key-id');
		if (!form) {
			return;
		}
		if (!on) {
			idInput.value = '';
			if (title) setPanelCaption(title, attrLabel(title, 'data-label-create', 'Создать ключ'));
			if (submit) submit.textContent = attrLabel(submit, 'data-label-create', 'Создать');
			if (cancel) cancel.hidden = true;
			resetCreateKeyForm(form);
			return;
		}
		idInput.value = String(data.id || '');
		form.querySelector('[name="user_id"]').value = String(data.user_id != null ? data.user_id : 0);
		var levelSel = form.querySelector('[name="access_level_id"]');
		if (levelSel) {
			levelSel.value = String(data.access_level_id != null ? data.access_level_id : 0);
		}
		applyScopes(data.tables || {});
		if (title) setPanelCaption(title, t('Права ключа #{id}', {id: data.id}));
		if (submit) submit.textContent = attrLabel(submit, 'data-label-save', 'Сохранить');
		if (cancel) cancel.hidden = false;
		setFormBoxOpen(document.getElementById('dleapi-key-form-container'), true);
		form.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
		var panel = document.getElementById('dleapi-scopes-panel');
		if (panel) {
			panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
		}
	}

	function prependKeyRow(data) {
		var tbody = keysTbody();
		if (!tbody || !data || !data.id) {
			return;
		}
		removeEmptyRow();
		if (tbody.querySelector('tr[data-key-id="' + data.id + '"]')) {
			updateKeyRow(data);
			return;
		}
		var tr = document.createElement('tr');
		tr.setAttribute('data-key-id', String(data.id));
		tr.innerHTML =
			'<td>' + data.id + '</td>' +
			'<td><code>' + String(data.api || '').replace(/</g, '&lt;') + '</code></td>' +
			'<td data-level-id="' + (data.access_level_id || 0) + '">' + escText(data.level_name || levelLabel(data.access_level_id)) + '</td>' +
			'<td>' + yesNo(data.active !== false && data.active !== 0) + '</td>' +
			'<td data-user-id="' + (data.user_id != null ? data.user_id : 0) + '">' + escText(data.user_label || userLabel(data.user_id)) + '</td>' +
			'<td>' +
			'<button type="button" class="button small" data-dleapi-edit-key="' + data.id + '">' + t('Права') + '</button> ' +
			'<button type="button" class="button small" data-dleapi-toggle-key="' + data.id + '" data-active="0">' + t('Отключить') + '</button> ' +
			'<button type="button" class="button alert small" data-dleapi-delete-key="' + data.id + '">' + t('Удалить') + '</button>' +
			'</td>';
		tbody.insertBefore(tr, tbody.firstChild);
	}

	function updateKeyRow(data) {
		var tr = document.querySelector('#dleapi-keys-tbody tr[data-key-id="' + data.id + '"]');
		if (!tr) {
			return;
		}
		if (tr.children[2]) {
			tr.children[2].setAttribute('data-level-id', String(data.access_level_id || 0));
			tr.children[2].textContent = data.level_name || levelLabel(data.access_level_id);
		}
		if (tr.children[3]) tr.children[3].textContent = yesNo(data.active !== false && data.active !== 0);
		if (tr.children[4]) {
			tr.children[4].setAttribute('data-user-id', String(data.user_id != null ? data.user_id : 0));
			tr.children[4].textContent = data.user_label || userLabel(data.user_id);
		}
	}

	function escText(s) {
		var d = document.createElement('div');
		d.textContent = s == null ? '' : String(s);
		return d.innerHTML;
	}

	function levelLabel(id) {
		id = parseInt(id || '0', 10) || 0;
		if (id < 1) {
			return '—';
		}
		var sel = document.querySelector('#dleapi-create-key-form [name="access_level_id"]');
		var opt = sel && sel.querySelector('option[value="' + id + '"]');
		return opt ? opt.textContent.trim() : ('#' + id);
	}

	function userLabel(id) {
		id = parseInt(id || '0', 10) || 0;
		var sel = document.querySelector('#dleapi-create-key-form [name="user_id"]');
		var opt = sel && sel.querySelector('option[value="' + id + '"]');
		if (opt) {
			return opt.textContent.trim().replace(/^#\d+\s*—\s*/, '');
		}
		return id < 1 ? t('гость') : ('#' + id);
	}

	function removeKeyRow(id) {
		var tbody = keysTbody();
		var tr = tbody && tbody.querySelector('tr[data-key-id="' + id + '"]');
		if (tr) {
			tr.remove();
		}
		ensureEmptyRow();
		var idInput = document.getElementById('dleapi-key-id');
		if (idInput && String(idInput.value) === String(id)) {
			setEditMode(false);
		}
	}

	function resetCreateKeyForm(form) {
		if (!form) {
			return;
		}
		form.reset();
		var idInput = document.getElementById('dleapi-key-id');
		if (idInput) {
			idInput.value = '';
		}
		var ownOnly = form.querySelector('[name="own_only"]');
		if (ownOnly) {
			ownOnly.checked = true;
		}
		var isAdmin = form.querySelector('[name="is_admin"]');
		if (isAdmin) {
			isAdmin.checked = false;
		}
		var levelSel = form.querySelector('[name="access_level_id"]');
		if (levelSel) {
			levelSel.value = '0';
		}
		clearScopes();
	}

	function escapeHtml(s) {
		return String(s == null ? '' : s)
			.replace(/&/g, '&amp;')
			.replace(/</g, '&lt;')
			.replace(/>/g, '&gt;')
			.replace(/"/g, '&quot;');
	}

	function oauthTbody() {
		return document.getElementById('dleapi-oauth-tbody');
	}

	function showOauthSecretBanner(data) {
		var banner = document.getElementById('dleapi-oauth-secret-banner');
		var idInput = document.getElementById('dleapi-oauth-new-client-id');
		var secretInput = document.getElementById('dleapi-oauth-new-client-secret');
		if (!banner || !idInput || !secretInput || !data) {
			return;
		}
		idInput.value = String(data.client_id || '');
		secretInput.value = String(data.client_secret || '');
		banner.hidden = false;
		banner.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
	}

	function hideOauthSecretBanner() {
		var banner = document.getElementById('dleapi-oauth-secret-banner');
		var idInput = document.getElementById('dleapi-oauth-new-client-id');
		var secretInput = document.getElementById('dleapi-oauth-new-client-secret');
		if (banner) {
			banner.hidden = true;
		}
		if (idInput) {
			idInput.value = '';
		}
		if (secretInput) {
			secretInput.value = '';
		}
	}

	function copyFieldById(fieldId) {
		var el = document.getElementById(fieldId);
		var text = el ? String(el.value || '') : '';
		if (!text) {
			return;
		}
		function done(ok) {
			if (window.DevCraft && DevCraft.Metro && DevCraft.Metro.notifySuccess) {
				DevCraft.Metro.notifySuccess(
					t('Готово'),
					ok ? t('Скопировано в буфер обмена') : t('Выделите текст и нажмите Ctrl+C')
				);
			}
		}
		function fallback() {
			var helper = document.createElement('textarea');
			helper.value = text;
			helper.setAttribute('readonly', 'readonly');
			helper.style.position = 'fixed';
			helper.style.opacity = '0';
			helper.style.pointerEvents = 'none';
			document.body.appendChild(helper);
			helper.focus();
			helper.select();
			var ok = false;
			try {
				ok = document.execCommand('copy');
			} catch (e) {
				ok = false;
			}
			document.body.removeChild(helper);
			if (!ok && el) {
				el.focus();
				el.select();
			}
			done(ok);
		}
		if (navigator.clipboard && navigator.clipboard.writeText) {
			navigator.clipboard.writeText(text).then(function () {
				done(true);
			}).catch(function () {
				fallback();
			});
			return;
		}
		fallback();
	}

	function prependOauthRow(data) {
		var tbody = oauthTbody();
		if (!tbody || !data || !data.id) {
			return;
		}
		var empty = tbody.querySelector('.dleapi-oauth-empty');
		if (empty) {
			empty.remove();
		}
		if (tbody.querySelector('tr[data-oauth-id="' + data.id + '"]')) {
			updateOauthRow(data);
			return;
		}
		var tr = document.createElement('tr');
		tr.setAttribute('data-oauth-id', String(data.id));
		tr.innerHTML =
			'<td>' + data.id + '</td>' +
			'<td>' + escapeHtml(data.name || data.client_id || '') + '</td>' +
			'<td><code>' + escapeHtml(data.client_id || '') + '</code></td>' +
			'<td><code>' + escapeHtml(data.api_key_preview || '—') + '</code></td>' +
			'<td>' + yesNo(data.active !== false && data.active !== 0) + '</td>' +
			'<td>' +
			'<button type="button" class="button small" data-dleapi-edit-oauth="' + data.id + '">' + t('Изменить') + '</button> ' +
			'<button type="button" class="button alert small" data-dleapi-delete-oauth="' + data.id + '">' + t('Удалить') + '</button>' +
			'</td>';
		tbody.insertBefore(tr, tbody.firstChild);
	}

	function updateOauthRow(data) {
		var tr = document.querySelector('#dleapi-oauth-tbody tr[data-oauth-id="' + data.id + '"]');
		if (!tr) {
			return;
		}
		if (tr.children[1]) {
			tr.children[1].textContent = String(data.name || data.client_id || '');
		}
		if (tr.children[2] && data.client_id) {
			tr.children[2].innerHTML = '<code>' + escapeHtml(data.client_id) + '</code>';
		}
		if (tr.children[3]) {
			tr.children[3].innerHTML = '<code>' + escapeHtml(data.api_key_preview || '—') + '</code>';
		}
		if (tr.children[4] && data.active !== undefined) {
			tr.children[4].textContent = yesNo(!!data.active);
		}
	}

	function resetOauthForm(form) {
		if (!form) {
			return;
		}
		form.reset();
		var idInput = document.getElementById('dleapi-oauth-id');
		if (idInput) {
			idInput.value = '';
		}
		var active = form.querySelector('[name="active"]');
		if (active) {
			active.checked = true;
		}
		applyOauthGrants(null);
	}

	function collectOauthGrants(form) {
		var grants = [];
		if (!form) {
			return grants;
		}
		form.querySelectorAll('#dleapi-oauth-grants [name="grant_types[]"]').forEach(function (el) {
			if (el.checked) {
				grants.push(el.value);
			}
		});
		return grants;
	}

	function applyOauthGrants(csvOrList) {
		var selected = {};
		if (csvOrList == null) {
			['authorization_code', 'refresh_token', 'client_credentials', 'password'].forEach(function (g) {
				selected[g] = true;
			});
		} else if (Array.isArray(csvOrList)) {
			csvOrList.forEach(function (g) {
				selected[String(g)] = true;
			});
		} else {
			String(csvOrList || '').split(',').forEach(function (g) {
				g = g.trim();
				if (g) {
					selected[g] = true;
				}
			});
		}
		document.querySelectorAll('#dleapi-oauth-grants [name="grant_types[]"]').forEach(function (el) {
			el.checked = !!selected[el.value];
		});
	}

	function setOauthEditMode(on, data) {
		var form = document.getElementById('dleapi-create-oauth-form');
		var title = document.getElementById('dleapi-oauth-form-title');
		var submit = document.getElementById('dleapi-oauth-submit');
		var cancel = document.getElementById('dleapi-oauth-cancel-edit');
		var idInput = document.getElementById('dleapi-oauth-id');
		var idGroup = document.getElementById('dleapi-oauth-client-id-group');
		var idView = document.getElementById('dleapi-oauth-client-id-view');
		var activeGroup = document.getElementById('dleapi-oauth-active-group');
		var regenGroup = document.getElementById('dleapi-oauth-regen-group');
		var help = document.getElementById('dleapi-oauth-form-help');
		if (!form || !idInput) {
			return;
		}
		if (!on) {
			idInput.value = '';
			resetOauthForm(form);
			if (title) setPanelCaption(title, attrLabel(title, 'data-label-create', 'Создать клиент'));
			if (submit) submit.textContent = attrLabel(submit, 'data-label-create', 'Создать');
			if (cancel) cancel.hidden = true;
			if (idGroup) idGroup.hidden = true;
			if (activeGroup) activeGroup.hidden = true;
			if (regenGroup) regenGroup.hidden = true;
			if (idView) idView.value = '';
			if (help) {
				help.textContent = attrLabel(help, 'data-help-create', 'OAuth-клиент выдаёт access_token для вызовов /api/v2. Права запросов берёт из выбранного API-ключа (scopes). После создания сохраните client_id и client_secret — секрет больше не показывается.');
			}
			return;
		}
		data = data || {};
		idInput.value = String(data.id || '');
		form.querySelector('[name="name"]').value = String(data.name || '');
		form.querySelector('[name="api_key_id"]').value = String(data.api_key_id || '');
		form.querySelector('[name="redirect_uri"]').value = String(data.redirect_uri || '');
		applyOauthGrants(data.grant_types || '');
		var active = form.querySelector('[name="active"]');
		if (active) {
			active.checked = data.active !== false && data.active !== 0;
		}
		if (idView) {
			idView.value = String(data.client_id || '');
		}
		if (title) setPanelCaption(title, t('Клиент #{id}', {id: data.id}));
		if (submit) submit.textContent = attrLabel(submit, 'data-label-save', 'Сохранить');
		if (cancel) cancel.hidden = false;
		if (idGroup) idGroup.hidden = false;
		if (activeGroup) activeGroup.hidden = false;
		if (regenGroup) regenGroup.hidden = false;
		if (help) {
			help.textContent = attrLabel(help, 'data-help-edit', 'Редактирование: название, API-ключ, Redirect URI, grant types и активность. client_id не меняется; секрет — только через «Пересоздать client_secret».');
		}
		setFormBoxOpen(document.getElementById('dleapi-oauth-form-container'), true);
		form.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
	}

	function markRequestRow(row, status) {
		if (!row) {
			return;
		}
		var st = row.querySelector('[data-status]');
		if (st) {
			st.textContent = status || '—';
		}
		var actions = row.querySelector('td:last-child');
		if (actions) {
			actions.textContent = '—';
		}
	}

	function post(method, data, onSuccess, triggerEl) {
		if (!window.DevCraft || !DevCraft.Ajax || !DevCraft.Ajax.post) {
			console.error(t('DevCraft.Ajax недоступен'));
			return;
		}
		if (busy) {
			return;
		}
		busy = true;
		if (triggerEl) {
			triggerEl.disabled = true;
		}
		var payload = Object.assign({}, data || {}, { __loaderText: activityText(method) });
		return DevCraft.Ajax.post(method, payload).then(function (res) {
			if (DevCraft.Ajax.handleNotice) {
				DevCraft.Ajax.handleNotice(res);
			}
			if (res && res.success && typeof onSuccess === 'function') {
				onSuccess(res.data || {});
			}
			return res;
		}).catch(function (err) {
			if (DevCraft.Metro && DevCraft.Metro.notifyError) {
				DevCraft.Metro.notifyError(t('Ошибка'), t('Сеть или сервер недоступен'), err);
			}
		}).then(function (res) {
			busy = false;
			if (triggerEl && triggerEl.isConnected) {
				triggerEl.disabled = false;
			}
			return res;
		});
	}

	document.addEventListener('DOMContentLoaded', function () {
		var createKey = document.getElementById('dleapi-create-key-form');
		if (createKey) {
			createKey.addEventListener('submit', function (e) {
				e.preventDefault();
				var fd = new FormData(createKey);
				var id = parseInt(fd.get('id') || '0', 10);
				var payload = {
					user_id: parseInt(fd.get('user_id') || '0', 10),
					access_level_id: parseInt(fd.get('access_level_id') || '0', 10),
					tables: collectScopes()
				};
				var submitBtn = document.getElementById('dleapi-key-submit');
				if (id > 0) {
					payload.id = id;
					post('update_key', payload, function (data) {
						updateKeyRow(data);
						setEditMode(false);
					}, submitBtn);
					return;
				}
				post('create_key', payload, function (data) {
					prependKeyRow(data);
					resetCreateKeyForm(createKey);
				}, submitBtn);
			});
		}

		var cancelEdit = document.getElementById('dleapi-key-cancel-edit');
		if (cancelEdit) {
			cancelEdit.addEventListener('click', function () {
				setEditMode(false);
			});
		}

		document.querySelectorAll('[data-dleapi-scope-all]').forEach(function (master) {
			master.addEventListener('change', function () {
				var flag = master.getAttribute('data-dleapi-scope-all');
				document.querySelectorAll('#dleapi-scopes-table [data-scope-flag="' + flag + '"]').forEach(function (el) {
					el.checked = master.checked;
				});
			});
		});

		var createOauth = document.getElementById('dleapi-create-oauth-form');
		if (createOauth) {
			createOauth.addEventListener('submit', function (e) {
				e.preventDefault();
				var fd = new FormData(createOauth);
				var id = parseInt(fd.get('id') || '0', 10);
				var submitBtn = document.getElementById('dleapi-oauth-submit') || createOauth.querySelector('[type="submit"]');
				var payload = {
					name: fd.get('name') || '',
					api_key_id: parseInt(fd.get('api_key_id') || '0', 10),
					redirect_uri: fd.get('redirect_uri') || '',
					grant_types: collectOauthGrants(createOauth)
				};
				if (id > 0) {
					payload.id = id;
					payload.active = fd.get('active') ? 1 : 0;
					post('update_oauth_client', payload, function (data) {
						updateOauthRow(data);
						setOauthEditMode(false);
					}, submitBtn);
					return;
				}
				post('create_oauth_client', payload, function (data) {
					resetOauthForm(createOauth);
					prependOauthRow(data);
					showOauthSecretBanner(data);
				}, submitBtn);
			});
		}

		var cancelOauth = document.getElementById('dleapi-oauth-cancel-edit');
		if (cancelOauth) {
			cancelOauth.addEventListener('click', function () {
				setOauthEditMode(false);
			});
		}

		var regenSecret = document.getElementById('dleapi-oauth-regen-secret');
		if (regenSecret) {
			regenSecret.addEventListener('click', function () {
				var idInput = document.getElementById('dleapi-oauth-id');
				var id = idInput ? parseInt(idInput.value || '0', 10) : 0;
				if (id < 1) {
					return;
				}
				if (!confirm(t('Пересоздать client_secret? Старый секрет сразу перестанет работать.'))) {
					return;
				}
				post('regenerate_oauth_client_secret', { id: id }, function (data) {
					showOauthSecretBanner(data);
				}, regenSecret);
			});
		}

		var dismissSecret = document.getElementById('dleapi-oauth-secret-dismiss');
		if (dismissSecret) {
			dismissSecret.addEventListener('click', function () {
				hideOauthSecretBanner();
			});
		}

		document.addEventListener('click', function (e) {
			var copyBtn = e.target.closest('[data-dleapi-copy-target]');
			if (copyBtn) {
				e.preventDefault();
				copyFieldById(copyBtn.getAttribute('data-dleapi-copy-target'));
				return;
			}

			var editOauth = e.target.closest('[data-dleapi-edit-oauth]');
			if (editOauth) {
				e.preventDefault();
				var oauthId = parseInt(editOauth.getAttribute('data-dleapi-edit-oauth'), 10);
				post('get_oauth_client', { id: oauthId }, function (data) {
					setOauthEditMode(true, data);
				}, editOauth);
				return;
			}

			var edit = e.target.closest('[data-dleapi-edit-key]');
			if (edit) {
				e.preventDefault();
				var editId = parseInt(edit.getAttribute('data-dleapi-edit-key'), 10);
				post('get_key', { id: editId }, function (data) {
					setEditMode(true, data);
				}, edit);
				return;
			}

			var del = e.target.closest('[data-dleapi-delete-key]');
			if (del) {
				e.preventDefault();
				if (!confirm(t('Удалить ключ?'))) {
					return;
				}
				var delId = parseInt(del.getAttribute('data-dleapi-delete-key'), 10);
				post('delete_key', { id: delId }, function () {
					removeKeyRow(delId);
				}, del);
				return;
			}

			var tog = e.target.closest('[data-dleapi-toggle-key]');
			if (tog) {
				e.preventDefault();
				var id = parseInt(tog.getAttribute('data-dleapi-toggle-key'), 10);
				var active = parseInt(tog.getAttribute('data-active'), 10);
				post('toggle_key', { id: id, active: active }, function () {
					tog.setAttribute('data-active', active ? '0' : '1');
					tog.textContent = active ? t('Отключить') : t('Включить');
					var row = document.querySelector('#dleapi-keys-tbody tr[data-key-id="' + id + '"]');
					if (row && row.children[3]) {
						row.children[3].textContent = yesNo(!!active);
					}
				}, tog);
				return;
			}

			var delOauth = e.target.closest('[data-dleapi-delete-oauth]');
			if (delOauth) {
				e.preventDefault();
				if (!confirm(t('Удалить клиент?'))) {
					return;
				}
				var oid = parseInt(delOauth.getAttribute('data-dleapi-delete-oauth'), 10);
				post('delete_oauth_client', { id: oid }, function () {
					var tr = delOauth.closest('tr');
					if (tr) {
						tr.remove();
					}
					var idInput = document.getElementById('dleapi-oauth-id');
					if (idInput && String(idInput.value) === String(oid)) {
						setOauthEditMode(false);
					}
					var tbody = oauthTbody();
					if (tbody && !tbody.querySelector('tr[data-oauth-id]') && !tbody.querySelector('.dleapi-oauth-empty')) {
						var empty = document.createElement('tr');
						empty.className = 'dleapi-oauth-empty';
						empty.innerHTML = '<td colspan="6">' + t('Клиентов пока нет') + '</td>';
						tbody.appendChild(empty);
					}
				}, delOauth);
				return;
			}

			var editLevel = e.target.closest('[data-dleapi-edit-level]');
			if (editLevel) {
				e.preventDefault();
				var lid = parseInt(editLevel.getAttribute('data-dleapi-edit-level'), 10);
				post('get_access_level', { id: lid }, function (data) {
					setLevelEditMode(true, data);
				}, editLevel);
				return;
			}

			var delLevel = e.target.closest('[data-dleapi-delete-level]');
			if (delLevel) {
				e.preventDefault();
				if (!confirm(t('Удалить уровень?'))) {
					return;
				}
				var did = parseInt(delLevel.getAttribute('data-dleapi-delete-level'), 10);
				post('delete_access_level', { id: did }, function () {
					var tr = delLevel.closest('tr');
					if (tr) {
						tr.remove();
					}
				}, delLevel);
				return;
			}

			var approveReq = e.target.closest('[data-dleapi-approve-request]');
			if (approveReq) {
				e.preventDefault();
				var aid = parseInt(approveReq.getAttribute('data-dleapi-approve-request'), 10);
				var aRow = approveReq.closest('tr');
				if (aRow) {
					aRow.querySelectorAll('button').forEach(function (b) {
						b.disabled = true;
					});
				}
				post('decide_key_request', { id: aid, approve: 1 }, function (data) {
					markRequestRow(aRow, data.status || 'approved');
				}, approveReq).then(function (res) {
					if (res && !res.success && res.error && (res.error.code === 'already_decided' || res.error.code === 'not_found')) {
						markRequestRow(aRow, (res.data && res.data.status) || '—');
					}
				});
				return;
			}

			var denyReq = e.target.closest('[data-dleapi-deny-request]');
			if (denyReq) {
				e.preventDefault();
				var nid = parseInt(denyReq.getAttribute('data-dleapi-deny-request'), 10);
				var nRow = denyReq.closest('tr');
				if (nRow) {
					nRow.querySelectorAll('button').forEach(function (b) {
						b.disabled = true;
					});
				}
				post('decide_key_request', { id: nid, approve: 0 }, function (data) {
					markRequestRow(nRow, data.status || 'denied');
				}, denyReq).then(function (res) {
					if (res && !res.success && res.error && (res.error.code === 'already_decided' || res.error.code === 'not_found')) {
						markRequestRow(nRow, (res.data && res.data.status) || '—');
					}
				});
				return;
			}
		});

		bindFormToggles();
		renderPlaceholderLists();
		bindPlaceholderClicks();
		bindLevelForm();
		bindAccessSyncForm();
	});

	function collectLevelScopes() {
		var tables = {};
		document.querySelectorAll('#dleapi-level-scopes-table [data-level-scope]').forEach(function (el) {
			var table = el.getAttribute('data-table');
			var flag = el.getAttribute('data-level-scope');
			if (!table || !flag) {
				return;
			}
			if (!tables[table]) {
				tables[table] = { read: 0, write: 0, edit: 0, delete: 0 };
			}
			if (el.checked) {
				tables[table][flag] = 1;
			}
		});
		return tables;
	}

	function clearLevelScopes() {
		document.querySelectorAll('#dleapi-level-scopes-table [data-level-scope]').forEach(function (el) {
			el.checked = false;
		});
	}

	function applyLevelScopes(map) {
		clearLevelScopes();
		map = map || {};
		Object.keys(map).forEach(function (table) {
			var flags = map[table] || {};
			['read', 'write', 'edit', 'delete'].forEach(function (flag) {
				var el = document.querySelector(
					'#dleapi-level-scopes-table [data-level-scope="' + flag + '"][data-table="' + table + '"]'
				);
				if (el) {
					el.checked = !!flags[flag];
				}
			});
		});
	}

	function setLevelEditMode(on, data) {
		var form = document.getElementById('dleapi-level-form');
		var title = document.getElementById('dleapi-level-form-title');
		var cancel = document.getElementById('dleapi-level-cancel');
		var idInput = document.getElementById('dleapi-level-id');
		if (!form || !idInput) {
			return;
		}
		if (!on) {
			idInput.value = '';
			form.reset();
			var active = form.querySelector('[name="active"]');
			if (active) {
				active.checked = true;
			}
			var own = form.querySelector('[name="own_only"]');
			if (own) {
				own.checked = true;
			}
			['mask_ip', 'mask_passwords', 'mask_personal'].forEach(function (n) {
				var el = form.querySelector('[name="' + n + '"]');
				if (el) {
					el.checked = true;
				}
			});
			clearLevelScopes();
			if (title) {
				setPanelCaption(title, attrLabel(title, 'data-label-create', 'Создать уровень'));
			}
			if (cancel) {
				cancel.hidden = true;
			}
			return;
		}
		data = data || {};
		idInput.value = String(data.id || '');
		form.querySelector('[name="name"]').value = String(data.name || '');
		form.querySelector('[name="sort"]').value = String(data.sort != null ? data.sort : 0);
		form.querySelector('[name="active"]').checked = data.active !== false && data.active !== 0;
		form.querySelector('[name="premoderate"]').checked = !!data.premoderate;
		form.querySelector('[name="own_only"]').checked = data.own_only !== false && data.own_only !== 0;
		form.querySelector('[name="cheater"]').checked = !!data.cheater;
		form.querySelector('[name="mask_ip"]').checked = data.mask_ip !== false && data.mask_ip !== 0;
		form.querySelector('[name="mask_passwords"]').checked = data.mask_passwords !== false && data.mask_passwords !== 0;
		form.querySelector('[name="mask_personal"]').checked = data.mask_personal !== false && data.mask_personal !== 0;
		applyLevelScopes(data.tables || {});
		if (title) {
			setPanelCaption(title, t('Уровень #{id}', { id: data.id }));
		}
		if (cancel) {
			cancel.hidden = false;
		}
		setFormBoxOpen(document.getElementById('dleapi-level-form-container'), true);
		form.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
	}

	function bindLevelForm() {
		var form = document.getElementById('dleapi-level-form');
		if (!form) {
			return;
		}
		form.addEventListener('submit', function (e) {
			e.preventDefault();
			var fd = new FormData(form);
			var payload = {
				id: parseInt(fd.get('id') || '0', 10),
				name: fd.get('name') || '',
				sort: parseInt(fd.get('sort') || '0', 10),
				active: fd.get('active') ? 1 : 0,
				premoderate: fd.get('premoderate') ? 1 : 0,
				own_only: fd.get('own_only') ? 1 : 0,
				cheater: fd.get('cheater') ? 1 : 0,
				mask_ip: fd.get('mask_ip') ? 1 : 0,
				mask_passwords: fd.get('mask_passwords') ? 1 : 0,
				mask_personal: fd.get('mask_personal') ? 1 : 0,
				tables: collectLevelScopes()
			};
			post('save_access_level', payload, function () {
				window.location.reload();
			}, document.getElementById('dleapi-level-submit'));
		});
		var cancel = document.getElementById('dleapi-level-cancel');
		if (cancel) {
			cancel.addEventListener('click', function () {
				setLevelEditMode(false);
			});
		}
	}

	function bindAccessSyncForm() {
		var form = document.getElementById('dleapi-access-sync-form');
		if (!form) {
			return;
		}
		form.addEventListener('submit', function (e) {
			e.preventDefault();
			var fd = new FormData(form);
			var map = {};
			fd.forEach(function (val, key) {
				var m = /^map\[(\d+)\]$/.exec(key);
				if (m) {
					map[m[1]] = parseInt(val || '0', 10);
				}
			});
			post('save_access_sync', { map: map }, function () {}, form.querySelector('[type="submit"]'));
		});
	}

	global.DleApiAdminSettings = global.DleApiAdminSettings || {
		renderPlaceholderLists: renderPlaceholderLists,
		bindPlaceholderClicks: bindPlaceholderClicks,
		insertPlaceholder: insertPlaceholder
	};
})(window);
