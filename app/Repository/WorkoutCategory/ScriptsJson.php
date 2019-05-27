<?php

namespace App\Repository\WorkoutCategory;

use App\WorkoutCategory;
use App\WorkoutScript;

class ScriptsJson
{

    public static function get($category_id)
    {

        $categorys_scripts = WorkoutScript::where('workout_category_id',$category_id)
                                            ->get();

        // $categorys_scripts = WorkoutScript::get();

        return $categorys_scripts;

    }

}
