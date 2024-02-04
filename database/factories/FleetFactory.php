<?php

namespace Database\Factories;

use App\Models\FleetCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class FleetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $data = FleetCategory::factory()->create();
        return [
        'title' =>  $this->faker->title,
        'slug' =>  $this->faker->unique()->title,
        'seats' =>  1,
        'luggage' =>  1,
        'passengers' =>  1,
        'image_alt' =>  $this->faker->title,
        'category_id' =>  $data->id,
        'content' =>  $this->faker->title,
        'seo_title' =>  $this->faker->title,
        'license' =>  $this->faker->title,
        'user_id' =>  1,
        'seo_description' =>  $this->faker->title,
        'seo_keyphrase'=> $this->faker->title
        ];
    }
}
