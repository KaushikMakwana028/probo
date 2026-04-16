<?php
// ── DATA PREP ────────────────────────────────────────────────────────────────
$answer_error_flash = $this->session->flashdata('error');
if ($answer_error_flash === 'This market is not open for trading right now.' && !empty($market_is_open)) {
	$answer_error_flash = '';
}
$selected_answer  = $selected_answer_state ? strtolower((string)$selected_answer_state->answer) : 'yes';
$is_locked        = $selected_answer_state !== NULL;
$is_settled       = $is_locked && !empty($selected_answer_state->settled_at);
$is_correct_ans   = $is_settled && $selected_answer === strtolower((string)$selected_question->answer_key);
$yes_price        = (float)$selected_question->yes_price;
$no_price         = (float)$selected_question->no_price;
$market_total     = max(1.0, $yes_price + $no_price);
$default_price    = $selected_answer_state ? (float)$selected_answer_state->price : ($selected_answer === 'no' ? $no_price : $yes_price);
$default_price    = $default_price > 0 ? $default_price : max($yes_price, $no_price, 0.5);
$default_quantity = $selected_answer_state ? max(1, min(1000, (int)$selected_answer_state->quantity)) : 1;
$price_max        = max(0.5, $market_total - 0.5);
$multiplier       = isset($selected_question->multiplier) ? (float)$selected_question->multiplier : 1.25;
$winning_preview  = round($default_price * $default_quantity * $multiplier, 2);
$yes_trade_qty    = isset($trade_breakdown['yes_quantity']) ? (int)$trade_breakdown['yes_quantity'] : 0;
$no_trade_qty     = isset($trade_breakdown['no_quantity'])  ? (int)$trade_breakdown['no_quantity']  : 0;
$total_trade_qty  = $yes_trade_qty + $no_trade_qty;
$joined_users     = isset($total_users) ? (int) $total_users : 0;
$yes_pct          = $market_total > 0 ? round($yes_price / $market_total * 100) : 50;
$no_pct           = 100 - $yes_pct;
$yes_vol_pct      = $total_trade_qty > 0 ? round($yes_trade_qty / $total_trade_qty * 100) : 50;
$no_vol_pct       = 100 - $yes_vol_pct;
$QTY_MAX          = 1000;
$QTY_MIN          = 1;
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet">

