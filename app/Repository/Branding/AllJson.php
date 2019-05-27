<?php

namespace App\Repository\Branding;

use App\Branding;

class AllJson
{

    public static function get()
    {

        $brandings = Branding::get();

        return $brandings;

    }

}
