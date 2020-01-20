<?php

namespace App\Tasks;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\UserTrainingHistory;
use App\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class StartQuizAccess {

  public static function verify($productId,$questId){ // get the user's subcribed products
        
    $userId = Auth::id();

    $result= UserTrainingHistory::where('user_id', $userId)
    ->where('product_id', $productId)
    ->where('quest_level',$questId)
    ->where('score',0.00)
    ->where('video_watched',1)
    ->count();

    if($result == 1){
        return true;
    }else{
      return false;
    }

  }   


}

   