<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\UserLocation;
use App\DropOff;

$randomBetween = function ($min = 0, $max = 1) {
    return ($min + ($max - $min) * (mt_rand() / mt_getrandmax()));
};

// random user location
$factory->define(UserLocation::class, function () use ($randomBetween) {
    return [
        'lat' => $randomBetween(-37.73, -37.85),
        'lng' => $randomBetween(144.70, 145.15),
    ];
});

// random drop off location
$factory->define(DropOff::class, function () use ($randomBetween) {
    return [
        'lat' => $randomBetween(-37.73, -37.85),
        'lng' => $randomBetween(144.70, 145.15),
        'address' => 'Randomly Generated Drop Off',
    ];
});
