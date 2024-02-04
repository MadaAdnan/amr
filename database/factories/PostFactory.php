<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::factory()->create();
        return [
            'slug' => $this->faker->unique()->name,
            'title' => $this->faker->name,
            'content' => $this->faker->name,
            'status' => 'Draft',
            'date' => $this->faker->date('Y-m-d H:i:s'),
            'author' => $this->faker->name,
            'user_id' => $user->id,
            'seo_title' => $this->faker->name,
            'seo_description' => $this->faker->name,
            'reject_note' => $this->faker->name,
            'admin_reject_note' => '',
            'summary' => $this->faker->name
        ];
    }
}
