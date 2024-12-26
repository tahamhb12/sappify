<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Earning extends Model
{
    protected $fillable = ["user_id","earnings","type","billing_event_id"];

    public function user(){
        $this->belongsTo(User::class);
    }

    public function billingEvent(){
        return $this->belongsTo(BillingEvents::class);
    }

}
