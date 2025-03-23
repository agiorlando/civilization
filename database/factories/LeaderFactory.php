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
    protected $model = Leader::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Randomly decide if the dates are in BC or AD
        $isBC = $this->faker->boolean; // true = BC, false = AD

        if ($isBC) {
            // For BC, life_start and life_end are negative strings.
            // Example: life_start = "-500" (i.e. 500 BC) and life_end = "-300" (i.e. 300 BC)
            $lifeStart = '-' . $this->faker->numberBetween(400, 600);
            $lifeEnd = '-' . $this->faker->numberBetween(200, 399);
        } else {
            // For AD, generate positive year strings.
            // Example: life_start = "300" and life_end = "500"
            $lifeStart = (string) $this->faker->numberBetween(100, 400);
            $lifeEnd = (string) $this->faker->numberBetween(401, 800);
        }

        return [
            'name' => $this->faker->name,
            'civilization_id' => Civilization::factory(),
            'icon' => $this->faker->imageUrl(100, 100, 'people', true),
            'subtitle' => $this->faker->sentence,
            'life_start' => $lifeStart,
            'life_end' => $lifeEnd,
        ];
    }
}
