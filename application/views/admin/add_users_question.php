<?php if ($this->session->flashdata('error')): ?>
    <div class="message error" style="display:none;"><?php echo $this->session->flashdata('error'); ?></div>
<?php endif; ?>
<?php if ($this->session->flashdata('success')): ?>
    <div class="message success" style="display:none;"><?php echo $this->session->flashdata('success'); ?></div>
<?php endif; ?>

<style>
    .au-page {
        display: grid;
        gap: 20px;
        max-width: 1120px;
    }

    .au-hero {
        display: grid;
        grid-template-columns: minmax(0, 1.2fr) minmax(280px, .8fr);
        gap: 18px;
    }

    .au-card,
    .au-form-card,
    .au-side-card {
        background: #fff;
        border: 1px solid #e5e9f0;
        border-radius: 22px;
        box-shadow: 0 14px 34px rgba(15, 23, 42, 0.06);
    }

    .au-card {
        padding: 28px;
        position: relative;
        overflow: hidden;
    }

    .au-card::before {
        content: "";
        position: absolute;
        inset: 0 0 auto 0;
        height: 5px;
        background: linear-gradient(90deg, #2563eb, #1d4ed8, #0f172a);
    }

    .au-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 7px 12px;
        border-radius: 999px;
        background: #eef2ff;
        color: #1d4ed8;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .08em;
        margin-bottom: 14px;
    }

    .au-title {
        font-size: 34px;
        line-height: 1.1;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 10px;
    }

    .au-subtitle {
        font-size: 15px;
        color: #64748b;
        max-width: 640px;
        line-height: 1.7;
    }

    .au-stat-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 14px;
        margin-top: 22px;
    }

    .au-stat {
        padding: 16px 18px;
        border-radius: 18px;
        background: linear-gradient(180deg, #f8fbff, #eef4ff);
        border: 1px solid #dbe6ff;
    }

    .au-stat-label {
        display: block;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: #64748b;
        margin-bottom: 6px;
    }

    .au-stat-value {
        font-size: 26px;
        font-weight: 800;
        color: #1e3a8a;
    }

    .au-stat-note {
        margin-top: 4px;
        font-size: 12px;
        color: #64748b;
    }

    .au-side-card {
        padding: 24px;
        display: grid;
        gap: 16px;
        align-content: start;
        background: linear-gradient(180deg, #0f172a, #172554);
        color: #fff;
    }

    .au-side-card h3 {
        font-size: 20px;
        font-weight: 800;
    }

    .au-side-card p {
        color: rgba(255, 255, 255, 0.76);
        font-size: 14px;
    }

    .au-checks {
        display: grid;
        gap: 12px;
    }

    .au-check {
        display: flex;
        gap: 10px;
        align-items: flex-start;
        padding: 14px;
        border-radius: 16px;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.08);
    }

    .au-check i {
        color: #93c5fd;
        margin-top: 2px;
    }

    .au-check strong {
        display: block;
        font-size: 14px;
        margin-bottom: 3px;
    }

    .au-check span {
        display: block;
        font-size: 12px;
        color: rgba(255, 255, 255, 0.72);
        line-height: 1.55;
    }

    .au-form-card {
        padding: 28px;
    }

    .au-form-head {
        display: flex;
        justify-content: space-between;
        gap: 14px;
        align-items: flex-start;
        margin-bottom: 22px;
    }

    .au-form-head h2 {
        font-size: 28px;
        font-weight: 800;
        color: #0f172a;
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .au-form-head p {
        margin-top: 6px;
        color: #64748b;
        font-size: 14px;
    }

    .au-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
        border-radius: 999px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #334155;
        font-size: 13px;
        font-weight: 700;
        white-space: nowrap;
    }

    .au-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 18px;
    }

    .au-field {
        display: grid;
        gap: 8px;
    }

    .au-field.full {
        grid-column: 1 / -1;
    }

    .au-label-row {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        align-items: center;
    }

    .au-label-row label {
        margin: 0;
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
    }

    .au-help {
        font-size: 12px;
        color: #64748b;
    }

    .au-input-wrap {
        position: relative;
    }

    .au-input-wrap i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        pointer-events: none;
    }

    .au-input,
    .au-select {
        width: 100%;
        min-height: 56px;
        padding: 14px 16px 14px 46px;
        border-radius: 16px;
        border: 1px solid #dbe4f0;
        background: #fbfdff;
        color: #0f172a;
        font-size: 15px;
        font-weight: 600;
        transition: border-color .18s ease, box-shadow .18s ease, transform .18s ease;
    }

    .au-input:focus,
    .au-select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
        transform: translateY(-1px);
    }

    .au-status {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        min-height: 22px;
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
    }

    .au-status.is-loading {
        color: #1d4ed8;
    }

    .au-status.is-success {
        color: #15803d;
    }

    .au-status.is-error {
        color: #b91c1c;
    }

    .au-preview {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
        margin-top: 22px;
        padding: 18px;
        border-radius: 18px;
        background: linear-gradient(180deg, #f8fafc, #f1f5f9);
        border: 1px solid #e2e8f0;
    }

    .au-preview-item {
        padding: 12px 14px;
        border-radius: 14px;
        background: #fff;
        border: 1px solid #e5e7eb;
    }

    .au-preview-item span {
        display: block;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: #94a3b8;
        margin-bottom: 6px;
    }

    .au-preview-item strong {
        display: block;
        font-size: 17px;
        color: #0f172a;
        line-height: 1.35;
    }

    .au-actions {
        display: flex;
        justify-content: space-between;
        gap: 14px;
        align-items: center;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid #e5e9f0;
    }

    .au-actions p {
        font-size: 13px;
        color: #64748b;
    }

    .au-submit {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        min-width: 220px;
        min-height: 54px;
        padding: 14px 24px;
        border: none;
        border-radius: 16px;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #fff;
        font-size: 16px;
        font-weight: 800;
        cursor: pointer;
        box-shadow: 0 18px 32px rgba(37, 99, 235, 0.22);
        transition: transform .18s ease, box-shadow .18s ease, opacity .18s ease;
    }

    .au-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 22px 36px rgba(37, 99, 235, 0.26);
    }

    .au-submit:disabled {
        opacity: .6;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    @media (max-width: 980px) {
        .au-hero {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 760px) {
        .au-title {
            font-size: 28px;
        }

        .au-grid,
        .au-preview,
        .au-stat-grid {
            grid-template-columns: 1fr;
        }

        .au-form-head,
        .au-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .au-submit {
            width: 100%;
        }
    }
</style>

<div class="au-page">
    <section class="au-hero">
        <div class="au-card">
            <div class="au-eyebrow">
                <i class="fa-solid fa-user-plus"></i>
                Admin Tool
            </div>
            <h1 class="au-title">Add virtual users to a live question</h1>
            <p class="au-subtitle">Pick a category, load its questions instantly, then add a user count to boost the joined-user display for that specific market. This updates the admin extra users count only for the chosen question.</p>

            <div class="au-stat-grid">
                <div class="au-stat">
                    <span class="au-stat-label">Categories</span>
                    <strong class="au-stat-value"><?php echo count($categories); ?></strong>
                    <div class="au-stat-note">Available for quick selection</div>
                </div>
                <div class="au-stat">
                    <span class="au-stat-label">Action</span>
                    <strong class="au-stat-value">+ Users</strong>
                    <div class="au-stat-note">Applied directly to one question</div>
                </div>
            </div>
        </div>

        <aside class="au-side-card">
            <h3>Before you submit</h3>
            <p>Use this page when you want to increase the displayed joined-user count for a market without creating real answer records.</p>
            <div class="au-checks">
                <div class="au-check">
                    <i class="fa-solid fa-check-circle"></i>
                    <div>
                        <strong>Select the correct category first</strong>
                        <span>The question dropdown loads from the selected category only.</span>
                    </div>
                </div>
                <div class="au-check">
                    <i class="fa-solid fa-check-circle"></i>
                    <div>
                        <strong>Choose the exact question</strong>
                        <span>The count will be added only to the question you submit here.</span>
                    </div>
                </div>
                <div class="au-check">
                    <i class="fa-solid fa-check-circle"></i>
                    <div>
                        <strong>Enter a positive number</strong>
                        <span>Zero or empty values are blocked and shown with SweetAlert.</span>
                    </div>
                </div>
            </div>
        </aside>
    </section>

    <section class="au-form-card">
        <div class="au-form-head">
            <div>
                <h2><i class="fa-solid fa-users-medical"></i> Add Users to Question</h2>
                <p>Everything on this page now uses SweetAlert for success, error, and validation feedback.</p>
            </div>
            <div class="au-badge">
                <i class="fa-solid fa-bolt"></i>
                Fast category-to-question loading
            </div>
        </div>

        <form method="post" action="<?php echo site_url('admin/users/save_users_to_question'); ?>" id="addUsersForm">
            <div class="au-grid">
                <div class="au-field">
                    <div class="au-label-row">
                        <label for="categorySelect">Select Category</label>
                        <span class="au-help">Required</span>
                    </div>
                    <div class="au-input-wrap">
                        <i class="fa-solid fa-layer-group"></i>
                        <select id="categorySelect" class="au-select" required>
                            <option value="">Choose a category</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo (int) $cat->id; ?>">
                                    <?php echo html_escape($cat->name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="au-field">
                    <div class="au-label-row">
                        <label for="questionSelect">Select Question</label>
                        <span class="au-help">Loads after category</span>
                    </div>
                    <div class="au-input-wrap">
                        <i class="fa-solid fa-circle-question"></i>
                        <select name="question_id" id="questionSelect" class="au-select" required>
                            <option value="">Choose a question</option>
                        </select>
                    </div>
                    <div class="au-status" id="questionStatus">Select a category to load questions.</div>
                </div>

                <div class="au-field full">
                    <div class="au-label-row">
                        <label for="userCount">Add Users Count</label>
                        <span class="au-help">Numbers only</span>
                    </div>
                    <div class="au-input-wrap">
                        <i class="fa-solid fa-hashtag"></i>
                        <input type="number" id="userCount" name="user_count" min="1" step="1" class="au-input" required placeholder="Enter number of users to add, for example 10">
                    </div>
                </div>
            </div>

            <div class="au-preview">
                <div class="au-preview-item">
                    <span>Selected category</span>
                    <strong id="previewCategory">Not selected yet</strong>
                </div>
                <div class="au-preview-item">
                    <span>Selected question</span>
                    <strong id="previewQuestion">Choose a question</strong>
                </div>
                <div class="au-preview-item">
                    <span>Users to add</span>
                    <strong id="previewCount">0</strong>
                </div>
            </div>

            <div class="au-actions">
                <p>The update is applied immediately after confirmation and success is shown with SweetAlert.</p>
                <button type="submit" class="au-submit" id="submitBtn">
                    <i class="fa-solid fa-plus"></i>
                    Add Users
                </button>
            </div>
        </form>
    </section>
</div>

<script>
    (function() {
        'use strict';

        var form = document.getElementById('addUsersForm');
        var categorySelect = document.getElementById('categorySelect');
        var questionSelect = document.getElementById('questionSelect');
        var userCount = document.getElementById('userCount');
        var questionStatus = document.getElementById('questionStatus');
        var previewCategory = document.getElementById('previewCategory');
        var previewQuestion = document.getElementById('previewQuestion');
        var previewCount = document.getElementById('previewCount');
        var submitBtn = document.getElementById('submitBtn');

        function setStatus(text, state) {
            questionStatus.textContent = text;
            questionStatus.className = 'au-status' + (state ? ' is-' + state : '');
        }

        function resetQuestions(placeholder) {
            questionSelect.innerHTML = '<option value="">' + placeholder + '</option>';
        }

        function updatePreview() {
            var categoryOption = categorySelect.options[categorySelect.selectedIndex];
            var questionOption = questionSelect.options[questionSelect.selectedIndex];
            previewCategory.textContent = categorySelect.value ? categoryOption.textContent.trim() : 'Not selected yet';
            previewQuestion.textContent = questionSelect.value ? questionOption.textContent.trim() : 'Choose a question';
            previewCount.textContent = userCount.value && Number(userCount.value) > 0 ? String(parseInt(userCount.value, 10)) : '0';
        }

        categorySelect.addEventListener('change', function() {
            var catId = this.value;
            updatePreview();

            if (!catId) {
                resetQuestions('Choose a question');
                setStatus('Select a category to load questions.', '');
                updatePreview();
                return;
            }

            resetQuestions('Loading questions...');
            setStatus('Loading questions for the selected category...', 'loading');

            fetch('<?php echo site_url('admin/questions/get_questions_by_category/'); ?>' + catId, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(function(res) {
                    if (!res.ok) {
                        throw new Error('Unable to fetch questions.');
                    }
                    return res.json();
                })
                .then(function(data) {
                    resetQuestions('Choose a question');

                    if (!Array.isArray(data) || data.length === 0) {
                        setStatus('No questions found in this category.', 'error');
                        return;
                    }

                    data.forEach(function(q) {
                        var option = document.createElement('option');
                        option.value = q.id;
                        option.textContent = q.question;
                        questionSelect.appendChild(option);
                    });

                    setStatus(data.length + ' question' + (data.length !== 1 ? 's' : '') + ' loaded successfully.', 'success');
                })
                .catch(function() {
                    resetQuestions('Unable to load questions');
                    setStatus('Could not load questions. Please try again.', 'error');

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            text: 'Unable to load questions for this category. Please try again.',
                            confirmButtonColor: '#2563eb'
                        });
                    }
                })
                .finally(function() {
                    updatePreview();
                });
        });

        questionSelect.addEventListener('change', updatePreview);
        userCount.addEventListener('input', updatePreview);

        form.addEventListener('submit', function(event) {
            var count = parseInt(userCount.value, 10) || 0;

            if (!categorySelect.value) {
                event.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    text: 'Please select a category first.',
                    confirmButtonColor: '#2563eb'
                });
                return;
            }

            if (!questionSelect.value) {
                event.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    text: 'Please select a question before submitting.',
                    confirmButtonColor: '#2563eb'
                });
                return;
            }

            if (count <= 0) {
                event.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    text: 'Please enter a user count greater than zero.',
                    confirmButtonColor: '#2563eb'
                });
                return;
            }

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Adding Users...';
        });

        updatePreview();
    }());
</script>
