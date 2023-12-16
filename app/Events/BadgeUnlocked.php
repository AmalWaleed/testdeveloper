<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Badge; // Add this line to import the Badge class

class BadgeUnlocked
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $badge;
    public $user;

    public function __construct(Badge $badge, User $user)
    {
        $this->badge = $badge;
        $this->user = $user;

        // Create the achievement_user record
        $achievement = $this->badge->achievements->first();

        $this->user->achievements()->attach(
            $achievement,
            ['badge_id' => $this->badge->id, 'unlocked' => true],
            ['user_id' => $this->user->id]
        );
    }

    public function handle()
    {
        // Attach the badge to the user
        $this->user->updateCurrentBadge($this->badge->id);
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
