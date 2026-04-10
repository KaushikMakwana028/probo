<?php if ($this->session->flashdata('error')): ?>
	<div class="ref-alert ref-alert-error"><?php echo $this->session->flashdata('error'); ?></div>
<?php endif; ?>
<?php if ($this->session->flashdata('success')): ?>
	<div class="ref-alert ref-alert-success"><?php echo $this->session->flashdata('success'); ?></div>
<?php endif; ?>

<section class="ref-page">
	<div class="ref-hero">
		<div>
			<h2>Referral Settings</h2>
			<p>Set how much bonus the new user gets and how much the referring user gets. These values will apply automatically on future registrations with a referral code.</p>
		</div>
		<div class="ref-hero-badge">
			<span>Current Split</span>
			<strong>New User Rs <?php echo number_format((float) $settings['new_user_bonus'], 2); ?> | Referrer Rs <?php echo number_format((float) $settings['referrer_bonus'], 2); ?></strong>
		</div>
	</div>

	<div class="ref-grid">
		<div class="ref-card">
			<h3>Update Referral Amount</h3>
			<p>Admin controls the signup referral bonus from here.</p>
			<form method="post" action="<?php echo site_url('admin/referrals/save'); ?>" class="ref-form">
				<div class="ref-field">
					<label for="new_user_bonus">New User Bonus</label>
					<input type="number" id="new_user_bonus" name="new_user_bonus" min="0" step="0.01" value="<?php echo number_format((float) $settings['new_user_bonus'], 2, '.', ''); ?>" required>
				</div>
				<div class="ref-field">
					<label for="referrer_bonus">Referrer Bonus</label>
					<input type="number" id="referrer_bonus" name="referrer_bonus" min="0" step="0.01" value="<?php echo number_format((float) $settings['referrer_bonus'], 2, '.', ''); ?>" required>
				</div>
				<div class="ref-total-box">
					<span>Total Referral Cost</span>
					<strong id="ref-total-preview">Rs <?php echo number_format((float) $settings['new_user_bonus'] + (float) $settings['referrer_bonus'], 2); ?></strong>
				</div>
				<button type="submit" class="ref-primary-btn">Save Referral Settings</button>
			</form>
		</div>

		<div class="ref-card">
			<h3>How It Works</h3>
			<div class="ref-info-list">
				<div class="ref-info-item">
					<strong>Step 1</strong>
					<p>Existing user shares their referral code.</p>
				</div>
				<div class="ref-info-item">
					<strong>Step 2</strong>
					<p>New user signs up with that code.</p>
				</div>
				<div class="ref-info-item">
					<strong>Step 3</strong>
					<p>System credits both wallets according to the amount set by admin.</p>
				</div>
				<div class="ref-info-item">
					<strong>Step 4</strong>
					<p>Referral list page records who joined from whom and the exact credited amounts.</p>
				</div>
			</div>
			<a class="ref-secondary-link" href="<?php echo site_url('admin/referrals/list'); ?>">Open Referral List</a>
		</div>
	</div>
</section>

<style>
	.ref-page{display:grid;gap:22px}
	.ref-alert{padding:14px 18px;border-radius:14px;margin-bottom:18px;font-weight:600}
	.ref-alert-error{background:#fef2f2;border:1px solid #fecaca;color:#991b1b}
	.ref-alert-success{background:#f0fdf4;border:1px solid #bbf7d0;color:#166534}
	.ref-hero{display:grid;grid-template-columns:minmax(0,1fr) 360px;gap:20px;align-items:center;padding:28px;border-radius:26px;background:linear-gradient(135deg,#0f172a 0%,#2563eb 62%,#38bdf8 100%);color:#fff}
	.ref-hero h2{margin:0 0 8px;color:#fff;font-size:34px}
	.ref-hero p{margin:0;color:rgba(255,255,255,.84);line-height:1.7}
	.ref-hero-badge{padding:20px;border-radius:20px;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.18)}
	.ref-hero-badge span{display:block;margin-bottom:8px;font-size:12px;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.72);font-weight:700}
	.ref-hero-badge strong{display:block;color:#fff;font-size:22px;line-height:1.5}
	.ref-grid{display:grid;grid-template-columns:minmax(0,1fr) 380px;gap:22px}
	.ref-card{background:#fff;border:1px solid #e2e8f0;border-radius:24px;padding:24px;box-shadow:0 10px 26px rgba(15,23,42,.05)}
	.ref-card h3{margin:0 0 8px;color:#111827;font-size:24px}
	.ref-card p{margin:0;color:#64748b;line-height:1.6}
	.ref-form{display:grid;gap:16px;margin-top:20px}
	.ref-field{display:grid;gap:6px}
	.ref-field label{font-size:12px;text-transform:uppercase;letter-spacing:.08em;color:#64748b;font-weight:700}
	.ref-field input{width:100%;padding:14px 16px;border-radius:16px;border:1px solid #d1d5db;background:#f8fafc;font-size:15px}
	.ref-field input:focus{outline:none;border-color:#2563eb;box-shadow:0 0 0 3px rgba(37,99,235,.1);background:#fff}
	.ref-total-box{display:flex;align-items:center;justify-content:space-between;gap:16px;padding:16px 18px;border-radius:18px;background:#eef4ff;border:1px solid #bfdbfe}
	.ref-total-box span{color:#1d4ed8;font-size:13px;font-weight:700}
	.ref-total-box strong{color:#0f172a;font-size:24px}
	.ref-primary-btn,.ref-secondary-link{display:inline-flex;align-items:center;justify-content:center;padding:14px 18px;border-radius:16px;text-decoration:none;font-weight:700;border:none;cursor:pointer}
	.ref-primary-btn{background:linear-gradient(135deg,#2563eb 0%,#4338ca 100%);color:#fff}
	.ref-secondary-link{margin-top:20px;background:#f8fafc;border:1px solid #d1d5db;color:#334155}
	.ref-info-list{display:grid;gap:14px;margin-top:18px}
	.ref-info-item{padding:16px;border-radius:18px;background:#f8fafc;border:1px solid #e2e8f0}
	.ref-info-item strong{display:block;margin-bottom:6px;color:#111827}
	.ref-info-item p{margin:0}
	@media (max-width:1100px){.ref-hero,.ref-grid{grid-template-columns:1fr}}
</style>

<script>
	document.addEventListener('DOMContentLoaded', function() {
		const newUserInput = document.getElementById('new_user_bonus');
		const referrerInput = document.getElementById('referrer_bonus');
		const totalPreview = document.getElementById('ref-total-preview');

		function updateTotal() {
			const newUser = parseFloat(newUserInput.value || '0');
			const referrer = parseFloat(referrerInput.value || '0');
			totalPreview.textContent = 'Rs ' + (newUser + referrer).toFixed(2);
		}

		newUserInput.addEventListener('input', updateTotal);
		referrerInput.addEventListener('input', updateTotal);
		updateTotal();
	});
</script>
