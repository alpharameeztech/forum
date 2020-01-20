<?php

use App\Tasks\Users;
use Illuminate\Support\Facades\Hash;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

\URL::forceScheme('https');
 URL::forceRootUrl(env('APP_URL'));

Route::get('/', [
    'as'    => 'forum.home',
    'uses'  => 'ForumThreadController@index'
]);



Auth::routes();
Route::get('register', 'Auth\RegisterController@showRegistrationForm')
    ->name('register')
    ->middleware('has.token');

Route::get('/auth/redirect/{provider}', 'SocialController@redirect');
Route::get('/callback/{provider}', 'SocialController@callback');

Route::get('/home', 'HomeController@index')->name('home');


//========================= Forum ========================================

//store user thread subcription
Route::post('forum/threads/{channel}/{thread}/subscription', 'ForumThreadSubscriptionController@store')->middleware('auth');

//delete user thread subcription
Route::delete('forum/threads/{channel}/{thread}/subscription', 'ForumThreadSubscriptionController@destroy')->middleware('auth');


//get all the thread replies
Route::get('/forum/threads/{channel}/{thread}/replies','ForumReplyController@index');

Route::patch('forum/replies/{reply}', 'ForumReplyController@update')->middleware('auth');

//deleting a reply
Route::delete('forum/replies/{reply}', 'ForumReplyController@destroy')->middleware('auth');

//showcase all the threads
Route::get('threads', 'ForumThreadController@index');
Route::get('threads/{channel}', 'ForumThreadController@index');

//showcase all the threads
Route::get('forum/threads', 'ForumThreadController@index');

//create a forum thread
Route::get('forum/threads/create', 'ForumThreadController@create')->name('new.discussion')->middleware('auth');

//a user can delete their threads
Route::delete('forum/threads/{channel}/{thread}', 'ForumThreadController@destroy')->middleware('auth');

//showcase all the channel's thread
Route::get('forum/threads/{channel}', 'ForumThreadController@index');

//post a thread
Route::post('forum/threads', 'ForumThreadController@store')->middleware('auth');

//showcase a spefici channel thread
//showcase all the channel's thread
Route::get('forum/threads/{channel}/{thread}', 'ForumThreadController@show');

//update a thread
Route::patch('forum/threads/{channel}/{thread}', 'ForumThreadController@update');

//post a reply to a thread
Route::post('forum/threads/{channel}/{thread}/replies', 'ForumReplyController@store')->middleware('auth');

// mark a favorite reply
Route::post('/forum/replies/{reply}/favorites','ForumFavoriteController@store')->middleware('auth');

//delete a favorited reply
Route::delete('/forum/replies/{reply}/favorites','ForumFavoriteController@destroy')->middleware('auth');

// showing the user profile
Route::get('forum/profiles/{user}', 'ForumProfileController@show');

// showing the user notifications
Route::get('forum/profiles/{user}/notifications', 'NotificationController@index');

// marking a notification as read
Route::delete('forum/profiles/{user}/notifications/{notification}', 'NotificationController@destroy')->middleware('auth');

//mark the best reply of a thread
Route::post('forum/replies/{reply}/best', 'BestReplyController@store')->middleware('auth');

Route::get('api/users/list', function(){
  return Users::all();
});


Route::post('/upload/user/avatar', 'UserController@store')->middleware('auth');

//========================== Forum Ended ========================================

// ============================= shopify ===========================================
Route::prefix('/shopify')->middleware('odin')->group(function () {
    // ----- entry point for app
    Route::get('/', [
      'as'    => 'shopify.landing-page',
      'uses'  => 'ShopifyController@access'
    ])->middleware('payment')->middleware('hades');

    // ----- auth callback
    Route::get('auth-callback', [
      'as' => 'shopify.auth-callback',
      'uses' => 'ShopifyController@authCallback'
    ])->middleware('payment');

    // ----- uninstall hook callback
    Route::post('hooks/uninstall', [
      'as' => 'shopify.hooks-uninstall',
      'uses'  => 'ShopifyController@uninstall'
    ]);

    Route::get('/proxy', [
        'as' => 'shopify.proxy',
        'uses'  => 'ShopifyController@proxy'
    ]);
});

