<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopifyApp extends Model
{
    use HasFactory;

    protected $fillable = ["app_id","name","api_key","partner_id"];

    protected $table = 'shopify_apps';
    protected $primaryKey = 'app_id';
    public $incrementing = false;
    protected $keyType = 'string';


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
