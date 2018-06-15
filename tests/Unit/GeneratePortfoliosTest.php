<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Transaction;
use App\Client;
use App\Jobs\GeneratePortfolios;
use App\Parsers\CSV;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Scheme;

class GeneratePortfoliosTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_genrates_clients_logins_and_their_folios_according_to_provided_csv()
    {
        $data = CSV::read(stubs_path('txns.csv'))->get();
        $schmes = $data->pluck('prodcode')->map(function ($channel_code) {
            return create(Scheme::class, ['channel_partner_code' => $channel_code]);
        });
        $job = new GeneratePortfolios($data);

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
}
