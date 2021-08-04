<x-layout.auth title="{{ __('auth.please_verify_your_email') }}">
    <x-auth.card>
        <x-slot name="title">
            <x-auth.headline>{{ __('auth.please_verify_your_email') }}</x-auth.headline>
        </x-slot>


        @if (session('status') == 'verification-link-sent')
        <div class="bg-green-200 px-5 py-3">
            <p>{{ __('auth.verification_link_has_been_sent') }}</p>
        </div>
        @endif

        <div class="p-10">
            <p class="prose">{{ __('auth.verify_email_explanation') }}</p>
        </div>

        <form action="{{ route('verification.send') }}" method="post" class="px-10 pb-10">
            @csrf
            <x-ui.forms.button class="w-full">{{ __('auth.button_resend_verification_email') }}</x-ui.forms.button>

            <div class="mt-4 text-center">
                {{ __('auth.or') }}
                <x-ui.link href="{{ route('logout') }}">{{ __('auth.logout') }}</x-ui.link>
            </div>
        </form>

    </x-auth.card>
</x-layout.auth>