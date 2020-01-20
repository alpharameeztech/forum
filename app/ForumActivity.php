<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ForumActivity extends Model
{

    protected $gurarded = [];

     protected $fillable = ['shop_id', 'type','user_id'];

    public function subject(){

        return $this->morphTo();
    }

    public static function feed($user, $take = 50){

        return static::where('user_id',$user->id)
                        ->latest()
                        ->with('subject')
                        ->take($take)
                        ->get()
                        ->groupBy(function ($activity){
                            return $activity->created_at->format('Y-m-d');
                        });
    }
}
