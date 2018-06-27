<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\BseOrderEntryClient;

class BseOrderEntryClientTest extends TestCase
{
    /** @test */
    public function use_getPassword_method()
    {
        $client = new BseOrderEntryClient();
        $response = $client->getPassword([
            'UserId' => 1821101,
            'Password' => '123456',
            'PassKey' => str_random(7),
        ]);

        $this->assertEquals(100, $response[0], "ERROR {$response[0]}: {$response[1]}");
    }

    /** @test */
    public function use_orderEntryParam_method()
    {
        $client = new BseOrderEntryClient();
        $password = $client->getPassword([
            'UserId' => 1821101,
            'Password' => '123456',
            'PassKey' => $pass = str_random(7),
        ])[1];
        $response = $client->orderEntryParam([
            'TransCode' => 'NEW',
            'OrderID' => '',
            'TransNo' => strtotime(now()->toDateTimeString()),
            'UserID' => '1821101',
            'MemberId' => '18211',
            'ClientCode' => 'SW1',
            'SchemeCd' => '02-DP',
            'BuySell' => 'P',
            'BuySellType' => 'FRESH',
            'DPTxn' => 'C',
            'OrderVal' => '4000',
            'Qty' => '',
            'AllRedeem' => 'N',
            'FolioNo' => '',
            'Remarks' => '',
            'KYCStatus' => 'Y',
            'RefNo' => '',
            'SubBrCode' => '',
            'EUIN' => 'E123456',
            'EUINVal' => 'Y',
            'MinRedeem' => 'N',
            'DPC' => 'Y',
            'IPAdd' => '',
            'Password' => $password,
            'PassKey' => $pass,
            'Param1' => '',
            'Param2' => '',
            'Param3' => '',
        ]);
        $this->assertNotEquals('0', $response[2], $response[6]);
    }
}
