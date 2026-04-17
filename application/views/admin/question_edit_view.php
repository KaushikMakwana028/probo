<?php if ($this->session->flashdata('error')): ?>
	<div class="question-edit-flash question-edit-flash-error"><?php echo $this->session->flashdata('error'); ?></div>
<?php endif; ?>

<section class="question-edit-layout">
	<aside class="question-edit-summary">
		<div class="question-edit-summary-card">
			<p class="question-edit-eyebrow">Question Editor</p>
			<h3>Edit Question</h3>
			<p class="question-edit-note">Update text, category, pricing, timing, and market status. After edit, answer key goes back to unsaved state.</p>

			<div class="question-edit-pills">
				<span><?php echo html_escape($question->category_name); ?></span>
				<span><?php echo strtoupper(html_escape($question->status !== '' ? $question->status : 'draft')); ?></span>
			</div>

			<div class="question-edit-price-box">
				<div>
					<label>Yes Price</label>
					<strong>Rs <?php echo number_format((float) $question->yes_price, 2); ?></strong>
				</div>
				<div>
					<label>No Price</label>
					<strong>Rs <?php echo number_format((float) $question->no_price, 2); ?></strong>
				</div>
			</div>

			<div class="question-edit-links">
				<a href="<?php echo site_url('admin/questions/view?category_id=' . (int) $question->category_id); ?>">Back to Questions</a>
			</div>
		</div>
	</aside>

	<section class="question-edit-panel">
		<div class="question-edit-head">
			<div>
				<p class="question-edit-eyebrow">Admin Questions</p>
				<h2>Update Question Details</h2>
				<p>Keep the question content and trade values accurate for users.</p>
			</div>
			<a class="question-delete-btn" href="<?php echo site_url('admin/questions/delete/' . (int) $question->id); ?>" onclick="return confirm('Delete this question permanently?');">Delete Question</a>
		</div>

		<form method="post" action="<?php echo site_url('admin/questions/update/' . (int) $question->id); ?>">
			<div class="question-edit-grid">
				<div class="form-group">
					<label for="category_id">Category</label>
					<select id="category_id" name="category_id" required>
						<?php foreach ($categories as $category_item): ?>
							<option value="<?php echo (int) $category_item->id; ?>" <?php echo ((int) set_value('category_id', $question->category_id) === (int) $category_item->id) ? 'selected' : ''; ?>>
								<?php echo html_escape($category_item->name); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>

				<div class="form-group">
					<label for="status">Market Status</label>
					<select id="status" name="status" required>
						<option value="draft" <?php echo set_value('status', $question->status) === 'draft' ? 'selected' : ''; ?>>Draft</option>
						<option value="open" <?php echo set_value('status', $question->status) === 'open' ? 'selected' : ''; ?>>Open</option>
						<option value="closed" <?php echo set_value('status', $question->status) === 'closed' ? 'selected' : ''; ?>>Closed</option>
					</select>
				</div>

				<div class="form-group form-group-full">
					<label for="question">Question Text</label>
					<textarea id="question" name="question" rows="5" required><?php echo set_value('question', $question->question); ?></textarea>
				</div>

				<div class="form-group">
					<label for="yes_price">Yes Price</label>
					<input type="number" id="yes_price" name="yes_price" min="0.01" step="0.01" value="<?php echo set_value('yes_price', number_format((float) $question->yes_price, 2, '.', '')); ?>" required>
				</div>

				<div class="form-group">
					<label for="no_price">No Price</label>
					<input type="number" id="no_price" name="no_price" min="0.01" step="0.01" value="<?php echo set_value('no_price', number_format((float) $question->no_price, 2, '.', '')); ?>" required>
				</div>

				<div class="form-group">
					<label for="multiplier">Multiplier</label>
					<input type="number" id="multiplier" name="multiplier" min="0.01" step="0.01" value="<?php echo set_value('multiplier', number_format((float) ($question->multiplier ?? 1.25), 2, '.', '')); ?>" required>
				</div>

				<div class="form-group">
					<label for="start_time">Start Time</label>
					<input type="datetime-local" id="start_time" name="start_time" value="<?php echo set_value('start_time', !empty($question->start_time) ? date('Y-m-d\TH:i', strtotime($question->start_time)) : ''); ?>">
				</div>

				<div class="form-group">
					<label for="end_time">End Time</label>
					<input type="datetime-local" id="end_time" name="end_time" value="<?php echo set_value('end_time', !empty($question->end_time) ? date('Y-m-d\TH:i', strtotime($question->end_time)) : ''); ?>">
				</div>
			</div>

			<div class="question-edit-actions">
				<a class="question-secondary-btn" href="<?php echo site_url('admin/questions/view?category_id=' . (int) $question->category_id); ?>">Cancel</a>
				<button type="submit" class="question-primary-btn">Save Question</button>
			</div>
		</form>
	</section>
