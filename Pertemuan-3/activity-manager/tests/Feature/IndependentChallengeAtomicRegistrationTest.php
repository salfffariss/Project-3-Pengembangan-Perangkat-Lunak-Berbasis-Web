<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Registration;
use App\Services\RegistrationService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndependentChallengeAtomicRegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * IC-01 & IC-05: Pendaftaran valid berhasil membuat Registration dan menambah registered_count
     */
    public function test_valid_registration_creates_record_and_increments_count(): void
    {
        $activity = Activity::where('status', 'published')
            ->where('start_at', '>=', now()->toDateString())
            ->first();

        $this->assertNotNull($activity);
        $initialCount = $activity->registered_count;

        $response = $this->post(route('activities.register', $activity), [
            'participant_name' => 'Budi Santoso',
            'email' => 'budi.santoso@example.com',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('registrations', [
            'activity_id' => $activity->id,
            'participant_name' => 'Budi Santoso',
            'email' => 'budi.santoso@example.com',
        ]);

        $this->assertEquals($initialCount + 1, $activity->fresh()->registered_count);
    }

    /**
     * IC-01: Kegiatan berstatus draft menolak pendaftaran
     */
    public function test_draft_activity_rejects_registration(): void
    {
        $draft = Activity::where('status', 'draft')->first();
        $this->assertNotNull($draft);

        $response = $this->post(route('activities.register', $draft), [
            'participant_name' => 'Peserta Draft',
            'email' => 'draft.user@example.com',
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('registrations', [
            'email' => 'draft.user@example.com',
        ]);
        $this->assertEquals(0, $draft->fresh()->registered_count);
    }

    /**
     * IC-02: Pendaftaran ditolak jika start_at sudah lewat
     */
    public function test_registration_rejected_if_start_at_has_passed(): void
    {
        $pastActivity = Activity::where('status', 'published')->first();
        $pastActivity->update([
            'start_at' => now()->subDays(5)->toDateString(),
            'end_at' => now()->subDays(4)->toDateString(),
        ]);

        $response = $this->post(route('activities.register', $pastActivity), [
            'participant_name' => 'Peserta Terlambat',
            'email' => 'terlambat@example.com',
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('registrations', [
            'email' => 'terlambat@example.com',
        ]);
    }

    /**
     * IC-03: Email duplikat pada kegiatan yang sama ditolak
     */
    public function test_duplicate_email_on_same_activity_is_rejected(): void
    {
        $activity = Activity::where('status', 'published')
            ->where('start_at', '>=', now()->toDateString())
            ->first();

        // Pendaftaran pertama berhasil
        $this->post(route('activities.register', $activity), [
            'participant_name' => 'Peserta Pertama',
            'email' => 'sama@example.com',
        ]);

        // Pendaftaran kedua dengan email yang sama harus ditolak
        $response = $this->post(route('activities.register', $activity), [
            'participant_name' => 'Peserta Kedua',
            'email' => 'sama@example.com',
        ]);

        $response->assertSessionHas('error');
        // Hanya boleh ada 1 record di database
        $this->assertEquals(1, Registration::where('activity_id', $activity->id)->where('email', 'sama@example.com')->count());
    }

    /**
     * IC-04: Kapasitas penuh menolak pendaftaran
     */
    public function test_full_capacity_activity_rejects_registration(): void
    {
        $activity = Activity::where('status', 'published')
            ->where('start_at', '>=', now()->toDateString())
            ->first();

        // Atur kapasitas 1 dan pendaftar 1 (penuh)
        $activity->update([
            'capacity' => 1,
            'registered_count' => 1,
        ]);

        $response = $this->post(route('activities.register', $activity), [
            'participant_name' => 'Peserta Lebih',
            'email' => 'lebih@example.com',
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('registrations', [
            'email' => 'lebih@example.com',
        ]);
        $this->assertEquals(1, $activity->fresh()->registered_count);
    }

    /**
     * IC-06: Eksperimen kegagalan terkontrol membuktikan rollback transaction
     */
    public function test_controlled_failure_triggers_transaction_rollback(): void
    {
        $activity = Activity::where('status', 'published')
            ->where('start_at', '>=', now()->toDateString())
            ->first();

        $initialCount = $activity->registered_count;
        $service = app(RegistrationService::class);

        $caughtException = false;
        try {
            // Jalankan pendaftaran dengan callback hook yang sengaja melempar exception sebelum commit
            $service->register($activity, [
                'participant_name' => 'Simulasi Rollback',
                'email' => 'rollback@example.com',
            ], function () {
                throw new Exception('Simulasi kegagalan sistem di tengah transaction!');
            });
        } catch (Exception $e) {
            $caughtException = true;
        }

        $this->assertTrue($caughtException);

        // Buktikan Atomicity: Tidak ada data parsial!
        // Record registration TIDAK boleh tersimpan di database
        $this->assertDatabaseMissing('registrations', [
            'email' => 'rollback@example.com',
        ]);

        // Counter registered_count tidak boleh bertambah
        $this->assertEquals($initialCount, $activity->fresh()->registered_count);
    }
}
