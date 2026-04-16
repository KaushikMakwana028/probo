<?php
$profile_image = !empty($user->profile_image) ? $user->profile_image : 'assets/images/default-profile.svg';
$profile_image_src = preg_match('/^https?:\/\//i', $profile_image) ? $profile_image : base_url($profile_image);
$display_name    = trim((string) $user->name);
$display_mobile  = trim((string) $user->mobile);
$display_email   = trim((string) $user->email);
$display_address = trim((string) $user->address);
$initials = '';
$name_parts = array_slice(array_filter(explode(' ', $display_name ?: 'U')), 0, 2);
foreach ($name_parts as $name_part) {
	$initials .= strtoupper(substr($name_part, 0, 1));
}
if ($initials === '') $initials = 'U';

$avatar_palettes = [
	['#1d4ed8', '#3b82f6'],
	['#0369a1', '#0ea5e9'],
	['#047857', '#10b981'],
	['#b45309', '#f59e0b'],
	['#9d174d', '#ec4899'],
	['#6d28d9', '#8b5cf6'],
	['#0f766e', '#14b8a6'],
	['#c2410c', '#f97316'],
];
$pal = $avatar_palettes[(int)$user->id % count($avatar_palettes)];

$has_email   = $display_email   !== '';
$has_mobile  = $display_mobile  !== '';
$has_address = $display_address !== '';
$has_image   = !empty($user->profile_image);
?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">

<?php if ($this->session->flashdata('error')): ?>
	<div class="ue-toast ue-toast--err" role="alert">
		<div class="ue-toast__icon">
			<svg viewBox="0 0 20 20" fill="currentColor">
				<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
			</svg>
		</div>
		<span><?php echo $this->session->flashdata('error'); ?></span>
		<button class="ue-toast__close" onclick="this.parentElement.remove()" aria-label="Dismiss">&times;</button>
	</div>
<?php endif; ?>

<?php if ($this->session->flashdata('success')): ?>
	<div class="ue-toast ue-toast--ok" role="alert">
		<div class="ue-toast__icon">
			<svg viewBox="0 0 20 20" fill="currentColor">
				<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
			</svg>
		</div>
		<span><?php echo $this->session->flashdata('success'); ?></span>
		<button class="ue-toast__close" onclick="this.parentElement.remove()" aria-label="Dismiss">&times;</button>
	</div>
<?php endif; ?>

