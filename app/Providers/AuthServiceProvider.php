<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        'App\ForumThread' => 'App\Policies\ForumThreadPolicy',
        'App\ForumReply' => 'App\Policies\ForumReplyPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //if the loggedin user is administrator with the following any emails then they are allowed on policy
        Gate::before(function ($user){
            if($user->email === 'rameezisrarcode@gmail.com' || $user->email === 'shahlajalalicoo@gmail.com' || $user->email === 'tariqkhursheedceo@gmail.com'){
                return true;
            }
        });
    }
}
