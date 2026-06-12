<?php
$detail_question  = $selected_question;
$real_users = isset($detail_question->real_users) ? (int)$detail_question->real_users : 0;
$yes_qty    = isset($detail_question->trade_totals['actual_yes_quantity']) ? (int)$detail_question->trade_totals['actual_yes_quantity'] : 0;
$no_qty     = isset($detail_question->trade_totals['actual_no_quantity'])  ? (int)$detail_question->trade_totals['actual_no_quantity']  : 0;
$yes_users  = isset($detail_question->user_counts['yes_users'])     ? (int)$detail_question->user_counts['yes_users']     : 0;
$no_users   = isset($detail_question->user_counts['no_users'])      ? (int)$detail_question->user_counts['no_users']      : 0;
$yes_spent  = isset($detail_question->trade_totals['yes_spent']) ? (float)$detail_question->trade_totals['yes_spent'] : 0.0;
$no_spent   = isset($detail_question->trade_totals['no_spent'])  ? (float)$detail_question->trade_totals['no_spent']  : 0.0;

$market_total   = (float)$detail_question->yes_price + (float)$detail_question->no_price;
$spread_total   = abs((float)$detail_question->yes_price - (float)$detail_question->no_price);
$multiplier_val = isset($detail_question->multiplier) ? (float)$detail_question->multiplier : 1.25;

$now_ts   = time();
$start_ts = (!empty($detail_question->start_time) && $detail_question->start_time !== '0000-00-00 00:00:00') ? strtotime($detail_question->start_time) : false;
$end_ts   = (!empty($detail_question->end_time)   && $detail_question->end_time   !== '0000-00-00 00:00:00') ? strtotime($detail_question->end_time)   : false;

$timing_class = 'live';
$timing_label = 'Live';
if ($start_ts && $now_ts < $start_ts) {
	$timing_class = 'upcoming';
	$timing_label = 'Upcoming';
} elseif ($end_ts && $now_ts > $end_ts) {
	$timing_class = 'ended';
	$timing_label = 'Ended';
} elseif (strtolower((string)$detail_question->status) !== 'open') {
	$timing_class = 'ended';
	$timing_label = ucfirst((string)$detail_question->status);
}

