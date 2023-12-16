<?php

namespace Database\Seeders;

use App\Models\Lesson;
use App\Models\Achievement;
use App\Models\Badge;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $lessons = Lesson::factory()
            ->count(20)
            ->create();

        $badges = Badge::factory()
            ->count(10)
            ->create();

        $achievements = Achievement::factory()
            ->count(10)
            ->create();
    }
}
