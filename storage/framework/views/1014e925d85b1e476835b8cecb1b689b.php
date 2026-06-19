<?php $__env->startSection('title', 'Pesan'); ?>
<?php $__env->startSection('eyebrow', 'Nota dan Pemberitahuan'); ?>

<?php $__env->startSection('content'); ?>
<div class="grid grid-2">
    <div class="stack">
        <?php $__empty_1 = true; $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="panel" style="display:flex; justify-content:space-between; align-items:center; <?php echo e(!$message->is_read ? 'border-left: 4px solid var(--primary);' : 'opacity: 0.8;'); ?>">
                <div>
                    <h3 style="margin:0 0 5px 0;"><?php echo e($message->title); ?></h3>
                    <p class="muted" style="margin:0; font-size:12px;"><?php echo e($message->created_at->diffForHumans()); ?></p>
                </div>
                <div>
                    <?php if(Str::startsWith($message->body, 'http')): ?>
                        <a href="<?php echo e(route('messages.read', $message)); ?>" class="button small <?php echo e($message->is_read ? 'secondary' : ''); ?>">Lihat Nota</a>
                    <?php else: ?>
                        <?php if(!$message->is_read): ?>
                            <a href="<?php echo e(route('messages.read', $message)); ?>" class="button small">Tandai Dibaca</a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="panel" style="text-align:center; padding:30px; color:var(--muted);">
                Belum ada pesan.
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ProyekTI\resources\views/messages/index.blade.php ENDPATH**/ ?>