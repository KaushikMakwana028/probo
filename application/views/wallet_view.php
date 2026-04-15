<?php
$history_filter = isset($history_filter) ? $history_filter : 'all';
$pending_withdrawals = array();
$merged_transactions = array();
$withdrawal_tx_ids = array();

if (!empty($transactions)) {
	foreach ($transactions as $tx) {
		if (isset($tx->source_type) && $tx->source_type === 'withdrawal' && !empty($tx->source_id)) {
			$withdrawal_tx_ids[(int) $tx->source_id] = TRUE;
		}

		$tx->_history_timestamp = !empty($tx->created_at) ? strtotime($tx->created_at) : 0;
		$merged_transactions[] = $tx;
	}
}

if (!empty($withdrawals)) {
	foreach ($withdrawals as $wr) {
		$ws = strtolower(trim((string) $wr->status));
		$is_pending = in_array($ws, array('pending', 'processing'), TRUE);
		$has_wallet_tx = isset($withdrawal_tx_ids[(int) $wr->id]);

		if ($is_pending) {
			$pending_withdrawals[] = $wr;
			continue;
		}

		if (!$has_wallet_tx) {
			$history_row = new stdClass();
			$history_row->created_at = $wr->created_at;
			$history_row->amount = $wr->amount;
			$history_row->type = 'debit';
			$history_row->source_type = 'withdrawal_request';
			$history_row->history_type_label = 'Withdrawal';
			$history_row->history_badge_class = 'wlt-badge--withdrawal';
			$history_row->history_filter = 'withdrawals';
			$history_row->history_description = 'Withdrawal request';
			$history_row->history_status_label = in_array($ws, array('approved', 'success', 'completed'), TRUE) ? 'Success' : (in_array($ws, array('rejected', 'failed', 'declined'), TRUE) ? 'Rejected' : ucfirst($ws));
			$history_row->history_status_class = in_array($ws, array('approved', 'success', 'completed'), TRUE) ? 'wlt-badge--success' : (in_array($ws, array('rejected', 'failed', 'declined'), TRUE) ? 'wlt-badge--rejected' : 'wlt-badge--processed');
			$history_row->_history_timestamp = !empty($wr->created_at) ? strtotime($wr->created_at) : 0;
			$merged_transactions[] = $history_row;
		}
	}
}

usort($merged_transactions, function ($a, $b) {
	$time_compare = (int) $b->_history_timestamp <=> (int) $a->_history_timestamp;
	if ($time_compare !== 0) {
		return $time_compare;
	}

	return 0;
});

$transactions = $merged_transactions;
$withdrawals = $pending_withdrawals;
$wallet_history_count = count($withdrawals) + count($transactions);
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">

<?php if ($this->session->flashdata('error')): ?>
	<div class="wlt-alert wlt-alert--err" role="alert">
		<div class="wlt-alert__icon">
			<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
				<circle cx="12" cy="12" r="10" />
				<line x1="12" y1="8" x2="12" y2="12" />
				<line x1="12" y1="16" x2="12.01" y2="16" />
			</svg>
		</div>
		<span><?php echo $this->session->flashdata('error'); ?></span>
		<button class="wlt-alert__close" onclick="this.parentElement.remove()">&times;</button>
	</div>
<?php endif; ?>
<?php if ($this->session->flashdata('success')): ?>
	<div class="wlt-alert wlt-alert--ok" role="alert">
		<div class="wlt-alert__icon">
			<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
				<polyline points="20 6 9 17 4 12" />
			</svg>
		</div>
		<span><?php echo $this->session->flashdata('success'); ?></span>
		<button class="wlt-alert__close" onclick="this.parentElement.remove()">&times;</button>
	</div>
<?php endif; ?>

