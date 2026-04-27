<?php if ($this->session->flashdata('error')): ?>
	<div class="qd-flash qd-flash-err"><?php echo $this->session->flashdata('error'); ?></div>
<?php endif; ?>
<?php if ($this->session->flashdata('success')): ?>
	<div class="qd-flash qd-flash-ok"><?php echo $this->session->flashdata('success'); ?></div>
<?php endif; ?>

<?php
$detail_question = $selected_question;
$questions_in_category = $selected_category && !empty($selected_category->questions) ? $selected_category->questions : array();
$cat_q_count = count($questions_in_category);
$saved_count = 0;
$now_ts = time();

foreach ($questions_in_category as $question_item) {
	if (in_array(strtolower((string) $question_item->answer_key), array('yes', 'no'), TRUE)) {
		$saved_count++;
	}
}

$yes_qty    = isset($detail_question->trade_totals['yes_quantity']) ? (int) $detail_question->trade_totals['yes_quantity'] : 0;
$no_qty     = isset($detail_question->trade_totals['no_quantity'])  ? (int) $detail_question->trade_totals['no_quantity']  : 0;
$actual_yes_qty = isset($detail_question->trade_totals['actual_yes_quantity']) ? (int) $detail_question->trade_totals['actual_yes_quantity'] : $yes_qty;
$actual_no_qty  = isset($detail_question->trade_totals['actual_no_quantity']) ? (int) $detail_question->trade_totals['actual_no_quantity'] : $no_qty;
$admin_yes_qty  = isset($detail_question->trade_totals['admin_yes_quantity']) ? (int) $detail_question->trade_totals['admin_yes_quantity'] : 0;
$admin_no_qty   = isset($detail_question->trade_totals['admin_no_quantity']) ? (int) $detail_question->trade_totals['admin_no_quantity'] : 0;
$yes_users  = isset($detail_question->user_counts['yes_users'])     ? (int) $detail_question->user_counts['yes_users']     : 0;
$no_users   = isset($detail_question->user_counts['no_users'])      ? (int) $detail_question->user_counts['no_users']      : 0;
$real_users = isset($detail_question->real_users)                   ? (int) $detail_question->real_users                   : 0;

$market_total = (float) $detail_question->yes_price + (float) $detail_question->no_price;
$flow_total   = $yes_qty + $no_qty;
$spread_total = abs((float) $detail_question->yes_price - (float) $detail_question->no_price);
$is_saved     = in_array(strtolower((string) $detail_question->answer_key), array('yes', 'no'), TRUE);

$history_points = $this->Category_model->get_question_price_history((int) $detail_question->id, 12);

$start_ts = (!empty($detail_question->start_time) && $detail_question->start_time !== '0000-00-00 00:00:00') ? strtotime($detail_question->start_time) : FALSE;
$end_ts   = (!empty($detail_question->end_time)   && $detail_question->end_time   !== '0000-00-00 00:00:00') ? strtotime($detail_question->end_time)   : FALSE;

$timing_class = 'live';
$timing_label = 'Live';

if ($start_ts && $now_ts < $start_ts) {
	$timing_class = 'upcoming';
	$timing_label = 'Upcoming';
} elseif ($end_ts && $now_ts > $end_ts) {
	$timing_class = 'ended';
	$timing_label = 'Ended';
} elseif (strtolower((string) $detail_question->status) !== 'open') {
	$timing_class = 'ended';
	$timing_label = ucfirst((string) $detail_question->status);
}
?>

