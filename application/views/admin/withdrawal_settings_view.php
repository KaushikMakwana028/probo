<?php if ($this->session->flashdata('success')): ?>
	<div class="ws-flash ws-flash-success">
		<div class="ws-flash-icon">✓</div>
		<span><?php echo $this->session->flashdata('success'); ?></span>
	</div>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
	<div class="ws-flash ws-flash-error">
		<div class="ws-flash-icon">✕</div>
		<span><?php echo $this->session->flashdata('error'); ?></span>
	</div>
<?php endif; ?>

<div class="ws-page">

	<div class="ws-pg-label">Withdrawals</div>
	<h1 class="ws-pg-title">Withdrawal settings</h1>
	<p class="ws-pg-sub">Set the minimum amount users must enter before a withdrawal request can be submitted.</p>

	<div class="ws-kpi-row">
		<div class="ws-kpi">
			<div class="ws-kpi-label">Current minimum</div>
			<div class="ws-kpi-value">Rs <?php echo number_format((float)$withdraw_min_amount, 2); ?></div>
			<span class="ws-chip ws-chip-green"><span class="ws-dot"></span>Live</span>
		</div>
		<div class="ws-kpi">
			<div class="ws-kpi-label">Pending requests</div>
			<div class="ws-kpi-value"><?php echo $pending_count ?? 0; ?></div>
			<span class="ws-chip ws-chip-amber"><span class="ws-dot"></span>Awaiting review</span>
		</div>
	</div>

	<div class="ws-card">
		<div class="ws-card-top">
			<div class="ws-card-icon">
				<svg viewBox="0 0 24 24" fill="none" stroke="#4f46e5" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
					<path d="M12 2v20M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6" />
				</svg>
			</div>
			<div>
				<div class="ws-card-name">Minimum withdraw amount</div>
				<div class="ws-card-sub">Applies to all user withdrawal requests</div>
			</div>
			<span class="ws-enabled-pill">Enabled</span>
		</div>

		<form method="post" action="<?php echo site_url('admin/withdrawals/save-settings'); ?>" class="ws-card-body">

			<div class="ws-two-col">
				<div>
					<div class="ws-field-label">Amount (Rs)</div>
					<div class="ws-input-wrap">
						<span class="ws-prefix">Rs</span>
						<input
							type="number"
							id="ws_amount"
							name="withdraw_min_amount"
							class="ws-input"
							min="1"
							step="0.01"
							value="<?php echo number_format((float)$withdraw_min_amount, 2, '.', ''); ?>"
							required>
					</div>
				</div>
				<div>
					<div class="ws-field-label">Quick select</div>
					<div class="ws-quick-btns">
						<button type="button" class="ws-quick-btn" onclick="wsSetAmt(100)">Rs 100</button>
						<button type="button" class="ws-quick-btn" onclick="wsSetAmt(500)">Rs 500</button>
						<button type="button" class="ws-quick-btn" onclick="wsSetAmt(1000)">Rs 1,000</button>
					</div>
				</div>
			</div>

			<div class="ws-field-label" style="margin-bottom:10px">Drag to adjust</div>
			<div class="ws-range-labels">
				<span>Rs 1</span>
				<span>Rs 5,000</span>
			</div>
			<div class="ws-range-track">
				<div class="ws-range-fill" id="ws-rfill"></div>
				<input type="range" class="ws-slider" id="ws-slider" min="1" max="5000" step="1"
					value="<?php echo min(5000, max(1, (int)$withdraw_min_amount)); ?>">
			</div>
			<div class="ws-range-val-row">
				<span class="ws-range-hint">Drag the slider</span>
				<span class="ws-range-val" id="ws-rval">Rs <?php echo (int)$withdraw_min_amount; ?></span>
			</div>

			<div class="ws-divider"></div>

			<div class="ws-preview">
				<div>
					<div class="ws-preview-tag">Live preview</div>
					<div class="ws-preview-num" id="ws-prev">Rs <?php echo number_format((float)$withdraw_min_amount, 2); ?></div>
				</div>
				<div class="ws-preview-right">
					<div class="ws-preview-caption">Change from saved</div>
					<div class="ws-delta" id="ws-delta"></div>
				</div>
			</div>

			<div class="ws-notice">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15">
					<circle cx="12" cy="12" r="10" />
					<line x1="12" y1="16" x2="12" y2="12" />
					<line x1="12" y1="8" x2="12.01" y2="8" />
				</svg>
				<p>This value updates immediately for all new withdrawal requests. Existing pending requests are not affected.</p>
			</div>

			<div class="ws-actions">
				<button type="button" class="ws-btn-ghost" id="ws-reset">Discard changes</button>
				<button type="submit" class="ws-btn-primary">
					<svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" width="14" height="14">
						<polyline points="20 6 9 17 4 12" />
					</svg>
					Save settings
				</button>
			</div>
		</form>

		<div class="ws-card-foot">
			<span class="ws-foot-meta">Last saved: <?php echo $last_updated ?? 'N/A'; ?></span>
			<a href="<?php echo site_url('admin/withdrawals'); ?>" class="ws-foot-link">View withdrawal history</a>
		</div>
	</div>
