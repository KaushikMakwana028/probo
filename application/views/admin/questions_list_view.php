<?php if ($this->session->flashdata('error')): ?>
	<div class="aq-flash aq-flash-err"><?php echo $this->session->flashdata('error'); ?></div>
<?php endif; ?>
<?php if ($this->session->flashdata('success')): ?>
	<div class="aq-flash aq-flash-ok"><?php echo $this->session->flashdata('success'); ?></div>
<?php endif; ?>

<?php
$selected_category = isset($selected_category) && is_object($selected_category) ? $selected_category : NULL;
$categories = isset($categories) && is_array($categories) ? $categories : array();
$total_questions = isset($total_questions) ? (int) $total_questions : 0;
$cat_q_count = 0;
$saved_count = 0;
$now_ts = time();

$questions_payload = array();

if ($selected_category && !empty($selected_category->questions)) {
	foreach ($selected_category->questions as $question_item) {
		$item_saved = in_array(strtolower((string) $question_item->answer_key), array('yes', 'no'), TRUE);
		if ($item_saved) {
			$saved_count++;
			continue;
		}

		$cat_q_count++;

		// AFTER:
		$real_users = isset($question_item->real_users) ? (int) $question_item->real_users : 0;
		if ($real_users <= 0 && isset($question_item->trade_totals['total_users'])) {
			$real_users = (int) $question_item->trade_totals['total_users'];
		}
		if ($real_users <= 0 && isset($question_item->user_counts)) {
			$real_users = (int) (($question_item->user_counts['yes_users'] ?? 0) + ($question_item->user_counts['no_users'] ?? 0));
		}
		// Add admin-injected users so the displayed count matches the user-facing total
		$admin_extra = isset($question_item->admin_users) ? (int) $question_item->admin_users : 0;
		if ($admin_extra <= 0) {
			$admin_extra = isset($question_item->total_users)
				? max(0, (int) $question_item->total_users - $real_users)
				: 0;
		}
		$display_users = $real_users + $admin_extra;
		$start_ts = (!empty($question_item->start_time) && $question_item->start_time !== '0000-00-00 00:00:00') ? strtotime($question_item->start_time) : FALSE;
		$end_ts = (!empty($question_item->end_time) && $question_item->end_time !== '0000-00-00 00:00:00') ? strtotime($question_item->end_time) : FALSE;
		$timing_class = 'live';
		$timing_label = 'Live';
		$sort_weight = 1;

		// AFTER:
		if ($start_ts && $now_ts < $start_ts) {
			$timing_class = 'upcoming';
			$timing_label = 'Upcoming';
			$sort_weight = 2;
		} elseif ($end_ts && $now_ts > $end_ts) {
			$timing_class = 'ended';
			$timing_label = 'Ended';
			$sort_weight = 3;
		} elseif (!$end_ts && strtolower((string) $question_item->status) !== 'open') {
			// Only use status-based label if there is NO end_time set.
			// If end_time is set and hasn't passed, trust the time window.
			$timing_class = 'ended';
			$timing_label = ucfirst((string) $question_item->status);
			$sort_weight = 3;
		}

		$questions_payload[] = array(
			'id' => (int) $question_item->id,
			'question' => (string) $question_item->question,
			'category_name' => html_escape($selected_category->name),
			'yes_price' => number_format((float) $question_item->yes_price, 2),
			'no_price' => number_format((float) $question_item->no_price, 2),
			'start_time' => !empty($question_item->start_time) ? html_escape($question_item->start_time) : 'Not set',
			'end_time' => !empty($question_item->end_time) ? html_escape($question_item->end_time) : 'Not set',
			'item_saved' => $item_saved,
			'real_users' => $display_users,
			'timing_class' => $timing_class,
			'timing_label' => $timing_label,
			'sort_weight' => $sort_weight,
			'detail_url' => site_url('admin/questions/detail/' . (int) $question_item->id)
		);
	}

	usort($questions_payload, function ($a, $b) {
		if ($a['sort_weight'] === $b['sort_weight']) {
			return $b['id'] <=> $a['id'];
		}
		return $a['sort_weight'] <=> $b['sort_weight'];
	});
}
?>

