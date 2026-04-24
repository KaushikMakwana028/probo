<?php
$total_users = isset($total_users) ? (int) $total_users : (!empty($users) ? count($users) : 0);
$users_with_email = 0;
$users_with_mobile = 0;
$users_with_address = 0;

if (!empty($users)) {
	foreach ($users as $user_item) {
		if (trim((string) $user_item->email)   !== '') $users_with_email++;
		if (trim((string) $user_item->mobile)  !== '') $users_with_mobile++;
		if (trim((string) $user_item->address) !== '') $users_with_address++;
	}
}

$email_pct   = $total_users > 0 ? round(($users_with_email   / $total_users) * 100) : 0;
$mobile_pct  = $total_users > 0 ? round(($users_with_mobile  / $total_users) * 100) : 0;
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
	<div class="ud-toast ud-toast--err" role="alert">
		<svg width="15" height="15" viewBox="0 0 20 20" fill="currentColor">
			<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
		</svg>
		<span><?php echo $this->session->flashdata('error'); ?></span>
		<button class="ud-toast__x" onclick="this.parentElement.remove()">×</button>
	</div>
<?php endif; ?>
<?php if ($this->session->flashdata('success')): ?>
	<div class="ud-toast ud-toast--ok" role="alert">
		<svg width="15" height="15" viewBox="0 0 20 20" fill="currentColor">
			<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
		</svg>
		<span><?php echo $this->session->flashdata('success'); ?></span>
		<button class="ud-toast__x" onclick="this.parentElement.remove()">×</button>
	</div>
<?php endif; ?>

<link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,400;0,500;0,600;1,400&family=Instrument+Serif:ital@1&display=swap" rel="stylesheet">

