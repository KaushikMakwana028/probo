<?php if ($this->session->flashdata('error')): ?>
	<div class="wr-toast wr-toast--error">
		<div class="wr-toast__icon">
			<svg width="18" height="18" viewBox="0 0 20 20" fill="none">
				<circle cx="10" cy="10" r="9" stroke="currentColor" stroke-width="2" />
				<path d="M10 6v4m0 4h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
			</svg>
		</div>
		<span><?php echo $this->session->flashdata('error'); ?></span>
		<button class="wr-toast__close" onclick="this.closest('.wr-toast').remove()">✕</button>
	</div>
<?php endif; ?>

<?php if ($this->session->flashdata('success')): ?>
	<div class="wr-toast wr-toast--success">
		<div class="wr-toast__icon">
			<svg width="18" height="18" viewBox="0 0 20 20" fill="none">
				<circle cx="10" cy="10" r="9" stroke="currentColor" stroke-width="2" />
				<path d="M6 10l3 3 5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
			</svg>
		</div>
		<span><?php echo $this->session->flashdata('success'); ?></span>
		<button class="wr-toast__close" onclick="this.closest('.wr-toast').remove()">✕</button>
	</div>
<?php endif; ?>

<script id="wr-data" type="application/json">
	<?php
	$withdrawalsJson = [];
	if (!empty($withdrawals)) {
		foreach ($withdrawals as $r) {
			$withdrawalsJson[] = [
				'id'                  => (int)$r->id,
				'user_name'           => html_escape($r->user_name),
				'user_mobile'         => html_escape($r->user_mobile),
				'amount'              => (float)$r->amount,
				'bank_name'           => html_escape($r->bank_name),
				'account_number'      => html_escape($r->account_number),
				'account_holder_name' => html_escape($r->account_holder_name),
				'ifsc_code'           => html_escape($r->ifsc_code),
				'status'              => html_escape($r->status),
				'admin_note'          => html_escape($r->admin_note),
			];
		}
	}
	echo json_encode($withdrawalsJson, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
	?>
</script>

<script id="wr-meta" type="application/json">
	{
		"total_requested": <?php echo (float)$total_requested; ?>,
		"pending_count": <?php echo (int)$pending_count; ?>,
		"approved_count": <?php echo (int)$approved_count; ?>,
		"total_count": <?php echo count($withdrawals); ?>,
		"base_url": "<?php echo site_url('admin/withdrawals'); ?>"
	}
</script>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<div class="wr-wrap" id="wr-app">

	<!-- HERO -->
	<div class="wr-hero">
		<div class="wr-hero__text">
			<p class="wr-hero__eyebrow">Admin · Finance</p>
			<h1 class="wr-hero__title">Withdrawal Requests</h1>
			<p class="wr-hero__sub">Review bank details and approve or reject user payout requests.</p>
		</div>
		<div class="wr-kpis">
			<div class="wr-kpi">
				<span class="wr-kpi__label">Total Requested</span>
				<strong class="wr-kpi__val" id="kpi-total">—</strong>
			</div>
			<div class="wr-kpi wr-kpi--amber">
				<span class="wr-kpi__label">Pending</span>
				<strong class="wr-kpi__val" id="kpi-pending">—</strong>
			</div>
			<div class="wr-kpi wr-kpi--green">
				<span class="wr-kpi__label">Approved</span>
				<strong class="wr-kpi__val" id="kpi-approved">—</strong>
			</div>
			<div class="wr-kpi wr-kpi--sky">
				<span class="wr-kpi__label">Total Records</span>
				<strong class="wr-kpi__val" id="kpi-count">—</strong>
			</div>
		</div>
	</div>

	<!-- TOOLBAR -->
	<div class="wr-toolbar">
		<div class="wr-search-wrap">
			<svg class="wr-search-ico" width="15" height="15" viewBox="0 0 20 20" fill="none">
				<circle cx="8.5" cy="8.5" r="5.5" stroke="currentColor" stroke-width="2" />
				<path d="M15 15l-3-3" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
			</svg>
			<input id="wr-search" class="wr-search" type="text"
				placeholder="Search user, bank, IFSC, account…" autocomplete="off">
			<button id="wr-search-clear" class="wr-search-clr">✕</button>
		</div>

		<div class="wr-toolbar__bottom">
			<div class="wr-tabs" id="wr-tabs">
				<button class="wr-tab wr-tab--on" data-status="all">All</button>
				<button class="wr-tab" data-status="pending">Pending</button>
				<button class="wr-tab" data-status="approved">Approved</button>
				<button class="wr-tab" data-status="rejected">Rejected</button>
			</div>
			<div class="wr-rows" id="wr-rows">
				<button class="wr-rows__btn" id="wr-rows-btn">
					<svg width="13" height="13" viewBox="0 0 16 16" fill="none">
						<path d="M2 4h12M2 8h12M2 12h7" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
					</svg>
					<span id="wr-rows-lbl">10 rows</span>
					<svg width="10" height="10" viewBox="0 0 12 12" fill="none">
						<path d="M3 4.5l3 3 3-3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
					</svg>
				</button>
				<div class="wr-rows__drop" id="wr-rows-drop">
					<button class="wr-rows__opt wr-rows__opt--on" data-rows="10">10 rows</button>
					<button class="wr-rows__opt" data-rows="25">25 rows</button>
					<button class="wr-rows__opt" data-rows="50">50 rows</button>
					<button class="wr-rows__opt" data-rows="100">100 rows</button>
					<button class="wr-rows__opt" data-rows="all">All rows</button>
				</div>
			</div>
		</div>
	</div>

	<p class="wr-info" id="wr-info"></p>

	<!-- DESKTOP TABLE -->
	<div class="wr-tbox" id="wr-tbox">
		<div class="wr-tscroll">
			<table class="wr-table" id="wr-table">
				<thead>
					<tr>
						<th class="srt" data-col="user_name">User <span class="arr"></span></th>
						<th class="srt" data-col="amount">Amount <span class="arr"></span></th>
						<th class="srt" data-col="bank_name">Bank <span class="arr"></span></th>
						<th>Account</th>
						<th>IFSC</th>
						<th class="srt" data-col="status">Status <span class="arr"></span></th>
						<th class="th-act">Action</th>
					</tr>
				</thead>
				<tbody id="wr-tbody"></tbody>
			</table>
		</div>
	</div>

	<!-- MOBILE CARDS -->
	<div class="wr-cards" id="wr-cards"></div>

	<!-- EMPTY STATE -->
	<div class="wr-empty" id="wr-empty">
		<div class="wr-empty__ico">📭</div>
		<h3>No results found</h3>
		<p>Try adjusting your search or filter.</p>
	</div>

	<!-- PAGINATION -->
	<div class="wr-pager" id="wr-pager"></div>
</div>

<!-- MODAL -->
<div class="wr-mbg" id="wr-modal">
	<div class="wr-modal">
		<div class="wr-modal__head">
			<h3 id="wr-modal-title">Approve Request</h3>
			<button class="wr-modal__x" id="wr-modal-close">✕</button>
		</div>
		<div class="wr-modal__body">
			<div class="wr-modal__sum" id="wr-modal-sum"></div>
			<label class="wr-modal__lbl">
				Admin Remark <span style="opacity:.45;font-weight:400">(optional)</span>
			</label>
			<textarea class="wr-modal__ta" id="wr-modal-remark" rows="3"
				placeholder="Note visible to the user…"></textarea>
		</div>
		<div class="wr-modal__foot">
			<button class="wr-btn wr-btn--ghost" id="wr-modal-cancel">Cancel</button>
			<button class="wr-btn" id="wr-modal-ok">Confirm</button>
		</div>
	</div>
</div>

<!-- ═══════ STYLES ═══════ -->
<style>
	*,
	*::before,
	*::after {
		box-sizing: border-box;
		margin: 0;
		padding: 0
	}

	:root {
		--f: 'Roboto', sans-serif;
		--bg: #f1f5f9;
		--card: #fff;
		--bdr: #e2e8f0;
		--txt: #0f172a;
		--mut: #64748b;
		--fnt: #94a3b8;
		--blu: #2563eb;
		--blu-lt: #eff6ff;
		--grn: #16a34a;
		--grn-lt: #f0fdf4;
		--amb: #d97706;
		--amb-lt: #fffbeb;
		--red: #dc2626;
		--red-lt: #fef2f2;
		--r: 12px;
		--rs: 8px;
		--sh: 0 1px 4px rgba(0, 0, 0, .07), 0 4px 16px rgba(0, 0, 0, .05);
		--shd: 0 4px 16px rgba(0, 0, 0, .09), 0 12px 36px rgba(0, 0, 0, .08);
	}

	body,
	* {
		font-family: var(--f)
	}

	/* WRAP */
	.wr-wrap {
		background: var(--bg);
		min-height: 100vh;
		padding: 16px 12px;
		display: flex;
		flex-direction: column;
		gap: 14px;
	}

	/* TOASTS */
	.wr-toast {
		display: flex;
		align-items: center;
		gap: 10px;
		padding: 12px 15px;
		border-radius: var(--rs);
		font-size: 14px;
		font-weight: 500;
		box-shadow: var(--sh);
		margin-bottom: 4px;
		animation: tIn .3s ease;
	}

	.wr-toast--error {
		background: var(--red-lt);
		color: #7f1d1d;
		border: 1px solid #fecaca
	}

	.wr-toast--success {
		background: var(--grn-lt);
		color: #14532d;
		border: 1px solid #bbf7d0
	}

	.wr-toast span {
		flex: 1
	}

	.wr-toast__close {
		background: none;
		border: none;
		cursor: pointer;
		color: inherit;
		opacity: .6;
		padding: 3px 6px;
		border-radius: 4px;
		font-size: 12px;
		transition: opacity .2s
	}

	.wr-toast__close:hover {
		opacity: 1
	}

	@keyframes tIn {
		from {
			opacity: 0;
			transform: translateY(-10px)
		}

		to {
			opacity: 1;
			transform: none
		}
	}

	/* HERO */
	.wr-hero {
		background: linear-gradient(135deg, #0f1e3d 0%, #1d40af 55%, #2563eb 100%);
		border-radius: var(--r);
		padding: 22px 18px;
		color: #fff;
		box-shadow: 0 8px 32px rgba(37, 99, 235, .25);
		display: flex;
		flex-direction: column;
		gap: 18px;
	}

	.wr-hero__eyebrow {
		font-size: 10px;
		font-weight: 500;
		letter-spacing: .1em;
		text-transform: uppercase;
		color: rgba(255, 255, 255, .5);
		margin-bottom: 7px;
	}

	.wr-hero__title {
		font-size: 22px;
		font-weight: 700;
		line-height: 1.2;
		margin-bottom: 7px;
	}

	.wr-hero__sub {
		font-size: 13px;
		color: rgba(255, 255, 255, .7);
		line-height: 1.6;
		font-weight: 400;
	}

	/* 2-col kpi grid on mobile, 4-col on desktop */
	.wr-kpis {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 9px;
	}

	.wr-kpi {
		background: rgba(255, 255, 255, .11);
		border: 1px solid rgba(255, 255, 255, .17);
		border-radius: 9px;
		padding: 12px 14px;
		display: flex;
		flex-direction: column;
		gap: 4px;
	}

	.wr-kpi__label {
		font-size: 9px;
		font-weight: 600;
		letter-spacing: .09em;
		text-transform: uppercase;
		color: rgba(255, 255, 255, .55);
	}

	.wr-kpi__val {
		font-size: 18px;
		font-weight: 700;
		color: #fff;
		word-break: break-all
	}

	.wr-kpi--amber .wr-kpi__val {
		color: #fde68a
	}

	.wr-kpi--green .wr-kpi__val {
		color: #86efac
	}

	.wr-kpi--sky .wr-kpi__val {
		color: #bae6fd
	}

	/* TOOLBAR */
	.wr-toolbar {
		background: var(--card);
		border: 1px solid var(--bdr);
		border-radius: var(--r);
		padding: 12px 14px;
		display: flex;
		flex-direction: column;
		gap: 10px;
		box-shadow: var(--sh);
	}

	/* search */
	.wr-search-wrap {
		position: relative;
		display: flex;
		align-items: center
	}

	.wr-search-ico {
		position: absolute;
		left: 11px;
		color: var(--fnt);
		pointer-events: none;
		flex-shrink: 0;
	}

	.wr-search {
		width: 100%;
		padding: 10px 34px 10px 34px;
		border: 1.5px solid var(--bdr);
		border-radius: var(--rs);
		font-family: var(--f);
		font-size: 14px;
		color: var(--txt);
		background: #f8fafc;
		outline: none;
		transition: border-color .2s, box-shadow .2s;
	}

	.wr-search:focus {
		border-color: var(--blu);
		box-shadow: 0 0 0 3px rgba(37, 99, 235, .1);
		background: #fff;
	}

	.wr-search::placeholder {
		color: var(--fnt)
	}

	.wr-search-clr {
		position: absolute;
		right: 9px;
		background: none;
		border: none;
		cursor: pointer;
		color: var(--fnt);
		font-size: 11px;
		padding: 4px 6px;
		border-radius: 4px;
		display: none;
		transition: color .2s;
	}

	.wr-search-clr:hover {
		color: var(--txt)
	}

	.wr-search-clr.show {
		display: block
	}

	/* toolbar bottom row */
	.wr-toolbar__bottom {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 8px;
		flex-wrap: wrap;
	}

	/* tabs */
	.wr-tabs {
		display: flex;
		background: #f1f5f9;
		border: 1px solid var(--bdr);
		border-radius: var(--rs);
		padding: 3px;
		gap: 2px;
		flex-wrap: wrap;
		flex: 1;
		min-width: 0;
	}

	.wr-tab {
		border: none;
		background: none;
		cursor: pointer;
		font-family: var(--f);
		font-size: 12px;
		font-weight: 500;
		color: var(--mut);
		padding: 6px 10px;
		border-radius: 6px;
		transition: all .18s;
		white-space: nowrap;
	}

	.wr-tab:hover {
		color: var(--txt);
		background: rgba(0, 0, 0, .04)
	}

	.wr-tab--on {
		background: #fff;
		color: var(--blu);
		font-weight: 600;
		box-shadow: 0 1px 3px rgba(0, 0, 0, .1);
	}

	/* rows dropdown */
	.wr-rows {
		position: relative;
		flex-shrink: 0
	}

	.wr-rows__btn {
		display: inline-flex;
		align-items: center;
		gap: 5px;
		padding: 7px 11px;
		border: 1.5px solid var(--bdr);
		border-radius: var(--rs);
		background: var(--card);
		font-family: var(--f);
		font-size: 12px;
		font-weight: 600;
		color: var(--txt);
		cursor: pointer;
		white-space: nowrap;
		transition: border-color .2s;
	}

	.wr-rows__btn:hover {
		border-color: var(--blu)
	}

	.wr-rows__drop {
		position: absolute;
		right: 0;
		top: calc(100% + 5px);
		background: var(--card);
		border: 1px solid var(--bdr);
		border-radius: var(--rs);
		box-shadow: var(--shd);
		padding: 4px;
		min-width: 115px;
		z-index: 300;
		display: none;
	}

	.wr-rows__drop.open {
		display: block;
		animation: dIn .14s ease
	}

	@keyframes dIn {
		from {
			opacity: 0;
			transform: translateY(-5px)
		}

		to {
			opacity: 1;
			transform: none
		}
	}

	.wr-rows__opt {
		display: block;
		width: 100%;
		padding: 8px 13px;
		border: none;
		background: none;
		font-family: var(--f);
		font-size: 13px;
		color: var(--mut);
		cursor: pointer;
		border-radius: 6px;
		text-align: left;
		transition: background .13s, color .13s;
	}

	.wr-rows__opt:hover {
		background: #f1f5f9;
		color: var(--txt)
	}

	.wr-rows__opt--on {
		color: var(--blu);
		font-weight: 700
	}

	/* RESULT INFO */
	.wr-info {
		font-size: 12px;
		color: var(--mut);
		padding: 0 2px;
		font-weight: 400;
		min-height: 15px;
	}

	/* DESKTOP TABLE — hidden on mobile */
	.wr-tbox {
		background: var(--card);
		border: 1px solid var(--bdr);
		border-radius: var(--r);
		box-shadow: var(--sh);
		overflow: hidden;
		display: none;
	}

	.wr-tscroll {
		overflow-x: auto;
		-webkit-overflow-scrolling: touch
	}

	.wr-table {
		width: 100%;
		border-collapse: collapse;
		font-size: 14px
	}

	.wr-table thead tr {
		background: #f8fafc;
		border-bottom: 1px solid var(--bdr)
	}

	.wr-table th {
		padding: 11px 14px;
		text-align: left;
		font-size: 11px;
		font-weight: 700;
		letter-spacing: .07em;
		text-transform: uppercase;
		color: var(--mut);
		white-space: nowrap;
	}

	.wr-table th.srt {
		cursor: pointer;
		user-select: none
	}

	.wr-table th.srt:hover {
		color: var(--blu)
	}

	.wr-table th .arr {
		font-size: 10px;
		opacity: .35
	}

	.wr-table th.sa .arr::after {
		content: '↑';
		color: var(--blu);
		opacity: 1
	}

	.wr-table th.sd .arr::after {
		content: '↓';
		color: var(--blu);
		opacity: 1
	}

	.th-act {
		text-align: center
	}

	.wr-table tbody tr {
		border-bottom: 1px solid var(--bdr);
		transition: background .13s
	}

	.wr-table tbody tr:last-child {
		border-bottom: none
	}

	.wr-table tbody tr:hover {
		background: #f7f9ff
	}

	.wr-table td {
		padding: 13px 14px;
		vertical-align: middle;
		font-size: 14px;
		color: var(--txt)
	}

	.td-u strong {
		display: block;
		font-weight: 600;
		margin-bottom: 2px
	}

	.td-u small {
		font-size: 12px;
		color: var(--mut)
	}

	.td-mono {
		font-size: 12px;
		color: var(--mut)
	}

	.td-amt {
		font-weight: 700;
		font-size: 15px;
		color: var(--blu)
	}

	.td-act {
		text-align: center
	}

	.td-note {
		font-size: 12px;
		color: var(--mut);
		font-style: italic
	}

	/* MOBILE CARDS — visible on mobile */
	.wr-cards {
		display: flex;
		flex-direction: column;
		gap: 11px
	}

	.wr-card {
		background: var(--card);
		border: 1px solid var(--bdr);
		border-radius: var(--r);
		box-shadow: var(--sh);
		overflow: hidden;
	}

	.wr-card__top {
		padding: 13px 15px;
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 8px;
		border-bottom: 1px solid var(--bdr);
	}

	.wr-card__usr strong {
		display: block;
		font-size: 15px;
		font-weight: 600;
		color: var(--txt);
		margin-bottom: 2px;
	}

	.wr-card__usr small {
		font-size: 12px;
		color: var(--mut)
	}

	.wr-card__amt {
		font-size: 17px;
		font-weight: 700;
		color: var(--blu);
		white-space: nowrap;
	}

	.wr-card__body {
		padding: 11px 15px;
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 9px 14px;
	}

	.wr-card__fl {}

	.wr-card__fl-lbl {
		display: block;
		font-size: 10px;
		font-weight: 700;
		letter-spacing: .08em;
		text-transform: uppercase;
		color: var(--fnt);
		margin-bottom: 3px;
	}

	.wr-card__fl-val {
		font-size: 13px;
		font-weight: 500;
		color: var(--txt);
		word-break: break-all;
	}

	.wr-card__fl-sub {
		font-size: 11px;
		color: var(--mut);
		margin-top: 2px
	}

	.wr-card__bot {
		padding: 11px 15px;
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 8px;
		background: #fafbfc;
		border-top: 1px solid var(--bdr);
		flex-wrap: wrap;
	}

	.wr-card__note {
		font-size: 12px;
		color: var(--mut);
		font-style: italic;
		flex: 1
	}

	.wr-card__acts {
		display: flex;
		gap: 7px;
		flex-wrap: wrap
	}

	/* PILL */
	.wr-pill {
		display: inline-flex;
		align-items: center;
		padding: 4px 9px;
		border-radius: 999px;
		font-size: 11px;
		font-weight: 700;
		letter-spacing: .04em;
		text-transform: uppercase;
		white-space: nowrap;
	}

	.wr-pill--pending {
		background: #fef3c7;
		color: #92400e;
		border: 1px solid #fcd34d
	}

	.wr-pill--approved {
		background: #dcfce7;
		color: #166534;
		border: 1px solid #86efac
	}

	.wr-pill--rejected {
		background: #fee2e2;
		color: #991b1b;
		border: 1px solid #fca5a5
	}

	/* BUTTONS */
	.wr-btn {
		display: inline-flex;
		align-items: center;
		gap: 5px;
		padding: 8px 13px;
		border-radius: var(--rs);
		border: 1.5px solid transparent;
		font-family: var(--f);
		font-size: 13px;
		font-weight: 600;
		cursor: pointer;
		transition: all .18s;
		white-space: nowrap;
		text-decoration: none;
	}

	.wr-btn--approve {
		background: var(--grn-lt);
		color: var(--grn);
		border-color: #86efac
	}

	.wr-btn--approve:hover {
		background: #bbf7d0;
		transform: translateY(-1px);
		box-shadow: 0 3px 10px rgba(22, 163, 74, .2)
	}

	.wr-btn--reject {
		background: var(--red-lt);
		color: var(--red);
		border-color: #fca5a5
	}

	.wr-btn--reject:hover {
		background: #fecaca;
		transform: translateY(-1px);
		box-shadow: 0 3px 10px rgba(220, 38, 38, .2)
	}

	.wr-btn--ghost {
		background: #f1f5f9;
		color: var(--mut);
		border-color: var(--bdr)
	}

	.wr-btn--ghost:hover {
		background: var(--bdr);
		color: var(--txt)
	}

	/* EMPTY */
	.wr-empty {
		display: none;
		text-align: center;
		padding: 60px 20px;
		background: var(--card);
		border: 1px solid var(--bdr);
		border-radius: var(--r);
		box-shadow: var(--sh);
	}

	.wr-empty__ico {
		font-size: 48px;
		margin-bottom: 12px
	}

	.wr-empty h3 {
		font-size: 18px;
		font-weight: 700;
		color: var(--txt);
		margin-bottom: 6px
	}

	.wr-empty p {
		font-size: 14px;
		color: var(--mut)
	}

	/* PAGINATION */
	.wr-pager {
		display: flex;
		align-items: center;
		justify-content: center;
		gap: 5px;
		flex-wrap: wrap;
		padding: 4px 0 8px;
	}

	.pg {
		min-width: 36px;
		height: 36px;
		padding: 0 10px;
		border-radius: var(--rs);
		border: 1.5px solid var(--bdr);
		background: var(--card);
		font-family: var(--f);
		font-size: 13px;
		font-weight: 600;
		color: var(--mut);
		cursor: pointer;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		transition: all .18s;
	}

	.pg:hover:not(:disabled) {
		border-color: var(--blu);
		color: var(--blu)
	}

	.pg.on {
		background: var(--blu);
		color: #fff;
		border-color: var(--blu);
		box-shadow: 0 2px 8px rgba(37, 99, 235, .3)
	}

	.pg:disabled {
		opacity: .35;
		cursor: default
	}

	.pg-dot {
		font-size: 14px;
		color: var(--fnt);
		padding: 0 3px
	}

	/* MODAL */
	.wr-mbg {
		position: fixed;
		inset: 0;
		background: rgba(0, 0, 0, .5);
		backdrop-filter: blur(2px);
		display: none;
		align-items: center;
		justify-content: center;
		z-index: 9999;
		padding: 14px;
	}

	.wr-mbg.open {
		display: flex;
		animation: fbg .2s ease
	}

	@keyframes fbg {
		from {
			opacity: 0
		}

		to {
			opacity: 1
		}
	}

	.wr-modal {
		background: var(--card);
		border-radius: 16px;
		width: 100%;
		max-width: 430px;
		max-height: 90vh;
		overflow-y: auto;
		box-shadow: 0 24px 64px rgba(0, 0, 0, .22);
		animation: mpop .24s cubic-bezier(.16, 1, .3, 1);
	}

	@keyframes mpop {
		from {
			opacity: 0;
			transform: scale(.93) translateY(12px)
		}

		to {
			opacity: 1;
			transform: none
		}
	}

	.wr-modal__head {
		display: flex;
		align-items: center;
		justify-content: space-between;
		padding: 17px 19px;
		border-bottom: 1px solid var(--bdr);
		position: sticky;
		top: 0;
		background: var(--card);
		z-index: 1;
	}

	.wr-modal__head h3 {
		font-size: 15px;
		font-weight: 700;
		color: var(--txt)
	}

	.wr-modal__x {
		background: none;
		border: none;
		cursor: pointer;
		color: var(--mut);
		font-size: 13px;
		padding: 5px 7px;
		border-radius: 6px;
		transition: background .2s;
	}

	.wr-modal__x:hover {
		background: #f1f5f9
	}

	.wr-modal__body {
		padding: 16px 19px
	}

	.wr-modal__sum {
		background: #f8fafc;
		border: 1px solid var(--bdr);
		border-radius: var(--rs);
		padding: 11px 13px;
		margin-bottom: 13px;
		font-size: 13px;
		color: var(--mut);
		line-height: 1.85;
	}

	.wr-modal__sum strong {
		color: var(--txt);
		font-weight: 600
	}

	.wr-modal__lbl {
		display: block;
		font-size: 11px;
		font-weight: 700;
		text-transform: uppercase;
		letter-spacing: .08em;
		color: var(--mut);
		margin-bottom: 7px;
	}

	.wr-modal__ta {
		width: 100%;
		padding: 9px 12px;
		border: 1.5px solid var(--bdr);
		border-radius: var(--rs);
		font-family: var(--f);
		font-size: 14px;
		color: var(--txt);
		resize: vertical;
		outline: none;
		transition: border-color .2s;
	}

	.wr-modal__ta:focus {
		border-color: var(--blu);
		box-shadow: 0 0 0 3px rgba(37, 99, 235, .1)
	}

	.wr-modal__foot {
		display: flex;
		gap: 8px;
		justify-content: flex-end;
		padding: 13px 19px;
		border-top: 1px solid var(--bdr);
		background: #fafbfc;
	}

	#wr-modal-ok {
		background: var(--blu);
		color: #fff;
		border-color: var(--blu)
	}

	#wr-modal-ok:hover {
		background: #1d4ed8
	}

	#wr-modal-ok.rm {
		background: var(--red);
		border-color: var(--red)
	}

	#wr-modal-ok.rm:hover {
		background: #b91c1c
	}

	/* ═══ RESPONSIVE ═══ */

	/* tiny phones */
	@media(max-width:380px) {
		.wr-hero__title {
			font-size: 19px
		}

		.wr-kpi__val {
			font-size: 16px
		}

		.wr-tab {
			padding: 5px 8px;
			font-size: 11px
		}

		.wr-card__amt {
			font-size: 15px
		}

		.wr-btn {
			padding: 7px 10px;
			font-size: 12px
		}
	}

	/* phablet: side-by-side hero */
	@media(min-width:540px) {
		.wr-wrap {
			padding: 20px 16px
		}

		.wr-hero {
			flex-direction: row;
			align-items: flex-start;
			justify-content: space-between;
			padding: 26px 22px;
		}

		.wr-hero__text {
			flex: 1
		}

		.wr-hero__title {
			font-size: 26px
		}

		.wr-kpis {
			width: 240px;
			flex-shrink: 0;
			grid-template-columns: 1fr 1fr;
		}
	}

	/* tablet+: show table, hide cards */
	@media(min-width:768px) {
		.wr-wrap {
			padding: 24px 20px
		}

		.wr-tbox {
			display: block !important
		}

		.wr-cards {
			display: none !important
		}

		.wr-toolbar {
			flex-direction: row;
			align-items: center
		}

		.wr-search-wrap {
			flex: 1
		}

		.wr-toolbar__bottom {
			flex: 0 0 auto;
			flex-wrap: nowrap
		}

		.wr-tabs {
			flex: 0 0 auto
		}
	}

	/* desktop */
	@media(min-width:1024px) {
		.wr-wrap {
			padding: 28px 24px;
			gap: 18px
		}

		.wr-hero__title {
			font-size: 30px
		}

		.wr-hero {
			padding: 30px 28px
		}

		.wr-kpis {
			width: auto;
			grid-template-columns: repeat(4, minmax(110px, 1fr));
		}
	}
