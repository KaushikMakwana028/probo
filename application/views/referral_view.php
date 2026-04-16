<?php
$profile_image = !empty($user->profile_image) ? $user->profile_image : 'assets/images/default-profile.svg';
$profile_image_src = preg_match('/^https?:\/\//i', $profile_image) ? $profile_image : base_url($profile_image);
$referral_summary = isset($referral_summary) ? $referral_summary : array('referral_count' => 0, 'bonus_amount' => 0);
$register_url = isset($register_url) ? $register_url : site_url('login/register');
$share_message = isset($share_message) ? $share_message : ('Join PROBO and use my referral code ' . $referral_code . ' while registering. Sign up here: ' . $register_url);
$share_message_encoded = rawurlencode($share_message);
$register_url_encoded = rawurlencode($register_url);
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,600;0,9..144,700;1,9..144,400&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
	:root {
		--rf2-sand: #faf7f2;
		--rf2-cream: #f4ede0;
		--rf2-warm: #e8d8c0;
		--rf2-amber: #c97c2a;
		--rf2-amber-light: #f5c571;
		--rf2-amber-soft: #fdf3e3;
		--rf2-ink: #1a130a;
		--rf2-ink2: #3d2e1c;
		--rf2-text: #6b5740;
		--rf2-muted: #9c8470;
		--rf2-line: #e8ddd0;
		--rf2-green: #2d7a4f;
		--rf2-green-soft: #eaf4ee;
		--rf2-blue: #2555a0;
		--rf2-blue-soft: #ebf0fb;
		--rf2-rose: #c84b6e;
		--rf2-rose-soft: #fce8ef;
		--rf2-card: #ffffff;
		--rf2-shadow: 0 20px 50px rgba(60, 35, 10, 0.09);
	}

	.rf2 {
		font-family: 'Outfit', sans-serif;
		color: var(--rf2-ink);
		display: grid;
		gap: 24px;
		background: var(--rf2-sand);
		border-radius: 0;
		min-height: 100%;
	}

	/* ── EDITORIAL HERO ─────────────────────────────────── */
	.rf2-hero {
		position: relative;
		border-radius: 28px;
		overflow: hidden;
		background: var(--rf2-ink);
		padding: 0;
		display: grid;
		grid-template-columns: 1fr 1fr;
		min-height: 320px;
	}

	.rf2-hero-left {
		padding: 44px 44px 44px 44px;
		position: relative;
		z-index: 2;
		display: flex;
		flex-direction: column;
		justify-content: space-between;
	}

	.rf2-hero-right {
		background: var(--rf2-amber);
		position: relative;
		overflow: hidden;
		display: flex;
		align-items: center;
		justify-content: center;
	}

	.rf2-hero-right::before {
		content: '';
		position: absolute;
		width: 320px;
		height: 320px;
		border-radius: 50%;
		background: rgba(255, 255, 255, 0.07);
		top: -60px;
		right: -60px;
	}

	.rf2-hero-right::after {
		content: '';
		position: absolute;
		width: 200px;
		height: 200px;
		border-radius: 50%;
		background: rgba(255, 255, 255, 0.05);
		bottom: -40px;
		left: -40px;
	}

	.rf2-big-code {
		position: relative;
		z-index: 2;
		text-align: center;
	}

	.rf2-big-code-label {
		font-size: 11px;
		font-weight: 700;
		letter-spacing: 0.14em;
		text-transform: uppercase;
		color: rgba(255, 255, 255, 0.65);
		margin-bottom: 10px;
	}

	.rf2-big-code-val {
		font-family: 'Fraunces', serif;
		font-size: clamp(42px, 6vw, 72px);
		font-weight: 700;
		color: #fff;
		letter-spacing: 0.06em;
		line-height: 1;
		display: block;
	}

	.rf2-big-code-sub {
		margin-top: 14px;
		font-size: 12px;
		color: rgba(255, 255, 255, 0.7);
	}

	.rf2-hero-tag {
		display: inline-flex;
		align-items: center;
		gap: 8px;
		font-size: 11px;
		font-weight: 600;
		letter-spacing: 0.1em;
		text-transform: uppercase;
		color: var(--rf2-amber-light);
		margin-bottom: 20px;
	}

	.rf2-hero-tag span {
		width: 22px;
		height: 1px;
		background: var(--rf2-amber-light);
	}

	.rf2-hero-title {
		font-family: 'Fraunces', serif;
		font-size: clamp(26px, 3.5vw, 40px);
		font-weight: 700;
		line-height: 1.1;
		color: #fff;
		margin-bottom: 18px;
	}

	.rf2-hero-title em {
		font-style: italic;
		color: var(--rf2-amber-light);
	}

	.rf2-hero-stats {
		display: flex;
		gap: 28px;
	}

	.rf2-hero-stat-val {
		font-family: 'Fraunces', serif;
		font-size: 30px;
		font-weight: 600;
		color: #fff;
		line-height: 1;
		margin-bottom: 4px;
	}

	.rf2-hero-stat-label {
		font-size: 11px;
		color: rgba(255, 255, 255, 0.5);
		text-transform: uppercase;
		letter-spacing: 0.08em;
	}

	.rf2-hero-stat-div {
		width: 1px;
		background: rgba(255, 255, 255, 0.12);
	}

	/* ── BODY LAYOUT ─────────────────────────────────── */
	.rf2-layout {
		display: grid;
		grid-template-columns: 1.15fr 0.85fr;
		gap: 24px;
		align-items: start;
	}

	.rf2-col {
		display: grid;
		gap: 24px;
	}

	/* ── PANEL ─────────────────────────────────── */
	.rf2-panel {
		background: var(--rf2-card);
		border: 1px solid var(--rf2-line);
		border-radius: 24px;
		box-shadow: var(--rf2-shadow);
		overflow: hidden;
	}

	.rf2-panel-head {
		padding: 24px 26px 18px;
		border-bottom: 1px solid var(--rf2-line);
	}

	.rf2-panel-title {
		font-family: 'Fraunces', serif;
		font-size: 22px;
		font-weight: 600;
		color: var(--rf2-ink);
		margin-bottom: 4px;
	}

	.rf2-panel-sub {
		font-size: 13px;
		color: var(--rf2-muted);
	}

	.rf2-panel-body {
		padding: 22px 26px 26px;
	}

	/* ── CODE BOX ─────────────────────────────────── */
	.rf2-codebox {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 18px;
		padding: 22px 24px;
		border-radius: 18px;
		background: var(--rf2-amber-soft);
		border: 1.5px dashed var(--rf2-amber-light);
	}

	.rf2-codebox-label {
		font-size: 10px;
		font-weight: 700;
		letter-spacing: 0.12em;
		text-transform: uppercase;
		color: var(--rf2-amber);
		margin-bottom: 8px;
	}

	.rf2-codebox-val {
		font-family: 'Fraunces', serif;
		font-size: 38px;
		font-weight: 600;
		letter-spacing: 0.08em;
		color: var(--rf2-ink);
	}

	.rf2-copy-btn {
		display: inline-flex;
		align-items: center;
		gap: 8px;
		padding: 12px 20px;
		border-radius: 12px;
		border: 1.5px solid var(--rf2-warm);
		background: #fff;
		color: var(--rf2-ink2);
		font-size: 13px;
		font-weight: 600;
		cursor: pointer;
		transition: all 0.2s;
		white-space: nowrap;
		font-family: 'Outfit', sans-serif;
	}

	.rf2-copy-btn:hover {
		background: var(--rf2-amber);
		border-color: var(--rf2-amber);
		color: #fff;
		transform: translateY(-1px);
		box-shadow: 0 8px 20px rgba(201, 124, 42, 0.25);
	}

	/* ── SHARE CHANNELS ─────────────────────────────────── */
	.rf2-share-list {
		display: grid;
		gap: 12px;
		margin-top: 18px;
	}

	.rf2-share {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 16px;
		padding: 16px 18px;
		border-radius: 16px;
		border: 1px solid var(--rf2-line);
		background: var(--rf2-card);
		text-decoration: none;
		color: var(--rf2-ink);
		cursor: pointer;
		font-family: 'Outfit', sans-serif;
		transition: all 0.2s;
	}

	.rf2-share:hover {
		border-color: var(--rf2-warm);
		background: var(--rf2-sand);
		transform: translateX(4px);
	}

	.rf2-share-left {
		display: flex;
		align-items: center;
		gap: 14px;
	}

	.rf2-share-icon {
		width: 46px;
		height: 46px;
		border-radius: 14px;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 18px;
		flex-shrink: 0;
	}

	.si-green {
		background: var(--rf2-green-soft);
		color: var(--rf2-green);
	}

	.si-blue {
		background: var(--rf2-blue-soft);
		color: var(--rf2-blue);
	}

	.si-amber {
		background: var(--rf2-amber-soft);
		color: var(--rf2-amber);
	}

	.si-rose {
		background: var(--rf2-rose-soft);
		color: var(--rf2-rose);
	}

	.rf2-share-name {
		font-size: 14px;
		font-weight: 600;
		margin-bottom: 2px;
	}

	.rf2-share-desc {
		font-size: 12px;
		color: var(--rf2-muted);
	}

	.rf2-share-chev {
		font-size: 12px;
		color: var(--rf2-warm);
		transition: transform 0.2s, color 0.2s;
	}

	.rf2-share:hover .rf2-share-chev {
		transform: translateX(3px);
		color: var(--rf2-amber);
	}

	/* ── MESSAGE BOX ─────────────────────────────────── */
	.rf2-msgbox {
		margin-top: 20px;
		padding: 18px 20px;
		border-radius: 16px;
		background: #f9f7f4;
		border: 1px solid var(--rf2-line);
	}

	.rf2-msgbox-label {
		font-size: 10px;
		font-weight: 700;
		letter-spacing: 0.12em;
		text-transform: uppercase;
		color: var(--rf2-muted);
		margin-bottom: 10px;
	}

	.rf2-msgbox-text {
		font-size: 13px;
		line-height: 1.8;
		color: var(--rf2-text);
		white-space: pre-line;
	}

	/* ── PROFILE CARD ─────────────────────────────────── */
	.rf2-profile {
		display: flex;
		align-items: center;
		gap: 16px;
		padding: 22px 24px;
		border-radius: 20px;
		background: var(--rf2-card);
		border: 1px solid var(--rf2-line);
		box-shadow: var(--rf2-shadow);
	}

	.rf2-profile img {
		width: 68px;
		height: 68px;
		border-radius: 50%;
		object-fit: cover;
		border: 3px solid var(--rf2-warm);
	}

	.rf2-profile-role {
		font-size: 10px;
		font-weight: 700;
		letter-spacing: 0.1em;
		text-transform: uppercase;
		color: var(--rf2-muted);
		margin-bottom: 4px;
	}

	.rf2-profile-name {
		font-family: 'Fraunces', serif;
		font-size: 22px;
		font-weight: 600;
		margin-bottom: 3px;
	}

	.rf2-profile-email {
		font-size: 13px;
		color: var(--rf2-muted);
	}

	/* ── REWARDS SUMMARY ─────────────────────────────────── */
	.rf2-rewards {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 14px;
	}

	.rf2-reward-card {
		padding: 20px;
		border-radius: 18px;
		background: var(--rf2-card);
		border: 1px solid var(--rf2-line);
		text-align: center;
		box-shadow: 0 8px 24px rgba(60, 35, 10, 0.06);
	}

	.rf2-reward-icon {
		width: 44px;
		height: 44px;
		border-radius: 50%;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 18px;
		margin: 0 auto 12px;
	}

	.ri-a {
		background: var(--rf2-amber-soft);
		color: var(--rf2-amber);
	}

	.ri-g {
		background: var(--rf2-green-soft);
		color: var(--rf2-green);
	}

	.rf2-reward-val {
		font-family: 'Fraunces', serif;
		font-size: 28px;
		font-weight: 600;
		color: var(--rf2-ink);
		margin-bottom: 4px;
	}

	.rf2-reward-label {
		font-size: 11px;
		color: var(--rf2-muted);
		text-transform: uppercase;
		letter-spacing: 0.08em;
		font-weight: 600;
	}

	/* ── HOW-TO TIPS ─────────────────────────────────── */
	.rf2-howto {
		display: grid;
		gap: 14px;
	}

	.rf2-step {
		display: flex;
		gap: 16px;
		align-items: flex-start;
	}

	.rf2-step-num {
		width: 32px;
		height: 32px;
		border-radius: 50%;
		background: var(--rf2-cream);
		border: 1.5px solid var(--rf2-warm);
		display: flex;
		align-items: center;
		justify-content: center;
		font-family: 'Fraunces', serif;
		font-size: 15px;
		font-weight: 600;
		color: var(--rf2-amber);
		flex-shrink: 0;
	}

	.rf2-step-title {
		font-size: 14px;
		font-weight: 600;
		color: var(--rf2-ink);
		margin-bottom: 5px;
	}

	.rf2-step-body {
		font-size: 13px;
		line-height: 1.7;
		color: var(--rf2-text);
	}

	.rf2-step-line {
		width: 1px;
		background: var(--rf2-line);
		height: 20px;
		margin-left: 15px;
	}

	@media (max-width: 1060px) {
		.rf2-hero {
			grid-template-columns: 1fr;
		}

		.rf2-hero-right {
			display: none;
		}

		.rf2-layout {
			grid-template-columns: 1fr;
		}
	}

	@media (max-width: 680px) {
		.rf2-hero-left {
			padding: 28px 22px;
		}

		.rf2-panel-head,
		.rf2-panel-body {
			padding-left: 18px;
			padding-right: 18px;
		}

		.rf2-rewards {
			grid-template-columns: 1fr;
		}
	}
