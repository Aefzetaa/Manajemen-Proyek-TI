@extends('layouts.app')

@section('title', 'Edit Booking')
@section('eyebrow', 'Ubah layanan dan jadwal booking Anda')

@section('content')
    <style>
        .booking-service-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 10px;
            margin-top: 5px;
        }
        .booking-service-card {
            display: grid;
            grid-template-columns: auto minmax(0, 1fr) auto;
            align-items: center;
            gap: 10px;
            padding: 12px;
            border: 1px solid var(--line);
            border-radius: 10px;
            background: var(--panel-solid);
            cursor: pointer;
        }
        .booking-service-name {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            font-weight: 750;
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
        <form method="POST" action="{{ route('bookings.update', $booking) }}" class="form-grid">
            @csrf
            @method('PATCH')
            <div class="field full">
                <label>Jenis Servis (Bisa pilih lebih dari satu)</label>
                <div class="booking-service-grid">
                    @php
                        $selectedServices = old('service_type_ids', $booking->serviceTypes->pluck('id')->toArray());
                    @endphp
                    @foreach($serviceTypes as $type)
                        @php
                            $discountPercent = $type->discountPercent();
                            $discountedPrice = $type->discountedPrice();
                        @endphp
                        <label class="booking-service-card">
                            <input type="checkbox" name="service_type_ids[]" value="{{ $type->id }}" 
                                @checked(in_array($type->id, $selectedServices))>
                            <span class="booking-service-name">
                                {{ $type->name }}
                                @if($type->hasActiveDiscount())
                                    <span class="booking-discount-badge">{{ $discountPercent }}%</span>
                                @endif
                            </span>
                            <span class="booking-price-box">
                                @if($type->hasActiveDiscount())
                                    <span class="booking-price-old">Rp {{ number_format($type->base_price, 0, ',', '.') }}</span>
                                    <span class="booking-price-final">Rp {{ number_format($discountedPrice, 0, ',', '.') }}</span>
                                @else
                                    <span class="booking-price-final">Rp {{ number_format($type->base_price, 0, ',', '.') }}</span>
                                @endif
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>
            <div class="field">
                <label for="vehicle_id">Kendaraan Tersimpan</label>
                <select id="vehicle_id" name="vehicle_id">
                    <option value="">Tambah kendaraan baru</option>
                    @foreach($vehicles as $vehicle)
                        <option value="{{ $vehicle->id }}" @selected(old('vehicle_id', $booking->vehicle_id) == $vehicle->id)>
                            {{ $vehicle->plate_number }} - {{ $vehicle->brand }} {{ $vehicle->model }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label for="booking_date">Tanggal</label>
                <input id="booking_date" name="booking_date" type="date" min="{{ now()->toDateString() }}" value="{{ old('booking_date', $booking->booking_date->toDateString()) }}" required>
            </div>
            <div class="field full">
                <label>Slot Jam Tersedia</label>
                <div class="slot-grid">
                    @foreach($slots as $slot)
                        <label class="slot-option" data-slot-option>
                            <span>{{ $slot }}</span>
                            <span class="muted" data-slot-state>Tersedia</span>
                            @php
                                $bookingTimeStr = substr((string)$booking->booking_time, 0, 5);
                            @endphp
                            <input name="booking_time" type="radio" value="{{ $slot }}" @checked(old('booking_time', $bookingTimeStr) === $slot) required>
                        </label>
                    @endforeach
                </div>
            </div>
            <div class="field" data-new-vehicle>
                <label for="plate_number">Nomor Polisi</label>
                <input id="plate_number" name="plate_number" value="{{ old('plate_number') }}" placeholder="H 1234 AB">
            </div>
            <div class="field" data-new-vehicle>
                <label for="brand">Merek</label>
                <input id="brand" name="brand" value="{{ old('brand') }}" placeholder="Honda">
            </div>
            <div class="field" data-new-vehicle>
                <label for="model">Model</label>
                <input id="model" name="model" value="{{ old('model') }}" placeholder="Beat">
            </div>
            <div class="field" data-new-vehicle>
                <label for="year">Tahun</label>
                <input id="year" name="year" type="number" min="1980" max="{{ now()->year }}" value="{{ old('year') }}">
            </div>
            <div class="field full">
                <label for="service_description">Keluhan / Kebutuhan Servis</label>
                <textarea id="service_description" name="service_description">{{ old('service_description', $booking->service_description) }}</textarea>
            </div>
            <div class="field full">
                <button class="button" type="submit">Simpan Perubahan</button>
            </div>
        </form>
    </section>
@endsection

@section('scripts')
    <script>
        const takenSlotsByDate = @json($takenSlots);
        const dateInput = document.getElementById('booking_date');
        const vehicleSelect = document.getElementById('vehicle_id');
        const newVehicleFields = document.querySelectorAll('[data-new-vehicle]');

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
                state.textContent = isTaken ? 'Terisi' : 'Tersedia';

                // Allow the currently checked slot if it's the one we are editing
                if (input.checked && isTaken && input.value !== '{{ substr((string)$booking->booking_time, 0, 5) }}' || dateInput.value !== '{{ $booking->booking_date->toDateString() }}' && isTaken) {
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
@endsection
