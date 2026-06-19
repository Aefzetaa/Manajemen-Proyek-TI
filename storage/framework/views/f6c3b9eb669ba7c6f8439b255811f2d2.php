<?php
    use App\Models\User;

    $roleSections = [
        'owner'    => ['label' => 'Owner',     'color' => '#7c3aed'],
        'cashier'  => ['label' => 'Kasir',     'color' => '#c36a16'],
        'mechanic' => ['label' => 'Mekanik',   'color' => '#2563eb'],
        'customer' => ['label' => 'Pelanggan', 'color' => '#168348'],
    ];

    $roleOrder = array_keys($roleSections);

    $grouped = User::query()
        ->get(['id', 'username', 'role'])
        ->groupBy('role')
        ->map(fn ($users) => $users->sortBy('username', SORT_NATURAL | SORT_FLAG_CASE)->values());

    $currentId = auth()->id();
    $openAddModal = session('_dqs_modal') === 'add';
?>

<div id="devQuickSwitch" class="dqs-root" aria-label="Dev quick switch akun">
    <div class="dqs-fab" id="dqsFab" role="button" tabindex="0" title="Ganti akun (dev)">
        <svg class="dqs-fab-icon-default" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
            <circle cx="9" cy="7" r="4"/>
            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
        </svg>

    </div>

    <div class="dqs-panel" id="dqsPanel" hidden>
        <div class="dqs-panel-head">
            <div>
                <span class="dqs-badge">DEV</span>
                <strong>Ganti Akun</strong>
            </div>
            <div class="dqs-head-actions">
                <span class="dqs-icon-btn" id="dqsOpenAdd" role="button" tabindex="0" title="Tambah akun" aria-label="Tambah akun">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
                </span>
                    <form method="POST" action="<?php echo e(route('dev.quick-switch.reset-all')); ?>" class="dqs-form-reset-all" id="dqsResetForm">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="dqs-icon-btn dqs-icon-reset" title="Reset semua aktivitas" aria-label="Reset semua aktivitas">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 12a9 9 0 1 1-2.64-6.36"/><path d="M21 3v6h-6"/>
                        </svg>
                    </button>
                </form>
                <span class="dqs-icon-btn" id="dqsClose" role="button" tabindex="0" title="Tutup" aria-label="Tutup panel">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M18 6L6 18M6 6l12 12"/></svg>
                </span>
            </div>
        </div>
        <p class="dqs-hint">Klik username untuk masuk.</p>

        <div class="dqs-body">
            <?php if($grouped->flatten()->isEmpty()): ?>
                <p class="dqs-empty">Belum ada akun. Tambah lewat ikon +.</p>
            <?php endif; ?>
            <?php $__currentLoopData = $roleOrder; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $users = $grouped->get($role);
                    if (! $users || $users->isEmpty()) {
                        continue;
                    }
                    $section = $roleSections[$role];
                ?>
                <section class="dqs-group" style="--section-color: <?php echo e($section['color']); ?>">
                    <header class="dqs-group-head">
                        <span class="dqs-group-dot" aria-hidden="true"></span>
                        <span class="dqs-group-title"><?php echo e($section['label']); ?></span>
                        <span class="dqs-group-count"><?php echo e($users->count()); ?></span>
                    </header>
                    <ul class="dqs-group-list">
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $isActive = $currentId === $u->id; ?>
                            <li class="dqs-row-wrap">
                                <?php if($isActive): ?>
                                    <div class="dqs-row is-active">
                                        <span class="dqs-row-user"><?php echo e($u->username); ?></span>
                                        <span class="dqs-row-badge">Aktif</span>
                                    </div>
                                <?php else: ?>
                                    <form method="POST" action="<?php echo e(route('dev.quick-switch.switch', $u)); ?>" class="dqs-form dqs-form-switch">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="dqs-row">
                                            <span class="dqs-row-user"><?php echo e($u->username); ?></span>
                                            <span class="dqs-row-go" aria-hidden="true">→</span>
                                        </button>
                                    </form>
                                <?php endif; ?>
                                  <form method="POST" action="<?php echo e(route('dev.quick-switch.destroy', $u)); ?>" class="dqs-form dqs-form-del"
                                      data-confirm="Hapus akun <?php echo e($u->username); ?>?">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="dqs-icon-btn dqs-icon-del" title="Hapus <?php echo e($u->username); ?>" aria-label="Hapus <?php echo e($u->username); ?>">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M19 6l-1 14H6L5 6"/>
                                        </svg>
                                    </button>
                                </form>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </section>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>


