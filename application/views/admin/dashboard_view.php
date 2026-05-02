<?php
$profile_image = !empty($admin->profile_image) ? $admin->profile_image : 'assets/images/default-profile.svg';
$profile_image_src = preg_match('/^https?:\/\//i', $profile_image) ? $profile_image : base_url($profile_image);
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

<style>
	/* ─── RESET ─────────────────────────────────────────────── */
	*,
	*::before,
	*::after {
		box-sizing: border-box;
		margin: 0;
		padding: 0;
	}

	img {
		max-width: 100%;
		display: block;
	}

	a {
		text-decoration: none;
		color: inherit;
	}

	/* ─── TOKENS ─────────────────────────────────────────────── */
	:root {
		--font: 'Plus Jakarta Sans', system-ui, sans-serif;
		--gap: 12px;

		--bg-page: #eef2fb;
		--bg-card: #ffffff;
		--bg-subtle: #f5f7fd;

		--blue-50: #e8f0fe;
		--blue-100: #c5d8fc;
		--blue-500: #2463eb;
		--blue-600: #1a52d4;
		--blue-700: #1340b8;

		--text-primary: #0f1c35;
		--text-secondary: #4a5878;
		--text-muted: #8a9bbf;

		--border: #dce4f3;
		--border-light: #edf1fa;

		--green: #10b981;
		--red: #ef4444;
		--cyan: #06b6d4;
		--orange: #f97316;
		--purple: #7c3aed;

		--radius-sm: 10px;
		--radius-md: 14px;
		--radius-lg: 18px;

		--shadow-sm: 0 1px 4px rgba(15, 28, 53, .06);
		--shadow-md: 0 4px 18px rgba(15, 28, 53, .10);
	}

	/* ─── WRAPPER ─────────────────────────────────────────────── */
	.adash {
		font-family: var(--font);
		background: var(--bg-page);
		color: var(--text-primary);
		padding: var(--gap);
		display: flex;
		flex-direction: column;
		gap: var(--gap);
		min-height: 100vh;
		width: 100%;
		max-width: 100%;
		overflow-x: hidden;
	}

	/* ═══════════════════════════════════════════════════════════
   HERO
═══════════════════════════════════════════════════════════ */
	.a-hero {
		border-radius: var(--radius-lg);
		background: linear-gradient(135deg, #1340b8 0%, #2463eb 60%, #3b82f6 100%);
		padding: 18px 16px;
		position: relative;
		overflow: hidden;
		width: 100%;
	}

	/* Blobs */
	.a-hero-blob {
		position: absolute;
		border-radius: 50%;
		background: rgba(255, 255, 255, .07);
		pointer-events: none;
	}

	.a-hero-blob-1 {
		width: 160px;
		height: 160px;
		right: -40px;
		top: -50px;
	}

	.a-hero-blob-2 {
		width: 90px;
		height: 90px;
		right: 30px;
		bottom: -35px;
	}

	/* Profile row — MOBILE: top-left pill */
	.a-hero-profile {
		display: flex;
		align-items: center;
		gap: 9px;
		margin-bottom: 16px;
		position: relative;
		z-index: 1;
		/* Constrain so it never overflows */
		max-width: 100%;
	}

	.a-hero-av {
		width: 38px;
		height: 38px;
		border-radius: 50%;
		object-fit: cover;
		border: 2px solid rgba(255, 255, 255, .5);
		flex-shrink: 0;
		background: rgba(255, 255, 255, .15);
	}

	.a-hero-pname {
		font-size: 13px;
		font-weight: 700;
		color: #fff;
		line-height: 1.2;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
		max-width: 180px;
	}

	.a-hero-prole {
		font-size: 9.5px;
		color: rgba(255, 255, 255, .6);
		text-transform: uppercase;
		letter-spacing: .08em;
		margin-top: 2px;
		font-weight: 500;
	}

	/* Content */
	.a-hero-content {
		position: relative;
		z-index: 1;
	}

	.a-hero-badge {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		background: rgba(255, 255, 255, .14);
		border: 1px solid rgba(255, 255, 255, .28);
		border-radius: 30px;
		padding: 4px 11px;
		font-size: 9px;
		font-weight: 700;
		letter-spacing: .09em;
		text-transform: uppercase;
		color: rgba(255, 255, 255, .9);
		margin-bottom: 9px;
	}

	.a-hero-dot {
		width: 6px;
		height: 6px;
		border-radius: 50%;
		background: #4ade80;
		box-shadow: 0 0 5px #4ade80;
		animation: pulse-dot 2s ease-in-out infinite;
		flex-shrink: 0;
	}

	@keyframes pulse-dot {

		0%,
		100% {
			opacity: 1;
			transform: scale(1);
		}

		50% {
			opacity: .4;
			transform: scale(.8);
		}
	}

	.a-hero-title {
		font-size: 22px;
		font-weight: 800;
		color: #fff;
		letter-spacing: -.4px;
		line-height: 1.15;
		margin-bottom: 6px;
	}

	.a-hero-sub {
		font-size: 12px;
		font-weight: 300;
		color: rgba(255, 255, 255, .65);
		line-height: 1.55;
	}

	/* Quick stat pills (hidden on mobile, shown ≥600px) */
	.a-hero-strip {
		display: none;
	}

	/* ═══════════════════════════════════════════════════════════
   STATS GRID
═══════════════════════════════════════════════════════════ */
	.a-stats {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: var(--gap);
		width: 100%;
	}

	/* QA block always full-width on mobile */
	.a-qa {
		grid-column: 1 / -1;
	}

	/* ─── STAT CARD ──────────────────────────────────────────── */
	.a-sc {
		background: var(--bg-card);
		border-radius: var(--radius-md);
		padding: 14px 13px 12px;
		border: 1px solid var(--border);
		position: relative;
		overflow: hidden;
		box-shadow: var(--shadow-sm);
		transition: transform .2s, box-shadow .2s;
		cursor: default;
		min-width: 0;
		/* prevent overflow in grid */
	}

	.a-sc::before {
		content: '';
		position: absolute;
		top: 0;
		left: 0;
		right: 0;
		height: 3px;
		background: var(--cc);
		border-radius: var(--radius-md) var(--radius-md) 0 0;
	}

	.a-sc::after {
		content: '';
		position: absolute;
		right: -12px;
		bottom: -12px;
		width: 60px;
		height: 60px;
		border-radius: 50%;
		background: var(--cc);
		opacity: .07;
		pointer-events: none;
	}

	.a-sc[data-c="blue"] {
		--cc: var(--blue-500);
	}

	.a-sc[data-c="red"] {
		--cc: var(--red);
	}

	.a-sc[data-c="cyan"] {
		--cc: var(--cyan);
	}

	.a-sc[data-c="green"] {
		--cc: var(--green);
	}

	.a-sc[data-c="orange"] {
		--cc: var(--orange);
	}

	.a-sc:active {
		transform: scale(.97);
	}

	.a-sc-top {
		display: flex;
		align-items: flex-start;
		justify-content: space-between;
		margin-bottom: 10px;
		gap: 4px;
	}

	.a-sc-ico {
		width: 32px;
		height: 32px;
		border-radius: 9px;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 13px;
		background: color-mix(in srgb, var(--cc) 12%, #fff);
		color: var(--cc);
		flex-shrink: 0;
	}

	.a-sc-lbl {
		font-size: 9px;
		font-weight: 700;
		text-transform: uppercase;
		letter-spacing: .06em;
		color: var(--text-muted);
		text-align: right;
		line-height: 1.3;
	}

	.a-sc-val {
		font-size: 26px;
		font-weight: 800;
		color: var(--text-primary);
		line-height: 1;
		letter-spacing: -1px;
		margin-bottom: 4px;
	}

	.a-sc-note {
		font-size: 10px;
		color: var(--text-muted);
		line-height: 1.4;
	}

	/* ─── QUICK ACTIONS ──────────────────────────────────────── */
	.a-qa {
		background: var(--bg-subtle);
		border: 1px solid var(--border);
		border-radius: var(--radius-md);
		padding: 14px 13px 12px;
		position: relative;
		overflow: hidden;
	}

	.a-qa::before {
		content: '';
		position: absolute;
		top: 0;
		left: 0;
		right: 0;
		height: 3px;
		background: var(--purple);
		border-radius: var(--radius-md) var(--radius-md) 0 0;
	}

	.a-qa-title {
		font-size: 9px;
		font-weight: 700;
		text-transform: uppercase;
		letter-spacing: .09em;
		color: var(--text-muted);
		margin-bottom: 10px;
	}

	/* Mobile: 3-col icon grid */
	.a-qa-grid {
		display: grid;
		grid-template-columns: 1fr 1fr 1fr;
		gap: 8px;
	}

	.a-qa-btn {
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		gap: 7px;
		padding: 12px 5px;
		border-radius: var(--radius-sm);
		background: var(--bg-card);
		border: 1px solid var(--border);
		color: var(--text-primary);
		font-family: var(--font);
		font-size: 10.5px;
		font-weight: 600;
		text-align: center;
		line-height: 1.3;
		transition: all .18s;
		box-shadow: var(--shadow-sm);
		cursor: pointer;
		min-width: 0;
	}

	.a-qa-btn:hover {
		background: var(--blue-500);
		color: #fff;
		border-color: var(--blue-500);
		box-shadow: 0 6px 18px rgba(36, 99, 235, .28);
		transform: translateY(-2px);
	}

	.a-qa-btn:hover .a-qa-ico {
		background: rgba(255, 255, 255, .2);
		color: #fff;
	}

	.a-qa-btn:active {
		transform: scale(.97);
	}

	.a-qa-ico {
		width: 32px;
		height: 32px;
		border-radius: 9px;
		background: var(--blue-50);
		color: var(--blue-500);
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 12px;
		flex-shrink: 0;
		transition: all .18s;
	}

	/* ─── SECTION LABEL ──────────────────────────────────────── */
	.a-section-label {
		font-size: 10px;
		font-weight: 700;
		text-transform: uppercase;
		letter-spacing: .1em;
		color: var(--text-muted);
		padding: 0 2px;
		margin-bottom: -4px;
	}

	/* ─── PANEL ──────────────────────────────────────────────── */
	.a-panel {
		background: var(--bg-card);
		border-radius: var(--radius-lg);
		border: 1px solid var(--border);
		box-shadow: var(--shadow-sm);
		overflow: hidden;
		width: 100%;
	}

	.a-panel-hd {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 10px;
		padding: 14px 15px;
		border-bottom: 1px solid var(--border-light);
		flex-wrap: wrap;
	}

	.a-panel-left {
		display: flex;
		align-items: center;
		gap: 10px;
		min-width: 0;
	}

	.a-panel-ico {
		width: 36px;
		height: 36px;
		border-radius: 9px;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 13px;
		flex-shrink: 0;
	}

	.aico-blue {
		background: var(--blue-50);
		color: var(--blue-500);
	}

	.aico-amber {
		background: #fef3c7;
		color: #b45309;
	}

	.a-panel-ttl {
		font-size: 13.5px;
		font-weight: 700;
		color: var(--text-primary);
	}

	.a-panel-sub {
		font-size: 11px;
		color: var(--text-muted);
		margin-top: 2px;
	}

	.a-view-all {
		display: inline-flex;
		align-items: center;
		gap: 5px;
		padding: 6px 11px;
		border-radius: var(--radius-sm);
		background: var(--blue-50);
		border: 1px solid var(--blue-100);
		color: var(--blue-600);
		font-family: var(--font);
		font-size: 11px;
		font-weight: 700;
		white-space: nowrap;
		transition: all .17s;
		flex-shrink: 0;
	}

	.a-view-all:hover {
		background: var(--blue-500);
		color: #fff;
		border-color: var(--blue-500);
	}

	/* ─── USER LIST ──────────────────────────────────────────── */
	.a-user-list {
		display: flex;
		flex-direction: column;
	}

	.a-user-row {
		display: flex;
		align-items: center;
		gap: 9px;
		padding: 11px 15px;
		border-bottom: 1px solid var(--border-light);
		transition: background .15s;
		min-width: 0;
	}

	.a-user-row:last-child {
		border-bottom: none;
	}

	.a-user-row:hover {
		background: var(--bg-subtle);
	}

	.a-user-num {
		width: 22px;
		height: 22px;
		border-radius: 6px;
		background: var(--bg-subtle);
		border: 1px solid var(--border);
		color: var(--text-muted);
		font-size: 10px;
		font-weight: 700;
		display: flex;
		align-items: center;
		justify-content: center;
		flex-shrink: 0;
	}

	.a-user-av {
		width: 34px;
		height: 34px;
		border-radius: 9px;
		background: linear-gradient(135deg, var(--blue-500), #4f46e5);
		color: #fff;
		font-size: 11px;
		font-weight: 700;
		display: flex;
		align-items: center;
		justify-content: center;
		text-transform: uppercase;
		flex-shrink: 0;
		letter-spacing: .5px;
	}

	.a-user-info {
		flex: 1;
		min-width: 0;
	}

	.a-user-name {
		font-size: 12.5px;
		font-weight: 600;
		color: var(--text-primary);
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
	}

	.a-user-email {
		font-size: 10.5px;
		color: var(--text-muted);
		margin-top: 2px;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
	}

	/* Hide phone on mobile — too cramped */
	.a-user-mobile {
		display: none;
	}

	.a-badge {
		display: inline-flex;
		align-items: center;
		gap: 4px;
		padding: 3px 7px;
		border-radius: 20px;
		font-size: 9.5px;
		font-weight: 700;
		white-space: nowrap;
		flex-shrink: 0;
	}

	.a-badge-user {
		background: var(--blue-50);
		color: var(--blue-600);
		border: 1px solid var(--blue-100);
	}

	.a-empty {
		padding: 30px 16px;
		color: var(--text-muted);
		font-size: 12.5px;
		text-align: center;
	}

	/* ─── LEADERBOARD ────────────────────────────────────────── */
	.a-lb-body {
		display: flex;
		flex-direction: column;
		gap: 8px;
		padding: 12px;
	}

	.a-lb-item {
		display: flex;
		align-items: center;
		gap: 9px;
		padding: 12px 13px 12px 17px;
		border-radius: var(--radius-md);
		background: var(--bg-subtle);
		border: 1px solid var(--border);
		position: relative;
		overflow: hidden;
		transition: box-shadow .2s, transform .2s;
		min-width: 0;
	}

	.a-lb-item:active {
		transform: scale(.99);
	}

	.a-lb-item::before {
		content: '';
		position: absolute;
		left: 0;
		top: 0;
		bottom: 0;
		width: 4px;
		border-radius: 4px 0 0 4px;
		background: var(--lbc, #d1d9ec);
	}

	.a-lb-item:nth-child(1) {
		--lbc: #f59e0b;
	}

	.a-lb-item:nth-child(2) {
		--lbc: #94a3b8;
	}

	.a-lb-item:nth-child(3) {
		--lbc: #c87941;
	}

	.a-lb-rank {
		width: 40px;
		height: 40px;
		border-radius: 10px;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 16px;
		font-weight: 800;
		flex-shrink: 0;
		background: var(--bg-card);
		color: var(--text-secondary);
		border: 1px solid var(--border);
	}

	.a-lb-item:nth-child(1) .a-lb-rank {
		background: linear-gradient(135deg, #fef9ee, #fde68a);
		color: #78350f;
		border-color: #fde68a;
	}

	.a-lb-item:nth-child(2) .a-lb-rank {
		background: linear-gradient(135deg, #f8fafc, #e2e8f0);
		color: #475569;
		border-color: #e2e8f0;
	}

	.a-lb-item:nth-child(3) .a-lb-rank {
		background: linear-gradient(135deg, #fdf4ee, #f5dcc7);
		color: #7c4a1e;
		border-color: #f5dcc7;
	}

	.a-lb-info {
		flex: 1;
		min-width: 0;
	}

	.a-lb-info h4 {
		font-size: 13px;
		font-weight: 700;
		color: var(--text-primary);
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
		margin-bottom: 2px;
	}

	.a-lb-info p {
		font-size: 11px;
		color: var(--text-muted);
	}

	.a-lb-info small {
		font-size: 10px;
		color: #aab5cc;
		display: block;
		margin-top: 2px;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
	}

	.a-lb-val {
		text-align: right;
		flex-shrink: 0;
	}

	.a-lb-val strong {
		display: block;
		font-size: 14px;
		font-weight: 800;
		color: var(--green);
		letter-spacing: -.3px;
	}

	.a-lb-val span {
		font-size: 10px;
		color: var(--text-muted);
		margin-top: 2px;
		display: block;
	}

	.a-lb-empty {
		display: flex;
		align-items: center;
		gap: 12px;
		padding: 20px 12px;
		border-radius: var(--radius-md);
		background: var(--bg-subtle);
		border: 1.5px dashed var(--border);
		color: var(--text-muted);
		font-size: 12.5px;
		line-height: 1.55;
	}

	.a-lb-empty-ico {
		width: 40px;
		height: 40px;
		border-radius: 10px;
		background: var(--bg-card);
		border: 1px solid var(--border);
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 16px;
		color: var(--text-muted);
		flex-shrink: 0;
	}

	/* ─── ANIMATIONS ─────────────────────────────────────────── */
	@keyframes aup {
		from {
			opacity: 0;
			transform: translateY(14px);
		}

		to {
			opacity: 1;
			transform: translateY(0);
		}
	}

	.adash>* {
		animation: aup .38s ease both;
	}

	.adash>*:nth-child(1) {
		animation-delay: .04s;
	}

	.adash>*:nth-child(2) {
		animation-delay: .10s;
	}

	.adash>*:nth-child(3) {
		animation-delay: .18s;
	}

	.adash>*:nth-child(4) {
		animation-delay: .24s;
	}

	.adash>*:nth-child(5) {
		animation-delay: .30s;
	}

	.adash>*:nth-child(6) {
		animation-delay: .36s;
	}

	/* ═══════════════════════════════════════════════════════════
   RESPONSIVE  ≤ 360px  — very small phones
═══════════════════════════════════════════════════════════ */
	@media (max-width: 360px) {
		:root {
			--gap: 9px;
		}

		.adash {
			padding: 9px;
		}

		.a-hero {
			padding: 14px 12px;
		}

		.a-hero-title {
			font-size: 19px;
		}

		.a-hero-av {
			width: 32px;
			height: 32px;
		}

		.a-hero-pname {
			font-size: 12px;
			max-width: 140px;
		}

		.a-sc {
			padding: 12px 10px 10px;
		}

		.a-sc-val {
			font-size: 23px;
		}

		.a-sc-ico {
			width: 28px;
			height: 28px;
			font-size: 11px;
			border-radius: 7px;
		}

		.a-sc-lbl {
			font-size: 8.5px;
		}

		.a-qa-btn {
			font-size: 9.5px;
			padding: 10px 3px;
			gap: 5px;
		}

		.a-qa-ico {
			width: 26px;
			height: 26px;
			font-size: 11px;
		}

		.a-lb-rank {
			width: 36px;
			height: 36px;
			font-size: 14px;
		}

		.a-lb-val strong {
			font-size: 12px;
		}

		.a-lb-info h4 {
			font-size: 12px;
		}

		.a-lb-body {
			padding: 9px;
		}

		.a-panel-hd {
			padding: 11px 12px;
		}

		.a-user-row {
			padding: 10px 12px;
		}
	}

	/* ═══════════════════════════════════════════════════════════
   RESPONSIVE  ≥ 480px  — large phones / phablets
═══════════════════════════════════════════════════════════ */
	@media (min-width: 480px) {
		:root {
			--gap: 14px;
		}

		.adash {
			padding: 14px;
		}

		/* 3-col stats: 5 cards + QA as 3rd col in row 2 */
		.a-stats {
			grid-template-columns: 1fr 1fr 1fr;
		}

		.a-qa {
			grid-column: auto;
		}

		/* QA buttons go vertical list inside */
		.a-qa-grid {
			grid-template-columns: 1fr;
			gap: 8px;
		}

		.a-qa-btn {
			flex-direction: row;
			justify-content: flex-start;
			padding: 10px 13px;
			font-size: 12.5px;
		}

		.a-hero {
			padding: 22px 20px;
		}

		.a-hero-title {
			font-size: 25px;
		}
	}

	/* ═══════════════════════════════════════════════════════════
   RESPONSIVE  ≥ 600px  — tablet
═══════════════════════════════════════════════════════════ */
	@media (min-width: 600px) {
		:root {
			--gap: 18px;
		}

		.adash {
			padding: 18px;
		}

		.a-hero {
			padding: 26px 24px;
		}

		.a-hero-title {
			font-size: 27px;
		}

		/* Show phone col in users */
		.a-user-mobile {
			display: block;
		}

		/* Show hero stat pills */
		.a-hero-strip {
			display: flex;
			gap: 9px;
			margin-top: 16px;
			flex-wrap: wrap;
			position: relative;
			z-index: 1;
		}

		.a-hero-pill {
			display: flex;
			align-items: center;
			gap: 7px;
			background: rgba(255, 255, 255, .12);
			border: 1px solid rgba(255, 255, 255, .22);
			border-radius: 30px;
			padding: 5px 13px;
			color: #fff;
			font-size: 11.5px;
			font-weight: 500;
			white-space: nowrap;
		}

		.a-hero-pill i {
			font-size: 10px;
			opacity: .75;
		}
	}

	/* ═══════════════════════════════════════════════════════════
   RESPONSIVE  ≥ 960px  — desktop
═══════════════════════════════════════════════════════════ */
	@media (min-width: 960px) {
		:root {
			--gap: 22px;
		}

		.adash {
			padding: 26px;
		}

		/* Hero side-by-side */
		.a-hero {
			display: flex;
			align-items: center;
			justify-content: space-between;
			padding: 32px 36px;
			gap: 20px;
		}

		.a-hero-profile {
			order: 2;
			margin-bottom: 0;
			background: rgba(255, 255, 255, .12);
			border: 1px solid rgba(255, 255, 255, .22);
			border-radius: 50px;
			padding: 7px 18px 7px 7px;
			flex-shrink: 0;
			backdrop-filter: blur(10px);
			max-width: none;
		}

		.a-hero-pname {
			max-width: none;
		}

		.a-hero-content {
			order: 1;
		}

		.a-hero-strip {
			order: 3;
			display: none;
		}

		.a-hero-title {
			font-size: 32px;
		}

		.a-sc:hover {
			transform: translateY(-3px);
			box-shadow: 0 10px 28px rgba(15, 28, 53, .11);
		}

		.a-lb-item:hover {
			background: var(--bg-card);
			box-shadow: var(--shadow-md);
			transform: translateY(-2px);
		}

		.a-panel-hd {
			padding: 16px 22px;
		}

		.a-user-row {
			padding: 14px 22px;
		}

		.a-lb-body {
			padding: 16px;
			gap: 11px;
		}
	}
</style>

<div class="adash">

	<!-- ── HERO ───────────────────────────────────────────────── -->
	<section class="a-hero">
		<div class="a-hero-blob a-hero-blob-1"></div>
		<div class="a-hero-blob a-hero-blob-2"></div>

		<div class="a-hero-profile">
			<img class="a-hero-av"
				src="<?php echo html_escape($profile_image_src); ?>"
				alt="<?php echo html_escape($admin->name); ?>">
			<div>
				<div class="a-hero-pname"><?php echo html_escape($admin->name); ?></div>
				<div class="a-hero-prole">Administrator</div>
			</div>
		</div>

		<div class="a-hero-content">
			<div class="a-hero-badge">
				<span class="a-hero-dot"></span> Live Overview
			</div>
			<h2 class="a-hero-title">PROGO11 Dashboard</h2>
			<p class="a-hero-sub">Accounts, categories, questions &amp; winner payouts — all in one place.</p>

			<!-- Only shown ≥600px -->
			<div class="a-hero-strip">
				<div class="a-hero-pill">
					<i class="fa-solid fa-users"></i>
					<?php echo number_format((int)$total_users); ?> Users
				</div>
				<div class="a-hero-pill">
					<i class="fa-solid fa-circle-question"></i>
					<?php echo number_format((int)$total_questions); ?> Questions
				</div>
				<div class="a-hero-pill">
					<i class="fa-solid fa-trophy"></i>
					Leaderboard Live
				</div>
			</div>
		</div>
	</section>

	<!-- ── STATS + QUICK ACTIONS ──────────────────────────────── -->
	<div class="a-stats">

		<div class="a-sc" data-c="blue">
			<div class="a-sc-top">
				<div class="a-sc-ico"><i class="fa-solid fa-users"></i></div>
				<div class="a-sc-lbl">Total<br>Users</div>
			</div>
			<div class="a-sc-val"><?php echo number_format((int)$total_users); ?></div>
			<div class="a-sc-note">All registered accounts</div>
		</div>

		<div class="a-sc" data-c="red">
			<div class="a-sc-top">
				<div class="a-sc-ico"><i class="fa-solid fa-user-shield"></i></div>
				<div class="a-sc-lbl">Admin<br>Users</div>
			</div>
			<div class="a-sc-val"><?php echo number_format((int)$total_admins); ?></div>
			<div class="a-sc-note">Dashboard access</div>
		</div>

		<div class="a-sc" data-c="cyan">
			<div class="a-sc-top">
				<div class="a-sc-ico"><i class="fa-solid fa-user"></i></div>
				<div class="a-sc-lbl">Regular<br>Members</div>
			</div>
			<div class="a-sc-val"><?php echo number_format((int)$total_members); ?></div>
			<div class="a-sc-note">Standard accounts</div>
		</div>

		<div class="a-sc" data-c="green">
			<div class="a-sc-top">
				<div class="a-sc-ico"><i class="fa-solid fa-folder-tree"></i></div>
				<div class="a-sc-lbl">Question<br>Categories</div>
			</div>
			<div class="a-sc-val"><?php echo number_format((int)$total_categories); ?></div>
			<div class="a-sc-note">Available categories</div>
		</div>

		<div class="a-sc" data-c="orange">
			<div class="a-sc-top">
				<div class="a-sc-ico"><i class="fa-solid fa-circle-question"></i></div>
				<div class="a-sc-lbl">Total<br>Questions</div>
			</div>
			<div class="a-sc-val"><?php echo number_format((int)$total_questions); ?></div>
			<div class="a-sc-note">Across all categories</div>
		</div>

		<!-- Quick Actions: always full-width -->
		<div class="a-qa">
			<div class="a-qa-title">Quick Actions</div>
			<div class="a-qa-grid">
				<a class="a-qa-btn" href="<?php echo site_url('admin/categories'); ?>">
					<span class="a-qa-ico"><i class="fa-solid fa-folder-tree"></i></span>
					Manage Categories
				</a>
				<a class="a-qa-btn" href="<?php echo site_url('admin/questions/add'); ?>">
					<span class="a-qa-ico"><i class="fa-solid fa-plus"></i></span>
					Add Question
				</a>
				<a class="a-qa-btn" href="<?php echo site_url('admin/users'); ?>">
					<span class="a-qa-ico"><i class="fa-solid fa-users"></i></span>
					Manage Users
				</a>
			</div>
		</div>

	</div>

	<!-- ── RECENT USERS ───────────────────────────────────────── -->
	<div class="a-section-label">Recent Members</div>

	<section class="a-panel">
		<div class="a-panel-hd">
			<div class="a-panel-left">
				<div class="a-panel-ico aico-blue"><i class="fa-solid fa-users"></i></div>
				<div>
					<div class="a-panel-ttl">Recent Users</div>
					<div class="a-panel-sub">Latest registered members</div>
				</div>
			</div>
			<a class="a-view-all" href="<?php echo site_url('admin/users'); ?>">
				View All <i class="fa-solid fa-arrow-right" style="font-size:9px;"></i>
			</a>
		</div>

		<div class="a-user-list">
			<?php if (!empty($latest_users)): ?>
				<?php foreach ($latest_users as $i => $u): ?>
					<div class="a-user-row">
						<div class="a-user-num"><?php echo (int)$i + 1; ?></div>
						<div class="a-user-av"><?php echo strtoupper(mb_substr(trim($u->name), 0, 2)); ?></div>
						<div class="a-user-info">
							<div class="a-user-name"><?php echo html_escape($u->name); ?></div>
							<div class="a-user-email"><?php echo html_escape($u->email); ?></div>
						</div>
						<div class="a-user-mobile"><?php echo html_escape($u->mobile); ?></div>
						<span class="a-badge a-badge-user">
							<i class="fa-solid fa-circle" style="font-size:5px;"></i> User
						</span>
					</div>
				<?php endforeach; ?>
			<?php else: ?>
				<div class="a-empty">
					<i class="fa-solid fa-users" style="display:block;font-size:24px;color:#d1d9ec;margin:0 auto 10px;"></i>
					No regular users registered yet.
				</div>
			<?php endif; ?>
		</div>
	</section>

	<!-- ── WINNER LEADERBOARD ─────────────────────────────────── -->
	<div class="a-section-label">Leaderboard</div>

	<section class="a-panel">
		<div class="a-panel-hd">
			<div class="a-panel-left">
				<div class="a-panel-ico aico-amber"><i class="fa-solid fa-trophy"></i></div>
				<div>
					<div class="a-panel-ttl">Winner Leaderboard</div>
					<div class="a-panel-sub">Ranked by total winning payouts</div>
				</div>
			</div>
		</div>

		<div class="a-lb-body">
			<?php if (!empty($winner_leaderboard)): ?>
				<?php foreach ($winner_leaderboard as $i => $w): ?>
					<div class="a-lb-item">
						<div class="a-lb-rank"><?php echo $i + 1; ?></div>
						<div class="a-lb-info">
							<h4><?php echo html_escape($w->name); ?></h4>
							<p><?php echo (int)$w->total_wins; ?> wins &middot; <?php echo (int)$w->total_trades; ?> trades</p>
							<small><?php echo html_escape($w->email); ?></small>
						</div>
						<div class="a-lb-val">
							<strong>&#8377;<?php echo number_format((float)$w->total_payout, 2); ?></strong>
							<span>Payout</span>
						</div>
					</div>
				<?php endforeach; ?>
			<?php else: ?>
				<div class="a-lb-empty">
					<div class="a-lb-empty-ico"><i class="fa-solid fa-trophy"></i></div>
					<div>No winners yet. Leaderboard fills once markets are resolved with payouts.</div>
				</div>
			<?php endif; ?>
		</div>
	</section>

</div>