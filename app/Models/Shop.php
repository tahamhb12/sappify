<?php

namespace App\Models;

use App\Models\Scopes\CheckRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = ["shop_id","avatarUrl","name","myshopifyDomain","user_id"];

    protected $table = 'shops';
    protected $primaryKey = 'shop_id';
    public $incrementing = false;
    protected $keyType = 'string';

    public function App(){
        return $this->hasMany(ShopifyApp::class,"app_shop");
    }
    public function event(){
        return $this->belongsTo(ShopifyAppEvent::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    protected static function booted()
    {
        static::addGlobalScope(new CheckRole);
    }

}
