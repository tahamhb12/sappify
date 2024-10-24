<?php

namespace App\Models;

use App\Models\Scopes\CheckRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;
    protected $fillable = ["partner_id","name","api_key","user_id"];

    public function shopifyApps(){
        return $this->hasMany(ShopifyApp::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function companies(){
        return $this->hasMany(Company::class);
    }

}
