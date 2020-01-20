<?php

namespace App\Repositories;

use App\ForumBranding;
use App\Interfaces\BrandingRepositoryInterface;
use App\User;
use Illuminate\Support\Facades\Hash;

class BrandingRepository implements BrandingRepositoryInterface
{
    /**
     * Get's a branding of current store
     *
     * @param int
     * @return collection
     */
    public function get()
    {
        $shop_id = 13;

        return ForumBranding::where('shop_id', $shop_id)->first() ? : '' ;

    }

    /**
     * Get's all brandings.
     *
     * @return mixed
     */
    public function all()
    {
        return User::where('type', 'branding')->get();
    }


    /**
     * Updates a branding.
     *
     * @param $id
     * @param $name
     * @param $pasword
     * @param $email
     * @param $ban
     */
    public function update($request)
    {

        $branding =  ForumBranding::find($request->id);

        if (!empty($request->file('logo'))) {
            $branding->logo = $request->file('logo')->store('test', 's3');
        }
        $branding->title = $request->title;
        $branding->copyright = $request->copyright;
        $branding->google_analytics_code = $request->google_analytics_code;

        $branding->save();
    }


    /**
     * Store a user
     * @param $user_data
     */
    public function store($request){

        $branding = new ForumBranding;

        $branding->logo = $request->file('logo')->store('test', 's3');

        $branding->title = $request->title;
        $branding->copyright = $request->copyright;
        $branding->google_analytics_code = $request->google_analytics_code;

        $branding->save();

    }
}
