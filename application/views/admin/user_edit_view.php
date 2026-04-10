<?php
$profile_image = !empty($user->profile_image) ? $user->profile_image : 'assets/images/default-profile.svg';
$profile_image_src = preg_match('/^https?:\/\//i', $profile_image) ? $profile_image : base_url($profile_image);
?>

<?php if ($this->session->flashdata('error')): ?>
	<div class="user-edit-flash user-edit-flash-error"><?php echo $this->session->flashdata('error'); ?></div>
<?php endif; ?>

<?php if ($this->session->flashdata('success')): ?>
	<div class="user-edit-flash user-edit-flash-success"><?php echo $this->session->flashdata('success'); ?></div>
<?php endif; ?>

<section class="user-edit-layout">
	<aside class="user-edit-summary">
		<div class="user-edit-profile-card">
			<div class="user-edit-avatar-ring">
				<img src="<?php echo html_escape($profile_image_src); ?>" alt="<?php echo html_escape($user->name); ?>" class="user-edit-avatar">
			</div>
			<h3><?php echo html_escape($user->name); ?></h3>
			<p>Regular User</p>

			<div class="user-edit-meta">
				<div>
					<span>Email</span>
					<strong><?php echo html_escape($user->email !== '' ? $user->email : 'Not available'); ?></strong>
				</div>
				<div>
					<span>Mobile</span>
					<strong><?php echo html_escape($user->mobile !== '' ? $user->mobile : 'Not available'); ?></strong>
				</div>
			</div>

			<div class="user-edit-side-actions">
				<a class="side-btn side-btn-light" href="<?php echo site_url('admin/users/view/' . (int) $user->id); ?>">View Details</a>
				<a class="side-btn side-btn-light" href="<?php echo site_url('admin/users'); ?>">Back to Users</a>
			</div>
		</div>
	</aside>

	<section class="user-edit-panel">
		<div class="user-edit-head">
			<div>
				<p class="user-edit-eyebrow">Edit User</p>
				<h2>Update User Details</h2>
				<p>Change the user's information below. Leave password empty if you do not want to reset it.</p>
			</div>
			<a class="side-btn side-btn-danger" href="<?php echo site_url('admin/users/delete/' . (int) $user->id); ?>" onclick="return confirm('Delete this user permanently?');">Delete User</a>
		</div>

		<form method="post" action="<?php echo site_url('admin/users/update/' . (int) $user->id); ?>" class="user-edit-form">
			<div class="user-edit-grid">
				<div class="form-group">
					<label for="name">Full Name *</label>
					<input type="text" id="name" name="name" value="<?php echo set_value('name', $user->name); ?>" required>
				</div>
				<div class="form-group">
					<label for="mobile">Mobile Number *</label>
					<input type="text" id="mobile" name="mobile" value="<?php echo set_value('mobile', $user->mobile); ?>" required>
				</div>
				<div class="form-group">
					<label for="email">Email Address *</label>
					<input type="email" id="email" name="email" value="<?php echo set_value('email', $user->email); ?>" required>
				</div>
				<div class="form-group">
					<label for="password">New Password</label>
					<input type="password" id="password" name="password" placeholder="Leave blank to keep current password">
				</div>
				<div class="form-group form-group-full">
					<label for="address">Address</label>
					<textarea id="address" name="address" rows="5"><?php echo set_value('address', $user->address); ?></textarea>
				</div>
			</div>

			<div class="user-edit-actions">
				<a class="side-btn side-btn-light" href="<?php echo site_url('admin/users'); ?>">Cancel</a>
				<button type="submit" class="side-btn side-btn-primary">Save Changes</button>
			</div>
		</form>
	</section>
</section>

