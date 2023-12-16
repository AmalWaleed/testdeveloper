<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('achievement_user', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned()->default(0); // Change the default value as needed
            $table->bigInteger('achievement_id')->unsigned();
            $table->bigInteger('badge_id')->unsigned()->nullable();
            $table->boolean('unlocked')->default(false); // Add this line
        
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('achievement_id')->references('id')->on('achievements')->onDelete('cascade');
        
            // Ensure that the data type and unsigned attribute match with the 'id' column in 'badges'
            $table->foreign('badge_id')->references('id')->on('badges')->onDelete('cascade');
        
            $table->primary(['user_id', 'achievement_id']);
            
            // Remove the timestamps
            $table->timestamps(null, null);
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achievement_user');
    }
};
