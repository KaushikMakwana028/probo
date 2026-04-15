<div class="wallet-container">
    <!-- Flash Messages -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <span><?php echo $this->session->flashdata('success'); ?></span>
            <button class="alert-close" onclick="this.parentElement.remove()">×</button>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <span><?php echo $this->session->flashdata('error'); ?></span>
            <button class="alert-close" onclick="this.parentElement.remove()">×</button>
        </div>
    <?php endif; ?>

    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <h2><i class="fas fa-wallet"></i> Add Balance to Wallet</h2>
            <p class="subtitle">Quick and secure deposit process</p>
        </div>
        <div class="balance-display">
            <span class="balance-label">Current Balance</span>
            <span class="balance-amount">₹<?php echo number_format($user->wallet_balance ?? 0, 2); ?></span>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="content-grid">
        <!-- Payment Information Card -->
        <div class="card payment-info-card">
            <div class="card-header">
                <h3><i class="fas fa-info-circle"></i> Payment Information</h3>
            </div>
            <div class="card-body">

                <!-- QR Code Section -->
                <?php if (!empty($settings->qr_image)): ?>
                    <div class="qr-section">
                        <h4><i class="fas fa-qrcode"></i> Scan & Pay</h4>
                        <div class="qr-container">
                            <img src="<?php echo base_url('uploads/qr/' . $settings->qr_image); ?>" alt="QR Code">
                            <p class="qr-hint">Scan this QR code with any UPI app</p>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- UPI Details -->
                <div class="payment-method">
                    <div class="method-header">
                        <i class="fab fa-google-pay"></i>
                        <h4>UPI Payment</h4>
                    </div>
                    <div class="method-details">
                        <div class="detail-item">
                            <span class="detail-label">UPI ID:</span>
                            <div class="detail-value-wrapper">
                                <span class="detail-value" id="upiId"><?php echo htmlspecialchars($settings->upi_id); ?></span>
                                <button class="copy-btn" onclick="copyToClipboard('upiId')" title="Copy UPI ID">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bank Details -->
                <div class="payment-method">
                    <div class="method-header">
                        <i class="fas fa-university"></i>
                        <h4>Bank Transfer</h4>
                    </div>
                    <div class="method-details">
                        <div class="detail-item">
                            <span class="detail-label">Bank Name:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($settings->bank_name); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Account Number:</span>
                            <div class="detail-value-wrapper">
                                <span class="detail-value" id="accountNumber"><?php echo htmlspecialchars($settings->account_number); ?></span>
                                <button class="copy-btn" onclick="copyToClipboard('accountNumber')" title="Copy Account Number">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">IFSC Code:</span>
                            <div class="detail-value-wrapper">
                                <span class="detail-value" id="ifscCode"><?php echo htmlspecialchars($settings->ifsc); ?></span>
                                <button class="copy-btn" onclick="copyToClipboard('ifscCode')" title="Copy IFSC Code">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Apps -->
                <div class="payment-apps">
                    <p>Accepted Payment Methods:</p>
                    <div class="app-icons">
                        <span class="app-icon" title="Google Pay"><i class="fab fa-google-pay"></i></span>
                        <span class="app-icon" title="PhonePe"><img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='%235f259f' d='M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z'/%3E%3C/svg%3E" alt="PhonePe"></span>
                        <span class="app-icon" title="Paytm"><i class="fas fa-mobile-alt"></i></span>
                        <span class="app-icon" title="Bank Transfer"><i class="fas fa-university"></i></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Deposit Request Form -->
        <div class="card deposit-form-card">
            <div class="card-header">
                <h3><i class="fas fa-file-invoice-dollar"></i> Submit Deposit Request</h3>
            </div>
            <div class="card-body">

                <!-- Instructions -->
                <div class="instructions">
                    <h4><i class="fas fa-list-ol"></i> How to Deposit?</h4>
                    <ol>
                        <li>Make payment using UPI or Bank Transfer</li>
                        <li>Note down the Transaction ID/UTR Number</li>
                        <li>Enter amount and Transaction ID below</li>
                        <li>Submit the request for verification</li>
                        <li>Balance will be credited after approval</li>
                    </ol>
                </div>

                <!-- Deposit Form -->
                <form method="post" action="<?php echo site_url('wallet/request_deposit'); ?>" id="depositForm">

                    <div class="form-group">
                        <label for="amount">
                            <i class="fas fa-rupee-sign"></i> Enter Amount <span class="required">*</span>
                        </label>
                        <div class="input-wrapper">
                            <span class="input-prefix">₹</span>
                            <input
                                type="number"
                                name="amount"
                                id="amount"
                                placeholder="Enter amount to deposit"
                                min="100"
                                step="1"
                                required
                                oninput="updateAmountPreview()">
                        </div>
                        <small class="form-hint">Minimum deposit: ₹100</small>
                    </div>

                    <div class="amount-suggestions">
                        <span class="suggestion-label">Quick Amount:</span>
                        <button type="button" class="amount-btn" onclick="setAmount(500)">₹500</button>
                        <button type="button" class="amount-btn" onclick="setAmount(1000)">₹1000</button>
                        <button type="button" class="amount-btn" onclick="setAmount(2000)">₹2000</button>
                        <button type="button" class="amount-btn" onclick="setAmount(5000)">₹5000</button>
                    </div>

                    <div class="form-group">
                        <label for="txn_id">
                            <i class="fas fa-receipt"></i> Transaction ID / UTR Number <span class="required">*</span>
                        </label>
                        <input
                            type="text"
                            name="txn_id"
                            id="txn_id"
                            placeholder="Enter 12-digit Transaction ID"
                            required
                            maxlength="50">
                        <small class="form-hint">Enter the Transaction ID from your payment app</small>
                    </div>

                    <!-- Preview Box -->
                    <div class="deposit-preview" id="depositPreview" style="display: none;">
                        <h4><i class="fas fa-eye"></i> Request Preview</h4>
                        <div class="preview-item">
                            <span>Deposit Amount:</span>
                            <strong id="previewAmount">₹<?php echo number_format((float)$user->wallet_balance, 2); ?></strong>
                        </div>
                        <div class="preview-item">
                            <span>Processing Fee:</span>
                            <strong class="text-success">Free</strong>
                        </div>
                        <div class="preview-item total">
                            <span>Total Credit:</span>
                            <strong id="previewTotal">₹0.00</strong>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary btn-submit">
                        <i class="fas fa-paper-plane"></i> Submit Deposit Request
                    </button>

                    <!-- Security Note -->
                    <div class="security-note">
                        <i class="fas fa-shield-alt"></i>
                        <p>Your transaction is secure and encrypted. We never store your payment details.</p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Recent Requests -->
    <div class="card recent-requests-card">
        <div class="card-header">
            <h3><i class="fas fa-history"></i> Recent Deposit Requests</h3>
            <a href="<?php echo site_url('wallet'); ?>" class="view-all-link">
                View All <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        <div class="card-body">
            <?php
            $recent_requests = $this->db
                ->where('user_id', $this->session->userdata('user_id'))
                ->order_by('created_at', 'DESC')
                ->limit(3)
                ->get('deposit_requests')
                ->result();
            ?>

            <?php if (!empty($recent_requests)): ?>
                <div class="requests-list">
                    <?php foreach ($recent_requests as $req): ?>
                        <div class="request-item">
                            <div class="request-info">
                                <span class="request-amount">₹<?php echo number_format($req->amount, 2); ?></span>
                                <span class="request-date">
                                    <i class="fas fa-clock"></i>
                                    <?php echo date('d M Y, h:i A', strtotime($req->created_at)); ?>
                                </span>
                            </div>
                            <div class="request-status">
                                <?php if ($req->status == 'pending'): ?>
                                    <span class="badge badge-warning">
                                        <i class="fas fa-hourglass-half"></i> Pending
                                    </span>
                                <?php elseif ($req->status == 'approved'): ?>
                                    <span class="badge badge-success">
                                        <i class="fas fa-check-circle"></i> Approved
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-danger">
                                        <i class="fas fa-times-circle"></i> Rejected
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>No deposit requests yet</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .wallet-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 20px;
    }

    /* Page Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding: 25px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        color: white;
    }

    .header-content h2 {
        margin: 0 0 5px 0;
        font-size: 1.8rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .subtitle {
        margin: 0;
        opacity: 0.9;
        font-size: 0.95rem;
    }

    .balance-display {
        text-align: right;
        background: rgba(255, 255, 255, 0.2);
        padding: 15px 25px;
        border-radius: 10px;
    }

    .balance-label {
        display: block;
        font-size: 0.85rem;
        opacity: 0.9;
        margin-bottom: 5px;
    }

    .balance-amount {
        display: block;
        font-size: 2rem;
        font-weight: 700;
    }

    /* Alerts */
    .alert {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 15px 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        position: relative;
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
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

    .alert i {
        font-size: 1.2rem;
    }

    .alert-close {
        position: absolute;
        right: 15px;
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        opacity: 0.5;
    }

    .alert-close:hover {
        opacity: 1;
    }

    /* Content Grid */
    .content-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
        margin-bottom: 25px;
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
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-header h3 {
        margin: 0;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .view-all-link {
        color: white;
        text-decoration: none;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 5px;
        transition: gap 0.3s;
    }

    .view-all-link:hover {
        gap: 10px;
    }

    .card-body {
        padding: 25px;
    }

    /* QR Section */
    .qr-section {
        text-align: center;
        margin-bottom: 25px;
        padding-bottom: 25px;
        border-bottom: 2px solid #f0f0f0;
    }

    .qr-section h4 {
        color: #495057;
        margin: 0 0 15px 0;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .qr-container {
        display: inline-block;
        padding: 20px;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        border-radius: 15px;
    }

    .qr-container img {
        max-width: 220px;
        border: 3px solid white;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .qr-hint {
        margin: 10px 0 0 0;
        color: #6c757d;
        font-size: 0.9rem;
    }

    /* Payment Method */
    .payment-method {
        margin-bottom: 20px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 10px;
        border-left: 4px solid #667eea;
    }

    .method-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 15px;
    }

    .method-header i {
        font-size: 1.5rem;
        color: #667eea;
    }

    .method-header h4 {
        margin: 0;
        color: #2c3e50;
        font-size: 1.1rem;
    }

    .method-details {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .detail-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px;
        background: white;
        border-radius: 8px;
    }

    .detail-label {
        color: #6c757d;
        font-size: 0.9rem;
    }

    .detail-value-wrapper {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .detail-value {
        font-weight: 600;
        color: #2c3e50;
        font-family: monospace;
        font-size: 1rem;
    }

    .copy-btn {
        background: #667eea;
        color: white;
        border: none;
        padding: 6px 10px;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .copy-btn:hover {
        background: #5568d3;
        transform: scale(1.1);
    }

    /* Payment Apps */
    .payment-apps {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 2px solid #f0f0f0;
        text-align: center;
    }

    .payment-apps p {
        color: #6c757d;
        margin-bottom: 15px;
        font-size: 0.9rem;
    }

    .app-icons {
        display: flex;
        justify-content: center;
        gap: 15px;
    }

    .app-icon {
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        font-size: 1.5rem;
        color: #667eea;
        transition: transform 0.3s;
    }

    .app-icon:hover {
        transform: translateY(-5px);
    }

    .app-icon img {
        width: 30px;
        height: 30px;
    }

    /* Instructions */
    .instructions {
        background: #e7f3ff;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 25px;
        border-left: 4px solid #0066cc;
    }

    .instructions h4 {
        color: #2c3e50;
        margin: 0 0 15px 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .instructions ol {
        margin: 0;
        padding-left: 20px;
        color: #495057;
    }

    .instructions li {
        margin-bottom: 8px;
        line-height: 1.6;
    }

    /* Form Groups */
    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: #495057;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .required {
        color: #dc3545;
    }

    .input-wrapper {
        position: relative;
    }

    .input-prefix {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #667eea;
        font-weight: 600;
        font-size: 1.1rem;
    }

    input[type="number"],
    input[type="text"] {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s;
    }

    input[type="number"] {
        padding-left: 35px;
    }

    input:focus {
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

    /* Amount Suggestions */
    .amount-suggestions {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .suggestion-label {
        color: #6c757d;
        font-size: 0.9rem;
    }

    .amount-btn {
        padding: 8px 15px;
        background: white;
        border: 2px solid #667eea;
        color: #667eea;
        border-radius: 20px;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.3s;
    }

    .amount-btn:hover {
        background: #667eea;
        color: white;
        transform: translateY(-2px);
    }

    /* Deposit Preview */
    .deposit-preview {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .deposit-preview h4 {
        margin: 0 0 15px 0;
        color: #2c3e50;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .preview-item {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }

    .preview-item.total {
        border-bottom: none;
        border-top: 2px solid #667eea;
        padding-top: 15px;
        margin-top: 5px;
        font-size: 1.1rem;
    }

    /* Buttons */
    .btn {
        padding: 14px 30px;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
    }

    /* Security Note */
    .security-note {
        margin-top: 20px;
        padding: 15px;
        background: #e8f5e9;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 10px;
        border-left: 4px solid #28a745;
    }

    .security-note i {
        color: #28a745;
        font-size: 1.3rem;
    }

    .security-note p {
        margin: 0;
        color: #155724;
        font-size: 0.9rem;
    }

    /* Recent Requests */
    .requests-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .request-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
        border-left: 4px solid #667eea;
    }

    .request-info {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .request-amount {
        font-size: 1.2rem;
        font-weight: 700;
        color: #2c3e50;
    }

    .request-date {
        font-size: 0.85rem;
        color: #6c757d;
    }

    .badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .badge-warning {
        background: #fff3cd;
        color: #856404;
    }

    .badge-success {
        background: #d4edda;
        color: #155724;
    }

    .badge-danger {
        background: #f8d7da;
        color: #721c24;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 40px;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 3rem;
        opacity: 0.3;
        margin-bottom: 15px;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .content-grid {
            grid-template-columns: 1fr;
        }

        .page-header {
            flex-direction: column;
            gap: 20px;
        }

        .balance-display {
            width: 100%;
            text-align: center;
        }
    }

    @media (max-width: 576px) {
        .wallet-container {
            padding: 10px;
        }

        .card-body {
            padding: 15px;
        }

        .amount-suggestions {
            flex-direction: column;
            align-items: stretch;
        }

        .amount-btn {
            width: 100%;
        }
    }
</style>

<script>
    // Copy to Clipboard
    function copyToClipboard(elementId) {
        const element = document.getElementById(elementId);
        const text = element.textContent;

        navigator.clipboard.writeText(text).then(() => {
            // Show success message
            const btn = event.target.closest('.copy-btn');
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check"></i>';
            btn.style.background = '#28a745';

            setTimeout(() => {
                btn.innerHTML = originalHTML;
                btn.style.background = '#667eea';
            }, 2000);
        });
    }

    // Set Amount from Quick Buttons
    function setAmount(amount) {
        document.getElementById('amount').value = amount;
        updateAmountPreview();
    }

    // Update Amount Preview
    function updateAmountPreview() {
        const amount = parseFloat(document.getElementById('amount').value) || 0;
        const preview = document.getElementById('depositPreview');

        if (amount > 0) {
            preview.style.display = 'block';
            document.getElementById('previewAmount').textContent = '₹' + amount.toFixed(2);
            document.getElementById('previewTotal').textContent = '₹' + amount.toFixed(2);
        } else {
            preview.style.display = 'none';
        }
    }

    // Form Validation
    document.getElementById('depositForm').addEventListener('submit', function(e) {
        const amount = parseFloat(document.getElementById('amount').value);
        const txnId = document.getElementById('txn_id').value.trim();

        if (amount < 100) {
            e.preventDefault();
            alert('Minimum deposit amount is ₹100');
            return false;
        }

        if (txnId.length < 6) {
            e.preventDefault();
            alert('Please enter a valid Transaction ID');
            return false;
        }
    });

    // Auto-hide alerts
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        });
    }, 5000);
</script>