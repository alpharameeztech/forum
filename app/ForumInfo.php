<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use Illuminate\Support\Facades\Auth;


class ForumInfo extends Model
{
   
    protected $guarded = [] ;

    public function user(){

        return $this->belongsTo(User::class, 'user_id');
    }
}
