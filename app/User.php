<?php

namespace App;

use Laravel\Spark\User as SparkUser;
use App\ForumThread;
use App\ForumActivity;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;
use App\ForumInfo;

class User extends SparkUser
{

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
    ];

    public function getRouteKeyName(){

        return 'name';
    }

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'authy_id',
        'country_code',
        'phone',
        'card_brand',
        'card_last_four',
        'card_country',
        'billing_address',
        'billing_address_line_2',
        // 'billing_city',
        'billing_zip',
        // 'billing_country',
        'extra_billing_information',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'trial_ends_at' => 'datetime',
        'uses_two_factor_auth' => 'boolean',
    ];

    public function threads(){

        return $this->hasMany(ForumThread::class);

    }

    public function activity(){
        return $this->hasMany(ForumActivity::class);
    }

    public function read($thread){

        cache()->forever(
            
            $this->visitedThreadCacheKey($thread),

            Carbon::now()
        );
        

    }

    public function visitedThreadCacheKey($thread){

      return sprintf("users.%s.visits.%s", $this->id, $thread->id);
    }

    public function lastReply(){

        return $this->hasOne(ForumReply::class)->latest();
        
    }

    public function forumInfo(){

        return $this->hasOne(ForumInfo::class);

    }

    public function updateExperience($incrementBy){

        if ($this->forumInfo != NULL) {

                $user_experience = $this->forumInfo->experience;
            
                $this->forumInfo->update([

                'experience' => $user_experience + $incrementBy

            ]);
        }else { 
            
            $this->forumInfo()->create([
                'user_id' => $this->id,
                
                'experience' => $incrementBy
            ]);
        }

       return true;

    }
}
