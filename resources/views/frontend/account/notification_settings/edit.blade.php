<x-layout.account :title="__('account/notification_settings.title')">
    <x-account.form action="{{ route('account.notification_settings.update') }}" class="space-y-6 max-w-xs">
        
        
        <x-account.form-buttons>
            @csrf
            @method('PUT')
            <x-ui.forms.button>{{ __('account/notification_settings.button_change_settings') }}</x-ui.forms.button>
        </x-account.form-buttons>
    </x-account.form>
</x-layout.account>