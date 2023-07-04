<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\ItemTransaction;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 100; $i++) {
            $item = Item::inRandomOrder()->limit(1)->get()[0];
            $issuer = User::inRandomOrder()->limit(1)->get()[0];
            $supplier = Supplier::inRandomOrder()->limit(1)->get()[0];
            $amount = fake()->numberBetween(1, 100);
            $status = fake()->randomElement(['Approved', 'Rejected', 'Pending']);

            $trans = ItemTransaction::factory()->create([
                'item_id' => $item->id,
                'issuer_id' => $issuer->id,
                'supplier_id' => $supplier->id,
                'amount' => $amount,
                'status' => $status,
                'created_at' => fake()->dateTimeThisYear()
            ]);

            if ($status == 'Approved') {
                $trans->approve();
            }
            if ($status == 'Rejected') {
                $trans->reject();
            }
            if ($status == 'Pending') {
                $trans->reject();
            }
        }
    }
}