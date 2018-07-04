<?php

namespace App;

use App\BseStar\CodesLookup;
use Illuminate\Database\Eloquent\Model;

class UccAccount extends Model
{
    protected $guarded = [];

    public function owner()
    {
        return $this->belongsTo(Client::class, 'owner_id');
    }

    public function getTaxStatusAttribute()
    {
        return CodesLookup::taxStatus($this->tax_status_code);
    }

    public function getOccupationAttribute()
    {
        return CodesLookup::occupation($this->occupation_code);
    }
}
