<?php $__env->startSection('title', 'Booking Baru'); ?>
<?php $__env->startSection('eyebrow', 'Pilih layanan dan jadwal'); ?>

<?php $__env->startSection('content'); ?>
    <style>
        .booking-service-line {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 18px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .booking-service-name {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            font-weight: 700;
            color: var(--ink);
        }
        .booking-discount-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 22px;
            padding: 0 8px;
            border-radius: 999px;
            background: var(--accent-soft);
            color: var(--accent);
            font-size: 11px;
            font-weight: 900;
        }
        .booking-price-box {
            min-width: 118px;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            justify-content: center;
            line-height: 1.2;
        }
        .booking-price-old {
            color: var(--muted);
            font-size: 12px;
            font-weight: 800;
            text-decoration: line-through;
            text-decoration-thickness: 2px;
        }
        .booking-price-final {
            color: var(--primary);
            font-size: 14px;
            font-weight: 850;
        }
    </style>
    <section class="panel">
        <form method="POST" action="<?php echo e(route('bookings.store')); ?>" class="form-grid">
            <?php echo csrf_field(); ?>
            <div class="field full" style="position:relative;">
                <label>Jenis Servis (Bisa pilih lebih dari satu)</label>
                
                <div class="custom-multi-select" onclick="toggleDropdown()" style="cursor:pointer; border-radius:8px; border:1px solid var(--line); padding:12px; background:var(--bg); display:flex; justify-content:space-between; align-items:center; transition:border-color 0.2s;">
                    <span id="dropdownLabel" style="color:var(--muted); font-weight:500;">Pilih layanan...</span>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color:var(--muted);"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </div>

                <div id="dropdownMenu" style="display:none; position:absolute; top:100%; left:0; right:0; background:var(--panel-solid); border:1px solid var(--line); border-radius:10px; margin-top:6px; box-shadow:var(--shadow-soft); z-index:100; max-height:280px; overflow-y:auto; padding:6px 0;">
                    <?php $__currentLoopData = $serviceTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $discountPercent = $type->discountPercent();
                            $discountedPrice = $type->discountedPrice();
                        ?>
                        <label class="dropdown-item booking-service-line" onmouseover="this.style.background='var(--table-head)'" onmouseout="this.style.background='transparent'">
                            <input type="checkbox" name="service_type_ids[]" value="<?php echo e($type->id); ?>" 
                                <?php if(is_array(old('service_type_ids')) && in_array($type->id, old('service_type_ids'))): echo 'checked'; endif; ?>
                                onchange="updateDropdownLabel()" data-name="<?php echo e($type->name); ?>" style="margin:0; width:18px; height:18px; accent-color:var(--primary);">
                            <span class="booking-service-name">
                                <?php echo e($type->name); ?>

                                <?php if($type->hasActiveDiscount()): ?>
                                    <span class="booking-discount-badge"><?php echo e($discountPercent); ?>%</span>
                                <?php endif; ?>
                            </span>
                            <span class="booking-price-box">
                                <?php if($type->hasActiveDiscount()): ?>
                                    <span class="booking-price-old">Rp <?php echo e(number_format($type->base_price, 0, ',', '.')); ?></span>
                                    <span class="booking-price-final">Rp <?php echo e(number_format($discountedPrice, 0, ',', '.')); ?></span>
                                <?php else: ?>
                                    <span class="booking-price-final">Rp <?php echo e(number_format($type->base_price, 0, ',', '.')); ?></span>
                                <?php endif; ?>
                            </span>
                        </label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <div class="field">
                <label for="vehicle_id">Kendaraan Tersimpan</label>
                <select id="vehicle_id" name="vehicle_id">
                    <option value="">Tambah kendaraan baru</option>
                    <?php $__currentLoopData = $vehicles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vehicle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($vehicle->id); ?>" <?php if(old('vehicle_id') == $vehicle->id): echo 'selected'; endif; ?>>
                            <?php echo e($vehicle->plate_number); ?> - <?php echo e($vehicle->brand); ?> <?php echo e($vehicle->model); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="field">
                <label for="booking_date">Tanggal</label>
                <input id="booking_date" name="booking_date" type="date" min="<?php echo e(now()->toDateString()); ?>" value="<?php echo e(old('booking_date', now()->addDay()->toDateString())); ?>" required>
            </div>
            <div class="field full">
                <label>Slot Jam Tersedia</label>
                <div class="slot-grid">
                    <?php $__currentLoopData = $slots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label class="slot-option" data-slot-option>
                            <span><?php echo e($slot); ?></span>
                            <span class="muted" data-slot-state>Tersedia</span>
                            <input name="booking_time" type="radio" value="<?php echo e($slot); ?>" <?php if(old('booking_time', $slots[0]) === $slot): echo 'checked'; endif; ?> required>
                        </label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <div class="field" data-new-vehicle>
                <label for="plate_number">Nomor Polisi</label>
                <input id="plate_number" name="plate_number" value="<?php echo e(old('plate_number')); ?>" placeholder="H 1234 AB">
            </div>
            <div class="field" data-new-vehicle>
                <label for="brand">Merek</label>
                <input id="brand" name="brand" value="<?php echo e(old('brand')); ?>" placeholder="Honda">
            </div>
            <div class="field" data-new-vehicle>
                <label for="model">Model</label>
                <input id="model" name="model" value="<?php echo e(old('model')); ?>" placeholder="Beat">
            </div>
            <div class="field" data-new-vehicle>
                <label for="year">Tahun</label>
                <input id="year" name="year" type="number" min="1980" max="<?php echo e(now()->year); ?>" value="<?php echo e(old('year')); ?>">
            </div>
            <div class="field full">
                <label for="service_description">Keluhan / Kebutuhan Servis</label>
                <textarea id="service_description" name="service_description"><?php echo e(old('service_description')); ?></textarea>
            </div>
            <div class="field full">
                <button class="button" type="submit">Simpan Booking</button>
            </div>
        </form>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        const takenSlotsByDate = <?php echo json_encode($takenSlots, 15, 512) ?>;
        const dateInput = document.getElementById('booking_date');
        const vehicleSelect = document.getElementById('vehicle_id');
        const newVehicleFields = document.querySelectorAll('[data-new-vehicle]');

        function toggleDropdown() {
            const menu = document.getElementById('dropdownMenu');
            menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
        }

        function updateDropdownLabel() {
            const checkboxes = document.querySelectorAll('input[name="service_type_ids[]"]:checked');
            const label = document.getElementById('dropdownLabel');
            if (checkboxes.length === 0) {
                label.textContent = 'Pilih layanan...';
                label.style.color = 'var(--muted)';
            } else if (checkboxes.length === 1) {
                label.textContent = checkboxes[0].getAttribute('data-name');
                label.style.color = 'var(--ink)';
            } else {
                label.textContent = checkboxes.length + ' layanan dipilih';
                label.style.color = 'var(--ink)';
            }
        }

        document.addEventListener('click', function(e) {
            const container = document.querySelector('.custom-multi-select').parentElement;
            if (!container.contains(e.target)) {
                document.getElementById('dropdownMenu').style.display = 'none';
            }
        });

        document.addEventListener('DOMContentLoaded', updateDropdownLabel);

        function syncVehicleFields() {
            const useSavedVehicle = Boolean(vehicleSelect && vehicleSelect.value);

            newVehicleFields.forEach((field) => {
                field.classList.toggle('hide', useSavedVehicle);
                field.querySelectorAll('input').forEach((input) => {
                    input.disabled = useSavedVehicle;
                });
            });
        }

        function syncSlots() {
            const takenSlots = new Set(takenSlotsByDate[dateInput.value] || []);
            let firstAvailable = null;

            document.querySelectorAll('[data-slot-option]').forEach((slot) => {
                const input = slot.querySelector('input');
                const state = slot.querySelector('[data-slot-state]');
                const isTaken = takenSlots.has(input.value);

                slot.classList.toggle('is-taken', isTaken);
                input.disabled = isTaken;
                state.textContent = isTaken ? 'Sold out' : 'Tersedia';

                if (isTaken && input.checked) {
                    input.checked = false;
                }

                if (! isTaken && ! firstAvailable) {
                    firstAvailable = input;
                }
            });

            if (! document.querySelector('input[name="booking_time"]:checked') && firstAvailable) {
                firstAvailable.checked = true;
            }
        }

        vehicleSelect?.addEventListener('change', syncVehicleFields);
        dateInput?.addEventListener('change', syncSlots);
        syncVehicleFields();
        syncSlots();
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ProyekTI\resources\views/bookings/create.blade.php ENDPATH**/ ?>