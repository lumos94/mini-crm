<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Client;
use App\Transaction;
use Faker\Generator as Faker;

$factory->define(Transaction::class, function (Faker $faker) {
    return [
        'client_id' => function () use ($faker) {
            // Check if at least one client exists, otherwise create a new one
            return  Client::firstOrCreate(
                ['email' => $faker->unique()->safeEmail], // Search by unique email
                [
                    'first_name' => $faker->firstName,
                    'last_name' => $faker->lastName,
                    'avatar' => 'default.png', // You can handle avatar upload separately if needed
                    'email' => $faker->unique()->safeEmail
                ]
            )->id;
        },
        'transaction_date' => $faker->dateTimeThisYear(), // Random date in the current year
        'amount' => $faker->randomFloat(2, 10, 1000), // Random float between 10 and 1000
    ];
});
