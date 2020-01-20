<?php

namespace App\Http\Controllers;

use App\Services\CustomSlug;
use Illuminate\Http\Request;
use App\ForumThread;
use Illuminate\Support\Facades\Auth;
use App\ForumChannel;
use App\User;
use App\Repository\Forum\Threads\Filter;
use App\Inspections\Spam;
use App\Tasks\Forum\TrendingThreads;
use Illuminate\Support\Facades\Cache;
use Zttp\Zttp;

class ForumThreadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ForumChannel $channel,  TrendingThreads $trendingThreads) //or $channelSlug = null
    {
        // if($channelSlug){

        //     $channelId = ForumChannel::where('slug',$channelSlug)->first()->id;

        //     $threads = ForumThread::where('forum_channel_id',$channelId)->latest()->get();


        // }

        if($channel->exists){ // if the channel is valid model on forumChannel
            $threads_builder_query = $channel->threads()->latest()->where('is_ban',0);
        }
        else {
            $threads_builder_query = ForumThread::where('is_ban',0)->latest();
        }

        $threads = Filter::apply($threads_builder_query);

        return view('forum.threads.index',[
            'threads' => $threads,
            'trending_threads' => $trendingThreads->get()
        ]);
        return $threads;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(TrendingThreads $trendingThreads)
    {

        return view('forum.threads.create', [
            'trending_threads' => $trendingThreads->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // //first make sure that captcha response is valid
        // $response  = Zttp::asFormParams()->post('https://www.google.com/recaptcha/api/siteverify',[
        //     'secret' => config('services.recaptcha.secret'),
        //     'response' => $request->input('g-recaptcha-response'),
        //     'remoteip' => $_SERVER['REMOTE_ADDR']
        // ]);

        // $response_body = json_decode($response->getBody(), true);

        // \Log::info(get_class($this));
        // \Log::info($response_body);

        // if($response_body['success'] == false){ // redirect and abort
        //     return redirect('forum/threads/create')
        //     ->with('flash-message','Sorry! Your thread is not posted. Captcha is required');
        // }

        $this->validateRequest();

        $thread = ForumThread::create([
            'shop_id' => Cache::get('shop_id'),
            'user_id' => Auth::id(),
            'forum_channel_id' => request('channel'),
            'is_ban' => 0, // allow by default
            'title' => request('title'),
            'slug' => CustomSlug::create(request('title')), //str_slug(request('title')),
            'body' => request('body')
        ]);

        //increment user experience
        $thread->creator->updateExperience(100);

        return redirect('forum'. $thread->path())
                ->with('flash-message','Your thread has been published!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($channelId, ForumThread $thread, TrendingThreads $trendingThreads)
    {

        if(auth()->user()){

            Auth::user()->read($thread); // user has read this thread

       }

        $thread = ForumThread::find($thread->id);

        // incremet the trending threads value
        $trendingThreads->push($thread);

        $thread->visits()->record(); // where visit is a function on forumThread model

        return view('forum.threads.show',[
            'thread' => $thread,
            'replies' => $thread->replies()->paginate(10),
            'trending_threads' => $trendingThreads->get()
        ]);

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
    public function update($channel, ForumThread $thread)
    {

        //use policies to verify whether the user can do this action or not
        $this->authorize('update', $thread);

         $thread->update(request()->validate([

            'body' => 'required'
         ]));

         if( request()->expectsJson()){

            return 1;
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($channel, ForumThread $thread)
    {
        //use policies to verify whether the user can do this action or not
        $this->authorize('delete', $thread);

        //first delete all the replies of a thread
        $thread->replies()->delete();
        //then delete a thread
        $thread->delete();

        return redirect('forum/threads');
    }

    protected function validateRequest(){

        $this->validate(request(), [
            'channel' => 'required|exists:forum_channels,id',  // a valid forum_channel_id is required of  the forum_channels table
            'title' => 'required',
            // 'body' => 'required'
        ]);

        resolve(Spam::class)->detect(request('body'));// with resolve you dont have to inject into class constructor

        resolve(Spam::class)->detect(request('title'));
    }
}
