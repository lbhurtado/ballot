<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;
use Faker\Factory as FakerFactory;

$factory->define(get_class(app('ballot.ballot')), function (Faker $faker) {
    $faker = FakerFactory::create('en_PH');
    
    return [
        'code' => $faker->numberBetween(1000,9999) . '-' . $faker->numberBetween(10000,99990)
    ];
});
