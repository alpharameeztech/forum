<?php

namespace App;

use App\ForumFavorite;
use App\Scopes\ShopScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Traits\RecordForumActivity;
use App\ForumThread;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class ForumReply extends Model
{
    use RecordForumActivity;

    protected $guarded = [] ;

    protected $with = ['owner', 'favorites'];

    protected $appends = ['isFavorited', 'isBest'];

    protected static function boot(){

        parent::boot();

        /*
         * The "booting" method of the model.
         * Global Query scope
         *Adding the constraint of the shop id
         * @return void
         */
        static::addGlobalScope(new ShopScope);

        static::created(function ($reply){

            $reply->thread->increment('replies_count');

        });

        static::deleted(function ($reply){

            if($reply->isBest()){

                $reply->thread->update([
                    'best_forum_reply_id' => NULL
                ]);
            }

            $reply->thread->decrement('replies_count');

        });


    }

    public function owner(){

        // as i am using the owner name instead of user,
        // so i am being explicit for the model key which then i have specificed user_id
        return $this->belongsTo(User::class,'user_id');
    }

    public function favorites(){

        return $this->morphMany(ForumFavorite::class, 'favorited');
    }

    public function favorite(){

        $user_id = Auth::id();
        //if the no record found for the sma user id  for the sam reply then make it favorite
        if(!$this->favorites()->where(['user_id' => $user_id ])->exists() ){
            $this->favorites()->create([
                'shop_id' => Cache::get('shop_id'),
                'user_id' => $user_id
            ]);
        }

    }

    public function isFavorited(){

        return $this->favorites()->where('user_id', Auth::id())->exists();
    }

    public function getIsFavoritedAttribute(){

        return $this->isFavorited();
    }

    public function thread(){

        return $this->belongsTo(ForumThread::class, 'forum_thread_id');
    }

    public function path(){

        return $this->thread->path() . "#reply-{$this->id}";

    }

    public function unFavorite(){

        $attributes = ['user_id' =>  Auth::id()];

        //$this->favorites()->where($attributes)->delete();

        // delete the favorited replies from the activity table if deleting from the favorites table
        $this->favorites()->where($attributes)->get()->each(function ( $favorite){

            $favorite->delete();
        });
    }

    public function wasJustPublished(){

        return $this->created_at->gt(Carbon::now()->subMinute()); //if that is more recent than a minute ago return boolean
    }

    // public function setBodyAttribute($body){

    //     try {
    //         $this->attributes['body'] = preg_replace('/@([\w\-]+)/','<a href="/forum/profiles/$1">$0</a>', $body);
    //     } catch (\Exception $e) {
    //         \Log::info($e);

    //     }

    // }

    public function isBest(){

        return $this->thread->best_forum_reply_id == $this->id;

    }

    public function getIsBestAttribute(){

        return $this->isBest();
    }
}
