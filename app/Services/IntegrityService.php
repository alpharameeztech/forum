<?php
/**
 * Created by PhpStorm.
 * User: Bilal
 * Date: 7/9/2018
 * Time: 10:52 AM
 */

namespace App\Services;


use App\Helpers\Helper;
use App\Interfaces\ThemeSettingRepositoryInterface;
use App\Interfaces\AppearanceRepositoryInterface;
use App\Shop;
use App\Tasks\CreateUser;
use App\Traits\ShopifyTrait;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use stdClass;


class IntegrityService
{
    use ShopifyTrait;


    protected $themeSettingRepository;
    protected $appearanceRepository;
    /**
     * @var BillingService
     */
    protected $billingService;

    protected $activeTheme;

    protected $themeName;

    protected $appSignature;

    /**
     * @var WebhookService
     */
    private $webhookService;

    /**
     * IntegrityService constructor.
     * @param BillingService $billingService
     */
    public function __construct(
        BillingService $billingService,
        WebhookService $webhookService,
        ThemeSettingRepositoryInterface $themeSettingRepository,
        AppearanceRepositoryInterface $appearanceRepository
    )
    {
        $this->billingService = $billingService;
        $this->webhookService = $webhookService;
        $this->appSignature = '{% include "'.env('APP_NAME').'" %}';
        $this->themeSettingRepository = $themeSettingRepository;
        $this->appearanceRepository = $appearanceRepository;
    }

    /**
     * @param $shop
     * @param $accessToken
     * @param stdClass $shopInfo
     * @return mixed
     * @throws \Exception
     */
    public function register($shop, $accessToken, stdClass $shopInfo)
    {
        // ----- delete if already exists
        $shopObj = Shop::where('name', $shop);
        if ($shopObj->count() > 0) {
            $shopObj = $shopObj->first();
            $shopObj->delete();
        }

        /* @var Shop $newShop */
        $newShop = Shop::create([
            'name'          => $shop,
            'access_token'  => $accessToken,
            'shop_name'     => $shopInfo->name,
            'email'         => $shopInfo->email,
            'owner'         => $shopInfo->shop_owner,
            'plan'          => $shopInfo->plan_name,
            'tfx_plan'      => 'business',
        ]);

        $newShop->save();
        $this->seedDefaults($newShop);
        $this->webhookService->injectHooks($newShop);
        return $newShop;
    }

    /**
     * Check if the integrity is in place
     * @param Request $request
     * @return array|bool
     */
    public function check(Request $request)
    {
        $shop = Shop::where('name', $request->get('shop'))->first();
        $resp = [
            'plan'      => '',
            'theme'     => null,
            'snippet'   => null,
            'checkout'  => null
        ];

        if($shop == null) return $resp;

        try {
            $this->getActiveTheme($shop , $request);

            if($request->hasHeader('CSUI-SHOP')){
                $shopJson = $this->csuiService->getShopJson($shop);
                $resp['theme_name'] = $this->themeName;
                $resp['tfx_plan'] = $shop->tfx_plan;
                $resp['plan'] = $shopJson->plan_name;
                $resp['shop_created_date'] = $shopJson->created_at;
                $resp['TFX_install_date'] = $shop->created_at->toCookieString();
                $this->csuiService->syncPlan($shop, $shopJson);
            }
            $theme = $this->getActiveThemeFile($shop);
            $themeFile = $theme != null && isset($theme->asset) ? $theme->asset->value : null;
            $resp['theme'] = $themeFile != null && strpos($themeFile, $this->appSignature) != false;

            // ----- check if app snippet liquid exists && is updated
            $hookSnippet = $this->getActiveThemeFile($shop, env('APP_NAME').'.liquid', 'snippets');
            $resp['snippet'] = $hookSnippet != null;
            if ($hookSnippet != null) {
                $snippetFileTime = Carbon::createFromTimestamp(Storage::disk('local')
                    ->lastModified('hook/'.env('APP_NAME').'.html'));
                $hookSnippetTime = Carbon::parse($hookSnippet->asset->updated_at);
                $hookSnippetTime->setTimezone('UTC');
                if ($hookSnippetTime->lessThan($snippetFileTime)) $resp['snippet'] = false;
            }

            if ($shop->plan == 'shopify_plus') {
                $checkoutAsset = $this->getActiveThemeFile($shop, 'checkout.liquid');
                $checkoutFile = $checkoutAsset != null && isset($checkoutAsset->asset) ? $checkoutAsset->asset->value : null;
                $resp['checkout'] = $checkoutAsset == null ? null : ($checkoutFile != null && strpos($checkoutFile, $this->appSignature) != false);
            }

            return $resp;
        } catch (\Exception $e) {
            return $resp;
        }
    }

