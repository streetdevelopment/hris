<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NoDepartmentInName implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Check if 'department' is in the dep_name
        return !str_contains(strtolower($value), 'department');
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute cannot contain the word "department".';
    }
}
