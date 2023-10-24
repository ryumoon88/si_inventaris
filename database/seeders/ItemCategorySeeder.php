<?php

namespace Database\Seeders;

use App\Models\ItemCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ItemCategory::factory(10)->create();

        $datas = [
            [
                'name' => 'Camera',
                'slug' => 'camera',
            ],
            [
                'name' => 'Camera DSLR',
                'slug' => 'camera-dslr',
            ],
            [
                'name' => 'Drone',
                'slug' => 'drone',
            ],
            [
                'name' => 'Tripod',
                'slug' => 'tripod',
            ],
            [
                'name' => 'Actioncam',
                'slug' => 'actioncam',
            ],
        ];

        ItemCategory::insert($datas);
    }
}
