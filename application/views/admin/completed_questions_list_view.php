<?php if ($this->session->flashdata('error')): ?>
	<div class="aq-flash aq-flash-err"><?php echo $this->session->flashdata('error'); ?></div>
<?php endif; ?>
<?php if ($this->session->flashdata('success')): ?>
	<div class="aq-flash aq-flash-ok"><?php echo $this->session->flashdata('success'); ?></div>
<?php endif; ?>

<?php
$list_mode = isset($list_mode) && $list_mode === 'completed' ? 'completed' : 'active';
$listing_stats = isset($listing_stats) && is_array($listing_stats) ? $listing_stats : array();
$page_total = isset($listing_stats['page_total']) ? (int) $listing_stats['page_total'] : 0;
$category_total = isset($listing_stats['category_total']) ? (int) $listing_stats['category_total'] : 0;
$saved_total = isset($listing_stats['saved_total']) ? (int) $listing_stats['saved_total'] : 0;
$initial_category_id = isset($initial_category_id) ? (int) $initial_category_id : 0;
$questions_payload = isset($questions_payload) && is_array($questions_payload) ? $questions_payload : array();
$page_heading = $list_mode === 'completed' ? 'Completed questions' : 'View questions';
$page_copy = $list_mode === 'completed'
	? 'Saved answer key questions move here automatically. Use filters, search, and pagination without reloading the page.'
	: 'Only pending-answer questions stay here. Once you save a key, that question moves to the Completed Questions page.';
