<?php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'category' => fake()->randomElement(['IR', 'Capture', 'Preset']),
            'hardware_model' => fake()->randomElement(['ToneX', 'Quad Cortex', 'Kemper', 'HX Stomp', 'Axe-Fx']),
            'cover_image_path' => null,
            'preset_file_path' => 'presets/demo.zip',
            'dry_sample_path' => 'samples/clean.wav',
            'wet_sample_path' => fake()->randomElement(['samples/clean.wav', 'samples/distortion.wav', 'samples/metal.wav']),
            'downloads_count' => fake()->numberBetween(0, 500),
        ];
    }
}
