<?php
$wallet_balance   = isset($user->wallet_balance)  ? (float) $user->wallet_balance  : 12450.75;
$user_trade_count = isset($user_trade_count)       ? (int)   $user_trade_count      : 247;
$categories       = isset($categories) && is_array($categories) ? $categories : array();
$featured_categories = array_slice($categories, 0, 8);
$category_count   = count($categories) ?: 12;

/* ── emoji icon map ── */
function get_category_emoji(string $name): string {
    $name = strtolower(trim($name));
    $map  = [
        'cricket'       => '🏏',
        'ipl'           => '🏏',
        'football'      => '⚽',
        'soccer'        => '⚽',
        'fifa'          => '⚽',
        'basketball'    => '🏀',
        'nba'           => '🏀',
        'tennis'        => '🎾',
        'badminton'     => '🏸',
        'kabaddi'       => '🤼',
        'hockey'        => '🏒',
        'golf'          => '⛳',
        'boxing'        => '🥊',
        'wrestling'     => '🤼',
        'volleyball'    => '🏐',
        'baseball'      => '⚾',
        'rugby'         => '🏉',
        'swimming'      => '🏊',
        'athletics'     => '🏃',
        'cycling'       => '🚴',
        'sports'        => '🏆',
        'finance'       => '📈',
        'stock'         => '📊',
        'stocks'        => '📊',
        'market'        => '📈',
        'markets'       => '📈',
        'nifty'         => '📊',
        'sensex'        => '📊',
        'crypto'        => '₿',
        'bitcoin'       => '₿',
        'ethereum'      => '🔷',
        'economy'       => '🏦',
        'trading'       => '💹',
        'investment'    => '💰',
        'gold'          => '🥇',
        'oil'           => '🛢️',
        'share'         => '📊',
        'politics'      => '🏛️',
        'election'      => '🗳️',
        'elections'     => '🗳️',
        'government'    => '🏛️',
        'news'          => '📰',
        'bollywood'     => '🎬',
        'movies'        => '🎬',
        'movie'         => '🎬',
        'hollywood'     => '🎬',
        'tv'            => '📺',
        'web series'    => '🎭',
        'music'         => '🎵',
        'gaming'        => '🎮',
        'esports'       => '🎮',
        'celebrity'     => '⭐',
        'entertainment' => '🎭',
        'technology'    => '💻',
        'tech'          => '💻',
        'science'       => '🔬',
        'ai'            => '🤖',
        'space'         => '🚀',
        'business'      => '💼',
        'startup'       => '🌱',
        'startups'      => '🌱',
        'companies'     => '🏢',
        'health'        => '❤️',
        'fitness'       => '💪',
        'food'          => '🍽️',
        'travel'        => '✈️',
        'fashion'       => '👗',
        'education'     => '🎓',
        'environment'   => '🌿',
        'weather'       => '☀️',
        'awards'        => '🏅',
        'award'         => '🏅',
        'oscars'        => '🏆',
        'international' => '🌍',
        'world'         => '🌍',
        'india'         => '🇮🇳',
    ];
    foreach ($map as $keyword => $emoji) {
        if (strpos($name, $keyword) !== false) return $emoji;
    }
    return '⚡';
}

/* category accent colors */
$cat_colors = [
    ['bg' => '#fff3e0', 'border' => '#ff9800', 'text' => '#e65100', 'icon_bg' => '#ff980020'],
    ['bg' => '#e3f2fd', 'border' => '#2196f3', 'text' => '#0d47a1', 'icon_bg' => '#2196f320'],
    ['bg' => '#e8f5e9', 'border' => '#4caf50', 'text' => '#1b5e20', 'icon_bg' => '#4caf5020'],
    ['bg' => '#fce4ec', 'border' => '#e91e63', 'text' => '#880e4f', 'icon_bg' => '#e91e6320'],
    ['bg' => '#ede7f6', 'border' => '#7c3aed', 'text' => '#4527a0', 'icon_bg' => '#7c3aed20'],
    ['bg' => '#e0f7fa', 'border' => '#00bcd4', 'text' => '#006064', 'icon_bg' => '#00bcd420'],
    ['bg' => '#fff8e1', 'border' => '#ffc107', 'text' => '#ff6f00', 'icon_bg' => '#ffc10720'],
    ['bg' => '#f3e5f5', 'border' => '#9c27b0', 'text' => '#4a148c', 'icon_bg' => '#9c27b020'],
];
?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Baloo+2:wght@600;700;800&display=swap" rel="stylesheet">

