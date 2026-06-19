<style>
/* Quiz Specific Styles & Tabs */
.mg-quiz-tabs {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
    border-bottom: 2px solid var(--mg-border);
    padding-bottom: 1rem;
    overflow-x: auto;
}
.mg-quiz-tab-btn {
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
.mg-quiz-tab-btn:hover { color: var(--mg-text); background: var(--mg-surface); }
.mg-quiz-tab-btn.active { color: var(--mg-primary); background: var(--mg-primary-light); }

.mg-tab-content { display: none; }
.mg-tab-content.active { display: block; }

/* Lobby layout */
.mg-quiz-lobby-container {
    max-width: 760px;
    margin: 0 auto;
    background: var(--mg-surface);
    padding: 2.25rem;
    border-radius: var(--mg-radius-lg);
    border: 1px solid var(--mg-border);
    text-align: center;
}

/* Arena layout */
.mg-quiz-arena-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 100%;
    position: relative;
}
.mg-quiz-board {
    background: var(--mg-surface);
    padding: 2.25rem;
    border-radius: var(--mg-radius-lg);
    border: 1px solid var(--mg-border);
    width: 100%;
    max-width: 800px;
    margin-top: 2rem;
    box-shadow: var(--mg-shadow);
}
.mg-quiz-question {
    font-size: 1.6rem;
    font-weight: 800;
    margin-bottom: 2rem;
    text-align: center;
    line-height: 1.5;
}
.mg-quiz-options {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}
.mg-quiz-option {
    padding: 1.25rem;
    background: var(--mg-bg);
    border: 2px solid var(--mg-border);
    border-radius: var(--mg-radius-md);
    font-size: 1.2rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    text-align: center;
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}
.mg-quiz-option:hover {
    border-color: var(--mg-primary);
    background: var(--mg-primary-light);
    color: var(--mg-text);
}
.mg-quiz-option.correct {
    background: var(--mg-success) !important;
    color: white !important;
    border-color: var(--mg-success) !important;
}
.mg-quiz-option.wrong {
    background: var(--mg-danger) !important;
    color: white !important;
    border-color: var(--mg-danger) !important;
}
.mg-quiz-option.disabled {
    opacity: 0.5;
    pointer-events: none;
}

/* Duo Option Highlights */
.mg-quiz-option.duo-p1 {
    border-color: #3b82f6 !important;
    box-shadow: 0 0 15px rgba(59, 130, 246, 0.6) !important;
}
.mg-quiz-option.duo-p2 {
    border-color: #ef4444 !important;
    box-shadow: 0 0 15px rgba(239, 68, 68, 0.6) !important;
}
.mg-quiz-option.duo-both {
    border-color: var(--mg-primary) !important;
    box-shadow: 0 0 15px #3b82f6, 0 0 15px #ef4444 !important;
}

.mg-quiz-progress {
    display: flex;
    justify-content: space-between;
    margin-bottom: 1.5rem;
    font-weight: 800;
    color: var(--mg-primary);
    font-size: 1.1rem;
}

.mg-quiz-hints {
    display: flex;
    justify-content: center;
    gap: 1rem;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--mg-border);
}

/* Common Utilities */
.mg-quiz-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}
.mg-quiz-status {
    font-size: 2.2rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
    text-align: center;
}
.mg-quiz-substatus {
    color: var(--mg-primary);
    font-weight: 600;
    margin-bottom: 1rem;
    text-align: center;
}
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
/* Detail skin: Zero Infinity Quiz arena */
#mg-screen-quiz {
    --quiz-accent: #ff4f8b;
    --quiz-gold: #ffd278;
    --quiz-ice: #7ed3ff;
    --quiz-green: #8ef6bd;
    --quiz-red: #ff6f7b;
    --quiz-border: rgba(255, 210, 120, 0.16);
    background:
        radial-gradient(circle at 18% 18%, rgba(255, 79, 139, 0.14), transparent 30%),
        radial-gradient(circle at 78% 18%, rgba(126, 211, 255, 0.11), transparent 32%),
        linear-gradient(135deg, #07060b 0%, #14101a 56%, #09070d 100%);
}

#mg-screen-quiz::before {
    background-image:
        linear-gradient(rgba(255, 210, 120, 0.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255, 79, 139, 0.035) 1px, transparent 1px);
    background-size: 54px 54px;
    opacity: 0.38;
}

#mg-screen-quiz .mg-quiz-tabs,
#mg-screen-quiz .mg-quiz-lobby-container,
#mg-screen-quiz .mg-quiz-arena-container,
#mg-screen-quiz .mg-quiz-board,
#mg-screen-quiz .mg-tournament-bracket,
#mg-screen-quiz .mg-matchup,
#mg-screen-quiz .mg-leaderboard-table,
#mg-screen-quiz .mg-shop-item,
#mg-screen-quiz .mg-game-store-card {
    border-radius: 8px;
    border-color: var(--quiz-border);
    background:
        repeating-linear-gradient(135deg, rgba(255, 255, 255, 0.025) 0 1px, transparent 1px 18px),
        linear-gradient(145deg, rgba(18, 16, 26, 0.96), rgba(8, 7, 12, 0.94));
    box-shadow: 0 18px 46px rgba(0, 0, 0, 0.32);
}

#mg-screen-quiz .mg-quiz-tab-btn,
#mg-screen-quiz .mg-quiz-option,
#mg-screen-quiz .mg-game-mode-action,
#mg-screen-quiz .mg-game-control-btn {
    border-radius: 8px;
}

#mg-screen-quiz .mg-quiz-tab-btn.active,
#mg-screen-quiz .mg-quiz-tab-btn:hover {
    color: var(--quiz-gold);
    border-color: rgba(255, 79, 139, 0.42);
    background: linear-gradient(135deg, rgba(255, 79, 139, 0.16), rgba(255, 210, 120, 0.06));
    box-shadow: 0 14px 34px rgba(255, 79, 139, 0.14);
}

#mg-screen-quiz .mg-quiz-question {
    color: #eef7ff;
}

#mg-screen-quiz .mg-quiz-option {
    color: rgba(238, 247, 255, 0.88);
    border-color: rgba(255, 210, 120, 0.14);
    background: rgba(255, 255, 255, 0.045);
}

#mg-screen-quiz .mg-quiz-option:hover:not(.disabled) {
    color: var(--quiz-gold);
    border-color: rgba(255, 79, 139, 0.44);
    background: rgba(255, 79, 139, 0.09);
}

#mg-screen-quiz .mg-quiz-option.correct {
    color: var(--quiz-green);
    border-color: rgba(142, 246, 189, 0.58);
    background: rgba(142, 246, 189, 0.1);
}

#mg-screen-quiz .mg-quiz-option.wrong {
    color: var(--quiz-red);
    border-color: rgba(255, 111, 123, 0.54);
    background: rgba(255, 111, 123, 0.1);
}

#mg-screen-quiz .mg-quiz-option.duo-p1 {
    border-color: rgba(126, 211, 255, 0.48);
    box-shadow: inset 4px 0 0 rgba(126, 211, 255, 0.82);
}

#mg-screen-quiz .mg-quiz-option.duo-p2 {
    border-color: rgba(255, 210, 120, 0.48);
    box-shadow: inset 4px 0 0 rgba(255, 210, 120, 0.82);
}

#mg-screen-quiz .mg-quiz-option.duo-both {
    border-color: rgba(255, 79, 139, 0.5);
    background: linear-gradient(90deg, rgba(126, 211, 255, 0.08), rgba(255, 210, 120, 0.08));
}

#mg-screen-quiz .mg-quiz-progress > div {
    background: linear-gradient(90deg, #ff4f8b, #ffd278, #7ed3ff) !important;
}

#mg-screen-quiz .mg-quiz-hints {
    border-radius: 8px;
    border-color: rgba(255, 210, 120, 0.16);
    background: rgba(255, 210, 120, 0.06);
}

