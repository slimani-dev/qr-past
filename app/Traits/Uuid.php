<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait Uuid
{
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            try {
                $model->id = (string)Str::uuid(); // generate uuid
                // Change id with your primary key
            } catch (\Exception $e) {
                abort(500, $e->getMessage());
            }
        });
    }
}
