<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class LoginController extends Controller
{
    protected $shop_id;
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');

        $this->shop_id = Cache::get('shop_id');
    }

    protected function authenticated(Request $request, $user)
    {

        \Log::info('When user authenticated');

        $sameEmailCountOfAuth = User::where('email',$user->email)->count();
        //logout the current user
        //then manually login the user
        //with the same email provided
        //but the one linked with current
        //shop store id
        if($sameEmailCountOfAuth > 1)
        {
            Auth::logout();

            $user = User::where('email', $user->email)
                ->where('shop_id',$this->shop_id)
                ->first();

            return Auth::login($user);

        }
    }
}
