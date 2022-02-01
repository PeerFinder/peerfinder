<x-layout.auth :title="__('auth.confirm_password_title')">
    <x-auth.card>
        <x-slot name="title">
            <x-auth.headline>{{ __('auth.confirm_your_password') }}</x-auth.headline>
        </x-slot>

        <x-auth.status :status="session('status')" />

        <x-auth.hint>{{ __('auth.confirm_password_explanation') }}</x-auth.hint>

        <x-auth.form action="{{ route('password.confirm') }}" class="space-y-4 p-0 px-10 pb-10">
            <div>
                <x-ui.forms.input id="password" name="password" type="password" autocomplete="current-password" required>{{ __('auth.field_password') }}</x-ui.forms.input>
            </div>

            <div class="text-center">
                @csrf
                <x-ui.forms.button class="w-full">{{ __('auth.button_confirm_password') }}</x-ui.forms.button>
            </div>
        </x-auth.form>
    </x-auth.card>
</x-layout.auth>