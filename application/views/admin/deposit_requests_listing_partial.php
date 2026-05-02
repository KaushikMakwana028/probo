<?php
$requests = isset($requests) && is_array($requests) ? $requests : array();
$listing = isset($listing) && is_array($listing) ? $listing : array(
    'page' => 1,
    'per_page' => 10,
    'total_filtered' => count($requests),
    'total_pages' => 1,
    'start' => empty($requests) ? 0 : 1,
    'end' => count($requests)
);
$status_filter = isset($status_filter) ? (string) $status_filter : '';
$pending_requests = array();
$approved_requests = array();
$rejected_requests = array();

foreach ($requests as $request_item) {
    $request_status = isset($request_item->status) ? $request_item->status : '';

    if ($request_status === 'pending') {
        $pending_requests[] = $request_item;
    } elseif ($request_status === 'approved') {
        $approved_requests[] = $request_item;
    } elseif ($request_status === 'rejected') {
        $rejected_requests[] = $request_item;
    }
}

$all_sorted = array_merge($pending_requests, $approved_requests, $rejected_requests);
$current_page = max(1, (int) $listing['page']);
$total_pages = max(1, (int) $listing['total_pages']);
$start = isset($listing['start']) ? (int) $listing['start'] : 0;
$end = isset($listing['end']) ? (int) $listing['end'] : count($all_sorted);
$total_filtered = isset($listing['total_filtered']) ? (int) $listing['total_filtered'] : count($all_sorted);
$visible_pages = array();

for ($page_num = 1; $page_num <= $total_pages; $page_num++) {
    if ($page_num === 1 || $page_num === $total_pages || abs($page_num - $current_page) <= 1) {
        $visible_pages[] = $page_num;
    }
}
?>

<?php if (!empty($all_sorted)): ?>

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
                $row_num = $start;
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
                    $user_name = trim((string) ($r->name ?? ''));
                    $user_email = (string) ($r->email ?? '');
                    $user_label = $user_name !== '' ? $user_name : 'Unknown User';
                    $avatar_bg = '#' . substr(md5($user_label), 0, 6);
                    $avatar_initial = strtoupper(substr($user_label, 0, 1));
                    ?>
                    <tr class="dr-row dr-row--<?php echo $r->status; ?>" id="row-<?php echo $r->id; ?>">
                        <td class="dr-td dr-td--num"><?php echo $row_num++; ?></td>
                        <td class="dr-td">
                            <div class="dr-user">
                                <div class="dr-avatar" style="background:<?php echo $avatar_bg; ?>">
                                    <?php echo $avatar_initial; ?>
                                </div>
                                <div>
                                    <div class="dr-user__name"><?php echo htmlspecialchars($user_label, ENT_QUOTES, 'UTF-8'); ?></div>
                                    <div class="dr-user__email"><?php echo htmlspecialchars($user_email, ENT_QUOTES, 'UTF-8'); ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="dr-td">
                            <span class="dr-amount">Rs <?php echo number_format($r->amount, 2); ?></span>
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
                                <span class="dr-nil">-</span>
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
            $user_name = trim((string) ($r->name ?? ''));
            $user_email = (string) ($r->email ?? '');
            $user_label = $user_name !== '' ? $user_name : 'Unknown User';
            $avatar_bg = '#' . substr(md5($user_label), 0, 6);
            $initials = strtoupper(substr($user_label, 0, 2));
            ?>
            <div class="dr-mcard dr-mcard--<?php echo $r->status; ?>" id="mcard-<?php echo $r->id; ?>">
                <div class="dr-mcard__top">
                    <div class="dr-mcard__user">
                        <div class="dr-mcard__avatar" style="background:<?php echo $avatar_bg; ?>"><?php echo $initials; ?></div>
                        <div class="dr-mcard__info">
                            <span class="dr-mcard__name"><?php echo htmlspecialchars($user_label, ENT_QUOTES, 'UTF-8'); ?></span>
                            <span class="dr-mcard__email"><?php echo htmlspecialchars($user_email, ENT_QUOTES, 'UTF-8'); ?></span>
                        </div>
                    </div>
                    <div class="dr-mcard__right">
                        <div class="dr-mcard__amount">Rs <?php echo number_format($r->amount, 2); ?></div>
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

    <div class="dr-footer">
        <span class="dr-footer__text">
            Showing <strong><?php echo $start; ?></strong> to <strong><?php echo $end; ?></strong> of <strong><?php echo $total_filtered; ?></strong> record(s)
            <?php if ($status_filter !== ''): ?>
                - filtered by <strong><?php echo ucfirst($status_filter); ?></strong>
            <?php endif; ?>
        </span>
        <?php if ($status_filter !== ''): ?>
            <a href="<?php echo site_url('admin/deposits/requests'); ?>" class="dr-clear-link" data-status="" data-page="1">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
                Clear filter
            </a>
        <?php endif; ?>
    </div>

    <?php if ($total_pages > 1): ?>
        <div class="dr-pagination" id="drPagination">
            <button type="button" class="dr-page-btn" data-page="<?php echo max(1, $current_page - 1); ?>" <?php echo $current_page <= 1 ? 'disabled' : ''; ?>>Previous</button>
            <div class="dr-page-numbers">
                <?php $last_page = 0; ?>
                <?php foreach ($visible_pages as $page_num): ?>
                    <?php if ($page_num - $last_page > 1): ?>
                        <span class="dr-page-ellipsis">...</span>
                    <?php endif; ?>
                    <button type="button" class="dr-page-num <?php echo $page_num === $current_page ? 'active' : ''; ?>" data-page="<?php echo $page_num; ?>" <?php echo $page_num === $current_page ? 'disabled' : ''; ?>><?php echo $page_num; ?></button>
                    <?php $last_page = $page_num; ?>
                <?php endforeach; ?>
            </div>
            <button type="button" class="dr-page-btn" data-page="<?php echo min($total_pages, $current_page + 1); ?>" <?php echo $current_page >= $total_pages ? 'disabled' : ''; ?>>Next</button>
        </div>
    <?php endif; ?>

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
