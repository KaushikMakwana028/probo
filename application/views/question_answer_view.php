<?php
$answer_error_flash = $this->session->flashdata('error');
if ($answer_error_flash === 'This market is not open for trading right now.' && !empty($market_is_open)) {
	$answer_error_flash = '';
}
$selected_answer  = $selected_answer_state ? strtolower((string)$selected_answer_state->answer) : 'yes';
$is_locked        = $selected_answer_state !== NULL;
$is_settled       = $is_locked && !empty($selected_answer_state->settled_at);
$is_correct_ans   = $is_settled && $selected_answer === strtolower((string)$selected_question->answer_key);
$yes_price        = (float)$selected_question->yes_price;
$no_price         = (float)$selected_question->no_price;
$market_total     = max(1.0, $yes_price + $no_price);
$default_price    = $selected_answer_state ? (float)$selected_answer_state->price : ($selected_answer === 'no' ? $no_price : $yes_price);
$default_price    = $default_price > 0 ? $default_price : max($yes_price, $no_price, 0.5);
$default_quantity = $selected_answer_state ? max(1, min(1000, (int)$selected_answer_state->quantity)) : 1;
$price_max        = max(0.5, $market_total - 0.5);
$multiplier = isset($selected_question->multiplier) ? (float)$selected_question->multiplier : 1.25;

$winning_preview = round($default_price * $default_quantity * $multiplier, 2);
$yes_trade_qty    = isset($trade_breakdown['yes_quantity']) ? (int)$trade_breakdown['yes_quantity'] : 0;
$no_trade_qty     = isset($trade_breakdown['no_quantity'])  ? (int)$trade_breakdown['no_quantity']  : 0;
$total_trade_qty  = $yes_trade_qty + $no_trade_qty;
$yes_pct          = $market_total > 0 ? round($yes_price / $market_total * 100) : 50;
$no_pct           = 100 - $yes_pct;
$yes_vol_pct      = $total_trade_qty > 0 ? round($yes_trade_qty / $total_trade_qty * 100) : 50;
$no_vol_pct       = 100 - $yes_vol_pct;
$QTY_MAX          = 1000;
$QTY_MIN          = 1;
?>

<?php if ($answer_error_flash): ?>
	<div class="af af-err"><?php echo $answer_error_flash; ?></div>
<?php endif; ?>
<?php if ($this->session->flashdata('success')): ?>
	<div class="af af-ok"><?php echo $this->session->flashdata('success'); ?></div>
<?php endif; ?>

