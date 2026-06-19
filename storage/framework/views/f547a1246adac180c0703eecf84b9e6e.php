<style>
/* RPS Specific Styles & Tabs */
.mg-rps-tabs {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
    border-bottom: 2px solid var(--mg-border);
    padding-bottom: 1rem;
    overflow-x: auto;
}
.mg-rps-tab-btn {
    background: transparent;
    border: none;
    color: var(--mg-text-muted);
    font-size: 1.2rem;
    font-weight: 700;
    cursor: pointer;
    padding: 0.5rem 1rem;
    border-radius: var(--mg-radius-sm);
    transition: all 0.2s;
    white-space: nowrap;
}
.mg-rps-tab-btn:hover { color: var(--mg-text); background: var(--mg-surface); }
.mg-rps-tab-btn.active { color: var(--mg-primary); background: var(--mg-primary-light); }

.mg-tab-content { display: none; }
.mg-tab-content.active { display: block; }

/* Lobby layout */
.mg-rps-lobby-container {
    max-width: 760px;
    margin: 0 auto;
    background: var(--mg-surface);
    padding: 2.25rem;
    border-radius: var(--mg-radius-lg);
    border: 1px solid var(--mg-border);
    text-align: center;
}

/* Arena layout */
.mg-rps-arena-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
}
.mg-rps-arena {
    display: flex;
    justify-content: space-around;
    align-items: center;
    width: 100%;
    margin-top: 2rem;
    background: var(--mg-surface);
    padding: 2.25rem;
    border-radius: var(--mg-radius-lg);
    border: 1px solid var(--mg-border);
}
.mg-rps-player, .mg-rps-bot {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
}
.mg-rps-hand-display {
    width: 150px;
    height: 150px;
    background: var(--mg-bg);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 5rem;
    border: 4px solid var(--mg-border);
    transition: all 0.2s ease-in-out;
}

/* Mirror the rival hand to face the player */
.mg-rps-bot .mg-rps-hand-display, #mg-rps-spec-p2-hand {
    transform: scaleX(-1);
}

.mg-rps-controls {
    display: flex;
    gap: 1.5rem;
    margin-top: 2rem;
}
.mg-rps-btn {
    width: 90px;
    height: 90px;
    font-size: 3rem;
    border-radius: 50%;
    background: var(--mg-surface);
    border: 2px solid var(--mg-border);
    cursor: pointer;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 0 10px rgba(255,255,255,0.05);
}
.mg-rps-btn:hover {
    border-color: var(--mg-primary);
    transform: translateY(-5px);
    box-shadow: 0 0 20px var(--mg-primary);
}

/* Shake keyframe animations */
@keyframes rps-shake-left {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    25% { transform: translateY(-15px) rotate(15deg); }
    50% { transform: translateY(10px) rotate(-10deg); }
    75% { transform: translateY(-5px) rotate(5deg); }
}
@keyframes rps-shake-right {
    0%, 100% { transform: translateY(0) rotate(0deg) scaleX(-1); }
    25% { transform: translateY(-15px) rotate(15deg) scaleX(-1); }
    50% { transform: translateY(10px) rotate(-10deg) scaleX(-1); }
    75% { transform: translateY(-5px) rotate(5deg) scaleX(-1); }
}
.mg-rps-hand-display.shaking-left {
    animation: rps-shake-left 0.4s ease-in-out infinite;
}
.mg-rps-hand-display.shaking-right {
    animation: rps-shake-right 0.4s ease-in-out infinite;
}

/* Common Utilities */
.mg-rps-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}
.mg-rps-status {
    font-size: 2.2rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
    text-align: center;
}
.mg-rps-substatus {
    color: var(--mg-primary);
    font-weight: 600;
    margin-bottom: 1rem;
    text-align: center;
}
.mg-rps-name { font-weight: 700; font-size: 1.2rem; }
.mg-stat-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid var(--mg-border);
}
.mg-game-controls {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-top: 1.5rem;
}

/* Duo Hint */
.mg-duo-hint {
    display: none;
    margin-top: 10px;
    font-size: 0.9rem;
    color: var(--mg-text-muted);
}
.mg-duo-hint.active { display: block; }

/* Shop Grid */
.mg-shop-section { margin-bottom: 3rem; }
.mg-shop-section-title { font-size: 1.5rem; margin-bottom: 1rem; border-bottom: 2px solid var(--mg-border); padding-bottom: 0.5rem;}
.mg-shop-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 1.5rem;
}
.mg-shop-item {
    background: var(--mg-surface);
    border: 1px solid var(--mg-border);
    border-radius: var(--mg-radius);
    padding: 1.5rem;
    text-align: center;
    transition: all 0.2s;
}
.mg-shop-item:hover { border-color: var(--mg-accent); transform: translateY(-3px);}
.mg-shop-item-icon { font-size: 3rem; margin-bottom: 1rem; }
.mg-shop-item-title { font-size: 1.2rem; font-weight: 700; margin-bottom: 0.5rem; }
.mg-shop-item-price { color: #10b981; font-weight: 800; font-size: 1.2rem; margin-bottom: 1rem; }
.mg-shop-item-owned { color: var(--mg-text-muted); font-weight: 600; padding: 0.5rem; background: var(--mg-surface-alt); border-radius: var(--mg-radius);}

/* Tournament Bracket UI */
.mg-tournament-bracket {
    display: none;
    background: var(--mg-surface);
    padding: 1.5rem;
    border-radius: var(--mg-radius-lg);
    border: 1px solid var(--mg-border);
    margin-bottom: 2rem;
    width: 100%;
}
.mg-tournament-bracket.active {
    display: flex;
    justify-content: space-around;
}
.mg-bracket-round {
    display: flex;
    flex-direction: column;
    justify-content: space-around;
    gap: 1rem;
}
.mg-matchup {
    background: var(--mg-bg);
    border: 1px solid var(--mg-border);
    padding: 0.5rem;
    border-radius: var(--mg-radius-sm);
    text-align: center;
    min-width: 120px;
}
.mg-matchup.active-match {
    border-color: var(--mg-primary);
    box-shadow: 0 0 10px rgba(124, 58, 237, 0.3);
}

/* Leaderboard */
.mg-leaderboard-table {
    width: 100%;
    border-collapse: collapse;
    background: var(--mg-surface);
    border-radius: var(--mg-radius-lg);
    overflow: hidden;
    border: 1px solid var(--mg-border);
}
.mg-leaderboard-table th, .mg-leaderboard-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid var(--mg-border);
}
.mg-leaderboard-table th { background: var(--mg-surface-alt); font-weight: 800; }
.mg-leaderboard-table tr:last-child td { border-bottom: none; }
/* Detail skin: Zero Infinity RPS arena */
#mg-screen-rps {
    --rps-accent: #ff4f8b;
    --rps-gold: #ffd278;
    --rps-ice: #7ed3ff;
    --rps-green: #8ef6bd;
    --rps-panel: rgba(16, 14, 23, 0.96);
    --rps-border: rgba(255, 210, 120, 0.16);
    background:
        radial-gradient(circle at 16% 16%, rgba(255, 79, 139, 0.15), transparent 30%),
        radial-gradient(circle at 86% 20%, rgba(126, 211, 255, 0.1), transparent 30%),
        linear-gradient(135deg, #07060b 0%, #14101a 56%, #09070d 100%);
}

#mg-screen-rps::before {
    background-image:
        linear-gradient(rgba(255, 210, 120, 0.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255, 79, 139, 0.035) 1px, transparent 1px);
    background-size: 54px 54px;
    opacity: 0.38;
}

#mg-screen-rps .mg-rps-tabs,
#mg-screen-rps .mg-rps-lobby-container,
#mg-screen-rps .mg-rps-arena,
#mg-screen-rps .mg-rps-player,
#mg-screen-rps .mg-rps-bot,
#mg-screen-rps .mg-tournament-bracket,
#mg-screen-rps .mg-matchup,
#mg-screen-rps .mg-leaderboard-table,
#mg-screen-rps .mg-shop-item,
#mg-screen-rps .mg-game-store-card {
    border-radius: 8px;
    border-color: var(--rps-border);
    background:
        repeating-linear-gradient(135deg, rgba(255, 255, 255, 0.025) 0 1px, transparent 1px 18px),
        linear-gradient(145deg, rgba(18, 16, 26, 0.96), rgba(8, 7, 12, 0.94));
    box-shadow: 0 18px 46px rgba(0, 0, 0, 0.32);
}

