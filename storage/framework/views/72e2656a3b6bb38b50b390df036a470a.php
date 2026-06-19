<style>
/* =========================================================
 * Obsidian-Aether Synthwave Theme Variables
 * ========================================================= */
#mg-screen-ttt {
    --ttt-bg: #07070a;
    --ttt-panel: rgba(15, 15, 25, 0.72);
    --ttt-panel-strong: rgba(22, 22, 34, 0.94);
    --ttt-border: rgba(255, 255, 255, 0.07);
    --ttt-border-glow: rgba(0, 242, 254, 0.25);
    --ttt-cyan: #00f2fe;
    --ttt-violet: #7c3aed;
    --ttt-amber: #f59e0b;
    --ttt-red: #ff5f6d;
    --ttt-green: #00e7a7;
    --mg-surface: rgba(17, 17, 27, 0.75);
    --mg-border: rgba(255, 255, 255, 0.06);
    --mg-radius-lg: 24px;
    --mg-radius-md: 18px;
    --mg-radius-sm: 12px;
    
    font-family: 'Outfit', 'Inter', system-ui, -apple-system, sans-serif;
    background: var(--ttt-bg);
    color: #e2e8f0;
    overflow: hidden;
    position: relative;
}

/* Background atmospheric grid & lights */
#mg-screen-ttt::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image: 
        linear-gradient(rgba(255, 255, 255, 0.015) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255, 255, 255, 0.015) 1px, transparent 1px);
    background-size: 40px 40px;
    pointer-events: none;
    z-index: 0;
}

/* Tab buttons */
.mg-ttt-tabs {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
    border: 1px solid var(--ttt-border);
    padding: 0.4rem;
    border-radius: 16px;
    background: rgba(0, 0, 0, 0.35);
    overflow: hidden;
    position: relative;
    z-index: 2;
    backdrop-filter: blur(10px);
}
.mg-ttt-tab-btn {
    background: transparent;
    border: 1px solid transparent;
    color: var(--mg-text-muted);
    font-size: 0.92rem;
    font-weight: 850;
    cursor: pointer;
    padding: 0.65rem 1.1rem;
    border-radius: 12px;
    transition: all 0.25s cubic-bezier(0.25, 0.8, 0.25, 1);
    white-space: nowrap;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.mg-ttt-tab-btn:hover {
    color: #fff;
    background: rgba(255, 255, 255, 0.03);
}
.mg-ttt-tab-btn.active {
    color: var(--ttt-cyan);
    border-color: rgba(0, 242, 254, 0.25);
    background: rgba(0, 242, 254, 0.08);
    box-shadow: 0 0 15px rgba(0, 242, 254, 0.12);
}

.mg-tab-content { display: none; }
.mg-tab-content.active { display: block; }

/* Top header bar */
.mg-game-lobby-topbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1.25rem;
    margin-bottom: 1.5rem;
    padding: 0.9rem 1.4rem;
    border: 1px solid var(--ttt-border);
    border-radius: 20px;
    background: linear-gradient(135deg, rgba(20, 20, 30, 0.85), rgba(10, 10, 15, 0.92));
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.35);
    position: relative;
    z-index: 2;
    backdrop-filter: blur(12px);
}
.mg-game-lobby-brand {
    display: flex;
    align-items: center;
    gap: 0.95rem;
}
.mg-game-lobby-logo {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: grid;
    place-items: center;
    background: linear-gradient(135deg, var(--ttt-cyan), var(--ttt-violet));
    color: #07070a;
    box-shadow: 0 8px 20px rgba(0, 242, 254, 0.25);
}
.mg-game-lobby-logo svg {
    width: 22px;
    height: 22px;
    fill: none;
    stroke: currentColor;
    stroke-width: 2.2;
}
.mg-game-lobby-title {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 900;
    letter-spacing: -0.02em;
    color: #fff;
}
.mg-game-lobby-subtitle {
    margin: 2px 0 0;
    font-size: 0.72rem;
    color: var(--mg-text-muted);
    font-weight: 700;
}
.mg-game-lobby-balance {
    display: flex;
    align-items: center;
    gap: 0.65rem;
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.06);
    padding: 0.5rem 1rem;
    border-radius: 12px;
    font-weight: 850;
    color: var(--ttt-green);
    font-size: 0.92rem;
}
.mg-game-lobby-balance svg {
    width: 18px;
    height: 18px;
    fill: none;
    stroke: currentColor;
    stroke-width: 2;
}
.mg-game-lobby-exit {
    background: rgba(255, 95, 109, 0.08);
    border: 1px solid rgba(255, 95, 109, 0.2);
    color: var(--ttt-red);
    font-weight: 850;
    font-size: 0.9rem;
    padding: 0.5rem 1.1rem;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.2s ease;
}
.mg-game-lobby-exit:hover {
    background: rgba(255, 95, 109, 0.15);
    box-shadow: 0 0 15px rgba(255, 95, 109, 0.15);
    transform: translateY(-1px);
}

/* =========================================================
 * LOBBY PLAY TAB REDESIGN - GLASSMORPHIC ESPORTS
 * ========================================================= */
.mg-ttt-play-lobby {
    width: 100%;
    position: relative;
    z-index: 2;
}
.mg-game-mode-title {
    font-size: 1.45rem;
    font-weight: 900;
    margin: 0.5rem 0 1.5rem;
    letter-spacing: -0.03em;
    color: #fff;
    text-align: left;
}
.mg-ttt-mode-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
    margin-bottom: 2rem;
}
.mg-ttt-mode-card-v2 {
    position: relative;
    border-radius: 24px;
    border: 1px solid rgba(255, 255, 255, 0.08);
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.05) 0%, rgba(255, 255, 255, 0.01) 100%);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    padding: 2.25rem 1.5rem;
    text-align: center;
    transition: all 0.35s cubic-bezier(0.25, 0.8, 0.25, 1);
    cursor: pointer;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: space-between;
    min-height: 330px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.45);
}
.mg-ttt-mode-card-v2::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: radial-gradient(circle at top right, rgba(0, 242, 254, 0.12), transparent 55%);
    z-index: 1;
    pointer-events: none;
    transition: opacity 0.3s ease;
}
.mg-ttt-mode-card-v2.active {
    border-color: rgba(0, 242, 254, 0.45);
    box-shadow: 0 0 35px rgba(0, 242, 254, 0.22), inset 0 0 12px rgba(0, 242, 254, 0.1);
    transform: translateY(-4px);
}
.mg-ttt-mode-card-v2:hover {
    transform: translateY(-5px);
    border-color: rgba(0, 242, 254, 0.35);
    box-shadow: 0 15px 45px rgba(0, 0, 0, 0.55), 0 0 25px rgba(0, 242, 254, 0.15);
}
.mg-ttt-mode-card-v2 .mode-badge {
    position: absolute;
    top: 1.1rem;
    right: 1.25rem;
    font-size: 0.68rem;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 0.12em;
    padding: 0.35rem 0.75rem;
    border-radius: 99px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.08);
    color: var(--mg-text-muted);
}
.mg-ttt-mode-card-v2.active .mode-badge {
    background: rgba(0, 242, 254, 0.15);
    border-color: rgba(0, 242, 254, 0.3);
    color: var(--ttt-cyan);
}
.mg-ttt-card-icon-wrap {
    width: 72px;
    height: 72px;
    border-radius: 20px;
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.05);
    display: grid;
    place-items: center;
    color: var(--mg-text-muted);
    transition: all 0.3s ease;
    margin-bottom: 1.25rem;
    position: relative;
    z-index: 2;
}
.mg-ttt-mode-card-v2:hover .mg-ttt-card-icon-wrap,
.mg-ttt-mode-card-v2.active .mg-ttt-card-icon-wrap {
    background: rgba(0, 242, 254, 0.1);
    border-color: rgba(0, 242, 254, 0.3);
    color: var(--ttt-cyan);
    box-shadow: 0 0 20px rgba(0, 242, 254, 0.2);
}
.mg-ttt-card-icon-wrap svg {
    width: 34px;
    height: 34px;
    fill: none;
    stroke: currentColor;
    stroke-width: 2;
    stroke-linecap: round;
    stroke-linejoin: round;
}
.mg-ttt-mode-card-v2 .mode-card-info {
    position: relative;
    z-index: 2;
    margin-bottom: 1.5rem;
}
.mg-ttt-mode-card-v2 h3 {
    margin: 0 0 0.5rem;
    font-size: 1.3rem;
    font-weight: 900;
    letter-spacing: -0.02em;
    color: #fff;
}
.mg-ttt-mode-card-v2 p {
    margin: 0;
    font-size: 0.85rem;
    color: var(--mg-text-muted);
    line-height: 1.55;
}
.mg-ttt-mode-card-v2 .card-action-btn {
    width: 100%;
    padding: 0.75rem 1.5rem;
    border-radius: 14px;
    font-weight: 900;
    font-size: 0.92rem;
    border: 1px solid rgba(255, 255, 255, 0.08);
    background: rgba(255, 255, 255, 0.03);
    color: #fff;
    cursor: pointer;
    transition: all 0.2s ease;
    position: relative;
    z-index: 2;
}
.mg-ttt-mode-card-v2.active .card-action-btn,
.mg-ttt-mode-card-v2:hover .card-action-btn {
    background: linear-gradient(135deg, var(--ttt-cyan), var(--ttt-violet));
    border-color: transparent;
    color: #07070a;
    box-shadow: 0 8px 22px rgba(0, 242, 254, 0.35);
}

/* Expandable settings drawer */
.mg-ttt-submode-drawer {
    max-height: 0;
    overflow: hidden;
    opacity: 0;
    transition: max-height 0.4s cubic-bezier(0.25, 0.8, 0.25, 1), opacity 0.3s ease, margin 0.3s ease, padding 0.3s ease;
    background: linear-gradient(145deg, rgba(20, 20, 28, 0.94), rgba(9, 9, 14, 0.98));
    border: 0 solid rgba(255, 255, 255, 0.08);
    border-radius: 24px;
    margin-bottom: 0;
}
.mg-ttt-submode-drawer.open {
    max-height: 500px;
    opacity: 1;
    border-width: 1px;
    padding: 1.75rem;
    margin-bottom: 2rem;
    box-shadow: 0 20px 45px rgba(0, 0, 0, 0.5), inset 0 0 20px rgba(0, 242, 254, 0.02);
}
.mg-ttt-drawer-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    align-items: flex-start;
    text-align: left;
}
.mg-ttt-drawer-section {
    display: flex;
    flex-direction: column;
    gap: 0.85rem;
}
.mg-ttt-drawer-section-title {
    font-size: 0.75rem;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    color: var(--ttt-cyan);
    font-weight: 900;
}
.mg-ttt-btn-group-v2 {
    display: flex;
    gap: 0.65rem;
    background: rgba(0, 0, 0, 0.3);
    border: 1px solid rgba(255, 255, 255, 0.05);
    padding: 0.35rem;
    border-radius: 16px;
}
.mg-ttt-btn-choice {
    flex: 1;
    background: transparent;
    border: none;
    color: var(--mg-text-muted);
    font-weight: 850;
    font-size: 0.9rem;
    padding: 0.75rem 1rem;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.2s ease;
}
.mg-ttt-btn-choice.active {
    background: rgba(255, 255, 255, 0.06);
    color: #fff;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.35);
}
.mg-ttt-btn-choice.active.diff-easy { color: var(--ttt-green); }
.mg-ttt-btn-choice.active.diff-pro { color: var(--ttt-amber); }
.mg-ttt-btn-choice.active.diff-world_class { color: var(--ttt-red); }

.mg-ttt-drawer-footer {
    grid-column: span 2;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1.5rem;
    border-top: 1px solid rgba(255, 255, 255, 0.06);
    padding-top: 1.25rem;
    margin-top: 0.5rem;
}
.mg-ttt-drawer-helper-text {
    font-size: 0.82rem;
    color: var(--mg-text-muted);
}
.mg-ttt-play-cta-v2 {
    background: linear-gradient(135deg, var(--ttt-cyan), var(--ttt-violet));
    color: #07070a;
    font-weight: 950;
    font-size: 0.98rem;
    padding: 0.85rem 2.25rem;
    border-radius: 16px;
    cursor: pointer;
    transition: all 0.25s ease;
    border: none;
    box-shadow: 0 8px 24px rgba(0, 242, 254, 0.35);
}
.mg-ttt-play-cta-v2:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 30px rgba(0, 242, 254, 0.5);
}
.mg-ttt-play-cta-v2:active {
    transform: translateY(0);
}

/* Autopilot dashboard card */
.mg-ttt-autopilot-panel-v2 {
    margin-top: 1.5rem;
    background: linear-gradient(135deg, rgba(0, 242, 254, 0.05) 0%, rgba(124, 58, 237, 0.02) 100%);
    border: 1px dashed rgba(0, 242, 254, 0.22);
    border-radius: 24px;
    padding: 1.4rem 1.6rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1.5rem;
    transition: all 0.3s ease;
    position: relative;
    z-index: 2;
    text-align: left;
}
.mg-ttt-autopilot-panel-v2:hover {
    background: linear-gradient(135deg, rgba(0, 242, 254, 0.08) 0%, rgba(124, 58, 237, 0.04) 100%);
    border-color: rgba(0, 242, 254, 0.35);
}
.mg-ttt-autopilot-details {
    display: flex;
    align-items: center;
    gap: 1.25rem;
}
.mg-ttt-autopilot-icon-wrap {
    width: 48px;
    height: 48px;
    border-radius: 16px;
    background: rgba(0, 242, 254, 0.1);
    color: var(--ttt-cyan);
    display: grid;
    place-items: center;
    box-shadow: 0 0 15px rgba(0, 242, 254, 0.1);
}
.mg-ttt-autopilot-icon-wrap svg {
    width: 24px;
    height: 24px;
    fill: none;
    stroke: currentColor;
    stroke-width: 2;
}
.mg-ttt-autopilot-text h4 {
    margin: 0 0 0.25rem;
    font-size: 1.05rem;
    font-weight: 850;
    color: #fff;
}
.mg-ttt-autopilot-text p {
    margin: 0;
    font-size: 0.82rem;
    color: var(--mg-text-muted);
}
.mg-ttt-toggle-row-v2 {
    display: flex;
    align-items: center;
    gap: 0.85rem;
    background: rgba(0, 0, 0, 0.25);
    border: 1px solid rgba(255, 255, 255, 0.05);
    padding: 0.6rem 1.1rem;
    border-radius: 16px;
    cursor: pointer;
    user-select: none;
    transition: all 0.2s ease;
}
.mg-ttt-toggle-row-v2:hover {
    border-color: rgba(0, 242, 254, 0.25);
    background: rgba(0, 0, 0, 0.35);
}
.mg-ttt-toggle-row-v2 span {
    font-weight: 900;
    font-size: 0.88rem;
    color: var(--ttt-cyan);
    letter-spacing: 0.03em;
}
.mg-ttt-toggle-row-v2 input {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

/* =========================================================
 * GAMEPLAY ARENA REDESIGN
 * ========================================================= */
.mg-ttt-arena-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.03) 0%, rgba(255, 255, 255, 0.005) 100%);
    border: 1px solid rgba(255, 255, 255, 0.06);
    padding: 0.8rem 1.25rem;
    border-radius: 20px;
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    position: relative;
    z-index: 2;
}
.mg-ttt-arena-layout {
    display: grid;
    grid-template-columns: minmax(190px, 0.6fr) minmax(380px, 1fr) minmax(190px, 0.6fr);
    gap: 1.5rem;
    align-items: stretch;
    position: relative;
    z-index: 2;
}
.mg-ttt-arena-container {
    min-height: 520px;
    padding: 2rem;
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 28px;
    background: radial-gradient(circle at 50% 30%, rgba(124, 58, 237, 0.15), transparent 45%), linear-gradient(145deg, rgba(20, 20, 28, 0.95), rgba(8, 8, 13, 0.98));
    box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.02), 0 30px 90px rgba(0, 0, 0, 0.45);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
}
.mg-ttt-arena-container::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: radial-gradient(circle at bottom right, rgba(0, 242, 254, 0.05), transparent 50%);
    pointer-events: none;
}
.mg-ttt-player-card {
    padding: 1.5rem 1.25rem;
    border: 1px solid rgba(255, 255, 255, 0.06);
    border-radius: 26px;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.03) 0%, rgba(255, 255, 255, 0.005) 100%);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.9rem;
    min-height: 240px;
    text-align: center;
    transition: all 0.35s cubic-bezier(0.25, 0.8, 0.25, 1);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
    position: relative;
}
.mg-ttt-player-card.active-turn {
    transform: translateY(-4px) scale(1.02);
}
.mg-ttt-player-card.is-home {
    border-color: rgba(0, 242, 254, 0.12);
}
.mg-ttt-player-card.is-home.active-turn {
    border-color: rgba(0, 242, 254, 0.45);
    box-shadow: 0 0 35px rgba(0, 242, 254, 0.22), inset 0 0 15px rgba(0, 242, 254, 0.1);
    animation: playerTurnHome 2s infinite ease-in-out;
}
.mg-ttt-player-card.is-rival {
    border-color: rgba(255, 95, 109, 0.1);
}
.mg-ttt-player-card.is-rival.active-turn {
    border-color: rgba(255, 95, 109, 0.4);
    box-shadow: 0 0 35px rgba(255, 95, 109, 0.18), inset 0 0 15px rgba(255, 95, 109, 0.08);
    animation: playerTurnRival 2s infinite ease-in-out;
}
@keyframes playerTurnHome {
    0%, 100% { box-shadow: 0 0 30px rgba(0, 242, 254, 0.18), inset 0 0 12px rgba(0, 242, 254, 0.08); }
    50% { box-shadow: 0 0 45px rgba(0, 242, 254, 0.32), inset 0 0 20px rgba(0, 242, 254, 0.15); }
}
@keyframes playerTurnRival {
    0%, 100% { box-shadow: 0 0 30px rgba(255, 95, 109, 0.15), inset 0 0 12px rgba(255, 95, 109, 0.06); }
    50% { box-shadow: 0 0 45px rgba(255, 95, 109, 0.28), inset 0 0 20px rgba(255, 95, 109, 0.12); }
}
.mg-ttt-avatar-ring {
    width: 80px;
    height: 80px;
    border-radius: 22px;
    border: 2px solid rgba(255, 255, 255, 0.1);
    display: grid;
    place-items: center;
    overflow: hidden;
    color: var(--ttt-amber);
    background: rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease;
}
.mg-ttt-player-card.active-turn .mg-ttt-avatar-ring {
    border-color: currentColor;
    box-shadow: 0 0 18px currentColor;
}
.mg-ttt-player-card.is-home .mg-ttt-avatar-ring {
    color: var(--ttt-cyan);
}
.mg-ttt-player-card.is-rival .mg-ttt-avatar-ring {
    color: var(--ttt-amber);
}
.mg-ttt-avatar-ring img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.mg-ttt-player-name {
    font-weight: 900;
    font-size: 1.05rem;
    color: #fff;
    margin: 0;
}
.mg-ttt-player-meta {
    color: var(--mg-text-muted);
    font-size: 0.72rem;
    text-transform: uppercase;
    letter-spacing: 0.15em;
    font-weight: 900;
}
.mg-ttt-vs-pill {
    width: max-content;
    padding: 0.35rem 0.85rem;
    border-radius: 999px;
    background: rgba(255, 176, 0, 0.1);
    color: var(--ttt-amber);
    border: 1px solid rgba(255, 176, 0, 0.25);
    font-weight: 900;
    font-size: 0.8rem;
    letter-spacing: 0.05em;
}
.mg-ttt-board {
    gap: 12px;
    background: rgba(0, 0, 0, 0.25);
    padding: 14px;
    border-radius: 28px;
    margin-top: 1.5rem;
    border: 1px solid rgba(255, 255, 255, 0.06);
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4), inset 0 0 20px rgba(255, 255, 255, 0.02);
    display: grid;
    grid-template-columns: repeat(3, 1fr);
}
.mg-ttt-cell {
    width: 104px;
    height: 104px;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.04) 0%, rgba(255, 255, 255, 0.005) 100%);
    border: 1px solid rgba(255, 255, 255, 0.07);
    border-radius: 20px;
    font-size: 3.25rem;
    font-weight: 950;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.25s cubic-bezier(0.25, 0.8, 0.25, 1);
    text-shadow: 0 0 20px currentColor;
    color: var(--mg-text);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
}
button.mg-ttt-cell {
    cursor: pointer;
}
.mg-ttt-cell:hover:not(.x):not(.o) {
    transform: translateY(-3px) scale(1.04);
    border-color: rgba(0, 242, 254, 0.35);
    background: rgba(0, 242, 254, 0.08);
    box-shadow: 0 8px 25px rgba(0, 242, 254, 0.15);
}
.mg-ttt-cell.x {
    color: var(--ttt-cyan);
}
.mg-ttt-cell.o {
    color: var(--ttt-amber);
}
.mg-ttt-cell.win {
    border-color: rgba(0, 231, 167, 0.65);
    background: rgba(0, 231, 167, 0.08);
    box-shadow: 0 0 30px rgba(0, 231, 167, 0.28), inset 0 0 12px rgba(0, 231, 167, 0.15);
    animation: winPulse 1.5s infinite alternate ease-in-out;
}
@keyframes winPulse {
    from { transform: scale(1); }
    to { transform: scale(1.03); }
}
.mg-ttt-board.is-locked .mg-ttt-cell {
    cursor: not-allowed;
    opacity: 0.85;
}
.mg-ttt-status {
    font-size: clamp(1.4rem, 2vw, 2.3rem);
    font-weight: 900;
    margin-bottom: 0.35rem;
    letter-spacing: -0.04em;
    color: #fff;
}
.mg-ttt-substatus {
    color: var(--mg-text-muted);
    font-weight: 600;
    font-size: 0.9rem;
}

