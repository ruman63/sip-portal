<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Folio extends Model
{
    protected $guarded = [];

    protected $appends = [
        'totalAmount', 
        'totalUnits', 
        'averageRate',
        'currentValue',
    ];

    public function client()
    {
        return $this->belongsTo('App\Client', 'client_id');
    }

    public function scheme()
    {
        return $this->belongsTo('App\Scheme', 'scheme_code', 'scheme_code');
    }

    public function transactions()
    {
        return $this->hasMany('App\Transaction');
    }

    public function getTotalAmountAttribute()
    {
        return $this->transactions()->sum('amount');
    }

    public function getTotalUnitsAttribute()
    {
        return $this->transactions()->sum('units');
    }

    public function getCurrentValueAttribute()
    {
        return $this->averageRate * $this->scheme->nav;
    }

    public function getAverageRateAttribute()
    {
        if(!$this->totalUnits) {
            return 0;
        }
        
        return $this->totalAmount / $this->totalUnits;
    }
}