<style>
/* ═══════════ VARIABLES ═══════════ */
:root {
    --white: #ffffff;
    --bg: #f0f4fc;
    --surface: #ffffff;
    --surface2: #f8f9ff;
    --border: #e4e9f5;
    --text-1: #1a1f36;
    --text-2: #5a6282;
    --text-3: #9099b5;

    --blue: #3b82f6;
    --blue-dark: #1d4ed8;
    --blue-light: #eff6ff;
    --green: #10b981;
    --green-light: #ecfdf5;
    --orange: #f97316;
    --orange-light: #fff7ed;
    --red: #ef4444;
    --purple: #8b5cf6;
    --gold: #f59e0b;
    --gold-light: #fffbeb;

    --shadow-sm: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
    --shadow-md: 0 4px 16px rgba(0,0,0,0.08), 0 2px 4px rgba(0,0,0,0.04);
    --shadow-lg: 0 10px 40px rgba(0,0,0,0.10), 0 4px 8px rgba(0,0,0,0.05);

    --r-xl: 24px;
    --r-lg: 18px;
    --r-md: 14px;
    --r-sm: 10px;

    --font-display: 'Baloo 2', cursive;
    --font-body: 'Nunito', sans-serif;
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.pd-root {
    font-family: var(--font-body);
    background: var(--bg);
    color: var(--text-1);
    padding: 20px 16px 40px;
}

.pd-wrap {
    max-width: 1240px;
    margin: 0 auto;
    display: grid;
    gap: 20px;
}

/* ═══════════ HERO ═══════════ */
.pd-hero {
    position: relative;
    overflow: hidden;
    border-radius: var(--r-xl);
    background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 45%, #0ea5e9 80%, #06b6d4 100%);
    padding: 36px 36px 34px;
    box-shadow: 0 20px 60px rgba(37,99,235,0.35);
}

.pd-hero::before {
    content: '';
    position: absolute;
    width: 360px; height: 360px;
    border-radius: 50%;
    background: rgba(255,255,255,0.07);
    top: -130px; right: -90px;
    pointer-events: none;
}
.pd-hero::after {
    content: '';
    position: absolute;
    width: 200px; height: 200px;
    border-radius: 50%;
    background: rgba(255,255,255,0.05);
    bottom: -70px; left: 35%;
    pointer-events: none;
}

.pd-hero-dots {
    position: absolute; inset: 0;
    background-image: radial-gradient(circle, rgba(255,255,255,0.08) 1.5px, transparent 1.5px);
    background-size: 28px 28px;
    pointer-events: none;
}

/* Hero content — single column (wallet commented out) */
.pd-hero-inner {
    position: relative;
    z-index: 2;
}

.pd-live-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255,255,255,0.15);
    border: 1px solid rgba(255,255,255,0.25);
    border-radius: 999px;
    padding: 6px 16px;
    font-size: 12px;
    font-weight: 700;
    color: #fff;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    margin-bottom: 18px;
    width: fit-content;
}

.pd-live-dot {
    width: 8px; height: 8px;
    background: #4ade80;
    border-radius: 50%;
    box-shadow: 0 0 0 3px rgba(74,222,128,0.35);
    animation: blink 1.3s ease-in-out infinite;
}

@keyframes blink {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: .6; transform: scale(.8); }
}

.pd-hero-h1 {
    font-family: var(--font-display);
    font-size: clamp(30px, 5vw, 60px);
    font-weight: 800;
    line-height: 1.05;
    color: #fff;
    text-shadow: 0 2px 12px rgba(0,0,0,0.15);
}
.pd-hero-h1 .accent {
    color: #fde68a;
    text-shadow: 0 0 20px rgba(253,230,138,0.50);
}

.pd-hero-sub {
    margin-top: 14px;
    font-size: 16px;
    color: rgba(255,255,255,0.82);
    line-height: 1.75;
    max-width: 600px;
}

/* Hero quick stats row */
.pd-hero-stats {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 22px;
}

.pd-hero-stat {
    display: flex;
    align-items: center;
    gap: 8px;
    background: rgba(255,255,255,0.14);
    border: 1px solid rgba(255,255,255,0.22);
    border-radius: var(--r-sm);
    padding: 9px 16px;
    font-size: 13px;
    font-weight: 700;
    color: #fff;
    backdrop-filter: blur(4px);
}
.pd-hero-stat .e { font-size: 17px; }

/* CTA buttons */
.pd-hero-btns {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 26px;
}

.pd-btn-main {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    padding: 14px 30px;
    border-radius: var(--r-md);
    font-family: var(--font-display);
    font-size: 16px;
    font-weight: 800;
    text-decoration: none;
    color: #1e3a8a;
    background: #fde68a;
    box-shadow: 0 8px 24px rgba(253,230,138,0.50), inset 0 1px 0 rgba(255,255,255,0.60);
    transition: transform .18s, box-shadow .18s;
}
.pd-btn-main:hover { transform: translateY(-3px) scale(1.02); box-shadow: 0 14px 36px rgba(253,230,138,0.65); }

.pd-btn-ghost {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    padding: 14px 30px;
    border-radius: var(--r-md);
    font-family: var(--font-display);
    font-size: 16px;
    font-weight: 800;
    text-decoration: none;
    color: #fff;
    background: rgba(255,255,255,0.14);
    border: 1.5px solid rgba(255,255,255,0.35);
    backdrop-filter: blur(4px);
    transition: transform .18s, background .18s;
}
.pd-btn-ghost:hover { transform: translateY(-3px); background: rgba(255,255,255,0.22); }

/* ═══════════ QUICK STATS BAR ═══════════ */
.pd-statsbar {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
}

.pd-statcard {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--r-lg);
    padding: 20px 22px;
    box-shadow: var(--shadow-md);
    display: flex;
    align-items: center;
    gap: 14px;
    text-decoration: none;
    transition: transform .18s, box-shadow .18s;
}
.pd-statcard:hover { transform: translateY(-3px); box-shadow: var(--shadow-lg); }

.pd-statcard-icon {
    width: 48px; height: 48px;
    border-radius: var(--r-md);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
}

