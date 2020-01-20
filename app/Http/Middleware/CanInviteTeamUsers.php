<?php

namespace App\Http\Middleware;

use App\Shop;
use Closure;
use Illuminate\Support\Facades\Cache;

class CanInviteTeamUsers
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (Cache::has('shop_id')) {

            $shop = Shop::find(Cache::get('shop_id'));
            $shop_max_team_member = $shop->associatedPlan->features->team_users;
            $shop_unlimited_max_team_member = $shop->associatedPlan->features->unlimited_team_users;

            $total_invites_send_to_users_of_a_shop = $shop->teamUserInvites->count();

            //if the user has reached the limit
            //as per the plan of sending team user email invitations
            //to join, then dont send invitation
            if( ($shop_unlimited_max_team_member == 1 ) || ($total_invites_send_to_users_of_a_shop < $shop_max_team_member) ){
                return $next($request);
            }


            $message = "You have reached your plan's limit. Please upgrade your plan.";
            return $message;
            //return redirect()->route('team.users')->withMessage($message);

        }else{
            return redirect()->route('team.users');
        }

    }
}
