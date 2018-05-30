<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $appends = ['currentValue'];
    protected $guarded = [];

    public static function boot(){
        static::creating(function($transaction) {
            $transaction->units = $transaction->amount/$transaction->rate;
        });
    }

    public function folio() {
        return $this->belongsTo('App\Folio');
    }
    
    public function scheme()
    {
        return $this->belongsTo('App\Scheme', 'scheme_code', 'scheme_code');
    }

    public function getCurrentValueAttribute() {
        return $this->units * $this->scheme->nav;
    }
}
