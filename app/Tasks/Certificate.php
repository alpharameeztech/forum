<?php

namespace App\Tasks;

use App\Branding;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManagerStatic as Image;
use App\UserTrainingHistory;
use App\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Routing\Redirector;
use Laravel\Cashier\Subscription;
use Storage;
use App\ProductsRepository\PublishedProducts;
use App\SubscriptionRepository\UserSubscriptions;

class Certificate {

    private $products;

    function __construct( PublishedProducts $products) {
       
        $this->products = $products;

    }

    

   public function create($request){ // get the user's subcribed products

        $product_id = $request->id; // get the requested product id
      
        $user_id = Auth::id();

        $user = Auth::user();
        
        $user_uuid = $user->uuid;

        $user_uuid_exp = explode('-',$user_uuid);
        

        // validate the user level for the requested product
        $verify= UserTrainingHistory::where('user_id', $user_id)
                                ->where('product_id', $product_id)
                                ->where('quest_level', 4.4)
                                ->where('score', '>=', 60.00)
                                ->exists(); // this is a collection

        if($verify){

        $user_name = ucwords(Auth::user()->name);
        
        $obj= Branding::where('product_id', $product_id)
        ->where('type', 'certificate')
        ->pluck('file'); // this is a collection
        
   
        //$var = storage_path() . '/app/public/'. $obj[0]; // for local storage
        //dd(Storage::disk("s3") );
        $var = Storage::disk("s3")->get($obj[0]); // for s3 file
    
        //$var = 'https://s3.us-east-2.amazonaws.com/greljedi/branding/ACS-01.jpg';
        $img = Image::make($var);
            
       

        $img->text($user_name, 1400, 900, function($font) {
            $font->file(public_path('fonts/roboto/Roboto-Black.ttf'));
            $font->size(50);
            $font->color('#000');
            $font->align('center');
            $font->valign('center');
            $font->angle(0);
        });


        //print the user uuid number
        $img->text($user_uuid_exp[4], 2000, 1250, function($font) {
            $font->file(public_path('fonts/roboto/Roboto-Black.ttf'));
            $font->size(35);
            $font->color('#000');
            $font->align('center');
            $font->valign('center');
            $font->angle(0);
        });

    
        
        //$img = Image::canvas(800, 600, '#ccc'); // creation of empty image
        return $img->response('jpg');

        }else{
            $products = $this->products->getAllPublishedProducts();  // make sure these products are the purchased ones by the logged in users
    
            $request->session()->flash('error', "Sorrry:( You have to pass all the levels of training first");
            return view('branding.index', [
                'products' => $products
            ]);
        }

    }   


}