$answer_key_label = !empty($detail_question->answer_key) ? strtoupper((string)$detail_question->answer_key) : 'Pending';
?>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
	/* ─── SCOPED RESET ─────────────────────────────────────── */
	.qp-root,
	.qp-root *,
	.qp-root *::before,
	.qp-root *::after {
		box-sizing: border-box;
		margin: 0;
		padding: 0;
	}

	/* ─── ROOT WRAPPER ─────────────────────────────────────── */
	/* Key fix: width:100% + overflow-x:hidden stops the left-clip
   caused by admin panel wrapper padding pushing content off-screen */
	.qp-root {
		width: 100%;
		max-width: 100%;
		overflow-x: hidden;
		/* ← prevents horizontal bleed */
		font-family: 'Roboto', sans-serif;
		color: #0f172a;
		display: flex;
		flex-direction: column;
		gap: 12px;
		padding: 0 0 32px 0;
	}

	/* ─── TOKENS ───────────────────────────────────────────── */
	.qp-root {
		--bg: #f1f5f9;
		--card: #ffffff;
		--muted: #f8fafc;
		--b1: rgba(15, 23, 42, .08);
		--b2: rgba(15, 23, 42, .14);
		--tx1: #0f172a;
		--tx2: #475569;
		--tx3: #94a3b8;

		--blue: #2563eb;
		--blue-lt: #eff6ff;
		--blue-bd: #bfdbfe;
		--green: #16a34a;
		--green-lt: #f0fdf4;
		--green-bd: #bbf7d0;
		--red: #dc2626;
		--red-lt: #fef2f2;
		--red-bd: #fecaca;
		--amber: #b45309;
		--amber-lt: #fffbeb;
		--amber-bd: #fde68a;
		--slate: #64748b;
		--slate-lt: #f8fafc;
		--slate-bd: #e2e8f0;
		--org: #c2410c;
		--org-lt: #fff7ed;
		--org-bd: #fed7aa;

		--r1: 6px;
		--r2: 10px;
		--r3: 14px;
		--r4: 18px;
		--ease: 150ms ease;
		--f-body: 'Roboto', sans-serif;
	}

	/* ─── CARD ─────────────────────────────────────────────── */
	.qp-card {
		width: 100%;
		background: var(--card);
		border: 1px solid var(--b1);
		border-radius: var(--r4);
		box-shadow: 0 1px 3px rgba(0, 0, 0, .06);
		overflow: hidden;
		/* clips inner content cleanly */
	}

	/* ─── TOP BAR ──────────────────────────────────────────── */
	.qp-topbar {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 12px;
		padding: 15px 18px;
		flex-wrap: wrap;
	}

	.qp-topbar-h {
		font-size: 16px;
		font-weight: 700;
		letter-spacing: -.02em;
		color: var(--tx1);
	}

	.qp-topbar-s {
		font-size: 12px;
		color: var(--tx3);
		margin-top: 2px;
	}

	.qp-back {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		padding: 8px 14px;
		border-radius: var(--r2);
		background: var(--blue-lt);
		border: 1px solid var(--blue-bd);
		color: var(--blue);
		font-size: 12px;
		font-weight: 600;
		font-family: var(--f-body);
		text-decoration: none;
		white-space: nowrap;
		flex-shrink: 0;
		transition: background var(--ease);
	}

	.qp-back:hover {
		background: #dbeafe;
	}

	/* ─── QUESTION BODY ────────────────────────────────────── */
	.qp-qbody {
		padding: 16px 18px;
	}

	.qp-qtitle {
		font-size: 16px;
		font-weight: 700;
		line-height: 1.5;
		letter-spacing: -.02em;
		color: var(--tx1);
		margin-bottom: 11px;
	}

	/* badges */
	.qp-badges {
		display: flex;
		flex-wrap: wrap;
		gap: 6px;
		margin-bottom: 12px;
	}

	.qp-badge {
		display: inline-flex;
		align-items: center;
		gap: 5px;
		padding: 4px 10px;
		border-radius: 999px;
		font-size: 10px;
		font-weight: 700;
		border: 1px solid;
		text-transform: uppercase;
		letter-spacing: .07em;
	}

	.qp-dot {
		width: 5px;
		height: 5px;
		border-radius: 50%;
		background: currentColor;
		flex-shrink: 0;
	}

	.qp-badge.bsaved {
		background: var(--green-lt);
		border-color: var(--green-bd);
		color: var(--green);
	}

	.qp-badge.bpend {
		background: var(--amber-lt);
		border-color: var(--amber-bd);
		color: var(--amber);
	}

	.qp-badge.blive {
		background: var(--green-lt);
		border-color: var(--green-bd);
		color: var(--green);
	}

	.qp-badge.bended {
		background: var(--slate-lt);
		border-color: var(--slate-bd);
		color: var(--slate);
	}

	.qp-badge.bupcoming {
		background: var(--org-lt);
		border-color: var(--org-bd);
		color: var(--org);
	}

	.qp-badge.bneutral {
		background: var(--slate-lt);
		border-color: var(--b2);
		color: var(--tx2);
	}

	/* actions */
	.qp-actions {
		display: flex;
		gap: 8px;
		flex-wrap: wrap;
		margin-bottom: 16px;
	}

	.qp-btn {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		padding: 8px 14px;
		border-radius: var(--r2);
		font-size: 12px;
		font-weight: 600;
		font-family: var(--f-body);
		text-decoration: none;
		border: 1px solid;
		transition: background var(--ease);
		white-space: nowrap;
	}

	.qp-btn-edit {
		background: var(--blue-lt);
		border-color: var(--blue-bd);
		color: var(--blue);
	}

	.qp-btn-edit:hover {
		background: #dbeafe;
	}

	.qp-btn-view {
		background: var(--slate-lt);
		border-color: var(--b2);
		color: var(--tx2);
	}

	.qp-btn-view:hover {
		background: #e2e8f0;
	}

	/* ─── STATS — 3 col, compact, no overflow ──────────────── */
	.qp-stats {
		display: grid;
		grid-template-columns: repeat(3, minmax(0, 1fr));
		/* minmax(0,1fr) = key fix */
		gap: 8px;
		margin-bottom: 12px;
	}

	.qp-stat {
		background: var(--muted);
		border: 1px solid var(--b1);
		border-radius: var(--r3);
		padding: 11px 10px;
		min-width: 0;
		overflow: hidden;
	}

	.qp-stat-l {
		font-size: 9px;
		font-weight: 700;
		letter-spacing: .1em;
		text-transform: uppercase;
		color: var(--tx3);
		margin-bottom: 5px;
	}

	.qp-stat-v {
		font-size: 20px;
		font-weight: 700;
		color: var(--tx1);
		line-height: 1.1;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
	}

	.qp-stat-s {
		font-size: 10px;
		color: var(--tx3);
		margin-top: 3px;
	}

	.qp-tqty {
		display: flex;
		align-items: baseline;
		gap: 2px;
		flex-wrap: wrap;
		font-size: 13px;
		font-weight: 700;
		padding-top: 2px;
	}

	.qp-tqty .y {
		color: var(--green);
	}

	.qp-tqty .sp {
		color: var(--tx3);
		font-size: 11px;
		padding: 0 1px;
	}

	.qp-tqty .n {
		color: var(--red);
	}

	/* ─── DETAIL GRID — 2 col ──────────────────────────────── */
	.qp-dgrid {
		display: grid;
		grid-template-columns: repeat(2, minmax(0, 1fr));
		border: 1px solid var(--b1);
		border-radius: var(--r3);
		overflow: hidden;
	}

	.qp-di {
		padding: 12px 13px;
		border-right: 1px solid var(--b1);
		border-bottom: 1px solid var(--b1);
		background: var(--card);
		min-width: 0;
	}

	.qp-di:nth-child(2n) {
		border-right: none;
	}

	.qp-di:nth-last-child(-n+2) {
		border-bottom: none;
	}

	.qp-di-l {
		font-size: 9px;
		font-weight: 700;
		letter-spacing: .1em;
		text-transform: uppercase;
		color: var(--tx3);
		margin-bottom: 4px;
	}

	.qp-di-v {
		font-size: 15px;
		font-weight: 700;
		color: var(--tx1);
		margin-bottom: 2px;
		word-break: break-word;
	}

	.qp-di-v.yes {
		color: var(--green);
	}

	.qp-di-v.no {
		color: var(--red);
	}

	.qp-di-s {
		font-size: 12px;
		font-weight: 600;
		color: var(--tx2);
	}

	/* ─── SECTION HEADER ───────────────────────────────────── */
	.qp-sechdr {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 10px;
		padding: 13px 18px;
		border-bottom: 1px solid var(--b1);
		background: var(--muted);
		flex-wrap: wrap;
	}

	.qp-sechdr-t {
		font-size: 14px;
		font-weight: 700;
		color: var(--tx1);
	}

	.qp-sechdr-s {
		font-size: 11px;
		color: var(--tx3);
		margin-top: 1px;
	}

	.qp-cnt {
		display: inline-flex;
		align-items: center;
		padding: 5px 12px;
		border-radius: 999px;
		font-size: 12px;
		font-weight: 700;
		background: var(--blue-lt);
		color: var(--blue);
		border: 1px solid var(--blue-bd);
		flex-shrink: 0;
	}

	/* ─── FILTER PANEL ─────────────────────────────────────── */
	.qp-filters {
		padding: 13px 18px;
		border-bottom: 1px solid var(--b1);
		background: var(--card);
	}

	/* search */
	.qp-sw {
		position: relative;
		margin-bottom: 10px;
	}

	.qp-si {
		position: absolute;
		left: 11px;
		top: 50%;
		transform: translateY(-50%);
		color: var(--tx3);
		pointer-events: none;
	}

	.qp-sinput {
		width: 100%;
		height: 38px;
		padding: 0 12px 0 34px;
		border-radius: var(--r2);
		border: 1px solid var(--b2);
		background: var(--muted);
		font-size: 13px;
		font-family: var(--f-body);
		color: var(--tx1);
		outline: none;
		transition: border-color var(--ease), box-shadow var(--ease);
	}

	.qp-sinput::placeholder {
		color: var(--tx3);
	}

	.qp-sinput:focus {
		border-color: var(--blue-bd);
		background: var(--card);
		box-shadow: 0 0 0 3px rgba(37, 99, 235, .1);
	}

	/* filter row */
	.qp-frow {
		display: grid;
		grid-template-columns: 1fr 1fr 1fr 1fr 72px auto;
		gap: 8px;
		align-items: end;
	}

	.qp-flbl {
		font-size: 9px;
		font-weight: 700;
		letter-spacing: .1em;
		text-transform: uppercase;
		color: var(--tx3);
		margin-bottom: 4px;
	}

	.qp-fi,
	.qp-fs {
		width: 100%;
		height: 36px;
		padding: 0 10px;
		border-radius: var(--r2);
		border: 1px solid var(--b2);
		background: var(--muted);
		font-size: 12px;
		font-family: inherit;
		color: var(--tx1);
		outline: none;
		transition: border-color var(--ease), box-shadow var(--ease);
		-webkit-appearance: none;
		appearance: none;
	}

	.qp-fi:focus,
	.qp-fs:focus {
		border-color: var(--blue-bd);
		background: var(--card);
		box-shadow: 0 0 0 3px rgba(37, 99, 235, .1);
	}

	.qp-fs {
		background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%2394a3b8' d='M3 5l5 6 5-6z'/%3E%3C/svg%3E");
		background-repeat: no-repeat;
		background-position: right 9px center;
		padding-right: 26px;
		cursor: pointer;
	}

	.qp-rbtn {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		gap: 5px;
		height: 36px;
		padding: 0 12px;
		border-radius: var(--r2);
		border: 1px solid var(--b2);
		background: var(--slate-lt);
		color: var(--tx2);
		font-size: 12px;
		font-weight: 600;
		font-family: inherit;
		cursor: pointer;
		white-space: nowrap;
		transition: background var(--ease);
	}

	.qp-rbtn:hover {
		background: #e2e8f0;
	}

	/* ─── DESKTOP TABLE ────────────────────────────────────── */
	.qp-twrap {
		overflow-x: auto;
		-webkit-overflow-scrolling: touch;
	}

	.qp-table {
		width: 100%;
		border-collapse: collapse;
		min-width: 680px;
	}

	.qp-table thead th {
		padding: 10px 14px;
		text-align: left;
		font-size: 9px;
		font-weight: 700;
		letter-spacing: .1em;
		text-transform: uppercase;
		color: var(--tx3);
		background: var(--muted);
		border-bottom: 1px solid var(--b1);
		white-space: nowrap;
	}

	.qp-table tbody td {
		padding: 12px 14px;
		font-size: 13px;
		color: var(--tx1);
		border-bottom: 1px solid var(--b1);
		vertical-align: middle;
	}

	.qp-table tbody tr:last-child td {
		border-bottom: none;
	}

	.qp-table tbody tr:hover td {
		background: #f8fbff;
	}

	.qt-n {
		font-weight: 600;
	}

	.qt-s {
		display: block;
		font-size: 11px;
		color: var(--tx3);
		margin-top: 1px;
	}

	.qt-sp {
		color: var(--amber);
		font-weight: 600;
	}

	.qt-w {
		color: var(--green);
		font-weight: 600;
	}

	/* Real Users — highlighted like spend values */
	.qp-stat--users .qp-stat-v {
		font-size: 28px;
		font-weight: 800;
		color: #2563eb;
		/* same blue as --blue token */
		letter-spacing: -.03em;
	}

	.qp-stat--users {
		background: #eff6ff;
		border-color: #bfdbfe;
	}

	.qp-stat--users .qp-stat-l {
		color: #2563eb;
		opacity: .7;
	}

	/* ─── PILLS ────────────────────────────────────────────── */
	.qp-pill {
		display: inline-flex;
		align-items: center;
		padding: 3px 9px;
		border-radius: 999px;
		font-size: 10px;
		font-weight: 700;
		text-transform: uppercase;
		letter-spacing: .05em;
		border: 1px solid;
		white-space: nowrap;
	}

	.qp-pill.yes {
		background: var(--green-lt);
		color: var(--green);
		border-color: var(--green-bd);
	}

	.qp-pill.no {
		background: var(--red-lt);
		color: var(--red);
		border-color: var(--red-bd);
	}

	.qp-pill.win {
		background: var(--green-lt);
		color: var(--green);
		border-color: var(--green-bd);
	}

	.qp-pill.lose {
		background: var(--red-lt);
		color: var(--red);
		border-color: var(--red-bd);
	}

	.qp-pill.pending {
		background: var(--slate-lt);
		color: var(--slate);
		border-color: var(--slate-bd);
	}

	/* ─── MOBILE USER CARDS ────────────────────────────────── */
	.qp-ucards {
		display: none;
		flex-direction: column;
	}

	.qp-ucard {
		padding: 14px 16px;
		border-bottom: 1px solid var(--b1);
		animation: qp-in .15s ease;
	}

	.qp-ucard:last-child {
		border-bottom: none;
	}

	@keyframes qp-in {
		from {
			opacity: 0;
			transform: translateY(4px);
		}

		to {
			opacity: 1;
			transform: translateY(0);
		}
	}

	.qp-uc-top {
		display: flex;
		align-items: flex-start;
		gap: 10px;
		margin-bottom: 10px;
	}

	.qp-uc-av {
		width: 36px;
		height: 36px;
		border-radius: 50%;
		flex-shrink: 0;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 12px;
		font-weight: 700;
		background: var(--blue-lt);
		color: var(--blue);
		border: 1px solid var(--blue-bd);
	}

	.qp-uc-m {
		flex: 1;
		min-width: 0;
	}

	.qp-uc-n {
		font-weight: 700;
		font-size: 14px;
		color: var(--tx1);
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
	}

	.qp-uc-c {
		font-size: 11px;
		color: var(--tx3);
		margin-top: 1px;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
	}

	.qp-uc-pp {
		display: flex;
		flex-direction: column;
		align-items: flex-end;
		gap: 4px;
		flex-shrink: 0;
	}

	.qp-uc-body {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 8px;
		padding: 10px 12px;
		background: var(--muted);
		border-radius: var(--r2);
		border: 1px solid var(--b1);
	}

	.qp-uc-fl {
		font-size: 9px;
		font-weight: 700;
		letter-spacing: .09em;
		text-transform: uppercase;
		color: var(--tx3);
		margin-bottom: 3px;
	}

	.qp-uc-fv {
		font-size: 13px;
		font-weight: 600;
		color: var(--tx1);
	}

	.qp-uc-fv.sp {
		color: var(--amber);
	}

	.qp-uc-fv.wi {
		color: var(--green);
	}

	.qp-uc-fv.ti {
		font-size: 11px;
		font-weight: 400;
		color: var(--tx3);
	}

	/* ─── LOADING / EMPTY ──────────────────────────────────── */
	.qp-empty {
		padding: 36px 20px;
		text-align: center;
		color: var(--tx3);
		font-size: 13px;
	}

	.qp-dots {
		display: inline-flex;
		gap: 4px;
		align-items: center;
		margin-left: 6px;
		vertical-align: middle;
	}

	.qp-dots span {
		width: 5px;
		height: 5px;
		border-radius: 50%;
		background: var(--tx3);
		animation: qp-blink 1.2s infinite;
	}

	.qp-dots span:nth-child(2) {
		animation-delay: .2s;
	}

	.qp-dots span:nth-child(3) {
		animation-delay: .4s;
	}

	@keyframes qp-blink {

		0%,
		80%,
		100% {
			opacity: .2;
			transform: scale(.8);
		}

		40% {
			opacity: 1;
			transform: scale(1);
		}
	}

	/* ─── FOOTER / PAGINATION ──────────────────────────────── */
	.qp-footer {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 10px;
		flex-wrap: wrap;
		padding: 12px 18px;
		border-top: 1px solid var(--b1);
		background: var(--muted);
	}

	.qp-pginfo {
		font-size: 12px;
		color: var(--tx3);
		font-weight: 500;
	}

	.qp-pager {
		display: flex;
		align-items: center;
		gap: 4px;
	}

	.qp-pbtn {
		width: 34px;
		height: 34px;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		border-radius: var(--r2);
		border: 1px solid var(--b2);
		background: var(--card);
		color: var(--tx2);
		cursor: pointer;
		transition: background var(--ease), color var(--ease);
	}

	.qp-pbtn:hover:not(:disabled) {
		background: var(--blue-lt);
		color: var(--blue);
		border-color: var(--blue-bd);
	}

	.qp-pbtn:disabled {
		opacity: .35;
		cursor: not-allowed;
	}

	.qp-pnums {
		display: flex;
		gap: 3px;
	}

	.qp-pnum {
		width: 34px;
		height: 34px;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		border-radius: var(--r2);
		border: 1px solid var(--b2);
		background: var(--card);
		font-size: 12px;
		font-weight: 600;
		font-family: inherit;
		color: var(--tx2);
		cursor: pointer;
		transition: all var(--ease);
	}

	.qp-pnum:hover:not(.active) {
		background: var(--blue-lt);
		color: var(--blue);
		border-color: var(--blue-bd);
	}

	.qp-pnum.active {
		background: var(--blue);
		color: #fff;
		border-color: var(--blue);
	}

	/* ══════════════════════════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════════════════════════ */

	/* tablet: 2-row filter layout */
	@media (max-width: 860px) {
		.qp-frow {
			grid-template-columns: 1fr 1fr 1fr auto;
			row-gap: 10px;
		}

		/* dates span 2 cols on their own row */
		.qp-frow .fd-from {
			grid-column: 1 / 3;
		}

		.qp-frow .fd-to {
			grid-column: 3 / 5;
		}
	}

	/* mobile */
	@media (max-width: 640px) {

		/* swap: hide table, show mobile cards */
		.qp-twrap {
			display: none;
		}

		.qp-ucards {
			display: flex;
		}

		/* tight padding on mobile */
		.qp-topbar {
			padding: 12px 14px;
		}

		.qp-qbody {
			padding: 13px 14px;
		}

		.qp-topbar-h {
			font-size: 14px;
		}

		.qp-qtitle {
			font-size: 14px;
		}

		/* stats: 3 compact columns */
		.qp-stats {
			grid-template-columns: repeat(3, minmax(0, 1fr));
			gap: 6px;
			margin-bottom: 10px;
		}

		.qp-stat {
			padding: 9px 8px;
		}

		.qp-stat-v {
			font-size: 17px;
		}

		.qp-tqty {
			font-size: 12px;
		}

		/* detail grid stays 2 col */
		.qp-di {
			padding: 10px 11px;
		}

		.qp-di-v {
			font-size: 13px;
		}

		/* section header */
		.qp-sechdr {
			padding: 11px 14px;
		}

		/* filters: 2-col on mobile */
		.qp-filters {
			padding: 11px 14px;
		}

		.qp-frow {
			grid-template-columns: 1fr 1fr;
			row-gap: 10px;
		}

		.qp-frow .fd-from {
			grid-column: 1 / 2;
		}

		.qp-frow .fd-to {
			grid-column: 2 / 3;
		}

		.qp-frow .fd-answer {
			grid-column: 1 / 2;
		}

		.qp-frow .fd-result {
			grid-column: 2 / 3;
		}

		.qp-frow .fd-rows {
			grid-column: 1 / 2;
		}

		.qp-frow .fd-reset {
			grid-column: 2 / 3;
			display: flex;
			align-items: flex-end;
			justify-content: flex-end;
		}

		.qp-footer {
			padding: 11px 14px;
		}
	}

	/* very small phones */
	@media (max-width: 360px) {
		.qp-stats {
			grid-template-columns: 1fr 1fr;
		}

		.qp-stats .qp-stat:last-child {
			grid-column: 1 / -1;
		}

		.qp-frow {
			grid-template-columns: 1fr;
		}

		.qp-frow .fd-from,
		.qp-frow .fd-to,
		.qp-frow .fd-answer,
		.qp-frow .fd-result,
		.qp-frow .fd-rows,
		.qp-frow .fd-reset {
			grid-column: 1 / 2;
		}

		.qp-frow .fd-reset {
			justify-content: stretch;
		}

		.qp-rbtn {
			width: 100%;
		}
	}
