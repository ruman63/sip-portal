<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //personal
            'name' => 'required',
            'pan' => 'required',
            'mapin' => 'required',
            'dob' => 'required',
            'gender' => 'required',
            'occupation' => 'required',
            'tax_status' => 'required',
            'father_name' => 'required',
            'guardian_name' => 'required',
            'guardian_pan' => 'required',
            
            //contact
            'hno' => 'required',
            'area' => 'required',
            'city' => 'required',
            'pincode' => 'required',
            'mobile' => 'required',
            'email' => 'required',
            'communication_mode' => 'required',
    
            //account
            'account_type' => 'required',
            'pay_mode' => 'required',
            'nominee_name' => 'required',
            'nominee_relation' => 'required',

            'bank_account.account_number' => 'required',
            'bank_account.account_type' => 'required',
            'bank_account.bank' => 'required',
            'bank_account.branch' => 'required',
            'bank_account.ifsc' => 'required',
            'bank_account.micr' => 'required',
        ];
    }

    public function persist()
    {
        $bankAccount = \App\BankAccount::create(request('bank_account'));

        $client = collect($this->rules())
                    ->keys()
                    ->filter( function($key) {
                        return !starts_with($key, 'bank_account');
                    })->toArray();

        $password = str_limit(md5($this->email . str_random('7')), 10, ''); 
        
        \App\Client::create(request()->only($client) + [
            'bank_account_number' => $bankAccount->account_number,
            'password' => bcrypt($password),
        ]);

        return $password;
    }
}
