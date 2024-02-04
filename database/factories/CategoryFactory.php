<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
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
            'slug'=>$this->faker->unique()->title,
            'seo_title'=>$this->faker->title,
            'seo_description'=>$this->faker->title,
            'seo_keyphrase'=>$this->faker->title,
        ];
    }
}
