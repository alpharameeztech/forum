<?php

namespace App\Tasks;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\UserTrainingHistory;
use App\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use App\SubscriptionRepository\UserSubscriptions;
use App\Resource;


class Resources {

   public static function free(){ // get the user's subcribed products

     $resources = Resource::where('free',1)->latest()->get();
    
        return $resources;

    } 
    
    public static function all(){ // get the user's subcribed products

        $resources = Resource::latest()->get();
       
        return $resources;
   
       } 

   


}