<style>
	.aq-page {
		display: flex;
		flex-direction: column;
		gap: 18px;
		color: #0f172a;
	}

	.aq-flash {
		padding: 12px 18px;
		border-radius: 12px;
		margin-bottom: 16px;
		font-size: 13px;
		font-weight: 600;
	}

	.aq-flash-err {
		background: #fef2f2;
		border: 1px solid #fecaca;
		color: #b91c1c;
	}

	.aq-flash-ok {
		background: #f0fdf4;
		border: 1px solid #bbf7d0;
		color: #15803d;
	}

	.aq-topbar,
	.aq-summary-card,
	.aq-panel,
	.aq-empty,
	.aq-row {
		background: #fff;
		border: 1px solid #e5e7eb;
		border-radius: 18px;
	}

	.aq-topbar {
		padding: 24px;
		display: flex;
		align-items: end;
		justify-content: space-between;
		gap: 16px;
		flex-wrap: wrap;
	}

	.aq-topbar h2 {
		margin: 0 0 6px;
		font-size: 24px;
		font-weight: 700;
	}

	.aq-topbar p {
		margin: 0;
		font-size: 13px;
		line-height: 1.6;
		color: #64748b;
	}

	.aq-filter {
		min-width: 320px;
	}

	.aq-filter label {
		display: block;
		margin-bottom: 6px;
		font-size: 11px;
		font-weight: 700;
		letter-spacing: .08em;
		text-transform: uppercase;
		color: #94a3b8;
	}

	.aq-filter select {
		width: 100%;
		padding: 11px 14px;
		border-radius: 12px;
		border: 1px solid #d1d5db;
		background: #f8fafc;
		font-size: 14px;
		color: #111827;
		outline: none;
	}

	.aq-summary {
		display: grid;
		grid-template-columns: repeat(3, minmax(0, 1fr));
		gap: 14px;
	}

	.aq-summary-card {
		padding: 18px 20px;
		position: relative;
		overflow: hidden;
	}

	.aq-summary-card::before {
		content: '';
		position: absolute;
		top: 0;
		left: 0;
		right: 0;
		height: 4px;
	}

	.aq-summary-card:nth-child(1)::before {
		background: #2563eb;
	}

	.aq-summary-card:nth-child(2)::before {
		background: #f59e0b;
	}

	.aq-summary-card:nth-child(3)::before {
		background: #10b981;
	}

	.aq-summary-label {
		font-size: 10px;
		font-weight: 700;
		letter-spacing: .1em;
		text-transform: uppercase;
		color: #94a3b8;
		margin-bottom: 10px;
	}

	.aq-summary-value {
		font-size: 30px;
		font-weight: 700;
		line-height: 1;
		margin-bottom: 6px;
	}

	.aq-summary-note {
		font-size: 12px;
		color: #64748b;
	}

	.aq-panel {
		padding: 22px;
	}

	.aq-panel-head {
		display: flex;
		align-items: end;
		justify-content: space-between;
		gap: 14px;
		flex-wrap: wrap;
		margin-bottom: 18px;
	}

	.aq-panel-head h3 {
		margin: 0 0 4px;
		font-size: 20px;
		font-weight: 700;
	}

	.aq-panel-head p {
		margin: 0;
		font-size: 13px;
		color: #64748b;
		line-height: 1.6;
	}

	.aq-count {
		display: inline-flex;
		align-items: center;
		padding: 8px 12px;
		border-radius: 999px;
		background: #eff6ff;
		border: 1px solid #bfdbfe;
		font-size: 12px;
		font-weight: 700;
		color: #1d4ed8;
	}

	.aq-list {
		display: flex;
		flex-direction: column;
		gap: 14px;
	}

	.aq-row {
		display: block;
		padding: 18px 20px;
		text-decoration: none;
		color: inherit;
		transition: border-color .15s ease, box-shadow .15s ease, transform .15s ease;
	}

	.aq-row:hover {
		border-color: #93c5fd;
		box-shadow: 0 16px 34px rgba(15, 23, 42, .07);
		transform: translateY(-1px);
	}

	.aq-row-top {
		display: flex;
		align-items: start;
		justify-content: space-between;
		gap: 14px;
		margin-bottom: 12px;
	}

	.aq-row-left {
		display: flex;
		gap: 14px;
		min-width: 0;
		flex: 1;
	}

	.aq-index {
		width: 38px;
		height: 38px;
		border-radius: 12px;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		font-size: 13px;
		font-weight: 700;
		flex-shrink: 0;
		background: #dbeafe;
		color: #1d4ed8;
	}

	.aq-title {
		font-size: 17px;
		font-weight: 700;
		line-height: 1.6;
		color: #111827;
	}

	.aq-sub {
		margin-top: 4px;
		font-size: 12px;
		color: #64748b;
	}

	.aq-badges {
		display: flex;
		flex-wrap: wrap;
		gap: 8px;
		justify-content: flex-end;
	}

	.aq-badge {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		padding: 7px 11px;
		border-radius: 999px;
		font-size: 11px;
		font-weight: 700;
		border: 1px solid #e2e8f0;
		background: #fff;
		color: #475569;
		text-transform: uppercase;
		letter-spacing: .04em;
	}

	.aq-badge.saved {
		background: #ecfdf5;
		border-color: #a7f3d0;
		color: #166534;
	}

	.aq-badge.pending {
		background: #fffbeb;
		border-color: #fde68a;
		color: #92400e;
	}

	.aq-badge.upcoming {
		background: #fff7ed;
		border-color: #fdba74;
		color: #c2410c;
	}

	.aq-badge.live {
		background: #ecfdf5;
		border-color: #86efac;
		color: #166534;
	}

	.aq-badge.ended {
		background: #f1f5f9;
		border-color: #cbd5e1;
		color: #475569;
	}

	.aq-row-meta {
		display: grid;
		grid-template-columns: repeat(5, minmax(0, 1fr));
		gap: 12px;
		margin-top: 12px;
	}

	.aq-meta-item {
		padding: 12px 14px;
		border-radius: 14px;
		background: #f8fafc;
		border: 1px solid #e2e8f0;
	}

	.aq-meta-item label {
		display: block;
		margin-bottom: 6px;
		font-size: 10px;
		font-weight: 700;
		letter-spacing: .1em;
		text-transform: uppercase;
		color: #94a3b8;
	}

	.aq-meta-item strong {
		display: block;
		font-size: 18px;
		font-weight: 700;
		line-height: 1.2;
		color: #111827;
	}

	.aq-meta-item small {
		display: block;
		margin-top: 4px;
		font-size: 12px;
		color: #64748b;
	}

	.aq-meta-item .yes {
		color: #047857;
	}

	.aq-meta-item .no {
		color: #c2410c;
	}

	.aq-open {
		margin-top: 14px;
		display: inline-flex;
		align-items: center;
		gap: 8px;
		font-size: 13px;
		font-weight: 700;
		color: #2563eb;
	}

	.aq-empty {
		padding: 56px 24px;
		text-align: center;
	}

	.aq-empty h4 {
		margin: 0 0 6px;
		font-size: 18px;
		font-weight: 700;
	}

	.aq-empty p {
		margin: 0;
		font-size: 13px;
		line-height: 1.7;
		color: #64748b;
	}

	@media (max-width: 1100px) {
		.aq-row-meta {
			grid-template-columns: repeat(2, minmax(0, 1fr));
		}
	}

	@media (max-width: 900px) {

		.aq-summary,
		.aq-row-meta {
			grid-template-columns: 1fr;
		}

		.aq-row-top {
			flex-direction: column;
		}

		.aq-badges {
			justify-content: flex-start;
		}
	}

	@media (max-width: 600px) {

		.aq-panel-controls {
			display: flex;
			flex-direction: row;
			gap: 10px;
			align-items: center;
		}

		.aq-search-wrap {
			flex: 1;
			min-width: 0;
		}

		.aq-search-input {
			width: 100%;
			padding: 10px 12px 10px 34px;
			font-size: 12px;
		}

		.aq-rows-select {
			width: auto;
			min-width: 90px;
			font-size: 12px;
			padding: 10px;
		}

		.aq-search-wrap i {
			left: 10px;
			font-size: 12px;
		}

		.aq-search-wrap {
			width: 100%;
		}

		.aq-rows-select {
			width: 100%;
		}

		.aq-topbar {
			padding: 14px;
			flex-direction: column;
			align-items: flex-start;
		}

		.aq-topbar h2 {
			font-size: 18px;
		}

		.aq-filter {
			width: 100%;
			min-width: 100%;
		}

		.aq-summary {
			grid-template-columns: 1fr;
		}

		.aq-row {
			padding: 14px;
		}

		.aq-row-left {
			flex-direction: column;
			gap: 8px;
		}

		.aq-index {
			width: 30px;
			height: 30px;
			font-size: 12px;
		}

		.aq-title {
			font-size: 14px;
		}

		.aq-sub {
			font-size: 11px;
		}

		.aq-badges {
			justify-content: flex-start;
			width: 100%;
		}

		.aq-row-meta {
			grid-template-columns: 1fr;
		}

		.aq-meta-item strong {
			font-size: 14px;
		}
	}

	.aq-panel-controls {
		display: flex;
		gap: 16px;
		align-items: center;
		flex-wrap: nowrap;
		margin-bottom: 20px;
		padding-bottom: 16px;
		border-bottom: 1px solid #e2e8f0;
	}

	.aq-search-wrap {
		position: relative;
		flex: 1;
		min-width: 200px;
	}

	.aq-search-wrap i {
		position: absolute;
		left: 14px;
		top: 50%;
		transform: translateY(-50%);
		color: #94a3b8;
		font-size: 14px;
	}

	.aq-search-input {
		width: 100%;
		padding: 10px 14px 10px 36px;
		border-radius: 12px;
		border: 1px solid #d1d5db;
		background: #fff;
		font-size: 13px;
		outline: none;
		transition: border-color .15s;
	}

	.aq-search-input:focus {
		border-color: #3b82f6;
		box-shadow: 0 0 0 3px rgba(59, 130, 246, .1);
	}

	.aq-rows-select {
		width: auto;
		min-width: 120px;
		flex-shrink: 0;
		padding: 10px 14px;
		border-radius: 12px;
		border: 1px solid #d1d5db;
		background: #fff;
		font-size: 13px;
		font-weight: 600;
		outline: none;
		cursor: pointer;
		color: #111827;
	}

	.aq-pagination {
		display: flex;
		align-items: center;
		justify-content: space-between;
		padding: 16px 20px;
		border-top: 1px solid #e2e8f0;
		margin-top: 10px;
	}

	.aq-page-info {
		font-size: 12px;
		font-weight: 500;
		color: #64748b;
	}

	.aq-page-btns {
		display: flex;
		align-items: center;
		gap: 6px;
	}

	.aq-page-btn {
		min-width: 34px;
		height: 34px;
		padding: 0 10px;
		border-radius: 8px;
		border: 1px solid #d1d5db;
		background: #fff;
		color: #111827;
		font-size: 13px;
		font-weight: 600;
		cursor: pointer;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		transition: all .15s;
	}

	.aq-page-btn:hover:not(:disabled) {
		background: #f1f5f9;
		border-color: #cbd5e1;
	}

	.aq-page-btn.is-active {
		background: #2563eb;
		color: #fff;
		border-color: #2563eb;
	}

	.aq-page-btn:disabled {
		opacity: .5;
		cursor: not-allowed;
	}

	.aq-page-ellipsis {
		color: #94a3b8;
		font-size: 14px;
		padding: 0 4px;
	}

	@media (max-width: 400px) {
		.aq-panel-controls {
			flex-direction: column;
			gap: 8px;
		}

		.aq-rows-select {
			width: 100%;
		}
	}
