<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

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

        foreach ($categories as $category){
            $category = Category::query()->create($category);

            $category->addMedia(public_path('images/1.png'))->preservingOriginal()->toMediaCollection('images');
        }
           

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
        foreach ($childCategories as $childCategory){
            $childCategoryObject = Category::query()->create($childCategory);

            
            $childCategoryObject->addMedia(public_path('images/1.png'))->preservingOriginal()->toMediaCollection('images');
        }

    }

    private function attachMedia($category): void
    {
        $images = collect(Storage::disk('local')->files('demo-images'));

        $category->addMediaFromDisk($images->random())
            ->preservingOriginal()
            ->toMediaCollection('images');
    }
}
