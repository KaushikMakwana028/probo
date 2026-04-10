<?php $selected_category = $selected_category ?? null; ?>

<?php if ($this->session->flashdata('error')): ?>
	<div class="question-list-flash question-list-flash-error"><?php echo $this->session->flashdata('error'); ?></div>
<?php endif; ?>

<?php if ($this->session->flashdata('success')): ?>
	<div class="question-list-flash question-list-flash-success"><?php echo $this->session->flashdata('success'); ?></div>
<?php endif; ?>

<section class="question-topbar">
	<div class="question-topbar-copy">
		<h2>View Questions</h2>
		<p>Select category from the top, review each question in clean cards, and save answer keys without confusion.</p>
	</div>

	<form method="get" action="<?php echo site_url('admin/questions/view'); ?>" class="question-topbar-form">
		<label for="category_id">Category</label>
		<select id="category_id" name="category_id" onchange="this.form.submit()">
			<option value="">Choose a category...</option>
			<?php foreach ($categories as $category_item): ?>
				<option value="<?php echo (int) $category_item->id; ?>" <?php echo ($selected_category && (int) $selected_category->id === (int) $category_item->id) ? 'selected' : ''; ?>>
					<?php echo html_escape($category_item->name); ?>
				</option>
			<?php endforeach; ?>
		</select>
	</form>
</section>

<section class="question-summary-grid">
	<div class="question-summary-card">
		<span>Total Questions</span>
		<strong><?php echo number_format((int) $total_questions); ?></strong>
		<p>All questions across the project.</p>
	</div>
	<div class="question-summary-card">
		<span>Selected Category</span>
		<strong><?php echo $selected_category ? number_format(count($selected_category->questions)) : '0'; ?></strong>
		<p><?php echo $selected_category ? html_escape($selected_category->name) : 'No category selected'; ?></p>
	</div>
	<div class="question-summary-card">
		<span>Answer Key Status</span>
		<strong>
			<?php
			$saved_count = 0;
			if ($selected_category && !empty($selected_category->questions)) {
				foreach ($selected_category->questions as $question_item) {
					if (in_array(strtolower((string) $question_item->answer_key), array('yes', 'no'), TRUE)) {
						$saved_count++;
					}
				}
			}
			echo (int) $saved_count;
			?>
		</strong>
		<p>Saved keys in this selected category.</p>
	</div>
</section>

