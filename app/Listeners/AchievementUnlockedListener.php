<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AchievementUnlockedListener
{
    /**
     * Handle the event.
     */
    public function handle(AchievementUnlocked $event): void
    {
        $achievementName = $event->achievement; // Access the 'name' property of the achievement
        $user = $event->user;

        // Perform actions based on the unlocked achievement
        // For example, you can log the achievement or update user data in the database

        // Log the achievement
        \Log::info("Achievement Unlocked: $achievementName for User ID: $user->id");

        // Update user data (for example, update unlocked achievements array in the database)
        $user->updateUnlockedAchievements($achievementName);
    }
}
