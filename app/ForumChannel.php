<?php

namespace App;

use App\Scopes\ShopScope;
use Illuminate\Database\Eloquent\Model;

class ForumChannel extends Model
{
    /**
     * The "booting" method of the model.
     * Global Query scope
     *Adding the constraint of the shop id
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new ShopScope);
    }

    public function getRouteKeyName(){

        return 'slug';
    }

    public function threads(){

        return $this->hasMany(ForumThread::class);

    }
}


