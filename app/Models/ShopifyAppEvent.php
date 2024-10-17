<?php

namespace App\Models;

use App\Models\Scopes\CheckRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopifyAppEvent extends Model
{
    use HasFactory;

    protected $fillable = ["occurred_at","type","app_id","shop_id","partner_id"];

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
