<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=IBM+Plex+Mono:wght@500;600&display=swap" rel="stylesheet">

<style>
	:root {
		--q2-bg: #f5f6fa;
		--q2-card: #ffffff;
		--q2-ink: #0e1117;
		--q2-text: #4b5263;
		--q2-muted: #8b92a5;
		--q2-line: #e8eaf0;
		--q2-blue: #3b6ef6;
		--q2-blue-soft: #eef2ff;
		--q2-blue-mid: #c7d4fd;
		--q2-green: #18a058;
		--q2-green-soft: #e6f6ec;
		--q2-red: #d84040;
		--q2-red-soft: #fce8e8;
		--q2-amber: #d97706;
		--q2-amber-soft: #fef3c7;
		--q2-shadow: 0 2px 16px rgba(14, 17, 23, 0.06);
		--q2-shadow-lg: 0 12px 36px rgba(14, 17, 23, 0.1);
	}

	.q2-wrap {
		font-family: 'Space Grotesk', sans-serif;
		color: var(--q2-ink);
		display: grid;
		gap: 20px;
	}

	/* ── CATEGORY HEADER ─────────────────────────────────── */
	.q2-cat-header {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 16px;
		flex-wrap: wrap;
	}

	.q2-cat-left {
		display: flex;
		align-items: center;
		gap: 14px;
	}

	.q2-cat-icon {
		width: 52px;
		height: 52px;
		border-radius: 16px;
		background: var(--q2-blue-soft);
		border: 1.5px solid var(--q2-blue-mid);
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 20px;
		color: var(--q2-blue);
		flex-shrink: 0;
	}

	.q2-cat-name {
		font-size: clamp(22px, 3vw, 30px);
		font-weight: 700;
		letter-spacing: -0.03em;
		margin-bottom: 3px;
		line-height: 1;
	}

	.q2-cat-desc {
		font-size: 13px;
		color: var(--q2-muted);
	}

	.q2-status-pill {
		display: inline-flex;
		align-items: center;
		gap: 7px;
		padding: 8px 16px;
		border-radius: 999px;
		font-size: 12px;
		font-weight: 600;
		background: var(--q2-green-soft);
		color: var(--q2-green);
		border: 1px solid rgba(24, 160, 88, 0.2);
	}

	.q2-status-dot {
		width: 7px;
		height: 7px;
		border-radius: 50%;
		background: var(--q2-green);
		animation: blink 1.8s ease-in-out infinite;
	}

	@keyframes blink {

		0%,
		100% {
			opacity: 1;
		}

		50% {
			opacity: 0.3;
		}
	}

	/* ── STATS BAR ─────────────────────────────────── */
	.q2-stats {
		display: grid;
		grid-template-columns: repeat(4, 1fr);
		gap: 1px;
		background: var(--q2-line);
		border: 1px solid var(--q2-line);
		border-radius: 18px;
		overflow: hidden;
		box-shadow: var(--q2-shadow);
	}

	.q2-stat {
		background: var(--q2-card);
		padding: 18px 20px;
		text-align: center;
	}

	.q2-stat-num {
		font-size: 28px;
		font-weight: 700;
		letter-spacing: -0.04em;
		line-height: 1;
		margin-bottom: 6px;
		font-family: 'IBM Plex Mono', monospace;
	}

	.q2-stat-num.col-default {
		color: var(--q2-ink);
	}

	.q2-stat-num.col-blue {
		color: var(--q2-blue);
	}

	.q2-stat-num.col-green {
		color: var(--q2-green);
	}

	.q2-stat-num.col-red {
		color: var(--q2-red);
	}

	.q2-stat-label {
		font-size: 11px;
		font-weight: 600;
		text-transform: uppercase;
		letter-spacing: 0.08em;
		color: var(--q2-muted);
	}

	/* ── QUESTION CARDS ─────────────────────────────────── */
	.q2-list {
		display: grid;
		gap: 14px;
	}

	.q2-card {
		background: var(--q2-card);
		border: 1.5px solid var(--q2-line);
		border-radius: 20px;
		padding: 0;
		overflow: hidden;
		text-decoration: none;
		color: var(--q2-ink);
		display: block;
		transition: all 0.22s ease;
		box-shadow: var(--q2-shadow);
		position: relative;
	}

	.q2-card:hover {
		transform: translateY(-3px);
		box-shadow: var(--q2-shadow-lg);
		border-color: var(--q2-blue-mid);
	}

	/* State left border accent */
	.q2-card::before {
		content: '';
		position: absolute;
		left: 0;
		top: 0;
		bottom: 0;
		width: 4px;
		border-radius: 4px 0 0 4px;
	}

	.q2-card.pending::before {
		background: var(--q2-blue);
	}

	.q2-card.correct::before {
		background: var(--q2-green);
	}

	.q2-card.wrong::before {
		background: var(--q2-red);
	}

	/* State backgrounds */
	.q2-card.correct {
		border-color: rgba(24, 160, 88, 0.2);
		background: linear-gradient(180deg, #fff, #f6fdf8);
	}

	.q2-card.wrong {
		border-color: rgba(216, 64, 64, 0.2);
		background: linear-gradient(180deg, #fff, #fef6f6);
	}

	.q2-card-inner {
		padding: 20px 22px 20px 26px;
	}

	/* Top row */
	.q2-card-top {
		display: flex;
		align-items: flex-start;
		justify-content: space-between;
		gap: 14px;
		margin-bottom: 16px;
	}

	.q2-card-main {
		display: flex;
		align-items: flex-start;
		gap: 14px;
		flex: 1;
	}

	.q2-num {
		width: 30px;
		height: 30px;
		border-radius: 9px;
		background: var(--q2-blue-soft);
		border: 1px solid var(--q2-blue-mid);
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 12px;
		font-weight: 700;
		color: var(--q2-blue);
		flex-shrink: 0;
		font-family: 'IBM Plex Mono', monospace;
	}

	.q2-num.n-green {
		background: var(--q2-green-soft);
		border-color: rgba(24, 160, 88, 0.25);
		color: var(--q2-green);
	}

	.q2-num.n-red {
		background: var(--q2-red-soft);
		border-color: rgba(216, 64, 64, 0.25);
		color: var(--q2-red);
	}

	.q2-question {
		font-size: 15px;
		font-weight: 600;
		line-height: 1.5;
		color: var(--q2-ink);
		flex: 1;
	}

	.q2-badge {
		display: inline-flex;
		align-items: center;
		gap: 5px;
		padding: 6px 13px;
		border-radius: 999px;
		font-size: 11px;
		font-weight: 700;
		text-transform: uppercase;
		letter-spacing: 0.06em;
		white-space: nowrap;
		flex-shrink: 0;
	}

	.badge-open {
		background: var(--q2-blue-soft);
		color: var(--q2-blue);
		border: 1px solid var(--q2-blue-mid);
	}

	.badge-wait {
		background: var(--q2-amber-soft);
		color: var(--q2-amber);
		border: 1px solid rgba(217, 119, 6, 0.2);
	}

	.badge-won {
		background: var(--q2-green-soft);
		color: var(--q2-green);
		border: 1px solid rgba(24, 160, 88, 0.25);
	}

	.badge-lost {
		background: var(--q2-red-soft);
		color: var(--q2-red);
		border: 1px solid rgba(216, 64, 64, 0.25);
	}

	/* Odds row */
	.q2-odds {
		display: flex;
		align-items: center;
		gap: 10px;
		margin-bottom: 14px;
		flex-wrap: wrap;
	}

	.q2-odd {
		display: inline-flex;
		align-items: center;
		gap: 8px;
		padding: 8px 14px;
		border-radius: 12px;
		font-size: 13px;
		font-weight: 600;
	}

	.odd-yes {
		background: var(--q2-green-soft);
		color: var(--q2-green);
		border: 1px solid rgba(24, 160, 88, 0.18);
	}

	.odd-no {
		background: var(--q2-red-soft);
		color: var(--q2-red);
		border: 1px solid rgba(216, 64, 64, 0.18);
	}

	.q2-odd-val {
		font-family: 'IBM Plex Mono', monospace;
		font-weight: 600;
	}

	.q2-separator {
		width: 4px;
		height: 4px;
		border-radius: 50%;
		background: var(--q2-line);
	}

	/* Users joined strip */
	.q2-users-bar {
		display: flex;
		align-items: center;
		gap: 8px;
		padding: 10px 14px;
		border-radius: 12px;
		background: linear-gradient(90deg, #fff1f2, #fce8e8);
		border: 1px solid rgba(216, 64, 64, 0.12);
		margin-bottom: 14px;
		width: fit-content;
	}

	.q2-users-bar i {
		font-size: 13px;
		color: var(--q2-red);
	}

	.q2-users-bar strong {
		font-size: 13px;
		font-weight: 700;
		color: var(--q2-red);
	}

	.q2-users-bar span {
		font-size: 12px;
		color: #c27070;
		font-weight: 500;
	}

	/* Footer row */
	.q2-card-foot {
		display: flex;
		align-items: center;
		justify-content: space-between;
		padding-top: 14px;
		border-top: 1px solid var(--q2-line);
	}

	.q2-foot-left {
		display: flex;
		align-items: center;
		gap: 8px;
		font-size: 12px;
		color: var(--q2-muted);
		font-weight: 500;
	}

	.q2-foot-left i {
		font-size: 11px;
	}

	.q2-foot-right {
		font-family: 'IBM Plex Mono', monospace;
		font-size: 13px;
		font-weight: 600;
		color: var(--q2-text);
	}

	.q2-foot-right.win {
		color: var(--q2-green);
	}

	.q2-foot-right.loss {
		color: var(--q2-red);
	}

	/* Hover arrow */
	.q2-arrow-wrap {
		display: flex;
		align-items: center;
		gap: 6px;
		font-size: 12px;
		color: var(--q2-blue);
		font-weight: 600;
		opacity: 0;
		transition: opacity 0.2s;
	}

	.q2-card:hover .q2-arrow-wrap {
		opacity: 1;
	}

	/* ── EMPTY STATES ─────────────────────────────────── */
	.q2-empty {
		text-align: center;
		padding: 60px 24px;
		border-radius: 20px;
		border: 1.5px dashed var(--q2-line);
		background: var(--q2-card);
	}

	.q2-empty-icon {
		width: 60px;
		height: 60px;
		border-radius: 20px;
		background: var(--q2-blue-soft);
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 22px;
		color: var(--q2-blue);
		margin: 0 auto 16px;
	}

	.q2-empty h3 {
		font-size: 18px;
		font-weight: 700;
		margin-bottom: 8px;
	}

	.q2-empty p {
		font-size: 14px;
		color: var(--q2-muted);
		max-width: 320px;
		margin: 0 auto;
		line-height: 1.7;
	}

	@media (max-width: 680px) {
		.q2-stats {
			grid-template-columns: repeat(2, 1fr);
		}

		.q2-card-inner {
			padding: 16px 18px 16px 22px;
		}

		.q2-cat-header {
			flex-direction: column;
			align-items: flex-start;
		}
	}
</style>

<div class="q2-wrap">

	<?php if ($selected_category && !empty($selected_category->questions)): ?>

		<!-- CATEGORY HEADER -->
		<div class="q2-cat-header">
			<div class="q2-cat-left">
				<div class="q2-cat-icon"><i class="fa-solid fa-question"></i></div>
				<div>
					<div class="q2-cat-name"><?php echo html_escape($selected_category->name); ?> Questions</div>
					<div class="q2-cat-desc">This category is active now — select any question to trade</div>
				</div>
			</div>
			<div class="q2-status-pill">
				<div class="q2-status-dot"></div>
				Active category
			</div>
		</div>

		<!-- STATS BAR -->
		<div class="q2-stats">
			<div class="q2-stat">
				<div class="q2-stat-num col-default"><?php echo (int) $total_questions; ?></div>
				<div class="q2-stat-label">Total</div>
			</div>
			<div class="q2-stat">
				<div class="q2-stat-num col-blue"><?php echo (int) $answered_count; ?></div>
				<div class="q2-stat-label">Answered</div>
			</div>
			<div class="q2-stat">
				<div class="q2-stat-num col-green"><?php echo (int) $correct_count; ?></div>
				<div class="q2-stat-label">Correct</div>
			</div>
			<div class="q2-stat">
				<div class="q2-stat-num col-red"><?php echo (int) $wrong_count; ?></div>
				<div class="q2-stat-label">Wrong</div>
			</div>
		</div>

		<!-- QUESTION LIST -->
		<div class="q2-list">
			<?php foreach ($selected_category->questions as $index => $question_item): ?>
				<?php
				$answer_state  = isset($user_answers[(int) $question_item->id]) ? $user_answers[(int) $question_item->id] : NULL;
				$status        = 'pending';
				$status_label  = 'Open';
				$badge_class   = 'badge-open';
				$amount_text   = 'Not answered';
				$amount_class  = '';

				if ($answer_state) {
					if (!empty($answer_state->settled_at)) {
						$is_correct   = strtolower((string) $answer_state->answer) === strtolower((string) $question_item->answer_key);
						$status       = $is_correct ? 'correct' : 'wrong';
						$status_label = $is_correct ? 'Won' : 'Lost';
						$badge_class  = $is_correct ? 'badge-won' : 'badge-lost';
						$amount_text  = $is_correct ? '₹' . number_format((float) $answer_state->payout_amount, 2) : '₹0.00';
						$amount_class = $is_correct ? 'win' : 'loss';
					} else {
						$status       = 'pending';
						$status_label = 'Waiting';
						$badge_class  = 'badge-wait';
						$amount_text  = 'Stake ₹' . number_format((float) $answer_state->stake_amount, 2);
					}
				}

				$num_class = ($status === 'correct') ? 'n-green' : (($status === 'wrong') ? 'n-red' : '');
				?>
				<a class="q2-card <?php echo $status; ?>" href="<?php echo site_url('questions/answer/' . (int) $question_item->id); ?>">
					<div class="q2-card-inner">
						<div class="q2-card-top">
							<div class="q2-card-main">
								<div class="q2-num <?php echo $num_class; ?>"><?php echo $index + 1; ?></div>
								<div class="q2-question"><?php echo html_escape($question_item->question); ?></div>
							</div>
							<span class="q2-badge <?php echo $badge_class; ?>">
								<?php if ($status === 'correct'): ?><i class="fa-solid fa-check"></i>
								<?php elseif ($status === 'wrong'): ?><i class="fa-solid fa-xmark"></i>
								<?php elseif ($status_label === 'Waiting'): ?><i class="fa-solid fa-clock"></i>
								<?php else: ?><i class="fa-solid fa-circle" style="font-size:7px"></i>
								<?php endif; ?>
								<?php echo $status_label; ?>
							</span>
						</div>

						<div class="q2-odds">
							<div class="q2-odd odd-yes">
								<span>YES</span>
								<span class="q2-odd-val">₹<?php echo number_format((float) $question_item->yes_price, 2); ?></span>
							</div>
							<div class="q2-separator"></div>
							<div class="q2-odd odd-no">
								<span>NO</span>
								<span class="q2-odd-val">₹<?php echo number_format((float) $question_item->no_price, 2); ?></span>
							</div>
						</div>

						<div class="q2-users-bar">
							<i class="fa fa-users"></i>
							<strong><?php echo (int) (isset($question_item->total_users) ? $question_item->total_users : 0); ?></strong>
							<span>users joined</span>
						</div>

						<div class="q2-card-foot">
							<div class="q2-foot-left">
								<?php if ($answer_state): ?>
									<i class="fa-solid fa-lock"></i> Answer locked
								<?php else: ?>
									<i class="fa-solid fa-arrow-pointer"></i> Open to trade
								<?php endif; ?>
							</div>
							<div style="display:flex;align-items:center;gap:14px">
								<span class="q2-foot-right <?php echo $amount_class; ?>"><?php echo $amount_text; ?></span>
								<span class="q2-arrow-wrap">Trade <i class="fa-solid fa-arrow-right"></i></span>
							</div>
						</div>
					</div>
				</a>
			<?php endforeach; ?>
		</div>

	<?php elseif ($selected_category): ?>

		<div class="q2-empty">
			<div class="q2-empty-icon"><i class="fa-solid fa-inbox"></i></div>
			<h3>No Questions Found</h3>
			<p>This category has no questions yet. Please choose another category from the list above.</p>
		</div>

	<?php else: ?>

		<div class="q2-empty">
			<div class="q2-empty-icon"><i class="fa-solid fa-grid-2"></i></div>
			<h3>Pick a Category</h3>
			<p>Select any category capsule above and all its questions will load here instantly — no page refresh needed.</p>
		</div>

	<?php endif; ?>

</div>