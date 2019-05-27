<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ForumChannel;
use App\Traits\RecordForumActivity;
use App\ForumThreadSubscription;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ThreadWasUpdated;
use App\Events\ThreadHasNewReply;
use App\Visits;
use App\ForumReply;

class ForumThread extends Model
{
    use RecordForumActivity;

    protected $guarded = [] ;

    protected $appends = ['isSubscribedTo'];

    public function getRouteKeyName(){

        return 'slug';
    }

    public function getReplyCountAttribute(){
        
        return $this->replies()->count();

    }

    public function path(){

        return "/threads/{$this->channel->slug}/{$this->slug}"; // adddin a slug channel name i.e threads/php/1

    }
    public function replies(){
        
        return $this->hasMany(ForumReply::class)->withCount('favorites');
    }

    public function creator(){

        // as i am using the owner name instead of user,
        // so i am being explicit for the model key which then i have specificed user_id
        return $this->belongsTo(User::class, 'user_id');
    }

    public function addReply($reply){

        $reply = $this->replies()->create($reply); // this->replihipd deletages to the older relationship

        event(new ThreadHasNewReply($this, $reply));

        //$this->notifySubscribers($reply);

        return $reply;
    }

    // public function notifySubscribers($reply){

    //     foreach ($this->subscriptions as $subscription) {
            
    //         if($subscription->user_id !=  $reply->user_id){

    //             $subscription->user->notify( new ThreadWasUpdated($this, $reply));
                
    //             \Log::info('adding into notfications table');

    //             //$subscription->user->notify( $reply);
                
    //         }
         

    //     }

    // }

    public function channel(){

        return $this->belongsTo(ForumChannel::class, 'forum_channel_id');
    }

    // public static function boot(){
    //     parent::boot();

    //     static::deleting(function($thread){
    //         $thread->replies->delete();
    //     });
    // }

    public function subscribe(){

        $this->subscriptions()->create([

            'user_id' => Auth::id()

        ]);

    }

    public function unsubscribe(){
        
        $user_id = Auth::id();
        
        return $this->subscriptions()->where('user_id', $user_id)->delete();

    }

    public function subscriptions(){

       return  $this->hasMany(ForumThreadSubscription::class);

    }

    public function getIsSubscribedToAttribute(){
      
        $user_id = Auth::id();

        $exists =  $this->subscriptions()
                    ->where('user_id',$user_id)
                    ->exists();

        return $exists ? 1 : 0;
        
    }

    public function hasUpdatesFor($user = null){

       

        // $user = $user ?: Auth::user();

        // $key = sprintf("users.%s.visits.%s", Auth::id(), $this->id);

        $key = Auth::user()->visitedThreadCacheKey($this);

        return $this->updated_at > cache($key);

    }

    public function visits(){

        return new Visits($this);
    }

    public function setSlugAttribute($value){

        if(static::whereSlug($value)->exists()){
            
            $latestModelId = Static::max('id');

            $this->attributes['slug'] = $value . '-' . ++$latestModelId;
            
        }else{

            $this->attributes['slug'] = $value;

        }
       
    }

    public function markAsBest(ForumReply $reply){
        
        $this->update([
            'best_forum_reply_id' => $reply->id
        ]);
    }

    public function recentReply(){
        
        return $this->replies()->latest()->first();
    }
}
