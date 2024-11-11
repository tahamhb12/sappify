<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = ['shop_id', 'avatarUrl', 'name', 'myshopifyDomain', 'partner_id', 'tags', 'notes', 'description', 'status', 'company_id', 'title', 'image'];

    protected $table = 'shops';

    public function apps()
    {
        return $this->belongsToMany(ShopifyApp::class, 'app_shop', 'shop_id', 'app_id');
    }

    public function BillingEvents()
    {
        return $this->hasMany(BillingEvents::class);
    }

    public function AppEvents()
    {
        return $this->hasMany(ShopifyAppEvent::class);
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
