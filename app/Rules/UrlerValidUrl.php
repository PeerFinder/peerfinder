<?php

namespace App\Rules;

use App\Helpers\Facades\Urler;
use Illuminate\Contracts\Validation\Rule;

class UrlerValidUrl implements Rule
{
    private $attribute;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        $this->attribute = $attribute;
        return Urler::validate($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.url', ['attribute' => $this->attribute]);
    }
}
