<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopifyApp extends Model
{
    use HasFactory;

    protected $fillable = ['app_id', 'name', 'api_key', 'partner_id', 'url', 'title', 'description', 'image'];

    protected $table = 'shopify_apps';

    public function update(array $attributes = [], array $options = [])
    {
        unset($attributes['api_key']);
        unset($attributes['app_id']);

        return parent::update($attributes, $options);
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }
    public function referral()
    {
        return $this->hasMany(Referral::class);
    }

    public function AppEvents()
    {
        return $this->hasMany(ShopifyAppEvent::class, 'app_id');
    }

    public function BillingEvents()
    {
        return $this->hasMany(BillingEvents::class, 'app_id');
    }

    public function shops()
    {
        return $this->belongsToMany(Shop::class, 'app_shop', 'app_id', 'shop_id')
            ->withPivot('status');
    }

    public function affiliateProgram()
    {
        return $this->hasOne(AffiliateProgram::class, 'app_id');
    }
}
