<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class CurrentPassword implements Rule
{
    private $hash;
    
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($guard)
    {
        $this->hash = auth()->guard($guard)->user()->password;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return Hash::check($value, $this->hash); 
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Password is invalid.';
    }
}