<div class="wlt-root">

	<!-- ══ HERO BANNER ══ -->
	<header class="wlt-hero">
		<div class="wlt-hero__bg"></div>
		<div class="wlt-hero__inner">
			<div class="wlt-hero__left">
				<p class="wlt-hero__eyebrow">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<rect x="2" y="5" width="20" height="14" rx="3" />
						<path d="M2 10h20" />
					</svg>
					Wallet
				</p>
				<h1 class="wlt-hero__title">Balance &amp; Transactions</h1>
				<p class="wlt-hero__sub">Deposit funds, request withdrawals, manage your bank account, and review your complete transaction history.</p>
				<div class="wlt-hero__chips">
					<div class="wlt-hero__chip">
						<span class="wlt-hero__chip-label">Total Balance</span>
						<span class="wlt-hero__chip-val">₹<?php echo number_format((float)$user->wallet_balance, 2); ?></span>
					</div>
					<div class="wlt-hero__chip wlt-hero__chip--green">
						<span class="wlt-hero__chip-label">Total Winnings</span>
						<span class="wlt-hero__chip-val">₹<?php echo number_format((float)$total_winnings, 2); ?></span>
					</div>
					<div class="wlt-hero__chip wlt-hero__chip--red">
						<span class="wlt-hero__chip-label">Total Withdrawn</span>
						<span class="wlt-hero__chip-val">₹<?php echo number_format((float)$total_withdrawn, 2); ?></span>
					</div>
					<div class="wlt-hero__chip wlt-hero__chip--green">
						<span class="wlt-hero__chip-label">TOTAL DEPOSITED</span>
						<span class="wlt-hero__chip-val">₹<?php echo number_format($total_deposited, 2); ?></span>
					</div>
				</div>
			</div>
			<div class="wlt-hero__balance-card">
				<p class="wlt-hero__balance-label">Available Balance</p>
				<p class="wlt-hero__balance-val">₹<?php echo number_format((float)$user->wallet_balance, 2); ?></p>
				<div class="wlt-hero__balance-footer">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
					</svg>
					Secured &amp; Encrypted
				</div>
			</div>
		</div>
	</header>

	<!-- ══ ACTION ROW ══ -->
	<div class="wlt-actions">
		<!-- WITHDRAW -->
		<div class="wlt-card" data-reveal>
			<div class="wlt-card__head">
				<div class="wlt-card__head-icon <?php echo $bank_details_saved ? 'wlt-card__head-icon--red' : 'wlt-card__head-icon--gray'; ?>">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
						<line x1="12" y1="5" x2="12" y2="19" />
						<polyline points="19 12 12 19 5 12" />
					</svg>
				</div>
				<div>
					<p class="wlt-card__eyebrow">Cash Out</p>
					<h2 class="wlt-card__title">Withdraw</h2>
				</div>
			</div>
			<p class="wlt-card__desc">
				<?php echo $bank_details_saved
					? 'Withdrawal requests are reviewed and processed by admin.'
					: 'You must save your bank details before requesting a withdrawal.'; ?>
			</p>
			<form method="post" action="<?php echo site_url('wallet/request-withdrawal'); ?>" class="wlt-form">
				<div class="wlt-field">
					<label class="wlt-label" for="wd_amount">Amount (₹)</label>
					<div class="wlt-input-wrap">
						<span class="wlt-input-prefix">₹</span>
						<input type="number" id="wd_amount" name="amount"
							min="1" step="0.01"
							max="<?php echo number_format((float)$user->wallet_balance, 2, '.', ''); ?>"
							placeholder="0.00"
							<?php echo $bank_details_saved ? '' : 'disabled'; ?> required
							class="wlt-input wlt-input--prefixed">
					</div>
					<?php if ($bank_details_saved): ?>
						<p class="wlt-field-hint">Max available: <strong>₹<?php echo number_format((float)$user->wallet_balance, 2); ?></strong></p>
					<?php endif; ?>
				</div>
				<button type="submit"
					class="wlt-btn wlt-btn--full <?php echo $bank_details_saved ? 'wlt-btn--red' : 'wlt-btn--disabled'; ?>"
					<?php echo $bank_details_saved ? '' : 'disabled'; ?>>
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
						<line x1="12" y1="5" x2="12" y2="19" />
						<polyline points="19 12 12 19 5 12" />
					</svg>
					Request Withdrawal
				</button>
			</form>
		</div>

		<!-- BANK DETAILS -->
		<div class="wlt-card wlt-card--bank" data-reveal>
			<div class="wlt-card__head">
				<div class="wlt-card__head-icon wlt-card__head-icon--green">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
						<rect x="3" y="5" width="18" height="14" rx="2" />
						<path d="M3 10h18" />
						<path d="M7 15h.01M11 15h2" />
					</svg>
				</div>
				<div>
					<p class="wlt-card__eyebrow">Linked Account</p>
					<h2 class="wlt-card__title">Bank Details</h2>
				</div>
				<?php if ($bank_details_saved && !$edit_bank_details): ?>
					<a class="wlt-icon-btn" href="<?php echo site_url('wallet?edit_bank=1&history=' . $history_filter); ?>" title="Edit bank details">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
							<path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" />
							<path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
						</svg>
					</a>
				<?php endif; ?>
			</div>

			<?php if ($edit_bank_details): ?>
				<form method="post" action="<?php echo site_url('wallet/save-bank-details'); ?>" class="wlt-bank-form">
					<div class="wlt-field">
						<label class="wlt-label" for="bank_account_holder_name">Account Holder Name</label>
						<input type="text" id="bank_account_holder_name" name="bank_account_holder_name"
							value="<?php echo set_value('bank_account_holder_name', $user->bank_account_holder_name); ?>"
							required placeholder="Full legal name" class="wlt-input">
					</div>
					<div class="wlt-field">
						<label class="wlt-label" for="bank_name">Bank Name</label>
						<input type="text" id="bank_name" name="bank_name"
							value="<?php echo set_value('bank_name', $user->bank_name); ?>"
							required placeholder="e.g. HDFC Bank" class="wlt-input">
					</div>
					<div class="wlt-field">
						<label class="wlt-label" for="bank_account_number">Account Number</label>
						<input type="text" id="bank_account_number" name="bank_account_number"
							value="<?php echo set_value('bank_account_number', $user->bank_account_number); ?>"
							required placeholder="Enter account number" class="wlt-input">
					</div>
					<div class="wlt-field">
						<label class="wlt-label" for="bank_ifsc_code">IFSC Code</label>
						<input type="text" id="bank_ifsc_code" name="bank_ifsc_code"
							value="<?php echo set_value('bank_ifsc_code', $user->bank_ifsc_code); ?>"
							required placeholder="e.g. HDFC0001234" class="wlt-input">
					</div>
					<div class="wlt-bank-form__actions">
						<?php if ($bank_details_saved): ?>
							<a class="wlt-btn wlt-btn--ghost" href="<?php echo site_url('wallet?history=' . $history_filter); ?>">Cancel</a>
						<?php endif; ?>
						<button type="submit" class="wlt-btn wlt-btn--blue">
							<?php echo $bank_details_saved ? 'Update Details' : 'Save Details'; ?>
						</button>
					</div>
				</form>
			<?php else: ?>
				<div class="wlt-bank-grid">
					<div class="wlt-bank-box">
						<span class="wlt-bank-box__label">Account Holder</span>
						<strong class="wlt-bank-box__val"><?php echo html_escape($user->bank_account_holder_name ?: '—'); ?></strong>
					</div>
					<div class="wlt-bank-box">
						<span class="wlt-bank-box__label">Bank Name</span>
						<strong class="wlt-bank-box__val"><?php echo html_escape($user->bank_name ?: '—'); ?></strong>
					</div>
					<div class="wlt-bank-box wlt-bank-box--accent">
						<span class="wlt-bank-box__label">Account Number</span>
						<strong class="wlt-bank-box__val wlt-bank-box__val--mono"><?php echo html_escape($user->bank_account_number ?: '—'); ?></strong>
					</div>
					<div class="wlt-bank-box wlt-bank-box--accent">
						<span class="wlt-bank-box__label">IFSC Code</span>
						<strong class="wlt-bank-box__val wlt-bank-box__val--mono"><?php echo html_escape($user->bank_ifsc_code ?: '—'); ?></strong>
					</div>
				</div>
				<?php if (!$bank_details_saved): ?>
					<a class="wlt-btn wlt-btn--outline wlt-btn--full" href="<?php echo site_url('wallet?edit_bank=1&history=' . $history_filter); ?>" style="margin-top:14px;">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
							<line x1="12" y1="5" x2="12" y2="19" />
							<line x1="5" y1="12" x2="19" y2="12" />
						</svg>
						Add Bank Details
					</a>
				<?php endif; ?>
			<?php endif; ?>
		</div>

	</div><!-- /wlt-actions -->

	<!-- ══ TRANSACTION HISTORY ══ -->
	<section class="wlt-tx-section" data-reveal>
		<div class="wlt-tx-header">
			<div class="wlt-tx-header__top">
				<div>
					<p class="wlt-eyebrow-plain">History</p>
					<h2 class="wlt-tx-title">Transaction History</h2>
				</div>
				<span class="wlt-tx-count" id="tx-count"><?php echo $wallet_history_count; ?> records</span>
			</div>

			<div class="wlt-filter-bar">
				<?php
				$wallet_filters = [
					'all'         => ['label' => 'All',         'color' => '#2563eb'],
					'deposits'    => ['label' => 'Deposits',    'color' => '#7c3aed'],
					'winnings'    => ['label' => 'Winnings',    'color' => '#059669'],
					'withdrawals' => ['label' => 'Withdrawals', 'color' => '#e02424'],
					'trades'      => ['label' => 'Trades',      'color' => '#d97706'],
					'refunds'     => ['label' => 'Refunds',     'color' => '#0891b2'],
				];
				foreach ($wallet_filters as $fk => $fv):
				?>
					<button class="wlt-filter-pill <?php echo $history_filter === $fk ? 'is-active' : ''; ?>"
						data-filter="<?php echo $fk; ?>"
						data-color="<?php echo $fv['color']; ?>"
						style="<?php echo $history_filter === $fk ? '--pill-color:' . $fv['color'] : ''; ?>">
						<span class="wlt-filter-pill__dot"></span>
						<?php echo $fv['label']; ?>
					</button>
				<?php endforeach; ?>

				<div class="wlt-rows-wrap">
					<button class="wlt-rows-btn" id="rowsBtn">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
							<line x1="8" y1="6" x2="21" y2="6" />
							<line x1="8" y1="12" x2="21" y2="12" />
							<line x1="8" y1="18" x2="21" y2="18" />
							<line x1="3" y1="6" x2="3.01" y2="6" />
							<line x1="3" y1="12" x2="3.01" y2="12" />
							<line x1="3" y1="18" x2="3.01" y2="18" />
						</svg>
						<span id="rowsLabel">10 rows</span>
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" id="rowsChevron">
							<polyline points="6 9 12 15 18 9" />
						</svg>
					</button>
					<div class="wlt-rows-dropdown" id="rowsDropdown">
						<div class="wlt-rows-opt is-active" data-rows="10">10 rows</div>
						<div class="wlt-rows-opt" data-rows="25">25 rows</div>
						<div class="wlt-rows-opt" data-rows="50">50 rows</div>
						<div class="wlt-rows-opt" data-rows="100">100 rows</div>
						<div class="wlt-rows-opt" data-rows="99999">All rows</div>
					</div>
				</div>
			</div>
		</div>

		<div class="wlt-table-wrap">
			<table class="wlt-table" id="txTable">
				<thead>
					<tr>
						<th>#</th>
						<th>Type</th>
						<th>Description</th>
						<th>Amount</th>
						<th>Status</th>
						<th>Date &amp; Time</th>
					</tr>
				</thead>
				<tbody id="txBody">

					<!-- ── WITHDRAWAL REQUESTS (from $withdrawals array) ── -->
					<?php if (!empty($withdrawals)):
						foreach ($withdrawals as $wr):
							$ws = strtolower(trim((string)$wr->status));
							// Map status
							if (in_array($ws, ['approved', 'success', 'completed'])) {
								$status_label = 'Success';
								$status_class = 'wlt-badge--success';
							} elseif (in_array($ws, ['pending', 'processing'])) {
								$status_label = 'Pending';
								$status_class = 'wlt-badge--pending';
							} elseif (in_array($ws, ['rejected', 'failed', 'declined'])) {
								$status_label = 'Rejected';
								$status_class = 'wlt-badge--rejected';
							} else {
								$status_label = ucfirst($ws);
								$status_class = 'wlt-badge--pending';
							}
					?>
							<tr data-filter-type="withdrawals">
								<td class="wlt-td-num">—</td>
								<td>
									<span class="wlt-badge wlt-badge--withdrawal">
										<span class="wlt-badge-dot"></span>Withdrawal
									</span>
								</td>
								<td class="wlt-td-desc">Withdrawal request</td>
								<td class="wlt-td-debit">− ₹<?php echo number_format((float)$wr->amount, 2); ?></td>
								<td><span class="wlt-badge <?php echo $status_class; ?>"><?php echo html_escape($status_label); ?></span></td>
								<td class="wlt-td-date"><?php echo html_escape(date('d M Y, h:i A', strtotime($wr->created_at))); ?></td>
							</tr>
					<?php endforeach;
					endif; ?>

					<!-- ── REGULAR TRANSACTIONS ── -->
					<?php if (!empty($transactions)):
						foreach ($transactions as $index => $tx):
							$is_credit    = strtolower((string)$tx->type) === 'credit';
							$type_label   = isset($tx->history_type_label) ? $tx->history_type_label : 'Transaction';
							$badge_class  = isset($tx->history_badge_class) ? $tx->history_badge_class : 'wlt-badge--default';
							$tx_filter    = isset($tx->history_filter) ? $tx->history_filter : 'all';
							$description  = isset($tx->history_description) ? $tx->history_description : '';

							if (isset($tx->history_type_label)) {
								$status_class = isset($tx->history_status_class) ? $tx->history_status_class : ($is_credit ? 'wlt-badge--success' : 'wlt-badge--processed');
								$status_label = isset($tx->history_status_label) ? $tx->history_status_label : ($is_credit ? 'Success' : 'Processed');
							} elseif ($tx->source_type === 'deposit') {
								$type_label  = 'Deposit';
								$badge_class = 'wlt-badge--deposit';
								$tx_filter   = 'deposits';
								$description = 'Wallet top-up';
							} elseif ($tx->source_type === 'question_result') {
								$type_label  = 'Winning';
								$badge_class = 'wlt-badge--winning';
								$tx_filter   = 'winnings';
								$description = 'Question result payout';
							} elseif ($tx->source_type === 'trade_entry') {
								$type_label  = 'Trade';
								$badge_class = 'wlt-badge--trade';
								$tx_filter   = 'trades';
								$description = 'Trade entry fee';
							} elseif ($tx->source_type === 'withdrawal') {
								$type_label  = 'Withdrawal';
								$badge_class = 'wlt-badge--withdrawal';
								$tx_filter   = 'withdrawals';
								$description = 'Withdrawal processed';
							} elseif ($tx->source_type === 'referral_bonus') {
								$type_label  = 'Referral';
								$badge_class = 'wlt-badge--referral';
								$tx_filter   = 'all';
								$description = 'Referral bonus';
							} elseif ($tx->source_type === 'refund') {
								$type_label  = 'Refund';
								$badge_class = 'wlt-badge--refund';
								$tx_filter   = 'refunds';
								$description = 'Refund credited';
							}

							if (!isset($tx->history_type_label)) {
								$status_class = $is_credit ? 'wlt-badge--success' : 'wlt-badge--processed';
								$status_label = $is_credit ? 'Success' : 'Processed';
							}
					?>
							<tr data-filter-type="<?php echo $tx_filter; ?>">
								<td class="wlt-td-num">—</td>
								<td>
									<span class="wlt-badge <?php echo $badge_class; ?>">
										<span class="wlt-badge-dot"></span><?php echo html_escape($type_label); ?>
									</span>
								</td>
								<td class="wlt-td-desc"><?php echo html_escape($description); ?></td>
								<td class="<?php echo $is_credit ? 'wlt-td-credit' : 'wlt-td-debit'; ?>">
									<?php echo $is_credit ? '+' : '−'; ?> ₹<?php echo number_format((float)$tx->amount, 2); ?>
								</td>
								<td><span class="wlt-badge <?php echo $status_class; ?>"><?php echo html_escape($status_label); ?></span></td>
								<td class="wlt-td-date"><?php echo html_escape(date('d M Y, h:i A', strtotime($tx->created_at))); ?></td>
							</tr>
					<?php endforeach;
					endif; ?>

				</tbody>
			</table>
		</div>

		<div class="wlt-pagination" id="txPagination">
			<span class="wlt-page-info" id="txPageInfo">Showing 1–10</span>
			<div class="wlt-page-btns" id="txPageBtns"></div>
		</div>
	</section>

