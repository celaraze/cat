<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Device;
use App\Models\DeviceCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Device>
 */
class DeviceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'asset_number' => fake('en')->unique()->name(),
            'category_id' => DeviceCategory::factory()->create()->getKey(),
            'name' => fake()->name(),
            'sn' => Str::random(10),
            'specification' => Str::random(10),
            'image' => fake()->url(),
            'brand_id' => Brand::factory()->create()->getKey(),
        ];
    }
}