/* =========================================================
 * TOURNAMENT & SHOP WIDGETS
 * ========================================================= */
.mg-shop-section {
    margin-bottom: 1.5rem;
    position: relative;
    z-index: 2;
    text-align: left;
}
.mg-shop-section-title {
    font-size: 1.25rem;
    margin-bottom: 1rem;
    color: var(--ttt-amber);
    border: 0;
    padding: 0;
    font-weight: 850;
}
.mg-shop-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
    gap: 1.25rem;
}
.mg-shop-item {
    border: 1px solid rgba(255, 255, 255, 0.08);
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.04) 0%, rgba(255, 255, 255, 0.005) 100%);
    border-radius: 22px;
    padding: 1.5rem 1.25rem;
    text-align: center;
    transition: all 0.25s cubic-bezier(0.25, 0.8, 0.25, 1);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
}
.mg-shop-item:hover {
    transform: translateY(-4px);
    border-color: rgba(255, 176, 0, 0.3);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.35), 0 0 20px rgba(255, 176, 0, 0.1);
}
.mg-shop-item-icon {
    width: 54px;
    height: 54px;
    margin: 0 auto 0.85rem;
    border-radius: 18px;
    display: grid;
    place-items: center;
    background: rgba(255, 176, 0, 0.08);
    color: var(--ttt-amber);
    font-size: 1.8rem;
}
.mg-shop-item-icon:empty::before {
    content: '';
    width: 24px;
    height: 24px;
    border: 2.2px solid currentColor;
    border-radius: 8px;
    box-shadow: inset 8px 0 0 rgba(255, 176, 0, 0.15);
}
.mg-shop-item-title {
    font-size: 1.05rem;
    font-weight: 850;
    margin-bottom: 0.35rem;
    color: #fff;
}
.mg-shop-item-price {
    color: var(--ttt-green);
    font-weight: 900;
    font-size: 1.1rem;
    margin-bottom: 0.85rem;
}
.mg-shop-item-owned {
    color: var(--mg-text-muted);
    font-weight: 850;
    font-size: 0.88rem;
    padding: 0.5rem;
    background: rgba(255, 255, 255, 0.04);
    border-radius: 10px;
    border: 1px solid rgba(255, 255, 255, 0.06);
}
.mg-tournament-bracket {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.03) 0%, rgba(255, 255, 255, 0.005) 100%);
    padding: 1.25rem;
    border-radius: 24px;
    border: 1px solid rgba(255, 255, 255, 0.07);
    margin-bottom: 1.5rem;
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.25);
    display: none;
}
.mg-tournament-bracket.active {
    display: flex;
    justify-content: space-around;
}
.mg-bracket-round {
    display: flex;
    flex-direction: column;
    justify-content: space-around;
    gap: 1.25rem;
}
.mg-matchup {
    background: rgba(0, 0, 0, 0.25);
    border: 1px solid rgba(255, 255, 255, 0.06);
    padding: 0.75rem 1rem;
    border-radius: 16px;
    font-weight: 850;
    color: var(--mg-text-muted);
    transition: all 0.3s ease;
    min-width: 130px;
}
.mg-matchup.active-match {
    border-color: var(--ttt-amber);
    box-shadow: 0 0 22px rgba(255, 176, 0, 0.18), inset 0 0 10px rgba(255, 176, 0, 0.08);
    color: #fff;
    background: rgba(255, 176, 0, 0.06);
}
.mg-leaderboard-table {
    width: 100%;
    border-collapse: collapse;
    background: rgba(255, 255, 255, 0.015);
    border-radius: 24px;
    border: 1px solid rgba(255, 255, 255, 0.06);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    overflow: hidden;
    margin-top: 1rem;
}
.mg-leaderboard-table th {
    background: rgba(255, 255, 255, 0.04);
    color: var(--ttt-amber);
    font-weight: 900;
    padding: 1.1rem;
    text-align: left;
}
.mg-leaderboard-table td {
    padding: 1.1rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.04);
    text-align: left;
}
.mg-leaderboard-table tr:last-child td {
    border-bottom: none;
}

/* =========================================================
 * POPUP MODALS & RESULTS REDESIGN
 * ========================================================= */
.mg-ttt-result-panel {
    display: none;
    width: 100%;
    margin-top: 1.5rem;
    padding: 1.6rem;
    border-radius: 26px;
    border: 1px solid rgba(255, 255, 255, 0.08);
    background: radial-gradient(circle at 15% 0%, rgba(255, 176, 0, 0.12), transparent 40%), linear-gradient(145deg, rgba(22, 22, 31, 0.98), rgba(9, 9, 14, 0.99));
    box-shadow: 0 30px 90px rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    animation: resultSlideIn 0.35s cubic-bezier(0.25, 0.8, 0.25, 1) forwards;
}
@keyframes resultSlideIn {
    from { opacity: 0; transform: translateY(15px); }
    to { opacity: 1; transform: translateY(0); }
}
.mg-ttt-result-panel.active {
    display: block;
}
.mg-ttt-result-grid {
    display: grid;
    grid-template-columns: auto 1fr;
    gap: 1.25rem;
    align-items: center;
    text-align: left;
}
.mg-ttt-result-icon {
    width: 66px;
    height: 66px;
    border-radius: 20px;
    display: grid;
    place-items: center;
    color: #111827;
    background: linear-gradient(135deg, #ffb000, #ff7a00);
    box-shadow: 0 8px 25px rgba(255, 176, 0, 0.35);
}
.mg-ttt-result-icon.is-loss {
    background: linear-gradient(135deg, #ff5f6d, #b91c1c);
    color: #fff;
    box-shadow: 0 8px 25px rgba(255, 95, 109, 0.35);
}
.mg-ttt-result-icon.is-draw {
    background: linear-gradient(135deg, #94a3b8, #475569);
    color: #fff;
    box-shadow: 0 8px 25px rgba(148, 163, 184, 0.25);
}
.mg-ttt-result-actions {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 0.75rem;
    margin-top: 1.25rem;
}
.mg-ttt-result-title {
    margin: 0;
    font-size: clamp(1.6rem, 2.5vw, 2.5rem);
    font-weight: 1000;
    letter-spacing: -0.04em;
    color: #fff;
}
.mg-ttt-result-copy {
    margin: 0.35rem 0 0;
    color: var(--mg-text-muted);
    line-height: 1.6;
    font-size: 0.9rem;
}
.mg-ttt-result-reward {
    margin-top: 1rem;
    padding: 0.9rem 1.25rem;
    border-radius: 18px;
    border: 1px solid rgba(0, 255, 179, 0.25);
    background: rgba(0, 255, 179, 0.08);
    color: #00ffb3;
    font-weight: 950;
    text-align: center;
    font-size: 1.1rem;
    box-shadow: 0 0 20px rgba(0, 255, 179, 0.1);
}

/* Modal boxes */
.mg-ttt-local-modal {
    position: fixed;
    inset: 0;
    z-index: 100005;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 1rem;
    background: rgba(0, 0, 0, 0.65);
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);
    pointer-events: none;
    transition: opacity 0.3s ease;
}
.mg-ttt-local-modal.active {
    display: flex;
    pointer-events: auto;
}
.mg-ttt-local-card {
    width: min(92vw, 460px);
    border-radius: 28px;
    border: 1px solid rgba(255, 255, 255, 0.08);
    background: radial-gradient(circle at top left, rgba(0, 242, 254, 0.1), transparent 40%), linear-gradient(145deg, rgba(22, 22, 30, 0.98), rgba(7, 7, 12, 0.99));
    box-shadow: 0 30px 90px rgba(0, 0, 0, 0.55), inset 0 0 20px rgba(255, 255, 255, 0.01);
    padding: 1.75rem;
    transform: scale(0.95);
    opacity: 0;
    transition: transform 0.3s cubic-bezier(0.25, 0.8, 0.25, 1), opacity 0.3s ease;
}
.mg-ttt-local-modal.active .mg-ttt-local-card {
    transform: scale(1);
    opacity: 1;
}
.mg-ttt-modal-head {
    display: flex;
    gap: 1rem;
    align-items: flex-start;
    text-align: left;
}
.mg-ttt-modal-title {
    margin: 0;
    font-size: 1.35rem;
    font-weight: 950;
    color: #fff;
}
.mg-ttt-modal-message {
    margin: 0.45rem 0 0;
    color: var(--mg-text-muted);
    line-height: 1.6;
    font-size: 0.92rem;
}
.mg-ttt-modal-actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.75rem;
    margin-top: 1.5rem;
}
.mg-ttt-danger-btn {
    background: linear-gradient(135deg, #ff5f6d, #b91c1c);
    color: #fff;
    border: none;
    font-weight: 900;
    border-radius: 14px;
    cursor: pointer;
    transition: all 0.2s ease;
    padding: 0.75rem;
}
.mg-ttt-danger-btn:hover {
    box-shadow: 0 8px 20px rgba(255, 95, 109, 0.3);
}
.mg-ttt-muted-btn {
    background: rgba(255, 255, 255, 0.04);
    color: var(--mg-text);
    border: 1px solid rgba(255, 255, 255, 0.08);
    font-weight: 900;
    border-radius: 14px;
    cursor: pointer;
    transition: all 0.2s ease;
    padding: 0.75rem;
}
.mg-ttt-muted-btn:hover {
    background: rgba(255, 255, 255, 0.08);
}
.mg-ttt-mark {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: grid;
    place-items: center;
    background: linear-gradient(135deg, var(--ttt-amber), #ff7a00);
    color: #07070a;
}
.mg-ttt-svg {
    width: 22px;
    height: 22px;
    fill: none;
    stroke: currentColor;
    stroke-width: 2.2;
}

/* IMMERSIVE MATCHMAKING & SPECTATOR OVERLAYS */
.mg-ttt-overlay {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 999999;
    align-items: center;
    justify-content: center;
    padding: 1.5rem;
    background: radial-gradient(circle at 15% 15%, rgba(0, 242, 254, 0.1), transparent 45%), rgba(5, 5, 9, 0.95);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    pointer-events: none;
    opacity: 0;
    transition: opacity 0.3s ease;
}
.mg-ttt-overlay.active {
    display: flex;
    pointer-events: auto;
    opacity: 1;
}
.mg-ttt-versus-card,
.mg-ttt-spectator-card {
    width: min(920px, 94vw);
    border-radius: 36px;
    border: 1px solid rgba(255, 255, 255, 0.08);
    background: linear-gradient(145deg, rgba(22, 22, 30, 0.94), rgba(8, 8, 13, 0.97));
    box-shadow: 0 40px 110px rgba(0, 0, 0, 0.65), inset 0 0 30px rgba(0, 242, 254, 0.02);
    padding: 2.5rem 2rem;
    text-align: center;
    position: relative;
    overflow: hidden;
}
.mg-ttt-versus-card::before,
.mg-ttt-spectator-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: radial-gradient(circle at bottom center, rgba(124, 58, 237, 0.08), transparent 50%);
    pointer-events: none;
}
.mg-ttt-versus-card h3,
.mg-ttt-spectator-card h3 {
    margin: 0.25rem 0 1.25rem;
    font-size: clamp(1.6rem, 3vw, 2.75rem);
    font-weight: 950;
    letter-spacing: -0.04em;
    color: #fff;
}
.mg-ttt-kicker {
    font-size: 0.75rem;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    color: var(--ttt-cyan);
    font-weight: 900;
    margin: 0;
}
.mg-ttt-versus-row {
    display: grid;
    grid-template-columns: 1fr auto 1fr;
    align-items: center;
    gap: 1.5rem;
    margin-top: 1.5rem;
}
.mg-ttt-fighter-card {
    min-height: 240px;
    border-radius: 28px;
    border: 1px solid rgba(255, 255, 255, 0.06);
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.03) 0%, rgba(255, 255, 255, 0.005) 100%);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    padding: 1.5rem;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.25);
    transition: all 0.3s ease;
}
.mg-ttt-fighter-card.is-player {
    border-color: rgba(0, 242, 254, 0.15);
}
.mg-ttt-fighter-card.is-rival {
    border-color: rgba(255, 95, 109, 0.12);
}
.mg-ttt-fighter-card img {
    width: 90px;
    height: 90px;
    border-radius: 24px;
    object-fit: cover;
    border: 2px solid rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
}
.mg-ttt-fighter-card.is-player img {
    border-color: var(--ttt-cyan);
    box-shadow: 0 0 20px rgba(0, 242, 254, 0.25);
}
.mg-ttt-fighter-card.is-rival img {
    border-color: var(--ttt-amber);
    box-shadow: 0 0 20px rgba(255, 176, 0, 0.2);
}
.mg-ttt-fighter-card strong {
    font-size: 1.25rem;
    color: #fff;
    font-weight: 900;
}
.mg-ttt-fighter-card span {
    color: var(--mg-text-muted);
    text-transform: uppercase;
    letter-spacing: 0.15em;
    font-size: 0.7rem;
    font-weight: 900;
}
.mg-ttt-vs-badge {
    width: 76px;
    height: 76px;
    border-radius: 24px;
    display: grid;
    place-items: center;
    font-weight: 1000;
    color: #111827;
    font-size: 1.4rem;
    background: linear-gradient(135deg, var(--ttt-amber), #ff7a00);
    box-shadow: 0 0 40px rgba(255, 176, 0, 0.35);
    animation: vsBadgePulse 1.5s infinite alternate ease-in-out;
}
@keyframes vsBadgePulse {
    from { transform: scale(1) rotate(-3deg); box-shadow: 0 0 35px rgba(255, 176, 0, 0.3); }
    to { transform: scale(1.08) rotate(3deg); box-shadow: 0 0 50px rgba(255, 176, 0, 0.5); }
}
.mg-ttt-rival-orb {
    width: 90px;
    height: 90px;
    border-radius: 24px;
    display: grid;
    place-items: center;
    font-weight: 1000;
    font-size: 2.5rem;
    color: var(--ttt-cyan);
    background: radial-gradient(circle, rgba(0, 242, 254, 0.15), rgba(255, 255, 255, 0.02));
    border: 1.5px dashed rgba(0, 242, 254, 0.35);
    box-shadow: 0 0 25px rgba(0, 242, 254, 0.1);
    animation: pulseOrb 2s infinite ease-in-out;
}
@keyframes pulseOrb {
    0%, 100% { transform: scale(1); border-color: rgba(0, 242, 254, 0.35); }
    50% { transform: scale(1.04); border-color: rgba(0, 242, 254, 0.65); box-shadow: 0 0 35px rgba(0, 242, 254, 0.25); }
}
.mg-ttt-scan-track {
    height: 8px;
    border-radius: 99px;
    margin-top: 1.75rem;
    overflow: hidden;
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.08);
}
.mg-ttt-scan-bar {
    height: 100%;
    width: 0%;
    border-radius: inherit;
    background: linear-gradient(90deg, var(--ttt-violet), var(--ttt-cyan), var(--ttt-amber));
    box-shadow: 0 0 10px rgba(0, 242, 254, 0.5);
}
.mg-ttt-danger-outline {
    border-color: rgba(255, 95, 109, 0.5) !important;
    color: #ff9aa3 !important;
    background: rgba(255, 95, 109, 0.05) !important;
    font-weight: 850;
    transition: all 0.2s ease;
}
.mg-ttt-danger-outline:hover {
    background: rgba(255, 95, 109, 0.12) !important;
    box-shadow: 0 0 15px rgba(255, 95, 109, 0.15);
}

