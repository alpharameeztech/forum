<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ForumInvite extends Model
{
    use Notifiable;

    protected $fillable = [
        'shop_id' ,'name', 'alias', 'email', 'token'
    ];
}
