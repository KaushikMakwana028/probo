<?php
// Separate requests by status — pending always first
$pending_requests  = array_filter($requests ?? [], fn($r) => $r->status === 'pending');
$approved_requests = array_filter($requests ?? [], fn($r) => $r->status === 'approved');
$rejected_requests = array_filter($requests ?? [], fn($r) => $r->status === 'rejected');
$all_sorted = array_merge(
    array_values($pending_requests),
    array_values($approved_requests),
    array_values($rejected_requests)
);
?>

<div class="dr-wrapper">

    <!-- Header -->
    <div class="dr-header">
        <div class="dr-title">
            <i class="fas fa-money-bill-wave"></i>
            <div>
                <h2>Deposit Requests</h2>
                <p>Pending requests are shown first</p>
            </div>
        </div>
        <div class="dr-header-actions">
            <a href="<?php echo site_url('admin/deposits/requests?status=pending'); ?>"
                class="filter-btn <?php echo ($status_filter == 'pending') ? 'active' : ''; ?>">
                <i class="fas fa-clock"></i> Pending
                <?php if (!empty($stats['pending'])): ?>
                    <span class="count-badge"><?php echo $stats['pending']; ?></span>
                <?php endif; ?>
            </a>
            <a href="<?php echo site_url('admin/deposits/requests?status=approved'); ?>"
                class="filter-btn <?php echo ($status_filter == 'approved') ? 'active success' : ''; ?>">
                <i class="fas fa-check-circle"></i> Approved
            </a>
            <a href="<?php echo site_url('admin/deposits/requests?status=rejected'); ?>"
                class="filter-btn <?php echo ($status_filter == 'rejected') ? 'active danger' : ''; ?>">
                <i class="fas fa-times-circle"></i> Rejected
            </a>
            <a href="<?php echo site_url('admin/deposits/requests'); ?>"
                class="filter-btn <?php echo (!$status_filter) ? 'active' : ''; ?>">
                <i class="fas fa-list"></i> All
            </a>
            <button class="refresh-btn" onclick="location.reload()">
                <i class="fas fa-sync-alt"></i>
            </button>
        </div>
    </div>

    <!-- Stats Bar -->
    <div class="dr-stats">
        <div class="stat-pill pending">
            <i class="fas fa-hourglass-half"></i>
            <span><?php echo $stats['pending']; ?> Pending</span>
        </div>
        <div class="stat-pill approved">
            <i class="fas fa-check-circle"></i>
            <span><?php echo $stats['approved']; ?> Approved</span>
        </div>
        <div class="stat-pill rejected">
            <i class="fas fa-times-circle"></i>
            <span><?php echo $stats['rejected']; ?> Rejected</span>
        </div>
        <div class="stat-pill amount">
            <i class="fas fa-rupee-sign"></i>
            <span>₹<?php echo number_format($stats['approved_amount'], 2); ?> Approved</span>
        </div>
        <div class="stat-pill total">
            <i class="fas fa-layer-group"></i>
            <span><?php echo $stats['total']; ?> Total</span>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="flash flash-success">
            <i class="fas fa-check-circle"></i>
            <?php echo $this->session->flashdata('success'); ?>
        </div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="flash flash-error">
            <i class="fas fa-exclamation-circle"></i>
            <?php echo $this->session->flashdata('error'); ?>
        </div>
    <?php endif; ?>

    <!-- Table Card -->
    <div class="dr-card">
        <div class="table-responsive">
            <table class="dr-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th><i class="fas fa-user"></i> User</th>
                        <th><i class="fas fa-rupee-sign"></i> Amount</th>
                        <th><i class="fas fa-receipt"></i> Txn ID</th>
                        <th><i class="fas fa-calendar"></i> Date</th>
                        <th><i class="fas fa-info-circle"></i> Status</th>
                        <th><i class="fas fa-cog"></i> Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($all_sorted)): ?>
                        <?php
                        $prev_status = null;
                        $row_num = 1;
                        foreach ($all_sorted as $r):
                            // Insert a section divider when status group changes
                            if ($prev_status !== null && $prev_status !== $r->status):
                        ?>
                                <tr class="section-divider">
                                    <td colspan="7">
                                        <span class="divider-label">
                                            <?php if ($r->status === 'approved'): ?>
                                                <i class="fas fa-check-circle"></i> Approved Requests
                                            <?php elseif ($r->status === 'rejected'): ?>
                                                <i class="fas fa-times-circle"></i> Rejected Requests
                                            <?php endif; ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php
                            endif;
                            $prev_status = $r->status;
                            ?>

                            <tr id="row-<?php echo $r->id; ?>"
                                class="dr-row status-<?php echo $r->status; ?> <?php echo ($r->status === 'pending') ? 'row-pending' : ''; ?>">

                                <td class="row-num"><?php echo $row_num++; ?></td>

                                <td>
                                    <div class="user-cell">
                                        <div class="avatar" style="background: <?php echo '#' . substr(md5($r->name), 0, 6); ?>">
                                            <?php echo strtoupper(substr($r->name, 0, 1)); ?>
                                        </div>
                                        <div class="user-meta">
                                            <span class="user-name"><?php echo htmlspecialchars($r->name); ?></span>
                                            <span class="user-email"><?php echo htmlspecialchars($r->email ?? ''); ?></span>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <span class="amount-cell">₹<?php echo number_format($r->amount, 2); ?></span>
                                </td>

                                <td>
                                    <code class="txn-cell"><?php echo htmlspecialchars($r->txn_id ?? 'N/A'); ?></code>
                                </td>

                                <td>
                                    <div class="date-cell">
                                        <span class="date-main">
                                            <?php echo isset($r->created_at) ? date('d M Y', strtotime($r->created_at)) : 'N/A'; ?>
                                        </span>
                                        <span class="date-time">
                                            <?php echo isset($r->created_at) ? date('h:i A', strtotime($r->created_at)) : ''; ?>
                                        </span>
                                    </div>
                                </td>

                                <td>
                                    <?php if ($r->status === 'pending'): ?>
                                        <span class="status-badge pending">
                                            <span class="pulse-dot"></span>
                                            Pending
                                        </span>
                                    <?php elseif ($r->status === 'approved'): ?>
                                        <span class="status-badge approved">
                                            <i class="fas fa-check"></i> Approved
                                        </span>
                                    <?php else: ?>
                                        <span class="status-badge rejected">
                                            <i class="fas fa-times"></i> Rejected
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?php if ($r->status === 'pending'): ?>
                                        <div class="action-btns">
                                            <button class="btn-approve"
                                                onclick="handleAction('approve', <?php echo $r->id; ?>, this)"
                                                title="Approve this deposit">
                                                <i class="fas fa-check"></i> Approve
                                            </button>
                                            <button class="btn-reject"
                                                onclick="handleAction('reject', <?php echo $r->id; ?>, this)"
                                                title="Reject this deposit">
                                                <i class="fas fa-times"></i> Reject
                                            </button>
                                        </div>
                                    <?php elseif ($r->status === 'approved'): ?>
                                        <div class="processed-info">
                                            <i class="fas fa-check-circle" style="color:#22c55e"></i>
                                            <span>
                                                <?php echo isset($r->approved_at) ? date('d M, h:i A', strtotime($r->approved_at)) : 'Processed'; ?>
                                            </span>
                                        </div>
                                    <?php else: ?>
                                        <div class="processed-info">
                                            <i class="fas fa-times-circle" style="color:#ef4444"></i>
                                            <span>
                                                <?php echo isset($r->rejected_at) ? date('d M, h:i A', strtotime($r->rejected_at)) : 'Processed'; ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>

                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <div class="empty-icon"><i class="fas fa-inbox"></i></div>
                                    <p>No deposit requests found</p>
                                    <small>New requests will appear here</small>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if (!empty($all_sorted)): ?>
            <div class="dr-footer">
                <span>
                    Showing <strong><?php echo count($all_sorted); ?></strong> request(s)
                    <?php if ($status_filter): ?>
                        — filtered by <strong><?php echo ucfirst($status_filter); ?></strong>
                    <?php endif; ?>
                </span>
                <?php if ($status_filter): ?>
                    <a href="<?php echo site_url('admin/deposits/requests'); ?>" class="clear-filter">
                        <i class="fas fa-times"></i> Clear Filter
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay" style="display:none;">
    <div class="loading-box">
        <div class="spinner"></div>
        <p id="loadingText">Processing...</p>
    </div>
