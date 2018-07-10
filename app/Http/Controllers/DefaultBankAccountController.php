<?php

namespace App\Http\Controllers;

use App\BankAccount;

class DefaultBankAccountController extends Controller
{
    public function store(BankAccount $bankAccount)
    {
        $client = auth()->guard('web')->user();

        if ($client->updateDefaultBank($bankAccount)) {
            return ['message' => 'Success'];
        }

        return response()->json(['You dont own this Bank Account!'], 403);
    }
}
