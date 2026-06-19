@extends('layouts.app')

@section('title', 'Aktivitas')
@section('eyebrow', 'Aktivitas profil dan transaksi saldo Anda')

@section('content')
<section class="panel">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Aktivitas</th>
                    <th>Detail</th>
                </tr>
            </thead>
            <tbody>
                @forelse($activities as $activity)
                    <tr>
                        <td class="muted">{{ $activity->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            @if($activity->type === 'money_in')
                                <span style="display:inline-flex; align-items:center; gap:6px; color:var(--ok); font-weight:700;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    +Rp {{ number_format($activity->amount, 0, ',', '.') }}
                                </span>
                            @elseif($activity->type === 'money_out')
                                <span style="display:inline-flex; align-items:center; gap:6px; color:var(--danger); font-weight:700;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                    -Rp {{ number_format(abs($activity->amount), 0, ',', '.') }}
                                </span>
                            @else
                                <span style="display:inline-flex; align-items:center; gap:6px; color:var(--primary); font-weight:700;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                    Perubahan Profil
                                </span>
                            @endif
                        </td>
                        <td>
                            <span style="font-size:14px;">{{ $activity->description }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="muted" style="text-align:center; padding:30px;">
                            Belum ada riwayat aktivitas di akun ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($activities->hasPages())
        <div class="pagination" style="margin-top:14px;">{{ $activities->links() }}</div>
    @endif
</section>
@endsection