</style>

<div class="rf2">

	<!-- EDITORIAL HERO -->
	<section class="rf2-hero">
		<div class="rf2-hero-left">
			<div>
				<div class="rf2-hero-tag">
					<span></span>
					Referral Program
				</div>
				<h2 class="rf2-hero-title">
					Invite friends &<br>
					<em>earn ₹<?php echo number_format((float)$referral_settings['referrer_bonus'], 0); ?> per referral.</em>
				</h2>
			</div>
			<div class="rf2-hero-stats">
				<div>
					<div class="rf2-hero-stat-val"><?php echo number_format((int) $referral_summary['referral_count']); ?></div>
					<div class="rf2-hero-stat-label">Referrals</div>
				</div>
				<div class="rf2-hero-stat-div"></div>
				<div>
					<div class="rf2-hero-stat-val">₹<?php echo number_format((float) $referral_summary['bonus_amount'], 2); ?></div>
					<div class="rf2-hero-stat-label">Bonus earned</div>
				</div>
			</div>
		</div>
		<div class="rf2-hero-right">
			<div class="rf2-big-code">
				<div class="rf2-big-code-label">Your code</div>
				<span class="rf2-big-code-val"><?php echo html_escape($referral_code); ?></span>
				<div class="rf2-big-code-sub">Share this with friends to earn</div>
			</div>
		</div>
	</section>

	<!-- LAYOUT -->
	<div class="rf2-layout">
		<div class="rf2-col">

			<!-- Share section -->
			<div class="rf2-panel">
				<div class="rf2-panel-head">
					<div class="rf2-panel-title">Share your code</div>
					<div class="rf2-panel-sub">Pick a channel and send your invite in one tap</div>
				</div>
				<div class="rf2-panel-body">
					<div class="rf2-codebox">
						<div>
							<div class="rf2-codebox-label">Referral code</div>
							<div class="rf2-codebox-val" id="rf2Code"><?php echo html_escape($referral_code); ?></div>
						</div>
						<button class="rf2-copy-btn" id="rf2CopyCode">
							<i class="fa-solid fa-copy"></i>
							Copy code
						</button>
					</div>

					<div class="rf2-share-list">
						<a class="rf2-share" href="https://wa.me/?text=<?php echo $share_message_encoded; ?>" target="_blank" rel="noopener">
							<div class="rf2-share-left">
								<div class="rf2-share-icon si-green"><i class="fa-brands fa-whatsapp"></i></div>
								<div>
									<div class="rf2-share-name">WhatsApp</div>
									<div class="rf2-share-desc">Send invite directly to a chat</div>
								</div>
							</div>
							<i class="fa-solid fa-arrow-right rf2-share-chev"></i>
						</a>

						<a class="rf2-share" href="https://t.me/share/url?url=<?php echo $register_url_encoded; ?>&text=<?php echo $share_message_encoded; ?>" target="_blank" rel="noopener">
							<div class="rf2-share-left">
								<div class="rf2-share-icon si-blue"><i class="fa-brands fa-telegram"></i></div>
								<div>
									<div class="rf2-share-name">Telegram</div>
									<div class="rf2-share-desc">Share message and link together</div>
								</div>
							</div>
							<i class="fa-solid fa-arrow-right rf2-share-chev"></i>
						</a>

						<a class="rf2-share" href="mailto:?subject=<?php echo rawurlencode('Join me on PROBO'); ?>&body=<?php echo $share_message_encoded; ?>">
							<div class="rf2-share-left">
								<div class="rf2-share-icon si-amber"><i class="fa-solid fa-envelope"></i></div>
								<div>
									<div class="rf2-share-name">Email</div>
									<div class="rf2-share-desc">Open mail with message pre-filled</div>
								</div>
							</div>
							<i class="fa-solid fa-arrow-right rf2-share-chev"></i>
						</a>

						<a class="rf2-share" href="https://www.instagram.com/" target="_blank" rel="noopener" id="rf2InstagramShare">
							<div class="rf2-share-left">
								<div class="rf2-share-icon si-rose"><i class="fa-brands fa-instagram"></i></div>
								<div>
									<div class="rf2-share-name">Instagram</div>
									<div class="rf2-share-desc">Copy message, paste in DM or bio</div>
								</div>
							</div>
							<i class="fa-solid fa-arrow-right rf2-share-chev"></i>
						</a>

						<button type="button" class="rf2-share" id="rf2CopyMessage">
							<div class="rf2-share-left">
								<div class="rf2-share-icon si-blue"><i class="fa-solid fa-message"></i></div>
								<div>
									<div class="rf2-share-name">Copy full message</div>
									<div class="rf2-share-desc">Share anywhere you want</div>
								</div>
							</div>
							<i class="fa-solid fa-copy rf2-share-chev"></i>
						</button>

						<button type="button" class="rf2-share" id="rf2NativeShare">
							<div class="rf2-share-left">
								<div class="rf2-share-icon si-green"><i class="fa-solid fa-share-nodes"></i></div>
								<div>
									<div class="rf2-share-name">More apps</div>
									<div class="rf2-share-desc">Use your system share sheet</div>
								</div>
							</div>
							<i class="fa-solid fa-mobile-screen-button rf2-share-chev"></i>
						</button>
					</div>

					<div class="rf2-msgbox">
						<div class="rf2-msgbox-label">Invite message preview</div>
						<div class="rf2-msgbox-text" id="rf2Message"><?php echo html_escape($share_message); ?></div>
					</div>
				</div>
			</div>
		</div>

		<div class="rf2-col">

			<!-- Profile card -->
			<div class="rf2-profile">
				<img src="<?php echo html_escape($profile_image_src); ?>" alt="Profile">
				<div>
					<div class="rf2-profile-role">Referral owner</div>
					<div class="rf2-profile-name"><?php echo html_escape($user->name); ?></div>
					<div class="rf2-profile-email"><?php echo html_escape($user->email); ?></div>
				</div>
			</div>

			<!-- Rewards summary -->
			<div class="rf2-rewards">
				<div class="rf2-reward-card">
					<div class="rf2-reward-icon ri-g"><i class="fa-solid fa-users"></i></div>
					<div class="rf2-reward-val"><?php echo number_format((int) $referral_summary['referral_count']); ?></div>
					<div class="rf2-reward-label">Friends referred</div>
				</div>
				<div class="rf2-reward-card">
					<div class="rf2-reward-icon ri-a"><i class="fa-solid fa-indian-rupee-sign"></i></div>
					<div class="rf2-reward-val">₹<?php echo number_format((float) $referral_summary['bonus_amount'], 0); ?></div>
					<div class="rf2-reward-label">Bonus earned</div>
				</div>
			</div>

			<!-- How to tips -->
			<div class="rf2-panel">
				<div class="rf2-panel-head">
					<div class="rf2-panel-title">How to share better</div>
					<div class="rf2-panel-sub">Simple tips to get more friends to register</div>
				</div>
				<div class="rf2-panel-body">
					<div class="rf2-howto">
						<div class="rf2-step">
							<div class="rf2-step-num">1</div>
							<div>
								<div class="rf2-step-title">Tell them to enter the code during sign-up</div>
								<div class="rf2-step-body">Your code matters most at the registration form — remind your friend to enter it there.</div>
							</div>
						</div>
						<div class="rf2-step-line"></div>
						<div class="rf2-step">
							<div class="rf2-step-num">2</div>
							<div>
								<div class="rf2-step-title">WhatsApp converts best</div>
								<div class="rf2-step-body">Personal chat feels warmer than mass posting — send it directly to people you know.</div>
							</div>
						</div>
						<div class="rf2-step-line"></div>
						<div class="rf2-step">
							<div class="rf2-step-num">3</div>
							<div>
								<div class="rf2-step-title">Instagram works with copied text</div>
								<div class="rf2-step-body">Tap Instagram to copy your message first, then paste it into DMs, bio, or stories.</div>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>

