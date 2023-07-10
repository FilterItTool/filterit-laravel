<?php

namespace FilterIt\Tests\Unit\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use FilterIt\Tests\Unit\Models\User;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition() : array
    {
        return [
            'name'  => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'age'   => $this->faker->numberBetween(10, 80),
        ];
    }
}