<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\ForumInvite;

class HasToken
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /**
         * Only for GET requests. Otherwise, this middleware will block our registration.
         */
        if ($request->isMethod('get')) {

            $token = '';

            //session('invitation_token', '');
            $request->session()->put('invitation_token', '');
            $request->session()->put('name_associated_with_the_token', '');
            $request->session()->put('alias_associated_with_the_token', '');
            $request->session()->put('email_associated_with_the_token','');


            if ( isset($request->token) ) {

                $token_from_the_url = $request->get('token');

                $data = ForumInvite::where('token', $token_from_the_url)->first();

                /*
                 * if token is valid
                 * then storing the token,
                 * name and email on the session
                 */
                if(!empty($data))
                {
                    $request->session()->put('invitation_token', $token_from_the_url);

                    $request->session()->put('name_associated_with_the_token', $data['name']);

                    $request->session()->put('alias_associated_with_the_token', $data['alias']);

                    $request->session()->put('email_associated_with_the_token', $data['email']);

                }else
                {
                    /**
                     * either the token is already used
                     * or the token is false
                     * then redirect it to login page
                     */
                    return redirect()->route('login');
                }



            }



            /**
             * Lets try to find invitation by its token.
             * If failed -> return to request page with error.
             */




        }
        return $next($request);
    }
}
