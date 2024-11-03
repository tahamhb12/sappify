<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingEvents extends Model
{
    use HasFactory;
    protected $fillable = ["event_id","type","amount","currency","billingOn","name","isTest","app_id","partner_id","shop_id","occurred_at"];

    public function app(){
        return $this->belongsTo(ShopifyApp::class);
    }
    public function shop(){
        return $this->belongsTo(Shop::class);
    }
    public function partner(){
        return $this->belongsTo(Partner::class);
    }
}
