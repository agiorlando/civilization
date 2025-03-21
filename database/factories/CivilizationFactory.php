<?php
declare(strict_types=1);

namespace Database\Factories;

use App\Models\Civilization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Civilization>
 */
class CivilizationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'icon' => $this->faker->imageUrl(100, 100, 'business', true),
        ];
    }
}
