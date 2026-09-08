<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $categories = ['Maths','English','Science','History','Geography'];

        foreach ($categories as $category) {
            Category::create([
                'slug' => strtolower($category),
                'name' => $category,
            ]);
        }
    }
}
