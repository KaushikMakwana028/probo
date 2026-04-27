<?php
// ── DATA PREP ────────────────────────────────────────────────────────────────
$answer_error_flash = $this->session->flashdata('error');
if ($answer_error_flash === 'This market is not open for trading right now.' && !empty($market_is_open)) {
	$answer_error_flash = '';
}
$selected_answer  = $selected_answer_state ? strtolower((string)$selected_answer_state->answer) : 'yes';
$is_locked        = $selected_answer_state !== NULL;
$is_settled       = $is_locked && !empty($selected_answer_state->settled_at);
$is_sold_trade    = $is_locked && strtolower((string)($selected_answer_state->settlement_type ?? '')) === 'sell';
$is_correct_ans   = $is_sold_trade || ($is_settled && $selected_answer === strtolower((string)$selected_question->answer_key));
$sell_trade_summary = isset($sell_trade_summary) && is_array($sell_trade_summary) ? $sell_trade_summary : array();
$yes_price        = (float)$selected_question->yes_price;
$no_price         = (float)$selected_question->no_price;
$market_total     = max(1.0, $yes_price + $no_price);
$default_price    = $selected_answer_state ? (float)$selected_answer_state->price : ($selected_answer === 'no' ? $no_price : $yes_price);
$default_price    = $default_price > 0 ? $default_price : max($yes_price, $no_price, 0.5);
$default_quantity = $selected_answer_state ? max(1, min(1000, (int)$selected_answer_state->quantity)) : 1;
$price_max        = max(0.5, $market_total - 0.5);
$multiplier       = isset($selected_question->multiplier) ? (float)$selected_question->multiplier : 1.25;
if ($selected_answer_state && $default_price > 0 && $default_quantity > 0) {
	$locked_preview = isset($selected_answer_state->entry_payout_amount) ? (float)$selected_answer_state->entry_payout_amount : 0;
	if ($locked_preview > 0) {
		$multiplier = round($locked_preview / ($default_price * $default_quantity), 4);
	}
}
$winning_preview  = ($selected_answer_state && isset($selected_answer_state->entry_payout_amount) && (float)$selected_answer_state->entry_payout_amount > 0)
	? (float)$selected_answer_state->entry_payout_amount
	: round($default_price * $default_quantity * $multiplier, 2);
$yes_trade_qty    = isset($trade_breakdown['yes_quantity']) ? (int)$trade_breakdown['yes_quantity'] : 0;
$no_trade_qty     = isset($trade_breakdown['no_quantity'])  ? (int)$trade_breakdown['no_quantity']  : 0;
$total_trade_qty  = $yes_trade_qty + $no_trade_qty;
$joined_users     = isset($total_users) ? (int)$total_users : 0;
$yes_pct          = $market_total > 0 ? round($yes_price / $market_total * 100) : 50;
$no_pct           = 100 - $yes_pct;
$yes_vol_pct      = $total_trade_qty > 0 ? round($yes_trade_qty / $total_trade_qty * 100) : 50;
$no_vol_pct       = 100 - $yes_vol_pct;
$QTY_MAX          = 1000;
$QTY_MIN          = 1;
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400&display=swap" rel="stylesheet">

