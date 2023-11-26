<?php

namespace Database\Factories;

use App\Models\EmployeeStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EmployeeStatus>
 */
class EmployeeStatusFactory extends Factory
{
    protected $model = EmployeeStatus::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement([
                EmployeeStatus::CONTRACT,
                EmployeeStatus::PERMANENT,
            ]),
        ];
    }
}
