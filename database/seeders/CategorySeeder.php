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
        // create categories

        $categories = [
            ['name' => 'Kopfbedeckung', 'is_impacted_by_rain' => true],
            ['name' => 'T-Shirt', 'is_impacted_by_rain' => false],
            ['name' => 'Pullover', 'is_impacted_by_rain' => false],
            ['name' => 'Jacke', 'is_impacted_by_rain' => true],
            ['name' => 'Hose', 'is_impacted_by_rain' => true],
            ['name' => 'Strumpfhose', 'is_impacted_by_rain' => false],
            ['name' => 'Socken', 'is_impacted_by_rain' => false],
            ['name' => 'Schuhe', 'is_impacted_by_rain' => true],
            ['name' => 'Accessoires', 'is_impacted_by_rain' => true],
            ['name' => 'Sonnenbrille', 'is_impacted_by_rain' => false],
            ['name' => 'Sonnencreme', 'is_impacted_by_rain' => false],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(['name' => $category['name']], $category);
        }
    }
}
