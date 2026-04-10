<?php
$history_filter = isset($history_filter) ? $history_filter : 'all';
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&display=swap" rel="stylesheet">

<?php if ($this->session->flashdata('error')): ?>
	<div class="w-alert w-alert-error">
		<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
			<circle cx="12" cy="12" r="10" />
			<line x1="12" y1="8" x2="12" y2="12" />
			<line x1="12" y1="16" x2="12.01" y2="16" />
		</svg>
		<?php echo $this->session->flashdata('error'); ?>
	</div>
<?php endif; ?>
<?php if ($this->session->flashdata('success')): ?>
	<div class="w-alert w-alert-success">
		<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
			<polyline points="20 6 9 17 4 12" />
		</svg>
		<?php echo $this->session->flashdata('success'); ?>
	</div>
<?php endif; ?>

<style>
	:root {
		--ink: #0c0f1a;
		--ink-2: #3a3f52;
		--ink-3: #7b8197;
		--surface: #ffffff;
		--surface-2: #f5f6fa;
		--surface-3: #edf0f8;
		--border: rgba(12, 15, 26, .08);
		--border-2: rgba(12, 15, 26, .14);
		--accent: #1a56ff;
		--accent-2: #0038d6;
		--accent-pale: #eff4ff;
		--accent-glow: rgba(26, 86, 255, .10);
		--green: #059669;
		--green-bg: #ecfdf5;
		--green-border: #a7f3d0;
		--green-text: #065f46;
		--red: #dc2626;
		--red-bg: #fef2f2;
		--red-border: #fecaca;
		--red-text: #991b1b;
		--amber: #d97706;
		--amber-bg: #fffbeb;
		--amber-border: #fde68a;
		--amber-text: #92400e;
		--blue-bg: #eff6ff;
		--blue-border: #bfdbfe;
		--blue-text: #1d4ed8;
		--radius-sm: 8px;
		--radius: 14px;
		--radius-lg: 20px;
		--radius-xl: 26px;
		--font-display: 'Syne', sans-serif;
		--font-body: 'DM Sans', sans-serif;
		--shadow-sm: 0 1px 3px rgba(12, 15, 26, .06), 0 1px 2px rgba(12, 15, 26, .04);
		--shadow: 0 4px 16px rgba(12, 15, 26, .07), 0 1px 4px rgba(12, 15, 26, .04);
		--shadow-md: 0 8px 28px rgba(12, 15, 26, .09), 0 2px 8px rgba(12, 15, 26, .05);
	}

	* {
		box-sizing: border-box;
	}

	.w-shell {
		font-family: var(--font-body);
		color: var(--ink);
		display: grid;
		gap: 20px;
		animation: wFadeUp .4s ease both;
	}

	@keyframes wFadeUp {
		from {
			opacity: 0;
			transform: translateY(14px);
		}

		to {
			opacity: 1;
			transform: translateY(0);
		}
	}

	/* ALERTS */
	.w-alert {
		display: flex;
		align-items: center;
		gap: 10px;
		padding: 13px 18px;
		border-radius: var(--radius);
		font-size: 14px;
		font-weight: 500;
	}

	.w-alert-error {
		background: var(--red-bg);
		border: 1px solid var(--red-border);
		color: var(--red-text);
	}

	.w-alert-success {
		background: var(--green-bg);
		border: 1px solid var(--green-border);
		color: var(--green-text);
	}

	/* HERO */
	.w-hero {
		position: relative;
		overflow: hidden;
		border-radius: var(--radius-xl);
		background: var(--ink);
		display: grid;
		grid-template-columns: 1fr 280px;
		gap: 20px;
		align-items: center;
		padding: 32px 36px;
		box-shadow: var(--shadow-md);
	}

	.w-hero-bg {
		position: absolute;
		inset: 0;
		background: radial-gradient(ellipse 55% 90% at 85% 50%, rgba(26, 86, 255, .5) 0%, transparent 70%), radial-gradient(ellipse 35% 50% at 15% 10%, rgba(56, 189, 248, .15) 0%, transparent 65%);
		pointer-events: none;
	}

	.w-hero-left {
		position: relative;
		z-index: 2;
	}

	.w-hero-eyebrow {
		display: inline-flex;
		align-items: center;
		gap: 7px;
		margin-bottom: 10px;
		font-family: var(--font-display);
		font-size: 11px;
		font-weight: 700;
		letter-spacing: .1em;
		text-transform: uppercase;
		color: rgba(255, 255, 255, .5);
	}

	.w-hero-eyebrow::before {
		content: '';
		display: block;
		width: 18px;
		height: 1.5px;
		background: rgba(255, 255, 255, .3);
	}

	.w-hero h1 {
		font-family: var(--font-display);
		font-size: 32px;
		font-weight: 800;
		color: #fff;
		margin: 0 0 9px;
		line-height: 1.1;
		letter-spacing: -.01em;
	}

	.w-hero p {
		color: rgba(255, 255, 255, .56);
		font-size: 13.5px;
		line-height: 1.65;
		max-width: 500px;
		margin: 0;
	}

	.w-hero-balance {
		position: relative;
		z-index: 2;
		padding: 20px 24px;
		border-radius: var(--radius-lg);
		background: rgba(255, 255, 255, .07);
		border: 1px solid rgba(255, 255, 255, .12);
		backdrop-filter: blur(12px);
		text-align: center;
	}

	.w-hero-balance-label {
		display: block;
		margin-bottom: 8px;
		color: rgba(255, 255, 255, .6);
		font-size: 11px;
		font-weight: 700;
		letter-spacing: .09em;
		text-transform: uppercase;
	}

	.w-hero-balance-val {
		font-family: var(--font-display);
		font-size: 34px;
		font-weight: 800;
		color: #fff;
		line-height: 1;
	}

	/* STATS */
	.w-stats {
		display: grid;
		grid-template-columns: repeat(3, 1fr);
		gap: 14px;
	}

	.w-stat {
		background: var(--surface);
		border: 1px solid var(--border);
		border-radius: var(--radius-lg);
		padding: 20px 22px;
		box-shadow: var(--shadow-sm);
		display: flex;
		align-items: center;
		gap: 15px;
		transition: box-shadow .2s, transform .2s;
	}

	.w-stat:hover {
		box-shadow: var(--shadow);
		transform: translateY(-2px);
	}

	.w-stat-icon {
		width: 44px;
		height: 44px;
		border-radius: var(--radius-sm);
		display: flex;
		align-items: center;
		justify-content: center;
		flex-shrink: 0;
	}

	.w-stat-icon.blue {
		background: var(--blue-bg);
	}

	.w-stat-icon.green {
		background: var(--green-bg);
	}

	.w-stat-icon.red {
		background: var(--red-bg);
	}

	.w-stat-body span {
		display: block;
		font-size: 11px;
		font-weight: 700;
		letter-spacing: .07em;
		text-transform: uppercase;
		color: var(--ink-3);
		margin-bottom: 5px;
	}

	.w-stat-body strong {
		font-family: var(--font-display);
		font-size: 24px;
		font-weight: 700;
		color: var(--ink);
		line-height: 1;
	}

	/* MAIN LAYOUT */
	.w-grid {
		display: grid;
		grid-template-columns: minmax(0, 1fr) 340px;
		gap: 20px;
		align-items: start;
	}

	.w-col-main,
	.w-col-side {
		display: grid;
		gap: 20px;
	}

	/* CARDS */
	.w-card {
		background: var(--surface);
		border: 1px solid var(--border);
		border-radius: var(--radius-xl);
		padding: 26px;
		box-shadow: var(--shadow-sm);
	}

	.w-card-head {
		display: flex;
		align-items: flex-start;
		justify-content: space-between;
		gap: 16px;
		margin-bottom: 18px;
	}

	.w-card-head h2 {
		font-family: var(--font-display);
		font-size: 19px;
		font-weight: 700;
		color: var(--ink);
		margin: 0 0 4px;
	}

	.w-card-head p {
		font-size: 13px;
		color: var(--ink-3);
		margin: 0;
		line-height: 1.5;
	}

	/* GHOST + PRIMARY BTNS */
	.w-primary-btn,
	.w-ghost-btn {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		gap: 7px;
		padding: 11px 18px;
		border-radius: var(--radius);
		font-family: var(--font-body);
		font-weight: 600;
		font-size: 14px;
		text-decoration: none;
		border: none;
		cursor: pointer;
		transition: all .15s;
	}

	.w-primary-btn {
		background: var(--accent);
		color: #fff;
	}

	.w-primary-btn:hover {
		background: var(--accent-2);
	}

	.w-primary-btn:disabled {
		opacity: .45;
		cursor: not-allowed;
	}

	.w-primary-btn-full {
		width: 100%;
		margin-top: 8px;
	}

	.w-ghost-btn {
		background: var(--surface-2);
		color: var(--ink-2);
		border: 1px solid var(--border-2);
	}

	.w-ghost-btn:hover {
		background: var(--surface-3);
	}

	/* BANK FORM */
	.w-form-grid,
	.w-bank-grid {
		display: grid;
		grid-template-columns: repeat(2, minmax(0, 1fr));
		gap: 14px;
	}

	.w-field {
		display: flex;
		flex-direction: column;
		gap: 6px;
	}

	.w-field label {
		font-size: 11px;
		font-weight: 700;
		letter-spacing: .07em;
		text-transform: uppercase;
		color: var(--ink-3);
	}

	.w-field input {
		width: 100%;
		padding: 11px 14px;
		border-radius: var(--radius-sm);
		border: 1px solid var(--border-2);
		background: var(--surface-2);
		font-family: var(--font-body);
		font-size: 14.5px;
		color: var(--ink);
		transition: border-color .15s, box-shadow .15s;
	}

	.w-field input:focus {
		outline: none;
		border-color: var(--accent);
		box-shadow: 0 0 0 3px var(--accent-glow);
		background: var(--surface);
	}

	.w-field small {
		font-size: 12px;
		color: var(--ink-3);
	}

	.w-form-actions {
		grid-column: 1/-1;
		display: flex;
		justify-content: flex-end;
		gap: 10px;
		margin-top: 4px;
	}

	.w-bank-box {
		padding: 14px 16px;
		border-radius: var(--radius);
		background: var(--surface-2);
		border: 1px solid var(--border);
	}

	.w-bank-box span {
		display: block;
		font-size: 11px;
		font-weight: 700;
		letter-spacing: .07em;
		text-transform: uppercase;
		color: var(--ink-3);
		margin-bottom: 6px;
	}

	.w-bank-box strong {
		font-size: 15px;
		color: var(--ink);
	}

	/* TRANSACTION TABLE CARD */
	.w-tx-card {
		background: var(--surface);
		border: 1px solid var(--border);
		border-radius: var(--radius-xl);
		box-shadow: var(--shadow-sm);
		overflow: hidden;
	}

	.w-tx-header {
		padding: 20px 24px 0;
	}

	.w-tx-title-row {
		display: flex;
		align-items: center;
		gap: 12px;
		margin-bottom: 14px;
	}

	.w-tx-title-row h2 {
		font-family: var(--font-display);
		font-size: 19px;
		font-weight: 700;
		color: var(--ink);
		margin: 0;
	}

	.w-tx-count {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		padding: 3px 10px;
		border-radius: 999px;
		background: var(--surface-3);
		border: 1px solid var(--border-2);
		font-size: 12px;
		font-weight: 600;
		color: var(--ink-3);
	}

	/* TYPE FILTER PILLS */
	.w-filter-bar {
		display: flex;
		align-items: center;
		gap: 8px;
		flex-wrap: wrap;
		padding-bottom: 18px;
		border-bottom: 1px solid var(--border);
	}

	.w-filter-pill {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		padding: 8px 16px;
		border-radius: 999px;
		font-size: 13px;
		font-weight: 600;
		text-decoration: none;
		border: 1.5px solid var(--border-2);
		color: var(--ink-2);
		background: var(--surface);
		transition: all .15s;
		white-space: nowrap;
	}

	.w-filter-pill .pill-dot {
		width: 7px;
		height: 7px;
		border-radius: 50%;
		background: currentColor;
		opacity: .5;
	}

	.w-filter-pill:hover {
		border-color: var(--accent);
		color: var(--accent);
		background: var(--accent-pale);
	}

	.w-filter-pill.active {
		border-color: transparent;
		color: #fff;
		background: var(--accent);
		box-shadow: 0 2px 8px rgba(26, 86, 255, .3);
	}

	.w-filter-pill.active .pill-dot {
		opacity: 1;
		background: #fff;
	}

	/* Colored active states per type */
	.w-filter-pill[data-type="winnings"].active {
		background: #059669;
		box-shadow: 0 2px 8px rgba(5, 150, 105, .3);
	}

	.w-filter-pill[data-type="withdrawals"].active {
		background: #dc2626;
		box-shadow: 0 2px 8px rgba(220, 38, 38, .3);
	}

	.w-filter-pill[data-type="deposits"].active {
		background: #7c3aed;
		box-shadow: 0 2px 8px rgba(124, 58, 237, .3);
	}

	.w-filter-pill[data-type="trades"].active {
		background: #d97706;
		box-shadow: 0 2px 8px rgba(217, 119, 6, .3);
	}

	.w-filter-pill[data-type="refunds"].active {
		background: #0891b2;
		box-shadow: 0 2px 8px rgba(8, 145, 178, .3);
	}

	/* ROWS SELECTOR (right side of filter bar) */
	.w-rows-selector {
		margin-left: auto;
		position: relative;
	}

	.w-rows-btn {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		padding: 8px 14px;
		border-radius: 999px;
		font-size: 13px;
		font-weight: 600;
		color: var(--ink-2);
		background: var(--surface);
		border: 1.5px solid var(--border-2);
		cursor: pointer;
		transition: border-color .15s;
		white-space: nowrap;
	}

	.w-rows-btn:hover {
		border-color: var(--border-2);
		background: var(--surface-2);
	}

	.w-rows-btn svg {
		transition: transform .2s;
	}

	.w-rows-btn.open svg {
		transform: rotate(180deg);
	}

	.w-rows-dropdown {
		position: absolute;
		top: calc(100% + 6px);
		right: 0;
		z-index: 50;
		background: var(--surface);
		border: 1px solid var(--border-2);
		border-radius: var(--radius);
		box-shadow: var(--shadow-md);
		min-width: 130px;
		overflow: hidden;
		display: none;
	}

	.w-rows-dropdown.open {
		display: block;
	}

	.w-rows-option {
		display: flex;
		align-items: center;
		justify-content: space-between;
		padding: 10px 16px;
		font-size: 13.5px;
		font-weight: 500;
		color: var(--ink-2);
		cursor: pointer;
		transition: background .1s;
	}

	.w-rows-option:hover {
		background: var(--surface-2);
	}

	.w-rows-option.active {
		color: var(--accent);
		font-weight: 700;
		background: var(--accent-pale);
	}

	.w-rows-option.active::after {
		content: '';
		display: block;
		width: 6px;
		height: 6px;
		border-radius: 50%;
		background: var(--accent);
	}

	/* TABLE */
	.w-table-wrap {
		overflow-x: auto;
	}

	.w-table {
		width: 100%;
		border-collapse: collapse;
		min-width: 680px;
	}

	.w-table thead tr {
		background: var(--surface-2);
	}

	.w-table th {
		padding: 12px 20px;
		font-size: 11px;
		font-weight: 700;
		letter-spacing: .07em;
		text-transform: uppercase;
		color: var(--ink-3);
		text-align: left;
	}

	.w-table td {
		padding: 15px 20px;
		border-top: 1px solid var(--border);
		font-size: 14px;
		color: var(--ink);
	}

	.w-table tbody tr {
		transition: background .1s;
	}

	.w-table tbody tr:hover {
		background: var(--surface-2);
	}

	/* BADGES */
	.w-badge {
		display: inline-flex;
		align-items: center;
		gap: 5px;
		padding: 5px 12px;
		border-radius: 999px;
		font-size: 12px;
		font-weight: 700;
	}

	.w-badge-dot {
		width: 5px;
		height: 5px;
		border-radius: 50%;
	}

	.wb-deposit {
		background: #f3f0ff;
		color: #6d28d9;
	}

	.wb-deposit .w-badge-dot {
		background: #7c3aed;
	}

	.wb-winning {
		background: var(--green-bg);
		color: var(--green-text);
	}

	.wb-winning .w-badge-dot {
		background: #059669;
	}

	.wb-trade {
		background: var(--amber-bg);
		color: var(--amber-text);
	}

	.wb-trade .w-badge-dot {
		background: #d97706;
	}

	.wb-withdrawal {
		background: var(--red-bg);
		color: var(--red-text);
	}

	.wb-withdrawal .w-badge-dot {
		background: #dc2626;
	}

	.wb-referral {
		background: #fff0f9;
		color: #9d174d;
	}

	.wb-referral .w-badge-dot {
		background: #ec4899;
	}

	.wb-refund {
		background: #f0fdfe;
		color: #0e7490;
	}

	.wb-refund .w-badge-dot {
		background: #0891b2;
	}

	.wb-default {
		background: var(--surface-3);
		color: var(--ink-2);
	}

	.ws-success {
		background: var(--green-bg);
		color: var(--green-text);
	}

	.ws-processed,
	.ws-pending {
		background: var(--blue-bg);
		color: var(--blue-text);
	}

	.ws-failed,
	.ws-rejected {
		background: var(--red-bg);
		color: var(--red-text);
	}

	.ws-approved {
		background: var(--green-bg);
		color: var(--green-text);
	}

	.w-amount-credit {
		color: var(--green);
		font-weight: 700;
	}

	.w-amount-debit {
		color: var(--red);
		font-weight: 700;
	}

	.w-empty-row td {
		text-align: center;
		padding: 40px;
		color: var(--ink-3);
	}

	/* PAGINATION */
	.w-pagination {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 12px;
		padding: 14px 20px;
		border-top: 1px solid var(--border);
	}

	.w-page-info {
		font-size: 13px;
		color: var(--ink-3);
	}

	.w-page-btns {
		display: flex;
		gap: 6px;
	}

	.w-page-btn {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		width: 32px;
		height: 32px;
		border-radius: 8px;
		border: 1px solid var(--border-2);
		background: var(--surface);
		color: var(--ink-2);
		font-size: 13px;
		font-weight: 600;
		cursor: pointer;
		transition: all .12s;
	}

	.w-page-btn:hover:not(:disabled) {
		background: var(--surface-3);
		border-color: var(--border-2);
	}

	.w-page-btn.active {
		background: var(--accent);
		color: #fff;
		border-color: var(--accent);
	}

	.w-page-btn:disabled {
		opacity: .35;
		cursor: not-allowed;
	}

	/* SIDE FORMS */
	.w-withdrawals {
		margin-top: 18px;
		padding-top: 18px;
		border-top: 1px solid var(--border);
	}

	.w-withdrawals h3 {
		font-family: var(--font-display);
		font-size: 13px;
		font-weight: 700;
		letter-spacing: .06em;
		text-transform: uppercase;
		color: var(--ink-3);
		margin: 0 0 12px;
	}

	.w-wr-row {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 12px;
		padding: 12px 14px;
		border-radius: var(--radius);
		background: var(--surface-2);
		border: 1px solid var(--border);
		margin-bottom: 8px;
	}

	.w-wr-row strong {
		display: block;
		font-size: 14px;
		color: var(--ink);
	}

	.w-wr-row small {
		display: block;
		font-size: 12px;
		color: var(--ink-3);
		margin-top: 2px;
	}

	.w-side-empty {
		margin: 0;
		font-size: 13.5px;
		color: var(--ink-3);
	}

	/* RESPONSIVE */
	@media(max-width:1060px) {

		.w-hero,
		.w-grid,
		.w-stats {
			grid-template-columns: 1fr;
		}

		.w-hero-balance {
			display: none;
		}
	}

	@media(max-width:680px) {

		.w-form-grid,
		.w-bank-grid,
		.w-stats {
			grid-template-columns: 1fr;
		}

		.w-filter-bar {
			gap: 6px;
		}

		.w-filter-pill {
			padding: 7px 12px;
			font-size: 12px;
		}
	}