<script>
	(function() {
		'use strict';
		var code = '<?php echo addslashes($referral_code); ?>';
		var message = '<?php echo addslashes($share_message); ?>';

		function toast(text) {
			if (typeof Swal !== 'undefined') {
				Swal.fire({
					icon: 'success',
					text: text,
					confirmButtonColor: '#c97c2a'
				});
			}
		}

		function copyText(txt, msg) {
			navigator.clipboard.writeText(txt).then(function() {
				toast(msg);
			});
		}

		var copyCodeBtn = document.getElementById('rf2CopyCode');
		var copyMsgBtn = document.getElementById('rf2CopyMessage');
		var instaBtn = document.getElementById('rf2InstagramShare');
		var nativeBtn = document.getElementById('rf2NativeShare');

		if (copyCodeBtn) copyCodeBtn.addEventListener('click', function() {
			copyText(code, 'Referral code copied.');
		});
		if (copyMsgBtn) copyMsgBtn.addEventListener('click', function() {
			copyText(message, 'Referral message copied.');
		});
		if (instaBtn) instaBtn.addEventListener('click', function() {
			copyText(message, 'Message copied — paste it in Instagram.');
		});
		if (nativeBtn) nativeBtn.addEventListener('click', function() {
			if (navigator.share) {
				navigator.share({
					title: 'Join me on PROBO',
					text: message,
					url: '<?php echo addslashes($register_url); ?>'
				}).catch(function() {});
			} else {
				copyText(message, 'Share sheet unavailable — message copied instead.');
			}
		});
	}());
</script>