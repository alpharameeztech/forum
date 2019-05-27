<?php

namespace App\Repository\WorkoutCategory;

use App\WorkoutCategory;

class AllCategories
{
    //this class return the categories data in a format require by the
    public static function formatted()
    {

        $categories = WorkoutCategory::get();

        foreach ($categories as $key => $value) {

            $categories[$key]['text'] = $value['title']; // adding a new key with value same as of title for the sidebar navigation

            $categories[$key]['url'] = "/scripts/$value[id]";
           
            $categories[$key]['seal'] = $value['image'];
        }
        return $categories;

    }

}
