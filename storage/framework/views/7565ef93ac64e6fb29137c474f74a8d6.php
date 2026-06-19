<style>
/* 
 * =========================================================
 * ZERO-INFINITY MINIGAME SYSTEM - CORE STYLES
 * =========================================================
 * Violet Palette: Primary (#7C3AED), Accent (#A78BFA)
 */
:root {
    --mg-bg: #09090b; /* Ultra-slick dark background */
    --mg-surface: #121214; /* Sleek dark cards */
    --mg-text: #fafafa; /* Modern gray/white */
    --mg-text-muted: #a1a1aa; /* Muted gray */
    --mg-border: rgba(124, 58, 237, 0.2); /* Neon glowing purple borders */
    
    --mg-primary: #7c3aed; /* Neon Purple */
    --mg-primary-hover: #8b5cf6; /* Hover Purple */
    --mg-primary-light: rgba(124, 58, 237, 0.1);
    --mg-accent: #a78bfa; /* Lighter Purple */
    
    --mg-success: #10B981;
    --mg-danger: #EF4444;
    --mg-warning: #F59E0B;
    
    --mg-radius-lg: 16px;
    --mg-radius-md: 12px;
    --mg-radius-sm: 8px;
    
    --mg-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.4), 0 4px 6px -2px rgba(0, 0, 0, 0.2);
    --mg-shadow-glow: 0 0 20px rgba(124, 58, 237, 0.4);
    
    --mg-font: 'Outfit', 'Inter', system-ui, -apple-system, sans-serif;
    --mg-arcade-local-zoom: 85%; /* Global layout 65% x local 85% = effective arcade zoom 55.25%. */
    --mg-arcade-portal-cover-width: 181vw;
    --mg-arcade-portal-cover-height: calc(var(--vh-fixed, 100vh) * 1.81);
        --mg-arcade-cover-width: 181vw;
        --mg-arcade-cover-height: calc(var(--vh-fixed, 100vh) * 1.81);
}

@keyframes pulse {
    0% { transform: scale(1); opacity: 0.9; }
    50% { transform: scale(1.05); opacity: 1; box-shadow: 0 0 15px rgba(16, 185, 129, 0.4); }
    100% { transform: scale(1); opacity: 0.9; }
}

.mg-wrapper {
    font-family: var(--mg-font);
    background-color: var(--mg-bg);
    color: var(--mg-text);
    position: fixed;
    inset: 0; /* Ensures full screen coverage regardless of zoom */
    z-index: 99999;
    overflow-y: hidden;
    overflow-x: hidden;
    display: none;
    zoom: var(--mg-arcade-local-zoom);
    width: var(--mg-arcade-cover-width);
    min-height: var(--mg-arcade-cover-height);
}

.mg-wrapper.active {
    display: block;
}

/* Base Utility Classes */
.mg-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.75rem 1.5rem;
    border-radius: var(--mg-radius-md);
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    border: none;
    outline: none;
}
.mg-btn-primary {
    background-color: var(--mg-primary);
    color: white;
}
.mg-btn-primary:hover {
    background-color: var(--mg-primary-hover);
    box-shadow: var(--mg-shadow-glow);
}
.mg-btn-secondary {
    background-color: var(--mg-surface);
    color: var(--mg-text);
    border: 1px solid var(--mg-border);
}
.mg-btn-secondary:hover {
    border-color: var(--mg-primary);
    color: var(--mg-primary);
}


.mg-result-panel {
    display: none;
    margin: 1.5rem auto 0;
    width: min(720px, 100%);
    padding: 1.25rem;
    border: 1px solid rgba(245, 158, 11, 0.28);
    border-radius: var(--mg-radius-lg);
    background: linear-gradient(145deg, rgba(18, 18, 20, 0.96), rgba(30, 24, 18, 0.9));
    box-shadow: 0 18px 45px rgba(0, 0, 0, 0.35);
    pointer-events: auto;
}
.mg-result-panel.active { display: block; }
.mg-result-kicker {
    color: var(--mg-warning);
    font-size: 0.76rem;
    letter-spacing: 0.16em;
    text-transform: uppercase;
    font-weight: 800;
    margin-bottom: 0.45rem;
}
.mg-result-title {
    font-size: clamp(1.5rem, 2.4vw, 2.4rem);
    line-height: 1.05;
    margin: 0;
    color: var(--mg-text);
}
.mg-result-copy {
    margin: 0.65rem 0 0;
    color: var(--mg-text-muted);
    line-height: 1.6;
}
.mg-result-reward {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 1rem;
    padding: 0.65rem 0.9rem;
    border-radius: 999px;
    background: rgba(16, 185, 129, 0.12);
    color: #34d399;
    font-weight: 900;
}
.mg-result-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    margin-top: 1.15rem;
}
.mg-result-actions .mg-btn { flex: 1 1 180px; }

/* Animations */
@keyframes mgFadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
.mg-animate-fade-in { animation: mgFadeIn 0.3s ease forwards; }

.mg-screen {
    display: none;
    padding: 2.25rem 2.75rem;
    max-width: min(1680px, calc(100% - 5rem));
    margin: 0 auto;
    min-height: calc(var(--mg-arcade-cover-height) - 90px);
    pointer-events: none;
}
.mg-screen.active {
    display: block;
    position: relative;
    z-index: 2;
    pointer-events: auto;
    animation: mgFadeIn 0.4s ease;
}

.mg-wrapper:not(.portal-active) {
    width: var(--mg-arcade-cover-width);
    height: var(--mg-arcade-cover-height);
}
.mg-wrapper:not(.portal-active) #mg-screen-portal {
    display: none !important;
    pointer-events: none !important;
}
.mg-wrapper:not(.portal-active) .mg-local-modal-overlay:not(.active) {
    display: none !important;
    pointer-events: none !important;
}

