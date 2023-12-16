<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'body',
        'user_id'
    ];

    /**
     * Get the user that wrote the comment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function create(Request $request)
{
    // Create the comment
    $comment = Comment::create($request->all());

    // Check if it's the user's first comment
    if ($comment->user->comments->count() == 1) {
        $firstCommentAchievement = Achievement::where('name', 'First Comment Written')->first();
        $comment->user->unlockAchievement($firstCommentAchievement);
    }

    // ...
}
}
