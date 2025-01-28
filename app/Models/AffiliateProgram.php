<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliateProgram extends Model
{
    protected $fillable=["unique_id","app_url","commission_rate","amount_per_install","min_payout","sign_up_page","app_id","partner_id"];


    protected $table = 'affiliate_programs';

    public function app(){
        return $this->belongsTo(ShopifyApp::class);
    }
    public function partner(){
        return $this->belongsTo(Partner::class);
    }
    public function users(){
        return $this->belongsToMany(User::class,'affiliate_user')->using(AffiliateUser::class);
    }
    public function referrals()
    {
        return $this->hasMany(Referral::class);
    }

    public function payouts(){
        return $this->hasMany(Payout::class);
    }
}
