<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = [];

    protected $appends = ['units'];

    public function folio() {
        return $this->belongsTo('App\Folio');
    }


    public function getUnitsAttribute()
    {
        return $this->amount/$this->rate;
    }
}
