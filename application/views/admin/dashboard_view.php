<?php
$profile_image = !empty($admin->profile_image) ? $admin->profile_image : 'assets/images/default-profile.svg';
$profile_image_src = preg_match('/^https?:\/\//i', $profile_image) ? $profile_image : base_url($profile_image);
?>

<style>
	.db2-page {
		display: grid;
		gap: 20px;
	}

	.db2-hero {
		display: flex;
		justify-content: space-between;
		align-items: center;
		gap: 20px;
		padding: 28px;
		border-radius: 24px;
		background: linear-gradient(135deg, #123c8d 0%, #2563eb 58%, #4338ca 100%);
		box-shadow: 0 18px 36px rgba(37, 99, 235, 0.22);
		color: #fff;
		overflow: hidden;
		position: relative;
	}

	.db2-hero::after {
		content: '';
		position: absolute;
		right: -50px;
		top: -50px;
		width: 220px;
		height: 220px;
		border-radius: 50%;
		background: radial-gradient(circle, rgba(255, 255, 255, 0.14) 0%, transparent 72%);
	}

	.db2-hero-title {
		margin: 0 0 8px;
		font-size: 30px;
		color: #fff;
		position: relative;
		z-index: 1;
	}

	.db2-hero-sub {
		margin: 0;
		max-width: 620px;
		color: rgba(255, 255, 255, 0.82);
		position: relative;
		z-index: 1;
	}

	.db2-chip {
		display: inline-flex;
		align-items: center;
		gap: 12px;
		padding: 10px 16px 10px 10px;
		border-radius: 999px;
		background: rgba(255, 255, 255, 0.14);
		border: 1px solid rgba(255, 255, 255, 0.22);
		backdrop-filter: blur(10px);
		-webkit-backdrop-filter: blur(10px);
		position: relative;
		z-index: 1;
	}

	.db2-chip-av {
		width: 44px;
		height: 44px;
		border-radius: 50%;
		object-fit: cover;
		border: 2px solid rgba(255, 255, 255, 0.72);
	}

	.db2-chip-info {
		display: flex;
		flex-direction: column;
	}

	.db2-chip-name {
		color: #fff;
		font-weight: 700;
	}

	.db2-chip-role {
		font-size: 12px;
		color: rgba(255, 255, 255, 0.7);
		text-transform: uppercase;
		letter-spacing: 0.08em;
	}

	.db2-stats {
		display: grid;
		grid-template-columns: repeat(3, minmax(0, 1fr));
		gap: 18px;
	}

	.db2-card {
		position: relative;
		padding: 22px 20px;
		border-radius: 22px;
		background: #fff;
		border: 1px solid #e5e7eb;
		box-shadow: 0 8px 22px rgba(15, 23, 42, 0.05);
		overflow: hidden;
	}

	.db2-card::before {
		content: '';
		position: absolute;
		left: 0;
		top: 0;
		width: 100%;
		height: 4px;
		background: var(--card-accent, #2563eb);
	}

	.db2-card[data-a="indigo"] {
		--card-accent: #6366f1;
	}

	.db2-card[data-a="rose"] {
		--card-accent: #f43f5e;
	}

	.db2-card[data-a="sky"] {
		--card-accent: #0ea5e9;
	}

	.db2-card[data-a="emerald"] {
		--card-accent: #10b981;
	}

	.db2-card[data-a="amber"] {
		--card-accent: #f59e0b;
	}

	.db2-card[data-a="violet"] {
		--card-accent: #8b5cf6;
	}

	.db2-label {
		display: flex;
		align-items: center;
		gap: 7px;
		margin-bottom: 12px;
		font-size: 12px;
		font-weight: 700;
		text-transform: uppercase;
		letter-spacing: 0.08em;
		color: #64748b;
	}

	.db2-dot {
		width: 8px;
		height: 8px;
		border-radius: 50%;
		background: var(--card-accent, #2563eb);
	}

	.db2-val {
		font-size: 42px;
		line-height: 1;
		font-weight: 800;
		color: #0f172a;
		margin-bottom: 10px;
	}

	.db2-note {
		font-size: 14px;
		color: #64748b;
	}

	.db2-ico {
		position: absolute;
		right: 16px;
		top: 16px;
		font-size: 26px;
		opacity: 0.12;
	}

	.db2-act-card {
		background: linear-gradient(180deg, #eef2ff 0%, #f8faff 100%);
	}

	.db2-act-list {
		display: grid;
		gap: 10px;
		margin-top: 8px;
	}

	.db2-act-link {
		display: inline-flex;
		align-items: center;
		gap: 8px;
		color: #4338ca;
		text-decoration: none;
		font-weight: 700;
	}

	.db2-panel {
		background: #fff;
		border: 1px solid #e5e7eb;
		border-radius: 24px;
		overflow: hidden;
		box-shadow: 0 10px 28px rgba(15, 23, 42, 0.05);
	}

	.db2-panel-head {
		display: flex;
		justify-content: space-between;
		align-items: center;
		gap: 16px;
		padding: 22px 24px;
		border-bottom: 1px solid #eef2f7;
	}

	.db2-panel-title {
		display: flex;
		align-items: center;
		gap: 10px;
		margin: 0 0 4px;
		font-size: 24px;
		color: #111827;
	}

	.db2-panel-icon {
		width: 34px;
		height: 34px;
		border-radius: 12px;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		background: #eef2ff;
		color: #4338ca;
	}

	.db2-panel-sub {
		margin: 0;
		color: #6b7280;
	}

	.db2-btn-all {
		display: inline-flex;
		align-items: center;
		gap: 7px;
		padding: 10px 14px;
		border-radius: 12px;
		background: #f8fafc;
		color: #1e3a8a;
		text-decoration: none;
		font-weight: 700;
	}

	.db2-tbl-wrap {
		overflow-x: auto;
	}

	.db2-tbl {
		width: 100%;
		border-collapse: collapse;
		min-width: 680px;
	}

	.db2-tbl th {
		padding: 14px 18px;
		background: #f8fafc;
		color: #64748b;
		font-size: 12px;
		text-transform: uppercase;
		letter-spacing: 0.08em;
		text-align: left;
		border-bottom: 1px solid #e5e7eb;
	}

	.db2-tbl td {
		padding: 16px 18px;
		border-bottom: 1px solid #eef2f7;
		color: #1f2937;
	}

	.db2-tbl tbody tr:hover {
		background: #f8fbff;
	}

	.db2-badge {
		display: inline-flex;
		align-items: center;
		padding: 6px 12px;
		border-radius: 999px;
		font-size: 12px;
		font-weight: 700;
		background: #dbeafe;
		color: #1d4ed8;
		border: 1px solid #93c5fd;
	}

	.db2-win-list{display:grid;gap:14px;padding:22px 24px 24px}
	.db2-win-card{display:grid;grid-template-columns:56px minmax(0,1fr) auto;gap:14px;align-items:center;padding:16px 18px;border-radius:18px;background:#f8fbff;border:1px solid #dbeafe}
	.db2-win-rank{width:56px;height:56px;border-radius:18px;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#dbeafe 0%,#c7d2fe 100%);color:#1d4ed8;font-size:22px;font-weight:800}
	.db2-win-user h4{margin:0 0 4px;color:#0f172a;font-size:18px}
	.db2-win-user p{margin:0;color:#64748b;font-size:14px}
	.db2-win-user small{display:block;margin-top:6px;color:#94a3b8}
	.db2-win-value{text-align:right}
	.db2-win-value strong{display:block;font-size:24px;color:#166534}
	.db2-win-value span{display:block;color:#64748b;font-size:13px}

	@media (max-width: 1100px) {
		.db2-stats {
			grid-template-columns: repeat(2, minmax(0, 1fr));
		}
	}

	@media (max-width: 820px) {

		.db2-hero,
		.db2-panel-head {
			flex-direction: column;
			align-items: stretch;
		}

		.db2-stats {
			grid-template-columns: 1fr;
		}
	}

	@media (max-width: 640px) {

		.db2-hero,
		.db2-panel-head {
			padding: 20px;
		}

		.db2-card {
			padding: 20px 18px;
		}

		.db2-hero-title,
		.db2-panel-title {
			font-size: 24px;
		}
	}
</style>

<div class="db2-page">
	<section class="db2-hero">
		<div>
			<h2 class="db2-hero-title">PROBO Admin Dashboard</h2>
			<p class="db2-hero-sub">Platform overview for accounts, categories and questions.</p>
		</div>
		<div class="db2-chip">
			<img class="db2-chip-av" src="<?php echo html_escape($profile_image_src); ?>" alt="<?php echo html_escape($admin->name); ?>">
			<div class="db2-chip-info">
				<span class="db2-chip-name"><?php echo html_escape($admin->name); ?></span>
				<span class="db2-chip-role">Administrator</span>
			</div>
		</div>
	</section>

	<div class="db2-stats">
		<div class="db2-card" data-a="indigo">
			<span class="db2-ico"><i class="fa-solid fa-users"></i></span>
			<div class="db2-label"><span class="db2-dot"></span>Total Users</div>
			<div class="db2-val"><?php echo number_format((int) $total_users); ?></div>
			<div class="db2-note">All registered accounts including admins</div>
		</div>

		<div class="db2-card" data-a="rose">
			<span class="db2-ico"><i class="fa-solid fa-user-shield"></i></span>
			<div class="db2-label"><span class="db2-dot"></span>Admins</div>
			<div class="db2-val"><?php echo number_format((int) $total_admins); ?></div>
			<div class="db2-note">Accounts with dashboard access</div>
		</div>

		<div class="db2-card" data-a="sky">
			<span class="db2-ico"><i class="fa-solid fa-user"></i></span>
			<div class="db2-label"><span class="db2-dot"></span>Members</div>
			<div class="db2-val"><?php echo number_format((int) $total_members); ?></div>
			<div class="db2-note">Regular user accounts only</div>
		</div>

		<div class="db2-card" data-a="emerald">
			<span class="db2-ico"><i class="fa-solid fa-folder-tree"></i></span>
			<div class="db2-label"><span class="db2-dot"></span>Categories</div>
			<div class="db2-val"><?php echo number_format((int) $total_categories); ?></div>
			<div class="db2-note">Question categories available in the system</div>
		</div>

		<div class="db2-card" data-a="amber">
			<span class="db2-ico"><i class="fa-solid fa-circle-question"></i></span>
			<div class="db2-label"><span class="db2-dot"></span>Questions</div>
			<div class="db2-val"><?php echo number_format((int) $total_questions); ?></div>
			<div class="db2-note">Total questions across all categories</div>
		</div>

		<div class="db2-card db2-act-card" data-a="violet">
			<div class="db2-label"><span class="db2-dot"></span>Quick Actions</div>
			<div class="db2-act-list">
				<a class="db2-act-link" href="<?php echo site_url('admin/categories'); ?>">
					<i class="fa-solid fa-folder-tree"></i> Manage Categories
				</a>
				<a class="db2-act-link" href="<?php echo site_url('admin/questions/add'); ?>">
					<i class="fa-solid fa-plus"></i> Add Question
				</a>
				<a class="db2-act-link" href="<?php echo site_url('admin/users'); ?>">
					<i class="fa-solid fa-users"></i> Manage User List
				</a>
			</div>
		</div>
	</div>

	<section class="db2-panel">
		<div class="db2-panel-head">
			<div>
				<h3 class="db2-panel-title">
					<span class="db2-panel-icon"><i class="fa-solid fa-users"></i></span>
					Recent Users
				</h3>
				<p class="db2-panel-sub">Latest registered regular users</p>
			</div>
			<a class="db2-btn-all" href="<?php echo site_url('admin/users'); ?>">
				View All <i class="fa-solid fa-arrow-right" style="font-size: 10px;"></i>
			</a>
		</div>

		<div class="db2-tbl-wrap">
			<table class="db2-tbl">
				<thead>
					<tr>
						<th>S.No</th>
						<th>Name</th>
						<th>Mobile</th>
						<th>Email</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($latest_users)): ?>
						<?php foreach ($latest_users as $index => $u): ?>
							<tr>
								<td><?php echo (int) $index + 1; ?></td>
								<td><?php echo html_escape($u->name); ?></td>
								<td><?php echo html_escape($u->mobile); ?></td>
								<td><?php echo html_escape($u->email); ?></td>
								<td><span class="db2-badge">User</span></td>
							</tr>
						<?php endforeach; ?>
					<?php else: ?>
						<tr>
							<td colspan="5">No regular users yet</td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</section>

	<section class="db2-panel">
		<div class="db2-panel-head">
			<div>
				<h3 class="db2-panel-title">
					<span class="db2-panel-icon"><i class="fa-solid fa-trophy"></i></span>
					Winner Leaderboard
				</h3>
				<p class="db2-panel-sub">Only users with actual winning payouts are shown here.</p>
			</div>
		</div>
		<div class="db2-win-list">
			<?php if (!empty($winner_leaderboard)): ?>
				<?php foreach ($winner_leaderboard as $index => $winner): ?>
					<div class="db2-win-card">
						<div class="db2-win-rank"><?php echo $index + 1; ?></div>
						<div class="db2-win-user">
							<h4><?php echo html_escape($winner->name); ?></h4>
							<p><?php echo (int) $winner->total_wins; ?> winning markets from <?php echo (int) $winner->total_trades; ?> trades</p>
							<small><?php echo html_escape($winner->email); ?></small>
						</div>
						<div class="db2-win-value">
							<strong>Rs <?php echo number_format((float) $winner->total_payout, 2); ?></strong>
							<span>Total payout</span>
						</div>
					</div>
				<?php endforeach; ?>
			<?php else: ?>
				<div style="padding:18px;border-radius:18px;background:#f8fafc;border:1px dashed #cbd5e1;color:#64748b;">No winners yet. The leaderboard will fill after markets are resolved with payouts.</div>
			<?php endif; ?>
		</div>
	</section>
</div>
