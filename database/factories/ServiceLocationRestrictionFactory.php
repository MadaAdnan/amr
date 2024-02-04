<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ServiceLocationRestriction;
use App\Models\City;

class ServiceLocationRestrictionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $city = City::factory()->create();
        return
        [
            'address' => $this->faker->address,
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'status' => $this->faker->randomElement([ServiceLocationRestriction::ACTIVE_STATUS,ServiceLocationRestriction::INACTIVE_STATUS]),
            'service' => $this->faker->randomElement([ServiceLocationRestriction::SERVICE_POINT_TO_POINT,ServiceLocationRestriction::SERVICE_HOURLY,ServiceLocationRestriction::SERVICE_BOTH]),
            'service_limitation' =>$this->faker->randomElement([ServiceLocationRestriction::SERVICE_LIMITATION_PICKUP,ServiceLocationRestriction::SERVICE_LIMITATION_DROP_OFF]),
            'city_id' => $city->id,
            'radius' => $this->faker->randomFloat(2, 1, 100)
        ];
    }
}
