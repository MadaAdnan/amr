<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Coupon;

class CouponFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'coupon_name' => $this->faker->word,
            'coupon_code' => Str::upper($this->faker->unique()->randomLetter . $this->faker->unique()->randomLetter . $this->faker->unique()->randomLetter),
            'usage_limit' => $this->faker->randomNumber(2),
            'percentage_discount' => $this->faker->randomFloat(2, 1, 100),
            'active_from' => $this->faker->dateTimeBetween('now', '+30 days'),
            'active_to' => $this->faker->dateTimeBetween('+31 days', '+60 days'),
            'discount_type' => $this->faker->randomElement([Coupon::DISCOUNT_TYPE_PERCENTAGE,Coupon::DISCOUNT_TYPE_PRICE]),
        ];
    }
}
