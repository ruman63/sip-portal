<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class Sip extends Model
{
    protected $table = 'sip';
    protected $dates = ['date'];
    protected $guarded = [];

    public function schedules()
    {
        return $this->hasMany(SipSchedule::class);
    }

    public function scheme() 
    {
        return $this->hasOne(Scheme::class, 'scheme_code', 'scheme_code');
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function generateSchedules()
    {
        return Collection::times($this->installments, function($times) {
            return $this->schedules()->create([
                'due_date' => $this->date->addMonths($times-1) 
            ]);
        });
    }
}
