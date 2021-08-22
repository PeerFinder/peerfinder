<x-layout.account :title="__('account/account.title')">
    <x-account.form :action="route('account.account.destroy')" class="space-y-6">
        <p>{{ __('account/account.deletion_warning') }}</p>

        <div class="max-w-xs">
            <x-ui.forms.input id="password" name="password" type="password" autocomplete="current-password" required>{{ __('auth.field_password') }}</x-ui.forms.input>
        </div>

        <x-account.form-buttons>
            @csrf
            @method('DELETE')
            <x-ui.forms.button action="destroy">{{ __('account/account.button_delete_account') }}</x-ui.forms.button>
        </x-account.form-buttons>
    </x-account.form>
</x-layout.account>