$panel_heading = $list_mode === 'completed' ? 'Completed questions list' : 'Pending questions list';
$panel_copy = $list_mode === 'completed'
	? 'Review resolved markets, filter by category or answer key, and open details when needed.'
	: 'Review active or unsolved markets, filter by category or timing, and open details when needed.';
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

	.aq-topbar h2,
	.aq-panel-head h3,
	.aq-empty h4 {
		margin: 0 0 6px;
		font-weight: 700;
	}

	.aq-topbar h2 {
		font-size: 24px;
	}

	.aq-topbar p,
	.aq-panel-head p,
	.aq-empty p {
		margin: 0;
		font-size: 13px;
		line-height: 1.6;
		color: #64748b;
	}

	.aq-topbar-meta {
		display: flex;
		align-items: center;
		gap: 10px;
		flex-wrap: wrap;
	}

	.aq-chip {
		display: inline-flex;
		align-items: center;
		padding: 8px 12px;
		border-radius: 999px;
		font-size: 12px;
		font-weight: 700;
		border: 1px solid #dbeafe;
		background: #eff6ff;
		color: #1d4ed8;
	}

	.aq-chip.completed {
		background: #ecfdf5;
		border-color: #a7f3d0;
		color: #166534;
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
		font-size: 20px;
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

	.aq-panel-controls {
		display: grid;
		grid-template-columns: minmax(220px, 1.2fr) repeat(3, minmax(0, .7fr));
		gap: 12px;
		align-items: center;
		margin-bottom: 20px;
		padding-bottom: 16px;
		border-bottom: 1px solid #e2e8f0;
	}

	.aq-search-wrap,
	.aq-control {
		position: relative;
	}

	.aq-search-wrap i {
		position: absolute;
		left: 14px;
		top: 50%;
		transform: translateY(-50%);
		color: #94a3b8;
		font-size: 14px;
	}

	.aq-search-input,
	.aq-select {
		width: 100%;
		padding: 10px 14px;
		border-radius: 12px;
		border: 1px solid #d1d5db;
		background: #fff;
		font-size: 13px;
		outline: none;
		color: #111827;
	}

	.aq-search-input {
		padding-left: 36px;
	}

	.aq-search-input:focus,
	.aq-select:focus {
		border-color: #3b82f6;
		box-shadow: 0 0 0 3px rgba(59, 130, 246, .1);
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
		flex-wrap: wrap;
		justify-content: flex-end;
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

	@media (max-width: 1100px) {

		.aq-panel-controls,
		.aq-row-meta {
			grid-template-columns: repeat(2, minmax(0, 1fr));
		}
	}

	@media (max-width: 900px) {

		.aq-summary,
		.aq-row-meta,
		.aq-panel-controls {
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
		.aq-topbar {
			padding: 14px;
			flex-direction: column;
			align-items: flex-start;
		}

		.aq-topbar h2 {
			font-size: 18px;
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

		.aq-meta-item strong {
			font-size: 14px;
		}

		.aq-pagination {
			flex-direction: column;
			align-items: flex-start;
			gap: 12px;
		}
	}
</style>

<div
	class="aq-page"
	id="aqApp"
	data-mode="<?php echo html_escape($list_mode); ?>"
	data-initial-category-id="<?php echo $initial_category_id; ?>"
	data-questions="<?php echo htmlspecialchars(json_encode($questions_payload), ENT_QUOTES, 'UTF-8'); ?>">

	<section class="aq-topbar">
		<div>
			<h2><?php echo html_escape($page_heading); ?></h2>
			<p><?php echo html_escape($page_copy); ?></p>
		</div>
		<div class="aq-topbar-meta">
			<span class="aq-chip <?php echo $list_mode === 'completed' ? 'completed' : ''; ?>">
				<?php echo $list_mode === 'completed' ? 'Completed Flow' : 'Pending Flow'; ?>
			</span>
			<span class="aq-chip"><?php echo $page_total; ?> question<?php echo $page_total !== 1 ? 's' : ''; ?></span>
		</div>
	</section>

	<div class="aq-summary">
		<div class="aq-summary-card">
			<div class="aq-summary-label">Total questions</div>
			<div class="aq-summary-value"><?php echo number_format((int) $total_questions); ?></div>
			<div class="aq-summary-note">Across all categories</div>
		</div>
		<div class="aq-summary-card">
			<div class="aq-summary-label"><?php echo $list_mode === 'completed' ? 'Completed page' : 'Pending page'; ?></div>
			<div class="aq-summary-value"><?php echo number_format($page_total); ?></div>
			<div class="aq-summary-note"><?php echo $list_mode === 'completed' ? 'Questions with saved keys' : 'Questions waiting for keys'; ?></div>
		</div>
		<div class="aq-summary-card">
			<div class="aq-summary-label">Categories covered</div>
			<div class="aq-summary-value"><?php echo number_format($category_total); ?></div>
			<div class="aq-summary-note"><?php echo $saved_total > 0 ? number_format($saved_total) . ' saved key question(s) in this list' : 'Filter by category anytime'; ?></div>
		</div>
	</div>

	<section class="aq-panel">
		<div class="aq-panel-head">
			<div>
				<h3><?php echo html_escape($panel_heading); ?></h3>
				<p><?php echo html_escape($panel_copy); ?></p>
			</div>
			<span class="aq-count" id="aqCount">0 questions</span>
		</div>

		<div class="aq-panel-controls">
			<div class="aq-search-wrap">
				<i class="fa-solid fa-magnifying-glass"></i>
				<input type="text" id="aqSearch" class="aq-search-input" placeholder="Search by question, category, or ID">
			</div>

			<div class="aq-control">
				<select id="aqCategory" class="aq-select">
					<option value="0">All categories</option>
					<?php foreach ($categories as $cat): ?>
						<option value="<?php echo (int) $cat->id; ?>"><?php echo html_escape($cat->name); ?></option>
					<?php endforeach; ?>
				</select>
			</div>

			<div class="aq-control">
				<select id="aqTiming" class="aq-select">
					<option value="all">All timings</option>
					<option value="live">Live</option>
					<option value="upcoming">Upcoming</option>
					<option value="ended">Ended</option>
				</select>
			</div>

			<div class="aq-control">
				<select id="aqRows" class="aq-select">
					<option value="10" selected>10 rows</option>
					<option value="25">25 rows</option>
					<option value="50">50 rows</option>
					<option value="100">100 rows</option>
					<option value="all">All rows</option>
				</select>
			</div>
		</div>

		<?php if ($list_mode === 'completed'): ?>
			<div class="aq-panel-controls" style="grid-template-columns:minmax(220px,1fr); margin-top:-8px;">
				<div class="aq-control" style="max-width:220px;">
					<select id="aqAnswerKey" class="aq-select">
						<option value="all">All answer keys</option>
						<option value="yes">Yes key</option>
						<option value="no">No key</option>
					</select>
				</div>
			</div>
		<?php endif; ?>

		<div class="aq-list" id="aqList"></div>

		<div class="aq-pagination" id="aqPagination" style="display:none;">
			<div class="aq-page-info" id="aqPageInfo"></div>
			<div class="aq-page-btns" id="aqPageBtns"></div>
		</div>
	</section>
</div>

<script>
	document.addEventListener('DOMContentLoaded', function() {
		var app = document.getElementById('aqApp');
		if (!app) return;

		var questions = [];
		try {
			questions = JSON.parse(app.getAttribute('data-questions') || '[]');
		} catch (error) {
			console.error('Failed to parse questions payload.', error);
		}

		var state = {
			mode: app.getAttribute('data-mode') === 'completed' ? 'completed' : 'active',
			categoryId: parseInt(app.getAttribute('data-initial-category-id') || '0', 10) || 0,
			searchTerm: '',
			timing: 'all',
			answerKey: 'all',
			rowsPerPage: 10,
			page: 1
		};

		var els = {
			search: document.getElementById('aqSearch'),
			category: document.getElementById('aqCategory'),
			timing: document.getElementById('aqTiming'),
			answerKey: document.getElementById('aqAnswerKey'),
			rows: document.getElementById('aqRows'),
			list: document.getElementById('aqList'),
			count: document.getElementById('aqCount'),
			pagination: document.getElementById('aqPagination'),
			pageInfo: document.getElementById('aqPageInfo'),
			pageBtns: document.getElementById('aqPageBtns')
		};

		function escapeHtml(value) {
			return String(value)
				.replace(/&/g, '&amp;')
				.replace(/</g, '&lt;')
				.replace(/>/g, '&gt;')
				.replace(/"/g, '&quot;')
				.replace(/'/g, '&#039;');
		}

		function syncFiltersToUrl() {
			if (!window.history || !window.history.replaceState) return;
			var url = new URL(window.location.href);

			if (state.categoryId > 0) {
				url.searchParams.set('category_id', String(state.categoryId));
			} else {
				url.searchParams.delete('category_id');
			}

			window.history.replaceState({}, '', url.toString());
		}

		function getFilteredQuestions() {
			return questions.filter(function(question) {
				if (state.categoryId > 0 && Number(question.category_id) !== state.categoryId) {
					return false;
				}

				if (state.timing !== 'all' && question.timing_class !== state.timing) {
					return false;
				}

				if (state.mode === 'completed' && state.answerKey !== 'all' && question.answer_key !== state.answerKey) {
					return false;
				}

				if (!state.searchTerm) {
					return true;
				}

				var term = state.searchTerm.toLowerCase();
				return question.question.toLowerCase().indexOf(term) !== -1 ||
					question.category_name.toLowerCase().indexOf(term) !== -1 ||
					String(question.id).indexOf(term) !== -1;
			});
		}

		function renderCount(totalItems) {
			var label = totalItems === 1 ? 'question' : 'questions';
			els.count.textContent = totalItems + ' ' + label;
		}

		function renderEmpty() {
			var title = state.mode === 'completed' ? 'No completed questions found' : 'No pending questions found';
			var text = state.mode === 'completed' ?
				'Try another category, answer key, or search term.' :
				'Try another category, timing, or search term.';

			els.list.innerHTML = '<div class="aq-empty"><h4>' + title + '</h4><p>' + text + '</p></div>';
		}

		function renderList(items, startIndex) {
			if (!items.length) {
				renderEmpty();
				return;
			}

			els.list.innerHTML = items.map(function(question, index) {
				var itemSavedClass = question.item_saved ? 'saved' : 'pending';
				var itemSavedLabel = question.item_saved ? 'Key saved' : 'Key pending';
				var answerBadge = '';
				var displayIndex = startIndex + index + 1;

				if (state.mode === 'completed' && question.answer_key) {
					answerBadge = '<span class="aq-badge saved">' + escapeHtml(question.answer_key.toUpperCase()) + ' key</span>';
				}

				return '' +
					'<a class="aq-row" href="' + escapeHtml(question.detail_url) + '">' +
					'<div class="aq-row-top">' +
					'<div class="aq-row-left">' +
					'<span class="aq-index">' + displayIndex + '</span>' +
					'<div>' +
					'<div class="aq-title">' + escapeHtml(question.question) + '</div>' +
					'<div class="aq-sub">Category: ' + escapeHtml(question.category_name) + '</div>' +
					'</div>' +
					'</div>' +
					'<div class="aq-badges">' +
					'<span class="aq-badge ' + itemSavedClass + '">' + itemSavedLabel + '</span>' +
					answerBadge +
					'<span class="aq-badge ' + escapeHtml(question.timing_class) + '">' + escapeHtml(question.timing_label) + '</span>' +
					'</div>' +
					'</div>' +
					'<div class="aq-row-meta">' +
					'<div class="aq-meta-item">' +
					'<label>Joined users</label>' +
					'<strong>' + escapeHtml(question.real_users) + '</strong>' +
					'<small>Only real users</small>' +
					'</div>' +
					'<div class="aq-meta-item">' +
					'<label>Yes price</label>' +
					'<strong class="yes">Rs ' + escapeHtml(question.yes_price) + '</strong>' +
					'<small>Current YES market price</small>' +
					'</div>' +
					'<div class="aq-meta-item">' +
					'<label>No price</label>' +
					'<strong class="no">Rs ' + escapeHtml(question.no_price) + '</strong>' +
					'<small>Current NO market price</small>' +
					'</div>' +
					'<div class="aq-meta-item">' +
					'<label>Start time</label>' +
					'<strong style="font-size:15px;">' + escapeHtml(question.start_time) + '</strong>' +
					'<small>Market opening time</small>' +
					'</div>' +
					'<div class="aq-meta-item">' +
					'<label>Close time</label>' +
					'<strong style="font-size:15px;">' + escapeHtml(question.end_time) + '</strong>' +
					'<small>Market closing time</small>' +
					'</div>' +
					'</div>' +
					'<div class="aq-open">' +
					'<span>Open question details</span>' +
					'<i class="fa-solid fa-arrow-right"></i>' +
					'</div>' +
					'</a>';
			}).join('');
		}

		function renderPagination(totalItems) {
			if (state.rowsPerPage === 'all' || totalItems <= state.rowsPerPage) {
				els.pagination.style.display = 'none';
				return;
			}

			var totalPages = Math.ceil(totalItems / state.rowsPerPage);
			if (state.page > totalPages) {
				state.page = totalPages;
			}

			var startItem = (state.page - 1) * state.rowsPerPage + 1;
			var endItem = Math.min(state.page * state.rowsPerPage, totalItems);
			var currentPage = state.page;
			var pages = [];

			els.pagination.style.display = 'flex';
			els.pageInfo.textContent = 'Showing ' + startItem + ' to ' + endItem + ' of ' + totalItems;

			if (totalPages <= 7) {
				for (var i = 1; i <= totalPages; i++) {
					pages.push(i);
				}
			} else {
				pages.push(1);
				if (currentPage > 3) {
					pages.push('...');
				}

				var start = Math.max(2, currentPage - 1);
				var end = Math.min(totalPages - 1, currentPage + 1);
				for (var j = start; j <= end; j++) {
					pages.push(j);
				}

				if (currentPage < totalPages - 2) {
					pages.push('...');
				}
				pages.push(totalPages);
			}

			var html = '<button class="aq-page-btn" data-page="' + (currentPage - 1) + '" ' + (currentPage === 1 ? 'disabled' : '') + '><i class="fa-solid fa-chevron-left"></i></button>';

			pages.forEach(function(pageItem) {
				if (pageItem === '...') {
					html += '<span class="aq-page-ellipsis">...</span>';
					return;
				}

				html += '<button class="aq-page-btn ' + (pageItem === currentPage ? 'is-active' : '') + '" data-page="' + pageItem + '">' + pageItem + '</button>';
			});

			html += '<button class="aq-page-btn" data-page="' + (currentPage + 1) + '" ' + (currentPage === totalPages ? 'disabled' : '') + '><i class="fa-solid fa-chevron-right"></i></button>';
			els.pageBtns.innerHTML = html;
		}

		function render() {
			var filtered = getFilteredQuestions();
			var itemsToRender = filtered;
			var startIndex = 0;

			renderCount(filtered.length);

			if (state.rowsPerPage !== 'all') {
				var limit = parseInt(state.rowsPerPage, 10);
				var totalPages = Math.ceil(filtered.length / limit);
				if (state.page > totalPages) {
					state.page = Math.max(1, totalPages);
				}

				startIndex = (state.page - 1) * limit;
				itemsToRender = filtered.slice(startIndex, startIndex + limit);
			}

			renderList(itemsToRender, startIndex);
			renderPagination(filtered.length);
			syncFiltersToUrl();
		}

		if (els.category) {
			els.category.value = String(state.categoryId);
			els.category.addEventListener('change', function(event) {
				state.categoryId = parseInt(event.target.value || '0', 10) || 0;
				state.page = 1;
				render();
			});
		}

		if (els.search) {
			els.search.addEventListener('input', function(event) {
				state.searchTerm = event.target.value.trim();
				state.page = 1;
				render();
			});
		}

		if (els.timing) {
			els.timing.addEventListener('change', function(event) {
				state.timing = event.target.value || 'all';
				state.page = 1;
				render();
			});
		}

		if (els.answerKey) {
			els.answerKey.addEventListener('change', function(event) {
				state.answerKey = event.target.value || 'all';
				state.page = 1;
				render();
			});
		}

		if (els.rows) {
			els.rows.addEventListener('change', function(event) {
				state.rowsPerPage = event.target.value === 'all' ? 'all' : parseInt(event.target.value, 10);
				state.page = 1;
				render();
			});
		}

		if (els.pageBtns) {
			els.pageBtns.addEventListener('click', function(event) {
				var button = event.target.closest('.aq-page-btn');
				if (!button || button.disabled) {
					return;
				}

				var nextPage = parseInt(button.getAttribute('data-page') || '1', 10);
				if (isNaN(nextPage)) {
					return;
				}

				state.page = nextPage;
				render();
				els.list.scrollIntoView({
					behavior: 'smooth',
					block: 'start'
				});
			});
		}

		render();
	});
</script>