<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopifyAppEvent extends Model
{
    use HasFactory;

    protected $fillable = ["occurred_at","type","app_id","shop_id"];

    public function app(){
        return $this->belongsTo(ShopifyApp::class);
    }
    public function shops(){
        return $this->hasMany(Shop::class);
    }
}
