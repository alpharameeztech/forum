<?php
/**
 * Created by PhpStorm.
 * User: Bilal
 * Date: 7/9/2018
 * Time: 10:52 AM
 */

namespace App\Services;


use App\Contracts\DefinitionRepository;
use App\Contracts\SettingRepository;
use App\Contracts\ShopRepository;
use App\Shop;
use App\Traits\ShopifyTrait;
use Illuminate\Support\Facades\Log;

class WebhookService
{
    use ShopifyTrait;

    /**
     * @var ShopRepository
     */
    protected $shopRepo;

    /**
     * @var SettingRepository
     */
    protected $settingRepository;

    /**
     * IntegrityService constructor.
     * @param ShopRepository $shopRepo
     * @param SettingRepository $settingRepository
     */
    public function __construct(
    )
    {

    }

    /**
     * initialize hooks on shop when new install
     * @param $shop
     * @throws \Exception
     */
    public function injectHooks(Shop $shop)
    {
        // ---- inject hooks
        $this->registerUninstallHook($shop);
    }

    /**
     * @param $shop
     * @return array
     * @throws \Exception
     */
    public function registerHook($shop, $topic, $route)
    {
        $shop = $shop instanceof Shop ? $shop : Shop::where('name', $shop)->first();
        $response = [
            'status' => '',  // ----- can be ['failure', 'success', 'abort']
            'message' => '',
            'data' => []
        ];

        // ----- register uninstall hook
        $shopify = $this->getShopifyObj($shop);
        $hook = $shopify->call([
            'URL' => '/admin/webhooks/count.json?topic='.$topic,
            'METHOD' => 'GET'
        ]);

        if (isset($hook->count) && $hook->count == 0) {
            try {
                $resp = $shopify->call([
                    'METHOD'    => 'POST',
                    'URL'       => '/admin/webhooks.json',
                    'DATA'      => [
                        'webhook' => [
                            'topic'     => $topic,
                            'address'   => $route,
                            'format'    => 'json'
                        ]
                    ]
                ]);

                $response['status'] = 'success';
                $response['message'] = 'Hook injected successfully.';
                $response['data'] = $resp->webhook;

            } catch (\Exception $e) {
                $response['status'] = 'failure';
                $response['message'] = $e->getMessage();
            }
        } else {
            $response['abort'] = 'failure';
            $response['message'] = 'Hook already exists.';
        }

        return $response;
    }

    /**
     * Hook for loading pixel Engine on each page load of storefront
     * @param $shop
     * @throws \Exception
     */
    public function registerScriptHook($shop)
    {
        $shop = $shop instanceof Shop ? $shop : Shop::where('name', $shop)->first();
        $shopify = $this->getShopifyObj($shop);

        // ----- existing hook cleanup
        $hook = $shopify->call([
            'URL' => '/admin/script_tags.json',
            'METHOD' => 'GET'
        ]);
        if (isset($hook->script_tags)) {
            foreach ($hook->script_tags as $crntTag) {
                $shopify->call([
                    'URL' => '/admin/script_tags/'.$crntTag->id.'.json',
                    'METHOD' => 'DELETE'
                ]);
            }
        }

        $resp = $shopify->call([
            'METHOD'    => 'POST',
            'URL'       => '/admin/script_tags.json',
            'DATA'      => [
                'script_tag' => [
                    'event' => 'onload',
                    'src'   => route('lazy.js'),
                ]
            ]
        ]);
    }

    /**
     * @param $shop
     * @return array
     * @throws \Exception
     */
    public function registerUninstallHook($shop)
    {
        return $this->registerHook($shop, 'app/uninstalled', route('shopify.hooks-uninstall'));
    }

    public function registerThemePublishHook(Shop $shop)
    {
        return $this->registerHook($shop, 'themes/publish', route('hooks.themePublished'));
    }

    public function registerProductCreateHook($shop)
    {
        return $this->registerHook($shop, 'products/create', route('product.create'));
    }

    public function registerProductUpdateHook($shop)
    {
        return $this->registerHook($shop, 'products/update', route('product.update'));
    }

    public function registerProductDeleteHook($shop)
    {
        return $this->registerHook($shop, 'products/delete', route('product.delete'));
    }

    public function registerOrderCreateHook($shop){
        Log::info("order hook registered");
        return $this->registerHook($shop, ' orders/create', route('order.create'));
    }

}
