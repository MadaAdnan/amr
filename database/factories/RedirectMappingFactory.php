<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RedirectMappingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'old_url' => $this->faker->url,
            'new_url' => $this->faker->url,
            'is_active' => $this->faker->boolean,
        ];
    }
}
