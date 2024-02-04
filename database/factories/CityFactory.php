<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\State;

class CityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $state = State::factory()->create();

        return [
            'title' => $this->faker->sentence,
            'status' => 'Active',
            'state_id' => $state->id,
            'daily_from' => $this->faker->time('H:i:s'),
            'daily_to' => $this->faker->time('H:i:s'),
            'daily_price' => $this->faker->randomFloat(2, 10, 100),
            'split_hour_mechanism' => $this->faker->boolean,
            'split_hour_mechanism_price' => $this->faker->randomFloat(2, 5, 50),
            'city_id' => $this->faker->randomDigitNotNull,
            'updated_at' => $this->faker->dateTimeThisYear,
        ];
    }
}