    /**
     * inject app hook into active theme
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function fix(Request $request)
    {
        $shop = Shop::where('name', $request->get('shop'))->first();
        $resp = [
            'plan'      => ($shop != null) ? $shop->plan : '',
            'theme'     => $request->get('theme', false),
            'checkout'  => $request->get('checkout', false),
            'snippet'   => $request->get('snippet', false)
        ];

        if($shop == null) return $resp;

        $this->getActiveTheme($shop, $request);
        if($request->hasHeader('CSUI-SHOP')){
            $resp['theme_name'] = $this->themeName;
            $resp['tfx_plan'] = $shop->tfx_plan;
        }
        try {
            if ($request->get('snippet', false) == false) $resp['snippet'] = $this->injectSnippet($shop);
            if ($request->get('theme', false) == false) $resp['theme'] = $this->patchThemeFile($shop);

            if ($shop->plan == 'shopify_plus') {
                $resp['checkout'] = $this->patchThemeFile($shop, 'checkout.liquid');
            }
            return $resp;

        } catch (\Exception $e) {
            return $resp;
        }
    }

    /**
     * @param $shop
     * @param Request $request
     * @throws \Exception
     */
    private function getActiveTheme($shop, Request $request = null)
    {
        $shopify = $this->getShopifyObj($shop);
        $csui = ($request != null && $request->hasHeader('CSUI-SHOP')) ? true : false;
        if ($shop->theme_id == null || $csui) {
            $resp = $shopify->call(['URL' => '/admin/themes.json', 'METHOD' => 'GET']);
            foreach($resp->themes as $key => $theme){
                if($theme->role == "main"){
                    $shop->theme_id = $theme->id;
                    $this->activeTheme = $theme->id;
                    $this->themeName = $theme->name;
                    if(!$csui) $shop->save();
                    break;
                }
            }
        } else {
            $this->activeTheme = $shop->theme_id;
        }
    }

