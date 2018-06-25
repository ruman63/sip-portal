<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\BseSoapClient;

class BseSoapClientTest extends TestCase
{
    /** @test */
    public function use_getPassword_method()
    {
        $client = new BseSoapClient();
        $response = $client->getPassword([
            'UserId' => 1775602,
            'Password' => 'sipwealth123',
            'PassKey' => str_random(7),
        ]);

        $this->assertEquals(100, $response[0], "ERROR {$response[0]}: {$response[1]}");
    }
}
