<?php

namespace App\Models\Scopes;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class CheckRole implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        /* if(Auth::check() && Auth::user()->role !== 'admin') {
            // $builder->where('user_id', Auth::id());
        } */
    }
}
