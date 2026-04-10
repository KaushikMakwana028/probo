<?php if ($this->session->flashdata('error')): ?>
	<div class="withdraw-flash withdraw-flash-error">
		<svg class="flash-icon" width="20" height="20" viewBox="0 0 20 20" fill="none">
			<path d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16zM10 6v4m0 4h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
		</svg>
		<span><?php echo $this->session->flashdata('error'); ?></span>
		<button class="flash-close" onclick="this.parentElement.remove()">
			<svg width="16" height="16" viewBox="0 0 16 16" fill="none">
				<path d="M12 4L4 12M4 4l8 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
			</svg>
		</button>
	</div>
<?php endif; ?>

<?php if ($this->session->flashdata('success')): ?>
	<div class="withdraw-flash withdraw-flash-success">
		<svg class="flash-icon" width="20" height="20" viewBox="0 0 20 20" fill="none">
			<path d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-1-6l5-5-1-1-4 4-2-2-1 1 3 3z" fill="currentColor" />
		</svg>
		<span><?php echo $this->session->flashdata('success'); ?></span>
		<button class="flash-close" onclick="this.parentElement.remove()">
			<svg width="16" height="16" viewBox="0 0 16 16" fill="none">
				<path d="M12 4L4 12M4 4l8 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
			</svg>
		</button>
	</div>
<?php endif; ?>

<section class="withdraw-page">
	<div class="withdraw-hero">
		<div class="withdraw-hero-content">
			<h2>💰 Withdrawal Requests</h2>
			<p>Review user requests, verify the bank account details, and approve only the payouts that should move from wallet to bank transfer.</p>
		</div>
		<div class="withdraw-hero-badge">
			<span>Total Requested</span>
			<strong>Rs <?php echo number_format((float) $total_requested, 2); ?></strong>
		</div>
	</div>

	<div class="withdraw-summary">
		<div class="withdraw-stat withdraw-stat-pending">
			<div class="stat-icon">⏳</div>
			<div class="stat-content">
				<span>Pending</span>
				<strong><?php echo (int) $pending_count; ?></strong>
				<p>Requests waiting for admin action</p>
			</div>
		</div>
		<div class="withdraw-stat withdraw-stat-approved">
			<div class="stat-icon">✅</div>
			<div class="stat-content">
				<span>Approved</span>
				<strong><?php echo (int) $approved_count; ?></strong>
				<p>Requests already marked for transfer</p>
			</div>
		</div>
		<div class="withdraw-stat withdraw-stat-total">
			<div class="stat-icon">📊</div>
			<div class="stat-content">
				<span>Total Requests</span>
				<strong><?php echo count($withdrawals); ?></strong>
				<p>All processed and pending entries</p>
			</div>
		</div>
	</div>

	<div class="withdraw-panel">
		<div class="withdraw-head">
			<div>
				<h3>Request Queue</h3>
				<p>Each request shows the user, amount, and saved bank details in a cleaner approval workflow.</p>
			</div>
		</div>

		<?php if (!empty($withdrawals)): ?>
			<div class="withdraw-list">
				<?php foreach ($withdrawals as $request): ?>
					<div class="withdraw-card">
						<div class="withdraw-user">
							<span class="withdraw-label">👤 User</span>
							<strong><?php echo html_escape($request->user_name); ?></strong>
							<small>📱 <?php echo html_escape($request->user_mobile); ?></small>
						</div>
						<div class="withdraw-meta">
							<span class="withdraw-label">💵 Amount</span>
							<strong class="withdraw-amount">Rs <?php echo number_format((float) $request->amount, 2); ?></strong>
						</div>
						<div class="withdraw-meta">
							<span class="withdraw-label">🏦 Bank</span>
							<strong><?php echo html_escape($request->bank_name); ?></strong>
						</div>
						<div class="withdraw-meta">
							<span class="withdraw-label">🔢 Account</span>
							<strong><?php echo html_escape($request->account_number); ?></strong>
							<small><?php echo html_escape($request->account_holder_name); ?></small>
						</div>
						<div class="withdraw-meta">
							<span class="withdraw-label">🏷️ IFSC</span>
							<strong><?php echo html_escape($request->ifsc_code); ?></strong>
						</div>
						<div class="withdraw-action-box">
							<span class="withdraw-status withdraw-status-<?php echo html_escape($request->status); ?>">
								<?php echo ucfirst(html_escape($request->status)); ?>
							</span>
							<?php if ($request->status === 'pending'): ?>
								<div class="withdraw-actions">
									<a class="withdraw-btn withdraw-btn-approve" href="<?php echo site_url('admin/withdrawals/approve/' . (int) $request->id); ?>" onclick="return confirm('✅ Are you sure you want to approve this withdrawal request?');">
										<svg width="16" height="16" viewBox="0 0 16 16" fill="none">
											<path d="M13 4L6 11L3 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
										</svg>
										Approve
									</a>
									<a class="withdraw-btn withdraw-btn-reject" href="<?php echo site_url('admin/withdrawals/reject/' . (int) $request->id); ?>" onclick="return confirm('❌ Are you sure you want to reject this withdrawal request?');">
										<svg width="16" height="16" viewBox="0 0 16 16" fill="none">
											<path d="M12 4L4 12M4 4l8 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
										</svg>
										Reject
									</a>
								</div>
							<?php else: ?>
								<p class="withdraw-note">
									<svg width="14" height="14" viewBox="0 0 16 16" fill="none" style="display: inline-block; vertical-align: middle; margin-right: 4px;">
										<path d="M8 1a7 7 0 100 14A7 7 0 008 1zm0 3v4m0 2h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
									</svg>
									<?php echo html_escape($request->admin_note); ?>
								</p>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php else: ?>
			<div class="withdraw-empty">
				<div class="empty-icon">📭</div>
				<h3>No withdrawal requests</h3>
				<p>Once users start sending requests, they will appear here for review.</p>
			</div>
		<?php endif; ?>
	</div>
