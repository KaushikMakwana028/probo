<?php $selected_category = $selected_category ?? null; ?>

<?php if ($this->session->flashdata('error')): ?>
	<div class="vq-flash vq-flash-err"><?php echo $this->session->flashdata('error'); ?></div>
<?php endif; ?>
<?php if ($this->session->flashdata('success')): ?>
	<div class="vq-flash vq-flash-ok"><?php echo $this->session->flashdata('success'); ?></div>
<?php endif; ?>

<style>
	*,
	*::before,
	*::after {
		box-sizing: border-box;
		margin: 0;
		padding: 0;
	}

	.vq-flash {
		padding: 12px 18px;
		border-radius: 10px;
		margin-bottom: 16px;
		font-size: 13px;
		font-weight: 600;
	}

	.vq-flash-err {
		background: #fef2f2;
		border: 1px solid #fecaca;
		color: #b91c1c;
	}

	.vq-flash-ok {
		background: #f0fdf4;
		border: 1px solid #bbf7d0;
		color: #15803d;
	}

	.vq-page {
		display: flex;
		flex-direction: column;
		gap: 16px;
		font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
		color: #111827;
	}

	/* ── topbar ── */
	.vq-topbar {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 20px;
		flex-wrap: wrap;
		padding: 20px 24px;
		background: #fff;
		border: 1px solid #e5e7eb;
		border-radius: 14px;
	}

	.vq-topbar-left h2 {
		font-size: 18px;
		font-weight: 600;
		color: #111827;
		margin-bottom: 3px;
	}

	.vq-topbar-left p {
		font-size: 13px;
		color: #6b7280;
		line-height: 1.5;
	}

	.vq-cat-wrap {
		display: flex;
		flex-direction: column;
		gap: 5px;
		min-width: 260px;
	}

	.vq-cat-wrap label {
		font-size: 11px;
		font-weight: 600;
		text-transform: uppercase;
		letter-spacing: .07em;
		color: #9ca3af;
	}

	.vq-cat-wrap select {
		padding: 9px 36px 9px 12px;
		border-radius: 9px;
		border: 1px solid #e5e7eb;
		background: #f9fafb url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M6 8L1 3h10z'/%3E%3C/svg%3E") no-repeat right 12px center;
		color: #111827;
		font-size: 14px;
		font-family: inherit;
		cursor: pointer;
		appearance: none;
		transition: border-color .15s;
	}

	.vq-cat-wrap select:focus {
		outline: none;
		border-color: #6366f1;
		box-shadow: 0 0 0 3px rgba(99, 102, 241, .12);
	}

	/* ── summary ── */
	.vq-summary {
		display: grid;
		grid-template-columns: repeat(3, 1fr);
		gap: 12px;
	}

	.vq-sum-card {
		padding: 18px 20px;
		border-radius: 12px;
		background: #fff;
		border: 1px solid #e5e7eb;
		position: relative;
		overflow: hidden;
	}

	.vq-sum-card::after {
		content: '';
		position: absolute;
		top: 0;
		left: 0;
		right: 0;
		height: 3px;
		border-radius: 12px 12px 0 0;
	}

	.vq-sum-card:nth-child(1)::after {
		background: #6366f1;
	}

	.vq-sum-card:nth-child(2)::after {
		background: #0ea5e9;
	}

	.vq-sum-card:nth-child(3)::after {
		background: #10b981;
	}

	.vq-sum-label {
		font-size: 10px;
		font-weight: 700;
		text-transform: uppercase;
		letter-spacing: .1em;
		color: #9ca3af;
		margin-bottom: 8px;
	}

	.vq-sum-num {
		font-size: 30px;
		font-weight: 600;
		color: #111827;
		line-height: 1;
		margin-bottom: 6px;
	}

	.vq-sum-desc {
		font-size: 12px;
		color: #9ca3af;
	}

	/* ── shell ── */
	.vq-shell {
		background: #fff;
		border: 1px solid #e5e7eb;
		border-radius: 14px;
		overflow: hidden;
	}

	.vq-shell-head {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 12px;
		padding: 14px 20px;
		background: #f9fafb;
		border-bottom: 1px solid #e5e7eb;
	}

	.vq-shell-head h3 {
		font-size: 14px;
		font-weight: 600;
		color: #374151;
	}

	.vq-shell-head span {
		font-size: 12px;
		color: #9ca3af;
	}

	.vq-shell-body {
		padding: 20px;
	}

	/* ── card grid ── */
	.vq-card-grid {
		display: flex;
		flex-direction: column;
		gap: 14px;
	}

	.vq-card {
		border-radius: 12px;
		border: 1px solid #e5e7eb;
		background: #fff;
		overflow: hidden;
		transition: box-shadow .2s;
	}

	.vq-card:hover {
		box-shadow: 0 4px 16px rgba(0, 0, 0, .07);
	}

	.vq-card.key-saved {
		border-color: #a7f3d0;
	}

	.vq-card.key-unsaved {
		border-color: #fde68a;
	}

	/* card head */
	.vq-card-head {
		display: flex;
		align-items: center;
		gap: 12px;
		padding: 12px 16px;
		border-bottom: 1px solid #f3f4f6;
	}

	.vq-card.key-saved .vq-card-head {
		background: #f0fdf4;
		border-bottom-color: #d1fae5;
	}

	.vq-card.key-unsaved .vq-card-head {
		background: #fffbeb;
		border-bottom-color: #fef3c7;
	}

	.vq-card-idx {
		width: 32px;
		height: 32px;
		border-radius: 8px;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		font-size: 13px;
		font-weight: 700;
		flex-shrink: 0;
		background: #f3f4f6;
		color: #6b7280;
	}

	.vq-card.key-saved .vq-card-idx {
		background: #d1fae5;
		color: #059669;
	}

	.vq-card.key-unsaved .vq-card-idx {
		background: #fef3c7;
		color: #d97706;
	}

	.vq-card-head-mid {
		flex: 1;
		min-width: 0;
	}

	.vq-key-badge {
		display: inline-flex;
		align-items: center;
		gap: 5px;
		padding: 3px 10px;
		border-radius: 999px;
		font-size: 10px;
		font-weight: 700;
		text-transform: uppercase;
		letter-spacing: .07em;
		margin-bottom: 2px;
	}

	.vq-key-badge.saved {
		background: #d1fae5;
		color: #065f46;
		border: 1px solid #a7f3d0;
	}

	.vq-key-badge.unsaved {
		background: #fef3c7;
		color: #92400e;
		border: 1px solid #fde68a;
	}

	.vq-key-badge::before {
		content: '';
		width: 5px;
		height: 5px;
		border-radius: 50%;
		display: inline-block;
	}

	.vq-key-badge.saved::before {
		background: #10b981;
	}

	.vq-key-badge.unsaved::before {
		background: #f59e0b;
	}

	.vq-card-head-mid small {
		font-size: 11px;
		color: #9ca3af;
		display: block;
	}

	.vq-card-actions {
		display: flex;
		gap: 8px;
		flex-shrink: 0;
	}

	.vq-btn-edit,
	.vq-btn-del {
		padding: 6px 13px;
		border-radius: 7px;
		font-size: 12px;
		font-weight: 600;
		text-decoration: none;
		display: inline-flex;
		align-items: center;
		gap: 5px;
		transition: opacity .15s;
	}

	.vq-btn-edit:hover,
	.vq-btn-del:hover {
		opacity: .8;
	}

	.vq-btn-edit {
		background: #eff6ff;
		color: #1d4ed8;
		border: 1px solid #bfdbfe;
	}

	.vq-btn-del {
		background: #fff1f2;
		color: #be123c;
		border: 1px solid #fecdd3;
	}

	/* card body */
	.vq-card-body {
		padding: 16px;
	}

	.vq-question-text {
		padding: 13px 15px;
		border-radius: 10px;
		background: #f9fafb;
		border: 1px solid #e5e7eb;
		color: #111827;
		font-size: 14px;
		font-weight: 500;
		line-height: 1.65;
		margin-bottom: 14px;
	}

	/* meta grid */
	.vq-meta-grid {
		display: grid;
		grid-template-columns: repeat(3, 1fr);
		gap: 8px;
		margin-bottom: 14px;
	}

	.vq-meta-item {
		padding: 11px 13px;
		border-radius: 10px;
		background: #f9fafb;
		border: 1px solid #e5e7eb;
	}

	.vq-mi-label {
		display: block;
		font-size: 9px;
		font-weight: 700;
		text-transform: uppercase;
		letter-spacing: .1em;
		color: #9ca3af;
		margin-bottom: 4px;
	}

	.vq-mi-val {
		display: block;
		font-size: 14px;
		font-weight: 600;
		color: #111827;
	}

	.vq-mi-sub {
		display: block;
		font-size: 11px;
		color: #9ca3af;
		margin-top: 2px;
	}

	.yes-col {
		color: #059669;
	}

	.no-col {
		color: #dc2626;
	}

	/* live panel */
	.vq-live-panel {
		border: 1px solid #e0e7ff;
		border-radius: 12px;
		padding: 16px;
		background: #fafbff;
		margin-bottom: 14px;
	}

	.vq-live-panel-top {
		display: flex;
		align-items: flex-start;
		justify-content: space-between;
		gap: 14px;
		flex-wrap: wrap;
		margin-bottom: 14px;
	}

	.vq-live-eyebrow {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		padding: 4px 10px;
		border-radius: 999px;
		background: #ede9fe;
		color: #5b21b6;
		font-size: 10px;
		font-weight: 700;
		letter-spacing: .08em;
		text-transform: uppercase;
		margin-bottom: 6px;
	}

	.vq-live-eyebrow::before {
		content: '';
		width: 6px;
		height: 6px;
		border-radius: 50%;
		background: #7c3aed;
		animation: vq-pulse 1.8s infinite;
	}

	@keyframes vq-pulse {

		0%,
		100% {
			opacity: 1;
		}

		50% {
			opacity: .35;
		}
	}

	.vq-live-panel-top h4 {
		font-size: 15px;
		font-weight: 600;
		color: #111827;
		margin-bottom: 4px;
	}

	.vq-live-panel-top p {
		font-size: 12px;
		color: #6b7280;
		line-height: 1.55;
		max-width: 480px;
	}

	.vq-live-legend {
		display: flex;
		gap: 8px;
		flex-wrap: wrap;
		align-items: flex-start;
		padding-top: 4px;
	}

	.vq-live-dot {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		padding: 5px 11px;
		border-radius: 999px;
		font-size: 11px;
		font-weight: 600;
		border: 1px solid #e5e7eb;
		background: #fff;
		color: #374151;
	}

	.vq-live-dot::before {
		content: '';
		width: 8px;
		height: 8px;
		border-radius: 50%;
		flex-shrink: 0;
	}

	.vq-live-dot.yes::before {
		background: #6366f1;
	}

	.vq-live-dot.no::before {
		background: #f97316;
	}

	.vq-live-metrics {
		display: grid;
		grid-template-columns: repeat(4, minmax(0, 1fr));
		gap: 10px;
		margin-bottom: 12px;
	}

	.vq-live-metric {
		padding: 12px 14px;
		border-radius: 10px;
		background: #fff;
		border: 1px solid #e5e7eb;
	}

	.vq-live-metric span {
		display: block;
		font-size: 10px;
		font-weight: 700;
		text-transform: uppercase;
		letter-spacing: .08em;
		color: #9ca3af;
		margin-bottom: 6px;
	}

	.vq-live-metric strong {
		display: block;
		font-size: 18px;
		font-weight: 600;
		color: #111827;
		line-height: 1.1;
	}

	.vq-live-metric small {
		display: block;
		margin-top: 4px;
		font-size: 11px;
		color: #9ca3af;
	}

	.vq-live-metric.yes strong {
		color: #4f46e5;
	}

	.vq-live-metric.no strong {
		color: #ea580c;
	}

	.vq-live-metric.flow strong {
		color: #059669;
	}

	.vq-live-chart-wrap {
		border-radius: 10px;
		border: 1px solid #e5e7eb;
		background: #fff;
		padding: 10px;
	}

	.vq-live-chart {
		min-height: 140px;
	}

	.vq-live-foot {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 10px;
		flex-wrap: wrap;
		margin-top: 10px;
	}

	.vq-live-foot-note {
		font-size: 11px;
		color: #9ca3af;
	}

	.vq-live-spread {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		padding: 6px 12px;
		border-radius: 999px;
		background: #fff;
		border: 1px solid #e5e7eb;
		font-size: 12px;
		font-weight: 600;
		color: #374151;
	}

	.vq-live-spread strong {
		color: #4f46e5;
	}

	/* answer row */
	.vq-answer-row {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 10px;
	}

	.vq-answer-choice {
		display: flex;
		align-items: center;
		gap: 10px;
		padding: 12px 14px;
		border-radius: 10px;
		border: 1.5px solid #e5e7eb;
		background: #f9fafb;
		cursor: pointer;
		font-size: 13px;
		font-weight: 500;
		color: #374151;
		transition: all .15s;
	}

	.vq-answer-choice:hover {
		border-color: #a5b4fc;
		background: #eff6ff;
		color: #3730a3;
	}

	.vq-answer-choice.selected-yes {
		border-color: #10b981;
		background: #f0fdf4;
		color: #065f46;
	}

	.vq-answer-choice.selected-no {
		border-color: #6366f1;
		background: #eef2ff;
		color: #3730a3;
	}

	.vq-answer-choice input {
		width: 15px;
		height: 15px;
		margin: 0;
		flex-shrink: 0;
		accent-color: #4f46e5;
	}

	/* save bar */
	.vq-save-bar {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 14px;
		padding: 14px 20px;
		background: #f9fafb;
		border-top: 1px solid #e5e7eb;
	}

	.vq-save-bar p {
		font-size: 13px;
		color: #6b7280;
	}

	.vq-save-bar p strong {
		color: #374151;
	}

	.vq-save-btn {
		padding: 10px 22px;
		border: none;
		border-radius: 9px;
		background: #4f46e5;
		color: #fff;
		font-size: 13px;
		font-weight: 600;
		cursor: pointer;
		transition: background .15s, transform .1s;
		white-space: nowrap;
	}

	.vq-save-btn:hover {
		background: #4338ca;
		transform: translateY(-1px);
	}

	.vq-save-btn:active {
		transform: translateY(0);
	}

	/* empty */
	.vq-empty {
		padding: 52px 20px;
		text-align: center;
	}

	.vq-empty-icon {
		width: 48px;
		height: 48px;
		border-radius: 12px;
		background: #f3f4f6;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		margin-bottom: 12px;
	}

	.vq-empty-icon svg {
		width: 22px;
		height: 22px;
		color: #9ca3af;
	}

	.vq-empty h4 {
		font-size: 15px;
		font-weight: 600;
		color: #374151;
		margin-bottom: 5px;
	}

	.vq-empty p {
		font-size: 13px;
		color: #9ca3af;
	}

	/* ===== HEADER USER STATS ===== */
	.vq-users-pro {
		display: flex;
		gap: 8px;
		margin-top: 6px;
	}

	.vq-users-pro .chip {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		padding: 4px 10px;
		border-radius: 999px;
		font-size: 11px;
		font-weight: 600;
		border: 1px solid transparent;
	}

	/* total */
	.vq-users-pro .total {
		background: #f3f4f6;
		color: #374151;
	}

	/* YES */
	.vq-users-pro .yes {
		background: #eef2ff;
		color: #4f46e5;
	}

	/* NO */
	.vq-users-pro .no {
		background: #fff7ed;
		color: #ea580c;
	}

	/* ===== CARD STATS ===== */
	.vq-stats-pro {
		display: flex;
		gap: 10px;
		margin-top: 8px;
	}

	.vq-stats-pro .mini {
		display: inline-flex;
		align-items: center;
		gap: 5px;
		padding: 4px 8px;
		border-radius: 6px;
		font-size: 11px;
		font-weight: 600;
	}

	/* YES */
	.vq-stats-pro .yes {
		background: #eef2ff;
		color: #4f46e5;
	}

	/* NO */
	.vq-stats-pro .no {
		background: #fff7ed;
		color: #ea580c;
	}

	/* trades */
	.vq-stats-pro .trade {
		background: #f9fafb;
		color: #6b7280;
	}

	/* responsive */
	@media(max-width:900px) {
		.vq-topbar {
			flex-direction: column;
			align-items: flex-start;
		}

		.vq-cat-wrap {
			min-width: auto;
			width: 100%;
		}

		.vq-summary {
			grid-template-columns: repeat(3, 1fr);
		}

		.vq-meta-grid {
			grid-template-columns: repeat(2, 1fr);
		}

		.vq-live-metrics {
			grid-template-columns: repeat(2, minmax(0, 1fr));
		}
	}

	@media(max-width:600px) {
		.vq-summary {
			grid-template-columns: 1fr 1fr;
		}

		.vq-meta-grid {
			grid-template-columns: repeat(2, 1fr);
		}

		.vq-answer-row {
			grid-template-columns: 1fr;
		}

		.vq-card-actions {
			flex-wrap: wrap;
		}

		.vq-live-metrics {
			grid-template-columns: 1fr 1fr;
		}

		.vq-live-panel-top {
			flex-direction: column;
		}
	}
