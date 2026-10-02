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
                'title' => 'Dasar Framework Laravel 13',
                'description' => 'Mempelajari dasar request lifecycle, routing, dan Blade templating.',
                'start_at' => '2026-10-01',
                'end_at' => '2026-10-02',
                'location' => 'Lab Komputer 1',
                'capacity' => 40,
                'category_id' => $catAkademik?->id,
                'status' => 'draft',
            ],
            [
                'code' => 'ACT-002',
                'title' => 'Workshop Git dan GitHub Collaboration',
                'description' => 'Latihan kolaborasi branch, conflict resolution, dan pull request.',
                'start_at' => '2026-10-05',
                'end_at' => '2026-10-06',
                'location' => 'Gedung D4 Lt 3',
                'capacity' => 60,
                'category_id' => $catWorkshop?->id,
                'status' => 'published',
            ],
            [
                'code' => 'ACT-003',
                'title' => 'Praktikum Desain Antarmuka Responsif',
                'description' => 'Membangun antarmuka modern dengan utility CSS dan Blade component.',
                'start_at' => '2026-10-08',
                'end_at' => '2026-10-09',
                'location' => 'Lab Multimedia',
                'capacity' => 45,
                'category_id' => $catPraktikum?->id,
                'status' => 'published',
            ],
            [
                'code' => 'ACT-004',
                'title' => 'Rapat Pleno Organisasi Mahasiswa',
                'description' => 'Evaluasi program kerja semester gasal.',
                'start_at' => '2026-09-20',
                'end_at' => '2026-09-20',
                'location' => 'Ruang Teater',
                'capacity' => 100,
                'category_id' => $catOrganisasi?->id,
                'status' => 'completed',
            ],
            [
                'code' => 'ACT-005',
                'title' => 'Penyusunan Strategi Akademik Mandiri',
                'description' => 'Manajemen waktu dan persiapan ujian tengah semester.',
                'start_at' => '2026-09-15',
                'end_at' => '2026-09-16',
                'location' => 'Auditorium Barat',
                'capacity' => 150,
                'category_id' => $catAkademik?->id,
                'status' => 'completed',
            ],
            [
                'code' => 'ACT-006',
                'title' => 'Workshop REST API dengan Laravel Sanitization',
                'description' => 'Membangun endpoint API aman dan terstandarisasi.',
                'start_at' => '2026-10-12',
                'end_at' => '2026-10-13',
                'location' => 'Lab Jaringan',
                'capacity' => 35,
                'category_id' => $catWorkshop?->id,
                'status' => 'published',
            ],
            [
                'code' => 'ACT-007',
                'title' => 'Praktikum Database Migrations dan Seeding',
                'description' => 'Implementasi skema database relasional tingkat lanjut.',
                'start_at' => '2026-10-15',
                'end_at' => '2026-10-16',
                'location' => 'Lab Komputer 2',
                'capacity' => 40,
                'category_id' => $catPraktikum?->id,
                'status' => 'draft',
            ],
            [
                'code' => 'ACT-008',
                'title' => 'Seminar Karir Web Developer Masa Depan',
                'description' => 'Menghadirkan pembicara industri teknologi dan alumni berprestasi.',
                'start_at' => '2026-10-20',
                'end_at' => '2026-10-20',
                'location' => 'Auditorium Utama',
                'capacity' => 250,
                'category_id' => $catAkademik?->id,
                'status' => 'published',
            ],
            [
                'code' => 'ACT-009',
                'title' => 'Latihan Kepemimpinan Mahasiswa Tingkat Dasar',
                'description' => 'Pelatihan komunikasi efektif, negosiasi, dan kepemimpinan tim.',
                'start_at' => '2026-10-25',
                'end_at' => '2026-10-27',
                'location' => 'Bumi Perkemahan',
                'capacity' => 80,
                'category_id' => $catOrganisasi?->id,
                'status' => 'draft',
            ],
            [
                'code' => 'ACT-010',
                'title' => 'Workshop Static Analysis dengan SonarQube',
                'description' => 'Mendeteksi code smell dan vulnerability sejak dini.',
                'start_at' => '2026-10-28',
                'end_at' => '2026-10-29',
                'location' => 'Lab Rekayasa Perangkat Lunak',
                'capacity' => 50,
                'category_id' => $catWorkshop?->id,
                'status' => 'published',
            ],
            [
                'code' => 'ACT-011',
                'title' => 'Praktikum Automated Testing PHPUnit',
                'description' => 'Menulis unit test dan feature test terstruktur.',
                'start_at' => '2026-11-02',
                'end_at' => '2026-11-03',
                'location' => 'Lab Komputer 1',
                'capacity' => 40,
                'category_id' => $catPraktikum?->id,
                'status' => 'published',
            ],
            [
                'code' => 'ACT-012',
                'title' => 'Bakti Sosial Komunitas Peduli Teknologi',
                'description' => 'Literasi komputer bagi siswa sekolah dasar terpencil.',
                'start_at' => '2026-11-05',
                'end_at' => '2026-11-06',
                'location' => 'Balai Desa Sukamaju',
                'capacity' => 30,
                'category_id' => $catOrganisasi?->id,
                'status' => 'completed',
            ],
            [
                'code' => 'ACT-013',
                'title' => 'Kuliah Umum Kecerdasan Buatan dan Big Data',
                'description' => 'Pengenalan Machine Learning dan pemanfaatannya di web enterprise.',
                'start_at' => '2026-11-10',
                'end_at' => '2026-11-10',
                'location' => 'Auditorium Lantai 4',
                'capacity' => 200,
                'category_id' => $catAkademik?->id,
                'status' => 'draft',
            ],
            [
                'code' => 'ACT-014',
                'title' => 'Workshop DevOps dan CI/CD Pipeline',
                'description' => 'Deploy otomatis aplikasi Laravel menggunakan GitHub Actions.',
                'start_at' => '2026-11-15',
                'end_at' => '2026-11-16',
                'location' => 'Lab Server',
                'capacity' => 30,
                'category_id' => $catWorkshop?->id,
                'status' => 'published',
            ],
            [
                'code' => 'ACT-015',
                'title' => 'Praktikum Optimasi Query dan Caching Redis',
                'description' => 'Mengatasi bottleneck database dan caching data query.',
                'start_at' => '2026-11-20',
                'end_at' => '2026-11-21',
                'location' => 'Lab Komputer 3',
                'capacity' => 45,
                'category_id' => $catPraktikum?->id,
                'status' => 'published',
            ],
            [
                'code' => 'ACT-016',
                'title' => 'Malam Keakraban Mahasiswa Informatika',
                'description' => 'Temu ramah tamah antar angkatan mahasiswa D3 TI.',
                'start_at' => '2026-11-25',
                'end_at' => '2026-11-26',
                'location' => 'Wisma Seni Budaya',
                'capacity' => 120,
                'category_id' => $catOrganisasi?->id,
                'status' => 'completed',
            ],
        ];

        foreach ($kegiatan as $item) {
            Activity::updateOrCreate(['code' => $item['code']], $item);
        }
    }
}
