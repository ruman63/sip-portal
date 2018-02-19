<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $guarded = [];

    public function schemes()
    {
        return $this->hasMany(Scheme::class, 'company_id');
    }
}
