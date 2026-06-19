@props([
    'name' => 'avatar',
    'selected' => null,
    'size' => 64,
    'placeholder' => false,
    'contained' => false,
])

@php
    use App\Models\User;

    $avatarOptions = collect(User::avatarOptions());
    $hasSelectedAvatar = ! $placeholder || filled($selected);
    $selectedAvatar = User::normalizeAvatar($selected);
    $pickerId = 'avatarPicker_' . substr(md5($name . $selectedAvatar . random_int(1, 999999)), 0, 8);
    $leftOptions = $avatarOptions->where('side', 'P')->values();
    $rightOptions = $avatarOptions->where('side', 'L')->values();
    $leftSlots = [
        ['x' => -174, 'y' => -142],
        ['x' => -222, 'y' => -72],
        ['x' => -238, 'y' => 0],
        ['x' => -222, 'y' => 72],
        ['x' => -174, 'y' => 142],
    ];
    $rightSlots = [
        ['x' => 174, 'y' => -142],
        ['x' => 222, 'y' => -72],
        ['x' => 238, 'y' => 0],
        ['x' => 222, 'y' => 72],
        ['x' => 174, 'y' => 142],
    ];
@endphp

@once
<style>
    .avatar-picker {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .avatar-picker-trigger {
        width: var(--avatar-picker-size, 64px);
        height: var(--avatar-picker-size, 64px);
        padding: 0;
        border: 2px solid color-mix(in srgb, var(--primary) 56%, transparent);
        border-radius: 50%;
        background: transparent;
        cursor: pointer;
        overflow: hidden;
        box-shadow: 0 12px 30px color-mix(in srgb, var(--primary) 18%, transparent);
        display: grid;
        place-items: center;
        color: color-mix(in srgb, var(--primary) 74%, var(--ink));
    }
    .avatar-picker-trigger img {
        width: 58px;
        height: 58px;
        display: block;
        border-radius: 50%;
        object-fit: cover;
        object-position: center center;
        background: color-mix(in srgb, var(--panel, #111827) 24%, transparent);
    }
    .avatar-picker-trigger img[hidden],
    .avatar-picker-placeholder[hidden] {
        display: none !important;
    }
    .avatar-picker.is-empty .avatar-picker-trigger img {
        display: none !important;
    }
    .avatar-picker.is-empty .avatar-picker-placeholder {
        display: grid !important;
    }
    .avatar-picker-placeholder {
        width: 58%;
        height: 58%;
        display: grid;
        place-items: center;
    }
    .avatar-picker-placeholder svg {
        width: 100%;
        height: 100%;
    }
    .avatar-picker-orbit {
        position: absolute;
        left: 50%;
        top: 50%;
        --avatar-floating-left: 50vw;
        --avatar-floating-top: 50vh;
        width: 520px;
        height: 520px;
        border-radius: 50%;
        background: color-mix(in srgb, var(--panel, #111827) 32%, transparent);
        border: 1px solid color-mix(in srgb, var(--primary) 30%, transparent);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        box-shadow: 0 22px 60px rgba(0, 0, 0, .22);
        transform: translate(-50%, -50%);
        opacity: 0;
        pointer-events: none;
        transition: opacity .12s ease;
        z-index: 2147483001;
    }
    .avatar-picker-center {
        position: absolute;
        left: 50%;
        top: 50%;
        width: 82px;
        height: 82px;
        border-radius: 50%;
        transform: translate(-50%, -50%);
        display: grid;
        place-items: center;
        color: color-mix(in srgb, var(--primary) 82%, var(--ink));
        background: color-mix(in srgb, var(--panel, #111827) 38%, transparent);
        border: 2px solid color-mix(in srgb, var(--primary) 48%, transparent);
        box-shadow: 0 0 0 10px color-mix(in srgb, var(--primary) 7%, transparent), 0 18px 44px color-mix(in srgb, var(--primary) 18%, transparent);
        pointer-events: none;
        z-index: 2;
    }
    .avatar-picker-center img {
        width: 66px;
        height: 66px;
        display: block;
        border-radius: 50%;
        object-fit: cover;
        object-position: center center;
        background: color-mix(in srgb, var(--panel, #111827) 24%, transparent);
    }
    .avatar-picker-center-placeholder {
        width: 52%;
        height: 52%;
        display: grid;
        place-items: center;
    }
    .avatar-picker-center-placeholder svg {
        width: 100%;
        height: 100%;
    }
    .avatar-picker-center img[hidden],
    .avatar-picker-center-placeholder[hidden],
    .avatar-picker.is-empty .avatar-picker-center img {
        display: none !important;
    }
    .avatar-picker.is-empty .avatar-picker-center-placeholder {
        display: grid !important;
    }
    .avatar-picker.is-open .avatar-picker-orbit {
        opacity: 1;
        pointer-events: auto;
        transform: translate(-50%, -50%);
    }
    .avatar-picker-orbit.is-floating {
        opacity: 1;
        pointer-events: auto;
        transform: translate(-50%, -50%);
    }
    .avatar-picker-option {
        position: absolute;
        left: 50%;
        top: 50%;
        width: 58px;
        height: 58px;
        transform: translate(calc(-50% + var(--avatar-x)), calc(-50% + var(--avatar-y)));
        cursor: pointer;
    }
    .avatar-picker-option input {
        display: none;
    }
    .avatar-picker-option img {
        width: 58px;
        height: 58px;
        display: block;
        border-radius: 50%;
        object-fit: cover;
        object-position: center center;
        background: color-mix(in srgb, var(--panel, #111827) 24%, transparent);
        border: 2px solid color-mix(in srgb, var(--primary) 34%, rgba(255,255,255,.18));
        box-shadow: 0 10px 24px rgba(0, 0, 0, .28);
        transition: transform .14s ease, border-color .14s ease, box-shadow .14s ease;
    }
    .avatar-picker-option:hover img {
        border-color: color-mix(in srgb, var(--primary) 72%, white);
        transform: scale(1.04);
    }
    .avatar-picker-option input:checked + img {
        border-color: var(--primary);
        transform: scale(1.08);
        box-shadow: 0 0 0 5px color-mix(in srgb, var(--primary) 14%, transparent), 0 12px 28px rgba(0, 0, 0, .34);
    }
    .avatar-picker.is-contained .avatar-picker-orbit {
        width: min(520px, calc(100vw - 56px));
        height: min(520px, calc(100vw - 56px));
    }
    .avatar-picker-screen {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, .66);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        z-index: 2147483000;
    }
    .avatar-picker.is-contained.is-open .avatar-picker-orbit,
    .avatar-picker-orbit.is-floating {
        position: fixed;
        left: var(--avatar-floating-left);
        top: var(--avatar-floating-top);
    }
</style>
@endonce

<div class="avatar-picker{{ $contained ? ' is-contained' : '' }}{{ $hasSelectedAvatar ? '' : ' is-empty' }}" id="{{ $pickerId }}" style="--avatar-picker-size: {{ (int) $size }}px;">
    <button type="button" class="avatar-picker-trigger" data-avatar-trigger aria-label="Pilih avatar">
        <img src="{{ $hasSelectedAvatar ? asset('img/' . $selectedAvatar) : '' }}" alt="Avatar terpilih" data-avatar-preview style="{{ $hasSelectedAvatar ? '' : 'display:none;' }}">
        <span class="avatar-picker-placeholder" data-avatar-placeholder style="{{ $hasSelectedAvatar ? 'display:none;' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="12" cy="8" r="4"></circle>
                <path d="M5 20a7 7 0 0 1 14 0"></path>
            </svg>
        </span>
    </button>

    <div class="avatar-picker-orbit" data-avatar-panel>
        <div class="avatar-picker-center" aria-hidden="true">
            <img src="{{ $hasSelectedAvatar ? asset('img/' . $selectedAvatar) : '' }}" alt="" data-avatar-center-preview style="{{ $hasSelectedAvatar ? '' : 'display:none;' }}">
            <span class="avatar-picker-center-placeholder" data-avatar-center-placeholder style="{{ $hasSelectedAvatar ? 'display:none;' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="8" r="4"></circle>
                    <path d="M5 20a7 7 0 0 1 14 0"></path>
                </svg>
            </span>
        </div>

        @foreach($leftOptions as $index => $avatar)
            @php $slot = $leftSlots[$index] ?? ['x' => -112, 'y' => 0]; @endphp
            <label class="avatar-picker-option" title="{{ $avatar['label'] }}" style="--avatar-x: {{ $slot['x'] }}px; --avatar-y: {{ $slot['y'] }}px;">
                <input type="radio" name="{{ $name }}" value="{{ $avatar['value'] }}" @checked($hasSelectedAvatar && $selectedAvatar === $avatar['value'])>
                <img src="{{ asset('img/' . $avatar['value']) }}" alt="{{ $avatar['label'] }}">
            </label>
        @endforeach

        @foreach($rightOptions as $index => $avatar)
            @php $slot = $rightSlots[$index] ?? ['x' => 112, 'y' => 0]; @endphp
            <label class="avatar-picker-option" title="{{ $avatar['label'] }}" style="--avatar-x: {{ $slot['x'] }}px; --avatar-y: {{ $slot['y'] }}px;">
                <input type="radio" name="{{ $name }}" value="{{ $avatar['value'] }}" @checked($hasSelectedAvatar && $selectedAvatar === $avatar['value'])>
                <img src="{{ asset('img/' . $avatar['value']) }}" alt="{{ $avatar['label'] }}">
            </label>
        @endforeach
    </div>
</div>

@once
<script>
let activeAvatarPicker = null;
let activeAvatarPanelAnchor = null;
let activeAvatarOverlay = null;

function readCssZoom(element) {
    if (!element) return 1;

    const zoomValue = window.getComputedStyle(element).zoom;
    if (!zoomValue || zoomValue === 'normal') return 1;

    if (zoomValue.endsWith('%')) {
        return parseFloat(zoomValue) / 100 || 1;
    }

    const zoom = parseFloat(zoomValue);
    if (!Number.isFinite(zoom) || zoom <= 0) return 1;

    return zoom > 10 ? zoom / 100 : zoom;
}

function currentPageZoom() {
    return readCssZoom(document.documentElement) * readCssZoom(document.body);
}

function updateFloatingAvatarLayout() {
    const zoom = currentPageZoom();
    const viewport = window.visualViewport;
    const width = viewport?.width || window.innerWidth;
    const height = viewport?.height || window.innerHeight;
    const offsetLeft = viewport?.offsetLeft || 0;
    const offsetTop = viewport?.offsetTop || 0;
    const centerLeft = (offsetLeft + (width / 2)) / zoom;
    const centerTop = (offsetTop + (height / 2)) / zoom;
    const fullWidth = window.innerWidth / zoom;
    const fullHeight = window.innerHeight / zoom;

    if (activeAvatarOverlay) {
        activeAvatarOverlay.style.width = `${Math.ceil(fullWidth)}px`;
        activeAvatarOverlay.style.height = `${Math.ceil(fullHeight)}px`;
    }

    const panel = activeAvatarPicker
        ? document.querySelector('.avatar-picker-orbit.is-floating[data-avatar-panel]')
        : null;

    if (panel) {
        panel.style.setProperty('--avatar-floating-left', `${Math.round(centerLeft)}px`);
        panel.style.setProperty('--avatar-floating-top', `${Math.round(centerTop)}px`);
    }
}

function closeAvatarPicker(picker) {
    if (!picker) return;

    const panel = picker.querySelector('[data-avatar-panel]') || (activeAvatarPicker === picker ? document.querySelector('.avatar-picker-orbit.is-floating[data-avatar-panel]') : null);

    if (panel && activeAvatarPanelAnchor && activeAvatarPanelAnchor.parentNode) {
        panel.classList.remove('is-floating');
        panel.querySelectorAll('input[type="radio"]').forEach(function (input) {
            input.removeAttribute('form');
        });
        activeAvatarPanelAnchor.parentNode.insertBefore(panel, activeAvatarPanelAnchor);
        activeAvatarPanelAnchor.remove();
    }

    if (activeAvatarOverlay) {
        activeAvatarOverlay.remove();
    }

    picker.classList.remove('is-open');
    activeAvatarPicker = null;
    activeAvatarPanelAnchor = null;
    activeAvatarOverlay = null;
}

function openAvatarPicker(picker) {
    const panel = picker.querySelector('[data-avatar-panel]');
    if (!panel) return;

    if (picker.classList.contains('is-contained')) {
        const form = picker.closest('form');
        if (form && !form.id) {
            form.id = 'avatarForm_' + Math.random().toString(36).slice(2);
        }

        if (form?.id) {
            panel.querySelectorAll('input[type="radio"]').forEach(function (input) {
                input.setAttribute('form', form.id);
            });
        }

        activeAvatarPanelAnchor = document.createComment('avatar-picker-panel-anchor');
        panel.parentNode.insertBefore(activeAvatarPanelAnchor, panel);

        activeAvatarOverlay = document.createElement('div');
        activeAvatarOverlay.className = 'avatar-picker-screen';
        document.body.appendChild(activeAvatarOverlay);
        document.body.appendChild(panel);
        panel.classList.add('is-floating');
        activeAvatarPicker = picker;
        updateFloatingAvatarLayout();

        activeAvatarOverlay.addEventListener('click', function () {
            closeAvatarPicker(picker);
        });
    }

    picker.classList.add('is-open');
    activeAvatarPicker = picker;
}

document.addEventListener('click', function (event) {
    document.querySelectorAll('.avatar-picker.is-open').forEach(function (picker) {
        const panel = picker.querySelector('[data-avatar-panel]') || (activeAvatarPicker === picker ? document.querySelector('.avatar-picker-orbit.is-floating[data-avatar-panel]') : null);
        const trigger = picker.querySelector('[data-avatar-trigger]');
        if (!picker.contains(event.target) && !panel?.contains(event.target) && !trigger?.contains(event.target)) {
            closeAvatarPicker(picker);
        }
    });
});

document.addEventListener('click', function (event) {
    const trigger = event.target.closest('[data-avatar-trigger]');
    if (!trigger) return;

    event.preventDefault();
    const picker = trigger.closest('.avatar-picker');
    if (!picker) return;

    document.querySelectorAll('.avatar-picker.is-open').forEach(function (otherPicker) {
        if (otherPicker !== picker) closeAvatarPicker(otherPicker);
    });

    const willOpen = !picker.classList.contains('is-open');
    if (willOpen) {
        openAvatarPicker(picker);
    } else {
        closeAvatarPicker(picker);
    }
});

window.addEventListener('resize', updateFloatingAvatarLayout);
if (window.visualViewport) {
    window.visualViewport.addEventListener('resize', updateFloatingAvatarLayout);
    window.visualViewport.addEventListener('scroll', updateFloatingAvatarLayout);
}

document.addEventListener('change', function (event) {
    const input = event.target.closest('.avatar-picker input[type="radio"]');
    const floatingInput = event.target.closest('.avatar-picker-orbit.is-floating input[type="radio"]');
    if (!input && !floatingInput) return;

    const activeInput = input || floatingInput;
    const picker = input?.closest('.avatar-picker') || activeAvatarPicker;
    const preview = picker ? picker.querySelector('[data-avatar-preview]') : null;
    const placeholder = picker ? picker.querySelector('[data-avatar-placeholder]') : null;
    const activePanel = activeInput.closest('[data-avatar-panel]');
    const centerPreview = activePanel ? activePanel.querySelector('[data-avatar-center-preview]') : null;
    const centerPlaceholder = activePanel ? activePanel.querySelector('[data-avatar-center-placeholder]') : null;
    const image = activeInput.nextElementSibling;

    if (preview && image) {
        preview.src = image.src;
        preview.hidden = false;
        preview.style.display = 'block';
    }

    if (centerPreview && image) {
        centerPreview.src = image.src;
        centerPreview.hidden = false;
        centerPreview.style.display = 'block';
    }

    if (placeholder) {
        placeholder.hidden = true;
        placeholder.style.display = 'none';
    }

    if (centerPlaceholder) {
        centerPlaceholder.hidden = true;
        centerPlaceholder.style.display = 'none';
    }

    if (picker) {
        picker.classList.remove('is-empty');
        closeAvatarPicker(picker);
    }
});
</script>
@endonce
