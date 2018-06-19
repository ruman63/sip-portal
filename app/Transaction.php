<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    // protected $appends = ['currentValue'];
    protected $guarded = [];

    public static function boot()
    {
        static::creating(function ($transaction) {
            $transaction->units = $transaction->rate == 0 ? 0 : $transaction->amount / $transaction->rate;
        });
        static::updating(function ($transaction) {
            $transaction->units = $transaction->rate == 0 ? 0 : $transaction->amount / $transaction->rate;
        });
    }

    public function client()
    {
        return $this->belongsTo('App\Client');
    }

    public function scheme()
    {
        return $this->belongsTo('App\Scheme', 'scheme_code', 'scheme_code');
    }

    public function getCurrentValueAttribute()
    {
        return $this->units * $this->scheme->nav;
    }

    public function getUnitsAttribute($units)
    {
        return round($units, 2);
    }

    public function getAmountAttribute($amount)
    {
        return round($amount, 2);
    }

    public function getRateAttribute($rate)
    {
        return round($rate, 2);
    }
}
