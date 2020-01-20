<?php

namespace App\Tasks;

use App\ForumThemeSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\ForumChannel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class ShopThemeSettings
{
    static public function filter($find)
    {
        $shopId = 13; //this is the current shop id

        $themeSettings = ForumThemeSetting::where('shop_id', $shopId)->first();

        if($themeSettings != null)
        {
            return $themeSettings->$find;
        }

    }
}
