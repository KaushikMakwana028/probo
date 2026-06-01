<?php
$wallet_balance = isset($user->wallet_balance) ? (float) $user->wallet_balance : 12450.75;
$user_trade_count = isset($user_trade_count) ? (int) $user_trade_count : 247;
$categories = isset($categories) && is_array($categories) ? $categories : array();
$featured_categories = array_slice($categories, 0, 8);
$category_count = count($categories) ?: 12;
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">

<style>
	/* ═══════════════════════════════════════════════
   TOKENS
═══════════════════════════════════════════════ */
	:root {
		--f: 'Roboto', sans-serif;

		/* Backgrounds */
		--bg: #f2f4f8;
		--bg-2: #e8ecf4;
		--white: #ffffff;
		--card: #ffffff;

		/* Borders */
		--bdr: rgba(0, 0, 0, 0.07);
		--bdr-2: rgba(0, 0, 0, 0.11);

		/* Text */
		--t-1: #0d1226;
		--t-2: #3d4a63;
		--t-3: #7a869f;
		--t-4: #b0bac9;

		/* Primary brand */
		--brand: #2563eb;
		--brand-light: rgba(37, 99, 235, 0.09);
		--brand-med: rgba(37, 99, 235, 0.15);

		/* Accents */
		--green: #059669;
		--green-bg: rgba(5, 150, 105, 0.09);
		--orange: #ea6c00;
		--orange-bg: rgba(234, 108, 0, 0.09);
		--violet: #7c3aed;
		--violet-bg: rgba(124, 58, 237, 0.09);
		--rose: #e11d48;
		--rose-bg: rgba(225, 29, 72, 0.09);
		--sky: #0284c7;
		--sky-bg: rgba(2, 132, 199, 0.09);
		--amber: #d97706;
		--amber-bg: rgba(217, 119, 6, 0.09);

		/* Play CTA gradient */
		--cta-from: #2563eb;
		--cta-to: #1d4ed8;

		/* Radius */
		--rx-sm: 10px;
		--rx: 14px;
		--rx-lg: 20px;
		--rx-xl: 26px;

		/* Shadow */
		--sh: 0 2px 12px rgba(0, 0, 0, 0.07);
		--sh-md: 0 6px 28px rgba(0, 0, 0, 0.09);
		--sh-lg: 0 16px 48px rgba(0, 0, 0, 0.13);
		--sh-brand: 0 8px 28px rgba(37, 99, 235, 0.28);
	}

	*,
	*::before,
	*::after {
		box-sizing: border-box;
		margin: 0;
		padding: 0;
	}

	.dash {
		font-family: var(--f);
		background: var(--bg);
		color: var(--t-1);
		display: flex;
		flex-direction: column;
		gap: 0;
		padding-bottom: 32px;
	}

	a {
		text-decoration: none;
		color: inherit;
	}

	/* ═══════════════════════════════════════════════
   HERO
═══════════════════════════════════════════════ */
	.d-hero {
		margin: 20px 20px 0;
		border-radius: var(--rx-xl);
		background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 55%, #3b82f6 100%);
		padding: 36px 32px 34px;
		position: relative;
		overflow: hidden;
		color: #fff;
		box-shadow: var(--sh-brand);
	}

	/* decorative circles */
	.d-hero::before {
		content: '';
		position: absolute;
		width: 380px;
		height: 380px;
		top: -140px;
		right: -100px;
		border-radius: 50%;
		background: rgba(255, 255, 255, 0.07);
		pointer-events: none;
	}

	.d-hero::after {
		content: '';
		position: absolute;
		width: 200px;
		height: 200px;
		bottom: -80px;
		left: 20%;
		border-radius: 50%;
		background: rgba(255, 255, 255, 0.05);
		pointer-events: none;
	}

	.d-hero-inner {
		position: relative;
		z-index: 1;
		display: grid;
		grid-template-columns: 1fr auto;
		gap: 28px;
		align-items: center;
	}

	.d-hero-eyebrow {
		display: inline-flex;
		align-items: center;
		gap: 7px;
		padding: 5px 13px;
		border-radius: 999px;
		background: rgba(255, 255, 255, 0.14);
		border: 1px solid rgba(255, 255, 255, 0.22);
		font-size: 11px;
		font-weight: 700;
		letter-spacing: 0.10em;
		text-transform: uppercase;
		color: rgba(255, 255, 255, 0.9);
		margin-bottom: 16px;
	}

	.d-live-dot {
		width: 6px;
		height: 6px;
		border-radius: 50%;
		background: #4ade80;
		animation: ldPulse 1.5s ease-in-out infinite;
	}

	@keyframes ldPulse {

		0%,
		100% {
			opacity: 1;
			transform: scale(1);
		}

		50% {
			opacity: 0.5;
			transform: scale(0.65);
		}
	}

	.d-hero-h1 {
		font-size: clamp(28px, 4vw, 44px);
		font-weight: 900;
		line-height: 1.05;
		letter-spacing: -0.03em;
		color: #fff;
		margin-bottom: 10px;
	}

	.d-hero-h1 em {
		font-style: normal;
		color: #bef264;
	}

	.d-hero-sub {
		font-size: 14px;
		line-height: 1.7;
		color: rgba(255, 255, 255, 0.7);
		margin-bottom: 24px;
		max-width: 420px;
	}

	.d-hero-btns {
		display: flex;
		gap: 10px;
		flex-wrap: wrap;
	}

	.d-btn-play {
		display: inline-flex;
		align-items: center;
		gap: 8px;
		padding: 13px 24px;
		border-radius: var(--rx);
		background: #fff;
		color: var(--brand);
		font-size: 14px;
		font-weight: 800;
		border: none;
		cursor: pointer;
		transition: all 0.2s ease;
		box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
		letter-spacing: -0.01em;
	}

	.d-btn-play:hover {
		transform: translateY(-2px);
		box-shadow: 0 10px 28px rgba(0, 0, 0, 0.28);
	}

	.d-btn-play .play-icon {
		width: 22px;
		height: 22px;
		border-radius: 50%;
		background: var(--brand);
		color: #fff;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 9px;
	}

	.d-btn-ghost {
		display: inline-flex;
		align-items: center;
		gap: 8px;
		padding: 12px 20px;
		border-radius: var(--rx);
		background: rgba(255, 255, 255, 0.12);
		color: rgba(255, 255, 255, 0.9);
		font-size: 14px;
		font-weight: 700;
		border: 1px solid rgba(255, 255, 255, 0.22);
		cursor: pointer;
		transition: all 0.2s ease;
	}

	.d-btn-ghost:hover {
		background: rgba(255, 255, 255, 0.2);
		transform: translateY(-2px);
	}

	/* Hero right panel */
	.d-hero-panel {
		width: 230px;
		flex-shrink: 0;
	}

	.d-bal-card {
		background: rgba(255, 255, 255, 0.12);
		border: 1px solid rgba(255, 255, 255, 0.18);
		border-radius: var(--rx-lg);
		padding: 20px 22px;
		backdrop-filter: blur(16px);
		margin-bottom: 10px;
	}

	.d-bal-label {
		font-size: 10px;
		font-weight: 700;
		text-transform: uppercase;
		letter-spacing: 0.12em;
		color: rgba(255, 255, 255, 0.55);
		margin-bottom: 6px;
	}

	.d-bal-val {
		font-size: 34px;
		font-weight: 900;
		letter-spacing: -0.045em;
		line-height: 1;
		color: #fff;
		margin-bottom: 6px;
	}

	.d-bal-sub {
		font-size: 11px;
		color: rgba(255, 255, 255, 0.45);
	}

	.d-hero-mini-stats {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 8px;
	}

	.d-mini-stat {
		background: rgba(255, 255, 255, 0.10);
		border: 1px solid rgba(255, 255, 255, 0.14);
		border-radius: var(--rx);
		padding: 14px 14px 12px;
		backdrop-filter: blur(8px);
	}

	.d-mini-label {
		font-size: 9px;
		font-weight: 700;
		text-transform: uppercase;
		letter-spacing: 0.10em;
		color: rgba(255, 255, 255, 0.45);
		margin-bottom: 5px;
	}

	.d-mini-val {
		font-size: 22px;
		font-weight: 900;
		letter-spacing: -0.03em;
		color: #fff;
	}

	/* ═══════════════════════════════════════════════
   GRID LAYOUT
═══════════════════════════════════════════════ */
	.d-body {
		margin: 20px 20px 0;
		display: grid;
		grid-template-columns: repeat(2, 1fr);
		gap: 16px;
	}

	/* ═══════════════════════════════════════════════
   CARD
═══════════════════════════════════════════════ */
	.d-card {
		background: var(--card);
		border-radius: var(--rx-xl);
		border: 1px solid var(--bdr);
		box-shadow: var(--sh);
		overflow: hidden;
	}

	.d-card-head {
		padding: 22px 24px 0;
	}

	.d-card-title {
		font-size: 16px;
		font-weight: 800;
		letter-spacing: -0.02em;
		color: var(--t-1);
		margin-bottom: 3px;
	}

	.d-card-desc {
		font-size: 12.5px;
		color: var(--t-3);
	}

	.d-card-divider {
		height: 1px;
		background: var(--bdr);
		margin: 18px 0 0;
	}

	.d-card-body {
		padding: 20px 24px;
	}

	/* ═══════════════════════════════════════════════
   PLAY ITEMS
═══════════════════════════════════════════════ */
	.d-play-grid {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 12px;
	}

	.d-play-item {
		border-radius: var(--rx-lg);
		padding: 20px 18px;
		border: 1px solid var(--bdr);
		display: flex;
		flex-direction: column;
		gap: 0;
		cursor: pointer;
		transition: all 0.22s ease;
		position: relative;
		overflow: hidden;
		min-height: 190px;
	}

	.pi-live {
		background: linear-gradient(145deg, #f0fdf4 0%, #dcfce7 100%);
		border-color: rgba(5, 150, 105, 0.15);
	}

	.pi-wallet {
		background: linear-gradient(145deg, #eff6ff 0%, #dbeafe 100%);
		border-color: rgba(37, 99, 235, 0.15);
	}

	.d-play-item:hover {
		transform: translateY(-3px);
		box-shadow: var(--sh-lg);
		border-color: transparent;
	}

	.d-pi-badge {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		padding: 5px 11px;
		border-radius: 999px;
		font-size: 10px;
		font-weight: 800;
		letter-spacing: 0.07em;
		text-transform: uppercase;
		margin-bottom: 14px;
		width: fit-content;
	}

	.pi-live .d-pi-badge {
		background: var(--green-bg);
		color: var(--green);
		border: 1px solid rgba(5, 150, 105, 0.2);
	}

	.pi-wallet .d-pi-badge {
		background: var(--brand-light);
		color: var(--brand);
		border: 1px solid rgba(37, 99, 235, 0.18);
	}

	.d-pi-dot {
		width: 5px;
		height: 5px;
		border-radius: 50%;
		background: currentColor;
	}

	.pi-live .d-pi-dot {
		animation: ldPulse 1.5s infinite;
	}

	.d-pi-title {
		font-size: 17px;
		font-weight: 800;
		letter-spacing: -0.025em;
		line-height: 1.2;
		margin-bottom: 8px;
		color: var(--t-1);
	}

	.d-pi-desc {
		font-size: 12px;
		line-height: 1.65;
		color: var(--t-3);
		flex: 1;
		margin-bottom: 16px;
	}

	.d-pi-arrow {
		width: 34px;
		height: 34px;
		border-radius: 9px;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 12px;
		align-self: flex-start;
		transition: transform 0.2s;
		color: #fff;
	}

	.pi-live .d-pi-arrow {
		background: var(--green);
	}

	.pi-wallet .d-pi-arrow {
		background: var(--brand);
	}

	.d-play-item:hover .d-pi-arrow {
		transform: translateX(3px);
	}

	/* ═══════════════════════════════════════════════
   SHORTCUTS
═══════════════════════════════════════════════ */
	.d-shortcuts {
		display: flex;
		flex-direction: column;
		gap: 8px;
	}

	.d-sc {
		display: flex;
		align-items: center;
		gap: 14px;
		padding: 14px 16px;
		background: var(--bg);
		border: 1px solid var(--bdr);
		border-radius: var(--rx);
		cursor: pointer;
		transition: all 0.18s ease;
		justify-content: space-between;
	}

	.d-sc:hover {
		background: var(--brand-light);
		border-color: rgba(37, 99, 235, 0.18);
		transform: translateX(3px);
	}

	.d-sc-left {
		display: flex;
		align-items: center;
		gap: 12px;
	}

	.d-sc-icon {
		width: 42px;
		height: 42px;
		border-radius: 12px;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 16px;
		flex-shrink: 0;
	}

	.sci-blue {
		background: var(--brand-light);
		color: var(--brand);
	}

	.sci-green {
		background: var(--green-bg);
		color: var(--green);
	}

	.sci-violet {
		background: var(--violet-bg);
		color: var(--violet);
	}

	.d-sc-title {
		font-size: 13.5px;
		font-weight: 700;
		color: var(--t-1);
		margin-bottom: 2px;
	}

	.d-sc-sub {
		font-size: 12px;
		color: var(--t-3);
	}

	.d-sc-chev {
		color: var(--t-4);
		font-size: 11px;
		transition: all 0.18s;
	}

	.d-sc:hover .d-sc-chev {
		color: var(--brand);
		transform: translateX(2px);
	}

	/* ═══════════════════════════════════════════════
   CATEGORIES — auto-icon, colorful
═══════════════════════════════════════════════ */
	.d-cats-grid {
		display: grid;
		grid-template-columns: repeat(2, 1fr);
		gap: 10px;
	}

	.d-cat-card {
		display: flex;
		align-items: center;
		gap: 12px;
		padding: 14px 16px;
		border-radius: var(--rx);
		border: 1px solid var(--bdr);
		background: var(--bg);
		cursor: pointer;
		transition: all 0.2s ease;
		position: relative;
		overflow: hidden;
	}

	.d-cat-card::before {
		content: '';
		position: absolute;
		inset: 0;
		opacity: 0;
		transition: opacity 0.2s;
	}

	.d-cat-card:hover {
		transform: translateY(-2px);
		box-shadow: var(--sh-md);
		border-color: transparent;
	}

	.d-cat-card:hover::before {
		opacity: 1;
	}

	/* Color themes per category card */
	.cc-0::before {
		background: linear-gradient(135deg, rgba(37, 99, 235, 0.06), rgba(37, 99, 235, 0.02));
	}

	.cc-1::before {
		background: linear-gradient(135deg, rgba(5, 150, 105, 0.07), rgba(5, 150, 105, 0.02));
	}

	.cc-2::before {
		background: linear-gradient(135deg, rgba(234, 108, 0, 0.07), rgba(234, 108, 0, 0.02));
	}

	.cc-3::before {
		background: linear-gradient(135deg, rgba(124, 58, 237, 0.07), rgba(124, 58, 237, 0.02));
	}

	.cc-4::before {
		background: linear-gradient(135deg, rgba(225, 29, 72, 0.07), rgba(225, 29, 72, 0.02));
	}

	.cc-5::before {
		background: linear-gradient(135deg, rgba(2, 132, 199, 0.07), rgba(2, 132, 199, 0.02));
	}

	.cc-6::before {
		background: linear-gradient(135deg, rgba(217, 119, 6, 0.08), rgba(217, 119, 6, 0.02));
	}

	.cc-7::before {
		background: linear-gradient(135deg, rgba(37, 99, 235, 0.08), rgba(37, 99, 235, 0.02));
	}

	.d-cat-icon-wrap {
		width: 42px;
		height: 42px;
		border-radius: 12px;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 18px;
		flex-shrink: 0;
		position: relative;
		z-index: 1;
	}

	/* Icon bg colors */
	.cib-0 {
		background: var(--brand-light);
		color: var(--brand);
	}

	.cib-1 {
		background: var(--green-bg);
		color: var(--green);
	}

	.cib-2 {
		background: var(--orange-bg);
		color: var(--orange);
	}

	.cib-3 {
		background: var(--violet-bg);
		color: var(--violet);
	}

	.cib-4 {
		background: var(--rose-bg);
		color: var(--rose);
	}

	.cib-5 {
		background: var(--sky-bg);
		color: var(--sky);
	}

	.cib-6 {
		background: var(--amber-bg);
		color: var(--amber);
	}

	.cib-7 {
		background: var(--brand-light);
		color: var(--brand);
	}

	.d-cat-text {
		flex: 1;
		min-width: 0;
		position: relative;
		z-index: 1;
	}

	.d-cat-name {
		font-size: 13px;
		font-weight: 700;
		color: var(--t-1);
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
		margin-bottom: 3px;
	}

	.d-cat-count {
		font-size: 11px;
		color: var(--t-3);
		font-weight: 500;
	}

	.d-cat-arrow {
		color: var(--t-4);
		font-size: 11px;
		position: relative;
		z-index: 1;
		transition: all 0.18s;
	}

	.d-cat-card:hover .d-cat-arrow {
		color: var(--brand);
		transform: translateX(2px);
	}

	.d-empty {
		grid-column: 1 / -1;
		padding: 24px;
		border-radius: var(--rx);
		border: 1.5px dashed var(--bdr-2);
		background: var(--bg-2);
		font-size: 13px;
		color: var(--t-3);
		text-align: center;
	}

	/* ═══════════════════════════════════════════════
   TIPS
═══════════════════════════════════════════════ */
	.d-tips {
		display: flex;
		flex-direction: column;
		gap: 10px;
	}

	.d-tip {
		display: flex;
		gap: 14px;
		align-items: flex-start;
		padding: 16px 18px;
		background: var(--bg);
		border: 1px solid var(--bdr);
		border-radius: var(--rx);
		transition: border-color 0.18s;
	}

	.d-tip:hover {
		border-color: var(--bdr-2);
	}

	.d-tip-icon {
		width: 36px;
		height: 36px;
		border-radius: 10px;
		background: var(--white);
		border: 1px solid var(--bdr);
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 16px;
		flex-shrink: 0;
		box-shadow: var(--sh);
	}

	.d-tip-title {
		font-size: 13px;
		font-weight: 700;
		color: var(--t-1);
		margin-bottom: 4px;
	}

	.d-tip-desc {
		font-size: 12px;
		line-height: 1.65;
		color: var(--t-3);
	}

	/* ═══════════════════════════════════════════════
   BOTTOM REFERRAL BANNER
═══════════════════════════════════════════════ */
	.d-banner {
		margin: 16px 20px 0;
		border-radius: var(--rx-xl);
		background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%);
		padding: 26px 28px;
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 16px;
		position: relative;
		overflow: hidden;
		box-shadow: 0 8px 28px rgba(124, 58, 237, 0.28);
	}

	.d-banner::before {
		content: '';
		position: absolute;
		width: 260px;
		height: 260px;
		top: -110px;
		right: -60px;
		border-radius: 50%;
		background: rgba(255, 255, 255, 0.07);
		pointer-events: none;
	}

	.d-banner-text {
		position: relative;
		z-index: 1;
	}

	.d-banner-h {
		font-size: 18px;
		font-weight: 900;
		color: #fff;
		letter-spacing: -0.025em;
		line-height: 1.2;
		margin-bottom: 4px;
	}

	.d-banner-sub {
		font-size: 13px;
		color: rgba(255, 255, 255, 0.65);
		line-height: 1.5;
	}

	.d-banner-btn {
		position: relative;
		z-index: 1;
		display: inline-flex;
		align-items: center;
		gap: 8px;
		padding: 12px 22px;
		border-radius: var(--rx);
		background: #fff;
		color: var(--violet);
		font-size: 13px;
		font-weight: 800;
		flex-shrink: 0;
		transition: all 0.2s;
		cursor: pointer;
		white-space: nowrap;
	}

	.d-banner-btn:hover {
		transform: scale(1.04);
		box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
	}

	/* ═══════════════════════════════════════════════
   ANIMATIONS
═══════════════════════════════════════════════ */
	@keyframes fadeUp {
		from {
			opacity: 0;
			transform: translateY(16px);
		}

		to {
			opacity: 1;
			transform: translateY(0);
		}
	}

	.d-hero,
	.d-body,
	.d-banner {
		animation: fadeUp 0.5s ease both;
	}

	.d-body {
		animation-delay: 0.08s;
	}

	.d-banner {
		animation-delay: 0.14s;
	}

	/* ═══════════════════════════════════════════════
   RESPONSIVE
═══════════════════════════════════════════════ */
	@media (max-width: 900px) {
		.d-hero-inner {
			grid-template-columns: 1fr;
		}

		.d-hero-panel {
			width: 100%;
		}

		.d-hero-mini-stats {
			grid-template-columns: repeat(4, 1fr);
		}
	}

	@media (max-width: 640px) {
		.d-hero {
			margin: 12px 12px 0;
			padding: 26px 20px;
		}

		.d-body {
			margin: 12px 12px 0;
			grid-template-columns: 1fr;
			gap: 12px;
		}

		.d-banner {
			margin: 12px 12px 0;
			flex-direction: column;
			align-items: flex-start;
		}

		.d-banner-btn {
			width: 100%;
			justify-content: center;
		}

		.d-hero-mini-stats {
			grid-template-columns: repeat(2, 1fr);
		}

		.d-play-grid {
			grid-template-columns: 1fr;
		}

		.d-cats-grid {
			grid-template-columns: 1fr;
		}

		.d-hero-h1 {
			font-size: 28px;
		}

		.d-card-head {
			padding: 18px 18px 0;
		}

		.d-card-body {
			padding: 16px 18px;
		}
	}

	@media (min-width: 1200px) {
		.d-hero {
			margin: 24px 24px 0;
		}

		.d-body {
			margin: 20px 24px 0;
		}

		.d-banner {
			margin: 20px 24px 0;
		}
	}
</style>

<?php
/* ─── Category icon resolver ──────────────────────────────── */
function get_category_icon(string $name): string
{
	$n = strtolower(trim($name));
	$map = [
		// Sports
		'cricket'      => 'fa-solid fa-cricket-bat-ball',
		'football'     => 'fa-solid fa-futbol',
		'soccer'       => 'fa-solid fa-futbol',
		'basketball'   => 'fa-solid fa-basketball',
		'tennis'       => 'fa-solid fa-baseball',
		'badminton'    => 'fa-solid fa-shuttlecock',
		'kabaddi'      => 'fa-solid fa-person-running',
		'hockey'       => 'fa-solid fa-hockey-puck',
		'golf'         => 'fa-solid fa-golf-ball-tee',
		'boxing'       => 'fa-solid fa-hand-fist',
		'sports'       => 'fa-solid fa-trophy',
		'ipl'          => 'fa-solid fa-cricket-bat-ball',
		// Finance / Market
		'finance'      => 'fa-solid fa-chart-line',
		'stock'        => 'fa-solid fa-chart-candlestick',
		'stocks'       => 'fa-solid fa-chart-candlestick',
		'market'       => 'fa-solid fa-arrow-trend-up',
		'markets'      => 'fa-solid fa-arrow-trend-up',
		'crypto'       => 'fa-brands fa-bitcoin',
		'bitcoin'      => 'fa-brands fa-bitcoin',
		'economy'      => 'fa-solid fa-building-columns',
		'trading'      => 'fa-solid fa-chart-bar',
		'investment'   => 'fa-solid fa-piggy-bank',
		// Politics / News
		'politics'     => 'fa-solid fa-landmark',
		'election'     => 'fa-solid fa-person-booth',
		'elections'    => 'fa-solid fa-person-booth',
		'news'         => 'fa-solid fa-newspaper',
		'government'   => 'fa-solid fa-flag',
		// Entertainment
		'bollywood'    => 'fa-solid fa-film',
		'movies'       => 'fa-solid fa-clapperboard',
		'movie'        => 'fa-solid fa-clapperboard',
		'tv'           => 'fa-solid fa-tv',
		'music'        => 'fa-solid fa-music',
		'gaming'       => 'fa-solid fa-gamepad',
		'celebrity'    => 'fa-solid fa-star',
		'entertainment' => 'fa-solid fa-masks-theater',
		// Tech
		'technology'   => 'fa-solid fa-microchip',
		'tech'         => 'fa-solid fa-microchip',
		'science'      => 'fa-solid fa-flask',
		'ai'           => 'fa-solid fa-robot',
		'space'        => 'fa-solid fa-rocket',
		// Business
		'business'     => 'fa-solid fa-briefcase',
		'startups'     => 'fa-solid fa-seedling',
		'startup'      => 'fa-solid fa-seedling',
		'companies'    => 'fa-solid fa-building',
		// Lifestyle
		'health'       => 'fa-solid fa-heart-pulse',
		'fitness'      => 'fa-solid fa-dumbbell',
		'food'         => 'fa-solid fa-utensils',
		'travel'       => 'fa-solid fa-plane',
		'fashion'      => 'fa-solid fa-shirt',
		'education'    => 'fa-solid fa-graduation-cap',
		'environment'  => 'fa-solid fa-leaf',
		'weather'      => 'fa-solid fa-cloud-sun',
		// Misc
		'awards'       => 'fa-solid fa-award',
		'award'        => 'fa-solid fa-award',
		'international' => 'fa-solid fa-globe',
		'world'        => 'fa-solid fa-globe',
		'india'        => 'fa-solid fa-flag',
	];
	foreach ($map as $keyword => $icon) {
		if (str_contains($n, $keyword)) return $icon;
	}
	return 'fa-solid fa-bolt'; // default
}

$cc_colors  = ['cc-0', 'cc-1', 'cc-2', 'cc-3', 'cc-4', 'cc-5', 'cc-6', 'cc-7'];
$cib_colors = ['cib-0', 'cib-1', 'cib-2', 'cib-3', 'cib-4', 'cib-5', 'cib-6', 'cib-7'];
?>

<div class="dash">

	<!-- ══ HERO ══════════════════════════════════════ -->
	<section class="d-hero">
		<div class="d-hero-inner">
			<div>
				<div class="d-hero-eyebrow">
					<span class="d-live-dot"></span>
					Live Markets Open
				</div>
				<h1 class="d-hero-h1">Trade Smarter.<br><em>Win Bigger.</em></h1>
				<p class="d-hero-sub">Jump into live question markets, place predictions and collect your winnings — all in one click.</p>
				<div class="d-hero-btns">
					<a class="d-btn-play" href="<?php echo site_url('questions'); ?>">
						<span class="play-icon"><i class="fa-solid fa-play"></i></span>
						Play Now
					</a>
					<a class="d-btn-ghost" href="<?php echo site_url('wallet/add_balance'); ?>">
						<i class="fa-solid fa-plus"></i> Add Balance
					</a>
				</div>
			</div>

			<div class="d-hero-panel">
				<div class="d-bal-card">
					<div class="d-bal-label">Wallet Balance</div>
					<div class="d-bal-val">₹<?php echo number_format($wallet_balance, 2); ?></div>
					<div class="d-bal-sub">Ready for live trades</div>
				</div>
				<div class="d-hero-mini-stats">
					<div class="d-mini-stat">
						<div class="d-mini-label">Trades</div>
						<div class="d-mini-val"><?php echo number_format($user_trade_count); ?></div>
					</div>
					<div class="d-mini-stat">
						<div class="d-mini-label">Categories</div>
						<div class="d-mini-val"><?php echo number_format($category_count); ?></div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- ══ BODY GRID ══════════════════════════════════ -->
	<div class="d-body">

		<!-- Play Now -->
		<div class="d-card">
			<div class="d-card-head">
				<div class="d-card-title">Quick Play</div>
				<div class="d-card-desc">Fastest routes into live markets and funding.</div>
				<div class="d-card-divider"></div>
			</div>
			<div class="d-card-body">
				<div class="d-play-grid">
					<a class="d-play-item pi-live" href="<?php echo site_url('questions'); ?>">
						<div class="d-pi-badge"><span class="d-pi-dot"></span> Live</div>
						<div class="d-pi-title">Start Trading</div>
						<div class="d-pi-desc">Browse open markets and place your call now.</div>
						<div class="d-pi-arrow"><i class="fa-solid fa-arrow-right"></i></div>
					</a>
					<a class="d-play-item pi-wallet" href="<?php echo site_url('wallet/add_balance'); ?>">
						<div class="d-pi-badge"><span class="d-pi-dot"></span> Fast</div>
						<div class="d-pi-title">Top Up Wallet</div>
						<div class="d-pi-desc">Reload in seconds — never miss a big market.</div>
						<div class="d-pi-arrow"><i class="fa-solid fa-arrow-right"></i></div>
					</a>
				</div>
			</div>
		</div>

		<!-- Account Shortcuts -->
		<div class="d-card">
			<div class="d-card-head">
				<div class="d-card-title">Account</div>
				<div class="d-card-desc">Manage wallet, profile and referrals.</div>
				<div class="d-card-divider"></div>
			</div>
			<div class="d-card-body">
				<div class="d-shortcuts">
					<a class="d-sc" href="<?php echo site_url('wallet'); ?>">
						<div class="d-sc-left">
							<div class="d-sc-icon sci-blue"><i class="fa-solid fa-wallet"></i></div>
							<div>
								<div class="d-sc-title">Wallet Overview</div>
								<div class="d-sc-sub">Deposits, withdrawals &amp; history</div>
							</div>
						</div>
						<i class="fa-solid fa-chevron-right d-sc-chev"></i>
					</a>
					<a class="d-sc" href="<?php echo site_url('profile'); ?>">
						<div class="d-sc-left">
							<div class="d-sc-icon sci-green"><i class="fa-solid fa-user"></i></div>
							<div>
								<div class="d-sc-title">Profile Settings</div>
								<div class="d-sc-sub">Update details, photo &amp; password</div>
							</div>
						</div>
						<i class="fa-solid fa-chevron-right d-sc-chev"></i>
					</a>
					<a class="d-sc" href="<?php echo site_url('referral'); ?>">
						<div class="d-sc-left">
							<div class="d-sc-icon sci-violet"><i class="fa-solid fa-gift"></i></div>
							<div>
								<div class="d-sc-title">Refer Friends</div>
								<div class="d-sc-sub">Share your invite and earn rewards</div>
							</div>
						</div>
						<i class="fa-solid fa-chevron-right d-sc-chev"></i>
					</a>
				</div>
			</div>
		</div>

		<!-- Categories with auto-icons -->
		<div class="d-card">
			<div class="d-card-head">
				<div class="d-card-title">Explore Categories</div>
				<div class="d-card-desc">Jump directly into any live category.</div>
				<div class="d-card-divider"></div>
			</div>
			<div class="d-card-body">
				<div class="d-cats-grid">
					<?php if (!empty($featured_categories)): ?>
						<?php foreach ($featured_categories as $i => $cat):
							$ci  = $i % 8;
							$ico = get_category_icon($cat->name);
						?>
							<a class="d-cat-card <?php echo $cc_colors[$ci]; ?>"
								href="<?php echo site_url('questions?category_id=' . (int)$cat->id); ?>">
								<div class="d-cat-icon-wrap <?php echo $cib_colors[$ci]; ?>">
									<i class="<?php echo $ico; ?>"></i>
								</div>
								<div class="d-cat-text">
									<div class="d-cat-name"><?php echo html_escape($cat->name); ?></div>
									<div class="d-cat-count"><?php echo (int)$cat->question_count; ?> questions</div>
								</div>
								<i class="fa-solid fa-chevron-right d-cat-arrow"></i>
							</a>
						<?php endforeach; ?>
					<?php else: ?>
						<div class="d-empty">No categories yet — check back soon.</div>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<!-- Tips -->
		<div class="d-card">
			<div class="d-card-head">
				<div class="d-card-title">Quick Tips</div>
				<div class="d-card-desc">Get the most from every session.</div>
				<div class="d-card-divider"></div>
			</div>
			<div class="d-card-body">
				<div class="d-tips">
					<div class="d-tip">
						<div class="d-tip-icon">⚡</div>
						<div>
							<div class="d-tip-title">Start with Live Categories</div>
							<div class="d-tip-desc">Use Quick Play for the fastest path into open question markets with real prizes.</div>
						</div>
					</div>
					<div class="d-tip">
						<div class="d-tip-icon">💰</div>
						<div>
							<div class="d-tip-title">Keep Your Wallet Funded</div>
							<div class="d-tip-desc">A funded wallet means you can react instantly when a strong market opens.</div>
						</div>
					</div>
					<div class="d-tip">
						<div class="d-tip-icon">🔔</div>
						<div>
							<div class="d-tip-title">Watch the Bell Icon</div>
							<div class="d-tip-desc">Trade updates, payouts and wallet alerts all land in the header notification bell.</div>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div><!-- /d-body -->

	<!-- ══ REFERRAL BANNER ═══════════════════════════ -->
	<a class="d-banner" href="<?php echo site_url('referral'); ?>">
		<div class="d-banner-text">
			<div class="d-banner-h">🎁 Refer Friends. Earn Rewards.</div>
			<div class="d-banner-sub">Share your invite link and earn bonus wallet credits for every friend who joins and plays.</div>
		</div>
		<div class="d-banner-btn">
			<i class="fa-solid fa-share-nodes"></i> Refer Now
		</div>
	</a>

</div>