<div class="ud">

	<!-- Banner -->
	<header class="ud-banner">
		<div class="ud-banner__left">
			<div class="ud-banner__eyebrow"><span class="ud-dot"></span> Admin panel</div>
			<h1 class="ud-banner__title">User Directory</h1>
			<p class="ud-banner__sub">Manage registered users, audit contact completeness, and act fast — all from one clean interface.</p>
		</div>
		<div class="ud-kpis">
			<div class="ud-kpi ud-kpi--main">
				<span class="ud-kpi__label">Total users</span>
				<strong class="ud-kpi__val" id="visibleCount"><?php echo $total_users; ?></strong>
			</div>
			<div class="ud-kpi">
				<span class="ud-kpi__label">With mobile</span>
				<strong class="ud-kpi__val"><?php echo $users_with_mobile; ?></strong>
			</div>
			<div class="ud-kpi">
				<span class="ud-kpi__label">With address</span>
				<strong class="ud-kpi__val"><?php echo $users_with_address; ?></strong>
			</div>
		</div>
	</header>

	<!-- Coverage Cards -->
	<section class="ud-coverage">
		<div class="ud-cov">
			<div class="ud-cov__top">
				<div class="ud-cov__icon ud-cov__icon--e">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
						<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
						<polyline points="22,6 12,13 2,6" />
					</svg>
				</div>
				<div class="ud-cov__right">
					<span class="ud-cov__num"><?php echo $users_with_email; ?></span>
					<span class="ud-cov__pct"><?php echo $email_pct; ?>% coverage</span>
				</div>
			</div>
			<p class="ud-cov__label">Email addresses</p>
			<div class="ud-bar">
				<div class="ud-bar__fill" style="width:<?php echo $email_pct; ?>%"></div>
			</div>
		</div>
		<div class="ud-cov">
			<div class="ud-cov__top">
				<div class="ud-cov__icon ud-cov__icon--m">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
						<rect x="5" y="2" width="14" height="20" rx="2" />
						<line x1="12" y1="18" x2="12.01" y2="18" />
					</svg>
				</div>
				<div class="ud-cov__right">
					<span class="ud-cov__num"><?php echo $users_with_mobile; ?></span>
					<span class="ud-cov__pct"><?php echo $mobile_pct; ?>% coverage</span>
				</div>
			</div>
			<p class="ud-cov__label">Mobile numbers</p>
			<div class="ud-bar">
				<div class="ud-bar__fill" style="width:<?php echo $mobile_pct; ?>%"></div>
			</div>
		</div>
		<div class="ud-cov">
			<div class="ud-cov__top">
				<div class="ud-cov__icon ud-cov__icon--a">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
						<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z" />
						<circle cx="12" cy="10" r="3" />
					</svg>
				</div>
				<div class="ud-cov__right">
					<span class="ud-cov__num"><?php echo $users_with_address; ?></span>
					<span class="ud-cov__pct"><?php echo $address_pct; ?>% coverage</span>
				</div>
			</div>
			<p class="ud-cov__label">Addresses filled</p>
			<div class="ud-bar">
				<div class="ud-bar__fill" style="width:<?php echo $address_pct; ?>%"></div>
			</div>
		</div>
	</section>

	<!-- Table Panel -->
	<section class="ud-panel">
		<div class="ud-panel__head">
			<div class="ud-panel__head-text">
				<h2 class="ud-panel__title">All users</h2>
				<p class="ud-panel__sub">Search, sort and manage every registered account.</p>
			</div>
			<div class="ud-controls">
				<div class="ud-search-wrap">
					<svg class="ud-search-ico" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<circle cx="11" cy="11" r="8" />
						<line x1="21" y1="21" x2="16.65" y2="16.65" />
					</svg>
					<input type="text" id="searchUsers" class="ud-input ud-input--search" placeholder="Name, email, mobile…" autocomplete="off">
					<button class="ud-search-clear" id="searchClear" hidden>
						<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
							<line x1="18" y1="6" x2="6" y2="18" />
							<line x1="6" y1="6" x2="18" y2="18" />
						</svg>
					</button>
				</div>
				<div class="ud-select-wrap">
					<svg class="ud-select-ico" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<line x1="4" y1="6" x2="20" y2="6" />
						<line x1="4" y1="12" x2="14" y2="12" />
						<line x1="4" y1="18" x2="10" y2="18" />
					</svg>
					<select id="sortUsers" class="ud-input ud-input--select">
						<option value="newest">Newest first</option>
						<option value="oldest">Oldest first</option>
						<option value="name">Name A → Z</option>
					</select>
				</div>
				<div class="ud-select-wrap">
					<svg class="ud-select-ico" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<path d="M8 6h13" />
						<path d="M8 12h13" />
						<path d="M8 18h13" />
						<path d="M3 6h.01" />
						<path d="M3 12h.01" />
						<path d="M3 18h.01" />
					</svg>
					<select id="rowsPerPage" class="ud-input ud-input--select">
						<option value="10" selected>10 / page</option>
						<option value="25">25 / page</option>
						<option value="50">50 / page</option>
						<option value="100">100 / page</option>
						<option value="all">All rows</option>
					</select>
				</div>
			</div>
		</div>

		<!-- Desktop Table -->
		<div class="ud-table-scroll">
			<table class="ud-table">
				<thead>
					<tr>
						<th class="ud-th ud-th--sno">#</th>
						<th class="ud-th">User</th>
						<th class="ud-th">Mobile</th>
						<th class="ud-th">Email</th>
						<th class="ud-th ud-th--addr">Address</th>
						<th class="ud-th ud-th--act">Actions</th>
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
							<tr class="ud-row"
								data-id="<?php echo (int)$user_item->id; ?>"
								data-name="<?php echo html_escape(strtolower($display_name)); ?>"
								data-mobile="<?php echo html_escape(strtolower($display_mobile)); ?>"
								data-email="<?php echo html_escape(strtolower($display_email)); ?>"
								data-address="<?php echo html_escape(strtolower($display_address)); ?>">
								<td class="ud-td ud-td--sno"><span class="ud-sno"><?php echo $index + 1; ?></span></td>
								<td class="ud-td">
									<div class="ud-user">
										<div class="ud-avatar" style="background:linear-gradient(135deg,<?php echo $pal[0]; ?>,<?php echo $pal[1]; ?>)"><?php echo html_escape($initials); ?></div>
										<div>
											<span class="ud-user__name"><?php echo html_escape($display_name ?: 'Unknown user'); ?></span>
											<span class="ud-user__meta">Regular account</span>
										</div>
									</div>
								</td>
								<td class="ud-td">
									<?php if ($display_mobile): ?>
										<span class="ud-field">
											<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
												<rect x="5" y="2" width="14" height="20" rx="2" />
												<line x1="12" y1="18" x2="12.01" y2="18" />
											</svg>
											<?php echo html_escape($display_mobile); ?>
										</span>
									<?php else: ?><span class="ud-nil">—</span><?php endif; ?>
								</td>
								<td class="ud-td">
									<?php if ($display_email): ?>
										<span class="ud-field">
											<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
												<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
												<polyline points="22,6 12,13 2,6" />
											</svg>
											<?php echo html_escape($display_email); ?>
										</span>
									<?php else: ?><span class="ud-nil">—</span><?php endif; ?>
								</td>
								<td class="ud-td ud-td--addr">
									<?php if ($display_address): ?>
										<span class="ud-field ud-field--addr" title="<?php echo html_escape($display_address); ?>">
											<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
												<path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0118 0z" />
												<circle cx="12" cy="10" r="3" />
											</svg>
											<?php echo html_escape($display_address); ?>
										</span>
									<?php else: ?><span class="ud-nil">—</span><?php endif; ?>
								</td>
								<td class="ud-td ud-td--act">
									<div class="ud-acts">
										<a class="ud-act" href="<?php echo site_url('admin/users/view/' . (int)$user_item->id); ?>" title="View profile">
											<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
												<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
												<circle cx="12" cy="12" r="3" />
											</svg>
											<span>View</span>
										</a>
										<a class="ud-act" href="<?php echo site_url('admin/users/edit/' . (int)$user_item->id); ?>" title="Edit user">
											<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
												<path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" />
												<path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
											</svg>
											<span>Edit</span>
										</a>
										<a class="ud-act ud-act--del" href="<?php echo site_url('admin/users/delete/' . (int)$user_item->id); ?>" title="Delete user" onclick="return confirm('Permanently delete this user?')">
											<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
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
								<div class="ud-empty">
									<div class="ud-empty__icon">
										<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4">
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

		<!-- Mobile User Cards -->
		<div class="ud-mobile-cards" id="udMobileCards">
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
					<div class="ud-mcard"
						data-id="<?php echo (int)$user_item->id; ?>"
						data-name="<?php echo html_escape(strtolower($display_name)); ?>"
						data-mobile="<?php echo html_escape(strtolower($display_mobile)); ?>"
						data-email="<?php echo html_escape(strtolower($display_email)); ?>"
						data-address="<?php echo html_escape(strtolower($display_address)); ?>">
						<div class="ud-mcard__header">
							<div class="ud-mcard__user">
								<div class="ud-mcard__avatar" style="background:linear-gradient(135deg,<?php echo $pal[0]; ?>,<?php echo $pal[1]; ?>)"><?php echo html_escape($initials); ?></div>
								<div>
									<span class="ud-mcard__name"><?php echo html_escape($display_name ?: 'Unknown user'); ?></span>
									<span class="ud-mcard__badge">Regular account</span>
								</div>
							</div>
							<div class="ud-mcard__acts">
								<a class="ud-act" href="<?php echo site_url('admin/users/view/' . (int)$user_item->id); ?>">
									<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
										<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
										<circle cx="12" cy="12" r="3" />
									</svg>
								</a>
								<a class="ud-act" href="<?php echo site_url('admin/users/edit/' . (int)$user_item->id); ?>">
									<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
										<path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" />
										<path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
									</svg>
								</a>
								<a class="ud-act ud-act--del" href="<?php echo site_url('admin/users/delete/' . (int)$user_item->id); ?>" onclick="return confirm('Permanently delete this user?')">
									<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
										<polyline points="3 6 5 6 21 6" />
										<path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6" />
										<path d="M10 11v6M14 11v6" />
										<path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2" />
									</svg>
								</a>
							</div>
						</div>
						<div class="ud-mcard__fields">
							<?php if ($display_mobile): ?>
								<div class="ud-mcard__field">
									<span class="ud-mcard__field-label">Mobile</span>
									<span class="ud-mcard__field-val">
										<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
											<rect x="5" y="2" width="14" height="20" rx="2" />
											<line x1="12" y1="18" x2="12.01" y2="18" />
										</svg>
										<?php echo html_escape($display_mobile); ?>
									</span>
								</div>
							<?php endif; ?>
							<?php if ($display_email): ?>
								<div class="ud-mcard__field">
									<span class="ud-mcard__field-label">Email</span>
									<span class="ud-mcard__field-val">
										<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
											<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
											<polyline points="22,6 12,13 2,6" />
										</svg>
										<?php echo html_escape($display_email); ?>
									</span>
								</div>
							<?php endif; ?>
							<?php if ($display_address): ?>
								<div class="ud-mcard__field">
									<span class="ud-mcard__field-label">Address</span>
									<span class="ud-mcard__field-val">
										<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
											<path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0118 0z" />
											<circle cx="12" cy="10" r="3" />
										</svg>
										<?php echo html_escape($display_address); ?>
									</span>
								</div>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>

		<div class="ud-pagination" id="udPagination" hidden>
			<div class="ud-page-info" id="udPageInfo"></div>
			<div class="ud-page-btns" id="udPageBtns"></div>
		</div>

		<!-- No results -->
		<div class="ud-empty ud-empty--filter" id="filteredEmpty" hidden>
			<div class="ud-empty__icon">
				<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4">
					<circle cx="11" cy="11" r="8" />
					<line x1="21" y1="21" x2="16.65" y2="16.65" />
				</svg>
			</div>
			<h3>No results</h3>
			<p>No users match your search. Try a different term.</p>
			<button class="ud-clr-btn" id="clearSearchBtn">Clear search</button>
		</div>

		<footer class="ud-foot">
			<span class="ud-foot__info" id="admFooterInfo">
				Showing all <strong><?php echo $total_users; ?></strong> users
			</span>
			<span class="ud-badge">Regular users only</span>
		</footer>
	</section>
