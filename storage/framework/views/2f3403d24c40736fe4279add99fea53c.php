<?php $__env->startSection('title', 'Akun'); ?>
<?php $__env->startSection('eyebrow', 'Manajemen Profil'); ?>

<?php $__env->startSection('content'); ?>
<div class="grid grid-2">
    <div class="stack">
        <section class="panel">
            <h2>Profil & Keamanan</h2>
            <p class="muted" style="margin-bottom:20px; font-size:14px;">Perbarui informasi pribadi dan password akun Anda.</p>
            
            <form method="POST" action="<?php echo e(route('account.update')); ?>" class="stack">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>
                
                <div class="field">
                    <label>Role / Peran</label>
                    <input type="text" value="<?php echo e($user->roleLabel()); ?>" disabled style="background:var(--bg); cursor:not-allowed;">
                    <small class="muted">Role tidak dapat diubah setelah pendaftaran.</small>
                </div>
                
                <div class="field">
                    <label>Pilih Avatar Baru</label>
                    <?php if (isset($component)) { $__componentOriginalf2fd01b0ef80303dbcd883ceaa433d1a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2fd01b0ef80303dbcd883ceaa433d1a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.avatar-picker','data' => ['name' => 'avatar','selected' => old('avatar', $user->avatar),'size' => 62]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('avatar-picker'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'avatar','selected' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('avatar', $user->avatar)),'size' => 62]); ?>
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

                <div class="field">
                    <label for="username">Username</label>
                    <input id="username" name="username" type="text" value="<?php echo e(old('username', $user->username)); ?>" required maxlength="8" pattern="[a-zA-Z0-9_]+" title="Maksimal 8 karakter tanpa spasi">
                </div>

                <div class="field">
                    <label for="name">Nama Lengkap</label>
                    <input id="name" name="name" type="text" value="<?php echo e(old('name', $user->name)); ?>" required>
                </div>
                
                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" value="<?php echo e(old('email', $user->email)); ?>" required>
                </div>
                
                <div class="field">
                    <label for="phone">Nomor HP</label>
                    <input id="phone" name="phone" type="text" value="<?php echo e(old('phone', $user->phone)); ?>" required>
                </div>
                
                <hr style="border:0; border-top:1px solid var(--line); margin: 10px 0;">
                
                <p class="muted" style="font-size:13px; margin:0;">Kosongkan bagian password jika tidak ingin mengubah password.</p>
                
                <div class="field">
                    <label for="current_password">Password Saat Ini</label>
                    <input id="current_password" name="current_password" type="password">
                </div>
                
                <div class="field">
                    <label for="password">Password Baru</label>
                    <input id="password" name="password" type="password" minlength="8">
                </div>
                
                <div class="field">
                    <label for="password_confirmation">Konfirmasi Password Baru</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" minlength="8">
                </div>
                
                <div style="margin-top:10px;">
                    <button class="button" type="submit">Simpan Perubahan</button>
                </div>
            </form>
        </section>

        
        <?php if(in_array(auth()->user()->role, ['mechanic', 'cashier', 'owner'])): ?>
        <section class="panel">
            <div style="display:flex; align-items:center; gap:10px; margin-bottom:15px;">
                <span style="font-size:20px;">🔒</span>
                <h2 style="margin:0;">Ubah Pin</h2>
                <?php if(! auth()->user()->withdraw_pin): ?>
                    <span style="font-size:11px; background:var(--warning, #f59e0b); color:#000; padding:2px 8px; border-radius:20px; font-weight:600;">Belum Diatur</span>
                <?php else: ?>
                    <span style="font-size:11px; background:var(--success, #10b981); color:#fff; padding:2px 8px; border-radius:20px; font-weight:600;">Sudah Diatur</span>
                <?php endif; ?>
            </div>

            <form method="POST" action="<?php echo e(route('account.update')); ?>" class="stack">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>

                
                <input type="hidden" name="name" value="<?php echo e(auth()->user()->name); ?>">
                <input type="hidden" name="username" value="<?php echo e(auth()->user()->username); ?>">
                <input type="hidden" name="email" value="<?php echo e(auth()->user()->email); ?>">
                <input type="hidden" name="phone" value="<?php echo e(auth()->user()->phone); ?>">

                <div class="field">
                    <label for="current_withdraw_pin">Masukkan Pin Terkini</label>
                    <input id="current_withdraw_pin" name="current_withdraw_pin" type="password"
                           inputmode="numeric" maxlength="4" placeholder="<?php echo e(auth()->user()->withdraw_pin ? 'Masukkan PIN lama' : 'Masukkan Pin Default'); ?>">
                    <?php $__errorArgs = ['current_withdraw_pin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="error-text"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="field">
                    <label for="withdraw_pin">Masukkan Pin Baru</label>
                    <input id="withdraw_pin" name="withdraw_pin" type="password"
                           inputmode="numeric" maxlength="4" placeholder="Masukkan 4 digit PIN baru">
                    <?php $__errorArgs = ['withdraw_pin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="error-text"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="field">
                    <label for="withdraw_pin_confirmation">Konfirmasi PIN Baru</label>
                    <input id="withdraw_pin_confirmation" name="withdraw_pin_confirmation" type="password"
                           inputmode="numeric" maxlength="4" placeholder="Ulangi PIN baru">
                </div>

                <div style="margin-top:10px;">
                    <button class="button" type="submit">Konfirmasi</button>
                </div>
            </form>
        </section>
        <?php endif; ?>
    </div>
    
    <div class="stack">
        <section class="panel">
            <h2>Pengaturan Tema</h2>
            <p class="muted" style="margin-bottom:20px; font-size:14px;">Pilih tema antarmuka sesuai gaya Anda.</p>
            
            <div class="field">
                <label for="theme_selector">Pilih Tema</label>
                <select id="theme_selector" style="cursor:pointer;">
                    <option value="dark">🌙 Dark (Default)</option>
                    <option value="dark-planet">🪐 Dark Planet</option>
                    <option value="horror">🩸 Horror</option>
                    <option value="modern">⚡ Modern</option>
                </select>
                <small class="muted">Tema akan langsung berubah saat dipilih.</small>
            </div>
        </section>





        <section class="panel" style="border-color:var(--danger);">
            <div style="display:flex; align-items:center; gap:10px; margin-bottom:14px;">
                <span style="font-size:20px;">⚠️</span>
                <h2 style="margin:0; color:var(--danger);">Hapus Akun</h2>
            </div>
            <p class="muted" style="margin-bottom:20px; font-size:14px; line-height:1.5;">
                Setelah akun dihapus, semua data dan sumber daya yang terkait akan dihapus secara permanen. 
                Sebelum menghapus akun, pastikan Anda telah menyimpan data yang ingin dipertahankan.
            </p>
            
            <form method="POST" action="<?php echo e(route('account.destroy')); ?>" class="stack" data-confirm="Apakah Anda yakin ingin menghapus akun secara permanen? Tindakan ini tidak dapat dibatalkan.">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                
                <div class="field">
                    <label for="delete_password">Konfirmasi dengan Password</label>
                    <input id="delete_password" name="password" type="password" required placeholder="Masukkan password Anda">
                </div>
                
                <div>
                    <button class="button danger" type="submit">Hapus Akun Permanen</button>
                </div>
            </form>
        </section>
    </div>
</div>

<!-- THANK YOU SUCCESS MODAL POPUP -->
<div id="thankYouModal" style="position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.65); backdrop-filter:blur(8px); display:none; align-items:center; justify-content:center; z-index:99999; animation:modalOverlayIn 0.25s ease-out;">
    <div style="background:linear-gradient(135deg, #111827, #070a13); border:2px solid var(--primary); padding:32px; border-radius:16px; max-width:440px; width:100%; text-align:center; box-shadow:var(--shadow);">
        <div style="font-size:50px; margin-bottom:16px; filter:drop-shadow(0 0 10px rgba(32,188,168,0.35));">✨</div>
        <h2 style="margin:0 0 12px 0; font-size:20px; font-weight:800; color:var(--ink);">Pembelian Berhasil</h2>
        <p class="muted" style="font-size:14px; line-height:1.6; margin-bottom:24px; color:#c5c5c5;">
            Terima kasih telah membeli Fitur eksklusif kami, Fitur sekarang bisa diaktifkan di Tema dalam pengaturan
        </p>
        <button type="button" class="button" onclick="document.getElementById('thankYouModal').style.display='none';" style="width:120px; margin:0 auto; padding:8px 16px; font-weight:700;">Tutup</button>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const themeSelector = document.getElementById('theme_selector');
        const userId = '<?php echo e(auth()->id()); ?>';
        let currentTheme = localStorage.getItem('theme_' + userId) || 'dark';
        
        if (currentTheme === 'light') {
            currentTheme = 'dark';
            localStorage.setItem('theme_' + userId, 'dark');
            document.documentElement.dataset.theme = 'dark';
        }
        
        // Set dropdown value to current theme
        themeSelector.value = currentTheme;
        
        // Handle theme change
        themeSelector.addEventListener('change', function(e) {
            const selectedTheme = e.target.value;
            currentTheme = selectedTheme;
            document.documentElement.dataset.theme = selectedTheme;
            localStorage.setItem('theme_' + userId, selectedTheme);
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ProyekTI\resources\views/account/edit.blade.php ENDPATH**/ ?>