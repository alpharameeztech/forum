<?php

namespace App\Repository\Forum;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\UserTrainingHistory;
use App\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\SubscriptionRepository\UserSubscriptions;
use App\Question;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserAccount
{

    public static function get()
    {

        return User::where('id', Auth::user()->id)
                    ->first();

    }

    public static function update()
    {

        $user =  User::where('id', Auth::user()->id)
                        ->first();

        if (!empty(request()->file('profilePicture'))) {
            $user->avatar = request()->file('profilePicture')->store('avatars', 's3');
        }

        $user->name = request()->name;

        $user->email = request()->email;

        if(request()->password != ''){
            $user->password = Hash::make(request()->password);
        }

        $user->save();

    }
}
