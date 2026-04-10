<div class="auth-shell">
	<div class="auth-card">
		<h1>Admin Login</h1>
		<p>Sign in with your admin mobile number and password.</p>

		<?php if ($this->session->flashdata('error')): ?>
			<div class="message error"><?php echo $this->session->flashdata('error'); ?></div>
		<?php endif; ?>

		<?php echo validation_errors('<div class="message error">', '</div>'); ?>

		<form method="post" action="<?php echo site_url('admin/login/authenticate'); ?>">
			<div class="form-group">
				<label for="mobile">Mobile</label>
				<input type="text" id="mobile" name="mobile" value="<?php echo set_value('mobile'); ?>" placeholder="Enter admin mobile number">
			</div>

			<div class="form-group">
				<label for="password">Password</label>
				<input type="password" id="password" name="password" placeholder="Enter password">
			</div>

			<button class="btn-primary" type="submit">Login</button>
		</form>
	</div>
</div>