<style>
	/* ─── reset ────────────────────────────────────────── */
	*,
	*::before,
	*::after {
		box-sizing: border-box;
	}

	/* ─── page shell ────────────────────────────────────── */
	.qd-page {
		display: flex;
		flex-direction: column;
		gap: 16px;
		color: #0f172a;
	}

	/* ─── flash messages ────────────────────────────────── */
	.qd-flash {
		padding: 11px 16px;
		border-radius: 8px;
		margin-bottom: 4px;
		font-size: 12px;
		font-weight: 500;
	}

	.qd-flash-err {
		background: #fcebeb;
		border: 0.5px solid #f09595;
		color: #a32d2d;
	}

	.qd-flash-ok {
		background: #eaf3de;
		border: 0.5px solid #c0dd97;
		color: #3b6d11;
	}

	/* ─── shared card surface ───────────────────────────── */
	.qd-topbar,
	.qd-main,
	.qd-answer-box,
	.qd-chart-shell {
		background: #fff;
		border: 0.5px solid rgba(0, 0, 0, .12);
		border-radius: 12px;
	}

	/* ─── topbar ─────────────────────────────────────────── */
	.qd-topbar {
		padding: 18px 22px;
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 12px;
		flex-wrap: wrap;
	}

	.qd-topbar h2 {
		margin: 0 0 3px;
		font-size: 18px;
		font-weight: 500;
	}

	.qd-topbar p {
		margin: 0;
		font-size: 12px;
		color: #64748b;
	}

	.qd-back {
		display: inline-flex;
		align-items: center;
		gap: 7px;
		padding: 9px 14px;
		border-radius: 8px;
		background: #e6f1fb;
		border: 0.5px solid #b5d4f4;
		color: #185fa5;
		font-size: 12px;
		font-weight: 500;
		text-decoration: none;
	}

	/* ─── main section ───────────────────────────────────── */
	.qd-main {
		padding: 22px;
	}

	/* ─── question head ──────────────────────────────────── */
	.qd-head {
		display: flex;
		align-items: flex-start;
		justify-content: space-between;
		gap: 14px;
		flex-wrap: wrap;
		padding-bottom: 18px;
		border-bottom: 0.5px solid rgba(0, 0, 0, .10);
	}

	.qd-head h3 {
		margin: 0 0 10px;
		font-size: 20px;
		font-weight: 500;
		line-height: 1.45;
	}

	/* ─── badges ─────────────────────────────────────────── */
	.qd-badges {
		display: flex;
		flex-wrap: wrap;
		gap: 7px;
	}

	.qd-badge {
		display: inline-flex;
		align-items: center;
		gap: 5px;
		padding: 5px 10px;
		border-radius: 999px;
		font-size: 10px;
		font-weight: 500;
		border: 0.5px solid;
		text-transform: uppercase;
		letter-spacing: .04em;
	}

	.qd-badge .qd-dot {
		width: 6px;
		height: 6px;
		border-radius: 50%;
		background: currentColor;
		display: inline-block;
	}

	.qd-badge.saved {
		background: #eaf3de;
		border-color: #c0dd97;
		color: #3b6d11;
	}

	.qd-badge.pending {
		background: #faeeda;
		border-color: #fac775;
		color: #854f0b;
	}

	.qd-badge.live {
		background: #eaf3de;
		border-color: #c0dd97;
		color: #3b6d11;
	}

	.qd-badge.ended {
		background: #f1f5f9;
		border-color: rgba(0, 0, 0, .15);
		color: #64748b;
	}

	.qd-badge.upcoming {
		background: #faece7;
		border-color: #f5c4b3;
		color: #993c1d;
	}

	.qd-badge.neutral {
		background: #f8fafc;
		border-color: rgba(0, 0, 0, .12);
		color: #64748b;
	}

	/* ─── action buttons ─────────────────────────────────── */
	.qd-actions {
		display: flex;
		gap: 8px;
		flex-wrap: wrap;
	}

	.qd-btn {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		padding: 9px 13px;
		border-radius: 8px;
		font-size: 12px;
		font-weight: 500;
		text-decoration: none;
		border: 0.5px solid;
	}

	.qd-btn-info {
		background: #f8fafc;
		border-color: rgba(0, 0, 0, .15);
		color: #64748b;
	}

	.qd-btn-edit {
		background: #e6f1fb;
		border-color: #b5d4f4;
		color: #185fa5;
	}

	.qd-btn-del {
		background: #fcebeb;
		border-color: #f09595;
		color: #a32d2d;
	}

	/* ─── facts grid ─────────────────────────────────────── */
	.qd-facts {
		display: grid;
		grid-template-columns: repeat(2, minmax(0, 1fr));
		gap: 0 24px;
		padding: 18px 0;
		border-bottom: 0.5px solid rgba(0, 0, 0, .10);
	}

	.qd-fact {
		padding: 11px 0;
		border-bottom: 0.5px dashed rgba(0, 0, 0, .10);
	}

	.qd-fact:nth-last-child(-n+2) {
		border-bottom: none;
	}

	.qd-fact label {
		display: block;
		margin-bottom: 5px;
		font-size: 10px;
		font-weight: 500;
		letter-spacing: .08em;
		text-transform: uppercase;
		color: #94a3b8;
	}

	.qd-fact strong {
		display: block;
		font-size: 20px;
		font-weight: 500;
		line-height: 1.3;
		color: #111827;
		margin-bottom: 3px;
	}

	.qd-fact small {
		display: block;
		font-size: 11px;
		color: #64748b;
	}

	.qd-fact .yes {
		color: #0f6e56;
	}

	.qd-fact .no {
		color: #993c1d;
	}

	/* ─── chart ──────────────────────────────────────────── */
	.qd-chart-section {
		padding: 20px 0 0;
	}

	.qd-chart-section h4 {
		margin: 0 0 3px;
		font-size: 13px;
		font-weight: 500;
	}

	.qd-chart-section p {
		margin: 0 0 12px;
		font-size: 11px;
		color: #64748b;
	}

	.qd-chart-shell {
		padding: 14px 10px 10px;
		background: #f8fafc;
	}

	.qd-live-chart {
		min-height: 150px;
	}

	.qd-chart-legend {
		display: flex;
		align-items: center;
		gap: 14px;
		margin-top: 10px;
		justify-content: flex-end;
	}

	.qd-legend-item {
		display: flex;
		align-items: center;
		gap: 5px;
		font-size: 11px;
		color: #64748b;
	}

	.qd-legend-dot {
		width: 10px;
		height: 3px;
		border-radius: 2px;
		display: inline-block;
	}

	.qd-chart-foot {
		margin-top: 8px;
		display: flex;
		align-items: center;
		justify-content: space-between;
		font-size: 11px;
		color: #94a3b8;
	}

	/* ─── answer key box ─────────────────────────────────── */
	.qd-answer-box {
		padding: 18px;
		margin-top: 16px;
	}

	.qd-answer-box h4 {
		margin: 0 0 3px;
		font-size: 14px;
		font-weight: 500;
	}

	.qd-answer-box>p {
		margin: 0 0 14px;
		font-size: 11px;
		color: #64748b;
	}

	.qd-answer-row {
		display: grid;
		grid-template-columns: repeat(2, minmax(0, 1fr));
		gap: 10px;
		margin-top: 12px;
	}

	.qd-answer-choice {
		display: flex;
		align-items: center;
		gap: 10px;
		padding: 13px;
		border-radius: 8px;
		border: 0.5px solid rgba(0, 0, 0, .15);
		background: #f8fafc;
		cursor: pointer;
		font-size: 13px;
		font-weight: 500;
		color: #64748b;
	}

	.qd-answer-choice input[type="radio"] {
		width: 15px;
		height: 15px;
		margin: 0;
		accent-color: #378add;
	}

	.qd-answer-choice.selected-yes {
		border-color: #97c459;
		background: #eaf3de;
		color: #3b6d11;
	}

	.qd-answer-choice.selected-no {
		border-color: #f5c4b3;
		background: #faece7;
		color: #993c1d;
	}

	.qd-answer-footer {
		margin-top: 14px;
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 12px;
		flex-wrap: wrap;
	}

	.qd-answer-footer p {
		margin: 0;
		font-size: 11px;
		color: #64748b;
	}

	.qd-save-btn {
		padding: 10px 18px;
		border: none;
		border-radius: 8px;
		background: #378add;
		color: #fff;
		font-size: 12px;
		font-weight: 500;
		cursor: pointer;
	}

	.qd-save-btn:hover {
		background: #185fa5;
	}

	/* ─── responsive ─────────────────────────────────────── */
	@media (max-width: 600px) {

		.qd-main {
			padding: 14px;
		}

		.qd-head {
			flex-direction: column;
			gap: 10px;
		}

		.qd-head h3 {
			font-size: 16px;
		}

		.qd-badges {
			flex-wrap: wrap;
		}

		.qd-facts {
			grid-template-columns: 1fr;
		}

		.qd-fact strong {
			font-size: 14px;
		}

		.qd-answer-row {
			grid-template-columns: 1fr;
		}

		.qd-answer-choice {
			padding: 10px;
			font-size: 12px;
		}
	}
