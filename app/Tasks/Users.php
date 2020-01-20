<?php

namespace App\Tasks;

use App\User;
use Illuminate\Support\Facades\Cache;

class Users
{
    public static function all()
    { // get the user's subcribed products

        $users  = User::where('shop_id',Cache::get('shop_id'))->get();

        foreach ($users as $user) {

            $user['key'] = $user->alias ? $user->alias : $user->name ;
            $user['value'] = $user->alias ? $user->alias : $user->name;

            unset($user['email']);
            unset($user['stripe_id']);
            unset($user['current_billing_plan']);
            unset($user['billing_city']);
            unset($user['billing_state']);
            unset($user['billing_country']);
            unset($user['vat_id']);
            unset($user['uuid']);
            unset($user['two_factor_reset_code']);

        }

        return $users;
    }
}
