<?php

namespace Database\Factories;

use Illuminate\Support\Carbon;
use App\Models\Attribute;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttributeFactory extends Factory
{
    protected $model = Attribute::class;

    public function definition(): array
    {
        return [
            'name'  => fake()->name(),
        ];
    }
}
