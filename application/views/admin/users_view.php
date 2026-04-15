<?php
$total_users = isset($total_users) ? (int) $total_users : (!empty($users) ? count($users) : 0);
$users_with_email = 0;
$users_with_mobile = 0;
$users_with_address = 0;

if (!empty($users)) {
	foreach ($users as $user_item) {
		if (trim((string) $user_item->email) !== '') $users_with_email++;
		if (trim((string) $user_item->mobile) !== '') $users_with_mobile++;
		if (trim((string) $user_item->address) !== '') $users_with_address++;
	}
}

$email_pct   = $total_users > 0 ? round(($users_with_email / $total_users) * 100) : 0;
$mobile_pct  = $total_users > 0 ? round(($users_with_mobile / $total_users) * 100) : 0;
$address_pct = $total_users > 0 ? round(($users_with_address / $total_users) * 100) : 0;

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
?>

<?php if ($this->session->flashdata('error')): ?>
	<div class="adm2-toast adm2-toast--err" role="alert">
		<svg viewBox="0 0 20 20" fill="currentColor">
			<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
		</svg>
		<span><?php echo $this->session->flashdata('error'); ?></span>
		<button class="adm2-toast__x" onclick="this.parentElement.remove()" aria-label="Dismiss">&times;</button>
	</div>
<?php endif; ?>

<?php if ($this->session->flashdata('success')): ?>
	<div class="adm2-toast adm2-toast--ok" role="alert">
		<svg viewBox="0 0 20 20" fill="currentColor">
			<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
		</svg>
		<span><?php echo $this->session->flashdata('success'); ?></span>
		<button class="adm2-toast__x" onclick="this.parentElement.remove()" aria-label="Dismiss">&times;</button>
	</div>
<?php endif; ?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">

