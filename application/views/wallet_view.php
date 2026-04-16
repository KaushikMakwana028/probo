<?php
// wallet_view.php — Enhanced UI v3 (Perfect Mobile Responsive)
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
<style>
	/* ══════════════════════════════════════════════════════════════
	   RESET & BASE - Enhanced Mobile
	══════════════════════════════════════════════════════════════ */
	*,
	*::before,
	*::after {
		box-sizing: border-box;
		margin: 0;
		padding: 0;
		-webkit-tap-highlight-color: rgba(0, 0, 0, 0);
	}

	:root {
		/* Surfaces */
		--surf-0: #ffffff;
		--surf-1: #f7f8fa;
		--surf-2: #eef0f4;
		--surf-3: #e3e6ec;

		/* Borders */
		--bdr: #e2e5ea;
		--bdr-2: #cdd1d9;

		/* Ink */
		--ink-1: #0d1117;
		--ink-2: #1e2531;
		--ink-3: #4a5568;
		--ink-4: #7a8699;
		--ink-5: #a9b4c2;

		/* Brand */
		--blue: #2563eb;
		--blue-dk: #1d4ed8;
		--blue-lt: #eff6ff;
		--blue-bd: #bfdbfe;
		--blue-tx: #1e40af;

		--green: #16a34a;
		--green-lt: #f0fdf4;
		--green-bd: #bbf7d0;
		--green-tx: #166534;

		--red: #dc2626;
		--red-dk: #b91c1c;
		--red-lt: #fef2f2;
		--red-bd: #fecaca;
		--red-tx: #991b1b;

		--amber: #d97706;
		--amber-lt: #fffbeb;
		--amber-bd: #fde68a;
		--amber-tx: #92400e;

		--purple: #7c3aed;
		--purple-lt: #faf5ff;
		--purple-bd: #e9d5ff;
		--purple-tx: #5b21b6;

		--cyan: #0891b2;
		--cyan-lt: #ecfeff;
		--cyan-bd: #cffafe;
		--cyan-tx: #164e63;

		--pink: #db2777;
		--pink-lt: #fdf2f8;
		--pink-bd: #fbcfe8;
		--pink-tx: #831843;

		/* Typography */
		--font: 'Roboto', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
		--font-mono: 'Roboto Mono', 'Courier New', monospace;

		/* Radii */
		--r-xs: 4px;
		--r-sm: 6px;
		--r-md: 10px;
		--r-lg: 14px;
		--r-xl: 18px;
		--r-2xl: 22px;
		--r-pill: 999px;

		/* Shadows */
		--sh-xs: 0 1px 3px rgba(13, 17, 23, .05);
		--sh-sm: 0 2px 8px rgba(13, 17, 23, .06), 0 1px 3px rgba(13, 17, 23, .04);
		--sh-md: 0 4px 16px rgba(13, 17, 23, .08), 0 1px 4px rgba(13, 17, 23, .04);
		--sh-lg: 0 8px 24px rgba(13, 17, 23, .1), 0 2px 8px rgba(13, 17, 23, .05);
	}

	html {
		-webkit-text-size-adjust: 100%;
		-webkit-font-smoothing: antialiased;
		-moz-osx-font-smoothing: grayscale;
		overflow-x: hidden;
		scroll-behavior: smooth;
		/* Prevent horizontal scroll on all devices */
		width: 100%;
		max-width: 100%;
	}

	html,
	body {
		width: 100%;
		max-width: 100vw;
		margin: 0;
		padding: 0;
		overflow-x: hidden;
		position: relative;
	}

	body {
		font-family: var(--font);
		color: var(--ink-1);
		background: var(--surf-1);
		line-height: 1.5;
		overflow-x: hidden;
		min-height: 100vh;
		/* Better mobile scrolling */
		-webkit-overflow-scrolling: touch;
	}

	/* Prevent zoom on input focus (iOS) */
	input,
	select,
	textarea,
	button {
		font-size: 16px;
	}

	@supports (-webkit-touch-callout: none) {

		/* iOS specific styles */
		body {
			min-height: -webkit-fill-available;
		}
	}

	/* ══════════════════════════════════════════════════════════════
	   ROOT CONTAINER - Enhanced
	══════════════════════════════════════════════════════════════ */
	.wv {
		width: 100%;
		max-width: 100%;
		display: flex;
		flex-direction: column;
		gap: 20px;
		padding: 20px 16px 48px;
		overflow-x: hidden;
		position: relative;
	}

	/* Ensure no child element causes overflow */
	.wv>* {
		max-width: 100%;
		overflow-wrap: break-word;
		word-wrap: break-word;
	}

	/* ══════════════════════════════════════════════════════════════
	   ALERTS - Enhanced Mobile
	══════════════════════════════════════════════════════════════ */
	.wv-alert {
		display: flex;
		align-items: flex-start;
		gap: 12px;
		padding: 14px 18px;
		border-radius: var(--r-lg);
		font-size: 13.5px;
		font-weight: 500;
		border: 1px solid transparent;
		animation: wvSlideIn .3s cubic-bezier(0.16, 1, 0.3, 1);
		box-shadow: var(--sh-sm);
		max-width: 100%;
		overflow: hidden;
	}

	@keyframes wvSlideIn {
		from {
			opacity: 0;
			transform: translateY(-12px);
		}

		to {
			opacity: 1;
			transform: none;
		}
	}

	.wv-alert--ok {
		background: var(--green-lt);
		border-color: var(--green-bd);
		color: var(--green-tx);
	}

	.wv-alert--err {
		background: var(--red-lt);
		border-color: var(--red-bd);
		color: var(--red-tx);
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
		width: 14px;
		height: 14px;
	}

	.wv-alert--ok .wv-alert__ico {
		background: var(--green-bd);
	}

	.wv-alert--err .wv-alert__ico {
		background: var(--red-bd);
	}

	.wv-alert span {
		flex: 1;
		line-height: 1.5;
		min-width: 0;
		overflow-wrap: break-word;
	}

	.wv-alert__close {
		margin-left: auto;
		background: none;
		border: none;
		cursor: pointer;
		color: inherit;
		opacity: .5;
		font-size: 20px;
		padding: 0 8px;
		line-height: 1;
		font-family: var(--font);
		transition: opacity .2s;
		flex-shrink: 0;
		/* Better touch target */
		min-width: 44px;
		min-height: 44px;
		display: flex;
		align-items: center;
		justify-content: center;
	}

	.wv-alert__close:hover,
	.wv-alert__close:active {
		opacity: 1;
	}

	/* ══════════════════════════════════════════════════════════════
	   HERO SECTION - Enhanced Mobile
	══════════════════════════════════════════════════════════════ */
	.wv-hero {
		border-radius: var(--r-2xl);
		background: #0b1022;
		position: relative;
		overflow: hidden;
		box-shadow: var(--sh-md);
		max-width: 100%;
	}

	.wv-hero__orbs {
		position: absolute;
		inset: 0;
		pointer-events: none;
		background:
			radial-gradient(ellipse 60% 90% at 95% 50%, rgba(37, 99, 235, .4) 0%, transparent 65%),
			radial-gradient(ellipse 45% 65% at 5% 20%, rgba(124, 58, 237, .22) 0%, transparent 60%),
			radial-gradient(ellipse 45% 55% at 50% 115%, rgba(16, 185, 129, .14) 0%, transparent 60%);
	}

	.wv-hero__grid {
		position: absolute;
		inset: 0;
		background-image:
			linear-gradient(rgba(255, 255, 255, .02) 1px, transparent 1px),
			linear-gradient(90deg, rgba(255, 255, 255, .02) 1px, transparent 1px);
		background-size: 40px 40px;
		pointer-events: none;
		opacity: 0.6;
	}

	.wv-hero__inner {
		position: relative;
		z-index: 1;
		display: flex;
		justify-content: space-between;
		align-items: center;
		gap: 32px;
		padding: 40px 44px;
		flex-wrap: wrap;
	}

	.wv-hero__left {
		flex: 1;
		min-width: 0;
	}

	.wv-hero__eyebrow {
		display: inline-flex;
		align-items: center;
		gap: 7px;
		font-size: 10px;
		font-weight: 700;
		letter-spacing: .14em;
		text-transform: uppercase;
		color: rgba(255, 255, 255, .38);
		margin-bottom: 12px;
	}

	.wv-hero__eyebrow svg {
		width: 13px;
		height: 13px;
		flex-shrink: 0;
	}

	.wv-hero__title {
		font-size: clamp(20px, 5vw, 38px);
		font-weight: 700;
		color: #fff;
		letter-spacing: -.03em;
		line-height: 1.1;
		margin-bottom: 10px;
		word-wrap: break-word;
		overflow-wrap: break-word;
		hyphens: auto;
	}

	.wv-hero__sub {
		font-size: clamp(13px, 2.5vw, 14px);
		font-weight: 300;
		color: rgba(255, 255, 255, .45);
		line-height: 1.6;
		max-width: 460px;
		margin-bottom: 28px;
	}

	.wv-hero__stats {
		display: flex;
		gap: 12px;
		flex-wrap: wrap;
		margin-bottom: 24px;
	}

	.wv-hero__stat {
		padding: 14px 20px;
		border-radius: var(--r-lg);
		background: rgba(255, 255, 255, .06);
		border: 1px solid rgba(255, 255, 255, .09);
		min-width: 140px;
		transition: all .3s cubic-bezier(0.16, 1, 0.3, 1);
		flex: 1;
		/* Prevent overflow */
		overflow: hidden;
	}

	.wv-hero__stat:hover,
	.wv-hero__stat:active {
		border-color: rgba(255, 255, 255, .18);
		background: rgba(255, 255, 255, .08);
		transform: translateY(-2px);
	}

	.wv-hero__stat--green {
		border-color: rgba(34, 197, 94, .25);
		background: rgba(34, 197, 94, .08);
	}

	.wv-hero__stat--red {
		border-color: rgba(244, 63, 94, .25);
		background: rgba(244, 63, 94, .08);
	}

	.wv-hero__stat-lbl {
		display: block;
		font-size: clamp(9px, 2vw, 10px);
		font-weight: 700;
		letter-spacing: .11em;
		text-transform: uppercase;
		color: rgba(255, 255, 255, .38);
		margin-bottom: 8px;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
	}

	.wv-hero__stat-val {
		display: block;
		font-size: clamp(16px, 4vw, 20px);
		font-weight: 700;
		color: #fff;
		letter-spacing: -.025em;
		overflow: hidden;
		text-overflow: ellipsis;
	}

	.wv-hero__stat--green .wv-hero__stat-val {
		color: #86efac;
	}

	.wv-hero__stat--red .wv-hero__stat-val {
		color: #fda4af;
	}

	.wv-hero__cta {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		gap: 9px;
		padding: 12px 26px;
		border-radius: var(--r-pill);
		background: var(--blue);
		border: 1px solid rgba(255, 255, 255, .15);
		color: #fff;
		font-family: var(--font);
		font-size: 14px;
		font-weight: 700;
		letter-spacing: -.01em;
		text-decoration: none;
		cursor: pointer;
		box-shadow: 0 4px 20px rgba(37, 99, 235, .45);
		transition: all .25s cubic-bezier(0.16, 1, 0.3, 1);
		/* Better touch target */
		min-height: 48px;
		white-space: nowrap;
	}

	.wv-hero__cta svg {
		width: 15px;
		height: 15px;
		flex-shrink: 0;
	}

	.wv-hero__cta:hover,
	.wv-hero__cta:active {
		background: var(--blue-dk);
		transform: translateY(-2px);
		box-shadow: 0 8px 28px rgba(37, 99, 235, .55);
	}

	.wv-hero__cta:active {
		transform: translateY(0);
	}

	/* Balance bubble */
	.wv-hero__bal {
		flex-shrink: 0;
		padding: 32px 38px;
		border-radius: var(--r-xl);
		background: rgba(255, 255, 255, .07);
		border: 1px solid rgba(255, 255, 255, .12);
		backdrop-filter: blur(20px);
		-webkit-backdrop-filter: blur(20px);
		text-align: center;
		min-width: 220px;
		transition: all .3s cubic-bezier(0.16, 1, 0.3, 1);
	}

	.wv-hero__bal:hover {
		background: rgba(255, 255, 255, .09);
		border-color: rgba(255, 255, 255, .18);
	}

	.wv-hero__bal-lbl {
		display: block;
		font-size: 10px;
		font-weight: 700;
		letter-spacing: .14em;
		text-transform: uppercase;
		color: rgba(255, 255, 255, .38);
		margin-bottom: 12px;
	}

	.wv-hero__bal-amt {
		display: block;
		font-size: clamp(28px, 8vw, 42px);
		font-weight: 700;
		color: #fff;
		letter-spacing: -.035em;
		line-height: 1;
		margin-bottom: 16px;
		overflow: hidden;
		text-overflow: ellipsis;
	}

	.wv-hero__bal-note {
		display: flex;
		align-items: center;
		justify-content: center;
		gap: 6px;
		font-size: 11px;
		color: rgba(255, 255, 255, .3);
		font-weight: 500;
	}

	.wv-hero__bal-note svg {
		width: 12px;
		height: 12px;
		flex-shrink: 0;
	}

	/* ══════════════════════════════════════════════════════════════
	   CARD GRID - Enhanced Mobile
	══════════════════════════════════════════════════════════════ */
	.wv-grid {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(min(320px, 100%), 1fr));
		gap: 20px;
		width: 100%;
		max-width: 100%;
	}

	/* ══════════════════════════════════════════════════════════════
	   CARD - Enhanced Mobile
	══════════════════════════════════════════════════════════════ */
	.wv-card {
		background: var(--surf-0);
		border: 1px solid var(--bdr);
		border-radius: var(--r-xl);
		box-shadow: var(--sh-xs);
		overflow: hidden;
		transition: all .3s cubic-bezier(0.16, 1, 0.3, 1);
		max-width: 100%;
	}

	.wv-card:hover {
		box-shadow: var(--sh-md);
		transform: translateY(-2px);
	}

	.wv-card__head {
		padding: 18px 22px 16px;
		border-bottom: 1px solid var(--bdr);
		display: flex;
		align-items: center;
		gap: 14px;
		flex-wrap: wrap;
	}

	.wv-card__icon {
		width: 38px;
		height: 38px;
		border-radius: var(--r-md);
		display: flex;
		align-items: center;
		justify-content: center;
		flex-shrink: 0;
		transition: transform .2s;
	}

	.wv-card:hover .wv-card__icon {
		transform: scale(1.08);
	}

	.wv-card__icon svg {
		width: 16px;
		height: 16px;
	}

	.wv-card__icon--blue {
		background: var(--blue-lt);
		color: var(--blue);
		border: 1px solid var(--blue-bd);
	}

	.wv-card__icon--red {
		background: var(--red-lt);
		color: var(--red);
		border: 1px solid var(--red-bd);
	}

	.wv-card__icon--green {
		background: var(--green-lt);
		color: var(--green);
		border: 1px solid var(--green-bd);
	}

	.wv-card__icon--muted {
		background: var(--surf-2);
		color: var(--ink-4);
		border: 1px solid var(--bdr);
	}

	.wv-card__meta {
		flex: 1;
		min-width: 0;
		overflow: hidden;
	}

	.wv-card__label {
		font-size: 10px;
		font-weight: 700;
		letter-spacing: .1em;
		text-transform: uppercase;
		color: var(--ink-5);
		margin-bottom: 3px;
	}

	.wv-card__title {
		font-size: 15px;
		font-weight: 700;
		color: var(--ink-1);
		letter-spacing: -.018em;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
	}

	.wv-card__edit-btn {
		width: 44px;
		height: 44px;
		border-radius: var(--r-sm);
		border: 1px solid var(--bdr-2);
		background: var(--surf-1);
		display: flex;
		align-items: center;
		justify-content: center;
		color: var(--ink-3);
		text-decoration: none;
		flex-shrink: 0;
		transition: all .2s;
	}

	.wv-card__edit-btn svg {
		width: 14px;
		height: 14px;
	}

	.wv-card__edit-btn:hover,
	.wv-card__edit-btn:active {
		background: var(--surf-2);
		color: var(--blue);
		border-color: var(--blue-bd);
	}

	.wv-card__desc {
		font-size: 13px;
		color: var(--ink-4);
		line-height: 1.6;
		padding: 15px 22px;
		border-bottom: 1px solid var(--bdr);
	}

	.wv-card__body {
		padding: 22px;
	}

	/* ══════════════════════════════════════════════════════════════
	   FORM - Enhanced Mobile
	══════════════════════════════════════════════════════════════ */
	.wv-form {
		display: flex;
		flex-direction: column;
		gap: 16px;
	}

	.wv-field {
		display: flex;
		flex-direction: column;
		gap: 6px;
	}

	.wv-label {
		font-size: 11px;
		font-weight: 700;
		letter-spacing: .09em;
		text-transform: uppercase;
		color: var(--ink-3);
	}

	.wv-input-wrap {
		position: relative;
		display: flex;
		align-items: center;
		width: 100%;
	}

	.wv-pfx {
		position: absolute;
		left: 14px;
		font-size: 15px;
		font-weight: 700;
		color: var(--ink-4);
		pointer-events: none;
		z-index: 1;
	}

	.wv-input {
		width: 100%;
		min-height: 48px;
		padding: 0 14px;
		border: 1.5px solid var(--bdr-2);
		border-radius: var(--r-md);
		background: var(--surf-1);
		color: var(--ink-1);
		font-family: var(--font);
		font-size: 16px;
		font-weight: 400;
		transition: all .2s;
		-webkit-appearance: none;
		appearance: none;
		/* Prevent zoom on iOS */
		touch-action: manipulation;
	}

	.wv-input--pfx {
		padding-left: 32px;
	}

	.wv-input:hover {
		border-color: var(--ink-4);
		background: var(--surf-0);
	}

	.wv-input:focus {
		outline: none;
		border-color: var(--blue);
		box-shadow: 0 0 0 3px rgba(37, 99, 235, .12);
		background: var(--surf-0);
	}

	.wv-input:disabled {
		opacity: .5;
		cursor: not-allowed;
	}

	.wv-input::placeholder {
		color: var(--ink-5);
	}

	.wv-hint {
		font-size: 12px;
		color: var(--ink-5);
		line-height: 1.5;
	}

	.wv-hint strong {
		color: var(--ink-3);
		font-weight: 600;
	}

	/* ══════════════════════════════════════════════════════════════
	   BUTTONS - Enhanced Mobile
	══════════════════════════════════════════════════════════════ */
	.wv-btn {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		gap: 8px;
		padding: 12px 24px;
		border-radius: var(--r-md);
		font-family: var(--font);
		font-size: 16px;
		font-weight: 700;
		border: 1.5px solid transparent;
		cursor: pointer;
		transition: all .2s cubic-bezier(0.16, 1, 0.3, 1);
		text-decoration: none;
		white-space: nowrap;
		letter-spacing: -.01em;
		line-height: 1;
		min-height: 48px;
		/* Better touch target */
		touch-action: manipulation;
		-webkit-tap-highlight-color: transparent;
	}

	.wv-btn svg {
		width: 15px;
		height: 15px;
		flex-shrink: 0;
	}

	.wv-btn--full {
		width: 100%;
	}

	.wv-btn--blue {
		background: var(--blue);
		border-color: var(--blue-dk);
		color: #fff;
		box-shadow: 0 2px 12px rgba(37, 99, 235, .25);
	}

	.wv-btn--blue:hover,
	.wv-btn--blue:active {
		background: var(--blue-dk);
		box-shadow: 0 4px 20px rgba(37, 99, 235, .4);
		transform: translateY(-1px);
	}

	.wv-btn--blue:active {
		transform: translateY(0);
	}

	.wv-btn--red {
		background: var(--red);
		border-color: var(--red-dk);
		color: #fff;
		box-shadow: 0 2px 12px rgba(220, 38, 38, .25);
	}

	.wv-btn--red:hover,
	.wv-btn--red:active {
		background: var(--red-dk);
		box-shadow: 0 4px 20px rgba(220, 38, 38, .4);
		transform: translateY(-1px);
	}

	.wv-btn--red:active {
		transform: translateY(0);
	}

	.wv-btn--ghost {
		background: var(--surf-1);
		border-color: var(--bdr-2);
		color: var(--ink-3);
	}

	.wv-btn--ghost:hover,
	.wv-btn--ghost:active {
		background: var(--surf-2);
		color: var(--ink-2);
	}

	.wv-btn--outline {
		background: var(--surf-0);
		border-color: var(--bdr-2);
		color: var(--ink-2);
	}

	.wv-btn--outline:hover,
	.wv-btn--outline:active {
		background: var(--surf-1);
		border-color: var(--blue);
		color: var(--blue);
	}

	.wv-btn--disabled {
		background: var(--surf-2);
		border-color: var(--bdr);
		color: var(--ink-4);
		cursor: not-allowed;
		box-shadow: none;
	}

	.wv-btn--disabled:hover {
		transform: none;
	}

	/* ══════════════════════════════════════════════════════════════
	   BANK DETAILS - Enhanced Mobile
	══════════════════════════════════════════════════════════════ */
	.wv-bank-form {
		display: flex;
		flex-direction: column;
		gap: 15px;
	}

	.wv-bank-form__footer {
		display: flex;
		justify-content: flex-end;
		gap: 10px;
		padding-top: 6px;
		flex-wrap: wrap;
	}

	.wv-bank-grid {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(min(200px, 100%), 1fr));
		gap: 12px;
	}

	.wv-bank-box {
		padding: 14px 16px;
		border-radius: var(--r-md);
		background: var(--surf-1);
		border: 1px solid var(--bdr);
		transition: all .2s;
		overflow: hidden;
	}

	.wv-bank-box:hover,
	.wv-bank-box:active {
		background: var(--surf-2);
	}

	.wv-bank-box--accent {
		background: var(--blue-lt);
		border-color: var(--blue-bd);
	}

	.wv-bank-box--accent:hover {
		background: #dbeafe;
	}

	.wv-bank-box__lbl {
		display: block;
		font-size: 10px;
		font-weight: 700;
		letter-spacing: .1em;
		text-transform: uppercase;
		color: var(--ink-5);
		margin-bottom: 6px;
	}

	.wv-bank-box--accent .wv-bank-box__lbl {
		color: rgba(30, 64, 175, .6);
	}

	.wv-bank-box__val {
		display: block;
		font-size: 13.5px;
		font-weight: 600;
		color: var(--ink-2);
		word-break: break-all;
		line-height: 1.4;
	}

	.wv-bank-box__val--mono {
		font-family: var(--font-mono);
		font-size: 13px;
		color: var(--blue-tx);
		letter-spacing: .03em;
	}

	/* ══════════════════════════════════════════════════════════════
	   TRANSACTIONS PANEL - Enhanced Mobile
	══════════════════════════════════════════════════════════════ */
	.wv-tx {
		background: var(--surf-0);
		border: 1px solid var(--bdr);
		border-radius: var(--r-xl);
		box-shadow: var(--sh-xs);
		overflow: hidden;
		transition: box-shadow .3s;
		max-width: 100%;
	}

	.wv-tx:hover {
		box-shadow: var(--sh-md);
	}

	.wv-tx__head {
		padding: 24px 26px 0;
	}

	.wv-tx__title-row {
		display: flex;
		align-items: center;
		gap: 14px;
		margin-bottom: 20px;
		flex-wrap: wrap;
	}

	.wv-tx__eyebrow {
		font-size: 10px;
		font-weight: 700;
		letter-spacing: .13em;
		text-transform: uppercase;
		color: var(--ink-5);
		margin-bottom: 3px;
	}

	.wv-tx__h2 {
		font-size: 22px;
		font-weight: 700;
		color: var(--ink-1);
		letter-spacing: -.025em;
	}

	.wv-tx__count {
		display: inline-flex;
		align-items: center;
		padding: 5px 14px;
		border-radius: var(--r-pill);
		background: var(--surf-2);
		border: 1px solid var(--bdr);
		font-size: 12px;
		font-weight: 600;
		color: var(--ink-4);
		white-space: nowrap;
	}

	/* Filter bar - Enhanced for mobile */
	.wv-filters {
		display: flex;
		align-items: center;
		gap: 8px;
		flex-wrap: nowrap;
		overflow-x: auto;
		-webkit-overflow-scrolling: touch;
		padding-bottom: 20px;
		border-bottom: 1px solid var(--bdr);
		/* Hide scrollbar but keep functionality */
		scrollbar-width: none;
		-ms-overflow-style: none;
	}

	.wv-filters::-webkit-scrollbar {
		display: none;
	}

	/* Add scroll indicator for mobile */
	.wv-filters::after {
		content: '';
		position: absolute;
		right: 0;
		top: 0;
		bottom: 20px;
		width: 40px;
		background: linear-gradient(to right, transparent, var(--surf-0));
		pointer-events: none;
		opacity: 0;
		transition: opacity .3s;
	}

	@media (max-width: 640px) {
		.wv-filters::after {
			opacity: 1;
		}
	}

	.wv-pill {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		padding: 8px 16px;
		border-radius: var(--r-pill);
		font-size: 13px;
		font-weight: 600;
		font-family: var(--font);
		border: 1.5px solid var(--bdr-2);
		color: var(--ink-3);
		background: var(--surf-0);
		cursor: pointer;
		transition: all .2s;
		white-space: nowrap;
		min-height: 40px;
		flex-shrink: 0;
		touch-action: manipulation;
		-webkit-tap-highlight-color: transparent;
	}

	.wv-pill__dot {
		width: 5px;
		height: 5px;
		border-radius: 50%;
		background: currentColor;
		opacity: .4;
		flex-shrink: 0;
	}

	.wv-pill:hover,
	.wv-pill:active {
		border-color: var(--blue);
		color: var(--blue);
		background: var(--blue-lt);
	}

	.wv-pill.is-on {
		background: var(--pill-c, var(--blue));
		border-color: transparent;
		color: #fff;
		box-shadow: 0 2px 12px rgba(0, 0, 0, .2);
	}

	.wv-pill.is-on .wv-pill__dot {
		opacity: 1;
		background: rgba(255, 255, 255, .75);
	}

	/* Rows selector - Enhanced */
	.wv-rows-wrap {
		margin-left: auto;
		position: relative;
		flex-shrink: 0;
	}

	.wv-rows-btn {
		display: inline-flex;
		align-items: center;
		gap: 8px;
		padding: 8px 14px;
		border-radius: var(--r-pill);
		font-size: 13px;
		font-weight: 600;
		font-family: var(--font);
		color: var(--ink-3);
		background: var(--surf-1);
		border: 1.5px solid var(--bdr-2);
		cursor: pointer;
		transition: all .2s;
		min-height: 40px;
		white-space: nowrap;
		touch-action: manipulation;
		-webkit-tap-highlight-color: transparent;
	}

	.wv-rows-btn:hover,
	.wv-rows-btn:active {
		background: var(--surf-2);
	}

	.wv-rows-btn svg {
		width: 13px;
		height: 13px;
		flex-shrink: 0;
	}

	#wvChevron {
		transition: transform .25s;
	}

	.wv-rows-btn.open #wvChevron {
		transform: rotate(180deg);
	}

	.wv-rows-dd {
		position: absolute;
		top: calc(100% + 8px);
		right: 0;
		z-index: 60;
		background: var(--surf-0);
		border: 1px solid var(--bdr-2);
		border-radius: var(--r-lg);
		box-shadow: var(--sh-lg);
		min-width: 160px;
		overflow: hidden;
		display: none;
		animation: wvDropdown .2s cubic-bezier(0.16, 1, 0.3, 1);
	}

	@keyframes wvDropdown {
		from {
			opacity: 0;
			transform: translateY(-8px);
		}

		to {
			opacity: 1;
			transform: none;
		}
	}

	.wv-rows-dd.open {
		display: block;
	}

	.wv-rows-opt {
		padding: 12px 18px;
		font-size: 14px;
		font-weight: 500;
		color: var(--ink-2);
		cursor: pointer;
		transition: background .15s;
		display: flex;
		align-items: center;
		justify-content: space-between;
		min-height: 48px;
		touch-action: manipulation;
	}

	.wv-rows-opt:hover,
	.wv-rows-opt:active {
		background: var(--surf-1);
	}

	.wv-rows-opt.is-on {
		color: var(--blue);
		font-weight: 700;
		background: var(--blue-lt);
	}

	.wv-rows-opt.is-on::after {
		content: '';
		width: 6px;
		height: 6px;
		border-radius: 50%;
		background: var(--blue);
		flex-shrink: 0;
	}

	/* Table - Enhanced scroll */
	.wv-table-wrap {
		overflow-x: auto;
		-webkit-overflow-scrolling: touch;
		scrollbar-width: thin;
		scrollbar-color: var(--bdr-2) var(--surf-1);
		max-width: 100%;
	}

	.wv-table-wrap::-webkit-scrollbar {
		height: 8px;
	}

	.wv-table-wrap::-webkit-scrollbar-track {
		background: var(--surf-1);
	}

	.wv-table-wrap::-webkit-scrollbar-thumb {
		background: var(--bdr-2);
		border-radius: 4px;
	}

	.wv-table {
		width: 100%;
		border-collapse: collapse;
		min-width: 650px;
	}

	.wv-table thead tr {
		background: var(--surf-1);
	}

	.wv-table th {
		padding: 12px 20px;
		font-size: 10.5px;
		font-weight: 700;
		letter-spacing: .09em;
		text-transform: uppercase;
		color: var(--ink-4);
		text-align: left;
		border-bottom: 1px solid var(--bdr);
		white-space: nowrap;
	}

	.wv-table td {
		padding: 14px 20px;
		border-top: 1px solid var(--bdr);
		font-size: 13.5px;
		color: var(--ink-1);
		vertical-align: middle;
	}

	.wv-table tbody tr {
		transition: background .15s;
	}

	.wv-table tbody tr:hover,
	.wv-table tbody tr:active {
		background: var(--surf-1);
	}

	.wv-td-num {
		color: var(--ink-5) !important;
		font-size: 12px !important;
		font-family: var(--font-mono);
	}

	.wv-td-desc {
		color: var(--ink-4) !important;
		font-size: 13px !important;
	}

	.wv-td-date {
		color: var(--ink-4) !important;
		font-size: 12.5px !important;
		white-space: nowrap;
	}

	.wv-td-cr {
		color: var(--green) !important;
		font-weight: 700;
	}

	.wv-td-dr {
		color: var(--red) !important;
		font-weight: 700;
	}

	/* Mobile list (hidden by default) */
	.wv-mobile-list {
		display: none;
		gap: 14px;
		padding: 14px;
	}

	.wv-mobile-card {
		background: var(--surf-0);
		border: 1px solid var(--bdr);
		border-radius: var(--r-lg);
		box-shadow: var(--sh-xs);
		padding: 16px;
		transition: all .2s;
		overflow: hidden;
		max-width: 100%;
	}

	.wv-mobile-card:hover,
	.wv-mobile-card:active {
		box-shadow: var(--sh-sm);
		transform: translateY(-1px);
	}

	.wv-mobile-card__row {
		display: grid;
		grid-template-columns: 110px minmax(0, 1fr);
		gap: 12px;
		padding: 10px 0;
		align-items: start;
	}

	.wv-mobile-card__row+.wv-mobile-card__row {
		border-top: 1px solid var(--surf-2);
	}

	.wv-mobile-card__label {
		font-size: 10.5px;
		font-weight: 700;
		letter-spacing: .08em;
		text-transform: uppercase;
		color: var(--ink-5);
		padding-top: 2px;
	}

	.wv-mobile-card__value {
		min-width: 0;
		text-align: right;
		font-size: 14px;
		font-weight: 500;
		color: var(--ink-1);
		overflow-wrap: anywhere;
		word-break: break-word;
		line-height: 1.5;
	}

	.wv-mobile-card__value .wv-badge {
		margin-left: auto;
	}

	/* ══════════════════════════════════════════════════════════════
	   BADGES - Enhanced
	══════════════════════════════════════════════════════════════ */
	.wv-badge {
		display: inline-flex;
		align-items: center;
		gap: 5px;
		padding: 5px 12px;
		border-radius: var(--r-pill);
		font-size: 11.5px;
		font-weight: 700;
		white-space: nowrap;
		flex-shrink: 0;
	}

	.wv-badge-dot {
		width: 4px;
		height: 4px;
		border-radius: 50%;
		background: currentColor;
		flex-shrink: 0;
	}

	.wv-badge--deposit {
		background: var(--purple-lt);
		color: var(--purple-tx);
	}

	.wv-badge--winning {
		background: var(--green-lt);
		color: var(--green-tx);
	}

	.wv-badge--trade {
		background: var(--amber-lt);
		color: var(--amber-tx);
	}

	.wv-badge--withdrawal {
		background: var(--red-lt);
		color: var(--red-tx);
	}

	.wv-badge--referral {
		background: var(--pink-lt);
		color: var(--pink-tx);
	}

	.wv-badge--refund {
		background: var(--cyan-lt);
		color: var(--cyan-tx);
	}

	.wv-badge--default {
		background: var(--surf-2);
		color: var(--ink-3);
	}

	.wv-badge--success {
		background: var(--green-lt);
		color: var(--green-tx);
	}

	.wv-badge--processed {
		background: var(--blue-lt);
		color: var(--blue-tx);
	}

	.wv-badge--pending {
		background: var(--amber-lt);
		color: var(--amber-tx);
	}

	.wv-badge--rejected {
		background: var(--red-lt);
		color: var(--red-tx);
	}

	/* ══════════════════════════════════════════════════════════════
	   EMPTY STATE
	══════════════════════════════════════════════════════════════ */
	.wv-empty td {
		text-align: center;
		padding: 60px 20px;
		color: var(--ink-4);
		font-size: 14px;
	}

	.wv-empty-ico {
		display: block;
		margin: 0 auto 14px;
		width: 32px;
		height: 32px;
		opacity: .25;
	}

	/* ══════════════════════════════════════════════════════════════
	   PAGINATION - Enhanced Mobile
	══════════════════════════════════════════════════════════════ */
	.wv-pagination {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 14px;
		padding: 16px 22px;
		border-top: 1px solid var(--bdr);
		flex-wrap: wrap;
	}

	.wv-page-info {
		font-size: 13px;
		color: var(--ink-4);
		font-weight: 500;
	}

	.wv-page-btns {
		display: flex;
		gap: 6px;
		align-items: center;
		flex-wrap: wrap;
	}

	.wv-page-btn {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		min-width: 40px;
		min-height: 40px;
		padding: 0 10px;
		border-radius: var(--r-md);
		border: 1.5px solid var(--bdr-2);
		background: var(--surf-0);
		color: var(--ink-2);
		font-family: var(--font);
		font-size: 14px;
		font-weight: 600;
		cursor: pointer;
		transition: all .2s;
		touch-action: manipulation;
		-webkit-tap-highlight-color: transparent;
	}

	.wv-page-btn:hover:not(:disabled),
	.wv-page-btn:active:not(:disabled) {
		background: var(--surf-2);
		border-color: var(--blue);
	}

	.wv-page-btn.is-on {
		background: var(--blue);
		color: #fff;
		border-color: var(--blue);
		box-shadow: 0 2px 8px rgba(37, 99, 235, .3);
	}

	.wv-page-btn:disabled {
		opacity: .35;
		cursor: not-allowed;
	}

	.wv-page-dots {
		padding: 0 6px;
		color: var(--ink-4);
		font-size: 14px;
		align-self: center;
	}

	/* ══════════════════════════════════════════════════════════════
	   REVEAL ANIMATION
	══════════════════════════════════════════════════════════════ */
	[data-rev] {
		opacity: 0;
		transform: translateY(16px);
		transition: opacity .5s cubic-bezier(0.16, 1, 0.3, 1),
			transform .5s cubic-bezier(0.16, 1, 0.3, 1);
	}

	[data-rev].on {
		opacity: 1;
		transform: none;
	}

	/* ══════════════════════════════════════════════════════════════
	   RESPONSIVE BREAKPOINTS - ENHANCED
	══════════════════════════════════════════════════════════════ */

	/* ── 1024px and below ── */
	@media (max-width: 1024px) {
		.wv-grid {
			grid-template-columns: 1fr;
		}

		.wv-hero__inner {
			padding: 30px 32px;
			gap: 28px;
		}

		.wv-hero__stats {
			display: grid;
			grid-template-columns: repeat(2, 1fr);
		}
	}

	/* ── 768px and below (Tablet) ── */
	@media (max-width: 768px) {
		.wv {
			padding: 14px 12px 36px;
			gap: 16px;
		}

		.wv-hero__inner {
			flex-direction: column;
			align-items: stretch;
			padding: 24px 20px;
			gap: 22px;
		}

		.wv-hero__left {
			width: 100%;
		}

		.wv-hero__title {
			font-size: clamp(22px, 5vw, 26px);
		}

		.wv-hero__sub {
			font-size: 13px;
			margin-bottom: 20px;
		}

		.wv-hero__stats {
			margin-bottom: 18px;
			gap: 10px;
		}

		.wv-hero__stat {
			min-width: unset;
			padding: 12px 16px;
		}

		.wv-hero__cta {
			width: 100%;
			justify-content: center;
			padding: 14px 24px;
		}

		.wv-hero__bal {
			width: 100%;
			min-width: unset;
			text-align: center;
			padding: 22px 24px;
		}

		.wv-hero__bal-amt {
			font-size: clamp(30px, 7vw, 36px);
		}

		.wv-bank-grid {
			grid-template-columns: 1fr;
		}

		.wv-tx__head {
			padding: 18px 20px 0;
		}

		.wv-filters {
			gap: 7px;
			padding-bottom: 18px;
		}

		.wv-card__body {
			padding: 18px;
		}

		.wv-card__head {
			padding: 15px 18px;
		}

		.wv-card__desc {
			padding: 13px 18px;
		}
	}

	/* ── 640px and below (Mobile) ── */
	@media (max-width: 640px) {
		.wv {
			padding: 10px 10px 32px;
			gap: 14px;
		}

		.wv-alert {
			padding: 12px 14px;
			font-size: 13px;
			gap: 10px;
		}

		.wv-alert__ico {
			width: 20px;
			height: 20px;
		}

		.wv-alert__ico svg {
			width: 13px;
			height: 13px;
		}

		.wv-alert__close {
			min-width: 40px;
			min-height: 40px;
		}

		.wv-hero {
			border-radius: var(--r-xl);
		}

		.wv-hero__inner {
			padding: 20px 16px;
			gap: 18px;
		}

		.wv-hero__title {
			font-size: clamp(20px, 5vw, 23px);
		}

		.wv-hero__sub {
			font-size: 13px;
			margin-bottom: 18px;
		}

		.wv-hero__stats {
			grid-template-columns: 1fr;
			gap: 8px;
			margin-bottom: 16px;
		}

		.wv-hero__stat {
			padding: 12px 14px;
		}

		.wv-hero__stat-val {
			font-size: clamp(16px, 4vw, 18px);
		}

		.wv-hero__cta {
			padding: 13px 22px;
			font-size: 14px;
			min-height: 50px;
		}

		.wv-hero__bal {
			padding: 18px;
		}

		.wv-hero__bal-amt {
			font-size: clamp(28px, 7vw, 32px);
			margin-bottom: 12px;
		}

		.wv-card {
			border-radius: var(--r-lg);
		}

		.wv-card__body {
			padding: 16px;
		}

		.wv-card__head {
			padding: 14px 16px;
		}

		.wv-card__desc {
			padding: 12px 16px;
			font-size: 12.5px;
		}

		.wv-card__icon {
			width: 36px;
			height: 36px;
		}

		.wv-card__icon svg {
			width: 15px;
			height: 15px;
		}

		.wv-card__title {
			font-size: 14px;
		}

		.wv-card__edit-btn {
			width: 40px;
			height: 40px;
		}

		.wv-input {
			min-height: 50px;
			font-size: 16px;
			padding: 0 14px;
		}

		.wv-input--pfx {
			padding-left: 34px;
		}

		.wv-btn {
			font-size: 15px;
			padding: 12px 20px;
			min-height: 50px;
		}

		.wv-field {
			gap: 8px;
		}

		.wv-label {
			font-size: 11px;
		}

		.wv-bank-form {
			gap: 14px;
		}

		.wv-bank-form__footer {
			flex-direction: column-reverse;
			gap: 10px;
		}

		.wv-bank-form__footer .wv-btn {
			width: 100%;
		}

		.wv-bank-box {
			padding: 13px 14px;
		}

		.wv-tx__head {
			padding: 16px 16px 0;
		}

		.wv-tx__title-row {
			flex-direction: column;
			align-items: flex-start;
			gap: 10px;
			margin-bottom: 16px;
		}

		.wv-tx__h2 {
			font-size: 19px;
		}

		.wv-tx__count {
			font-size: 11.5px;
		}

		.wv-filters {
			flex-wrap: nowrap;
			overflow-x: auto;
			-webkit-overflow-scrolling: touch;
			scrollbar-width: none;
			padding-bottom: 16px;
			gap: 6px;
			position: relative;
		}

		.wv-filters::-webkit-scrollbar {
			display: none;
		}

		.wv-pill {
			flex: 0 0 auto;
			font-size: 13px;
			padding: 8px 15px;
			min-height: 40px;
		}

		.wv-rows-wrap {
			width: 100%;
			margin-left: 0;
		}

		.wv-rows-btn {
			width: 100%;
			justify-content: space-between;
			min-height: 44px;
			font-size: 14px;
		}

		.wv-rows-dd {
			left: 0;
			right: 0;
			width: 100%;
		}

		/* Hide desktop table, show mobile cards */
		.wv-table-wrap {
			display: none;
		}

		.wv-mobile-list {
			display: grid;
		}

		.wv-mobile-card {
			padding: 14px;
		}

		.wv-mobile-card__row {
			grid-template-columns: 1fr;
			gap: 6px;
			padding: 9px 0;
		}

		.wv-mobile-card__label {
			font-size: 10.5px;
		}

		.wv-mobile-card__value {
			text-align: left;
			font-size: 14px;
		}

		.wv-mobile-card__value .wv-badge {
			margin-left: 0;
		}

		.wv-pagination {
			flex-direction: column;
			align-items: stretch;
			gap: 12px;
			padding: 14px 16px;
		}

		.wv-page-info {
			text-align: center;
			font-size: 12.5px;
		}

		.wv-page-btns {
			justify-content: center;
		}

		.wv-page-btn {
			min-width: 40px;
			min-height: 40px;
			font-size: 14px;
		}
	}

	/* ── 480px and below (Small Mobile) ── */
	@media (max-width: 480px) {
		.wv {
			padding: 8px 8px 28px;
			gap: 12px;
		}

		.wv-alert {
			padding: 11px 12px;
			font-size: 12.5px;
			gap: 8px;
		}

		.wv-hero__inner {
			padding: 16px 14px;
			gap: 16px;
		}

		.wv-hero__title {
			font-size: clamp(19px, 5vw, 21px);
		}

		.wv-hero__sub {
			font-size: 12.5px;
		}

		.wv-hero__eyebrow {
			font-size: 9.5px;
		}

		.wv-hero__stat {
			padding: 11px 13px;
		}

		.wv-hero__stat-lbl {
			font-size: 9.5px;
		}

		.wv-hero__stat-val {
			font-size: clamp(15px, 4vw, 17px);
		}

		.wv-hero__bal {
			padding: 16px;
		}

		.wv-hero__bal-amt {
			font-size: clamp(26px, 7vw, 28px);
		}

		.wv-hero__bal-note {
			font-size: 10.5px;
		}

		.wv-card__body {
			padding: 14px;
		}

		.wv-card__head {
			padding: 13px 14px;
		}

		.wv-card__desc {
			padding: 11px 14px;
			font-size: 12px;
		}

		.wv-card__title {
			font-size: 13.5px;
		}

		.wv-card__label {
			font-size: 10px;
		}

		.wv-btn {
			font-size: 14px;
			padding: 11px 18px;
			min-height: 48px;
		}

		.wv-btn svg {
			width: 14px;
			height: 14px;
		}

		.wv-input {
			min-height: 48px;
			font-size: 16px;
			padding: 0 12px;
		}

		.wv-input--pfx {
			padding-left: 32px;
		}

		.wv-pfx {
			font-size: 14px;
			left: 12px;
		}

		.wv-bank-box {
			padding: 12px 13px;
		}

		.wv-bank-box__val {
			font-size: 13px;
		}

		.wv-tx__head {
			padding: 14px 14px 0;
		}

		.wv-tx__h2 {
			font-size: 18px;
		}

		.wv-tx__count {
			font-size: 11px;
			padding: 4px 11px;
		}

		.wv-filters {
			gap: 5px;
		}

		.wv-pill {
			font-size: 12.5px;
			padding: 7px 14px;
			min-height: 38px;
		}

		.wv-mobile-list {
			padding: 12px;
			gap: 12px;
		}

		.wv-mobile-card {
			padding: 13px;
		}

		.wv-mobile-card__row {
			padding: 8px 0;
		}

		.wv-mobile-card__label {
			font-size: 10px;
		}

		.wv-mobile-card__value {
			font-size: 13.5px;
		}

		.wv-badge {
			font-size: 11px;
			padding: 4px 10px;
		}

		.wv-pagination {
			padding: 12px 14px;
		}

		.wv-page-btn {
			min-width: 38px;
			min-height: 38px;
			font-size: 13px;
		}
	}

	/* ── 375px and below (Extra Small Mobile) ── */
	@media (max-width: 375px) {
		.wv {
			padding: 6px 6px 24px;
			gap: 10px;
		}

		.wv-alert {
			padding: 10px 11px;
			font-size: 12px;
		}

		.wv-hero__inner {
			padding: 14px 12px;
		}

		.wv-hero__title {
			font-size: clamp(18px, 5vw, 19px);
		}

		.wv-hero__sub {
			font-size: 12px;
		}

		.wv-hero__stat {
			padding: 10px 12px;
		}

		.wv-hero__stat-val {
			font-size: clamp(14px, 4vw, 16px);
		}

		.wv-hero__bal {
			padding: 14px;
		}

		.wv-hero__bal-amt {
			font-size: clamp(24px, 7vw, 26px);
		}

		.wv-card__body {
			padding: 12px;
		}

		.wv-card__head {
			padding: 12px 13px;
		}

		.wv-card__desc {
			padding: 10px 13px;
		}

		.wv-btn {
			font-size: 13px;
			padding: 10px 16px;
			min-height: 46px;
		}

		.wv-input {
			min-height: 46px;
		}

		.wv-mobile-card {
			padding: 12px;
		}

		.wv-tx__head {
			padding: 12px 12px 0;
		}

		.wv-tx__h2 {
			font-size: 17px;
		}

		.wv-page-btn {
			min-width: 36px;
			min-height: 36px;
			font-size: 12.5px;
		}
	}

	/* ── 320px (Smallest Devices) ── */
	@media (max-width: 320px) {
		.wv {
			padding: 5px 5px 20px;
		}

		.wv-hero__inner {
			padding: 12px 10px;
		}

		.wv-hero__title {
			font-size: 17px;
		}

		.wv-hero__sub {
			font-size: 11.5px;
		}

		.wv-hero__bal-amt {
			font-size: 24px;
		}

		.wv-card__body {
			padding: 11px;
		}

		.wv-card__head {
			padding: 11px 12px;
		}

		.wv-btn {
			font-size: 12.5px;
			padding: 9px 14px;
			min-height: 44px;
		}

		.wv-input {
			min-height: 44px;
		}

		.wv-mobile-card {
			padding: 11px;
		}

		.wv-page-btn {
			min-width: 34px;
			min-height: 34px;
			font-size: 12px;
		}
	}

	/* ══════════════════════════════════════════════════════════════
	   LANDSCAPE MOBILE OPTIMIZATIONS
	══════════════════════════════════════════════════════════════ */
	@media (max-height: 500px) and (orientation: landscape) {
		.wv-hero__inner {
			flex-direction: row;
			padding: 20px 24px;
		}

		.wv-hero__stats {
			grid-template-columns: repeat(4, 1fr);
			gap: 8px;
		}

		.wv-hero__bal {
			min-width: 200px;
			padding: 20px 24px;
		}
	}

	/* ══════════════════════════════════════════════════════════════
	   SAFE AREA INSETS (iPhone X+, notches, etc.)
	══════════════════════════════════════════════════════════════ */
	@supports (padding: max(0px)) {
		.wv {
			padding-left: max(10px, env(safe-area-inset-left));
			padding-right: max(10px, env(safe-area-inset-right));
			padding-bottom: max(32px, env(safe-area-inset-bottom));
		}

		.wv-alert {
			margin-left: max(0px, env(safe-area-inset-left));
			margin-right: max(0px, env(safe-area-inset-right));
		}
	}

	/* ══════════════════════════════════════════════════════════════
	   ACCESSIBILITY ENHANCEMENTS
	══════════════════════════════════════════════════════════════ */
	@media (prefers-reduced-motion: reduce) {

		*,
		*::before,
		*::after {
			animation-duration: 0.01ms !important;
			animation-iteration-count: 1 !important;
			transition-duration: 0.01ms !important;
			scroll-behavior: auto !important;
		}

		[data-rev] {
			opacity: 1;
			transform: none;
		}
	}

	/* Focus visible (keyboard navigation) */
	*:focus-visible {
		outline: 2px solid var(--blue);
		outline-offset: 2px;
	}

	/* High contrast mode support */
	@media (prefers-contrast: high) {

		.wv-card,
		.wv-hero,
		.wv-tx {
			border-width: 2px;
		}

		.wv-btn {
			border-width: 2px;
		}
	}
