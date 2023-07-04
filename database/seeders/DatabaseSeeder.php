<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        \App\Models\User::factory()->create([
            'name' => 'Naufal Hady',
            'email' => 'naufalhady08@yahoo.com',
            'active' => true,
        ]);

        $this->call([
            ShieldSeeder::class,
            RoleSeeder::class,

            // ProductCategorySeeder::class,
            // ProductSeeder::class,
            SupplierSeeder::class,
            ItemCategorySeeder::class,
            ItemSeeder::class,

            ItemTransactionSeeder::class
        ]);
    }
}