<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\PlanFeature;

class Plan extends Model
{

    public function features()
    {
        return $this->hasOne(PlanFeature::class);
    }

    public function associatedShops()
    {
        return $this->hasMany(Shop::class);
    }
}
