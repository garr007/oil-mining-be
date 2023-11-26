<?php

namespace Database\Factories;

use App\Models\Division;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Division>
 */
class DivisionFactory extends Factory
{
    protected $model = Division::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement([
                Division::MINING,
                Division::HRD,
                Division::RND,
                Division::SALES,
                Division::FINANCE,
                Division::LEGAL,
                Division::IT,
            ]),
        ];
    }
}
