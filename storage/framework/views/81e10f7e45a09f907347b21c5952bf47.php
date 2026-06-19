<?php $__env->startSection('title', 'Pembayaran'); ?>
<?php $__env->startSection('eyebrow', auth()->user()->isRole('customer') ? 'Tagihan servis Anda' : 'Kelola pembayaran pelanggan'); ?>

<?php $__env->startSection('content'); ?>


<?php if(auth()->user()->isRole('cashier')): ?>
    
    <section class="panel" style="margin-bottom:16px;">
        <form method="GET" id="filterForm" class="toolbar">
            <label style="font-size:13px; color:var(--muted);">Filter Jenis:</label>
            <select name="service_type" onchange="document.getElementById('filterForm').submit()" style="max-width:200px;">
                <option value="">Semua Jenis</option>
                <option value="Matic"   <?php if(request('service_type') === 'Matic'): echo 'selected'; endif; ?>>Matic</option>
                <option value="Manual"  <?php if(request('service_type') === 'Manual'): echo 'selected'; endif; ?>>Manual</option>
                <option value="Kopling" <?php if(request('service_type') === 'Kopling'): echo 'selected'; endif; ?>>Kopling</option>
            </select>
            <?php if(request('service_type')): ?>
                <a class="button secondary small" href="<?php echo e(route('payments.index')); ?>">Reset</a>
            <?php endif; ?>
        </form>
    </section>

    
    <div class="stack">
        <?php $__empty_1 = true; $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php $order = $payment->serviceOrder; ?>
            <div class="panel" style="padding:20px;" id="card-<?php echo e($payment->id); ?>">
                <div style="display:grid; grid-template-columns:1fr auto; gap:16px; align-items:flex-start;">
                    
                    <div>
                        <div style="display:flex; align-items:center; gap:10px; margin-bottom:10px; flex-wrap:wrap;">
                            <strong style="font-size:16px;"><?php echo e($order->customer->name); ?></strong>
                            <span class="status <?php echo e($order->status); ?>"><?php echo e($order->statusLabel()); ?></span>
                            <span class="muted" style="font-size:12px;"><?php echo e($payment->reference_no); ?></span>
                        </div>
                        <div style="display:grid; grid-template-columns:repeat(3,auto); gap:8px 24px; font-size:13px;">
                            <div><span class="muted">Kendaraan:</span> <strong><?php echo e($order->vehicle?->plate_number ?? '-'); ?></strong></div>
                            <div><span class="muted">Jenis Servis:</span> <strong><?php echo e($order->serviceType?->name ?? '-'); ?></strong></div>
                            <div><span class="muted">Mekanik:</span> <strong><?php echo e($order->mechanic?->name ?? '-'); ?></strong></div>
                            <div><span class="muted">Ongkos Jasa:</span> <strong>Rp <?php echo e(number_format($order->labor_cost, 0, ',', '.')); ?></strong></div>
                            <div><span class="muted">Sparepart:</span> <strong>Rp <?php echo e(number_format($order->parts_total, 0, ',', '.')); ?></strong></div>
                            <div><span class="muted">Tanggal Servis:</span> <strong><?php echo e($order->serviced_at->format('d/m/Y')); ?></strong></div>
                        </div>
                        <?php if($order->diagnosis): ?>
                            <div style="margin-top:8px; font-size:13px; color:var(--muted);">Diagnosis: <?php echo e(Str::limit($order->diagnosis, 80)); ?></div>
                        <?php endif; ?>
                    </div>

                    
                    <div style="text-align:right;">
                        <div style="font-size:12px; color:var(--muted); margin-bottom:2px;">Total Tagihan</div>
                        <div style="font-size:26px; font-weight:900; color:var(--primary);">Rp <?php echo e(number_format($payment->amount, 0, ',', '.')); ?></div>
                        
                        <?php if($order->status === 'waiting_cashier'): ?>
                            <form method="POST" action="<?php echo e(route('service-orders.send-to-customer', $order)); ?>" style="margin-top:10px;">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <button class="button small" type="submit">Kirim ke Pelanggan</button>
                            </form>
                        <?php else: ?>
                            <button class="button small" style="margin-top:10px;" onclick="toggleForm('form-<?php echo e($payment->id); ?>', this)">
                                Lunas
                            </button>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if($order->status !== 'waiting_cashier'): ?>
                
                <div id="form-<?php echo e($payment->id); ?>" style="display:none; margin-top:18px; border-top:1px solid var(--line); padding-top:18px;">
                    <form method="POST" action="<?php echo e(route('payments.paid', $payment)); ?>">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>
                        <input type="hidden" name="method" value="Tunai">
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:14px;">
                            <div class="field">
                                <label>Uang Diterima (Rp)</label>
                                <input type="number" name="cash_received" placeholder="Masukkan jumlah uang..."
                                    min="<?php echo e($payment->amount); ?>" required
                                    oninput="calcChange(this, <?php echo e($payment->amount); ?>, 'kembalian-<?php echo e($payment->id); ?>')"
                                    style="font-size:15px; font-weight:700;">
                            </div>
                            <div class="field">
                                <label>Kembalian</label>
                                <div id="kembalian-<?php echo e($payment->id); ?>" style="padding:10px 11px; border-radius:8px; border:1px solid var(--line); background:var(--bg); font-size:15px; font-weight:800; color:var(--muted); min-height:42px; display:flex; align-items:center;">
                                    
                                </div>
                            </div>
                        </div>
                        <div style="display:flex; gap:10px; align-items:center;">
                            <button class="button" type="submit">Lunas</button>
                            <button type="button" class="button secondary" onclick="toggleForm('form-<?php echo e($payment->id); ?>', null)">Batal</button>
                        </div>
                    </form>
                </div>
                <?php endif; ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="panel" style="text-align:center; padding:50px; color:var(--muted);">
                <strong style="font-size:18px;">Tidak ada pembayaran tunai yang menunggu</strong>
                <p style="font-size:14px; margin-top:6px;">Semua tagihan sudah lunas atau pelanggan memilih ZeroPay.</p>
            </div>
        <?php endif; ?>
    </div>

    <?php if($payments->hasPages()): ?>
        <div class="pagination" style="margin-top:14px;"><?php echo e($payments->links()); ?></div>
    <?php endif; ?>


