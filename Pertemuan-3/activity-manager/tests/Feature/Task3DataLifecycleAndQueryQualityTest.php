<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class Task3DataLifecycleAndQueryQualityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * AC-10: Soft Delete menyimpan timestamp deleted_at dan menyembunyikan data dari index
     */
    public function test_soft_delete_preserves_row_and_hides_from_index(): void
    {
        $activity = Activity::first();
        $this->assertNotNull($activity);

        $response = $this->delete(route('activities.destroy', $activity));
        $response->assertRedirect(route('activities.index'));
        $response->assertSessionHas('success');

        // Pastikan baris masih ada di database
        $this->assertDatabaseHas('activities', [
            'id' => $activity->id,
        ]);

        // Pastikan deleted_at terisi (soft deleted)
        $this->assertNotNull($activity->fresh()->deleted_at);
        $this->assertTrue($activity->fresh()->trashed());

        // Pastikan tidak ditemukan oleh query normal Eloquent
        $this->assertNull(Activity::find($activity->id));
        $this->assertNotNull(Activity::withTrashed()->find($activity->id));

        // Pastikan link detail kegiatan tidak muncul lagi di index reguler
        $indexResponse = $this->get(route('activities.index', ['search' => $activity->code]));
        $indexResponse->assertDontSee(route('activities.show', $activity));
        $indexResponse->assertSee('Tidak ada kegiatan yang sesuai dengan filter.');
    }

    /**
     * AC-11: Data terhapus muncul di halaman Trash dan dapat di-restore kembali
     */
    public function test_trash_displays_soft_deleted_activity_and_restore_works(): void
    {
        $activity = Activity::first();
        $activity->delete();

        // 1. Tampil di halaman Trash
        $trashResponse = $this->get(route('activities.trash'));
        $trashResponse->assertStatus(200);
        $trashResponse->assertSee($activity->code);
        $trashResponse->assertSee($activity->title);

        // 2. Eksekusi Restore
        $restoreResponse = $this->post(route('activities.restore', $activity->id));
        $restoreResponse->assertRedirect(route('activities.trash'));
        $restoreResponse->assertSessionHas('success');

        // 3. Pastikan status trashed hilang dan deleted_at menjadi null
        $this->assertFalse($activity->fresh()->trashed());
        $this->assertNull($activity->fresh()->deleted_at);

        // 4. Data kembali muncul di index reguler
        $indexResponse = $this->get(route('activities.index', ['search' => $activity->code]));
        $indexResponse->assertSee($activity->title);
    }

    /**
     * AC-12: Pembuktian N+1 Elimination melalui Eager Loading
     */
    public function test_eager_loading_eliminates_n_plus_one_query(): void
    {
        // 1. Uji Lazy Loading (tanpa with): query dieksekusi berulang di dalam loop
        DB::flushQueryLog();
        DB::enableQueryLog();

        $lazyActivities = Activity::take(10)->get();
        foreach ($lazyActivities as $item) {
            // Mengakses relasi satu per satu tanpa eager loading
            $categoryName = $item->category?->name;
        }

        $lazyQueries = DB::getQueryLog();
        // 1 query untuk mengambil activities + N query untuk masing-masing category (total 11 query)
        $this->assertGreaterThanOrEqual(10, count($lazyQueries));

        // 2. Uji Eager Loading (dengan with('category')): hanya 2 query total
        DB::flushQueryLog();

        $eagerActivities = Activity::with('category')->take(10)->get();
        foreach ($eagerActivities as $item) {
            $categoryName = $item->category?->name;
        }

        $eagerQueries = DB::getQueryLog();
        // Hanya 2 query: 1 untuk activities dan 1 untuk categories WHERE id IN (...)
        $this->assertCount(2, $eagerQueries);
        DB::disableQueryLog();
    }
}
