<?php if ($selected_category && !empty($selected_category->questions)): ?>
	<section class="question-list-shell">
		<div class="question-list-head">
			<h3><?php echo html_escape($selected_category->name); ?> Questions</h3>
			<p>This category is active now. Every question appears in a cleaner single-column layout, so it is easier to scan and open.</p>
		</div>

		<div class="question-stats">
			<div class="question-stat">
				<span>Total Questions</span>
				<strong><?php echo (int) $total_questions; ?></strong>
			</div>
			<div class="question-stat">
				<span>Answered</span>
				<strong><?php echo (int) $answered_count; ?></strong>
			</div>
			<div class="question-stat">
				<span>Correct</span>
				<strong><?php echo (int) $correct_count; ?></strong>
			</div>
			<div class="question-stat">
				<span>Wrong</span>
				<strong><?php echo (int) $wrong_count; ?></strong>
			</div>
		</div>

		<div class="question-grid">
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
				<a class="question-card <?php echo $status; ?>" href="<?php echo site_url('questions/answer/' . (int) $question_item->id); ?>">
					<div class="question-card-top">
						<span class="question-card-index"><?php echo $index + 1; ?></span>
						<span class="question-status <?php echo $status; ?>"><?php echo $status_label; ?></span>
					</div>
					<h4><?php echo html_escape($question_item->question); ?></h4>
					<p>
						YES: Rs <?php echo number_format((float) $question_item->yes_price, 2); ?>
						&nbsp;&nbsp;|&nbsp;&nbsp;
						NO: Rs <?php echo number_format((float) $question_item->no_price, 2); ?>
					</p>
					<div class="question-card-foot">
						<span><?php echo $answer_state ? 'Answer locked' : 'Open answer screen'; ?></span>
						<span class="question-card-amount"><?php echo $amount_text; ?></span>
					</div>
				</a>
			<?php endforeach; ?>
		</div>
	</section>
<?php elseif ($selected_category): ?>
	<div class="question-empty">
		<h3>No Questions Found</h3>
		<p>This category does not have questions yet. Please choose another category.</p>
	</div>
<?php else: ?>
	<div class="question-empty">
		<h3>Start With A Category</h3>
		<p>Select a category capsule above and all questions from that category will load here without refreshing the page.</p>
	</div>
<?php endif; ?>
