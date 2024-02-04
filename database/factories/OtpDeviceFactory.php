<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OtpDeviceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'device' => $this->faker->word,
        ];
    }
}