    /**
     * get active theme's main template file
     * @param $shop
     * @param string $file
     * @param string $directory
     * @return null
     * @throws \Exception
     */
    private function getActiveThemeFile($shop, $file = 'theme.liquid', $directory = 'layout')
    {
        try {
            $shopify = $this->getShopifyObj($shop);
            $themeFile = null;

            if($this->activeTheme != null) {
                $themeFile = $shopify->call([
                    'URL' => '/admin/themes/' . $this->activeTheme . '/assets.json?asset[key]='.$directory.'/'.$file,
                    'METHOD' => 'GET'
                ]);
            }
            return $themeFile;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function patchThemeFile($shop, $file = 'theme.liquid')
    {
        // ----- inject hook placeholder
        $themeFile = $this->getActiveThemeFile($shop, $file);
        if ($themeFile != null && strpos($themeFile->asset->value, $this->appSignature) == true) return true;

        if ($themeFile == null) return false;

        $gotTitle = strpos($themeFile->asset->value, '</title>') != false;
        $injection = PHP_EOL.'  '.$this->appSignature.PHP_EOL;
        if ($gotTitle) {
            $content = str_replace('</title>', '</title>'.$injection, $themeFile->asset->value);
        } else {
            $content = str_replace('</head>', $injection.'</head>', $themeFile->asset->value);
        }

        $shopify = $this->getShopifyObj($shop);
        $resp = $shopify->call([
            'METHOD'    => 'PUT',
            'URL'       => '/admin/themes/' . $this->activeTheme . '/assets.json',
            'DATA'      => [
                'asset' => [
                    'key'   => 'layout/'.$file,
                    'value' => $content,
                ]
            ]
        ]);
        return true;
    }

    /**
     * clean the active theme saved in shop table
     * @param Shop $shop
     * @throws \Exception
     */
    private function cleanTheme(Shop $shop)
    {
        $shopify = $this->getShopifyObj($shop);
        $this->cleanThemeFile($shop);

        // ----- clean checkout.liquid
        if ($shop->plan == 'shopify_plus') {
            $this->cleanThemeFile($shop, 'checkout.liquid');
        }

        // ----- send a delete call for the hook asset itself
        $hookFile = $shopify->call([
            'URL' => '/admin/themes/' . $this->activeTheme . '/assets.json?asset[key]=snippets/'.env('APP_NAME').'.liquid',
            'METHOD' => 'DELETE'
        ]);

        // ----- send a delete call for the hook asset itself
        $assetFile = $shopify->call([
            'URL' => '/admin/themes/' . $this->activeTheme . '/assets.json?asset[key]=assets/'.env('APP_NAME').'.js',
            'METHOD' => 'DELETE'
        ]);
    }

    private function cleanThemeFile($shop, $file = 'theme.liquid')
    {
        try {
            $theme = $this->getActiveThemeFile($shop, $file);
            $themeFile = isset($theme->asset) ? $theme->asset->value : null;
            $gotHook = $themeFile == null ? false :
                (strpos($themeFile, $this->appSignature) == false ? false : true);
            $shopify = $this->getShopifyObj($shop);

            // ---- remove hook placeholder
            if ($gotHook) {
                $content = str_replace(
                    $this->appSignature,
                    '',
                    $theme->asset->value
                );

                $resp = $shopify->call([
                    'METHOD'    => 'PUT',
                    'URL'       => '/admin/themes/' . $this->activeTheme . '/assets.json',
                    'DATA'      => [
                        'asset' => [
                            'key'   => 'layout/'.$file,
                            'value' => $content,
                        ]
                    ]
                ]);
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }

    }

    private function seedDefaults(Shop $shop)
    {

        // create a user
        CreateUser::execute(
            $shop->owner,
            $shop->email,
            'admin',
            $shop->id
        );

        //set the default forum theme setting
        $this->themeSettingRepository->store($shop);

        //set the default forum appearance
        $this->appearanceRepository->store($shop);
    }

    public function uninstall($shop)
    {
        try {
            $shop = Shop::where('name', $shop)->first();

            if ($shop->billing != null) {
                $this->billingService->setShop($shop)->optout();
            }
            $shop->delete();

            return response([
                'status' => true
            ], 200);
        } catch (\Exception $e) {
            return response([
                'status' => false
            ], 200);
        }
    }

    public function eraseCustomers(Request $request)
    {
        return response('', 200);
    }

    public function eraseShop(Request $request)
    {
        return response('', 200);
    }

    public function acceptBillingStatus(Request $request)
    {
        $shop = Shop::where('name', $request->get('shop'))->first();

        $data = [
            'status'   => 'pending',
            'link'      => '',
            'apps'      => $shop != null ? 'https://'.$shop->name.'/admin/apps' : ''
        ];

        if ($shop == null || $shop->billing == null) return $data;

        if ($shop->billing->status == 'pending')
        {
            $shopify = $this->getShopifyObj($shop);
            $charge = $shopify->call(['URL' => 'admin/recurring_application_charges/'.$shop->billing->shopify_billing_id.'.json', 'METHOD' => 'GET']);
            $chargeDetails = $charge->recurring_application_charge;

            if ($chargeDetails->status == 'declined' || $chargeDetails->status == 'expired') {
                $data['status'] = $shop->billing->status = $chargeDetails->status;
                $data['link'] = route('pages.restartBilling', Helper::generateHadesBlock($shop));
                $shop->billing->save();
                return $data;
            }

            $chargeDetails = $charge->recurring_application_charge;
            $data['link'] = $chargeDetails->confirmation_url;
        } else {
            $data['status'] = $shop->billing->status;
            $data['link'] = route('pages.restartBilling', Helper::generateHadesBlock($shop));
        }
        return $data;
    }

    public function cleanUninstall(Request $request, $revokeApi = true)
    {
        $shop = $request->get('shop');
        $shop = Shop::where('name', $shop)->first();

        try {
            $this->getActiveTheme($shop, $request);
            $this->cleanTheme($shop);
            // ----- revoke API Access
            if ($revokeApi) {
                $client = new Client(['base_uri' => 'https://'.$shop->name.'/']);
                $response = $client->delete('admin/api_permissions/current.json', [
                    'headers' => [
                        'Content-Type'              => 'application/json',
                        'Accept'                    => 'application/json',
                        'Content-Length'            => '0',
                        'X-Shopify-Access-Token'    => $shop->access_token
                    ],
                    'curl'  => [
                        CURLOPT_RETURNTRANSFER => true
                    ]
                ]);
            }

        } catch (\Exception $e) {
            return response([
                'status' => false
            ], 500);
        }

        return response([
            'status' => true
        ], 200);

    }

    /**
     * Method to create theme liquid file
     * @param Shop $shop
     * @return bool
     */
    private function injectSnippet(Shop $shop)
    {
        try {
            $shopify = $this->getShopifyObj($shop);

            // ----- send a delete call for the hook snippet itself
            $shopify->call([
                'URL' => '/admin/themes/' . $this->activeTheme . '/assets.json?asset[key]=snippets/'.env('APP_NAME').'.liquid',
                'METHOD' => 'DELETE'
            ]);

            $props = [
                'APP_VENDOR'  => env('APP_VENDOR'),
                'HOOK_URL'    => route('pull.js', ['shop' => 'SHOP_DOMAIN']),
                'APP_URL'     => str_replace('https:', '', env('APP_URL'))
            ];
            $snippetFile = Storage::disk('local')->get('hook/winAds.html');
            $hookSnippet = str_replace(array_keys($props), array_values($props), $snippetFile);
            $hookSnippet = str_replace('SHOP_DOMAIN', '{{shop.permanent_domain}}', $hookSnippet);

            $shopify->call([
                'METHOD'    => 'PUT',
                'URL'       => '/admin/themes/' . $this->activeTheme . '/assets.json',
                'DATA'      => [
                    'asset' => [
                        'key'   => 'snippets/'.env('APP_NAME').'.liquid',
                        'value' => $hookSnippet,
                    ]
                ]
            ]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function themePublished(Request $request)
    {
        try {
            $shop = $request->input('shop');
            $shopObj = Shop::where('name', $shop)->first();

            // ----- agility check, if the active theme and the published theme are same, abort
            if ((string)$request->get('id') == $shopObj->theme_id) {
                return response([
                    'status' => false
                ], 200);
            }

            // ------ proceed with the routine if role is main
            if ($request->role == 'main') {

                // ----- clean old theme
                if ($shopObj->theme_id != null) {

                    // ----- set the old theme id in service prop, and update the one in table for agility
                    $this->getActiveTheme($shopObj, $request);
                    $shopObj->theme_id = $request->get('id');
                    $shopObj->save();

                    $this->cleanTheme($shopObj);
                }

                $this->fix($request);
            }
        } catch (\Exception $e) {
            return response([
                'status' => false
            ], 200);
        }
    }

    /**
     * @param $shop
     * @return array
     */
    public function shopHasTFX($shop)
    {
        $response = [
            'status'    => false,
            'plan'      => '',
            'winAds' => false,
            'message'   => 'winAds is not installed.'
        ];

        /** @var Shop $shopObj */
        $shopObj = Shop::where('name', $shop)->first();

        // ----- TFX check
        if ($shopObj != null) {
            $response['status'] = true;
            $response['plan'] = $shopObj->tfx_plan;
            $response['winAds'] = true;
            $response['message'] = 'Pixel Genie is installed.';
        }

        // ----- TFY check
        $client = new Client(['base_uri' => env('winAds_URL')]);
        $serviceResp = $client->get('is_winAds_installed/'.$shop);
        $body = json_decode($serviceResp->getBody());
        $gotTFY = boolval($body->status);

        // ----- TFY check
        if ($gotTFY) {
            $response['status'] = true;
            $response['winAds'] = true;
        }

        return $response;
    }

}