<div class="adm2">

	<!-- ── HEADER BANNER ── -->
	<header class="adm2-banner">
		<div>
			<div class="adm2-banner__eyebrow"><span class="adm2-banner__dot"></span> Admin panel</div>
			<h1 class="adm2-banner__title">User Directory</h1>
			<p class="adm2-banner__sub">Manage registered users, audit contact completeness, and act fast — all from one clean interface.</p>
		</div>
		<div class="adm2-kpis">
			<div class="adm2-kpi adm2-kpi--main">
				<span class="adm2-kpi__label">Total users</span>
				<strong class="adm2-kpi__val" id="visibleCount"><?php echo $total_users; ?></strong>
			</div>
			<div class="adm2-kpi">
				<span class="adm2-kpi__label">With mobile</span>
				<strong class="adm2-kpi__val"><?php echo $users_with_mobile; ?></strong>
			</div>
			<div class="adm2-kpi">
				<span class="adm2-kpi__label">With address</span>
				<strong class="adm2-kpi__val"><?php echo $users_with_address; ?></strong>
			</div>
		</div>
	</header>

	<!-- ── COVERAGE CARDS ── -->
	<section class="adm2-coverage" aria-label="Profile coverage statistics">
		<div class="adm2-cov">
			<div class="adm2-cov__top">
				<div class="adm2-cov__icon adm2-cov__icon--e">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
						<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
						<polyline points="22,6 12,13 2,6" />
					</svg>
				</div>
				<div class="adm2-cov__right">
					<span class="adm2-cov__num"><?php echo $users_with_email; ?></span>
					<span class="adm2-cov__pct"><?php echo $email_pct; ?>% coverage</span>
				</div>
			</div>
			<p class="adm2-cov__label">Email addresses</p>
			<div class="adm2-bar">
				<div class="adm2-bar__fill" style="width:<?php echo $email_pct; ?>%"></div>
			</div>
		</div>

		<div class="adm2-cov">
			<div class="adm2-cov__top">
				<div class="adm2-cov__icon adm2-cov__icon--m">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
						<rect x="5" y="2" width="14" height="20" rx="2" />
						<line x1="12" y1="18" x2="12.01" y2="18" />
					</svg>
				</div>
				<div class="adm2-cov__right">
					<span class="adm2-cov__num"><?php echo $users_with_mobile; ?></span>
					<span class="adm2-cov__pct"><?php echo $mobile_pct; ?>% coverage</span>
				</div>
			</div>
			<p class="adm2-cov__label">Mobile numbers</p>
			<div class="adm2-bar">
				<div class="adm2-bar__fill" style="width:<?php echo $mobile_pct; ?>%"></div>
			</div>
		</div>

		<div class="adm2-cov">
			<div class="adm2-cov__top">
				<div class="adm2-cov__icon adm2-cov__icon--a">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
						<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z" />
						<circle cx="12" cy="10" r="3" />
					</svg>
				</div>
				<div class="adm2-cov__right">
					<span class="adm2-cov__num"><?php echo $users_with_address; ?></span>
					<span class="adm2-cov__pct"><?php echo $address_pct; ?>% coverage</span>
				</div>
			</div>
			<p class="adm2-cov__label">Addresses filled</p>
			<div class="adm2-bar">
				<div class="adm2-bar__fill" style="width:<?php echo $address_pct; ?>%"></div>
			</div>
		</div>
	</section>

	<!-- ── TABLE PANEL ── -->
	<section class="adm2-panel">
		<div class="adm2-panel__head">
			<div>
				<h2 class="adm2-panel__title">All users</h2>
				<p class="adm2-panel__desc">Search, sort and manage every registered account.</p>
			</div>
			<div class="adm2-controls">
				<div class="adm2-srch-w">
					<svg class="adm2-srch-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<circle cx="11" cy="11" r="8" />
						<line x1="21" y1="21" x2="16.65" y2="16.65" />
					</svg>
					<input type="text" id="searchUsers" class="adm2-inp adm2-inp--s" placeholder="Name, email, mobile…" aria-label="Search users">
					<button class="adm2-srch-x" id="searchClear" hidden aria-label="Clear search">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
							<line x1="18" y1="6" x2="6" y2="18" />
							<line x1="6" y1="6" x2="18" y2="18" />
						</svg>
					</button>
				</div>
				<div class="adm2-sel-w">
					<svg class="adm2-sel-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<line x1="4" y1="6" x2="20" y2="6" />
						<line x1="4" y1="12" x2="14" y2="12" />
						<line x1="4" y1="18" x2="10" y2="18" />
					</svg>
					<select id="sortUsers" class="adm2-inp adm2-inp--sel" aria-label="Sort users">
						<option value="newest">Newest first</option>
						<option value="oldest">Oldest first</option>
						<option value="name">Name A → Z</option>
					</select>
				</div>
			</div>
		</div>

		<div class="adm2-scroll">
			<table class="adm2-table" role="grid" aria-label="User directory">
				<thead>
					<tr>
						<th scope="col" class="adm2-th adm2-th--sno">#</th>
						<th scope="col" class="adm2-th">User</th>
						<th scope="col" class="adm2-th">Mobile</th>
						<th scope="col" class="adm2-th">Email</th>
						<th scope="col" class="adm2-th">Address</th>
						<th scope="col" class="adm2-th adm2-th--act">Actions</th>
					</tr>
				</thead>
				<tbody id="usersTableBody">
					<?php if (!empty($users)): ?>
						<?php foreach ($users as $index => $user_item): ?>
							<?php
							$display_name    = trim((string) $user_item->name);
							$display_mobile  = trim((string) $user_item->mobile);
							$display_email   = trim((string) $user_item->email);
							$display_address = trim((string) $user_item->address);

							$initials = '';
							$name_parts = array_slice(array_filter(explode(' ', $display_name ?: 'U')), 0, 2);
							foreach ($name_parts as $p) $initials .= strtoupper(substr($p, 0, 1));
							if ($initials === '') $initials = 'U';

							$pal = $avatar_palettes[$user_item->id % count($avatar_palettes)];
							?>
							<tr class="adm2-row"
								data-id="<?php echo (int) $user_item->id; ?>"
								data-name="<?php echo html_escape(strtolower($display_name)); ?>"
								data-mobile="<?php echo html_escape(strtolower($display_mobile)); ?>"
								data-email="<?php echo html_escape(strtolower($display_email)); ?>"
								data-address="<?php echo html_escape(strtolower($display_address)); ?>">

								<td class="adm2-td adm2-td--sno">
									<span class="adm2-sno"><?php echo $index + 1; ?></span>
								</td>

								<td class="adm2-td">
									<div class="adm2-user">
										<div class="adm2-avatar" style="background:linear-gradient(135deg,<?php echo $pal[0]; ?>,<?php echo $pal[1]; ?>)">
											<?php echo html_escape($initials); ?>
										</div>
										<div>
											<span class="adm2-user__name"><?php echo html_escape($display_name ?: 'Unknown user'); ?></span>
											<span class="adm2-user__meta">Regular account</span>
										</div>
									</div>
								</td>

								<td class="adm2-td">
									<?php if ($display_mobile): ?>
										<span class="adm2-field adm2-field--e">
											<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
												<rect x="5" y="2" width="14" height="20" rx="2" />
												<line x1="12" y1="18" x2="12.01" y2="18" />
											</svg>
											<?php echo html_escape($display_mobile); ?>
										</span>
									<?php else: ?><span class="adm2-field adm2-field--nil">—</span><?php endif; ?>
								</td>

								<td class="adm2-td">
									<?php if ($display_email): ?>
										<span class="adm2-field adm2-field--e">
											<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
												<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
												<polyline points="22,6 12,13 2,6" />
											</svg>
											<?php echo html_escape($display_email); ?>
										</span>
									<?php else: ?><span class="adm2-field adm2-field--nil">—</span><?php endif; ?>
								</td>

								<td class="adm2-td adm2-td--addr">
									<?php if ($display_address): ?>
										<span class="adm2-field adm2-field--e adm2-field--addr" title="<?php echo html_escape($display_address); ?>">
											<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
												<path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0118 0z" />
												<circle cx="12" cy="10" r="3" />
											</svg>
											<?php echo html_escape($display_address); ?>
										</span>
									<?php else: ?><span class="adm2-field adm2-field--nil">—</span><?php endif; ?>
								</td>

								<td class="adm2-td adm2-td--act">
									<div class="adm2-acts">
										<a class="adm2-act" href="<?php echo site_url('admin/users/view/' . (int) $user_item->id); ?>" title="View profile">
											<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
												<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
												<circle cx="12" cy="12" r="3" />
											</svg>
											<span>View</span>
										</a>
										<a class="adm2-act" href="<?php echo site_url('admin/users/edit/' . (int) $user_item->id); ?>" title="Edit user">
											<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
												<path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" />
												<path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
											</svg>
											<span>Edit</span>
										</a>
										<a class="adm2-act adm2-act--del" href="<?php echo site_url('admin/users/delete/' . (int) $user_item->id); ?>" title="Delete user" onclick="return confirm('Permanently delete this user?')">
											<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
												<polyline points="3 6 5 6 21 6" />
												<path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6" />
												<path d="M10 11v6M14 11v6" />
												<path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2" />
											</svg>
											<span>Delete</span>
										</a>
									</div>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php else: ?>
						<tr id="admEmptyRow">
							<td colspan="6">
								<div class="adm2-empty">
									<div class="adm2-empty__icon">
										<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4">
											<path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
											<circle cx="9" cy="7" r="4" />
											<path d="M23 21v-2a4 4 0 00-3-3.87" />
											<path d="M16 3.13a4 4 0 010 7.75" />
										</svg>
									</div>
									<h3>No users yet</h3>
									<p>No regular user accounts have been registered.</p>
								</div>
							</td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>

		<div class="adm2-empty adm2-empty--filtered" id="filteredEmpty" hidden>
			<div class="adm2-empty__icon">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4">
					<circle cx="11" cy="11" r="8" />
					<line x1="21" y1="21" x2="16.65" y2="16.65" />
				</svg>
			</div>
			<h3>No results</h3>
			<p>No users match your search. Try a different term.</p>
			<button class="adm2-clr-btn" id="clearSearchBtn">Clear search</button>
		</div>

		<footer class="adm2-foot">
			<span class="adm2-foot-info" id="admFooterInfo">
				Showing all <strong><?php echo $total_users; ?></strong> users
			</span>
			<span class="adm2-badge">Regular users only</span>
		</footer>
	</section>