</style>

<div class="vq-page">

	<!-- topbar -->
	<section class="vq-topbar">
		<div class="vq-topbar-left">
			<h2>View questions</h2>
			<p>Select a category to review questions, monitor live prices, and assign answer keys.</p>
		</div>
		<form method="get" action="<?php echo site_url('admin/questions/view'); ?>">
			<div class="vq-cat-wrap">
				<label for="category_id">Filter by category</label>
				<select id="category_id" name="category_id" onchange="this.form.submit()">
					<option value="">Choose a category…</option>
					<?php foreach ($categories as $cat): ?>
						<option value="<?php echo (int)$cat->id; ?>" <?php echo ($selected_category && (int)$selected_category->id === (int)$cat->id) ? 'selected' : ''; ?>>
							<?php echo html_escape($cat->name); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>
		</form>
	</section>

	<!-- summary -->
	<?php
	$saved_count = 0;
	if ($selected_category && !empty($selected_category->questions)) {
		foreach ($selected_category->questions as $q) {
			if (in_array(strtolower((string)$q->answer_key), ['yes', 'no'], true)) $saved_count++;
		}
	}
	$cat_q_count = $selected_category ? count($selected_category->questions) : 0;
	?>
	<div class="vq-summary">
		<div class="vq-sum-card">
			<div class="vq-sum-label">Total questions</div>
			<div class="vq-sum-num"><?php echo number_format((int)$total_questions); ?></div>
			<div class="vq-sum-desc">Across all categories</div>
		</div>
		<div class="vq-sum-card">
			<div class="vq-sum-label">Selected category</div>
			<div class="vq-sum-num"><?php echo $cat_q_count; ?></div>
			<div class="vq-sum-desc"><?php echo $selected_category ? html_escape($selected_category->name) : 'No category selected'; ?></div>
		</div>
		<div class="vq-sum-card">
			<div class="vq-sum-label">Keys saved</div>
			<div class="vq-sum-num"><?php echo $saved_count; ?></div>
			<div class="vq-sum-desc">Answer keys in this category</div>
		</div>
	</div>

	<!-- shell -->
	<section class="vq-shell">
		<div class="vq-shell-head">
			<h3>
				<?php if ($selected_category): ?>
					<?php echo html_escape($selected_category->name); ?> &mdash; Questions
				<?php else: ?>
					Questions
				<?php endif; ?>
			</h3>
			<?php if ($selected_category && !empty($selected_category->questions)): ?>
				<span><?php echo $cat_q_count; ?> question<?php echo $cat_q_count !== 1 ? 's' : ''; ?> &bull; <?php echo $saved_count; ?> key<?php echo $saved_count !== 1 ? 's' : ''; ?> saved</span>
			<?php endif; ?>
		</div>

		<?php if ($selected_category && !empty($selected_category->questions)): ?>
			<form method="post" action="<?php echo site_url('admin/questions/save-answer-keys'); ?>">
				<input type="hidden" name="category_id" value="<?php echo (int)$selected_category->id; ?>">

				<div class="vq-shell-body">
					<div class="vq-card-grid">
						<?php foreach ($selected_category->questions as $idx => $q):
							$saved = in_array(strtolower((string)$q->answer_key), ['yes', 'no'], true);
							$yes_qty = isset($q->trade_totals['yes_quantity']) ? (int)$q->trade_totals['yes_quantity'] : 0;
							$no_qty  = isset($q->trade_totals['no_quantity'])  ? (int)$q->trade_totals['no_quantity']  : 0;
							$yes_users = isset($q->user_counts['yes_users']) ? (int)$q->user_counts['yes_users'] : 0;
							$no_users  = isset($q->user_counts['no_users'])  ? (int)$q->user_counts['no_users']  : 0;
							$total_users = $yes_users + $no_users;
							$total_qty = $yes_qty + $no_qty;
						?>
							<div class="vq-card <?php echo $saved ? 'key-saved' : 'key-unsaved'; ?>"
								data-question-card
								data-question-id="<?php echo (int)$q->id; ?>">

								<!-- head -->
								<div class="vq-card-head">
									<div class="vq-card-idx"><?php echo $idx + 1; ?></div>
									<div class="vq-card-head-mid">
										<span class="vq-key-badge <?php echo $saved ? 'saved' : 'unsaved'; ?>">
											<?php echo $saved ? 'Key saved' : 'Key unsaved'; ?>
										</span>
										<small class="vq-users-pro">
											<span class="chip total js-total-users">
												<i class="fa-solid fa-users"></i> <?php echo $total_users; ?>
											</span>

											<span class="chip yes js-yes-users">
												YES <?php echo $yes_users; ?>
											</span>

											<span class="chip no js-no-users">
												NO <?php echo $no_users; ?>
											</span>
										</small>
									</div>
									<div class="vq-card-actions">
										<a class="vq-btn-edit" href="<?php echo site_url('admin/questions/edit/' . (int)$q->id); ?>">
											<svg width="11" height="11" viewBox="0 0 16 16" fill="none">
												<path d="M11.5 2.5l2 2-9 9H2.5v-2l9-9z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
											</svg>
											Edit
										</a>
										<a class="vq-btn-del" href="<?php echo site_url('admin/questions/delete/' . (int)$q->id); ?>"
											onclick="return confirm('Delete this question?');">
											<svg width="11" height="11" viewBox="0 0 16 16" fill="none">
												<path d="M2 4h12M5 4V2h6v2M6 7v5M10 7v5M3 4l1 10h8l1-10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
											</svg>
											Delete
										</a>
									</div>
								</div>

								<!-- body -->
								<div class="vq-card-body">

									<div class="vq-question-text"><?php echo html_escape($q->question); ?></div>

									<!-- meta -->
									<div class="vq-meta-grid">
										<div class="vq-meta-item">
											<span class="vq-mi-label">Yes price</span>
											<span class="vq-mi-val yes-col js-yes-price">Rs <?php echo number_format((float)$q->yes_price, 2); ?></span>
										</div>
										<div class="vq-meta-item">
											<span class="vq-mi-label">No price</span>
											<span class="vq-mi-val no-col js-no-price">Rs <?php echo number_format((float)$q->no_price, 2); ?></span>
										</div>
										<div class="vq-meta-item">
											<span class="vq-mi-label">Market total</span>
											<span class="vq-mi-val js-market-total">Rs <?php echo number_format((float)$q->yes_price + (float)$q->no_price, 2); ?></span>
										</div>
										<div class="vq-meta-item">
											<span class="vq-mi-label">Status</span>
											<span class="vq-mi-val js-status-plain"><?php echo ucfirst(html_escape($q->status)); ?></span>
										</div>
										<div class="vq-meta-item">
											<span class="vq-mi-label">Trade qty</span>
											<span class="vq-mi-val js-trade-qty">Y <?php echo $yes_qty; ?> &nbsp;|&nbsp; N <?php echo $no_qty; ?></span>
										</div>
										<div class="vq-meta-item">
											<span class="vq-mi-label">Time window</span>
											<span class="vq-mi-val" style="font-size:12px;"><?php echo !empty($q->start_time) ? html_escape($q->start_time) : 'No start'; ?></span>
											<span class="vq-mi-sub"><?php echo !empty($q->end_time) ? html_escape($q->end_time) : 'No end'; ?></span>
										</div>
									</div>

									<!-- live panel -->
									<div class="vq-live-panel">
										<div class="vq-live-panel-top">
											<div>
												<div class="vq-live-eyebrow">Live market</div>
												<h4>Live demand snapshot</h4>
												<p>Track YES vs NO pricing, activity, and momentum while answer keys are being reviewed.</p>
											</div>
											<div class="vq-live-legend">
												<span class="vq-live-dot yes">YES</span>
												<span class="vq-live-dot no">NO</span>
											</div>
										</div>

										<div class="vq-live-metrics">
											<div class="vq-live-metric yes">
												<span>Yes price</span>
												<strong class="js-yes-price">Rs <?php echo number_format((float)$q->yes_price, 2); ?></strong>
												<small class="vq-stats-pro">
													<span class="mini yes js-yes-user-mini">
														<i class="fa-solid fa-user"></i> <?php echo $yes_users; ?>
													</span>
													<span class="mini trade">
														<i class="fa-solid fa-arrow-right-arrow-left"></i> <?php echo $yes_qty; ?>
													</span>
												</small>
											</div>
											<div class="vq-live-metric no">
												<span>No price</span>
												<strong class="js-no-price">Rs <?php echo number_format((float)$q->no_price, 2); ?></strong>
												<small class="vq-stats-pro">
													<span class="mini no js-no-user-mini">
														<i class="fa-solid fa-user"></i> <?php echo $no_users; ?>
													</span>
													<span class="mini trade">
														<i class="fa-solid fa-arrow-right-arrow-left"></i> <?php echo $no_qty; ?>
													</span>
												</small>
											</div>
											<div class="vq-live-metric total">
												<span>Market total</span>
												<strong class="js-market-total">Rs <?php echo number_format((float)$q->yes_price + (float)$q->no_price, 2); ?></strong>
												<small class="js-status"><?php echo ucfirst(html_escape($q->status)); ?> market</small>
											</div>
											<div class="vq-live-metric flow">
												<span>Trade flow</span>
												<strong class="js-flow-total"><?php echo $total_qty; ?></strong>
												<small class="js-trade-qty">Y <?php echo $yes_qty; ?> | N <?php echo $no_qty; ?></small>
											</div>
										</div>

										<div class="vq-live-chart-wrap">
											<div class="vq-live-chart" data-history='<?php echo json_encode($this->Category_model->get_question_price_history((int)$q->id, 12)); ?>'></div>
										</div>
										<div class="vq-live-foot">
											<span class="vq-live-foot-note">Auto-refreshes every 12 seconds.</span>
											<span class="vq-live-spread">Spread &nbsp;<strong class="js-spread">Rs <?php echo number_format(abs((float)$q->yes_price - (float)$q->no_price), 2); ?></strong></span>
										</div>
									</div>

									<!-- answer key -->
									<div class="vq-answer-row">
										<label class="vq-answer-choice <?php echo strtolower((string)$q->answer_key) === 'yes' ? 'selected-yes' : ''; ?>"
											for="ak_yes_<?php echo (int)$q->id; ?>">
											<input type="radio"
												id="ak_yes_<?php echo (int)$q->id; ?>"
												name="answer_keys[<?php echo (int)$q->id; ?>]"
												value="yes"
												<?php echo strtolower((string)$q->answer_key) === 'yes' ? 'checked' : ''; ?>>
											<span>&#10003; &nbsp;Yes is correct</span>
										</label>
										<label class="vq-answer-choice <?php echo strtolower((string)$q->answer_key) === 'no' ? 'selected-no' : ''; ?>"
											for="ak_no_<?php echo (int)$q->id; ?>">
											<input type="radio"
												id="ak_no_<?php echo (int)$q->id; ?>"
												name="answer_keys[<?php echo (int)$q->id; ?>]"
												value="no"
												<?php echo strtolower((string)$q->answer_key) === 'no' ? 'checked' : ''; ?>>
											<span>&#10007; &nbsp;No is correct</span>
										</label>
									</div>

								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>

				<div class="vq-save-bar">
					<p><strong><?php echo $cat_q_count; ?></strong> questions &bull; <strong><?php echo $saved_count; ?></strong> keys already saved</p>
					<button type="submit" class="vq-save-btn">Save answer keys</button>
				</div>
			</form>

		<?php elseif ($selected_category): ?>
			<div class="vq-shell-body">
				<div class="vq-empty">
					<div class="vq-empty-icon">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
							<circle cx="12" cy="12" r="9" />
							<path d="M12 8v4M12 16h.01" />
						</svg>
					</div>
					<h4>No questions yet</h4>
					<p>This category has no questions. Add one to get started.</p>
				</div>
			</div>
		<?php else: ?>
			<div class="vq-shell-body">
				<div class="vq-empty">
					<div class="vq-empty-icon">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
							<path d="M4 6h16M4 10h16M4 14h10M4 18h6" />
						</svg>
					</div>
					<h4>Select a category first</h4>
					<p>Choose a category from the dropdown above to load its questions.</p>
				</div>
			</div>
		<?php endif; ?>
	</section>

