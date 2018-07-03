<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Exceptions\MaximumBankAccountsReachedException;

class BankAccount extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($bankAccount) {
            if ($bankAccount->owner->bankAccounts()->count() >= 5) {
                throw new MaximumBankAccountsReachedException();
            }
        });

        static::created(function ($bankAccount) {
            if (!$bankAccount->owner->defaultBankAccount) {
                $bankAccount->setAsDefault();
            }
        });
    }

    public function owner()
    {
        return $this->belongsTo('App\Client', 'owner_id');
    }

    public function setAsDefault()
    {
        return $this->owner->updateDefaultBank($this);
    }
}
