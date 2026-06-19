<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo $__env->yieldContent('title', 'Dashboard'); ?> | Milky Garage</title>
    <link rel="icon" type="image/webp" href="<?php echo e(asset('img/Milky Garage Assets/Logo Area.webp')); ?>">
    <link rel="shortcut icon" type="image/webp" href="<?php echo e(asset('img/Milky Garage Assets/Logo Area.webp')); ?>">
    <link rel="apple-touch-icon" href="<?php echo e(asset('img/Milky Garage Assets/Logo Area.webp')); ?>">
    <script>
        const userId = '<?php echo e(auth()->check() ? auth()->id() : 'guest'); ?>';
        const savedTheme = localStorage.getItem('theme_' + userId);
        if (savedTheme && savedTheme !== 'light') {
            document.documentElement.dataset.theme = savedTheme;
        } else {
            document.documentElement.dataset.theme = 'dark';
        }
    </script>
    <style>
        :root {
            color-scheme: dark;
            --bg: #0e1416;
            --bg-soft: #111a1d;
            --panel: rgba(19, 27, 31, 0.88);
            --panel-solid: #141d21;
            --ink: #edf7f5;
            --muted: #a6b3b7;
            --line: rgba(226, 241, 237, 0.13);
            --primary: #20bca8;
            --primary-dark: #5eead4;
            --primary-soft: rgba(32, 188, 168, 0.15);
            --accent: #f59e0b;
            --accent-soft: rgba(245, 158, 11, 0.14);
            --danger: #f87171;
            --ok: #4ade80;
            --info: #60a5fa;
            --shadow: 0 22px 55px rgba(0, 0, 0, 0.35);
            --shadow-soft: 0 10px 28px rgba(0, 0, 0, 0.25);
            --sidebar-bg: rgba(15, 23, 26, 0.85);
            --input-bg: #10181b;
            --table-head: rgba(32, 188, 168, 0.11);
            --focus: rgba(94, 234, 212, 0.3);
            --vh-fixed: 133.333vh; /* 100vh / 0.75 zoom factor to fill exactly 100% of physical viewport height */
        }

        html[data-theme="dark-planet"] {
            color-scheme: dark;
            --bg: #090614;
            --bg-soft: #100b1f;
            --panel: rgba(18, 11, 38, 0.88);
            --panel-solid: #150d2e;
            --ink: #f0f0f5;
            --muted: #9c92bd;
            --line: rgba(175, 160, 230, 0.18);
            --primary: #8b5cf6;
            --primary-dark: #a78bfa;
            --primary-soft: rgba(139, 92, 246, 0.15);
            --accent: #ec4899;
            --accent-soft: rgba(236, 72, 153, 0.14);
            --danger: #f43f5e;
            --ok: #10b981;
            --info: #3b82f6;
            --shadow: 0 22px 55px rgba(0, 0, 0, 0.45);
            --shadow-soft: 0 10px 28px rgba(0, 0, 0, 0.3);
            --sidebar-bg: rgba(9, 6, 20, 0.85);
            --input-bg: #0c081c;
            --table-head: rgba(139, 92, 246, 0.11);
            --focus: rgba(167, 139, 250, 0.3);
        }

        html[data-theme="horror"] {
            color-scheme: dark;
            --bg: #070707;
            --bg-soft: #0d0a0b;
            --panel: rgba(16, 13, 14, 0.9);
            --panel-solid: #121011;
            --ink: #eee7e4;
            --muted: #9a8a86;
            --line: rgba(112, 63, 61, 0.24);
            --primary: #7f1d1d;
            --primary-dark: #b45353;
            --primary-soft: rgba(127, 29, 29, 0.18);
            --accent: #8b6f47;
            --accent-soft: rgba(139, 111, 71, 0.15);
            --danger: #991b1b;
            --ok: #3f7f5f;
            --info: #4f647e;
            --shadow: 0 24px 60px rgba(0, 0, 0, 0.62);
            --shadow-soft: 0 12px 32px rgba(0, 0, 0, 0.42);
            --sidebar-bg: rgba(9, 8, 8, 0.9);
            --input-bg: #0b090a;
            --table-head: rgba(127, 29, 29, 0.12);
            --focus: rgba(180, 83, 83, 0.22);
        }

        html[data-theme="modern"] {
            color-scheme: dark;
            --bg: #121212;
            --bg-soft: #1a1a1a;
            --panel: rgba(30, 30, 30, 0.88);
            --panel-solid: #242424;
            --ink: #fdfdfd;
            --muted: #a1a1aa;
            --line: rgba(255, 255, 255, 0.1);
            --primary: #06b6d4;
            --primary-dark: #22d3ee;
            --primary-soft: rgba(6, 182, 212, 0.15);
            --accent: #facc15;
            --accent-soft: rgba(250, 204, 21, 0.14);
            --danger: #ef4444;
            --ok: #22c55e;
            --info: #3b82f6;
            --shadow: 0 22px 55px rgba(0, 0, 0, 0.3);
            --shadow-soft: 0 10px 28px rgba(0, 0, 0, 0.2);
            --sidebar-bg: rgba(18, 18, 18, 0.85);
            --input-bg: #1f1f1f;
            --table-head: rgba(6, 182, 212, 0.11);
            --focus: rgba(34, 211, 238, 0.3);
        }

        
        * {
            box-sizing: border-box;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        html {
            scroll-behavior: smooth;
            zoom: 75%;
            text-rendering: optimizeLegibility;
        }
        img, svg {
            image-rendering: -webkit-optimize-contrast;
            image-rendering: crisp-edges;
        }
        svg {
            vector-effect: non-scaling-stroke;
        }
        .metric-card, .nav a, .logout-button, .button, .panel, .quick-card, .story-row, .slot-option, input, select, textarea {
            transform: translateZ(0);
            backface-visibility: hidden;
            -webkit-backface-visibility: hidden;
        }
        body {
            margin: 0;
            background: var(--bg);
            color: var(--ink);
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            letter-spacing: 0;
            min-height: var(--vh-fixed);
            transition: background 0.2s ease, color 0.2s ease;
        }
        @keyframes modalOverlayIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        .modal-overlay {
            animation: modalOverlayIn 0.25s ease-out;
        }
        .modal-card {
            animation: modalOverlayIn 0.18s ease-out;
        }
        .button:active { transform: scale(0.98); }
        .metric-card:hover { transform: translateY(-2px); transition: transform 0.2s ease, box-shadow 0.2s ease; }
        body::before {
            content: "";
            position: fixed;
            inset: 0;
            pointer-events: none;
            background:
                linear-gradient(135deg, color-mix(in srgb, var(--primary) 12%, transparent), transparent 34%),
                linear-gradient(315deg, color-mix(in srgb, var(--accent) 12%, transparent), transparent 38%);
            opacity: 0.85;
            z-index: -1;
        }
        html[data-theme] body::before { opacity: 0.45; }
        html[data-theme="horror"] body::before {
            background:
                radial-gradient(circle at 18% 0%, rgba(127, 29, 29, 0.13), transparent 30%),
                linear-gradient(145deg, rgba(52, 35, 35, 0.22), transparent 42%),
                linear-gradient(315deg, rgba(139, 111, 71, 0.09), transparent 36%);
            opacity: 0.48;
        }
        a { color: inherit; text-decoration: none; }
        button, input, select, textarea { font: inherit; letter-spacing: 0; }
        table { border-collapse: collapse; width: 100%; }
        ::selection { background: var(--focus); }

        .shell { min-height: var(--vh-fixed); display: grid; grid-template-columns: 260px minmax(0, 1fr); }
        .sidebar {
            background: var(--sidebar-bg);
            border-right: 1px solid var(--line);
            padding: 22px 18px;
            position: sticky;
            top: 0;
            height: var(--vh-fixed);
            backdrop-filter: blur(18px);
            box-shadow: var(--shadow-soft);
            display: flex;
            flex-direction: column;
        }

        .brand {
            display: flex;
            gap: 11px;
            align-items: center;
            font-weight: 850;
            font-size: 19px;
            line-height: 1.2;
        }
        .brand-mark {
            display: inline-grid;
            place-items: center;
            width: 42px;
            height: 42px;
            background: transparent;
        }
        .brand small {
            display: block;
            margin-top: 3px;
            color: var(--muted);
            font-size: 12px;
            font-weight: 650;
        }
        .role-badge {
            display: inline-flex;
            align-items: center;
            margin-top: 10px;
            padding: 6px 10px;
            border: 1px solid color-mix(in srgb, var(--primary) 30%, transparent);
            color: var(--primary-dark);
            background: var(--primary-soft);
            border-radius: 999px;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
        }
        .zeropay-card {
            background: var(--bg);
            border-radius: 10px;
            padding: 12px;
            border: 1px solid var(--line);
            margin-bottom: 8px;
            display: grid;
            grid-template-columns: minmax(0, 1fr) 34px;
            align-items: center;
            gap: 10px;
        }
        .zeropay-copy {
            min-width: 0;
        }
        .zeropay-label {
            font-size: 11px;
            font-weight: 600;
            color: var(--muted);
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        .zeropay-balance {
            display: block;
            max-width: 100%;
            color: var(--primary);
            font-size: clamp(10px, 0.72vw, 13px);
            font-weight: 850;
            line-height: 1.18;
            white-space: nowrap;
            overflow: visible;
            text-overflow: clip;
            letter-spacing: -0.04em;
            font-variant-numeric: tabular-nums;
        }
        .zeropay-action {
            display: grid;
            place-items: center;
            width: 34px;
            height: 34px;
            border-radius: 8px;
            flex-shrink: 0;
            transition: 0.2s;
        }
        
        .ui-svg {
            width: 18px;
            height: 18px;
            display: block;
        }
        .nav a::before, .logout-button::before {
            display: none !important;
            content: none !important;
        }
        .nav-icon, .ui-icon-badge {
            width: 30px;
            height: 30px;
            border-radius: 10px;
            display: inline-grid;
            place-items: center;
            flex: 0 0 auto;
            color: #fff;
            border: 1px solid rgba(255,255,255,.13);
            background: linear-gradient(135deg, rgba(32,188,168,.95), rgba(96,165,250,.72));
            box-shadow: 0 10px 24px rgba(32,188,168,.16);
        }
        .nav-icon-dashboard, .nav-icon-home { background: linear-gradient(135deg, #20bca8, #38bdf8); }
        .nav-icon-booking { background: linear-gradient(135deg, #f59e0b, #fb7185); }
        .nav-icon-payment, .nav-icon-report { background: linear-gradient(135deg, #06b6d4, #2563eb); }
        .nav-icon-agent, .nav-icon-trend { background: linear-gradient(135deg, #8b5cf6, #06b6d4); }
        .nav-icon-history { background: linear-gradient(135deg, #64748b, #20bca8); }
        .nav-icon-service, .nav-icon-catalog { background: linear-gradient(135deg, #f97316, #eab308); }
        .nav-icon-login, .nav-icon-register { background: linear-gradient(135deg, #10b981, #14b8a6); }
        .nav .nav-icon {
            color: var(--muted);
            border-color: var(--line);
            background: transparent;
            box-shadow: none;
        }
        .nav a.active .nav-icon, .nav a:hover .nav-icon {
            color: var(--muted);
            border-color: var(--line);
            background: transparent;
            box-shadow: none;
        }
        .zeropay-action {
            border: 1px solid var(--line);
            background: color-mix(in srgb, var(--bg) 82%, transparent);
            color: #f6a51a;
            box-shadow: none;
        }
        .zeropay-action .ui-svg { width: 19px; height: 19px; filter: drop-shadow(0 0 8px rgba(246,165,26,.28)); }
        .zeropay-action:hover {
            transform: translateY(-1px);
            border-color: color-mix(in srgb, var(--primary) 34%, var(--line));
            background: color-mix(in srgb, var(--panel-solid) 92%, var(--primary-soft));
            box-shadow: 0 14px 26px rgba(0,0,0,.18);
        }
        .zeropay-modal-overlay {
            position: fixed;
            inset: 0;
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            padding: 18px;
            background: rgba(0,0,0,.68);
            backdrop-filter: blur(10px);
        }
        .zeropay-modal-overlay.is-open { display: flex; }
        .zeropay-modal-card {
            position: relative;
            width: min(480px, 100%);
            border: 1px solid color-mix(in srgb, var(--primary) 18%, var(--line));
            border-radius: 18px;
            padding: 0;
            background: color-mix(in srgb, var(--panel-solid) 96%, var(--bg));
            box-shadow: 0 30px 80px rgba(0,0,0,.48);
            overflow: hidden;
        }
        .zeropay-modal-close {
            position: absolute;
            top: 14px;
            right: 14px;
            width: 36px;
            height: 36px;
            display: grid;
            place-items: center;
            border-radius: 12px;
            border: 1px solid var(--line);
            background: color-mix(in srgb, var(--bg) 78%, transparent);
            color: var(--muted);
            cursor: pointer;
        }
        .zeropay-modal-close:hover { color: var(--ink); border-color: color-mix(in srgb, var(--primary) 35%, var(--line)); }
        .zeropay-modal-head {
            display: flex;
            gap: 14px;
            align-items: flex-start;
            margin: 0;
            padding: 24px 26px 20px;
            padding-right: 58px;
            border-bottom: 1px solid var(--line);
        }
        .zeropay-modal-icon {
            width: 44px;
            height: 44px;
            border-radius: 13px;
            background: color-mix(in srgb, var(--bg) 82%, transparent);
            border: 1px solid var(--line);
            color: #f6a51a;
            box-shadow: none;
        }
        .zeropay-modal-icon .ui-svg { width: 22px; height: 22px; filter: drop-shadow(0 0 10px rgba(246,165,26,.24)); }
        .zeropay-modal-eyebrow { margin: 0 0 4px; color: var(--primary-dark); font-size: 11px; font-weight: 900; text-transform: uppercase; letter-spacing: .12em; }
        .zeropay-modal-title { margin: 0 0 6px; font-size: 24px; line-height: 1.1; }
        .zeropay-modal-desc { margin: 0; color: var(--muted); line-height: 1.5; max-width: 320px; }
        .zeropay-modal-actions { display: grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap: 12px; padding: 20px 26px 14px; }
        .zeropay-action-card {
            min-height: 86px;
            border-radius: 14px;
            border: 1px solid var(--line);
            background: color-mix(in srgb, var(--bg) 70%, transparent);
            color: var(--ink);
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px;
            font-weight: 850;
            transition: border-color .15s ease, background .15s ease, transform .15s ease;
        }
        .zeropay-action-card:hover {
            transform: translateY(-1px);
            border-color: color-mix(in srgb, var(--primary) 34%, var(--line));
            background: color-mix(in srgb, var(--primary-soft) 34%, var(--panel-solid));
        }
        .zeropay-action-card .ui-icon-badge {
            width: 36px;
            height: 36px;
            border-radius: 11px;
            border: 1px solid var(--line);
            background: transparent;
            color: var(--primary-dark);
            box-shadow: none;
        }
        .zeropay-action-card.secondary { background: color-mix(in srgb, var(--bg) 70%, transparent); }
        .zeropay-action-card small { display: block; color: var(--muted); font-weight: 650; margin-top: 3px; }
        .zeropay-modal-cancel {
            width: 100%;
            min-height: 44px;
            border: 0;
            background: transparent;
            color: var(--muted);
            cursor: pointer;
            border-top: 1px solid var(--line);
            border-radius: 0;
        }
        .zeropay-modal-cancel:hover { color: var(--ink); background: color-mix(in srgb, var(--muted) 10%, transparent); }
        .nav { display: grid; gap: 7px; margin-top: 26px; }
        .nav a, .logout-button {
            display: flex;
            align-items: center;
            gap: 9px;
            width: 100%;
            padding: 11px 12px;
            border-radius: 8px;
            color: var(--ink);
            border: 1px solid transparent;
            background: transparent;
            text-align: left;
            cursor: pointer;
            transition: transform 0.15s ease, background 0.15s ease, border-color 0.15s ease;
        }
        .nav a::before, .logout-button::before {
            content: "";
            width: 7px;
            height: 7px;
            border-radius: 999px;
            background: color-mix(in srgb, var(--muted) 55%, transparent);
            flex: 0 0 auto;
        }
        .nav a.active, .nav a:hover, .logout-button:hover {
            border-color: color-mix(in srgb, var(--primary) 28%, transparent);
            background: var(--primary-soft);
            color: var(--primary-dark);
            transform: translateX(2px);
        }
        .nav a.active::before, .nav a:hover::before, .logout-button:hover::before {
            background: var(--primary);
        }
        .theme-toggle {
            width: 100%;
            margin-top: 14px;
            justify-content: center;
            border-color: var(--line);
        }
        .main { padding: 28px; min-width: 0; }
        .topbar {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 22px;
        }
        .eyebrow {
            color: var(--primary-dark);
            font-size: 13px;
            margin: 0 0 5px;
            font-weight: 800;
            text-transform: uppercase;
        }
        h1 { margin: 0; font-size: 31px; line-height: 1.12; }
        h2 { margin: 0 0 14px; font-size: 18px; letter-spacing: 0; }
        h3 { margin: 0 0 10px; font-size: 15px; }
        .muted { color: var(--muted); }
        .grid { display: grid; gap: 16px; }
        .grid-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .grid-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .grid-4 { grid-template-columns: repeat(4, minmax(0, 1fr)); }
        .panel {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: 8px;
            padding: 19px;
            box-shadow: var(--shadow-soft);
            backdrop-filter: blur(12px);
        }
        .panel.flush { padding: 0; overflow: hidden; }
        .panel-header {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: flex-start;
            margin-bottom: 14px;
        }
        .panel-title { margin: 0; }
        .metric-card {
            min-height: 134px;
            display: grid;
            align-content: space-between;
            position: relative;
            overflow: hidden;
        }
        .metric-card::after {
            content: "";
            position: absolute;
            right: 16px;
            bottom: 16px;
            width: 48px;
            height: 6px;
            border-radius: 999px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
            opacity: 0.85;
        }
        .metric-label {
            color: var(--muted);
            font-size: 13px;
            font-weight: 760;
        }
        .metric {
            font-size: 29px;
            font-weight: 850;
            margin-top: 6px;
            color: var(--ink);
        }
        .metric-note { color: var(--muted); font-size: 12px; margin-top: 8px; }
        .toolbar { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
        .button {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            min-height: 38px;
            padding: 9px 13px;
            border-radius: 8px;
            border: 1px solid var(--primary);
            background: var(--primary);
            color: #fff;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 10px 18px color-mix(in srgb, var(--primary) 22%, transparent);
            transition: transform 0.15s ease, box-shadow 0.15s ease, background 0.15s ease;
        }
        .button:hover { transform: translateY(-1px); box-shadow: var(--shadow-soft); }
        .button:focus-visible, input:focus, select:focus, textarea:focus {
            outline: 3px solid var(--focus);
            outline-offset: 1px;
        }
        .button.secondary {
            background: var(--panel-solid);
            color: var(--primary-dark);
            border-color: color-mix(in srgb, var(--primary) 30%, var(--line));
            box-shadow: none;
        }
        .button.warn { background: var(--accent); border-color: var(--accent); }
        .button.danger { background: var(--danger); border-color: var(--danger); }
        .button.small { min-height: 32px; padding: 6px 10px; font-size: 13px; }
        .alert {
            border-radius: 8px;
            padding: 12px 14px;
            margin-bottom: 14px;
            border: 1px solid color-mix(in srgb, var(--ok) 28%, transparent);
            background: color-mix(in srgb, var(--ok) 12%, var(--panel-solid));
            color: var(--ink);
            text-align: center;
        }
        .alert.error {
            border-color: color-mix(in srgb, var(--danger) 35%, transparent);
            background: color-mix(in srgb, var(--danger) 12%, var(--panel-solid));
            color: var(--ink);
            text-align: left;
        }
        .form-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; }
        .field { display: grid; gap: 6px; }
        .field.full { grid-column: 1 / -1; }
        label { font-size: 13px; font-weight: 750; color: var(--ink); }
        input, select, textarea {
            width: 100%;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: var(--input-bg);
            padding: 10px 11px;
            color: var(--ink);
            transition: border-color 0.15s ease, background 0.15s ease, outline 0.15s ease;
        }
        input::placeholder, textarea::placeholder { color: color-mix(in srgb, var(--muted) 75%, transparent); }
        textarea { min-height: 100px; resize: vertical; }
        .error-text { color: var(--danger); font-size: 12px; }
        .table-wrap {
            overflow-x: auto;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: var(--panel-solid);
        }
        th, td { padding: 11px 12px; border-bottom: 1px solid var(--line); text-align: left; vertical-align: top; font-size: 14px; }
        th { background: var(--table-head); font-size: 12px; text-transform: uppercase; color: var(--muted); }
        tbody tr { transition: background 0.15s ease; }
        tbody tr:hover { background: color-mix(in srgb, var(--primary) 6%, transparent); }
        tr:last-child td { border-bottom: 0; }
        .status {
            display: inline-flex;
            padding: 4px 8px;
            border-radius: 999px;
            background: color-mix(in srgb, var(--muted) 14%, transparent);
            color: var(--ink);
            font-size: 12px;
            font-weight: 800;
        }
        .status.paid, .status.finished, .status.approved { background: color-mix(in srgb, var(--ok) 18%, transparent); color: var(--ok); }
        .status.pending, .status.waiting_approval, .status.scheduled { background: color-mix(in srgb, var(--accent) 18%, transparent); color: var(--accent); }
        .status.checked_in, .status.in_service { background: color-mix(in srgb, var(--info) 18%, transparent); color: var(--info); }
        .status.canceled { background: color-mix(in srgb, var(--danger) 18%, transparent); color: var(--danger); }
        .stack { display: grid; gap: 14px; }
        .split { display: flex; justify-content: space-between; gap: 16px; align-items: center; }
        .line-item { display: grid; grid-template-columns: minmax(0, 1fr) 110px 130px; gap: 12px; align-items: center; }
        .pagination { margin-top: 14px; }
        .bar-row { display: grid; grid-template-columns: 130px minmax(160px, 1fr) 60px; gap: 10px; align-items: center; margin: 10px 0; }
        .bar { height: 18px; border-radius: 999px; background: color-mix(in srgb, var(--primary) 12%, var(--panel-solid)); overflow: hidden; }
        .bar span { display: block; height: 100%; background: linear-gradient(90deg, var(--primary), var(--accent)); }
        .invoice {
            max-width: 850px;
            margin: 0 auto;
            background: var(--panel-solid);
            border: 1px solid var(--line);
            border-radius: 8px;
            padding: 28px;
            box-shadow: var(--shadow);
        }
        .page-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
        }
        .quick-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
        }
        .quick-card {
            display: grid;
            gap: 8px;
            padding: 15px;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: var(--panel-solid);
            min-height: 116px;
        }
        .quick-card strong { font-size: 15px; }
        .quick-card span { color: var(--muted); font-size: 13px; line-height: 1.45; }
        .story-list { display: grid; gap: 9px; }
        .story-row {
            display: grid;
            grid-template-columns: 70px minmax(0, 1fr) auto;
            gap: 10px;
            align-items: center;
            padding: 10px;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: color-mix(in srgb, var(--panel-solid) 86%, transparent);
        }
        .story-row strong { font-size: 13px; }
        .story-row span { color: var(--muted); font-size: 13px; }
        .timeline {
            display: grid;
            gap: 12px;
        }
        .timeline-item {
            display: grid;
            grid-template-columns: 112px minmax(0, 1fr);
            gap: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--line);
        }
        .timeline-item:last-child { border-bottom: 0; padding-bottom: 0; }
        .slot-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 8px;
        }
        .slot-option {
            border: 1px solid var(--line);
            border-radius: 8px;
            background: var(--panel-solid);
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }
        .slot-option input { width: auto; }
        .slot-option.is-taken {
            opacity: 0.5;
            cursor: not-allowed;
            background: color-mix(in srgb, var(--danger) 8%, var(--panel-solid));
        }
        .hide { display: none !important; }
        .button:disabled {
            opacity: 0.55;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        @media (max-width: 980px) {
            .shell { grid-template-columns: 1fr; }
            .sidebar { position: static; height: auto; border-right: 0; border-bottom: 1px solid var(--line); }
            .nav { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .grid-4, .grid-3, .grid-2, .form-grid, .quick-grid { grid-template-columns: 1fr; }
            .main { padding: 18px; }
            .topbar { display: grid; }
            .line-item { grid-template-columns: 1fr; }
            .timeline-item { grid-template-columns: 1fr; }
            .slot-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }

        @media print {
            .sidebar, .topbar .toolbar, .no-print { display: none !important; }
            .shell { display: block; }
            .main { padding: 0; }
            body { background: #fff; }
            body::before { display: none; }
        }
    </style>
</head>

<body>
    <?php echo $__env->make('components.modal-dialog', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


    <div class="shell">
        <aside class="sidebar">
            <div style="padding: 24px 24px 16px;">
                <?php if(auth()->guard()->check()): ?>
                    <div style="display:flex; align-items:flex-start; gap:12px; margin-bottom:16px;">
                        <img src="<?php echo e(asset('img/' . auth()->user()->avatarPath())); ?>" alt="Avatar" style="width:48px; height:48px; border-radius:12px; object-fit:cover; border:2px solid var(--primary-soft); flex-shrink:0;">
                        <div style="flex-grow:1; min-width:0;">
                            <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:6px;">
                                <div style="font-weight:800; font-size:18px; color:var(--ink); line-height:1.2; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="<?php echo e(ucwords(auth()->user()->username)); ?>"><?php echo e(ucwords(auth()->user()->username)); ?></div>
                            </div>
                            
                            <?php if(auth()->user()->isRole('owner')): ?>
                                <div class="role-badge" style="margin-top:0; font-size:10px; padding:4px 8px; display:inline-flex;"><?php echo e(auth()->user()->roleLabel()); ?></div>
                            <?php else: ?>
                                <div class="role-badge" style="margin-top:0; font-size:11px;"><?php echo e(auth()->user()->roleLabel()); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="zeropay-card">
                        <div class="zeropay-copy">
                            <div class="zeropay-label">ZeroPay</div>
                            <div id="sidebarZeroPayBalance" class="zeropay-balance" title="Rp <?php echo e(number_format(auth()->user()->balance, 0, ',', '.')); ?>">Rp <?php echo e(number_format(auth()->user()->balance, 0, ',', '.')); ?></div>
                        </div>
                        <a href="#" class="zeropay-action" data-zeropay-modal-open title="Kelola ZeroPay"><?php echo $__env->make('components.ui-icon', ['name' => 'bolt', 'class' => 'ui-svg'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></a>
                    </div>


                <?php else: ?>
                    <div class="brand">
                        <span class="brand-mark">
                            <img src="<?php echo e(asset('img/Milky Garage Assets/Logo Area.webp')); ?>" alt="Logo" style="width:100%; height:100%; object-fit:contain;">
                        </span>
                        <span>Milky Garage</span>
                    </div>
                <?php endif; ?>
            </div>
            <?php if(auth()->guard()->check()): ?>
                <nav class="nav">
                    <a class="<?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>" href="<?php echo e(route('dashboard')); ?>"><span class="nav-icon nav-icon-dashboard"><?php echo $__env->make('components.ui-icon', ['name' => 'dashboard', 'class' => 'ui-svg'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></span><span>Dashboard</span></a>
                    <?php if(auth()->user()->isRole('mechanic')): ?>
                        <a class="<?php echo e(request()->routeIs('bookings.*') ? 'active' : ''); ?>" href="<?php echo e(route('bookings.index')); ?>"><span class="nav-icon nav-icon-booking"><?php echo $__env->make('components.ui-icon', ['name' => 'booking', 'class' => 'ui-svg'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></span><span>ACC Booking</span></a>
                        <a class="<?php echo e(request()->routeIs('service-orders.*') ? 'active' : ''); ?>" href="<?php echo e(route('service-orders.index')); ?>"><span class="nav-icon nav-icon-service"><?php echo $__env->make('components.ui-icon', ['name' => 'service', 'class' => 'ui-svg'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></span><span>Laporan Gaji</span></a>
                        <a class="<?php echo e(request()->routeIs('reports.analytics') ? 'active' : ''); ?>" href="<?php echo e(route('reports.analytics')); ?>"><span class="nav-icon nav-icon-agent"><?php echo $__env->make('components.ui-icon', ['name' => 'agent', 'class' => 'ui-svg'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></span><span>Agent AI</span></a>
                        <a class="<?php echo e(request()->routeIs('account.activities') ? 'active' : ''); ?>" href="<?php echo e(route('account.activities')); ?>"><span class="nav-icon nav-icon-history"><?php echo $__env->make('components.ui-icon', ['name' => 'history', 'class' => 'ui-svg'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></span><span>Riwayat Akun</span></a>
                    <?php elseif(auth()->user()->isRole('cashier')): ?>
                        <a class="<?php echo e(request()->routeIs('payments.*') ? 'active' : ''); ?>" href="<?php echo e(route('payments.index')); ?>"><span class="nav-icon nav-icon-payment"><?php echo $__env->make('components.ui-icon', ['name' => 'payment', 'class' => 'ui-svg'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></span><span>Kelola Pembayaran</span></a>
                        <a class="<?php echo e(request()->routeIs('reports.cashier') ? 'active' : ''); ?>" href="<?php echo e(route('reports.cashier')); ?>"><span class="nav-icon nav-icon-report"><?php echo $__env->make('components.ui-icon', ['name' => 'report', 'class' => 'ui-svg'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></span><span>Laporan Kasir</span></a>
                        <a class="<?php echo e(request()->routeIs('service-orders.*') ? 'active' : ''); ?>" href="<?php echo e(route('service-orders.index')); ?>"><span class="nav-icon nav-icon-service"><?php echo $__env->make('components.ui-icon', ['name' => 'service', 'class' => 'ui-svg'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></span><span>Laporan Gaji</span></a>
                        <a class="<?php echo e(request()->routeIs('reports.analytics') ? 'active' : ''); ?>" href="<?php echo e(route('reports.analytics')); ?>"><span class="nav-icon nav-icon-agent"><?php echo $__env->make('components.ui-icon', ['name' => 'agent', 'class' => 'ui-svg'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></span><span>Agent AI</span></a>
                        <a class="<?php echo e(request()->routeIs('account.activities') ? 'active' : ''); ?>" href="<?php echo e(route('account.activities')); ?>"><span class="nav-icon nav-icon-history"><?php echo $__env->make('components.ui-icon', ['name' => 'history', 'class' => 'ui-svg'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></span><span>Riwayat Akun</span></a>
                    <?php elseif(auth()->user()->isRole('customer')): ?>
                        <a class="<?php echo e(request()->routeIs('bookings.*') ? 'active' : ''); ?>" href="<?php echo e(route('bookings.index')); ?>"><span class="nav-icon nav-icon-booking"><?php echo $__env->make('components.ui-icon', ['name' => 'booking', 'class' => 'ui-svg'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></span><span>Booking Service</span></a>
                        <a class="<?php echo e(request()->routeIs('payments.*') ? 'active' : ''); ?>" href="<?php echo e(route('payments.index')); ?>"><span class="nav-icon nav-icon-payment"><?php echo $__env->make('components.ui-icon', ['name' => 'payment', 'class' => 'ui-svg'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></span><span>Pembayaran</span></a>
                        <a class="<?php echo e(request()->routeIs('reports.analytics') ? 'active' : ''); ?>" href="<?php echo e(route('reports.analytics')); ?>"><span class="nav-icon nav-icon-agent"><?php echo $__env->make('components.ui-icon', ['name' => 'agent', 'class' => 'ui-svg'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></span><span>Agent AI</span></a>
                        <a class="<?php echo e(request()->routeIs('service-orders.*') ? 'active' : ''); ?>" href="<?php echo e(route('service-orders.index')); ?>"><span class="nav-icon nav-icon-service"><?php echo $__env->make('components.ui-icon', ['name' => 'service', 'class' => 'ui-svg'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></span><span>Riwayat Servis</span></a>
                        <a class="<?php echo e(request()->routeIs('account.activities') ? 'active' : ''); ?>" href="<?php echo e(route('account.activities')); ?>"><span class="nav-icon nav-icon-history"><?php echo $__env->make('components.ui-icon', ['name' => 'history', 'class' => 'ui-svg'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></span><span>Riwayat Akun</span></a>
                    <?php elseif(auth()->user()->isRole('owner')): ?>
                        <a class="<?php echo e(request()->routeIs('reports.finance') ? 'active' : ''); ?>" href="<?php echo e(route('reports.finance')); ?>"><span class="nav-icon nav-icon-report"><?php echo $__env->make('components.ui-icon', ['name' => 'report', 'class' => 'ui-svg'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></span><span>Keuangan Harian</span></a>
                        <a class="<?php echo e(request()->routeIs('catalog.*') ? 'active' : ''); ?>" href="<?php echo e(route('catalog.index')); ?>"><span class="nav-icon nav-icon-catalog"><?php echo $__env->make('components.ui-icon', ['name' => 'catalog', 'class' => 'ui-svg'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></span><span>Master Data</span></a>
                        <a class="<?php echo e(request()->routeIs('reports.analytics') ? 'active' : ''); ?>" href="<?php echo e(route('reports.analytics')); ?>"><span class="nav-icon nav-icon-trend"><?php echo $__env->make('components.ui-icon', ['name' => 'trend', 'class' => 'ui-svg'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></span><span>Trend Analyze</span></a>
                        <a class="<?php echo e(request()->routeIs('account.activities') ? 'active' : ''); ?>" href="<?php echo e(route('account.activities')); ?>"><span class="nav-icon nav-icon-history"><?php echo $__env->make('components.ui-icon', ['name' => 'history', 'class' => 'ui-svg'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></span><span>Riwayat Akun</span></a>
                    <?php endif; ?>
                </nav>
            <?php else: ?>
                <nav class="nav">
                    <a class="<?php echo e(request()->routeIs('login') ? 'active' : ''); ?>" href="<?php echo e(route('login')); ?>"><span class="nav-icon nav-icon-login"><?php echo $__env->make('components.ui-icon', ['name' => 'login', 'class' => 'ui-svg'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></span><span>Login</span></a>
                    <a class="<?php echo e(request()->routeIs('register') ? 'active' : ''); ?>" href="<?php echo e(route('register')); ?>"><span class="nav-icon nav-icon-register"><?php echo $__env->make('components.ui-icon', ['name' => 'register', 'class' => 'ui-svg'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></span><span>Registrasi</span></a>
                </nav>
            <?php endif; ?>
        </aside>

        <main class="main">
            <div class="topbar">
                <div>
                    <?php $eyebrow = trim($__env->yieldContent('eyebrow', 'Ai Agent - ZoruAi Artificial Intelligence')); ?>
                    <?php if($eyebrow !== ''): ?>
                        <p class="eyebrow"><?php echo e($eyebrow); ?></p>
                    <?php endif; ?>
                    <h1><?php echo $__env->yieldContent('title', config('app.name')); ?></h1>
                </div>
                <div class="page-actions" style="display:flex; gap:10px; align-items:center;">
                    <?php echo $__env->yieldContent('actions'); ?>
                    <a href="<?php echo e(route('account.edit')); ?>" title="Pengaturan" style="display:grid; place-items:center; width:40px; height:40px; border-radius:12px; background:var(--bg); border:1px solid var(--line); color:var(--muted); transition:0.2s;" onmouseover="this.style.color='var(--ink)'; this.style.borderColor='var(--primary)';" onmouseout="this.style.color='var(--muted)'; this.style.borderColor='var(--line)';">
                        <?php echo $__env->make('components.ui-icon', ['name' => 'settings', 'class' => 'ui-svg'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </a>
                    <form method="POST" action="<?php echo e(route('logout')); ?>" style="margin:0;">
                        <?php echo csrf_field(); ?>
                        <button type="submit" title="Logout" style="display:grid; place-items:center; width:40px; height:40px; border-radius:12px; background:var(--danger); border:1px solid var(--danger); color:#fff; cursor:pointer; transition:0.2s;" onmouseover="this.style.opacity='0.8';" onmouseout="this.style.opacity='1';">
                            <?php echo $__env->make('components.ui-icon', ['name' => 'logout', 'class' => 'ui-svg'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </button>
                    </form>
                </div>
            </div>



            <?php if(session('success')): ?>
                <div class="alert"><?php echo e(session('success')); ?></div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="alert error">
                    <div style="display:flex; align-items:center; gap:8px; font-weight:700; margin-bottom:8px;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                        Mohon maaf, terdapat kendala:
                    </div>
                    <ul style="margin:0; padding-left:26px; font-size:14px; line-height:1.5;">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>
    
    <script>
        // Auto-hide alerts after 3 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(a => {
                a.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                a.style.opacity = '0';
                a.style.transform = 'translateY(-10px)';
                setTimeout(() => a.remove(), 500);
            });
        }, 3000); // 3 seconds
    </script>
    
    <?php if(session('unclaimed_salary_prompt') && auth()->user()->isRole('cashier')): ?>
        <div id="salaryPromptModal" style="position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.5); display:flex; align-items:center; justify-content:center; z-index:9999;">
            <div style="background:var(--bg); padding:30px; border-radius:12px; max-width:400px; width:100%; text-align:center; box-shadow:0 10px 30px rgba(0,0,0,0.2);">
                <div style="font-size:40px; margin-bottom:16px; font-weight:900; color:var(--primary);">Rp</div>
                <h2 style="margin-bottom:8px;">Ups, Anda belum ambil gaji!</h2>
                <p class="muted" style="margin-bottom:20px;">Anda memiliki gaji/fee sebesar <strong>Rp <?php echo e(number_format(auth()->user()->unclaimed_salary, 0, ',', '.')); ?></strong> hari ini.</p>
                <form method="POST" action="<?php echo e(route('cashier.claim-salary')); ?>" style="display:flex; gap:10px; justify-content:center; margin-bottom:16px;">
                    <?php echo csrf_field(); ?>
                    <button type="submit" name="action" value="ambil" class="button">Ambil & Keluar</button>
                    <button type="submit" name="action" value="tarik" class="button secondary">Tarik ke Rekening</button>
                </form>
                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="force_logout" value="1">
                    <button type="submit" style="background:none; border:none; color:var(--muted); text-decoration:underline; cursor:pointer;">Abaikan & Tetap Keluar</button>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <?php if(auth()->user() && auth()->user()->isRole('customer')): ?>
        <?php
            $unreadNota = auth()->user()->messages()->where('is_read', false)->where('title', 'Nota Servis')->first();
        ?>
        <?php if($unreadNota): ?>
            <div id="customerNotaOverlay" style="position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.8); display:flex; align-items:center; justify-content:center; z-index:99999; cursor:pointer;" onclick="clickCustomerNota()">
                <div style="background:#fff; color:#000; padding:40px; border-radius:8px; max-width:400px; width:100%; text-align:center; box-shadow:0 10px 30px rgba(0,0,0,0.5); user-select:none; font-family:monospace;">
                    <h1 style="margin-bottom:10px; border-bottom:2px dashed #ccc; padding-bottom:10px;">NOTA SERVIS</h1>
                    <p>Terima kasih telah membayar tunai.</p>
                    <div style="margin:20px 0; font-size:28px; font-weight:900;">NOTA</div>
                    <p style="font-size:12px; color:#666;">Ketuk nota 3 kali untuk mencatat penerimaan nota dari kasir.</p>
                    <div id="customerClickCounter" style="margin-top:15px; font-weight:bold; font-size:18px; color:var(--primary);">Klik: 0/3</div>
                </div>
            </div>
            
            <script>
                let customerNotaClicks = 0;
                function clickCustomerNota() {
                    customerNotaClicks++;
                    document.getElementById('customerClickCounter').innerText = `Klik: ${customerNotaClicks}/3`;
                    
                    if (customerNotaClicks >= 3) {
                        document.getElementById('customerNotaOverlay').style.display = 'none';
                        window.location.href = "<?php echo e(route('messages.read', $unreadNota)); ?>";
                    }
                }
            </script>
        <?php endif; ?>
    <?php endif; ?>

    <?php if(auth()->guard()->check()): ?>
        <div id="zeroPayActionModal" class="zeropay-modal-overlay" aria-hidden="true">
            <section class="zeropay-modal-card" role="dialog" aria-modal="true" aria-labelledby="zeroPayModalTitle">
                <button type="button" class="zeropay-modal-close" data-zeropay-modal-close aria-label="Tutup">
                    <?php echo $__env->make('components.ui-icon', ['name' => 'close', 'class' => 'ui-svg'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </button>
                <div class="zeropay-modal-head">
                    <span class="ui-icon-badge zeropay-modal-icon">
                        <?php echo $__env->make('components.ui-icon', ['name' => 'bolt', 'class' => 'ui-svg'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </span>
                    <div>
                        <p class="zeropay-modal-eyebrow">ZeroPay</p>
                        <h2 id="zeroPayModalTitle" class="zeropay-modal-title">Kelola Saldo</h2>
                        <p class="zeropay-modal-desc">Pilih aksi saldo yang ingin Anda proses.</p>
                    </div>
                </div>
                <div class="zeropay-modal-actions">
                    <a href="<?php echo e(route('topup.index')); ?>" class="zeropay-action-card">
                        <span class="ui-icon-badge nav-icon-payment"><?php echo $__env->make('components.ui-icon', ['name' => 'topup', 'class' => 'ui-svg'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></span>
                        <span>Top-up Saldo<small>Tambah saldo ZeroPay</small></span>
                    </a>
                    <a href="<?php echo e(route('withdraw.index')); ?>" class="zeropay-action-card secondary">
                        <span class="ui-icon-badge nav-icon-report"><?php echo $__env->make('components.ui-icon', ['name' => 'withdraw', 'class' => 'ui-svg'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></span>
                        <span>Tarik Dana<small>Ajukan penarikan saldo</small></span>
                    </a>
                </div>
                <button type="button" class="zeropay-modal-cancel" data-zeropay-modal-close>Batal</button>
            </section>
        </div>
    <?php endif; ?>

    <div id="qrisOverlay" aria-hidden="true" style="position:fixed; inset:0; background:rgba(0,0,0,0.68); backdrop-filter:blur(10px); display:none; align-items:center; justify-content:center; z-index:99999; padding:18px;">
        <section style="width:min(380px, 100%); border:1px solid color-mix(in srgb, var(--primary) 22%, var(--line)); border-radius:18px; background:color-mix(in srgb, var(--panel-solid) 96%, var(--bg)); color:var(--ink); box-shadow:0 30px 80px rgba(0,0,0,.48); overflow:hidden;">
            <div style="display:flex; gap:14px; align-items:flex-start; padding:24px 26px 18px; border-bottom:1px solid var(--line);">
                <span class="ui-icon-badge zeropay-modal-icon" style="flex-shrink:0;"><?php echo $__env->make('components.ui-icon', ['name' => 'bolt', 'class' => 'ui-svg'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></span>
                <div>
                    <p style="margin:0 0 4px; color:var(--primary-dark); font-size:11px; font-weight:900; text-transform:uppercase; letter-spacing:.12em;">ZeroPay</p>
                    <h2 id="qrisTitle" style="margin:0 0 6px; font-size:23px; line-height:1.1;">Memproses Transaksi</h2>
                    <p id="qrisSubtitle" style="margin:0; color:var(--muted); line-height:1.5; font-size:14px;">ZeroPay sedang mengonfirmasi transaksi Anda.</p>
                </div>
            </div>
            <div style="padding:22px 26px 24px; display:grid; gap:16px;">
                <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:8px;" aria-hidden="true">
                    <span style="height:44px; border-radius:12px; background:color-mix(in srgb, var(--primary-soft) 48%, var(--bg)); border:1px solid var(--line);"></span>
                    <span style="height:44px; border-radius:12px; background:color-mix(in srgb, var(--accent) 24%, var(--bg)); border:1px solid var(--line);"></span>
                    <span style="height:44px; border-radius:12px; background:color-mix(in srgb, var(--primary-dark) 30%, var(--bg)); border:1px solid var(--line);"></span>
                </div>
                <p id="qrisStatus" style="margin:0; color:var(--muted); font-size:13px; font-weight:750;">Menghubungkan ke kanal pembayaran...</p>
                <div style="width:100%; height:8px; background:color-mix(in srgb, var(--line) 68%, transparent); border-radius:999px; overflow:hidden;">
                    <div id="qrisProgress" style="height:100%; width:0%; background:linear-gradient(90deg, var(--primary), var(--primary-dark)); transition:width 1.4s ease;"></div>
                </div>
            </div>
        </section>
    </div>

    <script>
        window.runZeroPaySimulation = function(options = {}) {
            const overlay = document.getElementById('qrisOverlay');
            const progress = document.getElementById('qrisProgress');
            const title = document.getElementById('qrisTitle');
            const subtitle = document.getElementById('qrisSubtitle');
            const status = document.getElementById('qrisStatus');
            const duration = Number(options.duration || 1400);

            if (!overlay || !progress) {
                if (typeof options.onComplete === 'function') {
                    options.onComplete();
                    return;
                }
                if (options.form) options.form.submit();
                return;
            }

            if (title) title.textContent = options.title || 'Memproses Transaksi';
            if (subtitle) subtitle.textContent = options.subtitle || 'ZeroPay sedang mengonfirmasi transaksi Anda.';
            if (status) status.textContent = options.status || 'Menghubungkan ke kanal pembayaran...';
            overlay.style.display = 'flex';
            overlay.setAttribute('aria-hidden', 'false');
            progress.style.transition = 'none';
            progress.style.width = '0%';
            void progress.offsetWidth;
            progress.style.transition = `width ${duration}ms ease`;
            progress.style.width = '100%';

            window.setTimeout(() => {
                if (status) status.textContent = 'Transaksi berhasil dikonfirmasi.';
                window.setTimeout(() => {
                    overlay.style.display = 'none';
                    overlay.setAttribute('aria-hidden', 'true');
                    progress.style.transition = 'none';
                    progress.style.width = '0%';

                    if (typeof options.onComplete === 'function') {
                        options.onComplete();
                        return;
                    }
                    if (options.form) options.form.submit();
                }, 220);
            }, duration);
        };
    </script>
    <script>
        (() => {
            const modal = () => document.getElementById('zeroPayActionModal');
            const close = () => {
                const el = modal();
                if (!el) return;
                el.classList.remove('is-open');
                el.setAttribute('aria-hidden', 'true');
            };
            document.addEventListener('click', (event) => {
                const openButton = event.target.closest('[data-zeropay-modal-open]');
                if (openButton) {
                    event.preventDefault();
                    const el = modal();
                    if (!el) return;
                    el.classList.add('is-open');
                    el.setAttribute('aria-hidden', 'false');
                    return;
                }
                const el = modal();
                if (!el || !el.classList.contains('is-open')) return;
                if (event.target === el || event.target.closest('[data-zeropay-modal-close]')) {
                    event.preventDefault();
                    close();
                }
            });
            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') close();
            });
        })();
    </script>
    <script>
        (() => {
            function shouldPrimeLink(link) {
                if (!link || !link.closest('.nav')) return false;
                if (link.hasAttribute('download') || link.target) return false;

                const href = link.getAttribute('href') || '';
                if (!href || href === '#' || href.startsWith('#')) return false;

                const url = new URL(href, window.location.href);
                if (url.origin !== window.location.origin) return false;
                if (url.pathname === window.location.pathname && url.search === window.location.search) return false;

                return true;
            }

            document.addEventListener('click', (event) => {
                const link = event.target.closest('a[href]');
                if (!shouldPrimeLink(link)) return;

                const nav = link.closest('.nav');
                nav?.querySelectorAll('a.active').forEach((item) => item.classList.remove('active'));
                link.classList.add('active');
            });
        })();
    </script>
    <?php echo $__env->yieldContent('scripts'); ?>

    <?php echo $__env->renderWhen(config('dev_quick_switch.enabled'), 'components.dev-quick-switch', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>

</body>
</html>






<?php /**PATH C:\laragon\www\ProyekTI\resources\views/layouts/app.blade.php ENDPATH**/ ?>