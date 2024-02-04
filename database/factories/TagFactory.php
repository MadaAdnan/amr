<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'slug' => $this->faker->unique()->slug,
            'subject' => $this->faker->word,
            'strength' => $this->faker->randomDigitNotNull,
            'monthly_volume' => $this->faker->randomNumber(),
            'is_keyword' => $this->faker->boolean,
        ];
    }
}
