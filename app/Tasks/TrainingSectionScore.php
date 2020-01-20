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
use App\Option;

// use function GuzzleHttp\json_decode;

class TrainingSectionScore
{
    public static function evaluate($product_id, $quiz_id, $question_ids, $answers)
    {
        $refined_submitted_answers = array_filter(json_decode($answers));
    
        $question_ids = json_decode($question_ids);
    
        $questions =[];
    
        foreach ($question_ids as $key => $value) {
            $questions[] = Question::where('id', $value)->get()->first();
        }
    
        $correctAnswers =   Answers::get($questions);
    
        $submittedAnswers =  json_decode($answers);
    
        $matchedQuestions = 0;
    
        $unMatchedQuestions = 0;
    
        foreach ($correctAnswers as $key => $value) {
            if ($value == $refined_submitted_answers[$key]) {
                $matchedQuestions++;
            } else {
                $unMatchedQuestions++;
            }
        }
    
        $percentageScore = number_format(($matchedQuestions / count($correctAnswers)) * 100, 0);
    
        $quiz_level = Level::get($quiz_id); // 1.1, 1.2 etc
    
        if ($percentageScore >= 60) {
           
            //save the data to the database
            QuizRecord::save($product_id, $quiz_level, $percentageScore);
    
            return "true";
        } else {
            return "false";
        }
    }


    public static function store()
    {
        $submittedQuestionsAnswers = request()->fields;

        $matchedQuestions = 0;

        $unMatchedQuestions = 0;
    
        $submittedQuestions = array_keys(request()->fields);
        
        $questions = [];

        $wrongAnswersSubmitted = [];

        $correctAnswersWere = [];
    

        foreach ($submittedQuestions as $key => $value) {
            $questions[] = Question::where('id', $value)->get()->first();
        }

        $correctAnswers =   Answers::get($questions);

        foreach ($correctAnswers as $key => $value) {
            $submittedAnswerRaw = $submittedQuestionsAnswers[$key];
    
            $submittedAnswer = explode('_', $submittedAnswerRaw);

            if ($value == $submittedAnswer[1]) {
                $matchedQuestions++;
            } else {

                $wrongAnswersSubmitted[$key] = $value;
        
                $correctAnswerWere = Option::where('question_id', $key)
                                    ->where('value', $value)->pluck('option')->first();

                $correctQuestionWere = Question::find($key);

                $correctQuestionWere = $correctQuestionWere->text;

                // this will create an array like that
                // 'Research has shown that people who exercise ' => ' Are healthier than those who dont',
                //'What is aerobic exercise?' => ' A form of cardio that stimulates heart to pump blood effectively',
                $correctAnswersWere[$correctQuestionWere] = $correctAnswerWere;
                
                $unMatchedQuestions++;
            }
        }

        $percentageScore = number_format(($matchedQuestions / count($correctAnswers)) * 100, 0);

        $quiz_level = Level::get(request()->quiz); // 1.1, 1.2 etc

        if ($percentageScore >= 60) {
   
        //save the data to the database
            QuizRecord::save(request()->product, $quiz_level, $percentageScore);

            return 'true';
        } else {
            return $correctAnswersWere;
        }

        return response()->json(null, 200);
    }
}
