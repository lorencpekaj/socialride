<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Trip;
use Faker\Generator as Faker;

$factory->define(Trip::class, function (Faker $faker) {
    return [
        'distance' => rand(1000, 25000),
        'duration' => rand(300, 3600),
    ];
});