<style>
	:root {
		--f-head: 'Syne', sans-serif;
		--f-body: 'DM Sans', sans-serif;
		--ink: #0a0d14;
		--ink-2: #1c2235;
		--text: #4a5568;
		--muted: #8892a4;
		--surface: #f5f7fb;
		--surface-2: #edf0f7;
		--white: #ffffff;
		--accent: #5b5ef4;
		--accent-light: rgba(91, 94, 244, 0.1);
		--green: #00c896;
		--green-soft: rgba(0, 200, 150, 0.1);
		--green-border: rgba(0, 200, 150, 0.3);
		--red: #ff4d6a;
		--red-soft: rgba(255, 77, 106, 0.1);
		--red-border: rgba(255, 77, 106, 0.3);
		--gold: #f5a623;
		--border: rgba(0, 0, 0, 0.07);
		--border-2: rgba(0, 0, 0, 0.11);
		--shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.05);
		--shadow-md: 0 8px 24px rgba(0, 0, 0, 0.08);
		--shadow-lg: 0 20px 48px rgba(0, 0, 0, 0.1);
		--r-sm: 10px;
		--r-md: 16px;
		--r-lg: 20px;
		--r-xl: 26px;
	}

	*,
	*::before,
	*::after {
		box-sizing: border-box;
		margin: 0;
		padding: 0;
	}

	.tp {
		font-family: var(--f-body);
		color: var(--ink);
		display: grid;
		gap: 18px;
	}

	/* ─── ALERTS ─── */
	.tp-alert {
		padding: 14px 18px;
		border-radius: var(--r-md);
		font-size: 14px;
		font-weight: 500;
		display: flex;
		align-items: center;
		gap: 10px;
	}

	.tp-alert-err {
		background: var(--red-soft);
		border: 1px solid var(--red-border);
		color: #cc1f3a;
	}

	.tp-alert-ok {
		background: var(--green-soft);
		border: 1px solid var(--green-border);
		color: #009970;
	}

	/* ─── BACK ─── */
	.tp-back {
		display: inline-flex;
		align-items: center;
		gap: 7px;
		font-size: 13px;
		font-weight: 500;
		color: var(--muted);
		text-decoration: none;
		padding: 8px 14px;
		border-radius: var(--r-sm);
		border: 1px solid var(--border-2);
		background: var(--white);
		transition: all 0.16s;
		align-self: start;
	}

	.tp-back:hover {
		color: var(--accent);
		border-color: var(--accent);
		background: var(--accent-light);
	}

	/* ─── QUESTION HERO ─── */
	.tp-qhero {
		background: var(--white);
		border: 1px solid var(--border);
		border-radius: var(--r-xl);
		padding: 28px 32px;
		display: grid;
		grid-template-columns: 1fr auto;
		gap: 20px;
		align-items: start;
		box-shadow: var(--shadow-sm);
	}

	.tp-qhero-title {
		font-family: var(--f-head);
		font-size: clamp(16px, 2.2vw, 22px);
		font-weight: 700;
		letter-spacing: -0.02em;
		color: var(--ink);
		line-height: 1.35;
		margin-bottom: 8px;
	}

	.tp-qhero-sub {
		font-size: 13px;
		line-height: 1.6;
		color: var(--muted);
	}

	.tp-qhero-meta {
		display: flex;
		flex-direction: column;
		align-items: flex-end;
		gap: 8px;
		flex-shrink: 0;
	}

	.tp-pill {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		padding: 6px 14px;
		border-radius: 999px;
		font-family: var(--f-head);
		font-size: 11px;
		font-weight: 600;
		letter-spacing: 0.06em;
		white-space: nowrap;
	}

	.tp-pill-open {
		background: var(--green-soft);
		color: #009970;
		border: 1px solid var(--green-border);
	}

	.tp-pill-closed {
		background: var(--surface);
		color: var(--muted);
		border: 1px solid var(--border-2);
	}

	.tp-pill-cat {
		background: var(--accent-light);
		color: var(--accent);
		border: 1px solid rgba(91, 94, 244, 0.2);
	}

	.tp-pill-users {
		background: var(--red-soft);
		color: #cc1f3a;
		border: 1px solid var(--red-border);
		font-size: 12px;
		padding: 7px 14px;
	}

	.tp-pulse {
		width: 6px;
		height: 6px;
		border-radius: 50%;
		background: var(--green);
		animation: tpPulse 1.6s ease-in-out infinite;
	}

	@keyframes tpPulse {

		0%,
		100% {
			opacity: 1;
			transform: scale(1);
		}

		50% {
			opacity: 0.4;
			transform: scale(0.65);
		}
	}

	/* ─── LAYOUT ─── */
	.tp-layout {
		display: grid;
		grid-template-columns: minmax(0, 1fr) 300px;
		gap: 18px;
		align-items: start;
	}

	/* ─── PANELS ─── */
	.tp-panel {
		background: var(--white);
		border: 1px solid var(--border);
		border-radius: var(--r-xl);
		overflow: hidden;
		box-shadow: var(--shadow-sm);
	}

	.tp-panel+.tp-panel {
		margin-top: 16px;
	}

	.tp-panel-head {
		padding: 18px 24px;
		border-bottom: 1px solid var(--border);
		background: var(--surface);
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 12px;
	}

	.tp-panel-title {
		font-family: var(--f-head);
		font-size: 15px;
		font-weight: 700;
		color: var(--ink);
		letter-spacing: -0.01em;
	}

	.tp-panel-sub {
		font-size: 12px;
		color: var(--muted);
		margin-top: 2px;
	}

	.tp-panel-body {
		padding: 22px 24px;
	}

	/* ─── MARKET OVERVIEW ─── */
	.tp-prob-bar-wrap {
		margin-bottom: 20px;
	}

	.tp-prob-labels {
		display: flex;
		justify-content: space-between;
		font-family: var(--f-head);
		font-size: 13px;
		font-weight: 700;
		margin-bottom: 8px;
	}

	.tp-yes-label {
		color: var(--green);
	}

	.tp-no-label {
		color: var(--red);
	}

	.tp-prob-bar {
		height: 8px;
		border-radius: 999px;
		background: var(--surface-2);
		overflow: hidden;
		display: flex;
	}

	.tp-prob-y {
		height: 100%;
		background: linear-gradient(90deg, #00c896, #00a87a);
		border-radius: 999px 0 0 999px;
		transition: width 0.5s ease;
	}

	.tp-prob-n {
		height: 100%;
		background: linear-gradient(90deg, #ff6b82, #ff4d6a);
		border-radius: 0 999px 999px 0;
		transition: width 0.5s ease;
	}

	.tp-market-chips {
		display: grid;
		grid-template-columns: repeat(3, 1fr);
		gap: 10px;
		margin-bottom: 16px;
	}

	.tp-mchip {
		padding: 14px 16px;
		border-radius: var(--r-md);
		border: 1px solid var(--border);
		background: var(--surface);
		text-align: center;
	}

	.tp-mchip-label {
		font-family: var(--f-head);
		font-size: 10px;
		font-weight: 600;
		text-transform: uppercase;
		letter-spacing: 0.1em;
		margin-bottom: 5px;
		color: var(--muted);
	}

	.tp-mchip-val {
		font-family: var(--f-head);
		font-size: 19px;
		font-weight: 800;
		letter-spacing: -0.02em;
	}

	.mc-users .tp-mchip-label {
		color: #cc1f3a;
	}

	.mc-users .tp-mchip-val {
		color: #cc1f3a;
	}

	.mc-yes .tp-mchip-label {
		color: #009970;
	}

	.mc-yes .tp-mchip-val {
		color: #009970;
	}

	.mc-no .tp-mchip-label {
		color: var(--red);
	}

	.mc-no .tp-mchip-val {
		color: var(--red);
	}

	.tp-vol-row {
		display: flex;
		align-items: center;
		gap: 10px;
		font-size: 12px;
		font-weight: 600;
		font-family: var(--f-head);
	}

	.tp-vol-bar {
		flex: 1;
		height: 5px;
		border-radius: 999px;
		background: var(--surface-2);
		overflow: hidden;
	}

	.tp-vol-fill {
		height: 100%;
		background: linear-gradient(90deg, var(--green), var(--red));
		border-radius: 999px;
	}

	.tv-yes {
		color: #009970;
	}

	.tv-no {
		color: var(--red);
	}

	/* ─── TRADE SECTION ─── */

	/* Result Banner */
	.tp-result {
		border-radius: var(--r-lg);
		padding: 20px 22px;
		margin-bottom: 20px;
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 16px;
		flex-wrap: wrap;
	}

	.tp-result-pend {
		background: rgba(91, 94, 244, 0.06);
		border: 1px solid rgba(91, 94, 244, 0.2);
	}

	.tp-result-correct {
		background: var(--green-soft);
		border: 1px solid var(--green-border);
	}

	.tp-result-wrong {
		background: var(--red-soft);
		border: 1px solid var(--red-border);
	}

	.tp-result-tag {
		font-family: var(--f-head);
		font-size: 10px;
		font-weight: 700;
		text-transform: uppercase;
		letter-spacing: 0.1em;
		margin-bottom: 5px;
	}

	.tp-result-pend .tp-result-tag {
		color: var(--accent);
	}

	.tp-result-correct .tp-result-tag {
		color: #009970;
	}

	.tp-result-wrong .tp-result-tag {
		color: var(--red);
	}

	.tp-result h4 {
		font-family: var(--f-head);
		font-size: 15px;
		font-weight: 700;
		color: var(--ink);
		margin-bottom: 4px;
	}

	.tp-result p {
		font-size: 13px;
		color: var(--muted);
		line-height: 1.5;
	}

	.tp-result-amt {
		font-family: var(--f-head);
		font-size: 26px;
		font-weight: 800;
		letter-spacing: -0.03em;
		flex-shrink: 0;
	}

	.tp-result-pend .tp-result-amt {
		color: var(--accent);
	}

	.tp-result-correct .tp-result-amt {
		color: #009970;
	}

	.tp-result-wrong .tp-result-amt {
		color: var(--red);
	}

	/* Answer Toggle */
	.tp-toggle {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 10px;
		margin-bottom: 18px;
	}

	.tp-toggle input[type=radio] {
		display: none;
	}

	.tp-toggle label {
		display: flex;
		align-items: center;
		justify-content: space-between;
		padding: 16px 18px;
		border-radius: var(--r-md);
		border: 1.5px solid var(--border-2);
		background: var(--surface);
		cursor: pointer;
		transition: all 0.18s;
	}

	.tp-tog-left {
		display: flex;
		align-items: center;
		gap: 10px;
	}

	.tp-tog-dot {
		width: 8px;
		height: 8px;
		border-radius: 50%;
	}

	.tog-yes-lbl .tp-tog-dot {
		background: var(--green);
	}

	.tog-no-lbl .tp-tog-dot {
		background: var(--red);
	}

	.tp-tog-name {
		font-family: var(--f-head);
		font-size: 15px;
		font-weight: 700;
		color: var(--ink);
	}

	.tp-tog-price {
		font-size: 12px;
		color: var(--muted);
	}

	.tp-tog-badge {
		font-family: var(--f-head);
		font-size: 11px;
		font-weight: 700;
		padding: 4px 10px;
		border-radius: 6px;
		background: var(--surface-2);
		color: var(--muted);
		transition: all 0.18s;
	}

	#tp_yes:checked+label.tog-yes-lbl {
		border-color: var(--green);
		background: var(--green-soft);
	}

	#tp_yes:checked+label.tog-yes-lbl .tp-tog-name {
		color: #009970;
	}

	#tp_yes:checked+label.tog-yes-lbl .tp-tog-badge {
		background: var(--green);
		color: #fff;
	}

	#tp_no:checked+label.tog-no-lbl {
		border-color: var(--red);
		background: var(--red-soft);
	}

	#tp_no:checked+label.tog-no-lbl .tp-tog-name {
		color: var(--red);
	}

	#tp_no:checked+label.tog-no-lbl .tp-tog-badge {
		background: var(--red);
		color: #fff;
	}

	.tp-toggle.locked label {
		cursor: not-allowed;
		opacity: 0.6;
	}

	/* Controls */
	.tp-controls {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 12px;
		margin-bottom: 18px;
	}

	.tp-ctrl {
		padding: 16px 18px;
		border-radius: var(--r-md);
		border: 1px solid var(--border);
		background: var(--surface);
	}

	.tp-ctrl-hdr {
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-bottom: 12px;
	}

	.tp-ctrl-label {
		font-family: var(--f-head);
		font-size: 10px;
		font-weight: 600;
		text-transform: uppercase;
		letter-spacing: 0.1em;
		color: var(--muted);
	}

	.tp-ctrl-val {
		font-family: var(--f-head);
		font-size: 16px;
		font-weight: 800;
		color: var(--ink);
	}

	.tp-stepper {
		display: flex;
		align-items: center;
		gap: 8px;
	}

	.tp-step-btn {
		width: 32px;
		height: 32px;
		border-radius: 8px;
		border: 1px solid var(--border-2);
		background: var(--white);
		color: var(--accent);
		font-size: 15px;
		cursor: pointer;
		display: flex;
		align-items: center;
		justify-content: center;
		transition: all 0.14s;
		flex-shrink: 0;
		line-height: 1;
		font-family: var(--f-head);
		font-weight: 700;
	}

	.tp-step-btn:hover {
		background: var(--accent);
		color: #fff;
		border-color: var(--accent);
	}

	.tp-step-btn:disabled {
		opacity: 0.3;
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
		padding: 7px 8px;
		border-radius: 8px;
		border: 1px solid var(--border-2);
		background: var(--white);
		color: var(--ink);
		text-align: center;
		font-size: 14px;
		font-weight: 700;
		font-family: var(--f-head);
		outline: none;
	}

	.tp-stepper input[type=number]:focus {
		border-color: var(--accent);
		box-shadow: 0 0 0 3px rgba(91, 94, 244, 0.1);
	}

	.tp-stepper input[type=number]::-webkit-inner-spin-button,
	.tp-stepper input[type=number]::-webkit-outer-spin-button {
		-webkit-appearance: none;
	}

	.tp-ctrl-hint {
		font-size: 11px;
		color: var(--muted);
		margin-top: 6px;
	}

	.tp-qty-warn {
		font-size: 11px;
		color: var(--red);
		margin-top: 5px;
		display: none;
	}

	.tp-qty-warn.show {
		display: block;
	}

	/* Stake Preview */
	.tp-stake {
		border-radius: var(--r-md);
		border: 1px solid var(--border);
		overflow: hidden;
		margin-bottom: 16px;
	}

	.tp-stake-head {
		padding: 12px 18px;
		background: linear-gradient(90deg, rgba(91, 94, 244, 0.06), rgba(91, 94, 244, 0.02));
		border-bottom: 1px solid var(--border);
		display: flex;
		align-items: center;
		gap: 10px;
	}

	.tp-boost-badge {
		display: inline-flex;
		align-items: center;
		gap: 5px;
		padding: 4px 10px;
		border-radius: 999px;
		background: var(--green-soft);
		color: #009970;
		font-family: var(--f-head);
		font-size: 11px;
		font-weight: 700;
		border: 1px solid var(--green-border);
	}

	.tp-stake-body {
		padding: 0;
	}

	.tp-stake-row {
		display: flex;
		align-items: center;
		justify-content: space-between;
		padding: 12px 18px;
		font-size: 13px;
		border-bottom: 1px solid var(--border);
	}

	.tp-stake-row:last-child {
		border-bottom: none;
	}

	.tp-stake-row span {
		color: var(--muted);
	}

	.tp-stake-row strong {
		font-family: var(--f-head);
		font-weight: 700;
		color: var(--ink);
	}

	.tp-stake-total strong {
		color: var(--accent);
		font-size: 15px;
	}

	.tp-stake-win strong {
		color: var(--green);
	}

	/* Formula */
	.tp-formula {
		padding: 14px 18px;
		border-radius: var(--r-md);
		background: rgba(91, 94, 244, 0.04);
		border: 1px solid rgba(91, 94, 244, 0.15);
		margin-bottom: 18px;
	}

	.tp-formula-title {
		font-family: var(--f-head);
		font-size: 10px;
		font-weight: 700;
		text-transform: uppercase;
		letter-spacing: 0.1em;
		color: var(--accent);
		margin-bottom: 6px;
	}

	.tp-formula p {
		font-size: 13px;
		line-height: 1.65;
		color: var(--muted);
	}

	.tp-formula strong {
		color: var(--accent);
	}

	/* Submit */
	.tp-submit-row {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 16px;
		padding: 16px 20px;
		border-radius: var(--r-md);
		background: var(--surface);
		border: 1px solid var(--border);
		flex-wrap: wrap;
	}

	.tp-submit-info strong {
		font-family: var(--f-head);
		font-size: 14px;
		font-weight: 700;
		color: var(--ink);
		display: block;
		margin-bottom: 2px;
	}

	.tp-submit-info p {
		font-size: 12px;
		color: var(--muted);
	}

	.tp-submit-btn {
		padding: 13px 28px;
		border: none;
		border-radius: var(--r-sm);
		background: var(--ink);
		color: var(--white);
		font-family: var(--f-head);
		font-size: 14px;
		font-weight: 700;
		cursor: pointer;
		letter-spacing: 0.02em;
		transition: all 0.18s;
		flex-shrink: 0;
	}

	.tp-submit-btn:hover {
		background: var(--accent);
		transform: translateY(-1px);
		box-shadow: 0 8px 20px rgba(91, 94, 244, 0.3);
	}

	.tp-submit-btn:active {
		transform: scale(0.98);
	}

	.tp-submit-btn:disabled {
		background: var(--surface-2);
		color: var(--muted);
		cursor: not-allowed;
		transform: none;
		box-shadow: none;
	}

	.tp-lock-note {
		padding: 14px 18px;
		border-radius: var(--r-md);
		border: 1px dashed var(--border-2);
		background: var(--surface);
		font-size: 13px;
		color: var(--muted);
		line-height: 1.6;
		margin-top: 16px;
	}

	/* Chart */
	.tp-chart-wrap {
		border-radius: var(--r-md);
		background: var(--surface);
		border: 1px solid var(--border);
		padding: 18px;
	}

	.tp-chart-hdr {
		display: flex;
		justify-content: space-between;
		align-items: flex-start;
		gap: 10px;
		margin-bottom: 16px;
		flex-wrap: wrap;
	}

	.tp-chart-title {
		font-family: var(--f-head);
		font-size: 14px;
		font-weight: 700;
		color: var(--ink);
		margin-bottom: 2px;
	}

	.tp-chart-sub {
		font-size: 12px;
		color: var(--muted);
	}

	.tp-chart-legend {
		display: flex;
		gap: 6px;
	}

	.tp-leg-pill {
		display: inline-flex;
		align-items: center;
		gap: 5px;
		padding: 4px 10px;
		border-radius: 999px;
		font-size: 11px;
		font-weight: 600;
		font-family: var(--f-head);
		background: var(--white);
		border: 1px solid var(--border-2);
		color: var(--muted);
	}

	.tp-leg-pill::before {
		content: '';
		width: 6px;
		height: 6px;
		border-radius: 50%;
		display: inline-block;
	}

	.tp-leg-pill.yes::before {
		background: var(--green);
	}

	.tp-leg-pill.no::before {
		background: var(--red);
	}

	.tp-chart-metrics {
		display: grid;
		grid-template-columns: repeat(4, 1fr);
		gap: 8px;
		margin-bottom: 14px;
	}

	.tp-metric {
		background: var(--white);
		border: 1px solid var(--border);
		border-radius: var(--r-sm);
		padding: 12px 14px;
	}

	.tp-metric-label {
		font-family: var(--f-head);
		font-size: 10px;
		font-weight: 600;
		text-transform: uppercase;
		letter-spacing: 0.08em;
		color: var(--muted);
		margin-bottom: 4px;
	}

	.tp-metric-val {
		font-family: var(--f-head);
		font-size: 17px;
		font-weight: 800;
		color: var(--ink);
	}

	.tp-metric-val.m-yes {
		color: #009970;
	}

	.tp-metric-val.m-no {
		color: var(--red);
	}

	.tp-metric-val.m-acc {
		color: var(--accent);
	}

	.tp-metric-sub {
		font-size: 10px;
		color: var(--muted);
		margin-top: 3px;
	}

	.tp-chart-svg {
		background: var(--white);
		border: 1px solid var(--border);
		border-radius: var(--r-sm);
		overflow: hidden;
		padding: 8px;
	}

	.tp-chart-footer {
		display: flex;
		align-items: center;
		justify-content: space-between;
		margin-top: 10px;
		font-size: 11px;
		color: var(--muted);
	}

	.tp-spread-pill {
		padding: 3px 10px;
		border-radius: 999px;
		background: var(--white);
		border: 1px solid var(--border-2);
		color: var(--text);
		font-weight: 600;
		font-family: var(--f-head);
	}

	/* Sidebar */
	.tp-side {
		display: flex;
		flex-direction: column;
		gap: 16px;
	}

	.tp-progress-grid {
		display: grid;
		grid-template-columns: repeat(3, 1fr);
		gap: 8px;
	}

	.tp-prog-stat {
		background: var(--surface);
		border: 1px solid var(--border);
		border-radius: var(--r-md);
		padding: 14px;
		text-align: center;
	}

	.tp-prog-label {
		font-family: var(--f-head);
		font-size: 10px;
		font-weight: 600;
		text-transform: uppercase;
		letter-spacing: 0.08em;
		color: var(--muted);
		margin-bottom: 5px;
	}

	.tp-prog-val {
		font-family: var(--f-head);
		font-size: 22px;
		font-weight: 800;
		letter-spacing: -0.02em;
	}

	.ps-total .tp-prog-val {
		color: var(--accent);
	}

	.ps-correct .tp-prog-val {
		color: var(--green);
	}

	.ps-wrong .tp-prog-val {
		color: var(--red);
	}

	/* Question List */
	.tp-qlist {
		display: flex;
		flex-direction: column;
		gap: 7px;
	}

	.tp-qitem {
		display: block;
		padding: 13px 16px;
		border-radius: var(--r-md);
		border: 1px solid var(--border);
		background: var(--white);
		text-decoration: none;
		transition: all 0.18s;
	}

	.tp-qitem:hover {
		border-color: #c5cdd9;
		transform: translateX(2px);
		box-shadow: var(--shadow-sm);
	}

	.tp-qitem.q-active {
		border-color: var(--accent);
		background: rgba(91, 94, 244, 0.04);
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
		font-weight: 500;
		color: var(--ink);
		line-height: 1.45;
		display: -webkit-box;
		-webkit-line-clamp: 2;
		-webkit-box-orient: vertical;
		overflow: hidden;
		margin-bottom: 7px;
	}

	.tp-qitem-foot {
		display: flex;
		align-items: center;
		gap: 6px;
	}

	.tp-qbadge {
		display: inline-flex;
		align-items: center;
		gap: 4px;
		padding: 2px 8px;
		border-radius: 6px;
		font-family: var(--f-head);
		font-size: 10px;
		font-weight: 700;
	}

	.tp-qbadge.bq-open {
		background: var(--green-soft);
		color: #009970;
		border: 1px solid var(--green-border);
	}

	.tp-qbadge.bq-closed {
		background: var(--surface-2);
		color: var(--muted);
		border: 1px solid var(--border-2);
	}

	.tp-qbadge.bq-correct {
		background: var(--green-soft);
		color: #009970;
		border: 1px solid var(--green-border);
	}

	.tp-qbadge.bq-wrong {
		background: var(--red-soft);
		color: var(--red);
		border: 1px solid var(--red-border);
	}

	.tp-qbadge-dot {
		width: 5px;
		height: 5px;
		border-radius: 50%;
		background: currentColor;
	}

	/* Responsive */
	@media (max-width: 1020px) {
		.tp-layout {
			grid-template-columns: 1fr;
		}

		.tp-chart-metrics {
			grid-template-columns: repeat(2, 1fr);
		}
	}

	@media (max-width: 640px) {
		.tp-qhero {
			grid-template-columns: 1fr;
			padding: 20px;
		}

		.tp-qhero-meta {
			flex-direction: row;
			flex-wrap: wrap;
			align-items: flex-start;
		}

		.tp-controls {
			grid-template-columns: 1fr;
		}

		.tp-market-chips {
			grid-template-columns: 1fr;
		}

		.tp-panel-body {
			padding: 18px;
		}

		.tp-panel-head {
			padding: 16px 18px;
		}
	}
