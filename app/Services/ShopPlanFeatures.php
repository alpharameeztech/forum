<?php

namespace App\Services;

use App\Shop;
use Illuminate\Support\Facades\Cache;

class ShopPlanFeatures
{
    /**
     * Service that will return the
     * current shop associated plan's feature
     */
    public static function get()
    {

        $shop = Shop::find( Cache::get('shop_id') );

        return $shop->associatedPlan->features;
    }

}
