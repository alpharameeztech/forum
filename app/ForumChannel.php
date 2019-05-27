<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ForumChannel extends Model
{
    public function getRouteKeyName(){

        return 'slug';
    }

    public function threads(){

        return $this->hasMany(ForumThread::class);

    }
}


