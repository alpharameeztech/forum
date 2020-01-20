<?php
/**
 * Created by PhpStorm.
 * User: Bilal
 * Date: 3/8/2018
 * Time: 02:23 PM
 */

namespace App\Services;

use App\Helpers\Helper;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GeoPlugin
{

    public static function country(){

        //first try to get from the Cloudflare the user country
        $request = new Request;
        $cf_headers = $request->headers->has('CF-IPCountry');
        $cf_country = $request->header('CF-IPCountry');

        //cloudflare pass the country
        //on production
        //then return it
        if($cf_country != null)
        {
            return $cf_country;
        }else
        {
            //use the geolocation service
            //return GeoPlugin::geoLocationService();
            return null;
        }

    }

    public static function geoLocationService(){

        //the geoPlugin server
        $host = 'http://www.geoplugin.net/php.gp?ip={IP}&base_currency={CURRENCY}';

        //the default base currency
        $currency = 'USD';
        //initiate the geoPlugin vars
        $ip = null;
        $city = null;
        $region = null;
        $areaCode = null;
        $dmaCode = null;
        $countryCode = null;
        $countryName = null;
        $continentCode = null;
        $latitude = null;
        $longitude = null;
        $currencyCode = null;
        $currencySymbol = null;
        $currencyConverter = null;
        $autoloadLanguage = true;

        $ip =  $_SERVER["HTTP_CF_CONNECTING_IP"] ?? null;

        if($ip != null)
        {
            $host = "http://www.geoplugin.net/php.gp?ip=$ip";

            $data = array();

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $host);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, 'geoPlugin PHP Class v1.0');
            $response = curl_exec($ch);
            curl_close($ch);

            $data = unserialize($response);

            $countryName = $data['geoplugin_countryName'];

            return  $countryName;
        }else
        {
            return null;
        }
    }

}