#mg-screen-rps .mg-rps-tab-btn,
#mg-screen-rps .mg-rps-btn,
#mg-screen-rps .mg-game-mode-action,
#mg-screen-rps .mg-game-control-btn {
    border-radius: 8px;
}

#mg-screen-rps .mg-rps-tab-btn.active,
#mg-screen-rps .mg-rps-tab-btn:hover,
#mg-screen-rps .mg-rps-btn:hover,
#mg-screen-rps .mg-rps-btn.active {
    color: var(--rps-gold);
    border-color: rgba(255, 79, 139, 0.42);
    background: linear-gradient(135deg, rgba(255, 79, 139, 0.16), rgba(255, 210, 120, 0.06));
    box-shadow: 0 14px 34px rgba(255, 79, 139, 0.14);
}

#mg-screen-rps .mg-rps-hand-display {
    border-radius: 8px;
    color: var(--rps-gold);
    border: 1px solid rgba(255, 210, 120, 0.2);
    background: linear-gradient(145deg, rgba(255, 79, 139, 0.1), rgba(126, 211, 255, 0.06));
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.08), 0 18px 36px rgba(0, 0, 0, 0.28);
}

#mg-screen-rps .mg-rps-status,
#mg-screen-rps .mg-stat-row strong,
#mg-screen-rps .mg-shop-item-price,
#mg-screen-rps .mg-game-store-price {
    color: var(--rps-green);
}

#mg-screen-rps .mg-shop-grid,
#mg-screen-rps .mg-game-store-grid {
    gap: 14px;
}

#mg-screen-rps .mg-shop-item-icon,
#mg-screen-rps .mg-game-store-icon {
    border-radius: 8px;
    color: var(--rps-gold);
    background: linear-gradient(145deg, rgba(255, 79, 139, 0.16), rgba(255, 210, 120, 0.1));
    border: 1px solid rgba(255, 210, 120, 0.18);
}

#mg-screen-rps .mg-shop-item:hover,
#mg-screen-rps .mg-game-store-card:hover {
    border-color: rgba(255, 79, 139, 0.38);
    transform: translateY(-2px);
}

#mg-screen-rps .mg-shop-item-owned {
    border-radius: 8px;
    color: var(--rps-gold);
    border: 1px solid rgba(255, 210, 120, 0.22);
    background: rgba(255, 210, 120, 0.08);
}

#mg-screen-rps .mg-matchup.winner {
    border-color: rgba(142, 246, 189, 0.42);
    background: rgba(142, 246, 189, 0.08);
}

#mg-rps-matchmaking,
#mg-rps-spectator {
    background: rgba(4, 3, 7, 0.82) !important;
    backdrop-filter: blur(18px);
}

#mg-rps-matchmaking > div:first-child {
    color: var(--rps-gold) !important;
    text-shadow: 0 0 26px rgba(255, 210, 120, 0.26);
}

#mg-rps-matchmaking [style*="background: #121214"],
#mg-rps-spectator [style*="background: #121214"] {
    border-radius: 8px !important;
    border-color: rgba(255, 210, 120, 0.22) !important;
    background: linear-gradient(145deg, rgba(18, 16, 26, 0.98), rgba(9, 8, 14, 0.97)) !important;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.38) !important;
}

#mg-rps-matchmaking-bar {
    background: linear-gradient(90deg, #ff4f8b, #ffd278, #7ed3ff) !important;
}

#mg-rps-countdown-overlay {
    color: var(--rps-gold) !important;
    text-shadow: 0 0 32px rgba(255, 210, 120, 0.36);
}
</style>