</style>

<div class="aq-page" id="aqApp" data-questions="<?php echo htmlspecialchars(json_encode($questions_payload), ENT_QUOTES, 'UTF-8'); ?>">
	<section class="aq-topbar">
		<div>
			<h2>View questions</h2>
			<p>Select a category to load questions. Each row shows real joined users, prices, time window, and market state before opening the details page.</p>
		</div>
		<form method="get" action="<?php echo site_url('admin/questions/view'); ?>" class="aq-filter">
			<label for="category_id">Filter by category</label>
			<select id="category_id" name="category_id" onchange="this.form.submit()">
				<option value="">Choose a category...</option>
				<?php foreach ($categories as $cat): ?>
					<option value="<?php echo (int) $cat->id; ?>" <?php echo ($selected_category && (int) $selected_category->id === (int) $cat->id) ? 'selected' : ''; ?>>
						<?php echo html_escape($cat->name); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</form>
	</section>

	<div class="aq-summary">
		<div class="aq-summary-card">
			<div class="aq-summary-label">Total questions</div>
			<div class="aq-summary-value"><?php echo number_format((int) $total_questions); ?></div>
			<div class="aq-summary-note">Across all categories</div>
		</div>
		<div class="aq-summary-card">
			<div class="aq-summary-label">Selected category</div>
			<div class="aq-summary-value"><?php echo $cat_q_count; ?></div>
			<div class="aq-summary-note"><?php echo $selected_category ? html_escape($selected_category->name) : 'No category selected'; ?></div>
		</div>
		<div class="aq-summary-card">
			<div class="aq-summary-label">Keys saved</div>
			<div class="aq-summary-value"><?php echo $saved_count; ?></div>
			<div class="aq-summary-note">Saved answer keys in this category</div>
		</div>
	</div>

	<?php if ($selected_category && !empty($selected_category->questions)): ?>
		<section class="aq-panel">
			<div class="aq-panel-head">
				<div>
					<h3><?php echo html_escape($selected_category->name); ?> questions</h3>
					<p>One question per row. Timing color shows whether the market is upcoming, live, or ended.</p>
				</div>
				<span class="aq-count"><?php echo $cat_q_count; ?> question<?php echo $cat_q_count !== 1 ? 's' : ''; ?></span>
			</div>

			<div class="aq-panel-controls">
				<div class="aq-search-wrap">
					<i class="fa-solid fa-magnifying-glass"></i>
					<input type="text" id="aqSearch" class="aq-search-input" placeholder="Search questions by Text">
				</div>
				<select id="aqRows" class="aq-rows-select">
					<option value="10" selected>10 rows</option>
					<option value="25">25 rows</option>
					<option value="50">50 rows</option>
					<option value="100">100 rows</option>
					<option value="all">All rows</option>
				</select>
			</div>

			<div class="aq-list" id="aqList"></div>

			<div class="aq-pagination" id="aqPagination" style="display: none;">
				<div class="aq-page-info" id="aqPageInfo"></div>
				<div class="aq-page-btns" id="aqPageBtns"></div>
			</div>
		</section>
	<?php elseif ($selected_category): ?>
		<div class="aq-empty">
			<h4>No pending questions</h4>
			<p>All questions in this category already have saved keys. You can review them on the Completed Questions page.</p>
		</div>
	<?php else: ?>
		<div class="aq-empty">
			<h4>Select a category first</h4>
			<p>Choose a category from the dropdown above to load its questions list.</p>
		</div>
	<?php endif; ?>
