<?php

namespace Database\Factories;

use App\Models\Badge;
use Illuminate\Database\Eloquent\Factories\Factory;

class BadgeFactory extends Factory
{
    protected $model = Badge::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            // other attributes...
        ];
    }
}
