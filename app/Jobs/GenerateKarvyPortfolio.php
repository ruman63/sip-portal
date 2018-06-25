<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Client;
use App\Scheme;
use Illuminate\Support\Carbon;
use App\Transaction;

class GenerateKarvyPortfolio implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data->groupBy('pan1');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->data->each(function ($transactions, $pan) {
            $client = Client::where('pan', $pan)->first() ?? $this->createClient($transactions->first()['investor name'], $pan);
            $transactions->each(function ($transaction) use ($client) {
                $this->createTransaction($transaction, $client);
            });
        });
    }

    private function createClient($name, $pan)
    {
        $name = explode(' ', $name);
        $first_name = array_shift($name);
        $last_name = implode(' ', $name);
        $password = bcrypt($pan);
        return Client::create(compact('first_name', 'last_name', 'pan', 'password'));
    }

    private function createTransaction($transaction, $client)
    {
        $scheme = Scheme::where('isin', $transaction['isin'])->first();
        if (!$scheme || Transaction::where('uid', $transaction['transaction id'])->exists()) {
            return;
        }
        return $scheme->transactions()->create([
            'uid' => $transaction['transaction id'],
            'folio_no' => $transaction['folio number'],
            'type' => $transaction['transaction type'],
            'date' => Carbon::parse($transaction['transaction date'])->format('Y-m-d'),
            'amount' => $transaction['amount'],
            'rate' => $transaction['price'],
            'client_id' => $client->id,
        ]);
    }
}