</div>

<script>
	document.addEventListener("DOMContentLoaded", function() {
		var app = document.getElementById('aqApp');
		if (!app) return;

		var questions = [];
		try {
			questions = JSON.parse(app.getAttribute('data-questions') || '[]');
		} catch (e) {
			console.error("Failed to parse questions", e);
		}

		var state = {
			searchTerm: '',
			rowsPerPage: 10,
			page: 1
		};

		var els = {
			search: document.getElementById('aqSearch'),
			rows: document.getElementById('aqRows'),
			list: document.getElementById('aqList'),
			pagination: document.getElementById('aqPagination'),
			pageInfo: document.getElementById('aqPageInfo'),
			pageBtns: document.getElementById('aqPageBtns')
		};

		if (!els.list) return;

		function getFiltered() {
			return questions.filter(function(q) {
				if (!state.searchTerm) return true;
				var term = state.searchTerm.toLowerCase();
				return q.question.toLowerCase().includes(term) || q.id.toString().includes(term);
			});
		}

		function esc(str) {
			return String(str)
				.replace(/&/g, '&amp;')
				.replace(/</g, '&lt;')
				.replace(/>/g, '&gt;')
				.replace(/"/g, '&quot;')
				.replace(/'/g, '&#039;');
		}

		function renderList(items, startIndex) {
			if (items.length === 0) {
				els.list.innerHTML = '<div class="aq-empty" style="padding: 40px; text-align: center;"><h4>No matches found</h4><p>Try a different search term.</p></div>';
				return;
			}

			els.list.innerHTML = items.map(function(q, i) {
				var index = startIndex + i + 1;
				var itemSavedClass = q.item_saved ? 'saved' : 'pending';
				var itemSavedLabel = q.item_saved ? 'Key saved' : 'Key pending';

				return `
				<a class="aq-row" href="${esc(q.detail_url)}">
					<div class="aq-row-top">
						<div class="aq-row-left">
							<span class="aq-index">${index}</span>
							<div>
								<div class="aq-title">${esc(q.question)}</div>
								<div class="aq-sub">Category: ${esc(q.category_name)}</div>
							</div>
						</div>
						<div class="aq-badges">
							<span class="aq-badge ${itemSavedClass}">
								${itemSavedLabel}
							</span>
							<span class="aq-badge ${esc(q.timing_class)}">
								${esc(q.timing_label)}
							</span>
						</div>
					</div>

					<div class="aq-row-meta">
						<div class="aq-meta-item">
							<label>joined users</label>
							<strong>${q.real_users}</strong>
<small>Total Joined</small>
						</div>
						<div class="aq-meta-item">
							<label>Yes price</label>
							<strong class="yes">Rs ${esc(q.yes_price)}</strong>
							<small>Current YES market price</small>
						</div>
						<div class="aq-meta-item">
							<label>No price</label>
							<strong class="no">Rs ${esc(q.no_price)}</strong>
							<small>Current NO market price</small>
						</div>
						<div class="aq-meta-item">
							<label>Start time</label>
							<strong style="font-size:15px;">${esc(q.start_time)}</strong>
							<small>Market opening time</small>
						</div>
						<div class="aq-meta-item">
							<label>Close time</label>
							<strong style="font-size:15px;">${esc(q.end_time)}</strong>
							<small>Market closing time</small>
						</div>
					</div>

					<div class="aq-open">
						<span>Open question details</span>
						<i class="fa-solid fa-arrow-right"></i>
					</div>
				</a>
			`;
			}).join('');
		}

		function renderPagination(totalItems) {
			if (state.rowsPerPage === 'all' || totalItems <= state.rowsPerPage) {
				els.pagination.style.display = 'none';
				return;
			}

			var totalPages = Math.ceil(totalItems / state.rowsPerPage);
			if (state.page > totalPages) state.page = totalPages;

			var startItem = (state.page - 1) * state.rowsPerPage + 1;
			var endItem = Math.min(state.page * state.rowsPerPage, totalItems);

			els.pagination.style.display = 'flex';
			els.pageInfo.textContent = 'Showing ' + startItem + ' to ' + endItem + ' of ' + totalItems;

			var p = state.page;
			var pages = [];
			if (totalPages <= 7) {
				for (var i = 1; i <= totalPages; i++) pages.push(i);
			} else {
				pages.push(1);
				if (p > 3) pages.push('...');
				var lo = Math.max(2, p - 1);
				var hi = Math.min(totalPages - 1, p + 1);
				for (var j = lo; j <= hi; j++) pages.push(j);
				if (p < totalPages - 2) pages.push('...');
				pages.push(totalPages);
			}

			var html = '<button class="aq-page-btn" data-page="' + (p - 1) + '" ' + (p === 1 ? 'disabled' : '') + '><i class="fa-solid fa-chevron-left"></i></button>';

			pages.forEach(function(pg) {
				if (pg === '...') {
					html += '<span class="aq-page-ellipsis">...</span>';
				} else {
					html += '<button class="aq-page-btn ' + (pg === p ? 'is-active' : '') + '" data-page="' + pg + '">' + pg + '</button>';
				}
			});

			html += '<button class="aq-page-btn" data-page="' + (p + 1) + '" ' + (p === totalPages ? 'disabled' : '') + '><i class="fa-solid fa-chevron-right"></i></button>';

			els.pageBtns.innerHTML = html;
		}

		function render() {
			var filtered = getFiltered();
			var itemsToRender = filtered;
			var startIndex = 0;

			if (state.rowsPerPage !== 'all') {
				var limit = parseInt(state.rowsPerPage, 10);
				var totalPages = Math.ceil(filtered.length / limit);
				if (state.page > totalPages) state.page = Math.max(1, totalPages);

				startIndex = (state.page - 1) * limit;
				itemsToRender = filtered.slice(startIndex, startIndex + limit);
			}

			renderList(itemsToRender, startIndex);
			renderPagination(filtered.length);
		}

		els.search.addEventListener('input', function(e) {
			state.searchTerm = e.target.value;
			state.page = 1;
			render();
		});

		els.rows.addEventListener('change', function(e) {
			state.rowsPerPage = e.target.value === 'all' ? 'all' : parseInt(e.target.value, 10);
			state.page = 1;
			render();
		});

		els.pageBtns.addEventListener('click', function(e) {
			var btn = e.target.closest('.aq-page-btn');
			if (!btn || btn.disabled) return;
			var nextPage = parseInt(btn.getAttribute('data-page'), 10);
			if (!isNaN(nextPage)) {
				state.page = nextPage;
				render();
				els.list.scrollIntoView({
					behavior: 'smooth',
					block: 'start'
				});
			}
		});

		render();
	});
</script>