</style>

<!-- ══════════════════════════════════════════════════════════
     ROOT WRAPPER — scoped, no bleed
══════════════════════════════════════════════════════════ -->
<div class="qp-root">

	<!-- TOP BAR -->
	<div class="qp-card">
		<div class="qp-topbar">
			<div>
				<div class="qp-topbar-h">Question User Details</div>
				<div class="qp-topbar-s">Full breakdown of question &amp; user results</div>
			</div>
			<a class="qp-back" href="<?php echo site_url('admin/questions/detail/' . (int)$detail_question->id); ?>">
				<svg width="12" height="12" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2.5">
					<path d="M10 13L5 8l5-5" />
				</svg>
				Back
			</a>
		</div>
	</div>

	<!-- QUESTION INFO -->
	<div class="qp-card">
		<div class="qp-qbody">

			<div class="qp-qtitle"><?php echo html_escape($detail_question->question); ?></div>

			<div class="qp-badges">
				<span class="qp-badge <?php echo !empty($detail_question->answer_key) ? 'bsaved' : 'bpend'; ?>">
					<span class="qp-dot"></span>
					<?php echo !empty($detail_question->answer_key) ? 'Answer saved' : 'Answer pending'; ?>
				</span>
				<span class="qp-badge b<?php echo $timing_class; ?>">
					<span class="qp-dot"></span><?php echo $timing_label; ?>
				</span>
				<span class="qp-badge bneutral"><?php echo ucfirst((string)$detail_question->status); ?></span>
			</div>

			<div class="qp-actions">
				<a class="qp-btn qp-btn-edit" href="<?php echo site_url('admin/questions/edit/' . (int)$detail_question->id); ?>">
					<svg width="12" height="12" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2">
						<path d="M11 2l3 3-9 9H2v-3l9-9z" />
					</svg>Edit
				</a>
				<a class="qp-btn qp-btn-view" href="<?php echo site_url('admin/questions/detail/' . (int)$detail_question->id); ?>">
					<svg width="12" height="12" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2">
						<circle cx="8" cy="8" r="3" />
						<path d="M1 8C2.5 4.5 5 2.5 8 2.5S13.5 4.5 15 8c-1.5 3.5-4 5.5-7 5.5S2.5 11.5 1 8z" />
					</svg>View
				</a>
			</div>

			<!-- Stats: 3 compact columns -->
			<div class="qp-stats">
				<div class="qp-stat">
					<div class="qp-stat-l">Real Users</div>
					<div class="qp-stat-v"><?php echo $real_users; ?></div>
					<div class="qp-stat-s">Trades</div>
				</div>
				<div class="qp-stat">
					<div class="qp-stat-l">Trade Qty</div>
					<div class="qp-tqty">
						<span class="y">Y&nbsp;<?php echo $yes_qty; ?></span>
						<span class="sp">/</span>
						<span class="n">N&nbsp;<?php echo $no_qty; ?></span>
					</div>
					<div class="qp-stat-s">Total qty</div>
				</div>
				<div class="qp-stat">
					<div class="qp-stat-l">Answer Key</div>
					<div class="qp-stat-v" style="font-size:15px"><?php echo $answer_key_label; ?></div>
					<div class="qp-stat-s">Final result</div>
				</div>
			</div>

			<!-- Detail grid: 2 col -->
			<div class="qp-dgrid">
				<div class="qp-di">
					<div class="qp-di-l">Yes Price</div>
					<div class="qp-di-v yes">₹<?php echo number_format((float)$detail_question->yes_price, 2); ?></div>
					<div class="qp-di-s"><?php echo $yes_users; ?> users · <?php echo $yes_qty; ?> qty</div>
					<div class="qp-di-v" style="font-size: 13px; font-weight: 700; margin-top: 6px; color: var(--green);">Total: ₹<?php echo number_format($yes_spent, 2); ?></div>
				</div>
				<div class="qp-di">
					<div class="qp-di-l">No Price</div>
					<div class="qp-di-v no">₹<?php echo number_format((float)$detail_question->no_price, 2); ?></div>
					<div class="qp-di-s"><?php echo $no_users; ?> users · <?php echo $no_qty; ?> qty</div>
					<div class="qp-di-v" style="font-size: 13px; font-weight: 700; margin-top: 6px; color: var(--red);">Total: ₹<?php echo number_format($no_spent, 2); ?></div>
				</div>
				<div class="qp-di">
					<div class="qp-di-l">Market Total</div>
					<div class="qp-di-v">₹<?php echo number_format($market_total, 2); ?></div>
					<div class="qp-di-s">Spread ₹<?php echo number_format($spread_total, 2); ?></div>
				</div>

				<div class="qp-di">
					<div class="qp-di-l">Start Time</div>
					<div class="qp-di-v" style="font-size:12px;letter-spacing:-.01em"><?php echo !empty($detail_question->start_time) ? html_escape($detail_question->start_time) : 'Not set'; ?></div>
					<div class="qp-di-s">Opens</div>
				</div>
				<div class="qp-di">
					<div class="qp-di-l">Close Time</div>
					<div class="qp-di-v" style="font-size:12px;letter-spacing:-.01em"><?php echo !empty($detail_question->end_time) ? html_escape($detail_question->end_time) : 'Not set'; ?></div>
					<div class="qp-di-s">Closes</div>
				</div>
			</div>

		</div>
	</div>

	<!-- USERS LIST -->
	<div class="qp-card">

		<div class="qp-sechdr">
			<div>
				<div class="qp-sechdr-t">Users List</div>
				<div class="qp-sechdr-s">Win first · lose · pending</div>
			</div>
			<span class="qp-cnt" id="qpTotal">…</span>
		</div>

		<!-- Filters -->
		<div class="qp-filters">
			<!-- Search -->
			<div class="qp-sw">
				<svg class="qp-si" width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2">
					<circle cx="6.5" cy="6.5" r="4.5" />
					<path d="M10.5 10.5l3.5 3.5" />
				</svg>
				<input id="qpSearch" class="qp-sinput" type="text" placeholder="Search name, mobile, email…" autocomplete="off">
			</div>
			<!-- Filter row -->
			<div class="qp-frow">
				<div class="fd-from">
					<div class="qp-flbl">From</div>
					<input type="datetime-local" id="qpFrom" class="qp-fi">
				</div>
				<div class="fd-to">
					<div class="qp-flbl">To</div>
					<input type="datetime-local" id="qpTo" class="qp-fi">
				</div>
				<div class="fd-answer">
					<div class="qp-flbl">Answer</div>
					<select id="qpAnswer" class="qp-fs">
						<option value="all">All</option>
						<option value="yes">YES</option>
						<option value="no">NO</option>
					</select>
				</div>
				<div class="fd-result">
					<div class="qp-flbl">Result</div>
					<select id="qpResult" class="qp-fs">
						<option value="all">All</option>
						<option value="win">Win</option>
						<option value="lose">Lose</option>
						<option value="pending">Pending</option>
					</select>
				</div>
				<div class="fd-rows">
					<div class="qp-flbl">Rows</div>
					<select id="qpPerPage" class="qp-fs">
						<option value="10" selected>10</option>
						<option value="25">25</option>
						<option value="50">50</option>
						<option value="100">100</option>
					</select>
				</div>
				<div class="fd-reset" style="display:flex;align-items:flex-end">
					<button type="button" class="qp-rbtn" id="qpReset">
						<svg width="12" height="12" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2">
							<path d="M13.5 2.5A7 7 0 1 0 14.5 9" />
							<path d="M14.5 2.5v4h-4" />
						</svg>
						Reset
					</button>
				</div>
			</div>
		</div>

		<!-- Desktop table -->
		<div class="qp-twrap">
			<table class="qp-table">
				<thead>
					<tr>
						<th>User</th>
						<th>Answer</th>
						<th>Price × Qty</th>
						<th>Spend</th>
						<th>Result</th>
						<th>Winning</th>
						<th>Time</th>
					</tr>
				</thead>
				<tbody id="qpTableBody">
					<tr>
						<td colspan="7" class="qp-empty">Loading…<span class="qp-dots"><span></span><span></span><span></span></span></td>
					</tr>
				</tbody>
			</table>
		</div>

		<!-- Mobile cards -->
		<div id="qpMobileList" class="qp-ucards"></div>

		<!-- Pagination -->
		<div class="qp-footer">
			<div class="qp-pginfo" id="qpPageInfo">Loading…</div>
			<div class="qp-pager">
				<button type="button" class="qp-pbtn" id="qpPrev" disabled aria-label="Previous">
					<svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2.5">
						<path d="M10 13L5 8l5-5" />
					</svg>
				</button>
				<div class="qp-pnums" id="qpPageNums"></div>
				<button type="button" class="qp-pbtn" id="qpNext" disabled aria-label="Next">
					<svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2.5">
						<path d="M6 3l5 5-5 5" />
					</svg>
				</button>
			</div>
		</div>

	</div><!-- /.qp-card -->