</div>

<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<style>
	*,
	*::before,
	*::after {
		box-sizing: border-box;
		margin: 0;
		padding: 0;
	}

	.ws-page {
		font-family: 'Roboto', sans-serif;
		max-width: 820px;
		padding: 24px 16px;
		margin: 0 auto;
	}

	/* Flash */
	.ws-flash {
		display: flex;
		align-items: center;
		gap: 10px;
		padding: 12px 16px;
		border-radius: 10px;
		margin-bottom: 16px;
		font-size: 13px;
		font-weight: 500;
	}

	.ws-flash-success {
		background: #f0fdf4;
		border: 1px solid #bbf7d0;
		color: #166534;
	}

	.ws-flash-error {
		background: #fef2f2;
		border: 1px solid #fecaca;
		color: #991b1b;
	}

	.ws-flash-icon {
		width: 20px;
		height: 20px;
		border-radius: 50%;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 11px;
		font-weight: 700;
		flex-shrink: 0;
		color: #fff;
	}

	.ws-flash-success .ws-flash-icon {
		background: #16a34a;
	}

	.ws-flash-error .ws-flash-icon {
		background: #dc2626;
	}

	/* Page header */
	.ws-pg-label {
		font-size: 11px;
		font-weight: 700;
		letter-spacing: .13em;
		text-transform: uppercase;
		color: #6366f1;
		margin-bottom: 6px;
	}

	.ws-pg-title {
		font-size: 22px;
		font-weight: 700;
		color: #111827;
		margin-bottom: 4px;
	}

	.ws-pg-sub {
		font-size: 14px;
		color: #6b7280;
		margin-bottom: 24px;
		line-height: 1.5;
	}

	/* KPI row */
	.ws-kpi-row {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 12px;
		margin-bottom: 20px;
	}

	.ws-kpi {
		background: #f9fafb;
		border-radius: 12px;
		padding: 16px 18px;
		border: 1px solid #f3f4f6;
	}

	.ws-kpi-label {
		font-size: 11px;
		font-weight: 500;
		letter-spacing: .09em;
		text-transform: uppercase;
		color: #9ca3af;
		margin-bottom: 8px;
	}

	.ws-kpi-value {
		font-size: 24px;
		font-weight: 700;
		color: #111827;
		margin-bottom: 6px;
	}

	.ws-chip {
		display: inline-flex;
		align-items: center;
		gap: 5px;
		font-size: 11px;
		font-weight: 500;
		padding: 3px 10px;
		border-radius: 99px;
		font-family: 'Roboto', sans-serif;
	}

	.ws-chip-green {
		background: #dcfce7;
		color: #166534;
	}

	.ws-chip-amber {
		background: #fef9c3;
		color: #854d0e;
	}

	.ws-dot {
		width: 6px;
		height: 6px;
		border-radius: 50%;
		background: currentColor;
		flex-shrink: 0;
	}

	/* Card */
	.ws-card {
		background: #fff;
		border: 1px solid #e5e7eb;
		border-radius: 16px;
		overflow: hidden;
	}

	.ws-card-top {
		padding: 20px 22px;
		border-bottom: 1px solid #f3f4f6;
		display: flex;
		align-items: center;
		gap: 14px;
	}

	.ws-card-icon {
		width: 44px;
		height: 44px;
		border-radius: 12px;
		background: #eef2ff;
		display: flex;
		align-items: center;
		justify-content: center;
		flex-shrink: 0;
	}

	.ws-card-icon svg {
		width: 22px;
		height: 22px;
	}

	.ws-card-name {
		font-size: 15px;
		font-weight: 700;
		color: #111827;
	}

	.ws-card-sub {
		font-size: 12px;
		color: #9ca3af;
		margin-top: 2px;
	}

	.ws-enabled-pill {
		margin-left: auto;
		font-size: 11px;
		font-weight: 600;
		padding: 4px 12px;
		border-radius: 99px;
		background: #dcfce7;
		color: #166534;
		border: 1px solid #bbf7d0;
		white-space: nowrap;
		flex-shrink: 0;
		font-family: 'Roboto', sans-serif;
	}

	/* Form body */
	.ws-card-body {
		padding: 22px;
		display: grid;
		gap: 0;
	}

	.ws-two-col {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 14px;
		margin-bottom: 18px;
	}

	.ws-field-label {
		font-size: 11px;
		font-weight: 700;
		color: #9ca3af;
		text-transform: uppercase;
		letter-spacing: .07em;
		margin-bottom: 8px;
	}

	.ws-input-wrap {
		position: relative;
	}

	.ws-prefix {
		position: absolute;
		left: 14px;
		top: 50%;
		transform: translateY(-50%);
		font-size: 14px;
		font-weight: 500;
		color: #9ca3af;
		pointer-events: none;
	}

	.ws-input {
		width: 100%;
		padding: 14px 14px 14px 44px;
		border: 1px solid #e5e7eb;
		border-radius: 10px;
		font-size: 18px;
		font-weight: 700;
		font-family: 'Roboto', sans-serif;
		color: #111827;
		background: #fff;
		outline: none;
		-moz-appearance: textfield;
		transition: border-color .15s, box-shadow .15s;
	}

	.ws-input::-webkit-inner-spin-button,
	.ws-input::-webkit-outer-spin-button {
		-webkit-appearance: none;
	}

	.ws-input:focus {
		border-color: #6366f1;
		box-shadow: 0 0 0 3px rgba(99, 102, 241, .12);
	}

	.ws-quick-btns {
		display: flex;
		gap: 8px;
		flex-wrap: wrap;
	}

	.ws-quick-btn {
		padding: 8px 14px;
		border-radius: 10px;
		border: 1px solid #e5e7eb;
		background: #fff;
		font-family: 'Roboto', sans-serif;
		font-size: 12px;
		font-weight: 500;
		color: #374151;
		cursor: pointer;
	}

	.ws-quick-btn:hover {
		background: #f9fafb;
		border-color: #6366f1;
		color: #6366f1;
	}

	/* Range */
	.ws-range-labels {
		display: flex;
		justify-content: space-between;
		font-size: 11px;
		color: #d1d5db;
		margin-bottom: 6px;
	}

	.ws-range-track {
		position: relative;
		height: 6px;
		background: #f3f4f6;
		border-radius: 99px;
		margin-bottom: 8px;
	}

	.ws-range-fill {
		position: absolute;
		left: 0;
		top: 0;
		height: 100%;
		background: #6366f1;
		border-radius: 99px;
		pointer-events: none;
	}

	.ws-slider {
		width: 100%;
		position: absolute;
		top: -6px;
		left: 0;
		right: 0;
		-webkit-appearance: none;
		appearance: none;
		background: transparent;
		height: 6px;
		margin: 0;
	}

	.ws-slider::-webkit-slider-thumb {
		-webkit-appearance: none;
		width: 18px;
		height: 18px;
		border-radius: 50%;
		background: #fff;
		border: 2px solid #6366f1;
		cursor: pointer;
	}

	.ws-slider::-moz-range-thumb {
		width: 18px;
		height: 18px;
		border-radius: 50%;
		background: #fff;
		border: 2px solid #6366f1;
		cursor: pointer;
	}

	.ws-range-val-row {
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-bottom: 18px;
	}

	.ws-range-hint {
		font-size: 12px;
		color: #d1d5db;
	}

	.ws-range-val {
		font-size: 13px;
		font-weight: 700;
		color: #6366f1;
	}

	/* Divider */
	.ws-divider {
		height: 1px;
		background: #f3f4f6;
		margin: 2px 0 18px;
	}

	/* Preview */
	.ws-preview {
		border-radius: 12px;
		border: 1px solid #c7d2fe;
		background: #eef2ff;
		padding: 16px 20px;
		display: flex;
		align-items: center;
		justify-content: space-between;
		flex-wrap: wrap;
		gap: 12px;
		margin-bottom: 16px;
	}

	.ws-preview-tag {
		font-size: 11px;
		font-weight: 700;
		letter-spacing: .09em;
		text-transform: uppercase;
		color: #6366f1;
		margin-bottom: 5px;
	}

	.ws-preview-num {
		font-size: 32px;
		font-weight: 700;
		color: #3730a3;
	}

	.ws-preview-right {
		text-align: right;
	}

	.ws-preview-caption {
		font-size: 11px;
		color: #818cf8;
		margin-bottom: 4px;
	}

	.ws-delta {
		font-size: 12px;
		font-weight: 600;
		padding: 3px 10px;
		border-radius: 99px;
		display: none;
		font-family: 'Roboto', sans-serif;
	}

	.ws-delta-up {
		background: #dcfce7;
		color: #166534;
		display: inline-flex;
	}

	.ws-delta-down {
		background: #fef2f2;
		color: #991b1b;
		display: inline-flex;
	}

	/* Notice */
	.ws-notice {
		display: flex;
		gap: 10px;
		padding: 12px 14px;
		background: #f9fafb;
		border-radius: 10px;
		align-items: flex-start;
		margin-bottom: 18px;
		color: #9ca3af;
	}

	.ws-notice p {
		font-size: 12px;
		color: #6b7280;
		line-height: 1.6;
	}

	/* Actions */
	.ws-actions {
		display: flex;
		align-items: center;
		gap: 12px;
	}

	.ws-btn-ghost {
		padding: 11px 18px;
		border-radius: 10px;
		border: 1px solid #e5e7eb;
		background: transparent;
		font-family: 'Roboto', sans-serif;
		font-size: 13px;
		font-weight: 500;
		color: #6b7280;
		cursor: pointer;
	}

	.ws-btn-ghost:hover {
		background: #f9fafb;
	}

	.ws-btn-primary {
		margin-left: auto;
		padding: 11px 24px;
		border-radius: 10px;
		border: none;
		background: #4f46e5;
		color: #fff;
		font-family: 'Roboto', sans-serif;
		font-size: 13px;
		font-weight: 700;
		cursor: pointer;
		display: flex;
		align-items: center;
		gap: 8px;
	}

	.ws-btn-primary:hover {
		background: #4338ca;
	}

	.ws-btn-primary:active {
		transform: scale(.98);
	}

	/* Footer */
	.ws-card-foot {
		padding: 12px 22px;
		border-top: 1px solid #f3f4f6;
		display: flex;
		align-items: center;
		justify-content: space-between;
		flex-wrap: wrap;
		gap: 8px;
	}

	.ws-foot-meta {
		font-size: 11px;
		color: #d1d5db;
	}

	.ws-foot-link {
		font-size: 11px;
		color: #6366f1;
		text-decoration: none;
	}

	.ws-foot-link:hover {
		text-decoration: underline;
	}

	/* Mobile */
	@media (max-width: 600px) {

		.ws-kpi-row,
		.ws-two-col {
			grid-template-columns: 1fr;
		}
	}

	@media (max-width: 500px) {
		.ws-actions {
			flex-direction: column-reverse;
		}

		.ws-btn-ghost,
		.ws-btn-primary {
			width: 100%;
			justify-content: center;
		}

		.ws-preview {
			flex-direction: column;
		}

		.ws-preview-right {
			text-align: left;
		}

		.ws-enabled-pill {
			display: none;
		}
	}
