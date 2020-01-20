<?php

namespace App\Http\Middleware;

use App\Helpers\Helper;
use App\Shop;
use App\Services\BillingService;
use App\Traits\ShopifyTrait;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

class Payment
{
    use ShopifyTrait;

    /**
     * @var BillingService
     */
    protected $billingService;


    public function __construct(BillingService $billingService)
    {
        $this->billingService = $billingService;
    }

    /**
     * Only proceed if billing is enable, and we find a record in billings table
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!env('SHOPIFY_CHARGE')) return $next($request);
        $shop = $request->has('shop') ? $request->get('shop') : ($request->shop != null ? $request->shop : null);

        $shopObj = Shop::where('name', $shop)->first();
        if ($shopObj != null) {
            $freePassCheck = $shopObj->last_checked;
            $affiliate = $shopObj->plan == 'affiliate' && env('SHOPIFY_SUPPORT_AFFILIATES', false);

            if (!$affiliate || env('SHOPIFY_CHARGE')) {
                $this->syncFreePass($shopObj);
                if ($freePassCheck != null && $shopObj->free_pass == false) {
                    return $this->freePassExpired($request);
                }

                if (env('SHOPIFY_CHARGE') && $shopObj->billing != null) {

                    $this->billingService->setShop($shopObj);
                    if ($this->billingService->getStatus() != 'active') {
                        return $this->requestBilling($request, $shopObj);
                    }
                }

                // ----- if user didnt select billing plan ...
                if (env('SHOPIFY_BILLING_PLANS') &&
                    $shopObj->billing == null &&
                    !$affiliate &&
                    !$shopObj->free_pass &&
                    $request->getPathInfo() == '/') {
                    return redirect()->route('shopify-billing-plans', Helper::generateHadesBlock($shopObj));
                }
            }
        }

        return $next($request);
    }

    /**
     * @param Request $request
     * @param Shop $shop
     * @return string
     */
    private function requestBilling(Request $request, Shop $shop)
    {
        $uri = $request->getRequestUri();
        if (starts_with($uri, '/pull')) {
            return response('// App billing not configured.');
        } else {
            return redirect()->route('pages.requestBilling', Helper::generateHadesBlock($shop));
        }
    }

    /**
     * @param Request $request
     * @return string
     */
    private function freePassExpired(Request $request)
    {
        $uri = $request->getRequestUri();
        if (starts_with($uri, '/pull')) {
            return response('// Your free pass expired. Please reinstall the app.');
        } else {
            return redirect()->route('pages.freePassExpired');
        }
    }

    private function syncFreePass(Shop $shop)
    {
        if ($shop->free_pass && $shop->last_checked != null) {
            $now = Carbon::now();
            $last = $shop->last_checked;

            $nowDate = $now->format('Y-m-d');
            $lastCheckDate = $last->format('Y-m-d');

            // ----- only check if a day has passed since last checked
            if ($nowDate != $lastCheckDate) {

                // ----- check if should get free pass
                $response = $shop->shouldGetFreePass();
                $shop->free_pass = $response['active'];
                $shop->last_checked = $now;
                $shop->save();
            }
        }
    }

}