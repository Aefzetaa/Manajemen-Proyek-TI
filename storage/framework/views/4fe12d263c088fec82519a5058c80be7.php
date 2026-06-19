<?php $__env->startSection('title', 'Keuangan'); ?>
<?php $__env->startSection('eyebrow', 'Pendapatan dan jumlah transaksi'); ?>

<?php $__env->startSection('actions'); ?>
    <button class="button secondary no-print" onclick="window.print()">Export PDF</button>
    <a class="button secondary no-print" href="<?php echo e(route('reports.finance', ['date' => $date, 'export' => 'csv'])); ?>">Export Excel</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="panel">
        <form method="GET" class="toolbar" style="margin-bottom:14px;">
            <input type="date" name="date" value="<?php echo e($date); ?>">
            <button class="button secondary" type="submit">Tampilkan</button>
        </form>
        <div class="grid grid-2" style="margin-bottom:14px;">
            <div style="border:1px solid var(--line); border-radius:8px; padding:14px;">
                <div class="muted">Total Pendapatan</div>
                <div class="metric">Rp <?php echo e(number_format($totalRevenue, 0, ',', '.')); ?></div>
            </div>
            <div style="border:1px solid var(--line); border-radius:8px; padding:14px;">
                <div class="muted">Jumlah Transaksi</div>
                <div class="metric"><?php echo e($payments->count()); ?></div>
            </div>
        </div>



        <?php if($methodTotals->isNotEmpty()): ?>
            <?php ($maxMethod = max($methodTotals->max(), 1)); ?>
            <div style="margin-bottom:24px;">
                <h2>Grafik Metode Pembayaran</h2>
                <?php $__currentLoopData = $methodTotals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method => $amount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bar-row">
                        <strong><?php echo e($method); ?></strong>
                        <div class="bar"><span style="width: <?php echo e(($amount / $maxMethod) * 100); ?>%;"></span></div>
                        <span>Rp <?php echo e(number_format($amount, 0, ',', '.')); ?></span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Waktu</th><th>Pelanggan</th><th>Kendaraan</th><th>Metode</th><th>Jumlah</th></tr></thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($payment->paid_at?->format('H:i')); ?></td>
                            <td><?php echo e($payment->serviceOrder->customer->name); ?></td>
                            <td><?php echo e($payment->serviceOrder->vehicle?->plate_number); ?></td>
                            <td><?php echo e($payment->method); ?></td>
                            <td>Rp <?php echo e(number_format($payment->amount, 0, ',', '.')); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="5" class="muted">Belum ada transaksi lunas pada tanggal ini.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

<?php $__env->startSection('scripts'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ProyekTI\resources\views/reports/finance.blade.php ENDPATH**/ ?>