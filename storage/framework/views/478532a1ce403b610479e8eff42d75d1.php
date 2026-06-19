<?php $__env->startSection('title', 'Daftar'); ?>
<?php $__env->startSection('body-class', 'public-auth'); ?>

<?php $__env->startSection('content'); ?>
<div class="auth-page" style="align-items: flex-start; padding-top:72px;">
    <div style="width:100%; max-width: 600px; margin: 0 auto; position: relative;">
        
        <div class="auth-card" style="max-width: 100%;">
            
            <div class="step-badge">Langkah 1 dari 2</div>
            <h1 class="auth-title">Daftar Akun Baru</h1>
            <p class="auth-subtitle">Silakan isi data diri Anda. Untuk role internal, Anda akan diminta kode verifikasi di langkah selanjutnya.</p>

            <?php if($errors->any()): ?>
                <div class="alert error" style="margin-bottom:18px;">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div><?php echo e($err); ?></div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('register.store')); ?>">
                <?php echo csrf_field(); ?>
                
                <div style="margin-bottom:20px; text-align:center;">
                    <label style="display:block; margin-bottom:10px;">Pilih Avatar</label>
                    <div style="display:flex; justify-content:center; position:relative;">
                        <?php if (isset($component)) { $__componentOriginalf2fd01b0ef80303dbcd883ceaa433d1a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2fd01b0ef80303dbcd883ceaa433d1a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.avatar-picker','data' => ['name' => 'avatar','size' => 68,'placeholder' => true,'contained' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('avatar-picker'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'avatar','size' => 68,'placeholder' => true,'contained' => true]); ?>
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
                </div>

                <div class="form-grid">
                    <div class="field">
                        <label for="name">Nama Lengkap</label>
                        <input id="name" name="name" type="text"
                               value="<?php echo e(old('name', session('reg_pending.name'))); ?>" required autofocus>
                    </div>
                    <div class="field">
                        <label for="username">Username</label>
                        <input id="username" name="username" type="text"
                               value="<?php echo e(old('username', session('reg_pending.username'))); ?>" required maxlength="8" pattern="[a-zA-Z0-9_]+" title="Maksimal 8 karakter tanpa spasi">
                    </div>
                </div>
                
                <div class="form-grid">
                    <div class="field">
                        <label for="email">Email</label>
                        <input id="email" name="email" type="email"
                               value="<?php echo e(old('email', session('reg_pending.email'))); ?>" required>
                    </div>
                    <div class="field">
                        <label for="phone">Nomor HP</label>
                        <input id="phone" name="phone" type="text"
                               value="<?php echo e(old('phone', session('reg_pending.phone'))); ?>" required>
                    </div>
                </div>

                <div class="field">
                    <label for="role">Daftar Sebagai</label>
                    <select id="role" name="role" required>
                        <option value="">-- Pilih Role --</option>
                        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>" <?php if(old('role', session('reg_pending.role')) === $key): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="form-grid">
                    <div class="field">
                        <label for="password">Password</label>
                        <input id="password" name="password" type="password" required minlength="1">
                    </div>
                    <div class="field">
                        <label for="password_confirmation">Konfirmasi Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required minlength="1">
                    </div>
                </div>

                <button class="btn" type="submit" style="width:100%; padding:13px; margin-top:10px;">
                    Lanjut &rarr;
                </button>
            </form>
        </div>

        <p class="auth-foot" style="text-align:center; margin-top:20px; color:var(--muted); font-size:14px;">
            Sudah punya akun?
            <a class="text-link" href="<?php echo e(route('login')); ?>">Masuk ke sini</a>
        </p>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ProyekTI\resources\views/auth/register.blade.php ENDPATH**/ ?>