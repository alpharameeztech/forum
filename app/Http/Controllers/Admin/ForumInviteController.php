<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use App\Notifications\SendPublisherAnInvitationToJoin;
use App\ForumInvite;

class ForumInviteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * send the mail invitation
     * and generate token
     */
    public function invite(Request $request)
    {

        // validate the incoming request data

        do {
            //generate a random string using Laravel's str_random helper
            $token =  Str::random(40);
        } //check if the token already exists and if it does, try again
        while (ForumInvite::where('token', $token)->first());

        //create a new invite record
        $invite = ForumInvite::create([
            'shop_id' => Cache::get('shop_id'),
            'name' => $request->get('name'),
            'alias' => $request->get('alias'),
            'email' => $request->get('email'),
            'token' => $token
        ]);

        $invite->notify(new SendPublisherAnInvitationToJoin($invite));

    }
}
