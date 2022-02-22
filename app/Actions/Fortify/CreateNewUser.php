<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'timezone' => ['required', 'string', 'timezone'],
            'password' => $this->passwordRules(),
        ])->validate();

        $locale = substr(request()->server('HTTP_ACCEPT_LANGUAGE'), 0, 2);

        if (!in_array($locale, config('app.available_locales'))) {
            $locale = config('app.fallback_locale');
        }

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'timezone' => $input['timezone'],
            'password' => Hash::make($input['password']),
            'locale' => $locale,
        ]);
    }
}
