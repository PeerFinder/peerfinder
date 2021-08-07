<x-layout.account :title="__('account/email.title')">
    <x-account.form action="{{ route('account.email.update') }}" class="space-y-6 max-w-xs">
        <div>
        <x-ui.forms.input id="email" value="{{ old('email', $email) }}" name="email" type="email" autocomplete="email" required>{{ __('account/email.field_email') }}</x-ui.forms.input>
        </div>
        <div>
            @csrf
            @method('PUT')
            <x-ui.forms.button>{{ __('account/email.button_change_email') }}</x-ui.forms.button>
        </div>
    </x-account.form>
</x-layout.account>