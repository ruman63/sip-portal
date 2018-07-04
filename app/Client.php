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
        return $this->hasMany('App\Transaction');
    }

    public function address()
    {
        return $this->hasOne('App\Address');
    }

    public function uccAccount()
    {
        return $this->hasOne(UccAccount::class, 'owner_id');
    }

    public function bankAccounts()
    {
        return $this->hasMany('App\BankAccount', 'owner_id');
    }

    public function defaultBankAccount()
    {
        return $this->belongsTo('App\BankAccount', 'default_bank_id');
    }

    public function updateDefaultBank($bankAccount)
    {
        if ($bankAccount->owner_id != $this->id) {
            return false;
        }
        $this->update(['default_bank_id' => $bankAccount->id]);
        return $bankAccount;
    }

    public function changePassword($newPassword)
    {
        return $this->update(['password' => bcrypt($newPassword)]);
    }

    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
