<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;

/**
 * Class Shop.
 *
 * @package namespace App;
 */
class Shop extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    /**
     * Helper property to keep free Pass service response
     * @var null
     */
    protected $freePassInfo = null;

    protected $fillable = [
        'name',
        'access_token',
        'shop_name',
        'email',
        'owner',
        'plan',
        'eoe_plan'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function users()
    {
        return $this->hasMany('App\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function admin()
    {
        return $this->users()->where('type', 'admin')->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function billing()
    {
        return $this->hasOne('App\Billing');
    }

    public function oldBills()
    {
        return $this->hasMany(Billing::class, 'shop_name', 'name');
    }

    public function lastBill()
    {
        return $this->oldBills()->withTrashed()->where('deleted_at', '!=', null)->get()->last();
    }

    /**
     * helper method to check if shop should get free access to our app
     * @return bool
     */
    public function shouldGetFreePass()
    {
        return false;

    }

    public function channels()
    {
        return $this->hasMany(ForumChannel::class);
    }

    public function threads()
    {
        return $this->hasMany(ForumThread::class);
    }

    public function associatedPlan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public function teamUserInvites()
    {
        return $this->hasMany(ForumInvite::class);
    }
}
