<div class="dleapi-profile-key" id="dleapi-profile-key" data-user-hash="{user-hash}" data-profile-user-id="{profile-id}" data-viewer-id="{viewer-id}" data-admin-mode="{admin-mode}">
	<h3 class="dleapi-profile-key__title">{title}</h3>
	<div class="dleapi-profile-key__state">
		<div class="dleapi-profile-key__side">
			<div class="dleapi-profile-key__icon" id="dleapi-profile-icon">&lt;/&gt;</div>
			<button type="button" class="btn" id="dleapi-profile-request-new" hidden>{new-label}</button>
		</div>
		<div class="dleapi-profile-key__body">
			<p class="dleapi-profile-key__status" id="dleapi-profile-status">{loading-label}</p>
			<div class="dleapi-profile-key__meta d-none" id="dleapi-profile-meta">
				<div class="dleapi-profile-key__meta-row">
					<span class="dleapi-profile-key__label">{key-label}</span>
					<span class="dleapi-profile-key__value">
						<input type="text" id="dleapi-profile-key-value" class="dleapi-profile-key__masked" readonly value="" data-real-key="">
					</span>
					<button type="button" class="btn" id="dleapi-profile-copy" data-copy-target="dleapi-profile-key-value">{copy-label}</button>
				</div>
				<div class="dleapi-profile-key__meta-row">
					<span class="dleapi-profile-key__label">{from-label}</span>
					<span class="dleapi-profile-key__value" id="dleapi-profile-valid-from">—</span>
				</div>
				<div class="dleapi-profile-key__meta-row">
					<span class="dleapi-profile-key__label">{until-label}</span>
					<span class="dleapi-profile-key__value" id="dleapi-profile-valid-to">—</span>
				</div>
				<div class="dleapi-profile-key__meta-row">
					<span class="dleapi-profile-key__label">{level-label}</span>
					<span class="dleapi-profile-key__value" id="dleapi-profile-level">—</span>
				</div>
			</div>
			<div class="dleapi-profile-key__actions">
				<button type="button" class="btn btn-primary" id="dleapi-profile-request"{request-hidden}>{request-label}</button>
				<button type="button" class="btn btn-success" id="dleapi-profile-approve" hidden>{approve-label}</button>
				<button type="button" class="btn btn-danger" id="dleapi-profile-deny" hidden>{deny-label}</button>
			</div>
			<div class="dleapi-profile-key__notice" id="dleapi-profile-notice"></div>
		</div>
	</div>
</div>
