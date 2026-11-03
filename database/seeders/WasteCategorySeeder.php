<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WasteCategory;

class WasteCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Organik',
                'description' => 'Sampah yang mudah terurai secara alami.',
            ],
            [
                'name' => 'Anorganik',
                'description' => 'Sampah yang sulit terurai secara alami.',
            ],
            [
                'name' => 'B3',
                'description' => 'Sampah bahan berbahaya dan beracun.',
            ],
            [
                'name' => 'Lainnya',
                'description' => 'Kategori sampah lainnya.',
            ],
        ];

        foreach ($categories as $category) {
            WasteCategory::firstOrCreate(
                ['name' => $category['name']],
                ['description' => $category['description']]
            );
        }
    }
}
