<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Scheme extends Model
{
    protected $guarded = [];
    protected $dates = ['create_at', 'updated_at', 'date'];
    
    public function category() {
        return $this->belongsTo(SchemeCategory::class, 'scheme_category_id');
    }

    public function company() {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function setDateAttribute($date)
    {
        return $this->attributes['date'] = Carbon::createFromFormat('d-M-Y', $date)->format('Y-m-d');
    }

    public function setSalePriceAttribute($price) {
        if(!is_numeric($price)) {
            $this->attributes['sale_price'] = null;
        } else {
            $this->attributes['sale_price'] = (float)$price;
        }
    }

    public function setRepurchasePriceAttribute($price) {
        if(!is_numeric($price)) {
            $this->attributes['repurchase_price'] = null;
        } else {
            $this->attributes['repurchase_price'] = (float)$price;
        }
    }

    public function setNetValueAttribute($net) {
        if(!is_numeric($net)) {
            $this->attributes['net_value'] = null;
        } else {
            $this->attributes['net_value'] = (float)$net;
        }
    }
}
