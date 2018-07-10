<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $guarded = [];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($address) {
            if (!$address->country) {
                $address->country = 'India';
            }
        });
    }
}
