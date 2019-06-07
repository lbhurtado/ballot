<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;
use Faker\Factory as FakerFactory;

$factory->define(get_class(app('ballot.position')), function (Faker $faker) {
    $faker = FakerFactory::create('en_PH');
    
    return [
        'name' => $faker->name,
        'level' => $faker->numberBetween(1,4),
    ];
});
