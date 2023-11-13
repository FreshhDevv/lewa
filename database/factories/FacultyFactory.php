<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Faculty>
 */
class FacultyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Faculty of Engineering and Technology',
            'code' => random_int(1, 1000),
            'slug' => 'FET',
            'about' => $this->faker->text(),
        ];
    }
}
