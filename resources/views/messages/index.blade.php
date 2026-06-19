@extends('layouts.app')

@section('title', 'Pesan')
@section('eyebrow', 'Nota dan Pemberitahuan')

@section('content')
<div class="grid grid-2">
    <div class="stack">
        @forelse($messages as $message)
            <div class="panel" style="display:flex; justify-content:space-between; align-items:center; {{ !$message->is_read ? 'border-left: 4px solid var(--primary);' : 'opacity: 0.8;' }}">
                <div>
                    <h3 style="margin:0 0 5px 0;">{{ $message->title }}</h3>
                    <p class="muted" style="margin:0; font-size:12px;">{{ $message->created_at->diffForHumans() }}</p>
                </div>
                <div>
                    @if(Str::startsWith($message->body, 'http'))
                        <a href="{{ route('messages.read', $message) }}" class="button small {{ $message->is_read ? 'secondary' : '' }}">Lihat Nota</a>
                    @else
                        @if(!$message->is_read)
                            <a href="{{ route('messages.read', $message) }}" class="button small">Tandai Dibaca</a>
                        @endif
                    @endif
                </div>
            </div>
        @empty
            <div class="panel" style="text-align:center; padding:30px; color:var(--muted);">
                Belum ada pesan.
            </div>
        @endforelse
    </div>
</div>
@endsection
