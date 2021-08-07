<x-layout.auth :title="__('auth.forgot_password_title')">
    <x-auth.card>
        <x-slot name="title">
            <x-auth.headline>{{ __('auth.reset_your_password') }}</x-auth.headline>
            <p>{{ __('auth.or') }}
                <x-ui.link href="{{ route('login') }}" class="font-bold">{{ __('auth.sign_in') }}</x-ui.link>
            </p>
        </x-slot>

        <x-auth.status :status="session('status')" />

        <x-auth.hint>{{ __('auth.reset_password_explanation') }}</x-auth.hint>

        <x-auth.form action="{{ route('password.email') }}" class="space-y-4 p-0 px-10 pb-10">
            <div>
                <x-ui.forms.input id="email" value="{{ old('email') }}" name="email" type="email" autocomplete="email" required>{{ __('auth.field_email') }}</x-ui.forms.input>
            </div>
            
            <div class="text-center">
                @csrf
                <x-ui.forms.button class="w-full">{{ __('auth.button_request_password') }}</x-ui.forms.button>
            </div>
        </x-auth.form>
    </x-auth.card>
</x-layout.auth>