<?php $__env->startSection('title', 'Input Servis'); ?>
<?php $__env->startSection('eyebrow', 'Catatan mekanik untuk transaksi'); ?>

<?php $__env->startSection('content'); ?>
    <div class="grid grid-2">
        <section class="panel">
            <h2>Data Booking</h2>
            <p><strong><?php echo e($booking->user->name); ?></strong></p>
            <p class="muted"><?php echo e($booking->vehicle?->plate_number); ?> - <?php echo e($booking->vehicle?->brand); ?> <?php echo e($booking->vehicle?->model); ?></p>
            <p><?php echo e($booking->serviceTypes->pluck('name')->join(', ')); ?> pada <?php echo e($booking->booking_date->format('d/m/Y')); ?> <?php echo e(substr($booking->booking_time, 0, 5)); ?></p>
            <p class="muted"><?php echo e($booking->service_description); ?></p>
        </section>

        <section class="panel">
            <h2>Detail Pekerjaan</h2>
            <form method="POST" action="<?php echo e(route('service-orders.store', $booking)); ?>" class="stack">
                <?php echo csrf_field(); ?>
                <div class="field">
                    <label for="serviced_at">Tanggal Servis</label>
                    <input id="serviced_at" name="serviced_at" type="date" value="<?php echo e(old('serviced_at', now()->toDateString())); ?>" required>
                </div>
                <div class="field">
                    <label for="complaint">Keluhan</label>
                    <textarea id="complaint" name="complaint" readonly><?php echo e(old('complaint', $booking->service_description)); ?></textarea>
                </div>
                <div class="field">
                    <label for="diagnosis">Diagnosis / Pekerjaan</label>
                    <textarea id="diagnosis" name="diagnosis" required><?php echo e(old('diagnosis')); ?></textarea>
                </div>
                <div class="field" style="display: none;">
                    <label for="labor_cost">Biaya Jasa</label>
                    <input id="labor_cost" name="labor_cost" type="number" value="<?php echo e($booking->serviceTypes->sum('base_price')); ?>" readonly>
                </div>

                <div class="field">
                    <label>Sparepart Digunakan</label>
                    <div class="stack">
                        <?php $__currentLoopData = $spareParts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $part): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="line-item">
                                <div>
                                    <strong><?php echo e($part->name); ?></strong><br>
                                    <span class="muted">Stok <?php echo e($part->stock); ?> | Rp <?php echo e(number_format($part->price, 0, ',', '.')); ?></span>
                                </div>
                                <input name="parts[<?php echo e($part->id); ?>]" type="number" min="0" max="<?php echo e($part->stock); ?>" value="<?php echo e(old('parts.' . $part->id, 0)); ?>">
                                <span class="muted">Jumlah</span>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <button class="button" type="submit">Simpan Detail Servis</button>
            </form>
        </section>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ProyekTI\resources\views/service-orders/create.blade.php ENDPATH**/ ?>