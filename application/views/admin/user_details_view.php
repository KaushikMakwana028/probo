<?php
$profile_image = !empty($user->profile_image) ? $user->profile_image : 'assets/images/default-profile.svg';
$profile_image_src = preg_match('/^https?:\/\//i', $profile_image) ? $profile_image : base_url($profile_image);
$display_name    = trim((string) $user->name);
$display_mobile  = trim((string) $user->mobile);
$display_email   = trim((string) $user->email);
$display_address = trim((string) $user->address);
$initials = '';
$name_parts = array_slice(array_filter(explode(' ', $display_name ?: 'U')), 0, 2);
foreach ($name_parts as $name_part) $initials .= strtoupper(substr($name_part, 0, 1));
if ($initials === '') $initials = 'U';

$avatar_palettes = [
	['#6366f1', '#818cf8'],
	['#0ea5e9', '#38bdf8'],
	['#10b981', '#34d399'],
	['#f59e0b', '#fbbf24'],
	['#ec4899', '#f472b6'],
	['#8b5cf6', '#a78bfa'],
	['#14b8a6', '#2dd4bf'],
	['#f97316', '#fb923c'],
];
$pal = $avatar_palettes[(int)$user->id % count($avatar_palettes)];

$has_email   = $display_email   !== '';
$has_mobile  = $display_mobile  !== '';
$has_address = $display_address !== '';
$has_image   = !empty($user->profile_image);

$profile_score = 0;
if ($display_name !== '')  $profile_score += 25;
if ($has_email)            $profile_score += 25;
if ($has_mobile)           $profile_score += 25;
if ($has_address)          $profile_score += 25;
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500&family=DM+Mono&display=swap" rel="stylesheet">

<?php if ($this->session->flashdata('error')): ?>
	<div class="pv-toast pv-toast--err" role="alert">
		<svg viewBox="0 0 20 20" fill="currentColor">
			<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
		</svg>
		<span><?php echo $this->session->flashdata('error'); ?></span>
		<button class="pv-toast__x" onclick="this.parentElement.remove()">&times;</button>
	</div>
<?php endif; ?>

<?php if ($this->session->flashdata('success')): ?>
	<div class="pv-toast pv-toast--ok" role="alert">
		<svg viewBox="0 0 20 20" fill="currentColor">
			<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
		</svg>
		<span><?php echo $this->session->flashdata('success'); ?></span>
		<button class="pv-toast__x" onclick="this.parentElement.remove()">&times;</button>
	</div>
<?php endif; ?>

