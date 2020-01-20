<?php

namespace App\Traits;

use App\ForumActivity;
use App\ForumInvite;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * Trait TokenBasedRegistration
 * @package App\Traits
 *
 * this trait have a method store
 * which sets the user type to the provided one
 * once the user is being registered with the provided token
 */
trait TokenBasedRegistration
{


    // this method is called automatically gets called whereve we use it i.e :use TokenBasedRegistration;
    protected static function bootTokenBasedRegistration(){

        //$this->invitation_token = session('invitation_token');

    }

    /*A trait method
         * that validates
         * that email provided
         * has not be changed
         * from the inspect tool
         * and the same email provided
         * with associated token
         */
    public function verify($data)
    {

        if(!empty(session('invitation_token'))){

            $email_associated_with_the_token = ForumInvite::where('token', session('invitation_token') )->pluck('email')->first();

            if($email_associated_with_the_token != $data['email']){
                    abort(403, 'You are not suppose to change the email');
            }
        }

    }
    public function store($user, $shop_id, $userType = 'member', $alias){



        if($user && !empty(session('invitation_token'))){

            /*
             * this invitation token is the token
             * send by the  admin
             * to the publisher account type
             * to manage admin portion as
             * a publisher
             */
            $userInvitation = ForumInvite::where('email',  $user->email)->first();
            $userInvitation->used_at = Carbon::now();
            $userInvitation->save();

            /*
             * store that user which registers with token provided
             * as 'publisher'
             */
            $user = User::find($user->id);
            $user->alias = $alias;
            $user->type = $userType;
            $user->shop_id = $shop_id;
            $user->save();

        }
    }


}
