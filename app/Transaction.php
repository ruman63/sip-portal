<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = [];

    public static function boot(){
        static::creating(function($transaction) {
            $transaction->units = $transaction->amount/$transaction->rate;
        });
    }

    public function folio() {
        return $this->belongsTo('App\Folio');
    }
}
