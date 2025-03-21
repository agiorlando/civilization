<?php
declare(strict_types=1);

namespace Database\Factories;

use App\Models\HistoricalInfo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HistoricalInfo>
 */
class HistoricalInfoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'taxonomy_id' => 1,
            'heading' => $this->faker->sentence,
            'text' => $this->faker->paragraph,
            'type' => 'App\\Models\\Civilization',
        ];
    }
}
