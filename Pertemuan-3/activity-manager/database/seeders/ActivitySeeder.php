<?php

namespace Database\Seeders;

use App\Models\Activity;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kegiatan = [
            [
                'title' => 'Dasar Laravel 13',
                'description' => 'Mempelajari dasar-dasar request lifecycle dan routing.',
                'activity_date' => '2026-09-22',
                'category' => 'Akademik',
                'status' => 'Planned',
            ],
            [
                'title' => 'Workshop Git dan GitHub',
                'description' => 'Latihan kolaborasi branch dan pull request.',
                'activity_date' => '2026-09-25',
                'category' => 'Workshop',
                'status' => 'Planned',
            ],
            [
                'title' => 'Praktikum Desain Antarmuka',
                'description' => 'Membangun komponen Blade dengan CSS yang rapi.',
                'activity_date' => '2026-09-21',
                'category' => 'Praktikum',
                'status' => 'Ongoing',
            ],
            [
                'title' => 'Rapat Proyek Mingguan',
                'description' => 'Evaluasi Mingguan.',
                'activity_date' => '2026-09-23',
                'category' => 'Organisasi',
                'status' => 'Ongoing',
            ],
            [
                'title' => 'Penyusunan strategi kuliah',
                'description' => 'Manajemen wakttu.',
                'activity_date' => '2026-09-10',
                'category' => 'Akademik',
                'status' => 'Done',
            ],
        ];

        foreach ($kegiatan as $item) {
            Activity::create($item);
        }
    }
}
