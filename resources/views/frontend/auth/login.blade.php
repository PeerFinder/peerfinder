<x-layout.auth title="{{ __('auth.login_title') }}">
    <x-auth.card>
        <x-slot name="title">
            <x-auth.headline>{{ __('auth.sign_in_in_your_account') }}</x-auth.headline>

            @if (Route::has('register'))
            <p>{{ __('auth.or') }}
                <x-ui.link href="{{ route('register') }}" class="font-bold">{{ __('auth.sign_up_new_account') }}</x-ui.link>
            </p>
            @endif
        </x-slot>

        <form action="{{ route('login') }}" method="post" class="space-y-4 p-10">
            <div>
                <x-ui.forms.input id="email" value="{{ old('email') }}" name="email" type="email" autocomplete="email" XXrequired>{{ __('auth.field_email') }}</x-ui.forms.input>
            </div>
            <div>
                <x-ui.forms.input id="password" name="password" type="password" autocomplete="current-password" XXrequired>{{ __('auth.field_password') }}</x-ui.forms.input>
            </div>
            <div class="flex items-center space-x-2">
                <x-ui.forms.checkbox id="remember" name="remember">{{ __('auth.field_remember_me') }}</x-ui.forms.checkbox>
            </div>
            <div class="text-center">
                @csrf
                <x-ui.forms.button class="w-full">{{ __('auth.button_login') }}</x-ui.forms.button>
                @if (Route::has('password.request'))
                <div class="mt-4">
                    <x-ui.link href="{{ route('password.request') }}">{{ __('auth.forgot_password') }}</x-ui.link>
                </div>
                @endif
            </div>
        </form>
    </x-auth.card>
</x-layout.auth>