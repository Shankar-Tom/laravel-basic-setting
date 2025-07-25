<?php

namespace Shankar\LaravelBasicSetting\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidPhone implements Rule
{
    public function passes($attribute, $value)
    {
        return preg_match('/^(\+\d{1,3}[- ]?)?\(?\d{2,4}\)?[- ]?\d{2,4}[- ]?\d{2,4}$/', str_replace('-', '', $value));
    }

    public function message()
    {
        return 'The :attribute is not a valid phone number.';
    }
}
