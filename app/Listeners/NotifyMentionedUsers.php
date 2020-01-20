<?php

namespace App\Listeners;

use App\Events\ThreadHasNewReply;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\YouWereMentioned;
use App\User;

class NotifyMentionedUsers
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
        //preg_match_all('/\@([^\s\.]+)/', $event->reply->body, $matches);
          
        preg_match_all('/@([\w\-]+)/', $event->reply->body, $matches);
        
       // \Log::info($matches);

        $names = $matches[1];

        foreach ($names as $name) {
            
            $user  = User::whereName($name)->first();

            if($user){

                $user->notify(new YouWereMentioned ($event->reply));
            }
        }
    }
}
