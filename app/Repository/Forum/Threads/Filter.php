<?php

namespace App\Repository\Forum\Threads;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\UserTrainingHistory;
use App\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use App\SubscriptionRepository\UserSubscriptions;
use App\Question;
use App\User;

class Filter
{
    public static function apply($threads)
    { 
       
        //if request('by', we should filter by the given username)
       if($username = request('by')){

            $user = User::where('name',$username)->firstOrFail();

            $threads->where('user_id',$user->id);
        }

        if($popular = request('popular')){

            $threads->getQuery()->orders = []; // not by latest

            $threads = $threads->withCount('replies')->orderBy('replies_count','desc');
          
        }

        if($fresh = request('fresh')){

           
            $threads->getQuery()->orders = []; // not by latest

            $threads = $threads->where('replies_count',0)->latest();
          
        }

        // $threads = $threads->paginate(5);
        $threads = $threads->get();

        return $threads;
    }
}
