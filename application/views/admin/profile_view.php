<?php
$profile_image = !empty($admin->profile_image) ? $admin->profile_image : 'assets/images/default-profile.svg';
$profile_image_src = preg_match('/^https?:\/\//i', $profile_image) ? $profile_image : base_url($profile_image);
?>

<?php if ($this->session->flashdata('error')): ?>
	<div class="message error">
		<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M10 0C4.48 0 0 4.48 0 10C0 15.52 4.48 20 10 20C15.52 20 20 15.52 20 10C20 4.48 15.52 0 10 0ZM11 15H9V13H11V15ZM11 11H9V5H11V11Z" fill="currentColor" />
		</svg>
		<span><?php echo $this->session->flashdata('error'); ?></span>
		<button class="message-close" onclick="this.parentElement.remove()">✕</button>
	</div>
<?php endif; ?>

<?php if ($this->session->flashdata('success')): ?>
	<div class="message success">
		<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M10 0C4.48 0 0 4.48 0 10C0 15.52 4.48 20 10 20C15.52 20 20 15.52 20 10C20 4.48 15.52 0 10 0ZM8 15L3 10L4.41 8.59L8 12.17L15.59 4.58L17 6L8 15Z" fill="currentColor" />
		</svg>
		<span><?php echo $this->session->flashdata('success'); ?></span>
		<button class="message-close" onclick="this.parentElement.remove()">✕</button>
	</div>
<?php endif; ?>

<?php echo validation_errors('<div class="message error"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10 0C4.48 0 0 4.48 0 10C0 15.52 4.48 20 10 20C15.52 20 20 15.52 20 10C20 4.48 15.52 0 10 0ZM11 15H9V13H11V15ZM11 11H9V5H11V11Z" fill="currentColor" /></svg><span>', '</span><button class="message-close" onclick="this.parentElement.remove()">✕</button></div>'); ?>