<?php else: ?>
    <section class="panel">
        <?php if (! (auth()->user()->isRole('customer'))): ?>
            <form method="GET" class="toolbar" style="margin-bottom:14px;">
                <input type="date" name="date" value="<?php echo e(request('date')); ?>">
                <select name="status">
                    <option value="">Semua status</option>
                    <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($key); ?>" <?php if(request('status') === $key): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <button class="button secondary" type="submit">Filter</button>
                <a class="button secondary" href="<?php echo e(route('payments.index')); ?>">Reset</a>
            </form>
        <?php endif; ?>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Referensi</th>
                        <?php if (! (auth()->user()->isRole('customer'))): ?>
                            <th>Pelanggan</th>
                        <?php endif; ?>
                        <th>Kendaraan</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($payment->reference_no); ?><br><span class="muted"><?php echo e($payment->created_at->format('d/m/Y H:i')); ?></span></td>
                            <?php if (! (auth()->user()->isRole('customer'))): ?>
                                <td><?php echo e($payment->serviceOrder->customer->name); ?></td>
                            <?php endif; ?>
                            <td><?php echo e($payment->serviceOrder->vehicle?->plate_number); ?><br><span class="muted"><?php echo e($payment->serviceOrder->serviceType?->name); ?></span></td>
                            <td>Rp <?php echo e(number_format($payment->amount, 0, ',', '.')); ?></td>
                            <td><span class="status <?php echo e($payment->status); ?>"><?php echo e($payment->statusLabel()); ?></span></td>
                            <td>
                                <?php if($payment->status === 'pending'): ?>
                                    <?php if($payment->serviceOrder->status === 'waiting_cashier'): ?>
                                        <span class="muted">Menunggu kasir mengirim rincian biaya</span>
                                    <?php elseif($payment->serviceOrder->status === 'waiting_approval'): ?>
                                        <?php if(auth()->user()->isRole('customer')): ?>
                                            <a class="button small" href="<?php echo e(route('service-orders.show', $payment->serviceOrder)); ?>">Setujui &amp; Bayar</a>
                                        <?php else: ?>
                                            <span class="muted">Menunggu persetujuan pelanggan</span>
                                        <?php endif; ?>
                                    <?php elseif(auth()->user()->isRole('customer') && $payment->method === 'Tunai'): ?>
                                        <span class="muted">Menunggu kasir (Tunai)</span>
                                    <?php endif; ?>
                                <?php elseif($payment->invoice): ?>
                                    <a class="button secondary small" href="<?php echo e(route('payments.invoice', $payment->invoice)); ?>">Invoice</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="6" class="muted">Belum ada pembayaran.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="pagination"><?php echo e($payments->links()); ?></div>
    </section>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    function toggleForm(formId, btn) {
        const form = document.getElementById(formId);
        const isHidden = form.style.display === 'none';
        form.style.display = isHidden ? 'block' : 'none';
        if (btn) btn.textContent = isHidden ? 'Tutup' : 'Konfirmasi Pembayaran';
    }

    function calcChange(input, total, elId) {
        const received = parseInt(input.value) || 0;
        const el = document.getElementById(elId);
        if (received >= total) {
            const change = received - total;
            el.textContent = 'Rp ' + change.toLocaleString('id-ID');
            el.style.color = 'var(--ok)';
            el.style.borderColor = 'var(--ok)';
        } else if (received > 0) {
            el.textContent = 'Kurang Rp ' + (total - received).toLocaleString('id-ID');
            el.style.color = 'var(--danger)';
            el.style.borderColor = 'var(--danger)';
        } else {
            el.textContent = '';
            el.style.color = 'var(--muted)';
            el.style.borderColor = 'var(--line)';
        }
    }

    function handleCustomerPayment(e, form) {
        const method = form.querySelector('select[name="method"]').value;
        if (method === 'ZeroPay') {
            e.preventDefault();
            if (window.runZeroPaySimulation) {
                window.runZeroPaySimulation({
                    form,
                    title: 'Memproses Pembayaran',
                    subtitle: 'Tagihan servis sedang diproses melalui ZeroPay.',
                    status: 'Validasi saldo pelanggan...',
                    duration: 1400
                });
            } else {
                form.submit();
            }
        } else if (method === 'Tunai') {
            // Use modal confirm; prevent default then submit if user confirms
            e.preventDefault();
            window.showConfirm('Konfirmasi Pembayaran', 'Pilih metode pembayaran tunai?').then(ok => {
                if (ok) form.submit();
            });
        }
    }
