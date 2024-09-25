<?php

namespace App\Models;

use App\Models\Scopes\CheckRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopifyAppEvent extends Model
{
    use HasFactory;

    protected $fillable = ["occurred_at","type","app_id","shop_id","user_id"];

    public function app(){
        return $this->belongsTo(ShopifyApp::class,"app_id","app_id");
    }
    public function shops(){
        return $this->hasMany(Shop::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    protected static function booted()
    {
        static::addGlobalScope(new CheckRole);
    }
}
