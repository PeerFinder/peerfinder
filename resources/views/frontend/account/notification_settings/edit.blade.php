<x-layout.account :title="__('account/notification_settings.title')">
    <x-account.form action="{{ route('account.notification_settings.update') }}" class="space-y-6 max-w-xs">
        
        <div>
            <x-ui.forms.select :options="$statusOptions" name="UnreadMessages" :value="$statusValues['UnreadMessages']->value">{{ __('account/notification_settings.label_UnreadMessages') }}</x-ui.forms.select>
        </div>

        <div>
            <x-ui.forms.select :options="$statusOptions" name="NewGroupsNewsletter" :value="$statusValues['NewGroupsNewsletter']->value">{{ __('account/notification_settings.label_NewGroupsNewsletter') }}</x-ui.forms.select>
        </div>

        <x-account.form-buttons>
            @csrf
            @method('PUT')
            <x-ui.forms.button>{{ __('account/notification_settings.button_change_settings') }}</x-ui.forms.button>
        </x-account.form-buttons>
    </x-account.form>
</x-layout.account>