/* Responsive queries */
@media (max-width: 980px) {
    .mg-ttt-main-grid,
    .mg-ttt-arena-layout {
        grid-template-columns: 1fr;
    }
    .mg-ttt-result-actions,
    .mg-ttt-action-row {
        grid-template-columns: 1fr;
    }
    .mg-ttt-player-card {
        min-height: 160px;
        flex-direction: row;
        justify-content: flex-start;
        gap: 1.5rem;
    }
    .mg-ttt-mode-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
}
/* Detail skin: Zero Infinity arena */
#mg-screen-ttt {
    --ttt-cyan: #ff4f8b;
    --ttt-violet: #7ed3ff;
    --ttt-amber: #ffd278;
    --ttt-green: #8ef6bd;
    --ttt-red: #ff6f7b;
    --ttt-border: rgba(255, 210, 120, 0.18);
    --ttt-border-glow: rgba(255, 79, 139, 0.28);
    --ttt-visible-center-shift: clamp(-11rem, -8vw, -7rem);
    --ttt-visible-width: 117.65vw;
    --ttt-visible-inner-width: calc(var(--ttt-visible-width) - 2.5rem);
    --ttt-radius-lg: 8px;
    --ttt-radius-md: 8px;
    --ttt-radius-sm: 8px;
    background:
        radial-gradient(circle at 22% 18%, rgba(255, 79, 139, 0.16), transparent 28%),
        radial-gradient(circle at 78% 14%, rgba(126, 211, 255, 0.12), transparent 32%),
        linear-gradient(135deg, #07060b 0%, #14101a 55%, #09070d 100%);
    width: 100%;
    max-width: none;
    margin: 0;
}

#mg-screen-ttt::before {
    background-image:
        linear-gradient(rgba(255, 210, 120, 0.045) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255, 79, 139, 0.04) 1px, transparent 1px);
    background-size: 54px 54px;
    opacity: 0.42;
}

#mg-screen-ttt .mg-game-lobby-topbar,
#mg-screen-ttt .mg-ttt-tabs,
#mg-screen-ttt .mg-ttt-mode-card-v2,
#mg-screen-ttt .mg-ttt-submode-drawer.open,
#mg-screen-ttt .mg-ttt-autopilot-panel-v2,
#mg-screen-ttt .mg-ttt-arena-container,
#mg-screen-ttt .mg-ttt-player-card,
#mg-screen-ttt .mg-ttt-board,
#mg-screen-ttt .mg-tournament-bracket,
#mg-screen-ttt .mg-matchup,
#mg-screen-ttt .mg-leaderboard-table,
#mg-screen-ttt .mg-ttt-result-panel,
#mg-screen-ttt .mg-ttt-spectator-card {
    border-radius: 8px;
    border-color: rgba(255, 210, 120, 0.16);
    background:
        repeating-linear-gradient(135deg, rgba(255, 255, 255, 0.025) 0 1px, transparent 1px 18px),
        linear-gradient(145deg, rgba(18, 16, 26, 0.96), rgba(9, 8, 14, 0.94));
    box-shadow: 0 18px 46px rgba(0, 0, 0, 0.34);
}

#mg-screen-ttt .mg-game-lobby-topbar {
    display: grid;
    width: min(1580px, calc(100% - 8rem));
    max-width: 1580px;
    margin: 2rem auto 0;
    padding: 0.9rem 1rem;
    grid-template-columns: minmax(270px, 1fr) minmax(600px, 1.7fr) minmax(170px, auto);
    align-items: center;
    gap: 1rem;
    transform: translateX(var(--ttt-visible-center-shift));
}

#mg-screen-ttt .mg-game-lobby-brand {
    min-width: 0;
}

#mg-screen-ttt .mg-game-lobby-tabs {
    display: grid;
    grid-template-columns: repeat(5, minmax(86px, 1fr));
    justify-content: center;
    align-items: center;
    gap: 0.65rem;
}

#mg-screen-ttt .mg-game-lobby-tab {
    width: 100%;
    min-height: 42px;
    justify-content: center;
}

#mg-screen-ttt .mg-game-lobby-actions {
    justify-content: flex-end;
    gap: 0.7rem;
}

#mg-screen-ttt #ttt-main-view,
#mg-screen-ttt #ttt-tab-play {
    width: 100%;
}

#mg-screen-ttt #ttt-tab-play .mg-game-lobby-frame {
    width: 100%;
    min-height: calc(var(--vh-fixed, 100vh) - 8rem);
    display: flex;
    align-items: center;
    justify-content: center;
    border: 0;
    background: none;
    box-shadow: none;
    overflow: visible;
}

#mg-screen-ttt #ttt-tab-play .mg-game-lobby-main {
    width: 100%;
    min-height: calc(var(--vh-fixed, 100vh) - 11.5rem);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: clamp(2rem, 5vh, 4rem) 2rem 3rem;
}

#mg-screen-ttt #ttt-tab-play .mg-game-mode-panel {
    width: min(980px, 100%);
    text-align: center;
    display: grid;
    justify-items: center;
    transform: translateX(var(--ttt-visible-center-shift)) translateY(-2.25rem);
}

#mg-screen-ttt #ttt-tab-play .mg-game-mode-title {
    margin: 0 auto 2rem;
    max-width: 900px;
    font-size: clamp(2.35rem, 4.2vw, 4.25rem);
    line-height: 1.02;
    letter-spacing: 0;
    color: rgba(248, 250, 252, 0.9);
    text-shadow:
        0 0 30px rgba(255, 79, 139, 0.24),
        0 0 18px rgba(255, 210, 120, 0.1);
}

#mg-screen-ttt #ttt-tab-play .mg-game-mode-buttons {
    width: min(860px, 100%);
    margin: 0 auto;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 1rem;
}

#mg-screen-ttt #ttt-tab-play .mg-game-mode-btn {
    min-height: 72px;
    border-width: 1px;
    background:
        linear-gradient(145deg, rgba(255, 79, 139, 0.18), rgba(255, 210, 120, 0.06)),
        rgba(12, 10, 17, 0.62);
    box-shadow:
        inset 0 1px 0 rgba(255, 255, 255, 0.05),
        0 14px 30px rgba(255, 79, 139, 0.08);
}

#mg-screen-ttt #ttt-tab-play .mg-game-autopilot-row {
    width: min(860px, 100%);
    min-width: 0;
    min-height: 50px;
    margin: 1.35rem auto 0;
    padding: 0.65rem 0.85rem 0.65rem 1rem;
    border-width: 1px;
    background:
        linear-gradient(145deg, rgba(255, 79, 139, 0.14), rgba(255, 210, 120, 0.05)),
        rgba(9, 8, 14, 0.38);
    box-shadow:
        inset 0 1px 0 rgba(255, 255, 255, 0.04),
        0 10px 22px rgba(255, 79, 139, 0.08);
}

#mg-screen-ttt #ttt-tab-play .mg-game-autopilot-row span {
    font-size: 1rem;
    letter-spacing: 0;
}

#mg-screen-ttt #ttt-tab-play .mg-game-switch {
    width: 54px;
    height: 30px;
}

#mg-screen-ttt #ttt-tab-play .mg-game-switch::after {
    width: 20px;
    height: 20px;
}

#mg-screen-ttt #ttt-tab-play .mg-game-autopilot-row input:checked + .mg-game-switch::after {
    transform: translateX(25px);
}

#mg-screen-ttt #ttt-tab-play .mg-game-lobby-footer {
    display: none;
}

#mg-screen-ttt .mg-game-lobby-logo,
#mg-screen-ttt .mg-game-lobby-balance,
#mg-screen-ttt .mg-game-lobby-exit,
#mg-screen-ttt .mg-ttt-tab-btn,
#mg-screen-ttt .mg-ttt-btn-choice,
#mg-screen-ttt .mg-ttt-danger-outline,
#mg-screen-ttt .mg-ttt-arena-action,
#mg-screen-ttt .card-action-btn,
#mg-screen-ttt .mg-ttt-play-cta-v2,
#mg-screen-ttt .mg-ttt-result-actions button {
    border-radius: 8px;
}

#mg-screen-ttt .mg-game-lobby-logo {
    width: 46px;
    height: 46px;
    border-radius: 12px;
    color: #ffd278;
    background:
        radial-gradient(circle at 50% 44%, rgba(255, 210, 120, 0.16), transparent 44%),
        linear-gradient(145deg, rgba(255, 79, 139, 0.28), rgba(12, 10, 18, 0.92) 62%, rgba(126, 211, 255, 0.16));
    border: 1px solid rgba(255, 210, 120, 0.3);
    box-shadow:
        inset 0 1px 0 rgba(255, 255, 255, 0.08),
        0 14px 28px rgba(255, 79, 139, 0.16),
        0 10px 24px rgba(255, 210, 120, 0.08);
}

#mg-screen-ttt .mg-game-lobby-logo svg {
    width: 25px;
    height: 25px;
    stroke: currentColor;
    stroke-width: 2.15;
    filter: drop-shadow(0 0 8px rgba(255, 210, 120, 0.22));
}

#mg-screen-ttt .mg-game-lobby-logo-icon,
#mg-screen-ttt .mg-ttt-card-icon-wrap,
#mg-screen-ttt .mg-shop-item-icon,
#mg-screen-ttt .mg-game-store-icon,
#mg-screen-ttt .mg-ttt-result-icon {
    border-radius: 8px;
    color: #ffd278;
    background: linear-gradient(145deg, rgba(255, 79, 139, 0.16), rgba(255, 210, 120, 0.1));
    border: 1px solid rgba(255, 210, 120, 0.18);
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.08);
}

#mg-screen-ttt .mg-ttt-tab-btn.active,
#mg-screen-ttt .mg-ttt-tab-btn:hover,
#mg-screen-ttt .mg-ttt-mode-card-v2.active,
#mg-screen-ttt .mg-ttt-mode-card-v2:hover,
#mg-screen-ttt .mg-ttt-btn-choice:hover,
#mg-screen-ttt .mg-ttt-btn-choice.active {
    border-color: rgba(255, 79, 139, 0.44);
    color: #ffd278;
    background: linear-gradient(135deg, rgba(255, 79, 139, 0.16), rgba(255, 210, 120, 0.06));
    box-shadow: 0 16px 38px rgba(255, 79, 139, 0.13);
}

#mg-screen-ttt .mg-ttt-mode-card-v2::before {
    background: linear-gradient(135deg, rgba(255, 79, 139, 0.16), transparent 55%);
}

#mg-screen-ttt .mg-ttt-mode-card-v2.active .mode-badge,
#mg-screen-ttt .mg-ttt-mode-card-v2:hover .mode-badge,
#mg-screen-ttt .mg-shop-item-owned {
    color: #ffd278;
    border-color: rgba(255, 210, 120, 0.28);
    background: rgba(255, 210, 120, 0.1);
}

#mg-screen-ttt .mg-ttt-mode-card-v2.active .card-action-btn,
#mg-screen-ttt .mg-ttt-mode-card-v2:hover .card-action-btn,
#mg-screen-ttt .mg-ttt-play-cta-v2,
#mg-screen-ttt .mg-ttt-result-actions button:first-child {
    color: #17070d;
    border-color: transparent;
    background: linear-gradient(135deg, #ff4f8b, #ffd278);
    box-shadow: 0 14px 32px rgba(255, 79, 139, 0.24);
}

#mg-screen-ttt .mg-ttt-board {
    gap: 10px;
    padding: 12px;
}

#mg-screen-ttt .mg-ttt-cell {
    border-radius: 8px;
    color: #eef7ff;
    border-color: rgba(255, 210, 120, 0.16);
    background: rgba(255, 255, 255, 0.045);
    box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.025);
}

#mg-screen-ttt .mg-ttt-cell:hover:not(.x):not(.o) {
    border-color: rgba(255, 79, 139, 0.46);
    background: rgba(255, 79, 139, 0.09);
}

#mg-screen-ttt .mg-ttt-cell.x {
    color: #ff7aa8;
    text-shadow: 0 0 24px rgba(255, 79, 139, 0.45);
}

#mg-screen-ttt .mg-ttt-cell.o {
    color: #ffd278;
    text-shadow: 0 0 24px rgba(255, 210, 120, 0.38);
}

#mg-screen-ttt .mg-ttt-cell.win {
    color: #8ef6bd;
    border-color: rgba(142, 246, 189, 0.66);
    background: rgba(142, 246, 189, 0.1);
}

#mg-screen-ttt .mg-ttt-avatar-ring {
    border-color: rgba(255, 210, 120, 0.34);
    box-shadow: 0 0 0 4px rgba(255, 79, 139, 0.08), 0 0 26px rgba(255, 210, 120, 0.13);
}

#mg-screen-ttt .mg-ttt-vs-pill,
#mg-screen-ttt .mg-ttt-status,
#mg-screen-ttt .mg-shop-item-price,
#mg-screen-ttt .mg-game-store-price,
#mg-screen-ttt .mg-ttt-result-reward {
    color: #8ef6bd;
}

#mg-screen-ttt .mg-shop-grid,
#mg-screen-ttt .mg-game-store-grid {
    gap: 14px;
}

#mg-screen-ttt .mg-shop-item,
#mg-screen-ttt .mg-game-store-card {
    border-radius: 8px;
    border-color: rgba(255, 210, 120, 0.14);
    background: linear-gradient(145deg, rgba(18, 16, 26, 0.95), rgba(8, 7, 12, 0.94));
    box-shadow: 0 14px 34px rgba(0, 0, 0, 0.24);
}

#mg-screen-ttt .mg-shop-item:hover,
#mg-screen-ttt .mg-game-store-card:hover {
    border-color: rgba(255, 79, 139, 0.38);
    transform: translateY(-2px);
}

#mg-screen-ttt .mg-tournament-bracket,
#mg-screen-ttt .mg-bracket-round {
    gap: 12px;
}

#mg-screen-ttt .mg-matchup.winner {
    border-color: rgba(142, 246, 189, 0.42);
    background: rgba(142, 246, 189, 0.08);
}

#mg-screen-ttt .mg-ttt-overlay {
    background: rgba(4, 3, 7, 0.82);
    backdrop-filter: blur(18px);
}

#mg-screen-ttt .mg-ttt-matchmaking-card {
    border-radius: 8px;
    border-color: rgba(255, 210, 120, 0.2);
    background: linear-gradient(145deg, rgba(18, 16, 26, 0.97), rgba(9, 8, 14, 0.96));
}

#mg-screen-ttt .mg-ttt-scan-bar {
    background: linear-gradient(90deg, #ff4f8b, #ffd278, #7ed3ff);
}

#mg-screen-ttt,
#minigame-wrapper .mg-game-lobby-topbar[data-mg-docked-screen="ttt"] {
    --ttt-visible-center-shift: 0;
    --ttt-visible-width: 117.65vw;
    --ttt-visible-inner-width: calc(var(--ttt-visible-width) - 2.5rem);
    --ttt-docked-topbar-width: 157vw;
    --ttt-docked-topbar-inner-width: calc(var(--ttt-docked-topbar-width) - 2.5rem);
    --ttt-content-shift: 0px;
}

#mg-screen-ttt .mg-game-lobby-topbar,
#minigame-wrapper .mg-game-lobby-topbar[data-mg-docked-screen="ttt"],
#mg-topbar-lobby-host .mg-ttt-lobby-topbar {
    box-sizing: border-box;
    display: flex !important;
    align-items: center;
    width: 100% !important;
    max-width: none !important;
    justify-content: flex-start;
    transform: none;
    overflow: visible;
    position: relative;
    min-height: 86px;
    padding: 0.9rem 1.25rem;
}

#minigame-wrapper .mg-game-lobby-topbar[data-mg-docked-screen="ttt"],
#mg-topbar-lobby-host .mg-ttt-lobby-topbar {
    width: min(var(--ttt-docked-topbar-inner-width), 100%) !important;
    max-width: var(--ttt-docked-topbar-inner-width) !important;
}

#mg-screen-ttt .mg-game-lobby-tabs,
#minigame-wrapper .mg-game-lobby-topbar[data-mg-docked-screen="ttt"] .mg-game-lobby-tabs,
#mg-topbar-lobby-host .mg-ttt-lobby-topbar .mg-game-lobby-tabs {
    display: flex !important;
    flex: none;
    justify-content: center;
    grid-template-columns: none;
    width: auto;
    min-width: 0;
    gap: clamp(1rem, 1.85vw, 2.65rem);
    position: absolute !important;
    left: 50% !important;
    top: 50% !important;
    transform: translate(-50%, -50%) !important;
    z-index: 2;
}

#mg-screen-ttt .mg-game-lobby-actions,
#minigame-wrapper .mg-game-lobby-topbar[data-mg-docked-screen="ttt"] .mg-game-lobby-actions,
#mg-topbar-lobby-host .mg-ttt-lobby-topbar .mg-game-lobby-actions {
    display: flex !important;
    flex: none;
    align-items: center;
    justify-content: flex-end;
    gap: 0.85rem;
    min-width: max-content;
    width: max-content;
    max-width: calc(100% - 2.5rem);
    position: absolute !important;
    right: clamp(1rem, 1.25vw, 1.45rem) !important;
    left: auto !important;
    top: 50% !important;
    bottom: auto !important;
    transform: translateY(-50%) !important;
    z-index: 3;
}

#mg-screen-ttt .mg-game-lobby-brand,
#minigame-wrapper .mg-game-lobby-topbar[data-mg-docked-screen="ttt"] .mg-game-lobby-brand,
#mg-topbar-lobby-host .mg-ttt-lobby-topbar .mg-game-lobby-brand {
    flex: 0 1 330px;
    min-width: 230px;
    position: relative;
    z-index: 2;
}