<div class="ue-root">

	<!-- ── BREADCRUMB ── -->
	<nav class="ue-breadcrumb" aria-label="Breadcrumb">
		<a class="ue-breadcrumb__link" href="<?php echo site_url('admin/users'); ?>">
			<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
				<path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
				<polyline points="9 22 9 12 15 12 15 22" />
			</svg>
			Users
		</a>
		<svg class="ue-breadcrumb__sep" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
			<polyline points="9 18 15 12 9 6" />
		</svg>
		<a class="ue-breadcrumb__link" href="<?php echo site_url('admin/users/view/' . (int) $user->id); ?>">
			<?php echo html_escape($display_name ?: 'User'); ?>
		</a>
		<svg class="ue-breadcrumb__sep" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
			<polyline points="9 18 15 12 9 6" />
		</svg>
		<span class="ue-breadcrumb__curr">Edit profile</span>
	</nav>

	<!-- ── HEADER CARD ── -->
	<header class="ue-header">
		<div class="ue-header__stripe"></div>
		<div class="ue-header__inner">
			<div class="ue-header__left">
				<div class="ue-avatar-wrap">
					<?php if ($has_image): ?>
						<img src="<?php echo html_escape($profile_image_src); ?>"
							alt="<?php echo html_escape($display_name ?: 'User'); ?>"
							class="ue-avatar ue-avatar--photo"
							onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
						<div class="ue-avatar ue-avatar--init" style="--a:<?php echo $pal[0]; ?>;--b:<?php echo $pal[1]; ?>;display:none">
							<?php echo html_escape($initials); ?>
						</div>
					<?php else: ?>
						<div class="ue-avatar ue-avatar--init" style="--a:<?php echo $pal[0]; ?>;--b:<?php echo $pal[1]; ?>">
							<?php echo html_escape($initials); ?>
						</div>
					<?php endif; ?>
					<div class="ue-avatar__badge" title="Editing">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
							<path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" />
							<path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
						</svg>
					</div>
				</div>
				<div class="ue-header__meta">
					<div class="ue-badge ue-badge--edit">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
							<path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" />
							<path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
						</svg>
						Editing profile
					</div>
					<h1 class="ue-header__name"><?php echo html_escape($display_name ?: 'Unknown user'); ?></h1>
					<p class="ue-header__sub">
						Update this account's contact details, password, and location. Changes apply immediately.
					</p>
					<div class="ue-header__tags">
						<span class="ue-tag">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" />
								<circle cx="12" cy="7" r="4" />
							</svg>
							Regular user
						</span>
						<span class="ue-tag">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<circle cx="12" cy="12" r="10" />
								<polyline points="12 6 12 12 16 14" />
							</svg>
							ID #<?php echo (int) $user->id; ?>
						</span>
					</div>
				</div>
			</div>
			<div class="ue-header__actions">
				<a class="ue-hbtn ue-hbtn--ghost" href="<?php echo site_url('admin/users'); ?>">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<polyline points="15 18 9 12 15 6" />
					</svg>
					All users
				</a>
				<a class="ue-hbtn ue-hbtn--outline" href="<?php echo site_url('admin/users/view/' . (int) $user->id); ?>">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
						<circle cx="12" cy="12" r="3" />
					</svg>
					View profile
				</a>
				<a class="ue-hbtn ue-hbtn--danger" href="<?php echo site_url('admin/users/delete/' . (int) $user->id); ?>" onclick="return confirm('Permanently delete this user?')">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<polyline points="3 6 5 6 21 6" />
						<path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6" />
					</svg>
					Delete
				</a>
			</div>
		</div>
	</header>

	<!-- ── MAIN LAYOUT ── -->
	<div class="ue-layout">

		<!-- ── SIDEBAR ── -->
		<aside class="ue-sidebar">

			<!-- Snapshot -->
			<div class="ue-card" data-reveal>
				<div class="ue-card__head">
					<div class="ue-card__head-icon ue-card__head-icon--slate">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
							<circle cx="12" cy="12" r="10" />
							<line x1="12" y1="8" x2="12" y2="12" />
							<line x1="12" y1="16" x2="12.01" y2="16" />
						</svg>
					</div>
					<div>
						<p class="ue-eyebrow">Snapshot</p>
						<h2 class="ue-card__title">Current data</h2>
					</div>
				</div>
				<div class="ue-card__body">
					<div class="ue-snap-list">
						<div class="ue-snap-item">
							<div class="ue-snap-dot ue-snap-dot--blue"></div>
							<div class="ue-snap-content">
								<span class="ue-snap-label">Email</span>
								<span class="ue-snap-value <?php echo !$has_email ? 'ue-muted' : ''; ?>">
									<?php echo html_escape($display_email ?: 'Not set'); ?>
								</span>
							</div>
						</div>
						<div class="ue-snap-item">
							<div class="ue-snap-dot ue-snap-dot--green"></div>
							<div class="ue-snap-content">
								<span class="ue-snap-label">Mobile</span>
								<span class="ue-snap-value <?php echo !$has_mobile ? 'ue-muted' : ''; ?>">
									<?php echo html_escape($display_mobile ?: 'Not set'); ?>
								</span>
							</div>
						</div>
						<div class="ue-snap-item">
							<div class="ue-snap-dot ue-snap-dot--amber"></div>
							<div class="ue-snap-content">
								<span class="ue-snap-label">Address</span>
								<span class="ue-snap-value <?php echo !$has_address ? 'ue-muted' : ''; ?>">
									<?php echo html_escape($display_address ?: 'Not set'); ?>
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Tips -->
			<div class="ue-card" data-reveal>
				<div class="ue-card__head">
					<div class="ue-card__head-icon ue-card__head-icon--amber">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
							<polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2" />
						</svg>
					</div>
					<div>
						<p class="ue-eyebrow">Guidance</p>
						<h2 class="ue-card__title">Editor tips</h2>
					</div>
				</div>
				<div class="ue-card__body">
					<div class="ue-tips">
						<div class="ue-tip">
							<div class="ue-tip__icon ue-tip__icon--blue">
								<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
									<path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
								</svg>
							</div>
							<p>Fields marked <span class="ue-req-inline">*</span> are required and cannot be left blank.</p>
						</div>
						<div class="ue-tip">
							<div class="ue-tip__icon ue-tip__icon--amber">
								<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
									<rect x="3" y="11" width="18" height="11" rx="2" />
									<path d="M7 11V7a5 5 0 0110 0v4" />
								</svg>
							</div>
							<p>Leave the <strong>password</strong> field empty to keep the existing password.</p>
						</div>
						<div class="ue-tip">
							<div class="ue-tip__icon ue-tip__icon--green">
								<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
									<polyline points="13 2 3 14 12 14 11 22 21 10 12 10 13 2" />
								</svg>
							</div>
							<p>Changes take effect <strong>immediately</strong> after saving.</p>
						</div>
						<div class="ue-tip">
							<div class="ue-tip__icon ue-tip__icon--purple">
								<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
									<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
								</svg>
							</div>
							<p>New passwords must be <strong>at least 6 characters</strong> long.</p>
						</div>
					</div>
				</div>
			</div>

			<!-- Quick links -->
			<div class="ue-card" data-reveal>
				<div class="ue-card__head">
					<div class="ue-card__head-icon ue-card__head-icon--green">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
							<path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71" />
							<path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71" />
						</svg>
					</div>
					<div>
						<p class="ue-eyebrow">Navigation</p>
						<h2 class="ue-card__title">Quick links</h2>
					</div>
				</div>
				<div class="ue-card__body" style="padding-top:8px">
					<div class="ue-links">
						<a class="ue-qlink" href="<?php echo site_url('admin/users'); ?>">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
								<circle cx="9" cy="7" r="4" />
								<path d="M23 21v-2a4 4 0 00-3-3.87" />
								<path d="M16 3.13a4 4 0 010 7.75" />
							</svg>
							All users
						</a>
						<a class="ue-qlink" href="<?php echo site_url('admin/users/view/' . (int) $user->id); ?>">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
								<circle cx="12" cy="12" r="3" />
							</svg>
							View this profile
						</a>
						<a class="ue-qlink" href="<?php echo site_url('admin/users/add'); ?>">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<path d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2" />
								<circle cx="9" cy="7" r="4" />
								<line x1="19" y1="8" x2="19" y2="14" />
								<line x1="22" y1="11" x2="16" y2="11" />
							</svg>
							Add new user
						</a>
					</div>
				</div>
			</div>

		</aside>

		<!-- ── FORM PANEL ── -->
		<section class="ue-panel" data-reveal>

			<div class="ue-panel__head">
				<div class="ue-panel__head-left">
					<p class="ue-eyebrow">Form</p>
					<h2 class="ue-panel__title">Update details</h2>
					<p class="ue-panel__desc">Fill in the fields below and click <strong>Save changes</strong> when done.</p>
				</div>
				<div class="ue-panel__progress" id="formProgress" title="Form completion">
					<svg class="ue-progress-ring" viewBox="0 0 48 48">
						<circle class="ue-progress-ring__bg" cx="24" cy="24" r="20" />
						<circle class="ue-progress-ring__fill" id="progressFill" cx="24" cy="24" r="20" />
					</svg>
					<span class="ue-progress-pct" id="progressPct">0%</span>
				</div>
			</div>

			<form method="post" action="<?php echo site_url('admin/users/update/' . (int) $user->id); ?>" class="ue-form" id="editUserForm" novalidate>

				<!-- ── SECTION: Basic ── -->
				<div class="ue-section">
					<div class="ue-section__header">
						<div class="ue-section__icon">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" />
								<circle cx="12" cy="7" r="4" />
							</svg>
						</div>
						<div>
							<h3 class="ue-section__title">Basic information</h3>
							<p class="ue-section__desc">Name, mobile, and email address.</p>
						</div>
					</div>
					<div class="ue-grid">
						<div class="ue-field">
							<label class="ue-label" for="name">
								Full name
								<span class="ue-req" aria-label="required">*</span>
							</label>
							<div class="ue-input-wrap">
								<div class="ue-input-ico">
									<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
										<path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" />
										<circle cx="12" cy="7" r="4" />
									</svg>
								</div>
								<input type="text" id="name" name="name"
									value="<?php echo set_value('name', $user->name); ?>"
									placeholder="Enter full name"
									required autocomplete="name"
									class="ue-input" data-track>
								<div class="ue-input-valid">
									<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
										<polyline points="20 6 9 17 4 12" />
									</svg>
								</div>
							</div>
							<span class="ue-field-err" id="err-name"></span>
						</div>

						<div class="ue-field">
							<label class="ue-label" for="mobile">
								Mobile number
								<span class="ue-req" aria-label="required">*</span>
							</label>
							<div class="ue-input-wrap">
								<div class="ue-input-ico">
									<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
										<rect x="5" y="2" width="14" height="20" rx="2" />
										<line x1="12" y1="18" x2="12.01" y2="18" />
									</svg>
								</div>
								<input type="tel" id="mobile" name="mobile"
									value="<?php echo set_value('mobile', $user->mobile); ?>"
									placeholder="Enter mobile number"
									required autocomplete="tel"
									class="ue-input" data-track>
								<div class="ue-input-valid">
									<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
										<polyline points="20 6 9 17 4 12" />
									</svg>
								</div>
							</div>
							<span class="ue-field-err" id="err-mobile"></span>
						</div>

						<div class="ue-field ue-field--full">
							<label class="ue-label" for="email">
								Email address
								<span class="ue-req" aria-label="required">*</span>
							</label>
							<div class="ue-input-wrap">
								<div class="ue-input-ico">
									<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
										<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
										<polyline points="22,6 12,13 2,6" />
									</svg>
								</div>
								<input type="email" id="email" name="email"
									value="<?php echo set_value('email', $user->email); ?>"
									placeholder="Enter email address"
									required autocomplete="email"
									class="ue-input" data-track>
								<div class="ue-input-valid">
									<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
										<polyline points="20 6 9 17 4 12" />
									</svg>
								</div>
							</div>
							<span class="ue-field-err" id="err-email"></span>
						</div>
					</div>
				</div>

				<div class="ue-divider"></div>

				<!-- ── SECTION: Security ── -->
				<div class="ue-section">
					<div class="ue-section__header">
						<div class="ue-section__icon ue-section__icon--amber">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
								<path d="M7 11V7a5 5 0 0110 0v4" />
							</svg>
						</div>
						<div>
							<h3 class="ue-section__title">Security</h3>
							<p class="ue-section__desc">Leave blank to keep the current password unchanged.</p>
						</div>
					</div>
					<div class="ue-grid">
						<div class="ue-field ue-field--full">
							<label class="ue-label" for="password">New password</label>
							<div class="ue-input-wrap">
								<div class="ue-input-ico">
									<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
										<rect x="3" y="11" width="18" height="11" rx="2" />
										<path d="M7 11V7a5 5 0 0110 0v4" />
									</svg>
								</div>
								<input type="password" id="password" name="password"
									placeholder="Leave blank to keep current password"
									class="ue-input ue-input--pw"
									autocomplete="new-password">
								<button type="button" class="ue-pw-eye" id="pwToggle" aria-label="Toggle password visibility">
									<svg class="ue-eye--show" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
										<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
										<circle cx="12" cy="12" r="3" />
									</svg>
									<svg class="ue-eye--hide" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none">
										<path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94" />
										<path d="M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19" />
										<line x1="1" y1="1" x2="23" y2="23" />
									</svg>
								</button>
							</div>

							<div class="ue-pw-strength" id="pwStrength" hidden>
								<div class="ue-pw-bars">
									<div class="ue-pw-bar" id="pwBar1"></div>
									<div class="ue-pw-bar" id="pwBar2"></div>
									<div class="ue-pw-bar" id="pwBar3"></div>
									<div class="ue-pw-bar" id="pwBar4"></div>
									<div class="ue-pw-bar" id="pwBar5"></div>
								</div>
								<span class="ue-pw-label" id="pwLabel"></span>
							</div>

							<p class="ue-hint">
								<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
									<circle cx="12" cy="12" r="10" />
									<line x1="12" y1="8" x2="12" y2="12" />
									<line x1="12" y1="16" x2="12.01" y2="16" />
								</svg>
								Minimum 6 characters if changing. Use a mix of letters, numbers, and symbols for a stronger password.
							</p>
						</div>
					</div>
				</div>

				<div class="ue-divider"></div>

				<!-- ── SECTION: Location ── -->
				<div class="ue-section">
					<div class="ue-section__header">
						<div class="ue-section__icon ue-section__icon--green">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0118 0z" />
								<circle cx="12" cy="10" r="3" />
							</svg>
						</div>
						<div>
							<h3 class="ue-section__title">Location</h3>
							<p class="ue-section__desc">Delivery or contact address for this user.</p>
						</div>
					</div>
					<div class="ue-grid">
						<div class="ue-field ue-field--full">
							<label class="ue-label" for="address">Address</label>
							<div class="ue-input-wrap ue-input-wrap--ta">
								<div class="ue-input-ico ue-input-ico--ta">
									<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
										<path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0118 0z" />
										<circle cx="12" cy="10" r="3" />
									</svg>
								</div>
								<textarea id="address" name="address" rows="4"
									placeholder="Enter full delivery address"
									class="ue-input ue-textarea"
									data-track><?php echo set_value('address', $user->address); ?></textarea>
							</div>
						</div>
					</div>
				</div>

				<!-- ── FORM FOOTER ── -->
				<div class="ue-form-foot">
					<div class="ue-form-foot__left">
						<div class="ue-autosave-dot" id="autoSaveDot"></div>
						<span class="ue-form-foot__note" id="formNote">Ready to save</span>
					</div>
					<div class="ue-form-foot__btns">
						<a class="ue-btn ue-btn--cancel" href="<?php echo site_url('admin/users'); ?>">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<line x1="18" y1="6" x2="6" y2="18" />
								<line x1="6" y1="6" x2="18" y2="18" />
							</svg>
							Cancel
						</a>
						<button type="submit" class="ue-btn ue-btn--save" id="saveBtn">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="ue-btn__icon--check">
								<polyline points="20 6 9 17 4 12" />
							</svg>
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="ue-btn__icon--spin" style="display:none">
								<polyline points="23 4 23 10 17 10" />
								<path d="M20.49 15a9 9 0 11-2.12-9.36L23 10" />
							</svg>
							<span id="saveBtnText">Save changes</span>
						</button>
					</div>
				</div>

			</form>
		</section>
	</div>

	<!-- ── DANGER ZONE ── -->
	<section class="ue-danger" role="region" aria-label="Danger zone">
		<div class="ue-danger__left">
			<div class="ue-danger__icon">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
					<line x1="12" y1="9" x2="12" y2="13" />
					<line x1="12" y1="17" x2="12.01" y2="17" />
				</svg>
			</div>
			<div class="ue-danger__text">
				<p class="ue-danger__eyebrow">Danger zone</p>
				<h3 class="ue-danger__title">Delete this account</h3>
				<p class="ue-danger__desc">This permanently removes the user record and all associated data. This action cannot be undone.</p>
			</div>
		</div>
		<a class="ue-danger__btn" href="<?php echo site_url('admin/users/delete/' . (int) $user->id); ?>" onclick="return confirm('Permanently delete this user? This cannot be undone.')">
			<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
				<polyline points="3 6 5 6 21 6" />
				<path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6" />
				<path d="M10 11v6M14 11v6" />
				<path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2" />
			</svg>
			Delete permanently
		</a>
	</section>

