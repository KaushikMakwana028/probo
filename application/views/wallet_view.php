<?php
// wallet_view.php — Enhanced UI
$history_filter = isset($history_filter) ? $history_filter : 'all';
$pending_withdrawals = array();
$merged_transactions = array();
$withdrawal_tx_ids = array();

if (!empty($transactions)) {
	foreach ($transactions as $tx) {
		if (isset($tx->source_type) && $tx->source_type === 'withdrawal' && !empty($tx->source_id)) {
			$withdrawal_tx_ids[(int) $tx->source_id] = TRUE;
		}
		$tx->_history_timestamp = !empty($tx->created_at) ? strtotime($tx->created_at) : 0;
		$merged_transactions[] = $tx;
	}
}

if (!empty($withdrawals)) {
	foreach ($withdrawals as $wr) {
		$ws = strtolower(trim((string) $wr->status));
		$is_pending = in_array($ws, array('pending', 'processing'), TRUE);
		$has_wallet_tx = isset($withdrawal_tx_ids[(int) $wr->id]);
		if ($is_pending) {
			$pending_withdrawals[] = $wr;
			continue;
		}
		if (!$has_wallet_tx) {
			$history_row = new stdClass();
			$history_row->created_at = $wr->created_at;
			$history_row->amount = $wr->amount;
			$history_row->type = 'debit';
			$history_row->source_type = 'withdrawal_request';
			$history_row->history_type_label = 'Withdrawal';
			$history_row->history_badge_class = 'wv-badge--withdrawal';
			$history_row->history_filter = 'withdrawals';
			$history_row->history_description = 'Withdrawal request';
			$history_row->history_status_label = in_array($ws, array('approved', 'success', 'completed'), TRUE) ? 'Success' : (in_array($ws, array('rejected', 'failed', 'declined'), TRUE) ? 'Rejected' : ucfirst($ws));
			$history_row->history_status_class = in_array($ws, array('approved', 'success', 'completed'), TRUE) ? 'wv-badge--success' : (in_array($ws, array('rejected', 'failed', 'declined'), TRUE) ? 'wv-badge--rejected' : 'wv-badge--processed');
			$history_row->_history_timestamp = !empty($wr->created_at) ? strtotime($wr->created_at) : 0;
			$merged_transactions[] = $history_row;
		}
	}
}

usort($merged_transactions, function ($a, $b) {
	return (int)$b->_history_timestamp <=> (int)$a->_history_timestamp;
});

$transactions = $merged_transactions;
$withdrawals = $pending_withdrawals;
$wallet_history_count = count($withdrawals) + count($transactions);
?>

<link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

<?php if ($this->session->flashdata('error')): ?>
	<div class="wv-alert wv-alert--err" role="alert">
		<div class="wv-alert__ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
				<circle cx="12" cy="12" r="10" />
				<line x1="12" y1="8" x2="12" y2="12" />
				<line x1="12" y1="16" x2="12.01" y2="16" />
			</svg></div>
		<span><?php echo $this->session->flashdata('error'); ?></span>
		<button class="wv-alert__close" onclick="this.parentElement.remove()">&times;</button>
	</div>
<?php endif; ?>
<?php if ($this->session->flashdata('success')): ?>
	<div class="wv-alert wv-alert--ok" role="alert">
		<div class="wv-alert__ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
				<polyline points="20 6 9 17 4 12" />
			</svg></div>
		<span><?php echo $this->session->flashdata('success'); ?></span>
		<button class="wv-alert__close" onclick="this.parentElement.remove()">&times;</button>
	</div>
<?php endif; ?>

