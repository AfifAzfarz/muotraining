<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Technology', 'color' => '#3B82F6'],
            ['name' => 'Health',     'color' => '#10B981'],
            ['name' => 'Business',   'color' => '#F59E0B'],
            ['name' => 'Sports',     'color' => '#EF4444'],
            ['name' => 'Travel',     'color' => '#8B5CF6'],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name'        => $category['name'],
                'slug'        => Str::slug($category['name']),
                'description' => "This is the {$category['name']} category.",
                'color'       => $category['color'],
                'is_visible'  => true,
            ]);
        }
    }
}