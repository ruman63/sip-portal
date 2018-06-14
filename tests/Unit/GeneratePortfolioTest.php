<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Transaction;
use App\Client;
use App\Jobs\GeneratePortfolios;
use App\Parsers\CSV;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GeneratePortfoliosTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_genrates_clients_logins_and_their_folios_according_to_provided_csv()
    {
        $columns = [
            'amc_code', 'folio_no', 'prodcode', 'scheme', 'inv_name',
            'trxntype', 'trxnno', 'traddate', 'purprice', 'units', 'amount', 'pan',
        ];

        $job = new GeneratePortfolios(
            $data = CSV::read(stubs_path('txns.csv'))->get()
        );

        $job->handle();

        $this->assertEquals($data->unique('pan')->count(), Client::count());
        $this->assertEquals($data->unique('trxnno')->count(), Transaction::count());

        $counts = $data->groupBy('pan')->map->count()->take(3);

        $counts->each(function ($count, $pan) {
            $this->assertNotNull($client = Client::where('pan', $pan)->first());
            $this->assertCount($count, $client->transactions);
        });
    }
}
