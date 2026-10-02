<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Task2BusinessRuleAndQueryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * Skenario 1: Draft lengkap -> Published : Berhasil dan status berubah
     */
    public function test_scenario_1_draft_complete_can_be_published(): void
    {
        $draft = Activity::where('status', 'draft')->whereNotNull('location')->first();
        $this->assertNotNull($draft);

        $response = $this->post(route('activities.publish', $draft));

        $response->assertSessionHas('success');
        $this->assertEquals('published', $draft->fresh()->status);
    }

    /**
     * Skenario 2: Draft tidak lengkap -> Published : Ditolak dengan pesan yang jelas (BR-05)
     */
    public function test_scenario_2_draft_incomplete_cannot_be_published(): void
    {
        // Buat activity draft tanpa location
        $category = Category::first();
        $incomplete = Activity::create([
            'code' => 'ACT-INCOMPLETE',
            'title' => 'Draft Belum Lengkap',
            'category_id' => $category->id,
            'start_at' => '2026-10-10',
            'end_at' => '2026-10-11',
            'location' => null, // Lokasi sengaja dikosongkan
            'capacity' => 50,
            'status' => 'draft',
        ]);

        $response = $this->post(route('activities.publish', $incomplete));

        $response->assertSessionHas('error');
        $this->assertEquals('draft', $incomplete->fresh()->status);
    }

    /**
     * Skenario 3: Published -> Completed : Berhasil (BR-06)
     */
    public function test_scenario_3_published_to_completed_succeeds(): void
    {
        $published = Activity::where('status', 'published')->first();
        $this->assertNotNull($published);

        $response = $this->post(route('activities.complete', $published));

        $response->assertSessionHas('success');
        $this->assertEquals('completed', $published->fresh()->status);
    }

    /**
     * Skenario 4: Completed -> Draft : Ditolak (BR-07)
     */
    public function test_scenario_4_completed_to_draft_is_rejected(): void
    {
        $completed = Activity::where('status', 'completed')->first();
        $this->assertNotNull($completed);

        // Percobaan publish atau update kembali dari completed harus ditolak
        $response = $this->post(route('activities.publish', $completed));
        $response->assertSessionHas('error');
        $this->assertEquals('completed', $completed->fresh()->status);
    }

    /**
     * Skenario 5: Search + Category + Status : Hasil memenuhi seluruh parameter
     */
    public function test_scenario_5_search_category_status_filter_combination(): void
    {
        $catWorkshop = Category::where('slug', 'workshop')->first();

        $response = $this->get(route('activities.index', [
            'search' => 'Git',
            'category_id' => $catWorkshop->id,
            'status' => 'published',
        ]));

        $response->assertStatus(200);
        $response->assertSee('ACT-002');
        $response->assertSee('Workshop Git dan GitHub Collaboration');
    }

    /**
     * Skenario 6: Pagination halaman 2 : Parameter pencarian/filter tetap ada (withQueryString)
     */
    public function test_scenario_6_pagination_preserves_query_string(): void
    {
        // Total data di seeder ada 16, per page 10, sehingga halaman 2 pasti ada
        $response = $this->get(route('activities.index', [
            'status' => 'published',
            'sort' => 'oldest',
            'page' => 2,
        ]));

        $response->assertStatus(200);
        // Link pagination harus mengandung parameter query
        $response->assertSee('status=published');
        $response->assertSee('sort=oldest');
    }

    /**
     * Uji Form Edit Umum tidak mengizinkan perubahan status bebas
     */
    public function test_edit_form_cannot_arbitrarily_change_status(): void
    {
        $draft = Activity::where('status', 'draft')->first();

        // Kirim request update mencoba menyusupkan status='completed'
        $this->put(route('activities.update', $draft), [
            'code' => $draft->code,
            'title' => $draft->title,
            'category_id' => $draft->category_id,
            'start_at' => $draft->start_at->format('Y-m-d'),
            'end_at' => $draft->end_at->format('Y-m-d'),
            'capacity' => $draft->capacity,
            'location' => 'Lokasi Terupdate',
            'status' => 'completed', // Upaya manipulasi status di form edit umum
        ]);

        // Status harus tetap draft karena update form mengabaikan status
        $this->assertEquals('draft', $draft->fresh()->status);
        $this->assertEquals('Lokasi Terupdate', $draft->fresh()->location);
    }
}
