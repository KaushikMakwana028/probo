<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
	<title>Register | Create Account</title>
	<style>
		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
		}

		body {
			font-family: 'Roboto', system-ui, -apple-system, 'Segoe UI', Helvetica, Arial, sans-serif;
			background: linear-gradient(135deg, #eef2ff 0%, #d9e4ff 100%);
			min-height: 100vh;
			display: flex;
			align-items: center;
			justify-content: center;
			padding: 1.5rem;
		}

		/* Main shell container */
		.auth-shell {
			width: 100%;
			max-width: 520px;
			margin: 0 auto;
			animation: fadeSlideUp 0.5s ease-out;
		}

		/* Card design - enhanced for register */
		.auth-card {
			background: rgba(255, 255, 255, 0.98);
			backdrop-filter: blur(0px);
			border-radius: 2rem;
			padding: 2rem 2rem 2.2rem 2rem;
			box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25), 0 8px 20px rgba(0, 0, 0, 0.08);
			transition: transform 0.2s ease, box-shadow 0.2s ease;
			border: 1px solid rgba(255, 255, 255, 0.6);
		}

		.auth-card:hover {
			box-shadow: 0 30px 60px -15px rgba(0, 0, 0, 0.3);
		}

		/* Brand icon / logo - enhanced with gradient and icon */
		.brand-icon {
			width: 64px;
			height: 64px;
			border-radius: 22px;
			background: linear-gradient(135deg, #2f5cff, #17a38e);
			margin-bottom: 1.6rem;
			display: flex;
			align-items: center;
			justify-content: center;
			box-shadow: 0 12px 20px -10px rgba(47, 92, 255, 0.35);
			transition: all 0.2s;
		}

		.brand-icon svg {
			width: 34px;
			height: 34px;
			stroke: white;
			stroke-width: 1.8;
			fill: none;
		}

		/* Typography enhancements */
		h1 {
			font-size: 2rem;
			font-weight: 700;
			background: linear-gradient(135deg, #1f2b4e, #2c3e66);
			background-clip: text;
			-webkit-background-clip: text;
			color: transparent;
			letter-spacing: -0.3px;
			margin-bottom: 0.5rem;
		}

		.auth-card p {
			color: #5b6e8c;
			font-size: 0.95rem;
			margin-bottom: 1.8rem;
			border-left: 3px solid #2f5cff;
			padding-left: 0.8rem;
			font-weight: 450;
			line-height: 1.4;
		}

		/* Form groups - enhanced spacing */
		.form-group {
			margin-bottom: 1.3rem;
		}

		label {
			display: block;
			font-weight: 600;
			font-size: 0.85rem;
			margin-bottom: 0.5rem;
			color: #1f2a44;
			letter-spacing: -0.2px;
		}

		input {
			width: 100%;
			padding: 0.9rem 1rem;
			font-size: 1rem;
			font-family: inherit;
			border: 1.5px solid #e2e8f0;
			border-radius: 1.2rem;
			background: #ffffff;
			transition: all 0.2s ease;
			outline: none;
			color: #0f172a;
		}

		input:focus {
			border-color: #2f5cff;
			box-shadow: 0 0 0 4px rgba(47, 92, 255, 0.12);
			background-color: #fefefe;
		}

		input::placeholder {
			color: #b9c2d4;
			font-weight: 400;
			font-size: 0.9rem;
		}

		/* Button - primary action */
		.btn-primary {
			width: 100%;
			background: linear-gradient(105deg, #2f5cff, #1f4ad5);
			border: none;
			padding: 0.9rem 1rem;
			border-radius: 1.8rem;
			font-weight: 700;
			font-size: 1rem;
			font-family: inherit;
			color: white;
			cursor: pointer;
			transition: all 0.2s ease;
			margin-top: 0.6rem;
			margin-bottom: 1.2rem;
			box-shadow: 0 6px 14px rgba(47, 92, 255, 0.3);
			letter-spacing: 0.3px;
			display: flex;
			align-items: center;
			justify-content: center;
			gap: 8px;
		}

		.btn-primary:hover {
			background: linear-gradient(105deg, #1f4ad5, #103bb0);
			transform: translateY(-2px);
			box-shadow: 0 12px 24px -8px rgba(47, 92, 255, 0.45);
		}

		.btn-primary:active {
			transform: translateY(1px);
			transition: 0.05s;
		}

		/* Link text styling */
		.link-text {
			text-align: center;
			font-size: 0.9rem;
			color: #4a5a7a;
			border-top: 1px solid #edf2f7;
			padding-top: 1.4rem;
			margin-top: 0.2rem;
		}

		.link-text a {
			color: #2f5cff;
			text-decoration: none;
			font-weight: 600;
			transition: color 0.2s;
		}

		.link-text a:hover {
			color: #103bb0;
			text-decoration: underline;
		}

		/* Message boxes - error & success with icons */
		.message {
			padding: 0.85rem 1rem;
			border-radius: 1.2rem;
			margin-bottom: 1.4rem;
			font-size: 0.85rem;
			font-weight: 500;
			display: flex;
			align-items: center;
			gap: 10px;
			backdrop-filter: blur(4px);
			line-height: 1.4;
		}

		.message.error {
			background: #fff1f0;
			border-left: 4px solid #e53e3e;
			color: #b91c1c;
		}

		.message.success {
			background: #e6fffa;
			border-left: 4px solid #17a38e;
			color: #0e6b5c;
		}

		/* Validation errors from CodeIgniter (lists) */
		.message.error ul,
		.message.error li {
			background: transparent;
			list-style: none;
			margin: 0;
			padding: 0;
		}

		.message.error li {
			margin-bottom: 2px;
		}

		/* Responsive adjustments */
		@media (max-width: 560px) {
			.auth-card {
				padding: 1.6rem 1.3rem 1.8rem 1.3rem;
				border-radius: 1.6rem;
			}

			h1 {
				font-size: 1.8rem;
			}

			input {
				padding: 0.8rem 0.9rem;
			}

			.brand-icon {
				width: 56px;
				height: 56px;
			}

			.brand-icon svg {
				width: 30px;
				height: 30px;
			}
		}

		/* Animation */
		@keyframes fadeSlideUp {
			from {
				opacity: 0;
				transform: translateY(20px);
			}

			to {
				opacity: 1;
				transform: translateY(0);
			}
		}

		/* Additional polish for floating labels feel */
		.form-group {
			position: relative;
		}

		/* subtle icon hints for inputs (just visual enhancement) */
		input#name {
			background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="%23a0aec0" stroke-width="1.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>');
			background-repeat: no-repeat;
			background-position: left 1rem center;
			padding-left: 2.8rem;
		}

		input#mobile {
			background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="%23a0aec0" stroke-width="1.5"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>');
			background-repeat: no-repeat;
			background-position: left 1rem center;
			padding-left: 2.8rem;
		}

		input#email {
			background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="%23a0aec0" stroke-width="1.5"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>');
			background-repeat: no-repeat;
			background-position: left 1rem center;
			padding-left: 2.8rem;
		}

		input#password {
			background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="%23a0aec0" stroke-width="1.5"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>');
			background-repeat: no-repeat;
			background-position: left 1rem center;
			padding-left: 2.8rem;
		}

		/* Remove duplicate icons on focus (preserve) */
		input:focus {
			background-image: none;
			padding-left: 1rem;
		}

		/* But we also want to handle placeholder when focused: better UX */
		@media (max-width: 480px) {
			input {
				background-image: none !important;
				padding-left: 1rem !important;
			}
		}
	</style>
