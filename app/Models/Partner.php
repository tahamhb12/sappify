<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;
    protected $fillable = ["partner_id","name","api_key"];

    protected $table = "partners";
    protected $primaryKey = "partner_id";
    public $incrementing = false;
    protected $keyType = 'string';

    public function shopifyApps(){
        return $this->hasMany(ShopifyApp::class);
    }
}
