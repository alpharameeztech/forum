<?php

namespace App\Tasks;
use App\WorkoutCategory;

class WorkoutCategoryName
{

    public static function get($id)
    { // get the user's subcribed products

        $name = WorkoutCategory::where('id',$id)->pluck('title')->first();

        return $name;

    }

}