<style>
	.user-edit-flash {
		padding: 14px 18px;
		border-radius: 14px;
		margin-bottom: 20px;
		font-weight: 600;
	}

	.user-edit-flash-error {
		background: #fef2f2;
		border: 1px solid #fecaca;
		color: #991b1b;
	}

	.user-edit-flash-success {
		background: #f0fdf4;
		border: 1px solid #bbf7d0;
		color: #166534;
	}

	.user-edit-layout {
		display: grid;
		grid-template-columns: 320px minmax(0, 1fr);
		gap: 24px;
		align-items: start;
	}

	.user-edit-profile-card,
	.user-edit-panel {
		background: #fff;
		border: 1px solid #e5e7eb;
		border-radius: 26px;
		box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
	}

	.user-edit-profile-card {
		padding: 24px;
		position: sticky;
		top: 20px;
		text-align: center;
	}

	.user-edit-avatar-ring {
		width: 128px;
		height: 128px;
		margin: 0 auto 18px;
		padding: 6px;
		border-radius: 30px;
		background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%);
	}

	.user-edit-avatar {
		width: 100%;
		height: 100%;
		border-radius: 24px;
		object-fit: cover;
		background: #f8fafc;
	}

	.user-edit-profile-card h3 {
		margin: 0 0 6px;
		font-size: 26px;
		color: #111827;
	}

	.user-edit-profile-card>p {
		margin: 0 0 20px;
		color: #6b7280;
	}

	.user-edit-meta {
		display: grid;
		gap: 14px;
		margin-bottom: 22px;
		text-align: left;
	}

	.user-edit-meta div {
		padding: 14px 16px;
		border-radius: 18px;
		background: #f8fafc;
		border: 1px solid #e2e8f0;
	}

	.user-edit-meta span {
		display: block;
		font-size: 12px;
		text-transform: uppercase;
		letter-spacing: 0.12em;
		color: #64748b;
		font-weight: 700;
		margin-bottom: 8px;
	}

	.user-edit-meta strong {
		color: #0f172a;
		font-size: 15px;
		word-break: break-word;
	}

	.user-edit-side-actions {
		display: grid;
		gap: 10px;
	}

	.user-edit-panel {
		padding: 28px;
	}

	.user-edit-head {
		display: flex;
		justify-content: space-between;
		gap: 18px;
		align-items: flex-start;
		margin-bottom: 24px;
	}

	.user-edit-eyebrow {
		margin: 0 0 10px;
		font-size: 12px;
		text-transform: uppercase;
		letter-spacing: 0.14em;
		color: #2563eb;
		font-weight: 800;
	}

	.user-edit-head h2 {
		margin: 0 0 8px;
		font-size: 32px;
		color: #111827;
	}

	.user-edit-head p {
		margin: 0;
		color: #6b7280;
		max-width: 620px;
	}

	.user-edit-grid {
		display: grid;
		grid-template-columns: repeat(2, minmax(0, 1fr));
		gap: 18px;
	}

	.form-group {
		margin-bottom: 2px;
	}

	.form-group-full {
		grid-column: 1 / -1;
	}

	.form-group label {
		display: block;
		margin-bottom: 8px;
		font-size: 14px;
		font-weight: 700;
		color: #374151;
	}

	.form-group input,
	.form-group textarea {
		width: 100%;
		padding: 14px 16px;
		border: 1px solid #d1d5db;
		border-radius: 16px;
		font-size: 14px;
		font-family: inherit;
		background: #fff;
		transition: border-color 0.2s, box-shadow 0.2s;
	}

	.form-group textarea {
		resize: vertical;
	}

	.form-group input:focus,
	.form-group textarea:focus {
		outline: none;
		border-color: #2563eb;
		box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.10);
	}

	.user-edit-actions {
		display: flex;
		justify-content: flex-end;
		gap: 12px;
		margin-top: 24px;
		flex-wrap: wrap;
	}

	.side-btn {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		padding: 12px 18px;
		border-radius: 14px;
		text-decoration: none;
		border: 1px solid transparent;
		font-size: 14px;
		font-weight: 700;
		cursor: pointer;
	}

	.side-btn-light {
		background: #f8fafc;
		color: #334155;
		border-color: #dbe2ea;
	}

	.side-btn-primary {
		background: linear-gradient(135deg, #2563eb 0%, #4338ca 100%);
		color: #fff;
	}

	.side-btn-danger {
		background: #fef2f2;
		color: #b91c1c;
		border-color: #fecaca;
	}

	@media (max-width: 980px) {
		.user-edit-layout {
			grid-template-columns: 1fr;
		}

		.user-edit-profile-card {
			position: static;
		}
	}

	@media (max-width: 720px) {
		.user-edit-panel,
		.user-edit-profile-card {
			padding: 20px;
		}

		.user-edit-head {
			flex-direction: column;
			align-items: stretch;
		}

		.user-edit-grid {
			grid-template-columns: 1fr;
		}
	}
</style>