<style>
	:root {
		--ink: #0d1117;
		--ink-2: #24292f;
		--ink-3: #57606a;
		--ink-4: #8c959f;
		--line: #e8ecf0;
		--line-2: #d0d7de;
		--surf: #ffffff;
		--base: #f6f8fa;
		--base-2: #eef1f4;

		--blue: #2563eb;
		--blue-lt: #eff6ff;
		--blue-border: #bfdbfe;
		--blue-dk: #1d4ed8;
		--blue-text: #1e40af;

		--green: #16a34a;
		--green-lt: #f0fdf4;
		--green-border: #bbf7d0;
		--green-text: #166534;

		--red: #dc2626;
		--red-lt: #fef2f2;
		--red-border: #fecaca;
		--red-text: #991b1b;

		--amber: #d97706;
		--amber-lt: #fffbeb;
		--amber-border: #fde68a;
		--amber-text: #92400e;

		--purple: #7c3aed;
		--purple-lt: #faf5ff;
		--purple-border: #e9d5ff;
		--purple-text: #5b21b6;

		--cyan: #0891b2;
		--cyan-lt: #ecfeff;
		--cyan-border: #cffafe;
		--cyan-text: #164e63;

		--pink: #db2777;
		--pink-lt: #fdf2f8;
		--pink-border: #fbcfe8;
		--pink-text: #831843;

		--f-body: 'Sora', system-ui, sans-serif;
		--f-mono: 'JetBrains Mono', monospace;

		--r-sm: 6px;
		--r-md: 10px;
		--r-lg: 14px;
		--r-xl: 18px;
		--r-2xl: 24px;
		--sh-xs: 0 1px 3px rgba(13, 17, 23, .06);
		--sh-sm: 0 3px 12px rgba(13, 17, 23, .07), 0 1px 4px rgba(13, 17, 23, .04);
	}

	*,
	*::before,
	*::after {
		box-sizing: border-box;
		margin: 0;
		padding: 0;
	}

	.wv-root {
		max-width: 1200px;
		margin: 0 auto;
		padding: 20px 16px 60px;
		font-family: var(--f-body);
		color: var(--ink);
		-webkit-font-smoothing: antialiased;
		display: flex;
		flex-direction: column;
		gap: 18px;
	}

	/* ── ALERTS ── */
	.wv-alert {
		display: flex;
		align-items: flex-start;
		gap: 12px;
		padding: 14px 18px;
		border-radius: var(--r-lg);
		margin-bottom: 4px;
		font-size: 13.5px;
		font-weight: 500;
		animation: wvSlide .3s ease;
		border: 1px solid transparent;
	}

	@keyframes wvSlide {
		from {
			opacity: 0;
			transform: translateY(-8px);
		}

		to {
			opacity: 1;
			transform: none;
		}
	}

	.wv-alert--ok {
		background: var(--green-lt);
		border-color: var(--green-border);
		color: var(--green-text);
	}

	.wv-alert--err {
		background: var(--red-lt);
		border-color: var(--red-border);
		color: var(--red-text);
	}

	.wv-alert__ico {
		width: 22px;
		height: 22px;
		border-radius: var(--r-sm);
		display: flex;
		align-items: center;
		justify-content: center;
		flex-shrink: 0;
	}

	.wv-alert__ico svg {
		width: 13px;
		height: 13px;
	}

	.wv-alert--ok .wv-alert__ico {
		background: var(--green-border);
	}

	.wv-alert--err .wv-alert__ico {
		background: var(--red-border);
	}

	.wv-alert__close {
		margin-left: auto;
		background: none;
		border: none;
		cursor: pointer;
		color: inherit;
		opacity: .5;
		font-size: 18px;
		padding: 0 2px;
	}

	.wv-alert__close:hover {
		opacity: 1;
	}

	/* ── HERO ── */
	.wv-hero {
		border-radius: var(--r-2xl);
		overflow: hidden;
		background: #0b0f1e;
		position: relative;
	}

	.wv-hero__bg {
		position: absolute;
		inset: 0;
		pointer-events: none;
		background:
			radial-gradient(ellipse 55% 85% at 92% 50%, rgba(37, 99, 235, .38) 0%, transparent 65%),
			radial-gradient(ellipse 40% 60% at 5% 15%, rgba(124, 58, 237, .2) 0%, transparent 60%),
			radial-gradient(ellipse 40% 60% at 50% 110%, rgba(16, 185, 129, .12) 0%, transparent 60%);
	}

	.wv-hero__inner {
		position: relative;
		z-index: 1;
		padding: 30px 34px;
		display: flex;
		justify-content: space-between;
		align-items: center;
		gap: 24px;
		flex-wrap: wrap;
	}

	.wv-hero__left {
		flex: 1;
		min-width: 0;
	}

	.wv-hero__eye {
		display: inline-flex;
		align-items: center;
		gap: 7px;
		font-size: 10.5px;
		font-weight: 600;
		letter-spacing: .12em;
		text-transform: uppercase;
		color: rgba(255, 255, 255, .4);
		margin-bottom: 8px;
	}

	.wv-hero__eye svg {
		width: 12px;
		height: 12px;
	}

	.wv-hero__title {
		font-size: clamp(20px, 3vw, 34px);
		font-weight: 700;
		color: #fff;
		letter-spacing: -.025em;
		line-height: 1.15;
		margin-bottom: 6px;
	}

	.wv-hero__sub {
		font-size: 13px;
		color: rgba(255, 255, 255, .45);
		line-height: 1.6;
		max-width: 480px;
		margin-bottom: 22px;
	}

	.wv-hero__chips {
		display: flex;
		gap: 10px;
		flex-wrap: wrap;
	}

	.wv-hero__chip {
		padding: 12px 16px;
		border-radius: var(--r-lg);
		background: rgba(255, 255, 255, .07);
		border: 1px solid rgba(255, 255, 255, .1);
		min-width: 130px;
	}

	.wv-hero__chip--g {
		border-color: rgba(34, 197, 94, .25);
	}

	.wv-hero__chip--r {
		border-color: rgba(244, 63, 94, .25);
	}

	.wv-hero__chip-lbl {
		display: block;
		font-size: 9.5px;
		font-weight: 600;
		letter-spacing: .1em;
		text-transform: uppercase;
		color: rgba(255, 255, 255, .4);
		margin-bottom: 5px;
	}

	.wv-hero__chip-val {
		display: block;
		font-size: 18px;
		font-weight: 700;
		color: #fff;
		letter-spacing: -.02em;
	}

	.wv-hero__chip--g .wv-hero__chip-val {
		color: #86efac;
	}

	.wv-hero__chip--r .wv-hero__chip-val {
		color: #fda4af;
	}

	.wv-hero__bal {
		flex-shrink: 0;
		padding: 24px 30px;
		border-radius: var(--r-xl);
		background: rgba(255, 255, 255, .07);
		border: 1px solid rgba(255, 255, 255, .12);
		backdrop-filter: blur(14px);
		text-align: center;
		min-width: 200px;
	}

	.wv-hero__bal-lbl {
		display: block;
		font-size: 10px;
		font-weight: 600;
		letter-spacing: .12em;
		text-transform: uppercase;
		color: rgba(255, 255, 255, .4);
		margin-bottom: 10px;
	}

	.wv-hero__bal-val {
		display: block;
		font-size: 38px;
		font-weight: 700;
		color: #fff;
		letter-spacing: -.03em;
		line-height: 1;
		margin-bottom: 12px;
	}

	.wv-hero__bal-foot {
		display: flex;
		align-items: center;
		justify-content: center;
		gap: 5px;
		font-size: 11px;
		color: rgba(255, 255, 255, .3);
	}

	.wv-hero__bal-foot svg {
		width: 11px;
		height: 11px;
	}

	/* Deposit CTA in hero */
	.wv-hero__cta {
		display: inline-flex;
		align-items: center;
		gap: 7px;
		margin-top: 12px;
		padding: 10px 20px;
		border-radius: 999px;
		background: var(--blue);
		color: #fff;
		font-family: var(--f-body);
		font-size: 13px;
		font-weight: 700;
		text-decoration: none;
		letter-spacing: -.01em;
		border: none;
		cursor: pointer;
		box-shadow: 0 4px 16px rgba(37, 99, 235, .35);
		transition: all .15s;
	}

	.wv-hero__cta svg {
		width: 13px;
		height: 13px;
	}

	.wv-hero__cta:hover {
		background: var(--blue-dk);
		transform: translateY(-1px);
	}

	/* ── ACTION CARDS GRID ── */
	.wv-actions {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 16px;
	}

	/* ── CARD ── */
	.wv-card {
		background: var(--surf);
		border: 1px solid var(--line);
		border-radius: var(--r-xl);
		overflow: hidden;
		box-shadow: var(--sh-xs);
	}

	.wv-card__head {
		padding: 14px 20px;
		border-bottom: 1px solid var(--line);
		display: flex;
		align-items: center;
		gap: 10px;
	}

	.wv-card__ico {
		width: 34px;
		height: 34px;
		border-radius: var(--r-md);
		display: flex;
		align-items: center;
		justify-content: center;
		flex-shrink: 0;
	}

	.wv-card__ico svg {
		width: 14px;
		height: 14px;
	}

	.wv-card__ico--blue {
		background: var(--blue-lt);
		color: var(--blue);
		border: 1px solid var(--blue-border);
	}

	.wv-card__ico--red {
		background: var(--red-lt);
		color: var(--red);
		border: 1px solid var(--red-border);
	}

	.wv-card__ico--green {
		background: var(--green-lt);
		color: var(--green);
		border: 1px solid var(--green-border);
	}

	.wv-card__ico--gray {
		background: var(--base-2);
		color: var(--ink-4);
		border: 1px solid var(--line);
	}

	.wv-card__eyebrow {
		font-size: 10px;
		font-weight: 600;
		letter-spacing: .1em;
		text-transform: uppercase;
		color: var(--ink-4);
		margin-bottom: 1px;
	}

	.wv-card__title {
		font-size: 14.5px;
		font-weight: 700;
		color: var(--ink);
		letter-spacing: -.01em;
	}

	.wv-icon-btn {
		margin-left: auto;
		width: 30px;
		height: 30px;
		border-radius: var(--r-sm);
		border: 1px solid var(--line-2);
		background: var(--base);
		display: flex;
		align-items: center;
		justify-content: center;
		color: var(--ink-3);
		text-decoration: none;
		flex-shrink: 0;
		transition: background .15s;
	}

	.wv-icon-btn svg {
		width: 12px;
		height: 12px;
	}

	.wv-icon-btn:hover {
		background: var(--base-2);
	}

	.wv-card__desc {
		font-size: 12.5px;
		color: var(--ink-4);
		line-height: 1.55;
		margin: 0 20px 14px;
		padding-top: 14px;
		border-bottom: 1px solid var(--line);
		padding-bottom: 14px;
	}

	.wv-card__body {
		padding: 18px 20px;
	}

	/* ── FORM ── */
	.wv-form {
		display: flex;
		flex-direction: column;
		gap: 12px;
	}

	.wv-field {
		display: flex;
		flex-direction: column;
		gap: 5px;
	}

	.wv-lbl {
		font-size: 11px;
		font-weight: 700;
		letter-spacing: .08em;
		text-transform: uppercase;
		color: var(--ink-3);
	}

	.wv-input-wrap {
		position: relative;
		display: flex;
		align-items: center;
	}

	.wv-pfx {
		position: absolute;
		left: 12px;
		font-size: 14px;
		font-weight: 700;
		color: var(--ink-4);
		pointer-events: none;
		z-index: 1;
	}

	.wv-input {
		width: 100%;
		height: 44px;
		padding: 0 12px;
		border: 1.5px solid var(--line-2);
		border-radius: var(--r-md);
		background: var(--base);
		color: var(--ink);
		font-family: var(--f-body);
		font-size: 14px;
		font-weight: 500;
		transition: border-color .15s, box-shadow .15s, background .15s;
		-webkit-appearance: none;
	}

	.wv-input--pfx {
		padding-left: 26px;
	}

	.wv-input:hover {
		border-color: var(--ink-4);
		background: var(--surf);
	}

	.wv-input:focus {
		outline: none;
		border-color: var(--blue);
		box-shadow: 0 0 0 3.5px rgba(37, 99, 235, .1);
		background: var(--surf);
	}

	.wv-input:disabled {
		opacity: .4;
		cursor: not-allowed;
	}

	.wv-hint {
		font-size: 11.5px;
		color: var(--ink-5);
	}

	.wv-hint strong {
		color: var(--ink-3);
	}

	/* ── BUTTONS ── */
	.wv-btn {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		gap: 7px;
		padding: 11px 20px;
		border-radius: var(--r-md);
		font-family: var(--f-body);
		font-size: 13px;
		font-weight: 700;
		border: 1.5px solid transparent;
		cursor: pointer;
		transition: all .15s;
		text-decoration: none;
		white-space: nowrap;
		letter-spacing: -.01em;
	}

	.wv-btn svg {
		width: 13px;
		height: 13px;
	}

	.wv-btn--full {
		width: 100%;
	}

	.wv-btn--blue {
		background: var(--blue);
		border-color: var(--blue-dk);
		color: #fff;
		box-shadow: 0 2px 10px rgba(37, 99, 235, .22);
	}

	.wv-btn--blue:hover {
		background: var(--blue-dk);
		box-shadow: 0 4px 18px rgba(37, 99, 235, .35);
	}

	.wv-btn--red {
		background: var(--red);
		border-color: #b91c1c;
		color: #fff;
		box-shadow: 0 2px 10px rgba(220, 38, 38, .22);
	}

	.wv-btn--red:hover {
		background: #b91c1c;
		box-shadow: 0 4px 18px rgba(220, 38, 38, .35);
	}

	.wv-btn--ghost {
		background: var(--base);
		border-color: var(--line-2);
		color: var(--ink-3);
	}

	.wv-btn--ghost:hover {
		background: var(--base-2);
		color: var(--ink-2);
	}

	.wv-btn--outline {
		background: var(--surf);
		border-color: var(--line-2);
		color: var(--ink-2);
	}

	.wv-btn--outline:hover {
		background: var(--base);
	}

	.wv-btn--disabled {
		background: var(--base-2);
		border-color: var(--line);
		color: var(--ink-4);
		cursor: not-allowed;
	}

	/* ── BANK GRID ── */
	.wv-bank-form {
		display: flex;
		flex-direction: column;
		gap: 12px;
	}

	.wv-bank-form__actions {
		display: flex;
		justify-content: flex-end;
		gap: 8px;
		padding-top: 4px;
	}

	.wv-bank-grid {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 10px;
	}

	.wv-bank-box {
		padding: 12px 14px;
		border-radius: var(--r-md);
		background: var(--base);
		border: 1px solid var(--line);
	}

	.wv-bank-box--acc {
		background: var(--blue-lt);
		border-color: var(--blue-border);
	}

	.wv-bank-box__lbl {
		display: block;
		font-size: 10px;
		font-weight: 600;
		letter-spacing: .1em;
		text-transform: uppercase;
		color: var(--ink-4);
		margin-bottom: 4px;
	}

	.wv-bank-box--acc .wv-bank-box__lbl {
		color: var(--blue-text);
		opacity: .7;
	}

	.wv-bank-box__val {
		display: block;
		font-size: 13px;
		font-weight: 600;
		color: var(--ink-2);
		word-break: break-all;
	}

	.wv-bank-box__val--mono {
		font-family: var(--f-mono);
		font-size: 12.5px;
		color: var(--blue-text);
		letter-spacing: .03em;
	}

	/* ── TRANSACTION SECTION ── */
	.wv-tx-section {
		background: var(--surf);
		border: 1px solid var(--line);
		border-radius: var(--r-xl);
		overflow: hidden;
		box-shadow: var(--sh-xs);
	}

	.wv-tx-head {
		padding: 20px 22px 0;
	}

	.wv-tx-head__top {
		display: flex;
		align-items: center;
		gap: 12px;
		margin-bottom: 16px;
		flex-wrap: wrap;
	}

	.wv-tx-eye {
		font-size: 10px;
		font-weight: 600;
		letter-spacing: .12em;
		text-transform: uppercase;
		color: var(--ink-4);
		margin-bottom: 3px;
	}

	.wv-tx-title {
		font-size: 20px;
		font-weight: 700;
		color: var(--ink);
		letter-spacing: -.02em;
	}

	.wv-tx-count {
		display: inline-flex;
		align-items: center;
		padding: 4px 12px;
		border-radius: 999px;
		background: var(--base-2);
		border: 1px solid var(--line-2);
		font-size: 11.5px;
		font-weight: 600;
		color: var(--ink-4);
	}

	.wv-tx-head__top .spacer {
		flex: 1;
	}

	/* Filter bar */
	.wv-filter-bar {
		display: flex;
		align-items: center;
		gap: 6px;
		flex-wrap: wrap;
		padding-bottom: 16px;
		border-bottom: 1px solid var(--line);
	}

	.wv-pill {
		display: inline-flex;
		align-items: center;
		gap: 5px;
		padding: 6px 14px;
		border-radius: 999px;
		font-size: 12.5px;
		font-weight: 600;
		border: 1.5px solid var(--line-2);
		color: var(--ink-3);
		background: var(--surf);
		cursor: pointer;
		transition: all .15s;
		white-space: nowrap;
		font-family: var(--f-body);
	}

	.wv-pill__dot {
		width: 5px;
		height: 5px;
		border-radius: 50%;
		background: currentColor;
		opacity: .4;
	}

	.wv-pill:hover {
		border-color: var(--blue);
		color: var(--blue);
		background: var(--blue-lt);
	}

	.wv-pill.is-active {
		background: var(--pill-color, var(--blue));
		border-color: transparent;
		color: #fff;
		box-shadow: 0 2px 10px rgba(0, 0, 0, .18);
	}

	.wv-pill.is-active .wv-pill__dot {
		opacity: 1;
		background: rgba(255, 255, 255, .75);
	}

	/* Rows dropdown */
	.wv-rows-wrap {
		margin-left: auto;
		position: relative;
	}

	.wv-rows-btn {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		padding: 6px 12px;
		border-radius: 999px;
		font-size: 12.5px;
		font-weight: 600;
		color: var(--ink-3);
		background: var(--base);
		border: 1.5px solid var(--line-2);
		cursor: pointer;
		font-family: var(--f-body);
		transition: background .15s;
	}

	.wv-rows-btn svg {
		width: 12px;
		height: 12px;
	}

	#wvChevron {
		transition: transform .2s;
	}

	.wv-rows-btn.open #wvChevron {
		transform: rotate(180deg);
	}

	.wv-rows-dd {
		position: absolute;
		top: calc(100% + 6px);
		right: 0;
		z-index: 50;
		background: var(--surf);
		border: 1px solid var(--line-2);
		border-radius: var(--r-lg);
		box-shadow: 0 8px 28px rgba(13, 17, 23, .1);
		min-width: 130px;
		overflow: hidden;
		display: none;
	}

	.wv-rows-dd.open {
		display: block;
	}

	.wv-rows-opt {
		padding: 9px 16px;
		font-size: 13px;
		font-weight: 500;
		color: var(--ink-2);
		cursor: pointer;
		transition: background .1s;
		display: flex;
		align-items: center;
		justify-content: space-between;
	}

	.wv-rows-opt:hover {
		background: var(--base);
	}

	.wv-rows-opt.is-active {
		color: var(--blue);
		font-weight: 700;
		background: var(--blue-lt);
	}

	.wv-rows-opt.is-active::after {
		content: '';
		width: 6px;
		height: 6px;
		border-radius: 50%;
		background: var(--blue);
	}

	/* Table */
	.wv-table-wrap {
		overflow-x: auto;
	}

	.wv-table {
		width: 100%;
		border-collapse: collapse;
		min-width: 640px;
	}

	.wv-table thead tr {
		background: var(--base);
	}

	.wv-table th {
		padding: 11px 18px;
		font-size: 10px;
		font-weight: 700;
		letter-spacing: .08em;
		text-transform: uppercase;
		color: var(--ink-4);
		text-align: left;
		border-bottom: 1px solid var(--line);
	}

	.wv-table td {
		padding: 13px 18px;
		border-top: 1px solid var(--line);
		font-size: 13px;
		color: var(--ink);
		vertical-align: middle;
	}

	.wv-table tbody tr {
		transition: background .1s;
	}

	.wv-table tbody tr:hover {
		background: var(--base);
	}

	.wv-td-num {
		color: var(--ink-4) !important;
		font-size: 11.5px !important;
	}

	.wv-td-desc {
		color: var(--ink-4) !important;
		font-size: 12.5px !important;
	}

	.wv-td-cr {
		color: var(--green) !important;
		font-weight: 700;
	}

	.wv-td-dr {
		color: var(--red) !important;
		font-weight: 700;
	}

	.wv-td-date {
		color: var(--ink-4) !important;
		font-size: 12px !important;
		white-space: nowrap;
	}

	/* Badges */
	.wv-badge {
		display: inline-flex;
		align-items: center;
		gap: 5px;
		padding: 3px 10px;
		border-radius: 999px;
		font-size: 11px;
		font-weight: 700;
		white-space: nowrap;
	}

	.wv-badge-dot {
		width: 4px;
		height: 4px;
		border-radius: 50%;
		background: currentColor;
	}

	.wv-badge--deposit {
		background: var(--purple-lt);
		color: var(--purple-text);
	}

	.wv-badge--winning {
		background: var(--green-lt);
		color: var(--green-text);
	}

	.wv-badge--trade {
		background: var(--amber-lt);
		color: var(--amber-text);
	}

	.wv-badge--withdrawal {
		background: var(--red-lt);
		color: var(--red-text);
	}

	.wv-badge--referral {
		background: var(--pink-lt);
		color: var(--pink-text);
	}

	.wv-badge--refund {
		background: var(--cyan-lt);
		color: var(--cyan-text);
	}

	.wv-badge--default {
		background: var(--base-2);
		color: var(--ink-3);
	}

	.wv-badge--success {
		background: var(--green-lt);
		color: var(--green-text);
	}

	.wv-badge--processed {
		background: var(--blue-lt);
		color: var(--blue-text);
	}

	.wv-badge--pending {
		background: var(--amber-lt);
		color: var(--amber-text);
	}

	.wv-badge--rejected {
		background: var(--red-lt);
		color: var(--red-text);
	}

	/* Empty */
	.wv-empty td {
		text-align: center;
		padding: 52px 20px;
		color: var(--ink-4);
		font-size: 13.5px;
	}

	.wv-empty-ico {
		display: block;
		margin: 0 auto 10px;
		width: 28px;
		height: 28px;
		opacity: .25;
	}

	/* Pagination */
	.wv-pagination {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 12px;
		padding: 14px 18px;
		border-top: 1px solid var(--line);
	}

	.wv-page-info {
		font-size: 12.5px;
		color: var(--ink-4);
	}

	.wv-page-btns {
		display: flex;
		gap: 4px;
		align-items: center;
	}

	.wv-page-btn {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		min-width: 32px;
		height: 32px;
		padding: 0 6px;
		border-radius: var(--r-md);
		border: 1.5px solid var(--line-2);
		background: var(--surf);
		color: var(--ink-2);
		font-family: var(--f-body);
		font-size: 13px;
		font-weight: 600;
		cursor: pointer;
		transition: all .12s;
	}

	.wv-page-btn:hover:not(:disabled) {
		background: var(--base-2);
	}

	.wv-page-btn.is-active {
		background: var(--blue);
		color: #fff;
		border-color: var(--blue);
	}

	.wv-page-btn:disabled {
		opacity: .3;
		cursor: not-allowed;
	}

	.wv-page-dots {
		padding: 0 4px;
		color: var(--ink-4);
		font-size: 13px;
		align-self: center;
	}

	/* Reveal animation */
	[data-wv-reveal] {
		opacity: 0;
		transform: translateY(10px);
		transition: opacity .4s ease, transform .4s ease;
	}

	[data-wv-reveal].revealed {
		opacity: 1;
		transform: none;
	}

	/* ── RESPONSIVE ── */
	@media (max-width: 900px) {
		.wv-actions {
			grid-template-columns: 1fr;
		}

		.wv-bank-grid {
			grid-template-columns: repeat(4, 1fr);
		}
	}

	@media (max-width: 700px) {
		.wv-hero__inner {
			padding: 22px 18px;
			flex-direction: column;
			align-items: flex-start;
		}

		.wv-hero__bal {
			width: 100%;
			text-align: left;
			display: flex;
			align-items: center;
			gap: 16px;
		}

		.wv-hero__bal-lbl {
			margin-bottom: 0;
		}

		.wv-hero__chips {
			flex-direction: column;
		}

		.wv-hero__chip {
			padding: 10px 14px;
		}

		.wv-bank-grid {
			grid-template-columns: 1fr 1fr;
		}

		.wv-card__head {
			padding: 12px 16px;
		}

		.wv-card__body {
			padding: 14px 16px;
		}

		.wv-card__desc {
			margin: 0 16px 12px;
		}

		.wv-filter-bar {
			gap: 5px;
		}

		.wv-pill {
			font-size: 11.5px;
			padding: 5px 11px;
		}
	}

	@media (max-width: 480px) {
		.wv-hero__bal-val {
			font-size: 28px;
		}

		.wv-hero__title {
			font-size: 20px;
		}
	}
