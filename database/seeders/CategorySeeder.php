<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [];
        $childCategories = [];

        // Main categories with their children
        $categoryData = [
            ['name' => 'Electronics', 'children' => [
                'Smartphones',
                'Laptops',
                'Accessories',
            ]],
            ['name' => 'Fashion', 'children' => [
                'Men\'s Clothing',
                'Women\'s Clothing',
            ]],
            ['name' => 'Home & Living', 'children' => [
                'Furniture',
                'Decor',
            ]],
            ['name' => 'Sports', 'children' => [
                'Equipment',
                'Clothing',
            ]],
            ['name' => 'Beauty', 'children' => [
                'Skincare',
                'Makeup',
            ]],
        ];

        // First, insert main categories
        foreach ($categoryData as $category) {
            $categories[] = [
                'name'       => $category['name'],
                'parent_id'  => null,
            ];
        }

        foreach ($categories as $category)
            Category::query()->create($category);

        // Then, insert child categories
        $parentCategories = Category::all()->keyBy('name');

        foreach ($categoryData as $category) {
            $parentId = $parentCategories[$category['name']]->id;
            foreach ($category['children'] as $childName) {
                $childCategories[] = [
                    'name'       => $childName,
                    'parent_id'  => $parentId,
                ];
            }
        }
        foreach ($childCategories as $childCategory)
            Category::query()->create($childCategory);
    }
}
