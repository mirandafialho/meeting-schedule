<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
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
            'name'       => $this->faker->name(),
            'user_id'    => $user->id,
            'phone'      => $this->faker->phoneNumber(),
            'is_partner' => $this->faker->boolean(),
            'active'     => true
        ];
    }
}
