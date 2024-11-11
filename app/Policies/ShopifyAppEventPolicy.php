<?php

namespace App\Policies;

use App\Models\ShopifyAppEvent;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ShopifyAppEventPolicy
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
    public function view(User $user, ShopifyAppEvent $shopifyAppEvent): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ShopifyAppEvent $shopifyAppEvent): bool
    {
        return Auth::check() && Auth::user()->role == 'admin' ? true : false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ShopifyAppEvent $shopifyAppEvent): bool
    {
        return Auth::check() && Auth::user()->role == 'admin' ? true : false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ShopifyAppEvent $shopifyAppEvent): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ShopifyAppEvent $shopifyAppEvent): bool
    {
        return Auth::check() && Auth::user()->role == 'admin' ? true : false;
    }
}
