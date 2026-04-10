<?php
$profile_image = !empty($user->profile_image) ? $user->profile_image : 'assets/images/default-profile.svg';
$profile_image_src = preg_match('/^https?:\/\//i', $profile_image) ? $profile_image : base_url($profile_image);
$referral_summary = isset($referral_summary) ? $referral_summary : array('referral_count' => 0, 'bonus_amount' => 0);
$notifications = isset($notifications) ? $notifications : array();
$wallet_balance = isset($user->wallet_balance) ? (float) $user->wallet_balance : 0;
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
	:root {
		--f: 'Plus Jakarta Sans', sans-serif;
		--ink: #111827;
		--ink2: #374151;
		--ink3: #6b7280;
		--ink4: #9ca3af;
		--bg: #f9fafb;
		--card: #ffffff;
		--bd: #e5e7eb;
		--bd2: #d1d5db;
		--blue: #2563eb;
		--blue-lt: #eff6ff;
		--blue-bd: #bfdbfe;
		--blue-mid: #3b82f6;
		--green: #059669;
		--green-lt: #ecfdf5;
		--green-bd: #6ee7b7;
		--amber: #d97706;
		--amber-lt: #fffbeb;
		--amber-bd: #fde68a;
		--violet: #7c3aed;
		--violet-lt: #f5f3ff;
		--violet-bd: #ddd6fe;
		--red: #dc2626;
		--red-lt: #fef2f2;
		--red-bd: #fecaca;
		--r: 10px;
		--r-lg: 14px;
		--r-xl: 18px;
		--sh: 0 1px 3px rgba(0, 0, 0, .07), 0 1px 2px rgba(0, 0, 0, .04);
		--sh2: 0 4px 12px rgba(0, 0, 0, .08), 0 1px 4px rgba(0, 0, 0, .04);
	}

	*,
	*::before,
	*::after {
		box-sizing: border-box;
		margin: 0;
		padding: 0;
	}

	.ld {
		font-family: var(--f);
		color: var(--ink);
		background: var(--bg);
		display: grid;
		gap: 14px;
		animation: ldIn .35s ease both;
	}

	@keyframes ldIn {
		from {
			opacity: 0;
			transform: translateY(10px);
		}

		to {
			opacity: 1;
			transform: translateY(0);
		}
	}

	/* TOP GREETING STRIP */
	.ld-top {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 12px;
		background: var(--card);
		border: 1px solid var(--bd);
		border-radius: var(--r-xl);
		padding: 14px 20px;
		box-shadow: var(--sh);
	}

	.ld-top-left {
		display: flex;
		align-items: center;
		gap: 12px;
	}

	.ld-avatar {
		position: relative;
		width: 46px;
		height: 46px;
		flex-shrink: 0;
	}

	.ld-avatar img {
		width: 100%;
		height: 100%;
		border-radius: 50%;
		object-fit: cover;
		border: 2px solid var(--bd);
		display: block;
	}

	.ld-avatar-dot {
		position: absolute;
		bottom: 1px;
		right: 1px;
		width: 11px;
		height: 11px;
		border-radius: 50%;
		background: var(--green);
		border: 2px solid white;
	}

	.ld-greeting small {
		display: block;
		font-size: 11.5px;
		color: var(--ink3);
		font-weight: 500;
		margin-bottom: 2px;
	}

	.ld-greeting strong {
		font-size: 17px;
		font-weight: 800;
		color: var(--ink);
		letter-spacing: -.02em;
	}

	.ld-greeting span {
		color: var(--blue);
	}

	.ld-top-email {
		font-size: 12.5px;
		color: var(--ink3);
	}

	/* STAT ROW */
	.ld-stats {
		display: grid;
		grid-template-columns: repeat(3, 1fr);
		gap: 10px;
	}

	.ld-stat {
		background: var(--card);
		border: 1px solid var(--bd);
		border-radius: var(--r-lg);
		padding: 14px 16px;
		display: flex;
		align-items: center;
		gap: 12px;
		box-shadow: var(--sh);
		transition: box-shadow .18s, transform .18s;
	}

	.ld-stat:hover {
		box-shadow: var(--sh2);
		transform: translateY(-1px);
	}

	.ld-stat-ico {
		width: 40px;
		height: 40px;
		border-radius: var(--r);
		flex-shrink: 0;
		display: flex;
		align-items: center;
		justify-content: center;
	}

	.ld-stat-ico.g {
		background: var(--green-lt);
		border: 1px solid var(--green-bd);
	}

	.ld-stat-ico.b {
		background: var(--blue-lt);
		border: 1px solid var(--blue-bd);
	}

	.ld-stat-ico.v {
		background: var(--violet-lt);
		border: 1px solid var(--violet-bd);
	}

	.ld-stat-body>span {
		display: block;
		font-size: 10.5px;
		font-weight: 700;
		letter-spacing: .06em;
		text-transform: uppercase;
		color: var(--ink3);
		margin-bottom: 4px;
	}

	.ld-stat-body>strong {
		font-size: 21px;
		font-weight: 800;
		color: var(--ink);
		letter-spacing: -.02em;
		line-height: 1;
	}

	/* MAIN GRID */
	.ld-grid {
		display: grid;
		grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
		gap: 14px;
		align-items: start;
	}

	.ld-col {
		display: grid;
		gap: 14px;
	}

	/* CARD */
	.ld-card {
		background: var(--card);
		border: 1px solid var(--bd);
		border-radius: var(--r-xl);
		box-shadow: var(--sh);
		overflow: hidden;
	}

	.ld-card-hd {
		padding: 14px 18px 12px;
		border-bottom: 1px solid var(--bd);
		display: flex;
		align-items: flex-start;
		justify-content: space-between;
		gap: 10px;
	}

	.ld-card-hd h2 {
		font-size: 14px;
		font-weight: 800;
		color: var(--ink);
		letter-spacing: -.01em;
	}

	.ld-card-hd p {
		font-size: 12px;
		color: var(--ink3);
		margin-top: 2px;
	}

	.ld-card-body {
		padding: 14px 18px;
	}

	/* BALANCE BANNER */
	.ld-balance {
		background: linear-gradient(120deg, #1d4ed8 0%, #2563eb 55%, #4f46e5 100%);
		border-radius: var(--r-xl);
		padding: 18px 22px;
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 14px;
		box-shadow: 0 4px 18px rgba(37, 99, 235, .25);
		position: relative;
		overflow: hidden;
	}

	.ld-balance::before {
		content: '';
		position: absolute;
		top: -30px;
		right: -30px;
		width: 120px;
		height: 120px;
		border-radius: 50%;
		background: rgba(255, 255, 255, .07);
		pointer-events: none;
	}

	.ld-balance-left small {
		display: block;
		font-size: 11px;
		font-weight: 600;
		letter-spacing: .08em;
		text-transform: uppercase;
		color: rgba(255, 255, 255, .65);
		margin-bottom: 6px;
	}

	.ld-balance-left strong {
		font-size: 34px;
		font-weight: 800;
		color: #fff;
		letter-spacing: -.03em;
		line-height: 1;
	}

	.ld-balance-left p {
		font-size: 12px;
		color: rgba(255, 255, 255, .6);
		margin-top: 5px;
	}

	.ld-balance-right {
		display: flex;
		align-items: center;
		justify-content: center;
		width: 52px;
		height: 52px;
		border-radius: 50%;
		background: rgba(255, 255, 255, .15);
		border: 1px solid rgba(255, 255, 255, .2);
		flex-shrink: 0;
	}

	/* QUICK LINKS */
	.ld-quick {
		display: grid;
		grid-template-columns: repeat(3, 1fr);
		gap: 8px;
		padding: 12px;
	}

	.ld-qlink {
		display: flex;
		flex-direction: column;
		align-items: center;
		gap: 8px;
		padding: 14px 10px;
		border-radius: var(--r-lg);
		background: var(--bg);
		border: 1px solid var(--bd);
		text-decoration: none;
		transition: border-color .15s, background .15s, transform .15s;
		text-align: center;
	}

	.ld-qlink:hover {
		border-color: var(--blue-bd);
		background: var(--blue-lt);
		transform: translateY(-1px);
	}

	.ld-qlink:hover .ld-qlink-arrow {
		background: var(--blue-lt);
		color: var(--blue);
	}

	.ld-qlink-ico {
		width: 38px;
		height: 38px;
		border-radius: 10px;
		display: flex;
		align-items: center;
		justify-content: center;
	}

	.ld-qlink-ico.q {
		background: var(--violet-lt);
		border: 1px solid var(--violet-bd);
	}

	.ld-qlink-ico.w {
		background: var(--green-lt);
		border: 1px solid var(--green-bd);
	}

	.ld-qlink-ico.p {
		background: var(--blue-lt);
		border: 1px solid var(--blue-bd);
	}

	.ld-qlink>span {
		font-size: 13px;
		font-weight: 700;
		color: var(--ink);
	}

	.ld-qlink>small {
		font-size: 11px;
		color: var(--ink3);
		line-height: 1.4;
	}

	.ld-qlink-arrow {
		width: 22px;
		height: 22px;
		border-radius: 50%;
		background: #f3f4f6;
		display: flex;
		align-items: center;
		justify-content: center;
		color: var(--ink3);
		transition: background .15s, color .15s;
	}

	/* REFERRAL */
	.ld-ref-body {
		padding: 12px 14px;
		display: grid;
		gap: 10px;
	}

	.ld-ref-desc {
		font-size: 12.5px;
		color: var(--ink3);
		line-height: 1.6;
	}

	.ld-ref-code-box {
		display: flex;
		align-items: center;
		gap: 10px;
		padding: 12px 14px;
		border-radius: var(--r);
		background: var(--amber-lt);
		border: 1.5px dashed var(--amber-bd);
	}

	.ld-ref-code-box>div {
		flex: 1;
		min-width: 0;
	}

	.ld-ref-code-lbl {
		display: block;
		font-size: 10px;
		font-weight: 700;
		letter-spacing: .09em;
		text-transform: uppercase;
		color: var(--amber);
		margin-bottom: 3px;
	}

	.ld-ref-code-val {
		font-size: 20px;
		font-weight: 800;
		color: #92400e;
		letter-spacing: .05em;
	}

	.ld-copy-btn {
		display: inline-flex;
		align-items: center;
		gap: 5px;
		padding: 8px 13px;
		border-radius: 8px;
		background: #fef3c7;
		border: 1px solid var(--amber-bd);
		color: #92400e;
		font-family: var(--f);
		font-size: 12px;
		font-weight: 700;
		cursor: pointer;
		transition: background .12s;
		white-space: nowrap;
		flex-shrink: 0;
	}

	.ld-copy-btn:hover {
		background: var(--amber-bd);
	}

	.ld-ref-stats {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 8px;
	}

	.ld-ref-stat {
		padding: 11px 13px;
		border-radius: var(--r);
		background: var(--bg);
		border: 1px solid var(--bd);
	}

	.ld-ref-stat span {
		display: block;
		font-size: 10px;
		font-weight: 700;
		letter-spacing: .07em;
		text-transform: uppercase;
		color: var(--ink3);
		margin-bottom: 4px;
	}

	.ld-ref-stat strong {
		font-size: 18px;
		font-weight: 800;
		color: var(--ink);
	}

	/* NOTIFICATIONS */
	.ld-notif-toolbar {
		display: flex;
		align-items: center;
		gap: 8px;
		padding: 10px 14px 0;
	}

	.ld-notif-badge {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		min-width: 20px;
		height: 20px;
		padding: 0 6px;
		border-radius: 999px;
		background: var(--blue);
		color: #fff;
		font-size: 11px;
		font-weight: 700;
	}

	.ld-mark-all {
		display: inline-flex;
		align-items: center;
		gap: 4px;
		padding: 5px 11px;
		border-radius: 7px;
		background: #f3f4f6;
		border: 1px solid var(--bd);
		color: var(--ink3);
		font-size: 11.5px;
		font-weight: 600;
		text-decoration: none;
		transition: background .12s, color .12s;
		margin-left: auto;
	}

	.ld-mark-all:hover {
		background: var(--bd);
		color: var(--ink);
	}

	.ld-notif-list {
		padding: 10px 12px;
		display: grid;
		gap: 7px;
		max-height: 400px;
		overflow-y: auto;
	}

	.ld-notif-list::-webkit-scrollbar {
		width: 3px;
	}

	.ld-notif-list::-webkit-scrollbar-thumb {
		background: var(--bd2);
		border-radius: 2px;
	}

	.ld-notif-item {
		padding: 11px 13px;
		border-radius: var(--r);
		background: var(--bg);
		border: 1px solid var(--bd);
		position: relative;
		transition: border-color .12s;
	}

	.ld-notif-item.unread {
		background: var(--blue-lt);
		border-color: var(--blue-bd);
	}

	.ld-notif-item.unread::before {
		content: '';
		position: absolute;
		left: 0;
		top: 50%;
		transform: translateY(-50%);
		width: 3px;
		height: 55%;
		border-radius: 0 2px 2px 0;
		background: var(--blue);
	}

	.ld-notif-title {
		font-size: 13px;
		font-weight: 700;
		color: var(--ink);
		margin-bottom: 3px;
	}

	.ld-notif-msg {
		font-size: 12px;
		color: var(--ink2);
		line-height: 1.5;
	}

	.ld-notif-time {
		display: block;
		font-size: 11px;
		color: var(--ink4);
		margin-top: 5px;
	}

	.ld-notif-empty {
		padding: 24px 16px;
		text-align: center;
	}

	.ld-notif-empty-ico {
		width: 44px;
		height: 44px;
		border-radius: 12px;
		background: var(--bg);
		border: 1px solid var(--bd);
		display: flex;
		align-items: center;
		justify-content: center;
		margin: 0 auto 10px;
	}

	.ld-notif-empty p {
		font-size: 12.5px;
		color: var(--ink3);
		line-height: 1.6;
	}

	/* RESPONSIVE */
	@media(max-width:900px) {
		.ld-grid {
			grid-template-columns: 1fr;
		}
	}

	@media(max-width:600px) {
		.ld-stats {
			grid-template-columns: 1fr;
		}

		.ld-quick {
			grid-template-columns: 1fr;
		}

		.ld-top-email {
			display: none;
		}
	}
</style>

<div class="ld">

	<!-- GREETING STRIP -->
	<div class="ld-top">
		<div class="ld-top-left">
			<div class="ld-avatar">
				<img src="<?php echo html_escape($profile_image_src); ?>" alt="Avatar">
				<div class="ld-avatar-dot"></div>
			</div>
			<div class="ld-greeting">
				<small>Welcome back —</small>
				<strong><span><?php echo html_escape($user->name); ?></span></strong>
			</div>
		</div>
		<span class="ld-top-email"><?php echo html_escape($user->email); ?></span>
	</div>

	<!-- STATS -->
	<div class="ld-stats">
		<div class="ld-stat">
			<div class="ld-stat-ico g">
				<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<rect x="2" y="5" width="20" height="14" rx="3" />
					<path d="M2 10h20" />
				</svg>
			</div>
			<div class="ld-stat-body">
				<span>Wallet Balance</span>
				<strong>₹<?php echo number_format($wallet_balance, 2); ?></strong>
			</div>
		</div>
		<div class="ld-stat">
			<div class="ld-stat-ico v">
				<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<polyline points="22 7 13.5 15.5 8.5 10.5 2 17" />
					<polyline points="16 7 22 7 22 13" />
				</svg>
			</div>
			<div class="ld-stat-body">
				<span>Total Trades</span>
				<strong><?php echo number_format((int)$user_trade_count); ?></strong>
			</div>
		</div>
		<div class="ld-stat">
			<div class="ld-stat-ico b">
				<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
					<path d="M13.73 21a2 2 0 0 1-3.46 0" />
				</svg>
			</div>
			<div class="ld-stat-body">
				<span>Unread Alerts</span>
				<strong><?php echo number_format((int)$unread_notifications); ?></strong>
			</div>
		</div>
	</div>

	<!-- MAIN GRID -->
	<div class="ld-grid">

		<!-- LEFT -->
		<div class="ld-col">

			<!-- BALANCE -->
			<div class="ld-balance">
				<div class="ld-balance-left">
					<small>Available Balance</small>
					<strong>₹<?php echo number_format($wallet_balance, 2); ?></strong>
					<p>Ready to trade or withdraw</p>
				</div>
				<div class="ld-balance-right">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.85)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<rect x="2" y="5" width="20" height="14" rx="3" />
						<path d="M2 10h20" />
					</svg>
				</div>
			</div>

			<!-- QUICK ACCESS -->
			<div class="ld-card">
				<div class="ld-card-hd">
					<div>
						<h2>Quick Access</h2>
						<p>Jump to any section instantly</p>
					</div>
				</div>
				<div class="ld-quick">
					<a class="ld-qlink" href="<?php echo site_url('questions'); ?>">
						<div class="ld-qlink-ico q">
							<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<circle cx="12" cy="12" r="10" />
								<path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
								<line x1="12" y1="17" x2="12.01" y2="17" />
							</svg>
						</div>
						<span>Questions</span>
						<small>Browse &amp; trade markets</small>
						<div class="ld-qlink-arrow"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
								<path d="M5 12h14M12 5l7 7-7 7" />
							</svg></div>
					</a>
					<a class="ld-qlink" href="<?php echo site_url('wallet'); ?>">
						<div class="ld-qlink-ico w">
							<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<rect x="2" y="5" width="20" height="14" rx="3" />
								<path d="M2 10h20" />
							</svg>
						</div>
						<span>Wallet</span>
						<small>Deposits &amp; withdrawals</small>
						<div class="ld-qlink-arrow"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
								<path d="M5 12h14M12 5l7 7-7 7" />
							</svg></div>
					</a>
					<a class="ld-qlink" href="<?php echo site_url('profile'); ?>">
						<div class="ld-qlink-ico p">
							<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
								<circle cx="12" cy="7" r="4" />
							</svg>
						</div>
						<span>Profile</span>
						<small>Account &amp; password</small>
						<div class="ld-qlink-arrow"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
								<path d="M5 12h14M12 5l7 7-7 7" />
							</svg></div>
					</a>
				</div>
			</div>

			<!-- REFERRAL -->
			<div class="ld-card">
				<div class="ld-card-hd">
					<div>
						<h2>Referral Program</h2>
						<p>Share your code — both sides earn the signup bonus</p>
					</div>
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
					</svg>
				</div>
				<div class="ld-ref-body">
					<div class="ld-ref-code-box">
						<div>
							<span class="ld-ref-code-lbl">Your referral code</span>
							<span class="ld-ref-code-val" id="ref-code-v"><?php echo html_escape($referral_code); ?></span>
						</div>
						<button class="ld-copy-btn" id="ref-copy-btn" onclick="ldCopy()">
							<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
								<rect x="9" y="9" width="13" height="13" rx="2" />
								<path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" />
							</svg>
							Copy
						</button>
					</div>
					<div class="ld-ref-stats">
						<div class="ld-ref-stat">
							<span>Total Referrals</span>
							<strong><?php echo number_format((int)$referral_summary['referral_count']); ?></strong>
						</div>
						<div class="ld-ref-stat">
							<span>Bonus Earned</span>
							<strong>₹<?php echo number_format((float)$referral_summary['bonus_amount'], 2); ?></strong>
						</div>
					</div>
				</div>
			</div>

		</div>

		<!-- RIGHT: NOTIFICATIONS -->
		<div class="ld-col">
			<div class="ld-card">
				<div class="ld-card-hd">
					<div>
						<h2>Notifications</h2>
						<p>Trades, wallet &amp; system alerts</p>
					</div>
					<?php if ((int)$unread_notifications > 0): ?>
						<span class="ld-notif-badge"><?php echo (int)$unread_notifications; ?></span>
					<?php endif; ?>
				</div>
				<div class="ld-notif-toolbar">
					<a class="ld-mark-all" href="<?php echo site_url('dashboard/mark-notifications-read'); ?>">
						<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
							<polyline points="20 6 9 17 4 12" />
						</svg>
						Mark all read
					</a>
				</div>
				<div class="ld-notif-list">
					<?php if (!empty($notifications)): ?>
						<?php foreach ($notifications as $n): ?>
							<div class="ld-notif-item <?php echo empty($n->is_read) ? 'unread' : ''; ?>">
								<div class="ld-notif-title"><?php echo html_escape($n->title); ?></div>
								<div class="ld-notif-msg"><?php echo html_escape($n->message); ?></div>
								<?php if (!empty($n->created_at)): ?>
									<span class="ld-notif-time"><?php echo html_escape($n->created_at); ?></span>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					<?php else: ?>
						<div class="ld-notif-empty">
							<div class="ld-notif-empty-ico">
								<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
									<path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
									<path d="M13.73 21a2 2 0 0 1-3.46 0" />
								</svg>
							</div>
							<p>No notifications yet.<br>Trade &amp; wallet alerts will appear here.</p>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>

	</div>
</div>

<script>
	function ldCopy() {
		var code = document.getElementById('ref-code-v').textContent.trim();
		var btn = document.getElementById('ref-copy-btn');
		navigator.clipboard.writeText(code).then(function() {
			var orig = btn.innerHTML;
			btn.innerHTML = '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg> Copied!';
			btn.style.background = '#d1fae5';
			btn.style.borderColor = '#6ee7b7';
			btn.style.color = '#065f46';
			setTimeout(function() {
				btn.innerHTML = orig;
				btn.style.background = '';
				btn.style.borderColor = '';
				btn.style.color = '';
			}, 2000);
		});
	}
</script>