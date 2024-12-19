<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;


class User extends Authenticatable implements FilamentUser, HasTenants
{
    use HasFactory, Notifiable;

    public function getTenants(Panel $panel): Collection
    {
        return $this->partners;
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return $this->partners()->whereKey($tenant)->exists();
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'referral_code'
    ];

    public static function generateUniqueReferralCode(): string
    {
        do {
            $referralCode = Str::upper(Str::random(8));
        } while (self::where('referral_code', $referralCode)->exists());

        return $referralCode;
    }
    protected static function booted()
    {
        static::creating(function ($user) {
            if ($user->role === 'affiliate') {
                $user->referral_code = self::generateUniqueReferralCode();
            }
        });
    }


    public function affiliatePrograms(){
        return $this->belongsToMany(AffiliateProgram::class,'affiliate_user');
    }

    public function partners()
    {
        return $this->hasMany(Partner::class);
    }
    public function referrals()
    {
        return $this->hasMany(Referral::class);
    }

    public function ShopifyApps()
    {
        return $this->hasMany(ShopifyApp::class);
    }

    public function Events()
    {
        return $this->hasMany(ShopifyAppEvent::class);
    }

    public function shops()
    {
        return $this->hasMany(Shop::class);
    }

    public function earnings(){
        $this->hasMany(Earning::class);
    }


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
