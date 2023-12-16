<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Badge; // Make sure to import the Badge model

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getCurrentBadge()
{
    // Implement your logic to get the current badge for the user
    // This could involve querying the database or some other criteria based on your application

    // For example, you might want to retrieve the latest unlocked badge
    return $this->badges()->latest()->first();
}


    /**
     * The comments that belong to the user.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * The lessons that a user has access to.
     */
    public function lessons()
    {
        return $this->belongsToMany(Lesson::class);
    }

    /**
     * The lessons that a user has watched.
     */
    public function watched()
    {
        return $this->belongsToMany(Lesson::class)->wherePivot('watched', true);
    }

    public function achievements()
    {
        return $this->belongsToMany('App\Models\Achievement')->withTimestamps();
    }

    public function unlockAchievement($achievement)
{
    if (!$this->achievements->contains($achievement)) {
        $this->achievements()->attach($achievement);
    }
}


    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'achievement_user', 'user_id', 'achievement_id')
            ->withPivot('unlocked') // assuming 'unlocked' is the name of the pivot column
            ->withTimestamps(); // assuming you have timestamps on your pivot table
    }


    public function updateCurrentBadge($badgeId)
    {
        // Attach the badge to the user
        $this->badges()->attach($badgeId);
    
        // Dump user badges for debugging
        dump("Updated User Badges: " . json_encode($this->badges()->pluck('id')->toArray()));
    }
    


    public function getNextBadge()
    {
        $currentBadge = $this->getCurrentBadge(); // Assuming you have a method named 'getCurrentBadge'
    
        if ($currentBadge) {
            // Find the next badge based on the order
            $nextBadge = Badge::where('order', '>', $currentBadge->order)
                ->orderBy('order')
                ->first();
    
            return $nextBadge;
        }
    
        return null; // Return null if there is no current badge
    }

    public function updateUnlockedAchievements($achievements)
    {
        // Attach the provided achievements to the user
        $this->achievements()->syncWithoutDetaching($achievements);
    }

    
    public function hasBadge(Badge $badge)
    {
        return $this->badges->contains($badge);
    }
    
    

    public function hasAchievement($achievement)
{
    // Check if the user has the specified achievement
    return $this->achievements->contains('id', $achievement->id);
}


}

