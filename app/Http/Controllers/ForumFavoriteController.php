<?php

namespace App\Http\Controllers;

use App\ForumFavorite;
use Illuminate\Http\Request;
use App\ForumReply;
use Illuminate\Support\Facades\Auth;

class ForumFavoriteController extends Controller
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
    public function store(ForumReply $reply)
    {
        // $ForumFavorite =  ForumFavorite::create([

        //     'user_id' => Auth::id(),
        //     'favorited_id' => $reply->id,
        //     'favorited_type' => get_class($reply)
        // ]);

        // as the polymorphio relation is already being set then just create as
       // $reply->favorites()->create(['user_id' => Auth::id()]);

        $reply->favorite();

        return back();

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ForumFavorite  $forumFavorite
     * @return \Illuminate\Http\Response
     */
    public function show(ForumFavorite $forumFavorite)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ForumFavorite  $forumFavorite
     * @return \Illuminate\Http\Response
     */
    public function edit(ForumFavorite $forumFavorite)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ForumFavorite  $forumFavorite
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ForumFavorite $forumFavorite)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ForumFavorite  $forumFavorite
     * @return \Illuminate\Http\Response
     */
    public function destroy(ForumReply $reply)
    {
        $reply->unFavorite();
    }
}
