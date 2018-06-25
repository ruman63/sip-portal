<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\BseSoapClient;

class BseSoapClientTest extends TestCase
{
<<<<<<< Updated upstream
    public function setUp()
    {
        parent::setUp();

        $this->markTestSkipped('This test takes a lot of time... skipping for now.');

        $this->credentials = [
            'UserId' => '1821101',
            'Password' => '123@456',
        ];

        $this->bseClient = new BseSoapClient();
    }

    /** @test */
    public function getPassword_method_returns_proper_password()
    {
        $response = $this->bseClient->getPassword(
            $this->credentials + [
                'PassKey' => $passKey = str_random(10),
            ]
        )->getPasswordResult;

        $response = explode('|', $response);

        $this->assertEquals($response[0], 100, "Error: ${response[1]}");

        $response = $this->bseClient->Decrypt([
            'pwd' => $response[1],
        ]);

        $array = explode('|', $response->DecryptResult);
        $this->assertArraySubset(array_values($this->credentials), $array);
=======
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
>>>>>>> Stashed changes
    }
}
