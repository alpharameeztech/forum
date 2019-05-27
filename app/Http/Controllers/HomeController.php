<?php

namespace App\Http\Controllers;

use App\ProductsRepository\PublishedProducts;
use App\ProductsRepository\SingleProduct;
use App\ProductsRepository\SubscribedProduct;
use App\SubscriptionRepository\UserSubscriptions;
use App\Tasks\Progress;
use App\Tasks\Score;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    private $products;

    private $userSubscriptions;

    private $isSubscribedProduct;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        PublishedProducts $products,
        UserSubscriptions $userSubscriptions,
        SubscribedProduct $isSubscribedProduct
    ) {
        $this->middleware('auth');

        $this->middleware('subscribed');

        $this->products = $products;

        $this->userSubscriptions = $userSubscriptions;

        $this->isSubscribedProduct = $isSubscribedProduct;
    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function index()
    {

        $user_id = Auth::id();

        $products = $this->products->getAllPublishedProducts(); // pass all the purchased products of a user

        $subscribed_products = $this->userSubscriptions->subscribed_products(); // for multiple susbcrition

        if ($subscribed_products == false) { // user has unsubscribed and grce period ends too
            return redirect('settings#/subscription');
        } else {
            return view('dashboard.home', [
                'products' => $subscribed_products,
            ]);
        }
    }

    public function show($productId)
    {
        //dd(Auth::user()->sparkPlan());
        // dd(Auth::user()->current_billing_plan);
        $user_id = Auth::id();
        $verfied = $this->isSubscribedProduct->verify($productId); // check if the user has subscribed to this product

        //check whether requested  the product has subscribed or all access
        if ($verfied) {

            $product = SingleProduct::get($productId);

            $progress = Progress::result($productId);

            $score_array = Score::result($productId);

            return view('dashboard.show', [
                'product' => $product[0],
                'progress' => $progress,
                'score_array' => $score_array,
            ]);

        } else {
            return redirect('settings#/subscription');
        }
    }

}
