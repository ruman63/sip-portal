<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\BseStar\CodesLookup;

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
        return $this->hasMany('App\Transaction');
    }

    public function changePassword($newPassword)
    {
        return $this->update([ 'password' => bcrypt($newPassword) ]);
    }
    
    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
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
