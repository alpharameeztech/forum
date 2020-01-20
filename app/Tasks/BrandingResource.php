<?php

namespace App\Tasks;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\UserTrainingHistory;
use App\Product;
use App\Branding;
use Storage;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use App\SubscriptionRepository\UserSubscriptions;

class BrandingResource {

    public static function download($request){ // get the user's subcribed products
       
        $product_id = $request->pid; // get the requested product id
            
        $branding_id = $request->id; // get the requested product branding id
     
        $obj= Branding::where('product_id', $product_id)
            ->where('id', $branding_id)
            ->where('type', 'file')
            ->pluck('file'); // this is a collection

        $var = storage_path() . '/app/public/'. $obj[0];
    
        $img = Image::make($var);

        return $img->response('png');

    }   

}