#mg-screen-ttt .mg-game-rankings-kicker {
    display: none;
}

#mg-screen-ttt .mg-game-lobby-subtitle,
#minigame-wrapper .mg-game-lobby-topbar[data-mg-docked-screen="ttt"] .mg-game-lobby-subtitle {
    display: block;
    margin-top: 0.18rem;
}

#mg-screen-ttt .mg-game-lobby-balance,
#minigame-wrapper .mg-game-lobby-topbar[data-mg-docked-screen="ttt"] .mg-game-lobby-balance {
    min-width: 118px;
    justify-content: center;
    white-space: nowrap;
}

#mg-screen-ttt .mg-game-lobby-exit,
#minigame-wrapper .mg-game-lobby-topbar[data-mg-docked-screen="ttt"] .mg-game-lobby-exit {
    min-width: 142px;
    white-space: nowrap;
}

#mg-screen-ttt .mg-game-lobby-logo,
#minigame-wrapper .mg-game-lobby-topbar[data-mg-docked-screen="ttt"] .mg-game-lobby-logo {
    overflow: hidden;
    background: rgba(9, 8, 14, 0.86);
}

#mg-screen-ttt .mg-game-lobby-logo img,
#minigame-wrapper .mg-game-lobby-topbar[data-mg-docked-screen="ttt"] .mg-game-lobby-logo img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    padding: 5px;
}

#mg-screen-ttt .mg-game-guide-button,
#minigame-wrapper .mg-game-lobby-topbar[data-mg-docked-screen="ttt"] .mg-game-guide-button {
    width: 44px;
    min-width: 44px;
    min-height: 42px;
    padding: 0;
    display: grid;
    place-items: center;
}

#mg-screen-ttt .mg-game-guide-button svg,
#minigame-wrapper .mg-game-lobby-topbar[data-mg-docked-screen="ttt"] .mg-game-guide-button svg {
    width: 20px;
    height: 20px;
    fill: none;
    stroke: currentColor;
    stroke-width: 2;
    stroke-linecap: round;
    stroke-linejoin: round;
}

#mg-screen-ttt .mg-ttt-tab-btn {
    border-bottom-color: transparent;
    box-shadow: none;
}

#mg-screen-ttt .mg-ttt-tab-btn.active,
#minigame-wrapper .mg-game-lobby-topbar[data-mg-docked-screen="ttt"] .mg-ttt-tab-btn.active {
    border-color: rgba(255, 79, 139, 0.44);
    color: #ffd278;
    box-shadow: 0 10px 24px rgba(255, 79, 139, 0.14);
}

#mg-screen-ttt #ttt-tab-play .mg-game-mode-panel {
    transform: translate(var(--ttt-content-shift), -1.6rem);
}

#mg-screen-ttt #ttt-main-view {
    width: min(var(--ttt-visible-inner-width), 100%);
    max-width: var(--ttt-visible-inner-width);
}

#mg-screen-ttt .mg-game-tab-surface {
    transform: translateX(var(--ttt-content-shift));
}

#mg-screen-ttt .mg-game-store-shell,
#mg-screen-ttt .mg-game-rankings-shell,
#mg-screen-ttt #ttt-tab-stats .mg-game-tab-surface,
#mg-screen-ttt .mg-game-guide-shell {
    transform: translateX(var(--ttt-content-shift));
    margin-left: auto;
    margin-right: auto;
}

#mg-screen-ttt .mg-game-store-shell {
    width: min(1180px, calc(100% - 2rem));
    padding-top: clamp(3rem, 6vh, 5rem);
}

#mg-screen-ttt #ttt-tab-play .mg-game-lobby-main {
    padding-left: 1rem;
    padding-right: 1rem;
}

#mg-screen-ttt #ttt-tab-play .mg-game-mode-buttons,
#mg-screen-ttt #ttt-tab-play .mg-game-autopilot-row {
    width: min(880px, 100%);
}

#mg-screen-ttt .mg-game-rankings-shell {
    min-height: calc(var(--vh-fixed, 100vh) - 9rem);
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    align-items: center;
    padding-top: clamp(3rem, 6vh, 5rem);
}

#mg-screen-ttt .mg-game-rankings-title {
    line-height: 1.08;
    margin-bottom: clamp(2.2rem, 4vh, 3.4rem);
}

#mg-screen-ttt .mg-game-podium {
    margin-top: 0;
    margin-bottom: 2.4rem;
}

#mg-screen-ttt .mg-game-podium-card {
    align-content: center;
    gap: 0.45rem;
}

#mg-screen-ttt .mg-game-podium-card strong {
    color: #f8fafc;
    font-size: 1rem;
}

#mg-screen-ttt .mg-game-podium-card small {
    display: block;
    line-height: 1.35;
    color: rgba(248, 250, 252, 0.72);
}

#mg-screen-ttt .mg-game-podium-card .mg-game-podium-meta {
    color: #ffd278;
    font-weight: 900;
}

#mg-screen-ttt .mg-game-rank-list {
    max-height: 330px;
    overflow-y: auto;
    padding-right: 0.35rem;
    scrollbar-width: thin;
    scrollbar-color: rgba(255, 210, 120, 0.42) rgba(255, 255, 255, 0.04);
}

#mg-screen-ttt .mg-game-rank-list::-webkit-scrollbar {
    width: 8px;
}

#mg-screen-ttt .mg-game-rank-list::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.04);
    border-radius: 999px;
}

#mg-screen-ttt .mg-game-rank-list::-webkit-scrollbar-thumb {
    background: rgba(255, 210, 120, 0.42);
    border-radius: 999px;
}

#mg-screen-ttt .mg-game-rank-list .mg-leaderboard-table {
    margin-top: 0;
}

#mg-screen-ttt .mg-game-rank-list .mg-leaderboard-table thead th {
    position: sticky;
    top: 0;
    z-index: 2;
    background: #0f0d15;
    padding-top: 0.85rem;
    padding-bottom: 0.85rem;
}

#mg-screen-ttt .mg-game-guide-shell {
    width: min(1180px, calc(100% - 2rem));
    min-height: calc(var(--vh-fixed, 100vh) - 11rem);
    gap: clamp(2rem, 4vw, 4.5rem);
    padding-top: clamp(3rem, 6vh, 5rem);
}

#mg-screen-ttt #ttt-tab-stats .mg-game-tab-surface {
    width: min(1180px, calc(100% - 2rem));
    padding-top: clamp(3rem, 6vh, 5rem);
}

#mg-screen-ttt .mg-game-guide-step {
    width: 100%;
    border: 0;
    background: transparent;
    text-align: left;
    cursor: pointer;
    font: inherit;
    padding: 0;
}

.mg-ttt-shop-modal {
    position: fixed;
    inset: 0;
    z-index: 100006;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 1.25rem;
    background: rgba(0, 0, 0, 0.74);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
}

.mg-ttt-shop-modal.active {
    display: flex;
}

.mg-ttt-shop-card {
    width: min(94vw, 450px);
    max-height: min(86vh, 640px);
    overflow: hidden;
    border: 1px solid rgba(255, 210, 120, 0.24);
    border-radius: 16px;
    background:
        repeating-linear-gradient(90deg, rgba(255, 255, 255, 0.014) 0, rgba(255, 255, 255, 0.014) 1px, transparent 1px, transparent 58px),
        linear-gradient(145deg, rgba(17, 15, 22, 0.98), rgba(8, 7, 12, 0.98));
    padding: 1.45rem;
    box-shadow: 0 32px 90px rgba(0, 0, 0, 0.58);
    animation: tttShopModalPop 0.2s ease both;
}

@keyframes tttShopModalPop {
    from { opacity: 0; transform: translateY(12px) scale(0.98); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}

.mg-ttt-shop-modal-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 1rem;
}

.mg-ttt-shop-heading {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.mg-ttt-shop-mark,
.mg-ttt-shop-preview-icon {
    display: grid;
    place-items: center;
    border: 1px solid rgba(255, 176, 0, 0.28);
    background: rgba(255, 176, 0, 0.13);
    color: #ffd278;
}

.mg-ttt-shop-mark {
    width: 48px;
    height: 48px;
    border-radius: 12px;
}

.mg-ttt-shop-preview-icon {
    width: 96px;
    height: 96px;
    margin: 0 auto 1rem;
    border-radius: 15px;
}

.mg-ttt-shop-preview-icon svg,
.mg-ttt-shop-mark svg,
.mg-ttt-shop-close svg {
    width: 38px;
    height: 38px;
    fill: none;
    stroke: currentColor;
    stroke-width: 1.8;
    stroke-linecap: round;
    stroke-linejoin: round;
}

.mg-ttt-shop-close {
    width: 32px;
    height: 32px;
    border-radius: 10px;
    border: 1px solid rgba(255, 210, 120, 0.2);
    background: rgba(255, 255, 255, 0.06);
    color: #f8fafc;
    cursor: pointer;
    display: grid;
    place-items: center;
}

.mg-ttt-shop-close svg {
    width: 20px;
    height: 20px;
    stroke-width: 2.2;
}

.mg-ttt-shop-title {
    margin: 0;
    color: #f8fafc;
    font-size: 1rem;
    font-weight: 950;
}

.mg-ttt-shop-subtitle,
.mg-ttt-shop-desc {
    color: rgba(248, 250, 252, 0.62);
}

.mg-ttt-shop-body {
    text-align: center;
}

.mg-ttt-shop-item-name {
    margin: 0 0 0.6rem;
    color: #f8fafc;
    font-size: 1.18rem;
}

.mg-ttt-shop-desc {
    margin: 0 auto;
    line-height: 1.55;
}

.mg-ttt-shop-status {
    display: inline-flex;
    margin: 1rem auto 0;
    padding: 0.45rem 0.75rem;
    border-radius: 999px;
    border: 1px solid rgba(142, 246, 189, 0.24);
    background: rgba(142, 246, 189, 0.1);
    color: #8ef6bd;
    font-size: 0.8rem;
    font-weight: 850;
}

.mg-ttt-shop-price {
    width: 100%;
    margin-top: 1rem;
    border: 1px solid rgba(255, 176, 0, 0.32);
    border-radius: 12px;
    background: rgba(255, 176, 0, 0.12);
    padding: 1rem;
    text-align: center;
    color: #ff4f8b;
    font-size: 1.35rem;
    font-weight: 950;
}

.mg-ttt-shop-actions {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 0.75rem;
    margin-top: 1rem;
}

.mg-ttt-shop-actions button {
    min-height: 44px;
}

#mg-screen-ttt .mg-game-guide-step:hover .mg-game-guide-num,
#mg-screen-ttt .mg-game-guide-step:focus-visible .mg-game-guide-num {
    border-color: rgba(255, 210, 120, 0.48);
    box-shadow: 0 0 20px rgba(255, 210, 120, 0.14);
}

#mg-screen-ttt .mg-game-guide-icon svg {
    width: 54%;
    height: 54%;
}

@media (max-width: 980px) {
    #mg-screen-ttt .mg-game-lobby-topbar,
    #minigame-wrapper .mg-game-lobby-topbar[data-mg-docked-screen="ttt"] {
        display: grid;
        grid-template-columns: 1fr;
        width: min(100%, calc(100% - 1rem));
        padding-right: 1rem;
    }

    #mg-screen-ttt .mg-game-lobby-tabs,
    #minigame-wrapper .mg-game-lobby-topbar[data-mg-docked-screen="ttt"] .mg-game-lobby-tabs {
        width: 100%;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        position: static;
        transform: none;
    }

    #mg-screen-ttt .mg-game-lobby-actions,
    #minigame-wrapper .mg-game-lobby-topbar[data-mg-docked-screen="ttt"] .mg-game-lobby-actions {
        width: 100%;
        justify-content: center;
        flex-wrap: wrap;
        position: static;
        transform: none;
    }

    #mg-screen-ttt #ttt-tab-play .mg-game-mode-panel {
        transform: none;
    }

    #mg-screen-ttt .mg-game-store-shell,
    #mg-screen-ttt .mg-game-rankings-shell,
    #mg-screen-ttt #ttt-tab-stats .mg-game-tab-surface,
    #mg-screen-ttt .mg-game-guide-shell {
        transform: none;
    }
}
</style>