</div><!-- /.adm2 -->

<style>
	/* ─────────────────────────────────────────
   IMPORTS & RESET
───────────────────────────────────────── */
	*,
	*::before,
	*::after {
		box-sizing: border-box;
		margin: 0;
		padding: 0;
	}

	/* ─────────────────────────────────────────
   DESIGN TOKENS
───────────────────────────────────────── */
	:root {
		--f-body: 'Inter', system-ui, sans-serif;
		--f-display: 'Instrument Serif', Georgia, serif;

		/* Neutrals */
		--c-ink: #111827;
		--c-ink-2: #374151;
		--c-ink-3: #6b7280;
		--c-ink-4: #9ca3af;
		--c-line: #e5e7eb;
		--c-line-2: #f3f4f6;
		--c-surface: #ffffff;
		--c-base: #f9fafb;

		/* Semantic */
		--c-red: #a32d2d;
		--c-red-lt: #fcebeb;
		--c-red-bd: #f7c1c1;
		--c-green: #22c55e;
		--c-green-lt: #eaf3de;
		--c-green-bd: #c0dd97;
		--c-green-tx: #27500a;

		/* Shadows */
		--sh-xs: 0 1px 2px rgba(0, 0, 0, .05);
		--sh-sm: 0 2px 8px rgba(0, 0, 0, .06);

		/* Radii */
		--r-sm: 8px;
		--r-md: 12px;
		--r-lg: 16px;
	}

	body {
		font-family: var(--f-body);
		background: var(--c-base);
		color: var(--c-ink);
	}

	/* ─────────────────────────────────────────
   ROOT
───────────────────────────────────────── */
	.adm2 {
		padding: 24px 0 48px;
		display: flex;
		flex-direction: column;
		gap: 14px;
	}

	/* ─────────────────────────────────────────
   TOASTS
───────────────────────────────────────── */
	.adm2-toast {
		display: flex;
		align-items: center;
		gap: 10px;
		padding: 12px 16px;
		border-radius: var(--r-md);
		font-size: 13.5px;
		font-weight: 500;
		border: 1px solid transparent;
		animation: toastIn .3s cubic-bezier(.34, 1.56, .64, 1) both;
	}

	.adm2-toast svg {
		width: 16px;
		height: 16px;
		flex-shrink: 0;
	}

	.adm2-toast--err {
		background: var(--c-red-lt);
		border-color: var(--c-red-bd);
		color: #791f1f;
	}

	.adm2-toast--ok {
		background: var(--c-green-lt);
		border-color: var(--c-green-bd);
		color: var(--c-green-tx);
	}

	.adm2-toast__x {
		margin-left: auto;
		border: none;
		background: none;
		cursor: pointer;
		font-size: 18px;
		line-height: 1;
		color: inherit;
		opacity: .5;
	}

	.adm2-toast__x:hover {
		opacity: 1;
	}

	@keyframes toastIn {
		from {
			opacity: 0;
			transform: translateY(-10px);
		}

		to {
			opacity: 1;
			transform: none;
		}
	}

	/* ─────────────────────────────────────────
   BANNER
───────────────────────────────────────── */
	.adm2-banner {
		background: var(--c-surface);
		border: 1px solid var(--c-line);
		border-radius: var(--r-lg);
		padding: 32px 36px;
		display: flex;
		justify-content: space-between;
		align-items: flex-end;
		gap: 24px;
		flex-wrap: wrap;
		box-shadow: var(--sh-xs);
	}

	.adm2-banner__eyebrow {
		display: inline-flex;
		align-items: center;
		gap: 7px;
		font-size: 11px;
		font-weight: 500;
		letter-spacing: .1em;
		text-transform: uppercase;
		color: var(--c-ink-3);
		margin-bottom: 10px;
	}

	.adm2-banner__dot {
		width: 6px;
		height: 6px;
		border-radius: 50%;
		background: var(--c-green);
	}

	.adm2-banner__title {
		font-family: var(--f-display);
		font-size: clamp(28px, 4vw, 44px);
		font-weight: 400;
		font-style: italic;
		color: var(--c-ink);
		line-height: 1.1;
		margin-bottom: 8px;
	}

	.adm2-banner__sub {
		font-size: 13.5px;
		color: var(--c-ink-3);
		line-height: 1.65;
		max-width: 420px;
	}

	/* KPIs */
	.adm2-kpis {
		display: flex;
		gap: 10px;
		flex-wrap: wrap;
		flex-shrink: 0;
	}

	.adm2-kpi {
		min-width: 110px;
		padding: 16px 20px;
		border-radius: var(--r-md);
		border: 1px solid var(--c-line);
		background: var(--c-base);
		text-align: center;
	}

	.adm2-kpi--main {
		background: var(--c-surface);
		border-color: var(--c-ink);
	}

	.adm2-kpi__label {
		display: block;
		font-size: 10.5px;
		font-weight: 500;
		letter-spacing: .09em;
		text-transform: uppercase;
		color: var(--c-ink-4);
		margin-bottom: 7px;
	}

	.adm2-kpi__val {
		display: block;
		font-family: var(--f-display);
		font-size: 30px;
		font-style: italic;
		font-weight: 400;
		color: var(--c-ink);
		line-height: 1;
	}

	/* ─────────────────────────────────────────
   COVERAGE CARDS
───────────────────────────────────────── */
	.adm2-coverage {
		display: grid;
		grid-template-columns: repeat(3, 1fr);
		gap: 12px;
	}

	.adm2-cov {
		background: var(--c-surface);
		border: 1px solid var(--c-line);
		border-radius: var(--r-lg);
		padding: 20px 22px;
		box-shadow: var(--sh-xs);
		transition: box-shadow .18s, transform .18s;
	}

	.adm2-cov:hover {
		box-shadow: var(--sh-sm);
		transform: translateY(-1px);
	}

	.adm2-cov__top {
		display: flex;
		align-items: center;
		justify-content: space-between;
		margin-bottom: 12px;
	}

	.adm2-cov__icon {
		width: 38px;
		height: 38px;
		border-radius: var(--r-sm);
		display: flex;
		align-items: center;
		justify-content: center;
		background: var(--c-base);
		color: var(--c-ink-3);
		border: 1px solid var(--c-line);
	}

	.adm2-cov__icon svg {
		width: 17px;
		height: 17px;
	}

	.adm2-cov__right {
		text-align: right;
	}

	.adm2-cov__num {
		display: block;
		font-family: var(--f-display);
		font-size: 26px;
		font-style: italic;
		color: var(--c-ink);
		line-height: 1;
	}

	.adm2-cov__pct {
		font-size: 12px;
		color: var(--c-ink-4);
		font-weight: 400;
	}

	.adm2-cov__label {
		font-size: 12.5px;
		font-weight: 500;
		color: var(--c-ink-3);
		margin-bottom: 10px;
	}

	.adm2-bar {
		height: 3px;
		background: var(--c-line-2);
		border-radius: 999px;
		overflow: hidden;
	}

	.adm2-bar__fill {
		height: 100%;
		border-radius: 999px;
		background: var(--c-ink);
		transition: width .5s cubic-bezier(.34, 1.2, .64, 1);
	}

	/* ─────────────────────────────────────────
   TABLE PANEL
───────────────────────────────────────── */
	.adm2-panel {
		background: var(--c-surface);
		border: 1px solid var(--c-line);
		border-radius: var(--r-lg);
		overflow: hidden;
		box-shadow: var(--sh-xs);
	}

	.adm2-panel__head {
		display: flex;
		justify-content: space-between;
		align-items: flex-end;
		gap: 16px;
		flex-wrap: wrap;
		padding: 26px 30px 22px;
		border-bottom: 1px solid var(--c-line);
		background: var(--c-base);
	}

	.adm2-panel__title {
		font-family: var(--f-display);
		font-size: 22px;
		font-style: italic;
		font-weight: 400;
		color: var(--c-ink);
		margin-bottom: 4px;
	}

	.adm2-panel__desc {
		font-size: 13px;
		color: var(--c-ink-4);
	}

	/* Controls */
	.adm2-controls {
		display: flex;
		gap: 8px;
		align-items: center;
		flex-wrap: wrap;
	}

	.adm2-srch-w {
		position: relative;
	}

	.adm2-srch-ico {
		position: absolute;
		top: 50%;
		left: 12px;
		transform: translateY(-50%);
		width: 14px;
		height: 14px;
		color: var(--c-ink-4);
		pointer-events: none;
	}

	.adm2-srch-x {
		position: absolute;
		top: 50%;
		right: 10px;
		transform: translateY(-50%);
		border: none;
		background: none;
		cursor: pointer;
		color: var(--c-ink-4);
		display: flex;
		padding: 3px;
		border-radius: 4px;
		transition: color .12s;
	}

	.adm2-srch-x svg {
		width: 12px;
		height: 12px;
	}

	.adm2-srch-x:hover {
		color: var(--c-red);
	}

	.adm2-inp {
		height: 38px;
		border: 1px solid var(--c-line);
		border-radius: var(--r-sm);
		background: var(--c-surface);
		color: var(--c-ink);
		font-family: var(--f-body);
		font-size: 13px;
		transition: border-color .15s, box-shadow .15s;
		appearance: none;
		-webkit-appearance: none;
	}

	.adm2-inp:focus {
		outline: none;
		border-color: var(--c-ink-3);
		box-shadow: 0 0 0 3px rgba(17, 24, 39, .07);
	}

	.adm2-inp--s {
		padding: 0 32px 0 36px;
		width: 260px;
	}

	.adm2-inp--sel {
		padding: 0 32px 0 34px;
		width: 165px;
		cursor: pointer;
	}

	.adm2-sel-w {
		position: relative;
	}

	.adm2-sel-ico {
		position: absolute;
		top: 50%;
		left: 11px;
		transform: translateY(-50%);
		width: 14px;
		height: 14px;
		color: var(--c-ink-4);
		pointer-events: none;
	}

	.adm2-sel-w::after {
		content: '';
		position: absolute;
		top: 50%;
		right: 12px;
		transform: translateY(-50%);
		width: 0;
		height: 0;
		border-left: 4px solid transparent;
		border-right: 4px solid transparent;
		border-top: 5px solid var(--c-ink-4);
		pointer-events: none;
	}

	/* ─────────────────────────────────────────
   TABLE
───────────────────────────────────────── */
	.adm2-scroll {
		overflow-x: auto;
		padding: 10px 16px 4px;
	}

	.adm2-table {
		width: 100%;
		min-width: 880px;
		border-collapse: separate;
		border-spacing: 0 4px;
	}

	.adm2-th {
		padding: 0 14px 8px;
		font-size: 10.5px;
		font-weight: 500;
		letter-spacing: .1em;
		text-transform: uppercase;
		color: var(--c-ink-4);
		text-align: left;
		white-space: nowrap;
	}

	.adm2-th--sno {
		width: 52px;
	}

	.adm2-th--act {
		text-align: right;
		width: 220px;
	}

	.adm2-td {
		padding: 13px 14px;
		background: var(--c-base);
		border-top: 1px solid var(--c-line);
		border-bottom: 1px solid var(--c-line);
		vertical-align: middle;
		font-size: 13.5px;
		color: var(--c-ink-2);
		transition: background .12s;
	}

	.adm2-td:first-child {
		border-left: 1px solid var(--c-line);
		border-radius: var(--r-sm) 0 0 var(--r-sm);
	}

	.adm2-td:last-child {
		border-right: 1px solid var(--c-line);
		border-radius: 0 var(--r-sm) var(--r-sm) 0;
	}

	.adm2-row:hover .adm2-td {
		background: var(--c-surface);
	}

	.adm2-td--sno {
		width: 52px;
	}

	.adm2-td--addr {
		max-width: 190px;
	}

	.adm2-td--act {
		text-align: right;
	}

	/* Serial number */
	.adm2-sno {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		width: 30px;
		height: 30px;
		border-radius: var(--r-sm);
		background: var(--c-surface);
		border: 1px solid var(--c-line);
		font-size: 12px;
		font-weight: 500;
		color: var(--c-ink-4);
	}

	/* Avatar & User */
	.adm2-user {
		display: flex;
		align-items: center;
		gap: 11px;
	}

	.adm2-avatar {
		width: 40px;
		height: 40px;
		border-radius: var(--r-sm);
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 14px;
		font-weight: 500;
		color: #fff;
		flex-shrink: 0;
	}

	.adm2-user__name {
		display: block;
		font-size: 14px;
		font-weight: 500;
		color: var(--c-ink);
	}

	.adm2-user__meta {
		display: block;
		font-size: 11.5px;
		color: var(--c-ink-4);
		margin-top: 2px;
	}

	/* Fields */
	.adm2-field {
		display: inline-flex;
		align-items: center;
		gap: 5px;
		font-size: 13px;
	}

	.adm2-field svg {
		width: 13px;
		height: 13px;
		flex-shrink: 0;
		color: var(--c-ink-4);
	}

	.adm2-field--e {
		color: var(--c-ink-2);
	}

	.adm2-field--nil {
		color: var(--c-ink-4);
		font-style: italic;
	}

	.adm2-field--addr {
		max-width: 180px;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
		display: inline-flex;
	}

	/* Action buttons */
	.adm2-acts {
		display: flex;
		justify-content: flex-end;
		gap: 5px;
	}

	.adm2-act {
		display: inline-flex;
		align-items: center;
		gap: 4px;
		padding: 6px 13px;
		border-radius: var(--r-sm);
		font-size: 12px;
		font-weight: 500;
		text-decoration: none;
		border: 1px solid var(--c-line);
		background: var(--c-surface);
		color: var(--c-ink-2);
		cursor: pointer;
		transition: background .12s, border-color .12s, transform .12s;
	}

	.adm2-act svg {
		width: 12px;
		height: 12px;
	}

	.adm2-act:hover {
		background: var(--c-base);
		border-color: var(--c-ink-4);
		transform: translateY(-1px);
	}

	.adm2-act:active {
		transform: none;
	}

	.adm2-act--del:hover {
		color: var(--c-red);
		border-color: var(--c-red-bd);
		background: var(--c-red-lt);
	}

	/* ─────────────────────────────────────────
   EMPTY STATES
───────────────────────────────────────── */
	.adm2-empty {
		padding: 48px 24px;
		text-align: center;
	}

	.adm2-empty__icon {
		width: 52px;
		height: 52px;
		margin: 0 auto 14px;
		border-radius: var(--r-md);
		display: flex;
		align-items: center;
		justify-content: center;
		background: var(--c-base);
		border: 1px solid var(--c-line);
		color: var(--c-ink-4);
	}

	.adm2-empty__icon svg {
		width: 24px;
		height: 24px;
	}

	.adm2-empty h3 {
		font-family: var(--f-display);
		font-size: 20px;
		font-style: italic;
		font-weight: 400;
		color: var(--c-ink);
		margin-bottom: 6px;
	}

	.adm2-empty p {
		font-size: 13px;
		color: var(--c-ink-4);
	}

	.adm2-empty--filtered {
		border-top: 1px solid var(--c-line);
	}

	.adm2-clr-btn {
		margin-top: 14px;
		padding: 8px 20px;
		border-radius: var(--r-sm);
		border: 1px solid var(--c-line);
		background: var(--c-surface);
		color: var(--c-ink-2);
		font-family: var(--f-body);
		font-size: 13px;
		font-weight: 500;
		cursor: pointer;
		transition: background .12s, border-color .12s;
	}

	.adm2-clr-btn:hover {
		background: var(--c-base);
		border-color: var(--c-ink-4);
	}

	/* ─────────────────────────────────────────
   FOOTER
───────────────────────────────────────── */
	.adm2-foot {
		display: flex;
		justify-content: space-between;
		align-items: center;
		flex-wrap: wrap;
		gap: 12px;
		padding: 16px 30px 20px;
		border-top: 1px solid var(--c-line);
		background: var(--c-base);
	}

	.adm2-foot-info {
		font-size: 13px;
		color: var(--c-ink-4);
	}

	.adm2-foot-info strong {
		color: var(--c-ink-3);
		font-weight: 500;
	}

	.adm2-badge {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		padding: 5px 12px;
		border-radius: 999px;
		background: var(--c-surface);
		border: 1px solid var(--c-line);
		color: var(--c-ink-4);
		font-size: 11px;
		font-weight: 500;
		letter-spacing: .04em;
	}

	.adm2-badge::before {
		content: '';
		width: 5px;
		height: 5px;
		border-radius: 50%;
		background: var(--c-green);
	}

	/* ─────────────────────────────────────────
   RESPONSIVE
───────────────────────────────────────── */
	@media (max-width: 900px) {
		.adm2-coverage {
			grid-template-columns: 1fr;
		}

		.adm2-panel__head {
			flex-direction: column;
			align-items: stretch;
			padding: 20px;
		}

		.adm2-controls {
			flex-direction: column;
		}

		.adm2-inp--s,
		.adm2-inp--sel,
		.adm2-srch-w,
		.adm2-sel-w {
			width: 100%;
		}
	}

	@media (max-width: 700px) {
		.adm2-banner {
			flex-direction: column;
			align-items: flex-start;
			padding: 24px;
		}

		.adm2-kpis {
			width: 100%;
		}

		.adm2-kpi {
			flex: 1;
		}
	}

	@media (max-width: 560px) {
		.adm2-banner__title {
			font-size: 28px;
		}

		.adm2-kpi__val {
			font-size: 24px;
		}

		.adm2-panel__title {
			font-size: 20px;
		}

		.adm2-act span {
			display: none;
		}

		.adm2-act {
			padding: 7px 9px;
		}

		.adm2-act svg {
			width: 14px;
			height: 14px;
		}
	}
