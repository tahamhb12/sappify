<?php

namespace App\Models;

use App\Models\Scopes\CheckRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = ["shop_id","avatarUrl","name","myshopifyDomain","partner_id","tags","notes","description"];

    protected $table = 'shops';

    public function apps(){
        return $this->belongsToMany(ShopifyApp::class,'app_shop', 'shop_id', 'app_id');
    }
    public function events(){
        return $this->hasMany(ShopifyAppEvent::class);
    }
    public function partner(){
        return $this->belongsTo(Partner::class);
    }

}
