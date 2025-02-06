<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class Payout extends Model
{

    protected $fillable = ["amount","paypal_email","status","comment","affiliate_program_id"];

    protected static function booted()
    {

        static::creating(function ($payout) {
            $payout->user_id = Auth::id();
        });
    }

    public function affiliateProgram(){
        return $this->belongsTo(AffiliateProgram::class);
    }
    public function partner(){
        return $this->belongsTo(Partner::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
