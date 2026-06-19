<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\ServiceOrder;
use App\Models\ServiceType;
use App\Models\SparePart;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkshopFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_register_and_create_booking(): void
    {
        $serviceType = ServiceType::create([
            'name' => 'Servis Ringan',
            'estimated_minutes' => 60,
            'base_price' => 75000,
            'mechanic_salary' => 25000,
            'cashier_salary' => 5000,
        ]);

        $customer = User::factory()->create([
            'role' => 'customer',
            'username' => 'cust01',
            'phone' => '081234567890',
        ]);

        $this->actingAs($customer)->post(route('bookings.store'), [
            'service_type_ids' => [$serviceType->id],
            'booking_date' => now()->addDay()->toDateString(),
            'booking_time' => '09:00',
            'plate_number' => 'H 9999 ZZ',
            'brand' => 'Honda',
            'model' => 'Beat',
            'year' => 2024,
            'service_description' => 'Servis rutin bulanan',
        ])->assertRedirect(route('bookings.index'));

        $booking = Booking::first();
        $this->assertNotNull($booking);
        $this->assertSame('scheduled', $booking->status);
        $this->assertTrue($booking->serviceTypes->contains('id', $serviceType->id));
    }

    public function test_service_payment_flow_matches_current_workshop_process(): void
    {
        $customer = User::factory()->create(['role' => 'customer', 'phone' => '081111111111']);
        $mechanic = User::factory()->create(['role' => 'mechanic', 'phone' => '082222222222']);
        $cashier = User::factory()->create(['role' => 'cashier', 'phone' => '083333333333']);
        $serviceType = ServiceType::create([
            'name' => 'Tune Up Mesin',
            'estimated_minutes' => 120,
            'base_price' => 150000,
            'mechanic_salary' => 50000,
            'cashier_salary' => 10000,
        ]);
        $sparePart = SparePart::create(['name' => 'Busi Motor', 'sku' => 'BUSI-TEST', 'stock' => 4, 'price' => 28000]);
        $vehicle = Vehicle::create([
            'user_id' => $customer->id,
            'plate_number' => 'H 1234 AB',
            'brand' => 'Yamaha',
            'model' => 'Mio',
            'year' => 2021,
        ]);
        $booking = Booking::create([
            'user_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'mechanic_id' => $mechanic->id,
            'booking_date' => now()->toDateString(),
            'booking_time' => '10:00',
            'status' => 'in_progress',
            'service_description' => 'Mesin brebet',
        ]);
        $booking->serviceTypes()->attach($serviceType->id);

        $this->actingAs($mechanic)->post(route('service-orders.store', $booking), [
            'serviced_at' => now()->toDateString(),
            'diagnosis' => 'Busi melemah dan perlu diganti',
            'parts' => [$sparePart->id => 1],
        ])->assertRedirect(route('service-orders.index'));

        $order = ServiceOrder::firstOrFail();
        $this->assertSame('waiting_cashier', $order->status);
        $this->assertDatabaseHas('payments', ['service_order_id' => $order->id, 'status' => 'pending']);
        $this->assertDatabaseHas('spare_parts', ['id' => $sparePart->id, 'stock' => 3]);

        $this->actingAs($cashier)
            ->patch(route('service-orders.send-to-customer', $order))
            ->assertSessionHas('success');

        $order->refresh();
        $this->assertSame('waiting_approval', $order->status);

        $this->actingAs($customer)
            ->patch(route('service-orders.approve', $order))
            ->assertSessionHas('success');

        $order->refresh();
        $this->assertSame('approved', $order->status);

        $payment = Payment::firstOrFail();

        $this->actingAs($cashier)->patch(route('payments.paid', $payment), [
            'method' => 'Tunai',
            'cash_received' => $payment->amount,
        ])->assertSessionHas('show_nota_cashier');

        $payment->refresh();
        $this->assertSame('paid', $payment->status);
        $this->assertSame('finished', $order->refresh()->status);
        $this->assertSame(1, Invoice::count());
    }

    public function test_cashier_cannot_mark_paid_when_cash_received_is_less_than_bill(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $cashier = User::factory()->create(['role' => 'cashier']);
        $serviceType = ServiceType::create([
            'name' => 'Ganti Oli',
            'estimated_minutes' => 30,
            'base_price' => 100000,
            'mechanic_salary' => 20000,
            'cashier_salary' => 5000,
        ]);
        $booking = Booking::create([
            'user_id' => $customer->id,
            'booking_date' => now()->toDateString(),
            'booking_time' => '11:00',
            'status' => 'completed',
        ]);
        $booking->serviceTypes()->attach($serviceType->id);

        $order = ServiceOrder::create([
            'booking_id' => $booking->id,
            'customer_id' => $customer->id,
            'serviced_at' => now()->toDateString(),
            'diagnosis' => 'Ganti oli',
            'labor_cost' => 100000,
            'parts_total' => 0,
            'total_cost' => 100000,
            'status' => 'approved',
        ]);

        $payment = Payment::create([
            'service_order_id' => $order->id,
            'reference_no' => 'PAY-TEST-1',
            'amount' => 100000,
            'status' => 'pending',
            'method' => 'Tunai',
        ]);

        $this->actingAs($cashier)
            ->from(route('payments.index'))
            ->patch(route('payments.paid', $payment), [
                'method' => 'Tunai',
                'cash_received' => 50000,
            ])
            ->assertSessionHasErrors('cash_received');

        $this->assertSame('pending', $payment->fresh()->status);
    }

    public function test_owner_can_open_analytics_report(): void
    {
        $owner = User::factory()->create(['role' => 'owner', 'phone' => '080000000000']);

        $this->actingAs($owner)
            ->get(route('reports.analytics'))
            ->assertOk();
    }
}
