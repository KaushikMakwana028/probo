<?php
$categories = isset($categories) && is_array($categories) ? $categories : array();
$selected_category_id = (int) $this->input->get('category_id');
$question_count       = (int) $this->input->get('question_count');
if ($question_count <= 0)   $question_count = 1;
if ($question_count > 100)  $question_count = 100;

$selected_category_name = '';
foreach ($categories as $cat) {
	if ((int) $cat->id === $selected_category_id) {
		$selected_category_name = $cat->name;
		break;
	}
}
?>

<?php if ($this->session->flashdata('error')): ?>
	<div class="q-alert q-alert--error">
		<svg width="16" height="16" viewBox="0 0 20 20" fill="none">
			<circle cx="10" cy="10" r="9" stroke="currentColor" stroke-width="2" />
			<path d="M10 6v4m0 4h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
		</svg>
		<span><?php echo $this->session->flashdata('error'); ?></span>
		<button onclick="this.parentElement.remove()">×</button>
	</div>
<?php endif; ?>
<?php if ($this->session->flashdata('success')): ?>
	<div class="q-alert q-alert--success">
		<svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
			<path d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-1-6l5-5-1-1-4 4-2-2-1 1 3 3z" />
		</svg>
		<span><?php echo $this->session->flashdata('success'); ?></span>
		<button onclick="this.parentElement.remove()">×</button>
	</div>
<?php endif; ?>

