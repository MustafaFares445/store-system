<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use App\Models\Attribute;

class AttributeSeeder extends Seeder
{
    public function run(): void
    {
        $attributes = ['Color', 'Size', 'Material', 'Storage', 'RAM'];
        $attributesToInsert = array_map(function ($attribute){
            return [
                'name'  => $attribute,
            ];
        }, $attributes);

        Attribute::query()->insert($attributesToInsert);

        Attribute::query()->first()->categories()->sync(Category::query()->latest()->first('id'));
        Attribute::query()->skip(1)->first()->categories()->sync(Category::query()->latest()->first('id'));
        Attribute::query()->skip(2)->first()->categories()->sync(Category::query()->latest()->skip(1)->first('id'));
    }
}
