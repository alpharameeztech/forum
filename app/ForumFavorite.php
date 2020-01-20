<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\RecordForumActivity;

class ForumFavorite extends Model
{
    use RecordForumActivity;

    protected $guarded = [];

    public function favorited(){

        return $this->morphTo();
    }
    
}
