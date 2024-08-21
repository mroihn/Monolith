<?php

namespace Database\Factories;

use Carbon\Carbon;
use Faker\Factory as faker;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */

class UserFactory extends Factory
{
    public function definition(): array
    {
        $faker = faker::create();
        return [
            'first_name'=>$faker->firstName(),
            'last_name'=>$faker->lastName(),
            'username'=>$faker->unique()->userName(),
            'email'=>$faker->unique()->email(),
            'password'=>Hash::make('12345678'),
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ];
    }
}