</style>

<!-- ═══════ SCRIPT ═══════ -->
<script>
	(function() {
		const RAW = JSON.parse(document.getElementById('wr-data').textContent);
		const META = JSON.parse(document.getElementById('wr-meta').textContent);
		const BASE = META.base_url;

		let S = {
			q: '',
			status: 'all',
			rpp: 10,
			page: 1,
			col: null,
			dir: 'asc'
		};

		const g = id => document.getElementById(id);
		const $tbody = g('wr-tbody');
		const $cards = g('wr-cards');
		const $empty = g('wr-empty');
		const $tbox = g('wr-tbox');
		const $pager = g('wr-pager');
		const $info = g('wr-info');
		const $search = g('wr-search');
		const $clr = g('wr-search-clear');
		const $tabs = g('wr-tabs');
		const $rowsBtn = g('wr-rows-btn');
		const $rowsLbl = g('wr-rows-lbl');
		const $rowsDrp = g('wr-rows-drop');
		const $modal = g('wr-modal');
		const $mTitle = g('wr-modal-title');
		const $mSum = g('wr-modal-sum');
		const $mRmk = g('wr-modal-remark');
		const $mOk = g('wr-modal-ok');

		/* KPIs */
		g('kpi-total').textContent = 'Rs ' + fmt(META.total_requested);
		g('kpi-pending').textContent = META.pending_count;
		g('kpi-approved').textContent = META.approved_count;
		g('kpi-count').textContent = META.total_count;

		function fmt(n) {
			return parseFloat(n).toLocaleString('en-IN', {
				minimumFractionDigits: 2,
				maximumFractionDigits: 2
			});
		}

		function esc(s) {
			return String(s)
				.replace(/&/g, '&amp;').replace(/</g, '&lt;')
				.replace(/>/g, '&gt;').replace(/"/g, '&quot;');
		}

		function cap(s) {
			return s.charAt(0).toUpperCase() + s.slice(1)
		}

		function defNote(st) {
			return st === 'approved' ?
				'Approved and marked for bank transfer.' :
				'Request was rejected.';
		}

		/* filter + sort */
		function getFiltered() {
			let d = RAW.slice();
			const q = S.q.toLowerCase();
			if (q) d = d.filter(r =>
				r.user_name.toLowerCase().includes(q) ||
				r.user_mobile.includes(q) ||
				r.bank_name.toLowerCase().includes(q) ||
				r.account_number.includes(q) ||
				r.account_holder_name.toLowerCase().includes(q) ||
				r.ifsc_code.toLowerCase().includes(q)
			);
			if (S.status !== 'all') d = d.filter(r => r.status === S.status);
			if (S.col) {
				d.sort((a, b) => {
					let av = a[S.col],
						bv = b[S.col];
					if (typeof av === 'string') {
						av = av.toLowerCase();
						bv = bv.toLowerCase();
					}
					return av < bv ? (S.dir === 'asc' ? -1 : 1) : av > bv ? (S.dir === 'asc' ? 1 : -1) : 0;
				});
			}
			return d;
		}

		/* render */
		function render() {
			const data = getFiltered();
			const total = data.length;
			const rpp = S.rpp === 'all' ? Math.max(total, 1) : S.rpp;
			const pages = Math.max(1, Math.ceil(total / rpp));
			if (S.page > pages) S.page = 1;
			const start = (S.page - 1) * rpp;
			const slice = data.slice(start, start + rpp);

			$info.textContent = total === 0 ?
				'No results' :
				`Showing ${start+1}–${Math.min(start+rpp,total)} of ${total} record${total!==1?'s':''}`;

			if (slice.length === 0) {
				$empty.style.display = 'block';
				$tbox.style.display = 'none';
				$cards.innerHTML = '';
				$tbody.innerHTML = '';
			} else {
				$empty.style.display = 'none';
				/* desktop table visibility controlled by CSS media query */
				$tbody.innerHTML = slice.map(tableRow).join('');
				$cards.innerHTML = slice.map(mobileCard).join('');
			}
			renderPager(pages);
		}

		/* table row */
		function tableRow(r) {
			const pill = `<span class="wr-pill wr-pill--${r.status}">${cap(r.status)}</span>`;
			let act;
			if (r.status === 'pending') {
				act = `<div style="display:inline-flex;gap:6px;justify-content:center">
				<button class="wr-btn wr-btn--approve" onclick="openModal(${r.id},'approve')">
					<svg width="11" height="11" viewBox="0 0 16 16" fill="none"><path d="M3 8l4 4 6-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>Approve
				</button>
				<button class="wr-btn wr-btn--reject" onclick="openModal(${r.id},'reject')">
					<svg width="11" height="11" viewBox="0 0 16 16" fill="none"><path d="M12 4L4 12M4 4l8 8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>Reject
				</button>
			</div>`;
			} else {
				act = `<span class="td-note">${esc(r.admin_note||defNote(r.status))}</span>`;
			}
			return `<tr>
			<td class="td-u"><strong>${esc(r.user_name)}</strong><small>📱 ${esc(r.user_mobile)}</small></td>
			<td><span class="td-amt">Rs ${fmt(r.amount)}</span></td>
			<td>${esc(r.bank_name)}</td>
			<td><span class="td-mono">${esc(r.account_number)}</span><br>
				<small style="font-size:11px;color:var(--mut)">${esc(r.account_holder_name)}</small></td>
			<td class="td-mono">${esc(r.ifsc_code)}</td>
			<td>${pill}</td>
			<td class="td-act">${act}</td>
		</tr>`;
		}

		/* mobile card */
		function mobileCard(r) {
			const pill = `<span class="wr-pill wr-pill--${r.status}">${cap(r.status)}</span>`;
			let bot;
			if (r.status === 'pending') {
				bot = `<div class="wr-card__acts">
				<button class="wr-btn wr-btn--approve" onclick="openModal(${r.id},'approve')">
					<svg width="11" height="11" viewBox="0 0 16 16" fill="none"><path d="M3 8l4 4 6-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>Approve
				</button>
				<button class="wr-btn wr-btn--reject" onclick="openModal(${r.id},'reject')">
					<svg width="11" height="11" viewBox="0 0 16 16" fill="none"><path d="M12 4L4 12M4 4l8 8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>Reject
				</button>
			</div>`;
			} else {
				bot = `<p class="wr-card__note">${esc(r.admin_note||defNote(r.status))}</p>`;
			}
			return `<div class="wr-card">
			<div class="wr-card__top">
				<div class="wr-card__usr">
					<strong>${esc(r.user_name)}</strong>
					<small>📱 ${esc(r.user_mobile)}</small>
				</div>
				<span class="wr-card__amt">Rs ${fmt(r.amount)}</span>
			</div>
			<div class="wr-card__body">
				<div class="wr-card__fl">
					<span class="wr-card__fl-lbl">🏦 Bank</span>
					<span class="wr-card__fl-val">${esc(r.bank_name)}</span>
				</div>
				<div class="wr-card__fl">
					<span class="wr-card__fl-lbl">🏷️ IFSC</span>
					<span class="wr-card__fl-val">${esc(r.ifsc_code)}</span>
				</div>
				<div class="wr-card__fl" style="grid-column:1/-1">
					<span class="wr-card__fl-lbl">🔢 Account</span>
					<span class="wr-card__fl-val">${esc(r.account_number)}</span>
					<div class="wr-card__fl-sub">${esc(r.account_holder_name)}</div>
				</div>
			</div>
			<div class="wr-card__bot">
				${pill}
				${bot}
			</div>
		</div>`;
		}

		/* pagination */
		function renderPager(totalPages) {
			if (totalPages <= 1) {
				$pager.innerHTML = '';
				return;
			}
			const p = S.page;
			let html = `<button class="pg" ${p===1?'disabled':''} onclick="gp(${p-1})">&#8592;</button>`;
			pageRange(p, totalPages).forEach(n => {
				if (n === '…') html += `<span class="pg-dot">…</span>`;
				else html += `<button class="pg ${n===p?'on':''}" onclick="gp(${n})">${n}</button>`;
			});
			html += `<button class="pg" ${p===totalPages?'disabled':''} onclick="gp(${p+1})">&#8594;</button>`;
			$pager.innerHTML = html;
		}

		function pageRange(c, t) {
			if (t <= 7) return Array.from({
				length: t
			}, (_, i) => i + 1);
			const r = [1];
			if (c > 3) r.push('…');
			for (let i = Math.max(2, c - 1); i <= Math.min(t - 1, c + 1); i++) r.push(i);
			if (c < t - 2) r.push('…');
			r.push(t);
			return r;
		}
		window.gp = p => {
			S.page = p;
			render();
			window.scrollTo({
				top: 0,
				behavior: 'smooth'
			})
		};

		/* search */
		$search.addEventListener('input', function() {
			S.q = this.value;
			S.page = 1;
			$clr.classList.toggle('show', this.value.length > 0);
			render();
		});
		$clr.addEventListener('click', () => {
			$search.value = '';
			S.q = '';
			S.page = 1;
			$clr.classList.remove('show');
			render();
			$search.focus();
		});

		/* tabs */
		$tabs.addEventListener('click', e => {
			const t = e.target.closest('.wr-tab');
			if (!t) return;
			$tabs.querySelectorAll('.wr-tab').forEach(x => x.classList.remove('wr-tab--on'));
			t.classList.add('wr-tab--on');
			S.status = t.dataset.status;
			S.page = 1;
			render();
		});

		/* rows */
		$rowsBtn.addEventListener('click', e => {
			e.stopPropagation();
			$rowsDrp.classList.toggle('open');
		});
		document.addEventListener('click', () => $rowsDrp.classList.remove('open'));
		$rowsDrp.addEventListener('click', e => {
			const o = e.target.closest('.wr-rows__opt');
			if (!o) return;
			$rowsDrp.querySelectorAll('.wr-rows__opt').forEach(x => x.classList.remove('wr-rows__opt--on'));
			o.classList.add('wr-rows__opt--on');
			const v = o.dataset.rows;
			S.rpp = v === 'all' ? 'all' : parseInt(v);
			$rowsLbl.textContent = o.textContent;
			S.page = 1;
			$rowsDrp.classList.remove('open');
			render();
		});

		/* sort */
		document.querySelectorAll('.wr-table th.srt').forEach(th => {
			th.addEventListener('click', function() {
				const col = this.dataset.col;
				if (S.col === col) S.dir = S.dir === 'asc' ? 'desc' : 'asc';
				else {
					S.col = col;
					S.dir = 'asc';
				}
				document.querySelectorAll('.wr-table th.srt')
					.forEach(t => t.classList.remove('sa', 'sd'));
				this.classList.add(S.dir === 'asc' ? 'sa' : 'sd');
				render();
			});
		});

		/* modal */
		let mAct = null,
			mId = null;
		window.openModal = function(id, action) {
			const r = RAW.find(x => x.id === id);
			if (!r) return;
			mAct = action;
			mId = id;
			$mTitle.textContent = action === 'approve' ? '✅ Approve Withdrawal' : '❌ Reject Withdrawal';
			$mSum.innerHTML = `
			<strong>User:</strong> ${esc(r.user_name)} (${esc(r.user_mobile)})<br>
			<strong>Amount:</strong> Rs ${fmt(r.amount)}<br>
			<strong>Bank:</strong> ${esc(r.bank_name)} | <strong>IFSC:</strong> ${esc(r.ifsc_code)}<br>
			<strong>Account:</strong> ${esc(r.account_number)} (${esc(r.account_holder_name)})
		`;
			$mRmk.value = r.admin_note || '';
			$mOk.textContent = action === 'approve' ? 'Approve' : 'Reject';
			$mOk.className = 'wr-btn' + (action === 'reject' ? ' rm' : '');
			$modal.classList.add('open');
			setTimeout(() => $mRmk.focus(), 110);
		};

		function closeModal() {
			$modal.classList.remove('open');
		}
		g('wr-modal-close').addEventListener('click', closeModal);
		g('wr-modal-cancel').addEventListener('click', closeModal);
		$modal.addEventListener('click', e => {
			if (e.target === $modal) closeModal();
		});
		$mOk.addEventListener('click', () => {
			if (!mId || !mAct) return;
			const f = document.createElement('form');
			f.method = 'post';
			f.action = BASE + '/' + mAct + '/' + mId;
			const n = document.createElement('input');
			n.type = 'hidden';
			n.name = 'admin_note';
			n.value = $mRmk.value;
			f.appendChild(n);
			document.body.appendChild(f);
			f.submit();
		});
		document.addEventListener('keydown', e => {
			if (e.key === 'Escape') closeModal();
		});

		/* toast auto-dismiss */
		document.querySelectorAll('.wr-toast').forEach(el => {
			setTimeout(() => {
				el.style.transition = 'opacity .4s,transform .4s';
				el.style.opacity = '0';
				el.style.transform = 'translateY(-10px)';
				setTimeout(() => el.remove(), 400);
			}, 5000);
		});

		/* re-render on resize (switches table/cards at 768px) */
		let rt;
		window.addEventListener('resize', () => {
			clearTimeout(rt);
			rt = setTimeout(render, 160);
		});

		render();
	})();
</script>