<?php
$profile_image = !empty($user->profile_image) ? $user->profile_image : 'assets/images/default-profile.svg';
$profile_image_src = preg_match('/^https?:\/\//i', $profile_image) ? $profile_image : base_url($profile_image);
?>

<?php if ($this->session->flashdata('error')): ?>
	<div class="user-detail-flash user-detail-flash-error"><?php echo $this->session->flashdata('error'); ?></div>
<?php endif; ?>

<section class="user-detail-shell">
	<div class="user-detail-card">
		<div class="user-detail-cover"></div>
		<div class="user-detail-main">
			<div class="user-detail-avatar-wrap">
				<img src="<?php echo html_escape($profile_image_src); ?>" alt="<?php echo html_escape($user->name); ?>" class="user-detail-avatar">
			</div>
			<div class="user-detail-head">
				<div>
					<p class="user-detail-eyebrow">User Details</p>
					<h2><?php echo html_escape($user->name); ?></h2>
					<p class="user-detail-sub">Complete user profile information for admin review.</p>
				</div>
				<div class="user-detail-actions">
					<a class="detail-btn detail-btn-light" href="<?php echo site_url('admin/users'); ?>">Back to List</a>
					<a class="detail-btn detail-btn-primary" href="<?php echo site_url('admin/users/edit/' . (int) $user->id); ?>">Edit User</a>
				</div>
			</div>

			<div class="user-info-grid">
				<div class="user-info-card">
					<span class="label">Attempted Answers</span>
					<strong><?php echo (int) $attempted_answers; ?></strong>
				</div>
				<div class="user-info-card">
					<span class="label">Winning Amount</span>
					<strong>Rs <?php echo number_format((float) $total_winnings, 2); ?></strong>
				</div>
				<div class="user-info-card">
					<span class="label">Total Withdraw</span>
					<strong>Rs <?php echo number_format((float) $total_withdraw, 2); ?></strong>
				</div>
				<div class="user-info-card">
					<span class="label">Name</span>
					<strong><?php echo html_escape($user->name); ?></strong>
				</div>
				<div class="user-info-card">
					<span class="label">Mobile Number</span>
					<strong><?php echo html_escape($user->mobile !== '' ? $user->mobile : 'Not available'); ?></strong>
				</div>
				<div class="user-info-card">
					<span class="label">Email Address</span>
					<strong><?php echo html_escape($user->email !== '' ? $user->email : 'Not available'); ?></strong>
				</div>
				<div class="user-info-card">
					<span class="label">Role</span>
					<strong>Regular User</strong>
				</div>
				<div class="user-info-card">
					<span class="label">Profile Image</span>
					<strong><?php echo !empty($user->profile_image) ? 'Uploaded' : 'Default'; ?></strong>
				</div>
				<div class="user-info-card user-info-card-wide">
					<span class="label">Address</span>
					<strong><?php echo html_escape(trim((string) $user->address) !== '' ? $user->address : 'Address not provided'); ?></strong>
				</div>
			</div>

			<div class="user-danger-zone">
				<div>
					<h3>Delete User</h3>
					<p>Remove this user account permanently from the system.</p>
				</div>
				<a class="detail-btn detail-btn-danger" href="<?php echo site_url('admin/users/delete/' . (int) $user->id); ?>" onclick="return confirm('Delete this user permanently?');">Delete User</a>
			</div>
		</div>
	</div>
</section>

<style>
	.user-detail-flash {
		padding: 14px 18px;
		border-radius: 14px;
		margin-bottom: 20px;
		font-weight: 600;
	}

	.user-detail-flash-error {
		background: #fef2f2;
		border: 1px solid #fecaca;
		color: #991b1b;
	}

	.user-detail-shell {
		display: grid;
	}

	.user-detail-card {
		background: #fff;
		border: 1px solid #e5e7eb;
		border-radius: 28px;
		overflow: hidden;
		box-shadow: 0 14px 36px rgba(15, 23, 42, 0.08);
	}

	.user-detail-cover {
		height: 170px;
		background: linear-gradient(135deg, #1d4ed8 0%, #4f46e5 50%, #0f766e 100%);
	}

	.user-detail-main {
		padding: 0 30px 30px;
	}

	.user-detail-avatar-wrap {
		margin-top: -62px;
		margin-bottom: 20px;
	}

	.user-detail-avatar {
		width: 124px;
		height: 124px;
		border-radius: 32px;
		object-fit: cover;
		border: 6px solid #fff;
		box-shadow: 0 10px 30px rgba(15, 23, 42, 0.16);
		background: #f8fafc;
	}

	.user-detail-head {
		display: flex;
		justify-content: space-between;
		gap: 20px;
		align-items: flex-start;
		margin-bottom: 26px;
	}

	.user-detail-eyebrow {
		margin: 0 0 10px;
		font-size: 12px;
		text-transform: uppercase;
		letter-spacing: 0.14em;
		color: #2563eb;
		font-weight: 800;
	}

	.user-detail-head h2 {
		margin: 0 0 8px;
		font-size: 34px;
		color: #111827;
	}

	.user-detail-sub {
		margin: 0;
		color: #6b7280;
	}

	.user-detail-actions {
		display: flex;
		gap: 10px;
		flex-wrap: wrap;
	}

	.detail-btn {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		padding: 12px 18px;
		border-radius: 14px;
		text-decoration: none;
		font-size: 14px;
		font-weight: 700;
		border: 1px solid transparent;
	}

	.detail-btn-light {
		background: #f8fafc;
		color: #334155;
		border-color: #dbe2ea;
	}

	.detail-btn-primary {
		background: linear-gradient(135deg, #2563eb 0%, #4338ca 100%);
		color: #fff;
	}

	.detail-btn-danger {
		background: #fef2f2;
		color: #b91c1c;
		border-color: #fecaca;
	}

	.user-info-grid {
		display: grid;
		grid-template-columns: repeat(2, minmax(0, 1fr));
		gap: 18px;
		margin-bottom: 26px;
	}

	.user-info-card {
		padding: 20px;
		border-radius: 20px;
		background: #f8fafc;
		border: 1px solid #e2e8f0;
	}

	.user-info-card-wide {
		grid-column: 1 / -1;
	}

	.user-info-card .label {
		display: block;
		margin-bottom: 10px;
		font-size: 12px;
		text-transform: uppercase;
		letter-spacing: 0.12em;
		color: #64748b;
		font-weight: 700;
	}

	.user-info-card strong {
		font-size: 18px;
		line-height: 1.5;
		color: #0f172a;
	}

	.user-danger-zone {
		display: flex;
		justify-content: space-between;
		align-items: center;
		gap: 18px;
		padding: 22px;
		border-radius: 22px;
		background: linear-gradient(135deg, #fff1f2 0%, #fef2f2 100%);
		border: 1px solid #fecdd3;
	}

	.user-danger-zone h3 {
		margin: 0 0 6px;
		color: #881337;
	}

	.user-danger-zone p {
		margin: 0;
		color: #9f1239;
	}

	@media (max-width: 860px) {
		.user-detail-head,
		.user-danger-zone {
			flex-direction: column;
			align-items: stretch;
		}

		.user-info-grid {
			grid-template-columns: 1fr;
		}
	}

	@media (max-width: 640px) {
		.user-detail-main {
			padding: 0 20px 20px;
		}

		.user-detail-head h2 {
			font-size: 28px;
		}
	}
</style>
