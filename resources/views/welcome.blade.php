@extends('layouts.public')

@section('title', 'Beranda')
@section('body-class', 'public-welcome')

@section('page-styles')
.pub-wrap {
    height: calc(var(--vh-fixed) - 70px);
    overflow: hidden;
}
.welcome-page {
    --welcome-max: 1260px;
    height: 100%;
    overflow: hidden;
    position: relative;
}
.welcome-track {
    height: 100%;
    display: flex;
    transform: translateX(calc(var(--active-panel, 0) * -100%));
    transition: transform 520ms cubic-bezier(0.22, 0.82, 0.22, 1);
}
.welcome-panel {
    width: 100%;
    min-width: 100%;
    height: 100%;
    overflow: hidden;
}
.welcome-panel-scroll {
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: color-mix(in srgb, var(--primary) 55%, transparent) transparent;
}
#promo {
    position: relative;
    scroll-padding-bottom: 120px;
}
.hero {
    height: 100%;
    display: grid;
    grid-template-columns: minmax(430px, 0.92fr) minmax(540px, 1.08fr);
    align-items: center;
    gap: 48px;
    padding: 52px 70px 20px;
    max-width: var(--welcome-max);
    margin: 0 auto;
}
.hero-eyebrow {
    display: inline-flex;
    align-items: center;
    padding: 8px 16px;
    border-radius: 999px;
    background: var(--primary-soft);
    color: var(--primary-dark);
    font-size: 13px;
    font-weight: 900;
    text-transform: uppercase;
    margin-bottom: 20px;
}
.hero-title {
    font-size: 74px;
    font-weight: 950;
    line-height: 1.02;
    margin-bottom: 22px;
}
.hero-title span {
    background: linear-gradient(135deg, var(--primary-dark), #9ac85c, var(--accent));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
.hero-sub {
    color: var(--muted);
    font-size: 22px;
    line-height: 1.62;
    max-width: 650px;
    margin-bottom: 34px;
}
.hero-actions {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
    align-items: center;
}
.hero-actions .btn {
    min-height: 60px;
    padding: 0 34px;
    font-size: 18px;
    border-radius: 12px;
}

:root {
    --vh-fixed: 125vh !important;
}
html {
    zoom: 85% !important;
}

.hero-visual {
    width: 100%;
    height: min(560px, calc(var(--vh-fixed) - 200px));
    min-height: 430px;
    border-radius: 22px;
    overflow: hidden;
    border: 1.5px solid color-mix(in srgb, var(--primary) 35%, var(--line));
    box-shadow: var(--shadow);
    background: var(--panel-solid);
    position: relative;
    cursor: grab;
    user-select: none;
    -webkit-user-drag: none;
}
.hero-visual:active {
    cursor: grabbing;
}
.hero-slide {
    position: absolute;
    inset: 0;
    opacity: 0;
    transform: scale(1.08) rotate(0.3deg);
    filter: blur(4px);
    transition: opacity 800ms cubic-bezier(0.25, 1, 0.5, 1), transform 1200ms cubic-bezier(0.25, 1, 0.5, 1), filter 800ms ease;
    pointer-events: none;
    z-index: 1;
    will-change: transform, opacity, filter;
    backface-visibility: hidden;
    -webkit-backface-visibility: hidden;
}
.hero-slide.active {
    opacity: 1;
    transform: scale(1) rotate(0deg);
    filter: blur(0px);
    pointer-events: auto;
    z-index: 2;
}
.hero-slide img {
    width: 100%;
    height: 100%;
    display: block;
    object-fit: cover;
    pointer-events: none;
    will-change: transform;
    backface-visibility: hidden;
    -webkit-backface-visibility: hidden;
}
.slide-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 24px;
    background: linear-gradient(to top, rgba(14, 20, 22, 0.95) 0%, rgba(14, 20, 22, 0.4) 60%, transparent 100%);
    z-index: 5;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    pointer-events: none;
}
.glass-card {
    background: rgba(17, 26, 29, 0.65);
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);
    border: 1px solid rgba(255, 255, 255, 0.09);
    border-radius: 16px;
    padding: 16px 20px;
    max-width: 460px;
    transform: translateY(22px);
    opacity: 0;
    transition: transform 550ms cubic-bezier(0.25, 1, 0.5, 1), opacity 550ms ease;
    will-change: transform, opacity;
    box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.1), var(--shadow-soft);
}
.hero-slide.active .glass-card {
    transform: translateY(0);
    opacity: 1;
    transition-delay: 150ms;
}
.slide-badge {
    display: inline-flex;
    align-items: center;
    font-size: 9px;
    font-weight: 900;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    padding: 4px 9px;
    border-radius: 6px;
    margin-bottom: 10px;
    border: 1px solid transparent;
}
.badge-primary {
    background: rgba(32, 188, 168, 0.15);
    color: var(--primary-dark);
    border-color: rgba(32, 188, 168, 0.3);
}
.badge-accent {
    background: rgba(245, 158, 11, 0.15);
    color: var(--accent);
    border-color: rgba(245, 158, 11, 0.3);
}
.badge-info {
    background: rgba(96, 165, 250, 0.15);
    color: var(--info);
    border-color: rgba(96, 165, 250, 0.3);
}
.badge-neon {
    background: rgba(139, 92, 246, 0.15);
    color: #a78bfa;
    border-color: rgba(139, 92, 246, 0.3);
}
.badge-success {
    background: rgba(74, 222, 128, 0.15);
    color: var(--ok);
    border-color: rgba(74, 222, 128, 0.3);
}
.slide-title {
    font-size: 23px;
    font-weight: 950;
    margin: 0 0 6px 0;
    line-height: 1.2;
    letter-spacing: -0.2px;
    display: block;
    color: var(--ink);
}
.hero-slide.active:nth-child(1) .slide-title {
    color: var(--primary-dark);
}
.hero-slide.active:nth-child(2) .slide-title {
    color: var(--accent);
}
.hero-slide.active:nth-child(3) .slide-title {
    color: var(--info);
}
.hero-slide.active:nth-child(4) .slide-title {
    color: #a78bfa;
}
.hero-slide.active:nth-child(5) .slide-title {
    color: var(--ok);
}
.slide-desc {
    font-size: 13px;
    color: var(--muted);
    line-height: 1.5;
    margin: 0;
    font-weight: 500;
}
.hero-indicators {
    position: absolute;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 10;
    display: flex;
    gap: 8px;
    padding: 8px 14px;
    border-radius: 999px;
    background: rgba(14, 20, 22, 0.62);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(226, 241, 237, 0.11);
    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.5);
}
.indicator-pill {
    border: 0;
    outline: 0;
    padding: 0;
    width: 12px;
    height: 5px;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.18);
    cursor: pointer;
    position: relative;
    overflow: hidden;
    transition: width 350ms cubic-bezier(0.25, 1, 0.5, 1), background 300ms ease;
}
.indicator-pill:hover {
    background: rgba(255, 255, 255, 0.32);
}
.indicator-pill.active {
    width: 42px;
    background: rgba(255, 255, 255, 0.12);
}
.indicator-progress {
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    width: 0%;
    background: linear-gradient(90deg, var(--primary), var(--primary-dark));
    box-shadow: 0 0 8px var(--primary);
}
.indicator-pill:not(.active) .indicator-progress {
    transition: none !important;
    width: 0% !important;
}
.indicator-pill.active .indicator-progress {
    width: 100%;
    transition: width 4000ms linear;
}
.alert-success {
    position: fixed;
    top: 92px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 30;
    width: min(640px, calc(100vw - 48px));
    padding: 14px 20px;
    border-radius: 12px;
    background: color-mix(in srgb, var(--ok) 14%, var(--panel-solid));
    border: 1px solid color-mix(in srgb, var(--ok) 30%, transparent);
    color: var(--ink);
    text-align: center;
    font-weight: 700;
}
.panel-inner {
    max-width: var(--welcome-max);
    min-height: 100%;
    margin: 0 auto;
    padding: 56px 70px 46px;
}
#promo .panel-inner {
    max-width: 1480px;
    padding-right: 96px;
    padding-bottom: 180px;
}
.panel-head {
    margin-bottom: 22px;
}
.panel-head h2 {
    margin: 0;
    font-size: 42px;
    line-height: 1.08;
}
.service-list {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 14px;
    padding-bottom: 28px;
}
.service-row,
.promo-card,
.about-panel {
    border: 1px solid var(--line);
    border-radius: 16px;
    background: color-mix(in srgb, var(--panel) 88%, transparent);
    box-shadow: var(--shadow);
}
.service-row {
    display: grid;
    grid-template-columns: 1fr auto;
    align-items: center;
    gap: 18px;
    padding: 18px 20px;
}
.service-row h3,
.promo-card h3 {
    margin: 0 0 7px;
    font-size: 19px;
}
.service-title-line {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}
.service-discount-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 24px;
    padding: 0 9px;
    border-radius: 999px;
    background: var(--accent-soft);
    color: var(--accent);
    font-size: 12px;
    font-weight: 900;
}
.service-row p,
.promo-card p,
.about-panel p,
.about-point p {
    margin: 0;
    color: var(--muted);
    font-size: 15px;
    line-height: 1.55;
}
.service-price {
    min-width: 150px;
    min-height: 52px;
    padding: 10px 14px;
    border-radius: 12px;
    background: var(--primary-soft);
    color: var(--primary-dark);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    font-weight: 900;
}
.service-price-old {
    color: var(--muted);
    font-size: 12px;
    font-weight: 800;
    text-decoration: line-through;
    text-decoration-thickness: 2px;
}
.service-price-new {
    color: var(--primary-dark);
    font-size: 15px;
    line-height: 1.15;
}
.promo-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 16px;
    width: 100%;
    max-width: none;
    padding-bottom: 132px;
}
.promo-card {
    min-height: 150px;
    padding: 20px 28px;
    display: grid;
    grid-template-columns: minmax(0, 1fr) auto;
    align-items: center;
    gap: 34px;
    position: relative;
    overflow: hidden;
}
.promo-card::before {
    content: "";
    position: absolute;
    inset: 0 auto 0 0;
    width: 5px;
    background: linear-gradient(180deg, var(--primary), var(--accent));
}
.promo-card-main {
    position: relative;
    z-index: 1;
    display: grid;
    gap: 10px;
}
.promo-badge {
    align-self: flex-start;
    width: max-content;
    padding: 7px 12px;
    border-radius: 999px;
    background: var(--accent-soft);
    color: var(--accent);
    font-size: 12px;
    font-weight: 900;
}
.promo-meta {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-top: 6px;
}
.promo-chip {
    display: inline-flex;
    min-height: 32px;
    align-items: center;
    padding: 0 12px;
    border: 1px solid var(--line);
    border-radius: 999px;
    color: var(--muted);
    background: color-mix(in srgb, var(--bg) 72%, transparent);
    font-size: 13px;
    font-weight: 800;
}
.promo-discount-panel {
    position: relative;
    z-index: 1;
    width: 148px;
    min-height: 104px;
    border-radius: 18px;
    border: 1px solid color-mix(in srgb, var(--accent) 32%, transparent);
    background: linear-gradient(135deg, color-mix(in srgb, var(--accent-soft) 82%, transparent), color-mix(in srgb, var(--primary-soft) 72%, transparent));
    display: grid;
    place-items: center;
    text-align: center;
    box-shadow: 0 18px 48px color-mix(in srgb, var(--accent) 14%, transparent);
}
.promo-discount-panel strong {
    display: block;
    color: var(--accent);
    font-size: 34px;
    line-height: 1;
    font-weight: 950;
}
.promo-discount-panel span {
    color: var(--muted);
    font-size: 12px;
    font-weight: 900;
    text-transform: uppercase;
}
.about-grid {
    display: grid;
    grid-template-columns: 1.08fr 0.92fr;
    gap: 18px;
}
.about-panel {
    padding: 28px;
}
.about-panel p {
    font-size: 20px;
    line-height: 1.72;
}
.about-points {
    display: grid;
    gap: 12px;
}
.about-point {
    padding: 16px 18px;
    border-radius: 14px;
    border: 1px solid var(--line);
    background: color-mix(in srgb, var(--bg) 82%, transparent);
}
.about-point strong {
    display: block;
    margin-bottom: 6px;
    font-size: 16px;
}
.empty-state {
    grid-column: 1 / -1;
    padding: 26px;
    border: 1px dashed var(--line);
    border-radius: 16px;
    color: var(--muted);
    background: color-mix(in srgb, var(--panel) 65%, transparent);
}
.public-zoru-launch {
    position: fixed;
    left: 28px;
    right: auto;
    bottom: 28px;
    z-index: 70;
    display: inline-flex;
    align-items: center;
    flex-direction: row-reverse;
    gap: 12px;
    border: 0;
    background: transparent;
    color: var(--ink);
    cursor: pointer;
    transform: scale(.9412);
    transform-origin: left bottom;
}
.public-zoru-launch-label {
    opacity: 0;
    transform: translateX(12px);
    pointer-events: none;
    padding: 10px 14px;
    border-radius: 999px;
    border: 1px solid var(--line);
    background: color-mix(in srgb, var(--panel-solid) 92%, transparent);
    color: var(--primary-dark);
    font-weight: 900;
    box-shadow: var(--shadow);
    transition: opacity .18s ease, transform .18s ease;
    white-space: nowrap;
}
.public-zoru-launch:hover .public-zoru-launch-label,
.public-zoru-launch:focus-visible .public-zoru-launch-label {
    opacity: 1;
    transform: translateX(0);
}
.public-zoru-face {
    width: 56px;
    height: 56px;
    border-radius: 18px;
    border: 1px solid color-mix(in srgb, var(--primary) 52%, transparent);
    background:
        linear-gradient(135deg, color-mix(in srgb, var(--primary) 22%, var(--panel-solid)), color-mix(in srgb, #4f7cff 18%, var(--panel-solid)));
    display: grid;
    place-items: center;
    box-shadow: 0 18px 45px color-mix(in srgb, var(--primary) 25%, transparent);
    position: relative;
}
.public-zoru-face::before {
    content: "";
    position: absolute;
    top: 9px;
    left: 50%;
    width: 2px;
    height: 10px;
    background: var(--primary-dark);
    transform: translateX(-50%);
    border-radius: 999px;
}
.public-zoru-face::after {
    content: "";
    position: absolute;
    top: 5px;
    left: 50%;
    width: 8px;
    height: 8px;
    background: var(--accent);
    transform: translateX(-50%);
    border-radius: 50%;
}
.public-zoru-eyes {
    width: 36px;
    height: 24px;
    border-radius: 10px;
    border: 1px solid color-mix(in srgb, var(--primary-dark) 55%, transparent);
    background: color-mix(in srgb, var(--bg) 72%, transparent);
    position: relative;
}
.public-zoru-face > .public-zoru-eyes {
    transform: translateY(4px);
}
.public-zoru-eyes::before,
.public-zoru-eyes::after {
    content: "";
    position: absolute;
    top: 8px;
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: var(--primary-dark);
    box-shadow: 0 0 12px var(--primary);
}
.public-zoru-eyes::before {
    left: 8px;
}
.public-zoru-eyes::after {
    right: 8px;
}
.public-zoru-modal {
    position: fixed;
    inset: 0;
    z-index: 90;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 28px;
    background: rgba(0, 0, 0, .58);
    backdrop-filter: blur(10px);
}
.public-zoru-modal.is-open {
    display: flex;
}
.public-zoru-card {
    width: min(760px, calc(100vw - 48px));
    height: min(560px, calc(var(--vh-fixed) - 96px));
    max-height: min(760px, calc(var(--vh-fixed) - 96px));
    display: grid;
    grid-template-rows: auto 1fr auto;
    border-radius: 22px;
    border: 1px solid var(--line);
    background: color-mix(in srgb, var(--panel-solid) 96%, transparent);
    box-shadow: var(--shadow);
    overflow: hidden;
    transform: scale(.9412);
    transform-origin: center;
}
.public-zoru-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 18px 20px;
    border-bottom: 1px solid var(--line);
}
.public-zoru-title {
    display: flex;
    align-items: center;
    gap: 12px;
}
.public-zoru-mini {
    width: 38px;
    height: 38px;
    border-radius: 13px;
    display: grid;
    place-items: center;
    background: var(--primary-soft);
    border: 1px solid color-mix(in srgb, var(--primary) 40%, transparent);
}
.public-zoru-mini .public-zoru-eyes {
    transform: scale(.68);
}
.public-zoru-title h3 {
    margin: 0;
    font-size: 22px;
}
.public-zoru-title p {
    margin: 3px 0 0;
    color: var(--muted);
    font-size: 13px;
}
.public-zoru-close {
    width: 38px;
    height: 38px;
    border-radius: 12px;
    border: 1px solid var(--line);
    background: var(--bg);
    color: var(--ink);
    cursor: pointer;
    font-size: 22px;
    line-height: 1;
}
.public-zoru-chat {
    min-height: 0;
    overflow-y: auto;
    padding: 18px 20px;
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.public-zoru-bubble {
    max-width: 82%;
    border-radius: 16px;
    padding: 12px 14px;
    line-height: 1.58;
    white-space: pre-wrap;
    font-size: 14px;
}
.public-zoru-bubble.is-opening {
    max-width: 82%;
}
.public-zoru-bubble.assistant {
    align-self: flex-start;
    background: color-mix(in srgb, var(--primary-soft) 68%, transparent);
    border: 1px solid color-mix(in srgb, var(--primary) 22%, transparent);
}
.public-zoru-bubble.user {
    align-self: flex-end;
    background: color-mix(in srgb, var(--accent-soft) 76%, transparent);
    border: 1px solid color-mix(in srgb, var(--accent) 24%, transparent);
}
.public-zoru-foot {
    padding: 16px 20px 18px;
    border-top: 1px solid var(--line);
    display: grid;
    gap: 12px;
}
.public-zoru-shortcuts {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}
.public-zoru-shortcuts button {
    border: 1px solid var(--line);
    background: color-mix(in srgb, var(--bg) 78%, transparent);
    color: var(--primary-dark);
    border-radius: 999px;
    padding: 8px 12px;
    font-weight: 850;
    cursor: pointer;
}
.public-zoru-form {
    display: grid;
    grid-template-columns: minmax(0, 1fr) auto;
    gap: 10px;
}
.public-zoru-form input {
    min-height: 46px;
}
.public-zoru-form button {
    min-width: 96px;
    border-radius: 12px;
    border: 1px solid var(--primary);
    background: var(--primary);
    color: #062f2b;
    font-weight: 900;
    cursor: pointer;
}
@media (max-width: 980px) {
    .pub-wrap {
        height: calc(var(--vh-fixed) - 120px);
    }
    .hero,
    .service-list,
    .promo-grid,
    .about-grid {
        grid-template-columns: 1fr;
    }
    .hero,
    .panel-inner {
        padding: 32px 28px;
    }
    #promo .panel-inner {
        padding-right: 28px;
        padding-bottom: 72px;
    }
    .hero {
        gap: 24px;
        overflow-y: auto;
    }
    .hero-title {
        font-size: 50px;
    }
    .hero-sub {
        font-size: 17px;
    }
    .hero-visual {
        min-height: 300px;
        height: 340px;
    }
    .panel-head h2 {
        font-size: 34px;
    }
    .public-zoru-card {
        max-height: calc(var(--vh-fixed) - 42px);
    }
    .promo-card {
        grid-template-columns: 1fr;
    }
    .promo-discount-panel {
        width: 100%;
        min-height: 82px;
    }
}
@media (max-width: 620px) {
    .hero-title {
        font-size: 40px;
    }
    .service-row {
        grid-template-columns: 1fr;
    }
    .service-price {
        width: 100%;
        text-align: left;
    }
    .public-zoru-launch {
        left: 18px;
        right: auto;
        bottom: 18px;
    }
    .public-zoru-launch-label {
        display: none;
    }
    .public-zoru-form {
        grid-template-columns: 1fr;
    }
    .public-zoru-bubble {
        max-width: 100%;
    }
}
@endsection

@section('content')
@php
    $services = $services ?? collect();
    $activePromotions = $activePromotions ?? collect();
@endphp

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="welcome-page" data-welcome-carousel>
    <div class="welcome-track">
        <section id="home" class="welcome-panel hero" data-panel="home">
            <div class="hero-left">
                <div class="hero-eyebrow">Milky Garage</div>
                <h1 class="hero-title">
                    Performa Maksimal,<br><span>Layanan Profesional</span>
                </h1>
                <p class="hero-sub">
                    Bengkel motor dengan layanan cepat, transparan, dan mekanik handal. Pilih layanan, pantau proses, dan nikmati pengalaman servis yang lebih rapi.
                </p>
                <div class="hero-actions">
                    @auth
                        <a class="btn lg" href="{{ route('dashboard') }}">Dashboard</a>
                    @else
                        <a class="btn lg" href="{{ route('login') }}">Masuk</a>
                        <a class="btn outline lg" href="{{ route('register') }}">Buat Akun</a>
                        <button type="button" class="btn outline lg" id="publicZoruOpen" aria-label="Buka ZoruAi Assistant" style="display: inline-flex; align-items: center; gap: 8px;">
                            <span>Customer Service</span>
                            <span style="font-size: 16px; line-height: 1; filter: drop-shadow(0 0 5px var(--primary));">🤖</span>
                        </button>
                    @endauth
                </div>
            </div>

            <div class="hero-visual" id="heroCarousel">
                <!-- Slide 1: Office -->
                <div class="hero-slide active">
                    <img src="{{ asset('img/Milky Garage Assets/Office Area.webp') }}?v=4" alt="Milky Garage Office">
                    <div class="slide-overlay">
                        <div class="glass-card">
                            <span class="slide-badge badge-primary">MAIN OFFICE</span>
                            <h2 class="slide-title">Milky Garage Office</h2>
                            <p class="slide-desc">Pusat pelayanan utama yang transparan, modern, dan nyaman untuk pendaftaran servis Anda.</p>
                        </div>
                    </div>
                </div>
                <!-- Slide 2: Bar -->
                <div class="hero-slide">
                    <img src="{{ asset('img/Milky Garage Assets/Bar Area.webp') }}?v=4" alt="Bar Milky Garage">
                    <div class="slide-overlay">
                        <div class="glass-card">
                            <span class="slide-badge badge-accent">PREMIUM LOUNGE</span>
                            <h2 class="slide-title">Milky Garage Bar</h2>
                            <p class="slide-desc">Nikmati racikan susu segar khas Milky Garage secara gratis selagi motor kesayangan diservis.</p>
                        </div>
                    </div>
                </div>
                <!-- Slide 3: Cafe -->
                <div class="hero-slide">
                    <img src="{{ asset('img/Milky Garage Assets/Cafe Area.webp') }}?v=4" alt="Cafe Milky Garage">
                    <div class="slide-overlay">
                        <div class="glass-card">
                            <span class="slide-badge badge-info">COMFORT ZONE</span>
                            <h2 class="slide-title">Milky Garage Cafe</h2>
                            <p class="slide-desc">Ruang tunggu santai berkonsep Cafe modern dengan Wi-Fi kencang, pas untuk bekerja atau bersenang.</p>
                        </div>
                    </div>
                </div>
                <!-- Slide 4: Administrator -->
                <div class="hero-slide">
                    <img src="{{ asset('img/Milky Garage Assets/Administrartor Area.webp') }}?v=4" alt="Administrator">
                    <div class="slide-overlay">
                        <div class="glass-card">
                            <span class="slide-badge badge-neon">SYSTEM CONTROL</span>
                            <h2 class="slide-title">Administrator</h2>
                            <p class="slide-desc">Pusat kendali operasional, manajemen data master, pelaporan transaksi, dan monitoring aktivitas bengkel.</p>
                        </div>
                    </div>
                </div>
                <!-- Slide 5: Service Area -->
                <div class="hero-slide">
                    <img src="{{ asset('img/Milky Garage Assets/Service Area.webp') }}?v=4" alt="Service Area">
                    <div class="slide-overlay">
                        <div class="glass-card">
                            <span class="slide-badge badge-success">EXPERT CLINIC</span>
                            <h2 class="slide-title">Service Area</h2>
                            <p class="slide-desc">Area servis steril dengan peralatan canggih dan ditangani langsung oleh mekanik bersertifikat.</p>
                        </div>
                    </div>
                </div>

                <!-- Glassmorphic Indicator Navigation -->
                <div class="hero-indicators">
                    <button type="button" class="indicator-pill active" data-slide="0" aria-label="Slide 1"><span class="indicator-progress"></span></button>
                    <button type="button" class="indicator-pill" data-slide="1" aria-label="Slide 2"><span class="indicator-progress"></span></button>
                    <button type="button" class="indicator-pill" data-slide="2" aria-label="Slide 3"><span class="indicator-progress"></span></button>
                    <button type="button" class="indicator-pill" data-slide="3" aria-label="Slide 4"><span class="indicator-progress"></span></button>
                    <button type="button" class="indicator-pill" data-slide="4" aria-label="Slide 5"><span class="indicator-progress"></span></button>
                </div>
            </div>
        </section>

        <section id="layanan" class="welcome-panel welcome-panel-scroll" data-panel="layanan">
            <div class="panel-inner">
                <div class="panel-head">
                    <h2>Layanan Bengkel</h2>
                </div>
                <div class="service-list">
                    @forelse($services as $service)
                        @php
                            $discountPercent = $service->discountPercent();
                            $discountedPrice = $service->discountedPrice();
                        @endphp
                        <article id="service-{{ $service->id }}" class="service-row">
                            <div>
                                <div class="service-title-line">
                                    <h3>{{ $service->name }}</h3>
                                    @if($service->hasActiveDiscount())
                                        <span class="service-discount-badge">{{ $discountPercent }}%</span>
                                    @endif
                                </div>
                                <p>Estimasi pengerjaan sekitar {{ $service->estimated_minutes }} menit.</p>
                            </div>
                            <div class="service-price">
                                @if($service->hasActiveDiscount())
                                    <span class="service-price-old">Rp {{ number_format($service->base_price, 0, ',', '.') }}</span>
                                    <span class="service-price-new">Rp {{ number_format($discountedPrice, 0, ',', '.') }}</span>
                                @else
                                    <span class="service-price-new">Rp {{ number_format($service->base_price, 0, ',', '.') }}</span>
                                @endif
                            </div>
                        </article>
                    @empty
                        <div class="empty-state">Belum ada layanan yang tersedia.</div>
                    @endforelse
                </div>
            </div>
        </section>

        <section id="promo" class="welcome-panel welcome-panel-scroll" data-panel="promo">
            <div class="panel-inner">
                <div class="panel-head">
                    <h2>Promo Aktif</h2>
                </div>
                <div class="promo-grid">
                    @forelse($activePromotions as $promo)
                        <article class="promo-card">
                            <div class="promo-card-main">
                                <span class="promo-badge">Promo {{ $promo->discount_percent }}%</span>
                                <h3>{{ $promo->title }}</h3>
                                <p>{{ $promo->description ?: 'Promo aktif untuk layanan Milky Garage.' }}</p>
                                <div class="promo-meta">
                                    <span class="promo-chip">{{ $promo->serviceType?->name ?? 'Semua layanan' }}</span>
                                    @if($promo->ends_at)
                                        <span class="promo-chip">Berlaku sampai {{ $promo->ends_at->format('d M Y') }}</span>
                                    @else
                                        <span class="promo-chip">Berlaku hari ini</span>
                                    @endif
                                </div>
                            </div>
                            <div class="promo-discount-panel" aria-label="Diskon {{ $promo->discount_percent }} persen">
                                <div>
                                    <strong>{{ $promo->discount_percent }}%</strong>
                                    <span>Potongan</span>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="empty-state">Belum ada promo aktif saat ini.</div>
                    @endforelse
                </div>
            </div>
        </section>

        <section id="tentang" class="welcome-panel" data-panel="tentang">
            <div class="panel-inner">
                <div class="panel-head">
                    <h2>Tentang Kami</h2>
                </div>
                <div class="about-grid">
                    <article class="about-panel">
                        <p>
                            Milky Garage adalah bengkel motor yang menggabungkan layanan servis, pengelolaan operasional, dan bantuan AI dalam satu alur kerja yang matang. Pelanggan dapat melihat layanan, membuat booking, dan mengikuti status pekerjaan dengan lebih jelas, sementara tim bengkel terbantu oleh pencatatan service, pembayaran, laporan, katalog layanan, promo, serta ZoruAI untuk mempercepat pekerjaan administratif. Sistem ini dirancang agar pengalaman servis terasa lebih tenang, terarah, dan profesional dari awal sampai selesai.
                        </p>
                    </article>
                    <div class="about-points">
                        <div class="about-point">
                            <strong>Terintegrasi dengan AI</strong>
                            <p>ZoruAI membantu alur operasional seperti informasi layanan, promo, analisis, dan perintah owner.</p>
                        </div>
                        <div class="about-point">
                            <strong>Layanan bengkel jelas</strong>
                            <p>Setiap service punya harga dan estimasi waktu agar pelanggan memahami pilihan sejak awal.</p>
                        </div>
                        <div class="about-point">
                            <strong>Operasional matang</strong>
                            <p>Booking, service order, pembayaran, laporan, katalog, dan notifikasi dibuat saling terhubung.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>



<div class="public-zoru-modal" id="publicZoruModal" aria-hidden="true">
    <div class="public-zoru-card" role="dialog" aria-modal="true" aria-labelledby="publicZoruTitle">
        <div class="public-zoru-head">
            <div class="public-zoru-title">
                <span class="public-zoru-mini" aria-hidden="true"><span class="public-zoru-eyes"></span></span>
                <div>
                    <h3 id="publicZoruTitle">ZoruAi Assistant</h3>
                    <p>Milky Garage</p>
                </div>
            </div>
            <button type="button" class="public-zoru-close" id="publicZoruClose" aria-label="Tutup">x</button>
        </div>

        <div class="public-zoru-chat" id="publicZoruChat">
            <div class="public-zoru-bubble assistant is-opening">Halo, saya ZoruAi Assistant publik Milky Garage. Saya bisa membantu soal layanan bengkel, promo, cara booking, pembayaran umum, ZeroPay secara umum, dan panduan web.</div>
        </div>

        <div class="public-zoru-foot">
            <div class="public-zoru-shortcuts">
                <button type="button" data-public-zoru-shortcut="daftar layanan">Daftar layanan</button>
                <button type="button" data-public-zoru-shortcut="promo aktif">Promo aktif</button>
                <button type="button" data-public-zoru-shortcut="cara booking">Cara booking</button>
                <button type="button" data-public-zoru-shortcut="panduan web">Panduan web</button>
                <button type="button" data-public-zoru-shortcut="tentang ZoruAI">Tentang ZoruAI</button>
            </div>
            <form class="public-zoru-form" id="publicZoruForm">
                <input type="text" id="publicZoruInput" maxlength="500" autocomplete="off" placeholder="Tanyakan layanan, promo, atau cara booking..." required>
                <button type="submit">Kirim</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
(function () {
    const carousel = document.getElementById('heroCarousel');
    if (!carousel) return;

    const slides = Array.from(carousel.querySelectorAll('.hero-slide'));
    const pills = Array.from(carousel.querySelectorAll('.indicator-pill'));
    let currentIndex = 0;
    let timer = null;
    const intervalDuration = 4000;

    // Draggable Variables
    let isDragging = false;
    let startX = 0;
    let dragOffset = 0;

    function showSlide(index) {
        slides.forEach(s => {
            s.classList.remove('active');
            s.style.transform = ''; // reset drag styles
        });
        pills.forEach(p => p.classList.remove('active'));

        currentIndex = (index + slides.length) % slides.length;

        slides[currentIndex].classList.add('active');
        pills[currentIndex].classList.add('active');

        startTimer();
    }

    function startTimer() {
        if (timer) clearTimeout(timer);
        timer = setTimeout(() => {
            showSlide(currentIndex + 1);
        }, intervalDuration);
    }

    pills.forEach((pill, idx) => {
        pill.addEventListener('click', () => {
            showSlide(idx);
        });
    });

    // Drag / Swipe Mouse and Touch Handlers
    function onDragStart(e) {
        isDragging = true;
        startX = e.type.includes('touch') ? e.touches[0].clientX : e.clientX;
        dragOffset = 0;
        if (timer) clearTimeout(timer); // stop auto rotating while dragging
    }

    function onDragMove(e) {
        if (!isDragging) return;
        const currentX = e.type.includes('touch') ? e.touches[0].clientX : e.clientX;
        dragOffset = currentX - startX;

        // Apply a visual drag effect (slightly translating the active slide)
        const activeSlide = slides[currentIndex];
        if (activeSlide) {
            // Apply scale down and translation
            activeSlide.style.transform = `translateX(${dragOffset}px) scale(0.98)`;
            activeSlide.style.transition = 'none'; // remove transition for real-time tracking
        }
    }

    function onDragEnd() {
        if (!isDragging) return;
        isDragging = false;

        const activeSlide = slides[currentIndex];
        if (activeSlide) {
            activeSlide.style.transition = ''; // restore CSS transitions
        }

        // Swipe Threshold (100px)
        if (dragOffset < -100) {
            // Dragged left -> next slide
            showSlide(currentIndex + 1);
        } else if (dragOffset > 100) {
            // Dragged right -> previous slide
            showSlide(currentIndex - 1);
        } else {
            // Snap back
            showSlide(currentIndex);
        }
    }

    // Attach Event Listeners
    carousel.addEventListener('mousedown', onDragStart);
    carousel.addEventListener('mousemove', onDragMove);
    window.addEventListener('mouseup', onDragEnd);

    carousel.addEventListener('touchstart', onDragStart, { passive: true });
    carousel.addEventListener('touchmove', onDragMove, { passive: true });
    window.addEventListener('touchend', onDragEnd);

    showSlide(0);
})();
</script>
<script>
(function () {
    const page = document.querySelector('[data-welcome-carousel]');
    if (!page) return;

    const panels = ['home', 'layanan', 'promo', 'tentang'];
    const navLinks = Array.from(document.querySelectorAll('.pub-nav-link'));

    function setPanel(name, replace = false) {
        const index = Math.max(0, panels.indexOf(name));
        const active = panels[index] || 'home';
        page.style.setProperty('--active-panel', index);

        navLinks.forEach(link => {
            link.classList.toggle('active', link.hash === '#' + active);
        });

        const method = replace ? 'replaceState' : 'pushState';
        if (window.location.hash !== '#' + active) {
            window.history[method](null, '', '#' + active);
        }
    }

    navLinks.forEach(link => {
        if (!link.hash || !panels.includes(link.hash.slice(1))) return;
        link.addEventListener('click', event => {
            const linkUrl = new URL(link.href, window.location.origin);
            if (linkUrl.pathname !== window.location.pathname) return;
            event.preventDefault();
            setPanel(link.hash.slice(1));
        });
    });

    window.addEventListener('popstate', () => {
        setPanel((window.location.hash || '#home').slice(1), true);
    });

    setPanel((window.location.hash || '#home').slice(1), true);
})();
</script>
<script>
(function () {
    const openButton = document.getElementById('publicZoruOpen');
    const modal = document.getElementById('publicZoruModal');
    const closeButton = document.getElementById('publicZoruClose');
    const chat = document.getElementById('publicZoruChat');
    const form = document.getElementById('publicZoruForm');
    const input = document.getElementById('publicZoruInput');
    const csrfToken = @json(csrf_token());

    if (!openButton || !modal || !closeButton || !chat || !form || !input) return;

    function setOpen(open) {
        modal.classList.toggle('is-open', open);
        modal.setAttribute('aria-hidden', open ? 'false' : 'true');
        if (open) {
            setTimeout(() => input.focus(), 80);
        }
    }

    function addBubble(type, text) {
        const bubble = document.createElement('div');
        bubble.className = 'public-zoru-bubble ' + type;
        bubble.textContent = text;
        chat.appendChild(bubble);
        chat.scrollTop = chat.scrollHeight;
        return bubble;
    }

    function publicZoruFallback(prompt) {
        const q = prompt.toLowerCase();
        if (q.includes('layanan') || q.includes('servis') || q.includes('service')) {
            return 'Daftar layanan Milky Garage dapat dilihat melalui bagian Layanan. Di sana Anda bisa melihat pilihan servis, estimasi pengerjaan, dan informasi harga yang tersedia.';
        }
        if (q.includes('promo') || q.includes('diskon')) {
            return 'Promo aktif dapat dicek melalui bagian Promo. Jika ada diskon berjalan, sistem akan menampilkan nama promo, layanan terkait, dan masa berlakunya.';
        }
        if (q.includes('booking') || q.includes('daftar') || q.includes('jadwal')) {
            return 'Untuk booking servis, masuk atau daftar akun terlebih dahulu, lalu buka menu Booking Service. Pilih kendaraan, layanan, tanggal, jam, dan tulis keluhan motor agar tim bengkel bisa memproses jadwal dengan jelas.';
        }
        if (q.includes('panduan') || q.includes('web') || q.includes('website') || q.includes('cara pakai')) {
            return 'Panduan singkat: Home berisi pengenalan bengkel, Layanan menampilkan servis, Promo menampilkan diskon, Tentang Kami berisi profil bengkel, Masuk untuk akun lama, dan Daftar untuk pelanggan baru.';
        }
        if (q.includes('zoru') || q.includes('assistant') || q.includes('asisten') || q.includes('siapa')) {
            return 'Saya ZoruAi Assistant untuk Milky Garage. Saya membantu menjawab informasi umum tentang layanan bengkel, promo, alur booking, pembayaran, dan panduan penggunaan web.';
        }
        return 'Saya bisa membantu seputar Milky Garage: layanan bengkel, promo, cara booking, pembayaran, ZeroPay, dan panduan web. Coba pilih salah satu tombol cepat di bawah.';
    }
    async function askZoru(prompt) {
        const cleanPrompt = prompt.trim();
        if (!cleanPrompt) return;

        addBubble('user', cleanPrompt);
        input.value = '';
        const loading = addBubble('assistant', 'ZoruAi Assistant sedang menyiapkan jawaban...');

        try {
            const response = await fetch(@json(route('zoruai.public')), {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ prompt: cleanPrompt }),
            });
            if (!response.ok) {
                throw new Error('public zoru request failed');
            }
            const data = await response.json();
            loading.textContent = data.reply || publicZoruFallback(cleanPrompt);
        } catch (_) {
            loading.textContent = publicZoruFallback(cleanPrompt);
        }
    }

    openButton.addEventListener('click', () => setOpen(true));
    closeButton.addEventListener('click', () => setOpen(false));
    modal.addEventListener('click', (event) => {
        if (event.target === modal) setOpen(false);
    });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') setOpen(false);
    });

    document.querySelectorAll('[data-public-zoru-shortcut]').forEach((button) => {
        button.addEventListener('click', () => {
            setOpen(true);
            askZoru(button.dataset.publicZoruShortcut || '');
        });
    });

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        askZoru(input.value);
    });
})();
</script>
@endsection
