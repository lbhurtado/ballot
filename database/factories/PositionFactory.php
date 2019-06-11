<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;
use Faker\Factory as FakerFactory;

$factory->define(get_class(app('ballot.position')), function (Faker $faker) {
    $faker = FakerFactory::create('en_PH');
    
    return [
        'name' => $faker->name,
        'seats' => $faker->numberBetween(1,12),
        'level' => $faker->numberBetween(1,4),
    ];
});
