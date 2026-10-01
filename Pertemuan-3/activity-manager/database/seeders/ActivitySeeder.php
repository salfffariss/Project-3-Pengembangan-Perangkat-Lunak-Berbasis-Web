<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $catAkademik = Category::where('slug', 'akademik')->first();
        $catWorkshop = Category::where('slug', 'workshop')->first();
        $catPraktikum = Category::where('slug', 'praktikum')->first();
        $catOrganisasi = Category::where('slug', 'organisasi')->first();

        $kegiatan = [
            [
                'code' => 'ACT-001',
                'title' => 'Dasar Laravel 13',
                'description' => 'Mempelajari dasar-dasar request lifecycle dan routing.',
                'activity_date' => '2026-09-22',
                'category_id' => $catAkademik?->id,
                'status' => 'Planned',
            ],
            [
                'code' => 'ACT-002',
                'title' => 'Workshop Git dan GitHub',
                'description' => 'Latihan kolaborasi branch dan pull request.',
                'activity_date' => '2026-09-25',
                'category_id' => $catWorkshop?->id,
                'status' => 'Planned',
            ],
            [
                'code' => 'ACT-003',
                'title' => 'Praktikum Desain Antarmuka',
                'description' => 'Membangun komponen Blade dengan CSS yang rapi.',
                'activity_date' => '2026-09-21',
                'category_id' => $catPraktikum?->id,
                'status' => 'Ongoing',
            ],
            [
                'code' => 'ACT-004',
                'title' => 'Rapat Proyek Mingguan',
                'description' => 'Evaluasi Mingguan.',
                'activity_date' => '2026-09-23',
                'category_id' => $catOrganisasi?->id,
                'status' => 'Ongoing',
            ],
            [
                'code' => 'ACT-005',
                'title' => 'Penyusunan strategi kuliah',
                'description' => 'Manajemen wakttu.',
                'activity_date' => '2026-09-10',
                'category_id' => $catAkademik?->id,
                'status' => 'Done',
            ],
        ];

        foreach ($kegiatan as $item) {
            Activity::updateOrCreate(['code' => $item['code']], $item);
        }
    }
}
