<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => fake()->uuid(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'is_admin' => false,
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'created_at' => date('Y-m-d'),
            'updated_at' => null,
            'religion' => fake()->randomElement(['islam', 'christian']),
            'phone' => fake()->numerify('628##########'),
            'address' => fake()->address(),
            'birth_date' => fake()->date('Y-m-d', date('Y-m-d', strtotime(date('Y-m-d') . ' -18 year'))),
            'social_number' => fake()->numerify('################'),
        ];
    }
}
