<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    protected $model = Employee::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $entryDate = fake()->dateTimeThisDecade('now +1 year');
        $is_active = ($entryDate->getTimestamp() < strtotime(date('Y-m-d')));

        return [
            'entry_date' => $entryDate,
            'is_active' => $is_active,
        ];
    }
}
