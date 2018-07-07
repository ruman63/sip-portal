<?php

namespace App\Http\Controllers;

use App\Client;

class ProfileController extends Controller
{
    public function show()
    {
        $client = Client::where('id', auth()->guard('web')->id())
            ->with(['address', 'bankAccounts', 'defaultBankAccount'])
            ->firstOrFail();
        return view('profile.show', compact('client'));
    }

    public function update()
    {
        $client = auth()->guard('web')->user();

        $client->update(
            $data = request()->only([
                'first_name', 'last_name', 'dob', 'gender', 'email', 'pan', 'mobile',
                'guardian', 'guardian_pan', 'nominee', 'nominee_relation',
            ])
        );

        return [
            'message' => 'Updated successfully!',
            'data' => $data,
        ];
    }
}
