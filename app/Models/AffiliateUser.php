<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AffiliateUser extends Pivot
{
    protected $table = 'affiliate_user';
    protected $fillable = ['user_id', 'affiliate_program_id'];
}
