<?php

namespace App\Support\Traits;

use Illuminate\Support\Str;

trait Uuids
{
    /**
     * Boot function from Laravel.
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $column = $model->uuidColumn ?? 'uuid';
            if (empty($model->{$column})) {
                $model->{$column} = Str::uuid()->toString();
            }
        });
    }
}
