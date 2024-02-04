<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FleetCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
        'title' =>$this->faker->title,
        'short_title' => $this->faker->title,
        'slug' => $this->faker->unique()->title,
        'image_alt' => $this->faker->title,
        'category_description' => $this->faker->title,
        'passengers' => 2,
        'flight_tracking' => 1,
        'luggage' => 2,
        'seo_title' => $this->faker->title,
        'seo_description' => $this->faker->title,
        'seo_keyphrase' => $this->faker->title,
        'split_hour_mechanism' => 15,
        'split_hour_mechanism_price' => 5,
        'pricing_rules' => json_encode(config('general.default_pricing')),
        'daily_from' => '5:00',
        'daily_to' => '6:00',
        'daily_price' => '90',
        'content' => 'test',
        ];
    }
}
