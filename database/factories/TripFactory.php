<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Trip;
use Faker\Generator as Faker;

$factory->define(Trip::class, function (Faker $faker) {
    // making the duration proportional to distance
    $randomDistance = rand(1000, 25000);
    return [
        'distance' => $randomDistance,
        'duration' => $randomDistance / 11,
    ];
});