</style>

<div class="wv-root">

	<!-- ── HERO ── -->
	<header class="wv-hero" data-wv-reveal>
		<div class="wv-hero__bg"></div>
		<div class="wv-hero__inner">
			<div class="wv-hero__left">
				<p class="wv-hero__eye">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<rect x="2" y="5" width="20" height="14" rx="3" />
						<path d="M2 10h20" />
					</svg>
					Wallet
				</p>
				<h1 class="wv-hero__title">Balance &amp; Transactions</h1>
				<p class="wv-hero__sub">Manage deposits, withdrawals, bank details, and your full transaction history.</p>
				<div class="wv-hero__chips">
					<div class="wv-hero__chip">
						<span class="wv-hero__chip-lbl">Total Balance</span>
						<span class="wv-hero__chip-val">₹<?php echo number_format((float)$user->wallet_balance, 2); ?></span>
					</div>
					<div class="wv-hero__chip wv-hero__chip--g">
						<span class="wv-hero__chip-lbl">Total Winnings</span>
						<span class="wv-hero__chip-val">₹<?php echo number_format((float)$total_winnings, 2); ?></span>
					</div>
					<div class="wv-hero__chip wv-hero__chip--r">
						<span class="wv-hero__chip-lbl">Total Withdrawn</span>
						<span class="wv-hero__chip-val">₹<?php echo number_format((float)$total_withdrawn, 2); ?></span>
					</div>
					<div class="wv-hero__chip wv-hero__chip--g">
						<span class="wv-hero__chip-lbl">Total Deposited</span>
						<span class="wv-hero__chip-val">₹<?php echo number_format($total_deposited, 2); ?></span>
					</div>
				</div>
				<a href="<?php echo site_url('wallet/add-balance'); ?>" class="wv-hero__cta">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
						<line x1="12" y1="5" x2="12" y2="19" />
						<line x1="5" y1="12" x2="19" y2="12" />
					</svg>
					Add Balance
				</a>
			</div>
			<div class="wv-hero__bal">
				<p class="wv-hero__bal-lbl">Available Balance</p>
				<p class="wv-hero__bal-val">₹<?php echo number_format((float)$user->wallet_balance, 2); ?></p>
				<div class="wv-hero__bal-foot">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
					</svg>
					Secured &amp; Encrypted
				</div>
			</div>
		</div>
	</header>

	<!-- ── ACTION CARDS ── -->
	<div class="wv-actions">

		<!-- WITHDRAW -->
		<div class="wv-card" data-wv-reveal>
			<div class="wv-card__head">
				<div class="wv-card__ico <?php echo $bank_details_saved ? 'wv-card__ico--red' : 'wv-card__ico--gray'; ?>">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
						<line x1="12" y1="5" x2="12" y2="19" />
						<polyline points="19 12 12 19 5 12" />
					</svg>
				</div>
				<div>
					<p class="wv-card__eyebrow">Cash Out</p>
					<h2 class="wv-card__title">Withdraw Funds</h2>
				</div>
			</div>
			<p class="wv-card__desc">
				<?php echo $bank_details_saved
					? 'Withdrawal requests are reviewed and processed by the admin team.'
					: 'You must add your bank account details before requesting a withdrawal.'; ?>
			</p>
			<div class="wv-card__body">
				<form method="post" action="<?php echo site_url('wallet/request-withdrawal'); ?>" class="wv-form">
					<div class="wv-field">
						<label class="wv-lbl" for="wd_amount">Amount (₹)</label>
						<div class="wv-input-wrap">
							<span class="wv-pfx">₹</span>
							<input type="number" id="wd_amount" name="amount"
								min="1" step="0.01"
								max="<?php echo number_format((float)$user->wallet_balance, 2, '.', ''); ?>"
								placeholder="0.00"
								<?php echo $bank_details_saved ? '' : 'disabled'; ?> required
								class="wv-input wv-input--pfx">
						</div>
						<?php if ($bank_details_saved): ?>
							<p class="wv-hint">Max available: <strong>₹<?php echo number_format((float)$user->wallet_balance, 2); ?></strong></p>
						<?php endif; ?>
					</div>
					<button type="submit"
						class="wv-btn wv-btn--full <?php echo $bank_details_saved ? 'wv-btn--red' : 'wv-btn--disabled'; ?>"
						<?php echo $bank_details_saved ? '' : 'disabled'; ?>>
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
							<line x1="12" y1="5" x2="12" y2="19" />
							<polyline points="19 12 12 19 5 12" />
						</svg>
						Request Withdrawal
					</button>
				</form>
			</div>
		</div>

		<!-- BANK DETAILS -->
		<div class="wv-card" data-wv-reveal>
			<div class="wv-card__head">
				<div class="wv-card__ico wv-card__ico--green">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
						<rect x="3" y="5" width="18" height="14" rx="2" />
						<path d="M3 10h18" />
						<path d="M7 15h.01M11 15h2" />
					</svg>
				</div>
				<div>
					<p class="wv-card__eyebrow">Linked Account</p>
					<h2 class="wv-card__title">Bank Details</h2>
				</div>
				<?php if ($bank_details_saved && !$edit_bank_details): ?>
					<a class="wv-icon-btn" href="<?php echo site_url('wallet?edit_bank=1&history=' . $history_filter); ?>" title="Edit">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
							<path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" />
							<path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
						</svg>
					</a>
				<?php endif; ?>
			</div>
			<div class="wv-card__body">
				<?php if ($edit_bank_details): ?>
					<form method="post" action="<?php echo site_url('wallet/save-bank-details'); ?>" class="wv-bank-form">
						<div class="wv-field">
							<label class="wv-lbl" for="bank_account_holder_name">Account Holder Name</label>
							<input type="text" id="bank_account_holder_name" name="bank_account_holder_name"
								value="<?php echo set_value('bank_account_holder_name', $user->bank_account_holder_name); ?>"
								required placeholder="Full legal name" class="wv-input">
						</div>
						<div class="wv-field">
							<label class="wv-lbl" for="bank_name">Bank Name</label>
							<input type="text" id="bank_name" name="bank_name"
								value="<?php echo set_value('bank_name', $user->bank_name); ?>"
								required placeholder="e.g. HDFC Bank" class="wv-input">
						</div>
						<div class="wv-field">
							<label class="wv-lbl" for="bank_account_number">Account Number</label>
							<input type="text" id="bank_account_number" name="bank_account_number"
								value="<?php echo set_value('bank_account_number', $user->bank_account_number); ?>"
								required placeholder="Enter account number" class="wv-input">
						</div>
						<div class="wv-field">
							<label class="wv-lbl" for="bank_ifsc_code">IFSC Code</label>
							<input type="text" id="bank_ifsc_code" name="bank_ifsc_code"
								value="<?php echo set_value('bank_ifsc_code', $user->bank_ifsc_code); ?>"
								required placeholder="e.g. HDFC0001234" class="wv-input">
						</div>
						<div class="wv-bank-form__actions">
							<?php if ($bank_details_saved): ?>
								<a class="wv-btn wv-btn--ghost" href="<?php echo site_url('wallet?history=' . $history_filter); ?>">Cancel</a>
							<?php endif; ?>
							<button type="submit" class="wv-btn wv-btn--blue">
								<?php echo $bank_details_saved ? 'Update Details' : 'Save Details'; ?>
							</button>
						</div>
					</form>
				<?php else: ?>
					<div class="wv-bank-grid">
						<div class="wv-bank-box">
							<span class="wv-bank-box__lbl">Account Holder</span>
							<strong class="wv-bank-box__val"><?php echo html_escape($user->bank_account_holder_name ?: '—'); ?></strong>
						</div>
						<div class="wv-bank-box">
							<span class="wv-bank-box__lbl">Bank Name</span>
							<strong class="wv-bank-box__val"><?php echo html_escape($user->bank_name ?: '—'); ?></strong>
						</div>
						<div class="wv-bank-box wv-bank-box--acc">
							<span class="wv-bank-box__lbl">Account Number</span>
							<strong class="wv-bank-box__val wv-bank-box__val--mono"><?php echo html_escape($user->bank_account_number ?: '—'); ?></strong>
						</div>
						<div class="wv-bank-box wv-bank-box--acc">
							<span class="wv-bank-box__lbl">IFSC Code</span>
							<strong class="wv-bank-box__val wv-bank-box__val--mono"><?php echo html_escape($user->bank_ifsc_code ?: '—'); ?></strong>
						</div>
					</div>
					<?php if (!$bank_details_saved): ?>
						<a class="wv-btn wv-btn--outline wv-btn--full" href="<?php echo site_url('wallet?edit_bank=1&history=' . $history_filter); ?>" style="margin-top:14px;">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
								<line x1="12" y1="5" x2="12" y2="19" />
								<line x1="5" y1="12" x2="19" y2="12" />
							</svg>
							Add Bank Details
						</a>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		</div>

	</div><!-- /wv-actions -->

	<!-- ── TRANSACTION HISTORY ── -->
	<section class="wv-tx-section" data-wv-reveal>
		<div class="wv-tx-head">
			<div class="wv-tx-head__top">
				<div>
					<p class="wv-tx-eye">History</p>
					<h2 class="wv-tx-title">Transaction History</h2>
				</div>
				<span class="wv-tx-count" id="wvCount"><?php echo $wallet_history_count; ?> records</span>
				<div class="spacer"></div>
			</div>
			<div class="wv-filter-bar">
				<?php
				$wv_filters = [
					'all'         => ['label' => 'All',         'color' => '#2563eb'],
					'deposits'    => ['label' => 'Deposits',    'color' => '#7c3aed'],
					'winnings'    => ['label' => 'Winnings',    'color' => '#16a34a'],
					'withdrawals' => ['label' => 'Withdrawals', 'color' => '#dc2626'],
					'trades'      => ['label' => 'Trades',      'color' => '#d97706'],
					'refunds'     => ['label' => 'Refunds',     'color' => '#0891b2'],
				];
				foreach ($wv_filters as $fk => $fv): ?>
					<button class="wv-pill <?php echo $history_filter === $fk ? 'is-active' : ''; ?>"
						data-filter="<?php echo $fk; ?>"
						data-color="<?php echo $fv['color']; ?>"
						style="<?php echo $history_filter === $fk ? '--pill-color:' . $fv['color'] : ''; ?>">
						<span class="wv-pill__dot"></span>
						<?php echo $fv['label']; ?>
					</button>
				<?php endforeach; ?>
				<div class="wv-rows-wrap">
					<button class="wv-rows-btn" id="wvRowsBtn">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
							<line x1="8" y1="6" x2="21" y2="6" />
							<line x1="8" y1="12" x2="21" y2="12" />
							<line x1="8" y1="18" x2="21" y2="18" />
							<line x1="3" y1="6" x2="3.01" y2="6" />
							<line x1="3" y1="12" x2="3.01" y2="12" />
							<line x1="3" y1="18" x2="3.01" y2="18" />
						</svg>
						<span id="wvRowsLbl">10 rows</span>
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" id="wvChevron">
							<polyline points="6 9 12 15 18 9" />
						</svg>
					</button>
					<div class="wv-rows-dd" id="wvRowsDd">
						<div class="wv-rows-opt is-active" data-rows="10">10 rows</div>
						<div class="wv-rows-opt" data-rows="25">25 rows</div>
						<div class="wv-rows-opt" data-rows="50">50 rows</div>
						<div class="wv-rows-opt" data-rows="100">100 rows</div>
						<div class="wv-rows-opt" data-rows="99999">All rows</div>
					</div>
				</div>
			</div>
		</div>

		<div class="wv-table-wrap">
			<table class="wv-table" id="wvTable">
				<thead>
					<tr>
						<th>#</th>
						<th>Type</th>
						<th>Description</th>
						<th>Amount</th>
						<th>Status</th>
						<th>Date &amp; Time</th>
					</tr>
				</thead>
				<tbody id="wvBody">

					<?php if (!empty($withdrawals)):
						foreach ($withdrawals as $wr):
							$ws = strtolower(trim((string)$wr->status));
							if (in_array($ws, ['approved', 'success', 'completed'])) {
								$sl = 'Success';
								$sc = 'wv-badge--success';
							} elseif (in_array($ws, ['pending', 'processing'])) {
								$sl = 'Pending';
								$sc = 'wv-badge--pending';
							} elseif (in_array($ws, ['rejected', 'failed', 'declined'])) {
								$sl = 'Rejected';
								$sc = 'wv-badge--rejected';
							} else {
								$sl = ucfirst($ws);
								$sc = 'wv-badge--pending';
							}
					?>
							<tr data-filter-type="withdrawals">
								<td class="wv-td-num">—</td>
								<td><span class="wv-badge wv-badge--withdrawal"><span class="wv-badge-dot"></span>Withdrawal</span></td>
								<td class="wv-td-desc">Withdrawal request</td>
								<td class="wv-td-dr">− ₹<?php echo number_format((float)$wr->amount, 2); ?></td>
								<td><span class="wv-badge <?php echo $sc; ?>"><?php echo html_escape($sl); ?></span></td>
								<td class="wv-td-date"><?php echo html_escape(date('d M Y, h:i A', strtotime($wr->created_at))); ?></td>
							</tr>
					<?php endforeach;
					endif; ?>

					<?php if (!empty($transactions)):
						foreach ($transactions as $index => $tx):
							$is_cr     = strtolower((string)$tx->type) === 'credit';
							$type_lbl  = isset($tx->history_type_label) ? $tx->history_type_label : 'Transaction';
							$b_class   = isset($tx->history_badge_class) ? $tx->history_badge_class : 'wv-badge--default';
							$tx_filter = isset($tx->history_filter) ? $tx->history_filter : 'all';
							$desc      = isset($tx->history_description) ? $tx->history_description : '';

							if (isset($tx->history_type_label)) {
								$sc = isset($tx->history_status_class) ? $tx->history_status_class : ($is_cr ? 'wv-badge--success' : 'wv-badge--processed');
								$sl = isset($tx->history_status_label) ? $tx->history_status_label : ($is_cr ? 'Success' : 'Processed');
							} elseif ($tx->source_type === 'deposit') {
								$type_lbl = 'Deposit';
								$b_class = 'wv-badge--deposit';
								$tx_filter = 'deposits';
								$desc = 'Wallet top-up';
							} elseif ($tx->source_type === 'question_result') {
								$type_lbl = 'Winning';
								$b_class = 'wv-badge--winning';
								$tx_filter = 'winnings';
								$desc = 'Question result payout';
							} elseif ($tx->source_type === 'trade_entry') {
								$type_lbl = 'Trade';
								$b_class = 'wv-badge--trade';
								$tx_filter = 'trades';
								$desc = 'Trade entry fee';
							} elseif ($tx->source_type === 'withdrawal') {
								$type_lbl = 'Withdrawal';
								$b_class = 'wv-badge--withdrawal';
								$tx_filter = 'withdrawals';
								$desc = 'Withdrawal processed';
							} elseif ($tx->source_type === 'referral_bonus') {
								$type_lbl = 'Referral';
								$b_class = 'wv-badge--referral';
								$tx_filter = 'all';
								$desc = 'Referral bonus';
							} elseif ($tx->source_type === 'refund') {
								$type_lbl = 'Refund';
								$b_class = 'wv-badge--refund';
								$tx_filter = 'refunds';
								$desc = 'Refund credited';
							}

							if (!isset($tx->history_type_label)) {
								$sc = $is_cr ? 'wv-badge--success' : 'wv-badge--processed';
								$sl = $is_cr ? 'Success' : 'Processed';
							}
					?>
							<tr data-filter-type="<?php echo $tx_filter; ?>">
								<td class="wv-td-num">—</td>
								<td><span class="wv-badge <?php echo $b_class; ?>"><span class="wv-badge-dot"></span><?php echo html_escape($type_lbl); ?></span></td>
								<td class="wv-td-desc"><?php echo html_escape($desc); ?></td>
								<td class="<?php echo $is_cr ? 'wv-td-cr' : 'wv-td-dr'; ?>">
									<?php echo $is_cr ? '+' : '−'; ?> ₹<?php echo number_format((float)$tx->amount, 2); ?>
								</td>
								<td><span class="wv-badge <?php echo $sc; ?>"><?php echo html_escape($sl); ?></span></td>
								<td class="wv-td-date"><?php echo html_escape(date('d M Y, h:i A', strtotime($tx->created_at))); ?></td>
							</tr>
					<?php endforeach;
					endif; ?>

				</tbody>
			</table>
		</div>

		<div class="wv-pagination" id="wvPagination">
			<span class="wv-page-info" id="wvPageInfo">Showing 1–10</span>
			<div class="wv-page-btns" id="wvPageBtns"></div>
		</div>
	</section>

