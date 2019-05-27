<?php

namespace App\Tasks\ActiveCampaign\Repository;

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


class ActiveCampaignUserEmail {

    public static function get($contact_data){ // get the user's subcribed products

        return $contact_data->contact->email;
       
    }   

}