/* Shared cinematic lobby for TTT/RPS/Quiz */
.mg-game-lobby-frame {
    min-height: calc(var(--mg-arcade-cover-height) - 4.5rem);
    display: grid;
    grid-template-rows: auto 1fr auto;
    background:
        radial-gradient(circle at 92% 8%, rgba(245, 158, 11, 0.18), transparent 28%),
        radial-gradient(circle at 8% 92%, rgba(180, 125, 35, 0.12), transparent 30%),
        linear-gradient(135deg, rgba(7, 14, 23, 0.96), rgba(9, 10, 15, 0.98) 55%, rgba(20, 17, 10, 0.96));
    border: 1px solid rgba(244, 198, 116, 0.16);
    box-shadow: inset 0 0 0 1px rgba(255,255,255,0.025), 0 30px 90px rgba(0,0,0,0.35);
    overflow: hidden;
}
.mg-game-lobby-topbar {
    min-height: 64px;
    display: grid;
    grid-template-columns: minmax(240px, 1fr) auto minmax(240px, 1fr);
    align-items: center;
    gap: 1.5rem;
    padding: 0.8rem 1.8rem;
    border-bottom: 1px solid rgba(244, 198, 116, 0.24);
    background: rgba(5, 7, 12, 0.46);
    backdrop-filter: blur(14px);
}
.mg-game-lobby-brand { display: flex; align-items: center; gap: 0.75rem; }
.mg-game-lobby-logo { width: 32px; height: 32px; color: #f7d58b; display: grid; place-items: center; }
.mg-game-lobby-logo svg { width: 28px; height: 28px; fill: none; stroke: currentColor; stroke-width: 2.4; stroke-linecap: round; stroke-linejoin: round; }
.mg-game-lobby-title { margin: 0; font-size: 1.08rem; font-weight: 950; color: #f8fafc; letter-spacing: -0.02em; }
.mg-game-lobby-subtitle { margin: 0.12rem 0 0; color: rgba(248,250,252,0.68); font-size: 0.72rem; }
.mg-game-lobby-tabs { display: flex; align-items: center; justify-content: center; gap: 2.3rem; }
.mg-game-lobby-tab {
    appearance: none;
    border: 0;
    background: transparent;
    color: rgba(248,250,252,0.86);
    font-size: 0.92rem;
    font-weight: 900;
    padding: 0.55rem 0;
    cursor: pointer;
    position: relative;
    transition: color 0.18s ease;
}
.mg-game-lobby-tab::after {
    content: '';
    position: absolute;
    left: 0;
    right: 0;
    bottom: 0;
    height: 2px;
    border-radius: 999px;
    background: #f7d58b;
    opacity: 0;
    transform: scaleX(0.4);
    transition: opacity 0.18s ease, transform 0.18s ease;
}
.mg-game-lobby-tab:hover,
.mg-game-lobby-tab.active { color: #f7d58b; }
.mg-game-lobby-tab.active::after { opacity: 1; transform: scaleX(1); }
.mg-game-lobby-actions { display: flex; align-items: center; justify-content: flex-end; gap: 0.75rem; }
.mg-game-lobby-balance,
.mg-game-lobby-exit {
    min-height: 40px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.45rem;
    border-radius: 14px;
    border: 1px solid rgba(244,198,116,0.28);
    background: rgba(255,255,255,0.045);
    color: #f8fafc;
    padding: 0.55rem 0.95rem;
    font-weight: 950;
}
.mg-game-lobby-balance svg { width: 17px; height: 17px; fill: none; stroke: #f7d58b; stroke-width: 2.2; stroke-linecap: round; stroke-linejoin: round; }
.mg-game-lobby-exit { cursor: pointer; border-color: rgba(255,255,255,0.14); min-width: 82px; }
.mg-game-lobby-exit:hover { border-color: rgba(244,198,116,0.4); color: #f7d58b; }
.mg-game-lobby-main {
    display: grid;
    place-items: center;
    padding: clamp(2rem, 8vh, 7rem) 2rem;
}
.mg-game-mode-panel { width: min(760px, 100%); text-align: center; }
.mg-game-mode-title {
    margin: 0 0 1.8rem;
    color: #f7d58b;
    text-shadow: 0 0 24px rgba(247,213,139,0.2);
    font-size: clamp(1.8rem, 3.2vw, 2.8rem);
    font-weight: 950;
    letter-spacing: -0.04em;
}
.mg-game-mode-buttons { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.15rem; }
.mg-game-mode-btn {
    min-height: 76px;
    border-radius: 16px;
    border: 2px solid rgba(247,213,139,0.75);
    background: linear-gradient(135deg, rgba(95,70,24,0.72), rgba(35,35,28,0.74));
    color: #f6e2b8;
    font-size: 1.18rem;
    font-weight: 950;
    cursor: pointer;
    box-shadow: 0 0 0 3px rgba(247,213,139,0.08), 0 0 32px rgba(247,213,139,0.16);
    transition: transform 0.16s ease, box-shadow 0.16s ease, background 0.16s ease;
}
.mg-game-mode-btn:hover,
.mg-game-mode-btn.active {
    transform: translateY(-2px);
    background: linear-gradient(135deg, rgba(132,98,33,0.88), rgba(58,49,31,0.9));
    box-shadow: 0 0 0 4px rgba(247,213,139,0.12), 0 0 44px rgba(247,213,139,0.24);
}
.mg-game-solo-duo-panel {
    display: none;
    grid-template-columns: 1fr 1fr;
    gap: 0.8rem;
    margin: 1rem auto 0;
    width: min(520px, 100%);
}
.mg-game-solo-duo-panel.active { display: grid; }
.mg-game-submode-btn {
    min-height: 52px;
    border-radius: 14px;
    border: 1px solid rgba(247,213,139,0.42);
    background: rgba(255,255,255,0.045);
    color: #f8fafc;
    cursor: pointer;
    font-weight: 900;
}
.mg-game-submode-btn:hover,
.mg-game-submode-btn.active { color: #f7d58b; background: rgba(247,213,139,0.1); }
.mg-game-autopilot-row {
    min-height: 74px;
    margin-top: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 0.9rem 1.2rem;
    border-radius: 16px;
    border: 2px solid rgba(247,213,139,0.78);
    background: rgba(5,10,18,0.52);
    box-shadow: 0 0 30px rgba(247,213,139,0.12);
    cursor: pointer;
}
.mg-game-autopilot-row span { color: rgba(248,250,252,0.84); font-size: 1.16rem; font-weight: 950; }
.mg-game-autopilot-row input { position: absolute; opacity: 0; pointer-events: none; }
.mg-game-switch { width: 64px; height: 34px; border-radius: 999px; border: 2px solid rgba(247,213,139,0.66); background: rgba(255,255,255,0.08); position: relative; transition: background 0.18s ease; }
.mg-game-switch::after { content: ''; width: 24px; height: 24px; border-radius: 50%; background: #10131a; position: absolute; top: 3px; left: 4px; box-shadow: 0 0 0 2px rgba(247,213,139,0.54); transition: transform 0.18s ease, background 0.18s ease; }
.mg-game-autopilot-row input:checked + .mg-game-switch { background: linear-gradient(135deg, #facc15, #f59e0b); }
.mg-game-autopilot-row input:checked + .mg-game-switch::after { transform: translateX(29px); background: #fff7d6; }
.mg-game-lobby-footer {
    min-height: 54px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 0.8rem 1.8rem;
    border-top: 1px solid rgba(244,198,116,0.18);
    color: rgba(248,250,252,0.74);
    font-size: 0.78rem;
}
.mg-game-lobby-links { display: flex; gap: 2.2rem; }
.mg-game-lobby-links span { color: rgba(248,250,252,0.82); }
@media (max-width: 920px) {
    .mg-game-lobby-topbar { grid-template-columns: 1fr; justify-items: center; }
    .mg-game-lobby-actions { justify-content: center; }
    .mg-game-mode-buttons { grid-template-columns: 1fr; }
    .mg-game-lobby-footer { flex-direction: column; text-align: center; }
}

/* Cinematic game store, leaderboard, profile, and guide */
.mg-game-tab-surface {
    width: min(1320px, 100%);
    margin: 0 auto;
    padding: clamp(1.4rem, 3vw, 2.5rem);
}
.mg-game-section-title {
    margin: 0 0 1.25rem;
    color: #f8fafc;
    font-size: clamp(1.8rem, 3vw, 3rem);
    font-weight: 500;
    letter-spacing: -0.045em;
}
.mg-game-store-shell { align-self: stretch; }
.mg-game-store-grid {
    display: grid;
    gap: 1.4rem;
    margin-bottom: 2.2rem;
}
.mg-game-store-grid.is-auto { grid-template-columns: repeat(4, minmax(0, 1fr)); }
.mg-game-store-grid.is-cosmetic { grid-template-columns: repeat(3, minmax(0, 1fr)); }
.mg-game-store-card {
    min-height: 190px;
    border-radius: 16px;
    border: 1.5px solid rgba(247, 213, 139, 0.72);
    background:
        radial-gradient(circle at 50% 30%, rgba(247, 213, 139, 0.16), transparent 34%),
        linear-gradient(135deg, rgba(42, 46, 43, 0.66), rgba(15, 25, 36, 0.72));
    box-shadow: inset 0 0 0 1px rgba(255,255,255,0.03), 0 0 30px rgba(247,213,139,0.10);
    overflow: hidden;
}
.mg-game-store-hit {
    width: 100%;
    height: 100%;
    min-height: 190px;
    border: 0;
    background: transparent;
    color: #f8fafc;
    cursor: pointer;
    display: grid;
    place-items: center;
    align-content: center;
    gap: 0.75rem;
    padding: 1.25rem;
    text-align: center;
}
.mg-game-store-hit:hover { background: rgba(247,213,139,0.06); }
.mg-game-store-icon {
    width: 68px;
    height: 68px;
    color: #f7d58b;
    display: grid;
    place-items: center;
    filter: drop-shadow(0 0 18px rgba(247,213,139,0.26));
}
.mg-game-store-icon svg { width: 64px; height: 64px; fill: none; stroke: currentColor; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }
.mg-game-store-name { color: rgba(248,250,252,0.80); font-size: 1.18rem; font-weight: 500; line-height: 1.2; }
.mg-game-store-price { color: #f7d58b; font-size: 0.9rem; font-weight: 900; }
.mg-shop-item-owned,
.mg-game-store-owned {
    display: grid;
    place-items: center;
    width: 100%;
    min-height: 42px;
    color: #f7d58b;
    font-weight: 950;
    border-top: 1px solid rgba(247,213,139,0.24);
}
.mg-game-rankings-shell { text-align: center; padding-top: 2.6rem; }
.mg-game-rankings-title {
    margin: 0;
    color: #f8fafc;
    font-size: clamp(2.2rem, 4vw, 4rem);
    font-weight: 950;
    letter-spacing: -0.055em;
    text-transform: uppercase;
}
.mg-game-rankings-kicker {
    margin: 0.8rem 0 2.4rem;
    color: #f7d58b;
    font-weight: 950;
    letter-spacing: 0.18em;
    text-transform: uppercase;
}
.mg-game-podium {
    display: grid;
    grid-template-columns: 1fr 1.15fr 1fr;
    align-items: end;
    gap: 2.2rem;
    width: min(980px, 100%);
    margin: 0 auto 2.4rem;
}
.mg-game-podium-card {
    min-height: 180px;
    border-radius: 16px;
    border: 1px solid rgba(247,213,139,0.25);
    background: rgba(15, 24, 35, 0.82);
    display: grid;
    place-items: center;
    padding: 1.2rem;
    color: rgba(248,250,252,0.82);
}
.mg-game-podium-card.is-champion { min-height: 260px; border-top: 4px solid #f7d58b; box-shadow: 0 0 34px rgba(247,213,139,0.12); }
.mg-game-podium-rank { width: 52px; height: 52px; border-radius: 999px; display: grid; place-items: center; background: #f7d58b; color: #17120a; font-weight: 950; }
.mg-game-rank-list {
    width: min(1120px, 100%);
    margin: 0 auto;
}
.mg-game-rank-list .mg-leaderboard-table {
    border-collapse: separate;
    border-spacing: 0 0.8rem;
    width: 100%;
}
.mg-game-rank-list .mg-leaderboard-table th {
    color: rgba(248,250,252,0.56);
    font-size: 0.78rem;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    border: 0;
    padding: 0 1rem;
}
.mg-game-rank-list .mg-leaderboard-table td {
    background: rgba(17, 27, 39, 0.86);
    border-top: 1px solid rgba(247,213,139,0.12);
    border-bottom: 1px solid rgba(247,213,139,0.12);
    padding: 1rem;
    color: #f8fafc;
}
.mg-game-rank-list .mg-leaderboard-table td:first-child { border-left: 1px solid rgba(247,213,139,0.12); border-radius: 14px 0 0 14px; }
.mg-game-rank-list .mg-leaderboard-table td:last-child { border-right: 1px solid rgba(247,213,139,0.12); border-radius: 0 14px 14px 0; color: #f7d58b; font-weight: 950; }
.mg-game-profile-card,
.mg-game-guide-card {
    border-radius: 24px;
    border: 1px solid rgba(247,213,139,0.16);
    background: rgba(15, 23, 34, 0.78);
    box-shadow: inset 0 0 0 1px rgba(255,255,255,0.025), 0 24px 80px rgba(0,0,0,0.28);
    padding: clamp(1.4rem, 3vw, 2.4rem);
}
.mg-game-profile-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 1rem;
}
.mg-game-profile-stat {
    border-radius: 16px;
    border: 1px solid rgba(255,255,255,0.08);
    background: rgba(255,255,255,0.04);
    padding: 1rem;
}
.mg-game-profile-stat span { display:block; color: rgba(248,250,252,0.58); font-size: 0.8rem; margin-bottom: 0.35rem; }
.mg-game-profile-stat strong { color: #f7d58b; font-size: 1.1rem; }
.mg-game-guide-shell {
    display: grid;
    grid-template-columns: minmax(260px, 0.8fr) minmax(380px, 1.4fr);
    gap: 2.4rem;
    align-items: center;
}
.mg-game-guide-title { margin: 0 0 1rem; color: #f7d58b; font-size: clamp(2.3rem, 4.2vw, 4.5rem); line-height: 0.95; letter-spacing: -0.06em; }
.mg-game-guide-lead { color: rgba(248,250,252,0.78); font-size: 1.05rem; line-height: 1.65; }
.mg-game-guide-steps { display: grid; gap: 1.05rem; margin-top: 2.3rem; }
.mg-game-guide-step { display: grid; grid-template-columns: 58px 1fr; gap: 1.1rem; align-items: start; color: rgba(248,250,252,0.52); }
.mg-game-guide-step strong { color: #f7d58b; display:block; margin-bottom: 0.25rem; }
.mg-game-guide-num { width: 48px; height: 48px; border-radius: 12px; display:grid; place-items:center; border:1px solid rgba(247,213,139,0.18); background: rgba(255,255,255,0.05); color:#f7d58b; font-weight:950; }
.mg-game-guide-step.is-active .mg-game-guide-num { background:#f7d58b; color:#17120a; box-shadow:0 0 28px rgba(247,213,139,0.24); }
.mg-game-guide-visual { text-align:center; }
.mg-game-guide-icon { width: min(360px, 80%); aspect-ratio: 1; margin: 0 auto 2rem; border-radius: 28px; display:grid; place-items:center; border:1px solid rgba(247,213,139,0.32); background: radial-gradient(circle, rgba(247,213,139,0.16), rgba(11,20,31,0.78) 58%); color:#f7d58b; }
.mg-game-guide-icon svg { width: 45%; height:45%; fill:none; stroke:currentColor; stroke-width:1.8; stroke-linecap:round; stroke-linejoin:round; }
.mg-game-guide-visual h3 { margin:0 0 0.8rem; color:#f7d58b; font-size:2rem; font-weight:500; }
.mg-game-guide-visual p { margin:0 auto; max-width: 640px; color:rgba(248,250,252,0.78); line-height:1.65; }
@media (max-width: 980px) {
    .mg-game-store-grid.is-auto,
    .mg-game-store-grid.is-cosmetic,
    .mg-game-podium,
    .mg-game-profile-grid,
    .mg-game-guide-shell { grid-template-columns: 1fr; }
}
/* Topbar */
.mg-topbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 2rem;
    background: var(--mg-surface);
    border-bottom: 1px solid var(--mg-border);
    position: sticky;
    top: 0;
    z-index: 10;
}
.mg-topbar-default {
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
}
.mg-topbar-brand {
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--mg-primary);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.mg-topbar-actions {
    display: flex;
    gap: 1rem;
    align-items: center;
}
.mg-topbar-lobby-host {
    display: none;
    width: 100%;
}
.mg-topbar.has-lobby-nav {
    padding: 0.55rem 1.25rem;
}
.mg-topbar.has-lobby-nav .mg-topbar-default {
    display: none;
}
.mg-topbar.has-lobby-nav .mg-topbar-lobby-host {
    display: block;
}
.mg-topbar-lobby-host .mg-game-lobby-topbar {
    width: 100%;
    max-width: none;
    margin: 0;
    border: 0;
    background: transparent;
    box-shadow: none;
}
.mg-balance-badge {
    background: var(--mg-primary-light);
    color: var(--mg-primary);
    padding: 0.5rem 1rem;
    border-radius: 999px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.mg-zp-icon {
    width: 1.35rem;
    height: 1.35rem;
    border-radius: 999px;
    display: inline-grid;
    place-items: center;
    background: radial-gradient(circle at 35% 30%, #fde68a, #f59e0b 50%, #92400e 100%);
    color: #111827;
    font-size: 0.72rem;
    font-weight: 950;
    box-shadow: 0 0 18px rgba(245, 158, 11, 0.28);
}

.mg-local-modal-overlay {
    position: fixed;
    inset: 0;
    z-index: 100004;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 1rem;
    background: rgba(0, 0, 0, 0.58);
    backdrop-filter: blur(14px);
}

.mg-local-modal-overlay.active {
    display: flex;
}

.mg-local-modal {
    width: min(94vw, 520px);
    border: 1px solid rgba(148, 163, 184, 0.22);
    border-radius: 26px;
    background:
        radial-gradient(circle at top left, rgba(20, 184, 166, 0.16), transparent 38%),
        linear-gradient(145deg, rgba(10, 15, 28, 0.98), rgba(6, 8, 14, 0.98));
    box-shadow: 0 32px 90px rgba(0, 0, 0, 0.6);
    padding: 1.25rem;
    animation: mgModalIn 0.22s ease both;
}

@keyframes mgModalIn {
    from { opacity: 0; transform: translateY(14px) scale(0.98); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}

.mg-local-modal-title {
    margin: 0 0 0.55rem;
    font-size: 1.22rem;
    font-weight: 950;
}

.mg-local-modal-message {
    color: var(--mg-text-muted);
    line-height: 1.6;
    margin-bottom: 1rem;
}

.mg-local-modal-input input,
.mg-local-modal-input select {
    width: 100%;
    background: #050816;
    border: 1px solid rgba(148, 163, 184, 0.22);
    color: #f8fafc;
    border-radius: 14px;
    padding: 0.85rem 0.95rem;
    font-weight: 800;
}

.mg-local-modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 0.65rem;
    margin-top: 1rem;
}

.mg-payment-pulse {
    height: 8px;
    overflow: hidden;
    border-radius: 999px;
    background: rgba(148, 163, 184, 0.14);
    margin: 0.75rem 0 1rem;
}

.mg-payment-pulse::before {
    content: "";
    display: block;
    width: 42%;
    height: 100%;
    border-radius: inherit;
    background: linear-gradient(90deg, #14b8a6, #f59e0b);
    animation: mgPayMove 1s ease-in-out infinite;
}

@keyframes mgPayMove {
    0% { transform: translateX(-110%); }
    100% { transform: translateX(260%); }
}

/* Arcade skin: dashboard-like structure with a distinct ruby/gold arcade palette. */
:root {
    --mg-bg: #08070c;
    --mg-surface: #121019;
    --mg-text: #fbf7ff;
    --mg-text-muted: #b7adbf;
    --mg-border: rgba(255, 79, 139, 0.24);
    --mg-primary: #ff4f8b;
    --mg-primary-hover: #ff7aa8;
    --mg-primary-light: rgba(255, 79, 139, 0.13);
    --mg-accent: #f8c35a;
    --mg-shadow-glow: 0 0 24px rgba(255, 79, 139, 0.34);
}

.mg-wrapper {
    background:
        repeating-linear-gradient(90deg, rgba(255, 255, 255, 0.018) 0, rgba(255, 255, 255, 0.018) 1px, transparent 1px, transparent 84px),
        linear-gradient(135deg, #07070b 0%, #12101a 58%, #0a0709 100%);
}

#minigame-wrapper.portal-active {
    background:
        repeating-linear-gradient(90deg, rgba(255, 255, 255, 0.016) 0, rgba(255, 255, 255, 0.016) 1px, transparent 1px, transparent 88px),
        #08070c;
}

.mg-btn,
.mg-local-modal,
.mg-game-lobby-frame,
.mg-game-lobby-balance,
.mg-game-lobby-exit,
.mg-game-mode-btn,
.mg-game-submode-btn,
.mg-game-autopilot-row,
.mg-game-store-card,
.mg-game-podium-card,
.mg-game-profile-card,
.mg-game-guide-card,
.mg-game-profile-stat {
    border-radius: 8px;
}

.mg-btn-primary {
    background: linear-gradient(135deg, #ff4f8b, #f8c35a);
    color: #13080e;
    font-weight: 900;
}

.mg-btn-primary:hover {
    background: linear-gradient(135deg, #ff7aa8, #ffd278);
    box-shadow: var(--mg-shadow-glow);
}

.mg-topbar {
    background: rgba(13, 11, 18, 0.92);
    border-bottom-color: rgba(255, 79, 139, 0.22);
}

.mg-topbar-brand,
.mg-balance-badge {
    color: #ffd278;
}

.mg-topbar-logo {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    object-fit: cover;
    border: 1px solid rgba(255, 210, 120, 0.16);
    background: rgba(255, 255, 255, 0.03);
}

.mg-balance-badge {
    border: 1px solid rgba(255, 210, 120, 0.24);
    background: rgba(255, 210, 120, 0.09);
}

.mg-zp-icon {
    background: linear-gradient(135deg, #ff4f8b, #f8c35a);
    color: #16090d;
    box-shadow: 0 0 18px rgba(255, 79, 139, 0.26);
}

.mg-game-lobby-frame {
    background:
        repeating-linear-gradient(0deg, rgba(255, 255, 255, 0.018) 0, rgba(255, 255, 255, 0.018) 1px, transparent 1px, transparent 76px),
        linear-gradient(135deg, rgba(12, 10, 17, 0.98), rgba(20, 13, 23, 0.98) 58%, rgba(19, 12, 13, 0.96));
    border-color: rgba(255, 79, 139, 0.22);
}

.mg-game-lobby-topbar {
    background: rgba(9, 8, 13, 0.72);
    border-bottom-color: rgba(255, 79, 139, 0.2);
}

.mg-game-lobby-logo,
.mg-game-lobby-tab:hover,
.mg-game-lobby-tab.active,
.mg-game-lobby-exit:hover,
.mg-game-mode-title,
.mg-game-store-icon,
.mg-game-store-price,
.mg-game-store-owned,
.mg-game-rankings-kicker,
.mg-game-profile-stat strong,
.mg-game-guide-title,
.mg-game-guide-step strong,
.mg-game-guide-visual h3 {
    color: #ffd278;
}

.mg-game-lobby-tab::after,
.mg-game-podium-rank,
.mg-game-guide-step.is-active .mg-game-guide-num {
    background: linear-gradient(135deg, #ff4f8b, #f8c35a);
    color: #13080e;
}

.mg-game-lobby-balance,
.mg-game-lobby-exit,
.mg-game-submode-btn,
.mg-game-autopilot-row,
.mg-game-store-card,
.mg-game-podium-card,
.mg-game-profile-card,
.mg-game-guide-card {
    border-color: rgba(255, 210, 120, 0.2);
    background: rgba(17, 15, 22, 0.78);
}

.mg-game-lobby-balance svg {
    stroke: #ffd278;
}

.mg-game-mode-btn {
    border-color: rgba(255, 79, 139, 0.52);
    background: linear-gradient(135deg, rgba(95, 19, 51, 0.72), rgba(32, 24, 30, 0.78));
    color: #ffe7ef;
    box-shadow: 0 0 0 3px rgba(255, 79, 139, 0.08), 0 0 32px rgba(255, 79, 139, 0.13);
}

.mg-game-mode-btn:hover,
.mg-game-mode-btn.active {
    background: linear-gradient(135deg, rgba(148, 36, 76, 0.86), rgba(64, 42, 33, 0.9));
    box-shadow: 0 0 0 4px rgba(255, 79, 139, 0.12), 0 0 44px rgba(255, 210, 120, 0.16);
}

.mg-game-submode-btn:hover,
.mg-game-submode-btn.active {
    color: #ffd278;
    background: rgba(255, 210, 120, 0.1);
}

.mg-game-switch {
    border-color: rgba(255, 79, 139, 0.62);
}

.mg-game-switch::after {
    box-shadow: 0 0 0 2px rgba(255, 210, 120, 0.5);
}

.mg-game-autopilot-row input:checked + .mg-game-switch,
.mg-payment-pulse::before {
    background: linear-gradient(90deg, #ff4f8b, #f8c35a);
}

.mg-game-store-card {
    background:
        repeating-linear-gradient(90deg, rgba(255, 255, 255, 0.014) 0, rgba(255, 255, 255, 0.014) 1px, transparent 1px, transparent 48px),
        linear-gradient(135deg, rgba(41, 23, 35, 0.72), rgba(18, 22, 31, 0.74));
    box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.025), 0 20px 58px rgba(0, 0, 0, 0.26);
}

.mg-game-store-hit:hover {
    background: rgba(255, 79, 139, 0.07);
}

.mg-game-podium-card.is-champion {
    border-top-color: #ff4f8b;
    box-shadow: 0 0 34px rgba(255, 79, 139, 0.14);
}

.mg-game-rank-list .mg-leaderboard-table td {
    background: rgba(18, 15, 23, 0.88);
    border-top-color: rgba(255, 79, 139, 0.13);
    border-bottom-color: rgba(255, 79, 139, 0.13);
}

.mg-game-rank-list .mg-leaderboard-table td:first-child {
    border-left-color: rgba(255, 79, 139, 0.13);
}

.mg-game-rank-list .mg-leaderboard-table td:last-child {
    border-right-color: rgba(255, 79, 139, 0.13);
    color: #ffd278;
}

.mg-game-guide-num,
.mg-game-guide-icon {
    border-color: rgba(255, 79, 139, 0.22);
    color: #ffd278;
    background: rgba(255, 255, 255, 0.045);
}

.mg-local-modal {
    border-color: rgba(255, 210, 120, 0.22);
    background:
        repeating-linear-gradient(90deg, rgba(255, 255, 255, 0.014) 0, rgba(255, 255, 255, 0.014) 1px, transparent 1px, transparent 58px),
        linear-gradient(145deg, rgba(17, 15, 22, 0.98), rgba(8, 7, 12, 0.98));
}

.mg-local-modal-input input,
.mg-local-modal-input select {
    border-radius: 8px;
    background: #08070c;
    border-color: rgba(255, 210, 120, 0.18);
}
</style>

<div id="minigame-wrapper" class="mg-wrapper">
    
    <div class="mg-topbar">
        <div id="mg-topbar-default" class="mg-topbar-default">
            <div class="mg-topbar-brand">
                <img class="mg-topbar-logo" src="<?php echo e(asset('img/Milky Garage Assets/Logo Area.webp')); ?>" alt="Milky Garage">
                <span>Zero-Infinity Arcade Portal</span>
            </div>
            <div class="mg-topbar-actions">
                <div id="mg-gamepad-indicator" style="display: none; align-items: center; gap: 0.35rem; color: #10B981; font-weight: 700; background: rgba(16, 185, 129, 0.15); padding: 0.35rem 0.75rem; border-radius: 999px; border: 1px solid rgba(16, 185, 129, 0.4); font-size: 0.8rem; box-shadow: 0 0 10px rgba(16, 185, 129, 0.2); animation: pulse 1.5s infinite;">
                    <span aria-hidden="true">GP</span> Gamepad Connected
                </div>
                <div class="mg-balance-badge"><span class="mg-zp-icon" aria-hidden="true"><?php echo $__env->make('components.ui-icon', ['name' => 'zeropay', 'class' => 'ui-svg'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></span><span id="mg-global-balance">Rp <?php echo e(number_format(Auth::user()->balance ?? 0, 0, ',', '.')); ?></span></div>
                <button class="mg-btn mg-btn-secondary" type="button" data-mg-action="close-arcade">Keluar</button>
            </div>
        </div>
        <div id="mg-topbar-lobby-host" class="mg-topbar-lobby-host" aria-hidden="true"></div>
    </div>

    <div id="mg-local-modal-overlay" class="mg-local-modal-overlay" aria-hidden="true">
        <div class="mg-local-modal" role="dialog" aria-modal="true" aria-labelledby="mg-local-modal-title">
            <h3 id="mg-local-modal-title" class="mg-local-modal-title"></h3>
            <div id="mg-local-modal-message" class="mg-local-modal-message"></div>
            <div id="mg-local-modal-input" class="mg-local-modal-input"></div>
            <div id="mg-local-modal-actions" class="mg-local-modal-actions">
                <button id="mg-local-modal-cancel" type="button" class="mg-btn mg-btn-secondary">Batal</button>
                <button id="mg-local-modal-ok" type="button" class="mg-btn mg-btn-primary">OK</button>
            </div>
        </div>
    </div>

    <!-- Portal Screen (Game Selection) -->
    <?php if (isset($component)) { $__componentOriginal33a57a5beb844334b1888fcc8e811c6d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal33a57a5beb844334b1888fcc8e811c6d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.minigame-portal','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('minigame-portal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal33a57a5beb844334b1888fcc8e811c6d)): ?>
<?php $attributes = $__attributesOriginal33a57a5beb844334b1888fcc8e811c6d; ?>
<?php unset($__attributesOriginal33a57a5beb844334b1888fcc8e811c6d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal33a57a5beb844334b1888fcc8e811c6d)): ?>
<?php $component = $__componentOriginal33a57a5beb844334b1888fcc8e811c6d; ?>
<?php unset($__componentOriginal33a57a5beb844334b1888fcc8e811c6d); ?>
<?php endif; ?>

    <!-- Game Screens -->
    <?php if (isset($component)) { $__componentOriginal237cfe62609e2c4172bf836c1c219bed = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal237cfe62609e2c4172bf836c1c219bed = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.minigame-ttt','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('minigame-ttt'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal237cfe62609e2c4172bf836c1c219bed)): ?>
<?php $attributes = $__attributesOriginal237cfe62609e2c4172bf836c1c219bed; ?>
<?php unset($__attributesOriginal237cfe62609e2c4172bf836c1c219bed); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal237cfe62609e2c4172bf836c1c219bed)): ?>
<?php $component = $__componentOriginal237cfe62609e2c4172bf836c1c219bed; ?>
<?php unset($__componentOriginal237cfe62609e2c4172bf836c1c219bed); ?>
<?php endif; ?>
    <?php if (isset($component)) { $__componentOriginal8ffffd8094a22d184cfa21fadc3bef3a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8ffffd8094a22d184cfa21fadc3bef3a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.minigame-rps','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('minigame-rps'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8ffffd8094a22d184cfa21fadc3bef3a)): ?>
<?php $attributes = $__attributesOriginal8ffffd8094a22d184cfa21fadc3bef3a; ?>
<?php unset($__attributesOriginal8ffffd8094a22d184cfa21fadc3bef3a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8ffffd8094a22d184cfa21fadc3bef3a)): ?>
<?php $component = $__componentOriginal8ffffd8094a22d184cfa21fadc3bef3a; ?>
<?php unset($__componentOriginal8ffffd8094a22d184cfa21fadc3bef3a); ?>
<?php endif; ?>
    <?php if (isset($component)) { $__componentOriginal377613c9ce64a54a0cbaf92cbead5584 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal377613c9ce64a54a0cbaf92cbead5584 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.minigame-quiz','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('minigame-quiz'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal377613c9ce64a54a0cbaf92cbead5584)): ?>
<?php $attributes = $__attributesOriginal377613c9ce64a54a0cbaf92cbead5584; ?>
<?php unset($__attributesOriginal377613c9ce64a54a0cbaf92cbead5584); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal377613c9ce64a54a0cbaf92cbead5584)): ?>
<?php $component = $__componentOriginal377613c9ce64a54a0cbaf92cbead5584; ?>
<?php unset($__componentOriginal377613c9ce64a54a0cbaf92cbead5584); ?>
<?php endif; ?>

</div>

<script>
/**
 * CORE MINIGAME SYSTEM (Zero-Infinity Engine)
 */
const MgCore = {
    csrfToken: '<?php echo e(csrf_token()); ?>',
    balance: <?php echo e(Auth::user()->balance ?? 0); ?>,
    currentScreen: 'portal',
    gamepadLoop: null,
    originalModals: null,
    localModalResolver: null,
    stateKey: 'mgArcadeSessionState',
    restoreAttempted: false,
    dockedLobbyTopbar: null,
    dockedLobbyPlaceholder: null,

    init() {
        // Background arcade sync
        this.syncArcadePresence();
        
        // Start 10-second ping interval without doing background work while the tab is hidden.
        this.presenceInterval = setInterval(() => {
            if (!document.hidden) this.syncArcadePresence();
        }, 10000);
        
        // Gamepad Support
        window.addEventListener("gamepadconnected", (e) => {
            console.log("Gamepad connected at index %d: %s.", e.gamepad.index, e.gamepad.id);
            document.getElementById('mg-gamepad-indicator').style.display = 'flex';
            this.startGamepadLoop();
        });
        window.addEventListener("gamepaddisconnected", (e) => {
            document.getElementById('mg-gamepad-indicator').style.display = 'none';
            if (this.gamepadLoop) {
                cancelAnimationFrame(this.gamepadLoop);
                this.gamepadLoop = null;
            }
        });
        document.addEventListener('visibilitychange', () => {
            if (document.hidden && this.gamepadLoop) {
                cancelAnimationFrame(this.gamepadLoop);
                this.gamepadLoop = null;
            } else if (!document.hidden && navigator.getGamepads && navigator.getGamepads()[0]) {
                this.startGamepadLoop();
            }
        });

        this.restoreState();
    },

    startGamepadLoop() {
        if (this.gamepadLoop || document.hidden) return;
        this.gamepadLoop = requestAnimationFrame(() => this.pollGamepad());
    },

    pollGamepad() {
        this.gamepadLoop = null;
        if (document.hidden || !navigator.getGamepads) return;
        const gamepads = navigator.getGamepads();
        if (gamepads[0]) {
            const gp = gamepads[0];
            
            // 1. A/X Button (Button index 0) -> Click focused element
            if (gp.buttons[0].pressed && !this.aButtonPressed) {
                this.aButtonPressed = true;
                if (document.activeElement && document.activeElement.tagName !== 'BODY') {
                    document.activeElement.click();
                }
            } else if (!gp.buttons[0].pressed) {
                this.aButtonPressed = false;
            }

            // 2. B/O Button (Button index 1) -> Go back to lobby or close portal
            if (gp.buttons[1].pressed && !this.bButtonPressed) {
                this.bButtonPressed = true;
                if (this.currentScreen !== 'portal') {
                    this.navigate('portal');
                } else {
                    this.close();
                }
            } else if (!gp.buttons[1].pressed) {
                this.bButtonPressed = false;
            }
            
            // 3. D-Pad / Left-stick navigation
            // Down (13) or Axis 1 > 0.5 (Left stick down)
            if ((gp.buttons[13].pressed || gp.axes[1] > 0.5) && !this.downPressed) {
                this.downPressed = true;
                this.navigateFocus(1);
            } else if (!gp.buttons[13].pressed && gp.axes[1] <= 0.5) {
                this.downPressed = false;
            }
            
            // Up (12) or Axis 1 < -0.5 (Left stick up)
            if ((gp.buttons[12].pressed || gp.axes[1] < -0.5) && !this.upPressed) {
                this.upPressed = true;
                this.navigateFocus(-1);
            } else if (!gp.buttons[12].pressed && gp.axes[1] >= -0.5) {
                this.upPressed = false;
            }

            // Right (15) or Axis 0 > 0.5 (Left stick right)
            if ((gp.buttons[15].pressed || gp.axes[0] > 0.5) && !this.rightPressed) {
                this.rightPressed = true;
                this.navigateFocus(1);
            } else if (!gp.buttons[15].pressed && gp.axes[0] <= 0.5) {
                this.rightPressed = false;
            }

            // Left (14) or Axis 0 < -0.5 (Left stick left)
            if ((gp.buttons[14].pressed || gp.axes[0] < -0.5) && !this.leftPressed) {
                this.leftPressed = true;
                this.navigateFocus(-1);
            } else if (!gp.buttons[14].pressed && gp.axes[0] >= -0.5) {
                this.leftPressed = false;
            }
        }
        if (gamepads[0]) this.startGamepadLoop();
    },
    
    navigateFocus(direction) {
        const focusable = Array.from(document.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'))
            .filter(el => el.offsetWidth > 0 || el.offsetHeight > 0);
        
        if (focusable.length === 0) return;

        const index = focusable.indexOf(document.activeElement);
        let nextIndex = 0;
        if (index > -1) {
            nextIndex = index + direction;
            if (nextIndex >= focusable.length) nextIndex = 0;
            if (nextIndex < 0) nextIndex = focusable.length - 1;
        }
        focusable[nextIndex].focus();
    },

    readSavedState() {
        try {
            return JSON.parse(sessionStorage.getItem(this.stateKey) || '{}');
        } catch (error) {
            return {};
        }
    },

    saveState(screenId = this.currentScreen) {
        const wrapper = document.getElementById('minigame-wrapper');
        if (!wrapper) return;

        try {
            sessionStorage.setItem(this.stateKey, JSON.stringify({
                active: wrapper.classList.contains('active'),
                screen: screenId || 'portal',
            }));
        } catch (error) {
            // Session storage can be unavailable in strict browser modes.
        }
    },

    clearState() {
        try {
            sessionStorage.removeItem(this.stateKey);
        } catch (error) {
            // Keep close behavior working even when session storage is blocked.
        }
    },

    restoreState() {
        if (this.restoreAttempted) return;
        this.restoreAttempted = true;

        const saved = this.readSavedState();
        const validScreens = ['portal', 'ttt', 'rps', 'quiz'];
        if (!saved?.active || !validScreens.includes(saved.screen)) return;

        const wrapper = document.getElementById('minigame-wrapper');
        if (!wrapper) return;

        wrapper.classList.add('active');
        this.enableScopedModal();
        this.bindMinigameClickFallback();
        if (typeof window.resetPortalEntrySplash === 'function') window.resetPortalEntrySplash();
        this.navigate(saved.screen || 'portal');
    },

    open(screenId = 'portal') {
        document.getElementById('minigame-wrapper').classList.add('active');
        this.enableScopedModal();
        this.bindMinigameClickFallback();
        if (typeof window.resetPortalEntrySplash === 'function') window.resetPortalEntrySplash();
        this.saveState(screenId);
        this.navigate(screenId);
    },

    close() {
        this.restoreDockedLobbyTopbar();
        document.getElementById('minigame-wrapper').classList.remove('active');
        this.restoreGlobalModal();
        if (typeof window.resetPortalEntrySplash === 'function') window.resetPortalEntrySplash();
        this.clearState();
    },

    async navigate(screenId) {
        const wrapper = document.getElementById('minigame-wrapper');
        const target = document.getElementById('mg-screen-' + screenId);
        if (!wrapper || !target) {
            console.error('Minigame screen not found:', screenId);
            return this.toast('Lobby game tidak ditemukan. Coba muat ulang halaman.');
        }

        this.hidePortalOverlays();
        this.hideInactiveGameOverlays(screenId);
        document.querySelectorAll('.mg-screen').forEach(el => el.classList.remove('active'));
        target.classList.add('active');
        target.style.pointerEvents = 'auto';
        console.info('Minigame screen active:', screenId);
        wrapper.classList.toggle('portal-active', screenId === 'portal');
        this.syncTopbar(screenId, target);
        wrapper.scrollTop = 0;
        target.scrollTop = 0;
        requestAnimationFrame(() => {
            wrapper.scrollTop = 0;
            target.scrollTop = 0;
        });
        this.currentScreen = screenId;
        this.saveState(screenId);
        if (screenId === 'portal' && typeof window.loadPortalData === 'function') {
            window.loadPortalData(true);
            return;
        }

        try {
            if (screenId === 'ttt' && window.MgTtt?.init) await window.MgTtt.init();
            if (screenId === 'rps' && window.MgRps?.init) await window.MgRps.init();
            if (screenId === 'quiz' && window.MgQuiz?.init) await window.MgQuiz.init();
        } catch (error) {
            console.error('Minigame lobby init failed:', error);
            this.toast('Lobby berhasil dibuka, tetapi sebagian data belum siap. Coba lanjutkan dari lobby atau muat ulang halaman.');
        }
    },

    syncTopbar(screenId, target) {
        const topbar = document.querySelector('#minigame-wrapper .mg-topbar');
        const host = document.getElementById('mg-topbar-lobby-host');
        if (!topbar || !host) return;

        if (screenId === 'portal') {
            this.restoreDockedLobbyTopbar();
            return;
        }

        let lobbyTopbar = target?.querySelector('.mg-game-lobby-topbar');
        if (!lobbyTopbar && this.dockedLobbyTopbar?.dataset?.mgDockedScreen === screenId) {
            lobbyTopbar = this.dockedLobbyTopbar;
        }

        if (!lobbyTopbar) {
            this.restoreDockedLobbyTopbar();
            return;
        }

        if (this.dockedLobbyTopbar && this.dockedLobbyTopbar !== lobbyTopbar) {
            this.restoreDockedLobbyTopbar();
        }

        if (!this.dockedLobbyTopbar) {
            const placeholder = document.createComment('mg-game-lobby-topbar-placeholder');
            lobbyTopbar.parentNode.insertBefore(placeholder, lobbyTopbar);
            lobbyTopbar.dataset.mgDockedScreen = screenId;
            host.appendChild(lobbyTopbar);
            this.dockedLobbyTopbar = lobbyTopbar;
            this.dockedLobbyPlaceholder = placeholder;
        }

        topbar.classList.add('has-lobby-nav');
        host.removeAttribute('aria-hidden');
    },

    restoreDockedLobbyTopbar() {
        const topbar = document.querySelector('#minigame-wrapper .mg-topbar');
        const host = document.getElementById('mg-topbar-lobby-host');

        if (this.dockedLobbyTopbar && this.dockedLobbyPlaceholder?.parentNode) {
            this.dockedLobbyPlaceholder.parentNode.insertBefore(this.dockedLobbyTopbar, this.dockedLobbyPlaceholder);
            delete this.dockedLobbyTopbar.dataset.mgDockedScreen;
            this.dockedLobbyPlaceholder.remove();
        }

        this.dockedLobbyTopbar = null;
        this.dockedLobbyPlaceholder = null;
        topbar?.classList.remove('has-lobby-nav');
        host?.setAttribute('aria-hidden', 'true');
    },

    hidePortalOverlays() {
        ['arcade-entry-splash', 'arcade-welcome', 'portal-lobby-transition', 'portal-qris-overlay', 'portal-modal-overlay'].forEach((id) => {
            const el = document.getElementById(id);
            if (!el) return;
            el.classList.remove('active');
            el.setAttribute('aria-hidden', 'true');
            el.style.pointerEvents = 'none';
        });
    },

    hideInactiveGameOverlays(activeScreen) {
        ['ttt', 'rps', 'quiz'].forEach((game) => {
            ['matchmaking', 'spectator'].forEach((type) => {
                const el = document.getElementById(`mg-${game}-${type}`);
                if (!el) return;
                const belongsToActiveScreen = game === activeScreen;
                if (!belongsToActiveScreen) {
                    el.style.display = 'none';
                    el.style.pointerEvents = 'none';
                    el.setAttribute('aria-hidden', 'true');
                    return;
                }
                if (el.style.display === 'none' || !el.style.display) {
                    el.style.pointerEvents = 'none';
                    el.setAttribute('aria-hidden', 'true');
                }
            });
        });

        const localModal = document.getElementById('mg-local-modal-overlay');
        if (localModal && !localModal.classList.contains('active')) {
            localModal.style.pointerEvents = 'none';
            localModal.setAttribute('aria-hidden', 'true');
        }
    },
    bindMinigameClickFallback() {
        const wrapper = document.getElementById('minigame-wrapper');
        if (!wrapper || wrapper.dataset.clickFallbackBound === '1') return;
        wrapper.dataset.clickFallbackBound = '1';

        const games = {
            ttt: () => window.MgTtt,
            rps: () => window.MgRps,
            quiz: () => window.MgQuiz,
        };

        const callGameMethod = (game, method, args = []) => {
            if (!game || typeof game[method] !== 'function') return false;
            try {
                const result = game[method](...args);
                if (result && typeof result.catch === 'function') {
                    result.catch((error) => {
                        console.error(`Minigame action failed: ${method}`, error);
                        this.toast('Aksi belum berhasil dijalankan. Coba klik sekali lagi.');
                    });
                }
                return true;
            } catch (error) {
                console.error(`Minigame action failed: ${method}`, error);
                this.toast('Aksi belum berhasil dijalankan. Coba klik sekali lagi.');
                return true;
            }
        };

        wrapper.addEventListener('click', (event) => {
            const button = event.target.closest('button');
            if (!button || !wrapper.contains(button)) return;

            const explicitAction = button.dataset.mgAction;
            if (explicitAction) {
                const gameKey = button.dataset.mgGame || this.currentScreen;
                const game = games[gameKey] ? games[gameKey]() : null;
                let handled = true;

                switch (explicitAction) {
                    case 'switch-tab':
                        handled = callGameMethod(game, 'switchTab', [button.dataset.tab, button]);
                        break;
                    case 'select-lobby-mode': {
                        const selectedMode = button.dataset.mode;
                        const modeSelect = document.getElementById(`mg-${gameKey}-mode`);
                        const soloDuoPanel = document.getElementById(`mg-${gameKey}-solo-duo-panel`);
                        if (selectedMode === 'solo_duo') {
                            if (soloDuoPanel) soloDuoPanel.classList.toggle('active');
                            button.classList.toggle('active');
                        } else if (modeSelect && selectedMode) {
                            modeSelect.value = selectedMode;
                            wrapper.querySelectorAll(`[data-mg-game="${gameKey}"][data-mg-action="select-lobby-mode"]`).forEach((btn) => btn.classList.remove('active'));
                            button.classList.add('active');
                            if (soloDuoPanel && (selectedMode === 'have_fun' || selectedMode === 'duo')) soloDuoPanel.classList.add('active');
                            const autoBox = document.getElementById(`mg-${gameKey}-auto-grinding`);
                            if (autoBox && selectedMode === 'duo') autoBox.checked = false;
                            if (button.dataset.start === '1') handled = callGameMethod(game, 'startGameRouter');
                        } else {
                            handled = false;
                        }
                        break;
                    }
                    case 'start-game':
                        handled = callGameMethod(game, 'startGameRouter');
                        break;
                    case 'start-autopilot':
                        handled = callGameMethod(game, 'enableAutopilotFromLobby');
                        break;
                    case 'exit-portal':
                        handled = callGameMethod(game, 'exitArenaAndGoHome') || (this.navigate('portal'), true);
                        break;
                    case 'exit-lobby':
                        handled = callGameMethod(game, 'exitArena');
                        break;
                    case 'toggle-autopilot':
                        handled = callGameMethod(game, 'toggleAutopilot');
                        break;
                    case 'surrender':
                        handled = callGameMethod(game, 'surrenderMatch');
                        break;
                    case 'result-continue':
                    case 'result-lobby':
                    case 'result-portal':
                        handled = callGameMethod(game, 'handleResultAction', [explicitAction]);
                        break;
                    case 'confirm-exit-spectator':
                        handled = callGameMethod(game, 'confirmExitSpectator');
                        break;
                    case 'make-move':
                        handled = callGameMethod(game, 'makeMove', [button.dataset.move]);
                        break;
                    case 'use-hint':
                        handled = callGameMethod(game, 'useHint', [button.dataset.hint]);
                        break;
                    case 'buy-shop-item':
                        if (typeof window.buyShopItem === 'function') {
                            window.buyShopItem(button.dataset.itemKey, button.dataset.itemType, Number(button.dataset.price || 0));
                        } else {
                            handled = false;
                        }
                        break;
                    case 'close-arcade':
                        this.close();
                        break;
                    default:
                        handled = false;
                }

                if (handled) {
                    event.preventDefault();
                    event.stopImmediatePropagation();
                }
                return;
            }

            const action = button.getAttribute('onclick') || '';
            if (!action.includes('MgTtt.') && !action.includes('MgRps.') && !action.includes('MgQuiz.')) return;

            const game = action.includes('MgTtt.') ? window.MgTtt : action.includes('MgRps.') ? window.MgRps : window.MgQuiz;
            if (!game) return;

            const tabMatch = action.match(/switchTab\('([^']+)'/);
            if (tabMatch && typeof game.switchTab === 'function') {
                event.preventDefault();
                event.stopImmediatePropagation();
                game.switchTab(tabMatch[1], button);
                return;
            }

            const callableActions = [
                'startGameRouter',
                'enableAutopilotFromLobby',
                'exitArenaAndGoHome',
                'exitArena',
                'toggleAutopilot',
                'surrenderMatch',
                'confirmExitSpectator'
            ];

            const method = callableActions.find((name) => action.includes(`.${name}(`));
            if (!method || typeof game[method] !== 'function') return;

            event.preventDefault();
            event.stopImmediatePropagation();
            game[method]();
        }, true);
    },

    updateBalance(newBalance) {
        const parsedBalance = Number(newBalance || 0);
        this.balance = parsedBalance;
        const formatted = 'Rp ' + parsedBalance.toLocaleString('id-ID');
        ['mg-global-balance', 'portal-header-balance', 'portal-shop-balance', 'prof-zeropay'].forEach((id) => {
            const el = document.getElementById(id);
            if (el) el.textContent = formatted;
        });
        const numeric = document.getElementById('portal-balance-display');
        if (numeric) numeric.textContent = parsedBalance.toLocaleString('id-ID');
        const portalData = window.portalData;
        if (portalData && portalData.profile) {
            portalData.profile.balance = parsedBalance;
        }
    },

    enableScopedModal() {
        if (!this.originalModals) {
            this.originalModals = {
                alert: window.showAlert,
                confirm: window.showConfirm,
                prompt: window.showPrompt,
            };
        }

        window.showAlert = (title, message, okText = 'OK') => this.showLocalAlert(title, message, okText);
        window.showConfirm = (title, message, okText = 'OK', cancelText = 'Batal') => this.showLocalConfirm(title, message, okText, cancelText);
        window.showPrompt = (title, message, defaultValue = '') => this.showLocalPrompt(title, message, defaultValue);
    },

    restoreGlobalModal() {
        if (!this.originalModals) return;
        window.showAlert = this.originalModals.alert;
        window.showConfirm = this.originalModals.confirm;
        window.showPrompt = this.originalModals.prompt;
    },

    localModalElements() {
        return {
            overlay: document.getElementById('mg-local-modal-overlay'),
            title: document.getElementById('mg-local-modal-title'),
            message: document.getElementById('mg-local-modal-message'),
            input: document.getElementById('mg-local-modal-input'),
            ok: document.getElementById('mg-local-modal-ok'),
            cancel: document.getElementById('mg-local-modal-cancel'),
        };
    },

    openLocalModal({ title, message, inputHtml = '', okText = 'OK', cancelText = 'Batal', showCancel = true, onOpen = null }) {
        const els = this.localModalElements();
        els.title.innerText = title || '';
        els.message.innerHTML = message || '';
        els.input.innerHTML = inputHtml;
        els.ok.innerText = okText;
        els.cancel.innerText = cancelText;
        els.cancel.style.display = showCancel ? '' : 'none';
        els.overlay.classList.add('active');
        els.overlay.setAttribute('aria-hidden', 'false');
        els.overlay.style.pointerEvents = 'auto';

        return new Promise((resolve) => {
            const cleanup = (payload) => {
                els.ok.onclick = null;
                els.cancel.onclick = null;
                els.overlay.onclick = null;
                els.overlay.classList.remove('active');
                els.overlay.setAttribute('aria-hidden', 'true');
                els.overlay.style.pointerEvents = 'none';
                resolve(payload);
            };

            els.ok.onclick = () => {
                const field = els.input.querySelector('input, select, textarea');
                cleanup({ ok: true, value: field ? field.value : null });
            };
            els.cancel.onclick = () => cleanup({ ok: false, value: null });
            els.overlay.onclick = (event) => {
                if (event.target === els.overlay) cleanup({ ok: false, value: null });
            };

            if (typeof onOpen === 'function') onOpen(els);
        });
    },

    async showLocalAlert(title, message, okText = 'OK') {
        await this.openLocalModal({ title, message, okText, showCancel: false });
    },

    async showLocalConfirm(title, message, okText = 'OK', cancelText = 'Batal') {
        const result = await this.openLocalModal({ title, message, okText, cancelText, showCancel: true });
        return result.ok;
    },

    async showLocalPrompt(title, message, defaultValue = '') {
        const safeValue = String(defaultValue ?? '').replace(/"/g, '&quot;');
        const result = await this.openLocalModal({
            title,
            message,
            inputHtml: `<input type="text" value="${safeValue}">`,
            okText: 'OK',
            cancelText: 'Batal',
            showCancel: true,
            onOpen: (els) => setTimeout(() => els.input.querySelector('input')?.focus(), 30),
        });

        return result.ok ? result.value : null;
    },

    async apiCall(url, data = {}) {
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: JSON.stringify(data)
            });
            return await response.json();
        } catch (e) {
            console.error('Request Error:', e);
            return { status: 'error', message: e.message };
        }
    },

    async apiGet(url) {
        try {
            const response = await fetch(url);
            return await response.json();
        } catch (e) {
            console.error('Request Error:', e);
            return { status: 'error', message: e.message };
        }
    },

    async syncArcadePresence() {
        // Silent sync for arcade state
        await this.apiCall('/arcade/ping');
    },
    
    // UI Utility for showing toasts
    toast(msg, type='info') {
        return window.showAlert('Informasi', msg);
    }
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        MgCore.init();
    });
} else {
    MgCore.init();
}
</script>





<?php /**PATH C:\laragon\www\ProyekTI\resources\views/components/minigame.blade.php ENDPATH**/ ?>