</style>

<script>
	(function() {
		const amt = document.getElementById('ws_amount'),
			slider = document.getElementById('ws-slider'),
			rfill = document.getElementById('ws-rfill'),
			rval = document.getElementById('ws-rval'),
			prev = document.getElementById('ws-prev'),
			delta = document.getElementById('ws-delta'),
			reset = document.getElementById('ws-reset');

		let saved = parseFloat(amt.value) || 0;

		function fmt(v) {
			return 'Rs ' + parseFloat(v).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
		}

		function fmtShort(v) {
			return 'Rs ' + Math.round(v).toLocaleString();
		}

		function fillPct(v) {
			return Math.max(0, Math.min(100, ((v - 1) / 4999) * 100)).toFixed(2) + '%';
		}

		function sync() {
			const v = parseFloat(amt.value) || 0;
			slider.value = Math.min(5000, Math.max(1, Math.round(v)));
			rfill.style.width = fillPct(v);
			rval.textContent = fmtShort(v);
			prev.textContent = fmt(v);
			const d = v - saved;
			if (Math.abs(d) < 0.01) {
				delta.style.display = 'none';
			} else if (d > 0) {
				delta.className = 'ws-delta ws-delta-up';
				delta.textContent = '+Rs ' + d.toFixed(2);
				delta.style.display = 'inline-flex';
			} else {
				delta.className = 'ws-delta ws-delta-down';
				delta.textContent = '-Rs ' + Math.abs(d).toFixed(2);
				delta.style.display = 'inline-flex';
			}
		}

		function wsSetAmt(v) {
			amt.value = v.toFixed(2);
			sync();
		}
		window.wsSetAmt = wsSetAmt;
		amt.addEventListener('input', sync);
		slider.addEventListener('input', function() {
			amt.value = parseFloat(this.value).toFixed(2);
			sync();
		});
		reset.addEventListener('click', function() {
			amt.value = saved.toFixed(2);
			sync();
		});
		sync();
	})();
</script>