<div id="mg-screen-rps" class="mg-screen">
    <!-- Matchmaking Overlay -->
    <div id="mg-rps-matchmaking" style="display: none; position: fixed; inset: 0; background: rgba(9, 9, 11, 0.97); z-index: 999999; align-items: center; justify-content: center; flex-direction: column;">
        <div style="font-size: 2.2rem; font-weight: 800; color: #EF4444; text-shadow: 0 0 20px rgba(239, 68, 68, 0.5); margin-bottom: 2rem; animation: glitch 1.5s infinite; font-family: 'Outfit', sans-serif;"> MENCARI TANDING...</div>
        <div style="display: flex; gap: 3rem; align-items: center; margin-bottom: 3rem;">
            <div style="text-align: center; background: #121214; padding: 1.5rem; border-radius: 16px; border: 2px solid #7c3aed; width: 160px; box-shadow: 0 0 20px rgba(124, 58, 237, 0.15);">
                <img src="<?php echo e(asset('img/' . Auth::user()->avatarPath())); ?>" style="width: 80px; height: 80px; border-radius: 50%; border: 3px solid #7c3aed; margin-bottom: 1rem; object-fit: cover;">
                <div style="font-weight: 700; color: #fff; font-size: 0.95rem;"><?php echo e(Auth::user()->name); ?></div>
                <div style="font-size: 0.75rem; color: #a78bfa; font-weight: 600; text-transform: uppercase;">Pemain</div>
            </div>
            <div style="font-size: 2rem; font-weight: 900; color: #EF4444; animation: pulse 1s infinite;">VS</div>
            <div id="mg-rps-opponent-card" style="text-align: center; background: #121214; padding: 1.5rem; border-radius: 16px; border: 2px solid #EF4444; width: 160px; box-shadow: 0 0 20px rgba(239, 68, 68, 0.15);">
                <div id="mg-rps-opponent-avatar" style="font-size: 3.5rem; margin-bottom: 1rem; height: 80px; display: flex; align-items: center; justify-content: center;"></div>
                <div id="mg-rps-opponent-name" style="font-weight: 700; color: #fff; font-size: 0.95rem;">Mencari...</div>
                <div style="font-size: 0.75rem; color: #EF4444; font-weight: 600; text-transform: uppercase;">Penantang Terdaftar</div>
            </div>
        </div>
        <div style="width: 250px; height: 8px; background: rgba(255, 255, 255, 0.1); border-radius: 99px; overflow: hidden; border: 1px solid rgba(255, 255, 255, 0.2);">
            <div id="mg-rps-matchmaking-bar" style="height: 100%; background: linear-gradient(90deg, #EF4444, #F59E0B); width: 0%;"></div>
        </div>
    </div>

    <!-- Rival spectator overlay -->
    <div id="mg-rps-spectator" style="display: none; position: fixed; inset: 0; background: rgba(9, 9, 11, 0.97); z-index: 999999; align-items: center; justify-content: center; flex-direction: column;">
        <div style="font-size: 2.2rem; font-weight: 800; color: #EF4444; text-shadow: 0 0 20px rgba(239, 68, 68, 0.5); margin-bottom: 1rem; font-family: 'Outfit', sans-serif;"> MODE PANTAU</div>
        <div style="color: #a1a1aa; font-weight: 600; margin-bottom: 2rem;" id="mg-rps-spectator-sub">Penantang B vs Penantang C</div>
        
        <div class="mg-rps-arena-container" style="background: #121214; padding: 2rem; border-radius: 20px; border: 1px solid rgba(255,255,255,0.05); box-shadow: var(--mg-shadow); width: 450px;">
            <div id="mg-rps-spec-status" style="font-size: 1.4rem; font-weight: 700; color: #fff; margin-bottom: 1.5rem; text-align: center;">Menginisialisasi...</div>
            <div class="mg-rps-arena" style="margin-top: 0;">
                <div class="mg-rps-player">
                    <span class="mg-rps-name" id="mg-rps-spec-p1-name">Penantang B</span>
                    <div id="mg-rps-spec-p1-hand" class="mg-rps-hand-display"></div>
                </div>
                <div style="display:flex; align-items:center; font-size:2rem; font-weight:800; color:var(--mg-text-muted);">VS</div>
                <div class="mg-rps-bot">
                    <span class="mg-rps-name" id="mg-rps-spec-p2-name">Penantang C</span>
                    <div id="mg-rps-spec-p2-hand" class="mg-rps-hand-display"></div>
                </div>
            </div>
        </div>

        <button class="mg-btn mg-btn-secondary" style="margin-top: 2rem; border-color: #EF4444; color: #EF4444;" type="button" data-mg-game="rps" data-mg-action="confirm-exit-spectator">Keluar Pantau</button>
    </div>

    <div class="mg-game-lobby-topbar">
        <div class="mg-game-lobby-brand">
            <div class="mg-game-lobby-logo"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 11V6.5a1.5 1.5 0 0 1 3 0V11"/><path d="M10 11V5.5a1.5 1.5 0 0 1 3 0V11"/><path d="M13 11V7a1.5 1.5 0 0 1 3 0v6"/><path d="M16 13l1.8-1.8a1.5 1.5 0 0 1 2.2 2.1l-4.2 5.1A6 6 0 0 1 11.2 21H10a6 6 0 0 1-6-6v-3.5a1.5 1.5 0 0 1 3 0V14"/></svg></div>
            <div>
                <h2 class="mg-game-lobby-title">Rock Paper Scissors</h2>
                <p class="mg-game-lobby-subtitle">Game Idea by Aefzetaa</p>
            </div>
        </div>
        <div class="mg-game-lobby-tabs">
            <button class="mg-game-lobby-tab mg-rps-tab-btn active" type="button" data-mg-game="rps" data-mg-action="switch-tab" data-tab="play">Play</button>
            <button class="mg-game-lobby-tab mg-rps-tab-btn" type="button" data-mg-game="rps" data-mg-action="switch-tab" data-tab="shop">Store</button>
            <button class="mg-game-lobby-tab mg-rps-tab-btn" type="button" data-mg-game="rps" data-mg-action="switch-tab" data-tab="leaderboard">Leaderboard</button>
            <button class="mg-game-lobby-tab mg-rps-tab-btn" type="button" data-mg-game="rps" data-mg-action="switch-tab" data-tab="stats">Profile</button>
            <button class="mg-game-lobby-tab mg-rps-tab-btn" type="button" data-mg-game="rps" data-mg-action="switch-tab" data-tab="guide">Guide</button>
        </div>
        <div class="mg-game-lobby-actions">
            <div class="mg-game-lobby-balance"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7.5h16a1.5 1.5 0 0 1 1.5 1.5v8.5A1.5 1.5 0 0 1 20 19H4a2 2 0 0 1-2-2V6.5A2.5 2.5 0 0 1 4.5 4H18"/><path d="M16.5 13h4"/><path d="M4.5 4A2.5 2.5 0 0 0 4 9h16"/></svg><span>Rp <?php echo e(number_format(Auth::user()->balance ?? 0, 0, ',', '.')); ?></span></div>
            <button class="mg-game-lobby-exit" type="button" data-mg-game="rps" data-mg-action="exit-portal">Exit</button>
        </div>
    </div>

    <!-- Main Navigation area (hidden when playing) -->
    <div id="rps-main-view">
        <div id="rps-tab-play" class="mg-tab-content active">
            <section class="mg-game-lobby-frame">
                <div class="mg-game-lobby-main">
                    <div class="mg-game-mode-panel">
                        <h1 class="mg-game-mode-title">Which mode do you want to play?</h1>
                        <div class="mg-game-mode-buttons">
                            <button class="mg-game-mode-btn" type="button" data-mg-game="rps" data-mg-action="select-lobby-mode" data-mode="solo_duo">Solo / Duo</button>
                            <button class="mg-game-mode-btn" type="button" data-mg-game="rps" data-mg-action="select-lobby-mode" data-mode="rank" data-start="1">Ranked</button>
                            <button class="mg-game-mode-btn" type="button" data-mg-game="rps" data-mg-action="select-lobby-mode" data-mode="tournament" data-start="1">Tournament</button>
                        </div>
                        <div id="mg-rps-solo-duo-panel" class="mg-game-solo-duo-panel">
                            <button class="mg-game-submode-btn" type="button" data-mg-game="rps" data-mg-action="select-lobby-mode" data-mode="have_fun" data-start="1">Solo</button>
                            <button class="mg-game-submode-btn" type="button" data-mg-game="rps" data-mg-action="select-lobby-mode" data-mode="duo" data-start="1">Duo</button>
                        </div>
                        <select id="mg-rps-mode" style="display:none;">
                            <option value="have_fun">Solo (Kasual)</option>
                            <option value="rank">Ranked</option>
                            <option value="duo">Duo (Satu Perangkat)</option>
                            <option value="tournament">Tournament (Final BO5)</option>
                        </select>
                        <select id="mg-rps-diff" style="display:none;">
                            <option value="easy">Rival Arena</option>
                            <option value="pro">Rival Arena</option>
                            <option value="world_class">Rival Arena</option>
                        </select>
                        <label class="mg-game-autopilot-row" for="mg-rps-auto-grinding">
                            <span>Auto Pilot</span>
                            <input type="checkbox" id="mg-rps-auto-grinding">
                            <i class="mg-game-switch" aria-hidden="true"></i>
                        </label>
                    </div>
                </div>
                <footer class="mg-game-lobby-footer">
                    <div><strong>Rock Paper Scissors</strong><br>&copy; 2024 Zero Infinity Arcade Portal. All rights reserved.</div>
                    <div class="mg-game-lobby-links"><span>Privacy Policy</span><span>Terms of Service</span><span>Support</span></div>
                </footer>
            </section>
        </div>

        <div id="rps-tab-shop" class="mg-tab-content">
            <section class="mg-game-tab-surface mg-game-store-shell">
                <h2 class="mg-game-section-title">Autopilot</h2>
                <div class="mg-game-store-grid is-auto">
                    <article class="mg-game-store-card"><button class="mg-game-store-hit" type="button" data-mg-action="buy-shop-item" data-item-key="pro_50" data-item-type="autopilot" data-price="250000"><span class="mg-game-store-icon"><svg viewBox="0 0 24 24"><path d="M8 3h8l2 3v12l-2 3H8l-2-3V6l2-3Z"/><path d="M9 8h6M9 12h6M9 16h6M4 8h2M4 12h2M4 16h2M18 8h2M18 12h2M18 16h2"/></svg></span><span class="mg-game-store-name">Pro (+50)</span><span class="mg-game-store-price">Rp 250.000</span></button></article>
                    <article class="mg-game-store-card"><button class="mg-game-store-hit" type="button" data-mg-action="buy-shop-item" data-item-key="pro_200" data-item-type="autopilot" data-price="950000"><span class="mg-game-store-icon"><svg viewBox="0 0 24 24"><path d="M8 3h8l2 3v12l-2 3H8l-2-3V6l2-3Z"/><path d="M9 8h6M9 12h6M9 16h6M4 8h2M4 12h2M4 16h2M18 8h2M18 12h2M18 16h2"/></svg></span><span class="mg-game-store-name">Pro (+200)</span><span class="mg-game-store-price">Rp 950.000</span></button></article>
                    <article class="mg-game-store-card"><button class="mg-game-store-hit" type="button" data-mg-action="buy-shop-item" data-item-key="world_class_500" data-item-type="autopilot" data-price="2350000"><span class="mg-game-store-icon"><svg viewBox="0 0 24 24"><path d="M8 3h8l2 3v12l-2 3H8l-2-3V6l2-3Z"/><path d="M9 8h6M9 12h6M9 16h6M4 8h2M4 12h2M4 16h2M18 8h2M18 12h2M18 16h2"/></svg></span><span class="mg-game-store-name">World Class (+500)</span><span class="mg-game-store-price">Rp 2.350.000</span></button></article>
                    <article class="mg-game-store-card"><button class="mg-game-store-hit" type="button" data-mg-action="buy-shop-item" data-item-key="world_class_200" data-item-type="autopilot" data-price="950000"><span class="mg-game-store-icon"><svg viewBox="0 0 24 24"><path d="M8 3h8l2 3v12l-2 3H8l-2-3V6l2-3Z"/><path d="M9 8h6M9 12h6M9 16h6M4 8h2M4 12h2M4 16h2M18 8h2M18 12h2M18 16h2"/></svg></span><span class="mg-game-store-name">World Class (+200)</span><span class="mg-game-store-price">Rp 950.000</span></button></article>
                </div>
                <h2 class="mg-game-section-title">Cosmetics</h2>
                <div class="mg-game-store-grid is-cosmetic">
                    <article class="mg-game-store-card" id="shop-rps-rps_arena_cyber"><button class="mg-game-store-hit" type="button" data-mg-action="buy-shop-item" data-item-key="rps_arena_cyber" data-item-type="arena_theme" data-price="25000"><span class="mg-game-store-icon"><svg viewBox="0 0 24 24"><path d="M5 5h14v14H5z"/><path d="M9 2v3M15 2v3M9 19v3M15 19v3M2 9h3M2 15h3M19 9h3M19 15h3"/><path d="M9 12h6"/></svg></span><span class="mg-game-store-name">Cyber Arena</span><span class="mg-game-store-price">Rp 25.000</span></button></article>
                    <article class="mg-game-store-card" id="shop-rps-rps_arena_royal"><button class="mg-game-store-hit" type="button" data-mg-action="buy-shop-item" data-item-key="rps_arena_royal" data-item-type="arena_theme" data-price="25000"><span class="mg-game-store-icon"><svg viewBox="0 0 24 24"><path d="M4 9l4-4 4 4 4-4 4 4v8H4z"/><path d="M4 17h16M8 13h8"/></svg></span><span class="mg-game-store-name">Royal Court</span><span class="mg-game-store-price">Rp 25.000</span></button></article>
                </div>
            </section>
        </div>

        <div id="rps-tab-leaderboard" class="mg-tab-content">
            <section class="mg-game-tab-surface mg-game-rankings-shell">
                <h1 class="mg-game-rankings-title">Grand Arena Rankings</h1>
                <p class="mg-game-rankings-kicker">Game Idea by Aefzetaa</p>
                <div class="mg-game-podium" aria-hidden="true">
                    <div class="mg-game-podium-card"><span class="mg-game-podium-rank">2</span><strong>Runner Up</strong><small>Rival terbaik kedua</small></div>
                    <div class="mg-game-podium-card is-champion"><span class="mg-game-podium-rank">1</span><strong>Champion</strong><small>Pemuncak arena</small></div>
                    <div class="mg-game-podium-card"><span class="mg-game-podium-rank">3</span><strong>Third Place</strong><small>Masuk podium</small></div>
                </div>
                <div class="mg-game-rank-list">
                    <table class="mg-leaderboard-table">
                        <thead><tr><th>#</th><th>Player</th><th>Rank</th><th>Stars</th><th>Total Reward</th></tr></thead>
                        <tbody id="rps-leaderboard-body"><!-- Injected by JS --></tbody>
                    </table>
                </div>
            </section>
        </div>

        <div id="rps-tab-stats" class="mg-tab-content">
            <section class="mg-game-tab-surface">
                <div class="mg-game-profile-card">
                    <h2 class="mg-game-section-title">Rock Paper Scissors Profile</h2>
                    <div class="mg-game-profile-grid">
                        <div class="mg-game-profile-stat"><span>Rank</span><strong id="mg-rps-rank">Loading...</strong></div>
                        <div class="mg-game-profile-stat"><span>Stars</span><strong id="mg-rps-stars">0</strong></div>
                        <div class="mg-game-profile-stat"><span>Win / Loss / Draw</span><strong id="mg-rps-wld">0 / 0 / 0</strong></div>
                        <div class="mg-game-profile-stat"><span>Autopilot</span><strong id="mg-rps-autopilot">0 / 0 / 0</strong></div>
                    </div>
                </div>
            </section>
        </div>

        <div id="rps-tab-guide" class="mg-tab-content">
            <section class="mg-game-tab-surface mg-game-guide-shell">
                <div>
                    <h1 class="mg-game-guide-title">Master the Arena</h1>
                    <p class="mg-game-guide-lead">Pertarungan cepat berbasis prediksi, tempo, dan momentum. Pilih tangan yang tepat saat tekanan naik.</p>
                    <div class="mg-game-guide-steps">
                        <div class="mg-game-guide-step is-active"><span class="mg-game-guide-num">01</span><div><strong>Getting Started</strong><span>Pilih mode dan siapkan ritme bermain Anda.</span></div></div>
                        <div class="mg-game-guide-step"><span class="mg-game-guide-num">02</span><div><strong>Choosing a Mode</strong><span>Solo/Duo, Ranked, dan Tournament punya tempo berbeda.</span></div></div>
                        <div class="mg-game-guide-step"><span class="mg-game-guide-num">03</span><div><strong>Using Support</strong><span>Gunakan autopilot atau hint hanya di mode yang mendukung.</span></div></div>
                        <div class="mg-game-guide-step"><span class="mg-game-guide-num">04</span><div><strong>Climbing the Ranks</strong><span>Menang di mode kompetitif untuk menaikkan reputasi arena.</span></div></div>
                    </div>
                </div>
                <div class="mg-game-guide-card mg-game-guide-visual">
                    <div class="mg-game-guide-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 11V6.5a1.5 1.5 0 0 1 3 0V11"/><path d="M10 11V5.5a1.5 1.5 0 0 1 3 0V11"/><path d="M13 11V7a1.5 1.5 0 0 1 3 0v6"/><path d="M16 13l1.8-1.8a1.5 1.5 0 0 1 2.2 2.1l-4.2 5.1A6 6 0 0 1 11.2 21H10a6 6 0 0 1-6-6v-3.5a1.5 1.5 0 0 1 3 0V14"/></svg></div>
                    <h3>Read the Rival</h3>
                    <p>Batu, kertas, dan gunting terlihat sederhana, tetapi ritme pilihan akan menentukan siapa yang memegang momentum.</p>
                </div>
            </section>
        </div>

        <!-- Arena Area (Hidden initially) -->
    <div id="rps-arena-view" style="display:none;">
        <div style="display:flex; gap:1rem; margin-bottom:2rem; flex-wrap:wrap;">
            <button class="mg-btn mg-btn-secondary" type="button" data-mg-game="rps" data-mg-action="exit-lobby">Kembali ke Lobby</button>
            <button class="mg-btn mg-btn-secondary" style="border-color:var(--mg-warning); color:var(--mg-warning);" type="button" data-mg-game="rps" data-mg-action="toggle-autopilot" id="mg-rps-arena-autopilot">Aktifkan Autopilot</button>
            <button class="mg-btn mg-btn-secondary" style="border-color:var(--mg-danger); color:var(--mg-danger);" type="button" data-mg-game="rps" data-mg-action="surrender">Menyerah</button>
        </div>
        
        <div class="mg-tournament-bracket" id="mg-rps-bracket">
            <!-- Bracket injected via JS -->
        </div>

        <div class="mg-rps-arena-container">
            <!-- Absolute Giant Glow Countdown text inside center of arena container -->
            <div id="mg-rps-countdown-overlay" style="display: none; position: absolute; top: 40%; left: 50%; transform: translate(-50%, -50%); font-size: 6rem; font-weight: 900; color: var(--mg-danger); text-shadow: 0 0 30px var(--mg-danger); pointer-events: none; z-index: 10;">3</div>

            <div id="mg-rps-status" class="mg-rps-status">Siap Bermain?</div>
            <div id="mg-rps-substatus" class="mg-rps-substatus">Pilih Mode dan klik Mulai</div>
            
            <div class="mg-rps-arena">
                <div class="mg-rps-player">
                    <span class="mg-rps-name" id="mg-rps-p1-name">Anda</span>
                    <div id="mg-rps-player-hand" class="mg-rps-hand-display"></div>
                </div>
                
                <div style="display:flex; align-items:center; font-size:2.5rem; font-weight:800; color:var(--mg-text-muted);">
                    VS
                </div>

                <div class="mg-rps-bot">
                    <span class="mg-rps-name" id="mg-rps-p2-name">Rival Arena</span>
                    <div id="mg-rps-bot-hand" class="mg-rps-hand-display"></div>
                </div>
            </div>

            <div class="mg-rps-controls" id="mg-rps-controls-p1">
                <button class="mg-rps-btn" type="button" data-mg-game="rps" data-mg-action="make-move" data-move="rock"></button>
                <button class="mg-rps-btn" type="button" data-mg-game="rps" data-mg-action="make-move" data-move="paper"></button>
                <button class="mg-rps-btn" type="button" data-mg-game="rps" data-mg-action="make-move" data-move="scissors"></button>
            </div>
            <div class="mg-duo-hint" id="mg-rps-duo-hint">
                Duo Mode: Player 1 (Kiri) gunakan tombol A/S/D. Player 2 (Kanan) gunakan J/K/L.
            </div>
            <div id="mg-rps-result-panel" class="mg-result-panel" aria-live="polite"></div>
        </div>
    </div>
