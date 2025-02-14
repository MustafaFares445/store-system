<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Carbon;
use App\Models\Attribute;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductVariantFactory extends Factory
{
    protected $model = ProductVariant::class;

    public function definition(): array
    {
        return [
            'value'            => fake()->word(),
            'additional_price' => fake()->randomFloat(),
            'quantity'         => fake()->randomNumber(),

            'product_id'       => Product::factory(),
            'attribute_id'     => Attribute::factory(),
        ];
    }
}
