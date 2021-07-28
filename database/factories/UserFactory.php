<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $id = $this->faker->unique()->safeEmail();

        return [
            'name' => "{$this->faker->firstName()} {$this->faker->lastName()}",
            'uniqueid' => $id,
            'email' => $id,
            'emails' => random_int(0, 1) ? "$id;{$this->faker->safeEmail()}" : null,
            'active' => true,
            'admin' => false,
        ];
    }
}
