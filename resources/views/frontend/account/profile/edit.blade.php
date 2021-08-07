<x-layout.account :title="__('account/profile.title')">
    <x-account.form :action="route('account.profile.update')" class="space-y-6">
        <div class="max-w-xs">
            <x-ui.forms.input id="name" value="{{ old('name', $user->name) }}" name="name" type="text" autocomplete="name" required>{{ __('account/profile.field_name') }}</x-ui.forms.input>
        </div>

        <div>
            @csrf
            @method('PUT')
            <x-ui.forms.button>{{ __('account/profile.button_change_profile') }}</x-ui.forms.button>
        </div>
    </x-account.form>
</x-layout.account>