<style>
	/* ═══════════════════════════════════════════════
   TOKENS
═══════════════════════════════════════════════ */
	:root {
		--accent: #5b5ef4;
		--accent-light: #eeeeff;
		--accent-dark: #4344d4;
		--surface: #ffffff;
		--surface-2: #f7f8fc;
		--border: #e4e6ef;
		--border-focus: #5b5ef4;
		--text-1: #12131a;
		--text-2: #4b5066;
		--text-3: #9499b0;
		--green: #12b76a;
		--green-bg: #edfaf3;
		--green-border: #a3e6c7;
		--red: #e03e3e;
		--red-bg: #fff1f1;
		--red-border: #fcc;
		--radius-sm: 8px;
		--radius-md: 12px;
		--radius-lg: 16px;
		--shadow-sm: 0 1px 3px rgba(18, 19, 26, .06), 0 1px 2px rgba(18, 19, 26, .04);
		--shadow-md: 0 4px 12px rgba(18, 19, 26, .08), 0 2px 4px rgba(18, 19, 26, .04);
		--shadow-lg: 0 8px 24px rgba(18, 19, 26, .10), 0 2px 8px rgba(18, 19, 26, .04);
	}

	/* ═══════════════════════════════════════════════
   STATS STRIP
═══════════════════════════════════════════════ */
	.qs-stats {
		display: grid;
		grid-template-columns: repeat(3, 1fr);
		gap: 14px;
		margin-bottom: 22px;
	}

	.qs-stat {
		background: var(--surface);
		border: 1px solid var(--border);
		border-radius: var(--radius-md);
		padding: 18px 20px;
		display: flex;
		align-items: center;
		gap: 16px;
		box-shadow: var(--shadow-sm);
		transition: box-shadow .2s, border-color .2s;
	}

	.qs-stat:hover {
		box-shadow: var(--shadow-md);
		border-color: #d0d2e0;
	}

	.qs-stat-icon {
		width: 46px;
		height: 46px;
		border-radius: var(--radius-sm);
		display: flex;
		align-items: center;
		justify-content: center;
		flex-shrink: 0;
	}

	.qs-stat-icon--indigo {
		background: #eeeeff;
	}

	.qs-stat-icon--rose {
		background: #fff0f3;
	}

	.qs-stat-icon--teal {
		background: #e6faf4;
	}

	.qs-stat-body {}

	.qs-stat-num {
		font-size: 26px;
		font-weight: 800;
		color: var(--text-1);
		line-height: 1;
		letter-spacing: -.5px;
	}

	.qs-stat-label {
		font-size: 12px;
		color: var(--text-3);
		font-weight: 600;
		margin-top: 3px;
		text-transform: uppercase;
		letter-spacing: .04em;
	}

	.qs-stat-link {
		display: inline-flex;
		align-items: center;
		gap: 4px;
		font-size: 13px;
		font-weight: 600;
		color: var(--accent);
		text-decoration: none;
		margin-top: 2px;
	}

	.qs-stat-link:hover {
		text-decoration: underline;
	}

	.qs-stat-link svg {
		transition: transform .2s;
	}

	.qs-stat-link:hover svg {
		transform: translateX(3px);
	}

	/* ═══════════════════════════════════════════════
   LAYOUT
═══════════════════════════════════════════════ */
	.qs-layout {
		display: grid;
		grid-template-columns: 310px 1fr;
		gap: 20px;
		align-items: start;
	}

	/* ═══════════════════════════════════════════════
   CARD
═══════════════════════════════════════════════ */
	.qs-card {
		background: var(--surface);
		border: 1px solid var(--border);
		border-radius: var(--radius-lg);
		box-shadow: var(--shadow-sm);
		overflow: hidden;
	}

	.qs-card--sticky {
		position: sticky;
		top: 16px;
	}

	.qs-card-header {
		padding: 20px 22px 16px;
		border-bottom: 1px solid var(--border);
		display: flex;
		align-items: flex-start;
		gap: 12px;
	}

	.qs-card-hicon {
		width: 36px;
		height: 36px;
		background: var(--accent-light);
		border-radius: var(--radius-sm);
		display: flex;
		align-items: center;
		justify-content: center;
		flex-shrink: 0;
		margin-top: 1px;
	}

	.qs-card-htitle {
		font-size: 15px;
		font-weight: 700;
		color: var(--text-1);
		margin: 0 0 3px;
		letter-spacing: -.1px;
	}

	.qs-card-hsub {
		font-size: 12.5px;
		color: var(--text-3);
		margin: 0;
		line-height: 1.5;
	}

	.qs-card-body {
		padding: 20px 22px;
	}

	/* ═══════════════════════════════════════════════
   FORM
═══════════════════════════════════════════════ */
	.qs-field {
		margin-bottom: 16px;
	}

	.qs-field:last-child {
		margin-bottom: 0;
	}

	.qs-label {
		display: flex;
		align-items: center;
		gap: 6px;
		font-size: 12.5px;
		font-weight: 700;
		color: var(--text-2);
		margin-bottom: 7px;
		letter-spacing: .02em;
	}

	.qs-label svg {
		color: var(--text-3);
		flex-shrink: 0;
	}

	.qs-input,
	.qs-select,
	.qs-textarea {
		width: 100%;
		padding: 10px 13px;
		border: 1.5px solid var(--border);
		border-radius: var(--radius-sm);
		font-size: 13.5px;
		color: var(--text-1);
		background: var(--surface-2);
		font-family: inherit;
		transition: border-color .15s, background .15s, box-shadow .15s;
		appearance: none;
		-webkit-appearance: none;
	}

	.qs-select {
		background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none'%3E%3Cpath d='M6 9l6 6 6-6' stroke='%239499b0' stroke-width='2' stroke-linecap='round'/%3E%3C/svg%3E");
		background-repeat: no-repeat;
		background-position: right 12px center;
		padding-right: 34px;
	}

	.qs-input:focus,
	.qs-select:focus,
	.qs-textarea:focus {
		outline: none;
		border-color: var(--border-focus);
		background: var(--surface);
		box-shadow: 0 0 0 3px rgba(91, 94, 244, .12);
	}

	.qs-textarea {
		min-height: 96px;
		resize: vertical;
		line-height: 1.55;
	}

	.qs-textarea::placeholder {
		color: var(--text-3);
		font-size: 13px;
	}

	.qs-grid-2 {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 12px;
	}

	/* ═══════════════════════════════════════════════
   COUNT PILLS
═══════════════════════════════════════════════ */
	.qs-pills {
		display: flex;
		gap: 8px;
		margin-top: 9px;
	}

	.qs-pill {
		flex: 1;
		padding: 8px 6px;
		border: 1.5px solid var(--border);
		border-radius: var(--radius-sm);
		background: var(--surface-2);
		color: var(--text-2);
		font-size: 13px;
		font-weight: 700;
		cursor: pointer;
		text-align: center;
		transition: all .15s;
		font-family: inherit;
	}

	.qs-pill:hover {
		border-color: var(--accent);
		color: var(--accent);
		background: var(--accent-light);
	}

	.qs-pill.active {
		border-color: var(--accent);
		background: var(--accent);
		color: #fff;
		box-shadow: 0 2px 8px rgba(91, 94, 244, .3);
	}

	/* ═══════════════════════════════════════════════
   BUTTON
═══════════════════════════════════════════════ */
	.qs-btn {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		gap: 8px;
		padding: 11px 20px;
		border-radius: var(--radius-sm);
		font-size: 13.5px;
		font-weight: 700;
		cursor: pointer;
		border: none;
		font-family: inherit;
		text-decoration: none;
		white-space: nowrap;
		transition: all .15s;
		letter-spacing: .01em;
	}

	.qs-btn--primary {
		background: var(--accent);
		color: #fff;
		box-shadow: 0 2px 6px rgba(91, 94, 244, .3);
	}

	.qs-btn--primary:hover {
		background: var(--accent-dark);
		box-shadow: 0 4px 12px rgba(91, 94, 244, .38);
		transform: translateY(-1px);
	}

	.qs-btn--primary:active {
		transform: translateY(0);
	}

	.qs-btn--full {
		width: 100%;
	}

	.qs-btn--ghost {
		background: transparent;
		color: var(--text-2);
		border: 1.5px solid var(--border);
	}

	.qs-btn--ghost:hover {
		border-color: var(--accent);
		color: var(--accent);
		background: var(--accent-light);
	}

	/* ═══════════════════════════════════════════════
   STEPS (sidebar hints)
═══════════════════════════════════════════════ */
	.qs-steps {
		margin-top: 20px;
		padding-top: 18px;
		border-top: 1px solid var(--border);
		display: flex;
		flex-direction: column;
		gap: 12px;
	}

	.qs-step {
		display: flex;
		align-items: flex-start;
		gap: 12px;
	}

	.qs-step-num {
		width: 24px;
		height: 24px;
		border-radius: 50%;
		background: var(--accent-light);
		color: var(--accent);
		font-size: 11px;
		font-weight: 800;
		display: flex;
		align-items: center;
		justify-content: center;
		flex-shrink: 0;
		margin-top: 1px;
		border: 1.5px solid rgba(91, 94, 244, .2);
	}

	.qs-step-title {
		font-size: 13px;
		font-weight: 700;
		color: var(--text-1);
	}

	.qs-step-desc {
		font-size: 12px;
		color: var(--text-3);
		margin-top: 2px;
		line-height: 1.5;
	}

	/* ═══════════════════════════════════════════════
   SUMMARY BANNER
═══════════════════════════════════════════════ */
	.qs-summary {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 16px;
		padding: 14px 18px;
		background: linear-gradient(135deg, var(--accent-light) 0%, #f0f0ff 100%);
		border: 1.5px solid rgba(91, 94, 244, .22);
		border-radius: var(--radius-md);
		margin-bottom: 18px;
	}

	.qs-summary-cat {
		font-size: 14px;
		font-weight: 800;
		color: var(--accent-dark);
		letter-spacing: -.1px;
	}

	.qs-summary-meta {
		font-size: 12px;
		color: #7072bb;
		margin-top: 3px;
		font-weight: 500;
	}

	.qs-summary-badge {
		min-width: 46px;
		height: 46px;
		border-radius: 10px;
		background: var(--accent);
		color: #fff;
		font-size: 20px;
		font-weight: 800;
		display: flex;
		align-items: center;
		justify-content: center;
		flex-shrink: 0;
		box-shadow: 0 3px 10px rgba(91, 94, 244, .35);
	}

	/* ═══════════════════════════════════════════════
   NOTICE
═══════════════════════════════════════════════ */
	.qs-notice {
		display: flex;
		align-items: center;
		gap: 10px;
		padding: 11px 15px;
		background: var(--green-bg);
		border: 1px solid var(--green-border);
		border-radius: var(--radius-sm);
		margin-bottom: 16px;
		font-size: 13px;
		color: #0a6640;
		font-weight: 600;
	}

	.qs-notice svg {
		flex-shrink: 0;
	}

	/* ═══════════════════════════════════════════════
   QUESTION CARDS
═══════════════════════════════════════════════ */
	.qs-qstack {
		display: flex;
		flex-direction: column;
		gap: 14px;
	}

	.qs-qcard {
		border: 1.5px solid var(--border);
		border-radius: var(--radius-md);
		background: var(--surface);
		overflow: hidden;
		box-shadow: var(--shadow-sm);
		transition: border-color .2s, box-shadow .2s;
	}

	.qs-qcard:hover {
		border-color: rgba(91, 94, 244, .35);
		box-shadow: var(--shadow-md);
	}

	.qs-qcard-header {
		display: flex;
		align-items: center;
		gap: 10px;
		padding: 13px 18px;
		background: var(--surface-2);
		border-bottom: 1px solid var(--border);
	}

	.qs-qcard-num {
		width: 26px;
		height: 26px;
		border-radius: 7px;
		background: var(--accent);
		color: #fff;
		font-size: 12px;
		font-weight: 800;
		display: flex;
		align-items: center;
		justify-content: center;
		flex-shrink: 0;
	}

	.qs-qcard-title {
		font-size: 13px;
		font-weight: 700;
		color: var(--text-1);
		letter-spacing: -.1px;
	}

	.qs-qcard-body {
		padding: 18px;
	}

	/* Price row */
	.qs-price-row {
		display: flex;
		align-items: center;
		justify-content: space-between;
		padding: 12px 16px;
		background: var(--surface-2);
		border: 1.5px solid var(--border);
		border-radius: var(--radius-sm);
		margin-top: 14px;
		gap: 12px;
	}

	.qs-price-label {
		font-size: 11px;
		font-weight: 700;
		text-transform: uppercase;
		letter-spacing: .06em;
		color: var(--text-3);
	}

	.qs-price-total {
		font-size: 18px;
		font-weight: 800;
		color: var(--text-1);
		margin-top: 3px;
		transition: color .2s;
	}

	.qs-price-hint {
		font-size: 11.5px;
		color: var(--text-3);
		font-weight: 500;
	}

	/* ═══════════════════════════════════════════════
   ACTIONS BAR
═══════════════════════════════════════════════ */
	.qs-actions {
		display: flex;
		gap: 10px;
		margin-top: 22px;
		padding-top: 18px;
		border-top: 1px solid var(--border);
	}

	.qs-actions .qs-btn--primary {
		flex: 1;
	}

	/* ═══════════════════════════════════════════════
   EMPTY STATE
═══════════════════════════════════════════════ */
	.qs-empty {
		padding: 52px 28px;
		text-align: center;
		border: 2px dashed var(--border);
		border-radius: var(--radius-md);
		background: var(--surface-2);
	}

	.qs-empty-icon {
		width: 56px;
		height: 56px;
		border-radius: 14px;
		background: var(--accent-light);
		display: flex;
		align-items: center;
		justify-content: center;
		margin: 0 auto 16px;
	}

	.qs-empty-title {
		font-size: 16px;
		font-weight: 800;
		color: var(--text-1);
		margin: 0 0 8px;
		letter-spacing: -.2px;
	}

	.qs-empty-desc {
		font-size: 13px;
		color: var(--text-3);
		max-width: 360px;
		margin: 0 auto;
		line-height: 1.65;
	}

	/* ═══════════════════════════════════════════════
   ALERTS
═══════════════════════════════════════════════ */
	.q-alert {
		display: flex;
		align-items: center;
		gap: 10px;
		padding: 13px 16px;
		border-radius: var(--radius-sm);
		margin-bottom: 16px;
		font-size: 13px;
		font-weight: 600;
		animation: qSlideIn .3s ease;
	}

	@keyframes qSlideIn {
		from {
			opacity: 0;
			transform: translateY(-8px);
		}

		to {
			opacity: 1;
			transform: translateY(0);
		}
	}

	.q-alert--error {
		background: var(--red-bg);
		border: 1px solid var(--red-border);
		color: var(--red);
	}

	.q-alert--success {
		background: var(--green-bg);
		border: 1px solid var(--green-border);
		color: #0a6640;
	}

	.q-alert span {
		flex: 1;
	}

	.q-alert button {
		background: none;
		border: none;
		cursor: pointer;
		font-size: 18px;
		line-height: 1;
		color: inherit;
		opacity: .55;
		padding: 0 2px;
	}

	.q-alert button:hover {
		opacity: 1;
	}

	/* ═══════════════════════════════════════════════
   RESPONSIVE
═══════════════════════════════════════════════ */
	@media (max-width: 960px) {
		.qs-layout {
			grid-template-columns: 1fr;
		}

		.qs-card--sticky {
			position: static;
		}
	}

	@media (max-width: 640px) {
		.qs-stats {
			grid-template-columns: 1fr 1fr;
		}

		.qs-stats .qs-stat:last-child {
			grid-column: span 2;
		}

		.qs-stat-num {
			font-size: 22px;
		}

		.qs-grid-2 {
			grid-template-columns: 1fr;
		}

		.qs-actions {
			flex-direction: column;
		}

		.qs-card-body,
		.qs-qcard-body {
			padding: 16px;
		}

		.qs-card-header {
			padding: 16px;
		}
	}

	@media (max-width: 420px) {
		.qs-stats {
			grid-template-columns: 1fr;
		}

		.qs-stats .qs-stat:last-child {
			grid-column: span 1;
		}

		.qs-summary {
			flex-direction: column;
			align-items: flex-start;
		}

		.qs-summary-badge {
			align-self: flex-end;
		}
	}
</style>

<!-- ─── STATS ─────────────────────────────────────── -->
<div class="qs-stats">
	<div class="qs-stat">
		<div class="qs-stat-icon qs-stat-icon--indigo">
			<svg width="20" height="20" viewBox="0 0 24 24" fill="none">
				<path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2m-3 7h.01M12 16h.01" stroke="#5b5ef4" stroke-width="2" stroke-linecap="round" />
			</svg>
		</div>
		<div class="qs-stat-body">
			<div class="qs-stat-num"><?php echo number_format((int)$total_questions); ?></div>
			<div class="qs-stat-label">Total Questions</div>
		</div>
	</div>

	<div class="qs-stat">
		<div class="qs-stat-icon qs-stat-icon--rose">
			<svg width="20" height="20" viewBox="0 0 24 24" fill="none">
				<path d="M4 6h16M4 10h16M4 14h10M4 18h6" stroke="#e05f8e" stroke-width="2" stroke-linecap="round" />
			</svg>
		</div>
		<div class="qs-stat-body">
			<div class="qs-stat-num"><?php echo number_format(count($categories)); ?></div>
			<div class="qs-stat-label">Categories</div>
		</div>
	</div>

	<div class="qs-stat">
		<div class="qs-stat-icon qs-stat-icon--teal">
			<svg width="20" height="20" viewBox="0 0 24 24" fill="none">
				<path d="M13 10V3L4 14h7v7l9-11h-7z" stroke="#0d9488" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
			</svg>
		</div>
		<div class="qs-stat-body">
			<div class="qs-stat-label" style="margin-bottom:4px;">Quick Access</div>
			<a class="qs-stat-link" href="<?php echo site_url('admin/questions/view'); ?>">
				View All Questions
				<svg width="13" height="13" viewBox="0 0 24 24" fill="none">
					<path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
				</svg>
			</a>
		</div>
	</div>
</div>

<!-- ─── LAYOUT ────────────────────────────────────── -->
<div class="qs-layout">

	<!-- LEFT: Setup Wizard -->
	<aside class="qs-card qs-card--sticky">
		<div class="qs-card-header">
			<div class="qs-card-hicon">
				<svg width="18" height="18" viewBox="0 0 24 24" fill="none">
					<path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" stroke="#5b5ef4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
				</svg>
			</div>
			<div>
				<h3 class="qs-card-htitle">Setup Wizard</h3>
				<p class="qs-card-hsub">Pick a category and quantity to generate your form.</p>
			</div>
		</div>

		<div class="qs-card-body">
			<form method="get" action="<?php echo site_url('admin/questions/add'); ?>" id="qSetupForm">

				<div class="qs-field">
					<label class="qs-label" for="category_id">
						<svg width="13" height="13" viewBox="0 0 24 24" fill="none">
							<path d="M4 6h16M4 10h16M4 14h10M4 18h6" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
						</svg>
						Category
					</label>
					<select class="qs-select" id="category_id" name="category_id" required>
						<option value="">Select a category</option>
						<?php foreach ($categories as $cat): ?>
							<option value="<?php echo (int)$cat->id; ?>" <?php echo $selected_category_id === (int)$cat->id ? 'selected' : ''; ?>>
								<?php echo html_escape($cat->name); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>

				<div class="qs-field">
					<label class="qs-label" for="question_count">
						<svg width="13" height="13" viewBox="0 0 24 24" fill="none">
							<path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2" stroke="currentColor" stroke-width="2" />
						</svg>
						Number of Questions
					</label>
					<input type="number" class="qs-input" id="question_count" name="question_count" min="1" max="100" value="<?php echo $question_count; ?>" required>
					<div class="qs-pills">
						<button class="qs-pill<?php echo $question_count === 1  ? ' active' : ''; ?>" type="button" data-count="1">1</button>
						<button class="qs-pill<?php echo $question_count === 5  ? ' active' : ''; ?>" type="button" data-count="5">5</button>
						<button class="qs-pill<?php echo $question_count === 10 ? ' active' : ''; ?>" type="button" data-count="10">10</button>
						<button class="qs-pill<?php echo $question_count === 20 ? ' active' : ''; ?>" type="button" data-count="20">20</button>
					</div>
				</div>

				<button class="qs-btn qs-btn--primary qs-btn--full" type="submit">
					<svg width="15" height="15" viewBox="0 0 24 24" fill="none">
						<path d="M13 10V3L4 14h7v7l9-11h-7z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
					</svg>
					Generate Form
				</button>
			</form>

			<div class="qs-steps">
				<div class="qs-step">
					<div class="qs-step-num">1</div>
					<div>
						<div class="qs-step-title">Choose Category</div>
						<div class="qs-step-desc">Select where these questions will be organised.</div>
					</div>
				</div>
				<div class="qs-step">
					<div class="qs-step-num">2</div>
					<div>
						<div class="qs-step-title">Set Quantity</div>
						<div class="qs-step-desc">Enter or tap how many inputs you need.</div>
					</div>
				</div>
				<div class="qs-step">
					<div class="qs-step-num">3</div>
					<div>
						<div class="qs-step-title">Fill &amp; Save</div>
						<div class="qs-step-desc">Complete each field and submit in bulk.</div>
					</div>
				</div>
			</div>
		</div>
	</aside>

	<!-- RIGHT: Question Builder -->
	<section class="qs-card">
		<div class="qs-card-header">
			<div class="qs-card-hicon">
				<svg width="18" height="18" viewBox="0 0 24 24" fill="none">
					<path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" stroke="#5b5ef4" stroke-width="2" stroke-linecap="round" />
					<path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" stroke="#5b5ef4" stroke-width="2" stroke-linecap="round" />
				</svg>
			</div>
			<div>
				<h3 class="qs-card-htitle">Question Builder</h3>
				<p class="qs-card-hsub">Create and manage multiple questions in one go.</p>
			</div>
		</div>

		<div class="qs-card-body">

			<?php if ($selected_category_id > 0 && $selected_category_name !== ''): ?>

				<div class="qs-notice">
					<svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor" style="color:var(--green)">
						<path d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-1-6l5-5-1-1-4 4-2-2-1 1 3 3z" />
					</svg>
					Form ready — you can now fill in <?php echo $question_count; ?> question<?php echo $question_count > 1 ? 's' : ''; ?>.
				</div>

				<div class="qs-summary">
					<div>
						<div class="qs-summary-cat"><?php echo html_escape($selected_category_name); ?></div>
						<div class="qs-summary-meta">Preparing <?php echo $question_count; ?> question<?php echo $question_count > 1 ? 's' : ''; ?> for this category</div>
					</div>
					<div class="qs-summary-badge"><?php echo $question_count; ?></div>
				</div>

				<form method="post" action="<?php echo site_url('admin/questions/create'); ?>">
					<input type="hidden" name="category_id" value="<?php echo $selected_category_id; ?>">
					<input type="hidden" name="question_count" value="<?php echo $question_count; ?>">

					<!-- Start / End / Status row -->
					<div class="qs-grid-2" style="margin-bottom:14px;">
						<div class="qs-field" style="margin-bottom:0">
							<label class="qs-label" for="start_time">
								<svg width="13" height="13" viewBox="0 0 24 24" fill="none">
									<circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2" />
									<path d="M12 7v5l3 3" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
								</svg>
								Start Time
							</label>
							<input type="datetime-local" class="qs-input" id="start_time" name="start_time" value="<?php echo set_value('start_time'); ?>">
						</div>
						<div class="qs-field" style="margin-bottom:0">
							<label class="qs-label" for="end_time">
								<svg width="13" height="13" viewBox="0 0 24 24" fill="none">
									<circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2" />
									<path d="M12 7v5l3 3" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
								</svg>
								End Time
							</label>
							<input type="datetime-local" class="qs-input" id="end_time" name="end_time" value="<?php echo set_value('end_time'); ?>">
						</div>
					</div>

					<div class="qs-field" style="margin-bottom:22px;">
						<label class="qs-label" for="status">
							<svg width="13" height="13" viewBox="0 0 24 24" fill="none">
								<path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
							</svg>
							Market Status
						</label>
						<select class="qs-select" id="status" name="status" required>
							<option value="draft" <?php echo set_value('status', 'draft') === 'draft'  ? 'selected' : ''; ?>>Draft</option>
							<option value="open" <?php echo set_value('status') === 'open'   ? 'selected' : ''; ?>>Open</option>
							<option value="closed" <?php echo set_value('status') === 'closed' ? 'selected' : ''; ?>>Closed</option>
						</select>
					</div>

					<!-- Question cards -->
					<div class="qs-qstack">
						<?php for ($i = 0; $i < $question_count; $i++): ?>
							<div class="qs-qcard">
								<div class="qs-qcard-header">
									<div class="qs-qcard-num"><?php echo $i + 1; ?></div>
									<div class="qs-qcard-title">Question <?php echo $i + 1; ?></div>
								</div>
								<div class="qs-qcard-body">
									<div class="qs-field">
										<label class="qs-label" for="question_<?php echo $i; ?>">Question Text</label>
										<textarea class="qs-textarea" id="question_<?php echo $i; ?>" name="questions[<?php echo $i; ?>]" placeholder="Type your question here…" required><?php echo set_value('questions[' . $i . ']'); ?></textarea>
									</div>
									<div class="qs-grid-2">
										<div class="qs-field" style="margin-bottom:0">
											<label class="qs-label" for="yes_price_<?php echo $i; ?>">Yes Price (₹)</label>
											<input type="number" class="qs-input js-yes" id="yes_price_<?php echo $i; ?>" name="yes_prices[<?php echo $i; ?>]" min="0.01" step="0.01" value="<?php echo set_value('yes_prices[' . $i . ']', '10.00'); ?>" required>
										</div>
										<div class="qs-field" style="margin-bottom:0">
											<label class="qs-label" for="no_price_<?php echo $i; ?>">No Price (₹)</label>
											<input type="number" class="qs-input js-no" id="no_price_<?php echo $i; ?>" name="no_prices[<?php echo $i; ?>]" min="0.01" step="0.01" value="<?php echo set_value('no_prices[' . $i . ']', '10.00'); ?>" required>
										</div>
									</div>
									<div class="qs-price-row">
										<div>
											<div class="qs-price-label">Market Total</div>
											<div class="qs-price-total js-total">₹20.00</div>
										</div>
										<div class="qs-price-hint">YES + NO combined</div>
									</div>
								</div>
							</div>
						<?php endfor; ?>
					</div>

					<div class="qs-actions">
						<button class="qs-btn qs-btn--primary" type="submit">
							<svg width="15" height="15" viewBox="0 0 24 24" fill="none">
								<path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" stroke="currentColor" stroke-width="2" />
								<polyline points="17 21 17 13 7 13 7 21" stroke="currentColor" stroke-width="2" />
								<polyline points="7 3 7 8 15 8" stroke="currentColor" stroke-width="2" />
							</svg>
							Save All Questions
						</button>
						<a class="qs-btn qs-btn--ghost" href="<?php echo site_url('admin/questions/view' . ($selected_category_id > 0 ? '?category_id=' . $selected_category_id : '')); ?>">
							View Existing
						</a>
					</div>
				</form>

			<?php else: ?>

				<div class="qs-empty">
					<div class="qs-empty-icon">
						<svg width="26" height="26" viewBox="0 0 24 24" fill="none">
							<path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" stroke="#5b5ef4" stroke-width="2" stroke-linecap="round" />
							<path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" stroke="#5b5ef4" stroke-width="2" stroke-linecap="round" />
						</svg>
					</div>
					<div class="qs-empty-title">No questions yet</div>
					<p class="qs-empty-desc">Use the Setup Wizard on the left to select a category and quantity. Your input form will appear here instantly.</p>
				</div>

			<?php endif; ?>

		</div><!-- /card-body -->
	</section>

</div><!-- /layout -->

<script>
	(function() {
		// Count pills
		document.querySelectorAll('.qs-pill').forEach(function(btn) {
			btn.addEventListener('click', function() {
				document.getElementById('question_count').value = this.dataset.count;
				document.querySelectorAll('.qs-pill').forEach(function(b) {
					b.classList.remove('active');
				});
				this.classList.add('active');
			});
		});

		// Price totals
		document.querySelectorAll('.qs-qcard').forEach(function(card) {
			var yes = card.querySelector('.js-yes');
			var no = card.querySelector('.js-no');
			var total = card.querySelector('.js-total');
			if (!yes || !no || !total) return;

			function recalc() {
				total.textContent = '₹' + ((parseFloat(yes.value) || 0) + (parseFloat(no.value) || 0)).toFixed(2);
			}
			yes.addEventListener('input', recalc);
			no.addEventListener('input', recalc);
			recalc();
		});

		// Alert auto-dismiss
		document.querySelectorAll('.q-alert').forEach(function(el) {
			setTimeout(function() {
				el.style.transition = 'opacity .3s';
				el.style.opacity = '0';
				setTimeout(function() {
					el.remove();
				}, 300);
			}, 5000);
		});

		// Submit guard
		document.querySelectorAll('form').forEach(function(form) {
			form.addEventListener('submit', function() {
				var btn = form.querySelector('button[type="submit"]');
				if (btn) {
					btn.disabled = true;
					btn.textContent = 'Saving…';
					setTimeout(function() {
						btn.disabled = false;
					}, 4000);
				}
			});
		});
	}());
</script>