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
        return $this->transactions->sum('currentValue');
    }

    public function getAverageRateAttribute()
    {
        if(!$this->totalUnits) {
            return 0;
        }
        
        return $this->totalAmount / $this->totalUnits;
    }
}
