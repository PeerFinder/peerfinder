<?php

namespace Matcher\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;
use Matcher\Models\Peergroup;

class IsGroupMember implements Rule
{
    private $attribute;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Peergroup $pg, $error_message)
    {
        $this->error_message = $error_message;
        $this->pg = $pg;
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
        $user = User::where('username', $value)->first();
        return $this->pg->isMember($user);
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
