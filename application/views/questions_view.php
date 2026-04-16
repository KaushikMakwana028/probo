<?php if ($this->session->flashdata('error')): ?>
	<div class="qflash qflash-err"><?php echo $this->session->flashdata('error'); ?></div>
<?php endif; ?>
<?php if ($this->session->flashdata('success')): ?>
	<div class="qflash qflash-ok"><?php echo $this->session->flashdata('success'); ?></div>
<?php endif; ?>

<?php $total_questions = $selected_category && !empty($selected_category->questions) ? count($selected_category->questions) : 0; ?>

<style>
	/* ----- RESET & VARIABLES (professional dark accent theme) ----- */
	*,
	*::before,
	*::after {
		box-sizing: border-box;
		margin: 0;
		padding: 0;
	}

	:root {
		--bg-page: #f1f5f9;
		--surface: #ffffff;
		--surface-secondary: #f8fafc;
		--text-primary: #0f172a;
		--text-secondary: #334155;
		--text-tertiary: #64748b;
		--border-light: #e2e8f0;
		--border-medium: #cbd5e1;
		--accent-dark: #0f172a;
		/* dark slate */
		--accent-blue: #2563eb;
		--accent-green: #10b981;
		--accent-red: #ef4444;
		--shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.02), 0 1px 2px rgba(0, 0, 0, 0.05);
		--shadow-md: 0 4px 8px -2px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.03);
		--shadow-lg: 0 12px 24px -8px rgba(0, 0, 0, 0.08);
		--radius-card: 20px;
		--radius-element: 14px;
		--radius-pill: 40px;
		--transition: all 0.2s ease;
	}

	/* ----- FLASH MESSAGES (refined) ----- */
	.qflash {
		padding: 14px 18px;
		border-radius: 16px;
		margin-bottom: 24px;
		font-size: 0.9rem;
		font-weight: 500;
		display: flex;
		align-items: center;
		backdrop-filter: blur(4px);
	}

	.qflash-err {
		background: rgba(239, 68, 68, 0.08);
		border: 1px solid rgba(239, 68, 68, 0.2);
		color: #b91c1c;
	}

	.qflash-ok {
		background: rgba(16, 185, 129, 0.08);
		border: 1px solid rgba(16, 185, 129, 0.2);
		color: #047857;
	}

	/* ----- PAGE WRAPPER ----- */
	.qp {
		display: flex;
		flex-direction: column;
		gap: 24px;
		max-width: 1400px;
		margin: 0 auto;
	}

	/* ----- HERO SECTION (dark accent) ----- */
	.qhero {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 24px;
		padding: 32px 36px;
		border-radius: var(--radius-card);
		background: var(--accent-dark);
		background-image: radial-gradient(circle at 100% 0%, rgba(56, 189, 248, 0.08) 0%, transparent 50%);
		border: 1px solid rgba(255, 255, 255, 0.05);
		box-shadow: var(--shadow-lg);
	}

	.qhero-left h1 {
		font-size: 1.8rem;
		font-weight: 600;
		letter-spacing: -0.01em;
		color: #ffffff;
		margin-bottom: 6px;
	}

	.qhero-left p {
		font-size: 0.95rem;
		color: #cbd5e1;
		line-height: 1.6;
		max-width: 480px;
	}

	.qhero-stats {
		display: flex;
		gap: 16px;
		flex-shrink: 0;
	}

	.stat-pill {
		min-width: 140px;
		padding: 16px 20px;
		border-radius: 18px;
		text-align: left;
		background: rgba(255, 255, 255, 0.06);
		backdrop-filter: blur(8px);
		border: 1px solid rgba(255, 255, 255, 0.1);
		box-shadow: var(--shadow-sm);
	}

	.stat-pill-label {
		display: block;
		font-size: 0.7rem;
		text-transform: uppercase;
		letter-spacing: 0.1em;
		color: #94a3b8;
		margin-bottom: 6px;
		font-weight: 600;
	}

	.stat-pill-val {
		font-size: 1.8rem;
		font-weight: 600;
		color: #ffffff;
		line-height: 1.2;
	}

	.stat-prog {
		margin-top: 10px;
		height: 5px;
		background: rgba(255, 255, 255, 0.15);
		border-radius: 999px;
		overflow: hidden;
	}

	.stat-prog-fill {
		height: 100%;
		background: var(--accent-blue);
		border-radius: 999px;
		transition: width 0.5s cubic-bezier(0.4, 0, 0.2, 1);
	}

	/* ----- SECTION CARDS (clean, elevated) ----- */
	.qsection {
		border-radius: var(--radius-card);
		overflow: hidden;
		background: var(--surface);
		border: 1px solid var(--border-light);
		box-shadow: var(--shadow-md);
	}

	.qsection-head {
		padding: 20px 28px;
		background: var(--surface-secondary);
		border-bottom: 1px solid var(--border-light);
	}

	.qsection-head h2 {
		font-size: 1.2rem;
		font-weight: 600;
		color: var(--text-primary);
		margin-bottom: 4px;
	}

	.qsection-head p {
		font-size: 0.85rem;
		color: var(--text-tertiary);
	}

	.qsection-body {
		padding: 24px 28px;
		background: var(--surface);
	}

	/* ----- CATEGORY TOOLBAR ----- */
	.cat-toolbar {
		display: grid;
		grid-template-columns: 1fr auto;
		gap: 16px;
		align-items: center;
		margin-bottom: 24px;
	}

	.cat-search {
		position: relative;
	}

	.cat-search svg {
		position: absolute;
		left: 16px;
		top: 50%;
		transform: translateY(-50%);
		width: 16px;
		height: 16px;
		color: var(--text-tertiary);
		pointer-events: none;
	}

	.cat-search input {
		width: 100%;
		padding: 12px 18px 12px 44px;
		border-radius: 40px;
		font-family: inherit;
		font-size: 0.9rem;
		border: 1px solid var(--border-light);
		background: var(--surface);
		color: var(--text-primary);
		outline: none;
		transition: var(--transition);
		box-shadow: var(--shadow-sm);
	}

	.cat-search input:focus {
		border-color: var(--accent-blue);
		box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
	}

	.cat-badge {
		padding: 10px 18px;
		border-radius: 40px;
		white-space: nowrap;
		font-size: 0.8rem;
		font-weight: 500;
		background: var(--surface-secondary);
		border: 1px solid var(--border-light);
		color: var(--text-secondary);
	}

	.cat-badge strong {
		color: var(--accent-blue);
		font-weight: 700;
	}

	/* ----- CATEGORY CAPSULES (pill style) ----- */
	.cat-grid {
		display: flex;
		flex-wrap: wrap;
		gap: 12px;
	}

	.cat-card {
		display: flex;
		align-items: center;
		gap: 10px;
		padding: 10px 18px 10px 10px;
		border-radius: var(--radius-pill);
		border: 1px solid var(--border-light);
		background: var(--surface);
		cursor: pointer;
		font-family: inherit;
		font-size: 0.9rem;
		font-weight: 500;
		color: var(--text-secondary);
		transition: var(--transition);
		box-shadow: var(--shadow-sm);
	}

	.cat-card:hover {
		border-color: var(--accent-blue);
		background: #f0f9ff;
		color: var(--accent-blue);
		transform: translateY(-1px);
		box-shadow: var(--shadow-md);
	}

	.cat-card.active {
		border-color: var(--accent-dark);
		background: var(--accent-dark);
		color: #ffffff;
		box-shadow: 0 6px 12px -6px rgba(15, 23, 42, 0.2);
	}

	.cat-avatar {
		width: 32px;
		height: 32px;
		border-radius: 50%;
		background: var(--border-light);
		display: inline-flex;
		align-items: center;
		justify-content: center;
		font-size: 0.7rem;
		font-weight: 600;
		color: var(--text-secondary);
		flex-shrink: 0;
	}

	.cat-card.active .cat-avatar {
		background: rgba(255, 255, 255, 0.15);
		color: #ffffff;
	}

	.cat-card-n {
		font-size: 0.8rem;
		color: var(--text-tertiary);
	}

	.cat-card.active .cat-card-n {
		color: rgba(255, 255, 255, 0.7);
	}

	.cat-empty {
		display: none;
		padding: 24px;
		border: 1px dashed var(--border-medium);
		border-radius: var(--radius-element);
		text-align: center;
		font-size: 0.9rem;
		color: var(--text-tertiary);
		margin-top: 16px;
	}

	/* ----- STATS BAR (inside question section) ----- */
	.q-stats {
		display: grid;
		grid-template-columns: repeat(4, 1fr);
		gap: 14px;
		margin-bottom: 24px;
	}

	.q-stat {
		padding: 16px 18px;
		border-radius: var(--radius-element);
		background: var(--surface-secondary);
		border: 1px solid var(--border-light);
	}

	.q-stat-label {
		display: block;
		font-size: 0.7rem;
		text-transform: uppercase;
		letter-spacing: 0.08em;
		color: var(--text-tertiary);
		margin-bottom: 6px;
		font-weight: 600;
	}

	.q-stat-val {
		font-size: 1.8rem;
		font-weight: 600;
		color: var(--text-primary);
	}

	.q-stat.stat-correct .q-stat-val {
		color: var(--accent-green);
	}

	.q-stat.stat-wrong .q-stat-val {
		color: var(--accent-red);
	}

	.q-stat.stat-pending .q-stat-val {
		color: var(--accent-blue);
	}

	/* ----- QUESTION CARDS (modern, airy) ----- */
	.question-list-shell {
		display: grid;
		gap: 18px;
	}

	.question-list-head h3 {
		font-size: 1.1rem;
		font-weight: 600;
		color: var(--text-primary);
		margin-bottom: 6px;
	}

	.question-list-head p {
		font-size: 0.85rem;
		color: var(--text-tertiary);
	}

	.q-list {
		display: flex;
		flex-direction: column;
		gap: 12px;
	}

	.q-card {
		padding: 20px 24px;
		border-radius: var(--radius-element);
		display: flex;
		flex-direction: column;
		gap: 14px;
		border: 1px solid var(--border-light);
		background: var(--surface);
		transition: var(--transition);
		text-decoration: none;
		color: inherit;
		box-shadow: var(--shadow-sm);
	}

	.q-card:hover {
		border-color: var(--border-medium);
		box-shadow: var(--shadow-md);
		transform: translateY(-2px);
	}

	.q-card.correct {
		background: rgba(16, 185, 129, 0.03);
		border-color: rgba(16, 185, 129, 0.3);
	}

	.q-card.wrong {
		background: rgba(239, 68, 68, 0.03);
		border-color: rgba(239, 68, 68, 0.3);
	}

	.q-card.pending {
		background: rgba(37, 99, 235, 0.02);
		border-color: rgba(37, 99, 235, 0.2);
	}

	.q-card-top {
		display: flex;
		align-items: flex-start;
		justify-content: space-between;
		gap: 16px;
	}

	.q-card-left {
		display: flex;
		align-items: flex-start;
		gap: 14px;
		flex: 1;
		min-width: 0;
	}

	.q-idx {
		width: 36px;
		height: 36px;
		border-radius: 12px;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		font-size: 0.85rem;
		font-weight: 600;
		flex-shrink: 0;
		background: var(--surface-secondary);
		color: var(--text-secondary);
	}

	.q-card.correct .q-idx {
		background: rgba(16, 185, 129, 0.15);
		color: #047857;
	}

	.q-card.wrong .q-idx {
		background: rgba(239, 68, 68, 0.15);
		color: #b91c1c;
	}

	.q-card.pending .q-idx {
		background: rgba(37, 99, 235, 0.15);
		color: #1e40af;
	}

	.q-card h4 {
		font-size: 1rem;
		font-weight: 500;
		color: var(--text-primary);
		line-height: 1.5;
	}

	.q-status {
		padding: 5px 12px;
		border-radius: 30px;
		font-size: 0.7rem;
		font-weight: 600;
		text-transform: uppercase;
		letter-spacing: 0.05em;
		white-space: nowrap;
		flex-shrink: 0;
	}

	.q-status.pending {
		background: rgba(37, 99, 235, 0.1);
		color: #1e40af;
		border: 1px solid rgba(37, 99, 235, 0.2);
	}

	.q-status.correct {
		background: rgba(16, 185, 129, 0.1);
		color: #047857;
		border: 1px solid rgba(16, 185, 129, 0.2);
	}

	.q-status.wrong {
		background: rgba(239, 68, 68, 0.1);
		color: #b91c1c;
		border: 1px solid rgba(239, 68, 68, 0.2);
	}

	.q-card-foot {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 16px;
		font-size: 0.8rem;
		color: var(--text-tertiary);
		border-top: 1px solid var(--border-light);
		padding-top: 14px;
	}

	.q-card.correct .q-card-foot {
		border-top-color: rgba(16, 185, 129, 0.2);
	}

	.q-card.wrong .q-card-foot {
		border-top-color: rgba(239, 68, 68, 0.2);
	}

	.q-card.pending .q-card-foot {
		border-top-color: rgba(37, 99, 235, 0.15);
	}

	.q-bet-options {
		display: flex;
		gap: 8px;
		flex-wrap: wrap;
	}

	.q-bet {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		padding: 6px 14px;
		border-radius: 30px;
		background: var(--surface-secondary);
		border: 1px solid var(--border-light);
		font-family: inherit;
		font-size: 0.8rem;
		font-weight: 500;
		color: var(--text-secondary);
		cursor: pointer;
		text-decoration: none;
		transition: var(--transition);
	}

	.q-bet-yes:hover {
		border-color: var(--accent-green);
		color: #047857;
		background: rgba(16, 185, 129, 0.05);
	}

	.q-bet-no:hover {
		border-color: var(--accent-red);
		color: #b91c1c;
		background: rgba(239, 68, 68, 0.05);
	}

	.q-dot {
		width: 6px;
		height: 6px;
		border-radius: 50%;
		display: inline-block;
	}

	.q-dot-yes {
		background: var(--accent-green);
	}

	.q-dot-no {
		background: var(--accent-red);
	}

	.q-foot-right {
		display: flex;
		align-items: center;
		gap: 12px;
	}

	.q-marks {
		display: inline-flex;
		align-items: center;
		gap: 5px;
		padding: 4px 12px;
		border-radius: 30px;
		background: var(--surface-secondary);
		border: 1px solid var(--border-light);
		color: var(--text-secondary);
		font-size: 0.75rem;
		font-weight: 500;
	}

	.q-open {
		display: inline-flex;
		align-items: center;
		gap: 5px;
		font-size: 0.8rem;
		color: var(--accent-blue);
		text-decoration: none;
		font-weight: 500;
	}

	.q-open:hover {
		text-decoration: underline;
	}

	/* ----- EMPTY / LOADING STATES ----- */
	.q-empty {
		text-align: center;
		padding: 48px 24px;
		border: 1px dashed var(--border-medium);
		border-radius: var(--radius-element);
		background: var(--surface-secondary);
	}

	.q-empty h3 {
		font-size: 1.1rem;
		font-weight: 600;
		color: var(--text-primary);
		margin-bottom: 8px;
	}

	.q-empty p {
		font-size: 0.9rem;
		color: var(--text-tertiary);
	}

	.q-loading {
		display: flex;
		align-items: center;
		justify-content: center;
		gap: 14px;
		padding: 48px;
		border-radius: var(--radius-element);
		border: 1px solid var(--border-light);
		font-size: 0.95rem;
		color: var(--text-secondary);
		font-weight: 500;
	}

	.q-spinner {
		width: 20px;
		height: 20px;
		border: 2px solid var(--border-light);
		border-top-color: var(--accent-blue);
		border-radius: 50%;
		animation: qspin 0.7s linear infinite;
	}

	@keyframes qspin {
		to {
			transform: rotate(360deg);
		}
	}

	/* ----- RESPONSIVE (mobile first enhancements) ----- */
	@media (max-width: 900px) {
		.qhero {
			flex-direction: column;
			align-items: stretch;
			padding: 24px;
		}

		.qhero-stats {
			justify-content: space-between;
		}

		.stat-pill {
			min-width: 0;
			flex: 1;
		}

		.q-stats {
			grid-template-columns: repeat(2, 1fr);
		}
	}

	@media (max-width: 680px) {
		.qp {
			gap: 16px;
		}

		.qsection-head {
			padding: 16px 20px;
		}

		.qsection-body {
			padding: 18px 20px;
		}

		.cat-toolbar {
			grid-template-columns: 1fr;
		}

		.cat-badge {
			justify-self: start;
		}

		.qhero-left h1 {
			font-size: 1.5rem;
		}

		.stat-pill {
			padding: 14px 16px;
		}

		.stat-pill-val {
			font-size: 1.5rem;
		}

		.q-card {
			padding: 16px 18px;
		}

		.q-card-top {
			flex-wrap: wrap;
		}

		.q-status {
			margin-left: auto;
		}

		.q-stats {
			gap: 8px;
		}

		.q-stat {
			padding: 12px 14px;
		}

		.q-stat-val {
			font-size: 1.5rem;
		}
	}

	@media (max-width: 480px) {
		.cat-card {
			width: 100%;
			justify-content: space-between;
		}

		.q-bet-options {
			width: 100%;
		}

		.q-bet {
			flex: 1;
			justify-content: center;
		}
	}
