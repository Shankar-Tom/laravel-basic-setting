<?php

namespace Shankar\LaravelBasicSetting\Rules;

use Illuminate\Contracts\Validation\Rule;

class AlphaSpace implements Rule
{
    public function passes($attribute, $value)
    {
        return preg_match('/^[A-Za-z\s]+$/', $value);
    }

    public function message()
    {
        return 'The :attribute only allow alphabet and space';
    }
}