</style>

<script>
	(function() {
		'use strict';

		var si = document.getElementById('searchUsers');
		var ss = document.getElementById('sortUsers');
		var sx = document.getElementById('searchClear');
		var cb = document.getElementById('clearSearchBtn');
		var tb = document.getElementById('usersTableBody');
		var vc = document.getElementById('visibleCount');
		var fi = document.getElementById('admFooterInfo');
		var fe = document.getElementById('filteredEmpty');

		if (!tb) return;

		function rows() {
			return Array.prototype.slice.call(tb.querySelectorAll('.adm2-row'));
		}

		function reindex(vis) {
			var n = 0;
			vis.forEach(function(r) {
				n++;
				var s = r.querySelector('.adm2-sno');
				if (s) s.textContent = n;
			});
			return n;
		}

		function filter() {
			var term = si ? si.value.toLowerCase().trim() : '';
			var all = rows();
			var vis = [];

			all.forEach(function(r) {
				var match = !term ||
					(r.dataset.name || '').indexOf(term) > -1 ||
					(r.dataset.mobile || '').indexOf(term) > -1 ||
					(r.dataset.email || '').indexOf(term) > -1 ||
					(r.dataset.address || '').indexOf(term) > -1;
				r.style.display = match ? '' : 'none';
				if (match) vis.push(r);
			});

			var n = reindex(vis);
			if (vc) vc.textContent = n;
			if (fe) fe.hidden = n > 0;
			if (sx) sx.hidden = !term;
			if (fi) fi.innerHTML = term ?
				'Showing <strong>' + n + '</strong> of <strong>' + all.length + '</strong> users' :
				'Showing all <strong>' + all.length + '</strong> users';
		}

		function sort() {
			var all = rows();
			var order = ss ? ss.value : 'newest';
			all.sort(function(a, b) {
				if (order === 'oldest') return parseInt(a.dataset.id, 10) - parseInt(b.dataset.id, 10);
				if (order === 'name') return (a.dataset.name || '').localeCompare(b.dataset.name || '');
				return parseInt(b.dataset.id, 10) - parseInt(a.dataset.id, 10);
			});
			all.forEach(function(r) {
				tb.appendChild(r);
			});
			filter();
		}

		function clearSearch() {
			if (si) {
				si.value = '';
				si.focus();
			}
			filter();
		}

		if (si) si.addEventListener('input', filter);
		if (ss) ss.addEventListener('change', sort);
		if (sx) sx.addEventListener('click', clearSearch);
		if (cb) cb.addEventListener('click', clearSearch);

		sort(); /* initial render */

		/* Auto-dismiss toasts */
		Array.prototype.slice.call(document.querySelectorAll('.adm2-toast')).forEach(function(el) {
			setTimeout(function() {
				el.style.transition = 'opacity .4s ease, transform .4s ease';
				el.style.opacity = '0';
				el.style.transform = 'translateY(-6px)';
				setTimeout(function() {
					el.remove();
				}, 430);
			}, 4500);
		});
	}());
</script>