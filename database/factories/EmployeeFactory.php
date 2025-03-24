<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EmployeeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'employee_id'   => 'EMP' . $this->faker->unique()->numberBetween(100, 999),
            'employee_name' => $this->faker->name(),
            'gender'        => $this->faker->randomElement(['male', 'female']),
            'birthday'      => $this->faker->date(),
            'email'         => $this->faker->unique()->safeEmail(),
            'created_at'    => now(),
            'updated_at'    => now(),
        ];
    }
}