</script>

<?php if(session('show_nota_cashier')): ?>
    <div id="notaOverlay" style="position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.8); display:flex; align-items:center; justify-content:center; z-index:99999; cursor:pointer;" onclick="clickNota()">
        <div style="background:#fff; color:#000; padding:40px; border-radius:8px; max-width:400px; width:100%; text-align:center; box-shadow:0 10px 30px rgba(0,0,0,0.5); user-select:none; font-family:monospace;">
            <h1 style="margin-bottom:10px; border-bottom:2px dashed #ccc; padding-bottom:10px;">NOTA SERVIS</h1>
            <p>Terima kasih telah membayar tunai.</p>
            <div style="margin:20px 0; font-size:40px;">NOTA</div>
            <p style="font-size:12px; color:#666;">Ketuk nota 3 kali untuk mencatat pemberian ke pelanggan.</p>
            <div id="clickCounter" style="margin-top:15px; font-weight:bold; font-size:18px; color:var(--primary);">Klik: 0/3</div>
        </div>
    </div>
    
    <script>
        let notaClicks = 0;
        function clickNota() {
            notaClicks++;
            document.getElementById('clickCounter').innerText = `Klik: ${notaClicks}/3`;
            
            if (notaClicks >= 3) {
                document.getElementById('notaOverlay').style.display = 'none';
                
                // Hit AJAX to give nota
                fetch("<?php echo e(route('payments.give-nota', session('show_nota_cashier'))); ?>", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    }
                }).then(() => {
                    window.location.reload();
                });
            }
        }
    </script>
<?php endif; ?>

<?php if(session('show_zeropay_receipt')): ?>
    <div id="zeropayReceiptOverlay" style="position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.8); display:flex; align-items:center; justify-content:center; z-index:99999;">
        <div style="background:#fff; color:#000; padding:40px; border-radius:8px; max-width:400px; width:100%; text-align:center; box-shadow:0 10px 30px rgba(0,0,0,0.5); font-family:monospace; position:relative; overflow:hidden;">
            <div style="position:absolute; top:20%; left:50%; transform:translate(-50%, -50%) rotate(-15deg); font-size:60px; color:rgba(46,204,113,0.3); font-weight:900; border:6px solid rgba(46,204,113,0.3); padding:10px 30px; border-radius:15px; pointer-events:none;">LUNAS</div>
            <h2 style="margin-bottom:10px; border-bottom:2px dashed #ccc; padding-bottom:10px;">RECEIPT ZEROPAY</h2>
            <p style="font-weight:bold; font-size:18px;">Pembayaran Berhasil!</p>
            <p style="font-size:14px; margin-top:10px;">Terima kasih telah menggunakan ZeroPay.</p>
        </div>
    </div>
    <script>
        setTimeout(() => {
            const overlay = document.getElementById('zeropayReceiptOverlay');
            if (overlay) overlay.style.display = 'none';
        }, 3000);
    </script>
<?php endif; ?>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ProyekTI\resources\views/payments/index.blade.php ENDPATH**/ ?>