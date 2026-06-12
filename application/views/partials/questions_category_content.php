<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900;1,400&display=swap" rel="stylesheet">

<style>
	:root {
		--ql-bg: #f0f4ff;
		--ql-surface: #ffffff;
		--ql-surface2: #f7f9fc;
		--ql-ink: #0d1321;
		--ql-text: #3d4a63;
		--ql-muted: #7a8499;
		--ql-line: #e4e9f4;
		--ql-blue: #2f5be8;
		--ql-blue-bg: #eef2fd;
		--ql-blue-border: #c5d0f9;
		--ql-green: #0fa966;
		--ql-green-bg: #e8f9f1;
		--ql-green-border: #a3dfc2;
		--ql-red: #dc3545;
		--ql-red-bg: #fdeef0;
		--ql-red-border: #f4b8bf;
		--ql-amber: #c97c10;
		--ql-amber-bg: #fef5e4;
		--ql-amber-border: #f5d08a;
		--ql-r: 18px;
		--ql-r-sm: 12px;
		--ql-r-pill: 999px;
	}

	* {
		box-sizing: border-box;
	}

	.ql-wrap {
		font-family: 'Roboto', sans-serif;
		display: flex;
		flex-direction: column;
		gap: 16px;
		width: 100%;
	}

	/* ══════════════════════════════════
	   HEADER
	══════════════════════════════════ */
	.ql-header {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 14px;
		flex-wrap: wrap;
		padding: 22px 24px;
		background: linear-gradient(135deg, #2f5be8 0%, #6f8cf5 100%);
		border-radius: var(--ql-r);
		position: relative;
		overflow: hidden;
	}

	.ql-header::after {
		content: '';
		position: absolute;
		top: -40px;
		right: -40px;
		width: 160px;
		height: 160px;
		border-radius: 50%;
		background: rgba(255, 255, 255, 0.08);
	}

	.ql-header::before {
		content: '';
		position: absolute;
		bottom: -20px;
		left: 40%;
		width: 120px;
		height: 120px;
		border-radius: 50%;
		background: rgba(255, 255, 255, 0.05);
	}

	.ql-header-left {
		display: flex;
		align-items: center;
		gap: 16px;
		position: relative;
		z-index: 1;
	}

	.ql-header-icon {
		width: 48px;
		height: 48px;
		border-radius: 14px;
		background: rgba(255, 255, 255, 0.18);
		border: 1.5px solid rgba(255, 255, 255, 0.35);
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 20px;
		color: #fff;
		flex-shrink: 0;
	}

	.ql-header-title {
		font-size: clamp(18px, 2.5vw, 24px);
		font-weight: 700;
		letter-spacing: -0.02em;
		color: #fff;
		line-height: 1.2;
	}

	.ql-header-sub {
		font-size: 13px;
		color: rgba(255, 255, 255, 0.82);
		margin-top: 3px;
	}

	.ql-live-badge {
		display: inline-flex;
		align-items: center;
		gap: 8px;
		padding: 8px 16px;
		border-radius: var(--ql-r-pill);
		font-size: 11px;
		font-weight: 700;
		letter-spacing: 0.05em;
		text-transform: uppercase;
		background: rgba(255, 255, 255, 0.18);
		color: #fff;
		border: 1px solid rgba(255, 255, 255, 0.35);
		position: relative;
		z-index: 1;
	}

	.ql-live-dot {
		width: 7px;
		height: 7px;
		border-radius: 50%;
		background: #4ef0a3;
		animation: ql-pulse 1.8s ease-in-out infinite;
	}

	@keyframes ql-pulse {
		0% {
			box-shadow: 0 0 0 0 rgba(78, 240, 163, 0.7);
		}

		70% {
			box-shadow: 0 0 0 6px rgba(78, 240, 163, 0);
		}

		100% {
			box-shadow: 0 0 0 0 rgba(78, 240, 163, 0);
		}
	}

	/* ══════════════════════════════════
	   STAT CARDS
	══════════════════════════════════ */
	.ql-stats {
		display: grid;
		grid-template-columns: repeat(4, 1fr);
		gap: 12px;
	}

	.ql-stat {
		border-radius: var(--ql-r-sm);
		padding: 14px 14px 12px;
		position: relative;
		overflow: hidden;
		display: flex;
		flex-direction: column;
		gap: 0;
	}

	/* top accent stripe */
	.ql-stat::before {
		content: '';
		position: absolute;
		top: 0;
		left: 0;
		right: 0;
		height: 3px;
		border-radius: var(--ql-r-sm) var(--ql-r-sm) 0 0;
	}

	/* icon in top-right corner */
	.ql-stat-icon {
		position: absolute;
		top: 14px;
		right: 12px;
		width: 32px;
		height: 32px;
		border-radius: 9px;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 14px;
	}

	/* big number */
	.ql-stat-val {
		font-size: 32px;
		font-weight: 900;
		letter-spacing: -0.05em;
		line-height: 1;
		margin-top: 10px;
		margin-bottom: 6px;
	}

	/* label below number */
	.ql-stat-label {
		font-size: 11px;
		font-weight: 600;
		text-transform: uppercase;
		letter-spacing: 0.07em;
		color: var(--ql-muted);
	}

	/* Total */
	.ql-stat.s-total {
		background: #fff;
		border: 1.5px solid var(--ql-line);
	}

	.ql-stat.s-total::before {
		background: linear-gradient(90deg, #2f5be8, #6f8cf5);
	}

	.ql-stat.s-total .ql-stat-icon {
		background: var(--ql-blue-bg);
		color: var(--ql-blue);
	}

	.ql-stat.s-total .ql-stat-val {
		color: var(--ql-ink);
	}

	/* Answered */
	.ql-stat.s-answered {
		background: linear-gradient(160deg, var(--ql-blue-bg) 0%, #fff 80%);
		border: 1.5px solid var(--ql-blue-border);
	}

	.ql-stat.s-answered::before {
		background: linear-gradient(90deg, #2f5be8, #6f8cf5);
	}

	.ql-stat.s-answered .ql-stat-icon {
		background: rgba(47, 91, 232, 0.12);
		color: var(--ql-blue);
	}

	.ql-stat.s-answered .ql-stat-val {
		color: var(--ql-blue);
	}

	/* Correct */
	.ql-stat.s-correct {
		background: linear-gradient(160deg, var(--ql-green-bg) 0%, #fff 80%);
		border: 1.5px solid var(--ql-green-border);
	}

	.ql-stat.s-correct::before {
		background: linear-gradient(90deg, #0fa966, #4ddb9a);
	}

	.ql-stat.s-correct .ql-stat-icon {
		background: rgba(15, 169, 102, 0.12);
		color: var(--ql-green);
	}

	.ql-stat.s-correct .ql-stat-val {
		color: var(--ql-green);
	}

	/* Wrong */
	.ql-stat.s-wrong {
		background: linear-gradient(160deg, var(--ql-red-bg) 0%, #fff 80%);
		border: 1.5px solid var(--ql-red-border);
	}

	.ql-stat.s-wrong::before {
		background: linear-gradient(90deg, #dc3545, #ff7b89);
	}

	.ql-stat.s-wrong .ql-stat-icon {
		background: rgba(220, 53, 69, 0.10);
		color: var(--ql-red);
	}

	.ql-stat.s-wrong .ql-stat-val {
		color: var(--ql-red);
	}

	/* ══════════════════════════════════
	   QUESTION CARDS
	══════════════════════════════════ */
	.ql-list {
		display: flex;
		flex-direction: column;
		gap: 14px;
	}

	.ql-card {
		display: block;
		text-decoration: none;
		color: var(--ql-ink);
		background: var(--ql-surface);
		border: 1.5px solid var(--ql-line);
		border-radius: var(--ql-r);
		overflow: hidden;
		transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
		position: relative;
	}

	.ql-card:hover {
		transform: translateY(-3px);
		box-shadow: 0 10px 32px rgba(47, 91, 232, 0.13);
		border-color: var(--ql-blue-border);
	}

	.ql-card:active {
		transform: translateY(-1px) scale(0.995);
	}

	/* left accent bar */
	.ql-card::after {
		content: '';
		position: absolute;
		left: 0;
		top: 0;
		bottom: 0;
		width: 5px;
	}

	.ql-card.state-pending::after {
		background: linear-gradient(180deg, #2f5be8, #6f8cf5);
	}

	.ql-card.state-correct {
		border-color: var(--ql-green-border);
		background: linear-gradient(135deg, #fff 0%, #f4fdf8 100%);
	}

	.ql-card.state-correct::after {
		background: linear-gradient(180deg, #0fa966, #4ddb9a);
	}

	.ql-card.state-wrong {
		border-color: var(--ql-red-border);
		background: linear-gradient(135deg, #fff 0%, #fdf4f5 100%);
	}

	.ql-card.state-wrong::after {
		background: linear-gradient(180deg, #dc3545, #ff7b89);
	}

	.ql-card-body {
		padding: 18px 20px 18px 24px;
	}

	/* title row */
	.ql-card-row {
		display: flex;
		align-items: flex-start;
		justify-content: space-between;
		gap: 12px;
		margin-bottom: 14px;
	}

	.ql-card-title-group {
		display: flex;
		align-items: flex-start;
		gap: 10px;
		flex: 1;
		min-width: 0;
	}

	.ql-idx {
		width: 30px;
		height: 30px;
		border-radius: 9px;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 12px;
		font-weight: 700;
		flex-shrink: 0;
		background: var(--ql-blue-bg);
		color: var(--ql-blue);
		border: 1px solid var(--ql-blue-border);
	}

	.ql-card.state-correct .ql-idx {
		background: var(--ql-green-bg);
		color: var(--ql-green);
		border-color: var(--ql-green-border);
	}

	.ql-card.state-wrong .ql-idx {
		background: var(--ql-red-bg);
		color: var(--ql-red);
		border-color: var(--ql-red-border);
	}

	.ql-question-text {
		font-size: 15px;
		font-weight: 600;
		line-height: 1.55;
		color: var(--ql-ink);
		flex: 1;
		min-width: 0;
	}

	.ql-status-tag {
		display: inline-flex;
		align-items: center;
		gap: 5px;
		padding: 6px 12px;
		border-radius: var(--ql-r-pill);
		font-size: 11px;
		font-weight: 700;
		text-transform: uppercase;
		letter-spacing: 0.05em;
		white-space: nowrap;
		flex-shrink: 0;
		border: 1px solid transparent;
	}

	.tag-pending {
		background: var(--ql-blue-bg);
		color: var(--ql-blue);
		border-color: var(--ql-blue-border);
	}

	.tag-review {
		background: var(--ql-amber-bg);
		color: var(--ql-amber);
		border-color: var(--ql-amber-border);
	}

	.tag-not-open {
		background: var(--ql-surface2);
		color: var(--ql-muted);
		border-color: var(--ql-line);
	}

	.tag-won {
		background: var(--ql-green);
		color: #fff;
		border-color: var(--ql-green);
	}

	.tag-lost {
		background: var(--ql-red-bg);
		color: var(--ql-red);
		border-color: var(--ql-red-border);
	}

	/* ── YES / NO chips — always 2-col grid ── */
	.ql-odds-row {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 10px;
		margin-bottom: 12px;
	}

	.ql-odd-chip {
		display: flex;
		flex-direction: row;
		align-items: center;
		justify-content: space-between;
		padding: 11px 16px;
		border-radius: var(--ql-r-sm);
	}

	.ql-odd-chip.yes {
		background: var(--ql-green-bg);
		border: 1px solid var(--ql-green-border);
	}

	.ql-odd-chip.no {
		background: var(--ql-red-bg);
		border: 1px solid var(--ql-red-border);
	}

	.ql-odd-chip .chip-label {
		font-size: 11px;
		font-weight: 700;
		letter-spacing: 0.08em;
	}

	.ql-odd-chip.yes .chip-label,
	.ql-odd-chip.yes .chip-val {
		color: var(--ql-green);
	}

	.ql-odd-chip.no .chip-label,
	.ql-odd-chip.no .chip-val {
		color: var(--ql-red);
	}

	.ql-odd-chip .chip-val {
		font-weight: 800;
		font-size: 18px;
	}

	/* ══════════════════════════════════
	   WIN HERO BANNER
	══════════════════════════════════ */
	.ql-win-hero {
		position: relative;
		border-radius: var(--ql-r-sm);
		overflow: hidden;
		margin-bottom: 12px;
		background: linear-gradient(135deg, #0c2318 0%, #0d2d1a 50%, #112b1e 100%);
		border: 1.5px solid rgba(78, 240, 163, 0.25);
		padding: 14px 18px;
	}

	/* shimmer sweep */
	.ql-win-hero::before {
		content: '';
		position: absolute;
		top: -50%;
		left: -60%;
		width: 55%;
		height: 200%;
		background: linear-gradient(105deg, transparent 30%, rgba(78, 240, 163, 0.10) 50%, transparent 70%);
		animation: ql-shimmer 3.5s ease-in-out infinite;
		pointer-events: none;
	}

	@keyframes ql-shimmer {
		0% {
			left: -60%;
		}

		100% {
			left: 120%;
		}
	}

	/* dot grid */
	.ql-win-hero::after {
		content: '';
		position: absolute;
		inset: 0;
		background-image:
			radial-gradient(rgba(78, 240, 163, 0.12) 1px, transparent 1px);
		background-size: 18px 18px;
		pointer-events: none;
	}

	.ql-win-hero-inner {
		position: relative;
		z-index: 1;
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 16px;
	}

	.ql-win-hero-left {
		display: flex;
		flex-direction: column;
		gap: 3px;
		min-width: 0;
	}

	.ql-win-hero-eyebrow {
		display: flex;
		align-items: center;
		gap: 6px;
		font-size: 10px;
		font-weight: 700;
		letter-spacing: 0.1em;
		text-transform: uppercase;
		color: #4ef0a3;
	}

	.ql-win-glow-dot {
		width: 6px;
		height: 6px;
		border-radius: 50%;
		background: #4ef0a3;
		flex-shrink: 0;
		box-shadow: 0 0 6px 2px rgba(78, 240, 163, 0.55);
		animation: ql-glow 2s ease-in-out infinite;
	}

	@keyframes ql-glow {

		0%,
		100% {
			box-shadow: 0 0 5px 1px rgba(78, 240, 163, 0.5);
		}

		50% {
			box-shadow: 0 0 10px 3px rgba(78, 240, 163, 0.85);
		}
	}

	.ql-win-hero-label {
		font-size: 14px;
		font-weight: 700;
		color: #fff;
		white-space: nowrap;
	}

	.ql-win-hero-sub {
		font-size: 11px;
		color: rgba(255, 255, 255, 0.42);
		white-space: nowrap;
	}

	.ql-win-hero-right {
		display: flex;
		flex-direction: column;
		align-items: flex-end;
		gap: 3px;
		flex-shrink: 0;
	}

	.ql-win-amount {
		font-size: 30px;
		font-weight: 900;
		letter-spacing: -0.04em;
		line-height: 1;
		color: #4ef0a3;
		text-shadow: 0 0 18px rgba(78, 240, 163, 0.30);
	}

	.ql-win-amount .win-plus {
		font-size: 18px;
		font-weight: 900;
		color: #4ef0a3;
		opacity: 0.65;
	}

	.ql-win-per-qty {
		font-size: 10px;
		font-weight: 600;
		color: rgba(78, 240, 163, 0.55);
		letter-spacing: 0.04em;
		white-space: nowrap;
	}

	/* ── traders strip ── */
	.ql-users-strip {
		display: inline-flex;
		align-items: center;
		gap: 7px;
		font-size: 12px;
		color: var(--ql-text);
		font-weight: 500;
		margin-bottom: 14px;
	}

	.ql-users-strip strong {
		color: var(--ql-ink);
		font-weight: 700;
	}

	/* ── card footer ── */
	.ql-card-footer {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 10px;
		padding-top: 12px;
		border-top: 1px solid var(--ql-line);
	}

	.ql-card.state-correct .ql-card-footer {
		border-color: var(--ql-green-border);
	}

	.ql-card.state-wrong .ql-card-footer {
		border-color: var(--ql-red-border);
	}

	.ql-footer-left {
		display: flex;
		align-items: center;
		gap: 6px;
		font-size: 12px;
		color: var(--ql-muted);
		font-weight: 500;
	}

	.ql-footer-right {
		display: flex;
		align-items: center;
		gap: 12px;
		flex-shrink: 0;
	}

	.ql-payout {
		font-size: 14px;
		font-weight: 800;
	}

	.ql-payout.win {
		color: var(--ql-green);
	}

	.ql-payout.loss {
		color: var(--ql-red);
	}

	.ql-payout.neutral {
		color: var(--ql-text);
	}

	.ql-trade-btn {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		font-size: 12px;
		font-weight: 700;
		color: #fff;
		background: var(--ql-blue);
		padding: 8px 16px;
		border-radius: var(--ql-r-pill);
		white-space: nowrap;
		transition: transform 0.15s ease, box-shadow 0.15s ease;
	}

	.ql-trade-btn:hover {
		box-shadow: 0 4px 14px rgba(47, 91, 232, 0.38);
		transform: translateX(2px);
	}

	/* ── empty states ── */
	.ql-empty {
		text-align: center;
		padding: 64px 24px;
		background: var(--ql-surface);
		border: 1.5px dashed var(--ql-line);
		border-radius: var(--ql-r);
	}

	.ql-empty-icon {
		width: 56px;
		height: 56px;
		border-radius: 18px;
		background: var(--ql-blue-bg);
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 20px;
		color: var(--ql-blue);
		margin: 0 auto 16px;
		border: 1.5px solid var(--ql-blue-border);
	}

	.ql-empty h3 {
		font-size: 18px;
		font-weight: 700;
		color: var(--ql-ink);
		margin-bottom: 8px;
	}

	.ql-empty p {
		font-size: 14px;
		color: var(--ql-muted);
		max-width: 300px;
		margin: 0 auto;
		line-height: 1.7;
	}

	/* ══════════════════════════════════
	   MOBILE  ≤ 680px
	══════════════════════════════════ */
	@media (max-width: 680px) {

		/* header */
		.ql-header {
			padding: 14px 16px;
			border-radius: var(--ql-r-sm);
		}

		.ql-header-left {
			gap: 10px;
		}

		.ql-header-icon {
			width: 38px;
			height: 38px;
			font-size: 15px;
			border-radius: 10px;
		}

		.ql-header-title {
			font-size: 16px;
		}

		.ql-header-sub {
			font-size: 11px;
		}

		.ql-live-badge {
			padding: 6px 11px;
			font-size: 10px;
		}

		/* stats — 2×2 grid, compact */
		.ql-stats {
			grid-template-columns: repeat(2, 1fr);
			gap: 8px;
		}

		.ql-stat {
			padding: 11px 12px 10px;
		}

		.ql-stat-icon {
			width: 28px;
			height: 28px;
			border-radius: 8px;
			font-size: 13px;
			top: 10px;
			right: 10px;
		}

		.ql-stat-val {
			font-size: 26px;
			margin-top: 8px;
			margin-bottom: 4px;
		}

		.ql-stat-label {
			font-size: 10px;
		}

		/* card */
		.ql-card-body {
			padding: 14px 14px 14px 18px;
		}

		.ql-card-row {
			margin-bottom: 12px;
			gap: 8px;
		}

		.ql-idx {
			width: 26px;
			height: 26px;
			font-size: 11px;
			border-radius: 7px;
		}

		.ql-question-text {
			font-size: 13px;
			line-height: 1.5;
		}

		.ql-status-tag {
			font-size: 10px;
			padding: 5px 10px;
		}

		/* chips — always 2-col, no exceptions */
		.ql-odds-row {
			gap: 7px;
		}

		.ql-odd-chip {
			padding: 9px 12px;
		}

		.ql-odd-chip .chip-val {
			font-size: 15px;
		}

		.ql-odd-chip .chip-label {
			font-size: 10px;
		}

		/* win hero — stack on very small, still readable */
		.ql-win-hero {
			padding: 12px 13px;
			margin-bottom: 10px;
		}

		.ql-win-hero-inner {
			gap: 10px;
		}

		.ql-win-hero-eyebrow {
			font-size: 9px;
		}

		.ql-win-hero-label {
			font-size: 13px;
		}

		.ql-win-hero-sub {
			font-size: 10px;
		}

		.ql-win-amount {
			font-size: 26px;
		}

		.ql-win-amount .win-plus {
			font-size: 16px;
		}

		.ql-win-per-qty {
			font-size: 9px;
		}

		/* traders + footer */
		.ql-users-strip {
			font-size: 11px;
			margin-bottom: 11px;
		}

		.ql-card-footer {
			flex-wrap: nowrap;
			gap: 6px;
			padding-top: 10px;
		}

		.ql-footer-left {
			font-size: 11px;
		}

		.ql-payout {
			font-size: 13px;
		}

		.ql-trade-btn {
			font-size: 11px;
			padding: 7px 13px;
		}

		.ql-footer-right {
			gap: 8px;
		}
	}
</style>

<div class="ql-wrap">

	<?php if ($selected_category && !empty($selected_category->questions)): ?>

		<!-- Header -->
		<div class="ql-header">
			<div class="ql-header-left">
				<div class="ql-header-icon"><i class="fa-solid fa-bolt"></i></div>
				<div>
					<div class="ql-header-title"><?php echo html_escape($selected_category->name); ?></div>
					<div class="ql-header-sub">Select any question below to trade</div>
				</div>
			</div>
			<div class="ql-live-badge">
				<div class="ql-live-dot"></div>
				Live
			</div>
		</div>

		<!-- Stats -->
		<div class="ql-stats">
			<div class="ql-stat s-total">
				<div class="ql-stat-icon"><i class="fa-solid fa-layer-group"></i></div>
				<div class="ql-stat-val"><?php echo (int)$total_questions; ?></div>
				<div class="ql-stat-label">Total</div>
			</div>
			<div class="ql-stat s-answered">
				<div class="ql-stat-icon"><i class="fa-solid fa-pen-to-square"></i></div>
				<div class="ql-stat-val"><?php echo (int)$answered_count; ?></div>
				<div class="ql-stat-label">Answered</div>
			</div>
			<div class="ql-stat s-correct">
				<div class="ql-stat-icon"><i class="fa-solid fa-circle-check"></i></div>
				<div class="ql-stat-val"><?php echo (int)$correct_count; ?></div>
				<div class="ql-stat-label">Correct</div>
			</div>
			<div class="ql-stat s-wrong">
				<div class="ql-stat-icon"><i class="fa-solid fa-circle-xmark"></i></div>
				<div class="ql-stat-val"><?php echo (int)$wrong_count; ?></div>
				<div class="ql-stat-label">Wrong</div>
			</div>
		</div>

		<!-- Question list -->
		<div class="ql-list">
			<?php foreach ($selected_category->questions as $index => $question_item): ?>
				<?php
				$answer_state    = isset($user_answers[(int)$question_item->id]) ? $user_answers[(int)$question_item->id] : NULL;
				$question_status = strtolower(trim((string)(isset($question_item->status) ? $question_item->status : '')));
				$start_ts        = (!empty($question_item->start_time) && $question_item->start_time !== '0000-00-00 00:00:00') ? strtotime($question_item->start_time) : FALSE;
				$end_ts          = (!empty($question_item->end_time)   && $question_item->end_time   !== '0000-00-00 00:00:00') ? strtotime($question_item->end_time)   : FALSE;
				$now_ts          = time();
				$is_trade_open   = !in_array($question_status, array('draft', 'resolved', 'closed'), TRUE)
					&& ($start_ts === FALSE || $now_ts >= $start_ts)
					&& ($end_ts   === FALSE || $now_ts <= $end_ts);

				$state        = 'state-pending';
				$tag_class    = 'tag-pending';
				$tag_icon     = 'fa-solid fa-circle';
				$tag_label    = 'Open';
				$payout_text  = 'Not answered';
				$payout_class = 'neutral';

				if ($answer_state) {
					if (!empty($answer_state->settled_at)) {
						$is_sold      = strtolower((string)($answer_state->settlement_type ?? '')) === 'sell';
						$is_correct   = strtolower((string)$answer_state->answer) === strtolower((string)$question_item->answer_key);
						$state        = ($is_sold || $is_correct) ? 'state-correct' : 'state-wrong';
						$tag_class    = $is_sold ? 'tag-review'   : ($is_correct ? 'tag-won'  : 'tag-lost');
						$tag_icon     = $is_sold ? 'fa-solid fa-arrow-up-right-from-square' : ($is_correct ? 'fa-solid fa-check' : 'fa-solid fa-xmark');
						$tag_label    = $is_sold ? 'Completed'    : ($is_correct ? 'Won'      : 'Lost');
						$payout_text  = ($is_sold || $is_correct) ? '+ ₹' . number_format((float)$answer_state->payout_amount, 2) : '₹0.00';
						$payout_class = ($is_sold || $is_correct) ? 'win' : 'loss';
					} else {
						$tag_class    = 'tag-review';
						$tag_icon     = 'fa-solid fa-eye';
						$tag_label    = 'Review';
						$payout_text  = 'Stake ₹' . number_format((float)$answer_state->stake_amount, 2);
						$payout_class = 'neutral';
					}
				} elseif (!$is_trade_open) {
					$tag_class = 'tag-not-open';
					$tag_icon  = 'fa-solid fa-lock';
					$tag_label = $question_status === 'draft' ? 'Draft' : 'Closed';
				}

				$win_per_share = (float)$question_item->yes_price + (float)$question_item->no_price;
				?>

				<a class="ql-card <?php echo $state; ?>" href="<?php echo site_url('questions/answer/' . (int)$question_item->id); ?>">
					<div class="ql-card-body">

						<!-- Title row -->
						<div class="ql-card-row">
							<div class="ql-card-title-group">
								<div class="ql-idx"><?php echo $index + 1; ?></div>
								<div class="ql-question-text"><?php echo html_escape($question_item->question); ?></div>
							</div>
							<span class="ql-status-tag <?php echo $tag_class; ?>">
								<i class="<?php echo $tag_icon; ?>" style="font-size:9px"></i>
								<?php echo $tag_label; ?>
							</span>
						</div>

						<!-- YES / NO — always 2-col grid -->
						<div class="ql-odds-row">
							<div class="ql-odd-chip yes">
								<span class="chip-label">YES</span>
								<span class="chip-val">₹<?php echo number_format((float)$question_item->yes_price, 2); ?></span>
							</div>
							<div class="ql-odd-chip no">
								<span class="chip-label">NO</span>
								<span class="chip-val">₹<?php echo number_format((float)$question_item->no_price, 2); ?></span>
							</div>
						</div>

						<!-- WIN PER SHARE hero -->
						<div class="ql-win-hero">
							<div class="ql-win-hero-inner">
								<div class="ql-win-hero-left">
									<div class="ql-win-hero-eyebrow">
										<span class="ql-win-glow-dot"></span>
										<i class="fa-solid fa-trophy" style="font-size:10px"></i>
										Guaranteed payout
									</div>
									<div class="ql-win-hero-label">Win per share</div>
									<div class="ql-win-hero-sub">More shares = more winnings</div>
								</div>
								<div class="ql-win-hero-right">
									<div class="ql-win-amount">
										<span class="win-plus">₹</span><?php echo number_format($win_per_share, 2); ?>
									</div>
									<div class="ql-win-per-qty">× qty = total win</div>
								</div>
							</div>
						</div>

						<!-- Traders joined -->
						<div class="ql-users-strip">
							<i class="fa-solid fa-users" style="font-size:12px;color:var(--ql-muted)"></i>
							<strong><?php echo (int)(isset($question_item->total_users) ? $question_item->total_users : 0); ?></strong>
							traders joined
						</div>

						<!-- Footer -->
						<div class="ql-card-footer">
							<div class="ql-footer-left">
								<?php if ($answer_state): ?>
									<?php if (!empty($answer_state->settled_at)): ?>
										<i class="fa-solid fa-flag-checkered" style="font-size:11px"></i> Completed
									<?php else: ?>
										<i class="fa-solid fa-eye" style="font-size:11px"></i> Under review
									<?php endif; ?>
								<?php elseif ($is_trade_open): ?>
									<i class="fa-solid fa-circle-dot" style="font-size:11px;color:var(--ql-blue)"></i> Open to trade
								<?php else: ?>
									<i class="fa-solid fa-lock" style="font-size:11px"></i>
									<?php echo $question_status === 'draft' ? 'Draft question' : 'Not open for trade'; ?>
								<?php endif; ?>
							</div>
							<div class="ql-footer-right">
								<span class="ql-payout <?php echo $payout_class; ?>"><?php echo $payout_text; ?></span>
								<span class="ql-trade-btn">Trade <i class="fa-solid fa-arrow-right" style="font-size:10px"></i></span>
							</div>
						</div>

					</div>
				</a>

			<?php endforeach; ?>
		</div>

	<?php elseif ($selected_category): ?>

		<div class="ql-empty">
			<div class="ql-empty-icon"><i class="fa-solid fa-inbox"></i></div>
			<h3>No Questions Yet</h3>
			<p>This category has no questions. Please choose another category from the list above.</p>
		</div>

	<?php else: ?>

		<div class="ql-empty">
			<div class="ql-empty-icon"><i class="fa-solid fa-hand-pointer"></i></div>
			<h3>Pick a Category</h3>
			<p>Select any category above and its questions will appear here instantly — no page reload.</p>
		</div>

	<?php endif; ?>

</div>