</div><!-- /.qp-root -->

<script>
	(function() {
		'use strict';
		var EP = '<?php echo site_url('admin/questions/detail-users-data/' . (int)$detail_question->id); ?>';

		var $ = function(id) {
			return document.getElementById(id);
		};
		var tbody = $('qpTableBody'),
			mlist = $('qpMobileList'),
			pgInfo = $('qpPageInfo'),
			tot = $('qpTotal'),
			prev = $('qpPrev'),
			next = $('qpNext'),
			pnums = $('qpPageNums'),
			srch = $('qpSearch'),
			ansSel = $('qpAnswer'),
			resSel = $('qpResult'),
			ppSel = $('qpPerPage'),
			fromI = $('qpFrom'),
			toI = $('qpTo'),
			rst = $('qpReset');

		var S = {
			page: 1,
			per_page: 10,
			search: '',
			answer: 'all',
			result: 'all',
			from: '',
			to: '',
			total: 0
		};
		var dbt = null;

		function esc(v) {
			return String(v == null ? '' : v).replace(/[&<>"']/g, function(c) {
				return {
					'&': '&amp;',
					'<': '&lt;',
					'>': '&gt;',
					'"': '&quot;',
					"'": '&#39;'
				} [c];
			});
		}

		function normDT(v) {
			return v ? v.replace('T', ' ') + ':00' : '';
		}

		function av(n) {
			var p = String(n || '?').trim().split(/\s+/);
			return (p[0][0] + (p[1] ? p[1][0] : '')).toUpperCase();
		}

		function go() {
			load();
			var p = new URLSearchParams();
			p.set('page', S.page);
			p.set('per_page', S.per_page);
			p.set('search', S.search);
			p.set('answer', S.answer);
			p.set('result', S.result);
			p.set('from', normDT(S.from));
			p.set('to', normDT(S.to));
			fetch(EP + '?' + p)
				.then(function(r) {
					return r.json();
				})
				.then(function(d) {
					if (!d || d.status !== 'ok') {
						err('Error loading data.');
						return;
					}
					S.total = parseInt(d.total) || 0;
					render(d.rows || []);
					foot();
				}).catch(function() {
					err('Network error.');
				});
		}

		function render(rows) {
			if (!rows.length) {
				tbody.innerHTML = '<tr><td colspan="7" class="qp-empty">No users match your filters.</td></tr>';
				mlist.innerHTML = '<div class="qp-empty">No users match your filters.</div>';
				return;
			}
			tbody.innerHTML = rows.map(function(r) {
				return '<tr>' +
					'<td><div class="qt-n">' + esc(r.name) + '</div>' +
					'<span class="qt-s">' + esc(r.email) + '</span>' +
					'<span class="qt-s">' + esc(r.mobile) + '</span></td>' +
					'<td><span class="qp-pill ' + esc(r.answer) + '">' + esc(r.answer).toUpperCase() + '</span></td>' +
					'<td style="font-weight:500">₹' + esc(r.price) + ' × ' + esc(r.quantity) + '</td>' +
					'<td class="qt-sp">₹' + esc(r.stake_amount) + '</td>' +
					'<td><span class="qp-pill ' + esc(r.result_status) + '">' + esc(r.result_status) + '</span></td>' +
					'<td class="qt-w">₹' + esc(r.win_amount) + '</td>' +
					'<td style="color:var(--tx3);font-size:11px">' + esc(r.created_at) + '</td>' +
					'</tr>';
			}).join('');

			mlist.innerHTML = rows.map(function(r) {
				return '<div class="qp-ucard">' +
					'<div class="qp-uc-top">' +
					'<div class="qp-uc-av">' + av(r.name) + '</div>' +
					'<div class="qp-uc-m">' +
					'<div class="qp-uc-n">' + esc(r.name) + '</div>' +
					'<div class="qp-uc-c">' + esc(r.email) + ' · ' + esc(r.mobile) + '</div>' +
					'</div>' +
					'<div class="qp-uc-pp">' +
					'<span class="qp-pill ' + esc(r.answer) + '">' + esc(r.answer).toUpperCase() + '</span>' +
					'<span class="qp-pill ' + esc(r.result_status) + '">' + esc(r.result_status) + '</span>' +
					'</div>' +
					'</div>' +
					'<div class="qp-uc-body">' +
					'<div><div class="qp-uc-fl">Price × Qty</div><div class="qp-uc-fv">₹' + esc(r.price) + ' × ' + esc(r.quantity) + '</div></div>' +
					'<div><div class="qp-uc-fl">Spend</div><div class="qp-uc-fv sp">₹' + esc(r.stake_amount) + '</div></div>' +
					'<div><div class="qp-uc-fl">Winning</div><div class="qp-uc-fv wi">₹' + esc(r.win_amount) + '</div></div>' +
					'<div><div class="qp-uc-fl">Time</div><div class="qp-uc-fv ti">' + esc(r.created_at) + '</div></div>' +
					'</div></div>';
			}).join('');
		}

		function foot() {
			var t = S.total,
				pp = parseInt(S.per_page);
			var s = t ? ((S.page - 1) * pp) + 1 : 0,
				e = Math.min(S.page * pp, t);
			pgInfo.textContent = t ? 'Showing ' + s + '–' + e + ' of ' + t + ' users' : 'No users found';
			tot.textContent = t + ' user' + (t === 1 ? '' : 's');
			prev.disabled = S.page <= 1;
			next.disabled = e >= t;
			buildPages(t, pp);
		}

		function buildPages(t, pp) {
			var tp = Math.max(1, Math.ceil(t / pp)),
				cur = S.page,
				arr = [];
			if (tp <= 7) {
				for (var i = 1; i <= tp; i++) arr.push(i);
			} else {
				arr = [1];
				if (cur > 3) arr.push('…');
				for (var j = Math.max(2, cur - 1); j <= Math.min(tp - 1, cur + 1); j++) arr.push(j);
				if (cur < tp - 2) arr.push('…');
				arr.push(tp);
			}
			pnums.innerHTML = arr.map(function(p) {
				if (p === '…') return '<span style="padding:0 2px;color:var(--tx3);font-size:13px">…</span>';
				return '<button type="button" class="qp-pnum' + (p === cur ? ' active' : '') + '" data-p="' + p + '">' + p + '</button>';
			}).join('');
			pnums.querySelectorAll('.qp-pnum').forEach(function(b) {
				b.addEventListener('click', function() {
					var pg = parseInt(this.getAttribute('data-p'));
					if (pg !== S.page) {
						S.page = pg;
						go();
					}
				});
			});
		}

		function load() {
			var d = '<span class="qp-dots"><span></span><span></span><span></span></span>';
			tbody.innerHTML = '<tr><td colspan="7" class="qp-empty">Loading…' + d + '</td></tr>';
			mlist.innerHTML = '<div class="qp-empty">Loading…' + d + '</div>';
			pgInfo.textContent = 'Loading…';
			tot.textContent = '…';
		}

		function err(m) {
			tbody.innerHTML = '<tr><td colspan="7" class="qp-empty" style="color:var(--red)">' + esc(m) + '</td></tr>';
			mlist.innerHTML = '<div class="qp-empty" style="color:var(--red)">' + esc(m) + '</div>';
		}

		srch.addEventListener('input', function() {
			clearTimeout(dbt);
			dbt = setTimeout(function() {
				S.search = srch.value.trim();
				S.page = 1;
				go();
			}, 400);
		});
		ansSel.addEventListener('change', function() {
			S.answer = this.value;
			S.page = 1;
			go();
		});
		resSel.addEventListener('change', function() {
			S.result = this.value;
			S.page = 1;
			go();
		});
		ppSel.addEventListener('change', function() {
			S.per_page = parseInt(this.value);
			S.page = 1;
			go();
		});

		function onF() {
			S.from = fromI.value;
			S.page = 1;
			go();
		}

		function onT() {
			S.to = toI.value;
			S.page = 1;
			go();
		}
		fromI.addEventListener('change', onF);
		fromI.addEventListener('input', onF);
		toI.addEventListener('change', onT);
		toI.addEventListener('input', onT);

		prev.addEventListener('click', function() {
			if (S.page > 1) {
				S.page--;
				go();
			}
		});
		next.addEventListener('click', function() {
			if ((S.page * S.per_page) < S.total) {
				S.page++;
				go();
			}
		});

		rst.addEventListener('click', function() {
			srch.value = '';
			ansSel.value = 'all';
			resSel.value = 'all';
			ppSel.value = '10';
			fromI.value = '';
			toI.value = '';
			S = {
				page: 1,
				per_page: 10,
				search: '',
				answer: 'all',
				result: 'all',
				from: '',
				to: '',
				total: 0
			};
			go();
		});

		go();
	}());
</script>