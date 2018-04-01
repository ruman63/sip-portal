<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = [];

    public function folio() {
        return $this->belongsTo('App\Folio');
    }
}