</div>

<style>
	*,
	*::before,
	*::after {
		box-sizing: border-box;
		margin: 0;
		padding: 0;
	}

	:root {
		--f-body: 'Roboto', system-ui, -apple-system, sans-serif;
		--f-display: 'Roboto', sans-serif;
		--ink: #111827;
		--ink2: #374151;
		--ink3: #6B7280;
		--ink4: #9CA3AF;
		--line: #E5E7EB;
		--line2: #F3F4F6;
		--bg: #FFFFFF;
		--bg2: #F9FAFB;
		--red: #A32D2D;
		--red-lt: #FCEBEB;
		--red-bd: #F7C1C1;
		--green: #22C55E;
		--green-lt: #EAF3DE;
		--green-bd: #C0DD97;
		--green-tx: #27500A;
		--r-sm: 8px;
		--r-md: 12px;
		--r-lg: 16px;
	}

	.ud {
		font-family: var(--f-body);
		padding: 0 0 48px;
		display: flex;
		flex-direction: column;
		gap: 14px;
	}

	/* Toast */
	.ud-toast {
		display: flex;
		align-items: center;
		gap: 10px;
		padding: 12px 16px;
		border-radius: var(--r-md);
		font-size: 13.5px;
		font-weight: 500;
		border: 1px solid transparent;
		animation: toastIn .3s cubic-bezier(.34, 1.56, .64, 1) both;
		margin-bottom: 4px;
	}

	@keyframes toastIn {
		from {
			opacity: 0;
			transform: translateY(-10px)
		}

		to {
			opacity: 1;
			transform: none
		}
	}

	.ud-toast svg {
		flex-shrink: 0;
	}

	.ud-toast--err {
		background: var(--red-lt);
		border-color: var(--red-bd);
		color: #791f1f;
	}

	.ud-toast--ok {
		background: var(--green-lt);
		border-color: var(--green-bd);
		color: var(--green-tx);
	}

	.ud-toast__x {
		margin-left: auto;
		border: none;
		background: none;
		cursor: pointer;
		font-size: 18px;
		color: inherit;
		opacity: .5;
	}

	.ud-toast__x:hover {
		opacity: 1;
	}

	/* Banner */
	.ud-banner {
		background: var(--bg);
		border: 1px solid var(--line);
		border-radius: var(--r-lg);
		padding: 28px 32px;
		display: flex;
		justify-content: space-between;
		align-items: flex-end;
		gap: 24px;
		flex-wrap: wrap;
	}

	.ud-banner__eyebrow {
		display: inline-flex;
		align-items: center;
		gap: 7px;
		font-size: 11px;
		font-weight: 500;
		letter-spacing: .1em;
		text-transform: uppercase;
		color: var(--ink3);
		margin-bottom: 10px;
	}

	.ud-dot {
		width: 6px;
		height: 6px;
		border-radius: 50%;
		background: var(--green);
		flex-shrink: 0;
	}

	.ud-banner__title {
		font-family: var(--f-display);
		font-size: clamp(26px, 4vw, 40px);
		font-style: italic;
		color: var(--ink);
		line-height: 1.1;
		margin-bottom: 8px;
	}

	.ud-banner__sub {
		font-size: 13px;
		color: var(--ink3);
		line-height: 1.65;
		max-width: 400px;
	}

	.ud-kpis {
		display: flex;
		gap: 10px;
		flex-wrap: wrap;
		flex-shrink: 0;
	}

	.ud-kpi {
		min-width: 100px;
		padding: 14px 18px;
		border-radius: var(--r-md);
		border: 1px solid var(--line);
		background: var(--bg2);
		text-align: center;
	}

	.ud-kpi--main {
		background: var(--bg);
		border-color: var(--ink);
	}

	.ud-kpi__label {
		display: block;
		font-size: 10px;
		font-weight: 500;
		letter-spacing: .09em;
		text-transform: uppercase;
		color: var(--ink4);
		margin-bottom: 6px;
	}

	.ud-kpi__val {
		display: block;
		font-family: var(--f-display);
		font-size: 28px;
		font-style: italic;
		font-weight: 400;
		color: var(--ink);
		line-height: 1;
	}

	/* Coverage */
	.ud-coverage {
		display: grid;
		grid-template-columns: repeat(3, 1fr);
		gap: 12px;
	}

	.ud-cov {
		background: var(--bg);
		border: 1px solid var(--line);
		border-radius: var(--r-lg);
		padding: 18px 20px;
		transition: box-shadow .15s, transform .15s;
	}

	.ud-cov:hover {
		box-shadow: 0 2px 10px rgba(0, 0, 0, .06);
		transform: translateY(-1px);
	}

	.ud-cov__top {
		display: flex;
		align-items: center;
		justify-content: space-between;
		margin-bottom: 12px;
	}

	.ud-cov__icon {
		width: 36px;
		height: 36px;
		border-radius: var(--r-sm);
		display: flex;
		align-items: center;
		justify-content: center;
		background: var(--bg2);
		color: var(--ink3);
		border: 1px solid var(--line);
	}

	.ud-cov__right {
		text-align: right;
	}

	.ud-cov__num {
		display: block;
		font-family: var(--f-display);
		font-size: 24px;
		font-style: italic;
		color: var(--ink);
		line-height: 1;
	}

	.ud-cov__pct {
		font-size: 12px;
		color: var(--ink4);
	}

	.ud-cov__label {
		font-size: 12px;
		font-weight: 500;
		color: var(--ink3);
		margin-bottom: 10px;
	}

	.ud-bar {
		height: 3px;
		background: var(--line2);
		border-radius: 999px;
		overflow: hidden;
	}

	.ud-bar__fill {
		height: 100%;
		border-radius: 999px;
		background: var(--ink);
		transition: width .5s cubic-bezier(.34, 1.2, .64, 1);
	}

	/* Panel */
	.ud-panel {
		background: var(--bg);
		border: 1px solid var(--line);
		border-radius: var(--r-lg);
		overflow: hidden;
	}

	.ud-panel__head {
		display: flex;
		justify-content: space-between;
		align-items: flex-end;
		gap: 16px;
		flex-wrap: wrap;
		padding: 22px 26px 18px;
		border-bottom: 1px solid var(--line);
		background: var(--bg2);
	}

	.ud-panel__title {
		font-family: var(--f-display);
		font-size: 20px;
		font-style: italic;
		font-weight: 400;
		color: var(--ink);
		margin-bottom: 3px;
	}

	.ud-panel__sub {
		font-size: 12.5px;
		color: var(--ink4);
	}

	.ud-controls {
		display: flex;
		gap: 8px;
		align-items: center;
		flex-wrap: wrap;
	}

	.ud-search-wrap {
		position: relative;
	}

	.ud-search-ico {
		position: absolute;
		top: 50%;
		left: 11px;
		transform: translateY(-50%);
		color: var(--ink4);
		pointer-events: none;
	}

	.ud-search-clear {
		position: absolute;
		top: 50%;
		right: 9px;
		transform: translateY(-50%);
		border: none;
		background: none;
		cursor: pointer;
		color: var(--ink4);
		display: flex;
		padding: 3px;
		border-radius: 4px;
		transition: color .12s;
	}

	.ud-search-clear:hover {
		color: var(--red);
	}

	.ud-input {
		height: 36px;
		border: 1px solid var(--line);
		border-radius: var(--r-sm);
		background: var(--bg);
		color: var(--ink);
		font-family: var(--f-body);
		font-size: 13px;
		transition: border-color .15s, box-shadow .15s;
		-webkit-appearance: none;
		appearance: none;
	}

	.ud-input:focus {
		outline: none;
		border-color: var(--ink3);
		box-shadow: 0 0 0 3px rgba(17, 24, 39, .06);
	}

	.ud-input--search {
		padding: 0 30px 0 34px;
		width: 240px;
	}

	.ud-input--select {
		padding: 0 28px 0 32px;
		width: 155px;
		cursor: pointer;
	}

	.ud-select-wrap {
		position: relative;
	}

	.ud-select-ico {
		position: absolute;
		top: 50%;
		left: 10px;
		transform: translateY(-50%);
		color: var(--ink4);
		pointer-events: none;
	}

	.ud-select-wrap::after {
		content: '';
		position: absolute;
		top: 50%;
		right: 10px;
		transform: translateY(-50%);
		width: 0;
		height: 0;
		border-left: 4px solid transparent;
		border-right: 4px solid transparent;
		border-top: 5px solid var(--ink4);
		pointer-events: none;
	}

	.ud-pagination {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 12px;
		flex-wrap: wrap;
		padding: 14px 26px;
		border-top: 1px solid var(--line);
		background: var(--bg);
	}

	.ud-page-info {
		font-size: 12.5px;
		color: var(--ink4);
	}

	.ud-page-info strong {
		color: var(--ink3);
		font-weight: 600;
	}

	.ud-page-btns {
		display: flex;
		align-items: center;
		gap: 6px;
		flex-wrap: wrap;
	}

	.ud-page-btn {
		min-width: 34px;
		height: 34px;
		padding: 0 10px;
		border-radius: var(--r-sm);
		border: 1px solid var(--line);
		background: var(--bg);
		color: var(--ink2);
		font-family: var(--f-body);
		font-size: 12px;
		font-weight: 600;
		cursor: pointer;
		transition: all .12s;
		display: inline-flex;
		align-items: center;
		justify-content: center;
	}

	.ud-page-btn:hover:not(:disabled) {
		background: var(--bg2);
		border-color: var(--ink4);
		transform: translateY(-1px);
	}

	.ud-page-btn.is-active {
		background: var(--ink);
		border-color: var(--ink);
		color: #fff;
	}

	.ud-page-btn:disabled {
		opacity: .4;
		cursor: not-allowed;
		transform: none;
	}

	.ud-page-ellipsis {
		font-size: 13px;
		color: var(--ink4);
		padding: 0 4px;
	}

	/* Desktop Table */
	.ud-table-scroll {
		overflow-x: auto;
		padding: 10px 14px 4px;
	}

	.ud-table {
		width: 100%;
		min-width: 840px;
		border-collapse: separate;
		border-spacing: 0 4px;
	}

	.ud-th {
		padding: 0 12px 8px;
		font-size: 10.5px;
		font-weight: 500;
		letter-spacing: .1em;
		text-transform: uppercase;
		color: var(--ink4);
		text-align: left;
		white-space: nowrap;
	}

	.ud-th--sno {
		width: 48px;
	}

	.ud-th--act {
		text-align: right;
		width: 200px;
	}

	.ud-th--addr {
		max-width: 160px;
	}

	.ud-td {
		padding: 12px;
		background: var(--bg2);
		border-top: 1px solid var(--line);
		border-bottom: 1px solid var(--line);
		vertical-align: middle;
		font-size: 13.5px;
		color: var(--ink2);
		transition: background .1s;
	}

	.ud-td:first-child {
		border-left: 1px solid var(--line);
		border-radius: var(--r-sm) 0 0 var(--r-sm);
	}

	.ud-td:last-child {
		border-right: 1px solid var(--line);
		border-radius: 0 var(--r-sm) var(--r-sm) 0;
	}

	.ud-row:hover .ud-td {
		background: var(--bg);
	}

	.ud-td--sno {
		width: 48px;
	}

	.ud-td--addr {
		max-width: 160px;
	}

	.ud-td--act {
		text-align: right;
	}

	.ud-sno {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		width: 28px;
		height: 28px;
		border-radius: var(--r-sm);
		background: var(--bg);
		border: 1px solid var(--line);
		font-size: 11.5px;
		font-weight: 500;
		color: var(--ink4);
	}

	.ud-user {
		display: flex;
		align-items: center;
		gap: 10px;
	}

	.ud-avatar {
		width: 38px;
		height: 38px;
		border-radius: var(--r-sm);
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 13px;
		font-weight: 500;
		color: #fff;
		flex-shrink: 0;
	}

	.ud-user__name {
		display: block;
		font-size: 13.5px;
		font-weight: 500;
		color: var(--ink);
	}

	.ud-user__meta {
		display: block;
		font-size: 11px;
		color: var(--ink4);
		margin-top: 2px;
	}

	.ud-field {
		display: inline-flex;
		align-items: center;
		gap: 5px;
		font-size: 13px;
		color: var(--ink2);
	}

	.ud-field svg {
		color: var(--ink4);
		flex-shrink: 0;
	}

	.ud-field--addr {
		max-width: 170px;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
	}

	.ud-nil {
		color: var(--ink4);
		font-style: italic;
	}

	.ud-acts {
		display: flex;
		justify-content: flex-end;
		gap: 5px;
	}

	.ud-act {
		display: inline-flex;
		align-items: center;
		gap: 4px;
		padding: 5px 11px;
		border-radius: var(--r-sm);
		font-size: 12px;
		font-weight: 500;
		text-decoration: none;
		border: 1px solid var(--line);
		background: var(--bg);
		color: var(--ink2);
		cursor: pointer;
		transition: all .12s;
	}

	.ud-act svg {
		flex-shrink: 0;
	}

	.ud-act:hover {
		background: var(--bg2);
		border-color: var(--ink4);
		transform: translateY(-1px);
	}

	.ud-act:active {
		transform: none;
	}

	.ud-act--del:hover {
		color: var(--red);
		border-color: var(--red-bd);
		background: var(--red-lt);
	}

	/* Mobile Cards */
	.ud-mobile-cards {
		display: none;
	}

	.ud-mcard {
		padding: 16px;
		border-bottom: 1px solid var(--line2);
		transition: background .1s;
	}

	.ud-mcard:last-child {
		border-bottom: none;
	}

	.ud-mcard:hover {
		background: var(--bg2);
	}

	.ud-mcard__header {
		display: flex;
		justify-content: space-between;
		align-items: center;
		gap: 10px;
		margin-bottom: 12px;
	}

	.ud-mcard__user {
		display: flex;
		align-items: center;
		gap: 10px;
		min-width: 0;
	}

	.ud-mcard__avatar {
		width: 40px;
		height: 40px;
		border-radius: var(--r-sm);
		flex-shrink: 0;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 13px;
		font-weight: 500;
		color: #fff;
	}

	.ud-mcard__name {
		display: block;
		font-size: 14px;
		font-weight: 500;
		color: var(--ink);
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
	}

	.ud-mcard__badge {
		display: block;
		font-size: 11px;
		color: var(--ink4);
		margin-top: 2px;
	}

	.ud-mcard__acts {
		display: flex;
		gap: 5px;
		flex-shrink: 0;
	}

	.ud-mcard__acts .ud-act {
		padding: 6px 9px;
	}

	.ud-mcard__fields {
		display: flex;
		flex-direction: column;
		gap: 8px;
	}

	.ud-mcard__field {
		display: flex;
		align-items: flex-start;
		gap: 10px;
	}

	.ud-mcard__field-label {
		font-size: 11px;
		font-weight: 500;
		text-transform: uppercase;
		letter-spacing: .05em;
		color: var(--ink4);
		min-width: 56px;
		padding-top: 1px;
	}

	.ud-mcard__field-val {
		display: flex;
		align-items: center;
		gap: 5px;
		font-size: 13px;
		color: var(--ink2);
		min-width: 0;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
	}

	.ud-mcard__field-val svg {
		flex-shrink: 0;
		color: var(--ink4);
	}

	/* Empty */
	.ud-empty {
		padding: 48px 24px;
		text-align: center;
	}

	.ud-empty__icon {
		width: 50px;
		height: 50px;
		margin: 0 auto 14px;
		border-radius: var(--r-md);
		display: flex;
		align-items: center;
		justify-content: center;
		background: var(--bg2);
		border: 1px solid var(--line);
		color: var(--ink4);
	}

	.ud-empty h3 {
		font-family: var(--f-display);
		font-size: 19px;
		font-style: italic;
		font-weight: 400;
		color: var(--ink);
		margin-bottom: 5px;
	}

	.ud-empty p {
		font-size: 13px;
		color: var(--ink4);
	}

	.ud-empty--filter {
		border-top: 1px solid var(--line);
	}

	.ud-clr-btn {
		margin-top: 14px;
		padding: 7px 18px;
		border-radius: var(--r-sm);
		border: 1px solid var(--line);
		background: var(--bg);
		color: var(--ink2);
		font-family: var(--f-body);
		font-size: 13px;
		font-weight: 500;
		cursor: pointer;
		transition: all .12s;
	}

	.ud-clr-btn:hover {
		background: var(--bg2);
		border-color: var(--ink4);
	}

	/* Footer */
	.ud-foot {
		display: flex;
		justify-content: space-between;
		align-items: center;
		flex-wrap: wrap;
		gap: 10px;
		padding: 14px 26px 18px;
		border-top: 1px solid var(--line);
		background: var(--bg2);
	}

	.ud-foot__info {
		font-size: 13px;
		color: var(--ink4);
	}

	.ud-foot__info strong {
		color: var(--ink3);
		font-weight: 500;
	}

	.ud-badge {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		padding: 4px 11px;
		border-radius: 999px;
		background: var(--bg);
		border: 1px solid var(--line);
		color: var(--ink4);
		font-size: 11px;
		font-weight: 500;
		letter-spacing: .04em;
	}

	.ud-badge::before {
		content: '';
		width: 5px;
		height: 5px;
		border-radius: 50%;
		background: var(--green);
	}

	/* Responsive */
	@media (max-width: 960px) {
		.ud-coverage {
			grid-template-columns: repeat(3, 1fr);
		}
	}

	@media (max-width: 740px) {
		.ud-coverage {
			grid-template-columns: 1fr;
		}

		.ud-panel__head {
			flex-direction: column;
			align-items: stretch;
			padding: 18px;
		}

		.ud-controls {
			flex-direction: column;
		}

		.ud-input--search,
		.ud-input--select,
		.ud-search-wrap,
		.ud-select-wrap {
			width: 100%;
		}

		.ud-table-scroll {
			display: none;
		}

		.ud-mobile-cards {
			display: block;
		}

		.ud-pagination {
			padding: 14px 18px;
		}
	}

	@media (max-width: 640px) {
		.ud-banner {
			flex-direction: column;
			align-items: flex-start;
			padding: 20px;
		}

		.ud-kpis {
			width: 100%;
		}

		.ud-kpi {
			flex: 1;
			min-width: auto;
			padding: 12px 14px;
		}

		.ud-kpi__val {
			font-size: 24px;
		}

		.ud-banner__title {
			font-size: 26px;
		}
	}

	@media (max-width: 400px) {
		.ud-kpis {
			flex-direction: column;
		}
	}
