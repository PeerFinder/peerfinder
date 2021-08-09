<x-layout.auth :title="__('auth.please_verify_your_email')">
    <x-auth.card>
        <x-slot name="title">
            <x-auth.headline>{{ __('auth.please_verify_your_email') }}</x-auth.headline>
            <p>{{ __('auth.or') }}
                <x-ui.link href="{{ route('logout') }}">{{ __('auth.logout') }}</x-ui.link>
            </p>            
        </x-slot>

        @if (session('status') == 'verification-link-sent')
        <x-auth.flash class="bg-green-200">
            <p>{{ __('auth.verification_link_has_been_sent') }}</p>
        </x-auth.flash>
        @endif

        @if (session('success'))
        <x-auth.flash class="bg-green-200">
            <p>{{ session('success') }}</p>
        </x-auth.flash>
        @endif

        <x-auth.hint>{{ __('auth.verify_email_explanation') }}</x-auth.hint>

        <x-auth.form action="{{ route('verification.send') }}" class="p-0 px-10 pb-10">
            @csrf
            <x-ui.forms.button class="w-full">{{ __('auth.button_resend_verification_email') }}</x-ui.forms.button>
            @if (Route::has('account.email.edit'))
                <div class="mt-4 text-center">
                    <x-ui.link href="{{ route('account.email.edit') }}">{{ __('auth.change_your_email') }}</x-ui.link>
                </div>
            @endif
        </x-auth.form>
    </x-auth.card>
</x-layout.auth>