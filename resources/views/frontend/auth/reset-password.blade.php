<x-layout.auth :title="__('auth.forgot_password_title')">
    <x-auth.card>
        <x-slot name="title">
            <x-auth.headline>{{ __('auth.reset_your_password') }}</x-auth.headline>
            <p>{{ __('auth.or') }}
                <x-ui.link href="{{ route('login') }}" class="font-semibold">{{ __('auth.sign_in') }}</x-ui.link>
            </p>
        </x-slot>

        <x-auth.status :status="session('status')" />

        <x-auth.form action="{{ route('password.update') }}" class="space-y-4">
            <div>
                <x-ui.forms.input id="email" value="{{ old('email', $request->email) }}" name="email" type="email" autocomplete="email" required>{{ __('auth.field_email') }}</x-ui.forms.input>
            </div>
            <div>
                <x-ui.forms.input id="password" name="password" type="password" autocomplete="new-password" required>{{ __('auth.field_password') }}</x-ui.forms.input>
            </div>
            <div>
                <x-ui.forms.input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required>{{ __('auth.field_password_confirmation') }}</x-ui.forms.input>
            </div>

            <div class="text-center">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}" />
                <x-ui.forms.button class="w-full">{{ __('auth.button_reset_password') }}</x-ui.forms.button>
            </div>
        </x-auth.form>
    </x-auth.card>
</x-layout.auth>