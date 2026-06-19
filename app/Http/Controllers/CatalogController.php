<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use App\Models\ServiceType;
use App\Models\SparePart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class CatalogController extends Controller
{
    public function index()
    {
        return view('catalog.index', [
            'serviceTypes' => ServiceType::orderBy('name')->get(),
            'spareParts' => SparePart::orderBy('name')->get(),
            'promotions' => Schema::hasTable('promotions')
                ? Promotion::with('serviceType')->latest()->get()
                : collect(),
        ]);
    }

    public function storeServiceType(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120', 'unique:service_types,name'],
            'estimated_minutes' => ['required', 'integer', 'min:10'],
            'base_price' => ['required', 'integer', 'min:0'],
            'mechanic_salary' => ['required', 'integer', 'min:0', 'max:100'],
            'cashier_salary' => ['required', 'integer', 'min:0', 'max:100'],
        ]);

        $employeeShare = $validated['mechanic_salary'] + $validated['cashier_salary'];
        if ($employeeShare < 50) {
            return back()->withErrors(['mechanic_salary' => 'Total persentase mekanik dan kasir minimal 50%.'])->withInput();
        }
        if ($employeeShare > 100) {
            return back()->withErrors(['mechanic_salary' => 'Total persentase mekanik dan kasir maksimal 100%.'])->withInput();
        }

        ServiceType::create($validated);

        return back()->with('success', 'Jenis servis ditambahkan.');
    }

    public function updateServiceType(Request $request, ServiceType $serviceType)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120', 'unique:service_types,name,' . $serviceType->id],
            'estimated_minutes' => ['required', 'integer', 'min:10'],
            'base_price' => ['required', 'integer', 'min:0'],
            'mechanic_salary' => ['required', 'integer', 'min:0', 'max:100'],
            'cashier_salary' => ['required', 'integer', 'min:0', 'max:100'],
        ]);

        $employeeShare = $validated['mechanic_salary'] + $validated['cashier_salary'];
        if ($employeeShare < 50) {
            return back()->withErrors(['mechanic_salary' => 'Total persentase mekanik dan kasir minimal 50%.'])->withInput();
        }
        if ($employeeShare > 100) {
            return back()->withErrors(['mechanic_salary' => 'Total persentase mekanik dan kasir maksimal 100%.'])->withInput();
        }

        $serviceType->update($validated);

        return back()->with('success', 'Jenis servis diperbarui.');
    }

    public function storePromotion(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:140'],
            'description' => ['nullable', 'string', 'max:500'],
            'service_type_id' => ['nullable', 'exists:service_types,id'],
            'discount_percent' => ['required', 'integer', 'min:1', 'max:100'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['starts_at'] = $validated['starts_at'] ?? now();
        Promotion::create($validated);

        return back()->with('success', 'Promo berhasil ditambahkan.');
    }

    public function updatePromotion(Request $request, Promotion $promotion)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:140'],
            'description' => ['nullable', 'string', 'max:500'],
            'service_type_id' => ['nullable', 'exists:service_types,id'],
            'discount_percent' => ['required', 'integer', 'min:1', 'max:100'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['starts_at'] = $validated['starts_at'] ?? $promotion->starts_at ?? now();
        $promotion->update($validated);

        return back()->with('success', 'Promo berhasil diperbarui.');
    }

    public function destroyPromotion(Promotion $promotion)
    {
        $promotion->delete();

        return back()->with('success', 'Promo berhasil dihapus.');
    }

    public function storeSparePart(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'sku' => ['required', 'string', 'max:50', 'unique:spare_parts,sku'],
            'stock' => ['required', 'integer', 'min:0'],
            'price' => ['required', 'integer', 'min:0'],
            'rng_price' => ['required', 'integer', 'min:0'],
        ]);

        $qty = (int) $validated['stock'];
        if ($qty > 0) {
            $rngPricePerUnit = (int) $validated['rng_price']; // RNG purchase price from frontend
            $totalCost = $qty * $rngPricePerUnit;
            
            $owner = $request->user();
            if ($owner->balance < $totalCost) {
                return back()->withErrors(['catalog' => 'Saldo ZeroPay tidak mencukupi untuk modal awal sebesar Rp ' . number_format($totalCost, 0, ',', '.')]);
            }

            $owner->decrement('balance', $totalCost);
            \App\Models\AccountActivity::create([
                'user_id' => $owner->id,
                'type' => 'money_out',
                'description' => 'Beli modal awal ' . $validated['name'] . ' (' . $qty . ' pcs @ Rp ' . number_format($rngPricePerUnit, 0, ',', '.') . ')',
                'amount' => -$totalCost,
            ]);
        }

        unset($validated['rng_price']);
        SparePart::create($validated);

        $msg = 'Sparepart ditambahkan.';
        if (isset($totalCost)) {
            $msg .= ' Total modal Rp ' . number_format($totalCost, 0, ',', '.') . ' dipotong dari ZeroPay.';
        }

        return back()->with('success', $msg);
    }
    
    public function updateSparePart(Request $request, SparePart $sparePart)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'sku' => ['required', 'string', 'max:50', 'unique:spare_parts,sku,' . $sparePart->id],
            'stock' => ['required', 'integer', 'min:0'],
            'price' => ['required', 'integer', 'min:0'],
        ]);

        $sparePart->update($validated);

        return back()->with('success', 'Sparepart diperbarui.');
    }

    public function restockSparePart(Request $request, SparePart $sparePart)
    {
        $validated = $request->validate([
            'added_stock' => ['required', 'integer', 'min:1'],
        ]);

        $qty = (int) $validated['added_stock'];
        $rngPricePerUnit = rand(10000, 50000); // RNG pricing for restock cost
        $totalCost = $qty * $rngPricePerUnit;
        
        $owner = $request->user();
        if ($owner->balance < $totalCost) {
            return back()->withErrors(['catalog' => 'Saldo ZeroPay tidak mencukupi untuk restock sebesar Rp ' . number_format($totalCost, 0, ',', '.')]);
        }

        $owner->decrement('balance', $totalCost);
        \App\Models\AccountActivity::create([
            'user_id' => $owner->id,
            'type' => 'money_out',
            'description' => 'Restock ' . $sparePart->name . ' (' . $qty . ' pcs @ Rp ' . number_format($rngPricePerUnit, 0, ',', '.') . ')',
            'amount' => -$totalCost,
        ]);

        $sparePart->increment('stock', $qty);

        return back()->with('success', "Stok {$sparePart->name} ditambah {$qty} unit. Total biaya Rp " . number_format($totalCost, 0, ',', '.') . " dipotong dari ZeroPay.");
    }

    public function destroyServiceType(ServiceType $serviceType)
    {
        try {
            $serviceType->delete();
            return back()->with('success', 'Jenis servis berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->withErrors(['catalog' => 'Gagal menghapus jenis servis, mungkin masih digunakan pada transaksi.']);
        }
    }

    public function destroySparePart(SparePart $sparePart)
    {
        try {
            $sparePart->delete();
            return back()->with('success', 'Sparepart berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->withErrors(['catalog' => 'Gagal menghapus sparepart, mungkin masih digunakan pada transaksi.']);
        }
    }
}
