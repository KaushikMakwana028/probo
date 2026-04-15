<?php
$selected_category_id = (int) $this->input->get('category_id');
$question_count = (int) $this->input->get('question_count');

if ($question_count <= 0) {
	$question_count = 1;
}

if ($question_count > 100) {
	$question_count = 100;
}

$selected_category_name = '';
foreach ($categories as $category_item) {
	if ((int) $category_item->id === $selected_category_id) {
		$selected_category_name = $category_item->name;
		break;
	}
}
?>

<?php if ($this->session->flashdata('error')): ?>
	<div class="alert-message alert-error">
		<svg class="alert-icon" width="20" height="20" viewBox="0 0 20 20" fill="none">
			<path d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16zM10 6v4m0 4h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
		</svg>
		<span><?php echo $this->session->flashdata('error'); ?></span>
		<button class="alert-close" onclick="this.parentElement.remove()">
			<svg width="16" height="16" viewBox="0 0 16 16" fill="none">
				<path d="M12 4L4 12M4 4l8 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
			</svg>
		</button>
	</div>
<?php endif; ?>

<?php if ($this->session->flashdata('success')): ?>
	<div class="alert-message alert-success">
		<svg class="alert-icon" width="20" height="20" viewBox="0 0 20 20" fill="none">
			<path d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-1-6l5-5-1-1-4 4-2-2-1 1 3 3z" fill="currentColor" />
		</svg>
		<span><?php echo $this->session->flashdata('success'); ?></span>
		<button class="alert-close" onclick="this.parentElement.remove()">
			<svg width="16" height="16" viewBox="0 0 16 16" fill="none">
				<path d="M12 4L4 12M4 4l8 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
			</svg>
		</button>
	</div>
<?php endif; ?>

