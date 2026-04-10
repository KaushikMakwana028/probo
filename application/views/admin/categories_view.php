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

<style>
	* {
		box-sizing: border-box;
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
		position: relative;
		box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
	}

	.message span {
		flex: 1;
	}

	.message-close {
		background: none;
		border: none;
		color: inherit;
		font-size: 18px;
		cursor: pointer;
		padding: 4px 8px;
		border-radius: 6px;
		opacity: 0.7;
		transition: all 0.2s ease;
		line-height: 1;
	}

	.message-close:hover {
		opacity: 1;
		background: rgba(0, 0, 0, 0.1);
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

	.message.error {
		background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
		border: 2px solid #fecaca;
		color: #991b1b;
	}

	.message.success {
		background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
		border: 2px solid #bbf7d0;
		color: #166534;
	}

	/* Admin Stats */
	.admin-stats {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
		gap: 24px;
		margin-bottom: 32px;
	}

	.admin-stat-card {
		padding: 28px;
		border-radius: 20px;
		background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
		color: white;
		position: relative;
		overflow: hidden;
		transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
		box-shadow: 0 8px 24px rgba(102, 126, 234, 0.25);
	}

	.admin-stat-card::before {
		content: '';
		position: absolute;
		top: -50%;
		right: -50%;
		width: 200%;
		height: 200%;
		background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, transparent 70%);
		pointer-events: none;
		transition: transform 0.6s ease;
	}

	.admin-stat-card::after {
		content: '';
		position: absolute;
		bottom: 0;
		right: 0;
		width: 100px;
		height: 100px;
		background: rgba(255, 255, 255, 0.05);
		border-radius: 50%;
		transform: translate(30%, 30%);
		transition: transform 0.4s ease;
	}

	.admin-stat-card:hover {
		transform: translateY(-8px) scale(1.02);
		box-shadow: 0 16px 40px rgba(102, 126, 234, 0.35);
	}

	.admin-stat-card:hover::before {
		transform: rotate(180deg);
	}

	.admin-stat-card:hover::after {
		transform: translate(20%, 20%) scale(1.5);
	}

	.admin-stat-card:nth-child(2) {
		background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
		box-shadow: 0 8px 24px rgba(245, 87, 108, 0.25);
	}

	.admin-stat-card:nth-child(2):hover {
		box-shadow: 0 16px 40px rgba(245, 87, 108, 0.35);
	}

	.admin-stat-card:nth-child(3) {
		background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
		box-shadow: 0 8px 24px rgba(79, 172, 254, 0.25);
	}

	.admin-stat-card:nth-child(3):hover {
		box-shadow: 0 16px 40px rgba(79, 172, 254, 0.35);
	}

	.admin-stat-label {
		font-size: 13px;
		font-weight: 700;
		opacity: 0.95;
		margin-bottom: 12px;
		text-transform: uppercase;
		letter-spacing: 1px;
		position: relative;
		z-index: 1;
	}

	.admin-stat-value {
		font-size: 48px;
		font-weight: 900;
		margin-bottom: 12px;
		line-height: 1;
		position: relative;
		z-index: 1;
		text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
	}

	.admin-stat-note {
		font-size: 13px;
		opacity: 0.9;
		line-height: 1.6;
		position: relative;
		z-index: 1;
	}

	.quick-link {
		color: white;
		text-decoration: none;
		font-weight: 700;
		transition: all 0.3s ease;
		display: inline-flex;
		align-items: center;
		gap: 6px;
		padding: 8px 16px;
		background: rgba(255, 255, 255, 0.15);
		border-radius: 10px;
		backdrop-filter: blur(10px);
		border: 1px solid rgba(255, 255, 255, 0.2);
	}

	.quick-link:hover {
		background: rgba(255, 255, 255, 0.25);
		transform: translateX(4px);
		box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
	}

	/* Page Grid */
	.category-page-grid {
		display: grid;
		grid-template-columns: minmax(0, 420px) minmax(0, 1fr);
		gap: 28px;
		align-items: start;
	}

	/* Form Panel */
	.form-panel,
	.admin-panel {
		padding: 32px;
		border-radius: 24px;
		border: 2px solid #e5e7eb;
		background: #ffffff;
		box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02), 0 12px 24px rgba(0, 0, 0, 0.03);
		transition: all 0.3s ease;
		position: relative;
		overflow: hidden;
	}

	.form-panel::before,
	.admin-panel::before {
		content: '';
		position: absolute;
		top: 0;
		left: 0;
		right: 0;
		height: 4px;
		background: linear-gradient(90deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
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

	.form-panel:hover,
	.admin-panel:hover {
		border-color: #d1d5db;
		box-shadow: 0 8px 16px rgba(0, 0, 0, 0.04), 0 20px 40px rgba(0, 0, 0, 0.06);
		transform: translateY(-2px);
	}

	.admin-panel-head {
		display: flex;
		align-items: flex-start;
		justify-content: space-between;
		gap: 16px;
		margin-bottom: 28px;
		padding-bottom: 24px;
		border-bottom: 2px solid #f3f4f6;
		position: relative;
	}

	.admin-panel-head::after {
		content: '';
		position: absolute;
		bottom: -2px;
		left: 0;
		width: 60px;
		height: 2px;
		background: linear-gradient(90deg, #667eea, #764ba2);
		border-radius: 2px;
	}

	.section-title {
		font-size: 24px;
		font-weight: 800;
		color: #111827;
		margin: 0 0 8px 0;
		background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
		-webkit-background-clip: text;
		-webkit-text-fill-color: transparent;
		background-clip: text;
	}

	.admin-panel-head p {
		color: #6b7280;
		font-size: 14px;
		margin: 0;
		line-height: 1.6;
	}

	/* Form Styles */
	.form-group {
		margin-bottom: 24px;
	}

	.form-group label {
		display: block;
		margin-bottom: 10px;
		font-weight: 700;
		color: #374151;
		font-size: 14px;
		letter-spacing: 0.3px;
	}

	.form-group input[type="text"] {
		width: 100%;
		padding: 16px 18px;
		border: 2px solid #e5e7eb;
		border-radius: 14px;
		font-size: 15px;
		transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
		background: #f9fafb;
		font-weight: 500;
	}

	.form-group input[type="text"]:focus {
		outline: none;
		border-color: #667eea;
		background: #ffffff;
		box-shadow: 0 0 0 5px rgba(102, 126, 234, 0.1), 0 4px 12px rgba(102, 126, 234, 0.15);
		transform: translateY(-2px);
	}

	.form-group input[type="text"]::placeholder {
		color: #9ca3af;
		font-weight: 400;
	}

	/* Buttons */
	.btn-primary,
	.btn-secondary,
	.btn-danger {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		gap: 8px;
		padding: 14px 24px;
		border-radius: 14px;
		font-weight: 700;
		font-size: 14px;
		text-decoration: none;
		cursor: pointer;
		border: 2px solid transparent;
		transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
		white-space: nowrap;
		position: relative;
		overflow: hidden;
		letter-spacing: 0.3px;
	}

	.btn-primary::before,
	.btn-secondary::before,
	.btn-danger::before {
		content: '';
		position: absolute;
		top: 50%;
		left: 50%;
		width: 0;
		height: 0;
		border-radius: 50%;
		background: rgba(255, 255, 255, 0.2);
		transform: translate(-50%, -50%);
		transition: width 0.6s ease, height 0.6s ease;
	}

	.btn-primary:hover::before,
	.btn-secondary:hover::before,
	.btn-danger:hover::before {
		width: 300px;
		height: 300px;
	}

	.btn-primary {
		background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
		color: white;
		width: 100%;
		border: none;
		box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
	}

	.btn-primary:hover {
		transform: translateY(-3px) scale(1.02);
		box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
	}

	.btn-primary:active {
		transform: translateY(-1px) scale(0.98);
	}

	.btn-secondary {
		background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
		border-color: #e5e7eb;
		color: #374151;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
	}

	.btn-secondary:hover {
		background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
		border-color: #d1d5db;
		transform: translateY(-2px);
		box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
	}

	.btn-danger {
		background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
		border-color: #fecaca;
		color: #dc2626;
		box-shadow: 0 2px 8px rgba(220, 38, 38, 0.1);
	}

	.btn-danger:hover {
		background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
		border-color: #fca5a5;
		transform: translateY(-2px);
		box-shadow: 0 4px 12px rgba(220, 38, 38, 0.2);
	}

	/* Category List */
	.category-list {
		display: grid;
		gap: 18px;
	}

	.category-card {
		padding: 28px;
		border-radius: 18px;
		border: 2px solid #f3f4f6;
		background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%);
		transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
		position: relative;
		overflow: hidden;
	}

	.category-card::before {
		content: '';
		position: absolute;
		left: 0;
		top: 0;
		height: 100%;
		width: 5px;
		background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
		transform: scaleY(0);
		transform-origin: bottom;
		transition: transform 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
	}

	.category-card::after {
		content: '';
		position: absolute;
		top: 0;
		right: 0;
		width: 100px;
		height: 100px;
		background: radial-gradient(circle, rgba(102, 126, 234, 0.05) 0%, transparent 70%);
		border-radius: 50%;
		transform: translate(50%, -50%) scale(0);
		transition: transform 0.4s ease;
	}

	.category-card:hover {
		border-color: #667eea;
		box-shadow: 0 12px 32px rgba(102, 126, 234, 0.15);
		transform: translateX(8px) translateY(-4px);
		background: #ffffff;
	}

	.category-card:hover::before {
		transform: scaleY(1);
		transform-origin: top;
	}

	.category-card:hover::after {
		transform: translate(50%, -50%) scale(2);
	}

	.category-card-head {
		display: flex;
		align-items: flex-start;
		justify-content: space-between;
		gap: 20px;
		position: relative;
		z-index: 1;
	}

	.category-card-head>div:first-child {
		flex: 1;
	}

	.category-card h4 {
		font-size: 20px;
		font-weight: 800;
		color: #111827;
		margin: 0 0 12px 0;
		letter-spacing: 0.3px;
		transition: color 0.3s ease;
	}

	.category-card:hover h4 {
		color: #667eea;
	}

	.card-actions {
		display: flex;
		flex-wrap: wrap;
		gap: 10px;
	}

	/* Info Pill */
	.info-pill {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		padding: 8px 14px;
		border-radius: 999px;
		background: linear-gradient(135deg, #dbeafe 0%, #e0f2fe 100%);
		color: #0369a1;
		font-size: 13px;
		font-weight: 700;
		border: 2px solid #bae6fd;
		transition: all 0.3s ease;
		box-shadow: 0 2px 8px rgba(3, 105, 161, 0.1);
	}

	.category-card:hover .info-pill {
		background: linear-gradient(135deg, #e0f2fe 0%, #dbeafe 100%);
		transform: scale(1.05);
		box-shadow: 0 4px 12px rgba(3, 105, 161, 0.2);
	}

	/* Empty State */
	.empty-state {
		padding: 60px 32px;
		border-radius: 20px;
		background: linear-gradient(135deg, #f9fafb 0%, #ffffff 100%);
		border: 3px dashed #d1d5db;
		text-align: center;
		transition: all 0.3s ease;
		position: relative;
		overflow: hidden;
	}

	.empty-state::before {
		content: '📭';
		position: absolute;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
		font-size: 120px;
		opacity: 0.05;
		pointer-events: none;
	}

	.empty-state:hover {
		border-color: #9ca3af;
		background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%);
	}

	.empty-state p {
		color: #6b7280;
		font-size: 15px;
		margin: 0;
		line-height: 1.8;
		font-weight: 500;
		position: relative;
		z-index: 1;
	}

	/* Responsive */
	@media (max-width: 920px) {
		.category-page-grid {
			grid-template-columns: 1fr;
		}

		.admin-stats {
			grid-template-columns: 1fr;
		}
	}

	@media (max-width: 768px) {
		.category-card-head {
			flex-direction: column;
		}

		.card-actions {
			width: 100%;
		}

		.card-actions .btn-secondary,
		.card-actions .btn-danger {
			flex: 1;
		}

		.admin-panel-head {
			flex-direction: column;
		}

		.form-panel,
		.admin-panel {
			padding: 24px;
		}

		.admin-stat-card {
			padding: 24px;
		}
	}

	@media (max-width: 480px) {
		.admin-stat-value {
			font-size: 36px;
		}

		.section-title {
			font-size: 20px;
		}

		.form-panel,
		.admin-panel {
			padding: 20px;
		}

		.category-card {
			padding: 20px;
		}
	}

	/* Smooth scrolling */
	html {
		scroll-behavior: smooth;
	}

	/* Selection styling */
	::selection {
		background: rgba(102, 126, 234, 0.2);
		color: #111827;
	}
</style>

<section class="admin-stats">
	<div class="admin-stat-card">
		<div class="admin-stat-label">Total Categories</div>
		<div class="admin-stat-value"><?php echo (int) $total_categories; ?></div>
		<div class="admin-stat-note">Manage and organize your categories</div>
	</div>
	<div class="admin-stat-card">
		<div class="admin-stat-label">Total Questions</div>
		<div class="admin-stat-value"><?php echo (int) $total_questions; ?></div>
		<div class="admin-stat-note">Across all categories</div>
	</div>
	<div class="admin-stat-card">
		<div class="admin-stat-label">Quick Actions</div>
		<div class="admin-stat-note">
			<a class="quick-link" href="<?php echo site_url('admin/questions'); ?>">
				<span>Manage Questions</span>
				<span>→</span>
			</a>
		</div>
	</div>
</section>

<section class="category-page-grid">
	<section class="form-panel">
		<div class="admin-panel-head">
			<div>
				<h3 class="section-title"><?php echo $edit_category ? '✏️ Edit Category' : '➕ Add Category'; ?></h3>
				<p><?php echo $edit_category ? 'Update category information below' : 'Create a new category for questions'; ?></p>
			</div>
			<?php if ($edit_category): ?>
				<a class="btn-secondary" href="<?php echo site_url('admin/categories'); ?>">✕ Cancel</a>
			<?php endif; ?>
		</div>

		<form method="post" action="<?php echo $edit_category ? site_url('admin/categories/update/' . $edit_category->id) : site_url('admin/categories/create'); ?>">
			<div class="form-group">
				<label for="category_name">Category Name *</label>
				<input
					type="text"
					id="category_name"
					name="name"
					value="<?php echo set_value('name', $edit_category ? $edit_category->name : ''); ?>"
					placeholder="e.g., General Knowledge, Science, History..."
					required
					autocomplete="off">
			</div>

			<button class="btn-primary" type="submit">
				<span><?php echo $edit_category ? '💾 Update Category' : '✓ Create Category'; ?></span>
			</button>
		</form>
	</section>

	<section class="admin-panel">
		<div class="admin-panel-head">
			<div>
				<h3 class="section-title">📋 All Categories</h3>
				<p>View and manage your question categories</p>
			</div>
		</div>

		<div class="category-list">
			<?php if (!empty($categories)): ?>
				<?php foreach ($categories as $category_item): ?>
					<article class="category-card">
						<div class="category-card-head">
							<div>
								<h4><?php echo html_escape($category_item->name); ?></h4>
								<span class="info-pill">
									<span>📝</span>
									<span><?php echo count($category_item->questions); ?> question<?php echo count($category_item->questions) != 1 ? 's' : ''; ?></span>
								</span>
							</div>

							<div class="card-actions">
								<a class="btn-secondary" href="<?php echo site_url('admin/categories?edit_category=' . (int) $category_item->id); ?>">
									<span>✏️ Edit</span>
								</a>
								<a class="btn-danger" href="<?php echo site_url('admin/categories/delete/' . (int) $category_item->id); ?>" onclick="return confirm('⚠️ Are you sure you want to delete this category and all its questions?\n\nThis action cannot be undone.');">
									<span>🗑️ Delete</span>
								</a>
							</div>
						</div>
					</article>
				<?php endforeach; ?>
			<?php else: ?>
				<div class="empty-state">
					<p><strong>📭 No categories found yet.</strong><br>Create your first category using the form on the left.</p>
				</div>
			<?php endif; ?>
		</div>
	</section>
</section>