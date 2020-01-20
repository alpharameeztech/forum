<?php

namespace App\Tasks;

use App\ForumThemeSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\ForumChannel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ShopTheme
{
    static public function filter($filter)
    {

        $shopId = Cache::get('shop_id'); //this is the current shop id

        $themeSettings = ForumThemeSetting::where('shop_id', $shopId)->first();
        if($themeSettings != null)
        {
            $shopTheme = $themeSettings->theme;

            return $shopTheme->$filter;
        }

    }
}
