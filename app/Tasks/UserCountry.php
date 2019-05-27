<?php

namespace App\Tasks;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;


class UserCountry {

   public static function name(){ // get the user's subcribed products

        
    // $user_id = Auth::user()->id;



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





    //session_destroy();
    //$ip = '182.176.176.21';
    //$ip = '1.0.0.255';
        

        // $session_current_ip = $session->get('session_current_ip');

        // $session_current_country_code = $session->get('session_current_country_code');

        // $session_licence_price = $session->get('session_licence_price');

        // $session_designation_price = $session->get('session_designation_price');

 
//    if(empty($session_current_ip)){
        //$ip = JFactory::getApplication()->input->server->get('REMOTE_ADDR','');
       //  $ip = file_get_contents('https://api.ipify.org');
      
      $ip =  $_SERVER["HTTP_CF_CONNECTING_IP"] ?? '31.203.100.107'; // get the user actual ip or if not accessible set it to zone 2 any country .i.e kuwait
  
       
       //$ip = $_SERVER["HTTP_CF_CONNECTING_IP"];
        //$ip = '203.175.72.232';
        //$ip = '109.201.137.46';
         
        // //$ip = request()->ip();
        
        //dd($_SERVER['HTTP_X_FORWARDED_FOR']);
        //$session->set('session_current_ip', $ip);

        // global $_SERVER;
        // if ( is_null( $ip ) ) {
        // 	$ip = JFactory::getApplication()->input->server->get('REMOTE_ADDR','');
        // }


        $host = "http://www.geoplugin.net/php.gp?ip=$ip&base_currency=dollar";

        

        $data = array();
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $host);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'geoPlugin PHP Class v1.0');
        $response = curl_exec($ch);
        curl_close($ch);
       // Log::info($response);
      

        
        $data = unserialize($response);
        
         //set the geoPlugin vars
        
        $city = $data['geoplugin_city'];
         $region = $data['geoplugin_region'];
         $areaCode = $data['geoplugin_areaCode'];
         $dmaCode = $data['geoplugin_dmaCode'];
         $countryCode = $data['geoplugin_countryCode'];
         $countryName = $data['geoplugin_countryName'];
         $continentCode = $data['geoplugin_continentCode'];
         $latitude = $data['geoplugin_latitude'];
        $longitude = $data['geoplugin_longitude'];
        $currencyCode = $data['geoplugin_currencyCode'];
        $currencySymbol = $data['geoplugin_currencySymbol'];
        $currencyConverter = $data['geoplugin_currencyConverter'];
        
       
        //dd($countryCode);
        return $ct_name = $countryName;


    }   


}



