<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\ServiceType;
use App\Models\Vehicle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        $query = Booking::with(['user', 'vehicle', 'serviceTypes', 'serviceOrder'])
            ->orderByDesc('booking_date')
            ->orderBy('booking_time');

        if ($request->user()->isRole('customer')) {
            $query->where('user_id', $request->user()->id)
                  ->whereIn('status', ['scheduled', 'accepted', 'in_progress']);
        } elseif ($request->user()->isRole('mechanic')) {
            $query->where(function($q) use ($request) {
                $q->where('status', 'scheduled')
                  ->orWhere(function($q2) use ($request) {
                      $q2->whereIn('status', ['accepted', 'in_progress'])
                         ->where('mechanic_id', $request->user()->id);
                  });
            });
        } else {
            if ($request->filled('date')) {
                $query->whereDate('booking_date', $request->date('date'));
            }

            if ($request->filled('status')) {
                $query->where('status', $request->input('status'));
            }
        }

        return view('bookings.index', [
            'bookings' => $query->paginate(10)->withQueryString(),
            'statuses' => Booking::STATUSES,
        ]);
    }

    public function create(Request $request): View
    {
        $takenSlots = Booking::query()
            ->whereBetween('booking_date', [today(), today()->addDays(30)])
            ->where('status', '!=', 'canceled')
            ->get(['booking_date', 'booking_time'])
            ->groupBy(fn(Booking $booking) => $booking->booking_date->toDateString())
            ->map(fn($bookings) => $bookings
                ->map(fn(Booking $booking) => substr((string) $booking->booking_time, 0, 5))
                ->values());

        return view('bookings.create', [
            'serviceTypes' => ServiceType::with('activePromotions')->orderBy('name')->get(),
            'vehicles' => $request->user()->vehicles()->orderBy('plate_number')->get(),
            'slots' => $this->slots(),
            'takenSlots' => $takenSlots,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'service_type_ids' => ['required', 'array', 'min:1'],
            'service_type_ids.*' => ['exists:service_types,id'],
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
            'booking_time' => ['required', 'date_format:H:i'],
            'vehicle_id' => ['nullable', 'exists:vehicles,id'],
            'plate_number' => ['required_without:vehicle_id', 'nullable', 'string', 'max:20'],
            'brand' => ['required_without:vehicle_id', 'nullable', 'string', 'max:80'],
            'model' => ['required_without:vehicle_id', 'nullable', 'string', 'max:80'],
            'year' => ['nullable', 'integer', 'min:1980', 'max:' . now()->year],
            'service_description' => ['nullable', 'string', 'max:1000'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $slotTaken = Booking::whereDate('booking_date', $validated['booking_date'])
            ->where('booking_time', $validated['booking_time'])
            ->where('status', '!=', 'canceled')
            ->exists();

        if ($slotTaken) {
            return back()
                ->withErrors(['booking_time' => 'Slot waktu tersebut sudah terisi. Silakan pilih jam lain.'])
                ->withInput();
        }

        $vehicleId = $validated['vehicle_id'] ?? null;

        if ($vehicleId) {
            $vehicleId = Vehicle::where('user_id', $request->user()->id)
                ->whereKey($vehicleId)
                ->value('id');

            if (! $vehicleId) {
                return back()
                    ->withErrors(['vehicle_id' => 'Kendaraan tidak valid untuk akun Anda.'])
                    ->withInput();
            }
        } else {
            $vehicle = Vehicle::create([
                'user_id' => $request->user()->id,
                'plate_number' => strtoupper($validated['plate_number']),
                'brand' => $validated['brand'],
                'model' => $validated['model'],
                'year' => $validated['year'] ?? null,
            ]);
            $vehicleId = $vehicle->id;
        }

        $booking = Booking::create([
            'user_id' => $request->user()->id,
            'vehicle_id' => $vehicleId,
            'booking_date' => $validated['booking_date'],
            'booking_time' => $validated['booking_time'],
            'service_description' => $validated['service_description'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'status' => 'scheduled',
        ]);

        $booking->serviceTypes()->attach($validated['service_type_ids']);

        return redirect()->route('bookings.index')->with('success', 'Booking servis berhasil dibuat.');
    }

    public function updateStatus(Request $request, Booking $booking): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:' . implode(',', array_keys(Booking::STATUSES))],
        ]);

        $booking->update(['status' => $validated['status']]);

        return back()->with('success', 'Status booking diperbarui.');
    }

    public function cancel(Request $request, Booking $booking): RedirectResponse
    {
        abort_unless($request->user()->id === $booking->user_id, 403);
        abort_unless($booking->status === 'scheduled', 403, 'Booking tidak dapat dibatalkan saat ini.');

        $booking->update(['status' => 'canceled']);

        return back()->with('success', 'Booking Anda berhasil dibatalkan.');
    }

    public function edit(Request $request, Booking $booking): View
    {
        abort_unless($request->user()->id === $booking->user_id, 403);
        // Can edit if within 1 hour of creation AND mechanic_id is null (not accepted yet)
        $diffInMinutes = $booking->created_at->diffInMinutes(now());
        abort_unless($diffInMinutes <= 60 && $booking->mechanic_id === null, 403, 'Booking tidak dapat diedit karena sudah lewat 1 jam atau sudah diambil mekanik.');

        $takenSlots = Booking::query()
            ->whereBetween('booking_date', [today(), today()->addDays(30)])
            ->where('status', '!=', 'canceled')
            ->where('id', '!=', $booking->id) // Exclude current booking
            ->get(['booking_date', 'booking_time'])
            ->groupBy(fn(Booking $b) => $b->booking_date->toDateString())
            ->map(fn($bookings) => $bookings
                ->map(fn(Booking $b) => substr((string) $b->booking_time, 0, 5))
                ->values());

        return view('bookings.edit', [
            'booking' => $booking,
            'serviceTypes' => ServiceType::with('activePromotions')->orderBy('name')->get(),
            'vehicles' => $request->user()->vehicles()->orderBy('plate_number')->get(),
            'slots' => $this->slots(),
            'takenSlots' => $takenSlots,
        ]);
    }

    public function update(Request $request, Booking $booking): RedirectResponse
    {
        abort_unless($request->user()->id === $booking->user_id, 403);
        $diffInMinutes = $booking->created_at->diffInMinutes(now());
        abort_unless($diffInMinutes <= 60 && $booking->mechanic_id === null, 403, 'Booking tidak dapat diedit karena sudah lewat 1 jam atau sudah diambil mekanik.');

        $validated = $request->validate([
            'service_type_ids' => ['required', 'array', 'min:1'],
            'service_type_ids.*' => ['exists:service_types,id'],
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
            'booking_time' => ['required', 'date_format:H:i'],
            'vehicle_id' => ['nullable', 'exists:vehicles,id'],
            'plate_number' => ['required_without:vehicle_id', 'nullable', 'string', 'max:20'],
            'brand' => ['required_without:vehicle_id', 'nullable', 'string', 'max:80'],
            'model' => ['required_without:vehicle_id', 'nullable', 'string', 'max:80'],
            'year' => ['nullable', 'integer', 'min:1980', 'max:' . now()->year],
            'service_description' => ['nullable', 'string', 'max:1000'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $slotTaken = Booking::whereDate('booking_date', $validated['booking_date'])
            ->where('booking_time', $validated['booking_time'])
            ->where('status', '!=', 'canceled')
            ->where('id', '!=', $booking->id)
            ->exists();

        if ($slotTaken) {
            return back()
                ->withErrors(['booking_time' => 'Slot waktu tersebut sudah terisi. Silakan pilih jam lain.'])
                ->withInput();
        }

        $vehicleId = $validated['vehicle_id'] ?? null;

        if ($vehicleId) {
            $vehicleId = Vehicle::where('user_id', $request->user()->id)
                ->whereKey($vehicleId)
                ->value('id');

            if (! $vehicleId) {
                return back()
                    ->withErrors(['vehicle_id' => 'Kendaraan tidak valid untuk akun Anda.'])
                    ->withInput();
            }
        } else {
            $vehicle = Vehicle::create([
                'user_id' => $request->user()->id,
                'plate_number' => strtoupper($validated['plate_number']),
                'brand' => $validated['brand'],
                'model' => $validated['model'],
                'year' => $validated['year'] ?? null,
            ]);
            $vehicleId = $vehicle->id;
        }

        $booking->update([
            'vehicle_id' => $vehicleId,
            'booking_date' => $validated['booking_date'],
            'booking_time' => $validated['booking_time'],
            'service_description' => $validated['service_description'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        $booking->serviceTypes()->sync($validated['service_type_ids']);

        return redirect()->route('bookings.index')->with('success', 'Booking servis berhasil diubah.');
    }

    public function accept(Request $request, Booking $booking): RedirectResponse
    {
        abort_unless($request->user()->isRole('mechanic'), 403);
        abort_unless($booking->status === 'scheduled', 403, 'Booking tidak dapat diambil.');
        abort_if($booking->mechanic_id !== null, 403, 'Booking sudah diambil mekanik lain.');

        $booking->update([
            'status' => 'accepted',
            'mechanic_id' => $request->user()->id,
        ]);

        return back()->with('success', 'Pekerjaan berhasil diambil. Silakan mulai kerjakan jika sudah siap.');
    }

    public function startWork(Request $request, Booking $booking): RedirectResponse
    {
        abort_unless($request->user()->isRole('mechanic'), 403);
        abort_unless($booking->mechanic_id === $request->user()->id, 403, 'Ini bukan pekerjaan Anda.');
        abort_unless($booking->status === 'accepted', 403, 'Booking tidak dapat dikerjakan.');

        $booking->update(['status' => 'in_progress']);

        return back()->with('success', 'Status pekerjaan diubah menjadi Sedang Dikerjakan.');
    }

    private function slots(): array
    {
        return ['08:00', '09:00', '10:00', '11:00', '13:00', '14:00', '15:00', '16:00'];
    }
}