<div class="dqs-modal-overlay" id="dqsAddModal" hidden>
    <div class="dqs-modal-card" role="dialog" aria-labelledby="dqsAddTitle" aria-modal="true">
        <div class="dqs-modal-head">
            <div>
                <span class="dqs-badge">DEV</span>
                <h3 id="dqsAddTitle">Tambah Akun</h3>
            </div>
            <span class="dqs-icon-btn" id="dqsCloseAdd" role="button" tabindex="0" title="Tutup" aria-label="Tutup form">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </span>
        </div>

        <?php if($errors->any()): ?>
            <div class="dqs-errors">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div><?php echo e($err); ?></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('dev.quick-switch.store')); ?>" class="dqs-add-form" id="dqsAddForm">
            <?php echo csrf_field(); ?>
            <div class="dqs-avatars">
                <?php if (isset($component)) { $__componentOriginalf2fd01b0ef80303dbcd883ceaa433d1a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2fd01b0ef80303dbcd883ceaa433d1a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.avatar-picker','data' => ['name' => 'avatar','size' => 60,'placeholder' => true,'contained' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('avatar-picker'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'avatar','size' => 60,'placeholder' => true,'contained' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2fd01b0ef80303dbcd883ceaa433d1a)): ?>
<?php $attributes = $__attributesOriginalf2fd01b0ef80303dbcd883ceaa433d1a; ?>
<?php unset($__attributesOriginalf2fd01b0ef80303dbcd883ceaa433d1a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2fd01b0ef80303dbcd883ceaa433d1a)): ?>
<?php $component = $__componentOriginalf2fd01b0ef80303dbcd883ceaa433d1a; ?>
<?php unset($__componentOriginalf2fd01b0ef80303dbcd883ceaa433d1a); ?>
<?php endif; ?>
            </div>
            <div class="dqs-field-row">
                <label class="dqs-field">
                    <span>Nama</span>
                    <input type="text" name="name" value="<?php echo e(old('name')); ?>" required maxlength="255">
                </label>
                <label class="dqs-field">
                    <span>Username</span>
                    <input type="text" name="username" value="<?php echo e(old('username')); ?>" required maxlength="8" pattern="[a-zA-Z0-9_]+">
                </label>
            </div>
            <div class="dqs-field-row">
                <label class="dqs-field">
                    <span>Email</span>
                    <input type="email" name="email" value="<?php echo e(old('email')); ?>" required>
                </label>
                <label class="dqs-field">
                    <span>No. HP</span>
                    <input type="text" name="phone" value="<?php echo e(old('phone')); ?>" required>
                </label>
            </div>
            <label class="dqs-field dqs-field-role">
                <span>Role</span>
                <select name="role" class="dqs-select" required>
                    <option value="" disabled <?php if(! old('role')): echo 'selected'; endif; ?>>— Pilih role —</option>
                    <?php $__currentLoopData = User::ROLES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($key); ?>" <?php if(old('role') === $key): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </label>
            <div class="dqs-field-row">
                <label class="dqs-field">
                    <span>Password</span>
                    <input type="password" name="password" required minlength="1">
                </label>
                <label class="dqs-field">
                    <span>Ulangi Password</span>
                    <input type="password" name="password_confirmation" required minlength="1">
                </label>
            </div>
            <div class="dqs-modal-foot">
                <button type="submit" class="dqs-btn-confirm">Konfirmasi</button>
            </div>
        </form>
    </div>
</div>


<div class="dqs-reset-overlay" id="dqsResetModal" hidden>
    <div class="dqs-reset-card" role="dialog" aria-labelledby="dqsResetTitle" aria-modal="true">
        <div class="dqs-reset-mark" aria-hidden="true">
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 9v4"/><path d="M12 17h.01"/><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"/>
            </svg>
        </div>
        <div class="dqs-reset-copy">
            <span class="dqs-badge dqs-badge-danger">RESET DEV</span>
            <h3 id="dqsResetTitle">Reset semua aktivitas?</h3>
            <p>Data demo akan dikosongkan agar proyek kembali bersih. Akun login tetap dipertahankan.</p>
            <div class="dqs-reset-list" aria-label="Data yang akan dibersihkan">
                <span>Booking, servis, pembayaran, pesan</span>
                <span>Saldo, riwayat akun, dan data transaksi demo</span>
                <span>Data katalog akan dikembalikan ke default demo</span>
            </div>
        </div>
        <div class="dqs-reset-actions">
            <button type="button" class="dqs-btn-ghost" id="dqsCancelReset">Batal</button>
            <button type="button" class="dqs-btn-danger" id="dqsConfirmReset">Reset Sekarang</button>
        </div>
    </div>
</div>



<div class="dqs-cheat-overlay" id="dqsCheatModal" hidden>
    <div class="dqs-cheat-card" role="dialog" aria-labelledby="dqsCheatTitle" aria-modal="true">
        <div class="dqs-modal-head">
            <div>
                <span class="dqs-badge">DEV</span>
                <h3 id="dqsCheatTitle">Masukan Kode</h3>
            </div>
            <span class="dqs-icon-btn" id="dqsCloseCheat" role="button" tabindex="0" title="Tutup" aria-label="Tutup kode cheat">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </span>
        </div>

        <form class="dqs-cheat-form" id="dqsCheatForm">
            <label class="dqs-field">
                <span>Kode</span>
                <input type="password" id="dqsCheatInput" autocomplete="off" required>
            </label>
            <p class="dqs-cheat-message" id="dqsCheatMessage" aria-live="polite"></p>
            <button type="submit" class="dqs-btn-confirm">Konfirmasi</button>
        </form>
    </div>
