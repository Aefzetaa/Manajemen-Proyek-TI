@extends('layouts.app')

@section('title', 'ZoruAi')
@section('eyebrow', 'Asisten Milky Garage')

@section('content')
<style>
    /* Zoru page scroll lock */
    html, body { overflow: hidden; }
    .shell { height: var(--vh-fixed); min-height: var(--vh-fixed); overflow: hidden; }
    .main { min-height: 0; overflow: hidden; display: flex; flex-direction: column; }

    @keyframes zoruMessageIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes zoruBookBackdropIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    @keyframes zoruBookOpen {
        0% { opacity: 0; transform: translateY(24px) scale(0.9) rotateX(18deg); }
        55% { opacity: 1; transform: translateY(0) scale(1.02) rotateX(0deg); }
        100% { opacity: 1; transform: translateY(0) scale(1) rotateX(0deg); }
    }
    @keyframes zoruGlow {
        from { box-shadow: 0 0 6px var(--ok); }
        to { box-shadow: 0 0 16px var(--ok); }
    }
    
    /* Premium Zoru Ai Split Layout styling */
    .zoru-container {
        display: grid;
        grid-template-columns: 320px minmax(0, 1fr);
        gap: 20px;
        width: min(1200px, 100%);
        max-width: 1200px;
        margin: 0 auto;
        height: 100%;
        min-height: 0;
        flex: 1;
    }

    @media (max-width: 900px) {
        .zoru-container {
            grid-template-columns: 1fr;
            height: auto;
            min-height: auto;
        }
        .zoru-sidebar {
            display: none !important;
        }
    }

    /* Left Sidebar styling */
    .zoru-sidebar {
        background: var(--panel);
        border: 1px solid var(--line);
        border-radius: 16px;
        padding: 24px;
        display: flex;
        flex-direction: column;
        gap: 24px;
        box-shadow: var(--shadow-soft);
        overflow: hidden;
        height: 100%;
        min-width: 0;
    }

    /* Zoru Cards in Sidebar */
    .zoru-card {
        background: var(--bg-soft);
        border: 1px solid var(--line);
        border-radius: 12px;
        padding: 18px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 12px;
        transition: all 0.2s ease;
    }
    .zoru-card:hover {
        transform: translateY(-2px);
        border-color: var(--primary);
        box-shadow: var(--shadow-soft);
    }

    .user-avatar-ring {
        position: relative;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary), var(--accent, #c36a16));
        padding: 3px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px var(--primary-soft);
    }
    .user-avatar-initials {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: var(--panel-solid);
        color: var(--ink);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 18px;
        text-transform: uppercase;
    }
    .user-role-badge {
        position: absolute;
        bottom: -4px;
        background: var(--primary);
        color: #fff;
        font-size: 9px;
        font-weight: 800;
        padding: 2px 8px;
        border-radius: 10px;
        text-transform: uppercase;
        border: 1.5px solid var(--panel-solid);
    }

    .user-details {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    .user-name {
        margin: 0;
        font-size: 15px;
        font-weight: 800;
        color: var(--ink);
    }
    .user-role {
        font-size: 11px;
        color: var(--muted);
        font-weight: 600;
    }

    .user-balance-section {
        width: 100%;
        border-top: 1px dashed var(--line);
        padding-top: 12px;
        margin-top: 4px;
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    .balance-label {
        font-size: 10px;
        font-weight: 800;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: 0.6px;
    }
    .balance-value {
        font-size: 16px;
        font-weight: 900;
        color: var(--primary);
    }

    /* Shortcuts list */
    .zoru-sidebar-section {
        display: flex;
        flex-direction: column;
        gap: 12px;
        flex: 1;
        overflow: hidden;
    }
    .section-title {
        margin: 0;
        font-size: 11px;
        font-weight: 800;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: 0.8px;
        text-align: center;
    }
    .zoru-shortcuts-grid {
        display: flex;
        flex-direction: column;
        gap: 8px;
        overflow-y: auto;
        overflow-x: hidden;
        flex: 1;
        min-height: 0;
        padding-right: 4px;
    }
    .zoru-shortcuts-grid::-webkit-scrollbar {
        width: 5px;
    }
    .zoru-shortcuts-grid::-webkit-scrollbar-track {
        background: transparent;
    }
    .zoru-shortcuts-grid::-webkit-scrollbar-thumb {
        background: rgba(32, 188, 168, 0.25);
        border-radius: 4px;
    }
    .zoru-shortcuts-grid::-webkit-scrollbar-thumb:hover {
        background: var(--primary);
    }
    .zoru-shortcut-btn {
        background: var(--bg-soft);
        border: 1px solid var(--line);
        border-radius: 10px;
        padding: 10px 12px;
        display: flex;
        align-items: center;
        gap: 10px;
        text-align: left;
        cursor: pointer;
        width: 100%;
        transition: all 0.15s ease;
    }
    .zoru-shortcut-btn:hover {
        background: var(--primary-soft);
        border-color: var(--primary);
        transform: translateX(3px);
    }
    .zoru-shortcut-btn .icon {
        width: 28px;
        height: 28px;
        display: inline-grid;
        place-items: center;
        flex-shrink: 0;
        color: var(--primary-dark);
        border: 1px solid color-mix(in srgb, var(--primary) 34%, transparent);
        border-radius: 9px;
        background: color-mix(in srgb, var(--primary-soft) 36%, transparent);
    }
    .zoru-shortcut-btn .icon svg {
        width: 16px;
        height: 16px;
        display: block;
        fill: none;
        stroke: currentColor;
        stroke-width: 2;
        stroke-linecap: round;
        stroke-linejoin: round;
    }
    .zoru-shortcut-btn .label {
        font-size: 12px;
        font-weight: 700;
        color: var(--ink);
        line-height: 1.3;
    }
    .zoru-shortcut-btn:hover .label {
        color: var(--primary-dark);
    }
    .zoru-shortcut-btn:hover .icon {
        border-color: color-mix(in srgb, var(--primary) 48%, transparent);
        background: color-mix(in srgb, var(--primary-soft) 52%, transparent);
    }
    .zoru-shortcut-btn.btn-hidden-gem {
        min-height: 44px;
        padding: 10px 12px !important;
        border-radius: 10px !important;
        justify-content: flex-start;
        color: #e9d5ff !important;
        transform: none;
    }
    .zoru-shortcut-btn.btn-hidden-gem .icon {
        position: relative;
        width: 18px;
        height: 15px;
        font-size: 0;
        border: 0;
        border-radius: 0;
        background: transparent;
        filter: drop-shadow(0 0 8px rgba(168, 85, 247, 0.34));
    }
    .zoru-shortcut-btn.btn-hidden-gem .icon::before,
    .zoru-shortcut-btn.btn-hidden-gem .icon::after {
        content: '';
        position: absolute;
        inset: 0;
        clip-path: polygon(18% 0, 82% 0, 100% 42%, 50% 100%, 0 42%);
    }
    .zoru-shortcut-btn.btn-hidden-gem .icon::before {
        background: linear-gradient(90deg, #1d9bf0 0 28%, #67e8f9 28% 52%, #38bdf8 52% 74%, #2563eb 74% 100%);
    }
    .zoru-shortcut-btn.btn-hidden-gem .icon::after {
        inset: 0 5px;
        background: linear-gradient(180deg, rgba(255,255,255,0.65), rgba(147,197,253,0.12) 48%, rgba(30,64,175,0.55));
        opacity: 0.78;
    }
    .zoru-shortcut-btn.btn-hidden-gem .label {
        color: #f5e8ff;
        font-weight: 850;
    }
    .zoru-shortcut-btn.btn-hidden-gem::after {
        margin-left: auto;
        flex-shrink: 0;
    }
    .zoru-shortcut-btn.btn-hidden-gem:hover {
        transform: translateX(3px);
    }

    /* Sidebar compliance badge */
    .zoru-sidebar-footer {
        margin-top: auto;
    }
    .compliance-badge {
        background: rgba(16, 185, 129, 0.08);
        border: 1px solid rgba(16, 185, 129, 0.2);
        border-radius: 12px;
        padding: 12px;
        display: flex;
        gap: 10px;
    }
    .compliance-badge .badge-icon {
        font-size: 18px;
        margin-top: 1px;
    }
    .compliance-badge .badge-text {
        display: flex;
        flex-direction: column;
        gap: 2px;
        text-align: left;
    }
    .compliance-badge .badge-text strong {
        font-size: 11px;
        color: #10b981;
        font-weight: 800;
    }
    .compliance-badge .badge-text span {
        font-size: 10px;
        color: var(--muted);
        line-height: 1.4;
        font-weight: 500;
    }

    /* Right Main Panel styling */
    .zoru-main {
        background: var(--panel);
        border: 1px solid var(--line);
        border-radius: 16px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        box-shadow: var(--shadow-soft);
        width: 100%;
        min-width: 0;
        height: 100%;
        min-height: 0;
    }

    /* Header styling */
    .zoru-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 24px;
        background: var(--panel);
        border-bottom: 1px solid var(--line);
        z-index: 5;
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .status-dot-pulse {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: var(--ok);
        box-shadow: 0 0 10px var(--ok);
        animation: zoruGlow 2s infinite alternate;
    }
    .zoru-robot-icon {
        width: 34px;
        height: 34px;
        border-radius: 12px;
        border: 1px solid color-mix(in srgb, var(--primary) 42%, transparent);
        background: linear-gradient(135deg, color-mix(in srgb, var(--primary) 20%, var(--panel)), color-mix(in srgb, #4f7cff 14%, var(--panel)));
        display: grid;
        place-items: center;
        position: relative;
        box-shadow: 0 12px 28px color-mix(in srgb, var(--primary) 16%, transparent);
        flex-shrink: 0;
    }
    .zoru-robot-icon::before {
        content: "";
        position: absolute;
        top: 5px;
        left: 50%;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--accent);
        transform: translateX(-50%);
    }
    .zoru-robot-icon::after {
        content: "";
        width: 24px;
        height: 17px;
        border-radius: 8px;
        border: 1px solid color-mix(in srgb, var(--primary-dark) 55%, transparent);
        background:
            radial-gradient(circle at 8px 8px, var(--primary-dark) 0 3px, transparent 4px),
            radial-gradient(circle at 16px 8px, var(--primary-dark) 0 3px, transparent 4px),
            color-mix(in srgb, var(--bg) 72%, transparent);
        box-shadow: 0 0 12px color-mix(in srgb, var(--primary) 30%, transparent);
        transform: translateY(3px);
    }
    .header-titles {
        display: flex;
        flex-direction: column;
        text-align: left;
    }
    .header-title {
        margin: 0;
        font-size: 16px;
        font-weight: 800;
        color: var(--ink);
    }
    .header-subtitle {
        font-size: 10px;
        font-weight: 800;
        color: var(--primary-dark);
        text-transform: uppercase;
        letter-spacing: 0.6px;
    }

    .header-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        justify-content: flex-end;
    }
    .zoru-action-btn {
        background: var(--bg-soft);
        border: 1px solid var(--line);
        border-radius: 8px;
        padding: 6px 12px;
        font-size: 12px;
        font-weight: 800;
        color: var(--ink);
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.15s ease;
    }
    .zoru-action-btn:hover {
        border-color: var(--primary);
        color: var(--primary);
        transform: translateY(-1px);
    }
    .btn-hidden-gem {
        position: relative;
        isolation: isolate;
        overflow: hidden;
        min-height: 38px;
        max-width: 100%;
        padding-inline: 14px !important;
        border-radius: 12px !important;
        background: linear-gradient(135deg, rgba(20, 184, 166, 0.12), rgba(124, 58, 237, 0.14), rgba(245, 158, 11, 0.1)) !important;
        border-color: rgba(124, 58, 237, 0.34) !important;
        color: #e9d5ff !important;
        box-shadow: 0 8px 20px rgba(88, 28, 135, 0.14), inset 0 0 0 1px rgba(255,255,255,0.04);
        line-height: 1.15;
        white-space: nowrap;
    }
    .btn-hidden-gem::before {
        content: '';
        position: absolute;
        inset: -60% -35%;
        z-index: -1;
        background: linear-gradient(115deg, transparent 28%, rgba(255,255,255,0.22) 45%, transparent 62%);
        transform: translateX(-58%) rotate(8deg);
        transition: transform 0.55s ease;
    }
    .btn-hidden-gem::after {
        content: 'Soon';
        margin-left: 4px;
        padding: 2px 7px;
        border-radius: 999px;
        background: rgba(255,255,255,0.1);
        color: #fde68a;
        font-size: 9px;
        font-weight: 900;
        letter-spacing: 0.4px;
        text-transform: uppercase;
    }
    .btn-hidden-gem:hover {
        border-color: rgba(245, 158, 11, 0.52) !important;
        box-shadow: 0 10px 24px rgba(124, 58, 237, 0.2), 0 0 14px rgba(245, 158, 11, 0.12);
    }
    .btn-hidden-gem:hover::before {
        transform: translateX(58%) rotate(8deg);
    }
    @media (max-width: 720px) {
        .header-actions {
            justify-content: flex-start;
        }
        .btn-hidden-gem {
            white-space: normal;
            text-align: left;
        }
    }
    .btn-executive {
        background: var(--primary-soft);
        color: var(--primary-dark);
        border-color: rgba(32, 188, 168, 0.2);
    }
    .btn-executive:hover {
        background: var(--primary);
        color: #fff;
        border-color: var(--primary);
    }
    .btn-owner-access {
        background: linear-gradient(145deg, rgba(245, 158, 11, 0.13), rgba(195, 106, 22, 0.08));
        color: #f4a447;
        border-color: rgba(245, 158, 11, 0.28);
        box-shadow: inset 0 0 0 1px rgba(245, 158, 11, 0.04);
    }
    .btn-owner-access:hover {
        background: linear-gradient(145deg, rgba(245, 158, 11, 0.2), rgba(195, 106, 22, 0.12));
        color: #ffd08a;
        border-color: rgba(245, 158, 11, 0.42);
        box-shadow: 0 10px 24px rgba(195, 106, 22, 0.12);
    }

    /* Chatbox styling */
    .zoru-chatbox {
        flex: 1;
        padding: 24px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 18px;
        background: var(--panel-solid);
        scroll-behavior: smooth;
    }
    .zoru-trendsbox {
        flex: 1;
        width: 100%;
        min-height: 0;
        padding: 24px;
        overflow-y: auto;
        background: var(--panel-solid);
        flex-direction: column;
        gap: 20px;
    }
    .zoru-trendsbox .panel {
        width: 100%;
    }
    .zoru-trends-filter {
        flex: 0 0 auto;
        margin: 0;
        padding: 16px;
    }
    .zoru-trends-chart-panel {
        flex: 1;
        min-height: 0;
        padding: 24px;
        display: flex;
        flex-direction: column;
    }
    .zoru-trends-chart-grid {
        flex: 1;
        min-height: 320px;
    }

    /* Bubbles styling */
    .zoru-bubble {
        max-width: 80%;
        padding: 14px 18px;
        border-radius: 16px;
        font-size: 14px;
        line-height: 1.6;
        box-shadow: var(--shadow-sm);
        animation: zoruMessageIn 0.22s ease-out forwards;
        text-align: left;
        white-space: pre-wrap;
    }
    .zoru-bubble-ai {
        align-self: flex-start;
        background: var(--panel);
        border: 1px solid var(--line);
        color: var(--ink);
        border-top-left-radius: 4px;
    }
    .zoru-bubble-user {
        align-self: flex-end;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: #ffffff;
        border-top-right-radius: 4px;
        box-shadow: 0 4px 15px var(--primary-soft);
    }

    /* Zoru action cards inside Chatbox */
    .zoru-action-card {
        align-self: flex-start;
        width: 100%;
        max-width: 400px;
        background: var(--panel);
        border: 1px solid var(--line);
        border-radius: 14px;
        padding: 18px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        box-shadow: var(--shadow-soft);
        animation: zoruMessageIn 0.22s ease-out forwards;
        text-align: left;
    }
    .zoru-action-card h3 {
        margin: 0;
        font-size: 14px;
        font-weight: 800;
        color: var(--ink);
        border-bottom: 1px dashed var(--line);
        padding-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .zoru-action-row {
        display: flex;
        justify-content: space-between;
        font-size: 12px;
        color: var(--ink);
        font-weight: 600;
    }
    .zoru-action-row span:first-child {
        color: var(--muted);
    }
    .zoru-action-row span:last-child {
        font-weight: 700;
    }

    /* Form bar styling */
    .zoru-form-bar {
        padding: 16px 24px;
        background: var(--panel);
        border-top: 1px solid var(--line);
        z-index: 5;
    }
    .zoru-form-container {
        display: flex;
        gap: 10px;
        margin: 0;
    }
    .zoru-input {
        flex: 1;
        padding: 12px 16px;
        border-radius: 10px;
        border: 1px solid var(--line);
        background: var(--bg-soft);
        color: var(--ink);
        font-size: 13px;
        font-weight: 600;
        transition: all 0.15s ease;
    }
    .zoru-input:focus {
        outline: none;
        border-color: var(--primary);
        background: var(--panel-solid);
        box-shadow: 0 0 0 3px var(--focus);
    }
    .zoru-send-btn {
        background: var(--primary);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 0 20px;
        font-size: 13px;
        font-weight: 800;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.15s ease;
        box-shadow: 0 4px 12px var(--primary-soft);
    }
    .zoru-send-btn:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 6px 16px var(--primary-soft);
    }
    .zoru-send-btn:active {
        transform: translateY(0);
    }
    
    /* Guide Book Styles */
    .zoru-book-modal {
        position: fixed;
        inset: 0;
        z-index: 100;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 24px;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(6px);
    }
    .zoru-book-modal.is-open {
        display: flex;
        animation: zoruBookBackdropIn .18s ease-out;
    }
    .zoru-book {
        position: relative;
        width: min(560px, calc(100vw - 48px));
        height: min(520px, calc(var(--vh-fixed, 100vh) - 96px));
        min-height: 360px;
        flex: 0 0 auto;
        background: var(--panel);
        border: 1px solid var(--line);
        border-radius: 16px;
        padding: 32px 32px 72px;
        box-shadow: var(--shadow-soft);
        overflow: hidden;
        animation: zoruBookOpen 0.32s cubic-bezier(0.19, 1, 0.22, 1);
        text-align: left;
    }
    .zoru-book-close {
        position: absolute;
        top: 20px;
        right: 20px;
        background: transparent;
        border: none;
        color: var(--muted);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: color 0.15s ease;
    }
    .zoru-book-close:hover {
        color: var(--danger);
    }
    .zoru-book-nav {
        background: var(--panel);
        border: 1px solid var(--line);
        color: var(--ink);
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .zoru-book-nav:hover:not([disabled]) {
        border-color: var(--primary);
        color: var(--primary);
    }
    .zoru-book-nav[disabled] {
        opacity: 0.35;
        cursor: default;
    }

    /* Glassmorphism Skills Dropdown Autocomplete styling */
    .zoru-skills-dropdown {
        position: absolute;
        bottom: calc(100% + 10px);
        left: 24px;
        right: 24px;
        background: rgba(15, 23, 42, 0.88);
        backdrop-filter: blur(14px) saturate(180%);
        -webkit-backdrop-filter: blur(14px) saturate(180%);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 12px;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.5);
        max-height: 240px;
        overflow: hidden;
        display: none;
        z-index: 999;
        padding: 6px;
    }
    .zoru-skill-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 14px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.15s ease;
        border-bottom: 1px solid rgba(255, 255, 255, 0.02);
    }
    .zoru-skill-item:last-child {
        border-bottom: none;
    }
    .zoru-skill-item:hover, .zoru-skill-item.is-active {
        background: rgba(245, 158, 11, 0.15);
        color: #fff;
    }
    .zoru-skill-item .skill-cmd {
        font-family: monospace;
        font-weight: 800;
        font-size: 13.5px;
        color: #f59e0b;
    }
    .zoru-skill-item .skill-desc {
        font-size: 11.5px;
        color: var(--muted);
        font-weight: 500;
    }
    
    /* Interactive Clickable Command Tags in Chat Bubbles */
    .zoru-clickable-cmd {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 30px;
        padding: 0 12px;
        margin: 0 4px;
        border: 1px solid color-mix(in srgb, var(--primary) 34%, var(--line));
        border-radius: 999px;
        background: color-mix(in srgb, var(--panel-solid) 86%, var(--primary-soft));
        color: var(--ink);
        font-family: inherit;
        font-size: 12.5px;
        line-height: 1;
        font-weight: 900;
        text-decoration: none;
        cursor: pointer;
        vertical-align: baseline;
        box-shadow: 0 8px 20px color-mix(in srgb, var(--primary) 8%, transparent);
        transition: border-color .18s ease, background .18s ease, transform .18s ease, color .18s ease;
    }
    .zoru-clickable-cmd:hover {
        border-color: color-mix(in srgb, var(--primary) 58%, transparent);
        background: color-mix(in srgb, var(--primary-soft) 82%, var(--panel-solid));
        color: var(--primary-dark);
        text-decoration: none;
        transform: translateY(-1px);
    }
    .zoru-clickable-cmd:active {
        transform: translateY(0);
    }
</style>

@php
    $userVal = trim(auth()->user()->username);
    $words = preg_split('/[_\s-]+/', $userVal);
    $initials = '';
    if (count($words) >= 2) {
        $initials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
    } else {
        $initials = strtoupper(substr($userVal, 0, min(2, strlen($userVal))));
    }
    
    $allServices = \App\Models\ServiceType::with('activePromotions')
        ->orderBy('name')
        ->get(['id', 'name', 'base_price', 'estimated_minutes', 'mechanic_salary', 'cashier_salary'])
        ->map(fn ($service) => [
            'id' => $service->id,
            'name' => $service->name,
            'base_price' => $service->base_price,
            'estimated_minutes' => $service->estimated_minutes,
            'mechanic_salary' => $service->mechanic_salary,
            'cashier_salary' => $service->cashier_salary,
            'discount_percent' => $service->discountPercent(),
            'discounted_price' => $service->discountedPrice(),
        ]);
    $allSpareParts = \App\Models\SparePart::orderBy('name')->get(['id', 'name', 'price', 'stock']);
    $userVehicles = auth()->user()->isRole('customer') ? auth()->user()->vehicles()->orderBy('plate_number')->get(['id', 'plate_number', 'brand', 'model']) : collect();
@endphp

<div class="zoru-container">
    
    <!-- LEFT SIDEBAR -->
    <aside class="zoru-sidebar">
        <!-- USER / OWNER INFO -->
        <div class="zoru-card">
            <div class="user-avatar-ring">
                <span class="user-avatar-initials">{{ $initials }}</span>
                <span class="user-role-badge" style="background:var(--primary); color:#fff; font-size:9px; font-weight:800; padding:2px 8px; border-radius:10px; text-transform:uppercase; border:1.5px solid var(--panel-solid); position:absolute; bottom:-4px; white-space:nowrap;">{{ auth()->user()->roleLabel() }}</span>
            </div>
            <div class="user-details">
                <h3 class="user-name">{{ auth()->user()->username }}</h3>
                <span class="user-role" style="font-size: 11px; color: var(--muted); font-weight: 500; word-break: break-all;">{{ auth()->user()->email }}</span>
            </div>
        </div>

        <!-- QUICK PINTASAN SHORTCUTS -->
        <div class="zoru-sidebar-section">
            <h4 class="section-title">Pintasan Asisten</h4>
            <div class="zoru-shortcuts-grid">
                @if(auth()->user()->isRole('owner'))
                    <button type="button" class="zoru-shortcut-btn" onclick="submitZoruPrompt('analisis trend')">
                        <span class="icon" data-icon="TR"></span>
                        <span class="label">Analisis Trend</span>
                    </button>
                    <button type="button" class="zoru-shortcut-btn" onclick="submitZoruPrompt('restock')">
                        <span class="icon" data-icon="RS"></span>
                        <span class="label">Restock sparepart</span>
                    </button>
                    <button type="button" class="zoru-shortcut-btn" onclick="submitZoruPrompt('tambah servis')">
                        <span class="icon" data-icon="SV"></span>
                        <span class="label">Tambah jenis servis</span>
                    </button>
                    <button type="button" class="zoru-shortcut-btn" onclick="submitZoruPrompt('diskon')">
                        <span class="icon" data-icon="DS"></span>
                        <span class="label">Pasang diskon servis</span>
                    </button>
                    <button type="button" class="zoru-shortcut-btn" onclick="submitZoruPrompt('ubah harga')">
                        <span class="icon" data-icon="HG"></span>
                        <span class="label">Ubah harga servis</span>
                    </button>
                    <button type="button" class="zoru-shortcut-btn" onclick="submitZoruPrompt('gaji')">
                        <span class="icon" data-icon="GJ"></span>
                        <span class="label">Atur gaji karyawan</span>
                    </button>
                    <button type="button" class="zoru-shortcut-btn" onclick="submitZoruPrompt('topup')">
                        <span class="icon" data-icon="UP"></span>
                        <span class="label">Top-up saldo ZeroPay</span>
                    </button>
                    <button type="button" class="zoru-shortcut-btn" onclick="submitZoruPrompt('withdraw')">
                        <span class="icon" data-icon="WD"></span>
                        <span class="label">Penarikan dana</span>
                    </button>
                @elseif(auth()->user()->isRole('cashier'))
                    <button type="button" class="zoru-shortcut-btn" onclick="submitZoruPrompt('topup')">
                        <span class="icon" data-icon="UP"></span>
                        <span class="label">Top-up ZeroPay</span>
                    </button>
                    <button type="button" class="zoru-shortcut-btn" onclick="submitZoruPrompt('withdraw')">
                        <span class="icon" data-icon="WD"></span>
                        <span class="label">Tarik dana ZeroPay</span>
                    </button>
                    <button type="button" class="zoru-shortcut-btn" onclick="submitZoruPrompt('gaji saya')">
                        <span class="icon" data-icon="GJ"></span>
                        <span class="label">Laporan Gaji</span>
                    </button>
                @elseif(auth()->user()->isRole('mechanic'))
                    <button type="button" class="zoru-shortcut-btn" onclick="submitZoruPrompt('topup')">
                        <span class="icon" data-icon="UP"></span>
                        <span class="label">Top-up ZeroPay</span>
                    </button>
                    <button type="button" class="zoru-shortcut-btn" onclick="submitZoruPrompt('withdraw')">
                        <span class="icon" data-icon="WD"></span>
                        <span class="label">Tarik dana ZeroPay</span>
                    </button>
                    <button type="button" class="zoru-shortcut-btn" onclick="submitZoruPrompt('gaji saya')">
                        <span class="icon" data-icon="GJ"></span>
                        <span class="label">Laporan Gaji</span>
                    </button>
                    <button type="button" class="zoru-shortcut-btn" onclick="submitZoruPrompt('acc booking')">
                        <span class="icon" data-icon="BK"></span>
                        <span class="label">ACC Booking</span>
                    </button>
                @else {{-- Customer --}}
                    <button type="button" class="zoru-shortcut-btn" onclick="submitZoruPrompt('booking')">
                        <span class="icon" data-icon="BK"></span>
                        <span class="label">Booking Service Baru</span>
                    </button>
                    <button type="button" class="zoru-shortcut-btn" onclick="submitZoruPrompt('topup')">
                        <span class="icon" data-icon="UP"></span>
                        <span class="label">Top-up ZeroPay</span>
                    </button>
                    <button type="button" class="zoru-shortcut-btn" onclick="submitZoruPrompt('withdraw')">
                        <span class="icon" data-icon="WD"></span>
                        <span class="label">Tarik dana ZeroPay</span>
                    </button>
                    <button type="button" class="zoru-shortcut-btn" onclick="submitZoruPrompt('rincian zeropay')">
                        <span class="icon" data-icon="ZP"></span>
                        <span class="label">Rincian ZeroPay</span>
                    </button>
                @endif
                <button type="button" class="zoru-shortcut-btn btn-hidden-gem" id="hiddenGemShortcutBtn">
                    <span class="icon" data-icon="HGEM" aria-hidden="true"></span>
                    <span class="label">Hidden Gem</span>
                </button>
            </div>
        </div>

        <!-- STATUS BADGE -->
        <div class="zoru-sidebar-footer">
            <div class="compliance-badge">
                <span class="badge-icon" data-icon="OK"></span>
                <div class="badge-text">
                    <strong>Area Bantuan Siap</strong>
                    <span>ZoruAi membantu kebutuhan layanan dan transaksi Anda.</span>
                </div>
            </div>
        </div>
    </aside>

    <!-- RIGHT MAIN CHAT AREA -->
    <main class="zoru-main">
        
        <!-- HEADER -->
        <div class="zoru-header">
            <div class="header-left">
                <span class="zoru-robot-icon" aria-hidden="true"></span>
                <div class="header-titles">
                    <h2 class="header-title">ZoruAi Assistant</h2>
                    <span class="header-subtitle" style="color:var(--ok);">Online</span>
                </div>
            </div>
            
            <div class="header-actions" style="display:flex; gap:10px; align-items:center;">
                @if(auth()->user()->isRole('owner'))
                    <!-- TABS FOR OWNER -->
                    <div style="display:flex; gap:4px; background:var(--bg-soft); padding:4px; border-radius:10px; border:1px solid var(--line); margin-right:10px;" class="no-print">
                        <button type="button" class="zoru-action-btn btn-executive active" id="tabChatBtn" style="margin:0; border:none;" onclick="switchAnalyticsTab('chat')">
                             Chat Asisten
                        </button>
                        <button type="button" class="zoru-action-btn" id="tabTrendsBtn" style="margin:0; border:none;" onclick="switchAnalyticsTab('trends')">
                             Analitik Tren
                        </button>
                    </div>

                    <button type="button" class="zoru-action-btn btn-owner-access" id="executiveModeBtn">
                         Hak Akses Owner
                    </button>
                    <button type="button" class="zoru-action-btn btn-guide" id="guideBookButton">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                            <path d="M8 6h8"></path>
                            <path d="M8 10h6"></path>
                        </svg>
                        Panduan
                    </button>
                @else
                    <button type="button" class="zoru-action-btn btn-guide" id="guideBookButton">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                            <path d="M8 6h8"></path>
                            <path d="M8 10h6"></path>
                        </svg>
                        Panduan
                    </button>
                @endif
            </div>
        </div>
        
        <!-- MESSAGES BOX -->
        <div id="chatBox" class="zoru-chatbox">
            <div class="zoru-bubble zoru-bubble-ai" id="zoruWelcomeBubble"><strong>Halo {{ auth()->user()->username }}! </strong>

Saya ZoruAi Assistant, pendamping layanan Milky Garage. Anda dapat meminta bantuan diagnosa ringan, formulir layanan, transaksi ZeroPay, dan kebutuhan bengkel lainnya.

<em>Tanyakan sesuatu atau pilih salah satu pintasan asisten di sebelah kiri untuk memulai percakapan kita!</em></div>
        </div>
        
        <!-- FORM BAR -->
        <div class="zoru-form-bar" style="position:relative;">
            <!-- Floating Autocomplete Dropdown -->
            <div id="zoruSkillsDropdown" class="zoru-skills-dropdown"></div>
            
            <form id="zoruForm" class="zoru-form-container">
                <input type="text" id="zoruPrompt" class="zoru-input" placeholder="apa yang bisa saya bantu hari ini?" required autocomplete="off">
                <button type="submit" class="zoru-send-btn">
                    <span>Kirim</span>
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                </button>
            </form>
        </div>

        @if(auth()->user()->isRole('owner'))
            <!-- TRENDS PANEL -->
            <div id="trendsBox" class="zoru-trendsbox" style="display:none;">
                
                <!-- Filter Toolbar -->
                <div class="panel zoru-trends-filter">
                    <form method="GET" action="{{ route('reports.analytics') }}" id="trendsFilterForm" class="toolbar" style="justify-content:space-between;">
                        <input type="hidden" name="tab" value="trends">
                        <div style="display:flex; align-items:center; gap:14px; flex-wrap:wrap;">
                            <div style="display:flex; align-items:center; gap:10px;">
                                <label for="service_type_id" style="font-weight:700; font-size:14px; color:var(--muted);">Filter Jenis Servis:</label>
                                <select name="service_type_id" id="service_type_id" onchange="document.getElementById('trendsFilterForm').submit()" style="max-width:250px;">
                                    <option value="">Semua Jenis Servis</option>
                                    @foreach($serviceTypes as $type)
                                        <option value="{{ $type->id }}" @selected((string) request('service_type_id') === (string) $type->id)>{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div style="display:flex; align-items:center; gap:10px;">
                                <label for="quarter" style="font-weight:700; font-size:14px; color:var(--muted);">Periode:</label>
                                <select name="quarter" id="quarter" onchange="document.getElementById('trendsFilterForm').submit()" style="max-width:230px;">
                                    @foreach($quarterOptions as $quarterValue => $quarter)
                                        <option value="{{ $quarterValue }}" @selected((int) $selectedQuarter === (int) $quarterValue)>
                                            {{ $quarter['label'] }} ({{ $quarter['range'] }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @if(request('service_type_id') || (int) request('quarter', 1) !== 1)
                            <a class="button secondary small" href="{{ route('reports.analytics', ['tab' => 'trends']) }}">Reset Filter</a>
                        @endif
                    </form>
                </div>

                <!-- Chart Panel -->
                <div class="panel zoru-trends-chart-panel">
                    <h3 style="margin-top:0; font-size:18px; font-weight:800; border-bottom:1px solid var(--line); padding-bottom:12px; margin-bottom:20px; display:flex; align-items:center; gap:8px;">
                         Tren Jumlah Servis Bulanan
                        <span style="font-size:13px; font-weight:600; color:var(--primary-dark); background:var(--primary-soft); padding:3px 10px; border-radius:20px;">
                            {{ $trendYear }}
                        </span>
                        @if(request('service_type_id') && $serviceTypes->firstWhere('id', request('service_type_id')))
                            <span style="font-size:13px; font-weight:600; color:var(--primary-dark); background:var(--primary-soft); padding:3px 10px; border-radius:20px;">
                                {{ $serviceTypes->firstWhere('id', request('service_type_id'))->name }}
                            </span>
                        @endif
                    </h3>

                    @php($maxTrend = max($monthlyTrends->max('count'), 1))
                    <div class="zoru-trends-chart-grid" style="display:grid; grid-template-columns: repeat(6, 1fr); gap:16px; align-items:end; padding-top:20px; border-bottom:2px solid var(--line); margin-bottom:16px; position:relative;">
                        @foreach($monthlyTrends as $trend)
                            @php($percent = ($trend['count'] / $maxTrend) * 100)
                            <div style="display:flex; flex-direction:column; align-items:center; gap:12px; height:100%; justify-content:flex-end;">
                                <!-- Tooltip/Count -->
                                <div style="background:var(--bg-soft); border:1px solid var(--line); border-radius:6px; padding:4px 10px; font-size:13px; font-weight:800; color:var(--primary-dark); box-shadow:var(--shadow-soft);">
                                    {{ $trend['count'] }} Servis
                                </div>
                                
                                <!-- Bar -->
                                <div style="width:100%; max-width:60px; height:{{ $percent }}%; min-height:8px; background:linear-gradient(180deg, var(--primary), var(--primary-soft)); border-radius:8px 8px 0 0; transition:height 0.4s ease; box-shadow:0 0 15px var(--primary-soft); position:relative; overflow:hidden;">
                                    <!-- Shimmer effect -->
                                    <div style="position:absolute; inset:0; background:linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent); animation:shimmer 2s infinite; transform:skewX(-20deg);"></div>
                                </div>

                                <!-- Label Month -->
                                <div style="font-size:12px; font-weight:700; color:var(--muted); text-align:center; padding-bottom:8px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; width:100%;">
                                    {{ $trend['month_name'] }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <p class="muted" style="font-size:12px; margin:0; text-align:right;">* Menampilkan statistik data penyelesaian servis lunas periode {{ $quarterOptions[$selectedQuarter]['range'] }} {{ $trendYear }}.</p>
                </div>
            </div>
        @endif
        
    </main>
</div>

<!-- PANDUAN PINTAR (GUIDE BOOK) MODAL -->
<div class="zoru-book-modal" id="zoruGuideModal">
    <div class="zoru-book">
        <button type="button" class="zoru-book-close" id="closeGuideModal" title="Tutup">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
        
        @if(auth()->user()->isRole('owner'))
            <!-- GUIDE FOR OWNER -->
            <!-- Halaman 1 -->
            <div class="zoru-book-page is-active" data-book-page style="display:flex; flex-direction:column; gap:16px;">
                <h2 style="margin:0; font-size:22px; font-weight:900; color:var(--ink);">Panduan Owner ZoruAi</h2>
                <p style="margin:0; font-size:13px; line-height:1.6; color:var(--ink);">ZoruAi membantu Owner membaca kondisi bengkel dan membuka aksi operasional penting tanpa keluar dari halaman Agent AI.</p>
                <h3 style="margin:8px 0 0; font-size:14px; font-weight:800; color:var(--ink);">Yang bisa dilakukan ZoruAi:</h3>
                <ul style="margin:0; padding-left:20px; font-size:13px; line-height:1.7; color:var(--ink);">
                    <li><strong>Analisis Trend:</strong> membaca omzet, layanan populer, performa mekanik, dan kondisi stok.</li>
                    <li><strong>Master Data:</strong> membuka form ubah harga, pasang diskon, tambah servis, restock sparepart, dan atur gaji karyawan.</li>
                    <li><strong>ZeroPay Owner:</strong> membuka top-up, penarikan dana, serta menjelaskan rincian saldo.</li>
                </ul>
            </div>
            <!-- Halaman 2 -->
            <div class="zoru-book-page" data-book-page style="display:none; flex-direction:column; gap:16px;">
                <h2 style="margin:0; font-size:20px; font-weight:900; color:var(--ink);">Analisis Trend & Keputusan</h2>
                <p style="margin:0; font-size:13px; line-height:1.6; color:var(--ink);">Saat memilih <strong>Analisis Trend</strong>, ZoruAi menyediakan pilihan Bulan Ini, 3 Bulan Terakhir, dan 6 Bulan Terakhir.</p>
                <div style="background:var(--bg-soft); border:1px solid var(--line); border-radius:10px; padding:12px; margin:4px 0;">
                    <strong style="font-size:11px; color:var(--muted); text-transform:uppercase;">Hasil yang dibaca:</strong>
                    <ul style="margin:8px 0 0; padding-left:18px; font-size:12.5px; line-height:1.7; color:var(--ink);">
                        <li>Omzet pada periode yang dipilih.</li>
                        <li>Tren layanan yang paling sering dikerjakan.</li>
                        <li>Performa mekanik berdasarkan servis selesai.</li>
                        <li>Kondisi stok sparepart yang perlu perhatian.</li>
                        <li>Saran tindakan operasional untuk owner.</li>
                    </ul>
                </div>
                <p style="margin:0; font-size:12.5px; line-height:1.6; color:var(--ink);">Jika data belum cukup, ZoruAi akan menjelaskan bahwa analisis belum dapat disimpulkan dengan aman.</p>
            </div>
            <!-- Halaman 3 -->
            <div class="zoru-book-page" data-book-page style="display:none; flex-direction:column; gap:16px;">
                <h2 style="margin:0; font-size:20px; font-weight:900; color:var(--ink);">Master Data Lewat ZoruAi</h2>
                <p style="margin:0; font-size:13px; line-height:1.6; color:var(--ink);">Owner bisa meminta ZoruAi membuka form cepat untuk pengaturan data bengkel.</p>
                <div style="background:var(--bg-soft); border:1px solid var(--line); border-radius:10px; padding:12px; margin:4px 0;">
                    <strong style="font-size:11px; color:var(--muted); text-transform:uppercase;">Contoh pintasan:</strong>
                    <ul style="margin:8px 0 0; padding-left:18px; font-size:12.5px; line-height:1.7; color:var(--ink);">
                        <li>Ubah harga layanan.</li>
                        <li>Pasang diskon layanan.</li>
                        <li>Tambah jenis servis baru.</li>
                        <li>Restock sparepart.</li>
                        <li>Atur persentase gaji karyawan.</li>
                    </ul>
                </div>
                <p style="margin:0; font-size:12.5px; line-height:1.6; color:var(--ink);">ZoruAi tidak langsung mengubah data tanpa form. Owner tetap menekan konfirmasi sebelum perubahan disimpan.</p>
            </div>
            <!-- Halaman 4 -->
            <div class="zoru-book-page" data-book-page style="display:none; flex-direction:column; gap:16px;">
                <h2 style="margin:0; font-size:20px; font-weight:900; color:var(--ink);">ZeroPay & Transaksi Owner</h2>
                <p style="margin:0; font-size:13px; line-height:1.6; color:var(--ink);">ZoruAi bisa membantu Owner mengelola saldo dan memahami arus dana bengkel.</p>
                <div style="background:var(--bg-soft); border:1px solid var(--line); border-radius:10px; padding:12px; margin:4px 0; font-size:12px;">
                    <strong style="font-size:10px; color:var(--muted); text-transform:uppercase;">Bisa diminta:</strong>
                    <ul style="margin:8px 0 12px; padding-left:18px; font-size:12.5px; line-height:1.7; color:var(--ink);">
                        <li>Penjelasan rincian ZeroPay.</li>
                        <li>Panduan top-up saldo.</li>
                        <li>Panduan penarikan dana.</li>
                        <li>Penjelasan proses restock sparepart.</li>
                    </ul>
                    <strong style="font-size:10px; color:var(--muted); text-transform:uppercase;">Catatan:</strong>
                    <p style="margin:6px 0 0; font-size:12.5px; line-height:1.6; color:var(--ink);">Transaksi penting tetap melalui konfirmasi dan validasi saldo sebelum diproses.</p>
                </div>
            </div>
            <!-- Halaman 5 -->
            <div class="zoru-book-page" data-book-page style="display:none; flex-direction:column; gap:16px;">
                <h2 style="margin:0; font-size:20px; font-weight:900; color:var(--ink);">Batas Aman ZoruAi Owner</h2>
                <p style="margin:0; font-size:13px; line-height:1.6; color:var(--ink);">ZoruAi membantu mempercepat pekerjaan, tetapi tidak mengambil alih kendali Owner.</p>
                <ul style="margin:0; padding-left:20px; font-size:13px; line-height:1.7; color:var(--ink);">
                    <li>Perubahan harga, promo, restock, dan persentase gaji harus melalui form konfirmasi.</li>
                    <li>Fitur sensitif Owner tidak dibuka untuk role lain.</li>
                    <li>ZoruAi menjawab dari data aplikasi Milky Garage dan tidak memakai layanan eksternal.</li>
                    <li>Untuk aksi besar, gunakan Master Data atau Keuangan Harian sebagai tempat pengecekan akhir.</li>
                </ul>
            </div>
        @elseif(auth()->user()->isRole('cashier'))
            <!-- GUIDE FOR CASHIER -->
            <!-- Halaman 1 -->
            <div class="zoru-book-page is-active" data-book-page style="display:flex; flex-direction:column; gap:16px;">
                <h2 style="margin:0; font-size:22px; font-weight:900; color:var(--ink);">Panduan Kasir ZoruAi</h2>
                <p style="margin:0; font-size:13px; line-height:1.6; color:var(--ink);">ZoruAi membantu Kasir memahami pembayaran, laporan gaji, ZeroPay, dan alur transaksi pelanggan.</p>
                <h3 style="margin:8px 0 0; font-size:14px; font-weight:800; color:var(--ink);">Yang bisa dilakukan ZoruAi:</h3>
                <ul style="margin:0; padding-left:20px; font-size:13px; line-height:1.7; color:var(--ink);">
                    <li><strong>Pembayaran:</strong> menjelaskan status tagihan, metode bayar, dan alur nota pelanggan.</li>
                    <li><strong>Gaji Saya:</strong> membuka laporan gaji kasir berdasarkan bulan dan tahun.</li>
                    <li><strong>ZeroPay:</strong> membuka form penarikan dana dan menjelaskan rincian saldo.</li>
                </ul>
            </div>
            <!-- Halaman 2 -->
            <div class="zoru-book-page" data-book-page style="display:none; flex-direction:column; gap:16px;">
                <h2 style="margin:0; font-size:20px; font-weight:900; color:var(--ink);">Bantuan Pembayaran</h2>
                <p style="margin:0; font-size:13px; line-height:1.6; color:var(--ink);">Kasir bisa bertanya ke ZoruAi saat ingin memahami proses tagihan dan pelunasan.</p>
                <ol style="margin:0; padding-left:20px; font-size:13px; line-height:1.7; color:var(--ink);">
                    <li>Tanyakan cara memproses pembayaran tunai, transfer, QRIS, atau ZeroPay.</li>
                    <li>Minta penjelasan status pending, menunggu kasir, atau lunas.</li>
                    <li>Minta panduan pengiriman nota digital ke pelanggan.</li>
                    <li>Gunakan jawaban ZoruAi sebagai panduan, lalu tetap konfirmasi melalui halaman pembayaran.</li>
                </ol>
            </div>
            <!-- Halaman 3 -->
            <div class="zoru-book-page" data-book-page style="display:none; flex-direction:column; gap:16px;">
                <h2 style="margin:0; font-size:20px; font-weight:900; color:var(--ink);">Gaji & ZeroPay Kasir</h2>
                <p style="margin:0; font-size:13px; line-height:1.6; color:var(--ink);">ZoruAi dapat membantu Kasir melihat hak gaji dan mengelola saldo.</p>
                <ul style="margin:0; padding-left:20px; font-size:13px; line-height:1.7; color:var(--ink);">
                    <li>Klik pintasan <strong>Gaji Saya</strong> untuk memahami laporan gaji kasir.</li>
                    <li>Klik pintasan <strong>Penarikan Dana</strong> untuk memahami alur withdraw.</li>
                    <li>Tanyakan <strong>Rincian ZeroPay</strong> untuk memahami saldo dan alur transaksi.</li>
                    <li>Penarikan tetap membutuhkan PIN dan validasi saldo.</li>
                </ul>
            </div>
            <!-- Halaman 4 -->
            <div class="zoru-book-page" data-book-page style="display:none; flex-direction:column; gap:16px;">
                <h2 style="margin:0; font-size:20px; font-weight:900; color:var(--ink);">Batas Akses Kasir</h2>
                <p style="margin:0; font-size:13px; line-height:1.6; color:var(--ink);">ZoruAi menyesuaikan jawaban dengan role Kasir.</p>
                <ul style="margin:0; padding-left:20px; font-size:13px; line-height:1.7; color:var(--ink);">
                    <li>Kasir tidak dapat mengubah harga layanan, promo, stok, atau persentase gaji.</li>
                    <li>ZoruAi tidak membuka data Owner yang tidak diperlukan kasir.</li>
                    <li>Untuk transaksi nyata di sistem, tetap gunakan menu pembayaran resmi.</li>
                    <li>Jika pertanyaan terlalu umum, ZoruAi akan memberi pilihan kata kunci yang lebih tepat.</li>
                </ul>
            </div>
        @elseif(auth()->user()->isRole('mechanic'))
            <!-- GUIDE FOR MECHANIC -->
            <!-- Halaman 1 -->
            <div class="zoru-book-page is-active" data-book-page style="display:flex; flex-direction:column; gap:16px;">
                <h2 style="margin:0; font-size:22px; font-weight:900; color:var(--ink);">Panduan Mekanik ZoruAi</h2>
                <p style="margin:0; font-size:13px; line-height:1.6; color:var(--ink);">ZoruAi membantu Mekanik mengambil booking, memahami alur pengerjaan, bertanya diagnosa ringan, dan melihat gaji.</p>
                <h3 style="margin:8px 0 0; font-size:14px; font-weight:800; color:var(--ink);">Yang bisa dilakukan ZoruAi:</h3>
                <ul style="margin:0; padding-left:20px; font-size:13px; line-height:1.7; color:var(--ink);">
                    <li><strong>ACC Booking:</strong> membuka daftar booking hari ini yang masih bisa diambil.</li>
                    <li><strong>Panduan Servis:</strong> menjelaskan mulai kerja, selesai servis, dan pencatatan sparepart.</li>
                    <li><strong>Gaji & ZeroPay:</strong> membuka laporan gaji mekanik dan form penarikan dana.</li>
                </ul>
            </div>
            <!-- Halaman 2 -->
            <div class="zoru-book-page" data-book-page style="display:none; flex-direction:column; gap:16px;">
                <h2 style="margin:0; font-size:20px; font-weight:900; color:var(--ink);">ACC Booking Lewat ZoruAi</h2>
                <p style="margin:0; font-size:13px; line-height:1.6; color:var(--ink);">Pintasan <strong>ACC Booking</strong> membantu mekanik melihat pekerjaan yang bisa diambil.</p>
                <ol style="margin:0; padding-left:20px; font-size:13px; line-height:1.7; color:var(--ink);">
                    <li>Klik pintasan <strong>ACC Booking</strong> atau ketik acc booking.</li>
                    <li>ZoruAi menampilkan booking yang statusnya masih tersedia.</li>
                    <li>Pilih <strong>Terima Booking</strong> untuk mengambil pekerjaan.</li>
                    <li>Setelah diterima, lanjutkan proses kerja dari halaman booking/service order.</li>
                </ol>
            </div>
            <!-- Halaman 3 -->
            <div class="zoru-book-page" data-book-page style="display:none; flex-direction:column; gap:16px;">
                <h2 style="margin:0; font-size:20px; font-weight:900; color:var(--ink);">Bantuan Diagnosa Ringan</h2>
                <p style="margin:0; font-size:13px; line-height:1.6; color:var(--ink);">Mekanik dapat bertanya panduan troubleshooting ringan atau alur pencatatan servis.</p>
                <div style="background:var(--bg-soft); border:1px solid var(--line); border-radius:10px; padding:12px; margin:4px 0;">
                    <strong style="font-size:11px; color:var(--muted); text-transform:uppercase;">Contoh pertanyaan:</strong>
                    <ul style="margin:8px 0 0; padding-left:18px; font-size:12.5px; line-height:1.7; color:var(--ink);">
                        <li>Tanda oli harus diganti.</li>
                        <li>Busi sering basah.</li>
                        <li>Cara mencatat sparepart pada servis.</li>
                    </ul>
                </div>
            </div>
            <!-- Halaman 4 -->
            <div class="zoru-book-page" data-book-page style="display:none; flex-direction:column; gap:16px;">
                <h2 style="margin:0; font-size:20px; font-weight:900; color:var(--ink);">Gaji & Batas Akses Mekanik</h2>
                <p style="margin:0; font-size:13px; line-height:1.6; color:var(--ink);">ZoruAi hanya membuka fitur yang sesuai dengan role Mekanik.</p>
                <ul style="margin:0; padding-left:20px; font-size:13px; line-height:1.7; color:var(--ink);">
                    <li>Ketik atau klik <strong>Gaji Saya</strong> untuk memahami laporan gaji.</li>
                    <li>Ketik atau klik <strong>Penarikan Dana</strong> untuk memahami alur withdraw.</li>
                    <li>Mekanik tidak dapat mengubah harga, promo, stok, atau data owner.</li>
                    <li>Jawaban ZoruAi tetap mengikuti data aplikasi Milky Garage.</li>
                </ul>
            </div>
        @else
            <!-- GUIDE FOR CUSTOMER -->
            <!-- Halaman 1 -->
            <div class="zoru-book-page is-active" data-book-page style="display:flex; flex-direction:column; gap:16px;">
                <h2 style="margin:0; font-size:22px; font-weight:900; color:var(--ink);">Panduan Pelanggan ZoruAi</h2>
                <p style="margin:0; font-size:13px; line-height:1.6; color:var(--ink);">ZoruAi membantu pelanggan membuat booking, memahami pembayaran, mengelola ZeroPay, membaca riwayat servis, dan memahami gejala motor ringan.</p>
                <h3 style="margin:8px 0 0; font-size:14px; font-weight:800; color:var(--ink);">Yang bisa dilakukan ZoruAi:</h3>
                <ul style="margin:0; padding-left:20px; font-size:13px; line-height:1.7; color:var(--ink);">
                    <li><strong>Booking Service:</strong> membuka form booking dan memandu data kendaraan, layanan, tanggal, serta jam.</li>
                    <li><strong>Pembayaran:</strong> menjelaskan tagihan, metode bayar, dan status servis.</li>
                    <li><strong>ZeroPay:</strong> membuka form top-up dan menjelaskan rincian saldo.</li>
                    <li><strong>Troubleshooting Ringan:</strong> memberi panduan awal untuk gejala umum seperti oli, busi, rem, mesin susah nyala, asap, bunyi, atau getaran.</li>
                </ul>
            </div>
            <!-- Halaman 2 -->
            <div class="zoru-book-page" data-book-page style="display:none; flex-direction:column; gap:16px;">
                <h2 style="margin:0; font-size:20px; font-weight:900; color:var(--ink);">Booking Lewat ZoruAi</h2>
                <p style="margin:0; font-size:13px; line-height:1.6; color:var(--ink);">Pelanggan bisa meminta ZoruAi menyiapkan form booking tanpa mencari menu manual.</p>
                <ol style="margin:0; padding-left:20px; font-size:13px; line-height:1.7; color:var(--ink);">
                    <li>Klik pintasan <strong>Booking Service Baru</strong> atau ketik booking.</li>
                    <li>Isi kendaraan, jenis servis, tanggal, jam, dan keluhan.</li>
                    <li>ZoruAi membantu membuka form, sementara validasi jadwal tetap diproses sistem.</li>
                    <li>Status booking dapat dipantau dari menu Booking Service.</li>
                </ol>
            </div>
            <!-- Halaman 3 -->
            <div class="zoru-book-page" data-book-page style="display:none; flex-direction:column; gap:16px;">
                <h2 style="margin:0; font-size:20px; font-weight:900; color:var(--ink);">ZeroPay & Pembayaran</h2>
                <p style="margin:0; font-size:13px; line-height:1.6; color:var(--ink);">ZoruAi bisa membantu pelanggan memahami saldo dan proses pelunasan tagihan.</p>
                <ul style="margin:0; padding-left:20px; font-size:13px; line-height:1.7; color:var(--ink);">
                    <li>Ketik atau klik <strong>Top-up Saldo ZeroPay</strong> untuk memahami pengisian saldo.</li>
                    <li>Tanyakan <strong>Rincian ZeroPay</strong> untuk penjelasan saldo dan riwayat transaksi.</li>
                    <li>ZoruAi dapat menjelaskan perbedaan pembayaran ZeroPay, tunai, transfer, dan QRIS.</li>
                    <li>Pelunasan tetap dilakukan dari halaman Pembayaran.</li>
                </ul>
            </div>
            <!-- Halaman 4 -->
            <div class="zoru-book-page" data-book-page style="display:none; flex-direction:column; gap:16px;">
                <h2 style="margin:0; font-size:20px; font-weight:900; color:var(--ink);">Troubleshooting Ringan</h2>
                <p style="margin:0; font-size:13px; line-height:1.6; color:var(--ink);">ZoruAi dapat membantu pelanggan memahami gejala awal sebelum membuat booking servis.</p>
                <ul style="margin:0; padding-left:20px; font-size:13px; line-height:1.7; color:var(--ink);">
                    <li>Oli: tanda oli perlu dicek atau diganti.</li>
                    <li>Busi dan mesin susah nyala: kemungkinan awal yang perlu diperiksa.</li>
                    <li>Rem: gejala kampas rem menipis atau pengereman tidak normal.</li>
                    <li>Asap, suara, atau getaran: catatan keluhan yang perlu ditulis saat booking.</li>
                    <li>Jawaban ZoruAi hanya panduan awal. Pemeriksaan tetap dilakukan melalui proses servis.</li>
                </ul>
            </div>
            <!-- Halaman 5 -->
            <div class="zoru-book-page" data-book-page style="display:none; flex-direction:column; gap:16px;">
                <h2 style="margin:0; font-size:20px; font-weight:900; color:var(--ink);">Batas Akses Pelanggan</h2>
                <p style="margin:0; font-size:13px; line-height:1.6; color:var(--ink);">ZoruAi pelanggan hanya membantu kebutuhan pelanggan dan informasi umum bengkel.</p>
                <ul style="margin:0; padding-left:20px; font-size:13px; line-height:1.7; color:var(--ink);">
                    <li>Pelanggan tidak bisa mengakses master data, laporan owner, restock, atau gaji karyawan.</li>
                    <li>ZoruAi dapat menjawab layanan, promo aktif, cara booking, pembayaran, troubleshooting ringan, dan riwayat servis.</li>
                    <li>Jika pertanyaan menyangkut data internal, ZoruAi akan membatasi jawaban sesuai role.</li>
                    <li>Semua bantuan tetap mengikuti data aplikasi Milky Garage.</li>
                </ul>
            </div>
        @endif
        
        <!-- NAVIGATION BAR -->
        <div style="position:absolute; bottom:24px; left:32px; right:32px; display:flex; align-items:center; justify-content:space-between; padding-top:16px; border-top:1px solid var(--line);">
            <button type="button" class="zoru-book-nav" id="guidePrevPage" disabled> Sebelumnya</button>
            <div class="zoru-book-page-indicator" id="guidePageIndicator">Halaman 1</div>
            <button type="button" class="zoru-book-nav" id="guideNextPage">Berikutnya </button>
        </div>
    </div>
</div>

<div id="hiddenGemComingSoonOverlay" style="position:fixed; inset:0; z-index:9999; display:none; place-items:center; padding:24px; background:rgba(0,0,0,0.72); backdrop-filter:blur(10px);">
    <div role="dialog" aria-modal="true" aria-labelledby="hiddenGemComingSoonTitle" style="width:min(440px, 100%); border:1px solid var(--line); border-radius:16px; background:var(--panel-solid); box-shadow:0 22px 70px rgba(0,0,0,0.42); padding:24px; text-align:center;">
        <div style="width:58px; height:58px; margin:0 auto 16px; display:grid; place-items:center; border-radius:16px; border:1px solid rgba(245,158,11,0.35); background:rgba(245,158,11,0.1); color:#fbbf24;">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M12 3 20 9l-8 12L4 9z"></path>
                <path d="M4 9h16"></path>
                <path d="M9 9l3 12 3-12"></path>
            </svg>
        </div>
        <h2 id="hiddenGemComingSoonTitle" style="margin:0 0 8px; font-size:28px; font-weight:900; color:var(--ink);">Coming Soon</h2>
        <p style="margin:0 0 20px; color:var(--muted); font-size:14px; line-height:1.6;">Hidden Gem ZoruAi sedang disiapkan ulang untuk tetap lokal dan aman.</p>
        <button type="button" id="hiddenGemComingSoonClose" class="zoru-action-btn" style="margin:0 auto; min-width:120px; justify-content:center;">Tutup</button>
    </div>
</div>

@endsection

@section('scripts')
<script>
    const zoruSvgIcons = {
        TR: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 18V6"/><path d="M4 18h16"/><path d="m7 15 4-4 3 3 5-7"/></svg>',
        RS: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16v12H4z"/><path d="M8 7V5h8v2"/><path d="M9 13h6"/><path d="M12 10v6"/></svg>',
        SV: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 7 7 14"/><path d="m5 16 3 3 9-9-3-3z"/><path d="M16 5l3 3"/></svg>',
        DS: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 12 12 20 4 12V4h8z"/><path d="M8 8h.01"/><path d="M10 16l6-6"/></svg>',
        HG: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 17h16"/><path d="M7 13h4"/><path d="M13 9h4"/><path d="M8 7v10"/><path d="M16 7v10"/></svg>',
        GJ: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16v10H4z"/><path d="M12 10v4"/><path d="M9 12h6"/></svg>',
        UP: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 19V5"/><path d="m6 11 6-6 6 6"/><path d="M5 19h14"/></svg>',
        WD: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5v14"/><path d="m18 13-6 6-6-6"/><path d="M5 5h14"/></svg>',
        ZP: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16v10H4z"/><path d="M16 11h3"/><path d="M8 11h4"/></svg>',
        BK: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 5h14v15H5z"/><path d="M8 3v4"/><path d="M16 3v4"/><path d="M8 11h8"/><path d="M8 15h5"/></svg>',
        OK: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m5 12 4 4L19 6"/></svg>',
        HGEM: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3 20 9l-8 12L4 9z"/><path d="M4 9h16"/><path d="M9 9l3 12 3-12"/></svg>'
    };

    function hydrateZoruShortcutIcons() {
        document.querySelectorAll('.zoru-shortcut-btn .icon, .compliance-badge .badge-icon').forEach((el) => {
            const key = (el.dataset.icon || el.textContent || '').trim().toUpperCase();
            if (zoruSvgIcons[key]) {
                el.innerHTML = zoruSvgIcons[key];
                el.dataset.icon = key;
            }
        });
    }

    function updateSidebarZeroPayBalance(balance) {
        const sidebarEl = document.getElementById('sidebarZeroPayBalance');
        if (sidebarEl) sidebarEl.textContent = formatRupiah(balance);
    }
    function switchAnalyticsTab(tab) {
        const chatBox = document.getElementById('chatBox');
        const formBar = document.querySelector('.zoru-form-bar');
        const trendsBox = document.getElementById('trendsBox');
        const tabChatBtn = document.getElementById('tabChatBtn');
        const tabTrendsBtn = document.getElementById('tabTrendsBtn');

        if (!chatBox || !formBar || !trendsBox) return;

        if (tab === 'chat') {
            chatBox.style.display = 'flex';
            formBar.style.display = 'block';
            trendsBox.style.display = 'none';
            
            if (tabChatBtn) tabChatBtn.classList.add('btn-executive', 'active');
            if (tabTrendsBtn) tabTrendsBtn.classList.remove('btn-executive', 'active');
        } else {
            chatBox.style.display = 'none';
            formBar.style.display = 'none';
            trendsBox.style.display = 'flex';
            
            if (tabTrendsBtn) tabTrendsBtn.classList.add('btn-executive', 'active');
            if (tabChatBtn) tabChatBtn.classList.remove('btn-executive', 'active');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        hydrateZoruShortcutIcons();
        const analyticsParams = new URLSearchParams(window.location.search);
        const navigationEntry = performance.getEntriesByType('navigation')[0];
        const isBrowserReload = navigationEntry
            ? navigationEntry.type === 'reload'
            : performance.navigation && performance.navigation.type === 1;
        if (!isBrowserReload && analyticsParams.get('tab') === 'trends') {
            switchAnalyticsTab('trends');
        }
    });

    const servicesList = @json($allServices);
    const sparePartsList = @json($allSpareParts);
    const userVehiclesList = @json($userVehicles);

    let guidePageIndex = 0;

    function escapeJsString(str) {
        return str.replace(/'/g, "\\'").replace(/"/g, '\\"');
    }

    function escapeHtmlString(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function formatZoruCommandLabel(value) {
        const specialLabels = {
            zeropay: 'ZeroPay',
            'rincian zeropay': 'Rincian ZeroPay',
            topup: 'Top-up',
            'top up': 'Top-up',
            'top-up': 'Top-up',
            withdraw: 'Withdraw',
            restock: 'Restock',
            booking: 'Booking',
            pembayaran: 'Pembayaran',
            gaji: 'Gaji',
            'gaji saya': 'Gaji Saya',
            'acc booking': 'ACC Booking',
            'ubah harga': 'Ubah Harga',
            diskon: 'Diskon',
            'analisis trend': 'Analisis Trend',
            'tambah servis': 'Tambah Servis',
            'tarik dana': 'Tarik Dana',
            developer: 'Developer',
            pencipta: 'Pencipta',
            pembuat: 'Pembuat',
            'pembuat sistem': 'Pembuat Sistem'
        };

        const normalized = String(value)
            .replace(/^\/+/, '')
            .replace(/[_-]+/g, ' ')
            .replace(/\s+/g, ' ')
            .trim()
            .toLowerCase();

        if (specialLabels[normalized]) {
            return specialLabels[normalized];
        }

        return normalized.replace(/\b\w/g, (char) => char.toUpperCase());
    }

    function renderZoruCommandButton(prompt, labelSource) {
        const label = formatZoruCommandLabel(labelSource || prompt);
        return `<button type="button" class="zoru-clickable-cmd" onclick="submitZoruPrompt('${escapeJsString(prompt)}')">${escapeHtmlString(label)}</button>`;
    }

    function makeCommandsClickable(element) {
        if (!element || element.dataset.commandsClickable) return;
        element.dataset.commandsClickable = "true";
        let html = element.innerHTML;
        
        // 1. Match slash commands like /restock, /gaji, /topup, /withdraw, /booking, /pembayaran, /zeropay
        const slashCommands = ['/analisis-trend', '/restock', '/tambah-servis', '/diskon', '/ubah-harga', '/gaji', '/topup', '/withdraw', '/pembayaran', '/zeropay', '/booking', '/troubleshoot', '/gaji-saya', '/acc-booking'];
        slashCommands.forEach(cmd => {
            const regex = new RegExp(cmd + '\\b', 'gi');
            html = html.replace(regex, (match) => {
                let prompt = match;
                const skill = allSkills.find(s => s.cmd.toLowerCase() === match.toLowerCase());
                if (skill) {
                    prompt = skill.prompt;
                } else {
                    prompt = prompt.replace('/', '');
                }
                return renderZoruCommandButton(prompt, skill ? skill.label : match);
            });
        });
        
        // 2. Match text inside quotes that looks like a command.
        const commandKeywords = [
            'restock', 'ubah harga', 'diskon', 'gaji', 'top-up', 'topup', 'withdraw', 'tarik dana',
            'pembayaran', 'rincian zeropay', 'booking', 'bagaimana cara', 'analisa', 'analisis', 'tren', 'gaji saya', 'acc booking',
            'pembuat', 'pembuat sistem', 'developer', 'pencipta'
        ];
        
        // Matches anything inside double quotes: "..."
        html = html.replace(/"([^"\n]{3,100})"/g, (match, p1) => {
            const lower = p1.toLowerCase();
            const isCommand = commandKeywords.some(keyword => lower.includes(keyword)) || allSkills.some(s => s.prompt.toLowerCase() === lower);
            if (isCommand) {
                return renderZoruCommandButton(p1, p1);
            }
            return match;
        });
        
        element.innerHTML = html;
    }

    // MutationObserver to automatically make all new bubbles clickable
    document.addEventListener('DOMContentLoaded', () => {
        hydrateZoruShortcutIcons();
        const chatBox = document.getElementById('chatBox');
        if (chatBox) {
            // Parse existing welcome message
            const welcomeBubble = document.getElementById('zoruWelcomeBubble');
            if (welcomeBubble) {
                makeCommandsClickable(welcomeBubble);
            }
            
            // Observe chatBox for new bubbles
            const observer = new MutationObserver((mutations) => {
                mutations.forEach((mutation) => {
                    mutation.addedNodes.forEach((node) => {
                        if (node.nodeType === Node.ELEMENT_NODE) {
                            if (node.classList.contains('zoru-bubble-ai')) {
                                makeCommandsClickable(node);
                            }
                            node.querySelectorAll('.zoru-bubble-ai').forEach(child => {
                                makeCommandsClickable(child);
                            });
                        }
                    });
                });
            });
            observer.observe(chatBox, { childList: true, subtree: true });
        }
    });
    
    function scrollToBottom() {
        const chatBox = document.getElementById('chatBox');
        chatBox.scrollTop = chatBox.scrollHeight;
    }
    
    function setPromptInput(text) {
        document.getElementById('zoruPrompt').value = text;
        document.getElementById('zoruPrompt').focus();
    }
    
    // Rotating Placeholder logic
    const zoruPromptInput = document.getElementById('zoruPrompt');
    if (zoruPromptInput) {
        const placeholders = [
            "apa yang bisa saya bantu hari ini?",
            "ketik / untuk skill"
        ];
        let placeholderIndex = 0;
        if (window.__zoruPlaceholderTimer) clearInterval(window.__zoruPlaceholderTimer);
        window.__zoruPlaceholderTimer = setInterval(() => {
            if (document.hidden) return;
            placeholderIndex = (placeholderIndex + 1) % placeholders.length;
            zoruPromptInput.placeholder = placeholders[placeholderIndex];
        }, 4500);
    }
    
    // Autocomplete '/' Dropdown Skill logic
    const allSkills = [];
    @if(auth()->user()->isRole('owner'))
        allSkills.push(
            { cmd: '/analisis-trend', label: 'Analisis Trend & Rekomendasi', prompt: 'analisis trend' },
            { cmd: '/restock', label: 'Restock sparepart', prompt: 'restock' },
            { cmd: '/tambah-servis', label: 'Tambah jenis servis', prompt: 'tambah servis' },
            { cmd: '/diskon', label: 'Pasang diskon servis', prompt: 'diskon' },
            { cmd: '/ubah-harga', label: 'Ubah harga servis', prompt: 'ubah harga' },
            { cmd: '/gaji', label: 'Atur gaji karyawan', prompt: 'gaji' },
            { cmd: '/topup', label: 'Top-up saldo ZeroPay', prompt: 'topup' },
            { cmd: '/withdraw', label: 'Penarikan dana', prompt: 'withdraw' }
        );
    @elseif(auth()->user()->isRole('cashier'))
        allSkills.push(
            { cmd: '/topup', label: 'Top-up saldo ZeroPay', prompt: 'topup' },
            { cmd: '/withdraw', label: 'Tarik dana ZeroPay', prompt: 'withdraw' },
            { cmd: '/gaji-saya', label: 'Laporan Gaji', prompt: 'gaji saya' }
        );
    @elseif(auth()->user()->isRole('mechanic'))
        allSkills.push(
            { cmd: '/topup', label: 'Top-up saldo ZeroPay', prompt: 'topup' },
            { cmd: '/withdraw', label: 'Tarik dana ZeroPay', prompt: 'withdraw' },
            { cmd: '/gaji-saya', label: 'Laporan Gaji', prompt: 'gaji saya' },
            { cmd: '/acc-booking', label: 'ACC Booking', prompt: 'acc booking' }
        );
    @else
        allSkills.push(
            { cmd: '/booking', label: 'Booking Service Baru', prompt: 'booking' },
            { cmd: '/topup', label: 'Top-up ZeroPay', prompt: 'topup' },
            { cmd: '/withdraw', label: 'Tarik dana ZeroPay', prompt: 'withdraw' },
            { cmd: '/zeropay', label: 'Rincian ZeroPay', prompt: 'rincian zeropay' }
        );
    @endif

    const dropdown = document.getElementById('zoruSkillsDropdown');
    let activeSkillIndex = -1;
    let filteredSkills = [];

    if (zoruPromptInput && dropdown) {
        zoruPromptInput.addEventListener('input', function() {
            const val = this.value;
            if (val.startsWith('/')) {
                const query = val.slice(1).toLowerCase();
                filteredSkills = allSkills.filter(s => s.cmd.toLowerCase().includes('/' + query) || s.label.toLowerCase().includes(query));
                renderSkillsDropdown();
            } else {
                dropdown.style.display = 'none';
                activeSkillIndex = -1;
            }
        });

        zoruPromptInput.addEventListener('keydown', function(e) {
            if (dropdown.style.display !== 'none' && filteredSkills.length > 0) {
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    activeSkillIndex = (activeSkillIndex + 1) % filteredSkills.length;
                    updateActiveSkillItem();
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    activeSkillIndex = (activeSkillIndex - 1 + filteredSkills.length) % filteredSkills.length;
                    updateActiveSkillItem();
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    if (activeSkillIndex >= 0 && activeSkillIndex < filteredSkills.length) {
                        selectSkillItem(filteredSkills[activeSkillIndex]);
                    }
                } else if (e.key === 'Escape') {
                    e.preventDefault();
                    dropdown.style.display = 'none';
                    activeSkillIndex = -1;
                }
            }
        });
    }

    function renderSkillsDropdown() {
        if (filteredSkills.length === 0) {
            dropdown.style.display = 'none';
            return;
        }
        dropdown.innerHTML = '';
        filteredSkills.forEach((skill, idx) => {
            const item = document.createElement('div');
            item.className = 'zoru-skill-item';
            if (idx === activeSkillIndex) {
                item.classList.add('is-active');
            }
            item.innerHTML = `
                <span class="skill-cmd">${escapeHtml(skill.cmd)}</span>
                <span class="skill-desc">${escapeHtml(skill.label)}</span>
            `;
            item.addEventListener('click', () => {
                selectSkillItem(skill);
            });
            dropdown.appendChild(item);
        });
        dropdown.style.display = 'block';
    }

    function updateActiveSkillItem() {
        const items = dropdown.querySelectorAll('.zoru-skill-item');
        items.forEach((item, idx) => {
            if (idx === activeSkillIndex) {
                item.classList.add('is-active');
                item.scrollIntoView({ block: 'nearest' });
            } else {
                item.classList.remove('is-active');
            }
        });
    }

    function selectSkillItem(skill) {
        zoruPromptInput.value = '';
        dropdown.style.display = 'none';
        activeSkillIndex = -1;
        submitZoruPrompt(skill.prompt);
    }

    // Hide dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (zoruPromptInput && dropdown && e.target !== zoruPromptInput && e.target !== dropdown && !dropdown.contains(e.target)) {
            dropdown.style.display = 'none';
            activeSkillIndex = -1;
        }
    });
    
    // Guide Book Navigation
    const guideModal = document.getElementById('zoruGuideModal');
    
    function openGuideModal() {
        guideModal.classList.add('is-open');
        guidePageIndex = 0;
        renderGuidePage();
    }
    
    function closeGuideModal() {
        guideModal.classList.remove('is-open');
    }
    
    function renderGuidePage() {
        const pages = document.querySelectorAll('[data-book-page]');
        pages.forEach((page, idx) => {
            page.style.display = (idx === guidePageIndex) ? 'flex' : 'none';
            if (idx === guidePageIndex) page.classList.add('is-active');
        });
        
        document.getElementById('guidePrevPage').disabled = (guidePageIndex === 0);
        document.getElementById('guideNextPage').disabled = (guidePageIndex === pages.length - 1);
        document.getElementById('guidePageIndicator').textContent = `Halaman ${guidePageIndex + 1} / ${pages.length}`;
    }
    
    document.getElementById('guideBookButton').addEventListener('click', openGuideModal);
    document.getElementById('closeGuideModal').addEventListener('click', closeGuideModal);
    
    document.getElementById('guidePrevPage').addEventListener('click', () => {
        if (guidePageIndex > 0) {
            guidePageIndex--;
            renderGuidePage();
        }
    });
    
    document.getElementById('guideNextPage').addEventListener('click', () => {
        const pages = document.querySelectorAll('[data-book-page]');
        if (guidePageIndex < pages.length - 1) {
            guidePageIndex++;
            renderGuidePage();
        }
    });
    
    guideModal.addEventListener('click', function(e) {
        if (e.target === this) closeGuideModal();
    });
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeGuideModal();
    });
    
    // submitZoruPrompt function
    function submitZoruPrompt(promptText) {
        const chatBox = document.getElementById('chatBox');
        
        const cleaned = promptText.trim().toLowerCase();
        
        if (cleaned === 'booking' || cleaned === '/booking' || cleaned === 'booking service baru') {
            createBookingFormCard();
            return;
        }
        
        if (cleaned === 'topup' || cleaned === '/topup' || cleaned === 'top-up' || cleaned === 'top-up zeropay') {
            createTopupFormCard();
            return;
        }
        
        if (cleaned === 'withdraw' || cleaned === '/withdraw' || cleaned === 'tarik dana' || cleaned === 'tarik dana zeropay') {
            createWithdrawFormCard();
            return;
        }
        
        if (cleaned === 'gaji saya' || cleaned === '/gaji-saya' || cleaned === 'laporan gaji' || cleaned === 'rincian gaji') {
            createSalaryReportCard();
            return;
        }

        if (cleaned === 'analisis trend' || cleaned === '/analisis-trend' || cleaned === 'analisis tren' || cleaned === 'trend') {
            const userDiv = document.createElement('div');
            userDiv.className = "zoru-bubble zoru-bubble-user";
            userDiv.textContent = promptText;
            chatBox.appendChild(userDiv);
            createTrendPeriodPickerCard();
            scrollToBottom();
            return;
        }
        
        if (cleaned === 'acc booking' || cleaned === '/acc-booking' || cleaned === 'terima booking') {
            fetchAndOpenAccBookingForm();
            return;
        }
        
        // 1. Append User Message Bubble
        const userDiv = document.createElement('div');
        userDiv.className = "zoru-bubble zoru-bubble-user";
        userDiv.textContent = promptText;
        chatBox.appendChild(userDiv);
        scrollToBottom();
        
        // 2. Fetch from backend
        fetch("{{ route('reports.analytics.zoru') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ prompt: promptText })
        })
        .then(res => res.json())
        .then(data => {
            // Update sidebar balance dynamically
            if (data.balance !== undefined) {
                updateSidebarZeroPayBalance(data.balance);
            }

            let zoruDiv = null;
            const replyText = String(data.reply || '').trim();
            if (replyText !== '') {
                zoruDiv = document.createElement('div');
                zoruDiv.className = "zoru-bubble zoru-bubble-ai";
                zoruDiv.innerHTML = data.reply;
                makeCommandsClickable(zoruDiv);
                chatBox.appendChild(zoruDiv);
                scrollToBottom();
            }
            
            // Handle actions triggered by ZoruAi
            if (data.action) {
                handleZoruAction(data.action);
            } else if (zoruDiv) {
                // Only show feedback buttons ("Paham" & "Tidak Paham") for informational or systemic explanations
                // Avoid showing them for casual greetings or chit-chat responses
                const systemKeywords = [
                    'zeropay', 'saldo', 'tarik', 'withdraw', 'booking', 'servis', 'service', 
                    'gaji', 'harga', 'diskon', 'restock', 'katalog', 'sparepart', 
                    'spare part', 'pin', 'keuangan', 'laporan', 'aktivitas', 'riwayat',
                    'troubleshoot'
                ];
                const casualKeywords = [
                    'halo', 'hai', 'kabar', 'ngapain', 'siapa', 'keren', 'hebat', 'terima kasih', 'makasih', 'thanks', 'pencipta', 'developer', 'gem', 'paham', 'bantu'
                ];
                const lowercasePrompt = 'withdraw zeropay';
                        const lowercaseReply = data.reply.toLowerCase();
                        const isSystemic = systemKeywords.some(keyword => lowercasePrompt.includes(keyword) || lowercaseReply.includes(keyword));
                const isCasual = casualKeywords.some(keyword => lowercasePrompt.includes(keyword));
                
                if (isSystemic && !isCasual) {
                    const isTopupContext = promptText && (promptText.toLowerCase().includes('top-up') || promptText.toLowerCase().includes('topup'));
                    appendInformationalFeedback(zoruDiv, isTopupContext ? 'topup' : null);
                }
            }
        })
        .catch(err => {
            console.error('Error fetching Zoru:', err);
            const errDiv = document.createElement('div');
            errDiv.className = "zoru-bubble zoru-bubble-ai";
            errDiv.style.background = "var(--danger)";
            errDiv.style.color = "#fff";
            errDiv.style.borderColor = "var(--danger)";
            errDiv.textContent = 'Terjadi kendala saat memproses. Pastikan server Laragon Anda menyala.';
            chatBox.appendChild(errDiv);
            scrollToBottom();
        });
    }
    
    // Append dynamic Paham / Tidak Paham feedback buttons under informational AI responses
    function appendInformationalFeedback(parentBubble, context) {
        const feedbackRow = document.createElement('div');
        feedbackRow.className = 'zoru-feedback-row';
        feedbackRow.style.cssText = `
            display: flex;
            gap: 8px;
            margin-top: 12px;
            animation: zoruMessageIn 0.2s ease-out forwards;
            align-self: flex-start;
            justify-content: flex-start;
        `;
        
        feedbackRow.innerHTML = `
            <button type="button" class="zoru-action-btn btn-paham" style="display: inline-flex; align-items: center; justify-content: center; padding: 6px 14px; font-size: 12px; border-radius: 8px; background: rgba(16, 185, 129, 0.08); border: 1px solid rgba(16, 185, 129, 0.25); color: #10b981; font-weight:700; cursor:pointer; height:30px; min-width:80px; gap:5px; box-shadow:none; transition: all 0.15s ease; line-height:1; text-align:center; white-space:nowrap;">
                Paham 
            </button>
            <button type="button" class="zoru-action-btn btn-tidak-paham" style="display: inline-flex; align-items: center; justify-content: center; padding: 6px 14px; font-size: 12px; border-radius: 8px; background: rgba(239, 68, 68, 0.08); border: 1px solid rgba(239, 68, 68, 0.25); color: #ef4444; font-weight:700; cursor:pointer; height:30px; min-width:100px; gap:5px; box-shadow:none; transition: all 0.15s ease; line-height:1; text-align:center; white-space:nowrap;">
                Tidak Paham 
            </button>
        `;
        
        parentBubble.appendChild(feedbackRow);
        
        const pahamBtn = feedbackRow.querySelector('.btn-paham');
        const tidakBtn = feedbackRow.querySelector('.btn-tidak-paham');

        pahamBtn.addEventListener('mouseenter', () => {
            pahamBtn.style.background = 'rgba(16, 185, 129, 0.16)';
            pahamBtn.style.borderColor = '#10b981';
        });
        pahamBtn.addEventListener('mouseleave', () => {
            pahamBtn.style.background = 'rgba(16, 185, 129, 0.08)';
            pahamBtn.style.borderColor = 'rgba(16, 185, 129, 0.25)';
        });
        
        tidakBtn.addEventListener('mouseenter', () => {
            tidakBtn.style.background = 'rgba(239, 68, 68, 0.16)';
            tidakBtn.style.borderColor = '#ef4444';
        });
        tidakBtn.addEventListener('mouseleave', () => {
            tidakBtn.style.background = 'rgba(239, 68, 68, 0.08)';
            tidakBtn.style.borderColor = 'rgba(239, 68, 68, 0.25)';
        });
        
        pahamBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            pahamBtn.disabled = true;
            tidakBtn.disabled = true;
            pahamBtn.style.opacity = '0.5';
            tidakBtn.style.display = 'none';

            if (context === 'hidden_gem') {
                return;
            }
            
            // If top-up context, show barcode simulation
            if (context === 'topup') {
                showPaymentLoading();
                return;
            }
            
            const chatBox = document.getElementById('chatBox');
            const successDiv = document.createElement('div');
            successDiv.className = "zoru-bubble zoru-bubble-ai";
            successDiv.style.cssText = `
                align-self: flex-start;
                background: rgba(16, 185, 129, 0.08);
                border: 1px solid rgba(16, 185, 129, 0.2);
                color: #10b981;
                font-size: 13px;
                font-weight: 600;
                margin-top: 8px;
                padding: 8px 12px;
                border-radius: 12px;
                border-top-left-radius: 4px;
            `;
            successDiv.innerHTML = `<strong>ZoruAi:</strong> Luar biasa! Senang bisa membantu Anda memahami informasi ini. Percakapan selesai. Jika ada hal lain, saya siap membantu kembali! `;
            chatBox.appendChild(successDiv);
            scrollToBottom();
        });
        
        tidakBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            pahamBtn.disabled = true;
            tidakBtn.disabled = true;
            tidakBtn.style.opacity = '0.5';
            pahamBtn.style.display = 'none';

            if (context === 'hidden_gem') {
                openHiddenGemPortal();
                return;
            }
            
            submitZoruPrompt('Jelaskan lagi lebih detail');
        });
    }
    
    // Payment loading overlay for top-up flow
    function showPaymentLoading(callback) {
        const finish = () => {
            if (callback) {
                callback(null, null);
            } else {
                const chatBox = document.getElementById('chatBox');
                const successDiv = document.createElement('div');
                successDiv.className = "zoru-bubble zoru-bubble-ai";
                successDiv.style.cssText = `
                    align-self: flex-start;
                    background: rgba(16, 185, 129, 0.08);
                    border: 1px solid rgba(16, 185, 129, 0.2);
                    color: #10b981;
                    font-size: 13px;
                    font-weight: 600;
                    margin-top: 8px;
                    padding: 8px 12px;
                    border-radius: 12px;
                    border-top-left-radius: 4px;
                `;
                successDiv.innerHTML = `<strong>ZoruAi:</strong> Pembayaran top-up berhasil diverifikasi! Saldo ZeroPay Anda telah diperbarui. Terima kasih!`;
                chatBox.appendChild(successDiv);
                scrollToBottom();
            }
        };

        if (window.runZeroPaySimulation) {
            window.runZeroPaySimulation({
                title: 'Memproses Top-up',
                subtitle: 'Top-up ZeroPay sedang diproses melalui kanal pembayaran.',
                status: 'Menunggu respons pembayaran...',
                duration: 1400,
                onComplete: finish
            });
            return;
        }

        finish();
    }

    function showProcessingSimulation(title = 'Memproses', subtitle = 'Permintaan Anda sedang diproses.') {
        const overlayHandle = { remove() {} };

        if (window.runZeroPaySimulation) {
            window.runZeroPaySimulation({
                title,
                subtitle,
                status: 'Mengonfirmasi transaksi...',
                duration: 1400,
                onComplete: () => {}
            });
            return overlayHandle;
        }

        return overlayHandle;
    }
    // Withdrawal PIN Verification Modal Overlay (matches manual #pinVerifyModal exactly)
    function showPinVerificationModal(callback) {
        const overlay = document.createElement('div');
        overlay.style.cssText = `
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.55); display: flex; align-items: center;
            justify-content: center; z-index: 99999; animation: modalOverlayIn 0.25s ease-out;
        `;
        overlay.innerHTML = `
            <div class="panel modal-card" style="background:var(--panel-solid); max-width:380px; width:100%; padding:28px 24px; text-align:center; border:1px solid var(--line); box-shadow:var(--shadow);">
                <div style="width:56px; height:56px; margin:0 auto 16px; border-radius:14px; background:linear-gradient(135deg, var(--primary), var(--accent)); display:grid; place-items:center; color:#fff; font-size:18px; font-weight:900;">PIN</div>
                <h3 style="margin:0 0 8px; color:var(--ink);">Verifikasi PIN Anda</h3>
                <p class="muted" style="font-size:13px; margin-bottom:20px; color:var(--muted);">Masukkan PIN keamanan 4 digit untuk melanjutkan penarikan dana.</p>

                <input type="password" id="zoruPinInput" inputmode="numeric" maxlength="4" placeholder="****" autocomplete="off"
                       style="letter-spacing:8px; text-align:center; font-weight:bold; font-size:22px; max-width:160px; margin:0 auto 12px; display:block; background:var(--input-bg); color:var(--ink); border:1px solid var(--line); border-radius:8px; padding:10px;">

                <div id="zoruPinError" style="color:var(--danger); font-size:12px; margin-bottom:12px; min-height:18px;"></div>

                <div style="display:flex; gap:10px; justify-content:center; flex-wrap:wrap;">
                    <button type="button" class="button secondary" id="closeZoruPinBtn">Batal</button>
                    <button type="button" class="button" id="confirmZoruPinBtn">Verifikasi & Tarik</button>
                </div>
            </div>
        `;
        document.body.appendChild(overlay);

        const pinInput = document.getElementById('zoruPinInput');
        const confirmBtn = document.getElementById('confirmZoruPinBtn');
        const closeBtn = document.getElementById('closeZoruPinBtn');
        const errorDiv = document.getElementById('zoruPinError');

        setTimeout(() => pinInput.focus(), 100);

        closeBtn.addEventListener('click', () => {
            overlay.remove();
        });

        confirmBtn.addEventListener('click', () => {
            const pin = pinInput.value.trim();
            if (pin.length !== 4 || isNaN(pin)) {
                errorDiv.textContent = 'PIN harus 4 digit angka.';
                pinInput.focus();
                return;
            }
            if (callback) {
                callback(pin, overlay, errorDiv, confirmBtn, closeBtn);
            } else {
                overlay.remove();
            }
        });

        pinInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                confirmBtn.click();
            }
        });
    }
    
    // Zoru Form Submit
    document.getElementById('zoruForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const input = document.getElementById('zoruPrompt');
        const prompt = input.value.trim();
        if (!prompt) return;
        
        input.value = '';
        submitZoruPrompt(prompt);
    });
    
    // Executive Owner Summary Handler
    const executiveBtn = document.getElementById('executiveModeBtn');
    if (executiveBtn) {
        executiveBtn.addEventListener('click', function() {
            const chatBox = document.getElementById('chatBox');
            const execDiv = document.createElement('div');
            execDiv.className = "zoru-bubble zoru-bubble-ai";
            execDiv.style.background = "var(--panel)";
            execDiv.style.borderColor = "rgba(245, 158, 11, 0.25)";
            execDiv.style.boxShadow = "0 8px 30px rgba(245, 158, 11, 0.05)";
            execDiv.style.whiteSpace = "normal";
            execDiv.innerHTML = `
                <div class="zoru-owner-access-card" style="font-family: inherit; color: var(--ink); width:100%; max-width:550px;">
                    <style>
    /* Zoru page scroll lock */
    html, body { overflow: hidden; }
    .shell { height: var(--vh-fixed); min-height: var(--vh-fixed); overflow: hidden; }
    .main { min-height: 0; overflow: hidden; display: flex; flex-direction: column; }

                        .owner-gate-card:hover {
                            background: rgba(245, 158, 11, 0.06) !important;
                            border-color: rgba(245, 158, 11, 0.45) !important;
                            transform: translateY(-2px);
                            box-shadow: 0 4px 15px rgba(245, 158, 11, 0.05);
                        }
                    </style>
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid rgba(255,255,255,0.08);">
                        <span style="font-size: 26px; filter: drop-shadow(0 0 5px rgba(245, 158, 11, 0.4));"></span>
                        <div>
                            <h3 style="margin: 0; font-size: 15px; font-weight: 800; background: linear-gradient(135deg, #f59e0b, #ef4444); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Wewenang &amp; Kendali Owner</h3>
                            <span style="font-size: 9.5px; color: var(--muted); text-transform: uppercase; letter-spacing: 0.8px; font-weight: 700;">Akses Administrator Tertinggi</span>
                        </div>
                    </div>
                    
                    <p style="margin: 0 0 16px 0; font-size: 13px; line-height: 1.5; color: var(--muted);">
                        Sebagai Owner, Anda memegang otoritas penuh atas database siap Milky Garage. Gunakan jalan pintas berikut untuk mengontrol sistem secara langsung:
                    </p>

                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; margin-bottom: 16px;">
                        <a href="{{ route('reports.finance') }}" style="display: flex; flex-direction: column; gap: 6px; background: rgba(255,255,255,0.02); border: 1px solid var(--line); border-radius: 10px; padding: 12px; text-decoration: none; transition: all 0.2s;" class="owner-gate-card">
                            <span style="font-size: 18px;"></span>
                            <strong style="font-size: 12.5px; color: var(--ink); font-weight: 800;">Laporan Keuangan</strong>
                            <span style="font-size: 11px; color: var(--muted); line-height: 1.35;">Monitor omzet lunas, kas profit, dan unduh rekapan CSV luring.</span>
                        </a>
                        
                        <a href="{{ route('catalog.index') }}" style="display: flex; flex-direction: column; gap: 6px; background: rgba(255,255,255,0.02); border: 1px solid var(--line); border-radius: 10px; padding: 12px; text-decoration: none; transition: all 0.2s;" class="owner-gate-card">
                            <span style="font-size: 18px;"></span>
                            <strong style="font-size: 12.5px; color: var(--ink); font-weight: 800;">Master Data</strong>
                            <span style="font-size: 11px; color: var(--muted); line-height: 1.35;">Kelola tipe servis, ubah harga modal sparepart, dan sinkronkan stok.</span>
                        </a>

                        <div style="display: flex; flex-direction: column; gap: 6px; background: rgba(255,255,255,0.02); border: 1px solid var(--line); border-radius: 10px; padding: 12px; cursor: pointer; transition: all 0.2s;" class="owner-gate-card" onclick="submitZoruPrompt('saldo')">
                            <span style="font-size: 18px;"></span>
                            <strong style="font-size: 12.5px; color: var(--ink); font-weight: 800;">Dompet ZeroPay</strong>
                            <span style="font-size: 11px; color: var(--muted); line-height: 1.35;">Isi saldo atau tarik dana demonstasi ZeroPay langsung di bilah navigasi.</span>
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 6px; background: rgba(255,255,255,0.02); border: 1px solid var(--line); border-radius: 10px; padding: 12px; cursor: pointer; transition: all 0.2s;" class="owner-gate-card" onclick="openGuideModal()">
                            <span style="width:28px; height:28px; display:inline-flex; align-items:center; justify-content:center; border-radius:9px; color:#f4a447; border:1px solid rgba(245,158,11,0.28); background:rgba(245,158,11,0.08);">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                    <path d="M8 6h8"></path>
                                    <path d="M8 10h6"></path>
                                </svg>
                            </span>
                            <strong style="font-size: 12.5px; color: var(--ink); font-weight: 800;">Panduan Asisten</strong>
                            <span style="font-size: 11px; color: var(--muted); line-height: 1.35;">Tinjau pedoman sintaks obrolan untuk memicu transaksi luring otomatis.</span>
                        </div>
                    </div>
                    
                    <div style="background: rgba(245, 158, 11, 0.06); border: 1px solid rgba(245, 158, 11, 0.2); border-radius: 8px; padding: 10px 12px; font-size: 11.5px; line-height: 1.45; color: #fbbf24; font-weight: 600;">
                         <strong>Jalan Pintas Cepat:</strong> Anda dapat mengklik tombol di atas untuk membuka menu atau memicu asisten ZoruAi secara instan!
                    </div>
                </div>
            `;
            chatBox.appendChild(execDiv);
            scrollToBottom();
        });
    }

    function openHiddenGemPortal() {
        const overlay = document.getElementById('hiddenGemComingSoonOverlay');
        if (overlay) {
            overlay.style.display = 'grid';
        }
    }

    const hiddenGemShortcutBtn = document.getElementById('hiddenGemShortcutBtn');
    if (hiddenGemShortcutBtn) {
        hiddenGemShortcutBtn.addEventListener('click', openHiddenGemPortal);
    }

    const hiddenGemComingSoonClose = document.getElementById('hiddenGemComingSoonClose');
    if (hiddenGemComingSoonClose) {
        hiddenGemComingSoonClose.addEventListener('click', function() {
            const overlay = document.getElementById('hiddenGemComingSoonOverlay');
            if (overlay) {
                overlay.style.display = 'none';
            }
        });
    }

    const hiddenGemRpsBtn = document.getElementById('hiddenGemRpsBtn');
    if (hiddenGemRpsBtn) {
        hiddenGemRpsBtn.addEventListener('click', openHiddenGemPortal);
    }

    
    // ZoruAi Action Card Generator
    function handleZoruAction(action) {
        const chatBox = document.getElementById('chatBox');
        if (action.type === 'restock_sparepart') {
            createRestockCard(action);
        } else if (action.type === 'update_price_form') {
            createUpdatePriceCard();
        } else if (action.type === 'apply_discount_form') {
            createApplyDiscountCard();
        } else if (action.type === 'restock_part_form') {
            createRestockPartFormCard();
        } else if (action.type === 'set_salary_form') {
            createSetSalaryCard();
        } else if (action.type === 'add_service_form') {
            createAddServiceCard();
        } else if (action.type === 'topup_form') {
            createTopupFormCard();
        } else if (action.type === 'withdraw_form') {
            createWithdrawFormCard();
        } else if (action.type === 'booking_form') {
            createBookingFormCard();
        } else if (action.type === 'salary_report_form') {
            createSalaryReportCard(action);
        } else if (action.type === 'acc_booking_form') {
            createAccBookingFormCard(action);
        } else if (action.type === 'add_spare_part_form') {
            appendHint(' Pintasan master data: Gunakan menu [Katalog] -> klik tombol [Tambah Jasa/Sparepart Baru] untuk mendaftarkan item baru ke sistem.');
        } else if (action.type === 'delete_service_form' || action.type === 'delete_spare_part_form') {
            appendHint(' Pintasan master data: Anda dapat menghapus data jasa/spareparts secara permanen melalui tabel daftar item di menu [Master Data].');
        }
    }
    
    function appendHint(text) {
        const chatBox = document.getElementById('chatBox');
        const hintDiv = document.createElement('div');
        hintDiv.className = "zoru-bubble zoru-bubble-ai";
        hintDiv.style.background = "var(--primary-soft)";
        hintDiv.style.borderColor = "rgba(32, 188, 168, 0.2)";
        hintDiv.style.color = "var(--primary-dark)";
        hintDiv.textContent = text;
        chatBox.appendChild(hintDiv);
        scrollToBottom();
    }

    function createTrendPeriodPickerCard() {
        const chatBox = document.getElementById('chatBox');
        const card = document.createElement('div');
        card.className = 'zoru-action-card';
        card.style.maxWidth = '520px';

        card.innerHTML = `
            <h3>Analisis Trend</h3>
            <div style="display:grid; grid-template-columns:repeat(3, minmax(0, 1fr)); gap:10px;">
                <button type="button" class="button" data-period-prompt="analisis trend bulan ini" style="margin-top:0;">Bulan Ini</button>
                <button type="button" class="button secondary" data-period-prompt="analisis trend 3 bulan terakhir" style="margin-top:0;">3 Bulan Terakhir</button>
                <button type="button" class="button secondary" data-period-prompt="analisis trend 6 bulan terakhir" style="margin-top:0;">6 Bulan Terakhir</button>
            </div>
        `;

        card.querySelectorAll('[data-period-prompt]').forEach((button) => {
            button.addEventListener('click', () => {
                const prompt = button.dataset.periodPrompt;
                card.remove();
                submitZoruPrompt(prompt);
            });
        });

        chatBox.appendChild(card);
        scrollToBottom();
    }
    
    // Form Creation Functions
    function createUpdatePriceCard() {
        const chatBox = document.getElementById('chatBox');
        const card = document.createElement('div');
        card.className = 'zoru-action-card';
        
        let optionsHtml = servicesList.map(s => `<option value="${escapeHtml(s.name)}">${escapeHtml(s.name)} (Rp ${s.base_price.toLocaleString('id-ID')})</option>`).join('');
        
        card.innerHTML = `
            <h3> Ubah Harga Servis</h3>
            <div class="field" style="margin-bottom:8px;">
                <label style="font-size:11px;">Pilih Servis</label>
                <select class="zoru-input" data-field="service" style="width:100%; padding:8px 10px;">
                    ${optionsHtml}
                </select>
            </div>
            <div class="field" style="margin-bottom:8px;">
                <label style="font-size:11px;">Harga Baru (Rp)</label>
                <input type="number" class="zoru-input" data-field="price" placeholder="Masukkan harga baru..." style="width:100%; padding:8px 10px;">
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-top:8px;">
                <button type="button" class="button" data-action="confirm" style="margin-top:0;">Konfirmasi</button>
                <button type="button" class="button secondary" data-action="cancel" style="margin-top:0;">Batal</button>
            </div>
            <div class="feedback-msg" style="margin-top:8px;"></div>
        `;
        card.querySelector('h3').textContent = 'Pasang Diskon Servis';
        
        const confirmBtn = card.querySelector('[data-action="confirm"]');
        const cancelBtn = card.querySelector('[data-action="cancel"]');
        const serviceSelect = card.querySelector('[data-field="service"]');
        const priceInput = card.querySelector('[data-field="price"]');
        const feedback = card.querySelector('.feedback-msg');
        
        confirmBtn.addEventListener('click', () => {
            const serviceName = serviceSelect.value;
            const newPrice = priceInput.value.trim();
            if (!newPrice || isNaN(newPrice) || parseInt(newPrice) < 0) {
                feedback.innerHTML = `<span style="color:var(--danger); font-size:11px; font-weight:700;">Harga tidak valid!</span>`;
                return;
            }
            
            confirmBtn.disabled = true;
            cancelBtn.disabled = true;
            serviceSelect.disabled = true;
            priceInput.disabled = true;
            
            const promptText = `ubah harga ${serviceName} jadi ${newPrice}`;
            submitZoruPrompt(promptText);
            card.remove();
        });
        
        cancelBtn.addEventListener('click', () => {
            card.style.transition = 'all 0.22s ease';
            card.style.opacity = '0';
            card.style.transform = 'scale(0.9)';
            setTimeout(() => card.remove(), 220);
        });
        
        chatBox.appendChild(card);
        scrollToBottom();
    }

    function createApplyDiscountCard() {
        const chatBox = document.getElementById('chatBox');
        const card = document.createElement('div');
        card.className = 'zoru-action-card';
        
        const nowLocal = new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000).toISOString().slice(0, 16);
        const nextWeekLocal = new Date(Date.now() + (7 * 24 * 60 * 60 * 1000) - (new Date()).getTimezoneOffset() * 60000).toISOString().slice(0, 16);
        let optionsHtml = servicesList.map(s => {
            const priceLabel = s.discount_percent > 0
                ? `Rp ${s.discounted_price.toLocaleString('id-ID')} dari Rp ${s.base_price.toLocaleString('id-ID')}`
                : `Rp ${s.base_price.toLocaleString('id-ID')}`;
            return `<option value="${escapeHtml(s.name)}">${escapeHtml(s.name)} (${priceLabel})</option>`;
        }).join('');
        
        card.innerHTML = `
            <h3> Pasang Diskon Servis</h3>
            <div class="field" style="margin-bottom:8px;">
                <label style="font-size:11px;">Pilih Servis</label>
                <select class="zoru-input" data-field="service" style="width:100%; padding:8px 10px;">
                    ${optionsHtml}
                </select>
            </div>
            <div class="field" style="margin-bottom:8px;">
                <label style="font-size:11px;">Persentase Diskon (%)</label>
                <input type="number" class="zoru-input" data-field="percent" placeholder="Masukkan % diskon (cth: 10)..." min="1" max="100" style="width:100%; padding:8px 10px;">
            </div>
            <div class="field" style="margin-bottom:8px;">
                <label style="font-size:11px;">Judul Promo</label>
                <input type="text" class="zoru-input" data-field="title" placeholder="Contoh: Hemat Servis Akhir Pekan" style="width:100%; padding:8px 10px;">
            </div>
            <div class="field" style="margin-bottom:8px;">
                <label style="font-size:11px;">Deskripsi Promo</label>
                <textarea class="zoru-input" data-field="description" placeholder="Tulis pesan promo untuk pelanggan." rows="3" style="width:100%; padding:8px 10px; resize:vertical;"></textarea>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                <div class="field" style="margin-bottom:8px;">
                    <label style="font-size:11px;">Mulai</label>
                    <input type="datetime-local" class="zoru-input" data-field="starts_at" value="${nowLocal}" style="width:100%; padding:8px 10px;">
                </div>
                <div class="field" style="margin-bottom:8px;">
                    <label style="font-size:11px;">Selesai</label>
                    <input type="datetime-local" class="zoru-input" data-field="ends_at" value="${nextWeekLocal}" style="width:100%; padding:8px 10px;">
                </div>
            </div>
            <label style="display:flex; align-items:center; gap:8px; font-size:12px; font-weight:800; margin:4px 0 8px;">
                <input type="checkbox" data-field="is_active" checked style="width:auto;"> Aktifkan promo
            </label>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-top:8px;">
                <button type="button" class="button" data-action="confirm" style="margin-top:0;">Konfirmasi</button>
                <button type="button" class="button secondary" data-action="cancel" style="margin-top:0;">Batal</button>
            </div>
            <div class="feedback-msg" style="margin-top:8px;"></div>
        `;
        
        const confirmBtn = card.querySelector('[data-action="confirm"]');
        const cancelBtn = card.querySelector('[data-action="cancel"]');
        const serviceSelect = card.querySelector('[data-field="service"]');
        const percentInput = card.querySelector('[data-field="percent"]');
        const titleInput = card.querySelector('[data-field="title"]');
        const descriptionInput = card.querySelector('[data-field="description"]');
        const startsAtInput = card.querySelector('[data-field="starts_at"]');
        const endsAtInput = card.querySelector('[data-field="ends_at"]');
        const isActiveInput = card.querySelector('[data-field="is_active"]');
        const feedback = card.querySelector('.feedback-msg');
        
        confirmBtn.addEventListener('click', () => {
            const serviceName = serviceSelect.value;
            const percent = percentInput.value.trim();
            if (!percent || isNaN(percent) || parseFloat(percent) <= 0 || parseFloat(percent) > 100) {
                feedback.innerHTML = `<span style="color:var(--danger); font-size:11px; font-weight:700;">Persentase tidak valid! (1-100)</span>`;
                return;
            }
            
            confirmBtn.disabled = true;
            cancelBtn.disabled = true;
            serviceSelect.disabled = true;
            percentInput.disabled = true;
            titleInput.disabled = true;
            descriptionInput.disabled = true;
            startsAtInput.disabled = true;
            endsAtInput.disabled = true;
            isActiveInput.disabled = true;
            
            const title = titleInput.value.trim() || `Diskon ${percent}% ${serviceName}`;
            const description = descriptionInput.value.trim() || 'Promo aktif untuk pelanggan Milky Garage.';
            const startsAt = startsAtInput.value.trim();
            const endsAt = endsAtInput.value.trim();
            const status = isActiveInput.checked ? 'aktif' : 'nonaktif';
            const promptText = `diskon ${percent}% untuk ${serviceName} | judul: ${title} | deskripsi: ${description} | mulai: ${startsAt} | selesai: ${endsAt} | status: ${status}`;
            submitZoruPrompt(promptText);
            card.remove();
        });
        
        cancelBtn.addEventListener('click', () => {
            card.style.transition = 'all 0.22s ease';
            card.style.opacity = '0';
            card.style.transform = 'scale(0.9)';
            setTimeout(() => card.remove(), 220);
        });
        
        chatBox.appendChild(card);
        scrollToBottom();
    }

    function createRestockPartFormCard() {
        const chatBox = document.getElementById('chatBox');
        const card = document.createElement('div');
        card.className = 'zoru-action-card';
        
        let optionsHtml = sparePartsList.map(p => `<option value="${escapeHtml(p.name)}">${escapeHtml(p.name)} (Stok: ${p.stock})</option>`).join('');
        
        card.innerHTML = `
            <h3> Restock Sparepart</h3>
            <div class="field" style="margin-bottom:8px;">
                <label style="font-size:11px;">Pilih Sparepart</label>
                <select class="zoru-input" data-field="part" style="width:100%; padding:8px 10px;">
                    ${optionsHtml}
                </select>
            </div>
            <div class="field" style="margin-bottom:8px;">
                <label style="font-size:11px;">Jumlah Restock (unit)</label>
                <input type="number" class="zoru-input" data-field="qty" placeholder="Masukkan jumlah unit..." min="1" value="10" style="width:100%; padding:8px 10px;">
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-top:8px;">
                <button type="button" class="button" data-action="confirm" style="margin-top:0;">Konfirmasi</button>
                <button type="button" class="button secondary" data-action="cancel" style="margin-top:0;">Batal</button>
            </div>
            <div class="feedback-msg" style="margin-top:8px;"></div>
        `;
        
        const confirmBtn = card.querySelector('[data-action="confirm"]');
        const cancelBtn = card.querySelector('[data-action="cancel"]');
        const partSelect = card.querySelector('[data-field="part"]');
        const qtyInput = card.querySelector('[data-field="qty"]');
        const feedback = card.querySelector('.feedback-msg');
        
        confirmBtn.addEventListener('click', () => {
            const partName = partSelect.value;
            const qty = qtyInput.value.trim();
            if (!qty || isNaN(qty) || parseInt(qty) <= 0) {
                feedback.innerHTML = `<span style="color:var(--danger); font-size:11px; font-weight:700;">Jumlah tidak valid!</span>`;
                return;
            }
            
            confirmBtn.disabled = true;
            cancelBtn.disabled = true;
            partSelect.disabled = true;
            qtyInput.disabled = true;
            
            const promptText = `restock ${partName} sebanyak ${qty} unit`;
            submitZoruPrompt(promptText);
            card.remove();
        });
        
        cancelBtn.addEventListener('click', () => {
            card.style.transition = 'all 0.22s ease';
            card.style.opacity = '0';
            card.style.transform = 'scale(0.9)';
            setTimeout(() => card.remove(), 220);
        });
        
        chatBox.appendChild(card);
        scrollToBottom();
    }

    function createSetSalaryCard() {
        const chatBox = document.getElementById('chatBox');
        const card = document.createElement('div');
        card.className = 'zoru-action-card';
        const sharePercentFor = (service) => {
            const mechanic = parseInt(service?.mechanic_salary ?? 35, 10);
            const cashier = parseInt(service?.cashier_salary ?? 15, 10);
            const total = mechanic + cashier;

            if (mechanic < 0 || cashier < 0 || total < 50 || total > 100) {
                return { mechanic: 35, cashier: 15 };
            }

            return { mechanic, cashier };
        };
        
        let optionsHtml = servicesList.map(s => `<option value="${escapeHtml(s.name)}">${escapeHtml(s.name)}</option>`).join('');
        
        card.innerHTML = `
            <h3> Atur Gaji Karyawan</h3>
            <div class="field" style="margin-bottom:8px;">
                <label style="font-size:11px;">Pilih Servis</label>
                <select class="zoru-input" data-field="service" style="width:100%; padding:8px 10px;">
                    ${optionsHtml}
                </select>
            </div>
            <div class="field" style="margin-bottom:8px;">
                <label style="font-size:11px;">Persentase Mekanik (%)</label>
                <input type="number" class="zoru-input" data-field="mechanic" min="0" max="100" placeholder="Contoh: 35" style="width:100%; padding:8px 10px;">
            </div>
            <div class="field" style="margin-bottom:8px;">
                <label style="font-size:11px;">Persentase Kasir (%)</label>
                <input type="number" class="zoru-input" data-field="cashier" min="0" max="100" placeholder="Contoh: 15" style="width:100%; padding:8px 10px;">
            </div>
            <p style="margin:0 0 8px; color:var(--muted); font-size:11px; line-height:1.5;">Total mekanik + kasir wajib minimal 50% dan maksimal 100%. Owner otomatis mendapat sisa jasa, sparepart tetap milik owner penuh.</p>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-top:8px;">
                <button type="button" class="button" data-action="confirm" style="margin-top:0;">Konfirmasi</button>
                <button type="button" class="button secondary" data-action="cancel" style="margin-top:0;">Batal</button>
            </div>
            <div class="feedback-msg" style="margin-top:8px;"></div>
        `;
        
        const confirmBtn = card.querySelector('[data-action="confirm"]');
        const cancelBtn = card.querySelector('[data-action="cancel"]');
        const serviceSelect = card.querySelector('[data-field="service"]');
        const mechanicInput = card.querySelector('[data-field="mechanic"]');
        const cashierInput = card.querySelector('[data-field="cashier"]');
        const feedback = card.querySelector('.feedback-msg');
        const applyCurrentShare = () => {
            const service = servicesList.find(s => s.name === serviceSelect.value);
            const share = sharePercentFor(service);
            mechanicInput.value = share.mechanic;
            cashierInput.value = share.cashier;
        };

        applyCurrentShare();
        serviceSelect.addEventListener('change', applyCurrentShare);
        
        confirmBtn.addEventListener('click', () => {
            const serviceName = serviceSelect.value;
            const mechPercent = mechanicInput.value.trim();
            const cashPercent = cashierInput.value.trim();
            const mechValue = parseInt(mechPercent, 10);
            const cashValue = parseInt(cashPercent, 10);
            const total = mechValue + cashValue;

            if (!mechPercent || isNaN(mechValue) || mechValue < 0 || mechValue > 100 || !cashPercent || isNaN(cashValue) || cashValue < 0 || cashValue > 100) {
                feedback.innerHTML = `<span style="color:var(--danger); font-size:11px; font-weight:700;">Persentase tidak valid!</span>`;
                return;
            }

            if (total < 50) {
                feedback.innerHTML = `<span style="color:var(--danger); font-size:11px; font-weight:700;">Total mekanik + kasir minimal 50%.</span>`;
                return;
            }

            if (total > 100) {
                feedback.innerHTML = `<span style="color:var(--danger); font-size:11px; font-weight:700;">Total mekanik + kasir maksimal 100%.</span>`;
                return;
            }
            
            confirmBtn.disabled = true;
            cancelBtn.disabled = true;
            serviceSelect.disabled = true;
            mechanicInput.disabled = true;
            cashierInput.disabled = true;

            const service = servicesList.find(s => s.name === serviceName);
            if (service) {
                service.mechanic_salary = mechValue;
                service.cashier_salary = cashValue;
            }
            
            const promptText = `ubah gaji ${serviceName} jadi mekanik ${mechValue}% dan kasir ${cashValue}%`;
            submitZoruPrompt(promptText);
            card.remove();
        });
        
        cancelBtn.addEventListener('click', () => {
            card.style.transition = 'all 0.22s ease';
            card.style.opacity = '0';
            card.style.transform = 'scale(0.9)';
            setTimeout(() => card.remove(), 220);
        });
        
        chatBox.appendChild(card);
        scrollToBottom();
    }

    function createAddServiceCard() {
        const chatBox = document.getElementById('chatBox');
        const card = document.createElement('div');
        card.className = 'zoru-action-card';
        
        card.innerHTML = `
            <h3> Tambah Jenis Servis Baru</h3>
            <div class="field" style="margin-bottom:6px;">
                <label style="font-size:11px;">Nama Servis</label>
                <input type="text" class="zoru-input" data-field="name" placeholder="Masukkan nama servis..." style="width:100%; padding:6px 10px;">
            </div>
            <div class="field" style="margin-bottom:6px;">
                <label style="font-size:11px;">Durasi (menit)</label>
                <input type="number" class="zoru-input" data-field="duration" placeholder="Estimasi durasi..." min="1" style="width:100%; padding:6px 10px;">
            </div>
            <div class="field" style="margin-bottom:6px;">
                <label style="font-size:11px;">Harga Servis (Rp)</label>
                <input type="number" class="zoru-input" data-field="price" placeholder="Harga jasa servis..." style="width:100%; padding:6px 10px;">
            </div>
            <div class="field" style="margin-bottom:6px;">
                <label style="font-size:11px;">Persentase Mekanik (%)</label>
                <input type="number" class="zoru-input" data-field="mechanic" min="0" max="100" value="35" placeholder="Contoh: 35" style="width:100%; padding:6px 10px;">
            </div>
            <div class="field" style="margin-bottom:6px;">
                <label style="font-size:11px;">Persentase Kasir (%)</label>
                <input type="number" class="zoru-input" data-field="cashier" min="0" max="100" value="15" placeholder="Contoh: 15" style="width:100%; padding:6px 10px;">
            </div>
            <p style="margin:0 0 6px; color:var(--muted); font-size:11px; line-height:1.45;">Total mekanik + kasir wajib minimal 50% dan maksimal 100%.</p>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-top:8px;">
                <button type="button" class="button" data-action="confirm" style="margin-top:0;">Konfirmasi</button>
                <button type="button" class="button secondary" data-action="cancel" style="margin-top:0;">Batal</button>
            </div>
            <div class="feedback-msg" style="margin-top:6px;"></div>
        `;
        
        const confirmBtn = card.querySelector('[data-action="confirm"]');
        const cancelBtn = card.querySelector('[data-action="cancel"]');
        const nameInput = card.querySelector('[data-field="name"]');
        const durationInput = card.querySelector('[data-field="duration"]');
        const priceInput = card.querySelector('[data-field="price"]');
        const mechanicInput = card.querySelector('[data-field="mechanic"]');
        const cashierInput = card.querySelector('[data-field="cashier"]');
        const feedback = card.querySelector('.feedback-msg');
        
        confirmBtn.addEventListener('click', () => {
            const name = nameInput.value.trim();
            const duration = durationInput.value.trim();
            const price = priceInput.value.trim();
            const mechanic = mechanicInput.value.trim();
            const cashier = cashierInput.value.trim();
            
            const mechanicValue = parseInt(mechanic, 10);
            const cashierValue = parseInt(cashier, 10);
            const employeeShare = mechanicValue + cashierValue;

            if (!name || !duration || isNaN(duration) || parseInt(duration) <= 0 || !price || isNaN(price) || parseInt(price) < 0 || !mechanic || isNaN(mechanicValue) || mechanicValue < 0 || mechanicValue > 100 || !cashier || isNaN(cashierValue) || cashierValue < 0 || cashierValue > 100) {
                feedback.innerHTML = `<span style="color:var(--danger); font-size:11px; font-weight:700;">Semua field wajib diisi dengan valid!</span>`;
                return;
            }

            if (employeeShare < 50 || employeeShare > 100) {
                feedback.innerHTML = `<span style="color:var(--danger); font-size:11px; font-weight:700;">Total mekanik + kasir harus 50% sampai 100%.</span>`;
                return;
            }
            
            confirmBtn.disabled = true;
            cancelBtn.disabled = true;
            nameInput.disabled = true;
            durationInput.disabled = true;
            priceInput.disabled = true;
            mechanicInput.disabled = true;
            cashierInput.disabled = true;
            
            const promptText = `tambah servis ${name} dengan durasi ${duration} menit, harga ${price}, gaji mekanik ${mechanicValue}%, gaji kasir ${cashierValue}%`;
            submitZoruPrompt(promptText);
            card.remove();
        });
        
        cancelBtn.addEventListener('click', () => {
            card.style.transition = 'all 0.22s ease';
            card.style.opacity = '0';
            card.style.transform = 'scale(0.9)';
            setTimeout(() => card.remove(), 220);
        });
        
        chatBox.appendChild(card);
        scrollToBottom();
    }

    function createTopupFormCard() {
        const chatBox = document.getElementById('chatBox');
        const card = document.createElement('div');
        card.className = 'zoru-action-card';
        
        card.innerHTML = `
            <h3>Top-up Saldo ZeroPay</h3>
            <div class="field" style="margin-bottom:8px;">
                <label style="font-size:11px;">Nominal Top-up (Rp)</label>
                <input type="number" class="zoru-input" data-field="amount" placeholder="Cth: 50000" min="10000" style="width:100%; padding:8px 10px;">
            </div>
            <div class="field" style="margin-bottom:8px;">
                <label style="font-size:11px;">Metode Pembayaran</label>
                <select class="zoru-input" data-field="method" style="width:100%; padding:8px 10px;">
                    <option value="GoPay">GoPay</option>
                    <option value="OVO">OVO</option>
                    <option value="DANA">DANA</option>
                    <option value="ShopeePay">ShopeePay</option>
                </select>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-top:8px;">
                <button type="button" class="button" data-action="confirm" style="margin-top:0;">Konfirmasi</button>
                <button type="button" class="button secondary" data-action="cancel" style="margin-top:0;">Batal</button>
            </div>
            <div class="feedback-msg" style="margin-top:8px;"></div>
        `;
        
        const confirmBtn = card.querySelector('[data-action="confirm"]');
        const cancelBtn = card.querySelector('[data-action="cancel"]');
        const amountInput = card.querySelector('[data-field="amount"]');
        const methodSelect = card.querySelector('[data-field="method"]');
        const feedback = card.querySelector('.feedback-msg');
        
        confirmBtn.addEventListener('click', () => {
            const amount = amountInput.value.trim();
            const method = methodSelect.value;
            if (!amount || isNaN(amount) || parseInt(amount) < 10000) {
                feedback.innerHTML = `<span style="color:var(--danger); font-size:11px; font-weight:700;">Nominal minimal Rp 10.000!</span>`;
                return;
            }
            
            confirmBtn.disabled = true;
            cancelBtn.disabled = true;
            
            // Hide form visually
            card.style.display = 'none';
            
            // Show QRIS simulation and send AJAX once countdown finishes
            showPaymentLoading((overlayElement, timerInstance) => {
                const requestAmount = parseInt(amount, 10);
                
                fetch("{{ route('reports.analytics.zoru') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ action: 'topup_zeropay', amount: requestAmount, method: method.toLowerCase() })
                })
                .then(res => res.json())
                .then(data => {
                    if (overlayElement) overlayElement.remove();
                    if (timerInstance) clearInterval(timerInstance);
                    card.remove();
                    
                    if (data.balance !== undefined) {
                        updateSidebarZeroPayBalance(data.balance);
                    }

                    const chatBox = document.getElementById('chatBox');
                    const zoruDiv = document.createElement('div');
                    zoruDiv.className = "zoru-bubble zoru-bubble-ai";
                    zoruDiv.innerHTML = data.reply;
                    makeCommandsClickable(zoruDiv);
                    chatBox.appendChild(zoruDiv);
                    scrollToBottom();
                    
                    const systemKeywords = [
                        'zeropay', 'saldo', 'tarik', 'withdraw', 'booking', 'servis', 'service', 
                        'oli', 'gaji', 'harga', 'diskon', 'restock', 'katalog', 'sparepart', 
                        'spare part', 'pin', 'keuangan', 'laporan', 'aktivitas', 'riwayat',
                        'fitur', 'sistem', 'kasir', 'mekanik', 'owner', 'cara', 'bagaimana',
                        'panduan', 'bantuan', 'troubleshoot'
                    ];
                    const lowercasePrompt = 'topup zeropay';
                    const lowercaseReply = data.reply.toLowerCase();
                    const isSystemic = systemKeywords.some(keyword => lowercasePrompt.includes(keyword) || lowercaseReply.includes(keyword));
                    if (isSystemic) {
                        appendInformationalFeedback(zoruDiv, null);
                    }
                })
                .catch(err => {
                    console.error('Error fetching Zoru:', err);
                    if (overlayElement) overlayElement.remove();
                    if (timerInstance) clearInterval(timerInstance);
                    card.remove();
                    
                    const errDiv = document.createElement('div');
                    errDiv.className = "zoru-bubble zoru-bubble-ai";
                    errDiv.style.background = "var(--danger)";
                    errDiv.style.color = "#fff";
                    errDiv.style.borderColor = "var(--danger)";
                    errDiv.textContent = 'Terjadi kendala saat top-up.';
                    chatBox.appendChild(errDiv);
                    scrollToBottom();
                });
            });
        });
        
        cancelBtn.addEventListener('click', () => {
            card.style.transition = 'all 0.22s ease';
            card.style.opacity = '0';
            card.style.transform = 'scale(0.9)';
            setTimeout(() => card.remove(), 220);
        });
        
        chatBox.appendChild(card);
        scrollToBottom();
    }

    function createWithdrawFormCard() {
        const chatBox = document.getElementById('chatBox');
        const card = document.createElement('div');
        card.className = 'zoru-action-card';
        
        card.innerHTML = `
            <h3>Tarik Dana ZeroPay</h3>
            <div class="field" style="margin-bottom:8px;">
                <label style="font-size:11px;">Nominal Penarikan (Rp)</label>
                <input type="number" class="zoru-input" data-field="amount" placeholder="Cth: 20000" min="5000" style="width:100%; padding:8px 10px;">
            </div>
            <div class="field" style="margin-bottom:8px;">
                <label style="font-size:11px;">E-Wallet Tujuan</label>
                <select class="zoru-input" data-field="wallet" style="width:100%; padding:8px 10px;">
                    <option value="GoPay">GoPay</option>
                    <option value="OVO">OVO</option>
                    <option value="DANA">DANA</option>
                    <option value="LinkAja">LinkAja</option>
                </select>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-top:8px;">
                <button type="button" class="button" data-action="confirm" style="margin-top:0;">Konfirmasi</button>
                <button type="button" class="button secondary" data-action="cancel" style="margin-top:0;">Batal</button>
            </div>
            <div class="feedback-msg" style="margin-top:6px;"></div>
        `;
        
        const confirmBtn = card.querySelector('[data-action="confirm"]');
        const cancelBtn = card.querySelector('[data-action="cancel"]');
        const amountInput = card.querySelector('[data-field="amount"]');
        const walletSelect = card.querySelector('[data-field="wallet"]');
        const feedback = card.querySelector('.feedback-msg');
        
        confirmBtn.addEventListener('click', () => {
            const amount = amountInput.value.trim();
            const wallet = walletSelect.value;
            if (!amount || isNaN(amount) || parseInt(amount) < 5000) {
                feedback.innerHTML = `<span style="color:var(--danger); font-size:11px; font-weight:700;">Nominal minimal Rp 5.000!</span>`;
                return;
            }
            
            confirmBtn.disabled = true;
            confirmBtn.disabled = false; // reset immediately to allow subsequent actions
            
            showPinVerificationModal((pin, modalOverlay, errorDiv, pinConfirmBtn, pinCloseBtn) => {
                pinConfirmBtn.disabled = true;
                pinCloseBtn.disabled = true;
                pinConfirmBtn.textContent = 'Memverifikasi...';
                errorDiv.textContent = '';
                
                const processingOverlay = showProcessingSimulation('Memproses Penarikan', 'Permintaan tarik dana sedang diproses.');
                const requestAmount = parseInt(amount, 10);
                
                fetch("{{ route('reports.analytics.zoru') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ action: 'withdraw_zeropay', amount: requestAmount, wallet, pin })
                })
                .then(res => res.json())
                .then(data => {
                    if (processingOverlay) processingOverlay.remove();
                    if (data.status === 'error') {
                        // Display error in modal error div, clear PIN input, keep modal open
                        errorDiv.textContent = data.reply || 'Permintaan belum bisa diproses.';
                        pinConfirmBtn.disabled = false;
                        pinCloseBtn.disabled = false;
                        pinConfirmBtn.textContent = 'Verifikasi & Tarik';
                        const pinInput = document.getElementById('zoruPinInput');
                        if (pinInput) {
                            pinInput.value = '';
                            pinInput.focus();
                        }
                    } else {
                        // Success! Remove modal and card
                        modalOverlay.remove();
                        card.remove();
                        
                        // Update sidebar balance dynamically
                        if (data.balance !== undefined) {
                            updateSidebarZeroPayBalance(data.balance);
                        }

                        // Append ZoruAi response bubble
                        const chatBox = document.getElementById('chatBox');
                        const zoruDiv = document.createElement('div');
                        zoruDiv.className = "zoru-bubble zoru-bubble-ai";
                        zoruDiv.innerHTML = data.reply;
                        makeCommandsClickable(zoruDiv);
                        chatBox.appendChild(zoruDiv);
                        scrollToBottom();
                        
                        // Append feedback buttons if applicable
                        const systemKeywords = [
                            'zeropay', 'saldo', 'tarik', 'withdraw', 'booking', 'servis', 'service', 
                            'oli', 'gaji', 'harga', 'diskon', 'restock', 'katalog', 'sparepart', 
                            'spare part', 'pin', 'keuangan', 'laporan', 'aktivitas', 'riwayat',
                            'fitur', 'sistem', 'kasir', 'mekanik', 'owner', 'cara', 'bagaimana',
                            'panduan', 'bantuan', 'troubleshoot'
                        ];
                        const lowercasePrompt = 'topup zeropay';
                        const lowercaseReply = replyText.toLowerCase();
                        const isSystemic = systemKeywords.some(keyword => lowercasePrompt.includes(keyword) || lowercaseReply.includes(keyword));
                        if (isSystemic) {
                            appendInformationalFeedback(zoruDiv, null);
                        }
                    }
                })
                .catch(err => {
                    if (processingOverlay) processingOverlay.remove();
                    console.error('Error fetching Zoru:', err);
                    errorDiv.textContent = 'Terjadi kendala saat memproses.';
                    pinConfirmBtn.disabled = false;
                    pinCloseBtn.disabled = false;
                    pinConfirmBtn.textContent = 'Verifikasi & Tarik';
                });
            });
        });
        
        cancelBtn.addEventListener('click', () => {
            card.style.transition = 'all 0.22s ease';
            card.style.opacity = '0';
            card.style.transform = 'scale(0.9)';
            setTimeout(() => card.remove(), 220);
        });
        
        chatBox.appendChild(card);
        scrollToBottom();
    }

    function createRestockCard(action) {
        const chatBox = document.getElementById('chatBox');
        const card = document.createElement('div');
        card.className = 'zoru-action-card';
        card.id = `restock_card_${action.token}`;
        card.dataset.sparePartId = action.spare_part_id;
        
        card.innerHTML = `
            <h3> Konfirmasi Restock</h3>
            <div class="zoru-action-row"><span>Sparepart</span><span>${escapeHtml(action.name)}</span></div>
            <div class="zoru-action-row"><span>Stok saat ini</span><span>${action.current_stock} unit</span></div>
            <div class="zoru-action-row"><span>Jumlah restock</span><span>${action.qty} unit</span></div>
            <div class="zoru-action-row"><span>Harga satuan</span><span>${formatRupiah(action.unit_price)}</span></div>
            <div class="zoru-action-row"><span>Total bayar</span><span>${formatRupiah(action.total)}</span></div>
            <div class="zoru-action-row"><span>Saldo ZeroPay owner</span><span>${formatRupiah(action.balance)}</span></div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-top:8px;">
                <button type="button" class="button" data-action="pay" style="margin-top:0;" onclick="payRestock('${action.token}', this)">Bayar</button>
                <button type="button" class="button secondary" data-action="cancel" style="margin-top:0;" onclick="cancelRestock('${action.token}', this)">Batal</button>
            </div>
            <div style="font-size:11px; margin-top:4px; line-height:1.4; color:var(--muted)">Stok baru ditambahkan setelah pembayaran berhasil.</div>
            <div id="restock_feedback_${action.token}" style="margin-top:8px;"></div>
        `;
        
        chatBox.appendChild(card);
        scrollToBottom();
    }
    
    function payRestock(token, btn) {
        const card = document.getElementById(`restock_card_${token}`);
        const feedback = document.getElementById(`restock_feedback_${token}`);
        const cancelBtn = btn.nextElementSibling;
        
        btn.disabled = true;
        cancelBtn.disabled = true;
        btn.textContent = 'Membayar...';

        const submitRestockPayment = () => {
            fetch("{{ route('reports.analytics.zoru.restock') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ token: token })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    btn.textContent = 'Berhasil';
                    feedback.innerHTML = `<div style="margin-top:12px; padding:10px 12px; border-radius:8px; background:color-mix(in srgb, var(--ok) 14%, transparent); color:var(--ok); font-size:13px; line-height:1.5; font-weight:700;">${escapeHtml(data.message)}</div>`;
                    
                    updateSidebarZeroPayBalance(data.balance);
                    const partId = parseInt(card?.dataset?.sparePartId || '0', 10);
                    const part = sparePartsList.find(item => parseInt(item.id, 10) === partId);
                    if (part && data.stock !== undefined) {
                        part.stock = data.stock;
                    }
                } else {
                    btn.disabled = false;
                    cancelBtn.disabled = false;
                    btn.textContent = 'Bayar';
                    feedback.innerHTML = `<div style="margin-top:12px; padding:10px 12px; border-radius:8px; background:color-mix(in srgb, var(--danger) 14%, transparent); color:var(--danger); font-size:13px; line-height:1.5; font-weight:700;">${escapeHtml(data.message)}</div>`;
                }
            })
            .catch(err => {
                console.error('Error paying restock:', err);
                btn.disabled = false;
                cancelBtn.disabled = false;
                btn.textContent = 'Bayar';
                feedback.innerHTML = `<div style="margin-top:12px; padding:10px 12px; border-radius:8px; background:color-mix(in srgb, var(--danger) 14%, transparent); color:var(--danger); font-size:13px; line-height:1.5; font-weight:700;">Koneksi belum merespons.</div>`;
            });
        };

        if (window.runZeroPaySimulation) {
            window.runZeroPaySimulation({
                title: 'Memproses Restock',
                subtitle: 'Saldo ZeroPay owner dan pembaruan stok sedang diproses.',
                status: 'Validasi pembelian stok...',
                duration: 1400,
                onComplete: submitRestockPayment
            });
            return;
        }

        submitRestockPayment();
    }
    
    function cancelRestock(token, btn) {
        const card = document.getElementById(`restock_card_${token}`);
        const feedback = document.getElementById(`restock_feedback_${token}`);
        const payBtn = btn.previousElementSibling;
        
        btn.disabled = true;
        payBtn.disabled = true;
        btn.textContent = 'Membatalkan...';
        
        fetch("{{ route('reports.analytics.zoru.restock.cancel') }}", {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ token: token })
        })
        .then(res => res.json())
        .then(data => {
            btn.textContent = 'Dibatalkan';
            feedback.innerHTML = `<div style="margin-top:12px; padding:10px 12px; border-radius:8px; background:var(--line); color:var(--muted); font-size:13px; line-height:1.5; font-weight:700;">${escapeHtml(data.message)}</div>`;
            setTimeout(() => {
                if (card) {
                    card.style.transition = 'all 0.22s ease';
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.9)';
                    setTimeout(() => card.remove(), 220);
                }
            }, 1200);
        })
        .catch(err => {
            console.error('Error cancelling restock:', err);
            btn.disabled = false;
            payBtn.disabled = false;
            btn.textContent = 'Batal';
        });
    }
    
    function createBookingFormCard() {
        const chatBox = document.getElementById('chatBox');
        const card = document.createElement('div');
        card.className = 'zoru-action-card';
        card.style.maxWidth = '450px';
        
        let vehicleOptionsHtml = '<option value="">-- Tambah Kendaraan Baru --</option>';
        if (typeof userVehiclesList !== 'undefined' && userVehiclesList.length > 0) {
            vehicleOptionsHtml = userVehiclesList.map(v => `<option value="${v.id}">${v.plate_number} - ${v.brand} ${v.model}</option>`).join('') + vehicleOptionsHtml;
        }
        
        let selectedServiceIds = [];
        
        let servicesHtml = `
            <select class="zoru-input" id="serviceSelector" style="width:100%; padding:8px 10px;">
                <option value="">-- Pilih Layanan Servis (Bisa lebih dari 1) --</option>
                ${servicesList.map(s => `<option value="${s.id}">${escapeHtml(s.name)} (Rp ${s.base_price.toLocaleString('id-ID')})</option>`).join('')}
            </select>
            <div id="selectedServicesContainer" style="display:flex; flex-wrap:wrap; gap:6px; margin-top:8px;"></div>
        `;
        
        const todayStr = new Date().toISOString().split('T')[0];
        
        card.innerHTML = `
            <h3> Booking Service Baru</h3>
            
            <div class="field" style="margin-bottom:8px;">
                <label style="font-size:11px; font-weight:700;">Kendaraan</label>
                <select class="zoru-input" data-field="vehicle_id" style="width:100%; padding:8px 10px;">
                    ${vehicleOptionsHtml}
                </select>
            </div>
            
            <div id="newVehicleFields" style="border:1px dashed var(--line); border-radius:8px; padding:10px; margin-bottom:8px; background:var(--bg-soft);">
                <div style="font-weight:700; font-size:11px; margin-bottom:6px; color:var(--primary);">Detil Kendaraan Baru:</div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px; margin-bottom:6px;">
                    <div>
                        <label style="font-size:10px;">Plat Nomor *</label>
                        <input type="text" class="zoru-input" data-field="plate_number" placeholder="Cth: B 1234 ABC" style="width:100%; padding:6px 8px; font-size:12px;">
                    </div>
                    <div>
                        <label style="font-size:10px;">Brand/Merk *</label>
                        <input type="text" class="zoru-input" data-field="brand" placeholder="Cth: Honda / Yamaha" style="width:100%; padding:6px 8px; font-size:12px;">
                    </div>
                </div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px;">
                    <div>
                        <label style="font-size:10px;">Model *</label>
                        <input type="text" class="zoru-input" data-field="model" placeholder="Cth: Vario 150" style="width:100%; padding:6px 8px; font-size:12px;">
                    </div>
                    <div>
                        <label style="font-size:10px;">Tahun</label>
                        <input type="number" class="zoru-input" data-field="year" placeholder="Cth: 2019" style="width:100%; padding:6px 8px; font-size:12px;">
                    </div>
                </div>
            </div>
            
            <div class="field" style="margin-bottom:8px;">
                <label style="font-size:11px; font-weight:700; display:block; margin-bottom:4px;">Pilih Layanan Servis *</label>
                ${servicesHtml}
            </div>
            
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px; margin-bottom:8px;">
                <div>
                    <label style="font-size:11px; font-weight:700;">Tanggal Kunjungan *</label>
                    <input type="date" class="zoru-input" data-field="booking_date" min="${todayStr}" value="${todayStr}" style="width:100%; padding:8px 10px;">
                </div>
                <div>
                    <label style="font-size:11px; font-weight:700;">Jam Kunjungan *</label>
                    <select class="zoru-input" data-field="booking_time" style="width:100%; padding:8px 10px;">
                        <option value="08:00">08:00 WIB</option>
                        <option value="09:00">09:00 WIB</option>
                        <option value="10:00">10:00 WIB</option>
                        <option value="11:00">11:00 WIB</option>
                        <option value="13:00">13:00 WIB</option>
                        <option value="14:00">14:00 WIB</option>
                        <option value="15:00">15:00 WIB</option>
                        <option value="16:00">16:00 WIB</option>
                    </select>
                </div>
            </div>
            
            <div class="field" style="margin-bottom:8px;">
                <label style="font-size:11px; font-weight:700;">Catatan / Deskripsi Keluhan</label>
                <textarea class="zoru-input" data-field="notes" placeholder="Tuliskan keluhan motor Anda di sini..." style="width:100%; height:50px; padding:8px 10px; font-size:12px; resize:none;"></textarea>
            </div>
            
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-top:8px;">
                <button type="button" class="button" data-action="confirm" style="margin-top:0;">Konfirmasi Booking</button>
                <button type="button" class="button secondary" data-action="cancel" style="margin-top:0;">Batal</button>
            </div>
            
            <div class="feedback-msg" style="margin-top:8px;"></div>
        `;
        
        const vehicleSelect = card.querySelector('[data-field="vehicle_id"]');
        const newVehicleFields = card.querySelector('#newVehicleFields');
        
        const plateInput = card.querySelector('[data-field="plate_number"]');
        const brandInput = card.querySelector('[data-field="brand"]');
        const modelInput = card.querySelector('[data-field="model"]');
        const yearInput = card.querySelector('[data-field="year"]');
        
        const bookingDateInput = card.querySelector('[data-field="booking_date"]');
        const bookingTimeSelect = card.querySelector('[data-field="booking_time"]');
        const notesTextarea = card.querySelector('[data-field="notes"]');
        
        const confirmBtn = card.querySelector('[data-action="confirm"]');
        const cancelBtn = card.querySelector('[data-action="cancel"]');
        const feedback = card.querySelector('.feedback-msg');
        
        const serviceSelector = card.querySelector('#serviceSelector');
        const selectedServicesContainer = card.querySelector('#selectedServicesContainer');
        
        function renderSelectedServices() {
            selectedServicesContainer.innerHTML = '';
            if (selectedServiceIds.length === 0) {
                selectedServicesContainer.innerHTML = '<span style="color:var(--muted); font-size:11px;">Belum ada layanan dipilih.</span>';
                return;
            }
            selectedServiceIds.forEach(id => {
                const s = servicesList.find(item => item.id == id);
                if (s) {
                    const badge = document.createElement('span');
                    badge.style.cssText = `
                        background: var(--primary-soft);
                        border: 1px solid color-mix(in srgb, var(--primary) 30%, transparent);
                        color: var(--primary-dark);
                        padding: 4px 8px;
                        border-radius: 6px;
                        font-size: 11px;
                        display: inline-flex;
                        align-items: center;
                        gap: 6px;
                        font-weight: 600;
                    `;
                    badge.innerHTML = `
                        ${escapeHtml(s.name)}
                        <span class="remove-btn" style="cursor:pointer; color:var(--danger); font-weight:800; font-size:12px; padding: 0 2px;"></span>
                    `;
                    badge.querySelector('.remove-btn').addEventListener('click', () => {
                        selectedServiceIds = selectedServiceIds.filter(val => val != id);
                        renderSelectedServices();
                    });
                    selectedServicesContainer.appendChild(badge);
                }
            });
        }
        
        serviceSelector.addEventListener('change', () => {
            const val = serviceSelector.value;
            if (val) {
                if (!selectedServiceIds.includes(val)) {
                    selectedServiceIds.push(val);
                }
                serviceSelector.value = ''; // Reset select
                renderSelectedServices();
            }
        });
        
        // Initial render
        renderSelectedServices();
        
        // Show/hide new vehicle fields based on vehicle select
        vehicleSelect.addEventListener('change', () => {
            if (vehicleSelect.value === "") {
                newVehicleFields.style.display = 'block';
            } else {
                newVehicleFields.style.display = 'none';
            }
        });
        
        // Init state
        if (vehicleSelect.value !== "") {
            newVehicleFields.style.display = 'none';
        }
        
        confirmBtn.addEventListener('click', () => {
            const vehicleId = vehicleSelect.value;
            const serviceTypeIds = selectedServiceIds;
            
            const bookingDate = bookingDateInput.value;
            const bookingTime = bookingTimeSelect.value;
            const notes = notesTextarea.value.trim();
            
            // Client side validation
            if (serviceTypeIds.length === 0) {
                feedback.innerHTML = `<span style="color:var(--danger); font-size:11px; font-weight:700;">Harap pilih layanan servis!</span>`;
                return;
            }
            if (!bookingDate) {
                feedback.innerHTML = `<span style="color:var(--danger); font-size:11px; font-weight:700;">Tanggal kunjungan wajib diisi!</span>`;
                return;
            }
            if (!bookingTime) {
                feedback.innerHTML = `<span style="color:var(--danger); font-size:11px; font-weight:700;">Jam kunjungan wajib diisi!</span>`;
                return;
            }
            
            let plate = "";
            let brand = "";
            let model = "";
            let year = "";
            
            if (vehicleId === "") {
                plate = plateInput.value.trim();
                brand = brandInput.value.trim();
                model = modelInput.value.trim();
                year = yearInput.value.trim();
                
                if (!plate || !brand || !model) {
                    feedback.innerHTML = `<span style="color:var(--danger); font-size:11px; font-weight:700;">Harap isi Plat, Brand, dan Model untuk kendaraan baru!</span>`;
                    return;
                }
            }
            
            confirmBtn.disabled = true;
            cancelBtn.disabled = true;
            confirmBtn.textContent = 'Memproses...';
            feedback.innerHTML = '';
            
            const payload = {
                service_type_ids: serviceTypeIds,
                booking_date: bookingDate,
                booking_time: bookingTime,
                service_description: notes,
                notes: notes
            };
            
            if (vehicleId !== "") {
                payload.vehicle_id = vehicleId;
            } else {
                payload.plate_number = plate;
                payload.brand = brand;
                payload.model = model;
                if (year) payload.year = year;
            }
            
            // Submit via AJAX POST to bookings.store
            fetch("{{ route('bookings.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(payload)
            })
            .then(res => {
                if (res.redirected) {
                    // Success! Follow redirect
                    feedback.innerHTML = `<span style="color:var(--ok); font-size:11px; font-weight:700;">Sukses! Mengalihkan...</span>`;
                    setTimeout(() => {
                        window.location.href = res.url;
                    }, 800);
                    return { success: true, processed: true };
                }
                return res.json();
            })
            .then(data => {
                if (data.processed) return;
                if (data.errors) {
                    confirmBtn.disabled = false;
                    cancelBtn.disabled = false;
                    confirmBtn.textContent = 'Konfirmasi Booking';
                    
                    const firstErrorKey = Object.keys(data.errors)[0];
                    const firstErrorMsg = data.errors[firstErrorKey][0];
                    feedback.innerHTML = `<span style="color:var(--danger); font-size:11px; font-weight:700;">${escapeHtml(firstErrorMsg)}</span>`;
                } else if (data.message) {
                    confirmBtn.disabled = false;
                    cancelBtn.disabled = false;
                    confirmBtn.textContent = 'Konfirmasi Booking';
                    feedback.innerHTML = `<span style="color:var(--danger); font-size:11px; font-weight:700;">${escapeHtml(data.message)}</span>`;
                } else {
                    // Fallback redirect to /bookings
                    feedback.innerHTML = `<span style="color:var(--ok); font-size:11px; font-weight:700;">Sukses! Memuat riwayat booking...</span>`;
                    setTimeout(() => {
                        window.location.href = "{{ route('bookings.index') }}";
                    }, 800);
                }
            })
            .catch(err => {
                console.error('Error submitting booking:', err);
                confirmBtn.disabled = false;
                cancelBtn.disabled = false;
                confirmBtn.textContent = 'Konfirmasi Booking';
                feedback.innerHTML = `<span style="color:var(--danger); font-size:11px; font-weight:700;">Koneksi belum merespons.</span>`;
            });
        });
        
        cancelBtn.addEventListener('click', () => {
            card.style.transition = 'all 0.22s ease';
            card.style.opacity = '0';
            card.style.transform = 'scale(0.9)';
            setTimeout(() => card.remove(), 220);
        });
        
        chatBox.appendChild(card);
        scrollToBottom();
    }

    function createSalaryReportCard(action) {
        const chatBox = document.getElementById('chatBox');
        const card = document.createElement('div');
        card.className = 'zoru-action-card';
        card.style.maxWidth = '400px';
        
        const now = new Date();
        const currentMonth = now.getMonth() + 1;
        const currentYear = now.getFullYear();
        
        card.innerHTML = `
            <h3> Laporan Gaji & Komisi</h3>
            
            <div class="field" style="margin-bottom:8px;">
                <label style="font-size:11px; font-weight:700;">Pilih Bulan</label>
                <select class="zoru-input" id="salaryMonth" style="width:100%; padding:8px 10px;">
                    <option value="1">Januari</option>
                    <option value="2">Februari</option>
                    <option value="3">Maret</option>
                    <option value="4">April</option>
                    <option value="5">Mei</option>
                    <option value="6">Juni</option>
                    <option value="7">Juli</option>
                    <option value="8">Agustus</option>
                    <option value="9">September</option>
                    <option value="10">Oktober</option>
                    <option value="11">November</option>
                    <option value="12">Desember</option>
                </select>
            </div>
            
            <div class="field" style="margin-bottom:12px;">
                <label style="font-size:11px; font-weight:700;">Pilih Tahun</label>
                <select class="zoru-input" id="salaryYear" style="width:100%; padding:8px 10px;">
                    <option value="2025">2025</option>
                    <option value="2026">2026</option>
                    <option value="2027">2027</option>
                </select>
            </div>
            
            <div style="background:var(--bg-soft); border:1px solid var(--line); border-radius:10px; padding:12px; margin-bottom:14px; text-align:center;">
                <div style="font-size:12px; color:var(--muted); font-weight:700; margin-bottom:4px;">Total Gaji & Komisi Anda:</div>
                <div id="salaryValueDisplay" style="font-size:22px; color:var(--primary); font-weight:900;">Rp 0</div>
            </div>
            
            <button type="button" class="button" id="salaryCloseBtn" style="margin-top:0; width:100%;">Tutup</button>
        `;
        
        const monthSelect = card.querySelector('#salaryMonth');
        const yearSelect = card.querySelector('#salaryYear');
        const valueDisplay = card.querySelector('#salaryValueDisplay');
        const closeBtn = card.querySelector('#salaryCloseBtn');
        
        monthSelect.value = currentMonth;
        yearSelect.value = currentYear;
        
        function fetchSalary() {
            valueDisplay.textContent = 'Memuat...';
            fetch("{{ route('reports.analytics.zoru') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    action: 'get_salary_report',
                    month: monthSelect.value,
                    year: yearSelect.value
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    valueDisplay.textContent = formatRupiah(data.earned);
                } else {
                    valueDisplay.textContent = 'Gagal memuat';
                }
            })
            .catch(err => {
                console.error(err);
                valueDisplay.textContent = 'Koneksi error';
            });
        }
        
        monthSelect.addEventListener('change', fetchSalary);
        yearSelect.addEventListener('change', fetchSalary);
        
        // Initial load
        fetchSalary();
        
        closeBtn.addEventListener('click', () => {
            card.style.transition = 'all 0.22s ease';
            card.style.opacity = '0';
            card.style.transform = 'scale(0.9)';
            setTimeout(() => card.remove(), 220);
        });
        
        chatBox.appendChild(card);
        scrollToBottom();
    }

    function createAccBookingFormCard(action) {
        const chatBox = document.getElementById('chatBox');
        const card = document.createElement('div');
        card.className = 'zoru-action-card';
        card.style.maxWidth = '450px';
        
        const bookings = action.bookings || [];
        // Filter: only scheduled and not taken yet
        const availableBookings = bookings.filter(b => b.status === 'scheduled' && b.mechanic_id === null);
        
        let contentHtml = '';
        
        if (bookings.length === 0) {
            contentHtml = `<div style="text-align:center; color:var(--muted); font-weight:700; padding:15px 0;">Hari ini belum ada yang booking</div>`;
        } else if (availableBookings.length === 0) {
            contentHtml = `<div style="text-align:center; color:var(--warning); font-weight:700; padding:15px 0;">Bookingan sudah habis</div>`;
        } else {
            contentHtml = availableBookings.map(b => `
                <div style="border:1px solid var(--line); border-radius:10px; padding:12px; margin-bottom:10px; background:var(--bg-soft);" id="booking_item_${b.id}">
                     <div style="font-weight:800; font-size:13px; color:var(--ink); margin-bottom:4px;">${escapeHtml(b.customer_name)}</div>
                     <div style="font-size:11.5px; color:var(--muted); margin-bottom:6px;">
                          ${escapeHtml(b.brand)} ${escapeHtml(b.model)} (${escapeHtml(b.plate_number)})
                     </div>
                     <div style="font-size:11px; margin-bottom:10px;">
                          <strong>Servis:</strong> ${escapeHtml(b.services.join(', '))}
                     </div>
                     <button type="button" class="button" style="margin-top:0; width:100%; padding:6px 12px; font-size:12px;" onclick="acceptBookingInChat(${b.id}, this)">
                         Terima Booking
                     </button>
                </div>
            `).join('');
        }
        
        card.innerHTML = `
            <h3> ACC Booking Servis Hari Ini</h3>
            <div style="max-height:280px; overflow:hidden; margin-bottom:12px; padding-right:4px;">
                ${contentHtml}
            </div>
            <button type="button" class="button secondary" id="accBookingCloseBtn" style="margin-top:0; width:100%;">Tutup</button>
            <div class="feedback-msg" style="margin-top:8px;"></div>
        `;
        
        const closeBtn = card.querySelector('#accBookingCloseBtn');
        closeBtn.addEventListener('click', () => {
            card.style.transition = 'all 0.22s ease';
            card.style.opacity = '0';
            card.style.transform = 'scale(0.9)';
            setTimeout(() => card.remove(), 220);
        });
        
        chatBox.appendChild(card);
        scrollToBottom();
    }
    
    function acceptBookingInChat(bookingId, btn) {
        const card = btn.closest('.zoru-action-card');
        const feedback = card.querySelector('.feedback-msg');
        btn.disabled = true;
        btn.textContent = 'Memproses...';
        
        fetch(`/bookings/${bookingId}/accept`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-HTTP-Method-Override': 'PATCH'
            }
        })
        .then(res => {
            feedback.innerHTML = `<span style="color:var(--ok); font-size:11px; font-weight:700;">Sukses menerima booking! Mengalihkan...</span>`;
            setTimeout(() => {
                window.location.href = "{{ route('bookings.index') }}";
            }, 1000);
        })
        .catch(err => {
            console.error(err);
            btn.disabled = false;
            btn.textContent = 'Terima Booking';
            feedback.innerHTML = `<span style="color:var(--danger); font-size:11px; font-weight:700;">Gagal mengambil booking.</span>`;
        });
    }

    function formatRupiah(value) {
        return 'Rp ' + Number(value || 0).toLocaleString('id-ID');
    }
    
    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, char => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        }[char]));
    }

    function fetchAndOpenAccBookingForm() {
        const chatBox = document.getElementById('chatBox');
        
        fetch("{{ route('reports.analytics.zoru') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ prompt: 'acc booking' })
        })
        .then(res => res.json())
        .then(data => {
            if (data.action && data.action.type === 'acc_booking_form') {
                createAccBookingFormCard(data.action);
            } else {
                const errDiv = document.createElement('div');
                errDiv.className = "zoru-bubble zoru-bubble-ai";
                errDiv.textContent = 'Gagal memuat daftar booking hari ini.';
                chatBox.appendChild(errDiv);
                scrollToBottom();
            }
        })
        .catch(err => {
            console.error(err);
            const errDiv = document.createElement('div');
            errDiv.className = "zoru-bubble zoru-bubble-ai";
            errDiv.textContent = 'Koneksi belum merespons saat memuat booking.';
            chatBox.appendChild(errDiv);
            scrollToBottom();
        });
    }
</script>
@endsection












