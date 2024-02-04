<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Country;

class StateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $country = Country::factory()->create();

        return [
            'name' => $this->faker->name,
            'country_id' => $country->id,
            'status' => 'Active',    
        ];
    }
}
