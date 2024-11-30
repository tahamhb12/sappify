<?php

namespace App\Policies;

use App\Models\AffiliateProgram;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class AffiliateProgramPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AffiliateProgram $affiliateProgram): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        $user = Auth::user();
        return $user->isAffiliated ? false :true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AffiliateProgram $affiliateProgram): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AffiliateProgram $affiliateProgram): bool
    {
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AffiliateProgram $affiliateProgram): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AffiliateProgram $affiliateProgram): bool
    {
        return true;
    }
}
