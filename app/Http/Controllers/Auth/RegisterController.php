<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Rules\UniqueShopAndEmail;
use App\Services\GeoPlugin;
use App\Traits\TokenBasedRegistration;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{

    protected $shop_id;

    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;
    use TokenBasedRegistration;

    /**
     * Where to redirect users after registration.
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

        $this->shop_id = Cache::get('shop_id');

        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     * @throws ValidationException
     */
    protected function validator(array $data)
    {
        /*A trait method
         */
        $this->verify($data);


        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', new UniqueShopAndEmail],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {


        try {
            //get the registering user country
            $user_country = GeoPlugin::country();
        } catch (Exception $e) {
            \Log::info($e);
            return true;
        }

        $user = User::create([
            'shop_id' => $this->shop_id,
            'name' => $data['name'],
            'slug' => $data['name'] . '-' .  $this->shop_id,
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'country' => $user_country
        ]);

        /*
         * A trait method
         * that will set the user type
         * as provided in the parameter
         * along with shop id
         * and alias
         */
        if( !empty(session('invitation_token')) ) {
            $this->store($user, $this->shop_id ,'publisher', $data['alias']);
        }

        return $user;
    }
}