#mg-screen-quiz .mg-quiz-status,
#mg-screen-quiz .mg-stat-row strong,
#mg-screen-quiz .mg-shop-item-price,
#mg-screen-quiz .mg-game-store-price {
    color: var(--quiz-green);
}

#mg-screen-quiz .mg-shop-grid,
#mg-screen-quiz .mg-game-store-grid {
    gap: 14px;
}

#mg-screen-quiz .mg-shop-item-icon,
#mg-screen-quiz .mg-game-store-icon {
    border-radius: 8px;
    color: var(--quiz-gold);
    background: linear-gradient(145deg, rgba(255, 79, 139, 0.16), rgba(255, 210, 120, 0.1));
    border: 1px solid rgba(255, 210, 120, 0.18);
}

#mg-screen-quiz .mg-shop-item:hover,
#mg-screen-quiz .mg-game-store-card:hover {
    border-color: rgba(255, 79, 139, 0.38);
    transform: translateY(-2px);
}

#mg-screen-quiz .mg-shop-item-owned {
    border-radius: 8px;
    color: var(--quiz-gold);
    border: 1px solid rgba(255, 210, 120, 0.22);
    background: rgba(255, 210, 120, 0.08);
}

#mg-screen-quiz .mg-matchup.winner {
    border-color: rgba(142, 246, 189, 0.42);
    background: rgba(142, 246, 189, 0.08);
}

#mg-quiz-matchmaking,
#mg-quiz-spectator {
    background: rgba(4, 3, 7, 0.82) !important;
    backdrop-filter: blur(18px);
}

#mg-quiz-matchmaking > div:first-child {
    color: var(--quiz-gold) !important;
    text-shadow: 0 0 26px rgba(255, 210, 120, 0.26);
}

#mg-quiz-matchmaking [style*="background: #121214"],
#mg-quiz-spectator [style*="background: #121214"] {
    border-radius: 8px !important;
    border-color: rgba(255, 210, 120, 0.22) !important;
    background: linear-gradient(145deg, rgba(18, 16, 26, 0.98), rgba(9, 8, 14, 0.97)) !important;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.38) !important;
}

#mg-quiz-matchmaking-bar {
    background: linear-gradient(90deg, #ff4f8b, #ffd278, #7ed3ff) !important;
}

#mg-quiz-countdown-overlay {
    color: var(--quiz-gold) !important;
    text-shadow: 0 0 32px rgba(255, 210, 120, 0.36);
}
</style>