</div>

<script>
window.MgRps = {
    isPlaying: false,
    currentMode: 'have_fun', 
    leaderboardData: [],
    currentOpponent: 'Rival Arena',
    
    playerWins: 0,
    botWins: 0,
    targetWins: 3, // Default BO5 (First to 3 wins)
    roundCount: 1,

    duoState: { p1Move: null, p2Move: null, p1Wins: 0, p2Wins: 0, target: 2 },
    tourneyState: { stage: 0, wins: 0, losses: 0, target: 1, active: false, players: [] },
    isAutoGrinding: false,
    isSpectating: false,
    specInterval: null,
    specRoundWinsP1: 0,
    specRoundWinsP2: 0,
    specTargetWins: 1,

    async init() {
        this.resetArena();
        try {
            await this.safeLoadLobby();
        } catch (error) {
            console.error('RPS lobby load failed:', error);
            MgCore.toast('Lobby Rock Paper Scissors terbuka, tetapi data arena belum siap.');
        }
        if (this.handleDuoKeysBound) document.removeEventListener('keydown', this.handleDuoKeysBound);
        this.handleDuoKeysBound = this.handleDuoKeys.bind(this);
        document.addEventListener('keydown', this.handleDuoKeysBound);
        window.renderGameShop = () => this.renderShopUI();

        const modeSelect = document.getElementById('mg-rps-mode');
        const diffSelect = document.getElementById('mg-rps-diff');
        if (modeSelect && diffSelect) {
            diffSelect.style.display = modeSelect.value === 'have_fun' ? 'block' : 'none';
            if (!this.modeChangeBound) {
                modeSelect.addEventListener('change', function() {
                    diffSelect.style.display = this.value === 'have_fun' ? 'block' : 'none';
                });
                this.modeChangeBound = true;
            }
        }
    },

    exitArenaAndGoHome() {
        this.isAutoGrinding = false;
        this.exitArena();
        MgCore.navigate('portal');
    },

    switchTab(tabId, btnElement) {
        document.querySelectorAll('#rps-main-view .mg-tab-content').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('#rps-main-view .mg-rps-tab-btn').forEach(el => el.classList.remove('active'));
        document.getElementById('rps-tab-' + tabId).classList.add('active');
        if (btnElement) btnElement.classList.add('active');
    },

    renderShopUI() {
        const inv = window.MgUserInventory || [];
        ['rps_arena_cyber', 'rps_arena_royal'].forEach(itemKey => {
            if (inv.includes(itemKey)) {
                const el = document.getElementById('shop-rps-' + itemKey);
                if (el) el.querySelector('button').outerHTML = `<div class="mg-shop-item-owned">Dimiliki </div>`;
            }
        });
    },

    async loadLobby() {
        const res = await MgCore.apiGet('/rps-minigame/lobby-data');
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
            console.error('RPS lobby refresh failed:', error);
            MgCore.toast('Data arena belum sempat diperbarui, tetapi layar tetap bisa dipakai.');
        }
    },
    updateStats(player) {
        document.getElementById('mg-rps-rank').innerText = player.profile.rank.replace(/_/g, ' ');
        document.getElementById('mg-rps-stars').innerText = player.profile.stars;
        document.getElementById('mg-rps-wld').innerText = `${player.profile.win_count} / ${player.profile.loss_count} / ${player.profile.draw_count}`;
        document.getElementById('mg-rps-autopilot').innerText = `${player.autopilot.free} / ${player.autopilot.pro} / ${player.autopilot.world_class}`;
        MgCore.updateBalance(player.balance);
    },

    renderLeaderboard() {
        const tbody = document.getElementById('rps-leaderboard-body');
        if (!tbody) return;
        tbody.innerHTML = '';
        this.leaderboardData.slice(0, 20).forEach((entry, idx) => {
            const style = 'color: var(--mg-primary); font-weight:bold;';
            tbody.innerHTML += `
                <tr style="${style}">
                    <td>#${idx + 1}</td>
                    <td>${entry.username || entry.name}</td>
                    <td>${entry.rank.replace(/_/g, ' ')}</td>
                    <td> ${entry.stars}</td>
                    <td>Rp ${Number(entry.reward_total || 0).toLocaleString('id-ID')}</td>
                </tr>
            `;
        });
    },
    
    resetArena() {
        document.getElementById('mg-rps-player-hand').innerText = '';
        document.getElementById('mg-rps-bot-hand').innerText = '';
        this.setStatus('Siap Bermain?', 'Pilih Mode dan klik Mulai');
    },
    
    getEmoji(move) {
        if(move === 'rock') return '';
        if(move === 'paper') return '';
        if(move === 'scissors') return '';
        return '';
    },
    
    setStatus(text, sub = "") {
        document.getElementById('mg-rps-status').innerText = text;
        document.getElementById('mg-rps-substatus').innerText = sub;
    },
    pickRegisteredOpponent() {
        const currentUser = '<?php echo e(Auth::user()->username ?: Auth::user()->name); ?>'.toLowerCase();
        const names = (this.leaderboardData || [])
            .map(entry => entry.username || entry.name)
            .filter(name => name && name.toLowerCase() !== currentUser);
        return names.length ? names[Math.floor(Math.random() * names.length)] : 'Penantang Arena';
    },
    async startGameRouter() {
        const mode = document.getElementById('mg-rps-mode').value;
        const autoGrindingActive = document.getElementById('mg-rps-auto-grinding').checked;
        this.currentMode = mode;
        this.isAutoGrinding = autoGrindingActive;

        if (this.isAutoGrinding && mode === 'duo') {
            await window.showAlert('Autopilot Tidak Didukung', 'Autopilot tidak tersedia untuk mode Duo.');
            this.isAutoGrinding = false;
            document.getElementById('mg-rps-auto-grinding').checked = false;
            return;
        }

        document.getElementById('rps-main-view').style.display = 'none';
        document.getElementById('rps-arena-view').style.display = 'block';
        document.getElementById('mg-rps-bracket').classList.remove('active');

        // Matchmaking screen animation
        const opponentName = this.pickRegisteredOpponent();
        this.currentOpponent = opponentName;
        if (mode === 'rank' || mode === 'tournament' || mode === 'have_fun') {
            this.showMatchmaking(opponentName, async () => {
                // Semua mode arcade sekarang bebas biaya masuk.
                if (this.isAutoGrinding) {
                        const quota = await MgCore.apiCall('/rps-minigame/consume-autopilot-quota');
                        if (!quota.success) {
                            await window.showAlert('Autopilot Berhenti', quota.message || 'Kuota Autopilot Anda habis.');
                            this.isAutoGrinding = false;
                            document.getElementById('mg-rps-auto-grinding').checked = false;
                            this.exitArena();
                            return;
                        }
                        this.updateStats({ profile: quota.profile, balance: quota.new_balance, autopilot: { free: quota.remaining_free, pro: quota.remaining_pro, world_class: quota.remaining_world_class } });
                    }

                if (mode === 'tournament') {
                    this.initTournament();
                } else {
                    if (mode === 'have_fun') {
                        // Set random difficulty for Solo Kasual behind the scene
                        const difficulties = ['easy', 'pro', 'world_class'];
                        document.getElementById('mg-rps-diff').value = difficulties[Math.floor(Math.random() * difficulties.length)];
                    }
                    
                    // Reset wins for match
                    this.playerWins = 0;
                    this.botWins = 0;
                    this.targetWins = 3; // BO5
                    this.roundCount = 1;
                    this.startSoloRound();
                }
            });
        } else {
            // Duo Mode
            this.duoState = { p1Move: null, p2Move: null, p1Wins: 0, p2Wins: 0, target: 2 };
            this.startDuoRound();
        }
    },

    showMatchmaking(opponentName, callback) {
        document.getElementById('mg-rps-opponent-name').innerText = opponentName;
        document.getElementById('mg-rps-matchmaking').style.display = 'flex';
        const bar = document.getElementById('mg-rps-matchmaking-bar');
        bar.style.transition = 'none';
        bar.style.width = '0%';
        
        setTimeout(() => {
            bar.style.transition = 'width 3s ease-in-out';
            bar.style.width = '100%';
        }, 50);

        setTimeout(() => {
            document.getElementById('mg-rps-matchmaking').style.display = 'none';
            callback();
        }, 3200);
    },

    async runAutoGrinding(opponentName) {
        document.getElementById('mg-rps-opponent-name').innerText = opponentName;
        document.getElementById('mg-rps-matchmaking').style.display = 'flex';
        const bar = document.getElementById('mg-rps-matchmaking-bar');
        bar.style.transition = 'none';
        bar.style.width = '0%';

        setTimeout(() => {
            bar.style.transition = 'width 3s ease-in-out';
            bar.style.width = '100%';
        }, 50);

        // Run Autopilot in parallel
        const autopilotPromise = MgCore.apiCall('/rps-minigame/use-autopilot');

        setTimeout(async () => {
            const res = await autopilotPromise;
            document.getElementById('mg-rps-matchmaking').style.display = 'none';

            if (!res.success) {
                await window.showAlert('Autopilot Terhenti', 'Autopilot Terhenti: ' + (res.message || 'Kesalahan Autopilot'));
                this.isAutoGrinding = false;
                document.getElementById('mg-rps-auto-grinding').checked = false;
                this.exitArena();
                return;
            }

            // Reset hands to 
            document.getElementById('mg-rps-player-hand').innerText = '';
            document.getElementById('mg-rps-bot-hand').innerText = '';
            
            let msg = res.result === 'win' ? "Anda Menang (Autopilot)!" : (res.result === 'loss' ? "Rival Menang (Autopilot)!" : "Seri!");
            this.setStatus(msg, `Reward: Rp ${Number(res.reward_amount ?? 0).toLocaleString('id-ID')} | Sisa Kuota Autopilot: F:${res.remaining_free} P:${res.remaining_pro} WC:${res.remaining_world_class}`);
            
            if (res.rank_reward > 0) {
                MgCore.toast(`Hadiah Naik Rank: Rp ${res.rank_reward.toLocaleString('id-ID')} ditambahkan ke ZeroPay!`);
            }
            if (res.rank_up) {
                MgCore.toast("Naik Rank!");
            }

            if (document.getElementById('portal-entry-display')) {
                if (res.new_balance !== undefined) MgCore.updateBalance(res.new_balance);
            }

            await this.safeLoadLobby();

            if (this.isAutoGrinding && document.getElementById('mg-rps-auto-grinding').checked) {
                let countdown = 3;
                const interval = setInterval(() => {
                    this.setStatus(msg, `Autopilot: Mencari lawan berikutnya dalam ${countdown}...`);
                    countdown--;
                    if (countdown < 0) {
                        clearInterval(interval);
                        if (this.isAutoGrinding && document.getElementById('mg-rps-auto-grinding').checked) {
                            const nextOpponent = this.pickRegisteredOpponent();
                            this.runAutoGrinding(nextOpponent);
                        }
                    }
                }, 1000);
            }
        }, 3200);
    },

    exitArena() {
        this.isPlaying = false;
        this.isAutoGrinding = false;
        this.hideResultPanel();
        document.getElementById('rps-arena-view').style.display = 'none';
        document.getElementById('rps-main-view').style.display = 'block';
        this.resetArena();
    },

    startSoloRound() {
        this.hideResultPanel();
        this.isPlaying = true;
        document.getElementById('mg-rps-player-hand').innerText = '';
        document.getElementById('mg-rps-bot-hand').innerText = '';
        document.getElementById('mg-rps-controls-p1').style.opacity = '1';
        document.getElementById('mg-rps-controls-p1').style.pointerEvents = 'all';

        document.getElementById('mg-rps-p1-name').innerText = 'Anda';
        document.getElementById('mg-rps-p2-name').innerText = this.currentOpponent;

        this.setStatus(`Ronde ${this.roundCount}`, `Skor: Anda ${this.playerWins} - ${this.botWins} vs ${this.currentOpponent} (BO5)`);
        this.scheduleAutopilotMove();
    },

    async makeMove(playerMove) {
        if (!this.isPlaying) return;
        this.isPlaying = false;

        // Disable controls
        document.getElementById('mg-rps-controls-p1').style.opacity = '0.5';
        document.getElementById('mg-rps-controls-p1').style.pointerEvents = 'none';

        // Add shake animations
        const pHand = document.getElementById('mg-rps-player-hand');
        const bHand = document.getElementById('mg-rps-bot-hand');
        pHand.innerText = '';
        bHand.innerText = '';
        pHand.classList.add('shaking-left');
        bHand.classList.add('shaking-right');

        // Start flashing countdown sound/visual
        const countdownOverlay = document.getElementById('mg-rps-countdown-overlay');
        countdownOverlay.style.display = 'block';
        
        let count = 3;
        countdownOverlay.innerText = count;

        const countdownInterval = setInterval(() => {
            count--;
            if (count > 0) {
                countdownOverlay.innerText = count;
            } else if (count === 0) {
                countdownOverlay.innerText = 'BUKA!';
            } else {
                clearInterval(countdownInterval);
                countdownOverlay.style.display = 'none';
            }
        }, 700);

        // Fetch AI move in parallel
        let diff = document.getElementById('mg-rps-diff').value;
        if (this.currentMode === 'rank') {
            diff = 'world_class';
        } else if (this.currentMode === 'tournament') {
            diff = this.tourneyState.stage === 3 ? 'world_class' : 'pro';
        }
        
        const aiPromise = MgCore.apiCall('/rps-minigame/play', { difficulty: diff });

        setTimeout(async () => {
            // Remove shaking
            pHand.classList.remove('shaking-left');
            bHand.classList.remove('shaking-right');

            const res = await aiPromise;
            if (res.status !== 'success') {
                MgCore.toast(res.message || 'Gerakan rival belum siap. Coba pilih lagi.');
                this.setStatus('Rival belum siap', 'Pilih ulang batu, kertas, atau gunting.');
                return;
            }

            if (res.status === 'success') {
                const botMove = res.move;
                pHand.innerText = this.getEmoji(playerMove);
                bHand.innerText = this.getEmoji(botMove);

                let result = 'draw';
                if (
                    (playerMove === 'rock' && botMove === 'scissors') ||
                    (playerMove === 'paper' && botMove === 'rock') ||
                    (playerMove === 'scissors' && botMove === 'paper')
                ) {
                    result = 'win';
                } else if (playerMove !== botMove) {
                    result = 'loss';
                }

                // Process round result
                if (result === 'win') {
                    this.playerWins++;
                    this.setStatus("Anda Menang Ronde ini!", `Skor: Anda ${this.playerWins} - ${this.botWins} Rival`);
                } else if (result === 'loss') {
                    this.botWins++;
                    this.setStatus("Rival Menang Ronde ini!", `Skor: Anda ${this.playerWins} - ${this.botWins} Rival`);
                } else {
                    this.setStatus("Ronde Seri! (Diulang)", `Skor: Anda ${this.playerWins} - ${this.botWins} Rival`);
                }

                setTimeout(() => {
                    if (this.playerWins >= this.targetWins) {
                        this.endAiMatch('win');
                    } else if (this.botWins >= this.targetWins) {
                        this.endAiMatch('loss');
                    } else {
                        if (result !== 'draw') {
                            this.roundCount++;
                        }
                        this.startSoloRound();
                    }
                }, 2000);

            } else {
                await window.showAlert('Kesalahan', 'Kesalahan komunikasi server.');
                this.isPlaying = true;
                document.getElementById('mg-rps-controls-p1').style.opacity = '1';
                document.getElementById('mg-rps-controls-p1').style.pointerEvents = 'all';
            }
        }, 2200);
    },

    async endAiMatch(result) {
        this.isPlaying = false;
        
        if (this.currentMode === 'tournament') {
            if (result === 'win') {
                if (this.tourneyState.stage === 1) {
                    this.tourneyState.stage = 3;
                    this.renderBracket();
                    this.setStatus("Lolos ke Final! ", "Memulai pertandingan final...");
                    setTimeout(() => {
                        this.playerWins = 0;
                        this.botWins = 0;
                        this.targetWins = 3; // BO5 for Final
                        this.roundCount = 1;
                        this.startTournamentRound();
                    }, 2000);
                } else if (this.tourneyState.stage === 3) {
                    this.setStatus("Juara Turnamen!", "Selamat! Reward turnamen sedang diproses.");
                    this.submitBackendResult('tournament', 'win');
                }
            } else {
                this.setStatus("Tereliminasi!", "Game Over.");
                this.submitBackendResult('tournament', 'loss');
            }
            return;
        }

        let msg = result === 'win' ? "Menang Pertandingan! " : "Kalah Pertandingan!";
        this.setStatus(msg, "Menyimpan hasil ke database...");
        this.submitBackendResult(this.currentMode, result);
    },

    async submitBackendResult(mode, result) {
        let backendMode = mode === 'rank' ? 'rank' : (mode === 'tournament' ? 'tournament' : 'have_fun');
        let diff = document.getElementById('mg-rps-diff').value;
        if (mode === 'rank') diff = 'world_class';
        else if (mode === 'tournament') diff = 'world_class';

        const res = await MgCore.apiCall('/rps-minigame/match-result', {
            game_mode: backendMode,
            difficulty: diff,
            result: result
        });

        if (res.status !== 'success') {
            MgCore.toast(res.message || 'Hasil belum bisa disimpan. Arena tetap bisa dilanjutkan.');
            this.showResultPanel(mode, result, { reward_amount: 0, new_balance: MgCore.balance, message: res.message || 'Hasil belum bisa disimpan.' });
            return;
        }

        if (res.status === 'success') {
            if (Number(res.reward_amount ?? res.reward ?? 0) > 0) MgCore.toast(`Mendapatkan Rp ${Number(res.reward_amount ?? res.reward ?? 0).toLocaleString('id-ID')}!`);
            if (res.rank_up) MgCore.toast("Naik Rank!");
            if (res.new_balance !== undefined) {
                MgCore.updateBalance(res.new_balance);
            }
            this.updateStats({ profile: res.profile, balance: res.new_balance, autopilot: { free:0,pro:0,world_class:0 } });
            await this.safeLoadLobby();
            if (this.isAutoGrinding) {
                this.exitArena();
                setTimeout(() => this.startGameRouter(), 900);
                return;
            }
            this.showResultPanel(mode, result, res);
        }
    },


    hideResultPanel() {
        const panel = document.getElementById('mg-rps-result-panel');
        if (!panel) return;
        panel.classList.remove('active');
        panel.style.display = 'none';
    },

    formatModeLabel(mode) {
        return ({ have_fun: 'Have Fun Solo', rank: 'Ranked', tournament: 'Tournament', duo: 'Duo' }[mode] || mode || 'Arena');
    },

    showResultPanel(mode, result, payload = {}) {
        const panel = document.getElementById('mg-rps-result-panel');
        if (!panel) return;
        const reward = Number(payload.reward_amount ?? payload.reward ?? 0);
        const balance = payload.new_balance !== undefined ? `Saldo sekarang Rp ${Number(payload.new_balance).toLocaleString('id-ID')}.` : '';
        const resultText = result === 'win' ? 'Anda Menang' : (result === 'loss' ? 'Anda Kalah' : 'Seri');
        const note = mode === 'duo'
            ? 'Mode Duo adalah hiburan bersama, tanpa reward dan tanpa riwayat reward.'
            : (reward > 0 ? `Reward Rp ${reward.toLocaleString('id-ID')} sudah masuk ke ZeroPay.` : 'Tidak ada reward untuk hasil ini.');
        panel.innerHTML = `
            <div class="mg-result-kicker">${this.formatModeLabel(mode)}</div>
            <h3 class="mg-result-title">${resultText}</h3>
            <p class="mg-result-copy">${note} ${balance}</p>
            ${reward > 0 ? `<div class="mg-result-reward">Rp ${reward.toLocaleString('id-ID')}</div>` : ''}
            <div class="mg-result-actions">
                <button class="mg-btn mg-btn-primary" type="button" data-mg-game="rps" data-mg-action="result-continue">Lanjut Main</button>
                <button class="mg-btn mg-btn-secondary" type="button" data-mg-game="rps" data-mg-action="result-lobby">Kembali ke Lobby</button>
                <button class="mg-btn mg-btn-secondary" type="button" data-mg-game="rps" data-mg-action="result-portal">Kembali ke Portal</button>
            </div>`;
        panel.style.display = 'block';
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

    startDuoRound() {
        this.hideResultPanel();
        this.isPlaying = true;
        this.duoState.p1Move = null;
        this.duoState.p2Move = null;
        document.getElementById('mg-rps-player-hand').innerText = '';
        document.getElementById('mg-rps-bot-hand').innerText = '';
        this.setStatus("Pilih Pilihan Anda", `Skor P1: ${this.duoState.p1Wins} - ${this.duoState.p2Wins} P2 (BO3)`);
    },

    handleDuoKeys(e) {
        if (!this.isPlaying || this.currentMode !== 'duo') return;
        const key = e.key.toLowerCase();
        
        if (['a','s','d'].includes(key) && !this.duoState.p1Move) {
            this.duoState.p1Move = key === 'a' ? 'rock' : (key === 's' ? 'paper' : 'scissors');
            document.getElementById('mg-rps-player-hand').innerText = '';
        }
        
        if (['j','k','l'].includes(key) && !this.duoState.p2Move) {
            this.duoState.p2Move = key === 'j' ? 'rock' : (key === 'k' ? 'paper' : 'scissors');
            document.getElementById('mg-rps-bot-hand').innerText = '';
        }
        
        if (this.duoState.p1Move && this.duoState.p2Move) {
            this.resolveDuoRound();
        }
    },

    resolveDuoRound() {
        this.isPlaying = false;
        
        // Shake animation
        const pHand = document.getElementById('mg-rps-player-hand');
        const bHand = document.getElementById('mg-rps-bot-hand');
        pHand.innerText = '';
        bHand.innerText = '';
        pHand.classList.add('shaking-left');
        bHand.classList.add('shaking-right');

        setTimeout(() => {
            pHand.classList.remove('shaking-left');
            bHand.classList.remove('shaking-right');

            const p1 = this.duoState.p1Move;
            const p2 = this.duoState.p2Move;
            
            pHand.innerText = this.getEmoji(p1);
            bHand.innerText = this.getEmoji(p2);
            
            let winner = 'draw';
            if (
                (p1 === 'rock' && p2 === 'scissors') ||
                (p1 === 'paper' && p2 === 'rock') ||
                (p1 === 'scissors' && p2 === 'paper')
            ) {
                winner = 'p1';
                this.duoState.p1Wins++;
            } else if (p1 !== p2) {
                winner = 'p2';
                this.duoState.p2Wins++;
            }
            
            if (this.duoState.p1Wins >= this.duoState.target) {
                this.setStatus("Player 1 (Kiri) MENANG LAGA! ", `Skor Akhir: ${this.duoState.p1Wins} - ${this.duoState.p2Wins}`);
                this.showResultPanel('duo', 'draw', { reward_amount: 0, new_balance: MgCore.balance });
            } else if (this.duoState.p2Wins >= this.duoState.target) {
                this.setStatus("Player 2 (Kanan) MENANG LAGA! ", `Skor Akhir: ${this.duoState.p1Wins} - ${this.duoState.p2Wins}`);
                this.showResultPanel('duo', 'draw', { reward_amount: 0, new_balance: MgCore.balance });
            } else {
                this.setStatus(winner === 'draw' ? "Seri!" : (winner === 'p1' ? "P1 Menang Ronde!" : "P2 Menang Ronde!"), `Skor: ${this.duoState.p1Wins} - ${this.duoState.p2Wins}`);
                setTimeout(() => this.startDuoRound(), 2000);
            }
        }, 1500);
    },

    async initTournament() {
        const res = await MgCore.apiCall('/arcade/tournament-start', { game: 'rps' });
        let players = ['Anda', 'Penantang A', 'Penantang B', 'Penantang C'];
        if (res.status === 'success' && res.players && res.players.length >= 4) {
            players = res.players;
        }

        this.tourneyState = { stage: 1, wins: 0, losses: 0, target: 1, active: true, players: players };
        document.getElementById('mg-rps-bracket').classList.add('active');
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
        document.getElementById('mg-rps-spectator').style.display = 'flex';
        document.getElementById('mg-rps-spectator-sub').innerText = `Semi-Final: ${p[2]} (X) vs ${p[3]} (O)`;
        
        this.specRoundWinsP1 = 0;
        this.specRoundWinsP2 = 0;
        this.specTargetWins = 1; // Best of 1 for Semi-final
        
        document.getElementById('mg-rps-spec-status').innerText = "Penantang bersiap bertanding...";

        const moves = ['rock', 'paper', 'scissors'];

        this.specInterval = setInterval(() => {
            if (!this.isSpectating) return;

            const pHand = document.getElementById('mg-rps-spec-p1-hand');
            const bHand = document.getElementById('mg-rps-spec-p2-hand');
            
            pHand.innerText = '';
            bHand.innerText = '';
            pHand.classList.add('shaking-left');
            bHand.classList.add('shaking-right');

            setTimeout(() => {
                if (!this.isSpectating) return;
                pHand.classList.remove('shaking-left');
                bHand.classList.remove('shaking-right');

                const p1Move = moves[Math.floor(Math.random() * 3)];
                const p2Move = moves[Math.floor(Math.random() * 3)];

                pHand.innerText = this.getEmoji(p1Move);
                bHand.innerText = this.getEmoji(p2Move);

                let winner = 'draw';
                if (
                    (p1Move === 'rock' && p2Move === 'scissors') ||
                    (p1Move === 'paper' && p2Move === 'rock') ||
                    (p1Move === 'scissors' && p2Move === 'paper')
                ) {
                    winner = 'p1';
                    this.specRoundWinsP1++;
                } else if (p1Move !== p2Move) {
                    winner = 'p2';
                    this.specRoundWinsP2++;
                }

                if (winner === 'p1') {
                    document.getElementById('mg-rps-spec-status').innerText = `${p[2]} Menang Ronde!`;
                } else if (winner === 'p2') {
                    document.getElementById('mg-rps-spec-status').innerText = `${p[3]} Menang Ronde!`;
                } else {
                    document.getElementById('mg-rps-spec-status').innerText = "Seri! Mengulang ronde...";
                }

                if (this.specRoundWinsP1 >= this.specTargetWins) {
                    clearInterval(this.specInterval);
                    setTimeout(() => this.finishSpectating(p[2]), 1500);
                } else if (this.specRoundWinsP2 >= this.specTargetWins) {
                    clearInterval(this.specInterval);
                    setTimeout(() => this.finishSpectating(p[3]), 1500);
                }
            }, 1200);

        }, 3000);
    },

    async confirmExitSpectator() {
        const p = this.tourneyState.players || ['Anda', 'Penantang A', 'Penantang B', 'Penantang C'];
        const ok = await window.showConfirm('Keluar Pantau', 'Apakah Anda yakin ingin keluar dari Pantau? Hasil pertandingan rival akan ditentukan cepat.');
        if (ok) {
            clearInterval(this.specInterval);
            this.finishSpectating(Math.random() > 0.5 ? p[2] : p[3]);
        }
    },

    async finishSpectating(winner) {
        this.isSpectating = false;
        this.tourneyState.winnerOfSemi2 = winner;
        document.getElementById('mg-rps-spectator').style.display = 'none';
        await window.showAlert('Lolos ke Final', `${winner} berhasil lolos ke Final!`);
        
        // Setup final player state
        this.playerWins = 0;
        this.botWins = 0;
        this.targetWins = 3; // BO5 for Final
        this.roundCount = 1;
        this.startTournamentRound();
    },

    startTournamentRound() {
        this.hideResultPanel();
        this.isPlaying = true;
        document.getElementById('mg-rps-player-hand').innerText = '';
        document.getElementById('mg-rps-bot-hand').innerText = '';
        document.getElementById('mg-rps-controls-p1').style.opacity = '1';
        document.getElementById('mg-rps-controls-p1').style.pointerEvents = 'all';

        const p = this.tourneyState.players || ['Anda', 'Penantang A', 'Penantang B', 'Penantang C'];
        const opponent = this.tourneyState.stage === 3 ? (this.tourneyState.winnerOfSemi2 || p[2]) : p[1];

        document.getElementById('mg-rps-p1-name').innerText = p[0];
        document.getElementById('mg-rps-p2-name').innerText = opponent;

        if (this.tourneyState.stage === 3) {
            this.setStatus(`Final: Ronde ${this.roundCount}`, `Skor Final: ${p[0]} ${this.playerWins} - ${this.botWins} ${opponent} (BO5)`);
        } else {
            this.setStatus("Semi-Final", `${p[0]} vs ${opponent} (BO1)`);
            this.targetWins = 1; // BO1 for player semi-final
        }
    },

    renderBracket() {
        const b = document.getElementById('mg-rps-bracket');
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

    enableAutopilotFromLobby() {
        const box = document.getElementById('mg-rps-auto-grinding');
        if (box) box.checked = true;
        this.startGameRouter();
    },

    async toggleAutopilot() {
        if (this.currentMode === 'duo') {
            await window.showAlert('Tidak Didukung', 'Autopilot tidak tersedia untuk mode Duo.');
            return;
        }
        this.isAutoGrinding = !this.isAutoGrinding;
        const box = document.getElementById('mg-rps-auto-grinding');
        if (box) box.checked = this.isAutoGrinding;
        const btn = document.getElementById('mg-rps-arena-autopilot');
        if (btn) btn.innerText = this.isAutoGrinding ? 'Autopilot Aktif' : 'Aktifkan Autopilot';
        this.scheduleAutopilotMove();
    },

    scheduleAutopilotMove() {
        if (!this.isAutoGrinding || !this.isPlaying || this.currentMode === 'duo') return;
        const moves = ['rock', 'paper', 'scissors'];
        setTimeout(() => {
            if (!this.isAutoGrinding || !this.isPlaying || this.currentMode === 'duo') return;
            this.makeMove(moves[Math.floor(Math.random() * moves.length)]);
        }, 800);
    },

    async surrenderMatch() {
        if (!this.isPlaying || this.currentMode === 'duo') {
            this.exitArena();
            return;
        }
        let ok = false;
        try {
            ok = await window.showConfirm('Menyerah?', 'Menyerah dihitung kalah resmi. Reward tidak diberikan dan rank dapat turun.', 'Menyerah', 'Lanjut Main');
        } catch (error) {
            console.error('Surrender confirm failed:', error);
            MgCore.toast('Konfirmasi belum siap. Coba sekali lagi.');
            return;
        }
        if (!ok) return;
        this.isPlaying = false;
        this.isAutoGrinding = false;
        const box = document.getElementById('mg-rps-auto-grinding');
        if (box) box.checked = false;
        this.setStatus('Anda Menyerah', 'Hasil disimpan sebagai kekalahan match.');
        try {
            await this.submitBackendResult(this.currentMode, 'loss');
        } catch (error) {
            console.error('Surrender submit failed:', error);
            this.showResultPanel(this.currentMode, 'loss', { reward_amount: 0, new_balance: MgCore.balance, message: 'Hasil menyerah belum tersimpan.' });
        }
    },
    async useAutopilot() {
        const mode = document.getElementById('mg-rps-mode').value;
        if (mode === 'duo') {
            await window.showAlert('Tidak Didukung', 'Autopilot tidak tersedia untuk mode Duo.');
            return;
        }

        MgCore.toast("Autopilot berjalan...");
        const res = await MgCore.apiCall('/rps-minigame/use-autopilot');
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






<?php /**PATH C:\laragon\www\ProyekTI\resources\views/components/minigame-rps.blade.php ENDPATH**/ ?>