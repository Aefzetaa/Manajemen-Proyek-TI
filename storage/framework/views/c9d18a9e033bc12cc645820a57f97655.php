<style>
#minigame-wrapper.portal-active {
    inset: 0;
    width: var(--mg-arcade-portal-cover-width);
    min-width: var(--mg-arcade-portal-cover-width);
    height: var(--mg-arcade-portal-cover-height);
    min-height: var(--mg-arcade-portal-cover-height);
    overflow: hidden;
    background: #0b0b10;
}

#minigame-wrapper.portal-active > .mg-topbar { display: none; }

#mg-screen-portal {
    position: absolute;
    inset: 0;
    z-index: 1;
    display: none;
    pointer-events: none;
    max-width: none;
    width: 100%;
    height: 100%;
    margin: 0;
    padding: 0;
    overflow: hidden;
    background: #0b0b10;
}

#mg-screen-portal.active {
    display: block;
    pointer-events: auto;
}

.mg-portal-shell {
    --portal-bg: #101017;
    --portal-panel: #202029;
    --portal-border: rgba(255, 255, 255, 0.12);
    --portal-border-strong: rgba(255, 255, 255, 0.20);
    --portal-text: #f8fafc;
    --portal-muted: #a8a8b3;
    --portal-amber: #ffb000;
    --portal-cyan: #00d9ff;
    --portal-green: #00e7a7;
    --portal-red: #ff5f6d;
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    display: grid;
    grid-template-columns: 270px minmax(0, 1fr);
    background:
        radial-gradient(circle at 18% 8%, rgba(255, 176, 0, 0.08), transparent 22%),
        radial-gradient(circle at 82% 12%, rgba(255, 176, 0, 0.05), transparent 24%),
        linear-gradient(135deg, #0a0a0f 0%, var(--portal-bg) 55%, #0b0b11 100%);
    color: var(--portal-text);
}

.mg-portal-shell.theme-cyber-night {
    --portal-bg: #101022;
    background:
        radial-gradient(circle at 18% 8%, rgba(0, 217, 255, 0.12), transparent 23%),
        radial-gradient(circle at 82% 12%, rgba(124, 58, 237, 0.18), transparent 26%),
        linear-gradient(135deg, #070710 0%, #101022 55%, #080814 100%);
}

.mg-portal-shell.theme-solar-vault {
    --portal-bg: #16110b;
    background:
        radial-gradient(circle at 18% 8%, rgba(255, 176, 0, 0.18), transparent 24%),
        radial-gradient(circle at 82% 12%, rgba(245, 158, 11, 0.14), transparent 28%),
        linear-gradient(135deg, #0d0a08 0%, #17110b 58%, #0d0b08 100%);
}

.mg-portal-shell.theme-mono-luxe {
    --portal-bg: #0d0d10;
    background:
        radial-gradient(circle at 18% 8%, rgba(255, 255, 255, 0.08), transparent 22%),
        radial-gradient(circle at 82% 12%, rgba(148, 163, 184, 0.10), transparent 26%),
        linear-gradient(135deg, #070707 0%, #111114 55%, #080809 100%);
}

.mg-portal-shell.theme-quiz-theme-dark {
    --portal-bg: #090914;
    background:
        radial-gradient(circle at 18% 8%, rgba(88, 28, 135, 0.18), transparent 24%),
        radial-gradient(circle at 82% 12%, rgba(2, 132, 199, 0.12), transparent 26%),
        linear-gradient(135deg, #050509 0%, #0a0a17 58%, #05050b 100%);
}

.mg-portal-shell.theme-quiz-theme-hologram {
    --portal-bg: #0b1116;
    background:
        radial-gradient(circle at 18% 8%, rgba(45, 212, 191, 0.13), transparent 24%),
        radial-gradient(circle at 82% 12%, rgba(244, 114, 182, 0.13), transparent 26%),
        linear-gradient(135deg, #06080d 0%, #0b1116 58%, #090812 100%);
}
.mg-portal-shell,
.mg-portal-shell button,
.mg-portal-shell input,
.mg-portal-shell select { font-family: var(--mg-font); }

.mg-portal-sidebar {
    min-height: 100%;
    display: flex;
    flex-direction: column;
    background: rgba(8, 8, 12, 0.95);
    border-right: 1px solid var(--portal-border);
}

.mg-portal-brand {
    min-height: 92px;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1.45rem 1.55rem;
    border-bottom: 1px solid var(--portal-border);
}

.mg-portal-logo {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    display: grid;
    place-items: center;
    background: linear-gradient(135deg, #ffbd12, #ff8a00);
    color: #111827;
    font-size: 1.25rem;
    box-shadow: 0 18px 35px rgba(255, 160, 0, 0.24);
}

.mg-portal-logo-image {
    overflow: hidden;
    padding: 0;
    background: #050506;
    border: 1px solid rgba(248, 195, 90, 0.22);
}

.mg-portal-logo-image img {
    width: 100%;
    height: 100%;
    display: block;
    object-fit: cover;
    object-position: center;
}

.mg-portal-brand-title {
    margin: 0;
    font-size: 1.02rem;
    font-weight: 950;
    letter-spacing: -0.04em;
}

.mg-portal-brand-subtitle,
.mg-muted,
.mg-balance-label,
.mg-small { color: var(--portal-muted); }

.mg-portal-nav {
    display: grid;
    gap: 0.42rem;
    padding: 1.25rem 0.85rem;
}

.mg-portal-tab,
.mg-shop-cat,
.mg-history-filter {
    border: 1px solid transparent;
    display: flex;
    align-items: center;
    gap: 0.7rem;
    background: transparent;
    color: rgba(248, 250, 252, 0.72);
    cursor: pointer;
    font-weight: 850;
    text-align: left;
    transition: border-color 0.16s ease, background 0.16s ease, color 0.16s ease, transform 0.16s ease;
}

.mg-portal-tab {
    width: 100%;
    border-radius: 10px;
    padding: 0.88rem 1rem;
}

.mg-portal-tab:hover,
.mg-portal-tab.active,
.mg-shop-cat:hover,
.mg-shop-cat.active,
.mg-history-filter:hover,
.mg-history-filter.active {
    color: var(--portal-amber);
    border-color: rgba(255, 176, 0, 0.36);
    background: rgba(255, 176, 0, 0.12);
}

.mg-portal-tab-icon,
.mg-action-icon,
.mg-shop-cat-icon,
.mg-history-icon,
.mg-stat-icon {
    display: inline-grid;
    place-items: center;
    flex: 0 0 auto;
}

.mg-portal-tab-icon {
    width: 22px;
    height: 22px;
    font-size: 1rem;
}

.mg-portal-svg {
    width: 1em;
    height: 1em;
    display: block;
    fill: none;
    stroke: currentColor;
    stroke-width: 2;
    stroke-linecap: round;
    stroke-linejoin: round;
}
.mg-game-symbol .mg-portal-svg,
.mg-balance-big-icon .mg-portal-svg,
.mg-welcome-icon .mg-portal-svg,
.mg-splash-mark .mg-portal-svg { width: 60%; height: 60%; }

.mg-portal-sidebar-foot {
    margin-top: auto;
    padding: 1rem 1rem 1.15rem;
    border-top: 1px solid var(--portal-border);
}

.mg-portal-copy {
    margin-top: 1rem;
    font-size: 0.72rem;
    color: rgba(248, 250, 252, 0.45);
    text-align: center;
}

.mg-portal-main {
    min-width: 0;
    min-height: 0;
    display: grid;
    grid-template-rows: 72px minmax(0, 1fr);
}

.mg-portal-header {
    min-width: 0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 0 1.45rem;
    border-bottom: 1px solid var(--portal-border);
    background: rgba(10, 10, 15, 0.72);
}

.mg-mobile-menu-button { display: none; }

.mg-portal-screen-name {
    margin: 0;
    font-size: 1.2rem;
    font-weight: 900;
    letter-spacing: -0.04em;
}

.mg-header-actions,
.mg-balance-chip,
.mg-action-row,
.mg-rank-row,
.mg-card-row,
.mg-modal-title-row {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.mg-balance-chip {
    min-height: 40px;
    border: 1px solid rgba(255, 176, 0, 0.35);
    border-radius: 10px;
    padding: 0.45rem 0.85rem;
    background: rgba(255, 176, 0, 0.11);
    font-weight: 900;
}

.mg-zp-icon {
    width: 1.45rem;
    height: 1.45rem;
    border-radius: 999px;
    display: inline-grid;
    place-items: center;
    background: radial-gradient(circle at 34% 25%, #ffe08a, #ffab00 58%, #a04f00 100%);
    color: #15110a;
    font-size: 0.76rem;
    font-weight: 950;
    box-shadow: 0 0 18px rgba(255, 176, 0, 0.22);
}

.mg-portal-stage {
    min-height: 0;
    height: 100%;
    overflow: hidden;
    scrollbar-width: none;
}

.mg-portal-stage::-webkit-scrollbar { display: none; }

.mg-portal-content {
    display: none;
    min-height: 100%;
    padding: 2.25rem 2.75rem;
    pointer-events: none;
    animation: mgPortalIn 0.24s ease both;
}

.mg-portal-content.active {
    display: block;
    pointer-events: auto;
}

@keyframes mgPortalIn {
    from { opacity: 0; transform: translateY(8px); }
    to { opacity: 1; transform: translateY(0); }
}

.mg-portal-container {
    width: min(1480px, 100%);
    margin: 0 auto;
}

.mg-portal-container.wide { width: min(1620px, 100%); }

.mg-section-head {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 1.55rem;
}

.mg-kicker,
.mg-card-kicker {
    color: var(--portal-amber);
    font-size: 0.72rem;
    font-weight: 950;
    letter-spacing: 0.16em;
    text-transform: uppercase;
}

.mg-section-title {
    margin: 0.25rem 0 0;
    font-size: clamp(1.65rem, 3vw, 2.75rem);
    line-height: 1;
    letter-spacing: -0.055em;
    font-weight: 950;
}

.mg-section-copy {
    max-width: 690px;
    margin: 0.55rem 0 0;
    color: var(--portal-muted);
    line-height: 1.55;
}

.mg-home-balances,
.mg-home-actions,
.mg-games-grid,
.mg-shop-grid,
.mg-profile-stats,
.mg-rank-list,
.mg-history-summary,
.mg-modal-grid {
    display: grid;
    gap: 1rem;
}

.mg-home-balances,
.mg-home-actions,
.mg-history-summary { grid-template-columns: repeat(2, minmax(0, 1fr)); }

.mg-games-grid,
.mg-profile-stats { grid-template-columns: repeat(3, minmax(0, 1fr)); }
.mg-shop-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); }

.mg-balance-card,
.mg-clean-card,
.mg-action-card,
.mg-game-card,
.mg-shop-card,
.mg-profile-hero,
.mg-stat-box,
.mg-rank-panel,
.mg-history-row,
.mg-summary-card,
.mg-custom-panel,
.mg-modal-box {
    border: 1px solid var(--portal-border-strong);
    background: linear-gradient(145deg, rgba(34, 34, 43, 0.94), rgba(25, 25, 33, 0.94));
    box-shadow: 0 24px 70px rgba(0, 0, 0, 0.24);
}

.mg-balance-card {
    min-height: 154px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.65rem;
    overflow: hidden;
}

.mg-balance-card.gold {
    border-color: rgba(255, 176, 0, 0.34);
    background:
        radial-gradient(circle at 70% 16%, rgba(255, 176, 0, 0.14), transparent 34%),
        linear-gradient(145deg, rgba(64, 38, 12, 0.88), rgba(32, 27, 24, 0.95));
}

.mg-balance-card.cyan {
    border-color: rgba(0, 217, 255, 0.22);
    background:
        radial-gradient(circle at 75% 14%, rgba(0, 217, 255, 0.13), transparent 34%),
        linear-gradient(145deg, rgba(11, 45, 56, 0.9), rgba(18, 30, 38, 0.95));
}

.mg-balance-big-icon {
    width: 48px;
    height: 48px;
    border-radius: 999px;
    display: grid;
    place-items: center;
    background: var(--portal-amber);
    color: #15110a;
    font-size: 1.25rem;
    font-weight: 950;
}

.mg-balance-card.cyan .mg-balance-big-icon { background: var(--portal-cyan); }

.mg-balance-value {
    margin-top: 0.2rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: clamp(1.65rem, 3vw, 2.35rem);
    font-weight: 950;
    letter-spacing: -0.05em;
}

.mg-balance-card.cyan .mg-balance-value { color: var(--portal-cyan); font-size: clamp(1.15rem, 1.8vw, 1.85rem); }
.mg-balance-value span { overflow: hidden; text-overflow: ellipsis; }

.mg-balance-note {
    margin: 0.65rem 0 0;
    color: rgba(248, 250, 252, 0.56);
    font-size: 0.82rem;
    line-height: 1.5;
}

.mg-home-actions { margin-top: 2rem; }

.mg-action-card {
    min-height: 96px;
    border-radius: 15px;
    border-color: rgba(255, 255, 255, 0.16);
    padding: 1rem 1.35rem;
    justify-content: flex-start;
    color: var(--portal-text);
    cursor: pointer;
}

.mg-action-card.primary {
    border: 0;
    background: linear-gradient(135deg, #ffb000, #ed7300);
    color: #fff;
}

.mg-action-card:hover,
.mg-game-card:hover,
.mg-shop-card:hover,
.mg-history-row:hover {
    transform: translateY(-2px);
    border-color: rgba(255, 176, 0, 0.34);
}

.mg-action-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    background: rgba(0, 0, 0, 0.18);
    font-size: 1.45rem;
}

.mg-clean-card {
    margin-top: 2rem;
    border-radius: 15px;
    padding: 1.45rem;
}

.mg-fee-list,
.mg-history-list,
.mg-shop-tabs {
    display: grid;
    gap: 0.75rem;
}

.mg-fee-item {
    display: grid;
    grid-template-columns: minmax(0, 1fr) auto;
    align-items: center;
    gap: 1rem;
    padding: 0.95rem 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
}

.mg-fee-item:last-child { border-bottom: 0; }

.mg-game-card {
    position: relative;
    min-height: 236px;
    display: flex;
    flex-direction: column;
    border-radius: 15px;
    padding: 1.55rem;
    overflow: hidden;
    transition: transform 0.16s ease, border-color 0.16s ease;
}

.mg-game-card.ttt {
    border-color: rgba(216, 72, 255, 0.26);
    background:
        radial-gradient(circle at 84% 10%, rgba(255, 255, 255, 0.11), transparent 18%),
        linear-gradient(135deg, rgba(73, 28, 92, 0.96), rgba(96, 37, 77, 0.9));
}

.mg-game-card.rps {
    border-color: rgba(0, 217, 255, 0.22);
    background:
        radial-gradient(circle at 68% 8%, rgba(255, 255, 255, 0.10), transparent 16%),
        linear-gradient(135deg, rgba(13, 54, 92, 0.96), rgba(17, 74, 94, 0.9));
}

.mg-game-card.quiz {
    border-color: rgba(0, 231, 167, 0.24);
    background:
        radial-gradient(circle at 86% 14%, rgba(255, 176, 0, 0.12), transparent 18%),
        linear-gradient(135deg, rgba(12, 73, 55, 0.96), rgba(11, 86, 73, 0.88));
}

.mg-game-symbol {
    position: absolute;
    right: 1rem;
    top: 0.4rem;
    color: rgba(255, 255, 255, 0.12);
    font-size: 4.1rem;
    font-weight: 950;
    letter-spacing: -0.12em;
}

.mg-game-card h3,
.mg-shop-card h3 {
    margin: 0.6rem 0;
    font-size: 1.18rem;
    font-weight: 950;
}

.mg-game-card p,
.mg-shop-card p {
    margin: 0;
    color: rgba(248, 250, 252, 0.72);
    line-height: 1.5;
}

.mg-game-meta {
    margin: 1rem 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: rgba(248, 250, 252, 0.64);
    font-size: 0.82rem;
}

.mg-game-card .mg-btn {
    margin-top: auto;
    width: 100%;
    border: 1px solid rgba(255, 255, 255, 0.22);
    background: rgba(255, 255, 255, 0.11);
}

.mg-stat-box {
    border-radius: 14px;
    padding: 1.35rem;
    text-align: center;
}

.mg-stat-value {
    color: var(--portal-amber);
    font-size: 1.45rem;
    font-weight: 950;
}

.mg-shop-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 1.45rem;
}

.mg-shop-tabs { grid-template-columns: repeat(3, max-content); }

.mg-shop-cat,
.mg-history-filter {
    border-color: var(--portal-border-strong);
    border-radius: 9px;
    padding: 0.78rem 1rem;
    background: rgba(255, 255, 255, 0.04);
}

.mg-shop-card {
    min-height: 310px;
    border-radius: 15px;
    padding: 1.55rem;
    text-align: center;
    cursor: pointer;
    transition: transform 0.16s ease, border-color 0.16s ease;
}

.mg-shop-emoji {
    width: 64px;
    height: 64px;
    margin: 0 auto 1rem;
    display: grid;
    place-items: center;
    border-radius: 14px;
    border: 1px solid rgba(255, 176, 0, 0.26);
    background: rgba(255, 176, 0, 0.15);
    font-size: 2rem;
}

.mg-price-pill,
.mg-status-pill {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.38rem;
    width: fit-content;
    border-radius: 999px;
    color: var(--portal-amber);
    font-weight: 950;
}

.mg-price-pill { margin: 1rem auto; }

.mg-shop-card.owned {
    border-color: rgba(0, 231, 167, 0.28);
    background:
        radial-gradient(circle at 50% 0%, rgba(0, 231, 167, 0.09), transparent 30%),
        linear-gradient(145deg, rgba(32, 39, 39, 0.94), rgba(24, 29, 31, 0.94));
}

.mg-modal-status-pill {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: fit-content;
    margin: 0.75rem auto 0;
    border: 1px solid rgba(0, 231, 167, 0.28);
    border-radius: 999px;
    padding: 0.35rem 0.7rem;
    color: var(--portal-green);
    background: rgba(0, 231, 167, 0.08);
    font-size: 0.72rem;
    font-weight: 950;
}

.mg-shop-card .mg-btn:disabled,
.mg-modal-actions .mg-btn:disabled {
    cursor: not-allowed;
    color: rgba(248, 250, 252, 0.62);
    border-color: rgba(0, 231, 167, 0.25);
    background: rgba(0, 231, 167, 0.10);
}
.mg-shop-card .mg-btn {
    width: 100%;
    border: 1px solid rgba(255, 176, 0, 0.34);
    background: rgba(255, 176, 0, 0.14);
}

.mg-shop-balance-bar {
    margin-top: 2rem;
    border: 1px solid var(--portal-border-strong);
    border-radius: 12px;
    padding: 1rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: rgba(255, 255, 255, 0.05);
}

.mg-profile-hero {
    width: min(960px, 100%);
    margin: 0 auto 2rem;
    border-radius: 14px;
    padding: 2.25rem 2.75rem;
    display: grid;
    grid-template-columns: auto minmax(0, 1fr) auto;
    align-items: center;
    gap: 1.45rem;
    background:
        radial-gradient(circle at 74% 0%, rgba(255, 176, 0, 0.13), transparent 28%),
        linear-gradient(145deg, rgba(39, 39, 47, 0.96), rgba(28, 28, 36, 0.96));
}

.mg-profile-avatar-wrapper {
    position: relative;
    width: 96px;
    height: 96px;
    aspect-ratio: 1 / 1;
    border-radius: 999px;
    display: grid;
    place-items: center;
    background: linear-gradient(135deg, #ffc024, #ff9700);
    border: 4px solid rgba(255, 176, 0, 0.36);
    color: #111827;
    font-size: 2rem;
    box-shadow: 0 18px 35px rgba(255, 176, 0, 0.28);
}

.mg-profile-avatar-wrapper img {
    position: absolute;
    inset: 9px;
    width: calc(100% - 18px);
    height: calc(100% - 18px);
    border-radius: 50%;
    object-fit: cover;
    object-position: center;
    background: rgba(18, 24, 28, 0.8);
    z-index: 1;
}

.mg-profile-online {
    position: absolute;
    right: 0.15rem;
    bottom: 0.15rem;
    width: 26px;
    height: 26px;
    border: 5px solid #202029;
    border-radius: 999px;
    background: var(--portal-green);
    z-index: 2;
}

.mg-profile-stats {
    width: min(960px, 100%);
    margin: 0 auto 2rem;
}

.mg-profile-stats .mg-stat-box {
    text-align: left;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.mg-stat-icon {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    background: rgba(255, 176, 0, 0.15);
    color: var(--portal-amber);
    font-size: 1.3rem;
}

.mg-rank-panel,
.mg-custom-panel {
    width: min(960px, 100%);
    margin: 0 auto 2rem;
    border-radius: 14px;
    padding: 1.55rem;
}

.mg-rank-list { margin-top: 1rem; }

.mg-rank-row {
    justify-content: space-between;
    border-radius: 10px;
    padding: 0.9rem;
    background: rgba(255, 255, 255, 0.05);
}

.mg-rank-name {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.mg-custom-select {
    width: 100%;
    margin-top: 0.45rem;
    background: #0c0c12;
    border: 1px solid rgba(255, 255, 255, 0.13);
    color: var(--portal-text);
    border-radius: 10px;
    padding: 0.9rem 1rem;
    font-weight: 850;
}

.mg-form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}

.mg-history-tabs {
    display: flex;
    gap: 0.7rem;
    margin-bottom: 2rem;
}

#portal-tab-history {
    box-sizing: border-box;
    height: 100%;
    min-height: 0;
    overflow-x: hidden;
    overflow-y: auto;
    padding-bottom: 2.5rem;
    scrollbar-width: thin;
    scrollbar-color: rgba(255, 210, 120, 0.42) rgba(255, 255, 255, 0.05);
}

#portal-tab-history.active {
    display: block;
}

#portal-tab-history .mg-section-head {
    flex: 0 0 auto;
}

#portal-tab-history .mg-portal-container {
    min-height: auto;
    height: auto;
    display: block;
    padding-bottom: 1.75rem;
}

.mg-history-list {
    width: min(960px, 100%);
    margin: 0 auto;
    min-height: 0;
    overflow: visible;
}

#portal-tab-history::-webkit-scrollbar { width: 8px; }
#portal-tab-history::-webkit-scrollbar-track { background: rgba(255, 255, 255, 0.05); border-radius: 999px; }
#portal-tab-history::-webkit-scrollbar-thumb { background: rgba(255, 210, 120, 0.42); border-radius: 999px; }
#portal-tab-history::-webkit-scrollbar-thumb:hover { background: rgba(255, 210, 120, 0.62); }

.mg-history-row {
    grid-template-columns: auto minmax(0, 1fr) auto;
    display: grid;
    align-items: center;
    gap: 1rem;
    border-radius: 14px;
    padding: 1rem;
    margin-bottom: 0.8rem;
    transition: transform 0.16s ease, border-color 0.16s ease;
}

.mg-history-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.06);
    font-size: 1.35rem;
}

.mg-history-amount.in { color: var(--portal-green); }
.mg-history-amount.out,
.mg-history-amount.zero { color: var(--portal-red); }

.mg-history-summary {
    width: min(960px, 100%);
    margin: 2rem auto 0;
    flex: 0 0 auto;
}

.mg-summary-card {
    border-radius: 14px;
    padding: 1.15rem;
}

.mg-summary-card.in {
    border-color: rgba(0, 231, 167, 0.25);
    background: rgba(0, 231, 167, 0.08);
}

.mg-summary-card.out {
    border-color: rgba(255, 95, 109, 0.25);
    background: rgba(255, 95, 109, 0.08);
}

.mg-summary-value {
    margin-top: 0.35rem;
    font-size: 1.45rem;
    font-weight: 950;
}

.mg-arcade-splash {
    position: absolute;
    inset: 0;
    z-index: 74;
    display: none;
    place-items: center;
    padding: 2.25rem 2.75rem;
    background: rgba(8, 8, 12, 0.78);
    backdrop-filter: blur(9px);
}

.mg-arcade-splash.active { display: grid; }

.mg-splash-card {
    width: min(420px, 100%);
    border: 1px solid rgba(255, 176, 0, 0.28);
    border-radius: 20px;
    padding: 1.8rem;
    text-align: center;
    background: linear-gradient(145deg, rgba(32, 32, 41, 0.96), rgba(12, 12, 18, 0.98));
    box-shadow: 0 34px 90px rgba(0, 0, 0, 0.52);
    animation: mgPortalIn 0.28s ease both;
}

.mg-splash-mark {
    width: 68px;
    height: 68px;
    margin: 0 auto 1rem;
    display: grid;
    place-items: center;
    border-radius: 20px;
    background: linear-gradient(135deg, #ffbd12, #ff8a00);
    color: #111827;
    font-weight: 950;
    letter-spacing: -0.06em;
}
.mg-arcade-welcome {
    position: absolute;
    inset: 0;
    z-index: 70;
    display: none;
    place-items: center;
    padding: 2.25rem 2.75rem;
    background:
        radial-gradient(circle at 22% 18%, rgba(255, 176, 0, 0.18), transparent 26%),
        radial-gradient(circle at 78% 70%, rgba(0, 217, 255, 0.10), transparent 26%),
        rgba(8, 8, 12, 0.92);
    backdrop-filter: blur(10px);
}

.mg-arcade-welcome { pointer-events: none; }
.mg-arcade-welcome.active { pointer-events: auto; }
.mg-welcome-card { pointer-events: auto; }

.mg-arcade-welcome.active { display: grid; }

.mg-portal-shell.arcade-unregistered .mg-portal-sidebar,
.mg-portal-shell.arcade-unregistered .mg-portal-main,
.mg-portal-shell.arcade-unregistered .mg-floating-social {
    pointer-events: none;
    filter: blur(2px) saturate(0.8);
}

.mg-welcome-card {
    width: min(560px, 100%);
    border: 1px solid rgba(255, 176, 0, 0.34);
    border-radius: 20px;
    padding: 2.25rem 2.75rem;
    background:
        radial-gradient(circle at 76% 0%, rgba(255, 176, 0, 0.18), transparent 32%),
        linear-gradient(145deg, rgba(32, 32, 41, 0.98), rgba(14, 14, 21, 0.98));
    box-shadow: 0 34px 100px rgba(0, 0, 0, 0.54);
    text-align: center;
}

.mg-welcome-icon {
    width: 76px;
    height: 76px;
    margin: 0 auto 1rem;
    display: grid;
    place-items: center;
    border-radius: 22px;
    background: linear-gradient(135deg, #ffbd12, #ff8a00);
    color: #111827;
    font-size: 2rem;
    box-shadow: 0 20px 42px rgba(255, 176, 0, 0.28);
}

.mg-welcome-card h2 {
    margin: 0;
    font-size: clamp(1.8rem, 3vw, 2.6rem);
    letter-spacing: -0.055em;
}

.mg-welcome-card p {
    margin: 0.8rem auto 0;
    max-width: 440px;
    color: var(--portal-muted);
    line-height: 1.6;
}

.mg-welcome-actions {
    margin-top: 1.4rem;
    display: grid;
    gap: 0.75rem;
}
.mg-qris-overlay {
    position: absolute;
    inset: 0;
    z-index: 95;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 1.25rem;
    background: rgba(0, 0, 0, 0.78);
    backdrop-filter: blur(12px);
}

.mg-qris-overlay.active { display: flex; }

.mg-qris-card {
    width: min(92vw, 390px);
    border: 1px solid rgba(255, 176, 0, 0.35);
    border-radius: 18px;
    padding: 1.4rem;
    background: linear-gradient(145deg, rgba(31, 31, 40, 0.98), rgba(13, 13, 19, 0.98));
    box-shadow: 0 30px 90px rgba(0, 0, 0, 0.58);
    text-align: center;
}

.mg-qris-grid {
    width: 164px;
    height: 164px;
    margin: 1.1rem auto;
    border: 10px solid #f8fafc;
    border-radius: 14px;
    background:
        linear-gradient(90deg, #111 12px, transparent 12px) 0 0 / 28px 28px,
        linear-gradient(#111 12px, transparent 12px) 0 0 / 28px 28px,
        radial-gradient(circle at 22% 22%, #111 0 18px, transparent 19px),
        radial-gradient(circle at 78% 22%, #111 0 18px, transparent 19px),
        radial-gradient(circle at 22% 78%, #111 0 18px, transparent 19px),
        #fff;
    position: relative;
    overflow: hidden;
}

.mg-qris-grid::after {
    content: "";
    position: absolute;
    left: -20%;
    right: -20%;
    top: 0;
    height: 24px;
    background: linear-gradient(90deg, transparent, rgba(0, 217, 255, 0.75), transparent);
    animation: mgQrisScan 1.15s ease-in-out infinite;
}

@keyframes mgQrisScan {
    from { transform: translateY(-24px); }
    to { transform: translateY(174px); }
}

.mg-qris-progress {
    height: 8px;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.10);
    overflow: hidden;
}

.mg-qris-progress span {
    display: block;
    width: 42%;
    height: 100%;
    border-radius: inherit;
    background: linear-gradient(90deg, #ffb000, #00d9ff);
    animation: mgQrisMove 1.05s ease-in-out infinite alternate;
}

@keyframes mgQrisMove {
    from { transform: translateX(-35%); }
    to { transform: translateX(165%); }
}
.mg-process-spinner {
    width: 74px;
    height: 74px;
    margin: 1.2rem auto;
    border-radius: 999px;
    border: 7px solid rgba(255, 255, 255, 0.10);
    border-top-color: var(--portal-amber);
    border-right-color: var(--portal-cyan);
    animation: mgPortalSpin 0.82s linear infinite;
    box-shadow: 0 0 32px rgba(255, 176, 0, 0.18);
}

@keyframes mgPortalSpin {
    to { transform: rotate(360deg); }
}
.mg-modal-overlay {
    position: absolute;
    inset: 0;
    z-index: 80;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 1.25rem;
    background: rgba(0, 0, 0, 0.74);
    backdrop-filter: blur(10px);
}

.mg-modal-overlay.active { display: flex; }

.mg-portal-modal {
    width: min(94vw, 450px);
    max-height: min(86vh, 640px);
    overflow: hidden;
    border: 1px solid var(--portal-border-strong);
    border-radius: 16px;
    background: #1d1d26;
    padding: 1.45rem;
    box-shadow: 0 32px 90px rgba(0, 0, 0, 0.58);
    animation: mgModalPop 0.2s ease both;
}

@keyframes mgModalPop {
    from { opacity: 0; transform: translateY(12px) scale(0.98); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}

.mg-modal-title-row {
    justify-content: space-between;
    margin-bottom: 1rem;
}

.mg-modal-heading {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.mg-modal-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: grid;
    place-items: center;
    background: rgba(255, 176, 0, 0.14);
    color: var(--portal-amber);
    font-size: 1.45rem;
}

.mg-modal-close {
    width: 32px;
    height: 32px;
    border-radius: 10px;
    border: 1px solid var(--portal-border-strong);
    background: rgba(255, 255, 255, 0.06);
    color: var(--portal-text);
    cursor: pointer;
}

.mg-modal-option,
.mg-modal-input-card {
    width: 100%;
    border: 1px solid var(--portal-border-strong);
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.05);
    color: var(--portal-text);
    padding: 1rem;
}

.mg-modal-option {
    display: flex;
    align-items: center;
    gap: 0.85rem;
    text-align: left;
    cursor: pointer;
}

.mg-modal-option.active,
.mg-modal-option:hover {
    border-color: rgba(255, 176, 0, 0.5);
    background: rgba(255, 176, 0, 0.10);
}

.mg-modal-option-list {
    display: grid;
    gap: 0.75rem;
    max-height: 342px;
    overflow: hidden;
    padding-right: 0;
}

.mg-modal-input-card input {
    width: 100%;
    margin-top: 0.45rem;
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 10px;
    background: #0d0d12;
    color: var(--portal-text);
    padding: 0.9rem;
    font-weight: 850;
}

.mg-modal-preview-icon {
    width: 96px;
    height: 96px;
    margin: 0 auto 1rem;
    border-radius: 15px;
    display: grid;
    place-items: center;
    border: 1px solid rgba(255, 176, 0, 0.28);
    background: rgba(255, 176, 0, 0.13);
    font-size: 3rem;
}

.mg-modal-actions {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 0.75rem;
    margin-top: 1rem;
}

.mg-empty-state {
    min-height: 180px;
    display: grid;
    place-items: center;
    color: var(--portal-muted);
    border: 1px dashed rgba(255, 255, 255, 0.16);
    border-radius: 14px;
    background: rgba(255, 255, 255, 0.035);
}

@media (max-width: 1180px) {
    .mg-portal-shell { grid-template-columns: 1fr; }

    .mg-portal-sidebar {
        display: none;
        position: absolute;
        inset: 0 auto 0 0;
        z-index: 60;
        width: min(86vw, 310px);
    }

    .mg-portal-sidebar.mobile-open { display: flex; }
    .mg-mobile-menu-button { display: inline-flex; }

    .mg-home-balances,
    .mg-home-actions,
    .mg-games-grid,
    .mg-shop-grid,
    .mg-profile-stats,
    .mg-form-grid,
    .mg-history-summary { grid-template-columns: 1fr; }

    .mg-shop-tabs { grid-template-columns: 1fr; }

    .mg-profile-hero {
        grid-template-columns: 1fr;
        text-align: center;
    }
}

@media (max-width: 640px) {
    .mg-portal-content { padding: 1rem; }

    .mg-portal-header,
    .mg-section-head,
    .mg-shop-toolbar {
        align-items: stretch;
        flex-direction: column;
        height: auto;
        padding-top: 1rem;
        padding-bottom: 1rem;
    }

    .mg-header-actions,
    .mg-action-row,
    .mg-modal-actions {
        width: 100%;
        flex-direction: column;
        grid-template-columns: 1fr;
    }

    .mg-balance-chip,
    .mg-header-actions .mg-btn {
        width: 100%;
        justify-content: center;
    }
}

/* Revamp 1.5: ZeroPay-only portal cleanup */
.mg-portal-shell {
    --portal-bg: #0a0a0b;
    --portal-panel: #201f20;
    --portal-border: rgba(195, 245, 255, 0.13);
    --portal-border-strong: rgba(195, 245, 255, 0.22);
    --portal-text: #e5e2e3;
    --portal-muted: #bac9cc;
    --portal-amber: #00daf3;
    --portal-cyan: #c3f5ff;
    --portal-green: #00ee8a;
    --portal-red: #ff6f7b;
    background:
        linear-gradient(135deg, rgba(0, 218, 243, 0.10), transparent 34%),
        linear-gradient(225deg, rgba(207, 92, 255, 0.12), transparent 38%),
        repeating-linear-gradient(90deg, rgba(255,255,255,0.022) 0, rgba(255,255,255,0.022) 1px, transparent 1px, transparent 76px),
        #0a0a0b;
}
.mg-portal-sidebar {
    background: rgba(14, 14, 15, 0.88);
    backdrop-filter: blur(18px);
}
.mg-portal-logo,
.mg-welcome-icon,
.mg-splash-mark,
.mg-balance-big-icon {
    background: linear-gradient(135deg, #9cf0ff, #00daf3);
    color: #001f24;
    box-shadow: 0 0 34px rgba(0, 218, 243, 0.20);
}
.mg-portal-tab:hover,
.mg-portal-tab.active,
.mg-shop-cat:hover,
.mg-shop-cat.active,
.mg-profile-filter.active {
    border-color: rgba(0, 218, 243, 0.44);
    background: rgba(0, 218, 243, 0.10);
    color: #c3f5ff;
}
.mg-balance-chip,
.mg-home-title-card,
.mg-role-badge,
.mg-game-card .mg-btn,
.mg-custom-panel .mg-btn-primary,
.mg-modal-actions .mg-btn-primary {
    border-color: rgba(0, 218, 243, 0.34);
}
.mg-home-title-card {
    background:
        linear-gradient(145deg, rgba(32, 31, 32, 0.94), rgba(14, 14, 15, 0.94));
}
.mg-game-card .mg-btn,
.mg-custom-panel .mg-btn-primary,
.mg-modal-actions .mg-btn-primary {
    background: linear-gradient(135deg, #9cf0ff, #00daf3) !important;
    color: #001f24 !important;
}
.mg-game-card.ttt {
    border-color: rgba(236, 178, 255, 0.34);
    background:
        linear-gradient(145deg, rgba(82, 0, 113, 0.78), rgba(27, 20, 39, 0.94));
}
.mg-game-card.rps {
    border-color: rgba(156, 240, 255, 0.34);
    background:
        linear-gradient(145deg, rgba(0, 98, 110, 0.72), rgba(17, 31, 37, 0.94));
}
.mg-game-card.quiz {
    border-color: rgba(91, 255, 161, 0.32);
    background:
        linear-gradient(145deg, rgba(0, 102, 56, 0.70), rgba(16, 34, 27, 0.94));
}
.mg-splash-card,
.mg-welcome-card,
.mg-qris-card,
.mg-portal-modal {
    border-color: rgba(195, 245, 255, 0.26);
    background: linear-gradient(145deg, rgba(32, 31, 32, 0.98), rgba(14, 14, 15, 0.98));
}
.mg-process-spinner {
    border-color: rgba(195, 245, 255, 0.16);
    border-top-color: #00daf3;
}
.mg-qris-progress span {
    background: linear-gradient(90deg, #00daf3, #cf5cff, #00ee8a);
}
.mg-lobby-transition-note {
    margin-top: 0.75rem;
    color: var(--portal-muted);
    font-size: 0.86rem;
    line-height: 1.5;
}
.mg-portal-logo { font-size: 1rem; }
.mg-portal-logo .mg-portal-svg { width: 62%; height: 62%; stroke-width: 2.25; }
.mg-balance-chip.compact-zp { gap: 0.55rem; min-width: 178px; justify-content: center; }
.mg-home-intro { display: grid; grid-template-columns: minmax(0, 0.95fr) minmax(0, 1.05fr); gap: 1rem; align-items: stretch; margin-bottom: 1.4rem; }
.mg-home-intro .mg-balance-card { min-height: 138px; }
.mg-home-title-card { border-radius: 15px; padding: 1.55rem; display: flex; flex-direction: column; justify-content: center; border: 1px solid rgba(255,176,0,.26); background: radial-gradient(circle at 82% 12%, rgba(255,176,0,.16), transparent 32%), linear-gradient(145deg, rgba(40,31,21,.9), rgba(24,24,31,.94)); }
.mg-home-title-card h1 { margin: .2rem 0 0; font-size: clamp(1.75rem, 3vw, 2.65rem); line-height: 1; letter-spacing: -.055em; }
.mg-mode-chips { margin: 1rem 0; display: flex; flex-wrap: wrap; gap: .5rem; }
.mg-mode-chip { border: 1px solid rgba(255,255,255,.18); border-radius: 999px; padding: .42rem .68rem; background: rgba(255,255,255,.08); color: rgba(248,250,252,.82); font-size: .78rem; font-weight: 900; }
.mg-game-card .mg-btn { border: 0; background: linear-gradient(135deg, #ffb000, #ed7300); color: #121016; font-weight: 950; }
.mg-game-card .mg-btn:hover { filter: brightness(1.06); }
.mg-game-card .mg-price-pill { display: none; }
.mg-shop-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); align-items: stretch; gap: 1.15rem; }
.mg-shop-tabs { grid-template-columns: repeat(3, max-content); margin-bottom: 1.3rem; }
.mg-shop-cat { justify-content: center; }
.mg-shop-card { min-height: 238px; padding: 1.28rem; }
.mg-shop-card .mg-shop-emoji .mg-portal-svg { width: 58%; height: 58%; }
.mg-shop-card p { min-height: 3.2em; }
.mg-autopilot-group { grid-column: 1 / -1; margin: .2rem 0 -.2rem; color: var(--portal-amber); font-weight: 950; letter-spacing: .1em; text-transform: uppercase; font-size: .76rem; }
.mg-profile-filter-row { width: min(960px,100%); margin: 0 auto 1rem; display: flex; justify-content: flex-end; gap: .55rem; flex-wrap: wrap; }
.mg-profile-filter { border: 1px solid var(--portal-border-strong); border-radius: 999px; background: rgba(255,255,255,.05); color: rgba(248,250,252,.76); padding: .52rem .8rem; font-weight: 900; cursor: pointer; }
.mg-profile-filter.active { color: var(--portal-amber); border-color: rgba(255,176,0,.38); background: rgba(255,176,0,.13); }
.mg-profile-filter-row, .mg-profile-filter, .mg-portal-tab, .mg-btn, .mg-shop-category, .mg-shop-card { position: relative; z-index: 2; }
.mg-portal-content:not(.active), .mg-portal-content[aria-hidden="true"] { pointer-events: none; }
.mg-portal-content.active { pointer-events: auto; }
.mg-role-badge { display: inline-flex; align-items: center; width: fit-content; margin-top: .48rem; border-radius: 999px; padding: .34rem .65rem; background: rgba(255,176,0,.12); border: 1px solid rgba(255,176,0,.34); color: var(--portal-amber); font-weight: 950; font-size: .78rem; }
.mg-rank-row.ttt .mg-status-pill { color: var(--portal-amber); background: rgba(255,176,0,.12); }
.mg-rank-row.rps .mg-status-pill { color: #c084fc; background: rgba(192,132,252,.12); }
.mg-rank-row.quiz .mg-status-pill { color: var(--portal-green); background: rgba(0,231,167,.10); }
.mg-custom-select, .mg-custom-select option { background: #08080d; color: var(--portal-text); }
.mg-history-list { margin: 0 auto; }
@media (max-width: 1180px) { .mg-shop-grid { grid-template-columns: repeat(3, minmax(0,1fr)); } .mg-home-intro { grid-template-columns: 1fr; } }
@media (max-width: 760px) { .mg-shop-grid, .mg-games-grid { grid-template-columns: 1fr; } }

/* Arcade portal skin: same dashboard discipline as the workshop UI, distinct ruby/gold palette. */
.mg-portal-shell {
    --portal-bg: #09070d;
    --portal-panel: #17131d;
    --portal-border: rgba(255, 79, 139, 0.15);
    --portal-border-strong: rgba(255, 210, 120, 0.22);
    --portal-text: #fbf7ff;
    --portal-muted: #b7adbf;
    --portal-amber: #ff4f8b;
    --portal-cyan: #ffd278;
    --portal-green: #8ef6bd;
    --portal-red: #ff6f7b;
    grid-template-columns: 286px minmax(0, 1fr);
    background:
        repeating-linear-gradient(90deg, rgba(255, 255, 255, 0.018) 0, rgba(255, 255, 255, 0.018) 1px, transparent 1px, transparent 92px),
        linear-gradient(135deg, #08070c 0%, #14101a 58%, #0b0709 100%);
}

.mg-portal-sidebar {
    background: rgba(10, 8, 14, 0.9);
    border-right-color: rgba(255, 79, 139, 0.16);
    backdrop-filter: blur(18px);
}

.mg-portal-brand {
    min-height: 86px;
    border-bottom-color: rgba(255, 79, 139, 0.16);
}

.mg-portal-logo,
.mg-welcome-icon,
.mg-splash-mark,
.mg-balance-big-icon,
.mg-shop-emoji {
    background: linear-gradient(135deg, #ff4f8b, #f8c35a);
    color: #16090d;
    box-shadow: 0 18px 38px rgba(255, 79, 139, 0.22);
}

.mg-portal-header {
    min-height: 72px;
    background: rgba(13, 11, 18, 0.78);
    border-bottom-color: rgba(255, 79, 139, 0.16);
    backdrop-filter: blur(16px);
}

.mg-portal-tab,
.mg-shop-cat,
.mg-history-filter,
.mg-profile-filter,
.mg-btn,
.mg-balance-chip,
.mg-balance-card,
.mg-clean-card,
.mg-action-card,
.mg-game-card,
.mg-shop-card,
.mg-profile-hero,
.mg-stat-box,
.mg-rank-panel,
.mg-history-row,
.mg-summary-card,
.mg-custom-panel,
.mg-modal-box,
.mg-welcome-card,
.mg-qris-card,
.mg-portal-modal,
.mg-splash-card {
    border-radius: 8px;
}

.mg-portal-tab:hover,
.mg-portal-tab.active,
.mg-shop-cat:hover,
.mg-shop-cat.active,
.mg-history-filter:hover,
.mg-history-filter.active,
.mg-profile-filter.active {
    color: #ffd278;
    border-color: rgba(255, 210, 120, 0.36);
    background: rgba(255, 210, 120, 0.09);
}

.mg-portal-tab.active {
    box-shadow: inset 3px 0 0 #ff4f8b;
}

.mg-balance-chip,
.mg-role-badge,
.mg-price-pill,
.mg-status-pill {
    color: #ffd278;
    border-color: rgba(255, 210, 120, 0.28);
    background: rgba(255, 210, 120, 0.09);
}

.mg-zp-icon {
    background: linear-gradient(135deg, #ff4f8b, #f8c35a);
    color: #16090d;
    box-shadow: 0 0 18px rgba(255, 79, 139, 0.26);
}

.mg-portal-content {
    padding: 2.35rem 2.65rem;
    animation: none;
}

.mg-portal-container {
    width: min(1520px, 100%);
    margin-left: 0;
    margin-right: auto;
}

.mg-portal-container.wide {
    width: min(1560px, 100%);
}

.mg-home-intro {
    grid-template-columns: minmax(0, 1.1fr) minmax(280px, 0.9fr);
}

.mg-games-grid {
    grid-template-columns: repeat(3, minmax(250px, 1fr));
}

.mg-shop-grid {
    grid-template-columns: repeat(4, minmax(210px, 1fr));
}

.mg-section-head {
    margin-bottom: 1.35rem;
}

.mg-kicker,
.mg-card-kicker,
.mg-autopilot-group {
    color: #ffd278;
}

.mg-section-title {
    letter-spacing: -0.035em;
}

.mg-balance-card,
.mg-clean-card,
.mg-action-card,
.mg-game-card,
.mg-shop-card,
.mg-profile-hero,
.mg-stat-box,
.mg-rank-panel,
.mg-history-row,
.mg-summary-card,
.mg-custom-panel,
.mg-modal-box {
    border-color: rgba(255, 210, 120, 0.18);
    background:
        repeating-linear-gradient(90deg, rgba(255, 255, 255, 0.014) 0, rgba(255, 255, 255, 0.014) 1px, transparent 1px, transparent 58px),
        linear-gradient(145deg, rgba(20, 17, 25, 0.94), rgba(11, 10, 15, 0.94));
    box-shadow: 0 24px 70px rgba(0, 0, 0, 0.26);
}

.mg-home-title-card {
    border-radius: 8px;
    border-color: rgba(255, 79, 139, 0.28);
    background:
        repeating-linear-gradient(90deg, rgba(255, 255, 255, 0.014) 0, rgba(255, 255, 255, 0.014) 1px, transparent 1px, transparent 64px),
        linear-gradient(145deg, rgba(30, 16, 29, 0.92), rgba(14, 12, 18, 0.96));
}

.mg-balance-card.gold {
    border-color: rgba(255, 79, 139, 0.28);
    background:
        repeating-linear-gradient(90deg, rgba(255, 255, 255, 0.014) 0, rgba(255, 255, 255, 0.014) 1px, transparent 1px, transparent 54px),
        linear-gradient(145deg, rgba(67, 20, 43, 0.82), rgba(21, 17, 23, 0.95));
}

.mg-balance-card.cyan {
    border-color: rgba(255, 210, 120, 0.24);
    background:
        repeating-linear-gradient(90deg, rgba(255, 255, 255, 0.014) 0, rgba(255, 255, 255, 0.014) 1px, transparent 1px, transparent 54px),
        linear-gradient(145deg, rgba(66, 45, 21, 0.72), rgba(22, 19, 21, 0.95));
}

.mg-balance-value,
.mg-stat-value,
.mg-balance-card.cyan .mg-balance-value {
    color: #ffd278;
}

.mg-action-card.primary,
.mg-game-card .mg-btn,
.mg-custom-panel .mg-btn-primary,
.mg-modal-actions .mg-btn-primary,
.mg-shop-card .mg-btn {
    background: linear-gradient(135deg, #ff4f8b, #f8c35a) !important;
    color: #16090d !important;
    border: 0 !important;
    font-weight: 950;
}

.mg-action-card:hover,
.mg-game-card:hover,
.mg-shop-card:hover,
.mg-history-row:hover {
    transform: translateY(-1px);
    border-color: rgba(255, 210, 120, 0.3);
}

.mg-game-card {
    min-height: 258px;
    padding: 1.5rem;
}

.mg-game-card.ttt {
    border-color: rgba(255, 79, 139, 0.34);
    background:
        repeating-linear-gradient(90deg, rgba(255, 255, 255, 0.014) 0, rgba(255, 255, 255, 0.014) 1px, transparent 1px, transparent 54px),
        linear-gradient(135deg, rgba(87, 20, 55, 0.9), rgba(25, 15, 25, 0.94));
}

.mg-game-card.rps {
    border-color: rgba(255, 210, 120, 0.3);
    background:
        repeating-linear-gradient(90deg, rgba(255, 255, 255, 0.014) 0, rgba(255, 255, 255, 0.014) 1px, transparent 1px, transparent 54px),
        linear-gradient(135deg, rgba(70, 47, 18, 0.88), rgba(23, 17, 22, 0.94));
}

.mg-game-card.quiz {
    border-color: rgba(126, 211, 255, 0.28);
    background:
        repeating-linear-gradient(90deg, rgba(255, 255, 255, 0.014) 0, rgba(255, 255, 255, 0.014) 1px, transparent 1px, transparent 54px),
        linear-gradient(135deg, rgba(24, 49, 73, 0.84), rgba(17, 16, 24, 0.94));
}

.mg-game-symbol {
    color: rgba(255, 210, 120, 0.14);
}

.mg-shop-grid {
    gap: 1.1rem;
}

.mg-shop-card {
    min-height: 254px;
}

.mg-shop-card.owned {
    border-color: rgba(142, 246, 189, 0.24);
    background:
        repeating-linear-gradient(90deg, rgba(255, 255, 255, 0.014) 0, rgba(255, 255, 255, 0.014) 1px, transparent 1px, transparent 58px),
        linear-gradient(145deg, rgba(24, 47, 36, 0.74), rgba(15, 17, 20, 0.94));
}

.mg-shop-card .mg-btn:disabled,
.mg-modal-actions .mg-btn:disabled {
    color: rgba(251, 247, 255, 0.58);
    border-color: rgba(142, 246, 189, 0.22) !important;
    background: rgba(142, 246, 189, 0.08) !important;
}

.mg-modal-status-pill,
.mg-history-amount.in {
    color: #8ef6bd;
}

.mg-profile-hero {
    background:
        repeating-linear-gradient(90deg, rgba(255, 255, 255, 0.014) 0, rgba(255, 255, 255, 0.014) 1px, transparent 1px, transparent 58px),
        linear-gradient(145deg, rgba(39, 22, 37, 0.88), rgba(15, 13, 19, 0.96));
}

.mg-profile-avatar-wrapper {
    background: linear-gradient(135deg, #ff4f8b, #f8c35a);
    border-color: rgba(255, 210, 120, 0.34);
    box-shadow: 0 18px 35px rgba(255, 79, 139, 0.25);
}

.mg-profile-online {
    border-color: #17131d;
    background: #8ef6bd;
}

.mg-splash-card,
.mg-welcome-card,
.mg-qris-card,
.mg-portal-modal {
    border-color: rgba(255, 210, 120, 0.24);
    background:
        repeating-linear-gradient(90deg, rgba(255, 255, 255, 0.014) 0, rgba(255, 255, 255, 0.014) 1px, transparent 1px, transparent 54px),
        linear-gradient(145deg, rgba(20, 17, 25, 0.98), rgba(8, 7, 12, 0.98));
}

.mg-portal-logo-image {
    background: #050506;
    color: inherit;
    box-shadow: 0 16px 34px rgba(0, 0, 0, 0.34);
}

.mg-portal-logo-image .mg-portal-svg {
    display: none;
}

.mg-arcade-welcome,
.mg-arcade-splash,
.mg-qris-overlay,
.mg-modal-overlay {
    width: 100%;
    min-width: 100%;
    height: 100%;
    min-height: 100%;
}

.mg-arcade-welcome.active,
.mg-arcade-splash.active,
.mg-qris-overlay.active,
.mg-modal-overlay.active {
    display: block;
}

.mg-welcome-card,
.mg-splash-card,
.mg-qris-card,
.mg-portal-modal {
    position: absolute;
    left: var(--mg-portal-popup-left, 50%);
    top: var(--mg-portal-popup-top, 50%);
    transform: translate(-50%, -50%) !important;
    animation: none !important;
}

.mg-welcome-card {
    width: min(540px, 88vw);
}

.mg-process-spinner {
    border-color: rgba(255, 210, 120, 0.16);
    border-top-color: #ff4f8b;
    border-right-color: #ffd278;
}

.mg-qris-progress span {
    background: linear-gradient(90deg, #ff4f8b, #f8c35a, #7ed3ff);
}

.mg-custom-select,
.mg-custom-select option,
.mg-modal-box input,
.mg-modal-box select {
    background: #08070c;
    color: var(--portal-text);
}

.mg-profile-filter.active,
.mg-rank-row.ttt .mg-status-pill {
    color: #ffd278;
    border-color: rgba(255, 210, 120, 0.32);
    background: rgba(255, 210, 120, 0.09);
}

.mg-profile-identity {
    display: grid;
    gap: 0.25rem;
    align-content: center;
}

.mg-profile-name-row {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.mg-profile-name-row h1 {
    line-height: 1;
}

.mg-profile-email {
    line-height: 1.25;
}

.mg-profile-name-row .mg-role-badge {
    margin-top: 0;
}

.mg-shop-toolbar {
    align-items: end;
    gap: 1rem;
}

.mg-shop-card {
    display: flex;
    flex-direction: column;
    min-height: 304px;
}

.mg-shop-card-body {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.mg-shop-card h3 {
    min-height: 2.35em;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.5rem;
}

.mg-shop-card p {
    min-height: 4.6em;
    display: flex;
    align-items: center;
    justify-content: center;
}

.mg-shop-purchase-block {
    width: 100%;
    display: grid;
    gap: 0.75rem;
    margin-top: auto;
}

.mg-shop-purchase-block .mg-price-pill {
    margin: 0 auto;
}

.mg-modal-close .mg-portal-svg {
    width: 1rem;
    height: 1rem;
    color: currentColor;
}

@media (max-width: 1180px) {
    .mg-portal-shell { grid-template-columns: 250px minmax(0, 1fr); }
}
</style>

<div id="mg-screen-portal" class="mg-screen active">
    <div class="mg-portal-shell">
        <div id="portal-lobby-transition" class="mg-arcade-splash" aria-hidden="true">
            <section class="mg-splash-card" role="status" aria-live="polite">
                <div class="mg-splash-mark" data-portal-icon="gamepad"></div>
                <div class="mg-kicker">Membuka Lobby</div>
                <h2 id="portal-lobby-transition-title" style="margin:0.45rem 0 0;">Arena Dipilih</h2>
                <p class="mg-lobby-transition-note">Portal akan memindahkan pemain ke lobby lokal. Layar gameplay tidak termasuk scope desain portal.</p>
                <div class="mg-process-spinner" aria-hidden="true" style="margin:1.15rem auto 0;"></div>
            </section>
        </div>
        <div id="arcade-welcome" class="mg-arcade-welcome" aria-hidden="true">
            <section class="mg-welcome-card">
                <div class="mg-welcome-icon" data-portal-icon="arcade"></div>
                <div class="mg-kicker">Arena Baru Menunggu</div>
                <h2>Selamat Datang di Zero Infinity</h2>
                <p>Halo <strong id="arcade-welcome-name">Pemain</strong>, kaitkan akun Anda untuk membuka profil arcade, saldo ZeroPay, riwayat, dan daftar penantang arena.</p>
                <div class="mg-welcome-actions">
                    <button type="button" class="mg-btn mg-btn-primary" data-register-arcade>Kaitkan Akun dengan Arcade</button>
                    <small class="mg-muted">Satu klik untuk membuka profil arcade Anda.</small>
                </div>
            </section>
        </div>
        <aside id="mg-portal-sidebar" class="mg-portal-sidebar">
            <div class="mg-portal-brand">
                <div class="mg-portal-logo mg-portal-logo-image">
                    <img src="<?php echo e(asset('img/Milky Garage assets/Logo Area.webp')); ?>" alt="Milky Garage">
                </div>
                <div>
                    <p class="mg-portal-brand-title">Zero Infinity Arcade</p><div class="mg-portal-brand-subtitle">Portal</div>
                </div>
            </div>

            <nav class="mg-portal-nav" aria-label="Arcade Portal">
                <button type="button" class="mg-portal-tab active" data-portal-tab="home"><span class="mg-portal-tab-icon" data-portal-icon="home"></span> Home</button>
                <button type="button" class="mg-portal-tab" data-portal-tab="shop"><span class="mg-portal-tab-icon" data-portal-icon="shop"></span> Shop</button>
                <button type="button" class="mg-portal-tab" data-portal-tab="profile"><span class="mg-portal-tab-icon" data-portal-icon="profile"></span> Profile</button>
                <button type="button" class="mg-portal-tab" data-portal-tab="history"><span class="mg-portal-tab-icon" data-portal-icon="history"></span> Riwayat Akun</button>
            </nav>

            <div class="mg-portal-sidebar-foot">
                <button type="button" class="mg-btn mg-btn-secondary" style="width:100%;" data-portal-close>Keluar Portal</button>
                <div class="mg-portal-copy">&copy; 2026 Zero Infinity Arcade</div>
            </div>
        </aside>

        <div class="mg-portal-main">
            <header class="mg-portal-header">
                <div class="mg-header-actions">
                    <button type="button" class="mg-btn mg-btn-secondary mg-mobile-menu-button" data-portal-menu>Menu</button>
                    <h2 id="portal-screen-title" class="mg-portal-screen-name">Home</h2>
                </div>
                <div class="mg-header-actions">
                    <div class="mg-balance-chip compact-zp" aria-label="Saldo ZeroPay"><span class="mg-zp-icon" data-portal-icon="wallet"></span><strong id="portal-header-balance">Rp <?php echo e(number_format(Auth::user()->balance ?? 0, 0, ',', '.')); ?></strong></div>
                    <button type="button" class="mg-btn mg-btn-secondary" data-portal-close>Keluar</button>
                </div>
            </header>

            <main class="mg-portal-stage">
                                <section id="portal-tab-home" class="mg-portal-content active">
                    <div class="mg-portal-container wide">
                        <div class="mg-home-intro">
                            <article class="mg-home-title-card">
                                <div class="mg-kicker">Arcade Portal</div>
                                <h1>Home</h1>
                                <p class="mg-section-copy">Pilih arena, masuk lobby, lalu kejar reward dari setiap kemenangan.</p>
                            </article>
                            <article class="mg-balance-card cyan">
                                <div class="mg-balance-big-icon" data-portal-icon="wallet"></div>
                                <div>
                                    <div class="mg-balance-label">Saldo</div>
                                    <div class="mg-balance-value">Rp <span id="portal-balance-display"><?php echo e(number_format(Auth::user()->balance ?? 0, 0, ',', '.')); ?></span></div>
                                </div>
                            </article>
                        </div>

                        <div class="mg-games-grid">
                            <article class="mg-game-card ttt">
                                <div class="mg-game-symbol" data-portal-icon="ttt"></div>
                                <h3>Tic-Tac-Toe Arena</h3>
                                <p>Strategi klasik dalam arena kompetitif. Menangkan 3 baris untuk menjadi juara.</p>
                                <div class="mg-mode-chips"><span class="mg-mode-chip">Have Fun</span><span class="mg-mode-chip">Ranked</span><span class="mg-mode-chip">Turnamen</span></div>
                                <button type="button" class="mg-btn" data-game-target="ttt">Masuk Lobby</button>
                            </article>
                            <article class="mg-game-card rps">
                                <div class="mg-game-symbol" data-portal-icon="rps"></div>
                                <h3>Rock Paper Scissors</h3>
                                <p>Pertarungan cepat melawan pemain lain. Gunakan strategi untuk mengalahkan lawan.</p>
                                <div class="mg-mode-chips"><span class="mg-mode-chip">Have Fun</span><span class="mg-mode-chip">Ranked</span><span class="mg-mode-chip">Turnamen</span></div>
                                <button type="button" class="mg-btn" data-game-target="rps">Masuk Lobby</button>
                            </article>
                            <article class="mg-game-card quiz">
                                <div class="mg-game-symbol" data-portal-icon="quiz">QZ</div>
                                <h3>Quiz Science Arena</h3>
                                <p>Uji pengetahuan sains Anda. Jawab pertanyaan dengan cepat dan tepat untuk menang.</p>
                                <div class="mg-mode-chips"><span class="mg-mode-chip">Have Fun</span><span class="mg-mode-chip">Ranked</span><span class="mg-mode-chip">Turnamen</span></div>
                                <button type="button" class="mg-btn" data-game-target="quiz">Masuk Lobby</button>
                            </article>
                        </div>
                    </div>
                </section>
<section id="portal-tab-shop" class="mg-portal-content">
                    <div class="mg-portal-container wide">
                        <div class="mg-shop-toolbar">
                            <div>
                                <h1 class="mg-section-title">Arcade Shop</h1>
                            </div>
                        </div>
                        <div id="portal-shop-categories" class="mg-shop-tabs"></div>
                        <div id="portal-shop-grid" class="mg-shop-grid" style="margin-top:2rem;"></div>
</div>
                </section>

                                <section id="portal-tab-profile" class="mg-portal-content">
                    <div class="mg-portal-container wide">
                        <article class="mg-profile-hero">
                            <div class="mg-profile-avatar-wrapper">
                                <img id="prof-avatar" alt="Avatar" style="display:none;">
                                <span id="prof-initials">PX</span>
                                <span class="mg-profile-online"></span>
                            </div>
                            <div class="mg-profile-identity">
                                <div class="mg-profile-name-row">
                                    <h1 id="prof-name" style="margin:0;font-size:1.55rem;">Player</h1>
                                    <span id="prof-role" class="mg-role-badge">Pemain</span>
                                </div>
                                <div class="mg-muted mg-profile-email"><span id="prof-email">-</span></div>
                            </div>
                        </article>

                        <div class="mg-profile-filter-row">
                            <button type="button" class="mg-profile-filter active" data-profile-filter="overall">Keseluruhan</button>
                            <button type="button" class="mg-profile-filter" data-profile-filter="ttt">TTT</button>
                            <button type="button" class="mg-profile-filter" data-profile-filter="rps">RPS</button>
                            <button type="button" class="mg-profile-filter" data-profile-filter="quiz">Quiz</button>
                        </div>

                        <div class="mg-profile-stats">
                            <div class="mg-stat-box"><span class="mg-stat-icon" data-portal-icon="walk">W</span><div><div class="mg-muted">Win / Lose</div><div id="prof-winlose" class="mg-stat-value">0 - 0</div></div></div>
                            <div class="mg-stat-box"><span class="mg-stat-icon" style="color:var(--portal-cyan);background:rgba(0,217,255,0.12);">%</span><div><div class="mg-muted">Win Rate</div><div id="prof-rate" class="mg-stat-value">0%</div></div></div>
                            <div class="mg-stat-box"><span class="mg-stat-icon" data-portal-icon="trophy"></span><div><div class="mg-muted">Total Pertandingan</div><div id="prof-total" class="mg-stat-value">0</div></div></div>
                        </div>

                        <article class="mg-rank-panel">
                            <h3 style="margin:0;">Ranked</h3>
                            <div class="mg-rank-list">
                                <div class="mg-rank-row ttt"><div class="mg-rank-name"><span data-portal-icon="ttt">XO</span><span>Tic-Tac-Toe Arena</span></div><span class="mg-status-pill" id="prof-ttt-rank">Bronze I</span></div>
                                <div class="mg-rank-row rps"><div class="mg-rank-name"><span data-portal-icon="rps">RPS</span><span>Rock Paper Scissors</span></div><span class="mg-status-pill" id="prof-rps-rank">Bronze I</span></div>
                                <div class="mg-rank-row quiz"><div class="mg-rank-name"><span data-portal-icon="quiz">QZ</span><span>Quiz Science Arena</span></div><span class="mg-status-pill" id="prof-quiz-rank">Bronze I</span></div>
                            </div>
                        </article>

                        <article class="mg-custom-panel">
                            <h3 style="margin:0;">Kustomisasi Portal</h3>
                            <div class="mg-form-grid">
                                <label>
                                    <span class="mg-muted">Tema Portal</span>
                                    <select id="kustomisasi-theme" class="mg-custom-select"></select>
                                </label>
                                <label>
                                    <span class="mg-muted">Frame Avatar</span>
                                    <select id="kustomisasi-border" class="mg-custom-select"></select>
                                </label>
                            </div>
                            <button type="button" class="mg-btn mg-btn-primary" style="width:100%;margin-top:1rem;background:linear-gradient(135deg,#ffb000,#ed7300);" data-portal-action="apply-customization">Terapkan Kustomisasi</button>
                        </article>
                    </div>
                </section>
                <section id="portal-tab-history" class="mg-portal-content">
                    <div class="mg-portal-container">
                        <div class="mg-section-head">
                            <div>
                                <h1 class="mg-section-title">Riwayat</h1>
                                <p class="mg-section-copy">Lihat transaksi dan aktivitas permainan Anda.</p>
                            </div>
                        </div>
                        <div id="portal-history-list" class="mg-history-list"></div>
                        <div class="mg-history-summary">
                            <div class="mg-summary-card in"><div class="mg-muted">Total Pemasukan Arcade</div><div id="history-income" class="mg-summary-value" style="color:var(--portal-green);">+Rp 0</div></div>
                            <div class="mg-summary-card out"><div class="mg-muted">Total Pengeluaran Arcade</div><div id="history-outcome" class="mg-summary-value" style="color:var(--portal-red);">Rp 0</div></div>
                        </div>
                    </div>
                </section></main>
        </div>

        <div id="portal-qris-overlay" class="mg-qris-overlay" aria-hidden="true">
            <section class="mg-qris-card" role="status" aria-live="polite">
                <div class="mg-kicker">Proses Arena</div>
                <h3 id="portal-qris-title" style="margin:0.35rem 0 0;">Memproses Transaksi</h3>
                <p id="portal-qris-subtitle" class="mg-muted" style="margin:0.55rem 0 0;">Saldo dan transaksi sedang diproses.</p>
                <div class="mg-process-spinner" aria-hidden="true"></div>
                <div class="mg-qris-progress"><span></span></div>
            </section>
        </div>
        <div id="portal-modal-overlay" class="mg-modal-overlay" aria-hidden="true">
            <div class="mg-portal-modal" role="dialog" aria-modal="true" aria-labelledby="portal-modal-title">
                <div class="mg-modal-title-row">
                    <div class="mg-modal-heading">
                        <span id="portal-modal-icon" class="mg-modal-icon">+</span>
                        <div>
                            <h3 id="portal-modal-title" style="margin:0;">Modal</h3>
                            <div id="portal-modal-subtitle" class="mg-muted">Detail</div>
                        </div>
                    </div>
                    <button type="button" class="mg-modal-close" data-modal-close aria-label="Tutup modal"><span data-portal-icon="x"></span></button>
                </div>
                <div id="portal-modal-body"></div>
            </div>
        </div>
    </div>
</div>

<script>
let portalData = null;
let activeShopCategory = 'portal_theme';
let activeHistoryFilter = 'all';
let activeProfileFilter = 'overall';
let previewItemState = null;
let portalLoadAlertShown = false;

const PortalArcade = {
    shopIcons: {
        portal_theme: ['moon', 'sun', 'diamond', 'flame', 'star'],
        avatar_border: ['crown', 'diamond', 'flame', 'ring', 'shield'],
        autopilot: ['robot', 'robot', 'robot', 'robot', 'robot'],
    },

    icon(name) {
        const icons = {
            arcade: '<svg class="mg-portal-svg" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 9h12l2 8H4z"/><path d="M8 13h4"/><path d="M10 11v4"/><path d="M16 13h.01"/><path d="M18 15h.01"/></svg>',
            home: '<svg class="mg-portal-svg" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 11 12 4l8 7"/><path d="M6 10v10h12V10"/><path d="M10 20v-5h4v5"/></svg>',
            gamepad: '<svg class="mg-portal-svg" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 10h12a3 3 0 0 1 3 3v3a3 3 0 0 1-5.2 2L14 16h-4l-1.8 2A3 3 0 0 1 3 16v-3a3 3 0 0 1 3-3z"/><path d="M8 13h3"/><path d="M9.5 11.5v3"/><path d="M16 13h.01"/><path d="M18 15h.01"/></svg>',
            shop: '<svg class="mg-portal-svg" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 7h12l-1 13H7z"/><path d="M9 7a3 3 0 0 1 6 0"/></svg>',
            cart: '<svg class="mg-portal-svg" viewBox="0 0 24 24" aria-hidden="true"><path d="M3 4h2l2.4 11.5a2 2 0 0 0 2 1.5h8.6a2 2 0 0 0 1.9-1.4L22 8H7"/><circle cx="9" cy="20" r="1.5"/><circle cx="18" cy="20" r="1.5"/></svg>',
            profile: '<svg class="mg-portal-svg" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M5 21a7 7 0 0 1 14 0"/></svg>',
            history: '<svg class="mg-portal-svg" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 12a8 8 0 1 0 3-6"/><path d="M4 5v5h5"/><path d="M12 8v5l3 2"/></svg>',
            coin: '<svg class="mg-portal-svg" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M9 16V8l6 8V8"/></svg>',
            wallet: '<svg class="mg-portal-svg" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16v10H4z"/><path d="M16 11h3"/><path d="M8 11h4"/></svg>',
            ttt: '<svg class="mg-portal-svg" viewBox="0 0 24 24" aria-hidden="true"><path d="M8 3v18"/><path d="M16 3v18"/><path d="M3 8h18"/><path d="M3 16h18"/></svg>',
            rps: '<svg class="mg-portal-svg" viewBox="0 0 24 24" aria-hidden="true"><path d="M8 11V5a2 2 0 0 1 4 0v6"/><path d="M12 11V4a2 2 0 0 1 4 0v8"/><path d="M16 12V7a2 2 0 0 1 4 0v8a7 7 0 0 1-14 0v-2"/></svg>',
            quiz: '<svg class="mg-portal-svg" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 9a3 3 0 1 1 5 2.2c-1.2.7-2 1.4-2 2.8"/><path d="M12 18h.01"/><circle cx="12" cy="12" r="9"/></svg>',
            star: '<svg class="mg-portal-svg" viewBox="0 0 24 24" aria-hidden="true"><path d="m12 3 2.8 5.7 6.2.9-4.5 4.4 1.1 6.2L12 17.2l-5.6 3 1.1-6.2L3 9.6l6.2-.9z"/></svg>',
            x: '<svg class="mg-portal-svg" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6 18 18"/><path d="M18 6 6 18"/></svg>',
            in: '<svg class="mg-portal-svg" viewBox="0 0 24 24" aria-hidden="true"><path d="m5 12 5 5L20 7"/></svg>',
            out: '<svg class="mg-portal-svg" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6 18 18"/><path d="M18 6 6 18"/></svg>',
            moon: '<svg class="mg-portal-svg" viewBox="0 0 24 24" aria-hidden="true"><path d="M21 14.5A8.5 8.5 0 0 1 9.5 3a7 7 0 1 0 11.5 11.5z"/></svg>',
            sun: '<svg class="mg-portal-svg" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.9 4.9 1.4 1.4"/><path d="m17.7 17.7 1.4 1.4"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m4.9 19.1 1.4-1.4"/><path d="m17.7 6.3 1.4-1.4"/></svg>',
            diamond: '<svg class="mg-portal-svg" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 3h12l4 6-10 12L2 9z"/><path d="M2 9h20"/><path d="m8 9 4 12 4-12"/></svg>',
            flame: '<svg class="mg-portal-svg" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 22c4 0 7-2.7 7-6.7 0-3.1-2-5.2-4.1-7.1C13.5 6.9 12.3 5.6 12 3c-3.3 2.1-6 5.1-6 9.2C6 18 9 22 12 22z"/><path d="M12 22c1.8 0 3-1.3 3-3 0-1.5-.9-2.5-2-3.5-.8.8-2 1.9-2 3.5 0 1.7 1.2 3 1 3z"/></svg>',
            robot: '<svg class="mg-portal-svg" viewBox="0 0 24 24" aria-hidden="true"><rect x="5" y="8" width="14" height="11" rx="3"/><path d="M12 8V4"/><circle cx="9" cy="13" r="1"/><circle cx="15" cy="13" r="1"/><path d="M9 17h6"/></svg>',
            ring: '<svg class="mg-portal-svg" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="7"/><circle cx="12" cy="12" r="3"/><path d="M12 2v3"/></svg>',
            shield: '<svg class="mg-portal-svg" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3 20 6v6c0 5-3.4 8-8 9-4.6-1-8-4-8-9V6z"/></svg>',
            crown: '<svg class="mg-portal-svg" viewBox="0 0 24 24" aria-hidden="true"><path d="m3 8 4 4 5-7 5 7 4-4-2 11H5z"/><path d="M5 19h14"/></svg>',
            walk: '<svg class="mg-portal-svg" viewBox="0 0 24 24" aria-hidden="true"><circle cx="13" cy="4" r="2"/><path d="m10 9 3-2 3 3"/><path d="m13 7-2 6"/><path d="m11 13-4 7"/><path d="m12 14 5 6"/></svg>',
            trophy: '<svg class="mg-portal-svg" viewBox="0 0 24 24" aria-hidden="true"><path d="M8 4h8v5a4 4 0 0 1-8 0z"/><path d="M8 6H4v2a4 4 0 0 0 4 4"/><path d="M16 6h4v2a4 4 0 0 1-4 4"/><path d="M12 13v5"/><path d="M8 21h8"/></svg>'
        };
        return icons[name] || icons.star;
    },

    hydrateIcons(root = document) {
        root.querySelectorAll('[data-portal-icon]').forEach((el) => {
            el.innerHTML = this.icon(el.dataset.portalIcon);
        });
        root.querySelectorAll('.mg-shop-cat-icon, .mg-shop-item-icon, .mg-modal-icon, .mg-history-icon').forEach((el) => {
            const key = (el.dataset.portalIcon || el.textContent || '').toLowerCase();
            const name = key.includes('out') ? 'out' : key.includes('in') ? 'in' : key.includes('rps') ? 'rps' : key.includes('q') ? 'quiz' : key.includes('x') ? 'ttt' : 'star';
            el.innerHTML = this.icon(name);
        });
    },

    csrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.content || MgCore.csrfToken;
    },

    async localGet(url) {
        const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
        const contentType = res.headers.get('content-type') || '';
        if (!contentType.includes('application/json')) {
            return { status: 'error', message: 'Route mengembalikan HTML, bukan JSON.' };
        }
        return res.json();
    },

    async localPost(url, payload) {
        const res = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.csrfToken(),
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload || {})
        });
        const contentType = res.headers.get('content-type') || '';
        if (!contentType.includes('application/json')) {
            return { status: 'error', message: 'Route mengembalikan HTML, bukan JSON.' };
        }
        return res.json();
    },

    updatePopupCenter() {
        const root = document.getElementById('mg-screen-portal');
        if (!root) return;

        const rect = root.getBoundingClientRect();
        const rawWidth = root.offsetWidth || rect.width || 1;
        const rawHeight = root.offsetHeight || rect.height || 1;
        const scaleX = rect.width / rawWidth || 1;
        const scaleY = rect.height / rawHeight || 1;
        const viewport = window.visualViewport;
        const centerX = (viewport ? viewport.offsetLeft + (viewport.width / 2) : window.innerWidth / 2);
        const centerY = (viewport ? viewport.offsetTop + (viewport.height / 2) : window.innerHeight / 2);
        const left = (centerX - rect.left) / scaleX;
        const top = (centerY - rect.top) / scaleY;

        root.style.setProperty('--mg-portal-popup-left', `${Math.max(0, Math.min(rawWidth, left))}px`);
        root.style.setProperty('--mg-portal-popup-top', `${Math.max(0, Math.min(rawHeight, top))}px`);
    },

    showLobbyTransition(gameKey) {
        const names = {
            ttt: 'Tic-Tac-Toe Arena',
            rps: 'Rock Paper Scissors',
            quiz: 'Quiz Science Arena',
        };
        const overlay = document.getElementById('portal-lobby-transition');
        const title = document.getElementById('portal-lobby-transition-title');
        if (title) title.innerText = names[gameKey] || 'Arena Dipilih';
        if (!overlay) return;
        this.hydrateIcons(overlay);
        this.updatePopupCenter();
        overlay.classList.add('active');
        overlay.setAttribute('aria-hidden', 'false');
        overlay.style.pointerEvents = 'auto';
        requestAnimationFrame(() => this.updatePopupCenter());
    },

    hideLobbyTransition() {
        const overlay = document.getElementById('portal-lobby-transition');
        if (!overlay) return;
        overlay.classList.remove('active');
        overlay.setAttribute('aria-hidden', 'true');
        overlay.style.pointerEvents = 'none';
    },

    async confirmExit() {
        const confirmed = await window.showConfirm(
            'Keluar dari Arcade?',
            'Anda akan kembali ke halaman sebelumnya. Data portal tetap tersimpan di sistem lokal.',
            'Keluar',
            'Batal'
        );
        if (confirmed) MgCore.close();
    },

    showWelcome(user = {}) {
        const welcome = document.getElementById('arcade-welcome');
        const shell = document.querySelector('.mg-portal-shell');
        const name = document.getElementById('arcade-welcome-name');
        if (name) name.innerText = user.username || user.name || 'Pemain';
        shell?.classList.add('arcade-unregistered');
        this.updatePopupCenter();
        welcome?.classList.add('active');
        welcome?.setAttribute('aria-hidden', 'false');
        requestAnimationFrame(() => this.updatePopupCenter());
    },

    hideWelcome() {
        const welcome = document.getElementById('arcade-welcome');
        const shell = document.querySelector('.mg-portal-shell');
        shell?.classList.remove('arcade-unregistered');
        welcome?.classList.remove('active');
        welcome?.setAttribute('aria-hidden', 'true');
    },

    async registerArcade() {
        const button = document.querySelector('[data-register-arcade]');
        if (button?.dataset.busy === '1') return;
        const originalLabel = button?.innerHTML || 'Kaitkan Akun dengan Arcade';
        try {
            if (button) {
                button.dataset.busy = '1';
                button.disabled = true;
                button.innerHTML = 'Mengaitkan akun...';
            }
            const data = await this.withQrisProcess(
                'Mengaitkan Akun Arcade',
                'Profil TTT, RPS, dan Quiz sedang dibuat secara lokal.',
                () => this.localPost('/arcade/register', {})
            );
            if (data.status === 'success' && data.arcade_registered) {
                portalData = data;
                this.hideWelcome();
                renderPortalData();
                await loadPortalData(false);
                await window.showAlert('Arcade Aktif', 'Akun Anda sudah terhubung ke portal arcade.');
                return;
            }
            await window.showAlert('Gagal Mengaitkan', data.message || 'Profil arcade belum dapat dibuat.');
        } catch (error) {
            console.error('[PortalArcade] register failed', error);
            await window.showAlert('Gagal Mengaitkan', 'Koneksi ke route arcade belum berhasil. Silakan coba lagi.');
        } finally {
            if (button) {
                button.disabled = false;
                button.dataset.busy = '0';
                button.innerHTML = originalLabel;
            }
        }
    },
    escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, char => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        }[char]));
    },

    formatNumber(value) {
        return parseInt(value || 0, 10).toLocaleString('id-ID');
    },

    rupiah(value) {
        return `Rp ${this.formatNumber(value)}`;
    },

    itemIcon(type, key, index = 0) {
        const normalized = String(key || '').toLowerCase();
        if (type === 'autopilot' || normalized.includes('pro_') || normalized.includes('world_class')) return this.icon('robot');
        if (normalized.includes('cyber') || normalized.includes('night')) return this.icon('moon');
        if (normalized.includes('solar') || normalized.includes('sun')) return this.icon('sun');
        if (normalized.includes('mono') || normalized.includes('diamond')) return this.icon('diamond');
        if (normalized.includes('dark') || normalized.includes('flame')) return this.icon('flame');
        if (normalized.includes('hologram')) return this.icon('star');
        if (normalized.includes('neon')) return this.icon('ring');
        if (normalized.includes('obsidian')) return this.icon('shield');
        if (normalized.includes('aurora') || normalized.includes('gold')) return this.icon('crown');
        if (normalized.includes('pixel')) return this.icon('ttt');
        return this.icon('star');
    },
    isQuotaItem(itemType) {
        return itemType === 'autopilot';
    },

    ownsItem(itemType, itemKey) {
        if (this.isQuotaItem(itemType)) return false;
        return (portalData?.profile?.inventory || []).some(item => item.item_type === itemType && item.item_key === itemKey);
    },

    itemButtonText(itemType, itemKey) {
        if (this.isQuotaItem(itemType)) return 'Tambah Charge';
        return this.ownsItem(itemType, itemKey) ? 'Sudah Dimiliki' : 'Beli Item';
    },

    applyPurchaseResult(data = {}) {
        if (!portalData?.profile) return;
        if (Number.isFinite(parseInt(data.new_balance, 10))) {
            portalData.profile.balance = parseInt(data.new_balance, 10);
        }
        if (Array.isArray(data.inventory)) {
            portalData.profile.inventory = data.inventory;
        }
        if (data.autopilot) {
            portalData.profile.autopilot = data.autopilot;
            portalData.profile.autopilot_free_quota = data.autopilot.free ?? portalData.profile.autopilot_free_quota;
            portalData.profile.autopilot_pro_quota = data.autopilot.pro ?? portalData.profile.autopilot_pro_quota;
            portalData.profile.autopilot_world_class_quota = data.autopilot.world_class ?? portalData.profile.autopilot_world_class_quota;
        }
        renderPortalData();
    },
    showModal({ icon = '+', title = 'Modal', subtitle = '', body = '' }) {
        const modalIcon = document.getElementById('portal-modal-icon');
        const modalIconName = icon === 'ITEM' ? 'cart' : 'star';
        if (modalIcon) { modalIcon.dataset.portalIcon = modalIconName; modalIcon.innerHTML = this.icon(modalIconName); }
        document.getElementById('portal-modal-title').innerText = title;
        document.getElementById('portal-modal-subtitle').innerText = subtitle;
        document.getElementById('portal-modal-body').innerHTML = body;
        this.hydrateIcons(document.getElementById('portal-modal-overlay') || document);
        const overlay = document.getElementById('portal-modal-overlay');
        this.updatePopupCenter();
        overlay.classList.add('active');
        overlay.setAttribute('aria-hidden', 'false');
        overlay.style.pointerEvents = 'auto';
        requestAnimationFrame(() => this.updatePopupCenter());
    },

    closeModal() {
        const overlay = document.getElementById('portal-modal-overlay');
        overlay.classList.remove('active');
        overlay.setAttribute('aria-hidden', 'true');
        overlay.style.pointerEvents = 'none';
        document.getElementById('portal-modal-body').innerHTML = '';
        previewItemState = null;
    },

    sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    },

    showQrisProcess(title = 'Memproses Transaksi', subtitle = 'Saldo dan transaksi sedang diproses.') {
        const overlay = document.getElementById('portal-qris-overlay');
        const titleEl = document.getElementById('portal-qris-title');
        const subtitleEl = document.getElementById('portal-qris-subtitle');
        if (titleEl) titleEl.innerText = title;
        if (subtitleEl) subtitleEl.innerText = subtitle;
        this.updatePopupCenter();
        overlay?.classList.add('active');
        overlay?.setAttribute('aria-hidden', 'false');
        if (overlay) overlay.style.pointerEvents = 'auto';
        requestAnimationFrame(() => this.updatePopupCenter());
    },

    hideQrisProcess() {
        const overlay = document.getElementById('portal-qris-overlay');
        overlay?.classList.remove('active');
        overlay?.setAttribute('aria-hidden', 'true');
        if (overlay) overlay.style.pointerEvents = 'none';
    },

    async withQrisProcess(title, subtitle, action) {
        this.showQrisProcess(title, subtitle);
        await this.sleep(650);
        try {
            return await action();
        } finally {
            await this.sleep(550);
            this.hideQrisProcess();
        }
    },
    previewItem(itemType, itemKey) {
        const item = portalData?.catalog?.[itemType]?.[itemKey];
        if (!item) return;
        previewItemState = { itemType, itemKey };
        const icon = this.itemIcon(itemType, itemKey);
        const owned = this.ownsItem(itemType, itemKey);
        const quota = this.isQuotaItem(itemType);
        const statusText = quota ? 'Dapat dibeli berulang' : (owned ? 'Sudah Dimiliki' : 'Belum Dimiliki');
        const buyText = quota ? 'Tambah Charge' : (owned ? 'Sudah Dimiliki' : 'Beli Sekarang');
        this.showModal({
            icon: 'ITEM',
            title: 'Preview Item',
            subtitle: 'Detail item shop',
            body: `
                <div style="text-align:center;">
                    <div class="mg-modal-preview-icon">${icon}</div>
                    <h2 style="margin:0 0 0.6rem;">${this.escapeHtml(item.label || itemKey)}</h2>
                    <p class="mg-muted">${this.escapeHtml(item.description || 'Item arcade untuk portal Anda.')}</p>
                    <div class="mg-modal-status-pill">${statusText}</div>
                </div>
                <div class="mg-modal-input-card" style="margin-top:1rem;text-align:center;border-color:rgba(255,176,0,0.32);background:rgba(255,176,0,0.12);">
                    <strong style="font-size:1.35rem;color:var(--portal-amber);">${this.rupiah(item.price)}</strong>
                </div>
                <div class="mg-modal-actions">
                    <button type="button" class="mg-btn mg-btn-secondary" data-modal-close>Batal</button>
                    <button type="button" class="mg-btn mg-btn-primary" style="background:linear-gradient(135deg,#ffb000,#ed7300);" data-confirm-buy ${owned ? 'disabled' : ''}>${buyText}</button>
                </div>
            `
        });
    },
    async confirmBuy() {
        if (!previewItemState) return;
        const { itemType, itemKey } = previewItemState;
        const item = portalData?.catalog?.[itemType]?.[itemKey];
        const balance = parseInt(portalData?.profile?.balance || 0, 10);
        if (item && balance < parseInt(item.price || 0, 10)) {
            await window.showAlert('Saldo ZeroPay Tidak Cukup', 'Saldo ZeroPay Anda tidak cukup untuk membeli item ini.');
            return;
        }
        const initData = await this.localPost('/arcade/purchase-init', { item_type: itemType, item_key: itemKey });
        if (initData.status !== 'awaiting_confirmation') {
            await window.showAlert('Pembelian Gagal', initData.message || 'Item tidak dapat dibeli.');
            return;
        }
        const data = await this.withQrisProcess('Memproses Pembelian Item', 'Item shop sedang dikaitkan ke akun arcade.', () => this.localPost('/arcade/purchase', { confirmation_token: initData.confirmation_token }));
        if (data.status === 'success') {
            this.closeModal();
            await window.showAlert('Pembelian Berhasil', 'Item sudah masuk ke akun arcade.');
            await loadPortalData(false);
        } else {
            await window.showAlert('Pembelian Gagal', data.message || 'Transaksi tidak dapat diproses.');
        }
    },

    async applyCustomization() {
        const theme = document.getElementById('kustomisasi-theme')?.value || 'default';
        const border = document.getElementById('kustomisasi-border')?.value || 'default';
        const confirmed = await window.showConfirm(
            'Terapkan Kustomisasi?',
            'Tema portal dan frame avatar pilihan Anda akan diterapkan ke profil arcade lokal.',
            'Terapkan',
            'Batal'
        );
        if (!confirmed) return;

        const themeResult = await this.localPost('/arcade/equip-customization', { type: 'portal_theme', item_key: theme });
        const borderResult = await this.localPost('/arcade/equip-customization', { type: 'avatar_border', item_key: border });
        if (themeResult.status === 'success' && borderResult.status === 'success') {
            if (portalData?.profile) {
                portalData.profile.active_portal_theme = theme === 'default' ? null : theme;
                portalData.profile.active_avatar_border = border === 'default' ? null : border;
                applyPortalFrame(portalData.profile);
            }
            await window.showAlert('Kustomisasi Disimpan', 'Kustomisasi tersimpan. Tampilan portal diperbarui.');
            await loadPortalData(false);
        } else {
            await window.showAlert('Kustomisasi Gagal', themeResult.message || borderResult.message || 'Item belum dimiliki.');
        }
    }
};

function switchPortalTab(tab) {
    const titles = { home: 'Home', shop: 'Shop', profile: 'Profile', history: 'Riwayat' };
    document.querySelectorAll('.mg-portal-tab').forEach(el => el.classList.toggle('active', el.dataset.portalTab === tab));
    document.querySelectorAll('.mg-portal-content').forEach(el => el.classList.toggle('active', el.id === 'portal-tab-' + tab));
    const title = document.getElementById('portal-screen-title');
    if (title) title.innerText = titles[tab] || tab;
    document.getElementById('mg-portal-sidebar')?.classList.remove('mobile-open');
    const shell = document.querySelector('.mg-portal-main');
    const wrapper = document.getElementById('minigame-wrapper');
    if (shell) shell.scrollTop = 0;
    if (wrapper) wrapper.scrollTop = 0;
    if (!portalData) loadPortalData(true);
}

function switchShopCategory(category) {
    activeShopCategory = category;
    renderShop();
}

function switchHistoryFilter(filter) {
    activeHistoryFilter = filter;
    document.querySelectorAll('[data-history-filter]').forEach(el => el.classList.toggle('active', el.dataset.historyFilter === filter));
    renderHistory();
}

async function loadPortalData(showIntro = false) {
    try {
        const data = await PortalArcade.localGet('/arcade/portal-data');
        if (data.status !== 'success') {
            if (showIntro && !portalLoadAlertShown) {
                portalLoadAlertShown = true;
                await window.showAlert('Portal Gagal Dimuat', data.message || 'Data portal tidak tersedia.');
            }
            return;
        }
        portalData = data;
        if (data.arcade_registered === false) {
            PortalArcade.showWelcome(data.user || {});
            PortalArcade.hydrateIcons(document.getElementById('mg-screen-portal') || document);
            return;
        }
        PortalArcade.hideWelcome();
        renderPortalData();
        PortalArcade.hydrateIcons(document.getElementById('mg-screen-portal') || document);
    } catch (e) {
        console.error('Portal load failed', e);
        if (showIntro && !portalLoadAlertShown) {
            portalLoadAlertShown = true;
            await window.showAlert('Portal Gagal Dimuat', 'Data portal tidak dapat dibaca.');
        }
    }
}
function renderPortalData() {
    if (!portalData?.profile) return;
    const profile = portalData.profile || {};
    const balanceText = PortalArcade.rupiah(profile.balance);
    const headerBalance = document.getElementById('portal-header-balance');
    const balanceDisplay = document.getElementById('portal-balance-display');
    const sideBalance = document.getElementById('sidebarZeroPayBalance');
    if (headerBalance) headerBalance.innerText = balanceText;
    if (balanceDisplay) balanceDisplay.innerText = PortalArcade.formatNumber(profile.balance);
    if (sideBalance) sideBalance.innerText = balanceText;
    MgCore.updateBalance(profile.balance);
    renderShop();
    renderProfile();
    renderCustomization();
    renderHistory();
    applyPortalFrame(profile);
}
function renderFees() {
    // ZeroPay-only arcade: entry fees are no longer rendered in the portal.
}
function renderShop() {
    const catalog = portalData?.catalog || {};
    const categories = {
        portal_theme: { label: 'Tema Portal' },
        avatar_border: { label: 'Frame Profil' },
        autopilot: { label: 'Quota Autopilot' },
    };
    const catWrap = document.getElementById('portal-shop-categories');
    if (catWrap) {
        catWrap.innerHTML = Object.keys(categories).map(key => `
            <button type="button" class="mg-shop-cat ${activeShopCategory === key ? 'active' : ''}" data-shop-category="${key}">${categories[key].label}</button>
        `).join('');
    }

    const grid = document.getElementById('portal-shop-grid');
    if (!grid) return;
    const items = catalog[activeShopCategory] || {};
    const renderCard = (key, index = 0) => {
        const item = items[key];
        if (!item) return '';
        const itemName = PortalArcade.escapeHtml(item.label || key);
        const description = PortalArcade.escapeHtml(item.description || 'Item arcade untuk portal Anda.');
        const icon = PortalArcade.itemIcon(activeShopCategory, key, index);
        const owned = PortalArcade.ownsItem(activeShopCategory, key);
        const quota = PortalArcade.isQuotaItem(activeShopCategory);
        const buttonText = PortalArcade.itemButtonText(activeShopCategory, key);
        return `
            <article class="mg-shop-card ${owned ? 'owned' : ''}" data-shop-item-type="${activeShopCategory}" data-shop-item-key="${PortalArcade.escapeHtml(key)}">
                <div class="mg-shop-card-body">
                    <div class="mg-shop-emoji">${icon}</div>
                    <h3>${itemName}</h3>
                    <p>${description}</p>
                </div>
                <div class="mg-shop-purchase-block">
                    <div class="mg-price-pill">${PortalArcade.rupiah(item.price)}</div>
                    <button type="button" class="mg-btn mg-btn-secondary" ${owned && !quota ? 'disabled' : ''}>${buttonText}</button>
                </div>
            </article>
        `;
    };

    if (activeShopCategory === 'autopilot') {
        const proKeys = Object.keys(items).filter(key => key.startsWith('pro_'));
        const worldKeys = Object.keys(items).filter(key => key.startsWith('world_class_'));
        const blocks = [];
        if (proKeys.length) {
            blocks.push('<div class="mg-autopilot-group">Pro</div>');
            blocks.push(...proKeys.map(renderCard));
        }
        if (worldKeys.length) {
            blocks.push('<div class="mg-autopilot-group">World</div>');
            blocks.push(...worldKeys.map(renderCard));
        }
        grid.innerHTML = blocks.join('') || '<div class="mg-empty-state">Kategori ini belum tersedia.</div>';
    } else {
        const cards = Object.keys(items).map(renderCard);
        grid.innerHTML = cards.join('') || '<div class="mg-empty-state">Kategori ini belum tersedia.</div>';
    }
}
function renderProfile() {
    const profile = portalData.profile || {};
    const avatar = document.getElementById('prof-avatar');
    const initials = document.getElementById('prof-initials');
    if (profile.avatar && avatar && initials) {
        avatar.src = profile.avatar;
        avatar.style.display = 'block';
        initials.style.display = 'none';
    } else if (avatar && initials) {
        avatar.removeAttribute('src');
        avatar.style.display = 'none';
        initials.style.display = 'block';
        initials.innerText = (profile.username || profile.name || 'PX').slice(0, 2).toUpperCase();
    }
    const nameEl = document.getElementById('prof-name');
    const emailEl = document.getElementById('prof-email');
    const roleEl = document.getElementById('prof-role');
    if (nameEl) nameEl.innerText = profile.username || profile.name || 'Player Zero';
    if (emailEl) emailEl.innerText = profile.email || 'Profil arcade';
    if (roleEl) roleEl.innerText = profile.role_label || profile.role || 'Pemain';

    const tttRank = rankLabel(profile.ranks?.ttt?.rank);
    const rpsRank = rankLabel(profile.ranks?.rps?.rank);
    const quizRank = rankLabel(profile.ranks?.quiz?.rank);
    const tttEl = document.getElementById('prof-ttt-rank');
    const rpsEl = document.getElementById('prof-rps-rank');
    const quizEl = document.getElementById('prof-quiz-rank');
    if (tttEl) tttEl.innerText = tttRank;
    if (rpsEl) rpsEl.innerText = rpsRank;
    if (quizEl) quizEl.innerText = quizRank;

    document.querySelectorAll('[data-profile-filter]').forEach(el => el.classList.toggle('active', el.dataset.profileFilter === activeProfileFilter));
    const stats = profile.stats?.[activeProfileFilter] || profile.stats?.overall || { wins: 0, losses: 0, draws: 0, win_rate: 0 };
    const wins = parseInt(stats.wins || 0, 10);
    const losses = parseInt(stats.losses || 0, 10);
    const draws = parseInt(stats.draws || 0, 10);
    const total = wins + losses + draws;
    const winLoseEl = document.getElementById('prof-winlose');
    const rateEl = document.getElementById('prof-rate');
    const totalEl = document.getElementById('prof-total');
    if (winLoseEl) winLoseEl.innerText = `${wins} - ${losses}`;
    if (rateEl) rateEl.innerText = `${stats.win_rate || 0}%`;
    if (totalEl) totalEl.innerText = total;
}
function rankLabel(value) {
    return String(value || 'BRONZE_I').replace(/_/g, ' ').replace(/\b\w/g, char => char.toUpperCase());
}

function renderCustomization() {
    const inventory = portalData.profile?.inventory || [];
    const catalog = portalData.catalog || {};
    const themeSelect = document.getElementById('kustomisasi-theme');
    const borderSelect = document.getElementById('kustomisasi-border');
    themeSelect.innerHTML = '<option value="default">Default Dark</option>';
    borderSelect.innerHTML = '<option value="default">Default Frame</option>';
    inventory.forEach(item => {
        const option = document.createElement('option');
        option.value = item.item_key;
        option.text = catalog[item.item_type]?.[item.item_key]?.label || item.item_key.replace(/_/g, ' ').toUpperCase();
        if (item.item_type === 'portal_theme') themeSelect.add(option);
        if (item.item_type === 'avatar_border') borderSelect.add(option);
    });
    themeSelect.value = portalData.profile?.active_portal_theme || 'default';
    borderSelect.value = portalData.profile?.active_avatar_border || 'default';
}

function renderHistory() {
    const list = document.getElementById('portal-history-list');
    if (!list) return;
    const hiddenAccountWords = ['topup', 'top-up', 'saldo arcade lama', 'dipindahkan'];
    const accountRows = (portalData?.history?.account || [])
        .filter(item => {
            const text = `${item.type || ''} ${item.title || ''} ${item.description || ''}`.toLowerCase();
            return !hiddenAccountWords.some(word => text.includes(word));
        })
        .map(item => ({ ...item, history_type: 'account' }));
    const gameRewardRows = (portalData?.history?.games || [])
        .filter(item => parseInt(item.reward_amount || 0, 10) > 0);
    const rows = accountRows.sort((a, b) => (parseInt(b.created_sort || 0, 10) - parseInt(a.created_sort || 0, 10)));
    let income = gameRewardRows.reduce((total, item) => total + parseInt(item.reward_amount || 0, 10), 0);
    let outcome = accountRows.reduce((total, item) => {
        const amount = parseInt(item.zeropay_amount || 0, 10);
        return amount < 0 ? total + Math.abs(amount) : total;
    }, 0);

    if (!rows.length) {
        list.innerHTML = '<div class="mg-empty-state">Belum ada riwayat pembelian item arcade.</div>';
        document.getElementById('history-income').innerText = `+${PortalArcade.rupiah(income)}`;
        document.getElementById('history-outcome').innerText = `-${PortalArcade.rupiah(outcome)}`;
        return;
    }

    list.innerHTML = rows.map(item => {
        const amount = parseInt(item.zeropay_amount || 0, 10);
        const title = item.title || 'Pembelian Arcade';
        const description = item.description || 'Item arcade berhasil dibeli.';
        const icon = PortalArcade.icon('shop');
        return `
            <div class="mg-history-row">
                <span class="mg-history-icon">${icon}</span>
                <div><strong>${PortalArcade.escapeHtml(title)}</strong><div class="mg-muted">${PortalArcade.escapeHtml(description)}</div><small class="mg-muted">${PortalArcade.escapeHtml(item.created_at || '')}</small></div>
                <strong class="mg-history-amount ${amount >= 0 ? 'in' : 'out'}">${amount > 0 ? '+' : amount < 0 ? '-' : ''}${PortalArcade.rupiah(Math.abs(amount))}</strong>
            </div>
        `;
    }).join('');
    document.getElementById('history-income').innerText = `+${PortalArcade.rupiah(income)}`;
    document.getElementById('history-outcome').innerText = `-${PortalArcade.rupiah(outcome)}`;
}
function modeName(mode) {
    return { casual: 'Have Fun', have_fun: 'Have Fun', duo: 'Have Fun Duo', rank: 'Ranked', ranked: 'Ranked', tournament: 'Turnamen' }[mode] || 'Arcade';
}
function gameName(game) {
    return { ttt: 'Tic-Tac-Toe Arena', rps: 'Rock Paper Scissors', quiz: 'Quiz Science Arena' }[game] || 'Arcade';
}

function gameIcon(game) {
    return { ttt: PortalArcade.icon('ttt'), rps: PortalArcade.icon('rps'), quiz: PortalArcade.icon('quiz') }[game] || PortalArcade.icon('star');
}

function applyPortalFrame(profile) {
    const shell = document.querySelector('.mg-portal-shell');
    const avatarWrap = document.querySelector('.mg-profile-avatar-wrapper');
    if (shell) {
        shell.classList.remove('theme-cyber-night', 'theme-solar-vault', 'theme-mono-luxe', 'theme-quiz-theme-dark', 'theme-quiz-theme-hologram');
        const theme = String(profile.active_portal_theme || '').replace(/_/g, '-');
        if (theme) shell.classList.add(`theme-${theme}`);
    }
    if (!avatarWrap) return;
    const border = profile.active_avatar_border;
    avatarWrap.style.borderColor = border === 'neon_ring' ? '#00d9ff' : border === 'obsidian_frame' ? '#cbd5e1' : border === 'aurora_ring' ? '#f472b6' : 'rgba(255, 176, 0, 0.36)';
    avatarWrap.style.boxShadow = border === 'neon_ring'
        ? '0 0 30px rgba(0, 217, 255, 0.36)'
        : border === 'aurora_ring'
            ? '0 0 34px rgba(244, 114, 182, 0.38)'
            : border === 'obsidian_frame'
                ? '0 0 22px rgba(203, 213, 225, 0.22)'
                : '0 18px 35px rgba(255, 176, 0, 0.28)';
}

function bindPortalEvents() {
    const root = document.getElementById('mg-screen-portal');
    if (!root || root.dataset.bound === '1') return;
    root.dataset.bound = '1';

    const consume = (event) => {
        event.preventDefault();
        event.stopPropagation();
        if (typeof event.stopImmediatePropagation === 'function') event.stopImmediatePropagation();
    };

    root.addEventListener('click', async (event) => {
        const register = event.target.closest('[data-register-arcade]');
        if (register) {
            consume(event);
            return PortalArcade.registerArcade();
        }

        const tab = event.target.closest('[data-portal-tab]');
        if (tab) {
            consume(event);
            return switchPortalTab(tab.dataset.portalTab);
        }

        const category = event.target.closest('[data-shop-category]');
        if (category) {
            consume(event);
            return switchShopCategory(category.dataset.shopCategory);
        }

        const history = event.target.closest('[data-history-filter]');
        if (history) {
            consume(event);
            return switchHistoryFilter(history.dataset.historyFilter);
        }

        const profileFilter = event.target.closest('[data-profile-filter]');
        if (profileFilter) {
            consume(event);
            activeProfileFilter = profileFilter.dataset.profileFilter || 'overall';
            renderProfile();
            return;
        }

        const close = event.target.closest('[data-portal-close]');
        if (close) {
            consume(event);
            return PortalArcade.confirmExit();
        }

        const menu = event.target.closest('[data-portal-menu]');
        if (menu) {
            consume(event);
            return document.getElementById('mg-portal-sidebar')?.classList.toggle('mobile-open');
        }

        const game = event.target.closest('[data-game-target]');
        if (game) {
            consume(event);
            const target = game.dataset.gameTarget;
            if (!['ttt', 'rps', 'quiz'].includes(target)) return MgCore.toast('Lobby game tidak dikenal.');
            PortalArcade.showLobbyTransition(target);
            await PortalArcade.sleep(700);
            PortalArcade.hideLobbyTransition();
            return MgCore.navigate(target);
        }

        const action = event.target.closest('[data-portal-action]');
        if (action) {
            consume(event);
            if (action.dataset.portalAction === 'apply-customization') return PortalArcade.applyCustomization();
            return;
        }

        const item = event.target.closest('[data-shop-item-key]');
        if (item) {
            consume(event);
            return PortalArcade.previewItem(item.dataset.shopItemType, item.dataset.shopItemKey);
        }

        if (event.target.closest('[data-modal-close]') || event.target.id === 'portal-modal-overlay') {
            consume(event);
            return PortalArcade.closeModal();
        }

        const confirmBuy = event.target.closest('[data-confirm-buy]');
        if (confirmBuy) {
            consume(event);
            if (confirmBuy.disabled) return;
            return PortalArcade.confirmBuy();
        }
    }, true);
}

window.buyShopItem = async function(itemKey, type) {
    PortalArcade.previewItem(type, itemKey);
};

window.resetPortalEntrySplash = function() {
    portalLoadAlertShown = false;
};

window.loadPortalData = loadPortalData;
window.addEventListener('resize', () => PortalArcade.updatePopupCenter());
window.visualViewport?.addEventListener('resize', () => PortalArcade.updatePopupCenter());
window.visualViewport?.addEventListener('scroll', () => PortalArcade.updatePopupCenter());

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        bindPortalEvents();
        PortalArcade.updatePopupCenter();
    });
} else {
    bindPortalEvents();
    PortalArcade.updatePopupCenter();
}
</script>


<?php /**PATH C:\laragon\www\ProyekTI\resources\views/components/minigame-portal.blade.php ENDPATH**/ ?>