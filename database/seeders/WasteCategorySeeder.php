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
                'description' => 'Sampah organik yang mudah terurai secara alami.',
            ],
            [
                'name' => 'Kertas',
                'description' => 'Sampah kertas dan produk berbahan kertas.',
            ],
            [
                'name' => 'Plastik',
                'description' => 'Sampah berbahan plastik yang dapat didaur ulang.',
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
