@extends('layouts.app')

@section('title', 'Input Servis')
@section('eyebrow', 'Catatan mekanik untuk transaksi')

@section('content')
    <div class="grid grid-2">
        <section class="panel">
            <h2>Data Booking</h2>
            <p><strong>{{ $booking->user->name }}</strong></p>
            <p class="muted">{{ $booking->vehicle?->plate_number }} - {{ $booking->vehicle?->brand }} {{ $booking->vehicle?->model }}</p>
            <p>{{ $booking->serviceTypes->pluck('name')->join(', ') }} pada {{ $booking->booking_date->format('d/m/Y') }} {{ substr($booking->booking_time, 0, 5) }}</p>
            <p class="muted">{{ $booking->service_description }}</p>
        </section>

        <section class="panel">
            <h2>Detail Pekerjaan</h2>
            <form method="POST" action="{{ route('service-orders.store', $booking) }}" class="stack">
                @csrf
                <div class="field">
                    <label for="serviced_at">Tanggal Servis</label>
                    <input id="serviced_at" name="serviced_at" type="date" value="{{ old('serviced_at', now()->toDateString()) }}" required>
                </div>
                <div class="field">
                    <label for="complaint">Keluhan</label>
                    <textarea id="complaint" name="complaint" readonly>{{ old('complaint', $booking->service_description) }}</textarea>
                </div>
                <div class="field">
                    <label for="diagnosis">Diagnosis / Pekerjaan</label>
                    <textarea id="diagnosis" name="diagnosis" required>{{ old('diagnosis') }}</textarea>
                </div>
                <div class="field" style="display: none;">
                    <label for="labor_cost">Biaya Jasa</label>
                    <input id="labor_cost" name="labor_cost" type="number" value="{{ $booking->serviceTypes->sum('base_price') }}" readonly>
                </div>

                <div class="field">
                    <label>Sparepart Digunakan</label>
                    <div class="stack">
                        @foreach($spareParts as $part)
                            <div class="line-item">
                                <div>
                                    <strong>{{ $part->name }}</strong><br>
                                    <span class="muted">Stok {{ $part->stock }} | Rp {{ number_format($part->price, 0, ',', '.') }}</span>
                                </div>
                                <input name="parts[{{ $part->id }}]" type="number" min="0" max="{{ $part->stock }}" value="{{ old('parts.' . $part->id, 0) }}">
                                <span class="muted">Jumlah</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <button class="button" type="submit">Simpan Detail Servis</button>
            </form>
        </section>
    </div>
@endsection
