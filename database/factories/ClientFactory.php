<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Client;
use Faker\Generator as Faker;

$factory->define(Client::class, function (Faker $faker) {
    return [
         'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'avatar' => 'avatars/avatar.png',
        'email' => $faker->unique()->safeEmail,
    ];
});
