<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ForumThreadSubscription extends Model
{

    protected $guarded = [] ;

    public function user(){

        return $this->belongsTo(User::class);

    }

    public function thread(){

       return $this->belongs(ForumThread::class);
    }

}
