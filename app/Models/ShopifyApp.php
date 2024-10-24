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

    public function AppEvents(){
        return $this->hasMany(ShopifyAppEvent::class,'app_id');
    }
    public function transactionEvents(){
        return $this->hasMany(TransactionEvent::class,'app_id');
    }
    public function shops(){
        return $this->belongsToMany(Shop::class,'app_shop', 'app_id', 'shop_id');
    }



}