// ----- payment confirmation
Route::get('/shopify/charge-callback', [
  'as' => 'shopify.charge-callback',
  'uses' => 'ShopifyController@chargeCallback'
])->middleware('payment');


// ============================= shopify ended ===========================================

// ================================ admin & publisher ===================================================

Route::get('/{any}', 'Admin\AdminController@index')->where('any', 'admin')->middleware('is.grant');

use Illuminate\Routing\Router;

Route::group([
    'prefix'        => 'api',
    'namespace'     => 'Admin',
    'middleware'    => 'is.grant',
], function (Router $router) {

    Route::get('/channels', 'ForumChannelController@index');
    Route::patch('/channels', 'ForumChannelController@update');
    Route::post('/channels', 'ForumChannelController@store');
    Route::get('/channels/count', function(){
        return \App\Services\Stats\Channels::count();
    });
    Route::get('/channels/total/theads', function(){
        return \App\Services\Stats\Channels::totalThreads();
    });

    Route::get('/threads', 'ForumThreadController@index');
    Route::patch('/threads', 'ForumThreadController@update');
    Route::patch('/ban/thread', 'ForumThreadController@ban');
    Route::get('/threads/count', function(){
        return \App\Services\Stats\Threads::count();
    });
    Route::get('/threads/with/no/replies', function(){
        return \App\Services\Stats\Threads::noReplies();
    });
    Route::get('/trending/threads', function(){
        $trending_thread = new \App\Tasks\Forum\TrendingThreads;
        return $trending_thread();
    });
    Route::get('/recent/thread', function(){
        return \App\Services\Stats\Threads::recent();
    });

    Route::get('/replies', 'ForumReplyController@index');
    Route::patch('/replies', 'ForumReplyController@update');
    Route::patch('/ban/reply', 'ForumReplyController@ban');
    Route::get('/replies/count', function(){
        return \App\Services\Stats\Replies::count();
    });
    Route::get('/recent/reply', function(){
        return \App\Services\Stats\Replies::recent();
    });

    Route::get('/publishers', 'ForumPublisherController@index');
    Route::post('/publisher', 'ForumPublisherController@store');
    Route::patch('/publisher', 'ForumPublisherController@update');

    Route::get('/members', 'ForumMemberController@index');
    Route::post('/member', 'ForumMemberController@store');
    Route::patch('/ban/member', 'ForumMemberController@ban');
    Route::get('/members/count', function(){
        return  \App\Services\Stats\Members::count();
    });
    Route::get('/top/members', function(){
        return \App\Services\Stats\Members::top();
    });
    Route::get('/recent/member', function(){
        return \App\Services\Stats\Members::recent();
    });

    //get the store settings
    Route::get('/branding', 'ForumBrandingController@index');
    Route::patch('/branding', 'ForumBrandingController@update');
    Route::post('/branding', 'ForumBrandingController@store');

    Route::get('/themes', 'ForumThemeController@index');

    Route::get('/theme/settings', 'ForumThemeSettingController@show');
    Route::patch('/theme/settings', 'ForumThemeSettingController@update');


    Route::patch('/shop/appearance', 'ForumAppearanceController@update');
    Route::post('/shop/appearance', 'ForumAppearanceController@store');

    Route::get('/account/details', function(){
        return \App\Repository\Forum\UserAccount::get();
    });
    Route::post('/account/details', function(){
        return \App\Repository\Forum\UserAccount::update();
    });

    Route::get('/shop/plan/features', function (){

        return \App\Services\ShopPlanFeatures::get();
    });


});

// ================================ admin & publisher ended ===================================================

// ================================ admin  ===================================================
Route::group([
    'prefix'        => 'api',
    'namespace'     => 'Admin',
    'middleware'    => 'is.admin',
], function (Router $router) {

    Route::get('/publishers', 'ForumPublisherController@index')->name('team.users');
//    Route::post('/publisher', 'ForumPublisherController@store');
    Route::patch('/publisher', 'ForumPublisherController@update');

    Route::post('/invitation', 'ForumInviteController@invite')->name('sendInvitation')->middleware('can.invite.team.users');

});
// ================================ admin ended ===================================================

Route::get('/api/shop/appearance', 'Admin\ForumAppearanceController@show')->name('shopAppearance');