<div id="mg-screen-ttt" class="mg-screen">
    <!-- Matchmaking Overlay -->
    <div id="mg-ttt-matchmaking" class="mg-ttt-overlay" aria-hidden="true">
        <div class="mg-ttt-versus-card">
            <p class="mg-ttt-kicker">Mencari Penantang</p>
            <h3>Rival Arena Sedang Disiapkan</h3>
            <div class="mg-ttt-versus-row">
                <div class="mg-ttt-fighter-card is-player">
                    <div class="mg-ttt-avatar-ring">
                        <img src="<?php echo e(asset('img/' . Auth::user()->avatarPath())); ?>" alt="Avatar pemain">
                    </div>
                    <strong><?php echo e(Auth::user()->name); ?></strong>
                    <span>Pemain</span>
                </div>
                <div class="mg-ttt-vs-badge">VS</div>
                <div id="mg-ttt-opponent-card" class="mg-ttt-fighter-card is-rival">
                    <div id="mg-ttt-opponent-avatar" class="mg-ttt-rival-orb">?</div>
                    <strong id="mg-ttt-opponent-name">Mencari...</strong>
                    <span>Penantang</span>
                </div>
            </div>
            <div class="mg-ttt-scan-track"><div id="mg-ttt-matchmaking-bar" class="mg-ttt-scan-bar"></div></div>
        </div>
    </div>

    <!-- Rival spectator overlay -->
    <div id="mg-ttt-spectator" class="mg-ttt-overlay" aria-hidden="true">
        <div class="mg-ttt-spectator-card">
            <p class="mg-ttt-kicker">Siaran Arena</p>
            <h3 id="mg-ttt-spectator-sub">Penantang A vs Penantang B</h3>
            <div id="mg-ttt-spec-status" class="mg-ttt-status">Menginisialisasi...</div>
            <div class="mg-ttt-board" id="mg-ttt-spec-board" aria-label="Papan pantau turnamen"></div>
            <button class="mg-btn mg-btn-secondary mg-ttt-danger-outline" type="button" data-mg-game="ttt" data-mg-action="confirm-exit-spectator">Keluar Pantau</button>
        </div>
    </div>

    <div class="mg-game-lobby-topbar mg-ttt-lobby-topbar">
        <div class="mg-game-lobby-brand">
            <div class="mg-game-lobby-logo">
                <img src="<?php echo e(asset('img/Milky Garage Assets/Logo Area.webp')); ?>" alt="Milky Garage">
            </div>
            <div>
                <h2 class="mg-game-lobby-title">Tic Tac Toe Arena</h2>
                <p class="mg-game-lobby-subtitle">Game Idea by Aefzetaa</p>
            </div>
        </div>
        <div class="mg-game-lobby-tabs">
            <button class="mg-game-lobby-tab mg-ttt-tab-btn active" type="button" data-mg-game="ttt" data-mg-action="switch-tab" data-tab="play">Play</button>
            <button class="mg-game-lobby-tab mg-ttt-tab-btn" type="button" data-mg-game="ttt" data-mg-action="switch-tab" data-tab="shop">Store</button>
            <button class="mg-game-lobby-tab mg-ttt-tab-btn" type="button" data-mg-game="ttt" data-mg-action="switch-tab" data-tab="leaderboard">Leaderboard</button>
            <button class="mg-game-lobby-tab mg-ttt-tab-btn" type="button" data-mg-game="ttt" data-mg-action="switch-tab" data-tab="stats">Profile</button>
        </div>
        <div class="mg-game-lobby-actions">
            <button class="mg-game-lobby-tab mg-ttt-tab-btn mg-game-guide-button" type="button" data-mg-game="ttt" data-mg-action="switch-tab" data-tab="guide" aria-label="Buka guide" title="Guide">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M4.5 5.5A2.5 2.5 0 0 1 7 3h13v17H7a2.5 2.5 0 0 0-2.5 2.5V5.5Z"/>
                    <path d="M4.5 5.5v17M8 7h8M8 11h7"/>
                </svg>
            </button>
            <div class="mg-game-lobby-balance">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M4 7.5h16a1.5 1.5 0 0 1 1.5 1.5v8.5A1.5 1.5 0 0 1 20 19H4a2 2 0 0 1-2-2V6.5A2.5 2.5 0 0 1 4.5 4H18"/>
                    <path d="M16.5 13h4"/>
                    <path d="M4.5 4A2.5 2.5 0 0 0 4 9h16"/>
                </svg>
                <span id="mg-ttt-lobby-balance">Rp <?php echo e(number_format(Auth::user()->balance ?? 0, 0, ',', '.')); ?></span>
            </div>
            <button class="mg-game-lobby-exit" type="button" data-mg-game="ttt" data-mg-action="exit-portal">Kembali ke Portal</button>
        </div>
    </div>

    <!-- Main Navigation area (hidden when playing) -->
    <div id="ttt-main-view">
        <div id="ttt-tab-play" class="mg-tab-content active">
            <section class="mg-game-lobby-frame">
                <div class="mg-game-lobby-main">
                    <div class="mg-game-mode-panel">
                        <h1 class="mg-game-mode-title">Which mode do you want to play?</h1>
                        <div class="mg-game-mode-buttons">
                            <button class="mg-game-mode-btn" type="button" data-mg-game="ttt" data-mg-action="select-lobby-mode" data-mode="solo_duo">Solo / Duo</button>
                            <button class="mg-game-mode-btn" type="button" data-mg-game="ttt" data-mg-action="select-lobby-mode" data-mode="rank" data-start="1">Ranked</button>
                            <button class="mg-game-mode-btn" type="button" data-mg-game="ttt" data-mg-action="select-lobby-mode" data-mode="tournament" data-start="1">Tournament</button>
                        </div>
                        <div id="mg-ttt-solo-duo-panel" class="mg-game-solo-duo-panel">
                            <button class="mg-game-submode-btn" type="button" data-mg-game="ttt" data-mg-action="select-lobby-mode" data-mode="have_fun" data-start="1">Solo (vs AI)</button>
                            <button class="mg-game-submode-btn" type="button" data-mg-game="ttt" data-mg-action="select-lobby-mode" data-mode="duo" data-start="1">Duo (1 Layar)</button>
                        </div>
                        <select id="mg-ttt-mode" style="display:none;">
                            <option value="have_fun">have_fun</option>
                            <option value="rank">rank</option>
                            <option value="duo">duo</option>
                            <option value="tournament">tournament</option>
                        </select>
                        <select id="mg-ttt-diff" style="display:none;">
                            <option value="easy">easy</option>
                            <option value="pro">pro</option>
                            <option value="world_class">world_class</option>
                        </select>
                        <label class="mg-game-autopilot-row" for="mg-ttt-auto-grinding">
                            <span>Auto Pilot</span>
                            <input type="checkbox" id="mg-ttt-auto-grinding">
                            <i class="mg-game-switch" aria-hidden="true"></i>
                        </label>
                    </div>
                </div>
                <footer class="mg-game-lobby-footer">
                    <div><strong>Tic-Tac-Toe Arena</strong><br>&copy; 2024 Zero Infinity Arcade Portal. All rights reserved.</div>
                    <div class="mg-game-lobby-links"><span>Privacy Policy</span><span>Terms of Service</span><span>Support</span></div>
                </footer>
            </section>
        </div>



        <div id="ttt-tab-shop" class="mg-tab-content">
            <section class="mg-game-tab-surface mg-game-store-shell">
                <h2 class="mg-game-section-title">Autopilot</h2>
                <div class="mg-game-store-grid is-auto">
                    <article class="mg-game-store-card">
                        <button class="mg-game-store-hit" type="button" data-mg-action="buy-shop-item" data-item-key="pro_50" data-item-type="autopilot" data-price="250000">
                            <span class="mg-game-store-icon">
                                <svg viewBox="0 0 24 24">
                                    <path d="M8 3h8l2 3v12l-2 3H8l-2-3V6l2-3Z"/>
                                    <path d="M9 8h6M9 12h6M9 16h6M4 8h2M4 12h2M4 16h2M18 8h2M18 12h2M18 16h2"/>
                                </svg>
                            </span>
                            <span class="mg-game-store-name">Pro (+50)</span>
                            <span class="mg-game-store-price">Rp 250.000</span>
                        </button>
                    </article>
                    <article class="mg-game-store-card">
                        <button class="mg-game-store-hit" type="button" data-mg-action="buy-shop-item" data-item-key="pro_200" data-item-type="autopilot" data-price="950000">
                            <span class="mg-game-store-icon">
                                <svg viewBox="0 0 24 24">
                                    <path d="M8 3h8l2 3v12l-2 3H8l-2-3V6l2-3Z"/>
                                    <path d="M9 8h6M9 12h6M9 16h6M4 8h2M4 12h2M4 16h2M18 8h2M18 12h2M18 16h2"/>
                                </svg>
                            </span>
                            <span class="mg-game-store-name">Pro (+200)</span>
                            <span class="mg-game-store-price">Rp 950.000</span>
                        </button>
                    </article>
                    <article class="mg-game-store-card">
                        <button class="mg-game-store-hit" type="button" data-mg-action="buy-shop-item" data-item-key="world_class_500" data-item-type="autopilot" data-price="2350000">
                            <span class="mg-game-store-icon">
                                <svg viewBox="0 0 24 24">
                                    <path d="M8 3h8l2 3v12l-2 3H8l-2-3V6l2-3Z"/>
                                    <path d="M9 8h6M9 12h6M9 16h6M4 8h2M4 12h2M4 16h2M18 8h2M18 12h2M18 16h2"/>
                                </svg>
                            </span>
                            <span class="mg-game-store-name">World Class (+500)</span>
                            <span class="mg-game-store-price">Rp 2.350.000</span>
                        </button>
                    </article>
                    <article class="mg-game-store-card">
                        <button class="mg-game-store-hit" type="button" data-mg-action="buy-shop-item" data-item-key="world_class_200" data-item-type="autopilot" data-price="950000">
                            <span class="mg-game-store-icon">
                                <svg viewBox="0 0 24 24">
                                    <path d="M8 3h8l2 3v12l-2 3H8l-2-3V6l2-3Z"/>
                                    <path d="M9 8h6M9 12h6M9 16h6M4 8h2M4 12h2M4 16h2M18 8h2M18 12h2M18 16h2"/>
                                </svg>
                            </span>
                            <span class="mg-game-store-name">World Class (+200)</span>
                            <span class="mg-game-store-price">Rp 950.000</span>
                        </button>
                    </article>
                </div>
                <h2 class="mg-game-section-title">Cosmetics</h2>
                <div class="mg-game-store-grid is-cosmetic">
                    <article class="mg-game-store-card" id="shop-ttt-ttt_board_neon">
                        <button class="mg-game-store-hit" type="button" data-mg-action="buy-shop-item" data-item-key="ttt_board_neon" data-item-type="board_theme" data-price="25000">
                            <span class="mg-game-store-icon">
                                <svg viewBox="0 0 24 24">
                                    <path d="M4 4h6v6H4zM14 4h6v6h-6zM4 14h6v6H4zM14 14h6v6h-6z"/>
                                </svg>
                            </span>
                            <span class="mg-game-store-name">Neon Glow Board</span>
                            <span class="mg-game-store-price">Rp 25.000</span>
                        </button>
                    </article>
                    <article class="mg-game-store-card" id="shop-ttt-ttt_board_frost">
                        <button class="mg-game-store-hit" type="button" data-mg-action="buy-shop-item" data-item-key="ttt_board_frost" data-item-type="board_theme" data-price="25000">
                            <span class="mg-game-store-icon">
                                <svg viewBox="0 0 24 24">
                                    <path d="M12 2v20M4.2 6.2l15.6 11.6M19.8 6.2L4.2 17.8"/>
                                    <path d="M7 4.5 12 8l5-3.5M7 19.5 12 16l5 3.5"/>
                                </svg>
                            </span>
                            <span class="mg-game-store-name">Frost Crystal Board</span>
                            <span class="mg-game-store-price">Rp 25.000</span>
                        </button>
                    </article>
                    <article class="mg-game-store-card" id="shop-ttt-ttt_token_gold">
                        <button class="mg-game-store-hit" type="button" data-mg-action="buy-shop-item" data-item-key="ttt_token_gold" data-item-type="token_skin" data-price="25000">
                            <span class="mg-game-store-icon">
                                <svg viewBox="0 0 24 24">
                                    <circle cx="9" cy="12" r="5"/>
                                    <path d="M13 8h3.5a4 4 0 0 1 0 8H13"/>
                                    <path d="M9 8v8M6.8 10h4.4"/>
                                </svg>
                            </span>
                            <span class="mg-game-store-name">Gold Tokens</span>
                            <span class="mg-game-store-price">Rp 25.000</span>
                        </button>
                    </article>
                </div>
            </section>
        </div>

        <div id="ttt-tab-leaderboard" class="mg-tab-content">
            <section class="mg-game-tab-surface mg-game-rankings-shell">
                <h1 class="mg-game-rankings-title">Grand Arena Rankings</h1>
                <div class="mg-game-podium">
                    <div class="mg-game-podium-card" id="ttt-podium-2"><span class="mg-game-podium-rank">2</span><strong>Belum ada akun</strong><small>Rank: -</small><small class="mg-game-podium-meta">Bintang: 0 | Reward: Rp 0</small></div>
                    <div class="mg-game-podium-card is-champion" id="ttt-podium-1"><span class="mg-game-podium-rank">1</span><strong>Belum ada akun</strong><small>Rank: -</small><small class="mg-game-podium-meta">Bintang: 0 | Reward: Rp 0</small></div>
                    <div class="mg-game-podium-card" id="ttt-podium-3"><span class="mg-game-podium-rank">3</span><strong>Belum ada akun</strong><small>Rank: -</small><small class="mg-game-podium-meta">Bintang: 0 | Reward: Rp 0</small></div>
                </div>
                <div class="mg-game-rank-list">
                    <table class="mg-leaderboard-table">
                        <thead><tr><th>Top</th><th>Player</th><th>Rank</th><th>Stars</th><th>Total Reward</th></tr></thead>
                        <tbody id="ttt-leaderboard-body"><!-- Injected by JS --></tbody>
                    </table>
                </div>
            </section>
        </div>

        <div id="ttt-tab-stats" class="mg-tab-content">
            <section class="mg-game-tab-surface">
                <div class="mg-game-profile-card">
                    <h2 class="mg-game-section-title">Tic-Tac-Toe Arena Profile</h2>
                    <div class="mg-game-profile-grid">
                        <div class="mg-game-profile-stat"><span>Rank</span><strong id="mg-ttt-rank">Loading...</strong></div>
                        <div class="mg-game-profile-stat"><span>Stars</span><strong id="mg-ttt-stars">0</strong></div>
                        <div class="mg-game-profile-stat"><span>Win / Loss / Draw</span><strong id="mg-ttt-wld">0 / 0 / 0</strong></div>
                        <div class="mg-game-profile-stat"><span>Autopilot</span><strong id="mg-ttt-autopilot">0 / 0 / 0</strong></div>
                    </div>
                </div>
            </section>
        </div>

        <div id="ttt-tab-guide" class="mg-tab-content">
            <section class="mg-game-tab-surface mg-game-guide-shell">
                <div>
                    <h1 class="mg-game-guide-title">Master the Arena</h1>
                    <p class="mg-game-guide-lead" id="mg-ttt-guide-lead">Baca panduan singkat ini sebelum bermain agar Anda lebih siap memilih mode, memakai bantuan, dan mengejar rank dengan langkah yang rapi.</p>
                    <div class="mg-game-guide-steps">
                        <button class="mg-game-guide-step is-active" type="button" data-guide-step="0"><span class="mg-game-guide-num">01</span><div><strong>Getting Started</strong><span>Pilih mode dan siapkan ritme bermain Anda.</span></div></button>
                        <button class="mg-game-guide-step" type="button" data-guide-step="1"><span class="mg-game-guide-num">02</span><div><strong>Choosing a Mode</strong><span>Solo/Duo, Ranked, dan Tournament punya tempo berbeda.</span></div></button>
                        <button class="mg-game-guide-step" type="button" data-guide-step="2"><span class="mg-game-guide-num">03</span><div><strong>Using Support</strong><span>Gunakan autopilot hanya di mode yang mendukung.</span></div></button>
                        <button class="mg-game-guide-step" type="button" data-guide-step="3"><span class="mg-game-guide-num">04</span><div><strong>Climbing the Ranks</strong><span>Menang di mode kompetitif untuk menaikkan reputasi arena.</span></div></button>
                    </div>
                </div>
                <div class="mg-game-guide-card mg-game-guide-visual">
                    <div class="mg-game-guide-icon">
                        <svg viewBox="0 0 100 100" aria-hidden="true">
                            <path d="M36 12v76M64 12v76M12 36h76M12 64h76"/>
                            <path d="M17 17l14 14M31 17 17 31"/>
                            <circle cx="78" cy="78" r="10"/>
                        </svg>
                    </div>
                    <h3 id="mg-ttt-guide-card-title">Control the Grid</h3>
                    <p id="mg-ttt-guide-card-copy">Setiap langkah punya tekanan. Rebut tiga petak sejajar, paksa rival salah baca, lalu naikkan reputasi Anda di arena.</p>
                </div>
            </section>
        </div>
    </div>

    <!-- Arena Area (Hidden initially) -->
    <div id="ttt-arena-view" style="display:none;">
        <div class="mg-ttt-arena-actions">
            <button class="mg-btn mg-btn-secondary" type="button" data-mg-game="ttt" data-mg-action="exit-lobby">Kembali ke Lobby</button>
            <button class="mg-btn mg-ttt-secondary-cta" type="button" data-mg-game="ttt" data-mg-action="toggle-autopilot" id="mg-ttt-arena-autopilot">Aktifkan Autopilot</button>
            <button class="mg-btn mg-ttt-danger-outline" type="button" data-mg-game="ttt" data-mg-action="surrender">Menyerah</button>
        </div>

        <div class="mg-tournament-bracket" id="mg-ttt-bracket"></div>

        <div class="mg-ttt-arena-layout">
            <aside class="mg-ttt-player-card is-home">
                <div class="mg-ttt-avatar-ring">
                    <img src="<?php echo e(asset('img/' . Auth::user()->avatarPath())); ?>" alt="Avatar pemain">
                </div>
                <span class="mg-ttt-player-meta">Pemain</span>
                <strong class="mg-ttt-player-name"><?php echo e(Auth::user()->name); ?></strong>
                <small id="mg-ttt-player-mode">Siap bertanding</small>
            </aside>

            <section class="mg-ttt-arena-container">
                <p class="mg-ttt-kicker">Match Arena</p>
                <div id="mg-ttt-status" class="mg-ttt-status">Siap Bermain?</div>
                <div id="mg-ttt-substatus" class="mg-ttt-substatus">Pilih mode dan mulai pertandingan.</div>
                <div class="mg-ttt-board" id="mg-ttt-board-grid" aria-label="Papan Tic Tac Toe"></div>
                <div id="mg-ttt-result-panel" class="mg-ttt-result-panel" aria-live="polite"></div>
            </section>

            <aside class="mg-ttt-player-card is-rival" id="mg-ttt-arena-rival">
                <div class="mg-ttt-avatar-ring">
                    <div class="mg-ttt-rival-orb">?</div>
                </div>
                <span class="mg-ttt-player-meta">Penantang</span>
                <strong class="mg-ttt-player-name">Penantang Arena</strong>
                <small id="mg-ttt-rival-mode">Membaca pola langkah</small>
            </aside>
        </div>
    </div>

    <!-- Confirmation modal -->
    <div id="mg-ttt-local-modal" class="mg-ttt-local-modal" aria-hidden="true">
        <div class="mg-ttt-local-card" role="dialog" aria-modal="true" aria-labelledby="mg-ttt-modal-title">
            <div class="mg-ttt-modal-head">
                <div class="mg-ttt-mark" aria-hidden="true">
                    <svg class="mg-ttt-svg" viewBox="0 0 24 24">
                        <path d="M12 3l9 16H3L12 3z"/>
                        <path d="M12 9v4"/>
                        <path d="M12 17h.01"/>
                    </svg>
                </div>
                <div>
                    <h3 id="mg-ttt-modal-title" class="mg-ttt-modal-title">Konfirmasi</h3>
                    <p id="mg-ttt-modal-message" class="mg-ttt-modal-message">Lanjutkan aksi ini?</p>
                </div>
            </div>
            <div class="mg-ttt-modal-actions">
                <button id="mg-ttt-modal-cancel" class="mg-btn mg-ttt-muted-btn" type="button">Batal</button>
                <button id="mg-ttt-modal-ok" class="mg-btn mg-ttt-primary-cta" type="button">Lanjutkan</button>
            </div>
        </div>
    </div>

    <div id="mg-ttt-shop-modal" class="mg-ttt-shop-modal" aria-hidden="true">
        <div class="mg-ttt-shop-card" role="dialog" aria-modal="true" aria-labelledby="mg-ttt-shop-title">
            <div class="mg-ttt-shop-modal-head">
                <div class="mg-ttt-shop-heading">
                    <span class="mg-ttt-shop-mark" aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <path d="M12 3.5l2.4 5 5.5.8-4 3.9.95 5.5L12 16.1 7.15 18.7l.95-5.5-4-3.9 5.5-.8L12 3.5Z"/>
                        </svg>
                    </span>
                    <div>
                        <h3 class="mg-ttt-shop-title">Preview Item</h3>
                        <div class="mg-ttt-shop-subtitle">Detail item shop</div>
                    </div>
                </div>
                <button id="mg-ttt-shop-close" class="mg-ttt-shop-close" type="button" aria-label="Tutup modal">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M6 6l12 12M18 6 6 18"/>
                    </svg>
                </button>
            </div>
            <div class="mg-ttt-shop-body">
                <div id="mg-ttt-shop-icon" class="mg-ttt-shop-preview-icon" aria-hidden="true"></div>
                <h2 id="mg-ttt-shop-title" class="mg-ttt-shop-item-name">Item</h2>
                <p id="mg-ttt-shop-desc" class="mg-ttt-shop-desc">Detail item arena.</p>
                <div id="mg-ttt-shop-status" class="mg-ttt-shop-status">Belum Dimiliki</div>
            </div>
            <div id="mg-ttt-shop-price" class="mg-ttt-shop-price">Rp 0</div>
            <div class="mg-ttt-shop-actions">
                <button id="mg-ttt-shop-cancel" type="button" class="mg-btn mg-btn-secondary">Batal</button>
                <button id="mg-ttt-shop-buy" type="button" class="mg-btn mg-btn-primary" style="background:linear-gradient(135deg,#ff4f8b,#ffd278);">Beli Sekarang</button>
            </div>
        </div>
    </div>
</div>

