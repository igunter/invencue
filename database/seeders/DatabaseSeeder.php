<?php
namespace Database\Seeders;

use App\Models\AgeRange;
use App\Models\Category;
use App\Models\Game;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $age_ranges = ['6-10', '10-13', '13-16'];
        foreach ($age_ranges as $age_range) {
            AgeRange::create([
                'slug' => Str::slug($age_range),
                'name' => $age_range,
            ]);
        }

        $categories = ['Maths', 'English', 'Science', 'History', 'Geography'];
        foreach ($categories as $category) {
            Category::create([
                'slug' => Str::slug($category),
                'name' => $category,
            ]);
        }

        $games = [[
            'Category' => 'Maths',
            'Name'     => 'Addition',
            'Icon'     => 'bi bi-plus-circle',
            'AgeRange' => '6-10',
            'Blurb'    => 'Test your addition skills with this fun and interactive game. Solve addition problems and improve your math abilities while having a great time!',
        ], [
            'Category' => 'Maths',
            'Name'     => 'Subtraction',
            'Icon'     => 'bi bi-dash-circle',
            'AgeRange' => '6-10',
            'Blurb'    => 'Test your subtraction skills with this fun and interactive game. Solve subtraction problems and improve your math abilities while having a great time!',
        ], [
            'Category' => 'Maths',
            'Name'     => 'Multiplication',
            'Icon'     => 'bi bi-x-circle',
            'AgeRange' => '6-10',
            'Blurb'    => 'Test your multiplication skills with this fun and interactive game. Solve multiplication problems and improve your math abilities while having a great time!',
        ], [
            'Category' => 'Maths',
            'Name'     => 'Division',
            'Icon'     => 'bi bi-slash-circle',
            'AgeRange' => '6-10',
            'Blurb'    => 'Test your division skills with this fun and interactive game. Solve division problems and improve your math abilities while having a great time!',
        ]];

        foreach ($games as $game) {
            Game::create([
                'age_range_slug' => Str::slug($game['AgeRange']),
                'category_slug'  => Str::slug($game['Category']),
                'slug'           => Str::slug($game['Name']),
                'name'           => $game['Name'],
                'icon'           => $game['Icon'],
                'blurb'          => $game['Blurb'],
            ]);
        }
    }
}
