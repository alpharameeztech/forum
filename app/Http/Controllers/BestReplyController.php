<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ForumReply;
use Illuminate\Support\Facades\Auth;

class BestReplyController extends Controller
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

        //if the owner isnt the one who is marking the best reply then abort
        //abort_if( $reply->thread->user_id !=  Auth::id(), 401 );

        //$this->authorize('update', $reply->thread);

        // $reply->thread->update([

        //     'best_forum_reply_id' => $reply->id

        // ]);

        $reply->thread->markAsBest($reply);

        if($reply->owner->id !=  $reply->thread->creator->id) {

            $reply->owner->updateExperience(150);

        }else{
           
        }
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
}
