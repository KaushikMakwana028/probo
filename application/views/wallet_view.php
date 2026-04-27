<?php
// wallet_view.php — Enhanced UI v5 (Full Mobile Fix + Premium Design)
$history_filter = isset($history_filter) ? $history_filter : 'all';
$withdraw_min_amount = isset($withdraw_min_amount) && (float) $withdraw_min_amount > 0 ? (float) $withdraw_min_amount : 5000;
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
			$history_row->history_badge_class = 'badge--withdrawal';
			$history_row->history_filter = 'withdrawals';
			$history_row->history_description = 'Withdrawal request';
			$history_row->history_status_label = in_array($ws, array('approved', 'success', 'completed'), TRUE) ? 'Success' : (in_array($ws, array('rejected', 'failed', 'declined'), TRUE) ? 'Rejected' : ucfirst($ws));
			$history_row->history_status_class = in_array($ws, array('approved', 'success', 'completed'), TRUE) ? 'badge--success' : (in_array($ws, array('rejected', 'failed', 'declined'), TRUE) ? 'badge--rejected' : 'badge--pending');
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
$withdrawal_status_alert_payload = array();

if (!empty($withdrawal_status_alert)) {
	$alert_title = isset($withdrawal_status_alert->title) ? trim((string) $withdrawal_status_alert->title) : 'Withdrawal update';
	$alert_message = isset($withdrawal_status_alert->message) ? trim((string) $withdrawal_status_alert->message) : '';
	$alert_icon = stripos($alert_title, 'rejected') !== FALSE ? 'error' : 'success';
	$alert_html = nl2br(html_escape($alert_message));

	if (stripos($alert_message, 'Remark:') !== FALSE) {
		$remark_parts = preg_split('/Remark:/i', $alert_message, 2);
		$main_message = isset($remark_parts[0]) ? trim((string) $remark_parts[0]) : '';
		$remark_message = isset($remark_parts[1]) ? trim((string) $remark_parts[1]) : '';
		$alert_html = nl2br(html_escape($main_message));
		if ($remark_message !== '') {
			$alert_html .= '<br><strong>Remark:</strong> <strong>' . html_escape($remark_message) . '</strong>';
		}
	}

	$withdrawal_status_alert_payload = array(
		'id' => isset($withdrawal_status_alert->id) ? (int) $withdrawal_status_alert->id : 0,
		'title' => $alert_title,
		'message' => $alert_message,
		'html' => $alert_html,
		'icon' => $alert_icon
	);
}
?>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
	/* ═══════════════════════════════════════════════
   RESET & TOKENS
═══════════════════════════════════════════════ */
	*,
	*::before,
	*::after {
		box-sizing: border-box;
		margin: 0;
		padding: 0;
		-webkit-tap-highlight-color: transparent;
	}

	:root {
		--font: 'DM Sans', system-ui, sans-serif;
		--mono: 'DM Mono', monospace;

		/* Surfaces */
		--s0: #ffffff;
		--s1: #f4f6f9;
		--s2: #eaecf2;
		--s3: #dde1ea;
		--bdr: #e2e6ef;
		--bdr2: #c8cedd;

		/* Ink */
		--i1: #0c1120;
		--i2: #1a2236;
		--i3: #3d4e68;
		--i4: #6b7a96;
		--i5: #a0adbf;

		/* Brand */
		--blue: #1e54e8;
		--blue-dk: #1740c0;
		--blue-lt: #eef3fe;
		--blue-bd: #c0d0fb;
		--blue-tx: #1840b8;

		/* Semantic */
		--green: #0f9d58;
		--green-lt: #edfaf3;
		--green-bd: #a8eecb;
		--green-tx: #0a6e3e;
		--red: #e53935;
		--red-dk: #c62828;
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

		/* Radii */
		--r-xs: 4px;
		--r-sm: 8px;
		--r-md: 12px;
		--r-lg: 16px;
		--r-xl: 20px;
		--r-pill: 999px;

		/* Shadows */
		--sh-xs: 0 1px 3px rgba(12, 17, 32, .06);
		--sh-sm: 0 2px 10px rgba(12, 17, 32, .07), 0 1px 2px rgba(12, 17, 32, .04);
		--sh-md: 0 6px 20px rgba(12, 17, 32, .09), 0 2px 6px rgba(12, 17, 32, .05);
		--sh-lg: 0 12px 32px rgba(12, 17, 32, .12), 0 4px 10px rgba(12, 17, 32, .06);
	}

	html {
		-webkit-text-size-adjust: 100%;
		overflow-x: hidden;
		width: 100%;
	}

	html,
	body {
		width: 100%;
		max-width: 100%;
		overflow-x: hidden;
	}

	body {
		font-family: var(--font);
		color: var(--i1);
		background: var(--s1);
		line-height: 1.5;
		min-height: 100vh;
		-webkit-font-smoothing: antialiased;
		-webkit-overflow-scrolling: touch;
	}

	input,
	select,
	textarea,
	button {
		font-size: 16px;
		font-family: var(--font);
	}

	/* ═══════════════════════════════════════════════
   ROOT WRAPPER
═══════════════════════════════════════════════ */
	.wv {
		width: 100%;
		max-width: 100%;
		display: flex;
		flex-direction: column;
		gap: 14px;
		padding: 14px 12px 60px;
		overflow-x: hidden;
	}

	/* ═══════════════════════════════════════════════
   ALERTS
═══════════════════════════════════════════════ */
	.wv-alert {
		display: flex;
		align-items: flex-start;
		gap: 10px;
		padding: 13px 14px;
		border-radius: var(--r-lg);
		font-size: 13.5px;
		font-weight: 500;
		border: 1px solid transparent;
		animation: slideIn .3s cubic-bezier(.16, 1, .3, 1);
		box-shadow: var(--sh-sm);
	}

	@keyframes slideIn {
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
		border-radius: 6px;
		display: flex;
		align-items: center;
		justify-content: center;
		flex-shrink: 0;
	}

	.wv-alert__ico svg {
		width: 12px;
		height: 12px;
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
		line-height: 1;
		min-width: 34px;
		min-height: 34px;
		display: flex;
		align-items: center;
		justify-content: center;
		flex-shrink: 0;
		transition: opacity .2s;
	}

	.wv-alert__close:hover {
		opacity: 1;
	}

	/* ═══════════════════════════════════════════════
   HERO — dark card, mobile-first
═══════════════════════════════════════════════ */
	.wv-hero {
		border-radius: var(--r-xl);
		background: #080f22;
		position: relative;
		overflow: hidden;
		box-shadow: var(--sh-lg);
		width: 100%;
	}

	.wv-hero__bg {
		position: absolute;
		inset: 0;
		pointer-events: none;
		background:
			radial-gradient(ellipse 80% 70% at 110% 30%, rgba(30, 84, 232, .45) 0%, transparent 65%),
			radial-gradient(ellipse 60% 60% at -10% 10%, rgba(124, 58, 237, .25) 0%, transparent 55%),
			radial-gradient(ellipse 50% 80% at 50% 130%, rgba(16, 185, 129, .12) 0%, transparent 60%);
	}

	.wv-hero__dots {
		position: absolute;
		inset: 0;
		pointer-events: none;
		opacity: .35;
		background-image: radial-gradient(rgba(255, 255, 255, .08) 1px, transparent 1px);
		background-size: 24px 24px;
	}

	.wv-hero__inner {
		position: relative;
		z-index: 1;
		padding: 22px 18px 20px;
		display: flex;
		flex-direction: column;
		gap: 18px;
	}

	.wv-hero__eyebrow {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		font-size: 10px;
		font-weight: 700;
		letter-spacing: .14em;
		text-transform: uppercase;
		color: rgba(255, 255, 255, .35);
		margin-bottom: 8px;
	}

	.wv-hero__eyebrow svg {
		width: 11px;
		height: 11px;
		flex-shrink: 0;
	}

	.wv-hero__title {
		font-size: 22px;
		font-weight: 800;
		color: #fff;
		letter-spacing: -.04em;
		line-height: 1.15;
		margin-bottom: 6px;
		word-wrap: break-word;
	}

	.wv-hero__sub {
		font-size: 12.5px;
		font-weight: 400;
		color: rgba(255, 255, 255, .38);
		line-height: 1.6;
		margin-bottom: 14px;
	}

	/* Stats — 2-col grid on mobile */
	.wv-hero__stats {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 8px;
		margin-bottom: 14px;
	}

	.wv-hero__stat {
		padding: 11px 13px;
		border-radius: var(--r-md);
		background: rgba(255, 255, 255, .06);
		border: 1px solid rgba(255, 255, 255, .08);
		min-width: 0;
		overflow: hidden;
	}

	.wv-hero__stat--green {
		background: rgba(16, 185, 129, .1);
		border-color: rgba(16, 185, 129, .25);
	}

	.wv-hero__stat--red {
		background: rgba(229, 57, 53, .1);
		border-color: rgba(229, 57, 53, .25);
	}

	.wv-hero__stat-lbl {
		display: block;
		font-size: 9px;
		font-weight: 700;
		letter-spacing: .1em;
		text-transform: uppercase;
		color: rgba(255, 255, 255, .35);
		margin-bottom: 5px;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
	}

	.wv-hero__stat-val {
		display: block;
		font-size: 15px;
		font-weight: 800;
		color: #fff;
		letter-spacing: -.03em;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
	}

	.wv-hero__stat--green .wv-hero__stat-val {
		color: #6ee7b7;
	}

	.wv-hero__stat--red .wv-hero__stat-val {
		color: #fca5a5;
	}

	.wv-hero__cta {
		display: flex;
		align-items: center;
		justify-content: center;
		gap: 8px;
		padding: 14px 20px;
		border-radius: var(--r-pill);
		background: var(--blue);
		border: 1px solid rgba(255, 255, 255, .18);
		color: #fff;
		font-family: var(--font);
		font-size: 15px;
		font-weight: 700;
		text-decoration: none;
		cursor: pointer;
		box-shadow: 0 4px 24px rgba(30, 84, 232, .45);
		transition: all .25s;
		min-height: 50px;
		width: 100%;
	}

	.wv-hero__cta svg {
		width: 15px;
		height: 15px;
		flex-shrink: 0;
	}

	.wv-hero__cta:active {
		background: var(--blue-dk);
		transform: scale(.98);
	}

	/* Balance pill — compact on mobile */
	.wv-hero__bal {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 14px;
		flex-wrap: wrap;
		padding: 16px 18px;
		border-radius: var(--r-lg);
		background: rgba(255, 255, 255, .07);
		border: 1px solid rgba(255, 255, 255, .12);
		backdrop-filter: blur(16px);
		-webkit-backdrop-filter: blur(16px);
	}

	.wv-hero__bal-left {}

	.wv-hero__bal-lbl {
		display: block;
		font-size: 9.5px;
		font-weight: 700;
		letter-spacing: .13em;
		text-transform: uppercase;
		color: rgba(255, 255, 255, .35);
		margin-bottom: 4px;
	}

	.wv-hero__bal-amt {
		display: block;
		font-size: 30px;
		font-weight: 800;
		color: #fff;
		letter-spacing: -.04em;
		line-height: 1;
	}

	.wv-hero__bal-note {
		display: flex;
		align-items: center;
		gap: 5px;
		font-size: 10.5px;
		color: rgba(255, 255, 255, .3);
		font-weight: 500;
		margin-top: 6px;
	}

	.wv-hero__bal-note svg {
		width: 10px;
		height: 10px;
	}

	.wv-hero__bal-chip {
		display: inline-flex;
		align-items: center;
		gap: 5px;
		padding: 6px 12px;
		border-radius: var(--r-pill);
		background: rgba(110, 231, 183, .12);
		border: 1px solid rgba(110, 231, 183, .2);
		color: #6ee7b7;
		font-size: 11px;
		font-weight: 700;
		white-space: nowrap;
		flex-shrink: 0;
	}

	.wv-hero__bal-chip svg {
		width: 10px;
		height: 10px;
	}

	/* ═══════════════════════════════════════════════
   ACTION GRID
═══════════════════════════════════════════════ */
	.wv-grid {
		display: flex;
		flex-direction: column;
		gap: 14px;
		width: 100%;
	}

	/* ═══════════════════════════════════════════════
   CARD
═══════════════════════════════════════════════ */
	.wv-card {
		background: var(--s0);
		border: 1px solid var(--bdr);
		border-radius: var(--r-xl);
		box-shadow: var(--sh-sm);
		overflow: hidden;
		width: 100%;
	}

	.wv-card__head {
		padding: 15px 16px;
		border-bottom: 1px solid var(--bdr);
		display: flex;
		align-items: center;
		gap: 12px;
	}

	.wv-card__icon {
		width: 38px;
		height: 38px;
		border-radius: var(--r-md);
		display: flex;
		align-items: center;
		justify-content: center;
		flex-shrink: 0;
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
		background: var(--s2);
		color: var(--i4);
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
		color: var(--i5);
		margin-bottom: 2px;
	}

	.wv-card__title {
		font-size: 15px;
		font-weight: 700;
		color: var(--i1);
		letter-spacing: -.02em;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
	}

	.wv-card__edit-btn {
		width: 38px;
		height: 38px;
		border-radius: var(--r-sm);
		border: 1px solid var(--bdr2);
		background: var(--s1);
		display: flex;
		align-items: center;
		justify-content: center;
		color: var(--i3);
		text-decoration: none;
		flex-shrink: 0;
		transition: all .2s;
	}

	.wv-card__edit-btn svg {
		width: 14px;
		height: 14px;
	}

	.wv-card__edit-btn:hover {
		background: var(--blue-lt);
		color: var(--blue);
		border-color: var(--blue-bd);
	}

	.wv-card__desc {
		font-size: 13px;
		color: var(--i4);
		line-height: 1.6;
		padding: 11px 16px;
		border-bottom: 1px solid var(--bdr);
	}

	.wv-card__body {
		padding: 16px;
	}

	/* ═══════════════════════════════════════════════
   FORM
═══════════════════════════════════════════════ */
	.wv-form {
		display: flex;
		flex-direction: column;
		gap: 13px;
	}

	.wv-field {
		display: flex;
		flex-direction: column;
		gap: 6px;
	}

	.wv-label {
		font-size: 11px;
		font-weight: 700;
		letter-spacing: .08em;
		text-transform: uppercase;
		color: var(--i3);
	}

	.wv-input-wrap {
		position: relative;
		display: flex;
		align-items: center;
		width: 100%;
	}

	.wv-pfx {
		position: absolute;
		left: 13px;
		font-size: 15px;
		font-weight: 700;
		color: var(--i4);
		pointer-events: none;
		z-index: 1;
	}

	.wv-input {
		width: 100%;
		min-height: 50px;
		padding: 0 13px;
		border: 1.5px solid var(--bdr2);
		border-radius: var(--r-md);
		background: var(--s1);
		color: var(--i1);
		font-family: var(--font);
		font-size: 16px;
		font-weight: 400;
		transition: all .2s;
		-webkit-appearance: none;
		appearance: none;
		touch-action: manipulation;
	}

	.wv-input--pfx {
		padding-left: 30px;
	}

	.wv-input:focus {
		outline: none;
		border-color: var(--blue);
		box-shadow: 0 0 0 3px rgba(30, 84, 232, .1);
		background: var(--s0);
	}

	.wv-input:disabled {
		opacity: .45;
		cursor: not-allowed;
	}

	.wv-input::placeholder {
		color: var(--i5);
	}

	.wv-hint {
		font-size: 12px;
		color: var(--i5);
		line-height: 1.5;
	}

	.wv-hint strong {
		color: var(--i3);
		font-weight: 600;
	}

	/* ═══════════════════════════════════════════════
   BUTTONS
═══════════════════════════════════════════════ */
	.wv-btn {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		gap: 8px;
		padding: 13px 20px;
		border-radius: var(--r-md);
		font-family: var(--font);
		font-size: 15px;
		font-weight: 700;
		border: 1.5px solid transparent;
		cursor: pointer;
		transition: all .2s cubic-bezier(.16, 1, .3, 1);
		text-decoration: none;
		white-space: nowrap;
		letter-spacing: -.01em;
		line-height: 1;
		min-height: 50px;
		touch-action: manipulation;
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
		box-shadow: 0 2px 12px rgba(30, 84, 232, .3);
	}

	.wv-btn--blue:active {
		background: var(--blue-dk);
	}

	.wv-btn--red {
		background: var(--red);
		border-color: var(--red-dk);
		color: #fff;
		box-shadow: 0 2px 12px rgba(229, 57, 53, .3);
	}

	.wv-btn--red:active {
		background: var(--red-dk);
	}

	.wv-btn--ghost {
		background: var(--s1);
		border-color: var(--bdr2);
		color: var(--i3);
	}

	.wv-btn--ghost:active {
		background: var(--s2);
	}

	.wv-btn--outline {
		background: var(--s0);
		border-color: var(--bdr2);
		color: var(--i2);
	}

	.wv-btn--disabled {
		background: var(--s2);
		border-color: var(--bdr);
		color: var(--i4);
		cursor: not-allowed;
		box-shadow: none;
	}

	/* ═══════════════════════════════════════════════
   BANK DETAILS
═══════════════════════════════════════════════ */
	.wv-bank-form {
		display: flex;
		flex-direction: column;
		gap: 13px;
	}

	.wv-bank-form__footer {
		display: flex;
		flex-direction: column-reverse;
		gap: 9px;
		padding-top: 4px;
	}

	.wv-bank-form__footer .wv-btn {
		width: 100%;
	}

	.wv-bank-grid {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 8px;
	}

	.wv-bank-box {
		padding: 12px 13px;
		border-radius: var(--r-md);
		background: var(--s1);
		border: 1px solid var(--bdr);
		overflow: hidden;
		min-width: 0;
	}

	.wv-bank-box--accent {
		background: var(--blue-lt);
		border-color: var(--blue-bd);
	}

	.wv-bank-box__lbl {
		display: block;
		font-size: 9.5px;
		font-weight: 700;
		letter-spacing: .1em;
		text-transform: uppercase;
		color: var(--i5);
		margin-bottom: 4px;
	}

	.wv-bank-box--accent .wv-bank-box__lbl {
		color: rgba(24, 64, 184, .55);
	}

	.wv-bank-box__val {
		display: block;
		font-size: 13px;
		font-weight: 600;
		color: var(--i2);
		overflow-wrap: anywhere;
		word-break: break-all;
		line-height: 1.4;
	}

	.wv-bank-box__val--mono {
		font-family: var(--mono);
		font-size: 12px;
		color: var(--blue-tx);
		letter-spacing: .03em;
	}

	/* ═══════════════════════════════════════════════
   TRANSACTION PANEL
═══════════════════════════════════════════════ */
	.wv-tx {
		background: var(--s0);
		border: 1px solid var(--bdr);
		border-radius: var(--r-xl);
		box-shadow: var(--sh-sm);
		overflow: hidden;
		width: 100%;
	}

	.wv-tx__head {
		padding: 16px 14px 0;
	}

	.wv-tx__title-row {
		display: flex;
		align-items: flex-start;
		justify-content: space-between;
		gap: 10px;
		margin-bottom: 14px;
		flex-wrap: wrap;
	}

	.wv-tx__heading-group {}

	.wv-tx__eyebrow {
		font-size: 10px;
		font-weight: 700;
		letter-spacing: .13em;
		text-transform: uppercase;
		color: var(--i5);
		margin-bottom: 2px;
	}

	.wv-tx__h2 {
		font-size: 19px;
		font-weight: 800;
		color: var(--i1);
		letter-spacing: -.03em;
	}

	.wv-tx__count {
		display: inline-flex;
		align-items: center;
		padding: 5px 12px;
		border-radius: var(--r-pill);
		background: var(--s2);
		border: 1px solid var(--bdr);
		font-size: 11.5px;
		font-weight: 600;
		color: var(--i4);
		white-space: nowrap;
		flex-shrink: 0;
		margin-top: 2px;
	}

	/* ── Filter bar ── */
	.wv-filters {
		display: flex;
		flex-direction: column;
		gap: 8px;
		padding-bottom: 14px;
		border-bottom: 1px solid var(--bdr);
	}

	.wv-filter-scroll {
		display: flex;
		align-items: center;
		gap: 6px;
		overflow-x: auto;
		-webkit-overflow-scrolling: touch;
		scrollbar-width: none;
		-ms-overflow-style: none;
		padding-bottom: 2px;
	}

	.wv-filter-scroll::-webkit-scrollbar {
		display: none;
	}

	.wv-filter-row-bottom {
		display: flex;
		align-items: center;
		justify-content: flex-end;
	}

	.wv-pill {
		display: inline-flex;
		align-items: center;
		gap: 5px;
		padding: 7px 13px;
		border-radius: var(--r-pill);
		font-size: 13px;
		font-weight: 600;
		font-family: var(--font);
		border: 1.5px solid var(--bdr2);
		color: var(--i3);
		background: var(--s0);
		cursor: pointer;
		transition: all .2s;
		white-space: nowrap;
		min-height: 36px;
		flex-shrink: 0;
		touch-action: manipulation;
	}

	.wv-pill__dot {
		width: 5px;
		height: 5px;
		border-radius: 50%;
		background: currentColor;
		opacity: .4;
		flex-shrink: 0;
	}

	.wv-pill.is-on {
		background: var(--pill-c, var(--blue));
		border-color: transparent;
		color: #fff;
		box-shadow: 0 2px 12px rgba(0, 0, 0, .18);
	}

	.wv-pill.is-on .wv-pill__dot {
		opacity: 1;
		background: rgba(255, 255, 255, .7);
	}

	/* Rows dropdown */
	.wv-rows-wrap {
		position: relative;
		flex-shrink: 0;
	}

	.wv-rows-btn {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		padding: 7px 12px;
		border-radius: var(--r-pill);
		font-size: 13px;
		font-weight: 600;
		font-family: var(--font);
		color: var(--i3);
		background: var(--s1);
		border: 1.5px solid var(--bdr2);
		cursor: pointer;
		transition: all .2s;
		min-height: 36px;
		white-space: nowrap;
		touch-action: manipulation;
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
		top: calc(100% + 6px);
		right: 0;
		z-index: 60;
		background: var(--s0);
		border: 1px solid var(--bdr2);
		border-radius: var(--r-lg);
		box-shadow: var(--sh-lg);
		min-width: 145px;
		overflow: hidden;
		display: none;
		animation: dropDown .2s cubic-bezier(.16, 1, .3, 1);
	}

	@keyframes dropDown {
		from {
			opacity: 0;
			transform: translateY(-6px);
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
		padding: 11px 15px;
		font-size: 14px;
		font-weight: 500;
		color: var(--i2);
		cursor: pointer;
		transition: background .15s;
		display: flex;
		align-items: center;
		justify-content: space-between;
		min-height: 44px;
	}

	.wv-rows-opt:active {
		background: var(--s1);
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

	/* Desktop table — hidden on mobile */
	.wv-table-wrap {
		display: none;
	}

	/* ═══════════════════════════════════════════════
   MOBILE TRANSACTION CARDS — PREMIUM REDESIGN
═══════════════════════════════════════════════ */
	.wv-mobile-list {
		display: flex;
		flex-direction: column;
		gap: 0;
		padding: 0 0 4px;
	}

	.wv-tx-card {
		padding: 14px 14px 13px;
		border-bottom: 1px solid var(--s2);
		display: grid;
		grid-template-columns: 40px 1fr auto;
		gap: 0 11px;
		align-items: center;
		transition: background .15s;
		position: relative;
	}

	.wv-tx-card:last-child {
		border-bottom: none;
	}

	.wv-tx-card:active {
		background: var(--s1);
	}

	/* Left: icon circle */
	.wv-tx-card__ico {
		width: 40px;
		height: 40px;
		border-radius: var(--r-md);
		display: flex;
		align-items: center;
		justify-content: center;
		flex-shrink: 0;
		align-self: center;
	}

	.wv-tx-card__ico svg {
		width: 17px;
		height: 17px;
	}

	.ico--deposit {
		background: var(--purple-lt);
		color: var(--purple);
	}

	.ico--winning {
		background: var(--green-lt);
		color: var(--green);
	}

	.ico--trade {
		background: var(--amber-lt);
		color: var(--amber);
	}

	.ico--withdrawal {
		background: var(--red-lt);
		color: var(--red);
	}

	.ico--referral {
		background: var(--pink-lt);
		color: var(--pink);
	}

	.ico--refund {
		background: var(--cyan-lt);
		color: var(--cyan);
	}

	.ico--default {
		background: var(--s2);
		color: var(--i4);
	}

	/* Center: text */
	.wv-tx-card__body {
		min-width: 0;
	}

	.wv-tx-card__type {
		font-size: 13.5px;
		font-weight: 700;
		color: var(--i1);
		letter-spacing: -.01em;
		margin-bottom: 2px;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
	}

	.wv-tx-card__desc {
		font-size: 12px;
		color: var(--i4);
		font-weight: 400;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
		margin-bottom: 3px;
	}

	.wv-tx-card__meta {
		display: flex;
		align-items: center;
		gap: 6px;
		flex-wrap: wrap;
	}

	.wv-tx-card__date {
		font-size: 11px;
		color: var(--i5);
		font-weight: 500;
	}

	/* Right: amount + status */
	.wv-tx-card__right {
		display: flex;
		flex-direction: column;
		align-items: flex-end;
		gap: 5px;
		flex-shrink: 0;
	}

	.wv-tx-card__amt {
		font-size: 15px;
		font-weight: 800;
		letter-spacing: -.025em;
		line-height: 1;
	}

	.amt-cr {
		color: var(--green);
	}

	.amt-dr {
		color: var(--red);
	}

	/* Row number bubble */
	.wv-tx-card__num {
		font-size: 10px;
		font-weight: 700;
		color: var(--i5);
		font-family: var(--mono);
	}

	/* ═══════════════════════════════════════════════
   BADGES
═══════════════════════════════════════════════ */
	.badge {
		display: inline-flex;
		align-items: center;
		gap: 3px;
		padding: 3px 8px;
		border-radius: var(--r-pill);
		font-size: 10.5px;
		font-weight: 700;
		white-space: nowrap;
		flex-shrink: 0;
	}

	.badge-dot {
		width: 4px;
		height: 4px;
		border-radius: 50%;
		background: currentColor;
		flex-shrink: 0;
	}

	.badge--deposit {
		background: var(--purple-lt);
		color: var(--purple-tx);
	}

	.badge--winning {
		background: var(--green-lt);
		color: var(--green-tx);
	}

	.badge--trade {
		background: var(--amber-lt);
		color: var(--amber-tx);
	}

	.badge--withdrawal {
		background: var(--red-lt);
		color: var(--red-tx);
	}

	.badge--referral {
		background: var(--pink-lt);
		color: var(--pink-tx);
	}

	.badge--refund {
		background: var(--cyan-lt);
		color: var(--cyan-tx);
	}

	.badge--default {
		background: var(--s2);
		color: var(--i3);
	}

	.badge--success {
		background: var(--green-lt);
		color: var(--green-tx);
	}

	.badge--processed {
		background: var(--blue-lt);
		color: var(--blue-tx);
	}

	.badge--pending {
		background: var(--amber-lt);
		color: var(--amber-tx);
	}

	.badge--rejected {
		background: var(--red-lt);
		color: var(--red-tx);
	}

	/* Pending row accent */
	.wv-tx-card--pending::before {
		content: '';
		position: absolute;
		left: 0;
		top: 8px;
		bottom: 8px;
		width: 3px;
		border-radius: 0 3px 3px 0;
		background: var(--amber);
	}

	/* ═══════════════════════════════════════════════
   EMPTY STATE
═══════════════════════════════════════════════ */
	.wv-empty-msg {
		text-align: center;
		padding: 40px 20px;
		color: var(--i4);
		font-size: 14px;
	}

	.wv-empty-ico {
		display: block;
		margin: 0 auto 12px;
		width: 28px;
		height: 28px;
		opacity: .2;
	}

	/* ═══════════════════════════════════════════════
   PAGINATION — mobile-optimized
═══════════════════════════════════════════════ */
	.wv-pagination {
		display: flex;
		align-items: center;
		flex-direction: column;
		gap: 10px;
		padding: 13px 14px;
		border-top: 1px solid var(--bdr);
	}

	.wv-page-info {
		font-size: 12.5px;
		color: var(--i4);
		font-weight: 500;
		text-align: center;
	}

	.wv-page-btns {
		display: flex;
		gap: 5px;
		align-items: center;
		flex-wrap: wrap;
		justify-content: center;
	}

	.wv-page-btn {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		min-width: 38px;
		min-height: 38px;
		padding: 0 8px;
		border-radius: var(--r-md);
		border: 1.5px solid var(--bdr2);
		background: var(--s0);
		color: var(--i2);
		font-family: var(--font);
		font-size: 14px;
		font-weight: 600;
		cursor: pointer;
		transition: all .2s;
		touch-action: manipulation;
	}

	.wv-page-btn:active:not(:disabled) {
		background: var(--s2);
		border-color: var(--blue);
	}

	.wv-page-btn.is-on {
		background: var(--blue);
		color: #fff;
		border-color: var(--blue);
		box-shadow: 0 2px 10px rgba(30, 84, 232, .3);
	}

	.wv-page-btn:disabled {
		opacity: .32;
		cursor: not-allowed;
	}

	.wv-page-dots {
		padding: 0 3px;
		color: var(--i4);
		font-size: 14px;
	}

	/* ═══════════════════════════════════════════════
   REVEAL ANIMATION
═══════════════════════════════════════════════ */
	[data-rev] {
		opacity: 0;
		transform: translateY(12px);
		transition: opacity .5s cubic-bezier(.16, 1, .3, 1), transform .5s cubic-bezier(.16, 1, .3, 1);
	}

	[data-rev].on {
		opacity: 1;
		transform: none;
	}

	/* ═══════════════════════════════════════════════
   TABLET+ (640px)
═══════════════════════════════════════════════ */
	@media (min-width:640px) {
		.wv {
			padding: 20px 20px 56px;
			gap: 20px;
		}

		.wv-hero__inner {
			padding: 32px 36px;
			flex-direction: row;
			align-items: center;
			gap: 32px;
		}

		.wv-hero__left {
			flex: 1;
			min-width: 0;
		}

		.wv-hero__title {
			font-size: 28px;
		}

		.wv-hero__stats {
			grid-template-columns: repeat(4, 1fr);
			gap: 10px;
		}

		.wv-hero__stat-val {
			font-size: 17px;
		}

		.wv-hero__cta {
			width: auto;
		}

		.wv-hero__bal {
			flex-direction: column;
			align-items: flex-start;
			width: 230px;
			flex-shrink: 0;
			padding: 22px 24px;
		}

		.wv-hero__bal-amt {
			font-size: 34px;
		}

		.wv-tx__title-row {
			flex-direction: row;
			align-items: center;
			flex-wrap: nowrap;
			margin-bottom: 16px;
		}

		.wv-filters {
			flex-direction: row;
			align-items: center;
			flex-wrap: nowrap;
		}

		.wv-filter-row-bottom {
			display: contents;
		}

		.wv-filter-scroll {
			flex: 1;
		}

		.wv-bank-form__footer {
			flex-direction: row;
			justify-content: flex-end;
		}

		.wv-bank-form__footer .wv-btn {
			width: auto;
		}

		.wv-pagination {
			flex-direction: row;
			justify-content: space-between;
		}

		.wv-page-info {
			text-align: left;
		}
	}

	/* ═══════════════════════════════════════════════
   DESKTOP (900px+)
═══════════════════════════════════════════════ */
	@media (min-width:900px) {
		.wv {
			padding: 24px 28px 64px;
			gap: 24px;
		}

		.wv-grid {
			display: grid;
			grid-template-columns: repeat(2, 1fr);
			gap: 24px;
		}

		.wv-hero__title {
			font-size: 34px;
		}

		.wv-hero__bal {
			width: 250px;
		}

		.wv-hero__stats {
			grid-template-columns: repeat(4, 1fr);
		}

		.wv-tx__head {
			padding: 22px 24px 0;
		}

		.wv-tx__h2 {
			font-size: 21px;
		}

		/* Show table, hide mobile cards */
		.wv-table-wrap {
			display: block;
			overflow-x: auto;
			-webkit-overflow-scrolling: touch;
			scrollbar-width: thin;
			scrollbar-color: var(--bdr2) var(--s1);
		}

		.wv-table-wrap::-webkit-scrollbar {
			height: 6px;
		}

		.wv-table-wrap::-webkit-scrollbar-track {
			background: var(--s1);
		}

		.wv-table-wrap::-webkit-scrollbar-thumb {
			background: var(--bdr2);
			border-radius: 4px;
		}

		.wv-mobile-list {
			display: none !important;
		}

		.wv-table {
			width: 100%;
			border-collapse: collapse;
			min-width: 600px;
		}

		.wv-table thead tr {
			background: var(--s1);
		}

		.wv-table th {
			padding: 10px 20px;
			font-size: 10.5px;
			font-weight: 700;
			letter-spacing: .09em;
			text-transform: uppercase;
			color: var(--i4);
			text-align: left;
			border-bottom: 1px solid var(--bdr);
			white-space: nowrap;
		}

		.wv-table td {
			padding: 13px 20px;
			border-top: 1px solid var(--bdr);
			font-size: 13.5px;
			color: var(--i1);
			vertical-align: middle;
		}

		.wv-table tbody tr:hover {
			background: var(--s1);
		}

		.td-date {
			color: var(--i4) !important;
			font-size: 12.5px !important;
			white-space: nowrap;
		}

		.td-desc {
			color: var(--i4) !important;
			font-size: 13px !important;
		}

		.td-num {
			color: var(--i5) !important;
			font-size: 12px !important;
			font-family: var(--mono);
		}

		.td-cr {
			color: var(--green) !important;
			font-weight: 700;
		}

		.td-dr {
			color: var(--red) !important;
			font-weight: 700;
		}

		.wv-empty td {
			text-align: center;
			padding: 60px 20px;
			color: var(--i4);
			font-size: 14px;
		}

		.wv-pagination {
			flex-direction: row;
			justify-content: space-between;
			padding: 15px 22px;
		}

		.wv-page-info {
			text-align: left;
		}
	}

	@media (max-width: 768px) {

		.main-area,
		.page-grid {
			width: 100% !important;
			max-width: 100% !important;
			overflow-x: hidden !important;
			padding: 10px !important;
		}

		body {
			overflow-x: hidden !important;
		}

		.wv {
			padding: 10px !important;
			gap: 12px !important;
		}

		.wv-card,
		.wv-hero,
		.wv-tx {
			width: 100% !important;
		}

		.wv-hero__inner {
			padding: 16px !important;
			gap: 14px !important;
		}

		.wv-hero__stats {
			grid-template-columns: 1fr 1fr !important;
		}

		.wv-hero__bal {
			flex-direction: column !important;
			align-items: flex-start !important;
			width: 100% !important;
		}

		.wv-hero__bal-amt {
			font-size: 26px !important;
		}

		.wv-bank-grid {
			grid-template-columns: 1fr !important;
		}

		.wv-bank-box {
			width: 100% !important;
		}

		.wv-btn,
		.wv-input {
			width: 100% !important;
		}

		.wv-input-wrap {
			width: 100%;
		}

		.wv-page-btns {
			justify-content: center !important;
			flex-wrap: wrap !important;
		}
	}

	@media (max-width: 480px) {

		.wv-tx-card {
			grid-template-columns: 36px 1fr !important;
			gap: 8px !important;
		}

		.wv-tx-card__right {
			grid-column: 2;
			align-items: flex-start !important;
		}

		.wv-tx-card__amt {
			font-size: 14px !important;
		}
	}

	/* ═══════════════════════════════════════════════
   SAFE AREA
═══════════════════════════════════════════════ */
	@supports (padding:max(0px)) {
		.wv {
			padding-left: max(12px, env(safe-area-inset-left));
			padding-right: max(12px, env(safe-area-inset-right));
			padding-bottom: max(60px, env(safe-area-inset-bottom));
		}
	}

	/* ═══════════════════════════════════════════════
   ACCESSIBILITY
═══════════════════════════════════════════════ */
	@media (prefers-reduced-motion:reduce) {

		*,
		*::before,
		*::after {
			animation-duration: .01ms !important;
			transition-duration: .01ms !important;
		}

		[data-rev] {
			opacity: 1;
			transform: none;
		}
	}

	*:focus-visible {
		outline: 2px solid var(--blue);
		outline-offset: 2px;
	}
</style>

<?php if ($this->session->flashdata('error')): ?>
	<div class="wv-alert wv-alert--err" role="alert">
		<div class="wv-alert__ico">
			<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
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
			<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
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
		<div class="wv-hero__bg" aria-hidden="true"></div>
		<div class="wv-hero__dots" aria-hidden="true"></div>
		<div class="wv-hero__inner">
			<div class="wv-hero__left">
				<p class="wv-hero__eyebrow" aria-hidden="true">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
						<line x1="12" y1="5" x2="12" y2="19" />
						<line x1="5" y1="12" x2="19" y2="12" />
					</svg>
					Add Balance
				</a>
			</div>

			<div class="wv-hero__bal" role="region" aria-label="Available balance">
				<div class="wv-hero__bal-left">
					<p class="wv-hero__bal-lbl">Available Balance</p>
					<span class="wv-hero__bal-amt">₹<?php echo number_format((float)$user->wallet_balance, 2); ?></span>
					<div class="wv-hero__bal-note">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
							<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
						</svg>
						Secured &amp; Encrypted
					</div>
				</div>
				<div class="wv-hero__bal-chip">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
						<polyline points="20 6 9 17 4 12" />
					</svg>
					Active
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
				<form method="post" action="<?php echo site_url('wallet/request-withdrawal'); ?>" class="wv-form" id="wvWithdrawForm">
					<div class="wv-field">
						<label class="wv-label" for="wd_amount">Amount (₹)</label>
						<div class="wv-input-wrap">
							<span class="wv-pfx" aria-hidden="true">₹</span>
							<input
								type="number" id="wd_amount" name="amount"
								min="0.01" step="0.01"
								max="<?php echo number_format((float)$user->wallet_balance, 2, '.', ''); ?>"
								placeholder="0.00"
								<?php echo $bank_details_saved ? '' : 'disabled'; ?> required
								class="wv-input wv-input--pfx"
								aria-label="Withdrawal amount in rupees">
						</div>
						<?php if ($bank_details_saved): ?>
							<p class="wv-hint">Max available: <strong>₹<?php echo number_format((float)$user->wallet_balance, 2); ?></strong></p>
							<p class="wv-hint">Minimum withdrawal: <strong>Rs <?php echo number_format((float) $withdraw_min_amount, ((float) $withdraw_min_amount == floor((float) $withdraw_min_amount)) ? 0 : 2); ?></strong></p>
						<?php endif; ?>
					</div>
					<button
						type="submit"
						class="wv-btn wv-btn--full <?php echo $bank_details_saved ? 'wv-btn--red' : 'wv-btn--disabled'; ?>"
						<?php echo $bank_details_saved ? '' : 'disabled aria-disabled="true"'; ?>>
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
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
								<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
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
							style="margin-top:16px;">
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

	</div><!-- /wv-grid -->

	<!-- ══ TRANSACTION HISTORY ══ -->
	<section class="wv-tx" data-rev aria-label="Transaction history">
		<div class="wv-tx__head">
			<div class="wv-tx__title-row">
				<div class="wv-tx__heading-group">
					<p class="wv-tx__eyebrow">Ledger</p>
					<h2 class="wv-tx__h2">Transaction History</h2>
				</div>
				<span class="wv-tx__count" id="wvCount" aria-live="polite"><?php echo $wallet_history_count; ?> records</span>
			</div>

			<!-- Filter bar -->
			<div class="wv-filters" role="group" aria-label="Filter transactions" id="wvFilters">
				<div class="wv-filter-scroll" aria-label="Transaction type filters">
					<?php
					$wv_filters = [
						'all'         => ['label' => 'All',         'color' => '#1e54e8'],
						'deposits'    => ['label' => 'Deposits',    'color' => '#7c3aed'],
						'winnings'    => ['label' => 'Winnings',    'color' => '#0f9d58'],
						'withdrawals' => ['label' => 'Withdrawals', 'color' => '#e53935'],
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
				</div>
				<div class="wv-filter-row-bottom">
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
					<?php
					if (!empty($withdrawals)):
						foreach ($withdrawals as $wr):
							$ws = strtolower(trim((string)$wr->status));
							if (in_array($ws, ['approved', 'success', 'completed'])) {
								$sl = 'Success';
								$sc = 'badge--success';
							} elseif (in_array($ws, ['pending', 'processing'])) {
								$sl = 'Pending';
								$sc = 'badge--pending';
							} elseif (in_array($ws, ['rejected', 'failed', 'declined'])) {
								$sl = 'Rejected';
								$sc = 'badge--rejected';
							} else {
								$sl = ucfirst($ws);
								$sc = 'badge--pending';
							}
					?>
							<tr data-ft="withdrawals" data-history-item="1">
								<td class="td-num">—</td>
								<td><span class="badge badge--withdrawal"><span class="badge-dot" aria-hidden="true"></span>Withdrawal</span></td>
								<td class="td-desc">Withdrawal request<?php echo !empty($wr->admin_note) ? ' - ' . html_escape($wr->admin_note) : ''; ?></td>
								<td class="td-dr">− ₹<?php echo number_format((float)$wr->amount, 2); ?></td>
								<td><span class="badge <?php echo $sc; ?>"><?php echo html_escape($sl); ?></span></td>
								<td class="td-date"><?php echo html_escape(date('d M Y, h:i A', strtotime($wr->created_at))); ?></td>
							</tr>
					<?php endforeach;
					endif; ?>

					<?php
					if (!empty($transactions)):
						foreach ($transactions as $index => $tx):
							$is_cr   = strtolower((string)$tx->type) === 'credit';
							$type_lbl = isset($tx->history_type_label) ? $tx->history_type_label : 'Transaction';
							$b_class = isset($tx->history_badge_class) ? $tx->history_badge_class : 'badge--default';
							$tx_ft   = isset($tx->history_filter)      ? $tx->history_filter      : 'all';
							$desc    = isset($tx->history_description)  ? $tx->history_description : '';
							if (isset($tx->history_type_label)) {
								$sc = isset($tx->history_status_class) ? $tx->history_status_class : ($is_cr ? 'badge--success' : 'badge--processed');
								$sl = isset($tx->history_status_label) ? $tx->history_status_label : ($is_cr ? 'Success' : 'Processed');
							} elseif ($tx->source_type === 'deposit') {
								$type_lbl = 'Deposit';
								$b_class = 'badge--deposit';
								$tx_ft = 'deposits';
								$desc = 'Wallet top-up';
							} elseif ($tx->source_type === 'question_result') {
								$type_lbl = 'Winning';
								$b_class = 'badge--winning';
								$tx_ft = 'winnings';
								$desc = 'Question result payout';
							} elseif ($tx->source_type === 'trade_entry') {
								$type_lbl = 'Trade';
								$b_class = 'badge--trade';
								$tx_ft = 'trades';
								$desc = 'Trade entry fee';
							} elseif ($tx->source_type === 'trade_sell') {
								$type_lbl = 'Trade Exit';
								$b_class = 'badge--trade';
								$tx_ft = 'trades';
								$desc = 'Trade sold for instant profit';
							} elseif ($tx->source_type === 'withdrawal') {
								$type_lbl = 'Withdrawal';
								$b_class = 'badge--withdrawal';
								$tx_ft = 'withdrawals';
								$desc = 'Withdrawal processed';
							} elseif ($tx->source_type === 'referral_bonus') {
								$type_lbl = 'Referral';
								$b_class = 'badge--referral';
								$tx_ft = 'all';
								$desc = 'Referral bonus';
							} elseif ($tx->source_type === 'refund') {
								$type_lbl = 'Refund';
								$b_class = 'badge--refund';
								$tx_ft = 'refunds';
								$desc = 'Refund credited';
							}
							if (!isset($tx->history_type_label)) {
								$sc = $is_cr ? 'badge--success' : 'badge--processed';
								$sl = $is_cr ? 'Success' : 'Processed';
							}
					?>
							<tr data-ft="<?php echo $tx_ft; ?>" data-history-item="1">
								<td class="td-num">—</td>
								<td><span class="badge <?php echo $b_class; ?>"><span class="badge-dot" aria-hidden="true"></span><?php echo html_escape($type_lbl); ?></span></td>
								<td class="td-desc"><?php echo html_escape($desc); ?></td>
								<td class="<?php echo $is_cr ? 'td-cr' : 'td-dr'; ?>"><?php echo $is_cr ? '+' : '−'; ?> ₹<?php echo number_format((float)$tx->amount, 2); ?></td>
								<td><span class="badge <?php echo $sc; ?>"><?php echo html_escape($sl); ?></span></td>
								<td class="td-date"><?php echo html_escape(date('d M Y, h:i A', strtotime($tx->created_at))); ?></td>
							</tr>
					<?php endforeach;
					endif; ?>
				</tbody>
			</table>
		</div>

		<!-- Mobile List — New Premium Card Design -->
		<div class="wv-mobile-list" id="wvMobileList" aria-label="Transactions list">
			<?php
			/* Helper: map source_type to icon SVG and ico class */
			function wv_get_ico($source_type, $type_lbl)
			{
				$map = [
					'deposit'          => ['ico--deposit',    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg>'],
					'question_result'  => ['ico--winning',    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>'],
					'trade_entry'      => ['ico--trade',      '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>'],
					'trade_sell'       => ['ico--trade',      '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M4 12h10"/><path d="M10 8l4 4-4 4"/><path d="M20 6v12"/></svg>'],
					'withdrawal'       => ['ico--withdrawal', '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><polyline points="19 12 12 19 5 12"/></svg>'],
					'withdrawal_request' => ['ico--withdrawal', '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><polyline points="19 12 12 19 5 12"/></svg>'],
					'referral_bonus'   => ['ico--referral',   '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>'],
					'refund'           => ['ico--refund',     '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 102.13-9.36L1 10"/></svg>'],
				];
				if (isset($map[$source_type])) return $map[$source_type];
				return ['ico--default', '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>'];
			}

			/* Pending withdrawals */
			if (!empty($withdrawals)):
				foreach ($withdrawals as $wr):
					$ws = strtolower(trim((string)$wr->status));
					if (in_array($ws, ['approved', 'success', 'completed'])) {
						$sl = 'Success';
						$sc = 'badge--success';
					} elseif (in_array($ws, ['pending', 'processing'])) {
						$sl = 'Pending';
						$sc = 'badge--pending';
					} elseif (in_array($ws, ['rejected', 'failed', 'declined'])) {
						$sl = 'Rejected';
						$sc = 'badge--rejected';
					} else {
						$sl = ucfirst($ws);
						$sc = 'badge--pending';
					}
					list($ico_cls, $ico_svg) = wv_get_ico('withdrawal_request', 'Withdrawal');
			?>
					<div class="wv-tx-card wv-tx-card--pending" data-ft="withdrawals" data-history-item="1">
						<div class="wv-tx-card__ico <?php echo $ico_cls; ?>"><?php echo $ico_svg; ?></div>
						<div class="wv-tx-card__body">
							<div class="wv-tx-card__type">Withdrawal</div>
							<div class="wv-tx-card__desc">Withdrawal request<?php echo !empty($wr->admin_note) ? ' - ' . html_escape($wr->admin_note) : ''; ?></div>
							<div class="wv-tx-card__meta">
								<span class="wv-tx-card__date"><?php echo html_escape(date('d M Y, h:i A', strtotime($wr->created_at))); ?></span>
							</div>
						</div>
						<div class="wv-tx-card__right">
							<span class="wv-tx-card__amt amt-dr">− ₹<?php echo number_format((float)$wr->amount, 2); ?></span>
							<span class="badge <?php echo $sc; ?>"><?php echo html_escape($sl); ?></span>
							<span class="wv-tx-card__num"># —</span>
						</div>
					</div>
			<?php endforeach;
			endif; ?>

			<?php
			if (!empty($transactions)):
				foreach ($transactions as $index => $tx):
					$is_cr   = strtolower((string)$tx->type) === 'credit';
					$type_lbl = isset($tx->history_type_label) ? $tx->history_type_label : 'Transaction';
					$b_class = isset($tx->history_badge_class) ? $tx->history_badge_class : 'badge--default';
					$tx_ft   = isset($tx->history_filter)      ? $tx->history_filter      : 'all';
					$desc    = isset($tx->history_description)  ? $tx->history_description : '';
					$src     = isset($tx->source_type) ? $tx->source_type : '';
					if (isset($tx->history_type_label)) {
						$sc = isset($tx->history_status_class) ? $tx->history_status_class : ($is_cr ? 'badge--success' : 'badge--processed');
						$sl = isset($tx->history_status_label) ? $tx->history_status_label : ($is_cr ? 'Success' : 'Processed');
					} elseif ($src === 'deposit') {
						$type_lbl = 'Deposit';
						$b_class = 'badge--deposit';
						$tx_ft = 'deposits';
						$desc = 'Wallet top-up';
					} elseif ($src === 'question_result') {
						$type_lbl = 'Winning';
						$b_class = 'badge--winning';
						$tx_ft = 'winnings';
						$desc = 'Question result payout';
					} elseif ($src === 'trade_entry') {
						$type_lbl = 'Trade';
						$b_class = 'badge--trade';
						$tx_ft = 'trades';
						$desc = 'Trade entry fee';
					} elseif ($src === 'trade_sell') {
						$type_lbl = 'Trade Exit';
						$b_class = 'badge--trade';
						$tx_ft = 'trades';
						$desc = 'Trade sold for instant profit';
					} elseif ($src === 'withdrawal') {
						$type_lbl = 'Withdrawal';
						$b_class = 'badge--withdrawal';
						$tx_ft = 'withdrawals';
						$desc = 'Withdrawal processed';
					} elseif ($src === 'referral_bonus') {
						$type_lbl = 'Referral';
						$b_class = 'badge--referral';
						$tx_ft = 'all';
						$desc = 'Referral bonus';
					} elseif ($src === 'refund') {
						$type_lbl = 'Refund';
						$b_class = 'badge--refund';
						$tx_ft = 'refunds';
						$desc = 'Refund credited';
					}
					if (!isset($tx->history_type_label)) {
						$sc = $is_cr ? 'badge--success' : 'badge--processed';
						$sl = $is_cr ? 'Success' : 'Processed';
					}
					list($ico_cls, $ico_svg) = wv_get_ico($src, $type_lbl);
			?>
					<div class="wv-tx-card" data-ft="<?php echo $tx_ft; ?>" data-history-item="1">
						<div class="wv-tx-card__ico <?php echo $ico_cls; ?>"><?php echo $ico_svg; ?></div>
						<div class="wv-tx-card__body">
							<div class="wv-tx-card__type"><?php echo html_escape($type_lbl); ?></div>
							<div class="wv-tx-card__desc"><?php echo html_escape($desc); ?></div>
							<div class="wv-tx-card__meta">
								<span class="wv-tx-card__date"><?php echo html_escape(date('d M Y, h:i A', strtotime($tx->created_at))); ?></span>
							</div>
						</div>
						<div class="wv-tx-card__right">
							<span class="wv-tx-card__amt <?php echo $is_cr ? 'amt-cr' : 'amt-dr'; ?>"><?php echo $is_cr ? '+' : '−'; ?> ₹<?php echo number_format((float)$tx->amount, 2); ?></span>
							<span class="badge <?php echo $sc; ?>"><?php echo html_escape($sl); ?></span>
							<span class="wv-tx-card__num"># —</span>
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
				el.style.transition = 'opacity .4s, transform .4s';
				el.style.opacity = '0';
				el.style.transform = 'translateY(-8px)';
				setTimeout(function() {
					el.remove();
				}, 450);
			}, 5000);
		});

		/* ── Rows dropdown ── */
		var withdrawalStatusAlert = <?php echo json_encode($withdrawal_status_alert_payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;

		function rememberWalletAlert(notificationId) {
			if (!notificationId || !window.localStorage) {
				return;
			}

			try {
				window.localStorage.setItem('wallet-withdrawal-alert-seen-' + notificationId, '1');
			} catch (e) {}
		}

		function isWalletAlertRemembered(notificationId) {
			if (!notificationId || !window.localStorage) {
				return false;
			}

			try {
				return window.localStorage.getItem('wallet-withdrawal-alert-seen-' + notificationId) === '1';
			} catch (e) {
				return false;
			}
		}

		function markWalletNotificationRead(notificationId) {
			if (!notificationId || typeof fetch === 'undefined') {
				return;
			}

			fetch('<?php echo site_url('dashboard/mark-notification-read'); ?>', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
					'X-Requested-With': 'XMLHttpRequest'
				},
				body: 'notification_id=' + encodeURIComponent(notificationId)
			}).catch(function() {
				return null;
			});
		}

		function showWalletStatusAlert() {
			if (!withdrawalStatusAlert || !withdrawalStatusAlert.id || !withdrawalStatusAlert.message) {
				return;
			}

			if (isWalletAlertRemembered(withdrawalStatusAlert.id)) {
				markWalletNotificationRead(withdrawalStatusAlert.id);
				return;
			}

			if (typeof Swal !== 'undefined') {
				Swal.fire({
					icon: withdrawalStatusAlert.icon || 'info',
					title: withdrawalStatusAlert.title || 'Withdrawal update',
					html: withdrawalStatusAlert.html || withdrawalStatusAlert.message,
					confirmButtonText: 'OK',
					confirmButtonColor: '#2563eb',
					allowOutsideClick: false
				}).then(function() {
					rememberWalletAlert(withdrawalStatusAlert.id);
					markWalletNotificationRead(withdrawalStatusAlert.id);
				});
				return;
			}

			alert(withdrawalStatusAlert.message);
			rememberWalletAlert(withdrawalStatusAlert.id);
			markWalletNotificationRead(withdrawalStatusAlert.id);
		}

		showWalletStatusAlert();

		var rowsBtn = document.getElementById('wvRowsBtn');
		var rowsDd = document.getElementById('wvRowsDd');
		var rowsLbl = document.getElementById('wvRowsLbl');

		function closeRows() {
			if (!rowsDd) return;
			rowsDd.classList.remove('open');
			rowsBtn.classList.remove('open');
			rowsBtn.setAttribute('aria-expanded', 'false');
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

		function isMobile() {
			return window.innerWidth < 900;
		}

		function getRows() {
			var sel = isMobile() ?
				'#wvMobileList [data-history-item="1"][data-ft]' :
				'#wvBody tr[data-history-item="1"][data-ft]';
			var all = Array.from(document.querySelectorAll(sel));
			return currentFilter === 'all' ? all : all.filter(function(r) {
				return r.dataset.ft === currentFilter;
			});
		}

		function makeBtn(label, disabled, active) {
			var b = document.createElement('button');
			b.className = 'wv-page-btn' + (active ? ' is-on' : '');
			b.textContent = label;
			b.disabled = !!disabled;
			b.type = 'button';
			if (active) b.setAttribute('aria-current', 'page');
			return b;
		}

		function render() {
			var rows = getRows();
			var total = rows.length;
			var limit = rowsPerPage >= 99999 ? total : rowsPerPage;
			var pages = Math.max(1, Math.ceil(total / (limit || 1)));
			currentPage = Math.min(currentPage, pages);
			var start = (currentPage - 1) * limit;
			var end = start + limit;

			/* Hide ALL items in both views */
			Array.from(document.querySelectorAll(
				'#wvBody tr[data-history-item="1"][data-ft], #wvMobileList [data-history-item="1"][data-ft]'
			)).forEach(function(r) {
				r.style.display = 'none';
			});

			/* Show paginated items for active view + update numbers */
			rows.forEach(function(r, i) {
				r.style.display = (i >= start && i < end) ? '' : 'none';
				/* Update sequence number */
				var numEl = r.querySelector('.td-num, .wv-tx-card__num');
				if (numEl) numEl.textContent = (r.tagName === 'TR') ? (i + 1) : ('# ' + (i + 1));
			});

			/* Empty state */
			var emptyTr = document.getElementById('wvEmptyTr');
			var emptyMob = document.getElementById('wvEmptyMob');
			if (total === 0) {
				if (!emptyTr) {
					emptyTr = document.createElement('tr');
					emptyTr.id = 'wvEmptyTr';
					emptyTr.className = 'wv-empty';
					emptyTr.innerHTML = '<td colspan="6"><div class="wv-empty-msg">' +
						'<svg class="wv-empty-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>' +
						'No transactions found for this filter.</div></td>';
					document.getElementById('wvBody').appendChild(emptyTr);
				}
				emptyTr.style.display = '';
				if (!emptyMob) {
					emptyMob = document.createElement('div');
					emptyMob.id = 'wvEmptyMob';
					emptyMob.innerHTML = '<div class="wv-empty-msg">' +
						'<svg class="wv-empty-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>' +
						'No transactions found for this filter.</div>';
					document.getElementById('wvMobileList').appendChild(emptyMob);
				}
				emptyMob.style.display = '';
			} else {
				if (emptyTr) emptyTr.style.display = 'none';
				if (emptyMob) emptyMob.style.display = 'none';
			}

			/* Count badge */
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

			var pagEl = document.getElementById('wvPagination');
			if (pagEl) pagEl.style.display = (pages <= 1 && total <= limit) ? 'none' : 'flex';
		}

		render();

		var resizeTimer;
		window.addEventListener('resize', function() {
			clearTimeout(resizeTimer);
			resizeTimer = setTimeout(render, 100);
		});

		var withdrawForm = document.getElementById('wvWithdrawForm');
		var withdrawAmountInput = document.getElementById('wd_amount');
		if (withdrawForm && withdrawAmountInput) {
			withdrawForm.addEventListener('submit', function(e) {
				var amount = parseFloat(withdrawAmountInput.value) || 0;
				var minAmount = <?php echo number_format((float) $withdraw_min_amount, 2, '.', ''); ?>;
				if (amount < minAmount) {
					e.preventDefault();
					var minText = 'Minimum withdrawal amount is Rs ' + (Number.isInteger(minAmount) ? String(minAmount) : minAmount.toFixed(2)) + '.';
					if (typeof userSwalAlert === 'function') {
						userSwalAlert(minText, 'error');
					} else if (typeof Swal !== 'undefined') {
						Swal.fire({
							icon: 'error',
							text: minText,
							confirmButtonColor: '#2563eb'
						});
					} else {
						alert(minText);
					}
				}
			});
		}
	}());
</script>
