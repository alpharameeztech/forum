<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Billing extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at', 'activated_on', 'billing_on'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'shop_id',
        'shop_name',
        'shopify_billing_id',
        'price',
        'type',
        'status',
        'activated_on',
        'trial_days',
        'billing_on',
        'refunded'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shop()
    {
        return $this->belongsTo('App\Shop');
    }

    public function onTrial()
    {
        $now = Carbon::now();
        return $now->lessThan($this->billing_on);
    }
}
