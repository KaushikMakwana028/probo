<?php if ($this->session->flashdata('error')): ?>
	<div class="question-flash question-flash-error"><?php echo $this->session->flashdata('error'); ?></div>
<?php endif; ?>

<?php if ($this->session->flashdata('success')): ?>
	<div class="question-flash question-flash-success"><?php echo $this->session->flashdata('success'); ?></div>
<?php endif; ?>

<?php $total_questions = $selected_category && !empty($selected_category->questions) ? count($selected_category->questions) : 0; ?>

<style>
	.question-flash {
		padding: 14px 18px;
		border-radius: 14px;
		margin-bottom: 20px;
		font-weight: 600
	}

	.question-flash-error {
		background: #fef2f2;
		border: 1px solid #fecaca;
		color: #991b1b
	}

	.question-flash-success {
		background: #f0fdf4;
		border: 1px solid #bbf7d0;
		color: #166534
	}

	.question-page {
		display: grid;
		gap: 24px
	}

	.question-hero {
		position: relative;
		display: flex;
		justify-content: space-between;
		gap: 18px;
		align-items: center;
		padding: 34px;
		border-radius: 30px;
		background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 46%, #38bdf8 100%);
		color: #fff;
		box-shadow: 0 20px 40px rgba(15, 23, 42, .16);
		overflow: hidden
	}

	.question-hero:before,
	.question-hero:after {
		content: "";
		position: absolute;
		border-radius: 999px;
		background: rgba(255, 255, 255, .12)
	}

	.question-hero:before {
		width: 220px;
		height: 220px;
		top: -110px;
		right: 120px
	}

	.question-hero:after {
		width: 160px;
		height: 160px;
		bottom: -80px;
		right: -20px
	}

	.question-hero>* {
		position: relative;
		z-index: 1
	}

	.question-hero h1 {
		margin: 0 0 10px;
		color: #fff;
		font-size: 36px
	}

	.question-hero p {
		margin: 0;
		color: rgba(255, 255, 255, .84);
		max-width: 740px;
		font-size: 15px;
		line-height: 1.7
	}

	.question-hero-badge {
		min-width: 240px;
		padding: 20px;
		border-radius: 24px;
		background: rgba(255, 255, 255, .14);
		border: 1px solid rgba(255, 255, 255, .2);
		backdrop-filter: blur(10px);
		text-align: center
	}

	.question-hero-badge span {
		display: block;
		margin-bottom: 10px;
		font-size: 12px;
		text-transform: uppercase;
		letter-spacing: .12em;
		color: rgba(255, 255, 255, .74)
	}

	.question-hero-badge strong {
		font-size: 36px;
		color: #fff
	}

	.question-category-shell {
		background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
		border: 1px solid #dbe5f1;
		border-radius: 28px;
		padding: 28px;
		box-shadow: 0 14px 30px rgba(15, 23, 42, .05)
	}

	.question-category-shell h3,
	.question-list-head h3 {
		margin: 0 0 8px;
		color: #111827;
		font-size: 28px
	}

	.question-category-shell p,
	.question-list-head p {
		margin: 0;
		color: #64748b
	}

	.question-category-toolbar {
		display: grid;
		grid-template-columns: minmax(0, 1fr) 220px;
		gap: 16px;
		align-items: end;
		margin-top: 24px
	}

	.question-search {
		position: relative
	}

	.question-search label {
		display: block;
		margin-bottom: 8px;
		font-size: 12px;
		font-weight: 800;
		letter-spacing: .08em;
		text-transform: uppercase;
		color: #475569
	}

	.question-search input {
		width: 100%;
		padding: 15px 18px 15px 48px;
		border-radius: 18px;
		border: 1px solid #cbd5e1;
		background: #fff;
		color: #0f172a;
		font-size: 15px;
		box-shadow: inset 0 1px 2px rgba(15, 23, 42, .03)
	}

	.question-search input:focus {
		outline: none;
		border-color: #2563eb;
		box-shadow: 0 0 0 4px rgba(37, 99, 235, .1)
	}

	.question-search:before {
		content: "";
		position: absolute;
		left: 17px;
		bottom: 17px;
		width: 18px;
		height: 18px;
		border: 2px solid #94a3b8;
		border-radius: 50%
	}

	.question-search:after {
		content: "";
		position: absolute;
		left: 32px;
		bottom: 14px;
		width: 8px;
		height: 2px;
		background: #94a3b8;
		transform: rotate(45deg);
		border-radius: 999px
	}

	.question-category-helper {
		padding: 16px 18px;
		border-radius: 20px;
		background: #eef4ff;
		border: 1px solid #dbeafe
	}

	.question-category-helper span {
		display: block;
		margin-bottom: 6px;
		font-size: 12px;
		font-weight: 800;
		letter-spacing: .08em;
		text-transform: uppercase;
		color: #1d4ed8
	}

	.question-category-helper strong {
		display: block;
		font-size: 16px;
		color: #0f172a
	}

	.question-category-helper small {
		display: block;
		margin-top: 4px;
		color: #64748b;
		font-size: 13px
	}

	.category-grid {
		display: flex;
		flex-wrap: wrap;
		gap: 14px;
		margin-top: 20px
	}

	.category-card {
		position: relative;
		display: flex;
		align-items: center;
		gap: 12px;
		min-height: 72px;
		padding: 14px 18px;
		border-radius: 999px;
		border: 1px solid #dbe2ea;
		background: #fff;
		text-align: left;
		cursor: pointer;
		transition: all .2s ease
	}

	.category-card:hover {
		transform: translateY(-2px);
		box-shadow: 0 12px 24px rgba(15, 23, 42, .08);
		border-color: #bfdbfe
	}

	.category-card.active {
		background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 55%, #60a5fa 100%);
		border-color: #2563eb;
		box-shadow: 0 18px 32px rgba(37, 99, 235, .2)
	}

	.category-card.active .category-badge,
	.category-card.active h4,
	.category-card.active p,
	.category-card.active .category-count {
		color: #fff
	}

	.category-card.active .category-badge {
		background: rgba(255, 255, 255, .18)
	}

	.category-card-top {
		display: flex;
		align-items: center;
		gap: 10px;
		min-width: 0
	}

	.category-badge {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		width: 42px;
		height: 42px;
		border-radius: 50%;
		background: #e0ecff;
		color: #1d4ed8;
		font-size: 11px;
		font-weight: 800;
		text-transform: uppercase;
		letter-spacing: .06em;
		flex-shrink: 0
	}

	.category-card-copy {
		min-width: 0
	}

	.category-count {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		min-width: 40px;
		height: 40px;
		padding: 0 14px;
		border-radius: 999px;
		background: #eff6ff;
		color: #2563eb;
		font-size: 14px;
		font-weight: 800;
		flex-shrink: 0
	}

	.category-card h4 {
		margin: 0 0 3px;
		color: #0f172a;
		font-size: 17px;
		line-height: 1.2;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis
	}

	.category-card p {
		margin: 0;
		color: #64748b;
		font-size: 13px;
		white-space: nowrap
	}

	.category-empty-state {
		display: none;
		padding: 24px;
		border: 1px dashed #cbd5e1;
		border-radius: 22px;
		background: #f8fafc;
		text-align: center;
		color: #64748b;
		margin-top: 18px
	}

	.category-empty-state strong {
		display: block;
		margin-bottom: 6px;
		color: #0f172a;
		font-size: 18px
	}

	.question-content {
		display: grid;
		gap: 20px
	}

	.question-list-shell {
		background: #fff;
		border: 1px solid #e5e7eb;
		border-radius: 28px;
		padding: 26px;
		box-shadow: 0 12px 26px rgba(15, 23, 42, .05)
	}

	.question-stats {
		display: grid;
		grid-template-columns: repeat(4, minmax(0, 1fr));
		gap: 14px;
		margin: 20px 0
	}

	.question-stat {
		padding: 18px;
		border-radius: 20px;
		background: #f8fafc;
		border: 1px solid #e2e8f0
	}

	.question-stat span {
		display: block;
		margin-bottom: 8px;
		font-size: 12px;
		text-transform: uppercase;
		letter-spacing: .1em;
		color: #64748b;
		font-weight: 700
	}

	.question-stat strong {
		font-size: 30px;
		color: #111827
	}

	.question-grid {
		display: grid;
		grid-template-columns: 1fr;
		gap: 16px
	}

	.question-card {
		display: flex;
		flex-direction: column;
		gap: 16px;
		padding: 22px;
		border-radius: 24px;
		border: 1px solid #e2e8f0;
		background: #fff;
		text-decoration: none;
		transition: all .2s ease;
		box-shadow: 0 6px 18px rgba(15, 23, 42, .04)
	}

	.question-card:hover {
		transform: translateY(-2px);
		box-shadow: 0 14px 28px rgba(15, 23, 42, .08)
	}

	.question-card.correct {
		background: linear-gradient(135deg, #ecfdf5 0%, #f0fdf4 100%);
		border-color: #86efac
	}

	.question-card.wrong {
		background: linear-gradient(135deg, #fff1f2 0%, #fef2f2 100%);
		border-color: #fda4af
	}

	.question-card.pending {
		background: #fff
	}

	.question-card-top {
		display: flex;
		justify-content: space-between;
		gap: 12px;
		align-items: flex-start
	}

	.question-card-index {
		width: 44px;
		height: 44px;
		border-radius: 16px;
		background: #eff6ff;
		color: #2563eb;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		font-size: 15px;
		font-weight: 800;
		flex-shrink: 0
	}

	.question-card.correct .question-card-index {
		background: #dcfce7;
		color: #166534
	}

	.question-card.wrong .question-card-index {
		background: #ffe4e6;
		color: #be123c
	}

	.question-status {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		padding: 7px 11px;
		border-radius: 999px;
		font-size: 11px;
		font-weight: 800;
		text-transform: uppercase;
		letter-spacing: .08em
	}

	.question-status.pending {
		background: #e0f2fe;
		color: #0369a1
	}

	.question-status.correct {
		background: #dcfce7;
		color: #166534
	}

	.question-status.wrong {
		background: #fee2e2;
		color: #991b1b
	}

	.question-card h4 {
		margin: 0;
		color: #0f172a;
		font-size: 21px;
		line-height: 1.5
	}

	.question-card p {
		margin: 0;
		color: #64748b
	}

	.question-card-foot {
		display: flex;
		justify-content: space-between;
		gap: 12px;
		align-items: center;
		color: #475569;
		font-size: 13px
	}

	.question-card-amount {
		font-weight: 800
	}

	.question-empty {
		text-align: center;
		padding: 60px 20px;
		border: 1px dashed #cbd5e1;
		border-radius: 24px;
		background: #f8fafc
	}

	.question-empty h3 {
		margin: 0 0 8px;
		color: #111827;
		font-size: 26px
	}

	.question-empty p {
		margin: 0;
		color: #6b7280
	}

	.question-loading {
		display: flex;
		align-items: center;
		justify-content: center;
		padding: 48px;
		border-radius: 24px;
		background: #fff;
		border: 1px solid #e5e7eb;
		color: #64748b;
		font-weight: 700
	}

	@media (max-width:980px) {
		.question-hero {
			flex-direction: column;
			align-items: stretch
		}

		.question-stats,
		.question-category-toolbar {
			grid-template-columns: 1fr
		}
	}

	@media (max-width:720px) {
		.question-hero {
			padding: 26px
		}

		.question-hero h1 {
			font-size: 30px
		}

		.question-category-shell,
		.question-list-shell {
			padding: 20px
		}

		.category-grid {
			gap: 12px
		}

		.category-card {
			width: 100%;
			border-radius: 24px
		}

		.category-card h4,
		.category-card p {
			white-space: normal
		}
	}
</style>

<div class="question-page">
	<section class="question-hero">
		<div>
			<h1>Questions</h1>
			<p>Select a category card below. All questions from that category will load instantly, and each question will appear in a single clean column.</p>
		</div>
		<div class="question-hero-badge">
			<span>Answered Questions</span>
			<strong id="heroAnsweredCount"><?php echo (int) $answered_count; ?>/<?php echo (int) $total_questions; ?></strong>
		</div>
	</section>

	<section class="question-category-shell">
		<h3>Select Category</h3>
		<p>Pick a category capsule below. You can search in the same section while typing, and the questions will switch instantly without reloading.</p>

		<div class="question-category-toolbar">
			<div class="question-search">
				<label for="categorySearch">Search Category</label>
				<input type="text" id="categorySearch" placeholder="Type category name here">
			</div>
			<div class="question-category-helper">
				<span>Quick Filter</span>
				<strong><?php echo count($categories); ?> categories available</strong>
				<small>Select one capsule to load its questions in the same column below.</small>
			</div>
		</div>

		<div class="category-grid" id="categoryGrid">
			<?php foreach ($categories as $category_item): ?>
				<button
					type="button"
					class="category-card<?php echo ($selected_category && (int) $selected_category->id === (int) $category_item->id) ? ' active' : ''; ?>"
					data-category-card
					data-category-id="<?php echo (int) $category_item->id; ?>">
					<div class="category-card-top">
						<span class="category-badge">CAT</span>
						<div class="category-card-copy">
							<h4><?php echo html_escape($category_item->name); ?></h4>
							<p><?php echo (int) $category_item->question_count; ?> question<?php echo (int) $category_item->question_count === 1 ? '' : 's'; ?></p>
						</div>
					</div>
					<span class="category-count"><?php echo (int) $category_item->question_count; ?></span>
				</button>
			<?php endforeach; ?>
		</div>

		<div class="category-empty-state" id="categoryEmptyState">
			<strong>No category found</strong>
			Search with another word to see matching category capsules.
		</div>
	</section>

	<div class="question-content" id="questionContent">
		<?php echo $question_list_html; ?>
	</div>
</div>

<script>
	document.addEventListener('DOMContentLoaded', function() {
		const categoryGrid = document.getElementById('categoryGrid');
		const questionContent = document.getElementById('questionContent');
		const heroAnsweredCount = document.getElementById('heroAnsweredCount');
		const categorySearch = document.getElementById('categorySearch');
		const categoryEmptyState = document.getElementById('categoryEmptyState');

		if (!categoryGrid || !questionContent) {
			return;
		}

		const categoryCards = Array.prototype.slice.call(categoryGrid.querySelectorAll('[data-category-card]'));

		function setActiveCategory(categoryId) {
			categoryCards.forEach(function(card) {
				card.classList.toggle('active', Number(card.dataset.categoryId) === Number(categoryId));
			});
		}

		function filterCategories() {
			if (!categorySearch) {
				return;
			}

			const term = categorySearch.value.toLowerCase().trim();
			let visibleCount = 0;

			categoryCards.forEach(function(card) {
				const nameNode = card.querySelector('h4');
				const categoryName = nameNode ? nameNode.textContent.toLowerCase() : '';
				const matches = !term || categoryName.indexOf(term) !== -1;
				card.style.display = matches ? '' : 'none';
				if (matches) {
					visibleCount += 1;
				}
			});

			if (categoryEmptyState) {
				categoryEmptyState.style.display = visibleCount === 0 ? 'block' : 'none';
			}
		}

		function setLoadingState() {
			questionContent.innerHTML = '<div class="question-loading">Loading category questions...</div>';
		}

		function updateHistory(categoryId) {
			const targetUrl = categoryId ? '<?php echo site_url('questions'); ?>?category_id=' + categoryId : '<?php echo site_url('questions'); ?>';
			window.history.replaceState({}, '', targetUrl);
		}

		categoryGrid.addEventListener('click', function(event) {
			const button = event.target.closest('[data-category-card]');
			if (!button) {
				return;
			}

			const categoryId = Number(button.dataset.categoryId || '0');
			if (!categoryId) {
				return;
			}

			setActiveCategory(categoryId);
			setLoadingState();

			fetch('<?php echo site_url('questions/category-data'); ?>/' + categoryId, {
					headers: {
						'X-Requested-With': 'XMLHttpRequest'
					}
				})
				.then(function(response) {
					return response.json();
				})
				.then(function(data) {
					if (!data || data.status !== 'ok') {
						throw new Error('Unable to load questions.');
					}

					questionContent.innerHTML = data.html;
					heroAnsweredCount.textContent = data.answered_count + '/' + data.total_questions;
					updateHistory(categoryId);
				})
				.catch(function() {
					questionContent.innerHTML = '<div class="question-empty"><h3>Unable to load category</h3><p>Please try selecting the category again.</p></div>';
				});
		});

		if (categorySearch) {
			categorySearch.addEventListener('input', filterCategories);
			filterCategories();
		}
	});
</script>