</style>

<div class="qp">

	<!-- Hero (dark accent) -->
	<section class="qhero">
		<div class="qhero-left">
			<h1>Questions</h1>
			<p>Select a category below to load its questions instantly. Use the search field to filter categories as you type.</p>
		</div>
		<div class="qhero-stats">
			<div class="stat-pill">
				<span class="stat-pill-label">Answered</span>
				<span class="stat-pill-val" id="heroAnsweredCount"><?php echo (int)$answered_count; ?>/<?php echo (int)$total_questions; ?></span>
				<div class="stat-prog">
					<div class="stat-prog-fill" id="statProgFill"
						style="width:<?php echo $total_questions > 0 ? round($answered_count / $total_questions * 100) : 0; ?>%">
					</div>
				</div>
			</div>
			<div class="stat-pill">
				<span class="stat-pill-label">Categories</span>
				<span class="stat-pill-val"><?php echo count($categories); ?></span>
			</div>
		</div>
	</section>

	<!-- Category Picker -->
	<section class="qsection">
		<div class="qsection-head">
			<h2>Select category</h2>
			<p>Pick a category capsule — questions switch instantly, no page reload.</p>
		</div>
		<div class="qsection-body">
			<div class="cat-toolbar">
				<div class="cat-search">
					<svg viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
						<circle cx="6.5" cy="6.5" r="4.5" stroke="currentColor" stroke-width="1.5" />
						<path d="M10 10l3 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
					</svg>
					<input type="text" id="categorySearch" placeholder="Search categories…" autocomplete="off">
				</div>
				<div class="cat-badge" id="catBadge">
					<strong><?php echo count($categories); ?></strong>&nbsp;categor<?php echo count($categories) === 1 ? 'y' : 'ies'; ?>
				</div>
			</div>

			<div class="cat-grid" id="categoryGrid">
				<?php foreach ($categories as $cat):
					$words  = preg_split('/\s+/', trim($cat->name));
					$abbrev = '';
					foreach ($words as $w) $abbrev .= strtoupper(mb_substr($w, 0, 1));
					$abbrev = mb_substr($abbrev, 0, 2);
				?>
					<button type="button"
						class="cat-card<?php echo ($selected_category && (int)$selected_category->id === (int)$cat->id) ? ' active' : ''; ?>"
						data-category-card
						data-category-id="<?php echo (int)$cat->id; ?>">
						<span class="cat-avatar"><?php echo html_escape($abbrev); ?></span>
						<span class="cat-card-name"><?php echo html_escape($cat->name); ?></span>
						<span class="cat-card-n">(<?php echo (int)$cat->question_count; ?>)</span>
					</button>
				<?php endforeach; ?>
			</div>

			<div class="cat-empty" id="categoryEmptyState">No categories match your search.</div>
		</div>
	</section>

	<!-- Question List -->
	<section class="qsection">
		<div class="qsection-head">
			<h2 id="qSectionTitle"><?php echo $selected_category ? html_escape($selected_category->name) . ' questions' : 'Choose a category'; ?></h2>
			<p id="qSectionSub"><?php echo $selected_category ? (int)$total_questions . ' question' . ($total_questions !== 1 ? 's' : '') : 'Select a category above to view its questions.'; ?></p>
		</div>
		<div class="qsection-body">
			<div id="questionContent">
				<?php echo $question_list_html; ?>
			</div>
		</div>
	</section>

