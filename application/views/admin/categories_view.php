<?php if ($this->session->flashdata('error')): ?>
	<div class="c-alert c-alert--error">
		<svg width="16" height="16" viewBox="0 0 20 20" fill="none">
			<circle cx="10" cy="10" r="9" stroke="currentColor" stroke-width="2" />
			<path d="M10 6v4m0 4h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
		</svg>
		<span><?php echo $this->session->flashdata('error'); ?></span>
		<button onclick="this.parentElement.remove()">×</button>
	</div>
<?php endif; ?>
<?php if ($this->session->flashdata('success')): ?>
	<div class="c-alert c-alert--success">
		<svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
			<path d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-1-6l5-5-1-1-4 4-2-2-1 1 3 3z" />
		</svg>
		<span><?php echo $this->session->flashdata('success'); ?></span>
		<button onclick="this.parentElement.remove()">×</button>
	</div>
<?php endif; ?>

<style>
	/* ═══════════════════════════════════════════════
   TOKENS  (same as questions page)
═══════════════════════════════════════════════ */
	:root {
		--ac: #5b5ef4;
		--ac-lt: #eeeeff;
		--ac-dk: #4344d4;
		--sur: #ffffff;
		--sur2: #f7f8fc;
		--bdr: #e4e6ef;
		--t1: #12131a;
		--t2: #4b5066;
		--t3: #9499b0;
		--green: #12b76a;
		--green-bg: #edfaf3;
		--green-bd: #a3e6c7;
		--red: #e03e3e;
		--red-bg: #fff1f1;
		--red-bd: #fcc;
		--r-sm: 8px;
		--r-md: 12px;
		--r-lg: 16px;
		--sh-sm: 0 1px 3px rgba(18, 19, 26, .06), 0 1px 2px rgba(18, 19, 26, .04);
		--sh-md: 0 4px 12px rgba(18, 19, 26, .08), 0 2px 4px rgba(18, 19, 26, .04);
	}

	/* ═══════════════════════════════════════════════
   ALERTS
═══════════════════════════════════════════════ */
	.c-alert {
		display: flex;
		align-items: center;
		gap: 10px;
		padding: 13px 16px;
		border-radius: var(--r-sm);
		margin-bottom: 16px;
		font-size: 13px;
		font-weight: 600;
		animation: cSlide .3s ease;
	}

	@keyframes cSlide {
		from {
			opacity: 0;
			transform: translateY(-8px)
		}

		to {
			opacity: 1;
			transform: translateY(0)
		}
	}

	.c-alert--error {
		background: var(--red-bg);
		border: 1px solid var(--red-bd);
		color: var(--red);
	}

	.c-alert--success {
		background: var(--green-bg);
		border: 1px solid var(--green-bd);
		color: #0a6640;
	}

	.c-alert span {
		flex: 1;
	}

	.c-alert button {
		background: none;
		border: none;
		cursor: pointer;
		font-size: 18px;
		line-height: 1;
		color: inherit;
		opacity: .55;
		padding: 0 2px;
	}

	.c-alert button:hover {
		opacity: 1;
	}

	/* ═══════════════════════════════════════════════
   STATS STRIP
═══════════════════════════════════════════════ */
	.cs-stats {
		display: grid;
		grid-template-columns: repeat(3, 1fr);
		gap: 14px;
		margin-bottom: 22px;
	}

	.cs-stat {
		background: var(--sur);
		border: 1px solid var(--bdr);
		border-radius: var(--r-md);
		padding: 18px 20px;
		display: flex;
		align-items: center;
		gap: 16px;
		box-shadow: var(--sh-sm);
		transition: box-shadow .2s, border-color .2s;
	}

	.cs-stat:hover {
		box-shadow: var(--sh-md);
		border-color: #d0d2e0;
	}

	.cs-stat-icon {
		width: 46px;
		height: 46px;
		border-radius: var(--r-sm);
		display: flex;
		align-items: center;
		justify-content: center;
		flex-shrink: 0;
	}

	.cs-stat-icon--indigo {
		background: #eeeeff;
	}

	.cs-stat-icon--rose {
		background: #fff0f3;
	}

	.cs-stat-icon--teal {
		background: #e6faf4;
	}

	.cs-stat-num {
		font-size: 26px;
		font-weight: 800;
		color: var(--t1);
		line-height: 1;
		letter-spacing: -.5px;
	}

	.cs-stat-label {
		font-size: 12px;
		color: var(--t3);
		font-weight: 600;
		margin-top: 3px;
		text-transform: uppercase;
		letter-spacing: .04em;
	}

	.cs-stat-link {
		display: inline-flex;
		align-items: center;
		gap: 4px;
		font-size: 13px;
		font-weight: 600;
		color: var(--ac);
		text-decoration: none;
		margin-top: 2px;
	}

	.cs-stat-link:hover {
		text-decoration: underline;
	}

	.cs-stat-link svg {
		transition: transform .2s;
	}

	.cs-stat-link:hover svg {
		transform: translateX(3px);
	}

	/* ═══════════════════════════════════════════════
   LAYOUT
═══════════════════════════════════════════════ */
	.cs-layout {
		display: grid;
		grid-template-columns: 300px 1fr;
		gap: 20px;
		align-items: start;
	}

	/* ═══════════════════════════════════════════════
   CARD SHELL
═══════════════════════════════════════════════ */
	.cs-card {
		background: var(--sur);
		border: 1px solid var(--bdr);
		border-radius: var(--r-lg);
		box-shadow: var(--sh-sm);
		overflow: hidden;
	}

	.cs-card--sticky {
		position: sticky;
		top: 16px;
	}

	.cs-card-header {
		padding: 18px 22px 16px;
		border-bottom: 1px solid var(--bdr);
		display: flex;
		align-items: flex-start;
		gap: 12px;
	}

	.cs-card-hicon {
		width: 36px;
		height: 36px;
		background: var(--ac-lt);
		border-radius: var(--r-sm);
		display: flex;
		align-items: center;
		justify-content: center;
		flex-shrink: 0;
		margin-top: 1px;
	}

	.cs-card-htitle {
		font-size: 15px;
		font-weight: 700;
		color: var(--t1);
		margin: 0 0 3px;
		letter-spacing: -.1px;
	}

	.cs-card-hsub {
		font-size: 12.5px;
		color: var(--t3);
		margin: 0;
		line-height: 1.5;
	}

	.cs-card-body {
		padding: 20px 22px;
	}

	/* cancel link in header */
	.cs-cancel {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		margin-left: auto;
		flex-shrink: 0;
		font-size: 12.5px;
		font-weight: 700;
		color: var(--t3);
		text-decoration: none;
		padding: 6px 12px;
		border: 1px solid var(--bdr);
		border-radius: var(--r-sm);
		background: var(--sur2);
		transition: all .15s;
		white-space: nowrap;
	}

	.cs-cancel:hover {
		color: var(--red);
		border-color: var(--red-bd);
		background: var(--red-bg);
	}

	/* ═══════════════════════════════════════════════
   FORM
═══════════════════════════════════════════════ */
	.cs-field {
		margin-bottom: 16px;
	}

	.cs-label {
		display: flex;
		align-items: center;
		gap: 6px;
		font-size: 12.5px;
		font-weight: 700;
		color: var(--t2);
		margin-bottom: 7px;
		letter-spacing: .02em;
	}

	.cs-label svg {
		color: var(--t3);
	}

	.cs-input {
		width: 100%;
		padding: 10px 13px;
		border: 1.5px solid var(--bdr);
		border-radius: var(--r-sm);
		font-size: 13.5px;
		color: var(--t1);
		background: var(--sur2);
		font-family: inherit;
		transition: border-color .15s, background .15s, box-shadow .15s;
	}

	.cs-input:focus {
		outline: none;
		border-color: var(--ac);
		background: var(--sur);
		box-shadow: 0 0 0 3px rgba(91, 94, 244, .12);
	}

	.cs-input::placeholder {
		color: var(--t3);
		font-size: 13px;
	}

	/* ═══════════════════════════════════════════════
   BUTTONS
═══════════════════════════════════════════════ */
	.cs-btn {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		gap: 8px;
		padding: 11px 20px;
		border-radius: var(--r-sm);
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

	.cs-btn--primary {
		background: var(--ac);
		color: #fff;
		box-shadow: 0 2px 6px rgba(91, 94, 244, .3);
	}

	.cs-btn--primary:hover {
		background: var(--ac-dk);
		box-shadow: 0 4px 12px rgba(91, 94, 244, .38);
		transform: translateY(-1px);
	}

	.cs-btn--full {
		width: 100%;
	}

	.cs-btn--ghost {
		background: var(--sur2);
		color: var(--t2);
		border: 1.5px solid var(--bdr);
		padding: 8px 14px;
		font-size: 13px;
	}

	.cs-btn--ghost:hover {
		border-color: var(--ac);
		color: var(--ac);
		background: var(--ac-lt);
	}

	.cs-btn--danger {
		background: var(--red-bg);
		color: var(--red);
		border: 1.5px solid var(--red-bd);
		padding: 8px 14px;
		font-size: 13px;
	}

	.cs-btn--danger:hover {
		background: #ffe4e4;
		border-color: #f8a0a0;
		transform: translateY(-1px);
	}

	/* ═══════════════════════════════════════════════
   CATEGORY LIST
═══════════════════════════════════════════════ */
	.cs-list {
		display: flex;
		flex-direction: column;
		gap: 10px;
	}

	.cs-cat-row {
		display: flex;
		align-items: center;
		gap: 14px;
		padding: 14px 18px;
		border: 1.5px solid var(--bdr);
		border-radius: var(--r-md);
		background: var(--sur);
		box-shadow: var(--sh-sm);
		transition: border-color .2s, box-shadow .2s, background .2s;
	}

	.cs-cat-row:hover {
		border-color: rgba(91, 94, 244, .3);
		box-shadow: var(--sh-md);
		background: #fcfcff;
	}

	.cs-cat-avatar {
		width: 38px;
		height: 38px;
		border-radius: 10px;
		background: var(--ac-lt);
		display: flex;
		align-items: center;
		justify-content: center;
		flex-shrink: 0;
		font-size: 15px;
		font-weight: 800;
		color: var(--ac);
		text-transform: uppercase;
		letter-spacing: -.5px;
		border: 1.5px solid rgba(91, 94, 244, .15);
	}

	.cs-cat-info {
		flex: 1;
		min-width: 0;
	}

	.cs-cat-name {
		font-size: 14px;
		font-weight: 700;
		color: var(--t1);
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
		letter-spacing: -.1px;
	}

	.cs-cat-meta {
		display: inline-flex;
		align-items: center;
		gap: 5px;
		margin-top: 4px;
		font-size: 11.5px;
		font-weight: 600;
		color: #5b8fc9;
		background: #eef5ff;
		border: 1px solid #c3daf9;
		border-radius: 999px;
		padding: 2px 9px;
	}

	.cs-cat-actions {
		display: flex;
		gap: 8px;
		flex-shrink: 0;
	}

	/* ═══════════════════════════════════════════════
   EMPTY STATE
═══════════════════════════════════════════════ */
	.cs-empty {
		padding: 48px 24px;
		text-align: center;
		border: 2px dashed var(--bdr);
		border-radius: var(--r-md);
		background: var(--sur2);
	}

	.cs-empty-icon {
		width: 52px;
		height: 52px;
		border-radius: 13px;
		background: var(--ac-lt);
		display: flex;
		align-items: center;
		justify-content: center;
		margin: 0 auto 14px;
	}

	.cs-empty-title {
		font-size: 15px;
		font-weight: 800;
		color: var(--t1);
		margin: 0 0 7px;
		letter-spacing: -.2px;
	}

	.cs-empty-desc {
		font-size: 13px;
		color: var(--t3);
		max-width: 320px;
		margin: 0 auto;
		line-height: 1.65;
	}

	/* ═══════════════════════════════════════════════
   RESPONSIVE
═══════════════════════════════════════════════ */
	@media(max-width:960px) {
		.cs-layout {
			grid-template-columns: 1fr;
		}

		.cs-card--sticky {
			position: static;
		}
	}

	@media(max-width:640px) {
		.cs-stats {
			grid-template-columns: 1fr 1fr;
		}

		.cs-stats .cs-stat:last-child {
			grid-column: span 2;
		}

		.cs-card-body,
		.cs-card-header {
			padding: 16px;
		}

		.cs-cat-row {
			flex-wrap: wrap;
		}

		.cs-cat-actions {
			width: 100%;
		}

		.cs-btn--ghost,
		.cs-btn--danger {
			flex: 1;
			justify-content: center;
		}
	}

	@media(max-width:420px) {
		.cs-stats {
			grid-template-columns: 1fr;
		}

		.cs-stats .cs-stat:last-child {
			grid-column: span 1;
		}
	}
</style>

<!-- ─── STATS ─────────────────────────────────────── -->
<div class="cs-stats">
	<div class="cs-stat">
		<div class="cs-stat-icon cs-stat-icon--indigo">
			<svg width="20" height="20" viewBox="0 0 24 24" fill="none">
				<path d="M4 6h16M4 10h16M4 14h10M4 18h6" stroke="#5b5ef4" stroke-width="2" stroke-linecap="round" />
			</svg>
		</div>
		<div>
			<div class="cs-stat-num"><?php echo (int)$total_categories; ?></div>
			<div class="cs-stat-label">Categories</div>
		</div>
	</div>

	<div class="cs-stat">
		<div class="cs-stat-icon cs-stat-icon--rose">
			<svg width="20" height="20" viewBox="0 0 24 24" fill="none">
				<path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2" stroke="#e05f8e" stroke-width="2" stroke-linecap="round" />
			</svg>
		</div>
		<div>
			<div class="cs-stat-num"><?php echo (int)$total_questions; ?></div>
			<div class="cs-stat-label">Total Questions</div>
		</div>
	</div>

	<div class="cs-stat">
		<div class="cs-stat-icon cs-stat-icon--teal">
			<svg width="20" height="20" viewBox="0 0 24 24" fill="none">
				<path d="M13 10V3L4 14h7v7l9-11h-7z" stroke="#0d9488" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
			</svg>
		</div>
		<div>
			<div class="cs-stat-label" style="margin-bottom:4px;">Quick Access</div>
			<a class="cs-stat-link" href="<?php echo site_url('admin/questions'); ?>">
				Manage Questions
				<svg width="13" height="13" viewBox="0 0 24 24" fill="none">
					<path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
				</svg>
			</a>
		</div>
	</div>
</div>

<!-- ─── LAYOUT ────────────────────────────────────── -->
<div class="cs-layout">

	<!-- LEFT: Add / Edit Form -->
	<aside class="cs-card cs-card--sticky">
		<div class="cs-card-header">
			<div class="cs-card-hicon">
				<?php if ($edit_category): ?>
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none">
						<path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" stroke="#5b5ef4" stroke-width="2" stroke-linecap="round" />
						<path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" stroke="#5b5ef4" stroke-width="2" stroke-linecap="round" />
					</svg>
				<?php else: ?>
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none">
						<circle cx="12" cy="12" r="9" stroke="#5b5ef4" stroke-width="2" />
						<path d="M12 8v8M8 12h8" stroke="#5b5ef4" stroke-width="2" stroke-linecap="round" />
					</svg>
				<?php endif; ?>
			</div>
			<div style="flex:1">
				<h3 class="cs-card-htitle"><?php echo $edit_category ? 'Edit Category' : 'Add Category'; ?></h3>
				<p class="cs-card-hsub"><?php echo $edit_category ? 'Update the category name below.' : 'Create a new category for questions.'; ?></p>
			</div>
			<?php if ($edit_category): ?>
				<a class="cs-cancel" href="<?php echo site_url('admin/categories'); ?>">
					<svg width="12" height="12" viewBox="0 0 24 24" fill="none">
						<path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
					</svg>
					Cancel
				</a>
			<?php endif; ?>
		</div>

		<div class="cs-card-body">
			<form method="post" action="<?php echo $edit_category ? site_url('admin/categories/update/' . $edit_category->id) : site_url('admin/categories/create'); ?>">
				<div class="cs-field">
					<label class="cs-label" for="category_name">
						<svg width="13" height="13" viewBox="0 0 24 24" fill="none">
							<path d="M4 6h16M4 10h16M4 14h10M4 18h6" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
						</svg>
						Category Name <span style="color:var(--red);margin-left:2px;">*</span>
					</label>
					<input
						type="text"
						class="cs-input"
						id="category_name"
						name="name"
						value="<?php echo set_value('name', $edit_category ? $edit_category->name : ''); ?>"
						placeholder="e.g. Cricket, Science, Politics…"
						required
						autocomplete="off">
				</div>

				<button class="cs-btn cs-btn--primary cs-btn--full" type="submit">
					<?php if ($edit_category): ?>
						<svg width="15" height="15" viewBox="0 0 24 24" fill="none">
							<path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" stroke="currentColor" stroke-width="2" />
							<polyline points="17 21 17 13 7 13 7 21" stroke="currentColor" stroke-width="2" />
							<polyline points="7 3 7 8 15 8" stroke="currentColor" stroke-width="2" />
						</svg>
						Save Changes
					<?php else: ?>
						<svg width="15" height="15" viewBox="0 0 24 24" fill="none">
							<circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2" />
							<path d="M12 8v8M8 12h8" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
						</svg>
						Create Category
					<?php endif; ?>
				</button>
			</form>
		</div>
	</aside>

	<!-- RIGHT: Category List -->
	<section class="cs-card">
		<div class="cs-card-header">
			<div class="cs-card-hicon">
				<svg width="18" height="18" viewBox="0 0 24 24" fill="none">
					<path d="M4 6h16M4 10h16M4 14h10M4 18h6" stroke="#5b5ef4" stroke-width="2" stroke-linecap="round" />
				</svg>
			</div>
			<div>
				<h3 class="cs-card-htitle">All Categories</h3>
				<p class="cs-card-hsub">View, edit or delete your question categories.</p>
			</div>
		</div>

		<div class="cs-card-body">
			<?php if (!empty($categories)): ?>
				<div class="cs-list">
					<?php foreach ($categories as $cat): ?>
						<div class="cs-cat-row">
							<!-- Avatar: first letter of name -->
							<div class="cs-cat-avatar"><?php echo mb_strtoupper(mb_substr(html_escape($cat->name), 0, 2)); ?></div>

							<div class="cs-cat-info">
								<div class="cs-cat-name"><?php echo html_escape($cat->name); ?></div>
								<span class="cs-cat-meta">
									<svg width="11" height="11" viewBox="0 0 24 24" fill="none">
										<path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
									</svg>
									<?php $qc = count($cat->questions);
									echo $qc . ' question' . ($qc !== 1 ? 's' : ''); ?>
								</span>
							</div>

							<div class="cs-cat-actions">
								<a class="cs-btn cs-btn--ghost" href="<?php echo site_url('admin/categories?edit_category=' . (int)$cat->id); ?>">
									<svg width="13" height="13" viewBox="0 0 24 24" fill="none">
										<path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
										<path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
									</svg>
									Edit
								</a>
								<a class="cs-btn cs-btn--danger" href="<?php echo site_url('admin/categories/delete/' . (int)$cat->id); ?>"
									onclick="return confirm('Delete &quot;<?php echo addslashes(html_escape($cat->name)); ?>&quot; and all its questions?\nThis cannot be undone.');">
									<svg width="13" height="13" viewBox="0 0 24 24" fill="none">
										<polyline points="3 6 5 6 21 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
										<path d="M19 6l-1 14H6L5 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
										<path d="M10 11v6M14 11v6" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
										<path d="M9 6V4h6v2" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
									</svg>
									Delete
								</a>
							</div>
						</div>
					<?php endforeach; ?>
				</div>

			<?php else: ?>
				<div class="cs-empty">
					<div class="cs-empty-icon">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none">
							<path d="M4 6h16M4 10h16M4 14h10M4 18h6" stroke="#5b5ef4" stroke-width="2" stroke-linecap="round" />
						</svg>
					</div>
					<div class="cs-empty-title">No categories yet</div>
					<p class="cs-empty-desc">Use the form on the left to create your first category.</p>
				</div>
			<?php endif; ?>
		</div>
	</section>

</div>

<script>
	(function() {
		// Alert auto-dismiss
		document.querySelectorAll('.c-alert').forEach(function(el) {
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
					btn.style.opacity = '.6';
					setTimeout(function() {
						btn.disabled = false;
						btn.style.opacity = '1';
					}, 4000);
				}
			});
		});
	}());
</script>