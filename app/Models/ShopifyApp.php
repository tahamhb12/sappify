<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Filament\Facades\Filament;

class ShopifyApp extends Model
{
    use HasFactory;

    protected $fillable = ["app_id","name","api_key","partner_id"];

    protected $table = 'shopify_apps';




    public function partner(){
        return $this->belongsTo(Partner::class);
    }

    public function events(){
        return $this->hasMany(ShopifyAppEvent::class);
    }
    public function shops(){
        return $this->hasMany(Shop::class,"app_shop");
    }



}
