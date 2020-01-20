<?php

namespace App\Listeners;

use App\Events\ThreadHasNewReply;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\ThreadWasUpdated;

class NotifyThreadSubscribers
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ThreadHasNewReply  $event
     * @return void
     */
    public function handle(ThreadHasNewReply $event)
    {
        foreach ($event->thread->subscriptions as $subscription) {

            if($subscription->user_id !=  $event->reply->user_id){

                $subscription->user->notify( new ThreadWasUpdated($event->thread, $event->reply));

                //$subscription->user->notify( $reply);

            }

        }
    }
}
