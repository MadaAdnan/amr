<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Event;
use App\Models\City;

class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $city = City::factory()->create();

        return [
            'name' => $this->faker->word,
            'start_date' => $this->faker->date,
            'end_date' => $this->faker->date,
            'start_time' => $this->faker->time,
            'end_time' => $this->faker->time,
            'city_id' => $city->id,
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'radius' => $this->faker->randomFloat(2, 1, 100),
            'radius_area' => $this->faker->randomNumber(3),
            'price' => $this->faker->randomFloat(2, 10, 100),
            'address' => $this->faker->address,
            'status' => Event::STATUS_ACTIVE,
            'discount_type' => $this->faker->randomElement([Event::DISCOUNT_TYPE_PERCENTAGE, Event::DISCOUNT_TYPE_PRICE]),
            'apply_for' => $this->faker->randomElement([Event::APPLY_FOR_BOTH, Event::APPLY_FOR_PICKUP,Event::APPLY_FOR_DROP_OFF]),
            'service_type' => $this->faker->randomElement([1,2]),
            'endless' => $this->faker->boolean,
        ];
    }
}
