<?php

namespace App\Tasks;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\UserTrainingHistory;
use App\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use App\SubscriptionRepository\UserSubscriptions;

class IsUnderFreeTrial {
    
    
    public static function get(){ // get the user's subcribed products

        
        if(Auth::user()->onGenericTrial()){

            return 'true';
        }
        else{
            return 'false';
        }

    } 


}



