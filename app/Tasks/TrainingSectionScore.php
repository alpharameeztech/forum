<?php

namespace App\Tasks;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\UserTrainingHistory;
use App\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use App\SubscriptionRepository\UserSubscriptions;
use App\Question;
use App\Repository\Questions\Answers;
use App\QuizRepository\QuizRecord;
use App\QuizRepository\Level;

class TrainingSectionScore {

   public static function evaluate($product_id,$quiz_id,$question_ids,$answers){ //
    // public static function evaluate(){ //

    $question_ids = json_decode($question_ids);

    $questions =[];

    foreach ($question_ids as $key => $value) {
        
        $questions[] = Question::where('id',$value)->get()->first();
    }

    $correctAnswers =   Answers::get($questions);

    //$answers = str_replace('"','\'', $answers);
    
    //dd(trim($answers, '"'));

    $submittedAnswers =  json_decode($answers);

    

    $matchedQuestions = 0;

    $unMatchedQuestions = 0;

    foreach ($correctAnswers as $key => $value) {
       
        if ($value == $submittedAnswers[$key]) {
            $matchedQuestions++;
        } else {
            $unMatchedQuestions++;
        }
    }

    $percentageScore = number_format(($matchedQuestions / count($correctAnswers)) * 100, 0);

    $quiz_level = Level::get($quiz_id); // 1.1, 1.2 etc

    

    if($percentageScore >= 60){
       
        //save the data to the database
        QuizRecord::save($product_id,$quiz_level,$percentageScore);

        return "true";

    }else{
        
        return "false";
    }

  

   }

}



