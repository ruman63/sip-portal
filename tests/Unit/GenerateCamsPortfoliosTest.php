<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Transaction;
use App\Client;
use App\Jobs\GenerateCamsPortfolio;
use App\Parsers\CSV;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Scheme;

class GenerateCamsPortfoliosTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_genrates_clients_logins_and_their_folios_according_to_provided_csv()
    {
        $data = CSV::read(stubs_path('sample_cams.csv'))->get();
        $schmes = $data->pluck('prodcode')->map(function ($channel_code) {
            return create(Scheme::class, ['channel_partner_code' => $channel_code]);
        });
        $job = new GenerateCamsPortfolio($data);

        $job->handle();

        $this->assertEquals($data->unique('pan')->count(), Client::count());
        $this->assertEquals($data->unique('trxnno')->count(), Transaction::count());

        $counts = $data->groupBy('pan')->map->count()->take(3);

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
        $data = CSV::read(stubs_path('sample_cams.csv'))->get()->take(5);
        $newTxn = $data->first();
        $newTxn['trxnno'] = '1234567';
        $data->push($newTxn);
        $schmes = $data->pluck('prodcode')->map(function ($channel_code) {
            return create(Scheme::class, ['channel_partner_code' => $channel_code]);
        });
        $job = new GenerateCamsPortfolio($data);

        $job->handle();

        $this->assertEquals($data->count(), Transaction::count());
    }
}
