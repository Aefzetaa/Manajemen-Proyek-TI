@extends('layouts.app')

@section('title', auth()->user()->isRole('mechanic') ? 'ACC Booking' : 'Booking')
@section('eyebrow', auth()->user()->isRole('customer') ? 'Jadwal dan status booking Anda' : (auth()->user()->isRole('mechanic') ? 'Konfirmasi booking pelanggan' : 'Jadwal servis pelanggan'))

@section('actions')
    @if(auth()->user()->isRole('customer'))
        <a class="button" href="{{ route('bookings.create') }}">Booking Baru</a>
    @endif
@endsection

@section('content')
    <section class="panel">
        @unless(auth()->user()->hasAnyRole(['customer', 'mechanic']))
            <form method="GET" class="toolbar" style="margin-bottom:14px;">
                <input type="date" name="date" value="{{ request('date') }}" style="max-width:190px;">
                <select name="status" style="max-width:210px;">
                    <option value="">Semua status</option>
                    @foreach($statuses as $key => $label)
                        <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
                <button class="button secondary" type="submit">Filter</button>
                <a class="button secondary" href="{{ route('bookings.index') }}">Reset</a>
            </form>
        @endunless

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        @unless(auth()->user()->isRole('customer'))
                            <th>Pelanggan</th>
                        @endunless
                        <th>Kendaraan</th>
                        <th>Layanan</th>
                        <th>Jadwal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr>
                            @unless(auth()->user()->isRole('customer'))
                                <td>{{ $booking->user->username }}<br><span class="muted">{{ $booking->user->phone }}</span></td>
                            @endunless
                            <td>{{ $booking->vehicle?->plate_number }}<br><span class="muted">{{ $booking->vehicle?->brand }} {{ $booking->vehicle?->model }}</span></td>
                            <td>{{ $booking->serviceTypes->pluck('name')->join(', ') }}<br><span class="muted">{{ $booking->service_description }}</span></td>
                            <td>{{ $booking->booking_date->format('d/m/Y') }}<br><span class="muted">{{ substr($booking->booking_time, 0, 5) }}</span></td>
                            <td><span class="status {{ $booking->status }}">{{ $booking->statusLabel() }}</span></td>
                            <td>
                                <div class="toolbar">
                                    @if(auth()->user()->isRole('mechanic'))
                                        @if($booking->status === 'scheduled')
                                            <form method="POST" action="{{ route('bookings.accept', $booking) }}">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="button small">Ambil Pekerjaan</button>
                                            </form>
                                        @elseif($booking->status === 'accepted' && $booking->mechanic_id === auth()->user()->id)
                                            <form method="POST" action="{{ route('bookings.start-work', $booking) }}">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="button small">Mulai Kerjakan</button>
                                            </form>
                                        @elseif($booking->status === 'in_progress' && ! $booking->serviceOrder && $booking->mechanic_id === auth()->user()->id)
                                            <a class="button small" href="{{ route('service-orders.create', $booking) }}">Buat Rincian Servis</a>
                                        @endif
                                    @elseif(auth()->user()->isRole('owner') && ! $booking->serviceOrder && $booking->status !== 'canceled')
                                        <a class="button small" href="{{ route('service-orders.create', $booking) }}">Input Servis</a>
                                    @endif
                                    @if(auth()->user()->isRole('owner'))
                                        <form method="POST" action="{{ route('bookings.status', $booking) }}">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" onchange="this.form.submit()" style="min-width:145px;">
                                                @foreach($statuses as $key => $label)
                                                    <option value="{{ $key }}" @selected($booking->status === $key)>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </form>
                                    @endif
                                    @if(auth()->user()->isRole('customer') && $booking->status === 'scheduled')
                                        @if($booking->created_at->diffInMinutes(now()) <= 60 && $booking->mechanic_id === null)
                                            <a class="button small secondary" href="{{ route('bookings.edit', $booking) }}">Edit</a>
                                        @endif
                                        <form method="POST" action="{{ route('bookings.cancel', $booking) }}" data-confirm="Yakin ingin membatalkan booking ini?">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="button small" style="background:var(--danger); border-color:var(--danger); color:#fff;">Batalkan Booking</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="muted">Belum ada booking.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination">{{ $bookings->links() }}</div>
    </section>
@endsection
