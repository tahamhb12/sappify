<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Earning extends Model
{
    protected $fillable = ["user_id","earnings"];

    public function user(){
        $this->belongsTo(User::class);
    }

}
