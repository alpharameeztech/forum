<?php

namespace App\Tasks;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\UserTrainingHistory;
use App\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use App\SubscriptionRepository\UserSubscriptions;
use App\QuizRating;

class UserQuizRating {

  
    public static function get($quiz_id){
        
        $user_id = Auth::id();

        $rating = QuizRating::where('user_id',$user_id)
                    ->where('quiz_id',$quiz_id)
                    ->pluck('rating')
                    ->first();

        if($rating == null){

            return '0';
        }else{
            return $rating;
        }
    }


}



