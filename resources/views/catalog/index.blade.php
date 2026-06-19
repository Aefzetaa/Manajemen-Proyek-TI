@extends('layouts.app')

@section('title', 'Katalog')
@section('eyebrow', 'Kelola Layanan dan Suku Cadang')

@section('content')
<style>
    .catalog-tabs {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .catalog-tab {
        min-height: 44px;
        padding: 0 18px;
        border: 1px solid var(--line);
        border-radius: 12px;
        background: color-mix(in srgb, var(--panel) 90%, transparent);
        color: var(--muted);
        font-weight: 900;
        cursor: pointer;
    }

    .catalog-tab.is-active {
        border-color: color-mix(in srgb, var(--primary) 45%, transparent);
        background: color-mix(in srgb, var(--primary-soft) 72%, transparent);
        color: var(--primary-dark);
        box-shadow: 0 14px 34px color-mix(in srgb, var(--primary) 13%, transparent);
    }

    .catalog-workspace {
        display: grid;
        gap: 22px;
    }

    .catalog-workspace > .stack {
        display: contents;
    }

    .catalog-section {
        display: none;
    }

    .catalog-section.is-active {
        display: block;
    }

    .catalog-form {
        order: 1;
    }

    .catalog-table {
        order: 2;
    }

    .catalog-section .table-wrap {
        max-height: none !important;
    }

    .catalog-form.is-active {
        display: none;
    }

    .catalog-form.is-active.is-open {
        display: block;
    }

    .catalog-section-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 14px;
        margin-bottom: 16px;
    }

    .catalog-add-trigger {
        min-height: 40px;
        padding: 0 14px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
    }

    .catalog-add-trigger:disabled {
        cursor: not-allowed;
        opacity: 0.55;
        filter: grayscale(0.45);
        box-shadow: none;
    }

    .catalog-form-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        margin-bottom: 20px;
    }

    .catalog-form-title {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .catalog-form-close {
        width: 38px;
        height: 38px;
        border: 1px solid var(--line);
        border-radius: 10px;
        background: color-mix(in srgb, var(--panel) 92%, transparent);
        color: var(--muted);
        display: inline-grid;
        place-items: center;
        cursor: pointer;
        flex: 0 0 auto;
    }

    .catalog-form-close:hover {
        color: var(--ink);
        border-color: color-mix(in srgb, var(--primary) 35%, var(--line));
        background: var(--primary-soft);
    }

    @media (max-width: 720px) {
        .catalog-section-head {
            align-items: stretch;
            flex-direction: column;
        }

        .catalog-add-trigger {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="catalog-tabs" role="tablist" aria-label="Kategori Katalog">
    <button type="button" class="catalog-tab is-active" data-catalog-tab="promo">Promo</button>
    <button type="button" class="catalog-tab" data-catalog-tab="service">Layanan & Service</button>
    <button type="button" class="catalog-tab" data-catalog-tab="sparepart">Sparepart</button>
</div>

<div class="catalog-workspace">
    
    {{-- KOLOM KIRI: TABEL DATA --}}
    <div class="stack" style="gap:24px;">
        
        {{-- Tabel Jenis Servis --}}
        <section class="panel catalog-section catalog-table" data-catalog-panel="service">
            <div class="catalog-section-head">
                <h2 style="margin:0; font-size:18px;">Daftar Jenis Servis</h2>
                <button type="button" class="button catalog-add-trigger" data-catalog-form-open="service">
                    <span aria-hidden="true">+</span>
                    <span>Tambah Layanan</span>
                </button>
            </div>
            
            <div class="table-wrap" style="max-height:400px; overflow-y:auto;">
                <table>
                    <thead style="background:var(--bg); position:sticky; top:0; z-index:1;">
                        <tr><th>Nama Layanan</th><th>Waktu</th><th>Harga</th><th style="text-align:center;">Aksi</th></tr>
                    </thead>
                    <tbody>
                        @forelse($serviceTypes as $type)
                            <tr style="transition:0.2s;">
                                <td style="font-weight:600; color:var(--ink);">{{ $type->name }}</td>
                                <td><span style="background:var(--bg); padding:4px 8px; border-radius:6px; font-size:12px; border:1px solid var(--line);">{{ $type->estimated_minutes }} mnt</span></td>
                                <td style="color:var(--primary); font-weight:700;">Rp {{ number_format($type->base_price, 0, ',', '.') }}</td>
                                <td style="text-align:center;">
                                    <div style="display:flex; gap:8px; justify-content:center; flex-wrap:wrap;">
                                        <button class="button small secondary" style="padding:6px 12px; border-radius:8px;" type="button" onclick="document.getElementById('edit-service-{{ $type->id }}').style.display = document.getElementById('edit-service-{{ $type->id }}').style.display === 'none' ? 'table-row' : 'none'">Edit</button>
                                        <form method="POST" action="{{ route('catalog.service-types.destroy', $type) }}" data-confirm="Yakin ingin menghapus layanan ini secara permanen?" style="margin:0;">
                                            @csrf @method('DELETE')
                                            <button class="button small" style="background:var(--danger); border-color:var(--danger); color:#fff; padding:6px; border-radius:8px;" type="submit" title="Hapus">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <tr id="edit-service-{{ $type->id }}" style="display:none; background:var(--bg);">
                                <td colspan="4" style="padding:16px;">
                                    <div style="background:var(--panel); padding:16px; border-radius:12px; border:1px solid var(--line);">
                                        <form method="POST" action="{{ route('catalog.service-types.update', $type) }}" style="display:grid; gap:12px;">
                                            @csrf @method('PATCH')
                                            <div class="grid grid-2" style="gap:12px;">
                                                <input type="text" name="name" value="{{ $type->name }}" required placeholder="Nama Layanan">
                                                <input type="number" name="estimated_minutes" value="{{ $type->estimated_minutes }}" required placeholder="Estimasi Menit">
                                                <input type="number" name="base_price" value="{{ $type->base_price }}" required placeholder="Harga Dasar (Rp)">
                                                <input type="number" name="mechanic_salary" min="0" max="100" value="{{ $type->mechanicSharePercent() }}" required placeholder="Persentase Mekanik (%)">
                                                <input type="number" name="cashier_salary" min="0" max="100" value="{{ $type->cashierSharePercent() }}" required placeholder="Persentase Kasir (%)">
                                            </div>
                                            <div style="display:flex; justify-content:flex-end; gap:8px;">
                                                <button class="button small" type="submit">Simpan Perubahan</button>
                                                <button type="button" class="button small secondary" onclick="document.getElementById('edit-service-{{ $type->id }}').style.display='none'">Batal</button>
                                            </div>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="muted" style="text-align:center; padding:20px;">Belum ada jenis servis terdaftar.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        {{-- Tabel Promo --}}
        <section class="panel catalog-section catalog-table is-active" data-catalog-panel="promo">
            <div class="catalog-section-head">
                <h2 style="margin:0; font-size:18px;">Promo Layanan</h2>
                <button type="button" class="button catalog-add-trigger" data-catalog-form-open="promo">
                    <span aria-hidden="true">+</span>
                    <span>Tambah Promo</span>
                </button>
            </div>

            <div class="table-wrap" style="max-height:360px; overflow-y:auto;">
                <table>
                    <thead style="background:var(--bg); position:sticky; top:0; z-index:1;">
                        <tr><th>Promo</th><th>Layanan</th><th>Diskon</th><th>Status</th><th style="text-align:center;">Aksi</th></tr>
                    </thead>
                    <tbody>
                        @forelse($promotions as $promo)
                            <tr>
                                <td>
                                    <div style="font-weight:700; color:var(--ink);">{{ $promo->title }}</div>
                                    <div style="font-size:11px; color:var(--muted); margin-top:2px;">{{ \Illuminate\Support\Str::limit($promo->description, 80) }}</div>
                                    <div style="font-size:11px; color:var(--muted); margin-top:4px;">
                                        {{ $promo->starts_at?->format('d/m/Y H:i') ?? 'Mulai sekarang' }} - {{ $promo->ends_at?->format('d/m/Y H:i') ?? 'Tanpa batas' }}
                                    </div>
                                </td>
                                <td>{{ $promo->serviceType?->name ?? 'Semua layanan' }}</td>
                                <td style="color:var(--accent); font-weight:800;">{{ $promo->discount_percent }}%</td>
                                <td>
                                    <span class="status {{ $promo->is_active ? 'paid' : 'canceled' }}">{{ $promo->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                                </td>
                                <td style="text-align:center;">
                                    <div style="display:flex; gap:8px; justify-content:center; flex-wrap:wrap;">
                                        <button class="button small secondary" style="padding:6px 12px; border-radius:8px;" type="button" onclick="document.getElementById('edit-promo-{{ $promo->id }}').style.display = document.getElementById('edit-promo-{{ $promo->id }}').style.display === 'none' ? 'table-row' : 'none'">Edit</button>
                                        <form method="POST" action="{{ route('catalog.promotions.destroy', $promo) }}" data-confirm="Yakin ingin menghapus promo ini?" style="margin:0;">
                                            @csrf @method('DELETE')
                                            <button class="button small" style="background:var(--danger); border-color:var(--danger); color:#fff; padding:6px; border-radius:8px;" type="submit">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <tr id="edit-promo-{{ $promo->id }}" style="display:none; background:var(--bg);">
                                <td colspan="5" style="padding:16px;">
                                    <div style="background:var(--panel); padding:16px; border-radius:12px; border:1px solid var(--line);">
                                        <form method="POST" action="{{ route('catalog.promotions.update', $promo) }}" style="display:grid; gap:12px;">
                                            @csrf @method('PATCH')
                                            <div class="grid grid-2" style="gap:12px;">
                                                <input type="text" name="title" value="{{ $promo->title }}" required placeholder="Judul Promo">
                                                <select name="service_type_id">
                                                    <option value="">Semua layanan</option>
                                                    @foreach($serviceTypes as $type)
                                                        <option value="{{ $type->id }}" @selected($promo->service_type_id === $type->id)>{{ $type->name }}</option>
                                                    @endforeach
                                                </select>
                                                <input type="number" name="discount_percent" min="1" max="100" value="{{ $promo->discount_percent }}" required placeholder="Diskon (%)">
                                                <label style="display:flex; align-items:center; gap:8px; font-weight:700;">
                                                    <input type="checkbox" name="is_active" value="1" @checked($promo->is_active) style="width:auto;"> Aktif
                                                </label>
                                                <input type="datetime-local" name="starts_at" value="{{ $promo->starts_at?->format('Y-m-d\TH:i') }}">
                                                <input type="datetime-local" name="ends_at" value="{{ $promo->ends_at?->format('Y-m-d\TH:i') }}">
                                            </div>
                                            <textarea name="description" rows="3" placeholder="Deskripsi promo">{{ $promo->description }}</textarea>
                                            <div style="display:flex; justify-content:flex-end; gap:8px;">
                                                <button class="button small" type="submit">Simpan Promo</button>
                                                <button type="button" class="button small secondary" onclick="document.getElementById('edit-promo-{{ $promo->id }}').style.display='none'">Batal</button>
                                            </div>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="muted" style="text-align:center; padding:20px;">Belum ada promo. Tambahkan promo agar tampil untuk pelanggan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        {{-- Tabel Sparepart --}}
        <section class="panel catalog-section catalog-table" data-catalog-panel="sparepart">
            <div class="catalog-section-head">
                <h2 style="margin:0; font-size:18px;">Inventaris Sparepart</h2>
                <button type="button" class="button catalog-add-trigger" data-catalog-form-open="sparepart">
                    <span aria-hidden="true">+</span>
                    <span>Tambah Sparepart</span>
                </button>
            </div>

            <div class="table-wrap" style="max-height:450px; overflow-y:auto;">
                <table>
                    <thead style="background:var(--bg); position:sticky; top:0; z-index:1;">
                        <tr><th>Produk & SKU</th><th>Stok</th><th>Harga Satuan</th><th>Harga Jual</th><th style="text-align:center;">Aksi</th></tr>
                    </thead>
                    <tbody>
                        @forelse($spareParts as $part)
                            <tr style="transition:0.2s;">
                                <td>
                                    <div style="font-weight:600; color:var(--ink);">{{ $part->name }}</div>
                                    <div style="font-size:11px; color:var(--muted); font-family:monospace; margin-top:2px;">{{ $part->sku }}</div>
                                </td>
                                <td>
                                    @if($part->stock <= 5)
                                        <span style="background:var(--danger); color:#fff; padding:4px 8px; border-radius:6px; font-size:12px; font-weight:800; border:1px solid var(--danger);">{{ $part->stock }}</span>
                                    @else
                                        <span style="background:var(--bg); padding:4px 8px; border-radius:6px; font-size:12px; font-weight:700; border:1px solid var(--line);">{{ $part->stock }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="dynamic-rng-price" style="font-family:monospace; font-weight:700; color:var(--accent);">Rp {{ number_format(rand(10000, 50000), 0, ',', '.') }}</span>
                                </td>
                                <td style="color:var(--primary); font-weight:700;">Rp {{ number_format($part->price, 0, ',', '.') }}</td>
                                <td style="text-align:center;">
                                    <div style="display:flex; gap:8px; justify-content:center; flex-wrap:wrap;">
                                        <button class="button small secondary" style="padding:6px 12px; border-radius:8px;" type="button" onclick="document.getElementById('edit-part-{{ $part->id }}').style.display = document.getElementById('edit-part-{{ $part->id }}').style.display === 'none' ? 'table-row' : 'none'">Edit</button>
                                        <button class="button small secondary" style="padding:6px 12px; border-radius:8px;" type="button" onclick="showRestockForm({{ $part->id }}, '{{ $part->name }}')">+ Restock</button>
                                        <form method="POST" action="{{ route('catalog.spare-parts.destroy', $part) }}" data-confirm="Yakin ingin menghapus sparepart ini?" style="margin:0;">
                                            @csrf @method('DELETE')
                                            <button class="button small" style="background:var(--danger); border-color:var(--danger); color:#fff; padding:6px; border-radius:8px;" type="submit" title="Hapus">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <tr id="edit-part-{{ $part->id }}" style="display:none; background:var(--bg);">
                                <td colspan="5" style="padding:16px;">
                                    <div style="background:var(--panel); padding:16px; border-radius:12px; border:1px solid var(--line);">
                                        <form method="POST" action="{{ route('catalog.spare-parts.update', $part) }}" style="display:grid; gap:12px;">
                                            @csrf @method('PATCH')
                                            <div class="grid grid-2" style="gap:12px;">
                                                <input type="text" name="name" value="{{ $part->name }}" required placeholder="Nama Sparepart">
                                                <input type="text" name="sku" value="{{ $part->sku }}" required placeholder="SKU">
                                                <input type="number" name="stock" value="{{ $part->stock }}" required placeholder="Stok">
                                                <input type="number" name="price" value="{{ $part->price }}" required placeholder="Harga Jual (Rp)">
                                            </div>
                                            <div style="display:flex; justify-content:flex-end; gap:8px;">
                                                <button class="button small" type="submit">Simpan Perubahan</button>
                                                <button type="button" class="button small secondary" onclick="document.getElementById('edit-part-{{ $part->id }}').style.display='none'">Batal</button>
                                            </div>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <tr id="restock-row-{{ $part->id }}" style="display:none; background:var(--bg);">
                                <td colspan="5" style="padding:16px;">
                                    <div style="background:var(--panel); padding:16px; border-radius:12px; border:1px dashed var(--accent);">
                                        <form method="POST" action="{{ route('catalog.spare-parts.restock', $part) }}" onsubmit="return handleQrisSubmit(event, this);" style="margin:0; display:flex; align-items:flex-end; gap:16px; flex-wrap:wrap;">
                                            @csrf
                                            <div style="flex:1; min-width:150px;">
                                                <label style="font-size:12px; color:var(--muted); font-weight:600; margin-bottom:4px; display:block;">Restock <strong>{{ $part->name }}</strong></label>
                                                <input type="number" name="added_stock" min="1" required placeholder="Jumlah item..." style="width:100%; border:1px solid var(--line); border-radius:8px; padding:10px; background:var(--bg); color:var(--ink);">
                                                <span style="font-size:11px; color:var(--muted); margin-top:4px; display:block;">*Biaya restock akan dipotong langsung dari saldo ZeroPay Owner dengan harga RNG per pcs.</span>
                                            </div>
                                            <div style="display:flex; gap:8px;">
                                                <button class="button" style="padding:10px 20px; border-radius:8px; background:var(--accent); border-color:var(--accent);" type="submit">Restock</button>
                                                <button class="button secondary" style="padding:10px 20px; border-radius:8px;" type="button" onclick="document.getElementById('restock-row-{{ $part->id }}').style.display='none'">Batal</button>
                                            </div>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="muted" style="text-align:center; padding:20px;">Belum ada data sparepart.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    {{-- KOLOM KANAN: FORM INPUT --}}
    <div class="stack" style="gap:24px;">
        
        {{-- Form Tambah Jenis Servis --}}
        <section class="panel catalog-section catalog-form" data-catalog-panel="service" style="background:var(--panel); border:1px solid var(--line); box-shadow:var(--shadow-sm);">
            <div class="catalog-form-head">
                <div class="catalog-form-title">
                    <div style="width:36px; height:36px; border-radius:10px; background:var(--primary-soft); color:var(--primary-dark); display:grid; place-items:center;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg>
                    </div>
                    <h2 style="margin:0; font-size:18px;">Tambah Jenis Layanan</h2>
                </div>
                <button type="button" class="catalog-form-close" data-catalog-form-close aria-label="Tutup form tambah layanan">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"></path><path d="m6 6 12 12"></path></svg>
                </button>
            </div>
            
            <form method="POST" action="{{ route('catalog.service-types.store') }}" class="stack">
                @csrf
                <div class="field">
                    <label style="font-weight:600; font-size:13px;">Nama Layanan</label>
                    <input name="name" required style="border-radius:8px; padding:12px; border:1px solid var(--line); background:var(--bg);">
                </div>
                <div class="grid grid-2" style="gap:16px;">
                    <div class="field">
                        <label style="font-weight:600; font-size:13px;">Estimasi Waktu (Menit)</label>
                        <input name="estimated_minutes" type="number" min="10" value="60" required style="border-radius:8px; padding:12px; border:1px solid var(--line); background:var(--bg);">
                    </div>
                    <div class="field">
                        <label style="font-weight:600; font-size:13px;">Harga Dasar (Rp)</label>
                        <input name="base_price" type="number" min="0" required style="border-radius:8px; padding:12px; border:1px solid var(--line); background:var(--bg);">
                    </div>
                    <div class="field">
                        <label style="font-weight:600; font-size:13px;">Persentase Mekanik (%)</label>
                        <input name="mechanic_salary" type="number" min="0" max="100" value="35" required style="border-radius:8px; padding:12px; border:1px solid var(--line); background:var(--bg);">
                    </div>
                    <div class="field">
                        <label style="font-weight:600; font-size:13px;">Persentase Kasir (%)</label>
                        <input name="cashier_salary" type="number" min="0" max="100" value="15" required style="border-radius:8px; padding:12px; border:1px solid var(--line); background:var(--bg);">
                    </div>
                </div>
                <button class="button" type="submit" style="border-radius:8px; padding:14px; font-weight:700; margin-top:8px;">Simpan Layanan Baru</button>
            </form>
        </section>

        {{-- Form Tambah Promo --}}
        <section class="panel catalog-section catalog-form is-active" data-catalog-panel="promo" style="background:var(--panel); border:1px solid var(--line); box-shadow:var(--shadow-sm);">
            <div class="catalog-form-head">
                <div class="catalog-form-title">
                    <div style="width:36px; height:36px; border-radius:10px; background:var(--accent-soft); color:var(--accent); display:grid; place-items:center;">
                        %
                    </div>
                    <h2 style="margin:0; font-size:18px;">Tambah Promo Welcome</h2>
                </div>
                <button type="button" class="catalog-form-close" data-catalog-form-close aria-label="Tutup form tambah promo">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"></path><path d="m6 6 12 12"></path></svg>
                </button>
            </div>

            <form method="POST" action="{{ route('catalog.promotions.store') }}" class="stack">
                @csrf
                <div class="field">
                    <label style="font-weight:600; font-size:13px;">Judul Promo</label>
                    <input name="title" required placeholder="Contoh: Diskon Servis Ringan" style="border-radius:8px; padding:12px; border:1px solid var(--line); background:var(--bg);">
                </div>
                <div class="grid grid-2" style="gap:16px;">
                    <div class="field">
                        <label style="font-weight:600; font-size:13px;">Layanan</label>
                        <select name="service_type_id" style="border-radius:8px; padding:12px; border:1px solid var(--line); background:var(--bg);">
                            <option value="">Semua layanan</option>
                            @foreach($serviceTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label style="font-weight:600; font-size:13px;">Diskon (%)</label>
                        <input name="discount_percent" type="number" min="1" max="100" value="10" required style="border-radius:8px; padding:12px; border:1px solid var(--line); background:var(--bg);">
                    </div>
                    <div class="field">
                        <label style="font-weight:600; font-size:13px;">Mulai</label>
                        <input name="starts_at" type="datetime-local" style="border-radius:8px; padding:12px; border:1px solid var(--line); background:var(--bg);">
                    </div>
                    <div class="field">
                        <label style="font-weight:600; font-size:13px;">Selesai</label>
                        <input name="ends_at" type="datetime-local" style="border-radius:8px; padding:12px; border:1px solid var(--line); background:var(--bg);">
                    </div>
                </div>
                <div class="field">
                    <label style="font-weight:600; font-size:13px;">Deskripsi Promo</label>
                    <textarea name="description" rows="3" placeholder="Tulis pesan promo untuk pelanggan." style="border-radius:8px; padding:12px; border:1px solid var(--line); background:var(--bg);"></textarea>
                </div>
                <label style="display:flex; align-items:center; gap:8px; font-weight:700; color:var(--muted);">
                    <input type="checkbox" name="is_active" value="1" checked style="width:auto;"> Aktifkan promo
                </label>
                <button class="button" type="submit" style="border-radius:8px; padding:14px; font-weight:700; margin-top:8px;">Simpan Promo</button>
            </form>
        </section>

        {{-- Form Tambah Sparepart Baru --}}
        <section class="panel catalog-section catalog-form" data-catalog-panel="sparepart" style="background:var(--panel); border:1px solid var(--line); box-shadow:var(--shadow-sm);">
            <div class="catalog-form-head">
                <div class="catalog-form-title">
                    <div style="width:36px; height:36px; border-radius:10px; background:var(--accent-soft); color:var(--accent); display:grid; place-items:center;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                    </div>
                    <h2 style="margin:0; font-size:18px;">Registrasi Sparepart Baru</h2>
                </div>
                <button type="button" class="catalog-form-close" data-catalog-form-close aria-label="Tutup form tambah sparepart">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"></path><path d="m6 6 12 12"></path></svg>
                </button>
            </div>

            <form method="POST" action="{{ route('catalog.spare-parts.store') }}" class="stack" onsubmit="return handleQrisSubmit(event, this);">
                @csrf
                <div class="field">
                    <label style="font-weight:600; font-size:13px;">Nama Sparepart</label>
                    <div style="position:relative; display:flex; align-items:center;">
                        <input name="name" id="sparepartNameInput" required style="border-radius:8px; padding:12px; padding-right:40px; border:1px solid var(--line); background:var(--bg); width:100%;">
                        <button type="button" id="rngSearchBtn" style="position:absolute; right:8px; background:none; border:none; cursor:pointer; color:var(--muted); padding:4px;" onclick="generateRngPrice()">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        </button>
                    </div>
                </div>
                <div class="field">
                    <label style="font-weight:600; font-size:13px;">Kode Produk (SKU)</label>
                    <input name="sku" required style="border-radius:8px; padding:12px; border:1px solid var(--line); background:var(--bg); text-transform:uppercase;">
                </div>
                <div class="grid grid-3" style="gap:16px;">
                    <div class="field">
                        <label style="font-weight:600; font-size:13px;">Modal Stok Awal</label>
                        <input name="stock" type="number" min="0" required style="border-radius:8px; padding:12px; border:1px solid var(--line); background:var(--bg);">
                    </div>
                    <div class="field">
                        <label style="font-weight:600; font-size:13px;">Harga Satuan</label>
                        <input name="rng_price" id="sparepartRngInput" type="number" readonly required placeholder="" style="border-radius:8px; padding:12px; border:1px solid var(--line); background:color-mix(in srgb, var(--line) 30%, var(--bg)); color:var(--muted); cursor:not-allowed;">
                    </div>
                    <div class="field">
                        <label style="font-weight:600; font-size:13px;">Harga Jual (Rp)</label>
                        <input name="price" type="number" min="0" required style="border-radius:8px; padding:12px; border:1px solid var(--line); background:var(--bg);">
                    </div>
                </div>
                <button class="button" type="submit" style="border-radius:8px; padding:14px; font-weight:700; margin-top:8px; background:var(--accent); border-color:var(--accent);">Beli & Daftarkan</button>
            </form>
        </section>

    </div>
</div>
@endsection

@section('scripts')
<script>
    document.querySelectorAll('[data-catalog-tab]').forEach((button) => {
        button.addEventListener('click', () => {
            const target = button.dataset.catalogTab;

            document.querySelectorAll('[data-catalog-tab]').forEach((tab) => {
                tab.classList.toggle('is-active', tab === button);
            });

            document.querySelectorAll('[data-catalog-panel]').forEach((panel) => {
                panel.classList.toggle('is-active', panel.dataset.catalogPanel === target);
            });

            syncCatalogFormTriggers();
        });
    });

    function syncCatalogFormTriggers() {
        const openPanels = new Set(
            Array.from(document.querySelectorAll('.catalog-form.is-open'))
                .map((formPanel) => formPanel.dataset.catalogPanel)
        );

        document.querySelectorAll('[data-catalog-form-open]').forEach((button) => {
            button.disabled = openPanels.has(button.dataset.catalogFormOpen);
        });
    }

    document.querySelectorAll('[data-catalog-form-open]').forEach((button) => {
        button.addEventListener('click', () => {
            const target = button.dataset.catalogFormOpen;

            document.querySelectorAll('.catalog-form').forEach((formPanel) => {
                formPanel.classList.toggle('is-open', formPanel.dataset.catalogPanel === target);
            });

            syncCatalogFormTriggers();
        });
    });

    document.querySelectorAll('[data-catalog-form-close]').forEach((button) => {
        button.addEventListener('click', () => {
            button.closest('.catalog-form')?.classList.remove('is-open');
            syncCatalogFormTriggers();
        });
    });

    syncCatalogFormTriggers();

    function showRestockForm(id, name) {
        const row = document.getElementById('restock-row-' + id);
        row.style.display = row.style.display === 'none' ? 'table-row' : 'none';
    }

    function handleQrisSubmit(e, form) {
        e.preventDefault();
        if (window.runZeroPaySimulation) {
            window.runZeroPaySimulation({
                form,
                title: 'Memproses Pembelian',
                subtitle: 'Saldo ZeroPay owner dan pembaruan stok sedang diproses.',
                status: 'Mengonfirmasi saldo dan stok...',
                duration: 1400
            });
        } else {
            form.submit();
        }
    }

    setInterval(() => {
        if (document.hidden) return;
        document.querySelectorAll('.dynamic-rng-price').forEach(el => {
            const rng = Math.floor(Math.random() * (50000 - 10000 + 1)) + 10000;
            el.innerText = 'Rp ' + rng.toLocaleString('id-ID');
        });
    }, 300000); // 5 menit

    let rngCooldown = 0;
    async function generateRngPrice() {
        if (rngCooldown > 0) return;
        
        const input = document.getElementById('sparepartNameInput').value;
        if (input.length >= 5) {
            const rng = Math.floor(Math.random() * (50000 - 10000 + 1)) + 10000;
            document.getElementById('sparepartRngInput').value = rng;
            
            rngCooldown = 15;
            const btn = document.getElementById('rngSearchBtn');
            btn.disabled = true;
            btn.style.opacity = '0.3';
            btn.style.cursor = 'not-allowed';
            
            const timer = setInterval(() => {
                rngCooldown--;
                if (rngCooldown <= 0) {
                    clearInterval(timer);
                    btn.disabled = false;
                    btn.style.opacity = '1';
                    btn.style.cursor = 'pointer';
                }
            }, 1000);
        } else {
            await window.showAlert('Perhatian', 'Minimal 5 karakter untuk mencari nama sparepart & generate harga RNG!');
        }
    }
</script>
@endsection
