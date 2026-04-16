<?php
$pending_requests  = array_filter($requests ?? [], fn($r) => $r->status === 'pending');
$approved_requests = array_filter($requests ?? [], fn($r) => $r->status === 'approved');
$rejected_requests = array_filter($requests ?? [], fn($r) => $r->status === 'rejected');
$all_sorted = array_merge(
    array_values($pending_requests),
    array_values($approved_requests),
    array_values($rejected_requests)
);
?>

<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400&display=swap" rel="stylesheet">

<div class="dr-wrap">

    <!-- Page Header -->
    <div class="dr-header">
        <div class="dr-header__left">
            <div class="dr-header__icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="5" width="20" height="14" rx="2" />
                    <line x1="2" y1="10" x2="22" y2="10" />
                </svg>
            </div>
            <div>
                <h1 class="dr-header__title">Deposit Requests</h1>
                <p class="dr-header__sub">Review and manage incoming deposit requests</p>
            </div>
        </div>
        <button class="dr-refresh-btn" onclick="location.reload()" title="Refresh page">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="23 4 23 10 17 10" />
                <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10" />
            </svg>
            <span>Refresh</span>
        </button>
    </div>

    <!-- Stats Grid -->
    <div class="dr-stats">
        <div class="dr-stat dr-stat--pending">
            <div class="dr-stat__icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10" />
                    <polyline points="12 6 12 12 16 14" />
                </svg>
            </div>
            <div class="dr-stat__body">
                <span class="dr-stat__num"><?php echo $stats['pending']; ?></span>
                <span class="dr-stat__lbl">Pending</span>
            </div>
        </div>
        <div class="dr-stat dr-stat--approved">
            <div class="dr-stat__icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                    <polyline points="22 4 12 14.01 9 11.01" />
                </svg>
            </div>
            <div class="dr-stat__body">
                <span class="dr-stat__num"><?php echo $stats['approved']; ?></span>
                <span class="dr-stat__lbl">Approved</span>
            </div>
        </div>
        <div class="dr-stat dr-stat--rejected">
            <div class="dr-stat__icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="15" y1="9" x2="9" y2="15" />
                    <line x1="9" y1="9" x2="15" y2="15" />
                </svg>
            </div>
            <div class="dr-stat__body">
                <span class="dr-stat__num"><?php echo $stats['rejected']; ?></span>
                <span class="dr-stat__lbl">Rejected</span>
            </div>
        </div>
        <div class="dr-stat dr-stat--amount">
            <div class="dr-stat__icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="1" x2="12" y2="23" />
                    <path d="M17 5H9.5a3.5 3.5 0 1 0 0 7h5a3.5 3.5 0 1 1 0 7H6" />
                </svg>
            </div>
            <div class="dr-stat__body">
                <span class="dr-stat__num dr-stat__num--sm">₹<?php echo number_format($stats['approved_amount'], 0); ?></span>
                <span class="dr-stat__lbl">Approved Total</span>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="dr-tabs-wrap">
        <div class="dr-tabs">
            <a href="<?php echo site_url('admin/deposits/requests'); ?>"
                class="dr-tab <?php echo (!$status_filter) ? 'dr-tab--active' : ''; ?>">
                All
                <span class="dr-tab__count"><?php echo $stats['total']; ?></span>
            </a>
            <a href="<?php echo site_url('admin/deposits/requests?status=pending'); ?>"
                class="dr-tab <?php echo ($status_filter == 'pending') ? 'dr-tab--active dr-tab--pending' : ''; ?>">
                Pending
                <?php if (!empty($stats['pending'])): ?>
                    <span class="dr-tab__count dr-tab__count--hot"><?php echo $stats['pending']; ?></span>
                <?php endif; ?>
            </a>
            <a href="<?php echo site_url('admin/deposits/requests?status=approved'); ?>"
                class="dr-tab <?php echo ($status_filter == 'approved') ? 'dr-tab--active dr-tab--approved' : ''; ?>">
                Approved
                <span class="dr-tab__count"><?php echo $stats['approved']; ?></span>
            </a>
            <a href="<?php echo site_url('admin/deposits/requests?status=rejected'); ?>"
                class="dr-tab <?php echo ($status_filter == 'rejected') ? 'dr-tab--active dr-tab--rejected' : ''; ?>">
                Rejected
                <span class="dr-tab__count"><?php echo $stats['rejected']; ?></span>
            </a>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="dr-flash dr-flash--success" id="flashMsg">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                <polyline points="22 4 12 14.01 9 11.01" />
            </svg>
            <span><?php echo $this->session->flashdata('success'); ?></span>
            <button class="dr-flash__close" onclick="this.parentElement.remove()">×</button>
        </div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="dr-flash dr-flash--error" id="flashMsg">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10" />
                <line x1="12" y1="8" x2="12" y2="12" />
                <line x1="12" y1="16" x2="12.01" y2="16" />
            </svg>
            <span><?php echo $this->session->flashdata('error'); ?></span>
            <button class="dr-flash__close" onclick="this.parentElement.remove()">×</button>
        </div>
    <?php endif; ?>

    <!-- Main Content -->
    <div class="dr-card">
        <?php if (!empty($all_sorted)): ?>

            <!-- Desktop Table -->
            <div class="dr-desktop-table">
                <table class="dr-table">
                    <thead>
                        <tr>
                            <th class="dr-th dr-th--num">#</th>
                            <th class="dr-th">User</th>
                            <th class="dr-th">Amount</th>
                            <th class="dr-th">Receipt</th>
                            <th class="dr-th">Date</th>
                            <th class="dr-th">Status</th>
                            <th class="dr-th">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $prev_status = null;
                        $row_num = 1;
                        foreach ($all_sorted as $r):
                            if ($prev_status !== null && $prev_status !== $r->status):
                        ?>
                                <tr class="dr-divider-row">
                                    <td colspan="7">
                                        <div class="dr-divider-inner">
                                            <?php if ($r->status === 'approved'): ?>
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                                    <polyline points="22 4 12 14.01 9 11.01" />
                                                </svg>Approved Requests
                                            <?php elseif ($r->status === 'rejected'): ?>
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                    <circle cx="12" cy="12" r="10" />
                                                    <line x1="15" y1="9" x2="9" y2="15" />
                                                    <line x1="9" y1="9" x2="15" y2="15" />
                                                </svg>Rejected Requests
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php
                            endif;
                            $prev_status = $r->status;
                            $avatar_bg = '#' . substr(md5($r->name), 0, 6);
                            ?>
                            <tr class="dr-row dr-row--<?php echo $r->status; ?>" id="row-<?php echo $r->id; ?>">
                                <td class="dr-td dr-td--num"><?php echo $row_num++; ?></td>
                                <td class="dr-td">
                                    <div class="dr-user">
                                        <div class="dr-avatar" style="background:<?php echo $avatar_bg; ?>">
                                            <?php echo strtoupper(substr($r->name, 0, 1)); ?>
                                        </div>
                                        <div>
                                            <div class="dr-user__name"><?php echo htmlspecialchars($r->name); ?></div>
                                            <div class="dr-user__email"><?php echo htmlspecialchars($r->email ?? ''); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="dr-td">
                                    <span class="dr-amount">₹<?php echo number_format($r->amount, 2); ?></span>
                                </td>
                                <td class="dr-td">
                                    <?php if (!empty($r->receipt)): ?>
                                        <a href="<?php echo base_url('uploads/receipts/' . $r->receipt); ?>" target="_blank" class="dr-receipt-btn">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                            View
                                        </a>
                                    <?php else: ?>
                                        <span class="dr-nil">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="dr-td">
                                    <div class="dr-date">
                                        <?php echo isset($r->created_at) ? date('d M Y', strtotime($r->created_at)) : 'N/A'; ?>
                                        <span class="dr-date__time"><?php echo isset($r->created_at) ? date('h:i A', strtotime($r->created_at)) : ''; ?></span>
                                    </div>
                                </td>
                                <td class="dr-td">
                                    <?php if ($r->status === 'pending'): ?>
                                        <span class="dr-badge dr-badge--pending"><span class="dr-pulse"></span>Pending</span>
                                    <?php elseif ($r->status === 'approved'): ?>
                                        <span class="dr-badge dr-badge--approved">
                                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                                <polyline points="20 6 9 17 4 12" />
                                            </svg>
                                            Approved
                                        </span>
                                    <?php else: ?>
                                        <span class="dr-badge dr-badge--rejected">
                                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                                <line x1="18" y1="6" x2="6" y2="18" />
                                                <line x1="6" y1="6" x2="18" y2="18" />
                                            </svg>
                                            Rejected
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="dr-td">
                                    <?php if ($r->status === 'pending'): ?>
                                        <div class="dr-action-btns">
                                            <button class="dr-btn-approve" onclick="handleAction('approve', <?php echo $r->id; ?>, this)">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                    <polyline points="20 6 9 17 4 12" />
                                                </svg>
                                                Approve
                                            </button>
                                            <button class="dr-btn-reject" onclick="handleAction('reject', <?php echo $r->id; ?>, this)">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                    <line x1="18" y1="6" x2="6" y2="18" />
                                                    <line x1="6" y1="6" x2="18" y2="18" />
                                                </svg>
                                                Reject
                                            </button>
                                        </div>
                                    <?php elseif ($r->status === 'approved'): ?>
                                        <div class="dr-processed dr-processed--approved">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                                <polyline points="22 4 12 14.01 9 11.01" />
                                            </svg>
                                            <?php echo isset($r->approved_at) ? date('d M, h:i A', strtotime($r->approved_at)) : 'Processed'; ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="dr-processed dr-processed--rejected">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                <circle cx="12" cy="12" r="10" />
                                                <line x1="15" y1="9" x2="9" y2="15" />
                                                <line x1="9" y1="9" x2="15" y2="15" />
                                            </svg>
                                            <?php echo isset($r->rejected_at) ? date('d M, h:i A', strtotime($r->rejected_at)) : 'Processed'; ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="dr-mobile-list">
                <?php
                $prev_status = null;
                foreach ($all_sorted as $r):
                    if ($prev_status !== null && $prev_status !== $r->status):
                ?>
                        <div class="dr-mobile-sep">
                            <?php if ($r->status === 'approved'): ?>
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                    <polyline points="22 4 12 14.01 9 11.01" />
                                </svg>
                                Approved
                            <?php elseif ($r->status === 'rejected'): ?>
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <circle cx="12" cy="12" r="10" />
                                    <line x1="15" y1="9" x2="9" y2="15" />
                                    <line x1="9" y1="9" x2="15" y2="15" />
                                </svg>
                                Rejected
                            <?php endif; ?>
                        </div>
                    <?php
                    endif;
                    $prev_status = $r->status;
                    $avatar_bg = '#' . substr(md5($r->name), 0, 6);
                    $initials = strtoupper(substr($r->name, 0, 2));
                    ?>
                    <div class="dr-mcard dr-mcard--<?php echo $r->status; ?>" id="mcard-<?php echo $r->id; ?>">
                        <div class="dr-mcard__top">
                            <div class="dr-mcard__user">
                                <div class="dr-mcard__avatar" style="background:<?php echo $avatar_bg; ?>"><?php echo $initials; ?></div>
                                <div class="dr-mcard__info">
                                    <span class="dr-mcard__name"><?php echo htmlspecialchars($r->name); ?></span>
                                    <span class="dr-mcard__email"><?php echo htmlspecialchars($r->email ?? ''); ?></span>
                                </div>
                            </div>
                            <div class="dr-mcard__right">
                                <div class="dr-mcard__amount">₹<?php echo number_format($r->amount, 2); ?></div>
                                <?php if ($r->status === 'pending'): ?>
                                    <span class="dr-badge dr-badge--pending"><span class="dr-pulse"></span>Pending</span>
                                <?php elseif ($r->status === 'approved'): ?>
                                    <span class="dr-badge dr-badge--approved">
                                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                            <polyline points="20 6 9 17 4 12" />
                                        </svg>
                                        Approved
                                    </span>
                                <?php else: ?>
                                    <span class="dr-badge dr-badge--rejected">
                                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                            <line x1="18" y1="6" x2="6" y2="18" />
                                            <line x1="6" y1="6" x2="18" y2="18" />
                                        </svg>
                                        Rejected
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="dr-mcard__meta">
                            <div class="dr-mcard__meta-item">
                                <span class="dr-mcard__meta-label">Date</span>
                                <span class="dr-mcard__meta-val"><?php echo isset($r->created_at) ? date('d M Y, h:i A', strtotime($r->created_at)) : 'N/A'; ?></span>
                            </div>
                            <?php if (!empty($r->receipt)): ?>
                                <div class="dr-mcard__meta-item">
                                    <a href="<?php echo base_url('uploads/receipts/' . $r->receipt); ?>" target="_blank" class="dr-receipt-link">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                            <circle cx="12" cy="12" r="3" />
                                        </svg>
                                        View Receipt
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>

                        <?php if ($r->status === 'pending'): ?>
                            <div class="dr-mcard__actions">
                                <button class="dr-mcard__approve" onclick="handleAction('approve', <?php echo $r->id; ?>, this)">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                    Approve
                                </button>
                                <button class="dr-mcard__reject" onclick="handleAction('reject', <?php echo $r->id; ?>, this)">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <line x1="18" y1="6" x2="6" y2="18" />
                                        <line x1="6" y1="6" x2="18" y2="18" />
                                    </svg>
                                    Reject
                                </button>
                            </div>
                        <?php elseif ($r->status === 'approved'): ?>
                            <div class="dr-mcard__status-bar dr-mcard__status-bar--approved">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                    <polyline points="22 4 12 14.01 9 11.01" />
                                </svg>
                                Approved <?php echo isset($r->approved_at) ? date('d M, h:i A', strtotime($r->approved_at)) : ''; ?>
                            </div>
                        <?php else: ?>
                            <div class="dr-mcard__status-bar dr-mcard__status-bar--rejected">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <circle cx="12" cy="12" r="10" />
                                    <line x1="15" y1="9" x2="9" y2="15" />
                                    <line x1="9" y1="9" x2="15" y2="15" />
                                </svg>
                                Rejected <?php echo isset($r->rejected_at) ? date('d M, h:i A', strtotime($r->rejected_at)) : ''; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Footer -->
            <div class="dr-footer">
                <span class="dr-footer__text">
                    Showing <strong><?php echo count($all_sorted); ?></strong> record(s)
                    <?php if ($status_filter): ?>
                        &mdash; filtered by <strong><?php echo ucfirst($status_filter); ?></strong>
                    <?php endif; ?>
                </span>
                <?php if ($status_filter): ?>
                    <a href="<?php echo site_url('admin/deposits/requests'); ?>" class="dr-clear-link">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <line x1="18" y1="6" x2="6" y2="18" />
                            <line x1="6" y1="6" x2="18" y2="18" />
                        </svg>
                        Clear filter
                    </a>
                <?php endif; ?>
            </div>

        <?php else: ?>
            <div class="dr-empty">
                <div class="dr-empty__icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
                    </svg>
                </div>
                <p class="dr-empty__title">No deposit requests found</p>
                <span class="dr-empty__sub">New requests will appear here automatically</span>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Loading Overlay -->
