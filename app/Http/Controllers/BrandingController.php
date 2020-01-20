<?php

namespace App\Http\Controllers;

use App\AllAccess\SubscribedAllAccess;
use App\Branding;
use App\ProductsRepository\Name;
use App\ProductsRepository\PublishedProducts;
use App\SubscriptionRepository\UserSubscriptions;
use App\Tasks\BrandingAccess;
use App\Tasks\BrandingResource;
use App\Tasks\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BrandingController extends Controller
{
    private $allAccess;

    private $products;

    private $userSubscriptions;

    private $certificate;

    public function __construct(
        SubscribedAllAccess $allAccess,
        PublishedProducts $products,
        UserSubscriptions $userSubscriptions,
        Certificate $certificate) {
        $this->middleware('auth');

        $this->middleware('subscribed');

        $this->allAccess = $allAccess;

        $this->products = $products;

        $this->userSubscriptions = $userSubscriptions;

        $this->certificate = $certificate;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = Auth::id();

        $products = $this->products->getAllPublishedProducts(); // all active/published products

        $isAllAccessSubscribed = $this->allAccess->getAllPoductsOnAccessAll(); // either false or list of all products if subscribed all access

        // //check first for all access subscription
        if (($isAllAccessSubscribed) != false) {

            return view('branding.index', [
                'products' => $isAllAccessSubscribed,
            ]);
        } // return as the user has subscribed to all access

        $subscribed_products = $this->userSubscriptions->subscribed_products(); // for multiple susbcrition

        if (($subscribed_products) != '') {

            return view('branding.index', [
                'products' => $subscribed_products,
            ]);

        }

        if ($subscribed_product[0] == false) { // user has unsubscribed and grce period ends too

            return redirect('settings#/subscription');

        } else {

            return view('branding.index', [

                'products' => $subscribed_product,
            ]);
        }
    }

    public function certificate(Request $request)
    {

        return $this->certificate->create($request);

    }

    public function resource(Request $request)
    {

        $verify = BrandingAccess::verify($request->pid);

        if ($verify) {

            return BrandingResource::download($request);

        } else {

            $products = $this->userSubscriptions->subscribed_products(); // make sure these products are the purchased ones by the logged in users

            $request->session()->flash('error', "Sorrry:( You have to pass all the levels of training first");

            return view('branding.index', [
                'products' => $products,
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Branding  $branding
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {

        // validate the user level for the requested product
        $verify = BrandingAccess::verify($id);

        // if ($verify) {
            if(true){
            $brandings = Branding::where('product_id', $id)->get();

            $licence = Name::get($id);

            // Verify whether the user can access this resource or not i.e if purchased
            $verified = true;

            if ($verified && count($brandings)) {
                return view('branding.show', [
                    'brandings' => $brandings,
                    'licence' => $licence,
                    'product_id' => $id,
                ]);
            }
        } else {

            // make sure these products are the purchased ones by the logged in users
            $request->session()->flash('error', "Sorry:( You have to pass all the levels of training first to unlock the branding");

            $products = $this->products->getAllPublishedProducts(); // all active/published products

            $subscribed_products = $this->userSubscriptions->subscribed_products(); // for multiple susbcrition

            $isAllAccessSubscribed = $this->allAccess->getAllPoductsOnAccessAll(); // either false or list of all products if subscribed all access

            // //check first for all access subscription
            if (($isAllAccessSubscribed) != false) {

                return view('branding.index', [
                    'products' => $isAllAccessSubscribed,
                ]);
            } // return as the user has subscribed to all access

            if (($subscribed_products) != '') {

                return view('branding.index', [
                    'products' => $subscribed_products,
                ]);

            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Branding  $branding
     * @return \Illuminate\Http\Response
     */
    public function edit(Branding $branding)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Branding  $branding
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Branding $branding)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Branding  $branding
     * @return \Illuminate\Http\Response
     */
    public function destroy(Branding $branding)
    {
        //
    }

}