</style>

<?php if ($this->session->flashdata('error')): ?>
	<div class="wv-alert wv-alert--err" role="alert">
		<div class="wv-alert__ico">
			<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
				<circle cx="12" cy="12" r="10" />
				<line x1="12" y1="8" x2="12" y2="12" />
				<line x1="12" y1="16" x2="12.01" y2="16" />
			</svg>
		</div>
		<span><?php echo $this->session->flashdata('error'); ?></span>
		<button class="wv-alert__close" onclick="this.parentElement.remove()" aria-label="Dismiss">&times;</button>
	</div>
<?php endif; ?>

<?php if ($this->session->flashdata('success')): ?>
	<div class="wv-alert wv-alert--ok" role="alert">
		<div class="wv-alert__ico">
			<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
				<polyline points="20 6 9 17 4 12" />
			</svg>
		</div>
		<span><?php echo $this->session->flashdata('success'); ?></span>
		<button class="wv-alert__close" onclick="this.parentElement.remove()" aria-label="Dismiss">&times;</button>
	</div>
<?php endif; ?>

<div class="wv">

	<!-- ══ HERO ══ -->
	<header class="wv-hero" data-rev>
		<div class="wv-hero__orbs" aria-hidden="true"></div>
		<div class="wv-hero__grid" aria-hidden="true"></div>
		<div class="wv-hero__inner">
			<div class="wv-hero__left">
				<p class="wv-hero__eyebrow" aria-hidden="true">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
						<rect x="2" y="5" width="20" height="14" rx="3" />
						<path d="M2 10h20" />
					</svg>
					Wallet Dashboard
				</p>
				<h1 class="wv-hero__title">Balance &amp; Transactions</h1>
				<p class="wv-hero__sub">Manage deposits, withdrawals, bank details, and your complete transaction history.</p>

				<div class="wv-hero__stats" role="list">
					<div class="wv-hero__stat" role="listitem">
						<span class="wv-hero__stat-lbl">Total Balance</span>
						<span class="wv-hero__stat-val">₹<?php echo number_format((float)$user->wallet_balance, 2); ?></span>
					</div>
					<div class="wv-hero__stat wv-hero__stat--green" role="listitem">
						<span class="wv-hero__stat-lbl">Total Winnings</span>
						<span class="wv-hero__stat-val">₹<?php echo number_format((float)$total_winnings, 2); ?></span>
					</div>
					<div class="wv-hero__stat wv-hero__stat--red" role="listitem">
						<span class="wv-hero__stat-lbl">Total Withdrawn</span>
						<span class="wv-hero__stat-val">₹<?php echo number_format((float)$total_withdrawn, 2); ?></span>
					</div>
					<div class="wv-hero__stat wv-hero__stat--green" role="listitem">
						<span class="wv-hero__stat-lbl">Total Deposited</span>
						<span class="wv-hero__stat-val">₹<?php echo number_format((float)$total_deposited, 2); ?></span>
					</div>
				</div>

				<a href="<?php echo site_url('wallet/add_balance'); ?>" class="wv-hero__cta">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
						<line x1="12" y1="5" x2="12" y2="19" />
						<line x1="5" y1="12" x2="19" y2="12" />
					</svg>
					Add Balance
				</a>
			</div>

			<div class="wv-hero__bal" role="region" aria-label="Available balance">
				<p class="wv-hero__bal-lbl">Available Balance</p>
				<span class="wv-hero__bal-amt">₹<?php echo number_format((float)$user->wallet_balance, 2); ?></span>
				<div class="wv-hero__bal-note">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
						<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
					</svg>
					Secured &amp; Encrypted
				</div>
			</div>
		</div>
	</header>

	<!-- ══ ACTION CARDS ══ -->
	<div class="wv-grid" role="main">

		<!-- Withdraw -->
		<div class="wv-card" data-rev>
			<div class="wv-card__head">
				<div class="wv-card__icon <?php echo $bank_details_saved ? 'wv-card__icon--red' : 'wv-card__icon--muted'; ?>" aria-hidden="true">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
						<line x1="12" y1="5" x2="12" y2="19" />
						<polyline points="19 12 12 19 5 12" />
					</svg>
				</div>
				<div class="wv-card__meta">
					<p class="wv-card__label">Cash Out</p>
					<h2 class="wv-card__title">Withdraw Funds</h2>
				</div>
			</div>
			<p class="wv-card__desc">
				<?php echo $bank_details_saved
					? 'Withdrawal requests are reviewed and processed securely by the admin team.'
					: 'You must add your bank account details before requesting a withdrawal.'; ?>
			</p>
			<div class="wv-card__body">
				<form method="post" action="<?php echo site_url('wallet/request-withdrawal'); ?>" class="wv-form">
					<div class="wv-field">
						<label class="wv-label" for="wd_amount">Amount (₹)</label>
						<div class="wv-input-wrap">
							<span class="wv-pfx" aria-hidden="true">₹</span>
							<input
								type="number" id="wd_amount" name="amount"
								min="1" step="0.01"
								max="<?php echo number_format((float)$user->wallet_balance, 2, '.', ''); ?>"
								placeholder="0.00"
								<?php echo $bank_details_saved ? '' : 'disabled'; ?> required
								class="wv-input wv-input--pfx"
								aria-label="Withdrawal amount in rupees">
						</div>
						<?php if ($bank_details_saved): ?>
							<p class="wv-hint">Max available: <strong>₹<?php echo number_format((float)$user->wallet_balance, 2); ?></strong></p>
						<?php endif; ?>
					</div>
					<button
						type="submit"
						class="wv-btn wv-btn--full <?php echo $bank_details_saved ? 'wv-btn--red' : 'wv-btn--disabled'; ?>"
						<?php echo $bank_details_saved ? '' : 'disabled aria-disabled="true"'; ?>>
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
							<line x1="12" y1="5" x2="12" y2="19" />
							<polyline points="19 12 12 19 5 12" />
						</svg>
						Request Withdrawal
					</button>
				</form>
			</div>
		</div>

		<!-- Bank Details -->
		<div class="wv-card" data-rev>
			<div class="wv-card__head">
				<div class="wv-card__icon wv-card__icon--green" aria-hidden="true">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
						<rect x="3" y="5" width="18" height="14" rx="2" />
						<path d="M3 10h18" />
						<path d="M7 15h.01M11 15h2" />
					</svg>
				</div>
				<div class="wv-card__meta">
					<p class="wv-card__label">Linked Account</p>
					<h2 class="wv-card__title">Bank Details</h2>
				</div>
				<?php if ($bank_details_saved && !$edit_bank_details): ?>
					<a class="wv-card__edit-btn"
						href="<?php echo site_url('wallet?edit_bank=1&history=' . $history_filter); ?>"
						title="Edit bank details" aria-label="Edit bank details">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
							<path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" />
							<path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
						</svg>
					</a>
				<?php endif; ?>
			</div>
			<div class="wv-card__body">
				<?php if ($edit_bank_details): ?>
					<form method="post" action="<?php echo site_url('wallet/save-bank-details'); ?>" class="wv-bank-form" novalidate>
						<div class="wv-field">
							<label class="wv-label" for="bank_holder">Account Holder Name</label>
							<input type="text" id="bank_holder" name="bank_account_holder_name"
								value="<?php echo set_value('bank_account_holder_name', $user->bank_account_holder_name); ?>"
								required placeholder="Full legal name" class="wv-input">
						</div>
						<div class="wv-field">
							<label class="wv-label" for="bank_name">Bank Name</label>
							<input type="text" id="bank_name" name="bank_name"
								value="<?php echo set_value('bank_name', $user->bank_name); ?>"
								required placeholder="e.g. HDFC Bank" class="wv-input">
						</div>
						<div class="wv-field">
							<label class="wv-label" for="bank_acc">Account Number</label>
							<input type="text" id="bank_acc" name="bank_account_number"
								value="<?php echo set_value('bank_account_number', $user->bank_account_number); ?>"
								required placeholder="Enter account number" class="wv-input"
								inputmode="numeric" autocomplete="off">
						</div>
						<div class="wv-field">
							<label class="wv-label" for="bank_ifsc">IFSC Code</label>
							<input type="text" id="bank_ifsc" name="bank_ifsc_code"
								value="<?php echo set_value('bank_ifsc_code', $user->bank_ifsc_code); ?>"
								required placeholder="e.g. HDFC0001234" class="wv-input"
								style="text-transform:uppercase;" autocomplete="off">
						</div>
						<div class="wv-bank-form__footer">
							<?php if ($bank_details_saved): ?>
								<a class="wv-btn wv-btn--ghost" href="<?php echo site_url('wallet?history=' . $history_filter); ?>">Cancel</a>
							<?php endif; ?>
							<button type="submit" class="wv-btn wv-btn--blue">
								<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
									<polyline points="20 6 9 17 4 12" />
								</svg>
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
						<div class="wv-bank-box wv-bank-box--accent">
							<span class="wv-bank-box__lbl">Account Number</span>
							<strong class="wv-bank-box__val wv-bank-box__val--mono"><?php echo html_escape($user->bank_account_number ?: '—'); ?></strong>
						</div>
						<div class="wv-bank-box wv-bank-box--accent">
							<span class="wv-bank-box__lbl">IFSC Code</span>
							<strong class="wv-bank-box__val wv-bank-box__val--mono"><?php echo html_escape($user->bank_ifsc_code ?: '—'); ?></strong>
						</div>
					</div>
					<?php if (!$bank_details_saved): ?>
						<a class="wv-btn wv-btn--outline wv-btn--full"
							href="<?php echo site_url('wallet?edit_bank=1&history=' . $history_filter); ?>"
							style="margin-top:18px;">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
								<line x1="12" y1="5" x2="12" y2="19" />
								<line x1="5" y1="12" x2="19" y2="12" />
							</svg>
							Add Bank Details
						</a>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		</div>

	</div><!-- /wv-grid -->

	<!-- ══ TRANSACTION HISTORY ══ -->
	<section class="wv-tx" data-rev aria-label="Transaction history">
		<div class="wv-tx__head">
			<div class="wv-tx__title-row">
				<div>
					<p class="wv-tx__eyebrow">Ledger</p>
					<h2 class="wv-tx__h2">Transaction History</h2>
				</div>
				<span class="wv-tx__count" id="wvCount" aria-live="polite"><?php echo $wallet_history_count; ?> records</span>
			</div>
			<div class="wv-filters" role="group" aria-label="Filter transactions">
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
					<button
						class="wv-pill <?php echo $history_filter === $fk ? 'is-on' : ''; ?>"
						data-filter="<?php echo $fk; ?>"
						data-color="<?php echo $fv['color']; ?>"
						style="<?php echo $history_filter === $fk ? '--pill-c:' . $fv['color'] : ''; ?>"
						aria-pressed="<?php echo $history_filter === $fk ? 'true' : 'false'; ?>"
						type="button">
						<span class="wv-pill__dot" aria-hidden="true"></span>
						<?php echo $fv['label']; ?>
					</button>
				<?php endforeach; ?>

				<div class="wv-rows-wrap">
					<button class="wv-rows-btn" id="wvRowsBtn" type="button" aria-haspopup="listbox" aria-expanded="false">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
							<line x1="8" y1="6" x2="21" y2="6" />
							<line x1="8" y1="12" x2="21" y2="12" />
							<line x1="8" y1="18" x2="21" y2="18" />
							<line x1="3" y1="6" x2="3.01" y2="6" />
							<line x1="3" y1="12" x2="3.01" y2="12" />
							<line x1="3" y1="18" x2="3.01" y2="18" />
						</svg>
						<span id="wvRowsLbl">10 rows</span>
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" id="wvChevron" aria-hidden="true">
							<polyline points="6 9 12 15 18 9" />
						</svg>
					</button>
					<div class="wv-rows-dd" id="wvRowsDd" role="listbox" aria-label="Rows per page">
						<div class="wv-rows-opt is-on" data-rows="10" role="option" aria-selected="true">10 rows</div>
						<div class="wv-rows-opt" data-rows="25" role="option" aria-selected="false">25 rows</div>
						<div class="wv-rows-opt" data-rows="50" role="option" aria-selected="false">50 rows</div>
						<div class="wv-rows-opt" data-rows="100" role="option" aria-selected="false">100 rows</div>
						<div class="wv-rows-opt" data-rows="99999" role="option" aria-selected="false">All rows</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Desktop Table -->
		<div class="wv-table-wrap" role="region" aria-label="Transactions table" tabindex="0">
			<table class="wv-table" id="wvTable">
				<thead>
					<tr>
						<th scope="col">#</th>
						<th scope="col">Type</th>
						<th scope="col">Description</th>
						<th scope="col">Amount</th>
						<th scope="col">Status</th>
						<th scope="col">Date &amp; Time</th>
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
							<tr data-ft="withdrawals" data-history-item="1">
								<td class="wv-td-num">—</td>
								<td><span class="wv-badge wv-badge--withdrawal"><span class="wv-badge-dot" aria-hidden="true"></span>Withdrawal</span></td>
								<td class="wv-td-desc">Withdrawal request</td>
								<td class="wv-td-dr">− ₹<?php echo number_format((float)$wr->amount, 2); ?></td>
								<td><span class="wv-badge <?php echo $sc; ?>"><?php echo html_escape($sl); ?></span></td>
								<td class="wv-td-date"><?php echo html_escape(date('d M Y, h:i A', strtotime($wr->created_at))); ?></td>
							</tr>
					<?php endforeach;
					endif; ?>

					<?php if (!empty($transactions)):
						foreach ($transactions as $index => $tx):
							$is_cr    = strtolower((string)$tx->type) === 'credit';
							$type_lbl = isset($tx->history_type_label) ? $tx->history_type_label : 'Transaction';
							$b_class  = isset($tx->history_badge_class) ? $tx->history_badge_class : 'wv-badge--default';
							$tx_ft    = isset($tx->history_filter)      ? $tx->history_filter      : 'all';
							$desc     = isset($tx->history_description)  ? $tx->history_description  : '';

							if (isset($tx->history_type_label)) {
								$sc = isset($tx->history_status_class) ? $tx->history_status_class : ($is_cr ? 'wv-badge--success' : 'wv-badge--processed');
								$sl = isset($tx->history_status_label) ? $tx->history_status_label : ($is_cr ? 'Success' : 'Processed');
							} elseif ($tx->source_type === 'deposit') {
								$type_lbl = 'Deposit';
								$b_class = 'wv-badge--deposit';
								$tx_ft = 'deposits';
								$desc = 'Wallet top-up';
							} elseif ($tx->source_type === 'question_result') {
								$type_lbl = 'Winning';
								$b_class = 'wv-badge--winning';
								$tx_ft = 'winnings';
								$desc = 'Question result payout';
							} elseif ($tx->source_type === 'trade_entry') {
								$type_lbl = 'Trade';
								$b_class = 'wv-badge--trade';
								$tx_ft = 'trades';
								$desc = 'Trade entry fee';
							} elseif ($tx->source_type === 'withdrawal') {
								$type_lbl = 'Withdrawal';
								$b_class = 'wv-badge--withdrawal';
								$tx_ft = 'withdrawals';
								$desc = 'Withdrawal processed';
							} elseif ($tx->source_type === 'referral_bonus') {
								$type_lbl = 'Referral';
								$b_class = 'wv-badge--referral';
								$tx_ft = 'all';
								$desc = 'Referral bonus';
							} elseif ($tx->source_type === 'refund') {
								$type_lbl = 'Refund';
								$b_class = 'wv-badge--refund';
								$tx_ft = 'refunds';
								$desc = 'Refund credited';
							}

							if (!isset($tx->history_type_label)) {
								$sc = $is_cr ? 'wv-badge--success' : 'wv-badge--processed';
								$sl = $is_cr ? 'Success' : 'Processed';
							}
					?>
							<tr data-ft="<?php echo $tx_ft; ?>" data-history-item="1">
								<td class="wv-td-num">—</td>
								<td><span class="wv-badge <?php echo $b_class; ?>"><span class="wv-badge-dot" aria-hidden="true"></span><?php echo html_escape($type_lbl); ?></span></td>
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

		<!-- Mobile Cards -->
		<div class="wv-mobile-list" id="wvMobileList" aria-label="Transactions list">
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
					<div class="wv-mobile-card" data-ft="withdrawals" data-history-item="1">
						<div class="wv-mobile-card__row">
							<div class="wv-mobile-card__label">#</div>
							<div class="wv-mobile-card__value wv-td-num">—</div>
						</div>
						<div class="wv-mobile-card__row">
							<div class="wv-mobile-card__label">Type</div>
							<div class="wv-mobile-card__value"><span class="wv-badge wv-badge--withdrawal"><span class="wv-badge-dot" aria-hidden="true"></span>Withdrawal</span></div>
						</div>
						<div class="wv-mobile-card__row">
							<div class="wv-mobile-card__label">Description</div>
							<div class="wv-mobile-card__value wv-td-desc">Withdrawal request</div>
						</div>
						<div class="wv-mobile-card__row">
							<div class="wv-mobile-card__label">Amount</div>
							<div class="wv-mobile-card__value wv-td-dr">− ₹<?php echo number_format((float)$wr->amount, 2); ?></div>
						</div>
						<div class="wv-mobile-card__row">
							<div class="wv-mobile-card__label">Status</div>
							<div class="wv-mobile-card__value"><span class="wv-badge <?php echo $sc; ?>"><?php echo html_escape($sl); ?></span></div>
						</div>
						<div class="wv-mobile-card__row">
							<div class="wv-mobile-card__label">Date &amp; Time</div>
							<div class="wv-mobile-card__value wv-td-date"><?php echo html_escape(date('d M Y, h:i A', strtotime($wr->created_at))); ?></div>
						</div>
					</div>
			<?php endforeach;
			endif; ?>

			<?php if (!empty($transactions)):
				foreach ($transactions as $index => $tx):
					$is_cr    = strtolower((string)$tx->type) === 'credit';
					$type_lbl = isset($tx->history_type_label) ? $tx->history_type_label : 'Transaction';
					$b_class  = isset($tx->history_badge_class) ? $tx->history_badge_class : 'wv-badge--default';
					$tx_ft    = isset($tx->history_filter)      ? $tx->history_filter      : 'all';
					$desc     = isset($tx->history_description) ? $tx->history_description : '';

					if (isset($tx->history_type_label)) {
						$sc = isset($tx->history_status_class) ? $tx->history_status_class : ($is_cr ? 'wv-badge--success' : 'wv-badge--processed');
						$sl = isset($tx->history_status_label) ? $tx->history_status_label : ($is_cr ? 'Success' : 'Processed');
					} elseif ($tx->source_type === 'deposit') {
						$type_lbl = 'Deposit';
						$b_class = 'wv-badge--deposit';
						$tx_ft = 'deposits';
						$desc = 'Wallet top-up';
					} elseif ($tx->source_type === 'question_result') {
						$type_lbl = 'Winning';
						$b_class = 'wv-badge--winning';
						$tx_ft = 'winnings';
						$desc = 'Question result payout';
					} elseif ($tx->source_type === 'trade_entry') {
						$type_lbl = 'Trade';
						$b_class = 'wv-badge--trade';
						$tx_ft = 'trades';
						$desc = 'Trade entry fee';
					} elseif ($tx->source_type === 'withdrawal') {
						$type_lbl = 'Withdrawal';
						$b_class = 'wv-badge--withdrawal';
						$tx_ft = 'withdrawals';
						$desc = 'Withdrawal processed';
					} elseif ($tx->source_type === 'referral_bonus') {
						$type_lbl = 'Referral';
						$b_class = 'wv-badge--referral';
						$tx_ft = 'all';
						$desc = 'Referral bonus';
					} elseif ($tx->source_type === 'refund') {
						$type_lbl = 'Refund';
						$b_class = 'wv-badge--refund';
						$tx_ft = 'refunds';
						$desc = 'Refund credited';
					}

					if (!isset($tx->history_type_label)) {
						$sc = $is_cr ? 'wv-badge--success' : 'wv-badge--processed';
						$sl = $is_cr ? 'Success' : 'Processed';
					}
			?>
					<div class="wv-mobile-card" data-ft="<?php echo $tx_ft; ?>" data-history-item="1">
						<div class="wv-mobile-card__row">
							<div class="wv-mobile-card__label">#</div>
							<div class="wv-mobile-card__value wv-td-num">—</div>
						</div>
						<div class="wv-mobile-card__row">
							<div class="wv-mobile-card__label">Type</div>
							<div class="wv-mobile-card__value"><span class="wv-badge <?php echo $b_class; ?>"><span class="wv-badge-dot" aria-hidden="true"></span><?php echo html_escape($type_lbl); ?></span></div>
						</div>
						<div class="wv-mobile-card__row">
							<div class="wv-mobile-card__label">Description</div>
							<div class="wv-mobile-card__value wv-td-desc"><?php echo html_escape($desc); ?></div>
						</div>
						<div class="wv-mobile-card__row">
							<div class="wv-mobile-card__label">Amount</div>
							<div class="wv-mobile-card__value <?php echo $is_cr ? 'wv-td-cr' : 'wv-td-dr'; ?>"><?php echo $is_cr ? '+' : '−'; ?> ₹<?php echo number_format((float)$tx->amount, 2); ?></div>
						</div>
						<div class="wv-mobile-card__row">
							<div class="wv-mobile-card__label">Status</div>
							<div class="wv-mobile-card__value"><span class="wv-badge <?php echo $sc; ?>"><?php echo html_escape($sl); ?></span></div>
						</div>
						<div class="wv-mobile-card__row">
							<div class="wv-mobile-card__label">Date &amp; Time</div>
							<div class="wv-mobile-card__value wv-td-date"><?php echo html_escape(date('d M Y, h:i A', strtotime($tx->created_at))); ?></div>
						</div>
					</div>
			<?php endforeach;
			endif; ?>
		</div>

		<!-- Pagination -->
		<div class="wv-pagination" id="wvPagination" role="navigation" aria-label="Pagination">
			<span class="wv-page-info" id="wvPageInfo" aria-live="polite">Showing 1–10</span>
			<div class="wv-page-btns" id="wvPageBtns"></div>
		</div>
	</section>

