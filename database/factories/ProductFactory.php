<?php

namespace Database\Factories;

use App\Modules\Products\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;
    public function definition(): array
    {
        $name = fake()->words(3, true);

        return [
            'name' => $name,
            'slug' => Str::slug($name) . '-' . fake()->unique()->numberBetween(1, 99999),
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 10000, 1000000),
            'stock' => fake()->numberBetween(0, 100),
            'low_stock_threshold' => 5,
            'is_active' => true,
        ];
    }
}
