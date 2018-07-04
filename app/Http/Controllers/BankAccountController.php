<?php

namespace App\Http\Controllers;

use App\Exceptions\MaximumBankAccountsReachedException;
use App\BankAccount;

class BankAccountController extends Controller
{
    public function store()
    {
        $client = auth()->guard('web')->user();

        try {
            $bankAccount = $client->addBankAccount(
                request()->all()
            );

            return [
                'message' => 'Success',
                'data' => $bankAccount,
            ];
        } catch (MaximumBankAccountsReachedException $e) {
            return response()->json(['message' => 'You can add only 5 accounts.'], 400);
        }
    }

    public function update(BankAccount $bankAccount)
    {
        if ($bankAccount->owner_id != auth()->guard('web')->id()) {
            return response()->json(['message' => 'You dont own this Bank Account!'], 403);
        }

        $data = request()->only(['account_number', 'account_type_code', 'ifsc_code', 'micr']);

        $bankAccount->update($data);

        return [
            'message' => 'Updated!',
            'data' => $bankAccount->fresh(),
        ];
    }
}
