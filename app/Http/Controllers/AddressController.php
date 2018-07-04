<?php

namespace App\Http\Controllers;

class AddressController extends Controller
{
    public function store()
    {
        $user = auth()->guard('web')->user();
        if ($user->address()->count()) {
            return response()->json(['message' => 'You already have an address, please modify it!'], 400);
        }

        $address = $user->address()->create(request()->all());

        return [
            'message' => 'Success',
            'data' => $address,
        ];
    }

    public function update()
    {
        $client = auth()->guard('web')->user();
        if ($client->address()->update(request()->all())) {
            return [
                'message' => 'Success',
                'data' => $client->address,
            ];
        }

        return response()->json(['message' => 'Failed!'], 304);
    }
}
