<?php

namespace App\Repository\Channels;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\ForumChannel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class All
{
    public static function get()
    {

       $channels =  ForumChannel::get();

       return $channels;

    }
}