<div class="pv-root">

	<!-- ── BACK NAV ── -->
	<nav class="pv-nav">
		<a class="pv-back" href="<?php echo site_url('admin/users'); ?>">
			<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
				<polyline points="15 18 9 12 15 6" />
			</svg>
			All users
		</a>
		<div class="pv-breadcrumb">
			<span>Admin</span>
			<span class="pv-breadcrumb__sep">/</span>
			<span>Users</span>
			<span class="pv-breadcrumb__sep">/</span>
			<span class="pv-breadcrumb__current"><?php echo html_escape($display_name ?: 'Profile'); ?></span>
		</div>
	</nav>

	<!-- ── HERO ── -->
	<header class="pv-hero">
		<div class="pv-hero__photo-col">
			<div class="pv-avatar-wrap">
				<?php if ($has_image): ?>
					<img src="<?php echo html_escape($profile_image_src); ?>"
						alt="<?php echo html_escape($display_name ?: 'User'); ?>"
						class="pv-avatar pv-avatar--img"
						onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
					<div class="pv-avatar pv-avatar--init" style="--av-a:<?php echo $pal[0]; ?>;--av-b:<?php echo $pal[1]; ?>;display:none">
						<?php echo html_escape($initials); ?>
					</div>
				<?php else: ?>
					<div class="pv-avatar pv-avatar--init" style="--av-a:<?php echo $pal[0]; ?>;--av-b:<?php echo $pal[1]; ?>">
						<?php echo html_escape($initials); ?>
					</div>
				<?php endif; ?>
				<span class="pv-status-pip" title="Active"></span>
			</div>

			<div class="pv-completeness">
				<div class="pv-completeness__ring">
					<svg viewBox="0 0 44 44">
						<circle cx="22" cy="22" r="18" fill="none" stroke="#e9e5e0" stroke-width="3.5" />
						<circle cx="22" cy="22" r="18" fill="none" stroke="#1a1a1a" stroke-width="3.5"
							stroke-dasharray="<?php echo round(113 * $profile_score / 100, 1); ?> 113"
							stroke-dashoffset="28.3" stroke-linecap="round" />
					</svg>
					<span class="pv-completeness__pct"><?php echo $profile_score; ?></span>
				</div>
				<span class="pv-completeness__label">Profile<br>complete</span>
			</div>
		</div>

		<div class="pv-hero__copy">
			<div class="pv-hero__top">
				<div>
					<p class="pv-eyebrow">Regular user · ID #<?php echo (int) $user->id; ?></p>
					<h1 class="pv-name"><?php echo html_escape($display_name ?: 'Unknown user'); ?></h1>
					<?php if ($profile_score === 100): ?>
						<span class="pv-badge pv-badge--verified">
							<svg viewBox="0 0 16 16" fill="currentColor">
								<path d="M13.854 3.646a.5.5 0 010 .708l-7 7a.5.5 0 01-.708 0l-3.5-3.5a.5.5 0 11.708-.708L6.5 10.293l6.646-6.647a.5.5 0 01.708 0z" />
							</svg>
							Complete profile
						</span>
					<?php endif; ?>
				</div>
				<div class="pv-hero__actions">
					<a class="pv-btn pv-btn--outline" href="<?php echo site_url('admin/users/edit/' . (int) $user->id); ?>">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
							<path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" />
							<path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
						</svg>
						Edit profile
					</a>
					<a class="pv-btn pv-btn--danger" href="<?php echo site_url('admin/users/delete/' . (int) $user->id); ?>" onclick="return confirm('Permanently delete this user?')">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
							<polyline points="3 6 5 6 21 6" />
							<path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6" />
						</svg>
						Delete
					</a>
				</div>
			</div>

			<!-- Quick contact strip -->
			<div class="pv-contact-strip">
				<?php if ($has_email): ?>
					<a href="mailto:<?php echo html_escape($display_email); ?>" class="pv-contact-pill">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
							<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
							<polyline points="22,6 12,13 2,6" />
						</svg>
						<?php echo html_escape($display_email); ?>
					</a>
				<?php endif; ?>
				<?php if ($has_mobile): ?>
					<a href="tel:<?php echo html_escape($display_mobile); ?>" class="pv-contact-pill">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
							<rect x="5" y="2" width="14" height="20" rx="2" />
							<line x1="12" y1="18" x2="12.01" y2="18" />
						</svg>
						<?php echo html_escape($display_mobile); ?>
					</a>
				<?php endif; ?>
				<?php if ($has_address): ?>
					<span class="pv-contact-pill pv-contact-pill--static">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
							<path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0118 0z" />
							<circle cx="12" cy="10" r="3" />
						</svg>
						<?php echo html_escape($display_address); ?>
					</span>
				<?php endif; ?>
			</div>
		</div>
	</header>

	<!-- ── STAT STRIP ── -->
	<section class="pv-stats" aria-label="Activity statistics">
		<div class="pv-stat">
			<span class="pv-stat__num"><?php echo number_format((int) $attempted_answers); ?></span>
			<span class="pv-stat__label">Attempted answers</span>
		</div>
		<div class="pv-stat-divider"></div>
		<div class="pv-stat">
			<span class="pv-stat__num">₹<?php echo number_format((float) $total_winnings, 2); ?></span>
			<span class="pv-stat__label">Total winnings</span>
		</div>
		<div class="pv-stat-divider"></div>
		<div class="pv-stat">
			<span class="pv-stat__num">₹<?php echo number_format((float) $total_withdraw, 2); ?></span>
			<span class="pv-stat__label">Total withdrawn</span>
		</div>
		<div class="pv-stat-divider"></div>
		<div class="pv-stat">
			<span class="pv-stat__num"><?php echo $profile_score; ?>%</span>
			<span class="pv-stat__label">Profile completeness</span>
		</div>
	</section>

	<!-- ── MAIN CONTENT ── -->
	<div class="pv-body">

		<!-- Left: Details -->
		<main class="pv-main">
			<div class="pv-section-header">
				<h2 class="pv-section-title">Account details</h2>
				<a class="pv-text-link" href="<?php echo site_url('admin/users/edit/' . (int) $user->id); ?>">
					Edit <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<line x1="5" y1="12" x2="19" y2="12" />
						<polyline points="12 5 19 12 12 19" />
					</svg>
				</a>
			</div>

			<div class="pv-fields">
				<div class="pv-field">
					<div class="pv-field__meta">
						<span class="pv-field__label">Full name</span>
					</div>
					<div class="pv-field__content">
						<span class="pv-field__val <?php echo !$display_name ? 'pv-field__val--empty' : ''; ?>">
							<?php echo html_escape($display_name ?: 'Not provided'); ?>
						</span>
					</div>
					<div class="pv-field__status <?php echo $display_name ? 'pv-field__status--ok' : 'pv-field__status--no'; ?>"></div>
				</div>

				<div class="pv-field">
					<div class="pv-field__meta">
						<span class="pv-field__label">Email address</span>
					</div>
					<div class="pv-field__content">
						<span class="pv-field__val <?php echo !$has_email ? 'pv-field__val--empty' : ''; ?>">
							<?php echo html_escape($display_email ?: 'Not provided'); ?>
						</span>
					</div>
					<?php if ($has_email): ?>
						<a href="mailto:<?php echo html_escape($display_email); ?>" class="pv-field__cta">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<line x1="22" y1="2" x2="11" y2="13" />
								<polygon points="22 2 15 22 11 13 2 9 22 2" />
							</svg>
						</a>
					<?php else: ?>
						<div class="pv-field__status pv-field__status--no"></div>
					<?php endif; ?>
				</div>

				<div class="pv-field">
					<div class="pv-field__meta">
						<span class="pv-field__label">Mobile number</span>
					</div>
					<div class="pv-field__content">
						<span class="pv-field__val pv-field__val--mono <?php echo !$has_mobile ? 'pv-field__val--empty' : ''; ?>">
							<?php echo html_escape($display_mobile ?: 'Not provided'); ?>
						</span>
					</div>
					<?php if ($has_mobile): ?>
						<a href="tel:<?php echo html_escape($display_mobile); ?>" class="pv-field__cta">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81 19.79 19.79 0 011 1.22 2 2 0 013 0h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L7.09 7.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 14.92z" />
							</svg>
						</a>
					<?php else: ?>
						<div class="pv-field__status pv-field__status--no"></div>
					<?php endif; ?>
				</div>

				<div class="pv-field">
					<div class="pv-field__meta">
						<span class="pv-field__label">Profile photo</span>
					</div>
					<div class="pv-field__content">
						<span class="pv-field__val"><?php echo $has_image ? 'Custom photo uploaded' : 'Using default avatar'; ?></span>
					</div>
					<div class="pv-field__status <?php echo $has_image ? 'pv-field__status--ok' : 'pv-field__status--no'; ?>"></div>
				</div>

				<div class="pv-field pv-field--full">
					<div class="pv-field__meta">
						<span class="pv-field__label">Address</span>
					</div>
					<div class="pv-field__content">
						<span class="pv-field__val <?php echo !$has_address ? 'pv-field__val--empty' : ''; ?>">
							<?php echo html_escape($display_address ?: 'No address on file'); ?>
						</span>
					</div>
					<div class="pv-field__status <?php echo $has_address ? 'pv-field__status--ok' : 'pv-field__status--no'; ?>"></div>
				</div>
			</div>
		</main>

		<!-- Right: Sidebar -->
		<aside class="pv-aside">

			<!-- Profile health -->
			<div class="pv-card">
				<div class="pv-card__head">
					<h3 class="pv-card__title">Profile health</h3>
				</div>
				<div class="pv-card__body">
					<div class="pv-health-list">
						<div class="pv-health-row">
							<div class="pv-health-dot <?php echo $display_name ? 'pv-health-dot--ok' : 'pv-health-dot--no'; ?>"></div>
							<span class="pv-health-label">Full name</span>
							<span class="pv-health-pill <?php echo $display_name ? 'pv-health-pill--ok' : 'pv-health-pill--no'; ?>">
								<?php echo $display_name ? 'Provided' : 'Missing'; ?>
							</span>
						</div>
						<div class="pv-health-row">
							<div class="pv-health-dot <?php echo $has_email ? 'pv-health-dot--ok' : 'pv-health-dot--no'; ?>"></div>
							<span class="pv-health-label">Email address</span>
							<span class="pv-health-pill <?php echo $has_email ? 'pv-health-pill--ok' : 'pv-health-pill--no'; ?>">
								<?php echo $has_email ? 'Saved' : 'Missing'; ?>
							</span>
						</div>
						<div class="pv-health-row">
							<div class="pv-health-dot <?php echo $has_mobile ? 'pv-health-dot--ok' : 'pv-health-dot--no'; ?>"></div>
							<span class="pv-health-label">Mobile number</span>
							<span class="pv-health-pill <?php echo $has_mobile ? 'pv-health-pill--ok' : 'pv-health-pill--no'; ?>">
								<?php echo $has_mobile ? 'Saved' : 'Missing'; ?>
							</span>
						</div>
						<div class="pv-health-row">
							<div class="pv-health-dot <?php echo $has_address ? 'pv-health-dot--ok' : 'pv-health-dot--no'; ?>"></div>
							<span class="pv-health-label">Address</span>
							<span class="pv-health-pill <?php echo $has_address ? 'pv-health-pill--ok' : 'pv-health-pill--no'; ?>">
								<?php echo $has_address ? 'Saved' : 'Missing'; ?>
							</span>
						</div>
						<div class="pv-health-row">
							<div class="pv-health-dot <?php echo $has_image ? 'pv-health-dot--ok' : 'pv-health-dot--no'; ?>"></div>
							<span class="pv-health-label">Profile photo</span>
							<span class="pv-health-pill <?php echo $has_image ? 'pv-health-pill--ok' : 'pv-health-pill--no'; ?>">
								<?php echo $has_image ? 'Uploaded' : 'Default'; ?>
							</span>
						</div>
					</div>

					<div class="pv-progress-wrap">
						<div class="pv-progress-track">
							<div class="pv-progress-fill" style="width:<?php echo $profile_score; ?>%"></div>
						</div>
						<div class="pv-progress-meta">
							<span><?php echo $profile_score; ?>% complete</span>
							<span><?php echo $profile_score === 100 ? 'All fields filled' : (100 - $profile_score) . '% remaining'; ?></span>
						</div>
					</div>
				</div>
			</div>

			<!-- Quick actions -->
			<div class="pv-card">
				<div class="pv-card__head">
					<h3 class="pv-card__title">Quick actions</h3>
				</div>
				<div class="pv-card__body pv-card__body--actions">
					<a class="pv-qact" href="<?php echo site_url('admin/users/edit/' . (int) $user->id); ?>">
						<div class="pv-qact__icon">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" />
								<path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
							</svg>
						</div>
						<div class="pv-qact__body">
							<span class="pv-qact__title">Edit account</span>
							<span class="pv-qact__desc">Update user information</span>
						</div>
						<svg class="pv-qact__arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
							<polyline points="9 18 15 12 9 6" />
						</svg>
					</a>
					<a class="pv-qact" href="<?php echo site_url('admin/users'); ?>">
						<div class="pv-qact__icon">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
								<circle cx="9" cy="7" r="4" />
								<path d="M23 21v-2a4 4 0 00-3-3.87" />
								<path d="M16 3.13a4 4 0 010 7.75" />
							</svg>
						</div>
						<div class="pv-qact__body">
							<span class="pv-qact__title">All users</span>
							<span class="pv-qact__desc">Back to user directory</span>
						</div>
						<svg class="pv-qact__arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
							<polyline points="9 18 15 12 9 6" />
						</svg>
					</a>
				</div>
			</div>

		</aside>
	</div>

	<!-- ── DANGER ZONE ── -->
	<section class="pv-danger">
		<div class="pv-danger__left">
			<p class="pv-danger__eyebrow">Danger zone</p>
			<h3 class="pv-danger__title">Delete this account</h3>
			<p class="pv-danger__desc">This action is permanent and cannot be undone. All data associated with this user — including their activity history — will be immediately erased.</p>
		</div>
		<a class="pv-danger__btn" href="<?php echo site_url('admin/users/delete/' . (int) $user->id); ?>" onclick="return confirm('Permanently delete this user? This cannot be undone.')">
			<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
				<polyline points="3 6 5 6 21 6" />
				<path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6" />
				<path d="M10 11v6M14 11v6" />
				<path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2" />
			</svg>
			Delete user permanently
		</a>
	</section>

