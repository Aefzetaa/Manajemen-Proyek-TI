<?php $__env->startSection('title', 'Invoice'); ?>
<?php $__env->startSection('eyebrow', $invoice->invoice_number); ?>

<?php $__env->startSection('actions'); ?>
    <button class="button secondary no-print" onclick="window.print()">Cetak / Simpan PDF</button>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="invoice">
        <div class="split">
            <div>
                <h2>Invoice Bengkel Motor</h2>
                <p class="muted"><?php echo e($invoice->invoice_number); ?></p>
            </div>
            <div style="text-align:right;">
                <strong><?php echo e($invoice->issued_at->format('d/m/Y H:i')); ?></strong><br>
                <span class="muted"><?php echo e($invoice->recipient_channel ?: 'Digital'); ?></span>
            </div>
        </div>

        <?php ($order = $invoice->payment->serviceOrder); ?>
        <div class="grid grid-2" style="margin-top:20px;">
            <div>
                <h3>Pelanggan</h3>
                <p><?php echo e($order->customer->name); ?><br><span class="muted"><?php echo e($order->customer->phone); ?></span></p>
            </div>
            <div>
                <h3>Kendaraan</h3>
                <p><?php echo e($order->vehicle?->plate_number); ?><br><span class="muted"><?php echo e($order->vehicle?->brand); ?> <?php echo e($order->vehicle?->model); ?></span></p>
            </div>
        </div>

        <div class="table-wrap" style="margin-top:18px;">
            <table>
                <thead><tr><th>Item</th><th>Qty</th><th>Harga</th><th>Total</th></tr></thead>
                <tbody>
                    <tr>
                        <td>Biaya jasa</td>
                        <td>1</td>
                        <td>Rp <?php echo e(number_format($order->labor_cost, 0, ',', '.')); ?></td>
                        <td>Rp <?php echo e(number_format($order->labor_cost, 0, ',', '.')); ?></td>
                    </tr>
                    <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($item->description); ?></td>
                            <td><?php echo e($item->quantity); ?></td>
                            <td>Rp <?php echo e(number_format($item->unit_price, 0, ',', '.')); ?></td>
                            <td>Rp <?php echo e(number_format($item->total_price, 0, ',', '.')); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td colspan="3"><strong>Total Dibayar</strong></td>
                        <td><strong>Rp <?php echo e(number_format($invoice->payment->amount, 0, ',', '.')); ?></strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ProyekTI\resources\views/payments/invoice.blade.php ENDPATH**/ ?>