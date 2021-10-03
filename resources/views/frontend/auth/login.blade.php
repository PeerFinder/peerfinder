<x-layout.auth :title="__('auth.login_title')">
    <x-auth.card>
        <x-slot name="title">
            <x-auth.headline>{{ __('auth.sign_in_in_your_account') }}</x-auth.headline>
        </x-slot>
        
        <x-auth.status :status="session('status')" />

        <x-auth.form action="{{ route('login') }}" class="space-y-4">
            <div>
                <x-ui.forms.input id="email" value="{{ old('email') }}" name="email" type="email" autocomplete="email" required>{{ __('auth.field_email') }}</x-ui.forms.input>
            </div>
            <div>
                <x-ui.forms.input id="password" name="password" type="password" autocomplete="current-password" required>{{ __('auth.field_password') }}</x-ui.forms.input>
            </div>
            <div class="flex items-center">
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
        </x-auth.form>

        @if (Route::has('register'))
        <x-slot name="aftercard">
            <div class="p-4 flex justify-between items-center">
                <div class="flex-1 text-center font-semibold">{{ __('auth.no_account') }}</div>
                <x-ui.forms.button tag="a" action="create" href="{{ route('register') }}">{{ __('auth.sign_up_new_account') }}</x-ui.forms.button>
            </div>
        </x-slot>
        @endif
    </x-auth.card>
</x-layout.auth>