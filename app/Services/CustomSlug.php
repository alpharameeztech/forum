<?php

namespace App\Services;


use App\ForumThread;

class CustomSlug
{
    /**
     * @param $title
     * @return string
     * @throws \Exception
     */
    public static function create($title)
    {

        // Normalize the title
        $slug = str_slug($title);

        // Get any that could possibly be related.
        // This cuts the queries down by doing it once.

        $slugAlreadyExist = ForumThread::where('slug', $slug)->count();

        // If the slug already exist, then append auth user id.
        if ( $slugAlreadyExist ){
            return $slug . '-' . auth()->user()->id;
        }

        //else we are good to return the slug
        //with str_slug call
        return $slug;

    }

}
