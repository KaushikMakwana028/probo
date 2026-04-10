<?php $total_users = isset($total_users) ? (int) $total_users : (!empty($users) ? count($users) : 0); ?>

<?php if ($this->session->flashdata('error')): ?>
	<div class="users-flash users-flash-error"><?php echo $this->session->flashdata('error'); ?></div>
<?php endif; ?>

<?php if ($this->session->flashdata('success')): ?>
	<div class="users-flash users-flash-success"><?php echo $this->session->flashdata('success'); ?></div>
<?php endif; ?>

<section class="users-hero">
	<div>
		<p class="users-eyebrow">Admin Panel</p>
		<h2>Registered Users</h2>
		<p class="users-hero-text">This page shows only normal users. Admin accounts are excluded from this list.</p>
	</div>
	<div class="users-hero-count">
		<span>Total Users</span>
		<strong id="visibleUsersCount"><?php echo $total_users; ?></strong>
	</div>
</section>

<section class="users-panel">
	<div class="users-panel-head">
		<div>
			<h3>User Directory</h3>
			<p>Search and manage user accounts with separate view and edit pages.</p>
		</div>
		<div class="users-tools">
			<div class="users-search">
				<input type="text" id="searchUsers" placeholder="Search by name, email, mobile or address">
			</div>
			<select id="sortUsers">
				<option value="newest">Newest First</option>
				<option value="oldest">Oldest First</option>
				<option value="name">Name A-Z</option>
			</select>
		</div>
	</div>

	<div class="users-table-wrap">
		<table class="users-table">
			<thead>
				<tr>
					<th>S.No</th>
					<th>User</th>
					<th>Mobile</th>
					<th>Email</th>
					<th>Address</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody id="usersTableBody">
				<?php if (!empty($users)): ?>
					<?php foreach ($users as $index => $user_item): ?>
						<?php
						$display_name = trim((string) $user_item->name);
						$display_mobile = trim((string) $user_item->mobile);
						$display_email = trim((string) $user_item->email);
						$display_address = trim((string) $user_item->address);
						$avatar_text = strtoupper(substr($display_name !== '' ? $display_name : 'U', 0, 2));
						?>
						<tr
							class="user-row"
							data-id="<?php echo (int) $user_item->id; ?>"
							data-name="<?php echo html_escape(strtolower($display_name)); ?>"
							data-mobile="<?php echo html_escape(strtolower($display_mobile)); ?>"
							data-email="<?php echo html_escape(strtolower($display_email)); ?>"
							data-address="<?php echo html_escape(strtolower($display_address)); ?>">
							<td class="serial-cell">
								<span class="serial-number"><?php echo (int) $index + 1; ?></span>
							</td>
							<td>
								<div class="user-card">
									<div class="user-avatar"><?php echo html_escape($avatar_text); ?></div>
									<div class="user-meta">
										<strong><?php echo html_escape($display_name !== '' ? $display_name : 'Unknown User'); ?></strong>
										<span>Regular User</span>
									</div>
								</div>
							</td>
							<td><?php echo html_escape($display_mobile !== '' ? $display_mobile : 'Not available'); ?></td>
							<td><?php echo html_escape($display_email !== '' ? $display_email : 'Not available'); ?></td>
							<td class="address-cell"><?php echo html_escape($display_address !== '' ? $display_address : 'Not provided'); ?></td>
							<td>
								<div class="action-group">
									<a class="action-btn action-view" href="<?php echo site_url('admin/users/view/' . (int) $user_item->id); ?>">View</a>
									<a class="action-btn action-edit" href="<?php echo site_url('admin/users/edit/' . (int) $user_item->id); ?>">Edit</a>
									<a class="action-btn action-delete" href="<?php echo site_url('admin/users/delete/' . (int) $user_item->id); ?>" onclick="return confirm('Delete this user permanently?');">Delete</a>
								</div>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php else: ?>
					<tr id="emptyUsersRow">
						<td colspan="6">
							<div class="users-empty">
								<h4>No users found</h4>
								<p>There are no regular users to display right now.</p>
							</div>
						</td>
					</tr>
				<?php endif; ?>
			</tbody>
		</table>
	</div>

	<div class="users-empty users-empty-filtered" id="filteredEmptyState" hidden>
		<h4>No matching users</h4>
		<p>Try a different search term.</p>
	</div>
