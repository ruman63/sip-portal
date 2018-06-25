<?php

namespace Tests\Unit;

use App\Client;
use App\Scheme;
use Tests\TestCase;
use App\Transaction;
use App\Parsers\CSV;
use App\Jobs\GenerateKarvyPortfolio;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GenerateKarvyPortfoliosTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_generates_clients_logins_and_their_folios_according_to_provided_csv()
    {
        $data = CSV::read(stubs_path('sample_karvy.csv'))->get();

        $schemes = $data->pluck('isin')->map(function ($channel_code) {
            return create(Scheme::class, ['isin' => $channel_code]);
        });

        $job = new GenerateKarvyPortfolio($data);

        $job->handle();

        $this->assertEquals($data->unique('pan1')->count(), Client::count());
        $this->assertEquals($data->unique('transaction id')->count(), Transaction::count());

        $counts = $data->groupBy('pan1')->map->count()->take(3);

        $counts->each(function ($count, $pan) {
            $this->assertNotNull($client = Client::with('transactions.scheme')->where('pan', $pan)->first());
            $this->assertCount($count, $client->transactions);
            $client->transactions->pluck('scheme')->assertHasInstancesOf(Scheme::class);
        });
    }

    /** @test */
    public function if_a_transaction_already_existed_it_leaves_txn_as_it_is()
    {
        $oldTxn = create(Transaction::class, ['uid' => '1234567']);
        $data = CSV::read(stubs_path('sample_karvy.csv'))->get()->take(5);
        $newTxn = $data->first();
        $newTxn['transaction id'] = '1234567';
        $data->push($newTxn);
        $schmes = $data->pluck('isin')->map(function ($isin) {
            return create(Scheme::class, ['isin' => $isin]);
        });
        $job = new GenerateKarvyPortfolio($data);

        $job->handle();

        $this->assertEquals($data->count(), Transaction::count());
    }
}
