<?php $__env->startSection('title', auth()->user()->isRole('mechanic') ? 'ACC Booking' : 'Booking'); ?>
<?php $__env->startSection('eyebrow', auth()->user()->isRole('customer') ? 'Jadwal dan status booking Anda' : (auth()->user()->isRole('mechanic') ? 'Konfirmasi booking pelanggan' : 'Jadwal servis pelanggan')); ?>

<?php $__env->startSection('actions'); ?>
    <?php if(auth()->user()->isRole('customer')): ?>
        <a class="button" href="<?php echo e(route('bookings.create')); ?>">Booking Baru</a>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="panel">
        <?php if (! (auth()->user()->hasAnyRole(['customer', 'mechanic']))): ?>
            <form method="GET" class="toolbar" style="margin-bottom:14px;">
                <input type="date" name="date" value="<?php echo e(request('date')); ?>" style="max-width:190px;">
                <select name="status" style="max-width:210px;">
                    <option value="">Semua status</option>
                    <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($key); ?>" <?php if(request('status') === $key): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <button class="button secondary" type="submit">Filter</button>
                <a class="button secondary" href="<?php echo e(route('bookings.index')); ?>">Reset</a>
            </form>
        <?php endif; ?>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <?php if (! (auth()->user()->isRole('customer'))): ?>
                            <th>Pelanggan</th>
                        <?php endif; ?>
                        <th>Kendaraan</th>
                        <th>Layanan</th>
                        <th>Jadwal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <?php if (! (auth()->user()->isRole('customer'))): ?>
                                <td><?php echo e($booking->user->username); ?><br><span class="muted"><?php echo e($booking->user->phone); ?></span></td>
                            <?php endif; ?>
                            <td><?php echo e($booking->vehicle?->plate_number); ?><br><span class="muted"><?php echo e($booking->vehicle?->brand); ?> <?php echo e($booking->vehicle?->model); ?></span></td>
                            <td><?php echo e($booking->serviceTypes->pluck('name')->join(', ')); ?><br><span class="muted"><?php echo e($booking->service_description); ?></span></td>
                            <td><?php echo e($booking->booking_date->format('d/m/Y')); ?><br><span class="muted"><?php echo e(substr($booking->booking_time, 0, 5)); ?></span></td>
                            <td><span class="status <?php echo e($booking->status); ?>"><?php echo e($booking->statusLabel()); ?></span></td>
                            <td>
                                <div class="toolbar">
                                    <?php if(auth()->user()->isRole('mechanic')): ?>
                                        <?php if($booking->status === 'scheduled'): ?>
                                            <form method="POST" action="<?php echo e(route('bookings.accept', $booking)); ?>">
                                                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                                <button type="submit" class="button small">Ambil Pekerjaan</button>
                                            </form>
                                        <?php elseif($booking->status === 'accepted' && $booking->mechanic_id === auth()->user()->id): ?>
                                            <form method="POST" action="<?php echo e(route('bookings.start-work', $booking)); ?>">
                                                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                                <button type="submit" class="button small">Mulai Kerjakan</button>
                                            </form>
                                        <?php elseif($booking->status === 'in_progress' && ! $booking->serviceOrder && $booking->mechanic_id === auth()->user()->id): ?>
                                            <a class="button small" href="<?php echo e(route('service-orders.create', $booking)); ?>">Buat Rincian Servis</a>
                                        <?php endif; ?>
                                    <?php elseif(auth()->user()->isRole('owner') && ! $booking->serviceOrder && $booking->status !== 'canceled'): ?>
                                        <a class="button small" href="<?php echo e(route('service-orders.create', $booking)); ?>">Input Servis</a>
                                    <?php endif; ?>
                                    <?php if(auth()->user()->isRole('owner')): ?>
                                        <form method="POST" action="<?php echo e(route('bookings.status', $booking)); ?>">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?>
                                            <select name="status" onchange="this.form.submit()" style="min-width:145px;">
                                                <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($key); ?>" <?php if($booking->status === $key): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </form>
                                    <?php endif; ?>
                                    <?php if(auth()->user()->isRole('customer') && $booking->status === 'scheduled'): ?>
                                        <?php if($booking->created_at->diffInMinutes(now()) <= 60 && $booking->mechanic_id === null): ?>
                                            <a class="button small secondary" href="<?php echo e(route('bookings.edit', $booking)); ?>">Edit</a>
                                        <?php endif; ?>
                                        <form method="POST" action="<?php echo e(route('bookings.cancel', $booking)); ?>" data-confirm="Yakin ingin membatalkan booking ini?">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?>
                                            <button type="submit" class="button small" style="background:var(--danger); border-color:var(--danger); color:#fff;">Batalkan Booking</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="6" class="muted">Belum ada booking.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="pagination"><?php echo e($bookings->links()); ?></div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ProyekTI\resources\views/bookings/index.blade.php ENDPATH**/ ?>