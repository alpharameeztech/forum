<?php

namespace App\Tasks;
use App\User;

class Users {

   public static function all(){ // get the user's subcribed products

        $users  = User::get();

        
        foreach ($users as $user) {

           $user['key'] = $user->name;
           $user['value'] = $user->name;

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



