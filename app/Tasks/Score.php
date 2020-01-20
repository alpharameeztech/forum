<?php

namespace App\Tasks;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\UserTrainingHistory;
use App\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use App\SubscriptionRepository\UserSubscriptions;

class Score {

    public static function result($product_id){ 

        
        $user_id = Auth::id();

        $score_array = ['','','','','','','','','','','','','','','',''];

        $score = UserTrainingHistory::where('user_id',$user_id)
                ->where('product_id',$product_id)
                ->where('score', '>=', 60)
                ->get();
        
        foreach ($score as $key => $value) {
                $score_array[$key] = $value->score;
        }
       

        return $score_array;

    }   


}



