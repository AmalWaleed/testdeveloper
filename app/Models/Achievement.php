<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;
        // Define the table associated with the model
        protected $table = 'achievements';

        // Define the fillable attributes for mass assignment
        protected $fillable = ['name', 'description'];
    
        // Relationships, if any (for example, if achievements are associated with users)
         public function users()
         {
             return $this->belongsToMany(User::class, 'user_achievements');
         }
}
