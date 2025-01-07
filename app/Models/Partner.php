<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = ['partner_id', 'name', 'api_key', 'user_id'];

    public function update(array $attributes = [], array $options = [])
    {
        unset($attributes['partner_id']);
        unset($attributes['api_key']);

        return parent::update($attributes, $options);
    }

    public function shopifyApps()
    {
        return $this->hasMany(ShopifyApp::class);
    }
    public function referrals()
    {
        return $this->hasMany(Referral::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function companies()
    {
        return $this->hasMany(Company::class);
    }

    public function affiliatePrograms()
    {
        return $this->hasMany(AffiliateProgram::class);
    }
    public function payouts()
    {
        return $this->hasMany(Payout::class);
    }
}
