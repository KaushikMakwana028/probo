<?php
$answer_error_flash = $this->session->flashdata('error');
if ($answer_error_flash === 'This market is not open for trading right now.' && !empty($market_is_open)) {
	$answer_error_flash = '';
}
?>
<?php if ($answer_error_flash): ?>
	<div class="answer-flash answer-flash-error"><?php echo $answer_error_flash; ?></div>
<?php endif; ?>

<?php if ($this->session->flashdata('success')): ?>
	<div class="answer-flash answer-flash-success"><?php echo $this->session->flashdata('success'); ?></div>
<?php endif; ?>

<?php
$selected_answer = $selected_answer_state ? strtolower((string) $selected_answer_state->answer) : 'yes';
$is_locked = $selected_answer_state !== NULL;
$is_settled = $is_locked && !empty($selected_answer_state->settled_at);
$is_correct = $is_settled && $selected_answer === strtolower((string) $selected_question->answer_key);
$yes_price = (float) $selected_question->yes_price;
$no_price = (float) $selected_question->no_price;
$market_total = max(1.0, $yes_price + $no_price);
$default_price = $selected_answer_state ? (float) $selected_answer_state->price : ($selected_answer === 'no' ? $no_price : $yes_price);
$default_price = $default_price > 0 ? $default_price : max($yes_price, $no_price, 0.5);
$default_quantity = $selected_answer_state ? max(1, (int) $selected_answer_state->quantity) : 1;
$price_max = max(0.5, $market_total - 0.5);
$winning_preview = round($default_price * $default_quantity * 1.25, 2);
$formula_text = 'Winning Amount = Selected Price x Quantity x 1.25';
?>

