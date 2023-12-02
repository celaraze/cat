<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\PartCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Part>
 */
class PartFactory extends Factory
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
            'category_id' => PartCategory::factory()->create()->getKey(),
            'sn' => Str::random(10),
            'specification' => Str::random(10),
            'image' => fake()->url(),
            'brand_id' => Brand::factory()->create()->getKey(),
        ];
    }
}
