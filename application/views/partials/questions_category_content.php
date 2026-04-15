<?php if ($selected_category && !empty($selected_category->questions)): ?>
	<section class="question-list-shell">
		<div class="question-list-head">
			<h3><?php echo html_escape($selected_category->name); ?> Questions</h3>
			<p>This category is active now. Every question appears in a cleaner single-column layout, so it is easier to scan and open.</p>
		</div>

		<div class="q-stats">
			<div class="q-stat">
				<span class="q-stat-label">Total Questions</span>
				<strong class="q-stat-val"><?php echo (int) $total_questions; ?></strong>
			</div>
			<div class="q-stat stat-pending">
				<span class="q-stat-label">Answered</span>
				<strong class="q-stat-val"><?php echo (int) $answered_count; ?></strong>
			</div>
			<div class="q-stat stat-correct">
				<span class="q-stat-label">Correct</span>
				<strong class="q-stat-val"><?php echo (int) $correct_count; ?></strong>
			</div>
			<div class="q-stat stat-wrong">
				<span class="q-stat-label">Wrong</span>
				<strong class="q-stat-val"><?php echo (int) $wrong_count; ?></strong>
			</div>
		</div>

		<div class="q-list">
			<?php foreach ($selected_category->questions as $index => $question_item): ?>
				<?php
				$answer_state = isset($user_answers[(int) $question_item->id]) ? $user_answers[(int) $question_item->id] : NULL;
				$status = 'pending';
				$status_label = 'Open';
				$amount_text = 'Not answered';

				if ($answer_state) {
					if (!empty($answer_state->settled_at)) {
						$is_correct = strtolower((string) $answer_state->answer) === strtolower((string) $question_item->answer_key);
						$status = $is_correct ? 'correct' : 'wrong';
						$status_label = $is_correct ? 'Won' : 'Lost';
						$amount_text = $is_correct ? 'Winning Rs ' . number_format((float) $answer_state->payout_amount, 2) : 'Winning Rs 0.00';
					} else {
						$status = 'pending';
						$status_label = 'Waiting';
						$amount_text = 'Stake Rs ' . number_format((float) $answer_state->stake_amount, 2);
					}
				}
				?>
				<a class="q-card <?php echo $status; ?>" href="<?php echo site_url('questions/answer/' . (int) $question_item->id); ?>">
					<div class="q-card-top">
						<div class="q-card-left">
							<span class="q-idx"><?php echo $index + 1; ?></span>
							<h4><?php echo html_escape($question_item->question); ?></h4>
						</div>
						<span class="q-status <?php echo $status; ?>"><?php echo $status_label; ?></span>
					</div>
					<div class="q-bet-options">
						<span class="q-bet q-bet-yes">
							<span class="q-dot q-dot-yes"></span>
							YES: Rs <?php echo number_format((float) $question_item->yes_price, 2); ?>
						</span>
						<span class="q-bet q-bet-no">
							<span class="q-dot q-dot-no"></span>
							NO: Rs <?php echo number_format((float) $question_item->no_price, 2); ?>
						</span>
					</div>
					<div class="q-card-foot">
						<span><?php echo $answer_state ? 'Answer locked' : 'Open answer screen'; ?></span>
						<span class="q-marks"><?php echo $amount_text; ?></span>
					</div>
				</a>
			<?php endforeach; ?>
		</div>
	</section>
<?php elseif ($selected_category): ?>
	<div class="q-empty">
		<h3>No Questions Found</h3>
		<p>This category does not have questions yet. Please choose another category.</p>
	</div>
<?php else: ?>
	<div class="q-empty">
		<h3>Start With A Category</h3>
		<p>Select a category capsule above and all questions from that category will load here without refreshing the page.</p>
	</div>
<?php endif; ?>