</section>

<style>
	.users-flash {
		padding: 14px 18px;
		border-radius: 14px;
		margin-bottom: 20px;
		font-weight: 600;
		border: 1px solid transparent;
	}

	.users-flash-error {
		background: #fef2f2;
		border-color: #fecaca;
		color: #991b1b;
	}

	.users-flash-success {
		background: #f0fdf4;
		border-color: #bbf7d0;
		color: #166534;
	}

	.users-hero {
		display: flex;
		justify-content: space-between;
		gap: 20px;
		align-items: center;
		padding: 28px;
		border-radius: 24px;
		background: linear-gradient(135deg, #123c8d 0%, #2563eb 55%, #4f46e5 100%);
		color: #fff;
		margin-bottom: 24px;
		box-shadow: 0 18px 40px rgba(37, 99, 235, 0.22);
	}

	.users-eyebrow {
		margin: 0 0 10px;
		text-transform: uppercase;
		letter-spacing: 0.14em;
		font-size: 12px;
		font-weight: 700;
		color: rgba(255, 255, 255, 0.72);
	}

	.users-hero h2 {
		margin: 0 0 8px;
		font-size: 34px;
		line-height: 1.1;
		color: #fff;
	}

	.users-hero-text {
		margin: 0;
		max-width: 560px;
		color: rgba(255, 255, 255, 0.82);
	}

	.users-hero-count {
		min-width: 170px;
		padding: 20px;
		border-radius: 20px;
		background: rgba(255, 255, 255, 0.12);
		backdrop-filter: blur(10px);
		-webkit-backdrop-filter: blur(10px);
		text-align: center;
	}

	.users-hero-count span {
		display: block;
		font-size: 12px;
		text-transform: uppercase;
		letter-spacing: 0.12em;
		color: rgba(255, 255, 255, 0.72);
		margin-bottom: 8px;
	}

	.users-hero-count strong {
		font-size: 42px;
		line-height: 1;
		color: #fff;
	}

	.users-panel {
		background: #fff;
		border: 1px solid #e5e7eb;
		border-radius: 24px;
		padding: 26px;
		box-shadow: 0 8px 24px rgba(15, 23, 42, 0.05);
	}

	.users-panel-head {
		display: flex;
		justify-content: space-between;
		gap: 18px;
		align-items: flex-start;
		margin-bottom: 22px;
	}

	.users-panel-head h3 {
		margin: 0 0 6px;
		font-size: 28px;
		color: #111827;
	}

	.users-panel-head p {
		margin: 0;
		color: #6b7280;
	}

	.users-tools {
		display: flex;
		gap: 12px;
		flex-wrap: wrap;
		align-items: center;
	}

	.users-search {
		min-width: 280px;
	}

	.users-search input,
	.users-tools select {
		width: 100%;
		padding: 12px 14px;
		border: 1px solid #d1d5db;
		border-radius: 14px;
		font-size: 14px;
		background: #fff;
	}

	.users-search input:focus,
	.users-tools select:focus {
		outline: none;
		border-color: #2563eb;
		box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.10);
	}

	.users-table-wrap {
		overflow-x: auto;
	}

	.users-table {
		width: 100%;
		border-collapse: collapse;
		min-width: 980px;
	}

	.users-table th {
		padding: 16px 18px;
		font-size: 12px;
		text-transform: uppercase;
		letter-spacing: 0.08em;
		color: #6b7280;
		text-align: left;
		background: #f8fafc;
		border-bottom: 1px solid #e5e7eb;
	}

	.users-table td {
		padding: 18px;
		border-bottom: 1px solid #eef2f7;
		color: #1f2937;
		vertical-align: middle;
	}

	.user-row:hover {
		background: #f8fbff;
	}

	.serial-cell {
		width: 80px;
	}

	.serial-number {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		width: 38px;
		height: 38px;
		border-radius: 12px;
		background: #eff6ff;
		color: #2563eb;
		font-weight: 800;
	}

	.user-card {
		display: flex;
		align-items: center;
		gap: 12px;
	}

	.user-avatar {
		width: 46px;
		height: 46px;
		border-radius: 16px;
		background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%);
		color: #fff;
		font-weight: 800;
		display: flex;
		align-items: center;
		justify-content: center;
	}

	.user-meta strong {
		display: block;
		color: #111827;
		font-size: 15px;
		margin-bottom: 4px;
	}

	.user-meta span {
		color: #6b7280;
		font-size: 13px;
	}

	.address-cell {
		max-width: 220px;
		color: #6b7280;
	}

	.action-group {
		display: flex;
		gap: 8px;
		flex-wrap: wrap;
	}

	.action-btn {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		padding: 10px 14px;
		border-radius: 12px;
		text-decoration: none;
		font-size: 13px;
		font-weight: 700;
		border: 1px solid transparent;
		transition: all 0.2s ease;
	}

	.action-view {
		background: #eff6ff;
		color: #1d4ed8;
	}

	.action-edit {
		background: #eef2ff;
		color: #4338ca;
	}

	.action-delete {
		background: #fef2f2;
		color: #b91c1c;
	}

	.action-btn:hover {
		transform: translateY(-1px);
	}

	.users-empty {
		text-align: center;
		padding: 50px 20px;
	}

	.users-empty h4 {
		margin: 0 0 8px;
		font-size: 20px;
		color: #111827;
	}

	.users-empty p {
		margin: 0;
		color: #6b7280;
	}

	.users-empty-filtered {
		margin-top: 18px;
		border: 1px dashed #cbd5e1;
		border-radius: 20px;
		background: #f8fafc;
	}

	@media (max-width: 900px) {

		.users-hero,
		.users-panel-head {
			flex-direction: column;
			align-items: stretch;
		}

		.users-hero-count {
			min-width: auto;
		}

		.users-search {
			min-width: 100%;
		}
	}

	@media (max-width: 640px) {

		.users-panel,
		.users-hero {
			padding: 20px;
		}

		.users-hero h2 {
			font-size: 28px;
		}
	}
