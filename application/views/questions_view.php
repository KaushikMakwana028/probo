<?php if ($this->session->flashdata('error')): ?>
	<div class="qf qf-err"><i class="fa-solid fa-circle-exclamation"></i> <?php echo $this->session->flashdata('error'); ?></div>
<?php endif; ?>
<?php if ($this->session->flashdata('success')): ?>
	<div class="qf qf-ok"><i class="fa-solid fa-circle-check"></i> <?php echo $this->session->flashdata('success'); ?></div>
<?php endif; ?>

<?php $total_questions = $selected_category && !empty($selected_category->questions) ? count($selected_category->questions) : 0; ?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

<style>
	*,
	*::before,
	*::after {
		box-sizing: border-box;
		margin: 0;
		padding: 0;
	}

	:root {
		--p-bg: #eef1f8;
		--p-surface: #ffffff;
		--p-surface2: #f5f7fc;
		--p-ink: #0d1321;
		--p-text: #3d4a63;
		--p-muted: #7a8499;
		--p-line: #dde3f0;
		--p-blue: #2f5be8;
		--p-blue-bg: #eef2fd;
		--p-blue-border: #c5d0f9;
		--p-green: #0fa966;
		--p-green-bg: #e8f9f1;
		--p-red: #dc3545;
		--p-red-bg: #fdeef0;
		--p-amber: #c97c10;
		--p-amber-bg: #fef5e4;
		--p-shadow: 0 1px 3px rgba(15, 23, 60, 0.06), 0 4px 16px rgba(15, 23, 60, 0.04);
		--p-shadow-md: 0 4px 24px rgba(15, 23, 60, 0.09), 0 1px 4px rgba(15, 23, 60, 0.04);
		--p-r: 20px;
		--p-r-sm: 12px;
		--p-r-pill: 999px;
		--p-transition: all 0.2s ease;
	}

	/* Flash */
	.qf {
		display: flex;
		align-items: center;
		gap: 10px;
		padding: 13px 18px;
		border-radius: 14px;
		margin-bottom: 20px;
		font-family: 'Roboto', sans-serif;
		font-size: 14px;
		font-weight: 500;
	}

	.qf-err {
		background: var(--p-red-bg);
		border: 1px solid rgba(220, 53, 69, 0.25);
		color: #a91f2a;
	}

	.qf-ok {
		background: var(--p-green-bg);
		border: 1px solid rgba(15, 169, 102, 0.25);
		color: #0a7047;
	}

	/* Page wrapper */
	.qp {
		font-family: 'Roboto', sans-serif;
		display: flex;
		flex-direction: column;
		gap: 20px;
		max-width: 1360px;
		margin: 0 auto;
		padding: 0 0 40px;
	}

	/* ── HERO ── */
	.qp-hero {
		border-radius: var(--p-r);
		padding: 0;
		overflow: hidden;
		background: var(--p-ink);
		box-shadow: var(--p-shadow-md);
		position: relative;
	}

	.qp-hero-inner {
		display: flex;
		align-items: stretch;
		gap: 0;
	}

	.qp-hero-left {
		flex: 1;
		padding: 36px 40px;
		background: linear-gradient(125deg, #1a2744 0%, #0d1321 100%);
		display: flex;
		flex-direction: column;
		justify-content: center;
		gap: 10px;
		position: relative;
	}

	.qp-hero-left::after {
		content: '';
		position: absolute;
		right: -1px;
		top: 0;
		bottom: 0;
		width: 1px;
		background: rgba(255, 255, 255, 0.08);
	}

	.qp-hero-eyebrow {
		display: inline-flex;
		align-items: center;
		gap: 7px;
		font-size: 11px;
		font-weight: 600;
		letter-spacing: 0.1em;
		text-transform: uppercase;
		color: rgba(255, 255, 255, 0.5);
	}

	.qp-hero-title {
		font-size: clamp(26px, 3vw, 38px);
		font-weight: 700;
		color: #ffffff;
		letter-spacing: -0.03em;
		line-height: 1.1;
	}

	.qp-hero-desc {
		font-size: 14px;
		color: rgba(255, 255, 255, 0.55);
		line-height: 1.7;
		max-width: 420px;
		font-weight: 300;
	}

	.qp-hero-actions {
		display: flex;
		align-items: center;
		gap: 12px;
		flex-wrap: wrap;
		margin-top: 10px;
	}

	.qp-guide-btn {
		display: inline-flex;
		align-items: center;
		gap: 8px;
		padding: 11px 18px;
		border-radius: var(--p-r-pill);
		border: 1px solid rgba(255, 255, 255, 0.16);
		background: rgba(255, 255, 255, 0.08);
		color: #fff;
		font-size: 13px;
		font-weight: 700;
		letter-spacing: -0.01em;
		cursor: pointer;
		transition: var(--p-transition);
		box-shadow: 0 8px 24px rgba(13, 19, 33, 0.18);
	}

	.qp-guide-btn:hover {
		transform: translateY(-1px);
		background: rgba(255, 255, 255, 0.14);
		border-color: rgba(255, 255, 255, 0.28);
	}

	.qp-hero-right {
		display: flex;
		flex-direction: row;
		align-items: stretch;
	}

	.qp-hero-stat {
		width: 150px;
		padding: 28px 24px;
		display: flex;
		flex-direction: column;
		justify-content: center;
		background: rgba(255, 255, 255, 0.035);
		border-left: 1px solid rgba(255, 255, 255, 0.07);
		transition: background 0.2s;
	}

	.qp-hero-stat:hover {
		background: rgba(255, 255, 255, 0.06);
	}

	.qp-hero-stat-label {
		font-size: 10px;
		font-weight: 600;
		text-transform: uppercase;
		letter-spacing: 0.1em;
		color: rgba(255, 255, 255, 0.4);
		margin-bottom: 8px;
	}

	.qp-hero-stat-val {
		font-size: 32px;
		font-weight: 700;
		color: #ffffff;
		letter-spacing: -0.04em;
		line-height: 1;
		margin-bottom: 12px;
	}

	.qp-hero-stat-val span {
		font-size: 18px;
		font-weight: 400;
		color: rgba(255, 255, 255, 0.4);
	}

	.qp-prog-track {
		height: 4px;
		background: rgba(255, 255, 255, 0.12);
		border-radius: 4px;
		overflow: hidden;
	}

	.qp-prog-fill {
		height: 100%;
		background: var(--p-blue);
		border-radius: 4px;
		transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
	}

	/* ── SECTION CARD ── */
	.qp-section {
		background: var(--p-surface);
		border: 1px solid var(--p-line);
		border-radius: var(--p-r);
		overflow: hidden;
		box-shadow: var(--p-shadow);
	}

	.qp-section-head {
		padding: 20px 28px;
		border-bottom: 1px solid var(--p-line);
		background: var(--p-surface2);
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 12px;
		flex-wrap: wrap;
	}

	.qp-section-head-left h2 {
		font-size: 16px;
		font-weight: 700;
		color: var(--p-ink);
		letter-spacing: -0.01em;
		margin-bottom: 2px;
	}

	.qp-section-head-left p {
		font-size: 13px;
		color: var(--p-muted);
	}

	.qp-count-tag {
		display: inline-flex;
		align-items: center;
		gap: 5px;
		padding: 6px 14px;
		border-radius: var(--p-r-pill);
		background: var(--p-blue-bg);
		border: 1px solid var(--p-blue-border);
		font-size: 12px;
		font-weight: 600;
		color: var(--p-blue);
		white-space: nowrap;
	}

	.qp-section-body {
		padding: 24px 28px;
	}

	/* ── CATEGORY TOOLBAR ── */
	.qp-toolbar {
		display: flex;
		align-items: center;
		gap: 12px;
		margin-bottom: 20px;
	}

	.qp-search-wrap {
		position: relative;
		flex: 1;
	}

	.qp-search-wrap svg {
		position: absolute;
		left: 14px;
		top: 50%;
		transform: translateY(-50%);
		width: 15px;
		height: 15px;
		color: var(--p-muted);
		pointer-events: none;
	}

	.qp-search-wrap input {
		width: 100%;
		height: 42px;
		padding: 0 16px 0 42px;
		border-radius: var(--p-r-pill);
		border: 1.5px solid var(--p-line);
		background: var(--p-surface);
		font-family: 'Roboto', sans-serif;
		font-size: 14px;
		color: var(--p-ink);
		outline: none;
		transition: var(--p-transition);
	}

	.qp-search-wrap input::placeholder {
		color: var(--p-muted);
	}

	.qp-search-wrap input:focus {
		border-color: var(--p-blue);
		box-shadow: 0 0 0 3px rgba(47, 91, 232, 0.1);
	}

	.qp-cat-counter {
		height: 42px;
		padding: 0 18px;
		border-radius: var(--p-r-pill);
		background: var(--p-surface2);
		border: 1px solid var(--p-line);
		display: flex;
		align-items: center;
		font-size: 13px;
		font-weight: 500;
		color: var(--p-text);
		white-space: nowrap;
		flex-shrink: 0;
	}

	.qp-cat-counter strong {
		color: var(--p-blue);
		font-weight: 700;
		margin-right: 3px;
	}

	/* ── CATEGORY PILLS GRID ── */
	.qp-cat-grid {
		display: flex;
		flex-wrap: wrap;
		gap: 10px;
	}

	.qp-cat-pill {
		display: inline-flex;
		align-items: center;
		gap: 9px;
		padding: 9px 16px 9px 9px;
		border-radius: var(--p-r-pill);
		border: 1.5px solid var(--p-line);
		background: var(--p-surface);
		cursor: pointer;
		font-family: 'Roboto', sans-serif;
		font-size: 14px;
		font-weight: 500;
		color: var(--p-text);
		transition: var(--p-transition);
		box-shadow: var(--p-shadow);
		outline: none;
	}

	.qp-cat-pill:hover {
		border-color: var(--p-blue-border);
		background: var(--p-blue-bg);
		color: var(--p-blue);
		transform: translateY(-1px);
		box-shadow: var(--p-shadow-md);
	}

	.qp-cat-pill.active {
		border-color: var(--p-blue);
		background: var(--p-blue);
		color: #ffffff;
		box-shadow: 0 4px 14px rgba(47, 91, 232, 0.25);
	}

	.qp-cat-avatar {
		width: 30px;
		height: 30px;
		border-radius: 50%;
		background: var(--p-line);
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 11px;
		font-weight: 700;
		color: var(--p-text);
		flex-shrink: 0;
		transition: var(--p-transition);
	}

	.qp-cat-pill:hover .qp-cat-avatar {
		background: rgba(47, 91, 232, 0.15);
		color: var(--p-blue);
	}

	.qp-cat-pill.active .qp-cat-avatar {
		background: rgba(255, 255, 255, 0.2);
		color: #ffffff;
	}

	.qp-cat-count {
		font-size: 12px;
		opacity: 0.65;
	}

	.qp-cat-empty {
		display: none;
		padding: 20px;
		text-align: center;
		font-size: 13px;
		color: var(--p-muted);
		border: 1px dashed var(--p-line);
		border-radius: var(--p-r-sm);
		margin-top: 12px;
	}

	/* ── QUESTION SECTION HEAD ── */
	.qp-q-head {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 14px;
		flex-wrap: wrap;
		margin-bottom: 20px;
	}

	.qp-q-title {
		font-size: 16px;
		font-weight: 700;
		color: var(--p-ink);
		letter-spacing: -0.01em;
	}

	.qp-q-sub {
		font-size: 13px;
		color: var(--p-muted);
		margin-top: 2px;
	}

	/* Loading / error states */
	.qp-loading {
		display: flex;
		align-items: center;
		justify-content: center;
		gap: 14px;
		padding: 56px 24px;
		border: 1px solid var(--p-line);
		border-radius: var(--p-r-sm);
		background: var(--p-surface2);
		font-size: 14px;
		color: var(--p-text);
		font-weight: 500;
	}

	.qp-spinner {
		width: 20px;
		height: 20px;
		border: 2.5px solid var(--p-line);
		border-top-color: var(--p-blue);
		border-radius: 50%;
		animation: qp-spin 0.65s linear infinite;
		flex-shrink: 0;
	}

	@keyframes qp-spin {
		to {
			transform: rotate(360deg);
		}
	}

	.qp-guide-overlay {
		position: fixed;
		inset: 0;
		background: rgba(13, 19, 33, 0.56);
		backdrop-filter: blur(8px);
		-webkit-backdrop-filter: blur(8px);
		display: none;
		align-items: center;
		justify-content: center;
		padding: 22px;
		z-index: 1200;
	}

	.qp-guide-overlay.is-open {
		display: flex;
	}

	.qp-guide-modal {
		width: min(1040px, 100%);
		max-height: 88vh;
		background: var(--p-surface);
		border: 1px solid var(--p-line);
		border-radius: 26px;
		box-shadow: 0 28px 90px rgba(15, 23, 60, 0.24);
		overflow: hidden;
		display: flex;
		flex-direction: column;
	}

	.qp-guide-head {
		display: flex;
		align-items: flex-start;
		justify-content: space-between;
		gap: 18px;
		padding: 24px 28px 20px;
		background:
			radial-gradient(circle at top left, rgba(56, 189, 248, 0.16), transparent 26%),
			linear-gradient(135deg, #f7fbff 0%, #eef8ff 52%, #eef2ff 100%);
		border-bottom: 1px solid var(--p-line);
	}

	.qp-guide-eyebrow {
		display: inline-block;
		margin-bottom: 8px;
		font-size: 11px;
		font-weight: 700;
		letter-spacing: 0.12em;
		text-transform: uppercase;
		color: var(--p-blue);
	}

	.qp-guide-title {
		font-size: clamp(22px, 3vw, 30px);
		font-weight: 700;
		color: var(--p-ink);
		letter-spacing: -0.03em;
		line-height: 1.1;
	}

	.qp-guide-sub {
		margin-top: 8px;
		max-width: 720px;
		font-size: 14px;
		line-height: 1.7;
		color: var(--p-muted);
	}

	.qp-guide-actions {
		display: flex;
		align-items: center;
		gap: 12px;
		flex-shrink: 0;
	}

	.qp-guide-lang {
		display: inline-flex;
		align-items: center;
		gap: 4px;
		padding: 4px;
		background: #fff;
		border: 1px solid var(--p-line);
		border-radius: var(--p-r-pill);
	}

	.qp-guide-lang button {
		border: none;
		background: transparent;
		color: var(--p-muted);
		padding: 8px 14px;
		border-radius: var(--p-r-pill);
		font-size: 12px;
		font-weight: 700;
		cursor: pointer;
		font-family: inherit;
	}

	.qp-guide-lang button.is-active {
		background: linear-gradient(135deg, #2563eb, #06b6d4);
		color: #fff;
		box-shadow: 0 10px 24px rgba(37, 99, 235, 0.24);
	}

	.qp-guide-close {
		width: 42px;
		height: 42px;
		border-radius: 14px;
		border: 1px solid var(--p-line);
		background: #fff;
		color: var(--p-text);
		display: inline-flex;
		align-items: center;
		justify-content: center;
		cursor: pointer;
	}

	.qp-guide-body {
		padding: 24px 28px 28px;
		overflow-y: auto;
		display: grid;
		gap: 22px;
	}

	.qp-guide-banner {
		display: flex;
		align-items: center;
		gap: 20px;
		padding: 22px 26px;
		border-radius: 24px;
		background:
			radial-gradient(circle at top right, rgba(125, 211, 252, 0.28), transparent 24%),
			radial-gradient(circle at bottom left, rgba(129, 140, 248, 0.22), transparent 26%),
			linear-gradient(135deg, #0f172a 0%, #0f766e 100%);
		color: #fff;
		box-shadow: 0 22px 46px rgba(15, 23, 42, 0.22);
	}

	.qp-guide-banner h3 {
		font-size: 18px;
		font-weight: 700;
		margin-bottom: 10px;
	}

	.qp-guide-banner p {
		color: rgba(255, 255, 255, 0.76);
		font-size: 14px;
		line-height: 1.7;
	}

	.qp-guide-visual {
		display: flex;
		align-items: center;
		justify-content: center;
		flex: 0 0 auto;
	}

	.qp-guide-visual-stack {
		max-width: 240px;
		width: auto;
		padding: 16px;
		border-radius: 22px;
		background: rgba(255, 255, 255, 0.08);
		border: 1px solid rgba(255, 255, 255, 0.12);
		box-shadow:
			inset 0 1px 0 rgba(255, 255, 255, 0.06),
			0 12px 30px rgba(0, 0, 0, 0.25);
		backdrop-filter: blur(10px);
		margin: auto;
	}

	.qp-guide-icon-flow {
		display: flex;
		align-items: center;
		justify-content: center;
		gap: 10px;
	}

	.qp-guide-icon-node {
		width: 70px;
		height: 70px;
		border-radius: 20px;
		display: flex;
		align-items: center;
		justify-content: center;
		background: rgba(255, 255, 255, 0.1);
		border: 1px solid rgba(255, 255, 255, 0.15);
		box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
		transition: all 0.25s ease;
	}

	.qp-guide-icon-node i {
		width: 46px;
		height: 46px;
		border-radius: 14px;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 18px;
		color: #fff;
		background: linear-gradient(135deg, #0ea5e9, #2563eb);
		box-shadow: 0 8px 18px rgba(37, 99, 235, 0.4);
		transition: all 0.3s ease;
	}

	.qp-guide-icon-node:nth-child(1) i {
		background: linear-gradient(135deg, #6366f1, #8b5cf6);
	}

	.qp-guide-icon-node:nth-child(3) i {
		background: linear-gradient(135deg, #10b981, #059669);
	}

	.qp-guide-icon-node:nth-child(5) i {
		background: linear-gradient(135deg, #f59e0b, #f97316);
	}

	.qp-guide-icon-connector {
		width: 24px;
		height: 2px;
		border-radius: 999px;
		background: rgba(255, 255, 255, 0.5);
		position: relative;
	}

	.qp-guide-icon-connector::after {
		content: "";
		position: absolute;
		right: -2px;
		top: 50%;
		width: 10px;
		height: 10px;
		border-top: 2px solid #fff;
		border-right: 2px solid #fff;
		transform: translateY(-50%) rotate(45deg);
	}

	.qp-guide-icon-node:hover {
		transform: translateY(-6px) scale(1.05);
		box-shadow: 0 20px 40px rgba(0, 0, 0, 0.35);
	}

	.qp-guide-icon-node:hover i {
		transform: scale(1.1);
	}

	.qp-guide-icon-node.is-accent i {
		background: linear-gradient(135deg, #0ea5e9, #2563eb);
		color: #fff;
	}

	.qp-guide-section-title {
		font-size: 18px;
		font-weight: 700;
		color: var(--p-ink);
	}

	.qp-guide-steps {
		display: grid;
		grid-template-columns: repeat(2, minmax(0, 1fr));
		gap: 16px;
	}

	.qp-guide-step {
		background: #fff;
		border: 1px solid var(--p-line);
		border-radius: 22px;
		overflow: hidden;
		box-shadow: 0 16px 34px rgba(15, 23, 42, 0.08);
		transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
		position: relative;
	}

	.qp-guide-step:hover {
		transform: translateY(-3px);
		box-shadow: 0 22px 40px rgba(15, 23, 42, 0.12);
		border-color: rgba(37, 99, 235, 0.18);
	}

	.qp-guide-step::before {
		content: "";
		position: absolute;
		left: 0;
		top: 0;
		bottom: 0;
		width: 5px;
		background: linear-gradient(180deg, #2563eb, #06b6d4);
	}

	.qp-guide-media {
		padding: 16px;
	}

	.qp-guide-media img {
		width: 100%;
		height: 150px;
		object-fit: cover;
		display: block;
		border-radius: 18px;
		box-shadow: 0 12px 24px rgba(15, 23, 42, 0.08);
	}

	.qp-guide-media.blue {
		background: linear-gradient(135deg, #eff6ff, #dbeafe);
	}

	.qp-guide-media.green {
		background: linear-gradient(135deg, #f0fdf4, #dcfce7);
	}

	.qp-guide-media.amber {
		background: linear-gradient(135deg, #fffbeb, #fef3c7);
	}

	.qp-guide-media.red {
		background: linear-gradient(135deg, #fff1f2, #ffe4e6);
	}

	.qp-guide-step-body {
		padding: 18px 18px 20px;
	}

	.qp-guide-step-no {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		min-width: 46px;
		height: 28px;
		border-radius: var(--p-r-pill);
		background: linear-gradient(135deg, #dbeafe, #e0e7ff);
		color: #1d4ed8;
		box-shadow: inset 0 0 0 1px rgba(37, 99, 235, 0.10);
		font-size: 11px;
		font-weight: 700;
		letter-spacing: 0.08em;
		margin-bottom: 10px;
	}

	.qp-guide-step h4 {
		font-size: 15px;
		font-weight: 700;
		color: var(--p-ink);
		margin-bottom: 8px;
	}

	.qp-guide-step p {
		font-size: 13px;
		line-height: 1.65;
		color: var(--p-muted);
	}

	.qp-guide-grid-panels {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 16px;
	}

	.qp-guide-panel {
		background: #fff;
		border: 1px solid var(--p-line);
		border-radius: 20px;
		padding: 18px;
		box-shadow: 0 16px 34px rgba(15, 23, 42, 0.06);
		position: relative;
		overflow: hidden;
	}

	.qp-guide-panel::after {
		content: "";
		position: absolute;
		right: -32px;
		top: -32px;
		width: 110px;
		height: 110px;
		border-radius: 999px;
		background: radial-gradient(circle, rgba(56, 189, 248, 0.12), transparent 70%);
	}

	.qp-guide-panel h3 {
		font-size: 16px;
		font-weight: 700;
		color: var(--p-ink);
		margin-bottom: 12px;
	}

	.qp-guide-list {
		padding-left: 18px;
		display: grid;
		gap: 10px;
		font-size: 13px;
		line-height: 1.65;
		color: var(--p-text);
	}

	.qp-guide-note {
		display: flex;
		align-items: flex-start;
		gap: 10px;
		padding: 16px 18px;
		border-radius: 18px;
		background: linear-gradient(135deg, #fff8e6, #fffbeb);
		border: 1px solid #f5d08a;
		color: #8a5a08;
		font-size: 13px;
		line-height: 1.65;
		box-shadow: 0 12px 28px rgba(245, 158, 11, 0.08);
	}

	.qp-guide-banner>div:last-child {
		flex: 1;
		min-width: 0;
	}

	.qp-guide-banner h3,
	.qp-guide-banner p {
		word-break: break-word;
	}

	/* ── RESPONSIVE ── */
	@media (max-width: 900px) {
		.qp-hero-inner {
			flex-direction: column;
		}

		.qp-hero-left {
			padding: 28px 24px;
		}

		.qp-hero-left::after {
			display: none;
		}

		.qp-hero-right {
			border-top: 1px solid rgba(255, 255, 255, 0.07);
		}

		.qp-hero-stat {
			flex: 1;
			width: auto;
			border-left: none;
			border-right: 1px solid rgba(255, 255, 255, 0.07);
			padding: 20px 20px;
		}

		.qp-hero-stat:last-child {
			border-right: none;
		}

		.qp-hero-stat-val {
			font-size: 26px;
		}

		.qp-guide-banner,
		.qp-guide-steps,
		.qp-guide-grid-panels {
			grid-template-columns: 1fr;
		}

		.qp-guide-visual-stack {
			width: 100%;
		}
	}

	@media (max-width: 768px) {
		.qp-guide-banner {
			flex-direction: column;
			align-items: flex-start;
		}
	}

	@media (max-width: 680px) {
		.qp {
			gap: 14px;
		}

		.qp-section-head {
			padding: 16px 18px;
		}

		.qp-section-body {
			padding: 18px 18px;
		}

		.qp-toolbar {
			flex-wrap: wrap;
		}

		.qp-cat-counter {
			display: none;
		}

		.qp-hero-left {
			padding: 22px 20px;
		}

		.qp-hero-actions {
			flex-direction: column;
			align-items: flex-start;
		}

		.qp-guide-btn {
			width: 100%;
			justify-content: center;
		}

		.qp-hero-stat-val {
			font-size: 22px;
			margin-bottom: 8px;
		}

		.qp-hero-stat {
			padding: 16px;
		}

		.qp-guide-overlay {
			padding: 12px;
		}

		.qp-guide-head,
		.qp-guide-body {
			padding-left: 16px;
			padding-right: 16px;
		}

		.qp-guide-head {
			flex-direction: column;
		}

		.qp-guide-actions {
			width: 100%;
			justify-content: space-between;
		}

		.qp-guide-lang {
			flex: 1;
		}

		.qp-guide-lang button {
			flex: 1;
			text-align: center;
		}
	}

	@media (max-width: 480px) {
		.qp-cat-pill {
			width: calc(50% - 5px);
			justify-content: space-between;
		}
	}
</style>

<div class="qp">

	<!-- ── Hero ── -->
	<div class="qp-hero">
		<div class="qp-hero-inner">
			<div class="qp-hero-left">
				<div class="qp-hero-eyebrow">
					<i class="fa-solid fa-chart-line" style="font-size:11px"></i>
					Prediction Market
				</div>
				<div class="qp-hero-title">Questions</div>
				<div class="qp-hero-desc">Select a category below to load its questions instantly. Trade YES or NO on each outcome and track your performance.</div>
				<div class="qp-hero-actions">
					<button type="button" class="qp-guide-btn" id="qpGuideOpenBtn">
						<i class="fa-solid fa-book-open" style="font-size:13px"></i>
						How To Play
					</button>
				</div>
			</div>
			<div class="qp-hero-right">
				<div class="qp-hero-stat">
					<div class="qp-hero-stat-label">Answered</div>
					<div class="qp-hero-stat-val" id="heroAnswered">
						<?php echo (int)$answered_count; ?><span>/<?php echo (int)$total_questions; ?></span>
					</div>
					<div class="qp-prog-track">
						<div class="qp-prog-fill" id="heroProg"
							style="width:<?php echo $total_questions > 0 ? round($answered_count / $total_questions * 100) : 0; ?>%">
						</div>
					</div>
				</div>
				<div class="qp-hero-stat">
					<div class="qp-hero-stat-label">Categories</div>
					<div class="qp-hero-stat-val"><?php echo count($categories); ?></div>
					<div class="qp-prog-track">
						<div class="qp-prog-fill" style="width:100%;background:rgba(255,255,255,0.15)"></div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- ── Category Picker ── -->
	<div class="qp-section">
		<div class="qp-section-head">
			<div class="qp-section-head-left">
				<h2>Categories</h2>
				<p>Tap a category to load its questions — no page refresh needed</p>
			</div>
			<div class="qp-count-tag" id="catCountTag">
				<i class="fa-solid fa-layer-group" style="font-size:11px"></i>
				<span id="catCountVal"><?php echo count($categories); ?></span>&nbsp;available
			</div>
		</div>
		<div class="qp-section-body">

			<div class="qp-toolbar">
				<div class="qp-search-wrap">
					<svg viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
						<circle cx="6.5" cy="6.5" r="4.5" stroke="currentColor" stroke-width="1.5" />
						<path d="M10 10l3 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
					</svg>
					<input type="text" id="catSearch" placeholder="Search categories…" autocomplete="off">
				</div>
				<div class="qp-cat-counter">
					<strong id="catCounterNum"><?php echo count($categories); ?></strong>
					categor<?php echo count($categories) === 1 ? 'y' : 'ies'; ?>
				</div>
			</div>

			<div class="qp-cat-grid" id="catGrid">
				<?php foreach ($categories as $cat):
					$words  = preg_split('/\s+/', trim($cat->name));
					$abbrev = '';
					foreach ($words as $w) $abbrev .= strtoupper(mb_substr($w, 0, 1));
					$abbrev = mb_substr($abbrev, 0, 2);
				?>
					<button type="button"
						class="qp-cat-pill<?php echo ($selected_category && (int)$selected_category->id === (int)$cat->id) ? ' active' : ''; ?>"
						data-cat-btn
						data-cat-id="<?php echo (int)$cat->id; ?>">
						<span class="qp-cat-avatar"><?php echo html_escape($abbrev); ?></span>
						<span class="qp-cat-name"><?php echo html_escape($cat->name); ?></span>
						<span class="qp-cat-count">(<?php echo (int)$cat->question_count; ?>)</span>
					</button>
				<?php endforeach; ?>
			</div>

			<div class="qp-cat-empty" id="catEmpty">No categories match your search.</div>

		</div>
	</div>

	<!-- ── Questions ── -->
	<div class="qp-section">
		<div class="qp-section-head">
			<div class="qp-section-head-left">
				<h2 id="qSecTitle"><?php echo $selected_category ? html_escape($selected_category->name) . ' questions' : 'Choose a category'; ?></h2>
				<p id="qSecSub"><?php echo $selected_category ? (int)$total_questions . ' question' . ($total_questions !== 1 ? 's' : '') : 'Select a category above to view its questions.'; ?></p>
			</div>
			<?php if ($selected_category): ?>
				<div class="qp-count-tag" id="qCountTag">
					<i class="fa-solid fa-list-check" style="font-size:11px"></i>
					<?php echo (int)$total_questions; ?> questions
				</div>
			<?php else: ?>
				<div class="qp-count-tag" id="qCountTag" style="display:none"></div>
			<?php endif; ?>
		</div>
		<div class="qp-section-body">
			<div id="qContent">
				<?php echo $question_list_html; ?>
			</div>
		</div>
	</div>

</div>

<div class="qp-guide-overlay" id="qpGuideOverlay" aria-hidden="true">
	<div class="qp-guide-modal" role="dialog" aria-modal="true" aria-labelledby="qpGuideTitle">
		<div class="qp-guide-head">
			<div>
				<span class="qp-guide-eyebrow">Trading Guide</span>
				<div id="qpGuideTitle" class="qp-guide-title" data-lang="en">How to play and trade on questions</div>
				<div class="qp-guide-title" data-lang="hi" style="display:none;">सवालों पर कैसे खेलें और ट्रेड करें</div>
				<div class="qp-guide-sub" data-lang="en">Learn how to choose a market, place a trade safely, understand stake and payout, and track whether your answer wins or loses.</div>
				<div class="qp-guide-sub" data-lang="hi" style="display:none;">यह गाइड बताती है कि मार्केट कैसे चुनें, सुरक्षित तरीके से ट्रेड कैसे करें, स्टेक और पेआउट कैसे समझें, और आपका जवाब जीता या हारा यह कैसे ट्रैक करें।</div>
			</div>
			<div class="qp-guide-actions">
				<div class="qp-guide-lang">
					<button type="button" class="is-active" data-guide-lang="en">English</button>
					<button type="button" data-guide-lang="hi">Hindi</button>
				</div>
				<button type="button" class="qp-guide-close" id="qpGuideCloseBtn" aria-label="Close guide">
					<i class="fa-solid fa-xmark"></i>
				</button>
			</div>
		</div>

		<div class="qp-guide-body">
			<div class="qp-guide-banner">
				<div class="qp-guide-visual">
					<div class="qp-guide-visual-stack">
						<div class="qp-guide-icon-flow" aria-hidden="true">
							<div class="qp-guide-icon-node">
								<i class="fa-solid fa-layer-group"></i>
							</div>
							<div class="qp-guide-icon-connector"></div>
							<div class="qp-guide-icon-node">
								<i class="fa-solid fa-scale-balanced"></i>
							</div>
							<div class="qp-guide-icon-connector"></div>
							<div class="qp-guide-icon-node is-accent">
								<i class="fa-solid fa-bolt"></i>
							</div>
						</div>
					</div>
				</div>
				<div>
					<h3 data-lang="en">Understand the flow before you place a trade</h3>
					<h3 data-lang="hi" style="display:none;">ट्रेड करने से पहले पूरा फ्लो समझें</h3>
					<p data-lang="en">Choose a category, open a question, compare YES and NO prices, then set your price and quantity. Once you confirm, the stake is deducted and your trade stays locked until the final result is declared.</p>
					<p data-lang="hi" style="display:none;">पहले कैटेगरी चुनें, फिर सवाल खोलें, YES और NO प्राइस देखें, अपनी प्राइस और क्वांटिटी भरें और ट्रेड कन्फर्म करें। सबमिट होने के बाद आपका जवाब लॉक हो जाता है।</p>
				</div>
			</div>

			<div class="qp-guide-section-title" data-lang="en">Step by step</div>
			<div class="qp-guide-section-title" data-lang="hi" style="display:none;">स्टेप बाय स्टेप</div>

			<div class="qp-guide-steps">
				<article class="qp-guide-step">
					<div class="qp-guide-media blue">
						<img src="<?php echo base_url('assets/images/cards/hp1.png'); ?>" alt="Select category card image">
					</div>
					<div class="qp-guide-step-body">
						<div class="qp-guide-step-no">01</div>
						<h4 data-lang="en">Select a category</h4>
						<h4 data-lang="hi" style="display:none;">कैटेगरी चुनें</h4>
						<p data-lang="en">Start from the category chips at the top. Open the category you want and check only the questions that are currently available for trading.</p>
						<p data-lang="hi" style="display:none;">ऊपर दिए गए कैटेगरी चिप्स में से अपनी पसंद की कैटेगरी चुनें। उसी कैटेगरी के उपलब्ध सवाल तुरंत नीचे दिखेंगे।</p>
					</div>
				</article>

				<article class="qp-guide-step">
					<div class="qp-guide-media green">
						<img src="<?php echo base_url('assets/images/cards/hp2.png'); ?>" alt="Question selection card image">
					</div>
					<div class="qp-guide-step-body">
						<div class="qp-guide-step-no">02</div>
						<h4 data-lang="en">Open a question and choose YES or NO</h4>
						<h4 data-lang="hi" style="display:none;">सवाल खोलें और YES या NO चुनें</h4>
						<p data-lang="en">Read the question carefully before tapping a side. Choose YES if you believe the event will happen, or NO if you believe it will not happen.</p>
						<p data-lang="hi" style="display:none;">सवाल को ध्यान से पढ़ें। अगर आपको लगता है कि घटना होगी तो YES चुनें, नहीं होगी तो NO चुनें।</p>
					</div>
				</article>

				<article class="qp-guide-step">
					<div class="qp-guide-media amber">
						<img src="<?php echo base_url('assets/images/cards/hp3.png'); ?>" alt="Price and quantity card image">
					</div>
					<div class="qp-guide-step-body">
						<div class="qp-guide-step-no">03</div>
						<h4 data-lang="en">Enter price and quantity</h4>
						<h4 data-lang="hi" style="display:none;">प्राइस और क्वांटिटी भरें</h4>
						<p data-lang="en">Set your price within the allowed range, then choose your quantity. Your total stake is calculated from price multiplied by quantity.</p>
						<p data-lang="hi" style="display:none;">अनुमत रेंज के अंदर अपनी प्राइस भरें, फिर क्वांटिटी चुनें। आपका कुल स्टेक इन दोनों के हिसाब से बनता है।</p>
					</div>
				</article>

				<article class="qp-guide-step">
					<div class="qp-guide-media red">
						<img src="<?php echo base_url('assets/images/cards/hp4.png'); ?>" alt="Trade result card image">
					</div>
					<div class="qp-guide-step-body">
						<div class="qp-guide-step-no">04</div>
						<h4 data-lang="en">Confirm trade and track result</h4>
						<h4 data-lang="hi" style="display:none;">ट्रेड कन्फर्म करें और रिजल्ट ट्रैक करें</h4>
						<p data-lang="en">After submitting, the trade amount is deducted from your wallet immediately. Later you can track whether the trade is waiting, won, or lost after settlement.</p>
						<p data-lang="hi" style="display:none;">सबमिट करने के बाद ट्रेड अमाउंट आपके वॉलेट से कटता है। बाद में आप देख सकते हैं कि आपका ट्रेड waiting, won या lost है।</p>
					</div>
				</article>
			</div>

			<div class="qp-guide-grid-panels">
				<section class="qp-guide-panel">
					<h3 data-lang="en">Quick tips</h3>
					<h3 data-lang="hi" style="display:none;">जल्दी समझने वाले टिप्स</h3>
					<ul class="qp-guide-list" data-lang="en">
						<li>Read the question fully before selecting YES or NO.</li>
						<li>Check the market closing time before placing any trade.</li>
						<li>Confirm your price, quantity, and total stake before submit.</li>
						<li>Once submitted, you cannot edit or cancel that trade.</li>
					</ul>
					<ul class="qp-guide-list" data-lang="hi" style="display:none;">
						<li>YES या NO चुनने से पहले सवाल पूरा पढ़ें।</li>
						<li>मार्केट बंद होने का समय जरूर देखें।</li>
						<li>फाइनल सबमिट से पहले कुल स्टेक चेक करें।</li>
						<li>एक बार जवाब देने के बाद ट्रेड बदला नहीं जा सकता।</li>
					</ul>
				</section>

				<section class="qp-guide-panel">
					<h3 data-lang="en">Result states</h3>
					<h3 data-lang="hi" style="display:none;">रिजल्ट स्टेट्स</h3>
					<ul class="qp-guide-list" data-lang="en">
						<li><strong>Open:</strong> the market is active and you can place a trade.</li>
						<li><strong>Waiting:</strong> your trade is locked and the result is still pending.</li>
						<li><strong>Won:</strong> your selected side matched the final declared result.</li>
						<li><strong>Lost:</strong> your selected side did not match the final result.</li>
					</ul>
					<ul class="qp-guide-list" data-lang="hi" style="display:none;">
						<li><strong>Open:</strong> आप अभी ट्रेड कर सकते हैं।</li>
						<li><strong>Waiting:</strong> ट्रेड लॉक है और रिजल्ट बाकी है।</li>
						<li><strong>Won:</strong> आपका चुना हुआ साइड सही निकला।</li>
						<li><strong>Lost:</strong> आपका चुना हुआ साइड गलत निकला।</li>
					</ul>
				</section>
			</div>

			<div class="qp-guide-note">
				<i class="fa-solid fa-circle-info" style="font-size:14px;margin-top:2px"></i>
				<div data-lang="en">Place a trade only after reviewing the question, market status, price, quantity, and wallet balance carefully. Trading closes automatically when the market is no longer open.</div>
				<div data-lang="hi" style="display:none;">प्राइस, क्वांटिटी और वॉलेट बैलेंस ध्यान से देखने के बाद ही ट्रेड करें। मार्केट नियमों के अनुसार सवाल कभी भी बंद हो सकता है।</div>
			</div>
		</div>
	</div>
</div>

<script>
	(function() {
		'use strict';

		var BASE_URL = '<?php echo site_url('questions'); ?>';
		var AJAX_URL = '<?php echo site_url('questions/category-data'); ?>';

		var catGrid = document.getElementById('catGrid');
		var catSearch = document.getElementById('catSearch');
		var catEmpty = document.getElementById('catEmpty');
		var catCountEl = document.getElementById('catCounterNum');
		var catCountTag = document.getElementById('catCountVal');
		var qContent = document.getElementById('qContent');
		var qTitle = document.getElementById('qSecTitle');
		var qSub = document.getElementById('qSecSub');
		var qCountTag = document.getElementById('qCountTag');
		var heroAns = document.getElementById('heroAnswered');
		var heroProg = document.getElementById('heroProg');
		var guideOverlay = document.getElementById('qpGuideOverlay');
		var guideOpenBtn = document.getElementById('qpGuideOpenBtn');
		var guideCloseBtn = document.getElementById('qpGuideCloseBtn');
		var guideLangButtons = Array.from(document.querySelectorAll('[data-guide-lang]'));

		if (!catGrid || !qContent) return;

		var pills = Array.from(catGrid.querySelectorAll('[data-cat-btn]'));

		function setActive(id) {
			pills.forEach(function(p) {
				p.classList.toggle('active', Number(p.dataset.catId) === Number(id));
			});
		}

		function showLoading() {
			qContent.innerHTML = '<div class="qp-loading"><div class="qp-spinner"></div> Loading questions…</div>';
		}

		function showError() {
			qContent.innerHTML = '<div class="qp-loading" style="color:var(--p-red)"><i class="fa-solid fa-triangle-exclamation"></i> Unable to load. Try selecting the category again.</div>';
		}

		function updateHero(answered, total) {
			heroAns.innerHTML = answered + '<span>/' + total + '</span>';
			heroProg.style.width = total > 0 ? Math.round(answered / total * 100) + '%' : '0%';
		}

		function filterPills() {
			var term = catSearch.value.toLowerCase().trim();
			var vis = 0;
			pills.forEach(function(p) {
				var name = (p.querySelector('.qp-cat-name') || {}).textContent || '';
				var ok = !term || name.toLowerCase().indexOf(term) !== -1;
				p.style.display = ok ? '' : 'none';
				if (ok) vis++;
			});
			catEmpty.style.display = vis === 0 ? 'block' : 'none';
			if (catCountEl) catCountEl.textContent = vis;
			if (catCountTag) catCountTag.textContent = vis;
		}

		catGrid.addEventListener('click', function(e) {
			var btn = e.target.closest('[data-cat-btn]');
			if (!btn) return;
			var id = Number(btn.dataset.catId || '0');
			if (!id) return;
			var name = (btn.querySelector('.qp-cat-name') || {}).textContent || 'Questions';

			setActive(id);
			showLoading();
			qTitle.textContent = name + ' questions';
			qSub.textContent = 'Loading…';
			if (qCountTag) {
				qCountTag.style.display = 'none';
			}

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
					var total = Number(data.total_questions) || 0;
					var ans = Number(data.answered_count) || 0;
					qTitle.textContent = name + ' questions';
					qSub.textContent = total + ' question' + (total !== 1 ? 's' : '');
					if (qCountTag) {
						qCountTag.style.display = 'inline-flex';
						qCountTag.innerHTML = '<i class="fa-solid fa-list-check" style="font-size:11px"></i> ' + total + ' questions';
					}
					updateHero(ans, total);
					window.history.replaceState({}, '', BASE_URL + '?category_id=' + id);
				})
				.catch(showError);
		});

		catSearch.addEventListener('input', filterPills);
		filterPills();

		function setGuideLanguage(lang) {
			if (!guideOverlay) return;
			guideOverlay.querySelectorAll('[data-lang]').forEach(function(el) {
				el.style.display = el.getAttribute('data-lang') === lang ? '' : 'none';
			});
			guideLangButtons.forEach(function(btn) {
				btn.classList.toggle('is-active', btn.getAttribute('data-guide-lang') === lang);
			});
		}

		function openGuide() {
			if (!guideOverlay) return;
			guideOverlay.classList.add('is-open');
			guideOverlay.setAttribute('aria-hidden', 'false');
			document.body.style.overflow = 'hidden';
		}

		function closeGuide() {
			if (!guideOverlay) return;
			guideOverlay.classList.remove('is-open');
			guideOverlay.setAttribute('aria-hidden', 'true');
			document.body.style.overflow = '';
		}

		if (guideOpenBtn) {
			guideOpenBtn.addEventListener('click', openGuide);
		}

		if (guideCloseBtn) {
			guideCloseBtn.addEventListener('click', closeGuide);
		}

		if (guideOverlay) {
			guideOverlay.addEventListener('click', function(e) {
				if (e.target === guideOverlay) {
					closeGuide();
				}
			});
		}

		document.addEventListener('keydown', function(e) {
			if (e.key === 'Escape' && guideOverlay && guideOverlay.classList.contains('is-open')) {
				closeGuide();
			}
		});

		guideLangButtons.forEach(function(btn) {
			btn.addEventListener('click', function() {
				setGuideLanguage(this.getAttribute('data-guide-lang'));
			});
		});

		setGuideLanguage('en');
	}());
</script>