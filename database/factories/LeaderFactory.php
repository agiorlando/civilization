<?php
declare(strict_types=1);

namespace Database\Factories;

use App\Models\Leader;
use App\Models\Civilization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Leader>
 */
class LeaderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'civilization_id' => Civilization::factory(),
            'icon' => $this->faker->imageUrl(100, 100, 'people', true),
            'subtitle' => $this->faker->sentence,
            'lifespan' => $this->faker->year . ' - ' . $this->faker->year,
        ];
    }
}
