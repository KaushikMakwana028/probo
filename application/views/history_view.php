<?php
/*
 * Trade History View — Enhanced Premium Dashboard
 * Fully Mobile Responsive + Enhanced UI
 * CodeIgniter 3/4 compatible
 */
?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700;800;900&family=DM+Mono:wght@400;500;600&display=swap" rel="stylesheet">

<style>
    *,
    *::before,
    *::after {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    :root {
        --page: #f1f5f9;
        --white: #ffffff;
        --surface: #f8fafc;
        --border: #e2e8f0;
        --border2: #cbd5e1;
        --accent: #2563eb;
        --accent-light: #eff6ff;
        --accent-mid: #bfdbfe;
        --green: #16a34a;
        --green-bg: #f0fdf4;
        --green-mid: #bbf7d0;
        --red: #dc2626;
        --red-bg: #fff1f2;
        --red-mid: #fecdd3;
        --amber: #b45309;
        --amber-bg: #fffbeb;
        --amber-mid: #fde68a;
        --text: #0f172a;
        --text2: #475569;
        --text3: #94a3b8;
        --sh: 0 1px 3px rgba(0, 0, 0, .07), 0 1px 2px rgba(0, 0, 0, .04);
        --sh-md: 0 4px 16px rgba(0, 0, 0, .09), 0 2px 6px rgba(0, 0, 0, .05);
        --sh-lg: 0 8px 32px rgba(0, 0, 0, .10), 0 4px 12px rgba(0, 0, 0, .06);
        --r: 12px;
        --rlg: 16px;
        --rcard: 18px;
    }

    html,
    body {
        font-family: 'DM Sans', sans-serif;
        background: var(--page);
        color: var(--text);
        -webkit-font-smoothing: antialiased;
    }

    /* ── PAGE ─────────────────────────────────────── */
    .th-page {
        padding: 0 0 4rem;
        min-height: 100vh;
        max-width: 1400px;
        margin: 0 auto;
        padding-left: 20px;
        padding-right: 20px;
    }

    /* ── HEADER ───────────────────────────────────── */
    .th-hdr {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        padding: 2rem 0 1.75rem;
        gap: 12px;
        flex-wrap: wrap;
    }

    .th-hdr-left {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .th-bc {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .12em;
        color: var(--text3);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .th-bc span {
        color: var(--text3);
    }

    .th-bc em {
        color: var(--accent);
        font-style: normal;
    }

    .th-bc-sep {
        opacity: .5;
    }

    .th-ttl {
        font-size: clamp(22px, 5vw, 30px);
        font-weight: 900;
        color: var(--text);
        letter-spacing: -1.5px;
        line-height: 1;
    }

    .th-ttl em {
        font-style: normal;
        color: var(--accent);
    }

    .live-chip {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 16px;
        background: var(--green-bg);
        border: 1px solid var(--green-mid);
        border-radius: 24px;
        align-self: flex-start;
        margin-top: 8px;
        box-shadow: 0 2px 8px rgba(22, 163, 74, .12);
    }

    .live-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: var(--green);
        animation: blink 2s infinite;
        box-shadow: 0 0 0 3px rgba(22, 163, 74, .2);
    }

    @keyframes blink {

        0%,
        100% {
            opacity: 1
        }

        50% {
            opacity: .3
        }
    }

    .live-txt {
        font-size: 12px;
        font-weight: 700;
        color: var(--green);
    }

    /* ── STAT CARDS ───────────────────────────────── */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
        margin-bottom: 1.5rem;
    }

    .scard {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: var(--rcard);
        padding: 20px;
        box-shadow: var(--sh);
        transition: box-shadow .2s, transform .2s;
        position: relative;
        overflow: hidden;
    }

    .scard::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        border-radius: var(--rcard) var(--rcard) 0 0;
    }

    .scard-purple::before {
        background: linear-gradient(90deg, #7c3aed, #a855f7);
    }

    .scard-green::before {
        background: linear-gradient(90deg, #16a34a, #22c55e);
    }

    .scard-red::before {
        background: linear-gradient(90deg, #dc2626, #f87171);
    }

    .scard-blue::before {
        background: linear-gradient(90deg, #2563eb, #60a5fa);
    }

    .scard:hover {
        box-shadow: var(--sh-md);
        transform: translateY(-2px);
    }

    .scard-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 14px;
    }

    .scard-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .scard-icon svg {
        width: 18px;
        height: 18px;
        fill: none;
        stroke-width: 2;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .ic-total {
        background: #ede9fe;
    }

    .ic-win {
        background: var(--green-bg);
        border: 1px solid var(--green-mid);
    }

    .ic-loss {
        background: var(--red-bg);
        border: 1px solid var(--red-mid);
    }

    .ic-money {
        background: var(--accent-light);
        border: 1px solid var(--accent-mid);
    }

    .scard-badge {
        font-size: 10px;
        font-weight: 700;
        padding: 4px 9px;
        border-radius: 20px;
        letter-spacing: .03em;
    }

    .badge-purple {
        background: #ede9fe;
        color: #5b21b6;
    }

    .badge-green {
        background: var(--green-bg);
        color: var(--green);
    }

    .badge-red {
        background: var(--red-bg);
        color: var(--red);
    }

    .badge-blue {
        background: var(--accent-light);
        color: var(--accent);
    }

    .scard-val {
        font-size: clamp(26px, 4vw, 34px);
        font-weight: 900;
        letter-spacing: -2px;
        line-height: 1;
        margin-bottom: 4px;
    }

    .scard-val.amt {
        font-size: clamp(16px, 2.5vw, 22px);
        letter-spacing: -.5px;
    }

    .scard-lbl {
        font-size: 10.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .12em;
        color: var(--text3);
    }

    .scard-div {
        height: 1px;
        background: var(--border);
        margin: 12px 0 10px;
    }

    .scard-sub {
        font-size: 12px;
        color: var(--text3);
        line-height: 1.4;
    }

    .val-purple {
        color: #6d28d9;
    }

    .val-green {
        color: var(--green);
    }

    .val-red {
        color: var(--red);
    }

    .val-blue {
        color: var(--accent);
    }

    .scard-bar {
        height: 5px;
        border-radius: 5px;
        background: var(--border);
        overflow: hidden;
        margin-top: 14px;
    }

    .scard-fill {
        height: 100%;
        border-radius: 5px;
    }

    .fill-g {
        background: linear-gradient(90deg, #16a34a, #22c55e);
    }

    .fill-r {
        background: linear-gradient(90deg, #dc2626, #f87171);
    }

    .mini-c {
        display: flex;
        align-items: flex-end;
        gap: 3px;
        height: 28px;
        margin-top: 14px;
    }

    .mc-b {
        flex: 1;
        border-radius: 3px 3px 0 0;
        min-height: 3px;
    }

    /* ── SUMMARY ROW ──────────────────────────────── */
    .sum-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
        margin-bottom: 1.5rem;
    }

    .srow-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: var(--rcard);
        padding: 18px 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: var(--sh);
        transition: box-shadow .2s;
    }

    .srow-card:hover {
        box-shadow: var(--sh-md);
    }

    .pr-svg {
        width: 52px;
        height: 52px;
        flex-shrink: 0;
    }

    .pr-bg {
        stroke: #e2e8f0;
    }

    .pr-fill {
        transform-origin: center;
        transform: rotate(-90deg);
        transition: stroke-dashoffset .9s cubic-bezier(.4, 0, .2, 1);
    }

    .srow-lbl {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .12em;
        color: var(--text3);
        margin-bottom: 3px;
    }

    .srow-val {
        font-size: clamp(20px, 3.5vw, 26px);
        font-weight: 900;
        letter-spacing: -1px;
        line-height: 1.1;
    }

    .srow-desc {
        font-size: 12px;
        color: var(--text2);
        margin-top: 4px;
    }

    /* ── CONTROLS ─────────────────────────────────── */
    .th-ctrl {
        display: flex;
        gap: 10px;
        margin-bottom: 1.25rem;
        align-items: center;
        flex-wrap: wrap;
    }

    .s-wrap {
        flex: 1;
        min-width: 200px;
        position: relative;
    }

    .s-wrap input {
        width: 100%;
        height: 44px;
        padding: 0 14px 0 44px;
        background: var(--white);
        border: 1.5px solid var(--border);
        border-radius: var(--r);
        font-size: 13.5px;
        font-family: 'DM Sans', sans-serif;
        color: var(--text);
        outline: none;
        box-shadow: var(--sh);
        transition: border .15s, box-shadow .15s;
    }

    .s-wrap input::placeholder {
        color: var(--text3);
    }

    .s-wrap input:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, .12);
    }

    .s-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        width: 16px;
        height: 16px;
        stroke: var(--text3);
        fill: none;
        stroke-width: 2;
        stroke-linecap: round;
        pointer-events: none;
    }

    .ctrl-right {
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
    }

    .f-pills {
        display: flex;
        gap: 3px;
        background: var(--white);
        border: 1.5px solid var(--border);
        border-radius: var(--r);
        padding: 4px;
        box-shadow: var(--sh);
    }

    .pill {
        height: 36px;
        padding: 0 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        color: var(--text2);
        cursor: pointer;
        font-family: 'DM Sans', sans-serif;
        border: none;
        background: transparent;
        transition: all .15s;
        white-space: nowrap;
    }

    .pill.active {
        background: var(--accent);
        color: #fff;
        font-weight: 700;
        box-shadow: 0 2px 10px rgba(37, 99, 235, .3);
    }

    .pill:not(.active):hover {
        background: var(--surface);
        color: var(--text);
    }

    .srt-sel {
        height: 44px;
        padding: 0 36px 0 14px;
        background: var(--white);
        border: 1.5px solid var(--border);
        border-radius: var(--r);
        font-size: 13px;
        font-family: 'DM Sans', sans-serif;
        color: var(--text);
        cursor: pointer;
        outline: none;
        box-shadow: var(--sh);
        font-weight: 500;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.5' stroke-linecap='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
    }

    .srt-sel:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, .12);
    }

    /* ── TABLE ────────────────────────────────────── */
    /* Columns: cat(120px) | question(flex) | ans(65px) | spent(130px) | winning(140px) | status(100px) | date(140px) */
    .tbl-wrap {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: var(--rcard);
        overflow: hidden;
        box-shadow: var(--sh-lg);
    }

    .tbl-head {
        display: grid;
        grid-template-columns: 120px minmax(0, 1fr) 65px 130px 140px 100px 140px;
        background: var(--surface);
        border-bottom: 1.5px solid var(--border);
    }

    .th {
        padding: 12px 15px;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .1em;
        color: var(--text3);
    }

    .trow {
        display: grid;
        grid-template-columns: 120px minmax(0, 1fr) 65px 130px 140px 100px 140px;
        border-bottom: 1px solid var(--border);
        transition: background .12s;
    }

    .trow:last-child {
        border-bottom: none;
    }

    .trow:hover {
        background: #f5f8ff;
    }

    .trow:hover .q-text {
        color: var(--text);
    }

    .td {
        padding: 14px 15px;
        font-size: 13px;
        color: var(--text);
        display: flex;
        align-items: center;
        min-width: 0;
        overflow: hidden;
    }

    /* Category */
    .cat {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        border-radius: 8px;
        padding: 5px 10px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
        flex-shrink: 0;
        max-width: 100%;
        overflow: hidden;
    }

    .cat span {
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .cat-dot {
        width: 5px;
        height: 5px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .cat-Cricket {
        background: #f5f3ff;
        color: #6d28d9;
    }

    .cat-Cricket .cat-dot {
        background: #7c3aed;
    }

    .cat-Football {
        background: #eff6ff;
        color: #1e40af;
    }

    .cat-Football .cat-dot {
        background: #2563eb;
    }

    .cat-Politics {
        background: #fff1f2;
        color: #9f1239;
    }

    .cat-Politics .cat-dot {
        background: #e11d48;
    }

    .cat-Movies {
        background: #fff7ed;
        color: #9a3412;
    }

    .cat-Movies .cat-dot {
        background: #ea580c;
    }

    .cat-Finance {
        background: #f0fdf4;
        color: #166534;
    }

    .cat-Finance .cat-dot {
        background: #16a34a;
    }

    .cat-Sports {
        background: #ecfeff;
        color: #155e75;
    }

    .cat-Sports .cat-dot {
        background: #0891b2;
    }

    .cat-Tech {
        background: #faf5ff;
        color: #6b21a8;
    }

    .cat-Tech .cat-dot {
        background: #a855f7;
    }

    .cat-Entertainment {
        background: #fdf2f8;
        color: #86198f;
    }

    .cat-Entertainment .cat-dot {
        background: #c026d3;
    }

    /* Question */
    .q-wrap {
        width: 100%;
        min-width: 0;
    }

    .q-text {
        display: block;
        font-size: 12.5px;
        line-height: 1.45;
        color: var(--text2);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
        transition: color .15s;
        cursor: default;
        font-weight: 500;
    }

    /* Answer */
    .ans {
        display: inline-flex;
        align-items: center;
        border-radius: 7px;
        padding: 4px 9px;
        font-size: 11px;
        font-weight: 700;
        font-family: 'DM Mono', monospace;
        white-space: nowrap;
        letter-spacing: .03em;
    }

    .ans-yes {
        background: var(--green-bg);
        color: var(--green);
        border: 1px solid var(--green-mid);
    }

    .ans-no {
        background: var(--red-bg);
        color: var(--red);
        border: 1px solid var(--red-mid);
    }

    /* Amounts */
    .stake-v,
    .amt-p,
    .amt-n {
        font-family: 'DM Mono', monospace;
        font-size: 13px;
        white-space: nowrap;
    }

    .stake-v {
        font-weight: 600;
        color: var(--text);
    }

    .amt-p {
        font-weight: 700;
        color: var(--green);
    }

    .amt-n {
        color: var(--text3);
    }

    /* Status */
    .status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        border-radius: 20px;
        padding: 5px 12px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
        letter-spacing: .02em;
    }

    .status::before {
        content: '';
        width: 5px;
        height: 5px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .s-win {
        background: var(--green-bg);
        color: var(--green);
        border: 1px solid var(--green-mid);
    }

    .s-win::before {
        background: var(--green);
    }

    .s-lose {
        background: var(--red-bg);
        color: var(--red);
        border: 1px solid var(--red-mid);
    }

    .s-lose::before {
        background: var(--red);
    }

    .s-pending {
        background: var(--amber-bg);
        color: var(--amber);
        border: 1px solid var(--amber-mid);
    }

    .s-pending::before {
        background: var(--amber);
        animation: blink 1.5s infinite;
    }

    .s-sold {
        background: var(--accent-light);
        color: var(--accent);
        border: 1px solid var(--accent-mid);
    }

    .s-sold::before {
        background: var(--accent);
    }

    /* Date */
    .date-v {
        font-size: 11.5px;
        color: var(--text2);
        font-family: 'DM Mono', monospace;
        line-height: 1.6;
    }

    /* ── EMPTY STATE ──────────────────────────────── */
    .empty-st {
        padding: 5rem 2rem;
        text-align: center;
    }

    .empty-ic {
        width: 58px;
        height: 58px;
        margin: 0 auto 16px;
        border-radius: 16px;
        background: var(--surface);
        border: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .empty-ic svg {
        width: 24px;
        height: 24px;
        stroke: var(--text3);
        fill: none;
        stroke-width: 1.5;
        stroke-linecap: round;
    }

    .empty-t {
        font-size: 16px;
        font-weight: 800;
        color: var(--text2);
        margin-bottom: 6px;
    }

    .empty-s {
        font-size: 13px;
        color: var(--text3);
    }

    /* ── PAGINATION ───────────────────────────────── */
    .pagi {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 20px;
        border-top: 1px solid var(--border);
        background: var(--surface);
        flex-wrap: wrap;
        gap: 12px;
    }

    .pg-info {
        font-size: 12px;
        color: var(--text3);
    }

    .pg-info strong {
        color: var(--text2);
        font-weight: 700;
    }

    .pg-btns {
        display: flex;
        gap: 4px;
        flex-wrap: wrap;
    }

    .pg-btn {
        height: 34px;
        min-width: 34px;
        padding: 0 10px;
        border: 1px solid var(--border);
        border-radius: 8px;
        background: var(--white);
        color: var(--text2);
        font-size: 12.5px;
        font-family: 'DM Sans', sans-serif;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all .12s;
        font-weight: 600;
        box-shadow: var(--sh);
        text-decoration: none;
    }

    .pg-btn:hover:not(.active):not(.disabled) {
        background: var(--accent-light);
        border-color: var(--accent-mid);
        color: var(--accent);
    }

    .pg-btn.active {
        background: var(--accent);
        color: #fff;
        border-color: var(--accent);
        font-weight: 700;
        box-shadow: 0 2px 8px rgba(37, 99, 235, .3);
    }

    .pg-btn.disabled {
        opacity: .3;
        cursor: default;
        pointer-events: none;
    }

    /* ══════════════════════════════════════════════
   MOBILE CARDS — shown below 760px
══════════════════════════════════════════════ */
    .mob-card {
        display: none;
        flex-direction: column;
        gap: 0;
        padding: 0;
        border-bottom: 1px solid var(--border);
        background: var(--white);
        transition: background .12s;
    }

    .mob-card:last-child {
        border-bottom: none;
    }

    .mob-card:active {
        background: #f5f8ff;
    }

    .mob-card-inner {
        padding: 16px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .mob-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .mob-top-left {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .mob-q {
        font-size: 13.5px;
        font-weight: 600;
        color: var(--text);
        line-height: 1.5;
    }

    .mob-divider {
        height: 1px;
        background: var(--border);
        margin: 0 -16px;
        width: calc(100% + 32px);
    }

    .mob-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px 16px;
    }

    .mob-field {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .mob-lbl {
        font-size: 9.5px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .12em;
        color: var(--text3);
    }

    /* ── RESPONSIVE BREAKPOINTS ───────────────────── */

    /* 1200px — slightly tighter columns */
    @media (max-width: 1200px) {

        .tbl-head,
        .trow {
            grid-template-columns: 110px minmax(0, 1fr) 60px 120px 130px 95px 130px;
        }
    }

    /* 1024px — 2x2 stats on tablet */
    @media (max-width: 1024px) {
        .stats-grid {
            grid-template-columns: 1fr 1fr;
        }

        .tbl-head,
        .trow {
            grid-template-columns: 100px minmax(0, 1fr) 55px 110px 120px 88px 118px;
        }

        .th,
        .td {
            padding: 11px 12px;
        }
    }

    /* 900px — hide date column */
    @media (max-width: 900px) {

        .tbl-head,
        .trow {
            grid-template-columns: 100px minmax(0, 1fr) 55px 110px 120px 88px;
        }

        .th:last-child,
        .td:last-child {
            display: none;
        }

        .sum-row {
            grid-template-columns: 1fr 1fr;
        }
    }

    /* 760px — switch to mobile cards */
    @media (max-width: 760px) {
        .th-page {
            padding-left: 16px;
            padding-right: 16px;
        }

        .stats-grid {
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .sum-row {
            grid-template-columns: 1fr;
            gap: 10px;
        }

        /* Hide desktop table, show cards */
        .tbl-head {
            display: none;
        }

        .trow {
            display: none;
        }

        .mob-card {
            display: flex;
        }

        /* Controls stack vertically */
        .th-ctrl {
            flex-direction: column;
            gap: 10px;
        }

        .s-wrap {
            min-width: 100%;
        }

        .ctrl-right {
            width: 100%;
            flex-direction: column;
            gap: 8px;
        }

        .f-pills {
            width: 100%;
            justify-content: space-between;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
        }

        .f-pills::-webkit-scrollbar {
            display: none;
        }

        .pill {
            padding: 0 12px;
            font-size: 12.5px;
        }

        .srt-sel {
            width: 100%;
        }

        /* Smaller stat card values */
        .scard-val {
            font-size: 26px;
        }

        .scard-val.amt {
            font-size: 17px;
        }

        .scard {
            padding: 16px;
        }

        /* Pagination scrollable */
        .pagi {
            flex-direction: column;
            align-items: flex-start;
        }

        .pg-btns {
            overflow-x: auto;
            width: 100%;
            flex-wrap: nowrap;
            padding-bottom: 2px;
        }

        .pg-btn {
            flex-shrink: 0;
        }
    }

    /* 520px — single-column stats */
    @media (max-width: 520px) {
        .stats-grid {
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .scard {
            padding: 14px;
        }

        .scard-val {
            font-size: 22px;
        }

        .scard-val.amt {
            font-size: 15px;
        }

        .scard-badge {
            font-size: 9px;
            padding: 3px 7px;
        }

        .scard-sub {
            font-size: 11px;
        }

        .scard-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
        }

        .scard-icon svg {
            width: 15px;
            height: 15px;
        }

        .th-ttl {
            letter-spacing: -1px;
        }

        .mob-q {
            font-size: 13px;
        }
    }

    /* 380px — very small phones */
    @media (max-width: 380px) {
        .th-page {
            padding-left: 12px;
            padding-right: 12px;
        }

        .th-hdr {
            flex-direction: column;
        }

        .stats-grid {
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .scard-val {
            font-size: 20px;
        }

        .mob-grid {
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
    }
</style>

<?php
/* ─── PHP CALCULATIONS ──────────────────────────── */
$history = isset($history) && is_array($history) ? $history : array();
$total_count = count($history);
$wins = array();
$losses = array();
$pending = array();
$total_won = 0;
$total_staked = 0;
$pending_stake = 0;
$win_stake = 0;

foreach ($history as $history_row) {
    $result        = isset($history_row->result)         ? $history_row->result         : 'pending';
    $stake_amount  = isset($history_row->stake_amount)   ? (float)$history_row->stake_amount   : 0;
    $winning_amount = isset($history_row->winning_amount) ? (float)$history_row->winning_amount : 0;
    $total_staked += $stake_amount;
    if ($result === 'win' || $result === 'sold') {
        $wins[] = $history_row;
        $total_won += $winning_amount;
        $win_stake += $stake_amount;
    } elseif ($result === 'lose') {
        $losses[] = $history_row;
    } else {
        $pending[] = $history_row;
        $pending_stake += $stake_amount;
    }
}

$win_count    = count($wins);
$loss_count   = count($losses);
$pending_count = count($pending);
$avg_win      = $win_count   ? $total_won / $win_count : 0;
$win_rate     = $total_count ? $win_count  / $total_count * 100 : 0;
$loss_rate    = $total_count ? $loss_count / $total_count * 100 : 0;
$roi          = $win_stake   ? ($total_won - $win_stake) / $win_stake * 100 : 0;

/* Mini chart — last 6 months */
$mc_trades = array_fill(0, 6, 0);
$mc_won    = array_fill(0, 6, 0);
foreach ($history as $r) {
    $i = (int)floor((time() - strtotime($r->created_at)) / 2592000);
    if ($i < 6) {
        $mc_trades[5 - $i]++;
        if ($r->result === 'win') $mc_won[5 - $i] += ($r->winning_amount ?? 0);
    }
}
$mxt = max(array_merge($mc_trades, [1]));
$mxw = max(array_merge($mc_won,    [1]));

/* Ring offsets (r=21, circ≈132) */
$CIRC = 132;
$rw   = number_format($CIRC * (1 - $win_rate / 100), 2);
$rr   = number_format($CIRC * (1 - min($roi / 100, 1)), 2);
$rp   = number_format($CIRC * (1 - ($total_count ? $pending_count / $total_count : 0)), 2);

/* Filter / search / sort / pagination */
$active_filter = $this->input->get('filter') ?: 'all';
$search_q      = trim($this->input->get('search') ?: '');
$sort_by       = $this->input->get('sort')   ?: 'newest';
$current_page  = max(1, (int)($this->input->get('pg') ?: 1));
$per_page      = 10;

$filtered = $history;
if ($active_filter !== 'all') {
    $tmp = array();
    foreach ($filtered as $fr) {
        if (isset($fr->result) && $fr->result === $active_filter) $tmp[] = $fr;
    }
    $filtered = $tmp;
}
if ($search_q !== '') {
    $sq  = strtolower($search_q);
    $tmp = array();
    foreach ($filtered as $fr) {
        $qv = strtolower(isset($fr->question)      ? $fr->question      : '');
        $cv = strtolower(isset($fr->category_name) ? $fr->category_name : '');
        if (strpos($qv, $sq) !== FALSE || strpos($cv, $sq) !== FALSE) $tmp[] = $fr;
    }
    $filtered = $tmp;
}
$filtered = array_values($filtered);
usort($filtered, function ($a, $b) use ($sort_by) {
    $ai = isset($a->id) ? (int)$a->id : 0;
    $bi = isset($b->id) ? (int)$b->id : 0;
    $as = isset($a->stake_amount)   ? (float)$a->stake_amount   : 0;
    $bs = isset($b->stake_amount)   ? (float)$b->stake_amount   : 0;
    $aw = isset($a->winning_amount) ? (float)$a->winning_amount : 0;
    $bw = isset($b->winning_amount) ? (float)$b->winning_amount : 0;
    switch ($sort_by) {
        case 'oldest':
            return $ai <=> $bi;
        case 'stake_high':
            return $bs <=> $as;
        case 'stake_low':
            return $as <=> $bs;
        case 'win_high':
            return $bw <=> $aw;
        default:
            return $bi <=> $ai;
    }
});
$total_filtered = count($filtered);
$total_pages    = max(1, (int)ceil($total_filtered / $per_page));
$current_page   = min($current_page, $total_pages);
$offset         = ($current_page - 1) * $per_page;
$page_rows      = array_slice($filtered, $offset, $per_page);
$start_num      = $total_filtered ? $offset + 1 : 0;
$end_num        = min($offset + $per_page, $total_filtered);
$bq             = "filter={$active_filter}&search=" . urlencode($search_q) . "&sort={$sort_by}";

function th_cat($n)
{
    foreach (['Cricket', 'Football', 'Politics', 'Movies', 'Finance', 'Sports', 'Tech', 'Entertainment'] as $k)
        if (stripos((string)$n, $k) !== false) return $k;
    return 'Finance';
}
?>

<div class="th-page">

    <!-- HEADER -->
    <div class="th-hdr">
        <div class="th-hdr-left">
            <div class="th-bc">
                <span>Dashboard</span>
                <span class="th-bc-sep">/</span>
                <em>Trade History</em>
            </div>
            <div class="th-ttl">Trade <em>History</em></div>
        </div>
        <div class="live-chip">
            <div class="live-dot"></div>
            <span class="live-txt">Live</span>
        </div>
    </div>

    <!-- STAT CARDS -->
    <div class="stats-grid">

        <div class="scard scard-purple">
            <div class="scard-top">
                <div class="scard-icon ic-total">
                    <svg viewBox="0 0 20 20" stroke="#7c3aed">
                        <rect x="2" y="2" width="7" height="7" rx="1.5" />
                        <rect x="11" y="2" width="7" height="7" rx="1.5" />
                        <rect x="2" y="11" width="7" height="7" rx="1.5" />
                        <rect x="11" y="11" width="7" height="7" rx="1.5" />
                    </svg>
                </div>
                <span class="scard-badge badge-purple">All time</span>
            </div>
            <div class="scard-val val-purple"><?= $total_count ?></div>
            <div class="scard-lbl">Total Trades</div>
            <div class="scard-div"></div>
            <div class="scard-sub">₹<?= number_format($total_staked, 2) ?> staked</div>
            <div class="mini-c">
                <?php foreach ($mc_trades as $v): $h = max(3, round($v / $mxt * 26));
                    $op = round(0.35 + $v / $mxt * 0.65, 2); ?>
                    <div class="mc-b" style="height:<?= $h ?>px;background:#7c3aed;opacity:<?= $op ?>"></div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="scard scard-green">
            <div class="scard-top">
                <div class="scard-icon ic-win">
                    <svg viewBox="0 0 20 20" stroke="#16a34a">
                        <path d="M4 10l4 4 8-8" />
                    </svg>
                </div>
                <span class="scard-badge badge-green">↑ <?= number_format($win_rate, 0) ?>% rate</span>
            </div>
            <div class="scard-val val-green"><?= $win_count ?></div>
            <div class="scard-lbl">Wins</div>
            <div class="scard-div"></div>
            <div class="scard-sub"><?= number_format($win_rate, 1) ?>% win rate</div>
            <div class="scard-bar">
                <div class="scard-fill fill-g" style="width:<?= number_format($win_rate, 1) ?>%"></div>
            </div>
        </div>

        <div class="scard scard-red">
            <div class="scard-top">
                <div class="scard-icon ic-loss">
                    <svg viewBox="0 0 20 20" stroke="#dc2626">
                        <path d="M5 5l10 10M15 5L5 15" />
                    </svg>
                </div>
                <span class="scard-badge badge-red">↓ <?= number_format($loss_rate, 0) ?>% rate</span>
            </div>
            <div class="scard-val val-red"><?= $loss_count ?></div>
            <div class="scard-lbl">Losses</div>
            <div class="scard-div"></div>
            <div class="scard-sub"><?= number_format($loss_rate, 1) ?>% loss rate</div>
            <div class="scard-bar">
                <div class="scard-fill fill-r" style="width:<?= number_format($loss_rate, 1) ?>%"></div>
            </div>
        </div>

        <div class="scard scard-blue">
            <div class="scard-top">
                <div class="scard-icon ic-money">
                    <svg viewBox="0 0 20 20" stroke="#2563eb">
                        <circle cx="10" cy="10" r="8" />
                        <path d="M10 6v8M8 8.5c0-1.1.9-1.5 2-1.5s2 .4 2 1.5-1 1.5-2 1.5-2 .4-2 1.5.9 1.5 2 1.5 2-.4 2-1.5" />
                    </svg>
                </div>
                <span class="scard-badge badge-blue">Net profit</span>
            </div>
            <div class="scard-val val-blue amt">₹<?= number_format($total_won, 2) ?></div>
            <div class="scard-lbl">Total Won</div>
            <div class="scard-div"></div>
            <div class="scard-sub">Avg ₹<?= number_format($avg_win, 2) ?> per win</div>
            <div class="mini-c">
                <?php foreach ($mc_won as $v): $h = max(3, round($v / $mxw * 26));
                    $op = round(0.35 + $v / $mxw * 0.65, 2); ?>
                    <div class="mc-b" style="height:<?= $h ?>px;background:#2563eb;opacity:<?= $op ?>"></div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>

    <!-- SUMMARY ROW -->
    <div class="sum-row">
        <div class="srow-card">
            <svg class="pr-svg" viewBox="0 0 52 52">
                <circle class="pr-bg" cx="26" cy="26" r="21" fill="none" stroke-width="4.5" />
                <circle class="pr-fill" cx="26" cy="26" r="21" fill="none" stroke-width="4.5"
                    stroke="#16a34a" stroke-dasharray="<?= $CIRC ?>" stroke-dashoffset="<?= $rw ?>" stroke-linecap="round" />
            </svg>
            <div>
                <div class="srow-lbl">Win Rate</div>
                <div class="srow-val val-green"><?= number_format($win_rate, 1) ?>%</div>
                <div class="srow-desc"><?= $win_count ?> of <?= $total_count ?> trades won</div>
            </div>
        </div>
        <div class="srow-card">
            <svg class="pr-svg" viewBox="0 0 52 52">
                <circle class="pr-bg" cx="26" cy="26" r="21" fill="none" stroke-width="4.5" />
                <circle class="pr-fill" cx="26" cy="26" r="21" fill="none" stroke-width="4.5"
                    stroke="#2563eb" stroke-dasharray="<?= $CIRC ?>" stroke-dashoffset="<?= $rr ?>" stroke-linecap="round" />
            </svg>
            <div>
                <div class="srow-lbl">Avg Return</div>
                <div class="srow-val val-blue">+<?= number_format($roi, 1) ?>%</div>
                <div class="srow-desc">per winning trade</div>
            </div>
        </div>
        <div class="srow-card">
            <svg class="pr-svg" viewBox="0 0 52 52">
                <circle class="pr-bg" cx="26" cy="26" r="21" fill="none" stroke-width="4.5" />
                <circle class="pr-fill" cx="26" cy="26" r="21" fill="none" stroke-width="4.5"
                    stroke="#b45309" stroke-dasharray="<?= $CIRC ?>" stroke-dashoffset="<?= $rp ?>" stroke-linecap="round" />
            </svg>
            <div>
                <div class="srow-lbl">Pending</div>
                <div class="srow-val" style="color:#b45309"><?= $pending_count ?></div>
                <div class="srow-desc">₹<?= number_format($pending_stake, 2) ?> at stake</div>
            </div>
        </div>
    </div>

    <!-- CONTROLS -->
    <div class="th-ctrl">
        <div class="s-wrap">
            <svg class="s-icon" viewBox="0 0 16 16">
                <circle cx="6.5" cy="6.5" r="5" />
                <path d="M10.5 10.5l3.5 3.5" />
            </svg>
            <input type="text" id="searchInput"
                placeholder="Search question or category…"
                value="<?= htmlspecialchars($search_q) ?>">
        </div>
        <div class="ctrl-right">
            <div class="f-pills">
                <?php foreach (['all' => 'All', 'win' => 'Win', 'lose' => 'Lose', 'sold' => 'Sold', 'pending' => 'Pending'] as $k => $l): ?>
                    <button class="pill <?= $active_filter === $k ? 'active' : '' ?>"
                        data-filter="<?= $k ?>" onclick="thF('<?= $k ?>')"><?= $l ?></button>
                <?php endforeach; ?>
            </div>
            <select class="srt-sel" id="sortSel" onchange="thA()">
                <?php foreach (['newest' => 'Newest first', 'oldest' => 'Oldest first', 'stake_high' => 'Highest stake', 'stake_low' => 'Lowest stake', 'win_high' => 'Highest winning'] as $v => $l): ?>
                    <option value="<?= $v ?>" <?= $sort_by === $v ? 'selected' : '' ?>><?= $l ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <!-- TABLE / CARDS -->
    <div class="tbl-wrap">
        <!-- Desktop header -->
        <div class="tbl-head">
            <div class="th">Category</div>
            <div class="th">Question</div>
            <div class="th">Ans</div>
            <div class="th">Spent</div>
            <div class="th">Winning</div>
            <div class="th">Status</div>
            <div class="th">Date</div>
        </div>

        <?php if ($page_rows): foreach ($page_rows as $row):
                $wa  = isset($row->winning_amount) ? $row->winning_amount : 0;
                if ($row->result === 'win') {
                    $sc = 's-win';
                    $sl = 'Win';
                } elseif ($row->result === 'sold') {
                    $sc = 's-sold';
                    $sl = 'Sold';
                } elseif ($row->result === 'lose') {
                    $sc = 's-lose';
                    $sl = 'Lose';
                } else {
                    $sc = 's-pending';
                    $sl = 'Pending';
                }
                $cat = th_cat($row->category_name);
                $ts  = strtotime($row->created_at);
                $q   = htmlspecialchars($row->question);
        ?>

                <!-- Desktop row -->
                <div class="trow">
                    <div class="td">
                        <span class="cat cat-<?= $cat ?>">
                            <span class="cat-dot"></span>
                            <span><?= htmlspecialchars($row->category_name) ?></span>
                        </span>
                    </div>
                    <div class="td" style="overflow:hidden;min-width:0;">
                        <div class="q-wrap">
                            <span class="q-text" title="<?= $q ?>"><?= $q ?></span>
                        </div>
                    </div>
                    <div class="td"><span class="ans ans-<?= $row->answer ?>"><?= strtoupper($row->answer) ?></span></div>
                    <div class="td"><span class="stake-v">₹<?= number_format($row->stake_amount, 2) ?></span></div>
                    <div class="td"><span class="<?= $wa > 0 ? 'amt-p' : 'amt-n' ?>">₹<?= number_format($wa, 2) ?></span></div>
                    <div class="td"><span class="status <?= $sc ?>"><?= $sl ?></span></div>
                    <div class="td"><span class="date-v"><?= date('d M Y', $ts) ?><br><?= date('h:i A', $ts) ?></span></div>
                </div>

                <!-- Mobile card -->
                <div class="mob-card">
                    <div class="mob-card-inner">
                        <div class="mob-top">
                            <div class="mob-top-left">
                                <span class="cat cat-<?= $cat ?>">
                                    <span class="cat-dot"></span>
                                    <span><?= htmlspecialchars($row->category_name) ?></span>
                                </span>
                                <span class="ans ans-<?= $row->answer ?>"><?= strtoupper($row->answer) ?></span>
                            </div>
                            <span class="status <?= $sc ?>"><?= $sl ?></span>
                        </div>
                        <div class="mob-q"><?= $q ?></div>
                        <div class="mob-divider"></div>
                        <div class="mob-grid">
                            <div class="mob-field">
                                <span class="mob-lbl">Spent</span>
                                <span class="stake-v">₹<?= number_format($row->stake_amount, 2) ?></span>
                            </div>
                            <div class="mob-field">
                                <span class="mob-lbl">Winning</span>
                                <span class="<?= $wa > 0 ? 'amt-p' : 'amt-n' ?>">₹<?= number_format($wa, 2) ?></span>
                            </div>
                            <div class="mob-field" style="grid-column:1/-1;">
                                <span class="mob-lbl">Date</span>
                                <span class="date-v" style="font-size:11.5px"><?= date('d M Y, h:i A', $ts) ?></span>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endforeach;
        else: ?>
            <div class="empty-st">
                <div class="empty-ic">
                    <svg viewBox="0 0 22 22">
                        <circle cx="10" cy="10" r="8" />
                        <path d="M15 15l4 4" />
                    </svg>
                </div>
                <div class="empty-t">No results found</div>
                <div class="empty-s">Try adjusting your search or filter</div>
            </div>
        <?php endif; ?>

        <?php if ($total_filtered > 0): ?>
            <div class="pagi">
                <span class="pg-info">
                    Showing <strong><?= $start_num ?>–<?= $end_num ?></strong> of <strong><?= $total_filtered ?></strong>
                </span>
                <div class="pg-btns">
                    <?php $pu = "?{$bq}&pg="; ?>
                    <a href="<?= $pu . ($current_page - 1) ?>" class="pg-btn <?= $current_page <= 1 ? 'disabled' : '' ?>">&#8249;</a>
                    <?php
                    $sp = max(1, min($current_page - 3, $total_pages - 6));
                    $ep = min($total_pages, $sp + 6);
                    for ($i = $sp; $i <= $ep; $i++):
                    ?><a href="<?= $pu . $i ?>" class="pg-btn <?= $i === $current_page ? 'active' : '' ?>"><?= $i ?></a><?php endfor; ?>
                    <a href="<?= $pu . ($current_page + 1) ?>" class="pg-btn <?= $current_page >= $total_pages ? 'disabled' : '' ?>">&#8250;</a>
                </div>
            </div>
        <?php endif; ?>

    </div>

</div>

<script>
    (function() {
        function url(pg) {
            const p = new URLSearchParams(window.location.search);
            p.set('search', document.getElementById('searchInput').value);
            p.set('sort', document.getElementById('sortSel').value);
            p.set('pg', pg || 1);
            return '?' + p.toString();
        }
        let t;
        document.getElementById('searchInput').addEventListener('input', () => {
            clearTimeout(t);
            t = setTimeout(() => {
                window.location.href = url(1);
            }, 320);
        });
        window.thA = () => {
            window.location.href = url(1);
        };
        window.thF = f => {
            const p = new URLSearchParams(window.location.search);
            p.set('filter', f);
            p.set('pg', 1);
            window.location.href = '?' + p.toString();
        };
    })();
</script>