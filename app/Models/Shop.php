<?php

namespace App\Models;

use App\Models\Scopes\CheckRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = ["shop_id","avatarUrl","name","myshopifyDomain","partner_id"];

    protected $table = 'shops';

    public function App(){
        return $this->hasMany(ShopifyApp::class,"app_shop");
    }
    public function events(){
        return $this->hasMany(ShopifyAppEvent::class);
    }
    public function partner(){
        return $this->belongsTo(Partner::class);
    }

}
