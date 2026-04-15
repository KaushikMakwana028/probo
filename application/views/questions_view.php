<?php if ($this->session->flashdata('error')): ?>
	<div class="qflash qflash-err"><?php echo $this->session->flashdata('error'); ?></div>
<?php endif; ?>
<?php if ($this->session->flashdata('success')): ?>
	<div class="qflash qflash-ok"><?php echo $this->session->flashdata('success'); ?></div>
<?php endif; ?>

<?php $total_questions = $selected_category && !empty($selected_category->questions) ? count($selected_category->questions) : 0; ?>

<style>
	*,
	*::before,
	*::after {
		box-sizing: border-box;
		margin: 0;
		padding: 0;
	}

	/* ── flash ── */
	.qflash {
		padding: 12px 16px;
		border-radius: 12px;
		margin-bottom: 18px;
		font-size: 14px;
		font-weight: 500;
	}

	.qflash-err {
		background: rgba(239, 68, 68, .08);
		border: 0.5px solid rgba(239, 68, 68, .3);
		color: #dc2626;
	}

	.qflash-ok {
		background: rgba(34, 197, 94, .08);
		border: 0.5px solid rgba(34, 197, 94, .3);
		color: #16a34a;
	}

	/* ── page wrapper ── */
	.qp {
		display: grid;
		gap: 20px;
	}

	/* ── hero ── */
	.qhero {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 20px;
		padding: 28px 32px;
		border-radius: 20px;
		background: var(--color-background-secondary, #f8fafc);
		border: 0.5px solid var(--color-border-secondary, #e2e8f0);
	}

	.qhero-left h1 {
		font-size: 22px;
		font-weight: 500;
		color: var(--color-text-primary, #0f172a);
		margin-bottom: 6px;
	}

	.qhero-left p {
		font-size: 14px;
		color: var(--color-text-secondary, #64748b);
		line-height: 1.65;
		max-width: 480px;
	}

	.qhero-stats {
		display: flex;
		gap: 12px;
		flex-shrink: 0;
	}

	.stat-pill {
		min-width: 120px;
		padding: 14px 18px;
		border-radius: 16px;
		text-align: center;
		background: var(--color-background-primary, #fff);
		border: 0.5px solid var(--color-border-tertiary, #f1f5f9);
	}

	.stat-pill-label {
		display: block;
		font-size: 11px;
		text-transform: uppercase;
		letter-spacing: .09em;
		color: var(--color-text-tertiary, #94a3b8);
		margin-bottom: 6px;
		font-weight: 500;
	}

	.stat-pill-val {
		font-size: 22px;
		font-weight: 500;
		color: var(--color-text-primary, #0f172a);
	}

	.stat-prog {
		margin-top: 8px;
		height: 4px;
		background: var(--color-border-tertiary, #e2e8f0);
		border-radius: 999px;
		overflow: hidden;
	}

	.stat-prog-fill {
		height: 100%;
		background: #3b82f6;
		border-radius: 999px;
		transition: width .5s cubic-bezier(.4, 0, .2, 1);
	}

	/* ── section shell ── */
	.qsection {
		border-radius: 16px;
		overflow: hidden;
		border: 0.5px solid var(--color-border-tertiary, #e2e8f0);
	}

	.qsection-head {
		padding: 16px 24px;
		background: var(--color-background-secondary, #f8fafc);
		border-bottom: 0.5px solid var(--color-border-tertiary, #e2e8f0);
	}

	.qsection-head h2 {
		font-size: 15px;
		font-weight: 500;
		color: var(--color-text-primary, #0f172a);
		margin-bottom: 2px;
	}

	.qsection-head p {
		font-size: 13px;
		color: var(--color-text-tertiary, #94a3b8);
	}

	.qsection-body {
		background: var(--color-background-primary, #fff);
		padding: 22px 24px;
	}

	/* ── toolbar ── */
	.cat-toolbar {
		display: grid;
		grid-template-columns: 1fr auto;
		gap: 10px;
		align-items: center;
		margin-bottom: 18px;
	}

	.cat-search {
		position: relative;
	}

	.cat-search svg {
		position: absolute;
		left: 12px;
		top: 50%;
		transform: translateY(-50%);
		width: 14px;
		height: 14px;
		color: var(--color-text-tertiary, #94a3b8);
		pointer-events: none;
	}

	.cat-search input {
		width: 100%;
		padding: 9px 14px 9px 36px;
		border-radius: 10px;
		font-family: inherit;
		font-size: 14px;
		border: 0.5px solid var(--color-border-secondary, #e2e8f0);
		background: var(--color-background-secondary, #f8fafc);
		color: var(--color-text-primary, #0f172a);
		outline: none;
		transition: border-color .15s, box-shadow .15s;
	}

	.cat-search input:focus {
		border-color: #3b82f6;
		box-shadow: 0 0 0 3px rgba(59, 130, 246, .12);
	}

	.cat-badge {
		padding: 9px 14px;
		border-radius: 10px;
		white-space: nowrap;
		font-size: 12px;
		font-weight: 500;
		background: var(--color-background-secondary, #f8fafc);
		border: 0.5px solid var(--color-border-tertiary, #e2e8f0);
		color: var(--color-text-secondary, #64748b);
	}

	.cat-badge strong {
		color: #3b82f6;
	}

	/* ── category capsules ── */
	.cat-grid {
		display: flex;
		flex-wrap: wrap;
		gap: 10px;
	}

	.cat-card {
		display: flex;
		align-items: center;
		gap: 9px;
		padding: 8px 14px 8px 8px;
		border-radius: 999px;
		border: 0.5px solid var(--color-border-secondary, #e2e8f0);
		background: var(--color-background-secondary, #f8fafc);
		cursor: pointer;
		font-family: inherit;
		font-size: 13px;
		font-weight: 500;
		color: var(--color-text-secondary, #64748b);
		transition: all .18s ease;
	}

	.cat-card:hover {
		border-color: #93c5fd;
		background: rgba(59, 130, 246, .07);
		color: #1d4ed8;
		transform: translateY(-1px);
	}

	.cat-card.active {
		border-color: #2563eb;
		background: #2563eb;
		color: #fff;
	}

	.cat-avatar {
		width: 28px;
		height: 28px;
		border-radius: 50%;
		background: var(--color-border-tertiary, #e2e8f0);
		display: inline-flex;
		align-items: center;
		justify-content: center;
		font-size: 10px;
		font-weight: 500;
		color: var(--color-text-secondary, #64748b);
		flex-shrink: 0;
	}

	.cat-card.active .cat-avatar {
		background: rgba(255, 255, 255, .2);
		color: #fff;
	}

	.cat-card-n {
		font-size: 12px;
		color: var(--color-text-tertiary, #94a3b8);
	}

	.cat-card.active .cat-card-n {
		color: rgba(255, 255, 255, .6);
	}

	.cat-empty {
		display: none;
		padding: 16px;
		border: 0.5px dashed var(--color-border-secondary, #e2e8f0);
		border-radius: 12px;
		text-align: center;
		font-size: 13px;
		color: var(--color-text-tertiary, #94a3b8);
		margin-top: 12px;
	}

	/* ── stats bar ── */
	.q-stats {
		display: grid;
		grid-template-columns: repeat(4, 1fr);
		gap: 10px;
		margin-bottom: 20px;
	}

	.q-stat {
		padding: 14px 16px;
		border-radius: 12px;
		background: var(--color-background-secondary, #f8fafc);
		border: 0.5px solid var(--color-border-tertiary, #e2e8f0);
	}

	.q-stat-label {
		display: block;
		font-size: 10px;
		text-transform: uppercase;
		letter-spacing: .1em;
		color: var(--color-text-tertiary, #94a3b8);
		margin-bottom: 6px;
		font-weight: 500;
	}

	.q-stat-val {
		font-size: 22px;
		font-weight: 500;
		color: var(--color-text-primary, #0f172a);
	}

	.q-stat.stat-correct .q-stat-val {
		color: #22c55e;
	}

	.q-stat.stat-wrong .q-stat-val {
		color: #ef4444;
	}

	.q-stat.stat-pending .q-stat-val {
		color: #3b82f6;
	}

	/* ── question cards ── */
	.q-list {
		display: flex;
		flex-direction: column;
		gap: 10px;
	}

	.question-list-shell {
		display: grid;
		gap: 20px;
	}

	.question-list-head h3 {
		font-size: 16px;
		font-weight: 500;
		color: var(--color-text-primary, #0f172a);
		margin-bottom: 6px;
	}

	.question-list-head p {
		font-size: 13px;
		line-height: 1.65;
		color: var(--color-text-secondary, #64748b);
	}

	.q-card {
		padding: 18px 20px;
		border-radius: 14px;
		display: flex;
		flex-direction: column;
		gap: 12px;
		border: 0.5px solid var(--color-border-secondary, #e2e8f0);
		background: var(--color-background-primary, #fff);
		transition: border-color .15s, box-shadow .15s, transform .15s;
		text-decoration: none;
		color: inherit;
	}

	.q-card:hover {
		border-color: var(--color-border-primary, #cbd5e1);
		box-shadow: 0 4px 12px rgba(15, 23, 42, .07);
		transform: translateY(-1px);
	}

	.q-card.correct {
		background: rgba(34, 197, 94, .04);
		border-color: rgba(34, 197, 94, .3);
	}

	.q-card.wrong {
		background: rgba(239, 68, 68, .04);
		border-color: rgba(239, 68, 68, .3);
	}

	.q-card.pending {
		background: rgba(59, 130, 246, .03);
		border-color: rgba(59, 130, 246, .2);
	}

	.q-card-top {
		display: flex;
		align-items: flex-start;
		justify-content: space-between;
		gap: 12px;
	}

	.q-card-left {
		display: flex;
		align-items: flex-start;
		gap: 12px;
		flex: 1;
		min-width: 0;
	}

	.q-idx {
		width: 34px;
		height: 34px;
		border-radius: 10px;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		font-size: 13px;
		font-weight: 500;
		flex-shrink: 0;
		background: var(--color-background-secondary, #f1f5f9);
		color: var(--color-text-secondary, #64748b);
	}

	.q-card.correct .q-idx {
		background: rgba(34, 197, 94, .15);
		color: #16a34a;
	}

	.q-card.wrong .q-idx {
		background: rgba(239, 68, 68, .15);
		color: #dc2626;
	}

	.q-card.pending .q-idx {
		background: rgba(59, 130, 246, .15);
		color: #2563eb;
	}

	.q-card h4 {
		font-size: 15px;
		font-weight: 500;
		color: var(--color-text-primary, #0f172a);
		line-height: 1.55;
	}

	.q-status {
		padding: 4px 10px;
		border-radius: 999px;
		font-size: 10px;
		font-weight: 500;
		text-transform: uppercase;
		letter-spacing: .08em;
		white-space: nowrap;
		flex-shrink: 0;
	}

	.q-status.pending {
		background: rgba(59, 130, 246, .1);
		color: #2563eb;
		border: 0.5px solid rgba(59, 130, 246, .3);
	}

	.q-status.correct {
		background: rgba(34, 197, 94, .1);
		color: #16a34a;
		border: 0.5px solid rgba(34, 197, 94, .3);
	}

	.q-status.wrong {
		background: rgba(239, 68, 68, .1);
		color: #dc2626;
		border: 0.5px solid rgba(239, 68, 68, .3);
	}

	.q-card-foot {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 12px;
		font-size: 12px;
		color: var(--color-text-tertiary, #94a3b8);
		border-top: 0.5px solid var(--color-border-tertiary, #f1f5f9);
		padding-top: 12px;
	}

	.q-card.correct .q-card-foot {
		border-top-color: rgba(34, 197, 94, .2);
	}

	.q-card.wrong .q-card-foot {
		border-top-color: rgba(239, 68, 68, .2);
	}

	.q-card.pending .q-card-foot {
		border-top-color: rgba(59, 130, 246, .15);
	}

	.q-bet-options {
		display: flex;
		gap: 8px;
		flex-wrap: wrap;
	}

	.q-bet {
		display: inline-flex;
		align-items: center;
		gap: 5px;
		padding: 5px 12px;
		border-radius: 8px;
		background: var(--color-background-secondary, #f8fafc);
		border: 0.5px solid var(--color-border-secondary, #e2e8f0);
		font-family: inherit;
		font-size: 12px;
		font-weight: 500;
		color: var(--color-text-secondary, #64748b);
		cursor: pointer;
		text-decoration: none;
		transition: all .15s;
	}

	.q-bet-yes:hover {
		border-color: #22c55e;
		color: #16a34a;
		background: rgba(34, 197, 94, .06);
	}

	.q-bet-no:hover {
		border-color: #ef4444;
		color: #dc2626;
		background: rgba(239, 68, 68, .06);
	}

	.q-dot {
		width: 6px;
		height: 6px;
		border-radius: 50%;
		display: inline-block;
		flex-shrink: 0;
	}

	.q-dot-yes {
		background: #22c55e;
	}

	.q-dot-no {
		background: #ef4444;
	}

	.q-foot-right {
		display: flex;
		align-items: center;
		gap: 10px;
	}

	.q-marks {
		display: inline-flex;
		align-items: center;
		gap: 5px;
		padding: 3px 10px;
		border-radius: 999px;
		background: var(--color-background-secondary, #f8fafc);
		border: 0.5px solid var(--color-border-tertiary, #e2e8f0);
		color: var(--color-text-secondary, #64748b);
		font-size: 11px;
		font-weight: 500;
	}

	.q-open {
		display: inline-flex;
		align-items: center;
		gap: 4px;
		font-size: 12px;
		color: #3b82f6;
		text-decoration: none;
		font-weight: 500;
	}

	.q-open:hover {
		text-decoration: underline;
	}

	/* ── empty / loading ── */
	.q-empty {
		text-align: center;
		padding: 48px 20px;
		border: 0.5px dashed var(--color-border-secondary, #e2e8f0);
		border-radius: 14px;
		background: var(--color-background-secondary, #f8fafc);
	}

	.q-empty h3 {
		font-size: 16px;
		font-weight: 500;
		color: var(--color-text-primary, #0f172a);
		margin-bottom: 6px;
	}

	.q-empty p {
		font-size: 13px;
		color: var(--color-text-tertiary, #94a3b8);
	}

	.q-loading {
		display: flex;
		align-items: center;
		justify-content: center;
		gap: 12px;
		padding: 44px;
		border-radius: 14px;
		border: 0.5px solid var(--color-border-tertiary, #e2e8f0);
		font-size: 14px;
		color: var(--color-text-secondary, #64748b);
		font-weight: 500;
	}

	.q-spinner {
		width: 18px;
		height: 18px;
		border: 2px solid var(--color-border-secondary, #e2e8f0);
		border-top-color: #3b82f6;
		border-radius: 50%;
		animation: qspin .6s linear infinite;
		flex-shrink: 0;
	}

	@keyframes qspin {
		to {
			transform: rotate(360deg);
		}
	}

	/* ── responsive ── */
	@media (max-width: 900px) {
		.qhero {
			flex-direction: column;
			align-items: flex-start;
		}

		.q-stats {
			grid-template-columns: repeat(2, 1fr);
		}
	}

	@media (max-width: 680px) {
		.qhero {
			padding: 20px;
		}

		.qsection-body {
			padding: 18px;
		}

		.cat-toolbar {
			grid-template-columns: 1fr;
		}
	}
</style>

<div class="qp">

	<!-- ── Hero ── -->
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

	<!-- ── Category picker ── -->
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

	<!-- ── Question list ── -->
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
