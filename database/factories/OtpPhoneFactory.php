<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\OtpDevice;

class OtpPhoneFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $device = OtpDevice::factory()->create();
        return [
            'phone' => $this->faker->phoneNumber,
            'device_id' => $device->id,
            'otp_code' => $this->faker->randomNumber(6),

        ];
    }
}
