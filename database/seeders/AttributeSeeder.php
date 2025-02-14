<?php

namespace Database\Seeders;

use App\Enums\AttributesFilterTypes;
use App\Enums\AttributesTypes;
use App\Models\Category;
use Illuminate\Database\Seeder;
use App\Models\Attribute;

class AttributeSeeder extends Seeder
{
    private array $values = [
        'Color'    => ['Red', 'Blue', 'Green', 'Black', 'White', 'Yellow', 'Purple'],
        'Size'     => ['XS', 'S', 'M', 'L', 'XL', 'XXL'],
        'Material' => ['Cotton', 'Polyester', 'Leather', 'Metal', 'Plastic'],
        'Storage'  => ['64GB', '128GB', '256GB', '512GB', '1TB'],
        'RAM'      => ['4GB', '8GB', '16GB', '32GB'],
    ];

    public function run(): void
    {
        $attributes = ['Color', 'Size', 'Material', 'Storage', 'RAM'];

        foreach ($attributes as $attributeName) {
            // Create the attribute
            $attribute = Attribute::query()->create([
                'name' => $attributeName,
                'type' => AttributesTypes::getRandomEnumValue(),
                'filter_type' => AttributesFilterTypes::getRandomEnumValue()
            ]);;

            // Prepare the options data
            $optionsData = array_map(function ($value) use ($attribute) {
                return ['attribute_id' => $attribute->id, 'value' => $value];
            }, $this->values[$attributeName]);

            // Insert the options
            $attribute->options()->createMany($optionsData);
        }

        Attribute::query()->first()->categories()->sync(Category::query()->latest()->first('id'));
        Attribute::query()->skip(1)->first()->categories()->sync(Category::query()->latest()->first('id'));
        Attribute::query()->skip(2)->first()->categories()->sync(Category::query()->latest()->skip(1)->first('id'));
    }
}