</div>

<style>
    .dr-wrapper {
        padding: 0;
        font-family: 'Segoe UI', system-ui, sans-serif;
    }

    /* ── Header ── */
    .dr-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 18px;
        gap: 16px;
        flex-wrap: wrap;
    }

    .dr-title {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .dr-title i {
        font-size: 1.8rem;
        color: #667eea;
        background: #eef0fd;
        width: 54px;
        height: 54px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .dr-title h2 {
        margin: 0;
        font-size: 1.4rem;
        font-weight: 700;
        color: #1e293b;
    }

    .dr-title p {
        margin: 2px 0 0;
        font-size: 0.82rem;
        color: #94a3b8;
    }

    /* ── Filter Buttons ── */
    .dr-header-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .filter-btn {
        padding: 7px 14px;
        border-radius: 8px;
        font-size: 0.83rem;
        font-weight: 500;
        color: #64748b;
        background: #f1f5f9;
        border: 1.5px solid transparent;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
        position: relative;
    }

    .filter-btn:hover {
        background: #e2e8f0;
        color: #334155;
    }

    .filter-btn.active {
        background: #667eea;
        color: white;
        border-color: #667eea;
    }

    .filter-btn.active.success {
        background: #22c55e;
        border-color: #22c55e;
    }

    .filter-btn.active.danger {
        background: #ef4444;
        border-color: #ef4444;
    }

    .count-badge {
        background: #ef4444;
        color: white;
        border-radius: 10px;
        padding: 1px 7px;
        font-size: 0.75rem;
        font-weight: 700;
        min-width: 20px;
        text-align: center;
        animation: pulse-badge 2s infinite;
    }

    @keyframes pulse-badge {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.15);
        }
    }

    .refresh-btn {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: #f1f5f9;
        border: 1.5px solid #e2e8f0;
        color: #64748b;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .refresh-btn:hover {
        background: #667eea;
        color: white;
        border-color: #667eea;
        transform: rotate(90deg);
    }

    /* ── Stats Bar ── */
    .dr-stats {
        display: flex;
        gap: 10px;
        margin-bottom: 18px;
        flex-wrap: wrap;
    }

    .stat-pill {
        display: flex;
        align-items: center;
        gap: 7px;
        padding: 8px 16px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .stat-pill.pending {
        background: #fef3c7;
        color: #92400e;
    }

    .stat-pill.approved {
        background: #dcfce7;
        color: #166534;
    }

    .stat-pill.rejected {
        background: #fee2e2;
        color: #991b1b;
    }

    .stat-pill.amount {
        background: #dbeafe;
        color: #1e40af;
    }

    .stat-pill.total {
        background: #f3f4f6;
        color: #374151;
    }

    /* ── Flash Messages ── */
    .flash {
        padding: 13px 18px;
        border-radius: 10px;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 500;
        font-size: 0.9rem;
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-8px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .flash-success {
        background: #dcfce7;
        color: #166534;
        border-left: 4px solid #22c55e;
    }

    .flash-error {
        background: #fee2e2;
        color: #991b1b;
        border-left: 4px solid #ef4444;
    }

    /* ── Card ── */
    .dr-card {
        background: white;
        border-radius: 14px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08), 0 4px 16px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }

    .table-responsive {
        overflow-x: auto;
    }

    /* ── Table ── */
    .dr-table {
        width: 100%;
        border-collapse: collapse;
    }

    .dr-table thead tr {
        background: #f8fafc;
        border-bottom: 2px solid #e2e8f0;
    }

    .dr-table thead th {
        padding: 13px 16px;
        text-align: left;
        font-size: 0.8rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        white-space: nowrap;
    }

    .dr-table thead th i {
        margin-right: 5px;
        color: #94a3b8;
    }

    /* Pending rows get a subtle left accent */
    .dr-row {
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.15s;
    }

    .dr-row:hover {
        background: #f8fafc;
    }

    .dr-row.row-pending {
        border-left: 3px solid #f59e0b;
        background: #fffbeb;
    }

    .dr-row.row-pending:hover {
        background: #fef3c7;
    }

    .dr-row.status-approved {
        border-left: 3px solid #22c55e;
    }

    .dr-row.status-rejected {
        border-left: 3px solid #ef4444;
        opacity: 0.75;
    }

    .dr-table td {
        padding: 13px 16px;
        vertical-align: middle;
    }

    /* Section divider between status groups */
    .section-divider td {
        padding: 6px 16px;
        background: #f8fafc;
        border-top: 2px solid #e2e8f0;
        border-bottom: 2px solid #e2e8f0;
    }

    .divider-label {
        font-size: 0.78rem;
        font-weight: 600;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .row-num {
        color: #cbd5e1;
        font-size: 0.82rem;
        font-weight: 600;
        width: 32px;
    }

    /* User cell */
    .user-cell {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .avatar {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 15px;
        flex-shrink: 0;
    }

    .user-meta {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .user-name {
        font-weight: 600;
        font-size: 0.9rem;
        color: #1e293b;
    }

    .user-email {
        font-size: 0.78rem;
        color: #94a3b8;
    }

    /* Amount */
    .amount-cell {
        font-weight: 700;
        font-size: 1rem;
        color: #166534;
    }

    /* Txn ID */
    .txn-cell {
        background: #f8fafc;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.82rem;
        color: #475569;
        border: 1px solid #e2e8f0;
        font-family: 'Courier New', monospace;
    }

    /* Date */
    .date-cell {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .date-main {
        font-size: 0.88rem;
        font-weight: 600;
        color: #334155;
    }

    .date-time {
        font-size: 0.78rem;
        color: #94a3b8;
    }

    /* Status Badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 50px;
        font-size: 0.82rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .status-badge.pending {
        background: #fef3c7;
        color: #92400e;
    }

    .status-badge.approved {
        background: #dcfce7;
        color: #166534;
    }

    .status-badge.rejected {
        background: #fee2e2;
        color: #991b1b;
    }

    /* Pulsing dot for pending */
    .pulse-dot {
        width: 7px;
        height: 7px;
        background: #f59e0b;
        border-radius: 50%;
        display: inline-block;
        animation: pulse-dot 1.5s infinite;
    }

    @keyframes pulse-dot {

        0%,
        100% {
            opacity: 1;
            transform: scale(1);
        }

        50% {
            opacity: 0.5;
            transform: scale(0.7);
        }
    }

    /* Action Buttons */
    .action-btns {
        display: flex;
        gap: 7px;
        align-items: center;
    }

    .btn-approve,
    .btn-reject {
        padding: 6px 14px;
        border: none;
        border-radius: 7px;
        font-size: 0.82rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.2s;
        white-space: nowrap;
    }

    .btn-approve {
        background: #22c55e;
        color: white;
    }

    .btn-approve:hover {
        background: #16a34a;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(34, 197, 94, 0.35);
    }

    .btn-reject {
        background: #fee2e2;
        color: #ef4444;
    }

    .btn-reject:hover {
        background: #ef4444;
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .btn-approve:disabled,
    .btn-reject:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none !important;
    }

    .processed-info {
        display: flex;
        align-items: center;
        gap: 7px;
        font-size: 0.82rem;
        color: #94a3b8;
    }

    /* Footer */
    .dr-footer {
        padding: 12px 18px;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.85rem;
        color: #64748b;
    }

    .clear-filter {
        color: #667eea;
        text-decoration: none;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .clear-filter:hover {
        text-decoration: underline;
    }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #94a3b8;
    }

    .empty-icon {
        font-size: 3rem;
        margin-bottom: 14px;
        opacity: 0.3;
    }

    .empty-state p {
        font-size: 1rem;
        font-weight: 600;
        color: #64748b;
        margin: 0 0 4px;
    }

    .empty-state small {
        font-size: 0.83rem;
    }

    /* Loading Overlay */
    .loading-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.5);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(3px);
    }

    .loading-box {
        background: white;
        border-radius: 16px;
        padding: 36px 48px;
        text-align: center;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
    }

    .spinner {
        width: 42px;
        height: 42px;
        border: 3px solid #e2e8f0;
        border-top-color: #667eea;
        border-radius: 50%;
        animation: spin 0.7s linear infinite;
        margin: 0 auto 16px;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    .loading-box p {
        margin: 0;
        font-weight: 600;
        color: #334155;
        font-size: 0.95rem;
    }

    /* Row flash after action */
    @keyframes rowFlashGreen {
        0% {
            background: #dcfce7;
        }

        100% {
            background: transparent;
        }
    }

    @keyframes rowFlashRed {
        0% {
            background: #fee2e2;
        }

        100% {
            background: transparent;
        }
    }

    .flash-green {
        animation: rowFlashGreen 1.5s ease forwards;
    }

    .flash-red {
        animation: rowFlashRed 1.5s ease forwards;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .dr-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .dr-header-actions {
            width: 100%;
        }

        .filter-btn {
            flex: 1;
            justify-content: center;
            font-size: 0.78rem;
        }

        .action-btns {
            flex-direction: column;
        }

        .btn-approve,
        .btn-reject {
            width: 100%;
            justify-content: center;
        }

        .dr-stats {
            gap: 6px;
        }

        .stat-pill {
            font-size: 0.78rem;
            padding: 6px 12px;
        }
    }
</style>

<script>
    function handleAction(action, id, btnEl) {
        const isApprove = action === 'approve';
        const confirmMsg = isApprove ?
            '✅ Approve this deposit and credit the user\'s wallet?' :
            '❌ Reject this deposit request?';

        if (!confirm(confirmMsg)) return;

        // Disable both buttons in this row to prevent double-click
        const row = document.getElementById('row-' + id);
        row.querySelectorAll('.btn-approve, .btn-reject').forEach(b => {
            b.disabled = true;
            b.style.opacity = '0.5';
        });

        // Show loading overlay
        const overlay = document.getElementById('loadingOverlay');
        const loadText = document.getElementById('loadingText');
        loadText.textContent = isApprove ? 'Approving deposit...' : 'Rejecting request...';
        overlay.style.display = 'flex';

        // Navigate to action URL
        const url = '<?php echo site_url("admin/deposits/"); ?>' + action + '/' + id;
        window.location.href = url;
    }

    // Auto-dismiss flash messages after 4 seconds
    setTimeout(function() {
        document.querySelectorAll('.flash').forEach(function(el) {
            el.style.transition = 'opacity 0.4s';
            el.style.opacity = '0';
            setTimeout(function() {
                el.remove();
            }, 400);
        });
    }, 4000);
</script>