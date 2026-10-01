<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Akademik', 'slug' => 'akademik'],
            ['name' => 'Workshop', 'slug' => 'workshop'],
            ['name' => 'Praktikum', 'slug' => 'praktikum'],
            ['name' => 'Organisasi', 'slug' => 'organisasi'],
            ['name' => 'Kategori Kosong', 'slug' => 'kategori-kosong'], // Digunakan untuk uji hapus kategori kosong
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