</div>

<style>


    .dqs-root.dqs-dragging {
        transition: none !important;
        cursor: grabbing !important;
    }
    .dqs-root.dqs-dragging .dqs-fab {
        transition: none !important;
        cursor: grabbing !important;
    }

    .dqs-root,
    .dqs-modal-overlay,
    .dqs-cheat-overlay,
    .dqs-reset-overlay {
        --dqs-panel: #141c20;
        --dqs-panel-elevated: #1a2429;
        --dqs-ink: #edf7f5;
        --dqs-muted: #94a3a8;
        --dqs-line: rgba(148, 163, 168, 0.22);
        --dqs-primary: #2dd4bf;
        --dqs-primary-dark: #14b8a6;
        --dqs-danger: #f87171;
        --dqs-shadow: 0 20px 50px rgba(0, 0, 0, 0.55);
        --dqs-input-bg: #11181c;
        --dqs-input-bg-hover: #161f24;
        color-scheme: dark;
    }
    .dqs-root.is-cheat-hidden {
        display: none !important;
    }
    .dqs-root {
        position: fixed;
        z-index: 99999999;
        right: 34px;
        bottom: 116px;
        touch-action: none;
        font-family: Inter, ui-sans-serif, system-ui, sans-serif;
        color: var(--dqs-ink);
        font-size: 15px;
    }
    .dqs-icon-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        border-radius: 12px;
        border: none;
        background: transparent;
        color: var(--dqs-muted);
        cursor: pointer;
        padding: 0;
        transition: background 0.15s, color 0.15s, transform 0.12s;
        flex-shrink: 0;
    }
    span.dqs-icon-btn:hover,
    button.dqs-icon-btn:hover {
        background: var(--dqs-line);
        color: var(--dqs-ink);
    }
    .dqs-icon-del:hover { color: var(--dqs-danger); background: color-mix(in srgb, var(--dqs-danger) 14%, transparent); }
    .dqs-form-reset-all { margin: 0; display: inline-flex; }
    .dqs-icon-reset:hover { color: var(--dqs-primary); background: color-mix(in srgb, var(--dqs-primary) 14%, transparent); }
    .dqs-fab {
        width: 68px;
        height: 68px;
        border-radius: 20px;
        background: linear-gradient(135deg, var(--dqs-primary-dark), #6366f1);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: grab;
        box-shadow: var(--dqs-shadow);
        border: 1px solid color-mix(in srgb, #fff 25%, transparent);
        transition: transform 0.2s ease;
        user-select: none;
    }
    .dqs-fab:hover { transform: scale(1.06); }
    .dqs-fab svg {
        width: 28px;
        height: 28px;
    }
    .dqs-fab:active { cursor: grabbing; }
    .dqs-head-actions { display: flex; gap: 2px; align-items: center; }
    .dqs-panel {
        position: absolute;
        width: min(360px, calc(100vw - 28px));
        max-height: min(600px, 78vh);
        background: var(--dqs-panel);
        color: var(--dqs-ink);
        border: 1px solid var(--dqs-line);
        border-radius: 18px;
        box-shadow: var(--dqs-shadow);
        backdrop-filter: blur(14px);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        animation: dqsPop 0.22s ease-out;
    }
    .dqs-root.dqs-panel-up .dqs-panel {
        right: 0;
        bottom: calc(100% + 14px);
        left: auto;
        top: auto;
    }
    .dqs-root.dqs-panel-down .dqs-panel {
        right: 0;
        top: calc(100% + 14px);
        left: auto;
        bottom: auto;
    }
    .dqs-root.dqs-panel-right .dqs-panel {
        left: calc(100% + 14px);
        top: var(--dqs-panel-offset-y, 0px);
        right: auto;
        bottom: auto;
    }
    .dqs-root.dqs-panel-left .dqs-panel {
        right: calc(100% + 14px);
        top: var(--dqs-panel-offset-y, 0px);
        left: auto;
        bottom: auto;
    }
    .dqs-root.dqs-panel-center .dqs-panel {
        left: var(--dqs-panel-offset-x, 0px);
        top: var(--dqs-panel-offset-y, 0px);
        right: auto;
        bottom: auto;
    }
    .dqs-panel[hidden] { display: none !important; }
    @keyframes dqsPop {
        from { opacity: 0; transform: translateY(8px) scale(0.97); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
    .dqs-panel-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 14px 8px 18px;
        gap: 8px;
        flex-shrink: 0;
    }
    .dqs-panel-head strong { font-size: 17px; display: block; }
    .dqs-badge {
        display: inline-block;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.06em;
        padding: 2px 7px;
        border-radius: 999px;
        background: color-mix(in srgb, var(--dqs-primary) 18%, transparent);
        color: var(--dqs-primary);
        margin-bottom: 4px;
    }
    .dqs-hint {
        margin: 0 18px 10px;
        font-size: 13px;
        color: var(--dqs-muted);
        flex-shrink: 0;
    }
    .dqs-body { overflow-y: auto; padding: 5px 12px 14px; }
    .dqs-empty { font-size: 13px; color: var(--dqs-muted); padding: 8px 6px; margin: 0; }
    .dqs-group + .dqs-group {
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid var(--dqs-line);
    }
    .dqs-group-head {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 3px 6px 9px;
    }
    .dqs-group-dot { width: 10px; height: 10px; border-radius: 50%; background: var(--section-color); }
    .dqs-group-title {
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: var(--section-color);
        flex: 1;
    }
    .dqs-group-count {
        font-size: 11px;
        font-weight: 700;
        color: var(--dqs-muted);
        background: color-mix(in srgb, var(--section-color) 12%, transparent);
        padding: 2px 7px;
        border-radius: 999px;
    }
    .dqs-group-list { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 4px; }
    .dqs-row-wrap {
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .dqs-form { margin: 0; }
    .dqs-form-switch { flex: 1; min-width: 0; }
    .dqs-row {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding: 11px 14px;
        border-radius: 12px;
        border: 1px solid transparent;
        background: var(--dqs-input-bg);
        text-align: left;
        cursor: pointer;
        font: inherit;
        color: inherit;
        transition: background 0.15s, border-color 0.15s;
    }
    button.dqs-row:hover {
        background: color-mix(in srgb, var(--section-color) 12%, transparent);
        border-color: color-mix(in srgb, var(--section-color) 28%, transparent);
    }
    .dqs-row.is-active {
        flex: 1;
        background: color-mix(in srgb, var(--section-color) 16%, transparent);
        border-color: color-mix(in srgb, var(--section-color) 38%, transparent);
    }
    .dqs-row-user { font-size: 15px; font-weight: 650; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .dqs-row-go { font-size: 16px; color: var(--dqs-muted); opacity: 0.5; }
    button.dqs-row:hover .dqs-row-go { opacity: 1; color: var(--section-color); }
    .dqs-row-badge { font-size: 11px; font-weight: 800; text-transform: uppercase; color: var(--section-color); }

    .dqs-modal-overlay,
    .dqs-cheat-overlay,
    .dqs-reset-overlay {
        position: fixed;
        inset: 0;
        z-index: 100000000;
        background: rgba(0, 0, 0, 0.72);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        font-family: Inter, ui-sans-serif, system-ui, sans-serif;
        color: var(--dqs-ink);
        animation: dqsPop 0.2s ease-out;
    }
    .dqs-modal-overlay[hidden],
    .dqs-cheat-overlay[hidden],
    .dqs-reset-overlay[hidden] { display: none !important; }
    .dqs-modal-card,
    .dqs-cheat-card,
    .dqs-reset-card {
        width: 100%;
        max-width: 500px;
        max-height: 90vh;
        overflow: hidden;
        background: var(--dqs-panel);
        color: var(--dqs-ink);
        border: 1px solid var(--dqs-line);
        border-radius: 18px;
        box-shadow: var(--dqs-shadow);
        padding: 22px 24px 24px;
    }
    .dqs-cheat-card {
        max-width: 420px;
    }
    .dqs-modal-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 14px;
        gap: 10px;
    }
    .dqs-modal-head h3 { margin: 0; font-size: 20px; }
    .dqs-cheat-card .dqs-modal-head {
        position: relative;
        min-height: 38px;
    }
    .dqs-cheat-card .dqs-modal-head > div:first-child {
        flex: 1;
    }
    #dqsCheatTitle {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        width: max-content;
        text-align: center;
    }
    .dqs-errors {
        background: color-mix(in srgb, var(--dqs-danger) 12%, transparent);
        color: var(--dqs-danger);
        border-radius: 10px;
        padding: 10px 12px;
        font-size: 13px;
        margin-bottom: 12px;
        line-height: 1.45;
    }
    .dqs-avatars { display: flex; justify-content: center; gap: 10px; margin-bottom: 18px; flex-wrap: wrap; position: relative; min-height: 70px; }
    .dqs-av-label { cursor: pointer; }
    .dqs-av-img {
        width: 60px;
        height: 60px;
        border-radius: 14px;
        border: 2px solid transparent;
        object-fit: cover;
        transition: border-color 0.15s;
    }
    .dqs-av-label input:checked + .dqs-av-img { border-color: var(--dqs-primary); }
    .dqs-field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
    @media (max-width: 400px) { .dqs-field-row { grid-template-columns: 1fr; } }
    .dqs-field { display: flex; flex-direction: column; gap: 5px; margin-bottom: 12px; }
    .dqs-field span { font-size: 12px; font-weight: 700; color: var(--dqs-muted); text-transform: uppercase; letter-spacing: 0.04em; }
    .dqs-field input,
    .dqs-field select.dqs-select {
        width: 100%;
        padding: 12px 14px;
        border-radius: 12px;
        border: 1px solid var(--dqs-line);
        background-color: var(--dqs-input-bg);
        color: var(--dqs-ink);
        font: inherit;
        font-size: 15px;
        font-weight: 500;
        transition: border-color 0.15s, background-color 0.15s, box-shadow 0.15s;
    }
    .dqs-field input::placeholder { color: var(--dqs-muted); opacity: 0.85; }
    .dqs-field input:hover,
    .dqs-field select.dqs-select:hover {
        background-color: var(--dqs-input-bg-hover);
        border-color: color-mix(in srgb, var(--dqs-primary) 40%, var(--dqs-line));
    }
    .dqs-field input:focus,
    .dqs-field select.dqs-select:focus {
        outline: none;
        border-color: var(--dqs-primary);
        box-shadow: 0 0 0 3px color-mix(in srgb, var(--dqs-primary) 25%, transparent);
    }
    .dqs-field select.dqs-select {
        padding-right: 38px;
        cursor: pointer;
        appearance: none;
        -webkit-appearance: none;
        background-image: none;
    }
    .dqs-field select.dqs-select:invalid { color: var(--dqs-muted); }
    .dqs-field select.dqs-select option {
        background: #141d21;
        color: var(--dqs-ink);
    }
    .dqs-field select.dqs-select option:checked {
        background: #1a2a30;
        color: var(--dqs-primary);
    }
    .dqs-field select.dqs-select option[value=""] {
        color: var(--dqs-muted);
        font-style: italic;
    }
    .dqs-modal-foot {
        display: flex;
        justify-content: stretch;
        margin-top: 8px;
        padding-top: 4px;
    }
    .dqs-btn-confirm {
        width: 100%;
        padding: 14px 22px;
        border: none;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--dqs-primary-dark), #0d9488);
        color: #042f2e;
        font: inherit;
        font-size: 16px;
        font-weight: 700;
        letter-spacing: 0.02em;
        cursor: pointer;
        transition: transform 0.15s, box-shadow 0.15s, filter 0.15s;
        box-shadow: 0 4px 18px color-mix(in srgb, var(--dqs-primary) 35%, transparent);
    }
    .dqs-btn-confirm:hover {
        filter: brightness(1.08);
        transform: translateY(-1px);
        box-shadow: 0 6px 22px color-mix(in srgb, var(--dqs-primary) 45%, transparent);
    }
    .dqs-btn-confirm:active { transform: translateY(0); }
    .dqs-cheat-form {
        margin: 0;
    }
    .dqs-cheat-message {
        min-height: 20px;
        margin: -2px 0 12px;
        color: var(--dqs-danger);
        font-size: 13px;
        font-weight: 700;
    }
    .dqs-cheat-message.is-success {
        color: var(--dqs-primary);
    }
    .dqs-reset-card {
        max-width: 470px;
        display: grid;
        grid-template-columns: 64px 1fr;
        gap: 16px;
        position: relative;
        overflow: hidden;
        border-color: color-mix(in srgb, var(--dqs-danger) 34%, var(--dqs-line));
        background:
            radial-gradient(circle at 20% 0%, color-mix(in srgb, var(--dqs-danger) 18%, transparent), transparent 36%),
            var(--dqs-panel);
    }
    .dqs-reset-mark {
        width: 58px;
        height: 58px;
        border-radius: 18px;
        display: grid;
        place-items: center;
        color: #fecaca;
        background: color-mix(in srgb, var(--dqs-danger) 18%, transparent);
        border: 1px solid color-mix(in srgb, var(--dqs-danger) 34%, transparent);
        box-shadow: 0 14px 38px color-mix(in srgb, var(--dqs-danger) 20%, transparent);
    }
    .dqs-badge-danger {
        color: #fecaca;
        background: color-mix(in srgb, var(--dqs-danger) 18%, transparent);
    }
    .dqs-reset-copy h3 {
        margin: 3px 0 8px;
        font-size: 23px;
        letter-spacing: -0.03em;
    }
    .dqs-reset-copy p {
        margin: 0;
        color: var(--dqs-muted);
        line-height: 1.55;
        font-size: 14px;
    }
    .dqs-reset-list {
        display: grid;
        gap: 8px;
        margin-top: 14px;
        padding: 12px;
        border-radius: 14px;
        border: 1px solid var(--dqs-line);
        background: rgba(0, 0, 0, 0.16);
        color: #dbe8e6;
        font-size: 13px;
    }
    .dqs-reset-list span::before {
        content: '';
        display: inline-block;
        width: 7px;
        height: 7px;
        margin-right: 8px;
        border-radius: 999px;
        background: var(--dqs-danger);
        box-shadow: 0 0 12px color-mix(in srgb, var(--dqs-danger) 70%, transparent);
    }
    .dqs-reset-actions {
        grid-column: 1 / -1;
        display: grid;
        grid-template-columns: 1fr 1.15fr;
        gap: 10px;
        margin-top: 4px;
    }
    .dqs-btn-ghost,
    .dqs-btn-danger {
        min-height: 48px;
        border-radius: 13px;
        font: inherit;
        font-weight: 800;
        cursor: pointer;
        transition: transform 0.14s, filter 0.14s, border-color 0.14s;
    }
    .dqs-btn-ghost {
        color: var(--dqs-ink);
        background: var(--dqs-input-bg);
        border: 1px solid var(--dqs-line);
    }
    .dqs-btn-danger {
        color: #fff7f7;
        background: linear-gradient(135deg, #ef4444, #b91c1c);
        border: 1px solid color-mix(in srgb, #fff 18%, transparent);
        box-shadow: 0 14px 34px color-mix(in srgb, var(--dqs-danger) 32%, transparent);
    }
    .dqs-btn-ghost:hover,
    .dqs-btn-danger:hover {
        transform: translateY(-1px);
        filter: brightness(1.06);
    }
    @media (max-width: 520px) {
        .dqs-reset-card { grid-template-columns: 1fr; }
        .dqs-reset-actions { grid-template-columns: 1fr; }
    }
</style>

<script>
(function () {
    const root = document.getElementById('devQuickSwitch');
    const fab = document.getElementById('dqsFab');
    const panel = document.getElementById('dqsPanel');
    const closeBtn = document.getElementById('dqsClose');
    const addModal = document.getElementById('dqsAddModal');
    const openAdd = document.getElementById('dqsOpenAdd');
    const closeAdd = document.getElementById('dqsCloseAdd');
    const addForm = document.getElementById('dqsAddForm');
    
    const cheatModal = document.getElementById('dqsCheatModal');
    const resetForm = document.getElementById('dqsResetForm');
    const resetModal = document.getElementById('dqsResetModal');
    const cancelReset = document.getElementById('dqsCancelReset');
    const confirmReset = document.getElementById('dqsConfirmReset');
    const closeCheat = document.getElementById('dqsCloseCheat');
    const cheatForm = document.getElementById('dqsCheatForm');
    const cheatInput = document.getElementById('dqsCheatInput');
    const cheatMessage = document.getElementById('dqsCheatMessage');
    const storageKey = 'dev_quick_switch_pos';
    const visibilityKey = 'dev_quick_switch_visibility';

    // Auto-fill password confirmation (Dev Quick Switch exclusive)
    if (addForm) {
        const passwordInput = addForm.querySelector('input[name="password"]');
        const confirmInput = addForm.querySelector('input[name="password_confirmation"]');
        if (passwordInput && confirmInput) {
            passwordInput.addEventListener('input', () => {
                confirmInput.value = passwordInput.value;
            });
        }
    }

    function loadPos() {
        try {
            const raw = localStorage.getItem(storageKey);
            if (!raw) return;
            const { x, y } = JSON.parse(raw);
            if (Number.isFinite(x) && Number.isFinite(y)) {
                root.style.left = x + 'px';
                root.style.top = y + 'px';
                root.style.right = 'auto';
                root.style.bottom = 'auto';
                keepInViewport();
            }
        } catch (_) {}
    }

    function savePos() {
        keepInViewport();
        const rect = root.getBoundingClientRect();
        const scale = getZoomScale();
        localStorage.setItem(storageKey, JSON.stringify({
            x: Math.round(rect.left / scale),
            y: Math.round(rect.top / scale),
        }));
    }

    function keepInViewport() {
        const scale = getZoomScale();
        const rect = root.getBoundingClientRect();
        const margin = 14;
        const width = rect.width || 68;
        const height = rect.height || 68;
        const currentLeft = root.offsetLeft || ((window.innerWidth / scale) - width - 34);
        const currentTop = root.offsetTop || ((window.innerHeight / scale) - height - 116);
        const maxLeft = Math.max(margin, (window.innerWidth / scale) - width - margin);
        const maxTop = Math.max(margin, (window.innerHeight / scale) - height - margin);
        const nextLeft = Math.min(Math.max(currentLeft, margin), maxLeft);
        const nextTop = Math.min(Math.max(currentTop, margin), maxTop);

        root.style.left = nextLeft + 'px';
        root.style.top = nextTop + 'px';
        root.style.right = 'auto';
        root.style.bottom = 'auto';
    }

    function clampNumber(value, min, max) {
        return Math.min(Math.max(value, min), max);
    }

    function clearPanelPlacement() {
        root.classList.remove('dqs-panel-up', 'dqs-panel-down', 'dqs-panel-right', 'dqs-panel-left', 'dqs-panel-center');
        root.style.removeProperty('--dqs-panel-offset-x');
        root.style.removeProperty('--dqs-panel-offset-y');
    }

    function positionPanel() {
        if (panel.hidden) return;

        const margin = 14;
        const gap = 14;
        const viewportWidth = window.innerWidth;
        const viewportHeight = window.innerHeight;
        const scale = getZoomScale();
        const triggerRect = root.getBoundingClientRect();
        const panelWidth = panel.offsetWidth || 360;
        const panelHeight = Math.min(panel.offsetHeight || 420, viewportHeight - (margin * 2));
        const spaceRight = viewportWidth - triggerRect.right - margin;
        const spaceLeft = triggerRect.left - margin;
        const spaceBottom = viewportHeight - triggerRect.bottom - margin;
        const spaceTop = triggerRect.top - margin;
        const placements = [
            { name: 'left', space: spaceLeft, fits: spaceLeft >= panelWidth + gap },
            { name: 'right', space: spaceRight, fits: spaceRight >= panelWidth + gap },
            { name: 'down', space: spaceBottom, fits: spaceBottom >= panelHeight + gap },
            { name: 'up', space: spaceTop, fits: spaceTop >= panelHeight + gap },
        ];
        const placement = placements.find((item) => item.fits) || placements.sort((a, b) => b.space - a.space)[0];
        const rootWidth = triggerRect.width || 68;
        const rootHeight = triggerRect.height || 68;
        const centeredPanelLeft = triggerRect.left + (rootWidth / 2) - (panelWidth / 2);
        const centeredPanelTop = triggerRect.top + (rootHeight / 2) - (panelHeight / 2);

        clearPanelPlacement();
        root.classList.add(`dqs-panel-${placement.name}`);

        if (placement.name === 'left' || placement.name === 'right') {
            const desiredTop = clampNumber(centeredPanelTop, margin, viewportHeight - panelHeight - margin);
            root.style.setProperty('--dqs-panel-offset-y', `${Math.round((desiredTop - triggerRect.top) / scale)}px`);
            return;
        }

        if (placement.name === 'up' || placement.name === 'down') {
            const desiredLeft = clampNumber(triggerRect.right - panelWidth, margin, viewportWidth - panelWidth - margin);
            root.style.setProperty('--dqs-panel-offset-x', `${Math.round((desiredLeft - triggerRect.left) / scale)}px`);
            root.classList.remove(`dqs-panel-${placement.name}`);
            root.classList.add('dqs-panel-center');
            root.style.setProperty(
                '--dqs-panel-offset-y',
                placement.name === 'down'
                    ? `${Math.round((rootHeight + gap) / scale)}px`
                    : `${Math.round((-panelHeight - gap) / scale)}px`
            );
        }
    }

    loadPos();
    if (!root.style.left) {
        root.style.right = '34px';
        root.style.bottom = '116px';
    }

    function setPanelOpen(open) {
        root.classList.toggle('is-open', open);
        panel.hidden = !open;
        if (open) positionPanel();
        if (!open) clearPanelPlacement();
        fab.setAttribute('aria-expanded', open ? 'true' : 'false');
    }

    function applyQuickSwitchVisibility(isVisible) {
        root.classList.toggle('is-cheat-hidden', !isVisible);
        if (!isVisible) {
            setPanelOpen(false);
        }
        localStorage.setItem(visibilityKey, isVisible ? 'shown' : 'hidden');
    }

    function setCheatOpen(open) {
        cheatModal.hidden = !open;
        if (open) {
            cheatInput.value = '';
            cheatMessage.textContent = '';
            cheatMessage.classList.remove('is-success');
            setTimeout(() => cheatInput.focus(), 60);
        }
    }

    applyQuickSwitchVisibility(localStorage.getItem(visibilityKey) !== 'hidden');

    

    function setAddOpen(open) {
        addModal.hidden = !open;
        if (open) setPanelOpen(false);
    }

    function setResetOpen(open) {
        resetModal.hidden = !open;
        if (open) setPanelOpen(false);
    }

    function bindIcon(el, fn) {
        el.addEventListener('click', fn);
        el.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); fn(); }
        });
    }

    bindIcon(openAdd, () => setAddOpen(true));
    bindIcon(closeAdd, () => setAddOpen(false));
    bindIcon(closeBtn, () => setPanelOpen(false));
    
    bindIcon(closeCheat, () => setCheatOpen(false));
    bindIcon(cancelReset, () => setResetOpen(false));

    addModal.addEventListener('click', (e) => {
        if (e.target === addModal) setAddOpen(false);
    });

    

    cheatModal.addEventListener('click', (e) => {
        if (e.target === cheatModal) setCheatOpen(false);
    });

    resetModal.addEventListener('click', (e) => {
        if (e.target === resetModal) setResetOpen(false);
    });

    resetForm.addEventListener('submit', (e) => {
        if (resetForm.dataset.confirmed === '1') return;
        e.preventDefault();
        setResetOpen(true);
    });

    confirmReset.addEventListener('click', () => {
        resetForm.dataset.confirmed = '1';
        confirmReset.disabled = true;
        confirmReset.textContent = 'Mereset...';
        resetForm.submit();
    });

    cheatForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const code = cheatInput.value.trim();

        if (code === 'Aefzetaa') {
            applyQuickSwitchVisibility(true);
            cheatMessage.textContent = 'Dev Quick Switch ditampilkan.';
            cheatMessage.classList.add('is-success');
            setTimeout(() => setCheatOpen(false), 450);
            return;
        }

        if (code === 'Nwraaq') {
            applyQuickSwitchVisibility(false);
            cheatMessage.textContent = 'Dev Quick Switch disembunyikan.';
            cheatMessage.classList.add('is-success');
            setTimeout(() => setCheatOpen(false), 450);
            return;
        }

        cheatMessage.textContent = 'Kode tidak valid.';
        cheatMessage.classList.remove('is-success');
        cheatInput.select();
    });

    fab.addEventListener('click', () => {
        if (fab.dataset.dragged === '1') { fab.dataset.dragged = '0'; return; }
        setPanelOpen(panel.hidden);
    });
    fab.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); setPanelOpen(panel.hidden); }
    });

    document.addEventListener('click', (e) => {
        if (!root.contains(e.target) && !addModal.contains(e.target) && !cheatModal.contains(e.target)) setPanelOpen(false);
    });

    document.addEventListener('keydown', (e) => {
        if (e.ctrlKey && (e.key === '/' || e.code === 'Slash')) {
            e.preventDefault();
            setCheatOpen(true);
            return;
        }

        if (e.key === 'Escape') {
            setCheatOpen(false);
            setResetOpen(false);
        }
    });

    let dragging = false, moved = false, startX = 0, startY = 0, startLeft = 0, startTop = 0;

    function getZoomScale() {
        const htmlZoom = getComputedStyle(document.documentElement).zoom;
        const bodyZoom = getComputedStyle(document.body).zoom;
        
        let scale = 1;
        if (htmlZoom && htmlZoom !== 'normal') {
            const val = parseFloat(htmlZoom);
            scale = htmlZoom.includes('%') ? val / 100 : val;
        } else if (bodyZoom && bodyZoom !== 'normal') {
            const val = parseFloat(bodyZoom);
            scale = bodyZoom.includes('%') ? val / 100 : val;
        }
        return scale || 1;
    }

    function onPointerDown(e) {
        if (e.button !== 0) return;
        dragging = true; moved = false; fab.dataset.dragged = '0';
        root.classList.add('dqs-dragging');
        root.style.transition = 'none'; // disable transitions while dragging
        
        startX = e.clientX; 
        startY = e.clientY;
        
        startLeft = root.offsetLeft; 
        startTop = root.offsetTop;
        
        fab.setPointerCapture(e.pointerId);
    }
    function onPointerMove(e) {
        if (!dragging) return;
        const scale = getZoomScale();
        const dx = (e.clientX - startX) / scale;
        const dy = (e.clientY - startY) / scale;
        
        if (Math.abs(dx) > 4 || Math.abs(dy) > 4) moved = true;
        
        if (moved) {
            const rect = root.getBoundingClientRect();
            const margin = 14;
            const width = (rect.width || 68) / scale;
            const height = (rect.height || 68) / scale;
            const maxLeft = Math.max(margin, (window.innerWidth / scale) - width - margin);
            const maxTop = Math.max(margin, (window.innerHeight / scale) - height - margin);
            const nextLeft = clampNumber(startLeft + dx, margin, maxLeft);
            const nextTop = clampNumber(startTop + dy, margin, maxTop);

            root.style.right = 'auto'; 
            root.style.bottom = 'auto';
            root.style.left = nextLeft + 'px';
            root.style.top = nextTop + 'px';
            positionPanel();
        }
    }
    function onPointerUp(e) {
        if (!dragging) return;
        dragging = false;
        root.classList.remove('dqs-dragging');
        root.style.transition = ''; // restore transitions
        fab.releasePointerCapture(e.pointerId);
        if (moved) {
            fab.dataset.dragged = '1';
            savePos();
            positionPanel();
        }
    }
    fab.addEventListener('pointerdown', onPointerDown);
    fab.addEventListener('pointermove', onPointerMove);
    fab.addEventListener('pointerup', onPointerUp);
    fab.addEventListener('pointercancel', onPointerUp);
    window.addEventListener('resize', () => {
        keepInViewport();
        positionPanel();
        savePos();
    });

    <?php if($openAddModal): ?>
    setAddOpen(true);
    <?php endif; ?>
})();
</script>
<?php /**PATH C:\laragon\www\ProyekTI\resources\views/components/dev-quick-switch.blade.php ENDPATH**/ ?>