</style>

<script>
	(function() {
		'use strict';
		var si = document.getElementById('searchUsers');
		var ss = document.getElementById('sortUsers');
		var rp = document.getElementById('rowsPerPage');
		var sx = document.getElementById('searchClear');
		var cb = document.getElementById('clearSearchBtn');
		var tb = document.getElementById('usersTableBody');
		var mc = document.getElementById('udMobileCards');
		var vc = document.getElementById('visibleCount');
		var fi = document.getElementById('admFooterInfo');
		var fe = document.getElementById('filteredEmpty');
		var pg = document.getElementById('udPagination');
		var pi = document.getElementById('udPageInfo');
		var pb = document.getElementById('udPageBtns');
		var er = document.getElementById('admEmptyRow');
		var state = {
			page: 1,
			rowsPerPage: 10
		};

		function tableRows() {
			return tb ? Array.prototype.slice.call(tb.querySelectorAll('.ud-row')) : [];
		}

		function mobileCards() {
			return mc ? Array.prototype.slice.call(mc.querySelectorAll('.ud-mcard')) : [];
		}

		function reindex(vis) {
			var n = 0;
			vis.forEach(function(r) {
				n++;
				var s = r.querySelector('.ud-sno');
				if (s) s.textContent = n;
			});
			return n;
		}

		function buildPageButtons(totalPages) {
			var p = state.page;
			var items = [];
			var html = '';
			var i;

			if (totalPages <= 7) {
				for (i = 1; i <= totalPages; i++) {
					items.push(i);
				}
			} else {
				items.push(1);
				if (p > 3) items.push('left');
				for (i = Math.max(2, p - 1); i <= Math.min(totalPages - 1, p + 1); i++) {
					items.push(i);
				}
				if (p < totalPages - 2) items.push('right');
				items.push(totalPages);
			}

			html += '<button type="button" class="ud-page-btn" data-page="' + (p - 1) + '"' + (p === 1 ? ' disabled' : '') + '>&lsaquo;</button>';
			items.forEach(function(item) {
				if (typeof item === 'string') {
					html += '<span class="ud-page-ellipsis">...</span>';
				} else {
					html += '<button type="button" class="ud-page-btn' + (item === p ? ' is-active' : '') + '" data-page="' + item + '">' + item + '</button>';
				}
			});
			html += '<button type="button" class="ud-page-btn" data-page="' + (p + 1) + '"' + (p === totalPages ? ' disabled' : '') + '>&rsaquo;</button>';
			return html;
		}

		function renderPagination(totalCount, visibleCount) {
			var perPage = state.rowsPerPage;
			var totalPages;
			var start;
			var end;

			if (!pg || !pi || !pb) return;

			if (!totalCount || perPage === 'all' || totalCount <= perPage) {
				pg.hidden = true;
				return;
			}

			totalPages = Math.max(1, Math.ceil(totalCount / perPage));
			if (state.page > totalPages) state.page = totalPages;
			start = (state.page - 1) * perPage + 1;
			end = start + visibleCount - 1;

			pg.hidden = false;
			pi.innerHTML = 'Showing <strong>' + start + '-' + end + '</strong> of <strong>' + totalCount + '</strong> users';
			pb.innerHTML = buildPageButtons(totalPages);
		}

		function render() {
			var term = si ? si.value.toLowerCase().trim() : '';
			var tRows = tableRows();
			var mCards = mobileCards();
			var matchedIds = [];
			var pageIds = [];
			var visibleRows;
			var start = 0;
			var end = 0;
			var totalMatches = 0;
			var perPage = state.rowsPerPage;

			tRows.forEach(function(r) {
				var match = !term ||
					(r.dataset.name || '').indexOf(term) > -1 ||
					(r.dataset.mobile || '').indexOf(term) > -1 ||
					(r.dataset.email || '').indexOf(term) > -1 ||
					(r.dataset.address || '').indexOf(term) > -1;
				if (match) matchedIds.push(r.dataset.id || '');
			});

			totalMatches = matchedIds.length;

			if (perPage === 'all') {
				pageIds = matchedIds.slice();
			} else {
				start = (state.page - 1) * perPage;
				if (start >= totalMatches && totalMatches > 0) {
					state.page = 1;
					start = 0;
				}
				end = start + perPage;
				pageIds = matchedIds.slice(start, end);
			}

			tRows.forEach(function(r) {
				var show = pageIds.indexOf(r.dataset.id || '') > -1;
				r.style.display = show ? '' : 'none';
			});

			mCards.forEach(function(c) {
				var match = !term ||
					(c.dataset.name || '').indexOf(term) > -1 ||
					(c.dataset.mobile || '').indexOf(term) > -1 ||
					(c.dataset.email || '').indexOf(term) > -1 ||
					(c.dataset.address || '').indexOf(term) > -1;
				var show = match && pageIds.indexOf(c.dataset.id || '') > -1;
				c.style.display = show ? '' : 'none';
			});

			visibleRows = tRows.filter(function(r) {
				return r.style.display !== 'none';
			});

			var n = reindex(visibleRows);
			if (vc) vc.textContent = totalMatches;
			if (fe) fe.hidden = (totalMatches > 0);
			if (sx) sx.hidden = !term;
			if (er) er.style.display = tRows.length === 0 ? '' : 'none';
			if (fi) {
				if (!totalMatches) {
					fi.innerHTML = term ? 'Showing <strong>0</strong> of <strong>' + tRows.length + '</strong> users' : 'Showing <strong>0</strong> users';
				} else if (perPage === 'all') {
					fi.innerHTML = term ?
						'Showing <strong>' + totalMatches + '</strong> of <strong>' + tRows.length + '</strong> users' :
						'Showing all <strong>' + totalMatches + '</strong> users';
				} else {
					var pageStart = ((state.page - 1) * perPage) + 1;
					var pageEnd = pageStart + n - 1;
					fi.innerHTML = 'Showing <strong>' + pageStart + '-' + pageEnd + '</strong> of <strong>' + totalMatches + '</strong> users';
				}
			}

			renderPagination(totalMatches, n);
		}

		function sort() {
			var order = ss ? ss.value : 'newest';
			var tRows = tableRows();
			var mCards = mobileCards();

			function sortFn(a, b) {
				if (order === 'oldest') return parseInt(a.dataset.id, 10) - parseInt(b.dataset.id, 10);
				if (order === 'name') return (a.dataset.name || '').localeCompare(b.dataset.name || '');
				return parseInt(b.dataset.id, 10) - parseInt(a.dataset.id, 10);
			}
			tRows.sort(sortFn).forEach(function(r) {
				if (tb) tb.appendChild(r);
			});
			mCards.sort(sortFn).forEach(function(c) {
				if (mc) mc.appendChild(c);
			});
			render();
		}

		function clearSearch() {
			if (si) {
				si.value = '';
				si.focus();
			}
			state.page = 1;
			render();
		}

		if (si) si.addEventListener('input', function() {
			state.page = 1;
			render();
		});
		if (ss) ss.addEventListener('change', sort);
		if (rp) rp.addEventListener('change', function() {
			var value = rp.value;
			state.rowsPerPage = value === 'all' ? 'all' : parseInt(value, 10);
			state.page = 1;
			render();
		});
		if (sx) sx.addEventListener('click', clearSearch);
		if (cb) cb.addEventListener('click', clearSearch);
		if (pb) pb.addEventListener('click', function(e) {
			var btn = e.target.closest('.ud-page-btn');
			var nextPage;
			if (!btn || btn.disabled) return;
			nextPage = parseInt(btn.getAttribute('data-page'), 10);
			if (isNaN(nextPage) || nextPage < 1) return;
			state.page = nextPage;
			render();
		});

		sort();

		Array.prototype.slice.call(document.querySelectorAll('.ud-toast')).forEach(function(el) {
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
