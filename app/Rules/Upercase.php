<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;

class Upercase implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function passes($attribute, $value)
    {
        if ($value === mb_strtoupper($value, 'UTF-8')) {
            return true;
        }
        return false;
    }

    public function message()
    {
        return ':attribute phai duoc viet hoa.';
    }
}