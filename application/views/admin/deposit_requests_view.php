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

<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

<div class="dr-wrap">

    <!-- Page Title -->
    <div class="dr-topbar">
        <div class="dr-heading">
            <div class="dr-icon-box">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="5" width="20" height="14" rx="2" />
                    <line x1="2" y1="10" x2="22" y2="10" />
                </svg>
            </div>
            <div>
                <h1>Deposit Requests</h1>
                <p>Review and manage incoming deposit requests</p>
            </div>
        </div>
        <div class="dr-topbar-right">
            <button class="refresh-pill" onclick="location.reload()" title="Refresh">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="23 4 23 10 17 10" />
                    <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10" />
                </svg>
                Refresh
            </button>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="dr-stats-row">
        <div class="stat-card pending-card">
            <div class="stat-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10" />
                    <polyline points="12 6 12 12 16 14" />
                </svg>
            </div>
            <div class="stat-body">
                <span class="stat-num"><?php echo $stats['pending']; ?></span>
                <span class="stat-lbl">Pending</span>
            </div>
        </div>
        <div class="stat-card approved-card">
            <div class="stat-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                    <polyline points="22 4 12 14.01 9 11.01" />
                </svg>
            </div>
            <div class="stat-body">
                <span class="stat-num"><?php echo $stats['approved']; ?></span>
                <span class="stat-lbl">Approved</span>
            </div>
        </div>
        <div class="stat-card rejected-card">
            <div class="stat-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="15" y1="9" x2="9" y2="15" />
                    <line x1="9" y1="9" x2="15" y2="15" />
                </svg>
            </div>
            <div class="stat-body">
                <span class="stat-num"><?php echo $stats['rejected']; ?></span>
                <span class="stat-lbl">Rejected</span>
            </div>
        </div>
        <div class="stat-card amount-card">
            <div class="stat-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="1" x2="12" y2="23" />
                    <path d="M17 5H9.5a3.5 3.5 0 1 0 0 7h5a3.5 3.5 0 1 1 0 7H6" />
                </svg>
            </div>
            <div class="stat-body">
                <span class="stat-num">₹<?php echo number_format($stats['approved_amount'], 0); ?></span>
                <span class="stat-lbl">Approved Total</span>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="dr-tabs">
        <a href="<?php echo site_url('admin/deposits/requests'); ?>"
            class="tab-item <?php echo (!$status_filter) ? 'active' : ''; ?>">
            All
            <span class="tab-count"><?php echo $stats['total']; ?></span>
        </a>
        <a href="<?php echo site_url('admin/deposits/requests?status=pending'); ?>"
            class="tab-item <?php echo ($status_filter == 'pending') ? 'active active-pending' : ''; ?>">
            Pending
            <?php if (!empty($stats['pending'])): ?>
                <span class="tab-count hot"><?php echo $stats['pending']; ?></span>
            <?php endif; ?>
        </a>
        <a href="<?php echo site_url('admin/deposits/requests?status=approved'); ?>"
            class="tab-item <?php echo ($status_filter == 'approved') ? 'active active-approved' : ''; ?>">
            Approved
            <span class="tab-count"><?php echo $stats['approved']; ?></span>
        </a>
        <a href="<?php echo site_url('admin/deposits/requests?status=rejected'); ?>"
            class="tab-item <?php echo ($status_filter == 'rejected') ? 'active active-rejected' : ''; ?>">
            Rejected
            <span class="tab-count"><?php echo $stats['rejected']; ?></span>
        </a>
    </div>

    <!-- Flash Messages -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="flash-msg success-msg" id="flashMsg">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                <polyline points="22 4 12 14.01 9 11.01" />
            </svg>
            <?php echo $this->session->flashdata('success'); ?>
        </div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="flash-msg error-msg" id="flashMsg">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10" />
                <line x1="12" y1="8" x2="12" y2="12" />
                <line x1="12" y1="16" x2="12.01" y2="16" />
            </svg>
            <?php echo $this->session->flashdata('error'); ?>
        </div>
    <?php endif; ?>

    <!-- Table -->
    <div class="dr-table-card">
        <?php if (!empty($all_sorted)): ?>

            <!-- Mobile Cards View -->
            <div class="mobile-cards">
                <?php
                $prev_status = null;
                $row_num = 1;
                foreach ($all_sorted as $r):
                    if ($prev_status !== null && $prev_status !== $r->status):
                ?>
                        <div class="mobile-section-divider">
                            <?php if ($r->status === 'approved'): ?>
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                    <polyline points="22 4 12 14.01 9 11.01" />
                                </svg> Approved
                            <?php elseif ($r->status === 'rejected'): ?>
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <circle cx="12" cy="12" r="10" />
                                    <line x1="15" y1="9" x2="9" y2="15" />
                                    <line x1="9" y1="9" x2="15" y2="15" />
                                </svg> Rejected
                            <?php endif; ?>
                        </div>
                    <?php
                    endif;
                    $prev_status = $r->status;
                    $avatar_color = '#' . substr(md5($r->name), 0, 6);
                    $initials = strtoupper(substr($r->name, 0, 2));
                    ?>
                    <div class="mobile-card status-border-<?php echo $r->status; ?>" id="mcard-<?php echo $r->id; ?>">
                        <div class="mc-header">
                            <div class="mc-user">
                                <div class="mc-avatar" style="background:<?php echo $avatar_color; ?>"><?php echo $initials; ?></div>
                                <div>
                                    <div class="mc-name"><?php echo htmlspecialchars($r->name); ?></div>
                                    <div class="mc-email"><?php echo htmlspecialchars($r->email ?? ''); ?></div>
                                </div>
                            </div>
                            <div class="mc-right">
                                <div class="mc-amount">₹<?php echo number_format($r->amount, 2); ?></div>
                                <?php if ($r->status === 'pending'): ?>
                                    <span class="badge badge-pending"><span class="pulse"></span>Pending</span>
                                <?php elseif ($r->status === 'approved'): ?>
                                    <span class="badge badge-approved">Approved</span>
                                <?php else: ?>
                                    <span class="badge badge-rejected">Rejected</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="mc-meta">
                            <div class="mc-meta-item">
                                <span class="mc-meta-label">Date</span>
                                <span class="mc-meta-val"><?php echo isset($r->created_at) ? date('d M Y, h:i A', strtotime($r->created_at)) : 'N/A'; ?></span>
                            </div>
                            <?php if (!empty($r->receipt)): ?>
                                <div class="mc-meta-item">
                                    <a href="<?php echo base_url('uploads/receipts/' . $r->receipt); ?>" target="_blank" class="mc-receipt-link">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                            <circle cx="12" cy="12" r="3" />
                                        </svg>
                                        View Receipt
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php if ($r->status === 'pending'): ?>
                            <div class="mc-actions">
                                <button class="mc-btn-approve" onclick="handleAction('approve', <?php echo $r->id; ?>, this)">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                    Approve
                                </button>
                                <button class="mc-btn-reject" onclick="handleAction('reject', <?php echo $r->id; ?>, this)">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <line x1="18" y1="6" x2="6" y2="18" />
                                        <line x1="6" y1="6" x2="18" y2="18" />
                                    </svg>
                                    Reject
                                </button>
                            </div>
                        <?php elseif ($r->status === 'approved'): ?>
                            <div class="mc-processed approved-proc">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                    <polyline points="22 4 12 14.01 9 11.01" />
                                </svg>
                                Approved <?php echo isset($r->approved_at) ? date('d M, h:i A', strtotime($r->approved_at)) : ''; ?>
                            </div>
                        <?php else: ?>
                            <div class="mc-processed rejected-proc">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <circle cx="12" cy="12" r="10" />
                                    <line x1="15" y1="9" x2="9" y2="15" />
                                    <line x1="9" y1="9" x2="15" y2="15" />
                                </svg>
                                Rejected <?php echo isset($r->rejected_at) ? date('d M, h:i A', strtotime($r->rejected_at)) : ''; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php $row_num++;
                endforeach; ?>
            </div>

            <!-- Desktop Table View -->
            <div class="desktop-table">
                <table class="dr-tbl">
                    <thead>
                        <tr>
                            <th style="width:44px">#</th>
                            <th>User</th>
                            <th>Amount</th>
                            <th>Receipt</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $prev_status = null;
                        $row_num = 1;
                        foreach ($all_sorted as $r):
                            if ($prev_status !== null && $prev_status !== $r->status):
                        ?>
                                <tr class="tbl-divider">
                                    <td colspan="7">
                                        <div class="divider-inner">
                                            <?php if ($r->status === 'approved'): ?>
                                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                                    <polyline points="22 4 12 14.01 9 11.01" />
                                                </svg> Approved Requests
                                            <?php elseif ($r->status === 'rejected'): ?>
                                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                    <circle cx="12" cy="12" r="10" />
                                                    <line x1="15" y1="9" x2="9" y2="15" />
                                                    <line x1="9" y1="9" x2="15" y2="15" />
                                                </svg> Rejected Requests
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php
                            endif;
                            $prev_status = $r->status;
                            $avatar_color = '#' . substr(md5($r->name), 0, 6);
                            ?>
                            <tr class="dr-tbl-row <?php echo 'tbl-row-' . $r->status; ?>" id="row-<?php echo $r->id; ?>">
                                <td class="tbl-num"><?php echo $row_num++; ?></td>
                                <td>
                                    <div class="tbl-user">
                                        <div class="tbl-avatar" style="background:<?php echo $avatar_color; ?>">
                                            <?php echo strtoupper(substr($r->name, 0, 1)); ?>
                                        </div>
                                        <div>
                                            <div class="tbl-name"><?php echo htmlspecialchars($r->name); ?></div>
                                            <div class="tbl-email"><?php echo htmlspecialchars($r->email ?? ''); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="tbl-amount">₹<?php echo number_format($r->amount, 2); ?></span>
                                </td>
                                <td>
                                    <?php if (!empty($r->receipt)): ?>
                                        <a href="<?php echo base_url('uploads/receipts/' . $r->receipt); ?>" target="_blank" class="tbl-view-btn">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                            View
                                        </a>
                                    <?php else: ?>
                                        <span class="tbl-nofile">—</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="tbl-date">
                                        <?php echo isset($r->created_at) ? date('d M Y', strtotime($r->created_at)) : 'N/A'; ?>
                                        <span class="tbl-time"><?php echo isset($r->created_at) ? date('h:i A', strtotime($r->created_at)) : ''; ?></span>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($r->status === 'pending'): ?>
                                        <span class="badge badge-pending"><span class="pulse"></span>Pending</span>
                                    <?php elseif ($r->status === 'approved'): ?>
                                        <span class="badge badge-approved">
                                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                                <polyline points="20 6 9 17 4 12" />
                                            </svg>
                                            Approved
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-rejected">
                                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                                <line x1="18" y1="6" x2="6" y2="18" />
                                                <line x1="6" y1="6" x2="18" y2="18" />
                                            </svg>
                                            Rejected
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($r->status === 'pending'): ?>
                                        <div class="tbl-action-btns">
                                            <button class="tbl-approve" onclick="handleAction('approve', <?php echo $r->id; ?>, this)">
                                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                    <polyline points="20 6 9 17 4 12" />
                                                </svg>
                                                Approve
                                            </button>
                                            <button class="tbl-reject" onclick="handleAction('reject', <?php echo $r->id; ?>, this)">
                                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                    <line x1="18" y1="6" x2="6" y2="18" />
                                                    <line x1="6" y1="6" x2="18" y2="18" />
                                                </svg>
                                                Reject
                                            </button>
                                        </div>
                                    <?php elseif ($r->status === 'approved'): ?>
                                        <div class="tbl-processed tbl-proc-approved">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                                <polyline points="22 4 12 14.01 9 11.01" />
                                            </svg>
                                            <?php echo isset($r->approved_at) ? date('d M, h:i A', strtotime($r->approved_at)) : 'Processed'; ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="tbl-processed tbl-proc-rejected">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
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

            <!-- Footer -->
            <div class="dr-table-footer">
                <span class="tbl-footer-text">
                    Showing <strong><?php echo count($all_sorted); ?></strong> record(s)
                    <?php if ($status_filter): ?>
                        &mdash; filtered by <strong><?php echo ucfirst($status_filter); ?></strong>
                    <?php endif; ?>
                </span>
                <?php if ($status_filter): ?>
                    <a href="<?php echo site_url('admin/deposits/requests'); ?>" class="clear-filter-link">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <line x1="18" y1="6" x2="6" y2="18" />
                            <line x1="6" y1="6" x2="18" y2="18" />
                        </svg>
                        Clear filter
                    </a>
                <?php endif; ?>
            </div>

        <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
                    </svg>
                </div>
                <p>No deposit requests found</p>
                <span>New requests will appear here automatically</span>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" style="display:none;" class="ld-overlay">
    <div class="ld-box">
        <div class="ld-spinner"></div>
        <p id="loadingText">Processing...</p>
    </div>
