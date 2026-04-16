<?php
// add_balance_view.php — Enhanced UI
?>

<link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

<style>
    :root {
        --ink: #0d1117;
        --ink-2: #24292f;
        --ink-3: #57606a;
        --ink-4: #8c959f;
        --line: #e8ecf0;
        --line-2: #d0d7de;
        --surf: #ffffff;
        --base: #f6f8fa;
        --base-2: #eef1f4;

        --blue: #2563eb;
        --blue-lt: #eff6ff;
        --blue-border: #bfdbfe;
        --blue-dk: #1d4ed8;
        --blue-text: #1e40af;

        --green: #16a34a;
        --green-lt: #f0fdf4;
        --green-border: #bbf7d0;
        --green-text: #166534;

        --red: #dc2626;
        --red-lt: #fef2f2;
        --red-border: #fecaca;
        --red-text: #991b1b;

        --amber: #d97706;
        --amber-lt: #fffbeb;
        --amber-border: #fde68a;
        --amber-text: #92400e;

        --purple: #7c3aed;
        --purple-lt: #faf5ff;
        --purple-border: #e9d5ff;
        --purple-text: #5b21b6;

        --accent: #2563eb;
        --accent-dk: #1d4ed8;

        --radius-sm: 6px;
        --radius-md: 10px;
        --radius-lg: 14px;
        --radius-xl: 18px;
        --radius-2xl: 24px;

        --f-body: 'Sora', system-ui, sans-serif;
        --f-mono: 'JetBrains Mono', monospace;

        --sh-xs: 0 1px 3px rgba(13, 17, 23, .06), 0 1px 2px rgba(13, 17, 23, .04);
        --sh-sm: 0 3px 12px rgba(13, 17, 23, .07), 0 1px 4px rgba(13, 17, 23, .04);
        --sh-md: 0 8px 28px rgba(13, 17, 23, .09), 0 3px 10px rgba(13, 17, 23, .05);
    }

    *,
    *::before,
    *::after {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    .dep-wrap {
        max-width: 1160px;
        margin: 0 auto;
        padding: 20px 16px 60px;
        font-family: var(--f-body);
        color: var(--ink);
        -webkit-font-smoothing: antialiased;
    }

    /* ── ALERTS ── */
    .dep-alert {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 14px 18px;
        border-radius: var(--radius-lg);
        margin-bottom: 18px;
        font-size: 13.5px;
        font-weight: 500;
        animation: slideDown .3s ease;
        border: 1px solid transparent;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-8px);
        }

        to {
            opacity: 1;
            transform: none;
        }
    }

    .dep-alert--ok {
        background: var(--green-lt);
        border-color: var(--green-border);
        color: var(--green-text);
    }

    .dep-alert--err {
        background: var(--red-lt);
        border-color: var(--red-border);
        color: var(--red-text);
    }

    .dep-alert__ico {
        width: 22px;
        height: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: var(--radius-sm);
        flex-shrink: 0;
        font-size: 12px;
    }

    .dep-alert--ok .dep-alert__ico {
        background: var(--green-border);
    }

    .dep-alert--err .dep-alert__ico {
        background: var(--red-border);
    }

    .dep-alert__close {
        margin-left: auto;
        background: none;
        border: none;
        cursor: pointer;
        color: inherit;
        opacity: .5;
        font-size: 18px;
        padding: 0 2px;
        border-radius: 4px;
        flex-shrink: 0;
    }

    .dep-alert__close:hover {
        opacity: 1;
    }

    /* ── HERO HEADER ── */
    .dep-hero {
        position: relative;
        border-radius: var(--radius-2xl);
        overflow: hidden;
        background: #0b0f1e;
        padding: 32px 36px;
        margin-bottom: 22px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 24px;
        flex-wrap: wrap;
    }

    .dep-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse 60% 80% at 85% 50%, rgba(37, 99, 235, .35) 0%, transparent 65%),
            radial-gradient(ellipse 40% 60% at 5% 10%, rgba(124, 58, 237, .18) 0%, transparent 60%),
            radial-gradient(ellipse 50% 50% at 50% 110%, rgba(16, 185, 129, .1) 0%, transparent 60%);
        pointer-events: none;
    }

    .dep-hero__left {
        position: relative;
        z-index: 1;
    }

    .dep-hero__eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 10.5px;
        font-weight: 600;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: rgba(255, 255, 255, .45);
        margin-bottom: 8px;
    }

    .dep-hero__eyebrow svg {
        width: 12px;
        height: 12px;
    }

    .dep-hero__title {
        font-size: clamp(20px, 3vw, 32px);
        font-weight: 700;
        color: #fff;
        letter-spacing: -.025em;
        margin-bottom: 6px;
        line-height: 1.15;
    }

    .dep-hero__sub {
        font-size: 13px;
        color: rgba(255, 255, 255, .5);
        line-height: 1.6;
    }

    .dep-balance-pill {
        position: relative;
        z-index: 1;
        padding: 20px 28px;
        border-radius: var(--radius-xl);
        background: rgba(255, 255, 255, .07);
        border: 1px solid rgba(255, 255, 255, .12);
        backdrop-filter: blur(12px);
        text-align: center;
        flex-shrink: 0;
    }

    .dep-balance-pill__label {
        display: block;
        font-size: 10px;
        font-weight: 600;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: rgba(255, 255, 255, .4);
        margin-bottom: 8px;
    }

    .dep-balance-pill__val {
        display: block;
        font-size: 34px;
        font-weight: 700;
        letter-spacing: -.03em;
        color: #fff;
        line-height: 1;
    }

    .dep-balance-pill__foot {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        margin-top: 10px;
        font-size: 11px;
        color: rgba(255, 255, 255, .3);
    }

    .dep-balance-pill__foot svg {
        width: 11px;
        height: 11px;
    }

    /* ── MAIN GRID ── */
    .dep-grid {
        display: grid;
        grid-template-columns: 5fr 7fr;
        gap: 18px;
        margin-bottom: 18px;
    }

    /* ── CARDS ── */
    .dep-card {
        background: var(--surf);
        border: 1px solid var(--line);
        border-radius: var(--radius-xl);
        overflow: hidden;
        box-shadow: var(--sh-xs);
    }

    .dep-card__head {
        padding: 16px 22px;
        border-bottom: 1px solid var(--line);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .dep-card__head-icon {
        width: 34px;
        height: 34px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .dep-card__head-icon svg {
        width: 15px;
        height: 15px;
    }

    .dep-card__head-icon--blue {
        background: var(--blue-lt);
        color: var(--blue);
        border: 1px solid var(--blue-border);
    }

    .dep-card__head-icon--purple {
        background: var(--purple-lt);
        color: var(--purple);
        border: 1px solid var(--purple-border);
    }

    .dep-card__head-icon--green {
        background: var(--green-lt);
        color: var(--green);
        border: 1px solid var(--green-border);
    }

    .dep-card__head-eyebrow {
        font-size: 10px;
        font-weight: 600;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: var(--ink-4);
        margin-bottom: 1px;
    }

    .dep-card__head-title {
        font-size: 14.5px;
        font-weight: 700;
        color: var(--ink);
        letter-spacing: -.01em;
    }

    .dep-card__body {
        padding: 22px;
    }

    /* ── QR SECTION ── */
    .dep-qr {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 20px;
        background: var(--base);
        border-radius: var(--radius-lg);
        margin-bottom: 18px;
        border: 1px solid var(--line);
    }

    .dep-qr img {
        max-width: 180px;
        border-radius: var(--radius-md);
        border: 3px solid var(--surf);
        box-shadow: var(--sh-sm);
    }

    .dep-qr__label {
        margin-top: 10px;
        font-size: 11.5px;
        color: var(--ink-4);
        font-weight: 500;
    }

    /* ── PAYMENT METHODS ── */
    .dep-method {
        margin-bottom: 14px;
        padding: 16px;
        background: var(--base);
        border: 1px solid var(--line);
        border-radius: var(--radius-lg);
        border-left: 3px solid var(--accent);
    }

    .dep-method:last-child {
        margin-bottom: 0;
    }

    .dep-method__head {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
    }

    .dep-method__head svg {
        width: 16px;
        height: 16px;
        color: var(--accent);
    }

    .dep-method__head-title {
        font-size: 13px;
        font-weight: 700;
        color: var(--ink-2);
    }

    .dep-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 10px;
        background: var(--surf);
        border-radius: var(--radius-md);
        border: 1px solid var(--line);
        margin-bottom: 6px;
    }

    .dep-row:last-child {
        margin-bottom: 0;
    }

    .dep-row__label {
        font-size: 11.5px;
        color: var(--ink-4);
        font-weight: 500;
    }

    .dep-row__right {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .dep-row__val {
        font-family: var(--f-mono);
        font-size: 12.5px;
        font-weight: 500;
        color: var(--ink-2);
    }

    .dep-copy {
        width: 26px;
        height: 26px;
        border-radius: var(--radius-sm);
        background: var(--blue-lt);
        color: var(--blue);
        border: 1px solid var(--blue-border);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all .15s;
        flex-shrink: 0;
    }

    .dep-copy svg {
        width: 11px;
        height: 11px;
    }

    .dep-copy:hover {
        background: var(--blue);
        color: #fff;
        border-color: var(--blue);
    }

    .dep-copy.copied {
        background: var(--green);
        color: #fff;
        border-color: var(--green);
    }

    /* Payment app badges */
    .dep-apps {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        margin-top: 14px;
        padding-top: 14px;
        border-top: 1px solid var(--line);
    }

    .dep-apps__label {
        font-size: 11px;
        color: var(--ink-4);
        font-weight: 600;
    }

    .dep-app-badge {
        padding: 5px 10px;
        border-radius: 999px;
        background: var(--base-2);
        border: 1px solid var(--line-2);
        font-size: 11px;
        font-weight: 600;
        color: var(--ink-3);
    }

    /* ── DEPOSIT FORM ── */
    .dep-instructions {
        background: var(--blue-lt);
        border: 1px solid var(--blue-border);
        border-radius: var(--radius-lg);
        padding: 16px 18px;
        margin-bottom: 20px;
    }

    .dep-instructions__title {
        display: flex;
        align-items: center;
        gap: 7px;
        font-size: 12.5px;
        font-weight: 700;
        color: var(--blue-text);
        margin-bottom: 10px;
    }

    .dep-instructions__title svg {
        width: 14px;
        height: 14px;
    }

    .dep-steps {
        list-style: none;
        display: flex;
        flex-direction: column;
        gap: 7px;
    }

    .dep-steps li {
        display: flex;
        align-items: flex-start;
        gap: 9px;
        font-size: 12.5px;
        color: var(--blue-text);
        line-height: 1.5;
    }

    .dep-step-num {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: var(--blue);
        color: #fff;
        font-size: 10px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-top: 1px;
    }

    /* Form fields */
    .dep-field {
        margin-bottom: 18px;
    }

    .dep-label {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 11.5px;
        font-weight: 700;
        letter-spacing: .07em;
        text-transform: uppercase;
        color: var(--ink-3);
        margin-bottom: 7px;
    }

    .dep-label svg {
        width: 12px;
        height: 12px;
    }

    .dep-label .req {
        color: var(--red);
    }

    .dep-input-wrap {
        position: relative;
    }

    .dep-input-prefix {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 14px;
        font-weight: 700;
        color: var(--accent);
        pointer-events: none;
        z-index: 1;
    }

    .dep-input {
        width: 100%;
        height: 46px;
        padding: 0 13px;
        border: 1.5px solid var(--line-2);
        border-radius: var(--radius-md);
        background: var(--base);
        color: var(--ink);
        font-family: var(--f-body);
        font-size: 14px;
        font-weight: 500;
        transition: border-color .15s, box-shadow .15s, background .15s;
        -webkit-appearance: none;
    }

    .dep-input--prefixed {
        padding-left: 30px;
    }

    .dep-input:hover {
        border-color: var(--ink-4);
        background: var(--surf);
    }

    .dep-input:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3.5px rgba(37, 99, 235, .1);
        background: var(--surf);
    }

    .dep-hint {
        font-size: 11.5px;
        color: var(--ink-4);
        margin-top: 5px;
    }

    /* Quick amounts */
    .dep-quick {
        display: flex;
        align-items: center;
        gap: 7px;
        flex-wrap: wrap;
        margin-bottom: 18px;
    }

    .dep-quick__label {
        font-size: 11px;
        color: var(--ink-4);
        font-weight: 600;
    }

    .dep-quick-btn {
        padding: 7px 14px;
        border-radius: 999px;
        border: 1.5px solid var(--line-2);
        background: var(--surf);
        color: var(--ink-2);
        font-family: var(--f-body);
        font-size: 12.5px;
        font-weight: 600;
        cursor: pointer;
        transition: all .15s;
    }

    .dep-quick-btn:hover {
        border-color: var(--accent);
        color: var(--accent);
        background: var(--blue-lt);
    }

    .dep-quick-btn.active {
        background: var(--accent);
        border-color: var(--accent);
        color: #fff;
    }

    /* File upload */
    .dep-upload {
        position: relative;
        border: 2px dashed var(--line-2);
        border-radius: var(--radius-lg);
        padding: 24px 20px;
        text-align: center;
        cursor: pointer;
        transition: all .2s;
        background: var(--base);
    }

    .dep-upload:hover {
        border-color: var(--accent);
        background: var(--blue-lt);
    }

    .dep-upload.has-file {
        border-style: solid;
        border-color: var(--green);
        background: var(--green-lt);
    }

    .dep-upload input[type="file"] {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
        width: 100%;
        height: 100%;
    }

    .dep-upload__icon {
        width: 40px;
        height: 40px;
        border-radius: var(--radius-lg);
        background: var(--base-2);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        transition: background .2s;
    }

    .dep-upload:hover .dep-upload__icon {
        background: var(--blue-border);
    }

    .dep-upload.has-file .dep-upload__icon {
        background: var(--green-border);
    }

    .dep-upload__icon svg {
        width: 18px;
        height: 18px;
        color: var(--ink-3);
    }

    .dep-upload.has-file .dep-upload__icon svg {
        color: var(--green);
    }

    .dep-upload__title {
        font-size: 13.5px;
        font-weight: 600;
        color: var(--ink-2);
        margin-bottom: 3px;
    }

    .dep-upload__sub {
        font-size: 11.5px;
        color: var(--ink-4);
    }

    .dep-upload__filename {
        display: none;
        font-size: 12px;
        font-weight: 600;
        color: var(--green-text);
        margin-top: 6px;
    }

    .dep-upload.has-file .dep-upload__filename {
        display: block;
    }

    .dep-upload.has-file .dep-upload__sub {
        display: none;
    }

    /* Preview box */
    .dep-preview {
        background: linear-gradient(135deg, var(--base) 0%, var(--base-2) 100%);
        border: 1px solid var(--line-2);
        border-radius: var(--radius-lg);
        padding: 16px 18px;
        margin-bottom: 18px;
        display: none;
    }

    .dep-preview.show {
        display: block;
    }

    .dep-preview__title {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: var(--ink-4);
        margin-bottom: 12px;
    }

    .dep-preview-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 7px 0;
        border-bottom: 1px solid var(--line);
        font-size: 13px;
    }

    .dep-preview-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .dep-preview-row__label {
        color: var(--ink-4);
    }

    .dep-preview-row__val {
        font-weight: 700;
        color: var(--ink);
    }

    .dep-preview-row.total .dep-preview-row__label {
        font-weight: 700;
        color: var(--ink-2);
    }

    .dep-preview-row.total .dep-preview-row__val {
        font-size: 16px;
        color: var(--accent);
    }

    .dep-preview-row .free {
        color: var(--green);
    }

    /* Submit */
    .dep-submit {
        width: 100%;
        height: 50px;
        border-radius: var(--radius-lg);
        border: none;
        background: linear-gradient(135deg, var(--blue) 0%, var(--blue-dk) 100%);
        color: #fff;
        font-family: var(--f-body);
        font-size: 14.5px;
        font-weight: 700;
        letter-spacing: -.01em;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        transition: all .2s;
        box-shadow: 0 4px 16px rgba(37, 99, 235, .3);
    }

    .dep-submit svg {
        width: 16px;
        height: 16px;
    }

    .dep-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 24px rgba(37, 99, 235, .4);
    }

    .dep-submit:active {
        transform: none;
    }

    /* Security */
    .dep-security {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 14px;
        background: var(--green-lt);
        border: 1px solid var(--green-border);
        border-radius: var(--radius-md);
        margin-top: 14px;
    }

    .dep-security svg {
        width: 14px;
        height: 14px;
        color: var(--green);
        flex-shrink: 0;
    }

    .dep-security p {
        font-size: 12px;
        color: var(--green-text);
    }

    /* ── RECENT REQUESTS ── */
    .dep-recent {}

    .dep-recent-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0;
    }

    .dep-recent-link {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 12.5px;
        font-weight: 600;
        color: var(--accent);
        text-decoration: none;
        transition: gap .15s;
    }

    .dep-recent-link svg {
        width: 12px;
        height: 12px;
    }

    .dep-recent-link:hover {
        gap: 8px;
    }

    .dep-req-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .dep-req-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 16px;
        background: var(--base);
        border: 1px solid var(--line);
        border-radius: var(--radius-lg);
        border-left: 3px solid var(--accent);
        transition: background .15s;
    }

    .dep-req-item:hover {
        background: var(--base-2);
    }

    .dep-req-info {
        display: flex;
        flex-direction: column;
        gap: 3px;
    }

    .dep-req-amount {
        font-size: 15px;
        font-weight: 700;
        color: var(--ink);
        letter-spacing: -.01em;
    }

    .dep-req-date {
        font-size: 11.5px;
        color: var(--ink-4);
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .dep-req-date svg {
        width: 10px;
        height: 10px;
    }

    /* Status badges */
    .dep-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 999px;
        font-size: 11.5px;
        font-weight: 700;
    }

    .dep-badge--pending {
        background: var(--amber-lt);
        color: var(--amber-text);
        border: 1px solid var(--amber-border);
    }

    .dep-badge--approved {
        background: var(--green-lt);
        color: var(--green-text);
        border: 1px solid var(--green-border);
    }

    .dep-badge--rejected {
        background: var(--red-lt);
        color: var(--red-text);
        border: 1px solid var(--red-border);
    }

    .dep-badge svg {
        width: 9px;
        height: 9px;
    }

    /* Empty */
    .dep-empty {
        text-align: center;
        padding: 36px 20px;
        color: var(--ink-4);
    }

    .dep-empty svg {
        width: 32px;
        height: 32px;
        opacity: .3;
        display: block;
        margin: 0 auto 10px;
    }

    .dep-empty p {
        font-size: 13px;
    }

    /* ── RESPONSIVE ── */
    @media (max-width: 900px) {
        .dep-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 600px) {
        .dep-hero {
            padding: 22px 18px;
        }

        .dep-hero__title {
            font-size: 20px;
        }

        .dep-balance-pill {
            width: 100%;
        }

        .dep-balance-pill__val {
            font-size: 28px;
        }

        .dep-card__body {
            padding: 16px;
        }

        .dep-quick {
            flex-direction: row;
        }

        .dep-quick-btn {
            flex: 1;
            text-align: center;
        }
    }
