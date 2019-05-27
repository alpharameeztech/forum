<?php

namespace App\Providers;
use App;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use App\Repository\Channels\All as Channels;
use Barryvdh\Debugbar\ServiceProvider as Barryvdh;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
       
        if (App::environment('production', 'staging'))
        {
            URL::forceScheme('https');
          
        }   

        // make forumChannels available with every view
        view()->share('channels', Channels::get());
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        if($this->app->isLocal()){
            $this->app->register(Barryvdh::class);
        }

    }
}
