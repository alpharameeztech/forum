<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ForumReply;
use Faker\Generator as Faker;

$factory->define(ForumReply::class, function (Faker $faker) {
    return [
        'forum_thread_id' =>function(){

            return factory('App\ForumThread')->create()->id;
        },
        'user_id' =>function(){

            return factory('App\User')->create()->id;
        },
        'body' => $faker->paragraph()
    ];
});