</head>

<body>
	<div class="auth-shell">
		<div class="auth-card">
			<!-- Enhanced brand icon with user-plus symbol for registration -->
			<div class="brand-icon">
				<svg viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke="white" stroke-linecap="round" />
					<circle cx="12" cy="7" r="4" stroke="white" />
					<line x1="19" y1="2" x2="19" y2="6" stroke="white" stroke-linecap="round" />
					<line x1="17" y1="4" x2="21" y2="4" stroke="white" stroke-linecap="round" />
				</svg>
			</div>
			<h1>Create account</h1>
			<p>Join us today — fill in your details to get started.</p>

			<!-- CodeIgniter validation errors (with enhanced styling) -->
			<?php echo validation_errors('<div class="message error"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>', '</div>'); ?>

			<form method="post" action="<?php echo site_url('login/store_register'); ?>">
				<div class="form-group">
					<label for="name">Full name</label>
					<input type="text" id="name" name="name" value="<?php echo set_value('name'); ?>" placeholder="e.g., John Doe" autocomplete="name">
				</div>

				<div class="form-group">
					<label for="mobile">Mobile number</label>
					<input type="text" id="mobile" name="mobile" value="<?php echo set_value('mobile'); ?>" placeholder="+91 98765 43210" autocomplete="tel">
				</div>

				<div class="form-group">
					<label for="email">Email</label>
					<input type="email" id="email" name="email" value="<?php echo set_value('email'); ?>" placeholder="hello@example.com" autocomplete="email">
				</div>

				<div class="form-group">
					<label for="password">Password</label>
					<input type="password" id="password" name="password" placeholder="Create a strong password" autocomplete="new-password">
				</div>

				<div class="form-group">
					<label for="referral_code">Referral Code</label>
					<input type="text" id="referral_code" name="referral_code" value="<?php echo set_value('referral_code'); ?>" placeholder="Optional referral code">
				</div>

				<button class="btn-primary" type="submit">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
						<circle cx="9" cy="7" r="4" />
						<line x1="19" y1="8" x2="19" y2="14" />
						<line x1="22" y1="11" x2="16" y2="11" />
					</svg>
					Register
				</button>
			</form>

			<div class="link-text">
				Already have an account? <a href="<?php echo site_url('login'); ?>">Sign in →</a>
			</div>
		</div>
	</div>
</body>

</html>