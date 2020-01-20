<?php

namespace App\Tasks;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\UserTrainingHistory;
use App\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use App\SubscriptionRepository\UserSubscriptions;

class Progress {
    
    public static function result($product_id){ 

        $user_id = Auth::id();

        $quest_array = ['1.1','1.2','1.3','1.4','2.1','2.2','2.3','2.4','3.1','3.2','3.3','3.4','4.1','4.2','4.3','4.4'];
        
        $int_array = ['1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16'];
        
        $index;
        
        $quest_level= UserTrainingHistory::where('user_id', $user_id)
           ->where('product_id', $product_id)
           ->where('score','>=', 60 )
           ->max('quest_level');
           if(is_null($quest_level)){
              return null;
           }
           else{
                foreach ($quest_array as $key => $value) {
                  if($quest_level == $value){
                    $val= $int_array[$key];
                }
              }   


           $progress =  number_format(($val / 16) * 100,0);   
         
           return $progress;
         
        }
       
    }     

}



