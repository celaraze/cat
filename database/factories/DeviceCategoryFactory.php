<?php

namespace Database\Factories;

use App\Models\DeviceCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DeviceCategory>
 */
class DeviceCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->name,
        ];
    }
}
