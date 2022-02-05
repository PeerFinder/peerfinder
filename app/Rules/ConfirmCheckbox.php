<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ConfirmCheckbox implements Rule
{
    private $attribute;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($error_message)
    {
        $this->error_message = $error_message;
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
        return $value === '1';
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->error_message;
    }
}
