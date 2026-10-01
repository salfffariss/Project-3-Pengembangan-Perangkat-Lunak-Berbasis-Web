<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Task1RelationalIntegrityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * Skenario 1: Create dengan kategori valid -> Data tersimpan dan relasi benar
     */
    public function test_scenario_1_create_with_valid_category_succeeds(): void
    {
        $category = Category::first();

        $response = $this->post(route('activities.store'), [
            'code' => 'ACT-NEW-01',
            'title' => 'Kegiatan Baru Valid',
            'description' => 'Deskripsi kegiatan valid.',
            'activity_date' => '2026-10-15',
            'category_id' => $category->id,
            'status' => 'Planned',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('activities', [
            'code' => 'ACT-NEW-01',
            'category_id' => $category->id,
        ]);

        $activity = Activity::where('code', 'ACT-NEW-01')->first();
        $this->assertEquals($category->name, $activity->category->name);
    }

    /**
     * Skenario 2: Create dengan category_id tidak valid -> Request ditolak
     */
    public function test_scenario_2_create_with_invalid_category_id_is_rejected(): void
    {
        $response = $this->post(route('activities.store'), [
            'code' => 'ACT-INVALID-CAT',
            'title' => 'Kegiatan Kategori Invalid',
            'activity_date' => '2026-10-15',
            'category_id' => 99999, // ID tidak ada
            'status' => 'Planned',
        ]);

        $response->assertSessionHasErrors('category_id');
        $this->assertDatabaseMissing('activities', [
            'code' => 'ACT-INVALID-CAT',
        ]);
    }

    /**
     * Skenario 3: Create kode duplikat -> Request ditolak
     */
    public function test_scenario_3_create_duplicate_code_is_rejected(): void
    {
        $existing = Activity::first();
        $category = Category::first();

        $response = $this->post(route('activities.store'), [
            'code' => $existing->code, // Menggunakan kode yang sudah ada
            'title' => 'Kegiatan Kode Duplikat',
            'activity_date' => '2026-10-15',
            'category_id' => $category->id,
            'status' => 'Planned',
        ]);

        $response->assertSessionHasErrors('code');
    }

    /**
     * Skenario 4: Update tanpa mengganti kode -> Tidak dianggap duplikat terhadap diri sendiri
     */
    public function test_scenario_4_update_without_changing_code_succeeds(): void
    {
        $activity = Activity::first();

        $response = $this->put(route('activities.update', $activity), [
            'code' => $activity->code, // Kode tetap sama
            'title' => 'Judul Baru Setelah Update',
            'activity_date' => $activity->activity_date->format('Y-m-d'),
            'category_id' => $activity->category_id,
            'status' => $activity->status,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('activities.show', $activity));
        $this->assertDatabaseHas('activities', [
            'id' => $activity->id,
            'title' => 'Judul Baru Setelah Update',
        ]);
    }

    /**
     * Skenario 5: Hapus kategori yang masih dipakai -> Ditolak; data kegiatan tetap utuh
     */
    public function test_scenario_5_delete_category_with_activities_is_rejected(): void
    {
        $activity = Activity::first();
        $category = $activity->category;

        $response = $this->delete(route('categories.destroy', $category));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('categories', ['id' => $category->id]);
        $this->assertDatabaseHas('activities', ['id' => $activity->id]);
    }

    /**
     * Skenario 6: Hapus kategori kosong -> Berhasil
     */
    public function test_scenario_6_delete_empty_category_succeeds(): void
    {
        $emptyCategory = Category::where('slug', 'kategori-kosong')->first();
        $this->assertNotNull($emptyCategory);

        $response = $this->delete(route('categories.destroy', $emptyCategory));

        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('categories', ['id' => $emptyCategory->id]);
    }
}
