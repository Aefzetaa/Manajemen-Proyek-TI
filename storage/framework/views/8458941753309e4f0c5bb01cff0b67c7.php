<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('eyebrow', auth()->user()->isRole('customer') ? 'Area pelanggan Milky Garage' : (auth()->user()->isRole('mechanic') ? 'Area kerja mekanik' : (auth()->user()->isRole('cashier') ? 'Area kerja kasir' : 'Ringkasan operasional hari ini'))); ?>

<?php $__env->startSection('actions'); ?>
    <?php if(auth()->user()->isRole('customer')): ?>
        <a class="button secondary" href="<?php echo e(route('messages.index')); ?>" style="position:relative; display:inline-flex; align-items:center; gap:6px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
            Pesan
            <?php if($unreadMessageCount > 0): ?>
                <span style="position:absolute; top:-6px; right:-6px; background:var(--danger); color:#fff; font-size:10px; font-weight:800; padding:2px 6px; border-radius:10px; line-height:1;"><?php echo e($unreadMessageCount); ?></span>
            <?php endif; ?>
        </a>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>




<?php if(auth()->user()->isRole('customer')): ?>
    <div class="grid grid-3">
        <section class="panel metric-card">
            <div class="metric-label">Booking</div>
            <div class="metric"><?php echo e($bookingCount); ?></div>
            <div class="metric-note">Booking yang sedang dilakukan</div>
        </section>
        <section class="panel metric-card">
            <div class="metric-label">Service</div>
            <div class="metric"><?php echo e($serviceCount); ?></div>
            <div class="metric-note">Unit yang sedang di service</div>
        </section>
        <section class="panel metric-card">
            <div class="metric-label">Total</div>
            <div class="metric">Rp <?php echo e(number_format($pendingPaymentAmount, 0, ',', '.')); ?></div>
            <div class="metric-note">Total Pembayaran</div>
        </section>
    </div>

    <?php if($customerInServiceOrders->count() > 0): ?>
        <section class="panel" style="margin-top:16px;">
            <div class="split">
                <h2>Service yang sedang dikerjakan</h2>
                <a class="button secondary small" href="<?php echo e(route('service-orders.index')); ?>">Lihat Detail</a>
            </div>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Kendaraan</th><th>Layanan</th><th>Status</th></tr></thead>
                    <tbody>
                        <?php $__currentLoopData = $customerInServiceOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($booking->vehicle?->plate_number ?? '-'); ?></td>
                                <td><?php echo e($booking->serviceTypes->pluck('name')->join(', ') ?: '-'); ?></td>
                                <td><span class="status <?php echo e($booking->status); ?>"><?php echo e($booking->statusLabel()); ?></span></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </section>
    <?php else: ?>
        <section class="panel" style="margin-top:16px;">
            <div class="split">
                <h2>Jadwal Booking Terbaru Anda</h2>
                <a class="button secondary small" href="<?php echo e(route('bookings.index')); ?>">Lihat Semua</a>
            </div>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Kendaraan</th><th>Jadwal</th><th>Status</th></tr></thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $bookings->whereIn('status', ['scheduled', 'accepted', 'in_progress']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($booking->vehicle?->plate_number ?? '-'); ?></td>
                                <td><?php echo e($booking->booking_date->format('d/m/Y')); ?> <?php echo e(substr($booking->booking_time, 0, 5)); ?></td>
                                <td><span class="status <?php echo e($booking->status); ?>"><?php echo e($booking->statusLabel()); ?></span></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="3" class="muted">Belum ada booking aktif saat ini.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    <?php endif; ?>




<?php elseif(auth()->user()->isRole('mechanic')): ?>
    <div class="grid grid-3">
        <section class="panel metric-card">
            <div class="metric-label">Antrean</div>
            <div class="metric"><?php echo e($mechanicAntreanCount ?? 0); ?></div>
            <div class="metric-note">unit yang akan dikerjakan</div>
        </section>
        <section class="panel metric-card">
            <div class="metric-label">Dikerjakan</div>
            <div class="metric"><?php echo e($mechanicDikerjakanCount ?? 0); ?></div>
            <div class="metric-note">sedang proses pengerjaan</div>
        </section>
        <section class="panel metric-card">
            <div class="metric-label">Selesai</div>
            <div class="metric"><?php echo e($mechanicSelesaiCount ?? 0); ?></div>
            <div class="metric-note">Unit Beres</div>
        </section>
    </div>
    
    <section class="panel" style="margin-top:16px;">
        <div class="split">
            <h2>Pekerjaan Terbaru Anda</h2>
            <a class="button secondary small" href="<?php echo e(route('service-orders.index')); ?>">Lihat Semua</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Pelanggan</th><th>Kendaraan</th><th>Jenis Service</th><th>Jam Booking</th><th>Gaji (Rp)</th><th>Status Pekerjaan</th></tr></thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $mechanicDailyHistory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $mechanicShare = $booking->serviceOrder
                                ? $booking->serviceOrder->laborShareBreakdown()['mechanic']
                                : 0;
                        ?>
                        <tr>
                            <td><?php echo e($booking->user->username); ?></td>
                            <td><?php echo e($booking->vehicle?->plate_number ?? '-'); ?></td>
                            <td><?php echo e($booking->serviceTypes->pluck('name')->join(', ')); ?></td>
                            <td><?php echo e(substr($booking->booking_time, 0, 5)); ?></td>
                            <td>Rp <?php echo e(number_format($mechanicShare, 0, ',', '.')); ?></td>
                            <td><span class="status <?php echo e($booking->status); ?>"><?php echo e($booking->statusLabel()); ?></span></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="6" class="muted">Belum ada pekerjaan servis hari ini.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>




<?php elseif(auth()->user()->isRole('cashier')): ?>
    <div class="grid grid-4">
        <section class="panel metric-card">
            <div class="metric-label">Pendapatan Hari Ini</div>
            <div class="metric">Rp <?php echo e(number_format($cashierIncomeToday, 0, ',', '.')); ?></div>
            <div class="metric-note">Gaji harian + profit servis</div>
        </section>
        <section class="panel metric-card">
            <div class="metric-label">Menunggu Bayar</div>
            <div class="metric"><?php echo e($pendingPaymentCount); ?></div>
            <div class="metric-note">Invoice belum dilunasi</div>
        </section>
        <section class="panel metric-card">
            <div class="metric-label">Lunas Hari Ini</div>
            <div class="metric">Rp <?php echo e(number_format($paidAmount, 0, ',', '.')); ?></div>
            <div class="metric-note">Total transaksi lunas hari ini</div>
        </section>
        <section class="panel metric-card">
            <div class="metric-label">Kendaraan Selesai</div>
            <div class="metric"><?php echo e($cashierFinishedTodayCount); ?></div>
            <div class="metric-note">Selesai hari ini</div>
        </section>
    </div>

    <section class="panel" style="margin-top:16px;">
        <div class="split">
            <h2>Menunggu Konfirmasi Pembayaran</h2>
            <a class="button secondary small" href="<?php echo e(route('payments.index')); ?>">Lihat Semua</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Pelanggan</th><th>Kendaraan</th><th>Total</th><th>Metode</th><th>Status</th></tr></thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $orders->filter(fn ($o) => $o->payment?->status === 'pending')->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($order->customer->username); ?></td>
                            <td><?php echo e($order->vehicle?->plate_number ?? '-'); ?><br><span class="muted"><?php echo e($order->booking?->serviceTypes->pluck('name')->join(', ') ?: '-'); ?></span></td>
                            <td>Rp <?php echo e(number_format($order->payment?->amount ?? 0, 0, ',', '.')); ?></td>
                            <td><?php echo e($order->payment?->method ?: 'Belum dipilih'); ?></td>
                            <td><span class="status <?php echo e($order->payment?->status); ?>"><?php echo e($order->payment?->status === 'paid' ? 'Lunas' : 'Pending'); ?></span></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="5" class="muted">Belum ada data pembayaran.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>




<?php else: ?>
    <?php
        $totalCashflow7Days = collect($cashflow)->sum('total');
    ?>
    <div class="grid grid-4">
        <section class="panel metric-card">
            <div class="metric-label">Total Booking</div>
            <div class="metric"><?php echo e($ownerTotalBooking); ?></div>
            <div class="metric-note">Jadwal yang belum selesai</div>
        </section>
        <section class="panel metric-card">
            <div class="metric-label">Booking Dibatalkan</div>
            <div class="metric"><?php echo e($ownerCanceledBookingThisMonth); ?></div>
            <div class="metric-note">Batal di bulan ini</div>
        </section>
        <section class="panel metric-card">
            <div class="metric-label">Pendapatan Hari Ini</div>
            <div class="metric">Rp <?php echo e(number_format($ownerNetIncomeToday, 0, ',', '.')); ?></div>
            <div class="metric-note">Omset bersih</div>
        </section>
        <section class="panel metric-card">
            <div class="metric-label">Arus Kas 7 Hari Terakhir</div>
            <div class="metric">Rp <?php echo e(number_format($totalCashflow7Days, 0, ',', '.')); ?></div>
            <div class="metric-note">Total omset 7 hari terakhir</div>
        </section>
    </div>

    <section class="panel" style="margin-top:16px;">
        <h2>Stock Sparepart</h2>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Nama Sparepart</th><th>Sisa Stok</th><th>Status</th></tr></thead>
                <tbody>
                    <?php
                        $lowStockSpareparts = \App\Models\SparePart::where('stock', '<', 5)->orderBy('stock', 'asc')->get();
                    ?>
                    <?php $__empty_1 = true; $__currentLoopData = $lowStockSpareparts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $part): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($part->name); ?></td>
                            <td><strong><?php echo e($part->stock); ?></strong></td>
                            <td>
                                <?php if($part->stock == 0): ?>
                                    <span style="color:var(--danger); font-weight:bold;">HABIS</span>
                                <?php else: ?>
                                    <span style="color:var(--accent); font-weight:bold;">KRITIS</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="3" class="muted">Semua stok sparepart aman.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
<?php endif; ?>

<?php $__env->startSection('scripts'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ProyekTI\resources\views/dashboard.blade.php ENDPATH**/ ?>