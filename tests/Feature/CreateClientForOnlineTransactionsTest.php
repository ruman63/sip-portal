<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\BseAdditionalServicesClient;
use App\UccAccount;

class CreateClientForOnlineTransactionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function when_admin_creates_ucc_for_a_client_it_should_persisit_in_database_for_online_transactions()
    {
        $this->signInAdmin();
        $client = create('App\Client');
        $data = [];

        $mock = \Mockery::mock(BseAdditionalServicesClient::class);
        $mock->shouldReceive('getPassword')
            ->with([
                'UserId' => '1821101',
                'MemberId' => '18211',
                'Password' => '123456',
                'PassKey' => 'someKey',
            ])->once()->andReturn(['100', 'somePassword'])
            ->shouldReceive('MFAPI')->with([
                'Flag' => '02',
                'UserId' => '1821101',
                'encryptedPassword' => 'somePassword',
                'param' => implode('|', $data),
            ])->once()->andReturn(['100', 'Client Created Successfully']);

        app()->instance(BseAdditionalServicesClient::class, $mock);

        $this->postJson(route('admin.clients-ucc.store', $client), $data);

        \Mockery::close();

        $this->assertEquals(pow(10, 5) + $client->id, UccAccount::first()->ucc);
    }
}
