<?php $__env->startSection('title', 'Aktivitas'); ?>
<?php $__env->startSection('eyebrow', 'Aktivitas profil dan transaksi saldo Anda'); ?>

<?php $__env->startSection('content'); ?>
<section class="panel">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Aktivitas</th>
                    <th>Detail</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="muted"><?php echo e($activity->created_at->format('d/m/Y H:i')); ?></td>
                        <td>
                            <?php if($activity->type === 'money_in'): ?>
                                <span style="display:inline-flex; align-items:center; gap:6px; color:var(--ok); font-weight:700;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    +Rp <?php echo e(number_format($activity->amount, 0, ',', '.')); ?>

                                </span>
                            <?php elseif($activity->type === 'money_out'): ?>
                                <span style="display:inline-flex; align-items:center; gap:6px; color:var(--danger); font-weight:700;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                    -Rp <?php echo e(number_format(abs($activity->amount), 0, ',', '.')); ?>

                                </span>
                            <?php else: ?>
                                <span style="display:inline-flex; align-items:center; gap:6px; color:var(--primary); font-weight:700;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                    Perubahan Profil
                                </span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span style="font-size:14px;"><?php echo e($activity->description); ?></span>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="3" class="muted" style="text-align:center; padding:30px;">
                            Belum ada riwayat aktivitas di akun ini.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <?php if($activities->hasPages()): ?>
        <div class="pagination" style="margin-top:14px;"><?php echo e($activities->links()); ?></div>
    <?php endif; ?>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ProyekTI\resources\views/account/activities.blade.php ENDPATH**/ ?>