</div>

<?php if ($selected_category): ?>
	<script>
		(function() {
			'use strict';
			var categoryId = <?php echo (int)$selected_category->id; ?>;

			function drawChart(container, points) {
				if (!container) return;
				points = Array.isArray(points) ? points : [];
				var yesVals = points.map(function(p) {
					return parseFloat(p.yes_price || 0);
				});
				var noVals = points.map(function(p) {
					return parseFloat(p.no_price || 0);
				});
				if (!yesVals.length) {
					container.innerHTML = '<div style="display:flex;align-items:center;justify-content:center;min-height:140px;color:#9ca3af;font-size:13px;">No history recorded yet.</div>';
					return;
				}
				var all = [].concat(yesVals, noVals, [0.01]);
				var min = Math.min.apply(null, all),
					max = Math.max.apply(null, all);
				var W = 520,
					H = 140,
					px = 18,
					py = 14;
				var uid = 'vq' + (container.closest('[data-question-id]') || {
					dataset: {
						questionId: 'x'
					}
				}).dataset.questionId;
				var yG = uid + 'Y',
					nG = uid + 'N',
					bgG = uid + 'B';

				function norm(vals) {
					return vals.map(function(v, i) {
						var x = vals.length === 1 ? W / 2 : px + (i / (vals.length - 1)) * (W - px * 2);
						var r = max === min ? 0.5 : (v - min) / (max - min);
						var y = H - py - r * (H - py * 2);
						return x.toFixed(1) + ',' + y.toFixed(1);
					}).join(' ');
				}
				container.innerHTML =
					'<svg viewBox="0 0 ' + W + ' ' + H + '" width="100%" height="' + H + '" preserveAspectRatio="none">' +
					'<defs>' +
					'<linearGradient id="' + yG + '" x1="0%" y1="0%" x2="100%" y2="0%"><stop offset="0%" stop-color="#6366f1"/><stop offset="100%" stop-color="#818cf8"/></linearGradient>' +
					'<linearGradient id="' + nG + '" x1="0%" y1="0%" x2="100%" y2="0%"><stop offset="0%" stop-color="#f97316"/><stop offset="100%" stop-color="#fb923c"/></linearGradient>' +
					'</defs>' +
					'<rect x="0" y="0" width="' + W + '" height="' + H + '" rx="10" fill="#fff"/>' +
					'<line x1="' + px + '" y1="' + (H - py) + '" x2="' + (W - px) + '" y2="' + (H - py) + '" stroke="#e5e7eb" stroke-width="1"/>' +
					'<line x1="' + px + '" y1="' + (py + 4) + '" x2="' + (W - px) + '" y2="' + (py + 4) + '" stroke="#f3f4f6" stroke-width="1" stroke-dasharray="4 4"/>' +
					'<line x1="' + px + '" y1="' + Math.round(H / 2) + '" x2="' + (W - px) + '" y2="' + Math.round(H / 2) + '" stroke="#f3f4f6" stroke-width="1" stroke-dasharray="4 4"/>' +
					'<polyline fill="none" stroke="url(#' + yG + ')" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" points="' + norm(yesVals) + '"/>' +
					'<polyline fill="none" stroke="url(#' + nG + ')" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" points="' + norm(noVals) + '"/>' +
					'</svg>';
			}

			document.querySelectorAll('[data-question-card]').forEach(function(card) {
				var chart = card.querySelector('.vq-live-chart');
				var pts = [];
				try {
					pts = JSON.parse(chart.dataset.history || '[]');
				} catch (e) {}
				drawChart(chart, pts);
			});

			document.querySelectorAll('.vq-answer-choice input[type="radio"]').forEach(function(radio) {
				radio.addEventListener('change', function() {
					var wrap = this.closest('.vq-answer-row');
					if (!wrap) return;
					wrap.querySelectorAll('.vq-answer-choice').forEach(function(lbl) {
						lbl.classList.remove('selected-yes', 'selected-no');
					});
					var lbl = this.closest('.vq-answer-choice');
					if (lbl) lbl.classList.add(this.value === 'yes' ? 'selected-yes' : 'selected-no');
				});
			});

			async function refreshLive() {
				try {
					var r = await fetch('<?php echo site_url('admin/questions/live-stats/'); ?>' + categoryId, {
						headers: {
							'X-Requested-With': 'XMLHttpRequest'
						}
					});
					var data = await r.json();
					if (!data || data.status !== 'ok' || !Array.isArray(data.questions)) return;
					data.questions.forEach(function(q) {
						var card = document.querySelector('[data-question-id="' + q.id + '"]');
						if (!card) return;
						var yq = q.trade_totals ? q.trade_totals.yes_quantity : 0;
						var nq = q.trade_totals ? q.trade_totals.no_quantity : 0;
						var yu = q.trade_totals ? q.trade_totals.yes_users : 0;
						var nu = q.trade_totals ? q.trade_totals.no_users : 0;
						var tu = q.trade_totals ? q.trade_totals.total_users : 0;
						card.querySelectorAll('.js-yes-price').forEach(function(n) {
							n.textContent = 'Rs ' + Number(q.yes_price || 0).toFixed(2);
						});
						card.querySelectorAll('.js-no-price').forEach(function(n) {
							n.textContent = 'Rs ' + Number(q.no_price || 0).toFixed(2);
						});
						card.querySelectorAll('.js-market-total').forEach(function(n) {
							n.textContent = 'Rs ' + Number(q.market_total || 0).toFixed(2);
						});
						card.querySelectorAll('.js-status-plain').forEach(function(n) {
							n.textContent = String(q.status || '').charAt(0).toUpperCase() + String(q.status || '').slice(1);
						});
						card.querySelectorAll('.js-status').forEach(function(n) {
							n.textContent = String(q.status || '').charAt(0).toUpperCase() + String(q.status || '').slice(1) + ' market';
						});
						card.querySelectorAll('.js-trade-qty').forEach(function(n) {
							n.textContent = 'Y ' + yq + ' | N ' + nq;
						});
						card.querySelectorAll('.js-flow-total').forEach(function(n) {
							n.textContent = Number(yq) + Number(nq);
						});
						card.querySelectorAll('.js-total-users').forEach(function(n) {
							n.innerHTML = '<i class="fa-solid fa-users"></i> ' + tu;
						});
						card.querySelectorAll('.js-yes-users').forEach(function(n) {
							n.textContent = 'YES ' + yu;
						});
						card.querySelectorAll('.js-no-users').forEach(function(n) {
							n.textContent = 'NO ' + nu;
						});
						card.querySelectorAll('.js-yes-user-mini').forEach(function(n) {
							n.innerHTML = '<i class="fa-solid fa-user"></i> ' + yu;
						});
						card.querySelectorAll('.js-no-user-mini').forEach(function(n) {
							n.innerHTML = '<i class="fa-solid fa-user"></i> ' + nu;
						});
						card.querySelectorAll('.js-spread').forEach(function(n) {
							n.textContent = 'Rs ' + Math.abs(Number(q.yes_price || 0) - Number(q.no_price || 0)).toFixed(2);
						});
						drawChart(card.querySelector('.vq-live-chart'), q.price_history || []);
					});
				} catch (e) {}
			}
			setInterval(refreshLive, 12000);
		}());
	</script>
<?php endif; ?>
