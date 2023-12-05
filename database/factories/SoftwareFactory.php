<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Software;
use App\Models\SoftwareCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Software>
 */
class SoftwareFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     *
     * @throws \Exception
     */
    public function definition(): array
    {
        return [
            'asset_number' => fake('en')->unique()->name(),
            'category_id' => SoftwareCategory::factory()->create()->getKey(),
            'name' => fake()->name(),
            'sn' => Str::random(10),
            'specification' => Str::random(10),
            'max_license_count' => random_int(10, 100),
            'image' => fake()->url(),
            'brand_id' => Brand::factory()->create()->getKey(),
        ];
    }
}
