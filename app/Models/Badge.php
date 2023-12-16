<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Achievement;

class Badge extends Model
{
    use HasFactory;
      // Define the table associated with the model
      protected $table = 'badges';

      // Define the fillable attributes
      protected $fillable = [
          'name',
          // Add other fields as needed
      ];
  
      // Define any relationships with other models
      // For example, if badges are related to achievements, you might have a belongsToMany relationship
      public function achievements()
      {
          return $this->belongsToMany(Achievement::class, 'achievement_user', 'badge_id', 'achievement_id')
              ->withTimestamps();
      }

      public function users()
      {
          return $this->belongsToMany(User::class);
      }
}
