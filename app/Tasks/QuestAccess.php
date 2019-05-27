<?php

namespace App\Tasks;

use App\UserTrainingHistory;
use Illuminate\Support\Facades\Auth;
use App\QuizRepository\Level;

class QuestAccess
{

    public static function verify($productId, $questId)
    { // get the user's subcribed products

        $userId = Auth::id();

        $user_current_quest_level = UserTrainingHistory::where('user_id', $userId)
            ->where('product_id', $productId)
            ->where('score', '>=', 60)
            ->max('quest_level');

        $user_current_quest_level_explode = explode('.', $user_current_quest_level);

        if ($user_current_quest_level_explode[0] == '' && $questId == 1.1) {
            return true;
        } elseif ($user_current_quest_level_explode[0] == '' && $questId != 1.1) {
            return false;
        } else {

            switch ($user_current_quest_level_explode[1]) {
                case 4:
                    $user_current_quest_level += 0.7;
                    if ("$user_current_quest_level" >= $questId) {

                        return true;
                    } else {
                        return false;
                    }
                    break;

                case 1:
                case 2:
                case 3:

                    $user_current_quest_level += 0.1;
                    if ("$user_current_quest_level" >= $questId) {
                        return true;
                    } else {
                        return false;
                    }

                    break;
                default:
                    # code...
                    break;

            }

        }

    }

}
