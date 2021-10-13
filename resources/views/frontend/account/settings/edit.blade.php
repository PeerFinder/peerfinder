<x-layout.account :title="__('account/settings.title')">
    <x-account.form action="{{ route('account.settings.update') }}" class="space-y-6 max-w-xs">
        <div>
            <x-ui.forms.select name="locale" id="locale" :options="$locales" value="{{ old('locale', $user->locale) }}">{{ __('account/settings.field_locale') }}</x-ui.forms.select>
        </div>

        <div>
            <x-ui.forms.select name="timezone" id="timezone" :options="$timezones" value="{{ old('timezone', $user->timezone) }}">{{ __('account/settings.field_timezone') }}</x-ui.forms.select>
        </div>
        
        <x-account.form-buttons>
            @csrf
            @method('PUT')
            <x-ui.forms.button>{{ __('account/settings.button_change_settings') }}</x-ui.forms.button>
        </x-account.form-buttons>
    </x-account.form>
</x-layout.account>