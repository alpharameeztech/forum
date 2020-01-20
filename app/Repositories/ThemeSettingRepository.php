<?php

namespace App\Repositories;

use App\ForumTheme;
use App\ForumThemeSetting;
use App\Interfaces\themesettingRepositoryInterface;
use App\Services\ShopPlanFeatures;
use App\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class ThemeSettingRepository implements themesettingRepositoryInterface
{
    protected $shop_id;

    protected $shopPlanFeatures;

    public function __construct()
    {
        $this->shop_id = Cache::get('shop_id');

        $this->shopPlanFeatures = ShopPlanFeatures::get();
    }

    /**
     * Get's a theme Setting of current store
     *
     * @param int
     * @return collection
     */
    public function get()
    {

        return ForumThemeSetting::where('shop_id', $this->shop_id)->with('theme')->first() ? : '' ;

    }

    /**
     * Get's all themesettings.
     *
     * @return mixed
     */
    public function all()
    {

    }


    /**
     * Updates a themesetting.
     *
     * @param $id
     * @param $name
     * @param $pasword
     * @param $email
     * @param $ban
     */
    public function update($request)
    {

        $themeSetting =  Forumthemesetting::where('shop_id', $this->shop_id)->first();

        if (!empty($request->themeId)) {

            $themeSetting->forum_theme_id = $request->themeId;
        }

        /*
         * check whether the admin can
         * place custom css
         * with the current plan
         * not css code not empty
         */
        if ( ($this->shopPlanFeatures->custom_css != 0) && (!empty($request->css_code)) ) {

            $themeSetting->css_code = trim($request->css_code);
        }

        /*
         * check whether the admin can
         * place custom js
         * with the current plan
         * & js code not empty
         */
        if ( ($this->shopPlanFeatures->custom_js != 0) && ( !empty($request->js_code)) ) {

            $themeSetting->js_code = $request->js_code;
        }

        $themeSetting->save();
    }


    /**
     * Store a user
     * @param $user_data
     */
    public function store($request){

        $themeSetting = new ForumThemeSetting;

        $themeSetting->shop_id = $request->id;

        $theme = new ForumTheme();

        $default_theme= $theme->default();

        $themeSetting->forum_theme_id = $default_theme->id;

        $themeSetting->save();

    }
}
