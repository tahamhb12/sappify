<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopifyAppEvent extends Model
{
    use HasFactory;



    protected $fillable = ['occurred_at', 'type', 'app_id', 'shop_id', 'partner_id', 'description', 'reason'];

    public static $EVENT_TYPE_MAPPING = [
        'RELATIONSHIP_DEACTIVATED' => 'App Deactivated',
        'RELATIONSHIP_INSTALLED' => 'App Installed',
        'RELATIONSHIP_REACTIVATED' => 'App Reactivated',
        'RELATIONSHIP_UNINSTALLED' => 'App Uninstalled',
    ];

    public function app()
    {
        return $this->belongsTo(ShopifyApp::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function getTypeLabelAttribute()
    {
        return self::$EVENT_TYPE_MAPPING[$this->type];
    }
}
