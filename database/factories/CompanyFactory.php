<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Country;
use App\Models\State;
use App\Models\City;

class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $country = Country::factory()->create();
        $state = State::factory()->create();
        $city = City::factory()->create();

        return [
            'company_name' => $this->faker->company,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->unique()->phoneNumber,
            'street' => $this->faker->streetAddress,
            'country_id' => $country->id,
            'state_id' => $state->id,
            'city_id' => $city->id,
            'postal_code' => $this->faker->randomDigitNotNull,
        ];;
    }
}
