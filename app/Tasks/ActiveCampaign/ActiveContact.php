<?php

namespace App\Tasks\ActiveCampaign;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\UserTrainingHistory;
use App\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use App\SubscriptionRepository\UserSubscriptions;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

use GuzzleHttp\Psr7\Request as GuzzleRequest; 
use App\Tasks\ActiveCampaign\Repository\ActiveCampaignUserName;
use App\Tasks\ActiveCampaign\Repository\ActiveCampaignUserEmail;


class ActiveContact {

    public static $static_data;

    public static function initialize($customer_id){ // get the user's subcribed products

        $client = new Client();

        $res = $client->request('GET', 'https://globalrealestatelicence.api-us1.com/api/3/contacts/' . $customer_id,[
            'headers' => [
                'Api-Token' => 'ba61d6145665290421653d67d720ba209f1ab61e999a7cc8df6b41f78f8bc1b1c79a3957'
            ]
        ]);
  
        $res->getHeaderLine('content-type');
       
        $data = $res->getBody();
        
        $data_decoded = json_decode($data);
        
        $static_data = $data_decoded;
        
        return $static_data;

    }   

    public static function name($request, $customer_id){

        $static_data = ActiveContact::initialize($customer_id);

        $name = ActiveCampaignUserName::get($static_data);

        //$request->session()->put('ActiverCampaignUserName', $name);

       return $name;

    }

    public static function email($request, $customer_id){

        $static_data = ActiveContact::initialize($customer_id);

        $email = ActiveCampaignUserEmail::get($static_data);

        //$request->session()->put('ActiverCampaignUserEmail', $email);

       return $email;

    }


}



