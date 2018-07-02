<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Client;
use App\UccAccount;
use App\BseAdditionalServicesClient;

class UccAccountsController extends Controller
{
    public function store(Client $client, BseAdditionalServicesClient $bseClient)
    {
        $encPassword = $bseClient->getPassword([
            'UserId' => '1821101',
            'MemberId' => '18211',
            'PassKey' => 'someKey',
            'Password' => '123456',
        ]);
        $bseClient->MFAPI([
            'Flag' => '02',
            'UserId' => '1821101',
            'encryptedPassword' => $encPassword[1],
            'param' => implode('|', request()->all()),
        ]);

        return UccAccount::create([
            'ucc' => pow(10, 5) + $client->id,
        ]);
    }
}
