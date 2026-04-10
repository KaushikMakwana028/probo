<section class="ref-list-page">
	<div class="ref-list-hero">
		<div>
			<h2>Referral List</h2>
			<p>See which user joined from which referral code, who referred them, and how much bonus each side received.</p>
		</div>
		<div class="ref-list-hero-box">
			<span>Current Bonus</span>
			<strong>New User Rs <?php echo number_format((float) $settings['new_user_bonus'], 2); ?></strong>
			<strong>Referrer Rs <?php echo number_format((float) $settings['referrer_bonus'], 2); ?></strong>
		</div>
	</div>

	<div class="ref-list-card">
		<div class="ref-list-head">
			<div>
				<h3>Referral Activity</h3>
				<p>All completed referrals are listed below.</p>
			</div>
			<a class="ref-list-btn" href="<?php echo site_url('admin/referrals/add'); ?>">Update Amount</a>
		</div>

		<div class="ref-list-table-wrap">
			<table class="ref-list-table">
				<thead>
					<tr>
						<th>#</th>
						<th>Referred User</th>
						<th>Referred By</th>
						<th>Referral Code</th>
						<th>New User Bonus</th>
						<th>Referrer Bonus</th>
						<th>Date</th>
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($referral_rewards)): ?>
						<?php foreach ($referral_rewards as $index => $reward): ?>
							<tr>
								<td><?php echo $index + 1; ?></td>
								<td>
									<strong><?php echo html_escape($reward->referred_name); ?></strong>
									<small><?php echo html_escape($reward->referred_email); ?> | <?php echo html_escape($reward->referred_mobile); ?></small>
								</td>
								<td>
									<strong><?php echo html_escape($reward->referrer_name); ?></strong>
									<small><?php echo html_escape($reward->referrer_email); ?> | <?php echo html_escape($reward->referrer_mobile); ?></small>
								</td>
								<td><span class="ref-list-code"><?php echo html_escape($reward->referral_code); ?></span></td>
								<td class="ref-money">Rs <?php echo number_format((float) $reward->referred_bonus, 2); ?></td>
								<td class="ref-money">Rs <?php echo number_format((float) $reward->referrer_bonus, 2); ?></td>
								<td><?php echo !empty($reward->created_at) ? html_escape($reward->created_at) : '-'; ?></td>
							</tr>
						<?php endforeach; ?>
					<?php else: ?>
						<tr>
							<td colspan="7" class="ref-list-empty">No referral activity found yet.</td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</section>

<style>
	.ref-list-page{display:grid;gap:22px}
	.ref-list-hero{display:grid;grid-template-columns:minmax(0,1fr) 340px;gap:20px;align-items:center;padding:28px;border-radius:26px;background:linear-gradient(135deg,#0f172a 0%,#1d4ed8 64%,#38bdf8 100%);color:#fff}
	.ref-list-hero h2{margin:0 0 8px;color:#fff;font-size:34px}
	.ref-list-hero p{margin:0;color:rgba(255,255,255,.84);line-height:1.7}
	.ref-list-hero-box{padding:20px;border-radius:20px;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.16)}
	.ref-list-hero-box span{display:block;margin-bottom:8px;font-size:12px;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.72);font-weight:700}
	.ref-list-hero-box strong{display:block;color:#fff;font-size:20px;line-height:1.6}
	.ref-list-card{background:#fff;border:1px solid #e2e8f0;border-radius:24px;overflow:hidden;box-shadow:0 10px 26px rgba(15,23,42,.05)}
	.ref-list-head{display:flex;align-items:center;justify-content:space-between;gap:16px;padding:22px 24px;border-bottom:1px solid #eef2f7}
	.ref-list-head h3{margin:0 0 6px;color:#111827;font-size:24px}
	.ref-list-head p{margin:0;color:#64748b}
	.ref-list-btn{display:inline-flex;align-items:center;justify-content:center;padding:12px 16px;border-radius:14px;background:linear-gradient(135deg,#2563eb 0%,#4338ca 100%);color:#fff;text-decoration:none;font-weight:700}
	.ref-list-table-wrap{overflow:auto}
	.ref-list-table{width:100%;border-collapse:collapse;min-width:980px}
	.ref-list-table th{padding:14px 18px;background:#f8fafc;color:#64748b;font-size:12px;text-transform:uppercase;letter-spacing:.08em;text-align:left}
	.ref-list-table td{padding:16px 18px;border-top:1px solid #eef2f7;color:#111827;vertical-align:top}
	.ref-list-table strong{display:block;color:#111827}
	.ref-list-table small{display:block;margin-top:4px;color:#64748b;line-height:1.6}
	.ref-list-code{display:inline-flex;align-items:center;justify-content:center;padding:8px 12px;border-radius:999px;background:#eef2ff;color:#4338ca;font-weight:800;letter-spacing:.08em}
	.ref-money{font-weight:800;color:#166534}
	.ref-list-empty{text-align:center;padding:28px;color:#64748b}
	@media (max-width:1100px){.ref-list-hero{grid-template-columns:1fr}}
</style>
