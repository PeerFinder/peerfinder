<x-layout.account :title="__('account/profile.title')">
    <x-account.form :action="route('account.profile.update')" class="space-y-6">
        <div class="max-w-xs">
            <x-ui.forms.input id="name" value="{{ old('name', $user->name) }}" name="name" type="text"
                autocomplete="name" required>{{ __('account/profile.field_name') }}</x-ui.forms.input>
        </div>

        <div>
            <x-ui.forms.input id="slogan" value="{{ old('slogan', $user->slogan) }}" name="slogan" type="text">
                {{ __('account/profile.field_slogan') }}</x-ui.forms.input>
        </div>

        <div>
            <x-ui.forms.textarea id="about" value="{{ old('about', $user->about) }}" name="about" rows="5">
                {{ __('account/profile.field_about') }}</x-ui.forms.textarea>
        </div>

        <div>
            <x-ui.forms.input id="company" value="{{ old('company', $user->company) }}" name="company" type="text">
                {{ __('account/profile.field_company') }}</x-ui.forms.input>
        </div>

        <div>
            <x-ui.forms.input id="homepage" value="{{ old('homepage', $user->homepage) }}" name="homepage" type="text">
                {{ __('account/profile.field_homepage') }}</x-ui.forms.input>
        </div>

        @foreach ($platforms as $platform)
            <div>
                <x-ui.forms.input id="{{ $platform }}_profile" value="{{ old($platform . '_profile', $user->getAttribute($platform . '_profile')) }}" name="{{ $platform }}_profile" type="text">
                    {{ __('account/profile.field_' . $platform . '_profile') }}</x-ui.forms.input>
            </div>
        @endforeach

        <x-account.form-buttons>
            @csrf
            @method('PUT')
            <x-ui.forms.button>{{ __('account/profile.button_change_profile') }}</x-ui.forms.button>
        </x-account.form-buttons>
    </x-account.form>
</x-layout.account>
