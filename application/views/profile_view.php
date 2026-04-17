<?php
$profile_image = !empty($user->profile_image) ? $user->profile_image : 'assets/images/default-profile.svg';
$profile_image_src = preg_match('/^https?:\/\//i', $profile_image) ? $profile_image : base_url($profile_image);
?>

<?php if ($this->session->flashdata('error')): ?>
	<div class="alert-message alert-error">
		<span class="alert-icon">⚠️</span>
		<span><?php echo $this->session->flashdata('error'); ?></span>
		<button class="alert-close" onclick="this.parentElement.remove()">✕</button>
	</div>
<?php endif; ?>

<?php if ($this->session->flashdata('success')): ?>
	<div class="alert-message alert-success">
		<span class="alert-icon">✓</span>
		<span><?php echo $this->session->flashdata('success'); ?></span>
		<button class="alert-close" onclick="this.parentElement.remove()">✕</button>
	</div>
<?php endif; ?>

<?php echo validation_errors('<div class="alert-message alert-error"><span class="alert-icon">⚠️</span><span>', '</span><button class="alert-close" onclick="this.parentElement.remove()">✕</button></div>'); ?>

<style>
	:root {
		--primary: #6366f1;
		--primary-dark: #4f46e5;
		--success: #10b981;
		--error: #ef4444;
		--warning: #f59e0b;
		--border: #e5e7eb;
		--text: #111827;
		--text-light: #6b7280;
		--bg: #ffffff;
		--bg-light: #f9fafb;
		--shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1);
		--shadow-md: 0 4px 12px rgba(0, 0, 0, 0.08);
		--shadow-lg: 0 10px 30px rgba(0, 0, 0, 0.12);
	}

	/* Alerts */
	.alert-message {
		display: flex;
		align-items: center;
		gap: 12px;
		padding: 14px 18px;
		border-radius: 12px;
		margin-bottom: 20px;
		font-size: 14px;
		font-weight: 500;
		animation: slideDown 0.3s ease;
	}

	.alert-error {
		background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
		border-left: 4px solid var(--error);
		color: #991b1b;
	}

	.alert-success {
		background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
		border-left: 4px solid var(--success);
		color: #166534;
	}

	.alert-icon {
		font-size: 18px;
		flex-shrink: 0;
	}

	.alert-close {
		margin-left: auto;
		background: none;
		border: none;
		font-size: 20px;
		cursor: pointer;
		opacity: 0.5;
		padding: 4px;
		width: 28px;
		height: 28px;
		display: flex;
		align-items: center;
		justify-content: center;
		border-radius: 50%;
		transition: all 0.2s;
		color: inherit;
	}

	.alert-close:hover {
		opacity: 1;
		background: rgba(0, 0, 0, 0.1);
	}

	@keyframes slideDown {
		from {
			opacity: 0;
			transform: translateY(-10px);
		}

		to {
			opacity: 1;
			transform: translateY(0);
		}
	}

	/* Layout */
	.profile-page-grid {
		display: grid;
		grid-template-columns: 340px 1fr;
		gap: 28px;
		margin-top: 0;
	}

	/* Profile Summary Card */
	.profile-summary-card {
		background: white;
		border-radius: 20px;
		overflow: hidden;
		box-shadow: var(--shadow-md);
		border: 1px solid var(--border);
		position: sticky;
		top: 20px;
		height: fit-content;
	}

	.profile-cover {
		height: 120px;
		background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
		position: relative;
	}

	.profile-cover::before {
		content: "";
		position: absolute;
		inset: 0;
		background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
		opacity: 0.3;
	}

	.avatar-large {
		width: 130px;
		height: 130px;
		margin: -65px auto 0;
		position: relative;
		border-radius: 50%;
		padding: 6px;
		background: white;
		box-shadow: var(--shadow-lg);
	}

	.avatar-large img {
		width: 100%;
		height: 100%;
		border-radius: 50%;
		object-fit: cover;
		border: 4px solid white;
	}

	.avatar-upload-btn {
		position: absolute;
		bottom: 8px;
		right: 8px;
		width: 38px;
		height: 38px;
		background: linear-gradient(135deg, var(--primary) 0%, #8b5cf6 100%);
		border-radius: 50%;
		display: flex;
		align-items: center;
		justify-content: center;
		cursor: pointer;
		font-size: 18px;
		box-shadow: var(--shadow-md);
		transition: all 0.3s ease;
		border: 3px solid white;
	}

	.avatar-upload-btn:hover {
		transform: scale(1.1) rotate(5deg);
		box-shadow: var(--shadow-lg);
	}

	.avatar-upload-input {
		display: none;
	}

	.profile-summary-card h3 {
		text-align: center;
		margin: 20px 0 6px;
		font-size: 24px;
		color: var(--text);
		font-weight: 700;
	}

	.profile-summary-card>p {
		text-align: center;
		color: var(--text-light);
		font-size: 14px;
		margin: 0 0 24px;
	}

	.profile-stat-list {
		padding: 24px;
		background: var(--bg-light);
		border-top: 1px solid var(--border);
	}

	.profile-stat-item {
		padding: 16px;
		background: white;
		border-radius: 12px;
		margin-bottom: 12px;
		border: 1px solid var(--border);
		transition: all 0.2s ease;
	}

	.profile-stat-item:last-child {
		margin-bottom: 0;
	}

	.profile-stat-item:hover {
		border-color: var(--primary);
		box-shadow: var(--shadow-sm);
		transform: translateX(4px);
	}

	.profile-stat-item strong {
		display: block;
		font-size: 12px;
		color: var(--text-light);
		text-transform: uppercase;
		letter-spacing: 0.5px;
		margin-bottom: 6px;
		font-weight: 600;
	}

	.profile-stat-item div {
		color: var(--text);
		font-size: 15px;
		font-weight: 500;
		word-break: break-word;
	}

	/* Form Panels */
	.form-panel,
	.password-panel {
		background: white;
		padding: 32px;
		border-radius: 20px;
		box-shadow: var(--shadow-md);
		border: 1px solid var(--border);
		margin-bottom: 24px;
	}

	.password-panel {
		margin-bottom: 0;
	}

	.section-title {
		font-size: 22px;
		font-weight: 700;
		color: var(--text);
		margin: 0 0 8px;
		display: flex;
		align-items: center;
		gap: 10px;
	}

	.section-title::before {
		content: "";
		width: 4px;
		height: 24px;
		background: linear-gradient(135deg, var(--primary) 0%, #8b5cf6 100%);
		border-radius: 2px;
	}

	.form-panel>p,
	.password-panel>p {
		color: var(--text-light);
		margin: 0 0 28px;
		font-size: 14px;
		padding-left: 14px;
	}

	/* Form Grid */
	.form-grid {
		display: grid;
		grid-template-columns: repeat(2, 1fr);
		gap: 20px;
		margin-bottom: 28px;
	}

	.form-group {
		display: flex;
		flex-direction: column;
		gap: 8px;
	}

	.form-group.full-width {
		grid-column: 1 / -1;
	}

	.form-group label {
		font-weight: 600;
		color: var(--text);
		font-size: 14px;
		display: flex;
		align-items: center;
		gap: 6px;
	}

	.form-group label::before {
		content: "•";
		color: var(--primary);
		font-size: 18px;
	}

	.form-group input,
	.form-group textarea {
		width: 100%;
		padding: 13px 16px;
		border: 2px solid var(--border);
		border-radius: 12px;
		font-size: 15px;
		color: var(--text);
		background: var(--bg-light);
		transition: all 0.2s ease;
		font-family: inherit;
	}

	.form-group textarea {
		min-height: 100px;
		resize: vertical;
		line-height: 1.6;
	}

	.form-group input:hover,
	.form-group textarea:hover {
		border-color: var(--primary);
		background: white;
	}

	.form-group input:focus,
	.form-group textarea:focus {
		outline: none;
		border-color: var(--primary);
		background: white;
		box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
	}

	.form-group input::placeholder,
	.form-group textarea::placeholder {
		color: #9ca3af;
	}

	/* Buttons */
	.btn-primary {
		padding: 14px 32px;
		background: linear-gradient(135deg, var(--primary) 0%, #8b5cf6 100%);
		color: white;
		border: none;
		border-radius: 12px;
		font-size: 16px;
		font-weight: 700;
		cursor: pointer;
		transition: all 0.3s ease;
		box-shadow: 0 4px 16px rgba(99, 102, 241, 0.3);
		display: inline-flex;
		align-items: center;
		gap: 8px;
	}

	.btn-primary:hover {
		transform: translateY(-2px);
		box-shadow: 0 8px 24px rgba(99, 102, 241, 0.4);
	}

	.btn-primary:active {
		transform: translateY(0);
	}

	.btn-primary::before {
		content: "💾";
		font-size: 18px;
	}

	/* Password Strength Indicator */
	.password-strength {
		height: 4px;
		background: var(--border);
		border-radius: 2px;
		overflow: hidden;
		margin-top: 8px;
	}

	.password-strength-bar {
		height: 100%;
		width: 0%;
		transition: all 0.3s ease;
		border-radius: 2px;
	}

	.strength-weak {
		background: var(--error);
		width: 33%;
	}

	.strength-medium {
		background: var(--warning);
		width: 66%;
	}

	.strength-strong {
		background: var(--success);
		width: 100%;
	}

	/* Info Box */
	.info-box {
		background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
		border-left: 4px solid var(--primary);
		padding: 16px 20px;
		border-radius: 12px;
		margin-bottom: 20px;
		display: flex;
		align-items: start;
		gap: 12px;
	}

	.info-box-icon {
		font-size: 20px;
		flex-shrink: 0;
	}

	.info-box-content {
		flex: 1;
	}

	.info-box-content strong {
		display: block;
		color: var(--primary);
		font-size: 14px;
		margin-bottom: 4px;
	}

	.info-box-content p {
		margin: 0;
		color: #1e40af;
		font-size: 13px;
		line-height: 1.5;
	}

	/* Responsive */
	@media (max-width: 1024px) {
		.profile-page-grid {
			grid-template-columns: 1fr;
		}

		.profile-summary-card {
			position: relative;
			top: 0;
		}
	}

	@media (max-width: 640px) {
		.form-grid {
			grid-template-columns: 1fr;
		}

		.form-panel,
		.password-panel {
			padding: 24px 20px;
		}

		.profile-stat-list {
			padding: 20px;
		}

		.avatar-large {
			width: 110px;
			height: 110px;
			margin-top: -55px;
		}

		.section-title {
			font-size: 20px;
		}
	}

	/* Loading State */
	.btn-primary:disabled {
		opacity: 0.6;
		cursor: not-allowed;
		transform: none !important;
	}

	.btn-primary.loading::before {
		content: "";
		width: 16px;
		height: 16px;
		border: 2px solid rgba(255, 255, 255, 0.3);
		border-top-color: white;
		border-radius: 50%;
		animation: spin 0.6s linear infinite;
	}

	@keyframes spin {
		to {
			transform: rotate(360deg);
		}
	}

	/* Input Icons */
	.form-group.with-icon {
		position: relative;
	}

	.form-group.with-icon input {
		padding-left: 44px;
	}

	.input-icon {
		position: absolute;
		left: 14px;
		top: 38px;
		font-size: 18px;
		color: var(--text-light);
		pointer-events: none;
	}
</style>

<div class="profile-page-grid">
	<section class="profile-summary-card">
		<div class="profile-cover"></div>
		<div class="avatar-large">
			<img id="profilePreviewMain" src="<?php echo html_escape($profile_image_src); ?>" alt="Profile image">
			<label class="avatar-upload-btn" for="profile_image_file" title="Upload profile image">
				<i class="fa-solid fa-camera"></i>
			</label>
		</div>
		<h3><?php echo html_escape($user->name); ?></h3>
		<p><?php echo html_escape($user->email); ?></p>

		<div class="profile-stat-list">
			<div class="profile-stat-item">
				<strong>📱 Mobile Number</strong>
				<div><?php echo html_escape($user->mobile); ?></div>
			</div>
			<div class="profile-stat-item">
				<strong>📍 Address</strong>
				<div><?php echo !empty($user->address) ? html_escape($user->address) : 'Not updated yet'; ?></div>
			</div>
		</div>
	</section>

	<div>
		<section class="form-panel">
			<h3 class="section-title">Profile Details</h3>
			<p>Update your personal information and profile picture</p>

			<div class="info-box">
				<span class="info-box-icon">💡</span>
				<div class="info-box-content">
					<strong>Profile Picture Tips</strong>
					<p>Click the camera icon on your avatar to upload a new photo. Supported formats: JPG, PNG, GIF, WebP (Max 2MB)</p>
				</div>
			</div>

			<form method="post" action="<?php echo site_url('profile/update'); ?>" enctype="multipart/form-data" id="profileForm">
				<input class="avatar-upload-input" type="file" id="profile_image_file" name="profile_image_file" accept=".jpg,.jpeg,.png,.gif,.webp" data-preview-targets="profilePreviewMain,profilePreviewTop">

				<div class="form-grid">
					<div class="form-group">
						<label for="name">Full Name</label>
						<input type="text" id="name" name="name" value="<?php echo set_value('name', $user->name); ?>" placeholder="Enter your full name" required>
					</div>

					<div class="form-group">
						<label for="mobile">Mobile Number</label>
						<input type="text" id="mobile" name="mobile" value="<?php echo set_value('mobile', $user->mobile); ?>" placeholder="+1 (555) 000-0000" required>
					</div>

					<div class="form-group full-width">
						<label for="email">Email Address</label>
						<input type="email" id="email" name="email" value="<?php echo set_value('email', $user->email); ?>" placeholder="your.email@example.com" required>
					</div>

					<div class="form-group full-width">
						<label for="address">Address</label>
						<textarea id="address" name="address" placeholder="Enter your complete address"><?php echo set_value('address', $user->address); ?></textarea>
					</div>
				</div>

				<button class="btn-primary" type="submit">Update Profile</button>
			</form>
		</section>

		<section class="password-panel">
			<h3 class="section-title">Change Password</h3>
			<p>Set any password you want, but it must be at least 6 characters long.</p>

			<div class="info-box">
				<span class="info-box-icon">🔒</span>
				<div class="info-box-content">
					<strong>Password Rule</strong>
					<p>Password must be at least 6 characters long.</p>
				</div>
			</div>

			<form method="post" action="<?php echo site_url('profile/change-password'); ?>" id="passwordForm">
				<div class="form-grid">
					<div class="form-group full-width">
						<label for="new_password">New Password</label>
						<input type="password" id="new_password" name="new_password" placeholder="Enter new password" required minlength="6">
					</div>

					<div class="form-group">
						<label for="password_note">Password Rule</label>
						<input type="text" id="password_note" value="Minimum 6 characters" readonly>
						<div class="password-strength">
							<div class="password-strength-bar" id="strengthBar"></div>
						</div>
					</div>

					<div class="form-group">
						<label for="password_note_two">Simple Rule</label>
						<input type="text" id="password_note_two" value="No extra validation" readonly>
					</div>
				</div>

				<button class="btn-primary" type="submit">Change Password</button>
			</form>
		</section>
	</div>
</div>

<script>
	document.addEventListener('DOMContentLoaded', function() {
		// Profile image preview
		const fileInput = document.getElementById('profile_image_file');
		if (fileInput) {
			fileInput.addEventListener('change', function(e) {
				const file = e.target.files[0];
				if (file) {
					// Validate file size (2MB)
					if (file.size > 2 * 1024 * 1024) {
						if (typeof userSwalAlert === 'function') {
							userSwalAlert('File size must be less than 2MB', 'error');
						}
						this.value = '';
						return;
					}

					// Validate file type
					const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
					if (!validTypes.includes(file.type)) {
						if (typeof userSwalAlert === 'function') {
							userSwalAlert('Please upload a valid image file (JPG, PNG, GIF, or WebP)', 'error');
						}
						this.value = '';
						return;
					}

					const reader = new FileReader();
					reader.onload = function(event) {
						const targets = fileInput.dataset.previewTargets.split(',');
						targets.forEach(targetId => {
							const img = document.getElementById(targetId);
							if (img) {
								img.src = event.target.result;
							}
						});
					};
					reader.readAsDataURL(file);
				}
			});
		}

		// Password strength indicator
		const newPassword = document.getElementById('new_password');
		const strengthBar = document.getElementById('strengthBar');

		if (newPassword && strengthBar) {
			newPassword.addEventListener('input', function() {
				const password = this.value;
				let strength = 0;

				if (password.length >= 6) strength++;
				if (password.length >= 8) strength++;
				if (password.length >= 10) strength++;
				if (password.length >= 12) strength++;

				strengthBar.className = 'password-strength-bar';

				if (strength <= 1) {
					strengthBar.classList.add('strength-weak');
				} else if (strength <= 3) {
					strengthBar.classList.add('strength-medium');
				} else {
					strengthBar.classList.add('strength-strong');
				}
			});
		}

		const passwordForm = document.getElementById('passwordForm');

		if (passwordForm) {
			passwordForm.addEventListener('submit', function(e) {
				if (newPassword.value.length < 6) {
					e.preventDefault();
					if (typeof userSwalAlert === 'function') {
						userSwalAlert('Password must be at least 6 characters long!', 'error');
					}
					newPassword.focus();
				}
			});
		}

		// Form submission loading state
		const forms = document.querySelectorAll('form');
		forms.forEach(form => {
			form.addEventListener('submit', function() {
				const btn = this.querySelector('.btn-primary');
				if (btn) {
					btn.classList.add('loading');
					btn.disabled = true;
				}
			});
		});

		// Auto-hide alerts
		setTimeout(() => {
			document.querySelectorAll('.alert-message').forEach(alert => {
				alert.style.transition = 'all 0.3s ease';
				alert.style.opacity = '0';
				alert.style.transform = 'translateY(-10px)';
				setTimeout(() => alert.remove(), 300);
			});
		}, 5000);

		// Mobile number formatting (optional)
		const mobileInput = document.getElementById('mobile');
		if (mobileInput) {
			mobileInput.addEventListener('input', function(e) {
				let value = this.value.replace(/\D/g, '');
				if (value.length > 10) value = value.slice(0, 10);
				this.value = value;
			});
		}
	});
</script>
