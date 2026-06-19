@php
    $iconName = $name ?? 'spark';
    $iconClass = $class ?? 'ui-svg';
    $icons = [
        'dashboard' => '<path d="M4 5h7v6H4z"/><path d="M13 5h7v4h-7z"/><path d="M13 11h7v8h-7z"/><path d="M4 13h7v6H4z"/>',
        'booking' => '<path d="M7 3v4"/><path d="M17 3v4"/><path d="M4 8h16"/><rect x="4" y="5" width="16" height="16" rx="3"/><path d="m8 14 2 2 5-5"/>',
        'payment' => '<rect x="3" y="6" width="18" height="12" rx="3"/><path d="M3 10h18"/><path d="M7 15h4"/>',
        'agent' => '<rect x="5" y="7" width="14" height="11" rx="4"/><path d="M12 7V4"/><circle cx="9" cy="12.5" r="1"/><circle cx="15" cy="12.5" r="1"/><path d="M9 16h6"/>',
        'history' => '<path d="M4 12a8 8 0 1 0 3-6"/><path d="M4 5v5h5"/><path d="M12 8v5l3 2"/>',
        'service' => '<path d="m14.7 6.3 3 3"/><path d="M3 21l5.8-1.5L19.5 8.8a2.1 2.1 0 0 0-3-3L5.8 16.5z"/><path d="M12 8 8 4"/><path d="M4 8l4-4"/>',
        'report' => '<path d="M5 20V4h14v16z"/><path d="M8 8h8"/><path d="M8 12h5"/><path d="M8 16h7"/>',
        'catalog' => '<path d="M4 7h16"/><path d="M6 7v13h12V7"/><path d="M9 7a3 3 0 0 1 6 0"/><path d="M9 12h6"/>',
        'trend' => '<path d="M4 18h16"/><path d="M6 15l4-4 3 3 5-7"/><path d="M18 7h-4"/><path d="M18 7v4"/>',
        'topup' => '<circle cx="12" cy="12" r="9"/><path d="M12 8v8"/><path d="M8 12h8"/>',
        'withdraw' => '<circle cx="12" cy="12" r="9"/><path d="M8 12h8"/><path d="m13 8 4 4-4 4"/>',
        'settings' => '<path d="M4 7h10"/><path d="M18 7h2"/><circle cx="16" cy="7" r="2"/><path d="M4 12h2"/><path d="M10 12h10"/><circle cx="8" cy="12" r="2"/><path d="M4 17h8"/><path d="M16 17h4"/><circle cx="14" cy="17" r="2"/>',
        'logout' => '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/>',
        'zeropay' => '<rect x="3" y="6" width="18" height="12" rx="4"/><path d="M8 12h4"/><path d="M14 10h3"/><path d="M14 14h3"/>',
        'bolt' => '<path fill="currentColor" stroke="none" d="M13.4 3 6 13h5.1l-1.4 8 8.3-11.2h-5.3z"/>',
        'login' => '<path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><path d="m10 17 5-5-5-5"/><path d="M15 12H3"/>',        'register' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M19 8v6"/><path d="M16 11h6"/>',
        'verify' => '<path d="M12 3 20 6v6c0 5-3.4 8-8 9-4.6-1-8-4-8-9V6z"/><path d="m8.5 12 2.2 2.2 4.8-5"/>',
        'home' => '<path d="M4 11 12 4l8 7"/><path d="M6 10v10h12V10"/><path d="M10 20v-5h4v5"/>',
        'shop' => '<path d="M6 7h12l-1 13H7z"/><path d="M9 7a3 3 0 0 1 6 0"/>',
        'profile' => '<circle cx="12" cy="8" r="4"/><path d="M5 21a7 7 0 0 1 14 0"/>',
        'gamepad' => '<path d="M6 10h12a3 3 0 0 1 3 3v3a3 3 0 0 1-5.2 2L14 16h-4l-1.8 2A3 3 0 0 1 3 16v-3a3 3 0 0 1 3-3z"/><path d="M8 13h3"/><path d="M9.5 11.5v3"/><path d="M16 13h.01"/><path d="M18 15h.01"/>',
        'close' => '<path d="M18 6 6 18"/><path d="M6 6l12 12"/>',
        'spark' => '<path d="m12 3 2.4 5.4 5.6.6-4.2 3.7 1.2 5.5-5-2.8-5 2.8 1.2-5.5L4 9l5.6-.6z"/>'
    ];
@endphp
<svg class="{{ $iconClass }}" viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $icons[$iconName] ?? $icons['spark'] !!}</svg>