</div><!-- /.ue-root -->

<style>
	/* ─────────────────────────────
   RESET & TOKENS
───────────────────────────────── */
	*,
	*::before,
	*::after {
		box-sizing: border-box;
		margin: 0;
		padding: 0
	}

	:root {
		/* Neutrals */
		--ink: #0e1117;
		--ink-2: #2d3748;
		--ink-3: #4a5568;
		--ink-4: #718096;
		--ink-5: #a0aec0;
		--line: #e8ecf1;
		--line-2: #d2d9e3;
		--surface: #ffffff;
		--base: #f7f8fa;
		--base-2: #f0f2f5;

		/* Greens (primary accent) */
		--g-50: #f0fdf4;
		--g-100: #dcfce7;
		--g-200: #bbf7d0;
		--g-400: #4ade80;
		--g-500: #22c55e;
		--g-600: #16a34a;
		--g-700: #15803d;
		--g-800: #166534;
		--g-900: #14532d;

		/* Blues */
		--b-50: #eff6ff;
		--b-100: #dbeafe;
		--b-200: #bfdbfe;
		--b-500: #3b82f6;
		--b-600: #2563eb;
		--b-700: #1d4ed8;
		--b-800: #1e40af;

		/* Ambers */
		--am-50: #fffbeb;
		--am-100: #fef3c7;
		--am-200: #fde68a;
		--am-400: #fbbf24;
		--am-500: #f59e0b;
		--am-600: #d97706;
		--am-800: #92400e;

		/* Purples */
		--pu-50: #faf5ff;
		--pu-100: #f3e8ff;
		--pu-200: #e9d5ff;
		--pu-500: #a855f7;
		--pu-600: #9333ea;
		--pu-800: #6b21a8;

		/* Reds */
		--r-50: #fff1f2;
		--r-100: #ffe4e6;
		--r-200: #fecdd3;
		--r-400: #fb7185;
		--r-500: #f43f5e;
		--r-600: #e11d48;
		--r-700: #be123c;
		--r-800: #9f1239;

		/* Typography */
		--f-body: 'Roboto', system-ui, -apple-system, sans-serif;
		--f-display: 'DM Serif Display', Georgia, serif;

		/* Radii */
		--rad-sm: 6px;
		--rad-md: 10px;
		--rad-lg: 14px;
		--rad-xl: 18px;
		--rad-2xl: 24px;

		/* Shadows */
		--sh-xs: 0 1px 2px rgba(14, 17, 23, .04);
		--sh-sm: 0 2px 8px rgba(14, 17, 23, .06);
		--sh-md: 0 4px 20px rgba(14, 17, 23, .08);
		--sh-lg: 0 12px 40px rgba(14, 17, 23, .10);
	}

	body {
		font-family: var(--f-body);
		background: var(--base);
		color: var(--ink);
		font-size: 14px;
		line-height: 1.6;
		-webkit-font-smoothing: antialiased;
	}

	/* ─────────────────────────────
   ROOT WRAPPER
───────────────────────────────── */
	.ue-root {
		padding: 20px 0 60px;
		display: flex;
		flex-direction: column;
		gap: 16px;
	}

	/* ─────────────────────────────
   TOAST
───────────────────────────────── */
	.ue-toast {
		display: flex;
		align-items: center;
		gap: 12px;
		padding: 13px 16px;
		border-radius: var(--rad-lg);
		font-size: 13.5px;
		font-weight: 500;
		border: 1px solid transparent;
		animation: ue-slideDown .35s cubic-bezier(.34, 1.56, .64, 1) both;
		box-shadow: var(--sh-sm);
	}

	.ue-toast__icon {
		width: 30px;
		height: 30px;
		border-radius: var(--rad-md);
		display: flex;
		align-items: center;
		justify-content: center;
		flex-shrink: 0;
	}

	.ue-toast__icon svg {
		width: 16px;
		height: 16px;
	}

	.ue-toast--err {
		background: var(--r-50);
		border-color: var(--r-200);
		color: var(--r-800);
	}

	.ue-toast--err .ue-toast__icon {
		background: var(--r-100);
		color: var(--r-600);
	}

	.ue-toast--ok {
		background: var(--g-50);
		border-color: var(--g-200);
		color: var(--g-800);
	}

	.ue-toast--ok .ue-toast__icon {
		background: var(--g-100);
		color: var(--g-600);
	}

	.ue-toast__close {
		margin-left: auto;
		border: none;
		background: none;
		cursor: pointer;
		font-size: 18px;
		color: inherit;
		opacity: .45;
		line-height: 1;
		padding: 2px 5px;
		border-radius: 4px;
	}

	.ue-toast__close:hover {
		opacity: 1;
		background: rgba(0, 0, 0, .05);
	}

	@keyframes ue-slideDown {
		from {
			opacity: 0;
			transform: translateY(-10px)
		}

		to {
			opacity: 1;
			transform: none
		}
	}

	/* ─────────────────────────────
   BREADCRUMB
───────────────────────────────── */
	.ue-breadcrumb {
		display: flex;
		align-items: center;
		gap: 4px;
		flex-wrap: wrap;
	}

	.ue-breadcrumb__link {
		display: inline-flex;
		align-items: center;
		gap: 5px;
		font-size: 12.5px;
		font-weight: 500;
		color: var(--ink-4);
		text-decoration: none;
		padding: 4px 8px;
		border-radius: var(--rad-sm);
		transition: color .15s, background .15s;
	}

	.ue-breadcrumb__link svg {
		width: 13px;
		height: 13px;
	}

	.ue-breadcrumb__link:hover {
		color: var(--ink);
		background: var(--base-2);
	}

	.ue-breadcrumb__sep {
		width: 13px;
		height: 13px;
		color: var(--ink-5);
		flex-shrink: 0;
	}

	.ue-breadcrumb__curr {
		font-size: 12.5px;
		font-weight: 600;
		color: var(--g-700);
		padding: 4px 10px;
		border-radius: var(--rad-sm);
		background: var(--g-50);
		border: 1px solid var(--g-100);
	}

	/* ─────────────────────────────
   HEADER
───────────────────────────────── */
	.ue-header {
		background: var(--surface);
		border: 1px solid var(--line);
		border-radius: var(--rad-2xl);
		overflow: hidden;
		box-shadow: var(--sh-sm);
		position: relative;
	}

	.ue-header__stripe {
		height: 4px;
		background: linear-gradient(90deg, var(--g-500), var(--b-500), var(--pu-500));
	}

	.ue-header__inner {
		padding: 24px 28px 26px;
		display: flex;
		justify-content: space-between;
		align-items: center;
		gap: 20px;
		flex-wrap: wrap;
	}

	.ue-header__left {
		display: flex;
		align-items: center;
		gap: 20px;
		flex: 1;
		min-width: 0;
	}

	/* Avatar */
	.ue-avatar-wrap {
		position: relative;
		flex-shrink: 0;
	}

	.ue-avatar {
		width: 80px;
		height: 80px;
		border-radius: 20px;
		border: 2px solid var(--line);
		object-fit: cover;
		display: block;
	}

	.ue-avatar--init {
		display: flex;
		align-items: center;
		justify-content: center;
		background: linear-gradient(135deg, var(--a), var(--b));
		font-family: var(--f-display);
		font-size: 26px;
		color: #fff;
		letter-spacing: -.02em;
	}

	.ue-avatar__badge {
		position: absolute;
		bottom: -5px;
		right: -5px;
		width: 24px;
		height: 24px;
		border-radius: 7px;
		background: var(--g-600);
		border: 2px solid var(--surface);
		display: flex;
		align-items: center;
		justify-content: center;
		color: #fff;
	}

	.ue-avatar__badge svg {
		width: 11px;
		height: 11px;
	}

	/* Header meta */
	.ue-header__meta {
		flex: 1;
		min-width: 0;
	}

	.ue-badge {
		display: inline-flex;
		align-items: center;
		gap: 5px;
		padding: 4px 10px;
		border-radius: 999px;
		font-size: 11px;
		font-weight: 600;
		letter-spacing: .08em;
		text-transform: uppercase;
		margin-bottom: 8px;
	}

	.ue-badge svg {
		width: 11px;
		height: 11px;
	}

	.ue-badge--edit {
		background: var(--g-50);
		border: 1px solid var(--g-200);
		color: var(--g-700);
	}

	.ue-header__name {
		font-family: var(--f-display);
		font-size: clamp(20px, 3vw, 32px);
		color: var(--ink);
		line-height: 1.15;
		letter-spacing: -.02em;
		margin-bottom: 6px;
	}

	.ue-header__sub {
		font-size: 13.5px;
		color: var(--ink-4);
		line-height: 1.55;
		margin-bottom: 12px;
		max-width: 500px;
	}

	.ue-header__tags {
		display: flex;
		flex-wrap: wrap;
		gap: 6px;
	}

	.ue-tag {
		display: inline-flex;
		align-items: center;
		gap: 5px;
		padding: 5px 11px;
		border-radius: 999px;
		background: var(--base-2);
		border: 1px solid var(--line);
		font-size: 12px;
		font-weight: 500;
		color: var(--ink-3);
	}

	.ue-tag svg {
		width: 12px;
		height: 12px;
	}

	/* Header buttons */
	.ue-header__actions {
		display: flex;
		gap: 8px;
		flex-wrap: wrap;
		flex-shrink: 0;
	}

	.ue-hbtn {
		display: inline-flex;
		align-items: center;
		gap: 7px;
		padding: 9px 16px;
		border-radius: var(--rad-md);
		font-family: var(--f-body);
		font-size: 13px;
		font-weight: 500;
		text-decoration: none;
		border: 1px solid var(--line-2);
		cursor: pointer;
		transition: background .15s, border-color .15s, transform .12s, box-shadow .15s;
		white-space: nowrap;
	}

	.ue-hbtn svg {
		width: 13px;
		height: 13px;
	}

	.ue-hbtn:hover {
		transform: translateY(-1px);
		box-shadow: var(--sh-sm);
	}

	.ue-hbtn--ghost {
		background: var(--base);
		color: var(--ink-3);
	}

	.ue-hbtn--ghost:hover {
		background: var(--base-2);
		color: var(--ink-2);
	}

	.ue-hbtn--outline {
		background: var(--surface);
		color: var(--ink-2);
	}

	.ue-hbtn--outline:hover {
		background: var(--base);
	}

	.ue-hbtn--danger {
		background: var(--r-50);
		border-color: var(--r-200);
		color: var(--r-700);
	}

	.ue-hbtn--danger:hover {
		background: var(--r-100);
		border-color: var(--r-400);
	}

	/* ─────────────────────────────
   LAYOUT
───────────────────────────────── */
	.ue-layout {
		display: grid;
		grid-template-columns: 272px minmax(0, 1fr);
		gap: 16px;
		align-items: start;
	}

	/* ─────────────────────────────
   SIDEBAR
───────────────────────────────── */
	.ue-sidebar {
		display: flex;
		flex-direction: column;
		gap: 12px;
	}

	.ue-card {
		background: var(--surface);
		border: 1px solid var(--line);
		border-radius: var(--rad-xl);
		overflow: hidden;
		box-shadow: var(--sh-xs);
	}

	.ue-card__head {
		padding: 16px 18px;
		border-bottom: 1px solid var(--line);
		display: flex;
		align-items: center;
		gap: 12px;
		background: var(--base);
	}

	.ue-card__head-icon {
		width: 34px;
		height: 34px;
		border-radius: var(--rad-md);
		display: flex;
		align-items: center;
		justify-content: center;
		flex-shrink: 0;
	}

	.ue-card__head-icon svg {
		width: 15px;
		height: 15px;
	}

	.ue-card__head-icon--slate {
		background: var(--base-2);
		color: var(--ink-3);
		border: 1px solid var(--line-2);
	}

	.ue-card__head-icon--amber {
		background: var(--am-50);
		color: var(--am-600);
		border: 1px solid var(--am-200);
	}

	.ue-card__head-icon--green {
		background: var(--g-50);
		color: var(--g-600);
		border: 1px solid var(--g-200);
	}

	.ue-eyebrow {
		font-size: 10.5px;
		font-weight: 600;
		letter-spacing: .12em;
		text-transform: uppercase;
		color: var(--ink-5);
		margin-bottom: 2px;
	}

	.ue-card__title {
		font-size: 14px;
		font-weight: 700;
		color: var(--ink-2);
		letter-spacing: -.01em;
	}

	.ue-card__body {
		padding: 14px 18px;
	}

	/* Snap list */
	.ue-snap-list {
		display: flex;
		flex-direction: column;
		gap: 8px;
	}

	.ue-snap-item {
		display: flex;
		align-items: flex-start;
		gap: 10px;
		padding: 10px 12px;
		border-radius: var(--rad-md);
		border: 1px solid var(--line);
		background: var(--base);
		transition: border-color .2s;
	}

	.ue-snap-item:hover {
		border-color: var(--g-200);
	}

	.ue-snap-dot {
		width: 8px;
		height: 8px;
		border-radius: 50%;
		flex-shrink: 0;
		margin-top: 5px;
	}

	.ue-snap-dot--blue {
		background: var(--b-500);
		box-shadow: 0 0 0 3px var(--b-100);
	}

	.ue-snap-dot--green {
		background: var(--g-500);
		box-shadow: 0 0 0 3px var(--g-100);
	}

	.ue-snap-dot--amber {
		background: var(--am-500);
		box-shadow: 0 0 0 3px var(--am-100);
	}

	.ue-snap-content {}

	.ue-snap-label {
		display: block;
		font-size: 10px;
		font-weight: 600;
		letter-spacing: .1em;
		text-transform: uppercase;
		color: var(--ink-5);
		margin-bottom: 3px;
	}

	.ue-snap-value {
		display: block;
		font-size: 12.5px;
		font-weight: 500;
		color: var(--ink-2);
		word-break: break-all;
		line-height: 1.4;
	}

	.ue-muted {
		color: var(--ink-5) !important;
		font-style: italic;
		font-weight: 400 !important;
	}

	/* Tips */
	.ue-tips {
		display: flex;
		flex-direction: column;
		gap: 8px;
	}

	.ue-tip {
		display: flex;
		gap: 10px;
		align-items: flex-start;
		padding: 10px 12px;
		border-radius: var(--rad-md);
		background: var(--base);
		border: 1px solid var(--line);
	}

	.ue-tip__icon {
		width: 26px;
		height: 26px;
		border-radius: var(--rad-sm);
		flex-shrink: 0;
		display: flex;
		align-items: center;
		justify-content: center;
	}

	.ue-tip__icon svg {
		width: 13px;
		height: 13px;
	}

	.ue-tip__icon--blue {
		background: var(--b-50);
		color: var(--b-600);
	}

	.ue-tip__icon--amber {
		background: var(--am-50);
		color: var(--am-600);
	}

	.ue-tip__icon--green {
		background: var(--g-50);
		color: var(--g-600);
	}

	.ue-tip__icon--purple {
		background: var(--pu-50);
		color: var(--pu-600);
	}

	.ue-tip p {
		font-size: 12px;
		line-height: 1.55;
		color: var(--ink-4);
	}

	.ue-tip p strong {
		color: var(--ink-2);
	}

	.ue-req-inline {
		color: var(--r-600);
		font-weight: 700;
	}

	/* Quick links */
	.ue-links {
		display: flex;
		flex-direction: column;
		gap: 2px;
	}

	.ue-qlink {
		display: flex;
		align-items: center;
		gap: 9px;
		padding: 10px 12px;
		border-radius: var(--rad-md);
		font-size: 13px;
		font-weight: 500;
		color: var(--ink-3);
		text-decoration: none;
		transition: background .15s, color .15s;
	}

	.ue-qlink svg {
		width: 14px;
		height: 14px;
		flex-shrink: 0;
	}

	.ue-qlink:hover {
		background: var(--g-50);
		color: var(--g-700);
	}

	/* ─────────────────────────────
   FORM PANEL
───────────────────────────────── */
	.ue-panel {
		background: var(--surface);
		border: 1px solid var(--line);
		border-radius: var(--rad-xl);
		overflow: hidden;
		box-shadow: var(--sh-sm);
	}

	.ue-panel__head {
		padding: 20px 26px 18px;
		border-bottom: 1px solid var(--line);
		background: var(--base);
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 16px;
	}

	.ue-panel__title {
		font-family: var(--f-display);
		font-size: 22px;
		color: var(--ink);
		letter-spacing: -.02em;
		margin-bottom: 4px;
	}

	.ue-panel__desc {
		font-size: 13px;
		color: var(--ink-4);
	}

	.ue-panel__desc strong {
		color: var(--g-600);
	}

	/* Progress ring */
	.ue-panel__progress {
		position: relative;
		width: 52px;
		height: 52px;
		flex-shrink: 0;
	}

	.ue-progress-ring {
		width: 52px;
		height: 52px;
		transform: rotate(-90deg);
	}

	.ue-progress-ring__bg {
		fill: none;
		stroke: var(--line);
		stroke-width: 3.5;
	}

	.ue-progress-ring__fill {
		fill: none;
		stroke: var(--g-500);
		stroke-width: 3.5;
		stroke-linecap: round;
		stroke-dasharray: 125.6;
		stroke-dashoffset: 125.6;
		transition: stroke-dashoffset .5s ease;
	}

	.ue-progress-pct {
		position: absolute;
		inset: 0;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 10.5px;
		font-weight: 700;
		color: var(--g-700);
	}

	/* Form sections */
	.ue-form {}

	.ue-section {
		padding: 22px 26px;
	}

	.ue-section__header {
		display: flex;
		align-items: flex-start;
		gap: 12px;
		margin-bottom: 18px;
	}

	.ue-section__icon {
		width: 36px;
		height: 36px;
		border-radius: var(--rad-md);
		background: var(--base-2);
		border: 1px solid var(--line-2);
		color: var(--ink-3);
		display: flex;
		align-items: center;
		justify-content: center;
		flex-shrink: 0;
		margin-top: 2px;
	}

	.ue-section__icon svg {
		width: 16px;
		height: 16px;
	}

	.ue-section__icon--amber {
		background: var(--am-50);
		border-color: var(--am-200);
		color: var(--am-600);
	}

	.ue-section__icon--green {
		background: var(--g-50);
		border-color: var(--g-200);
		color: var(--g-600);
	}

	.ue-section__title {
		font-size: 15px;
		font-weight: 700;
		color: var(--ink-2);
		margin-bottom: 3px;
	}

	.ue-section__desc {
		font-size: 12.5px;
		color: var(--ink-5);
	}

	.ue-divider {
		height: 1px;
		background: var(--line);
	}

	/* Grid */
	.ue-grid {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 14px;
	}

	.ue-field {
		display: flex;
		flex-direction: column;
		gap: 7px;
	}

	.ue-field--full {
		grid-column: 1 / -1;
	}

	/* Label */
	.ue-label {
		font-size: 11.5px;
		font-weight: 600;
		letter-spacing: .07em;
		text-transform: uppercase;
		color: var(--ink-2);
		display: flex;
		align-items: center;
		gap: 4px;
	}

	.ue-req {
		color: var(--r-500);
		font-size: 14px;
		line-height: 1;
	}

	/* Input wrap */
	.ue-input-wrap {
		position: relative;
		display: flex;
		align-items: center;
	}

	.ue-input-wrap--ta {
		align-items: flex-start;
	}

	.ue-input-ico {
		position: absolute;
		left: 13px;
		top: 50%;
		transform: translateY(-50%);
		color: var(--ink-5);
		pointer-events: none;
		display: flex;
		align-items: center;
	}

	.ue-input-ico svg {
		width: 15px;
		height: 15px;
	}

	.ue-input-ico--ta {
		top: 14px;
		transform: none;
	}

	/* Valid checkmark */
	.ue-input-valid {
		position: absolute;
		right: 13px;
		top: 50%;
		transform: translateY(-50%);
		color: var(--g-500);
		opacity: 0;
		pointer-events: none;
		transition: opacity .25s;
	}

	.ue-input-valid svg {
		width: 15px;
		height: 15px;
	}

	/* Input */
	.ue-input {
		width: 100%;
		height: 46px;
		padding: 0 42px 0 42px;
		border: 1.5px solid var(--line-2);
		border-radius: var(--rad-md);
		background: var(--base);
		color: var(--ink);
		font-family: var(--f-body);
		font-size: 14px;
		font-weight: 400;
		transition: border-color .18s, box-shadow .18s, background .18s;
		-webkit-appearance: none;
	}

	.ue-input:hover {
		border-color: var(--ink-5);
	}

	.ue-input:focus {
		outline: none;
		border-color: var(--g-500);
		box-shadow: 0 0 0 3.5px rgba(34, 197, 94, .13);
		background: var(--surface);
	}

	.ue-input.is-valid {
		border-color: var(--g-400);
	}

	.ue-input.is-valid~.ue-input-valid {
		opacity: 1;
	}

	.ue-input.is-error {
		border-color: var(--r-400);
	}

	.ue-input.is-error:focus {
		box-shadow: 0 0 0 3.5px rgba(244, 63, 94, .13);
	}

	.ue-input--pw {
		padding-right: 42px;
	}

	.ue-textarea {
		height: auto;
		padding-top: 13px;
		padding-bottom: 13px;
		resize: vertical;
		min-height: 106px;
		line-height: 1.6;
	}

	/* Field error */
	.ue-field-err {
		font-size: 12px;
		color: var(--r-600);
		font-weight: 500;
		display: none;
	}

	.ue-field-err.show {
		display: block;
	}

	/* Password eye */
	.ue-pw-eye {
		position: absolute;
		right: 13px;
		top: 50%;
		transform: translateY(-50%);
		border: none;
		background: none;
		cursor: pointer;
		color: var(--ink-5);
		display: flex;
		align-items: center;
		padding: 3px;
		border-radius: 4px;
		transition: color .15s;
	}

	.ue-pw-eye svg {
		width: 15px;
		height: 15px;
	}

	.ue-pw-eye:hover {
		color: var(--ink-2);
	}

	/* Password strength */
	.ue-pw-strength {
		margin-top: 8px;
		display: flex;
		align-items: center;
		gap: 10px;
	}

	.ue-pw-bars {
		display: flex;
		gap: 4px;
		flex: 1;
	}

	.ue-pw-bar {
		flex: 1;
		height: 4px;
		border-radius: 999px;
		background: var(--line);
		transition: background .3s;
	}

	.ue-pw-label {
		font-size: 11.5px;
		font-weight: 600;
		white-space: nowrap;
		min-width: 70px;
	}

	/* Hint */
	.ue-hint {
		display: flex;
		align-items: flex-start;
		gap: 5px;
		font-size: 12px;
		color: var(--ink-5);
		line-height: 1.5;
	}

	.ue-hint svg {
		width: 12px;
		height: 12px;
		flex-shrink: 0;
		margin-top: 2px;
	}

	/* ─────────────────────────────
   FORM FOOTER
───────────────────────────────── */
	.ue-form-foot {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 16px;
		padding: 16px 26px 22px;
		border-top: 1px solid var(--line);
		background: var(--base);
		flex-wrap: wrap;
	}

	.ue-form-foot__left {
		display: flex;
		align-items: center;
		gap: 8px;
	}

	.ue-autosave-dot {
		width: 8px;
		height: 8px;
		border-radius: 50%;
		background: var(--g-400);
		flex-shrink: 0;
	}

	.ue-autosave-dot.dirty {
		background: var(--am-400);
	}

	.ue-form-foot__note {
		font-size: 12.5px;
		color: var(--ink-5);
	}

	.ue-form-foot__btns {
		display: flex;
		gap: 10px;
	}

	.ue-btn {
		display: inline-flex;
		align-items: center;
		gap: 8px;
		padding: 11px 22px;
		border-radius: var(--rad-md);
		font-family: var(--f-body);
		font-size: 14px;
		font-weight: 600;
		border: 1.5px solid transparent;
		cursor: pointer;
		transition: all .15s;
		text-decoration: none;
		white-space: nowrap;
	}

	.ue-btn svg {
		width: 14px;
		height: 14px;
	}

	.ue-btn:hover {
		transform: translateY(-1px);
		box-shadow: var(--sh-sm);
	}

	.ue-btn--cancel {
		background: var(--surface);
		border-color: var(--line-2);
		color: var(--ink-3);
	}

	.ue-btn--cancel:hover {
		background: var(--base-2);
		color: var(--ink-2);
	}

	.ue-btn--save {
		background: var(--g-600);
		border-color: var(--g-700);
		color: #fff;
		box-shadow: 0 2px 12px rgba(22, 163, 74, .25);
	}

	.ue-btn--save:hover {
		background: var(--g-700);
		box-shadow: 0 6px 20px rgba(22, 163, 74, .35);
	}

	.ue-btn--save:disabled {
		opacity: .65;
		pointer-events: none;
	}

	.ue-btn__icon--spin {
		animation: ue-spin .7s linear infinite;
	}

	@keyframes ue-spin {
		to {
			transform: rotate(360deg)
		}
	}

	/* ─────────────────────────────
   DANGER ZONE
───────────────────────────────── */
	.ue-danger {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 20px;
		flex-wrap: wrap;
		padding: 22px 26px;
		background: var(--surface);
		border: 1px solid var(--r-200);
		border-left: 4px solid var(--r-500);
		border-radius: var(--rad-xl);
		box-shadow: var(--sh-xs);
	}

	.ue-danger__left {
		display: flex;
		align-items: center;
		gap: 16px;
		flex: 1;
		min-width: 0;
	}

	.ue-danger__icon {
		width: 44px;
		height: 44px;
		border-radius: var(--rad-md);
		background: var(--r-50);
		border: 1px solid var(--r-200);
		color: var(--r-600);
		display: flex;
		align-items: center;
		justify-content: center;
		flex-shrink: 0;
	}

	.ue-danger__icon svg {
		width: 20px;
		height: 20px;
	}

	.ue-danger__text {
		flex: 1;
		min-width: 0;
	}

	.ue-danger__eyebrow {
		font-size: 10.5px;
		font-weight: 600;
		letter-spacing: .12em;
		text-transform: uppercase;
		color: var(--r-400);
		margin-bottom: 3px;
	}

	.ue-danger__title {
		font-size: 16px;
		font-weight: 700;
		color: var(--r-800);
		margin-bottom: 4px;
	}

	.ue-danger__desc {
		font-size: 13px;
		line-height: 1.55;
		color: var(--ink-4);
		max-width: 520px;
	}

	.ue-danger__btn {
		display: inline-flex;
		align-items: center;
		gap: 8px;
		padding: 11px 20px;
		border-radius: var(--rad-md);
		background: var(--r-600);
		border: 1px solid var(--r-700);
		color: #fff;
		font-family: var(--f-body);
		font-size: 13.5px;
		font-weight: 600;
		text-decoration: none;
		flex-shrink: 0;
		transition: all .15s;
		white-space: nowrap;
		cursor: pointer;
	}

	.ue-danger__btn svg {
		width: 14px;
		height: 14px;
	}

	.ue-danger__btn:hover {
		background: var(--r-700);
		transform: translateY(-1px);
		box-shadow: 0 6px 20px rgba(225, 29, 72, .3);
	}

	/* ─────────────────────────────
   REVEAL ANIMATION
───────────────────────────────── */
	[data-reveal] {
		opacity: 0;
		transform: translateY(10px);
		transition: opacity .4s ease, transform .4s ease;
	}

	[data-reveal].revealed {
		opacity: 1;
		transform: none;
	}

	/* ─────────────────────────────
   RESPONSIVE
───────────────────────────────── */
	@media (max-width: 1024px) {
		.ue-layout {
			grid-template-columns: 1fr;
		}

		.ue-sidebar {
			display: grid;
			grid-template-columns: repeat(2, 1fr);
		}
	}

	@media (max-width: 820px) {
		.ue-header__inner {
			flex-direction: column;
			align-items: flex-start;
		}

		.ue-header__actions {
			width: 100%;
		}

		.ue-section {
			padding: 18px 18px;
		}

		.ue-panel__head {
			padding: 18px 18px 16px;
		}

		.ue-form-foot {
			padding: 14px 18px 18px;
		}

		.ue-danger {
			flex-direction: column;
			align-items: flex-start;
		}

		.ue-danger__btn {
			width: 100%;
			justify-content: center;
		}
	}

	@media (max-width: 680px) {
		.ue-sidebar {
			grid-template-columns: 1fr;
		}

		.ue-grid {
			grid-template-columns: 1fr;
		}

		.ue-field--full {
			grid-column: auto;
		}

		.ue-avatar {
			width: 68px;
			height: 68px;
			border-radius: 16px;
		}

		.ue-header__name {
			font-size: 22px;
		}

		.ue-hbtn {
			flex: 1;
			justify-content: center;
		}

		.ue-btn--cancel,
		.ue-btn--save {
			flex: 1;
			justify-content: center;
		}
	}
