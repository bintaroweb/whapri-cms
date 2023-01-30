<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Throwable;

trait UserId
{
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            try {
                // $model->user_id = Auth::user()->parent_id;
            } catch (Throwable $e) {
                abort(500, $e->getMessage());
            }
        });
    }
}