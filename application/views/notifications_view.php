<?php
$notifications = isset($notifications) && is_array($notifications) ? $notifications : array();
$unread_notifications = isset($unread_notifications) ? (int) $unread_notifications : 0;
$total_notifications = count($notifications);
$read_notifications = max(0, $total_notifications - $unread_notifications);

if (!function_exists('notification_time_label')) {
	function notification_time_label($datetime)
	{
		if (empty($datetime)) return 'Just now';
		$timestamp = strtotime($datetime);
		if (!$timestamp) return (string) $datetime;
		$diff = time() - $timestamp;
		if ($diff < 60) return 'Just now';
		if ($diff < 3600) return floor($diff / 60) . 'm ago';
		if ($diff < 86400) return floor($diff / 3600) . 'h ago';
		if ($diff < 604800) return floor($diff / 86400) . 'd ago';
		return date('d M Y, h:i A', $timestamp);
	}
}

if (!function_exists('notification_normalize_message')) {
	function notification_normalize_message($title, $message)
	{
		static $question_cache = array();
		$CI = &get_instance();

		if (
			stripos((string) $message, 'Question #') === FALSE ||
			!isset($CI->Category_model) ||
			!is_object($CI->Category_model)
		) {
			return (string) $message;
		}

		return preg_replace_callback('/Question\s+#(\d+)/i', function($matches) use ($CI, &$question_cache) {
			$question_id = isset($matches[1]) ? (int) $matches[1] : 0;
			if ($question_id <= 0) {
				return $matches[0];
			}

			if (!array_key_exists($question_id, $question_cache)) {
				$question = $CI->Category_model->get_question($question_id);
				$question_cache[$question_id] = ($question && !empty($question->question))
					? trim((string) $question->question)
					: '';
			}

			if ($question_cache[$question_id] === '') {
				return $matches[0];
			}

			return 'Question **' . $question_cache[$question_id] . '**';
		}, (string) $message);
	}
}

