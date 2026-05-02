<?php
$initial_listing = isset($referral_listing) && is_array($referral_listing) ? $referral_listing : array(
	'rows' => array(),
	'total' => 0,
	'filtered_total' => 0,
	'page' => 1,
	'per_page' => 10,
	'total_pages' => 1
);

$initial_start = $initial_listing['filtered_total'] > 0 ? (($initial_listing['page'] - 1) * $initial_listing['per_page']) + 1 : 0;
$initial_end = min($initial_listing['filtered_total'], $initial_listing['page'] * $initial_listing['per_page']);
?>

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
				<p>Search, filter by date, choose row count, and paginate without reloading the page.</p>
			</div>
			<a class="ref-list-btn" href="<?php echo site_url('admin/referrals/add'); ?>">Update Amount</a>
		</div>

		<div class="ref-list-toolbar">
			<div class="ref-list-search">
				<label for="refSearch">Search</label>
				<input type="search" id="refSearch" placeholder="Search user, email, mobile, or code">
			</div>
			<div class="ref-list-filter">
				<label for="refDateFrom">From</label>
				<input type="date" id="refDateFrom">
			</div>
			<div class="ref-list-filter">
				<label for="refDateTo">To</label>
				<input type="date" id="refDateTo">
			</div>
			<div class="ref-list-filter ref-list-filter-rows">
				<label for="refPerPage">Rows</label>
				<select id="refPerPage">
					<option value="5">5</option>
					<option value="10" selected>10</option>
					<option value="25">25</option>
					<option value="50">50</option>
					<option value="100">100</option>
				</select>
			</div>
			<div class="ref-list-filter ref-list-filter-action">
				<button type="button" class="ref-list-reset" id="refResetBtn">Reset</button>
			</div>
		</div>

		<div class="ref-list-meta">
			<p id="refListSummary">Showing <?php echo $initial_start; ?> to <?php echo $initial_end; ?> of <?php echo (int) $initial_listing['filtered_total']; ?> entries</p>
			<p class="ref-list-total">Total records: <strong id="refListTotal"><?php echo (int) $initial_listing['total']; ?></strong></p>
		</div>

		<div class="ref-list-table-wrap">
			<div class="ref-list-loading" id="refListLoading" hidden>Loading referral activity...</div>
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
				<tbody id="refListBody">
					<?php if (!empty($initial_listing['rows'])): ?>
						<?php foreach ($initial_listing['rows'] as $index => $reward): ?>
							<tr>
								<td><?php echo $initial_start + $index; ?></td>
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

		<div class="ref-list-pagination" id="refListPagination">
			<button type="button" class="ref-page-btn" id="refPrevBtn">Previous</button>
			<div class="ref-page-numbers" id="refPageNumbers"></div>
			<button type="button" class="ref-page-btn" id="refNextBtn">Next</button>
		</div>
	</div>
</section>

