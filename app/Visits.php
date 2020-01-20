<?php

namespace App;
use Illuminate\Support\Facades\Redis;

class Visits
{
    protected $thread;

    public function __construct($thread)
    {

        $this->thread = $thread;

    }

    public function record(){

        Redis::incr($this->cacheKey());

        /*
         * Saving the visits count on the DB tabletoo
         */
        $this->thread->visits_count = ++$this->thread->visits_count;
        $this->thread->save();

        return $this;
    }

    public function count(){

        return Redis::get($this->cacheKey()) ?? 0 ;
    }

    public function reset(){

        Redis::del($this->cacheKey());

        return $this;

    }

    protected function cacheKey(){

        return config('app.name') . "threads. {$this->thread->id}.visits";
    }
}
