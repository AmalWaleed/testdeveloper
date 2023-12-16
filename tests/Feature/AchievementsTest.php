<?php

namespace Tests\Feature;

use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;
use App\Models\Badge;
use App\Models\Achievement;

class AchievementsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_unlock_achievements()
    {
        // Create a user
        $user = User::factory()->create();

        // Create an achievement
        $achievement = Achievement::factory()->create();

        // Attach the achievement to the user
        event(new AchievementUnlocked($achievement, $user));

        // Refresh the user instance from the database
        $user->refresh();

        // Check if the user has the achievement
        $this->assertTrue($user->hasAchievement($achievement));
    }

    /** @test */
    public function user_can_unlock_badges()
    {
        // Create a user
        $user = User::factory()->create();

        // Create a badge and an associated achievement
        $badge = Badge::factory()->create();
        $achievement = Achievement::factory()->create();
        $badge->achievements()->attach($achievement);

        // Dispatch the BadgeUnlocked event before refreshing the user instance
        event(new BadgeUnlocked($badge, $user));

        // Refresh the user instance from the database
        $user->refresh();

        // Dump badge associations for debugging
        dump("Actual Badge IDs: " . json_encode($user->badges()->pluck('id')->toArray()));
        dump("Expected Badge ID: " . $badge->id);

        // Dump user badges for debugging
        dump("User Badges: " . json_encode($user->badges->pluck('id')->toArray()));

        // Assert that the user has the badge
        $this->assertTrue($user->hasBadge($badge));
    }

    /** @test */
    public function achievements_endpoint_returns_expected_data()
    {
        // Create a user
        $user = User::factory()->create();

        // Make a request to the achievements endpoint
        $response = $this->get("/users/{$user->id}/achievements");

        // Assert a successful response
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'unlocked_achievements',
                'next_available_achievements',
                'current_badge',
                'next_badge',
                'remaining_to_unlock_next_badge',
            ]);

        // Assert the response structure and content
        $response->assertJson([
            'current_badge' => optional($user->getCurrentBadge())->name,
            'next_badge' => optional($user->getNextBadge())->name,
            // Add other assertions as needed
        ]);
    }
}
