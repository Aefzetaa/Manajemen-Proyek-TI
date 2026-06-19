<?php $__env->startSection('title', 'Kasir'); ?>
<?php $__env->startSection('eyebrow', 'Ringkasan transaksi kasir'); ?>

<?php $__env->startSection('actions'); ?>
    <a class="button secondary no-print" href="<?php echo e(route('reports.cashier', ['date' => $date, 'export' => 'csv'])); ?>">Export CSV</a>
    <button class="button secondary no-print" onclick="window.print()">Export PDF</button>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    
    <section class="panel" style="margin-bottom:16px;">
        <form method="GET" id="reportForm" class="toolbar">
            <label style="font-size:13px; color:var(--muted);">Tanggal:</label>
            <input type="date" name="date" value="<?php echo e($date); ?>" onchange="document.getElementById('reportForm').submit()" style="max-width:200px;">
        </form>
    </section>

    
    <div class="grid grid-2" style="margin-bottom:16px;">
        <section class="panel metric-card">
            <div class="metric-label">Total Transaksi</div>
            <div class="metric"><?php echo e($totalCount); ?></div>
            <div class="metric-note">Pembayaran lunas pada <?php echo e(\Carbon\Carbon::parse($date)->translatedFormat('d F Y')); ?></div>
        </section>
        <section class="panel metric-card">
            <div class="metric-label">Total Pendapatan</div>
            <div class="metric" style="font-size:22px;">Rp <?php echo e(number_format($totalRevenue, 0, ',', '.')); ?></div>
            <div class="metric-note">Omzet lunas pada <?php echo e(\Carbon\Carbon::parse($date)->translatedFormat('d F Y')); ?></div>
        </section>
    </div>

    
    <section class="panel">
        <div class="table-wrap">
            <table>
                <thead><tr><th>Referensi</th><th>Pelanggan</th><th>Kendaraan</th><th>Metode</th><th>Kasir</th><th>Jumlah</th></tr></thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($payment->reference_no); ?><br><span class="muted"><?php echo e($payment->paid_at?->format('H:i')); ?></span></td>
                            <td><?php echo e($payment->serviceOrder->customer->name); ?></td>
                            <td><?php echo e($payment->serviceOrder->vehicle?->plate_number ?? '-'); ?><br><span class="muted"><?php echo e($payment->serviceOrder->serviceType?->name ?? '-'); ?></span></td>
                            <td><?php echo e($payment->method ?? '-'); ?></td>
                            <td><?php echo e($payment->cashier?->name ?? 'ZeroPay / Otomatis'); ?></td>
                            <td><strong>Rp <?php echo e(number_format($payment->amount, 0, ',', '.')); ?></strong></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="6" class="muted" style="text-align:center; padding:30px;">Belum ada transaksi lunas pada tanggal ini.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ProyekTI\resources\views/reports/cashier.blade.php ENDPATH**/ ?>