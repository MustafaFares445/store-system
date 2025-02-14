<?php

namespace Database\Factories;

use Illuminate\Support\Carbon;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'name'  => fake()->sentence(3),
        ];
    }
}
