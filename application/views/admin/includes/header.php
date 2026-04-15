<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
	<title><?php echo isset($title) ? html_escape($title) : 'Admin'; ?></title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	<style>
		/* ═══════════════════════════════════════════════════════════
	   LAYOUT — mobile-first shell
	   ═══════════════════════════════════════════════════════════ */
		:root {
			--bg: #f2f5fb;
			--panel: #ffffff;
			--text: #1f2a44;
			--muted: #6b7a90;
			--line: #e5e9f0;
			--accent: #2563eb;
			--accent-soft: #eef2ff;
			--red: #ef4444;
			--sidebar-w: 264px;
		}

		*,
		*::before,
		*::after {
			box-sizing: border-box;
			margin: 0;
			padding: 0;
		}

		body {
			font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
			color: var(--text);
			background: var(--bg);
			min-height: 100vh;
			-webkit-font-smoothing: antialiased;
		}

		/* ── AUTH BODY ────────────────────────────────────────── */
		body.auth-page {
			display: flex;
			align-items: center;
			justify-content: center;
			padding: 20px;
			background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
		}

		/* ── FORM BASICS ──────────────────────────────────────── */
		h1,
		h2,
		h3,
		h4 {
			line-height: 1.2;
		}

		p {
			color: var(--muted);
			line-height: 1.6;
		}

		label {
			display: block;
			margin-bottom: 7px;
			font-weight: 600;
			font-size: 14px;
			color: var(--text);
		}

		input,
		textarea,
		select {
			width: 100%;
			padding: 11px 14px;
			border-radius: 11px;
			border: 1.5px solid var(--line);
			background: #fff;
			color: var(--text);
			font-size: 15px;
			outline: none;
			transition: border-color .2s, box-shadow .2s;
			font-family: inherit;
		}

		input:focus,
		textarea:focus,
		select:focus {
			border-color: var(--accent);
			box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
		}

		textarea {
			min-height: 100px;
			resize: vertical;
		}

		.form-group {
			margin-bottom: 18px;
		}

		.form-grid {
			display: grid;
			grid-template-columns: repeat(2, 1fr);
			gap: 18px;
		}

		.full-width {
			grid-column: 1/-1;
		}

		.btn-primary {
			display: inline-flex;
			align-items: center;
			justify-content: center;
			width: 100%;
			padding: 13px 22px;
			border: 0;
			border-radius: 11px;
			background: linear-gradient(135deg, var(--accent) 0%, #1d4ed8 100%);
			color: #fff;
			font-weight: 700;
			font-size: 15px;
			cursor: pointer;
			transition: transform .2s, box-shadow .2s;
			box-shadow: 0 4px 12px rgba(37, 99, 235, 0.28);
			font-family: inherit;
		}

		.btn-primary:hover {
			transform: translateY(-2px);
			box-shadow: 0 8px 20px rgba(37, 99, 235, 0.36);
		}

		.message {
			padding: 13px 16px;
			border-radius: 11px;
			margin-bottom: 18px;
			font-size: 14px;
			font-weight: 600;
			display: flex;
			align-items: center;
			gap: 9px;
		}

		.error {
			background: #fef2f2;
			color: #dc2626;
			border-left: 4px solid #dc2626;
		}

		.success {
			background: #f0fdf4;
			color: #16a34a;
			border-left: 4px solid #16a34a;
		}

		/* ── AUTH CARD ────────────────────────────────────────── */
		.auth-shell {
			width: 100%;
			max-width: 460px;
		}

		.auth-card {
			background: var(--panel);
			border: 1px solid var(--line);
			box-shadow: 0 10px 28px rgba(0, 0, 0, 0.10);
			border-radius: 22px;
			padding: 36px 30px;
		}

		.auth-card h1 {
			font-size: 38px;
			margin-bottom: 10px;
			background: linear-gradient(135deg, var(--accent) 0%, #7c3aed 100%);
			-webkit-background-clip: text;
			-webkit-text-fill-color: transparent;
			background-clip: text;
		}

		/* ── APP SHELL ────────────────────────────────────────── */
		.app-layout {
			display: flex;
			min-height: 100vh;
		}

		/* ── SIDEBAR ──────────────────────────────────────────── */
		.sidebar {
			width: var(--sidebar-w);
			background: linear-gradient(180deg, #fff 0%, #fafbfc 100%);
			border-right: 1px solid var(--line);
			position: fixed;
			top: 0;
			left: 0;
			bottom: 0;
			z-index: 30;
			overflow-x: hidden;
			overflow-y: auto;
			box-shadow: 2px 0 8px rgba(0, 0, 0, 0.04);
			transition: transform .28s cubic-bezier(.4, 0, .2, 1);
			/* Hidden off-screen by default on mobile */
			transform: translateX(-100%);
		}

		.sidebar::-webkit-scrollbar {
			width: 4px;
		}

		.sidebar::-webkit-scrollbar-thumb {
			background: #d1d5db;
			border-radius: 4px;
		}

		/* Open state — toggled by JS adding class to body */
		body.sb-open .sidebar {
			transform: translateX(0);
		}

		.sidebar-brand {
			padding: 22px 20px 18px;
			background: linear-gradient(135deg, var(--accent) 0%, #1d4ed8 100%);
			flex-shrink: 0;
		}

		.sidebar-brand-name {
			font-size: 30px;
			font-weight: 900;
			letter-spacing: .05em;
			color: #fff;
			line-height: 1;
			margin-bottom: 3px;
		}

		.sidebar-brand-sub {
			font-size: 10px;
			font-weight: 700;
			letter-spacing: .16em;
			text-transform: uppercase;
			color: rgba(255, 255, 255, .65);
		}

		.sidebar-hint {
			font-size: 10px;
			font-weight: 700;
			text-transform: uppercase;
			letter-spacing: .14em;
			color: #9ca3af;
			margin: 18px 18px 7px;
		}

		.sidebar-nav {
			padding: 0 10px 20px;
		}

		.sidebar-nav a,
		.sidebar-group-toggle {
			display: flex;
			align-items: center;
			gap: 11px;
			width: 100%;
			padding: 10px 13px;
			border-radius: 10px;
			border: 0;
			text-decoration: none;
			color: var(--text);
			font-size: 14px;
			font-weight: 600;
			background: transparent;
			white-space: nowrap;
			cursor: pointer;
			transition: background .2s, color .2s;
			position: relative;
		}

		.sidebar-nav a::before {
			content: '';
			position: absolute;
			left: 0;
			top: 50%;
			transform: translateY(-50%);
			width: 0;
			height: 65%;
			background: var(--accent);
			border-radius: 0 4px 4px 0;
			transition: width .2s;
		}

		.sidebar-nav a.active,
		.sidebar-nav a:hover {
			background: var(--accent-soft);
			color: var(--accent);
		}

		.sidebar-nav a.active::before {
			width: 3px;
		}

		.sidebar-group.open .sidebar-group-toggle,
		.sidebar-group-toggle:hover {
			background: var(--accent-soft);
			color: var(--accent);
		}

		.sidebar-nav i {
			font-size: 14px;
			width: 17px;
			text-align: center;
			flex-shrink: 0;
			opacity: .75;
		}

		.sidebar-nav a:hover i,
		.sidebar-nav a.active i {
			opacity: 1;
		}

		.sidebar-group {
			margin-bottom: 2px;
		}

		.sidebar-group-toggle {
			justify-content: space-between;
		}

		.sidebar-group-label {
			display: flex;
			align-items: center;
			gap: 11px;
			flex: 1;
			min-width: 0;
		}

		.sidebar-subnav {
			display: none;
			padding: 2px 0 2px 14px;
		}

		.sidebar-group.open .sidebar-subnav {
			display: block;
		}

		.sidebar-subnav a {
			padding: 8px 11px;
			font-size: 13px;
			margin-bottom: 1px;
		}

		.sidebar-caret {
			font-size: 10px;
			transition: transform .22s;
			flex-shrink: 0;
		}

		.sidebar-group.open .sidebar-caret {
			transform: rotate(180deg);
		}

		/* ── MAIN AREA ────────────────────────────────────────── */
		.main-area {
			flex: 1;
			display: flex;
			flex-direction: column;
			min-height: 100vh;
			/* On mobile sidebar is overlaid, so no margin needed */
			margin-left: 0;
			width: 100%;
			transition: margin-left .28s cubic-bezier(.4, 0, .2, 1);
			background: var(--bg);
		}

		/* ── TOPBAR ───────────────────────────────────────────── */
		.topbar {
			height: 56px;
			padding: 0 14px;
			display: flex;
			align-items: center;
			justify-content: space-between;
			gap: 10px;
			background: #fff;
			border-bottom: 1px solid var(--line);
			position: sticky;
			top: 0;
			z-index: 20;
			box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
			flex-shrink: 0;
			/* Ensure topbar never breaks */
			overflow: visible;
		}

		.topbar-left {
			display: flex;
			align-items: center;
			gap: 10px;
			flex: 1;
			min-width: 0;
			overflow: hidden;
		}

		.menu-toggle {
			width: 36px;
			height: 36px;
			min-width: 36px;
			border-radius: 9px;
			border: 1.5px solid var(--line);
			background: #fff;
			color: var(--text);
			font-size: 14px;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			cursor: pointer;
			flex-shrink: 0;
			transition: background .2s, border-color .2s, color .2s;
		}

		.menu-toggle:hover {
			background: var(--accent-soft);
			border-color: var(--accent);
			color: var(--accent);
		}

		.topbar-title {
			min-width: 0;
			overflow: hidden;
			flex: 1;
		}

		.topbar-title h1 {
			font-size: 15px;
			margin: 0 0 1px;
			font-weight: 700;
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
			color: var(--text);
		}

		.topbar-title p {
			font-size: 11px;
			color: var(--muted);
			margin: 0;
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
		}

		/* ── PROFILE MENU ─────────────────────────────────────── */
		.profile-menu {
			position: relative;
			flex-shrink: 0;
		}

		.profile-trigger {
			display: flex;
			align-items: center;
			gap: 8px;
			border: 1.5px solid var(--line);
			background: #fff;
			border-radius: 999px;
			padding: 4px 10px 4px 4px;
			cursor: pointer;
			font-family: inherit;
			/* kill all native button chrome */
			-webkit-appearance: none;
			appearance: none;
			outline: none;
			-webkit-tap-highlight-color: transparent;
			transition: border-color .2s, background .2s;
			/* prevent growing too wide */
			max-width: 200px;
		}

		.profile-trigger:focus:not(:focus-visible) {
			outline: none;
		}

		.profile-trigger:focus-visible {
			outline: 2px solid var(--accent);
			outline-offset: 2px;
		}

		.profile-menu.open .profile-trigger,
		.profile-trigger:hover {
			border-color: var(--accent);
			background: var(--accent-soft);
		}

		.topbar-avatar {
			width: 32px;
			height: 32px;
			min-width: 32px;
			border-radius: 50%;
			object-fit: cover;
			display: block;
			border: 2px solid rgba(37, 99, 235, .18);
			flex-shrink: 0;
		}

		.topbar-meta {
			display: flex;
			flex-direction: column;
			gap: 1px;
			min-width: 0;
			overflow: hidden;
		}

		.topbar-meta strong {
			display: block;
			font-size: 13px;
			font-weight: 700;
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
			color: var(--text);
		}

		.topbar-meta span {
			display: block;
			font-size: 10px;
			color: var(--muted);
			font-weight: 600;
			white-space: nowrap;
		}

		.topbar-caret {
			font-size: 10px;
			color: var(--muted);
			flex-shrink: 0;
			transition: transform .2s;
		}

		.profile-menu.open .topbar-caret {
			transform: rotate(180deg);
		}

		/* Dropdown */
		.profile-dropdown {
			position: absolute;
			right: 0;
			top: calc(100% + 7px);
			width: 188px;
			background: #fff;
			border-radius: 13px;
			border: 1px solid var(--line);
			box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
			padding: 6px;
			z-index: 50;
			visibility: hidden;
			opacity: 0;
			pointer-events: none;
			transform: translateY(-5px) scale(.97);
			transition: visibility 0s .15s, opacity .15s ease, transform .15s ease;
		}

		.profile-menu.open .profile-dropdown {
			visibility: visible;
			opacity: 1;
			pointer-events: auto;
			transform: translateY(0) scale(1);
			transition: visibility 0s, opacity .15s ease, transform .15s ease;
		}

		.profile-dropdown a {
			display: flex;
			align-items: center;
			gap: 9px;
			padding: 9px 10px;
			border-radius: 9px;
			text-decoration: none;
			color: var(--text);
			font-weight: 600;
			font-size: 13px;
			transition: background .14s, color .14s;
		}

		.profile-dropdown a:hover {
			background: var(--accent-soft);
			color: var(--accent);
		}

		.profile-dropdown .dd-danger {
			color: var(--red);
		}

		.profile-dropdown .dd-danger:hover {
			background: #fef2f2;
			color: var(--red);
		}

		.dd-icon {
			width: 28px;
			height: 28px;
			border-radius: 8px;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			background: var(--accent-soft);
			color: var(--accent);
			font-size: 11px;
			flex-shrink: 0;
		}

		.dd-danger .dd-icon {
			background: #fef2f2;
			color: var(--red);
		}

		/* ── PAGE GRID ────────────────────────────────────────── */
		.page-grid {
			padding: 16px;
			display: grid;
			gap: 16px;
			flex: 1;
			align-content: start;
		}

		/* ── GENERIC CARDS ────────────────────────────────────── */
		.content-card,
		.form-panel,
		.password-panel {
			background: var(--panel);
			border: 1px solid var(--line);
			box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
			border-radius: 16px;
			padding: 20px;
		}

		/* ── OVERLAY ──────────────────────────────────────────── */
		.sidebar-overlay {
			display: none;
			position: fixed;
			inset: 0;
			background: rgba(0, 0, 0, .42);
			z-index: 25;
			backdrop-filter: blur(2px);
			-webkit-backdrop-filter: blur(2px);
		}

		body.sb-open .sidebar-overlay {
			display: block;
		}

		/* ═══════════════════════════════════════════════════════
	   BREAKPOINTS
	   ═══════════════════════════════════════════════════════ */

		/* ≥ 1024px: sidebar always visible */
		@media (min-width: 1024px) {
			.sidebar {
				transform: translateX(0) !important;
			}

			body.sb-open .sidebar-overlay {
				display: none;
			}

			.main-area {
				margin-left: var(--sidebar-w);
				width: calc(100% - var(--sidebar-w));
			}

			.topbar {
				padding: 0 22px;
				height: 60px;
			}

			.menu-toggle {
				display: none;
			}

			/* hide hamburger on desktop */
			.page-grid {
				padding: 20px;
				gap: 20px;
			}
		}

		/* Mobile: collapse profile trigger name/role */
		@media (max-width: 479px) {

			.topbar-meta,
			.topbar-caret {
				display: none;
			}

			.profile-trigger {
				padding: 3px;
				border-radius: 50%;
				width: 42px;
				height: 42px;
				justify-content: center;
				max-width: 42px;
			}

			.topbar-avatar {
				width: 30px;
				height: 30px;
				min-width: 30px;
			}
		}

		@media (max-width: 359px) {
			.topbar {
				height: 50px;
				padding: 0 10px;
			}

			.menu-toggle {
				width: 32px;
				height: 32px;
				min-width: 32px;
				font-size: 13px;
			}

			.page-grid {
				padding: 10px;
				gap: 10px;
			}
		}

		@media (max-width: 767px) {
			.form-grid {
				grid-template-columns: 1fr;
			}
		}

		@media print {

			.sidebar,
			.topbar,
			.sidebar-overlay {
				display: none;
			}

			.main-area {
				margin-left: 0;
				width: 100%;
			}
		}

		@media (prefers-reduced-motion: reduce) {

			*,
			*::before,
			*::after {
				transition-duration: .01ms !important;
			}
		}
	</style>
</head>

<body class="<?php echo (isset($page_type) && $page_type === 'auth') ? 'auth-page' : 'dashboard-page'; ?>">

	<?php if (isset($page_type) && $page_type === 'dashboard' && isset($admin)):
		$_av = !empty($admin->profile_image) ? $admin->profile_image : 'assets/images/default-profile.svg';
		$_av_src = preg_match('/^https?:\/\//i', $_av) ? $_av : base_url($_av);
	?>

		<div class="app-layout">

			<!-- SIDEBAR -->
			<aside class="sidebar" id="adminSidebar">
				<div class="sidebar-brand">
					<p class="sidebar-brand-name">PROBO</p>
					<p class="sidebar-brand-sub">Admin Panel</p>
				</div>

				<p class="sidebar-hint">Navigation</p>

				<nav class="sidebar-nav">
					<a class="<?php echo (isset($active_page) && $active_page === 'dashboard') ? 'active' : ''; ?>"
						href="<?php echo site_url('admin/dashboard'); ?>">
						<i class="fa-solid fa-gauge-high"></i><span>Dashboard</span>
					</a>
					<a class="<?php echo (isset($active_page) && $active_page === 'users') ? 'active' : ''; ?>"
						href="<?php echo site_url('admin/users'); ?>">
						<i class="fa-solid fa-users"></i><span>Users</span>
					</a>

					<a class="<?php echo (isset($active_page) && $active_page === 'categories') ? 'active' : ''; ?>"
						href="<?php echo site_url('admin/categories'); ?>">
						<i class="fa-solid fa-folder-tree"></i><span>Categories</span>
					</a>
					<div class="sidebar-group <?php echo (isset($active_page) && strpos($active_page, 'questions') === 0) ? 'open' : ''; ?>">
						<button class="sidebar-group-toggle" type="button">
							<span class="sidebar-group-label">
								<i class="fa-solid fa-circle-question"></i><span>Questions</span>
							</span>
							<span class="sidebar-caret"><i class="fa-solid fa-chevron-down"></i></span>
						</button>
						<div class="sidebar-subnav">
							<a class="<?php echo (isset($active_page) && $active_page === 'questions_add') ? 'active' : ''; ?>"
								href="<?php echo site_url('admin/questions/add'); ?>">
								<i class="fa-solid fa-plus"></i><span>Add Question</span>
							</a>

							<a class="<?php echo (isset($active_page) && $active_page === 'questions_view') ? 'active' : ''; ?>"
								href="<?php echo site_url('admin/questions/view'); ?>">
								<i class="fa-solid fa-eye"></i><span>View Questions</span>
							</a>
						</div>
					</div>

					<div class="sidebar-group">
						<button class="sidebar-group-toggle">
							<span class="sidebar-group-label">
								<i class="fas fa-money-bill"></i>
								Deposits
							</span>
							<i class="fas fa-chevron-down sidebar-caret"></i>
						</button>

						<div class="sidebar-subnav">
							<a href="<?php echo site_url('admin/deposits/settings'); ?>">
								Payment Settings
							</a>
							<a href="<?php echo site_url('admin/deposits/requests'); ?>">
								Deposit Requests
							</a>
						</div>
					</div>
					
					<div class="sidebar-group <?php echo (isset($active_page) && strpos($active_page, 'referrals') === 0) ? 'open' : ''; ?>">
						<button class="sidebar-group-toggle" type="button">
							<span class="sidebar-group-label">
								<i class="fa-solid fa-share-nodes"></i><span>Referral</span>
							</span>
							<span class="sidebar-caret"><i class="fa-solid fa-chevron-down"></i></span>
						</button>
						<div class="sidebar-subnav">
							<a class="<?php echo (isset($active_page) && $active_page === 'referrals_add') ? 'active' : ''; ?>"
								href="<?php echo site_url('admin/referrals/add'); ?>">
								<i class="fa-solid fa-plus"></i><span>Add Referral</span>
							</a>

							<a class="<?php echo (isset($active_page) && $active_page === 'referrals_list') ? 'active' : ''; ?>"
								href="<?php echo site_url('admin/referrals/list'); ?>">
								<i class="fa-solid fa-list"></i><span>List Referral</span>
							</a>
						</div>
					</div>
					<a class="<?php echo (isset($active_page) && $active_page === 'withdrawals') ? 'active' : ''; ?>"
						href="<?php echo site_url('admin/withdrawals'); ?>">
						<i class="fa-solid fa-wallet"></i><span>Withdrawals</span>
					</a>
				</nav>
			</aside>

			<div class="sidebar-overlay" id="sidebarOverlay"></div>

			<main class="main-area">

				<!-- TOPBAR -->
				<header class="topbar">
					<div class="topbar-left">
						<button class="menu-toggle" id="menuToggle" type="button" aria-label="Toggle menu">
							<i class="fa-solid fa-bars"></i>
						</button>
						<div class="topbar-title">
							<h1><?php echo html_escape(isset($title) ? $title : 'Admin'); ?></h1>
							<p>Welcome back, <?php echo html_escape($admin->name); ?></p>
						</div>
					</div>

					<div class="profile-menu" id="profileMenu">
						<button class="profile-trigger" id="profileTrigger" type="button"
							aria-label="Profile menu" aria-expanded="false" aria-haspopup="true">
							<img class="topbar-avatar"
								src="<?php echo html_escape($_av_src); ?>"
								alt="<?php echo html_escape($admin->name); ?>">
							<div class="topbar-meta">
								<strong><?php echo html_escape($admin->name); ?></strong>
								<span>Administrator</span>
							</div>
							<i class="fa-solid fa-chevron-down topbar-caret" aria-hidden="true"></i>
						</button>

						<div class="profile-dropdown" id="profileDropdown" role="menu">
							<a href="<?php echo site_url('admin/profile'); ?>" role="menuitem">
								<span class="dd-icon"><i class="fa-solid fa-user"></i></span>
								<strong>My Profile</strong>
							</a>
							<a class="dd-danger" href="<?php echo site_url('admin/logout'); ?>" role="menuitem">
								<span class="dd-icon"><i class="fa-solid fa-right-from-bracket"></i></span>
								<strong>Logout</strong>
							</a>
						</div>
					</div>
				</header>

				<!-- PAGE CONTENT -->
				<div class="page-grid">

				<?php endif; ?>