<style>
	*,
	*::before,
	*::after {
		box-sizing: border-box;
		margin: 0;
		padding: 0
	}

	.af {
		padding: 13px 18px;
		border-radius: 12px;
		margin-bottom: 18px;
		font-size: 14px;
		font-weight: 500
	}

	.af-err {
		background: rgba(239, 68, 68, .07);
		border: 0.5px solid rgba(239, 68, 68, .3);
		color: #dc2626
	}

	.af-ok {
		background: rgba(34, 197, 94, .07);
		border: 0.5px solid rgba(34, 197, 94, .3);
		color: #16a34a
	}

	.ap {
		display: grid;
		gap: 20px
	}

	/* back link */
	.ap-back {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		font-size: 13px;
		font-weight: 500;
		color: var(--color-text-secondary, #64748b);
		text-decoration: none;
		padding: 7px 14px;
		border-radius: 9px;
		border: 0.5px solid var(--color-border-secondary, #e2e8f0);
		background: var(--color-background-secondary, #f8fafc);
		transition: all .15s
	}

	.ap-back:hover {
		border-color: #3b82f6;
		color: #2563eb;
		background: rgba(59, 130, 246, .06)
	}

	/* hero */
	.ap-hero {
		padding: 26px 30px;
		border-radius: 20px;
		background: var(--color-background-secondary, #f8fafc);
		border: 0.5px solid var(--color-border-secondary, #e2e8f0);
		display: flex;
		align-items: flex-start;
		justify-content: space-between;
		gap: 20px;
		flex-wrap: wrap
	}

	.ap-hero-left h1 {
		font-size: 18px;
		font-weight: 500;
		color: var(--color-text-primary, #0f172a);
		line-height: 1.45;
		margin-bottom: 6px
	}

	.ap-hero-left p {
		font-size: 13px;
		color: var(--color-text-secondary, #64748b);
		line-height: 1.6;
		max-width: 600px
	}

	.ap-hero-meta {
		display: flex;
		gap: 8px;
		align-items: center;
		flex-shrink: 0;
		flex-wrap: wrap
	}

	.ap-status {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		padding: 5px 13px;
		border-radius: 999px;
		font-size: 11px;
		font-weight: 500
	}

	.ap-status.open {
		background: rgba(34, 197, 94, .1);
		color: #16a34a;
		border: 0.5px solid rgba(34, 197, 94, .3)
	}

	.ap-status.closed {
		background: rgba(100, 116, 139, .1);
		color: #475569;
		border: 0.5px solid rgba(100, 116, 139, .25)
	}

	.ap-status-pulse {
		width: 6px;
		height: 6px;
		border-radius: 50%;
		background: #22c55e;
		animation: apulse 1.4s ease-in-out infinite
	}

	@keyframes apulse {

		0%,
		100% {
			opacity: 1;
			transform: scale(1)
		}

		50% {
			opacity: .5;
			transform: scale(.7)
		}
	}

	.ap-cat-tag {
		padding: 5px 12px;
		border-radius: 999px;
		font-size: 11px;
		font-weight: 500;
		background: rgba(59, 130, 246, .08);
		color: #2563eb;
		border: 0.5px solid rgba(59, 130, 246, .2)
	}

	/* layout */
	.ap-layout {
		display: grid;
		grid-template-columns: minmax(0, 1fr) 308px;
		gap: 20px;
		align-items: start
	}

	/* card */
	.acard {
		border-radius: 16px;
		overflow: hidden;
		border: 0.5px solid var(--color-border-tertiary, #e2e8f0)
	}

	.acard+.acard {
		margin-top: 16px
	}

	.acard-head {
		padding: 14px 22px;
		background: var(--color-background-secondary, #f8fafc);
		border-bottom: 0.5px solid var(--color-border-tertiary, #e2e8f0)
	}

	.acard-head h2 {
		font-size: 14px;
		font-weight: 500;
		color: var(--color-text-primary, #0f172a);
		margin-bottom: 2px
	}

	.acard-head p {
		font-size: 12px;
		color: var(--color-text-tertiary, #94a3b8)
	}

	.acard-body {
		background: var(--color-background-primary, #fff);
		padding: 22px
	}

	/* result banner */
	.ap-result {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 16px;
		flex-wrap: wrap;
		padding: 18px 20px;
		border-radius: 13px;
		margin-bottom: 20px
	}

	.ap-result.pend {
		background: rgba(59, 130, 246, .05);
		border: 0.5px solid rgba(59, 130, 246, .2)
	}

	.ap-result.correct {
		background: rgba(34, 197, 94, .05);
		border: 0.5px solid rgba(34, 197, 94, .25)
	}

	.ap-result.wrong {
		background: rgba(239, 68, 68, .05);
		border: 0.5px solid rgba(239, 68, 68, .25)
	}

	.ap-result-eye {
		font-size: 11px;
		font-weight: 500;
		text-transform: uppercase;
		letter-spacing: .07em;
		margin-bottom: 5px
	}

	.ap-result.pend .ap-result-eye {
		color: #2563eb
	}

	.ap-result.correct .ap-result-eye {
		color: #16a34a
	}

	.ap-result.wrong .ap-result-eye {
		color: #dc2626
	}

	.ap-result h4 {
		font-size: 15px;
		font-weight: 500;
		color: var(--color-text-primary, #0f172a);
		margin-bottom: 4px
	}

	.ap-result p {
		font-size: 13px;
		color: var(--color-text-secondary, #64748b);
		line-height: 1.5
	}

	.ap-result-amt {
		font-size: 24px;
		font-weight: 600;
		flex-shrink: 0
	}

	.ap-result.pend .ap-result-amt {
		color: #2563eb
	}

	.ap-result.correct .ap-result-amt {
		color: #16a34a
	}

	.ap-result.wrong .ap-result-amt {
		color: #dc2626
	}

	/* prob bar */
	.ap-prob {
		margin-bottom: 18px
	}

	.ap-prob-labels {
		display: flex;
		justify-content: space-between;
		font-size: 12px;
		font-weight: 500;
		margin-bottom: 6px
	}

	.ap-prob-yes {
		color: #16a34a
	}

	.ap-prob-no {
		color: #dc2626
	}

	.ap-prob-bar {
		height: 7px;
		border-radius: 999px;
		overflow: hidden;
		background: var(--color-background-secondary, #f1f5f9);
		display: flex
	}

	.ap-prob-fill-y {
		height: 100%;
		background: #22c55e;
		border-radius: 999px 0 0 999px;
		transition: width .4s
	}

	.ap-prob-fill-n {
		height: 100%;
		background: #ef4444;
		border-radius: 0 999px 999px 0;
		transition: width .4s
	}

	/* price chips */
	.ap-chips {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 10px;
		margin-bottom: 18px
	}

	.ap-chip {
		padding: 14px 16px;
		border-radius: 12px;
		text-align: center;
		background: var(--color-background-secondary, #f8fafc);
		border: 0.5px solid var(--color-border-tertiary, #e2e8f0)
	}

	.ap-chip-lbl {
		display: block;
		font-size: 10px;
		text-transform: uppercase;
		letter-spacing: .09em;
		font-weight: 500;
		margin-bottom: 4px
	}

	.ap-chip-val {
		font-size: 20px;
		font-weight: 600
	}

	.chip-yes .ap-chip-lbl {
		color: #16a34a
	}

	.chip-yes .ap-chip-val {
		color: #16a34a
	}

	.chip-no .ap-chip-lbl {
		color: #dc2626
	}

	.chip-no .ap-chip-val {
		color: #dc2626
	}

	/* volume */
	.ap-vol {
		display: flex;
		align-items: center;
		gap: 10px;
		font-size: 12px
	}

	.ap-vol-bar {
		flex: 1;
		height: 5px;
		border-radius: 999px;
		overflow: hidden;
		background: var(--color-background-secondary, #f1f5f9)
	}

	.ap-vol-fill {
		height: 100%;
		background: linear-gradient(90deg, #22c55e, #ef4444);
		transition: width .4s
	}

	.ap-vol-yes {
		color: #16a34a;
		font-weight: 500
	}

	.ap-vol-no {
		color: #dc2626;
		font-weight: 500
	}

	/* toggle */
	.ap-toggle {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 10px;
		margin-bottom: 18px
	}

	.ap-toggle input[type=radio] {
		display: none
	}

	.ap-toggle label {
		display: flex;
		align-items: center;
		justify-content: space-between;
		padding: 15px 16px;
		border-radius: 12px;
		cursor: pointer;
		transition: all .18s;
		border: 0.5px solid var(--color-border-secondary, #e2e8f0);
		background: var(--color-background-secondary, #f8fafc)
	}

	.tog-left {
		display: flex;
		align-items: center;
		gap: 8px
	}

	.tog-dot {
		width: 8px;
		height: 8px;
		border-radius: 50%
	}

	.tog-yes .tog-dot {
		background: #22c55e
	}

	.tog-no .tog-dot {
		background: #ef4444
	}

	.tog-name {
		font-size: 14px;
		font-weight: 500;
		color: var(--color-text-primary, #0f172a)
	}

	.tog-price {
		font-size: 12px;
		color: var(--color-text-tertiary, #94a3b8)
	}

	.tog-tag {
		font-size: 11px;
		font-weight: 600;
		padding: 3px 9px;
		border-radius: 6px;
		background: var(--color-border-tertiary, #e2e8f0);
		color: var(--color-text-secondary, #64748b)
	}

	#answer_yes:checked+label {
		border-color: #22c55e;
		background: rgba(34, 197, 94, .07)
	}

	#answer_yes:checked+label .tog-name {
		color: #16a34a
	}

	#answer_yes:checked+label .tog-tag {
		background: #22c55e;
		color: #fff
	}

	#answer_no:checked+label {
		border-color: #ef4444;
		background: rgba(239, 68, 68, .07)
	}

	#answer_no:checked+label .tog-name {
		color: #dc2626
	}

	#answer_no:checked+label .tog-tag {
		background: #ef4444;
		color: #fff
	}

	.ap-toggle.locked label {
		cursor: not-allowed;
		opacity: .65
	}

	/* controls */
	.ap-controls {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 12px;
		margin-bottom: 18px
	}

	.ap-ctrl {
		padding: 16px;
		border-radius: 12px;
		background: var(--color-background-secondary, #f8fafc);
		border: 0.5px solid var(--color-border-tertiary, #e2e8f0)
	}

	.ap-ctrl-head {
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-bottom: 11px
	}

	.ap-ctrl-head span {
		font-size: 10px;
		text-transform: uppercase;
		letter-spacing: .09em;
		font-weight: 500;
		color: var(--color-text-tertiary, #94a3b8)
	}

	.ap-ctrl-head strong {
		font-size: 18px;
		font-weight: 600;
		color: var(--color-text-primary, #0f172a)
	}

	.ap-stepper {
		display: flex;
		align-items: center;
		gap: 7px
	}

	.ap-step-btn {
		width: 34px;
		height: 34px;
		border-radius: 9px;
		flex-shrink: 0;
		border: 0.5px solid var(--color-border-secondary, #e2e8f0);
		background: var(--color-background-primary, #fff);
		color: #2563eb;
		font-size: 16px;
		cursor: pointer;
		display: flex;
		align-items: center;
		justify-content: center;
		transition: background .12s, border-color .12s;
		line-height: 1
	}

	.ap-step-btn:hover {
		background: rgba(59, 130, 246, .07);
		border-color: #93c5fd
	}

	.ap-step-btn:disabled {
		opacity: .35;
		cursor: not-allowed
	}

	.ap-stepper input[type=range] {
		flex: 1;
		accent-color: #2563eb;
		cursor: pointer
	}

	.ap-stepper input[type=number] {
		flex: 1;
		padding: 7px;
		border-radius: 9px;
		border: 0.5px solid var(--color-border-secondary, #e2e8f0);
		background: var(--color-background-primary, #fff);
		color: var(--color-text-primary, #0f172a);
		text-align: center;
		font-size: 14px;
		font-weight: 600;
		font-family: inherit;
		outline: none
	}

	.ap-stepper input[type=number]:focus {
		border-color: #3b82f6;
		box-shadow: 0 0 0 3px rgba(59, 130, 246, .1)
	}

	.ap-stepper input[type=number]::-webkit-inner-spin-button,
	.ap-stepper input[type=number]::-webkit-outer-spin-button {
		-webkit-appearance: none
	}

	.ap-ctrl-hint {
		font-size: 11px;
		color: var(--color-text-tertiary, #94a3b8);
		margin-top: 6px
	}

	.ap-qty-warn {
		font-size: 11px;
		color: #dc2626;
		margin-top: 5px;
		display: none
	}

	.ap-qty-warn.show {
		display: block
	}

	/* stake */
	.ap-stake {
		padding: 15px 16px;
		border-radius: 12px;
		margin-bottom: 18px;
		background: var(--color-background-secondary, #f8fafc);
		border: 0.5px solid var(--color-border-tertiary, #e2e8f0)
	}

	.ap-stake-row {
		display: flex;
		justify-content: space-between;
		align-items: center;
		font-size: 13px
	}

	.ap-stake-row+.ap-stake-row {
		margin-top: 7px;
		padding-top: 7px;
		border-top: 0.5px solid var(--color-border-tertiary, #e2e8f0)
	}

	.ap-stake-row span {
		color: var(--color-text-secondary, #64748b)
	}

	.ap-stake-row strong {
		font-weight: 600;
		color: var(--color-text-primary, #0f172a)
	}

	.ap-stake-total strong {
		font-size: 15px;
		color: #2563eb
	}

	.ap-stake-win strong {
		font-size: 14px;
		color: #16a34a
	}

	/* formula */
	.ap-formula {
		padding: 13px 16px;
		border-radius: 12px;
		margin-bottom: 18px;
		background: rgba(59, 130, 246, .04);
		border: 0.5px solid rgba(59, 130, 246, .15)
	}

	.ap-formula-ttl {
		font-size: 11px;
		font-weight: 500;
		color: #2563eb;
		margin-bottom: 5px;
		text-transform: uppercase;
		letter-spacing: .06em
	}

	.ap-formula p {
		font-size: 13px;
		color: var(--color-text-secondary, #64748b);
		line-height: 1.6
	}

	.ap-formula strong {
		color: #2563eb
	}

	/* submit bar */
	.ap-submit-bar {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 16px;
		flex-wrap: wrap;
		padding: 16px 18px;
		border-radius: 13px;
		margin-top: 20px;
		background: var(--color-background-secondary, #f8fafc);
		border: 0.5px solid var(--color-border-secondary, #e2e8f0)
	}

	.ap-submit-info p {
		font-size: 12px;
		color: var(--color-text-tertiary, #94a3b8);
		margin-top: 2px
	}

	.ap-submit-info strong {
		font-size: 14px;
		font-weight: 500;
		color: var(--color-text-primary, #0f172a)
	}

	.ap-submit-btn {
		padding: 11px 26px;
		border: none;
		border-radius: 10px;
		background: #2563eb;
		color: #fff;
		font-family: inherit;
		font-size: 14px;
		font-weight: 500;
		cursor: pointer;
		transition: background .15s, transform .1s;
		flex-shrink: 0
	}

	.ap-submit-btn:hover {
		background: #1d4ed8
	}

	.ap-submit-btn:active {
		transform: scale(.98)
	}

	.ap-submit-btn:disabled {
		background: var(--color-border-tertiary, #e2e8f0);
		color: var(--color-text-tertiary, #94a3b8);
		cursor: not-allowed;
		transform: none
	}

	.ap-lock-note {
		padding: 14px 16px;
		border-radius: 12px;
		margin-top: 18px;
		border: 0.5px dashed var(--color-border-secondary, #e2e8f0);
		background: var(--color-background-secondary, #f8fafc);
		font-size: 13px;
		color: var(--color-text-secondary, #64748b);
		line-height: 1.6
	}

	/* chart */
	.ap-chart-inner {
		padding: 16px;
		border-radius: 13px;
		background: var(--color-background-secondary, #f8fafc);
		border: 0.5px solid var(--color-border-tertiary, #e2e8f0)
	}

	.ap-chart-hdr {
		display: flex;
		justify-content: space-between;
		align-items: flex-start;
		gap: 12px;
		margin-bottom: 14px;
		flex-wrap: wrap
	}

	.ap-chart-title {
		font-size: 14px;
		font-weight: 500;
		color: var(--color-text-primary, #0f172a);
		margin-bottom: 3px
	}

	.ap-chart-sub {
		font-size: 12px;
		color: var(--color-text-tertiary, #94a3b8)
	}

	.ap-chart-leg {
		display: flex;
		gap: 8px
	}

	.ap-chart-pill {
		display: inline-flex;
		align-items: center;
		gap: 5px;
		padding: 4px 10px;
		border-radius: 999px;
		font-size: 11px;
		font-weight: 500;
		background: var(--color-background-primary, #fff);
		border: 0.5px solid var(--color-border-secondary, #e2e8f0);
		color: var(--color-text-secondary, #64748b)
	}

	.ap-chart-pill::before {
		content: "";
		width: 7px;
		height: 7px;
		border-radius: 50%;
		display: inline-block
	}

	.ap-chart-pill.yes::before {
		background: #22c55e
	}

	.ap-chart-pill.no::before {
		background: #ef4444
	}

	.ap-metrics {
		display: grid;
		grid-template-columns: repeat(4, 1fr);
		gap: 8px;
		margin-bottom: 12px
	}

	.ap-metric {
		padding: 11px 13px;
		border-radius: 10px;
		background: var(--color-background-primary, #fff);
		border: 0.5px solid var(--color-border-tertiary, #e2e8f0)
	}

	.ap-metric span {
		display: block;
		font-size: 10px;
		text-transform: uppercase;
		letter-spacing: .08em;
		color: var(--color-text-tertiary, #94a3b8);
		font-weight: 500;
		margin-bottom: 4px
	}

	.ap-metric strong {
		font-size: 16px;
		font-weight: 600;
		color: var(--color-text-primary, #0f172a)
	}

	.ap-metric small {
		display: block;
		font-size: 10px;
		color: var(--color-text-tertiary, #94a3b8);
		margin-top: 3px
	}

	.ap-metric.m-yes strong {
		color: #16a34a
	}

	.ap-metric.m-no strong {
		color: #dc2626
	}

	.ap-metric.m-pos strong {
		color: #2563eb
	}

	.ap-chart-svg-box {
		border-radius: 10px;
		overflow: hidden;
		background: var(--color-background-primary, #fff);
		border: 0.5px solid var(--color-border-tertiary, #e2e8f0);
		padding: 8px
	}

	.ap-chart-foot {
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-top: 10px;
		font-size: 11px;
		color: var(--color-text-tertiary, #94a3b8)
	}

	.ap-spread-pill {
		padding: 3px 10px;
		border-radius: 999px;
		background: var(--color-background-primary, #fff);
		border: 0.5px solid var(--color-border-tertiary, #e2e8f0);
		font-weight: 500;
		color: var(--color-text-secondary, #64748b)
	}

	/* sidebar */
	.ap-side {
		display: flex;
		flex-direction: column;
		gap: 16px
	}

	.ap-side-stats {
		display: grid;
		grid-template-columns: repeat(3, 1fr);
		gap: 8px
	}

	.ap-ss {
		padding: 13px;
		border-radius: 12px;
		text-align: center;
		background: var(--color-background-secondary, #f8fafc);
		border: 0.5px solid var(--color-border-tertiary, #e2e8f0)
	}

	.ap-ss span {
		display: block;
		font-size: 10px;
		text-transform: uppercase;
		letter-spacing: .08em;
		color: var(--color-text-tertiary, #94a3b8);
		font-weight: 500;
		margin-bottom: 4px
	}

	.ap-ss strong {
		font-size: 19px;
		font-weight: 600;
		color: var(--color-text-primary, #0f172a)
	}

	.ap-ss.s-a strong {
		color: #3b82f6
	}

	.ap-ss.s-c strong {
		color: #22c55e
	}

	.ap-ss.s-w strong {
		color: #ef4444
	}

	.ap-qlist {
		display: flex;
		flex-direction: column;
		gap: 8px
	}

	.ap-qitem {
		display: block;
		padding: 12px 15px;
		border-radius: 12px;
		text-decoration: none;
		border: 0.5px solid var(--color-border-secondary, #e2e8f0);
		background: var(--color-background-primary, #fff);
		transition: all .18s
	}

	.ap-qitem:hover {
		border-color: #93c5fd;
		transform: translateX(2px)
	}

	.ap-qitem.active {
		border-color: #2563eb;
		background: rgba(59, 130, 246, .05)
	}

	.ap-qitem.correct {
		background: rgba(34, 197, 94, .04);
		border-color: rgba(34, 197, 94, .3)
	}

	.ap-qitem.wrong {
		background: rgba(239, 68, 68, .04);
		border-color: rgba(239, 68, 68, .3)
	}

	.ap-qitem-txt {
		font-size: 13px;
		font-weight: 500;
		color: var(--color-text-primary, #0f172a);
		line-height: 1.45;
		display: block;
		margin-bottom: 5px;
		overflow: hidden;
		display: -webkit-box;
		-webkit-line-clamp: 2;
		-webkit-box-orient: vertical
	}

	.ap-qitem-foot {
		display: flex;
		align-items: center;
		gap: 6px
	}

	.ap-qbadge {
		display: inline-flex;
		align-items: center;
		gap: 4px;
		padding: 2px 8px;
		border-radius: 6px;
		font-size: 10px;
		font-weight: 500
	}

	.ap-qbadge.open {
		background: rgba(34, 197, 94, .1);
		color: #16a34a;
		border: 0.5px solid rgba(34, 197, 94, .25)
	}

	.ap-qbadge.locked {
		background: rgba(100, 116, 139, .1);
		color: #64748b;
		border: 0.5px solid rgba(100, 116, 139, .2)
	}

	.ap-qbadge.correct {
		background: rgba(34, 197, 94, .1);
		color: #16a34a;
		border: 0.5px solid rgba(34, 197, 94, .25)
	}

	.ap-qbadge.wrong {
		background: rgba(239, 68, 68, .1);
		color: #dc2626;
		border: 0.5px solid rgba(239, 68, 68, .25)
	}

	.ap-qbadge-dot {
		width: 5px;
		height: 5px;
		border-radius: 50%;
		background: currentColor;
		display: inline-block
	}

	@media(max-width:1000px) {
		.ap-layout {
			grid-template-columns: 1fr
		}

		.ap-metrics {
			grid-template-columns: repeat(2, 1fr)
		}
	}

	@media(max-width:640px) {
		.ap-controls {
			grid-template-columns: 1fr
		}

		.ap-hero {
			flex-direction: column
		}

		.ap-metrics {
			grid-template-columns: 1fr 1fr
		}
	}
</style>

<div class="ap">

	<!-- Back -->
	<div>
		<a href="<?php echo site_url('questions?category_id=' . (int)$selected_category->id); ?>" class="ap-back">
			<svg width="14" height="14" viewBox="0 0 14 14" fill="none">
				<path d="M9 11L5 7l4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
			</svg>
			Back to <?php echo html_escape($selected_category->name); ?>
		</a>
	</div>

	<!-- Hero -->
	<section class="ap-hero">
		<div class="ap-hero-left">
			<h1><?php echo html_escape($selected_question->question); ?></h1>
			<p><?php echo $is_locked ? 'Your trade has been submitted and locked. Payout will be credited once the admin resolves the market.' : 'Choose YES or NO, set your price and quantity, then submit. You can only trade once per question.'; ?></p>
		</div>
		<div class="ap-hero-meta">
			<span class="ap-status <?php echo $market_is_open ? 'open' : 'closed'; ?>">
				<?php if ($market_is_open): ?><span class="ap-status-pulse"></span><?php endif; ?>
				<?php echo $market_is_open ? 'Market open' : 'Market closed'; ?>
			</span>
			<span class="ap-cat-tag"><?php echo html_escape($selected_category->name); ?></span>
		</div>
	</section>

	<!-- Layout -->
	<div class="ap-layout">

		<!-- LEFT -->
		<div>

			<!-- Market overview -->
			<div class="acard">
				<div class="acard-head">
					<h2>Market overview</h2>
					<p>Current YES / NO prices and trade volume.</p>
				</div>
				<div class="acard-body">
					<div class="ap-prob">
						<div class="ap-prob-labels">
							<span class="ap-prob-yes">YES <?php echo $yes_pct; ?>%</span>
							<span class="ap-prob-no"><?php echo $no_pct; ?>% NO</span>
						</div>
						<div class="ap-prob-bar">
							<div class="ap-prob-fill-y" style="width:<?php echo $yes_pct; ?>%"></div>
							<div class="ap-prob-fill-n" style="width:<?php echo $no_pct; ?>%"></div>
						</div>
					</div>
					<div class="ap-chips">
						<div class="ap-chip chip-yes">
							<span class="ap-chip-lbl">YES price</span>
							<span class="ap-chip-val">Rs <?php echo number_format($yes_price, 2); ?></span>
						</div>
						<div class="ap-chip chip-no">
							<span class="ap-chip-lbl">NO price</span>
							<span class="ap-chip-val">Rs <?php echo number_format($no_price, 2); ?></span>
						</div>
					</div>
					<div class="ap-vol">
						<span class="ap-vol-yes"><?php echo $yes_trade_qty; ?> YES</span>
						<div class="ap-vol-bar">
							<div class="ap-vol-fill" style="width:<?php echo $yes_vol_pct; ?>%"></div>
						</div>
						<span class="ap-vol-no"><?php echo $no_trade_qty; ?> NO</span>
					</div>
				</div>
			</div>

			<!-- Trade card -->
			<div class="acard" style="margin-top:16px">
				<div class="acard-head">
					<h2><?php echo $is_locked ? 'Your trade' : 'Place trade'; ?></h2>
					<p><?php echo $is_locked ? 'Locked — cannot be changed.' : 'Pick a side, price, quantity and submit.'; ?></p>
				</div>
				<div class="acard-body">

					<?php if ($is_locked): ?>
						<div class="ap-result <?php echo $is_settled ? ($is_correct_ans ? 'correct' : 'wrong') : 'pend'; ?>">
							<div>
								<div class="ap-result-eye"><?php echo $is_settled ? ($is_correct_ans ? 'Correct answer' : 'Wrong answer') : 'Trade submitted'; ?></div>
								<h4>You answered <strong><?php echo strtoupper($selected_answer); ?></strong></h4>
								<p>
									<?php if ($is_settled): echo $is_correct_ans ? 'Your answer matched the result. Winning amount was credited.' : 'Your answer did not match. No payout was credited.';
									else: echo 'Stake deducted. Payout credited after admin resolves the market.';
									endif; ?>
								</p>
							</div>
							<div class="ap-result-amt">
								<?php if ($is_settled): echo $is_correct_ans ? '+Rs ' . number_format((float)$selected_answer_state->payout_amount, 2) : 'Rs 0.00';
								else: echo 'Rs ' . number_format((float)$selected_answer_state->stake_amount, 2);
								endif; ?>
							</div>
						</div>
					<?php endif; ?>

					<?php echo form_open('questions/save_answers'); ?>
					<input type="hidden" name="category_id" value="<?php echo (int)$selected_category->id; ?>">
					<input type="hidden" name="question_id" value="<?php echo (int)$selected_question->id; ?>">
					<input type="hidden" name="price" class="js-ph" value="<?php echo number_format($default_price, 2, '.', ''); ?>">

					<!-- Answer toggle -->
					<div class="ap-toggle<?php echo $is_locked ? ' locked' : ''; ?>">
						<div>
							<input type="radio" id="answer_yes" name="answer" value="yes" <?php echo $selected_answer === 'yes' ? 'checked' : ''; ?> <?php echo $is_locked ? 'disabled' : ''; ?>>
							<label for="answer_yes" class="tog-yes">
								<span class="tog-left"><span class="tog-dot"></span><span class="tog-name">YES</span></span>
								<span class="tog-price">Rs <?php echo number_format($yes_price, 2); ?></span>
								<span class="tog-tag">YES</span>
							</label>
						</div>
						<div>
							<input type="radio" id="answer_no" name="answer" value="no" <?php echo $selected_answer === 'no' ? 'checked' : ''; ?> <?php echo $is_locked ? 'disabled' : ''; ?>>
							<label for="answer_no" class="tog-no">
								<span class="tog-left"><span class="tog-dot"></span><span class="tog-name">NO</span></span>
								<span class="tog-price">Rs <?php echo number_format($no_price, 2); ?></span>
								<span class="tog-tag">NO</span>
							</label>
						</div>
					</div>

					<!-- Controls -->
					<div class="ap-controls">
						<div class="ap-ctrl">
							<div class="ap-ctrl-head">
								<span>Price per share</span>
								<strong class="js-pdisplay">Rs <?php echo number_format($default_price, 2); ?></strong>
							</div>
							<div class="ap-stepper">
								<button type="button" class="ap-step-btn js-pdec" <?php echo $is_locked ? 'disabled' : ''; ?>>−</button>
								<input type="range" class="js-prange" min="0.50" max="<?php echo number_format($price_max, 2, '.', ''); ?>" step="0.50" value="<?php echo number_format(min($default_price, $price_max), 2, '.', ''); ?>" <?php echo $is_locked ? 'disabled' : ''; ?>>
								<button type="button" class="ap-step-btn js-pinc" <?php echo $is_locked ? 'disabled' : ''; ?>>+</button>
							</div>
							<div class="ap-ctrl-hint">Rs 0.50 – Rs <?php echo number_format($price_max, 2); ?></div>
						</div>
						<div class="ap-ctrl">
							<div class="ap-ctrl-head">
								<span>Quantity</span>
								<strong class="js-qdisplay"><?php echo (int)$default_quantity; ?></strong>
							</div>
							<div class="ap-stepper">
								<button type="button" class="ap-step-btn js-qdec" <?php echo $is_locked ? 'disabled' : ''; ?>>−</button>
								<input type="number" class="js-qinput" name="quantity" min="<?php echo $QTY_MIN; ?>" max="<?php echo $QTY_MAX; ?>" step="1" value="<?php echo (int)$default_quantity; ?>" <?php echo $is_locked ? 'readonly' : ''; ?>>
								<button type="button" class="ap-step-btn js-qinc" <?php echo $is_locked ? 'disabled' : ''; ?>>+</button>
							</div>
							<div class="ap-ctrl-hint">Min <?php echo $QTY_MIN; ?> · Max <?php echo $QTY_MAX; ?></div>
							<div class="ap-qty-warn" id="js-qwarn">Quantity cannot exceed <?php echo $QTY_MAX; ?>.</div>
						</div>
					</div>

					<!-- Stake preview -->
					<div class="ap-stake">
						<div style="
							display:inline-block;
							padding:5px 12px;
							border-radius:999px;
							background:rgba(34,197,94,0.1);
							color:#16a34a;
							font-size:12px;
							font-weight:600;
							margin-bottom:10px;
						">
							🔥 x<?php echo number_format($multiplier, 2); ?> Boost
						</div>
						<div class="ap-stake-row">
							<span>Price × Quantity</span>
							<strong class="js-formula">—</strong>
						</div>
						<div class="ap-stake-row ap-stake-total">
							<span>Total stake</span>
							<strong class="js-stake">—</strong>
						</div>
						<div class="ap-stake-row ap-stake-win">
							<span>Winning preview (×1.25)</span>
							<strong class="js-win">—</strong>
						</div>
					</div>

					<!-- Formula -->
					<div class="ap-formula">
						<div class="ap-formula-ttl">How winning amount is calculated</div>
						<p>Winning = Price × Quantity × <?php echo number_format($multiplier, 2); ?>. If your answer matches the result, <strong class="js-win-inline">—</strong> will be credited to your wallet. If wrong, Rs 0.00 is paid.</p>
					</div>

					<?php if ($is_locked): ?>
						<div class="ap-lock-note">This trade is locked. Return to the question list to open another question in this category.</div>
					<?php else: ?>
						<div class="ap-submit-bar">
							<div class="ap-submit-info">
								<strong>Ready to submit?</strong>
								<p>This action is final and cannot be undone.</p>
							</div>
							<button type="submit" class="ap-submit-btn" id="js-sbtn">Place Trade</button>
						</div>
					<?php endif; ?>

					<?php echo form_close(); ?>

				</div>
			</div>

			<!-- Price history chart -->
			<div class="acard" style="margin-top:16px">
				<div class="acard-head">
					<h2>Price history</h2>
					<p>YES and NO price movement from market snapshots.</p>
				</div>
				<div class="acard-body">
					<div class="ap-chart-inner" data-chart data-history='<?php echo json_encode($price_history); ?>'>
						<div class="ap-chart-hdr">
							<div>
								<div class="ap-chart-title">Demand chart · <?php echo html_escape($selected_category->name); ?></div>
								<div class="ap-chart-sub">Auto-scaled from latest price snapshots.</div>
							</div>
							<div class="ap-chart-leg">
								<span class="ap-chart-pill yes">YES</span>
								<span class="ap-chart-pill no">NO</span>
							</div>
						</div>
						<div class="ap-metrics">
							<div class="ap-metric m-yes">
								<span>YES price</span>
								<strong>Rs <?php echo number_format($yes_price, 2); ?></strong>
								<small><?php echo $yes_trade_qty; ?> trades</small>
							</div>
							<div class="ap-metric m-no">
								<span>NO price</span>
								<strong>Rs <?php echo number_format($no_price, 2); ?></strong>
								<small><?php echo $no_trade_qty; ?> trades</small>
							</div>
							<div class="ap-metric">
								<span>Market total</span>
								<strong>Rs <?php echo number_format($market_total, 2); ?></strong>
								<small><?php echo ucfirst((string)$selected_question->status); ?></small>
							</div>
							<div class="ap-metric m-pos">
								<span>Positions</span>
								<strong><?php echo $total_trade_qty; ?></strong>
								<small>Total opened</small>
							</div>
						</div>
						<div class="ap-chart-svg-box">ap-stake
							<div id="js-chart"></div>
						</div>
						<div class="ap-chart-foot">
							<span>Current spread</span>
							<span class="ap-spread-pill">Rs <?php echo number_format(abs($yes_price - $no_price), 2); ?></span>
						</div>
					</div>
				</div>
			</div>

		</div><!-- /LEFT -->

		<!-- SIDEBAR -->
		<aside class="ap-side">

			<div class="acard">
				<div class="acard-head">
					<h2><?php echo html_escape($selected_category->name); ?> progress</h2>
					<p>Your answers in this category.</p>
				</div>
				<div class="acard-body">
					<div class="ap-side-stats">
						<div class="ap-ss s-a"><span>Answered</span><strong><?php echo (int)$answered_count; ?></strong></div>
						<div class="ap-ss s-c"><span>Correct</span><strong><?php echo (int)$correct_count; ?></strong></div>
						<div class="ap-ss s-w"><span>Wrong</span><strong><?php echo (int)$wrong_count; ?></strong></div>
					</div>
				</div>
			</div>

			<div class="acard">
				<div class="acard-head">
					<h2>All questions</h2>
					<p>Jump to any question in this category.</p>
				</div>
				<div class="acard-body">
					<div class="ap-qlist">
						<?php foreach ($selected_category->questions as $qi):
							$la = isset($user_answers[(int)$qi->id]) ? $user_answers[(int)$qi->id] : NULL;
							$ia = (int)$qi->id === (int)$selected_question->id;
							$qs = '';
							if ($la) $qs = strtolower((string)$la->answer) === strtolower((string)$qi->answer_key) ? 'correct' : 'wrong';
							$cls = $ia ? 'active' : $qs;
						?>
							<a class="ap-qitem <?php echo $cls; ?>" href="<?php echo site_url('questions/answer/' . (int)$qi->id); ?>">
								<span class="ap-qitem-txt"><?php echo html_escape($qi->question); ?></span>
								<div class="ap-qitem-foot">
									<?php if ($la): ?>
										<span class="ap-qbadge <?php echo $qs ?: 'locked'; ?>">
											<span class="ap-qbadge-dot"></span>
											<?php echo $qs === 'correct' ? 'Correct' : ($qs === 'wrong' ? 'Wrong' : 'Answered'); ?>
										</span>
									<?php elseif (strtolower(trim((string)$qi->status)) === 'open'): ?>
										<span class="ap-qbadge open"><span class="ap-qbadge-dot"></span>Open</span>
									<?php else: ?>
										<span class="ap-qbadge locked">Closed</span>
									<?php endif; ?>
								</div>
							</a>
						<?php endforeach; ?>
					</div>
				</div>
			</div>

		</aside>

	</div>
</div>

<script>
	(function() {
		'use strict';

		var QMAX = <?php echo $QTY_MAX; ?>,
			QMIN = <?php echo $QTY_MIN; ?>;
		var PMIN = 0.50,
			PMAX = <?php echo number_format($price_max, 4, '.', ''); ?>;
		var DYES = <?php echo number_format($yes_price, 4, '.', ''); ?>,
			DNO = <?php echo number_format($no_price, 4, '.', ''); ?>;

		var yR = document.getElementById('answer_yes'),
			nR = document.getElementById('answer_no');
		var pR = document.querySelector('.js-prange'),
			pH = document.querySelector('.js-ph');
		var pD = document.querySelector('.js-pdisplay'),
			qI = document.querySelector('.js-qinput');
		var qD = document.querySelector('.js-qdisplay'),
			qW = document.getElementById('js-qwarn');
		var fEl = document.querySelector('.js-formula'),
			sEl = document.querySelector('.js-stake');
		var wEl = document.querySelector('.js-win'),
			wIn = document.querySelector('.js-win-inline');
		var sb = document.getElementById('js-sbtn');
		var pDec = document.querySelector('.js-pdec'),
			pInc = document.querySelector('.js-pinc');
		var qDec = document.querySelector('.js-qdec'),
			qInc = document.querySelector('.js-qinc');

		function fmt(v) {
			return 'Rs ' + Number(v).toFixed(2);
		}

		function clamp(v, lo, hi) {
			return Math.min(Math.max(v, lo), hi);
		}

		function gP() {
			return pR ? parseFloat(pR.value) || PMIN : PMIN;
		}

		function gQ() {
			return qI ? parseInt(qI.value) || QMIN : QMIN;
		}

		function update() {
			var p = clamp(gP(), PMIN, PMAX),
				q = gQ(),
				over = q > QMAX;
			if (over) {
				q = QMAX;
				if (qI) qI.value = QMAX;
			}
			if (qW) qW.classList.toggle('show', over);
			q = clamp(q, QMIN, QMAX);
			var stake = p * q,
				win = stake * 1.25;
			if (pH) pH.value = p.toFixed(2);
			if (pD) pD.textContent = fmt(p);
			if (qD) qD.textContent = q;
			if (fEl) fEl.textContent = fmt(p) + ' \u00d7 ' + q;
			if (sEl) sEl.textContent = fmt(stake);
			if (wEl) wEl.textContent = fmt(win);
			if (wIn) wIn.textContent = fmt(win);
			if (sb) sb.disabled = !(p >= PMIN && p <= PMAX && q >= QMIN && q <= QMAX);
		}

		function syncP() {
			if (!pR) return;
			var base = (nR && nR.checked) ? DNO : DYES;
			pR.value = clamp(base > 0 ? base : PMIN, PMIN, PMAX).toFixed(2);
			update();
		}

		if (pR && !pR.disabled) {
			pR.addEventListener('input', update);
			if (yR) yR.addEventListener('change', syncP);
			if (nR) nR.addEventListener('change', syncP);
			if (pDec) pDec.addEventListener('click', function() {
				pR.value = clamp(parseFloat(pR.value) - 0.50, PMIN, PMAX).toFixed(2);
				update();
			});
			if (pInc) pInc.addEventListener('click', function() {
				pR.value = clamp(parseFloat(pR.value) + 0.50, PMIN, PMAX).toFixed(2);
				update();
			});
		}

		if (qI && !qI.readOnly) {
			qI.addEventListener('input', function() {
				var v = parseInt(this.value) || 0;
				if (v > QMAX) this.value = QMAX;
				if (v < 0) this.value = 0;
				update();
			});
			qI.addEventListener('blur', function() {
				var v = parseInt(this.value) || QMIN;
				this.value = clamp(v, QMIN, QMAX);
				update();
			});
			if (qDec) qDec.addEventListener('click', function() {
				qI.value = clamp((parseInt(qI.value) || 1) - 1, QMIN, QMAX);
				update();
			});
			if (qInc) qInc.addEventListener('click', function() {
				qI.value = clamp((parseInt(qI.value) || 0) + 1, QMIN, QMAX);
				update();
			});
			var fm = qI.closest('form');
			if (fm) fm.addEventListener('submit', function(e) {
				var q = parseInt(qI.value),
					p = parseFloat(pR ? pR.value : 0);
				qI.value = clamp(isNaN(q) ? QMIN : q, QMIN, QMAX);
				if (pH && pR) pH.value = clamp(parseFloat(pR.value) || PMIN, PMIN, PMAX).toFixed(2);
				if (q < QMIN || q > QMAX || p < PMIN || p > PMAX) {
					e.preventDefault();
					if (qW) qW.classList.add('show');
				}
			});
		}

		update();

		/* Chart */
		(function() {
			var host = document.querySelector('[data-chart]'),
				mount = document.getElementById('js-chart');
			if (!host || !mount) return;
			var pts = [];
			try {
				pts = JSON.parse(host.getAttribute('data-history') || '[]');
			} catch (e) {
				pts = [];
			}
			var vY = pts.map(function(p) {
				return parseFloat(p.yes_price || 0);
			});
			var vN = pts.map(function(p) {
				return parseFloat(p.no_price || 0);
			});
			var all = vY.concat(vN).filter(function(v) {
				return !isNaN(v);
			});
			if (!all.length) {
				mount.innerHTML = '<div style="display:flex;align-items:center;justify-content:center;height:150px;font-size:13px;color:var(--color-text-tertiary,#94a3b8)">No price history yet.</div>';
				return;
			}
			var W = 540,
				H = 150,
				PX = 18,
				PT = 12,
				PB = 18;
			var mn = Math.min.apply(null, all),
				mx = Math.max.apply(null, all.concat([mn + 0.01]));

			function norm(vals) {
				return vals.map(function(v, i) {
					var x = vals.length === 1 ? W / 2 : PX + (i / (vals.length - 1)) * (W - PX * 2);
					var r = (v - mn) / (mx - mn);
					var y = H - PB - r * (H - PT - PB);
					return x.toFixed(1) + ',' + y.toFixed(1);
				}).join(' ');
			}
			mount.innerHTML = '<svg viewBox="0 0 ' + W + ' ' + H + '" preserveAspectRatio="none" style="width:100%;display:block;min-height:' + H + 'px">' +
				'<defs>' +
				'<linearGradient id="cY" x1="0%" y1="0%" x2="100%" y2="0%"><stop offset="0%" stop-color="#22c55e"/><stop offset="100%" stop-color="#16a34a"/></linearGradient>' +
				'<linearGradient id="cN" x1="0%" y1="0%" x2="100%" y2="0%"><stop offset="0%" stop-color="#f87171"/><stop offset="100%" stop-color="#dc2626"/></linearGradient>' +
				'</defs>' +
				'<line x1="' + PX + '" y1="' + (H - PB) + '" x2="' + (W - PX) + '" y2="' + (H - PB) + '" stroke="var(--color-border-tertiary,#e2e8f0)" stroke-width="1"/>' +
				'<line x1="' + PX + '" y1="' + Math.round((H - PB + PT) / 2) + '" x2="' + (W - PX) + '" y2="' + Math.round((H - PB + PT) / 2) + '" stroke="var(--color-border-tertiary,#e2e8f0)" stroke-width="1" stroke-dasharray="4 4"/>' +
				'<polyline fill="none" stroke="url(#cY)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" points="' + norm(vY) + '"/>' +
				'<polyline fill="none" stroke="url(#cN)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" points="' + norm(vN) + '"/>' +
				'</svg>';
		}());
	}());
</script>