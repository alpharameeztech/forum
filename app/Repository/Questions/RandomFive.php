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

class RandomFive {

   public static function get($questId){ // get the user's subcribed products

        $questions = [];

        $result = Question::where('quiz_id', $questId)
                ->get(); //here the return value is actually the level i.e 1.2,1.3 of the quiz id from the quiz table
        $questions = $result->random(5); // random questions

        return $questions;

    }   


}