</style>

<div class="w-shell">

	<!-- HERO -->
	<section class="w-hero">
		<div class="w-hero-bg"></div>
		<div class="w-hero-left">
			<span class="w-hero-eyebrow">Wallet</span>
			<h1>Balance &amp; Transactions</h1>
			<p>Deposit funds, request withdrawals, manage your bank account, and review your full transaction history.</p>
		</div>
		<div class="w-hero-balance">
			<span class="w-hero-balance-label">Available Balance</span>
			<strong class="w-hero-balance-val">₹<?php echo number_format((float) $user->wallet_balance, 2); ?></strong>
		</div>
	</section>

	<!-- STATS -->
	<section class="w-stats">
		<div class="w-stat">
			<div class="w-stat-icon blue">
				<svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="#1a56ff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<rect x="2" y="5" width="20" height="14" rx="3" />
					<path d="M2 10h20" />
				</svg>
			</div>
			<div class="w-stat-body">
				<span>Total Balance</span>
				<strong>₹<?php echo number_format((float) $user->wallet_balance, 2); ?></strong>
			</div>
		</div>
		<div class="w-stat">
			<div class="w-stat-icon green">
				<svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<polyline points="22 7 13.5 15.5 8.5 10.5 2 17" />
					<polyline points="16 7 22 7 22 13" />
				</svg>
			</div>
			<div class="w-stat-body">
				<span>Total Winnings</span>
				<strong>₹<?php echo number_format((float) $total_winnings, 2); ?></strong>
			</div>
		</div>
		<div class="w-stat">
			<div class="w-stat-icon red">
				<svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
				</svg>
			</div>
			<div class="w-stat-body">
				<span>Total Withdrawn</span>
				<strong>₹<?php echo number_format((float) $total_withdrawn, 2); ?></strong>
			</div>
		</div>
	</section>

	<!-- MAIN GRID -->
	<div class="w-grid">
		<div class="w-col-main">

			<!-- BANK DETAILS -->
			<section class="w-card">
				<div class="w-card-head">
					<div>
						<h2>Bank Details</h2>
						<p><?php echo ($bank_details_saved && !$edit_bank_details) ? 'Saved bank details used for withdrawals.' : 'Add your bank details to enable withdrawals.'; ?></p>
					</div>
					<?php if ($bank_details_saved && !$edit_bank_details): ?>
						<a class="w-ghost-btn" href="<?php echo site_url('wallet?edit_bank=1&history=' . $history_filter); ?>">
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
								<path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
								<path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
							</svg>
							Edit
						</a>
					<?php endif; ?>
				</div>

				<?php if ($edit_bank_details): ?>
					<form method="post" action="<?php echo site_url('wallet/save-bank-details'); ?>" class="w-form-grid">
						<div class="w-field">
							<label for="bank_account_holder_name">Account Holder</label>
							<input type="text" id="bank_account_holder_name" name="bank_account_holder_name" value="<?php echo set_value('bank_account_holder_name', $user->bank_account_holder_name); ?>" required placeholder="Full name">
						</div>
						<div class="w-field">
							<label for="bank_name">Bank Name</label>
							<input type="text" id="bank_name" name="bank_name" value="<?php echo set_value('bank_name', $user->bank_name); ?>" required placeholder="e.g. HDFC Bank">
						</div>
						<div class="w-field">
							<label for="bank_account_number">Account Number</label>
							<input type="text" id="bank_account_number" name="bank_account_number" value="<?php echo set_value('bank_account_number', $user->bank_account_number); ?>" required placeholder="Enter account number">
						</div>
						<div class="w-field">
							<label for="bank_ifsc_code">IFSC Code</label>
							<input type="text" id="bank_ifsc_code" name="bank_ifsc_code" value="<?php echo set_value('bank_ifsc_code', $user->bank_ifsc_code); ?>" required placeholder="e.g. HDFC0001234">
						</div>
						<div class="w-form-actions">
							<?php if ($bank_details_saved): ?>
								<a class="w-ghost-btn" href="<?php echo site_url('wallet?history=' . $history_filter); ?>">Cancel</a>
							<?php endif; ?>
							<button type="submit" class="w-primary-btn"><?php echo $bank_details_saved ? 'Update Details' : 'Save Details'; ?></button>
						</div>
					</form>
				<?php else: ?>
					<div class="w-bank-grid">
						<div class="w-bank-box">
							<span>Account Holder</span>
							<strong><?php echo html_escape($user->bank_account_holder_name ?: '—'); ?></strong>
						</div>
						<div class="w-bank-box">
							<span>Bank Name</span>
							<strong><?php echo html_escape($user->bank_name ?: '—'); ?></strong>
						</div>
						<div class="w-bank-box">
							<span>Account Number</span>
							<strong><?php echo html_escape($user->bank_account_number ?: '—'); ?></strong>
						</div>
						<div class="w-bank-box">
							<span>IFSC Code</span>
							<strong><?php echo html_escape($user->bank_ifsc_code ?: '—'); ?></strong>
						</div>
					</div>
				<?php endif; ?>
			</section>

			<!-- TRANSACTION TABLE -->
			<section class="w-tx-card">
				<div class="w-tx-header">
					<div class="w-tx-title-row">
						<h2>Transaction History</h2>
						<span class="w-tx-count" id="tx-count"><?php echo count($transactions); ?> records</span>
					</div>

					<!-- FILTER BAR -->
					<div class="w-filter-bar" id="tx-filter-bar">
						<?php
						$wallet_filters = array(
							'all'         => array('label' => 'All',         'icon' => ''),
							'deposits'    => array('label' => 'Deposits',    'icon' => ''),
							'winnings'    => array('label' => 'Winnings',    'icon' => ''),
							'withdrawals' => array('label' => 'Withdrawals', 'icon' => ''),
							'trades'      => array('label' => 'Trades',      'icon' => ''),
							'refunds'     => array('label' => 'Refunds',     'icon' => ''),
						);
						?>
						<?php foreach ($wallet_filters as $fk => $fv): ?>
							<a class="w-filter-pill <?php echo $history_filter === $fk ? 'active' : ''; ?>"
								data-filter="<?php echo $fk; ?>"
								data-type="<?php echo $fk; ?>"
								href="<?php echo site_url('wallet?history=' . $fk); ?>">
								<span class="pill-dot"></span>
								<?php echo $fv['label']; ?>
							</a>
						<?php endforeach; ?>

						<!-- ROWS SELECTOR -->
						<div class="w-rows-selector">
							<button class="w-rows-btn" id="rows-btn" onclick="toggleRowsDropdown(event)">
								<span id="rows-label">10 rows</span>
								<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
									<polyline points="6 9 12 15 18 9" />
								</svg>
							</button>
							<div class="w-rows-dropdown" id="rows-dropdown">
								<div class="w-rows-option active" data-rows="10" onclick="setRows(10, this)">10 rows <span></span></div>
								<div class="w-rows-option" data-rows="25" onclick="setRows(25, this)">25 rows</div>
								<div class="w-rows-option" data-rows="50" onclick="setRows(50, this)">50 rows</div>
								<div class="w-rows-option" data-rows="100" onclick="setRows(100, this)">100 rows</div>
								<div class="w-rows-option" data-rows="99999" onclick="setRows(99999, this)">View all</div>
							</div>
						</div>
					</div>
				</div>

				<div class="w-table-wrap">
					<table class="w-table" id="tx-table">
						<thead>
							<tr>
								<th>#</th>
								<th>Type</th>
								<th>Amount</th>
								<th>Status</th>
								<th>Date &amp; Time</th>
							</tr>
						</thead>
						<tbody id="tx-body">
							<?php if (!empty($transactions)): ?>
								<?php foreach ($transactions as $index => $tx): ?>
									<?php
									$is_credit = strtolower((string) $tx->type) === 'credit';
									$type_label = $is_credit ? 'Credit' : 'Debit';
									$badge_class = 'wb-default';
									$tx_filter_type = 'all';

									if ($tx->source_type === 'deposit') {
										$type_label = 'Deposit';
										$badge_class = 'wb-deposit';
										$tx_filter_type = 'deposits';
									} elseif ($tx->source_type === 'question_result') {
										$type_label = 'Winning';
										$badge_class = 'wb-winning';
										$tx_filter_type = 'winnings';
									} elseif ($tx->source_type === 'trade_entry') {
										$type_label = 'Trade';
										$badge_class = 'wb-trade';
										$tx_filter_type = 'trades';
									} elseif ($tx->source_type === 'withdrawal') {
										$type_label = 'Withdrawal';
										$badge_class = 'wb-withdrawal';
										$tx_filter_type = 'withdrawals';
									} elseif ($tx->source_type === 'referral_bonus') {
										$type_label = 'Referral';
										$badge_class = 'wb-referral';
										$tx_filter_type = 'all';
									} elseif ($tx->source_type === 'refund') {
										$type_label = 'Refund';
										$badge_class = 'wb-refund';
										$tx_filter_type = 'refunds';
									}

									$status_class = $is_credit ? 'ws-success' : 'ws-processed';
									$status_label = $is_credit ? 'Success' : 'Processed';
									?>
									<tr data-filter-type="<?php echo $tx_filter_type; ?>">
										<td style="color:var(--ink-3);font-size:13px;"><?php echo $index + 1; ?></td>
										<td>
											<span class="w-badge <?php echo $badge_class; ?>">
												<span class="w-badge-dot"></span>
												<?php echo html_escape($type_label); ?>
											</span>
										</td>
										<td class="w-amount-<?php echo $is_credit ? 'credit' : 'debit'; ?>">
											<?php echo $is_credit ? '+' : '−'; ?> ₹<?php echo number_format((float) $tx->amount, 2); ?>
										</td>
										<td><span class="w-badge <?php echo $status_class; ?>"><?php echo $status_label; ?></span></td>
										<td style="color:var(--ink-2);font-size:13px;"><?php echo html_escape(date('d M Y, h:i A', strtotime($tx->created_at))); ?></td>
									</tr>
								<?php endforeach; ?>
							<?php else: ?>
								<tr class="w-empty-row">
									<td colspan="5">No transactions found.</td>
								</tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>

				<div class="w-pagination" id="tx-pagination">
					<span class="w-page-info" id="tx-page-info">Showing 1–10</span>
					<div class="w-page-btns" id="tx-page-btns"></div>
				</div>
			</section>

		</div><!-- /col-main -->

		<div class="w-col-side">

			<!-- DEPOSIT -->
			<section class="w-card">
				<div class="w-card-head">
					<div>
						<h2>Deposit</h2>
						<p>Funds added instantly to your wallet.</p>
					</div>
					<div style="width:40px;height:40px;border-radius:10px;background:var(--accent-pale);display:flex;align-items:center;justify-content:center;">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
							<line x1="12" y1="5" x2="12" y2="19" />
							<line x1="5" y1="12" x2="19" y2="12" />
						</svg>
					</div>
				</div>
				<form method="post" action="<?php echo site_url('wallet/deposit'); ?>">
					<div class="w-field">
						<label for="deposit_amount">Amount (₹)</label>
						<input type="number" id="deposit_amount" name="amount" min="1" step="0.01" max="100000" placeholder="0.00" required>
					</div>
					<button type="submit" class="w-primary-btn w-primary-btn-full">
						<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
							<line x1="12" y1="5" x2="12" y2="19" />
							<line x1="5" y1="12" x2="19" y2="12" />
						</svg>
						Add Funds
					</button>
				</form>
			</section>

			<!-- WITHDRAWAL -->
			<section class="w-card">
				<div class="w-card-head">
					<div>
						<h2>Withdraw</h2>
						<p><?php echo $bank_details_saved ? 'Admin approval required before transfer.' : 'Save bank details first to unlock.'; ?></p>
					</div>
					<div style="width:40px;height:40px;border-radius:10px;background:<?php echo $bank_details_saved ? 'var(--red-bg)' : 'var(--surface-3)'; ?>;display:flex;align-items:center;justify-content:center;">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="<?php echo $bank_details_saved ? 'var(--red)' : 'var(--ink-3)'; ?>" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
							<line x1="12" y1="5" x2="12" y2="19" />
							<polyline points="19 12 12 19 5 12" />
						</svg>
					</div>
				</div>
				<form method="post" action="<?php echo site_url('wallet/request-withdrawal'); ?>">
					<div class="w-field">
						<label for="wd_amount">Amount (₹)</label>
						<input type="number" id="wd_amount" name="amount" min="1" step="0.01"
							max="<?php echo number_format((float) $user->wallet_balance, 2, '.', ''); ?>"
							placeholder="0.00"
							<?php echo $bank_details_saved ? '' : 'disabled'; ?> required>
						<?php if ($bank_details_saved): ?>
							<small>Max: ₹<?php echo number_format((float) $user->wallet_balance, 2); ?></small>
						<?php endif; ?>
					</div>
					<button type="submit" class="w-primary-btn w-primary-btn-full"
						style="background:<?php echo $bank_details_saved ? 'var(--red)' : ''; ?>;"
						<?php echo $bank_details_saved ? '' : 'disabled'; ?>>
						<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
							<line x1="12" y1="5" x2="12" y2="19" />
							<polyline points="19 12 12 19 5 12" />
						</svg>
						Request Withdrawal
					</button>
				</form>

				<div class="w-withdrawals">
					<h3>Recent Requests</h3>
					<?php if (!empty($withdrawals)): ?>
						<?php foreach (array_slice($withdrawals, 0, 4) as $wr): ?>
							<?php
							$ws = strtolower($wr->status);
							$wsc = in_array($ws, ['approved', 'success']) ? 'ws-approved' : (in_array($ws, ['pending']) ? 'ws-pending' : (in_array($ws, ['rejected', 'failed']) ? 'ws-failed' : 'ws-processed'));
							?>
							<div class="w-wr-row">
								<div>
									<strong>₹<?php echo number_format((float) $wr->amount, 2); ?></strong>
									<small><?php echo html_escape(date('d M Y, h:i A', strtotime($wr->created_at))); ?></small>
								</div>
								<span class="w-badge <?php echo $wsc; ?>"><?php echo ucfirst(html_escape($wr->status)); ?></span>
							</div>
						<?php endforeach; ?>
					<?php else: ?>
						<p class="w-side-empty">No withdrawal requests yet.</p>
					<?php endif; ?>
				</div>
			</section>

		</div><!-- /col-side -->
	</div>
