<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ChildSeatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title'=>$this->faker->title,
            'description'=>$this->faker->sentence(6),
            'price'=>$this->faker->randomNumber(2),
            'status'=>'Published',
        ];
    }
}