<section class="question-content-shell">
	<?php if ($selected_category && !empty($selected_category->questions)): ?>
		<form method="post" action="<?php echo site_url('admin/questions/save-answer-keys'); ?>">
			<input type="hidden" name="category_id" value="<?php echo (int) $selected_category->id; ?>">

			<div class="question-card-grid">
				<?php foreach ($selected_category->questions as $index => $question_item): ?>
					<?php $is_key_saved = in_array(strtolower((string) $question_item->answer_key), array('yes', 'no'), TRUE); ?>
					<div class="question-manage-card <?php echo $is_key_saved ? 'key-saved' : 'key-unsaved'; ?>" data-question-card data-question-id="<?php echo (int) $question_item->id; ?>">
						<div class="question-manage-head">
							<div class="question-manage-index"><?php echo $index + 1; ?></div>
							<div class="question-manage-head-copy">
								<span class="question-save-state <?php echo $is_key_saved ? 'saved' : 'unsaved'; ?>">
									<?php echo $is_key_saved ? 'Key Saved' : 'Key Unsaved'; ?>
								</span>
								<small><?php echo $is_key_saved ? 'Saved answer key is active.' : 'Choose and save the key for this question.'; ?></small>
							</div>
							<div class="question-manage-actions">
								<a class="question-manage-edit" href="<?php echo site_url('admin/questions/edit/' . (int) $question_item->id); ?>">Edit</a>
								<a class="question-manage-delete" href="<?php echo site_url('admin/questions/delete/' . (int) $question_item->id); ?>" onclick="return confirm('Are you sure you want to delete this question?');">Delete</a>
							</div>
						</div>

						<div class="question-manage-text"><?php echo html_escape($question_item->question); ?></div>

						<div class="question-meta-grid">
							<div class="question-meta-item">
								<span>Yes Price</span>
								<strong class="js-yes-price">Rs <?php echo number_format((float) $question_item->yes_price, 2); ?></strong>
							</div>
							<div class="question-meta-item">
								<span>No Price</span>
								<strong class="js-no-price">Rs <?php echo number_format((float) $question_item->no_price, 2); ?></strong>
							</div>
							<div class="question-meta-item">
								<span>Market Total</span>
								<strong class="js-market-total">Rs <?php echo number_format((float) $question_item->yes_price + (float) $question_item->no_price, 2); ?></strong>
							</div>
							<div class="question-meta-item">
								<span>Status</span>
								<strong class="js-status"><?php echo ucfirst(html_escape($question_item->status)); ?></strong>
							</div>
							<div class="question-meta-item">
								<span>Trade Qty</span>
								<strong class="js-trade-qty">Y <?php echo (int) $question_item->trade_totals['yes_quantity']; ?> | N <?php echo (int) $question_item->trade_totals['no_quantity']; ?></strong>
							</div>
							<div class="question-meta-item">
								<span>Time</span>
								<strong><?php echo !empty($question_item->start_time) ? html_escape($question_item->start_time) : 'No start'; ?></strong>
								<small><?php echo !empty($question_item->end_time) ? html_escape($question_item->end_time) : 'No end'; ?></small>
							</div>
						</div>

						<div class="question-live-panel">
							<div class="question-live-head">
								<div class="question-live-copy">
									<strong>Live Demand Snapshot</strong>
									<small>Auto-refreshing price trend from current YES and NO demand.</small>
								</div>
								<div class="question-live-legend">
									<span class="question-live-dot yes">YES</span>
									<span class="question-live-dot no">NO</span>
								</div>
							</div>
							<div class="question-live-chart-shell">
								<div class="question-live-chart" data-history='<?php echo json_encode($this->Category_model->get_question_price_history((int) $question_item->id, 12)); ?>'></div>
							</div>
						</div>

						<div class="question-answer-key-wrap">
							<label class="answer-key-choice <?php echo strtolower((string) $question_item->answer_key) === 'yes' ? 'selected-yes' : ''; ?>" for="answer_key_yes_<?php echo (int) $question_item->id; ?>">
								<input type="radio" id="answer_key_yes_<?php echo (int) $question_item->id; ?>" name="answer_keys[<?php echo (int) $question_item->id; ?>]" value="yes" <?php echo strtolower((string) $question_item->answer_key) === 'yes' ? 'checked' : ''; ?>>
								<span>Yes is correct</span>
							</label>

							<label class="answer-key-choice <?php echo strtolower((string) $question_item->answer_key) === 'no' ? 'selected-no' : ''; ?>" for="answer_key_no_<?php echo (int) $question_item->id; ?>">
								<input type="radio" id="answer_key_no_<?php echo (int) $question_item->id; ?>" name="answer_keys[<?php echo (int) $question_item->id; ?>]" value="no" <?php echo strtolower((string) $question_item->answer_key) === 'no' ? 'checked' : ''; ?>>
								<span>No is correct</span>
							</label>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

			<div class="question-list-actions">
				<button type="submit" class="question-list-primary-btn">Save Selected Answer Keys</button>
			</div>
		</form>
	<?php elseif ($selected_category): ?>
		<div class="question-list-empty">
			<h4>No Questions Yet</h4>
			<p>This category does not have any questions right now.</p>
		</div>
	<?php else: ?>
		<div class="question-list-empty">
			<h4>Select Category First</h4>
			<p>Choose a category from the top filter to load its questions.</p>
		</div>
	<?php endif; ?>
</section>