</style>

<script>
	(function() {
		'use strict';

		/* ── Toast auto-dismiss ── */
		document.querySelectorAll('.ue-toast').forEach(function(el) {
			setTimeout(function() {
				el.style.transition = 'opacity .4s ease, transform .4s ease';
				el.style.opacity = '0';
				el.style.transform = 'translateY(-8px)';
				setTimeout(function() {
					el.remove();
				}, 420);
			}, 5000);
		});

		/* ── Staggered reveal ── */
		var revealEls = document.querySelectorAll('[data-reveal]');
		revealEls.forEach(function(el, i) {
			el.style.transitionDelay = (i * 60) + 'ms';
			requestAnimationFrame(function() {
				requestAnimationFrame(function() {
					el.classList.add('revealed');
				});
			});
		});

		/* ── Password toggle ── */
		var pwInput = document.getElementById('password');
		var pwToggle = document.getElementById('pwToggle');
		if (pwToggle && pwInput) {
			var eyeShow = pwToggle.querySelector('.ue-eye--show');
			var eyeHide = pwToggle.querySelector('.ue-eye--hide');
			pwToggle.addEventListener('click', function() {
				var isText = pwInput.type === 'text';
				pwInput.type = isText ? 'password' : 'text';
				eyeShow.style.display = isText ? '' : 'none';
				eyeHide.style.display = isText ? 'none' : '';
			});
		}

		/* ── Password strength ── */
		var pwStrength = document.getElementById('pwStrength');
		var pwLabel = document.getElementById('pwLabel');
		var bars = [
			document.getElementById('pwBar1'),
			document.getElementById('pwBar2'),
			document.getElementById('pwBar3'),
			document.getElementById('pwBar4'),
			document.getElementById('pwBar5'),
		];
		var strengthColors = ['#ef4444', '#f97316', '#eab308', '#22c55e', '#16a34a'];
		var strengthLabels = ['Very weak', 'Weak', 'Fair', 'Strong', 'Very strong'];

		function calcStrength(v) {
			if (!v) return 0;
			var s = 0;
			if (v.length >= 6) s++;
			if (v.length >= 10) s++;
			if (/[A-Z]/.test(v)) s++;
			if (/[0-9]/.test(v)) s++;
			if (/[^A-Za-z0-9]/.test(v)) s++;
			return s;
		}

		if (pwInput && pwStrength) {
			pwInput.addEventListener('input', function() {
				var v = pwInput.value;
				if (!v) {
					pwStrength.hidden = true;
					return;
				}
				pwStrength.hidden = false;
				var s = calcStrength(v);
				bars.forEach(function(bar, i) {
					bar.style.background = i < s ? strengthColors[Math.max(0, s - 1)] : '';
				});
				if (pwLabel) {
					pwLabel.textContent = strengthLabels[Math.max(0, s - 1)] || '';
					pwLabel.style.color = strengthColors[Math.max(0, s - 1)] || '';
				}
			});
		}

		/* ── Progress ring ── */
		var progressFill = document.getElementById('progressFill');
		var progressPct = document.getElementById('progressPct');
		var CIRCUMFERENCE = 125.6;

		function updateProgress() {
			var tracked = document.querySelectorAll('[data-track]');
			var filled = 0;
			tracked.forEach(function(el) {
				if (el.value && el.value.trim()) filled++;
			});
			var pct = Math.round((filled / tracked.length) * 100);
			if (progressFill) {
				progressFill.style.strokeDashoffset = CIRCUMFERENCE - (CIRCUMFERENCE * pct / 100);
			}
			if (progressPct) progressPct.textContent = pct + '%';
		}

		document.querySelectorAll('[data-track]').forEach(function(el) {
			el.addEventListener('input', updateProgress);
		});
		updateProgress();

		/* ── Dirty state dot ── */
		var autoSaveDot = document.getElementById('autoSaveDot');
		var formNote = document.getElementById('formNote');
		var isDirty = false;

		document.querySelectorAll('.ue-input').forEach(function(el) {
			el.addEventListener('input', function() {
				if (!isDirty) {
					isDirty = true;
					if (autoSaveDot) autoSaveDot.classList.add('dirty');
					if (formNote) formNote.textContent = 'Unsaved changes';
				}
			});
		});

		/* ── Inline validation ── */
		function validateField(input) {
			var errEl = document.getElementById('err-' + input.id);
			var valid = true;
			var msg = '';

			if (input.required && !input.value.trim()) {
				valid = false;
				msg = 'This field is required.';
			} else if (input.type === 'email' && input.value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input.value)) {
				valid = false;
				msg = 'Enter a valid email address.';
			}

			input.classList.toggle('is-valid', valid && input.value.trim().length > 0);
			input.classList.toggle('is-error', !valid);
			if (errEl) {
				errEl.textContent = msg;
				errEl.classList.toggle('show', !valid);
			}
			return valid;
		}

		['name', 'mobile', 'email'].forEach(function(id) {
			var el = document.getElementById(id);
			if (el) {
				el.addEventListener('blur', function() {
					validateField(el);
				});
				el.addEventListener('input', function() {
					if (el.classList.contains('is-error')) validateField(el);
				});
			}
		});

		/* ── Save button loading ── */
		var form = document.getElementById('editUserForm');
		var saveBtn = document.getElementById('saveBtn');
		var saveTxt = document.getElementById('saveBtnText');

		if (form && saveBtn) {
			form.addEventListener('submit', function(e) {
				var allValid = true;
				['name', 'mobile', 'email'].forEach(function(id) {
					var el = document.getElementById(id);
					if (el && !validateField(el)) allValid = false;
				});
				if (!allValid) {
					e.preventDefault();
					return;
				}

				var checkIcon = saveBtn.querySelector('.ue-btn__icon--check');
				var spinIcon = saveBtn.querySelector('.ue-btn__icon--spin');
				saveBtn.disabled = true;
				if (checkIcon) checkIcon.style.display = 'none';
				if (spinIcon) spinIcon.style.display = '';
				if (saveTxt) saveTxt.textContent = 'Saving…';
			});
		}

	}());
</script>