<?php

namespace App\Http\Middleware;

use App\Helpers\Helper;
use App\Traits\ShopifyTrait;
use Closure;
use Illuminate\Http\Request;

class Hades
{

    use ShopifyTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $skippedPaths = [
        ];
        $hades = [];
        if ($request->hasCookie(Helper::hadesHeader())) {
            $hades = Helper::decodeHadesCookie($request);
        } elseif ($request->has(Helper::hadesHeader())) {
            $hades = Helper::decodeHadesQuery($request);
        } elseif ($request->hasHeader(Helper::hadesHeader())) {
            $hades = Helper::decodeHadesHeader($request);
        }

        foreach ($hades as $key => $val) {
            $request->request->set($key, $val);
        }

        //Set shop to the search shop from CSUI
        $targetCookie = Helper::fbCookieName($request->request->get('shop'));

        if ($request->hasCookie($targetCookie) && !in_array($request->getPathInfo(), $skippedPaths)) {
            $fbAccessBlock = decrypt($request->cookie($targetCookie));

            if ($request->request->get('shop') == $fbAccessBlock['shop']) {
                $fbAccessBlock['remember'] = true;
                $request->request->add(['facebook-access' => $fbAccessBlock]);
            }
        }
        return $next($request);
    }

}
