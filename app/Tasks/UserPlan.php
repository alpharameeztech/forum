<?php

namespace App\Tasks;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\SubscriptionRepository\UserSubscriptions;
use App\Plan;

class UserPlan
{
    private $subscription;

    public function __construct()
    {
      
    }

    /**
     * This will return the user subscription plan
     * which is added on the admin
     * & which the user is subscribed to
     */ 
    public static function get()
    {
        
        $userSubscription =  Auth::user()->subscriptions;

        //if the $userSubscription is empty
        //that means user is under free trial
        //return either user subscription plan or empty string
        return count($userSubscription) ? Plan::where('plan_id', $userSubscription[0]['stripe_plan'])->first() : '';
        

    }
}