<style>
	* {
		box-sizing: border-box;
	}

	:root {
		--f-body: 'Roboto', sans-serif;
	}

	/* Flash Messages */
	.message {
		display: flex;
		align-items: center;
		gap: 12px;
		padding: 16px 20px;
		border-radius: 16px;
		margin-bottom: 24px;
		font-weight: 500;
		animation: slideDown 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
		box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
		position: relative;
		overflow: hidden;
	}

	.message::before {
		content: '';
		position: absolute;
		left: 0;
		top: 0;
		height: 100%;
		width: 4px;
		background: currentColor;
	}

	.message-close {
		margin-left: auto;
		background: none;
		border: none;
		font-size: 20px;
		cursor: pointer;
		opacity: 0.6;
		transition: opacity 0.2s;
		padding: 4px 8px;
		line-height: 1;
		color: inherit;
	}

	.message-close:hover {
		opacity: 1;
	}

	@keyframes slideDown {
		from {
			opacity: 0;
			transform: translateY(-20px);
		}

		to {
			opacity: 1;
			transform: translateY(0);
		}
	}

	.message.error {
		background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
		border: 1px solid #fecaca;
		color: #991b1b;
	}

	.message.success {
		background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
		border: 1px solid #bbf7d0;
		color: #166534;
	}

	/* Profile Page Grid */
	.profile-page-grid {
		display: grid;
		grid-template-columns: 380px 1fr;
		gap: 32px;
		align-items: start;
	}

	/* Profile Summary Card */
	.profile-summary-card {
		background: #ffffff;
		border-radius: 28px;
		overflow: hidden;
		box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
		border: 1px solid #e5e7eb;
		position: sticky;
		top: 20px;
		transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
	}

	.profile-summary-card:hover {
		box-shadow: 0 16px 48px rgba(0, 0, 0, 0.12);
		transform: translateY(-4px);
	}

	.profile-cover {
		height: 140px;
		background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
		position: relative;
		overflow: hidden;
	}

	.profile-cover::before {
		content: '';
		position: absolute;
		top: -50%;
		right: -50%;
		width: 200%;
		height: 200%;
		background: radial-gradient(circle, rgba(255, 255, 255, 0.2) 0%, transparent 70%);
		animation: shimmer 8s ease-in-out infinite;
	}

	@keyframes shimmer {

		0%,
		100% {
			transform: translate(-25%, -25%) rotate(0deg);
		}

		50% {
			transform: translate(-25%, -25%) rotate(180deg);
		}
	}

	.avatar-large {
		width: 140px;
		height: 140px;
		border-radius: 50%;
		margin: -70px auto 0;
		position: relative;
		border: 6px solid #ffffff;
		box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
		overflow: hidden;
		transition: all 0.4s ease;
		background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
	}

	.avatar-large:hover {
		transform: scale(1.05);
		box-shadow: 0 12px 32px rgba(102, 126, 234, 0.3);
	}

	.avatar-large img {
		width: 100%;
		height: 100%;
		object-fit: cover;
		display: block;
	}

	.avatar-upload-btn {
		position: absolute;
		bottom: 4px;
		right: 4px;
		width: 40px;
		height: 40px;
		border-radius: 50%;
		background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
		color: white;
		display: flex;
		align-items: center;
		justify-content: center;
		cursor: pointer;
		font-size: 20px;
		transition: all 0.3s ease;
		box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
	}

	.avatar-upload-btn:hover {
		transform: scale(1.1) rotate(10deg);
		box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
	}

	.avatar-upload-input {
		display: none;
	}

	.profile-summary-card h3 {
		text-align: center;
		margin: 20px 0 8px;
		font-size: 26px;
		font-weight: 800;
		color: #111827;
		background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
		-webkit-background-clip: text;
		-webkit-text-fill-color: transparent;
		background-clip: text;
	}

	.profile-summary-card>p {
		text-align: center;
		color: #6b7280;
		font-size: 14px;
		margin: 0 0 24px;
		padding: 0 24px;
	}

	/* Profile Stats */
	.profile-stat-list {
		padding: 24px;
		background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
		margin-top: 24px;
	}

	.profile-stat-item {
		padding: 16px 0;
		border-bottom: 2px solid #e5e7eb;
	}

	.profile-stat-item:last-child {
		border-bottom: none;
	}

	.profile-stat-item strong {
		display: flex;
		align-items: center;
		gap: 8px;
		font-size: 13px;
		font-weight: 700;
		color: #6b7280;
		text-transform: uppercase;
		letter-spacing: 0.5px;
		margin-bottom: 8px;
	}

	.profile-stat-item strong::before {
		content: '•';
		color: #667eea;
		font-size: 20px;
	}

	.profile-stat-item div {
		font-size: 15px;
		font-weight: 600;
		color: #111827;
		padding-left: 16px;
		line-height: 1.6;
	}

	/* Form Panels */
	.form-panel,
	.password-panel {
		background: #ffffff;
		border-radius: 24px;
		padding: 36px;
		box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
		border: 1px solid #e5e7eb;
		transition: all 0.3s ease;
		position: relative;
		overflow: hidden;
		margin-bottom: 28px;
	}

	.form-panel::before,
	.password-panel::before {
		content: '';
		position: absolute;
		top: 0;
		left: 0;
		right: 0;
		height: 4px;
		background: linear-gradient(90deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
		opacity: 0;
		transition: opacity 0.3s;
	}

	.form-panel:hover::before,
	.password-panel:hover::before {
		opacity: 1;
	}

	.form-panel:hover,
	.password-panel:hover {
		box-shadow: 0 12px 32px rgba(0, 0, 0, 0.08);
		border-color: #667eea;
		transform: translateY(-2px);
	}

	.section-title {
		font-size: 24px;
		font-weight: 800;
		color: #111827;
		margin: 0 0 8px 0;
		display: flex;
		align-items: center;
		gap: 12px;
		background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
		-webkit-background-clip: text;
		-webkit-text-fill-color: transparent;
		background-clip: text;
	}

	.section-title::before {
		content: '👤';
		font-size: 28px;
		-webkit-text-fill-color: initial;
		filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
	}

	.password-panel .section-title::before {
		content: '🔒';
	}

	.form-panel>p,
	.password-panel>p {
		color: #6b7280;
		font-size: 14px;
		margin: 0 0 28px 0;
		line-height: 1.6;
	}

	/* Form Grid */
	.form-grid {
		display: grid;
		grid-template-columns: repeat(2, 1fr);
		gap: 24px;
		margin-bottom: 28px;
	}

	.form-group {
		display: flex;
		flex-direction: column;
	}

	.form-group.full-width {
		grid-column: 1 / -1;
	}

	.form-group label {
		font-size: 14px;
		font-weight: 700;
		color: #374151;
		margin-bottom: 10px;
		display: flex;
		align-items: center;
		gap: 8px;
	}

	.form-group label::before {
		content: '•';
		color: #667eea;
		font-size: 20px;
	}

	.form-group input,
	.form-group textarea {
		width: 100%;
		padding: 14px 18px;
		border: 2px solid #e5e7eb;
		border-radius: 14px;
		font-size: 15px;
		transition: all 0.3s ease;
		background: #f9fafb;
		color: #111827;
		font-weight: 500;
		font-family: var(--f-body);
	}

	.form-group input:focus,
	.form-group textarea:focus {
		outline: none;
		border-color: #667eea;
		background: #ffffff;
		box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
		transform: translateY(-2px);
	}

	.form-group input::placeholder,
	.form-group textarea::placeholder {
		color: #9ca3af;
	}

	.form-group textarea {
		resize: vertical;
		min-height: 100px;
		line-height: 1.6;
	}

	/* Buttons */
	.btn-primary {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		gap: 10px;
		width: 100%;
		padding: 16px 28px;
		border-radius: 14px;
		font-weight: 700;
		font-size: 16px;
		text-decoration: none;
		cursor: pointer;
		border: none;
		background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
		color: white;
		transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
		box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
		position: relative;
		overflow: hidden;
		font-family: var(--f-body);
	}

	.btn-primary::before {
		content: '💾';
		font-size: 20px;
		transition: transform 0.3s;
	}

	.btn-primary::after {
		content: '';
		position: absolute;
		top: 50%;
		left: 50%;
		width: 0;
		height: 0;
		border-radius: 50%;
		background: rgba(255, 255, 255, 0.3);
		transform: translate(-50%, -50%);
		transition: width 0.6s, height 0.6s;
	}

	.btn-primary:hover {
		transform: translateY(-4px) scale(1.02);
		box-shadow: 0 12px 32px rgba(102, 126, 234, 0.5);
	}

	.btn-primary:hover::before {
		transform: scale(1.2) rotate(-10deg);
	}

	.btn-primary:hover::after {
		width: 300px;
		height: 300px;
	}

	.btn-primary:active {
		transform: translateY(-2px) scale(0.98);
	}

	.password-panel .btn-primary::before {
		content: '🔐';
	}

	/* Profile Image Preview */
	.avatar-large.uploading::after {
		content: '';
		position: absolute;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		background: rgba(0, 0, 0, 0.6);
		display: flex;
		align-items: center;
		justify-content: center;
	}

	/* Loading Spinner */
	.spinner {
		width: 40px;
		height: 40px;
		border: 4px solid rgba(255, 255, 255, 0.3);
		border-top-color: #ffffff;
		border-radius: 50%;
		animation: spin 1s linear infinite;
		position: absolute;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
		display: none;
	}

	.avatar-large.uploading .spinner {
		display: block;
	}

	@keyframes spin {
		to {
			transform: translate(-50%, -50%) rotate(360deg);
		}
	}

	/* Responsive Design */
	@media (max-width: 1200px) {
		.profile-page-grid {
			grid-template-columns: 340px 1fr;
			gap: 24px;
		}
	}

	@media (max-width: 992px) {
		.profile-page-grid {
			grid-template-columns: 1fr;
		}

		.profile-summary-card {
			position: static;
		}

		.form-grid {
			grid-template-columns: 1fr;
		}
	}

	@media (max-width: 768px) {

		.form-panel,
		.password-panel {
			padding: 28px;
		}

		.profile-cover {
			height: 120px;
		}

		.avatar-large {
			width: 120px;
			height: 120px;
			margin-top: -60px;
		}

		.profile-summary-card h3 {
			font-size: 22px;
		}

		.section-title {
			font-size: 20px;
		}
	}

	@media (max-width: 480px) {

		.form-panel,
		.password-panel {
			padding: 20px;
			border-radius: 20px;
		}

		.profile-summary-card {
			border-radius: 20px;
		}

		.profile-cover {
			height: 100px;
		}

		.avatar-large {
			width: 100px;
			height: 100px;
			margin-top: -50px;
			border-width: 4px;
		}

		.avatar-upload-btn {
			width: 32px;
			height: 32px;
			font-size: 16px;
		}

		.profile-summary-card h3 {
			font-size: 20px;
		}

		.section-title {
			font-size: 18px;
		}

		.form-grid {
			gap: 16px;
		}

		.message {
			padding: 12px 16px;
		}
	}

	/* Input Validation States */
	.form-group input:invalid:not(:placeholder-shown),
	.form-group textarea:invalid:not(:placeholder-shown) {
		border-color: #fca5a5;
	}

	.form-group input:valid:not(:placeholder-shown),
	.form-group textarea:valid:not(:placeholder-shown) {
		border-color: #86efac;
	}

	/* Focus Within Enhancement */
	.form-group:focus-within label {
		color: #667eea;
	}

	/* Smooth Transitions */
	* {
		-webkit-font-smoothing: antialiased;
		-moz-osx-font-smoothing: grayscale;
	}

	/* Print Styles */
	@media print {

		.message,
		.avatar-upload-btn,
		.btn-primary {
			display: none;
		}

		.profile-summary-card,
		.form-panel,
		.password-panel {
			box-shadow: none;
			border: 1px solid #000;
		}
	}
</style>

<div class="profile-page-grid">
	<section class="profile-summary-card">
		<div class="profile-cover"></div>
		<div class="avatar-large" id="avatarContainer">
			<img id="profilePreviewMain" src="<?php echo html_escape($profile_image_src); ?>" alt="Admin profile image">
			<label class="avatar-upload-btn" for="profile_image_file" title="Upload profile image">📷</label>
			<div class="spinner"></div>
		</div>
		<h3><?php echo html_escape($admin->name); ?></h3>
		<p><?php echo html_escape($admin->email); ?></p>

		<div class="profile-stat-list">
			<div class="profile-stat-item">
				<strong>📱 Mobile</strong>
				<div><?php echo html_escape($admin->mobile); ?></div>
			</div>
			<div class="profile-stat-item">
				<strong>📍 Address</strong>
				<div><?php echo !empty($admin->address) ? html_escape($admin->address) : 'Not updated yet'; ?></div>
			</div>
		</div>
	</section>

	<div>
		<section class="form-panel">
			<h3 class="section-title">Admin Profile</h3>
			<p>Update your admin account details and personal information.</p>

			<form method="post" action="<?php echo site_url('admin/profile/update'); ?>" enctype="multipart/form-data" id="profileForm">
				<input class="avatar-upload-input" type="file" id="profile_image_file" name="profile_image_file" accept=".jpg,.jpeg,.png,.gif,.webp" data-preview-targets="profilePreviewMain,profilePreviewTop">

				<div class="form-grid">
					<div class="form-group">
						<label for="name">Full Name</label>
						<input type="text" id="name" name="name" value="<?php echo set_value('name', $admin->name); ?>" placeholder="Enter your full name" required>
					</div>

					<div class="form-group">
						<label for="mobile">Mobile Number</label>
						<input type="tel" id="mobile" name="mobile" value="<?php echo set_value('mobile', $admin->mobile); ?>" placeholder="+1 (555) 000-0000" pattern="[0-9+\-\s\(\)]*">
					</div>

					<div class="form-group full-width">
						<label for="email">Email Address</label>
						<input type="email" id="email" name="email" value="<?php echo set_value('email', $admin->email); ?>" placeholder="your.email@example.com" required>
					</div>

					<div class="form-group full-width">
						<label for="address">Address</label>
						<textarea id="address" name="address" placeholder="Enter your complete address" rows="3"><?php echo set_value('address', $admin->address); ?></textarea>
					</div>
				</div>

				<button class="btn-primary" type="submit">Update Profile</button>
			</form>
		</section>

		<section class="password-panel">
			<h3 class="section-title">Change Password</h3>
			<p>Ensure your account is using a strong, unique password for security.</p>

			<form method="post" action="<?php echo site_url('admin/profile/change-password'); ?>" id="passwordForm">
				<div class="form-grid">
					<div class="form-group full-width">
						<label for="current_password">Current Password</label>
						<input type="password" id="current_password" name="current_password" placeholder="Enter your current password" required autocomplete="current-password">
					</div>

					<div class="form-group">
						<label for="new_password">New Password</label>
						<input type="password" id="new_password" name="new_password" placeholder="Enter new password" required minlength="8" autocomplete="new-password">
					</div>

					<div class="form-group">
						<label for="confirm_password">Confirm Password</label>
						<input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm new password" required minlength="8" autocomplete="new-password">
					</div>
				</div>

				<button class="btn-primary" type="submit">Change Password</button>
			</form>
		</section>
	</div>
</div>

<script>
	// Auto-hide flash messages
	document.addEventListener('DOMContentLoaded', function() {
		const messages = document.querySelectorAll('.message');
		messages.forEach(message => {
			setTimeout(() => {
				message.style.animation = 'slideUp 0.3s ease-out forwards';
				setTimeout(() => message.remove(), 300);
			}, 5000);
		});

		// Add slide up animation
		const style = document.createElement('style');
		style.textContent = `
			@keyframes slideUp {
				to {
					opacity: 0;
					transform: translateY(-20px);
				}
			}
		`;
		document.head.appendChild(style);
	});

	// Profile image preview
	const profileImageInput = document.getElementById('profile_image_file');
	const avatarContainer = document.getElementById('avatarContainer');

	if (profileImageInput) {
		profileImageInput.addEventListener('change', function(e) {
			const file = e.target.files[0];
			if (file) {
				// Validate file type
				const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
				if (!validTypes.includes(file.type)) {
					if (typeof adminSwalAlert === 'function') {
						adminSwalAlert('Please select a valid image file (JPG, PNG, GIF, or WebP)', 'error');
					}
					this.value = '';
					return;
				}

				// Validate file size (max 5MB)
				if (file.size > 5 * 1024 * 1024) {
					if (typeof adminSwalAlert === 'function') {
						adminSwalAlert('Image size must be less than 5MB', 'error');
					}
					this.value = '';
					return;
				}

				// Show loading state
				avatarContainer.classList.add('uploading');

				// Preview image
				const reader = new FileReader();
				reader.onload = function(event) {
					const targets = profileImageInput.dataset.previewTargets.split(',');
					targets.forEach(targetId => {
						const img = document.getElementById(targetId);
						if (img) {
							img.src = event.target.result;
						}
					});

					// Remove loading state
					setTimeout(() => {
						avatarContainer.classList.remove('uploading');
					}, 500);
				};
				reader.readAsDataURL(file);
			}
		});
	}

	// Password confirmation validation
	const passwordForm = document.getElementById('passwordForm');
	if (passwordForm) {
		passwordForm.addEventListener('submit', function(e) {
			const newPassword = document.getElementById('new_password').value;
			const confirmPassword = document.getElementById('confirm_password').value;

			if (newPassword !== confirmPassword) {
				e.preventDefault();
				if (typeof adminSwalAlert === 'function') {
					adminSwalAlert('New password and confirmation password do not match!', 'error');
				}
				document.getElementById('confirm_password').focus();
				return false;
			}

			if (newPassword.length < 8) {
				e.preventDefault();
				if (typeof adminSwalAlert === 'function') {
					adminSwalAlert('Password must be at least 8 characters long!', 'error');
				}
				document.getElementById('new_password').focus();
				return false;
			}
		});
	}

	// Form change detection
	let profileFormChanged = false;
	const profileForm = document.getElementById('profileForm');

	if (profileForm) {
		profileForm.addEventListener('change', () => {
			profileFormChanged = true;
		});

		profileForm.addEventListener('submit', () => {
			profileFormChanged = false;
		});

		window.addEventListener('beforeunload', (e) => {
			if (profileFormChanged) {
				e.preventDefault();
				e.returnValue = '';
			}
		});
	}

	// Real-time password strength indicator
	const newPasswordInput = document.getElementById('new_password');
	if (newPasswordInput) {
		newPasswordInput.addEventListener('input', function() {
			const password = this.value;
			let strength = 0;

			if (password.length >= 8) strength++;
			if (password.match(/[a-z]/)) strength++;
			if (password.match(/[A-Z]/)) strength++;
			if (password.match(/[0-9]/)) strength++;
			if (password.match(/[^a-zA-Z0-9]/)) strength++;

			// You can add visual indicator here
			const colors = ['#ef4444', '#f97316', '#eab308', '#84cc16', '#22c55e'];
			this.style.borderColor = colors[strength - 1] || '#e5e7eb';
		});
	}

	// Phone number formatting
	const mobileInput = document.getElementById('mobile');
	if (mobileInput) {
		mobileInput.addEventListener('input', function(e) {
			// Basic phone formatting (customize as needed)
			let value = e.target.value.replace(/\D/g, '');
			if (value.length > 10) value = value.slice(0, 10);
			// You can add more sophisticated formatting here
		});
	}

	// Smooth scroll to error
	const errorMessages = document.querySelectorAll('.message.error');
	if (errorMessages.length > 0) {
		errorMessages[0].scrollIntoView({
			behavior: 'smooth',
			block: 'center'
		});
	}
</script>