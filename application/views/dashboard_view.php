<?php
$wallet_balance = isset($user->wallet_balance) ? (float) $user->wallet_balance : 12450.75;
$user_trade_count = isset($user_trade_count) ? (int) $user_trade_count : 247;
$categories = isset($categories) && is_array($categories) ? $categories : array();
$featured_categories = array_slice($categories, 0, 6);
$category_count = count($categories) ?: 12;
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400&display=swap" rel="stylesheet">

<style>
	:root {
		--f-head: 'Roboto', sans-serif;
		--f-body: 'Roboto', sans-serif;
		--ink: #0a0d14;
		--ink-2: #1c2235;
		--ink-3: #2d3550;
		--surface: #f0f2f8;
		--surface-2: #e4e8f2;
		--white: #ffffff;
		--accent: #5b5ef4;
		--accent-2: #7c7ff7;
		--accent-glow: rgba(91, 94, 244, 0.18);
		--green: #00c896;
		--green-soft: rgba(0, 200, 150, 0.1);
		--red: #ff4d6a;
		--red-soft: rgba(255, 77, 106, 0.1);
		--gold: #f5a623;
		--gold-soft: rgba(245, 166, 35, 0.1);
		--border: rgba(0, 0, 0, 0.07);
		--border-2: rgba(0, 0, 0, 0.12);
		--shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.06);
		--shadow-md: 0 8px 24px rgba(0, 0, 0, 0.08);
		--shadow-lg: 0 20px 48px rgba(0, 0, 0, 0.1);
		--r-sm: 12px;
		--r-md: 18px;
		--r-lg: 24px;
		--r-xl: 32px;
	}

	*,
	*::before,
	*::after {
		box-sizing: border-box;
		margin: 0;
		padding: 0;
	}

	.dash {
		font-family: var(--f-body);
		color: var(--ink);
		display: grid;
		gap: 20px;
		padding: 4px;
	}

	/* ─── HERO ─── */
	.d-hero {
		position: relative;
		border-radius: var(--r-xl);
		background: var(--ink);
		overflow: hidden;
		padding: 40px 44px;
		color: var(--white);
		isolation: isolate;
	}

	.d-hero-bg {
		position: absolute;
		inset: 0;
		z-index: 0;
		background:
			radial-gradient(ellipse 80% 60% at 110% -10%, rgba(91, 94, 244, 0.55) 0%, transparent 60%),
			radial-gradient(ellipse 60% 80% at -10% 110%, rgba(0, 200, 150, 0.25) 0%, transparent 60%),
			radial-gradient(ellipse 40% 40% at 50% 50%, rgba(28, 34, 53, 0.8) 0%, transparent 80%),
			linear-gradient(160deg, #0a0d14 0%, #1a1e32 100%);
	}

	.d-hero-noise {
		position: absolute;
		inset: 0;
		z-index: 0;
		opacity: 0.035;
		background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
		background-size: 180px;
	}

	.d-hero-orb {
		position: absolute;
		border-radius: 50%;
		z-index: 0;
	}

	.d-hero-orb-1 {
		width: 420px;
		height: 420px;
		right: -80px;
		top: -160px;
		background: radial-gradient(circle, rgba(91, 94, 244, 0.3) 0%, transparent 70%);
		animation: orbFloat 8s ease-in-out infinite;
	}

	.d-hero-orb-2 {
		width: 250px;
		height: 250px;
		left: 30%;
		bottom: -100px;
		background: radial-gradient(circle, rgba(0, 200, 150, 0.2) 0%, transparent 70%);
		animation: orbFloat 12s ease-in-out infinite reverse;
	}

	@keyframes orbFloat {

		0%,
		100% {
			transform: translateY(0) scale(1);
		}

		50% {
			transform: translateY(-20px) scale(1.05);
		}
	}

	.d-hero-inner {
		position: relative;
		z-index: 1;
		display: grid;
		grid-template-columns: 1fr auto;
		gap: 32px;
		align-items: start;
	}

	.d-hero-label {
		display: inline-flex;
		align-items: center;
		gap: 7px;
		padding: 6px 14px;
		border-radius: 999px;
		background: rgba(255, 255, 255, 0.08);
		border: 1px solid rgba(255, 255, 255, 0.12);
		font-family: var(--f-head);
		font-size: 11px;
		font-weight: 600;
		letter-spacing: 0.12em;
		text-transform: uppercase;
		color: rgba(255, 255, 255, 0.7);
		margin-bottom: 20px;
	}

	.d-hero-label-dot {
		width: 6px;
		height: 6px;
		border-radius: 50%;
		background: var(--green);
		animation: pulse 2s ease-in-out infinite;
	}

	@keyframes pulse {

		0%,
		100% {
			opacity: 1;
			transform: scale(1);
		}

		50% {
			opacity: 0.5;
			transform: scale(0.7);
		}
	}

	.d-hero-h1 {
		font-family: var(--f-head);
		font-size: clamp(28px, 3.6vw, 48px);
		font-weight: 800;
		line-height: 1.05;
		letter-spacing: -0.03em;
		margin-bottom: 16px;
		color: #fff;
	}

	.d-hero-h1 em {
		font-style: normal;
		background: linear-gradient(90deg, #7c7ff7, #00c896);
		-webkit-background-clip: text;
		-webkit-text-fill-color: transparent;
		background-clip: text;
	}

	.d-hero-sub {
		font-size: 15px;
		line-height: 1.7;
		color: rgba(255, 255, 255, 0.58);
		max-width: 500px;
		margin-bottom: 28px;
	}

	.d-hero-actions {
		display: flex;
		gap: 12px;
		flex-wrap: wrap;
	}

	.d-btn {
		display: inline-flex;
		align-items: center;
		gap: 8px;
		padding: 13px 22px;
		border-radius: var(--r-sm);
		font-family: var(--f-head);
		font-size: 13px;
		font-weight: 700;
		text-decoration: none;
		letter-spacing: 0.02em;
		transition: all 0.2s ease;
		cursor: pointer;
		border: none;
	}

	.d-btn-primary {
		background: var(--white);
		color: var(--ink);
		box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25);
	}

	.d-btn-primary:hover {
		transform: translateY(-2px);
		box-shadow: 0 16px 36px rgba(0, 0, 0, 0.3);
	}

	.d-btn-ghost {
		background: rgba(255, 255, 255, 0.08);
		color: rgba(255, 255, 255, 0.85);
		border: 1px solid rgba(255, 255, 255, 0.14);
	}

	.d-btn-ghost:hover {
		background: rgba(255, 255, 255, 0.13);
		transform: translateY(-2px);
	}

	/* Hero right — stats panel */
	.d-hero-panel {
		width: 280px;
		flex-shrink: 0;
	}

	.d-hero-balance {
		background: rgba(255, 255, 255, 0.07);
		border: 1px solid rgba(255, 255, 255, 0.1);
		border-radius: var(--r-lg);
		padding: 22px 24px;
		backdrop-filter: blur(20px);
		margin-bottom: 12px;
	}

	.d-balance-label {
		font-family: var(--f-head);
		font-size: 10px;
		font-weight: 600;
		text-transform: uppercase;
		letter-spacing: 0.12em;
		color: rgba(255, 255, 255, 0.45);
		margin-bottom: 8px;
	}

	.d-balance-amount {
		font-family: var(--f-head);
		font-size: 38px;
		font-weight: 800;
		letter-spacing: -0.04em;
		line-height: 1;
		color: #fff;
		margin-bottom: 10px;
	}

	.d-balance-sub {
		font-size: 12px;
		color: rgba(255, 255, 255, 0.45);
		line-height: 1.5;
	}

	.d-hero-stats {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 10px;
	}

	.d-stat-mini {
		background: rgba(255, 255, 255, 0.06);
		border: 1px solid rgba(255, 255, 255, 0.09);
		border-radius: var(--r-md);
		padding: 16px;
		backdrop-filter: blur(10px);
	}

	.d-stat-mini-label {
		font-size: 10px;
		font-family: var(--f-head);
		font-weight: 600;
		text-transform: uppercase;
		letter-spacing: 0.1em;
		color: rgba(255, 255, 255, 0.4);
		margin-bottom: 6px;
	}

	.d-stat-mini-val {
		font-family: var(--f-head);
		font-size: 26px;
		font-weight: 800;
		letter-spacing: -0.03em;
		color: #fff;
	}

	.d-stat-mini-hint {
		font-size: 11px;
		color: rgba(255, 255, 255, 0.38);
		margin-top: 4px;
	}

	/* ─── BODY GRID ─── */
	.d-body {
		display: grid;
		grid-template-columns: repeat(2, minmax(0, 1fr));
		gap: 20px;
		align-items: start;
	}

	/* ─── CARDS ─── */
	.d-card {
		background: var(--white);
		border-radius: var(--r-xl);
		border: 1px solid var(--border);
		box-shadow: var(--shadow-md);
		overflow: hidden;
	}

	.d-card-head {
		padding: 24px 28px 0;
	}

	.d-card-title {
		font-family: var(--f-head);
		font-size: 18px;
		font-weight: 700;
		letter-spacing: -0.02em;
		color: var(--ink);
		margin-bottom: 4px;
	}

	.d-card-desc {
		font-size: 13px;
		color: #8892a4;
		line-height: 1.5;
	}

	.d-card-divider {
		height: 1px;
		background: var(--border);
		margin: 20px 0 0;
	}

	.d-card-body {
		padding: 24px 28px;
	}

	/* ─── PLAY GRID ─── */
	.d-play-grid {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 14px;
	}

	.d-play-item {
		border-radius: var(--r-lg);
		padding: 22px;
		text-decoration: none;
		color: inherit;
		border: 1px solid var(--border);
		background: linear-gradient(145deg, #fafbff 0%, #f3f5fc 100%);
		transition: all 0.22s ease;
		display: flex;
		flex-direction: column;
		gap: 0;
		position: relative;
		overflow: hidden;
	}

	.d-play-item::before {
		content: '';
		position: absolute;
		inset: 0;
		border-radius: inherit;
		opacity: 0;
		transition: opacity 0.2s;
	}

	.d-play-item.pi-live::before {
		background: linear-gradient(145deg, rgba(0, 200, 150, 0.06), rgba(0, 200, 150, 0.02));
	}

	.d-play-item.pi-wallet::before {
		background: linear-gradient(145deg, rgba(91, 94, 244, 0.06), rgba(91, 94, 244, 0.02));
	}

	.d-play-item:hover {
		transform: translateY(-3px);
		box-shadow: var(--shadow-lg);
		border-color: transparent;
	}

	.d-play-item:hover::before {
		opacity: 1;
	}

	.d-play-badge {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		padding: 5px 11px;
		border-radius: 999px;
		font-family: var(--f-head);
		font-size: 10px;
		font-weight: 700;
		letter-spacing: 0.08em;
		text-transform: uppercase;
		margin-bottom: 16px;
		width: fit-content;
	}

	.pi-live .d-play-badge {
		background: var(--green-soft);
		color: var(--green);
	}

	.pi-wallet .d-play-badge {
		background: var(--accent-glow);
		color: var(--accent);
	}

	.d-play-badge-dot {
		width: 5px;
		height: 5px;
		border-radius: 50%;
		background: currentColor;
	}

	.pi-live .d-play-badge-dot {
		animation: pulse 1.8s infinite;
	}

	.d-play-title {
		font-family: var(--f-head);
		font-size: 17px;
		font-weight: 700;
		letter-spacing: -0.02em;
		color: var(--ink);
		margin-bottom: 8px;
		line-height: 1.3;
	}

	.d-play-desc {
		font-size: 12.5px;
		line-height: 1.65;
		color: #8892a4;
		margin-bottom: 20px;
		flex: 1;
	}

	.d-play-arrow {
		width: 36px;
		height: 36px;
		border-radius: 10px;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		font-size: 13px;
		background: var(--ink);
		color: #fff;
		align-self: flex-start;
		transition: transform 0.2s;
	}

	.d-play-item:hover .d-play-arrow {
		transform: translateX(3px);
	}

	/* ─── SHORTCUTS ─── */
	.d-shortcuts {
		display: flex;
		flex-direction: column;
		gap: 10px;
	}

	.d-shortcut {
		display: flex;
		align-items: center;
		gap: 16px;
		padding: 16px 18px;
		border-radius: var(--r-md);
		border: 1px solid var(--border);
		text-decoration: none;
		color: inherit;
		background: #fafbff;
		transition: all 0.18s ease;
		justify-content: space-between;
	}

	.d-shortcut:hover {
		border-color: var(--accent);
		background: rgba(91, 94, 244, 0.03);
		transform: translateX(3px);
	}

	.d-shortcut-left {
		display: flex;
		align-items: center;
		gap: 14px;
	}

	.d-shortcut-icon {
		width: 44px;
		height: 44px;
		border-radius: 13px;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 16px;
		flex-shrink: 0;
	}

	.sc-blue {
		background: rgba(91, 94, 244, 0.1);
		color: var(--accent);
	}

	.sc-green {
		background: var(--green-soft);
		color: var(--green);
	}

	.sc-gold {
		background: var(--gold-soft);
		color: var(--gold);
	}

	.d-shortcut-title {
		font-family: var(--f-head);
		font-size: 14px;
		font-weight: 700;
		color: var(--ink);
		margin-bottom: 3px;
	}

	.d-shortcut-sub {
		font-size: 12px;
		color: #8892a4;
		line-height: 1.45;
	}

	.d-shortcut-chevron {
		color: #c5cdd9;
		font-size: 13px;
		flex-shrink: 0;
		transition: transform 0.18s, color 0.18s;
	}

	.d-shortcut:hover .d-shortcut-chevron {
		color: var(--accent);
		transform: translateX(2px);
	}

	/* ─── CATEGORIES ─── */
	.d-cat-wrap {
		display: flex;
		flex-wrap: wrap;
		gap: 8px;
	}

	.d-cat-chip {
		display: inline-flex;
		align-items: center;
		gap: 8px;
		padding: 10px 16px;
		border-radius: 999px;
		background: var(--surface);
		border: 1px solid var(--border);
		text-decoration: none;
		color: var(--ink);
		font-family: var(--f-head);
		font-size: 13px;
		font-weight: 600;
		transition: all 0.18s ease;
	}

	.d-cat-chip:hover {
		background: var(--ink);
		color: #fff;
		border-color: var(--ink);
		transform: translateY(-2px);
		box-shadow: var(--shadow-md);
	}

	.d-cat-count {
		background: var(--white);
		border: 1px solid var(--border-2);
		border-radius: 999px;
		min-width: 26px;
		height: 26px;
		padding: 0 7px;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		font-size: 11px;
		font-weight: 800;
		color: var(--accent);
		transition: all 0.18s;
	}

	.d-cat-chip:hover .d-cat-count {
		background: rgba(255, 255, 255, 0.15);
		border-color: rgba(255, 255, 255, 0.2);
		color: #fff;
	}

	.d-empty {
		padding: 20px;
		border-radius: var(--r-md);
		border: 1.5px dashed var(--border-2);
		background: var(--surface);
		font-size: 13px;
		color: #8892a4;
		text-align: center;
	}

	/* ─── TIPS ─── */
	.d-tips {
		display: grid;
		gap: 10px;
	}

	.d-tip {
		padding: 18px 20px;
		border-radius: var(--r-md);
		border: 1px solid var(--border);
		background: var(--surface);
		display: flex;
		gap: 14px;
		align-items: flex-start;
	}

	.d-tip-icon {
		width: 34px;
		height: 34px;
		border-radius: 10px;
		background: var(--white);
		border: 1px solid var(--border);
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 15px;
		flex-shrink: 0;
	}

	.d-tip-title {
		font-family: var(--f-head);
		font-size: 13px;
		font-weight: 700;
		color: var(--ink);
		margin-bottom: 4px;
	}

	.d-tip-desc {
		font-size: 12.5px;
		line-height: 1.65;
		color: #8892a4;
	}

	/* ─── RESPONSIVE ─── */
	@media (max-width: 1100px) {
		.d-hero-inner {
			grid-template-columns: 1fr;
		}

		.d-hero-panel {
			width: 100%;
		}

		.d-hero-stats {
			grid-template-columns: repeat(4, 1fr);
		}
	}

	@media (max-width: 860px) {
		.d-body {
			grid-template-columns: 1fr;
		}

		.d-hero {
			padding: 28px;
		}
	}

	@media (max-width: 600px) {
		.d-play-grid {
			grid-template-columns: 1fr;
		}

		.d-hero-stats {
			grid-template-columns: 1fr 1fr;
		}

		.d-card-head {
			padding: 20px 20px 0;
		}

		.d-card-body {
			padding: 20px;
		}
	}
</style>

<div class="dash">

	<!-- HERO -->
	<section class="d-hero">
		<div class="d-hero-bg"></div>
		<div class="d-hero-noise"></div>
		<div class="d-hero-orb d-hero-orb-1"></div>
		<div class="d-hero-orb d-hero-orb-2"></div>

		<div class="d-hero-inner">
			<div>
				<div class="d-hero-label">
					<span class="d-hero-label-dot"></span>
					Markets are live
				</div>
				<h1 class="d-hero-h1">Trade smarter.<br><em>Win bigger.</em></h1>
				<p class="d-hero-sub">Jump into live question markets, manage your wallet, and track your performance — all from one focused dashboard.</p>
				<div class="d-hero-actions">
					<a class="d-btn d-btn-primary" href="<?php echo site_url('questions'); ?>">
						<i class="fa-solid fa-play"></i> Play Now
					</a>
					<a class="d-btn d-btn-ghost" href="<?php echo site_url('wallet/add_balance'); ?>">
						<i class="fa-solid fa-wallet"></i> Add Balance
					</a>
				</div>
			</div>

			<div class="d-hero-panel">
				<div class="d-hero-balance">
					<div class="d-balance-label">Available Balance</div>
					<div class="d-balance-amount">₹<?php echo number_format($wallet_balance, 2); ?></div>
					<div class="d-balance-sub">Ready for live trades and fast market entry</div>
				</div>
				<div class="d-hero-stats">
					<div class="d-stat-mini">
						<div class="d-stat-mini-label">Trades</div>
						<div class="d-stat-mini-val"><?php echo number_format($user_trade_count); ?></div>
						<div class="d-stat-mini-hint">Markets answered</div>
					</div>
					<div class="d-stat-mini">
						<div class="d-stat-mini-label">Categories</div>
						<div class="d-stat-mini-val"><?php echo number_format($category_count); ?></div>
						<div class="d-stat-mini-hint">To browse</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- BODY -->
	<div class="d-body">

		<!-- Play Now -->
		<div class="d-card">
			<div class="d-card-head">
				<div class="d-card-title">Play Now</div>
				<div class="d-card-desc">Your fastest routes into live trading and wallet actions.</div>
				<div class="d-card-divider"></div>
			</div>
			<div class="d-card-body">
				<div class="d-play-grid">
					<a class="d-play-item pi-live" href="<?php echo site_url('questions'); ?>">
						<div class="d-play-badge">
							<span class="d-play-badge-dot"></span> Live Markets
						</div>
						<div class="d-play-title">Start Trading</div>
						<div class="d-play-desc">Browse open question categories and place trades with fewer clicks.</div>
						<div class="d-play-arrow"><i class="fa-solid fa-arrow-right"></i></div>
					</a>
					<a class="d-play-item pi-wallet" href="<?php echo site_url('wallet/add_balance'); ?>">
						<div class="d-play-badge">
							<span class="d-play-badge-dot"></span> Fast Funding
						</div>
						<div class="d-play-title">Top Up Wallet</div>
						<div class="d-play-desc">Add balance so you're always ready when a strong market appears.</div>
						<div class="d-play-arrow"><i class="fa-solid fa-arrow-right"></i></div>
					</a>
				</div>
			</div>
		</div>

		<!-- Account Shortcuts -->
		<div class="d-card">
			<div class="d-card-head">
				<div class="d-card-title">Account Shortcuts</div>
				<div class="d-card-desc">Manage your wallet, profile, and referral flow.</div>
				<div class="d-card-divider"></div>
			</div>
			<div class="d-card-body">
				<div class="d-shortcuts">
					<a class="d-shortcut" href="<?php echo site_url('wallet'); ?>">
						<div class="d-shortcut-left">
							<div class="d-shortcut-icon sc-blue"><i class="fa-solid fa-wallet"></i></div>
							<div>
								<div class="d-shortcut-title">Wallet Overview</div>
								<div class="d-shortcut-sub">Deposits, withdrawals and balance history</div>
							</div>
						</div>
						<i class="fa-solid fa-chevron-right d-shortcut-chevron"></i>
					</a>
					<a class="d-shortcut" href="<?php echo site_url('profile'); ?>">
						<div class="d-shortcut-left">
							<div class="d-shortcut-icon sc-green"><i class="fa-solid fa-user"></i></div>
							<div>
								<div class="d-shortcut-title">Profile Settings</div>
								<div class="d-shortcut-sub">Update details, photo and password</div>
							</div>
						</div>
						<i class="fa-solid fa-chevron-right d-shortcut-chevron"></i>
					</a>
					<a class="d-shortcut" href="<?php echo site_url('referral'); ?>">
						<div class="d-shortcut-left">
							<div class="d-shortcut-icon sc-gold"><i class="fa-solid fa-gift"></i></div>
							<div>
								<div class="d-shortcut-title">Refer Friends</div>
								<div class="d-shortcut-sub">Share your invite and earn rewards</div>
							</div>
						</div>
						<i class="fa-solid fa-chevron-right d-shortcut-chevron"></i>
					</a>
				</div>
			</div>
		</div>

		<!-- Categories -->
		<div class="d-card">
			<div class="d-card-head">
				<div class="d-card-title">Explore Categories</div>
				<div class="d-card-desc">Jump directly into any live category.</div>
				<div class="d-card-divider"></div>
			</div>
			<div class="d-card-body">
				<?php if (!empty($featured_categories)): ?>
					<div class="d-cat-wrap">
						<?php foreach ($featured_categories as $cat): ?>
							<a class="d-cat-chip" href="<?php echo site_url('questions?category_id=' . (int)$cat->id); ?>">
								<?php echo html_escape($cat->name); ?>
								<span class="d-cat-count"><?php echo (int)$cat->question_count; ?></span>
							</a>
						<?php endforeach; ?>
					</div>
				<?php else: ?>
					<div class="d-empty">No categories yet — check back soon.</div>
				<?php endif; ?>
			</div>
		</div>

		<!-- Tips -->
		<div class="d-card">
			<div class="d-card-head">
				<div class="d-card-title">Quick Tips</div>
				<div class="d-card-desc">Use the app more smoothly with these reminders.</div>
				<div class="d-card-divider"></div>
			</div>
			<div class="d-card-body">
				<div class="d-tips">
					<div class="d-tip">
						<div class="d-tip-icon">⚡</div>
						<div>
							<div class="d-tip-title">Start with Live Categories</div>
							<div class="d-tip-desc">Use Play Now for the quickest path into open question markets.</div>
						</div>
					</div>
					<div class="d-tip">
						<div class="d-tip-icon">💰</div>
						<div>
							<div class="d-tip-title">Keep Your Wallet Ready</div>
							<div class="d-tip-desc">A funded wallet lets you react fast when a strong market appears.</div>
						</div>
					</div>
					<div class="d-tip">
						<div class="d-tip-icon">🔔</div>
						<div>
							<div class="d-tip-title">Watch the Bell Icon</div>
							<div class="d-tip-desc">Trade updates, payouts and wallet alerts live in the header.</div>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>