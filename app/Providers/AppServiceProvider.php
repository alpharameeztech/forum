<?php

namespace App\Providers;

use App\Tasks\ShopTheme;
use App\Tasks\ShopThemeSettings;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use App\Repository\Channels\All as Channels;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        //app()->singleton('shop_id', 13);
//        $this->app->singleton('shop_id', function (){
//            return 4; // this should be the current store id
//        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $environment = app()->environment();
        //dd(Cache::get('shop_id'));
        if ( !(Cache::has('shop_id')) && $environment == 'local' ) {
            Cache::put('shop_id', 4, 600);
        }
        //Cache::put('shop_id', 3, 600);

        //dd(Cache::get('shop_id'));

        // make forumChannels available with every view
        view()->share('channels', Channels::get());

        //make shop theme css class available to all view
        view()->share('themeCssClass', ShopTheme::filter('css_class'));

        //find the css code from the theme custom setting
        view()->share('themeCssCode', ShopThemeSettings::filter('css_code'));

        //find the js code from the theme custom setting
        view()->share('themeJsCode', ShopThemeSettings::filter('js_code'));

    }
}
