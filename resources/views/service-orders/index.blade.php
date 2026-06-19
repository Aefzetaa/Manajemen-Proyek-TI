@extends('layouts.app')

@section('title', auth()->user()->isRole('customer') ? 'Riwayat' : (auth()->user()->hasAnyRole(['mechanic', 'cashier']) ? 'Gaji' : 'Servis'))
@section('eyebrow', auth()->user()->isRole('customer') ? 'Riwayat servis kendaraan Anda' : (auth()->user()->hasAnyRole(['mechanic', 'cashier']) ? 'Ringkasan gaji dan pekerjaan' : 'Detail pekerjaan mekanik dan biaya pelanggan'))

@section('content')
    <section class="panel">
        @if(auth()->user()->isRole('customer'))
            <form method="GET" class="toolbar" style="margin-bottom:14px;">
                <select name="year" onchange="this.form.submit()" style="max-width:210px;">
                    @php $currentYear = request('year', date('Y')); @endphp
                    @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                        <option value="{{ $y }}" @selected((string) $currentYear === (string) $y)>{{ $y }}</option>
                    @endfor
                </select>
                <a class="button secondary" href="{{ route('service-orders.index') }}">Reset</a>
            </form>
        @elseif(auth()->user()->hasAnyRole(['mechanic', 'cashier']))
            <form method="GET" class="toolbar" style="margin-bottom:24px;">
                <input type="month" name="month" value="{{ request('month', date('Y-m')) }}" onchange="this.form.submit()" style="max-width:210px;">
                <a class="button secondary" href="{{ route('service-orders.index') }}">Kembali ke Bulan Ini</a>
            </form>
            
            @php 
                $monthName = \Carbon\Carbon::parse(request('month', date('Y-m')))->translatedFormat('F Y');
            @endphp
            <div style="text-align:center; padding: 40px 20px; background:var(--bg-soft); border-radius:12px; border:1px solid var(--line);">
                <h3 style="margin-bottom:8px; color:var(--muted);">Total Gaji Bersih Anda Bulan {{ $monthName }}</h3>
                <h1 style="color:var(--ok); font-size:42px; margin:0;">Rp {{ number_format($mechanicTotalSalary, 0, ',', '.') }}</h1>
            </div>
        @else
            <form method="GET" class="toolbar" style="margin-bottom:14px;">
                <input type="date" name="from" value="{{ request('from') }}" aria-label="Dari tanggal">
                <input type="date" name="to" value="{{ request('to') }}" aria-label="Sampai tanggal">
                <select name="service_type_id">
                    <option value="">Semua jenis servis</option>
                    @foreach($serviceTypes as $type)
                        <option value="{{ $type->id }}" @selected((string) request('service_type_id') === (string) $type->id)>{{ $type->name }}</option>
                    @endforeach
                </select>
                <select name="status">
                    <option value="">Semua status</option>
                    @foreach($statuses as $key => $label)
                        <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
                <button class="button secondary" type="submit">Filter</button>
                <a class="button secondary" href="{{ route('service-orders.index') }}">Reset</a>
            </form>
        @endif

        @unless(auth()->user()->hasAnyRole(['mechanic', 'cashier']))
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        @unless(auth()->user()->isRole('customer'))
                            <th>Pelanggan</th>
                        @endunless
                        <th>Kendaraan</th>
                        <th>Diagnosis</th>
                        <th>Total</th>
                        <th>Status</th>
                        @unless(auth()->user()->isRole('customer'))
                            <th>Aksi</th>
                        @endunless
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr @if(auth()->user()->isRole('customer')) style="cursor:pointer;" onclick="window.location='{{ route('service-orders.show', $order) }}'" @endif>
                            <td>{{ $order->serviced_at->format('d/m/Y') }}</td>
                            @unless(auth()->user()->isRole('customer'))
                                <td>{{ $order->customer->name }}</td>
                            @endunless
                            <td>{{ $order->vehicle?->plate_number }}<br><span class="muted">{{ $order->serviceType?->name }}</span></td>
                            <td>{{ $order->diagnosis }}</td>
                            <td>Rp {{ number_format($order->total_cost, 0, ',', '.') }}</td>
                            <td><span class="status {{ $order->status }}">{{ $order->statusLabel() }}</span></td>
                            @unless(auth()->user()->isRole('customer'))
                                <td><a class="button secondary small" href="{{ route('service-orders.show', $order) }}">Detail</a></td>
                            @endunless
                        </tr>
                    @empty
                        <tr><td colspan="7" class="muted">Belum ada data servis.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination">{{ $orders->links() }}</div>
        @endunless
    </section>
@endsection
