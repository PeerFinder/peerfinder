<x-layout.account :title="__('account/password.title')">
    <x-account.form action="{{ route('account.password.update') }}" class="space-y-6 max-w-xs">
        <div>
            <x-ui.forms.input id="current_password" name="current_password" type="password" autocomplete="current-password" XXrequired>{{ __('account/password.field_current_password') }}</x-ui.forms.input>
        </div>
        <div>
            <x-ui.forms.input id="password" name="password" type="password" autocomplete="new-password" XXrequired>{{ __('account/password.field_new_password') }}</x-ui.forms.input>
        </div>
        <div>
            <x-ui.forms.input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" XXrequired>{{ __('account/password.field_new_password_confirmation') }}</x-ui.forms.input>
        </div>
        <div>
            @csrf
            @method('PUT')
            <x-ui.forms.button>{{ __('account/password.button_change_password') }}</x-ui.forms.button>
        </div>
    </x-account.form>
</x-layout.account>