</div>

<script>
	(function() {
		// ── Rows dropdown ──
		window.toggleRowsDropdown = function(e) {
			e.stopPropagation();
			const btn = document.getElementById('rows-btn');
			const dd = document.getElementById('rows-dropdown');
			const open = dd.classList.toggle('open');
			btn.classList.toggle('open', open);
		};

		document.addEventListener('click', function() {
			document.getElementById('rows-dropdown').classList.remove('open');
			document.getElementById('rows-btn').classList.remove('open');
		});

		// ── State ──
		let currentFilter = '<?php echo $history_filter; ?>';
		let rowsPerPage = 10;
		let currentPage = 1;

		// ── Rows setter ──
		window.setRows = function(n, el) {
			rowsPerPage = n;
			currentPage = 1;
			document.getElementById('rows-label').textContent = n >= 99999 ? 'View all' : n + ' rows';
			document.querySelectorAll('.w-rows-option').forEach(o => o.classList.remove('active'));
			el.classList.add('active');
			document.getElementById('rows-dropdown').classList.remove('open');
			document.getElementById('rows-btn').classList.remove('open');
			render();
		};

		// ── Get visible rows ──
		function getRows() {
			const all = Array.from(document.querySelectorAll('#tx-body tr[data-filter-type]'));
			if (currentFilter === 'all') return all;
			return all.filter(r => r.dataset.filterType === currentFilter);
		}

		// ── Render ──
		function render() {
			const rows = getRows();
			const total = rows.length;
			const limit = rowsPerPage >= 99999 ? total : rowsPerPage;
			const pages = Math.max(1, Math.ceil(total / limit));
			currentPage = Math.min(currentPage, pages);
			const start = (currentPage - 1) * limit;
			const end = start + limit;

			// show/hide all rows
			Array.from(document.querySelectorAll('#tx-body tr[data-filter-type]')).forEach(r => {
				r.style.display = 'none';
			});
			rows.forEach((r, i) => {
				r.style.display = (i >= start && i < end) ? '' : 'none';
				// re-number
				const numCell = r.querySelector('td:first-child');
				if (numCell) numCell.textContent = i + 1;
			});

			// empty state
			let emptyRow = document.getElementById('tx-empty');
			if (total === 0) {
				if (!emptyRow) {
					emptyRow = document.createElement('tr');
					emptyRow.id = 'tx-empty';
					emptyRow.className = 'w-empty-row';
					emptyRow.innerHTML = '<td colspan="5">No transactions found for this filter.</td>';
					document.getElementById('tx-body').appendChild(emptyRow);
				}
				emptyRow.style.display = '';
			} else if (emptyRow) {
				emptyRow.style.display = 'none';
			}

			// count badge
			document.getElementById('tx-count').textContent = total + ' record' + (total !== 1 ? 's' : '');

			// page info
			const actualEnd = Math.min(end, total);
			document.getElementById('tx-page-info').textContent =
				total === 0 ? 'No records' :
				'Showing ' + (start + 1) + '–' + actualEnd + ' of ' + total;

			// page buttons
			const btnContainer = document.getElementById('tx-page-btns');
			btnContainer.innerHTML = '';

			// prev
			const prev = makePageBtn('‹', currentPage <= 1);
			prev.addEventListener('click', function() {
				if (currentPage > 1) {
					currentPage--;
					render();
				}
			});
			btnContainer.appendChild(prev);

			// numbered (show up to 5 pages around current)
			const spread = 2;
			for (let p = 1; p <= pages; p++) {
				if (p === 1 || p === pages || (p >= currentPage - spread && p <= currentPage + spread)) {
					const pb = makePageBtn(p, false, p === currentPage);
					pb.addEventListener('click', (function(pg) {
						return function() {
							currentPage = pg;
							render();
						};
					})(p));
					btnContainer.appendChild(pb);
				} else if (
					(p === currentPage - spread - 1 && currentPage - spread > 2) ||
					(p === currentPage + spread + 1 && currentPage + spread < pages - 1)
				) {
					const dots = document.createElement('span');
					dots.textContent = '…';
					dots.style.cssText = 'padding:0 4px;color:var(--ink-3);font-size:13px;align-self:center;';
					btnContainer.appendChild(dots);
				}
			}

			// next
			const next = makePageBtn('›', currentPage >= pages);
			next.addEventListener('click', function() {
				if (currentPage < pages) {
					currentPage++;
					render();
				}
			});
			btnContainer.appendChild(next);

			// hide pagination if only 1 page
			document.getElementById('tx-pagination').style.display = pages <= 1 && total <= limit ? 'none' : 'flex';
		}

		function makePageBtn(label, disabled, active) {
			const b = document.createElement('button');
			b.className = 'w-page-btn' + (active ? ' active' : '');
			b.textContent = label;
			b.disabled = !!disabled;
			return b;
		}

		// ── Filter pills (client-side for instant UX; also href for page reload fallback) ──
		document.querySelectorAll('.w-filter-pill').forEach(function(pill) {
			pill.addEventListener('click', function(e) {
				e.preventDefault();
				currentFilter = this.dataset.filter;
				currentPage = 1;
				document.querySelectorAll('.w-filter-pill').forEach(p => p.classList.remove('active'));
				this.classList.add('active');
				render();
			});
		});

		// initial render
		render();
	})();
</script>