<?php $__env->startSection('title', auth()->user()->isRole('customer') ? 'Riwayat' : (auth()->user()->hasAnyRole(['mechanic', 'cashier']) ? 'Gaji' : 'Servis')); ?>
<?php $__env->startSection('eyebrow', auth()->user()->isRole('customer') ? 'Riwayat servis kendaraan Anda' : (auth()->user()->hasAnyRole(['mechanic', 'cashier']) ? 'Ringkasan gaji dan pekerjaan' : 'Detail pekerjaan mekanik dan biaya pelanggan')); ?>

<?php $__env->startSection('content'); ?>
    <section class="panel">
        <?php if(auth()->user()->isRole('customer')): ?>
            <form method="GET" class="toolbar" style="margin-bottom:14px;">
                <select name="year" onchange="this.form.submit()" style="max-width:210px;">
                    <?php $currentYear = request('year', date('Y')); ?>
                    <?php for($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                        <option value="<?php echo e($y); ?>" <?php if((string) $currentYear === (string) $y): echo 'selected'; endif; ?>><?php echo e($y); ?></option>
                    <?php endfor; ?>
                </select>
                <a class="button secondary" href="<?php echo e(route('service-orders.index')); ?>">Reset</a>
            </form>
        <?php elseif(auth()->user()->hasAnyRole(['mechanic', 'cashier'])): ?>
            <form method="GET" class="toolbar" style="margin-bottom:24px;">
                <input type="month" name="month" value="<?php echo e(request('month', date('Y-m'))); ?>" onchange="this.form.submit()" style="max-width:210px;">
                <a class="button secondary" href="<?php echo e(route('service-orders.index')); ?>">Kembali ke Bulan Ini</a>
            </form>
            
            <?php 
                $monthName = \Carbon\Carbon::parse(request('month', date('Y-m')))->translatedFormat('F Y');
            ?>
            <div style="text-align:center; padding: 40px 20px; background:var(--bg-soft); border-radius:12px; border:1px solid var(--line);">
                <h3 style="margin-bottom:8px; color:var(--muted);">Total Gaji Bersih Anda Bulan <?php echo e($monthName); ?></h3>
                <h1 style="color:var(--ok); font-size:42px; margin:0;">Rp <?php echo e(number_format($mechanicTotalSalary, 0, ',', '.')); ?></h1>
            </div>
        <?php else: ?>
            <form method="GET" class="toolbar" style="margin-bottom:14px;">
                <input type="date" name="from" value="<?php echo e(request('from')); ?>" aria-label="Dari tanggal">
                <input type="date" name="to" value="<?php echo e(request('to')); ?>" aria-label="Sampai tanggal">
                <select name="service_type_id">
                    <option value="">Semua jenis servis</option>
                    <?php $__currentLoopData = $serviceTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($type->id); ?>" <?php if((string) request('service_type_id') === (string) $type->id): echo 'selected'; endif; ?>><?php echo e($type->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <select name="status">
                    <option value="">Semua status</option>
                    <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($key); ?>" <?php if(request('status') === $key): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <button class="button secondary" type="submit">Filter</button>
                <a class="button secondary" href="<?php echo e(route('service-orders.index')); ?>">Reset</a>
            </form>
        <?php endif; ?>

        <?php if (! (auth()->user()->hasAnyRole(['mechanic', 'cashier']))): ?>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <?php if (! (auth()->user()->isRole('customer'))): ?>
                            <th>Pelanggan</th>
                        <?php endif; ?>
                        <th>Kendaraan</th>
                        <th>Diagnosis</th>
                        <th>Total</th>
                        <th>Status</th>
                        <?php if (! (auth()->user()->isRole('customer'))): ?>
                            <th>Aksi</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr <?php if(auth()->user()->isRole('customer')): ?> style="cursor:pointer;" onclick="window.location='<?php echo e(route('service-orders.show', $order)); ?>'" <?php endif; ?>>
                            <td><?php echo e($order->serviced_at->format('d/m/Y')); ?></td>
                            <?php if (! (auth()->user()->isRole('customer'))): ?>
                                <td><?php echo e($order->customer->name); ?></td>
                            <?php endif; ?>
                            <td><?php echo e($order->vehicle?->plate_number); ?><br><span class="muted"><?php echo e($order->serviceType?->name); ?></span></td>
                            <td><?php echo e($order->diagnosis); ?></td>
                            <td>Rp <?php echo e(number_format($order->total_cost, 0, ',', '.')); ?></td>
                            <td><span class="status <?php echo e($order->status); ?>"><?php echo e($order->statusLabel()); ?></span></td>
                            <?php if (! (auth()->user()->isRole('customer'))): ?>
                                <td><a class="button secondary small" href="<?php echo e(route('service-orders.show', $order)); ?>">Detail</a></td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="7" class="muted">Belum ada data servis.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="pagination"><?php echo e($orders->links()); ?></div>
        <?php endif; ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ProyekTI\resources\views/service-orders/index.blade.php ENDPATH**/ ?>