</div><!-- /.pv-root -->

<style>
	/* ─────────────────────────────────────────
   RESET
───────────────────────────────────────── */
	*,
	*::before,
	*::after {
		box-sizing: border-box;
		margin: 0;
		padding: 0;
	}

	/* ─────────────────────────────────────────
   TOKENS
───────────────────────────────────────── */
	:root {
		--f-display: 'Roboto', sans-serif;
		--f-body: 'Roboto', system-ui, sans-serif;
		--f-mono: 'Roboto', sans-serif;

		--c-ink: #1a1a1a;
		--c-ink-2: #3d3d3d;
		--c-ink-3: #7a7a7a;
		--c-ink-4: #ababab;
		--c-line: #e9e5e0;
		--c-line-2: #f2efe9;
		--c-surface: #ffffff;
		--c-base: #faf9f7;
		--c-warm: #f5f2ed;

		--c-ok: #2d7a4f;
		--c-ok-lt: #edf5f0;
		--c-ok-bd: #b3d9c3;
		--c-err: #b83232;
		--c-err-lt: #fdf2f2;
		--c-err-bd: #e8b3b3;

		--sh-xs: 0 1px 3px rgba(26, 16, 8, .05);
		--sh-sm: 0 3px 12px rgba(26, 16, 8, .07);
		--sh-md: 0 8px 28px rgba(26, 16, 8, .09);

		--r-sm: 8px;
		--r-md: 14px;
		--r-lg: 20px;
		--r-xl: 28px;
	}

	body {
		font-family: var(--f-body);
		background: var(--c-base);
		color: var(--c-ink);
		-webkit-font-smoothing: antialiased;
	}

	/* ─────────────────────────────────────────
   ROOT
───────────────────────────────────────── */
	.pv-root {
		padding: 20px 0 56px;
		display: flex;
		flex-direction: column;
		gap: 18px;
	}

	/* ─────────────────────────────────────────
   TOASTS
───────────────────────────────────────── */
	.pv-toast {
		display: flex;
		align-items: center;
		gap: 10px;
		padding: 13px 16px;
		border-radius: var(--r-md);
		font-size: 13.5px;
		font-weight: 400;
		border: 1px solid transparent;
		animation: toastIn .3s ease both;
	}

	.pv-toast svg {
		width: 16px;
		height: 16px;
		flex-shrink: 0;
	}

	.pv-toast--err {
		background: var(--c-err-lt);
		border-color: var(--c-err-bd);
		color: var(--c-err);
	}

	.pv-toast--ok {
		background: var(--c-ok-lt);
		border-color: var(--c-ok-bd);
		color: var(--c-ok);
	}

	.pv-toast__x {
		margin-left: auto;
		border: none;
		background: none;
		cursor: pointer;
		font-size: 18px;
		color: inherit;
		opacity: .5;
	}

	.pv-toast__x:hover {
		opacity: 1;
	}

	@keyframes toastIn {
		from {
			opacity: 0;
			transform: translateY(-8px);
		}

		to {
			opacity: 1;
			transform: none;
		}
	}

	/* ─────────────────────────────────────────
   NAV
───────────────────────────────────────── */
	.pv-nav {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 16px;
		flex-wrap: wrap;
	}

	.pv-back {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		font-size: 13.5px;
		font-weight: 400;
		color: var(--c-ink-3);
		text-decoration: none;
		transition: color .15s;
	}

	.pv-back svg {
		width: 15px;
		height: 15px;
	}

	.pv-back:hover {
		color: var(--c-ink);
	}

	.pv-breadcrumb {
		display: flex;
		align-items: center;
		gap: 6px;
		font-size: 12.5px;
		color: var(--c-ink-4);
	}

	.pv-breadcrumb__sep {
		opacity: .4;
	}

	.pv-breadcrumb__current {
		color: var(--c-ink-3);
	}

	/* ─────────────────────────────────────────
   HERO
───────────────────────────────────────── */
	.pv-hero {
		background: var(--c-surface);
		border: 1px solid var(--c-line);
		border-radius: var(--r-xl);
		padding: 36px 40px;
		display: flex;
		gap: 32px;
		flex-wrap: wrap;
		box-shadow: var(--sh-xs);
	}

	.pv-hero__photo-col {
		display: flex;
		flex-direction: column;
		align-items: center;
		gap: 16px;
		flex-shrink: 0;
	}

	/* Avatar */
	.pv-avatar-wrap {
		position: relative;
	}

	.pv-avatar {
		width: 108px;
		height: 108px;
		border-radius: var(--r-lg);
		border: 1px solid var(--c-line);
		display: block;
		object-fit: cover;
	}

	.pv-avatar--init {
		display: flex;
		align-items: center;
		justify-content: center;
		background: linear-gradient(135deg, var(--av-a), var(--av-b));
		font-family: var(--f-display);
		font-style: italic;
		font-size: 36px;
		color: #fff;
	}

	.pv-status-pip {
		position: absolute;
		bottom: -4px;
		right: -4px;
		width: 14px;
		height: 14px;
		border-radius: 50%;
		background: #22c55e;
		border: 2.5px solid var(--c-surface);
	}

	/* Completeness ring */
	.pv-completeness {
		display: flex;
		align-items: center;
		gap: 10px;
	}

	.pv-completeness__ring {
		position: relative;
		width: 44px;
		height: 44px;
		flex-shrink: 0;
	}

	.pv-completeness__ring svg {
		width: 44px;
		height: 44px;
		transform: rotate(-90deg);
	}

	.pv-completeness__pct {
		position: absolute;
		inset: 0;
		display: flex;
		align-items: center;
		justify-content: center;
		font-family: var(--f-mono);
		font-size: 10px;
		color: var(--c-ink);
	}

	.pv-completeness__label {
		font-size: 11px;
		font-weight: 400;
		color: var(--c-ink-3);
		line-height: 1.4;
	}

	/* Copy */
	.pv-hero__copy {
		flex: 1;
		min-width: 0;
	}

	.pv-hero__top {
		display: flex;
		justify-content: space-between;
		align-items: flex-start;
		gap: 20px;
		flex-wrap: wrap;
		margin-bottom: 20px;
	}

	.pv-eyebrow {
		font-size: 11.5px;
		font-weight: 400;
		color: var(--c-ink-4);
		letter-spacing: .04em;
		text-transform: uppercase;
		margin-bottom: 8px;
	}

	.pv-name {
		font-family: var(--f-display);
		font-style: italic;
		font-size: clamp(28px, 4vw, 46px);
		font-weight: 400;
		color: var(--c-ink);
		line-height: 1.05;
		margin-bottom: 10px;
	}

	.pv-badge {
		display: inline-flex;
		align-items: center;
		gap: 5px;
		padding: 5px 12px;
		border-radius: 999px;
		font-size: 11.5px;
		font-weight: 400;
	}

	.pv-badge--verified {
		background: var(--c-ok-lt);
		border: 1px solid var(--c-ok-bd);
		color: var(--c-ok);
	}

	.pv-badge svg {
		width: 11px;
		height: 11px;
	}

	.pv-hero__actions {
		display: flex;
		gap: 8px;
		flex-shrink: 0;
		flex-wrap: wrap;
	}

	.pv-btn {
		display: inline-flex;
		align-items: center;
		gap: 7px;
		padding: 10px 20px;
		border-radius: var(--r-md);
		font-family: var(--f-body);
		font-size: 13.5px;
		font-weight: 400;
		text-decoration: none;
		border: 1px solid transparent;
		transition: background .14s, transform .14s, box-shadow .14s;
		white-space: nowrap;
	}

	.pv-btn svg {
		width: 14px;
		height: 14px;
	}

	.pv-btn:hover {
		transform: translateY(-1px);
		box-shadow: var(--sh-sm);
	}

	.pv-btn--outline {
		background: var(--c-surface);
		border-color: var(--c-line);
		color: var(--c-ink-2);
	}

	.pv-btn--outline:hover {
		background: var(--c-warm);
	}

	.pv-btn--danger {
		background: var(--c-err-lt);
		border-color: var(--c-err-bd);
		color: var(--c-err);
	}

	.pv-btn--danger:hover {
		background: #fae8e8;
	}

	/* Contact strip */
	.pv-contact-strip {
		display: flex;
		flex-wrap: wrap;
		gap: 8px;
	}

	.pv-contact-pill {
		display: inline-flex;
		align-items: center;
		gap: 7px;
		padding: 8px 14px;
		border-radius: var(--r-md);
		background: var(--c-warm);
		border: 1px solid var(--c-line);
		color: var(--c-ink-2);
		font-size: 13px;
		font-weight: 400;
		text-decoration: none;
		transition: background .12s, border-color .12s;
		max-width: 320px;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
	}

	.pv-contact-pill svg {
		width: 13px;
		height: 13px;
		flex-shrink: 0;
		color: var(--c-ink-4);
	}

	.pv-contact-pill:not(.pv-contact-pill--static):hover {
		background: var(--c-line-2);
		border-color: var(--c-ink-4);
	}

	.pv-contact-pill--static {
		cursor: default;
	}

	/* ─────────────────────────────────────────
   STAT STRIP
───────────────────────────────────────── */
	.pv-stats {
		display: flex;
		align-items: center;
		background: var(--c-surface);
		border: 1px solid var(--c-line);
		border-radius: var(--r-lg);
		box-shadow: var(--sh-xs);
		overflow: hidden;
	}

	.pv-stat {
		flex: 1;
		padding: 22px 28px;
		display: flex;
		flex-direction: column;
		gap: 5px;
	}

	.pv-stat__num {
		font-family: var(--f-display);
		font-style: italic;
		font-size: 26px;
		color: var(--c-ink);
		line-height: 1;
	}

	.pv-stat__label {
		font-size: 11.5px;
		font-weight: 400;
		color: var(--c-ink-4);
		text-transform: uppercase;
		letter-spacing: .06em;
	}

	.pv-stat-divider {
		width: 1px;
		height: 48px;
		background: var(--c-line);
		flex-shrink: 0;
	}

	/* ─────────────────────────────────────────
   BODY GRID
───────────────────────────────────────── */
	.pv-body {
		display: grid;
		grid-template-columns: minmax(0, 1.4fr) minmax(280px, .6fr);
		gap: 16px;
		align-items: start;
	}

	/* ─────────────────────────────────────────
   MAIN / FIELDS
───────────────────────────────────────── */
	.pv-main {
		background: var(--c-surface);
		border: 1px solid var(--c-line);
		border-radius: var(--r-xl);
		overflow: hidden;
		box-shadow: var(--sh-xs);
	}

	.pv-section-header {
		display: flex;
		justify-content: space-between;
		align-items: center;
		padding: 26px 30px 20px;
		border-bottom: 1px solid var(--c-line);
	}

	.pv-section-title {
		font-family: var(--f-display);
		font-style: italic;
		font-size: 22px;
		font-weight: 400;
		color: var(--c-ink);
	}

	.pv-text-link {
		display: inline-flex;
		align-items: center;
		gap: 5px;
		font-size: 13px;
		font-weight: 400;
		color: var(--c-ink-3);
		text-decoration: none;
		transition: color .12s;
	}

	.pv-text-link svg {
		width: 13px;
		height: 13px;
	}

	.pv-text-link:hover {
		color: var(--c-ink);
	}

	.pv-fields {
		padding: 20px 24px 24px;
		display: flex;
		flex-direction: column;
		gap: 10px;
	}

	.pv-field {
		display: flex;
		align-items: center;
		gap: 16px;
		padding: 16px 20px;
		background: var(--c-base);
		border: 1px solid var(--c-line);
		border-radius: var(--r-md);
		transition: border-color .15s, background .15s;
	}

	.pv-field:hover {
		background: var(--c-warm);
		border-color: var(--c-line);
	}

	.pv-field__meta {
		width: 130px;
		flex-shrink: 0;
	}

	.pv-field__label {
		font-size: 11px;
		font-weight: 400;
		text-transform: uppercase;
		letter-spacing: .1em;
		color: var(--c-ink-4);
	}

	.pv-field__content {
		flex: 1;
		min-width: 0;
	}

	.pv-field__val {
		font-size: 14.5px;
		font-weight: 400;
		color: var(--c-ink);
		word-break: break-word;
	}

	.pv-field__val--empty {
		color: var(--c-ink-4);
		font-style: italic;
	}

	.pv-field__val--mono {
		font-family: var(--f-mono);
		font-size: 13.5px;
	}

	.pv-field__status {
		width: 8px;
		height: 8px;
		border-radius: 50%;
		flex-shrink: 0;
	}

	.pv-field__status--ok {
		background: #22c55e;
	}

	.pv-field__status--no {
		background: var(--c-line);
	}

	.pv-field__cta {
		display: flex;
		align-items: center;
		justify-content: center;
		width: 32px;
		height: 32px;
		border-radius: var(--r-sm);
		flex-shrink: 0;
		background: var(--c-surface);
		border: 1px solid var(--c-line);
		color: var(--c-ink-3);
		text-decoration: none;
		transition: background .12s, color .12s, transform .12s;
	}

	.pv-field__cta svg {
		width: 13px;
		height: 13px;
	}

	.pv-field__cta:hover {
		background: var(--c-ink);
		color: #fff;
		transform: scale(1.06);
		border-color: var(--c-ink);
	}

	/* ─────────────────────────────────────────
   SIDEBAR CARDS
───────────────────────────────────────── */
	.pv-aside {
		display: flex;
		flex-direction: column;
		gap: 14px;
	}

	.pv-card {
		background: var(--c-surface);
		border: 1px solid var(--c-line);
		border-radius: var(--r-lg);
		overflow: hidden;
		box-shadow: var(--sh-xs);
	}

	.pv-card__head {
		padding: 20px 22px 14px;
		border-bottom: 1px solid var(--c-line);
	}

	.pv-card__title {
		font-family: var(--f-display);
		font-style: italic;
		font-size: 18px;
		font-weight: 400;
		color: var(--c-ink);
	}

	.pv-card__body {
		padding: 16px 20px 20px;
	}

	.pv-card__body--actions {
		padding: 10px 14px 14px;
	}

	/* Health list */
	.pv-health-list {
		display: flex;
		flex-direction: column;
		gap: 8px;
		margin-bottom: 16px;
	}

	.pv-health-row {
		display: flex;
		align-items: center;
		gap: 10px;
		padding: 10px 12px;
		border-radius: var(--r-sm);
		background: var(--c-base);
		border: 1px solid var(--c-line-2);
	}

	.pv-health-dot {
		width: 7px;
		height: 7px;
		border-radius: 50%;
		flex-shrink: 0;
	}

	.pv-health-dot--ok {
		background: #22c55e;
	}

	.pv-health-dot--no {
		background: var(--c-line);
	}

	.pv-health-label {
		flex: 1;
		font-size: 13px;
		font-weight: 400;
		color: var(--c-ink-2);
	}

	.pv-health-pill {
		font-size: 11.5px;
		font-weight: 400;
		padding: 3px 10px;
		border-radius: 999px;
	}

	.pv-health-pill--ok {
		background: var(--c-ok-lt);
		color: var(--c-ok);
	}

	.pv-health-pill--no {
		background: var(--c-warm);
		color: var(--c-ink-4);
	}

	/* Progress */
	.pv-progress-track {
		height: 3px;
		background: var(--c-line-2);
		border-radius: 999px;
		overflow: hidden;
		margin-bottom: 8px;
	}

	.pv-progress-fill {
		height: 100%;
		border-radius: 999px;
		background: var(--c-ink);
		transition: width .6s ease;
	}

	.pv-progress-meta {
		display: flex;
		justify-content: space-between;
		font-size: 11.5px;
		color: var(--c-ink-4);
	}

	/* Quick actions */
	.pv-qact {
		display: flex;
		align-items: center;
		gap: 12px;
		padding: 13px 12px;
		border-radius: var(--r-md);
		text-decoration: none;
		transition: background .12s;
	}

	.pv-qact:hover {
		background: var(--c-warm);
	}

	.pv-qact__icon {
		width: 36px;
		height: 36px;
		border-radius: var(--r-sm);
		background: var(--c-base);
		border: 1px solid var(--c-line);
		display: flex;
		align-items: center;
		justify-content: center;
		flex-shrink: 0;
		color: var(--c-ink-3);
	}

	.pv-qact__icon svg {
		width: 15px;
		height: 15px;
	}

	.pv-qact__body {
		flex: 1;
	}

	.pv-qact__title {
		display: block;
		font-size: 13.5px;
		font-weight: 400;
		color: var(--c-ink);
	}

	.pv-qact__desc {
		display: block;
		font-size: 11.5px;
		color: var(--c-ink-4);
		margin-top: 2px;
	}

	.pv-qact__arrow {
		width: 14px;
		height: 14px;
		color: var(--c-ink-4);
		flex-shrink: 0;
	}

	/* ─────────────────────────────────────────
   DANGER ZONE
───────────────────────────────────────── */
	.pv-danger {
		display: flex;
		align-items: center;
		gap: 28px;
		flex-wrap: wrap;
		padding: 28px 32px;
		background: var(--c-surface);
		border: 1px solid var(--c-err-bd);
		border-radius: var(--r-xl);
		box-shadow: var(--sh-xs);
	}

	.pv-danger__left {
		flex: 1;
		min-width: 0;
	}

	.pv-danger__eyebrow {
		font-size: 11px;
		font-weight: 400;
		text-transform: uppercase;
		letter-spacing: .1em;
		color: var(--c-err);
		margin-bottom: 6px;
	}

	.pv-danger__title {
		font-family: var(--f-display);
		font-style: italic;
		font-size: 22px;
		font-weight: 400;
		color: var(--c-ink);
		margin-bottom: 8px;
	}

	.pv-danger__desc {
		font-size: 13.5px;
		line-height: 1.65;
		color: var(--c-ink-3);
		max-width: 520px;
	}

	.pv-danger__btn {
		display: inline-flex;
		align-items: center;
		gap: 8px;
		padding: 12px 22px;
		border-radius: var(--r-md);
		background: var(--c-err-lt);
		border: 1px solid var(--c-err-bd);
		color: var(--c-err);
		font-family: var(--f-body);
		font-size: 13.5px;
		font-weight: 400;
		text-decoration: none;
		white-space: nowrap;
		flex-shrink: 0;
		transition: background .14s, transform .14s, box-shadow .14s;
	}

	.pv-danger__btn svg {
		width: 14px;
		height: 14px;
	}

	.pv-danger__btn:hover {
		background: #fae8e8;
		transform: translateY(-1px);
		box-shadow: 0 4px 16px rgba(184, 50, 50, .12);
	}

	/* ─────────────────────────────────────────
   RESPONSIVE
───────────────────────────────────────── */
	@media (max-width: 1100px) {
		.pv-body {
			grid-template-columns: 1fr;
		}
	}

	@media (max-width: 860px) {
		.pv-hero {
			padding: 26px 24px;
			flex-direction: column;
		}

		.pv-hero__photo-col {
			flex-direction: row;
			align-items: center;
		}

		.pv-hero__top {
			flex-direction: column;
		}

		.pv-hero__actions {
			width: 100%;
		}

		.pv-btn {
			flex: 1;
			justify-content: center;
		}

		.pv-stats {
			flex-direction: column;
		}

		.pv-stat-divider {
			width: 100%;
			height: 1px;
		}

		.pv-danger {
			flex-direction: column;
		}

		.pv-danger__btn {
			width: 100%;
			justify-content: center;
		}

		.pv-section-header {
			padding: 20px;
		}

		.pv-fields {
			padding: 14px;
		}

		.pv-field {
			flex-wrap: wrap;
		}

		.pv-field__meta {
			width: 100%;
		}
	}

	@media (max-width: 560px) {
		.pv-nav {
			flex-direction: column;
			align-items: flex-start;
		}

		.pv-avatar {
			width: 84px;
			height: 84px;
		}

		.pv-avatar--init {
			font-size: 28px;
		}

		.pv-name {
			font-size: 28px;
		}
	}
</style>

<script>
	(function() {
		/* Auto-dismiss toasts */
		Array.prototype.slice.call(document.querySelectorAll('.pv-toast')).forEach(function(el) {
			setTimeout(function() {
				el.style.transition = 'opacity .4s ease, transform .4s ease';
				el.style.opacity = '0';
				el.style.transform = 'translateY(-6px)';
				setTimeout(function() {
					el.remove();
				}, 430);
			}, 4500);
		});

		/* Entrance animations */
		var items = document.querySelectorAll('.pv-hero, .pv-stats, .pv-main, .pv-card, .pv-danger');
		Array.prototype.slice.call(items).forEach(function(el, i) {
			el.style.opacity = '0';
			el.style.transform = 'translateY(12px)';
			el.style.transition = 'opacity .4s ease ' + (i * 55) + 'ms, transform .4s ease ' + (i * 55) + 'ms';
			requestAnimationFrame(function() {
				requestAnimationFrame(function() {
					el.style.opacity = '1';
					el.style.transform = 'none';
				});
			});
		});
	}());
</script>