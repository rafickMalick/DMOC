<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Electronique', 'description' => 'Smartphones, accessoires et gadgets.'],
            ['name' => 'Mode', 'description' => 'Vetements, chaussures et accessoires.'],
            ['name' => 'Maison', 'description' => 'Articles maison et decoration.'],
            ['name' => 'Beaute', 'description' => 'Soins, parfum et cosmetiques.'],
        ];

        foreach ($categories as $category) {
            Category::query()->updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                [
                    'name' => $category['name'],
                    'slug' => Str::slug($category['name']),
                    'description' => $category['description'],
                    'parent_id' => null,
                ]
            );
        }
    }
}