</style>

<div class="tp">

	<?php if ($answer_error_flash): ?>
		<div class="tp-alert tp-alert-err"><i class="fa-solid fa-circle-xmark"></i> <?php echo $answer_error_flash; ?></div>
	<?php endif; ?>
	<?php if ($this->session->flashdata('success')): ?>
		<div class="tp-alert tp-alert-ok"><i class="fa-solid fa-circle-check"></i> <?php echo $this->session->flashdata('success'); ?></div>
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

	<!-- Question Hero -->
	<section class="tp-qhero">
		<div>
			<h1 class="tp-qhero-title"><?php echo html_escape($selected_question->question); ?></h1>
			<p class="tp-qhero-sub">
				<?php echo $is_locked
					? 'Your trade is locked. Payout will be credited once the admin resolves this market.'
					: 'Pick YES or NO, set your price and quantity, then submit. One trade per question.'; ?>
			</p>
		</div>
		<div class="tp-qhero-meta">
			<span class="tp-pill tp-pill-users">
				<i class="fa-solid fa-users" style="font-size:11px;"></i>
				<?php echo number_format($joined_users); ?> Joined
			</span>
			<span class="tp-pill <?php echo $market_is_open ? 'tp-pill-open' : 'tp-pill-closed'; ?>">
				<?php if ($market_is_open): ?><span class="tp-pulse"></span><?php endif; ?>
				<?php echo $market_is_open ? 'Market Open' : 'Market Closed'; ?>
			</span>
			<span class="tp-pill tp-pill-cat"><?php echo html_escape($selected_category->name); ?></span>
		</div>
	</section>

	<!-- Layout -->
	<div class="tp-layout">

		<!-- LEFT -->
		<div>

			<!-- Market Overview -->
			<div class="tp-panel">
				<div class="tp-panel-head">
					<div>
						<div class="tp-panel-title">Market Overview</div>
						<div class="tp-panel-sub">Current YES / NO prices and trade volume.</div>
					</div>
				</div>
				<div class="tp-panel-body">

					<div class="tp-prob-bar-wrap">
						<div class="tp-prob-labels">
							<span class="tp-yes-label">YES — <?php echo $yes_pct; ?>%</span>
							<span class="tp-no-label"><?php echo $no_pct; ?>% — NO</span>
						</div>
						<div class="tp-prob-bar">
							<div class="tp-prob-y" style="width:<?php echo $yes_pct; ?>%"></div>
							<div class="tp-prob-n" style="width:<?php echo $no_pct; ?>%"></div>
						</div>
					</div>

					<div class="tp-market-chips">
						<div class="tp-mchip mc-users">
							<div class="tp-mchip-label">Joined</div>
							<div class="tp-mchip-val"><?php echo number_format($joined_users); ?></div>
						</div>
						<div class="tp-mchip mc-yes">
							<div class="tp-mchip-label">YES Price</div>
							<div class="tp-mchip-val">₹<?php echo number_format($yes_price, 2); ?></div>
						</div>
						<div class="tp-mchip mc-no">
							<div class="tp-mchip-label">NO Price</div>
							<div class="tp-mchip-val">₹<?php echo number_format($no_price, 2); ?></div>
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
			<div class="tp-panel" style="margin-top:16px">
				<div class="tp-panel-head">
					<div>
						<div class="tp-panel-title"><?php echo $is_locked ? 'Your Trade (Locked)' : 'Place Trade'; ?></div>
						<div class="tp-panel-sub"><?php echo $is_locked ? 'Cannot be modified.' : 'Choose a side and confirm your stake.'; ?></div>
					</div>
				</div>
				<div class="tp-panel-body">

					<?php if ($is_locked): ?>
						<div class="tp-result <?php echo $is_settled ? ($is_correct_ans ? 'tp-result-correct' : 'tp-result-wrong') : 'tp-result-pend'; ?>">
							<div>
								<div class="tp-result-tag">
									<?php echo $is_settled ? ($is_correct_ans ? '✓ Correct Answer' : '✗ Wrong Answer') : '⏳ Awaiting Result'; ?>
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

					<?php echo form_open('questions/save_answers'); ?>
					<input type="hidden" name="category_id" value="<?php echo (int)$selected_category->id; ?>">
					<input type="hidden" name="question_id" value="<?php echo (int)$selected_question->id; ?>">
					<input type="hidden" name="price" class="js-ph" value="<?php echo number_format($default_price, 2, '.', ''); ?>">

					<!-- Toggle -->
					<div class="tp-toggle<?php echo $is_locked ? ' locked' : ''; ?>">
						<div>
							<input type="radio" id="tp_yes" name="answer" value="yes"
								<?php echo $selected_answer === 'yes' ? 'checked' : ''; ?>
								<?php echo $is_locked ? 'disabled' : ''; ?>>
							<label for="tp_yes" class="tog-yes-lbl">
								<div class="tp-tog-left">
									<span class="tp-tog-dot"></span>
									<div>
										<div class="tp-tog-name">YES</div>
										<div class="tp-tog-price">₹<?php echo number_format($yes_price, 2); ?></div>
									</div>
								</div>
								<span class="tp-tog-badge">YES</span>
							</label>
						</div>
						<div>
							<input type="radio" id="tp_no" name="answer" value="no"
								<?php echo $selected_answer === 'no' ? 'checked' : ''; ?>
								<?php echo $is_locked ? 'disabled' : ''; ?>>
							<label for="tp_no" class="tog-no-lbl">
								<div class="tp-tog-left">
									<span class="tp-tog-dot"></span>
									<div>
										<div class="tp-tog-name">NO</div>
										<div class="tp-tog-price">₹<?php echo number_format($no_price, 2); ?></div>
									</div>
								</div>
								<span class="tp-tog-badge">NO</span>
							</label>
						</div>
					</div>

					<!-- Controls -->
					<div class="tp-controls">
						<div class="tp-ctrl">
							<div class="tp-ctrl-hdr">
								<span class="tp-ctrl-label">Price per share</span>
								<strong class="tp-ctrl-val js-pdisplay">₹<?php echo number_format($default_price, 2); ?></strong>
							</div>
							<div class="tp-stepper">
								<button type="button" class="tp-step-btn js-pdec" <?php echo $is_locked ? 'disabled' : ''; ?>>−</button>
								<input type="range" class="js-prange"
									min="0.50"
									max="<?php echo number_format($price_max, 2, '.', ''); ?>"
									step="0.50"
									value="<?php echo number_format(min($default_price, $price_max), 2, '.', ''); ?>"
									<?php echo $is_locked ? 'disabled' : ''; ?>>
								<button type="button" class="tp-step-btn js-pinc" <?php echo $is_locked ? 'disabled' : ''; ?>>+</button>
							</div>
							<div class="tp-ctrl-hint">₹0.50 – ₹<?php echo number_format($price_max, 2); ?></div>
						</div>

						<div class="tp-ctrl">
							<div class="tp-ctrl-hdr">
								<span class="tp-ctrl-label">Quantity</span>
								<strong class="tp-ctrl-val js-qdisplay"><?php echo (int)$default_quantity; ?></strong>
							</div>
							<div class="tp-stepper">
								<button type="button" class="tp-step-btn js-qdec" <?php echo $is_locked ? 'disabled' : ''; ?>>−</button>
								<input type="number" class="js-qinput" name="quantity"
									min="<?php echo $QTY_MIN; ?>"
									max="<?php echo $QTY_MAX; ?>"
									step="1"
									value="<?php echo (int)$default_quantity; ?>"
									<?php echo $is_locked ? 'readonly' : ''; ?>>
								<button type="button" class="tp-step-btn js-qinc" <?php echo $is_locked ? 'disabled' : ''; ?>>+</button>
							</div>
							<div class="tp-ctrl-hint">Min <?php echo $QTY_MIN; ?> · Max <?php echo number_format($QTY_MAX); ?></div>
							<div class="tp-qty-warn" id="js-qwarn">Quantity cannot exceed <?php echo $QTY_MAX; ?></div>
						</div>
					</div>

					<!-- Stake Preview -->
					<div class="tp-stake">
						<div class="tp-stake-head">
							<span class="tp-boost-badge">🔥 ×<?php echo number_format($multiplier, 2); ?> Boost Active</span>
						</div>
						<div class="tp-stake-body">
							<div class="tp-stake-row">
								<span>Price × Quantity</span>
								<strong class="js-formula">—</strong>
							</div>
							<div class="tp-stake-row tp-stake-total">
								<span>Total Stake</span>
								<strong class="js-stake">—</strong>
							</div>
							<div class="tp-stake-row tp-stake-win">
								<span>Winning Preview (×<?php echo number_format($multiplier, 2); ?>)</span>
								<strong class="js-win">—</strong>
							</div>
						</div>
					</div>

					<!-- Formula -->
					<div class="tp-formula">
						<div class="tp-formula-title">How Winning is Calculated</div>
						<p>If your answer matches the result: <strong class="js-win-inline">—</strong> will be credited. Formula: Price × Quantity × <?php echo number_format($multiplier, 2); ?>. Wrong answer = ₹0.00.</p>
					</div>

					<?php if ($is_locked): ?>
						<div class="tp-lock-note">
							<i class="fa-solid fa-lock" style="margin-right:6px;"></i>
							This trade is locked. Head back to the category to open another question.
						</div>
					<?php else: ?>
						<div class="tp-submit-row">
							<div class="tp-submit-info">
								<strong>Ready to submit?</strong>
								<p>This action is final and cannot be undone.</p>
							</div>
							<button type="submit" class="tp-submit-btn" id="js-sbtn">Place Trade →</button>
						</div>
					<?php endif; ?>

					<?php echo form_close(); ?>
				</div>
			</div>

			<!-- Price History Chart -->
			<div class="tp-panel" style="margin-top:16px">
				<div class="tp-panel-head">
					<div>
						<div class="tp-panel-title">Price History</div>
						<div class="tp-panel-sub">YES and NO price movement from market snapshots.</div>
					</div>
				</div>
				<div class="tp-panel-body">
					<div class="tp-chart-wrap" data-chart data-history='<?php echo json_encode($price_history); ?>'>
						<div class="tp-chart-hdr">
							<div>
								<div class="tp-chart-title">Demand Chart · <?php echo html_escape($selected_category->name); ?></div>
								<div class="tp-chart-sub">Auto-scaled from latest price snapshots</div>
							</div>
							<div class="tp-chart-legend">
								<span class="tp-leg-pill yes">YES</span>
								<span class="tp-leg-pill no">NO</span>
							</div>
						</div>

						<div class="tp-chart-metrics">
							<div class="tp-metric">
								<div class="tp-metric-label">YES Price</div>
								<div class="tp-metric-val m-yes">₹<?php echo number_format($yes_price, 2); ?></div>
								<div class="tp-metric-sub"><?php echo $yes_trade_qty; ?> trades</div>
							</div>
							<div class="tp-metric">
								<div class="tp-metric-label">NO Price</div>
								<div class="tp-metric-val m-no">₹<?php echo number_format($no_price, 2); ?></div>
								<div class="tp-metric-sub"><?php echo $no_trade_qty; ?> trades</div>
							</div>
							<div class="tp-metric">
								<div class="tp-metric-label">Market Total</div>
								<div class="tp-metric-val">₹<?php echo number_format($market_total, 2); ?></div>
								<div class="tp-metric-sub"><?php echo ucfirst((string)$selected_question->status); ?></div>
							</div>
							<div class="tp-metric">
								<div class="tp-metric-label">Positions</div>
								<div class="tp-metric-val m-acc"><?php echo $total_trade_qty; ?></div>
								<div class="tp-metric-sub">Total opened</div>
							</div>
						</div>

						<div class="tp-chart-svg">
							<div id="js-chart"></div>
						</div>

						<div class="tp-chart-footer">
							<span>Current spread</span>
							<span class="tp-spread-pill">₹<?php echo number_format(abs($yes_price - $no_price), 2); ?></span>
						</div>
					</div>
				</div>
			</div>

		</div><!-- /LEFT -->

		<!-- SIDEBAR -->
		<aside class="tp-side">

			<!-- Progress -->
			<div class="tp-panel">
				<div class="tp-panel-head">
					<div>
						<div class="tp-panel-title"><?php echo html_escape($selected_category->name); ?></div>
						<div class="tp-panel-sub">Your answers in this category</div>
					</div>
				</div>
				<div class="tp-panel-body">
					<div class="tp-progress-grid">
						<div class="tp-prog-stat ps-total">
							<div class="tp-prog-label">Done</div>
							<div class="tp-prog-val"><?php echo (int)$answered_count; ?></div>
						</div>
						<div class="tp-prog-stat ps-correct">
							<div class="tp-prog-label">Right</div>
							<div class="tp-prog-val"><?php echo (int)$correct_count; ?></div>
						</div>
						<div class="tp-prog-stat ps-wrong">
							<div class="tp-prog-label">Wrong</div>
							<div class="tp-prog-val"><?php echo (int)$wrong_count; ?></div>
						</div>
					</div>
				</div>
			</div>

			<!-- Question List -->
			<div class="tp-panel">
				<div class="tp-panel-head">
					<div>
						<div class="tp-panel-title">All Questions</div>
						<div class="tp-panel-sub">Jump to any question in this category</div>
					</div>
				</div>
				<div class="tp-panel-body">
					<div class="tp-qlist">
						<?php foreach ($selected_category->questions as $qi):
							$la = isset($user_answers[(int)$qi->id]) ? $user_answers[(int)$qi->id] : NULL;
							$ia = (int)$qi->id === (int)$selected_question->id;
							$qs = '';
							if ($la) $qs = strtolower((string)$la->answer) === strtolower((string)$qi->answer_key) ? 'correct' : 'wrong';
							$cls = $ia ? 'q-active' : ($qs ? 'q-' . $qs : '');
						?>
							<a class="tp-qitem <?php echo $cls; ?>" href="<?php echo site_url('questions/answer/' . (int)$qi->id); ?>">
								<div class="tp-qitem-text"><?php echo html_escape($qi->question); ?></div>
								<div class="tp-qitem-foot">
									<?php if ($la): ?>
										<span class="tp-qbadge <?php echo $qs === 'correct' ? 'bq-correct' : 'bq-wrong'; ?>">
											<span class="tp-qbadge-dot"></span>
											<?php echo $qs === 'correct' ? 'Correct' : 'Wrong'; ?>
										</span>
									<?php elseif (strtolower(trim((string)$qi->status)) === 'open'): ?>
										<span class="tp-qbadge bq-open"><span class="tp-qbadge-dot"></span>Open</span>
									<?php else: ?>
										<span class="tp-qbadge bq-closed">Closed</span>
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
				mount.innerHTML = '<div style="display:flex;align-items:center;justify-content:center;height:130px;font-size:13px;color:#8892a4;">No price history available yet.</div>';
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
				'<defs>' +
				'<linearGradient id="gY" x1="0" y1="0" x2="1" y2="0"><stop offset="0%" stop-color="#00c896"/><stop offset="100%" stop-color="#009970"/></linearGradient>' +
				'<linearGradient id="gN" x1="0" y1="0" x2="1" y2="0"><stop offset="0%" stop-color="#ff6b82"/><stop offset="100%" stop-color="#ff4d6a"/></linearGradient>' +
				'</defs>' +
				'<line x1="' + PX + '" y1="' + (H - PB) + '" x2="' + (W - PX) + '" y2="' + (H - PB) + '" stroke="#e4e8f2" stroke-width="1"/>' +
				'<line x1="' + PX + '" y1="' + Math.round((H - PB + PT) / 2) + '" x2="' + (W - PX) + '" y2="' + Math.round((H - PB + PT) / 2) + '" stroke="#e4e8f2" stroke-width="1" stroke-dasharray="4 4"/>' +
				'<polyline fill="none" stroke="url(#gY)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" points="' + norm(vY) + '"/>' +
				'<polyline fill="none" stroke="url(#gN)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" points="' + norm(vN) + '"/>' +
				'</svg>';
		}());
	}());
</script>