</div><!-- /.wlt-root -->

<style>
	/* ───────────────────────────────────
   RESET & TOKENS
─────────────────────────────────────*/
	*,
	*::before,
	*::after {
		box-sizing: border-box;
		margin: 0;
		padding: 0
	}

	:root {
		/* Neutrals */
		--ink: #0e1117;
		--ink-2: #2d3748;
		--ink-3: #4a5568;
		--ink-4: #718096;
		--ink-5: #a0aec0;
		--line: #e8ecf1;
		--line-2: #d2d9e3;
		--surface: #ffffff;
		--base: #f7f8fa;
		--base-2: #f0f2f5;

		/* Blue */
		--b-50: #eff6ff;
		--b-100: #dbeafe;
		--b-200: #bfdbfe;
		--b-500: #3b82f6;
		--b-600: #2563eb;
		--b-700: #1d4ed8;

		/* Green */
		--g-50: #f0fdf4;
		--g-100: #dcfce7;
		--g-200: #bbf7d0;
		--g-500: #22c55e;
		--g-600: #16a34a;
		--g-700: #15803d;
		--g-800: #166534;

		/* Red */
		--r-50: #fff1f2;
		--r-100: #ffe4e6;
		--r-200: #fecdd3;
		--r-400: #fb7185;
		--r-500: #f43f5e;
		--r-600: #e11d48;
		--r-700: #be123c;

		/* Amber */
		--am-50: #fffbeb;
		--am-100: #fef3c7;
		--am-200: #fde68a;
		--am-500: #f59e0b;
		--am-600: #d97706;

		/* Purple */
		--pu-50: #faf5ff;
		--pu-100: #f3e8ff;
		--pu-200: #e9d5ff;
		--pu-500: #a855f7;
		--pu-600: #9333ea;
		--pu-700: #7e22ce;

		/* Cyan */
		--cy-50: #ecfeff;
		--cy-100: #cffafe;
		--cy-600: #0891b2;

		/* Pink / referral */
		--pk-50: #fdf2f8;
		--pk-100: #fce7f3;
		--pk-600: #db2777;

		/* Typography */
		--f-body: 'DM Sans', system-ui, -apple-system, sans-serif;
		--f-display: 'DM Serif Display', Georgia, serif;
		--f-mono: 'SF Mono', 'Fira Code', monospace;

		/* Radii */
		--r-sm: 6px;
		--r-md: 10px;
		--r-lg: 14px;
		--r-xl: 18px;
		--r-2xl: 24px;

		/* Shadows */
		--sh-xs: 0 1px 2px rgba(14, 17, 23, .04);
		--sh-sm: 0 2px 8px rgba(14, 17, 23, .06), 0 1px 3px rgba(14, 17, 23, .04);
		--sh-md: 0 6px 24px rgba(14, 17, 23, .08), 0 2px 8px rgba(14, 17, 23, .04);
	}

	body {
		font-family: var(--f-body);
		background: var(--base);
		color: var(--ink);
		font-size: 14px;
		line-height: 1.6;
		-webkit-font-smoothing: antialiased;
	}

	/* ───────────────────────────────────
   ROOT
─────────────────────────────────────*/
	.wlt-root {
		padding: 16px 0 60px;
		display: flex;
		flex-direction: column;
		gap: 18px;
	}

	/* ───────────────────────────────────
   ALERTS
─────────────────────────────────────*/
	.wlt-alert {
		display: flex;
		align-items: center;
		gap: 10px;
		padding: 12px 16px;
		border-radius: var(--r-lg);
		font-size: 13.5px;
		font-weight: 500;
		border: 1px solid transparent;
		box-shadow: var(--sh-sm);
	}

	.wlt-alert__icon {
		width: 28px;
		height: 28px;
		border-radius: var(--r-md);
		display: flex;
		align-items: center;
		justify-content: center;
		flex-shrink: 0;
	}

	.wlt-alert__icon svg {
		width: 14px;
		height: 14px;
	}

	.wlt-alert--err {
		background: var(--r-50);
		border-color: var(--r-200);
		color: var(--r-700);
	}

	.wlt-alert--err .wlt-alert__icon {
		background: var(--r-100);
		color: var(--r-600);
	}

	.wlt-alert--ok {
		background: var(--g-50);
		border-color: var(--g-200);
		color: var(--g-800);
	}

	.wlt-alert--ok .wlt-alert__icon {
		background: var(--g-100);
		color: var(--g-600);
	}

	.wlt-alert__close {
		margin-left: auto;
		border: none;
		background: none;
		cursor: pointer;
		font-size: 18px;
		color: inherit;
		opacity: .4;
		padding: 2px 4px;
		border-radius: 4px;
	}

	.wlt-alert__close:hover {
		opacity: 1;
	}

	/* ───────────────────────────────────
   HERO
─────────────────────────────────────*/
	.wlt-hero {
		position: relative;
		overflow: hidden;
		border-radius: var(--r-2xl);
		background: linear-gradient(140deg, #0b0f20 0%, #0e1535 45%, #152358 75%, #1a3a8f 100%);
		box-shadow: 0 20px 60px rgba(11, 15, 32, .25);
	}

	.wlt-hero__bg {
		position: absolute;
		inset: 0;
		pointer-events: none;
		background:
			radial-gradient(ellipse 55% 90% at 95% 50%, rgba(59, 130, 246, .4) 0%, transparent 65%),
			radial-gradient(ellipse 35% 55% at 8% 15%, rgba(124, 58, 237, .22) 0%, transparent 60%),
			radial-gradient(ellipse 45% 70% at 55% 110%, rgba(8, 145, 178, .15) 0%, transparent 60%);
	}

	.wlt-hero__inner {
		position: relative;
		z-index: 1;
		padding: 32px 36px;
		display: flex;
		justify-content: space-between;
		align-items: center;
		gap: 28px;
		flex-wrap: wrap;
	}

	.wlt-hero__left {
		flex: 1;
		min-width: 0;
	}

	.wlt-hero__eyebrow {
		display: inline-flex;
		align-items: center;
		gap: 7px;
		font-size: 11px;
		font-weight: 600;
		letter-spacing: .12em;
		text-transform: uppercase;
		color: rgba(255, 255, 255, .5);
		margin-bottom: 10px;
	}

	.wlt-hero__eyebrow svg {
		width: 13px;
		height: 13px;
	}

	.wlt-hero__title {
		font-family: var(--f-display);
		font-size: clamp(22px, 3.5vw, 38px);
		color: #fff;
		letter-spacing: -.02em;
		line-height: 1.1;
		margin-bottom: 8px;
	}

	.wlt-hero__sub {
		font-size: 13.5px;
		color: rgba(255, 255, 255, .55);
		line-height: 1.65;
		max-width: 460px;
		margin-bottom: 22px;
	}

	.wlt-hero__chips {
		display: flex;
		gap: 10px;
		flex-wrap: wrap;
	}

	.wlt-hero__chip {
		padding: 12px 18px;
		border-radius: var(--r-lg);
		background: rgba(255, 255, 255, .08);
		border: 1px solid rgba(255, 255, 255, .12);
		backdrop-filter: blur(8px);
	}

	.wlt-hero__chip--green {
		border-color: rgba(34, 197, 94, .25);
	}

	.wlt-hero__chip--red {
		border-color: rgba(244, 63, 94, .25);
	}

	.wlt-hero__chip-label {
		display: block;
		font-size: 10.5px;
		font-weight: 600;
		letter-spacing: .1em;
		text-transform: uppercase;
		color: rgba(255, 255, 255, .45);
		margin-bottom: 4px;
	}

	.wlt-hero__chip-val {
		display: block;
		font-family: var(--f-display);
		font-size: 20px;
		color: #fff;
		line-height: 1;
	}

	.wlt-hero__chip--green .wlt-hero__chip-val {
		color: #86efac;
	}

	.wlt-hero__chip--red .wlt-hero__chip-val {
		color: #fda4af;
	}

	/* Balance card */
	.wlt-hero__balance-card {
		flex-shrink: 0;
		padding: 26px 32px;
		border-radius: var(--r-xl);
		background: rgba(255, 255, 255, .07);
		border: 1px solid rgba(255, 255, 255, .12);
		backdrop-filter: blur(16px);
		text-align: center;
		min-width: 210px;
	}

	.wlt-hero__balance-label {
		display: block;
		font-size: 11px;
		font-weight: 600;
		letter-spacing: .12em;
		text-transform: uppercase;
		color: rgba(255, 255, 255, .45);
		margin-bottom: 10px;
	}

	.wlt-hero__balance-val {
		display: block;
		font-family: var(--f-display);
		font-size: 40px;
		color: #fff;
		line-height: 1;
		margin-bottom: 14px;
	}

	.wlt-hero__balance-footer {
		display: flex;
		align-items: center;
		justify-content: center;
		gap: 5px;
		font-size: 11.5px;
		color: rgba(255, 255, 255, .35);
		font-weight: 500;
	}

	.wlt-hero__balance-footer svg {
		width: 12px;
		height: 12px;
	}

	/* ───────────────────────────────────
   ACTION CARDS
─────────────────────────────────────*/
	.wlt-actions {
		display: grid;
		grid-template-columns: repeat(3, 1fr);
		gap: 16px;
		align-items: start;
	}

	.wlt-card {
		background: var(--surface);
		border: 1px solid var(--line);
		border-radius: var(--r-xl);
		padding: 22px 22px 24px;
		box-shadow: var(--sh-xs);
	}

	.wlt-card__head {
		display: flex;
		align-items: center;
		gap: 12px;
		margin-bottom: 10px;
	}

	.wlt-card__head-icon {
		width: 38px;
		height: 38px;
		border-radius: var(--r-md);
		display: flex;
		align-items: center;
		justify-content: center;
		flex-shrink: 0;
	}

	.wlt-card__head-icon svg {
		width: 16px;
		height: 16px;
	}

	.wlt-card__head-icon--blue {
		background: var(--b-50);
		color: var(--b-600);
		border: 1px solid var(--b-200);
	}

	.wlt-card__head-icon--red {
		background: var(--r-50);
		color: var(--r-600);
		border: 1px solid var(--r-200);
	}

	.wlt-card__head-icon--green {
		background: var(--g-50);
		color: var(--g-700);
		border: 1px solid var(--g-200);
	}

	.wlt-card__head-icon--gray {
		background: var(--base-2);
		color: var(--ink-4);
		border: 1px solid var(--line);
	}

	.wlt-card__eyebrow {
		font-size: 10.5px;
		font-weight: 600;
		letter-spacing: .1em;
		text-transform: uppercase;
		color: var(--ink-5);
		margin-bottom: 2px;
	}

	.wlt-card__title {
		font-size: 17px;
		font-weight: 700;
		color: var(--ink);
		letter-spacing: -.01em;
	}

	.wlt-card__desc {
		font-size: 12.5px;
		color: var(--ink-4);
		line-height: 1.55;
		margin-bottom: 16px;
		padding-bottom: 16px;
		border-bottom: 1px solid var(--line);
	}

	.wlt-icon-btn {
		margin-left: auto;
		width: 32px;
		height: 32px;
		border-radius: var(--r-sm);
		border: 1px solid var(--line-2);
		background: var(--base);
		display: flex;
		align-items: center;
		justify-content: center;
		color: var(--ink-3);
		text-decoration: none;
		flex-shrink: 0;
		transition: background .15s, color .15s;
	}

	.wlt-icon-btn svg {
		width: 13px;
		height: 13px;
	}

	.wlt-icon-btn:hover {
		background: var(--base-2);
		color: var(--ink);
	}

	/* Form */
	.wlt-form {
		display: flex;
		flex-direction: column;
		gap: 12px;
	}

	.wlt-field {
		display: flex;
		flex-direction: column;
		gap: 6px;
	}

	.wlt-label {
		font-size: 11.5px;
		font-weight: 600;
		letter-spacing: .07em;
		text-transform: uppercase;
		color: var(--ink-3);
	}

	.wlt-input-wrap {
		position: relative;
		display: flex;
		align-items: center;
	}

	.wlt-input-prefix {
		position: absolute;
		left: 12px;
		font-size: 14px;
		font-weight: 600;
		color: var(--ink-4);
		pointer-events: none;
	}

	.wlt-input {
		width: 100%;
		height: 44px;
		padding: 0 13px;
		border: 1.5px solid var(--line-2);
		border-radius: var(--r-md);
		background: var(--base);
		color: var(--ink);
		font-family: var(--f-body);
		font-size: 14px;
		font-weight: 500;
		transition: border-color .15s, box-shadow .15s, background .15s;
		-webkit-appearance: none;
	}

	.wlt-input--prefixed {
		padding-left: 28px;
	}

	.wlt-input:hover {
		border-color: var(--ink-5);
	}

	.wlt-input:focus {
		outline: none;
		border-color: var(--b-500);
		box-shadow: 0 0 0 3.5px rgba(59, 130, 246, .12);
		background: var(--surface);
	}

	.wlt-input:disabled {
		opacity: .45;
		cursor: not-allowed;
	}

	.wlt-field-hint {
		font-size: 12px;
		color: var(--ink-5);
	}

	.wlt-field-hint strong {
		color: var(--ink-3);
	}

	/* Buttons */
	.wlt-btn {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		gap: 7px;
		padding: 11px 20px;
		border-radius: var(--r-md);
		font-family: var(--f-body);
		font-size: 13.5px;
		font-weight: 600;
		border: 1.5px solid transparent;
		cursor: pointer;
		transition: all .15s;
		text-decoration: none;
		white-space: nowrap;
	}

	.wlt-btn svg {
		width: 14px;
		height: 14px;
	}

	.wlt-btn--full {
		width: 100%;
	}

	.wlt-btn--blue {
		background: var(--b-600);
		border-color: var(--b-700);
		color: #fff;
		box-shadow: 0 2px 10px rgba(37, 99, 235, .22);
	}

	.wlt-btn--blue:hover {
		background: var(--b-700);
		box-shadow: 0 4px 18px rgba(37, 99, 235, .35);
	}

	.wlt-btn--red {
		background: var(--r-600);
		border-color: var(--r-700);
		color: #fff;
		box-shadow: 0 2px 10px rgba(225, 29, 72, .22);
	}

	.wlt-btn--red:hover {
		background: var(--r-700);
		box-shadow: 0 4px 18px rgba(225, 29, 72, .35);
	}

	.wlt-btn--ghost {
		background: var(--base);
		border-color: var(--line-2);
		color: var(--ink-3);
	}

	.wlt-btn--ghost:hover {
		background: var(--base-2);
		color: var(--ink-2);
	}

	.wlt-btn--outline {
		background: var(--surface);
		border-color: var(--line-2);
		color: var(--ink-2);
	}

	.wlt-btn--outline:hover {
		background: var(--base);
	}

	.wlt-btn--disabled {
		background: var(--base-2);
		border-color: var(--line);
		color: var(--ink-5);
		cursor: not-allowed;
	}

	/* Bank */
	.wlt-bank-form {
		display: flex;
		flex-direction: column;
		gap: 12px;
	}

	.wlt-bank-form__actions {
		display: flex;
		justify-content: flex-end;
		gap: 8px;
		padding-top: 4px;
	}

	.wlt-bank-grid {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 10px;
	}

	.wlt-bank-box {
		padding: 12px 14px;
		border-radius: var(--r-md);
		background: var(--base);
		border: 1px solid var(--line);
	}

	.wlt-bank-box--accent {
		background: var(--b-50);
		border-color: var(--b-100);
	}

	.wlt-bank-box__label {
		display: block;
		font-size: 10px;
		font-weight: 600;
		letter-spacing: .1em;
		text-transform: uppercase;
		color: var(--ink-5);
		margin-bottom: 5px;
	}

	.wlt-bank-box__val {
		display: block;
		font-size: 13.5px;
		font-weight: 600;
		color: var(--ink-2);
		word-break: break-all;
	}

	.wlt-bank-box__val--mono {
		font-family: var(--f-mono);
		font-size: 13px;
		color: var(--b-700);
		letter-spacing: .04em;
	}

	/* ───────────────────────────────────
   TRANSACTION SECTION
─────────────────────────────────────*/
	.wlt-tx-section {
		background: var(--surface);
		border: 1px solid var(--line);
		border-radius: var(--r-xl);
		overflow: hidden;
		box-shadow: var(--sh-sm);
	}

	.wlt-tx-header {
		padding: 20px 24px 0;
	}

	.wlt-tx-header__top {
		display: flex;
		align-items: center;
		gap: 12px;
		margin-bottom: 16px;
	}

	.wlt-eyebrow-plain {
		font-size: 10.5px;
		font-weight: 600;
		letter-spacing: .12em;
		text-transform: uppercase;
		color: var(--ink-5);
		margin-bottom: 3px;
	}

	.wlt-tx-title {
		font-family: var(--f-display);
		font-size: 22px;
		color: var(--ink);
		letter-spacing: -.02em;
	}

	.wlt-tx-count {
		display: inline-flex;
		align-items: center;
		padding: 4px 12px;
		border-radius: 999px;
		background: var(--base-2);
		border: 1px solid var(--line-2);
		font-size: 12px;
		font-weight: 600;
		color: var(--ink-4);
		margin-left: 4px;
	}

	/* Filter bar */
	.wlt-filter-bar {
		display: flex;
		align-items: center;
		gap: 6px;
		flex-wrap: wrap;
		padding-bottom: 16px;
		border-bottom: 1px solid var(--line);
	}

	.wlt-filter-pill {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		padding: 7px 15px;
		border-radius: 999px;
		font-size: 13px;
		font-weight: 600;
		border: 1.5px solid var(--line-2);
		color: var(--ink-3);
		background: var(--surface);
		cursor: pointer;
		transition: all .15s;
		white-space: nowrap;
		font-family: var(--f-body);
	}

	.wlt-filter-pill__dot {
		width: 6px;
		height: 6px;
		border-radius: 50%;
		background: currentColor;
		opacity: .4;
	}

	.wlt-filter-pill:hover {
		border-color: var(--b-500);
		color: var(--b-600);
		background: var(--b-50);
	}

	.wlt-filter-pill.is-active {
		background: var(--pill-color, var(--b-600));
		border-color: transparent;
		color: #fff;
		box-shadow: 0 2px 10px rgba(0, 0, 0, .2);
	}

	.wlt-filter-pill.is-active .wlt-filter-pill__dot {
		opacity: 1;
		background: rgba(255, 255, 255, .8);
	}

	/* Rows selector */
	.wlt-rows-wrap {
		margin-left: auto;
		position: relative;
	}

	.wlt-rows-btn {
		display: inline-flex;
		align-items: center;
		gap: 7px;
		padding: 7px 13px;
		border-radius: 999px;
		font-size: 13px;
		font-weight: 600;
		color: var(--ink-3);
		background: var(--base);
		border: 1.5px solid var(--line-2);
		cursor: pointer;
		font-family: var(--f-body);
		transition: background .15s;
	}

	.wlt-rows-btn svg {
		width: 13px;
		height: 13px;
	}

	#rowsChevron {
		transition: transform .2s;
	}

	.wlt-rows-btn.open #rowsChevron {
		transform: rotate(180deg);
	}

	.wlt-rows-dropdown {
		position: absolute;
		top: calc(100% + 6px);
		right: 0;
		z-index: 50;
		background: var(--surface);
		border: 1px solid var(--line-2);
		border-radius: var(--r-lg);
		box-shadow: var(--sh-md);
		min-width: 130px;
		overflow: hidden;
		display: none;
	}

	.wlt-rows-dropdown.open {
		display: block;
	}

	.wlt-rows-opt {
		padding: 9px 16px;
		font-size: 13.5px;
		font-weight: 500;
		color: var(--ink-2);
		cursor: pointer;
		transition: background .1s;
		display: flex;
		align-items: center;
		justify-content: space-between;
	}

	.wlt-rows-opt:hover {
		background: var(--base);
	}

	.wlt-rows-opt.is-active {
		color: var(--b-600);
		font-weight: 700;
		background: var(--b-50);
	}

	.wlt-rows-opt.is-active::after {
		content: '';
		width: 6px;
		height: 6px;
		border-radius: 50%;
		background: var(--b-600);
	}

	/* Table */
	.wlt-table-wrap {
		overflow-x: auto;
	}

	.wlt-table {
		width: 100%;
		border-collapse: collapse;
		min-width: 680px;
	}

	.wlt-table thead tr {
		background: var(--base);
	}

	.wlt-table th {
		padding: 12px 20px;
		font-size: 10.5px;
		font-weight: 700;
		letter-spacing: .08em;
		text-transform: uppercase;
		color: var(--ink-5);
		text-align: left;
		border-bottom: 1px solid var(--line);
	}

	.wlt-table td {
		padding: 14px 20px;
		border-top: 1px solid var(--line);
		font-size: 13.5px;
		color: var(--ink);
		vertical-align: middle;
	}

	.wlt-table tbody tr {
		transition: background .1s;
	}

	.wlt-table tbody tr:hover {
		background: var(--base);
	}

	.wlt-td-num {
		color: var(--ink-5) !important;
		font-size: 12px !important;
		font-weight: 500;
	}

	.wlt-td-desc {
		color: var(--ink-4) !important;
		font-size: 13px !important;
	}

	.wlt-td-credit {
		color: var(--g-700) !important;
		font-weight: 700;
	}

	.wlt-td-debit {
		color: var(--r-600) !important;
		font-weight: 700;
	}

	.wlt-td-date {
		color: var(--ink-4) !important;
		font-size: 12.5px !important;
		white-space: nowrap;
	}

	/* Badges */
	.wlt-badge {
		display: inline-flex;
		align-items: center;
		gap: 5px;
		padding: 4px 11px;
		border-radius: 999px;
		font-size: 11.5px;
		font-weight: 700;
		white-space: nowrap;
	}

	.wlt-badge-dot {
		width: 5px;
		height: 5px;
		border-radius: 50%;
		background: currentColor;
	}

	.wlt-badge--deposit {
		background: var(--pu-50);
		color: var(--pu-700);
	}

	.wlt-badge--winning {
		background: var(--g-50);
		color: var(--g-800);
	}

	.wlt-badge--trade {
		background: var(--am-50);
		color: var(--am-600);
	}

	.wlt-badge--withdrawal {
		background: var(--r-50);
		color: var(--r-700);
	}

	.wlt-badge--referral {
		background: var(--pk-50);
		color: var(--pk-600);
	}

	.wlt-badge--refund {
		background: var(--cy-50);
		color: var(--cy-600);
	}

	.wlt-badge--default {
		background: var(--base-2);
		color: var(--ink-3);
	}

	/* Status badges */
	.wlt-badge--success {
		background: var(--g-50);
		color: var(--g-800);
	}

	.wlt-badge--processed {
		background: var(--b-50);
		color: var(--b-700);
	}

	.wlt-badge--pending {
		background: var(--am-50);
		color: var(--am-600);
	}

	.wlt-badge--rejected {
		background: var(--r-50);
		color: var(--r-700);
	}

	/* Empty state */
	.wlt-empty-row td {
		text-align: center;
		padding: 52px 20px;
		color: var(--ink-5);
		font-size: 14px;
	}

	/* Pagination */
	.wlt-pagination {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 12px;
		padding: 13px 20px;
		border-top: 1px solid var(--line);
	}

	.wlt-page-info {
		font-size: 13px;
		color: var(--ink-5);
	}

	.wlt-page-btns {
		display: flex;
		gap: 5px;
		align-items: center;
	}

	.wlt-page-btn {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		min-width: 34px;
		height: 34px;
		padding: 0 6px;
		border-radius: var(--r-md);
		border: 1.5px solid var(--line-2);
		background: var(--surface);
		color: var(--ink-2);
		font-family: var(--f-body);
		font-size: 13px;
		font-weight: 600;
		cursor: pointer;
		transition: all .12s;
	}

	.wlt-page-btn:hover:not(:disabled) {
		background: var(--base-2);
	}

	.wlt-page-btn.is-active {
		background: var(--b-600);
		color: #fff;
		border-color: var(--b-600);
	}

	.wlt-page-btn:disabled {
		opacity: .3;
		cursor: not-allowed;
	}

	.wlt-page-dots {
		padding: 0 4px;
		color: var(--ink-5);
		font-size: 13px;
		align-self: center;
	}

	/* Reveal */
	[data-reveal] {
		opacity: 0;
		transform: translateY(10px);
		transition: opacity .4s ease, transform .4s ease;
	}

	[data-reveal].revealed {
		opacity: 1;
		transform: none;
	}

	/* ───────────────────────────────────
   RESPONSIVE
─────────────────────────────────────*/
	@media (max-width: 1100px) {
		.wlt-actions {
			grid-template-columns: 1fr 1fr;
		}

		.wlt-card--bank {
			grid-column: 1 / -1;
		}

		.wlt-bank-grid {
			grid-template-columns: repeat(4, 1fr);
		}
	}

	@media (max-width: 860px) {
		.wlt-hero__inner {
			flex-direction: column;
			align-items: flex-start;
		}

		.wlt-hero__balance-card {
			width: 100%;
			text-align: left;
			display: flex;
			align-items: center;
			gap: 20px;
		}

		.wlt-hero__balance-label {
			margin-bottom: 0;
		}
	}

	@media (max-width: 700px) {
		.wlt-actions {
			grid-template-columns: 1fr;
		}

		.wlt-card--bank {
			grid-column: auto;
		}

		.wlt-bank-grid {
			grid-template-columns: 1fr 1fr;
		}

		.wlt-hero__inner {
			padding: 24px 20px;
		}

		.wlt-hero__chips {
			flex-direction: column;
		}

		.wlt-hero__chip {
			padding: 10px 14px;
		}

		.wlt-filter-pill {
			font-size: 12px;
			padding: 6px 12px;
		}
	}
