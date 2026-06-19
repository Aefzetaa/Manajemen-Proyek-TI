<?php

namespace Tests\Feature;

use App\Models\ServiceType;
use App\Models\SparePart;
use App\Models\User;
use App\Services\ZoruAiResponseFormatter;
use App\Services\ZoruAiService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ZoruAiTrainingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }

    public function test_greetings_are_clean_and_branded_for_owner_and_user(): void
    {
        $service = app(ZoruAiService::class);
        $owner = User::factory()->make(['role' => 'owner', 'username' => 'nami']);
        $customer = User::factory()->make(['role' => 'customer', 'username' => 'budi']);

        $ownerGreeting = $service->greeting($owner);
        $customerGreeting = $service->greeting($customer);

        $this->assertStringContainsString('ZoruAi', $ownerGreeting);
        $this->assertStringContainsString('Milky Garage', $ownerGreeting);
        $this->assertStringContainsString('ZoruAi', $customerGreeting);
        $this->assertStringContainsString('Milky Garage', $customerGreeting);
        $this->assertDoesNotMatchRegularExpression('/\s{2,}/', $ownerGreeting);
        $this->assertDoesNotMatchRegularExpression('/\s{2,}/', $customerGreeting);
        $this->assertStringNotContainsString('â', $ownerGreeting . $customerGreeting);
        $this->assertStringNotContainsString('AI Asisten', $ownerGreeting . $customerGreeting);
    }

    public function test_general_dialog_dataset_answers_smalltalk(): void
    {
        $service = app(ZoruAiService::class);
        $user = User::factory()->make(['role' => 'customer', 'username' => 'budi']);

        $halo = $service->reply('Halo!', $user);
        $kabar = $service->reply('Apa kabar?', $user);

        $this->assertStringContainsString('Senang', $halo);
        $this->assertStringContainsString('Kabar baik', $kabar);
        $this->assertStringNotContainsString('â', $halo . $kabar);
    }

    public function test_identity_and_capability_prompts_stay_zoruai_branded(): void
    {
        $service = app(ZoruAiService::class);
        $user = User::factory()->make(['role' => 'customer', 'username' => 'budi']);

        $identity = $service->reply('Siapa kamu?', $user);
        $capability = $service->reply('Kamu bisa apa aja sih?', $user);
        $formalCapability = $service->reply('Apa yang bisa Anda lakukan?', $user);

        $this->assertStringContainsString('Saya ZoruAi', $identity);
        $this->assertStringContainsString('Milky Garage', $identity);
        $this->assertStringContainsString('Saya ZoruAi', $capability);
        $this->assertStringContainsString('Saya ZoruAi', $formalCapability);
        $this->assertStringNotContainsString('AI Asisten', $identity . $capability . $formalCapability);
        $this->assertStringNotContainsString('Paragraf yang baik', $formalCapability);
    }

    public function test_pricing_questions_use_master_data(): void
    {
        ServiceType::create([
            'name' => 'Servis Ringan',
            'estimated_minutes' => 60,
            'base_price' => 75000,
            'mechanic_salary' => 25000,
            'cashier_salary' => 5000,
        ]);
        SparePart::create(['name' => 'Busi Motor', 'sku' => 'BUSI-TEST', 'stock' => 4, 'price' => 28000]);

        $service = app(ZoruAiService::class);
        $user = User::factory()->make(['role' => 'customer', 'username' => 'budi']);

        $reply = $service->reply('Berapa harga servis ringan?', $user);

        $this->assertStringContainsString('master data Milky Garage', $reply);
        $this->assertStringContainsString('Servis Ringan: Rp 75.000', $reply);
    }

    public function test_joined_typo_price_question_uses_master_data(): void
    {
        ServiceType::create([
            'name' => 'Ganti Oli',
            'estimated_minutes' => 30,
            'base_price' => 45000,
            'mechanic_salary' => 15000,
            'cashier_salary' => 5000,
        ]);

        $service = app(ZoruAiService::class);
        $user = User::factory()->make(['role' => 'customer', 'username' => 'budi']);

        $reply = $service->reply('brape gantioli?', $user);

        $this->assertStringContainsString('Saya memahami maksud pertanyaan Anda', $reply);
        $this->assertStringContainsString('Ganti Oli: Rp 45.000', $reply);
        $this->assertStringContainsString('master data Milky Garage', $reply);
    }

    public function test_operational_error_answers_use_context_and_database(): void
    {
        SparePart::create(['name' => 'Busi Motor', 'sku' => 'BUSI-EMPTY', 'stock' => 0, 'price' => 28000]);
        SparePart::create(['name' => 'Filter Udara', 'sku' => 'FILTER-LOW', 'stock' => 3, 'price' => 45000]);
        ServiceType::create([
            'name' => 'Tune Up Mesin',
            'estimated_minutes' => 120,
            'base_price' => 150000,
            'mechanic_salary' => 40000,
            'cashier_salary' => 5000,
        ]);

        $service = app(ZoruAiService::class);
        $owner = User::factory()->make(['role' => 'owner', 'username' => 'nami', 'balance' => 20000]);

        $saldo = $service->reply('Saldo ZeroPay saya kurang, tagihan 150 ribu saldo 20 ribu', $owner);
        $stok = $service->reply('sparepart apa yang stok habis?', $owner);
        $durasi = $service->reply('berapa lama tune up mesin?', $owner);

        $this->assertStringContainsString('Rp 130.000', $saldo);
        $this->assertStringContainsString('Busi Motor', $stok);
        $this->assertStringContainsString('Filter Udara (3)', $stok);
        $this->assertStringContainsString('Tune Up Mesin: sekitar 120 menit', $durasi);
    }

    public function test_milky_garage_system_training_answers_project_questions(): void
    {
        $service = app(ZoruAiService::class);
        $user = User::factory()->make(['role' => 'customer', 'username' => 'budi']);

        $reply = $service->reply('fitur sistem aplikasi bengkel motor', $user);

        $this->assertStringContainsString('Milky Garage', $reply);
        $this->assertStringContainsString('ZeroPay', $reply);
        $this->assertStringContainsString('registrasi multi-role', $reply);
        $this->assertStringContainsString('Master Data', $reply);
    }

    public function test_zeropay_natural_question_gets_detailed_system_answer(): void
    {
        $service = app(ZoruAiService::class);
        $user = User::factory()->make(['role' => 'customer', 'username' => 'budi']);

        $reply = $service->reply('zeropay itu apa?', $user);

        $this->assertStringContainsString('Rincian ZeroPay', $reply);
        $this->assertStringContainsString('dompet digital Milky Garage', $reply);
        $this->assertStringContainsString('fitur fiksi', $reply);
    }

    public function test_prompt_understanding_dataset_is_integrated(): void
    {
        $service = app(ZoruAiService::class);
        $user = User::factory()->make(['role' => 'customer', 'username' => 'budi']);

        $reply = $service->reply('Apa itu fotosintesis?', $user);

        $this->assertStringContainsString('Fotosintesis adalah proses biologis', $reply);
        $this->assertStringContainsString('glukosa', $reply);
    }

    public function test_offline_composer_handles_common_ai_behaviors(): void
    {
        $service = app(ZoruAiService::class);
        $user = User::factory()->make(['role' => 'customer', 'username' => 'budi']);

        $calculation = $service->reply('Berapa 15% dari 240?', $user);
        $current = $service->reply('Siapa yang menang pemilu kemarin?', $user);
        $fallback = $service->reply('tolong jelaskan turbo kapal luar angkasa rahasia', $user);

        $this->assertStringContainsString('15% dari 240', $calculation);
        $this->assertStringContainsString('36', $calculation);
        $this->assertStringContainsString('berjalan offline', $current);
        $this->assertStringContainsString('tidak memiliki akses berita atau data real-time', $current);
        $this->assertStringContainsString('panduan bantuan offline', $fallback);
        $this->assertStringContainsString('Booking servis', $fallback);
    }

    public function test_unrelated_prompt_does_not_force_wrong_training_answer(): void
    {
        $service = app(ZoruAiService::class);
        $user = User::factory()->make(['role' => 'customer', 'username' => 'budi']);

        $reply = $service->reply('jelaskan turbo kapal luar angkasa rahasia dengan detail', $user);

        $this->assertStringContainsString('Saya belum menemukan kecocokan data pelatihan', $reply);
        $this->assertStringNotContainsString('Blockchain', $reply);
        $this->assertStringNotContainsString('Fotosintesis', $reply);
        $this->assertStringNotContainsString('service rutin motor', $reply);
    }

    public function test_owner_zoruai_restock_requires_card_payment(): void
    {
        $owner = User::factory()->create([
            'role' => 'owner',
            'username' => 'nami',
            'balance' => 200000,
        ]);
        $part = SparePart::create([
            'name' => 'Busi Motor',
            'sku' => 'BUSI-AI',
            'stock' => 4,
            'price' => 28000,
        ]);

        $response = $this->actingAs($owner)->postJson(route('reports.analytics.zoru'), [
            'prompt' => 'restock Busi Motor 3 unit',
        ]);

        $response->assertOk()
            ->assertJsonPath('action.type', 'restock_sparepart')
            ->assertJsonPath('action.name', 'Busi Motor')
            ->assertJsonPath('action.qty', 3);

        $part->refresh();
        $owner->refresh();
        $this->assertSame(4, $part->stock);
        $this->assertSame(200000, (int) $owner->balance);

        $action = $response->json('action');
        $pay = $this->actingAs($owner)->postJson(route('reports.analytics.zoru.restock'), [
            'token' => $action['token'],
        ]);

        $pay->assertOk()->assertJsonPath('success', true);

        $part->refresh();
        $owner->refresh();
        $this->assertSame(7, $part->stock);
        $this->assertSame(200000 - $action['total'], (int) $owner->balance);
        $this->assertDatabaseHas('account_activities', [
            'user_id' => $owner->id,
            'amount' => -$action['total'],
        ]);
    }

    public function test_zoruai_dialog_history_is_not_persisted(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'username' => 'budi',
        ]);

        $this->actingAs($user)->postJson(route('reports.analytics.zoru'), [
            'prompt' => 'Halo ZoruAi',
        ])->assertOk();

        $this->assertDatabaseCount('zoruai_chats', 0);
    }

    public function test_owner_zoruai_restock_payment_rejects_insufficient_balance(): void
    {
        $owner = User::factory()->create([
            'role' => 'owner',
            'username' => 'nami',
            'balance' => 1,
        ]);
        $part = SparePart::create([
            'name' => 'Filter Udara',
            'sku' => 'FILTER-AI',
            'stock' => 2,
            'price' => 45000,
        ]);

        $response = $this->actingAs($owner)->postJson(route('reports.analytics.zoru'), [
            'prompt' => 'tambah stok Filter Udara 2 pcs',
        ]);

        $action = $response->json('action');

        $pay = $this->actingAs($owner)->postJson(route('reports.analytics.zoru.restock'), [
            'token' => $action['token'],
        ]);

        $pay->assertStatus(422)->assertJsonPath('success', false);
        $part->refresh();
        $owner->refresh();
        $this->assertSame(2, $part->stock);
        $this->assertSame(1, (int) $owner->balance);
    }

    public function test_owner_can_cancel_zoruai_restock_card(): void
    {
        $owner = User::factory()->create([
            'role' => 'owner',
            'username' => 'nami',
            'balance' => 200000,
        ]);
        $part = SparePart::create([
            'name' => 'Kampas Rem Depan',
            'sku' => 'REM-AI',
            'stock' => 5,
            'price' => 85000,
        ]);

        $response = $this->actingAs($owner)->postJson(route('reports.analytics.zoru'), [
            'prompt' => 'restock Kampas Rem Depan 2 unit',
        ]);

        $token = $response->json('action.token');

        $cancel = $this->actingAs($owner)->deleteJson(route('reports.analytics.zoru.restock.cancel'), [
            'token' => $token,
        ]);

        $cancel->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Transaksi restock dibatalkan.');

        $pay = $this->actingAs($owner)->postJson(route('reports.analytics.zoru.restock'), [
            'token' => $token,
        ]);

        $pay->assertStatus(422)->assertJsonPath('success', false);
        $part->refresh();
        $owner->refresh();
        $this->assertSame(5, $part->stock);
        $this->assertSame(200000, (int) $owner->balance);
    }

    public function test_formatter_turns_training_sequence_words_into_steps(): void
    {
        $formatter = new ZoruAiResponseFormatter();

        $reply = $formatter->format('Pertama, cek oli. Kedua, cek busi. Ketiga, cek aki.');

        $this->assertStringContainsString('1. Cek oli', $reply);
        $this->assertStringContainsString('2. Cek busi', $reply);
        $this->assertStringContainsString('3. Cek aki', $reply);
    }

    public function test_sensitive_system_answers_are_owner_only(): void
    {
        $service = app(ZoruAiService::class);
        $owner = User::factory()->make(['role' => 'owner', 'username' => 'nami']);
        $customer = User::factory()->make(['role' => 'customer', 'username' => 'budi']);

        $ownerReply = $service->reply('berapa PIN demo penarikan zeropay?', $owner);
        $customerReply = $service->reply('berapa PIN demo penarikan zeropay?', $customer);

        $this->assertStringContainsString('0104', $ownerReply);
        $this->assertStringContainsString('0000', $ownerReply);
        $this->assertStringContainsString('1111', $ownerReply);
        $this->assertStringContainsString('informasi internal', $customerReply);
        $this->assertStringNotContainsString('0104', $customerReply);
        $this->assertStringNotContainsString('0000', $customerReply);
        $this->assertStringNotContainsString('1111', $customerReply);
    }

    public function test_formatter_repairs_common_mojibake(): void
    {
        $formatter = new ZoruAiResponseFormatter();

        $reply = $formatter->format("Rincian â€” fitur\nâ€¢ Booking â†’ bayar\nStok â‰¤5");

        $this->assertStringContainsString('Rincian - fitur', $reply);
        $this->assertStringContainsString('- Booking -> bayar', $reply);
        $this->assertStringContainsString('Stok <=5', $reply);
        $this->assertStringNotContainsString('â', $reply);
    }

    public function test_zoruai_handles_extreme_indonesian_slang_and_suffixes(): void
    {
        $service = app(ZoruAiService::class);
        $user = User::factory()->make(['role' => 'customer', 'username' => 'budi']);

        // Test slang pronouns, possessive suffixes, and abbreviations in ZeroPay
        $reply1 = $service->reply('saldonya zeropay ku brapa?', $user);
        $reply2 = $service->reply('cara bokingnya gmn sih?', $user);

        $this->assertStringContainsString('Saldo Anda saat ini', $reply1);
        $this->assertStringContainsString('Booking Service', $reply2);
    }

    public function test_zoruai_understands_hidden_gem_and_developer_questions(): void
    {
        $service = app(ZoruAiService::class);
        $user = User::factory()->make(['role' => 'customer', 'username' => 'budi']);

        $hiddenGemReply = $service->reply('apa itu tombol hidden gem?', $user);
        $developerReply = $service->reply('siapa pembuat sistem zoruai?', $user);

        $this->assertStringContainsString('Tombol Hidden Gem adalah fitur tersembunyi', $hiddenGemReply);
        $this->assertStringContainsString('didevelop oleh Aefzetaa', $developerReply);
    }

    public function test_zoruai_understands_identity_questions(): void
    {
        $service = app(ZoruAiService::class);
        $user = User::factory()->make(['role' => 'customer', 'username' => 'budi']);

        $reply1 = $service->reply('siapa saya?', $user);
        $reply2 = $service->reply('kamu tahu siapa saya?', $user);

        $this->assertStringContainsString('Anda adalah Kak Budi', $reply1);
        $this->assertStringContainsString('aktif sebagai Pelanggan', $reply1);
        $this->assertStringContainsString('Anda adalah Kak Budi', $reply2);
    }

    public function test_zoruai_understands_casual_greetings_kabar_aktivitas_pujian(): void
    {
        $service = app(ZoruAiService::class);
        $user = User::factory()->make(['role' => 'customer', 'username' => 'budi']);

        $apaKabar = $service->reply('Gimana kabarnya?', $user);
        $sedangApa = $service->reply('Lagi ngapain zoru?', $user);
        $pujian = $service->reply('kamu keren banget sih', $user);

        $this->assertStringContainsString('Kabar saya luar biasa baik', $apaKabar);
        $this->assertStringContainsString('Sama sekali tidak lelah', $sedangApa);
        $this->assertStringContainsString('terima kasih banyak atas pujiannya', \Illuminate\Support\Str::lower($pujian));
    }

    public function test_owner_salary_management(): void
    {
        ServiceType::create([
            'name' => 'Ganti Oli',
            'estimated_minutes' => 30,
            'base_price' => 45000,
            'mechanic_salary' => 15000,
            'cashier_salary' => 5000,
        ]);

        $owner = User::factory()->create(['role' => 'owner', 'username' => 'nami']);

        $service = app(ZoruAiService::class);
        
        // Test interactive form prompt
        $formPromptReply = $service->reply('Gaji karyawan', $owner);
        $this->assertStringContainsString('Silakan isi formulir di bawah ini untuk mengatur gaji mekanik dan kasir', $formPromptReply);

        // Test actual salary change command
        $changeSalaryReply = $service->reply('Ubah gaji Ganti Oli jadi Mekanik Rp 20.000 dan Kasir Rp 7.000', $owner);
        $this->assertStringContainsString('Gaji karyawan untuk Ganti Oli berhasil diperbarui', $changeSalaryReply);
        $this->assertStringContainsString('Mekanik menjadi Rp 20.000', $changeSalaryReply);
        $this->assertStringContainsString('Kasir menjadi Rp 7.000', $changeSalaryReply);

        // Verify database persistence
        $updatedService = ServiceType::where('name', 'Ganti Oli')->first();
        $this->assertSame(20000, $updatedService->mechanic_salary);
        $this->assertSame(7000, $updatedService->cashier_salary);
    }

    public function test_owner_add_and_delete_service(): void
    {
        $owner = User::factory()->create(['role' => 'owner', 'username' => 'nami']);

        // 1. Test prompt triggers for form action via API
        $responseForm = $this->actingAs($owner)->postJson(route('reports.analytics.zoru'), [
            'prompt' => 'tambah servis',
        ]);
        $responseForm->assertOk()->assertJsonPath('action.type', 'add_service_form');

        $responseDeleteForm = $this->actingAs($owner)->postJson(route('reports.analytics.zoru'), [
            'prompt' => 'hapus servis',
        ]);
        $responseDeleteForm->assertOk()->assertJsonPath('action.type', 'delete_service_form');

        // 2. Test execution of actual command to add a service
        $responseAdd = $this->actingAs($owner)->postJson(route('reports.analytics.zoru'), [
            'prompt' => 'Tambah servis Servis Super dengan durasi 45 menit, harga Rp 100.000, gaji mekanik Rp 30.000, gaji kasir Rp 10.000',
        ]);
        $responseAdd->assertOk();
        $this->assertStringContainsString('Servis Super', $responseAdd->json('reply'));
        $this->assertStringContainsString('Rp 100.000', $responseAdd->json('reply'));

        // Verify it was created in the database
        $this->assertDatabaseHas('service_types', [
            'name' => 'Servis Super',
            'estimated_minutes' => 45,
            'base_price' => 100000,
            'mechanic_salary' => 30000,
            'cashier_salary' => 10000,
        ]);

        // Try adding the same service again to verify error response
        $responseAddDuplicate = $this->actingAs($owner)->postJson(route('reports.analytics.zoru'), [
            'prompt' => 'Tambah servis Servis Super dengan durasi 45 menit, harga Rp 100.000, gaji mekanik Rp 30.000, gaji kasir Rp 10.000',
        ]);
        $responseAddDuplicate->assertOk();
        $this->assertStringContainsString('sudah terdaftar', $responseAddDuplicate->json('reply'));

        // 3. Test execution of actual command to delete service
        $responseDelete = $this->actingAs($owner)->postJson(route('reports.analytics.zoru'), [
            'prompt' => 'Hapus servis Servis Super',
        ]);
        $responseDelete->assertOk();
        $this->assertStringContainsString('berhasil dihapus', $responseDelete->json('reply'));

        // Verify it is gone from the database
        $this->assertDatabaseMissing('service_types', [
            'name' => 'Servis Super',
        ]);

        // Try deleting a non-existent service
        $responseDeleteNonExistent = $this->actingAs($owner)->postJson(route('reports.analytics.zoru'), [
            'prompt' => 'Hapus servis Servis Super',
        ]);
        $responseDeleteNonExistent->assertOk();
        $this->assertStringContainsString('tidak ditemukan', $responseDeleteNonExistent->json('reply'));
    }

    public function test_owner_add_and_delete_sparepart(): void
    {
        $owner = User::factory()->create([
            'role' => 'owner',
            'username' => 'nami',
            'balance' => 500000,
        ]);

        // 1. Test prompt triggers for form action via API
        $responseForm = $this->actingAs($owner)->postJson(route('reports.analytics.zoru'), [
            'prompt' => 'tambah sparepart',
        ]);
        $responseForm->assertOk()->assertJsonPath('action.type', 'add_spare_part_form');

        $responseDeleteForm = $this->actingAs($owner)->postJson(route('reports.analytics.zoru'), [
            'prompt' => 'hapus spare part',
        ]);
        $responseDeleteForm->assertOk()->assertJsonPath('action.type', 'delete_spare_part_form');

        // 2. Test adding spare part with insufficient balance (stock > 0, cost exceeds balance)
        $poorOwner = User::factory()->create([
            'role' => 'owner',
            'username' => 'luffy',
            'balance' => 10000,
        ]);
        $responseAddPoor = $this->actingAs($poorOwner)->postJson(route('reports.analytics.zoru'), [
            'prompt' => 'Tambah sparepart Ban Tubeless SKU BAN-TBL stok 5 unit, harga retail Rp 150.000, harga modal Rp 100.000',
        ]);
        $responseAddPoor->assertOk();
        $this->assertStringContainsString('tidak mencukupi', $responseAddPoor->json('reply'));
        $this->assertDatabaseMissing('spare_parts', ['sku' => 'BAN-TBL']);

        // 3. Test successfully adding spare part with sufficient balance
        $responseAdd = $this->actingAs($owner)->postJson(route('reports.analytics.zoru'), [
            'prompt' => 'Tambah sparepart Ban Tubeless SKU BAN-TBL stok 2 unit, harga retail Rp 150.000, harga modal Rp 100.000',
        ]);
        $responseAdd->assertOk();
        $this->assertStringContainsString('Ban Tubeless', $responseAdd->json('reply'));
        $this->assertStringContainsString('berhasil ditambahkan', $responseAdd->json('reply'));

        // Verify DB mutations
        $this->assertDatabaseHas('spare_parts', [
            'name' => 'Ban Tubeless',
            'sku' => 'BAN-TBL',
            'stock' => 2,
            'price' => 150000,
        ]);

        // Verify balance decrement (2 * 100,000 = 200,000)
        $owner->refresh();
        $this->assertEquals(300000, $owner->balance);

        // Verify AccountActivity record
        $this->assertDatabaseHas('account_activities', [
            'user_id' => $owner->id,
            'type' => 'money_out',
            'amount' => -200000,
        ]);

        // Try adding duplicate SKU
        $responseAddDup = $this->actingAs($owner)->postJson(route('reports.analytics.zoru'), [
            'prompt' => 'Tambah sparepart Ban Tubeless Baru SKU BAN-TBL stok 0 unit, harga retail Rp 150.000, harga modal Rp 100.000',
        ]);
        $responseAddDup->assertOk();
        $this->assertStringContainsString('sudah terdaftar', $responseAddDup->json('reply'));

        // 4. Test deleting spare part
        $responseDelete = $this->actingAs($owner)->postJson(route('reports.analytics.zoru'), [
            'prompt' => 'Hapus sparepart Ban Tubeless',
        ]);
        $responseDelete->assertOk();
        $this->assertStringContainsString('berhasil dihapus', $responseDelete->json('reply'));

        // Verify sparepart is gone
        $this->assertDatabaseMissing('spare_parts', ['name' => 'Ban Tubeless']);

        // Try deleting non-existent spare part
        $responseDeleteNonExistent = $this->actingAs($owner)->postJson(route('reports.analytics.zoru'), [
            'prompt' => 'Hapus sparepart Ban Tubeless',
        ]);
        $responseDeleteNonExistent->assertOk();
        $this->assertStringContainsString('tidak ditemukan', $responseDeleteNonExistent->json('reply'));
    }

    public function test_owner_topup_and_withdraw(): void
    {
        $owner = User::factory()->create([
            'role' => 'owner',
            'username' => 'nami',
            'balance' => 100000,
            'withdraw_pin' => '0104', // cast will hash it automatically
        ]);

        // 1. Test prompt triggers for form actions via API
        $responseTopupForm = $this->actingAs($owner)->postJson(route('reports.analytics.zoru'), [
            'prompt' => 'topup',
        ]);
        $responseTopupForm->assertOk()->assertJsonPath('action.type', 'topup_form');

        $responseWithdrawForm = $this->actingAs($owner)->postJson(route('reports.analytics.zoru'), [
            'prompt' => 'withdraw',
        ]);
        $responseWithdrawForm->assertOk()->assertJsonPath('action.type', 'withdraw_form');

        // 2. Test successful Top-up
        $responseTopup = $this->actingAs($owner)->postJson(route('reports.analytics.zoru'), [
            'prompt' => 'Top-up ZeroPay Rp 150.000 via gopay',
        ]);
        $responseTopup->assertOk();
        $this->assertStringContainsString('berhasil dilakukan via GOPAY', $responseTopup->json('reply'));

        // Verify balance
        $owner->refresh();
        $this->assertEquals(250000, $owner->balance);

        // Verify AccountActivity record
        $this->assertDatabaseHas('account_activities', [
            'user_id' => $owner->id,
            'type' => 'money_in',
            'amount' => 150000,
        ]);

        // 3. Test Withdraw with wrong PIN
        $responseWithdrawWrongPin = $this->actingAs($owner)->postJson(route('reports.analytics.zoru'), [
            'prompt' => 'Tarik dana ZeroPay Rp 50.000 ke DANA dengan PIN 9999',
        ]);
        $responseWithdrawWrongPin->assertOk();
        $this->assertStringContainsString('PIN withdraw yang Anda masukkan salah', $responseWithdrawWrongPin->json('reply'));

        // 4. Test Withdraw with insufficient balance
        $responseWithdrawTooMuch = $this->actingAs($owner)->postJson(route('reports.analytics.zoru'), [
            'prompt' => 'Tarik dana ZeroPay Rp 900.000 ke DANA dengan PIN 0104',
        ]);
        $responseWithdrawTooMuch->assertOk();
        $this->assertStringContainsString('Saldo Anda tidak mencukupi', $responseWithdrawTooMuch->json('reply'));

        // 5. Test successful Withdraw
        $responseWithdraw = $this->actingAs($owner)->postJson(route('reports.analytics.zoru'), [
            'prompt' => 'Tarik dana ZeroPay Rp 50.000 ke DANA dengan PIN 0104',
        ]);
        $responseWithdraw->assertOk();
        $this->assertStringContainsString('berhasil ditarik', $responseWithdraw->json('reply'));

        // Verify balance
        $owner->refresh();
        $this->assertEquals(200000, $owner->balance);

        // Verify AccountActivity record
        $this->assertDatabaseHas('account_activities', [
            'user_id' => $owner->id,
            'type' => 'money_out',
            'amount' => -50000,
        ]);
    }
}