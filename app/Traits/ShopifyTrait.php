<?php
/**
 * Created by PhpStorm.
 * User: Bilal
 * Date: 7/13/2018
 * Time: 3:38 PM
 */

namespace App\Traits;


use App\Shop;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use RocketCode\Shopify\API;

trait ShopifyTrait
{

    /**
     * @param mixed $shop
     * @param null $token
     * @return API
     */
    public function getShopifyObj($shop, $token = null)
    {
        $name = $shop instanceof Shop ? $shop->name : $shop;
        $token = $shop instanceof Shop ? $shop->access_token : $token;

        $apikey = env('SHOPIFY_API_KEY');
        $secret = env('SHOPIFY_API_SECRET');

        return \App::make('ShopifyAPI', [
            'API_KEY'       => $apikey,
            'API_SECRET'    => $secret,
            'SHOP_DOMAIN'   => $name,
            'ACCESS_TOKEN'  => $token
        ]);
    }

    public function verifyWebHook(Request $request)
    {
        $data = $request->getContent();
        $hmacHeader = $request->server('HTTP_X_SHOPIFY_HMAC_SHA256');

        // ----- dynamic secret
        $secret = env('SHOPIFY_API_SECRET');
        $name = $request->get('shop');
        // ----- dynamic secret end

        $calculatedHmac = base64_encode(hash_hmac('sha256', $data, $secret, true));
        return ($hmacHeader == $calculatedHmac);
    }

    public function verifyRequest(Request $request, $proxy = false)
    {
        $query_params = $request->query->all();
        if (!isset($query_params['timestamp'])) return false;
        $seconds_in_a_day = 24 * 60 * 60;
        $older_than_a_day = $query_params['timestamp'] < (time() - $seconds_in_a_day);
        if ($older_than_a_day) return false;

        $hmac = isset($query_params['hmac']) ? $query_params['hmac'] : $query_params['signature'];
        unset($query_params['signature'], $query_params['hmac'], $query_params['facebook-access']);

        foreach ($query_params as $key=>$val) $params[] = "$key=$val";
        sort($params);
        $glue = $proxy ? '' : '&';

        // ----- dynamic secret
        $secret = env('SHOPIFY_API_SECRET');
        $name = $request->get('shop');
        // ----- dynamic secret end

        $matched = (hash_hmac('sha256', implode($glue, $params), $secret) === $hmac);
        return $matched;
    }

    public function asyncGuzzle($path, Shop $shop, $callback)
    {
        // ----- revoke API Access
        $client = new Client(['base_uri' => 'https://'.$shop->name.'/admin/']);
        $promise = $client->requestAsync('GET', $path, [
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
        $promise->then(function($response) use ($callback){
            $callback(json_decode($response->getBody(), true));
        });
        return $promise;
    }

}
