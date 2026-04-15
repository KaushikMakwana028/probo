<div class="settings-container">
    <!-- Header -->
    <div class="page-header">
        <div>
            <h2><i class="fas fa-cog"></i> Payment Settings</h2>
            <p class="subtitle">Configure your payment gateway and bank details</p>
        </div>
        <div class="header-actions">
            <a href="<?php echo site_url('admin/deposits/requests'); ?>" class="btn btn-outline">
                <i class="fas fa-list"></i> View Requests
            </a>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <?php echo $this->session->flashdata('success'); ?>
            <button class="alert-close" onclick="this.parentElement.remove()">×</button>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <?php echo $this->session->flashdata('error'); ?>
            <button class="alert-close" onclick="this.parentElement.remove()">×</button>
        </div>
    <?php endif; ?>

    <!-- Main Content -->
    <div class="settings-grid">
        <!-- Settings Form -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-university"></i> Payment Information</h3>
            </div>
            <div class="card-body">
                <form method="post" action="<?php echo site_url('admin/deposits/save_settings'); ?>" enctype="multipart/form-data" id="settingsForm">

                    <!-- UPI Section -->
                    <div class="form-section">
                        <h4><i class="fab fa-google-pay"></i> UPI Details</h4>
                        <div class="form-group">
                            <label for="upi_id">
                                UPI ID <span class="required">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-at"></i>
                                </span>
                                <input
                                    type="text"
                                    name="upi_id"
                                    id="upi_id"
                                    value="<?php echo htmlspecialchars($settings->upi_id ?? ''); ?>"
                                    placeholder="example@upi"
                                    required>
                            </div>
                            <small class="form-hint">Enter your UPI ID for payments</small>
                        </div>
                    </div>

                    <!-- Bank Details Section -->
                    <div class="form-section">
                        <h4><i class="fas fa-building"></i> Bank Account Details</h4>

                        <div class="form-group">
                            <label for="bank_name">Bank Name</label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-landmark"></i>
                                </span>
                                <input
                                    type="text"
                                    name="bank_name"
                                    id="bank_name"
                                    value="<?php echo htmlspecialchars($settings->bank_name ?? ''); ?>"
                                    placeholder="e.g., State Bank of India">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="account_number">Account Number</label>
                                <div class="input-group">
                                    <span class="input-icon">
                                        <i class="fas fa-credit-card"></i>
                                    </span>
                                    <input
                                        type="text"
                                        name="account_number"
                                        id="account_number"
                                        value="<?php echo htmlspecialchars($settings->account_number ?? ''); ?>"
                                        placeholder="Enter account number">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="ifsc">IFSC Code</label>
                                <div class="input-group">
                                    <span class="input-icon">
                                        <i class="fas fa-code"></i>
                                    </span>
                                    <input
                                        type="text"
                                        name="ifsc"
                                        id="ifsc"
                                        value="<?php echo htmlspecialchars($settings->ifsc ?? ''); ?>"
                                        placeholder="e.g., SBIN0001234"
                                        style="text-transform: uppercase;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- QR Code Section -->
                    <div class="form-section">
                        <h4><i class="fas fa-qrcode"></i> QR Code</h4>

                        <div class="form-group">
                            <label for="qr_image">Upload QR Code</label>
                            <div class="file-upload-wrapper">
                                <input
                                    type="file"
                                    name="qr_image"
                                    id="qr_image"
                                    accept="image/*"
                                    onchange="previewImage(event)">
                                <label for="qr_image" class="file-upload-label">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <span>Choose Image</span>
                                    <small>JPG, PNG or GIF (Max 2MB)</small>
                                </label>
                            </div>
                        </div>

                        <!-- Current QR Code Display -->
                        <?php if (!empty($settings->qr_image)): ?>
                            <div class="current-qr" id="currentQR">
                                <div class="qr-header">
                                    <span>Current QR Code</span>
                                    <a href="<?php echo site_url('admin/deposits/delete_qr'); ?>"
                                        class="btn-delete-qr"
                                        onclick="return confirm('Are you sure you want to delete this QR code?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </div>
                                <div class="qr-preview">
                                    <img src="<?php echo base_url('uploads/qr/' . $settings->qr_image); ?>" alt="QR Code">
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- New QR Preview -->
                        <div class="qr-preview-new" id="qrPreview" style="display: none;">
                            <p>New QR Code Preview:</p>
                            <img id="previewImg" src="" alt="Preview">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Settings
                        </button>
                        <button type="reset" class="btn btn-secondary" onclick="resetPreview()">
                            <i class="fas fa-undo"></i> Reset
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Info Panel -->
        <div class="info-panel">
            <!-- Quick Info -->
            <div class="card info-card">
                <div class="card-header">
                    <h3><i class="fas fa-info-circle"></i> Information</h3>
                </div>
                <div class="card-body">
                    <div class="info-item">
                        <i class="fas fa-check-circle text-success"></i>
                        <div>
                            <strong>UPI Payments</strong>
                            <p>Users can pay via any UPI app</p>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-qrcode text-primary"></i>
                        <div>
                            <strong>QR Code</strong>
                            <p>Quick scan and pay option</p>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-shield-alt text-warning"></i>
                        <div>
                            <strong>Secure</strong>
                            <p>All transactions are encrypted</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Status -->
            <div class="card status-card">
                <div class="card-header">
                    <h3><i class="fas fa-chart-line"></i> Quick Stats</h3>
                </div>
                <div class="card-body">
                    <?php
                    $pending = $this->db->where('status', 'pending')->count_all_results('deposit_requests');
                    $today_deposits = $this->db->where('DATE(created_at)', date('Y-m-d'))
                        ->where('status', 'approved')
                        ->count_all_results('deposit_requests');
                    ?>
                    <div class="stat-item">
                        <div class="stat-icon pending">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-details">
                            <span class="stat-value"><?php echo $pending; ?></span>
                            <span class="stat-label">Pending Requests</span>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon success">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <div class="stat-details">
                            <span class="stat-value"><?php echo $today_deposits; ?></span>
                            <span class="stat-label">Today's Deposits</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Help Card -->
            <div class="card help-card">
                <div class="card-header">
                    <h3><i class="fas fa-question-circle"></i> Need Help?</h3>
                </div>
                <div class="card-body">
                    <p>Having trouble setting up payment methods?</p>
                    <a href="#" class="btn btn-help">
                        <i class="fas fa-book"></i> View Documentation
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .settings-container {
        padding: 20px;
        max-width: 1400px;
        margin: 0 auto;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #e9ecef;
    }

    .page-header h2 {
        margin: 0;
        color: #2c3e50;
        font-size: 2rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .subtitle {
        color: #6c757d;
        margin: 5px 0 0 0;
        font-size: 0.95rem;
    }

    .header-actions .btn-outline {
        background: white;
        border: 2px solid #667eea;
        color: #667eea;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .header-actions .btn-outline:hover {
        background: #667eea;
        color: white;
    }

    /* Alerts */
    .alert {
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        position: relative;
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border-left: 4px solid #28a745;
    }

    .alert-danger {
        background: #f8d7da;
        color: #721c24;
        border-left: 4px solid #dc3545;
    }

    .alert-close {
        position: absolute;
        right: 15px;
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        opacity: 0.5;
        transition: opacity 0.3s;
    }

    .alert-close:hover {
        opacity: 1;
    }

    /* Grid Layout */
    .settings-grid {
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 30px;
    }

    /* Cards */
    .card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px 25px;
    }

    .card-header h3 {
        margin: 0;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-body {
        padding: 30px;
    }

    /* Form Sections */
    .form-section {
        margin-bottom: 35px;
        padding-bottom: 25px;
        border-bottom: 1px solid #e9ecef;
    }

    .form-section:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }

    .form-section h4 {
        color: #495057;
        margin: 0 0 20px 0;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Form Groups */
    .form-group {
        margin-bottom: 20px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    label {
        display: block;
        margin-bottom: 8px;
        color: #495057;
        font-weight: 500;
        font-size: 0.95rem;
    }

    .required {
        color: #dc3545;
    }

    .input-group {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-icon {
        position: absolute;
        left: 15px;
        color: #667eea;
        z-index: 1;
    }

    input[type="text"],
    input[type="file"] {
        width: 100%;
        padding: 12px 15px 12px 45px;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        font-size: 0.95rem;
        transition: all 0.3s;
    }

    input[type="text"]:focus {
        border-color: #667eea;
        outline: none;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-hint {
        display: block;
        margin-top: 5px;
        color: #6c757d;
        font-size: 0.85rem;
    }

    /* File Upload */
    .file-upload-wrapper {
        position: relative;
    }

    .file-upload-wrapper input[type="file"] {
        position: absolute;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }

    .file-upload-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 30px;
        border: 2px dashed #667eea;
        border-radius: 8px;
        background: #f8f9ff;
        cursor: pointer;
        transition: all 0.3s;
        margin: 0;
    }

    .file-upload-label:hover {
        background: #f0f2ff;
        border-color: #5568d3;
    }

    .file-upload-label i {
        font-size: 2.5rem;
        color: #667eea;
        margin-bottom: 10px;
    }

    .file-upload-label span {
        color: #495057;
        font-weight: 500;
    }

    .file-upload-label small {
        color: #6c757d;
        font-size: 0.85rem;
        margin-top: 5px;
    }

    /* QR Code Display */
    .current-qr {
        margin-top: 20px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .qr-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .qr-header span {
        font-weight: 500;
        color: #495057;
    }

    .btn-delete-qr {
        color: #dc3545;
        text-decoration: none;
        font-size: 0.9rem;
        transition: all 0.3s;
    }

    .btn-delete-qr:hover {
        color: #c82333;
    }

    .qr-preview {
        text-align: center;
        padding: 20px;
        background: white;
        border-radius: 8px;
    }

    .qr-preview img {
        max-width: 250px;
        border: 2px solid #dee2e6;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .qr-preview-new {
        margin-top: 20px;
        padding: 20px;
        background: #e7f3ff;
        border-radius: 8px;
        text-align: center;
    }

    .qr-preview-new p {
        margin: 0 0 15px 0;
        color: #495057;
        font-weight: 500;
    }

    .qr-preview-new img {
        max-width: 250px;
        border: 2px solid #667eea;
        border-radius: 8px;
    }

    /* Buttons */
    .form-actions {
        display: flex;
        gap: 15px;
        margin-top: 30px;
    }

    .btn {
        padding: 12px 30px;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }

    .btn-secondary {
        background: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background: #5a6268;
    }

    /* Info Panel */
    .info-panel {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .info-card .card-body {
        padding: 20px;
    }

    .info-item {
        display: flex;
        gap: 15px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
        margin-bottom: 10px;
    }

    .info-item:last-child {
        margin-bottom: 0;
    }

    .info-item i {
        font-size: 1.5rem;
        margin-top: 2px;
    }

    .info-item strong {
        display: block;
        color: #2c3e50;
        margin-bottom: 3px;
    }

    .info-item p {
        margin: 0;
        font-size: 0.9rem;
        color: #6c757d;
    }

    /* Status Card */
    .stat-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
        margin-bottom: 15px;
    }

    .stat-item:last-child {
        margin-bottom: 0;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .stat-icon.pending {
        background: #fff3cd;
        color: #856404;
    }

    .stat-icon.success {
        background: #d4edda;
        color: #155724;
    }

    .stat-details {
        display: flex;
        flex-direction: column;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2c3e50;
    }

    .stat-label {
        font-size: 0.85rem;
        color: #6c757d;
    }

    /* Help Card */
    .help-card .card-body {
        text-align: center;
    }

    .help-card p {
        color: #6c757d;
        margin-bottom: 15px;
    }

    .btn-help {
        background: #17a2b8;
        color: white;
        text-decoration: none;
        padding: 10px 20px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
    }

    .btn-help:hover {
        background: #138496;
        transform: translateY(-2px);
    }

    /* Responsive */
    @media (max-width: 992px) {
        .settings-grid {
            grid-template-columns: 1fr;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
    }

    @media (max-width: 576px) {
        .settings-container {
            padding: 10px;
        }

        .card-body {
            padding: 20px;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<script>
    // Image Preview
    function previewImage(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('qrPreview');
        const previewImg = document.getElementById('previewImg');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    }

    // Reset Preview
    function resetPreview() {
        document.getElementById('qrPreview').style.display = 'none';
        document.getElementById('previewImg').src = '';
    }

    // Auto-hide alerts after 5 seconds
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        });
    }, 5000);

    // Form validation
    document.getElementById('settingsForm').addEventListener('submit', function(e) {
        const upiId = document.getElementById('upi_id').value.trim();

        if (!upiId) {
            e.preventDefault();
            alert('UPI ID is required!');
            document.getElementById('upi_id').focus();
            return false;
        }
    });
</script>