</section>

<style>
	* {
		box-sizing: border-box;
	}

	/* Flash Messages */
	.withdraw-flash {
		display: flex;
		align-items: center;
		gap: 12px;
		padding: 16px 20px;
		border-radius: 16px;
		margin-bottom: 24px;
		font-weight: 600;
		animation: slideDown 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
		box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
		position: relative;
		overflow: hidden;
	}

	.withdraw-flash::before {
		content: '';
		position: absolute;
		left: 0;
		top: 0;
		width: 4px;
		height: 100%;
		opacity: 0.8;
	}

	@keyframes slideDown {
		from {
			opacity: 0;
			transform: translateY(-20px) scale(0.95);
		}

		to {
			opacity: 1;
			transform: translateY(0) scale(1);
		}
	}

	.withdraw-flash-error {
		background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
		border: 2px solid #fecaca;
		color: #991b1b;
	}

	.withdraw-flash-error::before {
		background: #dc2626;
	}

	.withdraw-flash-success {
		background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
		border: 2px solid #bbf7d0;
		color: #166534;
	}

	.withdraw-flash-success::before {
		background: #22c55e;
	}

	.flash-icon {
		flex-shrink: 0;
	}

	.withdraw-flash span {
		flex: 1;
		font-size: 14px;
	}

	.flash-close {
		background: none;
		border: none;
		cursor: pointer;
		padding: 6px;
		border-radius: 8px;
		color: inherit;
		opacity: 0.7;
		transition: all 0.2s ease;
		display: flex;
		align-items: center;
		justify-content: center;
	}

	.flash-close:hover {
		opacity: 1;
		background: rgba(0, 0, 0, 0.08);
		transform: rotate(90deg);
	}

	/* Page Layout */
	.withdraw-page {
		display: grid;
		gap: 28px;
	}

	/* Hero Section */
	.withdraw-hero {
		display: flex;
		justify-content: space-between;
		gap: 24px;
		align-items: center;
		padding: 32px;
		border-radius: 24px;
		background: linear-gradient(135deg, #0f172a 0%, #1e40af 50%, #3b82f6 100%);
		color: #fff;
		box-shadow: 0 20px 48px rgba(29, 78, 216, 0.2);
		position: relative;
		overflow: hidden;
		transition: all 0.4s ease;
	}

	.withdraw-hero::before {
		content: '';
		position: absolute;
		top: -50%;
		right: -50%;
		width: 200%;
		height: 200%;
		background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
		pointer-events: none;
		animation: rotate 20s linear infinite;
	}

	@keyframes rotate {
		from {
			transform: rotate(0deg);
		}

		to {
			transform: rotate(360deg);
		}
	}

	.withdraw-hero:hover {
		box-shadow: 0 24px 56px rgba(29, 78, 216, 0.3);
		transform: translateY(-4px);
	}

	.withdraw-hero-content {
		position: relative;
		z-index: 1;
		flex: 1;
	}

	.withdraw-hero h2 {
		margin: 0 0 12px;
		color: #fff;
		font-size: 36px;
		font-weight: 900;
		text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
	}

	.withdraw-hero p {
		margin: 0;
		color: rgba(255, 255, 255, 0.9);
		max-width: 720px;
		line-height: 1.6;
		font-size: 15px;
		font-weight: 500;
	}

	.withdraw-hero-badge {
		min-width: 240px;
		padding: 24px;
		border-radius: 20px;
		background: rgba(255, 255, 255, 0.12);
		border: 2px solid rgba(255, 255, 255, 0.2);
		backdrop-filter: blur(10px);
		text-align: center;
		position: relative;
		z-index: 1;
		transition: all 0.3s ease;
		box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
	}

	.withdraw-hero-badge:hover {
		background: rgba(255, 255, 255, 0.18);
		transform: scale(1.05);
		box-shadow: 0 12px 32px rgba(0, 0, 0, 0.15);
	}

	.withdraw-hero-badge span {
		display: block;
		margin-bottom: 10px;
		font-size: 12px;
		text-transform: uppercase;
		letter-spacing: 0.15em;
		color: rgba(255, 255, 255, 0.8);
		font-weight: 700;
	}

	.withdraw-hero-badge strong {
		font-size: 36px;
		color: #fff;
		font-weight: 900;
		text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
	}

	/* Summary Stats */
	.withdraw-summary {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
		gap: 20px;
	}

	.withdraw-stat {
		display: flex;
		align-items: center;
		gap: 20px;
		padding: 24px;
		border-radius: 20px;
		background: #fff;
		border: 2px solid #e5e7eb;
		box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02), 0 12px 24px rgba(0, 0, 0, 0.03);
		transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
		position: relative;
		overflow: hidden;
	}

	.withdraw-stat::before {
		content: '';
		position: absolute;
		top: 0;
		left: 0;
		width: 4px;
		height: 100%;
		background: #3b82f6;
		transform: scaleY(0);
		transform-origin: top;
		transition: transform 0.3s ease;
	}

	.withdraw-stat:hover {
		border-color: #d1d5db;
		box-shadow: 0 8px 16px rgba(0, 0, 0, 0.04), 0 20px 40px rgba(0, 0, 0, 0.06);
		transform: translateY(-4px);
	}

	.withdraw-stat:hover::before {
		transform: scaleY(1);
	}

	.stat-icon {
		font-size: 48px;
		line-height: 1;
		flex-shrink: 0;
	}

	.stat-content {
		flex: 1;
		min-width: 0;
	}

	.withdraw-stat span {
		display: block;
		margin-bottom: 8px;
		font-size: 12px;
		text-transform: uppercase;
		letter-spacing: 0.1em;
		color: #64748b;
		font-weight: 700;
	}

	.withdraw-stat strong {
		display: block;
		font-size: 40px;
		color: #111827;
		margin-bottom: 6px;
		font-weight: 900;
		line-height: 1;
	}

	.withdraw-stat p {
		margin: 0;
		color: #64748b;
		font-size: 13px;
		line-height: 1.5;
		font-weight: 500;
	}

	.withdraw-stat-pending {
		background: linear-gradient(135deg, #fefce8 0%, #fef9c3 100%);
		border-color: #fde047;
	}

	.withdraw-stat-pending::before {
		background: #eab308;
	}

	.withdraw-stat-approved {
		background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
		border-color: #86efac;
	}

	.withdraw-stat-approved::before {
		background: #22c55e;
	}

	.withdraw-stat-total {
		background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
		border-color: #93c5fd;
	}

	.withdraw-stat-total::before {
		background: #3b82f6;
	}

	/* Panel */
	.withdraw-panel {
		background: #fff;
		border: 2px solid #e5e7eb;
		border-radius: 24px;
		padding: 32px;
		box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02), 0 12px 24px rgba(0, 0, 0, 0.03);
		position: relative;
		overflow: hidden;
	}

	.withdraw-panel::before {
		content: '';
		position: absolute;
		top: 0;
		left: 0;
		right: 0;
		height: 4px;
		background: linear-gradient(90deg, #3b82f6 0%, #8b5cf6 50%, #ec4899 100%);
		background-size: 200% 100%;
		animation: shimmer 3s linear infinite;
	}

	@keyframes shimmer {
		0% {
			background-position: -200% 0;
		}

		100% {
			background-position: 200% 0;
		}
	}

	.withdraw-head {
		margin-bottom: 24px;
		padding-bottom: 24px;
		border-bottom: 2px solid #f3f4f6;
		position: relative;
	}

	.withdraw-head::after {
		content: '';
		position: absolute;
		bottom: -2px;
		left: 0;
		width: 60px;
		height: 2px;
		background: linear-gradient(90deg, #3b82f6, #8b5cf6);
		border-radius: 2px;
	}

	.withdraw-head h3 {
		margin: 0 0 8px;
		color: #111827;
		font-size: 28px;
		font-weight: 800;
		background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
		-webkit-background-clip: text;
		-webkit-text-fill-color: transparent;
		background-clip: text;
	}

	.withdraw-head p {
		margin: 0;
		color: #6b7280;
		font-size: 15px;
		line-height: 1.6;
		font-weight: 500;
	}

	/* Withdraw List */
	.withdraw-list {
		display: grid;
		gap: 16px;
	}

	.withdraw-card {
		display: grid;
		grid-template-columns: minmax(0, 1.05fr) repeat(4, minmax(120px, 0.75fr)) minmax(220px, 0.95fr);
		gap: 18px;
		align-items: start;
		padding: 24px;
		border-radius: 18px;
		border: 2px solid #e5e7eb;
		background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%);
		transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
		position: relative;
		overflow: hidden;
	}

	.withdraw-card::before {
		content: '';
		position: absolute;
		left: 0;
		top: 0;
		width: 5px;
		height: 100%;
		background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
		opacity: 0;
		transform: scaleY(0);
		transform-origin: top;
		transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
	}

	.withdraw-card:hover {
		border-color: #3b82f6;
		background: white;
		box-shadow: 0 8px 24px rgba(59, 130, 246, 0.12);
		transform: translateX(4px);
	}

	.withdraw-card:hover::before {
		opacity: 1;
		transform: scaleY(1);
	}

	.withdraw-user,
	.withdraw-meta,
	.withdraw-action-box {
		min-width: 0;
	}

	.withdraw-label {
		display: block;
		margin-bottom: 8px;
		font-size: 11px;
		text-transform: uppercase;
		letter-spacing: 0.12em;
		color: #64748b;
		font-weight: 800;
	}

	.withdraw-user strong,
	.withdraw-meta strong {
		display: block;
		color: #0f172a;
		word-break: break-word;
		font-weight: 700;
		font-size: 15px;
		margin-bottom: 4px;
	}

	.withdraw-user small,
	.withdraw-meta small {
		display: block;
		color: #64748b;
		word-break: break-word;
		font-size: 13px;
		font-weight: 500;
		margin-top: 4px;
	}

	.withdraw-amount {
		color: #1d4ed8 !important;
		font-size: 18px !important;
		font-weight: 900 !important;
	}

	/* Status Badge */
	.withdraw-status {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		padding: 8px 14px;
		border-radius: 999px;
		font-size: 12px;
		font-weight: 800;
		margin-bottom: 12px;
		letter-spacing: 0.5px;
		text-transform: uppercase;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
		transition: all 0.3s ease;
	}

	.withdraw-status:hover {
		transform: scale(1.05);
	}

	.withdraw-status-pending {
		background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
		color: #92400e;
		border: 2px solid #fbbf24;
	}

	.withdraw-status-approved {
		background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
		color: #166534;
		border: 2px solid #22c55e;
	}

	.withdraw-status-rejected {
		background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
		color: #991b1b;
		border: 2px solid #ef4444;
	}

	/* Action Buttons */
	.withdraw-actions {
		display: flex;
		gap: 10px;
		flex-wrap: wrap;
	}

	.withdraw-btn {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		gap: 6px;
		padding: 12px 18px;
		border-radius: 12px;
		text-decoration: none;
		font-size: 13px;
		font-weight: 800;
		transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
		border: 2px solid transparent;
		letter-spacing: 0.3px;
	}

	.withdraw-btn-approve {
		background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
		color: #166534;
		border-color: #86efac;
	}

	.withdraw-btn-approve:hover {
		background: linear-gradient(135deg, #bbf7d0 0%, #86efac 100%);
		transform: translateY(-2px);
		box-shadow: 0 6px 20px rgba(34, 197, 94, 0.25);
	}

	.withdraw-btn-reject {
		background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
		color: #991b1b;
		border-color: #fca5a5;
	}

	.withdraw-btn-reject:hover {
		background: linear-gradient(135deg, #fecaca 0%, #fca5a5 100%);
		transform: translateY(-2px);
		box-shadow: 0 6px 20px rgba(239, 68, 68, 0.25);
	}

	.withdraw-note {
		margin: 0;
		color: #64748b;
		font-size: 13px;
		line-height: 1.6;
		font-weight: 500;
		padding: 12px 16px;
		background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
		border-radius: 10px;
		border: 1px solid #e2e8f0;
	}

	/* Empty State */
	.withdraw-empty {
		padding: 80px 32px;
		border: 3px dashed #cbd5e1;
		border-radius: 24px;
		background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
		text-align: center;
		transition: all 0.3s ease;
	}

	.withdraw-empty:hover {
		border-color: #94a3b8;
		background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
	}

	.empty-icon {
		font-size: 80px;
		margin-bottom: 24px;
		animation: bounce 2s infinite;
	}

	@keyframes bounce {

		0%,
		100% {
			transform: translateY(0);
		}

		50% {
			transform: translateY(-15px);
		}
	}

	.withdraw-empty h3 {
		margin: 0 0 12px;
		color: #111827;
		font-size: 28px;
		font-weight: 800;
		background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
		-webkit-background-clip: text;
		-webkit-text-fill-color: transparent;
		background-clip: text;
	}

	.withdraw-empty p {
		margin: 0;
		color: #6b7280;
		font-size: 15px;
		line-height: 1.6;
		font-weight: 500;
		max-width: 480px;
		margin-left: auto;
		margin-right: auto;
	}

	/* Responsive */
	@media (max-width: 1280px) {
		.withdraw-card {
			grid-template-columns: repeat(2, 1fr);
		}
	}

	@media (max-width: 1024px) {
		.withdraw-summary {
			grid-template-columns: 1fr;
		}

		.withdraw-hero {
			flex-direction: column;
			align-items: stretch;
		}

		.withdraw-hero-badge {
			min-width: auto;
		}
	}

	@media (max-width: 768px) {
		.withdraw-card {
			grid-template-columns: 1fr;
		}

		.withdraw-panel {
			padding: 24px;
		}

		.withdraw-hero {
			padding: 24px;
		}

		.withdraw-hero h2 {
			font-size: 28px;
		}

		.withdraw-stat {
			flex-direction: column;
			text-align: center;
		}

		.stat-icon {
			font-size: 56px;
		}

		.withdraw-stat strong {
			font-size: 36px;
		}
	}

	@media (max-width: 480px) {
		.withdraw-actions {
			width: 100%;
		}

		.withdraw-btn {
			flex: 1;
		}

		.withdraw-hero h2 {
			font-size: 24px;
		}

		.withdraw-hero-badge strong {
			font-size: 28px;
		}
	}

	/* Smooth scrolling */
	html {
		scroll-behavior: smooth;
	}

	/* Selection styling */
	::selection {
		background: rgba(59, 130, 246, 0.2);
		color: #111827;
	}
</style>

<script>
	document.addEventListener('DOMContentLoaded', function() {
		// Auto-dismiss flash messages
		const flashMessages = document.querySelectorAll('.withdraw-flash');
		flashMessages.forEach(function(flash) {
			setTimeout(function() {
				flash.style.animation = 'slideUp 0.4s ease forwards';
				setTimeout(function() {
					flash.remove();
				}, 400);
			}, 5000);
		});

		// Add slide up animation
		const style = document.createElement('style');
		style.textContent = `
			@keyframes slideUp {
				to {
					opacity: 0;
					transform: translateY(-20px) scale(0.95);
				}
			}
		`;
		document.head.appendChild(style);

		// Add confirmation animations
		const actionButtons = document.querySelectorAll('.withdraw-btn');
		actionButtons.forEach(function(btn) {
			btn.addEventListener('click', function(e) {
				const icon = this.querySelector('svg');
				if (icon) {
					icon.style.transform = 'scale(1.3) rotate(20deg)';
					setTimeout(() => {
						icon.style.transform = 'scale(1) rotate(0deg)';
					}, 300);
				}
			});
		});
	});
</script>