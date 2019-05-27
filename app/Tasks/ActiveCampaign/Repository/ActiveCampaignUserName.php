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


class ActiveCampaignUserName {

    public static function get($contact_data){ // get the user's subcribed products


        $firstName = $contact_data->contact->firstName;
        
        $lastName = $contact_data->contact->lastName;

        $fullName = $firstName . ' ' . $lastName;

        return $fullName;

       
    }   

}



