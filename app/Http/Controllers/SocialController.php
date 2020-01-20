<?php

namespace App\Http\Controllers;

use App\Services\GeoPlugin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Validator,Redirect,Response,File;
use Socialite;
use App\User;

class SocialController extends Controller
{
    protected $shop_id;

    public function __construct()
    {
        $this->shop_id = Cache::get('shop_id');
    }

    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }
    public function callback($provider)
    {

        if(json_encode($provider) == 'facebook'){
            $getInfo = Socialite::driver($provider)->user(); // works for facebook

        }else{

            $getInfo = Socialite::driver($provider)->stateless()->user(); // for google

        }

        $user = $this->createUser($getInfo,$provider);
        auth()->login($user);
        return redirect()->to('/home');
    }
    function createUser($getInfo,$provider){
        //get the registering user country
        $user_country = GeoPlugin::country();

        $user = User::where('provider_id', $getInfo->id)
                        ->where('shop_id', $this->shop_id)->first();
        if (!$user) {
            $user = User::create([
                'shop_id' => $this->shop_id,
                'name'     => $getInfo->name,
                'slug' => $getInfo->name . '-' .  $this->shop_id,
                'email'    => $getInfo->email,
                'password' => '',
                'provider' => $provider,
                'provider_id' => $getInfo->id,
                'avatar' => $getInfo->getAvatar(),
                'country' => $user_country
            ]);
        }
        return $user;
    }
}