</div><!-- /.wv-root -->

<script>
	(function() {
		'use strict';

		/* reveal */
		document.querySelectorAll('[data-wv-reveal]').forEach(function(el, i) {
			el.style.transitionDelay = (i * 70) + 'ms';
			requestAnimationFrame(function() {
				requestAnimationFrame(function() {
					el.classList.add('revealed');
				});
			});
		});

		/* alerts */
		document.querySelectorAll('.wv-alert').forEach(function(el) {
			setTimeout(function() {
				el.style.transition = 'opacity .4s ease, transform .4s ease';
				el.style.opacity = '0';
				el.style.transform = 'translateY(-6px)';
				setTimeout(function() {
					el.remove();
				}, 420);
			}, 5000);
		});

		/* rows dropdown */
		var rowsBtn = document.getElementById('wvRowsBtn');
		var rowsDd = document.getElementById('wvRowsDd');
		var rowsLbl = document.getElementById('wvRowsLbl');
		if (rowsBtn) {
			rowsBtn.addEventListener('click', function(e) {
				e.stopPropagation();
				var open = rowsDd.classList.toggle('open');
				rowsBtn.classList.toggle('open', open);
			});
			document.addEventListener('click', function() {
				rowsDd.classList.remove('open');
				rowsBtn.classList.remove('open');
			});
		}

		var currentFilter = '<?php echo $history_filter; ?>';
		var rowsPerPage = 10;
		var currentPage = 1;

		document.querySelectorAll('.wv-rows-opt').forEach(function(opt) {
			opt.addEventListener('click', function() {
				rowsPerPage = parseInt(this.dataset.rows, 10);
				currentPage = 1;
				rowsLbl.textContent = rowsPerPage >= 99999 ? 'All rows' : rowsPerPage + ' rows';
				document.querySelectorAll('.wv-rows-opt').forEach(function(o) {
					o.classList.remove('is-active');
				});
				this.classList.add('is-active');
				rowsDd.classList.remove('open');
				rowsBtn.classList.remove('open');
				render();
			});
		});

		document.querySelectorAll('.wv-pill').forEach(function(pill) {
			pill.addEventListener('click', function() {
				currentFilter = this.dataset.filter;
				currentPage = 1;
				var color = this.dataset.color || '';
				document.querySelectorAll('.wv-pill').forEach(function(p) {
					p.classList.remove('is-active');
					p.style.removeProperty('--pill-color');
				});
				this.classList.add('is-active');
				if (color) this.style.setProperty('--pill-color', color);
				render();
			});
		});

		function getRows() {
			var all = Array.from(document.querySelectorAll('#wvBody tr[data-filter-type]'));
			return currentFilter === 'all' ? all : all.filter(function(r) {
				return r.dataset.filterType === currentFilter;
			});
		}

		function render() {
			var rows = getRows();
			var total = rows.length;
			var limit = rowsPerPage >= 99999 ? total : rowsPerPage;
			var pages = Math.max(1, Math.ceil(total / (limit || 1)));
			currentPage = Math.min(currentPage, pages);
			var start = (currentPage - 1) * limit;
			var end = start + limit;

			Array.from(document.querySelectorAll('#wvBody tr[data-filter-type]')).forEach(function(r) {
				r.style.display = 'none';
			});
			rows.forEach(function(r, i) {
				r.style.display = (i >= start && i < end) ? '' : 'none';
				var nc = r.querySelector('.wv-td-num');
				if (nc) nc.textContent = i + 1;
			});

			var emptyRow = document.getElementById('wvEmpty');
			if (total === 0) {
				if (!emptyRow) {
					emptyRow = document.createElement('tr');
					emptyRow.id = 'wvEmpty';
					emptyRow.className = 'wv-empty';
					emptyRow.innerHTML = '<td colspan="6"><svg class="wv-empty-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>No transactions found for this filter.</td>';
					document.getElementById('wvBody').appendChild(emptyRow);
				}
				emptyRow.style.display = '';
			} else if (emptyRow) {
				emptyRow.style.display = 'none';
			}

			var countEl = document.getElementById('wvCount');
			if (countEl) countEl.textContent = total + ' record' + (total !== 1 ? 's' : '');

			var infoEl = document.getElementById('wvPageInfo');
			if (infoEl) infoEl.textContent = total === 0 ? 'No records' : 'Showing ' + (start + 1) + '–' + Math.min(end, total) + ' of ' + total;

			var bc = document.getElementById('wvPageBtns');
			bc.innerHTML = '';
			var pb = makeBtn('‹', currentPage <= 1);
			pb.addEventListener('click', function() {
				if (currentPage > 1) {
					currentPage--;
					render();
				}
			});
			bc.appendChild(pb);

			var lastDot = false;
			for (var p = 1; p <= pages; p++) {
				var show = p === 1 || p === pages || (p >= currentPage - 2 && p <= currentPage + 2);
				if (show) {
					lastDot = false;
					bc.appendChild(makeBtn(p, false, p === currentPage));
				} else if (!lastDot) {
					lastDot = true;
					var d = document.createElement('span');
					d.className = 'wv-page-dots';
					d.textContent = '…';
					bc.appendChild(d);
				}
			}
			bc.querySelectorAll('.wv-page-btn:not([disabled])').forEach(function(btn) {
				var pg = parseInt(btn.textContent, 10);
				if (!isNaN(pg)) btn.addEventListener('click', (function(n) {
					return function() {
						currentPage = n;
						render();
					};
				}(pg)));
			});

			var nb = makeBtn('›', currentPage >= pages);
			nb.addEventListener('click', function() {
				if (currentPage < pages) {
					currentPage++;
					render();
				}
			});
			bc.appendChild(nb);

			var pagEl = document.getElementById('wvPagination');
			if (pagEl) pagEl.style.display = (pages <= 1 && total <= limit) ? 'none' : 'flex';
		}

		function makeBtn(label, disabled, active) {
			var b = document.createElement('button');
			b.className = 'wv-page-btn' + (active ? ' is-active' : '');
			b.textContent = label;
			b.disabled = !!disabled;
			return b;
		}

		render();
	}());
</script>