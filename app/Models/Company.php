<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $fillable= ["name","partner_id"];

    protected static function boot()
    {
        parent::boot();
        
        static::deleting(function ($company) {
            foreach ($company->shops as $shop) {
                $shop->company_id = null;
                $shop->save();
            }
        });
    }

    public function shops(){
        return $this->hasMany(Shop::class);
    }
    public function partner(){
        return $this->belongsTo(Partner::class);
    }

}