.pd-statcard-val {
    font-family: var(--font-display);
    font-size: 22px;
    font-weight: 800;
    color: var(--text-1);
    line-height: 1;
}
.pd-statcard-lbl {
    margin-top: 4px;
    font-size: 12px;
    font-weight: 600;
    color: var(--text-3);
}

/* ═══════════ CHALLENGE BANNER ═══════════ */
.pd-challenge {
    position: relative;
    overflow: hidden;
    border-radius: var(--r-xl);
    background: linear-gradient(120deg, #fff7ed 0%, #fffbeb 50%, #fff3e0 100%);
    border: 2px solid #fed7aa;
    padding: 28px 32px;
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 24px;
    align-items: center;
    box-shadow: 0 8px 32px rgba(249,115,22,0.15);
}

.pd-challenge::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(100deg, transparent 30%, rgba(255,255,255,.60) 50%, transparent 70%);
    animation: shimmer 3.5s linear infinite;
    pointer-events: none;
}
@keyframes shimmer {
    0% { transform: translateX(-100%); }
    60% { transform: translateX(110%); }
    100% { transform: translateX(110%); }
}

.pd-challenge-left { position: relative; z-index: 2; }

.pd-challenge-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: #fed7aa;
    border-radius: 999px;
    padding: 5px 13px;
    font-size: 11px;
    font-weight: 800;
    color: #c2410c;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    margin-bottom: 14px;
}

