<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo isset($title) ? html_escape($title) : 'Application'; ?></title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	
	<style>
		:root {
			--primary: #2563eb;
			--primary-light: #3b82f6;
			--primary-dark: #1d4ed8;
			--secondary: #10b981;
			--accent: #8b5cf6;
			--success: #22c55e;
			--warning: #f59e0b;
			--error: #ef4444;
			--dark: #1e293b;
			--dark-light: #334155;
			--text: #475569;
			--text-light: #64748b;
			--text-lighter: #94a3b8;
			--border: #e2e8f0;
			--border-light: #f1f5f9;
			--bg: #f8fafc;
			--white: #ffffff;
			--sidebar-bg: #0f172a;
			--sidebar-hover: #1e293b;
			--shadow-xs: 0 1px 2px rgba(0, 0, 0, 0.05);
			--shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.06);
			--shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
			--shadow-md: 0 6px 12px rgba(0, 0, 0, 0.08);
			--shadow-lg: 0 10px 24px rgba(0, 0, 0, 0.1);
			--shadow-xl: 0 20px 40px rgba(0, 0, 0, 0.12);
		}

		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
		}

		html {
			scroll-behavior: smooth;
			-webkit-font-smoothing: antialiased;
			-moz-osx-font-smoothing: grayscale;
		}

		body {
			font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
			color: var(--text);
			background: var(--bg);
			line-height: 1.6;
		}

		body.auth-page {
			display: flex;
			align-items: center;
			justify-content: center;
			min-height: 100vh;
			padding: 24px;
			background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
			position: relative;
		}

		body.auth-page::before {
			content: "";
			position: absolute;
			inset: 0;
			background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
		}

		h1,
		h2,
		h3,
		h4,
		p {
			margin-top: 0;
		}

		/* Auth Styles */
		.auth-shell {
			width: 100%;
			max-width: 440px;
			position: relative;
			z-index: 1;
		}

		.auth-card {
			background: var(--white);
			border-radius: 20px;
			padding: 40px 36px;
			box-shadow: var(--shadow-xl);
			border: 1px solid var(--border);
		}

		.auth-header {
			text-align: center;
			margin-bottom: 32px;
		}

		.auth-logo {
			width: 64px;
			height: 64px;
			background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
			border-radius: 16px;
			display: flex;
			align-items: center;
			justify-content: center;
			font-size: 32px;
			margin: 0 auto 20px;
			box-shadow: var(--shadow-md);
		}

		.auth-card h1 {
			font-size: 28px;
			font-weight: 700;
			color: var(--dark);
			margin-bottom: 8px;
		}

		.auth-card p {
			color: var(--text-light);
			font-size: 15px;
		}

		.form-group {
			margin-bottom: 20px;
		}

		label {
			display: block;
			margin-bottom: 8px;
			font-weight: 600;
			font-size: 14px;
			color: var(--dark);
		}

		input,
		textarea,
		select {
			width: 100%;
			padding: 12px 16px;
			border-radius: 10px;
			border: 2px solid var(--border);
			background: var(--white);
			color: var(--dark);
			font-size: 15px;
			outline: none;
			transition: all 0.2s;
			font-family: inherit;
		}

		input:focus,
		textarea:focus,
		select:focus {
			border-color: var(--primary);
			box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
		}

		textarea {
			min-height: 100px;
			resize: vertical;
		}

		.btn-primary,
		.btn-outline {
			display: inline-flex;
			align-items: center;
			justify-content: center;
			gap: 8px;
			border-radius: 10px;
			padding: 12px 24px;
			font-weight: 600;
			font-size: 15px;
			text-decoration: none;
			cursor: pointer;
			transition: all 0.2s;
			border: none;
		}

		.btn-primary {
			width: 100%;
			background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
			color: var(--white);
			box-shadow: var(--shadow);
		}

		.btn-primary:hover {
			transform: translateY(-2px);
			box-shadow: var(--shadow-lg);
		}

		.btn-outline {
			background: var(--white);
			color: var(--primary);
			border: 2px solid var(--border);
		}

		.btn-outline:hover {
			border-color: var(--primary);
			background: var(--bg);
		}

		.message {
			padding: 12px 16px;
			border-radius: 10px;
			margin-bottom: 20px;
			font-size: 14px;
			font-weight: 500;
		}

		.error {
			background: #fef2f2;
			color: #991b1b;
			border: 1px solid #fecaca;
		}

		.success {
			background: #f0fdf4;
			color: #166534;
			border: 1px solid #bbf7d0;
		}

		.link-text {
			text-align: center;
			margin-top: 20px;
			font-size: 14px;
			color: var(--text-light);
		}

		.link-text a,
		.inline-link {
			color: var(--primary);
			text-decoration: none;
			font-weight: 600;
		}

		.link-text a:hover {
			text-decoration: underline;
		}

		/* Dashboard Layout */
		.app-layout {
			display: flex;
			min-height: 100vh;
			background: var(--bg);
		}

		/* Sidebar */
		.sidebar {
			width: 260px;
			background: var(--sidebar-bg);
			position: fixed;
			left: 0;
			top: 0;
			bottom: 0;
			z-index: 40;
			transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
			overflow-y: auto;
			overflow-x: hidden;
		}

		.sidebar::-webkit-scrollbar {
			width: 6px;
		}

		.sidebar::-webkit-scrollbar-track {
			background: transparent;
		}

		.sidebar::-webkit-scrollbar-thumb {
			background: rgba(255, 255, 255, 0.1);
			border-radius: 10px;
		}

		.sidebar-brand {
			padding: 24px 20px;
			border-bottom: 1px solid rgba(255, 255, 255, 0.1);
		}

		.brand-logo {
			display: flex;
			align-items: center;
			gap: 12px;
		}

		.brand-icon {
			width: 44px;
			height: 44px;
			background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
			border-radius: 12px;
			display: flex;
			align-items: center;
			justify-content: center;
			font-size: 24px;
		}

		.brand-text h2 {
			font-size: 24px;
			font-weight: 800;
			color: var(--white);
			letter-spacing: 0.5px;
			margin: 0;
		}

		.brand-text p {
			font-size: 11px;
			color: rgba(255, 255, 255, 0.5);
			text-transform: uppercase;
			letter-spacing: 1px;
			margin: 2px 0 0;
		}

		.sidebar-section {
			padding: 20px 0;
		}

		.sidebar-label {
			padding: 0 20px;
			font-size: 11px;
			font-weight: 700;
			text-transform: uppercase;
			letter-spacing: 1.2px;
			color: rgba(255, 255, 255, 0.4);
			margin-bottom: 8px;
		}

		.sidebar-nav {
			padding: 0 12px;
		}

		.sidebar-nav a {
			display: flex;
			align-items: center;
			gap: 12px;
			padding: 12px 16px;
			border-radius: 10px;
			text-decoration: none;
			color: rgba(255, 255, 255, 0.7);
			font-weight: 600;
			font-size: 14px;
			transition: all 0.2s;
			margin-bottom: 4px;
		}

		.sidebar-nav a:hover {
			background: var(--sidebar-hover);
			color: var(--white);
		}

		.sidebar-nav a.active {
			background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
			color: var(--white);
			box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
		}

		.sidebar-nav i {
			width: 20px;
			text-align: center;
			font-size: 18px;
		}

		body.sidebar-collapsed .sidebar {
			width: 80px;
		}

		body.sidebar-collapsed .brand-text,
		body.sidebar-collapsed .sidebar-label,
		body.sidebar-collapsed .sidebar-nav a span {
			display: none;
		}

		body.sidebar-collapsed .brand-logo {
			justify-content: center;
		}

		body.sidebar-collapsed .sidebar-nav a {
			justify-content: center;
		}

		/* Main Area */
		.main-area {
			flex: 1;
			margin-left: 260px;
			transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
			min-height: 100vh;
			display: flex;
			flex-direction: column;
		}

		body.sidebar-collapsed .main-area {
			margin-left: 80px;
		}

		/* Topbar */
		.topbar {
			background: var(--white);
			border-bottom: 1px solid var(--border);
			padding: 16px 24px;
			display: flex;
			align-items: center;
			justify-content: space-between;
			gap: 20px;
			position: sticky;
			top: 0;
			z-index: 30;
			box-shadow: var(--shadow-sm);
		}

		.topbar-left {
			display: flex;
			align-items: center;
			gap: 16px;
			flex: 1;
			min-width: 0;
		}

		.menu-toggle {
			width: 40px;
			height: 40px;
			border-radius: 10px;
			border: 1px solid var(--border);
			background: var(--white);
			color: var(--text);
			font-size: 18px;
			display: flex;
			align-items: center;
			justify-content: center;
			cursor: pointer;
			transition: all 0.2s;
		}

		.menu-toggle:hover {
			background: var(--bg);
			border-color: var(--primary);
			color: var(--primary);
		}

		.topbar-title h1 {
			font-size: 20px;
			font-weight: 700;
			color: var(--dark);
			margin-bottom: 2px;
		}

		.topbar-title p {
			font-size: 13px;
			color: var(--text-light);
			margin: 0;
		}

		/* Profile Menu */
		.profile-menu {
			position: relative;
		}

		.profile-trigger {
			display: flex;
			align-items: center;
			gap: 12px;
			padding: 6px 12px 6px 6px;
			border: 1px solid var(--border);
			background: var(--white);
			border-radius: 50px;
			cursor: pointer;
			transition: all 0.2s;
		}

		.profile-trigger:hover {
			box-shadow: var(--shadow-sm);
			border-color: var(--primary);
		}

		.avatar-circle {
			width: 40px;
			height: 40px;
			border-radius: 50%;
			object-fit: cover;
			border: 2px solid var(--white);
			box-shadow: var(--shadow-sm);
		}

		.profile-meta-mini {
			display: flex;
			flex-direction: column;
			align-items: flex-start;
		}

		.profile-meta-mini strong {
			font-size: 14px;
			font-weight: 600;
			color: var(--dark);
		}

		.profile-meta-mini span {
			font-size: 12px;
			color: var(--text-light);
		}

		.profile-dropdown {
			position: absolute;
			right: 0;
			top: calc(100% + 8px);
			width: 220px;
			background: var(--white);
			border-radius: 12px;
			border: 1px solid var(--border);
			box-shadow: var(--shadow-lg);
			padding: 8px;
			display: none;
			z-index: 50;
		}

		.profile-menu.open .profile-dropdown {
			display: block;
			animation: dropdownSlide 0.2s ease;
		}

		@keyframes dropdownSlide {
			from {
				opacity: 0;
				transform: translateY(-10px);
			}

			to {
				opacity: 1;
				transform: translateY(0);
			}
		}

		.profile-dropdown a {
			display: flex;
			align-items: center;
			gap: 12px;
			padding: 12px 14px;
			border-radius: 8px;
			text-decoration: none;
			color: var(--text);
			font-weight: 600;
			font-size: 14px;
			transition: all 0.2s;
		}

		.profile-dropdown a:hover {
			background: var(--bg);
		}

		.profile-dropdown .danger-link {
			color: var(--error);
		}

		.profile-dropdown .danger-link:hover {
			background: #fef2f2;
		}

		.profile-dropdown-icon {
			width: 36px;
			height: 36px;
			border-radius: 8px;
			display: flex;
			align-items: center;
			justify-content: center;
			background: var(--bg);
			color: var(--primary);
			font-size: 14px;
			font-weight: 700;
		}

		.danger-link .profile-dropdown-icon {
			background: #fef2f2;
			color: var(--error);
		}

		/* Page Content */
		.page-grid {
			flex: 1;
			padding: 24px;
		}

		/* Cards */
		.content-card,
		.form-panel,
		.password-panel,
		.profile-summary-card {
			background: var(--white);
			border: 1px solid var(--border);
			border-radius: 16px;
			padding: 24px;
			box-shadow: var(--shadow-sm);
		}

		.section-title {
			font-size: 20px;
			font-weight: 700;
			color: var(--dark);
			margin-bottom: 8px;
		}

		/* Dashboard Hero */
		.dashboard-hero {
			background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
			border-radius: 16px;
			padding: 32px;
			color: var(--white);
			margin-bottom: 24px;
			box-shadow: var(--shadow-md);
		}

		.dashboard-hero h2 {
			font-size: 28px;
			font-weight: 700;
			margin-bottom: 8px;
		}

		.dashboard-hero p {
			color: rgba(255, 255, 255, 0.8);
			font-size: 15px;
			margin: 0;
		}

		.summary-name {
			display: inline-flex;
			align-items: center;
			gap: 12px;
			padding: 8px 16px;
			background: rgba(255, 255, 255, 0.15);
			backdrop-filter: blur(10px);
			border-radius: 50px;
			margin-top: 16px;
			border: 1px solid rgba(255, 255, 255, 0.2);
		}

		.summary-name strong {
			color: var(--white);
			font-size: 14px;
		}

		/* Table */
		.dashboard-table {
			background: var(--white);
			border-radius: 12px;
			overflow: hidden;
			border: 1px solid var(--border);
			box-shadow: var(--shadow-sm);
		}

		.dashboard-head {
			display: grid;
			grid-template-columns: 1.2fr 0.9fr 0.8fr;
			gap: 16px;
			padding: 16px 20px;
			background: var(--bg);
			font-weight: 700;
			font-size: 13px;
			color: var(--dark);
			text-transform: uppercase;
			letter-spacing: 0.5px;
		}

		.dashboard-row {
			display: grid;
			grid-template-columns: 1.2fr 0.9fr 0.8fr;
			gap: 16px;
			padding: 16px 20px;
			border-top: 1px solid var(--border);
			align-items: center;
		}

		.badge-ok {
			display: inline-flex;
			align-items: center;
			padding: 6px 12px;
			background: #dcfce7;
			color: #166534;
			border-radius: 50px;
			font-size: 12px;
			font-weight: 600;
		}

		/* Profile Page */
		.profile-page-grid {
			display: grid;
			grid-template-columns: 340px 1fr;
			gap: 24px;
		}

		.profile-summary-card {
			text-align: center;
			height: fit-content;
		}

		.profile-cover {
			height: 100px;
			background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
			border-radius: 12px 12px 0 0;
			margin: -24px -24px 0;
		}

		.avatar-large {
			width: 100px;
			height: 100px;
			margin: -50px auto 16px;
			position: relative;
			background: var(--white);
			border-radius: 50%;
			padding: 4px;
			box-shadow: var(--shadow-md);
		}

		.avatar-large img,
		.avatar-large span {
			width: 92px;
			height: 92px;
			border-radius: 50%;
			object-fit: cover;
		}

		.avatar-large span {
			display: flex;
			align-items: center;
			justify-content: center;
			background: linear-gradient(135deg, #ff8a5b 0%, #ff6b35 100%);
			color: var(--white);
			font-size: 36px;
			font-weight: 700;
		}

		.avatar-upload-input {
			display: none;
		}

		.avatar-upload-btn {
			position: absolute;
			right: 0;
			bottom: 0;
			width: 32px;
			height: 32px;
			border-radius: 50%;
			background: var(--primary);
			color: var(--white);
			display: flex;
			align-items: center;
			justify-content: center;
			border: 3px solid var(--white);
			cursor: pointer;
			font-size: 14px;
			box-shadow: var(--shadow);
		}

		.profile-stat-list {
			margin-top: 24px;
			display: grid;
			gap: 12px;
			text-align: left;
		}

		.profile-stat-item {
			padding: 14px 16px;
			background: var(--bg);
			border-radius: 10px;
			border: 1px solid var(--border);
		}

		.profile-stat-item strong {
			display: block;
			font-size: 13px;
			color: var(--text-light);
			margin-bottom: 4px;
		}

		.profile-stat-item span {
			font-size: 15px;
			font-weight: 600;
			color: var(--dark);
		}

		.form-grid {
			display: grid;
			grid-template-columns: repeat(2, 1fr);
			gap: 20px;
		}

		.form-grid .full-width {
			grid-column: 1 / -1;
		}

		.form-panel+.password-panel {
			margin-top: 24px;
		}

		/* Footer */
		.site-footer {
			text-align: center;
			padding: 20px;
			color: var(--text-light);
			font-size: 13px;
			border-top: 1px solid var(--border);
		}

		/* Overlay */
		.sidebar-overlay {
			display: none;
			position: fixed;
			inset: 0;
			background: rgba(0, 0, 0, 0.5);
			z-index: 35;
		}

		body.sidebar-open .sidebar-overlay {
			display: block;
		}

		/* Responsive */
		@media (max-width: 1024px) {
			.profile-page-grid {
				grid-template-columns: 1fr;
			}
		}

		@media (max-width: 768px) {
			.sidebar {
				transform: translateX(-100%);
			}

			body.sidebar-open .sidebar {
				transform: translateX(0);
			}

			.main-area {
				margin-left: 0;
			}

			body.sidebar-collapsed .main-area {
				margin-left: 0;
			}

			.topbar {
				padding: 12px 16px;
			}

			.topbar-title h1 {
				font-size: 16px;
			}

			.topbar-title p {
				display: none;
			}

			.profile-meta-mini {
				display: none;
			}

			.profile-trigger {
				padding: 0;
				width: 40px;
				height: 40px;
				border-radius: 50%;
				justify-content: center;
			}

			.page-grid {
				padding: 16px;
			}

			.dashboard-hero {
				padding: 24px;
			}

			.dashboard-hero h2 {
				font-size: 22px;
			}

			.dashboard-head {
				display: none;
			}

			.dashboard-row {
				grid-template-columns: 1fr;
				gap: 8px;
			}

			.form-grid {
				grid-template-columns: 1fr;
			}
		}
	</style>
</head>

<body class="<?php echo (isset($page_type) && $page_type === 'auth') ? 'auth-page' : 'dashboard-page'; ?>">
	<?php if (isset($page_type) && $page_type === 'dashboard' && isset($user)): ?>
		<?php
		$header_profile_image = !empty($user->profile_image) ? $user->profile_image : 'assets/images/default-profile.svg';
		$header_profile_image_src = preg_match('/^https?:\/\//i', $header_profile_image) ? $header_profile_image : base_url($header_profile_image);
		?>
		<div class="app-layout">
			<aside class="sidebar" id="appSidebar">
				<div class="sidebar-brand">
					<div class="brand-logo">
						<!-- <div class="brand-icon">📋</div> -->
						<div class="brand-text">
							<h2>PROBO</h2>
							<!-- <p>Assessment</p> -->
						</div>
					</div>
				</div>

				<div class="sidebar-section">
					<div class="sidebar-label">Navigation</div>
					<nav class="sidebar-nav">
						<a class="<?php echo (isset($active_page) && $active_page === 'dashboard') ? 'active' : ''; ?>" href="<?php echo site_url('dashboard'); ?>">
							<i class="fa-solid fa-chart-pie"></i>
							<span>Dashboard</span>
						</a>

						<a class="<?php echo (isset($active_page) && $active_page === 'questions') ? 'active' : ''; ?>" href="<?php echo site_url('questions'); ?>">
							<i class="fa-solid fa-clipboard-question"></i>
							<span>Questions</span>
						</a>

					</nav>
				</div>
			</aside>

			<div class="sidebar-overlay" id="sidebarOverlay"></div>

			<main class="main-area">
				<header class="topbar">
					<div class="topbar-left">
						<button class="menu-toggle" id="menuToggle" type="button" aria-label="Toggle menu">
							<i class="fa-solid fa-bars"></i>
						</button>
						<div class="topbar-title">
							<h1><?php echo html_escape(isset($title) ? $title : 'Dashboard'); ?></h1>
							<p>Welcome back, <?php echo html_escape($user->name); ?></p>
						</div>
					</div>

					<div class="profile-menu" id="profileMenu">
						<button class="profile-trigger" id="profileTrigger" type="button">
							<img class="avatar-circle" <?php echo (isset($active_page) && $active_page === 'profile') ? 'id="profilePreviewTop"' : ''; ?> src="<?php echo html_escape($header_profile_image_src); ?>" alt="Profile">
							<div class="profile-meta-mini">
								<strong><?php echo html_escape($user->name); ?></strong>
								<span><?php echo (isset($user->role) && (int) $user->role === 1) ? 'Admin' : 'User'; ?></span>
							</div>
						</button>

						<div class="profile-dropdown">
							<a href="<?php echo site_url('wallet'); ?>">
								<span class="profile-dropdown-icon"><i class="fa-solid fa-wallet"></i></span>
								<span>Wallet</span>
							</a>
							<a href="<?php echo site_url('profile'); ?>">
								<span class="profile-dropdown-icon"><i class="fa-solid fa-user"></i></span>
								<span>Profile</span>
							</a>
							<a class="danger-link" href="<?php echo site_url('logout'); ?>">
								<span class="profile-dropdown-icon"><i class="fa-solid fa-right-from-bracket"></i></span>
								<span>Logout</span>
							</a>
						</div>
					</div>
				</header>

				<div class="page-grid">
				<?php endif; ?>
