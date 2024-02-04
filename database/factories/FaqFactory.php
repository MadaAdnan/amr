<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FaqFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'question'=>$this->faker->title,
            'answer'=>$this->faker->title,
            'sort'=>1,
            'type'=>"General",
        ];
    }
}
