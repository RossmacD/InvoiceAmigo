<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Invoice;
use Faker\Generator as Faker;

$factory->define(Invoice::class, function (Faker $faker) {
    return [
        // 'invoice_number' => $faker->numberBetween(10,300),
        // 'invoice_date' => $faker->date($format = 'Y-m-d', $max = 'now'),
        // 'due_date' => $faker->date($format = 'Y-m-d', $max = 'now'),
    ];
});