</style>

<script>
	(function() {
		var searchInput = document.getElementById('searchUsers');
		var sortUsers = document.getElementById('sortUsers');
		var tableBody = document.getElementById('usersTableBody');
		var counter = document.getElementById('visibleUsersCount');
		var filteredEmptyState = document.getElementById('filteredEmptyState');

		if (!tableBody) {
			return;
		}

		function getRows() {
			return Array.prototype.slice.call(tableBody.querySelectorAll('.user-row'));
		}

		function refreshSerials() {
			var rows = getRows();
			var currentIndex = 1;

			rows.forEach(function(row) {
				if (row.style.display !== 'none') {
					var serial = row.querySelector('.serial-number');

					if (serial) {
						serial.textContent = currentIndex;
					}

					currentIndex++;
				}
			});

			if (counter) {
				counter.textContent = currentIndex - 1;
			}

			if (filteredEmptyState) {
				filteredEmptyState.hidden = currentIndex !== 1;
			}
		}

		function applyFilters() {
			var term = searchInput ? searchInput.value.toLowerCase().trim() : '';
			var rows = getRows();

			rows.forEach(function(row) {
				var matches = !term ||
					row.dataset.name.indexOf(term) !== -1 ||
					row.dataset.mobile.indexOf(term) !== -1 ||
					row.dataset.email.indexOf(term) !== -1 ||
					row.dataset.address.indexOf(term) !== -1;

				row.style.display = matches ? '' : 'none';
			});

			refreshSerials();
		}

		function applySort() {
			var rows = getRows();
			var sortValue = sortUsers ? sortUsers.value : 'newest';

			rows.sort(function(a, b) {
				if (sortValue === 'oldest') {
					return parseInt(a.dataset.id, 10) - parseInt(b.dataset.id, 10);
				}

				if (sortValue === 'name') {
					return a.dataset.name.localeCompare(b.dataset.name);
				}

				return parseInt(b.dataset.id, 10) - parseInt(a.dataset.id, 10);
			});

			rows.forEach(function(row) {
				tableBody.appendChild(row);
			});

			applyFilters();
		}

		if (searchInput) {
			searchInput.addEventListener('input', applyFilters);
		}

		if (sortUsers) {
			sortUsers.addEventListener('change', applySort);
		}

		applySort();
	}());
</script>