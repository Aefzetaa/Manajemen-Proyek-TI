<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $__env->yieldContent('title', 'Milky Garage'); ?> | Milky Garage</title>
    <link class="favicon" rel="icon" type="image/webp" href="<?php echo e(asset('img/Milky Garage Assets/Logo Area.webp')); ?>">
    <link rel="shortcut icon" type="image/webp" href="<?php echo e(asset('img/Milky Garage Assets/Logo Area.webp')); ?>">
    <link rel="apple-touch-icon" href="<?php echo e(asset('img/Milky Garage Assets/Logo Area.webp')); ?>">
    <style>
        :root {
            color-scheme: dark;
            --bg: #0e1416;
            --bg-soft: #111a1d;
            --panel: rgba(19,27,31,0.90);
            --panel-solid: #141d21;
            --ink: #edf7f5;
            --muted: #a6b3b7;
            --line: rgba(226,241,237,0.13);
            --primary: #20bca8;
            --primary-dark: #5eead4;
            --primary-soft: rgba(32, 188, 168, 0.15);
            --accent: #f59e0b;
            --accent-soft: rgba(245, 158, 11, 0.14);
            --danger: #f87171;
            --ok: #4ade80;
            --shadow: 0 24px 70px rgba(0,0,0,0.45);
            --focus: rgba(94,234,212,0.30);
            --vh-fixed: 153.846vh; /* 100vh / 0.65 zoom factor to fill exactly 100% of physical viewport height */
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        html {
            scroll-behavior: smooth;
            zoom: 65%;
            text-rendering: optimizeLegibility;
        }
        img, svg {
            image-rendering: -webkit-optimize-contrast;
            image-rendering: crisp-edges;
        }
        svg {
            vector-effect: non-scaling-stroke;
        }
        .btn, .auth-card, img, svg, input, select, textarea {
            transform: translateZ(0);
            backface-visibility: hidden;
            -webkit-backface-visibility: hidden;
        }
        body {
            font-family: Aptos, "Segoe UI Variable", "Segoe UI", Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg);
            color: var(--ink);
            min-height: var(--vh-fixed);
            transition: background .2s, color .2s;
        }
        body.public-welcome {
            overflow: hidden;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideInNav {
            from { opacity: 0; transform: translateX(-8px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes scaleIn {
            from { opacity: 0; transform: scale(0.96); }
            to { opacity: 1; transform: scale(1); }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
        body::before {
            content: "";
            position: fixed;
            inset: 0;
            pointer-events: none;
            background:
                linear-gradient(135deg, rgba(15,118,110,.14), transparent 38%),
                linear-gradient(315deg, rgba(195,106,22,.12), transparent 40%);
            z-index: 0;
        }
        html[data-theme="dark"] body::before { opacity: .5; }

        /* â”€â”€ Navbar â”€â”€ */
        .pub-nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 50;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 32px;
            background: color-mix(in srgb, var(--panel) 70%, transparent);
            backdrop-filter: blur(18px);
            border-bottom: 1px solid var(--line);
        }
        .pub-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 800;
            font-size: 17px;
            text-decoration: none;
            color: var(--ink);
        }
        .pub-brand-mark {
            width: 36px; height: 36px;
            display: grid;
            place-items: center;
            background: transparent;
        }
        .pub-brand-mark img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        .pub-nav-menu {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-left: auto;
            margin-right: 18px;
        }
        .pub-nav-link {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            height: 38px;
            padding: 0 13px;
            border: 1px solid transparent;
            border-radius: 10px;
            background: transparent;
            color: var(--muted);
            font-size: 12px;
            font-weight: 850;
            text-decoration: none;
            text-transform: uppercase;
            cursor: pointer;
            transition: color .15s, border-color .15s, background .15s;
        }
        .pub-nav-link:hover {
            color: var(--ink);
            border-color: var(--line);
            background: color-mix(in srgb, var(--primary-soft) 35%, transparent);
        }
        .pub-nav-link.active {
            color: var(--ink);
            border-color: color-mix(in srgb, var(--primary) 55%, transparent);
            background: var(--primary-soft);
        }
        .pub-nav-actions { display: flex; gap: 10px; align-items: center; }

        /* â”€â”€ Buttons â”€â”€ */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 9px 18px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            border: 1px solid var(--primary);
            background: var(--primary);
            color: #fff;
            text-decoration: none;
            transition: transform .15s, box-shadow .15s, background .15s;
            box-shadow: 0 8px 20px color-mix(in srgb, var(--primary) 25%, transparent);
        }
        .btn:hover { transform: translateY(-1px); box-shadow: var(--shadow); }
        .btn.outline {
            background: transparent;
            color: var(--primary-dark);
            box-shadow: none;
        }
        .btn.outline:hover { background: var(--primary-soft); }
        .btn.danger { background: var(--danger); border-color: var(--danger); box-shadow: none; }
        .btn.danger:hover { opacity: .9; }
        .btn.lg { padding: 14px 28px; font-size: 16px; }

        /* â”€â”€ Main content wrapper â”€â”€ */
        .pub-wrap {
            position: relative;
            z-index: 1;
            padding-top: 70px; /* height of pub-nav */
        }
        /* Removed staggered alert animations */

        /* â”€â”€ Alert â”€â”€ */
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            border: 1px solid color-mix(in srgb, var(--ok) 30%, transparent);
            background: color-mix(in srgb, var(--ok) 12%, var(--panel-solid));
            color: var(--ink);
            margin-bottom: 16px;
        }
        .alert.error {
            border-color: color-mix(in srgb, var(--danger) 35%, transparent);
            background: color-mix(in srgb, var(--danger) 12%, var(--panel-solid));
        }

        /* â”€â”€ Auth card â”€â”€ */
        .auth-page {
            min-height: calc(var(--vh-fixed) - 88px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 16px;
        }
        .auth-page > div {
            width: min(440px, calc(100vw - 48px));
        }
        html:has(body.public-auth), html:has(body.public-verify) {
            zoom: 80%;
        }
        body.public-auth, body.public-verify {
            min-height: calc(100vh / .8);
            overflow: hidden;
        }
        body.public-auth .pub-wrap, body.public-verify .pub-wrap {
            min-height: calc(100vh / .8);
            display: grid;
        }
        body.public-auth .auth-page, body.public-verify .auth-page {
            min-height: calc((100vh / .8) - 70px) !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 16px !important;
            overflow: hidden;
        }
        body.public-auth .auth-page > div {
            max-width: 560px !important;
        }
        body.public-verify .auth-page > div {
            max-width: 460px !important;
            margin: 0 auto !important;
        }
        body.public-auth .auth-card {
            padding: 22px 24px;
            max-width: 100%;
        }
        body.public-verify .auth-card {
            padding: 26px;
            max-width: 100%;
        }
        body.public-auth .auth-logo,
        body.public-auth .auth-subtitle,
        body.public-auth .step-badge,
        body.public-verify .auth-subtitle,
        body.public-verify .step-badge {
            margin-bottom: 14px;
        }
        body.public-auth .auth-title,
        body.public-verify .auth-title {
            font-size: 30px;
            margin-bottom: 8px;
        }
        body.public-auth .form-grid {
            gap: 10px;
        }
        body.public-auth .field {
            gap: 5px;
            margin-bottom: 10px;
        }
        body.public-auth input,
        body.public-auth select,
        body.public-verify input {
            min-height: 42px;
            padding: 9px 11px;
        }
        body.public-auth .avatar-option {
            width: 52px !important;
            height: 52px !important;
        }
        body.public-verify .verify-box {
            padding: 18px;
            margin: 18px 0;
            border-radius: 16px;
            background: linear-gradient(145deg, color-mix(in srgb, var(--primary-soft) 60%, transparent), rgba(12, 22, 24, 0.78));
            border: 1px solid color-mix(in srgb, var(--primary) 34%, transparent);
            box-shadow: inset 0 0 0 1px rgba(255,255,255,.025);
        }
        body.public-verify .verify-box p {
            margin-bottom: 12px;
            line-height: 1.45;
        }
        .auth-card {
            width: 100%;
            max-width: 400px;
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 30px;
            box-shadow: var(--shadow);
            backdrop-filter: blur(16px);
            transition: box-shadow 0.25s ease, transform 0.25s ease;
        }
        .auth-card:hover {
            box-shadow: 0 24px 64px color-mix(in srgb, var(--primary) 12%, transparent);
        }
        .auth-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 28px;
        }
        .auth-logo-mark {
            width: 48px; height: 48px;
            display: grid;
            place-items: center;
            background: transparent;
        }
        .auth-logo-mark img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        .auth-logo-text { font-size: 20px; font-weight: 800; }
        .auth-logo-text small {
            display: block;
            margin-top: 3px;
            color: var(--muted);
            font-size: 12px;
            font-weight: 500;
        }
        .auth-title {
            font-size: 22px;
            font-weight: 800;
            margin-bottom: 6px;
        }
        .auth-subtitle {
            color: var(--muted);
            font-size: 14px;
            margin-bottom: 28px;
        }

        /* â”€â”€ Form elements â”€â”€ */
        .field { display: grid; gap: 6px; margin-bottom: 16px; }
        label { font-size: 13px; font-weight: 700; color: var(--ink); }
        input, select, textarea {
            width: 100%;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: color-mix(in srgb, var(--panel-solid) 70%, transparent);
            padding: 11px 12px;
            color: var(--ink);
            font: inherit;
            transition: border-color .15s, box-shadow .15s;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--focus);
        }
        input::placeholder { color: color-mix(in srgb, var(--muted) 70%, transparent); }
        .error-text { color: var(--danger); font-size: 12px; margin-top: 4px; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        @media (max-width: 600px) { .form-grid { grid-template-columns: 1fr; } }
        @media (max-width: 900px) {
            .pub-nav {
                gap: 12px;
                flex-wrap: wrap;
            }
            .pub-nav-menu {
                order: 3;
                width: 100%;
                margin: 0;
                overflow-x: auto;
                padding-bottom: 2px;
            }
        }

        /* â”€â”€ Divider â”€â”€ */
        .auth-divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 20px 0;
            color: var(--muted);
            font-size: 13px;
        }
        .auth-divider::before, .auth-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--line);
        }

        /* â”€â”€ Theme toggle â”€â”€ */
        .theme-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 100;
            padding: 8px 14px;
            border-radius: 999px;
            border: 1px solid var(--line);
            background: var(--panel);
            color: var(--muted);
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            backdrop-filter: blur(10px);
            transition: background .15s, transform .15s;
        }
        .theme-btn:hover { background: var(--panel-solid); transform: scale(1.04); }

        /* â”€â”€ Step badge â”€â”€ */
        .step-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 999px;
            background: var(--primary-soft);
            color: var(--primary-dark);
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 14px;
        }

        /* â”€â”€ Checkbox row â”€â”€ */
        .check-row {
            display: flex;
            gap: 8px;
            align-items: center;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 16px;
        }
        .check-row input { width: auto; }

        /* â”€â”€ Link â”€â”€ */
        a.text-link {
            color: var(--primary-dark);
            font-weight: 600;
            text-decoration: underline;
            text-underline-offset: 3px;
        }

        /* â”€â”€ Verification code input â”€â”€ */
        .verify-box {
            border: 2px dashed color-mix(in srgb, var(--primary) 40%, transparent);
            border-radius: 12px;
            padding: 20px;
            background: var(--primary-soft);
            margin-bottom: 20px;
        }
        .verify-box p {
            font-size: 14px;
            color: var(--muted);
            margin-bottom: 12px;
            line-height: 1.55;
        }

        <?php echo $__env->yieldContent('page-styles'); ?>
    </style>
