<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Middleware\Hades;
use App\Shop;
use App\Services\BillingService;
use App\Services\IntegrityService;
use App\Tasks\CreateUser;
use App\Traits\ShopifyTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;


class ShopifyController extends Controller
{
    /**
     * @var BillingService
     */
    protected $billing;

    protected $shop_id;

    /**
     * @var IntegrityService
     */
    protected $integrity;

    public function __construct(BillingService $billingService, IntegrityService $integrityService)
    {
        $this->billing = $billingService;
        $this->integrity = $integrityService;
    }

    /**
     * Action method which fires up whenever the root url (/) is accessed
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function access(Request $request)
    {
        $shop = $request->has('shop') ? $request->get('shop') :  null;
        $shopObj = Shop::where('name', $shop)->first();
        Cache::put('shop_id', $shopObj['id'], 1200); //for 60 minutes
        // ----- check if the shop is already set, then use it
        if ($shopObj != null) {
            // ----- loggin in shop admin, and redirect to admin page
            $admin = $shopObj->admin();
            Auth::loginUsingId($admin->id);
            return response()->redirectTo('/admin');
        } elseif ($shop != null) {
            return $this->doAuth($shop);
        }
    }

    /**
     * action for shopify callback URL, to store access token and login the user.
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function authCallback()
    {
        if (isset($_GET['code'])) {
            $shop = $_GET['shop'];
            $code = $_GET['code'];
            $shopInfo = [];

            $shopify = $this->getShopifyObj($shop, '');
            $accessToken = $shopify->getAccessToken($code);

            $shopify->setup(['ACCESS_TOKEN' => $accessToken]);

            try {
                $shopInfo = $shopify->call(['URL' => 'shop.json', 'METHOD' => 'GET']);
            } catch (\Exception $e) {
                echo $e->getMessage();
                exit;
            }

            $shop = $this->integrity->register($shop, $accessToken, $shopInfo->shop);
            $redirect = $this->billing->setShop($shop)->inspect();

            return redirect($redirect);
        }
    }

    public function plans(Request $request)
    {
        $shop = $request->get('shop');
        $shop = $this->repository->findByField('name', $shop)->first();
        return view('plans', array_merge( config('plans'), [
            'hades'     => Helper::generateHades($shop),
            'active'    => $request->get('active', null)
        ]));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function planSelected(Request $request)
    {
        $plan = $request->get('plan');
        $shop = $request->get('shop');
        $shop = $this->repository->findByField('name', $shop)->first();
        $shop->tfx_plan = $plan;
        $plans = config('plans.details');
        $planDetails = $plans[$plan];

        $shop->save();

        $this->billing->setShop($shop);
        $this->billing->cleanup();

        $redirect = ($planDetails['price'] == '0.00') ?
            route('landing-page', Helper::generateHadesBlock($shop)) : $this->billing->enroll($plan);
        return redirect($redirect);
    }

    public function planSwitched(Request $request)
    {
        $plan = $request->get('plan');
        $shop = $request->get('shop');
        $shop = $this->repository->findByField('name', $shop)->first();
        if ($shop->billing != null) {
            $shop->billing->status = 'cancelled';
            $shop->billing->save();
            $shop->billing->delete();
        }
        $plans = config('plans.details');
        $planDetails = $plans[$plan];

        $shop->tfx_plan = $plan;
        $shop->save();

        $this->billing->setShop($shop);
        $this->billing->cleanup();
        $redirect = ($planDetails['price'] == '0.00') ?
            route('landing-page', Helper::generateHadesBlock($shop)) : $this->billing->enroll($plan);
        return redirect($redirect);
    }

    /**
     * helper method to perform shopify auth
     * @param $shop
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function doAuth($shop)
    {
        $shopify = $this->getShopifyObj($shop, '');
        $premissionURL = $shopify->installURL([
            'permissions'   => config('shopify.permissions'),
            'redirect'      => route('shopify.auth-callback')
        ]);

        return redirect($premissionURL);
        //return view('shopify.escapeIFrame', ['installUrl' => $premissionURL]);
    }

    public function chargeCallback(Request $request)
    {
        if ($request->has('charge_id')) {
            return $this->billing->actUponDecision($request);
        } else {
            return '';
        }
    }

    public function dev()
    {
        return view('shopify.spa',
            [
                'shop' => 'asdasd',
                'api_key' => env('SHOPIFY_API_KEY')
            ]);
    }

    /**
     * AUTH done, init SPA ...
     * @param Shop $shop
     * @param null $fb
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function initSPA(Shop $shop, $fb = null)
    {
        $shopify = $this->getShopifyObj($shop);
        $shopInfo = $shopify->call(['URL' => 'shop.json', 'METHOD' => 'GET']);
        $selectedPlan = config('plans.details')[$shop->tfx_plan];
        $unitOfWork = [
            'hades'     => [
                'header'        => Helper::hadesHeader(),
                'block'         => Helper::generateHades($shop, ['facebook-access' => $fb])
            ],
            'shop'      => [
                'id'            => $shop->id,
                'domain'        => $shop->name,
                'email'         => $shop->email,
                'name'          => $shop->shop_name,
                'owner'         => $shop->owner,
                'currency'      => $shopInfo->shop->currency,
                'plan'          => $shop->plan,
                'lead'          => $shop->lead != null ? 'true' : 'false',
                'created_at'    => $shop->created_at,
                'shp_created_at'=> $shopInfo->shop->created_at
            ],
            'now'           => Carbon::now(),
            'onboarding'    => [
                'pixel'         => $shop->pixels != null && $shop->pixels->count() > 0 ? 'true' : 'false',
                'feed'          => $shop->catalog_settings != null && $shop->catalog_settings->count() > 0 ? 'true' : 'false'
            ],
            'timezone'  => [
                'format'        => $shopInfo->shop->timezone,
                'iana'          => $shopInfo->shop->iana_timezone,
            ],
            'fb'        => [
                'avatar'        => $fb != null ? $fb['avatar'] : null,
                'gotAccess'     => $fb != null ? 'true' : 'false',
                'token'         => $fb != null ? $fb['token'] : null,
                'remember'      => isset($fb['remember']) ? 'true' : 'false',
                'user'          => isset($fb['user']) ? $fb['user'] : null
            ],
            'plan'      => [
                'name'          => $shop->tfx_plan,
                'price'         => $selectedPlan['price'],
                'optin'         => $selectedPlan['optin'] == true ? 'true' : 'false',
                'limits'        => $selectedPlan['limits'],
                'ui'            => $selectedPlan['ui'],
                'modules'       => array_key_exists('modules', $selectedPlan) ? $selectedPlan['modules'] : []
            ],
            'csui'          => ($shop->name == env('CSUI_SHOP')) ? 1 : 0,
            'proxy'         => Helper::getProxyURL($shop->name)
        ];
        return view('shopify.spa', $unitOfWork);
    }


    public function freePass(Request $request)
    {
        return view('free-pass', [
            'mode' => $request->get('mode', 'free')
        ]);
    }

    public function affiliate()
    {
        return view('affiliate');
    }

    public function freePassExpired(Request $request)
    {
        return view('free-pass-expired');
    }

    /**
     * Show user the billing status and present with a appropriate action
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function requestBilling(Request $request)
    {
        $requestBillingData = $this->integrity->acceptBillingStatus($request);
        return view('accept-billing', $requestBillingData);
    }

    /**
     * flow for action when user declines payment, and clicks on "setup payment" on next page
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function restartBilling(Request $request)
    {
        $shop = $request->has('shop') ? $request->get('shop') :
            ($request->has('shop') ? $request->get('shop') : null);
        $shop = $this->repository->findByField('name', $shop)->first();
        $shop->billing->forceDelete();

        $this->billing->setShop($shop);
        if (env('SHOPIFY_BILLING_PLANS')) return redirect(route('shopify-billing-plans', Helper::generateHadesBlock($shop)));
        return redirect($this->billing->enroll());
    }

    public function contact(Request $request)
    {
        $shop = $request->has('shop') ? $request->get('shop') :
            ($request->has('shop') ? $request->get('shop') : null);
        $shop = $this->repository->findByField('name', $shop)->first();
        return view('contact', ['shop' => $shop]);
    }

    public function uninstall(Request $request)
    {
        $shop = $request->input('myshopify_domain');
        return $this->integrity->uninstall($shop);
    }

    public function proxy(Request $request)
    {
        /*
         * get the shop id from
         * the shop name
         * and put it to cache
         */
        $shopName = $request->shop;
        $shop = Shop::where('name', $shopName)->first();
        $this->shop_id = $shop->id;
        Cache::put('shop_id', $this->shop_id, 1200); // 60 minutes

        //Cache::forget('shop_id');
//        if (Cache::has('shop_id')) {
//            Cache::forget('shop_id');
//            Cache::put('shop_id', $this->shop_id, 5);
//        }else
//        {
            //Cache::put('shop_id', $this->shop_id, 600); // 10 minutes
     //   }


        $liquid =
            '<iframe'.PHP_EOL.
                        'width="100%"'.PHP_EOL.
//                        'height="800px"'.PHP_EOL.
                        'src="'.route('forum.home').'"'.PHP_EOL.
                        'allowtransparency="true"'.PHP_EOL.
                        'frameborder="0"'.PHP_EOL.
                        'scrolling="yes"'.PHP_EOL.
                        'id="forumFrame"'.PHP_EOL.
                        'onload="resizeIframe(this)"'.PHP_EOL.
//                        'onload="this.height=\'-webkit-fill-available \'"'.PHP_EOL.
//                        'onload="this.height=1000"'.PHP_EOL.
                        'style="height:-webkit-fill-available;"'.PHP_EOL.

            '></iframe>'.PHP_EOL;

        return response($liquid, 200, [
            'Content-Type'      => 'application/liquid'
        ]);
    }

}