$notification_payload = array();
foreach ($notifications as $notification) {
	$is_unread = empty($notification->is_read);
	$message = isset($notification->message) ? (string) $notification->message : '';
	$message = notification_normalize_message(
		isset($notification->title) ? (string) $notification->title : '',
		$message
	);
	$notification_payload[] = array(
		'id'         => isset($notification->id) ? (int) $notification->id : 0,
		'title'      => isset($notification->title) ? (string) $notification->title : 'Notification',
		'message'    => $message,
		'created_at' => isset($notification->created_at) ? (string) $notification->created_at : '',
		'time_label' => notification_time_label(isset($notification->created_at) ? $notification->created_at : ''),
		'is_read'    => $is_unread ? 0 : 1,
	);
}
?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
	/* ─── Reset & Base ─────────────────────────────────────── */
	*,
	*::before,
	*::after {
		box-sizing: border-box;
		margin: 0;
		padding: 0;
	}

	:root {
		--font: 'Roboto', sans-serif;

		/* Brand palette */
		--blue-50: #eff6ff;
		--blue-100: #dbeafe;
		--blue-200: #bfdbfe;
		--blue-500: #3b82f6;
		--blue-600: #2563eb;
		--blue-700: #1d4ed8;
		--blue-900: #1e3a8a;

		--sky-400: #38bdf8;
		--sky-500: #0ea5e9;

		--emerald-400: #34d399;
		--emerald-500: #10b981;

		--slate-50: #f8fafc;
		--slate-100: #f1f5f9;
		--slate-200: #e2e8f0;
		--slate-300: #cbd5e1;
		--slate-400: #94a3b8;
		--slate-500: #64748b;
		--slate-600: #475569;
		--slate-700: #334155;
		--slate-800: #1e293b;
		--slate-900: #0f172a;

		--red-400: #f87171;
		--red-500: #ef4444;

		--white: #ffffff;

		/* Semantic */
		--surface: var(--white);
		--surface-2: var(--slate-50);
		--border: rgba(15, 23, 42, .07);
		--border-strong: rgba(15, 23, 42, .13);
		--text-primary: var(--slate-900);
		--text-muted: var(--slate-500);
		--text-faint: var(--slate-400);

		--accent-start: var(--blue-600);
		--accent-end: var(--sky-500);
		--accent-grad: linear-gradient(135deg, var(--accent-start), var(--accent-end));

		--radius-sm: 10px;
		--radius-md: 16px;
		--radius-lg: 22px;
		--radius-xl: 28px;
		--radius-full: 999px;

		--shadow-xs: 0 1px 3px rgba(15, 23, 42, .06), 0 1px 2px rgba(15, 23, 42, .04);
		--shadow-sm: 0 4px 12px rgba(15, 23, 42, .07), 0 1px 4px rgba(15, 23, 42, .04);
		--shadow-md: 0 8px 24px rgba(15, 23, 42, .09), 0 2px 8px rgba(15, 23, 42, .05);
		--shadow-lg: 0 16px 40px rgba(15, 23, 42, .11), 0 4px 12px rgba(15, 23, 42, .06);
		--shadow-accent: 0 10px 28px rgba(37, 99, 235, .22);
	}

	body {
		font-family: var(--font);
		background: var(--slate-100);
		color: var(--text-primary);
		min-height: 100vh;
		line-height: 1.6;
		-webkit-font-smoothing: antialiased;
	}

	/* ─── Page Wrapper ─────────────────────────────────────── */
	.np-page {
		max-width: 1080px;
		margin: 0 auto;
		padding: 32px 20px 60px;
		display: grid;
		gap: 24px;
	}

	/* ─── Hero ─────────────────────────────────────────────── */
	.np-hero {
		position: relative;
		overflow: hidden;
		border-radius: var(--radius-xl);
		padding: 36px 36px 32px;
		background: linear-gradient(135deg, #06122b 0%, #0d1f42 45%, #0e3554 100%);
		color: var(--white);
		box-shadow: var(--shadow-lg);
	}

	/* subtle mesh overlay */
	.np-hero::before {
		content: '';
		position: absolute;
		inset: 0;
		background:
			radial-gradient(ellipse 60% 55% at 95% 5%, rgba(59, 130, 246, .28) 0%, transparent 60%),
			radial-gradient(ellipse 50% 50% at 5% 90%, rgba(16, 185, 129, .20) 0%, transparent 60%),
			radial-gradient(ellipse 40% 35% at 50% 50%, rgba(14, 165, 233, .08) 0%, transparent 55%);
		pointer-events: none;
	}

	/* dot-grid texture */
	.np-hero::after {
		content: '';
		position: absolute;
		inset: 0;
		background-image: radial-gradient(circle, rgba(255, 255, 255, .07) 1px, transparent 1px);
		background-size: 22px 22px;
		pointer-events: none;
	}

	.np-hero-inner {
		position: relative;
		z-index: 1;
		display: grid;
		grid-template-columns: 1.25fr 0.85fr;
		gap: 28px;
		align-items: center;
	}

	.np-badge {
		display: inline-flex;
		align-items: center;
		gap: 8px;
		padding: 6px 14px;
		border-radius: var(--radius-full);
		background: rgba(255, 255, 255, .1);
		border: 1px solid rgba(255, 255, 255, .15);
		font-size: 10px;
		font-weight: 700;
		letter-spacing: .13em;
		text-transform: uppercase;
		color: rgba(255, 255, 255, .75);
		margin-bottom: 18px;
	}

	.np-badge i {
		font-size: 11px;
	}

	.np-hero h1 {
		font-size: clamp(26px, 3.5vw, 40px);
		font-weight: 900;
		line-height: 1.1;
		letter-spacing: -.03em;
		margin-bottom: 12px;
	}

	.np-hero-sub {
		font-size: 14px;
		font-weight: 400;
		color: rgba(255, 255, 255, .65);
		line-height: 1.75;
		max-width: 480px;
	}

	/* Stat grid */
	.np-stats {
		display: grid;
		grid-template-columns: repeat(2, 1fr);
		gap: 12px;
	}

	.np-stat-card {
		padding: 18px 16px;
		border-radius: var(--radius-lg);
		background: rgba(255, 255, 255, .09);
		border: 1px solid rgba(255, 255, 255, .13);
		backdrop-filter: blur(10px);
		transition: background .2s;
	}

	.np-stat-card:hover {
		background: rgba(255, 255, 255, .13);
	}

	.np-stat-card strong {
		display: block;
		font-size: 30px;
		font-weight: 900;
		line-height: 1;
		margin-bottom: 6px;
		letter-spacing: -.02em;
	}

	.np-stat-card span {
		font-size: 12px;
		font-weight: 500;
		color: rgba(255, 255, 255, .6);
		text-transform: uppercase;
		letter-spacing: .06em;
	}

	/* ─── Toolbar ───────────────────────────────────────────── */
	.np-toolbar {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 16px;
		flex-wrap: wrap;
	}

	.np-toolbar-left h2 {
		font-size: 22px;
		font-weight: 700;
		color: var(--text-primary);
		letter-spacing: -.02em;
	}

	.np-toolbar-left p {
		font-size: 13px;
		color: var(--text-muted);
		margin-top: 3px;
		font-weight: 400;
	}

	.np-toolbar-right {
		display: flex;
		align-items: center;
		gap: 10px;
		flex-wrap: wrap;
	}

	/* Buttons */
	.np-btn {
		display: inline-flex;
		align-items: center;
		gap: 8px;
		padding: 11px 18px;
		border-radius: var(--radius-md);
		font-family: var(--font);
		font-size: 13px;
		font-weight: 500;
		text-decoration: none;
		cursor: pointer;
		border: none;
		transition: transform .18s ease, box-shadow .18s ease, opacity .18s ease;
		white-space: nowrap;
	}

	.np-btn:hover {
		transform: translateY(-2px);
	}

	.np-btn:active {
		transform: translateY(0);
	}

	.np-btn-ghost {
		background: var(--surface);
		color: var(--text-primary);
		border: 1px solid var(--border-strong);
		box-shadow: var(--shadow-xs);
	}

	.np-btn-ghost:hover {
		box-shadow: var(--shadow-sm);
	}

	.np-btn-primary {
		background: var(--accent-grad);
		color: var(--white);
		box-shadow: var(--shadow-accent);
	}

	.np-btn-primary:hover {
		box-shadow: 0 14px 36px rgba(37, 99, 235, .32);
	}

	/* Flash */
	.np-flash {
		display: flex;
		align-items: center;
		gap: 12px;
		padding: 14px 18px;
		border-radius: var(--radius-md);
		background: linear-gradient(135deg, rgba(16, 185, 129, .1), rgba(56, 189, 248, .08));
		border: 1px solid rgba(16, 185, 129, .2);
		color: #065f46;
		font-size: 14px;
		font-weight: 500;
	}

	/* ─── Board ─────────────────────────────────────────────── */
	.np-board {
		background: var(--surface);
		border: 1px solid var(--border);
		border-radius: var(--radius-xl);
		box-shadow: var(--shadow-md);
		overflow: hidden;
	}

	/* Board header */
	.np-board-head {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 14px;
		padding: 20px 24px 18px;
		border-bottom: 1px solid var(--border);
		flex-wrap: wrap;
		background: var(--surface);
	}

	/* Tabs */
	.np-tabs {
		display: flex;
		align-items: center;
		gap: 6px;
		background: var(--slate-100);
		padding: 5px;
		border-radius: var(--radius-md);
	}

	.np-tab {
		display: inline-flex;
		align-items: center;
		gap: 7px;
		padding: 9px 16px;
		border-radius: var(--radius-sm);
		border: none;
		background: transparent;
		color: var(--text-muted);
		font-family: var(--font);
		font-size: 13px;
		font-weight: 500;
		cursor: pointer;
		transition: all .18s ease;
		white-space: nowrap;
	}

	.np-tab:hover {
		color: var(--text-primary);
		background: rgba(255, 255, 255, .7);
	}

	.np-tab.is-active {
		background: var(--surface);
		color: var(--blue-700);
		font-weight: 700;
		box-shadow: var(--shadow-xs);
	}

	.np-tab-count {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		min-width: 20px;
		height: 20px;
		padding: 0 6px;
		border-radius: var(--radius-full);
		background: var(--slate-200);
		color: var(--text-muted);
		font-size: 11px;
		font-weight: 700;
		transition: background .18s, color .18s;
	}

	.np-tab.is-active .np-tab-count {
		background: var(--blue-100);
		color: var(--blue-700);
	}

	.np-tab-dot {
		width: 7px;
		height: 7px;
		border-radius: 50%;
		background: var(--red-500);
		box-shadow: 0 0 0 3px rgba(239, 68, 68, .18);
		flex-shrink: 0;
	}

	/* Controls */
	.np-controls {
		display: flex;
		align-items: center;
		gap: 10px;
	}

	.np-records-badge {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		padding: 8px 14px;
		border-radius: var(--radius-full);
		background: var(--blue-50);
		color: var(--blue-700);
		font-size: 12px;
		font-weight: 700;
	}

	.np-select-wrap {
		position: relative;
		display: inline-flex;
		align-items: center;
	}

	.np-select-icon {
		position: absolute;
		left: 12px;
		color: var(--text-faint);
		font-size: 12px;
		pointer-events: none;
		z-index: 1;
	}

	.np-select {
		padding: 9px 12px 9px 32px;
		border-radius: var(--radius-sm);
		border: 1px solid var(--border-strong);
		background: var(--surface);
		color: var(--text-primary);
		font-family: var(--font);
		font-size: 13px;
		font-weight: 500;
		outline: none;
		cursor: pointer;
		appearance: none;
		min-width: 120px;
		transition: border-color .18s, box-shadow .18s;
	}

	.np-select:focus {
		border-color: var(--blue-500);
		box-shadow: 0 0 0 3px rgba(37, 99, 235, .1);
	}

	/* Result summary bar */
	.np-summary-bar {
		display: flex;
		align-items: center;
		justify-content: space-between;
		padding: 12px 24px;
		background: var(--slate-50);
		border-bottom: 1px solid var(--border);
		font-size: 12px;
		font-weight: 500;
		color: var(--text-muted);
	}

	.np-summary-bar span strong {
		color: var(--text-primary);
		font-weight: 700;
	}

	/* List */
	.np-list {
		display: grid;
		gap: 1px;
		background: var(--border);
	}

	/* Card */
	.np-card {
		position: relative;
		display: grid;
		grid-template-columns: auto 1fr auto;
		align-items: flex-start;
		gap: 18px;
		padding: 20px 24px;
		background: var(--surface);
		transition: background .15s ease;
	}

	.np-card:hover {
		background: var(--slate-50);
	}

	.np-card.unread {
		background: #fafcff;
		cursor: pointer;
	}

	.np-card.unread:hover {
		background: #f3f7ff;
	}

	.np-card.is-loading {
		pointer-events: none;
		opacity: .72;
	}

	/* unread accent bar */
	.np-card.unread::before {
		content: '';
		position: absolute;
		left: 0;
		top: 16px;
		bottom: 16px;
		width: 4px;
		border-radius: 0 4px 4px 0;
		background: var(--accent-grad);
	}

	/* Icon */
	.np-icon-wrap {
		width: 46px;
		height: 46px;
		border-radius: var(--radius-md);
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 17px;
		flex-shrink: 0;
	}

	.np-card.unread .np-icon-wrap {
		background: var(--blue-50);
		color: var(--blue-600);
	}

	.np-card:not(.unread) .np-icon-wrap {
		background: var(--slate-100);
		color: var(--slate-500);
	}

	/* Content */
	.np-card-body {}

	.np-card-title {
		font-size: 15px;
		font-weight: 700;
		color: var(--text-primary);
		margin-bottom: 5px;
		line-height: 1.3;
	}

	.np-card.unread .np-card-title {
		color: var(--blue-900);
	}

	.np-card-msg {
		font-size: 13px;
		font-weight: 400;
		color: var(--text-muted);
		line-height: 1.65;
		white-space: pre-line;
	}

	.np-card-msg b,
	.np-card-msg strong {
		color: var(--text-primary);
		font-weight: 700;
	}

	/* Meta */
	.np-card-meta {
		display: flex;
		flex-direction: column;
		align-items: flex-end;
		gap: 8px;
		flex-shrink: 0;
	}

	.np-time {
		font-size: 11px;
		font-weight: 500;
		color: var(--text-faint);
		white-space: nowrap;
	}

	.np-pill {
		display: inline-flex;
		align-items: center;
		gap: 5px;
		padding: 5px 10px;
		border-radius: var(--radius-full);
		font-size: 10px;
		font-weight: 700;
		text-transform: uppercase;
		letter-spacing: .08em;
	}

	.np-pill-unread {
		background: var(--blue-50);
		color: var(--blue-700);
		border: 1px solid var(--blue-100);
	}

	.np-pill-read {
		background: var(--slate-100);
		color: var(--slate-500);
		border: 1px solid var(--slate-200);
	}

	.np-pill i {
		font-size: 8px;
	}

	/* ─── Empty state ───────────────────────────────────────── */
	.np-empty {
		padding: 56px 24px;
		text-align: center;
	}

	.np-empty-icon {
		width: 60px;
		height: 60px;
		margin: 0 auto 16px;
		border-radius: var(--radius-lg);
		background: var(--slate-100);
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 24px;
		color: var(--slate-400);
	}

	.np-empty h3 {
		font-size: 17px;
		font-weight: 700;
		color: var(--text-primary);
		margin-bottom: 8px;
	}

	.np-empty p {
		font-size: 13px;
		color: var(--text-muted);
		line-height: 1.7;
	}

	/* ─── Pagination ────────────────────────────────────────── */
	.np-pagination {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 14px;
		flex-wrap: wrap;
		padding: 16px 24px;
		border-top: 1px solid var(--border);
		background: var(--slate-50);
	}

	.np-page-info {
		font-size: 12px;
		font-weight: 500;
		color: var(--text-muted);
	}

	.np-page-btns {
		display: flex;
		align-items: center;
		gap: 4px;
		flex-wrap: wrap;
	}

	.np-page-btn {
		min-width: 36px;
		height: 36px;
		padding: 0 10px;
		border-radius: var(--radius-sm);
		border: 1px solid var(--border-strong);
		background: var(--surface);
		color: var(--text-primary);
		font-family: var(--font);
		font-size: 12px;
		font-weight: 600;
		cursor: pointer;
		transition: all .18s ease;
		display: inline-flex;
		align-items: center;
		justify-content: center;
	}

	.np-page-btn:hover:not(:disabled) {
		background: var(--accent-grad);
		border-color: transparent;
		color: var(--white);
		box-shadow: var(--shadow-accent);
	}

	.np-page-btn.is-active {
		background: var(--accent-grad);
		border-color: transparent;
		color: var(--white);
		box-shadow: var(--shadow-accent);
	}

	.np-page-btn:disabled {
		opacity: .38;
		cursor: not-allowed;
	}

	.np-page-ellipsis {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		width: 36px;
		height: 36px;
		font-size: 13px;
		color: var(--text-faint);
	}

	/* ─── Responsive ────────────────────────────────────────── */
	@media (max-width: 860px) {
		.np-hero-inner {
			grid-template-columns: 1fr;
		}

		.np-stats {
			grid-template-columns: repeat(2, 1fr);
		}
	}

	@media (max-width: 640px) {
		.np-page {
			padding: 16px 14px 48px;
			gap: 18px;
		}

		.np-hero {
			padding: 24px 20px;
			border-radius: var(--radius-lg);
		}

		.np-stats {
			grid-template-columns: repeat(2, 1fr);
			gap: 10px;
		}

		.np-board {
			border-radius: var(--radius-lg);
		}

		.np-board-head {
			padding: 16px 16px 14px;
		}

		.np-tabs {
			padding: 4px;
			gap: 4px;
		}

		.np-tab {
			padding: 8px 12px;
			font-size: 12px;
		}

		.np-card {
			padding: 16px;
			gap: 12px;
		}

		.np-card-meta {
			align-items: flex-start;
			flex-direction: row;
			flex-wrap: wrap;
		}

		.np-summary-bar {
			padding: 10px 16px;
		}

		.np-pagination {
			padding: 14px 16px;
		}
	}

	@media (max-width: 480px) {
		.np-card {
			grid-template-columns: auto 1fr;
		}

		.np-card-meta {
			grid-column: 1 / -1;
			flex-direction: row;
			align-items: center;
		}
	}
</style>

<div class="np-page" id="notificationApp" data-notifications="<?php echo htmlspecialchars(json_encode($notification_payload), ENT_QUOTES, 'UTF-8'); ?>">

	<!-- Hero -->
	<section class="np-hero">
		<div class="np-hero-inner">
			<div>
				<div class="np-badge">
					<i class="fa-solid fa-bell"></i>
					Notification Center
				</div>
				<h1>Stay updated on every wallet, trade &amp; system alert.</h1>
				<p class="np-hero-sub">All your notifications in one place — filter by status, paginate through history, and mark everything read in a single click.</p>
			</div>
			<div class="np-stats">
				<div class="np-stat-card">
					<strong id="npTotalStat"><?php echo $total_notifications; ?></strong>
					<span>Total</span>
				</div>
				<div class="np-stat-card">
					<strong id="npUnreadStat"><?php echo $unread_notifications; ?></strong>
					<span>Unread</span>
				</div>
				<div class="np-stat-card">
					<strong id="npReadStat"><?php echo $read_notifications; ?></strong>
					<span>Read</span>
				</div>
				<div class="np-stat-card">
					<strong id="npPageStat"><?php echo min(10, $total_notifications); ?></strong>
					<span>On Page</span>
				</div>
			</div>
		</div>
	</section>

	<!-- Toolbar -->
	<div class="np-toolbar">
		<div class="np-toolbar-left">
			<h2>All Notifications</h2>
			<p id="npToolbarText"><?php echo $unread_notifications > 0 ? 'New alerts are highlighted — spot them instantly.' : 'Everything is up to date.'; ?></p>
		</div>
		<div class="np-toolbar-right">
			<a class="np-btn np-btn-ghost" href="<?php echo site_url('dashboard'); ?>">
				<i class="fa-solid fa-arrow-left"></i> Dashboard
			</a>
			<a class="np-btn np-btn-primary" href="<?php echo site_url('dashboard/mark-notifications-read?redirect_to=dashboard/notifications'); ?>">
				<i class="fa-solid fa-check-double"></i> Mark all read
			</a>
		</div>
	</div>

	<?php if ($this->session->flashdata('success')): ?>
		<div class="np-flash">
			<i class="fa-solid fa-circle-check"></i>
			<?php echo htmlspecialchars($this->session->flashdata('success'), ENT_QUOTES, 'UTF-8'); ?>
		</div>
	<?php endif; ?>

	<!-- Board -->
	<section class="np-board">

		<!-- Head -->
		<div class="np-board-head">
			<div class="np-tabs" role="tablist" aria-label="Notification filters">
				<button type="button" class="np-tab is-active" data-filter="all">
					All <span class="np-tab-count" id="npAllCount"><?php echo $total_notifications; ?></span>
				</button>
				<button type="button" class="np-tab" data-filter="unread">
					Unread
					<span class="np-tab-count" id="npUnreadCount"><?php echo $unread_notifications; ?></span>
					<?php if ($unread_notifications > 0): ?>
						<span class="np-tab-dot" id="npUnreadDot"></span>
					<?php endif; ?>
				</button>
				<button type="button" class="np-tab" data-filter="read">
					Read <span class="np-tab-count" id="npReadCount"><?php echo $read_notifications; ?></span>
				</button>
			</div>

			<div class="np-controls">
				<div class="np-records-badge" id="npVisibleCount">
					<i class="fa-solid fa-list"></i>
					<?php echo $total_notifications; ?> records
				</div>
				<div class="np-select-wrap">
					<i class="fa-solid fa-table-rows np-select-icon"></i>
					<select class="np-select" id="npRowsPerPage">
						<option value="10" selected>10 / page</option>
						<option value="25">25 / page</option>
						<option value="50">50 / page</option>
						<option value="100">100 / page</option>
						<option value="all">All rows</option>
					</select>
				</div>
			</div>
		</div>

		<!-- Summary bar -->
		<div class="np-summary-bar">
			<span id="npResultSummary">Showing notifications</span>
		</div>

		<!-- List -->
		<div class="np-list" id="npList"></div>

		<!-- Empty -->
		<div class="np-empty" id="npEmptyState" hidden>
			<div class="np-empty-icon"><i class="fa-regular fa-bell-slash"></i></div>
			<h3>No notifications here</h3>
			<p>Try switching tabs to see All, Unread, or Read notifications.</p>
		</div>

		<!-- Pagination -->
		<div class="np-pagination" id="npPagination" hidden>
			<div class="np-page-info" id="npPageInfo"></div>
			<div class="np-page-btns" id="npPageButtons"></div>
		</div>

	</section>
</div>

<script>
	(function() {
		var app = document.getElementById('notificationApp');
		if (!app) return;
		var markReadUrl = <?php echo json_encode(site_url('dashboard/mark_notification_read')); ?>;

		var notifications = [];
		try {
			notifications = JSON.parse(app.getAttribute('data-notifications') || '[]');
		} catch (e) {
			notifications = [];
		}

		var state = {
			filter: 'all',
			rowsPerPage: 10,
			page: 1
		};

		var els = {
			list: document.getElementById('npList'),
			empty: document.getElementById('npEmptyState'),
			pagination: document.getElementById('npPagination'),
			pageInfo: document.getElementById('npPageInfo'),
			pageButtons: document.getElementById('npPageButtons'),
			resultSummary: document.getElementById('npResultSummary'),
			visibleCount: document.getElementById('npVisibleCount'),
			rowsSelect: document.getElementById('npRowsPerPage'),
			toolbarText: document.getElementById('npToolbarText'),
			totalStat: document.getElementById('npTotalStat'),
			unreadStat: document.getElementById('npUnreadStat'),
			readStat: document.getElementById('npReadStat'),
			pageStat: document.getElementById('npPageStat'),
			allCount: document.getElementById('npAllCount'),
			unreadCount: document.getElementById('npUnreadCount'),
			readCount: document.getElementById('npReadCount'),
			unreadDot: document.getElementById('npUnreadDot'),
		};

		var tabs = Array.prototype.slice.call(document.querySelectorAll('.np-tab'));

		function esc(v) {
			return String(v)
				.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
				.replace(/"/g, '&quot;').replace(/'/g, '&#039;');
		}

		function unreadCount() {
			return notifications.filter(function(n) {
				return Number(n.is_read) === 0;
			}).length;
		}

		function readCount() {
			return notifications.length - unreadCount();
		}

		function filtered() {
			return notifications.filter(function(n) {
				if (state.filter === 'unread') return Number(n.is_read) === 0;
				if (state.filter === 'read') return Number(n.is_read) === 1;
				return true;
			});
		}

		function paged(f) {
			if (state.rowsPerPage === 'all') return f;
			var s = (state.page - 1) * state.rowsPerPage;
			return f.slice(s, s + state.rowsPerPage);
		}

		function syncHeaderBadge(count) {
			var badge = document.querySelector('.notification-badge');
			if (!badge) return;
			if (count > 0) {
				badge.textContent = count;
				badge.style.display = 'inline-flex';
			} else {
				badge.style.display = 'none';
			}
		}

		function markNotificationRead(notificationId, cardEl) {
			if (!notificationId) return;
			if (cardEl) cardEl.classList.add('is-loading');

			var body = 'notification_id=' + encodeURIComponent(notificationId);
			fetch(markReadUrl, {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
					'X-Requested-With': 'XMLHttpRequest'
				},
				body: body,
				credentials: 'same-origin'
			}).then(function(response) {
				if (!response.ok) {
					throw new Error('Request failed');
				}
				return response.json();
			}).then(function(payload) {
				var target = notifications.find(function(item) {
					return Number(item.id) === Number(notificationId);
				});
				if (target) {
					target.is_read = 1;
				}
				render();
				if (payload && typeof payload.unread_notifications !== 'undefined') {
					syncHeaderBadge(parseInt(payload.unread_notifications, 10) || 0);
				}
			}).catch(function() {
				if (cardEl) cardEl.classList.remove('is-loading');
			});
		}

		function renderCards(items) {
			if (!items.length) {
				els.list.innerHTML = '';
				els.empty.hidden = false;
				return;
			}
			els.empty.hidden = true;
			els.list.innerHTML = items.map(function(n) {
				var u = Number(n.is_read) === 0;
				return '<article class="np-card' + (u ? ' unread' : '') + '" data-id="' + Number(n.id || 0) + '">' +
					'<div class="np-icon-wrap"><i class="fa-solid ' + (u ? 'fa-bell' : 'fa-check') + '"></i></div>' +
					'<div class="np-card-body">' +
					'<div class="np-card-title">' + esc(n.title) + '</div>' +
					'<div class="np-card-msg">' + esc(n.message).replace(/\*\*(.*?)\*\*/g, '<b>$1</b>') + '</div>' +
					'</div>' +
					'<div class="np-card-meta">' +
					'<span class="np-time">' + esc(n.time_label || 'Just now') + '</span>' +
					'<span class="np-pill ' + (u ? 'np-pill-unread' : 'np-pill-read') + '">' +
					'<i class="fa-solid ' + (u ? 'fa-circle' : 'fa-check') + '"></i>' +
					(u ? 'Unread' : 'Read') +
					'</span>' +
					'</div>' +
					'</article>';
			}).join('');
		}

		function buildPageButtons(totalPages) {
			/* Show max 7 buttons with ellipsis */
			var p = state.page,
				pages = [];
			if (totalPages <= 7) {
				for (var i = 1; i <= totalPages; i++) pages.push(i);
			} else {
				pages.push(1);
				if (p > 3) pages.push('…left');
				var lo = Math.max(2, p - 1),
					hi = Math.min(totalPages - 1, p + 1);
				for (var j = lo; j <= hi; j++) pages.push(j);
				if (p < totalPages - 2) pages.push('…right');
				pages.push(totalPages);
			}

			var html = '<button type="button" class="np-page-btn" data-page="' + (p - 1) + '"' + (p === 1 ? ' disabled' : '') +
				' aria-label="Previous"><i class="fa-solid fa-chevron-left" style="font-size:10px"></i></button>';
			pages.forEach(function(pg) {
				if (typeof pg === 'string') {
					html += '<span class="np-page-ellipsis">…</span>';
				} else {
					html += '<button type="button" class="np-page-btn' + (pg === p ? ' is-active' : '') +
						'" data-page="' + pg + '">' + pg + '</button>';
				}
			});
			html += '<button type="button" class="np-page-btn" data-page="' + (p + 1) + '"' + (p === totalPages ? ' disabled' : '') +
				' aria-label="Next"><i class="fa-solid fa-chevron-right" style="font-size:10px"></i></button>';
			return html;
		}

		function renderPagination(f) {
			if (state.rowsPerPage === 'all' || f.length <= state.rowsPerPage) {
				els.pagination.hidden = true;
				return;
			}
			var totalPages = Math.ceil(f.length / state.rowsPerPage);
			if (state.page > totalPages) state.page = totalPages;
			var start = (state.page - 1) * state.rowsPerPage + 1;
			var end = Math.min(state.page * state.rowsPerPage, f.length);
			els.pagination.hidden = false;
			els.pageInfo.textContent = 'Showing ' + start + '–' + end + ' of ' + f.length;
			els.pageButtons.innerHTML = buildPageButtons(totalPages);
		}

		function renderStats(f, v) {
			var uc = unreadCount(),
				rc = readCount();
			els.totalStat.textContent = notifications.length;
			els.unreadStat.textContent = uc;
			els.readStat.textContent = rc;
			els.pageStat.textContent = v.length;
			els.allCount.textContent = notifications.length;
			els.unreadCount.textContent = uc;
			els.readCount.textContent = rc;
			els.visibleCount.innerHTML = '<i class="fa-solid fa-list"></i> ' + f.length + ' records';
			if (els.unreadDot) els.unreadDot.style.display = uc > 0 ? '' : 'none';
			syncHeaderBadge(uc);
			var txt = state.filter === 'unread' ?
				(uc > 0 ? 'Unread alerts highlighted for quick review.' : 'No unread notifications right now.') :
				state.filter === 'read' ?
				(rc > 0 ? 'These notifications have already been seen.' : 'No read notifications yet.') :
				(uc > 0 ? 'New alerts are highlighted — spot them instantly.' : 'Everything is up to date.');
			els.toolbarText.textContent = txt;
		}

		function renderSummary(f, v) {
			if (!f.length) {
				els.resultSummary.textContent = state.filter === 'unread' ? 'No unread notifications.' : state.filter === 'read' ? 'No read notifications.' : 'No notifications available.';
				return;
			}
			if (state.rowsPerPage === 'all') {
				els.resultSummary.innerHTML = 'Showing all <strong>' + f.length + '</strong> notifications';
				return;
			}
			var start = (state.page - 1) * state.rowsPerPage + 1,
				end = start + v.length - 1;
			els.resultSummary.innerHTML = 'Showing <strong>' + start + '–' + end + '</strong> of <strong>' + f.length + '</strong> notifications';
		}

		function render() {
			tabs.forEach(function(t) {
				t.classList.toggle('is-active', t.getAttribute('data-filter') === state.filter);
			});
			var f = filtered(),
				v = paged(f);
			renderCards(v);
			renderPagination(f);
			renderStats(f, v);
			renderSummary(f, v);
		}

		tabs.forEach(function(t) {
			t.addEventListener('click', function() {
				state.filter = t.getAttribute('data-filter') || 'all';
				state.page = 1;
				render();
			});
		});

		els.rowsSelect.addEventListener('change', function() {
			var v = els.rowsSelect.value;
			state.rowsPerPage = v === 'all' ? 'all' : parseInt(v, 10);
			state.page = 1;
			render();
		});

		els.pageButtons.addEventListener('click', function(e) {
			var btn = e.target.closest('.np-page-btn');
			if (!btn || btn.disabled) return;
			var next = parseInt(btn.getAttribute('data-page'), 10);
			if (!isNaN(next) && next > 0) {
				state.page = next;
				render();
				/* scroll to top of board */
				var board = document.querySelector('.np-board');
				if (board) board.scrollIntoView({
					behavior: 'smooth',
					block: 'start'
				});
			}
		});

		els.list.addEventListener('click', function(e) {
			var card = e.target.closest('.np-card.unread');
			if (!card) return;
			var notificationId = parseInt(card.getAttribute('data-id'), 10);
			if (!notificationId) return;
			markNotificationRead(notificationId, card);
		});

		render();
	})();
</script>