<style>
	:root {
		--f: 'Roboto', sans-serif;
		--ink: #0f172a;
		--ink-2: #1e293b;
		--muted: #64748b;
		--hint: #94a3b8;
		--surface: #f8fafc;
		--surface-2: #f1f5f9;
		--white: #ffffff;
		--border: rgba(0, 0, 0, 0.07);
		--border-2: rgba(0, 0, 0, 0.12);
		--accent: #6366f1;
		--accent-soft: rgba(99, 102, 241, 0.08);
		--accent-border: rgba(99, 102, 241, 0.25);
		--green: #22c55e;
		--green-soft: rgba(34, 197, 94, 0.08);
		--green-border: rgba(34, 197, 94, 0.25);
		--red: #ef4444;
		--red-soft: rgba(239, 68, 68, 0.08);
		--red-border: rgba(239, 68, 68, 0.25);
		--r-sm: 8px;
		--r-md: 12px;
		--r-lg: 16px;
	}

	*,
	*::before,
	*::after {
		box-sizing: border-box;
		margin: 0;
		padding: 0;
	}

	.tp {
		font-family: var(--f);
		color: var(--ink);
		display: grid;
		gap: 14px;
	}

	/* ─── ALERTS ─── */
	.tp-alert {
		padding: 12px 16px;
		border-radius: var(--r-md);
		font-size: 14px;
		font-weight: 500;
		display: flex;
		align-items: center;
		gap: 9px;
	}

	.tp-alert-err {
		background: var(--red-soft);
		border: 0.5px solid var(--red-border);
		color: #b91c1c;
	}

	.tp-alert-ok {
		background: var(--green-soft);
		border: 0.5px solid var(--green-border);
		color: #15803d;
	}

	/* ─── BACK ─── */
	.tp-back {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		font-size: 13px;
		font-weight: 500;
		color: var(--muted);
		text-decoration: none;
		padding: 7px 14px;
		border-radius: var(--r-sm);
		border: 0.5px solid var(--border-2);
		background: var(--white);
		transition: all .15s;
	}

	.tp-back:hover {
		color: var(--ink);
		border-color: var(--border-2);
		background: var(--surface);
	}

	/* ─── HERO ─── */
	.tp-hero {
		background: var(--white);
		border: 0.5px solid var(--border);
		border-radius: var(--r-lg);
		padding: 22px 26px;
		display: flex;
		justify-content: space-between;
		align-items: flex-start;
		gap: 20px;
	}

	.tp-hero-q {
		font-size: clamp(16px, 2vw, 20px);
		font-weight: 600;
		line-height: 1.4;
		color: var(--ink);
	}

	.tp-hero-sub {
		font-size: 13px;
		color: var(--muted);
		margin-top: 6px;
		line-height: 1.6;
		display: inline-flex;
		align-items: center;
		gap: 8px;
		flex-wrap: wrap;
	}

	.tp-hero-sub-icon {
		width: 22px;
		height: 22px;
		border-radius: 999px;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		flex-shrink: 0;
		font-size: 11px;
		font-weight: 700;
	}

	.tp-hero-sub-icon.open {
		background: var(--green-soft);
		color: #15803d;
		border: 0.5px solid var(--green-border);
	}

	.tp-hero-sub-icon.closed {
		background: var(--red-soft);
		color: #b91c1c;
		border: 0.5px solid var(--red-border);
	}

	.tp-hero-sub-icon.pending {
		background: var(--accent-soft);
		color: var(--accent);
		border: 0.5px solid var(--accent-border);
	}

	.tp-hero-pills {
		display: flex;
		flex-direction: column;
		gap: 6px;
		align-items: flex-end;
		flex-shrink: 0;
	}

	.tp-pill {
		display: inline-flex;
		align-items: center;
		gap: 5px;
		padding: 5px 12px;
		border-radius: 999px;
		font-size: 12px;
		font-weight: 500;
		white-space: nowrap;
	}

	.tp-pill-open {
		background: var(--green-soft);
		color: #15803d;
		border: 0.5px solid var(--green-border);
		box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.45);
	}

	.tp-pill-closed {
		background: var(--red-soft);
		color: #b91c1c;
		border: 0.5px solid var(--red-border);
		box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.35);
	}

	.tp-pill-cat {
		background: var(--accent-soft);
		color: var(--accent);
		border: 0.5px solid var(--accent-border);
	}

	.tp-pill-users {
		background: var(--surface-2);
		color: var(--muted);
		border: 0.5px solid var(--border-2);
	}

	.tp-pulse {
		width: 6px;
		height: 6px;
		border-radius: 50%;
		background: var(--green);
		animation: pulse 1.6s infinite;
	}

	@keyframes pulse {

		0%,
		100% {
			opacity: 1
		}

		50% {
			opacity: .3
		}
	}

	/* ─── LAYOUT ─── */
	.tp-layout {
		display: grid;
		grid-template-columns: minmax(0, 1fr) 280px;
		gap: 14px;
		align-items: start;
	}

	/* ─── CARDS ─── */
	.tp-card {
		background: var(--white);
		border: 0.5px solid var(--border);
		border-radius: var(--r-lg);
		overflow: hidden;
	}

	.tp-card+.tp-card {
		margin-top: 12px;
	}

	.tp-card-head {
		padding: 14px 20px;
		border-bottom: 0.5px solid var(--border);
		background: var(--surface);
	}

	.tp-card-title {
		font-size: 14px;
		font-weight: 500;
		color: var(--ink);
	}

	.tp-card-sub {
		font-size: 12px;
		color: var(--muted);
		margin-top: 2px;
	}

	.tp-card-body {
		padding: 20px;
	}

	/* ─── MARKET OVERVIEW ─── */
	.tp-bar-labels {
		display: flex;
		justify-content: space-between;
		font-size: 13px;
		font-weight: 500;
		margin-bottom: 8px;
	}

	.tp-yes-lbl {
		color: #15803d;
	}

	.tp-no-lbl {
		color: #b91c1c;
	}

	.tp-prob-bar {
		height: 6px;
		border-radius: 999px;
		background: var(--surface-2);
		display: flex;
		overflow: hidden;
		margin-bottom: 18px;
	}

	.tp-bar-y {
		height: 100%;
		background: var(--green-soft);
		border-left: 2px solid var(--green);
	}

	.tp-bar-n {
		height: 100%;
		background: var(--red-soft);
		border-right: 2px solid var(--red);
	}

	.tp-mchips {
		display: grid;
		grid-template-columns: repeat(3, 1fr);
		gap: 8px;
	}

	.tp-chip {
		background: var(--surface);
		border: 0.5px solid var(--border);
		border-radius: var(--r-md);
		padding: 12px;
		text-align: center;
	}

	.tp-chip-lbl {
		font-size: 11px;
		color: var(--muted);
		margin-bottom: 4px;
		text-transform: uppercase;
		letter-spacing: .06em;
	}

	.tp-chip-val {
		font-size: 18px;
		font-weight: 500;
	}

	.mc-yes .tp-chip-val {
		color: #15803d;
	}

	.mc-no .tp-chip-val {
		color: #b91c1c;
	}

	.tp-vol-row {
		display: flex;
		align-items: center;
		gap: 10px;
		margin-top: 14px;
		font-size: 12px;
		font-weight: 500;
	}

	.tp-vol-bar {
		flex: 1;
		height: 4px;
		background: var(--surface-2);
		border-radius: 999px;
		overflow: hidden;
	}

	.tp-vol-fill {
		height: 100%;
		border-radius: 999px;
		background: linear-gradient(90deg, var(--green), var(--red));
	}

	.tv-yes {
		color: #15803d;
	}

	.tv-no {
		color: #b91c1c;
	}

	/* ─── TRADE PANEL ─── */
	.tp-result {
		border-radius: var(--r-md);
		padding: 16px 20px;
		margin-bottom: 16px;
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 14px;
		flex-wrap: wrap;
	}

	.tp-result-pend {
		background: var(--accent-soft);
		border: 0.5px solid var(--accent-border);
	}

	.tp-result-correct {
		background: var(--green-soft);
		border: 0.5px solid var(--green-border);
	}

	.tp-result-wrong {
		background: var(--red-soft);
		border: 0.5px solid var(--red-border);
	}

	.tp-result-tag {
		font-size: 11px;
		font-weight: 500;
		text-transform: uppercase;
		letter-spacing: .06em;
		margin-bottom: 4px;
		color: var(--muted);
	}

	.tp-result h4 {
		font-size: 14px;
		font-weight: 500;
		margin-bottom: 3px;
	}

	.tp-result p {
		font-size: 12px;
		color: var(--muted);
		line-height: 1.5;
	}

	.tp-result-amt {
		font-size: 22px;
		font-weight: 600;
		flex-shrink: 0;
	}

	.tp-result-pend .tp-result-amt {
		color: var(--accent);
	}

	.tp-result-correct .tp-result-amt {
		color: #15803d;
	}

	.tp-result-wrong .tp-result-amt {
		color: #b91c1c;
	}

	/* Toggle */
	.tp-toggle {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 8px;
		margin-bottom: 16px;
	}

	.tp-toggle input[type=radio] {
		display: none;
	}

	.tp-toggle label {
		display: flex;
		align-items: center;
		justify-content: space-between;
		padding: 14px 16px;
		border-radius: var(--r-md);
		border: 0.5px solid var(--border-2);
		background: var(--surface);
		cursor: pointer;
		transition: all .18s;
	}

	.tp-tog-l {
		display: flex;
		align-items: center;
		gap: 10px;
	}

	.tp-tog-dot {
		width: 8px;
		height: 8px;
		border-radius: 50%;
	}

	.tog-yes .tp-tog-dot {
		background: var(--green);
	}

	.tog-no .tp-tog-dot {
		background: var(--red);
	}

	.tp-tog-name {
		font-size: 15px;
		font-weight: 500;
	}

	.tp-tog-price {
		font-size: 12px;
		color: var(--muted);
		margin-top: 2px;
	}

	.tp-tog-badge {
		font-size: 11px;
		font-weight: 500;
		padding: 3px 10px;
		border-radius: 6px;
		background: var(--surface-2);
		color: var(--muted);
		transition: all .18s;
	}

	#tp_yes:checked+label.tog-yes {
		border-color: var(--green);
		border-width: 1.5px;
		background: var(--green-soft);
	}

	#tp_yes:checked+label.tog-yes .tp-tog-name {
		color: #15803d;
	}

	#tp_yes:checked+label.tog-yes .tp-tog-badge {
		background: var(--green);
		color: #fff;
	}

	#tp_no:checked+label.tog-no {
		border-color: var(--red);
		border-width: 1.5px;
		background: var(--red-soft);
	}

	#tp_no:checked+label.tog-no .tp-tog-name {
		color: #b91c1c;
	}

	#tp_no:checked+label.tog-no .tp-tog-badge {
		background: var(--red);
		color: #fff;
	}

	.tp-toggle.locked label {
		cursor: not-allowed;
		opacity: .55;
	}

	/* Controls */
	.tp-controls {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 10px;
		margin-bottom: 16px;
	}

	.tp-ctrl {
		background: var(--surface);
		border: 0.5px solid var(--border);
		border-radius: var(--r-md);
		padding: 14px;
	}

	.tp-ctrl-top {
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-bottom: 10px;
	}

	.tp-ctrl-lbl {
		font-size: 11px;
		color: var(--muted);
		text-transform: uppercase;
		letter-spacing: .06em;
	}

	.tp-ctrl-val {
		font-size: 15px;
		font-weight: 500;
	}

	.tp-stepper {
		display: flex;
		align-items: center;
		gap: 8px;
	}

	.tp-step-btn {
		width: 30px;
		height: 30px;
		border-radius: var(--r-sm);
		border: 0.5px solid var(--border-2);
		background: var(--white);
		color: var(--ink);
		font-size: 16px;
		cursor: pointer;
		display: flex;
		align-items: center;
		justify-content: center;
		flex-shrink: 0;
		transition: all .14s;
	}

	.tp-step-btn:hover {
		background: var(--surface-2);
		border-color: var(--border-2);
	}

	.tp-step-btn:disabled {
		opacity: .35;
		cursor: not-allowed;
	}

	.tp-stepper input[type=range] {
		flex: 1;
		accent-color: var(--accent);
		cursor: pointer;
		height: 4px;
	}

	.tp-stepper input[type=number] {
		flex: 1;
		padding: 7px 4px;
		border-radius: var(--r-sm);
		border: 0.5px solid var(--border-2);
		background: var(--white);
		color: var(--ink);
		text-align: center;
		font-size: 14px;
		font-weight: 500;
		font-family: var(--f);
		outline: none;
	}

	.tp-stepper input[type=number]:focus {
		border-color: var(--accent);
	}

	.tp-stepper input[type=number]::-webkit-inner-spin-button,
	.tp-stepper input[type=number]::-webkit-outer-spin-button {
		-webkit-appearance: none;
	}

	.tp-ctrl-hint {
		font-size: 11px;
		color: var(--hint);
		margin-top: 6px;
	}

	.tp-qty-warn {
		font-size: 11px;
		color: #b91c1c;
		margin-top: 5px;
		display: none;
	}

	.tp-qty-warn.show {
		display: block;
	}

	/* Stake summary */
	.tp-stake {
		border: 0.5px solid var(--border);
		border-radius: var(--r-md);
		overflow: hidden;
		margin-bottom: 14px;
	}

	.tp-stake-hd {
		padding: 10px 16px;
		background: var(--surface);
		border-bottom: 0.5px solid var(--border);
		display: flex;
		align-items: center;
		justify-content: space-between;
	}

	.tp-stake-hd-lbl {
		font-size: 12px;
		color: var(--muted);
	}

	.tp-boost {
		background: var(--green-soft);
		color: #15803d;
		border: 0.5px solid var(--green-border);
		border-radius: 999px;
		padding: 3px 10px;
		font-size: 11px;
		font-weight: 500;
	}

	.tp-stake-row {
		display: flex;
		justify-content: space-between;
		align-items: center;
		padding: 10px 16px;
		font-size: 13px;
		border-top: 0.5px solid var(--border);
	}

	.tp-stake-row span {
		color: var(--muted);
	}

	.tp-stake-row strong {
		font-weight: 500;
	}

	.tp-stake-total strong {
		color: var(--accent);
		font-size: 14px;
	}

	.tp-stake-win strong {
		color: #15803d;
		font-size: 14px;
	}

	/* Info box */
	.tp-info {
		background: var(--surface);
		border-left: 2px solid var(--accent);
		border-radius: 0 var(--r-md) var(--r-md) 0;
		padding: 12px 16px;
		margin-bottom: 16px;
		font-size: 13px;
		color: var(--muted);
		line-height: 1.6;
	}

	.tp-info strong {
		color: var(--ink);
		font-weight: 500;
	}

	/* Submit */
	.tp-submit-row {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 14px;
		padding: 18px 20px;
		border-radius: var(--r-md);
		background:
			radial-gradient(circle at top left, rgba(99, 102, 241, 0.16), transparent 38%),
			linear-gradient(135deg, #eff6ff 0%, #eef2ff 55%, #f8fafc 100%);
		border: 1px solid rgba(99, 102, 241, 0.18);
		box-shadow: 0 16px 34px rgba(99, 102, 241, 0.10);
		flex-wrap: wrap;
	}

	.tp-submit-row.market-closed {
		background:
			radial-gradient(circle at top right, rgba(239, 68, 68, 0.15), transparent 36%),
			linear-gradient(135deg, #fff7ed 0%, #fef2f2 58%, #fff1f2 100%);
		border-color: rgba(239, 68, 68, 0.22);
		box-shadow: 0 16px 34px rgba(239, 68, 68, 0.10);
	}

	.tp-submit-info strong {
		font-size: 18px;
		font-weight: 700;
		display: block;
		margin-bottom: 6px;
		color: var(--ink);
	}

	.tp-submit-info p {
		font-size: 13px;
		color: #475569;
		line-height: 1.6;
		max-width: 540px;
	}

	.tp-submit-btn {
		padding: 14px 26px;
		border: none;
		border-radius: 14px;
		background: linear-gradient(135deg, #2563eb 0%, #4f46e5 52%, #7c3aed 100%);
		color: #fff;
		font-family: var(--f);
		font-size: 14px;
		font-weight: 700;
		letter-spacing: 0.01em;
		cursor: pointer;
		transition: all .15s;
		flex-shrink: 0;
		min-width: 196px;
		box-shadow: 0 16px 34px rgba(79, 70, 229, 0.28);
	}

	.tp-submit-btn:hover {
		transform: translateY(-2px);
		box-shadow: 0 18px 36px rgba(79, 70, 229, 0.34);
	}

	.tp-submit-btn:active {
		transform: scale(.98);
	}

	.tp-submit-btn:disabled {
		background: linear-gradient(135deg, #f97316 0%, #ef4444 100%);
		color: #fff;
		border: 1px solid rgba(255, 255, 255, 0.22);
		cursor: not-allowed;
		transform: none;
		box-shadow: 0 14px 28px rgba(239, 68, 68, 0.20);
		opacity: 0.92;
	}

	.tp-lock-note {
		padding: 12px 16px;
		border-radius: var(--r-md);
		border: 0.5px dashed var(--border-2);
		font-size: 13px;
		color: var(--muted);
		line-height: 1.5;
		margin-top: 14px;
	}

	.tp-sell-box {
		margin-top: 14px;
		padding: 16px;
		border-radius: var(--r-md);
		background: rgba(37, 99, 235, 0.07);
		border: 1px solid rgba(37, 99, 235, 0.18);
		display: grid;
		gap: 12px;
	}

	.tp-sell-head {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 12px;
		flex-wrap: wrap;
	}

	.tp-sell-title {
		font-size: 15px;
		font-weight: 700;
		color: var(--ink);
	}

	.tp-sell-chip {
		display: inline-flex;
		align-items: center;
		padding: 6px 10px;
		border-radius: 999px;
		font-size: 11px;
		font-weight: 700;
		background: rgba(34, 197, 94, 0.12);
		color: #15803d;
	}

	.tp-sell-grid {
		display: grid;
		grid-template-columns: repeat(3, minmax(0, 1fr));
		gap: 10px;
	}

	.tp-sell-item {
		padding: 12px;
		border-radius: 12px;
		background: var(--white);
		border: 1px solid var(--border);
	}

	.tp-sell-item span {
		display: block;
		font-size: 11px;
		font-weight: 700;
		text-transform: uppercase;
		letter-spacing: .08em;
		color: var(--hint);
		margin-bottom: 6px;
	}

	.tp-sell-item strong {
		font-size: 18px;
		font-weight: 700;
		color: var(--ink);
	}

	.tp-sell-note {
		font-size: 13px;
		color: var(--muted);
		line-height: 1.6;
	}

	.tp-sell-actions {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 12px;
		flex-wrap: wrap;
	}

	.tp-sell-btn {
		border: none;
		border-radius: 12px;
		padding: 12px 18px;
		background: #2563eb;
		color: #fff;
		font-size: 13px;
		font-weight: 700;
		cursor: pointer;
	}

	.tp-sell-muted {
		font-size: 12px;
		color: var(--hint);
	}

	.tp-inline-note {
		display: inline-flex;
		align-items: flex-start;
		gap: 10px;
		padding: 14px 16px;
		border-radius: 16px;
		border: 1px solid rgba(239, 68, 68, 0.18);
		background: rgba(255, 255, 255, 0.72);
		backdrop-filter: blur(6px);
		color: #991b1b;
		flex: 1 1 280px;
	}

	.tp-inline-note i {
		margin-top: 2px;
	}

	.tp-inline-note strong {
		display: block;
		font-size: 14px;
		color: #7f1d1d;
		margin-bottom: 4px;
	}

	.tp-inline-note span {
		display: block;
		font-size: 12px;
		line-height: 1.5;
	}

	/* ─── CHART ─── */
	.tp-chart-wrap {
		background: var(--surface);
		border: 0.5px solid var(--border);
		border-radius: var(--r-md);
		padding: 14px;
	}

	.tp-chart-hdr {
		display: flex;
		justify-content: space-between;
		align-items: flex-start;
		margin-bottom: 12px;
		flex-wrap: wrap;
		gap: 8px;
	}

	.tp-chart-title {
		font-size: 13px;
		font-weight: 500;
	}

	.tp-chart-sub {
		font-size: 11px;
		color: var(--muted);
		margin-top: 2px;
	}

	.tp-chart-legend {
		display: flex;
		gap: 6px;
	}

	.tp-leg {
		display: inline-flex;
		align-items: center;
		gap: 5px;
		padding: 3px 10px;
		border-radius: 999px;
		font-size: 11px;
		border: 0.5px solid var(--border-2);
		color: var(--muted);
		background: var(--white);
	}

	.tp-leg-dot {
		width: 6px;
		height: 6px;
		border-radius: 50%;
	}

	.tp-metrics {
		display: grid;
		grid-template-columns: repeat(4, 1fr);
		gap: 6px;
		margin-bottom: 12px;
	}

	.tp-metric {
		background: var(--white);
		border: 0.5px solid var(--border);
		border-radius: var(--r-sm);
		padding: 10px 12px;
	}

	.tp-metric-lbl {
		font-size: 10px;
		color: var(--muted);
		text-transform: uppercase;
		letter-spacing: .06em;
		margin-bottom: 3px;
	}

	.tp-metric-val {
		font-size: 16px;
		font-weight: 500;
	}

	.tp-metric-sub {
		font-size: 10px;
		color: var(--hint);
		margin-top: 2px;
	}

	.m-yes {
		color: #15803d;
	}

	.m-no {
		color: #b91c1c;
	}

	.m-acc {
		color: var(--accent);
	}

	.tp-chart-svg {
		background: var(--white);
		border: 0.5px solid var(--border);
		border-radius: var(--r-sm);
		overflow: hidden;
		padding: 8px;
	}

	.tp-spread-row {
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-top: 10px;
		font-size: 12px;
		color: var(--muted);
	}

	.tp-spread-val {
		font-weight: 500;
		color: var(--ink);
		font-size: 12px;
	}

	/* ─── SIDEBAR ─── */
	.tp-side {
		display: flex;
		flex-direction: column;
		gap: 12px;
	}

	.tp-prog-grid {
		display: grid;
		grid-template-columns: repeat(3, 1fr);
		gap: 8px;
	}

	.tp-prog-stat {
		background: var(--surface);
		border: 0.5px solid var(--border);
		border-radius: var(--r-md);
		padding: 12px;
		text-align: center;
	}

	.tp-prog-lbl {
		font-size: 10px;
		color: var(--muted);
		text-transform: uppercase;
		letter-spacing: .06em;
		margin-bottom: 4px;
	}

	.tp-prog-val {
		font-size: 22px;
		font-weight: 500;
	}

	.ps-total .tp-prog-val {
		color: var(--accent);
	}

	.ps-correct .tp-prog-val {
		color: #15803d;
	}

	.ps-wrong .tp-prog-val {
		color: #b91c1c;
	}

	/* Question list */
	.tp-qlist {
		display: flex;
		flex-direction: column;
		gap: 6px;
	}

	.tp-qitem {
		display: block;
		padding: 11px 14px;
		border-radius: var(--r-md);
		border: 0.5px solid var(--border);
		background: var(--white);
		text-decoration: none;
		transition: all .15s;
	}

	.tp-qitem:hover {
		border-color: var(--border-2);
		background: var(--surface);
	}

	.tp-qitem.q-active {
		border-color: var(--accent);
		border-width: 1.5px;
		background: var(--accent-soft);
	}

	.tp-qitem.q-correct {
		border-color: var(--green-border);
		background: var(--green-soft);
	}

	.tp-qitem.q-wrong {
		border-color: var(--red-border);
		background: var(--red-soft);
	}

	.tp-qitem-text {
		font-size: 13px;
		color: var(--ink);
		line-height: 1.4;
		margin-bottom: 6px;
		display: -webkit-box;
		-webkit-line-clamp: 2;
		-webkit-box-orient: vertical;
		overflow: hidden;
	}

	.tp-qitem-foot {
		display: flex;
		gap: 6px;
	}

	.tp-qbadge {
		display: inline-flex;
		align-items: center;
		gap: 4px;
		padding: 2px 8px;
		border-radius: 6px;
		font-size: 10px;
		font-weight: 500;
	}

	.tp-qbadge-dot {
		width: 5px;
		height: 5px;
		border-radius: 50%;
		background: currentColor;
	}

	.bq-open {
		background: var(--green-soft);
		color: #15803d;
		border: 0.5px solid var(--green-border);
	}

	.bq-not-open {
		background: var(--surface-2);
		color: var(--muted);
		border: 0.5px solid var(--border-2);
	}

	.bq-review {
		background: var(--accent-soft);
		color: var(--accent);
		border: 0.5px solid var(--accent-border);
	}

	/* ─── RESPONSIVE ─── */
	@media (max-width: 960px) {
		.tp-layout {
			grid-template-columns: 1fr;
		}

		.tp-metrics {
			grid-template-columns: repeat(2, 1fr);
		}
	}

	@media (max-width: 600px) {
		.tp-hero {
			flex-direction: column;
			padding: 18px;
		}

		.tp-hero-pills {
			flex-direction: row;
			flex-wrap: wrap;
			align-items: flex-start;
		}

		.tp-controls {
			grid-template-columns: 1fr;
		}

		.tp-mchips {
			grid-template-columns: 1fr;
		}

		.tp-mchips .tp-chip {
			display: flex;
			justify-content: space-between;
			align-items: center;
			text-align: left;
		}

		.tp-card-body {
			padding: 16px;
		}

		.tp-card-head {
			padding: 13px 16px;
		}

		.tp-submit-btn {
			width: 100%;
		}
	}
</style>

<div class="tp">

	<?php if ($answer_error_flash): ?>
		<div class="tp-alert tp-alert-err">
			<i class="fa-solid fa-circle-xmark"></i>
			<?php echo $answer_error_flash; ?>
		</div>
	<?php endif; ?>
	<?php if ($this->session->flashdata('success')): ?>
		<div class="tp-alert tp-alert-ok">
			<i class="fa-solid fa-circle-check"></i>
			<?php echo $this->session->flashdata('success'); ?>
		</div>
	<?php endif; ?>

	<!-- Back -->
	<div>
		<a class="tp-back" href="<?php echo site_url('questions?category_id=' . (int)$selected_category->id); ?>">
			<svg width="14" height="14" viewBox="0 0 14 14" fill="none">
				<path d="M9 11L5 7l4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
			</svg>
			Back to <?php echo html_escape($selected_category->name); ?>
		</a>
	</div>

	<!-- Hero -->
	<div class="tp-hero">
		<div>
			<div class="tp-hero-q"><?php echo html_escape($selected_question->question); ?></div>
			<?php
			$start_ts = (!empty($selected_question->start_time) && $selected_question->start_time !== '0000-00-00 00:00:00') ? strtotime($selected_question->start_time) : false;
			$end_ts   = (!empty($selected_question->end_time) && $selected_question->end_time !== '0000-00-00 00:00:00') ? strtotime($selected_question->end_time) : false;
			$now      = time();
			?>

			<p class="tp-hero-sub">
				<?php if ($start_ts && $now < $start_ts): ?>
					⏳ Market starts at <strong><?php echo date('d M Y, h:i A', $start_ts); ?></strong>
				<?php elseif ($end_ts && $now <= $end_ts): ?>
					🔴 Market closes at <strong><?php echo date('d M Y, h:i A', $end_ts); ?></strong>
				<?php else: ?>
					⚠️ Market is closed
				<?php endif; ?>
			</p>
		</div>
		<div class="tp-hero-pills">
			<span class="tp-pill tp-pill-users">
				<svg width="12" height="12" viewBox="0 0 16 16" fill="currentColor">
					<path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM2 14s-1 0-1-1 1-4 7-4 7 3 7 4-1 1-1 1H2Z" />
				</svg>
				<?php echo number_format($joined_users); ?> joined
			</span>
			<span class="tp-pill <?php echo $market_is_open ? 'tp-pill-open' : 'tp-pill-closed'; ?>">
				<?php if ($market_is_open): ?><span class="tp-pulse"></span><?php endif; ?>
				<?php echo $market_is_open ? 'Market open' : 'Market closed'; ?>
			</span>
			<span class="tp-pill tp-pill-cat"><?php echo html_escape($selected_category->name); ?></span>
		</div>
	</div>

	<!-- Layout -->
	<div class="tp-layout">

		<!-- LEFT -->
		<div>

			<!-- Market Overview -->
			<div class="tp-card">
				<div class="tp-card-head">
					<div class="tp-card-title">Market overview</div>
					<div class="tp-card-sub">Current YES / NO prices and trade volume</div>
				</div>
				<div class="tp-card-body">
					<div class="tp-bar-labels">
						<span class="tp-yes-lbl">YES — <?php echo $yes_pct; ?>%</span>
						<span class="tp-no-lbl"><?php echo $no_pct; ?>% — NO</span>
					</div>
					<div class="tp-prob-bar">
						<div class="tp-bar-y" style="width:<?php echo $yes_pct; ?>%"></div>
						<div class="tp-bar-n" style="width:<?php echo $no_pct; ?>%"></div>
					</div>
					<div class="tp-mchips">
						<div class="tp-chip">
							<div class="tp-chip-lbl">Joined</div>
							<div class="tp-chip-val"><?php echo number_format($joined_users); ?></div>
						</div>
						<div class="tp-chip mc-yes">
							<div class="tp-chip-lbl">YES price</div>
							<div class="tp-chip-val">₹<?php echo number_format($yes_price, 2); ?></div>
						</div>
						<div class="tp-chip mc-no">
							<div class="tp-chip-lbl">NO price</div>
							<div class="tp-chip-val">₹<?php echo number_format($no_price, 2); ?></div>
						</div>
					</div>
					<div class="tp-vol-row">
						<span class="tv-yes"><?php echo $yes_trade_qty; ?> YES</span>
						<div class="tp-vol-bar">
							<div class="tp-vol-fill" style="width:<?php echo $yes_vol_pct; ?>%"></div>
						</div>
						<span class="tv-no"><?php echo $no_trade_qty; ?> NO</span>
					</div>
				</div>
			</div>

			<!-- Trade Panel -->
			<div class="tp-card">
				<div class="tp-card-head">
					<div class="tp-card-title"><?php echo $is_locked ? 'Your trade (locked)' : 'Place trade'; ?></div>
					<div class="tp-card-sub"><?php echo $is_locked ? 'Cannot be modified.' : 'Choose a side and confirm your stake.'; ?></div>
				</div>
				<div class="tp-card-body">

					<?php if ($is_locked): ?>
						<div class="tp-result <?php echo $is_settled ? ($is_correct_ans ? 'tp-result-correct' : 'tp-result-wrong') : 'tp-result-pend'; ?>">
							<div>
								<div class="tp-result-tag">
									<?php echo $is_settled ? ($is_correct_ans ? '✓ Correct answer' : '✗ Wrong answer') : '⏳ Awaiting result'; ?>
								</div>
								<h4>You answered <strong><?php echo strtoupper($selected_answer); ?></strong></h4>
								<p>
									<?php if ($is_settled):
										echo $is_correct_ans
											? 'Your answer matched the result. Winnings credited to your wallet.'
											: 'Your answer did not match the result. No payout was made.';
									else:
										echo 'Stake deducted. Payout will be credited once the admin resolves the market.';
									endif; ?>
								</p>
							</div>
							<div class="tp-result-amt">
								<?php if ($is_settled):
									echo $is_correct_ans ? '+₹' . number_format((float)$selected_answer_state->payout_amount, 2) : '₹0.00';
								else:
									echo '₹' . number_format((float)$selected_answer_state->stake_amount, 2);
								endif; ?>
							</div>
						</div>
					<?php endif; ?>

					<?php if ($is_locked && !$is_sold_trade && !empty($sell_trade_summary) && !empty($sell_trade_summary['can_sell'])): ?>
						<div class="tp-sell-box">
							<div class="tp-sell-head">
								<div class="tp-sell-title">Sell trade and book profit</div>
								<span class="tp-sell-chip">Sell Available</span>
							</div>
							<div class="tp-sell-grid">
								<div class="tp-sell-item">
									<span>Booked Return</span>
									<strong>Rs <?php echo number_format((float) ($sell_trade_summary['entry_amount'] ?? 0), 2); ?></strong>
								</div>
								<div class="tp-sell-item">
									<span>Current Return</span>
									<strong>Rs <?php echo number_format((float) ($sell_trade_summary['exit_amount'] ?? 0), 2); ?></strong>
								</div>
								<div class="tp-sell-item">
									<span>Net Profit</span>
									<strong>Rs <?php echo number_format((float) ($sell_trade_summary['net_profit'] ?? 0), 2); ?></strong>
								</div>
							</div>
							<div class="tp-sell-note"><?php echo html_escape((string) ($sell_trade_summary['message'] ?? '')); ?></div>
							<div class="tp-sell-actions">
								<div class="tp-sell-muted">
									Current price Rs <?php echo number_format((float) ($sell_trade_summary['current_price'] ?? 0), 2); ?>
									x <?php echo number_format((float) ($sell_trade_summary['current_multiplier'] ?? 0), 2); ?>
								</div>
								<form method="post" action="<?php echo site_url('questions/sell-trade'); ?>" class="js-sell-trade-form">
									<input type="hidden" name="question_id" value="<?php echo (int) $selected_question->id; ?>">
									<button type="submit" class="tp-sell-btn">Sell Trade Now</button>
								</form>
							</div>
						</div>
					<?php endif; ?>

					<?php echo form_open('questions/save_answers'); ?>
					<input type="hidden" name="category_id" value="<?php echo (int)$selected_category->id; ?>">
					<input type="hidden" name="question_id" value="<?php echo (int)$selected_question->id; ?>">
					<input type="hidden" name="price" class="js-ph" value="<?php echo number_format($default_price, 2, '.', ''); ?>">

					<!-- YES / NO Toggle -->
					<div class="tp-toggle<?php echo ($is_locked || !$market_is_open) ? ' locked' : ''; ?>">
						<div>
							<input type="radio" id="tp_yes" name="answer" value="yes"
								<?php echo $selected_answer === 'yes' ? 'checked' : ''; ?>
								<?php echo ($is_locked || !$market_is_open) ? 'disabled' : ''; ?>>
							<label for="tp_yes" class="tog-yes">
								<div class="tp-tog-l">
									<span class="tp-tog-dot"></span>
									<div>
										<div class="tp-tog-name">YES</div>
										<div class="tp-tog-price">₹<?php echo number_format($yes_price, 2); ?> per share</div>
									</div>
								</div>
								<span class="tp-tog-badge">YES</span>
							</label>
						</div>
						<div>
							<input type="radio" id="tp_no" name="answer" value="no"
								<?php echo $selected_answer === 'no' ? 'checked' : ''; ?>
								<?php echo ($is_locked || !$market_is_open) ? 'disabled' : ''; ?>>
							<label for="tp_no" class="tog-no">
								<div class="tp-tog-l">
									<span class="tp-tog-dot"></span>
									<div>
										<div class="tp-tog-name">NO</div>
										<div class="tp-tog-price">₹<?php echo number_format($no_price, 2); ?> per share</div>
									</div>
								</div>
								<span class="tp-tog-badge">NO</span>
							</label>
						</div>
					</div>

					<!-- Price & Quantity Controls -->
					<div class="tp-controls">
						<div class="tp-ctrl">
							<div class="tp-ctrl-top">
								<span class="tp-ctrl-lbl">Price / share</span>
								<strong class="tp-ctrl-val js-pdisplay">₹<?php echo number_format($default_price, 2); ?></strong>
							</div>
							<div class="tp-stepper">
								<button type="button" class="tp-step-btn js-pdec" <?php echo ($is_locked || !$market_is_open) ? 'disabled' : ''; ?>>−</button>
								<input type="range" class="js-prange"
									min="0.50"
									max="<?php echo number_format($price_max, 2, '.', ''); ?>"
									step="0.50"
									value="<?php echo number_format(min($default_price, $price_max), 2, '.', ''); ?>"
									<?php echo ($is_locked || !$market_is_open) ? 'disabled' : ''; ?>>
								<button type="button" class="tp-step-btn js-pinc" <?php echo ($is_locked || !$market_is_open) ? 'disabled' : ''; ?>>+</button>
							</div>
							<div class="tp-ctrl-hint">₹0.50 – ₹<?php echo number_format($price_max, 2); ?></div>
						</div>
						<div class="tp-ctrl">
							<div class="tp-ctrl-top">
								<span class="tp-ctrl-lbl">Quantity</span>
								<strong class="tp-ctrl-val js-qdisplay"><?php echo (int)$default_quantity; ?></strong>
							</div>
							<div class="tp-stepper">
								<button type="button" class="tp-step-btn js-qdec" <?php echo ($is_locked || !$market_is_open) ? 'disabled' : ''; ?>>−</button>
								<input type="number" class="js-qinput" name="quantity"
									min="<?php echo $QTY_MIN; ?>"
									max="<?php echo $QTY_MAX; ?>"
									step="1"
									value="<?php echo (int)$default_quantity; ?>"
									<?php echo ($is_locked || !$market_is_open) ? 'readonly' : ''; ?>>
								<button type="button" class="tp-step-btn js-qinc" <?php echo ($is_locked || !$market_is_open) ? 'disabled' : ''; ?>>+</button>
							</div>
							<div class="tp-ctrl-hint">Min <?php echo $QTY_MIN; ?> · Max <?php echo number_format($QTY_MAX); ?></div>
							<div class="tp-qty-warn" id="js-qwarn">Quantity cannot exceed <?php echo $QTY_MAX; ?></div>
						</div>
					</div>

					<!-- Stake Summary -->
					<div class="tp-stake">
						<div class="tp-stake-hd">
							<span class="tp-stake-hd-lbl">Trade summary</span>
							<span class="tp-boost">×<?php echo number_format($multiplier, 2); ?> boost active</span>
						</div>
						<div class="tp-stake-row"><span>Price × Quantity</span><strong class="js-formula">—</strong></div>
						<div class="tp-stake-row tp-stake-total"><span>Total stake</span><strong class="js-stake">—</strong></div>
						<div class="tp-stake-row tp-stake-win"><span>Winning preview (×<?php echo number_format($multiplier, 2); ?>)</span><strong class="js-win">—</strong></div>
					</div>

					<!-- How it works -->
					<div class="tp-info">
						If your answer matches the result, <strong class="js-win-inline">—</strong> will be credited.
						Formula: Price × Quantity × <?php echo number_format($multiplier, 2); ?>. Wrong answer = ₹0.00.
					</div>

					<?php if ($is_locked): ?>
						<div class="tp-lock-note">
							<i class="fa-solid fa-lock" style="margin-right:6px;"></i>
							This trade is locked. Head back to the category to open another question.
						</div>
					<?php else: ?>
						<div class="tp-submit-row<?php echo !$market_is_open ? ' market-closed' : ''; ?>">
							<div class="tp-submit-info">
								<strong>Ready to submit?</strong>
								<p><?php echo $market_is_open ? 'This action is final and cannot be undone.' : 'Trading will become available once this market opens again.'; ?></p>
							</div>
							<?php if (!$market_is_open): ?>
								<div class="tp-inline-note">
									<i class="fa-solid fa-ban"></i>
									<div>
										<strong>Market closed right now</strong>
										<span>Trading is not allowed right now. Please wait for market to open.</span>
									</div>
								</div>
							<?php endif; ?>
							<button type="submit" class="tp-submit-btn" id="js-sbtn"
								<?php echo !$market_is_open ? 'disabled' : ''; ?>>
								<?php echo $market_is_open ? 'Place trade →' : 'Market Closed'; ?>
							</button>
						</div>
					<?php endif; ?>

					<?php echo form_close(); ?>
				</div>
			</div>

			<!-- Price History Chart -->
			<div class="tp-card" style="margin-top:12px">
				<div class="tp-card-head">
					<div class="tp-card-title">Price history</div>
					<div class="tp-card-sub">YES and NO price movement from market snapshots</div>
				</div>
				<div class="tp-card-body">
					<div class="tp-chart-wrap" data-chart data-history='<?php echo json_encode($price_history); ?>'>
						<div class="tp-chart-hdr">
							<div>
								<div class="tp-chart-title">Demand chart · <?php echo html_escape($selected_category->name); ?></div>
								<div class="tp-chart-sub">Auto-scaled from latest price snapshots</div>
							</div>
							<div class="tp-chart-legend">
								<span class="tp-leg"><span class="tp-leg-dot" style="background:var(--green)"></span>YES</span>
								<span class="tp-leg"><span class="tp-leg-dot" style="background:var(--red)"></span>NO</span>
							</div>
						</div>
						<div class="tp-metrics">
							<div class="tp-metric">
								<div class="tp-metric-lbl">YES price</div>
								<div class="tp-metric-val m-yes">₹<?php echo number_format($yes_price, 2); ?></div>
								<div class="tp-metric-sub"><?php echo $yes_trade_qty; ?> trades</div>
							</div>
							<div class="tp-metric">
								<div class="tp-metric-lbl">NO price</div>
								<div class="tp-metric-val m-no">₹<?php echo number_format($no_price, 2); ?></div>
								<div class="tp-metric-sub"><?php echo $no_trade_qty; ?> trades</div>
							</div>
							<div class="tp-metric">
								<div class="tp-metric-lbl">Market total</div>
								<div class="tp-metric-val">₹<?php echo number_format($market_total, 2); ?></div>
								<div class="tp-metric-sub"><?php echo ucfirst((string)$selected_question->status); ?></div>
							</div>
							<div class="tp-metric">
								<div class="tp-metric-lbl">Positions</div>
								<div class="tp-metric-val m-acc"><?php echo $total_trade_qty; ?></div>
								<div class="tp-metric-sub">Total opened</div>
							</div>
						</div>
						<div class="tp-chart-svg">
							<div id="js-chart"></div>
						</div>
						<div class="tp-spread-row">
							<span>Current spread</span>
							<span class="tp-spread-val">₹<?php echo number_format(abs($yes_price - $no_price), 2); ?></span>
						</div>
					</div>
				</div>
			</div>

		</div><!-- /LEFT -->

		<!-- SIDEBAR -->
		<aside class="tp-side">

			<!-- Progress -->
			<div class="tp-card">
				<div class="tp-card-head">
					<div class="tp-card-title"><?php echo html_escape($selected_category->name); ?></div>
					<div class="tp-card-sub">Your answers in this category</div>
				</div>
				<div class="tp-card-body">
					<div class="tp-prog-grid">
						<div class="tp-prog-stat ps-total">
							<div class="tp-prog-lbl">Done</div>
							<div class="tp-prog-val"><?php echo (int)$answered_count; ?></div>
						</div>
						<div class="tp-prog-stat ps-correct">
							<div class="tp-prog-lbl">Right</div>
							<div class="tp-prog-val"><?php echo (int)$correct_count; ?></div>
						</div>
						<div class="tp-prog-stat ps-wrong">
							<div class="tp-prog-lbl">Wrong</div>
							<div class="tp-prog-val"><?php echo (int)$wrong_count; ?></div>
						</div>
					</div>
				</div>
			</div>

			<!-- Question List -->
			<div class="tp-card">
				<div class="tp-card-head">
					<div class="tp-card-title">All questions</div>
					<div class="tp-card-sub">Jump to any question in this category</div>
				</div>
				<div class="tp-card-body">
					<div class="tp-qlist">
						<?php foreach ($selected_category->questions as $qi):
							$la = isset($user_answers[(int)$qi->id]) ? $user_answers[(int)$qi->id] : NULL;
							$ia = (int)$qi->id === (int)$selected_question->id;
							$qs = '';
							$question_status = strtolower(trim((string)(isset($qi->status) ? $qi->status : '')));
							$start_ts = (!empty($qi->start_time) && $qi->start_time !== '0000-00-00 00:00:00') ? strtotime($qi->start_time) : FALSE;
							$end_ts = (!empty($qi->end_time) && $qi->end_time !== '0000-00-00 00:00:00') ? strtotime($qi->end_time) : FALSE;
							$is_trade_open = $question_status === 'open'
								&& ($start_ts === FALSE || time() >= $start_ts)
								&& ($end_ts === FALSE || time() <= $end_ts);
							if ($la && !empty($la->settled_at)) $qs = strtolower((string)$la->answer) === strtolower((string)$qi->answer_key) ? 'correct' : 'wrong';
							$cls = $ia ? 'q-active' : ($qs ? 'q-' . $qs : '');
						?>
							<a class="tp-qitem <?php echo $cls; ?>" href="<?php echo site_url('questions/answer/' . (int)$qi->id); ?>">
								<div class="tp-qitem-text"><?php echo html_escape($qi->question); ?></div>
								<div class="tp-qitem-foot">
									<?php if ($la): ?>
										<span class="tp-qbadge bq-review">
											Review question
										</span>
									<?php elseif ($is_trade_open): ?>
										<span class="tp-qbadge bq-open"><span class="tp-qbadge-dot"></span>Open</span>
									<?php else: ?>
										<span class="tp-qbadge bq-not-open">Not open</span>
									<?php endif; ?>
								</div>
							</a>
						<?php endforeach; ?>
					</div>
				</div>
			</div>

		</aside>

	</div>
</div>

<script>
	(function() {
		'use strict';

		var QMAX = <?php echo $QTY_MAX; ?>,
			QMIN = <?php echo $QTY_MIN; ?>;
		var PMIN = 0.50,
			PMAX = <?php echo number_format($price_max, 4, '.', ''); ?>;
		var DYES = <?php echo number_format($yes_price, 4, '.', ''); ?>,
			DNO = <?php echo number_format($no_price, 4, '.', ''); ?>;
		var MULT = <?php echo number_format($multiplier, 4, '.', ''); ?>;

		var yR = document.getElementById('tp_yes'),
			nR = document.getElementById('tp_no');
		var sellTradeForm = document.querySelector('.js-sell-trade-form');
		var heroSub = document.querySelector('.tp-hero-sub');
		var pR = document.querySelector('.js-prange'),
			pH = document.querySelector('.js-ph');
		var pD = document.querySelector('.js-pdisplay'),
			qI = document.querySelector('.js-qinput');
		var qD = document.querySelector('.js-qdisplay'),
			qW = document.getElementById('js-qwarn');
		var fEl = document.querySelector('.js-formula'),
			sEl = document.querySelector('.js-stake');
		var wEl = document.querySelector('.js-win'),
			wIn = document.querySelector('.js-win-inline');
		var sb = document.getElementById('js-sbtn');
		var pDec = document.querySelector('.js-pdec'),
			pInc = document.querySelector('.js-pinc');
		var qDec = document.querySelector('.js-qdec'),
			qInc = document.querySelector('.js-qinc');

		if (heroSub) {
			heroSub.innerHTML = <?php echo json_encode(
									($start_ts && $now < $start_ts)
										? '<span class="tp-hero-sub-icon pending"><i class="fa-solid fa-clock"></i></span><span>Market starts at <strong>' . date('d M Y, h:i A', $start_ts) . '</strong></span>'
										: (($end_ts && $now <= $end_ts)
											? '<span class="tp-hero-sub-icon open"><i class="fa-solid fa-bolt"></i></span><span>Market closes at <strong>' . date('d M Y, h:i A', $end_ts) . '</strong></span>'
											: '<span class="tp-hero-sub-icon closed"><i class="fa-solid fa-lock"></i></span><span>Market is closed</span>')
								); ?>;
		}

		if (sb) {
			sb.textContent = sb.disabled ? 'Market Closed' : 'Place Trade Now';
		}

		if (sellTradeForm) {
			sellTradeForm.addEventListener('submit', function(event) {
				event.preventDefault();

				if (typeof userSwalConfirm === 'function') {
					userSwalConfirm('Sell this trade and credit the current return to your wallet?', {
						icon: 'question',
						confirmButtonText: 'Sell Trade',
						cancelButtonText: 'Cancel'
					}).then(function(confirmed) {
						if (confirmed) {
							sellTradeForm.submit();
						}
					});
					return;
				}

				if (window.confirm('Sell this trade and credit the current return to your wallet?')) {
					sellTradeForm.submit();
				}
			});
		}

		function fmt(v) {
			return '₹' + Number(v).toFixed(2);
		}

		function clamp(v, lo, hi) {
			return Math.min(Math.max(v, lo), hi);
		}

		function gP() {
			return pR ? parseFloat(pR.value) || PMIN : PMIN;
		}

		function gQ() {
			return qI ? parseInt(qI.value) || QMIN : QMIN;
		}

		function update() {
			var p = clamp(gP(), PMIN, PMAX),
				q = gQ(),
				over = q > QMAX;
			if (over) {
				q = QMAX;
				if (qI) qI.value = QMAX;
			}
			if (qW) qW.classList.toggle('show', over);
			q = clamp(q, QMIN, QMAX);
			var stake = p * q,
				win = stake * MULT;
			if (pH) pH.value = p.toFixed(2);
			if (pD) pD.textContent = fmt(p);
			if (qD) qD.textContent = q;
			if (fEl) fEl.textContent = fmt(p) + ' × ' + q;
			if (sEl) sEl.textContent = fmt(stake);
			if (wEl) wEl.textContent = fmt(win);
			if (wIn) wIn.textContent = fmt(win);
			if (sb) sb.disabled = !(p >= PMIN && p <= PMAX && q >= QMIN && q <= QMAX);
		}

		function syncP() {
			if (!pR) return;
			var base = (nR && nR.checked) ? DNO : DYES;
			pR.value = clamp(base > 0 ? base : PMIN, PMIN, PMAX).toFixed(2);
			update();
		}

		if (pR && !pR.disabled) {
			pR.addEventListener('input', update);
			if (yR) yR.addEventListener('change', syncP);
			if (nR) nR.addEventListener('change', syncP);
			if (pDec) pDec.addEventListener('click', function() {
				pR.value = clamp(parseFloat(pR.value) - 0.50, PMIN, PMAX).toFixed(2);
				update();
			});
			if (pInc) pInc.addEventListener('click', function() {
				pR.value = clamp(parseFloat(pR.value) + 0.50, PMIN, PMAX).toFixed(2);
				update();
			});
		}

		if (qI && !qI.readOnly) {
			qI.addEventListener('input', function() {
				var v = parseInt(this.value) || 0;
				if (v > QMAX) this.value = QMAX;
				if (v < 0) this.value = 0;
				update();
			});
			qI.addEventListener('blur', function() {
				this.value = clamp(parseInt(this.value) || QMIN, QMIN, QMAX);
				update();
			});
			if (qDec) qDec.addEventListener('click', function() {
				qI.value = clamp((parseInt(qI.value) || 1) - 1, QMIN, QMAX);
				update();
			});
			if (qInc) qInc.addEventListener('click', function() {
				qI.value = clamp((parseInt(qI.value) || 0) + 1, QMIN, QMAX);
				update();
			});
			var fm = qI.closest('form');
			if (fm) fm.addEventListener('submit', function(e) {
				var q = parseInt(qI.value),
					p = parseFloat(pR ? pR.value : 0);
				qI.value = clamp(isNaN(q) ? QMIN : q, QMIN, QMAX);
				if (pH && pR) pH.value = clamp(parseFloat(pR.value) || PMIN, PMIN, PMAX).toFixed(2);
				if (q < QMIN || q > QMAX || p < PMIN || p > PMAX) {
					e.preventDefault();
					if (qW) qW.classList.add('show');
				}
			});
		}

		update();

		/* ── Chart ── */
		(function() {
			var host = document.querySelector('[data-chart]'),
				mount = document.getElementById('js-chart');
			if (!host || !mount) return;
			var pts = [];
			try {
				pts = JSON.parse(host.getAttribute('data-history') || '[]');
			} catch (e) {
				pts = [];
			}
			var vY = pts.map(function(p) {
				return parseFloat(p.yes_price || 0);
			});
			var vN = pts.map(function(p) {
				return parseFloat(p.no_price || 0);
			});
			var all = vY.concat(vN).filter(function(v) {
				return !isNaN(v);
			});
			if (!all.length) {
				mount.innerHTML = '<div style="display:flex;align-items:center;justify-content:center;height:130px;font-size:13px;color:#94a3b8;">No price history available yet.</div>';
				return;
			}
			var W = 520,
				H = 140,
				PX = 16,
				PT = 10,
				PB = 16;
			var mn = Math.min.apply(null, all),
				mx = Math.max.apply(null, all.concat([mn + 0.01]));

			function norm(vals) {
				return vals.map(function(v, i) {
					var x = vals.length === 1 ? W / 2 : PX + (i / (vals.length - 1)) * (W - PX * 2);
					var r = (v - mn) / (mx - mn);
					var y = H - PB - r * (H - PT - PB);
					return x.toFixed(1) + ',' + y.toFixed(1);
				}).join(' ');
			}

			mount.innerHTML =
				'<svg viewBox="0 0 ' + W + ' ' + H + '" preserveAspectRatio="none" style="width:100%;display:block;min-height:' + H + 'px">' +
				'<line x1="' + PX + '" y1="' + (H - PB) + '" x2="' + (W - PX) + '" y2="' + (H - PB) + '" stroke="#e2e8f0" stroke-width="1"/>' +
				'<line x1="' + PX + '" y1="' + Math.round((H - PB + PT) / 2) + '" x2="' + (W - PX) + '" y2="' + Math.round((H - PB + PT) / 2) + '" stroke="#e2e8f0" stroke-width="1" stroke-dasharray="4 4"/>' +
				'<polyline fill="none" stroke="#22c55e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" points="' + norm(vY) + '"/>' +
				'<polyline fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" points="' + norm(vN) + '"/>' +
				'</svg>';
		}());
	}());
</script>