<div id="mg-screen-quiz" class="mg-screen">
    <!-- Matchmaking Overlay -->
    <div id="mg-quiz-matchmaking" style="display: none; position: fixed; inset: 0; background: rgba(9, 9, 11, 0.97); z-index: 999999; align-items: center; justify-content: center; flex-direction: column;">
        <div style="font-size: 2.2rem; font-weight: 800; color: #059669; text-shadow: 0 0 20px rgba(5, 150, 105, 0.5); margin-bottom: 2rem; animation: glitch 1.5s infinite; font-family: 'Outfit', sans-serif;"> MEMPERSIAPKAN SPEEDRUN...</div>
        <div style="display: flex; gap: 3rem; align-items: center; margin-bottom: 3rem;">
            <div style="text-align: center; background: #121214; padding: 1.5rem; border-radius: 16px; border: 2px solid #7c3aed; width: 160px; box-shadow: 0 0 20px rgba(124, 58, 237, 0.15);">
                <img src="<?php echo e(asset('img/' . Auth::user()->avatarPath())); ?>" style="width: 80px; height: 80px; border-radius: 50%; border: 3px solid #7c3aed; margin-bottom: 1rem; object-fit: cover;">
                <div style="font-weight: 700; color: #fff; font-size: 0.95rem;"><?php echo e(Auth::user()->name); ?></div>
                <div style="font-size: 0.75rem; color: #a78bfa; font-weight: 600; text-transform: uppercase;">Pemain</div>
            </div>
            <div style="font-size: 2rem; font-weight: 900; color: #EF4444; animation: pulse 1s infinite;">VS</div>
            <div id="mg-quiz-opponent-card" style="text-align: center; background: #121214; padding: 1.5rem; border-radius: 16px; border: 2px solid #059669; width: 160px; box-shadow: 0 0 20px rgba(5, 150, 105, 0.15);">
                <div id="mg-quiz-opponent-avatar" style="font-size: 3.5rem; margin-bottom: 1rem; height: 80px; display: flex; align-items: center; justify-content: center;"></div>
                <div id="mg-quiz-opponent-name" style="font-weight: 700; color: #fff; font-size: 0.95rem;">Mencari...</div>
                <div style="font-size: 0.75rem; color: #059669; font-weight: 600; text-transform: uppercase;">Penantang Terdaftar</div>
            </div>
        </div>
        <div style="width: 250px; height: 8px; background: rgba(255, 255, 255, 0.1); border-radius: 99px; overflow: hidden; border: 1px solid rgba(255, 255, 255, 0.2);">
            <div id="mg-quiz-matchmaking-bar" style="height: 100%; background: linear-gradient(90deg, #059669, #34d399); width: 0%;"></div>
        </div>
    </div>

    <!-- Rival spectator overlay -->
    <div id="mg-quiz-spectator" style="display: none; position: fixed; inset: 0; background: rgba(9, 9, 11, 0.97); z-index: 999999; align-items: center; justify-content: center; flex-direction: column;">
        <div style="font-size: 2.2rem; font-weight: 800; color: #EF4444; text-shadow: 0 0 20px rgba(239, 68, 68, 0.5); margin-bottom: 1rem; font-family: 'Outfit', sans-serif;"> MODE PANTAU</div>
        <div style="color: #a1a1aa; font-weight: 600; margin-bottom: 2rem;" id="mg-quiz-spectator-sub">Penantang B vs Penantang C</div>
        
        <div class="mg-quiz-arena-container" style="background: #121214; padding: 2rem; border-radius: 20px; border: 1px solid rgba(255,255,255,0.05); box-shadow: var(--mg-shadow); width: 500px;">
            <div id="mg-quiz-spec-status" style="font-size: 1.4rem; font-weight: 700; color: #fff; margin-bottom: 1.5rem; text-align: center;">Menginisialisasi...</div>
            <div style="display: flex; flex-direction: column; gap: 1.5rem; width: 100%;">
                <div style="background: rgba(255,255,255,0.02); padding: 1rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.05);">
                    <div style="font-weight: bold; color: #a78bfa; margin-bottom: 0.5rem;" id="mg-quiz-spec-p1-name">Penantang B</div>
                    <div style="display: flex; justify-content: space-between;">
                        <span id="mg-quiz-spec-p1-progress">Soal: 0/15</span>
                        <span id="mg-quiz-spec-p1-time">Waktu: 0s</span>
                        <span id="mg-quiz-spec-p1-acc">Akurasi: 0%</span>
                    </div>
                </div>
                <div style="background: rgba(255,255,255,0.02); padding: 1rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.05);">
                    <div style="font-weight: bold; color: #f59e0b; margin-bottom: 0.5rem;" id="mg-quiz-spec-p2-name">Penantang C</div>
                    <div style="display: flex; justify-content: space-between;">
                        <span id="mg-quiz-spec-p2-progress">Soal: 0/15</span>
                        <span id="mg-quiz-spec-p2-time">Waktu: 0s</span>
                        <span id="mg-quiz-spec-p2-acc">Akurasi: 0%</span>
                    </div>
                </div>
            </div>
        </div>

        <button class="mg-btn mg-btn-secondary" style="margin-top: 2rem; border-color: #EF4444; color: #EF4444;" type="button" data-mg-game="quiz" data-mg-action="confirm-exit-spectator">Keluar Pantau</button>
    </div>

    <div class="mg-game-lobby-topbar">
        <div class="mg-game-lobby-brand">
            <div class="mg-game-lobby-logo"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9.2 9a3.1 3.1 0 1 1 4.8 2.6c-1.3.8-2 1.6-2 3.1"/><path d="M12 18h.01"/><circle cx="12" cy="12" r="9"/></svg></div>
            <div>
                <h2 class="mg-game-lobby-title">Quiz Science Arena</h2>
                <p class="mg-game-lobby-subtitle">Game Idea by Aefzetaa</p>
            </div>
        </div>
        <div class="mg-game-lobby-tabs">
            <button class="mg-game-lobby-tab mg-quiz-tab-btn active" type="button" data-mg-game="quiz" data-mg-action="switch-tab" data-tab="play">Play</button>
            <button class="mg-game-lobby-tab mg-quiz-tab-btn" type="button" data-mg-game="quiz" data-mg-action="switch-tab" data-tab="shop">Store</button>
            <button class="mg-game-lobby-tab mg-quiz-tab-btn" type="button" data-mg-game="quiz" data-mg-action="switch-tab" data-tab="leaderboard">Leaderboard</button>
            <button class="mg-game-lobby-tab mg-quiz-tab-btn" type="button" data-mg-game="quiz" data-mg-action="switch-tab" data-tab="stats">Profile</button>
            <button class="mg-game-lobby-tab mg-quiz-tab-btn" type="button" data-mg-game="quiz" data-mg-action="switch-tab" data-tab="guide">Guide</button>
        </div>
        <div class="mg-game-lobby-actions">
            <div class="mg-game-lobby-balance"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7.5h16a1.5 1.5 0 0 1 1.5 1.5v8.5A1.5 1.5 0 0 1 20 19H4a2 2 0 0 1-2-2V6.5A2.5 2.5 0 0 1 4.5 4H18"/><path d="M16.5 13h4"/><path d="M4.5 4A2.5 2.5 0 0 0 4 9h16"/></svg><span>Rp <?php echo e(number_format(Auth::user()->balance ?? 0, 0, ',', '.')); ?></span></div>
            <button class="mg-game-lobby-exit" type="button" data-mg-game="quiz" data-mg-action="exit-portal">Exit</button>
        </div>
    </div>

    <!-- Main Navigation area (hidden when playing) -->
    <div id="quiz-main-view">
        <div id="quiz-tab-play" class="mg-tab-content active">
            <section class="mg-game-lobby-frame">
                <div class="mg-game-lobby-main">
                    <div class="mg-game-mode-panel">
                        <h1 class="mg-game-mode-title">Which mode do you want to play?</h1>
                        <select id="mg-quiz-puzzle" class="mg-btn mg-btn-secondary" style="min-width:min(520px, 100%); margin:0 auto 1.6rem; text-align:center; font-size:1.05rem;">
                            <option value="">Memuat Puzzle...</option>
                        </select>
                        <div class="mg-game-mode-buttons">
                            <button class="mg-game-mode-btn" type="button" data-mg-game="quiz" data-mg-action="select-lobby-mode" data-mode="solo_duo">Solo / Duo</button>
                            <button class="mg-game-mode-btn" type="button" data-mg-game="quiz" data-mg-action="select-lobby-mode" data-mode="rank" data-start="1">Ranked</button>
                            <button class="mg-game-mode-btn" type="button" data-mg-game="quiz" data-mg-action="select-lobby-mode" data-mode="tournament" data-start="1">Tournament</button>
                        </div>
                        <div id="mg-quiz-solo-duo-panel" class="mg-game-solo-duo-panel">
                            <button class="mg-game-submode-btn" type="button" data-mg-game="quiz" data-mg-action="select-lobby-mode" data-mode="have_fun" data-start="1">Solo</button>
                            <button class="mg-game-submode-btn" type="button" data-mg-game="quiz" data-mg-action="select-lobby-mode" data-mode="duo" data-start="1">Duo</button>
                        </div>
                        <select id="mg-quiz-mode" style="display:none;">
                            <option value="have_fun">Solo (Kasual)</option>
                            <option value="rank">Ranked</option>
                            <option value="duo">Duo (Satu Perangkat)</option>
                            <option value="tournament">Tournament (Final BO3)</option>
                        </select>
                        <select id="mg-quiz-diff" style="display:none;">
                            <option value="easy">Rival Arena</option>
                            <option value="pro">Rival Arena</option>
                            <option value="world_class">Rival Arena</option>
                        </select>
                    </div>
                </div>
                <footer class="mg-game-lobby-footer">
                    <div><strong>Quiz Science Arena</strong><br>&copy; 2024 Zero Infinity Arcade Portal. All rights reserved.</div>
                    <div class="mg-game-lobby-links"><span>Privacy Policy</span><span>Terms of Service</span><span>Support</span></div>
                </footer>
            </section>
        </div>

        <div id="quiz-tab-shop" class="mg-tab-content">
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
                    <article class="mg-game-store-card" id="shop-quiz-quiz_theme_dark"><button class="mg-game-store-hit" type="button" data-mg-action="buy-shop-item" data-item-key="quiz_theme_dark" data-item-type="portal_theme" data-price="550000"><span class="mg-game-store-icon"><svg viewBox="0 0 24 24"><path d="M12 3a7 7 0 1 0 7 7 5 5 0 0 1-7-7Z"/><path d="M18 15l3 3M17 20l4-4"/></svg></span><span class="mg-game-store-name">Dark Matter Theme</span><span class="mg-game-store-price">Rp 550.000</span></button></article>
                    <article class="mg-game-store-card" id="shop-quiz-quiz_theme_hologram"><button class="mg-game-store-hit" type="button" data-mg-action="buy-shop-item" data-item-key="quiz_theme_hologram" data-item-type="portal_theme" data-price="650000"><span class="mg-game-store-icon"><svg viewBox="0 0 24 24"><path d="M12 3l2.6 5.4L20 11l-5.4 2.6L12 19l-2.6-5.4L4 11l5.4-2.6z"/><path d="M19 3v4M17 5h4"/></svg></span><span class="mg-game-store-name">Hologram Theme</span><span class="mg-game-store-price">Rp 650.000</span></button></article>
                    <article class="mg-game-store-card" id="shop-quiz-quiz_font_pixel"><button class="mg-game-store-hit" type="button" data-mg-action="buy-shop-item" data-item-key="quiz_font_pixel" data-item-type="avatar_border" data-price="220000"><span class="mg-game-store-icon"><svg viewBox="0 0 24 24"><path d="M4 5h16v14H4z"/><path d="M8 9h8M8 13h5M17 13h.01"/></svg></span><span class="mg-game-store-name">Pixel Border</span><span class="mg-game-store-price">Rp 220.000</span></button></article>
                </div>
            </section>
        </div>

        <div id="quiz-tab-leaderboard" class="mg-tab-content">
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
                        <tbody id="quiz-leaderboard-body"><!-- Injected by JS --></tbody>
                    </table>
                </div>
            </section>
        </div>

        <div id="quiz-tab-stats" class="mg-tab-content">
            <section class="mg-game-tab-surface">
                <div class="mg-game-profile-card">
                    <h2 class="mg-game-section-title">Quiz Science Arena Profile</h2>
                    <div class="mg-game-profile-grid">
                        <div class="mg-game-profile-stat"><span>Rank</span><strong id="mg-quiz-rank">Loading...</strong></div>
                        <div class="mg-game-profile-stat"><span>Stars</span><strong id="mg-quiz-stars">0</strong></div>
                        <div class="mg-game-profile-stat"><span>Win / Loss / Draw</span><strong id="mg-quiz-wld">0 / 0 / 0</strong></div>
                        <div class="mg-game-profile-stat"><span>Autopilot</span><strong id="mg-quiz-autopilot">0 / 0 / 0</strong></div>
                    </div>
                </div>
            </section>
        </div>

        <div id="quiz-tab-guide" class="mg-tab-content">
            <section class="mg-game-tab-surface mg-game-guide-shell">
                <div>
                    <h1 class="mg-game-guide-title">Master the Arena</h1>
                    <p class="mg-game-guide-lead">Jawab cepat, jaga akurasi, dan gunakan hint dengan bijak saat soal mulai mengejar waktu.</p>
                    <div class="mg-game-guide-steps">
                        <div class="mg-game-guide-step is-active"><span class="mg-game-guide-num">01</span><div><strong>Getting Started</strong><span>Pilih mode dan siapkan ritme bermain Anda.</span></div></div>
                        <div class="mg-game-guide-step"><span class="mg-game-guide-num">02</span><div><strong>Choosing a Mode</strong><span>Solo/Duo, Ranked, dan Tournament punya tempo berbeda.</span></div></div>
                        <div class="mg-game-guide-step"><span class="mg-game-guide-num">03</span><div><strong>Using Support</strong><span>Gunakan autopilot atau hint hanya di mode yang mendukung.</span></div></div>
                        <div class="mg-game-guide-step"><span class="mg-game-guide-num">04</span><div><strong>Climbing the Ranks</strong><span>Menang di mode kompetitif untuk menaikkan reputasi arena.</span></div></div>
                    </div>
                </div>
                <div class="mg-game-guide-card mg-game-guide-visual">
                    <div class="mg-game-guide-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9.2 9a3.1 3.1 0 1 1 4.8 2.6c-1.3.8-2 1.6-2 3.1"/><path d="M12 18h.01"/><circle cx="12" cy="12" r="9"/></svg></div>
                    <h3>Answer Under Pressure</h3>
                    <p>Setiap jawaban mengubah tempo. Akurasi dan kecepatan menjadi jalur utama untuk menutup arena dengan kemenangan.</p>
                </div>
            </section>
        </div>

        <!-- Arena Area (Hidden initially) -->
    <div id="quiz-arena-view" style="display:none;">
        <button class="mg-btn mg-btn-secondary" style="margin-bottom: 2rem;" type="button" data-mg-game="quiz" data-mg-action="exit-lobby"> Berhenti / Kembali ke Lobby</button>
        
        <div class="mg-tournament-bracket" id="mg-quiz-bracket">
            <!-- Bracket injected via JS -->
        </div>

        <div class="mg-quiz-arena-container">
            <div style="display:flex; gap:1rem; margin-bottom:2rem; flex-wrap:wrap;">
                <button class="mg-btn mg-btn-secondary" type="button" data-mg-game="quiz" data-mg-action="exit-lobby">Kembali ke Lobby</button>
                <button class="mg-btn mg-btn-secondary" style="border-color:var(--mg-danger); color:var(--mg-danger);" type="button" data-mg-game="quiz" data-mg-action="surrender">Menyerah</button>
            </div>
            <div id="mg-quiz-status" class="mg-quiz-status">Siap Bermain?</div>
            <div id="mg-quiz-substatus" class="mg-quiz-substatus">Pilih Mode dan klik Mulai</div>
            
            <div class="mg-quiz-board" id="mg-quiz-board" style="display:none;">
                <!-- Duo Score Overlay Header -->
                <div id="mg-quiz-duo-scores" style="display: none; justify-content: space-around; font-size: 1.5rem; font-weight: 800; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 2px dashed rgba(255,255,255,0.1);">
                    <div style="color: #3b82f6; text-shadow: 0 0 10px rgba(59, 130, 246, 0.4);"> Player 1: <span id="mg-quiz-p1-score">0</span></div>
                    <div style="color: #ef4444; text-shadow: 0 0 10px rgba(239, 68, 68, 0.4);"> Player 2: <span id="mg-quiz-p2-score">0</span></div>
                </div>

                <div class="mg-quiz-progress">
                    <span id="mg-quiz-progress-text">Soal 1/15</span>
                    <span id="mg-quiz-timer"> 0s</span>
                </div>
                <div class="mg-quiz-question" id="mg-quiz-question-text">Loading...</div>
                <div class="mg-quiz-options" id="mg-quiz-options">
                    <!-- Options injected via JS -->
                </div>

                <div class="mg-quiz-hints" id="mg-quiz-hints-container">
                    <button id="mg-quiz-hint-free" class="mg-btn mg-btn-secondary" type="button" data-mg-game="quiz" data-mg-action="use-hint" data-hint="free">Hint Free (Sisa: 0)</button>
                    <button id="mg-quiz-hint-pro" class="mg-btn mg-btn-secondary" type="button" data-mg-game="quiz" data-mg-action="use-hint" data-hint="pro">Hint Pro 50:50 (Sisa: 0)</button>
                    <button id="mg-quiz-hint-world_class" class="mg-btn mg-btn-secondary" type="button" data-mg-game="quiz" data-mg-action="use-hint" data-hint="world_class">Bocoran WC (Sisa: 0)</button>
                </div>

                <div id="mg-quiz-duo-guide" style="display: none; text-align: center; color: var(--mg-text-muted); font-size: 0.9rem; margin-top: 1.5rem; font-weight: 600;">
                    Player 1: Q=A, W=B, E=C, R=D (Biru) | Player 2: U=A, I=B, O=C, P=D (Merah)
                </div>
            </div>
            <div id="mg-quiz-result-panel" class="mg-result-panel" aria-live="polite"></div>
        </div>
    </div>