</div><!-- /.wv -->

<script>
	(function() {
		'use strict';

		/* ── Reveal animation ── */
		document.querySelectorAll('[data-rev]').forEach(function(el, i) {
			el.style.transitionDelay = (i * 70) + 'ms';
			requestAnimationFrame(function() {
				requestAnimationFrame(function() {
					el.classList.add('on');
				});
			});
		});

		/* ── Auto-dismiss alerts ── */
		document.querySelectorAll('.wv-alert').forEach(function(el) {
			setTimeout(function() {
				el.style.transition = 'opacity .4s ease, transform .4s ease';
				el.style.opacity = '0';
				el.style.transform = 'translateY(-8px)';
				setTimeout(function() {
					el.remove();
				}, 450);
			}, 5000);
		});

		/* ── Rows dropdown ── */
		var rowsBtn = document.getElementById('wvRowsBtn');
		var rowsDd = document.getElementById('wvRowsDd');
		var rowsLbl = document.getElementById('wvRowsLbl');

		function closeRows() {
			if (rowsDd) {
				rowsDd.classList.remove('open');
				rowsBtn.classList.remove('open');
				rowsBtn.setAttribute('aria-expanded', 'false');
			}
		}

		if (rowsBtn) {
			rowsBtn.addEventListener('click', function(e) {
				e.stopPropagation();
				var opening = !rowsDd.classList.contains('open');
				rowsDd.classList.toggle('open', opening);
				rowsBtn.classList.toggle('open', opening);
				rowsBtn.setAttribute('aria-expanded', String(opening));
			});
			document.addEventListener('click', closeRows);
		}

		/* ── State ── */
		var currentFilter = '<?php echo $history_filter; ?>';
		var rowsPerPage = 10;
		var currentPage = 1;

		/* ── Rows option click ── */
		document.querySelectorAll('.wv-rows-opt').forEach(function(opt) {
			opt.addEventListener('click', function() {
				rowsPerPage = parseInt(this.dataset.rows, 10);
				currentPage = 1;
				rowsLbl.textContent = rowsPerPage >= 99999 ? 'All rows' : rowsPerPage + ' rows';
				document.querySelectorAll('.wv-rows-opt').forEach(function(o) {
					o.classList.remove('is-on');
					o.setAttribute('aria-selected', 'false');
				});
				this.classList.add('is-on');
				this.setAttribute('aria-selected', 'true');
				closeRows();
				render();
			});
		});

		/* ── Filter pills ── */
		document.querySelectorAll('.wv-pill').forEach(function(pill) {
			pill.addEventListener('click', function() {
				currentFilter = this.dataset.filter;
				currentPage = 1;
				var color = this.dataset.color || '';
				document.querySelectorAll('.wv-pill').forEach(function(p) {
					p.classList.remove('is-on');
					p.style.removeProperty('--pill-c');
					p.setAttribute('aria-pressed', 'false');
				});
				this.classList.add('is-on');
				this.setAttribute('aria-pressed', 'true');
				if (color) this.style.setProperty('--pill-c', color);
				render();
			});
		});

		/* ── Get visible rows ── */
		function getActiveSelector() {
			return window.matchMedia('(max-width: 640px)').matches ?
				'#wvMobileList [data-history-item="1"][data-ft]' :
				'#wvBody tr[data-history-item="1"][data-ft]';
		}

		function getRows() {
			var all = Array.from(document.querySelectorAll(getActiveSelector()));
			return currentFilter === 'all' ?
				all :
				all.filter(function(r) {
					return r.dataset.ft === currentFilter;
				});
		}

		/* ── Make page button ── */
		function makeBtn(label, disabled, active) {
			var b = document.createElement('button');
			b.className = 'wv-page-btn' + (active ? ' is-on' : '');
			b.textContent = label;
			b.disabled = !!disabled;
			b.type = 'button';
			if (active) b.setAttribute('aria-current', 'page');
			return b;
		}

		/* ── Render ── */
		function render() {
			var rows = getRows();
			var total = rows.length;
			var limit = rowsPerPage >= 99999 ? total : rowsPerPage;
			var pages = Math.max(1, Math.ceil(total / (limit || 1)));
			currentPage = Math.min(currentPage, pages);
			var start = (currentPage - 1) * limit;
			var end = start + limit;

			/* Hide/show rows */
			Array.from(document.querySelectorAll('#wvBody tr[data-history-item="1"][data-ft], #wvMobileList [data-history-item="1"][data-ft]')).forEach(function(r) {
				r.style.display = 'none';
			});
			rows.forEach(function(r, i) {
				r.style.display = (i >= start && i < end) ? '' : 'none';
				var nc = r.querySelector('.wv-td-num');
				if (nc) nc.textContent = i + 1;
			});

			/* Empty state */
			var emptyRow = document.getElementById('wvEmpty');
			var emptyMobile = document.getElementById('wvEmptyMobile');
			if (total === 0) {
				if (!emptyRow) {
					emptyRow = document.createElement('tr');
					emptyRow.id = 'wvEmpty';
					emptyRow.className = 'wv-empty';
					emptyRow.innerHTML = '<td colspan="6">' +
						'<svg class="wv-empty-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">' +
						'<rect x="2" y="7" width="20" height="14" rx="2"/>' +
						'<path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/>' +
						'</svg>' +
						'No transactions found for this filter.</td>';
					document.getElementById('wvBody').appendChild(emptyRow);
				}
				emptyRow.style.display = '';
				if (!emptyMobile) {
					emptyMobile = document.createElement('div');
					emptyMobile.id = 'wvEmptyMobile';
					emptyMobile.className = 'wv-mobile-card wv-empty';
					emptyMobile.innerHTML =
						'<div style="text-align:center;padding:20px 10px;color:var(--ink-4);font-size:14px;">' +
						'<svg class="wv-empty-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">' +
						'<rect x="2" y="7" width="20" height="14" rx="2"/>' +
						'<path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/>' +
						'</svg>' +
						'No transactions found for this filter.</div>';
					document.getElementById('wvMobileList').appendChild(emptyMobile);
				}
				emptyMobile.style.display = '';
			} else {
				if (emptyRow) emptyRow.style.display = 'none';
				if (emptyMobile) emptyMobile.style.display = 'none';
			}

			/* Count label */
			var countEl = document.getElementById('wvCount');
			if (countEl) countEl.textContent = total + ' record' + (total !== 1 ? 's' : '');

			/* Page info */
			var infoEl = document.getElementById('wvPageInfo');
			if (infoEl) infoEl.textContent = total === 0 ?
				'No records' :
				'Showing ' + (start + 1) + '–' + Math.min(end, total) + ' of ' + total;

			/* Pagination buttons */
			var bc = document.getElementById('wvPageBtns');
			bc.innerHTML = '';

			var prevBtn = makeBtn('‹', currentPage <= 1);
			prevBtn.setAttribute('aria-label', 'Previous page');
			prevBtn.addEventListener('click', function() {
				if (currentPage > 1) {
					currentPage--;
					render();
				}
			});
			bc.appendChild(prevBtn);

			var lastDot = false;
			for (var p = 1; p <= pages; p++) {
				var show = p === 1 || p === pages || (p >= currentPage - 2 && p <= currentPage + 2);
				if (show) {
					lastDot = false;
					var pb = makeBtn(p, false, p === currentPage);
					(function(n) {
						pb.addEventListener('click', function() {
							currentPage = n;
							render();
						});
					}(p));
					bc.appendChild(pb);
				} else if (!lastDot) {
					lastDot = true;
					var d = document.createElement('span');
					d.className = 'wv-page-dots';
					d.textContent = '…';
					bc.appendChild(d);
				}
			}

			var nextBtn = makeBtn('›', currentPage >= pages);
			nextBtn.setAttribute('aria-label', 'Next page');
			nextBtn.addEventListener('click', function() {
				if (currentPage < pages) {
					currentPage++;
					render();
				}
			});
			bc.appendChild(nextBtn);

			/* Hide pagination when unnecessary */
			var pagEl = document.getElementById('wvPagination');
			if (pagEl) pagEl.style.display = (pages <= 1 && total <= limit) ? 'none' : 'flex';
		}

		render();
		window.addEventListener('resize', render);
	}());
</script>