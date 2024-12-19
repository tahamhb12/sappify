<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class Referral extends Model
{
    protected $fillable = ["affiliate_program_id","user_id","note","customer_shop","date","is_approved"];

    protected static function booted()
    {

        static::creating(function ($referral) {
            $referral->user_id = Auth::id();
        });
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function partner(){
        return $this->belongsTo(Partner::class);
    }
    public function affiliateProgram(){
        return $this->belongsTo(AffiliateProgram::class);
    }
}