</head>
<body class="<?php echo $__env->yieldContent('body-class'); ?>">
    <?php echo $__env->make('components.modal-dialog', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <nav class="pub-nav">
        <a href="<?php echo e(route('welcome')); ?>" class="pub-brand">
            <span class="pub-brand-mark">
                <img src="<?php echo e(asset('img/Milky Garage Assets/Logo Area.webp')); ?>" alt="Milky Garage Logo">
            </span>
            <span>Milky Garage</span>
        </a>
        <div class="pub-nav-menu">
            <a class="pub-nav-link" href="<?php echo e(route('welcome')); ?>#home">Home</a>
            <a class="pub-nav-link" href="<?php echo e(route('welcome')); ?>#layanan">Layanan</a>
            <a class="pub-nav-link" href="<?php echo e(route('welcome')); ?>#promo">Promo</a>
            <a class="pub-nav-link" href="<?php echo e(route('welcome')); ?>#tentang">Tentang Kami</a>
        </div>
        <div class="pub-nav-actions">
            <?php if(auth()->guard()->check()): ?>
                <a class="btn outline" href="<?php echo e(route('dashboard')); ?>">Dashboard</a>
                <form method="POST" action="<?php echo e(route('logout')); ?>" style="display:inline">
                    <?php echo csrf_field(); ?>
                    <button class="btn outline" type="submit">Keluar</button>
                </form>
            <?php else: ?>
                <a class="btn outline" href="<?php echo e(route('login')); ?>">Masuk</a>
                <a class="btn" href="<?php echo e(route('register')); ?>">Daftar</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="pub-wrap">
        <?php echo $__env->yieldContent('content'); ?>
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
    <?php echo $__env->yieldContent('scripts'); ?>

    <?php echo $__env->renderWhen(config('dev_quick_switch.enabled'), 'components.dev-quick-switch', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
</body>
</html>

<?php /**PATH C:\laragon\www\ProyekTI\resources\views/layouts/public.blade.php ENDPATH**/ ?>