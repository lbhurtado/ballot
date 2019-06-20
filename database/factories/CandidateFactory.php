<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;
use Faker\Factory as FakerFactory;

$factory->define(get_class(app('ballot.candidate')), function (Faker $faker) {
    $faker = FakerFactory::create('en_PH');
    
    return [
        'code' => $faker->word,
        'name' => $faker->name,
        'position_id' => factory(get_class(app('ballot.position')))->create()->id,
        'sequence' => $faker->numberBetween(1,1000000),
    ];
});
