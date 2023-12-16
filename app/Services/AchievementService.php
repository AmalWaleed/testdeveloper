<?php

namespace App\Services;

use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Models\User;
use App\Models\Achievement;
use App\Models\Badge;
use Illuminate\Support\Facades\DB;

class AchievementService
{
    public static function unlockLessonAchievement(User $user, $lessonCount)
    {
        $unlockedAchievements = self::determineUnlockedAchievements($lessonCount, 'lesson');
        self::fireAchievementEvents($unlockedAchievements, $user);
        self::checkBadgeUnlock($user);
    }

    public static function unlockCommentAchievement(User $user, $commentCount)
    {
        $unlockedAchievements = self::determineUnlockedAchievements($commentCount, 'comment');
        self::fireAchievementEvents($unlockedAchievements, $user);
        self::checkBadgeUnlock($user);
    }

    protected static function fireAchievementEvents(array $unlockedAchievements, User $user)
    {
        foreach ($unlockedAchievements as $achievement) {
            event(new AchievementUnlocked($achievement, $user));
        }
    }

    protected static function checkBadgeUnlock(User $user)
    {
        $unlockedBadge = ''; // Logic to determine unlocked badge

        if ($unlockedBadge) {
            event(new BadgeUnlocked($unlockedBadge, $user));
        }
    }

    protected static function determineUnlockedAchievements($count, $type)
    {
        $unlockedAchievements = [];

        if ($count >= 1) {
            $unlockedAchievements[] = "First $type Unlocked";
        }

        if ($count >= 3) {
            $unlockedAchievements[] = "3 $type Unlocked";
        }

        return $unlockedAchievements;
    }

    public static function getUnlockedAchievements(User $user)
    {
        return Achievement::select('achievements.id', 'achievements.name')
            ->join('achievement_user', 'achievements.id', '=', 'achievement_user.achievement_id')
            ->where('achievement_user.user_id', $user->id)
            ->where('achievements.unlocked', true)
            ->get();
    }

    public static function getNextAvailableAchievements(User $user, $count = 5)
    {
        return Achievement::whereNotIn('id', $user->achievements->pluck('id'))
            ->take($count)
            ->get();
    }

    public static function getCurrentBadge(User $user)
    {
        return $user->badges()->latest()->first();
    }

    public static function getNextBadge(User $user)
    {
        return Badge::whereNotIn('id', $user->badges()->pluck('id')->toArray())
            ->orderBy('id')
            ->first();
    }

    public static function getUnlockedBadges(User $user)
{
    $unlockedBadges = Badge::select('badges.id', 'badges.name')  // Add table alias or name
        ->join('achievement_user', 'badges.id', '=', 'achievement_user.badge_id')
        ->where('achievement_user.user_id', $user->id)
        ->get();

    return $unlockedBadges;
}

public function getUserAchievements(User $user)
{
    $achievements = DB::table('badges')
        ->join('achievement_user', 'badges.id', '=', 'achievement_user.badge_id')
        ->join('achievements', 'achievements.id', '=', 'achievement_user.achievement_id')
        ->where('achievement_user.user_id', '=', $user->id)
        ->select('badges.id as `badges.id`') // Specify table name with backticks
        ->get();

    // ... process $achievements and return the result
}

public static function getRemainingToUnlockNextBadge(User $user)
{
    $currentBadge = self::getCurrentBadge($user);
    $nextBadge = self::getNextBadge($user);

    if (!$currentBadge || !$nextBadge) {
        return 0;
    }

    $remainingToUnlockNextBadge = $nextBadge->order - $currentBadge->order;

    return $remainingToUnlockNextBadge > 0 ? $remainingToUnlockNextBadge : 0;
}



}