</style>

<div class="dep-wrap">

    <?php if ($this->session->flashdata('success')): ?>
        <div class="dep-alert dep-alert--ok">
            <div class="dep-alert__ico">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="20 6 9 17 4 12" />
                </svg>
            </div>
            <span><?php echo $this->session->flashdata('success'); ?></span>
            <button class="dep-alert__close" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="dep-alert dep-alert--err">
            <div class="dep-alert__ico">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="12" y1="8" x2="12" y2="12" />
                    <line x1="12" y1="16" x2="12.01" y2="16" />
                </svg>
            </div>
            <span><?php echo $this->session->flashdata('error'); ?></span>
            <button class="dep-alert__close" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>

    <!-- HERO -->
    <div class="dep-hero">
        <div class="dep-hero__left">
            <p class="dep-hero__eyebrow">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="5" width="20" height="14" rx="3" />
                    <path d="M2 10h20" />
                </svg>
                Wallet
            </p>
            <h1 class="dep-hero__title">Add Balance</h1>
            <p class="dep-hero__sub">Pay via UPI or bank transfer, then submit your request below.</p>
        </div>
        <div class="dep-balance-pill">
            <span class="dep-balance-pill__label">Current Balance</span>
            <span class="dep-balance-pill__val">₹<?php echo number_format((float)($user->wallet_balance ?? 0), 2); ?></span>
            <div class="dep-balance-pill__foot">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                </svg>
                Secured &amp; Encrypted
            </div>
        </div>
    </div>

    <!-- MAIN GRID -->
    <div class="dep-grid">

        <!-- LEFT: Payment Info -->
        <div class="dep-card">
            <div class="dep-card__head">
                <div class="dep-card__head-icon dep-card__head-icon--purple">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <rect x="2" y="5" width="20" height="14" rx="3" />
                        <path d="M2 10h20" />
                    </svg>
                </div>
                <div>
                    <p class="dep-card__head-eyebrow">Step 1</p>
                    <p class="dep-card__head-title">Payment Details</p>
                </div>
            </div>
            <div class="dep-card__body">

                <?php if (!empty($settings->qr_image)): ?>
                    <div class="dep-qr">
                        <img src="<?php echo base_url('uploads/qr/' . $settings->qr_image); ?>" alt="QR Code">
                        <p class="dep-qr__label">Scan with any UPI app to pay</p>
                    </div>
                <?php endif; ?>

                <!-- UPI -->
                <div class="dep-method">
                    <div class="dep-method__head">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="5" y="2" width="14" height="20" rx="2" />
                            <line x1="12" y1="18" x2="12.01" y2="18" />
                        </svg>
                        <span class="dep-method__head-title">UPI Payment</span>
                    </div>
                    <div class="dep-row">
                        <span class="dep-row__label">UPI ID</span>
                        <div class="dep-row__right">
                            <span class="dep-row__val" id="upiId"><?php echo htmlspecialchars($settings->upi_id); ?></span>
                            <button class="dep-copy" onclick="depCopy('upiId', this)" title="Copy">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <rect x="9" y="9" width="13" height="13" rx="2" />
                                    <path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Bank -->
                <div class="dep-method">
                    <div class="dep-method__head">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="5" width="18" height="14" rx="2" />
                            <path d="M3 10h18M7 15h.01M11 15h2" />
                        </svg>
                        <span class="dep-method__head-title">Bank Transfer</span>
                    </div>
                    <div class="dep-row">
                        <span class="dep-row__label">Bank</span>
                        <span class="dep-row__val"><?php echo htmlspecialchars($settings->bank_name); ?></span>
                    </div>
                    <div class="dep-row">
                        <span class="dep-row__label">Account No.</span>
                        <div class="dep-row__right">
                            <span class="dep-row__val" id="acNo"><?php echo htmlspecialchars($settings->account_number); ?></span>
                            <button class="dep-copy" onclick="depCopy('acNo', this)" title="Copy">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <rect x="9" y="9" width="13" height="13" rx="2" />
                                    <path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="dep-row">
                        <span class="dep-row__label">IFSC</span>
                        <div class="dep-row__right">
                            <span class="dep-row__val" id="ifsc"><?php echo htmlspecialchars($settings->ifsc); ?></span>
                            <button class="dep-copy" onclick="depCopy('ifsc', this)" title="Copy">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <rect x="9" y="9" width="13" height="13" rx="2" />
                                    <path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="dep-apps">
                    <span class="dep-apps__label">Accepted via:</span>
                    <span class="dep-app-badge">GPay</span>
                    <span class="dep-app-badge">PhonePe</span>
                    <span class="dep-app-badge">Paytm</span>
                    <span class="dep-app-badge">NEFT / RTGS</span>
                    <span class="dep-app-badge">IMPS</span>
                </div>

            </div>
        </div>

        <!-- RIGHT: Deposit Form -->
        <div class="dep-card">
            <div class="dep-card__head">
                <div class="dep-card__head-icon dep-card__head-icon--blue">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <polyline points="5 12 12 19 19 12" />
                    </svg>
                </div>
                <div>
                    <p class="dep-card__head-eyebrow">Step 2</p>
                    <p class="dep-card__head-title">Submit Deposit Request</p>
                </div>
            </div>
            <div class="dep-card__body">

                <div class="dep-instructions">
                    <div class="dep-instructions__title">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="8" x2="12" y2="12" />
                            <line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg>
                        How it works
                    </div>
                    <ol class="dep-steps">
                        <li><span class="dep-step-num">1</span> Pay using UPI or Bank Transfer from the left panel</li>
                        <li><span class="dep-step-num">2</span> Enter the amount you paid below</li>
                        <li><span class="dep-step-num">3</span> Upload your payment screenshot or receipt</li>
                        <li><span class="dep-step-num">4</span> Submit — your balance will be credited after admin approval</li>
                    </ol>
                </div>

                <form method="post" action="<?php echo site_url('wallet/request_deposit'); ?>" enctype="multipart/form-data" id="depForm">

                    <!-- Amount -->
                    <div class="dep-field">
                        <label class="dep-label" for="amount">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <line x1="12" y1="1" x2="12" y2="23" />
                                <path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6" />
                            </svg>
                            Amount <span class="req">*</span>
                        </label>
                        <div class="dep-input-wrap">
                            <span class="dep-input-prefix">₹</span>
                            <input type="number" id="amount" name="amount"
                                class="dep-input dep-input--prefixed"
                                placeholder="0.00" min="100" step="1" required
                                oninput="depUpdatePreview()">
                        </div>
                        <p class="dep-hint">Minimum deposit: ₹100</p>
                    </div>

                    <!-- Quick amounts -->
                    <div class="dep-quick">
                        <span class="dep-quick__label">Quick:</span>
                        <button type="button" class="dep-quick-btn" onclick="depSetAmt(500)">₹500</button>
                        <button type="button" class="dep-quick-btn" onclick="depSetAmt(1000)">₹1,000</button>
                        <button type="button" class="dep-quick-btn" onclick="depSetAmt(2000)">₹2,000</button>
                        <button type="button" class="dep-quick-btn" onclick="depSetAmt(5000)">₹5,000</button>
                    </div>

                    <!-- Receipt Upload -->
                    <div class="dep-field">
                        <label class="dep-label">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4" />
                                <polyline points="17 8 12 3 7 8" />
                                <line x1="12" y1="3" x2="12" y2="15" />
                            </svg>
                            Payment Receipt <span class="req">*</span>
                        </label>
                        <div class="dep-upload" id="depUpload">
                            <input type="file" name="receipt" id="receipt" accept=".jpg,.jpeg,.png,.pdf" required
                                onchange="depFileChange(this)">
                            <div class="dep-upload__icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4" />
                                    <polyline points="17 8 12 3 7 8" />
                                    <line x1="12" y1="3" x2="12" y2="15" />
                                </svg>
                            </div>
                            <div class="dep-upload__title">Click to upload or drag &amp; drop</div>
                            <div class="dep-upload__sub">JPG, PNG or PDF · Max 5 MB</div>
                            <div class="dep-upload__filename" id="depFileName"></div>
                        </div>
                    </div>

                    <!-- Preview -->
                    <div class="dep-preview" id="depPreview">
                        <p class="dep-preview__title">Request summary</p>
                        <div class="dep-preview-row">
                            <span class="dep-preview-row__label">Deposit Amount</span>
                            <span class="dep-preview-row__val" id="prevAmt">₹0.00</span>
                        </div>
                        <div class="dep-preview-row">
                            <span class="dep-preview-row__label">Processing Fee</span>
                            <span class="dep-preview-row__val free">Free</span>
                        </div>
                        <div class="dep-preview-row total">
                            <span class="dep-preview-row__label">You will receive</span>
                            <span class="dep-preview-row__val" id="prevTotal">₹0.00</span>
                        </div>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="dep-submit">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <line x1="22" y1="2" x2="11" y2="13" />
                            <polygon points="22 2 15 22 11 13 2 9 22 2" />
                        </svg>
                        Submit Deposit Request
                    </button>

                    <div class="dep-security">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                        </svg>
                        <p>Your transaction is verified by our team. Balance credited within a few minutes of approval.</p>
                    </div>

                </form>
            </div>
        </div>

    </div><!-- /dep-grid -->

    <!-- RECENT REQUESTS -->
    <div class="dep-card">
        <div class="dep-card__head">
            <div class="dep-card__head-icon dep-card__head-icon--green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <circle cx="12" cy="12" r="10" />
                    <polyline points="12 6 12 12 16 14" />
                </svg>
            </div>
            <div class="dep-recent-head" style="flex:1;">
                <div>
                    <p class="dep-card__head-eyebrow">History</p>
                    <p class="dep-card__head-title">Recent Requests</p>
                </div>
                <a href="<?php echo site_url('wallet'); ?>" class="dep-recent-link">
                    View all
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="5" y1="12" x2="19" y2="12" />
                        <polyline points="12 5 19 12 12 19" />
                    </svg>
                </a>
            </div>
        </div>
        <div class="dep-card__body">
            <?php
            $recent_requests = $this->db
                ->where('user_id', $this->session->userdata('user_id'))
                ->order_by('created_at', 'DESC')
                ->limit(3)
                ->get('deposit_requests')
                ->result();
            ?>

            <?php if (!empty($recent_requests)): ?>
                <div class="dep-req-list">
                    <?php foreach ($recent_requests as $req): ?>
                        <div class="dep-req-item">
                            <div class="dep-req-info">
                                <span class="dep-req-amount">₹<?php echo number_format($req->amount, 2); ?></span>
                                <span class="dep-req-date">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10" />
                                        <polyline points="12 6 12 12 16 14" />
                                    </svg>
                                    <?php echo date('d M Y, h:i A', strtotime($req->created_at)); ?>
                                </span>
                            </div>
                            <?php if ($req->status == 'pending'): ?>
                                <span class="dep-badge dep-badge--pending">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <circle cx="12" cy="12" r="10" />
                                        <polyline points="12 6 12 12" />
                                    </svg>
                                    Pending
                                </span>
                            <?php elseif ($req->status == 'approved'): ?>
                                <span class="dep-badge dep-badge--approved">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                    Approved
                                </span>
                            <?php else: ?>
                                <span class="dep-badge dep-badge--rejected">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <line x1="18" y1="6" x2="6" y2="18" />
                                        <line x1="6" y1="6" x2="18" y2="18" />
                                    </svg>
                                    Rejected
                                </span>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="dep-empty">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <rect x="2" y="7" width="20" height="14" rx="2" />
                        <path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2" />
                        <line x1="12" y1="12" x2="12" y2="16" />
                        <line x1="10" y1="14" x2="14" y2="14" />
                    </svg>
                    <p>No deposit requests yet</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<script>
    function depCopy(id, btn) {
        var text = document.getElementById(id).textContent.trim();
        navigator.clipboard.writeText(text).then(function() {
            var svg = btn.innerHTML;
            btn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>';
            btn.classList.add('copied');
            setTimeout(function() {
                btn.innerHTML = svg;
                btn.classList.remove('copied');
            }, 2000);
        });
    }

    function depSetAmt(amt) {
        document.getElementById('amount').value = amt;
        document.querySelectorAll('.dep-quick-btn').forEach(function(b) {
            b.classList.remove('active');
        });
        event.target.classList.add('active');
        depUpdatePreview();
    }

    function depUpdatePreview() {
        var amt = parseFloat(document.getElementById('amount').value) || 0;
        var prev = document.getElementById('depPreview');
        if (amt > 0) {
            prev.classList.add('show');
            document.getElementById('prevAmt').textContent = '₹' + amt.toFixed(2);
            document.getElementById('prevTotal').textContent = '₹' + amt.toFixed(2);
        } else {
            prev.classList.remove('show');
        }
        document.querySelectorAll('.dep-quick-btn').forEach(function(b) {
            b.classList.toggle('active', parseFloat(b.textContent.replace(/[₹,]/g, '')) === amt);
        });
    }

    function depFileChange(inp) {
        var wrap = document.getElementById('depUpload');
        var fn = document.getElementById('depFileName');
        if (inp.files && inp.files[0]) {
            wrap.classList.add('has-file');
            fn.textContent = inp.files[0].name;
        } else {
            wrap.classList.remove('has-file');
            fn.textContent = '';
        }
    }

    document.getElementById('depForm').addEventListener('submit', function(e) {
        var amt = parseFloat(document.getElementById('amount').value);
        if (!amt || amt < 100) {
            e.preventDefault();
            alert('Minimum deposit amount is ₹100');
        }
    });

    setTimeout(function() {
        document.querySelectorAll('.dep-alert').forEach(function(el) {
            el.style.transition = 'opacity .4s, transform .4s';
            el.style.opacity = '0';
            el.style.transform = 'translateY(-8px)';
            setTimeout(function() {
                el.remove();
            }, 420);
        });
    }, 5000);
</script>