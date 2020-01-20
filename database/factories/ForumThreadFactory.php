<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ForumThread;
use Faker\Generator as Faker;

$factory->define(ForumThread::class, function (Faker $faker) {
    $title = $faker->sentence();
    
    return [
        'user_id' =>function(){

            return factory('App\User')->create()->id;
        },
        'forum_channel_id' =>function(){

            return factory('App\ForumChannel')->create()->id;
        },
        'replies_count' => 0,
        'is_ban' => '0',
        'title' => $title,
        'slug' => str_slug($title),
        'body' => $faker->paragraph()
    
    ];
});