<style>
	* {
		box-sizing: border-box;
	}

	/* Alert Messages */
	.alert-message {
		display: flex;
		align-items: center;
		gap: 12px;
		padding: 16px 20px;
		border-radius: 16px;
		margin-bottom: 24px;
		animation: slideIn 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
		box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
		position: relative;
		overflow: hidden;
	}

	.alert-message::before {
		content: '';
		position: absolute;
		left: 0;
		top: 0;
		width: 4px;
		height: 100%;
		opacity: 0.8;
	}

	@keyframes slideIn {
		from {
			opacity: 0;
			transform: translateY(-20px) scale(0.95);
		}

		to {
			opacity: 1;
			transform: translateY(0) scale(1);
		}
	}

	@keyframes slideOut {
		to {
			opacity: 0;
			transform: translateY(-20px) scale(0.95);
		}
	}

	.alert-error {
		background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
		border: 2px solid #fecaca;
		color: #991b1b;
	}

	.alert-error::before {
		background: #dc2626;
	}

	.alert-success {
		background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
		border: 2px solid #bbf7d0;
		color: #166534;
	}

	.alert-success::before {
		background: #22c55e;
	}

	.alert-icon {
		flex-shrink: 0;
	}

	.alert-message span {
		flex: 1;
		font-size: 14px;
		font-weight: 600;
	}

	.alert-close {
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

	.alert-close:hover {
		opacity: 1;
		background: rgba(0, 0, 0, 0.08);
		transform: rotate(90deg);
	}

	/* Stats Section */
	.admin-stats {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
		gap: 24px;
		margin-bottom: 32px;
	}

	.admin-stat-card {
		background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
		border-radius: 20px;
		padding: 28px;
		color: white;
		position: relative;
		overflow: hidden;
		box-shadow: 0 8px 24px rgba(102, 126, 234, 0.25);
		transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
	}

	.admin-stat-card:hover {
		transform: translateY(-8px) scale(1.02);
		box-shadow: 0 16px 40px rgba(102, 126, 234, 0.35);
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

	.admin-stat-card:hover::before {
		transform: rotate(180deg);
	}

	.admin-stat-card::after {
		content: '';
		position: absolute;
		bottom: 0;
		right: 0;
		width: 120px;
		height: 120px;
		background: rgba(255, 255, 255, 0.05);
		border-radius: 50%;
		transform: translate(40%, 40%);
		transition: transform 0.4s ease;
	}

	.admin-stat-card:hover::after {
		transform: translate(30%, 30%) scale(1.3);
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
		text-transform: uppercase;
		letter-spacing: 1px;
		margin-bottom: 12px;
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
		display: inline-flex;
		align-items: center;
		gap: 8px;
		padding: 10px 18px;
		background: rgba(255, 255, 255, 0.15);
		border-radius: 12px;
		transition: all 0.3s ease;
		margin-top: 8px;
		border: 1px solid rgba(255, 255, 255, 0.2);
		backdrop-filter: blur(10px);
	}

	.quick-link:hover {
		background: rgba(255, 255, 255, 0.25);
		transform: translateX(4px);
		box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
	}

	.quick-link::after {
		content: '→';
		font-size: 18px;
		transition: transform 0.3s ease;
	}

	.quick-link:hover::after {
		transform: translateX(4px);
	}

	/* Layout */
	.questions-layout {
		display: grid;
		grid-template-columns: minmax(0, 400px) minmax(0, 1fr);
		gap: 28px;
		align-items: start;
	}

	.questions-side-card,
	.questions-main-card {
		background: #ffffff;
		border: 2px solid #e5e7eb;
		border-radius: 24px;
		padding: 32px;
		box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02), 0 12px 24px rgba(0, 0, 0, 0.03);
		transition: all 0.3s ease;
		position: relative;
		overflow: hidden;
	}

	.questions-side-card::before,
	.questions-main-card::before {
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

	.questions-side-card:hover,
	.questions-main-card:hover {
		border-color: #d1d5db;
		box-shadow: 0 8px 16px rgba(0, 0, 0, 0.04), 0 20px 40px rgba(0, 0, 0, 0.06);
		transform: translateY(-2px);
	}

	.questions-side-card {
		position: sticky;
		top: 20px;
	}

	.questions-main-card .admin-panel-head,
	.questions-side-card .admin-panel-head {
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
		display: flex;
		align-items: center;
		gap: 12px;
		background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
		-webkit-background-clip: text;
		-webkit-text-fill-color: transparent;
		background-clip: text;
	}

	.section-title::before {
		content: '';
		width: 5px;
		height: 28px;
		background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
		border-radius: 4px;
		flex-shrink: 0;
	}

	.admin-panel-head p {
		margin: 0;
		font-size: 14px;
		color: #6b7280;
		line-height: 1.6;
		font-weight: 500;
	}

	/* Form Styling */
	.form-group {
		margin-bottom: 24px;
	}

	.form-group label {
		display: flex;
		align-items: center;
		margin-bottom: 10px;
		font-weight: 700;
		color: #374151;
		font-size: 14px;
		letter-spacing: 0.3px;
	}

	.form-group label svg {
		margin-right: 6px;
		color: #667eea;
	}

	.form-group select,
	.form-group input[type="number"],
	.form-group input[type="datetime-local"] {
		width: 100%;
		padding: 14px 16px;
		border: 2px solid #e5e7eb;
		border-radius: 14px;
		font-size: 14px;
		transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
		background: #f9fafb;
		font-weight: 500;
		color: #1f2937;
	}

	.form-group select:focus,
	.form-group input[type="number"]:focus,
	.form-group input[type="datetime-local"]:focus {
		outline: none;
		border-color: #667eea;
		background: white;
		box-shadow: 0 0 0 5px rgba(102, 126, 234, 0.1), 0 4px 12px rgba(102, 126, 234, 0.15);
		transform: translateY(-2px);
	}

	.form-grid {
		display: grid;
		grid-template-columns: repeat(2, 1fr);
		gap: 16px;
	}

	.question-count-picker {
		display: grid;
		grid-template-columns: repeat(4, minmax(0, 1fr));
		gap: 12px;
		margin-top: 14px;
	}

	.question-count-option {
		border: 2px solid #e5e7eb;
		border-radius: 14px;
		padding: 16px 14px;
		background: linear-gradient(135deg, #f9fafb 0%, #ffffff 100%);
		text-align: center;
		font-size: 16px;
		font-weight: 800;
		color: #374151;
		cursor: pointer;
		transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
		position: relative;
		overflow: hidden;
	}

	.question-count-option::before {
		content: '';
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
		opacity: 0;
		transition: opacity 0.3s ease;
	}

	.question-count-option span {
		position: relative;
		z-index: 1;
	}

	.question-count-option:hover {
		border-color: #667eea;
		background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
		color: #4f46e5;
		transform: translateY(-4px) scale(1.05);
		box-shadow: 0 6px 20px rgba(102, 126, 234, 0.25);
	}

	.question-count-option.active {
		border-color: #667eea;
		background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
		color: white;
		box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
		transform: translateY(-2px);
	}

	.question-count-option.active::before {
		opacity: 1;
	}

	/* Hints Section */
	.questions-hint {
		display: grid;
		gap: 16px;
		margin-top: 32px;
		padding-top: 28px;
		border-top: 2px solid #f3f4f6;
	}

	.questions-hint-item {
		padding: 18px 20px;
		padding-left: 58px;
		border-radius: 14px;
		background: linear-gradient(135deg, #f8fbff 0%, #f0f9ff 100%);
		border: 2px solid #dbeafe;
		position: relative;
		transition: all 0.3s ease;
	}

	.questions-hint-item:hover {
		transform: translateX(4px);
		box-shadow: 0 4px 12px rgba(102, 126, 234, 0.1);
		border-color: #bae6fd;
	}

	.questions-hint-item::before {
		content: '';
		position: absolute;
		left: 20px;
		top: 18px;
		width: 28px;
		height: 28px;
		background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
		border-radius: 50%;
		display: flex;
		align-items: center;
		justify-content: center;
		color: white;
		font-size: 13px;
		font-weight: 800;
		box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
	}

	.questions-hint-item:nth-child(1)::before {
		content: '1';
		background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
	}

	.questions-hint-item:nth-child(2)::before {
		content: '2';
		background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
	}

	.questions-hint-item:nth-child(3)::before {
		content: '3';
		background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
	}

	.questions-hint-item strong {
		display: block;
		margin-bottom: 6px;
		font-size: 14px;
		color: #1f2937;
		font-weight: 800;
		letter-spacing: 0.3px;
	}

	.questions-hint-item p {
		margin: 0;
		font-size: 13px;
		color: #6b7280;
		line-height: 1.6;
		font-weight: 500;
	}

	/* Summary Badge */
	.questions-summary {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 24px;
		padding: 24px 28px;
		border-radius: 18px;
		background: linear-gradient(135deg, #eef2ff 0%, #e0f2fe 100%);
		border: 2px solid #c7d2fe;
		margin-bottom: 32px;
		position: relative;
		overflow: hidden;
		box-shadow: 0 4px 12px rgba(102, 126, 234, 0.1);
	}

	.questions-summary::before {
		content: '';
		position: absolute;
		top: -50%;
		left: -50%;
		width: 200%;
		height: 200%;
		background: radial-gradient(circle, rgba(102, 126, 234, 0.08) 0%, transparent 70%);
	}

	.questions-summary>div:first-child {
		position: relative;
		z-index: 1;
		flex: 1;
	}

	.questions-summary strong {
		display: block;
		color: #1f2937;
		font-size: 20px;
		margin-bottom: 8px;
		font-weight: 800;
		background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
		-webkit-background-clip: text;
		-webkit-text-fill-color: transparent;
		background-clip: text;
	}

	.questions-summary span {
		display: block;
		color: #6b7280;
		font-size: 14px;
		font-weight: 600;
	}

	.questions-summary-badge {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		min-width: 72px;
		height: 72px;
		padding: 0 20px;
		border-radius: 18px;
		background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%);
		border: 3px solid #667eea;
		color: #667eea;
		font-size: 32px;
		font-weight: 900;
		position: relative;
		z-index: 1;
		box-shadow: 0 6px 20px rgba(102, 126, 234, 0.25);
		transition: all 0.3s ease;
	}

	.questions-summary:hover .questions-summary-badge {
		transform: scale(1.1) rotate(5deg);
		box-shadow: 0 8px 24px rgba(102, 126, 234, 0.35);
	}

	/* Questions Stack */
	.questions-stack {
		display: grid;
		gap: 24px;
	}

	.question-input-card {
		padding: 28px;
		border-radius: 18px;
		border: 2px solid #e5e7eb;
		background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%);
		transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
		position: relative;
		overflow: hidden;
	}

	.question-input-card::before {
		content: '';
		position: absolute;
		left: 0;
		top: 0;
		width: 5px;
		height: 100%;
		background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
		opacity: 0;
		transform: scaleY(0);
		transform-origin: top;
		transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
	}

	.question-input-card:hover {
		border-color: #667eea;
		background: white;
		box-shadow: 0 8px 24px rgba(102, 126, 234, 0.12);
		transform: translateX(4px);
	}

	.question-input-card:hover::before {
		opacity: 1;
		transform: scaleY(1);
	}

	.question-input-card h4 {
		margin: 0 0 20px;
		font-size: 18px;
		font-weight: 800;
		color: #1f2937;
		display: flex;
		align-items: center;
		gap: 12px;
		letter-spacing: 0.3px;
	}

	.question-input-card h4::before {
		content: '';
		width: 10px;
		height: 10px;
		background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
		border-radius: 50%;
		box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
	}

	.question-price-summary {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 16px;
		margin-top: 16px;
		padding: 16px 20px;
		border-radius: 14px;
		background: linear-gradient(135deg, #eef2ff 0%, #e0f2fe 100%);
		border: 2px solid #c7d2fe;
		transition: all 0.3s ease;
	}

	.question-price-summary:hover {
		border-color: #a5b4fc;
		transform: scale(1.02);
	}

	.question-price-summary span {
		display: block;
		font-size: 11px;
		font-weight: 800;
		text-transform: uppercase;
		letter-spacing: 0.1em;
		color: #64748b;
	}

	.question-price-summary strong {
		display: block;
		margin-top: 6px;
		font-size: 22px;
		color: #1f2937;
		font-weight: 900;
	}

	.question-price-summary small {
		color: #475569;
		font-size: 12px;
		font-weight: 600;
	}

	.question-input-card textarea {
		width: 100%;
		min-height: 130px;
		padding: 16px 18px;
		border: 2px solid #e5e7eb;
		border-radius: 14px;
		font-size: 14px;
		font-family: inherit;
		resize: vertical;
		transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
		background: #f9fafb;
		font-weight: 500;
		line-height: 1.6;
		color: #1f2937;
	}

	.question-input-card textarea:focus {
		outline: none;
		border-color: #667eea;
		background: white;
		box-shadow: 0 0 0 5px rgba(102, 126, 234, 0.1), 0 4px 12px rgba(102, 126, 234, 0.15);
		transform: translateY(-2px);
	}

	.question-input-card textarea::placeholder {
		color: #9ca3af;
		font-weight: 400;
	}

	/* Buttons */
	.btn-primary {
		background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
		color: white;
		border: none;
		padding: 16px 32px;
		border-radius: 14px;
		font-size: 15px;
		font-weight: 800;
		cursor: pointer;
		transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
		box-shadow: 0 6px 20px rgba(102, 126, 234, 0.35);
		display: inline-flex;
		align-items: center;
		justify-content: center;
		gap: 10px;
		letter-spacing: 0.5px;
		position: relative;
		overflow: hidden;
	}

	.btn-primary::before {
		content: '✓';
		font-size: 20px;
		transition: transform 0.3s ease;
	}

	.btn-primary::after {
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

	.btn-primary:hover::after {
		width: 300px;
		height: 300px;
	}

	.btn-primary:hover {
		transform: translateY(-3px) scale(1.02);
		box-shadow: 0 10px 30px rgba(102, 126, 234, 0.45);
	}

	.btn-primary:hover::before {
		transform: scale(1.2) rotate(20deg);
	}

	.btn-primary:active {
		transform: translateY(-1px) scale(0.98);
	}

	.btn-secondary-link {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		padding: 16px 28px;
		border-radius: 14px;
		border: 2px solid #e5e7eb;
		background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%);
		color: #374151;
		text-decoration: none;
		font-weight: 800;
		font-size: 15px;
		transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
		letter-spacing: 0.3px;
	}

	.btn-secondary-link:hover {
		border-color: #667eea;
		color: #667eea;
		background: white;
		transform: translateY(-3px);
		box-shadow: 0 6px 20px rgba(102, 126, 234, 0.15);
	}

	.questions-actions {
		display: flex;
		align-items: center;
		gap: 16px;
		margin-top: 32px;
		padding-top: 28px;
		border-top: 2px solid #f3f4f6;
	}

	.questions-actions .btn-primary {
		flex: 1;
		min-width: 240px;
	}

	/* Empty State */
	.empty-state {
		padding: 80px 32px;
		border-radius: 24px;
		border: 3px dashed #cbd5e1;
		background: linear-gradient(135deg, #f8fbff 0%, #f8fafc 100%);
		text-align: center;
		transition: all 0.3s ease;
	}

	.empty-state:hover {
		border-color: #94a3b8;
		background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
	}

	.empty-state::before {
		content: '📝';
		font-size: 80px;
		display: block;
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

	.empty-state h4 {
		margin: 0 0 14px;
		color: #1f2937;
		font-size: 24px;
		font-weight: 800;
		background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
		-webkit-background-clip: text;
		-webkit-text-fill-color: transparent;
		background-clip: text;
	}

	.empty-state p {
		margin: 0;
		max-width: 520px;
		margin-left: auto;
		margin-right: auto;
		color: #6b7280;
		font-size: 15px;
		line-height: 1.7;
		font-weight: 500;
	}

	/* Progress Indicator */
	.form-progress {
		display: flex;
		align-items: center;
		gap: 12px;
		margin-bottom: 28px;
		padding: 18px 24px;
		background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
		border: 2px solid #bbf7d0;
		border-radius: 16px;
		box-shadow: 0 4px 12px rgba(34, 197, 94, 0.1);
		animation: slideIn 0.5s ease;
	}

	.form-progress-icon {
		width: 28px;
		height: 28px;
		background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
		color: white;
		border-radius: 50%;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 16px;
		font-weight: 800;
		box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
		flex-shrink: 0;
	}

	.form-progress-text {
		flex: 1;
		font-size: 14px;
		color: #166534;
		font-weight: 700;
		letter-spacing: 0.3px;
	}

	/* Responsive */
	@media (max-width: 1024px) {
		.questions-layout {
			grid-template-columns: 1fr;
		}

		.questions-side-card {
			position: static;
		}
	}

	@media (max-width: 768px) {
		.admin-stats {
			grid-template-columns: 1fr;
		}

		.questions-side-card,
		.questions-main-card {
			padding: 24px;
		}

		.question-count-picker {
			grid-template-columns: repeat(2, minmax(0, 1fr));
		}

		.form-grid {
			grid-template-columns: 1fr;
		}

		.questions-summary {
			flex-direction: column;
			align-items: flex-start;
		}

		.questions-summary-badge {
			align-self: flex-end;
		}

		.questions-actions {
			flex-direction: column;
			align-items: stretch;
		}

		.questions-actions .btn-primary,
		.btn-secondary-link {
			width: 100%;
		}

		.section-title {
			font-size: 20px;
		}

		.admin-stat-value {
			font-size: 36px;
		}

		.question-input-card {
			padding: 20px;
		}
	}

	@media (max-width: 480px) {
		.question-count-picker {
			grid-template-columns: repeat(2, minmax(0, 1fr));
		}

		.questions-side-card,
		.questions-main-card {
			padding: 20px;
		}

		.admin-stat-card {
			padding: 20px;
		}

		.admin-stat-value {
			font-size: 32px;
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

	/* Custom Scrollbar */
	::-webkit-scrollbar {
		width: 10px;
		height: 10px;
	}

	::-webkit-scrollbar-track {
		background: #f1f5f9;
		border-radius: 10px;
	}

	::-webkit-scrollbar-thumb {
		background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
		border-radius: 10px;
	}

	::-webkit-scrollbar-thumb:hover {
		background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
	}
</style>

<section class="admin-stats">
	<div class="admin-stat-card">
		<div class="admin-stat-label">Total Questions</div>
		<div class="admin-stat-value"><?php echo number_format((int) $total_questions); ?></div>
		<div class="admin-stat-note">Questions currently available across all categories.</div>
	</div>
	<div class="admin-stat-card">
		<div class="admin-stat-label">Categories</div>
		<div class="admin-stat-value"><?php echo number_format(count($categories)); ?></div>
		<div class="admin-stat-note">Choose one category, then add multiple questions in one go.</div>
	</div>
	<div class="admin-stat-card">
		<div class="admin-stat-label">Quick Actions</div>
		<div class="admin-stat-note">
			<a class="quick-link" href="<?php echo site_url('admin/questions/view'); ?>">View All Questions</a>
		</div>
	</div>
</section>

<section class="questions-layout">
	<aside class="questions-side-card">
		<div class="admin-panel-head">
			<div>
				<h3 class="section-title">Setup Wizard</h3>
				<p>Configure your question batch by selecting a category and quantity.</p>
			</div>
		</div>

		<form method="get" action="<?php echo site_url('admin/questions/add'); ?>" id="questionSetupForm">
			<div class="form-group">
				<label for="category_id">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none">
						<path d="M3 7h18M3 12h18M3 17h18" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
					</svg>
					Category
				</label>
				<select id="category_id" name="category_id" required>
					<option value="">Select a category</option>
					<?php foreach ($categories as $category_item): ?>
						<option value="<?php echo (int) $category_item->id; ?>" <?php echo $selected_category_id === (int) $category_item->id ? 'selected' : ''; ?>>
							<?php echo html_escape($category_item->name); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>

			<div class="form-group">
				<label for="question_count">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none">
						<path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2" stroke="currentColor" stroke-width="2" />
					</svg>
					Question Count
				</label>
				<input type="number" id="question_count" name="question_count" min="1" max="100" value="<?php echo $question_count; ?>" required>

				<div class="question-count-picker">
					<button class="question-count-option<?php echo $question_count === 1 ? ' active' : ''; ?>" type="button" data-count="1"><span>1</span></button>
					<button class="question-count-option<?php echo $question_count === 5 ? ' active' : ''; ?>" type="button" data-count="5"><span>5</span></button>
					<button class="question-count-option<?php echo $question_count === 10 ? ' active' : ''; ?>" type="button" data-count="10"><span>10</span></button>
					<button class="question-count-option<?php echo $question_count === 20 ? ' active' : ''; ?>" type="button" data-count="20"><span>20</span></button>
				</div>
			</div>

			<button class="btn-primary" type="submit" style="width: 100%;"><span>Generate Form</span></button>
		</form>

		<div class="questions-hint">
			<div class="questions-hint-item">
				<strong>Choose Category</strong>
				<p>Select the category where these questions will be organized.</p>
			</div>
			<div class="questions-hint-item">
				<strong>Set Quantity</strong>
				<p>Decide how many question input fields you want to create.</p>
			</div>
			<div class="questions-hint-item">
				<strong>Fill & Save</strong>
				<p>Complete all questions and save them in bulk to your database.</p>
			</div>
		</div>
	</aside>

	<section class="questions-main-card">
		<div class="admin-panel-head">
			<div>
				<h3 class="section-title">Question Builder</h3>
				<p>Create and manage multiple questions efficiently in one organized interface.</p>
			</div>
		</div>

		<?php if ($selected_category_id > 0 && $selected_category_name !== ''): ?>
			<?php if ($question_count > 0): ?>
				<div class="form-progress">
					<div class="form-progress-icon">✓</div>
					<div class="form-progress-text">
						Form ready! You can now add <?php echo $question_count; ?> question<?php echo $question_count > 1 ? 's' : ''; ?>.
					</div>
				</div>
			<?php endif; ?>

			<div class="questions-summary">
				<div>
					<strong><?php echo html_escape($selected_category_name); ?></strong>
					<span>Preparing <?php echo $question_count; ?> question<?php echo $question_count > 1 ? 's' : ''; ?> for this category</span>
				</div>
				<div class="questions-summary-badge"><?php echo $question_count; ?></div>
			</div>

			<form method="post" action="<?php echo site_url('admin/questions/create'); ?>">
				<input type="hidden" name="category_id" value="<?php echo $selected_category_id; ?>">
				<input type="hidden" name="question_count" value="<?php echo $question_count; ?>">

				<div class="form-grid" style="margin-bottom:24px;">
					<div class="form-group" style="margin-bottom: 0;">
						<label for="start_time">
							<svg width="16" height="16" viewBox="0 0 24 24" fill="none">
								<circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2" />
								<path d="M12 7v5l3 3" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
							</svg>
							Start Time
						</label>
						<input type="datetime-local" id="start_time" name="start_time" value="<?php echo set_value('start_time'); ?>">
					</div>
					<div class="form-group" style="margin-bottom: 0;">
						<label for="end_time">
							<svg width="16" height="16" viewBox="0 0 24 24" fill="none">
								<circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2" />
								<path d="M12 7v5l3 3" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
							</svg>
							End Time
						</label>
						<input type="datetime-local" id="end_time" name="end_time" value="<?php echo set_value('end_time'); ?>">
					</div>
				</div>

				<div class="form-group" style="margin-bottom:28px;">
					<label for="status">
						<svg width="16" height="16" viewBox="0 0 24 24" fill="none">
							<path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
						</svg>
						Market Status
					</label>
					<select id="status" name="status" required>
						<option value="draft" <?php echo set_value('status', 'draft') === 'draft' ? 'selected' : ''; ?>>📝 Draft</option>
						<option value="open" <?php echo set_value('status') === 'open' ? 'selected' : ''; ?>>🟢 Open</option>
						<option value="closed" <?php echo set_value('status') === 'closed' ? 'selected' : ''; ?>>🔴 Closed</option>
					</select>
				</div>

				<div class="questions-stack">
					<?php for ($i = 0; $i < $question_count; $i++): ?>
						<div class="question-input-card">
							<h4>Question <?php echo $i + 1; ?></h4>
							<div class="form-group">
								<label for="question_<?php echo $i; ?>">Question Text</label>
								<textarea id="question_<?php echo $i; ?>" name="questions[<?php echo $i; ?>]" placeholder="Enter your question here..." required><?php echo set_value('questions[' . $i . ']'); ?></textarea>
							</div>
							<div class="form-grid">
								<div class="form-group" style="margin-bottom: 0;">
									<label for="yes_price_<?php echo $i; ?>">Yes Price</label>
									<input type="number" class="js-yes-price" id="yes_price_<?php echo $i; ?>" name="yes_prices[<?php echo $i; ?>]" min="0.01" step="0.01" value="<?php echo set_value('yes_prices[' . $i . ']', '10.00'); ?>" required>
								</div>
								<div class="form-group" style="margin-bottom: 0;">
									<label for="no_price_<?php echo $i; ?>">No Price</label>
									<input type="number" class="js-no-price" id="no_price_<?php echo $i; ?>" name="no_prices[<?php echo $i; ?>]" min="0.01" step="0.01" value="<?php echo set_value('no_prices[' . $i . ']', '10.00'); ?>" required>
								</div>
								<div class="form-group">
									<label>Multiplier</label>
									<input type="number" step="0.01" name="multiplier" class="form-control" value="1.25" required>
								</div>
							</div>
							<div class="question-price-summary">
								<div>
									<span>Market Total</span>
									<strong class="js-market-total">Rs 20.00</strong>
								</div>
								<small>Admin controls the total using YES + NO.</small>
							</div>
						</div>
					<?php endfor; ?>
				</div>

				<div class="questions-actions">
					<button class="btn-primary" type="submit"><span>Save All Questions</span></button>
					<a class="btn-secondary-link" href="<?php echo site_url('admin/questions/view' . ($selected_category_id > 0 ? '?category_id=' . $selected_category_id : '')); ?>">
						View Existing Questions
					</a>
				</div>
			</form>
		<?php else: ?>
			<div class="empty-state">
				<h4>Ready to Add Questions?</h4>
				<p>Use the setup wizard on the left to select a category and specify how many questions you'd like to create. Your form will appear here instantly.</p>
			</div>
		<?php endif; ?>
	</section>
</section>

<script>
	document.addEventListener('DOMContentLoaded', function() {
		const countInput = document.getElementById('question_count');
		const countButtons = document.querySelectorAll('.question-count-option');
		const questionCards = document.querySelectorAll('.question-input-card');

		// Count button click handler
		countButtons.forEach(function(button) {
			button.addEventListener('click', function() {
				const count = this.getAttribute('data-count');
				countInput.value = count;

				countButtons.forEach(function(item) {
					item.classList.remove('active');
				});

				this.classList.add('active');
			});
		});

		// Price calculation for each question
		questionCards.forEach(function(card) {
			const yesInput = card.querySelector('.js-yes-price');
			const noInput = card.querySelector('.js-no-price');
			const totalNode = card.querySelector('.js-market-total');

			if (!yesInput || !noInput || !totalNode) {
				return;
			}

			const updateTotal = function() {
				const yes = parseFloat(yesInput.value || '0');
				const no = parseFloat(noInput.value || '0');
				const total = yes + no;
				totalNode.textContent = 'Rs ' + total.toFixed(2);

				// Visual feedback for price changes
				totalNode.style.transform = 'scale(1.1)';
				setTimeout(() => {
					totalNode.style.transform = 'scale(1)';
				}, 200);
			};

			yesInput.addEventListener('input', updateTotal);
			noInput.addEventListener('input', updateTotal);
			updateTotal();
		});

		// Auto-dismiss alerts after 5 seconds
		const alerts = document.querySelectorAll('.alert-message');
		alerts.forEach(function(alert) {
			setTimeout(function() {
				alert.style.animation = 'slideOut 0.3s ease forwards';
				setTimeout(function() {
					alert.remove();
				}, 300);
			}, 5000);
		});

		// Form validation
		const forms = document.querySelectorAll('form');
		forms.forEach(function(form) {
			form.addEventListener('submit', function(e) {
				const submitBtn = form.querySelector('button[type="submit"]');
				if (submitBtn) {
					submitBtn.disabled = true;
					submitBtn.style.opacity = '0.6';
					submitBtn.innerHTML = '<span>Processing...</span>';

					// Re-enable after 3 seconds in case of errors
					setTimeout(() => {
						submitBtn.disabled = false;
						submitBtn.style.opacity = '1';
					}, 3000);
				}
			});
		});

		// Add smooth scroll to top when form is submitted
		const questionBuilder = document.querySelector('.questions-main-card');
		if (questionBuilder && window.location.hash === '#success') {
			questionBuilder.scrollIntoView({
				behavior: 'smooth',
				block: 'start'
			});
		}
	});
</script>