</div>

<script>
	(function() {
		'use strict';

		var BASE_URL = '<?php echo site_url('questions'); ?>';
		var AJAX_URL = '<?php echo site_url('questions/category-data'); ?>';

		var grid = document.getElementById('categoryGrid');
		var qContent = document.getElementById('questionContent');
		var qTitle = document.getElementById('qSectionTitle');
		var qSub = document.getElementById('qSectionSub');
		var heroCount = document.getElementById('heroAnsweredCount');
		var progFill = document.getElementById('statProgFill');
		var catSearch = document.getElementById('categorySearch');
		var catBadge = document.getElementById('catBadge');
		var catEmpty = document.getElementById('categoryEmptyState');

		if (!grid || !qContent) return;

		var cards = Array.prototype.slice.call(grid.querySelectorAll('[data-category-card]'));

		function setActive(id) {
			cards.forEach(function(c) {
				c.classList.toggle('active', Number(c.dataset.categoryId) === Number(id));
			});
		}

		function setLoading() {
			qContent.innerHTML = '<div class="q-loading"><div class="q-spinner"></div> Loading questions…</div>';
		}

		function setError() {
			qContent.innerHTML = '<div class="q-empty"><h3>Unable to load questions</h3><p>Please select the category again.</p></div>';
		}

		function updateProgress(answered, total) {
			heroCount.textContent = answered + '/' + total;
			progFill.style.width = total > 0 ? Math.round(answered / total * 100) + '%' : '0%';
		}

		function pushHistory(id) {
			window.history.replaceState({}, '', id ? BASE_URL + '?category_id=' + id : BASE_URL);
		}

		function filterCats() {
			var term = catSearch.value.toLowerCase().trim();
			var vis = 0;
			cards.forEach(function(c) {
				var name = (c.querySelector('.cat-card-name') || {}).textContent || '';
				var matches = !term || name.toLowerCase().indexOf(term) !== -1;
				c.style.display = matches ? '' : 'none';
				if (matches) vis++;
			});
			catEmpty.style.display = vis === 0 ? 'block' : 'none';
			catBadge.innerHTML = '<strong>' + vis + '</strong>&nbsp;categor' + (vis === 1 ? 'y' : 'ies');
		}

		grid.addEventListener('click', function(e) {
			var btn = e.target.closest('[data-category-card]');
			if (!btn) return;
			var id = Number(btn.dataset.categoryId || '0');
			if (!id) return;

			var catName = (btn.querySelector('.cat-card-name') || {}).textContent || 'Questions';
			setActive(id);
			setLoading();
			qTitle.textContent = catName + ' questions';
			qSub.textContent = 'Loading…';

			fetch(AJAX_URL + '/' + id, {
					headers: {
						'X-Requested-With': 'XMLHttpRequest'
					}
				})
				.then(function(r) {
					return r.json();
				})
				.then(function(data) {
					if (!data || data.status !== 'ok') throw new Error();
					qContent.innerHTML = data.html;
					qSub.textContent = data.total_questions + ' question' + (data.total_questions !== 1 ? 's' : '');
					updateProgress(data.answered_count, data.total_questions);
					pushHistory(id);
				})
				.catch(setError);
		});

		catSearch.addEventListener('input', filterCats);
		filterCats();
	}());
</script>