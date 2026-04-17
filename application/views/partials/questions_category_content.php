<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400&display=swap" rel="stylesheet">

<style>
	:root {
		--ql-bg: #f0f4ff;
		--ql-surface: #ffffff;
		--ql-surface2: #f7f9fc;
		--ql-ink: #0d1321;
		--ql-text: #3d4a63;
		--ql-muted: #7a8499;
		--ql-line: #e4e9f4;
		--ql-blue: #2f5be8;
		--ql-blue-bg: #eef2fd;
		--ql-blue-border: #c5d0f9;
		--ql-green: #0fa966;
		--ql-green-bg: #e8f9f1;
		--ql-green-border: #a3dfc2;
		--ql-red: #dc3545;
		--ql-red-bg: #fdeef0;
		--ql-red-border: #f4b8bf;
		--ql-amber: #c97c10;
		--ql-amber-bg: #fef5e4;
		--ql-amber-border: #f5d08a;
		--ql-shadow: 0 1px 4px rgba(15, 23, 60, 0.07), 0 4px 16px rgba(15, 23, 60, 0.05);
		--ql-shadow-hover: 0 4px 20px rgba(15, 23, 60, 0.12), 0 1px 4px rgba(15, 23, 60, 0.06);
		--ql-r: 18px;
		--ql-r-sm: 12px;
		--ql-r-pill: 999px;
	}

	.ql-wrap {
		font-family: 'Roboto', sans-serif;
		display: flex;
		flex-direction: column;
		gap: 16px;
	}

	/* ── CAT HEADER ── */
	.ql-header {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 14px;
		flex-wrap: wrap;
		padding: 22px 24px;
		background: var(--ql-surface);
		border: 1px solid var(--ql-line);
		border-radius: var(--ql-r);
		box-shadow: var(--ql-shadow);
	}

	.ql-header-left {
		display: flex;
		align-items: center;
		gap: 16px;
	}

	.ql-header-icon {
		width: 48px;
		height: 48px;
		border-radius: 14px;
		background: var(--ql-blue-bg);
		border: 1.5px solid var(--ql-blue-border);
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 18px;
		color: var(--ql-blue);
		flex-shrink: 0;
	}

	.ql-header-title {
		font-size: clamp(18px, 2.5vw, 24px);
		font-weight: 700;
		letter-spacing: -0.02em;
		color: var(--ql-ink);
		line-height: 1.2;
	}

	.ql-header-sub {
		font-size: 13px;
		color: var(--ql-muted);
		margin-top: 3px;
		font-weight: 400;
	}

	.ql-live-badge {
		display: inline-flex;
		align-items: center;
		gap: 8px;
		padding: 8px 16px;
		border-radius: var(--ql-r-pill);
		font-size: 11px;
		font-weight: 600;
		letter-spacing: 0.05em;
		text-transform: uppercase;
		background: var(--ql-green-bg);
		color: var(--ql-green);
		border: 1px solid var(--ql-green-border);
	}

	.ql-live-dot {
		width: 7px;
		height: 7px;
		border-radius: 50%;
		background: var(--ql-green);
		animation: ql-pulse 1.8s ease-in-out infinite;
		flex-shrink: 0;
	}

	@keyframes ql-pulse {

		0%,
		100% {
			opacity: 1;
			transform: scale(1);
		}

		50% {
			opacity: 0.35;
			transform: scale(0.8);
		}
	}

	/* ── STATS ROW ── */
	.ql-stats {
		display: grid;
		grid-template-columns: repeat(4, 1fr);
		gap: 12px;
	}

	.ql-stat {
		background: var(--ql-surface);
		border: 1px solid var(--ql-line);
		border-radius: var(--ql-r-sm);
		padding: 16px 18px;
		box-shadow: var(--ql-shadow);
		position: relative;
		overflow: hidden;
	}

	.ql-stat::before {
		content: '';
		position: absolute;
		top: 0;
		left: 0;
		right: 0;
		height: 3px;
		border-radius: 3px 3px 0 0;
		background: var(--ql-line);
	}

	.ql-stat.s-total::before {
		background: var(--ql-ink);
	}

	.ql-stat.s-answered::before {
		background: var(--ql-blue);
	}

	.ql-stat.s-correct::before {
		background: var(--ql-green);
	}

	.ql-stat.s-wrong::before {
		background: var(--ql-red);
	}

	.ql-stat-val {
		font-size: 28px;
		font-weight: 700;
		letter-spacing: -0.04em;
		line-height: 1;
		margin-bottom: 5px;
	}

	.ql-stat.s-total .ql-stat-val {
		color: var(--ql-ink);
	}

	.ql-stat.s-answered .ql-stat-val {
		color: var(--ql-blue);
	}

	.ql-stat.s-correct .ql-stat-val {
		color: var(--ql-green);
	}

	.ql-stat.s-wrong .ql-stat-val {
		color: var(--ql-red);
	}

	.ql-stat-label {
		font-size: 11px;
		font-weight: 600;
		text-transform: uppercase;
		letter-spacing: 0.08em;
		color: var(--ql-muted);
	}

	/* ── QUESTION CARDS ── */
	.ql-list {
		display: flex;
		flex-direction: column;
		gap: 12px;
	}

	.ql-card {
		display: block;
		text-decoration: none;
		color: var(--ql-ink);
		background: var(--ql-surface);
		border: 1.5px solid var(--ql-line);
		border-radius: var(--ql-r);
		overflow: hidden;
		box-shadow: var(--ql-shadow);
		transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
		position: relative;
	}

	.ql-card:hover {
		transform: translateY(-3px);
		box-shadow: var(--ql-shadow-hover);
		border-color: var(--ql-blue-border);
	}

	.ql-card::after {
		content: '';
		position: absolute;
		left: 0;
		top: 0;
		bottom: 0;
		width: 4px;
	}

	.ql-card.state-pending::after {
		background: var(--ql-blue);
	}

	.ql-card.state-correct {
		border-color: var(--ql-green-border);
		background: linear-gradient(135deg, #fff 0%, #f4fdf8 100%);
	}

	.ql-card.state-correct::after {
		background: var(--ql-green);
	}

	.ql-card.state-wrong {
		border-color: var(--ql-red-border);
		background: linear-gradient(135deg, #fff 0%, #fdf4f5 100%);
	}

	.ql-card.state-wrong::after {
		background: var(--ql-red);
	}

	.ql-card-body {
		padding: 20px 22px 20px 26px;
	}

	/* card top row */
	.ql-card-row {
		display: flex;
		align-items: flex-start;
		justify-content: space-between;
		gap: 14px;
		margin-bottom: 14px;
	}

	.ql-card-title-group {
		display: flex;
		align-items: flex-start;
		gap: 12px;
		flex: 1;
		min-width: 0;
	}

	.ql-idx {
		width: 32px;
		height: 32px;
		border-radius: 10px;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 12px;
		font-weight: 700;
		flex-shrink: 0;
		background: var(--ql-blue-bg);
		color: var(--ql-blue);
		border: 1px solid var(--ql-blue-border);
	}

	.ql-card.state-correct .ql-idx {
		background: var(--ql-green-bg);
		color: var(--ql-green);
		border-color: var(--ql-green-border);
	}

	.ql-card.state-wrong .ql-idx {
		background: var(--ql-red-bg);
		color: var(--ql-red);
		border-color: var(--ql-red-border);
	}

	.ql-question-text {
		font-size: 15px;
		font-weight: 500;
		line-height: 1.55;
		color: var(--ql-ink);
		flex: 1;
		min-width: 0;
	}

	.ql-status-tag {
		display: inline-flex;
		align-items: center;
		gap: 5px;
		padding: 5px 12px;
		border-radius: var(--ql-r-pill);
		font-size: 11px;
		font-weight: 700;
		text-transform: uppercase;
		letter-spacing: 0.06em;
		white-space: nowrap;
		flex-shrink: 0;
		border: 1px solid transparent;
	}

	.tag-pending {
		background: var(--ql-blue-bg);
		color: var(--ql-blue);
		border-color: var(--ql-blue-border);
	}

	.tag-review {
		background: var(--ql-amber-bg);
		color: var(--ql-amber);
		border-color: var(--ql-amber-border);
	}

	.tag-not-open {
		background: var(--ql-surface2);
		color: var(--ql-muted);
		border-color: var(--ql-line);
	}

	.tag-waiting {
		background: var(--ql-amber-bg);
		color: var(--ql-amber);
		border-color: var(--ql-amber-border);
	}

	.tag-won {
		background: var(--ql-green-bg);
		color: var(--ql-green);
		border-color: var(--ql-green-border);
	}

	.tag-lost {
		background: var(--ql-red-bg);
		color: var(--ql-red);
		border-color: var(--ql-red-border);
	}

	/* odds row */
	.ql-odds-row {
		display: flex;
		align-items: center;
		gap: 8px;
		margin-bottom: 12px;
		flex-wrap: wrap;
	}

	.ql-odds-label {
		font-size: 11px;
		font-weight: 600;
		text-transform: uppercase;
		letter-spacing: 0.07em;
		color: var(--ql-muted);
		margin-right: 2px;
	}

	.ql-odd-chip {
		display: inline-flex;
		align-items: center;
		gap: 7px;
		padding: 6px 14px;
		border-radius: var(--ql-r-sm);
		font-size: 13px;
		font-weight: 600;
	}

	.ql-odd-chip.yes {
		background: var(--ql-green-bg);
		color: var(--ql-green);
		border: 1px solid var(--ql-green-border);
	}

	.ql-odd-chip.no {
		background: var(--ql-red-bg);
		color: var(--ql-red);
		border: 1px solid var(--ql-red-border);
	}

	.ql-odd-chip .chip-label {
		font-size: 10px;
		font-weight: 700;
		letter-spacing: 0.06em;
		opacity: 0.7;
	}

	.ql-odd-chip .chip-val {
		font-weight: 700;
	}

	/* users strip */
	.ql-users-strip {
		display: inline-flex;
		align-items: center;
		gap: 7px;
		padding: 7px 14px;
		border-radius: var(--ql-r-sm);
		background: var(--ql-surface2);
		border: 1px solid var(--ql-line);
		margin-bottom: 12px;
		font-size: 12px;
		color: var(--ql-text);
		font-weight: 500;
	}

	.ql-users-strip strong {
		color: var(--ql-ink);
		font-weight: 700;
	}

	/* footer */
	.ql-card-footer {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 12px;
		padding-top: 14px;
		border-top: 1px solid var(--ql-line);
		flex-wrap: wrap;
	}

	.ql-card.state-correct .ql-card-footer {
		border-color: var(--ql-green-border);
	}

	.ql-card.state-wrong .ql-card-footer {
		border-color: var(--ql-red-border);
	}

	.ql-footer-left {
		display: flex;
		align-items: center;
		gap: 7px;
		font-size: 12px;
		color: var(--ql-muted);
		font-weight: 500;
	}

	.ql-footer-right {
		display: flex;
		align-items: center;
		gap: 14px;
	}

	.ql-payout {
		font-size: 13px;
		font-weight: 700;
	}

	.ql-payout.win {
		color: var(--ql-green);
	}

	.ql-payout.loss {
		color: var(--ql-red);
	}

	.ql-payout.neutral {
		color: var(--ql-text);
	}

	.ql-trade-btn {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		font-size: 12px;
		font-weight: 600;
		color: var(--ql-blue);
		opacity: 0;
		transition: opacity 0.2s;
	}

	.ql-card:hover .ql-trade-btn {
		opacity: 1;
	}

	/* empty states */
	.ql-empty {
		text-align: center;
		padding: 64px 24px;
		background: var(--ql-surface);
		border: 1.5px dashed var(--ql-line);
		border-radius: var(--ql-r);
	}

	.ql-empty-icon {
		width: 56px;
		height: 56px;
		border-radius: 18px;
		background: var(--ql-blue-bg);
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 20px;
		color: var(--ql-blue);
		margin: 0 auto 16px;
		border: 1.5px solid var(--ql-blue-border);
	}

	.ql-empty h3 {
		font-size: 18px;
		font-weight: 700;
		color: var(--ql-ink);
		margin-bottom: 8px;
		letter-spacing: -0.01em;
	}

	.ql-empty p {
		font-size: 14px;
		color: var(--ql-muted);
		max-width: 300px;
		margin: 0 auto;
		line-height: 1.7;
	}

	@media (max-width: 680px) {
		.ql-stats {
			grid-template-columns: repeat(2, 1fr);
			gap: 10px;
		}

		.ql-card-body {
			padding: 16px 18px 16px 22px;
		}

		.ql-header {
			padding: 18px 18px;
		}

		.ql-header-left {
			gap: 12px;
		}

		.ql-card-row {
			flex-wrap: wrap;
		}

		.ql-status-tag {
			margin-left: auto;
		}

		.ql-trade-btn {
			display: none;
		}
	}
</style>

<div class="ql-wrap">

	<?php if ($selected_category && !empty($selected_category->questions)): ?>

		<!-- Header -->
		<div class="ql-header">
			<div class="ql-header-left">
				<div class="ql-header-icon"><i class="fa-solid fa-bolt"></i></div>
				<div>
					<div class="ql-header-title"><?php echo html_escape($selected_category->name); ?></div>
					<div class="ql-header-sub">Select any question below to trade</div>
				</div>
			</div>
			<div class="ql-live-badge">
				<div class="ql-live-dot"></div>
				Live
			</div>
		</div>

		<!-- Stats -->
		<div class="ql-stats">
			<div class="ql-stat s-total">
				<div class="ql-stat-val"><?php echo (int)$total_questions; ?></div>
				<div class="ql-stat-label">Total</div>
			</div>
			<div class="ql-stat s-answered">
				<div class="ql-stat-val"><?php echo (int)$answered_count; ?></div>
				<div class="ql-stat-label">Answered</div>
			</div>
			<div class="ql-stat s-correct">
				<div class="ql-stat-val"><?php echo (int)$correct_count; ?></div>
				<div class="ql-stat-label">Correct</div>
			</div>
			<div class="ql-stat s-wrong">
				<div class="ql-stat-val"><?php echo (int)$wrong_count; ?></div>
				<div class="ql-stat-label">Wrong</div>
			</div>
		</div>

		<!-- List -->
		<div class="ql-list">
			<?php foreach ($selected_category->questions as $index => $question_item): ?>
				<?php
				$answer_state = isset($user_answers[(int)$question_item->id]) ? $user_answers[(int)$question_item->id] : NULL;
				$question_status = strtolower(trim((string)(isset($question_item->status) ? $question_item->status : '')));
				$start_ts = (!empty($question_item->start_time) && $question_item->start_time !== '0000-00-00 00:00:00') ? strtotime($question_item->start_time) : FALSE;
				$end_ts = (!empty($question_item->end_time) && $question_item->end_time !== '0000-00-00 00:00:00') ? strtotime($question_item->end_time) : FALSE;
				$now_ts = time();
				$is_trade_open = $question_status === 'open'
					&& ($start_ts === FALSE || $now_ts >= $start_ts)
					&& ($end_ts === FALSE || $now_ts <= $end_ts);
				$state        = 'state-pending';
				$tag_class    = 'tag-pending';
				$tag_icon     = 'fa-solid fa-circle';
				$tag_label    = 'Open';
				$payout_text  = 'Not answered';
				$payout_class = 'neutral';

				if ($answer_state) {
					if (!empty($answer_state->settled_at)) {
						$is_correct   = strtolower((string)$answer_state->answer) === strtolower((string)$question_item->answer_key);
						$state        = $is_correct ? 'state-correct' : 'state-wrong';
						$tag_class    = $is_correct ? 'tag-won' : 'tag-lost';
						$tag_icon     = $is_correct ? 'fa-solid fa-check' : 'fa-solid fa-xmark';
						$tag_label    = $is_correct ? 'Won' : 'Lost';
						$payout_text  = $is_correct ? '+ ₹' . number_format((float)$answer_state->payout_amount, 2) : '₹0.00';
						$payout_class = $is_correct ? 'win' : 'loss';
					} else {
						$tag_class    = 'tag-review';
						$tag_icon     = 'fa-solid fa-eye';
						$tag_label    = 'Review question';
						$payout_text  = 'Stake ₹' . number_format((float)$answer_state->stake_amount, 2);
						$payout_class = 'neutral';
					}
				} elseif (!$is_trade_open) {
					$tag_class = 'tag-not-open';
					$tag_icon = 'fa-solid fa-lock';
					$tag_label = 'Not open';
				}
				?>
				<a class="ql-card <?php echo $state; ?>" href="<?php echo site_url('questions/answer/' . (int)$question_item->id); ?>">
					<div class="ql-card-body">

						<div class="ql-card-row">
							<div class="ql-card-title-group">
								<div class="ql-idx"><?php echo $index + 1; ?></div>
								<div class="ql-question-text"><?php echo html_escape($question_item->question); ?></div>
							</div>
							<span class="ql-status-tag <?php echo $tag_class; ?>">
								<i class="<?php echo $tag_icon; ?>" style="font-size:9px"></i>
								<?php echo $tag_label; ?>
							</span>
						</div>

						<div class="ql-odds-row">
							<span class="ql-odds-label">Odds</span>
							<div class="ql-odd-chip yes">
								<span class="chip-label">YES</span>
								<span class="chip-val">₹<?php echo number_format((float)$question_item->yes_price, 2); ?></span>
							</div>
							<div class="ql-odd-chip no">
								<span class="chip-label">NO</span>
								<span class="chip-val">₹<?php echo number_format((float)$question_item->no_price, 2); ?></span>
							</div>
						</div>

						<div class="ql-users-strip">
							<i class="fa-solid fa-users" style="font-size:12px;color:var(--ql-muted)"></i>
							<strong><?php echo (int)(isset($question_item->total_users) ? $question_item->total_users : 0); ?></strong>
							traders joined
						</div>

						<div class="ql-card-footer">
							<div class="ql-footer-left">
								<?php if ($answer_state): ?>
									<i class="fa-solid fa-eye" style="font-size:11px"></i> Review question
								<?php elseif ($is_trade_open): ?>
									<i class="fa-solid fa-circle-dot" style="font-size:11px;color:var(--ql-blue)"></i> Open to trade
								<?php else: ?>
									<i class="fa-solid fa-lock" style="font-size:11px"></i> Not open for trade
								<?php endif; ?>
							</div>
							<div class="ql-footer-right">
								<span class="ql-payout <?php echo $payout_class; ?>"><?php echo $payout_text; ?></span>
								<span class="ql-trade-btn">Trade <i class="fa-solid fa-arrow-right" style="font-size:11px"></i></span>
							</div>
						</div>

					</div>
				</a>
			<?php endforeach; ?>
		</div>

	<?php elseif ($selected_category): ?>

		<div class="ql-empty">
			<div class="ql-empty-icon"><i class="fa-solid fa-inbox"></i></div>
			<h3>No Questions Yet</h3>
			<p>This category has no questions. Please choose another category from the list above.</p>
		</div>

	<?php else: ?>

		<div class="ql-empty">
			<div class="ql-empty-icon"><i class="fa-solid fa-hand-pointer"></i></div>
			<h3>Pick a Category</h3>
			<p>Select any category above and its questions will appear here instantly — no page reload.</p>
		</div>

	<?php endif; ?>

</div>
