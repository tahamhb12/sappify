<?php

namespace App\Models;

use App\Models\Scopes\CheckRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;
    protected $fillable = ["partner_id","name","api_key","user_id"];

    protected $table = "partners";
    protected $primaryKey = "partner_id";
    public $incrementing = false;
    protected $keyType = 'string';

    public function shopifyApps(){
        return $this->hasMany(ShopifyApp::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::addGlobalScope(new CheckRole);
    }
}
