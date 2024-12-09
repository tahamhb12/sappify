<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingEvents extends Model
{
    use HasFactory;

    public static $EVENT_TYPE_MAPPING = [
        'CREDIT_APPLIED' => 'Credit Applied',
        'CREDIT_FAILED' => 'Credit Failed',
        'CREDIT_PENDING' => 'Credit Pending',
        'ONE_TIME_CHARGE_ACCEPTED' => 'One-Time Charge Accepted',
        'ONE_TIME_CHARGE_ACTIVATED' => 'One-Time Charge Activated',
        'ONE_TIME_CHARGE_DECLINED' => 'One-Time Charge Declined',
        'ONE_TIME_CHARGE_EXPIRED' => 'One-Time Charge Expired',
        'SUBSCRIPTION_APPROACHING_CAPPED_AMOUNT' => 'Subscription Approaching Cap',
        'SUBSCRIPTION_CAPPED_AMOUNT_UPDATED' => 'Subscription Cap Updated',
        'SUBSCRIPTION_CHARGE_ACCEPTED' => 'Subscription Charge Accepted',
        'SUBSCRIPTION_CHARGE_ACTIVATED' => 'Subscription Activated',
        'SUBSCRIPTION_CHARGE_CANCELED' => 'Subscription Canceled',
        'SUBSCRIPTION_CHARGE_DECLINED' => 'Subscription Charge Declined',
        'SUBSCRIPTION_CHARGE_EXPIRED' => 'Subscription Charge Expired',
        'SUBSCRIPTION_CHARGE_FROZEN' => 'Subscription Frozen',
        'SUBSCRIPTION_CHARGE_UNFROZEN' => 'Subscription Unfrozen',
        'USAGE_CHARGE_APPLIED' => 'Usage Charge Applied',
    ];

    protected $fillable = ['event_id', 'type', 'amount', 'currency', 'billingOn', 'name', 'isTest', 'app_id', 'partner_id', 'shop_id', 'occurred_at'];

    public function app()
    {
        return $this->belongsTo(ShopifyApp::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function getTypeLabelAttribute()
    {
        return self::$EVENT_TYPE_MAPPING[$this->type];
    }
}
