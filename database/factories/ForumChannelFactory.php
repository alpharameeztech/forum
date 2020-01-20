<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ForumChannel;
use Faker\Generator as Faker;

$factory->define(ForumChannel::class, function (Faker $faker) {
    $name = $faker->word();

    return [
       
        'name' => $name,
        'slug' => $name
    
    ];
});