</style>

<div class="qd-page">

	<section class="qd-topbar">
		<div>
			<h2>Question details</h2>
			<p>Only the selected question is shown here.</p>
		</div>
		<a class="qd-back" href="<?php echo site_url('admin/questions/view?category_id=' . (int) $selected_category->id); ?>">
			<svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
				<path d="M10 13L5 8l5-5" />
			</svg>
			Back to list
		</a>
	</section>

	<section class="qd-main" data-question-card data-question-id="<?php echo (int) $detail_question->id; ?>">

		<div class="qd-head">
			<div style="flex:1;min-width:0;">
				<h3><?php echo html_escape($detail_question->question); ?></h3>
				<div class="qd-badges">
					<span class="qd-badge <?php echo $is_saved ? 'saved' : 'pending'; ?>">
						<span class="qd-dot"></span>
						<?php echo $is_saved ? 'Answer key saved' : 'Answer key pending'; ?>
					</span>
					<span class="qd-badge <?php echo $timing_class; ?>" id="qdTimingBadge">
						<span class="qd-dot"></span>
						<?php echo $timing_label; ?>
					</span>
					<span class="qd-badge neutral"><?php echo ucfirst((string) $detail_question->status); ?> market</span>
				</div>
			</div>
			<div class="qd-actions">
				<a class="qd-btn qd-btn-info" href="<?php echo site_url('admin/questions/detail-users/' . (int) $detail_question->id); ?>">
					<svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
						<circle cx="8" cy="8" r="6" />
						<path d="M8 7v4M8 5.5v.5" />
					</svg>
					Details
				</a>
				<a class="qd-btn qd-btn-edit" href="<?php echo site_url('admin/questions/edit/' . (int) $detail_question->id); ?>">
					<svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
						<path d="M11 2l3 3-9 9H2v-3l9-9z" />
					</svg>
					Edit
				</a>
				<a class="qd-btn qd-btn-del" href="<?php echo site_url('admin/questions/delete/' . (int) $detail_question->id); ?>" onclick="return confirm('Delete this question?');">
					<svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
						<path d="M3 4h10M6 4V2h4v2M5 4v9h6V4" />
					</svg>
					Delete
				</a>
			</div>
		</div>

		<div class="qd-facts">
			<div class="qd-fact">
				<label>Real joined users</label>
				<strong class="js-real-users"><?php echo $real_users; ?></strong>
				<small>Only real users, no extra admin count</small>
			</div>
			<div class="qd-fact">
				<label>Trade quantity</label>
				<strong class="js-trade-qty">Y <?php echo $yes_qty; ?> | N <?php echo $no_qty; ?></strong>
				<small>Total flow: <span class="js-flow-total"><?php echo $flow_total; ?></span> | Actual Y <?php echo $actual_yes_qty; ?> + <?php echo $admin_yes_qty; ?> | Actual N <?php echo $actual_no_qty; ?> + <?php echo $admin_no_qty; ?></small>
			</div>
			<div class="qd-fact">
				<label>Yes price</label>
				<strong class="yes js-yes-price">Rs <?php echo number_format((float) $detail_question->yes_price, 2); ?></strong>
				<small class="js-yes-users">YES users: <?php echo $yes_users; ?></small>
			</div>
			<div class="qd-fact">
				<label>No price</label>
				<strong class="no js-no-price">Rs <?php echo number_format((float) $detail_question->no_price, 2); ?></strong>
				<small class="js-no-users">NO users: <?php echo $no_users; ?></small>
			</div>
			<div class="qd-fact">
				<label>Market total</label>
				<strong class="js-market-total">Rs <?php echo number_format($market_total, 2); ?></strong>
				<small>Spread: <span class="js-spread">Rs <?php echo number_format($spread_total, 2); ?></span></small>
			</div>
			<div class="qd-fact">
				<label>Multiplier</label>
				<strong>×<?php echo number_format((float) (isset($detail_question->multiplier) ? $detail_question->multiplier : 1.25), 2); ?></strong>
				<small>Current reward multiplier</small>
			</div>
			<div class="qd-fact">
				<label>Start time</label>
				<strong style="font-size:15px;"><?php echo !empty($detail_question->start_time) ? html_escape($detail_question->start_time) : 'Not set'; ?></strong>
				<small>Market opening time</small>
			</div>
			<div class="qd-fact">
				<label>Close time</label>
				<strong style="font-size:15px;"><?php echo !empty($detail_question->end_time) ? html_escape($detail_question->end_time) : 'Not set'; ?></strong>
				<small>Market closing time</small>
			</div>
		</div>

		<div class="qd-chart-section">
			<h4>Live demand snapshot</h4>
			<p>Pricing history and live trade numbers — auto-refreshes every 12 seconds.</p>
			<div class="qd-chart-shell">
				<div class="qd-live-chart" data-history='<?php echo json_encode($history_points); ?>'></div>
				<div class="qd-chart-legend">
					<div class="qd-legend-item"><span class="qd-legend-dot" style="background:#378add;"></span>Yes price</div>
					<div class="qd-legend-item"><span class="qd-legend-dot" style="background:#ef9f27;"></span>No price</div>
				</div>
			</div>
			<div class="qd-chart-foot">
				<span>Auto-refreshes every 12 s</span>
			</div>
		</div>

		<form method="post" action="<?php echo site_url('admin/questions/save-answer-keys'); ?>" class="qd-answer-box">
			<h4>Save answer key</h4>
			<p>Choose the correct result. You stay on the same details page after saving.</p>

			<input type="hidden" name="category_id" value="<?php echo (int) $selected_category->id; ?>">
			<input type="hidden" name="question_id" value="<?php echo (int) $detail_question->id; ?>">

			<div class="qd-answer-row">
				<label class="qd-answer-choice <?php echo strtolower((string) $detail_question->answer_key) === 'yes' ? 'selected-yes' : ''; ?>"
					for="qd_yes_<?php echo (int) $detail_question->id; ?>">
					<input type="radio"
						id="qd_yes_<?php echo (int) $detail_question->id; ?>"
						name="answer_keys[<?php echo (int) $detail_question->id; ?>]"
						value="yes"
						<?php echo strtolower((string) $detail_question->answer_key) === 'yes' ? 'checked' : ''; ?>>
					Yes is correct
				</label>
				<label class="qd-answer-choice <?php echo strtolower((string) $detail_question->answer_key) === 'no' ? 'selected-no' : ''; ?>"
					for="qd_no_<?php echo (int) $detail_question->id; ?>">
					<input type="radio"
						id="qd_no_<?php echo (int) $detail_question->id; ?>"
						name="answer_keys[<?php echo (int) $detail_question->id; ?>]"
						value="no"
						<?php echo strtolower((string) $detail_question->answer_key) === 'no' ? 'checked' : ''; ?>>
					No is correct
				</label>
			</div>

			<div class="qd-answer-footer">
				<p><?php echo $saved_count; ?> of <?php echo $cat_q_count; ?> questions in this category already have saved keys.</p>
				<button type="submit" class="qd-save-btn">Save answer key</button>
			</div>
		</form>

	</section>
