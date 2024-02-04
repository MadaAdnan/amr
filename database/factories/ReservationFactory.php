<?php

namespace Database\Factories;

use App\Models\FleetCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $fleetCategory = FleetCategory::factory()->create();
        $user = User::factory()->create();

        return [
            'pick_up_location'=>$this->faker->title,
            'drop_off_location'=>$this->faker->title,
            'pick_up_date'=> $this->faker->date($format = 'Y/m/d'),
            'pick_up_time'=>$this->faker->time($format = 'h:i'),
            'service_type'=>'1',
            'price'=>$this->faker->randomFloat(2, 100, 1000),
            'distance'=>$this->faker->randomNumber(2),
            'category_id'=>$fleetCategory->id,
            'return_date'=> $this->faker->date($format = 'Y/m/d'),
            'return_time'=>$this->faker->time($format = 'h:i'),
            'phone_primary'=>'09005600',
            'latitude'=>40.7826,
            'longitude'=>73.9656,
            'dropoff_latitude'=>40.7484,
            'dropoff_longitude'=>73.9857,
            'comment'=>'xxxx',
            'price_with_tip'=>40,
            'user_id'=>$user->id

        ];
    }
}