<script>
window.MgTtt = {
    board: Array(9).fill(null),
    isPlayerTurn: false,
    isPlaying: false,
    playerSymbol: 'X',
    botSymbol: 'O',
    leaderboardData: [],
    
    currentMode: 'have_fun', 
    duoState: { p1Wins: 0, p2Wins: 0, target: 2, currentTurn: 'X' },
    tourneyState: { stage: 0, wins: 0, losses: 0, target: 1, active: false, players: [] },
    isAutoGrinding: false,
    isSpectating: false,
    specInterval: null,
    specBoard: Array(9).fill(null),
    shopBridgeBound: false,
    shopPreviewBound: false,
    purchasePreview: null,
    previousBuyShopItem: null,
    guideLead: 'Baca panduan singkat ini sebelum bermain agar Anda lebih siap memilih mode, memakai bantuan, dan mengejar rank dengan langkah yang rapi.',
    guideItems: [
        {
            title: 'Control the Grid',
            copy: 'Setiap langkah punya tekanan. Rebut tiga petak sejajar, paksa rival salah baca, lalu naikkan reputasi Anda di arena.'
        },
        {
            title: 'Pick the Tempo',
            copy: 'Pilih mode sesuai ritme bermain. Mode kompetitif akan menghitung bintang rank dan hasil pertandingan.'
        },
        {
            title: 'Use Support Wisely',
            copy: 'Aktifkan autopilot hanya saat mode mendukung. Duo tetap manual karena dua pemain bermain bergantian di satu layar.'
        },
        {
            title: 'Climb the Board',
            copy: 'Leaderboard menampilkan akun dengan skor tertinggi. Tiga teratas masuk podium, sisanya masuk daftar Top.'
        }
    ],

    async init() {
        this.renderBoard();
        try {
            await this.safeLoadLobby();
        } catch (error) {
            console.error('TTT lobby load failed:', error);
            MgCore.toast('Lobby Tic-Tac-Toe terbuka, tetapi data arena belum siap.');
        }
        window.renderGameShop = () => this.renderShopUI();

        // Redesign lobby setup: set defaults & sync UI
        const modeEl = document.getElementById('mg-ttt-mode');
        if (modeEl) modeEl.value = 'have_fun';
        const diffEl = document.getElementById('mg-ttt-diff');
        if (diffEl) {
            diffEl.value = 'easy';
            diffEl.style.display = 'none'; // fully hide default select
        }
        this.setupGuide();
        this.bindLocalShopBridge();
        this.bindShopPreviewEvents();
        this.syncAutopilotToggleUI();
    },

    bindLocalShopBridge() {
        if (this.shopBridgeBound) return;
        this.shopBridgeBound = true;
        this.previousBuyShopItem = window.buyShopItem;
        window.buyShopItem = async (itemKey, type, price = 0) => {
            if (MgCore.currentScreen === 'ttt') {
                return this.openPurchasePreview(itemKey, type, Number(price || 0));
            }
            if (typeof this.previousBuyShopItem === 'function') {
                return this.previousBuyShopItem(itemKey, type, price);
            }
        };
    },

    bindShopPreviewEvents() {
        if (this.shopPreviewBound) return;
        this.shopPreviewBound = true;
        const overlay = document.getElementById('mg-ttt-shop-modal');
        const closeBtn = document.getElementById('mg-ttt-shop-close');
        const cancelBtn = document.getElementById('mg-ttt-shop-cancel');
        const buyBtn = document.getElementById('mg-ttt-shop-buy');
        const close = () => this.closePurchasePreview();
        closeBtn?.addEventListener('click', close);
        cancelBtn?.addEventListener('click', close);
        buyBtn?.addEventListener('click', () => this.confirmPreviewPurchase());
        overlay?.addEventListener('click', (event) => {
            if (event.target === overlay) this.closePurchasePreview();
        });
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && overlay?.classList.contains('active')) this.closePurchasePreview();
        });
    },

    setupGuide() {
        const steps = document.querySelectorAll('#ttt-tab-guide [data-guide-step]');
        steps.forEach((step) => {
            if (step.dataset.guideBound === '1') return;
            step.dataset.guideBound = '1';
            step.addEventListener('click', () => this.renderGuideStep(Number(step.dataset.guideStep || 0)));
        });
        this.renderGuideStep(0);
    },

    renderGuideStep(index = 0) {
        const item = this.guideItems[index] || this.guideItems[0];
        document.querySelectorAll('#ttt-tab-guide [data-guide-step]').forEach((step) => {
            step.classList.toggle('is-active', Number(step.dataset.guideStep || 0) === index);
        });
        const lead = document.getElementById('mg-ttt-guide-lead');
        const title = document.getElementById('mg-ttt-guide-card-title');
        const copy = document.getElementById('mg-ttt-guide-card-copy');
        if (lead) lead.innerText = this.guideLead;
        if (title) title.innerText = item.title;
        if (copy) copy.innerText = item.copy;
    },

    toggleCasualDrawer() {
        const drawer = document.getElementById('mg-ttt-casual-drawer');
        const casualCard = document.getElementById('ttt-card-casual');
        if (!drawer) return;
        
        // Remove active class from other mode cards
        document.getElementById('ttt-card-ranked').classList.remove('active');
        document.getElementById('ttt-card-tournament').classList.remove('active');

        drawer.classList.toggle('open');
        casualCard.classList.toggle('active');
    },

    setCasualSubmode(submode, event) {
        if (event) event.stopPropagation();
        
        // Update active class in sub-mode buttons
        document.getElementById('btn-submode-solo').classList.remove('active');
        document.getElementById('btn-submode-duo').classList.remove('active');
        
        if (submode === 'have_fun') {
            document.getElementById('btn-submode-solo').classList.add('active');
            document.getElementById('mg-ttt-diff-section').style.display = 'flex';
            document.getElementById('mg-ttt-drawer-helper').innerText = 'Mode Solo: Bermain santai melawan Bot AI. Bebas biaya masuk.';
        } else {
            document.getElementById('btn-submode-duo').classList.add('active');
            document.getElementById('mg-ttt-diff-section').style.display = 'none';
            document.getElementById('mg-ttt-drawer-helper').innerText = 'Mode Duo: Tantang teman Anda bermain di perangkat yang sama secara bergantian.';
            
            // Auto pilot cannot be active in duo mode
            const autoBox = document.getElementById('mg-ttt-auto-grinding');
            if (autoBox) {
                autoBox.checked = false;
                this.syncAutopilotToggleUI();
            }
        }
        
        document.getElementById('mg-ttt-mode').value = submode;
    },

    setCasualDifficulty(diff, event) {
        if (event) event.stopPropagation();
        
        document.getElementById('btn-diff-easy').classList.remove('active');
        document.getElementById('btn-diff-pro').classList.remove('active');
        document.getElementById('btn-diff-world_class').classList.remove('active');
        
        document.getElementById('btn-diff-' + diff).classList.add('active');
        document.getElementById('mg-ttt-diff').value = diff;
    },

    launchCasualGame() {
        const mode = document.getElementById('mg-ttt-mode').value;
        if (mode !== 'have_fun' && mode !== 'duo') {
            document.getElementById('mg-ttt-mode').value = 'have_fun';
        }
        this.startGameRouter();
    },

    startDirectMode(mode) {
        // Close casual drawer first
        const drawer = document.getElementById('mg-ttt-casual-drawer');
        if (drawer) drawer.classList.remove('open');
        document.getElementById('ttt-card-casual').classList.remove('active');

        // Highlight selected card
        document.getElementById('ttt-card-ranked').classList.remove('active');
        document.getElementById('ttt-card-tournament').classList.remove('active');
        
        const selectedCard = document.getElementById('ttt-card-' + mode);
        if (selectedCard) selectedCard.classList.add('active');

        document.getElementById('mg-ttt-mode').value = mode;
        this.startGameRouter();
    },

    toggleAutopilotLobbyToggle() {
        const autoBox = document.getElementById('mg-ttt-auto-grinding');
        if (!autoBox) return;
        
        const mode = document.getElementById('mg-ttt-mode').value;
        if (mode === 'duo') {
            MgCore.toast('Autopilot tidak didukung untuk mode Duo.');
            return;
        }

        autoBox.checked = !autoBox.checked;
        this.syncAutopilotToggleUI();
    },

    syncAutopilotToggleUI() {
        const autoBox = document.getElementById('mg-ttt-auto-grinding');
        const label = document.getElementById('mg-ttt-autopilot-status-lbl');
        const sw = document.getElementById('mg-ttt-autopilot-switch-visual');
        if (!autoBox) return;

        if (autoBox.checked) {
            if (label) label.innerText = 'AUTOPILOT: AKTIF';
            if (sw) sw.style.background = 'linear-gradient(135deg, #facc15, #f59e0b)';
        } else {
            if (label) label.innerText = 'AUTOPILOT: MATI';
            if (sw) sw.style.background = 'rgba(255, 255, 255, 0.08)';
        }
    },

    exitArenaAndGoHome() {
        this.isAutoGrinding = false;
        this.exitArena();
        MgCore.navigate('portal');
    },

    switchTab(tabId, btnElement) {
        document.querySelectorAll('#ttt-main-view .mg-tab-content').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('#mg-screen-ttt .mg-ttt-tab-btn, #minigame-wrapper .mg-game-lobby-topbar[data-mg-docked-screen="ttt"] .mg-ttt-tab-btn').forEach(el => el.classList.remove('active'));
        document.getElementById('ttt-tab-' + tabId).classList.add('active');
        if (btnElement) btnElement.classList.add('active');
        if (tabId === 'guide') this.renderGuideStep(0);
    },

    renderShopUI() {
        const inv = window.MgUserInventory || [];
        ['ttt_board_neon', 'ttt_board_frost', 'ttt_token_gold'].forEach(itemKey => {
            if (inv.includes(itemKey)) {
                const el = document.getElementById('shop-ttt-' + itemKey);
                const button = el?.querySelector('button');
                if (button) button.outerHTML = `<div class="mg-shop-item-owned">Dimiliki</div>`;
            }
        });
    },

    async loadLobby() {
        const res = await MgCore.apiGet('/minigame/lobby-data');
        if (res.status !== 'success') {
            MgCore.toast(res.message || 'Data lobby belum siap. Layar tetap bisa dipakai.');
            return;
        }

        if (res.status === 'success') {
            this.updateStats(res.player);
            this.leaderboardData = res.leaderboard || [];
            this.renderLeaderboard();
        }
    },

    async safeLoadLobby() {
        try {
            await this.loadLobby();
        } catch (error) {
            console.error('TTT lobby refresh failed:', error);
            MgCore.toast('Data arena belum sempat diperbarui, tetapi layar tetap bisa dipakai.');
        }
    },

    updateStats(player) {
        document.getElementById('mg-ttt-rank').innerText = player.profile.rank.replace(/_/g, ' ');
        document.getElementById('mg-ttt-stars').innerText = player.profile.stars;
        document.getElementById('mg-ttt-wld').innerText = `${player.profile.win_count} / ${player.profile.loss_count} / ${player.profile.draw_count}`;
        document.getElementById('mg-ttt-autopilot').innerText = `${player.autopilot.free} / ${player.autopilot.pro} / ${player.autopilot.world_class}`;
        MgCore.updateBalance(player.balance);
        const lobbyBalance = document.getElementById('mg-ttt-lobby-balance');
        if (lobbyBalance) lobbyBalance.innerText = 'Rp ' + Number(player.balance || 0).toLocaleString('id-ID');
    },

    formatRank(value) {
        return String(value || '-').replace(/_/g, ' ');
    },

    escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, (char) => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        }[char]));
    },

    renderPodiumCard(rankNumber, entry) {
        const card = document.getElementById('ttt-podium-' + rankNumber);
        if (!card) return;
        if (!entry) {
            card.innerHTML = `<span class="mg-game-podium-rank">${rankNumber}</span><strong>Belum ada akun</strong><small>Rank: -</small><small class="mg-game-podium-meta">Bintang: 0 | Reward: Rp 0</small>`;
            return;
        }
        const username = this.escapeHtml(entry.username || entry.name || 'Player');
        const rank = this.escapeHtml(this.formatRank(entry.rank));
        const stars = Number(entry.stars || 0).toLocaleString('id-ID');
        const reward = Number(entry.reward_total || 0).toLocaleString('id-ID');
        card.innerHTML = `<span class="mg-game-podium-rank">${rankNumber}</span><strong>${username}</strong><small>Rank: ${rank}</small><small class="mg-game-podium-meta">Bintang: ${stars} | Reward: Rp ${reward}</small>`;
    },

    renderLeaderboard() {
        const tbody = document.getElementById('ttt-leaderboard-body');
        if (!tbody) return;
        tbody.innerHTML = '';
        const sorted = this.leaderboardData || [];
        this.renderPodiumCard(1, sorted[0]);
        this.renderPodiumCard(2, sorted[1]);
        this.renderPodiumCard(3, sorted[2]);
        const visibleRows = sorted.slice(3, 13);
        if (!visibleRows.length) {
            tbody.innerHTML = '<tr><td colspan="5">Belum ada akun lain di daftar Top.</td></tr>';
            return;
        }
        visibleRows.forEach((entry, idx) => {
            const top = idx + 4;
            const style = 'color: var(--mg-primary); font-weight:bold;';
            tbody.innerHTML += `
                <tr style="${style}">
                    <td>${top}</td>
                    <td>${this.escapeHtml(entry.username || entry.name || 'Player')}</td>
                    <td>${this.escapeHtml(this.formatRank(entry.rank))}</td>
                    <td>${Number(entry.stars || 0).toLocaleString('id-ID')}</td>
                    <td>Rp ${Number(entry.reward_total || 0).toLocaleString('id-ID')}</td>
                </tr>
            `;
        });
    },

    shopItemMeta(itemKey, itemType, price = 0) {
        const labels = {
            pro_50: 'Pro (+50)',
            pro_200: 'Pro (+200)',
            world_class_500: 'World Class (+500)',
            world_class_200: 'World Class (+200)',
            ttt_board_neon: 'Neon Glow Board',
            ttt_board_frost: 'Frost Crystal Board',
            ttt_token_gold: 'Gold Tokens'
        };
        const descriptions = {
            pro_50: 'Tambahkan 50 charge autopilot Pro untuk sesi Tic-Tac-Toe yang lebih panjang.',
            pro_200: 'Tambahkan 200 charge autopilot Pro untuk rangkaian match yang lebih padat.',
            world_class_500: 'Tambahkan 500 charge World Class untuk permainan otomatis level tertinggi.',
            world_class_200: 'Tambahkan 200 charge World Class untuk sesi kompetitif panjang.',
            ttt_board_neon: 'Papan Tic-Tac-Toe dengan aksen neon yang lebih tegas.',
            ttt_board_frost: 'Papan kristal dingin untuk tampilan arena yang lebih bersih.',
            ttt_token_gold: 'Token X dan O beraksen emas untuk gaya bermain premium.'
        };
        return {
            itemKey,
            itemType,
            price: Number(price || 0),
            label: labels[itemKey] || itemKey.replace(/_/g, ' '),
            description: descriptions[itemKey] || 'Item arena untuk memperkuat pengalaman bermain Tic-Tac-Toe.'
        };
    },

    shopIconSvg(itemKey, itemType) {
        if (itemType === 'autopilot') {
            return `
                <svg viewBox="0 0 24 24">
                    <path d="M8 3h8l2 3v12l-2 3H8l-2-3V6l2-3Z"/>
                    <path d="M9 8h6M9 12h6M9 16h6M4 8h2M4 12h2M4 16h2M18 8h2M18 12h2M18 16h2"/>
                </svg>
            `;
        }
        if (itemKey === 'ttt_board_neon') {
            return `
                <svg viewBox="0 0 24 24">
                    <path d="M4 4h6v6H4zM14 4h6v6h-6zM4 14h6v6H4zM14 14h6v6h-6z"/>
                </svg>
            `;
        }
        if (itemKey === 'ttt_board_frost') {
            return `
                <svg viewBox="0 0 24 24">
                    <path d="M12 2v20M4.2 6.2l15.6 11.6M19.8 6.2L4.2 17.8"/>
                    <path d="M7 4.5 12 8l5-3.5M7 19.5 12 16l5 3.5"/>
                </svg>
            `;
        }
        return `
            <svg viewBox="0 0 24 24">
                <circle cx="9" cy="12" r="5"/>
                <path d="M13 8h3.5a4 4 0 0 1 0 8H13"/>
                <path d="M9 8v8M6.8 10h4.4"/>
            </svg>
        `;
    },

    openPurchasePreview(itemKey, itemType, price = 0) {
        const item = this.shopItemMeta(itemKey, itemType, price);
        this.purchasePreview = item;
        const overlay = document.getElementById('mg-ttt-shop-modal');
        const icon = document.getElementById('mg-ttt-shop-icon');
        const title = document.getElementById('mg-ttt-shop-title');
        const desc = document.getElementById('mg-ttt-shop-desc');
        const status = document.getElementById('mg-ttt-shop-status');
        const priceEl = document.getElementById('mg-ttt-shop-price');
        if (!overlay || !icon || !title || !desc || !status || !priceEl) {
            return this.purchaseShopItem(item.itemKey, item.itemType, item.price);
        }

        icon.innerHTML = this.shopIconSvg(item.itemKey, item.itemType);
        title.innerText = item.label;
        desc.innerText = item.description;
        status.innerText = item.itemType === 'autopilot' ? 'Dapat dibeli berulang' : 'Belum Dimiliki';
        priceEl.innerText = 'Rp ' + Number(item.price || 0).toLocaleString('id-ID');
        overlay.classList.add('active');
        overlay.setAttribute('aria-hidden', 'false');
        setTimeout(() => document.getElementById('mg-ttt-shop-buy')?.focus(), 30);
    },

    closePurchasePreview() {
        const overlay = document.getElementById('mg-ttt-shop-modal');
        overlay?.classList.remove('active');
        overlay?.setAttribute('aria-hidden', 'true');
    },

    async confirmPreviewPurchase() {
        if (!this.purchasePreview) return;
        const item = this.purchasePreview;
        await this.purchaseShopItem(item.itemKey, item.itemType, item.price);
    },

    async purchaseShopItem(itemKey, itemType, price = 0) {
        const item = this.shopItemMeta(itemKey, itemType, price);

        if (Number(MgCore.balance || 0) < Number(price || 0)) {
            await window.showAlert('Saldo Tidak Cukup', 'Saldo ZeroPay Anda tidak cukup untuk membeli item ini.');
            return;
        }

        const res = await MgCore.apiCall('/minigame/purchase-item', {
            item_type: itemType,
            item_key: itemKey,
            price: Number(price || 0)
        });

        if (res.status !== 'success') {
            await window.showAlert('Pembelian Gagal', res.message || 'Item belum bisa dibeli.');
            return;
        }

        this.closePurchasePreview();
        this.purchasePreview = null;
        MgCore.updateBalance(res.new_balance);
        const lobbyBalance = document.getElementById('mg-ttt-lobby-balance');
        if (lobbyBalance) lobbyBalance.innerText = 'Rp ' + Number(res.new_balance || 0).toLocaleString('id-ID');
        if (res.autopilot) {
            const autopilot = document.getElementById('mg-ttt-autopilot');
            if (autopilot) autopilot.innerText = `${res.autopilot.free} / ${res.autopilot.pro} / ${res.autopilot.world_class}`;
        }
        if (itemType !== 'autopilot') {
            const card = document.getElementById('shop-ttt-' + itemKey);
            const button = card?.querySelector('button');
            if (button) button.outerHTML = '<div class="mg-shop-item-owned">Dimiliki</div>';
        }
        await window.showAlert('Pembelian Berhasil', res.message || 'Item berhasil dibeli.');
    },

    renderBoard() {
        const grid = document.getElementById('mg-ttt-board-grid');
        if (!grid) return;
        grid.innerHTML = '';
        const winningLine = this.getWinningLine(this.board, 'X') || this.getWinningLine(this.board, 'O') || [];
        for (let i = 0; i < 9; i++) {
            const cell = document.createElement('button');
            cell.type = 'button';
            cell.className = 'mg-ttt-cell';
            cell.dataset.mgGame = 'ttt';
            cell.dataset.mgAction = 'make-move';
            cell.dataset.move = String(i);
            cell.setAttribute('aria-label', `Petak ${i + 1}`);
            if (this.board[i]) {
                cell.innerText = this.board[i];
                cell.classList.add(this.board[i].toLowerCase());
            }
            if (winningLine.includes(i)) {
                cell.classList.add('win');
            }
            grid.appendChild(cell);
        }
        this.updateTurnHighlight();
    },

    updateTurnHighlight() {
        const homeCard = document.querySelector('.mg-ttt-player-card.is-home');
        const rivalCard = document.getElementById('mg-ttt-arena-rival');
        if (!homeCard || !rivalCard) return;

        homeCard.classList.remove('active-turn');
        rivalCard.classList.remove('active-turn');

        if (!this.isPlaying) return;

        if (this.currentMode === 'duo') {
            if (this.duoState.currentTurn === 'X') {
                homeCard.classList.add('active-turn');
            } else {
                rivalCard.classList.add('active-turn');
            }
        } else {
            if (this.isPlayerTurn) {
                homeCard.classList.add('active-turn');
            } else {
                rivalCard.classList.add('active-turn');
            }
        }
    },

    pickRegisteredOpponent() {
        const currentUserId = <?php echo e(Auth::id() ?? 0); ?>;
        const rivals = (this.leaderboardData || [])
            .filter(entry => entry && Number(entry.user_id || 0) !== Number(currentUserId));
        const rival = rivals.length ? rivals[Math.floor(Math.random() * rivals.length)] : null;
        this.currentOpponentUserId = rival ? Number(rival.user_id || 0) : null;
        this.currentOpponentName = rival ? (rival.username || rival.name || 'Penantang Arena') : 'Penantang Arena';
        this.currentOpponentAvatar = rival ? (rival.avatar || <?php echo json_encode(\App\Models\User::DEFAULT_AVATAR, 15, 512) ?>) : <?php echo json_encode(\App\Models\User::DEFAULT_AVATAR, 15, 512) ?>;
        return this.currentOpponentName;
    },

    updateRivalCard(opponentName = 'Penantang Arena') {
        const card = document.getElementById('mg-ttt-arena-rival');
        if (!card) return;
        const nameEl = card.querySelector('strong');
        if (nameEl) nameEl.innerText = opponentName;

        const avatarEl = card.querySelector('.mg-ttt-avatar-ring');
        if (avatarEl) {
            const mode = this.currentMode;
            const isBot = (mode === 'have_fun' && mode !== 'duo') || mode === 'tournament';
            
            if (isBot) {
                avatarEl.innerHTML = `
                    <svg viewBox="0 0 100 100" class="bot-avatar-svg" style="width:50px; height:50px; fill:none; stroke:var(--ttt-amber); stroke-width:4; stroke-linecap:round; stroke-linejoin:round;">
                        <rect x="25" y="25" width="50" height="50" rx="10" />
                        <circle cx="40" cy="45" r="4" fill="var(--ttt-amber)" />
                        <circle cx="60" cy="45" r="4" fill="var(--ttt-amber)" />
                        <path d="M40 60h20" />
                        <path d="M50 15v10" />
                        <circle cx="50" cy="12" r="3" fill="var(--ttt-amber)" />
                    </svg>
                `;
            } else if (mode === 'duo') {
                avatarEl.innerHTML = `
                    <svg viewBox="0 0 100 100" class="bot-avatar-svg" style="width:50px; height:50px; fill:none; stroke:var(--ttt-amber); stroke-width:4; stroke-linecap:round; stroke-linejoin:round;">
                        <path d="M20 80a30 30 0 0 1 60 0M50 50a20 20 0 1 0 0-40 20 20 0 0 0 0 40z" />
                    </svg>
                `;
            } else {
                const avatarFile = this.currentOpponentAvatar || <?php echo json_encode(\App\Models\User::DEFAULT_AVATAR, 15, 512) ?>;
                avatarEl.innerHTML = `<img src="/img/${avatarFile}" alt="Avatar lawan" style="width:100%; height:100%; object-fit:cover; border-radius:22px;">`;
            }
        }
    },

    updatePlayerCardsNames() {
        const homeNameEl = document.querySelector('.mg-ttt-player-card.is-home strong');
        const homeAvatarEl = document.querySelector('.mg-ttt-player-card.is-home .mg-ttt-avatar-ring');
        const rivalNameEl = document.querySelector('#mg-ttt-arena-rival strong');
        
        if (this.currentMode === 'duo') {
            if (homeNameEl) homeNameEl.innerText = "Player 1 (X)";
            if (homeAvatarEl) {
                homeAvatarEl.innerHTML = `
                    <svg viewBox="0 0 100 100" class="bot-avatar-svg" style="width:50px; height:50px; fill:none; stroke:var(--ttt-cyan); stroke-width:4; stroke-linecap:round; stroke-linejoin:round;">
                        <path d="M20 80a30 30 0 0 1 60 0M50 50a20 20 0 1 0 0-40 20 20 0 0 0 0 40z" />
                    </svg>
                `;
            }
            if (rivalNameEl) rivalNameEl.innerText = "Player 2 (O)";
        } else {
            if (homeNameEl) homeNameEl.innerText = "<?php echo e(Auth::user()->name); ?>";
            if (homeAvatarEl) {
                homeAvatarEl.innerHTML = `<img src="<?php echo e(asset('img/' . Auth::user()->avatarPath())); ?>" alt="Avatar pemain">`;
            }
            if (rivalNameEl) rivalNameEl.innerText = this.currentOpponentName || 'Penantang Arena';
        }
    },

    async startGameRouter() {
        const mode = document.getElementById('mg-ttt-mode').value;
        const autoGrindingActive = document.getElementById('mg-ttt-auto-grinding').checked;
        this.currentMode = mode;
        this.isAutoGrinding = autoGrindingActive;

        if (this.isAutoGrinding && mode === 'duo') {
            await window.showAlert('Autopilot Tidak Didukung', 'Autopilot tidak tersedia untuk mode Duo.');
            this.isAutoGrinding = false;
            document.getElementById('mg-ttt-auto-grinding').checked = false;
            return;
        }

        // Show Arena, Hide Main View
        document.getElementById('ttt-main-view').style.display = 'none';
        document.getElementById('ttt-arena-view').style.display = 'block';
        document.getElementById('mg-ttt-bracket').classList.remove('active');

        // Matchmaking screen animation
        const opponentName = this.pickRegisteredOpponent();

        if (this.isAutoGrinding) {
            // Autopilot matchmaking is super quick and snappy
            this.runAutoGrinding(opponentName);
            return;
        }

        // Normal Matchmaking flow
        if (mode === 'rank' || mode === 'tournament' || mode === 'have_fun') {
            this.showMatchmaking(opponentName, async () => {
                if (mode === 'tournament') {
                    this.initTournament();
                } else {
                    if (mode === 'have_fun') {
                        const diffEl = document.getElementById('mg-ttt-diff');
                        if (!diffEl.value) {
                            const difficulties = ['easy', 'pro', 'world_class'];
                            diffEl.value = difficulties[Math.floor(Math.random() * difficulties.length)];
                        }
                    }
                    this.startSoloRound();
                }
            });
        } else {
            // Duo mode does not need matchmaking wait
            this.duoState = { p1Wins: 0, p2Wins: 0, target: 2, currentTurn: 'X' };
            this.startDuoRound();
        }
    },

    showMatchmaking(opponentName, callback) {
        const nameEl = document.getElementById('mg-ttt-opponent-name');
        const opponentAvatar = document.getElementById('mg-ttt-opponent-avatar');
        const opponentCard = document.getElementById('mg-ttt-opponent-card');
        
        // Reset matchmaking state
        if (nameEl) nameEl.innerText = 'Mencari Rival...';
        if (opponentAvatar) {
            opponentAvatar.innerHTML = '?';
            opponentAvatar.className = 'mg-ttt-rival-orb';
        }
        if (opponentCard) {
            opponentCard.classList.remove('match-found');
        }

        const matchmaking = document.getElementById('mg-ttt-matchmaking');
        if (matchmaking) {
            matchmaking.classList.add('active');
            matchmaking.setAttribute('aria-hidden', 'false');
        }
        const bar = document.getElementById('mg-ttt-matchmaking-bar');
        if (bar) {
            bar.style.transition = 'none';
            bar.style.width = '0%';
            setTimeout(() => {
                bar.style.transition = 'width 1.2s cubic-bezier(0.25, 0.8, 0.25, 1)';
                bar.style.width = '100%';
            }, 30);
        }

        // After 1 second, match resolved!
        setTimeout(() => {
            if (nameEl) nameEl.innerText = opponentName;
            
            const mode = this.currentMode;
            const isBot = (mode === 'have_fun' && mode !== 'duo') || mode === 'tournament';
            
            if (opponentAvatar) {
                if (isBot) {
                    opponentAvatar.innerHTML = `
                        <svg viewBox="0 0 100 100" class="bot-avatar-svg" style="width:50px; height:50px; fill:none; stroke:var(--ttt-amber); stroke-width:4; stroke-linecap:round; stroke-linejoin:round;">
                            <rect x="25" y="25" width="50" height="50" rx="10" />
                            <circle cx="40" cy="45" r="4" fill="var(--ttt-amber)" />
                            <circle cx="60" cy="45" r="4" fill="var(--ttt-amber)" />
                            <path d="M40 60h20" />
                            <path d="M50 15v10" />
                            <circle cx="50" cy="12" r="3" fill="var(--ttt-amber)" />
                        </svg>
                    `;
                    opponentAvatar.className = 'mg-ttt-avatar-ring';
                } else {
                    const avatarFile = this.currentOpponentAvatar || <?php echo json_encode(\App\Models\User::DEFAULT_AVATAR, 15, 512) ?>;
                    opponentAvatar.innerHTML = `<img src="/img/${avatarFile}" alt="Avatar lawan" style="width:100%; height:100%; object-fit:cover; border-radius:22px;">`;
                    opponentAvatar.className = 'mg-ttt-avatar-ring';
                }
            }
            
            if (opponentCard) {
                opponentCard.classList.add('match-found');
            }
            this.updateRivalCard(opponentName);
        }, 1000);

        // Hide at 1.5 seconds snappy!
        setTimeout(() => {
            if (matchmaking) {
                matchmaking.classList.remove('active');
                matchmaking.setAttribute('aria-hidden', 'true');
            }
            callback();
        }, 1500);
    },

    async runAutoGrinding(opponentName) {
        const nameEl = document.getElementById('mg-ttt-opponent-name');
        if (nameEl) nameEl.innerText = opponentName;
        this.updateRivalCard(opponentName);
        const matchmaking = document.getElementById('mg-ttt-matchmaking');
        if (matchmaking) {
            matchmaking.classList.add('active');
            matchmaking.setAttribute('aria-hidden', 'false');
        }
        const bar = document.getElementById('mg-ttt-matchmaking-bar');
        if (bar) {
            bar.style.transition = 'none';
            bar.style.width = '0%';
            setTimeout(() => {
                bar.style.transition = 'width 0.8s ease-in-out';
                bar.style.width = '100%';
            }, 30);
        }

        const autopilotPromise = MgCore.apiCall('/minigame/use-autopilot');

        setTimeout(async () => {
            const res = await autopilotPromise;
            if (matchmaking) {
                matchmaking.classList.remove('active');
                matchmaking.setAttribute('aria-hidden', 'true');
            }

            if (!res.success) {
                await window.showAlert('Autopilot Terhenti', 'Autopilot Terhenti: ' + (res.message || 'Kesalahan Autopilot'));
                this.isAutoGrinding = false;
                document.getElementById('mg-ttt-auto-grinding').checked = false;
                this.exitArena();
                return;
            }

            this.board = Array(9).fill(null);
            this.renderBoard();
            const reward = Number(res.reward_amount ?? 0);
            const msg = res.result === 'win' ? 'Anda Menang (Autopilot)!' : (res.result === 'loss' ? 'Rival Menang (Autopilot)!' : 'Seri!');
            this.setStatus(msg, `Reward: Rp ${reward.toLocaleString('id-ID')} | Sisa Kuota Autopilot: F:${res.remaining_free} P:${res.remaining_pro} WC:${res.remaining_world_class}`);

            if (res.rank_reward > 0) {
                MgCore.toast(`Hadiah Naik Rank: Rp ${res.rank_reward.toLocaleString('id-ID')} ditambahkan ke ZeroPay!`);
            }
            if (res.new_balance !== undefined) {
                MgCore.updateBalance(res.new_balance);
            }
            this.updateStats({ profile: res.profile, balance: res.new_balance, autopilot: { free: res.remaining_free, pro: res.remaining_pro, world_class: res.remaining_world_class } });
            await this.safeLoadLobby();

            if (this.isAutoGrinding) {
                setTimeout(() => this.runAutoGrinding(this.pickRegisteredOpponent()), 800);
            }
        }, 900);
    },

    exitArena() {
        this.isPlaying = false;
        this.isAutoGrinding = false;
        this.updateTurnHighlight();
        this.hideResultPanel();
        document.getElementById('ttt-arena-view').style.display = 'none';
        document.getElementById('ttt-main-view').style.display = 'block';
    },

    startSoloRound() {
        this.hideResultPanel();
        this.board = Array(9).fill(null);
        this.isPlaying = true;
        this.isPlayerTurn = true;
        this.updatePlayerCardsNames();
        this.setStatus("Giliran Anda (X)", `Difficulty: ${document.getElementById('mg-ttt-diff').value.toUpperCase()} | Mode: ${this.currentMode.toUpperCase()}`);
        this.renderBoard();
    },

    startDuoRound() {
        this.hideResultPanel();
        this.board = Array(9).fill(null);
        this.isPlaying = true;
        this.isPlayerTurn = true; 
        this.duoState.currentTurn = 'X';
        this.updatePlayerCardsNames();
        this.setStatus(`Giliran ${this.duoState.currentTurn}`, `Skor P1 (X): ${this.duoState.p1Wins} | P2 (O): ${this.duoState.p2Wins} (Best of 3)`);
        this.renderBoard();
    },
    
    async initTournament() {
        const res = await MgCore.apiCall('/arcade/tournament-start', { game: 'ttt' });
        let players = ['Anda', 'Penantang A', 'Penantang B', 'Penantang C'];
        if (res.status === 'success' && res.players && res.players.length >= 4) {
            players = res.players;
        }

        this.tourneyState = { stage: 1, wins: 0, losses: 0, target: 1, active: true, players: players };
        document.getElementById('mg-ttt-bracket').classList.add('active');
        this.renderBracket();
        this.askPantauOrSkip();
    },

    async askPantauOrSkip() {
        const p = this.tourneyState.players || ['Anda', 'Penantang A', 'Penantang B', 'Penantang C'];
        const choice = await window.showConfirm('Pantau Match', `Pertandingan Semi-Final ${p[2]} vs ${p[3]} sedang berjalan.\n\nKlik OK untuk MENONTON jalannya laga, atau CANCEL untuk langsung SKIP.`);
        if (choice) {
            this.startSpectatingBots();
        } else {
            const winner = Math.random() > 0.5 ? p[2] : p[3];
            this.tourneyState.winnerOfSemi2 = winner;
            await window.showAlert('Match Result', `${winner} berhasil mengalahkan lawannya dan lolos ke Final!`);
            this.startTournamentRound();
        }
    },

    startSpectatingBots() {
        this.isSpectating = true;
        const p = this.tourneyState.players || ['Anda', 'Penantang A', 'Penantang B', 'Penantang C'];
        const spectator = document.getElementById('mg-ttt-spectator');
        if (spectator) { spectator.classList.add('active'); spectator.setAttribute('aria-hidden', 'false'); }
        document.getElementById('mg-ttt-spectator-sub').innerText = `Semi-Final: ${p[2]} (X) vs ${p[3]} (O)`;
        
        this.specBoard = Array(9).fill(null);
        this.renderSpecBoard();
        
        let currentSpecTurn = 'X';
        document.getElementById('mg-ttt-spec-status').innerText = `Giliran ${p[2]} (X) sedang berpikir...`;

        this.specInterval = setInterval(async () => {
            if (!this.isSpectating) return;

            const emptyIndices = [];
            this.specBoard.forEach((cell, i) => { if (cell === null) emptyIndices.push(i); });

            if (emptyIndices.length === 0) {
                document.getElementById('mg-ttt-spec-status').innerText = "Pertandingan Seri!";
                clearInterval(this.specInterval);
                setTimeout(() => this.finishSpectating(Math.random() > 0.5 ? p[2] : p[3]), 1500);
                return;
            }

            const move = emptyIndices[Math.floor(Math.random() * emptyIndices.length)];
            this.specBoard[move] = currentSpecTurn;
            this.renderSpecBoard();

            if (this.checkWinner(this.specBoard, currentSpecTurn)) {
                const winnerName = currentSpecTurn === 'X' ? p[2] : p[3];
                document.getElementById('mg-ttt-spec-status').innerText = `${winnerName} MENANG! `;
                clearInterval(this.specInterval);
                setTimeout(() => this.finishSpectating(winnerName), 1500);
                return;
            }

            currentSpecTurn = currentSpecTurn === 'X' ? 'O' : 'X';
            document.getElementById('mg-ttt-spec-status').innerText = `Giliran ${currentSpecTurn === 'X' ? p[2] + ' (X)' : p[3] + ' (O)'} sedang berpikir...`;
        }, 800); // slightly faster spectating
    },

    renderSpecBoard() {
        const grid = document.getElementById('mg-ttt-spec-board');
        grid.innerHTML = '';
        for (let i = 0; i < 9; i++) {
            const cell = document.createElement('div');
            cell.className = 'mg-ttt-cell';
            if (this.specBoard[i]) {
                cell.innerText = this.specBoard[i];
                cell.classList.add(this.specBoard[i].toLowerCase());
            }
            grid.appendChild(cell);
        }
    },

    async confirmExitSpectator() {
        const p = this.tourneyState.players || ['Anda', 'Penantang A', 'Penantang B', 'Penantang C'];
        const ok = await this.showLocalConfirm('Keluar Pantau', 'Apakah Anda yakin ingin keluar dari Pantau? Hasil pertandingan rival akan ditentukan cepat.', 'Keluar Pantau', 'Tetap Pantau', false);
        if (ok) {
            clearInterval(this.specInterval);
            this.finishSpectating(Math.random() > 0.5 ? p[2] : p[3]);
        }
    },

    async finishSpectating(winner) {
        this.isSpectating = false;
        this.tourneyState.winnerOfSemi2 = winner;
        const spectator = document.getElementById('mg-ttt-spectator');
        if (spectator) { spectator.classList.remove('active'); spectator.setAttribute('aria-hidden', 'true'); }
        await window.showAlert('Lolos ke Final', `${winner} berhasil lolos ke Final!`);
        this.startTournamentRound();
    },
    
    startTournamentRound() {
        this.hideResultPanel();
        this.board = Array(9).fill(null);
        this.isPlaying = true;
        this.isPlayerTurn = true;
        this.updatePlayerCardsNames();
        
        const p = this.tourneyState.players || ['Anda', 'Penantang A', 'Penantang B', 'Penantang C'];
        const opponent = this.tourneyState.stage === 3 ? (this.tourneyState.winnerOfSemi2 || p[2]) : p[1];

        if (this.tourneyState.stage === 3) {
            this.tourneyState.target = 3; // Best of 5
            this.setStatus("Giliran Anda (X)", `FINAL TURNAMEN! Skor: ${this.tourneyState.wins} - ${this.tourneyState.losses} vs ${opponent} (Best of 5)`);
        } else {
            this.tourneyState.target = 1; // Semi final
            this.setStatus("Giliran Anda (X)", `SEMI-FINAL: Anda vs ${opponent}`);
        }
        this.renderBoard();
    },

    renderBracket() {
        const b = document.getElementById('mg-ttt-bracket');
        const p = this.tourneyState.players || ['Anda', 'Penantang A', 'Penantang B', 'Penantang C'];
        const finalOpponent = this.tourneyState.stage === 3 ? (this.tourneyState.winnerOfSemi2 || 'Finalis') : 'Pemenang Semi-2';
        b.innerHTML = `
            <div class="mg-bracket-round">
                <div class="mg-matchup ${this.tourneyState.stage===1?'active-match':''}">${p[0]} vs ${p[1]}</div>
                <div class="mg-matchup">${p[2]} vs ${p[3]}</div>
            </div>
            <div class="mg-bracket-round" style="justify-content:center;">
                <div class="mg-matchup ${this.tourneyState.stage===3?'active-match':''}">${p[0]} vs ${finalOpponent} (Final)</div>
            </div>
        `;
    },

    async makeMove(index) {
        index = Number(index);
        if (!Number.isInteger(index) || index < 0 || index > 8) return;
        if (!this.isPlaying || this.board[index] !== null) return;

        if (this.currentMode === 'duo') {
            this.board[index] = this.duoState.currentTurn;
            
            if (this.checkWinner(this.board, this.duoState.currentTurn)) {
                this.renderBoard();
                return this.endDuoRound(this.duoState.currentTurn);
            }
            if (!this.board.includes(null)) {
                this.renderBoard();
                return this.endDuoRound('draw');
            }
            
            this.duoState.currentTurn = this.duoState.currentTurn === 'X' ? 'O' : 'X';
            this.setStatus(`Giliran ${this.duoState.currentTurn}`, `Skor P1 (X): ${this.duoState.p1Wins} | P2 (O): ${this.duoState.p2Wins} (Best of 3)`);
            this.renderBoard();
            return;
        }

        if (!this.isPlayerTurn) return;
        
        this.board[index] = this.playerSymbol;
        this.isPlayerTurn = false;

        if (this.checkWinner(this.board, this.playerSymbol)) {
            this.renderBoard();
            return this.endAiRound('win');
        }
        if (!this.board.includes(null)) {
            this.renderBoard();
            return this.endAiRound('draw');
        }

        this.setStatus("Rival membaca langkahmu...");
        this.renderBoard();
        
        let diff = document.getElementById('mg-ttt-diff').value;
        if (this.currentMode === 'rank') {
            diff = 'world_class';
        } else if (this.currentMode === 'tournament') {
            diff = this.tourneyState.stage === 3 ? 'world_class' : 'pro';
        }

        const res = await MgCore.apiCall('/minigame/play', { board: this.board, difficulty: diff });

        if (res.status === 'success' && res.move !== null) {
            this.board[res.move] = this.botSymbol;
            
            if (this.checkWinner(this.board, this.botSymbol)) {
                this.renderBoard();
                return this.endAiRound('loss');
            }
            if (!this.board.includes(null)) {
                this.renderBoard();
                return this.endAiRound('draw');
            }
            
            this.isPlayerTurn = true;
            this.setStatus("Giliran Anda (X)");
            this.renderBoard();
            this.scheduleAutopilotMove();
        }
    },

    getWinningLine(b, p) {
        const wins = [[0,1,2],[3,4,5],[6,7,8],[0,3,6],[1,4,7],[2,5,8],[0,4,8],[2,4,6]];
        return wins.find(w => b[w[0]] === p && b[w[1]] === p && b[w[2]] === p) || null;
    },

    checkWinner(b, p) {
        return !!this.getWinningLine(b, p);
    },

    async endAiRound(result) {
        this.isPlaying = false;
        this.updateTurnHighlight();
        
        if (this.currentMode === 'tournament') {
            if (result === 'win') this.tourneyState.wins++;
            else if (result === 'loss') this.tourneyState.losses++;
            
            if (this.tourneyState.wins >= this.tourneyState.target) {
                if (this.tourneyState.stage === 1) {
                    this.tourneyState.stage = 3;
                    this.tourneyState.wins = 0;
                    this.tourneyState.losses = 0;
                    this.renderBracket();
                    this.setStatus("Anda Lolos ke Final!", "Mempersiapkan ronde final...");
                    setTimeout(() => this.startTournamentRound(), 1500);
                } else if (this.tourneyState.stage === 3) {
                    this.setStatus("Juara Turnamen!", "Selamat! Reward turnamen sedang diproses.");
                    this.submitBackendResult('tournament', 'win');
                }
            } else if (this.tourneyState.losses >= this.tourneyState.target) {
                this.setStatus("Anda Tereliminasi!", "Game Over.");
                this.submitBackendResult('tournament', 'loss');
            } else {
                this.setStatus("Ronde Selesai", `Skor Sementara: ${this.tourneyState.wins} - ${this.tourneyState.losses}`);
                setTimeout(() => this.startTournamentRound(), 1200);
            }
            return;
        }

        let msg = result === 'win' ? "Anda Menang!" : (result === 'loss' ? "Rival Menang!" : "Seri!");
        this.setStatus(msg, "Permainan Selesai.");
        this.submitBackendResult(this.currentMode, result);
    },
    
    endDuoRound(winner) {
        this.isPlaying = false;
        this.updateTurnHighlight();
        if (winner === 'X') this.duoState.p1Wins++;
        else if (winner === 'O') this.duoState.p2Wins++;
        
        if (this.duoState.p1Wins >= this.duoState.target) {
            this.setStatus("Player 1 (X) Menang Seri!", "Duo Match Selesai.");
            this.showResultPanel('duo', 'draw', { reward_amount: 0, new_balance: MgCore.balance });
        } else if (this.duoState.p2Wins >= this.duoState.target) {
            this.setStatus("Player 2 (O) Menang Seri!", "Duo Match Selesai.");
            this.showResultPanel('duo', 'draw', { reward_amount: 0, new_balance: MgCore.balance });
        } else {
            this.setStatus(winner==='draw'?"Seri!":"Game Selesai", "Lanjut game berikutnya...");
            setTimeout(() => this.startDuoRound(), 1200);
        }
    },

    async submitBackendResult(mode, result) {
        let backendMode = mode === 'rank' ? 'rank' : (mode === 'tournament' ? 'tournament' : 'have_fun');
        let diff = document.getElementById('mg-ttt-diff').value;
        if (mode === 'rank') diff = 'world_class';
        else if (mode === 'tournament') diff = 'world_class';

        const payload = { game_mode: backendMode, difficulty: diff, result: result };
        if (mode === 'rank' && this.currentOpponentUserId) payload.opponent_user_id = this.currentOpponentUserId;
        const res = await MgCore.apiCall('/minigame/match-result', payload);

        if (res.status !== 'success') {
            MgCore.toast(res.message || 'Hasil belum bisa disimpan. Arena tetap bisa dilanjutkan.');
            this.showResultPanel(mode, result, { reward_amount: 0, new_balance: MgCore.balance, message: res.message || 'Hasil belum bisa disimpan.' });
            return;
        }

        if (res.status === 'success') {
            if (Number(res.reward_amount ?? res.reward ?? 0) > 0) {
                MgCore.toast(`Mendapatkan Rp ${Number(res.reward_amount ?? res.reward ?? 0).toLocaleString('id-ID')}!`);
            }
            if (res.rank_up) MgCore.toast("Naik Rank!");
            if (res.new_balance !== undefined) {
                MgCore.updateBalance(res.new_balance);
            }
            this.updateStats({ profile: res.profile, balance: res.new_balance, autopilot: { free: res.profile.autopilot_free || 0, pro: res.profile.autopilot_pro || 0, world_class: res.profile.autopilot_world_class || 0 } });
            await this.safeLoadLobby();
            if (this.isAutoGrinding) {
                this.exitArena();
                setTimeout(() => this.startGameRouter(), 700);
                return;
            }
            this.showResultPanel(mode, result, res);
        }
    },

    hideResultPanel() {
        const panel = document.getElementById('mg-ttt-result-panel');
        if (!panel) return;
        panel.classList.remove('active');
    },

    formatModeLabel(mode) {
        return ({ have_fun: 'Have Fun Solo', rank: 'Ranked', tournament: 'Tournament', duo: 'Duo' }[mode] || mode || 'Arena');
    },

    showResultPanel(mode, result, payload = {}) {
        const panel = document.getElementById('mg-ttt-result-panel');
        if (!panel) return;
        const reward = Number(payload.reward_amount ?? payload.reward ?? 0);
        const balance = payload.new_balance !== undefined ? `Saldo sekarang Rp ${Number(payload.new_balance).toLocaleString('id-ID')}.` : '';
        const resultText = result === 'win' ? 'Anda Menang' : (result === 'loss' ? 'Anda Kalah' : 'Seri');
        const note = mode === 'duo'
            ? 'Mode Duo adalah hiburan bersama, tanpa reward dan tanpa riwayat reward.'
            : (reward > 0 ? `Reward Rp ${reward.toLocaleString('id-ID')} sudah masuk ke ZeroPay.` : 'Tidak ada reward untuk hasil ini.');
        const iconClass = result === 'loss' ? 'is-loss' : (result === 'draw' ? 'is-draw' : '');
        const iconPath = result === 'win'
            ? '<path d="M6 12l4 4 8-9"/>'
            : (result === 'loss' ? '<path d="M7 7l10 10M17 7L7 17"/>' : '<path d="M6 12h12"/>');
        panel.innerHTML = `
            <div class="mg-ttt-result-grid">
                <div class="mg-ttt-result-icon ${iconClass}" aria-hidden="true">
                    <svg class="mg-ttt-svg" viewBox="0 0 24 24">${iconPath}</svg>
                </div>
                <div>
                    <div class="mg-ttt-kicker">${this.formatModeLabel(mode)}</div>
                    <h3 class="mg-ttt-result-title">${resultText}</h3>
                    <p class="mg-ttt-result-copy">${note} ${balance}</p>
                </div>
            </div>
            ${reward > 0 ? `<div class="mg-ttt-result-reward">Rp ${reward.toLocaleString('id-ID')}</div>` : ''}
            <div class="mg-ttt-result-actions">
                <button class="mg-btn mg-ttt-primary-cta" type="button" data-mg-game="ttt" data-mg-action="result-continue">Lanjut Main</button>
                <button class="mg-btn mg-btn-secondary" type="button" data-mg-game="ttt" data-mg-action="result-lobby">Kembali ke Lobby</button>
                <button class="mg-btn mg-btn-secondary" type="button" data-mg-game="ttt" data-mg-action="result-portal">Kembali ke Portal</button>
            </div>`;
        panel.classList.add('active');
    },

    handleResultAction(action) {
        this.hideResultPanel();
        this.isAutoGrinding = false;
        this.exitArena();
        if (action === 'result-continue') {
            setTimeout(() => this.startGameRouter(), 80);
        } else if (action === 'result-portal') {
            MgCore.navigate('portal');
        }
    },

    setStatus(text, sub = "") {
        const status = document.getElementById('mg-ttt-status');
        const substatus = document.getElementById('mg-ttt-substatus');
        if (status) status.innerText = text;
        if (substatus) substatus.innerText = sub;
    },

    enableAutopilotFromLobby() {
        const box = document.getElementById('mg-ttt-auto-grinding');
        if (box) box.checked = true;
        this.startGameRouter();
    },

    async toggleAutopilot() {
        if (this.currentMode === 'duo') {
            await window.showAlert('Tidak Didukung', 'Autopilot tidak tersedia untuk mode Duo.');
            return;
        }
        this.isAutoGrinding = !this.isAutoGrinding;
        const box = document.getElementById('mg-ttt-auto-grinding');
        if (box) box.checked = this.isAutoGrinding;
        const btn = document.getElementById('mg-ttt-arena-autopilot');
        if (btn) btn.innerText = this.isAutoGrinding ? 'Autopilot Aktif' : 'Aktifkan Autopilot';
        this.scheduleAutopilotMove();
    },

    scheduleAutopilotMove() {
        if (!this.isAutoGrinding || !this.isPlaying || !this.isPlayerTurn || this.currentMode === 'duo') return;
        const empty = this.board.map((cell, idx) => cell === null ? idx : null).filter(idx => idx !== null);
        if (!empty.length) return;
        setTimeout(() => {
            if (!this.isAutoGrinding || !this.isPlaying || !this.isPlayerTurn) return;
            this.makeMove(empty[Math.floor(Math.random() * empty.length)]);
        }, 500); // faster auto move
    },

    async surrenderMatch() {
        if (!this.isPlaying || this.currentMode === 'duo') {
            this.exitArena();
            return;
        }
        let ok = false;
        try {
            ok = await this.showLocalConfirm('Menyerah?', 'Menyerah dihitung kalah resmi. Reward tidak diberikan dan rank dapat turun.', 'Menyerah', 'Lanjut Main', true);
        } catch (error) {
            console.error('Surrender confirm failed:', error);
            MgCore.toast('Konfirmasi belum siap. Coba sekali lagi.');
            return;
        }
        if (!ok) return;
        this.isPlaying = false;
        this.isAutoGrinding = false;
        const box = document.getElementById('mg-ttt-auto-grinding');
        if (box) box.checked = false;
        this.setStatus('Anda Menyerah', 'Hasil disimpan sebagai kekalahan match.');
        try {
            await this.submitBackendResult(this.currentMode, 'loss');
        } catch (error) {
            console.error('Surrender submit failed:', error);
            this.showResultPanel(this.currentMode, 'loss', { reward_amount: 0, new_balance: MgCore.balance, message: 'Hasil menyerah belum tersimpan.' });
        }
    },
    showLocalConfirm(title, message, okText = 'Lanjutkan', cancelText = 'Batal', danger = false) {
        return new Promise((resolve) => {
            const overlay = document.getElementById('mg-ttt-local-modal');
            const titleEl = document.getElementById('mg-ttt-modal-title');
            const msgEl = document.getElementById('mg-ttt-modal-message');
            const okBtn = document.getElementById('mg-ttt-modal-ok');
            const cancelBtn = document.getElementById('mg-ttt-modal-cancel');
            if (!overlay || !titleEl || !msgEl || !okBtn || !cancelBtn) {
                resolve(false);
                return;
            }

            titleEl.innerText = title;
            msgEl.innerText = message;
            okBtn.innerText = okText;
            cancelBtn.innerText = cancelText;
            okBtn.className = `mg-btn ${danger ? 'mg-ttt-danger-btn' : 'mg-ttt-primary-cta'}`;
            overlay.classList.add('active');
            overlay.setAttribute('aria-hidden', 'false');

            const close = (value) => {
                overlay.classList.remove('active');
                overlay.setAttribute('aria-hidden', 'true');
                okBtn.removeEventListener('click', onOk);
                cancelBtn.removeEventListener('click', onCancel);
                overlay.removeEventListener('click', onOverlay);
                document.removeEventListener('keydown', onKeydown);
                resolve(value);
            };
            const onOk = () => close(true);
            const onCancel = () => close(false);
            const onOverlay = (event) => { if (event.target === overlay) close(false); };
            const onKeydown = (event) => { if (event.key === 'Escape') close(false); };
            okBtn.addEventListener('click', onOk);
            cancelBtn.addEventListener('click', onCancel);
            overlay.addEventListener('click', onOverlay);
            document.addEventListener('keydown', onKeydown);
            setTimeout(() => okBtn.focus(), 30);
        });
    },
    async useAutopilot() {
        const mode = document.getElementById('mg-ttt-mode').value;
        if (mode === 'duo') {
            await window.showAlert('Tidak Didukung', 'Autopilot tidak tersedia untuk mode Duo.');
            return;
        }

        MgCore.toast('Autopilot berjalan...');
        const res = await MgCore.apiCall('/minigame/use-autopilot');
        if (res.success) {
            let msg = `Autopilot Selesai: Anda ${res.result.toUpperCase()}! (+Rp ${Number(res.reward_amount ?? 0).toLocaleString('id-ID')})`;
            MgCore.toast(msg);
            if (res.rank_reward > 0) {
                MgCore.toast(`Hadiah Naik Rank: Rp ${res.rank_reward.toLocaleString('id-ID')} ditambahkan ke ZeroPay!`);
            }
            if (res.new_balance !== undefined) {
                MgCore.updateBalance(res.new_balance);
            }
            this.safeLoadLobby();
        } else {
            await window.showAlert('Gagal Autopilot', res.message || 'Gagal Autopilot');
        }
    }
};
</script>
<?php /**PATH C:\laragon\www\ProyekTI\resources\views/components/minigame-ttt.blade.php ENDPATH**/ ?>