<style>
	.question-list-flash{padding:14px 18px;border-radius:14px;margin-bottom:20px;font-weight:600}
	.question-list-flash-error{background:#fef2f2;border:1px solid #fecaca;color:#991b1b}
	.question-list-flash-success{background:#f0fdf4;border:1px solid #bbf7d0;color:#166534}
	.question-topbar{display:grid;grid-template-columns:minmax(0,1fr) 340px;gap:20px;align-items:end;margin-bottom:22px;background:#fff;border:1px solid #e5e7eb;border-radius:24px;padding:24px;box-shadow:0 10px 24px rgba(15,23,42,.04)}
	.question-topbar-copy h2{margin:0 0 8px;color:#111827;font-size:34px}
	.question-topbar-copy p{margin:0;color:#64748b;max-width:760px}
	.question-topbar-form label{display:block;margin-bottom:8px;font-size:13px;font-weight:700;color:#475569}
	.question-topbar-form select{width:100%;padding:14px 16px;border-radius:16px;border:1px solid #d1d5db;font-size:15px}
	.question-summary-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:18px;margin-bottom:22px}
	.question-summary-card{background:#fff;border:1px solid #e5e7eb;border-radius:22px;padding:22px;box-shadow:0 10px 24px rgba(15,23,42,.04)}
	.question-summary-card span{display:block;margin-bottom:10px;font-size:12px;text-transform:uppercase;letter-spacing:.08em;color:#64748b;font-weight:700}
	.question-summary-card strong{display:block;font-size:34px;color:#111827;margin-bottom:8px}
	.question-summary-card p{margin:0;color:#64748b}
	.question-content-shell{background:#fff;border:1px solid #e5e7eb;border-radius:26px;padding:24px;box-shadow:0 10px 24px rgba(15,23,42,.04)}
	.question-card-grid{display:grid;gap:20px}
	.question-manage-card{border:1px solid #e2e8f0;border-radius:24px;padding:22px;background:#fff;transition:all .2s ease}
	.question-manage-card.key-unsaved{background:linear-gradient(135deg,#fff7ed 0%,#fffbeb 100%);border-color:#fdba74}
	.question-manage-card.key-saved{background:linear-gradient(135deg,#ecfeff 0%,#f0fdf4 100%);border-color:#86efac}
	.question-manage-head{display:grid;grid-template-columns:60px minmax(0,1fr) auto;gap:16px;align-items:start;margin-bottom:18px}
	.question-manage-index{width:44px;height:44px;border-radius:14px;background:#eef2ff;color:#4338ca;display:inline-flex;align-items:center;justify-content:center;font-weight:800;font-size:18px}
	.question-manage-head-copy small{display:block;margin-top:4px;color:#64748b}
	.question-save-state{display:inline-flex;align-items:center;justify-content:center;padding:8px 12px;border-radius:999px;font-size:12px;font-weight:800;text-transform:uppercase;letter-spacing:.08em}
	.question-save-state.saved{background:#dcfce7;color:#166534}
	.question-save-state.unsaved{background:#ffedd5;color:#c2410c}
	.question-manage-actions{display:flex;gap:10px;flex-wrap:wrap}
	.question-manage-edit,.question-manage-delete{display:inline-flex;align-items:center;justify-content:center;padding:10px 14px;border-radius:12px;font-size:13px;font-weight:700;text-decoration:none}
	.question-manage-edit{background:#eef2ff;color:#4338ca}
	.question-manage-delete{background:#fef2f2;color:#b91c1c}
	.question-manage-text{padding:18px;border-radius:18px;background:#fff;border:1px solid #e5e7eb;color:#0f172a;font-size:16px;font-weight:600;line-height:1.7;margin-bottom:18px}
	.question-meta-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px;margin-bottom:18px}
	.question-meta-item{padding:16px;border-radius:18px;background:#fff;border:1px solid #e2e8f0}
	.question-meta-item span{display:block;margin-bottom:8px;font-size:12px;text-transform:uppercase;letter-spacing:.08em;color:#64748b;font-weight:700}
	.question-meta-item strong{display:block;color:#111827;font-size:16px}
	.question-meta-item small{display:block;margin-top:4px;color:#64748b}
	.question-answer-key-wrap{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px}
	.question-live-panel{display:grid;gap:16px;padding:18px;border-radius:20px;background:linear-gradient(180deg,#ffffff 0%,#f8fbff 100%);border:1px solid #dbeafe;margin-bottom:18px}
	.question-live-head{display:flex;align-items:flex-start;justify-content:space-between;gap:16px}
	.question-live-copy strong{display:block;color:#0f172a;margin-bottom:4px;font-size:18px}
	.question-live-copy small{display:block;color:#64748b;line-height:1.6;max-width:560px}
	.question-live-legend{display:flex;align-items:center;gap:10px;flex-wrap:wrap}
	.question-live-dot{display:inline-flex;align-items:center;gap:8px;padding:8px 12px;border-radius:999px;background:#fff;border:1px solid #e2e8f0;font-size:12px;font-weight:800;letter-spacing:.08em;color:#475569}
	.question-live-dot::before{content:"";width:10px;height:10px;border-radius:999px;display:inline-block}
	.question-live-dot.yes::before{background:#2563eb}
	.question-live-dot.no::before{background:#f97316}
	.question-live-chart-shell{padding:8px;border-radius:18px;background:#fff;border:1px solid #e2e8f0}
	.question-live-chart{min-height:140px}
	.answer-key-choice{display:flex;align-items:center;gap:10px;padding:16px 18px;border-radius:18px;border:2px solid #e2e8f0;background:#fff;font-weight:700;cursor:pointer;transition:all .2s ease}
	.answer-key-choice.selected-yes{border-color:#22c55e;background:#ecfdf5}
	.answer-key-choice.selected-no{border-color:#3b82f6;background:#eff6ff}
	.answer-key-choice input{width:18px;height:18px;margin:0;accent-color:#4338ca}
	.question-list-actions{margin-top:24px;display:flex;justify-content:flex-end}
	.question-list-primary-btn{padding:14px 20px;border:0;border-radius:16px;background:linear-gradient(135deg,#2563eb 0%,#4338ca 100%);color:#fff;font-size:14px;font-weight:700;cursor:pointer}
	.question-list-empty{padding:60px 20px;text-align:center;border:1px dashed #cbd5e1;border-radius:22px;background:#f8fafc}
	.question-list-empty h4{margin:0 0 8px;color:#111827;font-size:24px}
	.question-list-empty p{margin:0;color:#64748b}
	@media (max-width:1100px){.question-topbar,.question-summary-grid,.question-meta-grid{grid-template-columns:1fr}.question-manage-head{grid-template-columns:1fr}.question-manage-actions{justify-content:flex-start}}
	@media (max-width:720px){.question-content-shell,.question-topbar,.question-summary-card{padding:20px}.question-answer-key-wrap,.question-live-panel{grid-template-columns:1fr}.question-live-head{flex-direction:column;align-items:flex-start}}
</style>

<?php if ($selected_category): ?>
<script>
	document.addEventListener('DOMContentLoaded', function() {
		const categoryId = <?php echo (int) $selected_category->id; ?>;
		const cards = document.querySelectorAll('[data-question-card]');

		function drawMiniChart(container, points) {
			if (!container) {
				return;
			}

			points = Array.isArray(points) ? points : [];
			const values = points.map(function(point) {
				return parseFloat(point.yes_price || 0);
			});
			const valuesNo = points.map(function(point) {
				return parseFloat(point.no_price || 0);
			});

			if (!values.length) {
				container.innerHTML = '';
				return;
			}

			const allValues = values.concat(valuesNo, [1]);
			const min = Math.min.apply(null, allValues);
			const max = Math.max.apply(null, allValues);
			const width = 420;
			const height = 140;
			const paddingX = 16;
			const paddingY = 16;
			const normalize = function(source) {
				return source.map(function(value, index) {
					const x = source.length === 1 ? width / 2 : paddingX + (index / (source.length - 1)) * (width - (paddingX * 2));
					const ratio = max === min ? 0.5 : (value - min) / (max - min);
					const y = height - paddingY - (ratio * (height - (paddingY * 2)));
					return x.toFixed(2) + ',' + y.toFixed(2);
				}).join(' ');
			};

			container.innerHTML = '<svg viewBox="0 0 ' + width + ' ' + height + '" width="100%" height="' + height + '" preserveAspectRatio="none"><rect x="0" y="0" width="' + width + '" height="' + height + '" rx="16" fill="#f8fafc"></rect><line x1="' + paddingX + '" y1="' + (height - paddingY) + '" x2="' + (width - paddingX) + '" y2="' + (height - paddingY) + '" stroke="#cbd5e1" stroke-width="1"></line><line x1="' + paddingX + '" y1="' + (paddingY + 8) + '" x2="' + (width - paddingX) + '" y2="' + (paddingY + 8) + '" stroke="#e2e8f0" stroke-width="1" stroke-dasharray="4 4"></line><line x1="' + paddingX + '" y1="' + Math.round(height / 2) + '" x2="' + (width - paddingX) + '" y2="' + Math.round(height / 2) + '" stroke="#e2e8f0" stroke-width="1" stroke-dasharray="4 4"></line><polyline fill="none" stroke="#2563eb" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" points="' + normalize(values) + '"></polyline><polyline fill="none" stroke="#f97316" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" points="' + normalize(valuesNo) + '"></polyline></svg>';
			container.dataset.history = JSON.stringify(points);
		}

		cards.forEach(function(card) {
			const chart = card.querySelector('.question-live-chart');
			let points = [];
			try {
				points = JSON.parse(chart.dataset.history || '[]');
			} catch (error) {
				points = [];
			}
			drawMiniChart(chart, points);
		});

		async function refreshLiveStats() {
			try {
				const response = await fetch('<?php echo site_url('admin/questions/live-stats/'); ?>' + categoryId, {headers: {'X-Requested-With': 'XMLHttpRequest'}});
				const payload = await response.json();

				if (!payload || payload.status !== 'ok' || !Array.isArray(payload.questions)) {
					return;
				}

				payload.questions.forEach(function(question) {
					const card = document.querySelector('[data-question-id="' + question.id + '"]');
					if (!card) {
						return;
					}

					const yesQty = question.trade_totals && question.trade_totals.yes_quantity ? question.trade_totals.yes_quantity : 0;
					const noQty = question.trade_totals && question.trade_totals.no_quantity ? question.trade_totals.no_quantity : 0;
					card.querySelector('.js-yes-price').textContent = 'Rs ' + Number(question.yes_price || 0).toFixed(2);
					card.querySelector('.js-no-price').textContent = 'Rs ' + Number(question.no_price || 0).toFixed(2);
					card.querySelector('.js-market-total').textContent = 'Rs ' + Number(question.market_total || 0).toFixed(2);
					card.querySelector('.js-status').textContent = String(question.status || '').charAt(0).toUpperCase() + String(question.status || '').slice(1);
					card.querySelector('.js-trade-qty').textContent = 'Y ' + yesQty + ' | N ' + noQty;
					drawMiniChart(card.querySelector('.question-live-chart'), question.price_history || []);
				});
			} catch (error) {
			}
		}

		setInterval(refreshLiveStats, 12000);
	});
</script>
<?php endif; ?>