<div id="drLoadingOverlay" style="display:none;" class="dr-overlay">
    <div class="dr-overlay__box">
        <div class="dr-overlay__spinner"></div>
        <p id="drLoadingText">Processing...</p>
    </div>
</div>

<style>
    *,
    *::before,
    *::after {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    :root {
        --amber-50: #FEF3C7;
        --amber-100: #FDE68A;
        --amber-600: #D97706;
        --amber-700: #B45309;
        --amber-800: #92400E;
        --green-50: #D1FAE5;
        --green-600: #059669;
        --green-700: #047857;
        --green-800: #065F46;
        --red-50: #FEE2E2;
        --red-200: #FECACA;
        --red-600: #DC2626;
        --red-800: #7F1D1D;
        --indigo-50: #EEF2FF;
        --indigo-100: #E0E7FF;
        --indigo-600: #4F46E5;
        --gray-50: #F9FAFB;
        --gray-100: #F3F4F6;
        --gray-200: #E5E7EB;
        --gray-300: #D1D5DB;
        --gray-400: #9CA3AF;
        --gray-500: #6B7280;
        --gray-600: #4B5563;
        --gray-700: #374151;
        --gray-800: #1F2937;
        --gray-900: #111827;
        --radius-sm: 6px;
        --radius-md: 10px;
        --radius-lg: 14px;
    }

    .dr-wrap {
        font-family: 'Roboto', system-ui, -apple-system, sans-serif;
        padding: 16px 12px 48px;
        max-width: 1280px;
        width: 100%;
        margin: 0 auto;
    }

    /* ── Header ── */
    .dr-header {
        width: 100%;
        overflow: hidden;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        gap: 12px;
        flex-wrap: wrap;
    }

    .dr-header__left {
        display: flex;
        align-items: center;
        gap: 14px;
        min-width: 0;
        flex: 1;
    }

    .dr-header__icon {
        flex-shrink: 0;
        width: 44px;
        height: 44px;
        background: var(--indigo-50);
        color: var(--indigo-600);
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .dr-header__title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--gray-900);
        letter-spacing: -0.3px;
        line-height: 1.25;
    }

    .dr-header__sub {
        font-size: 0.78rem;
        color: var(--gray-400);
        margin-top: 2px;
    }

    .dr-refresh-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        background: #fff;
        border: 1px solid var(--gray-200);
        border-radius: var(--radius-sm);
        font-size: 0.82rem;
        font-weight: 500;
        color: var(--gray-600);
        cursor: pointer;
        transition: all .15s;
        font-family: inherit;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .dr-refresh-btn:hover {
        background: var(--gray-50);
        border-color: var(--gray-300);
    }

    /* ── Stats ── */
    .dr-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
        margin-bottom: 20px;
    }

    .dr-stat {
        background: #fff;
        border-radius: var(--radius-md);
        border: 1px solid var(--gray-100);
        padding: 14px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: box-shadow .15s;
    }

    .dr-stat:hover {
        box-shadow: 0 2px 8px rgba(0, 0, 0, .06);
    }

    .dr-stat__icon {
        width: 38px;
        height: 38px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .dr-stat--pending .dr-stat__icon {
        background: var(--amber-50);
        color: var(--amber-600);
    }

    .dr-stat--approved .dr-stat__icon {
        background: var(--green-50);
        color: var(--green-600);
    }

    .dr-stat--rejected .dr-stat__icon {
        background: var(--red-50);
        color: var(--red-600);
    }

    .dr-stat--amount .dr-stat__icon {
        background: var(--indigo-50);
        color: var(--indigo-600);
    }

    .dr-stat__body {
        display: flex;
        flex-direction: column;
        gap: 1px;
        min-width: 0;
    }

    .dr-stat__num {
        font-size: 1.3rem;
        font-weight: 600;
        color: var(--gray-900);
        letter-spacing: -0.5px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .dr-stat__num--sm {
        font-size: 1rem;
    }

    .dr-stat__lbl {
        font-size: 0.75rem;
        color: var(--gray-400);
        font-weight: 500;
    }

    /* ── Tabs ── */
    .dr-tabs-wrap {
        overflow-x: auto;
        margin-bottom: 16px;
        scrollbar-width: thin;
        scrollbar-color: var(--gray-300) transparent;
        -webkit-overflow-scrolling: touch;
    }

    .dr-tabs-wrap::-webkit-scrollbar {
        height: 4px;
    }

    .dr-tabs-wrap::-webkit-scrollbar-track {
        background: transparent;
    }

    .dr-tabs-wrap::-webkit-scrollbar-thumb {
        background: var(--gray-300);
        border-radius: 4px;
    }

    .dr-tabs {
        display: inline-flex;
        gap: 2px;
        background: var(--gray-100);
        border-radius: var(--radius-md);
        padding: 4px;
        min-width: 100%;
        width: max-content;
    }

    .dr-tab {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 16px;
        border-radius: 7px;
        font-size: 0.83rem;
        font-weight: 500;
        color: var(--gray-500);
        text-decoration: none;
        transition: all .15s;
        white-space: nowrap;
    }

    .dr-tab:hover {
        color: var(--gray-700);
        background: rgba(255, 255, 255, .6);
    }

    .dr-tab--active {
        background: #fff;
        color: var(--gray-900);
        box-shadow: 0 1px 3px rgba(0, 0, 0, .08);
    }

    .dr-tab--pending {
        color: var(--amber-700);
    }

    .dr-tab--approved {
        color: var(--green-700);
    }

    .dr-tab--rejected {
        color: var(--red-600);
    }

    .dr-tab__count {
        background: var(--gray-200);
        color: var(--gray-500);
        border-radius: 20px;
        padding: 1px 7px;
        font-size: 0.72rem;
        font-weight: 600;
        min-width: 18px;
        text-align: center;
    }

    .dr-tab__count--hot {
        background: var(--red-50);
        color: var(--red-600);
        animation: hotpulse 2s infinite;
    }

    @keyframes hotpulse {

        0%,
        100% {
            transform: scale(1)
        }

        50% {
            transform: scale(1.1)
        }
    }

    /* ── Flash ── */
    .dr-flash {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        border-radius: var(--radius-md);
        margin-bottom: 14px;
        font-size: 0.86rem;
        font-weight: 500;
        animation: fadeIn .3s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-4px);
        }

        to {
            opacity: 1;
            transform: none;
        }
    }

    .dr-flash--success {
        background: var(--green-50);
        color: var(--green-800);
        border-left: 3px solid var(--green-600);
    }

    .dr-flash--error {
        background: var(--red-50);
        color: var(--red-800);
        border-left: 3px solid var(--red-600);
    }

    .dr-flash span {
        flex: 1;
        min-width: 0;
        word-break: break-word;
    }

    .dr-flash__close {
        border: none;
        background: none;
        cursor: pointer;
        font-size: 18px;
        color: inherit;
        opacity: .5;
        line-height: 1;
        flex-shrink: 0;
        padding: 0 4px;
    }

    .dr-flash__close:hover {
        opacity: 1;
    }

    /* ── Card ── */
    .dr-card {
        background: #fff;
        border-radius: var(--radius-lg);
        border: 1px solid var(--gray-100);
        overflow: hidden;
    }

    /* ── Desktop Table ── */
    .dr-desktop-table {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .dr-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 700px;
    }

    .dr-table thead tr {
        background: var(--gray-50);
        border-bottom: 1px solid var(--gray-100);
    }

    .dr-th {
        padding: 11px 16px;
        text-align: left;
        font-size: 0.72rem;
        font-weight: 600;
        color: var(--gray-400);
        text-transform: uppercase;
        letter-spacing: .05em;
        white-space: nowrap;
    }

    .dr-th--num {
        width: 48px;
    }

    .dr-row {
        border-bottom: 1px solid var(--gray-50);
        transition: background .1s;
    }

    .dr-row:hover {
        background: #FAFAFA;
    }

    .dr-row:last-child {
        border-bottom: none;
    }

    .dr-row--pending {
        border-left: 3px solid #F59E0B;
    }

    .dr-row--approved {
        border-left: 3px solid #10B981;
    }

    .dr-row--rejected {
        border-left: 3px solid #EF4444;
        opacity: .85;
    }

    .dr-td {
        padding: 12px 16px;
        vertical-align: middle;
        font-size: 0.875rem;
        color: var(--gray-700);
    }

    .dr-td--num {
        color: var(--gray-300);
        font-size: 0.78rem;
        font-weight: 600;
    }

    .dr-divider-row td {
        padding: 4px 16px;
        background: var(--gray-50);
        border-top: 1px solid var(--gray-100);
        border-bottom: 1px solid var(--gray-100);
    }

    .dr-divider-inner {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: var(--gray-400);
    }

    .dr-user {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .dr-avatar {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 13px;
        flex-shrink: 0;
    }

    .dr-user__name {
        font-weight: 500;
        font-size: 0.875rem;
        color: var(--gray-900);
    }

    .dr-user__email {
        font-size: 0.76rem;
        color: var(--gray-400);
        margin-top: 1px;
        word-break: break-word;
    }

    .dr-amount {
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--green-800);
        font-family: 'JetBrains Mono', monospace;
    }

    .dr-receipt-btn {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        background: var(--indigo-50);
        color: var(--indigo-600);
        border-radius: 5px;
        font-size: 0.76rem;
        font-weight: 500;
        text-decoration: none;
        transition: background .12s;
        white-space: nowrap;
    }

    .dr-receipt-btn:hover {
        background: var(--indigo-100);
    }

    .dr-nil {
        color: var(--gray-300);
    }

    .dr-date {
        font-size: 0.84rem;
        color: var(--gray-700);
        line-height: 1.4;
    }

    .dr-date__time {
        display: block;
        font-size: 0.74rem;
        color: var(--gray-400);
    }

    /* ── Badges ── */
    .dr-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 9px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .dr-badge--pending {
        background: var(--amber-50);
        color: var(--amber-800);
    }

    .dr-badge--approved {
        background: var(--green-50);
        color: var(--green-800);
    }

    .dr-badge--rejected {
        background: var(--red-50);
        color: var(--red-800);
    }

    .dr-pulse {
        width: 6px;
        height: 6px;
        background: #F59E0B;
        border-radius: 50%;
        display: inline-block;
        animation: blink 1.4s infinite;
    }

    @keyframes blink {

        0%,
        100% {
            opacity: 1;
            transform: scale(1)
        }

        50% {
            opacity: .4;
            transform: scale(.7)
        }
    }

    /* ── Action Buttons ── */
    .dr-action-btns {
        display: flex;
        gap: 5px;
        flex-wrap: wrap;
    }

    .dr-btn-approve,
    .dr-btn-reject {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 5px 10px;
        border: none;
        border-radius: 6px;
        font-size: 0.78rem;
        font-weight: 600;
        cursor: pointer;
        transition: all .15s;
        font-family: inherit;
        white-space: nowrap;
    }

    .dr-btn-approve {
        background: #10B981;
        color: #fff;
    }

    .dr-btn-approve:hover {
        background: #059669;
        transform: translateY(-1px);
    }

    .dr-btn-approve:disabled {
        opacity: .5;
        cursor: not-allowed;
        transform: none;
    }

    .dr-btn-reject {
        background: var(--red-50);
        color: var(--red-600);
    }

    .dr-btn-reject:hover {
        background: var(--red-600);
        color: #fff;
        transform: translateY(-1px);
    }

    .dr-btn-reject:disabled {
        opacity: .5;
        cursor: not-allowed;
        transform: none;
    }

    .dr-processed {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 0.78rem;
        color: var(--gray-400);
    }

    .dr-processed--approved {
        color: var(--green-600);
    }

    .dr-processed--rejected {
        color: var(--red-600);
    }

    /* ── Mobile Cards ── */
    .dr-mobile-list {
        display: none;
        width: 100%;
    }

    .dr-mcard {
        padding: 16px;
        border-bottom: 1px solid var(--gray-100);
        border-left: 3px solid transparent;
        transition: background .12s;
        width: 100%;
    }

    .dr-mcard:last-child {
        border-bottom: none;
    }

    .dr-mcard--pending {
        border-left-color: #F59E0B;
    }

    .dr-mcard--approved {
        border-left-color: #10B981;
    }

    .dr-mcard--rejected {
        border-left-color: #EF4444;
    }

    .dr-mobile-sep {
        display: flex;
        align-items: center;
        gap: 5px;
        padding: 6px 16px;
        background: var(--gray-50);
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: var(--gray-400);
        border-top: 1px solid var(--gray-100);
        border-bottom: 1px solid var(--gray-100);
    }

    .dr-mcard__top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 12px;
        flex-wrap: wrap;
    }

    .dr-mcard__user {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 0;
        flex: 1;
    }

    .dr-mcard__avatar {
        width: 38px;
        height: 38px;
        border-radius: 9px;
        flex-shrink: 0;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 13px;
    }

    .dr-mcard__info {
        min-width: 0;
        flex: 1;
    }

    .dr-mcard__name {
        display: block;
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--gray-900);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .dr-mcard__email {
        display: block;
        font-size: 0.75rem;
        color: var(--gray-400);
        margin-top: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .dr-mcard__right {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 6px;
        flex-shrink: 0;
        min-width: 0;
        width: 100%;
        align-items: flex-start;
    }

    .dr-mcard__info,
    .dr-mcard__right {
        min-width: 0;
        width: 100%;
    }

    .dr-mcard__name,
    .dr-mcard__email,
    .dr-mcard__amount,
    .dr-date,
    .dr-mcard__meta-item,
    .dr-receipt-link,
    .dr-mcard__status-bar {
        white-space: normal;
        word-break: break-word;
    }

    .dr-mcard__meta {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 12px;
    }

    .dr-mcard__meta-item {
        display: flex;
        flex-direction: column;
        gap: 1px;
        width: 100%;
        min-width: 0;
    }

    .dr-mcard__amount {
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--green-800);
        font-family: 'JetBrains Mono', monospace;
    }

    .dr-mcard__meta {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 12px;
    }

    .dr-mcard__meta-item {
        display: flex;
        flex-direction: column;
        gap: 1px;
    }

    .dr-mcard__meta-label {
        font-size: 0.7rem;
        color: var(--gray-400);
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: .04em;
    }

    .dr-mcard__meta-val {
        font-size: 0.8rem;
        color: var(--gray-700);
        font-weight: 500;
    }

    .dr-receipt-link {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 0.78rem;
        color: var(--indigo-600);
        text-decoration: none;
        font-weight: 500;
        margin-top: 2px;
    }

    .dr-mcard__actions {
        display: flex;
        gap: 8px;
    }

    .dr-mcard__approve,
    .dr-mcard__reject {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        padding: 10px 12px;
        border: none;
        border-radius: 8px;
        font-size: 0.84rem;
        font-weight: 600;
        cursor: pointer;
        font-family: inherit;
        transition: all .15s;
    }

    .dr-mcard__approve {
        background: #10B981;
        color: #fff;
    }

    .dr-mcard__approve:hover {
        background: #059669;
    }

    .dr-mcard__approve:disabled {
        opacity: .5;
        cursor: not-allowed;
    }

    .dr-mcard__reject {
        background: var(--red-50);
        color: var(--red-600);
    }

    .dr-mcard__reject:hover {
        background: var(--red-600);
        color: #fff;
    }

    .dr-mcard__reject:disabled {
        opacity: .5;
        cursor: not-allowed;
    }

    .dr-mcard__status-bar {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.79rem;
        font-weight: 500;
        padding: 7px 10px;
        border-radius: 7px;
    }

    .dr-mcard__status-bar--approved {
        background: var(--green-50);
        color: var(--green-800);
    }

    .dr-mcard__status-bar--rejected {
        background: var(--red-50);
        color: var(--red-800);
    }

    /* ── Footer ── */
    .dr-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 11px 20px;
        background: var(--gray-50);
        border-top: 1px solid var(--gray-100);
        font-size: 0.81rem;
        color: var(--gray-500);
        flex-wrap: wrap;
        gap: 8px;
    }

    .dr-footer__text {
        min-width: 0;
        word-break: break-word;
    }

    .dr-clear-link {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        color: var(--indigo-600);
        text-decoration: none;
        font-weight: 500;
        font-size: 0.8rem;
        flex-shrink: 0;
    }

    .dr-clear-link:hover {
        text-decoration: underline;
    }

    /* ── Empty ── */
    .dr-empty {
        text-align: center;
        padding: 64px 24px;
    }

    .dr-empty__icon {
        width: 60px;
        height: 60px;
        background: var(--gray-100);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        color: var(--gray-300);
    }

    .dr-empty__title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--gray-700);
        margin-bottom: 6px;
    }

    .dr-empty__sub {
        font-size: 0.84rem;
        color: var(--gray-400);
    }

    /* ── Loading Overlay ── */
    .dr-overlay {
        position: fixed;
        inset: 0;
        background: rgba(17, 24, 39, .55);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(3px);
    }

    .dr-overlay__box {
        background: #fff;
        border-radius: 14px;
        padding: 36px 48px;
        text-align: center;
        min-width: 190px;
    }

    .dr-overlay__spinner {
        width: 36px;
        height: 36px;
        border: 3px solid var(--gray-100);
        border-top-color: var(--indigo-600);
        border-radius: 50%;
        animation: spin .65s linear infinite;
        margin: 0 auto 14px;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    .dr-overlay__box p {
        margin: 0;
        font-weight: 600;
        color: var(--gray-700);
        font-size: 0.9rem;
        font-family: 'Roboto', sans-serif;
    }

    /* ══════════════════════════════════════════════════
       MOBILE RESPONSIVE BREAKPOINTS
       ══════════════════════════════════════════════════ */

    /* Tablet landscape (960px and below) */
    @media (max-width: 960px) {
        .dr-stats {
            grid-template-columns: repeat(2, 1fr);
        }

        .dr-stat {
            padding: 12px 14px;
        }

        .dr-stat__icon {
            width: 36px;
            height: 36px;
        }

        .dr-stat__num {
            font-size: 1.2rem;
        }
    }

    /* Tablet portrait (768px and below) */
    @media (max-width: 768px) {
        .dr-wrap {
            padding: 14px 10px 40px;
            overflow-x: hidden;
        }

        .dr-desktop-table {
            display: none;
        }

        .dr-mobile-list {
            display: block;
            max-width: 100%;
        }

        .dr-header {
            margin-bottom: 16px;
        }

        .dr-header__icon {
            width: 40px;
            height: 40px;
        }

        .dr-header__icon svg {
            width: 16px;
            height: 16px;
        }

        .dr-header__title {
            font-size: 1.15rem;
        }

        .dr-header__sub {
            font-size: 0.76rem;
        }

        .dr-tabs {
            display: flex;
            flex-wrap: wrap;
            width: 100%;
            gap: 6px;
        }

        .dr-tab {
            flex: 1 1 auto;
            min-width: 0;
        }

        .dr-stats {
            margin-bottom: 16px;
            gap: 8px;
        }

        .dr-tabs-wrap {
            margin-bottom: 14px;
        }

        .dr-mcard__top {
            flex-wrap: wrap;
            align-items: flex-start;
        }

        .dr-mcard__user,
        .dr-mcard__right {
            width: 100%;
        }

        .dr-mcard__right {
            align-items: flex-start;
        }

        .dr-mcard__meta {
            flex-direction: column;
            gap: 10px;
            width: 100%;
        }

        .dr-mcard__meta-item {
            width: 100%;
        }

        .dr-mcard__status-bar {
            width: 100%;
        }

        .dr-mcard__actions {
            width: 100%;
        }
    }

    /* Mobile landscape (680px and below) */
    @media (max-width: 680px) {
        .dr-wrap {
            padding: 12px 8px 36px;
        }

        .dr-header__left {
            gap: 10px;
        }

        .dr-header__icon {
            width: 38px;
            height: 38px;
        }

        .dr-mcard__top {
            flex-wrap: wrap;
            align-items: flex-start;
        }

        .dr-mcard__right {
            width: 100%;
            align-items: flex-start;
        }

        .dr-mcard__user {
            width: 100%;
        }

        .dr-mcard__meta {
            flex-direction: column;
            gap: 10px;
        }

        .dr-mcard__meta-item {
            width: 100%;
        }

        .dr-mcard__status-bar {
            width: 100%;
        }

        .dr-header__title {
            font-size: 1.1rem;
        }

        .dr-header__sub {
            font-size: 0.74rem;
        }

        .dr-refresh-btn span {
            display: none;
        }

        .dr-refresh-btn {
            padding: 8px 10px;
        }

        .dr-refresh-btn svg {
            width: 16px;
            height: 16px;
        }

        .dr-stats {
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
        }

        .dr-stat {
            padding: 12px 13px;
            gap: 10px;
        }

        .dr-stat__icon {
            width: 34px;
            height: 34px;
        }

        .dr-stat__icon svg {
            width: 14px;
            height: 14px;
        }

        .dr-stat__num {
            font-size: 1.1rem;
        }

        .dr-stat__num--sm {
            font-size: 0.95rem;
        }

        .dr-stat__lbl {
            font-size: 0.72rem;
        }

        .dr-flash {
            padding: 10px 14px;
            font-size: 0.82rem;
        }

        .dr-flash svg {
            width: 14px;
            height: 14px;
        }

        .dr-mcard {
            padding: 14px;
        }

        .dr-mcard__avatar {
            width: 36px;
            height: 36px;
            font-size: 12px;
        }

        .dr-mcard__name {
            font-size: 0.88rem;
        }

        .dr-mcard__email {
            font-size: 0.73rem;
        }

        .dr-mcard__amount {
            font-size: 1rem;
        }

        .dr-badge {
            font-size: 0.72rem;
            padding: 2px 8px;
        }

        .dr-footer {
            padding: 10px 16px;
            font-size: 0.78rem;
        }
    }

    /* Mobile portrait (480px and below) */
    @media (max-width: 480px) {
        .dr-wrap {
            padding: 10px 6px 32px;
        }

        .dr-header {
            gap: 10px;
        }

        .dr-mcard {
            padding: 12px 10px;
        }

        .dr-mcard__top {
            gap: 8px;
        }

        .dr-mcard__meta {
            gap: 8px;
        }

        .dr-mcard__actions {
            flex-direction: column;
            align-items: stretch;
        }

        .dr-mcard__approve,
        .dr-mcard__reject {
            width: 100%;
        }

        .dr-mcard__meta {
            flex-direction: column;
        }

        .dr-mcard__meta-item {
            width: 100%;
        }

        .dr-header__icon {
            width: 36px;
            height: 36px;
        }

        .dr-header__title {
            font-size: 1.05rem;
        }

        .dr-header__sub {
            display: none;
        }

        .dr-refresh-btn {
            padding: 7px 9px;
        }

        .dr-stats {
            gap: 6px;
        }

        .dr-stat {
            padding: 10px 11px;
            gap: 8px;
        }

        .dr-stat__icon {
            width: 32px;
            height: 32px;
        }

        .dr-stat__icon svg {
            width: 13px;
            height: 13px;
        }

        .dr-stat__num {
            font-size: 1.05rem;
        }

        .dr-stat__num--sm {
            font-size: 0.9rem;
        }

        .dr-stat__lbl {
            font-size: 0.7rem;
        }

        .dr-tabs {
            padding: 3px;
        }

        .dr-tab {
            padding: 6px 14px;
            font-size: 0.8rem;
        }

        .dr-tab__count {
            font-size: 0.7rem;
            padding: 1px 6px;
        }

        .dr-flash {
            padding: 9px 12px;
            font-size: 0.8rem;
        }

        .dr-mcard {
            padding: 12px;
        }

        .dr-mcard__avatar {
            width: 34px;
            height: 34px;
        }

        .dr-mcard__name {
            font-size: 0.86rem;
        }

        .dr-mcard__email {
            font-size: 0.72rem;
        }

        .dr-mcard__amount {
            font-size: 0.95rem;
        }

        .dr-mcard__meta {
            gap: 12px;
        }

        .dr-mcard__meta-label {
            font-size: 0.68rem;
        }

        .dr-mcard__meta-val {
            font-size: 0.78rem;
        }

        .dr-mcard__approve,
        .dr-mcard__reject {
            padding: 9px 10px;
            font-size: 0.82rem;
        }

        .dr-mcard__approve svg,
        .dr-mcard__reject svg {
            width: 12px;
            height: 12px;
        }

        .dr-mcard__status-bar {
            padding: 6px 9px;
            font-size: 0.77rem;
        }

        .dr-footer {
            padding: 9px 12px;
            font-size: 0.76rem;
        }

        .dr-empty {
            padding: 48px 20px;
        }

        .dr-empty__icon {
            width: 52px;
            height: 52px;
        }

        .dr-empty__title {
            font-size: 0.95rem;
        }

        .dr-empty__sub {
            font-size: 0.82rem;
        }

        .dr-overlay__box {
            padding: 28px 36px;
            min-width: 160px;
        }

        .dr-overlay__spinner {
            width: 32px;
            height: 32px;
        }

        .dr-overlay__box p {
            font-size: 0.85rem;
        }
    }

    /* Small mobile (420px and below) */
    @media (max-width: 420px) {
        .dr-stats {
            grid-template-columns: 1fr 1fr;
        }

        .dr-stat--amount {
            grid-column: 1 / -1;
        }

        .dr-mcard__actions {
            flex-direction: column;
            gap: 6px;
        }

        .dr-mcard__approve,
        .dr-mcard__reject {
            width: 100%;
        }

        .dr-footer {
            flex-direction: column;
            align-items: flex-start;
            gap: 6px;
        }
    }

    /* Extra small mobile (360px and below) */
    @media (max-width: 360px) {
        .dr-header__title {
            font-size: 1rem;
        }

        .dr-stat__num {
            font-size: 1rem;
        }

        .dr-stat__num--sm {
            font-size: 0.85rem;
        }

        .dr-stat__lbl {
            font-size: 0.68rem;
        }

        .dr-tab {
            padding: 5px 12px;
            font-size: 0.78rem;
        }

        .dr-mcard__name {
            font-size: 0.84rem;
        }

        .dr-mcard__amount {
            font-size: 0.9rem;
        }

        .dr-mcard__approve,
        .dr-mcard__reject {
            font-size: 0.8rem;
            padding: 8px 10px;
        }
    }

    /* Touch device hover states */
    @media (hover: none) and (pointer: coarse) {
        .dr-btn-approve:hover {
            transform: none;
        }

        .dr-btn-reject:hover {
            transform: none;
        }

        .dr-mcard__approve:hover {
            background: #10B981;
        }

        .dr-mcard__reject:hover {
            background: var(--red-50);
            color: var(--red-600);
        }

        .dr-stat:hover {
            box-shadow: none;
        }

        .dr-row:hover {
            background: transparent;
        }
    }
</style>

<script>
    function handleAction(action, id, btnEl) {
        var isApprove = action === 'approve';
        if (!confirm(isApprove ? 'Approve this deposit and credit wallet?' : 'Reject this deposit request?')) return;
        var rowEl = document.getElementById('row-' + id) || document.getElementById('mcard-' + id);
        if (rowEl) {
            rowEl.querySelectorAll('button').forEach(function(b) {
                b.disabled = true;
                b.style.opacity = '0.5';
            });
        }
        document.getElementById('drLoadingOverlay').style.display = 'flex';
        document.getElementById('drLoadingText').textContent = isApprove ? 'Approving...' : 'Rejecting...';
        window.location.href = '<?php echo site_url("admin/deposits/"); ?>' + action + '/' + id;
    }

    setTimeout(function() {
        var msg = document.getElementById('flashMsg');
        if (msg) {
            msg.style.transition = 'opacity .4s';
            msg.style.opacity = '0';
            setTimeout(function() {
                msg && msg.remove();
            }, 400);
        }
    }, 4500);
</script>