<style>
	.ref-list-page {
		display: grid;
		gap: 22px
	}

	.ref-list-hero {
		display: grid;
		grid-template-columns: minmax(0, 1fr) 340px;
		gap: 20px;
		align-items: center;
		padding: 28px;
		border-radius: 26px;
		background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 64%, #38bdf8 100%);
		color: #fff
	}

	.ref-list-hero h2 {
		margin: 0 0 8px;
		color: #fff;
		font-size: 34px
	}

	.ref-list-hero p {
		margin: 0;
		color: rgba(255, 255, 255, .84);
		line-height: 1.7
	}

	.ref-list-hero-box {
		padding: 20px;
		border-radius: 20px;
		background: rgba(255, 255, 255, .12);
		border: 1px solid rgba(255, 255, 255, .16)
	}

	.ref-list-hero-box span {
		display: block;
		margin-bottom: 8px;
		font-size: 12px;
		text-transform: uppercase;
		letter-spacing: .08em;
		color: rgba(255, 255, 255, .72);
		font-weight: 700
	}

	.ref-list-hero-box strong {
		display: block;
		color: #fff;
		font-size: 20px;
		line-height: 1.6
	}

	.ref-list-card {
		background: #fff;
		border: 1px solid #e2e8f0;
		border-radius: 24px;
		overflow: hidden;
		box-shadow: 0 10px 26px rgba(15, 23, 42, .05)
	}

	.ref-list-head {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 16px;
		padding: 22px 24px;
		border-bottom: 1px solid #eef2f7
	}

	.ref-list-head h3 {
		margin: 0 0 6px;
		color: #111827;
		font-size: 24px
	}

	.ref-list-head p {
		margin: 0;
		color: #64748b
	}

	.ref-list-btn {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		padding: 12px 16px;
		border-radius: 14px;
		background: linear-gradient(135deg, #2563eb 0%, #4338ca 100%);
		color: #fff;
		text-decoration: none;
		font-weight: 700
	}

	.ref-list-toolbar {
		display: grid;
		grid-template-columns: minmax(260px, 1.3fr) repeat(3, minmax(140px, .8fr)) auto;
		gap: 14px;
		padding: 20px 24px;
		border-bottom: 1px solid #eef2f7;
		background: #f8fbff
	}

	.ref-list-search,
	.ref-list-filter {
		display: grid;
		gap: 8px
	}

	.ref-list-toolbar label {
		margin: 0;
		font-size: 13px;
		font-weight: 700;
		color: #334155
	}

	.ref-list-filter-action {
		align-self: end
	}

	.ref-list-reset {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		min-height: 44px;
		padding: 0 16px;
		border: 1px solid #cbd5e1;
		border-radius: 12px;
		background: #fff;
		color: #0f172a;
		font-weight: 700;
		cursor: pointer
	}

	.ref-list-meta {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 16px;
		padding: 16px 24px;
		border-bottom: 1px solid #eef2f7
	}

	.ref-list-meta p {
		margin: 0;
		color: #64748b
	}

	.ref-list-total strong {
		color: #0f172a
	}

	.ref-list-table-wrap {
		position: relative;
		overflow: auto
	}

	.ref-list-loading {
		position: absolute;
		inset: 0;
		display: flex;
		align-items: center;
		justify-content: center;
		background: rgba(255, 255, 255, .82);
		color: #1d4ed8;
		font-weight: 700;
		z-index: 1
	}

	.ref-list-loading[hidden] {
		display: none !important
	}

	.ref-list-table {
		width: 100%;
		border-collapse: collapse;
		min-width: 980px
	}

	.ref-list-table th {
		padding: 14px 18px;
		background: #f8fafc;
		color: #64748b;
		font-size: 12px;
		text-transform: uppercase;
		letter-spacing: .08em;
		text-align: left
	}

	.ref-list-table td {
		padding: 16px 18px;
		border-top: 1px solid #eef2f7;
		color: #111827;
		vertical-align: top
	}

	.ref-list-table strong {
		display: block;
		color: #111827
	}

	.ref-list-table small {
		display: block;
		margin-top: 4px;
		color: #64748b;
		line-height: 1.6
	}

	.ref-list-code {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		padding: 8px 12px;
		border-radius: 999px;
		background: #eef2ff;
		color: #4338ca;
		font-weight: 800;
		letter-spacing: .08em
	}

	.ref-money {
		font-weight: 800;
		color: #166534
	}

	.ref-list-empty {
		text-align: center;
		padding: 28px;
		color: #64748b
	}

	.ref-list-pagination {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 12px;
		padding: 18px 24px;
		border-top: 1px solid #eef2f7;
		background: #fff
	}

	.ref-page-numbers {
		display: flex;
		align-items: center;
		justify-content: center;
		gap: 8px;
		flex-wrap: wrap
	}

	.ref-page-btn,
	.ref-page-number {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		min-width: 44px;
		height: 44px;
		padding: 0 14px;
		border: 1px solid #cbd5e1;
		border-radius: 12px;
		background: #fff;
		color: #0f172a;
		font-weight: 700;
		cursor: pointer;
		transition: all .2s ease
	}

	.ref-page-btn[disabled],
	.ref-page-number[disabled] {
		opacity: .45;
		cursor: not-allowed
	}

	.ref-page-number.active {
		background: #2563eb;
		border-color: #2563eb;
		color: #fff
	}

	.ref-page-ellipsis {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		min-width: 24px;
		color: #64748b;
		font-weight: 700
	}

	@media (max-width:1100px) {
		.ref-list-hero {
			grid-template-columns: 1fr
		}

		.ref-list-toolbar {
			grid-template-columns: repeat(2, minmax(0, 1fr))
		}

		.ref-list-filter-action {
			grid-column: 1/-1
		}
	}

	@media (max-width:700px) {

		.ref-list-head,
		.ref-list-meta,
		.ref-list-pagination {
			flex-direction: column;
			align-items: flex-start
		}

		.ref-list-toolbar {
			grid-template-columns: 1fr
		}

		.ref-page-numbers {
			justify-content: flex-start
		}
	}
</style>

<script>
	(function() {
		var endpoint = <?php echo json_encode(site_url('admin/referrals/list-data')); ?>;
		var bodyEl = document.getElementById('refListBody');
		var loadingEl = document.getElementById('refListLoading');
		var summaryEl = document.getElementById('refListSummary');
		var totalEl = document.getElementById('refListTotal');
		var paginationEl = document.getElementById('refListPagination');
		var pageNumbersEl = document.getElementById('refPageNumbers');
		var prevBtn = document.getElementById('refPrevBtn');
		var nextBtn = document.getElementById('refNextBtn');
		var searchEl = document.getElementById('refSearch');
		var dateFromEl = document.getElementById('refDateFrom');
		var dateToEl = document.getElementById('refDateTo');
		var perPageEl = document.getElementById('refPerPage');
		var resetBtn = document.getElementById('refResetBtn');
		var debounceTimer = null;
		var activeRequest = null;
		var state = {
			search: '',
			date_from: '',
			date_to: '',
			per_page: 10,
			page: 1
		};

		function escapeHtml(value) {
			return String(value || '').replace(/[&<>"']/g, function(char) {
				return {
					'&': '&amp;',
					'<': '&lt;',
					'>': '&gt;',
					'"': '&quot;',
					"'": '&#039;'
				} [char];
			});
		}

		function buildRow(row) {
			return '<tr>' +
				'<td>' + row.position + '</td>' +
				'<td><strong>' + escapeHtml(row.referred_name || '-') + '</strong><small>' + escapeHtml((row.referred_email || '-') + ' | ' + (row.referred_mobile || '-')) + '</small></td>' +
				'<td><strong>' + escapeHtml(row.referrer_name || '-') + '</strong><small>' + escapeHtml((row.referrer_email || '-') + ' | ' + (row.referrer_mobile || '-')) + '</small></td>' +
				'<td><span class="ref-list-code">' + escapeHtml(row.referral_code || '-') + '</span></td>' +
				'<td class="ref-money">Rs ' + escapeHtml(row.referred_bonus || '0.00') + '</td>' +
				'<td class="ref-money">Rs ' + escapeHtml(row.referrer_bonus || '0.00') + '</td>' +
				'<td>' + escapeHtml(row.created_at || '-') + '</td>' +
				'</tr>';
		}

		function buildEmptyRow(message) {
			return '<tr><td colspan="7" class="ref-list-empty">' + escapeHtml(message) + '</td></tr>';
		}

		function toggleLoading(isLoading) {
			loadingEl.hidden = !isLoading;
		}

		function updateSummary(meta) {
			summaryEl.textContent = 'Showing ' + meta.start + ' to ' + meta.end + ' of ' + meta.filtered_total + ' entries';
			totalEl.textContent = meta.total;
		}

		function createPageButton(label, page, isActive) {
			var button = document.createElement('button');
			button.type = 'button';
			button.className = 'ref-page-number' + (isActive ? ' active' : '');
			button.textContent = label;
			button.setAttribute('data-page', page);
			if (isActive) {
				button.disabled = true;
			}
			return button;
		}

		function renderPagination(meta) {
			pageNumbersEl.innerHTML = '';
			paginationEl.hidden = meta.total_pages <= 1;
			prevBtn.disabled = meta.page <= 1;
			nextBtn.disabled = meta.page >= meta.total_pages;

			if (meta.total_pages <= 1) {
				return;
			}

			var pages = [];
			var i;

			for (i = 1; i <= meta.total_pages; i++) {
				if (i === 1 || i === meta.total_pages || (i >= meta.page - 1 && i <= meta.page + 1)) {
					pages.push(i);
				}
			}

			var lastPage = 0;
			pages.forEach(function(page) {
				if (page - lastPage > 1) {
					var ellipsis = document.createElement('span');
					ellipsis.className = 'ref-page-ellipsis';
					ellipsis.textContent = '...';
					pageNumbersEl.appendChild(ellipsis);
				}
				pageNumbersEl.appendChild(createPageButton(String(page), page, page === meta.page));
				lastPage = page;
			});
		}

		function renderTable(payload) {
			var rows = payload.rows || [];
			var meta = payload.pagination;
			if (!rows.length) {
				bodyEl.innerHTML = buildEmptyRow('No referral activity found for the selected filters.');
			} else {
				bodyEl.innerHTML = rows.map(buildRow).join('');
			}
			updateSummary(meta);
			renderPagination(meta);
			state.page = meta.page;
		}

		function syncUrl() {
			if (!window.history || !window.history.replaceState) {
				return;
			}

			var params = new URLSearchParams();
			if (state.search) params.set('search', state.search);
			if (state.date_from) params.set('date_from', state.date_from);
			if (state.date_to) params.set('date_to', state.date_to);
			if (state.per_page !== 10) params.set('per_page', String(state.per_page));
			if (state.page > 1) params.set('page', String(state.page));

			var nextUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
			window.history.replaceState({}, '', nextUrl);
		}

		function fetchRows() {
			if (activeRequest) {
				activeRequest.abort();
			}

			activeRequest = new AbortController();
			var params = new URLSearchParams({
				search: state.search,
				date_from: state.date_from,
				date_to: state.date_to,
				per_page: String(state.per_page),
				page: String(state.page)
			});

			toggleLoading(true);

			fetch(endpoint + '?' + params.toString(), {
				headers: {
					'X-Requested-With': 'XMLHttpRequest'
				},
				signal: activeRequest.signal
			}).then(function(response) {
				if (!response.ok) {
					throw new Error('Request failed');
				}
				return response.json();
			}).then(function(payload) {
				if (!payload || payload.status !== 'success') {
					throw new Error('Invalid response');
				}
				renderTable(payload);
				syncUrl();
			}).catch(function(error) {
				if (error.name === 'AbortError') {
					return;
				}
				bodyEl.innerHTML = buildEmptyRow('Unable to load referral activity right now. Please try again.');
			}).finally(function() {
				toggleLoading(false);
			});
		}

		function queueFetch(resetPage) {
			if (resetPage) {
				state.page = 1;
			}

			window.clearTimeout(debounceTimer);
			debounceTimer = window.setTimeout(fetchRows, 250);
		}

		function loadStateFromUrl() {
			var params = new URLSearchParams(window.location.search);
			state.search = params.get('search') || '';
			state.date_from = params.get('date_from') || '';
			state.date_to = params.get('date_to') || '';
			state.per_page = parseInt(params.get('per_page') || '10', 10);
			state.page = parseInt(params.get('page') || '1', 10);

			if ([5, 10, 25, 50, 100].indexOf(state.per_page) === -1) {
				state.per_page = 10;
			}

			if (state.page < 1) {
				state.page = 1;
			}

			searchEl.value = state.search;
			dateFromEl.value = state.date_from;
			dateToEl.value = state.date_to;
			perPageEl.value = String(state.per_page);
		}

		searchEl.addEventListener('input', function() {
			state.search = this.value.trim();
			queueFetch(true);
		});

		dateFromEl.addEventListener('change', function() {
			state.date_from = this.value;
			queueFetch(true);
		});

		dateToEl.addEventListener('change', function() {
			state.date_to = this.value;
			queueFetch(true);
		});

		perPageEl.addEventListener('change', function() {
			state.per_page = parseInt(this.value, 10) || 10;
			queueFetch(true);
		});

		resetBtn.addEventListener('click', function() {
			state.search = '';
			state.date_from = '';
			state.date_to = '';
			state.per_page = 10;
			state.page = 1;
			searchEl.value = '';
			dateFromEl.value = '';
			dateToEl.value = '';
			perPageEl.value = '10';
			fetchRows();
		});

		pageNumbersEl.addEventListener('click', function(event) {
			var button = event.target.closest('[data-page]');
			if (!button) {
				return;
			}
			state.page = parseInt(button.getAttribute('data-page'), 10) || 1;
			fetchRows();
		});

		prevBtn.addEventListener('click', function() {
			if (state.page > 1) {
				state.page -= 1;
				fetchRows();
			}
		});

		nextBtn.addEventListener('click', function() {
			state.page += 1;
			fetchRows();
		});

		loadStateFromUrl();
		renderPagination({
			page: <?php echo (int) $initial_listing['page']; ?>,
			total_pages: <?php echo (int) $initial_listing['total_pages']; ?>
		});
		if (window.location.search) {
			fetchRows();
		}
	}());
</script>