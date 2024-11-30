<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AffiliateProgram extends Model
{
    protected $fillable=["unique_id","app_url","commission_rate","amount_per_install","min_payout","sign_up_page","app_id","partner_id"];

    public function app(){
        return $this->belongsTo(ShopifyApp::class);
    }
    public function partner(){
        return $this->belongsTo(Partner::class);
    }
}
