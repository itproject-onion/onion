<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'name' => 'Sonnenhut',
                'filepath' => '/storage/head1_cosi.png',
                'is_waterproof' => false,
                'min_temperature' => 20,
                'max_temperature' => 35,
                'min_uv_index' => null,
                'max_uv_index' => null,
                'cloud_cover_threshold' => null,
                'category_id' => 1, // Kopfbedeckung
            ],
            [
                'name' => 'Regenjacke',
                'filepath' => '/storage/jacke1_cosi.png',
                'is_waterproof' => true,
                'min_temperature' => 0,
                'max_temperature' => 15,
                'min_uv_index' => null,
                'max_uv_index' => null,
                'cloud_cover_threshold' => null,
                'category_id' => 4, // Jacke
            ],
            [
                'name' => 'Pullover',
                'filepath' => '/storage/pulli3_cosi.png',
                'is_waterproof' => null,
                'min_temperature' => 0,
                'max_temperature' => 15,
                'min_uv_index' => null,
                'max_uv_index' => null,
                'cloud_cover_threshold' => null,
                'category_id' => 3, // Pullover
            ],
            [
                'name' => 'T-Shirt',
                'filepath' => '/storage/shirt1_cosi.png',
                'is_waterproof' => null,
                'min_temperature' => 15,
                'max_temperature' => 35,
                'min_uv_index' => null,
                'max_uv_index' => null,
                'cloud_cover_threshold' => null,
                'category_id' => 2, // T-Shirt
            ],
            [
                'name' => 'Jeans',
                'filepath' => '/storage/lower2_cosi.png',
                'is_waterproof' => null,
                'min_temperature' => 10,
                'max_temperature' => 25,
                'min_uv_index' => null,
                'max_uv_index' => null,
                'cloud_cover_threshold' => null,
                'category_id' => 5, // Hose
            ],
            [
                'name' => 'Strumpfhose',
                'filepath' => '/storage/placeholder.png',
                'is_waterproof' => null,
                'min_temperature' => 15,
                'max_temperature' => 25,
                'min_uv_index' => null,
                'max_uv_index' => null,
                'cloud_cover_threshold' => null,
                'category_id' => 6, // Strumpfhose
            ],
            [
                'name' => 'Socken',
                'filepath' => '/storage/placeholder.png',
                'is_waterproof' => null,
                'min_temperature' => 0,
                'max_temperature' => 25,
                'min_uv_index' => null,
                'max_uv_index' => null,
                'cloud_cover_threshold' => null,
                'category_id' => 7, // Socken
            ],
            [
                'name' => 'Schuhe',
                'filepath' => '/storage/feet1_cosi.png',
                'is_waterproof' => false,
                'min_temperature' => 15,
                'max_temperature' => 25,
                'min_uv_index' => null,
                'max_uv_index' => null,
                'cloud_cover_threshold' => null,
                'category_id' => 8, // Schuhe
            ],
            [
                'name' => 'Handschuhe',
                'filepath' => '/storage/placeholder.png',
                'is_waterproof' => true,
                'min_temperature' => -10,
                'max_temperature' => 10,
                'min_uv_index' => null,
                'max_uv_index' => null,
                'cloud_cover_threshold' => null,
                'category_id' => 9, // Accessoires
            ],
            [
                'name' => 'Sonnenbrille',
                'filepath' => '/storage/placeholder.png',
                'is_waterproof' => null,
                'min_temperature' => null,
                'max_temperature' => null,
                'min_uv_index' => null,
                'max_uv_index' => null,
                'cloud_cover_threshold' => 60,
                'category_id' => 10, // Sonnenbrille
            ],
            [
                'name' => 'Sonnencreme LSF 30',
                'filepath' => '/storage/placeholder.png',
                'is_waterproof' => true,
                'min_temperature' => null,
                'max_temperature' => null,
                'min_uv_index' => 3,
                'max_uv_index' => 5,
                'cloud_cover_threshold' => null,
                'category_id' => 11, // Sonnencreme
            ],
            [
                'name' => 'Sonnencreme LSF 50',
                'filepath' => '/storage/placeholder.png',
                'is_waterproof' => true,
                'min_temperature' => null,
                'max_temperature' => null,
                'min_uv_index' => 5,
                'max_uv_index' => 10,
                'cloud_cover_threshold' => null,
                'category_id' => 11, // Sonnencreme
            ],
        ];

        foreach ($items as $item) {
            Item::updateOrCreate(
                ['name' => $item['name']],
                $item
            );
        }
    }
}
