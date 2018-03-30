<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Folio extends Model
{
    protected $guarded = [];

    protected $appends = ['units'];

    public function client()
    {
        return $this->belongsTo('App\Client', 'client_id');
    }

    public function scheme()
    {
        return $this->belongsTo('App\Scheme', 'scheme_code', 'scheme_code');
    }

    public function getUnitsAttribute()
    {
        if(! $this->purchase_price) {
            return null;
        }
        return $this->amount / $this->purchase_price ;
    }
}