</style>

<script>
	(function() {
		'use strict';

		/* ── Staggered reveal ── */
		document.querySelectorAll('[data-reveal]').forEach(function(el, i) {
			el.style.transitionDelay = (i * 70) + 'ms';
			requestAnimationFrame(function() {
				requestAnimationFrame(function() {
					el.classList.add('revealed');
				});
			});
		});

		/* ── Alert auto-dismiss ── */
		document.querySelectorAll('.wlt-alert').forEach(function(el) {
			setTimeout(function() {
				el.style.transition = 'opacity .4s ease, transform .4s ease';
				el.style.opacity = '0';
				el.style.transform = 'translateY(-6px)';
				setTimeout(function() {
					el.remove();
				}, 420);
			}, 5000);
		});

		/* ── Rows dropdown ── */
		var rowsBtn = document.getElementById('rowsBtn');
		var rowsDropdown = document.getElementById('rowsDropdown');
		var rowsLabel = document.getElementById('rowsLabel');

		if (rowsBtn) {
			rowsBtn.addEventListener('click', function(e) {
				e.stopPropagation();
				var open = rowsDropdown.classList.toggle('open');
				rowsBtn.classList.toggle('open', open);
			});
			document.addEventListener('click', function() {
				rowsDropdown.classList.remove('open');
				rowsBtn.classList.remove('open');
			});
		}

		/* ── State ── */
		var currentFilter = '<?php echo $history_filter; ?>';
		var rowsPerPage = 10;
		var currentPage = 1;

		/* ── Rows options ── */
		document.querySelectorAll('.wlt-rows-opt').forEach(function(opt) {
			opt.addEventListener('click', function() {
				rowsPerPage = parseInt(this.dataset.rows, 10);
				currentPage = 1;
				rowsLabel.textContent = rowsPerPage >= 99999 ? 'All rows' : rowsPerPage + ' rows';
				document.querySelectorAll('.wlt-rows-opt').forEach(function(o) {
					o.classList.remove('is-active');
				});
				this.classList.add('is-active');
				rowsDropdown.classList.remove('open');
				rowsBtn.classList.remove('open');
				render();
			});
		});

		/* ── Filter pills ── */
		document.querySelectorAll('.wlt-filter-pill').forEach(function(pill) {
			pill.addEventListener('click', function() {
				currentFilter = this.dataset.filter;
				currentPage = 1;
				var color = this.dataset.color || '';
				document.querySelectorAll('.wlt-filter-pill').forEach(function(p) {
					p.classList.remove('is-active');
					p.style.removeProperty('--pill-color');
				});
				this.classList.add('is-active');
				if (color) this.style.setProperty('--pill-color', color);
				render();
			});
		});

		/* ── Get visible rows ── */
		function getRows() {
			var all = Array.from(document.querySelectorAll('#txBody tr[data-filter-type]'));
			return currentFilter === 'all' ?
				all :
				all.filter(function(r) {
					return r.dataset.filterType === currentFilter;
				});
		}

		/* ── Render ── */
		function render() {
			var rows = getRows();
			var total = rows.length;
			var limit = rowsPerPage >= 99999 ? total : rowsPerPage;
			var pages = Math.max(1, Math.ceil(total / (limit || 1)));
			currentPage = Math.min(currentPage, pages);
			var start = (currentPage - 1) * limit;
			var end = start + limit;

			/* Hide all, show slice */
			Array.from(document.querySelectorAll('#txBody tr[data-filter-type]'))
				.forEach(function(r) {
					r.style.display = 'none';
				});
			rows.forEach(function(r, i) {
				r.style.display = (i >= start && i < end) ? '' : 'none';
				var nc = r.querySelector('.wlt-td-num');
				if (nc) nc.textContent = i + 1;
			});

			/* Empty state */
			var emptyRow = document.getElementById('txEmpty');
			if (total === 0) {
				if (!emptyRow) {
					emptyRow = document.createElement('tr');
					emptyRow.id = 'txEmpty';
					emptyRow.className = 'wlt-empty-row';
					emptyRow.innerHTML = '<td colspan="6">No transactions found for this filter.</td>';
					document.getElementById('txBody').appendChild(emptyRow);
				}
				emptyRow.style.display = '';
			} else if (emptyRow) {
				emptyRow.style.display = 'none';
			}

			/* Count */
			var countEl = document.getElementById('tx-count');
			if (countEl) countEl.textContent = total + ' record' + (total !== 1 ? 's' : '');

			/* Page info */
			var infoEl = document.getElementById('txPageInfo');
			if (infoEl) {
				var actualEnd = Math.min(end, total);
				infoEl.textContent = total === 0 ?
					'No records' :
					'Showing ' + (start + 1) + '–' + actualEnd + ' of ' + total;
			}

			/* Pagination buttons */
			var bc = document.getElementById('txPageBtns');
			bc.innerHTML = '';
			var prevBtn = makeBtn('‹', currentPage <= 1);
			prevBtn.addEventListener('click', function() {
				if (currentPage > 1) {
					currentPage--;
					render();
				}
			});
			bc.appendChild(prevBtn);

			var spread = 2;
			var lastWasDot = false;
			for (var p = 1; p <= pages; p++) {
				var show = p === 1 || p === pages || (p >= currentPage - spread && p <= currentPage + spread);
				if (show) {
					lastWasDot = false;
					bc.appendChild(makeBtn(p, false, p === currentPage));
				} else if (!lastWasDot) {
					lastWasDot = true;
					var dots = document.createElement('span');
					dots.className = 'wlt-page-dots';
					dots.textContent = '…';
					bc.appendChild(dots);
				}
			}
			bc.querySelectorAll('.wlt-page-btn:not([disabled])').forEach(function(btn) {
				var pg = parseInt(btn.textContent, 10);
				if (!isNaN(pg)) {
					btn.addEventListener('click', (function(n) {
						return function() {
							currentPage = n;
							render();
						};
					}(pg)));
				}
			});

			var nextBtn = makeBtn('›', currentPage >= pages);
			nextBtn.addEventListener('click', function() {
				if (currentPage < pages) {
					currentPage++;
					render();
				}
			});
			bc.appendChild(nextBtn);

			var paginationEl = document.getElementById('txPagination');
			if (paginationEl) {
				paginationEl.style.display = (pages <= 1 && total <= limit) ? 'none' : 'flex';
			}
		}

		function makeBtn(label, disabled, active) {
			var b = document.createElement('button');
			b.className = 'wlt-page-btn' + (active ? ' is-active' : '');
			b.textContent = label;
			b.disabled = !!disabled;
			return b;
		}

		render();
	}());
</script>