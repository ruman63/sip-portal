<?php

namespace App\Http\Controllers;

class AddressController extends Controller
{
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