</div>

<script>
window.MgQuiz = {
    puzzles: [],
    currentPuzzle: null,
    questions: [],
    currentIndex: 0,
    correctCount: 0,
    wrongCount: 0,
    startTime: 0,
    timerInterval: null,
    isPlaying: false,
    currentMode: 'have_fun',
    leaderboardData: [],
    
    // Duo State
    p1Score: 0,
    p2Score: 0,
    p1Choice: null,
    p2Choice: null,

    // AI thinking/selection state
    aiThinkTimer: null,
    aiChoiceIndex: null,

    // Tournament State
    tourneyState: { stage: 0, wins: 0, losses: 0, target: 1, active: false, players: [] },
    isAutoGrinding: false,
    isSpectating: false,
    specInterval: null,

    async init() {
        try {
            await this.safeLoadLobby();
        } catch (error) {
            console.error('Quiz lobby load failed:', error);
            MgCore.toast('Lobby Quiz terbuka, tetapi data arena belum siap.');
        }
        window.renderGameShop = () => this.renderShopUI();
        document.removeEventListener('keydown', this.handleDuoKeysBound);
        this.handleDuoKeysBound = this.handleDuoKeys.bind(this);
        document.addEventListener('keydown', this.handleDuoKeysBound);

        const modeSelect = document.getElementById('mg-quiz-mode');
        const diffSelect = document.getElementById('mg-quiz-diff');
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
        document.querySelectorAll('#quiz-main-view .mg-tab-content').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('#quiz-main-view .mg-quiz-tab-btn').forEach(el => el.classList.remove('active'));
        document.getElementById('quiz-tab-' + tabId).classList.add('active');
        if (btnElement) btnElement.classList.add('active');
    },

    renderShopUI() {
        const inv = window.MgUserInventory || [];
        ['quiz_theme_dark', 'quiz_theme_hologram', 'quiz_font_pixel'].forEach(itemKey => {
            if (inv.includes(itemKey)) {
                const el = document.getElementById('shop-quiz-' + itemKey);
                if (el) el.querySelector('button').outerHTML = `<div class="mg-shop-item-owned">Dimiliki </div>`;
            }
        });
    },

    async loadLobby() {
        const res = await MgCore.apiGet('/quiz-minigame/lobby-data');
        if (res.status !== 'success') {
            MgCore.toast(res.message || 'Data lobby belum siap. Layar tetap bisa dipakai.');
            return;
        }

        if (res.status === 'success') {
            this.updateStats(res.player);
            this.puzzles = res.puzzles;
            this.leaderboardData = res.leaderboard || [];
            
            const select = document.getElementById('mg-quiz-puzzle');
            select.innerHTML = '';
            this.puzzles.forEach(p => {
                const opt = document.createElement('option');
                opt.value = p.id;
                opt.innerText = p.title;
                select.appendChild(opt);
            });
            this.renderLeaderboard();
        }
    },

    async safeLoadLobby() {
        try {
            await this.loadLobby();
        } catch (error) {
            console.error('Quiz lobby refresh failed:', error);
            MgCore.toast('Data arena belum sempat diperbarui, tetapi layar tetap bisa dipakai.');
        }
    },
    updateStats(player) {
        document.getElementById('mg-quiz-rank').innerText = player.profile.rank.replace(/_/g, ' ');
        document.getElementById('mg-quiz-stars').innerText = player.profile.stars;
        document.getElementById('mg-quiz-wld').innerText = `${player.profile.win_count} / ${player.profile.loss_count} / ${player.profile.draw_count}`;
        document.getElementById('mg-quiz-autopilot').innerText = `${player.autopilot.free} / ${player.autopilot.pro} / ${player.autopilot.world_class}`;
        MgCore.updateBalance(player.balance);
        
        // Update hint sisa kuota text dynamically
        this.updateHintButtons(player.autopilot);
    },

    updateHintButtons(autopilot) {
        const btnFree = document.getElementById('mg-quiz-hint-free');
        const btnPro = document.getElementById('mg-quiz-hint-pro');
        const btnWC = document.getElementById('mg-quiz-hint-world_class');
        if (btnFree) btnFree.innerText = `Hint Free (Sisa: ${autopilot.free})`;
        if (btnPro) btnPro.innerText = `Hint Pro 50:50 (Sisa: ${autopilot.pro})`;
        if (btnWC) btnWC.innerText = `Bocoran WC (Sisa: ${autopilot.world_class})`;
    },

    renderLeaderboard() {
        const tbody = document.getElementById('quiz-leaderboard-body');
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

    setStatus(text, sub = "") {
        document.getElementById('mg-quiz-status').innerText = text;
        document.getElementById('mg-quiz-substatus').innerText = sub;
    },
    pickRegisteredOpponent() {
        const currentUser = '<?php echo e(Auth::user()->username ?: Auth::user()->name); ?>'.toLowerCase();
        const names = (this.leaderboardData || [])
            .map(entry => entry.username || entry.name)
            .filter(name => name && name.toLowerCase() !== currentUser);
        return names.length ? names[Math.floor(Math.random() * names.length)] : 'Penantang Arena';
    },
    async startGameRouter() {
        const mode = document.getElementById('mg-quiz-mode').value;
        const puzzleId = document.getElementById('mg-quiz-puzzle').value;
        this.currentMode = mode;
        this.isAutoGrinding = false;
        this.currentPuzzle = this.puzzles.find(p => p.id == puzzleId);
        
        if (!this.currentPuzzle) return MgCore.toast("Pilih puzzle dulu!");


        document.getElementById('quiz-main-view').style.display = 'none';
        document.getElementById('quiz-arena-view').style.display = 'block';
        document.getElementById('mg-quiz-bracket').classList.remove('active');
        
        // Matchmaking screen animation
        const opponentName = this.pickRegisteredOpponent();

        if (this.isAutoGrinding) {
            this.runAutoGrinding(opponentName);
            return;
        }

        if (mode === 'rank' || mode === 'tournament' || mode === 'have_fun') {
            this.showMatchmaking(opponentName, async () => {
                // Semua mode arcade sekarang bebas biaya masuk.
                document.getElementById('mg-quiz-board').style.display = 'block';
                if (mode === 'tournament') {
                    this.initTournament();
                } else {
                    if (mode === 'have_fun') {
                        // Set random difficulty for Solo Kasual behind the scene
                        const difficulties = ['easy', 'pro', 'world_class'];
                        document.getElementById('mg-quiz-diff').value = difficulties[Math.floor(Math.random() * difficulties.length)];
                    }
                    this.startSoloRound();
                }
            });
        } else {
            // Duo tanpa pencarian penantang
            document.getElementById('mg-quiz-board').style.display = 'block';
            this.p1Score = 0;
            this.p2Score = 0;
            this.startDuoRound();
        }
    },

    showMatchmaking(opponentName, callback) {
        document.getElementById('mg-quiz-opponent-name').innerText = opponentName;
        document.getElementById('mg-quiz-matchmaking').style.display = 'flex';
        const bar = document.getElementById('mg-quiz-matchmaking-bar');
        bar.style.transition = 'none';
        bar.style.width = '0%';
        
        setTimeout(() => {
            bar.style.transition = 'width 3s ease-in-out';
            bar.style.width = '100%';
        }, 50);

        setTimeout(() => {
            document.getElementById('mg-quiz-matchmaking').style.display = 'none';
            callback();
        }, 3200);
    },

    async runAutoGrinding(opponentName) {
        document.getElementById('mg-quiz-opponent-name').innerText = opponentName;
        document.getElementById('mg-quiz-matchmaking').style.display = 'flex';
        const bar = document.getElementById('mg-quiz-matchmaking-bar');
        bar.style.transition = 'none';
        bar.style.width = '0%';

        setTimeout(() => {
            bar.style.transition = 'width 3s ease-in-out';
            bar.style.width = '100%';
        }, 50);

        // Run Autopilot in parallel
        const autopilotPromise = MgCore.apiCall('/quiz-minigame/use-autopilot');

        setTimeout(async () => {
            const res = await autopilotPromise;
            document.getElementById('mg-quiz-matchmaking').style.display = 'none';

            if (!res.success) {
                await window.showAlert('Autopilot Terhenti', 'Autopilot Terhenti: ' + (res.message || 'Kesalahan Autopilot'));
                this.isAutoGrinding = false;
                document.getElementById('mg-quiz-auto-grinding').checked = false;
                this.exitArena();
                return;
            }

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

            if (this.isAutoGrinding && document.getElementById('mg-quiz-auto-grinding').checked) {
                let countdown = 3;
                const interval = setInterval(() => {
                    this.setStatus(msg, `Autopilot: Mencari lawan berikutnya dalam ${countdown}...`);
                    countdown--;
                    if (countdown < 0) {
                        clearInterval(interval);
                        if (this.isAutoGrinding && document.getElementById('mg-quiz-auto-grinding').checked) {
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
        clearInterval(this.timerInterval);
        clearTimeout(this.aiThinkTimer);
        this.hideResultPanel();
        document.getElementById('quiz-arena-view').style.display = 'none';
        document.getElementById('quiz-main-view').style.display = 'block';
        document.getElementById('mg-quiz-board').style.display = 'none';
        
        // Hide Duo UI elements just in case
        document.getElementById('mg-quiz-duo-scores').style.display = 'none';
        document.getElementById('mg-quiz-duo-guide').style.display = 'none';
        document.getElementById('mg-quiz-hints-container').style.display = 'flex';

        this.setStatus('Siap Bermain?', 'Pilih Mode dan klik Mulai');
    },

    startSoloRound() {
        this.hideResultPanel();
        // Normal single player mode layout
        document.getElementById('mg-quiz-duo-scores').style.display = 'none';
        document.getElementById('mg-quiz-duo-guide').style.display = 'none';
        document.getElementById('mg-quiz-hints-container').style.display = 'flex';

        this.questions = [...this.currentPuzzle.questions];
        this.questions.sort(() => Math.random() - 0.5);
        this.currentIndex = 0;
        this.correctCount = 0;
        this.wrongCount = 0;
        this.isPlaying = true;
        
        this.setStatus(`Speedrun: ${this.currentPuzzle.title}`, `Mode: ${this.currentMode.toUpperCase()}`);
        this.startTimer();
        this.renderQuestion();
    },

    startDuoRound() {
        this.hideResultPanel();
        // Split screen / co-op layout
        document.getElementById('mg-quiz-duo-scores').style.display = 'flex';
        document.getElementById('mg-quiz-duo-guide').style.display = 'block';
        document.getElementById('mg-quiz-hints-container').style.display = 'none'; // No hints in Duo mode!

        document.getElementById('mg-quiz-p1-score').innerText = this.p1Score;
        document.getElementById('mg-quiz-p2-score').innerText = this.p2Score;

        this.questions = [...this.currentPuzzle.questions];
        this.questions.sort(() => Math.random() - 0.5);
        this.currentIndex = 0;
        this.isPlaying = true;
        
        this.setStatus(`Duo Mode: ${this.currentPuzzle.title}`, "Gunakan Keyboard untuk Menjawab!");
        this.startTimer();
        this.renderQuestion();
    },

    async initTournament() {
        const res = await MgCore.apiCall('/arcade/tournament-start', { game: 'quiz' });
        let players = ['Anda', 'Penantang A', 'Penantang B', 'Penantang C'];
        if (res.status === 'success' && res.players && res.players.length >= 4) {
            players = res.players;
        }

        this.tourneyState = { stage: 1, wins: 0, losses: 0, target: 1, active: true, players: players };
        document.getElementById('mg-quiz-bracket').classList.add('active');
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
            await window.showAlert('Match Result', `${winner} berhasil menyelesaikan kuis dan lolos ke Final!`);
            this.startTournamentRound();
        }
    },

    startSpectatingBots() {
        this.isSpectating = true;
        const p = this.tourneyState.players || ['Anda', 'Penantang A', 'Penantang B', 'Penantang C'];
        document.getElementById('mg-quiz-spectator').style.display = 'flex';
        document.getElementById('mg-quiz-spectator-sub').innerText = `Semi-Final Speedrun: ${p[2]} vs ${p[3]}`;
        
        document.getElementById('mg-quiz-spec-status').innerText = "Kuis dimulai...";
        
        let p1Progress = 0;
        let p2Progress = 0;
        let p1Time = 0;
        let p2Time = 0;
        let p1Correct = 0;
        let p2Correct = 0;

        const totalQ = this.currentPuzzle?.questions?.length || 15;

        document.getElementById('mg-quiz-spec-p1-name').innerText = `${p[2]} (Easy)`;
        document.getElementById('mg-quiz-spec-p2-name').innerText = `${p[3]} (Normal)`;

        this.specInterval = setInterval(() => {
            if (!this.isSpectating) return;

            p1Time++;
            p2Time++;

            // P1 progresses every ~2s
            if (p1Time % 2 === 0 && p1Progress < totalQ) {
                p1Progress++;
                if (Math.random() > 0.4) p1Correct++;
            }
            // P2 progresses every ~1s (faster)
            if (p2Time % 1 === 0 && Math.random() > 0.3 && p2Progress < totalQ) {
                p2Progress++;
                if (Math.random() > 0.25) p2Correct++;
            }

            document.getElementById('mg-quiz-spec-p1-progress').innerText = `Soal: ${p1Progress}/${totalQ}`;
            document.getElementById('mg-quiz-spec-p1-time').innerText = `Waktu: ${p1Time}s`;
            document.getElementById('mg-quiz-spec-p1-acc').innerText = `Akurasi: ${Math.round((p1Correct / Math.max(1, p1Progress)) * 100)}%`;

            document.getElementById('mg-quiz-spec-p2-progress').innerText = `Soal: ${p2Progress}/${totalQ}`;
            document.getElementById('mg-quiz-spec-p2-time').innerText = `Waktu: ${p2Time}s`;
            document.getElementById('mg-quiz-spec-p2-acc').innerText = `Akurasi: ${Math.round((p2Correct / Math.max(1, p2Progress)) * 100)}%`;

            if (p1Progress >= totalQ && p2Progress >= totalQ) {
                clearInterval(this.specInterval);
                const p1Score = p1Time + ((totalQ - p1Correct) * 10);
                const p2Score = p2Time + ((totalQ - p2Correct) * 10);
                const winner = p1Score < p2Score ? p[2] : p[3];
                
                document.getElementById('mg-quiz-spec-status').innerText = `${winner} MENANG! `;
                setTimeout(() => this.finishSpectating(winner), 2000);
            }
        }, 800);
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
        document.getElementById('mg-quiz-spectator').style.display = 'none';
        await window.showAlert('Lolos ke Final', `${winner} berhasil lolos ke Final!`);
        this.startTournamentRound();
    },

    startTournamentRound() {
        this.hideResultPanel();
        this.questions = [...this.currentPuzzle.questions];
        this.questions.sort(() => Math.random() - 0.5);
        this.currentIndex = 0;
        this.correctCount = 0;
        this.wrongCount = 0;
        this.isPlaying = true;

        // Reset overlays
        document.getElementById('mg-quiz-duo-scores').style.display = 'none';
        document.getElementById('mg-quiz-duo-guide').style.display = 'none';
        document.getElementById('mg-quiz-hints-container').style.display = 'flex';

        const p = this.tourneyState.players || ['Anda', 'Penantang A', 'Penantang B', 'Penantang C'];
        const opponent = this.tourneyState.stage === 3 ? (this.tourneyState.winnerOfSemi2 || p[2]) : p[1];

        if (this.tourneyState.stage === 3) {
            this.tourneyState.target = 2; // BO3 Final
            this.setStatus(`Final: ${this.currentPuzzle.title}`, `Skor Final: ${p[0]} ${this.tourneyState.wins} - ${this.tourneyState.losses} vs ${opponent} (BO3)`);
        } else {
            this.setStatus(`Semi-Final`, `${p[0]} vs ${opponent} (BO1)`);
            this.tourneyState.target = 1;
        }

        this.startTimer();
        this.renderQuestion();
    },

    renderBracket() {
        const b = document.getElementById('mg-quiz-bracket');
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

    startTimer() {
        this.startTime = Date.now();
        clearInterval(this.timerInterval);
        this.timerInterval = setInterval(() => {
            const elapsed = Math.floor((Date.now() - this.startTime) / 1000);
            document.getElementById('mg-quiz-timer').innerText = ` ${elapsed}s`;
        }, 1000);
    },

    renderQuestion() {
        // Clear any Rival Arena choice visual/timer
        clearTimeout(this.aiThinkTimer);
        this.aiChoiceIndex = null;

        if (this.currentIndex >= this.questions.length) {
            return this.finishQuiz();
        }

        const q = this.questions[this.currentIndex];
        document.getElementById('mg-quiz-progress-text').innerText = `Soal ${this.currentIndex + 1}/${this.questions.length}`;
        document.getElementById('mg-quiz-question-text').innerText = q.question;

        const optionsDiv = document.getElementById('mg-quiz-options');
        optionsDiv.innerHTML = '';
        
        const opts = [...q.options];
        opts.forEach((opt, idx) => {
            const btn = document.createElement('div');
            btn.className = 'mg-quiz-option';
            btn.innerText = opt;
            btn.dataset.idx = idx;
            btn.onclick = () => this.answerQuestion(opt, q.correct_answer, btn);
            
            // Duo Option Locked Indicators
            if (this.currentMode === 'duo') {
                const indicatorContainer = document.createElement('div');
                indicatorContainer.className = 'mg-quiz-option-indicator-container';
                indicatorContainer.style.display = 'flex';
                indicatorContainer.style.gap = '0.5rem';
                indicatorContainer.style.marginTop = '0.5rem';

                const p1Ind = document.createElement('span');
                p1Ind.id = `opt-p1-ind-${idx}`;
                p1Ind.style.display = 'none';
                p1Ind.style.color = '#3b82f6';
                p1Ind.style.fontWeight = 'bold';
                p1Ind.style.fontSize = '0.8rem';
                p1Ind.innerText = ' P1';
                
                const p2Ind = document.createElement('span');
                p2Ind.id = `opt-p2-ind-${idx}`;
                p2Ind.style.display = 'none';
                p2Ind.style.color = '#ef4444';
                p2Ind.style.fontWeight = 'bold';
                p2Ind.style.fontSize = '0.8rem';
                p2Ind.innerText = ' P2';

                indicatorContainer.appendChild(p1Ind);
                indicatorContainer.appendChild(p2Ind);
                btn.appendChild(indicatorContainer);
            }

            optionsDiv.appendChild(btn);
        });

        // Trigger Simultaneous AI Think Loop (only in Solo/Ranked/Tournament modes)
        if (this.currentMode !== 'duo') {
            this.triggerAiSelection(q.correct_answer);
        }
    },

    triggerAiSelection(correctAnswer) {
        const diff = document.getElementById('mg-quiz-diff').value;
        let delay = 3000;
        let accuracy = 0.5;

        if (diff === 'easy') {
            delay = 4000 + Math.random() * 2000;
            accuracy = 0.50;
        } else if (diff === 'pro') {
            delay = 2000 + Math.random() * 1500;
            accuracy = 0.75;
        } else {
            delay = 500 + Math.random() * 800; // WC is lightning fast
            accuracy = 0.95;
        }

        this.aiThinkTimer = setTimeout(() => {
            if (!this.isPlaying) return;

            const options = document.querySelectorAll('.mg-quiz-option');
            let chosenIdx = null;

            // Determine if Rival Arena answers correctly
            const correctBtn = Array.from(options).find(el => el.innerText.trim() === correctAnswer);
            
            if (correctBtn && Math.random() <= accuracy) {
                chosenIdx = parseInt(correctBtn.dataset.idx);
            } else {
                const incorrectButtons = Array.from(options).filter(el => el.innerText.trim() !== correctAnswer);
                if (incorrectButtons.length > 0) {
                    chosenIdx = parseInt(incorrectButtons[Math.floor(Math.random() * incorrectButtons.length)].dataset.idx);
                } else {
                    chosenIdx = 0;
                }
            }

            this.aiChoiceIndex = chosenIdx;
            
            // Render simultaneous Rival Arena badge next to selection
            const targetBtn = Array.from(options).find(el => parseInt(el.dataset.idx) === chosenIdx);
            if (targetBtn) {
                const badge = document.createElement('span');
                badge.className = 'mg-quiz-ai-badge';
                badge.style.background = '#EF4444';
                badge.style.color = '#fff';
                badge.style.padding = '3px 8px';
                badge.style.borderRadius = '4px';
                badge.style.fontSize = '0.75rem';
                badge.style.marginTop = '0.5rem';
                badge.style.fontWeight = 'bold';
                badge.style.boxShadow = '0 0 10px #EF4444';
                badge.innerText = 'Rival Arena';
                targetBtn.appendChild(badge);
            }
        }, delay);
    },

    answerQuestion(selected, correct, btnEl) {
        if (!this.isPlaying || this.currentMode === 'duo') return;
        this.isPlaying = false;
        
        clearTimeout(this.aiThinkTimer);

        const isCorrect = selected === correct;
        if (isCorrect) {
            btnEl.classList.add('correct');
            this.correctCount++;
        } else {
            btnEl.classList.add('wrong');
            this.wrongCount++;
            document.querySelectorAll('.mg-quiz-option').forEach(el => {
                if (el.innerText.trim() === correct) el.classList.add('correct');
            });
        }

        document.querySelectorAll('.mg-quiz-option').forEach(el => el.classList.add('disabled'));
        
        setTimeout(() => {
            this.isPlaying = true;
            this.currentIndex++;
            this.renderQuestion();
        }, 1200);
    },

    handleDuoKeys(e) {
        if (!this.isPlaying || this.currentMode !== 'duo') return;
        const key = e.key.toLowerCase();

        // P1 choice keys: Q/W/E/R
        if (['q','w','e','r'].includes(key) && this.p1Choice === null) {
            const mapping = { 'q': 0, 'w': 1, 'e': 2, 'r': 3 };
            this.p1Choice = mapping[key];
            this.renderDuoVisualLocks();
        }

        // P2 choice keys: U/I/O/P
        if (['u','i','o','p'].includes(key) && this.p2Choice === null) {
            const mapping = { 'u': 0, 'i': 1, 'o': 2, 'p': 3 };
            this.p2Choice = mapping[key];
            this.renderDuoVisualLocks();
        }

        if (this.p1Choice !== null && this.p2Choice !== null) {
            this.resolveDuoRound();
        }
    },

    renderDuoVisualLocks() {
        const options = document.querySelectorAll('.mg-quiz-option');
        options.forEach(btn => {
            const idx = parseInt(btn.dataset.idx);
            
            // Remove classes
            btn.classList.remove('duo-p1', 'duo-p2', 'duo-both');
            
            const p1Ind = document.getElementById(`opt-p1-ind-${idx}`);
            const p2Ind = document.getElementById(`opt-p2-ind-${idx}`);
            
            if (p1Ind) p1Ind.style.display = (this.p1Choice === idx) ? 'inline-block' : 'none';
            if (p2Ind) p2Ind.style.display = (this.p2Choice === idx) ? 'inline-block' : 'none';

            if (this.p1Choice === idx && this.p2Choice === idx) {
                btn.classList.add('duo-both');
            } else if (this.p1Choice === idx) {
                btn.classList.add('duo-p1');
            } else if (this.p2Choice === idx) {
                btn.classList.add('duo-p2');
            }
        });
    },

    resolveDuoRound() {
        this.isPlaying = false;
        
        const q = this.questions[this.currentIndex];
        const correct = q.correct_answer;
        const options = document.querySelectorAll('.mg-quiz-option');

        // Check Correct Answer
        let correctIdx = null;
        options.forEach(btn => {
            if (btn.innerText.includes(correct)) {
                correctIdx = parseInt(btn.dataset.idx);
                btn.classList.add('correct');
            }
        });

        // Award/penalize P1
        if (this.p1Choice === correctIdx) {
            this.p1Score += 10;
        } else {
            this.p1Score -= 5;
            options.forEach(btn => {
                if (parseInt(btn.dataset.idx) === this.p1Choice) btn.classList.add('wrong');
            });
        }

        // Award/penalize P2
        if (this.p2Choice === correctIdx) {
            this.p2Score += 10;
        } else {
            this.p2Score -= 5;
            options.forEach(btn => {
                if (parseInt(btn.dataset.idx) === this.p2Choice) btn.classList.add('wrong');
            });
        }

        // Update score display
        document.getElementById('mg-quiz-p1-score').innerText = this.p1Score;
        document.getElementById('mg-quiz-p2-score').innerText = this.p2Score;

        document.querySelectorAll('.mg-quiz-option').forEach(el => el.classList.add('disabled'));

        setTimeout(() => {
            this.isPlaying = true;
            this.p1Choice = null;
            this.p2Choice = null;
            this.currentIndex++;
            this.renderQuestion();
        }, 1500);
    },

    async useHint(type) {
        if (!this.isPlaying || this.currentIndex >= this.questions.length) return;
        
        // Duo mode has free/unlimited hints that don't deduct quota
        if (this.currentMode === 'duo') {
            MgCore.toast("Hint dinonaktifkan di mode Duo!");
            return;
        }

        const res = await MgCore.apiCall('/quiz-minigame/use-hint', { type: type });
        if (res.status !== 'success') {
            MgCore.toast(res.message || 'Hint belum tersedia.');
            return;
        }

        if (res.status === 'success') {
            this.updateHintButtons(res.autopilot);
            MgCore.toast(res.message);
            
            const q = this.questions[this.currentIndex];
            let eliminated = 0;
            document.querySelectorAll('.mg-quiz-option').forEach(el => {
                if (!el.innerText.includes(q.correct_answer) && eliminated < res.eliminate_count) {
                    el.classList.add('disabled');
                    el.style.opacity = '0.1';
                    eliminated++;
                }
            });
        } else {
            MgCore.toast(res.message);
        }
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
        clearInterval(this.timerInterval);
        clearTimeout(this.aiThinkTimer);
        document.getElementById('mg-quiz-board').style.display = 'none';
        this.setStatus('Anda Menyerah', 'Hasil disimpan sebagai kekalahan match.');
        if (!this.currentPuzzle || !this.currentPuzzle.id) {
            this.showResultPanel(this.currentMode, 'loss', { reward_amount: 0, new_balance: MgCore.balance, message: 'Arena belum siap menyimpan hasil.' });
            return;
        }
        const res = await MgCore.apiCall('/quiz-minigame/match-result', {
            puzzle_id: this.currentPuzzle.id,
            correct_count: 0,
            wrong_count: Math.max(1, this.questions.length || 1),
            total_questions: Math.max(1, this.questions.length || 1),
            clear_time: 999,
            game_mode: this.currentMode === 'rank' ? 'rank' : (this.currentMode === 'tournament' ? 'tournament' : 'have_fun'),
            difficulty: 'world_class'
        });
        if (res.status !== 'success') {
            MgCore.toast(res.message || 'Hasil belum bisa disimpan. Arena tetap bisa dilanjutkan.');
            this.showResultPanel(this.currentMode, 'loss', { reward_amount: 0, new_balance: MgCore.balance, message: res.message || 'Hasil belum bisa disimpan.' });
            return;
        }

        if (res.status === 'success') {
            if (res.new_balance !== undefined) MgCore.updateBalance(res.new_balance);
            this.updateStats({ profile: res.profile, balance: res.new_balance, autopilot: { free:0,pro:0,world_class:0 } });
            await this.safeLoadLobby();
            this.showResultPanel(this.currentMode, 'loss', res);
        }
    },
    async finishQuiz() {
        this.isPlaying = false;
        clearInterval(this.timerInterval);
        const clearTime = Math.floor((Date.now() - this.startTime) / 1000);
        document.getElementById('mg-quiz-board').style.display = 'none';

        if (this.currentMode === 'duo') {
            let winText = "Duo Selesai! Seri!";
            if (this.p1Score > this.p2Score) winText = `Player 1 (Kiri) MENANG LAGA! `;
            else if (this.p2Score > this.p1Score) winText = `Player 2 (Kanan) MENANG LAGA! `;
            this.setStatus(winText, `Skor Akhir: P1:${this.p1Score} | P2:${this.p2Score}`);
            this.showResultPanel('duo', 'draw', { reward_amount: 0, new_balance: MgCore.balance });
            return;
        }

        if (this.currentMode === 'tournament' && this.tourneyState.stage !== 3) {
            const accuracy = this.questions.length > 0 ? (this.correctCount / this.questions.length) * 100 : 0;
            const rivalTime = clearTime + Math.floor(Math.random() * 16) + 6;
            const isWin = accuracy >= 50 && clearTime < rivalTime;
            const sub = `Akurasi Anda: ${Math.round(accuracy)}%. Waktu Anda: ${clearTime}s (Rival: ${rivalTime}s)`;

            if (isWin) this.tourneyState.wins++;
            else this.tourneyState.losses++;

            if (this.tourneyState.wins >= this.tourneyState.target) {
                this.tourneyState.stage = 3;
                this.tourneyState.wins = 0;
                this.tourneyState.losses = 0;
                this.renderBracket();
                this.setStatus("Lolos ke Final!", sub);
                setTimeout(() => this.startTournamentRound(), 2200);
            } else {
                this.setStatus("Anda Tereliminasi!", `Game Over. ${sub}`);
                await this.safeLoadLobby();
                this.showResultPanel('tournament', 'loss', { reward_amount: 0, new_balance: MgCore.balance });
            }
            return;
        }

        this.setStatus("Selesai!", "Memproses hasil speedrun...");

        const diff = document.getElementById('mg-quiz-diff').value;
        if (!this.currentPuzzle || !this.currentPuzzle.id) {
            this.showResultPanel(this.currentMode, 'loss', { reward_amount: 0, new_balance: MgCore.balance, message: 'Arena belum siap menyimpan hasil.' });
            return;
        }
        const res = await MgCore.apiCall('/quiz-minigame/match-result', {
            puzzle_id: this.currentPuzzle.id,
            correct_count: this.correctCount,
            wrong_count: this.wrongCount,
            total_questions: this.questions.length,
            clear_time: clearTime,
            game_mode: this.currentMode === 'tournament' ? 'tournament' : this.currentMode,
            difficulty: diff
        });

        if (res.status !== 'success') {
            MgCore.toast(res.message || 'Hasil belum bisa disimpan. Arena tetap bisa dilanjutkan.');
            this.showResultPanel(this.currentMode, 'loss', { reward_amount: 0, new_balance: MgCore.balance, message: res.message || 'Hasil belum bisa disimpan.' });
            return;
        }

        if (res.status === 'success') {
            const rivalTime = res.rival_time ?? res.bot_time;
            const sub = `Akurasi Anda: ${res.accuracy}%. Waktu Anda: ${res.player_time}s (Rival: ${rivalTime}s)`;
            
            if (this.currentMode === 'tournament') {
                const isWin = res.is_win;
                if (isWin) this.tourneyState.wins++;
                else this.tourneyState.losses++;
                
                if (this.tourneyState.wins >= this.tourneyState.target) {
                    if (this.tourneyState.stage === 1) {
                        this.tourneyState.stage = 3;
                        this.tourneyState.wins = 0;
                        this.tourneyState.losses = 0;
                        this.renderBracket();
                        this.setStatus("Lolos ke Final!", sub);
                        setTimeout(() => this.startTournamentRound(), 3000);
                    } else if (this.tourneyState.stage === 3) {
                        this.setStatus("Juara Turnamen!", `Selamat! Reward turnamen sudah diproses. ${sub}`);
                        if (res.new_balance !== undefined) MgCore.updateBalance(res.new_balance);
                        this.updateStats({ profile: res.profile, balance: res.new_balance, autopilot: { free:0,pro:0,world_class:0 } });
                        await this.safeLoadLobby();
                        this.showResultPanel('tournament', 'win', res);
                    }
                } else if (this.tourneyState.losses >= this.tourneyState.target) {
                    this.setStatus("Anda Tereliminasi!", `Game Over. ${sub}`);
                    this.safeLoadLobby();
                } else {
                    this.setStatus(isWin ? "Menang Ronde!" : "Kalah Ronde!", `Skor: ${this.tourneyState.wins} - ${this.tourneyState.losses}. ${sub}`);
                    setTimeout(() => this.startTournamentRound(), 3000);
                }
                return;
            }

            this.setStatus(res.is_win ? "Anda Menang! " : "Anda Kalah!", sub);
            
            if (Number(res.reward_amount ?? res.reward ?? 0) > 0) MgCore.toast(`Mendapatkan Rp ${Number(res.reward_amount ?? res.reward ?? 0).toLocaleString('id-ID')}!`);
            if (res.rank_up) MgCore.toast("Naik Rank!");
            if (res.new_balance !== undefined) {
                MgCore.updateBalance(res.new_balance);
            }
            
            this.updateStats({ profile: res.profile, balance: res.new_balance, autopilot: { free:0,pro:0,world_class:0 } });
            await this.safeLoadLobby();
            this.showResultPanel(this.currentMode, res.result || (res.is_win ? 'win' : 'loss'), res);
        }
    },


    hideResultPanel() {
        const panel = document.getElementById('mg-quiz-result-panel');
        if (!panel) return;
        panel.classList.remove('active');
        panel.style.display = 'none';
    },

    formatModeLabel(mode) {
        return ({ have_fun: 'Have Fun Solo', rank: 'Ranked', tournament: 'Tournament', duo: 'Duo' }[mode] || mode || 'Arena');
    },

    showResultPanel(mode, result, payload = {}) {
        const panel = document.getElementById('mg-quiz-result-panel');
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
                <button class="mg-btn mg-btn-primary" type="button" data-mg-game="quiz" data-mg-action="result-continue">Lanjut Main</button>
                <button class="mg-btn mg-btn-secondary" type="button" data-mg-game="quiz" data-mg-action="result-lobby">Kembali ke Lobby</button>
                <button class="mg-btn mg-btn-secondary" type="button" data-mg-game="quiz" data-mg-action="result-portal">Kembali ke Portal</button>
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

    async useAutopilot() {
        await window.showAlert('Hint', 'Hint hanya tersedia saat soal sedang berjalan.');
    }
};
</script>








<?php /**PATH C:\laragon\www\ProyekTI\resources\views/components/minigame-quiz.blade.php ENDPATH**/ ?>