</section>

<style>
	.question-edit-flash {
		padding: 14px 18px;
		border-radius: 14px;
		margin-bottom: 20px;
		font-weight: 600;
	}

	.question-edit-flash-error {
		background: #fef2f2;
		border: 1px solid #fecaca;
		color: #991b1b;
	}

	.question-edit-layout {
		display: grid;
		grid-template-columns: 320px minmax(0, 1fr);
		gap: 24px;
		align-items: start;
	}

	.question-edit-summary-card,
	.question-edit-panel {
		background: #fff;
		border: 1px solid #e5e7eb;
		border-radius: 26px;
		box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
	}

	.question-edit-summary-card {
		padding: 24px;
		position: sticky;
		top: 20px;
	}

	.question-edit-eyebrow {
		margin: 0 0 10px;
		font-size: 12px;
		letter-spacing: 0.14em;
		text-transform: uppercase;
		color: #2563eb;
		font-weight: 800;
	}

	.question-edit-summary-card h3,
	.question-edit-head h2 {
		margin: 0 0 8px;
		color: #111827;
	}

	.question-edit-note,
	.question-edit-head p {
		margin: 0;
		color: #6b7280;
	}

	.question-edit-pills {
		display: flex;
		gap: 10px;
		flex-wrap: wrap;
		margin: 20px 0;
	}

	.question-edit-pills span {
		padding: 8px 12px;
		border-radius: 999px;
		background: #eef2ff;
		color: #4338ca;
		font-weight: 700;
		font-size: 12px;
	}

	.question-edit-price-box {
		display: grid;
		gap: 12px;
	}

	.question-edit-price-box div {
		padding: 14px 16px;
		border-radius: 18px;
		background: #f8fafc;
		border: 1px solid #e2e8f0;
	}

	.question-edit-price-box label {
		display: block;
		margin-bottom: 6px;
		font-size: 12px;
		text-transform: uppercase;
		letter-spacing: 0.12em;
		color: #64748b;
		font-weight: 700;
	}

	.question-edit-price-box strong {
		color: #0f172a;
		font-size: 18px;
	}

	.question-edit-links {
		margin-top: 18px;
	}

	.question-edit-links a,
	.question-secondary-btn {
		color: #334155;
		text-decoration: none;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		padding: 12px 18px;
		border-radius: 14px;
		border: 1px solid #dbe2ea;
		background: #f8fafc;
		font-weight: 700;
	}

	.question-edit-panel {
		padding: 28px;
	}

	.question-edit-head {
		display: flex;
		justify-content: space-between;
		gap: 18px;
		align-items: flex-start;
		margin-bottom: 24px;
	}

	.question-delete-btn {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		padding: 12px 18px;
		border-radius: 14px;
		background: #fef2f2;
		color: #b91c1c;
		border: 1px solid #fecaca;
		text-decoration: none;
		font-weight: 700;
	}

	.question-edit-grid {
		display: grid;
		grid-template-columns: repeat(2, minmax(0, 1fr));
		gap: 18px;
	}

	.form-group-full {
		grid-column: 1 / -1;
	}

	.form-group label {
		display: block;
		margin-bottom: 8px;
		font-size: 14px;
		font-weight: 700;
		color: #374151;
	}

	.form-group input,
	.form-group select,
	.form-group textarea {
		width: 100%;
		padding: 14px 16px;
		border: 1px solid #d1d5db;
		border-radius: 16px;
		font-size: 14px;
		font-family: inherit;
		background: #fff;
	}

	.form-group textarea {
		resize: vertical;
	}

	.form-group input:focus,
	.form-group select:focus,
	.form-group textarea:focus {
		outline: none;
		border-color: #2563eb;
		box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.10);
	}

	.question-edit-actions {
		display: flex;
		justify-content: flex-end;
		gap: 12px;
		margin-top: 24px;
		flex-wrap: wrap;
	}

	.question-primary-btn {
		padding: 12px 18px;
		border-radius: 14px;
		border: 0;
		background: linear-gradient(135deg, #2563eb 0%, #4338ca 100%);
		color: #fff;
		font-size: 14px;
		font-weight: 700;
		cursor: pointer;
	}

	@media (max-width: 980px) {
		.question-edit-layout {
			grid-template-columns: 1fr;
		}

		.question-edit-summary-card {
			position: static;
		}
	}

	@media (max-width: 720px) {

		.question-edit-panel,
		.question-edit-summary-card {
			padding: 20px;
		}

		.question-edit-head {
			flex-direction: column;
			align-items: stretch;
		}

		.question-edit-grid {
			grid-template-columns: 1fr;
		}
	}
</style>
