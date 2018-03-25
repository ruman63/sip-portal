<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Folio extends Model
{
    protected $guarded = [];

    public function client()
    {
        return $this->belongsTo('App\Client', 'client_id');
    }

    public function scheme()
    {
        return $this->belongsTo('App\Scheme', 'scheme_code', 'scheme_code');
    }
}
