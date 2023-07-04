<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->sentence(3);
        return [
            'name' => $name,
            'item_category_id' => fake()->numberBetween(1, 10),
            'supplier_id' => fake()->numberBetween(1, 30),
            'description' => fake()->paragraph(),
            'buy_price' => fake()->numberBetween(100000, 120000),
            // 'sell_price' => fake()->numberBetween(120000, 150000),
            // 'quantity_in_stock' => fake()->numberBetween(100, 120),
        ];
    }
}