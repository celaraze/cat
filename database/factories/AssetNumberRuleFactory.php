<?php

namespace Database\Factories;

use App\Models\AssetNumberRule;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AssetNumberRule>
 */
class AssetNumberRuleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     * @throws Exception
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name,
            'formula' => 'TEST-{year}-{month}-{day}-{auto-increment}',
            'auto_increment_length' => random_int(1, 5),
        ];
    }
}
