<?php

namespace App\Repository\Questions;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\UserTrainingHistory;
use App\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use App\SubscriptionRepository\UserSubscriptions;
use App\Question;

class Answers {

   public static function get($questions){ // get the user's subcribed products

        foreach ($questions as $question) {
            $correctAnswers[] = $question->answer;
        }
        
        return $correctAnswers;

    }   


}



