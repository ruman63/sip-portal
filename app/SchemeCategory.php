<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SchemeCategory extends Model
{
    protected $guarded = [];
    
    public function schemes() {
        return $this->hasMany(Scheme::class, 'scheme_category_id');
    }
}
