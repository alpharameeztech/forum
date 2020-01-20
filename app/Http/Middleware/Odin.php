<?php

namespace App\Http\Middleware;

use App\Helpers\Helper;
use App\Traits\ShopifyTrait;
use Closure;
use Illuminate\Http\Request;

class Odin
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
        if ($this->verifyWebHook($request) ||
            $this->verifyRequest($request) ||
            $this->verifyRequest($request, true) ||
            $request->has(Helper::hadesHeader())
        ){
            if ($request->hasHeader('X-Shopify-Shop-Domain')) {
                $request->request->set('shop', $request->header('X-Shopify-Shop-Domain'));
            }
            return $next($request);
        } else {
            return response(view('shopify.welcome'));
        }
    }

}