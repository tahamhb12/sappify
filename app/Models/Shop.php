<?php

namespace App\Models;

use App\Models\Scopes\CheckRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = ["shop_id","avatarUrl","name","myshopifyDomain","partner_id","tags","notes","description","status","company_id","title","image"];

    protected $table = 'shops';


    public function apps(){
        return $this->belongsToMany(ShopifyApp::class,'app_shop', 'shop_id', 'app_id');
    }
    public function BillingEvents(){
        return $this->hasMany(BillingEvents::class);
    }
    public function Appevents(){
        return $this->hasMany(ShopifyAppEvent::class);
    }
    public function transactionEvents(){
        return $this->hasMany(TransactionEvent::class);
    }
    public function partner(){
        return $this->belongsTo(Partner::class);
    }
    public function company(){
        return $this->belongsTo(Company::class);
    }

}
