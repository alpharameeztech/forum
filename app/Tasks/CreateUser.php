<?php

namespace App\Tasks;

use App\Notifications\SendLoginToStoreOwner;
use App\User;
use Illuminate\Support\Facades\Hash;

class CreateUser
{

    public static function execute($name, $email, $type, $shopId = null)
    {

        //generate 8 characters long random password
        $password = str_random(8);

        try {
            $user = User::create([
                'name'      => $name,
                'email'     => $email,
                'type'      => $type,
                'password'  => Hash::make($password),
                'shop_id'   => $shopId
            ]);


            //and then send email with login details
            //to the store owner'email
            $user->unencryptedPassword = $password;
            $user->notify(new SendLoginToStoreOwner($user));

            return $user;

        } catch (Exception $e) {
            report($e);

            \Log::info($e);

            return false;
        }

    }

}
