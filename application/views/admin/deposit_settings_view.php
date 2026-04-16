<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">

<div class="ps-wrap">

    <!-- Page Header -->
    <div class="ps-header">
        <div class="ps-header-left">
            <div class="ps-icon-box">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="3" />
                    <path d="M19.07 4.93a10 10 0 0 1 0 14.14" />
                    <path d="M4.93 4.93a10 10 0 0 0 0 14.14" />
                </svg>
            </div>
            <div>
                <h1>Payment Settings</h1>
                <p>Configure UPI, bank details and QR code</p>
            </div>
        </div>
        <a href="<?php echo site_url('admin/deposits/requests'); ?>" class="ps-view-requests-btn">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="8" y1="6" x2="21" y2="6" />
                <line x1="8" y1="12" x2="21" y2="12" />
                <line x1="8" y1="18" x2="21" y2="18" />
                <line x1="3" y1="6" x2="3.01" y2="6" />
                <line x1="3" y1="12" x2="3.01" y2="12" />
                <line x1="3" y1="18" x2="3.01" y2="18" />
            </svg>
            View Requests
        </a>
    </div>

    <!-- Flash Messages -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="ps-flash ps-success" id="psFlash">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                <polyline points="22 4 12 14.01 9 11.01" />
            </svg>
            <?php echo $this->session->flashdata('success'); ?>
            <button class="ps-flash-close" onclick="this.parentElement.remove()">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
            </button>
        </div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="ps-flash ps-error" id="psFlash">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <circle cx="12" cy="12" r="10" />
                <line x1="12" y1="8" x2="12" y2="12" />
                <line x1="12" y1="16" x2="12.01" y2="16" />
            </svg>
            <?php echo $this->session->flashdata('error'); ?>
            <button class="ps-flash-close" onclick="this.parentElement.remove()">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
            </button>
        </div>
    <?php endif; ?>

    <!-- Main Grid -->
    <div class="ps-grid">

        <!-- Left: Form -->
        <div class="ps-form-col">
            <form method="post" action="<?php echo site_url('admin/deposits/save_settings'); ?>" enctype="multipart/form-data" id="settingsForm">

                <!-- UPI Section -->
                <div class="ps-card">
                    <div class="ps-card-head">
                        <div class="ps-card-head-icon upi-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="8" x2="12" y2="12" />
                                <line x1="12" y1="16" x2="12.01" y2="16" />
                            </svg>
                        </div>
                        <div>
                            <h2>UPI Details</h2>
                            <p>Accept payments via any UPI app</p>
                        </div>
                    </div>
                    <div class="ps-card-body">
                        <div class="ps-field">
                            <label for="upi_id">UPI ID <span class="req">*</span></label>
                            <div class="ps-input-wrap">
                                <span class="ps-input-icon">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="4" />
                                        <path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-3.92 7.94" />
                                    </svg>
                                </span>
                                <input
                                    type="text"
                                    name="upi_id"
                                    id="upi_id"
                                    value="<?php echo htmlspecialchars($settings->upi_id ?? ''); ?>"
                                    placeholder="yourname@upi"
                                    required>
                            </div>
                            <span class="ps-hint">Users can send payments directly to this UPI ID</span>
                        </div>
                    </div>
                </div>

                <!-- Bank Section -->
                <div class="ps-card">
                    <div class="ps-card-head">
                        <div class="ps-card-head-icon bank-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="3" y1="22" x2="21" y2="22" />
                                <line x1="6" y1="18" x2="6" y2="11" />
                                <line x1="10" y1="18" x2="10" y2="11" />
                                <line x1="14" y1="18" x2="14" y2="11" />
                                <line x1="18" y1="18" x2="18" y2="11" />
                                <polygon points="12 2 20 7 4 7" />
                            </svg>
                        </div>
                        <div>
                            <h2>Bank Account</h2>
                            <p>For direct bank transfers</p>
                        </div>
                    </div>
                    <div class="ps-card-body">
                        <div class="ps-field">
                            <label for="bank_name">Bank Name</label>
                            <div class="ps-input-wrap">
                                <span class="ps-input-icon">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="3" y1="22" x2="21" y2="22" />
                                        <polygon points="12 2 20 7 4 7" />
                                    </svg>
                                </span>
                                <input type="text" name="bank_name" id="bank_name"
                                    value="<?php echo htmlspecialchars($settings->bank_name ?? ''); ?>"
                                    placeholder="e.g. State Bank of India">
                            </div>
                        </div>
                        <div class="ps-field-row">
                            <div class="ps-field">
                                <label for="account_number">Account Number</label>
                                <div class="ps-input-wrap">
                                    <span class="ps-input-icon">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2" />
                                            <line x1="1" y1="10" x2="23" y2="10" />
                                        </svg>
                                    </span>
                                    <input type="text" name="account_number" id="account_number"
                                        value="<?php echo htmlspecialchars($settings->account_number ?? ''); ?>"
                                        placeholder="Account number">
                                </div>
                            </div>
                            <div class="ps-field">
                                <label for="ifsc">IFSC Code</label>
                                <div class="ps-input-wrap">
                                    <span class="ps-input-icon">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="16 18 22 12 16 6" />
                                            <polyline points="8 6 2 12 8 18" />
                                        </svg>
                                    </span>
                                    <input type="text" name="ifsc" id="ifsc"
                                        value="<?php echo htmlspecialchars($settings->ifsc ?? ''); ?>"
                                        placeholder="e.g. SBIN0001234"
                                        style="text-transform:uppercase">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- QR Code Section -->
                <div class="ps-card">
                    <div class="ps-card-head">
                        <div class="ps-card-head-icon qr-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="7" height="7" />
                                <rect x="14" y="3" width="7" height="7" />
                                <rect x="3" y="14" width="7" height="7" />
                                <path d="M21 21h-4v-4" />
                                <path d="M14 17h3" />
                                <path d="M17 14h4" />
                            </svg>
                        </div>
                        <div>
                            <h2>QR Code</h2>
                            <p>Scan to pay — fast and easy</p>
                        </div>
                    </div>
                    <div class="ps-card-body">
                        <!-- Current QR -->
                        <?php if (!empty($settings->qr_image)): ?>
                            <div class="current-qr-block">
                                <div class="cqr-label-row">
                                    <span class="cqr-label">Current QR Code</span>
                                    <a href="<?php echo site_url('admin/deposits/delete_qr'); ?>"
                                        class="cqr-delete-btn"
                                        onclick="return confirm('Delete this QR code?')">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6" />
                                            <path d="M19 6l-1 14H6L5 6" />
                                            <path d="M10 11v6" />
                                            <path d="M14 11v6" />
                                            <path d="M9 6V4h6v2" />
                                        </svg>
                                        Remove
                                    </a>
                                </div>
                                <div class="cqr-preview">
                                    <img src="<?php echo base_url('uploads/qr/' . $settings->qr_image); ?>" alt="QR Code">
                                    <div class="cqr-overlay">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                            <circle cx="12" cy="12" r="3" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Upload Zone -->
                        <div class="ps-upload-zone" id="uploadZone" onclick="document.getElementById('qr_image').click()">
                            <input type="file" name="qr_image" id="qr_image" accept="image/*" style="display:none" onchange="handleFileChange(event)">
                            <div class="upload-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="16 16 12 12 8 16" />
                                    <line x1="12" y1="12" x2="12" y2="21" />
                                    <path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3" />
                                </svg>
                            </div>
                            <p class="upload-title" id="uploadTitle">Click to upload QR image</p>
                            <p class="upload-hint">JPG, PNG or GIF — max 2MB</p>
                        </div>

                        <!-- New QR Preview -->
                        <div class="new-qr-preview" id="newQrPreview" style="display:none">
                            <div class="nqr-label">New QR — Preview</div>
                            <img id="previewImg" src="" alt="Preview">
                            <button type="button" class="nqr-remove" onclick="removePreview()">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <line x1="18" y1="6" x2="6" y2="18" />
                                    <line x1="6" y1="6" x2="18" y2="18" />
                                </svg>
                                Remove
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Submit Row -->
                <div class="ps-submit-row">
                    <button type="submit" class="ps-save-btn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                            <polyline points="17 21 17 13 7 13 7 21" />
                            <polyline points="7 3 7 8 15 8" />
                        </svg>
                        Save Settings
                    </button>
                    <button type="reset" class="ps-reset-btn" onclick="removePreview()">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="1 4 1 10 7 10" />
                            <path d="M3.51 15a9 9 0 1 0 .49-3.21" />
                        </svg>
                        Reset
                    </button>
                </div>

            </form>
        </div>

        <!-- Right: Sidebar -->
        <div class="ps-sidebar">

            <!-- Quick Stats -->
            <div class="ps-side-card">
                <div class="ps-side-card-head">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12" />
                    </svg>
                    Quick Stats
                </div>
                <div class="ps-side-card-body">
                    <?php
                    $pending = $this->db->where('status', 'pending')->count_all_results('deposit_requests');
                    $today_deposits = $this->db->where('DATE(created_at)', date('Y-m-d'))
                        ->where('status', 'approved')
                        ->count_all_results('deposit_requests');
                    ?>
                    <div class="ps-stat-row">
                        <div class="ps-stat-icon pending-ico">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <polyline points="12 6 12 12 16 14" />
                            </svg>
                        </div>
                        <div class="ps-stat-info">
                            <span class="ps-stat-num"><?php echo $pending; ?></span>
                            <span class="ps-stat-lbl">Pending Requests</span>
                        </div>
                    </div>
                    <div class="ps-stat-row">
                        <div class="ps-stat-icon today-ico">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                <line x1="16" y1="2" x2="16" y2="6" />
                                <line x1="8" y1="2" x2="8" y2="6" />
                                <line x1="3" y1="10" x2="21" y2="10" />
                            </svg>
                        </div>
                        <div class="ps-stat-info">
                            <span class="ps-stat-num"><?php echo $today_deposits; ?></span>
                            <span class="ps-stat-lbl">Today's Approvals</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info -->
            <div class="ps-side-card">
                <div class="ps-side-card-head">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="12" y1="8" x2="12" y2="12" />
                        <line x1="12" y1="16" x2="12.01" y2="16" />
                    </svg>
                    How It Works
                </div>
                <div class="ps-side-card-body">
                    <div class="ps-info-step">
                        <div class="ps-step-num">1</div>
                        <div>
                            <strong>User initiates deposit</strong>
                            <p>User sends payment to your UPI ID or scans the QR code</p>
                        </div>
                    </div>
                    <div class="ps-info-step">
                        <div class="ps-step-num">2</div>
                        <div>
                            <strong>Receipt uploaded</strong>
                            <p>User uploads proof of payment and submits the request</p>
                        </div>
                    </div>
                    <div class="ps-info-step">
                        <div class="ps-step-num">3</div>
                        <div>
                            <strong>Admin approves</strong>
                            <p>You verify and approve — wallet is credited instantly</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Help -->
            <div class="ps-side-card help-side-card">
                <div class="ps-side-card-head">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                        <line x1="12" y1="17" x2="12.01" y2="17" />
                    </svg>
                    Need Help?
                </div>
                <div class="ps-side-card-body">
                    <p class="ps-help-text">Having trouble setting up payment details?</p>
                    <a href="#" class="ps-help-btn">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z" />
                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z" />
                        </svg>
                        View Documentation
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    *,
    *::before,
    *::after {
        box-sizing: border-box;
    }

    .ps-wrap {
        font-family: 'Roboto', 'Segoe UI', system-ui, sans-serif;
        padding: 0 0 48px;
        max-width: 1200px;
    }

    /* ─── Header ─── */
    .ps-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        gap: 12px;
        flex-wrap: wrap;
    }

    .ps-header-left {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .ps-icon-box {
        width: 48px;
        height: 48px;
        background: #F0FDF4;
        color: #059669;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .ps-header h1 {
        margin: 0;
        font-size: 1.35rem;
        font-weight: 600;
        color: #111827;
        letter-spacing: -0.3px;
    }

    .ps-header p {
        margin: 2px 0 0;
        font-size: 0.82rem;
        color: #9CA3AF;
    }

    .ps-view-requests-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 18px;
        background: #fff;
        border: 1px solid #E5E7EB;
        border-radius: 9px;
        font-size: 0.84rem;
        font-weight: 500;
        color: #374151;
        text-decoration: none;
        transition: all 0.18s;
        white-space: nowrap;
    }

    .ps-view-requests-btn:hover {
        background: #F9FAFB;
        border-color: #D1D5DB;
    }

    /* ─── Flash ─── */
    .ps-flash {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 13px 18px;
        border-radius: 10px;
        margin-bottom: 20px;
        font-size: 0.88rem;
        font-weight: 500;
        animation: psSlide 0.3s ease;
        position: relative;
    }

    @keyframes psSlide {
        from {
            opacity: 0;
            transform: translateY(-6px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .ps-success {
        background: #D1FAE5;
        color: #065F46;
        border-left: 3px solid #059669;
    }

    .ps-error {
        background: #FEE2E2;
        color: #7F1D1D;
        border-left: 3px solid #DC2626;
    }

    .ps-flash-close {
        margin-left: auto;
        background: none;
        border: none;
        cursor: pointer;
        color: inherit;
        opacity: 0.5;
        padding: 2px;
        display: flex;
        align-items: center;
        transition: opacity 0.15s;
    }

    .ps-flash-close:hover {
        opacity: 1;
    }

    /* ─── Grid ─── */
    .ps-grid {
        display: grid;
        grid-template-columns: 1fr 300px;
        gap: 20px;
        align-items: start;
    }

    /* ─── Cards (form) ─── */
    .ps-card {
        background: #fff;
        border-radius: 14px;
        border: 1px solid #F3F4F6;
        margin-bottom: 16px;
        overflow: hidden;
    }

    .ps-card:last-of-type {
        margin-bottom: 0;
    }

    .ps-card-head {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 18px 22px;
        border-bottom: 1px solid #F9FAFB;
        background: #FAFAFA;
    }

    .ps-card-head-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .upi-icon {
        background: #EEF2FF;
        color: #4F46E5;
    }

    .bank-icon {
        background: #FFF7ED;
        color: #C2410C;
    }

    .qr-icon {
        background: #F0FDF4;
        color: #059669;
    }

    .ps-card-head h2 {
        margin: 0;
        font-size: 0.95rem;
        font-weight: 600;
        color: #111827;
    }

    .ps-card-head p {
        margin: 2px 0 0;
        font-size: 0.78rem;
        color: #9CA3AF;
    }

    .ps-card-body {
        padding: 22px;
    }

    /* ─── Fields ─── */
    .ps-field {
        margin-bottom: 18px;
    }

    .ps-field:last-child {
        margin-bottom: 0;
    }

    .ps-field label {
        display: block;
        font-size: 0.82rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 7px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .req {
        color: #DC2626;
    }

    .ps-input-wrap {
        position: relative;
    }

    .ps-input-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #9CA3AF;
        display: flex;
        align-items: center;
        pointer-events: none;
    }

    .ps-input-wrap input[type="text"] {
        width: 100%;
        padding: 10px 12px 10px 38px;
        border: 1px solid #E5E7EB;
        border-radius: 9px;
        font-size: 0.88rem;
        color: #111827;
        background: #fff;
        font-family: inherit;
        transition: border-color 0.18s, box-shadow 0.18s;
        outline: none;
    }

    .ps-input-wrap input[type="text"]:focus {
        border-color: #818CF8;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }

    .ps-hint {
        display: block;
        font-size: 0.77rem;
        color: #9CA3AF;
        margin-top: 6px;
    }

    .ps-field-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }

    /* ─── Upload Zone ─── */
    .ps-upload-zone {
        border: 1.5px dashed #D1D5DB;
        border-radius: 12px;
        padding: 28px 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        background: #FAFAFA;
    }

    .ps-upload-zone:hover {
        border-color: #818CF8;
        background: #F5F3FF;
    }

    .upload-icon {
        width: 48px;
        height: 48px;
        background: #EEF2FF;
        color: #4F46E5;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 12px;
    }

    .upload-title {
        margin: 0 0 4px;
        font-size: 0.88rem;
        font-weight: 600;
        color: #374151;
    }

    .upload-hint {
        margin: 0;
        font-size: 0.78rem;
        color: #9CA3AF;
    }

    /* ─── Current QR ─── */
    .current-qr-block {
        background: #F9FAFB;
        border-radius: 10px;
        padding: 14px;
        margin-bottom: 16px;
        border: 1px solid #F3F4F6;
    }

    .cqr-label-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }

    .cqr-label {
        font-size: 0.8rem;
        font-weight: 600;
        color: #6B7280;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .cqr-delete-btn {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 0.78rem;
        color: #DC2626;
        text-decoration: none;
        font-weight: 500;
        transition: opacity 0.15s;
    }

    .cqr-delete-btn:hover {
        opacity: 0.7;
    }

    .cqr-preview {
        position: relative;
        display: inline-block;
        border-radius: 10px;
        overflow: hidden;
    }

    .cqr-preview img {
        display: block;
        max-width: 180px;
        border-radius: 10px;
        border: 1px solid #E5E7EB;
    }

    .cqr-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.2s;
        border-radius: 10px;
    }

    .cqr-preview:hover .cqr-overlay {
        opacity: 1;
    }

    /* ─── New QR Preview ─── */
    .new-qr-preview {
        margin-top: 14px;
        background: #EEF2FF;
        border-radius: 10px;
        padding: 16px;
        text-align: center;
    }

    .nqr-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: #4F46E5;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 10px;
    }

    .new-qr-preview img {
        max-width: 160px;
        border-radius: 8px;
        border: 2px solid #818CF8;
        display: block;
        margin: 0 auto 10px;
    }

    .nqr-remove {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: none;
        border: none;
        font-size: 0.78rem;
        color: #6B7280;
        cursor: pointer;
        font-family: inherit;
        padding: 4px 8px;
        border-radius: 6px;
        transition: background 0.15s;
    }

    .nqr-remove:hover {
        background: rgba(0, 0, 0, 0.06);
    }

    /* ─── Submit Row ─── */
    .ps-submit-row {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .ps-save-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 11px 24px;
        background: #4F46E5;
        color: #fff;
        border: none;
        border-radius: 9px;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        font-family: inherit;
        transition: all 0.18s;
    }

    .ps-save-btn:hover {
        background: #4338CA;
        transform: translateY(-1px);
    }

    .ps-reset-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 11px 20px;
        background: #fff;
        color: #6B7280;
        border: 1px solid #E5E7EB;
        border-radius: 9px;
        font-size: 0.88rem;
        font-weight: 500;
        cursor: pointer;
        font-family: inherit;
        transition: all 0.18s;
    }

    .ps-reset-btn:hover {
        background: #F9FAFB;
    }

    /* ─── Sidebar ─── */
    .ps-sidebar {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .ps-side-card {
        background: #fff;
        border-radius: 14px;
        border: 1px solid #F3F4F6;
        overflow: hidden;
    }

    .ps-side-card-head {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 14px 18px;
        background: #F9FAFB;
        border-bottom: 1px solid #F3F4F6;
        font-size: 0.82rem;
        font-weight: 600;
        color: #374151;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .ps-side-card-body {
        padding: 16px 18px;
    }

    /* Stats */
    .ps-stat-row {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid #F9FAFB;
    }

    .ps-stat-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .ps-stat-row:first-child {
        padding-top: 0;
    }

    .ps-stat-icon {
        width: 36px;
        height: 36px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .pending-ico {
        background: #FEF3C7;
        color: #D97706;
    }

    .today-ico {
        background: #D1FAE5;
        color: #059669;
    }

    .ps-stat-info {
        display: flex;
        flex-direction: column;
        gap: 1px;
    }

    .ps-stat-num {
        font-size: 1.2rem;
        font-weight: 600;
        color: #111827;
    }

    .ps-stat-lbl {
        font-size: 0.76rem;
        color: #9CA3AF;
    }

    /* How it works steps */
    .ps-info-step {
        display: flex;
        gap: 12px;
        align-items: flex-start;
        margin-bottom: 14px;
    }

    .ps-info-step:last-child {
        margin-bottom: 0;
    }

    .ps-step-num {
        width: 24px;
        height: 24px;
        background: #EEF2FF;
        color: #4F46E5;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 700;
        flex-shrink: 0;
        margin-top: 1px;
    }

    .ps-info-step strong {
        display: block;
        font-size: 0.83rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 2px;
    }

    .ps-info-step p {
        margin: 0;
        font-size: 0.78rem;
        color: #9CA3AF;
        line-height: 1.5;
    }

    /* Help */
    .ps-help-text {
        font-size: 0.84rem;
        color: #6B7280;
        margin: 0 0 12px;
    }

    .ps-help-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 16px;
        background: #0891B2;
        color: #fff;
        border-radius: 8px;
        font-size: 0.83rem;
        font-weight: 500;
        text-decoration: none;
        transition: background 0.18s;
    }

    .ps-help-btn:hover {
        background: #0E7490;
    }

    /* ─── Responsive ─── */
    @media (max-width: 900px) {
        .ps-grid {
            grid-template-columns: 1fr;
        }

        .ps-sidebar {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 14px;
        }
    }

    @media (max-width: 640px) {
        .ps-field-row {
            grid-template-columns: 1fr;
        }

        .ps-sidebar {
            grid-template-columns: 1fr;
        }

        .ps-submit-row {
            flex-direction: column;
        }

        .ps-save-btn,
        .ps-reset-btn {
            width: 100%;
            justify-content: center;
        }

        .ps-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .ps-view-requests-btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<script>
    function handleFileChange(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('newQrPreview');
        const img = document.getElementById('previewImg');
        const title = document.getElementById('uploadTitle');

        if (file) {
            const reader = new FileReader();
            reader.onload = e => {
                img.src = e.target.result;
                preview.style.display = 'block';
                title.textContent = file.name;
            };
            reader.readAsDataURL(file);
        }
    }

    function removePreview() {
        document.getElementById('newQrPreview').style.display = 'none';
        document.getElementById('previewImg').src = '';
        document.getElementById('qr_image').value = '';
        document.getElementById('uploadTitle').textContent = 'Click to upload QR image';
    }

    setTimeout(() => {
        const flash = document.getElementById('psFlash');
        if (flash) {
            flash.style.transition = 'opacity 0.4s';
            flash.style.opacity = '0';
            setTimeout(() => flash && flash.remove(), 400);
        }
    }, 5000);

    document.getElementById('settingsForm').addEventListener('submit', function(e) {
        const upi = document.getElementById('upi_id').value.trim();
        if (!upi) {
            e.preventDefault();
            document.getElementById('upi_id').focus();
            document.getElementById('upi_id').style.borderColor = '#DC2626';
            document.getElementById('upi_id').style.boxShadow = '0 0 0 3px rgba(220,38,38,0.1)';
        }
    });
</script>