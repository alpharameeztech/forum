<?php

namespace App\Tasks;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\UserTrainingHistory;
use App\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use App\SubscriptionRepository\UserSubscriptions;
use App\Tool;

class Tools
{
    public static function free()
    { // get the user's subcribed products

        // Auth::user()->subscription();    
        $tools = Tool::where('free', 1)->latest()->get();
    
        return $tools;
    }
    
    public static function all()
    { // get the user's subcribed products

        $tools = Tool::latest()->get();
       
        return $tools;
    }

    protected static function featured(){

      $featuredTools = Tool::where('featured', 1)->latest()->get();

      return $featuredTools;

    }

    /**
     * Get either the free tools
     * or the featured tools
     * for the dashboard
     * based on user subscription status
     */
    public static function dashboard(){

        $tools = '';

        if(Auth::user()->current_billing_plan == null){
          $tools = Tools::free();
    
        }else{
          $tools = Tools::featured();
        }
       
        return $tools;

    }
}
