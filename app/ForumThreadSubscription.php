<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\ForumThread;
use App\Notifications\ThreadWasUpdated;

class ForumThreadSubscription extends Model
{

    protected $guarded = [] ;

    public function user(){

        return $this->belongsTo(User::class);

    }

    public function thread(){

       return $this->belongs(ForumThread::class);
    }

    public function notify( ){


        $this->user->notify(new ThreadWasUpdated($this->thread, $reply));


    }
}
