<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Client extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected $appends = ['name'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function transactions()
    {
        return $this->hasManyThrough('App\Transaction', 'App\Folio');
    }
    
    public function folios()
    {
        return $this->hasMany('App\Folio');
    }
    
    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
