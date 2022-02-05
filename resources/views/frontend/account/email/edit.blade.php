<x-layout.account :title="__('account/email.title')">
    
    @if (!$user->hasVerifiedEmail())
        <div class="p-3 mb-5 rounded-lg shadow border bg-yellow-200 border-yellow-500">
            {!! __('account/email.mail_not_verified_notice', ['link' => route('verification.notice')]) !!}
        </div>
    @endif

    <x-account.form :action="route('account.email.update')" class="space-y-6">
        <div class="max-w-xs">
            <x-ui.forms.input id="email" value="{{ old('email', $user->email) }}" name="email" type="email" autocomplete="email" required>{{ __('account/email.field_email') }}</x-ui.forms.input>
        </div>

        <div class="text-sm">{{ __('account/email.mail_change_notice') }}</div>

        <x-account.form-buttons>
            @csrf
            @method('PUT')
            <x-ui.forms.button>{{ __('account/email.button_change_email') }}</x-ui.forms.button>
        </x-account.form-buttons>
    </x-account.form>
</x-layout.account>