<?php

namespace App\Tasks;

use App\Branding;
use Intervention\Image\ImageManagerStatic as Image;
use Storage;
use Carbon\Carbon;

class CurrentYear
{

    public static function get()
    { 

        return Carbon::now()->year;

    }

}