<style>
	.answer-flash {
		padding: 14px 18px;
		border-radius: 14px;
		margin-bottom: 20px;
		font-weight: 600
	}

	.answer-flash-error {
		background: #fef2f2;
		border: 1px solid #fecaca;
		color: #991b1b
	}

	.answer-flash-success {
		background: #f0fdf4;
		border: 1px solid #bbf7d0;
		color: #166534
	}

	.answer-page {
		display: grid;
		gap: 24px
	}

	.answer-hero {
		position: relative;
		display: flex;
		justify-content: space-between;
		gap: 20px;
		align-items: flex-start;
		padding: 32px;
		border-radius: 30px;
		background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 38%, #2563eb 72%, #38bdf8 100%);
		color: #fff;
		box-shadow: 0 20px 40px rgba(29, 78, 216, .16);
		overflow: hidden
	}

	.answer-hero:before,
	.answer-hero:after {
		content: "";
		position: absolute;
		border-radius: 999px;
		background: rgba(255, 255, 255, .1)
	}

	.answer-hero:before {
		width: 220px;
		height: 220px;
		top: -110px;
		right: 130px
	}

	.answer-hero:after {
		width: 140px;
		height: 140px;
		bottom: -70px;
		right: -10px
	}

	.answer-hero>* {
		position: relative;
		z-index: 1
	}

	.answer-hero h1 {
		margin: 0 0 10px;
		color: #fff;
		font-size: 36px
	}

	.answer-hero p {
		margin: 0;
		color: rgba(255, 255, 255, .84);
		max-width: 720px;
		line-height: 1.7
	}

	.answer-hero-actions {
		display: flex;
		gap: 12px;
		flex-wrap: wrap;
		align-self: center
	}

	.answer-hero-btn {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		padding: 13px 20px;
		border-radius: 16px;
		text-decoration: none;
		font-size: 14px;
		font-weight: 800;
		transition: all .2s ease
	}

	.answer-hero-btn.light {
		background: rgba(255, 255, 255, .14);
		color: #fff;
		border: 1px solid rgba(255, 255, 255, .22)
	}

	.answer-hero-btn.white {
		background: #fff;
		color: #1d4ed8;
		box-shadow: 0 8px 20px rgba(255, 255, 255, .18)
	}

	.answer-hero-btn:hover {
		transform: translateY(-1px)
	}

	.answer-layout {
		display: grid;
		grid-template-columns: minmax(0, 1.2fr) 360px;
		gap: 22px
	}

	.answer-main,
	.answer-side {
		background: #fff;
		border: 1px solid #e2e8f0;
		border-radius: 28px;
		padding: 26px;
		box-shadow: 0 14px 30px rgba(15, 23, 42, .05)
	}

	.answer-main h3,
	.answer-side h3 {
		margin: 0 0 8px;
		color: #111827;
		font-size: 28px
	}

	.answer-main-sub,
	.answer-side p {
		margin: 0;
		color: #64748b;
		line-height: 1.7
	}

	.answer-result {
		display: flex;
		justify-content: space-between;
		gap: 16px;
		align-items: center;
		padding: 20px 22px;
		border-radius: 24px;
		margin: 22px 0;
		border: 1px solid transparent
	}

	.answer-result.correct {
		background: linear-gradient(135deg, #ecfdf5 0%, #f0fdf4 100%);
		border-color: #86efac
	}

	.answer-result.wrong {
		background: linear-gradient(135deg, #fff1f2 0%, #fef2f2 100%);
		border-color: #fda4af
	}

	.answer-result h4 {
		margin: 0 0 6px;
		font-size: 20px
	}

	.answer-result p {
		margin: 0;
		color: #475569
	}

	.answer-result strong {
		font-size: 30px
	}

	.answer-result.correct strong {
		color: #15803d
	}

	.answer-result.wrong strong {
		color: #dc2626
	}

	.answer-choice-grid {
		display: grid;
		grid-template-columns: repeat(2, minmax(0, 1fr));
		gap: 14px;
		margin: 22px 0
	}

	.answer-option {
		position: relative
	}

	.answer-option input {
		position: absolute;
		opacity: 0;
		pointer-events: none
	}

	.answer-label {
		display: flex;
		justify-content: space-between;
		gap: 12px;
		align-items: center;
		padding: 20px 22px;
		border-radius: 24px;
		border: 1px solid #dbe2ea;
		background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
		cursor: pointer;
		font-weight: 800;
		color: #0f172a;
		transition: all .2s ease;
		box-shadow: 0 6px 16px rgba(15, 23, 42, .04)
	}

	.answer-label small {
		display: block;
		margin-top: 4px;
		font-size: 12px;
		font-weight: 600;
		color: #64748b
	}

	.answer-option input:checked+.answer-label {
		border-color: #2563eb;
		background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
		color: #fff;
		box-shadow: 0 12px 24px rgba(37, 99, 235, .16)
	}

	.answer-option.answer-no input:checked+.answer-label {
		background: linear-gradient(135deg, #0f172a 0%, #334155 100%);
		border-color: #0f172a
	}

	.answer-option input:checked+.answer-label small {
		color: rgba(255, 255, 255, .8)
	}

	.answer-option.locked .answer-label {
		cursor: not-allowed;
		opacity: .7
	}

	.answer-control-grid {
		display: grid;
		grid-template-columns: repeat(2, minmax(0, 1fr));
		gap: 16px
	}

	.answer-control {
		padding: 22px;
		border-radius: 24px;
		background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
		border: 1px solid #e2e8f0
	}

	.answer-control-head {
		display: flex;
		justify-content: space-between;
		gap: 12px;
		align-items: center;
		margin-bottom: 14px
	}

	.answer-control-head span {
		font-size: 12px;
		text-transform: uppercase;
		letter-spacing: .12em;
		color: #64748b;
		font-weight: 700
	}

	.answer-control-head strong {
		font-size: 30px;
		color: #111827
	}

	.answer-stepper {
		display: grid;
		grid-template-columns: 44px 1fr 44px;
		gap: 10px;
		align-items: center
	}

	.answer-stepper button {
		width: 44px;
		height: 44px;
		border-radius: 14px;
		border: 1px solid #dbe2ea;
		background: #fff;
		color: #2563eb;
		font-size: 24px;
		cursor: pointer
	}

	.answer-stepper button:disabled {
		opacity: .5;
		cursor: not-allowed
	}

	.answer-stepper input[type=range] {
		width: 100%;
		accent-color: #2563eb
	}

	.answer-stepper input[type=number] {
		width: 100%;
		padding: 11px 12px;
		border-radius: 14px;
		border: 1px solid #d1d5db;
		text-align: center;
		font-size: 18px;
		font-weight: 700;
		background: #fff
	}

	.answer-stepper input[type=number]:focus {
		outline: none;
		border-color: #2563eb;
		box-shadow: 0 0 0 4px rgba(37, 99, 235, .1)
	}

	.answer-summary {
		display: grid;
		grid-template-columns: repeat(2, minmax(0, 1fr));
		gap: 14px;
		margin: 20px 0
	}

	.answer-summary-card {
		padding: 20px;
		border-radius: 22px;
		background: #fff;
		border: 1px solid #e2e8f0;
		box-shadow: 0 6px 16px rgba(15, 23, 42, .03)
	}

	.answer-summary-card span {
		display: block;
		margin-bottom: 8px;
		font-size: 12px;
		text-transform: uppercase;
		letter-spacing: .12em;
		color: #64748b;
		font-weight: 700
	}

	.answer-summary-card strong {
		font-size: 25px;
		color: #0f172a
	}

	.answer-summary-card.highlight {
		background: linear-gradient(135deg, #eff6ff 0%, #eef2ff 100%);
		border-color: #bfdbfe
	}

	.answer-info-card {
		padding: 22px;
		border-radius: 24px;
		background: #f8fafc;
		border: 1px solid #e2e8f0
	}

	.answer-info-card h4 {
		margin: 0 0 10px;
		color: #111827;
		font-size: 18px
	}

	.answer-info-card p {
		margin: 0;
		color: #475569
	}

	.answer-info-card strong {
		color: #1d4ed8
	}

	.answer-submit-bar {
		display: flex;
		justify-content: space-between;
		gap: 18px;
		align-items: center;
		padding: 20px 22px;
		border-radius: 24px;
		background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
		color: #fff;
		margin-top: 22px
	}

	.answer-submit-bar p {
		margin: 0;
		color: rgba(255, 255, 255, .76)
	}

	.answer-submit-bar strong {
		display: block;
		margin-bottom: 4px;
		color: #fff;
		font-size: 20px
	}

	.answer-submit-btn {
		padding: 15px 24px;
		border: 0;
		border-radius: 16px;
		background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%);
		color: #fff;
		font-size: 15px;
		font-weight: 800;
		cursor: pointer;
		box-shadow: 0 12px 24px rgba(79, 70, 229, .2)
	}

	.answer-submit-btn:disabled {
		opacity: .5;
		cursor: not-allowed
	}

	.answer-lock-note {
		padding: 18px 20px;
		border-radius: 22px;
		background: #f8fafc;
		border: 1px dashed #cbd5e1;
		color: #475569;
		margin-top: 22px
	}

	.answer-side-stats {
		display: grid;
		gap: 14px;
		margin-top: 20px
	}

	.answer-side-stat {
		padding: 20px;
		border-radius: 22px;
		background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
		border: 1px solid #e2e8f0
	}

	.answer-side-stat span {
		display: block;
		margin-bottom: 8px;
		font-size: 12px;
		text-transform: uppercase;
		letter-spacing: .12em;
		color: #64748b;
		font-weight: 700
	}

	.answer-side-stat strong {
		font-size: 30px;
		color: #111827
	}

	.answer-side-list {
		margin-top: 18px;
		display: grid;
		gap: 12px
	}

	.answer-side-link {
		display: block;
		padding: 16px 18px;
		border-radius: 20px;
		text-decoration: none;
		border: 1px solid #e2e8f0;
		background: #fff;
		color: #0f172a;
		box-shadow: 0 6px 14px rgba(15, 23, 42, .03);
		transition: all .2s ease
	}

	.answer-side-link.active {
		border-color: #2563eb;
		background: #eff6ff;
		box-shadow: 0 12px 24px rgba(37, 99, 235, .1)
	}

	.answer-side-link small {
		display: block;
		color: #64748b;
		margin-top: 4px
	}

	.answer-side-link.correct {
		background: #ecfdf5;
		border-color: #86efac
	}

	.answer-side-link.wrong {
		background: #fff1f2;
		border-color: #fda4af
	}

	.answer-side-link:hover {
		transform: translateY(-1px)
	}

	.market-chart{margin-top:14px;display:grid;gap:14px}
	.market-chart-head{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap}
	.market-chart-head strong{font-size:14px;color:#0f172a}
	.market-chart-legend{display:flex;align-items:center;gap:10px;flex-wrap:wrap}
	.market-chart-pill{display:inline-flex;align-items:center;gap:8px;padding:8px 12px;border-radius:999px;background:#fff;border:1px solid #e2e8f0;font-size:12px;font-weight:800;letter-spacing:.08em;color:#475569}
	.market-chart-pill::before{content:"";width:10px;height:10px;border-radius:999px;display:inline-block}
	.market-chart-pill.yes::before{background:#2563eb}
	.market-chart-pill.no::before{background:#f97316}
	.market-chart-shell{border-radius:22px;border:1px solid #dbeafe;background:linear-gradient(180deg,#ffffff 0%,#f8fbff 100%);padding:10px}
	.market-chart-svg{width:100%;min-height:150px}

	@media (max-width:1100px) {
		.answer-layout {
			grid-template-columns: 1fr
		}

		.answer-hero,
		.answer-submit-bar {
			flex-direction: column;
			align-items: stretch
		}
	}

	@media (max-width:720px) {

		.answer-choice-grid,
		.answer-control-grid,
		.answer-summary {
			grid-template-columns: 1fr
		}

		.answer-stepper {
			grid-template-columns: 40px 1fr 40px
		}

		.answer-stepper button {
			width: 40px;
			height: 40px
		}
	}
</style>

<div class="answer-page">
	<section class="answer-hero">
		<div>
			<h1>Answer Question</h1>
			<p>Choose your side, adjust the price and quantity, then submit one final answer. After submission the question is locked and the result card clearly shows whether you won or lost.</p>
		</div>
		<div class="answer-hero-actions">
			<a class="answer-hero-btn light" href="<?php echo site_url('questions?category_id=' . (int) $selected_category->id); ?>">Back to Questions</a>
			<a class="answer-hero-btn white" href="<?php echo site_url('wallet'); ?>">Open Wallet</a>
		</div>
	</section>

	<div class="answer-layout">
		<section class="answer-main" data-answer-stage data-default-yes="<?php echo number_format($yes_price, 2, '.', ''); ?>" data-default-no="<?php echo number_format($no_price, 2, '.', ''); ?>" data-market-total="<?php echo number_format($market_total, 2, '.', ''); ?>">
			<h3><?php echo html_escape($selected_question->question); ?></h3>
			<p class="answer-main-sub"><?php echo html_escape($selected_category->name); ?> market. The selection cards, amount controls, and winning preview are arranged below in a cleaner flow.</p>

			<?php if ($is_locked): ?>
				<div class="answer-result <?php echo $is_settled ? ($is_correct ? 'correct' : 'wrong') : 'correct'; ?>">
					<div>
						<h4><?php echo $is_settled ? ($is_correct ? 'Winning Answer' : 'Wrong Answer') : 'Trade Submitted'; ?></h4>
						<p>
							Your submitted answer was <strong><?php echo strtoupper($selected_answer); ?></strong>.
							<?php echo $is_settled ? ($is_correct ? 'This matched the result, so the winning amount was added to your wallet.' : 'This did not match the result, so no winning amount was credited.') : 'The stake was deducted from your wallet. Result payout will be credited after admin resolves the market.'; ?>
						</p>
					</div>
					<strong><?php echo $is_settled ? ($is_correct ? '+Rs ' . number_format((float) $selected_answer_state->payout_amount, 2) : 'Rs 0.00') : 'Rs ' . number_format((float) $selected_answer_state->stake_amount, 2); ?></strong>
				</div>
			<?php endif; ?>

			<form method="post" action="<?php echo site_url('questions/save-answers'); ?>">
				<input type="hidden" name="category_id" value="<?php echo (int) $selected_category->id; ?>">
				<input type="hidden" name="question_id" value="<?php echo (int) $selected_question->id; ?>">
				<input type="hidden" name="price" class="price-hidden" value="<?php echo number_format($default_price, 2, '.', ''); ?>">

				<div class="answer-choice-grid">
					<div class="answer-option<?php echo $is_locked ? ' locked' : ''; ?>">
						<input type="radio" id="answer_yes" name="answer" value="yes" <?php echo $selected_answer === 'yes' ? 'checked' : ''; ?> <?php echo $is_locked ? 'disabled' : ''; ?>>
						<label class="answer-label" for="answer_yes">
							<div>
								Yes
								<small>Base price Rs <?php echo number_format($yes_price, 2); ?></small>
							</div>
							<strong>YES</strong>
						</label>
					</div>
					<div class="answer-option answer-no<?php echo $is_locked ? ' locked' : ''; ?>">
						<input type="radio" id="answer_no" name="answer" value="no" <?php echo $selected_answer === 'no' ? 'checked' : ''; ?> <?php echo $is_locked ? 'disabled' : ''; ?>>
						<label class="answer-label" for="answer_no">
							<div>
								No
								<small>Base price Rs <?php echo number_format($no_price, 2); ?></small>
							</div>
							<strong>NO</strong>
						</label>
					</div>
				</div>

				<div class="answer-control-grid">
					<div class="answer-control">
						<div class="answer-control-head">
							<span>Selected Price</span>
							<strong class="price-display">Rs <?php echo number_format($default_price, 2); ?></strong>
						</div>
						<div class="answer-stepper">
							<button type="button" class="price-decrease" <?php echo $is_locked ? 'disabled' : ''; ?>>-</button>
							<input type="range" class="price-range" min="0.50" max="<?php echo number_format($price_max, 2, '.', ''); ?>" step="0.50" value="<?php echo number_format(min($default_price, $price_max), 2, '.', ''); ?>" <?php echo $is_locked ? 'disabled' : ''; ?>>
							<button type="button" class="price-increase" <?php echo $is_locked ? 'disabled' : ''; ?>>+</button>
						</div>
					</div>

					<div class="answer-control">
						<div class="answer-control-head">
							<span>Quantity</span>
							<strong class="qty-display"><?php echo (int) $default_quantity; ?></strong>
						</div>
						<div class="answer-stepper">
							<button type="button" class="qty-decrease" <?php echo $is_locked ? 'disabled' : ''; ?>>-</button>
							<input type="number" class="qty-input" name="quantity" min="1" step="1" value="<?php echo (int) $default_quantity; ?>" <?php echo $is_locked ? 'readonly' : ''; ?>>
							<button type="button" class="qty-increase" <?php echo $is_locked ? 'disabled' : ''; ?>>+</button>
						</div>
					</div>
				</div>

				<div class="answer-summary">
					<div class="answer-summary-card">
						<span>You Put</span>
						<strong class="you-put">Rs <?php echo number_format($default_price * $default_quantity, 2); ?></strong>
					</div>
					<div class="answer-summary-card highlight">
						<span><?php echo $is_locked && $is_settled ? 'Winning Amount' : 'Winning Preview'; ?></span>
						<strong class="you-get"><?php echo $is_locked && $is_settled ? 'Rs ' . number_format((float) $selected_answer_state->payout_amount, 2) : 'Rs ' . number_format($winning_preview, 2); ?></strong>
					</div>
				</div>

				<div class="answer-info-card">
					<h4>How winning amount is counted</h4>
					<p><strong><?php echo $formula_text; ?></strong></p>
					<p>
						If your submitted answer is correct, the app credits <strong class="formula-preview">Rs <?php echo number_format($winning_preview, 2); ?></strong> to your wallet.
						If your answer is wrong, the winning amount is <strong>Rs 0.00</strong>.
					</p>
				</div>

				<div class="answer-info-card">
					<h4>Live Price Trend</h4>
					<p>This chart uses the demand-based YES and NO price history recorded for this market.</p>
					<div class="market-chart" data-market-chart data-history='<?php echo json_encode($price_history); ?>'>
						<div class="market-chart-head">
							<strong>Demand-Based Market Movement</strong>
							<div class="market-chart-legend">
								<span class="market-chart-pill yes">YES</span>
								<span class="market-chart-pill no">NO</span>
							</div>
						</div>
						<div class="market-chart-shell">
							<div class="market-chart-svg"></div>
						</div>
					</div>
					<div class="answer-summary" style="margin-top:16px;">
						<div class="answer-summary-card">
							<span>YES Quantity</span>
							<strong><?php echo isset($trade_breakdown['yes_quantity']) ? (int) $trade_breakdown['yes_quantity'] : 0; ?></strong>
						</div>
						<div class="answer-summary-card">
							<span>NO Quantity</span>
							<strong><?php echo isset($trade_breakdown['no_quantity']) ? (int) $trade_breakdown['no_quantity'] : 0; ?></strong>
						</div>
					</div>
				</div>

				<?php if ($is_locked): ?>
					<div class="answer-lock-note">
						This trade has already been submitted and is now locked. You can go back to the question list and open another question from the same category.
					</div>
				<?php else: ?>
					<div class="answer-submit-bar">
						<div>
							<strong>Submit Final Answer</strong>
							<p>This saves the answer only for this question and then locks it from further changes.</p>
						</div>
						<button type="submit" class="answer-submit-btn">Save Answer</button>
					</div>
				<?php endif; ?>
			</form>
		</section>

		<aside class="answer-side">
			<h3><?php echo html_escape($selected_category->name); ?></h3>
			<p>Track your progress in this category and jump to another question whenever you want to answer the next one.</p>

			<div class="answer-side-stats">
				<div class="answer-side-stat">
					<span>Answered</span>
					<strong><?php echo (int) $answered_count; ?></strong>
				</div>
				<div class="answer-side-stat">
					<span>Correct</span>
					<strong><?php echo (int) $correct_count; ?></strong>
				</div>
				<div class="answer-side-stat">
					<span>Wrong</span>
					<strong><?php echo (int) $wrong_count; ?></strong>
				</div>
			</div>

			<div class="answer-side-list">
				<?php foreach ($selected_category->questions as $question_item): ?>
					<?php
					$link_answer = isset($user_answers[(int) $question_item->id]) ? $user_answers[(int) $question_item->id] : NULL;
					$link_status = '';

					if ($link_answer) {
						$link_status = strtolower((string) $link_answer->answer) === strtolower((string) $question_item->answer_key) ? 'correct' : 'wrong';
					}
					?>
					<a class="answer-side-link <?php echo ((int) $question_item->id === (int) $selected_question->id) ? 'active' : ''; ?> <?php echo $link_status; ?>" href="<?php echo site_url('questions/answer/' . (int) $question_item->id); ?>">
						<?php echo html_escape($question_item->question); ?>
						<small><?php echo $link_answer ? 'Answer locked' : 'Open question'; ?></small>
					</a>
				<?php endforeach; ?>
			</div>
		</aside>
	</div>
</div>

<script>
	document.addEventListener('DOMContentLoaded', function() {
		const stage = document.querySelector('[data-answer-stage]');
		if (!stage) {
			return;
		}

		const yesInput = document.getElementById('answer_yes');
		const noInput = document.getElementById('answer_no');
		const range = stage.querySelector('.price-range');
		const priceHidden = stage.querySelector('.price-hidden');
		const priceDisplay = stage.querySelector('.price-display');
		const qtyInput = stage.querySelector('.qty-input');
		const qtyDisplay = stage.querySelector('.qty-display');
		const youPut = stage.querySelector('.you-put');
		const youGet = stage.querySelector('.you-get');
		const formulaPreview = stage.querySelector('.formula-preview');
		const priceDecrease = stage.querySelector('.price-decrease');
		const priceIncrease = stage.querySelector('.price-increase');
		const qtyDecrease = stage.querySelector('.qty-decrease');
		const qtyIncrease = stage.querySelector('.qty-increase');
		const chartHost = document.querySelector('[data-market-chart]');

		function renderMarketChart() {
			if (!chartHost) {
				return;
			}

			const svgMount = chartHost.querySelector('.market-chart-svg');
			let points = [];

			try {
				points = JSON.parse(chartHost.getAttribute('data-history') || '[]');
			} catch (error) {
				points = [];
			}

			const valuesYes = points.map(function(item) {
				return parseFloat(item.yes_price || 0);
			});
			const valuesNo = points.map(function(item) {
				return parseFloat(item.no_price || 0);
			});
			const allValues = valuesYes.concat(valuesNo).filter(function(value) {
				return !isNaN(value);
			});

			if (!svgMount) {
				return;
			}

			if (!allValues.length) {
				svgMount.innerHTML = '<div style="display:flex;align-items:center;justify-content:center;min-height:150px;color:#64748b;font-size:14px;">No price history yet.</div>';
				return;
			}

			const width = 420;
			const height = 150;
			const paddingX = 18;
			const paddingTop = 18;
			const paddingBottom = 22;
			const min = Math.min.apply(null, allValues);
			const max = Math.max.apply(null, allValues.concat([1]));

			const normalizePoints = function(values) {
				return values.map(function(value, index) {
					const x = values.length === 1 ? width / 2 : paddingX + (index / (values.length - 1)) * (width - (paddingX * 2));
					const ratio = max === min ? 0.5 : (value - min) / (max - min);
					const y = height - paddingBottom - (ratio * (height - paddingTop - paddingBottom));
					return x.toFixed(2) + ',' + y.toFixed(2);
				}).join(' ');
			};

			svgMount.innerHTML = '<svg viewBox="0 0 ' + width + ' ' + height + '" width="100%" height="' + height + '" preserveAspectRatio="none"><defs><linearGradient id="yesLineAnswer" x1="0%" y1="0%" x2="100%" y2="0%"><stop offset="0%" stop-color="#2563eb"></stop><stop offset="100%" stop-color="#06b6d4"></stop></linearGradient><linearGradient id="noLineAnswer" x1="0%" y1="0%" x2="100%" y2="0%"><stop offset="0%" stop-color="#f97316"></stop><stop offset="100%" stop-color="#ef4444"></stop></linearGradient></defs><rect x="0" y="0" width="' + width + '" height="' + height + '" rx="18" fill="#f8fafc"></rect><line x1="' + paddingX + '" y1="' + (height - paddingBottom) + '" x2="' + (width - paddingX) + '" y2="' + (height - paddingBottom) + '" stroke="#cbd5e1" stroke-width="1"></line><line x1="' + paddingX + '" y1="' + Math.round((height - paddingBottom + paddingTop) / 2) + '" x2="' + (width - paddingX) + '" y2="' + Math.round((height - paddingBottom + paddingTop) / 2) + '" stroke="#e2e8f0" stroke-width="1" stroke-dasharray="4 4"></line><line x1="' + paddingX + '" y1="' + paddingTop + '" x2="' + (width - paddingX) + '" y2="' + paddingTop + '" stroke="#e2e8f0" stroke-width="1" stroke-dasharray="4 4"></line><polyline fill="none" stroke="url(#yesLineAnswer)" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" points="' + normalizePoints(valuesYes) + '"></polyline><polyline fill="none" stroke="url(#noLineAnswer)" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" points="' + normalizePoints(valuesNo) + '"></polyline></svg>';
		}

		renderMarketChart();

		if (!range || range.disabled) {
			return;
		}

		function formatMoney(value) {
			return 'Rs ' + Number(value).toFixed(2);
		}

		function selectedAnswer() {
			if (noInput && noInput.checked) {
				return 'no';
			}

			return 'yes';
		}

		function syncPriceFromAnswer() {
			const answer = selectedAnswer();
			const basePrice = parseFloat(stage.dataset[answer === 'no' ? 'defaultNo' : 'defaultYes'] || '0');
			range.value = Number(basePrice > 0 ? basePrice : 0.5).toFixed(2);
			updateValues();
		}

		function updateValues() {
			const currentPrice = Math.max(0, parseFloat(range.value || '0'));
			const marketTotal = Math.max(1, parseFloat(stage.dataset.marketTotal || '1'));
			const currentQty = Math.max(1, parseInt(qtyInput.value || '1', 10));
			const boundedPrice = Math.min(Math.max(0.5, currentPrice), Math.max(0.5, marketTotal - 0.5));
			const winningPreview = (boundedPrice * currentQty) * 1.25;

			range.value = boundedPrice.toFixed(2);
			priceHidden.value = boundedPrice.toFixed(2);
			qtyInput.value = currentQty;
			priceDisplay.textContent = formatMoney(boundedPrice);
			qtyDisplay.textContent = currentQty;
			youPut.textContent = formatMoney(boundedPrice * currentQty);
			youGet.textContent = formatMoney(winningPreview);

			if (formulaPreview) {
				formulaPreview.textContent = formatMoney(winningPreview);
			}
		}

		if (yesInput) {
			yesInput.addEventListener('change', syncPriceFromAnswer);
		}

		if (noInput) {
			noInput.addEventListener('change', syncPriceFromAnswer);
		}

		range.addEventListener('input', updateValues);
		qtyInput.addEventListener('input', updateValues);

		priceDecrease.addEventListener('click', function() {
			range.value = Math.max(parseFloat(range.min), parseFloat(range.value) - parseFloat(range.step)).toFixed(2);
			updateValues();
		});

		priceIncrease.addEventListener('click', function() {
			range.value = Math.min(parseFloat(range.max), parseFloat(range.value) + parseFloat(range.step)).toFixed(2);
			updateValues();
		});

		qtyDecrease.addEventListener('click', function() {
			qtyInput.value = Math.max(1, parseInt(qtyInput.value || '1', 10) - 1);
			updateValues();
		});

		qtyIncrease.addEventListener('click', function() {
			qtyInput.value = Math.max(1, parseInt(qtyInput.value || '1', 10) + 1);
			updateValues();
		});

		updateValues();
	});
</script>
