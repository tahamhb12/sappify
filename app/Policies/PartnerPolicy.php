<?php

namespace App\Policies;

use App\Models\Partner;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PartnerPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return Auth::check() && Auth()->user()->role == 'admin' ? true : false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Partner $partner): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        /*         if(Auth::user()->role == "user"){
                    $partner->user_id= Auth::user()->id;
                } */
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Partner $partner): bool
    {
        return $partner->user_id == $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Partner $partner): bool
    {
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Partner $partner): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Partner $partner): bool
    {
        return true;
    }
}