</div>

<script>
	(function() {
		'use strict';

		var categoryId = <?php echo (int) $selected_category->id; ?>;
		var questionId = <?php echo (int) $detail_question->id; ?>;
		var card = document.querySelector('[data-question-card]');
		var chart = card ? card.querySelector('.qd-live-chart') : null;

		function drawChart(container, points) {
			if (!container) return;
			points = Array.isArray(points) ? points : [];

			var yesVals = points.map(function(p) {
				return parseFloat(p.yes_price || 0);
			});
			var noVals = points.map(function(p) {
				return parseFloat(p.no_price || 0);
			});

			if (!yesVals.length) {
				container.innerHTML = '<div style="display:flex;align-items:center;justify-content:center;min-height:150px;color:#9ca3af;font-size:13px;">No history recorded yet.</div>';
				return;
			}

			var all = yesVals.concat(noVals).concat([0.01]);
			var minVal = Math.min.apply(null, all);
			var maxVal = Math.max.apply(null, all);
			var W = 560,
				H = 150,
				pX = 18,
				pY = 14;

			function pts(vals) {
				return vals.map(function(v, i) {
					var x = vals.length === 1 ? W / 2 : pX + (i / (vals.length - 1)) * (W - pX * 2);
					var r = maxVal === minVal ? 0.5 : (v - minVal) / (maxVal - minVal);
					var y = H - pY - r * (H - pY * 2);
					return x.toFixed(1) + ',' + y.toFixed(1);
				}).join(' ');
			}

			container.innerHTML =
				'<svg viewBox="0 0 ' + W + ' ' + H + '" width="100%" height="' + H + '" preserveAspectRatio="none">' +
				'<defs>' +
				'<linearGradient id="qdYes" x1="0%" y1="0%" x2="100%" y2="0%"><stop offset="0%" stop-color="#378add"/><stop offset="100%" stop-color="#85b7eb"/></linearGradient>' +
				'<linearGradient id="qdNo"  x1="0%" y1="0%" x2="100%" y2="0%"><stop offset="0%" stop-color="#ef9f27"/><stop offset="100%" stop-color="#fac775"/></linearGradient>' +
				'</defs>' +
				'<rect x="0" y="0" width="' + W + '" height="' + H + '" rx="6" fill="transparent"/>' +
				'<line x1="' + pX + '" y1="' + (H - pY) + '" x2="' + (W - pX) + '" y2="' + (H - pY) + '" stroke="rgba(0,0,0,.1)" stroke-width="0.5"/>' +
				'<line x1="' + pX + '" y1="' + Math.round(H / 2) + '" x2="' + (W - pX) + '" y2="' + Math.round(H / 2) + '" stroke="rgba(0,0,0,.08)" stroke-width="0.5" stroke-dasharray="4 4"/>' +
				'<polyline fill="none" stroke="url(#qdYes)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" points="' + pts(yesVals) + '"/>' +
				'<polyline fill="none" stroke="url(#qdNo)"  stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" points="' + pts(noVals) + '"/>' +
				'</svg>';
		}

		if (chart) {
			try {
				drawChart(chart, JSON.parse(chart.getAttribute('data-history') || '[]'));
			} catch (e) {
				drawChart(chart, []);
			}
		}

		/* answer choice highlight */
		document.querySelectorAll('.qd-answer-choice input[type="radio"]').forEach(function(radio) {
			radio.addEventListener('change', function() {
				var row = this.closest('.qd-answer-row');
				if (!row) return;
				row.querySelectorAll('.qd-answer-choice').forEach(function(l) {
					l.classList.remove('selected-yes', 'selected-no');
				});
				var lbl = this.closest('.qd-answer-choice');
				if (lbl) lbl.classList.add(this.value === 'yes' ? 'selected-yes' : 'selected-no');
			});
		});

		/* live refresh */
		function updateCard(q) {
			if (!card || !q) return;
			var yp = Number(q.yes_price || 0).toFixed(2);
			var np = Number(q.no_price || 0).toFixed(2);
			var mt = Number(q.market_total || 0).toFixed(2);
			var yq = q.trade_totals ? Number(q.trade_totals.yes_quantity || 0) : 0;
			var nq = q.trade_totals ? Number(q.trade_totals.no_quantity || 0) : 0;
			var ayq = q.trade_totals ? Number(q.trade_totals.actual_yes_quantity || 0) : yq;
			var anq = q.trade_totals ? Number(q.trade_totals.actual_no_quantity || 0) : nq;
			var exy = q.trade_totals ? Number(q.trade_totals.admin_yes_quantity || 0) : 0;
			var exn = q.trade_totals ? Number(q.trade_totals.admin_no_quantity || 0) : 0;
			var yu = q.trade_totals ? Number(q.trade_totals.yes_users || 0) : 0;
			var nu = q.trade_totals ? Number(q.trade_totals.no_users || 0) : 0;
			var ru = Number(q.real_users || 0);
			var spr = Math.abs(Number(q.yes_price || 0) - Number(q.no_price || 0)).toFixed(2);

			card.querySelectorAll('.js-yes-price').forEach(function(n) {
				n.textContent = 'Rs ' + yp;
			});
			card.querySelectorAll('.js-no-price').forEach(function(n) {
				n.textContent = 'Rs ' + np;
			});
			card.querySelectorAll('.js-market-total').forEach(function(n) {
				n.textContent = 'Rs ' + mt;
			});
			card.querySelectorAll('.js-trade-qty').forEach(function(n) {
				n.textContent = 'Y ' + yq + ' | N ' + nq;
			});
			card.querySelectorAll('.js-flow-total').forEach(function(n) {
				n.textContent = String(yq + nq);
			});
			var tradeFactSmall = card.querySelector('.js-flow-total');
			if (tradeFactSmall && tradeFactSmall.parentElement) {
				tradeFactSmall.parentElement.textContent = 'Total flow: ' + (yq + nq) + ' | Actual Y ' + ayq + ' + ' + exy + ' | Actual N ' + anq + ' + ' + exn;
			}
			card.querySelectorAll('.js-yes-users').forEach(function(n) {
				n.textContent = 'YES users: ' + yu;
			});
			card.querySelectorAll('.js-no-users').forEach(function(n) {
				n.textContent = 'NO users: ' + nu;
			});
			card.querySelectorAll('.js-real-users').forEach(function(n) {
				n.textContent = String(ru);
			});
			card.querySelectorAll('.js-spread').forEach(function(n) {
				n.textContent = 'Rs ' + spr;
			});
			drawChart(chart, q.price_history || []);
		}

		async function refreshLive() {
			try {
				var res = await fetch('<?php echo site_url('admin/questions/live-stats/'); ?>' + categoryId, {
					headers: {
						'X-Requested-With': 'XMLHttpRequest'
					}
				});
				var data = await res.json();
				if (!data || data.status !== 'ok' || !Array.isArray(data.questions)) return;
				data.questions.forEach(function(q) {
					if (Number(q.id) === questionId) updateCard(q);
				});
			} catch (e) {}
		}

		setInterval(refreshLive, 12000);
	}());
</script>
