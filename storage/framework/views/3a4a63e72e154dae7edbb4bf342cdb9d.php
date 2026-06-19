<?php $__env->startSection('title', 'Verifikasi'); ?>
<?php $__env->startSection('body-class', 'public-verify'); ?>

<?php $__env->startSection('content'); ?>
<div class="auth-page" style="align-items:flex-start; padding-top:72px;">
    <div style="width:100%; max-width: 440px; position: relative;">
        
        <div class="auth-card">
            <div class="step-badge">Langkah 2 dari 2</div>
            <h1 class="auth-title">Verifikasi Akses</h1>
            <p class="auth-subtitle">Anda mendaftar sebagai <strong><?php echo e($roleLabel); ?></strong>. Masukkan kode verifikasi untuk melanjutkan.</p>

            <?php if($errors->any()): ?>
                <div class="alert error" style="margin-bottom:18px;">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div><?php echo e($err); ?></div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('register.verify.store')); ?>">
                <?php echo csrf_field(); ?>
                <div class="verify-box">
                    <p>Kode ini diberikan oleh administrator untuk mencegah pendaftaran akun staf yang tidak sah.</p>
                    <div class="field" style="margin-bottom:0;">
                        <input id="verification_code" name="verification_code" type="password"
                               required autofocus autocomplete="off"
                               style="text-transform: uppercase; font-weight:700; font-size:16px; letter-spacing:1px; text-align:center;">
                    </div>
                </div>

                <button class="btn" type="submit" style="width:100%; padding:13px;">
                    Selesaikan Pendaftaran
                </button>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ProyekTI\resources\views/auth/verify.blade.php ENDPATH**/ ?>