.pd-challenge-headline {
    font-family: var(--font-display);
    font-size: clamp(24px, 4vw, 46px);
    font-weight: 800;
    line-height: 1.05;
    color: #1c1917;
}
.pd-challenge-headline .amt { color: #ea580c; }
.pd-challenge-headline .earn { color: #16a34a; }

.pd-challenge-desc {
    margin-top: 12px;
    font-size: 15px;
    color: #78716c;
    line-height: 1.75;
    max-width: 560px;
}
.pd-challenge-desc strong { color: #c2410c; font-weight: 800; }

.pd-challenge-steps {
    display: flex;
    gap: 12px;
    margin-top: 18px;
    flex-wrap: wrap;
}
.pd-challenge-step {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #fff;
    border: 1.5px solid #fed7aa;
    border-radius: var(--r-sm);
    padding: 9px 14px;
    font-size: 13px;
    font-weight: 700;
    color: #9a3412;
    box-shadow: var(--shadow-sm);
}
.pd-challenge-step .step-num {
    width: 24px; height: 24px;
    background: linear-gradient(135deg, #fb923c, #f97316);
    border-radius: 50%;
    color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; font-weight: 900; flex-shrink: 0;
}

.pd-challenge-right {
    position: relative; z-index: 2;
    display: flex; flex-direction: column;
    align-items: center; gap: 14px; flex-shrink: 0;
}

.pd-challenge-trophy {
    font-size: 68px;
    line-height: 1;
    filter: drop-shadow(0 4px 12px rgba(249,115,22,0.40));
    animation: trophy-bounce 2s ease-in-out infinite;
}
@keyframes trophy-bounce {
    0%, 100% { transform: translateY(0) rotate(-3deg); }
    50% { transform: translateY(-8px) rotate(3deg); }
}

.pd-challenge-cta {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 15px 28px;
    border-radius: var(--r-md);
    font-family: var(--font-display);
    font-size: 16px;
    font-weight: 800;
    text-decoration: none;
    color: #fff;
    background: linear-gradient(135deg, #f97316, #ea580c);
    box-shadow: 0 8px 28px rgba(249,115,22,0.45);
    white-space: nowrap;
    transition: transform .18s, box-shadow .18s;
    position: relative; overflow: hidden;
}
.pd-challenge-cta::before {
    content: '';
    position: absolute;
    top: 0; left: -100%; width: 60%; height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.25), transparent);
    animation: btn-flash 2.8s linear infinite;
}
@keyframes btn-flash {
    0% { left: -100%; }
    55% { left: 120%; }
    100% { left: 120%; }
}
.pd-challenge-cta:hover { transform: translateY(-4px) scale(1.03); box-shadow: 0 16px 44px rgba(249,115,22,0.55); }

/* ═══════════ MAIN GRID ═══════════ */
.pd-main-grid {
    display: grid;
    grid-template-columns: 1.1fr 0.9fr;
    gap: 20px;
}
.pd-col { display: grid; gap: 20px; align-content: start; }

/* ── Generic card ── */
.pd-card {
    background: var(--surface);
    border-radius: var(--r-xl);
    border: 1px solid var(--border);
    box-shadow: var(--shadow-md);
    overflow: hidden;
}

.pd-card-hd {
    padding: 22px 24px 0;
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 12px;
}
.pd-card-hd-left { flex: 1; }

.pd-card-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-family: var(--font-display);
    font-size: 19px;
    font-weight: 800;
    color: var(--text-1);
}
.pd-card-title .ico {
    width: 36px; height: 36px;
    border-radius: var(--r-sm);
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; flex-shrink: 0;
}
.pd-card-sub {
    margin-top: 5px;
    font-size: 13px;
    color: var(--text-3);
    line-height: 1.6;
}

.pd-see-all {
    font-size: 12px;
    font-weight: 800;
    color: var(--blue);
    text-decoration: none;
    background: var(--blue-light);
    border-radius: 999px;
    padding: 5px 12px;
    white-space: nowrap;
    transition: background .15s;
    flex-shrink: 0;
}
.pd-see-all:hover { background: #dbeafe; }

.pd-card-bd { padding: 18px 24px 24px; }

/* ═══════════ ARENA PLAY CARDS ═══════════ */
.pd-arena-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
}

.pd-arena {
    border-radius: var(--r-lg);
    padding: 22px 20px;
    min-height: 190px;
    display: flex; flex-direction: column;
    text-decoration: none;
    position: relative; overflow: hidden;
    transition: transform .20s, box-shadow .20s;
    border: 1.5px solid transparent;
}
.pd-arena:hover { transform: translateY(-5px); }

.pd-arena-live {
    background: linear-gradient(140deg, #eff6ff 0%, #dbeafe 50%, #bfdbfe 100%);
    border-color: #93c5fd;
    box-shadow: 0 4px 20px rgba(59,130,246,0.14);
}
.pd-arena-live:hover { box-shadow: 0 12px 40px rgba(59,130,246,0.25); }

.pd-arena-earn {
    background: linear-gradient(140deg, #ecfdf5 0%, #d1fae5 50%, #a7f3d0 100%);
    border-color: #6ee7b7;
    box-shadow: 0 4px 20px rgba(16,185,129,0.14);
}
.pd-arena-earn:hover { box-shadow: 0 12px 40px rgba(16,185,129,0.25); }

.pd-arena-emoji { font-size: 36px; line-height: 1; margin-bottom: 12px; }

.pd-arena-pill {
    display: inline-flex;
    align-items: center; gap: 6px;
    border-radius: 999px;
    padding: 4px 10px;
    font-size: 10px; font-weight: 800;
    letter-spacing: 0.12em; text-transform: uppercase;
    width: fit-content; margin-bottom: 10px;
}
.pd-arena-live .pd-arena-pill { background: #bfdbfe; color: #1d4ed8; }
.pd-arena-earn .pd-arena-pill { background: #a7f3d0; color: #065f46; }

.pd-arena-title {
    font-family: var(--font-display);
    font-size: 20px; font-weight: 800;
    line-height: 1.15; flex: 1;
}
.pd-arena-live .pd-arena-title { color: #1e3a8a; }
.pd-arena-earn .pd-arena-title { color: #064e3b; }

.pd-arena-foot {
    display: flex; align-items: center;
    justify-content: space-between;
    margin-top: 14px;
}
.pd-arena-cta {
    font-size: 13px; font-weight: 800;
    display: flex; align-items: center; gap: 6px;
}
.pd-arena-live .pd-arena-cta { color: #2563eb; }
.pd-arena-earn .pd-arena-cta { color: #059669; }

.pd-arena-icon {
    width: 32px; height: 32px;
    border-radius: var(--r-sm);
    display: flex; align-items: center; justify-content: center;
    font-size: 14px;
}
.pd-arena-live .pd-arena-icon { background: #bfdbfe; color: #1d4ed8; }
.pd-arena-earn .pd-arena-icon { background: #a7f3d0; color: #059669; }

/* ═══════════ CATEGORY GAME ROOMS ═══════════ */
.pd-cat-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}

.pd-cat {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px;
    border-radius: var(--r-md);
    text-decoration: none;
    border: 1.5px solid transparent;
    transition: transform .18s, box-shadow .18s;
    position: relative;
    overflow: hidden;
    min-width: 0; /* allow flex shrink */
}
.pd-cat:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-md);
}

/* lone last item spans full row */
.pd-cat:last-child:nth-child(odd) {
    grid-column: 1 / -1;
    max-width: 50%;
    margin: 0 auto;
    width: 100%;
    max-width: calc(50% - 6px);
    align-self: start;
}

.pd-cat-emoji-wrap {
    width: 44px; height: 44px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; flex-shrink: 0;
}

/* wrapper so text can shrink properly */
.pd-cat-text {
    min-width: 0;
    flex: 1;
}

.pd-cat-name {
    font-size: 13px;
    font-weight: 800;
    line-height: 1.3;
    word-break: break-word;
    white-space: normal;
    overflow-wrap: anywhere;
}
.pd-cat-count {
    margin-top: 3px;
    font-size: 11px;
    font-weight: 600;
    color: var(--text-3);
    white-space: nowrap;
}

.pd-empty {
    grid-column: 1/-1;
    padding: 28px; text-align: center;
    color: var(--text-3);
    background: var(--surface2);
    border: 1.5px dashed var(--border);
    border-radius: var(--r-md);
    font-size: 14px;
}

/* ═══════════ PLAYER HUB ═══════════ */
.pd-hub-list { display: grid; gap: 10px; }

.pd-hub-item {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 15px 16px;
    border-radius: var(--r-md);
    text-decoration: none;
    border: 1.5px solid var(--border);
    background: var(--surface2);
    transition: transform .18s, box-shadow .18s, border-color .18s, background .18s;
}
.pd-hub-item:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-md);
    border-color: #93c5fd;
    background: var(--blue-light);
}
.pd-hub-icon {
    width: 44px; height: 44px;
    border-radius: var(--r-md);
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; flex-shrink: 0;
}
.pd-hub-info { flex: 1; }
.pd-hub-title { font-size: 14px; font-weight: 800; color: var(--text-1); }
.pd-hub-sub { margin-top: 3px; font-size: 12px; color: var(--text-3); line-height: 1.5; }
.pd-hub-chevron { font-size: 18px; color: var(--text-3); flex-shrink: 0; font-weight: 700; }

/* ═══════════ WIN PATH ═══════════ */
.pd-steps { display: grid; gap: 0; position: relative; }
.pd-steps::before {
    content: '';
    position: absolute;
    left: 21px; top: 46px; bottom: 46px; width: 2px;
    background: linear-gradient(180deg, #3b82f6, #10b981, #8b5cf6);
    opacity: .25;
}

.pd-step {
    display: flex; gap: 16px;
    align-items: flex-start;
    padding: 16px 14px;
    border-radius: var(--r-md);
    transition: background .15s;
}
.pd-step:hover { background: var(--surface2); }

.pd-step-num {
    width: 42px; height: 42px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-family: var(--font-display);
    font-size: 16px; font-weight: 900;
    flex-shrink: 0; position: relative; z-index: 2;
}
.pd-step:nth-child(1) .pd-step-num { background: var(--blue-light); color: var(--blue); border: 2px solid #bfdbfe; }
.pd-step:nth-child(2) .pd-step-num { background: var(--green-light); color: var(--green); border: 2px solid #a7f3d0; }
.pd-step:nth-child(3) .pd-step-num { background: #ede9fe; color: var(--purple); border: 2px solid #c4b5fd; }

.pd-step-title { font-size: 14px; font-weight: 800; color: var(--text-1); margin-top: 10px; }
.pd-step-desc { margin-top: 4px; font-size: 12px; color: var(--text-3); line-height: 1.65; }

/* ═══════════ REFERRAL FOOTER ═══════════ */
.pd-referral {
    position: relative; overflow: hidden;
    border-radius: var(--r-xl);
    background: linear-gradient(120deg, #fdf4ff 0%, #fce7f3 40%, #ede9fe 100%);
    border: 2px solid #e9d5ff;
    padding: 28px 32px;
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 24px;
    align-items: center;
    text-decoration: none;
    box-shadow: 0 8px 32px rgba(139,92,246,0.12);
    transition: box-shadow .20s, transform .20s;
}
.pd-referral:hover { transform: translateY(-3px); box-shadow: 0 16px 48px rgba(139,92,246,0.22); }
.pd-referral::before {
    content: '🎁';
    position: absolute;
    right: 240px; top: 50%;
    transform: translateY(-50%);
    font-size: 80px; opacity: .10;
    pointer-events: none;
}

.pd-ref-eyebrow {
    font-size: 11px; font-weight: 800;
    letter-spacing: 0.18em; text-transform: uppercase;
    color: var(--purple); margin-bottom: 10px;
    display: flex; align-items: center; gap: 8px;
}
.pd-ref-eyebrow::before {
    content: ''; display: inline-block;
    width: 18px; height: 2px;
    background: var(--purple); border-radius: 1px;
}

.pd-ref-title {
    font-family: var(--font-display);
    font-size: clamp(22px, 3vw, 34px);
    font-weight: 800; color: #1c1917; line-height: 1.1;
}
.pd-ref-title span {
    background: linear-gradient(90deg, #7c3aed, #db2777);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.pd-ref-desc {
    margin-top: 10px; color: var(--text-2);
    font-size: 14px; line-height: 1.7; max-width: 580px;
}

.pd-ref-chips {
    display: flex; flex-wrap: wrap;
    gap: 9px; margin-top: 14px;
}
.pd-ref-chip {
    display: flex; align-items: center; gap: 7px;
    padding: 7px 13px;
    border-radius: var(--r-sm);
    background: #fff;
    border: 1.5px solid #e9d5ff;
    font-size: 13px; font-weight: 700; color: #6d28d9;
    box-shadow: var(--shadow-sm);
}

.pd-ref-cta {
    display: inline-flex; align-items: center; gap: 10px;
    padding: 16px 28px; border-radius: var(--r-md);
    font-family: var(--font-display);
    font-size: 17px; font-weight: 800;
    color: #fff;
    background: linear-gradient(135deg, #7c3aed, #9333ea);
    box-shadow: 0 8px 28px rgba(124,58,237,0.40), inset 0 1px 0 rgba(255,255,255,0.20);
    text-decoration: none; white-space: nowrap; flex-shrink: 0;
    transition: transform .18s, box-shadow .18s;
}
.pd-ref-cta:hover { transform: translateY(-4px) scale(1.03); box-shadow: 0 16px 44px rgba(124,58,237,0.55); }

/* ═══════════ ANIMATIONS ═══════════ */
@keyframes fadeUp {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
}
.pa { animation: fadeUp .50s ease both; }
.pa-1 { animation-delay: .04s; }
.pa-2 { animation-delay: .10s; }
.pa-3 { animation-delay: .16s; }
.pa-4 { animation-delay: .22s; }
.pa-5 { animation-delay: .30s; }

/* ═══════════ RESPONSIVE ═══════════ */
@media (max-width: 1080px) {
    .pd-main-grid { grid-template-columns: 1fr; }
}

@media (max-width: 768px) {
    .pd-root { padding: 12px 12px 28px; }
    .pd-hero { padding: 24px 20px 26px; }
    .pd-statsbar { grid-template-columns: repeat(3, 1fr); gap: 10px; }
    .pd-statcard { padding: 14px 12px; flex-direction: column; text-align: center; gap: 8px; }
    .pd-statcard-icon { width: 40px; height: 40px; font-size: 18px; }
    .pd-statcard-val { font-size: 18px; }
    .pd-arena-grid { grid-template-columns: 1fr 1fr; gap: 10px; }
    .pd-challenge { grid-template-columns: 1fr; }
    .pd-challenge-right { flex-direction: row; align-items: center; justify-content: space-between; }
    .pd-challenge-trophy { font-size: 54px; }
    .pd-referral { grid-template-columns: 1fr; }
    .pd-ref-cta { width: 100%; justify-content: center; }
    .pd-cat-grid { grid-template-columns: 1fr 1fr; gap: 10px; }
}

@media (max-width: 520px) {
    .pd-hero { padding: 20px 16px 22px; }
    .pd-hero-btns { flex-direction: column; }
    .pd-btn-main, .pd-btn-ghost { width: 100%; justify-content: center; }
    .pd-statsbar { grid-template-columns: repeat(3, 1fr); gap: 8px; }
    .pd-statcard { padding: 12px 8px; }
    .pd-statcard-val { font-size: 16px; }
    .pd-statcard-lbl { font-size: 10px; }
    .pd-arena-grid { grid-template-columns: 1fr; gap: 10px; }
    .pd-cat-grid { grid-template-columns: 1fr 1fr; }
    .pd-challenge { padding: 20px 18px; }
    .pd-challenge-steps { gap: 8px; }
    .pd-challenge-step { font-size: 12px; padding: 7px 10px; }
    .pd-card-hd, .pd-card-bd { padding-left: 16px; padding-right: 16px; }
    .pd-referral { padding: 22px 18px; }
    .pd-ref-chips { gap: 7px; }
    .pd-ref-chip { font-size: 12px; padding: 6px 10px; }
}
</style>

<div class="pd-root">
    <div class="pd-wrap">

        <!-- ══ HERO ══ -->
        <section class="pd-hero pa pa-1">
            <div class="pd-hero-dots"></div>
            <div class="pd-hero-inner">
                <div class="pd-live-badge">
                    <span class="pd-live-dot"></span>
                    Live Arena · <?php echo number_format($user_trade_count); ?> battles today
                </div>

                <h1 class="pd-hero-h1">
                    Answer Questions. <span class="accent">Win Real Money. 🏆</span>
                </h1>
                <p class="pd-hero-sub">
                    Pick a category, answer live questions, and earn cash rewards. The smarter your prediction, the bigger your win.
                </p>

                <div class="pd-hero-stats">
                    <div class="pd-hero-stat"><span class="e">👥</span> 50,000+ Players</div>
                    <div class="pd-hero-stat"><span class="e">⚡</span> <?php echo number_format($category_count); ?> Live Rooms</div>
                    <div class="pd-hero-stat"><span class="e">💰</span> ₹10L+ Paid Out</div>
                </div>

                <div class="pd-hero-btns">
                    <a class="pd-btn-main" href="<?php echo site_url('questions'); ?>">▶ Start Answering</a>
                    <a class="pd-btn-ghost" href="<?php echo site_url('wallet/add_balance'); ?>">⚡ Add Balance</a>
                </div>
            </div>
        </section>

        <!-- ══ WALLET SECTION (COMMENTED OUT) ══
        <div class="pd-wallet-section pa pa-2">
            <div class="pd-wallet">
                <div class="pd-wallet-label">💳 Wallet Balance</div>
                <div class="pd-wallet-balance"><span>₹</span><?php echo number_format($wallet_balance, 2); ?></div>
                <div class="pd-wallet-hr"></div>
                <div class="pd-wallet-row">
                    <div class="pd-wallet-stat">
                        <div class="wlbl">Battles</div>
                        <div class="wval"><?php echo number_format($user_trade_count); ?></div>
                    </div>
                    <div class="pd-wallet-stat">
                        <div class="wlbl">Rooms</div>
                        <div class="wval"><?php echo number_format($category_count); ?></div>
                    </div>
                </div>
                <div class="pd-wallet-btns">
                    <a class="pd-wbtn pd-wbtn-dep" href="<?php echo site_url('wallet/add_balance'); ?>">+ Deposit</a>
                    <a class="pd-wbtn pd-wbtn-with" href="<?php echo site_url('wallet'); ?>">↑ Withdraw</a>
                </div>
            </div>
        </div>
        END WALLET SECTION ══ -->

        <!-- ══ QUICK STATS BAR ══ -->
        <div class="pd-statsbar pa pa-2">
            <a class="pd-statcard" href="<?php echo site_url('wallet'); ?>">
                <div class="pd-statcard-icon" style="background:#ecfdf5;">💳</div>
                <div>
                    <div class="pd-statcard-val">₹<?php echo number_format($wallet_balance, 0); ?></div>
                    <div class="pd-statcard-lbl">Wallet Balance</div>
                </div>
            </a>
            <a class="pd-statcard" href="<?php echo site_url('trade_history'); ?>">
                <div class="pd-statcard-icon" style="background:#eff6ff;">⚔️</div>
                <div>
                    <div class="pd-statcard-val"><?php echo number_format($user_trade_count); ?></div>
                    <div class="pd-statcard-lbl">Total Battles</div>
                </div>
            </a>
            <a class="pd-statcard" href="<?php echo site_url('questions'); ?>">
                <div class="pd-statcard-icon" style="background:#fff7ed;">🏟️</div>
                <div>
                    <div class="pd-statcard-val"><?php echo number_format($category_count); ?></div>
                    <div class="pd-statcard-lbl">Live Rooms</div>
                </div>
            </a>
        </div>

        <!-- ══ CHALLENGE BANNER ══ -->
        <div class="pd-challenge pa pa-3">
            <div class="pd-challenge-left">
                <div class="pd-challenge-eyebrow">🔥 Today's Challenge</div>
                <div class="pd-challenge-headline">
                    Add <span class="amt">₹50</span>, Answer &amp; Win <span class="earn">₹500!</span>
                </div>
                <div class="pd-challenge-desc">
                    Top up <strong>₹50</strong> to your wallet, jump into any live question room, and answer correctly to win up to <strong>₹500</strong> in real cash.
                </div>
                <div class="pd-challenge-steps">
                    <div class="pd-challenge-step"><span class="step-num">1</span> Add ₹50 to wallet</div>
                    <div class="pd-challenge-step"><span class="step-num">2</span> Pick a live question</div>
                    <div class="pd-challenge-step"><span class="step-num">3</span> Answer &amp; win ₹500</div>
                </div>
            </div>
            <div class="pd-challenge-right">
                <div class="pd-challenge-trophy">🏆</div>
                <a class="pd-challenge-cta" href="<?php echo site_url('wallet/add_balance'); ?>">⚡ Add ₹50 &amp; Play</a>
            </div>
        </div>

        <!-- ══ MAIN GRID ══ -->
        <div class="pd-main-grid pa pa-4">

            <!-- LEFT -->
            <div class="pd-col">

                <!-- Play Arena -->
                <div class="pd-card">
                    <div class="pd-card-hd">
                        <div class="pd-card-hd-left">
                            <div class="pd-card-title">
                                <span class="ico" style="background:#eff6ff; color:#2563eb;">🎮</span>
                                Play Arena
                            </div>
                            <div class="pd-card-sub">Choose your mode and start earning right now.</div>
                        </div>
                        <a class="pd-see-all" href="<?php echo site_url('questions'); ?>">View All →</a>
                    </div>
                    <div class="pd-card-bd">
                        <div class="pd-arena-grid">
                            <a class="pd-arena pd-arena-live" href="<?php echo site_url('questions'); ?>">
                                <div class="pd-arena-emoji">⚡</div>
                                <div class="pd-arena-pill">● Live Now</div>
                                <div class="pd-arena-title">Live Questions</div>
                                <div class="pd-arena-foot">
                                    <span class="pd-arena-cta">Answer Now →</span>
                                    <span class="pd-arena-icon">→</span>
                                </div>
                            </a>
                            <a class="pd-arena pd-arena-earn" href="<?php echo site_url('wallet/add_balance'); ?>">
                                <div class="pd-arena-emoji">💸</div>
                                <div class="pd-arena-pill">🔥 Win ₹500</div>
                                <div class="pd-arena-title">Add ₹50,<br>Win ₹500</div>
                                <div class="pd-arena-foot">
                                    <span class="pd-arena-cta">Grab Now →</span>
                                    <span class="pd-arena-icon">₹</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Game Rooms / Categories -->
                <div class="pd-card">
                    <div class="pd-card-hd">
                        <div class="pd-card-hd-left">
                            <div class="pd-card-title">
                                <span class="ico" style="background:#fffbeb; color:#d97706;">🏟️</span>
                                Game Rooms
                            </div>
                            <div class="pd-card-sub">Pick your arena — every room has live questions.</div>
                        </div>
                        <a class="pd-see-all" href="<?php echo site_url('questions'); ?>">All Rooms →</a>
                    </div>
                    <div class="pd-card-bd">
                        <div class="pd-cat-grid">
                            <?php if (!empty($featured_categories)): ?>
                                <?php foreach ($featured_categories as $i => $cat):
                                    $emoji = get_category_emoji($cat->name);
                                    $c     = $cat_colors[$i % 8];
                                ?>
                                    <a class="pd-cat"
                                       href="<?php echo site_url('questions?category_id=' . (int)$cat->id); ?>"
                                       style="border-color:<?php echo $c['border']; ?>; background:<?php echo $c['bg']; ?>;">
                                        <div class="pd-cat-emoji-wrap" style="background:<?php echo $c['icon_bg']; ?>;">
                                            <?php echo $emoji; ?>
                                        </div>
                                        <div class="pd-cat-text">
                                            <div class="pd-cat-name" style="color:<?php echo $c['text']; ?>;"><?php echo html_escape($cat->name); ?></div>
                                            <div class="pd-cat-count"><?php echo (int)$cat->question_count; ?> live questions</div>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <!-- Demo categories when none exist -->
                                <?php
                                $demo_cats = [
                                    ['name'=>'Cricket', 'count'=>12],
                                    ['name'=>'Gold', 'count'=>5],
                                    ['name'=>'Crypto', 'count'=>8],
                                    ['name'=>'IPL 2026', 'count'=>15],
                                    ['name'=>'Share Market', 'count'=>7],
                                    ['name'=>'Politics', 'count'=>6],
                                    ['name'=>'Football', 'count'=>9],
                                    ['name'=>'Bollywood', 'count'=>4],
                                ];
                                foreach ($demo_cats as $di => $dc):
                                    $emoji = get_category_emoji($dc['name']);
                                    $c     = $cat_colors[$di % 8];
                                ?>
                                    <a class="pd-cat"
                                       href="<?php echo site_url('questions'); ?>"
                                       style="border-color:<?php echo $c['border']; ?>; background:<?php echo $c['bg']; ?>;">
                                        <div class="pd-cat-emoji-wrap" style="background:<?php echo $c['icon_bg']; ?>;">
                                            <?php echo $emoji; ?>
                                        </div>
                                        <div class="pd-cat-text">
                                            <div class="pd-cat-name" style="color:<?php echo $c['text']; ?>;"><?php echo $dc['name']; ?></div>
                                            <div class="pd-cat-count"><?php echo $dc['count']; ?> live questions</div>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div><!-- /left -->

            <!-- RIGHT -->
            <div class="pd-col">

                <!-- Player Hub -->
                <div class="pd-card">
                    <div class="pd-card-hd">
                        <div class="pd-card-hd-left">
                            <div class="pd-card-title">
                                <span class="ico" style="background:#ede9fe; color:#7c3aed;">🧑‍🚀</span>
                                Player Hub
                            </div>
                            <div class="pd-card-sub">Manage your account &amp; rewards.</div>
                        </div>
                    </div>
                    <div class="pd-card-bd">
                        <div class="pd-hub-list">
                            <a class="pd-hub-item" href="<?php echo site_url('wallet'); ?>">
                                <div class="pd-hub-icon" style="background:#eff6ff;">💳</div>
                                <div class="pd-hub-info">
                                    <div class="pd-hub-title">Wallet</div>
                                    <div class="pd-hub-sub">Balance, deposits &amp; withdrawals</div>
                                </div>
                                <span class="pd-hub-chevron">›</span>
                            </a>
                            <a class="pd-hub-item" href="<?php echo site_url('trade_history'); ?>">
                                <div class="pd-hub-icon" style="background:#ecfdf5;">📊</div>
                                <div class="pd-hub-info">
                                    <div class="pd-hub-title">My Answers &amp; Earnings</div>
                                    <div class="pd-hub-sub">View your prediction history &amp; winnings</div>
                                </div>
                                <span class="pd-hub-chevron">›</span>
                            </a>
                            <a class="pd-hub-item" href="<?php echo site_url('profile'); ?>">
                                <div class="pd-hub-icon" style="background:#fdf4ff;">🪪</div>
                                <div class="pd-hub-info">
                                    <div class="pd-hub-title">My Profile</div>
                                    <div class="pd-hub-sub">Avatar, details, password &amp; KYC</div>
                                </div>
                                <span class="pd-hub-chevron">›</span>
                            </a>
                            <a class="pd-hub-item" href="<?php echo site_url('referral'); ?>">
                                <div class="pd-hub-icon" style="background:#fff7ed;">🎁</div>
                                <div class="pd-hub-info">
                                    <div class="pd-hub-title">Referral Rewards</div>
                                    <div class="pd-hub-sub">Invite friends &amp; earn instant cash</div>
                                </div>
                                <span class="pd-hub-chevron">›</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- How To Win -->
                <div class="pd-card">
                    <div class="pd-card-hd">
                        <div class="pd-card-hd-left">
                            <div class="pd-card-title">
                                <span class="ico" style="background:#fff7ed; color:#ea580c;">🗺️</span>
                                How To Win
                            </div>
                            <div class="pd-card-sub">Three steps from ₹50 to ₹500.</div>
                        </div>
                    </div>
                    <div class="pd-card-bd" style="padding-top:10px;">
                        <div class="pd-steps">
                            <div class="pd-step">
                                <div class="pd-step-num">01</div>
                                <div>
                                    <div class="pd-step-title">Add ₹50 to Your Wallet</div>
                                    <div class="pd-step-desc">Deposit just ₹50 — your entry ticket to every live question room on the platform.</div>
                                </div>
                            </div>
                            <div class="pd-step">
                                <div class="pd-step-num">02</div>
                                <div>
                                    <div class="pd-step-title">Pick a Live Question</div>
                                    <div class="pd-step-desc">Browse Cricket, Politics, Finance, Movies &amp; more. Each question has a real cash prize pool waiting.</div>
                                </div>
                            </div>
                            <div class="pd-step">
                                <div class="pd-step-num">03</div>
                                <div>
                                    <div class="pd-step-title">Answer Right → Earn ₹500</div>
                                    <div class="pd-step-desc">Correct predictions pay out directly to your wallet. Refer friends to multiply your earnings even further.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div><!-- /right -->

        </div><!-- /main grid -->

        <!-- ══ REFERRAL FOOTER ══ -->
        <a class="pd-referral pa pa-5" href="<?php echo site_url('referral'); ?>">
            <div>
                <div class="pd-ref-eyebrow">Referral Program</div>
                <div class="pd-ref-title">Bring Friends.<br><span>Earn Together.</span></div>
                <div class="pd-ref-desc">Every friend you invite earns you instant cash — no cap, no limits. Share your link, grow your squad, and keep winning without even answering a question.</div>
                <div class="pd-ref-chips">
                    <div class="pd-ref-chip">🪙 ₹50 per referral</div>
                    <div class="pd-ref-chip">♾️ Unlimited invites</div>
                    <div class="pd-ref-chip">⚡ Instant wallet credit</div>
                </div>
            </div>
            <div class="pd-ref-cta">🔗 Refer &amp; Earn Now</div>
        </a>

    </div><!-- /wrap -->
</div><!-- /root -->