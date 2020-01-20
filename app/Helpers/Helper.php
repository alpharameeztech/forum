<?php
/**
 * Created by PhpStorm.
 * User: Bilal
 * Date: 12/17/2018
 * Time: 11:01 AM
 */
namespace App\Helpers;

use App\Shop;
use Carbon\Carbon;
use Illuminate\Http\Request;

class Helper
{
    public static function hadesHeader()
    {
        return 'eoe-forum-hades';
    }

    public static function generateHades(Shop $shop, $extra = [])
    {
        return encrypt(array_merge([
            'shop' => $shop->name,
            'expires' => Carbon::now()->addMinutes(intval(env('HADES_EXPIRE_MINUTES')))->toDateTimeString()
        ], $extra));
    }

    public static function generateHadesBlock(Shop $shop, $extra = [])
    {
        return [Helper::hadesHeader() => Helper::generateHades($shop, $extra)];
    }

    public static function generateHadesCookie(Shop $shop, $extra = [])
    {
        $minutes = time() + (60 * env('HADES_EXPIRE_MINUTES'));
        return cookie(Helper::hadesHeader(), Helper::generateHades($shop, $extra), $minutes);
    }

    public static function decodeHades($hades)
    {
        return decrypt($hades);
    }

    public static function decodeHadesCookie(Request $request)
    {
        return decrypt($request->cookie(Helper::hadesHeader()));
    }

    public static function decodeHadesQuery(Request $request)
    {
        return decrypt($request->get(Helper::hadesHeader()));
    }

    public static function decodeHadesHeader(Request $request)
    {
        return decrypt($request->header(Helper::hadesHeader()));
    }

    public static function fbCookieName($shop) {
        return 'eoe_dna_'.str_replace('.myshopify.com', '', $shop);
    }

    public static function getProxyURL($shop) {
        return env('SHOPIFY_PROXY');
    }

}
