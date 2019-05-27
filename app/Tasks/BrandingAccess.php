<?php

namespace App\Tasks;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\UserTrainingHistory;
use App\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use App\SubscriptionRepository\UserSubscriptions;

class BrandingAccess {

   public static function verify($product_id){ // get the user's subcribed products

        
        $user_id = Auth::id();

        $verify= UserTrainingHistory::where('user_id', $user_id)
        ->where('product_id', $product_id)
        ->where('quest_level', 4.4)
        ->where('score', '>=', 60.00)
        ->exists(); // this is a collection

        return $verify;

    }   

    public static function verifyInStringResponse($product_id){
        
        $user_id = Auth::id();

        $verify= UserTrainingHistory::where('user_id', $user_id)
        ->where('product_id', $product_id)
        ->where('quest_level', 4.4)
        ->where('score', '>=', 60.00)
        ->exists(); // this is a collection

        if($verify == true){

            return 'true';
        }else{
            return 'false';
        }
    }


}



