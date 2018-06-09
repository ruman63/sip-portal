<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SipSchedule extends Model
{
    protected $dates = ['date'];

    protected $casts = [
        'executed' => 'boolean'
    ];
    
    protected $guarded = [];

    public function scopePending($query)
    {
        return $query->where('executed', false);
    }

    public function sip()
    {
        return $this->belongsTo(Sip::class);
    }

    public function execute()
    {
        $txn = Transaction::create([
            'uid' => 'SIP' . $this->sip->id . '-' . $this->id,
            'folio_no' => $this->sip->folio_no,
            'type' => 'SIP',
            'date' => $this->due_date, 
            'client_id' => $this->sip->client_id,
            'scheme_code' => $this->sip->scheme_code,
            'rate' => $this->sip->scheme->nav,
            'amount' => $this->sip->amount,
        ]);

        if($txn) {
            $this->update(['executed' => true]);
        }

        return $txn;
    }
}
