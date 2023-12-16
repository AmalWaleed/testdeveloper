<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\AchievementService;

class AchievementsController extends Controller
{
    public function index(User $user)
    {
        return response()->json([
            'unlocked_achievements' => [],
            'next_available_achievements' => [],
            'current_badge' => '',
            'next_badge' => '',
            'remaing_to_unlock_next_badge' => 0
        ]);
    }

    public function getAchievements(User $user)
    {
        // Retrieve unlocked achievements and next available achievements for the user
        
        $unlockedAchievements = AchievementService::getUnlockedAchievements($user);
        $nextAvailableAchievements = AchievementService::getNextAvailableAchievements($user);

        // Retrieve current badge, next badge, and remaining achievements to unlock the next badge
        $currentBadge = AchievementService::getCurrentBadge($user);
        $nextBadge = AchievementService::getNextBadge($user);
        $remainingToUnlockNextBadge = AchievementService::getRemainingToUnlockNextBadge($user);

        return response()->json([
            'unlocked_achievements' => $unlockedAchievements,
            'next_available_achievements' => $nextAvailableAchievements,
            'current_badge' => $currentBadge,
            'next_badge' => $nextBadge,
            'remaining_to_unlock_next_badge' => $remainingToUnlockNextBadge,
        ]);
    }

    public function getUnlockedAchievements(User $user)
    {
        // Assuming you have a relationship defined in the User model
        $unlockedAchievements = $user->achievements;

        return response()->json($unlockedAchievements);
    }
}