</div>

<style>
    *,
    *::before,
    *::after {
        box-sizing: border-box;
    }

    .dr-wrap {
        font-family: 'DM Sans', 'Segoe UI', system-ui, sans-serif;
        padding: 0 0 40px;
        max-width: 1300px;
    }

    /* ─── Topbar ─── */
    .dr-topbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        gap: 12px;
        flex-wrap: wrap;
    }

    .dr-heading {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .dr-icon-box {
        width: 48px;
        height: 48px;
        background: #EEF2FF;
        color: #4F46E5;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .dr-heading h1 {
        margin: 0;
        font-size: 1.35rem;
        font-weight: 600;
        color: #111827;
        letter-spacing: -0.3px;
    }

    .dr-heading p {
        margin: 2px 0 0;
        font-size: 0.82rem;
        color: #9CA3AF;
    }

    .refresh-pill {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 16px;
        background: #fff;
        border: 1px solid #E5E7EB;
        border-radius: 8px;
        font-size: 0.83rem;
        font-weight: 500;
        color: #374151;
        cursor: pointer;
        transition: all 0.18s;
        font-family: inherit;
    }

    .refresh-pill:hover {
        background: #F9FAFB;
        border-color: #D1D5DB;
    }

    /* ─── Stats ─── */
    .dr-stats-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
        margin-bottom: 22px;
    }

    .stat-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #F3F4F6;
        padding: 16px 18px;
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .pending-card .stat-icon {
        background: #FEF3C7;
        color: #D97706;
    }

    .approved-card .stat-icon {
        background: #D1FAE5;
        color: #059669;
    }

    .rejected-card .stat-icon {
        background: #FEE2E2;
        color: #DC2626;
    }

    .amount-card .stat-icon {
        background: #EEF2FF;
        color: #4F46E5;
    }

    .stat-body {
        display: flex;
        flex-direction: column;
        gap: 1px;
        overflow: hidden;
    }

    .stat-num {
        font-size: 1.25rem;
        font-weight: 600;
        color: #111827;
        letter-spacing: -0.5px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .stat-lbl {
        font-size: 0.78rem;
        color: #9CA3AF;
        font-weight: 500;
    }

    /* ─── Tabs ─── */
    .dr-tabs {
        display: flex;
        gap: 2px;
        background: #F3F4F6;
        border-radius: 10px;
        padding: 4px;
        margin-bottom: 18px;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
    }

    .dr-tabs::-webkit-scrollbar {
        display: none;
    }

    .tab-item {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 7px 16px;
        border-radius: 7px;
        font-size: 0.84rem;
        font-weight: 500;
        color: #6B7280;
        text-decoration: none;
        transition: all 0.18s;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .tab-item:hover {
        color: #374151;
        background: rgba(255, 255, 255, 0.6);
    }

    .tab-item.active {
        background: #fff;
        color: #111827;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .tab-item.active-pending {
        color: #D97706;
    }

    .tab-item.active-approved {
        color: #059669;
    }

    .tab-item.active-rejected {
        color: #DC2626;
    }

    .tab-count {
        background: #E5E7EB;
        color: #6B7280;
        border-radius: 20px;
        padding: 1px 8px;
        font-size: 0.75rem;
        font-weight: 600;
        min-width: 20px;
        text-align: center;
    }

    .tab-count.hot {
        background: #FEE2E2;
        color: #DC2626;
        animation: hotpulse 2s infinite;
    }

    @keyframes hotpulse {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.12);
        }
    }

    /* ─── Flash ─── */
    .flash-msg {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 13px 18px;
        border-radius: 10px;
        margin-bottom: 16px;
        font-size: 0.88rem;
        font-weight: 500;
        animation: fadeSlide 0.3s ease;
    }

    @keyframes fadeSlide {
        from {
            opacity: 0;
            transform: translateY(-6px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .success-msg {
        background: #D1FAE5;
        color: #065F46;
        border-left: 3px solid #059669;
    }

    .error-msg {
        background: #FEE2E2;
        color: #7F1D1D;
        border-left: 3px solid #DC2626;
    }

    /* ─── Table Card ─── */
    .dr-table-card {
        background: #fff;
        border-radius: 14px;
        border: 1px solid #F3F4F6;
        overflow: hidden;
    }

    /* ─── Desktop Table ─── */
    .desktop-table {
        overflow-x: auto;
    }

    .dr-tbl {
        width: 100%;
        border-collapse: collapse;
        min-width: 720px;
    }

    .dr-tbl thead tr {
        background: #F9FAFB;
        border-bottom: 1px solid #F3F4F6;
    }

    .dr-tbl thead th {
        padding: 12px 16px;
        text-align: left;
        font-size: 0.75rem;
        font-weight: 600;
        color: #9CA3AF;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        white-space: nowrap;
    }

    .dr-tbl-row {
        border-bottom: 1px solid #F9FAFB;
        transition: background 0.12s;
    }

    .dr-tbl-row:hover {
        background: #FAFAFA;
    }

    .dr-tbl-row:last-child {
        border-bottom: none;
    }

    .tbl-row-pending {
        border-left: 3px solid #F59E0B;
    }

    .tbl-row-approved {
        border-left: 3px solid #10B981;
    }

    .tbl-row-rejected {
        border-left: 3px solid #EF4444;
        opacity: 0.8;
    }

    .dr-tbl td {
        padding: 13px 16px;
        vertical-align: middle;
        font-size: 0.875rem;
        color: #374151;
    }

    .tbl-divider td {
        padding: 5px 16px;
        background: #F9FAFB;
        border-top: 1px solid #F3F4F6;
        border-bottom: 1px solid #F3F4F6;
    }

    .divider-inner {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.72rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #9CA3AF;
    }

    .tbl-num {
        color: #D1D5DB;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .tbl-user {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .tbl-avatar {
        width: 36px;
        height: 36px;
        border-radius: 9px;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 14px;
        flex-shrink: 0;
    }

    .tbl-name {
        font-weight: 500;
        font-size: 0.875rem;
        color: #111827;
    }

    .tbl-email {
        font-size: 0.78rem;
        color: #9CA3AF;
        margin-top: 1px;
    }

    .tbl-amount {
        font-weight: 600;
        font-size: 0.95rem;
        color: #065F46;
        font-family: 'DM Mono', monospace;
    }

    .tbl-view-btn {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 10px;
        background: #EEF2FF;
        color: #4F46E5;
        border-radius: 6px;
        font-size: 0.78rem;
        font-weight: 500;
        text-decoration: none;
        transition: background 0.15s;
    }

    .tbl-view-btn:hover {
        background: #E0E7FF;
    }

    .tbl-nofile {
        color: #D1D5DB;
        font-size: 1rem;
    }

    .tbl-date {
        font-size: 0.85rem;
        color: #374151;
        line-height: 1.4;
    }

    .tbl-time {
        display: block;
        font-size: 0.76rem;
        color: #9CA3AF;
    }

    /* ─── Badges ─── */
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .badge-pending {
        background: #FEF3C7;
        color: #92400E;
    }

    .badge-approved {
        background: #D1FAE5;
        color: #065F46;
    }

    .badge-rejected {
        background: #FEE2E2;
        color: #7F1D1D;
    }

    .pulse {
        width: 6px;
        height: 6px;
        background: #F59E0B;
        border-radius: 50%;
        display: inline-block;
        animation: blink 1.4s ease infinite;
    }

    @keyframes blink {

        0%,
        100% {
            opacity: 1;
            transform: scale(1);
        }

        50% {
            opacity: 0.4;
            transform: scale(0.7);
        }
    }

    /* ─── Action Buttons ─── */
    .tbl-action-btns {
        display: flex;
        gap: 6px;
    }

    .tbl-approve,
    .tbl-reject {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 12px;
        border: none;
        border-radius: 7px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.18s;
        font-family: inherit;
        white-space: nowrap;
    }

    .tbl-approve {
        background: #10B981;
        color: #fff;
    }

    .tbl-approve:hover {
        background: #059669;
        transform: translateY(-1px);
    }

    .tbl-approve:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    .tbl-reject {
        background: #FEE2E2;
        color: #DC2626;
    }

    .tbl-reject:hover {
        background: #DC2626;
        color: #fff;
        transform: translateY(-1px);
    }

    .tbl-reject:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    .tbl-processed {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.8rem;
        color: #9CA3AF;
    }

    .tbl-proc-approved {
        color: #059669;
    }

    .tbl-proc-rejected {
        color: #DC2626;
    }

    /* ─── Table Footer ─── */
    .dr-table-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 20px;
        background: #F9FAFB;
        border-top: 1px solid #F3F4F6;
        font-size: 0.83rem;
        color: #6B7280;
        flex-wrap: wrap;
        gap: 8px;
    }

    .clear-filter-link {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        color: #4F46E5;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.82rem;
    }

    .clear-filter-link:hover {
        text-decoration: underline;
    }

    /* ─── Empty State ─── */
    .empty-state {
        text-align: center;
        padding: 64px 24px;
        color: #9CA3AF;
    }

    .empty-icon {
        width: 64px;
        height: 64px;
        background: #F3F4F6;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        color: #D1D5DB;
    }

    .empty-state p {
        font-size: 1rem;
        font-weight: 600;
        color: #374151;
        margin: 0 0 6px;
    }

    .empty-state span {
        font-size: 0.85rem;
    }

    /* ─── Mobile Cards ─── */
    .mobile-cards {
        display: none;
    }

    .mobile-card {
        padding: 16px;
        border-bottom: 1px solid #F3F4F6;
        border-left: 3px solid transparent;
        transition: background 0.15s;
    }

    .mobile-card:last-child {
        border-bottom: none;
    }

    .status-border-pending {
        border-left-color: #F59E0B;
    }

    .status-border-approved {
        border-left-color: #10B981;
    }

    .status-border-rejected {
        border-left-color: #EF4444;
    }

    .mobile-section-divider {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 7px 16px;
        background: #F9FAFB;
        font-size: 0.72rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #9CA3AF;
        border-top: 1px solid #F3F4F6;
        border-bottom: 1px solid #F3F4F6;
    }

    .mc-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 12px;
    }

    .mc-user {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 0;
    }

    .mc-avatar {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 13px;
        flex-shrink: 0;
    }

    .mc-name {
        font-weight: 600;
        font-size: 0.9rem;
        color: #111827;
    }

    .mc-email {
        font-size: 0.76rem;
        color: #9CA3AF;
        margin-top: 2px;
    }

    .mc-right {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 6px;
        flex-shrink: 0;
    }

    .mc-amount {
        font-size: 1.05rem;
        font-weight: 700;
        color: #065F46;
        font-family: 'DM Mono', monospace;
    }

    .mc-meta {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 12px;
    }

    .mc-meta-item {
        display: flex;
        flex-direction: column;
        gap: 1px;
    }

    .mc-meta-label {
        font-size: 0.72rem;
        color: #9CA3AF;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .mc-meta-val {
        font-size: 0.82rem;
        color: #374151;
        font-weight: 500;
    }

    .mc-receipt-link {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 0.8rem;
        color: #4F46E5;
        text-decoration: none;
        font-weight: 500;
        margin-top: 2px;
    }

    .mc-actions {
        display: flex;
        gap: 8px;
        margin-top: 4px;
    }

    .mc-btn-approve,
    .mc-btn-reject {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 9px 12px;
        border: none;
        border-radius: 8px;
        font-size: 0.84rem;
        font-weight: 600;
        cursor: pointer;
        font-family: inherit;
        transition: all 0.18s;
    }

    .mc-btn-approve {
        background: #10B981;
        color: #fff;
    }

    .mc-btn-approve:hover {
        background: #059669;
    }

    .mc-btn-reject {
        background: #FEE2E2;
        color: #DC2626;
    }

    .mc-btn-reject:hover {
        background: #DC2626;
        color: #fff;
    }

    .mc-processed {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.8rem;
        margin-top: 4px;
        padding: 7px 10px;
        border-radius: 7px;
    }

    .approved-proc {
        background: #D1FAE5;
        color: #065F46;
    }

    .rejected-proc {
        background: #FEE2E2;
        color: #7F1D1D;
    }

    /* ─── Loading Overlay ─── */
    .ld-overlay {
        position: fixed;
        inset: 0;
        background: rgba(17, 24, 39, 0.5);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(4px);
    }

    .ld-box {
        background: #fff;
        border-radius: 16px;
        padding: 36px 48px;
        text-align: center;
        min-width: 200px;
    }

    .ld-spinner {
        width: 40px;
        height: 40px;
        border: 3px solid #F3F4F6;
        border-top-color: #4F46E5;
        border-radius: 50%;
        animation: spin 0.7s linear infinite;
        margin: 0 auto 16px;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    .ld-box p {
        margin: 0;
        font-weight: 600;
        color: #374151;
        font-size: 0.95rem;
        font-family: 'DM Sans', sans-serif;
    }

    /* ─── Responsive ─── */
    @media (max-width: 900px) {
        .dr-stats-row {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 700px) {
        .desktop-table {
            display: none;
        }

        .mobile-cards {
            display: block;
        }

        .dr-stats-row {
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
        }

        .stat-card {
            padding: 12px 14px;
            gap: 10px;
        }

        .stat-num {
            font-size: 1.1rem;
        }

        .dr-heading h1 {
            font-size: 1.15rem;
        }

        .dr-icon-box {
            width: 40px;
            height: 40px;
            border-radius: 10px;
        }
    }

    @media (max-width: 420px) {
        .dr-stats-row {
            grid-template-columns: 1fr 1fr;
        }

        .amount-card {
            grid-column: 1 / -1;
        }
    }
</style>

<script>
    function handleAction(action, id, btnEl) {
        const isApprove = action === 'approve';
        if (!confirm(isApprove ? 'Approve this deposit and credit wallet?' : 'Reject this deposit request?')) return;

        const rowEl = document.getElementById('row-' + id) || document.getElementById('mcard-' + id);
        if (rowEl) {
            rowEl.querySelectorAll('button').forEach(b => {
                b.disabled = true;
                b.style.opacity = '0.5';
            });
        }

        document.getElementById('loadingOverlay').style.display = 'flex';
        document.getElementById('loadingText').textContent = isApprove ? 'Approving...' : 'Rejecting...';
        window.location.href = '<?php echo site_url("admin/deposits/"); ?>' + action + '/' + id;
    }

    setTimeout(function() {
        const msg = document.getElementById('flashMsg');
        if (msg) {
            msg.style.transition = 'opacity 0.4s';
            msg.style.opacity = '0';
            setTimeout(() => msg && msg.remove(), 400);
        }
    }, 4500);
</script>