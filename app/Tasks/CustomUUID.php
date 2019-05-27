<?php

namespace App\Tasks;

use App\User;
use Illuminate\Support\Facades\Auth;
use Webpatser\Uuid\Uuid;

class CustomUUID
{

    public static function get()
    {

        $user_id = Auth::id();

        $string = Uuid::generate()->string;

        $uuid_exploded = explode('-', $string);

        //now get the last enter userid
        $last_inserted_user_id = User::orderBy('id', 'desc')->pluck('id')->first();

        $last_inserted_user_id += 1;

        $uuid_exploded[4] = $uuid_exploded[4] . $last_inserted_user_id; // concatenating the last use id with last portion of the UUID

        $array = array($uuid_exploded[0], $uuid_exploded[1], $uuid_exploded[2], $uuid_exploded[3], $uuid_exploded[4]);

        $uuid = implode("-", $array);

        return $uuid;

    }

}
